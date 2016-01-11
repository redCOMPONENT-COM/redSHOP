<?php
/**
 * @package     Redshop.Library
 * @subpackage  Product.Compare
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Common architecture for payment class.
 *
 * @package     Redshop.Library
 * @subpackage  Product.Compare
 * @since       1.5
 */
class RedshopProductCompare implements Countable
{
	// Array stores the list of items in the cart:
	public $compare = array();

	protected $item = null;

	protected $key = null;

	// Constructor just sets the object up for usage:
	public function __construct()
	{
		$this->compare = JFactory::getSession()->get('product.compare', array());
	}

	// Returns a Boolean indicating if the cart is empty:
	public function isEmpty()
	{
		return (empty($this->compare['items']));
	}

	public function setKey()
	{
		$this->key = $this->item->productId;

		if (PRODUCT_COMPARISON_TYPE == 'category')
		{
			$this->key = $this->item->productId . '.' . $this->item->categoryId;

			// There is no category id set while removing find the key
			if (!$this->item->categoryId)
			{
				$this->key = $this->findItemKey();
			}
		}

		return $this->key;
	}

	protected function validItem()
	{
		return (
			$this->isEmpty()
			|| (PRODUCT_COMPARISON_TYPE == 'category' && $this->getCategoryId() === $this->item->categoryId)
		);
	}

	// Adds a new item to the cart:
	public function addItem($item)
	{
		$this->item = $item;

		// Throw an exception for invalid entried
		if (!$this->validItem())
		{
			throw new Exception('0`' . JText::_('COM_REDSHOP_ERROR_ADDING_PRODUCT_TO_COMPARE'), 1);
		}

		// Set Unique key based on comparision type
		$this->setKey();

		// Throw an exception if there's no id:
		if (!$this->key) throw new Exception('It requires items with unique product id.');

		// Throw an exception if comparison is overlimit.
		if ($this->count() >= PRODUCT_COMPARE_LIMIT)
		{
			throw new Exception('0`' . JText::_('COM_REDSHOP_LIMIT_CROSS_TO_COMPARE'));
		}

		// Throw an exception if already found in compare list.
		if (isset($this->compare['items'][$this->key]))
		{
			throw new Exception(JText::_('COM_REDSHOP_ALLREADY_ADDED_TO_COMPARE'));
		}
		// Add if not found
		else
		{
			$this->compare['category']          = $this->item->categoryId;
			$this->compare['items'][$this->key] = array('item' => $this->item);
		}

		$this->updateSession();
	}

	// Removes an item from the cart:
	public function deleteItem($item = null)
	{
		if (!is_object($item))
		{
			$this->compare['items'] = array();
		}
		else
		{
			$this->item = $item;

			// Set Unique key based on comparision type
			$this->setKey();

			// Remove it
			if (isset($this->compare['items'][$this->key]))
			{
				unset($this->compare['items'][$this->key]);
			}
		}

		$this->updateSession();
	}

	protected function findItemKey()
	{
		$filtered = array_keys(array_filter($this->getItems(), array($this, 'isKeyMatch'), ARRAY_FILTER_USE_KEY));

		if (!empty($filtered))
		{
			return $filtered[0];
		}

		return null;
	}

	protected function isKeyMatch($key)
	{
		return (is_integer(strpos($key, $this->item->productId . '.')));
	}

	// Required by Countable:
	public function count()
	{
		return count($this->compare['items']);
	}

	public function updateSession()
	{
		$this->compare['total'] = $this->count();

		JFactory::getSession()->set('product.compare', $this->compare);
	}

	public function getItems()
	{
		return $this->compare['items'];
	}

	public function getItemsTotal()
	{
		return (int) $this->compare['total'];
	}

	public function getCategoryId()
	{
		return (int) $this->compare['category'];
	}

	public function getAjaxResponse()
	{
		return RedshopLayoutHelper::render(
			'product.compare_ajax',
			array('object' => $this)
		);
	}

	public function getItemKey($productId, $categoryId = 0)
	{
		$this->item = new stdClass;
		$this->item->productId = $productId;
		$this->item->categoryId = $categoryId;

		return $this->findItemKey();
	}
}
