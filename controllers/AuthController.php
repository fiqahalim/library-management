<?php

class AuthController extends Controller
{
    private $userModel, $roleModel;

    // Constructor to initialize the model
    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
        $this->roleModel = $this->model('RoleModel');
    }

    public function login()
    {
        $this->view('auth/login');
    }

    public function authenticate()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $loginInput = $_POST['username'] ?? ''; 
        $password = $_POST['password'] ?? '';

        $userModel = $this->model('UserModel');
        $user = filter_var($loginInput, FILTER_VALIDATE_EMAIL) 
                ? $userModel->getByEmail($loginInput) 
                : $userModel->getByUsername($loginInput);

        if ($user && password_verify($password, $user['password'])) {
            if (!$user['is_verified']) {
                Flash::set('error', "Please verify your account first.");
                header("Location: " . APP_URL . "/auth/verify");
                exit;
            }
            
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role_id'] = $user['role_id']; 
            $_SESSION['full_name'] = $user['full_name'];

            Flash::set('success', "Welcome back, " . $user['full_name']);

            header("Location: " . APP_URL . "/auth/dashboard");
            exit;
        }

        Flash::set('error', "Invalid credentials.");
        header("Location: " . APP_URL . "/auth/login");
        exit;
    }

    public function register()
    {
        $this->view('auth/register');
    }

    public function registerProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . APP_URL . "/auth/register");
            exit;
        }

        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $student_no = trim($_POST['student_no'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // 1. Validation
        if (empty($fullname) || empty($email) || empty($phone) || empty($student_no) || empty($username) || empty($password)) {
            Flash::set('error', "All fields are required.");
            header("Location: " . APP_URL . "/auth/register");
            exit;
        }

        // 2. Check if Email already exists in unified users table
        $existingUser = $this->userModel->getByEmail($email);
        if ($existingUser) {
            Flash::set('error', "Email already registered.");
            header("Location: " . APP_URL . "/auth/register");
            exit;
        }

        // 3. Prepare Registration Data
        $data = [
            'fullname'      => $fullname,
            'email'         => $email,
            'phone'         => $phone,
            'student_no'    => $student_no,
            'username'      => $username,
            'password'      => $password,
            'role_id'       => 2, // HARDCODED: 2 is for 'Member'
            'status'        => 1,
        ];

        // 4. Create User (Using the Model we built earlier)
        if ($this->userModel->create($data)) {
            Flash::set('success', "Registration success. Hooray!");
            header("Location: " . APP_URL . "/auth/verify");
        } else {
            Flash::set('error', "Registration failed. Please try again.");
            header("Location: " . APP_URL . "/auth/register");
        }
        exit;
    }

    // Dashboard for users (admin/students)
    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['is_logged_in'])) {
            header("Location: " . APP_URL . "/auth/login");
            exit;
        }

        $roleId = $_SESSION['role_id'];
        $userId = $_SESSION['user_id'];
        
        $data = [
            'fullname' => $_SESSION['fullname'] ?? '',
            'role_id'   => $roleId,
            'activeSubscription' => null,
            'pastHistory' => [],
            'availablePlans' => [],
        ];

        // // --- ADMIN DASHBOARD ---
        // if ($roleId == 1) {
        //     $subModel = $this->model('SubscriptionModel');
        //     $payModel = $this->model('PaymentModel');
        //     $userModel = $this->model('UserModel');

        //     $data['totalMembers'] = $userModel->getTotalVerifiedMembers(); 
        //     $data['activeSubs']   = $subModel->countActiveSubscriptions();
        //     $data['totalRevenue'] = $payModel->getTotalRevenue();
        //     $data['revenueChartValues'] = $payModel->getMonthlyRevenue(date('Y'));
        //     $data['recentPayments'] = $payModel->getRecentPayments(5);

        //     $this->view('auth/dashboard', $data);
        //     return;
        // }

        // // --- MEMBER DASHBOARD ---
        // if ($roleId == 2) {
        //     $mySubscriptions = $this->subscriptionModel->getByUser($userId);
            
        //     $currentDate = date('Y-m-d');

        //     foreach ($mySubscriptions as $sub) {
        //         if ($sub['status'] === 'Active' && $sub['end_date'] >= $currentDate) {
        //             $data['activeSubscription'] = $sub;
        //         }
        //         $data['pastHistory'][] = $sub;
        //     }

        //     $data['availablePlans'] = $this->planModel->getAllPlans();

        //     $this->view('auth/dashboard', $data);
        //     return;
        // }

        echo "Access Denied: Invalid user role.";
    }

    public function profile()
    {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['auth_type'])) {
            header("Location: " . APP_URL . "/auth/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['auth_type'];
        $data = [];

        if ($role === 'admin') {
            $adminModel = $this->model('AdminModel');
            $idToFetch = $_SESSION['admin_id'] ?? $userId;
            $data = $adminModel->getAdminProfile($idToFetch);
        } elseif ($role === 'customer') {
            $customerModel = $this->model('CustomerModel');
            $idToFetch = $_SESSION['customer_id'] ?? $userId;
            $data = $customerModel->getProfile($idToFetch);
        } else {
            echo "Access denied.";
            exit;
        }

        // Pass role and any flash messages to the view
        $data['role'] = $role;
        $data['success'] = Flash::get('success');
        $data['error'] = Flash::get('error');

        $this->view('profile', $data);
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header("Location: " . APP_URL . "/auth/login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['auth_type'] ?? 'customer';

        $fullName = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';

        try {
            if ($role === 'admin') {
                $adminModel = $this->model('AdminModel');
                $adminId = $_SESSION['admin_id'] ?? $userId;
                
                $adminModel->updateProfileDetails($adminId, $username, $email);
                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $adminModel->updateAdminPassword($adminId, $hashed);
                }
            } else {
                $customerModel = $this->model('CustomerModel');
                $customerId = $_SESSION['customer_id'] ?? $userId;
                
                $customerModel->updateProfileDetails($customerId, $username, $fullName, $email, $phone); 

                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $customerModel->updateCustomerPassword($customerId, $hashed);
                }
            }

            Flash::set('success', "Profile details updated successfully!");
            
        } catch (\Exception $e) {
            Flash::set('error', "Update failed: " . $e->getMessage());
        }
        
        header("Location: " . APP_URL . "/profile");
        exit;
    }

    private function uploadImage($file)
    {
        $targetDir = __DIR__ . '/../public/uploads/';
        $targetFile = $targetDir . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $targetFile);
        
        return '/uploads/' . basename($file['name']);
    }

    public function logout()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Clear all session variables
        $_SESSION = [];

        // Destroy the session cookie if it exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();

        // Flash message and redirect
        Flash::set('success', "Logged out successfully.");
        header("Location: " . APP_URL . "/auth/login");
        exit;
    }

    // Show forgot password form
    public function forgotPassword()
    {
        $this->view('auth/forgot-password');
    }

    // Handle forgot password submission
    public function forgotPasswordProcess()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = trim($_POST['email'] ?? '');

        if (!$email) {
            Flash::set('error', 'Please enter your username or email.');
            header("Location: " . APP_URL . "/auth/forgot-password");
            exit;
        }

        $passwordResetModel = $this->model('PasswordResetModel');

        // Check Admin first
        $user = $this->adminModel->getByEmail($email);
        $userType = 'admin';

        // If not admin, check customer
        if (!$user) {
            $user = $this->customerModel->getByEmail($email, false);
            $userType = 'customer';
        }

        if (!$user) {
            Flash::set('error', 'No account found with that username/email.');
            header("Location: " . APP_URL . "/auth/forgot-password");
            exit;
        }

        // Generate token
        $token = bin2hex(random_bytes(16));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save token
        $passwordResetModel->saveToken($user['id'], $userType, $token, $expiresAt);

        // Send reset link (email here, can replace with WhatsApp)
        $resetLink = APP_URL . "/auth/reset-password?token=$token";
        $subject = "Password Reset Request";
        $message = "Click this link to reset your password (valid 1 hour): $resetLink";

        if (!empty($user['email'])) {
            mail($user['email'], $subject, $message);
        }

        Flash::set('success', 'Password reset link has been sent.');
        header("Location: " . APP_URL . "/auth/login");
        exit;
    }

    // Show reset password form
    public function resetPassword()
    {
        $token = $_GET['token'] ?? '';

        if (!$token) {
            Flash::set('error', 'Invalid password reset token.');
            header("Location: " . APP_URL . "/auth/login");
            exit;
        }

        $passwordResetModel = $this->model('PasswordResetModel');
        $resetRecord = $passwordResetModel->getByToken($token);

        if (!$resetRecord || strtotime($resetRecord['expires_at']) < time()) {
            Flash::set('error', 'Reset token is invalid or expired.');
            header("Location: " . APP_URL . "/auth/forgot-password");
            exit;
        }

        $this->view('auth/reset_password', ['token' => $token]);
    }

    // Process reset password submission
    public function resetPasswordProcess()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!$token || !$password || !$confirmPassword) {
            Flash::set('error', 'All fields are required.');
            header("Location: " . APP_URL . "/auth/reset-password?token=$token");
            exit;
        }

        if ($password !== $confirmPassword) {
            Flash::set('error', 'Passwords do not match.');
            header("Location: " . APP_URL . "/auth/reset-password?token=$token");
            exit;
        }

        $passwordResetModel = $this->model('PasswordResetModel');
        $resetRecord = $passwordResetModel->getByToken($token);

        if (!$resetRecord || strtotime($resetRecord['expires_at']) < time()) {
            Flash::set('error', 'Reset token is invalid or expired.');
            header("Location: " . APP_URL . "/auth/forgot-password");
            exit;
        }

        // Update password in the correct table
        $passwordResetModel->updatePassword($resetRecord['user_id'], $password, $resetRecord['user_type']);
        $passwordResetModel->deleteToken($token);

        Flash::set('success', 'Password has been reset successfully.');
        header("Location: " . APP_URL . "/auth/login");
        exit;
    }
}