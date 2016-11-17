<?php

class redTigraTreeMenu
{
	/***************************************************
	 * function traverse_tree_down
	 */
	function traverse_tree_down(&$mymenu_content, $category_id = '0', $level = '0', $shopper_group_id)
	{
		static $ibg = 0;
		global $Itemid, $sortparam;

		$db        = JFactory::getDbo();
		$objhelper = redhelper::getInstance();
		$Itemid    = JRequest::getInt('Itemid');
		$level++;

		if ($shopper_group_id)
		{
			$shoppergroup_cat = ModProMenuHelper::get_shoppergroup_cat($shopper_group_id);
		}
		else
		{
			$shoppergroup_cat = 0;
		}

		$query = "SELECT category_name as cname, category_id as cid, category_child_id as ccid FROM #__redshop_category as a "
			. "LEFT JOIN #__redshop_category_xref as b ON a.category_id=b.category_child_id "
			. "WHERE a.published=1 "
			. "AND b.category_parent_id=" . (int) $category_id;

		if ($shopper_group_id && $shoppergroup_cat)
		{
			$query .= " and category_id in (" . $shoppergroup_cat . ")";
		}

		$query .= " ORDER BY " . $sortparam . "";
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		if (!($categories == null))
		{
			$i = 1;

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