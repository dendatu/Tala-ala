<?php
session_start();
include "db.php";

$message = "";

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $message = "All fields are required.";
    } else {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "User not found.";
        }
    }
}

include "header.php";
?>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h2 style="margin-bottom: 1.5rem;">Login to Tala-ala</h2>
    
    <form method="POST">
        <label>Email Address</label>
        <input type="email" name="username" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit" name="login" class="btn" style="width: 100%;">Login</button>
    </form>
    
    <?php if ($message): ?>
        <div class="message error"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <p style="text-align: center; margin-top: 1rem;">
        Don't have an account? <a href="register.php" style="color: var(--accent);">Register here</a>
    </p>
</div>

<?php include "footer.php"; ?>