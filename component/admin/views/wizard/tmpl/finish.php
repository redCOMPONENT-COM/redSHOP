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
			<tr>
				<td>
					<div class="wizard_intro_text"><?php echo JText::_('COM_REDSHOP_FINISH_WIZARD_INTRO_TEXT');?></div>
				</td>
			</tr>
			<tr>
				<td><label class="inline checkbox"><strong><?php echo JText::_('COM_REDSHOP_INSTALL_DEMO_CONTENT');?></strong><input type="checkbox"
				                                                                                      name="installcontent"
				                                                                                      value="1"></label></td>
			</tr>
			<tr>
				<td>
					<input type="hidden" name="view" value="wizard"/>
					<input type="hidden" name="task" value="finish"/>
					<input type="hidden" name="substep" value="<?php echo $params->step; ?>"/>
					<input type="hidden" name="go" value=""/>
				</td>
			</tr>
		</table>
	</form>
</div>
