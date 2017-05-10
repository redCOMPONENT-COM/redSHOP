<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Category
 *
 * @since  1.5
 */
class RedshopHelperCategory
{
	protected static $categoryListReverse = array();

	protected static $categoryChildListReverse = array();

	/**
	 * Get category data
	 *
	 * @param   int  $cid  Category id
	 *
	 * @return  mixed
	 *
	 * @deprecated  2.0.6  Use RedshopEntityCategory instead
	 */
	public static function getCategoryById($cid)
	{
		return RedshopEntityCategory::getInstance($cid)->getItem();
	}

	/**
	 * Get Category List Reverse Array
	 *
	 * @param   string  $cid  Category id
	 *
	 * @return array
	 */
	public static function getCategoryListReverseArray($cid = '0')
	{
		self::$categoryListReverse = array();

		if ($category = self::getCategoryById($cid))
		{
			if (isset($category->parent_id))
			{
				self::getCategoryListRecursion($category->parent_id);
			}
		}

		return self::$categoryListReverse;
	}

	/**
	 * Get Category List Recursion
	 *
	 * @param   string  $cid  Category id
	 *
	 * @return void
	 */
	private static function getCategoryListRecursion($cid = '0')
	{
		if ($category = self::getCategoryById($cid))
		{
			if (isset($category->parent_id))
			{
				self::$categoryListReverse[] = $category;
				self::getCategoryListRecursion($category->parent_id);
			}
		}
	}

	/**
	 * Get Category List Array
	 *
	 * @param   int  $categoryId  First category level in filter
	 * @param   int  $cid         Current category id
	 *
	 * @return   array|mixed
	 */
	public static function getCategoryListArray($categoryId = 1, $cid = 1)
	{
		global $context;
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$view = $app->input->getCmd('view', '');
		$categoryMainFilter = $app->getUserStateFromRequest($context . 'category_main_filter', 'category_main_filter', 0);

		if ($categoryId)
		{
			$cid = $categoryId;
		}

		$key = $context . '_' . $view . '_' . $categoryMainFilter . '_' . $cid;

		if (array_key_exists($key, static::$categoryChildListReverse))
		{
			return static::$categoryChildListReverse[$key];
		}

		$query = $db->getQuery(true)
			->select(
				$db->qn(
					array(
						'id', 'parent_id', 'name', 'description',
						'published', 'ordering', 'category_full_image'
					)
				)
			)
			->from($db->qn('#__redshop_category'))
			->where($db->qn('parent_id') . ' != 0')
			->where($db->qn('level') . ' != 0');

		if ($view == 'category')
		{
			$filter_order = urldecode($app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering'));
			$filter_order_Dir = urldecode($app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', ''));
			$query->order($db->escape($filter_order . ' ' . $filter_order_Dir));
		}
		else
		{
			$query->order($db->qn('name'));
		}

		if ($categoryMainFilter)
		{
			$query->where($db->qn('name') . ' LIKE ' . $db->q('%' . $categoryMainFilter . '%'));
		}
		else
		{
			$query->where($db->qn('parent_id') . ' = ' . (int) $cid);
		}

		static::$categoryChildListReverse[$key] = null;

		if ($cats = $db->setQuery($query)->loadObjectList())
		{
			if ($categoryMainFilter)
			{
				static::$categoryChildListReverse[$key] = $cats;

				return $cats;
			}

			static::$categoryChildListReverse[$key] = array();

			foreach ($cats as $cat)
			{
				$cat->name = '- ' . $cat->name;
				static::$categoryChildListReverse[$key][] = $cat;
				self::getCategoryChildListRecursion($key, $cat->id);
			}
		}

		return self::$categoryChildListReverse[$key];
	}

	/**
	 * Get Category Child List Recursion
	 *
	 * @param   string  $key    Key in array Child List
	 * @param   int     $cid    Category id
	 * @param   int     $level  Level current category
	 *
	 * @return  void
	 */
	protected static function getCategoryChildListRecursion($key, $cid, $level = 1)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select(
				$db->qn(
					array(
						'id', 'parent_id', 'name', 'description',
						'published', 'ordering', 'category_full_image'
					)
				)
			)
			->from($db->qn('#__redshop_category'))
			->where('parent_id = ' . (int) $cid);
		$level++;

		if ($cats = $db->setQuery($query)->loadObjectList())
		{
			foreach ($cats as $cat)
			{
				$cat->name = str_repeat('- ', $level) . $cat->name;
				static::$categoryChildListReverse[$key][] = $cat;
				self::getCategoryChildListRecursion($key, $cat->id, $level);
			}
		}
	}

	/**
	 * List all categories and return HTML format
	 *
	 * @param   string   $name                Name of list
	 * @param   integer  $categoryId          Only category to show
	 * @param   array    $selectedCategories  Only select categories from this
	 * @param   integer  $size                Size of dropdown
	 * @param   boolean  $topLevel            Add option '-Top-'
	 * @param   boolean  $multiple            Dropdown is multiple or not
	 * @param   array    $disabledFields      Fields need to be disabled
	 * @param   integer  $width               Width in pixel
	 *
	 * @return  string   HTML of dropdown
	 *
	 * @since  2.0.0.3
	 */
	public static function listAll($name, $categoryId, $selectedCategories = array(), $size = 1, $topLevel = false,
	                               $multiple = false, $disabledFields = array(), $width = 250)
	{
		$db    = JFactory::getDbo();
		$html  = '';
		$query = $db->getQuery(true)
			->select($db->qn('parent_id'))
			->from($db->qn('#__redshop_category'));

		if ($categoryId)
		{
			$query->where($db->qn('id') . ' = ' . $db->q((int) $categoryId));
		}

		$db->setQuery($query);
		$cats = $db->loadObjectList();

		if ($cats)
		{
			$selectedCategories[] = $cats[0]->parent_id;
		}

		$multiple = $multiple ? "multiple=\"multiple\"" : "";
		$id       = str_replace('[]', '', $name);
		$html     .= "<select class=\"inputbox\" style=\"width: " . $width . "px;\" size=\"$size\" $multiple name=\"$name\" id=\"$id\">\n";

		if ($topLevel)
		{
			$html .= "<option value=\"0\"> -Top- </option>\n";
		}

		$html .= self::listTree($categoryId, '0', '0', $selectedCategories, $disabledFields);
		$html .= "</select>\n";

		return $html;
	}

	/**
	 * List children of category into dropdown with level,
	 * this is a function will be called resursively.
	 *
	 * @param   string  $categoryId          Exclude this category ID
	 * @param   string  $cid                 Parent category ID
	 * @param   string  $level               Default is 0
	 * @param   array   $selectedCategories  Only show selected categories
	 * @param   array   $disabledFields      Didable fields
	 * @param   string  $html                Before HTML
	 *
	 * @return String   HTML of <option></option>
	 *
	 * @since  2.0.0.3
	 */
	public static function listTree($categoryId = "", $cid = '0', $level = '0', $selectedCategories = array(),
	                                $disabledFields = array(), $html = '')
	{
		$db = JFactory::getDbo();
		$level++;

		$query = $db->getQuery(true)
			->select($db->qn(array('id', 'name')))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('id') . ' != ' . $db->q((int) $categoryId))
			->where($db->qn('level') . ' > 0')
			->order($db->qn('name') . ' ASC');

		if ($cid > 0)
		{
			$query->where($db->qn('parent_id') . ' = ' . $db->q((int) $cid));
		}

		$db->setQuery($query);
		$cats = $db->loadObjectList();

		for ($x = 0, $xn = count($cats); $x < $xn; $x++)
		{
			$cat = $cats[$x];
			$childId = $cat->id;

			if ($childId != $cid)
			{
				$selected = ($childId == $categoryId) ? "selected=\"selected\"" : "";

				if ($selected == "" && @$selectedCategories[$childId] == "1")
				{
					$selected = "selected=\"selected\"";
				}

				if (is_array($selectedCategories))
				{
					if (in_array($childId, $selectedCategories))
					{
						$selected = "selected=\"selected\"";
					}
				}

				$disabled = '';

				if (in_array($childId, $disabledFields))
				{
					$disabled = 'disabled="disabled"';
				}

				if ($disabled != '' && stristr($_SERVER['HTTP_USER_AGENT'], 'msie'))
				{
					// IE7 suffers from a bug, which makes disabled option fields selectable
				}
				else
				{
					$html .= "<option $selected $disabled value=\"$childId\">" . str_repeat('- ', $level) . $cat->name . "</option>";
				}
			}

			$html .= self::listTree($categoryId, $childId, $level, $selectedCategories, $disabledFields);
		}

		return $html;
	}

