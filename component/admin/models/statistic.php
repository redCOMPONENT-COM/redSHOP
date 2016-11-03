<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop statistics Model
 *
 * @package     Redshop.Backend
 * @subpackage  Models.Attributes
 * @since       2.0.0.2
 */
class RedshopModelStatistic extends RedshopModelList
{
	public $_filteroption = null;

	public $_typeoption = null;

	/**
	 * Constructor
	 *
	 * @deprecated  2.0.0.3
	 */
	public function __construct()
	{
		parent::__construct();
		$input                 = JFactory::getApplication()->input;
		$this->_filteroption   = $input->getInt('filteroption', 0);
		$this->_typeoption     = $input->getInt('typeoption', 2);
		$this->filterStartDate = $input->getString('filter_start_date', '');
		$this->filterEndDate   = $input->getString('filter_end_date', '');
		$this->filterDateLabel = $input->getString('filter_date_label', '');

		if (!$this->_filteroption && $input->getString('view', '') == "")
		{
			$this->_filteroption = 1;
		}
	}

	/**
	 * get most popular product data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getMostPopular()
	{
		$today   = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result  = array();
		$db      = $this->getDbo();
		$query   = $db->getQuery(true)
			->select($db->qn('pv.created_date'))
			->from($db->qn('#__redshop_pageviewer', 'pv'))
			->where($db->qn('pv.section') . ' = ' . $db->q('product'))
			->order($db->qn('pv.created_date') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->select('FROM_UNIXTIME(' . $db->qn('pv.created_date') . ', "' . $formate . '") AS viewdate')
			->select($db->qn('p.product_id'))
			->select($db->qn('p.product_name'))
			->select($db->qn('p.product_price'))
			->select('COUNT(*) AS visited')
			->from($db->qn('#__redshop_pageviewer', 'pv'))
			->leftjoin(
				$db->qn('#__redshop_product', 'p') . ' ON '
				. $db->qn('p.product_id') . ' = '
				. $db->qn('pv.section_id')
			)
			->where($db->qn('pv.section') . ' = ' . $db->q('product'))
			->where($db->qn('pv.section_id') . ' != 0')
			->group($db->qn('pv.section_id'))
			->order($db->qn('visited') . ' DESC');

		$mostPopular = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('pv.created_date') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('pv.created_date') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$mostPopular = $result;
			}
		}

		return $mostPopular;
	}

	/**
	 * get product best seller data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getBestSellers()
	{
		$today   = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result  = array();
		$db      = $this->getDbo();
		$query   = $db->getQuery(true)
			->select($db->qn('cdate'))
			->from($db->qn('#__redshop_order_item'))
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->select('COUNT(oi.product_id) AS totalproduct');

		if ($this->_typeoption == 2)
		{
			$query = $db->getQuery(true)
				->select('SUM(oi.product_quantity) AS totalproduct');
		}

		$query->select('FROM_UNIXTIME(' . $db->qn('oi.cdate') . ', "' . $formate . '") AS viewdate')
			->select($db->qn('p.product_id'))
			->select($db->qn('p.product_name'))
			->select($db->qn('p.product_price'))
			->from($db->qn('#__redshop_order_item', 'oi'))
			->leftjoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('oi.product_id'))
			->group($db->qn('oi.product_id'))
			->order($db->qn('totalproduct') . ' DESC');

		$bestSallers = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('oi.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('oi.cdate') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$bestSallers = $result;
			}
		}

		return $bestSallers;
	}

	/**
	 * get new product data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getNewProducts()
	{
		$today   = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result  = array();
		$db      = $this->getDbo();
		$query   = $db->getQuery(true)
			->select($db->qn('publish_date'))
			->from($db->qn('#__redshop_product'))
			->order($db->qn('publish_date') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query->getQuery(true)
			->select($db->qn('product_id'))
			->select($db->qn('product_name'))
			->select($db->qn('product_price'))
			->select('FROM_UNIXTIME(' . $db->qn('publish_date') . ', "' . $formate . '") AS viewdate')
			->from($db->qn('#__redshop_product'))
			->order($db->qn('publish_date') . ' DESC');

		$newProducts = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while (strtotime($minDate) < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('publish_date') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('publish_date') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$newProducts = $result;
			}
		}

		return $newProducts;
	}

	/**
	 * get new orders data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getNewOrders()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db      = $this->getDbo();
		$query   = $db->getQuery(true)
			->select($db->qn('cdate'))
			->from($db->qn('#__redshop_orders'))
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->select($db->qn('uf.firstname'))
			->select($db->qn('uf.lastname'))
			->select($db->qn('o.order_id'))
			->select($db->qn('o.order_total'))
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ', "' . $formate . '") AS viewdate')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' LIKE ' . $db->q('BT'))
			->order($db->qn('o.cdate') . ' DESC');

		$newOrders = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$newOrders = $result;
			}
		}

		return $newOrders;
	}

	/**
	 * get Order data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getOrders()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDBO();
		$query = $db->getQuery(true)
			->select('FROM_UNIXTIME(cdate,"' . $format . '") AS viewdate')
			->select('SUM(order_total) AS order_total')
			->select('COUNT(*) AS count')
			->from($db->qn('#__redshop_orders'))
			->order($db->qn('cdate') . ' DESC')
			->group($db->qn('viewdate'));

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('cdate') . ' > ' . $db->q(strtotime($this->filterStartDate)))
			->where($db->qn('cdate') . ' <= ' . $db->q(strtotime($this->filterEndDate) + 86400));
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * get Order data for export
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function exportOrder()
	{
		$db = $this->getDBO();
		$query = $db->getQuery(true)
			->select('DISTINCT(o.cdate)')
			->select('o.*')
			->select('ouf.*')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_order_users_info', 'ouf') . ' ON ' . $db->qn('o.order_id') . ' = ' . $db->qn('ouf.order_id'))
			->where($db->qn('ouf.address_type') . ' = ' . $db->q('BT'))
			->order($db->qn('o.order_id') . ' DESC');

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($this->filterStartDate)))
				->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($this->filterEndDate) + 86400));
		}

		return $this->_getList($query);
	}

	/**
	 * Count product by order
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function countProductByOrder()
	{
		$db = $this->getDBO();
		$query = $db->getQuery(true)
			->select($db->qn('order_id'))
			->select('COUNT(order_item_id) AS noproduct')
			->from($db->qn('#__redshop_order_item'))
			->group($db->qn('order_id'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * get Product data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getProducts()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDBO();
		$query = $db->getQuery(true)
			->select('DATE_FORMAT(p.publish_date,"' . $format . '") AS viewdate')
			->select('p.*')
			->select('COUNT(*) AS count')
			->select('m.manufacturer_name')
			->from($db->qn('#__redshop_product', 'p'))
			->leftjoin($db->qn('#__redshop_manufacturer', 'm') . ' ON ' . $db->qn('m.manufacturer_id') . ' = ' . $db->qn('p.manufacturer_id'))
			->order($db->qn('p.publish_date') . ' DESC')
			->group($db->qn('product_id'));

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('p.publish_date') . ' > ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterStartDate))))
				->where($db->qn('p.publish_date') . ' <= ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterEndDate) + 86400)));
		}

		$products = $db->setQuery($query)->loadObjectList();

		$query = $db->getQuery(true)
			->select('SUM(product_final_price) AS total_sale')
			->select('COUNT(*) AS unit_sold')
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_order_item'))
			->where($db->qn('order_status') . ' = ' . $db->q('S'))
			->group($db->qn('product_id'));

		$items = $db->setQuery($query)->loadObjectList();

		foreach ($products as $key => $product)
		{
			$products[$key]->unit_sold  = 0;
			$products[$key]->total_sale = 0;

			foreach ($items as $item)
			{
				if ($product->product_id == $item->product_id)
				{
					$products[$key]->unit_sold  = $item->unit_sold;
					$products[$key]->total_sale = $item->total_sale;
				}
			}
		}

		return $products;
	}

	/**
	 * get Product variants data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getProductVariants()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDBO();
		$query = $db->getQuery(true)
			->select('FROM_UNIXTIME(oi.cdate,"' . $format . '") AS viewdate')
			->select('oai.*')
			->select($db->qn('p.product_name'))
			->select($db->qn('p.product_id'))
			->select($db->qn('pap.property_number'))
			->from($db->qn('#__redshop_order_attribute_item', 'oai'))
			->leftjoin($db->qn('#__redshop_order_item', 'oi') . ' ON ' . $db->qn('oai.order_item_id') . ' = ' . $db->qn('oi.order_item_id'))
			->leftjoin($db->qn('#__redshop_product_attribute_property', 'pap') . ' ON ' . $db->qn('oai.section_id') . ' = ' . $db->qn('property_id'))
			->leftjoin($db->qn('#__redshop_product', 'p') . ' ON ' . $db->qn('oi.product_id') . ' = ' . $db->qn('p.product_id'))
			->where($db->qn('oai.section') . ' = ' . $db->q('property'))
			->order($db->qn('oi.order_item_id') . ' DESC,' . $db->qn('oai.parent_section_id') . ' ASC');

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('p.publish_date') . ' > ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterStartDate))))
				->where($db->qn('p.publish_date') . ' <= ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterEndDate) + 86400)));
		}

		$variants = $db->setQuery($query)->loadObjectList();
		$data = array();
		$result = array();

		foreach ($variants as $key => $variant)
		{
			$data[$variant->order_item_id]['attribute'][] = $variant->section_name;
			$data[$variant->order_item_id]['attribute_sku'][] = $variant->property_number;
			$data[$variant->order_item_id]['viewdate'] = $variant->viewdate;
			$data[$variant->order_item_id]['product_name'] = $variant->product_name;
			$data[$variant->order_item_id]['product_id'] = $variant->product_id;
		}

		foreach ($data as $key => $value)
		{
			$result[$key]['product_attribute'] = implode(' - ', $value['attribute']);
			$result[$key]['product_attribute_sku'] = implode(' - ', $value['attribute_sku']);
			$result[$key]['viewdate'] = $value['viewdate'];
			$result[$key]['product_name'] = $value['product_name'];
			$result[$key]['product_id'] = $value['product_id'];
		}

		$query = $db->getQuery(true)
			->select('SUM(product_final_price) AS total_sale')
			->select('COUNT(*) AS unit_sold')
			->select($db->qn('order_item_id'))
			->from($db->qn('#__redshop_order_item'))
			->where($db->qn('order_status') . ' = ' . $db->q('S'))
			->group($db->qn('order_item_id'));

		$items = $db->setQuery($query)->loadObjectList();

		foreach ($result as $itemId => $value)
		{
			$result[$itemId]['unit_sold']  = 0;
			$result[$itemId]['total_sale'] = 0;

			foreach ($items as $item)
			{
				if ($itemId == $item->order_item_id)
				{
					$result[$itemId]['unit_sold']  = $item->unit_sold;
					$result[$itemId]['total_sale'] = $item->total_sale;
				}
			}
		}

		return $result;
	}

	/**
	 * get Customer data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getCustomers()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDBO();
		$query = $db->getQuery(true)
			->select('DATE_FORMAT(u.registerDate,"' . $format . '") AS viewdate')
			->select($db->qn('ui.user_email'))
			->select($db->qn('ui.firstname'))
			->select($db->qn('ui.lastname'))
			->select($db->qn('ui.users_info_id'))
			->select($db->qn('ui.user_id'))
			->from($db->qn('#__redshop_users_info', 'ui'))
			->leftjoin($db->qn('#__users', 'u') . ' ON ' . $db->qn('u.id') . ' = ' . $db->qn('ui.user_id'))
			->where($db->qn('ui.address_type') . ' = ' . $db->q('BT'))
			->order($db->qn('u.registerDate') . ' DESC')
			->group($db->qn('ui.users_info_id'));

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('u.registerDate') . ' > ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterStartDate))))
				->where($db->qn('u.registerDate') . ' <= ' . $db->q(date('Y-m-d H:i:s', strtotime($this->filterEndDate) + 86400)));
		}

		$customers = $db->setQuery($query)->loadObjectList();

		$query = $db->getQuery(true)
			->select('COUNT(*) AS count')
			->select('SUM(order_total) AS total_sale')
			->select($db->qn('user_info_id'))
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_payment_status') . ' = ' . $db->q('Paid'))
			->group($db->qn('user_info_id'));

		$orderCount = $db->setQuery($query)->loadObjectList();

		foreach ($customers as $key => $customer)
		{
			$customers[$key]->total_sale = 0;
			$customers[$key]->count = 0;

			foreach ($orderCount as $value)
			{
				if ($customer->users_info_id == $value->user_info_id)
				{
					$customers[$key]->total_sale = $value->total_sale;
					$customers[$key]->count = $value->count;
				}
			}
		}

		return $customers;
	}

	/**
	 * get Quotation data for statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getQuotations()
	{
		$format = $this->getDateFormat();
		$db     = $this->getDBO();
		$query = $db->getQuery(true)
			->select('FROM_UNIXTIME(quotation_cdate,"' . $format . '") AS viewdate')
			->select('SUM(quotation_total) AS quotation_total')
			->select('COUNT(*) AS count')
			->from($db->qn('#__redshop_quotation'))
			->where($db->qn('quotation_status') . ' = 5')
			->order($db->qn('quotation_cdate') . ' DESC')
			->group($db->qn('viewdate'));

		if (!empty($this->filterStartDate) && !empty($this->filterEndDate))
		{
			$query->where($db->qn('quotation_cdate') . ' > ' . $db->q(strtotime($this->filterStartDate)))
			->where($db->qn('quotation_cdate') . ' <= ' . $db->q(strtotime($this->filterEndDate) + 86400));
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Get total turnover for cpanel view
	 * This is an optimized version of original getTotalTurnover() function
	 *
	 * @return  array  Turn over of shop to show statistics chart.
	 */
	public function getTotalTurnOverCpanel()
	{
		$formate = $this->getDateFormate();

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' = ' . $db->q('C'), 'OR')
			->where($db->qn('order_status') . ' = ' . $db->q('PR'), 'OR')
			->where($db->qn('order_status') . ' = ' . $db->q('S'), 'OR')
			->order($db->qn('cdate') . ' ASC');

