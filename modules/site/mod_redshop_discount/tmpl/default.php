<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_discount
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$productHelper = productHelper::getInstance();

?>
<div class="mod_discount_main">
	<table class="table table-striped table-condensed">
		<thead>
		<tr>
			<th><?php echo JText::_('MOD_REDSHOP_DISCOUNT_CONDITION'); ?></th>
			<th><?php echo JText::_('MOD_REDSHOP_DISCOUNT_DISCOUNT'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($data as $oneData):
			switch ($oneData->condition)
			{
				case '1':
					$cond = '<';
					break;
				case '3':
					$cond = '>';
					break;
				default:
					$cond = '=';
					break;
			}

			$amount = $oneData->amount;

			if ($oneData->discount_type == '1')
			{
				$discount_amount = number_format((double) $oneData->discount_amount, Redshop::getConfig()->get('PRICE_DECIMAL'), Redshop::getConfig()->get('PRICE_SEPERATOR'), Redshop::getConfig()->get('THOUSAND_SEPERATOR')) . ' %';
			}
			else
			{
				$discount_amount = $productHelper->getProductFormattedPrice($oneData->discount_amount);
			}
			?>
		<tr>
			<td><?php echo JText::sprintf('MOD_REDSHOP_DISCOUNT_CONDITION_TEMPLATE', $cond, $amount); ?></td>
			<td><?php echo $discount_amount; ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
