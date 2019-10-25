<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractExportPlugin;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Product
 *
 * @since  1.0
 */
class PlgRedshop_ExportOrder extends AbstractExportPlugin
{
	protected $orderItemWithRow = false;

	public function onAjaxOrder_Config()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		// Radio for load extra fields
		$configs[] = '<div class="form-group">
			<label class="col-md-2 control-label">' . JText::_('PLG_REDSHOP_EXPORT_ORDER_CONFIG_ORDER_ITEM') . '</label>
			<div class="col-md-10">
				<label class="radio-inline"><input name="order_item" value="1" type="radio" />' . JText::_('JYES') . '</label>
				<label class="radio-inline"><input name="order_item" value="0" type="radio" checked />' . JText::_('JNO') . '</label>
			</div>
		</div>';

		return implode('', $configs);
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxOrder_Start()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		return (int) $this->getTotalOrder_Export();
	}

	/**
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	protected function getTotalOrder_Export()
	{
		$query = $this->getQuery();
		$query->clear('select')
			->clear('group')
			->select('COUNT(DISTINCT o.order_id)');
		return (int) $this->db->setQuery($query)->loadResult();
	}

	/**
	 * Event run on export process
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function onAjaxOrder_Export()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$input = JFactory::getApplication()->input;
		$this->orderItemWithRow = (boolean) $input->getInt('order_item', 0);

		if ($this->orderItemWithRow)
		{
			return $this->exportDataWithRow();
		}

		return $this->exportDataWithColumn();
	}

	/**
	 * Event run on export process
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onAjaxOrder_Complete()
	{
		$this->downloadFile();

		JFactory::getApplication()->close();
	}

	/**
	 * Method for get query
	 *
	 * @return \JDatabaseQuery
	 *
	 * @since  1.0.0
	 */
	protected function getQuery()
	{
		$query = $this->db->getQuery(true)
			->select(
				array(
					$this->db->qn('o.order_id'),
					$this->db->qn('os.order_status_name'),
					$this->db->qn('o.order_payment_status'),
					$this->db->qn('o.cdate'),
					$this->db->qn('o.customer_note'),
					$this->db->qn('o.ship_method_id'),
					' concat(ouf.firstname, " " , ouf.lastname)  as shipping_user',
					$this->db->qn('ouf.address'),
					$this->db->qn('ouf.zipcode'),
					$this->db->qn('ouf.city'),
					$this->db->qn('ouf.country_code'),
					$this->db->qn('ouf.user_email')
				)
			)
			->from($this->db->qn('#__redshop_orders', 'o'))
			->leftJoin($this->db->qn('#__redshop_order_users_info', 'ouf') . ' ON ' . $this->db->qn('o.order_id') . ' = ' . $this->db->qn('ouf.order_id'))
			->leftJoin($this->db->qn('#__redshop_order_item', 'oi') . ' ON ' . $this->db->qn('o.order_id') . ' = ' . $this->db->qn('oi.order_id'))
			->leftJoin($this->db->qn('#__redshop_shipping_rate', 'sr') . ' ON ' . $this->db->qn('sr.shipping_rate_id') . ' = ' . $this->db->qn('o.ship_method_id'))
			->leftJoin($this->db->qn('#__redshop_order_status', 'os') . ' ON ' . $this->db->qn('os.order_status_code') . ' = ' . $this->db->qn('o.order_status'))
			->where($this->db->qn('ouf.address_type') . ' = ' . $this->db->q('ST'))
			->order($this->db->qn('o.order_id') . ' ASC')
			->group($this->db->qn('o.order_id'));

		return $query;
	}

	/**
	 * Method for do some stuff for data return. (Like image path,...)
	 *
	 * @param   array  &$data  Array of data.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	protected function exportDataWithRow()
	{
		$data = $this->getData(0, 0);

		if (empty($data))
		{
			return;
		}

		$db = JFactory::getDbo();
		$handle = fopen($this->getFilePath(), 'a');
		$headers = array('Order number', 'Order status', 'Order Payment Status', 'Order date', 'Customer Note', 'Shipping method', 'Shipping user', 'Shipping address',
			'Shipping postalcode', 'Shipping city', 'Shipping country', 'Email', 'Order Total');

		$this->writeData($headers, '', $handle);
		$orderTotal = array();

		foreach ($data as $item)
		{
			$item = (array) $item;
			$shippingDetail = \Redshop\Shipping\Rate::decrypt($item['ship_method_id']);
			$item['ship_method_id'] = \JText::_($shippingDetail[1]);
			$item['cdate'] = \RedshopHelperDatetime::convertDateFormat($item['cdate']);

			$query = $db->getQuery(true)
				->select('order_total')
				->from($db->qn('#__redshop_orders'))
				->where($db->qn('order_id') . ' = ' . $db->q($item['order_id']));

			$orderTotal['order_total'] = $db->setQuery($query)->loadResult();
			$item = array_merge($item, $orderTotal);
			$this->writeData($item, '', $handle);

			$query = $db->getQuery(true)
				->select('order_item_name, product_id, product_item_price')
				->from($db->qn('#__redshop_order_item'))
				->where($db->qn('order_id') . ' = ' . $db->q($item['order_id']));

			$orderItems = $db->setQuery($query)->loadAssocList();
			$headerOrderItem = array(' ', 'Product Name', 'Product Number', 'Product Price');
			$this->writeData($headerOrderItem, '', $handle);

			foreach ($orderItems as $orderItem)
			{
				$arrOrderItem = array_merge(array(''), $orderItem);
				$this->writeData($arrOrderItem, '', $handle);
			}
		}

		fclose($handle);
	}


	/**
	 * Method for do some stuff for data return. (Like image path,...)
	 *
	 * @param   array  &$data  Array of data.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	protected function exportDataWithColumn()
	{
		$data = $this->getData(0, 0);

		if (empty($data))
		{
			return;
		}

		$db = JFactory::getDbo();
		$handle = fopen($this->getFilePath(), 'a');
		$headers = array('Order number', 'Order status', 'Order Payment Status', 'Order date', 'Customer Note', 'Shipping method', 'Shipping user', 'Shipping address',
			'Shipping postalcode', 'Shipping city', 'Shipping country', 'Email',);

		$orderItemHeaders = $this->getHeaderOrderItem();
		$headersOrderItem = array_merge($orderItemHeaders, array('Order Total'));
		$headers = array_merge($headers, $headersOrderItem);

		$this->writeData($headers, '', $handle);

		foreach ($data as $item)
		{
			$item = (array) $item;
			$shippingDetail = \Redshop\Shipping\Rate::decrypt($item['ship_method_id']);
			$item['ship_method_id'] = \JText::_($shippingDetail[1]);
			$item['cdate'] = \RedshopHelperDatetime::convertDateFormat($item['cdate']);

			$query = $db->getQuery(true)
				->select('order_item_name, product_id, product_item_price')
				->from($db->qn('#__redshop_order_item'))
				->where($db->qn('order_id') . ' = ' . $db->q($item['order_id']));

			$orderItems = $db->setQuery($query)->loadAssocList();
			$maxColumnHeaderOrderItem = count($orderItemHeaders) - (count($orderItems) + count($orderItems));

			for ($i = 0; $i < $maxColumnHeaderOrderItem; $i++)
			{
				if ($orderItems[$i])
				{
					foreach ($orderItems[$i] as $key => $value)
					{
						$item[] = $value;
					}
				}
				else
				{
					$item[] = '';
				}
			}

			$query = $db->getQuery(true)
				->select('order_total')
				->from($db->qn('#__redshop_orders'))
				->where($db->qn('order_id') . ' = ' . $db->q($item['order_id']));

			$orderTotal = $db->setQuery($query)->loadResult();
			$item[] = $orderTotal;
			$this->writeData($item, '', $handle);
		}

		fclose($handle);
	}

	public function getHeaderOrderItem()
	{

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('order_id, order_total')
			->from($db->qn('#__redshop_orders'));

		$orders = $db->setQuery($query)->loadAssocList();
		$arrayHeaders = array();

		foreach ($orders as $order)
		{
			$query = $db->getQuery(true)
				->select('order_item_name, product_id, product_item_price')
				->from($db->qn('#__redshop_order_item'))
				->where($db->qn('order_id') . ' = ' . $db->q($order['order_id']));

			$orderItemNames = $db->setQuery($query)->loadAssocList();

			for ($i = 0; $i < count($orderItemNames); $i++)
			{
				foreach ($orderItemNames[$i] as $key => $orderItemName)
				{
					if (in_array($key . ' '. $i, $arrayHeaders))
					{
						continue;
					}

					$arrayHeaders[] = $key . ' ' . $i;
				}
			}
		}

		$this->arrOrderItem[] = $arrayHeaders;
		return $arrayHeaders;
	}
}