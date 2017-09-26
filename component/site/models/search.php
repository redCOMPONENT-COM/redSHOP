<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
	// @ToDo In feature, when class Search extends RedshopModelList, replace filter_fields in constructor
	public $filter_fields = array(
		'p.product_name ASC', 'product_name ASC',
		'p.product_price ASC', 'product_price ASC',
		'p.product_price DESC', 'product_price DESC',
		'p.product_number ASC', 'product_number ASC',
		'p.product_id DESC', 'product_id DESC',
		'pc.ordering ASC', 'ordering ASC'
	);

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState($ordering, $direction);

		$app    = JFactory::getApplication();
		$params = $app->getParams('com_redshop');
		$menu   = $app->getMenu();
		$item   = $menu->getActive();
		$layout = $app->getUserStateFromRequest($this->context . '.layout', 'layout', 'default');
		$this->setState('layout', $layout);

		$templateid = $app->getUserStateFromRequest($this->context . '.templateid', 'templateid', '');

		$cid = 0;

		if ($layout == 'newproduct')
		{
			$result = $item->query['template_id'];

			if ($result != 0)
			{
				$templateid = $result;
			}
			else
			{
				$cid = $item->query['categorytemplate'];
			}
		}
        elseif ($layout == 'productonsale')
		{
			$cid = $item->params->get('categorytemplate');
		}

		if ($layout == 'productonsale' || $layout == 'featuredproduct')
		{
			$result = $item->params->get('template_id');

			if ($result != 0)
			{
				$templateid = $result;
				$cid        = 0;
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

		$this->setState('templateid', $templateid);

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('c.template AS category_template, t.*')
			->from($db->qn('#__redshop_template', 't'))
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON t.template_id = c.template')
			->where('t.template_section = ' . $db->q('category'))
			->where('t.published = 1');

		if ($cid != 0)
		{
			$query->where('c.id = ' . (int) $cid);
		}

		if ($templateid != 0)
		{
			$query->where('t.template_id = ' . (int) $templateid);
		}

		$templateDesc = null;

		if ($template = $db->setQuery($query)->loadObject())
		{
			$redTemplate  = Redtemplate::getInstance();
			$templateDesc = $redTemplate->readtemplateFile($template->template_section, $template->template_name);
		}

		$this->setState('templateDesc', $templateDesc);
		$limit = 0;

		if ($module = JModuleHelper::getModule('redshop_search'))
		{
			$module_params  = new JRegistry($module->params);
			$perpageproduct = $module_params->get('productperpage', 5);
		}
		else
		{
			$perpageproduct = 5;
		}

		if (!strstr($templateDesc, "{show_all_products_in_category}")
			&& strstr($templateDesc, "{pagination}")
			&& strstr($templateDesc, "perpagelimit:")
		)
		{
			$perpage = explode('{perpagelimit:', $templateDesc);
			$perpage = explode('}', $perpage[1]);
			$limit   = intval($perpage[0]);
		}
		else
		{
			$limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', $limit, 'int');

			if (!$limit && $perpageproduct != 0 && $perpageproduct != '' && $layout == 'default')
			{
				$limit = $perpageproduct;
			}
            elseif (!$limit && $layout == 'productonsale')
			{
				$limit = $params->get('productlimit', 5);
			}
            elseif (!$limit)
			{
				$limit = Redshop::getConfig()->get('MAXCATEGORY');
			}
		}

		$productlimit = 0;

		if (isset($item->query['productlimit']))
		{
			$productlimit = $item->query['productlimit'];
		}

		$filter = $app->input->post->get('redform', array(), 'filter');
		$this->setState('filter.data', $filter);

		$orderBy = $app->input->getString('order_by', '');
		$this->setState('order_by', $orderBy);
		$this->setState('template_id', $filter['template_id']);

		$this->setState('productperpage', $perpageproduct);
		$this->setState('list.limit', $limit);
		$productlimit = $app->getUserStateFromRequest($this->context . '.productlimit', 'productlimit', $productlimit, 8);
		$this->setState('productlimit', $productlimit);

		$value      = $app->input->get('limitstart', 0, 'int');
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);

		$keyword = $app->getUserStateFromRequest($this->context . '.keyword', 'keyword', '');
		$this->setState('keyword', $keyword);
	}

	/**
	 * Method to get a store id based on the model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string $id An identifier string to generate the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		// Add the list state to the store id.
		$id .= ':' . $this->getState('productperpage');
		$id .= ':' . $this->getState('productlimit');
		$id .= ':' . $this->getState('templateid');
		$id .= ':' . $this->getState('keyword');
		$id .= ':' . $this->getState('layout');

		return md5($this->context . ':' . $id);
	}

	/**
	 * Method to get the starting number of items for the data set.
	 *
	 * @return  integer  The starting number of items available in the data set.
	 *
	 * @since   1.5
	 */
	public function getStart()
	{
		$store = $this->getStoreId('getstart');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		$start = $this->getState('list.start');
		$limit = $this->getState('list.limit');
		$total = $this->getTotal();

		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}

		// Add the total to the internal cache.
		$this->cache[$store] = $start;

		return $this->cache[$store];
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.5
	 */
	public function getData()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		$post = JFactory::getApplication()->input->post->getArray();
		$db           = JFactory::getDbo();
		$items        = array();
		$query        = $this->_buildQuery($post);
		$templateDesc = $this->getState('templateDesc');

		if ($templateDesc)
		{
			if (strstr($templateDesc, "{show_all_products_in_category}"))
			{
				$db->setQuery($query);
			}
            elseif (strstr($templateDesc, "{pagination}") || $this->getState('productlimit') > 0)
			{
				$db->setQuery($query, $this->getStart(), $this->getState('list.limit'));
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

			$user  = JFactory::getUser();
			$query = RedshopHelperProduct::getMainProductQuery($query, $user->id)
				->select(
					array(
						'pc.ordering', 'c.*', 'm.*',
						'CONCAT_WS(' . $db->q('.') . ', p.product_id, ' . (int) $user->id . ') AS concat_id'
					)
				)
				->select($db->qn('c.id', 'category_id'))
				->select($db->qn('c.name', 'category_name'))
				->leftJoin('#__redshop_category AS c ON c.id = pc.category_id')
				->leftJoin('#__redshop_manufacturer AS m ON m.manufacturer_id = p.manufacturer_id');

			if ($products = $db->setQuery($query)->loadObjectList('concat_id'))
			{
				RedshopHelperProduct::setProduct($products);
				$items = array_values($products);
			}
		}

		$this->preprocessData($this->context, $items);

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Method to get the total number of items for the data set.
	 *
	 * @return  integer  The total number of items available in the data set.
	 *
	 * @since   1.5
	 */
	public function getTotal()
	{
		// Get a storage key.
		$store = $this->getStoreId('getTotal');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		$productlimit = $this->getState('productlimit');
		$layout       = $this->getState('layout', 'default');

		$db    = JFactory::getDbo();
		$total = $db->setQuery($this->_buildQuery(0, true))
			->loadResult();

		if ($layout == 'newproduct' || $layout == 'productonsale')
		{
			if ($total > $productlimit && $productlimit != "")
			{
				$total = $productlimit;
			}
		}

		// Add the total to the internal cache.
		$this->cache[$store] = $total;

		return $this->cache[$store];
	}

	/**
	 * Get Search Condition
	 *
	 * @param   array|string $fields     Fields
	 * @param   array|string $conditions Conditions
	 * @param   string       $glue       Glue
	 *
	 * @return  string
	 */
	public function getSearchCondition($fields, $conditions, $glue = 'OR')
	{
		$where        = array();
		$db           = JFactory::getDbo();
		$conditions   = explode(' ', $conditions);
		$hasCondition = false;

		foreach ((array) $fields as $field)
		{
			$glueOneField = array();

			foreach ((array) $conditions as $condition)
			{
				$condition = trim($condition);

				if ($condition != '')
				{
					$hasCondition   = true;
					$glueOneField[] = $db->qn($field) . ' LIKE ' . $db->quote('%' . $condition . '%');
				}
			}

			$where[] = '(' . implode(' AND ', $glueOneField) . ')';
		}

		if ($hasCondition)
		{
			return '(' . implode(' ' . $glue . ' ', $where) . ')';
		}
		else
		{
			return '1 = 1';
		}
	}

	/**
	 * Build query
	 *
	 * @param   int|array $manudata Post request
	 * @param   bool      $getTotal Get total product(true) or product data(false)
	 *
	 * @return JDatabaseQuery
	 */
	public function _buildQuery($manudata = 0, $getTotal = false)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$db    = JFactory::getDbo();

		$orderByMethod = $input->getString(
			'order_by',
			$app->getParams()->get('order_by', Redshop::getConfig()->get('DEFAULT_PRODUCT_ORDERING_METHOD'))
		);
		$orderByObj = RedshopHelperUtility::prepareOrderBy(urldecode($orderByMethod));
		$orderBy    = $orderByObj->ordering . ' ' . $orderByObj->direction;

		if ($getTotal)
		{
			$query = $db->getQuery(true)
				->select('COUNT(DISTINCT(p.product_id))');
		}
		else
		{
			$query = $db->getQuery(true)
				->select('DISTINCT(p.product_id)')
				->leftJoin($db->qn('#__redshop_manufacturer', 'm') . ' ON m.manufacturer_id = p.manufacturer_id')
				->order($db->escape($orderBy));
		}

		$query->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON pc.product_id = p.product_id')
			->where('p.published = 1');

		JPluginHelper::importPlugin('redshop_search');
		RedshopHelperUtility::getDispatcher()->trigger('searchQuery', array(&$query));

		$layout = JRequest::getVar('layout', 'default');

		$category_helper = new product_category;
		$manufacture_id  = $input->getInt('manufacture_id', 0);
		$cat_group       = array();
		$customField     = $input->get('custom_field', array(), 'array');

		if ($category_id = $input->get('category_id', 0))
		{
			$cat = RedshopHelperCategory::getCategoryListArray(0, $category_id);

			for ($j = 0, $countCat = count($cat); $j < $countCat; $j++)
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

		$menu        = $app->getMenu();
		$item        = $menu->getActive();
		$days        = isset($item->query['newproduct']) ? $item->query['newproduct'] : 0;
		$today       = date('Y-m-d H:i:s', time());
		$days_before = date('Y-m-d H:i:s', time() - ($days * 60 * 60 * 24));
		$aclProducts = productHelper::getInstance()->loadAclProducts();

		// Shopper group - choose from manufactures Start
		$rsUserhelper               = rsUserHelper::getInstance();
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			// Sanitize ids
			$manufacturerIds = explode(',', $shopper_group_manufactures);
			JArrayHelper::toInteger($manufacturerIds);

			$query->where('p.manufacturer_id IN (' . implode(',', $manufacturerIds) . ')');
		}

		if (!empty($customField))
		{
			$key = 0;
			$subQuery = array();

			foreach ($customField as $fieldId => $fieldValue)
			{
				if (empty($fieldValue))
				{
					continue;
				}

				$subQuery[] = 'FIND_IN_SET("' . $fieldValue . '", ' . $db->qn('fd' . $key . '.data_txt') . ')';

				$query->leftJoin($db->qn('#__redshop_fields_data', 'fd' . $key) . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('fd' . $key . '.itemid'))
					->where($db->qn('fd' . $key . '.fieldid') . ' = ' . $db->q((int) $fieldId));
				$key++;
			}

			if (!empty($subQuery))
			{
				$query->where('(' . implode(' OR ', $subQuery) . ')');
			}
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

				for ($j = 0, $countCatMain = count($cat_main); $j < $countCatMain; $j++)
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

			for ($j = 0, $countCatMain = count($cat_main); $j < $countCatMain; $j++)
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
			$keyword           = $this->getState('keyword');
			$defaultSearchType = $app->input->getCmd('search_type', 'product_name');

			if (!empty($manudata['search_type']))
			{
				$defaultSearchType = $manudata['search_type'];
			}

			switch ($defaultSearchType)
			{
				case 'name_number':
					$query->where($this->getSearchCondition(array('p.product_name', 'p.product_number'), $keyword));
					break;
				case 'name_desc':
					$query->where($this->getSearchCondition(array('p.product_name', 'p.product_desc', 'p.product_s_desc'), $keyword));
					break;
				case 'virtual_product_num':
					$query->where($this->getSearchCondition(array('pap.property_number', 'ps.subattribute_color_number'), $keyword));
					break;
				case 'name_number_desc':
					$query->where(
						$this->getSearchCondition(
							array('p.product_name', 'p.product_number', 'p.product_desc', 'p.product_s_desc', 'pap.property_number', 'ps.subattribute_color_number'),
							$keyword
						)
					);
					break;
				case 'product_desc':
					$query->where($this->getSearchCondition(array('p.product_s_desc', 'p.product_desc'), $keyword));
					break;
				case 'product_name':
					$query->where($this->getSearchCondition('p.product_name', $keyword));
					break;
				case 'product_number':
					$query->where($this->getSearchCondition('p.product_number', $keyword));
					break;
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

	/**
	 * Red Product Filter
	 */
	public function getRedFilterProduct($remove = 0)
	{
		// Get seeion filter data

		$session = JSession::getInstance('none', array());

		// Get filter types and tags
		$getredfilter = $session->get('redfilter');

		$app = JFactory::getApplication();

		$type_id_main = explode('.', $app->input->get('tagid'));

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

			if ($app->input->get('main_sel') != "")
			{
				$main_sal_sp = explode(",", $app->input->get('main_sel'));

				for ($f = 0, $fn = count($main_sal_sp); $f < $fn; $f++)
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

			for ($i = 0, $in = count($main_sal_type); $i < $in; $i++)
			{
				if ($i != 0)
				{
					$q .= " LEFT JOIN #__redproductfinder_association_tag AS t" . $i . " ON t" . $i . ".association_id=ta.association_id ";
				}
			}

			$q        .= "where ( ";
			$dep_cond = array();

			for ($i = 0, $in = count($main_sal_type); $i < $in; $i++)
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

			$q       .= ") AND p.published = '1' AND x.category_id = " . (int) JRequest::getInt('cid', 0) . " order by p.product_name ";
			$product = $this->_getList($q);

			for ($i = 0, $in = count($product); $i < $in; $i++)
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
		$db    = JFactory::getDbo();
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

					for ($i = 0, $in = count($product); $i < $in; $i++)
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
					for ($t = 0, $tn = count($tags); $t < $tn; $t++)
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
							for ($t = 0, $tn = count($tags); $t < $tn; $t++)
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
            <div id="pfsearchheader"><?php echo JText::_('COM_REDSHOP_SEARCH_RESULT'); ?></div>

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
							<?php echo $filteredlists['type' . $key]; ?></div>
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
            <div id="pfsearchheader"><?php echo JText::_('COM_REDSHOP_SEARCH_CRITERIA'); ?></div>

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
						} ?>
                    </div>
                    <div class="typevalue <?php echo $type->type_name_css; ?>">
						<?php echo $lists['type' . $key]; ?></div>
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

			for ($i = 0, $in = count($product); $i < $in; $i++)
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
		$query = "SELECT  p.product_id, p.manufacturer_id FROM #__redshop_product_category_xref AS cx "
			. ", #__redshop_product AS p "
			. "WHERE cx.category_id = " . (int) $cid . " "
			. "AND p.product_id=cx.product_id ";
		$db->setQuery($query);
		$manufacturer = $db->loadObjectList();

		$mids = array();

		for ($i = 0, $countManuf = count($manufacturer); $i < $countManuf; $i++)
		{
			if ($manufacturer[$i]->manufacturer_id > 0)
			{
				$mids[] = $manufacturer[$i]->manufacturer_id;
			}
		}

		// Sanitize ids
		JArrayHelper::toInteger($mids);

		$query = "SELECT manufacturer_id AS value,manufacturer_name AS text FROM #__redshop_manufacturer "
			. "WHERE manufacturer_id IN ('" . implode(",", $mids) . "')";
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get ajax Data
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function getajaxData()
	{
		JLoader::import('joomla.application.module.helper');
		$module         = JModuleHelper::getModule('redshop_search');
		$params         = new JRegistry($module->params);
		$limit          = $params->get('noofsearchresults');
		$app            = JFactory::getApplication();
		$keyword        = $app->input->getString('keyword', '');
		$search_type    = $app->input->getCmd('search_type', '');
		$db             = JFactory::getDbo();
		$category_id    = $app->input->getInt('category_id', 0);
		$manufacture_id = $app->input->getInt('manufacture_id', 0);

		$query = $db->getQuery(true)
			->select('p.product_id AS id, p.product_name AS value')
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'x') . ' ON x.product_id = p.product_id')
			->leftJoin($db->qn('#__redshop_category', 'c') . ' ON x.category_id = c.id')
			->where('p.published = 1')
			->group('p.product_id');

		switch ($search_type)
		{
			case 'product_name';
				$query->where($this->getSearchCondition('p.product_name', $keyword));
				break;
			case 'product_number';
				$query->where($this->getSearchCondition('p.product_number', $keyword));
				break;
			case 'name_number';
				$query->where($this->getSearchCondition(array('p.product_name', 'p.product_number'), $keyword));
				break;
			case 'product_desc';
				$query->where($this->getSearchCondition(array('p.product_s_desc', 'p.product_desc'), $keyword));
				break;
			case 'name_desc';
				$query->where($this->getSearchCondition(array('p.product_name', 'p.product_s_desc', 'p.product_desc'), $keyword));
				break;
			case 'virtual_product_num':
				$query->where($this->getSearchCondition(array('pap.property_number', 'ps.subattribute_color_number'), $keyword));
				break;
			case 'name_number_desc':
				$query->where(
					$this->getSearchCondition(
						array('p.product_name', 'p.product_number', 'p.product_desc', 'p.product_s_desc', 'pap.property_number', 'ps.subattribute_color_number'),
						$keyword
					)
				);
				break;
		}

		if ($search_type == "name_number_desc" || $search_type == "virtual_product_num")
		{
			$query->leftJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON a.product_id = p.product_id')
				->leftJoin($db->qn('#__redshop_product_attribute_property', 'pap') . ' ON pap.attribute_id = a.attribute_id')
				->leftJoin($db->qn('#__redshop_product_subattribute_color', 'ps') . ' ON ps.subattribute_id = pap.property_id');
		}

		if ($category_id != "0")
		{
			$query->where('c.id = ' . (int) $category_id);
		}

		if ($manufacture_id != "0")
		{
			$query->where('p.manufacturer_id = ' . (int) $manufacture_id);
		}

		return $db->setQuery($query, 0, $limit)->loadObjectList();
	}

	/**
	 * Get List from product
	 *
	 * @return array
	 */
	public function getListQuery()
	{
		$pk = $this->getState('filter.data', array());

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn("p.product_id"))
			->from($db->qn("#__redshop_product", "p"))
			->leftjoin(
				$db->qn("#__redshop_product_category_xref", "pc") . " ON "
				. $db->qn('p.product_id') . " = "
				. $db->qn('pc.product_id')
			)
			->where($db->qn('p.published') . ' = 1')
			->where($db->qn('p.expired') . ' = 0')
			->group($db->qn('p.product_id'));

		$productOnSale   = !empty($pk['product_on_sale']) ? $pk['product_on_sale'] : 0;
		$cid             = !empty($pk['cid']) ? $pk['cid'] : 0;
		$mid             = !empty($pk['mid']) ? $pk['mid'] : 0;
		$rootCategory    = !empty($pk['root_category']) ? $pk['root_category'] : 0;
		$categoryForSale = !empty($pk['category_for_sale']) ? $pk['category_for_sale'] : array();
		$categories      = !empty($pk['category']) ? $pk['category'] : array();
		$manufacturers   = !empty($pk['manufacturer']) ? $pk['manufacturer'] : array();
		$keyword         = !empty($pk['keyword']) ? $pk['keyword'] : "";
		$customField     = !empty($pk['custom_field']) ? $pk['custom_field'] : "";

		if (isset($pk["filterprice"]))
		{
			$min = $pk["filterprice"]['min'];
			$max = $pk["filterprice"]['max'];
		}

		if (!empty($categories))
		{
			if (in_array($rootCategory, $categories))
			{
				$key = array_search($rootCategory, $categories);
				unset($categories[$key]);
			}

			$categoryList = implode(',', $categories);
		}
        elseif (!empty($cid))
		{
			$catList = RedshopHelperCategory::getCategoryListArray($cid);

			if (!empty($catList))
			{
				foreach ($catList as $key => $cat)
				{
					$list[] = $cat->id;
				}

				array_push($list, $cid);

				$categoryList = implode(',', $list);

			}
			else
			{
				$categoryList = $cid;
			}
		}
		else
		{
			$categoryList = $cid;
		}

		$orderBy = $this->getState('order_by');

		if ($orderBy == 'pc.ordering ASC' || $orderBy == 'c.ordering ASC')
		{
			$orderBy = 'p.product_id DESC';
		}

		if (!empty($pk["filterprice"]))
		{
			$comparePrice         = $db->qn('p.product_price') . ' >= ' . $db->q($min) . ' AND ' . $db->qn('p.product_price') . ' <= ' . $db->q(($max));
			$compareDiscountPrice = $db->qn('p.discount_price') . ' >= ' . $db->q($min) . ' AND ' . $db->qn('p.discount_price') . ' <= ' . $db->q(($max));
			$saleTime             = $db->qn('p.discount_stratdate') . ' AND ' . $db->qn('p.discount_enddate');
			$query->where('( CASE WHEN( ' . $db->qn('p.product_on_sale') . ' = 1 AND UNIX_TIMESTAMP() BETWEEN '
				. $saleTime . ') THEN ('
				. $compareDiscountPrice . ') ELSE ('
				. $comparePrice . ') END )'
			);
		}

		if (!empty($keyword))
		{
			$search = $db->q('%' . $db->escape(trim($keyword, true) . '%'));
			$query->leftjoin(
				$db->qn('#__redshop_manufacturer', 'm') . ' ON '
				. $db->qn('m.manufacturer_id') . ' = '
				. $db->qn('p.manufacturer_id')
			)
				->where('(' . $db->qn('p.product_name') . ' LIKE ' . $search . ' OR ' . $db->qn('m.manufacturer_name') . ' LIKE ' . $search . ')');
		}

		$catList  = RedshopHelperCategory::getCategoryListArray($categoryForSale);
		$childCat = array($categoryForSale);

		foreach ($catList as $key => $value)
		{
			$childCat[] = $value->id;
		}

		if (!empty($customField))
		{
			$key = 0;
			$subQuery = array();

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
		}

		if (!empty($categoryForSale) && in_array($cid, $childCat))
		{
			if (!empty($categories))
			{
				foreach ($categories as $key => $value)
				{
					$query->leftjoin(
						$db->qn('#__redshop_product_category_xref', 'pc' . $key) . ' ON '
						. $db->qn('p.product_id') . ' = '
						. $db->qn('pc' . $key . '.product_id')
					)
						->where($db->qn('pc' . $key . '.category_id') . ' = ' . $db->q((int) $value))
						->where($db->qn("pc.category_id") . " = " . $db->q((int) $cid));
				}
			}
			elseif (!empty($cid) || !empty($categories))
			{
				$query->where($db->qn("pc.category_id") . " IN (" . $categoryList . ')');
			}
		}
		elseif (!empty($cid) || !empty($categories))
		{
			$query->where($db->qn("pc.category_id") . " IN (" . $categoryList . ')');
		}

		if (!empty($manufacturers))
		{
			$query->where($db->qn("p.manufacturer_id") . " IN (" . implode(',', $manufacturers) . ')');
		}
		elseif ($mid)
		{
			$query->where($db->qn("p.manufacturer_id") . "=" . $db->q((int) $mid));
		}

		if (!empty($productOnSale))
		{
			$query->where($db->qn('p.product_on_sale') . ' = ' . $db->q((int) $productOnSale));
		}

		if ($orderBy)
		{
			$query->order($db->escape($orderBy));
		}

		JPluginHelper::importPlugin('redshop_product');
		JDispatcher::getInstance()->trigger('onFilterProduct', array(&$query, $pk));

		return $query;
	}

	/**
	 * Get Items
	 *
	 * @return array
	 */
	public function getItem()
	{
		$query      = $this->getListQuery();
		$db         = JFactory::getDbo();
		$start      = $this->getState('list.start');
		$limit      = $this->getState('list.limit');
		$templateId = $this->getState('template_id');

		$redTemplate  = Redtemplate::getInstance();
		$templateArr  = $redTemplate->getTemplate("category", $templateId);
		$templateDesc = $templateArr[0]->template_desc;

		if ($templateDesc)
		{
			if (strstr($templateDesc, "{pagination}"))
			{
				$db->setQuery($query, $start, $limit);
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

		return $db->loadColumn();
	}

	/**
	 * Get pagination.
	 *
	 * @return pagination
	 */
	public function getFilterPagination()
	{
		$endlimit         = $this->getState('list.limit');
		$limitstart       = $this->getState('list.start');
		$this->pagination = new JPagination($this->getFilterTotal(), $limitstart, $endlimit);

		return $this->pagination;
	}

	/**
	 * Get total.
	 *
	 * @return total
	 */
	public function getFilterTotal()
	{
		$query       = $this->getListQuery();
		$this->total = $this->_getListCount($query);

		return $this->total;
	}
}
