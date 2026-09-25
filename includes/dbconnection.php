<?php
/**
 * Central database connection.
 * Environment variables make deployment possible without editing source files.
 */
$dbHost = getenv('GITB_DB_HOST') ?: 'localhost';
$dbUser = getenv('GITB_DB_USER') ?: 'root';
$dbPass = getenv('GITB_DB_PASS') ?: '';
$dbName = getenv('GITB_DB_NAME') ?: 'resultgrading';

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

if (!$conn) {
    error_log('GITB database connection failed: ' . mysqli_connect_error());
    http_response_code(503);
    exit('The portal is temporarily unavailable. Please try again shortly.');
}

mysqli_set_charset($conn, 'utf8mb4');

// Legacy pages used both variable names. Keep the alias while pages are migrated.

