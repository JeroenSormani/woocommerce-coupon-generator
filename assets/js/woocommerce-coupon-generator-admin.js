( function( $ ) {

	var WCCG_Generator = {

		validation_messages: [],
		$form: $( '#wc-coupon-generator-form' ),

		init: function() {
			this.generate_coupons( 0, this );
		},
		generate_coupons: function( batch_step, self ) {

			var data = {
				'action': 		'wccg_generate_coupons',
				'form_data': 	$( '#wc-coupon-generator-form' ).serialize(),
				'batch_step': 	batch_step
			};

			$.post ( ajaxurl, data, function( response ) {

				response = JSON.parse( response );

				if ( response.message ) {
					self.add_message( response.message );
				}

				if ( 'done' == response.step ) {
					self.completed( self, parseInt($( '[name="number_of_coupons"]' ).val()), response.total_coupons_generated );
				} else {
					self.generate_coupons( response.step, self );
				}
				self.progress_bar( response.progress );

			});

		},
		progress_bar: function( progress ) {
			$( '.wc-coupon-generator-progress-bar .progress' ).css( 'width', progress + '%' );
			$( '.wc-coupon-generator-progress-percentage' ).html( progress + '%' );
			$( '.inner-progress' ).css( 'width', $( '.wc-coupon-generator-progress-bar' ).width() );
		},
		completed: function( self, coupons_generated, execution_time ) {
			$( '.wc-coupon-generator-progress-bar + .spinner' ).remove();
			$( '.wc-coupon-generator-completed-actions' ).show();

			var actions = $('.wc-coupon-generator-completed-actions');
				actions.show();
				actions.html(actions.html().replace( /{{ couponGenerator.quantity }}/, coupons_generated ));
		},
		add_message: function( message ) {
			$( '.wc-coupon-generator-progress-messages' ).prepend( '<span class="wc-coupon-generator-progress-message">' + message + '</span><br/>' );
		},
		validate: function() {

			// Reset messages.
			this.validation_messages = [];

			if ( '' === $( '#coupon_amount' ).val().trim() ) {
				this.validation_messages.push( COUPON_GENERATOR_FOR_WOOCOMMERCE.i18n_amount_error.toString() );
			}

			this.$form.triggerHandler( 'wc_coupon_generator_validate_form', [ this ] );
		},
		passes_validation: function() {

			if ( this.validation_messages.length > 0 ) {
				return false;
			}

			return true;
		},
		display_validation_errors: function() {
			// Display the error messages.
			if ( false === this.passes_validation() ) {

				var $messages = $( '<ul/>' );

				$.each(
					this.validation_messages,
					function( i, message ) {
						$messages.append( $( '<li/>' ).html( message ) );
					}
				);

				$( '#wc-coupon-generator-coupon-error' ).find( 'ul' ).html( $messages.html() );
				$( '#wc-coupon-generator-coupon-error' ).slideDown( 200 );			

			}
		}

	};

	/** Initialize */
	jQuery( function( $ ) {

		$( '#wc-coupon-generator-form' ).on( 'submit', function( e ) {
			e.preventDefault();

			WCCG_Generator.validate();
			

			if ( WCCG_Generator.passes_validation() ) {
				$(this).find('fieldset:first').hide().next('fieldset').show();
				WCCG_Generator.init();
			} else {
				WCCG_Generator.display_validation_errors();
			}
			
		} );

	} );
} )( jQuery );
