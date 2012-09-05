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
$now	=& JFactory::getDate();

?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		if (form.amount.value == ""){
			alert( "<?php echo JText::_( 'DISCOUNT_AMOUNT_MUST_FILLED', true ); ?>" );
		}else if(form.shopper_group_id.value == "" ){
			alert( "<?php echo JText::_( 'SHOPPER_GROUP_MUST_BE_SELECTED', true ); ?>" );
		} 
		else {
			submitform( pressbutton );
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'DETAILS' ); ?></legend>
		<table class="admintable">
					
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_( 'AMOUNT' ); ?>:
					</label>
				</td>
				<td>
					<input class="text_area" type="text" name="amount" id="amount" size="32" maxlength="250" value="<?php echo $producthelper->redpriceDecimal($this->detail->amount);?>" />
					<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_AMOUNT' ), JText::_( 'AMOUNT' ), 'tooltip.png', '', '', false); ?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<label for="name">
						<?php echo JText::_( 'CONDITION' ); ?>:
					</label>
				</td>
				<td>
					<?php echo $this->lists['discount_condition']; ?>
					<?php echo JHTML::tooltip( JText::_( 'TOOLTIP_CONDITION' ), JText::_( 'CONDITION' ), 'tooltip.png', '', '', false); ?>
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
					<input class="text_area" type="text" name="discount_amount" id="discount_amount" size="32" maxlength="250" value="<?php echo $producthelper->redpriceDecimal($this->detail->discount_amount);?>" />
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
					if($this->detail->start_date)
						$datee = date("d-m-Y",$this->detail->start_date);
					
					echo JHTML::_('calendar',$datee , 'start_date', 'start_date',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'32',  'maxlength'=>'19')); ?>
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
					if($this->detail->end_date)
						$datee = date("d-m-Y",$this->detail->end_date);
					
					echo JHTML::_('calendar',$datee , 'end_date', 'end_date',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'32',  'maxlength'=>'19')); ?>
				</td>
			</tr>
			<tr>	
				<td valign="top" align="right" class="key">
					<label for="deliverytime">
						<?php echo JText::_( 'SHOPPER_GROUP' ); ?>:
					</label>
				</td>
				<td>
					<?php
						echo $this->lists['shopper_group_id'];
					?>
				</td>
			</tr>					 
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_( 'PUBLISHED' ); ?>:
				</td>
				<td>
					<?php echo $this->lists['published']; ?>
				</td>
			</tr>			
	</table>
	</fieldset>
</div>
<div class="clr"></div>
<input type="hidden" name="cid[]" value="<?php echo $this->detail->discount_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="discount_detail" />
</form>
