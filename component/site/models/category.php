<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

JLoader::import('joomla.application.component.model');

/**
 * Class categoryModelcategory
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class CategoryModelCategory extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_product = null;

	public $_table_prefix = null;

	public $_template = null;

	public $_limit = null;

	public $_slidercount = 0;

	public $count_no_user_field = 0;

	public $minmaxArr = array(0, 0);

	public $_context = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$app = JFactory::getApplication();
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$this->producthelper = new producthelper;

		$params = $app->getParams('com_redshop');
		$layout = JRequest::getVar('layout');
		$print  = JRequest::getVar('print');
		$Id     = JRequest::getInt('cid', 0);

		if (!$print)
		{
			if (!$Id && $layout != '')
			{
				$Id = (int) $params->get('cid');
			}
		}

		$category_template = $app->getUserStateFromRequest($this->_context . 'category_template', 'category_template', 0);

		$this->setState('category_template', $category_template);

		$this->setId((int) $Id);
	}

	public function setId($id)
	{
		$this->_id   = $id;
		$this->_data = null;
	}

	public function _buildQuery()
	{
		$app = JFactory::getApplication();
		$menu            = $app->getMenu();
		$item            = $menu->getActive();
		$manufacturer_id = (isset($item)) ? intval($item->params->get('manufacturer_id')) : 0;
		$manufacturer_id = JRequest::getInt('manufacturer_id', $manufacturer_id, '', 'int');

		$layout  = JRequest::getVar('layout');
		$orderby = ($layout != "categoryproduct") ? $this->_buildContentOrderBy() : "";
		$groupby = $and = $left = "";

		if ($manufacturer_id)
		{
			$left    = "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS pcx ON pcx.category_id = c.category_id "
				. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = pcx.product_id "
				. "LEFT JOIN " . $this->_table_prefix . "manufacturer AS m ON m.manufacturer_id = p.manufacturer_id ";
			$and     = "AND m.manufacturer_id='" . $manufacturer_id . "' ";
			$groupby = "GROUP BY c.category_id ";
		}

		$query = "SELECT c.* FROM " . $this->_table_prefix . "category AS c "
			. "LEFT JOIN " . $this->_table_prefix . "category_xref AS cx ON cx.category_child_id=c.category_id "
			. $left
			. "WHERE c.published = 1 "
			. "AND cx.category_parent_id='" . $this->_id . "' "
			. $and
			. $groupby
			. $orderby;

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();

		if (DEFAULT_CATEGORY_ORDERING_METHOD)
		{
			$orderby = " ORDER BY " . DEFAULT_CATEGORY_ORDERING_METHOD;
		}
		else
		{
			$orderby = " ORDER BY c.ordering";
		}

		return $orderby;
	}

	public function _loadCategory()
	{
		$this->_maincat = array();

		if ($this->_id)
		{
			$maincat = $this->getTable('category_detail');
			$maincat->load($this->_id);
			$this->_maincat = $maincat;
		}

		return $this->_maincat;
	}

	public function getProductPerPage()
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();

		if (isset($this->_template[0]->template_desc) && !strstr($this->_template[0]->template_desc, "{show_all_products_in_category}") && strstr($this->_template[0]->template_desc, "{pagination}") && strstr($this->_template[0]->template_desc, "perpagelimit:"))
		{
			$perpage = explode('{perpagelimit:', $this->_template[0]->template_desc);
			$perpage = explode('}', $perpage[1]);
			$limit   = intval($perpage[0]);
		}
		else
		{
			if ($this->_id)
			{
				$limit = (isset($item)) ? intval($item->params->get('maxproduct')) : 0;

				if ($limit == 0)
				{
					$limit = $this->_maincat->products_per_page;
				}
			}
			else
			{
				$limit = MAXCATEGORY;
			}
		}

		if (strstr($this->_template[0]->template_desc, "{product_display_limit}"))
		{
			$endlimit = JRequest::getInt('limit', 0, '', 'int');
		}

		return $limit;
	}

	public function getCategorylistProduct($category_id = 0)
	{
		$app   = JFactory::getApplication();
		$menu  = $app->getMenu();
		$item  = $menu->getActive();
		$limit = (isset($item)) ? intval($item->params->get('maxproduct')) : 0;

		// $order_by = $this->_buildProductOrderBy();
		$order_by = (isset($item)) ? $item->params->get('order_by', 'p.product_name ASC') : 'p.product_name ASC';

		$query = "SELECT * FROM " . $this->_table_prefix . "product AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS pc ON pc.product_id=p.product_id "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON c.category_id=pc.category_id "
			. "LEFT JOIN " . $this->_table_prefix . "manufacturer AS m ON m.manufacturer_id=p.manufacturer_id "
			. "WHERE p.published = 1 AND p.expired = 0 "
			. "AND pc.category_id='" . $category_id . "' "
			. "AND p.product_parent_id = 0  order by "
			. $order_by . " LIMIT 0," . $limit;

		$this->_product = $this->_getList($query);

		return $this->_product;
	}

	public function getCategoryProduct($minmax = 0, $isSlider = false)
	{
		$app             = JFactory::getApplication();
		$menu            = $app->getMenu();
		$item            = $menu->getActive();
		$manufacturer_id = (isset($item)) ? intval($item->params->get('manufacturer_id')) : 0;

		$setproductfinderobj = new redhelper;
		$order_by            = $this->_buildProductOrderBy();
		$manufacturer_id     = JRequest::getInt('manufacturer_id', $manufacturer_id, '', 'int');

		$sort = "";
		$and  = "";

		// Shopper group - choose from manufactures Start
		$rsUserhelper               = new rsUserhelper;
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			$and .= " AND p.manufacturer_id IN (" . $shopper_group_manufactures . ") ";
		}

		// Shopper group - choose from manufactures End

		if ($manufacturer_id && $manufacturer_id > 0)
		{
			$and .= " AND p.manufacturer_id='" . $manufacturer_id . "' ";
		}

		if ($minmax && !(strstr($order_by, "p.product_price ASC") || strstr($order_by, "p.product_price DESC")))
		{
			$order_by = " ORDER BY p.product_price ASC";
		}

		$finder_condition = $this->getredproductfindertags();

		$query = "SELECT * FROM " . $this->_table_prefix . "product AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS pc ON pc.product_id=p.product_id "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON c.category_id=pc.category_id "
			. "LEFT JOIN " . $this->_table_prefix . "manufacturer AS m ON m.manufacturer_id=p.manufacturer_id "
			. "WHERE p.published = 1 AND p.expired = 0 "
			. "AND pc.category_id='" . $this->_id . "' "
			. "AND p.product_parent_id = 0 "
			. $and . $finder_condition . $order_by;

		$this->_product = $this->_getList($query);

		$priceSort = false;

		if (strstr($order_by, "p.product_price ASC"))
		{
			$priceSort = true;

			for ($i = 0; $i < count($this->_product); $i++)
			{
				$ProductPriceArr                  = $this->producthelper->getProductNetPrice($this->_product[$i]->product_id);
				$this->_product[$i]->productPrice = $ProductPriceArr['product_price'];
			}

			$this->_product = $this->columnSort($this->_product, 'productPrice', 'ASC');
		}
		elseif (strstr($order_by, "p.product_price DESC"))
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

			$this->_product[0]->minprice = floor($min);
			$this->_product[0]->maxprice = ceil($max);
			$this->setMaxMinProductPrice(array(floor($min), ceil($max)));
		}
		elseif ($isSlider)
		{
			$newProduct = array();

			for ($i = 0; $i < count($this->_product); $i++)
			{
				$ProductPriceArr                 = $this->producthelper->getProductNetPrice($this->_product[$i]->product_id);
				$this->_product[$i]->sliderprice = $ProductPriceArr['product_price'];

				if ($this->_product[$i]->sliderprice >= $this->minmaxArr[0] && $this->_product[$i]->sliderprice <= $this->minmaxArr[1])
				{
					$newProduct[] = $this->_product[$i];
				}
			}

			$this->_product = $newProduct;
			$this->_total   = count($this->_product);
		}
		else
		{
			$this->_total = count($this->_product);
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

	public function _buildProductOrderBy()
	{
		$app      = JFactory::getApplication();
		$params   = $app->getParams("com_redshop");
		$menu     = $app->getMenu();
		$item     = $menu->getActive();
		$order_by = urldecode(JRequest::getVar('order_by', ''));

		if ($order_by == '')
		{
			$order_by = (isset($item)) ? $item->params->get('order_by', 'p.product_name ASC') : DEFAULT_PRODUCT_ORDERING_METHOD;
		}

		$orderby = " ORDER BY " . $order_by;

		return $orderby;
	}

	public function getData()
	{
		$app = JFactory::getApplication();

		global $context;

		$endlimit   = $this->getProductPerPage();
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		$layout     = JRequest::getVar('layout');
		$query      = $this->_buildQuery();

		if ($layout == "categoryproduct")
		{
			$menu        = $app->getMenu();
			$item        = $menu->getActive();
			$endlimit    = (isset($item)) ? intval($item->params->get('maxcategory')) : 0;
			$limit       = $app->getUserStateFromRequest($context . 'limit', 'limit', $endlimit, 5);
			$limitstart  = JRequest::getVar('limitstart', 0, '', 'int');
			$this->_data = $this->_getList($query, $limitstart, $endlimit);

			return $this->_data;
		}

		if ($this->_id)
		{
			$this->_data = $this->_getList($query);
		}
		else
		{
			if (!strstr($this->_template[0]->template_desc, "{show_all_products_in_category}") && strstr($this->_template[0]->template_desc, "{pagination}"))
			{
				$this->_data = $this->_getList($query, $limitstart, $endlimit);
			}
			else
			{
				if (strstr($this->_template[0]->template_desc, "{show_all_products_in_category}"))
				{
					$this->_data = $this->_getList($query);
				}
				else
				{
					$this->_data = $this->_getList($query, 0, MAXCATEGORY);
				}
			}
		}

		return $this->_data;
	}

	public function getCategoryPagination()
	{
		$endlimit          = $this->getProductPerPage();
		$limitstart        = JRequest::getVar('limitstart', 0, '', 'int');
		$this->_pagination = new redPagination($this->getTotal(), $limitstart, $endlimit);

		return $this->_pagination;
	}

	public function getCategoryProductPagination()
	{
		$app = JFactory::getApplication();
		$menu     = $app->getMenu();
		$item     = $menu->getActive();
		$endlimit = (isset($item)) ? intval($item->params->get('maxcategory')) : 0;

		$limitstart        = JRequest::getVar('limitstart', 0, '', 'int');
		$this->_pagination = new redPagination($this->getTotal(), $limitstart, $endlimit);

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
		$app = JFactory::getApplication();

		$params            = $app->getParams('com_redshop');
		$category_template = $this->getState('category_template');

		$redTemplate = new Redtemplate;

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

	public function loadCategoryTemplate()
	{
		$app = JFactory::getApplication();

		$params            = $app->getParams('com_redshop');
		$category_template = (int) $this->getState('category_template');
		$redTemplate       = new Redtemplate;

		$selected_template = 0;
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
		else
		{
			$selected_template = DEFAULT_CATEGORYLIST_TEMPLATE;
		}

		$category_template_id = JRequest::getInt('category_template', $selected_template, '', 'int');
		$this->_template      = $redTemplate->getTemplate($template_section, $category_template_id);

		return $this->_template;
	}

	public function getManufacturer($mid = 0)
	{
		$and = "";
		$cid = JRequest::getVar('cid');

		if ($mid != 0)
		{
			$and = " AND m.manufacturer_id='" . $mid . "' ";
		}

		$query = "SELECT DISTINCT(m.manufacturer_id ),m.* FROM " . $this->_table_prefix . "manufacturer AS m "
			. "LEFT JOIN #__redshop_product AS p ON m.manufacturer_id  = p.manufacturer_id ";

		if ($cid != 0)
		{
			$query .= "LEFT JOIN #__redshop_product_category_xref AS pcx ON p.product_id  = pcx.product_id ";
			$and .= " AND pcx.category_id='" . $cid . "' ";
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
		$endlimit = $this->getProductPerPage();

		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		$query      = $this->_buildfletterQuery($letter, $fieldid);

		if (strstr($this->_template[0]->template_desc, "{pagination}"))
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
		$query = "SELECT p.*, fd.* FROM " . $this->_table_prefix . "product AS p ";
		$query .= " LEFT JOIN #__redshop_fields_data AS fd ON fd.itemid = p.product_id";
		$query .= " WHERE  fd.data_txt LIKE '$letter%' AND fd.fieldid='$fieldid'  AND  fd.section=1 AND p.published =1 ORDER BY product_name ";

		return $query;
	}

	public function getfletterPagination($letter, $fieldid)
	{
		$endlimit          = $this->getProductPerPage();
		$limitstart        = JRequest::getVar('limitstart', 0, '', 'int');
		$this->_pagination = new redPagination($this->getfletterTotal($letter, $fieldid), $limitstart, $endlimit);

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

		$setproductfinderobj = new redhelper;
		$setproductfinder    = $setproductfinderobj->isredProductfinder();
		$finder_condition    = "";

		if ($setproductfinder)
		{
			$query = "SELECT id FROM #__redproductfinder_filters WHERE published=1";
			$this->_db->setQuery($query);
			$rs_filters = $this->_db->loadResultArray();

			if (count($rs_filters) > 0)
			{
				$this->_is_filter_enable = true;
			}

			$tag = '';

			for ($f = 0; $f < count($rs_filters); $f++)
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
			$finder_condition = "";

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

							for ($t = 1; $t < count($tag); $t++)
							{
								$finder_query .= " LEFT JOIN #__redproductfinder_association_tag AS at" . $t . " ON at" . $t . ".association_id=at.association_id";
								$finder_where[] = " at" . $t . ".tag_id = '" . $tag[$t] . "'";
								$i++;
							}
						}

						$finder_query .= " WHERE a.id=at.association_id AND at.tag_id = '" . $tag[0] . "'";

						if (is_array($finder_where))
						{
							$finder_where = " AND " . implode(" AND ", $finder_where);
						}

						$finder_query .= $finder_where;
						$this->_db->setQuery($finder_query);
						$rs              = $this->_db->loadResultArray();
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

				$finder_condition;
			}
		}

		return $finder_condition;
	}
}
