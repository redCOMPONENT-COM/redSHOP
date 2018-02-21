<?php
/**
 * @package     Redshop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Product\Attribute;

defined('_JEXEC') or die;

class Helper
{
	/**
	 * @param   array   $attributes
	 * @param   integer $productId
	 * @param   integer $userId
	 * @param   float   $newProductPrice
	 * @param   integer $quantity
	 * @param   string  $data
	 *
	 * @return  array
	 * @since   __DEPLOY_VERSION__
	 */
	public static function calcAttributeCart($attributes = array(), $productId = 0, $userId = 0, $newProductPrice = 0, $quantity = 1, $data = '')
	{
		$user            = \JFactory::getUser();
		$stockroomHelper = \rsstockroomhelper::getInstance();
		$productHelper   = \productHelper::getInstance();

		if ($userId == 0)
		{
			$userId = $user->id;
		}

		$dispatcher = \RedshopHelperUtility::getDispatcher();
		\JPluginHelper::importPlugin('redshop_product');
		$dispatcher->trigger('onBeforeCalculateAttributeCart', array(&$attributes, &$productId, &$userId, &$newProductPrice, &$quantity, &$data));

		$sel               = 0;
		$selP              = 0;
		$applyVat          = $productHelper->getApplyattributeVatOrNot($data, $userId);
		$setPropEqual      = true;
		$setSubpropEqual   = true;
		$selectedAttributs = array();
		$selectedProperty  = array();
		$productOldprice   = 0;
		$productVatPrice   = 0;

		if ($newProductPrice != 0)
		{
			$productPrice = $newProductPrice;

			if ($productPrice > 0)
			{
				$productVatPrice = \RedshopHelperProduct::getProductTax($productId, $productPrice, $userId);
			}
		}
		else
		{
			$productPrices = \RedshopHelperProductPrice::getNetPrice($productId, $userId, $quantity, $data);

			// Using price without vat to proceed with calcualtion - we will apply vat in the end.
			$productPrice    = $productPrices['product_price_novat'];
			$productVatPrice = $productPrices['productVat'];
			$productOldprice = $productPrices['product_old_price_excl_vat'];
		}

		$isStock          = \RedshopHelperStockroom::isStockExist($productId);
		$isPreorderStock  = \RedshopHelperStockroom::isPreorderStockExists($productId);
		$displayAttribute = 0;

		for ($i = 0, $in = count($attributes); $i < $in; $i++)
		{
			$propertiesOperator        = array();
			$propertiesPrice           = array();
			$propertiesPriceWithVat    = array();
			$propertiesVat             = array();
			$subPropertiesOperator     = array();
			$subPropertiesPrice        = array();
			$subPropertiesPriceWithVat = array();
			$subPropertiesVat          = array();

			$properties = $attributes[$i]['attribute_childs'];

			if (count($properties) > 0)
			{
				$displayAttribute++;
			}

			for ($k = 0, $kn = count($properties); $k < $kn; $k++)
			{
				$propertyVat             = 0;
				$propertyOperator        = $properties[$k]['property_oprand'];
				$propertyPriceWithoutVat = (isset($properties[$k]['property_price'])) ? $properties[$k]['property_price'] : 0;
				$property                = \RedshopHelperProduct_Attribute::getAttributeProperties($properties[$k]['property_id']);
				$propertyPrice           = $propertyPriceWithoutVat;

				if ($propertyPriceWithoutVat > 0)
				{
					// Set property vat to 1 when price is 1. For * and / math rules.
					if ($propertyPriceWithoutVat == 1
						&& ($propertyOperator == '*' || $propertyOperator == '/'))
					{
						$propertyVat = 1;
					}

					if ($propertyOperator != '*' && $propertyOperator != '/')
					{
						$propertyVat = \RedshopHelperProduct::getProductTax($productId, $propertyPriceWithoutVat, $userId);
					}
				}

				$isStock         = RedshopHelperStockroom::isStockExists($properties[$k]['property_id'], "property");
				$isPreorderStock = RedshopHelperStockroom::isPreorderStockExists($properties[$k]['property_id'], "property");

				$propertiesOperator[$k]     = $propertyOperator;
				$propertiesPrice[$k]        = $propertyPriceWithoutVat;
				$propertiesPriceWithVat[$k] = $propertyPrice;
				$propertiesVat[$k]          = $propertyVat;
				$subProperties              = $properties[$k]['property_childs'];

				for ($l = 0, $ln = count($subProperties); $l < $ln; $l++)
				{
					if ($l == 0)
					{
						$selectedProperty[$selP++] = $properties[$k]['property_id'];
					}

					// Continue if there is no subproperty id
					if (!(int) $subProperties[$l]['subproperty_id'])
					{
						continue;
					}

					$subPropertyVat             = 0;
					$subPropertyOperator        = $subProperties[$l]['subproperty_oprand'];
					$subPropertyPriceWithoutVat = $subProperties[$l]['subproperty_price'];
					$subPropertyPrice           = $subPropertyPriceWithoutVat;

					if ($subPropertyPriceWithoutVat > 0)
					{
						// Set property vat to 1 when price is 1. For * and / math rules.
						if ($subPropertyPriceWithoutVat == 1
							&& ($subPropertyOperator == '*' || $subPropertyOperator == '/'))
						{
							$subPropertyVat = 1;
						}

						if ($subPropertyOperator != '*' && $subPropertyOperator != '/')
						{
							$subPropertyVat = \RedshopHelperProduct::getProductTax($productId, $subPropertyPriceWithoutVat, $userId);
						}
					}

					$isStock         = \RedshopHelperStockroom::isStockExists(
						$subProperties[$l]['subproperty_id'],
						"subproperty"
					);
					$isPreorderStock = \RedshopHelperStockroom::isPreorderStockExists(
						$subProperties[$l]['subproperty_id'],
						"subproperty"
					);

					$subPropertiesOperator[$k][$l]     = $subPropertyOperator;
					$subPropertiesPrice[$k][$l]        = $subPropertyPriceWithoutVat;
					$subPropertiesPriceWithVat[$k][$l] = $subPropertyPrice;
					$subPropertiesVat[$k][$l]          = $subPropertyVat;
				}
			}

			// FOR PROPERTY AND SUBPROPERTY PRICE CALCULATION
			$propertyPrices = $productHelper->makeTotalPriceByOprand($productPrice, $propertiesOperator, $propertiesPriceWithVat);
			$productPrice   = $propertyPrices[1];

			$propertyOldPriceVats = $productHelper->makeTotalPriceByOprand($productOldprice, $propertiesOperator, $propertiesPrice);
			$productOldprice      = $propertyOldPriceVats[1];

			for ($t = 0, $tn = count($properties); $t < $tn; $t++)
			{
				$selectedAttributs[$sel++] = $attributes[$i]['attribute_id'];

				if ($setPropEqual && $setSubpropEqual && isset($subPropertiesPriceWithVat[$t]))
				{
					$subPropertyPrices = $productHelper->makeTotalPriceByOprand($productPrice, $subPropertiesOperator[$t], $subPropertiesPriceWithVat[$t]);

					$productPrice = $subPropertyPrices[1];

					$subPropertyOldPriceVats = $productHelper->makeTotalPriceByOprand($productOldprice, $subPropertiesOperator[$t], $subPropertiesPrice[$t]);
					$productOldprice         = $subPropertyOldPriceVats[1];
				}
			}
		}

		$displayattribute = \RedshopLayoutHelper::render(
			'product.product_attribute',
			array(
				'attributes'       => $attributes,
				'data'             => $data,
				'displayAttribute' => $displayAttribute
			),
			'',
			array(
				'component' => 'com_redshop',
				'client'    => 0
			)
		);

		$productVatOldPrice = 0;

		if ($productOldprice > 0)
		{
			$productVatOldPrice = \RedshopHelperProduct::getProductTax($productId, $productOldprice, $userId);
		}

		// Recalculate VAT if set to apply vat for attribute
		if ($applyVat)
		{
			$productVatPrice = \RedshopHelperProduct::getProductTax($productId, $productPrice, $userId);
		}

		// Todo: For QA to check all cases.
		/*if ($this->getApplyVatOrNot($data, $userId))
		{
			$productPrice += $productVatPrice;
		}*/

		$data = array(
			$displayattribute,
			$productPrice,
			$productVatPrice,
			$selectedAttributs,
			$isStock,
			$productOldprice,
			$productVatOldPrice,
			$isPreorderStock,
			$selectedProperty
		);

		\JPluginHelper::importPlugin('redshop_product');
		\RedshopHelperUtility::getDispatcher()->trigger('onMakeAttributeCart', array(&$data, $attributes, $productId));

		return $data;
	}
}
