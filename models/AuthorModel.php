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
}