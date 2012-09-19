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
$editor =& JFactory::getEditor();
$model = $this->getModel('template_detail');
$showbuttons = JRequest::getVar('showbuttons');

$producthelper = new producthelper();

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(pressbutton) {
	submitbutton(pressbutton);
	}

submitbutton = function(pressbutton) { 
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		
		if (form.shipping_box_name.value == ""){
			alert( "<?php echo JText::_('COM_REDSHOP_BOX_MUST_HAVE_A_NAME', true ); ?>" );
		}else if (form.shipping_box_length.value == "" || form.shipping_box_length.value == 0){
			alert( "<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_BOX_LENGTH', true ); ?>" );
		}else if (form.shipping_box_width.value == "" || form.shipping_box_width.value == 0){
			alert( "<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_BOX_WIDTH', true ); ?>" );
		} else if (form.shipping_box_height.value == "" || form.shipping_box_height.value == 0){
			alert( "<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_BOX_HEIGHT', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_BOX_NAME' ); ?>: 
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="shipping_box_name" id="shipping_box_name" size="32" maxlength="250" value="<?php echo $this->detail->shipping_box_name;?>"  /> 
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="deliverytime">
					<?php echo JText::_('COM_REDSHOP_BOX_LENGTH' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="shipping_box_length" value="<?php echo $producthelper->redpriceDecimal($this->detail->shipping_box_length);?>" /> 				
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="deliverytime">
					<?php echo JText::_('COM_REDSHOP_BOX_WIDTH' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="shipping_box_width" value="<?php echo $producthelper->redpriceDecimal($this->detail->shipping_box_width);?>" /> 				
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="deliverytime">
					<?php echo JText::_('COM_REDSHOP_BOX_HEIGHT' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="shipping_box_height" value="<?php echo $producthelper->redpriceDecimal($this->detail->shipping_box_height);?>" /> 				
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="deliverytime">
					<?php echo JText::_('COM_REDSHOP_BOX_PRIORITY' ); ?>:
				</label>
			</td>
			<td>
				<input type="text" name="shipping_box_priority" value="<?php echo $this->detail->shipping_box_priority;?>" /> 				
			</td>
		</tr>		
		<tr>
			<td valign="top" align="right" class="key">
				<?php echo JText::_('COM_REDSHOP_PUBLISHED' ); ?>:
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
				
	</table>
	</fieldset>
</div>
<!-- Available Dynamic fields-->


<div class="clr"></div>
<input type="hidden" name="shipping_box_id" value="<?php echo $this->detail->shipping_box_id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="shipping_box_detail" />
</form>