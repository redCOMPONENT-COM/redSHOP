<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

global $root_label, $urlpath;
$urllive = $urlpath;

$db              = JFactory::getDbo();
$objhelper       = new redhelper ();
$Itemid          = JRequest::getInt('Itemid', '1');
$redproduct_menu = new modProMenuHelper;

/************** CATEGORY TREE *******************************/

/* dTree API, default value
* change to fit your needs **/
$useSelection   = 'true';
$useLines       = 'true';
$useIcons       = 'true';
$useStatusText  = 'false';
$useCookies     = 'false';
$closeSameLevel = 'false';

// if all folders should be open, we will ignore the closeSameLevel
$openAll = 'false';
if ($openAll == "true")
{
	$closeSameLevel = "false";
}


$menu_htmlcode = "";

// what should be used as the base of the tree?
// ( could be *first* menu item, *site* name, *module*, *menu* name or *text* )
$base = "first";


// in case *text* should be the base node, what text should be displayed?
$basetext = "";

// what category_id is selected?

$category_id = JRequest::getInt('cid');

if ($params->get('categorysorttype') == "catnameasc")
{
	$sortparam = "name ASC";
}
if ($params->get('categorysorttype') == "catnamedesc")
{
	$sortparam = "name DESC";
}
if ($params->get('categorysorttype') == "newest")
{
	$sortparam = "id DESC";
}
if ($params->get('categorysorttype') == "catorder")
{
	$sortparam = "ordering ASC";
}

if ($shopper_group_id)
{
	$shoppergroup_cat = $redproduct_menu->get_shoppergroup_cat($shopper_group_id);
}
else
{
	$shoppergroup_cat = 0;
}

// select menu items from database
$query = "SELECT id,parent_id,name FROM #__redshop_category "
	. "WHERE c.published=1 ";

if ($shopper_group_id && $shoppergroup_cat)
{
	$query .= " and id IN(" . $shoppergroup_cat . ")";
}

$query .= " ORDER BY " . $sortparam . "";
//."ORDER BY ".$sortparam." ";
$db->setQuery($query);
$catdatas = $db->loadObjectList();

// how many menu items in this menu?
// create a unique tree identifier, in case multiple dtrees are used
// (max one per module)
$tree = "d" . uniqid("tree_");

// start creating the content
// create left aligned table, load the CSS stylesheet and dTree code
$menu_htmlcode .= "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\"><tr><td align=\"left\">\n";
$menu_htmlcode .= "<link rel=\"stylesheet\" href=\"$js_src/dtree/dtree.css\" type=\"text/css\" />\n";
$menu_htmlcode .= "<script type=\"text/javascript\" src=\"$js_src/dtree/dtree.js\"></script>\n";
$menu_htmlcode .= "<script type=\"text/javascript\">\n";

// create the tree, using the unique name
// pass the live_site parameter on so dTree can find the icons
$menu_htmlcode .= "$tree = new dTree('$tree',\"$js_src\");\n";

// pass on the dTree API parameters
$menu_htmlcode .= "$tree.config.useSelection=" . $useSelection . ";\n";
$menu_htmlcode .= "$tree.config.useLines=" . $useLines . ";\n";
$menu_htmlcode .= "$tree.config.useIcons=" . $useIcons . ";\n";
$menu_htmlcode .= "$tree.config.useCookies=" . $useCookies . ";\n";
$menu_htmlcode .= "$tree.config.useStatusText=" . $useStatusText . ";\n";
$menu_htmlcode .= "$tree.config.closeSameLevel=" . $closeSameLevel . ";\n";

$basename = $root_label;

// what is the ID of this node?
$baseid = 0; //$db->f("category_parent_id");
// create the link (if not a menu item, no link [could be: to entry page of site])
$baselink = ($base == "first") ? $urllive . 'index.php?option=com_redshop&view=category&layout=detail' : "";

// remember which item is open, normally $Itemid
// except when we want the first item (e.g. Home) to be the base;
// in that case we have to pretend all remaining items belong to "Home"
$openid = $category_id;

// it could be that we are displaying e.g. mainmenu in this dtree,
// but item in usermenu is selected,
// so: for the rest of this module track if this menu contains the selected item
// Default value: first node (=baseid), but not selected
$opento          = $baseid;
$opento_selected = "false";
// what do you know... the first node was selected
if ($baseid == $openid)
{
	$opento_selected = "true";
}
$target = "";

// create the first node, parent is always -1
$menu_htmlcode .= "$tree.add(\"$baseid\",\"-1\",\"$basename\",\"$baselink\",\"\",\"$target\");\n";


$document = JFactory::getDocument();


foreach ($catdatas as $catdata)
{
	$cItemid = RedshopHelperUtility::getCategoryItemid($catdata->id);
	if ($cItemid != "")
	{
		$tmpItemid = $cItemid;
	}
	else
	{
		$tmpItemid = $Itemid;
	}

	// get name and link (just to save space in the code later on)
	$name = $catdata->name . ($redproduct_menu->products_in_category($catdata->id, $params));
	$url  = JRoute::_("index.php?option=com_redshop&view=category&layout=detail&Itemid=" . $tmpItemid . "&cid=" . $catdata->id);
	$menu_htmlcode .= "$tree.add(\"" . $catdata->id . "\",\"" . $catdata->parent_id . "\",\"$name\",\"$url\",\"\",\"$target\");\n";

	// if this node is the selected node
	if ($catdata->id == $openid)
	{
		$opento          = $openid;
		$opento_selected = "true";
	}
}

$menu_htmlcode .= "document.write($tree);\n";
$menu_htmlcode .= $openAll == "true" ? "$tree.openAll();\n" : "$tree.closeAll();\n";
$menu_htmlcode .= "$tree.openTo(\"$opento\",\"$opento_selected\");\n";
$menu_htmlcode .= "</script>\n";
$menu_htmlcode .= "<noscript>\n";
$menu_htmlcode .= $redproduct_menu->get_category_tree($params, $category_id, $class_mainlevel, $list_css_class = "mm123", $highlighted_style = "font-style:italic;", $shopper_group_id);

$menu_htmlcode .= "</noscript>\n";
$menu_htmlcode .= "</td></tr></table>\n";

echo $menu_htmlcode;
