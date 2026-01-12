<?php

class CategoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $sql = "INSERT INTO Categories (category_name, category_type, status) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['category_name'], 
            $data['category_type'], 
            $data['status'],
        ]);
    }

    // Update existing Categories
    public function update($id, $data)
    {
        $sql = "UPDATE Categories SET 
                category_name = ?, 
                category_type = ?, 
                status = ?
                WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['category_name'],
            $data['category_type'],
            $data['status'],
            $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Categories WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Get all categories for dropdowns and lists
    public function getAllCategories()
    {
        $sql = "SELECT * FROM Categories ORDER BY category_name ASC";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single category by ID (needed for Edit mode)
    public function getById($id)
    {
        $sql = "SELECT * FROM Categories WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}