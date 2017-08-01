jQuery(function($) {
	$(document).ready(function () {
		$('.card-number').keyup(function() {
			var detector = new BrandDetection();
			var brand = detector.detect($('.card-number').val());
			$(".card-number")[0].className = $(".card-number")[0].className.replace(/paymill-card-number-.*/g, '');
			if (brand !== 'unknown') {
				$('#card-number').addClass("paymill-card-number-" + brand);

				if (!detector.validate($('.card-number').val())) {
					$('#card-number').addClass("paymill-card-number-grayscale");
				}

				if (brand !== 'maestro') {
					VALIDATE_CVC = true;
				} else {
					VALIDATE_CVC = false;
				}
			}
		});

		$('.card-expiry').keyup(function() {
			if ( /^\d\d$/.test( $('.card-expiry').val() ) ) {
				var text = $('.card-expiry').val() + "/";
				$('.card-expiry').val(text);
			}
		});

		function PaymillResponseHandler(error, result) {
			if (error) {
				$(".payment_errors").text(error.apierror);
				$(".payment_errors").css("display","inline-block");
			} else {
				$(".payment_errors").css("display","none");
				$(".payment_errors").text("");
				var form = $("#payment-form");
				// Token
				var token = result.token;
				form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");
				form.get(0).submit();
			}
			$(".submit-button").removeAttr("disabled");
		}

		$("#payment-form").submit(function (event) {
			$('.submit-button').attr("disabled", "disabled");
			if (false === paymill.validateCardNumber($('.card-number').val())) {
				$(".payment_errors").text(Joomla.JText._('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_NUMBER'));
				$(".payment_errors").css("display","inline-block");
				$(".submit-button").removeAttr("disabled");
				return false;
			}
			var expiry = $('.card-expiry').val();
			expiry = expiry.split("/");
			if(expiry[1] && (expiry[1].length <= 2)){
				expiry[1] = '20'+expiry[1];
			}
			if (false === paymill.validateExpiry(expiry[0], expiry[1])) {
				$(".payment_errors").text(Joomla.JText._('PLG_RS_PAYMENT_PAYMILL_INVALID_EXPIRATION_DATE'));
				$(".payment_errors").css("display","inline-block");
				$(".submit-button").removeAttr("disabled");
				return false;
			}
			if (false === paymill.validateHolder($('.card-holdername').val())) {
				$(".payment_errors").text(Joomla.JText._('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_HOLDERNAME'));
				$(".payment_errors").css("display","inline-block");
				$(".submit-button").removeAttr("disabled");
				return false;
			}
			if ((false === paymill.validateCvc($('.card-cvc').val()))) {
				if(VALIDATE_CVC){
					$(".payment_errors").text(Joomla.JText._('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_CVC'));
					$(".payment_errors").css("display","inline-block");
					$(".submit-button").removeAttr("disabled");
					return false;
				} else {
					$('.card-cvc').val("000");
				}
			}

			var params = {
				amount_int:     parseInt($('.card-amount-int').val().replace(/[\.,]/, '.') * 100),  // E.g. "15" for 0.15 Eur
				currency:       $('.card-currency').val(),    // ISO 4217 e.g. "EUR"
				number:         $('.card-number').val(),
				exp_month:      expiry[0],
				exp_year:       expiry[1],
				cvc:            $('.card-cvc').val(),
				cardholder:     $('.card-holdername').val()
			};

			paymill.createToken(params, PaymillResponseHandler);
			return false;
		});
	});
});
