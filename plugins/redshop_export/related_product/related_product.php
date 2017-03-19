<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractExportPlugin;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Related Product
 *
 * @since  1.0
 */
class PlgRedshop_ExportRelated_Product extends AbstractExportPlugin
{
	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 *
	 * @TODO: Need to load XML File instead
	 */
	public function onAjaxRelated_Product_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return '';
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxRelated_Product_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->writeData($this->getHeader(), 'w+');

		return (int) $this->getTotal();
	}

	/**
	 * Event run on export process
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function onAjaxRelated_Product_Export()
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
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxRelated_Product_Complete()
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
		$db = $this->db;

		$query = $db->getQuery(true)
			->select($db->qn('p1.product_number', 'related_sku'))
			->select($db->qn('p2.product_number', 'product_sku'))
			->from($db->qn('#__redshop_product_related', 'rp'))
			->innerJoin($db->qn('#__redshop_product', 'p1') . ' ON ' . $db->qn('p1.product_id') . ' = ' . $db->qn('rp.related_id'))
			->innerJoin($db->qn('#__redshop_product', 'p2') . ' ON ' . $db->qn('p2.product_id') . ' = ' . $db->qn('rp.product_id'))
			->order($db->qn('p2.product_number'));

		return $query;
	}

	/**
	 * Method for get headers data.
	 *
	 * @return array
	 *
	 * @since  1.0.0
	 */
	protected function getHeader()
	{
		return array(
			'related_sku','product_sku'
		);
	}
}
