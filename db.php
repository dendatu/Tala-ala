<?php
/**
 * db.php - Database Configuration and Setup
 * ============================================
 * This file establishes the database connection and creates the required tables
 * if they don't already exist. It serves as the central database access point
 * for the entire Tala-ala application.
 */

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";

// Connect to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS talaala_db";
$conn->query($sql);

// Select the database
$conn->select_db("talaala_db");

// Create users table if not exists
$users_table = "CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($users_table);

// Create notes table if not exists
$notes_table = "CREATE TABLE IF NOT EXISTS notes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    filename VARCHAR(255) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($notes_table);

// Create notes folder if not exists
if (!is_dir("notes")) {
    mkdir("notes", 0777, true);
}
?>