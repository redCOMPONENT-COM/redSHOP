<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractImportPlugin;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Related product
 *
 * @since  1.0
 */
class PlgRedshop_ImportRelated_product extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $primaryKey = 'related_sku';

	/**
	 * @var string
	 */
	protected $nameKey = 'product_sku';

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxRelated_product_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return '';
	}

	/**
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	public function onAjaxRelated_product_Import()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input           = JFactory::getApplication()->input;
		$this->encoding  = $input->getString('encoding', 'UTF-8');
		$this->separator = $input->getString('separator', ',');
		$this->folder    = $input->getCmd('folder', '');

		return json_encode($this->importing());
	}

	/**
	 * Method for get table object.
	 *
	 * @return  \JTable
	 *
	 * @since   1.0.0
	 */
	public function getTable()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return JTable::getInstance('Related_product', 'Table');
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable  $table  Header array
	 * @param   array    $data   Data array
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function processImport($table, $data)
	{
		$db  = $this->db;
		$rId = $this->getProductIdByNumber($data['related_sku']);
		$pId = $this->getProductIdByNumber($data['product_sku']);
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__redshop_product_related'))
			->where($db->qn('related_id') . ' = ' . $db->q((int) $rId))
			->where($db->qn('product_id') . ' = ' . $db->q((int) $pId));

		if ($db->setQuery($query)->loadResult())
		{
			return true;
		}

		$query->clear()
			->insert($db->qn('#__redshop_product_related'))
			->columns($db->qn(array('related_id', 'product_id')))
			->values($rId . ',' . $pId);

		return $db->setQuery($query)->execute();
	}

	/**
	 * Get product id by product number.
	 *
	 * @param   string  $productNumber  product number
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function getProductIdByNumber($productNumber)
	{
		$db = $this->db;
		$query = $db->getQuery(true)
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_number') . ' = ' . $db->q($productNumber));

		return $db->setQuery($query)->loadResult();
	}
}
