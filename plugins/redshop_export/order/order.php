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
use Joomla\Utilities\ArrayHelper;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Product
 *
 * @since  1.0
 */
class PlgRedshop_ExportOrder extends AbstractExportPlugin
{
	protected $fromDate = '';

	protected $toDate = '';

	public function onAjaxOrder_Config()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$configs = array();
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

		$headers = $this->getHeader();

		if (!empty($headers))
		{
			$this->writeData($headers, 'w+');
		}

		return (int) $this->getTotal();
	}

	/**
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	protected function getTotal()
	{
		$input = JFactory::getApplication()->input;
		$this->fromDate = $input->get('from_date', '');
		$this->toDate = $input->get('to_date', '');

		$query = $this->getQuery();
		$query->clear('select')
			->clear('group')
			->select('COUNT(DISTINCT oi.order_item_id)');

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
		RedshopHelperAjax::validateAjaxRequest();
		$input = JFactory::getApplication()->input;
		$limit = $input->getInt('limit', 0);
		$start = $input->getInt('start', 0);

		return $this->exporting($start, $limit);
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
		$input = JFactory::getApplication()->input;
		$this->fromDate = $input->get('from_date', '');
		$this->toDate = $input->get('to_date', '');

		$query = $this->db->getQuery(true)
			->select(
				array(
					$this->db->qn('o.order_id'),
					$this->db->qn('oi.order_item_id'),
					$this->db->qn('oi.order_item_name'),
					'oi.product_item_price_excl_vat as product_price',
					$this->db->qn('oi.product_quantity'),
					$this->db->qn('oi.product_attribute'),
					'oi.customer_note AS item_note',
					$this->db->qn('os.order_status_name'),
					$this->db->qn('o.order_payment_status'),
					$this->db->qn('o.cdate'),
					$this->db->qn('o.customer_note'),
					$this->db->qn('o.ship_method_id'),
					' concat(ouf.firstname, " " , ouf.lastname)  as shipping_user',
					$this->db->qn('ouf.address'),
					$this->db->qn('ouf.zipcode'),
					$this->db->qn('ouf.city'),
					$this->db->qn('ouf.company_name'),
					$this->db->qn('ouf.vat_number'),
					$this->db->qn('ouf.country_code'),
					$this->db->qn('ouf.user_email'),
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
	protected function processData(&$data)
	{
		$db = JFactory::getDbo();
		$newData = array();

		if (empty($data))
		{
			return;
		}

		foreach ($data as $item)
		{
			$item = (array) $item;
			$item['order_id'] = $item['order_id'];
			$item['order_item_id'] = $item['order_item_id'];
			$item['order_item_name'] = $item['order_item_name'];
			$item['product_price'] = $item['product_price'];
			$item['product_quantity'] = $item['product_quantity'];

			$query = $db->getQuery(true)
				->select('section, section_name')
				->from($db->qn('#__redshop_order_attribute_item'))
				->where($db->qn('order_item_id') . ' = ' . $db->q($item['order_item_id']));

			$attributeNames = $db->setQuery($query)->loadObjectList();
			$orderAttribute = '';

			if ($attributeNames)
			{
				for ($it = 0, $in = count($attributeNames); $it < $in; $it++)
				{
					$orderAttribute .= $attributeNames[$it]->section_name . "\n";
				}

				if ($orderAttribute)
				{
					$item['product_attribute'] = $orderAttribute;
				}
				else
				{
					$item['product_attribute'] = '';
				}
			}
			else
			{
				$item['product_attribute'] = '';
			}

			$item['item_note'] = $item['item_note'];
			$item['order_status_name'] = $item['order_status_name'];
			$item['order_payment_status'] = $item['order_payment_status'];
			$item['cdate'] = \RedshopHelperDatetime::convertDateFormat($item['cdate']);
			$item['customer_note'] = $item['customer_note'];
			$shippingDetail = \Redshop\Shipping\Rate::decrypt($item['ship_method_id']);
			$item['ship_method_id'] = $shippingDetail[2];
			$item['shipping_user'] = $item['shipping_user'];
			$item['address'] = $item['address'];
			$item['zipcode'] = $item['zipcode'];
			$item['city'] = $item['city'];
			$item['company_name'] = $item['company_name'];
			$item['vat_number'] = $item['vat_number'];
			$item['country_code'] = $item['country_code'];
			$item['user_email'] = $item['user_email'];
			$item['order_total'] = $item['order_total'];

			$newData[] = $item;
		}

		$data = $newData;
	}
}