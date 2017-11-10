<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Cart;

/**
 * This class provide general cart helper methods
 *
 * @package     Redshop\Cart
 *
 * @since       2.0.7
 */
class Helper
{
	/**
	 * @param   array  $data
	 * @param   int    $newQuantity
	 *
	 * @return  array
	 *
	 * @TODO  Use foreach instead for loop
	 * @since   2.0.7
	 */
	public function updateAccessoryPriceArray($data = array(), $newQuantity = 1)
	{
		$productHelper = \productHelper::getInstance();
		$attArr = $data['cart_accessory'];

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$attchildArr = $attArr[$i]['accessory_childs'];

			$attArr[$i]['accessory_quantity'] = $newQuantity;

			for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
			{
				$propArr = $attchildArr[$j]['attribute_childs'];

				for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
				{
					$priceList = \RedshopHelperProduct_Attribute::getPropertyPrice($propArr[$k]['property_id'], $newQuantity, 'property');

					if (count($priceList) > 0)
					{
						$propArr[$k]['property_price'] = $priceList->product_price;
					}
					else
					{
						$priceList                     = $productHelper->getProperty($propArr[$k]['property_id'], 'property');
						$propArr[$k]['property_price'] = $priceList->product_price;
					}

					$subpropArr = $propArr[$k]['property_childs'];

					for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
					{
						$priceList = \RedshopHelperProduct_Attribute::getPropertyPrice($subpropArr[$l]['subproperty_id'], $newQuantity, 'subproperty');

						if (count($priceList) > 0)
						{
							$subpropArr[$l]['subproperty_price'] = $priceList->product_price;
						}
						else
						{
							$priceList                           = $productHelper->getProperty($subpropArr[$l]['subproperty_id'], 'subproperty');
							$subpropArr[$k]['subproperty_price'] = $priceList->product_price;
						}
					}

					$propArr[$k]['property_childs'] = $subpropArr;
				}

				$attchildArr[$j]['attribute_childs'] = $propArr;
			}

			$attArr[$i]['accessory_childs'] = $attchildArr;
		}

