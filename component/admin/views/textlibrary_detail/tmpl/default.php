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
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.text_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_TAG_NAME_MUST_HAVE_A_ENTER', true ); ?>");
		} else if (form.text_desc.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_TAG_DESCRIPTION_MUST_HAVE_A_ENTER', true ); ?>");
		} else if (form.section.value == 0 || form.section.value == '0') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_SELECT_TEXT_LIBRARY_SECTION', true ); ?>");
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
							<?php echo JText::_('COM_REDSHOP_TAG_NAME'); ?><span class="star text-danger"> *</span>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="text_name" id="text_name" size="32" maxlength="250"
						       value="<?php echo $this->detail->text_name; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TAG_NAME'), JText::_('COM_REDSHOP_TAG_NAME'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_TEXT_DESCRIPTION'); ?><span class="star text-danger"> *</span>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="text_desc" id="text_desc"
						       value="<?php echo $this->detail->text_desc; ?>" size="32" maxlength="250"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TEXT_FIELD'), JText::_('COM_REDSHOP_TEXT_FIELD'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_SECTION'); ?><span class="star text-danger"> *</span>:
					</td>
					<td>
						<?php echo $this->lists['section']; ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SECTION'), JText::_('COM_REDSHOP_SECTION'), 'tooltip.png', '', '', false); ?>
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
	<div class="col50">

	</div>

	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_TEXT_FIELD'); ?></legend>

			<table class="admintable table">
				<tr>
					<td>
						<?php echo $editor->display("text_field", $this->detail->text_field, '$widthPx', '$heightPx', '100', '20');    ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>

	<input type="hidden" name="cid[]" value="<?php echo $this->detail->textlibrary_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="textlibrary_detail"/>
</form>
