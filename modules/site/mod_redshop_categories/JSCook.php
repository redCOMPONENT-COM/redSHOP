<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

if (!defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

global $jscook_type, $jscookMenu_style, $jscookTree_style;


if (!class_exists('redCategoryMenu'))
{
	class redCategoryMenu
	{
		/***************************************************
		 * function traverse_tree_down
		 */
		function traverse_tree_down(&$mymenu_content, $category_id = '0', $level = '0', $params = '', $shopper_group_id)
		{
			static $ibg = 0;
			global $urlpath, $redproduct_menu;
			$db = JFactory::getDbo();
			$level++;
			$redproduct_menu = new modProMenuHelper;

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
			if ($shopper_group_id)
			{
				$shoppergroup_cat = $redproduct_menu->get_shoppergroup_cat($shopper_group_id);
			}

			$query = "SELECT category_name, category_id, category_child_id FROM #__redshop_category AS a "
				. "LEFT JOIN #__redshop_category_xref as b ON a.category_id=b.category_child_id "
				. "WHERE a.published='1' "
				. "AND b.category_parent_id= " . (int) $category_id;
			if ($shopper_group_id && count($shoppergroup_cat) > 0)
			{
				$query .= " and category_id IN(" . $shoppergroup_cat[0] . ")";
			}
			$query .= " ORDER BY " . $sortparam . "";
			//	."ORDER BY ".$sortparam."";
			$db->setQuery($query);
			$traverse_results = $db->loadObjectList();
			$objhelper        = new redhelper;
			$Itemid           = JRequest::getInt('Itemid');

			foreach ($traverse_results as $traverse_result)
			{

				$cItemid = $objhelper->getCategoryItemid($traverse_result->category_id);
				if ($cItemid != "")
				{
					$tmpItemid = $cItemid;
				}
				else
				{
					$tmpItemid = $Itemid;
				}
				if ($ibg != 0)
					$mymenu_content .= ",";

				$mymenu_content .= "\n[ '<img src=\"' + ctThemeXPBase + 'darrow.png\" alt=\"arr\" />','" . $traverse_result->category_name . "','" . JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $traverse_result->category_id . '&Itemid=' . $tmpItemid) . "',null,'" . $traverse_result->category_name . "'\n ";

				$ibg++;

				/* recurse through the subcategories */
				$this->traverse_tree_down($mymenu_content, $traverse_result->category_child_id, $level, $params, $shopper_group_id);

				/* let's see if the loop has reached its end */
				$mymenu_content .= "]";
			}

		}
	}
}

$Itemid = JRequest::getInt('Itemid');
$TreeId = JRequest::getInt('TreeId');
$js_src = JURI::root() . 'modules/mod_redshop_categories';

$document = JFactory::getDocument();
JHTML::script($js_src . '/JSCook/JSCookMenu.js');
$document->addScriptDeclaration('var ctThemeXPBase = "' . $js_src . '/ThemeXP/"');
$document->addScriptDeclaration('var cmThemeOfficeBase = "' . $js_src . '/ThemeOffice/"');

if ($jscook_type == "tree")
{

	if ($jscookTree_style == "ThemeXP")
	{
		$jscook_tree = "ctThemeXP1";
	}
	if ($jscookTree_style == "ThemeNavy")
	{
		$jscook_tree = "ctThemeNavy";
	}

	JHTML::script($js_src . '/JSCookTree.js');
	JHTML::script($js_src . '/' . $jscookTree_style . '/theme.js');
	JHTML::stylesheet($js_src . '/' . $jscookTree_style . '/theme.css');
	$_jscook = new redCategoryMenu;
}
else
{
	JHTML::script($js_src . '/JSCook/JSCookMenu.js');
	JHTML::script($js_src . '/JSCook/theme.js');
	JHTML::stylesheet($js_src . '/JSCook/theme.css');
	$_jscook = new redCategoryMenu;
}

// create a unique tree identifier, in case multiple trees are used
// (max one per module)
$varname = "JSCook_" . uniqid($jscook_type . "_");

$menu_htmlcode = '<div align="left" class="mainlevel" id="div_' . $varname . '"></div>
<script type="text/javascript">
//<!--
function ' . $varname . '_addEvent( obj, type, fn )
{
   if (obj.addEventListener) {
      obj.addEventListener( type, fn, false );
   } else if (obj.attachEvent) {
      obj["e"+type+fn] = fn;
      obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
      obj.attachEvent( "on"+type, obj[type+fn] );
   }
}

function ' . $varname . '_removeEvent( obj, type, fn )
{
   if (obj.removeEventListener) {
      obj.removeEventListener( type, fn, false );
   } else if (obj.detachEvent) {
      obj.detachEvent( "on"+type, obj[type+fn] );
      obj[type+fn] = null;
      obj["e"+type+fn] = null;
   }
}

var ' . $varname . ' =
[
';


$_jscook->traverse_tree_down($menu_htmlcode, '0', '0', $params, $shopper_group_id);


$menu_htmlcode .= "];
";
if ($jscook_type == "tree")
{
	$menu_htmlcode .= "var treeindex = ctDraw ('div_$varname', $varname, $jscook_tree, '$jscookTree_style', 0, 0);";
}
else
{
	$menu_htmlcode .= "cmDrawNow =function() { cmDraw ('div_$varname', $varname, '$menu_orientation', cm$jscookMenu_style, '$jscookMenu_style'); };
	" . $varname . "_addEvent( window, \"load\", cmDrawNow, false );";
}

$menu_htmlcode .= "
//-->
</script>\n";


if ($jscook_type == "tree")
{
	if ($TreeId)
	{
		$menu_htmlcode .= "<input type=\"hidden\" id=\"TreeId\" name=\"TreeId\" value=\"$TreeId\" />\n";
		$menu_htmlcode .= "<script language=\"JavaScript\" type=\"text/javascript\">ctExposeTreeIndex( treeindex, parseInt(ctGetObject('TreeId').value));</script>\n";
	}
}
$menu_htmlcode .= "<noscript>";
$menu_htmlcode .= $redproduct_menu->get_category_tree($params, $category_id, $class_mainlevel, $list_css_class = "mm123", $highlighted_style = "font-style:italic;", $shopper_group_id);
$menu_htmlcode .= "\n</noscript>\n";
echo $menu_htmlcode;

