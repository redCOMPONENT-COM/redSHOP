<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Order
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.0
 */
class RedshopModelOrder extends RedshopModel
{
	/**
	 * @var null
	 */
	public $_data = null;

	/**
	 * @var null
	 */
	public $_total = null;

	/**
	 * @var null
	 */
	public $_pagination = null;

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
		$id .= ':' . $this->getState('filter');
		$id .= ':' . $this->getState('filter_by');
		$id .= ':' . $this->getState('filter_status');
		$id .= ':' . $this->getState('filter_payment_status');
		$id .= ':' . $this->getState('filter_from_date');
		$id .= ':' . $this->getState('filter_to_date');

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
	protected function populateState($ordering = 'o.order_id', $direction = 'desc')
	{
		$filterStatus        = $this->getUserStateFromRequest($this->context . 'filter_status', 'filter_status', '', 'string');
		$filterPaymentStatus = $this->getUserStateFromRequest($this->context . 'filter_payment_status', 'filter_payment_status', '', '');
		$filter              = $this->getUserStateFromRequest($this->context . 'filter', 'filter', '');
		$filterBy            = $this->getUserStateFromRequest($this->context . 'filter_by', 'filter_by', '', '');
		$filterFromDate      = $this->getUserStateFromRequest($this->context . 'filter_from_date', 'filter_from_date', '', '');
		$filterToDate        = $this->getUserStateFromRequest($this->context . 'filter_to_date', 'filter_to_date', '', '');

		$this->setState('filter', $filter);
		$this->setState('filter_by', $filterBy);
		$this->setState('filter_status', $filterStatus);
		$this->setState('filter_payment_status', $filterPaymentStatus);
		$this->setState('filter_from_date', $filterFromDate);
		$this->setState('filter_to_date', $filterToDate);

		parent::populateState($ordering, $direction);
	}

	/**
	 * Method for build query
	 *
	 * @return JDatabaseQuery
	 * @throws Exception
	 */
	public function _buildQuery()
	{
		$app = JFactory::getApplication();
		$db  = $this->getDbo();

		$query = $db->getQuery(true)
			->select(
				array(
					'o.*',
					$db->qn('uf.lastname'),
					$db->qn('uf.firstname'),
					$db->qn('uf.user_email'),
					$db->qn('uf.is_company'),
					$db->qn('uf.company_name'),
					$db->qn('uf.ean_number'),
					$db->qn('os.order_status_name')
				)
			)
			->from($db->qn('#__redshop_orders', 'o'))
			->leftjoin(
				$db->qn('#__redshop_order_users_info', 'uf')
				. ' ON ' . $db->qn('o.order_id') . ' = ' . $db->qn('uf.order_id')
			)
			->innerJoin($db->qn('#__redshop_order_status', 'os') . ' ON ' . $db->qn('os.order_status_code') . '=' . $this->_db->qn('o.order_status'))
//			->where($db->qn('uf.address_type') . '=' . $db->q('BT'))
			->group($db->qn('o.order_id'));

		$filterBy = $this->getState('filter_by');

		// Filter: From date.
		$filterFromDate = $this->getState('filter_from_date');

		if ($filterFromDate)
		{
			$query->where($db->qn('o.cdate') . '>=' . strtotime($filterFromDate));
		}

		// Filter: To date
		$filterToDate = $this->getState('filter_to_date');

		if ($filterToDate)
		{
			// Adding 24 hours to the end date to consider whole end day
			$query->where($db->qn('o.cdate') . ' <= ' . (strtotime($filterToDate) + 24 * 3600));
		}

		// Filter: order status
		$filterStatus = $this->getState('filter_status');

		if ($filterStatus)
		{
			$query->where($db->qn('o.order_status') . ' = ' . $db->q($filterStatus));
		}

		// Filter: Order payment status
		$filterPaymentStatus = $this->getState('filter_payment_status');

		if ($filterPaymentStatus)
		{
			$query->where($db->qn('o.order_payment_status') . ' = ' . $db->q($filterPaymentStatus));
		}

		// Filter
		$filter = $this->getState('filter');

		if ($filter)
		{
			$filter = str_replace(' ', '', $filter);

			if ($filterBy == 'orderid')
			{
				$query->where($db->qn('o.order_id') . ' LIKE ' . $db->q('%' . $filter . '%'));
			}
			elseif ($filterBy == 'ordernumber')
			{
				$query->where($db->qn('o.order_number') . ' LIKE ' . $db->q('%' . $filter . '%'));
			}
			elseif ($filterBy == 'fullname')
			{
				$query->where(
					"REPLACE(CONCAT(" . $db->qn('uf.firstname') . ", "
					. $db->qn('uf.lastname') . "), ' ', '') LIKE " . $db->q('%' . $filter . '%')
				);
			}
			elseif ($filterBy == 'useremail')
			{
				$query->where($db->qn('uf.user_email') . ' LIKE ' . $db->q('%' . $filter . '%'));
			}
			// $filter_by == 'all'
			else
			{
				$query->where(
					"(REPLACE(CONCAT(" . $db->qn('uf.firstname') . ", "
					. $db->qn('uf.lastname') . "), ' ', '') LIKE " . $db->q('%' . $filter . '%')
					. " OR " . $db->qn('o.order_id') . " LIKE " . $db->q('%' . $filter . '%')
					. " OR " . $db->qn('o.order_number') . " LIKE " . $db->q('%' . $filter . '%')
					. " OR " . $db->qn('o.referral_code') . " LIKE " . $db->q('%' . $filter . '%')
					. " OR " . $db->qn('uf.user_email') . " LIKE " . $db->q('%' . $filter . '%')
					. ")"
				);
			}
		}

		$orderIds = $app->input->get('cid', array(), 'array');
		$orderIds = \Joomla\Utilities\ArrayHelper::toInteger($orderIds);
		$orderIds = array_filter(array_values($orderIds));

		if (!empty($orderIds))
		{
			$query->where($db->qn('o.order_id') . ' IN (' . implode(',', $orderIds) . ')');
		}

		if ('labellisting' == $app->input->getCmd('layout'))
		{
			$query->where($db->qn('o.order_label_create') . '=1');
		}

		$filterOrderDir = $this->getState('list.direction');
		$filterOrder    = $this->getState('list.ordering');
		$query->order($db->escape($filterOrder . ' ' . $filterOrderDir));

		return $query;
	}