		// Set the query and load the result.
		$db->setQuery($query, 0, 1);
		$minDate = $db->loadResult();

		if (!$minDate)
		{
			return array();
		}

		$query = $db->getQuery(true)
					->clear()
					->from($db->qn('#__redshop_orders', 'o'))
					->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
					->order($db->qn('o.cdate') . ' DESC')
					->group('viewdate');

		if (!empty($mindate))
		{
			$query->where($db->qn('cdate') . ' >= ' . $minDate);
		}

		$query->leftjoin(
					$db->qn('#__redshop_users_info', 'uf')
					. ' ON '
					. $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id')
					. ' AND ' . $db->qn('uf.address_type') . ' = ' . $db->q('BT')
				);

		if ($this->_filteroption == 2)
		{
			$query->select('CONCAT("' . JText::_('COM_REDSHOP_WEEKS') . ' - ", WEEKOFYEAR(FROM_UNIXTIME(o.cdate,"%Y-%m-%d"))) AS viewdate');

			$query->group('FROM_UNIXTIME(o.cdate,"%Y")');
		}
		elseif ($this->_filteroption == 4)
		{
			$query->select('CONCAT("' . JText::_('COM_REDSHOP_YEAR') . ' - ", FROM_UNIXTIME(o.cdate,"' . $formate . '")) AS viewdate');
		}
		else
		{
			$query->select('FROM_UNIXTIME(o.cdate,"' . $formate . '") AS viewdate');
		}

