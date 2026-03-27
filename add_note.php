<?php
/**
 * add_note.php - Note Creation Page (CREATE Operation)
 * ============================================
 * This page allows authenticated users to create new notes with rich text formatting.
 * It handles the "CREATE" operation in the CRUD functionality of the Tala-ala notes app.
 * Users can enter a title and content for their note, which is then saved as an HTML file
 * and its metadata stored in the database. The page includes a rich text editor with formatting options
 * and ensures that only logged-in users can access it. It also provides user feedback for validation
 * and system errors, and redirects to the dashboard upon successful note creation.
 */

// Start output buffering - prevents header errors when redirecting
ob_start();

// Include header (handles session, navbar, dark mode) and database connection
include "header.php";
include "db.php";

// Authentication: Only logged-in users can access this page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login if not authenticated
    ob_end_flush();
    exit();
}

// Get current user's ID for note ownership and message container
$user_id = $_SESSION['user_id'];
$message = "";


// FORM SUBMISSION HANDLER (CREATE NOTE)
// Processes the note when user clicks "Save Note"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_note'])) {
    // Sanitize input - trim whitespace from title
    $title = trim($_POST['title']);
    $content = $_POST['content'];  // Contains HTML from rich text editor
    
    // Validation: Title is required
    if (empty($title)) {
        $message = "Title is required.";
    } else {
        // Generate unique filename using timestamp + random number + user_id
        // Ensures no file name conflicts and organizes by user
        $timestamp = time();
        $random = rand(1000, 9999);
        $filename = "note_{$user_id}_{$timestamp}_{$random}.html";
        $filepath = "notes/" . $filename;
        
        // Build complete HTML document with styling for standalone viewing
        // Includes responsive design, dark mode support, and proper formatting
        $html_content = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . htmlspecialchars($title) . '</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
                    line-height: 1.6;
                    max-width: 800px;
                    margin: 40px auto;
                    padding: 20px;
                    background: #f5f5f7;
                }
                .note-container {
                    background: white;
                    border-radius: 16px;
                    padding: 40px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                .note-title {
                    font-size: 2em;
                    font-weight: bold;
                    margin-bottom: 20px;
                    color: #1c1c1e;
                    border-bottom: 2px solid #e9e9ef;
                    padding-bottom: 10px;
                }
                .note-content {
                    color: #1c1c1e;
                }
                .note-content strong, .note-content b { font-weight: bold; }
                .note-content em, .note-content i { font-style: italic; }
                .note-content u { text-decoration: underline; }
                .note-content h3 { font-size: 1.4em; margin: 1em 0 0.5em; }
                .note-content p { margin: 0.8em 0; }
                .note-content ul, .note-content ol { margin: 0.8em 0; padding-left: 2em; }
                .note-content li { margin: 0.3em 0; }
                @media (prefers-color-scheme: dark) {
                    body { background: #000000; }
                    .note-container { background: #1c1c1e; }
                    .note-title { color: #ffffff; border-bottom-color: #2c2c2e; }
                    .note-content { color: #ffffff; }
                }
            </style>
        </head>
        <body>
            <div class="note-container">
                <div class="note-title">' . htmlspecialchars($title) . '</div>
                <div class="note-content">' . $content . '</div>
            </div>
        </body>
        </html>';
        
        // Step 1: Save HTML file to /notes folder (persistent content storage)
        if (file_put_contents($filepath, $html_content)) {
            // Step 2: Save metadata to database (title, filename, user association)
            $sql = "INSERT INTO notes (user_id, title, filename) VALUES ('$user_id', '$title', '$filename')";
            if ($conn->query($sql)) {
                // Success! Redirect to dashboard to view all notes
                header("Location: dashboard.php");
                ob_end_flush();
                exit();
            } else {
                // Database error - clean up orphaned file
                $message = "Database error. Please try again.";
                unlink($filepath);  // Delete file since database entry failed
            }
        } else {
            // File system error - likely permission issues
            $message = "Failed to save note. Please check folder permissions.";
        }
    }
}
?>

<!-- ============================================
     PAGE STYLES - Controls layout and positioning
     ============================================ -->
<style>
/* Flexbox layout for header - title on left, buttons on right */
.add-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

/* Gradient text for visual branding */
.add-header h2 {
    margin: 0;
    background: var(--gradient-hero);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

/* Action buttons container - Save and Cancel */
.add-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.add-actions button {
    margin: 0;
    white-space: nowrap;
}

/* Mobile responsive - stack header vertically on small screens */
@media (max-width: 768px) {
    .add-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .add-actions {
        justify-content: flex-end;
    }
}
</style>

<!-- ============================================
     MAIN UI - Note Creation Form
     ============================================ -->
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <!-- Header with title and action buttons (top-right placement for easy access) -->
    <div class="add-header">
        <h2>+ Create New Note</h2>
        <div class="add-actions">
            <!-- Save button connects to form via form attribute -->
            <button type="submit" form="noteForm" name="save_note" class="btn" onclick="prepareSubmit()">Save Note</button>
            <!-- Cancel returns to dashboard without saving -->
            <a href="dashboard.php"><button type="button" class="btn btn-secondary">Cancel</button></a>
        </div>
    </div>
    
    <!-- Display validation or system error messages -->
    <?php if ($message): ?>
        <div class="message error"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <!-- Note form - POST method for secure data submission -->
    <form method="POST" id="noteForm">
        <!-- Title input - required field -->
        <label>Note Title</label>
        <input type="text" name="title" required placeholder="Enter note title..." style="margin-bottom: 20px;">
        
        <!-- Rich text editor section -->
        <label>Content</label>
        <div class="rich-editor">
            <!-- Formatting toolbar - buttons for text styling -->
            <div class="editor-toolbar">
                <button type="button" onclick="formatDocument('bold')"><b>Bold</b></button>
                <button type="button" onclick="formatDocument('italic')"><i>Italic</i></button>
                <button type="button" onclick="formatDocument('underline')"><u>Underline</u></button>
                <button type="button" onclick="formatDocument('heading')">Heading</button>
                <button type="button" onclick="formatDocument('paragraph')">Paragraph</button>
                <button type="button" onclick="formatDocument('insertUnorderedList')">• List</button>
                <button type="button" onclick="formatDocument('insertOrderedList')">1. List</button>
                <button type="button" onclick="formatDocument('removeFormat')">Clear Format</button>
            </div>
            <!-- Contenteditable div - WYSIWYG editing area -->
            <div id="editorContent" class="editor-content" contenteditable="true"></div>
        </div>
        
        <!-- Hidden input stores HTML content for form submission -->
        <input type="hidden" name="content" id="hiddenContent">
    </form>
</div>

<script>
// ============================================
// RICH TEXT FORMATTING FUNCTIONS
// ============================================

// Applies formatting commands to selected text using browser's execCommand API
function formatDocument(command, value = null) {
    if (command === 'heading') {
        document.execCommand('formatBlock', false, 'h3');
    } else if (command === 'paragraph') {
        document.execCommand('formatBlock', false, 'p');
    } else if (command === 'removeFormat') {
        document.execCommand('removeFormat', false, null);
    } else {
        document.execCommand(command, false, value);
    }
    document.getElementById('editorContent').focus();  // Keep focus on editor
}

// Prepares form by copying editor content to hidden input before submission
function prepareSubmit() {
    const editorContent = document.getElementById('editorContent');
    const hiddenContent = document.getElementById('hiddenContent');
    hiddenContent.value = editorContent.innerHTML;  // Copy HTML content
    
    // Validate content is not empty
    if (!hiddenContent.value.trim() || hiddenContent.value === '<br>' || hiddenContent.value === '<div><br></div>') {
        alert('Please enter some content for your note.');
        return false;
    }
    return true;
}

// ============================================
// KEYBOARD SHORTCUTS
// ============================================
// Adds Ctrl+B, Ctrl+I, Ctrl+U for quick formatting (standard word processor shortcuts)
const editorContent = document.getElementById('editorContent');
if (editorContent) {
    // Initialize empty editor
    if (editorContent.innerHTML.trim() === '') {
        editorContent.innerHTML = '';
    }
    
    // Listen for keyboard shortcuts
    editorContent.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'b':
                    e.preventDefault();
                    formatDocument('bold');
                    break;
                case 'i':
                    e.preventDefault();
                    formatDocument('italic');
                    break;
                case 'u':
                    e.preventDefault();
                    formatDocument('underline');
                    break;
            }
        }
    });
}
</script>

<?php 
// Include footer to close HTML structure and flush output buffer
include "footer.php";
ob_end_flush();
?>