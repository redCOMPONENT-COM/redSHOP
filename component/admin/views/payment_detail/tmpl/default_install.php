<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
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

		if (form.payment_method_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_PAYMENT_METHOD_MUST_HAVE_A_NAME', true ); ?>");
		} else {

			submitform(pressbutton);
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_INSTALL_NEW_PACKAGE'); ?></legend>

			<table class="admintable" width="100%">

				<tr>
					<td><input type="file" name="install_package" size="75"> <input type="submit" value="Install"></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="payment_method_id" value="<?php echo $this->detail->payment_method_id; ?>"/>
	<input type="hidden" name="task" value="install"/>
	<input type="hidden" name="view" value="payment_detail"/>
</form>
