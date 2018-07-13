<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @since       2.1.0
 */
class RedshopModelProduct extends RedshopModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @throws  Exception
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'product_name', 'p.product_name',
				'product_number', 'p.product_number',
				'product_price', 'p.product_price',
				'visited', 'p.visited',
				'published', 'p.published'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string $id A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.5
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('product_sort');
		$id .= ':' . $this->getState('search_field');
		$id .= ':' . $this->getState('keyword');
		$id .= ':' . $this->getState('category_id');

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'p.product_id', $direction = 'desc')
	{
		$search_field = $this->getUserStateFromRequest($this->context . '.search_field', 'search_field', 'p.product_name');
		$this->setState('search_field', $search_field);

		$keyword = $this->getUserStateFromRequest($this->context . '.keyword', 'keyword', '');
		$this->setState('keyword', $keyword);

		$category_id = $this->getUserStateFromRequest($this->context . '.category_id', 'category_id', 0);
		$this->setState('category_id', $category_id);

		$product_sort = $this->getUserStateFromRequest($this->context . '.product_sort', 'product_sort', 0);
		$this->setState('product_sort', $product_sort);

		parent::populateState($ordering, $direction);
	}

	/**
	 * @param   array|mixed $items
	 *
	 * @return  array|mixed
	 *
	 * @since   2.1.0
	 */
	protected function prepareItems($items)
	{
		if (!is_array($items))
		{
			$items = array();
		}

		// Establish the hierarchy of the menu
		$children = array();

		// First pass - collect children
		foreach ($items as $item)
		{
			$list = isset($children[$item->parent_id]) ? $children[$item->parent_id] : array();
			array_push($list, $item);
			$children[$item->parent_id] = $list;

			$item->categories  = $this->getProductCategories($item->id);
			$item->mediaDetail = $this->getProductMedias($item->id);
		}

		// Second pass - get an indent list of the items
		$items = array_values(JHTML::_('menu.treerecurse', 0, '', array(), $children, max(0, 9)));

		return $items;
	}

	/**
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	protected function getListQuery()
	{
		$db = JFactory::getDbo();

		$keyword     = $this->getState('keyword');
		$searchField = $this->getState('search_field');
		$categoryId  = $this->getState('category_id');
		$productSort = $this->getState('product_sort');

		$keyword      = addslashes($keyword);
		$arrayKeyword = array();

		$query = $db->getQuery(true);
		$query->select($db->quoteName(
			array(

				'p.product_id',
				'p.product_name',
				'p.product_name',
				'p.product_name',
				'p.product_price',
				'p.product_parent_id',
				'p.product_parent_id',
				'p.product_parent_id',
				'p.published',
				'p.visited',
				'p.manufacturer_id',
				'p.product_number',
				'p.checked_out',
				'p.checked_out_time',
				'p.discount_price',
				'p.product_template',
			),
			array(

				'id',
				'product_name',
				'treename',
				'title',
				'product_price',
				'product_parent_id',
				'parent_id',
				'parent',
				'published',
				'visited',
				'manufacturer_id',
				'product_number',
				'checked_out',
				'checked_out_time',
				'discount_price',
				'product_template',
			)
		))
			->from($db->quoteName('#__redshop_product', 'p'));

		$query->leftJoin(
			$db->quoteName('#__redshop_product_category_xref', 'x') . ' ON ' . $db->quoteName('x.product_id') . ' = ' . $db->quoteName('p.product_id')
		);
		$query->leftJoin(
			$db->quoteName('#__redshop_category', 'c') . ' ON ' . $db->quoteName('x.category_id') . ' = ' . $db->quoteName('c.id')
		);

		$query->select(array(
			'x.ordering',
			'x.category_id'
		));

		// Extra where to make sure we have at least one condition
		$query->where($db->quoteName('p.product_id') . ' > 0');

		if ($searchField == 'pa.property_number')
		{
			if (!empty($keyword))
			{
				$query->leftJoin(
					$db->quoteName('#__redshop_product_attribute', 'a') . ' ON ' . $db->quoteName('a.product_id') . ' = ' . $db->quoteName('p.product_id')
				);
				$query->leftJoin(
					$db->quoteName('#__redshop_product_attribute_property', 'pa') . ' ON ' . $db->quoteName('pa.attribute_id') . ' = ' . $db->quoteName('a.attribute_id')
				);
				$query->leftJoin(
					$db->quoteName('#__redshop_product_subattribute_color', 'ps') . ' ON ' . $db->quoteName('ps.subattribute_id') . ' = ' . $db->quoteName('pa.property_id')
				);

				$query->where(' ( '
					. $db->quoteName('pa.property_number') . ' LIKE ' . $db->quote('%' . $keyword . '%')
					. ' OR ' . $db->quoteName('ps.subattribute_color_number') . ' LIKE ' . $db->quote('%' . $keyword . '%')
					. ' ) '
				);
			}

			$query->group($db->quoteName('p.product_id'));
		}

		if (!empty($productSort))
		{
			switch ($productSort)
			{
				case 'p.published':
					$query->where($db->quoteName('p.published') . ' = 1');
					break;
				case 'p.unpublished':
					$query->where($db->quoteName('p.published') . ' = 0');
					break;
				case 'p.product_on_sale':
					$query->where($db->quoteName('p.product_on_sale') . ' = 1');
					break;
				case 'p.product_special':
					$query->where($db->quoteName('p.product_special') . ' = 1');
					break;
				case 'p.expired':
					$query->where($db->quoteName('p.expired') . ' = 1');
					break;
				case 'p.not_for_sale':
					$query->where($db->quoteName('p.not_for_sale') . ' > 0');
					break;
				case 'p.product_not_on_sale':
					$query->where($db->quoteName('p.product_on_sale') . ' = 0');
					break;
				case 'p.sold_out':
					$queryProduct      = "SELECT DISTINCT(p.id),p.attribute_set_id FROM #__redshop_product AS p ";
					$totalProducts     = $this->getDbo()->setQuery($queryProduct);
					$productsStocks    = productHelper::getInstance()->removeOutofstockProduct($totalProducts);
					$finalProductStock = $this->getFinalProductStock($productsStocks);

					if (!empty($finalProductStock))
					{
						$query->where($db->quoteName('p.product_id') . ' IN ( ' . implode(',', $finalProductStock) . ' )');
					}

					break;
			}
		}

		if (trim($keyword) != '')
		{
			$arrayKeyword = preg_split("/[\s-]+/", $keyword);
		}

		if ($searchField != 'pa.property_number' && !empty($arrayKeyword))
		{
			$condition = array();

			foreach ($arrayKeyword as $index => $keyword)
			{
				if ($searchField == 'p.name_number')
				{
					$condition [] = $db->quoteName('p.product_name') . ' LIKE ' . $db->quote('%' . $keyword . '%');
					$condition [] = $db->quoteName('p.product_number') . ' LIKE ' . $db->quote('%' . $keyword . '%');
				}
				else
				{
					if ($searchField == 'c.category_name')
					{
						$searchField = 'c.name';
					}

					$condition [] = $db->quoteName($searchField) . ' LIKE ' . $db->quote('%' . $keyword . '%');
				}
			}

			$query->where('  ( ' . implode(' OR ', $condition) . ' ) ');
		}

		if ($this->getState('filter.product_number'))
		{
			$query->where($db->quoteName('p.product_number') . ' = ' . $db->quote($db->escape($this->getState('filter.product_number'))));
		}

		if ($categoryId)
		{
			$query->where($db->quoteName('c.id') . ' = ' . (int) $categoryId);
		}

		$filter_order_Dir = $this->getState('list.direction');

		if ($categoryId)
		{
			$query->order($db->quoteName($this->getState('list.ordering', 'x.ordering')) . ' ' . $filter_order_Dir);

			return $query;
		}

		$query->order($db->quoteName($this->getState('list.ordering', 'p.product_id')) . ' ' . $filter_order_Dir);

		return $query;
	}

	public function getFinalProductStock($products)
	{
		if (empty($products))
		{
			return;
		}

		$product = array();

		foreach ($products as $productStock)
		{
			$product[] = $productStock->product_id;
		}

		$query_prd = "SELECT DISTINCT(p.product_id) FROM #__redshop_product AS p WHERE p.product_id NOT IN(" . implode(',', $product) . ")";

		return $this->_db->setQuery($query_prd)->loadColumn();
	}

	protected function getProductMedias($pid)
	{
		$query = 'SELECT * FROM #__redshop_media  WHERE section_id ="' . $pid . '" AND media_section = "product"';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	protected function getProductCategories($pid)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('name'))
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $pid))
			->order($db->qn('c.name'));

		return $db->setQuery($query)->loadObjectlist();
	}

	/**
	 * @param   integer $template_id Template ID
	 * @param   integer $product_id  Product ID
	 * @param   integer $section     Section
	 *
	 * @return  array|string|void
	 * @throws  Exception
	 */
	public function product_template($template_id, $product_id, $section)
	{
		if ($section == RedshopHelperExtrafields::SECTION_PRODUCT || $section == RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD)
		{
			$template_desc = RedshopHelperTemplate::getTemplate("product", $template_id);
		}
		else
		{
			$template_desc = RedshopHelperTemplate::getTemplate("category", $template_id);
		}

		if (empty($template_desc))
		{
			return;
		}

		$template = $template_desc[0]->template_desc;
		$str      = array();
		$sec      = explode(',', $section);

		for ($t = 0, $tn = count($sec); $t < $tn; $t++)
		{
			$inArr[] = "'" . $sec[$t] . "'";
		}

		$in = implode(',', $inArr);
		$q  = "SELECT field_name,field_type,field_section from #__redshop_fields where field_section in (" . $in . ") ";
		$this->_db->setQuery($q);
		$fields = $this->_db->loadObjectlist();

		for ($i = 0, $in = count($fields); $i < $in; $i++)
		{
			if (strstr($template, "{" . $fields[$i]->field_name . "}"))
			{
				if ($fields[$i]->field_section == 12)
				{
					if ($fields[$i]->field_type == 15)
					{
						$str[] = $fields[$i]->field_name;
					}
				}
				else
				{
					$str[] = $fields[$i]->field_name;
				}
			}
		}

		$list_field = array();

		if (count($str) > 0)
		{
			$dbname = implode(",", $str);

			for ($t = 0, $tn = count($sec); $t < $tn; $t++)
			{
				$list_field[] = RedshopHelperExtrafields::listAllField($sec[$t], $product_id, $dbname);
			}
		}

		if (count($list_field) > 0)
		{
			return $list_field;
		}

		return "";
	}

	/**
	 * @param   array $data Array of data
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public function assignTemplate($data)
	{
		$cid              = $data['cid'];
		$product_template = $data['product_template'];

		if (empty($cid))
		{
			return true;
		}

		$db    = JFactory::getDbo();
		$query = 'UPDATE #__redshop_product' . ' SET `product_template` = "'
			. intval($product_template) . '" ' . ' WHERE product_id IN ( ' . implode(',', $cid) . ' )';

		if (!$db->setQuery($query)->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return true;
	}

	/*
				 * Save product ordering
	 * @params: $cid - array , $order-array
	 * $cid= product ids
	 * $order = product current ordring
	 * @return: boolean
	 */
	public function saveorder($cid = array(), $order = 0)
	{
		$category_id_my = $this->getState('category_id');

		$orderarray = array();

		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			// Set product id as key AND order as value
			$orderarray[$cid[$i]] = $order[$i];
		}

		// Sorting array using value ( order )
		asort($orderarray);
		$i = 1;

		if (count($orderarray) > 0)
		{
			foreach ($orderarray as $productid => $order)
			{
				if ($order >= 0)
				{
					// Update ordering
					$query = 'UPDATE #__redshop_product_category_xref' . ' SET ordering = ' . (int) $i
						. ' WHERE product_id=' . $productid . ' AND category_id = ' . $category_id_my;
					$this->_db->setQuery($query);
					$this->_db->execute();
				}

				$i++;
			}
		}

		return true;
	}

	/**
	 * Method for save discount for list of product Ids
	 *
	 * @param   array $productIds     Product Id
	 * @param   array $discountPrices List of discount price.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.4
	 */
	public function saveDiscountPrices($productIds = array(), $discountPrices = array())
	{
		if (empty($productIds))
		{
			return false;
		}

		$productIds = ArrayHelper::toInteger($productIds);
		$case       = array();
		$db         = $this->_db;

		foreach ($productIds as $index => $productId)
		{
			// Skip if discount price doesn't populate
			if (!isset($discountPrices[$index]))
			{
				continue;
			}

			$price = (float) $discountPrices[$index];

			$case[] = 'WHEN ' . $db->qn('product_id') . ' = ' . $productId . ' AND ' . $db->qn('product_price') . ' >= ' . $price
				. ' THEN ' . $db->quote($price);
		}

		if (empty($case))
		{
			return false;
		}

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_product'))
			->set($db->qn('discount_price') . ' = CASE ' . implode(' ', $case) . ' ELSE ' . $db->qn('discount_price') . ' END');

		return $db->setQuery($query)->execute();
	}

	/**
	 * Method for save discount for list of product Ids
	 *
	 * @param   array $productIds Product Id
	 * @param   array $prices     List of discount price.
	 *
	 * @return  boolean
	 *
	 * @since   2.0.4
	 */
	public function savePrices($productIds = array(), $prices = array())
	{
		if (empty($productIds))
		{
			return false;
		}

		$productIds = ArrayHelper::toInteger($productIds);
		$case       = array();
		$db         = $this->_db;

		foreach ($productIds as $index => $productId)
		{
			// Skip if discount price doesn't populate
			if (!isset($prices[$index]))
			{
				continue;
			}

			$price = (float) $prices[$index];

			$case[] = 'WHEN ' . $db->qn('product_id') . ' = ' . $productId . ' THEN ' . $db->quote($price);
		}

		if (empty($case))
		{
			return false;
		}

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_product'))
			->set($db->qn('product_price') . ' = CASE ' . implode(' ', $case) . ' ELSE ' . $db->qn('product_price') . ' END');

		return $db->setQuery($query)->execute();
	}
}
