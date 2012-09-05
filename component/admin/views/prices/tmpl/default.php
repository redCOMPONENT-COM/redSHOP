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
require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' );
$producthelper = new producthelper();
$option = JRequest::getVar('option','','request','string');?>
<script language="javascript" type="text/javascript">
function submitform(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton)
    {
		form.task.value=pressbutton;
	}
    if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') ||(pressbutton=='saveorder')||(pressbutton=='orderup') || (pressbutton=='orderdown') )
	{
		form.view.value="prices_detail";
	}
	try {
		form.onsubmit();
	}
	catch(e){}
	form.submit();
}
</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<div id="editcell">
<table class="adminlist" width="100%">
<thead><tr><th width="5%"><?php echo JText::_( 'NUM' ); ?></th>
		<th width="5%"><input type="checkbox" name="toggle" onclick="checkAll(<?php echo count( $this->media ); ?>);" /></th>
		<th class="title" align="left" width="15%"><?php echo JText::_( 'PRODUCT_NAME' ); ?></th>
		<th width="10%"><?php echo JText::_( 'SHOPPER_GROUP' ); ?></th>
		<th width="10%"><?php echo JText::_( 'QUANTITY_START_LBL' ); ?></th>
		<th width="10%"><?php echo JText::_( 'QUANTITY_END_LBL' ); ?></th>
		<th width="15%"><?php echo JText::_( 'PRICE' ); ?></th>
		<th width="15%"><?php echo JText::_( 'DISCOUNT_PRICE' ); ?></th>
		</tr></thead>
	<?php	$k = 0;
		for ($i=0;$i<count($this->media); $i++)
		{
			$row = &$this->media[$i];
			$row->id = $row->price_id;
			//$product_id = $row->product_id;
			$link 	= JRoute::_( 'index.php?option='.$option.'&view=prices_detail&task=edit&product_id='.$row->product_id.'&cid[]='. $row->price_id );?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
			<td><a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_PRODUCT_PRICE' ); ?>"><?php echo $row->product_name;?></a></td>
			<td align="center"><?php echo $row->shopper_group_name;?></td>
			<td align="center"><?php echo $row->price_quantity_start;?></td>
			<td align="center"><?php echo $row->price_quantity_end;?></td>
			<td align="center" width="5%"><?php echo $producthelper->getProductFormattedPrice($row->product_price); ?></td>
			<td align="center" width="5%"><?php echo $producthelper->getProductFormattedPrice($row->discount_price); ?></td></tr>
	<?php		$k = 1 - $k;
		}	?>
<tfoot><td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td></tfoot>
</table>
</div>
<input type="hidden" name="view" value="prices" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="product_id" value="<?php echo $this->product_id?>" />
 <input type="hidden" name="boxchecked" value="0" />
</form>