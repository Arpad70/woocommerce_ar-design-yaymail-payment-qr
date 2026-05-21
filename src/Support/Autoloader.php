<?php

declare(strict_types=1);

namespace ArDesign\YaymailPaymentQr\Support;

final class Autoloader
{
	private const NAMESPACE_PREFIX = 'ArDesign\\YaymailPaymentQr\\';

	public static function register(): void
	{
		spl_autoload_register( array( self::class, 'autoload' ) );
	}

	private static function autoload( string $class_name ): void
	{
		if ( 0 !== strpos( $class_name, self::NAMESPACE_PREFIX ) ) {
			return;
		}

		$relative_class = substr( $class_name, strlen( self::NAMESPACE_PREFIX ) );
		$relative_path  = str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';
		$file_path      = ARD_YAYMAIL_PAYMENT_QR_PATH . 'src/' . $relative_path;

		if ( is_readable( $file_path ) ) {
			require_once $file_path;
		}
	}
}