	/**
	 * Build content order by user state from request
	 *
	 * @return string
	 *
	 * @since  2.0.0.3
	 */
	public static function buildContentOrderBy()
	{
		$db = JFactory::getDbo();
		global $context;
		$app = JFactory::getApplication();

		$filterOrder = urldecode($app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering'));
		$filterOrderDir = urldecode($app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', ''));

		$orderBy = ' ORDER BY ' . $db->escape($filterOrder . ' ' . $filterOrderDir);

		return $orderBy;
	}

	/**
	 * Get root parent categories
	 *
	 * @return object
	 *
	 * @since  2.0.0.3
	 */
	public static function getParentCategories()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('DISTINCT ' . $db->qn('name'))
			->select($db->qn('id'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('level') . ' = 1');

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get category tree
	 *
	 * @param   string  $cid  Category ID
	 *
	 * @return  array
	 *
	 * @since  2.0.0.3
	 */
	public static function getCategoryTree($cid = '0')
	{
		if (!isset($GLOBALS['catlist']))
		{
			$GLOBALS['catlist'] = array();
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn(array('id', 'name', 'parent_id')))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('parent_id') . ' = ' . $db->q((int) $cid));

		$db->setQuery($query);

		$cats = $db->loadObjectList();

		for ($x = 0, $xn = count($cats); $x < $xn; $x++)
		{
			$cat      = $cats[$x];
			$parentId = $cat->id;
			$GLOBALS['catlist'][] = $cat;
			self::getCategoryTree($parentId);
		}

		return $GLOBALS['catlist'];
	}

	/**
	 * Get category product list
	 *
	 * @param   string  $cid  Category ID
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 *
	 * @deprecated  2.0.6  Use RedshopEntityCategory::getProducts() instead.
	 */
	public static function getCategoryProductList($cid)
	{
		return RedshopEntityCategory::getInstance($cid)->getProducts();
	}

	/**
	 * get Root ID
	 *
	 * @return integer
	 *
	 * @since  2.0.5
	 */
	public static function getRootId()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('name') . ' = ' . $db->q('ROOT'))
			->where($db->qn('parent_id') . ' = 0')
			->where($db->qn('level') . ' = 0');

		return $db->setQuery($query)->loadResult();
	}
}
