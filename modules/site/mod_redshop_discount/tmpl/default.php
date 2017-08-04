<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_discount
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
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
		<?php foreach ($data as $oneData): ?>
			<?php switch ($oneData->condition): case '1': ?>
					<?php $cond = '<'; ?>
					<?php break; ?>
				<?php case '3': ?>
					<?php $cond = '>'; ?>
					<?php break; ?>
				<?php default: ?>
					<?php $cond = '='; ?>
					<?php break; ?>
			<?php endswitch ?>

			<?php $amount = $oneData->amount; ?>

			<?php if ($oneData->discount_type == '1'): ?>
				<?php $discountAmount = number_format((double) $oneData->discount_amount, Redshop::getConfig()->get('PRICE_DECIMAL'), Redshop::getConfig()->get('PRICE_SEPERATOR'), Redshop::getConfig()->get('THOUSAND_SEPERATOR')) . ' %'; ?>
			<?php else: ?>
				<?php $discountAmount = $productHelper->getProductFormattedPrice($oneData->discount_amount); ?>
			<?php endif ?>
		<tr>
			<td><?php echo JText::sprintf('MOD_REDSHOP_DISCOUNT_CONDITION_TEMPLATE', $cond, $amount); ?></td>
			<td><?php echo $discountAmount; ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
