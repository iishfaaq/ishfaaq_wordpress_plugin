<?php
// Register a new menu item in the admin dashboard
function ishfaaq_register_messages_menu() {
    add_menu_page(
        'Ishfaaq plugin Messages',       // Page title
        'Ishfaaq Plugin Messages',       // Menu title
        'manage_options',         // Capability
        'contact_messages',       // Menu slug
        'ishfaaq_display_messages_page', // Callback function to display the messages
        'dashicons-email',        // Icon
        90                        // Position
    );
}
add_action('admin_menu', 'ishfaaq_register_messages_menu');

// Display messages page content
function ishfaaq_display_messages_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_messages';
    $messages = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date DESC");

    ?>
    <div class="wrap">
        <h1>Contact Messages</h1>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th class="manage-column">Name</th>
                    <th class="manage-column">Contact Number</th>
                    <th class="manage-column">Email</th>
                    <th class="manage-column">Message</th>
                    <th class="manage-column">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message) : ?>
                    <tr>
                        <td><?php echo esc_html($message->name); ?></td>
                        <td><?php echo esc_html($message->contact_number); ?></td>
                        <td><?php echo esc_html($message->email); ?></td>
                        <td><?php echo esc_html($message->message); ?></td>
                        <td><?php echo esc_html($message->date); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
