<?php include("includes/header.php"); ?>
<?php
if(isset($_GET['success'])){
echo "<p style='color:green'>Message sent successfully.</p>";
}

if(isset($_GET['error'])){
echo "<p style='color:red'>Failed to send message.</p>";
}
?>
<section class="page-hero">
<h1>Contact Us</h1>
<p>We would love to hear from you</p>
</section>

<section class="contact-wrapper">

<div class="contact-grid">

<div class="contact-info">

<h3>Get In Touch</h3>

<p>Email: support@aslplatform.com</p>
<p>Phone: +91 9876543210</p>
<p>Location: Srinagar, India</p>

</div>


<div class="contact-form">

<form method="POST" action="send_contact.php">

<input type="text" name="name" placeholder="Your Name" required>

<input type="email" name="email" placeholder="Email Address" required>

<textarea name="message" placeholder="Your Message" rows="5" required></textarea>

<button class="btn-primary">Send Message</button>

</form>

</div>

</div>

</section>

<?php include("includes/footer.php"); ?>