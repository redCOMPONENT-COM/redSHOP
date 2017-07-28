<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Table Mass Discount
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.3
 */
class RedshopTableMass_Discount extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_mass_discount';

	/**
	 * Called before bind().
	 *
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src     An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function beforeBind(&$src, $ignore = array())
	{
		// Bind: Start Date unix
		if (isset($src['start_date']) && !empty($src['start_date']) && !is_numeric($src['start_date']))
		{
			$date = JFactory::getDate($src['start_date']);
			$src['start_date'] = $date->toUnix();
		}

		// Bind: End Date unix
		if (isset($src['end_date']) && !empty($src['end_date']) && !is_numeric($src['end_date']))
		{
			$date = JFactory::getDate($src['end_date']);
			$src['end_date'] = $date->toUnix();
		}

		// Bind: Discount products
		if (isset($src['discount_product']) && !empty($src['discount_product']) && is_array($src['discount_product']))
		{
			$src['discount_product'] = ArrayHelper::toInteger($src['discount_product']);
			$src['discount_product'] = array_unique(array_filter($src['discount_product']));
			$src['discount_product'] = implode(',', $src['discount_product']);
		}

		// Bind: Categories
		if (isset($src['category_id']) && !empty($src['category_id']) && is_array($src['category_id']))
		{
			$src['category_id'] = ArrayHelper::toInteger($src['category_id']);
			$src['category_id'] = array_unique(array_filter($src['category_id']));
			$src['category_id'] = implode(',', $src['category_id']);
		}

		// Bind: Manufacturers
		if (isset($src['manufacturer_id']) && !empty($src['manufacturer_id']) && is_array($src['manufacturer_id']))
		{
			$src['manufacturer_id'] = ArrayHelper::toInteger($src['manufacturer_id']);
			$src['manufacturer_id'] = array_unique(array_filter($src['manufacturer_id']));
			$src['manufacturer_id'] = implode(',', $src['manufacturer_id']);
		}

		return parent::beforeBind($src, $ignore);
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
	 * @throws  \InvalidArgumentException
	 */
	protected function doBind(&$src, $ignore = array())
	{
		if (empty($src['discount_product']) && empty($this->discount_product))
		{
			$this->discount_product = null;
			unset($src['discount_product']);
		}

		if (empty($src['category_id']) && empty($this->category_id))
		{
			$this->category_id = null;
			unset($src['category_id']);
		}

		return parent::doBind($src, $ignore);
	}

	/**
	 * Delete one or more registers
	 *
	 * @param   string/array  $pk  Array of ids or ids comma separated
	 *
	 * @return  boolean  Deleted successfuly?
	 */
	protected function doDelete($pk = null)
	{
		$massDiscountIds = $pk;

		if (!is_array($massDiscountIds))
		{
			$massDiscountIds = array($massDiscountIds);
		}

		$massDiscountIds = ArrayHelper::toInteger($massDiscountIds);
		$productIds      = array();

		$table = clone $this;

		foreach ($massDiscountIds as $massDiscountId)
		{
			if (!$table->load($massDiscountId))
			{
				continue;
			}

			if (!empty($table->get('discount_product')))
			{
				$this->updateProduct($table->get('discount_product'));
			}

			$categories = explode(',', $table->category_id);
			$categories = array_filter(ArrayHelper::toInteger($categories));

			foreach ($categories as $category)
			{
				$products = productHelper::getInstance()->getProductCategory($category);

				if (empty($products))
				{
					continue;
				}

				foreach ($products as $product)
				{
					$productIds[] = $product->product_id;
				}
			}

			$manufacturers = explode(',', $table->manufacturer_id);
			$manufacturers = array_filter(ArrayHelper::toInteger($manufacturers));

			foreach ($manufacturers as $manufacturer)
			{
				$products = $this->getProductsFromManufacturer($manufacturer);

				if (empty($products))
				{
					continue;
				}

				foreach ($products as $product)
				{
					$productIds[] = $product->product_id;
				}
			}
		}

		if (!empty($productIds))
		{
			$this->updateProduct($productIds);
		}

		return parent::doDelete($pk);
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
		if (!parent::doCheck())
		{
			return false;
		}

		if (empty($this->name))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MASS_DISCOUNT_MISSING_DISCOUNT_NAME'), 'error');

			return false;
		}

		if (empty($this->amount))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MASS_DISCOUNT_DISCOUNT_AMOUNT_MUST_BE_LARGER_THAN_ZERO'), 'error');

			return false;
		}

		if (is_null($this->type))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MASS_DISCOUNT_DISCOUNT_TYPE_IS_REQUIRED'), 'error');

			return false;
		}

		if (empty($this->discount_product) && empty($this->category_id) && empty($this->manufacturer_id))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MASS_DISCOUNT_DETAIL_NO_PRODUCTS_SELECTED'), 'error');

			return false;
		}

		if ($this->start_date > $this->end_date)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MASS_DISCOUNT_ENDDATE_LOWER_STARTDATE'), 'error');

			return false;
		}

		// Convert startdate to same day but at early morning
		$this->start_date = RedshopHelperDatetime::generateTimestamp($this->start_date, false);

		// Convert enddate to same day but at middle night
		$this->end_date = RedshopHelperDatetime::generateTimestamp($this->end_date);

		return true;
	}

	/**
	 * Called before store(). Overriden to send isNew to plugins.
	 *
	 * @param   boolean  $updateNulls  True to update null values as well.
	 * @param   boolean  $isNew        True if we are adding a new item.
	 * @param   mixed    $oldItem      null for new items | JTable otherwise
	 *
	 * @return  boolean  True on success.
	 */
	protected function beforeStore($updateNulls = false, $isNew = false, $oldItem = null)
	{
		if (!parent::beforeStore($updateNulls, $isNew, $oldItem))
		{
			return false;
		}

		$db            = $this->_db;
		$query         = $db->getQuery(true);
		$productHelper = productHelper::getInstance();

		$this->updateProductsBaseDiscountProduct($this, $isNew, $oldItem);

		/*
		 * Update products for "category_id"
		 */
		$categories          = $isNew ? array() : explode(',', $oldItem->category_id);
		$newCategories       = explode(',', $this->category_id);
		$isChangeCategory    = false;
		$isNewChangeCategory = false;

		$diffCategories = array_diff($categories, $newCategories);

		if (count($diffCategories))
		{
			sort($diffCategories);
		}
		else
		{
			$isChangeCategory = true;
		}

		$diffCategories = array_filter(array_values($diffCategories));

		foreach ($diffCategories as $diffCategory)
		{
			$products   = $productHelper->getProductCategory($diffCategory);
			$productIds = array();

			foreach ($products as $product)
			{
				$productIds[] = $product->product_id;
			}

			if (empty($productIds))
			{
				continue;
			}

			$query->clear()
				->update($db->qn('#__redshop_product'))
				->set($db->qn('product_on_sale') . ' = 0')
				->where($db->qn('product_id') . ' IN (' . implode(',', $productIds) . ')');

			if (!$db->setQuery($query)->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		$newDiffCategories = array_diff($newCategories, $categories);

		if (count($newDiffCategories))
		{
			sort($newDiffCategories);
		}
		else
		{
			$isNewChangeCategory = true;
		}

		if ($isChangeCategory && $isNewChangeCategory)
		{
			$newDiffCategories = $categories;
		}

		foreach ($newDiffCategories as $newDiffCategory)
		{
			$products = $productHelper->getProductCategory($newDiffCategory);

			foreach ($products as $product)
			{
				$productData = Redshop::product((int) $product->product_id);

				if ($this->type == 1)
				{
					$price = $productData->product_price - ($productData->product_price * $this->amount / 100);
				}
				else
				{
					$price = $productData->product_price - $this->amount;
				}

				$price = $productHelper->productPriceRound($price);

				$query->clear()
					->update($db->qn('#__redshop_product'))
					->set($db->qn('product_on_sale') . ' = 1')
					->set($db->qn('discount_price') . ' = ' . $price)
					->set($db->qn('discount_stratdate') . ' = ' . $this->start_date)
					->set($db->qn('discount_enddate') . ' = ' . $this->end_date)
					->where($db->qn('product_id') . ' = ' . $product->product_id);

				if (!$db->setQuery($query)->execute())
				{
					$this->setError($db->getErrorMsg());

					return false;
				}
			}
		}

		/*
		 * Update products for "manufacturer_id"
		 */
		$isChangeManufacturer    = false;
		$isNewChangeManufacturer = false;
		$manufacturers           = $isNew ? array() : explode(',', $oldItem->manufacturer_id);
		$newManufacturers        = explode(',', $this->manufacturer_id);

		$diffManufacturers = array_diff($manufacturers, $newManufacturers);

		if (count($diffManufacturers))
		{
			sort($diffManufacturers);
		}
		else
		{
			$isChangeManufacturer = true;
		}

		$diffManufacturers = array_filter(array_values($diffManufacturers));

		if (!empty($diffManufacturers))
		{
			$query->clear()
				->update($db->qn('#__redshop_product'))
				->set($db->qn('product_on_sale') . ' = 0')
				->where($db->qn('manufacturer_id') . ' IN (' . implode(',', $diffManufacturers) . ')');

			if (!$db->setQuery($query)->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		$newDiffManufacturers = array_diff($newManufacturers, $manufacturers);

		if (count($newDiffManufacturers))
		{
			sort($newDiffManufacturers);
		}
		else
		{
			$isNewChangeManufacturer = true;
		}

		if ($isNewChangeManufacturer && $isChangeManufacturer)
		{
			$newDiffManufacturers = $manufacturers;
		}

		$newDiffManufacturers = array_filter(array_values($newDiffManufacturers));

		if (!empty($newDiffManufacturers))
		{
			$query->clear()
				->select($db->qn('product_id'))
				->from($db->qn('#__redshop_product'))
				->where($db->qn('manufacturer_id') . ' IN (' . implode(',', $newDiffManufacturers) . ')');
			$productIds = $db->setQuery($query)->loadColumn();

			foreach ($productIds as $productId)
			{
				$productData = Redshop::product((int) $productId);

				if ($this->type == 1)
				{
					$price = $productData->product_price - ($productData->product_price * $this->amount / 100);
				}
				else
				{
					$price = $productData->product_price - $this->amount;
				}

				$price = $productHelper->productPriceRound($price);

				$query->clear()
					->update($db->qn('#__redshop_product'))
					->set($db->qn('product_on_sale') . ' = 1')
					->set($db->qn('discount_price') . ' = ' . $price)
					->set($db->qn('discount_stratdate') . ' = ' . $this->start_date)
					->set($db->qn('discount_enddate') . ' = ' . $this->end_date)
					->where($db->qn('product_id') . ' = ' . $productData->product_id);

				if (!$db->setQuery($query)->execute())
				{
					$this->setError($db->getErrorMsg());

					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Update Product On Sale status
	 *
	 * @param   array  $productIds  List of products.
	 *
	 * @return  boolean
	 */
	public function updateProduct($productIds)
	{
		if (empty($productIds))
		{
			return true;
		}

		if (!is_array($productIds))
		{
			$productIds = explode(',', $productIds);
		}

		$productIds = ArrayHelper::toInteger($productIds);

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_product'))
			->set($db->qn('product_on_sale') . ' = 0')
			->set($db->qn('discount_stratdate') . ' = 0')
			->set($db->qn('discount_enddate') . ' = 0')
			->set($db->qn('discount_price') . ' = 0')
			->where($db->qn('product_id') . ' IN (' . implode(',', $productIds) . ')');

		return $db->setQuery($query)->execute();
	}

	/**
	 * Method for get product of manufacturer.
	 *
	 * @param   int  $id  ID of manufacturer
	 *
	 * @return  mixed
	 *
	 * @since  2.0.3
	 */
	public function getProductsFromManufacturer($id)
	{
		if (!$id)
		{
			return array();
		}

		$db = $this->_db;

		$query = $db->getQuery(true)
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_product'))
			->where($db->qn('manufacturer_id') . ' = ' . (int) $id);

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Method for update product base on "discount_product"
	 *
	 * @param   self     $data     New data
	 * @param   boolean  $isNew    Is new or not.
	 * @param   mixed    $oldItem  Old data
	 *
	 * @return  boolean
	 *
	 * @since   2.0.3
	 */
	protected function updateProductsBaseDiscountProduct($data, $isNew = false, $oldItem = null)
	{
		$db = $this->_db;
		$query = $db->getQuery(true);
		$isChangeProduct = false;
		$isNewChangeProduct = false;
		$productHelper = productHelper::getInstance();

		$discountProducts    = $isNew ? array() : explode(',', $oldItem->discount_product);
		$newDiscountProducts = explode(',', $this->discount_product);

		$diffProducts = array_filter(array_diff($discountProducts, $newDiscountProducts));

		if (count($diffProducts))
		{
			sort($diffProducts);
		}
		else
		{
			$isChangeProduct = true;
		}

		if (!empty($diffProducts))
		{
			$query->clear()
				->update($db->qn('#__redshop_product'))
				->set($db->qn('product_on_sale') . ' = 0')
				->where($db->qn('product_id') . ' IN (' . implode(',', $diffProducts) . ')');

			if (!$db->setQuery($query)->execute())
			{
				$this->setError($db->getErrorMsg());

				return false;
			}
		}

		$newDiffProducts = array_diff($newDiscountProducts, $discountProducts);

		if (count($newDiffProducts))
		{
			sort($newDiffProducts);
		}
		else
		{
			$isNewChangeProduct = true;
		}

		if ($isChangeProduct && $isNewChangeProduct)
		{
			$newDiffProducts = $discountProducts;
		}

		$newDiffProducts = array_filter(array_values($newDiffProducts));

		if (empty($newDiffProducts))
		{
			return true;
		}

		foreach ($newDiffProducts as $newDiffProduct)
		{
			try
			{
				$productData = Redshop::product($newDiffProduct);

				// Ticket ONKELP-161: Temporary comment these code to by pass product_on_sale check before apply another mass discount
				if ($this->type == 1)
				{
					$price = $productData->product_price - ($productData->product_price * $this->amount / 100);
				}
				else
				{
					$price = $productData->product_price - $this->amount;
				}

				$price = $productHelper->productPriceRound($price);
				$query->clear();

				// Update fields
				$update = array(
					$db->qn('product_on_sale') . ' = 1',
					$db->qn('discount_price') . ' = ' . (float) $price,
					$db->qn('discount_stratdate') . ' = ' . (int) $this->start_date,
					$db->qn('discount_enddate') . ' = ' . (int) $this->end_date
				);

				// By condition
				$conditions = array (
					$db->qn('product_id') . ' = ' . (int) $newDiffProduct
				);
				$query->update($db->qn('#__redshop_product'))->set($update)->where($conditions);
				$db->setQuery($query);

				if (!$db->execute())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
			catch (Exception $e)
			{
				JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			}
		}

		return true;
	}
}
