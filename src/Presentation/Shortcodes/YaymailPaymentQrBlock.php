<?php

declare(strict_types=1);

namespace ArDesign\YaymailPaymentQr\Presentation\Shortcodes;

use WC_Order;
use WP_Post;

final class YaymailPaymentQrBlock
{
	private const SHORTCODE = 'ard_yaymail_payment_qr_block';

	/**
	 * @var array<int, WC_Order|null>
	 */
	private static array $yaymail_order_context_stack = array();

	public function register(): void
	{
		add_shortcode( self::SHORTCODE, array( $this, 'render' ) );
		add_action( 'yaymail_before_email_content', array( $this, 'captureYaymailOrderContext' ), 10, 2 );
		add_action( 'yaymail_after_email_content', array( $this, 'releaseYaymailOrderContext' ), 10, 2 );
	}

	/**
	 * @param mixed $template
	 * @param mixed $render_data
	 */
	public function captureYaymailOrderContext( $template, $render_data ): void
	{
		unset( $template );

		$order = null;

		if ( is_array( $render_data ) && isset( $render_data['order'] ) && $render_data['order'] instanceof WC_Order ) {
			$order = $render_data['order'];
		}

		self::$yaymail_order_context_stack[] = $order;
	}

	/**
	 * @param mixed $template
	 * @param mixed $render_data
	 */
	public function releaseYaymailOrderContext( $template, $render_data ): void
	{
		unset( $template, $render_data );

		if ( ! empty( self::$yaymail_order_context_stack ) ) {
			array_pop( self::$yaymail_order_context_stack );
		}
	}

	/**
	 * @param array<string, string> $atts
	 */
	public function render( $atts ): string
	{
		$atts = shortcode_atts(
			array(
				'order_id'             => '',
				'preview_order_number' => '14092',
				'preview_amount'       => '20.03',
				'company'              => 'AR DESIGN s.r.o.',
				'bank'                 => 'Všeobecná úverová banka, a.s.',
				'iban'                 => 'SK04 0200 0000 0038 7078 8755',
				'bic'                  => 'SUBASKBX',
				'currency'             => 'EUR',
				'qr_size'              => '180',
			),
			is_array( $atts ) ? $atts : array(),
			self::SHORTCODE
		);

		$order = $this->resolveOrder( $atts );

		$company  = trim( (string) $atts['company'] );
		$bank     = trim( (string) $atts['bank'] );
		$iban_raw = trim( (string) $atts['iban'] );
		$iban     = preg_replace( '/\s+/', '', $iban_raw ) ?: '';
		$bic      = trim( (string) $atts['bic'] );
		$currency = strtoupper( trim( (string) $atts['currency'] ) );
		$qr_size  = max( 120, (int) $atts['qr_size'] );
		$variable_symbol = '';

		if ( $order instanceof WC_Order ) {
			$order_number   = (string) $order->get_order_number();
			$order_currency = strtoupper( (string) $order->get_currency() );
			$currency       = '' !== $order_currency ? $order_currency : $currency;
			$amount_numeric = number_format( (float) $order->get_total(), 2, '.', '' );
			$amount_display = $this->formatAmountDisplay( (float) $order->get_total(), $currency );
			$variable_symbol = $this->resolveVariableSymbol( $order_number, (string) $order->get_id() );
		} else {
			$order_number   = (string) $atts['preview_order_number'];
			$preview_amount = (float) str_replace( ',', '.', (string) $atts['preview_amount'] );
			$amount_numeric = number_format( $preview_amount, 2, '.', '' );
			$amount_display = $this->formatAmountDisplay( (float) $amount_numeric, $currency );
			$variable_symbol = $this->resolveVariableSymbol( $order_number, $order_number );
		}

		$payment_payload = sprintf(
			'SPD*1.0*ACC:%s*AM:%s*CC:%s*X-VS:%s*MSG:%s',
			$iban,
			$amount_numeric,
			$currency,
			$variable_symbol,
			'Objednavka ' . $order_number
		);

		$qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=' . rawurlencode( $qr_size . 'x' . $qr_size ) . '&data=' . rawurlencode( $payment_payload );

		ob_start();
		?>
		<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%; border-collapse:collapse;">
			<tr>
				<td valign="top" width="60%" style="width:60%; padding:0 20px 0 0;">
					<h3 style="margin:0 0 12px 0; font-size:18px; line-height:1.4; font-weight:700;">
						<?php echo esc_html( $company ); ?>
					</h3>

					<p style="margin:0 0 8px 0; font-size:14px; line-height:1.6;">
						<strong><?php esc_html_e( 'Banka:', 'ar-design-yaymail-payment-qr' ); ?></strong> <?php echo esc_html( $bank ); ?>
					</p>

					<p style="margin:0 0 8px 0; font-size:14px; line-height:1.6;">
						<strong><?php esc_html_e( 'IBAN:', 'ar-design-yaymail-payment-qr' ); ?></strong> <?php echo esc_html( $iban_raw ); ?>
					</p>

					<p style="margin:0 0 8px 0; font-size:14px; line-height:1.6;">
						<strong><?php esc_html_e( 'BIC:', 'ar-design-yaymail-payment-qr' ); ?></strong> <?php echo esc_html( $bic ); ?>
					</p>

					<p style="margin:0 0 8px 0; font-size:14px; line-height:1.6;">
						<strong><?php esc_html_e( 'Variabilný symbol:', 'ar-design-yaymail-payment-qr' ); ?></strong> <?php echo esc_html( $variable_symbol ); ?>
					</p>

					<p style="margin:0; font-size:14px; line-height:1.6;">
						<strong><?php esc_html_e( 'Suma:', 'ar-design-yaymail-payment-qr' ); ?></strong> <?php echo esc_html( $amount_display ); ?>
					</p>
				</td>

				<td valign="top" width="40%" align="right" style="width:40%; text-align:right;">
					<img
						src="<?php echo esc_url( $qr_url ); ?>"
						alt="<?php esc_attr_e( 'QR kód pre platbu', 'ar-design-yaymail-payment-qr' ); ?>"
						width="<?php echo (int) $qr_size; ?>"
						height="<?php echo (int) $qr_size; ?>"
						style="display:block; width:<?php echo (int) $qr_size; ?>px; max-width:<?php echo (int) $qr_size; ?>px; height:auto; border:0; margin-left:auto;"
					/>
					<p style="margin:8px 0 0 0; font-size:12px; color:#666666; line-height:1.4;">
						<?php esc_html_e( 'Naskenujte QR kód vo svojej bankovej aplikácii.', 'ar-design-yaymail-payment-qr' ); ?>
					</p>
				</td>
			</tr>
		</table>
		<?php

		return (string) ob_get_clean();
	}

