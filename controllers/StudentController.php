<?php

class StudentController extends Controller
{
    private $bookModel, $historyModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role_id'] != 2) {
            header("Location: " . APP_URL . "/auth/login");
            exit;
        }
        $this->bookModel = $this->model('BookModel');
        $this->historyModel = $this->model('HistoryModel');
    }

    public function dashboard()
    {
        $this->view('auth/dashboard', ['fullname' => $_SESSION['fullname']]);
    }

    // Requirement: View Available Books (Title, Author, Category)
    public function viewBooks()
    {
        $search = $_GET['search'] ?? '';
    
        if (!empty($search)) {
            $books = $this->bookModel->searchBooks($search);
        } else {
            $books = $this->bookModel->getAllBooks();
        }

        $data = [
            'title' => 'Available Books',
            'books' => $books,
            'search' => $search,
        ];
        $this->view('student/books/index', $data);
    }

    // Submit borrow request
    public function borrowBook()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $bookId = $_POST['book_id'];
            $userId = $_SESSION['user_id'];
            
            if ($this->historyModel->createRequest($userId, $bookId)) {
                Flash::set('success', 'Request submitted! Please pick up the book at the counter.');
            }
            header("Location: " . APP_URL . "/student/history");
        }
    }

    // Requirement: Allow cancellation
    public function cancelBorrowRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $borrowId = $_POST['borrow_id'];
            $this->historyModel->updateStatus($borrowId, 'Cancelled');
            Flash::set('success', 'Request cancelled.');
            header("Location: " . APP_URL . "/student/history");
        }
    }

    public function viewHistory()
    {
        $data = [
            'history' => $this->historyModel->getUserHistory($_SESSION['user_id'])
        ];
        $this->view('student/history/index', $data);
    }
}