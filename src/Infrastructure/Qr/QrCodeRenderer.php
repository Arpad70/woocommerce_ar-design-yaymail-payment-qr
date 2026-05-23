<?php

declare(strict_types=1);

namespace ArDesign\YaymailPaymentQr\Infrastructure\Qr;

defined( 'ABSPATH' ) || exit;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Writer\PngWriter;
use RuntimeException;

final class QrCodeRenderer
{
	public function renderPng( string $payload, int $size ): string
	{
		if ( '' === $payload ) {
			throw new RuntimeException( 'QR payload cannot be empty.' );
		}

		$result = Builder::create()
			->writer( new PngWriter() )
			->data( $payload )
			->encoding( new Encoding( 'UTF-8' ) )
			->errorCorrectionLevel( new ErrorCorrectionLevelLow() )
			->size( max( 120, min( 512, $size ) ) )
			->margin( 0 )
			->build();

		return $result->getString();
	}
}