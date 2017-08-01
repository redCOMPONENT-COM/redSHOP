<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

class modProMenuHelper
{
	public function hasChilds($category_id)
	{
		$db = JFactory::getDbo();

		if (empty($GLOBALS['category_info'][$category_id]['has_childs']))
		{
			$q = "SELECT category_child_id FROM #__redshop_category_xref ";
			$q .= "WHERE category_parent_id=" . (int) $category_id;
			$db->setQuery($q);

			if ($db->loadObjectList() > 0)
			{
				$GLOBALS['category_info'][$category_id]['has_childs'] = true;
			}
			else
			{
				$GLOBALS['category_info'][$category_id]['has_childs'] = false;
			}
		}

		return $GLOBALS['category_info'][$category_id]['has_childs'];
	}

	public function sortCategoryTreeArray(&$categoryArr, $parent_selected)
	{
		// Copy the Array into an Array with auto_incrementing Indexes
		// Array of category table primary keys
		$key = array_keys($categoryArr);

		// Category count
		$nrows = $size = count($key);

		/** FIRST STEP
		 * Order the Category Array and build a Tree of it
		 **/

		$id_list    = array();
		$row_list   = array();
		$depth_list = array();

		$parent_ids      = array();
		$parent_ids_hash = array();

		// Build an array of category references
		$category_tmp = array();

		for ($i = 0; $i < $size; $i++)
		{
			$category_tmp[$i] = $categoryArr[$key[$i]];
			$parent_ids[$i]   = $category_tmp[$i]['category_parent_id'];

			if ($category_tmp[$i]["category_parent_id"] == $parent_selected)
			{
				array_push($id_list, $category_tmp[$i]["category_child_id"]);
				array_push($row_list, $i);
				array_push($depth_list, 0);
			}

			$parent_id = $parent_ids[$i];

			if (isset($parent_ids_hash[$parent_id]))
			{
				$parent_ids_hash[$parent_id][$i] = $parent_id;
			}
			else
			{
				$parent_ids_hash[$parent_id] = array($i => $parent_id);
			}
		}

		$loop_count = 0;

		// Hash to store children
		$watch      = array();

		while (count($id_list) < $nrows)
		{
			if ($loop_count > $nrows)
			{
				break;
			}

			$id_temp    = array();
			$row_temp   = array();
			$depth_temp = array();

			for ($i = 0, $countIdList = count($id_list); $i < $countIdList; $i++)
			{
				$id    = $id_list[$i];
				$row   = $row_list[$i];
				$depth = $depth_list[$i];

				array_push($id_temp, $id);
				array_push($row_temp, $row);
				array_push($depth_temp, $depth);

				if (isset($parent_ids_hash[$id]))
				{
					$children = $parent_ids_hash[$id];
				}
				else
				{
					$children = null;
				}

				if (!empty($children))
				{
					foreach ($children as $key => $value)
					{
						if (!isset($watch[$id][$category_tmp[$key]["category_child_id"]]))
						{
							$watch[$id][$category_tmp[$key]["category_child_id"]] = 1;

							array_push($id_temp, $category_tmp[$key]["category_child_id"]);
							array_push($row_temp, $key);
							array_push($depth_temp, $depth + 1);
						}
					}
				}
			}

			$id_list    = $id_temp;
			$row_list   = $row_temp;
			$depth_list = $depth_temp;

			$loop_count++;
		}

		return array(
			'id_list'      => $id_list,
			'row_list'     => $row_list,
			'depth_list'   => $depth_list,
			'category_tmp' => $category_tmp
		);
	}

