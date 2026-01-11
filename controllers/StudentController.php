<?php

class HomeController extends Controller
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

    // Home Page
    public function index()
    {
        // $plans = $this->planModel->getActivePlans();

        $this->view("home/index", [
            "title" => "Welcome to " . APP_NAME,
            // "plans" => $plans,
        ]);
    }

    // About Page
    public function about()
    {
        $this->view("home/about", [
            "title" => "About Us - " . APP_NAME
        ]);
    }

    // Contact Page
    public function contact()
    {
        $this->view("home/contact", [
            "title" => "Contact Us - " . APP_NAME
        ]);
    }

    // Plans Page
    public function plans()
    {
        $plans = $this->planModel->getActivePlans();
        $currentPlanId = null;

        // Check if member is logged in
        if (isset($_SESSION['user_id']) && $_SESSION['role_id'] == 2) {
            $activeSub = $this->subscriptionModel->getActiveSubscription($_SESSION['user_id']);
            if ($activeSub) {
                $currentPlanId = $activeSub['plan_id'];
            }
        }

        $this->view("home/plan", [
            "title" => "Plans - " . APP_NAME,
            "plans" => $plans,
            "currentPlanId" => $currentPlanId,
        ]);
    }

    // Handle the POST from the pricing page
    public function selectedPlans()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $plan_id = $_POST['plan_id'];
            $plan = $this->planModel->getPlanById($plan_id);

            if ($plan) {
                $_SESSION['enrollment_plan'] = $plan;

                if (isset($_SESSION['user_id'])) {
                    $this->view("home/enroll_details", [
                        "title" => "Confirm Plan - " . APP_NAME,
                        "plan" => $plan,
                        "user" => $this->userModel->getById($_SESSION['user_id'])
                    ]);
                } else {
                    $this->view("home/enroll_details", [
                    "title" => "Enrollment - " . APP_NAME,
                    "plan" => $plan,
                    "step" => 3
                    ]);
                }
            } else {
                header("Location: " . URLROOT . "/plans");
            }
        }
    }

    // Logic to check email (AJAX or Form Post)
    public function checkEmail()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $plan_id = $_POST['plan_id'];
            $user = $this->userModel->getByEmail($email);

            if ($user) {
                $existingSub = $this->subscriptionModel->getUserSubscriptionByPlan($user['user_id'], $plan_id);
                
                if ($existingSub) {
                    echo json_encode([
                        'status' => 'already_active', 
                        'message' => 'You already have an active subscription for this plan. You cannot purchase it again.'
                    ]);
                    return;
                }

                $anySub = $this->subscriptionModel->getActiveSubscription($user['user_id']);
                if ($anySub) {
                    echo json_encode([
                        'status' => 'switch_plan', 
                        'message' => 'You are currently on the ' . $anySub['plan_name'] . '. Proceeding will change your membership.'
                    ]);
                    return;
                }

                echo json_encode(['status' => 'exists', 'message' => 'Existing member found! Please login to confirm and pay.']);
            } else {
                echo json_encode(['status' => 'new', 'message' => 'New member! Please fill in your details below.']);
            }
        }
    }

    public function selectPayments()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_SESSION['enroll_data'] = $_POST;
            
            $plan = $this->planModel->getPlanById($_POST['plan_id']);
            
            $this->view("home/payment-details", [
                "title" => "Select Payment - " . APP_NAME,
                "plan" => $plan,
                "enroll_data" => $_POST
            ]);
        }
    }

    // Stripe API
    public function handleStripe()
    {
        if (!isset($_SESSION['enroll_data'])) {
            header("Location: " . APP_URL . "/plans");
            exit;
        }

        $_SESSION['enroll_data']['payment_method'] = 'Stripe';

        $enrollData = $_SESSION['enroll_data'];
        $planId = $enrollData['plan_id'];

        $stripeLinks = [
            1 => 'https://buy.stripe.com/test_8x28wQ5nefNp5Jxcnzew802', // Basic
            2 => 'https://buy.stripe.com/test_5kQbJ27vmcBdc7Vcnzew800', // Standard
            3 => 'https://buy.stripe.com/test_00w4gA02U0Svc7VfzLew803'  // Premium
        ];

        if (isset($stripeLinks[$planId])) {
            header("Location: " . $stripeLinks[$planId]);
            exit;
        } else {
            header("Location: " . APP_URL . "/plans");
            exit;
        }
    }

    // BillPlz API
    public function handleBillplz()
    {
        if (!isset($_SESSION['enroll_data'])) {
            header("Location: " . APP_URL . "/plans");
            exit;
        }

        $_SESSION['enroll_data']['payment_method'] = 'Billplz';
        session_write_close();

        $enrollData = $_SESSION['enroll_data'];
        $plan = $this->planModel->getPlanById($enrollData['plan_id']);

        $name = !empty($enrollData['full_name']) ? $enrollData['full_name'] : '';

        if (is_array($name)) {
            $name = $name[0];
        }

        $name = trim((string)$name);

        if (empty($name)) {
            $name = !empty($enrollData['username']) ? $enrollData['username'] : 'Gym Member';
        }
        
        $email = is_array($enrollData['email']) ? $enrollData['email'][0] : $enrollData['email'];
        $phone = is_array($enrollData['phone'] ?? '0123456789') ? $enrollData['phone'][0] : ($enrollData['phone'] ?? '0123456789');
        
        $temp_id = time(); 

        $bill = $this->createBillplzBill(
            (string)$name, 
            (string)$email, 
            (string)$phone, 
            $plan['monthly_fee'], 
            $temp_id
        );

        if (isset($bill['url'])) {
            header("Location: " . $bill['url']);
        } else {
            echo '<h3>Billplz Debug Info:</h3><pre>';
            print_r($bill);
            echo '</pre>';
            echo '<h4>Session Data Sent:</h4><pre>';
            print_r($enrollData);
            echo '</pre>';
            die("Billplz Error: Name was sent as '$name'");
        }
    }

    private function createBillplzBill($name, $email, $phone, $amount, $booking_id)
    {
        $api_key = 'd6f9bdfc-70fd-4f17-8129-7daa4302905f'; 
        $collection_id = 'h0avjdya'; 
        
        $url = 'https://www.billplz-sandbox.com/api/v3/bills';

        $data = [
            'collection_id' => $collection_id,
            'email'         => $email,
            'phone'         => $phone,
            'name'          => $name,
            'amount'        => round($amount * 100),
            'callback_url'  => APP_URL . '/payment-callback',
            'redirect_url'  => APP_URL . '/payment-success?gateway=billplz',
            'description'   => 'Gym Enrollment #' . $booking_id
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $api_key . ":");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function paymentSuccess()
    {
        if (!isset($_SESSION['enroll_data'])) {
            header("Location: " . APP_URL . "/plans");
            exit;
        }

        $enrollData = $_SESSION['enroll_data'];
        $finalMethod = $enrollData['payment_method'] ?? 'Stripe';
        $plan = $this->planModel->getPlanById($enrollData['plan_id']);
        $transactionId = 'TXN-' . strtoupper(uniqid());

        // 1. Process User (If new, create account)
        $user = $this->userModel->getByEmail($enrollData['email']);
        if (!$user) {
            $userData = [
                'role_id' => 2,
                'full_name' => $enrollData['full_name'],
                'username' => $enrollData['username'],
                'email' => $enrollData['email'],
                'password' => password_hash($enrollData['password'], PASSWORD_DEFAULT),
                'phone' => $enrollData['phone'],
                'v_code' => null
            ];
            $this->userModel->create($userData);
            $user = $this->userModel->getByEmail($enrollData['email']);
        }

        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['full_name'] = $user['full_name'];

        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$plan['duration_months']} months"));
        
        $subId = $this->subscriptionModel->create([
            'user_id' => $user['user_id'],
            'plan_id' => $plan['plan_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'final_price' => $plan['monthly_fee']
        ]);

        if ($subId) {
            $this->paymentModel->recordPayment($subId, $plan['monthly_fee'], $finalMethod, $transactionId);
        }

        unset($_SESSION['enroll_data']);
        unset($_SESSION['payment_choice']);
        
        $this->view('home/payment-success', [
            'title' => 'Success!',
            'message' => 'Welcome to the club! Your ' . $plan['plan_name'] . ' is now active.',
            'user_name' => $user['full_name'],
            'plan_name' => $plan['plan_name'],
            'is_logged_in' => true,
        ]);
    }
}