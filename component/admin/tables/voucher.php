<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Voucher
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.7
 */
class RedshopTableVoucher extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_voucher';

	/**
	 * @var  integer
	 */
	public $id;

	/**
	 * @var  string
	 */
	public $code;

	/**
	 * @var  float
	 */
	public $amount;

	/**
	 * @var  string
	 */
	public $type;

	/**
	 * @var  string
	 */
	public $start_date = '0000-00-00 00:00:00';

	/**
	 * @var  string
	 */
	public $end_date = '0000-00-00 00:00:00';

	/**
	 * @var  integer
	 */
	public $free_ship;

	/**
	 * @var  integer
	 */
	public $voucher_left;

	/**
	 * @var  integer
	 */
	public $published;

	/**
	 * Checks that the object is valid and able to be stored.
	 *
	 * This method checks that the parent_id is non-zero and exists in the database.
	 * Note that the root node (parent_id = 0) cannot be manipulated with this class.
	 *
	 * @return  boolean  True if all checks pass.
	 */
	protected function doCheck()
	{
		if (!parent::doCheck())
		{
			return false;
		}

		$db = $this->getDbo();

		// Check duplicate.
		$code = $this->get('code');

		$voucherQuery = $db->getQuery(true)
			->select($db->qn('code'))
			->from($db->qn('#__redshop_voucher'));

		if ($this->hasPrimaryKey())
		{
			$voucherQuery->where($db->qn('id') . ' <> ' . $this->id);
		}

		$couponQuery = $db->getQuery(true)
			->select($db->qn('code'))
			->from($db->qn('#__redshop_coupons'));
		$couponQuery->union($voucherQuery);

		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from('(' . $couponQuery . ') AS ' . $db->qn('data'))
			->where($db->qn('data.code') . ' = ' . $db->quote($code));

		if ($db->setQuery($query)->loadResult())
		{
			$this->setError(JText::_('COM_REDSHOP_VOUCHER_ERROR_CODE_ALREADY_EXIST'));

			return false;
		}

		return true;
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws  Exception
	 */
	protected function doBind(&$src, $ignore = array())
	{
		if (isset($src['voucher_products']) && !empty($src['voucher_products']))
		{
			$products = is_string($src['voucher_products']) ? explode(',', $src['voucher_products']) : $src['voucher_products'];
			$this->setOption('products', $products);
			unset($src['shopper_group']);
		}

		return parent::doBind($src, $ignore);
	}

	/**
	 * Do the database store.
	 *
	 * @param   boolean $updateNulls True to update null values as well.
	 *
	 * @return  boolean
	 */
	protected function doStore($updateNulls = false)
	{
		if (!parent::doStore($updateNulls))
		{
			return false;
		}

		if ($this->getOption('skip.updateProducts', false) === true || $this->getOption('inlineMode', false) === true)
		{
			return true;
		}

		return $this->updateProduct();
	}

	/**
	 * Method for update product xref.
	 *
	 * @return  boolean
	 */
	protected function updateProduct()
	{
		$db = $this->getDbo();

		// Clear current reference products.
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_product_voucher_xref'))
			->where($db->qn('voucher_id') . ' = ' . $this->id);
		$db->setQuery($query)->execute();

		$products = $this->getOption('products', null);

		if (empty(array_filter($products)))
		{
			return true;
		}

		$query->clear()
			->insert($db->qn('#__redshop_product_voucher_xref'))
			->columns($db->qn(array('voucher_id', 'product_id')));

		foreach ($products as $productId)
		{
			$query->values((int) $this->id . ',' . (int) $productId);
		}

		return $db->setQuery($query)->execute();
	}
}
