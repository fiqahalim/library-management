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
    public function getUserHistory($userId)
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
                WHERE h.user_id = ?
                ORDER BY h.borrow_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createRequest($userId, $bookId)
    {
        // 1. Get the category_id from the Books table first
        $sqlBook = "SELECT category_id FROM Books WHERE book_id = ?";
        $stmtBook = $this->db->prepare($sqlBook);
        $stmtBook->execute([$bookId]);
        $book = $stmtBook->fetch(PDO::FETCH_ASSOC);
        $categoryId = $book['category_id'];

        // 2. Set dates (Due date is 14 days from today)
        $borrowDate = date('Y-m-d H:i:s');
        $dueDate = date('Y-m-d H:i:s', strtotime('+14 days'));

        // 3. Insert into Histories table
        $sql = "INSERT INTO Histories (book_id, category_id, user_id, borrow_date, due_date, status) 
                VALUES (?, ?, ?, ?, ?, 'Pending')";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $bookId,
            $categoryId,
            $userId,
            $borrowDate,
            $dueDate
        ]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE Histories SET status = ? WHERE history_id = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$status, $id]);
    }
}