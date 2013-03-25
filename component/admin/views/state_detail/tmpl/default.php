<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$uri = JURI::getInstance();
$url = $uri->root();
?>


<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.country_id.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_COUNTRY_MUST_BE_SELECTED', true ); ?>");
		} else if (form.state_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_STATE_MUST_HAVE_A_NAME', true ); ?>");
		} else if (form.state_3_code.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_STATE_3_CODE_MUST_HAVE_A_VALUE', true ); ?>");
		} else if (form.state_3_code.value.length > 3 || form.state_3_code.value.length < 3) {
			alert("<?php echo JText::_('COM_REDSHOP_STATE_MUST_HAVE_A_3_DIGIT_CODE', true ); ?>");
			var stste = form.state_3_code.value;
			form.state_3_code.value = stste.slice(0, 3);
		} else if (form.state_2_code.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_STATE_2_CODE_MUST_HAVE_A_VALUE', true ); ?>");
		} else if (form.state_2_code.value.length > 2 || form.state_2_code.value.length < 2) {
			alert("<?php echo JText::_('COM_REDSHOP_STATE_MUST_HAVE_A_2_DIGIT_CODE', true ); ?>");
			var stste = form.state_2_code.value;
			form.state_2_code.value = stste.slice(0, 2);
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo "details" ?></legend>
		<table class="admintable">

			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_("COM_REDSHOP_COUNTRY_NAME"); ?>:</td>
				<td><?php echo $this->lists['country_id']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_COUNTRY_NAME'), JText::_('COM_REDSHOP_COUNTRY_NAME'), 'tooltip.png', '', '', false); ?></td>
			</tr>

			<tr>
				<td class="key"><?php echo JText::_("COM_REDSHOP_STATE_NAME"); ?></td>
				<td><input class="text_area" type="text" name="state_name" id="state_name" size="30" maxlength="100"
				           value="<?php echo $this->detail->state_name; ?>"/></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_("COM_REDSHOP_STATE_3_CODE"); ?>:</td>
				<td><input class="text_area" type="text" name="state_3_code" id="state_3_code" size="80" maxlength="255"
				           value="<?php echo $this->detail->state_3_code; ?>"/></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_("COM_REDSHOP_STATE_2_CODE"); ?>:</td>
				<td><input class="text_area" type="text" name="state_2_code" id="state_2_code" size="80" maxlength="255"
				           value="<?php echo $this->detail->state_2_code; ?>"/></td>

			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_("COM_REDSHOP_SHOW_STATE"); ?>:</td>
				<td><?php echo $this->lists['show_state']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SHOW_STATE'), JText::_('COM_REDSHOP_SHOW_STATE'), 'tooltip.png', '', '', false); ?></td>
			</tr>

		</table>
	</fieldset>


	<input type="hidden" name="cid[]" value="<?php echo $this->detail->state_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="state_detail"/>
</form>


