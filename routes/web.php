<?php

Route::get('/', function() {
    header("Location: " . APP_URL . "/auth/login");
    exit;
});

// Sanity check route
Route::get('/ping', function () {
    echo "Pong! Routing is working ✅";
});

/**
 * 2. Unified Authentication (Admin + Students)
 */
Route::group('/auth', function () {
    Route::get('/login', 'Auth@login');
    Route::post('/login', 'Auth@authenticate');
    Route::get('/register', 'Auth@register');
    Route::post('/register', 'Auth@registerProcess'); 
    Route::get('/logout', 'Auth@logout');
    
    // Password Recovery
    Route::get('/forgot-password', 'Auth@forgotPassword');
    Route::post('/forgot-password', 'Auth@forgotPasswordProcess');
    Route::get('/reset-password', 'Auth@resetPassword');
    Route::post('/reset-password', 'Auth@resetPasswordProcess');
    
    // Common Account Actions
    Route::get('/dashboard', 'Auth@dashboard');
    Route::get('/profile', 'Auth@profile');
    Route::post('/updateProfile', 'Auth@updateProfile');
});

/**
 * 3. Student Routes (Replaces HomeController)
 */
Route::group('/student', function () {
    Route::get('/books', 'Student@viewBooks');
    Route::get('/book/details', 'Student@bookDetails');
    Route::post('/borrow', 'Student@borrowBook');
    Route::get('/history', 'Student@viewHistory');
    Route::post('/cancel-request', 'Student@cancelBorrowRequest');
});

/**
 * 4. Admin Routes
 */
Route::group('/admin', function () {
    // Authors Management
    Route::get('/authors', 'Admin@author'); 
    Route::match(['GET', 'POST'], '/authors/create', 'Admin@createOrUpdateAuthor');
    Route::match(['GET', 'POST'], '/authors/edit/{id}', 'Admin@createOrUpdateAuthor');
    Route::get('/authors/delete/{id}', 'Admin@deleteAuthor');

    // Book Management
    Route::get('/books', 'Admin@book'); 
    Route::match(['GET', 'POST'], '/books/create', 'Admin@createOrUpdateBook');
    Route::match(['GET', 'POST'], '/books/edit/{id}', 'Admin@createOrUpdateBook');
    Route::get('/books/delete/{id}', 'Admin@deleteBook');
    Route::get('/books/view/{id}', 'Admin@viewBook');

    // Categories Management
    Route::get('/categories', 'Admin@category'); 
    Route::match(['GET', 'POST'], '/categories/create', 'Admin@createOrUpdateCategory');
    Route::match(['GET', 'POST'], '/categories/edit/{id}', 'Admin@createOrUpdateCategory');
    Route::get('/categories/delete/{id}', 'Admin@deleteCategory');

    // Student Management
    Route::get('/students', 'Admin@students');
    Route::match(['GET', 'POST'], '/students/create', 'Admin@createOrUpdateStudent');
    Route::match(['GET', 'POST'], '/students/edit/{id}', 'Admin@createOrUpdateStudent');
    Route::get('/students/delete/{id}', 'Admin@deleteStudent');
    Route::get('/students/view/{id}', 'Admin@viewStudent');

    // Borrowing/History Management
    Route::get('/borrowing', 'Admin@borrowRequests');
    Route::post('/borrowing/update', 'Admin@updateRequestStatus');
    Route::post('/borrowing/return', 'Admin@returnBook');
});