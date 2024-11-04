<?php
/**
 * Plugin Name: Ishfaaq Plugin
 * Description: This is an email testing plugin to send email from forms.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    die('Access Denied');
}

if (!class_exists('ishfaaqContactPlugin')) {

    class ishfaaqContactPlugin {

        public function __construct() {
            // Include files for options, frontend form, and display
            require_once plugin_dir_path(__FILE__) . 'Options.php';
            add_action('admin_menu', 'ishfaaq_email_plugin_menu');
            add_action('admin_init', 'ishfaaq_email_plugin_settings');

           
            require_once plugin_dir_path(__FILE__) . 'contact_form.php';
            require_once plugin_dir_path(__FILE__) . 'display_emails.php';
        }

    }

    new ishfaaqContactPlugin;

    // Include create_db.php for table creation function
    require_once plugin_dir_path(__FILE__) . 'create_db.php';

    // Register the activation hook to create the table
    register_activation_hook(__FILE__, 'ishfaaq_create_messages_table');
    add_action('wp_mail_failed', 'log_mail_error');
    function log_mail_error($wp_error) {
        error_log('Mail Error: ' . print_r($wp_error, true));
    }

    add_filter('wp_mail_from', function() {
        return 'testing@armedia.lk'; // Replace with a valid email address
    });
}
