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

	protected $fromDate = '';

	protected $toDate = '';

	public function onAjaxOrder_Config()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$configs = array();
		// Radio for load extra fields
		$configs[] = '<div class="form-group">
			<label class="col-md-3 control-label">' . JText::_('PLG_REDSHOP_EXPORT_ORDER_CONFIG_ORDER_ITEM') . '</label>
			<div class="col-md-9">
				<label class="radio-inline"><input name="order_item" value="1" type="radio" checked/>' . JText::_('JYES') . '</label>
				<label class="radio-inline"><input name="order_item" value="0" type="radio"/>' . JText::_('JNO') . '</label>
			</div>
		</div>';

		$configs[] = '<div class="form-group">
			<label class="col-md-2 control-label">' . JText::_('PLG_REDSHOP_EXPORT_ORDER_CONFIG_FROM_DATE') . '</label>
			<div class="col-md-4">
				<label class="text-inline"><input name="from_date" type="date" checked/></label>
			</div>
			<label class="col-md-2 control-label">' . JText::_('PLG_REDSHOP_EXPORT_ORDER_CONFIG_TO_DATE') . '</label>
			<div class="col-md-4">
				<label class="text-inline"><input name="to_date" type="date" checked/></label>
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
		$input = JFactory::getApplication()->input;
		$this->fromDate = $input->get('from_date', '');
		$this->toDate = $input->get('to_date', '');

		$query = $this->getQuery();
		$query->clear('select')
			->clear('group')
			->select('COUNT(DISTINCT o.order_id)');


		if ($this->fromDate)
		{
			$fromDate = strtotime($this->fromDate);
			$query->where($this->db->qn('o.cdate') . ' > ' . $this->db->q($fromDate));
		}

		if ($this->toDate)
		{
			$toDate = strtotime($this->toDate);
			$query->where($this->db->qn('o.cdate') . ' < ' . $this->db->q($toDate));
		}

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
		$this->fromDate = $input->get('from_date', '');
		$this->toDate = $input->get('to_date', '');

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
					$this->db->qn('ouf.user_email'),
					$this->db->qn('oi.order_item_name'),
					'oi.customer_note AS item_note',
					$this->db->qn('oi.order_item_sku'),
					$this->db->qn('oi.product_item_price'),
					$this->db->qn('o.order_total')
				)
			)
			->from($this->db->qn('#__redshop_orders', 'o'))
			->leftJoin($this->db->qn('#__redshop_order_users_info', 'ouf') . ' ON ' . $this->db->qn('o.order_id') . ' = ' . $this->db->qn('ouf.order_id'))
			->leftJoin($this->db->qn('#__redshop_order_item', 'oi') . ' ON ' . $this->db->qn('o.order_id') . ' = ' . $this->db->qn('oi.order_id'))
			->leftJoin($this->db->qn('#__redshop_shipping_rate', 'sr') . ' ON ' . $this->db->qn('sr.shipping_rate_id') . ' = ' . $this->db->qn('o.ship_method_id'))
			->leftJoin($this->db->qn('#__redshop_order_status', 'os') . ' ON ' . $this->db->qn('os.order_status_code') . ' = ' . $this->db->qn('oi.order_status'))
			->where($this->db->qn('ouf.address_type') . ' = ' . $this->db->q('ST'))
			->order($this->db->qn('o.order_id') . ' ASC');

		if ($this->fromDate)
		{
			$this->fromDate = $this->fromDate .' '. '00:00:00';
			$fromDate = strtotime($this->fromDate);
			$query->where($this->db->qn('o.cdate') . ' > ' . $this->db->q($fromDate));
		}

		if ($this->toDate)
		{
			$this->toDate = $this->toDate .' '. '23:59:59';
			$toDate = strtotime($this->toDate);
			$query->where($this->db->qn('o.cdate') . ' < ' . $this->db->q($toDate));
		}

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
		$headers = array('Order number', 'Order Item status', 'Order Payment Status', 'Order date', 'Order Customer Note', 'Shipping method', 'Shipping user', 'Shipping address',
			'Shipping postalcode', 'Shipping city', 'Shipping country', 'Email', 'Category Name', 'Product Name', 'Item Customer Note', 'Product Number', 'Colour', 'Product Price', 'Total');

		$this->writeData($headers, '', /** @scrutinizer ignore-type */ $handle);
		$arrData = array();

		foreach ($data as $item)
		{
			$item = (array) $item;
			$shippingDetail = \Redshop\Shipping\Rate::decrypt($item['ship_method_id']);
			$item['ship_method_id'] = \JText::_($shippingDetail[1]);
			$item['cdate'] = \RedshopHelperDatetime::convertDateFormat($item['cdate']);

			$query = $db->getQuery(true)
				->select($db->qn('product_id'))
				->from($db->qn('#__redshop_product'))
				->where($db->qn('product_number') . ' = ' . $db->q($item['order_item_sku']));

			$productId = $db->setQuery($query)->loadResult();

			$query = $db->getQuery(true)
				->select('order_item_id')
				->from($db->qn('#__redshop_order_item'))
				->where($db->qn('order_item_sku') . ' = ' . $db->q($item['order_item_sku']))
				->where($db->qn('order_id') . ' = ' . $db->q($item['order_id']));

			$orderItemId = $db->setQuery($query)->loadResult();

			$arrData['order_id'] = $item['order_id'];
			$arrData['order_status_name'] = $item['order_status_name'];
			$arrData['order_payment_status'] = $item['order_payment_status'];
			$arrData['cdate'] = $item['cdate'];
			$arrData['customer_note'] = $item['customer_note'];
			$arrData['ship_method_id'] = $item['ship_method_id'];
			$arrData['shipping_user'] = $item['shipping_user'];
			$arrData['address'] = $item['address'];
			$arrData['zipcode'] = $item['zipcode'];
			$arrData['city'] = $item['city'];
			$arrData['country_code'] = $item['country_code'];
			$arrData['user_email'] = $item['user_email'];
			$arrData['category_name'] = \productHelper::getCategoryNameByProductId($productId);
			$arrData['order_item_name'] = $item['order_item_name'];
			$arrData['item_customer_note'] = $item['item_note'];
			$arrData['order_item_sku'] = $item['order_item_sku'];
			$arrData['section_name'] = $this->getOrderItemAttribute($orderItemId);
			$arrData['product_item_price'] = $item['product_item_price'];
			$arrData['order_total'] = $item['order_total'];

			$this->writeData($arrData, '', $handle);
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
		$db = JFactory::getDbo();

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

		if ($this->fromDate)
		{
			$fromDate = strtotime($this->fromDate);
			$query->where($this->db->qn('o.cdate') . ' > ' . $this->db->q($fromDate));
		}

		if ($this->toDate)
		{
			$toDate = strtotime($this->toDate);
			$query->where($this->db->qn('o.cdate') . ' < ' . $this->db->q($toDate));
		}

		$data = $db->setQuery($query)->loadObjectList();

		if (empty($data))
		{
			return;
		}

		$handle = fopen($this->getFilePath(), 'a');
		$headers = array('Order number', 'Order status', 'Order Payment Status', 'Order date', 'Customer Note', 'Shipping method', 'Shipping user', 'Shipping address',
			'Shipping postalcode', 'Shipping city', 'Shipping country', 'Email');

		$orderItemHeaders = $this->getHeaderOrderItem();
		$headersOrderItem = array_merge($orderItemHeaders, array('Order Total'));
		$headers = array_merge($headers, $headersOrderItem);

		$this->writeData($headers, '', /** @scrutinizer ignore-type */ $handle);

		foreach ($data as $item)
		{
			$item = (array) $item;
			$shippingDetail = \Redshop\Shipping\Rate::decrypt($item['ship_method_id']);
			$item['ship_method_id'] = \JText::_($shippingDetail[1]);
			$item['cdate'] = \RedshopHelperDatetime::convertDateFormat($item['cdate']);

			$query = $db->getQuery(true)
				->select('order_item_name, order_item_sku, product_item_price')
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
				->select('order_item_name, order_item_sku, product_item_price')
				->from($db->qn('#__redshop_order_item'))
				->where($db->qn('order_id') . ' = ' . $db->q($order['order_id']));

			$orderItemNames = $db->setQuery($query)->loadAssocList();
			$maxOrderItemName = count($orderItemNames);

			for ($i = 0; $i < $maxOrderItemName; $i++)
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

		return $arrayHeaders;
	}

	public function getOrderItemAttribute($orderId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('section_name')
			->from($db->qn('#__redshop_order_attribute_item'))
			->where($db->qn('order_item_id') . ' = ' . /** @scrutinizer ignore-type */ $db->quote($orderId))
			->where($db->qn('section') . ' = ' . $db->quote('property'));

		return $db->setQuery($query)->loadResult();
	}
}