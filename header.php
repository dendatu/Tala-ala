<?php
// Start session at the VERY beginning before ANY output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check for dark mode cookie
$dark_mode = isset($_COOKIE['darkmode']) && $_COOKIE['darkmode'] === 'enabled';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Tala-ala - Personal Digital Diary</title>
    <link rel="icon" type="image/x-icon" href="assets/tala-ala_logo.png">
    <style>
        /* ========== GLOBAL RESET ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ========== BRAND COLOR VARIABLES ========== */
        /* Tala-ala Brand Colors: Blue (sky), Purple (twilight), Gold (sunset), Yellow (stars) */
        :root {
            /* Light Mode Colors - High Contrast */
            --bg-primary: #faf9ff;
            --bg-surface: #ffffff;
            --text-primary: #1a103c;
            --text-secondary: #5a4a6e;
            --text-muted: #7a6a8e;
            --border-light: #e9e2f0;
            
            /* Brand Colors */
            --blue: #3b82f6;
            --blue-dark: #2563eb;
            --blue-light: #60a5fa;
            --blue-sky: #38bdf8;
            --purple: #8b5cf6;
            --purple-dark: #7c3aed;
            --purple-light: #a78bfa;
            --purple-twilight: #c084fc;
            --gold: #f59e0b;
            --gold-dark: #d97706;
            --gold-light: #fbbf24;
            --gold-sunset: #fcd34d;
            --yellow: #eab308;
            --yellow-dark: #ca8a04;
            --yellow-light: #fde047;
            --yellow-star: #fef08a;
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--blue), var(--purple));
            --gradient-secondary: linear-gradient(135deg, var(--gold), var(--yellow));
            --gradient-sky: linear-gradient(135deg, var(--blue-sky), var(--purple-twilight));
            --gradient-sunset: linear-gradient(135deg, var(--gold-sunset), var(--yellow-star));
            --gradient-hero: linear-gradient(135deg, var(--blue) 0%, var(--purple) 50%, var(--gold) 100%);
            
            /* UI Colors */
            --accent: var(--blue);
            --accent-hover: var(--blue-dark);
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --success: #10b981;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --navbar-bg: rgba(255, 255, 255, 0.98);
            --navbar-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            --footer-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Dark Mode - Enhanced Contrast for Readability */
        body.dark {
            /* Dark Mode Colors - Optimized for readability */
            --bg-primary: #0a0a1a;
            --bg-surface: #14142b;
            --text-primary: #f5f5ff;
            --text-secondary: #cdc9e6;
            --text-muted: #a9a4c9;
            --border-light: #2a2a44;
            
            /* Brand Colors - Brighter for dark mode */
            --blue: #7ab7ff;
            --blue-dark: #5a9eff;
            --purple: #c084fc;
            --purple-dark: #a855f7;
            --gold: #ffb347;
            --gold-dark: #f59e0b;
            --yellow: #ffe45c;
            --navbar-bg: rgba(10, 10, 26, 0.98);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            --navbar-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
            --footer-shadow: 0 -2px 12px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ========== NAVIGATION ========== */
        .navbar {
            background: var(--navbar-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--navbar-shadow);
            transition: box-shadow 0.3s ease;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-container img {
            height: 40px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .brand {
            font-size: 1.8rem;
            font-weight: 700;
            background: var(--gradient-hero);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
            padding-bottom: 4px;
        }

        /* Active link styling with gradient underline */
        .nav-links a.active {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .nav-links a:hover {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .user-welcome {
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Sign Out Button - Redesigned to match brand */
        .signout-btn {
            background: linear-gradient(135deg, var(--gold), var(--yellow));
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
        }

        .signout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
            background: linear-gradient(135deg, var(--gold-dark), var(--yellow-dark));
            color: white;
        }

        /* Dark mode sign out button */
        body.dark .signout-btn {
            background: linear-gradient(135deg, var(--gold), var(--yellow));
            color: black;
        }

        body.dark .signout-btn:hover {
            background: linear-gradient(135deg, var(--gold-dark), var(--yellow-dark));
            color: white;
        }

        /* Theme Toggle - Star Inspired */
        .theme-toggle {
            background: var(--bg-surface);
            border: 1px solid var(--border-light);
            padding: 8px 16px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text-primary);
            transition: all 0.3s;
            font-weight: 500;
        }

        .theme-toggle:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
            transform: translateY(-1px);
        }

        /* ========== MAIN CONTAINER ========== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 140px);
        }

        /* ========== CARDS ========== */
        .card {
            background: var(--bg-surface);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            padding: 2rem;
            border: 1px solid var(--border-light);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-hero);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }

        .card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .card p {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* ========== BUTTONS ========== */
        .btn, button[type="submit"] {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 40px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            letter-spacing: 0.3px;
        }

        .btn:hover, button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: var(--bg-surface);
            border: 1px solid var(--border-light);
            color: var(--text-primary);
            box-shadow: none;
        }

        .btn-secondary:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #f97316);
        }

        /* ========== HERO SECTION ========== */
        .hero {
            text-align: center;
            padding: 3rem 1rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(139, 92, 246, 0.05), rgba(245, 158, 11, 0.05)); 
            border-radius: 48px;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        body.dark .hero {
            background: linear-gradient(135deg, rgba(122, 183, 255, 0.08), rgba(192, 132, 252, 0.08), rgba(255, 179, 71, 0.08));
        }

        .hero::before {
            content: '✨';
            position: absolute;
            top: -50px;
            right: -50px;
            font-size: 200px;
            opacity: 0.1;
            pointer-events: none;
        }

        .hero::after {
            content: '⭐';
            position: absolute;
            bottom: -50px;
            left: -50px;
            font-size: 200px;
            opacity: 0.1;
            pointer-events: none;
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1rem;
            background: var(--gradient-hero);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto 2rem;
            line-height: 1.8;
        }

        body.dark .hero p {
            color: var(--text-secondary);
            opacity: 0.95;
        }

        .hero strong {
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 700;
        }

        /* Quote styling */
        .hero + div p {
            color: var(--text-muted);
            font-style: italic;
        }

        body.dark .hero + div p {
            color: var(--text-secondary);
            opacity: 0.9;
        }

        /* ========== FORMS ========== */
        input, textarea {
            width: 100%;
            padding: 12px 16px;
            margin: 8px 0 16px;
            border: 2px solid var(--border-light);
            border-radius: 16px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.2s;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9rem;
            display: block;
            margin-top: 0.5rem;
        }

        /* ========== MESSAGES ========== */
        .message {
            margin-top: 1rem;
            padding: 12px;
            border-radius: 16px;
            text-align: center;
            font-weight: 500;
        }

        .error {
            color: var(--danger);
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .success {
            color: var(--success);
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        /* ========== NOTES GRID ========== */
        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .note-card {
            background: var(--bg-surface);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid var(--border-light);
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .note-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-hero);
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .note-card:hover::before {
            transform: scaleX(1);
        }

        .note-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-shadow);
        }

        .note-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .note-preview {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.4;
            height: 3rem;
            overflow: hidden;
        }

        .note-date {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .note-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        /* ========== RICH EDITOR ========== */
        .rich-editor {
            border: 2px solid var(--border-light);
            border-radius: 20px;
            background: var(--bg-primary);
            overflow: hidden;
            transition: all 0.2s;
        }

        .rich-editor:focus-within {
            border-color: var(--blue);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .editor-toolbar {
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border-light);
            padding: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .editor-toolbar button {
            background: var(--bg-primary);
            border: 1px solid var(--border-light);
            padding: 8px 14px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .editor-toolbar button:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
        }

        .editor-content {
            min-height: 400px;
            padding: 20px;
            background: var(--bg-surface);
            color: var(--text-primary);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            outline: none;
            overflow-y: auto;
        }

        .editor-content:focus {
            outline: none;
        }

        .editor-content[contenteditable="true"]:empty:before {
            content: "Write your story here...";
            color: var(--text-secondary);
            font-style: italic;
        }

        /* ========== DASHBOARD HEADER ========== */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-header h1 {
            background: var(--gradient-hero);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 2rem;
        }

        /* ========== FOOTER ========== */
        .footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border-light);
            margin-top: 2rem;
            font-size: 0.9rem;
            box-shadow: var(--footer-shadow);
            transition: box-shadow 0.3s ease;
        }

        body.dark .footer {
            color: var(--text-secondary);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                text-align: center;
                padding: 1rem;
            }
            
            .container {
                padding: 1rem;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .hero {
                padding: 2rem 1rem;
            }
            
            .notes-grid {
                grid-template-columns: 1fr;
            }
            
            .card h3 {
                font-size: 1.2rem;
            }
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .note-card {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>
<body>

<?php
// Apply dark mode class if cookie is set
if ($dark_mode) {
    echo '<script>document.body.classList.add("dark");</script>';
}

// Get current page filename for conditional navigation
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar">
    <div class="logo-container">
        <img src="assets/tala-ala_logo.png" alt="Tala-ala Logo" onerror="this.src='https://via.placeholder.com/40?text=⭐'">
        <span class="brand">Tala-ala</span>
    </div>
    <div class="nav-links">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($current_page != 'dashboard.php'): ?>
                <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">My Stories</a>
            <?php endif; ?>
            <span class="user-welcome">⭐ <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Manunulat'); ?></span>
            <a href="logout.php" class="signout-btn">Log Out</a>
        <?php else: ?>
            <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Home</a>
            <a href="login.php" class="<?php echo $current_page == 'login.php' ? 'active' : ''; ?>">Login</a>
            <a href="register.php" class="<?php echo $current_page == 'register.php' ? 'active' : ''; ?>">Register</a>
        <?php endif; ?>
        <button id="themeToggle" class="theme-toggle">🌙 Night</button>
    </div>
</nav>

<script>
// Dark mode toggle functionality
const themeToggle = document.getElementById('themeToggle');

function updateThemeButton() {
    const isDark = document.body.classList.contains('dark');
    if (themeToggle) {
        themeToggle.textContent = isDark ? '☀️ Day' : '🌙 Night';
    }
    localStorage.setItem('darkMode', isDark);
    document.cookie = `darkmode=${isDark ? 'enabled' : 'disabled'}; path=/; max-age=31536000`;
}

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark');
        updateThemeButton();
    });
    updateThemeButton();
}

// Initialize dark mode from localStorage
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark');
    updateThemeButton();
}
</script>

<main class="container">