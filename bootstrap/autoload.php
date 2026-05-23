<?php

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$composer_autoloader = ARD_YAYMAIL_PAYMENT_QR_PATH . 'vendor/autoload.php';

if ( is_readable( $composer_autoloader ) ) {
	require_once $composer_autoloader;
}

require_once ARD_YAYMAIL_PAYMENT_QR_PATH . 'src/Support/Autoloader.php';
require_once ARD_YAYMAIL_PAYMENT_QR_PATH . 'src/Support/Updates/GitHubUpdater.php';
require_once ARD_YAYMAIL_PAYMENT_QR_PATH . 'src/Support/Updates/RollbackManager.php';
require_once ARD_YAYMAIL_PAYMENT_QR_PATH . 'src/Presentation/Shortcodes/YaymailPaymentQrBlock.php';
require_once ARD_YAYMAIL_PAYMENT_QR_PATH . 'src/Application/Bootstrap.php';
