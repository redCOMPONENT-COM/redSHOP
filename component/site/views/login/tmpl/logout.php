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
defined ('_JEXEC') or die ('restricted access');
JHTML::_('behavior.tooltip');
global $mainframe;
$option = JRequest::getVar('option');
$Itemid = JRequest::getVar('Itemid');
$user = JFactory::getUser();
$params = &$mainframe->getParams($option);
$menu =& JSite::getMenu();

$returnitemid = $params->get('logout',$Itemid);
?>
<form action="<?php echo JRoute::_('index.php?option='.$option.'&view=login'); ?>" method="post">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" height="40">
				<p><?php 
						if($user->id >0)
							echo JText::_('LOGOUT_DESCRIPTION');
						else
							 echo JText::_('LOGOUT_SUCCESS');
					?></p>
			</td>
		</tr>
		<?php if($user->id >0){?>
			<tr>
				<td><input type="submit" name="submit" class="button" value="<?php echo JText::_('LOGOUT'); ?>"></td>
			</tr>
		<?php }?>
	</table>
	
	<input type="hidden" name="logout" id="logout" value="<?php echo $returnitemid;?>">
	<input type="hidden" name="task" id="task" value="logout"> 
</form>