		return $attArr;
	}

	/**
	 * @param   array  $data
	 * @param   int    $newQuantity
	 *
	 * @return  array
	 *
	 * @since   2.0.4
	 */
	public static function updateAttributePriceArray($data = array(), $newQuantity = 1)
	{
		$productHelper = \productHelper::getInstance();
		$attributes    = $data['cart_attribute'];

		// Process attributes
		foreach ($attributes as $attributeIndex => $attribute)
		{
			$properties = $attribute['attribute_childs'];

			foreach ($properties as $propertyIndex => $property)
			{
				$priceList = \RedshopHelperProduct_Attribute::getPropertyPrice($property['property_id'], $newQuantity, 'property');

				if (count($priceList) > 0)
				{
					$property['property_price'] = $priceList->product_price;
				}
				else
				{
					$priceList                  = $productHelper->getProperty($property['property_id'], 'property');
					$property['property_price'] = $priceList->product_price;
				}

				$subProperties = $property['property_childs'];

				foreach ($subProperties as $subPropertyIndex => $subProperty)
				{
					$priceList = \RedshopHelperProduct_Attribute::getPropertyPrice($subProperty['subproperty_id'], $newQuantity, 'subproperty');

					if (count($priceList) > 0)
					{
						$subProperty['subproperty_price'] = $priceList->product_price;
					}
					else
					{
						$priceList = $productHelper->getProperty($subProperty['subproperty_id'], 'subproperty');
						// @TODO  Need verify why we are using $propertyIndex instead $subPropertyIndex in this case
						$subProperties[$propertyIndex]['subproperty_price'] = $priceList->product_price;
					}
				}

				$property['property_childs'] = $subProperties;
			}

			$attribute['attribute_childs'] = $properties;
		}

		return $attributes;
	}

	/**
	 * @param   int    $productId
	 * @param   array  $cart
	 * @param   int    $voucherLeft
	 * @param   int    $globalVoucher
	 *
	 * @return  array
	 *
	 * @since   2.0.7
	 */
	public static function getCartProductPrice($productId, $cart, $voucherLeft, $globalVoucher)
	{
		$productArr           = array();
		$affectedProductIdArr = array();
		$idx                  = $cart['idx'];
		$productPrice         = 0;
		$productPriceExclVat  = 0;
		$productQuantity      = 0;

		$productIdArr = explode(',', $productId);

		for ($v = 0; ($v < $idx) && ($voucherLeft > 0); $v++)
		{
			if ($voucherLeft < $cart[$v]['quantity'] && $voucherLeft)
			{
				$cart[$v]['quantity'] = $voucherLeft;
			}

			if (in_array($cart[$v]['product_id'], $productIdArr) || $globalVoucher)
			{
				$productPrice           += $cart[$v]['product_price'] * $cart[$v]['quantity'];
				$productQuantity        += $cart[$v]['quantity'];
				$productPriceExclVat    += $cart[$v]['product_price_excl_vat'] * $cart[$v]['quantity'];
				$affectedProductIdArr[] = $cart[$v]['product_id'];
				$voucherLeft            = $voucherLeft - $cart[$v]['quantity'];
			}
		}

		$productArr['product_ids']            = implode(',', $affectedProductIdArr);
		$productArr['product_price']          = $productPrice;
		$productArr['product_price_excl_vat'] = $productPriceExclVat;
		$productArr['product_quantity']       = $productQuantity;

		return $productArr;
	}

	/**
	 * @param   array  $data
	 *
	 * @return  array
	 *
	 * @TODO  Use foreach instead for loop
	 * @since   2.0.7
	 */
	public static function changeAttribute($data)
	{
		$imageName = '';
		$type      = '';
		$cart      = \Redshop\Cart\Session\Helper::getCart();

		$generateAttributeCart = array();
		$productId             = $data['product_id'];
		$idx                   = $data['cart_index'];

		if (isset($data['attribute_id_prd_' . $productId . '_0']))
		{
			$attributeData = $data['attribute_id_prd_' . $productId . '_0'];

			for ($ia = 0, $countAttribute = count($attributeData); $ia < $countAttribute; $ia++)
			{
				$accPropertyCart                              = array();
				$attribute                                    = \RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $attributeData[$ia]);
				$generateAttributeCart[$ia]['attribute_id']   = $attributeData[$ia];
				$generateAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

				if ($attribute[0]->text != "" && isset($data['property_id_prd_' . $productId . '_0_' . $attributeData[$ia]]))
				{
					$accPropertyData = $data['property_id_prd_' . $productId . '_0_' . $attributeData[$ia]];

					for ($ip = 0, $countProperty = count($accPropertyData); $ip < $countProperty; $ip++)
					{
						if ($accPropertyData[$ip] != 0)
						{
							$accSubpropertyCart = array();
							$propertyPrice      = 0;
							$property           = \RedshopHelperProduct_Attribute::getProductAttribute($accPropertyData[$ip]);
							$priceList          = \RedshopHelperProduct_Attribute::getPropertyPrice(
								$accPropertyData[$ip], $cart[$idx]['quantity'],
								'property'
							);

							if (!empty($priceList))
							{
								$propertyPrice = $priceList->product_price;
							}
							else
							{
								$propertyPrice = $property[0]->property_price;
							}

							if (!empty($property)
								&& \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property[0]->property_image)
							)
							{
								$type      = 'product_attributes';
								$imageName = $property[0]->property_image;
							}

							$accPropertyCart[$ip]['property_id']     = $accPropertyData[$ip];
							$accPropertyCart[$ip]['property_name']   = $property[0]->text;
							$accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
							$accPropertyCart[$ip]['property_price']  = $propertyPrice;

							if (isset($data['subproperty_id_prd_' . $productId . '_0_' . $attributeData[$ia] . '_' . $accPropertyData[$ip]]))
							{
								$accSubPropertyData = $data['subproperty_id_prd_' . $productId . '_0_' . $attributeData[$ia] . '_' . $accPropertyData[$ip]];
								$countSubProperty   = count($accSubPropertyData);

								for ($isp = 0; $isp < $countSubProperty; $isp++)
								{
									if ($accSubPropertyData[$isp] != 0)
									{
										$subPropertyPrice = 0;
										$subProperty      = \RedshopHelperProduct_Attribute::getAttributeSubProperties($accSubPropertyData[$isp]);
										$priceList        = \RedshopHelperProduct_Attribute::getPropertyPrice(
											$accSubPropertyData[$isp],
											$cart[$idx]['quantity'], 'subproperty'
										);

										if (!empty($priceList))
										{
											$subPropertyPrice = $priceList->product_price;
										}
										else
										{
											$subPropertyPrice = $subProperty[0]->subattribute_color_price;
										}

										if (!empty($subProperty)
											&& \JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $subProperty[0]->subattribute_color_image)
										)
										{
											$type      = 'subcolor';
											$imageName = $subProperty[0]->subattribute_color_image;
										}

										$accSubpropertyCart[$isp]['subproperty_id']           = $accSubPropertyData[$isp];
										$accSubpropertyCart[$isp]['subproperty_name']         = $subProperty[0]->text;
										$accSubpropertyCart[$isp]['subproperty_oprand']       = $subProperty[0]->oprand;
										$accSubpropertyCart[$isp]['subattribute_color_title'] = $subProperty[0]->subattribute_color_title;
										$accSubpropertyCart[$isp]['subproperty_price']        = $subPropertyPrice;
									}
								}
							}

							$accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
						}
					}
				}

				$generateAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
			}
		}

		$cart[$idx]['cart_attribute'] = $generateAttributeCart;

		if (!empty($imageName) && !empty($type))
		{
			$cart[$idx]['hidden_attribute_cartimage'] = REDSHOP_FRONT_IMAGES_ABSPATH . $type . "/" . $imageName;
		}

		// @TODO Do we need setCart back ?

		return $cart;
	}

	/**
	 * Empty and delete current cart
	 *
	 * @return   boolean
	 *
	 * @since    2.0.6
	 */
	public static function emptyCart()
	{
		\Redshop\Cart\Session\Helper::reset();

		setcookie("redSHOPcart", "", time() - 3600, "/");
		\Redshop\Cart\Session\Helper::setCart(array('idx' => 0));

		return \RedshopHelperStockroom::deleteCartAfterEmpty();
	}
}
