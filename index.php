<?php
/**
 * index.php - Landing Page (Homepage)
 * ============================================
 * This is the entry point of the Tala-ala application - the first page users see
 * when visiting the website. It serves as the marketing and informational hub
 * for the application, introducing new visitors to the concept of Tala-ala and
 * encouraging them to sign up or log in. The page features a hero section with 
 * the app's logo, a compelling headline, and a brief description of the app's purpose 
 * and benefits. 
 * 
 * It also includes call-to-action buttons for registration and login, 
 * as well as additional sections that highlight key features of the app. 
 * The design is visually appealing and responsive, ensuring a great user experience across all devices.
 * The index.php page is crucial for converting visitors into users by effectively communicating the 
 * value of Tala-ala and providing easy access to the registration and login processes.
 */
$currentPage = "home";
include "header.php";
?>

<style>
/* Additional styles for hero section with logo and subtle background image */
.hero {
    text-align: center;
    padding: 2.5rem 2rem;
    /* Gradient overlay with background image - subtle appearance */
    background: linear-gradient(135deg, rgba(204, 212, 225, 0.85), rgba(133, 109, 194, 0.85), rgba(186, 209, 217, 0.85)), url('assets/hero_image.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border-radius: 48px;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

/* Dark mode hero - slightly different opacity for better visibility */
body.dark .hero {
    background: linear-gradient(135deg, rgba(8, 27, 57, 0.8), rgba(53, 24, 119, 0.8), rgba(22, 14, 0, 0.8)), url('assets/hero_image.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.hero-logo {
    margin-bottom: 0.1rem;
    animation: fadeInUp 0.5s ease-out;
}

.hero-logo img {
    max-width: 100px;
    height: auto;
    filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.2));
    transition: transform 0.3s ease;
    background: rgba(255, 255, 255, 0.14);
    border-radius: 50%;
    padding: 5px;
    object-fit: contain;
}

.hero-logo img:hover {
    transform: scale(1.05);
}

.hero h1 {
    font-size: 3.5rem;
    margin-bottom: 0.75rem;
    background: linear-gradient(135deg, #ffffff, #f0f0ff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 800;
    letter-spacing: -1px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

body.dark .hero h1 {
    background: linear-gradient(135deg, #ffffff, #e0e0ff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.hero p {
    font-size: 1.4rem;
    color: #ffffff;
    max-width: 650px;
    margin: 0 auto 1.5rem;
    line-height: 1.6;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    font-weight: 500;
}

body.dark .hero p {
    color: #ffffff;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.hero strong {
    background: linear-gradient(135deg, #ffd966, #faea8e);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 700;
}

/* Hero button styling - enhanced for visibility against background */
.hero .btn, 
.hero .btn-secondary {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.hero .btn-secondary {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: var(--blue-dark);
}

.hero .btn-secondary:hover {
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
}

/* Card hover effect */
.card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

/* Quote styling - made more compact */
.quote-section {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

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

/* Responsive adjustments */
@media (max-width: 768px) {
    .hero {
        padding: 1.5rem 1rem;
    }
    
    .hero h1 {
        font-size: 2rem;
    }
    
    .hero p {
        font-size: 0.9rem;
    }
    
    .hero-logo img {
        max-width: 70px;
    }
    
    .quote-section {
        margin-top: 1rem;
    }
}
</style>

<div class="hero">
    <!-- Logo Section -->
    <div class="hero-logo">
        <img src="assets/tala-ala_logo.png" alt="Tala-ala Logo" onerror="this.src='https://via.placeholder.com/100?text=Tala-ala'">
    </div>
    
    <h1>Tala-ala</h1>
    <p>
        A <strong>personal digital diary</strong> where you <strong>tala</strong> (record) your thoughts 
        and turn them into <strong>ala-ala</strong> (memories) you can revisit anytime.
    </p>

    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="register.php" class="btn">Start Your Story</a>
        <a href="login.php" class="btn btn-secondary">Continue Writing</a>
    </div>
</div>

<!-- Quote Section - Now more compact -->
<div class="quote-section" style="text-align: center;">
    <p style="font-style: italic; opacity: 0.8; font-size: 1rem;">
        “Write it today. Remember it tomorrow.”<br>
        (“Sa bawat tala ngayon, may alaala bukas.”)
    </p>
</div>

<!-- Feature Cards - Now appears sooner when page loads -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 2rem;">
    <div class="card" style="text-align: center;">
        <h3>Personal Diary</h3>
        <p>
            More than notes—record your daily thoughts, stories, and moments 
            in a space designed for reflection and meaning.
        </p>
    </div>

    <div class="card" style="text-align: center;">
        <h3>Private & Secure</h3>
        <p>
            Your <em>ala-ala</em> are yours alone. With account protection, 
            your memories stay safe, personal, and always within reach.
        </p>
    </div>

    <div class="card" style="text-align: center;">
        <h3>Express Freely</h3>
        <p>
            Highlight what matters. Format your entries to capture emotions, 
            ideas, and details just the way you want them remembered.
        </p>
    </div>

    <div class="card" style="text-align: center;">
        <h3>Night Mode</h3>
        <p>
            Switch to a calm, star-inspired view—because <em>tala</em> also means star. 
            Write peacefully at night under your own digital sky.
        </p>
    </div>
</div>

<div style="text-align: center; margin-top: 2.5rem;">
    <p style="opacity: 0.8; font-size: 0.95rem;">
        Begin your journey of thoughts, stories, and memories with <strong>Tala-ala</strong>.
    </p>
</div>

<?php include "footer.php"; ?>
