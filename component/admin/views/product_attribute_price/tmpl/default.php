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
$option = JRequest::getVar('option','','request','string');?>
<script language="javascript" type="text/javascript">
function submitform(pressbutton)
{
	var form = document.adminForm2;
	if (pressbutton)
    {
		form.task.value=pressbutton;
	}
    
	form.submit();
}
</script>
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm2" >
<div id="editcell">
<table class="adminlist" width="100%">
<thead><tr> 
		 	<th width="30%"><?php echo JText::_( 'SHOPPER_GROUP_NAME' ); ?></th>
		 	<th width="30%"><?php echo JText::_( 'QUANTITY_START_LBL' ); ?></th>
		 	<th width="30%"><?php echo JText::_( 'QUANTITY_END_LBL' ); ?></th>
		<th width="15%"><?php echo JText::_( 'PRODUCT_PRICE' ); ?>&nbsp;&nbsp;<img style="cursor:pointer" src="images/filesave.png" onclick="submitform('saveprice')"></th></tr></thead>
	<?php	$k = 0;
		for ($i=0;$i<count($this->prices); $i++)
		{
			$row = &$this->prices[$i];
			$row->id = $row->price_id;
			//$product_id = $row->product_id;
			?>
		<tr class="<?php echo "row$k"; ?>">
			 
			 
			
			<td align="center"><?php echo $row->shopper_group_name;?></td>
			<td align="center"><input type="text" name="price_quantity_start[]" id="price_quantity_start" value=" <?php echo $row->price_quantity_start;?>" /> </td>
			<td align="center"><input type="text" name="price_quantity_end[]" id="price_quantity_end" value="<?php echo $row->price_quantity_end;?>"/></td>
			<td align="center" width="5%"><input type="hidden" name="price_id[]" value="<?php echo $row->id; ?>"><input type="hidden" name="shopper_group_id[]" value="<?php echo $row->shopper_group_id; ?>"><input type="text" name="price[]" value="<?php echo $row->product_price; ?>"></td></tr>
	<?php		$k = 1 - $k;
		}	?>	
 
</table>
</div>
<input type="hidden" name="view" value="product_attribute_price" />
<input type="hidden" name="task" value="saveprice" />
<input type="hidden" name="section_id" value="<?php echo $this->section_id;?>" />
<input type="hidden" name="section" value="<?php echo $this->section;?>" />
<input type="hidden" name="cid" value="<?php echo $this->cid;?>" />
 <input type="hidden" name="boxchecked" value="0" />
 <input type="hidden" name="option" value="com_redshop" />
</form>