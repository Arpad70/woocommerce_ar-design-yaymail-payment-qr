<?php
/**
 * Plugin Name: Ar Design YayMail Payment QR
 * Plugin URI: https://github.com/Arpad70/woocommerce_ar-design-yaymail-payment-qr
 * Description: Pridáva shortcode pre YayMail blok s platobnými údajmi a dynamickým QR kódom pre WooCommerce objednávky.
 * Version: 0.1.6
 * Author: Arpád Horák
 * Author URI: https://arpad-horak.cz
 * Developer: Arpád Horák
 * Developer URI: https://arpad-horak.cz
 * Update URI: https://github.com/Arpad70/woocommerce_ar-design-yaymail-payment-qr
 * Requires Plugins: ar-design-shared-support
 * Requires at least: 6.7
 * Requires PHP: 8.0
 * Text Domain: ar-design-yaymail-payment-qr
 * Domain Path: /languages
 * WC requires at least: 7.0
 * WC tested up to: 10.6.1
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ARD_YAYMAIL_PAYMENT_QR_VERSION', '0.1.6' );
define( 'ARD_YAYMAIL_PAYMENT_QR_FILE', __FILE__ );
define( 'ARD_YAYMAIL_PAYMENT_QR_BASENAME', plugin_basename( __FILE__ ) );
define( 'ARD_YAYMAIL_PAYMENT_QR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ARD_YAYMAIL_PAYMENT_QR_URL', plugin_dir_url( __FILE__ ) );
define( 'ARD_YAYMAIL_PAYMENT_QR_GITHUB_REPOSITORY', 'Arpad70/woocommerce_ar-design-yaymail-payment-qr' );
define( 'ARD_YAYMAIL_PAYMENT_QR_SLUG', 'ar-design-yaymail-payment-qr' );

add_action(
	'before_woocommerce_init',
	static function (): void {
		if ( class_exists( '\\Automattic\\WooCommerce\\Utilities\\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', ARD_YAYMAIL_PAYMENT_QR_FILE, true );
		}
	}
);

require_once ARD_YAYMAIL_PAYMENT_QR_PATH . 'bootstrap/autoload.php';

ArDesign\YaymailPaymentQr\Support\Autoloader::register();

if ( ! function_exists( 'ard_yaymail_payment_qr_render_block' ) ) {
	/**
	 * Render shared payment QR block for YayMail or invoice templates.
	 *
	 * @param array<string, string> $atts Block attributes.
	 */
	function ard_yaymail_payment_qr_render_block( array $atts = array() ): string {
		$renderer = new ArDesign\YaymailPaymentQr\Presentation\Shortcodes\YaymailPaymentQrBlock();

		return $renderer->render( $atts );
	}
}

register_activation_hook( ARD_YAYMAIL_PAYMENT_QR_FILE, array( 'ArDesign\\YaymailPaymentQr\\Application\\Bootstrap', 'activate' ) );
register_deactivation_hook( ARD_YAYMAIL_PAYMENT_QR_FILE, array( 'ArDesign\\YaymailPaymentQr\\Application\\Bootstrap', 'deactivate' ) );

ArDesign\YaymailPaymentQr\Application\Bootstrap::boot()->run();
