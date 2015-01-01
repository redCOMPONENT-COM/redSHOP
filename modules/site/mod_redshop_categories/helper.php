<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::load('RedshopHelperHelper');

class modProMenuHelper
{
	function has_childs($category_id)
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

	function sortCategoryTreeArray(&$categoryArr, $parent_selected)
	{
		// Copy the Array into an Array with auto_incrementing Indexes
		// Array of category table primary keys
		$key = array_keys($categoryArr);

		// Category count
		$nrows = $size = sizeOf($key);

		/** FIRST STEP
		 * Order the Category Array and build a Tree of it
		 **/

		$id_list    = array();
		$row_list   = array();
		$depth_list = array();

		$children        = array();
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
			$children   = array();

			for ($i = 0; $i < count($id_list); $i++)
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

	function getCategoryTreeArray($only_published = 1, $keyword = "", $shopper_group_id, $parent_selected_remove)
	{
		global $categorysorttype;
		$db = JFactory::getDbo();

		if (empty($GLOBALS['category_info']['category_tree']))
		{
			if ($categorysorttype == "catnameasc")
			{
				$sortparam = "#__redshop_category.category_name ASC";
			}
			elseif ($categorysorttype == "catnamedesc")
			{
				$sortparam = "#__redshop_category.category_name DESC";
			}
			elseif ($categorysorttype == "newest")
			{
				$sortparam = "#__redshop_category.category_id DESC";
			}
			elseif ($categorysorttype == "catorder")
			{
				$sortparam = "#__redshop_category.ordering ASC";
			}
			else
			{
				$sortparam = "#__redshop_category.category_name ASC";
			}

			if ($shopper_group_id)
			{
				$shoppergroup_cat = $this->get_shoppergroup_cat($shopper_group_id);
			}

			// Get only published categories
			$query = "SELECT category_id, category_description, category_name,category_child_id as cid, category_parent_id as pid,ordering, published
						FROM #__redshop_category, #__redshop_category_xref WHERE ";

			if ($only_published)
			{
				$query .= "#__redshop_category.published='1' AND ";
			}

			if ($parent_selected_remove)
			{
				$parent_selected_remove = implode(',', $parent_selected_remove);
				$query .= " category_id not in (" . $parent_selected_remove . ") and ";
			}

			$query .= "#__redshop_category.category_id=#__redshop_category_xref.category_child_id ";

			if (!empty($keyword))
			{
				$query .= "AND ( category_name LIKE '%$keyword%' ";
				$query .= "OR category_description LIKE '%$keyword%' ";
				$query .= ") ";
			}

			if ($shopper_group_id)
			{
				$query .= " and category_id in (" . $shoppergroup_cat[0] . ")";
			}

			$query .= "ORDER BY " . $sortparam . "";

			$db->setQuery($query);
			$cat_results = $db->loadObjectList();

			$categories = array();

			if (count($cat_results) > 0)
			{
				foreach ($cat_results as $cat_result)
				{
					$categories[$cat_result->cid]["category_child_id"]    = $cat_result->cid;
					$categories[$cat_result->cid]["category_parent_id"]   = $cat_result->pid;
					$categories[$cat_result->cid]["category_name"]        = $cat_result->category_name;
					$categories[$cat_result->cid]["category_description"] = $cat_result->category_description;
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

	function product_count($category_id)
	{
		$db = JFactory::getDbo();

		if (!isset($GLOBALS['category_info'][$category_id]['product_count']))
		{
			$count = "SELECT count(#__redshop_product.product_id) as num_rows from #__redshop_product,#__redshop_product_category_xref, #__redshop_category WHERE ";
			$q     = "";
			$q .= "#__redshop_product_category_xref.category_id='$category_id' ";
			$q .= "AND #__redshop_category.category_id=#__redshop_product_category_xref.category_id ";
			$q .= "AND #__redshop_product.product_id=#__redshop_product_category_xref.product_id ";
			$q .= " AND #__redshop_product.published='1'";


			$count .= $q;

			$db->setQuery($count);

			$noofrows = $db->loadObject();

			$GLOBALS['category_info'][$category_id]['product_count'] = $noofrows->num_rows;
		}

		return $GLOBALS['category_info'][$category_id]['product_count'];
	}

	function products_in_category($category_id, $params = '')
	{
		global $urlpath;
		$db = JFactory::getDbo();

		$show_noofproducts = $params->get('show_noofproducts', 'yes');
		$num               = $this->product_count($category_id);

		if ($show_noofproducts == 'yes')
		{
			if (empty($num) || $this->has_childs($category_id))
			{
				$q = "SELECT category_child_id FROM #__redshop_category_xref ";
				$q .= "WHERE category_parent_id=" . (int) $category_id;

				$db->setQuery($q);
				$catresults = $db->loadObjectList();

				foreach ($catresults as $catresult)
				{
					$child_product_id = $catresult->category_child_id;
					$num += $this->product_count($child_product_id);
				}
			}

			return " ($num) ";
		}
		else
		{
			return ("");
		}

	}

	function get_category_tree($params, $category_id = 0,
		$links_css_class = "mainlevel",
		$list_css_class = "mm123",
		$highlighted_style = "font-style:italic;", $shopper_group_id = 0)
	{
		$objhelper              = new redhelper;
		$parent_selected        = $params->get('redshop_category', '');
		$parent_selected_remove = $params->get('redshop_category_remove', '');

		$categories = $this->getCategoryTreeArray($only_published = 1, $keyword = "", $shopper_group_id, $parent_selected_remove);

		// Sort array of category objects
		$result       = $this->sortCategoryTreeArray($categories, $parent_selected);
		$row_list     = $result['row_list'];
		$depth_list   = $result['depth_list'];
		$category_tmp = $result['category_tmp'];
		$nrows        = sizeof($category_tmp);

		// Copy the Array into an Array with auto_incrementing Indexes
		// Array of category table primary keys
		$key = array_keys($categories);

		// Category count
		$nrows = $size = sizeOf($key);

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

		$depth = max($depth_list);

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
			$sub    = 0;

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
						$sub = $depth_list[$n];
					}

					if ($depth_list[$n] < ($sub))
					{
						for ($i = $depth_list[$n]; $i < $sub; $i++)
						{
							$html .= '</ul></li>';
						}

						$sub = $depth_list[$n];
					}
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

				$Itemid = $objhelper->getCategoryItemid($category_tmp[$row_list[$n]]["category_child_id"]);

				if (!$Itemid)
				{
					$Itemid = JRequest::getInt('Itemid');
				}

				$uri = JURI::getInstance();
				$url = $uri->root();

				$catlink = 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $category_tmp[$row_list[$n]]["category_child_id"] . $append . '&Itemid=' . $Itemid;
				$html .= '<li ' . $class . ' ><a title="' . $catname . '" style="display:block;' . $style . '" class="' . $css_class . '" href=' . JRoute::_($catlink) . '>'
					. str_repeat("", $depth_list[$n]) . $catname
					. $this->products_in_category($category_tmp[$row_list[$n]]["category_child_id"], $params)
					. '</a>';

				if ($n == ($nrows - 1))
				{
					$html .= "</ul>";
				}
			}
		}

		return $html;
	}

	function get_shoppergroup_cat($shopper_group_id)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT shopper_group_categories  FROM #__redshop_shopper_group "
			. "WHERE shopper_group_id=" . (int) $shopper_group_id;
		$db->setQuery($query);
		$cat_id_arr = $db->loadColumn();

		return $cat_id_arr;
	}
}
