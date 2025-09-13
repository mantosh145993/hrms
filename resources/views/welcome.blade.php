<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bodmas Hrms</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <!--Nav section -->
    <div class="navbar" id="navbar">
        <div class="logo">
            <a href="#">HRMS</a>
        </div>
        <div class="menu-toggle" id="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="nav-links" id="nav-links">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#services">Services</a>
            <a href="#contact">Contact</a>
            <a href="{{ route('login') }}" class="cta-button">Login</a>

        </div>
    </div>
    <!-- Home section  -->
    <div class="container" id="home">
        <h1>Welcome to Bodmas HRMS Solutions</h1>
        <p>Smart HR Management Software to streamline your workforce, payroll, and productivity.</p>
        <a href="#services" class="cta-button">Explore Our Services</a>
    </div>
    <hr>
    <!-- About Section  -->
    <div class="container" id="about">
        <section>
            <h1>About Us</h1>
            <p>
                At <strong>Bodmas HRMS Solutions</strong>, we help businesses simplify their HR operations with modern,
                user-friendly software. Our goal is to empower organizations by automating manual HR tasks, improving
                efficiency, and enabling smarter decision-making.
            </p>
            <p>
                Whether you're a startup or an enterprise, our HRMS adapts to your needs and grows with your business.
            </p>
        </section>
    </div>
    <hr>
    <!-- Services Section -->
    <div class="container" id="services">
        <section>
            <h1>Our Services</h1>
            <div>
                <div class="service-card">
                    <h3>Employee Management</h3>
                    <p>Maintain complete employee records, attendance, and performance in one centralized system.</p>
                </div>
                <div class="service-card">
                    <h3>Payroll Automation</h3>
                    <p>Generate accurate payslips, manage compliance, and automate salary disbursement with ease.</p>
                </div>
                <div class="service-card">
                    <h3>Leave & Attendance</h3>
                    <p>Track attendance, manage leave requests, and integrate biometric/remote attendance systems.</p>
                </div>
                <div class="service-card">
                    <h3>Reports & Analytics</h3>
                    <p>Get insights into workforce productivity, payroll costs, and HR operations with detailed reports.</p>
                </div>
            </div>
    </div>
    </section>
    <hr>
    <!-- Contact Section -->
    <div class="container" id="contact">
        <section>
            <h1>Contact Us</h2>
                <p>Have questions or need a demo? Reach out to us today!</p>
                <p><strong>Email:</strong> info@bodmaseducation.com</p>
                <p><strong>Phone:</strong> +91 9899222022</p>
                <a href="mailto:info@bodmaseducation.com" class="cta-button">Call Us : 9899222022</a>
        </section>
    </div>
</body>

