var WCCG_Generator = {

	init: function() {
		this.generate_coupons( 0, this );
	},
	generate_coupons: function( batch_step, self ) {

		var data = {
			'action': 		'wccg_generate_coupons',
			'form_data': 	jQuery( '#wc-coupon-generator-form' ).serialize(),
			'batch_step': 	batch_step
		};

		jQuery.post ( ajaxurl, data, function( response ) {

			response = JSON.parse( response );

			if ( response.message ) {
				self.add_message( response.message );
			}

			if ( 'done' == response.step ) {
				self.completed( self, response.total_coupons_generated, response.total_coupons_generated );
			} else {
				self.generate_coupons( response.step, self );
			}
			self.progress_bar( response.progress );

		});

	},
	progress_bar: function( progress ) {
		jQuery( '.wc-coupon-generator-progress-bar .progress' ).css( 'width', progress + '%' );
		jQuery( '.wc-coupon-generator-progress-percentage' ).html( progress + '%' );
		jQuery( '.inner-progress' ).css( 'width', jQuery( '.wc-coupon-generator-progress-bar' ).width() );
	},
	completed: function( self, coupons_genrated, execution_time ) {
		jQuery( '.wc-coupon-generator-progress-bar + .spinner' ).remove();
	},
	add_message: function( message ) {
		jQuery( '.wc-coupon-generator-progress-messages' ).prepend( '<span class="wc-coupon-generator-progress-message">' + message + '</span><br/>' );
	}

};
