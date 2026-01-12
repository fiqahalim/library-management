<?php

class HistoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $sql = "INSERT INTO Histories (borrow_date, due_date, return_date, status) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['borrow_date'],
            $data['due_date'],
            $data['return_date'],
            $data['status'],
        ]);
    }

    // Update existing Histories
    public function update($id, $data)
    {
        $sql = "UPDATE Histories SET 
                borrow_date = ?,
                due_date = ?,
                return_date = ?,
                status = ?
                WHERE history_id = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['borrow_date'], 
            $data['due_date'],
            $data['return_date'],
            $data['status'],
            $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM Histories WHERE history_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Join methods book_id, category_id and user_id FK (Get History details)
    public function historyDetails()
    {
        $sql = "SELECT 
                    h.history_id,
                    h.borrow_date,
                    h.due_date,
                    h.return_date,
                    h.status,
                    b.book_name,
                    c.category_name,
                    u.fullname
                FROM Histories h
                INNER JOIN Books b ON h.book_id = b.book_id
                INNER JOIN Categories c ON h.category_id = c.category_id
                INNER JOIN Users u ON h.user_id = u.user_id
                ORDER BY h.history_id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get History by User
    public function getHistoryByUser()
    {
        $sql = "SELECT 
                    h.history_id,
                    h.borrow_date,
                    h.due_date,
                    h.return_date,
                    h.status,
                    b.book_name
                FROM Histories h
                INNER JOIN Books b ON h.book_id = b.book_id
                WHERE h.user_id
                ORDER BY h.borrow_date DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get history status (pending, approved, returned, cancelled)
    // Part ni tak siap lagi
    public function getHistoryStatus()
    {
        $sql = "SELECT 
                    h.history_id,
                    h.due_date,
                    b.book_name,
                    u.fullname
                FROM Histories h
                INNER JOIN Books b ON h.book_id = b.book_id
                INNER JOIN Categories c ON h.category_id = c.category_name
                INNER JOIN Users u ON h.user_id = u.fullname
                WHERE h.user_id = 'returned'
                AND h.due_date < CURDATE()";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}