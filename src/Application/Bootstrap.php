<?php

declare(strict_types=1);

namespace ArDesign\YaymailPaymentQr\Application;

defined( 'ABSPATH' ) || exit;

use ArDesign\YaymailPaymentQr\Presentation\Shortcodes\YaymailPaymentQrBlock;
use ArDesign\YaymailPaymentQr\Support\Updates\GitHubUpdater;
use ArDesign\YaymailPaymentQr\Support\Updates\RollbackManager;

final class Bootstrap
{
	private static ?self $instance = null;

	private GitHubUpdater $updater;

	private RollbackManager $rollback_manager;

	private YaymailPaymentQrBlock $shortcode;

	private function __construct()
	{
		$this->updater          = new GitHubUpdater(
			ARD_YAYMAIL_PAYMENT_QR_GITHUB_REPOSITORY,
			ARD_YAYMAIL_PAYMENT_QR_BASENAME,
			ARD_YAYMAIL_PAYMENT_QR_VERSION,
			ARD_YAYMAIL_PAYMENT_QR_SLUG,
			'Ar Design YayMail Payment QR',
			'ar-design-yaymail-payment-qr',
			'Pridáva shortcode pre YayMail blok s platobnými údajmi a dynamickým QR kódom pre WooCommerce objednávky.'
		);
		$this->rollback_manager = new RollbackManager( ARD_YAYMAIL_PAYMENT_QR_BASENAME, ARD_YAYMAIL_PAYMENT_QR_PATH );
		$this->shortcode        = new YaymailPaymentQrBlock();
	}

	public static function boot(): self
	{
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function run(): void
	{
		add_action( 'init', array( $this, 'loadTextDomain' ) );
		add_action( 'rest_api_init', array( $this->shortcode, 'registerRestRoutes' ) );
		add_filter( 'rest_pre_serve_request', array( $this->shortcode, 'serveQrCodeResponse' ), 10, 4 );
		add_action( 'plugins_loaded', array( $this, 'bootstrapRuntime' ), 20 );
	}

	public function loadTextDomain(): void
	{
		load_plugin_textdomain(
			'ar-design-yaymail-payment-qr',
			false,
			dirname( ARD_YAYMAIL_PAYMENT_QR_BASENAME ) . '/languages/'
		);
	}

	public function bootstrapRuntime(): void
	{
		$this->updater->register();
		$this->rollback_manager->register();
		$this->shortcode->register();

		if ( is_admin() ) {
			add_action( 'admin_notices', array( $this, 'renderDependencyNotice' ) );
		}
	}

	public static function activate(): void
	{
		update_option( 'ard_yaymail_payment_qr_version', ARD_YAYMAIL_PAYMENT_QR_VERSION );
	}

	public static function deactivate(): void
	{
		// Intentionally left blank.
	}

	public function renderDependencyNotice(): void
	{
		if ( $this->hasDependencies() ) {
			return;
		}

		if ( ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'manage_options' ) ) {
			return;
		}

		echo '<div class="notice notice-warning"><p>';
		echo esc_html__( 'Ar Design YayMail Payment QR pre plnú funkcionalitu vyžaduje aktívny WooCommerce. YayMail je odporúčaný pre použitie shortcode v e-mailoch.', 'ar-design-yaymail-payment-qr' );
		echo '</p></div>';
	}

	private function hasDependencies(): bool
	{
		return function_exists( 'wc_get_order' );
	}
}
