<?php
/**
 * edit_note.php - Note Editing Page (UPDATE Operation)
 * ============================================
 * This page allows users to edit existing notes.
 * It represents the "UPDATE" operation in the CRUD functionality.
 * Users can modify the title and content of their notes, which are saved as HTML files.
 * The page includes a rich text editor for formatting and ensures that only the note's owner can edit it.
 * Upon submission, the updated note is saved to the server and the database is updated accordingly.
 * User feedback is provided for validation errors and system issues, and successful updates redirect 
 * back to the dashboard.
 */
// Start output buffering to prevent header errors
ob_start();

// No session_start needed - header.php handles it
include "header.php";
include "db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    ob_end_flush();
    exit();
}

$user_id = $_SESSION['user_id'];
$note_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = "";
$title = "";
$content = "";

// Fetch note data
$sql = "SELECT * FROM notes WHERE id = '$note_id' AND user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: dashboard.php");
    ob_end_flush();
    exit();
}

$note = $result->fetch_assoc();
$title = $note['title'];
$filename = $note['filename'];

// Load note content from file
if (file_exists("notes/$filename")) {
    $file_content = file_get_contents("notes/$filename");
    if (preg_match('/<div class="note-content">(.*?)<\/div>/s', $file_content, $matches)) {
        $content = $matches[1];
    }
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_note'])) {
    $new_title = trim($_POST['title']);
    $new_content = $_POST['content'];
    
    if (empty($new_title)) {
        $message = "Title is required.";
    } else {
        // Generate new filename
        $timestamp = time();
        $random = rand(1000, 9999);
        $new_filename = "note_{$user_id}_{$timestamp}_{$random}.html";
        $filepath = "notes/" . $new_filename;
        
        // Create updated HTML content
        $html_content = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . htmlspecialchars($new_title) . '</title>
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
                <div class="note-title">' . htmlspecialchars($new_title) . '</div>
                <div class="note-content">' . $new_content . '</div>
            </div>
        </body>
        </html>';
        
        if (file_put_contents($filepath, $html_content)) {
            // Update database
            $update_sql = "UPDATE notes SET title = '$new_title', filename = '$new_filename', updated_at = NOW() WHERE id = '$note_id'";
            if ($conn->query($update_sql)) {
                // Delete old file
                if (file_exists("notes/$filename")) {
                    unlink("notes/$filename");
                }
                header("Location: dashboard.php");
                ob_end_flush();
                exit();
            } else {
                $message = "Database error. Please try again.";
                unlink($filepath);
            }
        } else {
            $message = "Failed to save note. Please check folder permissions.";
        }
    }
}
?>

<style>
/* Additional styling for edit page */
.edit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.edit-header h2 {
    margin: 0;
    background: var(--gradient-hero);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.edit-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.edit-actions button {
    margin: 0;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .edit-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .edit-actions {
        justify-content: flex-end;
    }
}
</style>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="edit-header">
        <h2>Edit Note</h2>
        <div class="edit-actions">
            <button type="submit" form="noteForm" name="update_note" class="btn" onclick="prepareSubmit()">Update Note</button>
            <a href="dashboard.php"><button type="button" class="btn btn-secondary">Cancel</button></a>
        </div>
    </div>
    
    <?php if ($message): ?>
        <div class="message error"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <form method="POST" id="noteForm">
        <label>Note Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required style="margin-bottom: 20px;">
        
        <label>Content</label>
        <div class="rich-editor">
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
            <div id="editorContent" class="editor-content" contenteditable="true"><?php echo $content; ?></div>
        </div>
        
        <!-- Hidden input to store HTML content -->
        <input type="hidden" name="content" id="hiddenContent">
    </form>
</div>

<script>
// Function to format text using document.execCommand
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
    document.getElementById('editorContent').focus();
}

// Prepare form submission
function prepareSubmit() {
    const editorContent = document.getElementById('editorContent');
    const hiddenContent = document.getElementById('hiddenContent');
    hiddenContent.value = editorContent.innerHTML;
    
    if (!hiddenContent.value.trim() || hiddenContent.value === '<br>' || hiddenContent.value === '<div><br></div>') {
        alert('Please enter some content for your note.');
        return false;
    }
    return true;
}

// Keyboard shortcuts
const editorContent = document.getElementById('editorContent');
if (editorContent) {
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
include "footer.php";
ob_end_flush();
?>