<?php
/**
 * @package     Redshop.Library
 * @subpackage  Product
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Product architecture
 *
 * @package     Redshop.Library
 * @subpackage  Product
 * @since       1.5
 */
class RedshopProduct
{
	/**
	 * Static instance of product Object
	 *
	 * @var  array of object
	 */
	private static $objInstance = [];

	/**
	 * Product Information
	 *
	 * @var  null
	 */
	protected $info;

	/**
	 * Protected product constructor. Must use getInstance() method.
	 *
	 * @param  integer  $id  Product Id
	 */
	protected function __construct($id)
	{
		if (!is_int($id))
		{
			throw new InvalidArgumentException(
				JText::sprintf('LIB_REDSHOP_PRODUCT_ID_NOT_VALID', __CLASS__),
				1
			);
		}

		$this->info = RedshopHelperProduct::getProductById($id);

		if (empty($this->info))
		{
			throw new Exception("Error Processing Request", 1);
		}
	}

	/**
	 * Returns product instance
	 *
	 * @return  object  product Object
	 */
	public static function getInstance($id)
	{
		if (!array_key_exists($id, self::$objInstance))
		{
			self::$objInstance[$id] = new RedshopProduct($id);
		}

		return self::$objInstance[$id];
	}

	/**
	 * Set variables in info
	 *
	 * @param  string  $name   Name of information property
	 * @param  mixed   $value  Value of property
	 */
	public function __set($name, $value)
	{
		$this->info->$name = $value;
	}

	/**
	 * Get product info property
	 *
	 * @param   string  $name  Name of property
	 *
	 * @return  mixed         value of property
	 */
	public function __get($name)
	{
		if (isset($this->info->$name))
		{
			return $this->info->$name;
		}

		return null;
	}

	/**
	 * Check for property exists
	 *
	 * @param   string   $name  Property name
	 *
	 * @return  boolean  True if property found
	 */
	public function __isset($name)
	{
		return property_exists($this->info, $name);
	}

	/**
	 * Current product id
	 *
	 * @return  integer  Product Id
	 */
	public function id()
	{
		return (int) $this->info->product_id;
	}

	/**
	 * Get product name
	 *
	 * @return  string  Product Name
	 */
	public function name()
	{
		return $this->info->product_name;
	}

	/**
	 * Product Price
	 *
	 * @todo    Don't use - Under Development
	 *
	 * @return  float  Product final price
	 */
	public function price()
	{
		JLog::add('Don\'t use price() function from ' . __CLASS__ . ', still under developement.');
	}
}
