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


?>
<script>
function frm_submit(){
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
		<td id="continue"  align="center"><strong><?php echo JTEXT::_('ARE_YOU_SURE')?></strong></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td  style="display:none;"  id="disturb" align="center"><strong><?php echo JTEXT::_('PLEASE_DO_NOT_DISTURB')?></strong></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td  style="display:none;"  id="loaderimg"  align="center"><img src='<?php echo JURI::base()?>components/com_redshop/assets/images/ajax-loader.gif' /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><input type="button" name="btn_submit" id="btn_submit" value="Confirm" onclick="frm_submit();" /></td>
	</tr>

</table>
<input type="hidden" name="view" value="zip_import" />
<input type="hidden" name="option" value="com_redshop" />
</form>
