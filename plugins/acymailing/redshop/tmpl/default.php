<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php acymailing_listingsearch($pageInfo->search); ?>
<table class="adminlist table table-striped table-hover" cellpadding="1">
	<thead>
	<tr>
		<th class="title">
			<?php echo JHTML::_('grid.sort', JText::_('PLG_ACYMAILING_REDSHOP_PRODUCT_NAME'), 'p.product_name', $pageInfo->filter->order->dir, $pageInfo->filter->order->value); ?>
		</th>
		<th class="title">
			<?php echo JHTML::_('grid.sort', JText::_('PLG_ACYMAILING_REDSHOP_PRODUCT_NUMBER'), 'p.product_number', $pageInfo->filter->order->dir, $pageInfo->filter->order->value); ?>
		</th>
		<th class="title"><?php echo JText::_('PLG_ACYMAILING_REDSHOP_CATEGORY_NAME'); ?></th>
		<th class="title">
			<?php echo JHTML::_('grid.sort', JText::_('PLG_ACYMAILING_REDSHOP_PRODUCT_ID'), 'p.product_id', $pageInfo->filter->order->dir, $pageInfo->filter->order->value); ?>
		</th>
	</tr>
	</thead>
	<tfoot>
	<tr style="cursor:pointer">
		<td colspan="4">
			<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
				<div style="float: left; margin: 0 10px 0 0;">
					<?php echo $pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>
			<?php echo $pagination->getListFooter(); ?>
		</td>
	</tr>
	</tfoot>
<?php
$k = 0;

for ($i = 0, $countProducts = count($rs); $i < $countProducts; $i++):
	$row = $rs[$i];
	?>
	<tr style="cursor:pointer" class="row<?php echo $k; ?>" onclick="setTag('{product:<?php
	echo $row->product_id; ?>}');insertTag();">
		<td><?php echo $row->product_name; ?></td>
		<td><?php echo $row->product_number; ?></td>
		<td><?php echo $row->category_name; ?></td>
		<td><?php echo $row->product_id; ?></td>
	</tr>
<?php
	$k = 1 - $k;
endfor; ?>
</table>
<input type="hidden" name="filter_order" value="<?php echo $pageInfo->filter->order->value; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $pageInfo->filter->order->dir; ?>" />
