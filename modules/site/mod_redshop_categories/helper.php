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

/**
 * Helper for mod_redshop_categories
 *
 * @since  1.5.3
 */

abstract class ModProMenuHelper
{
	/**
	 * hasChilds function
	 * 
	 * @param   int  $categoryId  ID of category
	 * 
	 * @return  boolean
	 */
	public static function hasChilds($categoryId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$sess = JFactory::getSession();
		$sessCatInfo = $sess->get('category_info');

		if (empty($sessCatInfo[$categoryId]['has_childs']))
		{
			$query->select($db->qn('category_child_id'))
				->from($db->qn('#__redshop_category_xref'))
				->where($db->qn('category_parent_id') . ' = ' . $db->q((int) $categoryId));

			$db->setQuery($query);

			if ($db->loadObjectList() > 0)
			{
				$sessCatInfo[$categoryId]['has_childs'] = true;
			}
			else
			{
				$sessCatInfo[$categoryId]['has_childs'] = false;
			}
		}

		$sess->set('category_info', $sessCatInfo);

		return $sessCatInfo[$categoryId]['has_childs'];
	}

	/**
	 * sortCategoryTreeArray function
	 * 
	 * @param   array  &$categoryArr    [description]
	 * @param   array  $parentSelected  [description]
	 * 
	 * @return  array
	 */
	public static function sortCategoryTreeArray(&$categoryArr, $parentSelected)
	{
		// Copy the Array into an Array with auto_incrementing Indexes
		// Array of category table primary keys
		$key = array_keys($categoryArr);

		// Category count
		$nrows = $size = count($key);

		/** FIRST STEP
		 * Order the Category Array and build a Tree of it
		 **/

		$ids    = array();
		$rows   = array();
		$depths = array();

		$parentIds      = array();
		$parentIdsHash = array();

		// Build an array of category references
		$categoryTmp = array();

		for ($i = 0; $i < $size; $i++)
		{
			$categoryTmp[$i] = $categoryArr[$key[$i]];
			$parentIds[$i]   = $categoryTmp[$i]['category_parent_id'];

			if ($categoryTmp[$i]["category_parent_id"] == $parentSelected)
			{
				array_push($ids, $categoryTmp[$i]["category_child_id"]);
				array_push($rows, $i);
				array_push($depths, 0);
			}

			$parentId = $parentIds[$i];

			if (isset($parentIdsHash[$parentId]))
			{
				$parentIdsHash[$parentId][$i] = $parentId;
			}
			else
			{
				$parentIdsHash[$parentId] = array($i => $parentId);
			}
		}

		$loop = 0;

		// Hash to store children
		$watch = array();

		while (count($ids) < $nrows)
		{
			if ($loop > $nrows)
			{
				break;
			}

			$idTemp    = array();
			$rowTemp   = array();
			$depthTemp = array();

			for ($i = 0, $countIdList = count($ids); $i < $countIdList; $i++)
			{
				$id    = $ids[$i];
				$row   = $rows[$i];
				$depth = $depths[$i];

				array_push($idTemp, $id);
				array_push($rowTemp, $row);
				array_push($depthTemp, $depth);

				if (isset($parentIdsHash[$id]))
				{
					$children = $parentIdsHash[$id];
				}
				else
				{
					$children = null;
				}

				if (!empty($children))
				{
					foreach ($children as $key => $value)
					{
						if (!isset($watch[$id][$categoryTmp[$key]["category_child_id"]]))
						{
							$watch[$id][$categoryTmp[$key]["category_child_id"]] = 1;

							array_push($idTemp, $categoryTmp[$key]["category_child_id"]);
							array_push($rowTemp, $key);
							array_push($depthTemp, $depth + 1);
						}
					}
				}
			}

			$ids    = $idTemp;
			$rows   = $rowTemp;
			$depths = $depthTemp;

			$loop++;
		}

		return array(
			'id_list'      => $ids,
			'row_list'     => $rows,
			'depth_list'   => $depths,
			'category_tmp' => $categoryTmp
		);
	}

