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
    Route::get('/dashboard', 'Student@dashboard');
    Route::get('/books', 'Student@viewBooks');
    Route::post('/borrow', 'Student@borrowBook');
    Route::get('/history', 'Student@viewHistory');
    Route::post('/cancel-request', 'Student@cancelBorrowRequest');
});

/**
 * 4. Admin Routes
 */
Route::group('/admin', function () {
    // Student/Member Management
    Route::get('/members', 'Admin@members');
    Route::match(['GET', 'POST'], '/members/create', 'Admin@createOrUpdateMember');
    Route::match(['GET', 'POST'], '/members/edit/{id}', 'Admin@createOrUpdateMember');
    Route::get('/members/delete/{id}', 'Admin@deleteMember');
    Route::get('/members/view/{id}', 'Admin@viewMember');

    // Book Management (Uses 'plans' logic from your template)
    Route::get('/books', 'Admin@plans'); 
    Route::match(['GET', 'POST'], '/books/create', 'Admin@createOrUpdatePlan');
    Route::match(['GET', 'POST'], '/books/edit/{id}', 'Admin@createOrUpdatePlan');
    Route::get('/books/delete/{id}', 'Admin@deletePlan');

    // Borrowing/History Management
    Route::get('/borrow-requests', 'Admin@payments');
    Route::post('/borrow-requests/update', 'Admin@updateStatus');
});