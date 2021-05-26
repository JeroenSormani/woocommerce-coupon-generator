var WCCG_Generator = {

	init: function() {
		this.generate_coupons( 0, this );
	},
	generate_coupons: function( batch_step, self ) {

		var data = {
			'action': 		'wccg_generate_coupons',
			'form_data': 	jQuery( '.wccg-wrap :input' ).serialize(),
			'batch_step': 	batch_step
		};

		jQuery.post ( ajaxurl, data, function( response ) {

			response = JSON.parse( response );

			if ( response.message ) {
				self.add_message( response.message );
			}

			if ( 'done' == response.step ) {
				self.completed( self, parseInt(jQuery( '[name="number_of_coupons"]' ).val()), response.total_coupons_generated );
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
	completed: function( self, coupons_generated, execution_time ) {
		jQuery( '.wc-coupon-generator-progress-bar + .spinner' ).remove();
		jQuery( '.wc-coupon-generator-completed-actions' ).show();

		var actions = jQuery('.wc-coupon-generator-completed-actions');
			actions.show();
			actions.html(actions.html().replace( /{{ couponGenerator.quantity }}/, coupons_generated ));

		var exportAnchor = actions.find('a');
		exportAnchor.attr('href', exportAnchor.attr('href').replace('quantity', 'quantity=' + coupons_generated));
	},
	add_message: function( message ) {
		jQuery( '.wc-coupon-generator-progress-messages' ).prepend( '<span class="wc-coupon-generator-progress-message">' + message + '</span><br/>' );
	}

};

(function($) {
	$('.wccg-next').on('click', function (e) {
		var activeStep = $('.active-step');
		var nextStep = activeStep.next();

		activeStep.removeClass('active-step').addClass('hidden');
		nextStep.removeClass('hidden').addClass('active-step');

		$('.step.active').removeClass('active').next().addClass('active');
	});

	$('.wccg-start').on('click', function (e) {
		WCCG_Generator.init();
	});
})(jQuery);
