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
require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' );
$producthelper = new producthelper();
$option = JRequest::getVar('option','','request','string');

?>
<script language="javascript" type="text/javascript">

function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') )
	 {		 
	  form.view.value="discount_detail";
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
				<?php echo JHTML::_('grid.sort', 'PRODUCT_AMOUNT', 'amount', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'CONDITION', '`condition`', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',  'DISCOUNT_TYPE', 'discount_type', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		 	</th>
		 	
		 	<th>
				<?php echo JHTML::_('grid.sort',  'DISCOUNT_AMOUNT', 'discount_amount', $this->lists['order_Dir'], $this->lists['order'] ); ?>
		 	</th>		 	
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'discount_product_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
					
		</tr>
	</thead>
	<?php
	
	$k = 0;
	for ($i=0, $n=count( $this->discounts ); $i < $n; $i++)
	{
		$row = &$this->discounts[$i];
        $row->id = $row->discount_product_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=discount_detail&layout=product&task=edit&cid[]='. $row->discount_product_id );
		
		$published 	= JHTML::_('grid.published', $row, $i );		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
			<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td align="center">
			<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_DISCOUNT' ); ?>">
			<?php echo $producthelper->getProductFormattedPrice($row->amount);//number_format($row->amount,2,PRICE_SEPERATOR,THOUSAND_SEPERATOR).CURRENCY_SYMBOL; ?></a>
			</td>
			<td align="center">
			<?php 
				switch ($row->condition) {
					case '1':
						echo JTEXT::_('LOWER');
						break;
					case '2':
						echo JTEXT::_('EQUAL');
						break;
					case '3':
						echo JTEXT::_('HIGHER');
						break;
				}
			?>
			</td>
			<td align="center">
			<?php if($row->discount_type == 0) echo JTEXT::_('TOTAL');
				else echo JTEXT::_('PERCENTAGE');
			?>
			</td>
			<td align="center">
			<?php 
			if($row->discount_type == 0) echo $producthelper->getProductFormattedPrice($row->discount_amount);
			else echo $row->discount_amount.'%';
			 
			?>
			</td>						
			<td align="center"><?php echo $published;?></td>
			<td align="center"><?php echo $row->discount_product_id; ?></td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>	

	<tfoot>
		<td colspan="9">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>
</div>

<input type="hidden" name="view" value="discount" />
<input type="hidden" name="layout" value="product" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
