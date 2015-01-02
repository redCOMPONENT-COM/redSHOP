<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergrouplogo
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$uri    = JURI::getInstance();
$url    = $uri->root();
$Itemid = JRequest::getInt('Itemid');
$user   = JFactory::getUser();
$option = 'com_redshop';
//echo $user->id;
echo "<div class='mod_redshop_shoppergrouplogo'>";
if (!$user->id)
{
	if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . DEFAULT_PORTAL_LOGO))
	{
		echo "<img src='" . REDSHOP_FRONT_IMAGES_ABSPATH . "shopperlogo/" . DEFAULT_PORTAL_LOGO . "' width='" . $thumbwidth . "' height='" . $thumbheight . "' />";
	}
}
else
{
	if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $rows->shopper_group_logo))
	{
		echo "<img src='" . REDSHOP_FRONT_IMAGES_ABSPATH . "shopperlogo/" . $rows->shopper_group_logo . "' width='" . $thumbwidth . "' height='" . $thumbheight . "' />";
	}
}
echo "</div>";?>
