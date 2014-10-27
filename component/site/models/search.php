<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.modellist');

JLoader::load('RedshopHelperAdminCategory');
JLoader::load('RedshopHelperProduct');

/**
 * Class searchModelsearch
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelSearch extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'p.product_id',
				'p.product_name',
				'p.product_price',
				'p.product_number'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   Ordering of sorting
	 * @param   string  $direction  Direction of sorting
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app       = JFactory::getApplication();
		$jinput    = $app->input;
		$ordering  = 'p.product_id';
		$direction = 'ASC';
		$limit     = 5;

		$redconfig  = $app->getParams();
		$orderBy = $jinput->getString('order_by', '');

		if (empty($orderBy))
		{
			$orderBy = $redconfig->get('order_by', DEFAULT_PRODUCT_ORDERING_METHOD);
		}

		if (!empty($orderBy))
		{
			$orders = explode(' ', $orderBy);

			if (in_array($orders[0], $this->filter_fields))
			{
				$ordering = $orders[0];
				$direction = $orders[1];
			}
		}

		$this->setState('list.ordering', $ordering);
		$this->setState('list.direction', $direction);

		if (!empty($jinput->getInt('limit')))
		{
			$limit = $jinput->getInt('limit');
		}
		else
		{
			// Get limit in tempalate
			$redTemplate = new Redtemplate;
			$template = $this->getCategoryTemplate();

			for ($i = 0; $i < count($template); $i++)
			{
				$template[$i]->template_desc = $redTemplate->readtemplateFile($template[$i]->template_section, $template[$i]->template_name);
			}

			if (count($template) > 0)
			{
				if (strstr($template[0]->template_desc, "{show_all_products_in_category}"))
				{
					$limit = 0;
				}
				elseif (strstr($template[0]->template_desc, "{pagination}"))
				{
					if (strstr($template[0]->template_desc, "perpagelimit:"))
					{
						$perpage = explode('{perpagelimit:', $template[0]->template_desc);
						$perpage = explode('}', $perpage[1]);
						$limit   = intval($perpage[0]);
					}
				}
			}

			// Get limit in menu item
			$menuItem = $app->getMenu()->getActive();

			if (isset($menuItem->query['productlimit']))
			{
				$limit = $menuItem->query['productlimit'];
			}
			elseif ($module = JModuleHelper::getModule('redshop_search'))
			{
				$module_params  = new JRegistry($module->params);
				$limit = $module_params->get('productperpage', $limit);
			}
		}

		$this->setState('list.limit', $limit);
		$this->setState('list.start', $jinput->getInt('limitstart', 0));
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 */
	protected function getListQuery()
	{
		$app             = JFactory::getApplication();
		$jinput          = $app->input;
		$menuItem        = $app->getMenu()->getActive();
		$layout          = $jinput->getString('layout', 'default');
		$db              = $this->getDbo();
		$user            = JFactory::getUser();
		$productHelper   = new producthelper;
		$category_helper = new product_category;

		$query = $db->getQuery(true);
		$query = $productHelper->getMainProductQuery($query, $user->id)
				->select(
					array(
						'm.*',
						'CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id, 0 AS is_category'
					)
				)
				->leftJoin($db->qn('#__redshop_manufacturer', 'm') . ' ON m.manufacturer_id = p.manufacturer_id');

		$query->where('p.published = 1 AND p.expired = 0');

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

		$aclProducts = $productHelper->loadAclProducts();

		if ($aclProducts != "")
		{
			// Sanitize ids
			$productIds = explode(',', $aclProducts);
			JArrayHelper::toInteger($productIds);

			$query->where('p.product_id IN (' . implode(',', $productIds) . ')');
		}

		$manufacture_id = $jinput->getInt('manufacture_id', 0);
		$category_id    = $jinput->getInt('category_id', 0);

		if ($category_id != 0)
		{
			$cat       = $category_helper->getCategoryListArray(0, $category_id);
			$cat_group = array();

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

			// Sanitize ids
			$catIds = explode(',', $cat_group);
			JArrayHelper::toInteger($catIds);

			$query->where('pc.category_id IN (' . $cat_group . ')');
		}

		$categorytemplate = $menuItem->params->get('categorytemplate');

		if ($categorytemplate)
		{
			$cat_main       = $category_helper->getCategoryTree($categorytemplate);
			$cat_group_main = array();

			for ($j = 0; $j < count($cat_main); $j++)
			{
				$cat_group_main[$j] = $cat_main[$j]->category_id;
			}

			$cat_group_main[] = $categorytemplate;
			JArrayHelper::toInteger($cat_group_main);

			$query->where('pc.category_id IN (' . implode(',', $cat_group_main) . ')');
		}

		if ($manufacture_id != 0)
		{
			$query->where('p.manufacturer_id = ' . (int) $manufacture_id);
		}

		if ($layout == 'productonsale')
		{
			$query->where(
				array(
					'p.product_on_sale = 1',
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
			$days        = isset($menuItem->query['newproduct']) ? $menuItem->query['newproduct'] : 0;
			$today       = date('Y-m-d H:i:s', time());
			$days_before = date('Y-m-d H:i:s', time() - ($days * 60 * 60 * 24));

			$query->where('p.publish_date BETWEEN ' . $db->quote($days_before) . ' AND ' . $db->quote($today))
				->where('p.product_parent_id = 0');
		}
		elseif ($layout == 'redfilter')
		{
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
			$keyword = $jinput->getString('keyword', '');
			$defaultSearchType = $jinput->getString('search_type', '');

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

			if ($defaultSearchType == "name_number_desc" || $defaultSearchType == "virtual_product_num")
			{
				$query->leftJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON a.product_id = p.product_id')
					->leftJoin($db->qn('#__redshop_product_attribute_property', 'pap') . ' ON pap.attribute_id = a.attribute_id')
					->leftJoin($db->qn('#__redshop_product_subattribute_color', 'ps') . ' ON ps.subattribute_id = pap.property_id');
			}
		}

		$orderCol	= $this->state->get('list.ordering', 'p.product_id');
		$orderDirn	= $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$app           = JFactory::getApplication();
		$layout        = $app->input->getString('layout', 'default');
		$producthelper = new producthelper;

		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$query = $this->_getListQuery();

		// Only sort when ordering and layout are default
		if ($this->getState('list.ordering') == 'p.product_id' && $layout == 'default')
		{
			$items = $this->_getList($query);

			if (($items) > 0)
			{
				$categories = array();
				$products = array();

				foreach ($items as $row)
				{
					if ($row->is_category)
					{
						$categories[] = $row;
					}
					else
					{
						$row->category_name = $producthelper->getCategoryNameByProductId($row->product_id);
						$products[] = $row;
					}
				}

				if ($this->getState('list.ordering') == 'p.product_id')
				{
					usort($products, array($this, "sortProducts"));
				}

				$items = array_merge($categories, $products);
				$items = array_slice($items, $this->getStart(), $this->getState('list.limit'));
			}
		}
		else
		{
			$items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));
		}

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Sort product function with prioritized search
	 *
	 * @param   object  $a  first product
	 * @param   object  $b  second product
	 *
	 * @return  int
	 */
	private function sortProducts($a, $b)
	{
		$app     = JFactory::getApplication();
		$context = 'search';
		$keyword = $app->input->getString('keyword');
		$keyword = strtolower($keyword);

		$charsA = preg_split('/ /', $a->product_name);
		$charsB = preg_split('/ /', $b->product_name);

		$strACname = strpos(strtolower($a->category_name), $keyword);
		$strBCname = strpos(strtolower($b->category_name), $keyword);

		$strAPname = strpos(strtolower($a->product_name), $keyword);
		$strBPname = strpos(strtolower($b->product_name), $keyword);

		// Compare product name
		if ($strAPname !== false && $strBPname === false)
		{
			return -1;
		}
		elseif ($strAPname === false && $strBPname !== false)
		{
			return 1;
		}

		// On keyword
		foreach ($charsA as $charA)
		{
			$charA = strtolower($charA);

			foreach ($charsB as $charB)
			{
				$charB = strtolower($charB);

				if ($charA == $keyword && $charB != $keyword)
				{
					return -1;
				}
				elseif ($charA != $keyword && $charB == $keyword)
				{
					return 1;
				}
			}
		}

		// Extract keyword
		$keywords = preg_split('/ /', $keyword);

		$nA = 0;
		$nB = 0;

		$intA = -1;
		$intB = -1;

		if (count($keywords) > 0)
		{
			foreach ($keywords as $i => $key)
			{
				foreach ($charsA as $charA)
				{
					$charA = strtolower($charA);

					foreach ($charsB as $charB)
					{
						$charB = strtolower($charB);

						if ($charA == $key && $charB != $key)
						{
							return -1;
						}
						elseif ($charA != $key && $charB == $key)
						{
							return 1;
						}
					}
				}
			}
		}

		// No in product name - Compoare in category
		if ($strACname !== false && $strBCname !== false)
		{
			return strcasecmp($a->product_name, $b->product_name);
		}
		else
		{
			if ($strACname !== false && $strBCname === false)
			{
				return -1;
			}
			elseif ($strACname === false && $strBCname !== false)
			{
				return 1;
			}
		}

		return strcasecmp($a->product_name, $b->product_name);
	}

	/**
	 * Get category result
	 *
	 * @return  array
	 */
	public function getCategories()
	{
		$module        = JModuleHelper::getModule('redshop_search');
		$module_params = new JRegistry($module->params);

		if ($module_params->get('searchCategory'))
		{
			$app            = JFactory::getApplication();
			$jinput         = $app->input;
			$db             = $this->getDbo();
			$keyword        = $jinput->getString('keyword', '');
			$manufacture_id = $jinput->getInt('manufacture_id', 0);
			$category_id    = $jinput->getInt('category_id', 0);

			$query = $db->getQuery(true);
			$query->select('c.*')->from($db->qn('#__redshop_category', 'c'));

			if ($manufacture_id)
			{
				$query->leftJoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON pcx.category_id = c.category_id')
					->leftJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = pcx.product_id')
					->leftJoin($db->qn('#__redshop_manufacturer', 'm') . ' ON m.manufacturer_id = p.manufacturer_id')
					->where('m.manufacturer_id = ' . (int) $manufacture_id)
					->group('c.category_id');
			}

			if ($category_id)
			{
				$query->leftJoin($db->qn('#__redshop_category_xref', 'cx') . ' ON cx.category_child_id = c.category_id')
					->where('cx.category_parent_id = ' . (int) $category_id);
			}

			$query->where($this->getSearchCondition(array('c.category_name', 'c.category_short_description', 'c.category_description'), $keyword));

			$db->setQuery($query);

			return $db->loadObjectList();
		}

		return null;
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
	 * Method to get Category Template
	 *
	 * @return  mixed
	 */
	public function getCategoryTemplate()
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

		$query = "SELECT c.category_template, t.* FROM #__redshop_template AS t "
			. "LEFT JOIN #__redshop_category AS c ON t.template_id = c.category_template "
			. "WHERE t.template_section='category' AND t.published=1 "
			. $and;

		return $this->_getList($query);
	}

	/**
	 * Red Product Filter
	 *
	 * @param   bool  $remove  Is remove.
	 *
	 * @return array
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

	/**
	 * [mod_redProductfilter description]
	 *
	 * @param   [type]  $Itemid  [description]
	 *
	 * @return  [type]           [description]
	 */
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

	/**
	 * [getTagsDetail description]
	 *
	 * @param   [type]   $id   [description]
	 * @param   integer  $all  [description]
	 *
	 * @return  [type]         [description]
	 */
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
	 *
	 * @param   int  $cid  Category Id.
	 *
	 * @return array
	 */
	public function loadCatProductsManufacturer($cid)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT  p.product_id, p.manufacturer_id FROM #__redshop_product_category_xref AS cx "
			. ", #__redshop_product AS p "
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

		$query = "SELECT manufacturer_id AS value,manufacturer_name AS text FROM #__redshop_manufacturer " . "WHERE manufacturer_id IN ('" . implode(",", $mids) . "')";
		$db->setQuery($query);

		return $db->loadObjectList();
	}
}

