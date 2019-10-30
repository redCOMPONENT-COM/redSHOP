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
 * Table Discount Product
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.1.0
 */
class RedshopTableDiscount_Product extends RedshopTable
{
	/**
	 * The table name without the prefix.
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_discount_product';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'discount_product_id';

	/**
	 * @var  integer
	 */
	public $discount_product_id;

	/**
	 * @var  integer
	 */
	public $amount = 0;

	/**
	 * @var  integer
	 */
	public $condition = 1;

	/**
	 * @var  float
	 */
	public $discount_amount = 0.0;

	/**
	 * @var  integer
	 */
	public $discount_type = 0;

	/**
	 * @var  integer
	 */
	public $start_date = 0;

	/**
	 * @var  integer
	 */
	public $end_date = 0;

	/**
	 * @var  integer
	 */
	public $published = 1;

	/**
	 * @var  string
	 */
	public $category_ids = '';

	/**
	 * Do the database store.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 *
	 * @return  boolean
	 */
	protected function doStore($updateNulls = false)
	{
		if (!parent::doStore($updateNulls))
		{
			return false;
		}

		if ($this->getOption('inlineMode', false) === true)
		{
			return true;
		}

		return $this->updateShopperGroups();
	}

	/**
	 * Method for update shopper group xref.
	 *
	 * @return  boolean
	 */
	protected function updateShopperGroups()
	{
		$db = $this->getDbo();

		// Clear current reference products.
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_discount_product_shoppers'))
			->where($db->qn('discount_product_id') . ' = ' . $this->discount_product_id);
		$db->setQuery($query)->execute();

		$shopperGroupIds = $this->getOption('shopperGroups', null);

		if (empty($shopperGroupIds) || empty(array_filter($shopperGroupIds)))
		{
			return true;
		}

		$query->clear()
			->insert($db->qn('#__redshop_discount_product_shoppers'))
			->columns($db->qn(array('discount_product_id', 'shopper_group_id')));

		foreach ($shopperGroupIds as $shopperGroupId)
		{
			$query->values((int) $this->discount_product_id . ',' . (int) $shopperGroupId);
		}

		return $db->setQuery($query)->execute();
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean         True on success.
	 *
	 * @throws  Exception
	 */
	protected function doBind(&$src, $ignore = array())
	{
		if (isset($src['shopper_group']) && !empty($src['shopper_group']))
		{
			$shopperGroups = is_string($src['shopper_group']) ? explode(',', $src['shopper_group']) : $src['shopper_group'];
			$this->setOption('shopperGroups', array_values(array_filter($shopperGroups)));
			unset($src['shopper_group']);
		}

		return parent::doBind($src, $ignore);
	}

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
		// Check amount
		if ((float) $this->amount <= 0.0)
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_DISCOUNT_PRODUCT_ERROR_AMOUNT_ZERO'));

			return false;
		}

		// Check discount amount
		if ((float) $this->discount_amount <= 0.0)
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_DISCOUNT_PRODUCT_ERROR_DISCOUNT_AMOUNT_ZERO'));

			return false;
		}

		// Check amount and discount amount
		if (((float) $this->amount) < (float) $this->discount_amount)
		{
			/** @scrutinizer ignore-deprecated */
			$this->setError(JText::_('COM_REDSHOP_DISCOUNT_PRODUCT_ERROR_AMOUNT_HIGHT_DISCOUNT_AMOUNT'));

			return false;
		}

		// If discount type is percent. Make sure discount amount not higher than 100.
		if ($this->discount_type == 1 && $this->discount_amount > 100)
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_DISCOUNT_PRODUCT_ERROR_DISCOUNT_PERCENTAGE'));

			return false;
		}

		// Make sure start date always lower than end date.
		if (!empty($this->start_date) && !empty($this->end_date) && $this->start_date > $this->end_date)
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_DISCOUNT_PRODUCT_ERROR_START_DATE_SAME_HIGH_END_DATE'));

			return false;
		}

		// Check shopper groups
		if (empty($this->getOption('shopperGroups', array())))
		{
			/** @scrutinizer ignore-deprecated */ $this->setError(JText::_('COM_REDSHOP_DISCOUNT_PRODUCT_ERROR_MISSING_SHOPPER_GROUPS'));

			return false;
		}

		return parent::doCheck();
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   mixed  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean            Deleted successfully?
	 */
	public function doDelete($pk = null)
	{
		$discountProductId = $this->discount_product_id;

		if (!parent::doDelete($pk))
		{
			return false;
		}

		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_discount_product_shoppers'))
			->where($db->qn('discount_product_id') . ' = ' . $discountProductId);

		return $db->setQuery($query)->execute();
	}
}
