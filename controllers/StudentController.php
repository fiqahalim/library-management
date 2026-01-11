<?php

class StudentController extends Controller
{
    private $bookModel, $historyModel, $fineModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['is_logged_in']) || $_SESSION['role_id'] != 2) {
            header("Location: " . APP_URL . "/auth/login");
            exit;
        }

        $this->bookModel    = $this->model('BookModel');
        $this->historyModel = $this->model('HistoryModel');
        $this->fineModel    = $this->model('FineModel');
    }

    /**
     * Display the Student Dashboard
     */
    public function dashboard()
    {
        $userId = $_SESSION['user_id'];

        $data = [
            'title'             => 'Student Dashboard',
            'fullname'          => $_SESSION['fullname'],
            'availableBooks'    => $this->bookModel->getAvailableBooks(5), // Top 5 recent
            'myCurrentBorrows'  => $this->historyModel->getUserActiveLoans($userId),
            'totalFines'        => $this->fineModel->getUserTotalFines($userId)
        ];

        $this->view('student/dashboard', $data);
    }

    /**
     * View all books in the library
     */
    public function books()
    {
        $data = [
            'title' => 'Library Books',
            'books' => $this->bookModel->getAllBooks()
        ];

        $this->view('student/books', $data);
    }

    /**
     * View user's borrowing history and fines
     */
    public function history()
    {
        $userId = $_SESSION['user_id'];

        $data = [
            'title'   => 'My Borrowing History',
            'history' => $this->historyModel->getUserHistory($userId),
            'fines'   => $this->fineModel->getUserFines($userId)
        ];

        $this->view('student/history', $data);
    }
}