	function getCategoryTreeArray($only_published = 1, $keyword = "", $shopper_group_id, $parent_selected_remove, $params)
	{
		global $categorysorttype;
		$db = JFactory::getDbo();
		$cid = JFactory::getApplication()->input->getInt('cid');

		if (empty($GLOBALS['category_info']['category_tree']))
		{
			// Get only published categories;
			$query = $db->getQuery(true)
				->select($db->qn('c.id'))
				->select($db->qn('c.description'))
				->select($db->qn('c.name'))
				->select($db->qn('c.ordering'))
				->select($db->qn('c.published'))
				->select($db->qn('c.id', 'cid'))
				->select($db->qn('c.parent_id', 'pid'))
				->from($db->qn('#__redshop_category', 'c'));

			// Only published
			if ($only_published)
			{
				$query->where($db->qn('c.published') . ' = 1');
			}

			// Filter via Shopper Group
			if ($shopper_group_id)
			{
				$shoppergroup_cat = $this->getShopperGroupCat($shopper_group_id);

				if ($shoppergroup_cat)
				{
					$query->where($db->qn('c.id') . ' IN (' . implode(',', $shoppergroup_cat) . ')');
				}
			}

			// Filter by keyword
			if (!empty($keyword))
			{
				$query->where('(' . $db->qn('c.name') . ' LIKE ' . $db->quote('%' . $keyword . '%')
					. ' OR ' . $db->qn('c.description') . ' LIKE ' . $db->quote('%' . $keyword . '%') . ')');
			}

			if ($parent_selected_remove)
			{
				$query->where($db->qn('c.id') . ' NOT IN (' . implode(',', $parent_selected_remove) . ')');
			}

			$baseOnCategory = $params->get('base_on_category', 'no');

			if (!empty($cid) && $baseOnCategory == 'yes')
			{
				$query->where($db->qn('c.parent_id') . ' = ' . $cid);
			}

			if ($categorysorttype == "catnameasc")
			{
				$query->order($db->qn('c.name') . ' ASC');
			}
			elseif ($categorysorttype == "catnamedesc")
			{
				$query->order($db->qn('c.name') . ' DESC');
			}
			elseif ($categorysorttype == "newest")
			{
				$query->order($db->qn('c.id') . ' DESC');
			}
			elseif ($categorysorttype == "catorder")
			{
				$query->order($db->qn('c.ordering') . ' ASC');
			}
			else
			{
				$query->order($db->qn('c.name') . ' ASC');
			}

			$db->setQuery($query);
			$cat_results = $db->loadObjectList();

			$categories = array();

			if (count($cat_results) > 0)
			{
				foreach ($cat_results as $cat_result)
				{
					$categories[$cat_result->cid]["category_child_id"]    = $cat_result->cid;
					$categories[$cat_result->cid]["category_parent_id"]   = $cat_result->pid;
					$categories[$cat_result->cid]["category_name"]        = $cat_result->name;
					$categories[$cat_result->cid]["category_description"] = $cat_result->description;
					$categories[$cat_result->cid]["ordering"]             = $cat_result->ordering;
					$categories[$cat_result->cid]["published"]            = $cat_result->published;
				}
			}

			$GLOBALS['category_info']['category_tree'] = $categories;

			return $GLOBALS['category_info']['category_tree'];
		}
		else
		{
			return $GLOBALS['category_info']['category_tree'];
		}
	}

	public function productCount($categoryId)
	{
		if (!isset($GLOBALS['category_info'][$categoryId]['product_count']))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('COUNT(p.product_id)')
				->from($db->qn('#__redshop_product', 'p'))
				->leftjoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id'))
				->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
				->where($db->qn('pcx.category_id') . ' = ' . $db->q((int) $categoryId))
				->where($db->qn('p.published') . ' = 1');

			$GLOBALS['category_info'][$categoryId]['product_count'] = $db->setQuery($query)->loadResult();
		}

