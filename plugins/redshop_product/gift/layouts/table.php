<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);

?>
<table class="table">
	<tr>
		<th><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_QUANTITY_START_LBL'); ?></th>
		<th><?php echo JText::_('COM_REDSHOP_QUANTITY_END_LBL'); ?></th>
	</tr>
	<?php foreach ($giftData as $key => $value) : ?>
		<tr>
			<td><?php echo $value->product_name; ?></td>
			<td><?php echo $value->quantity; ?></td>
			<td><?php echo $value->quantity_from; ?></td>
			<td><?php echo $value->quantity_to; ?></td>
		</tr>
	<?php endforeach; ?>
</table>