<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Class PlgRedshop_ProductDiscount_Affect_Attribute
 *
 * @since  1.0.0
 */
class PlgRedshop_ProductDiscount_Affect_Attribute extends JPlugin
{
	protected $autoloadLanguage = true;

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_product_discount_affect_attribute', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Calculate discount price for attribute
	 *
	 * @param   object  &$propertyPrice  Property Price
	 * @param   int     $sectionId       [description]
	 * @param   string  $section         [description]
	 * @param   int     $userId          [description]
	 *
	 * @return  void
	 */
	public function onGetPropertyPrice(&$propertyPrice, $sectionId, $section, $userId)
	{
		if ($propertyPrice == null)
		{
			// Get Property discount price (not quantity based - general discount)
			$discountPrice = $this->getPropertyDiscountPrice($sectionId, $section, $userId);

			if (!is_bool($discountPrice))
			{
				$propertyPrice = new stdClass;
				$propertyPrice->product_price = $discountPrice;
			}
		}
	}

	/**
	 * Count Property and Subproperty discount applied on the product which they belongs to.
	 *
	 * @param   integer  $sectionId  property or subproperty id
	 * @param   string   $section    Either 'property' or 'subproperty'
	 * @param   integer  $userId     User id
	 *
	 * @return  mixed    Float for price, boolean to validate
	 */
	private function getPropertyDiscountPrice($sectionId, $section, $userId)
	{
		$producthelper   = productHelper::getInstance();

		$property = $producthelper->getProperty($sectionId, $section);

		if ($property)
		{
			// Get discount eligibal ids
			$productSpecialIds = $producthelper->getProductSpecialId($userId);

			// Get apllicable discount price
			$specialPrice      = $producthelper->getProductSpecialPrice($property->product_price, $productSpecialIds, $property->product_id);

			if (!empty($specialPrice))
			{
				$discount = $specialPrice->discount_amount;

				if (0 != $specialPrice->discount_type)
				{
					$discount = ($property->product_price * $specialPrice->discount_amount) / 100;
				}
			}
			else
			{
				$p = $producthelper->getProductNetPrice($property->product_id);

				$discount = ($property->product_price * $p['product_price_saving_percentage']) / 100;
			}

			// Deduct discount price and return
			return $property->product_price - $discount;
		}

		// Return false when discount is not applicable
		return false;
	}
}
