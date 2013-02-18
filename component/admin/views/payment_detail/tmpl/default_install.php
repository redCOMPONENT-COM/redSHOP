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
		
		if (form.payment_method_name.value == ""){
			alert( "<?php echo JText::_('COM_REDSHOP_PAYMENT_METHOD_MUST_HAVE_A_NAME', true ); ?>" );
		} else {
			
			submitform( pressbutton );
		}
	}
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_INSTALL_NEW_PACKAGE' ); ?></legend>

		<table class="admintable" width="100%">
		 
		 <tr>
			 <td><input type="file" name="install_package" size="75"> <input type="submit" value="Install"></td>
			 		 </tr>		
	  </table>
	</fieldset>
</div> 
<div class="clr"></div>
<input type="hidden" name="payment_method_id" value="<?php echo $this->detail->payment_method_id; ?>" />
<input type="hidden" name="task" value="install" />
<input type="hidden" name="view" value="payment_detail" />
</form>