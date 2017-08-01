<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


?>
<script>
	function frm_submit() {
		var frm = document.adminForm;
		document.getElementById('continue').style.display = "none";
		document.getElementById('disturb').style.display = "block";
		document.getElementById('loaderimg').style.display = "block";
		document.getElementById('btn_submit').style.display = "none";
		frm.submit();
	}
</script>
<form name="adminForm" id="adminForm" action="index.php">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td id="continue" align="center"><strong><?php echo JText::_('COM_REDSHOP_ARE_YOU_SURE')?></strong></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td style="display:none;" id="disturb" align="center">
				<strong><?php echo JText::_('COM_REDSHOP_PLEASE_DO_NOT_DISTURB')?></strong></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td style="display:none;" id="loaderimg" align="center"><img
					src='<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH; ?>ajax-loader.gif'/></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="center"><input type="button" name="btn_submit" id="btn_submit" value="Confirm"
			                          onclick="frm_submit();"/></td>
		</tr>

	</table>
	<input type="hidden" name="view" value="zip_import"/>
	<input type="hidden" name="option" value="com_redshop"/>
</form>
