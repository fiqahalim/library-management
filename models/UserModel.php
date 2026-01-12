<?php

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Create new users (admin/students)
    public function create($data)
    {
        $sql = "INSERT INTO Users (fullname, email, phone, student_no, username, password, role_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['fullname'], 
            $data['email'], 
            $data['phone'],
            $data['student_no'],
            $data['username'], 
            password_hash($data['password'], PASSWORD_DEFAULT), 
            $data['role_id'],
            $data['status'] ?? 'Active',
        ]);
    }

    // Update existing users
    public function update($id, $data)
    {
        $sql = "UPDATE users SET 
                fullname = ?, 
                email = ?, 
                phone = ?, 
                student_no = ?,
                username = ?,
                role_id = ?,
                status = ?
                WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['fullname'],
            $data['email'],
            $data['phone'],
            $data['student_no'],
            $data['username'],
            $data['role_id'],
            $data['status'],
            $id
        ]);
    }

    // Login check
    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /**
     * ADMIN METHOD: Count verified members
     * This excludes admins and unverified signups.
     */
    public function getTotalVerifiedMembers() {
        $sql = "SELECT COUNT(*) FROM users WHERE role_id = 2 AND is_verified = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    /**
     * ADMIN METHOD: Get all users for the management table
     * This joins with the roles table so we can see "Member" or "Admin" text.
     */
    public function getAllUsers() {
        $sql = "SELECT u.*, r.role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.role_id 
                ORDER BY u.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * ADMIN METHOD: Change user role (Promote/Demote)
     * This is how you change a Member (2) to an Admin (1).
     */
    public function updateUserRole($userId, $newRoleId) {
        $sql = "UPDATE users SET role_id = ? WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([$newRoleId, $userId]);
    }

    /**
     * Check if email exists
     * Used during registration to prevent duplicate accounts.
     */
    public function getByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if username exists
     */
    public function getByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllMembers()
    {
        $sql = "SELECT u.*, r.role_name, s.status as sub_status, pl.plan_name 
                FROM users u
                JOIN roles r ON u.role_id = r.role_id
                LEFT JOIN subscriptions s ON u.user_id = s.user_id AND s.status = 'Active'
                LEFT JOIN plans pl ON s.plan_id = pl.plan_id
                WHERE u.role_id = 2
                ORDER BY u.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMemberWithSubscription($id)
    {
        $sql = "SELECT u.*, s.sub_id, s.plan_id, s.status as sub_status, s.start_date, s.end_date, s.final_price, p.plan_name 
                FROM users u
                LEFT JOIN subscriptions s ON u.user_id = s.user_id
                LEFT JOIN plans p ON s.plan_id = p.plan_id
                WHERE u.user_id = ?
                ORDER BY s.start_date DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        // Note: If the user has subscriptions/payments, 
        // you might need to delete those first or use ON DELETE CASCADE in SQL.
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>