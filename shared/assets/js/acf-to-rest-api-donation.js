( function( $ ) {
	$( '.acf-to-rest-api-donation-button-notice-dismiss' ).click( function( e ) {
		e.preventDefault();
		$( this ).closest('.acf-to-rest-api-donation-notice').slideUp();
		$.post( acf_to_rest_api_donation.ajax_url, {
			action: 'acf_to_rest_api_dismiss_notice',
			nonce: acf_to_rest_api_donation.nonce
		} );
	} );
} )( jQuery );