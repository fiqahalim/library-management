<?php

class AdminController extends Controller
{
    private $db, $userModel, $roleModel, $authorModel;

    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
        $this->roleModel = $this->model('RoleModel');
        $this->authorModel = $this->model('AuthorModel');

        $this->db = Database::getInstance()->getConnection();
    }

    // STUDENTS MANAGEMENT
    public function author()
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $authors = $this->authorModel->getAllAuthors();

        $this->view('admin/authors/index', [
            'authors' => $authors,
            'fullname' => $_SESSION['fullname'],
            'role_id' => $_SESSION['role_id']
        ]);
    }

    // STUDENTS MANAGEMENT
    public function students()
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

    // Create and Update student
    public function createOrUpdateStudent($id = null)
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

    // View students
    public function viewStudent($id)
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

    // Delete a student
    public function deleteStudent($id)
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
}