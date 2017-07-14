<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$uri = JURI::getInstance();
$url = $uri->root();
?>


<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		if (form.currency_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CURREMCY_MUST_HAVE_A_NAME', true ); ?>");
		}
		else if (form.currency_code.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_CURRENCY_CODE_MUST_HAVE_A_VALUE', true ); ?>");
		}

		else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo "details" ?></legend>
		<table class="admintable table">
			<tr>
				<td class="key"><?php echo JText::_('COM_REDSHOP_CURRENCY_NAME'); ?><span class="star text-danger"> *</span></td>
				<td><input class="text_area" type="text" name="currency_name" id="currency_name" size="30"
				           maxlength="100" value="<?php echo $this->detail->currency_name; ?>"/></td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('COM_REDSHOP_CURRENCY_CODE_LBL'); ?><span class="star text-danger"> *</span>
				</td>
				<td>
					<input class="text_area" type="text" name="currency_code" id="currency_code" size="80"
					       maxlength="255" value="<?php echo $this->detail->currency_code; ?>"/>
				</td>
			</tr>

		</table>
	</fieldset>


	<input type="hidden" name="cid[]" value="<?php echo $this->detail->currency_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="currency_detail"/>
</form>