	/**
	 * getCategoryTreeArray function
	 * 
	 * @param   integer  $onlyPublished         published or not
	 * @param   string   $keyword               keyword for search
	 * @param   integer  $shopperGroupId        id of shopper group
	 * @param   array    $parentSelectedRemove  selected parent to remove
	 * 
	 * @return  [type]
	 */
	public static function getCategoryTreeArray($onlyPublished = 1, $keyword = "", $shopperGroupId = '0', $parentSelectedRemove = [])
	{
		global $categorySortType;
		$db = JFactory::getDbo();
		$cid = JFactory::getApplication()->input->getInt('cid');

		$sess = JFactory::getSession();
		$sessCatInfo = $sess->get('category_info');

		if (empty($sessCatInfo['category_tree']))
		{
			// Get only published categories;
			$query = $db->getQuery(true)
				->select($db->qn('c.category_id'))
				->select($db->qn('c.category_description'))
				->select($db->qn('c.category_name'))
				->select($db->qn('c.ordering'))
				->select($db->qn('c.published'))
				->select($db->qn('ref.category_child_id', 'cid'))
				->select($db->qn('ref.category_parent_id', 'pid'))
				->from($db->qn('#__redshop_category', 'c'))
				->leftJoin($db->qn('#__redshop_category_xref', 'ref') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('ref.category_child_id'));

			// Only published
			if ($onlyPublished)
			{
				$query->where($db->qn('c.published') . ' = 1');
			}

			// Filter via Shopper Group
			if ($shopperGroupId)
			{
				$shopperGroupCat = self::getShopperGroupCat($shopperGroupId);

				if ($shopperGroupCat)
				{
					$query->where($db->qn('c.category_id') . ' IN (' . implode(',', $shopperGroupCat) . ')');
				}
			}

			// Filter by keyword
			if (!empty($keyword))
			{
				$query->where('(' . $db->qn('c.category_name') . ' LIKE ' . $db->quote('%' . $keyword . '%')
					. ' OR ' . $db->qn('c.category_description') . ' LIKE ' . $db->quote('%' . $keyword . '%') . ')');
			}

			if ($parentSelectedRemove)
			{
				$query->where($db->qn('c.category_id') . ' NOT IN (' . implode(',', $parentSelectedRemove) . ')');
			}

			if (!empty($cid))
			{
				$query->where($db->qn('ref.category_parent_id') . ' = ' . $db->q($cid));
			}

			switch ($categorySortType)
			{
				case "catnameasc":
					$query->order($db->qn('c.category_name') . ' ASC');
					break;
				case "catnamedesc":
					$query->order($db->qn('c.category_name') . ' DESC');
					break;
				case "newest":
					$query->order($db->qn('c.category_id') . ' DESC');
					break;
				case "catorder":
					$query->order($db->qn('c.ordering') . ' ASC');
					break;
				default:
					$query->order($db->qn('c.category_name') . ' ASC');
					break;
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
					$categories[$cat_result->cid]["category_name"]        = $cat_result->category_name;
					$categories[$cat_result->cid]["category_description"] = $cat_result->category_description;
					$categories[$cat_result->cid]["ordering"]             = $cat_result->ordering;
					$categories[$cat_result->cid]["published"]            = $cat_result->published;
				}
			}

			$sessCatInfo['category_tree'] = $categories;
			$sess->set('category_info', $sessCatInfo);

			return $categories;
		}
		else
		{
			return $sessCatInfo['category_tree'];
		}
	}

	/**
	 * productCount function
	 * 
	 * @param   int  $categoryId  [description]
	 * 
	 * @return  int
	 */
	public static function productCount($categoryId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$sess = JFactory::getSession();
		$sessCatInfo = $sess->get('category_info');

		if (!isset($sessCatInfo[$categoryId]['product_count']))
		{
			$query->select('COUNT(' . $db->qn('p.product_id') . ') AS ' . $db->qn('num_rows'))
				->from($db->qn('#__redshop_product', 'p'))
				->innerJoin($db->qn('#__redshop_product_category_xref', 'xf') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('xf.product_id'))
				->innerJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('xf.category_id'))
				->where($db->qn('p.published') . ' = 1')
				->where($db->qn('xf.category_id') . ' = ' . $db->q($categoryId));

			$db->setQuery($query);

			$noofrows = $db->loadObject();

			$sessCatInfo[$categoryId]['product_count'] = $noofrows->num_rows;
			$sess->set('category_info', $sessCatInfo);
		}

		return $sessCatInfo[$categoryId]['product_count'];
	}

	/**
	 * productsInCategory function
	 * 
	 * @param   int     $categoryId  Id of category
	 * @param   string  $params      Module params
	 * 
	 * @return  string
	 */
	public static function productsInCategory($categoryId, $params = '')
	{
		$db = JFactory::getDbo();

		$showCountProducts = $params->get('show_noofproducts', 'yes');

		if ($showCountProducts == 'yes')
		{
			$num = self::productCount($categoryId);

			if (empty($num) || self::hasChilds($categoryId))
			{
				$query = $db->getQuery(true);

				$query->select($db->qn('category_child_id'))
					->from($db->qn('#__redshop_category_xref'))
					->where($db->qn('category_parent_id') . ' = ' . $db->q((int) $categoryId));

				$db->setQuery($query);
				$catresults = $db->loadObjectList();

				foreach ($catresults as $catresult)
				{
					$childProductId = $catresult->category_child_id;
					$num += self::productCount($childProductId);
				}
			}

			return " ($num) ";
		}
		else
		{
			return ("");
		}
	}

	/**
	 * [get_category_tree description]
	 * 
	 * @param   array    $params            [description]
	 * @param   integer  $categoryId        [description]
	 * @param   string   $linksCssClass     [description]
	 * @param   string   $highlightedStyle  [description]
	 * @param   integer  $shopperGroupId    [description]
	 * 
	 * @return  html
	 */
	public static function getCategoryTree($params, $categoryId = 0, $linksCssClass = "mainlevel", $highlightedStyle = "font-style:italic;", $shopperGroupId = 0)
	{
		$objhelper            = redhelper::getInstance();
		$parentSelected       = $params->get('redshop_category', 0);
		$parentSelectedRemove = $params->get('redshop_category_remove', '');

		$categories = self::getCategoryTreeArray(1, "", $shopperGroupId, $parentSelectedRemove);

		// Sort array of category objects
		$result 	  = self::sortCategoryTreeArray($categories, $parentSelected);
		$rows  		  = $result['row_list'];
		$depths       = $result['depth_list'];
		$categoryTmp  = $result['category_tmp'];

		// Copy the Array into an Array with auto_incrementing Indexes
		// Array of category table primary keys
		$key = array_keys($categories);

		// Category count
		$nrows = count($key);

		$html = "";

		// Find out if we have subcategories to display
		$allowedSubcategories  = array();
		$root                  = array('category_child_id' => 0, 'category_parent_id' => 0);

		if (!empty($categories[$categoryId]["category_parent_id"]))
		{
			// Find the Root Category of this category
			$root                    = $categories[$categoryId];
			$allowedSubcategories[]  = $categories[$categoryId]["category_parent_id"];

			// Loop through the Tree up to the root
			while (!empty($root["category_parent_id"]))
			{
				$allowedSubcategories[] = $categories[$root["category_child_id"]]["category_child_id"];

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
		if ($nrows < count($rows))
		{
			$nrows = count($rows);
		}

		if (count($depths) > 0)
		{
			$depth = max($depths);
		}
		else
		{
			$depth = 0;
		}

		$sub = 0;

		// Now show the categories
		for ($n = 0; $n < $nrows; $n++)
		{
			if (!isset($rows[$n]) || !isset($categoryTmp[$rows[$n]]["category_child_id"]))
			{
				continue;
			}

			if ($categoryId == $categoryTmp[$rows[$n]]["category_child_id"] || $categoryTmp[$rows[$n]]["category_child_id"] == $root["category_child_id"]
				|| in_array($categoryTmp[$rows[$n]]["category_child_id"], $allowedSubcategories))
			{
				$style = $highlightedStyle;
			}
			else
			{
				$style = "";
			}

			$allowed = false;

			if ($depths[$n] > 0)
			{
				// Subcategory!
				if ((isset($root) && in_array($categoryTmp[$rows[$n]]["category_child_id"], $allowedSubcategories))
					|| $categoryTmp[$rows[$n]]["category_parent_id"] == $categoryId
					|| $categoryTmp[$rows[$n]]["category_parent_id"] == @$categories[$categoryId]["category_parent_id"]
					|| $categoryTmp[$rows[$n]]["category_parent_id"] == $root["category_child_id"])
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

				if ($style == $highlightedStyle)
				{
					$append = '&id=active_menu';
					$class  = "class='parent-active'";
				}

				if ($depths[$n] > 0)
				{
					$cssClass = "sublevel";

					if ($depth == $depths[$n] && $style == $highlightedStyle)
					{
						$class  = "class='active'";
					}

					if ($depths[$n] > ($sub))
					{
						$html .= '<ul>';
					}
					elseif ($depths[$n] < ($sub))
					{
						for ($i = $depths[$n]; $i < $sub; $i++)
						{
							$html .= '</ul></li>';
						}
					}

					$sub = $depths[$n];
				}
				else
				{
					$cssClass = $linksCssClass;

					if ($sub > 0)
					{
						$html .= str_repeat("</ul></li>", $sub);
						$sub = 0;
					}

					$html .= "</li>";
				}

				$catname = JText::_($categoryTmp[$rows[$n]]["category_name"]);

				$Itemid = $objhelper->getCategoryItemid($categoryTmp[$rows[$n]]["category_child_id"]);

				if (!$Itemid)
				{
					$Itemid = JRequest::getInt('Itemid');
				}

				$uri = JURI::getInstance();

				$catlink = 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $categoryTmp[$rows[$n]]["category_child_id"] . $append . '&Itemid=' . $Itemid;
				$html .= '<li' . ($class? ' ' . $class . ' ': '') . '><a title="' . $catname . '" style="display:block;' . $style . '" class="' . $cssClass . '" href=' . JRoute::_($catlink) . '>'
					. str_repeat("", $depths[$n]) . $catname
					. self::productsInCategory($categoryTmp[$rows[$n]]["category_child_id"], $params)
					. '</a>';

				if ($n == ($nrows - 1))
				{
					$html .= "</ul>";
				}
			}
		}

		return $html;
	}

	/**
	 * getShopperGroupCat function
	 * 
	 * @param   int  $shopperGroupId  ID of shopper group
	 * 
	 * @return  [type]                 [description]
	 */
	public static function getShopperGroupCat($shopperGroupId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn('shopper_group_categories'))
			->from($db->qn('#__redshop_shopper_group'))
			->where($db->qn('shopper_group_id') . ' = ' . $db->q((int) $shopperGroupId));

		$db->setQuery($query);
		$catIds = $db->loadResult();

		return $catIds;
	}

	/**
	 * getShopperGroupId description
	 * 
	 * @param   boolean  $useShopperGroup  use ShopperGroup or not
	 * @param   object   $user             User information
	 * 
	 * @return  int
	 */
	public static function getShopperGroupId($useShopperGroup, $user)
	{
		global $db;

		if ($useShopperGroup == "yes")
		{
			$shopperGroupId = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');

			if ($user->id)
			{
				$query = $db->getQuery(true);
				$query->clear();

				$query->select($db->qn('shopper_group_id'))
					->from($db->qn('#__redshop_users_info'))
					->where($db->qn('user_id') . ' = ' . $db->q($user->id));

				$db->setQuery($query);
				$getShopperGroupID = $db->loadResult();

				if ($getShopperGroupID)
				{
					$shopperGroupId = $getShopperGroupID;
				}
			}
		}
		else
		{
			$shopperGroupId = 0;
		}

		return $shopperGroupId;
	}
}
