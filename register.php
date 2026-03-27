<?php
/**
 * register.php - User registration page
 * Allows new users to create an account with full name, email, and password.
 * Uses password hashing for security and validation for data integrity.
 */

// Start session to check if user is already logged in
session_start();
include "db.php";

$message = "";           // Stores success/error messages
$fullname = $username = "";  // Preserve input values on error

// Redirect if user is already logged in (prevents duplicate registration)
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// ============================================
// REGISTRATION FORM HANDLER
// ============================================
// Processes registration when form is submitted
if (isset($_POST['register'])) {
    // Sanitize input - trim whitespace
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);  // Email address
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    $errors = [];  // Array to collect validation errors
    
    // ============================================
    // VALIDATION - Required Fields
    // ============================================
    // Check that all fields are filled
    if (empty($fullname) || empty($username) || empty($password) || empty($confirm)) {
        $errors[] = "All fields are required.";
    }
    
    
    // ============================================
    // PASSWORD VALIDATION
    // ============================================
    // Password must be strong: min 8 chars, 1 uppercase, 1 number, 1 special char
    $passwordPattern = "/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{8,}$/";
    if (!preg_match($passwordPattern, $password)) {
        $errors[] = "Password must be at least 8 characters and include one uppercase letter, one number, and one special character.";
    }
    
    // Check that password and confirmation match
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }
    
    // ============================================
    // DUPLICATE CHECK
    // ============================================
    // Verify email is not already registered
    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        $errors[] = "Email already registered. Please use a different email.";
    }
    
    // ============================================
    // CREATE USER (if no errors)
    // ============================================
    if (empty($errors)) {
        // Hash password for secure storage (never store plain text)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user into database
        $sql = "INSERT INTO users (full_name, username, password) VALUES ('$fullname', '$username', '$hashed_password')";
        
        if ($conn->query($sql)) {
            // Success message and clear form fields
            $message = "Registration successful! You can now login.";
            $fullname = $username = "";  // Clear input fields
        } else {
            $message = "Registration failed. Please try again.";
        }
    } else {
        // Display all validation errors
        $message = implode("<br>", $errors);
    }
}

// Include the header (contains navbar, dark mode, etc.)
include "header.php";
?>

<!-- ============================================
     REGISTRATION FORM UI
     ============================================ -->
<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2 style="margin-bottom: 1.5rem;">Create Account</h2>
    
    <!-- Registration Form - POST method for secure submission -->
    <form method="POST">
        <!-- Full Name input -->
        <label>Full Name</label>
        <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
        
        <!-- Email Address input (used as username) -->
        <label>Email Address</label>
        <input type="email" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
        
        <!-- Password input with validation requirements -->
        <label>Password</label>
        <input type="password" name="password" required>
        <small style="color: var(--text-secondary); font-size: 0.75rem;">
            Minimum 8 characters, 1 uppercase, 1 number, 1 special character
        </small>
        
        <!-- Confirm Password input -->
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>
        
        <!-- Submit button -->
        <button type="submit" name="register" class="btn" style="width: 100%;">Register</button>
    </form>
    
    <!-- Display success/error message -->
    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <!-- Link to login page for existing users -->
    <p style="text-align: center; margin-top: 1rem;">
        Already have an account? <a href="login.php" style="color: var(--accent);">Login here</a>
    </p>
</div>

<?php include "footer.php"; ?>