	/**
	 * Method for export data.
	 *
	 * @param   array  $cid  List of order ID
	 *
	 * @return  array<object>
	 */
	public function export_data($cid = array())
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT(' . $db->qn('o.cdate') . ')')
			->select('o.*')
			->select('ouf.*')
			->from($db->qn('#__redshop_orders', 'o'))
			->leftJoin($db->qn('#__redshop_order_users_info', 'ouf') . ' ON ' . $db->qn('o.order_id') . ' = ' . $db->qn('ouf.order_id'))
			->where($db->qn('ouf.address_type') . ' = ' . $db->quote('BT'))
			->order($db->qn('o.order_id') . ' DESC');

		if (!empty($cid))
		{
			$cid = \Joomla\Utilities\ArrayHelper::toInteger($cid);
			$query->where($db->qn('o.order_id') . ' IN (' . implode(',', $cid) . ')');
		}

		return $this->_getList($query);
	}

	/**
	 * Method for update download setting
	 *
	 * @param   integer $did     Download ID
	 * @param   integer $limit   Limiit
	 * @param   integer $enddate End date.
	 *
	 * @return  boolean
	 */
	public function updateDownloadSetting($did, $limit, $enddate)
	{
		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_product_download'))
			->set($db->qn('download_max') . ' = ' . $limit)
			->set($db->qn('end_date') . ' = ' . $enddate)
			->where($db->qn('download_id') . ' = ' . $did);

		return $db->setQuery($query)->execute();
	}

	/**
	 * GLS Export
	 *
	 * @param   array $cid sOrder Ids
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function gls_export($cid)
	{
		ob_clean();

		// Start the output
		$outputCsv = fopen('php://output', 'w');

		if ($outputCsv === false)
		{
			JFactory::getApplication()->close();
		}

		$ordersInfo = $this->getOrdersDetail($cid);

		foreach ($ordersInfo as $order)
		{
			$details = Redshop\Shipping\Rate::decrypt($order->ship_method_id);

			if (strtolower($details[0]) != 'plgredshop_shippingdefault_shipping_gls' || $order->shop_id == '')
			{
				continue;
			}

			$orderProducts  = RedshopHelperOrder::getOrderItemDetail($order->order_id);
			$billingDetails = RedshopEntityOrder::getInstance($order->order_id)->getBilling();

			$totalWeight = 0;

			foreach ($orderProducts as $orderProduct)
			{
				$weight       = (float) $this->getProductWeight($orderProduct->product_id);
				$totalWeight += ($weight * (float) $orderProduct->product_quantity);
			}

			$unitRatio = \Redshop\Helper\Utility::getUnitConversation('kg', Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'));

			if ($unitRatio != 0)
			{
				// Converting weight in kg
				$totalWeight = $totalWeight * $unitRatio;
			}

			$parcelType     = 'A';
			$shopDetail     = explode("|", $order->shop_id);
			$userPhone      = explode("###", $order->shop_id);
			$shopDetailTemp = explode("###", $shopDetail[7]);
			$shopDetail[7]  = $shopDetailTemp[0];
			$shopDetail[2]  = str_replace(',', '-', $shopDetail[2]);

			$row = array(
				$order->order_number,
				$shopDetail[1],
				$shopDetail[2],
				'Pakkeshop: ' . $shopDetail[0],
				$shopDetail[3],
				$shopDetail[7],
				'008',
				date("d-m-Y", $order->cdate),
				$totalWeight,
				1,
				'',
				'',
				$parcelType,
				'Z'    // Shippment Type
			);

			$userDetail = array();

			if (!empty($order->ship_method_id))
			{
				$userDetail = array(
					$billingDetails->get('firstname') . ' ' . $billingDetails->get('lastname'),
					substr($order->customer_note, 0, 29),        // GLS only support max 29 characters
					Redshop::getConfig()->get('GLS_CUSTOMER_ID'),
					$billingDetails->get('user_email'),
					$userPhone[1]
				);
			}

			$row = array_map('utf8_decode', array_merge($row, $userDetail));

			foreach ($row as &$column)
			{
				$column = '"' . $column . '"';
			}

			unset($column);

			// Output CSV line
			fputcsv($outputCsv, $row, ",", " ");
		}

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=redshop_gls_order_export.csv');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		JFactory::getApplication()->close();
	}

	/**
	 * Business GLS Export
	 *
	 * @param   array $cid Order Ids
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function business_gls_export($cid)
	{
		ob_clean();

		// Start the ouput
		$outputCsv = fopen('php://output', 'w');

		if ($outputCsv === false)
		{
			JFactory::getApplication()->close();
		}

		$ordersInfo = $this->getOrdersDetail($cid);

		foreach ($ordersInfo as $order)
		{
			$details = Redshop\Shipping\Rate::decrypt($order->ship_method_id);

			if (strtolower($details[0]) != 'plgredshop_shippingdefault_shipping_glsbusiness')
			{
				continue;
			}

			$orderProducts   = RedshopHelperOrder::getOrderItemDetail($order->order_id);
			$shippingDetails = RedshopEntityOrder::getInstance($order->order_id)->getShipping();
			$billingDetails  = RedshopEntityOrder::getInstance($order->order_id)->getBilling();
			$totalWeight     = 0;

			foreach ($orderProducts as $orderProduct)
			{
				$weight       = (float) $this->getProductWeight($orderProduct->product_id);
				$totalWeight += ($weight * (float) $orderProduct->product_quantity);
			}

			$unitRatio = \Redshop\Helper\Utility::getUnitConversation('kg', Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'));

			if ($unitRatio != 0)
			{
				// Converting weight in kg
				$totalWeight = $totalWeight * $unitRatio;
			}

			// Initialize row
			$row            = array($order->order_number);
			$extraFieldData = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_SHIPPING_GATEWAY, 1);
			$extraInfo      = array();

			foreach ($extraFieldData as $extraFieldDatum)
			{
				$extraFieldResult = RedshopHelperExtrafields::getData(
					$extraFieldDatum->field_id,
					RedshopHelperExtrafields::SECTION_SHIPPING_GATEWAY,
					$order->order_id
				);

				if ($extraFieldResult->data_txt != "" && $extraFieldDatum->field_show_in_front == 1)
				{
					$extraInfo[] = $extraFieldResult->data_txt;
				}
			}

			$rowAppend = array(
				'8',
				date("d-m-Y", $order->cdate),
				$totalWeight,
				1,
				'',
				'',
				'A',
				'A',
				$billingDetails->get('firstname') . ' ' . $billingDetails->get('lastname'),
				$order->customer_note,
				Redshop::getConfig()->get('GLS_CUSTOMER_ID'),
				$billingDetails->get('user_email'),
				$shippingDetails->get('phone')
			);

			$row = array_map('utf8_decode', array_merge($row, $extraInfo, $rowAppend));

			foreach ($row as &$column)
			{
				$column = '"' . $column . '"';
			}

			unset($column);

			// Output CSV line
			fputcsv($outputCsv, $row, ",", " ");
		}

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=redshop_gls_business_order_export.csv');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		JFactory::getApplication()->close();
	}

	/**
	 * Get Order details of the ids
	 *
	 * @param   array $orderIds Order Information Ids
	 *
	 * @return  array             Information of the orders in array
	 */
	public function getOrdersDetail($orderIds)
	{
		$orderIds = Joomla\Utilities\ArrayHelper::toInteger($orderIds);

		// Init variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_orders'));

		if ($orderIds[0] != 0)
		{
			$query->where($db->qn('order_id') . ' IN(' . implode(',', $orderIds) . ')');
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Get Product weight
	 *
	 * @param   integer  $productId  Product Id
	 *
	 * @return  integer              Product Weight
	 * @throws  Exception
	 */
	public function getProductWeight($productId)
	{
		return RedshopHelperProduct::getProductById($productId)->weight;
	}
}
