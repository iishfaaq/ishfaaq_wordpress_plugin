<?php

function ishfaaq_email_plugin_menu() {
    add_menu_page(
        'Ishfaaq Email Plugin Settings',  
        'Ishfaaq Email',           
        'manage_options',             
        'ishfaaq-plugin',           
        'ishfaaq_email_plugin_options_page', 
        'dashicons-email-alt2',    
        100                            
    );
}

function ishfaaq_email_plugin_options_page() {
    ?>
    <div class="wrap">
        <h1>Ishfaaq Email Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('ishfaaq_email_plugin_options');
            do_settings_sections('ishfaaq-plugin');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}


function ishfaaq_email_plugin_settings() {
    register_setting(
                'ishfaaq_email_plugin_options',
                'ishfaaq_email_plugin_settings',
                'ishfaaq_email_plugin_sanitize');

    add_settings_section('ishfaaq_email_plugin_main_section', 'Main Settings', 'ishfaaq_email_plugin_section_text', 'ishfaaq-plugin');
        
    add_settings_field(
        'ishfaaq_email_plugin_rec_email_id', 
        'Receiving Email ID', 
        'ishfaaq_email_plugin_rec_email_id_input', 
        'ishfaaq-plugin', 
        'ishfaaq_email_plugin_main_section'
    );
    
    // add_settings_field(
    //     'ishfaaq_email_plugin_ack_email_id', 
    //     'Acknowledgement Email ID', 
    //     'ishfaaq_email_plugin_ack_email_id_input', 
    //     'ishfaaq-plugin', 
    //     'ishfaaq_email_plugin_main_section'
    // );

    add_settings_field(
        'ishfaaq_email_plugin_email_body', 
        'Acknowledgement Description', 
        'ishfaaq_email_plugin_email_body_input', 
        'ishfaaq-plugin', 
        'ishfaaq_email_plugin_main_section'
    );
}

function ishfaaq_email_plugin_section_text() {
    echo '<div style="border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9; margin-bottom: 15px;">';
    echo '<p><strong>Ishfaaq Email Plugin:</strong> This plugin allows you to manage email settings directly from the WordPress dashboard. Configure options to suit your email needs easily and efficiently.</p>';
    echo '</div>';
}

function ishfaaq_email_plugin_rec_email_id_input() {
    $options = get_option('ishfaaq_email_plugin_settings');
    echo '<input type="email" name="ishfaaq_email_plugin_settings[rec_email_id]" value="' . (isset($options['rec_email_id']) ? esc_attr($options['rec_email_id']) : '') . '" />';
    echo '<p class="description">This email ID will be used to receive the emails.</p>'; 
}

// function ishfaaq_email_plugin_ack_email_id_input() {
//     $options = get_option('ishfaaq_email_plugin_settings');
//     echo '<input type="email" name="ishfaaq_email_plugin_settings[ack_email_id]" value="' . (isset($options['ack_email_id']) ? esc_attr($options['ack_email_id']) : '') . '" />';
//     echo '<p class="description">This email ID will be used to send the emails.</p>'; 
// }


function ishfaaq_email_plugin_email_body_input() {
    $options = get_option('ishfaaq_email_plugin_settings');
    echo '<textarea name="ishfaaq_email_plugin_settings[email_body]" rows="5" cols="50">' . (isset($options['email_body']) ? esc_textarea($options['email_body']) : '') . '</textarea>';
    echo '<p class="description">This body text will be included in the emails sent.</p>'; 
}





function ishfaaq_email_plugin_sanitize($input) {
    $sanitized_input = array();

    if (isset($input['rec_email_id'])) {
        $sanitized_input['rec_email_id'] = sanitize_email($input['rec_email_id']);
    }

    // if (isset($input['ack_email_id'])) {
    //     $sanitized_input['ack_email_id'] = sanitize_email($input['ack_email_id']);
    // }

    if (isset($input['email_body'])) {
        $sanitized_input['email_body'] = sanitize_textarea_field($input['email_body']);
    }

    return $sanitized_input;
}


function ishfaaq_email_plugin_admin_notice() {

    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
        echo '<div class="notice notice-success is-dismissible">
            <p>Settings have been saved successfully!</p>
        </div>';
    }
}
add_action('admin_notices', 'ishfaaq_email_plugin_admin_notice');