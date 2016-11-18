<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_articles_latest
 *
 * @since  1.5.3
 */

abstract class ModDtreeMenuHelper
{
	/**
	 * traverseTreeDown description
	 * 
	 * @param   [type]  &$mymenuContent  [description]
	 * @param   string  $categoryId      [description]
	 * @param   string  $level           [description]
	 * @param   string  $params          [description]
	 * @param   [type]  $shopperGroupId  [description]
	 * 
	 * @return  [type]                  [description]
	 */
	public static function traverseTreeDown(&$mymenuContent, $categoryId = '0', $level = '0', $params = '', $shopperGroupId = '0')
	{
		$rootLabel = $params->get('root_label', 'Shop');
		$uri = JURI::getInstance();
		$urlpath = $uri->root();
		$urllive = $urlpath;
		$liveModulePath     = $urlpath . 'modules/mod_redshop_categories';
		$classSfx = $params->get('class_sfx', '');
		$classMainLevel = "mainlevel_redshop" . $classSfx;

		$db              = JFactory::getDbo();
		$objhelper       = new redhelper;
		$Itemid          = JRequest::getInt('Itemid', '1');

		/************** CATEGORY TREE *******************************/

		/* dTree API, default value
		* change to fit your needs **/
		$useSelection   = 'true';
		$useLines       = 'true';
		$useIcons       = 'true';
		$useStatusText  = 'false';
		$useCookies     = 'false';
		$closeSameLevel = 'false';

		// If all folders should be open, we will ignore the closeSameLevel
		$openAll = 'false';

		if ($openAll == "true")
		{
			$closeSameLevel = "false";
		}

		$menuHtml = "";

		// What should be used as the base of the tree?
		// ( could be *first* menu item, *site* name, *module*, *menu* name or *text* )
		$base = "first";

		// In case *text* should be the base node, what text should be displayed?
		$basetext = "";

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

		if ($shopperGroupId)
		{
			$shopperGroupCat = ModProMenuHelper::getShoppergroupCat($shopperGroupId);
		}
		else
		{
			$shopperGroupCat = 0;
		}

		// Select menu items from database
		$query = "SELECT category_id,category_parent_id,category_name FROM #__redshop_category AS c "
			. "LEFT JOIN #__redshop_category_xref AS cx ON c.category_id=cx.category_child_id "
			. "WHERE c.published=1 ";

		if ($shopperGroupId && $shopperGroupCat)
		{
			$query .= " and category_id IN(" . $shopperGroupCat . ")";
		}

		$query .= " ORDER BY " . $sortparam . "";
		$db->setQuery($query);
		$catdatas = $db->loadObjectList();

		/**
		 * How many menu items in this menu?
		 * Create a unique tree identifier, in case multiple dtrees are used
		 * Max one per module
		 * */
		$tree = "d" . uniqid("tree_");

		// Start creating the content
		// Create left aligned table, load the CSS stylesheet and dTree code
		$menuHtml .= "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\"><tr><td align=\"left\">\n";
		$menuHtml .= "<link rel=\"stylesheet\" href=\"$liveModulePath/tmpl/dtree/dtree.css\" type=\"text/css\" />\n";
		$menuHtml .= "<script type=\"text/javascript\" src=\"$liveModulePath/tmpl/dtree/dtree.js\"></script>\n";
		$menuHtml .= "<script type=\"text/javascript\">\n";

		/**
		 * create the tree, using the unique name
		 * pass the live_site parameter on so dTree can find the icons
		 **/
		$menuHtml .= "$tree = new dTree('$tree',\"$liveModulePath/tmpl/\");\n";

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

		return $menuHtml;
	}
}
