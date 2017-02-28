<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_installed = ACF_To_REST_API::is_plugin_installed( 'acf' );

$target = false;
$action = __( 'Install', 'acf-to-rest-api' );
if ( current_user_can( 'install_plugins' ) ) {
	if ( $is_installed ) {
		$action = __( 'Active', 'acf-to-rest-api' );
		$url    = wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . $is_installed . '&plugin_status=active' ), 'activate-plugin_' . $is_installed );
	} else {
		$url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=advanced-custom-fields' ), 'install-plugin_advanced-custom-fields' );
	}
} else {
	$target = true;
	$url    = 'http://wordpress.org/plugins/advanced-custom-fields/';
}

?>

<div class="notice error is-dismissible">
	<p><strong><?php esc_html_e( 'ACF to REST API', 'act-to-rest-api' ); ?></strong> <?php esc_html_e( 'depends on the last version of Advanced Custom Fields to work!', 'acf-to-rest-api' ); ?></p>
	<p><a href="<?php echo esc_url( $url ); ?>" class="button button-primary"<?php if ( $target ) : ?> target="_blank"<?php endif; ?>><?php esc_html_e( $action . ' Advanced Custom Fields', 'acf-to-rest-api' ); ?></a></p>
</div>
