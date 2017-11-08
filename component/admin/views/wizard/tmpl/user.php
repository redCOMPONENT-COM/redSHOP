<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$params = JFactory::getApplication()->input->get('params', '', 'raw');
?>
<div>
	<form action="?option=com_redshop" method="POST" name="installform" id="installform">
		<table class="admintable table">
			<tr valign="top">
				<td width="50%">
					<fieldset class="adminform">
						<legend><?php echo JText::_('COM_REDSHOP_REGISTRATION'); ?></legend>
						<?php echo $this->loadTemplate('registration');?>
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
