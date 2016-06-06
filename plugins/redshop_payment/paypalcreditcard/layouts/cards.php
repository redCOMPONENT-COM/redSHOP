<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Payment.PaypalCreditCard
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

use PayPal\Api\CreditCard;

extract($displayData);

?>
<div class="ajax-error">&nbsp;</div>
<h2><?php echo JText::_('PLG_PAYPALCREDITCARD'); ?></h2>

<?php if (!$selectable) : ?>
<div class="navbar pull-right">
  <button type="submit" id="newCardBtn" class="btn btn-primary">New</button>
</div>
<?php endif; ?>

<div id="newCardform" class="hide">
<?php
	echo RedshopLayoutHelper::render(
		'form',
		array(
			'id'          => 0,
			'name'        => null,
			'type'        => null,
			'number'      => null,
			'expireMonth' => null,
			'expireYear'  => null,
			'creditCardTypes' => $creditCardTypes
		),
		__DIR__
	);
?>
</div>
<?php
try
{
	$params = array(
		"sort_by" => "create_time",
		"sort_order" => "desc",
		"merchant_id" => $merchantId,
		"external_customer_id" => $externalCustomerId
	);
	$cards = CreditCard::all($params, $apiContext);

	if (!empty($cards))
	{
	?>

		<div class="creditCards">
			<table class="table table-hover">
				<thead>
					<tr>
					<?php if ($selectable) : ?>
						<th>&nbsp;</th>
					<?php endif; ?>
						<th>Name</th>
						<th>Type</th>
						<th>Number</th>
						<th>Expire Month</th>
						<th>Expire Year</th>
					</tr>
				</thead>
				<tbody>
			<?php foreach ($cards->items as $card) : ?>
					<?php
						echo RedshopLayoutHelper::render(
							'card',
							array(
								'card'            => $card,
								'creditCardTypes' => $creditCardTypes,
								'selectable'      => $selectable
							),
							__DIR__
						);
					?>
			<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php
	}
}
catch (Exception $ex)
{
	JFactory::getApplication()->enqueueMessage($ex->getMessage(), 'warning');
}

JFactory::getDocument()->addScriptDeclaration('

');
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {

		jQuery(document).on('click', '.edit, .cancel', function() {
			var id = jQuery(this).attr('cardId');

			if (id != 0)
			{
				var el = jQuery('#card-edit-' + id);

				el.find('[name="cardNumber"]').attr('readonly','readonly');

				el.fadeToggle('slow');
			}
			else
			{
				jQuery('#newCardform').fadeToggle('slow');
			}
		});

		jQuery(document).on('click', '[id^="save-"]', function(event) {
			event.preventDefault();

			jQuery(this).attr('disabled', 'disabled');
			jQuery('.cancel').attr('disabled', 'disabled');
			jQuery('.credit-card-form').css('opacity', '0.3');

			var id       = this.id.replace('save-','');
			var el       = (id == 0) ? jQuery('#newCardform') : jQuery('#card-edit-' + id);
			var taskName = (id == 0) ? 'new' : 'update';

			var params = {
					option 			: 'com_redshop',
					view   			: 'account',
					layout 			: 'cards',
					plugin 			: 'paypalcreditcard',
					task   			: taskName,
					cardId 			: id,
					cardType 		: el.find('[name="cardType"]').val(),
					cardNumber      : el.find('[name="cardNumber"]').val(),
					cardName 		: el.find('[name="cardName"]').val(),
					cardExpireMonth : el.find('[name="cardExpireMonth"]').val(),
					cardExpireYear  : el.find('[name="cardExpireYear"]').val(),
					cardCvv  		: el.find('[name="cardCvv"]').val()
			};

			jQuery.ajax({
				url: redSHOP.RSConfig._('SITE_URL') + '?tmpl=component',
				type: 'POST',
				dataType: 'json',
				data: params,
			})
			.always(function(data, textStatus){

				if(textStatus == 'timeout' || textStatus == 'parsererror') {
					jQuery('.ajax-error').html('<span class="label label-important">Server Timeout</span>');
				}
				else if (typeof data === 'undefined' || textStatus == 'error') {
					jQuery('.ajax-error').html('<span class="label label-important">Application Error</span>');
				}
				else {
					if (data.messages.length > 0)
					{
						var hasImportant = false;

						jQuery(data.messages).each(function (messageIdx, messageData) {
							jQuery('.ajax-error').html(messageData.message);

							if (messageData.type_message != 'success')
							{
								hasImportant = true;
							}
						});

						if (!hasImportant)
						{
							if ('update' == taskName)
							{
								var fields = jQuery('#card-' + id).children();
								jQuery(fields.get(0)).html(params.cardName);
								jQuery(fields.get(3)).html(params.cardExpireMonth);
								jQuery(fields.get(4)).html(params.cardExpireYear);
							}
							// For new task
							else if (data.cardId != 0)
							{
								jQuery('.creditCards table').prepend(data.response);
								el.find('[name="cardNumber"]').val('');
								el.find('[name="cardName"]').val('');
								el.find('[name="cardExpireMonth"]').val('');
								el.find('[name="cardExpireYear"]').val('');
								el.find('[name="cardCvv"]').val('');
							}

							el.fadeToggle('slow');
						}
					}
				}

				jQuery('[id^="save-"]').removeAttr('disabled', 'disabled');
				jQuery('.cancel').removeAttr('disabled', 'disabled');
				jQuery('.credit-card-form').css('opacity', '');
			});
		});

		jQuery(document).on('click', '[id^="delete-"]', function(event) {
			event.preventDefault();
			var id = this.id.replace('delete-','');

			jQuery('#card-' + id).css('opacity', '0.3');

			var params = {
					option 			: 'com_redshop',
					view   			: 'account',
					layout 			: 'cards',
					plugin 			: 'paypalcreditcard',
					task   			: 'delete',
					cardId 			: id
			};

			jQuery.ajax({
				url: redSHOP.RSConfig._('SITE_URL') + '?tmpl=component',
				type: 'POST',
				dataType: 'json',
				data: params,
			})
			.always(function(data, textStatus){

				if(textStatus == 'timeout' || textStatus == 'parsererror') {
					jQuery('.ajax-error').html('<span class="label label-important">Server Issue/Timeout</span>');
				}
				else if (typeof data === 'undefined' || textStatus == 'error') {
					jQuery('.ajax-error').html('<span class="label label-important">Application Error</span>');
				}
				else {
					if (data.messages.length > 0)
					{
						var hasImportant = false;

						jQuery(data.messages).each(function (messageIdx, messageData) {
							jQuery('.ajax-error').html(messageData.message);

							if (messageData.type_message != 'success')
							{
								hasImportant = true;
							}
						});

						if (!hasImportant)
						{
							jQuery('#card-' + id).fadeOut('slow', function(){ jQuery(this).remove();});
							jQuery('#card-edit-' + id).fadeOut('slow', function(){ jQuery(this).remove();});
						}
					}
				}
			});
		});

		jQuery(document).on('click', '#newCardBtn', function(event) {
			event.preventDefault();
			jQuery('#newCardform').fadeToggle('slow');
		});

		// Un used
		redSHOP.paypal = {
			toggleCreditCard: function(el){

				el.find('.save').toggle();
				el.find('.cancel').toggle();
				el.find('.edit').toggle();
				el.find('.delete').toggle();

				el.find('.text').toggle();
				el.find('.input').toggle();
			}
		};
	});
</script>
