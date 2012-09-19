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
<div class="welcome_wizard">
	<div class="wizard_intro_text"><?php echo JText::_('COM_REDSHOP_WELCOME_WIZARD_INTRO_TEXT');?></div>
	<div>&nbsp;</div>
	<?php
	$doc_link = '<a href="http://wiki.redcomponent.com" target="_blank">website</a>';
	$forum_link = '<a href="http://redcomponent.com/forum/54-redshop" target="_blank">redSHOP - redCOMPONENT Forum</a>';
	$contact_link = '<a href="http://redcomponent.com" target="_blank">redCOMPONENT</a>';
	$learn_more_link = '<a href="http://www.redcomponent.com" target="_blank">redcomponent.com</a>';

	?>
	<div class="wizard_redshop_feature"><?php echo sprintf(JText::_('COM_REDSHOP_WELCOME_REDSHOP_FEATURE_TEXT'),$doc_link,$forum_link,$contact_link,$learn_more_link);?></div>
	<div>&nbsp;</div>
	<div class="wizard_basic_info"><?php echo JText::_('COM_REDSHOP_WIZARD_BASIC_INFO_TEXT');?></div>
	<div>&nbsp;</div>
</div>
<div>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
	<tr>
		<td valign="top">
    		<img src="<?php echo REDSHOP_ADMIN_IMAGES_ABSPATH;?>261-x-88.png" alt="redSHOP Logo" align="left">
		</td>
		<td valign="top" width="100%">
			<strong>redSHOP</strong><br/>
        	<font class="small">by <a href="http://www.redcomponent.com" target="_blank">redcomponent.com </a><br/></font>
        	<font class="small">
        	Released under the terms and conditions of the <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GNU General Public License</a>.
        	</font>
			<p>Remember to check for updates on:
				<img src="http://images.redcomponent.com/redcomponent.jpg" alt="">
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<form action="?option=com_redshop" method="POST" name="installform" id="installform">
				<input type="hidden" name="step" value="1"/>
				<input type="hidden" name="go" value=""/>
			</form>
		</td>
	</tr>
</table>
</div>