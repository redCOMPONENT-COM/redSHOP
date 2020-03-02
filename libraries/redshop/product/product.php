<?php
/**
 * @package     Redshop.Library
 * @subpackage  Product
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

jimport('joomla.log.log');

/**
 * Product architecture
 *
 * @property   integer  $min_order_product_quantity  Min order quantity
 * @property   integer  $max_order_product_quantity  Max order quantity
 * @property   string   $product_name                Product name
 * @property   string   $product_full_image          File name of product full image
 * @property   string   $product_s_desc              Product short description
 * @property   string   $product_desc                Product description
 * @property   string   $product_number              Product number
 * @property   integer  $manufacturer_id             Manufacturer ID
 * @property   integer  $supplier_id                 Supplier ID
 * @property   float    $weight                      Product weight
 * @property   double   $product_volume              Product volume
 * @property   float    $product_length              Product length
 * @property   float    $product_width               Product Width
 * @property   float    $product_height              Product height
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
	private static $objInstance = array();

	/**
	 * Product Information
	 *
	 * @var  null
	 */
	protected $info;

	/**
	 * Protected product constructor. Must use getInstance() method.
	 *
	 * @param   int  $id  Product Id
	 *
	 * @throws Exception
	 */
	protected function __construct($id = null)
	{
		try
		{
			if ($id)
			{
				$this->info = \Redshop\Product\Product::getProductById($id);
			}
			else
			{
				$this->info = new JObject;
			}
		}
		catch (Exception $e)
		{
			JLog::add(JText::_('COM_REDSHOP_ERROR_INVALID_PRODUCT_ID'), JLog::WARNING, 'com_redshop');
		}
	}

	/**
	 * Returns product instance
	 *
	 * @param   int  $id  Product Id
	 *
	 * @return  object  product Object
	 * @throws  Exception
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
	 * @param   string  $name   Name of information property
	 * @param   mixed   $value  Value of property
	 *
	 * @return  void
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
	 * @return  mixed          Value of property
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
	 * @param   string  $name  Property name
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
