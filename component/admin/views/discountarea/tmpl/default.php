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
defined('_JEXEC') or die('Restricted access');
$producthelper = new producthelper();
$option = JRequest::getVar('option','','request','string');

?>
<script language="javascript" type="text/javascript">
function submitform(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton)
    {form.task.value=pressbutton;}
     
	if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') )
	{
		form.view.value="discountarea_detail";
	}
	try {
		form.onsubmit();
	}
	catch(e){}
	form.submit();
}
</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="5%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->discounts ); ?>);" />
			</th>
			<th class="title">
				<?php echo JHTML::_('grid.sort', 'AREA_START', 'area_start', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'AREA_END', 'area_end', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',  'DISCOUNT_TYPE', 'discount_on', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		 	</th>
		 	<th>
				<?php echo JHTML::_('grid.sort',  'DISCOUNT_AMOUNT', 'amount', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		 	</th>		 	
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'discountAreaid', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
					
		</tr>
	</thead>
	<?php
	
	$k = 0;
	for ($i=0, $n=count( $this->discounts ); $i < $n; $i++)
	{
		$row = &$this->discounts[$i];
        $row->id = $row->discountAreaid;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=discountarea_detail&task=edit&cid[]='. $row->discountAreaid );
		
		$published 	= JHTML::_('grid.published', $row, $i );		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>
			<td align="center"><?php echo $row->area_start;?></td>
			<td align="center"><?php echo $row->area_end;?></td>
			<td align="center"><?php echo ($row->discount_on) ? JTEXT::_('PERSANTAGE') : JTEXT::_('TOTAL');?></td>
			<td align="center"><a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_DISCOUNT' ); ?>"><?php echo ($row->discount_on) ? $row->amount.'%': $producthelper->getProductFormattedPrice($row->amount);?></a></td>
			<td align="center"><?php echo $published;?></td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
<?php	$k = 1 - $k;
	}	?>	
	<tfoot><td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td></tfoot>
	</table>
</div>

<input type="hidden" name="view" value="discountarea" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>