<?php

class AuthorModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $sql = "INSERT INTO authors (author_name, bio) 
                VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['author_name'],
            $data['bio'],
        ]);
    }

    public function getAllAuthors()
    {
        $sql = "SELECT a.*
                FROM authors a
                ORDER BY a.created_at DESC";
        $stmt = $this->db->query($sql);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM authors WHERE author_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE authors SET author_name = ?, bio = ? WHERE author_id = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$data['author_name'], $data['bio'], $id]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM authors WHERE author_id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([$id]);
    }
}