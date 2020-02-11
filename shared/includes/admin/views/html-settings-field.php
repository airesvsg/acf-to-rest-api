<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="acf-to-rest-api-settings">
	<code><?php echo esc_url( home_url( 'wp-json/acf/' ) ); ?></code>
	<select name="acf_to_rest_api_settings[request_version]">
		<option value="2"<?php selected( 2, $request_version ); ?>>v2</option>
		<option value="3"<?php selected( 3, $request_version ); ?>>v3</option>
	</select>
	<p><a href="<?php echo esc_url( ACF_To_REST_API_Donation::DONATION_URL ); ?>" target="_blank" class="acf-to-rest-api-donation-button"><span class="dashicons dashicons-heart"></span>  <?php esc_html_e( 'Make a donation', 'acf-to-rest-api' ); ?></a> <?php esc_html_e( 'and help to improve the ACF to REST-API plugin.', 'acf-to-rest-api' ); ?></p>
</div>
