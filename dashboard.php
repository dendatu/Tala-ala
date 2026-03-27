<?php
/**
 * dashboard.php - Main Notes Listing Page (READ Operation)
 * ============================================
 * This page serves as the main dashboard where users can view all their notes.
 * It represents the "READ" operation in the CRUD functionality.
 * The dashboard displays a personalized greeting, statistics about the user's notes, and a grid of note cards.
 * Each note card shows the title, a preview of the content, and the last updated date. Users can click on a note to edit it
 * or use action buttons to edit or delete directly from the dashboard. The page also includes a welcome section 
 * with dynamic messaging based on the user's note count, and quick action buttons 
 * for creating new notes or browsing existing ones. If the user has no notes, an empty state 
 * encourages them to create their first note. The dashboard is designed to be visually appealing 
 * and user-friendly, with responsive design for mobile devices. 
 */

// Start output buffering - prevents header errors when redirecting
ob_start();

// Include header (handles session, navbar, dark mode) and database connection
include "header.php";
include "db.php";

// Authentication: Only logged-in users can access dashboard
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    ob_end_flush();
    exit();
}

// Get current user's data for personalized greeting
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'] ?? 'User';

// Fetch all notes for this user, ordered by most recent first
$sql = "SELECT * FROM notes WHERE user_id = '$user_id' ORDER BY updated_at DESC";
$notes = $conn->query($sql);
$total_notes = $notes->num_rows;  // Count total notes for statistics

// Get the most recent note date for "Last Activity" stat
$most_recent = "";
if ($total_notes > 0) {
    $first_note = $notes->fetch_assoc();
    $most_recent = date("F j, Y", strtotime($first_note['updated_at']));
    $notes->data_seek(0);  // Reset pointer to beginning of result set
}

// Extract first name for personalized greeting (e.g., "Hello, John!")
$first_name = explode(' ', trim($user_name))[0];
?>

<!-- ============================================
     DASHBOARD STYLES - Layout and Visual Design
     ============================================ -->
<style>
/* Welcome Section - Gradient background with decorative top border */
.dashboard-welcome {
    background: linear-gradient(135deg, var(--bg-surface) 0%, var(--bg-primary) 100%);
    border-radius: 28px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-light);
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
}

/* Colored top border accent (brand gradient) */
.dashboard-welcome::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-hero);
}

.welcome-content {
    position: relative;
    z-index: 1;
}

/* Small badge indicating this is the dashboard */
.welcome-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--blue), var(--purple));
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-bottom: 1rem;
    letter-spacing: 0.5px;
}

/* Gradient text for user's name */
.welcome-content h1 {
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
    background: var(--gradient-hero);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 700;
}

/* Personalized welcome message based on note count */
.welcome-message {
    color: var(--text-secondary);
    margin-top: 0.5rem;
    font-size: 1rem;
    line-height: 1.6;
    max-width: 80%;
}

/* Statistics Cards - Display note count and last activity */
.dashboard-stats {
    display: flex;
    gap: 1.5rem;
    margin: 2rem 0 1.5rem;
    flex-wrap: wrap;
}

.stat-card {
    background: var(--bg-surface);
    border-radius: 20px;
    padding: 1.25rem 1.75rem;
    border: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    flex: 1;
    min-width: 180px;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    border-color: var(--blue);
}

/* Icon container with gradient background */
.stat-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--blue);
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.stat-date {
    font-size: 0.9rem;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

/* Quick Action Buttons - Create new note or browse existing */
.quick-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.action-btn {
    background: var(--gradient-primary);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 40px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.action-btn-secondary {
    background: var(--bg-surface);
    border: 1px solid var(--border-light);
    color: var(--text-primary);
    box-shadow: none;
}

.action-btn-secondary:hover {
    background: var(--bg-primary);
    transform: translateY(-2px);
    border-color: var(--blue);
}

/* Section header with title and new note button */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Notes Grid - Displays all user notes in card layout */
.notes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 1.5rem;
    margin-top: 0.5rem;
}

.note-card {
    background: var(--bg-surface);
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

/* Gradient top border on hover */
.note-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-hero);
    border-radius: 20px 20px 0 0;
    opacity: 0;
    transition: opacity 0.3s;
}

.note-card:hover::before {
    opacity: 1;
}

.note-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.note-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--accent);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.note-preview {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.5;
    height: 3rem;
    overflow: hidden;
}

/* Metadata section with date */
.note-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-top: 0.5rem;
    border-top: 1px solid var(--border-light);
}

.note-date {
    font-size: 0.75rem;
    color: var(--text-secondary);
    font-weight: 500;
}

/* Edit/Delete buttons */
.note-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.note-actions button {
    padding: 6px 14px;
    font-size: 0.8rem;
}

/* Empty State - Shown when user has no notes */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, var(--bg-surface) 0%, var(--bg-primary) 100%);
    border-radius: 24px;
    border: 1px solid var(--border-light);
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
}

/* Dashboard Footer - Summary and add more link */
.dashboard-footer {
    text-align: center;
    margin-top: 2rem;
    padding: 1.5rem;
    color: var(--text-secondary);
    font-size: 0.85rem;
    border-top: 1px solid var(--border-light);
    background: var(--bg-surface);
    border-radius: 16px;
}

.dashboard-footer a {
    color: var(--accent);
    text-decoration: none;
    font-weight: 500;
}

