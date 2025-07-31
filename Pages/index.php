<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>


<body class="light-theme">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg fixed-top light-theme">
        <a class="navbar-brand" href="#home">School Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#academics">Academics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#admissions">Admissions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact Us</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Login
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="loginDropdown">
                        <a class="dropdown-item" href="student_auth.php">Student</a>
                        <a class="dropdown-item" href="parent_auth.php">Parent</a>
                        <a class="dropdown-item" href="teacher_auth.php">Teacher</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link theme-toggle" id="themeToggle"><i class="fas fa-moon"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Home Section (Carousel) -->
    <section id="home">
        <div id="schoolCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#schoolCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#schoolCarousel" data-slide-to="1"></li>
                <li data-target="#schoolCarousel" data-slide-to="2"></li>
                <li data-target="#schoolCarousel" data-slide-to="3"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/school1.jpg" alt="School Image 1">
                    <div class="carousel-caption">
                        <h1>Welcome to Our School</h1>
                        <p>Empowering Education with Technology</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/school2.jpeg" alt="School Image 2">
                    <div class="carousel-caption">
                        <h1>Explore Our Campus</h1>
                        <p>A Place for Learning and Growth</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/school3.jpg" alt="School Image 3">
                    <div class="carousel-caption">
                        <h1>Modern Facilities</h1>
                        <p>Designed for Excellence</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/school4.jpeg" alt="School Image 4">
                    <div class="carousel-caption">
                        <h1>Join Our Community</h1>
                        <p>Shape Your Future</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#schoolCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#schoolCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </section>

    <!-- Other Sections -->
    <?php
    include 'includes/about.php';
    include 'includes/academics.php';
    include 'includes/admissions.php';
    include 'includes/contact.php';
    ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Our School</h5>
                    <p>Founded in 1990, our school is committed to providing quality education and fostering a nurturing environment for students to thrive.</p>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-map-marker-alt"></i> 123 School Road, City, Country</p>
                    <p><i class="fas fa-phone"></i> +123 456 7890</p>
                    <p><i class="fas fa-envelope"></i> info@school.com</p>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="text-center mt-4">
                <p>&copy; <?php echo date("Y"); ?> School Management System. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Auto slide every 5 seconds
        $('.carousel').carousel({
            interval: 5000
        });

        // Smooth scrolling for nav links
        $('a[href*="#"]').not('[href="#"]').not('[data-slide]').on('click', function(e) {
            e.preventDefault();
            const target = $(this.hash);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 70 // Adjust for fixed navbar
                }, 800);
            }
        });

        // Theme toggle
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const navbar = document.querySelector('.navbar');
        themeToggle.addEventListener('click', () => {
            if (body.classList.contains('light-theme')) {
                body.classList.replace('light-theme', 'dark-theme');
                navbar.classList.remove('light-theme');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                body.classList.replace('dark-theme', 'light-theme');
                navbar.classList.add('light-theme');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
            localStorage.setItem('theme', body.classList.contains('light-theme') ? 'light' : 'dark');
        });

        // Load saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            body.classList.replace('light-theme', 'dark-theme');
            navbar.classList.remove('light-theme');
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
    </script>
</body>
</html>