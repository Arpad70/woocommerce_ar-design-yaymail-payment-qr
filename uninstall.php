<?php
/**
 * Uninstall hook for Ar Design YayMail Payment QR.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'ard_yaymail_payment_qr_version' );
