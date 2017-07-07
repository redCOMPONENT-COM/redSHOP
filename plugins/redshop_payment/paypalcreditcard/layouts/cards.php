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

JHtml::script('plg_redshop_payment_paypalcreditcard/cards.min.js', false, true, false);

?>
	<div class="ajax-error">&nbsp;</div>
	<h2><?php echo JText::_('PLG_PAYPALCREDITCARD'); ?></h2>

<?php if (!$selectable) : ?>
	<div class="navbar pull-right">
		<button type="submit" id="newCardBtn" class="btn btn-primary">New</button>
	</div>
<?php endif; ?>

	<div id="newCardform">
		<?php
		echo RedshopLayoutHelper::render(
			'form',
			array(
				'id'              => 0,
				'name'            => null,
				'type'            => null,
				'number'          => null,
				'expireMonth'     => null,
				'expireYear'      => null,
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
		"sort_by"              => "create_time",
		"sort_order"           => "desc",
		"merchant_id"          => $merchantId,
		"external_customer_id" => $externalCustomerId
	);

	$cards = CreditCard::all($params, $apiContext);

	if ($cards->total_items)
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
