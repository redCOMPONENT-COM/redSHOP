<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\Import;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Shopper group product price
 *
 * @since  1.0
 */
class PlgRedshop_ImportShopper_group_product_price extends Import\AbstractBase
{
	/**
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $primaryKey = 'price_id';

	/**
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $nameKey = 'product_price';

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShopper_group_product_price_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		// Ajax response
		$this->config();
	}

	/**
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	public function onAjaxShopper_group_product_price_Import()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return $this->import();
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

		return JTable::getInstance('Prices_detail', 'Table');
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
		$isNew = false;
		$db    = $this->db;

		$shopperGroupId = $this->getShopperGroupId(trim($data['shopper_group_id']));

		if (!$shopperGroupId)
		{
			return false;
		}

		if (!empty($data['discount_start_date']))
		{
			$data['discount_start_date'] = is_int($data['discount_start_date']) ? $data['discount_start_date'] : strtotime($data['discount_start_date']);
		}

		if (!empty($data['discount_end_date']))
		{
			$data['discount_end_date'] = is_int($data['discount_end_date']) ? $data['discount_end_date'] : strtotime($data['discount_end_date']);
		}

		$data['cdate'] = date('Y-m-d');
		$data['product_currency'] = Redshop::getConfig()->get('CURRENCY_CODE');

		$query = $db->getQuery(true)
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_number') . ' = ' . $db->q($data['product_number']));

		$data['product_id'] = $db->setQuery($query)->loadResult();

		if (!empty($data['product_id']))
		{
			if (array_key_exists($this->primaryKey, $data) && $data[$this->primaryKey])
			{
				$isNew = $table->load($data[$this->primaryKey]);
			}

			if (!$table->bind($data))
			{
				return false;
			}

			if ((!$isNew && !$db->insertObject('#__redshop_product_price', $table, $this->primaryKey)) || !$table->store())
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Get Shopper Group Id from input
	 *
	 * @param   integer  $shopperGroupInputId  Shopper Group Id from CSV File
	 *
	 * @return  integer  Shopper Group Id
	 *
	 * @since  1.0
	 */
	public function getShopperGroupId($shopperGroupInputId)
	{
		// Initialiase variables.
		$db    = $this->db;
		$query = $db->getQuery(true)
			->select('shopper_group_id')
			->from($db->qn('#__redshop_shopper_group'))
			->where($db->qn('shopper_group_id') . ' = ' . $db->q((int) trim($shopperGroupInputId)));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$shopperGroupId = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $shopperGroupId;
	}
}
