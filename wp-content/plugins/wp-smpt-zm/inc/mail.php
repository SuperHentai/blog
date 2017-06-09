<?php


/**
 * If set to SMPT filter the mail settings, if not return
 * Bulk of this code is copied, from wp-includes/pluggable.php as at version 2.2.2
 *
 * @since 1.0.0
 * @param $phpmailer The phpmailer object
 */
function wp_mail_smtp_zm_phpmailer_init_smtp( $phpmailer ){

    global $wp_mail_smtp_settings;

    // Check that mailer is not blank, and if mailer=smtp, host is not blank
    if ( ! $wp_mail_smtp_settings['mailer'] || ( $wp_mail_smtp_settings['mailer'] == 'smtp' && ! $wp_mail_smtp_settings['smtp_host'] ) ) {

        return;

    } else {

        if ( isset( $_POST['action'] ) && $_POST['action'] == 'wp_mail_smtp_zm_ajax' ){
            $phpmailer->SMTPDebug = true;
        }

        // Set the mailer type as per config above, this overrides the already called isMail method
        $phpmailer->Mailer = $wp_mail_smtp_settings['mailer'];

        // Set the Sender (return-path) if required
        if ( $wp_mail_smtp_settings['return_path'] == 'yes' )
            $phpmailer->Sender = $phpmailer->From;

        // Set the SMTPSecure value, if set to none, leave this blank
        $phpmailer->SMTPSecure = $wp_mail_smtp_settings['encryption'] == 'none' ? '' : $wp_mail_smtp_settings['encryption'];

        // If we're sending via SMTP, set the host
        if ( $wp_mail_smtp_settings['mailer'] == "smtp" ){

            // Set the other options
            $phpmailer->Host = $wp_mail_smtp_settings['smtp_host'];
            $phpmailer->Port = $wp_mail_smtp_settings['smtp_port'];

            // If we're using smtp auth, set the username & password
            if ( $wp_mail_smtp_settings['authentication'] == "yes" ){
                $phpmailer->SMTPAuth = TRUE;
                $phpmailer->Username = $wp_mail_smtp_settings['username'];
                $phpmailer->Password = wp_mail_smtp_zm_decrypted( WP_MAIL_SMTP_ZM_NAMESPACE, $wp_mail_smtp_settings['password'] );
            }
        }

    }

}
add_action('phpmailer_init','wp_mail_smtp_zm_phpmailer_init_smtp');


/**
 * Add the from address
 *
 * @since 1.0.0
 * @param   The original from
 */
function wp_mail_smtp_zm_mail_from( $orig ){

    global $wp_mail_smtp_settings;
    $mail_from = $wp_mail_smtp_settings['from_email'];

    // This is copied from pluggable.php lines 348-354 as at revision 10150
    // http://trac.wordpress.org/browser/branches/2.7/wp-includes/pluggable.php#L348

    // Get the site domain and get rid of www.
    $sitename = strtolower( $_SERVER['SERVER_NAME'] );
    if ( substr( $sitename, 0, 4 ) == 'www.' ) {
        $sitename = substr( $sitename, 4 );
    }

    $default_from = 'wordpress@' . $sitename;
    // End of copied code

    // If the from email is not the default, return it unchanged
    if ( $orig != $default_from ) {
        return $orig;
    }

    if ( is_email( $mail_from, false ) )
        return $mail_from;

    // If in doubt, return the original value
    return $orig;

}
add_filter('wp_mail_from','wp_mail_smtp_zm_mail_from');


/**
 * Filter the from name
 *
 * @since 1.0.0
 * @param $orig The original from name
 */
function wp_mail_smtp_zm_mail_from_name( $orig ){

    global $wp_mail_smtp_settings;
    $mail_from_name = $wp_mail_smtp_settings['from_name'];

    return $mail_from_name;

}
add_filter('wp_mail_from_name','wp_mail_smtp_zm_mail_from_name');