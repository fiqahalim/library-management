<?php

/**
 * Database Configuration
 * Adjust DB_USER and DB_PASS to match your local MySQL credentials.
 */
define("DB_HOST", "127.0.0.1:3316");
define("DB_NAME", "library");
define("DB_USER", "root");
define("DB_PASS", "");

/**
 * Application Settings
 */
define("APP_NAME", "Library Management");
define("APP_URL", "http://localhost/library-management"); // Adjust if needed
define("LOGIN_URL", APP_URL . "/auth/login");

/**
 * Error Reporting
 * Show all errors during development. 
 * Turn off display_errors in production.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Default Timezone
 */
date_default_timezone_set("Asia/Kuala_Lumpur");