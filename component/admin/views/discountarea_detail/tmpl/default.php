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

JHTML::_('behavior.tooltip');
$producthelper = new producthelper();
?>
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) 
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	if (form.amount.value == ""){
		alert( "<?php echo JText::_( 'DISCOUNT_AMOUNT_MUST_FILLED', true ); ?>" );
	} else {
		if(document.getElementById('container_product'))
		{
			selectAll(document.getElementById('container_product'));
		}
		submitform( pressbutton );
	}
}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col50">
<fieldset class="adminform">
	<legend><?php echo JText::_( 'DETAILS' ); ?></legend>
	<table class="admintable">
		<tr><td width="100" align="right" class="key"><?php echo JText::_( 'AREA_START' )?>:</td>
			<td><input class="text_area" type="text" name="area_start" id="area_start" size="32" maxlength="250" value="<?php echo $producthelper->redpriceDecimal($this->detail->area_start);?>" />
				<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_AREA_START' ), JText::_( 'AREA_START' ), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>
		<tr><td width="100" align="right" class="key"><?php echo JText::_( 'AREA_END' )?>:</td>
			<td><input class="text_area" type="text" name="area_end" id="area_end" size="32" maxlength="250" value="<?php echo $producthelper->redpriceDecimal($this->detail->area_end);?>" />
				<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_AREA_END' ), JText::_( 'AREA_END' ), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_( 'DISCOUNT_TYPE' ); ?>:</td>
			<td><?php echo $this->lists['discount_on']; ?>
				<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_DISCOUNT_TYPE' ), JText::_( 'DISCOUNT_TYPE' ), 'tooltip.png', '', '', false); ?></td></tr>
		<tr><td width="100" align="right" class="key"><?php echo JText::_( 'DISCOUNT_AMOUNT' ); ?>:</td>
			<td>
				<input class="text_area" type="text" name="amount" id="amount" size="32" maxlength="250" value="<?php echo $producthelper->redpriceDecimal($this->detail->amount);?>" />
				<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_DISCOUNT_AMOUNT' ), JText::_( 'DISCOUNT_AMOUNT' ), 'tooltip.png', '', '', false); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_( 'DISCOUNT_START_DATE' ); ?>:</td>
			<td><?php $datee = ($this->detail->discountstart_date) ? date("d-m-Y",$this->detail->discountstart_date) : date("d-m-Y",time());
				echo JHTML::_('calendar',$datee , 'discountstart_date', 'discountstart_date',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'32',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		<tr>	
			<td valign="top" align="right" class="key"><?php echo JText::_( 'DISCOUNT_END_DATE' ); ?>:</td>
			<td><?php $datee = ($this->detail->discountend_date) ? date("d-m-Y",$this->detail->discountend_date) : date("d-m-Y",time());
				echo JHTML::_('calendar',$datee , 'discountend_date', 'discountend_date',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'32',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		<tr>	
			<td valign="top" align="right" class="key"><?php echo JText::_( 'CATEGORY' ); ?>:</td>
			<td><?php echo $this->lists['category_id'];?></td>
		</tr>
		<tr>	
			<td valign="top" align="right" class="key"><?php echo JText::_( 'PRODUCT' ); ?>:
				<br /><br /><input style="width: 250px" type="text" id="input" value="" />
				<div style="display:none"><?php echo $this->lists['product_all'];?></div></td>
			<td><table class="admintable">
				<tr><td align="center"><input type="button" value="-&gt;" onClick="moveRight(10);" title="MoveRight"><br><br>
										<input type="button" value="&lt;-" onClick="moveLeft();" title="MoveLeft"></td>
					<td valign="top" align="right" class="key" style="width: 250px"><?php echo $this->lists['product_id'];?></td></tr></table></td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key"><?php echo JText::_( 'PUBLISHED' ); ?>:</td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>			
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->discountAreaid; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="discountarea_detail" />
</form>

<script type="text/javascript">

var options = {
	script:"index3.php?option=com_redshop&view=search&json=true&alert=shipping&",
	varname:"input",
	json:true,
	shownoresults:true,
	callback: function (obj) 
	{
		var selTo = document.adminForm.container_product;
		var chk_add=1;
		for (var i = 0; i < selTo.options.length; i++) 
		{
	        if(selTo.options[i].value==obj.id)
	        {
		        chk_add=0;
	        }
		}
		if(chk_add==1)
		{
			var newOption = new Option(obj.value, obj.id);
			selTo.options[selTo.options.length] = newOption;
		}
		document.adminForm.input.value = "";
	}
};

var as_json = new bsn.AutoSuggest('input', options);
</script>