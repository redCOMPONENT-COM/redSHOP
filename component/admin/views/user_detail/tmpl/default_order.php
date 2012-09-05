<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');	?>
<div id="editcell">
<table class="adminlist">
<thead>
<tr><th width="5%"><?php echo JText::_ ( 'NUM' );; ?></th>
	<th width="5%"><?php echo JTEXT::_('ORDER_ID'); ?></th>
	<th width="5%"><?php echo JTEXT::_('ORDER_NUMBER'); ?></th>
	<th width="5%"><?php echo JTEXT::_('ORDER_DATE'); ?></th>
	<th width="5%"><?php echo JTEXT::_('ORDER_TOTAL'); ?></th></tr></thead>
<tbody>
<?php
	$k = 0;
	for ($i=0; $i<count( $this->userorders );$i++)
	{
		$row = $this->userorders[$i];
		$row->id = $row->order_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=order_detail&task=edit&cid[]='.$row->order_id ); ?>
		<tr>
			<td align="center"><?php echo $this->pagination->getRowOffset ( $i );?></td>
			<td align="center">
				<a href="<?php echo $link; ?>" title="<?php echo JText::_('EDIT_ORDER');?>"><?php echo $row->order_id;?></a></td>
            <td align="center"><?php echo $row->order_number; ?></td>
            <td align="center"><?php echo $this->config->convertDateFormat($row->cdate); ?></td>
            <td align="center"><?php echo $this->producthelper->getProductFormattedPrice($row->order_total);?></td></tr>
<?php   $k = 1 - $k;
	}	?>
</tbody>
<tfoot><td colspan="5"><?php echo $this->pagination->getListFooter(); ?></td></tfoot>
</table>
</div>