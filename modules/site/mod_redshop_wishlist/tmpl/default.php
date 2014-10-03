<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_wishlist
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$uri = JURI::getInstance();
$url = $uri->root();

$user      = JFactory::getUser();
$redhelper = new redhelper;
$Itemid    = $redhelper->getItemid();

// get product helper
// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminConfiguration');
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

if (MY_WISHLIST)
{
	if (!$user->id)
	{
		echo "<div class='mod_redshop_wishlist'>";
		if (count($rows) > 0)
		{
			$mywishlist_link = JRoute::_('index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=' . $Itemid);
			echo "<a href=\"" . $mywishlist_link . "\" >" . JText::_('COM_REDSHOP_VIEW_WISHLIST') . "</a>";
			// send mail link
		}
		else
		{
			echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') . "</div>";
		}
		echo "</div>";
	}
	else // if user logged in than display this code.
	{
		echo "<div class='mod_redshop_wishlist'>";
		if ((count($wishlists) > 0) || (count($rows) > 0))
		{
			$mywishlist_link = JRoute::_('index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=' . $Itemid);
			echo  "<a href=\"" . $mywishlist_link . "\" >" . JText::_('COM_REDSHOP_VIEW_WISHLIST') . "</a>";
		}
		else
		{
			echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') . "</div>";
		}
		echo "</div>";


	}
}
else
{
	echo "<div>" . JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST') . "</div>";
}
