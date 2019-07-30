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

		$this->writeData($this->getHeader(), 'w+');

		return (int) $this->getTotalOrder_Export();
	}

	/**
	 *
	 * @return  int
	 *
	 * @since  2.1.1
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
		$subQuery = $this->db->getQuery(true)
			->select(
				array(
					$this->db->qn('o.order_number'),
					$this->db->qn('oi.order_item_id'),
					$this->db->qn('os.order_status_name'),
					$this->db->qn('o.order_payment_status'),
					$this->db->qn('o.cdate'),
					$this->db->qn('sr.shipping_class'),
					' concat(ouf.firstname, " " , ouf.lastname)  as shipping_user',
					$this->db->qn('ouf.address'),
					$this->db->qn('ouf.zipcode'),
					$this->db->qn('ouf.city'),
					$this->db->qn('ouf.country_code'),
					$this->db->qn('ouf.user_email'),
					$this->db->qn('oi.product_id'),
					$this->db->qn('oi.order_item_name'),
					$this->db->qn('oi.product_item_price'),
					$this->db->qn('oi.product_attribute'),
					$this->db->qn('o.order_total')
				)
			)
			->from($this->db->qn('#__redshop_orders', 'o'))
			->leftJoin($this->db->qn('#__redshop_order_users_info', 'ouf') . ' ON ' . $this->db->qn('o.order_id') . ' = ' . $this->db->qn('ouf.order_id'))
			->leftJoin($this->db->qn('#__redshop_order_item', 'oi') . ' ON ' . $this->db->qn('o.order_id') . ' = ' . $this->db->qn('oi.order_id'))
			->leftJoin($this->db->qn('#__redshop_shipping_rate', 'sr') . ' ON ' . $this->db->qn('sr.shipping_rate_id') . ' = ' . $this->db->qn('o.ship_method_id'))
			->leftJoin($this->db->qn('#__redshop_order_status', 'os') . ' ON ' . $this->db->qn('os.order_status_code') . ' = ' . $this->db->qn('o.order_status'))
			->where($this->db->qn('ouf.address_type') . ' = ' . $this->db->q('ST'))
			->order($this->db->qn('o.order_id') . ' ASC');

		return $subQuery;
	}

	protected function getHeader()
	{
		return array(
			'Order number', 'Order status', 'Order Payment Status', 'Order date', 'Shipping method', 'Shipping user', 'Shipping address',
			'Shipping postalcode', 'Shipping city', 'Shipping country', 'Email', 'Order Item Number', 'Product Number', 'Order Item Name', 'Order Item Price', 'Order Item Attribute', 'Order total'
		);
	}

	protected function processData(&$data)
	{
		$productHelper = productHelper::getInstance();
		if (empty($data))
		{
			return;
		}

		foreach ($data as $newData)
		{
			if ($newData->product_attribute)
			{
				$productAttribute = $productHelper->makeAttributeOrder($newData->order_item_id, 0, $newData->product_id, 0, 1);
				$newData->product_attribute = trim(str_replace("Subscription", " ", strip_tags(str_replace(",", " ", $productAttribute->product_attribute))));
			}
		}
	}
}