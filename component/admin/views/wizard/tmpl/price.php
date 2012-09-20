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

$params = JRequest::getVar('params');
?>
<div>
<form action="?option=com_redshop" method="POST" name="installform" id="installform">
<table class="admintable">
	<tr valign="top">
		<td width="50%">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'PRICE' ); ?></legend>
				<?php echo $this->loadTemplate('price');?>
			</fieldset>
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'TAX_TAB' ); ?></legend>
				<?php echo $this->loadTemplate('vat');?>
			</fieldset>
		</td>
		<td width="50%">
			<fieldset class="adminform">
			<legend><?php echo JText::_( 'DISCOUPON_TAB' ); ?></legend>
				<?php echo $this->loadTemplate('discount');?>
			</fieldset>
		</td>
	</tr>
	<tr>
		<td>
		<input type="hidden" name="view" value="wizard" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="substep" value="<?php echo $params->step;?>"/>
		<input type="hidden" name="go" value=""/>
		</td>
	</tr>
</table>
</form>
</div>