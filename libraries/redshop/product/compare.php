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
	/**
	 * Stores the compare items information
	 *
	 * @var  array
	 */
	public $compare = array();

	/**
	 * Items which will be added in compare list
	 *
	 * @var  object
	 */
	protected $item = null;

	/**
	 * Item unique key for compare list array
	 *
	 * @var  string
	 */
	protected $key = null;

	/**
	 * Init compare array and store the information from session
	 */
	public function __construct()
	{
		$this->compare = JFactory::getSession()->get(
			'product.compare',
			array(
				'items'    => array(),
				'total'    => 0,
				'category' => 0
			)
		);

		/*
		 * Clean session if user change comparision type to 'category' and different category id found in the last item list,
		 * in order to avoid backward incompatibility and user confusion
		 */
		if (Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE') == 'category' && $this->isMixedCategoryId())
		{
			$this->cleanSession();
		}
	}

	/**
	 * Returns a Boolean indicating if the compare items is empty
	 *
	 * @return  boolean
	 */
	public function isEmpty()
	{
		return (empty($this->compare['items']));
	}

	/**
	 * Prepare unique key for compare list array
	 *
	 * @return  string  Unique Key for compare array
	 */
	public function setKey()
	{
		$this->key = $this->item->productId;

		$this->key = $this->item->productId . '.' . $this->item->categoryId;

		// There is no category id set while removing find the key
		if (!$this->item->categoryId)
		{
			$this->key = $this->findItemKey();
		}

		return $this->key;
	}

	/**
	 * Check the new item is valid or not
	 *
	 * @return  boolean  Check the product is from same category or not based on config.
	 */
	protected function validItem()
	{
		return (
			$this->isEmpty()
			|| (Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE') == 'category' && in_array($this->item->categoryId, $this->getCategoryIds()))
			|| Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE') == 'global'
		);
	}

	/**
	 * Adds a new item to the session
	 *
	 * @param   object  $item  Compare Item info
	 *
	 * @throws  exception      Throw exception if not valid items and not unique keys
	 *
	 * @return  void
	 */
	public function addItem($item)
	{
		$this->item  = $item;
		$productData = RedshopHelperProduct::getProductById($this->item->productId);

		// Throw an exception for invalid entried
		if (!$this->validItem())
		{
			throw new Exception(JText::_('COM_REDSHOP_ERROR_ADDING_PRODUCT_TO_COMPARE'), 1);
		}

		// Set Unique key based on comparision type
		$this->setKey();

		// Throw an exception if there's no id:
		if (!$this->key)
		{
			throw new Exception(JText::_('COM_REDSHOP_ERROR_REQUIRE_UNIQUE_PRODUCT_ID_TO_COMPARE'));
		}

		// Throw an exception if comparison is over limit.
		if ($this->count() >= Redshop::getConfig()->get('PRODUCT_COMPARE_LIMIT'))
		{
			throw new Exception(JText::_('COM_REDSHOP_LIMIT_CROSS_TO_COMPARE'));
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
			$this->compare['categories']        = $productData->categories;
			$this->compare['items'][$this->key] = array('item' => $this->item);
		}

		$this->updateSession();
	}

	/**
	 * Removes an item from the list
	 *
	 * @param   object $item Item object to delete - Null if want to remove All
	 *
	 * @return  void
	 */
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

	/**
	 * Find key from given item
	 *
	 * @return  string  Key matched
	 */
	protected function findItemKey()
	{
		$filtered = array_keys(array_filter(array_keys($this->getItems()), array($this, 'isKeyMatch')));

		if (!empty($filtered))
		{
			return $filtered[0];
		}

		return null;
	}

	/**
	 * Check the key for having product id
	 *
	 * @param   string $key Item key
	 *
	 * @return  boolean
	 */
	protected function isKeyMatch($key)
	{
		return (is_integer(strpos($key, $this->item->productId . '.')));
	}

	/**
	 * Check if session have items with different category id
	 *
	 * @return  boolean
	 */
	public function isMixedCategoryId()
	{
		$cids = array();

		foreach ($this->compare['items'] as $key => $value)
		{
			if (in_array($value['item']->categoryId, $this->getCategoryIds()))
			{
				continue;
			}

			array_push($cids, $value['item']->categoryId);
		}

		// Count the number of category id in item list
		$countCid = count(array_unique($cids));

		return ($countCid > 1);
	}

	/**
	 * Count compare Items
	 *
	 * @return  integer  Total items
	 */
	public function count()
	{
		return count($this->compare['items']);
	}

	/**
	 * Update items info in session
	 *
	 * @return  void
	 */
	public function updateSession()
	{
		$this->compare['total'] = $this->count();

		JFactory::getSession()->set('product.compare', $this->compare);
	}

	/**
	 * Clean items info
	 *
	 * @return  void
	 */
	public function cleanSession()
	{
		JFactory::getSession()->set('product.compare', array(
				'items'    => array(),
				'total'    => 0,
				'category' => 0
			)
		);
	}

	/**
	 * Get compare items array
	 *
	 * @return  array  Items info
	 */
	public function getItems()
	{
		if (isset($this->compare['items']))
		{
			return $this->compare['items'];
		}

		return array();
	}

	/**
	 * Get total of items in array
	 *
	 * @return  integer  Total number of items
	 */
	public function getItemsTotal()
	{
		return (int) $this->compare['total'];
	}

	/**
	 * Get category id stored in compare list
	 *
	 * @return  integer  Category Id
	 */
	public function getCategoryId()
	{
		return (int) $this->compare['category'];
	}

	/**
	 * Get category id stored in compare list
	 *
	 * @return  integer  Category Id
	 */
	public function getCategoryIds()
	{
		return (array) $this->compare['categories'];
	}

	/**
	 * Build AJAX response html
	 *
	 * @return  string  HTML for ajax response
	 */
	public function getAjaxResponse()
	{
		return RedshopLayoutHelper::render(
			'product.compare_ajax',
			array('object' => $this)
		);
	}

	/**
	 * Get item key using product id and/or category id
	 *
	 * @param   integer $productId  Product Id
	 * @param   integer $categoryId Category Id
	 *
	 * @return  string    Matched Item key
	 */
	public function getItemKey($productId, $categoryId = 0)
	{
		$this->item             = new stdClass;
		$this->item->productId  = $productId;
		$this->item->categoryId = $categoryId;

		return $this->findItemKey();
	}
}
