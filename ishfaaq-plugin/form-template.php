<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__); ?>style.css">

<div class="contact-form">
    <h2>Contact Us</h2>
    <form method="post" action="">
        <p>
            <label for="sender_name">Your Name:</label>
            <input type="text" name="sender_name" id="sender_name" required>
        </p>
        <p>
            <label for="contact_number">Contact Number:</label>
            <input type="text" name="contact_number" id="contact_number">
        </p>
        <p>
            <label for="sender_email">Your Email:</label>
            <input type="email" name="sender_email" id="sender_email" required>
        </p>
        <p>
            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="5" required></textarea>
        </p>
        <p>
            <input type="submit" name="ishfaaq_contact_form" value="Send Message">
        </p>
    </form>
</div>
