<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = !empty($_SESSION['is_logged_in']);
$role_id = $isLoggedIn ? (int)$_SESSION['role_id'] : null;
$isAdmin = ($role_id === 1);

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base_path = parse_url(APP_URL, PHP_URL_PATH);
$current_page = trim(str_replace($base_path, '', $request_uri), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= APP_NAME ?></title>
    <link href="<?= APP_URL ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?= APP_URL ?>/assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>
<body id="<?= $isLoggedIn ? 'page-top' : '' ?>" class="<?= !$isLoggedIn ? 'bg-gradient-primary' : '' ?>">

    <?php if ($isLoggedIn): ?>
        <div id="wrapper">
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= APP_URL ?>">
                    <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-book"></i></div>
                    <div class="sidebar-brand-text mx-3">Library<sup>Sys</sup></div>
                </a>
                <hr class="sidebar-divider my-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/auth/dashboard">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <hr class="sidebar-divider">
                <?php if ($isAdmin): ?>
                    <div class="sidebar-heading">Books Managements</div>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/admin/authors">
                            <i class="fas fa-user-secret"></i>
                            <span>Author</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/admin/books">
                            <i class="fas fa-book"></i>
                            <span>Books</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/admin/categories">
                            <i class="fas fa-folder-open"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_URL ?>/admin/members">
                            <i class="fas fa-users"></i>
                            <span>Students</span>
                        </a>
                    </li>
                <?php else: ?>
                <div class="sidebar-heading">Library Services</div>
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/books/availability">
                        <i class="fas fa-fw fa-search"></i>
                        <span>Book Availability</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/books/borrow">
                        <i class="fas fa-fw fa-hand-holding-medical"></i>
                        <span>Borrow Books</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_URL ?>/student/history">
                        <i class="fas fa-fw fa-history"></i>
                        <span>Browsing History</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['fullname'] ?? 'User' ?></span>
                                <img class="img-profile rounded-circle" src="<?= APP_URL ?>/assets/img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <a class="dropdown-item" href="<?= APP_URL ?>/auth/logout">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
    <?php else: ?>
        <div class="container">
    <?php endif; ?>