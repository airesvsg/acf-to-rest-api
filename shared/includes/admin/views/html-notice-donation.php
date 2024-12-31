<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="notice acf-to-rest-api-donation-notice">
	<p><span class="acf-to-rest-api-donation-plugin-name">{ <?php esc_html_e( 'ACF to REST-API', 'acf-to-rest-api' ); ?> }</span> <a href="<?php echo esc_url( self::DONATION_URL ); ?>" target="_blank" class="acf-to-rest-api-donation-button"> <span class="dashicons dashicons-heart"></span> <?php echo esc_html_e( 'Make a donation', 'acf-to-rest-api' ); ?></a></p>
	<button type="button" class="notice-dismiss acf-to-rest-api-donation-button-notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
</div>
