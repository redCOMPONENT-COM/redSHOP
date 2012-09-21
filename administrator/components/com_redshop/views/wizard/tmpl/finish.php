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
	<tr>
		<td>
			<div class="wizard_intro_text"><?php echo JText::_('COM_REDSHOP_FINISH_WIZARD_INTRO_TEXT');?></div>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo JText::_('COM_REDSHOP_INSTALL_DEMO_CONTENT');?></strong><input type="checkbox" name="installcontent" value="1"></td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="view" value="wizard" />
			<input type="hidden" name="task" value="finish" />
			<input type="hidden" name="substep" value="<?php echo $params->step;?>"/>
			<input type="hidden" name="go" value=""/>
		</td>
	</tr>
</table>
</form>
</div>