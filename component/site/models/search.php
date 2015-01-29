<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



/**
 * Class searchModelsearch
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelSearch extends RedshopModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

	// @ToDo In feature, when class Search extends RedshopModelList, replace filter_fields in constructor
	public $filter_fields = array(
		'p.product_name ASC', 'product_name ASC',
		'p.product_price ASC', 'product_price ASC',
		'p.product_price DESC', 'product_price DESC',
		'p.product_number ASC', 'product_number ASC',
		'p.product_id DESC', 'product_id DESC',
		'pc.ordering ASC', 'ordering ASC'
	);

	public function __construct()
	{
		global $context;

		parent::__construct();

		$app = JFactory::getApplication();

		$context = 'search';

		$this->_table_prefix = '#__redshop_';
		$params              = $app->getParams('com_redshop');
		$menu                = $app->getMenu();
		$item                = $menu->getActive();

		$layout         = $app->getUserStateFromRequest($context . 'layout', 'layout', 'default');
		if ($module         = JModuleHelper::getModule('redshop_search'))
		{
			$module_params  = new JRegistry($module->params);
			$perpageproduct = $module_params->get('productperpage', 5);
		}
		else
		{
			$perpageproduct = 5;
		}

		if ($module)
		{
			$module_params  = new JRegistry($module->params);
			$perpageproduct = $module_params->get('productperpage', 5);
		}

		if ($layout == 'default')
		{
			$limit = $perpageproduct;
		}
		elseif ($layout == 'productonsale')
		{
			$limit = $params->get('productlimit', 5);
		}
		else
		{
			$limit = $params->get('maxcategory', 5);
		}

		$productlimit = 0;

		if (isset($item->query['productlimit']))
			$productlimit = $item->query['productlimit'];

		$limitstart = JRequest::getVar('limitstart', 0);
		$this->setState('productperpage', $perpageproduct);
		$this->setState('limit', $limit);
		$productlimit = $app->getUserStateFromRequest($context . 'productlimit', 'productlimit', $productlimit, 8);
		$this->setState('productlimit', $productlimit);
		$this->setState('limitstart', $limitstart);
	}

	public function getData()
	{
		$post = JRequest::get('POST');
		$db = JFactory::getDbo();
		$redTemplate = new Redtemplate;

		if (empty($this->_data))
		{
			$query = $this->_buildQuery($post);
			$template = $this->getCategoryTemplet();

			for ($i = 0; $i < count($template); $i++)
			{
				$template[$i]->template_desc = $redTemplate->readtemplateFile($template[$i]->template_section, $template[$i]->template_name);
			}

			if (count($template) > 0)
			{
				if (strstr($template[0]->template_desc, "{show_all_products_in_category}"))
				{
					$db->setQuery($query);
				}
				elseif (strstr($template[0]->template_desc, "{pagination}"))
				{
					if (strstr($template[0]->template_desc, "perpagelimit:"))
					{
						$perpage = explode('{perpagelimit:', $template[0]->template_desc);
						$perpage = explode('}', $perpage[1]);
						$limit   = intval($perpage[0]);
						$this->setState('limit', $limit);
					}

					if (strstr($template[0]->template_desc, "{product_display_limit}"))
					{
						$endlimit = $this->getProductPerPage();
						$limit    = JRequest::getInt('limit', $endlimit, '', 'int');
						$this->setState('limit', $limit);
					}

					$db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
				}
				elseif ($this->getState('productlimit') > 0)
				{
					$db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
				}
				else
				{
					$db->setQuery($query);
				}
			}
			else
			{
				$db->setQuery($query);
			}

			if ($productIds = $db->loadColumn())
			{
				// Third steep get all product relate info
				$query->clear()
					->where('p.product_id IN (' . implode(',', $productIds) . ')')
					->order('FIELD(p.product_id, ' . implode(',', $productIds) . ')');

				$user = JFactory::getUser();
				$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
					->select(
						array(
							'pc.*', 'c.*', 'm.*',
							'CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id'
						)
					)
					->leftJoin('#__redshop_category AS c ON c.category_id = pc.category_id')
					->leftJoin('#__redshop_manufacturer AS m ON m.manufacturer_id = p.manufacturer_id');

				if ($products = $db->setQuery($query)->loadObjectList('concat_id'))
				{
					RedshopHelperProduct::setProduct($products);
					$this->_data = array_values($products);
				}
			}
		}

		return $this->_data;
	}

	public function getProductPerPage()
	{
		$app = JFactory::getApplication();
		$redconfig   = $app->getParams();
		$redTemplate = new Redtemplate;
		$template    = $this->getCategoryTemplet();

		for ($i = 0; $i < count($template); $i++)
		{
			$template[$i]->template_desc = $redTemplate->readtemplateFile($template[$i]->template_section, $template[$i]->template_name);
		}

		if (isset($template[0]->template_desc) && !strstr($template[0]->template_desc, "{show_all_products_in_category}")
			&& strstr($template[0]->template_desc, "{pagination}")
			&& strstr($template[0]->template_desc, "perpagelimit:"))
		{
			$perpage = explode('{perpagelimit:', $template[0]->template_desc);
			$perpage = explode('}', $perpage[1]);
			$limit   = intval($perpage[0]);
		}
		else
		{
			$productperpage = $this->getState('productperpage');

			if ($productperpage != 0 && $productperpage != '')
			{
				$limit = $productperpage;
			}
			elseif ($this->_id)
			{
				$limit = intval($redconfig->get('maxproduct', 0));

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

		if (strstr($template[0]->template_desc, "{product_display_limit}"))
		{
			$endlimit = JRequest::getInt('limit', 0, '', 'int');
		}

		return $limit;
	}

	public function getTotal()
	{
		$app = JFactory::getApplication();
		$productlimit = $this->getstate('productlimit');
		$layout = $app->input->getCmd('layout', 'default');

		if (empty($this->_total))
		{
			$this->_db->setQuery($this->_buildQuery(0, true));
			$this->_total = $this->_db->loadResult();

			if ($layout == 'newproduct' || $layout == 'productonsale')
			{
				if ($this->_total > $productlimit && $productlimit != "")
				{
					$this->_total = $productlimit;
				}
			}
		}

		return $this->_total;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	/**
	 * Get Search Condition
	 *
	 * @param   array|string  $fields      Fields
	 * @param   array|string  $conditions  Conditions
	 * @param   string        $glue        Glue
	 *
	 * @return  string
	 */
	public function getSearchCondition($fields, $conditions, $glue = 'OR')
	{
		$where = array();
		$db = JFactory::getDbo();

		foreach ((array) $fields as $field)
		{
			foreach ((array) $conditions as $condition)
			{
				$where[] = $db->qn($field) . ' LIKE ' . $db->quote('%' . $condition . '%');
			}
		}

		return '(' . implode(' ' . $glue . ' ', $where) . ')';
	}

	/**
	 * Build query
	 *
	 * @param   int|array  $manudata  Post request
	 * @param   bool       $getTotal  Get total product(true) or product data(false)
	 *
	 * @return JDatabaseQuery
	 */
	public function _buildQuery($manudata = 0, $getTotal = false)
	{
		$app = JFactory::getApplication();
		$context = 'search';
		$db = JFactory::getDbo();
		$productHelper   = new producthelper;
		$redconfig  = $app->getParams();
		$getorderby = urldecode($app->input->getString('order_by', ''));

		if (in_array($getorderby, $this->filter_fields))
		{
			$order_by = $getorderby;
		}
		else
		{
			$order_by = $redconfig->get('order_by', DEFAULT_PRODUCT_ORDERING_METHOD);
		}

		if ($order_by == 'pc.ordering ASC' || $order_by == 'c.ordering ASC')
		{
			$order_by = 'p.product_id DESC';
		}

		if ($getTotal)
		{
			$query = $db->getQuery(true)
				->select('COUNT(DISTINCT(p.product_id))');
		}
		else
		{
			$query = $db->getQuery(true)
				->select('p.product_id')
				->leftJoin($db->qn('#__redshop_manufacturer', 'm') . ' ON m.manufacturer_id = p.manufacturer_id')
				->order($db->escape($order_by));
		}

		$query->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON pc.product_id = p.product_id')
			->where('p.published = 1');

		$layout = JRequest::getVar('layout', 'default');

		$category_helper = new product_category;
		$manufacture_id = JRequest::getInt('manufacture_id', 0);
		$cat_group = array();

		if ($category_id = $app->input->get('category_id', 0))
		{
			$cat = RedshopHelperCategory::getCategoryListArray(0, $category_id);

			for ($j = 0; $j < count($cat); $j++)
			{
				$cat_group[$j] = $cat[$j]->category_id;

				if ($j == count($cat) - 1)
				{
					$cat_group[$j + 1] = $category_id;
				}
			}

			JArrayHelper::toInteger($cat_group);

			if ($cat_group)
			{
				$cat_group = join(',', $cat_group);
			}
			else
			{
				$cat_group = $category_id;
			}
		}

		$menu = $app->getMenu();
		$item = $menu->getActive();
		$days        = isset($item->query['newproduct']) ? $item->query['newproduct'] : 0;
		$today       = date('Y-m-d H:i:s', time());
		$days_before = date('Y-m-d H:i:s', time() - ($days * 60 * 60 * 24));
		$aclProducts = $productHelper->loadAclProducts();

		// Shopper group - choose from manufactures Start
		$rsUserhelper               = new rsUserhelper;
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			// Sanitize ids
			$manufacturerIds = explode(',', $shopper_group_manufactures);
			JArrayHelper::toInteger($manufacturerIds);

			$query->where('p.manufacturer_id IN (' . implode(',', $manufacturerIds) . ')');
		}

		// Shopper group - choose from manufactures End
		if ($aclProducts != "")
		{
			// Sanitize ids
			$productIds = explode(',', $aclProducts);
			JArrayHelper::toInteger($productIds);

			$query->where('p.product_id IN (' . implode(',', $productIds) . ')');
		}

		if ($layout == 'productonsale')
		{
			$categoryid = $item->params->get('categorytemplate');

			if ($categoryid)
			{
				$cat_main       = $category_helper->getCategoryTree($categoryid);
				$cat_group_main = array();

				for ($j = 0; $j < count($cat_main); $j++)
				{
					$cat_group_main[$j] = $cat_main[$j]->category_id;
				}

				$cat_group_main[] = $categoryid;
				JArrayHelper::toInteger($cat_group_main);

				$query->where('pc.category_id IN (' . implode(',', $cat_group_main) . ')');
			}

			$query->where(
				array(
					'p.product_on_sale = 1',
					'p.expired = 0',
					'p.product_parent_id = 0'
				)
			);
		}
		elseif ($layout == 'featuredproduct')
		{
			$query->where('p.product_special = 1');
		}
		elseif ($layout == 'newproduct')
		{
			$catid = $item->query['categorytemplate'];

			$cat_main       = $category_helper->getCategoryTree($catid);
			$cat_group_main = array();

			for ($j = 0; $j < count($cat_main); $j++)
			{
				$cat_group_main[$j] = $cat_main[$j]->category_id;
			}

			$cat_group_main[] = $catid;
			JArrayHelper::toInteger($cat_group_main);

			if ($catid)
			{
				$query->where('pc.category_id in (' . implode(',', $cat_group_main) . ')');
			}

			$query->where('p.publish_date BETWEEN ' . $db->quote($days_before) . ' AND ' . $db->quote($today))
				->where('p.expired = 0')
				->where('p.product_parent_id = 0');
		}
		elseif ($layout == 'redfilter')
		{
			$query->where('p.expired = 0');

			// Get products for filtering
			if ($products = $this->getRedFilterProduct())
			{
				// Sanitize ids
				$productIds = explode(',', $products);
				JArrayHelper::toInteger($productIds);

				$query->where('p.product_id IN ( ' . implode(',', $productIds) . ')');
			}
		}
		else
		{
			$keyword = $app->getUserStateFromRequest($context . 'keyword', 'keyword', '');
			$defaultSearchType = $app->input->getCmd('search_type', 'product_name');

			if (!empty($manudata['search_type']))
			{
				$defaultSearchType = $manudata['search_type'];
			}

			if ($defaultSearchType == "name_number")
			{
				$query->where($this->getSearchCondition(array('p.product_name', 'p.product_number', 'p.product_s_desc'), $keyword));
			}
			elseif ($defaultSearchType == "name_desc")
			{
				$query->where($this->getSearchCondition(array('p.product_name', 'p.product_desc', 'p.product_s_desc'), $keyword));
			}
			elseif ($defaultSearchType == "virtual_product_num")
			{
				$query->where($this->getSearchCondition(array('pap.property_number', 'ps.subattribute_color_number'), $keyword));
			}
			elseif ($defaultSearchType == "name_number_desc")
			{
				$query->where(
					$this->getSearchCondition(
						array('p.product_name', 'p.product_number', 'p.product_desc', 'p.product_s_desc', 'pap.property_number', 'ps.subattribute_color_number'),
						$keyword
					)
				);
			}
			elseif ($defaultSearchType == "product_desc")
			{
				$query->where($this->getSearchCondition('p.' . $defaultSearchType, $keyword));
			}
			elseif ($defaultSearchType == "product_name")
			{
				$mainSpName = explode(' ', $keyword);

				if (count($mainSpName) > 0)
				{
					$query->where($this->getSearchCondition('p.product_name', explode(' ', $keyword), 'AND'));
				}
			}
			elseif ($defaultSearchType == "product_number")
			{
				$query->where($this->getSearchCondition(array('p.product_number'), $keyword));
			}

			if ($manufacture_id == 0)
			{
				if (!empty($manudata['manufacturer_id']))
				{
					$manufacture_id = $manudata['manufacturer_id'];
				}
			}

			if ($defaultSearchType == "name_number_desc" || $defaultSearchType == "virtual_product_num")
			{
				$query->leftJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON a.product_id = p.product_id')
					->leftJoin($db->qn('#__redshop_product_attribute_property', 'pap') . ' ON pap.attribute_id = a.attribute_id')
					->leftJoin($db->qn('#__redshop_product_subattribute_color', 'ps') . ' ON ps.subattribute_id = pap.property_id');
			}

			$query->where('p.expired = 0');

			if ($category_id != 0)
			{
				// Sanitize ids
				$catIds = explode(',', $cat_group);
				JArrayHelper::toInteger($catIds);

				$query->where('pc.category_id IN (' . $cat_group . ')');
			}

			if ($manufacture_id != 0)
			{
				$query->where('p.manufacturer_id = ' . (int) $manufacture_id);
			}
		}

		return $query;
	}

	public function _buildContentOrderBy()
	{
		$db = JFactory::getDbo();

		global $context;

		$app = JFactory::getApplication();

		$filter_order     = urldecode($app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'order_id'));
		$filter_order_Dir = urldecode($app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', ''));

		$orderby = ' ORDER BY ' . $db->escape($filter_order . ' ' . $filter_order_Dir);

		return $orderby;
	}

	public function getCategoryTemplet()
	{
		$app = JFactory::getApplication();
		$context = 'search';

		$layout     = $app->getUserStateFromRequest($context . 'layout', 'layout', '');
		$templateid = $app->getUserStateFromRequest($context . 'templateid', 'templateid', '');

		$params = JComponentHelper::getParams('com_redshop');
		$menu   = $app->getMenu();
		$item   = $menu->getActive();
		$cid 	= 0;

		$cid = 0;

		if ($layout == 'newproduct')
		{
			$cid = $item->query['categorytemplate'];
		}
		elseif ($layout == 'productonsale')
		{
			$cid = $item->params->get('categorytemplate');
		}

		if ($layout == 'productonsale' || $layout == 'featuredproduct')
		{
			$templateid = $item->params->get('template_id');

			if ($templateid != 0)
			{
				$cid = 0;
			}

			if ($templateid == 0 && $cid == 0)
			{
				$templateid = $app->getUserStateFromRequest($context . 'templateid', 'templateid', '');
			}
		}

		if ($templateid == "" && JModuleHelper::isEnabled('redPRODUCTFILTER'))
		{
			$module        = JModuleHelper::getModule('redPRODUCTFILTER');
			$module_params = new JRegistry($module->params);

			if ($module_params->get('filtertemplate') != "")
			{
				$templateid = $module_params->get('filtertemplate');
			}
		}

		$and = "";

		if ($cid != 0)
		{
			$and .= " AND c.category_id = " . (int) $cid . " ";
		}

		if ($templateid != 0)
		{
			$and .= " AND t.template_id = " . (int) $templateid . " ";
		}

		$query = "SELECT c.category_template, t.* FROM " . $this->_table_prefix . "template AS t "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON t.template_id = c.category_template "
			. "WHERE t.template_section='category' AND t.published=1 "
			. $and;

		return $this->_getList($query);
	}

	/**
	 * Red Product Filter
	 */
	public function getRedFilterProduct($remove = 0)
	{
		// Get seeion filter data

		$session = JSession::getInstance('none', array());

		// Get filter types and tags
		$getredfilter = $session->get('redfilter');

		$type_id_main = explode('.', JRequest::getVar('tagid'));

		// Initialise variables
		$lstproduct_id = array();
		$lasttypeid    = 0;
		$lasttagid     = 0;
		$productid     = 0;
		$products      = "";

		if (count($getredfilter) != 0)
		{
			$main_sal_sp   = array();
			$main_sal_type = array();
			$main_sal_tag  = array();

			if (JRequest::getVar('main_sel') != "")
			{
				$main_sal_sp = explode(",", JRequest::getVar('main_sel'));

				for ($f = 0; $f < count($main_sal_sp); $f++)
				{
					if ($main_sal_sp[$f] != "")
					{
						$main_typeid     = explode(".", $main_sal_sp[$f]);
						$main_sal_type[] = $main_typeid[1];
						$main_sal_tag[]  = $main_typeid[0];
					}
				}
			}

			$q = "SELECT a.product_id
						  FROM #__redproductfinder_association_tag AS ta
						  LEFT JOIN #__redproductfinder_associations AS a ON a.id = ta.association_id
						  LEFT JOIN #__redshop_product AS p ON p.product_id = a.product_id
						  LEFT JOIN #__redshop_product_category_xref x ON x.product_id = a.product_id ";

			for ($i = 0; $i < count($main_sal_type); $i++)
			{
				if ($i != 0)
				{
					$q .= " LEFT JOIN #__redproductfinder_association_tag AS t" . $i . " ON t" . $i . ".association_id=ta.association_id ";
				}
			}

			$q .= "where ( ";
			$dep_cond = array();

			for ($i = 0; $i < count($main_sal_type); $i++)
			{
				$chk_q = "";

				// Search for checkboxes
				if ($i != 0)
				{
					$chk_q .= "t" . $i . ".tag_id='" . (int) $main_sal_tag[$i] . "' ";
				}
				else
				{
					$chk_q .= "ta.tag_id='" . (int) $main_sal_tag[$i] . "' ";
				}

				if ($chk_q != "")
				{
					$dep_cond[] = " ( " . $chk_q . " ) ";
				}
			}

			if (count($dep_cond) <= 0)
			{
				$dep_cond[] = "1=1";
			}

			$q .= implode(" AND ", $dep_cond);

			$q .= ") AND p.published = '1' AND x.category_id = " . (int) JRequest::getInt('cid', 0) . " order by p.product_name ";
			$product = $this->_getList($q);

			for ($i = 0; $i < count($product); $i++)
			{
				$lstproduct_id[] = $product[$i]->product_id;
			}

			$products = implode(",", $lstproduct_id);
		}
		else
		{
			$session->set('redfilterproduct', array());
		}

		return $products;
	}

	public function mod_redProductfilter($Itemid)
	{
		$db = JFactory::getDbo();
		$query = "SELECT t.*, f.formname AS form_name FROM #__redproductfinder_types t
		LEFT JOIN #__redproductfinder_forms f
		ON t.form_id = f.id
		ORDER BY ordering";

		$types   = $this->_getList($query);
		$session = JSession::getInstance('none', array());

		$getredfilter = $session->get('redfilter');

		$redfilterproduct = $session->get('redfilterproduct');

		$redproducttotal = count($redfilterproduct);

		foreach ($types as $key => $type)
		{
			if (@!array_key_exists($type->id, $getredfilter))
			{
				$str                        = htmlentities($type->type_name, ENT_COMPAT, "UTF-8");
				$str                        = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|elig|slash|ring);/', '$1', $str);
				$str                        = str_replace(' ', '', $str);
				$types[$key]->type_name_css = html_entity_decode($str);

				$id         = $type->id;
				$all        = 1;
				$productids = "";

				if (count($getredfilter) > 0 && $all == 1)
				{
					$type_id = array();
					$tag_id  = array();

					$k = 0;

					foreach ($getredfilter as $typeid => $tags)
					{
						$type_id[] = $typeid;
						$tags      = explode(".", $tags);
						$tag_id[]  = $tags[0];

						if (count($getredfilter) - 1 == $k)
						{
							$lasttypeid = $typeid;
							$lasttagid  = $tags[0];
						}

						$k++;
					}

					$typeids = implode(",", $type_id);
					$tagids  = implode(",", $tag_id);

					$query = "SELECT ra.product_id FROM `#__redproductfinder_association_tag` as rat
					LEFT JOIN #__redproductfinder_associations as ra ON rat.`association_id` = ra.id
					WHERE  rat.`type_id` = " . $db->quote($lasttypeid) . " ";

					$query .= "AND  rat.`tag_id` = " . $db->quote($lasttagid) . " ";

					$product = $this->_getList($query);

					$products = array();

					for ($i = 0; $i < count($product); $i++)
					{
						$products[] = $product[$i]->product_id;
					}

					JArrayHelper::toInteger($products);
					$productids = implode(",", $products);
				}

				$q = "SELECT DISTINCT j.tag_id as tagid ,ra.product_id,count(ra.product_id) as ptotal ,CONCAT(j.tag_id,'.',j.type_id) AS tag_id, t.tag_name
			FROM ((#__redproductfinder_tag_type j, #__redproductfinder_tags t )
			LEFT JOIN #__redproductfinder_association_tag as rat ON  t.`id` = rat.`tag_id`)
			LEFT JOIN #__redproductfinder_associations as ra ON ra.id = rat.association_id
			WHERE j.tag_id = t.id
			AND j.type_id = " . (int) $id . "  ";

				if ($productids != "")
				{
					// Sanitize ids
					$productIds = explode(',', $productids);
					JArrayHelper::toInteger($productIds);

					$q .= " AND ra.product_id  IN ( " . implode(',', $productIds) . " ) ";
				}
				$q .= " GROUP BY t.id ORDER BY t.ordering  ";

				$tags = $this->_getList($q);

				$tagname = "";

				// Only show if the type has tags
				if (count($tags) > 0)
				{
					// Create the selection boxes
					for ($t = 0; $t < count($tags); $t++)
					{
						$type_id = explode('.', $tags[$t]->tag_id);

						$query = "SELECT count(*) as count FROM #__redproductfinder_association_tag as ra
							left join #__redproductfinder_associations as a on ra.association_id = a.id
							left join #__redshop_product as rp on rp.product_id = a.product_id
							WHERE type_id = " . $db->quote($type_id[1]) . " AND tag_id = " . $db->quote($type_id[0]) . " AND rp.published = 1";

						$published = $this->_getList($query);

						if ($published[0]->count > $redproducttotal && $redproducttotal > 0)
						{
							$finalcount = $redproducttotal;
						}
						else
						{
							$finalcount = $published[0]->count;
						}

						if ($finalcount > 0)
						{
							$tagname .= "&nbsp;&nbsp;<a  href='" . JRoute::_('index.php?option=com_redshop&view=search&layout=redfilter&typeid=' . $type->id . '&tagid=' . $tags[$t]->tag_id . '&Itemid=' . $Itemid) . "' title='" . $tags[$t]->tag_name . "' >" . $tags[$t]->tag_name . "</a> ( " . $finalcount . " )<br/>";
						}
					}

					if ($tagname != "")
					{
						$lists['type' . $key] = $tagname;
					}
				}
				else
				{
					unset($types[$key]);
				}
			}
		}

		if (count($getredfilter) != 0)
		{
			foreach ($getredfilter as $typeid => $tag_id)
			{
				foreach ($types as $key => $type)
				{
					if ($typeid == $type->id)
					{
						$str                        = htmlentities($type->type_name, ENT_COMPAT, "UTF-8");
						$str                        = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|elig|slash|ring);/', '$1', $str);
						$str                        = str_replace(' ', '', $str);
						$types[$key]->type_name_css = html_entity_decode($str);

						$tags = $this->getTagsDetail($type->id, 0);

						$tagname = "";

						// Only show if the type has tags
						if (count($tags) > 0)
						{
							// Create the selection boxes
							for ($t = 0; $t < count($tags); $t++)
							{
								if ($tags[$t]->tagid == $tag_id)
								{
									$tagname .= "<span style='float:left;'>&nbsp;&nbsp;" . $tags[$t]->tag_name . "</span><span style='float:right;'><a href='javascript:deleteTag(\"$type->id\",\"$Itemid\");' title='" . JText::_('COM_REDSHOP_DELETE') . "' >" . JText::_('COM_REDSHOP_DELETE') . "</a></span><br/>";
								}
							}

							if ($tagname != "")
							{
								$filteredlists['type' . $key] = $tagname;
							}
						}
						else
						{
							unset($types[$key]);
						}
					}
				}
			}
		}

		if (count($getredfilter) != 0)
		{
			?>
			<div id="pfsearchheader"><?php echo JText::_('COM_REDSHOP_SEARCH_RESULT');?></div>

			<div class="hrdivider"></div>
			<?php
			foreach ($getredfilter as $typeid => $tag_id)
			{
				foreach ($types as $key => $type)
				{
					if ($typeid == $type->id)
					{
						?>
						<div id="typename_<?php echo $type->id; ?>"
						     class="typename <?php echo $type->type_name_css; ?>">
							<?php echo $type->type_name; ?>
							<?php
							if (strlen($type->tooltip) > 0)
							{
								echo ' ' . JHTML::tooltip($type->tooltip, $type->type_name, 'tooltip.png', '', '', false);
							} ?>
						</div>
						<div id="typevalue_<?php echo $type->id; ?>"
						     class="typevalue <?php echo $type->type_name_css; ?>">
							<?php echo $filteredlists['type' . $key];?></div>
						<div class="hrdivider <?php echo $type->type_name_css; ?>"></div>

					<?php
					}
				}
			}
			?>
			<div>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=search&layout=redfilter&remove=1&Itemid=' . $Itemid); ?>"
				   title="<?php echo JText::_('COM_REDSHOP_CLEAR_ALL'); ?>">
					<?php echo JText::_('COM_REDSHOP_CLEAR_ALL'); ?></a>
			</div>
			<div id="spacer">&nbsp;_________________________</div>
		<?php
		}

		if (count($types) > 0)
		{
			?>
			<div id="pfsearchheader"><?php echo JText::_('COM_REDSHOP_SEARCH_CRITERIA');?></div>

			<div class="hrdivider"></div>
			<?php

			foreach ($types as $key => $type)
			{
				if (@!array_key_exists($type->id, $getredfilter) && @array_key_exists('type' . $key, $lists))
				{
					?>
					<div id="<?php echo $type->id; ?>"
					     class="typename <?php echo $type->type_name_css; ?>">
						<?php echo $type->type_name; ?>
						<?php
						if (strlen($type->tooltip) > 0)
						{
							echo ' ' . JHTML::tooltip($type->tooltip, $type->type_name, 'tooltip.png', '', '', false);
						}    ?>
					</div>
					<div class="typevalue <?php echo $type->type_name_css; ?>">
						<?php echo $lists['type' . $key];?></div>
					<div class="hrdivider <?php echo $type->type_name_css; ?>"></div>
				<?php
				}
			}
		}
	}

	public function getTagsDetail($id, $all = 1)
	{
		// For session
		$session      = JSession::getInstance('none', array());
		$getredfilter = $session->get('redfilter');
		$db           = JFactory::getDbo();
		$productids   = "";

		if (count($getredfilter) > 0 && $all == 1)
		{
			$type_id = array();
			$tag_id  = array();
			$k       = 0;

			foreach ($getredfilter as $typeid => $tags)
			{
				$type_id[] = $typeid;
				$tags      = explode(".", $tags);
				$tag_id[]  = $tags[0];

				if (count($getredfilter) - 1 == $k)
				{
					$lasttypeid = $typeid;
					$lasttagid  = $tags[0];
				}

				$k++;
			}

			$typeids = implode(",", $type_id);
			$tagids  = implode(",", $tag_id);

			$query = "SELECT ra.product_id FROM #__redproductfinder_association_tag AS rat "
				. "LEFT JOIN #__redproductfinder_associations AS ra ON rat.association_id = ra.id "
				. "WHERE rat.type_id = " . $db->quote($lasttypeid) . " "
				. "AND rat.tag_id = " . $db->quote($lasttagid) . " ";
			$db->setQuery($query);
			$product  = $db->loadObjectList();
			$products = array();

			for ($i = 0; $i < count($product); $i++)
			{
				$products[] = $product[$i]->product_id;
			}
		}

		$q = "SELECT DISTINCT j.tag_id AS tagid,ra.product_id,count(ra.product_id) AS ptotal, "
			. "CONCAT(j.tag_id,'.',j.type_id) AS tag_id, t.tag_name "
			. "FROM ((#__redproductfinder_tag_type j, #__redproductfinder_tags t ) "
			. "LEFT JOIN #__redproductfinder_association_tag as rat ON  t.`id` = rat.`tag_id`) "
			. "LEFT JOIN #__redproductfinder_associations as ra ON ra.id = rat.association_id "
			. "WHERE j.tag_id = t.id "
			. "AND j.type_id = " . (int) $id . " ";

		if ($productids != "")
		{
			// Sanitize ids
			JArrayHelper::toInteger($products);

			$q .= " AND ra.product_id IN (" . implode(",", $products) . ") ";
		}

		$q .= " GROUP BY t.id ORDER BY t.ordering ";
		$db->setQuery($q);

		return $db->loadObjectList();
	}

	/**
	 * Get Category products selected in search Module
	 */
	public function loadCatProductsManufacturer($cid)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT  p.product_id, p.manufacturer_id FROM " . $this->_table_prefix . "product_category_xref AS cx "
			. ", " . $this->_table_prefix . "product AS p "
			. "WHERE cx.category_id = " . (int) $cid . " "
			. "AND p.product_id=cx.product_id ";
		$db->setQuery($query);
		$manufacturer = $db->loadObjectList();

		$mids = array();

		for ($i = 0; $i < count($manufacturer); $i++)
		{
			if ($manufacturer[$i]->manufacturer_id > 0)
			{
				$mids[] = $manufacturer[$i]->manufacturer_id;
			}
		}

		// Sanitize ids
		JArrayHelper::toInteger($mids);

		$query = "SELECT manufacturer_id AS value,manufacturer_name AS text FROM " . $this->_table_prefix . "manufacturer "
			. "WHERE manufacturer_id IN ('" . implode(",", $mids). "')";
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	public function getajaxData()
	{
		JLoader::import('joomla.application.module.helper');
		$module      = JModuleHelper::getModule('redshop_search');
		$params      = new JRegistry($module->params);
		$limit       = $params->get('noofsearchresults');
		$keyword     = JRequest::getCmd('input');
		$search_type = JRequest::getCmd('search_type');
		$db = JFactory::getDbo();

		$category_id    = JRequest::getInt('category_id');
		$manufacture_id = JRequest::getInt('manufacture_id');

		$where = array();

		if ($search_type == 'product_name')
		{
			$where[] = "p.product_name LIKE " . $db->quote('%' . $keyword . '%') . " ";
		}
		elseif ($search_type == 'product_number')
		{
			$where[] = "p.product_number LIKE " . $db->quote('%' . $keyword . '%') . " ";
		}
		elseif ($search_type == 'name_number')
		{
			$where[] = "p.product_name LIKE " . $db->quote('%' . $keyword . '%')
				. " or p.product_number LIKE " . $db->quote('%' . $keyword . '%') . " ";
		}
		elseif ($search_type == 'product_desc')
		{
			$where[] = "p.product_s_desc LIKE " . $db->quote('%' . $keyword . '%')
				. "  or p.product_desc LIKE " . $db->quote('%' . $keyword . '%') . " ";
		}
		elseif ($search_type == 'name_desc')
		{
			$where[] = "p.product_name LIKE " . $db->quote('%' . $keyword . '%')
				. " or p.product_s_desc LIKE " . $db->quote('%' . $keyword . '%')
				. " or p.product_desc LIKE " . $db->quote('%' . $keyword . '%') . " ";
		}
		elseif ($search_type == 'name_number_desc')
		{
			$where[] = "p.product_name LIKE " . $db->quote('%' . $keyword . '%')
				. "  or p.product_number LIKE " . $db->quote('%' . $keyword . '%')
				. "  or p.product_s_desc LIKE " . $db->quote('%' . $keyword . '%')
				. "  or p.product_desc LIKE " . $db->quote('%' . $keyword . '%') . " ";
		}

		if ($category_id != "0")
		{
			$where[] = "c.category_id = " . (int) $category_id . " ";
		}

		if ($manufacture_id != "0")
		{
			$where[] = "p.manufacturer_id = " . (int) $manufacture_id . " ";
		}

		$wheres = '';
		$wheres = implode(" AND ", $where);

		$query = "SELECT p.product_id AS id,p.product_name AS value,p.product_number as value_number FROM "
			. $this->_table_prefix . "product p "
			. 'LEFT JOIN ' . $this->_table_prefix . 'product_category_xref x ON x.product_id = p.product_id '
			. 'LEFT JOIN ' . $this->_table_prefix . 'category c ON x.category_id = c.category_id '
			. " WHERE p.published=1 AND " . $wheres . " GROUP BY p.product_id ";

		$this->_data = $this->_getList($query, "0", $limit);

		return $this->_data;
	}
}
