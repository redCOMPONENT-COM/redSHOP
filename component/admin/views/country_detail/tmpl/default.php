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
		if (form.country_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_COUNTRY_MUST_HAVE_A_NAME', true ); ?>");
		}
		else if (form.country_3_code.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_COUNTRY_3_CODE_MUST_HAVE_A_VALUE', true ); ?>");
		}
		else if (form.country_3_code.value.length > 3 || form.country_3_code.value.length < 3) {
			alert("<?php echo JText::_('COM_REDSHOP_COUNTRY_3_CODE_MUST_HAVE_A_3_DIGIT_CODE', true ); ?>");
			var stste = form.country_3_code.value;
			form.country_3_code.value = stste.slice(0, 3);
		}
		else if (form.country_2_code.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_COUNTRY_2_CODE_MUST_HAVE_A_VALUE', true ); ?>");
		} else if (form.country_2_code.value.length > 2 || form.country_2_code.value.length < 2) {
			alert("<?php echo JText::_('COM_REDSHOP_COUNTRY_2_CODE_MUST_HAVE_A_2_DIGIT_CODE', true ); ?>");
			var stste = form.country_2_code.value;
			form.country_2_code.value = stste.slice(0, 2);
		}
		else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo "details" ?></legend>
		<table class="admintable">
			<tr>
				<td class="key"><?php echo JText::_('COM_REDSHOP_COUNTRY_NAME'); ?></td>
				<td><input class="text_area" type="text" name="country_name" id="country_name" size="30" maxlength="100"
				           value="<?php echo $this->detail->country_name; ?>"/></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('COM_REDSHOP_COUNTRY_3_CODE'); ?>:
				</td>
				<td>
					<input class="text_area" type="text" name="country_3_code" id="country_3_code" size="80"
					       maxlength="255" value="<?php echo $this->detail->country_3_code; ?>"/>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('COM_REDSHOP_COUNTRY_2_CODE'); ?>:
				</td>
				<td>
					<input class="text_area" type="text" name="country_2_code" id="country_2_code" size="80"
					       maxlength="255" value="<?php echo $this->detail->country_2_code; ?>"/>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('COM_REDSHOP_COUNTRY_JTEXT'); ?>:
				</td>
				<td>
					<input class="text_area" type="text" name="country_jtext" id="country_jtext" size="80"
					       maxlength="255" value="<?php echo $this->detail->country_jtext; ?>"/>
				</td>

		</table>
	</fieldset>


	<input type="hidden" name="cid[]" value="<?php echo $this->detail->country_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="country_detail"/>
</form>


