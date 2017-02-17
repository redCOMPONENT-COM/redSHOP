<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class categoryModelcategory
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelCategory extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_product = null;

	public $_template = null;

	public $_limit = null;

	public $_slidercount = 0;

	public $_maincat = null;

	public $count_no_user_field = 0;

	public $minmaxArr = array(0, 0);

	// @ToDo In feature, when class Category extends RedshopModelList, replace filter_fields in constructor
	public $filter_fields = array(
		'p.product_name', 'product_name',
		'p.product_price', 'product_price',
		'p.product_price', 'product_price',
		'p.product_number', 'product_number',
		'p.product_id', 'product_id',
		'pc.ordering', 'ordering'
	);

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$app = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		$params = $app->getParams('com_redshop');
		$layout = $input->getCmd('layout', 'detail');
		$print  = $input->getCmd('print', '');
		$Id     = $input->getInt('cid', 0);

		if (!$print && !$Id)
		{
			$Id = (int) $params->get('cid');
		}

		// Different context depending on the view
		if (empty($this->context))
		{
			$this->context = strtolower('com_redshop.category.' . $this->getName() . '.' . $layout . '.' . $Id);
		}

		parent::__construct();
		$this->producthelper = productHelper::getInstance();

		$this->setId((int) $Id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = '', $direction = '')
	{
		$app = JFactory::getApplication();
		$params = $app->getParams('com_redshop');
		$selectedTemplate = Redshop::getConfig()->get('DEFAULT_CATEGORYLIST_TEMPLATE');
		$layout = $app->input->getCmd('layout', 'detail');

		if ($this->_id)
		{
			$selectedTemplate  = (int) $params->get('category_template', 0);
			$mainCat = $this->_loadCategory();

			if (!$selectedTemplate && isset($mainCat->category_template))
			{
				$selectedTemplate = $mainCat->category_template;
			}
		}

		$categoryTemplate = $app->getUserStateFromRequest($this->context . '.category_template', 'category_template', $selectedTemplate, 'int');
		$this->setState('category_template', $categoryTemplate);

		if ($_POST)
		{
			$manufacturerId = $app->input->post->getInt('manufacturer_id', 0);

			if ($manufacturerId != $app->getUserState($this->context . '.manufacturer_id', $app->input->get->getInt('manufacturer_id', 0)))
			{
				$app->redirect(
					JRoute::_(
						'index.php?option=com_redshop&view=category&layout=' . $layout . '&cid=' . $this->_id . '&manufacturer_id=' . $manufacturerId
						. '&Itemid=' . $app->input->getInt('Itemid', 0),
						true
					)
				);
			}
		}
		else
		{
			$manufacturerId = $app->input->getInt('manufacturer_id', 0);
			$app->setUserState($this->context . '.manufacturer_id', $manufacturerId);
		}

		$this->setState('manufacturer_id', $manufacturerId);

		// Get default ordering
		$orderBySelect = $params->get('order_by', Redshop::getConfig()->get('DEFAULT_PRODUCT_ORDERING_METHOD'));
		$editTimestamp = $params->get('editTimestamp', 0);
		$userTimestamp = $app->getUserState($this->context . '.editTimestamp', 0);

		if ($editTimestamp > $userTimestamp)
		{
			$app->setUserState($this->context . '.order_by', $orderBySelect);
		}

		$app->setUserState($this->context . '.editTimestamp', time());

		$orderByMethod = $app->getUserStateFromRequest($this->context . '.order_by', 'order_by', $orderBySelect);
		$orderBy       = redhelper::getInstance()->prepareOrderBy($orderByMethod);

		$this->setState('list.ordering', $orderBy->ordering);
		$this->setState('list.direction', $orderBy->direction);

		$this->loadCategoryTemplate($categoryTemplate);

		if (isset($this->_template[0]->template_desc)
			&& strstr($this->_template[0]->template_desc, "{show_all_products_in_category}"))
		{
			$limit = 0;
		}
		elseif (isset($this->_template[0]->template_desc)
			&& strpos($this->_template[0]->template_desc, "{show_all_products_in_category}") === false
			&& strpos($this->_template[0]->template_desc, "{pagination}") !== false
			&& strpos($this->_template[0]->template_desc, "perpagelimit:") !== false)
		{
			$perpage = explode('{perpagelimit:', $this->_template[0]->template_desc);
			$perpage = explode('}', $perpage[1]);
			$limit   = intval($perpage[0]);
		}
		else
		{
			$limit = 0;

			if ($this->_id)
			{
				$item = $app->getMenu()->getActive();

				if (isset($this->_template[0]->template_desc) && strstr($this->_template[0]->template_desc, "{product_display_limit}"))
				{
					$limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');
				}

				if (!$limit && $item)
				{
					$limit = (int) $item->params->get('maxproduct', 0);
				}

				if (!$limit)
				{
					$limit = $this->_maincat->products_per_page;
				}
			}

			if (!$limit)
			{
				$limit = Redshop::getConfig()->get('MAXCATEGORY');
			}
		}

		$this->setState('list.limit', $limit);
		$value = $app->input->get('limitstart', 0, 'int');
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);
	}

	public function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

	/**
	 * Build a query
	 *
	 * @return  JDatabaseQuery
	 */
	public function _buildQuery()
	{
		$db              = JFactory::getDbo();
		$app             = JFactory::getApplication();
		$menu            = $app->getMenu();
		$item            = $menu->getActive();
		$manufacturer_id = (isset($item)) ? intval($item->params->get('manufacturer_id')) : 0;
		$manufacturer_id = $app->input->getInt('manufacturer_id', $manufacturer_id, '', 'int');
		$layout          = $app->input->getCmd('layout');

		$query = $db->getQuery(true);
		$query->select(
				array(
					'DISTINCT(' . $db->qn('c.category_id') . ')',
					'c.*'
				)
			)
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_category_xref', 'cx') . ' ON ' . $db->qn('cx.category_child_id') . ' = ' . $db->qn('c.category_id'))
			->where($db->qn('c.published') . ' = 1')
			->where($db->qn('cx.category_parent_id') . ' = ' . (int) $this->_id);

		if ($layout != 'categoryproduct')
		{
			$query->order($this->_buildContentOrderBy());
		}

		if ($manufacturer_id)
		{
			$query->leftJoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON ' . $db->qn('pcx.category_id') . ' = ' . $db->qn('c.category_id'))
				->leftJoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id'))
				->leftJoin($db->qn('#__redshop_manufacturer', 'm') . ' ON ' . $db->qn('m.manufacturer_id') . ' = ' . $db->qn('p.manufacturer_id'))
				->where($db->qn('m.manufacturer_id') . ' = ' . (int) $manufacturer_id)
				->groupby($db->qn('c.category_id'));
		}

		return $query;
	}

	public function _buildContentOrderBy()
	{
		if (Redshop::getConfig()->get('DEFAULT_CATEGORY_ORDERING_METHOD'))
		{
			$orderby = Redshop::getConfig()->get('DEFAULT_CATEGORY_ORDERING_METHOD');
		}
		else
		{
			$orderby = "c.ordering";
		}

		return $orderby;
	}

	public function _loadCategory()
	{
		$this->_maincat = RedshopHelperCategory::getCategoryById($this->_id);

		return $this->_maincat;
	}

	public function getCategorylistProduct($category_id = 0)
	{
		$app   = JFactory::getApplication();
		$menu  = $app->getMenu();
		$item  = $menu->getActive();
		$limit = (isset($item)) ? intval($item->params->get('maxproduct')) : 0;

		$order_by = (isset($item)) ? $item->params->get('order_by', 'p.product_name ASC') : 'p.product_name ASC';

		$query = "SELECT * FROM #__redshop_product AS p "
			. "LEFT JOIN #__redshop_product_category_xref AS pc ON pc.product_id=p.product_id "
			. "LEFT JOIN #__redshop_category AS c ON c.category_id=pc.category_id "
			. "LEFT JOIN #__redshop_manufacturer AS m ON m.manufacturer_id=p.manufacturer_id "
			. "WHERE p.published = 1 AND p.expired = 0 "
			. "AND pc.category_id = " . (int) $category_id . " "
			. "AND p.product_parent_id = 0  order by "
			. $order_by . " LIMIT 0," . $limit;

		$this->_product = $this->_getList($query);

		return $this->_product;
	}

	/**
	 * Method get Product of Category
	 *
	 * @param   int   $minmax    default variable is 0
	 * @param   bool  $isSlider  default variable is false
	 *
	 * @return mixed
	 */
	public function getCategoryProduct($minmax = 0, $isSlider = false)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$orderBy = $this->buildProductOrderBy();

		if ($minmax && !(strpos($orderBy, "p.product_price ASC") !== false || strpos($orderBy, "p.product_price DESC") !== false))
		{
			$orderBy = "p.product_price ASC";
		}

		$query = $db->getQuery(true);

		$manufacturerId = $this->getState('manufacturer_id');
		$endlimit = $this->getState('list.limit');
		$limitstart = $this->getState('list.start');
		$sort = "";

		// Shopper group - choose from manufactures Start
		$rsUserhelper               = rsUserHelper::getInstance();
		$shopperGroupManufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopperGroupManufactures != "")
		{
			$shopperGroupManufactures = explode(',', $shopperGroupManufactures);
			JArrayHelper::toInteger($shopperGroupManufactures);
			$shopperGroupManufactures = implode(',', $shopperGroupManufactures);
			$query->where('p.manufacturer_id IN (' . $shopperGroupManufactures . ')');
		}

		// Shopper group - choose from manufactures End

		if ($manufacturerId && $manufacturerId > 0)
		{
			$query->where('p.manufacturer_id = ' . (int) $manufacturerId);
		}

		$query->select($db->qn('p.product_id'))
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON ' . $db->qn('pc.product_id') . ' = ' . $db->qn('p.product_id'))
			->where($db->qn('p.published') . ' = 1')
			->where($db->qn('p.expired') . ' = 0')
			->where($db->qn('p.product_parent_id') . ' = 0')
			->order($orderBy);

		$filterIncludeProductFromSubCat = $this->getState('include_sub_categories_products', false);
		$categories = array($this->_id);

		if ($filterIncludeProductFromSubCat === true)
		{
			$tmpCategories = RedshopHelperCategory::getCategoryTree($this->_id);

			if (!empty($tmpCategories))
			{
				foreach ($tmpCategories as $child)
				{
					$categories[] = $child->category_id;
				}
			}
		}

		$query->where($db->qn('pc.category_id') . ' IN (' . implode(',', $categories) . ')');

		$finder_condition = $this->getredproductfindertags();

		if ($finder_condition != '')
		{
			$finder_condition = str_replace("AND", "", $finder_condition);
			$query->where($finder_condition);
		}

		$queryCount = clone $query;
		$queryCount->clear('select')
			->select('COUNT(DISTINCT(p.product_id))');

		// First steep get product ids
		if ($minmax != 0 || $isSlider)
		{
			$db->setQuery($query);
		}
		else
		{
			$db->setQuery($query, $limitstart, $endlimit);
		}

		$this->_product = array();

		if ($productIds = $db->loadColumn())
		{
			// Third steep get all product relate info
			$query->clear()
				->where('p.product_id IN (' . implode(',', $productIds) . ')')
				->order('FIELD(p.product_id, ' . implode(',', $productIds) . ')');

			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select(
					array(
						'pc.ordering', 'c.*', 'm.*',
						'CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id'
					)
				)
				->leftJoin('#__redshop_category AS c ON c.category_id = pc.category_id')
				->leftJoin('#__redshop_manufacturer AS m ON m.manufacturer_id = p.manufacturer_id')
				->where('pc.category_id IN (' . implode(',', $categories) . ')');

			if ($products = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($products);
				$this->_product = array_values($products);
			}
		}

		$priceSort = false;

		if (strpos($orderBy, "p.product_price ASC") !== false)
		{
			$priceSort = true;

			for ($i = 0; $i < count($this->_product); $i++)
			{
				$ProductPriceArr                  = $this->producthelper->getProductNetPrice($this->_product[$i]->product_id);
				$this->_product[$i]->productPrice = $ProductPriceArr['product_price'];
			}

			$this->_product = $this->columnSort($this->_product, 'productPrice', 'ASC');
		}
		elseif (strpos($orderBy, "p.product_price DESC") !== false)
		{
			$priceSort = true;
			$sort      = "DESC";

			for ($i = 0; $i < count($this->_product); $i++)
			{
				$ProductPriceArr                  = $this->producthelper->getProductNetPrice($this->_product[$i]->product_id);
				$this->_product[$i]->productPrice = $ProductPriceArr['product_price'];
			}

			$this->_product = $this->columnSort($this->_product, 'productPrice', 'DESC');
		}

		if ($minmax > 0)
		{
			$min = 0;

			if (!empty($priceSort))
			{
				if ($sort == "DESC")
				{
					$max = $this->_product[0]->productPrice + 100;
					$min = $this->_product[count($this->_product) - 1]->productPrice;
				}
				else
				{
					$min = $this->_product[0]->productPrice;
					$max = $this->_product[count($this->_product) - 1]->productPrice + 100;
				}
			}
			else
			{
				$ProductPriceArr = $this->producthelper->getProductNetPrice($this->_product[0]->product_id);
				$min             = $ProductPriceArr['product_price'];
				$ProductPriceArr = $this->producthelper->getProductNetPrice($this->_product[count($this->_product) - 1]->product_id);
				$max             = $ProductPriceArr['product_price'];

				if ($min >= $max)
				{
					$min = $this->_product[0]->product_price;
					$max = $max + 100;
				}
			}

			$this->setState('minprice', floor($min));
			$this->setState('maxprice', ceil($max));
			$this->setMaxMinProductPrice(array(floor($min), ceil($max)));
		}
		elseif ($isSlider)
		{
			$newProduct = array();

			for ($i = 0, $cp = count($this->_product); $i < $cp; $i++)
			{
				$ProductPriceArr                 = $this->producthelper->getProductNetPrice($this->_product[$i]->product_id);
				$this->_product[$i]->sliderprice = $ProductPriceArr['product_price'];

				if ($this->_product[$i]->sliderprice >= $this->minmaxArr[0] && $this->_product[$i]->sliderprice <= $this->minmaxArr[1])
				{
					$newProduct[] = $this->_product[$i];
				}
			}

			$this->_total   = count($newProduct);
			$this->_product = array_slice($newProduct, $limitstart, $endlimit);
		}
		else
		{
			$db->setQuery($queryCount);
			$this->_total = $db->loadResult();
		}

		return $this->_product;
	}

	public function columnSort($unsorted, $column, $sort)
	{
		$sorted = $unsorted;

		if ($sort == "ASC")
		{
			for ($i = 0; $i < count($sorted) - 1; $i++)
			{
				for ($j = 0; $j < count($sorted) - 1 - $i; $j++)
				{
					if ($sorted[$j]->$column > $sorted[$j + 1]->$column)
					{
						$tmp            = $sorted[$j];
						$sorted[$j]     = $sorted[$j + 1];
						$sorted[$j + 1] = $tmp;
					}
				}
			}
		}
		else
		{
			for ($i = 0; $i < count($sorted) - 1; $i++)
			{
				for ($j = 0; $j < count($sorted) - 1 - $i; $j++)
				{
					if ($sorted[$j]->$column < $sorted[$j + 1]->$column)
					{
						$tmp            = $sorted[$j];
						$sorted[$j]     = $sorted[$j + 1];
						$sorted[$j + 1] = $tmp;
					}
				}
			}
		}

		return $sorted;
	}

	/**
	 * Method get string order by of product when choose category
	 *
	 * @return  string
	 */
	public function buildProductOrderBy()
	{
		$orderBy        = redhelper::getInstance()->prepareOrderBy(Redshop::getConfig()->get('DEFAULT_PRODUCT_ORDERING_METHOD'));
		$filterOrder    = $this->getState('list.ordering', $orderBy->ordering);
		$filterOrderDir = $this->getState('list.direction', $orderBy->direction);

		return JFactory::getDbo()->escape($filterOrder . ' ' . $filterOrderDir);
	}

	public function getData()
	{
		$app = JFactory::getApplication();
		$endlimit   = $this->getState('list.limit');
		$limitstart = $this->getState('list.start');
		$layout     = JRequest::getVar('layout');
		$query      = $this->_buildQuery();

		if ($layout == "categoryproduct")
		{
			$menu        = $app->getMenu();
			$item        = $menu->getActive();
			$endlimit    = (isset($item)) ? intval($item->params->get('maxcategory')) : 0;
			$this->_data = $this->_getList($query, $limitstart, $endlimit);

			return $this->_data;
		}

		if ($this->_id)
		{
			$this->_data = $this->_getList($query);
		}
		else
		{
			if (strpos($this->_template[0]->template_desc, "{show_all_products_in_category}") === false && strpos($this->_template[0]->template_desc, "{pagination}") !== false)
			{
				$this->_data = $this->_getList($query, $limitstart, $endlimit);
			}
			else
			{
				if (strpos($this->_template[0]->template_desc, "{show_all_products_in_category}") !== false)
				{
					$this->_data = $this->_getList($query);
				}
				else
				{
					$this->_data = $this->_getList($query, 0, Redshop::getConfig()->get('MAXCATEGORY'));
				}
			}
		}

		return $this->_data;
	}

	public function getCategoryPagination()
	{
		$endlimit          = $this->getState('list.limit');
		$limitstart        = $this->getState('list.start');
		$this->_pagination = new JPagination($this->getTotal(), $limitstart, $endlimit);

		return $this->_pagination;
	}

	public function getCategoryProductPagination()
	{
		$app = JFactory::getApplication();
		$menu     = $app->getMenu();
		$item     = $menu->getActive();
		$endlimit = (isset($item)) ? intval($item->params->get('maxcategory')) : 0;

		$limitstart        = $this->getState('list.start');
		$this->_pagination = new JPagination($this->getTotal(), $limitstart, $endlimit);

		return $this->_pagination;
	}

	public function getTotal()
	{
		$query        = $this->_buildQuery();
		$this->_total = $this->_getListCount($query);

		return $this->_total;
	}

	public function getCategoryTemplate()
	{
		$category_template = $this->getState('category_template');

		$redTemplate = Redtemplate::getInstance();

		if ($this->_id)
		{
			$selected_template = $this->_maincat->category_template;

			if (isset($category_template) && $category_template != '')
			{
				$selected_template .= "," . $category_template;
			}

			if ($this->_maincat->category_more_template != "")
			{
				$selected_template .= "," . $this->_maincat->category_more_template;
			}

			$alltemplate = $redTemplate->getTemplate("category", $selected_template);
		}
		else
		{
			$alltemplate = $redTemplate->getTemplate("frontpage_category");
		}

		return $alltemplate;
	}

	/**
	 * Load Category Template
	 *
	 * @param   int  $category_template  Category template id
	 *
	 * @return  array|null
	 */
	public function loadCategoryTemplate($category_template = null)
	{
		$redTemplate       = Redtemplate::getInstance();

		$selected_template = Redshop::getConfig()->get('DEFAULT_CATEGORYLIST_TEMPLATE');
		$template_section  = "frontpage_category";

		if ($this->_id)
		{
			$template_section = "category";

			if (isset($category_template) && $category_template != 0)
			{
				$selected_template = $category_template;
			}
			elseif (isset($this->_maincat->category_template))
			{
				$selected_template = $this->_maincat->category_template;
			}
		}

		$this->_template      = $redTemplate->getTemplate($template_section, $selected_template);

		return $this->_template;
	}

	public function getManufacturer($mid = 0)
	{
		$and = "";
		$cid = JRequest::getVar('cid');

		if ($mid != 0)
		{
			$and = " AND m.manufacturer_id = " . (int) $mid . " ";
		}

		$query = "SELECT DISTINCT(m.manufacturer_id ),m.* FROM #__redshop_manufacturer AS m "
			. "LEFT JOIN #__redshop_product AS p ON m.manufacturer_id  = p.manufacturer_id ";

		if ($cid != 0)
		{
			$query .= "LEFT JOIN #__redshop_product_category_xref AS pcx ON p.product_id  = pcx.product_id ";
			$and .= " AND pcx.category_id = " . (int) $cid . " ";
		}

		$query .= "WHERE p.manufacturer_id != 0 AND m.published = 1 " . $and . "ORDER BY m.ordering ASC";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function setMaxMinProductPrice($minmax = array(0, 0))
	{
		$this->minmaxArr = $minmax;
	}

	public function getMaxMinProductPrice()
	{
		return $this->minmaxArr;
	}

	/**
	 * Function to get Product List Array with searched Letter
	 *
	 * @return array
	 */
	public function getAllproductArrayListwithfirst($letter, $fieldid)
	{
		$endlimit = $this->getState('list.limit');

		$limitstart = $this->getState('list.start');
		$query      = $this->_buildfletterQuery($letter, $fieldid);

		if (strpos($this->_template[0]->template_desc, "{pagination}") !== false)
		{
			$product_lists = $this->_getList($query, $limitstart, $endlimit);
		}
		else
		{
			$product_lists = $this->_getList($query, $limitstart, $endlimit);
		}

		return $product_lists;
	}

	public function _buildfletterQuery($letter, $fieldid)
	{
		$db = JFactory::getDbo();
		$query = "SELECT p.*, fd.* FROM #__redshop_product AS p ";
		$query .= " LEFT JOIN #__redshop_fields_data AS fd ON fd.itemid = p.product_id";
		$query .= " WHERE  fd.data_txt LIKE " . $db->quote($letter . '%') . " AND fd.fieldid = "
			. (int) $fieldid . "  AND  fd.section=1 AND p.published =1 ORDER BY product_name ";

		return $query;
	}

	public function getfletterPagination($letter, $fieldid)
	{
		$endlimit          = $this->getState('list.limit');
		$limitstart        = $this->getState('list.start');
		$this->_pagination = new JPagination($this->getfletterTotal($letter, $fieldid), $limitstart, $endlimit);

		return $this->_pagination;
	}

	public function getfletterTotal($letter, $fieldid)
	{
		if (empty ($this->_total))
		{
			$query        = $this->_buildfletterQuery($letter, $fieldid);
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	public function getredproductfindertags()
	{
		global $context;

		$app = JFactory::getApplication();

		$setproductfinderobj = redhelper::getInstance();
		$setproductfinder    = $setproductfinderobj->isredProductfinder();
		$finder_condition    = "";

		if ($setproductfinder)
		{
			$query = "SELECT id FROM #__redproductfinder_filters WHERE published=1";
			$this->_db->setQuery($query);
			$rs_filters = $this->_db->loadColumn();

			if (count($rs_filters) > 0)
			{
				$this->_is_filter_enable = true;
			}

			$tag = '';

			for ($f = 0, $fn = count($rs_filters); $f < $fn; $f++)
			{
				$tmp_tag = $app->getUserStateFromRequest($context . 'tag' . $rs_filters[$f], 'tag' . $rs_filters[$f], '');

				if (is_array($tmp_tag))
				{
					$tag = $tmp_tag;
				}
				elseif ($tmp_tag != "" && $tmp_tag != "0")
				{
					$tag[] = $tmp_tag;
				}
			}

			$finder_where     = "";
			$finder_query     = "";

			$findercomponent      = JComponentHelper::getComponent('com_redproductfinder');
			$productfinderconfig  = new JRegistry($findercomponent->params);
			$finder_filter_option = $productfinderconfig->get('redshop_filter_option');

			if ($tag)
			{
				if (is_array($tag))
				{
					if (count($tag) > 1 || $tag[0] != 0)
					{
						$finder_query = "SELECT product_id FROM #__redproductfinder_associations AS a,#__redproductfinder_association_tag AS at ";
						$finder_where = "";

						if (count($tag) > 1)
						{
							$i = 1;

							for ($t = 1, $tn = count($tag); $t < $tn; $t++)
							{
								$finder_query .= " LEFT JOIN #__redproductfinder_association_tag AS at" . $t . " ON at" . $t . ".association_id=at.association_id";
								$finder_where[] = " at" . $t . ".tag_id = " . (int) $tag[$t] . " ";
								$i++;
							}
						}

						$finder_query .= " WHERE a.id = at.association_id AND at.tag_id = " . (int) $tag[0] . " ";

						if (is_array($finder_where))
						{
							$finder_where = " AND " . implode(" AND ", $finder_where);
						}

						$finder_query .= $finder_where;
						$this->_db->setQuery($finder_query);
						$rs              = $this->_db->loadColumn();
						$finder_products = "";

						if (!empty($rs))
						{
							// Sanitise ids
							JArrayHelper::toInteger($rs);

							$finder_products = implode("','", $rs);
						}

						$finder_condition        = " AND p.product_id IN('" . $finder_products . "')";
						$this->_is_filter_enable = true;
					}

					if (count($tag) == 1 && $tag[0] == 0)
					{
						$finder_condition = "";
					}
				}
			}
		}

		return $finder_condition;
	}
}
