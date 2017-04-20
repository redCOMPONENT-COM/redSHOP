<?php

/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHtml::stylesheet('mod_redshop_categories/dtree.css', false, true);
JHtml::script('mod_redshop_categories/dtree.js', false, true);

$menuHtml = "";

// Start creating the content
// Create left aligned table, load the CSS stylesheet and dTree code
$menuHtml .= "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\"><tr><td align=\"left\">\n";
$menuHtml .= "<script type=\"text/javascript\">\n";

/**
 * create the tree, using the unique name
 * pass the live_site parameter on so dTree can find the icons
 **/
$menuHtml .= "$tree = new dTree('$tree',\"" . JUri::root() . "media/mod_redshop_categories/\");\n";

// Pass on the dTree API parameters
$menuHtml .= "$tree.config.useSelection=" . $useSelection . ";\n";
$menuHtml .= "$tree.config.useLines=" . $useLines . ";\n";
$menuHtml .= "$tree.config.useIcons=" . $useIcons . ";\n";
$menuHtml .= "$tree.config.useCookies=" . $useCookies . ";\n";
$menuHtml .= "$tree.config.useStatusText=" . $useStatusText . ";\n";
$menuHtml .= "$tree.config.closeSameLevel=" . $closeSameLevel . ";\n";

$basename = $rootLabel;

// What is the ID of this node?
$baseid = 0;

// Create the link (if not a menu item, no link [could be: to entry page of site])
$baselink = ($base == "first") ? $urllive . 'index.php?option=com_redshop&view=category&layout=detail' : "";

/**
 * remember which item is open, normally $Itemid
 * except when we want the first item (e.g. Home) to be the base;
 * in that case we have to pretend all remaining items belong to "Home"
 */
$openid = $categoryId;

/**
 * It could be that we are displaying e.g. mainmenu in this dtree,
 * but item in usermenu is selected,
 * so: for the rest of this module track if this menu contains the selected item
 * Default value: first node (=baseid), but not selected
 */
$openTo         = $baseid;
$openToSelected = "false";

// What do you know... the first node was selected
if ($baseid == $openid)
{
	$openToSelected = "true";
}

$target = "";

// Create the first node, parent is always -1
$menuHtml .= "$tree.add(\"$baseid\",\"-1\",\"$basename\",\"$baselink\",\"\",\"$target\");\n";

$document = JFactory::getDocument();

foreach ($catdatas as $catdata)
{
	$cItemid = $objhelper->getCategoryItemid($catdata->category_id);

	if ($cItemid != "")
	{
		$tmpItemid = $cItemid;
	}
	else
	{
		$tmpItemid = $Itemid;
	}

	// Get name and link (just to save space in the code later on)
	$name = $catdata->category_name . (ModProMenuHelper::productsInCategory($catdata->category_id, $params));
	$url  = JRoute::_("index.php?option=com_redshop&view=category&layout=detail&Itemid=" . $tmpItemid . "&cid=" . $catdata->category_id);
	$menuHtml .= "$tree.add(\"" . $catdata->category_id . "\",\"" . $catdata->category_parent_id . "\",\"$name\",\"$url\",\"\",\"$target\");\n";

	// If this node is the selected node
	if ($catdata->category_id == $openid)
	{
		$openTo          = $openid;
		$openToSelected = "true";
	}
}

$menuHtml .= "document.write($tree);\n";
$menuHtml .= $openAll == "true" ? "$tree.openAll();\n" : "$tree.closeAll();\n";
$menuHtml .= "$tree.openTo(\"$openTo\",\"$openToSelected\");\n";
$menuHtml .= "</script>\n";
$menuHtml .= "<noscript>\n";
$menuHtml .= ModProMenuHelper::getCategoryTree($params, $categoryId, $classMainLevel, $listCssClass = "mm123", $highlightedStyle = "font-style:italic;", $shopperGroupId);

$menuHtml .= "</noscript>\n";
$menuHtml .= "</td></tr></table>\n";

echo $menuHtml;
