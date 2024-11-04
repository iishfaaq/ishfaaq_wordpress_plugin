<?php
// Include the HTML form template and CSS styling
include 'smtp-config.php';

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

        // Get receiver's email from settings
        $options = get_option('ishfaaq_email_plugin_settings');
        $receiver_email = isset($options['rec_email_id']) ? $options['rec_email_id'] : '';
        $ack_email = isset($options['ack_email_id']) ? $options['ack_email_id'] : '';
        $ack_body = isset($options['email_body']) ? $options['email_body'] : '';

        // Validate fields
        if (empty($name) || !is_email($sender_email) || empty($message)) {
            echo '<div class="alert alert-danger">Please provide your name, a valid email, and a message.</div>';
        } else {
            // Save message to database
            $wpdb->insert(
                $wpdb->prefix . 'contact_messages',
                [
                    'name' => $name,
                    'contact_number' => $contact_number,
                    'email' => $sender_email,
                    'message' => $message,
                    'date' => current_time('mysql')
                ],
                ['%s', '%s', '%s', '%s', '%s']
            );

            // Send email notification
            $subject = 'New Message from Contact Form';
            $email_body = "New message:\n\nName: $name\nContact: $contact_number\nEmail: $sender_email\nMessage:\n$message\n";

            if (wp_mail($receiver_email, $subject, $email_body)) {
                echo '<div class="alert alert-success">Your message has been sent successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Failed to send the email.</div>';
            }

            // Set acknowledgment email
            wp_mail($sender_email, 'This is an acknowledgement', $ack_body);
        }
    }

    // Output form template
    ob_start();
    include 'form-template.php';
    return ob_get_clean();
}
add_shortcode('ishfaaq_contact_form', 'ishfaaq_contact_form_shortcode');