		$query->select('SUM(o.order_total) AS turnover');

		if ($this->_filteroption != 4)
		{
			$db->setQuery($query, 0, 10);
		}
		else
		{
			$db->setQuery($query);
		}

		return $db->loadRowList();
	}

	/**
	 * Get total sales for cpanel view
	 *
	 * @return  array  Sales of shop to show statistics chart.
	 */
	public function getTotalSalesCpanel()
	{
		$db    = JFactory::getDbo();
		$defaultQuery = $db->getQuery(true)
			->select('SUM(' . $db->qn('o.order_total') . ') AS total')
			->select('COUNT(' . $db->qn('o.order_total') . ') AS orders')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin(
				$db->qn('#__redshop_users_info', 'uf')
				. ' ON '
				. $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id')
				. ' AND ' . $db->qn('uf.address_type') . ' = ' . $db->q('BT')
			)
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')');

		// 30 days
		$query = clone $defaultQuery;
		$query->select($db->q(JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', '30')));
		$query->where('FROM_UNIXTIME(' . $db->qn('cdate') . ') BETWEEN NOW() - INTERVAL 30 DAY AND NOW()');

		// Today
		$union = clone $defaultQuery;
		$union->select($db->q(JText::_('COM_REDSHOP_STATISTIC_TODAY')));
		$union->where('DATE(FROM_UNIXTIME(' . $db->qn('cdate') . ')) = CURDATE()');
		$query->union($union);

		// Yesterday
		$union = clone $defaultQuery;
		$union->select($db->q(JText::_('COM_REDSHOP_STATISTIC_YESTERDAY')));
		$union->where('DATE(FROM_UNIXTIME(' . $db->qn('cdate') . ')) = SUBDATE(CURDATE(),1)');
		$query->union($union);

		// 7 days
		$union = clone $defaultQuery;
		$union->select($db->q(JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', '7')));
		$union->where('FROM_UNIXTIME(' . $db->qn('cdate') . ') BETWEEN NOW() - INTERVAL 7 DAY AND NOW()');
		$query->union($union);

		// 90 days
		$union = clone $defaultQuery;
		$union->select($db->q(JText::sprintf('COM_REDSHOP_STATISTIC_LAST_DAYS', '90')));
		$union->where('FROM_UNIXTIME(' . $db->qn('cdate') . ') BETWEEN NOW() - INTERVAL 90 DAY AND NOW()');
		$query->union($union);

		$db->setQuery($query);

		return $db->loadRowList();
	}

	/**
	 * get total turnover
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getTotalTurnover()
	{
		$turnOver = 0;
		$today    = $this->getStartDate();
		$formate  = $this->getDateFormate();
		$result   = array();
		$query    = $db->getQuery(true)
			->select($db->qn('cdate'))
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(o.cdate,"' . $format . '") AS viewdate')
			->select('SUM(o.order_total) AS turnover')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' = ' . $db->q('BT'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('o.cdate'));

		$turnOver = $this->_getList($query);

		if ($this->_filteroption == 3)
		{
			return $turnOver;
		}

		if ($this->_filteroption && $mindate != "" && $mindate != 0)
		{
			while ($mindate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));

				$rs = $db->setQuery($query)->loadObjectList();

				if (count($rs) > 0 && $rs[0]->turnover > 0)
				{
					if ($this->_filteroption == 2)
					{
						$rs[0]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[0];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$turnOver = array_reverse($result);
			}
		}

		return $turnOver;
	}

	/**
	 * get avarage order amount
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getAvgOrderAmount()
	{
		$amountPrice = "";
		$today       = $this->getStartDate();
		$formate     = $this->getDateFormate();
		$result      = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ',' . $db->q($formate) . ') AS viewdate')
			->select('(SUM(o.order_total)/COUNT(DISTINCT o.user_id)) AS avg_order')
			->from($db->qn('#__redshop_orders', 'o'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('viewdate') . ' DESC');

		if ($this->_filteroption && $minDate != '' && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query)->loadObjectList();

				if (count($rs) > 0 && $rs[0]->avg_order > 0)
				{
					if ($this->_filteroption == 2)
					{
						$rs[0]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[0];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$amountPrice = $result;
			}
		}

		if (empty($result))
		{
			$amountPrice = $db->setQuery($query)->loadObjectList();
		}

		return $amountPrice;
	}

	/**
	 * get amount price
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getAmountPrice()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ',' . $db->q($formate) . ') AS viewdate')
			->select($db->qn('uf.firstname'))
			->select($db->qn('uf.lastname'))
			->select('MAX(o.order_total) AS order_total')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' = ' . $db->q('BT'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->group($db->qn('o.user_id'))
			->order($db->qn('order_total') . 'DESC');
		$amountPrice = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$amountPrice = $result;
			}
		}

		return $amountPrice;
	}

	/**
	 * get amount spent in total
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getAmountSpentInTotal()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ',' . $db->q($formate) . ') AS viewdate')
			->select($db->qn('uf.firstname'))
			->select($db->qn('uf.lastname'))
			->select('SUM(o.order_total) AS order_total')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' = ' . $db->q('BT'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->group($db->qn('o.user_id'))
			->order($db->qn('order_total') . 'DESC');
		$amountPrice = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$amountPrice = $result;
			}
		}

		return $amountPrice;
	}

	/**
	 * get amount order
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getAmountOrder()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('cdate')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->order($db->qn('cdate') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('o.cdate') . ',' . $db->q($formate) . ') AS viewdate')
			->select($db->qn('uf.firstname'))
			->select($db->qn('uf.lastname'))
			->select('COUNT(o.user_id) AS totalorder')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin($db->qn('#__redshop_users_info', 'uf') . ' ON ' . $db->qn('o.user_id') . ' = ' . $db->qn('uf.user_id'))
			->where($db->qn('uf.address_type') . ' = ' . $db->q('BT'))
			->where($db->qn('o.order_status') . ' IN (' . $db->q('C') . ',' . $db->q('PR') . ',' . $db->q('S') . ')')
			->group($db->qn('o.user_id'))
			->order($db->qn('totalorder') . 'DESC');
		$amountPrice = $this->_getList($query);

		$amountOrder = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('o.cdate') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('o.cdate') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$amountOrder = $result;
			}
		}

		return $amountOrder;
	}

	/**
	 * get page viewer
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getPageViewer()
	{
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('created_date')
			->from($db->qn('#__redshop_pageviewer'))
			->order($db->qn('created_date') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('created_date') . ',' . $db->q($formate) . ') AS viewdate')
			->select($db->qn('section'))
			->select($db->qn('section_id'))
			->select('COUNT(*) AS totalpage')
			->from($db->qn('#__redshop_pageviewer'))
			->where($db->qn('section_id') . ' != 0')
			->group($db->qn('section'))
			->group($db->qn('section_id'))
			->order($db->qn('totalpage') . ' DESC');

		$pageViewer = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('created_date') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('created_date') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query)->loadObjectList();

				for ($i = 0, $in = count($rs); $i < $in; $i++)
				{
					if ($this->_filteroption == 2)
					{
						$rs[$i]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[$i];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$pageViewer = $result;
			}
		}

		return $pageViewer;
	}

	/**
	 * get redSHOP viewer
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getRedshopViewer()
	{
		$siteViewer = array();
		$today = $this->getStartDate();
		$formate = $this->getDateFormate();
		$result = array();
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select('created_date')
			->from($db->qn('#__redshop_siteviewer'))
			->order($db->qn('created_date') . ' ASC');
		$minDate = $db->setQuery($query)->loadResult();

		$query = $db->getQuery(true)
			->clear()
			->select('FROM_UNIXTIME(' . $db->qn('created_date') . ',' . $db->q($formate) . ') AS viewdate')
			->from($db->qn('#__redshop_siteviewer'));

		$siteViewer = $this->_getList($query);

		if ($this->_filteroption && $minDate != "" && $minDate != 0)
		{
			$query = $db->getQuery(true)
			->clear()
			->select('COUNT(*) AS viewer')
			->from($db->qn('#__redshop_siteviewer'));

			while ($minDate < strtotime($today))
			{
				$list = $this->getNextInterval($today);
				$query->where($db->qn('created_date') . ' > ' . $db->q(strtotime($list->preday)))
					->where($db->qn('created_date') . ' <= ' . $db->q(strtotime($today)));
				$rs = $db->setQuery($query)->loadObjectList();
				$rs[0] = new stdClass;
				$rs[0]->viewer = count($rs);

				if ($rs[0]->viewer > 0)
				{
					if ($this->_filteroption == 1)
					{
						$rs[0]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					if ($this->_filteroption == 2)
					{
						$rs[0]->viewdate = date("d M, Y", strtotime($list->preday) + 1);
					}

					if ($this->_filteroption == 3)
					{
						$rs[0]->viewdate = date("F, Y", strtotime($list->preday) + 1);
					}

					if ($this->_filteroption == 4)
					{
						$rs[0]->viewdate = date("Y", strtotime($list->preday) + 1);
					}

					$result[] = $rs[0];
				}

				$today = $list->preday;
			}

			if (!empty($result))
			{
				$siteViewer = $result;
			}
		}

		return $siteViewer;
	}

	/**
	 * get next interval
	 *
	 * @param   string  $today  today text
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getNextInterval($today)
	{
		$list = array();
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		switch ($this->_filteroption)
		{
			case 1:
				$query->select('SUBDATE("' . $db->qn($today) . '", INTERVAL 1 DAY) AS preday');
				$list = $db->setQuery($query)->loadObject();
				break;
			case 2:
				$query->select('SUBDATE("' . $db->qn($today) . '", INTERVAL 1 WEEK) AS preday');
				$list = $db->setQuery($query)->loadObject();
				break;
			case 3:
				$query->select('SUBDATE("' . $db->qn($today) . '", INTERVAL 1 MONTH) AS preday');
				$list = $db->setQuery($query)->loadObject();
				$list->preday = $list->preday . " 23:59:59";
				break;
			case 4:
				$query->select('SUBDATE("' . $db->qn($today) . '", INTERVAL 1 YEAR) AS preday');
				$list = $db->setQuery($query)->loadObject();
				break;
		}

		return $list;
	}

	/**
	 * get start date
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getStartDate()
	{
		$return = "";
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		switch ($this->_filteroption)
		{
			case 1:
				$query->select('CURDATE() AS date');
				$list = $db->setQuery($query)->loadObject();
				$return = $list->date . " 23:59:59";
				break;
			case 2:
				$query->select('ADDDATE(CURDATE(), INTERVAL 6-weekday(CURDATE()) DAY) AS date');
				$list = $db->setQuery($query)->loadObject();
				$return = $list->date . " 23:59:59";
				break;
			case 3:
				$query->select('LAST_DAY(CURDATE()) AS date');
				$list = $db->setQuery($query)->loadObject();
				$return = $list->date . " 23:59:59";
				break;
			case 4:
				$query->select('LAST_DAY("' . $db->qn(date("Y-12-d")) . '") AS date');
				$list = $db->setQuery($query)->loadObject();
				$return = $list->date . " 23:59:59";
				break;
		}

		return $return;
	}

	/**
	 * get date Format
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getDateFormate()
	{
		$return = "";

		switch ($this->_filteroption)
		{
			case 1:
				$return = "%d %b %Y";
				break;
			case 2:
				$return = "%d %b, %Y";
				break;
			case 3:
				$return = "%b, %Y";
				break;
			case 4:
				$return = "%Y";
				break;
			default:
				$return = "%Y";
				break;
		}

		return $return;
	}

	/**
	 * get date Format for new statistic
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getDateFormat()
	{
		$return = "";
		$startDate = strtotime($this->filterStartDate);
		$endDate = strtotime($this->filterEndDate);
		$interval = $endDate - $startDate;

		if ($interval == 0 && ($this->filterDateLabel == 'Today' || $this->filterDateLabel == 'Yesterday'))
		{
			$return = "%d %b %Y";
		}
		elseif ($interval <= 1209600)
		{
			$return = "%d %b. %Y";
		}
		elseif ($interval <= 7689600)
		{
			$return = "%b. %Y";
		}
		elseif ($interval <= 31536000)
		{
			$return = "%Y";
		}

		return $return;
	}

	/**
	 * get section detail
	 *
	 * @param   string  $section    section
	 * @param   int     $sectionId  section id
	 *
	 * @return  object.
	 *
	 * @since   2.0.0.3
	 */
	public function getSectionDetail($section, $sectionId)
	{
		$return = array();
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		switch ($section)
		{
			case "product":
				$query
					->select($db->qn('product_name', 'sname'))
					->select($db->qn('product_id', 'id'))
					->from($db->qn('#__redshop_product'))
					->where($db->qn('product_id') . ' = ' . $db->q((int) $sectionId));
				$return = $db->setQuery($query)->loadObject();
				break;
			case "category":
				$query
					->select($db->qn('category_name', 'sname'))
					->select($db->qn('category_id', 'id'))
					->from($db->qn('#__redshop_category'))
					->where($db->qn('category_id') . ' = ' . $db->q((int) $sectionId));
				$return = $db->setQuery($query)->loadObject();
				break;
			case "manufacturers":
				$query
					->select($db->qn('manufacturer_name', 'sname'))
					->select($db->qn('manufacturer_id', 'id'))
					->from($db->qn('#__redshop_manufacturer'))
					->where($db->qn('manufacturer_id') . ' = ' . $db->q((int) $sectionId));
				$return = $db->setQuery($query)->loadObject();
				break;
		}

		return $return;
	}
}
