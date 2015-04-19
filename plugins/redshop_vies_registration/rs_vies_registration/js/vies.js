jQuery(document).ready(function(){
	jQuery.validator.addMethod("vies", function( value, element, param ) {
		if ( this.optional( element ) ) {
			return "dependency-mismatch";
		}

		if (jQuery('#vies_status_invalid2').is(':checked')){
			return true;
		}

		var previous = this.previousValue( element ),
			validator, data;

		if (!this.settings.messages[ element.name ] ) {
			this.settings.messages[ element.name ] = {};
		}
		previous.originalMessage = this.settings.messages[ element.name ].vies;
		this.settings.messages[ element.name ].vies = previous.message;

		param = typeof param === "string" && { url: param } || param;

		if ( previous.old === value ) {
			return previous.valid;
		}

		previous.old = value;
		validator = this;
		this.startRequest( element );
		data = {};
		data[ element.name ] = value;
		jQuery('.waitVies').remove();
		jQuery.ajax( jQuery.extend( true, {
			url: param,
			mode: "abort",
			port: "validate" + element.name,
			dataType: "json",
			data: data,
			context: validator.currentForm,
			beforeSend: function () {
				jQuery( "<label class='waitVies'>" + Joomla.JText._('PLG_REDSHOP_VIES_REGISTRATION_VERYFIES_VAT_NUMBER') + "</label>" )
					.insertAfter('#' + element.name);
			},
			success: function( response ) {
				var valid = response === true || response === "true",
					errors, message, submitted;
				jQuery('.waitVies').remove();
				validator.settings.messages[ element.name ].vies = previous.originalMessage;
				if ( valid ) {
					submitted = validator.formSubmitted;
					validator.prepareElement( element );
					validator.formSubmitted = submitted;
					validator.successList.push( element );
					delete validator.invalid[ element.name ];
					validator.showErrors();
				} else {
					errors = {};
					message = response || validator.defaultMessage( element, "vies" );
					errors[ element.name ] = previous.message = jQuery.isFunction( message ) ? message( value ) : message;
					validator.invalid[ element.name ] = true;
					validator.showErrors( errors );
					jQuery( "<label class=\"checkbox waitVies\" style=\"color: #000;\"><input type=\"checkbox\" name=\"vies_status_invalid\" id=\"vies_status_invalid2\" value=\"1\">" + Joomla.JText._('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION_STATUS2') + "</label>" )
						.insertAfter('#' + element.name);
				}
				previous.valid = valid;
				validator.stopRequest( element, valid );
			}
		}, param ) );
		return "pending";
	});

	jQuery.validator.addMethod("country", function( value, element ) {
		if ( this.optional( element ) ) {
			return "dependency-mismatch";
		}

		if (jQuery('#vies_status_invalid2').is(':checked')){
			return true;
		}

		var vatNumber = jQuery("#vat_number");
		var vatElement = vatNumber[0];
		var previous = this.previousValue( element );

		if (!this.settings.messages[ element.name ] ) {
			this.settings.messages[ element.name ] = {};
		}
		previous.originalMessage = this.settings.messages[ element.name ].vies;
		this.settings.messages[ element.name ].vies = previous.message;

		if ( previous.old === value ) {
			return previous.valid;
		}

		previous.old = value;

		if (jQuery.data(vatElement, "previousValue"))
		{
			jQuery.data(vatElement, "previousValue", {
				old: null,
				valid: true,
				message: this.defaultMessage(vatElement, "vies" )
			});
		}

		var cleanElement = this.clean( vatNumber ),
			checkElement = this.validationTargetFor( cleanElement ),
			result = true;

		this.lastElement = checkElement;

		if ( checkElement === undefined ) {
			delete this.invalid[ cleanElement.name ];
		} else {
			result = this.check( checkElement ) !== false;
			if ( result ) {
				delete this.invalid[ checkElement.name ];
			} else {
				this.invalid[ checkElement.name ] = true;
			}
		}
		// Add aria-invalid status for screen readers
		jQuery( vatNumber ).attr( "aria-invalid", !result );

		if ( !this.numberOfInvalids() ) {
			// Hide error containers on last error
			this.toHide = this.toHide.add( this.containers );
		}

		return true;
	});

	jQuery("#vat_number").rules("add", {
		vies: {
			url: "index.php?tmpl=component&option=com_redshop&view=plugin&task=checkViesValidation&type=redshop_vies_registration",
			type: "post",
			data: {
				country_code: function () {
					return jQuery("#country_code").val();
				}
			}
		}
	});
	 jQuery("#country_code").rules('add', {
		country: true
	});
	jQuery('#adminForm').on('change', '#vies_status_invalid2', function(){
		var checked = jQuery(this).is(':checked');
		if (checked){
			jQuery('#vat_number').removeClass('error');
			jQuery('#vat_number-error').remove();
		}
	});
});