</html>
<style>
    .logo a {
        text-decoration: none;
        color: white;
        font-size: 20px;
        font-weight: bold;
    }

    .nav-links {
        display: flex;
        gap: 15px;
    }

    .nav-links a {
        text-decoration: none;
        color: white;
        font-size: 16px;
        transition: 0.3s;
    }

    .nav-links a:hover {
        color: #FFD700;
    }

    /* Hamburger Icon */
    .menu-toggle {
        display: none;
        flex-direction: column;
        cursor: pointer;
    }

    .menu-toggle span {
        height: 3px;
        width: 25px;
        background: white;
        margin: 4px 0;
        transition: 0.4s;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .nav-links {
            display: none;
            flex-direction: column;
            background: #000d25;
            position: absolute;
            top: 60px;
            right: 0;
            width: 200px;
            text-align: right;
            padding: 15px;
        }

        .nav-links.active {
            display: flex;
        }

        .menu-toggle {
            display: flex;
        }
    }

    body,
    html {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100%;
        overflow-x: hidden;
        font-family: 'Montserrat', sans-serif;
        background: linear-gradient(to bottom, #0d0d2b, #000000);
        color: white;
    }

    /* Custom scrollbar styling */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #0C0C27;
    }

    ::-webkit-scrollbar-thumb {
        background: #ff4081;
        border-radius: 6px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #e00070;
    }

    .navbar {
        position: fixed;
        top: 40px;
        /* Added more margin-top */
        left: 20px;
        right: 20px;
        padding: 10px 20px;
        margin: 0 60px;
        display: flex;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        transition: background 0.3s, color 0.1s, top 0.3s, left 0.3s, right 0.3s, padding 0.3s, margin 0.3s, box-shadow 0.3s;
        z-index: 1000;
    }

    .navbar.sticky {
        background: #0C0C27;
        color: #ff4081;
        top: 0;
        left: 0;
        right: 0;
        margin: 0;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* White shadow */
    }

    .navbar a {
        color: inherit;
        /* Use inherit to ensure color change on scroll */
        text-decoration: none;
        font-weight: 600;
        margin: 0 15px;
        transition: color 0.3s;
    }

    .container {
        height: 100vh;
        text-align: center;
        padding: 50px 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        z-index: 2;
        /* Ensure content is above particles */
    }

    .container:nth-child(odd) {
        background: rgba(0, 0, 0, 0.253);
    }

    h1 {
        font-size: 3em;
        margin-bottom: 0.5em;
    }

    p {
        font-size: 1.5em;
        margin-bottom: 1em;
    }

    .cta-button {
        background: linear-gradient(135deg, #ff4081, #e00070);
        padding: 1em 2em;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0 0 15px rgba(255, 64, 129, 0.5);
        transition: background 0.3s ease, box-shadow 0.3s ease;
    }

    .cta-button:hover {
        background: linear-gradient(135deg, #e00070, #ff4081);
        box-shadow: 0 0 25px rgba(255, 64, 129, 0.7);
    }

    .particles {
        position: fixed;
        /* Changed to fixed */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
        /* Ensure particles are below content */
    }

    .particle {
        position: absolute;
        width: 2px;
        height: 2px;
        background: white;
        border-radius: 50%;
        opacity: 0;
        box-shadow: 0 0 5px 1px white;
        animation: float 10s infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0) translateX(0);
            opacity: 0.7;
        }

        50% {
            opacity: 1;
        }

        100% {
            transform: translateY(-100vh) translateX(calc(-50vw + 100%));
            opacity: 0;
        }
    }

    /* Small stars background */
    .star-background {
        position: fixed;
        /* Changed to fixed */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: transparent;
        z-index: 0;
        /* Ensure star background is below everything */
    }

    .star {
        position: absolute;
        width: 1px;
        height: 1px;
        background: white;
        opacity: 0.8;
    }

    /* Additional section styling */
    .features {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 20px;
    }

    .feature-item {
        max-width: 300px;
        text-align: center;
        padding: 20px;
    }

    .logos {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        padding: 20px;
    }

    .logos img {
        max-width: 100px;
        margin: 20px;
    }

    .slider {
        width: 80%;
        max-width: 800px;
        overflow: hidden;
        position: relative;
        margin: 20px auto;
    }

    .slides {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .slide {
        min-width: 100%;
        box-sizing: border-box;
    }

    .slider img {
        width: 100%;
        border-radius: 10px;
    }

    .slider-buttons {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
        transform: translateY(-50%);
    }

    .slider-button {
        background: rgba(0, 0, 0, 0.5);
        border: none;
        color: white;
        padding: 10px;
        cursor: pointer;
    }
</style>
<script>
    const menuToggle = document.getElementById("menu-toggle");
    const navLinks = document.getElementById("nav-links");

    menuToggle.addEventListener("click", () => {
        navLinks.classList.toggle("active");
    });
    window.onscroll = function() {
        stickNavbar()
    };

    const navbar = document.getElementById("navbar");
    const sticky = navbar.offsetTop;

    function stickNavbar() {
        if (window.pageYOffset > sticky) {
            navbar.classList.add("sticky");
        } else {
            navbar.classList.remove("sticky");
        }
    }

    const particleContainer = document.querySelector('.particles');
    const starBackground = document.querySelector('.star-background');

    for (let i = 0; i < 100; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        particle.style.top = `${Math.random() * 100}vh`;
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.animationDelay = `${Math.random() * 10}s`;
        particleContainer.appendChild(particle);
    }

    for (let i = 0; i < 300; i++) {
        const star = document.createElement('div');
        star.classList.add('star');
        star.style.top = `${Math.random() * 100}vh`;
        star.style.left = `${Math.random() * 100}vw`;
        starBackground.appendChild(star);
    }

    // Slider functionality
    const slides = document.querySelector('.slides');
    const slide = document.querySelectorAll('.slide');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let index = 0;

    function showSlide(n) {
        index += n;
        if (index >= slide.length) {
            index = 0;
        }
        if (index < 0) {
            index = slide.length - 1;
        }
        slides.style.transform = 'translateX(' + (-index * 100) + '%)';
    }

    prevBtn.addEventListener('click', () => showSlide(-1));
    nextBtn.addEventListener('click', () => showSlide(1));
</script>