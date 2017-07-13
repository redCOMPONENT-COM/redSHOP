<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopModelOrder extends RedshopModel
{
	public $_data = null;

	public $_total = null;

	public $_pagination = null;

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
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
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @note    Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = 'o.order_id', $direction = 'desc')
	{
		$filter_status         = $this->getUserStateFromRequest($this->context . 'filter_status', 'filter_status', '', 'string');
		$filter_payment_status = $this->getUserStateFromRequest($this->context . 'filter_payment_status', 'filter_payment_status', '', '');
		$filter                = $this->getUserStateFromRequest($this->context . 'filter', 'filter', '');
		$filter_by             = $this->getUserStateFromRequest($this->context . 'filter_by', 'filter_by', '', '');
		$filter_from_date     = $this->getUserStateFromRequest($this->context . 'filter_from_date', 'filter_from_date', '', '');
		$filter_to_date       = $this->getUserStateFromRequest($this->context . 'filter_to_date', 'filter_to_date', '', '');

		$this->setState('filter', $filter);
		$this->setState('filter_by', $filter_by);
		$this->setState('filter_status', $filter_status);
		$this->setState('filter_payment_status', $filter_payment_status);
		$this->setState('filter_from_date', $filter_from_date);
		$this->setState('filter_to_date', $filter_to_date);

		parent::populateState($ordering, $direction);
	}

	public function _buildQuery()
	{
		$app                   = JFactory::getApplication();
		$db                    = JFactory::getDbo();
		$query                 = $db->getQuery(true);

		$filter                = $this->getState('filter');
		$filter_by             = $this->getState('filter_by');
		$filter_status         = $this->getState('filter_status');
		$filter_payment_status = $this->getState('filter_payment_status');
		$filter_from_date     = $this->getState('filter_from_date');
		$filter_to_date       = $this->getState('filter_to_date');

		if ($filter_from_date)
		{
			$query->where($db->qn('o.cdate') . '>=' . strtotime($filter_from_date));
		}

		if ($filter_to_date)
		{
			// Adding 24 hours to the end date to consider whole end day
			$query->where($db->qn('o.cdate') . ' <= ' . (strtotime($filter_to_date) + 24 * 3600));
		}

		if ($filter_status)
		{
			$query->where($db->qn('o.order_status') . '=' . $db->q($filter_status));
		}

		if ($filter_payment_status)
		{
			$query->where($db->qn('o.order_payment_status') . '=' . $db->q($filter_payment_status));
		}

		if ($filter)
		{
			$filter = str_replace(' ', '', $filter);

			if ($filter_by == 'orderid')
			{
				$query->where($db->qn('o.order_id') . ' LIKE ' . $db->q('%' . $filter . '%'));
			}
			elseif ($filter_by == 'ordernumber')
			{
				$query->where($db->qn('o.order_number') . ' LIKE ' . $db->q('%' . $filter . '%'));
			}
			elseif ($filter_by == 'fullname')
			{
				$query->where("REPLACE(CONCAT(" . $db->qn('uf.firstname') . ", " . $db->qn('uf.lastname') . "), ' ', '') LIKE " . $db->q('%' . $filter . '%'));
			}
			elseif ($filter_by == 'useremail')
			{
				$query->where($db->qn('uf.user_email') . ' LIKE ' . $db->q('%' . $filter . '%'));
			}
			// $filter_by == 'all'
			else
			{
				$query->where("(REPLACE(CONCAT(" . $db->qn('uf.firstname') . ", " . $db->qn('uf.lastname') . "), ' ', '') LIKE " . $db->q('%' . $filter . '%')
						. " OR " . $db->qn('o.order_id') . " LIKE " . $db->q('%' . $filter . '%')
						. " OR " . $db->qn('o.order_number') . " LIKE " . $db->q('%' . $filter . '%')
						. " OR " . $db->qn('o.referral_code') . " LIKE " . $db->q('%' . $filter . '%')
						. " OR " . $db->qn('uf.user_email') . " LIKE " . $db->q('%' . $filter . '%')
					. ")"
				);
			}
		}

		$cid = $app->input->get('cid', array(0), 'request', 'array');

		if ($cid[0] != 0)
		{
			$order_id = array();
			$order_id = implode(',', $cid);

			$query->where($db->qn('o.order_id') . ' IN (' . $order_id . ')');
		}

		if ('labellisting' == $app->input->getCmd('layout'))
		{
			$query->where($db->qn('o.order_label_create') . '=1');
		}

		$query->select(
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
			->where($db->qn('uf.address_type') . '=' . $db->q('BT'))
			->group($db->qn('o.order_id'));

		$filter_order_Dir = $this->getState('list.direction');
		$filter_order = $this->getState('list.ordering');
		$query->order($db->escape($filter_order . ' ' . $filter_order_Dir));

		return $query;
	}

	public function export_data($cid)
	{
		$where = "";

		$order_id = implode(',', $cid);


		$where[] = " 1=1";

		if ($cid[0] != 0)
		{
			$where[] = " o.order_id IN (" . $order_id . ")";
		}

		$where = count($where) ? '  ' . implode(' AND ', $where) : '';
		$orderby = " order by o.order_id DESC";

		$query = 'SELECT distinct(o.cdate),o.*,ouf.* FROM #__redshop_orders AS o '
			. 'LEFT JOIN #__redshop_order_users_info AS ouf ON o.order_id=ouf.order_id '
			. 'WHERE ouf.address_type LIKE "BT" '
			. 'AND ' . $where . ' '
			. $orderby;

		return $this->_getList($query);
	}

	public function updateDownloadSetting($did, $limit, $enddate)
	{
		$query = "UPDATE #__redshop_product_download "
			. " SET `download_max` = " . $limit . " , `end_date` = " . $enddate . " "
			. " WHERE download_id = '" . $did . "'";
		$this->_db->setQuery($query);

		if (!$this->_db->execute())
		{
			return false;
		}

		return true;
	}

	/**
	 * GLS Export
	 *
	 * @param   array  $cid  Order Ids
	 *
	 * @return  void
	 */
	public function gls_export($cid)
	{
		$orderHelper = order_functions::getInstance();
		ob_clean();

		// Start the ouput
		$outputCsv = fopen('php://output', 'w');

		$ordersInfo = $this->getOrdersDetail($cid);

		for ($i = 0, $in = count($ordersInfo); $i < $in; $i++)
		{
			$details = RedshopShippingRate::decrypt($ordersInfo[$i]->ship_method_id);

			if ((strtolower($details[0]) == 'plgredshop_shippingdefault_shipping_gls') && $ordersInfo[$i]->shop_id != "")
			{
				$orderproducts   = $orderHelper->getOrderItemDetail($ordersInfo[$i]->order_id);
				$shippingDetails = RedshopHelperOrder::getOrderShippingUserInfo($ordersInfo[$i]->order_id);
				$billingDetails  = RedshopHelperOrder::getOrderBillingUserInfo($ordersInfo[$i]->order_id);

				$totalWeight = 0;

				for ($c = 0, $cn = count($orderproducts); $c < $cn; $c++)
				{
					$weight      = (float) $this->getProductWeight($orderproducts[$c]->product_id);
					$totalWeight += ($weight * (float)$orderproducts[$c]->product_quantity);
				}

				$parceltype = 'A';
				$shopDetails_arr = explode("|", $ordersInfo[$i]->shop_id);

				$userphoneArr = explode("###", $ordersInfo[$i]->shop_id);

				$shopDetails_temparr = explode("###", $shopDetails_arr[7]);
				$shopDetails_arr[7] = $shopDetails_temparr[0];

				$shopDetails_arr[2] = str_replace(',', '-', $shopDetails_arr[2]);

				$row = array(
					$ordersInfo[$i]->order_number,
					$shopDetails_arr[1],
					$shopDetails_arr[2],
					'Pakkeshop: '	. $shopDetails_arr[0],
					$shopDetails_arr[3],
					$shopDetails_arr[7],
					'008',
					date("d-m-Y", $ordersInfo[$i]->cdate),
					$totalWeight,
					1,
					'',
					'',
					$parceltype,
					'Z'	// Shippment Type
				);

				$userDetail = array();

				if ($ordersInfo[$i]->ship_method_id != '')
				{
					$userDetail = array(
						$billingDetails->firstname . ' ' . $billingDetails->lastname,
						substr($ordersInfo[$i]->customer_note, 0, 29),		// GLS only support max 29 characters
						Redshop::getConfig()->get('GLS_CUSTOMER_ID'),
						$billingDetails->user_email,
						$userphoneArr[1]
					);
				}

				$row = array_map('utf8_decode', array_merge($row, $userDetail));

				// Output CSV line
				fputcsv($outputCsv, $row);
			}
		}

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=redshop_gls_order_export.csv');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		JFactory::getApplication()->close();
	}

	/**
	 * Business GLS Export
	 *
	 * @param   array  $cid  Order Ids
	 *
	 * @return  void
	 */
	public function business_gls_export($cid)
	{
		$orderHelper = order_functions::getInstance();
		$extraField  = extraField::getInstance();

		ob_clean();

		// Start the ouput
		$outputCsv = fopen('php://output', 'w');

		$ordersInfo = $this->getOrdersDetail($cid);

		for ($i = 0, $in = count($ordersInfo); $i < $in; $i++)
		{
			$details = RedshopShippingRate::decrypt($ordersInfo[$i]->ship_method_id);

			if (strtolower($details[0]) == 'plgredshop_shippingdefault_shipping_glsbusiness')
			{
				$orderproducts   = $orderHelper->getOrderItemDetail($ordersInfo[$i]->order_id);
				$shippingDetails = RedshopHelperOrder::getOrderShippingUserInfo($ordersInfo[$i]->order_id);
				$billingDetails  = RedshopHelperOrder::getOrderBillingUserInfo($ordersInfo[$i]->order_id);

				$totalWeight = 0;

				for ($c = 0, $cn = count($orderproducts); $c < $cn; $c++)
				{
					$weight      = (float) $this->getProductWeight($orderproducts[$c]->product_id);
					$totalWeight += ($weight * (float) $orderproducts[$c]->product_quantity);
				}

				// Initialize row
				$row = array(
					$ordersInfo[$i]->order_number
				);

				$extraFieldData = $extraField->getSectionFieldList(19, 1);
				$extraInfo      = array();

				for ($j = 0, $jn = count($extraFieldData); $j < $jn; $j++)
				{
					$extraFieldResult = $extraField->getSectionFieldDataList($extraFieldData[$j]->field_id, 19, $ordersInfo[$i]->order_id);

					if ($extraFieldResult->data_txt != "" && $extraFieldData[$j]->field_show_in_front == 1)
					{
						$extraInfo[] = $extraFieldResult->data_txt;
					}
				}

				$rowAppend = array(
					'8',
					date("d-m-Y", $ordersInfo[$i]->cdate),
					$totalWeight,
					1,
					'',
					'',
					'A',
					'A',
					$billingDetails->firstname . ' ' . $billingDetails->lastname,
					$ordersInfo[$i]->customer_note,
					Redshop::getConfig()->get('GLS_CUSTOMER_ID'),
					$billingDetails->user_email,
					$shippingDetails->phone
				);

				$row = array_merge($row, $extraInfo, $rowAppend);

				foreach ($row as $key => $value)
				{
					$row[$key] = utf8_decode($value);
				}

				// Output CSV line
				fputcsv($outputCsv, $row);
			}
		}

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=redshop_gls_business_order_export.csv');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		JFactory::getApplication()->close();
	}

	/**
	 * Get Order details of the ids
	 *
	 * @param   array  $orderIds  Order Information Ids
	 *
	 * @return  array             Information of the orders in array
	 */
	public function getOrdersDetail($orderIds)
	{
		JArrayHelper::toInteger($orderIds);

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_orders'));

		if ($orderIds[0] != 0)
		{
			$query->where($db->qn('order_id') . ' IN(' . implode(',', $orderIds) . ')');
		}

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * Get Product weight
	 *
	 * @param   integer  $productId  Product Id
	 *
	 * @return  integer              Product Weight
	 */
	public function getProductWeight($productId)
	{
		return RedshopHelperProduct::getProductById($productId)->weight;
	}
}
