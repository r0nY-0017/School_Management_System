<section id="contact">
    <div class="section-content">
        <h2 class="section-header">Contact Us</h2>
        <p>Have questions or need assistance? Reach out to us through the form below or contact us directly.</p>
        <div class="row mt-4">
            <div class="col-md-6">
                <form class="contact-form" action="contact.php" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="message" rows="5" placeholder="Your Message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <img src="images/school1.jpg" class="card-img-top" alt="Contact Info">
                    <div class="card-body">
                        <h5 class="card-title">Get in Touch</h5>
                        <p><i class="fas fa-map-marker-alt"></i> 123 School Road, City, Country</p>
                        <p><i class="fas fa-phone"></i> +123 456 7890</p>
                        <p><i class="fas fa-envelope"></i> info@school.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // ডাটাবেসে সেভ করার জন্য (ঐচ্ছিক)
    include 'db_connect.php';
    $sql = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Message sent successfully!'); window.location.href='index.php#contact';</script>";
    } else {
        echo "<script>alert('Error sending message. Please try again.'); window.location.href='index.php#contact';</script>";
    }
}
?>