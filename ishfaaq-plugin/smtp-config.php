<?php
add_action('phpmailer_init', 'custom_smtp_setup');
function custom_smtp_setup($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'mail.armedia.lk';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'testing@armedia.lk';
    $phpmailer->Password = 'Iishfaaq@12345';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From = 'testing@armedia.lk';
    $phpmailer->FromName = 'ishfaaq Plugin';
}
?>
