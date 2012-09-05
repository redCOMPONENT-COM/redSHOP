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
JHTML::_('behavior.modal');
	
$uri =& JURI::getInstance();
$url= $uri->root();
$Itemid	= JRequest::getVar('Itemid');
$user = &JFactory::getUser();
$option = 'com_redshop'; 
//echo $user->id;
echo "<div class='mod_redshop_shoppergrouplogo'>";
if(!$user->id)
{
	if (is_file(JPATH_ROOT.'/components/'.$option.'/assets/images/shopperlogo/'.DEFAULT_PORTAL_LOGO))
	{
		echo "<img src='".$url."/components/".$option."/assets/images/shopperlogo/".DEFAULT_PORTAL_LOGO."' width='".$thumbwidth."' height='".$thumbheight."' />";
	}
} else {
	if (is_file(JPATH_ROOT.'/components/'.$option.'/assets/images/shopperlogo/'.$rows->shopper_group_logo))
	{
		echo "<img src='".$url."/components/".$option."/assets/images/shopperlogo/".$rows->shopper_group_logo."' width='".$thumbwidth."' height='".$thumbheight."' />";
	}
}
echo "</div>";?>