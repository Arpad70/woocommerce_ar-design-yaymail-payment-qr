<?php
/**
 * Plugin Name: Ar Design YayMail Payment QR
 * Description: Pridáva shortcode pre YayMail blok s platobnými údajmi a dynamickým QR kódom pre WooCommerce objednávky.
 * Version: 0.1.1
 * Author: Arpád Horák
 * Update URI: https://github.com/Arpad70/woocommerce_ar-design-yaymail-payment-qr
 * Requires at least: 6.7
 * Requires PHP: 8.0
 * Text Domain: ar-design-yaymail-payment-qr
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ARD_YAYMAIL_PAYMENT_QR_VERSION', '0.1.1' );
define( 'ARD_YAYMAIL_PAYMENT_QR_FILE', __FILE__ );
define( 'ARD_YAYMAIL_PAYMENT_QR_BASENAME', plugin_basename( __FILE__ ) );
define( 'ARD_YAYMAIL_PAYMENT_QR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ARD_YAYMAIL_PAYMENT_QR_URL', plugin_dir_url( __FILE__ ) );
define( 'ARD_YAYMAIL_PAYMENT_QR_GITHUB_REPOSITORY', 'Arpad70/woocommerce_ar-design-yaymail-payment-qr' );
define( 'ARD_YAYMAIL_PAYMENT_QR_SLUG', 'ar-design-yaymail-payment-qr' );

require_once ARD_YAYMAIL_PAYMENT_QR_PATH . 'bootstrap/autoload.php';

ArDesign\YaymailPaymentQr\Support\Autoloader::register();

register_activation_hook( ARD_YAYMAIL_PAYMENT_QR_FILE, array( 'ArDesign\\YaymailPaymentQr\\Application\\Bootstrap', 'activate' ) );
register_deactivation_hook( ARD_YAYMAIL_PAYMENT_QR_FILE, array( 'ArDesign\\YaymailPaymentQr\\Application\\Bootstrap', 'deactivate' ) );

ArDesign\YaymailPaymentQr\Application\Bootstrap::boot()->run();
