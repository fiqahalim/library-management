<?php

class AdminController extends Controller
{
    private $db, $userModel, $paymentModel, $subscriptionModel, $planModel, $roleModel, $promoCodeModel;

    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
        $this->paymentModel = $this->model('PaymentModel');
        $this->subscriptionModel = $this->model('SubscriptionModel');
        $this->planModel = $this->model('PlanModel');
        $this->roleModel = $this->model('RoleModel');
        $this->promoCodeModel = $this->model('PromoCodeModel');

        $this->db = Database::getInstance()->getConnection();
    }

    // MEMBER MANAGEMENT
    public function members()
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $members = $this->userModel->getAllMembers(); // checking model, get sql query

        $this->view('admin/members/index', [
            'members' => $members,
            'full_name' => $_SESSION['full_name'],
            'role_id' => $_SESSION['role_id']
        ]);
    }

    // Create and Update member
    public function createOrUpdateMember($id = null)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $data = [
            'user' => null,
            'roles' => $this->roleModel->getAllRoles(),
            'plans' => $this->planModel->getAllPlans(),
            'title' => $id ? 'Edit Member' : 'Create Member'
        ];

        if ($id) {
            $data['user'] = $this->userModel->getMemberWithSubscription($id);
            if (!$data['user']) {
                die("Member not found.");
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userData = [
                'full_name' => trim($_POST['full_name']),
                'email'     => trim($_POST['email']),
                'phone'     => trim($_POST['phone']),
                'username'  => trim($_POST['username']),
                'role_id'   => $_POST['role_id']
            ];

            if ($id) {
                if ($this->userModel->update($id, $userData)) {
                    header('Location: ' . APP_URL . '/admin/members?success=updated');
                    exit;
                }
            } else {
                $userData['password'] = $_POST['password']; 
                if ($this->userModel->create($userData)) {
                    header('Location: ' . APP_URL . '/admin/members?success=created');
                    exit;
                }
            }
        }

        $this->view('admin/members/create-edit', $data);
    }

    // View member
    public function viewMember($id)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $user = $this->userModel->getById($id);
        $subscriptions = $this->subscriptionModel->getByUser($id);

        header('Content-Type: application/json');
        echo json_encode([
            'user' => $user,
            'subscriptions' => $subscriptions
        ]);
        exit;
    }

    // Delete a Member
    public function deleteMember($id)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        if ($this->userModel->delete($id)) {
            header('Location: ' . APP_URL . '/admin/members?success=deleted');
        } else {
            header('Location: ' . APP_URL . '/admin/members?error=failed');
        }
        exit;
    }

    // PLAN MANAGEMENTS
    public function plans()
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $plans = $this->planModel->getAllPlans();

        $this->view('admin/plans/index', [
            'plans' => $plans,
            'title' => 'Manage Membership Plans'
        ]);
    }

    public function createOrUpdatePlan($id = null)
    {
        $data = [
            'plan' => null,
            'title' => $id ? 'Edit Plan' : 'Create New Plan'
        ];

        if ($id) {
            $data['plan'] = $this->planModel->getPlanById($id);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $planData = [
                'plan_name'         => trim($_POST['plan_name']),
                'duration_months'   => (int)$_POST['duration_months'],
                'monthly_fee'       => (float)$_POST['monthly_fee'],
                'is_trial_plan'     => isset($_POST['is_trial_plan']) ? 1 : 0,
                'features'          => $_POST['features'],
            ];

            if ($id) {
                if ($this->planModel->updatePlan($id, $planData)) {
                    header('Location: ' . APP_URL . '/admin/plans');
                    exit;
                }
            } else {
                if ($this->planModel->createPlan($planData)) {
                    header('Location: ' . APP_URL . '/admin/plans');
                    exit;
                }
            }
        }

        $this->view('admin/plans/create-update', $data);
    }

    // PAYMENT MANAGEMENT
    public function payments()
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        // Call the correct model and method
        $payments = $this->paymentModel->getAllPayments();

        $this->view('admin/payments/index', [
            'payments' => $payments, // Pass it as 'payments'
            'title' => 'Manage Membership Payments'
        ]);
    }

    // Delete a Membership Plan
    public function deletePlan($id)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        if ($this->planModel->delete($id)) {
            header('Location: ' . APP_URL . '/admin/plans?success=deleted');
        } else {
            header('Location: ' . APP_URL . '/admin/plans?error=failed');
        }
        exit;
    }
}