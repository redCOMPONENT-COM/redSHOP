<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

if (!defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

global $Itemid, $urlpath, $sortparam;

// Decide which node to open (if any)
$Treeid = JRequest::getInt('Treeid');

// Get the root label
$root_label = $params->get('root_label');

if ($params->get('categorysorttype') == "catnameasc")
{
	$sortparam = "category_name ASC";
}
if ($params->get('categorysorttype') == "catnamedesc")
{
	$sortparam = "category_name DESC";
}
if ($params->get('categorysorttype') == "newest")
{
	$sortparam = "category_id DESC";
}
if ($params->get('categorysorttype') == "catorder")
{
	$sortparam = "ordering ASC";
}
// The tree generator
$vmTigraTree = new redTigraTreeMenu;

// A unique name for our tree (to support multiple instances of the menu)
$varname = uniqid("TigraTree_");

$document = JFactory::getDocument();
JHTML::Script('tree_tpl.js.php', $js_src . '/tigratree/', false);
JHTML::Script('tree.js', $js_src . '/tigratree/', false);

// Create the menu output
$menu_htmlcode = "<div class=\"$class_mainlevel\" style=\"text-align:left;\">
<script type=\"text/javascript\"><!--
var TREE_ITEMS_$varname = [\n";

// Create the root node
$menu_htmlcode .= "['" . $root_label . "', '" . JRoute::_($urlpath . 'index.php') . "',\n";

// Get the actual category items
$vmTigraTree->traverse_tree_down($menu_htmlcode, $category_id = '0', $level = '0', $shopper_group_id);

$menu_htmlcode .= "]];

var o_tree_$varname = new tree(TREE_ITEMS_$varname, TREE_TPL);
item_expand(o_tree_$varname, $Treeid);
o_tree_$varname.select($Treeid);
--></script>\n";

// Add a linked list in case JavaScript is disabled
$menu_htmlcode .= "<noscript>\n";
$menu_htmlcode .= $redproduct_menu->get_category_tree($params, $category_id, $class_mainlevel, $list_css_class = "mm123", $highlighted_style = "font-style:italic;", $shopper_group_id);
$menu_htmlcode .= "\n</noscript>\n";
$menu_htmlcode .= "</div>";

echo $menu_htmlcode;

class redTigraTreeMenu
{
	/***************************************************
	 * function traverse_tree_down
	 */
	function traverse_tree_down(&$mymenu_content, $category_id = '0', $level = '0', $shopper_group_id)
	{
		static $ibg = 0;
		global $Itemid, $urlpath, $sortparam;

		$db        = JFactory::getDbo();
		$objhelper = new redhelper ();
		$Itemid    = JRequest::getInt('Itemid');
		$level++;
		$redproduct_menu = new modProMenuHelper;
		if ($shopper_group_id)
		{
			$shoppergroup_cat = $redproduct_menu->get_shoppergroup_cat($shopper_group_id);
		}


		$query = "SELECT category_name as cname, category_id as cid, category_child_id as ccid FROM #__redshop_category as a "
			. "LEFT JOIN #__redshop_category_xref as b ON a.category_id=b.category_child_id "
			. "WHERE a.published=1 "
			. "AND b.category_parent_id=" . (int) $category_id;
		if ($shopper_group_id && count($shoppergroup_cat) > 0)
		{
			$query .= " and category_id in (" . $shoppergroup_cat[0] . ")";
		}
		$query .= " ORDER BY " . $sortparam . "";
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		if (!($categories == null))
		{
			$i             = 1;
			$numCategories = count($categories);
			foreach ($categories as $category)
			{
				$ibg++;
				$Treeid  = $ibg;
				$cItemid = $objhelper->getCategoryItemid($category->cid);
				if ($cItemid != "")
				{
					$tmpItemid = $cItemid;
				}
				else
				{
					$tmpItemid = $Itemid;
				}

				$mymenu_content .= str_repeat("\t", $level - 1);
				if ($level > 1 && $i == 1)
				{
					$mymenu_content .= ",";
				}
				$mymenu_content .= "['" . $category->cname;
				//$mymenu_content.= "','href='".JRoute::_($urlpath.'index.php?option=com_redshop&view=category&layout=detail&cid='.$category->cid.'&Treeid='.$Treeid.$itemid)."\''\n ";
				$mymenu_content .= "','href=\'" . JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $category->cid . '&Treeid=' . $Treeid . '&Itemid=' . $tmpItemid) . "\''\n ";
				/* recurse through the subcategories */
				$this->traverse_tree_down($mymenu_content, $category->ccid, $level, $shopper_group_id);
				$mymenu_content .= str_repeat("\t", $level - 1);

				/* let's see if the loop has reached its end */
				if ($i == sizeof($categories) && $level == 1)
				{
					$mymenu_content .= "]\n";
				}
				else
				{
					$mymenu_content .= "],\n";
				}
				$i++;
			}
		}
	}
}

