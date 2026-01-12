<?php

class BookModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $sql = "INSERT INTO Books (book_name, book_description, publish_date, category_id, author_id, status, availability_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['book_name'],
            $data['book_description'],
            $data['publish_date'],
            $data['category_id'],
            $data['author_id'],
            $data['status'],
            $data['availability_status'],
        ]);
    }

    // Update existing Books
    public function update($id, $data)
    {
        $sql = "UPDATE Books SET 
                book_name = ?,
                book_description = ?,
                publish_date = ?,
                category_id = ?,
                author_id = ?,
                status = ?,
                availability_status = ?
                WHERE book_id = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['book_name'], 
            $data['book_description'],
            $data['publish_date'],
            $data['category_id'],
            $data['author_id'],
            $data['status'],
            $data['availability_status'],
            $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Books WHERE book_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Join methods category_id and author_id FK (Get Book details)
    public function bookDetails()
    {
        $sql = "SELECT 
                    b.book_id,
                    b.book_name,
                    b.book_description,
                    b.publish_date,
                    b.status,
                    b.availability_status,
                    c.category_name,
                    a.author_name
                FROM Books b
                INNER JOIN Categories c ON b.category_id = c.category_id
                INNER JOIN Authors a ON b.author_id = a.author_id
                ORDER BY b.book_id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get book count by category
    public function getBookCountByCategory()
    {
        $sql = "SELECT 
                    c.category_name,
                    COUNT(b.book_id) as book_count
                FROM Categories c
                LEFT JOIN Books b ON c.category_id = b.category_id
                GROUP BY c.category_id
                ORDER BY book_count DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get available books
    public function getAvailableBooks()
    {
        $sql = "SELECT 
                    b.book_name,
                    a.author_name,
                    c.category_name
                FROM Books b
                INNER JOIN Authors a ON b.author_id = a.author_id
                INNER JOIN Categories c ON b.category_id = c.category_id
                WHERE b.availability_status = 'available'
                ORDER BY b.book_name";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}