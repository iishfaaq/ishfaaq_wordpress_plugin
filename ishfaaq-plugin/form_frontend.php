<?php
// Shortcode to display the contact form
function ishfaaq_contact_form_shortcode() {
    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ishfaaq_contact_form'])) {
        global $wpdb;

        // Sanitize and validate form fields
        $name = isset($_POST['sender_name']) ? sanitize_text_field($_POST['sender_name']) : '';
        $contact_number = isset($_POST['contact_number']) ? sanitize_text_field($_POST['contact_number']) : '';
        $sender_email = isset($_POST['sender_email']) ? sanitize_email($_POST['sender_email']) : '';
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

        // Get the receiver's email from the plugin settings
        $options = get_option('ishfaaq_email_plugin_settings');
        $receiver_email = isset($options['rec_email_id']) ? $options['rec_email_id'] : '';
        $ack_email = isset($options['ack_email_id']) ? $options['ack_email_id'] : '';
        $ack_body = isset($options['email_body']) ? $options['email_body'] : '';

        // Check if fields are valid
        if (empty($name) || !is_email($sender_email) || empty($message)) {
            echo '<div class="alert alert-danger">Please provide your name, a valid email, and a message.</div>';
        } else {
            // Save message to database
            $wpdb->insert(
                $wpdb->prefix . 'contact_messages', // Table name with prefix
                [
                    'name' => $name,
                    'contact_number' => $contact_number,
                    'email' => $sender_email,
                    'message' => $message,
                    'date' => current_time('mysql')
                ],
                ['%s', '%s', '%s', '%s', '%s']
            );

            // Prepare email
            $subject = 'New Message from Contact Form';
            $email_body = "You have received a new message from your contact form:\n\n" .
                "Name: $name\n" .
                "Contact Number: $contact_number\n" .
                "Email: $sender_email\n" .
                "Message:\n$message\n";

            // Send email
            if(wp_mail($receiver_email, $subject, $email_body)){
                echo '<div class="alert alert-success">Your message has been sent successfully!</div>';   
            }
            else {
                echo '<div class="alert alert-danger">Failed to send the email</div>';
            }

            add_filter('wp_mail_from', function() use ($ack_email) {
                return $ack_email; // Use the correctly defined variable
            });
            
            
            add_filter('wp_mail_from_name', function() {
                return 'Acknowledgement'; // Replace with the desired "From" name
            });

            $acksubject = 'This is an acknowledgement';
            //send acknowledge email
            wp_mail($sender_email, $acksubject, $ack_body);

            //echo '<div class="alert alert-success">Your message has been sent successfully!</div>';
        }
    }

    // Display the form
    ob_start();
    ?>
    <style>
        .contact-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 600px; /* Optional: set a max width for larger screens */
            margin: 0 auto; /* Center the form */
            width: 100%; /* Make the form take full width */
        }
        .contact-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .contact-form label {
            font-weight: bold;
        }
        .contact-form input[type="text"],
        .contact-form input[type="email"],
        .contact-form textarea {
            width: calc(100% - 22px); /* Full width minus padding */
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        .contact-form input[type="text"]:focus,
        .contact-form input[type="email"]:focus,
        .contact-form textarea:focus {
            border-color: #0073aa;
            outline: none;
        }
        .contact-form input[type="submit"] {
            background-color: #0073aa;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%; /* Full width for submit button */
        }
        .contact-form input[type="submit"]:hover {
            background-color: #005177;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            display: block;
        }
        .alert-danger {
            background-color: #f44336;
            color: white;
        }
        .alert-success {
            background-color: #4CAF50;
            color: white;
        }
    </style>

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
    <?php
    return ob_get_clean();
}
add_shortcode('ishfaaq_contact_form', 'ishfaaq_contact_form_shortcode');

add_action('phpmailer_init', 'custom_smtp_setup');
function custom_smtp_setup($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'mail.armedia.lk'; // Replace with your SMTP server
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'testing@armedia.lk';
    $phpmailer->Password = 'Iishfaaq@12345';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From = 'testing@armedia.lk';
    $phpmailer->FromName = 'ishfaaq Testing';

}