.dashboard-footer a:hover {
    text-decoration: underline;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .dashboard-welcome {
        padding: 1.5rem;
    }
    
    .welcome-content h1 {
        font-size: 1.6rem;
    }
    
    .welcome-message {
        max-width: 100%;
    }
    
    .stat-card {
        min-width: 100%;
    }
    
    .notes-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        flex-direction: column;
    }
    
    .action-btn, .action-btn-secondary {
        justify-content: center;
    }
}
</style>

<!-- ============================================
     WELCOME SECTION - Personalized Greeting
     ============================================ -->
<div class="dashboard-welcome">
    <div class="welcome-content">
        <!-- Dashboard badge indicating this is the main page -->
        <div class="welcome-badge">Personal Dashboard</div>
        
        <!-- Personalized greeting with user's first name -->
        <h1>Hello, <?php echo htmlspecialchars($first_name); ?>!</h1>
        
        <!-- Dynamic welcome message based on note count -->
        <div class="welcome-message">
            <?php 
            if ($total_notes == 0) {
                echo "Welcome to your personal space. Every great journey begins with a single note. Ready to start writing?";
            } elseif ($total_notes == 1) {
                echo "You've begun your journey with one beautiful note. Keep the inspiration flowing and watch your collection grow.";
            } else {
                echo "Your story is unfolding beautifully with " . $total_notes . " notes. Each thought you preserve adds meaning to your journey.";
            }
            ?>
        </div>
        
        <!-- Statistics Cards - Visual representation of user's note activity -->
        <div class="dashboard-stats">
            <!-- Total notes count card -->
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-info">
                    <div class="stat-number"><?php echo $total_notes; ?></div>
                    <div class="stat-label">Total Notes</div>
                </div>
            </div>
            <!-- Last activity card (only shown if notes exist) -->
            <?php if ($most_recent && $total_notes > 0): ?>
            <div class="stat-card">
                <div class="stat-icon">🕒</div>
                <div class="stat-info">
                    <div class="stat-date"><?php echo $most_recent; ?></div>
                    <div class="stat-label">Last Activity</div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Action Buttons - Primary user actions -->
        <div class="quick-actions">
            <a href="add_note.php" class="action-btn">+ Create New Note</a>
            <!-- Browse button only shown if notes exist -->
            <?php if ($total_notes > 0): ?>
            <a href="#notes-list" class="action-btn-secondary action-btn">Browse All Notes</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ============================================
     NOTES SECTION HEADER
     ============================================ -->
<div class="dashboard-header" id="notes-list">
    <!-- Dynamic section title based on note count -->
    <h2>
        <?php 
        if ($total_notes == 0) {
            echo "Getting Started";
        } elseif ($total_notes == 1) {
            echo "Your First Note";
        } else {
            echo "Your Notes Collection";
        }
        ?>
    </h2>
    <!-- New Note button for quick access -->
    <a href="add_note.php" class="btn">+ New Note</a>
</div>

<!-- ============================================
     NOTES DISPLAY - Grid or Empty State
     ============================================ -->
<?php if ($total_notes == 0): ?>
    <!-- Empty State: Encourages user to create first note -->
    <div class="empty-state">
        <h3>Start Your Journey</h3>
        <p>Your dashboard is waiting for your first thought, idea, or memory.</p>
        <a href="add_note.php" class="btn">Create Your First Note</a>
    </div>
<?php else: ?>
    <!-- Notes Grid: Display all user notes in card layout -->
    <div class="notes-grid">
        <?php while ($note = $notes->fetch_assoc()): 
            $file_path = "notes/" . $note['filename'];
            $preview = "";
            
            // Extract preview from the HTML file (first 100 characters)
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                // Extract content from the note-content div
                if (preg_match('/<div class="note-content">(.*?)<\/div>/s', $content, $matches)) {
                    $preview = strip_tags(substr($matches[1], 0, 100));
                } else {
                    $preview = strip_tags(substr($content, 0, 100));
                }
                $preview = strlen($preview) > 100 ? substr($preview, 0, 100) . '...' : $preview;
            }
            
            // Format date for display (e.g., "Jan 15, 2024")
            $date = new DateTime($note['updated_at']);
            $formatted_date = $date->format("M d, Y");
        ?>
        <!-- Note Card - Clickable to edit -->
        <div class="note-card" onclick="window.location.href='edit_note.php?id=<?php echo $note['id']; ?>'">
            <div class="note-title"><?php echo htmlspecialchars($note['title']); ?></div>
            <div class="note-preview"><?php echo htmlspecialchars($preview ?: 'Click to view this note'); ?></div>
            <div class="note-meta">
                <div class="note-date">Updated: <?php echo $formatted_date; ?></div>
            </div>
            <!-- Action buttons - Edit and Delete (stopPropagation prevents card click) -->
            <div class="note-actions" onclick="event.stopPropagation()">
                <a href="edit_note.php?id=<?php echo $note['id']; ?>">
                    <button class="btn btn-secondary">Edit</button>
                </a>
                <a href="delete_note.php?id=<?php echo $note['id']; ?>" onclick="return confirm('Delete this note?');">
                    <button class="btn btn-danger">Delete</button>
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Dashboard Footer: Summary and encouragement -->
    <div class="dashboard-footer">
        <p>✨ You have <?php echo $total_notes; ?> beautiful note<?php echo $total_notes != 1 ? 's' : ''; ?> in your collection • 
        <a href="add_note.php">Add another note</a></p>
    </div>
<?php endif; ?>

<?php 
// Include footer to close HTML structure and flush output buffer
include "footer.php";
ob_end_flush();
?>