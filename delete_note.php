<?php
/**
 * delete_note.php - Note Deletion Page (DELETE Operation)
 * ============================================
 * This page handles the permanent deletion of notes from the system.
 * It represents the "DELETE" operation in the CRUD functionality.
 * When a user chooses to delete a note, this script is called with the note's ID.
 * The script verifies that the user is authenticated and authorized to delete the note,
 * then removes the note's file from the server and deletes its record from the database.
 * After successful deletion, the user is redirected back to the dashboard with a success message.
 * If any errors occur during the process, appropriate error messages are displayed.
 */
// Start output buffering to prevent header errors
ob_start();

session_start();
include "db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    ob_end_flush();
    exit();
}

$user_id = $_SESSION['user_id'];
$note_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch filename
$sql = "SELECT filename FROM notes WHERE id = '$note_id' AND user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $note = $result->fetch_assoc();
    $filename = $note['filename'];
    
    // Delete file if exists
    if (file_exists("notes/$filename")) {
        unlink("notes/$filename");
    }
    
    // Delete from database
    $delete_sql = "DELETE FROM notes WHERE id = '$note_id' AND user_id = '$user_id'";
    $conn->query($delete_sql);
}

header("Location: dashboard.php");
ob_end_flush();
exit();
?>