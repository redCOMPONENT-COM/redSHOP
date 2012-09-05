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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'order.php' );
$order_functions = new order_functions();

$option = JRequest::getVar('option');
$model = $this->getModel ( 'coupon' );
$url = JUri::base();
$comment = JRequest::getVar('filter');
?>
<script language="javascript" type="text/javascript">

function submitform(pressbutton){
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;}
     
	 if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove') )
	 {		 
	  form.view.value="coupon_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}
function clearreset()
{
	var form = document.adminForm;
	form.filter.value="";
	form.submit();
}
</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<div id="editcell">
	<table width="100%"> 
			<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_( 'COUPON_FILTER' ); ?>:
				<input type="text" name="filter" id="filter" value="<?php echo $comment; ?>">
				<input type="reset" name="reset" id="reset" value="<?php echo JText::_( 'RESET' ); ?>" onclick="return clearreset();"> 
			</td>
		</tr>
	</table>
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="5%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->coupons ); ?>);" />
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COUPON_CODE', 'coupon_code', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
		 	<th>
				<?php echo JText::_( 'PERCENTAGE_OR_TOTAL' ); ?>
			</th>
		 	<th>
				<?php echo JHTML::_('grid.sort', 'COUPON_USERNAME', 'userid', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
		 	<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COUPON_TYPE', 'coupon_type', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COUPON_VALUE', 'coupon_value', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'LBL_COUPON_LEFT', 'coupon_left', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 'coupon_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>	
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->coupons ); $i < $n; $i++)
	{
		$row = &$this->coupons[$i];
        $row->id = $row->coupon_id;
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=coupon_detail&task=edit&cid[]='. $row->coupon_id );
		
		$published 	= JHTML::_('grid.published', $row, $i );
		
		if($row->userid)
			$username = $order_functions->getUserFullname($row->userid);		
		else
			$username= "";

			?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
				<?php echo $this->pagination->getRowOffset( $i ); ?>
			</td>
			<td align="center">
			<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
			</td>
			<td align="center">
			<a href="<?php echo $link; ?>"><?php echo  $row->coupon_code; ?></a>
			</td>
			<td class="order">
				<?php 
					if($row->percent_or_total==0)
						echo JText::_('TOTAL');
					else
						echo JText::_('PERCENTAGE'); 
				?>
			</td>
			<td>
				<?php if($username!="") echo $username; ?>
			</td>
			<td>
				<?php 
					if($row->coupon_type==0)
						echo JText::_('GLOBAL');
					else
						echo JText::_('USER_SPECIFIC'); 
				?>
			</td>
			<td align="center">			
				<?php if($row->percent_or_total!=0) {echo $row->coupon_value." %";} else { echo $producthelper->getProductFormattedPrice($row->coupon_value);
				//number_format($row->coupon_value,2,PRICE_SEPERATOR,THOUSAND_SEPERATOR); 
	}?>
			</td>
			<td align="center">
				<?php echo $row->coupon_left; ?>
			</td>
			<td align="center">
				<?php echo $published;?>
			</td>
			<td align="center">
				<?php echo $row->coupon_id; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>	
<tfoot>
	<td colspan="10">
		<?php echo $this->pagination->getListFooter(); ?>
	</td>
</tfoot>
</table>
</div>

<input type="hidden" name="view" value="coupon" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>