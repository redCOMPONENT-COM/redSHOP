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

$now	=& JFactory::getDate();

?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		else {
			submitform( pressbutton );
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" onSubmit="return selectAll(this.elements['container_product[]']);">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>
		<table class="admintable">

			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_( 'DISCOUNT_NAME' ); ?>:
				</td>
				<td>
					<input class="text_area" type="text" name="discount_name" id="discount_name" size="32" maxlength="250" value="<?php echo $this->detail->discount_name; ?>" />
					<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_DISCOUNT_NAME' ), JText::_( 'DISCOUNT_NAME' ), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_( 'DISCOUNT_TYPE' ); ?>:
				</td>
				<td>
					<?php echo $this->lists['discount_type']; ?>
					<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_DISCOUNT_TYPE' ), JText::_( 'DISCOUNT_TYPE' ), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_( 'DISCOUNT_AMOUNT' ); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="discount_amount" id="discount_amount" size="32" maxlength="250" value="<?php echo $this->detail->discount_amount;?>" />
					<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_DISCOUNT_AMOUNT' ), JText::_( 'DISCOUNT_AMOUNT' ), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_( 'DISCOUNT_START_DATE' ); ?>:
					</label>
				</td>
				<td>
				<?php
					if($this->detail->discount_startdate)
						$date = date("d-m-Y",$this->detail->discount_startdate);

					echo JHTML::_('calendar',$date , 'discount_startdate', 'discount_startdate',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'32',  'maxlength'=>'19')); ?>
				</td>
			</tr>

			<tr>
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_( 'DISCOUNT_END_DATE' ); ?>:
					</label>
				</td>
				<td>
				<?php
					if($this->detail->discount_enddate)
						$date = date("d-m-Y",$this->detail->discount_enddate);

					echo JHTML::_('calendar',$date , 'discount_enddate', 'discount_enddate',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'32',  'maxlength'=>'19')); ?>
				</td>
			</tr>
			<tr>
			<td valign="top" align="right" class="key">
					<label for="product">
						<?php echo JText::_( 'PRODUCT' ); ?>:
					</label>

			</td>
			<td>
				<table class="admintable">
					<tr width="100px">
						<td VALIGN="TOP" class="key"  align="center">
							<?php echo JText::_( 'PRODUCT' ); ?> <br /><br />
							<input style="width: 250px" type="text" id="input" value="" />
							<div style="display:none"><?php
								echo $this->lists['product_all'];
							?></div>
						</td>
						<TD align="center">
							<input type="button" value="-&gt;" onClick="moveRight(10);" title="MoveRight"><BR><BR>
							<input type="button" value="&lt;-" onClick="moveLeft();" title="MoveLeft">
						</TD>
						<TD VALIGN="TOP" align="right" class="key" >
							<?php echo JText::_( 'DISCOUNT_PRODUCT' ); ?><br /><br />
							<?php echo $this->lists['discount_product'];?>
						</td>
					</tr>
				</table>
			</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="category">
						<?php echo JText::_( 'CATEGORY' ); ?>:
					</label>
				</td>
				<td>
				<?php
					echo $this->lists['categories'];?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<label for="category">
						<?php echo JText::_( 'MANUFACTURER' ); ?>:
					</label>
				</td>
				<td>
				<?php
					echo $this->lists['manufacturers'];?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->mass_discount_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="mass_discount_detail" />
</form>
<script type="text/javascript">

	var options = {
		script:"index3.php?option=com_redshop&view=search&json=true&",
		varname:"input",
		json:true,
		shownoresults:true,

		callback: function (obj) {
		var selTo = document.adminForm.container_product;
		var chk_add=1;
		for (var i = 0; i < selTo.options.length; i++) {
	        if(selTo.options[i].value==obj.id)
	        { chk_add=0;
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