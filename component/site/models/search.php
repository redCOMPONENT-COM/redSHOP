<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.model');

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/category.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';

/**
 * Class searchModelsearch
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class searchModelsearch extends JModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	public $_table_prefix = null;

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
		$module         = JModuleHelper::getModule('redshop_search');
		$module_params  = new JRegistry($module->params);
		$perpageproduct = $module_params->get('productperpage', 5);

		if ($layout == 'default')
			$limit = $perpageproduct;
		elseif ($layout == 'productonsale')
			$limit = $params->get('productlimit', 5);
		else
			$limit = $params->get('maxcategory', 5);
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

		$redTemplate = new Redtemplate;

		if (empty($this->_data))
		{
			$query = $this->_buildQuery($post);
			$this->_db->setQuery($query);

			$template = $this->getCategoryTemplet();

			for ($i = 0; $i < count($template); $i++)
			{
				$template[$i]->template_desc = $redTemplate->readtemplateFile($template[$i]->template_section, $template[$i]->template_name);
			}

			if (count($template) > 0)
			{
				if (strstr($template[0]->template_desc, "{show_all_products_in_category}"))
				{
					$this->_data = $this->_getList($query);
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

					$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
				}
				elseif ($this->getState('productlimit') > 0)
				{
					$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('productlimit'));
				}
				else
				{
					$this->_data = $this->_getList($query);
				}
			}
			else
			{
				$this->_data = $this->_getList($query);
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
		$context      = 'search';
		$productlimit = $this->getstate('productlimit');

		$layout = JRequest::getCmd('layout', 'default');

		if (empty($this->_total))
		{
			$query = $this->_buildQuery();

			$this->_total = $this->_getListCount($query);

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
			$this->_pagination = new redPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function _buildQuery($manudata = 0)
	{
		$app = JFactory::getApplication();
		$context = 'search';

		$keyword = $app->getUserStateFromRequest($context . 'keyword', 'keyword', '');

		$defaultSearchType = '';

		if (!empty($manudata['search_type']))
		{
			$defaultSearchType     = $manudata['search_type'];
			$defaultSearchType_tmp = $manudata['search_type'];
		}

		$product_s_desc_srch = '';

		if ($defaultSearchType == "")
		{
			$defaultSearchType = 'product_name';
		}

		if ($defaultSearchType == "name_number")
		{
			$defaultSearchField = "name_number";
			$defaultSearchType  = '(p.product_name LIKE "%' . $keyword . '%" OR p.product_number LIKE "%' . $keyword . '%")';
		}
		elseif ($defaultSearchType == "name_desc")
		{
			$defaultSearchField = "name_desc";
			$defaultSearchType  = '(p.product_name LIKE "%' . $keyword
				. '%" OR p.product_desc LIKE "%' . $keyword
				. '%" OR  p.product_s_desc LIKE "%' . $keyword . '%")';
		}
		elseif ($defaultSearchType == "virtual_product_num")
		{
			$defaultSearchField = "virtual_product_num";
			$defaultSearchType  = '(pa.property_number LIKE "%' . $keyword . '%" OR  ps.subattribute_color_number LIKE "%' . $keyword . '%")';
		}
		elseif ($defaultSearchType == "name_number_desc")
		{
			$defaultSearchType = '(p.product_name LIKE "%' . $keyword
				. '%" OR p.product_number LIKE "%' . $keyword
				. '%" OR p.product_desc LIKE "%' . $keyword
				. '%" OR  p.product_s_desc LIKE "%' . $keyword
				. '%" OR  pa.property_number LIKE "%' . $keyword
				. '%" OR  ps.subattribute_color_number LIKE "%' . $keyword
				. '%")';
		}
		elseif ($defaultSearchType == "product_desc")
		{
			$defaultSearchField = $defaultSearchType;
			$defaultSearchType  = '(p.' . $defaultSearchType . ' LIKE "%' . $keyword . '%" OR  p.product_s_desc LIKE "%' . $keyword . '%" )';
		}
		elseif ($defaultSearchType == "product_name")
		{
			$main_sp_name = explode(" ", $keyword);

			$defaultSearchField = $defaultSearchType;

			for ($f = 0; $f < count($main_sp_name); $f++)
			{
				$defaultSearchType1[] = " p.product_name LIKE '%" . $main_sp_name[$f] . "%' ";
			}

			$defaultSearchType = "(" . implode("AND", $defaultSearchType1) . ")";
		}
		elseif ($defaultSearchType == "product_number")
		{
			$defaultSearchField = $defaultSearchType;
			$defaultSearchType  = '(p.product_number LIKE "%' . $keyword . '%")';
		}

		if ($product_s_desc_srch != '')
		{
			$defaultSearchType .= " OR (" . $product_s_desc_srch . ") ";
		}

		$redconfig  = $app->getParams();
		$getorderby = JRequest::getVar('order_by', '');

		$order_by = $getorderby;

		if ($getorderby == "")
		{
			$order_by = $redconfig->get('order_by', DEFAULT_PRODUCT_ORDERING_METHOD);
		}

		if ($order_by == 'pc.ordering ASC' || $order_by == 'c.ordering ASC')
		{
			$order_by = 'p.product_id DESC';
		}

		$layout = JRequest::getVar('layout', 'default');

		$category_helper = new product_category;
		$producthelper   = new producthelper;

		$manufacture_id = JRequest::getInt('manufacture_id', 0);
		$category_id    = JRequest::getInt('category_id', 0);

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

		if ($cat_group)
		{
			$cat_group = join(',', $cat_group);
		}
		else
		{
			$cat_group = $category_id;
		}

		$params = JComponentHelper::getParams('com_redshop');

		$menu = $app->getMenu();
		$item = $menu->getActive();

		$days        = $item->query['newproduct'];
		$today       = date('Y-m-d H:i:s', time());
		$days_before = date('Y-m-d H:i:s', time() - ($days * 60 * 60 * 24));
		$aclProducts = $producthelper->loadAclProducts();

		$whereaclProduct = "";

		// Shopper group - choose from manufactures Start
		$rsUserhelper               = new rsUserhelper;
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			$whereaclProduct .= " AND p.manufacturer_id IN (" . $shopper_group_manufactures . ") ";
		}

		// Shopper group - choose from manufactures End
		if ($aclProducts != "")
		{
			$whereaclProduct .= " AND p.product_id IN (" . $aclProducts . ")  ";
		}

		if ($layout == 'productonsale')
		{
			$categoryid = $item->params->get('categorytemplate');
			$cat_array  = "";
			$left_join  = "";

			if ($categoryid)
			{
				$cat_main       = $category_helper->getCategoryTree($categoryid);
				$cat_group_main = array();

				for ($j = 0; $j < count($cat_main); $j++)
				{
					$cat_group_main[$j] = $cat_main[$j]->category_id;
				}

				$cat_group_main[] = $categoryid;
				$cat_group_main   = join(',', $cat_group_main);

				$cat_array = " AND pcx.category_id in (" . $cat_group_main . ") AND pcx.product_id=p.product_id ";
				$left_join = " LEFT JOIN " . $this->_table_prefix . "product_category_xref pcx ON pcx.product_id=p.product_id ";
			}

			$query = " SELECT * FROM " . $this->_table_prefix . "product AS p "
				. $left_join
				. "WHERE p.published = 1 "
				. "AND p.product_on_sale=1 "
				. "AND p.expired=0 "
				. "AND p.product_parent_id=0 "
				. $whereaclProduct . $cat_array
				. "order by " . $order_by;
		}
		elseif ($layout == 'featuredproduct')
		{
			$query = " SELECT * FROM " . $this->_table_prefix . "product AS p "
				. "WHERE p.published = 1 "
				. "AND p.product_special=1 "
				. $whereaclProduct
				. "order by " . $order_by;
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
			$cat_group_main   = join(',', $cat_group_main);

			$extracond = "";

			if ($catid)
			{
				$extracond = " AND pcx.category_id in (" . $cat_group_main . ") AND pcx.product_id=p.product_id ";
			}

			$query = " SELECT distinct p.* "
				. " FROM " . $this->_table_prefix . "product p ," . $this->_table_prefix . "product_category_xref pcx "
				. "WHERE p.published = 1  "
				. "and  p.publish_date BETWEEN '" . $days_before . "' AND '" . $today . "' AND p.expired = 0  AND p.product_parent_id = 0 "
				. $whereaclProduct . $extracond
				. "order by " . $order_by;
		}
		elseif ($layout == 'redfilter')
		{
			// Get products for filtering
			$products = $this->getRedFilterProduct();

			$query = " SELECT * "
				. " FROM " . $this->_table_prefix . "product as p "
				. "WHERE p.published = 1 AND p.expired = 0 " . $whereaclProduct;

			if ($products != "")
				$query .= "AND p.product_id IN ( " . $products . " )  ";
			else
				$query .= "AND p.product_id IN ( '" . $products . "' )  ";
			$query .= "order by " . $order_by;
		}
		else
		{
			if ($manufacture_id == 0)
			{
				if (!empty($manudata['manufacturer_id']))
					$manufacture_id = $manudata['manufacturer_id'];
			}

			$query = "SELECT distinct p.* "
				. " FROM  " . $this->_table_prefix . "product as p  ";

			if ($category_id != 0)
			{
				$query .= " LEFT JOIN  " . $this->_table_prefix . "product_category_xref as pcx on p.product_id = pcx.product_id ";
			}

			if ($defaultSearchType_tmp == "name_number_desc" || $defaultSearchType_tmp == "virtual_product_num")
			{
				$query .= "LEFT JOIN " . $this->_table_prefix . "product_attribute AS a ON a.product_id = p.product_id "
					. "LEFT JOIN " . $this->_table_prefix . "product_attribute_property AS pa ON pa.attribute_id = a.attribute_id "
					. "LEFT JOIN " . $this->_table_prefix . "product_subattribute_color AS ps ON ps.subattribute_id = pa.property_id ";
			}

			$query .= " WHERE 1=1 AND p.expired = 0  " . $whereaclProduct;

			if ($category_id != 0)
			{
				$query .= " AND pcx.category_id in (" . $cat_group . ")";
			}

			if ($manufacture_id != 0)
			{
				$query .= " AND p.manufacturer_id =" . (int) $manufacture_id;
			}

			$query .= " AND " . $defaultSearchType
				. " AND p.published = 1"
				. " order by " . $order_by;
		}

		return $query;
	}

	public function _buildContentOrderBy()
	{
		global $context;

		$app = JFactory::getApplication();

		$filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', 'order_id');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');

		$orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

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
			$and .= " AND c.category_id = '" . $cid . "' ";
		}

		if ($templateid != 0)
		{
			$and .= " AND t.template_id = '" . $templateid . "' ";
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
					$q .= " LEFT JOIN #__redproductfinder_association_tag AS t" . $i . " ON t" . $i . ".association_id=ta.association_id ";
			}

			$q .= "where ( ";
			$dep_cond = array();

			for ($i = 0; $i < count($main_sal_type); $i++)
			{
				$chk_q = "";

				// Search for checkboxes
				if ($i != 0)
					$chk_q .= "t" . $i . ".tag_id='" . $main_sal_tag[$i] . "' ";
				else
					$chk_q .= "ta.tag_id='" . $main_sal_tag[$i] . "' ";

				if ($chk_q != "")
					$dep_cond[] = " ( " . $chk_q . " ) ";
			}

			if (count($dep_cond) <= 0)
				$dep_cond[] = "1=1";
			$q .= implode(" AND ", $dep_cond);

			$q .= ") AND p.published = '1' AND x.category_id='" . JRequest::getVar('cid') . "' order by p.product_name ";
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
					WHERE  rat.`type_id` IN (" . $lasttypeid . ") ";

					$query .= "AND  rat.`tag_id` IN (" . $lasttagid . ") ";

					$product = $this->_getList($query);

					$products = array();

					for ($i = 0; $i < count($product); $i++)
					{
						$products[] = $product[$i]->product_id;
					}

					$productids = implode(",", $products);
				}

				$q = "SELECT DISTINCT j.tag_id as tagid ,ra.product_id,count(ra.product_id) as ptotal ,CONCAT(j.tag_id,'.',j.type_id) AS tag_id, t.tag_name
			FROM ((#__redproductfinder_tag_type j, #__redproductfinder_tags t )
			LEFT JOIN #__redproductfinder_association_tag as rat ON  t.`id` = rat.`tag_id`)
			LEFT JOIN #__redproductfinder_associations as ra ON ra.id = rat.association_id
			WHERE j.tag_id = t.id
			AND j.type_id = " . $id . "  ";

				if ($productids != "")
					$q .= " AND ra.product_id  IN ( " . $productids . " ) ";
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
							WHERE type_id = '" . $type_id[1] . "' AND tag_id ='" . $type_id[0] . "' AND rp.published = 1";

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
		$db           = JFactory::getDBO();
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
				. "WHERE rat.type_id IN (" . $lasttypeid . ") "
				. "AND rat.tag_id IN (" . $lasttagid . ") ";
			$db->setQuery($query);
			$product  = $db->loadObjectList();
			$products = array();

			for ($i = 0; $i < count($product); $i++)
			{
				$products[] = $product[$i]->product_id;
			}

			$productids = implode(",", $products);
		}

		$q = "SELECT DISTINCT j.tag_id AS tagid,ra.product_id,count(ra.product_id) AS ptotal, "
			. "CONCAT(j.tag_id,'.',j.type_id) AS tag_id, t.tag_name "
			. "FROM ((#__redproductfinder_tag_type j, #__redproductfinder_tags t ) "
			. "LEFT JOIN #__redproductfinder_association_tag as rat ON  t.`id` = rat.`tag_id`) "
			. "LEFT JOIN #__redproductfinder_associations as ra ON ra.id = rat.association_id "
			. "WHERE j.tag_id = t.id "
			. "AND j.type_id = " . $id . " ";

		if ($productids != "")
		{
			$q .= " AND ra.product_id IN (" . $productids . ") ";
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
		$db    = JFactory::getDBO();
		$query = "SELECT  p.product_id, p.manufacturer_id FROM " . $this->_table_prefix . "product_category_xref AS cx "
			. ", " . $this->_table_prefix . "product AS p "
			. "WHERE cx.category_id='" . $cid . "' "
			. "AND p.product_id=cx.product_id ";
		$db->setQuery($query);
		$manufacturer = $db->loadObjectList();

		$mids = array();

		for ($i = 0; $i < count($manufacturer); $i++)
		{
			if ($manufacturer[$i]->manufacturer_id > 0)
				$mids[] = $manufacturer[$i]->manufacturer_id;
		}

		$mid = implode(",", $mids);

		$query = "SELECT manufacturer_id AS value,manufacturer_name AS text FROM " . $this->_table_prefix . "manufacturer "
			. "WHERE manufacturer_id IN ('" . $mid . "')";
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

		$category_id    = JRequest::getInt('category_id');
		$manufacture_id = JRequest::getInt('manufacture_id');

		$where = array();

		if ($search_type == 'product_name')
		{
			$where[] = "p.product_name LIKE('%" . $keyword . "%')";
		}
		elseif ($search_type == 'product_number')
		{
			$where[] = "p.product_number LIKE('%" . $keyword . "%')";
		}
		elseif ($search_type == 'name_number')
		{
			$where[] = "p.product_name LIKE('%" . $keyword . "%') or p.product_number LIKE('%" . $keyword . "%')";
		}
		elseif ($search_type == 'product_desc')
		{
			$where[] = "p.product_s_desc LIKE('%" . $keyword . "%') or p.product_desc LIKE('%" . $keyword . "%')";
		}
		elseif ($search_type == 'name_desc')
		{
			$where[] = "p.product_name LIKE('%" . $keyword . "%') or p.product_s_desc LIKE('%" . $keyword . "%') or p.product_desc LIKE('%" . $keyword . "%')";
		}
		elseif ($search_type == 'name_number_desc')
		{
			$where[] = "p.product_name LIKE('%"
				. $keyword . "%') or p.product_number LIKE('%"
				. $keyword . "%') or p.product_s_desc LIKE('%"
				. $keyword . "%') or p.product_desc LIKE('%"
				. $keyword . "%')";
		}

		if ($category_id != "0")
		{
			$where[] = "c.category_id = '" . $category_id . "'";
		}

		if ($manufacture_id != "0")
		{
			$where[] = "p.manufacturer_id = '" . $manufacture_id . "'";
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
