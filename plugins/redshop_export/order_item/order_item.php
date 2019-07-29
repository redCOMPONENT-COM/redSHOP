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
 * Plugins redSHOP Export Order Item
 *
 * @since  1.0
 */
class PlgRedshop_ExportOrder_Item extends AbstractExportPlugin
{
	/**
	 * Event run when user click on Start Export
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxOrder_Item_Start()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();
		
		$this->writeData($this->getHeader(), 'w+');
		
		return (int) $this->getTotalOrder_Item_Export();
	}
	
	/**
	 *
	 * @return  int
	 *
	 * @since  2.1.1
	 */
	protected function getTotalOrder_Item_Export()
	{
		$query = $this->getQuery();
		
		$query->clear('select')
			->clear('group')
			->select('COUNT(DISTINCT oi.order_item_id)');
		return (int) $this->db->setQuery($query)->loadResult();
	}
	
	/**
	 * Event run on export process
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function onAjaxOrder_Item_Export()
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
	public function onAjaxOrder_Item_Complete()
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
					$this->db->qn('oi.order_item_id'),
					$this->db->qn('o.order_id'),
					$this->db->qn('oi.product_id'),
					$this->db->qn('oi.order_item_name'),
					$this->db->qn('oi.product_quantity'),
					$this->db->qn('oi.product_item_price'),
					$this->db->qn('oi.product_attribute')
				)
			)
			->from($this->db->qn('#__redshop_order_item', 'oi'))
			->leftJoin($this->db->qn('#__redshop_orders', 'o') . ' ON ' . $this->db->qn('oi.order_id') . ' = ' . $this->db->qn('o.order_id'))
			->order($this->db->qn('oi.order_item_id') . ' ASC');
		return $subQuery;
	}
	
	protected function getHeader()
	{
		return array(
			'Order Item Number', 'Order Number', 'Product Number', 'Order Item Name', 'Quantity', 'Order Item Price', 'Order Item Attribute'
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
				$newData->product_attribute = strip_tags(str_replace(",", " ", $productAttribute->product_attribute));
			}
		}
	}
}