<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
$editor = JFactory::getEditor();
$showbuttons = JRequest::getVar('showbuttons');

$producthelper = productHelper::getInstance();

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.shipping_box_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_BOX_MUST_HAVE_A_NAME', true ); ?>");
		} else if (form.shipping_box_length.value == "" || form.shipping_box_length.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_BOX_LENGTH', true ); ?>");
		} else if (form.shipping_box_width.value == "" || form.shipping_box_width.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_BOX_WIDTH', true ); ?>");
		} else if (form.shipping_box_height.value == "" || form.shipping_box_height.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_HAVE_A_BOX_HEIGHT', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable table">
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_BOX_NAME'); ?>
							<span class="star text-danger"> *</span>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="shipping_box_name" id="shipping_box_name" size="32"
						       maxlength="250" value="<?php echo $this->detail->shipping_box_name; ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_BOX_LENGTH'); ?>
							<span class="star text-danger"> *</span>:
						</label>
					</td>
					<td>
						<input type="text" name="shipping_box_length"
						       value="<?php echo $producthelper->redpriceDecimal($this->detail->shipping_box_length); ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_BOX_WIDTH'); ?>
							<span class="star text-danger"> *</span>:
						</label>
					</td>
					<td>
						<input type="text" name="shipping_box_width"
						       value="<?php echo $producthelper->redpriceDecimal($this->detail->shipping_box_width); ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_BOX_HEIGHT'); ?>
							<span class="star text-danger"> *</span>:
						</label>
					</td>
					<td>
						<input type="text" name="shipping_box_height"
						       value="<?php echo $producthelper->redpriceDecimal($this->detail->shipping_box_height); ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_BOX_PRIORITY'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="shipping_box_priority"
						       value="<?php echo $this->detail->shipping_box_priority; ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
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
	<input type="hidden" name="shipping_box_id" value="<?php echo $this->detail->shipping_box_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="shipping_box_detail"/>
</form>