		return $GLOBALS['category_info'][$categoryId]['product_count'];
	}

	public function productsInCategory($categoryId, $params = '')
	{
		$showNoOfProducts = $params->get('show_noofproducts', 'yes');

		if ($showNoOfProducts != 'yes')
		{
			return "";
		}

		$num = $this->productCount($categoryId);

		if (empty($num) || $this->hasChilds($categoryId))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('id'))
				->from($db->qn('#__redshop_category'))
				->where($db->qn('parent_id') . ' = ' . $db->q((int) $categoryId));

			$result = $db->setQuery($query)->loadObjectList();

			foreach ($result as $category)
			{
				$childId = $category->id;
				$num += $this->productCount($childId);
			}
		}

		return '(' . $num . ')';
	}

	function getCategoryTree($params, $category_id = 0, $links_css_class = "mainlevel", $highlighted_style = "font-style:italic;", $shopper_group_id = 0)
	{
		$objhelper              = redhelper::getInstance();
		$parent_selected        = $params->get('redshop_category', RedshopHelperCategory::getRootId());
		$parent_selected_remove = $params->get('redshop_category_remove', '');

		$categories = $this->getCategoryTreeArray(1, "", $shopper_group_id, $parent_selected_remove, $params);

		// Sort array of category objects
		$result       = $this->sortCategoryTreeArray($categories, $parent_selected);
		$row_list     = $result['row_list'];
		$depth_list   = $result['depth_list'];
		$category_tmp = $result['category_tmp'];

		// Copy the Array into an Array with auto_incrementing Indexes
		// Array of category table primary keys
		$key = array_keys($categories);

		// Category count
		$nrows = count($key);

		$html = "";

		// Find out if we have subcategories to display
		$allowed_subcategories = array();
		$root                  = array('category_child_id' => 0, 'category_parent_id' => 0);

		if (!empty($categories[$category_id]["category_parent_id"]))
		{
			// Find the Root Category of this category
			$root                    = $categories[$category_id];
			$allowed_subcategories[] = $categories[$category_id]["category_parent_id"];

			// Loop through the Tree up to the root
			while (!empty($root["category_parent_id"]))
			{
				$allowed_subcategories[] = $categories[$root["category_child_id"]]["category_child_id"];

				if (isset($categories[$root["category_parent_id"]]))
				{
					$root = $categories[$root["category_parent_id"]];
				}
				else
				{
					$root = array('category_child_id' => 0, 'category_parent_id' => 0);
				}
			}
		}

		// Fix the empty Array Fields
		if ($nrows < count($row_list))
		{
			$nrows = count($row_list);
		}

		if (count($depth_list) > 0)
		{
			$depth = max($depth_list);
		}
		else
		{
			$depth = 0;
		}

		$sub    = 0;

		// Now show the categories
		for ($n = 0; $n < $nrows; $n++)
		{
			if (!isset($row_list[$n]) || !isset($category_tmp[$row_list[$n]]["category_child_id"]))
			{
				continue;
			}

			if ($category_id == $category_tmp[$row_list[$n]]["category_child_id"] || $category_tmp[$row_list[$n]]["category_child_id"] == $root["category_child_id"]
				|| in_array($category_tmp[$row_list[$n]]["category_child_id"], $allowed_subcategories))
			{
				$style = $highlighted_style;
			}
			else
			{
				$style = "";
			}

			$allowed = false;

			if ($depth_list[$n] > 0)
			{
				// Subcategory!
				if ((isset($root) && in_array($category_tmp[$row_list[$n]]["category_child_id"], $allowed_subcategories))
					|| $category_tmp[$row_list[$n]]["category_parent_id"] == $category_id
					|| $category_tmp[$row_list[$n]]["category_parent_id"] == @$categories[$category_id]["category_parent_id"]
					|| $category_tmp[$row_list[$n]]["category_parent_id"] == $root["category_child_id"])
				{
					$allowed = true;
				}
			}
			else
			{
				$allowed = true;
			}

			$append = "";
			$class  = "";


			if ($allowed)
			{
				if ($n == 0)
				{
					$html .= '<ul>';
				}

				if ($style == $highlighted_style)
				{
					$append = '&id=active_menu';
					$class  = "class='parent-active'";
				}

				if ($depth_list[$n] > 0)
				{
					$css_class = "sublevel";

					if ($depth == $depth_list[$n] && $style == $highlighted_style)
					{
						$class  = "class='active'";
					}

					if ($depth_list[$n] > ($sub))
					{
						$html .= '<ul>';
					}
					elseif ($depth_list[$n] < ($sub))
					{
						for ($i = $depth_list[$n]; $i < $sub; $i++)
						{
							$html .= '</ul></li>';
						}
					}

					$sub = $depth_list[$n];
				}
				else
				{
					$css_class = $links_css_class;

					if ($sub > 0)
					{
						$html .= str_repeat("</ul></li>", $sub);
						$sub = 0;
					}

					$html .= "</li>";
				}

				$catname = JText::_($category_tmp[$row_list[$n]]["category_name"]);

				$Itemid = RedshopHelperUtility::getCategoryItemid($category_tmp[$row_list[$n]]["category_child_id"]);

				if (!$Itemid)
				{
					$Itemid = JRequest::getInt('Itemid');
				}

				$uri = JURI::getInstance();

				$catlink = 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $category_tmp[$row_list[$n]]["category_child_id"] . $append . '&Itemid=' . $Itemid;
				$html .= '<li ' . $class . ' ><a title="' . $catname . '" style="display:block;' . $style . '" class="' . $css_class . '" href=' . JRoute::_($catlink) . '>'
					. str_repeat("", $depth_list[$n]) . $catname
					. $this->productsInCategory($category_tmp[$row_list[$n]]["category_child_id"], $params)
					. '</a>';

				if ($n == ($nrows - 1))
				{
					$html .= "</ul>";
				}
			}
		}

		return $html;
	}

	public function getShopperGroupCat($shopperGroupId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('shopper_group_categories'))
			->from($db->qn('#__redshop_shopper_group'))
			->where($db->qn('shopper_group_id') . ' = ' . $db->q((int) $shopperGroupId));

		return $db->setQuery($query)->loadResult();
	}
}