	/**
	 * @param array<string, string> $atts
	 */
	private function resolveOrder( array $atts ): ?WC_Order
	{
		if ( ! function_exists( 'wc_get_order' ) ) {
			return null;
		}

		if ( '' !== (string) $atts['order_id'] ) {
			$order = wc_get_order( (int) $atts['order_id'] );
			if ( $order instanceof WC_Order ) {
				return $order;
			}
		}

		$yaymail_context_order = $this->getCurrentYaymailContextOrder();

		if ( $yaymail_context_order instanceof WC_Order ) {
			return $yaymail_context_order;
		}

		global $order;

		if ( $order instanceof WC_Order ) {
			return $order;
		}

		if ( isset( $GLOBALS['order'] ) && $GLOBALS['order'] instanceof WC_Order ) {
			return $GLOBALS['order'];
		}

		if ( isset( $GLOBALS['post'] ) && $GLOBALS['post'] instanceof WP_Post && 'shop_order' === $GLOBALS['post']->post_type ) {
			$post_order = wc_get_order( (int) $GLOBALS['post']->ID );
			if ( $post_order instanceof WC_Order ) {
				return $post_order;
			}
		}

		return null;
	}

	private function getCurrentYaymailContextOrder(): ?WC_Order
	{
		for ( $index = count( self::$yaymail_order_context_stack ) - 1; $index >= 0; $index-- ) {
			$order = self::$yaymail_order_context_stack[ $index ] ?? null;

			if ( $order instanceof WC_Order ) {
				return $order;
			}
		}

		return null;
	}

	private function resolveVariableSymbol( string $preferred_value, string $fallback_value ): string
	{
		$preferred_digits = preg_replace( '/\D+/', '', $preferred_value ) ?: '';

		if ( '' !== $preferred_digits ) {
			return $preferred_digits;
		}

		$fallback_digits = preg_replace( '/\D+/', '', $fallback_value ) ?: '';

		return $fallback_digits;
	}

	private function formatAmountDisplay( float $amount, string $currency ): string
	{
		if ( function_exists( 'wc_price' ) ) {
			return html_entity_decode(
				wp_strip_all_tags(
					wc_price(
						$amount,
						array(
							'currency' => $currency,
						)
					)
				),
				ENT_QUOTES,
				'UTF-8'
			);
		}

		return number_format( $amount, 2, ',', ' ' ) . ' ' . $currency;
	}
}
