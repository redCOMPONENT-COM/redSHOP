<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
    ?>
<div id="editcell">
	<table class="adminlist table table-striped">
		<thead>
		<tr>
			<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM');; ?></th>
			<th width="5%"><?php echo JText::_('COM_REDSHOP_ORDER_ID'); ?></th>
			<th width="5%"><?php echo JText::_('COM_REDSHOP_ORDER_NUMBER'); ?></th>
			<th width="5%"><?php echo JText::_('COM_REDSHOP_ORDER_DATE'); ?></th>
			<th width="5%"><?php echo JText::_('COM_REDSHOP_ORDER_TOTAL'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		for ($i = 0; $i < count($this->userorders); $i++)
		{
			$row = $this->userorders[$i];
			$row->id = $row->order_id;
			$link = JRoute::_('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $row->order_id); ?>
			<tr>
				<td align="center"><?php echo $this->pagination->getRowOffset($i);?></td>
				<td align="center">
					<a href="<?php echo $link; ?>"
					   title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDER'); ?>"><?php echo $row->order_id;?></a></td>
				<td align="center"><?php echo $row->order_number; ?></td>
				<td align="center"><?php echo $this->config->convertDateFormat($row->cdate); ?></td>
				<td align="center"><?php echo $this->producthelper->getProductFormattedPrice($row->order_total);?></td>
			</tr>
			<?php   $k = 1 - $k;
		}    ?>
		</tbody>
		<tfoot>
		<td colspan="5">
			<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
				<div class="redShopLimitBox">
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>
			<?php echo $this->pagination->getListFooter(); ?></td>
		</tfoot>
	</table>
</div>
