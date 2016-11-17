<?php

abstract class redshopJscookCategoryMenuHelper
{
	/***************************************************
	 * function traverse_tree_down
	 */
	public static function traverseTreeDown(&$mymenu_content, $category_id = '0', $level = '0', $params = '', $shopperGroupId)
	{
		static $ibg = 0;
		global $redproduct_menu;
		$db = JFactory::getDbo();
		$level++;
		$redproduct_menu = new modProMenuHelper;

		if ($params->get('categorysorttype') == "catnameasc")
		{
			$sortparam = "category_name ASC";
		}
		elseif ($params->get('categorysorttype') == "catnamedesc")
		{
			$sortparam = "category_name DESC";
		}
		elseif ($params->get('categorysorttype') == "newest")
		{
			$sortparam = "category_id DESC";
		}
		elseif ($params->get('categorysorttype') == "catorder")
		{
			$sortparam = "ordering ASC";
		}

		if ($shopperGroupId)
		{
			$shoppergroup_cat = $redproduct_menu->get_shoppergroup_cat($shopperGroupId);
		}
		else
		{
			$shoppergroup_cat = 0;
		}

		$query = "SELECT category_name, category_id, category_child_id FROM #__redshop_category AS a "
			. "LEFT JOIN #__redshop_category_xref as b ON a.category_id=b.category_child_id "
			. "WHERE a.published='1' "
			. "AND b.category_parent_id= " . (int) $category_id;

		if ($shopperGroupId && $shoppergroup_cat)
		{
			$query .= " and category_id IN(" . $shoppergroup_cat . ")";
		}

		$query .= " ORDER BY " . $sortparam . "";

		$db->setQuery($query);
		$traverse_results = $db->loadObjectList();
		$objhelper        = redhelper::getInstance();
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
			self::traverseTreeDown($mymenu_content, $traverse_result->category_child_id, $level, $params, $shopperGroupId);

			/* let's see if the loop has reached its end */
			$mymenu_content .= "]";
		}
	}
}
