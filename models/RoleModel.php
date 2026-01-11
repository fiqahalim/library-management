<?php

class RoleModel
{
    private $db;

    public function __construct()
    {
       $this->db = Database::getInstance()->getConnection();
    }

    // Get all roles (useful for admin dropdowns)
    public function getAllRoles()
    {
        $stmt = $this->db->query("SELECT * FROM roles");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleName($role_id)
    {
        $stmt = $this->db->prepare("SELECT role_name FROM roles WHERE role_id = ?");
        $stmt->execute([$role_id]);

        return $stmt->fetchColumn();
    }
}