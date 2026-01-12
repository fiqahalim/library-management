<?php

class AdminController extends Controller
{
    private $db, $authorModel, $bookModel, $categoryModel, $userModel, $roleModel;

    public function __construct()
    {
        $this->authorModel = $this->model('AuthorModel');
        $this->bookModel = $this->model('BookModel');
        $this->categoryModel = $this->model('CategoryModel');
        $this->userModel = $this->model('UserModel');
        $this->roleModel = $this->model('RoleModel');

        $this->db = Database::getInstance()->getConnection();
    }

    // AUTHORS MANAGEMENT
    public function author()
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $authors = $this->authorModel->getAllAuthors();

        $this->view('admin/authors/index', [
            'authors' => $authors,
            'fullname' => $_SESSION['fullname'],
            'role_id' => $_SESSION['role_id']
        ]);
    }

    public function createOrUpdateAuthor($id = null)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $data = [
            'author' => null,
            'title'  => $id ? 'Edit Author' : 'Create Author'
        ];

        // If editing, fetch existing data
        if ($id) {
            $data['author'] = $this->authorModel->getById($id);
            if (!$data['author']) {
                Flash::set('error', 'Author not found.');
                header('Location: ' . APP_URL . '/admin/authors');
                exit;
            }
        }

        // Handle Form Submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $authorData = [
                'author_name' => trim($_POST['author_name']),
                'bio'         => trim($_POST['bio']),
            ];

            if ($id) {
                if ($this->authorModel->update($id, $authorData)) {
                    Flash::set('success', 'Author updated successfully!');
                }
            } else {
                if ($this->authorModel->create($authorData)) {
                    Flash::set('success', 'Author created successfully!');
                }
            }
            header('Location: ' . APP_URL . '/admin/authors');
            exit;
        }

        $this->view('admin/authors/create-edit', $data);
    }

    public function deleteAuthor($id)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        if ($this->authorModel->delete($id)) {
            Flash::set('success', 'Author deleted successfully.');
        } else {
            Flash::set('error', 'Unable to delete author. They may be linked to existing books.');
        }

        header('Location: ' . APP_URL . '/admin/authors');
        exit;
    }

    // BOOKS MANAGEMENT
    public function book()
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $books = $this->bookModel->getAllBooks();

        $this->view('admin/books/index', [
            'books' => $books,
            'fullname' => $_SESSION['fullname'],
            'role_id' => $_SESSION['role_id']
        ]);
    }

    public function createOrUpdateBook($id = null)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        // Fetch dependencies for dropdowns
        $authors = $this->authorModel->getAllAuthors();
        $categories = $this->categoryModel->getAllCategories();

        $data = [
            'book' => null,
            'authors' => $authors,
            'categories' => $categories,
            'title'  => $id ? 'Edit Book' : 'Create Book'
        ];

        if ($id) {
            $data['book'] = $this->bookModel->getById($id);
            if (!$data['book']) {
                Flash::set('error', 'Book not found.');
                header('Location: ' . APP_URL . '/admin/books');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $bookData = [
                'book_name'           => trim($_POST['book_name']),
                'book_description'    => trim($_POST['book_description']),
                'publish_date'        => $_POST['publish_date'],
                'category_id'         => $_POST['category_id'],
                'author_id'           => $_POST['author_id'],
                'status'              => $_POST['status'],
                'availability_status' => $_POST['availability_status'],
            ];

            if ($id) {
                $result = $this->bookModel->update($id, $bookData);
                $msg = 'Book updated successfully!';
            } else {
                $result = $this->bookModel->create($bookData);
                $msg = 'Book created successfully!';
            }

            if ($result) {
                Flash::set('success', $msg);
                header('Location: ' . APP_URL . '/admin/books');
                exit;
            }
        }

        $this->view('admin/books/create-edit', $data);
    }

    // Delete book
    public function deleteBook($id)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        if ($this->bookModel->delete($id)) {
            Flash::set('success', 'Book deleted successfully.');
        } else {
            Flash::set('error', 'Unable to delete book.');
        }

        header('Location: ' . APP_URL . '/admin/books');
        exit;
    }

    // CATEGORIES MANAGEMENT
    public function category()
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $categories = $this->categoryModel->getAllCategories();

        $this->view('admin/categories/index', [
            'categories' => $categories,
            'fullname' => $_SESSION['fullname'],
            'role_id' => $_SESSION['role_id']
        ]);
    }

    public function createOrUpdateCategory($id = null)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $data = [
            'category' => null,
            'title'    => $id ? 'Edit Category' : 'Create Category'
        ];

        if ($id) {
            $data['category'] = $this->categoryModel->getById($id);
            if (!$data['category']) {
                Flash::set('error', 'Category not found.');
                header('Location: ' . APP_URL . '/admin/categories');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $catData = [
                'category_name' => trim($_POST['category_name']),
                'category_type' => trim($_POST['category_type']),
                'status'        => $_POST['status']
            ];

            if ($id) {
                $result = $this->categoryModel->update($id, $catData);
                $msg = 'Category updated successfully!';
            } else {
                $result = $this->categoryModel->create($catData);
                $msg = 'Category created successfully!';
            }

            if ($result) {
                Flash::set('success', $msg);
                header('Location: ' . APP_URL . '/admin/categories');
                exit;
            }
        }

        $this->view('admin/categories/create-edit', $data);
    }

    // Delete category
    public function deleteCategory($id)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        if ($this->categoryModel->delete($id)) {
            Flash::set('success', 'Category deleted successfully.');
        } else {
            Flash::set('error', 'Unable to delete the category.');
        }

        header('Location: ' . APP_URL . '/admin/categories');
        exit;
    }

    // STUDENTS MANAGEMENT
    public function students()
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $users = $this->userModel->getAllUsers();

        $this->view('admin/students/index', [
            'users' => $users,
            'fullname' => $_SESSION['fullname'],
            'role_id' => $_SESSION['role_id'],
        ]);
    }

    // Create and Update student
    public function createOrUpdateStudent($id = null)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        $data = [
            'user' => null,
            'roles' => $this->roleModel->getAllRoles(),
            'title' => $id ? 'Edit Student' : 'Create Student'
        ];

        if ($id) {
            $data['user'] = $this->userModel->getAll();
            if (!$data['user']) {
                die("Student not found.");
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userData = [
                'fullname' => trim($_POST['fullname']),
                'email'     => trim($_POST['email']),
                'phone'     => trim($_POST['phone']),
                'student_no'=> trim($_POST['student_no']),
                'username'  => trim($_SESSION['username']),
                'role_id'   => $_SESSION['role_id']
            ];

            if ($id) {
                if ($this->userModel->update($id, $userData)) {
                    header('Location: ' . APP_URL . '/admin/students');
                    exit;
                }
            } else {
                $userData['password'] = $_POST['password']; 
                if ($this->userModel->create($userData)) {
                    header('Location: ' . APP_URL . '/admin/students');
                    exit;
                }
            }
        }

        $this->view('admin/students', $data);
    }

    // View students
    public function viewStudent($id)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $user = $this->userModel->getById($id);
        

        header('Content-Type: application/json');
        echo json_encode([
            'user' => $user,
            
        ]);
        exit;
    }

    // Delete a student
    public function deleteStudent($id)
    {
        if ($_SESSION['role_id'] != 1) {
            header('Location: ' . APP_URL . '/auth/dashboard');
            exit;
        }

        if ($this->userModel->delete($id)) {
            header('Location: ' . APP_URL . '/admin/students');
        } else {
            header('Location: ' . APP_URL . '/admin/students');
        }
        exit;
    }
}