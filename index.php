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
/* Additional styles for hero section with logo */
.hero {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(139, 92, 246, 0.05), rgba(245, 158, 11, 0.05)); 
    border-radius: 48px;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.hero-logo {
    margin-bottom: 1.5rem;
    animation: fadeInUp 0.5s ease-out;
}

.hero-logo img {
    max-width: 120px;
    height: auto;
    filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1));
    transition: transform 0.3s ease;
}

.hero-logo img:hover {
    transform: scale(1.05);
}

.hero h1 {
    font-size: 3.5rem;
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

.hero strong {
    background: var(--gradient-secondary);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 700;
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

@media (max-width: 768px) {
    .hero {
        padding: 2rem 1rem;
    }
    
    .hero h1 {
        font-size: 2.5rem;
    }
    
    .hero p {
        font-size: 1rem;
    }
    
    .hero-logo img {
        max-width: 80px;
    }
}
</style>

<div class="hero">
    <!-- Logo Section -->
    <div class="hero-logo">
        <img src="assets/tala-ala_logo.png" alt="Tala-ala Logo" onerror="this.src='https://via.placeholder.com/120?text=Tala-ala'">
    </div>
    
    <h1>Tala-ala</h1>
    <p>
        A personal digital diary where every thought becomes a memory. 
        Securely record <strong>(tala)</strong> your ideas, experiences, and reflections— 
        and turn them into meaningful memories <strong>(ala-ala)</strong> you can revisit anytime.
    </p>

    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="register.php" class="btn">Start Your Story</a>
        <a href="login.php" class="btn btn-secondary">Continue Writing</a>
    </div>
</div>

<div style="text-align: center; margin-top: 2rem;">
    <p style="font-style: italic; opacity: 0.8; font-size: 1.1rem;">
        “Write it today. Remember it tomorrow.”<br>
        (“Sa bawat tala ngayon, may alaala bukas.”)
    </p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem;">
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

<div style="text-align: center; margin-top: 3rem;">
    <p style="opacity: 0.8; font-size: 1rem;">
        Begin your journey of thoughts, stories, and memories with <strong>Tala-ala</strong>.
    </p>
</div>

<?php include "footer.php"; ?>