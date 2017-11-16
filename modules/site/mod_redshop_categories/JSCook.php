<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

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
			global $redproduct_menu;
			$db = JFactory::getDbo();
			$level++;
			$redproduct_menu = new modProMenuHelper;

			if ($params->get('categorysorttype') == "catnameasc")
			{
				$sortparam = "name ASC";
			}
			elseif ($params->get('categorysorttype') == "catnamedesc")
			{
				$sortparam = "name DESC";
			}
			elseif ($params->get('categorysorttype') == "newest")
			{
				$sortparam = "id DESC";
			}
			elseif ($params->get('categorysorttype') == "catorder")
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

			$query = "SELECT name, id FROM #__redshop_category "
				. "WHERE published='1' "
				. "AND parent_id= " . (int) $category_id;

			if ($shopper_group_id && $shoppergroup_cat)
			{
				$query .= " and id IN(" . $shoppergroup_cat . ")";
			}

			$query .= " ORDER BY " . $sortparam . "";

			$db->setQuery($query);
			$traverse_results = $db->loadObjectList();
			$objhelper        = redhelper::getInstance();
			$Itemid           = JRequest::getInt('Itemid');

			foreach ($traverse_results as $traverse_result)
			{
				$cItemid = RedshopHelperUtility::getCategoryItemid($traverse_result->id);

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

				$mymenu_content .= "\n[ '<img src=\"' + ctThemeXPBase + 'darrow.png\" alt=\"arr\" />','" . $traverse_result->name . "','" . JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $traverse_result->id . '&Itemid=' . $tmpItemid) . "',null,'" . $traverse_result->name . "'\n ";

				$ibg++;

				/* recurse through the subcategories */
				$this->traverse_tree_down($mymenu_content, $traverse_result->id, $level, $params, $shopper_group_id);

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
$document->addScriptDeclaration('var ctThemeXPBase = "' . $js_src . '/ThemeXP/";');

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

	$document->addScriptDeclaration(
		RedshopLayoutHelper::render(
			$jscookTree_style . '.theme',
			array(
				'ctThemeXPBase' => $js_src . '/' . $jscookTree_style . '/'
			),
			'modules/mod_redshop_categories/'
		)
	);
	JHTML::stylesheet($js_src . '/' . $jscookTree_style . '/theme.css');
	$_jscook = new redCategoryMenu;
}
else
{
	JHTML::script($js_src . '/JSCook/JSCookMenu.js');

	$document->addScriptDeclaration(
		RedshopLayoutHelper::render(
			'JSCook.theme',
			array(
				'cmThemeOfficeBase' => $js_src . '/ThemeOffice/'
			),
			'modules/mod_redshop_categories/'
		)
	);

	JHTML::stylesheet($js_src . '/JSCook/theme.css');
	$_jscook = new redCategoryMenu;
}

// Create a unique tree identifier, in case multiple trees are used
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
