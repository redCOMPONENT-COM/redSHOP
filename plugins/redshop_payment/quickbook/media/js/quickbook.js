var quickbook = {};

(function($){
	jQuery(document).ready(function() {
		jQuery('#getTicketModal').on('show', function () {

			if ('' == jQuery.trim(jQuery('#jform_params_appId').val()))
			{
				alert(Joomla.JText._('PLG_REDSHOP_PAYMENT_QUICKBOOK_APP_ID_REQUIRED'));

				return false;
			}
			else
			{
				// Replace Production url
				jQuery('#app_id_link_production').attr(
					'href',
					'https://merchantaccount.quickbooks.com/j/sdkconnection?appid=' + jQuery.trim(jQuery('#jform_params_appId').val()) + '&appdata=mydata'
				);

				// Replace development url
				jQuery('#app_id_link_develop').attr(
					'href',
					'https://merchantaccount.ptc.quickbooks.com/j/sdkconnection?appid=' + jQuery.trim(jQuery('#jform_params_appId').val()) + '&appdata=mydata'
				);
			}
		});

		jQuery('#generate_conn_ticket').click(function(event) {
			jQuery.ajax({
				url: redSHOP.RSConfig._('SITE_URL') + 'administrator/index.php?option=com_redshop&view=redshop&secret=' + redSHOP.RSConfig._('SECRET_WORD') + '&control=getConnectionTicket',
				type: 'GET',
				dataType: 'json',
			})
			.done(function(response) {
				jQuery('#jform_params_connectionTicket').val(response.conntkt);
				jQuery('#getTicketModal').modal('hide');
			})
			.fail(function(response) {
				alert(responseText);
			});
		});
	});
})(jQuery);
