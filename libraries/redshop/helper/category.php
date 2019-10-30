<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 *
	 * @throws  Exception
	 */
	public static function getCategoryListArray($categoryId = null, $cid = null)
	{
		global $context;

		$app  = JFactory::getApplication();
		$db   = JFactory::getDbo();
		$view = $app->input->getCmd('view', '');

		$categoryMainFilter = $app->getUserStateFromRequest($context . 'category_main_filter', 'category_main_filter', 0);

		if ($categoryId)
		{
			$cid = (int) $categoryId;
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
			->where($db->qn('level') . ' > 0')
			->where($db->qn('published') . ' = 1')
			->order($db->qn('lft'));

		if ($view == 'category')
		{
			$filter_order     = urldecode($app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering'));
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
			if ($cid !== null)
			{
				$query->where($db->qn('parent_id') . ' = ' . (int) $cid);
			}
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
		$db    = JFactory::getDbo();
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

		$cats = $db->setQuery($query)->loadObjectList();

		if (!empty($cats))
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
	 * @since   2.0.0.3
	 *
	 * @throws  Exception
	 */
	public static function listAll($name, $categoryId, $selectedCategories = array(), $size = 1, $topLevel = false,
	                               $multiple = false, $disabledFields = array(), $width = 250)
	{
		$db    = JFactory::getDbo();
		$html  = '';
		$query = $db->getQuery(true)
			->select($db->qn('parent_id'))
			->from($db->qn('#__redshop_category'))
			->order($db->qn('lft'));

		if ($categoryId)
		{
			$query->where($db->qn('id') . ' = ' . $db->q((int) $categoryId));
		}

		// Categories nested
		$query->where($db->qn('level') . ' > 0');

		$db->setQuery($query);
		$cats = $db->loadObjectList();

		if ($cats && Redshop::getConfig()->getBool('PRODUCT_DEFAULT_CATEGORY'))
		{
			$selectedCategories[] = $cats[0]->parent_id;
		}

		$multiple = $multiple ? "multiple=\"multiple\"" : "";
		$id       = str_replace('[]', '', $name);
		$html    .= "<select class=\"inputbox form-control\" style=\"width: " . $width . "px;\" size=\"$size\" $multiple name=\"$name\" id=\"$id\">\n";

		if ($topLevel)
		{
			$html .= "<option value=\"0\"> -Top- </option>\n";
		}

		$html .= self::listTree($selectedCategories, $disabledFields);
		$html .= "</select>\n";

		return $html;
	}

	/**
	 * List children of category into dropdown with level,
	 * this is a function will be called recursively.
	 *
	 * @param   array   $selectedCategories  Only show selected categories
	 * @param   array   $disabledFields      Disable fields
	 *
	 * @return  string                       HTML of <option></option>
	 *
	 * @since   2.0.0.3
	 *
	 * @throws  Exception
	 */
	public static function listTree($selectedCategories = array(), $disabledFields = array())
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('DISTINCT a.id AS value, a.name AS text, a.level, a.published, a.lft');

		$subQuery = $db->getQuery(true)
			->select('id, name, level, published, parent_id, lft, rgt')
			->from('#__redshop_category');

		$query->from('(' . (string) $subQuery . ') AS a')
			->join('LEFT', $db->qn('#__redshop_category') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
			->where($db->qn('a.level') . ' > 0');
		$query->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $exception)
		{
			throw new Exception($exception->getMessage(), 500);
		}

		$html = "";

		foreach ($options as $key => $option)
		{
			// Pad the option text with spaces using depth level as a multiplier.
			if ($option->published == 1)
			{
				$option->text = str_repeat('- ', $option->level) . $option->text;
			}
			else
			{
				$option->text = str_repeat('- ', $option->level) . '[' . $option->text . ']';
			}

			$selected = '';
			$disabled = '';

			if (in_array($option->value, $selectedCategories))
			{
				$selected = ' selected="selected" ';
			}

			if (in_array($option->value, $disabledFields))
			{
				$disabled = ' disabled="disabled" ';
			}

			$html .= '<option ' . $selected . $disabled . ' value="' . $option->value . '">' . $option->text . '</option>';
		}

		return $html;
	}

	/**
	 * Build content order by user state from request
	 *
	 * @return string
	 *
	 * @since  2.0.0.3
	 *
	 * @throws  Exception
	 */
	public static function buildContentOrderBy()
	{
		$db = JFactory::getDbo();
		global $context;
		$app = JFactory::getApplication();

		$filterOrder    = urldecode($app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'ordering'));
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
			$cat                  = $cats[$x];
			$parentId             = $cat->id;
			$GLOBALS['catlist'][] = $cat;
			self::getCategoryTree($parentId);
		}

		return $GLOBALS['catlist'];
	}

	/**
	 * Get category product list
	 *
	 * @param   integer  $cid  Category ID
	 *
	 * @return  array
	 *
	 * @since   2.0.0.3
	 *
	 */
	public static function getCategoryProductList($cid, $includeProductsFromSubCat = false)
	{
		return RedshopEntityCategory::getInstance($cid)->getProducts($includeProductsFromSubCat);
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
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('name') . ' = ' . $db->q('ROOT'))
			->where($db->qn('parent_id') . ' = 0')
			->where($db->qn('level') . ' = 0');

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * buildQueryFilterProduct
	 *
	 * @param   integer $categoryId    id of main category
	 * @param   array   $allCategories array of all categories id (main category or main category & its subcategories)
	 * @param   array   $filter        Filter data
	 *
	 * @return  bool|JDatabaseQuery  $query   The result query
	 *
	 * @since   2.1.2
	 */
	public static function buildQueryFilterProduct($categoryId, $allCategories = array(), $filters)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT(p.product_id)')
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON ' . $db->qn('pc.product_id') . ' = ' . $db->qn('p.product_id'))
			->where($db->qn('p.published') . ' = 1')
			->where($db->qn('p.expired') . ' = 0')
			->where($db->qn('p.product_parent_id') . ' = 0')
			->group($db->qn('p.product_id'));

		/* query builder for category filters */
		if (empty($allCategories))
		{
			$allCategories = array($categoryId);
		}

		$query->where($db->qn('pc.category_id') . ' IN (' . implode(',', $allCategories) . ')');
		$filterCategory = $filters['category'];

		if (!empty($filterCategory))
		{
			$filterCategory = array_merge(array($categoryId), $filterCategory);
			$query->where($db->qn('pc.category_id') . ' IN (' . implode(',', $filterCategory) . ')');
		}

		/* query builder for manufacturer filters */
		$manufacturer = $filters['manufacturer'];

		if ((int) $filters['mid'] > 0)
		{
			$query->where($db->qn('p.manufacturer_id') . ' = ' . (int) $filters['mid']);
		}
		elseif (!empty($manufacturer))
		{
			$query->where($db->qn('p.manufacturer_id') . ' IN (' . implode(',', $manufacturer) . ')');
		}

		/* query builder for price range filters */
		$priceRange = $filters['filterprice'];

		if (!empty($priceRange))
		{
			$min                  = $priceRange['min'];
			$max                  = $priceRange['max'];
			$comparePrice         = $db->qn('p.product_price') . ' >= ' . $db->q($min) . ' AND ' . $db->qn('p.product_price') . ' <= ' . $db->q(($max));
			$compareDiscountPrice = $db->qn('p.discount_price') . ' >= ' . $db->q($min) . ' AND ' . $db->qn('p.discount_price') . ' <= ' . $db->q(($max));
			$saleTime             = $db->qn('p.discount_stratdate') . ' AND ' . $db->qn('p.discount_enddate');
			$query->where('( CASE WHEN( ' . $db->qn('p.product_on_sale') . ' = 1 AND UNIX_TIMESTAMP() BETWEEN '
				. $saleTime . ') THEN ('
				. $compareDiscountPrice . ') ELSE ('
				. $comparePrice . ') END )'
			);
		}

		/* query builder for attributes */
		$attribute = $filters['attribute_name'];

		foreach ($attribute as $key => $value)
		{
			$query->leftJoin($db->qn('#__redshop_product_attribute', 'a' . $key)
				. ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a' . $key . '.product_id'))
				->where($db->qn('a' . $key . '.attribute_name') . ' = ' . $db->q($key));

			if (empty($value['property']))
			{
				continue;
			}

			$query->leftJoin($db->qn('#__redshop_product_attribute_property', 'ap' . $key)
				. ' ON ' . $db->qn('a' . $key . '.attribute_id') . ' = ' . $db->qn('ap' . $key . '.attribute_id'))
				->where($db->qn('ap' . $key . '.property_name') . ' IN ("' . implode('","', $value['property']) . '")');
		}

		/* query builder for product's custom fields */
		$customField = $filters['custom_field'];
		$key         = 0;
		$subQuery    = array();

		foreach ($customField as $fieldId => $fieldValues)
		{
			if (empty($fieldValues))
			{
				continue;
			}

			foreach ($fieldValues as $value)
			{
				$subQuery[] = 'FIND_IN_SET("' . $value . '", ' . $db->qn('fd' . $key . '.data_txt') . ')';
			}

			$query->leftJoin($db->qn('#__redshop_fields_data', 'fd' . $key) . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('fd' . $key . '.itemid'))
				->where($db->qn('fd' . $key . '.fieldid') . ' = ' . $db->q((int) $fieldId));
			$key++;
		}

		if (!empty($subQuery))
		{
			$query->where('(' . implode(' OR ', $subQuery) . ')');
		}

		return $query;
	}
}
