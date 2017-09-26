<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Load redSHOP Library
JLoader::import('redshop.library');
JLoader::import('reditem.library');

use Aesir\App;

/**
 * Generate product discount rule
 *
 * @since 1.0.0
 */
class PlgRedshop_ProductDiscount_Rule extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   object $subject The object to observe
	 * @param   array  $config  An optional associative array of configuration settings
	 *
	 * @since   1.0.0
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_product_discount_rule', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * onSetProductPrice - Set product price
	 *
	 * @param   float $productPrice Product Price
	 * @param   int   $productId    Product ID
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onSetProductPrice(&$productPrice, $productId)
	{
		$this->getDiscountRule($productPrice);
	}

	/**
	 * Get Product Discount Rult
	 *
	 * @param   float $price Product price
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function getDiscountRule(&$price)
	{
		$oprand     = $this->params->get("oprand", '-');
		$amount     = $this->params->get("amount", 0);
		$domain     = $this->params->get('domain', array());
		$domainList = array();

		foreach ($domain as $value)
		{
			$domainList[] = str_replace('#new#', '', $value);
		}

		if (!in_array(App::getVisitor()->getDomain()->get('domain'), $domainList))
		{
			return;
		}

		$price = $oprand == '+' ? $price + ($price * ($amount / 100)) : $price - ($price * ($amount / 100));
	}

	/**
	 * onSetProductDiscountPrice - Set product price
	 *
	 * @param   float $productDiscountPrice Product Discount Price
	 * @param   int   $productId            Product ID
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onSetProductDiscountPrice(&$productDiscountPrice, $productId)
	{
		$this->getDiscountRule($productDiscountPrice);
	}
}
