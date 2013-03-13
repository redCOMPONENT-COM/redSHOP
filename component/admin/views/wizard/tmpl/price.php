<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$params = JRequest::getVar('params');
?>
<div>
	<form action="?option=com_redshop" method="POST" name="installform" id="installform">
		<table class="admintable">
			<tr valign="top">
				<td width="50%">
					<fieldset class="adminform">
						<legend><?php echo JText::_('COM_REDSHOP_PRICE'); ?></legend>
						<?php echo $this->loadTemplate('price');?>
					</fieldset>
					<fieldset class="adminform">
						<legend><?php echo JText::_('COM_REDSHOP_TAX_TAB'); ?></legend>
						<?php echo $this->loadTemplate('vat');?>
					</fieldset>
				</td>
				<td width="50%">
					<fieldset class="adminform">
						<legend><?php echo JText::_('COM_REDSHOP_DISCOUPON_TAB'); ?></legend>
						<?php echo $this->loadTemplate('discount');?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td>
					<input type="hidden" name="view" value="wizard"/>
					<input type="hidden" name="task" value="save"/>
					<input type="hidden" name="substep" value="<?php echo $params->step; ?>"/>
					<input type="hidden" name="go" value=""/>
				</td>
			</tr>
		</table>
	</form>
</div>