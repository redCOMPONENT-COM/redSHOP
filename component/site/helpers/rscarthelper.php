<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

defined('_JEXEC') or die;

class rsCarthelper
{
	public $_table_prefix = null;

	public $_db = null;

	public $_session = null;

	public $_order_functions = null;

	public $_extra_field = null;

	public $_producthelper = null;

	public $_shippinghelper = null;

	public $_globalvoucher = 0;

	protected static $instance = null;

	protected $input;

	/**
	 * Returns the rsCarthelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  rsCarthelper  The rsCarthelper object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	public function __construct()
	{
		$this->_table_prefix    = '#__redshop_';
		$this->_db              = JFactory::getDBO();
		$this->_session         = JFactory::getSession();
		$this->_order_functions = order_functions::getInstance();
		$this->_extra_field     = extra_field::getInstance();
		$this->_producthelper   = productHelper::getInstance();
		$this->_shippinghelper  = shipping::getInstance();
		$this->input            = JFactory::getApplication()->input;
	}

	public function checkQuantityInStock($data = array(), $newquantity = 1, $minQuantity = 0)
	{
		JPluginHelper::importPlugin('redshop_product');
		$result = RedshopHelperUtility::getDispatcher()->trigger('onCheckQuantityInStock', array(&$data, &$newquantity, &$minQuantity));

		if (in_array(true, $result, true))
		{
			return $newquantity;
		}

		$productData     = RedshopHelperProduct::getProductById($data['product_id']);
		$productPreOrder = $productData->preorder;

		if ($productData->min_order_product_quantity > 0 && $productData->min_order_product_quantity > $newquantity)
		{
			$msg = $productData->product_name . " " . JText::_('COM_REDSHOP_WARNING_MSG_MINIMUM_QUANTITY');
			$msg = sprintf($msg, $productData->min_order_product_quantity);
			/** @scrutinizer ignore-deprecated */
			JError::raiseWarning('', $msg);
			$newquantity = $productData->min_order_product_quantity;
		}

		if (!Redshop::getConfig()->getBool('USE_STOCKROOM'))
		{
			return $newquantity;
		}

		$productStock  = 0;
		$allowPreOrder = Redshop::getConfig()->getBool('ALLOW_PRE_ORDER');

		if (($productPreOrder == 'global' && !$allowPreOrder)
			|| $productPreOrder == 'no'
			|| ($productPreOrder == "" && !$allowPreOrder))
		{
			$productStock = RedshopHelperStockroom::getStockroomTotalAmount($data['product_id']);
		}

		if (($productPreOrder == "global" && $allowPreOrder)
			|| $productPreOrder == "yes"
			|| ($productPreOrder == "" && $allowPreOrder))
		{
			$productStock  = RedshopHelperStockroom::getStockroomTotalAmount($data['product_id']);
			$productStock += RedshopHelperStockroom::getPreorderStockroomTotalAmount($data['product_id']);
		}

		$ownProductReserveStock = RedshopHelperStockroom::getCurrentUserReservedStock($data['product_id']);
		$attArr                 = $data['cart_attribute'];

		if (count($attArr) <= 0)
		{
			if ($productStock >= 0)
			{
				if ($newquantity > $ownProductReserveStock && $productStock < ($newquantity - $ownProductReserveStock))
				{
					$newquantity = $productStock + $ownProductReserveStock;
				}
			}
			else
			{
				$newquantity = $productStock + $ownProductReserveStock;
			}

			if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
			{
				$msg = $productData->product_name . " " . JText::_('COM_REDSHOP_WARNING_MSG_MAXIMUM_QUANTITY');
				$msg = sprintf($msg, $productData->max_order_product_quantity);
				/** @scrutinizer ignore-deprecated */
				JError::raiseWarning('', $msg);
				$newquantity = $productData->max_order_product_quantity;
			}

			if (array_key_exists('quantity', $data))
			{
				$productReservedQuantity = $ownProductReserveStock + $newquantity - $data['quantity'];
			}
			else
			{
				$productReservedQuantity = $newquantity;
			}

			RedshopHelperStockroom::addReservedStock($data['product_id'], $productReservedQuantity, 'product');
		}
		else
		{
			for ($i = 0, $in = count($attArr); $i < $in; $i++)
			{
				$propArr = $attArr[$i]['attribute_childs'];

				for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
				{
					// Get subproperties from add to cart tray.
					$subpropArr = $propArr[$k]['property_childs'];
					$totalSubProperty = count($subpropArr);
					$ownReservePropertyStock = RedshopHelperStockroom::getCurrentUserReservedStock($propArr[$k]['property_id'], 'property');
					$property_stock = 0;

					if (($productPreOrder == "global" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "no") || ($productPreOrder == "" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
					{
						$property_stock = RedshopHelperStockroom::getStockroomTotalAmount($propArr[$k]['property_id'], "property");
					}

					if (($productPreOrder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes") || ($productPreOrder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
					{
						$property_stock = RedshopHelperStockroom::getStockroomTotalAmount($propArr[$k]['property_id'], "property");
						$property_stock += RedshopHelperStockroom::getPreorderStockroomTotalAmount($propArr[$k]['property_id'], "property");
					}

					// Get Property stock only when SubProperty is not in cart
					if ($totalSubProperty <= 0)
					{
						if ($property_stock >= 0)
						{
							if ($newquantity > $ownReservePropertyStock && $property_stock < ($newquantity - $ownReservePropertyStock))
							{
								$newquantity = $property_stock + $ownReservePropertyStock;
							}
						}
						else
						{
							$newquantity = $property_stock + $ownReservePropertyStock;
						}

						if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
						{
							$newquantity = $productData->max_order_product_quantity;
						}

						if (array_key_exists('quantity', $data))
						{
							$propertyReservedQuantity = $ownReservePropertyStock + $newquantity - $data['quantity'];
							$newProductQuantity = $ownProductReserveStock + $newquantity - $data['quantity'];
						}
						else
						{
							$propertyReservedQuantity = $newquantity;
							$newProductQuantity = $ownProductReserveStock + $newquantity;
						}

						RedshopHelperStockroom::addReservedStock($propArr[$k]['property_id'], $propertyReservedQuantity, "property");
						RedshopHelperStockroom::addReservedStock($data['product_id'], $newProductQuantity, 'product');
					}
					else
					{
						// Get SubProperty Stock here.
						for ($l = 0; $l < $totalSubProperty; $l++)
						{
							$subproperty_stock = 0;

							if (($productPreOrder == "global" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "no") || ($productPreOrder == "" && !Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
							{
								$subproperty_stock = RedshopHelperStockroom::getStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
							}

							if (($productPreOrder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreOrder == "yes") || ($productPreOrder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')))
							{
								$subproperty_stock = RedshopHelperStockroom::getStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
								$subproperty_stock += RedshopHelperStockroom::getPreorderStockroomTotalAmount($subpropArr[$l]['subproperty_id'], "subproperty");
							}

							$ownSubPropReserveStock = RedshopHelperStockroom::getCurrentUserReservedStock($subpropArr[$l]['subproperty_id'], "subproperty");

							if ($subproperty_stock >= 0)
							{
								if ($newquantity > $ownSubPropReserveStock && $subproperty_stock < ($newquantity - $ownSubPropReserveStock))
								{
									$newquantity = $subproperty_stock + $ownSubPropReserveStock;
								}
							}
							else
							{
								$newquantity = $subproperty_stock + $ownSubPropReserveStock;
							}

							if ($productData->max_order_product_quantity > 0 && $productData->max_order_product_quantity < $newquantity)
							{
								$newquantity = $productData->max_order_product_quantity;
							}

							if (array_key_exists('quantity', $data))
							{
								$subPropertyReservedQuantity = $ownSubPropReserveStock + $newquantity - $data['quantity'];
								$newPropertyQuantity = $ownReservePropertyStock + $newquantity - $data['quantity'];
								$newProductQuantity = $ownProductReserveStock + $newquantity - $data['quantity'];
							}
							else
							{
								$subPropertyReservedQuantity = $newquantity;
								$newPropertyQuantity = $ownReservePropertyStock + $newquantity;
								$newProductQuantity = $ownProductReserveStock + $newquantity;
							}

							RedshopHelperStockroom::addReservedStock($subpropArr[$l]['subproperty_id'], $subPropertyReservedQuantity, 'subproperty');
							RedshopHelperStockroom::addReservedStock($propArr[$k]['property_id'], $newPropertyQuantity, 'property');
							RedshopHelperStockroom::addReservedStock($data['product_id'], $newProductQuantity, 'product');
						}
					}
				}
			}
		}

		return $newquantity;
	}

	/**
	 * Method for calculate final price of cart.
	 *
	 * @param   bool  $callmodify  Is modify cart?
	 *
	 * @return  array
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::cartFinalCalculation() instead.
	 *
	 * @throws  Exception
	 */
	public function cartFinalCalculation($callmodify = true)
	{
		return RedshopHelperCart::cartFinalCalculation($callmodify);
	}

	/**
	 * Store Cart to Database
	 *
	 * @param   array  $cart   Cart
	 *
	 * @return  null
	 * @throws  Exception
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::addCartToDatabase() instead.
	 */
	public function carttodb($cart = array())
	{
		return RedshopHelperCart::addCartToDatabase($cart);
	}

	/**
	 * Store Cart Attribute to Database
	 *
	 * @param   array    $attribute      Cart attribute data.
	 * @param   int      $cart_item_id   Cart item ID
	 * @param   int      $product_id     Cart product ID.
	 * @param   boolean  $isAccessary    Is this accessory?
	 *
	 * @return  boolean       True on success. False otherwise.
	 *
	 * @deprecated  2.0.3  Use RedshopHelperCart::addCartToDatabase() instead.
	 */
	public function attributetodb($attribute = array(), $cart_item_id = 0, $product_id = 0, $isAccessary = false)
	{
		return RedshopHelperCart::addCartAttributeToDatabase($attribute, $cart_item_id, $product_id, $isAccessary);
	}

	/**
	 * Remove cart entry from table
	 *
	 * @param   int  $cart_id   #__redshop_usercart table key id
	 * @param   int  $userid    user information id - joomla #__users table key id
	 * @param   bool $delCart   remove cart from #__redshop_usercart table
	 *
	 * @return bool
	 *
	 * @deprecated  2.0.3  Use edshopHelperCart::removeCartFromDatabase() instead.
	 */
	public function removecartfromdb($cart_id = 0, $userid = 0, $delCart = false)
	{
		return RedshopHelperCart::removeCartFromDatabase($cart_id, $userid, $delCart);
	}

	/**
	 * Method for convert data from database to cart.
	 *
	 * @param   int  $userId  ID of user.
	 *
	 * @deprecated   2.0.3  Use RedshopHelperCart::databaseToCart() instead.
	 */
	public function dbtocart($userId = 0)
	{
		RedshopHelperCart::databaseToCart($userId);
	}

	/**
	 * Method for generate attribute from cart.
	 *
	 * @param   int  $cart_item_id       ID of cart item.
	 * @param   int  $is_accessory       Is accessory?
	 * @param   int  $parent_section_id  ID of parent section
	 * @param   int  $quantity           Quantity of product.
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3
	 */
	public function generateAttributeFromCart($cart_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		return RedshopHelperCart::generateAttributeFromCart($cart_item_id, $is_accessory, $parent_section_id, $quantity);
	}

	public function generateAccessoryFromCart($cart_item_id = 0, $product_id = 0, $quantity = 1)
	{
		$generateAccessoryCart = array();

		$cartItemdata = $this->getCartItemAccessoryDetail($cart_item_id);

		for ($i = 0, $in = count($cartItemdata); $i < $in; $i++)
		{
			$accessory          = RedshopHelperAccessory::getProductAccessories($cartItemdata[$i]->product_id);
			$accessorypricelist = \Redshop\Product\Accessory::getPrice($product_id, $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1);
			$accessory_price    = $accessorypricelist[0];

			$generateAccessoryCart[$i]['accessory_id']     = $cartItemdata[$i]->product_id;
			$generateAccessoryCart[$i]['accessory_name']   = $accessory[0]->product_name;
			$generateAccessoryCart[$i]['accessory_oprand'] = $accessory[0]->oprand;
			$generateAccessoryCart[$i]['accessory_price']  = $accessory_price;
			$generateAccessoryCart[$i]['accessory_childs'] = RedshopHelperCart::generateAttributeFromCart($cart_item_id, 1, $cartItemdata[$i]->product_id, $quantity);
		}

		return $generateAccessoryCart;
	}

	public function getCartItemAccessoryDetail($cart_item_id = 0)
	{
		$list = null;

		if ($cart_item_id != 0)
		{
			$query = "SELECT * FROM  " . $this->_table_prefix . "usercart_accessory_item "
				. "WHERE cart_item_id=" . (int) $cart_item_id;
			$this->_db->setQuery($query);
			$list = $this->_db->loadObjectlist();
		}

		return $list;
	}

	public function getCartItemAttributeDetail($cart_item_id = 0, $is_accessory = 0, $section = "attribute", $parent_section_id = 0)
	{
		$db = JFactory::getDbo();

		$and = "";

		if ($cart_item_id != 0)
		{
			$and .= " AND cart_item_id=" . (int) $cart_item_id . " ";
		}

		if ($parent_section_id != 0)
		{
			$and .= " AND parent_section_id=" . (int) $parent_section_id . " ";
		}

		$query = "SELECT * FROM  " . $this->_table_prefix . "usercart_attribute_item "
			. "WHERE is_accessory_att=" . (int) $is_accessory . " "
			. "AND section=" . $db->quote($section) . " "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * Add GiftCard To Cart
	 *
	 * @param   array  $cartItem  Cart item
	 * @param   array  $data      User cart data
	 *
	 * @return  void
	 *
	 * @deprecated  2.1.0
	 *
	 * @see  RedshopHelperDiscount::addGiftCardToCart()
	 */
	public function addGiftCardToCart(&$cartItem, $data)
	{
		RedshopHelperDiscount::addGiftCardToCart($cartItem, $data);
	}

	/**
	 * Method for add product to cart
	 *
	 * @param   array  $data  Product data
	 *
	 * @return  mixed
	 * @throws  Exception
	 *
	 * @deprecated 2.1.0
	 * @see Redshop\Cart\Cart::addProduct
	 */
	public function addProductToCart($data = array())
	{
		return Redshop\Cart\Cart::addProduct($data);
	}

	public function userfieldValidation($data, $data_add, $section = 12)
	{
		$returnArr    = $this->_producthelper->getProductUserfieldFromTemplate($data_add);
		$userfieldArr = $returnArr[1];

		$msg = "";

		if (count($userfieldArr) > 0)
		{
			$req_fields = RedshopHelperExtrafields::getSectionFieldList($section, 1, 1, 1);

			for ($i = 0, $in = count($req_fields); $i < $in; $i++)
			{
				if (in_array($req_fields[$i]->name, $userfieldArr))
				{
					if (!isset($data[$req_fields[$i]->name]) || (isset($data[$req_fields[$i]->name]) && $data[$req_fields[$i]->name] == ""))
					{
						$msg .= $req_fields[$i]->title . " " . JText::_('COM_REDSHOP_IS_REQUIRED') . "<br/>";
					}
				}
			}
		}

		return $msg;
	}

	/**
	 * @param   array  $data
	 * @param   int    $user_id
	 *
	 * @return  array|bool
	 *
	 * @throws  Exception
	 */
	public function generateAccessoryArray($data, $user_id = 0)
	{
		$generateAccessoryCart = array();

		if (!empty($data['accessory_data']))
		{
			$accessoryData    = explode("@@", $data['accessory_data']);
			$accQuantityData = array();

			if (isset($data['acc_quantity_data']))
			{
				$accQuantityData = explode("@@", $data['acc_quantity_data']);
			}

			for ($i = 0, $in = count($accessoryData); $i < $in; $i++)
			{
				$accessory          = RedshopHelperAccessory::getProductAccessories($accessoryData[$i]);
				$accessoryPriceList = \Redshop\Product\Accessory::getPrice(
					$data['product_id'], $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1, $user_id
				);
				$accessory_price    = $accessoryPriceList[0];
				$acc_quantity       = (isset($accQuantityData[$i]) && $accQuantityData[$i]) ? $accQuantityData[$i] : $data['quantity'];

				$generateAccessoryCart[$i]['accessory_id']       = $accessoryData[$i];
				$generateAccessoryCart[$i]['accessory_name']     = $accessory[0]->product_name;
				$generateAccessoryCart[$i]['accessory_oprand']   = $accessory[0]->oprand;
				$generateAccessoryCart[$i]['accessory_price']    = $accessory_price * $acc_quantity;
				$generateAccessoryCart[$i]['accessory_quantity'] = $acc_quantity;

				$accAttributeCart = array();

				if (!empty($data['acc_attribute_data']))
				{
					$acc_attribute_data = explode('@@', $data['acc_attribute_data']);

					if ($acc_attribute_data[$i] != "")
					{
						$acc_attribute_data = explode('##', $acc_attribute_data[$i]);
						$countAccessoryAttribute = count($acc_attribute_data);

						for ($ia = 0; $ia < $countAccessoryAttribute; $ia++)
						{
							$accPropertyCart                         = array();
							$attribute                               = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $acc_attribute_data[$ia]);
							$accAttributeCart[$ia]['attribute_id']   = $acc_attribute_data[$ia];
							$accAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

							if ($attribute[0]->text != "" && !empty($data['acc_property_data']))
							{
								$acc_property_data = explode('@@', $data['acc_property_data']);
								$acc_property_data = explode('##', $acc_property_data[$i]);

								if (empty($acc_property_data[$ia]) && $attribute[0]->attribute_required == 1)
								{
									return array();
								}

								if (!empty($acc_property_data[$ia]))
								{
									$acc_property_data = explode(',,', $acc_property_data[$ia]);
									$countAccessoryProperty = count($acc_property_data);

									for ($ip = 0; $ip < $countAccessoryProperty; $ip++)
									{
										$accSubpropertyCart = array();
										$property_price     = 0;
										$property           = RedshopHelperProduct_Attribute::getAttributeProperties($acc_property_data[$ip]);
										$pricelist          = RedshopHelperProduct_Attribute::getPropertyPrice($acc_property_data[$ip], $data['quantity'], 'property', $user_id);

										if (count($pricelist) > 0)
										{
											$property_price = $pricelist->product_price;
										}
										else
										{
											$property_price = $property[0]->property_price;
										}

										$accPropertyCart[$ip]['property_id']     = $acc_property_data[$ip];
										$accPropertyCart[$ip]['property_name']   = $property[0]->text;
										$accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
										$accPropertyCart[$ip]['property_price']  = $property_price;

										if (!empty($data['acc_subproperty_data']))
										{
											$acc_subproperty_data = explode('@@', $data['acc_subproperty_data']);
											$acc_subproperty_data = @explode('##', $acc_subproperty_data[$i]);
											$acc_subproperty_data = @explode(',,', $acc_subproperty_data[$ia]);


											if (!empty($acc_subproperty_data[$ip]))
											{
												$acc_subproperty_data = explode('::', $acc_subproperty_data[$ip]);
												$countAccessorySubproperty = count($acc_subproperty_data);

												for ($isp = 0; $isp < $countAccessorySubproperty; $isp++)
												{
													$subproperty       = RedshopHelperProduct_Attribute::getAttributeSubProperties($acc_subproperty_data[$isp]);
													$pricelist         = RedshopHelperProduct_Attribute::getPropertyPrice($acc_subproperty_data[$isp], $data['quantity'], 'subproperty', $user_id);

													if (count($pricelist) > 0)
													{
														$subproperty_price = $pricelist->product_price;
													}
													else
													{
														$subproperty_price = $subproperty[0]->subattribute_color_price;
													}

													$accSubpropertyCart[$isp]['subproperty_id']     = $acc_subproperty_data[$isp];
													$accSubpropertyCart[$isp]['subproperty_name']   = $subproperty[0]->text;
													$accSubpropertyCart[$isp]['subproperty_oprand'] = $subproperty[0]->oprand;
													$accSubpropertyCart[$isp]['subproperty_price']  = $subproperty_price;
												}
											}
										}

										$accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
									}
								}
							}

							$accAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
						}
					}
				}
				else
				{
					$attribute_set_id   = $this->getAttributeSetId($accessory[0]->child_product_id);
					$attributes_acc_set = array();

					if ($attribute_set_id > 0)
					{
						$attributes_acc_set = $this->getProductAccAttribute($accessory[0]->child_product_id, $attribute_set_id, 0, 0, 1);
					}

					$requireAttribute = RedshopHelperProduct_Attribute::getProductAttribute($accessory[0]->child_product_id, 0, 0, 0, 1);
					$requireAttribute = array_merge($requireAttribute, $attributes_acc_set);

					if (count($requireAttribute) > 0)
					{
						$requied_attributeArr = array();

						for ($re = 0, $countAttribute = count($requireAttribute); $re < $countAttribute; $re++)
						{
							$requied_attributeArr[$re] = urldecode($requireAttribute[$re]->attribute_name);
						}

						$requied_attribute_name = implode(", ", $requied_attributeArr);

						// Throw an error as first attribute is required
						$msg      = urldecode($requied_attribute_name) . " " . JText::_('IS_REQUIRED');
						JFactory::getApplication()->enqueueMessage($msg);

						return false;
					}
				}

				$generateAccessoryCart[$i]['accessory_childs'] = $accAttributeCart;
			}
		}

		return $generateAccessoryCart;
	}

	public function getProductAccAttribute($product_id = 0, $attribute_set_id = 0, $attribute_id = 0, $published = 0, $attribute_required = 0, $notAttributeId = 0)
	{
		$and          = "";
		$astpublished = "";

		if ($product_id != 0)
		{
			// Secure productsIds
			if ($productsIds = explode(',', $product_id))
			{
				$productsIds = Joomla\Utilities\ArrayHelper::toInteger($productsIds);

				$and .= "AND p.product_id IN (" . implode(',', $productsIds) . ") ";
			}
		}

		if ($attribute_set_id != 0)
		{
			$and .= "AND a.attribute_set_id=" . (int) $attribute_set_id . " ";
		}

		if ($published != 0)
		{
			$astpublished = " AND ast.published=" . (int) $published . " ";
		}

		if ($attribute_required != 0)
		{
			$and .= "AND a.attribute_required=" . (int) $attribute_required . " ";
		}

		if ($notAttributeId != 0)
		{
			// Secure notAttributeId
			if ($notAttributeIds = explode(',', $notAttributeId))
			{
				$notAttributeIds = Joomla\Utilities\ArrayHelper::toInteger($notAttributeIds);

				$and .= "AND a.attribute_id NOT IN (" . implode(',', $notAttributeIds) . ") ";
			}
		}

		$query = "SELECT a.attribute_id AS value,a.attribute_name AS text,a.*,ast.attribute_set_name "
			. "FROM " . $this->_table_prefix . "product_attribute AS a "
			. "LEFT JOIN " . $this->_table_prefix . "attribute_set AS ast ON ast.attribute_set_id=a.attribute_set_id "
			. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.attribute_set_id=a.attribute_set_id " . $astpublished
			. "WHERE a.attribute_name!='' "
			. $and
			. " and attribute_published=1 ORDER BY a.ordering ASC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getAttributeSetId($pid)
	{
		return RedshopEntityProduct::getInstance($pid)->get('attribute_set_id');
	}

	/**
	 * Method for generate attribute array
	 *
	 * @param   array    $data    Data of attributes
	 * @param   integer  $userId  ID of user
	 *
	 * @return  array
	 *
	 * @deprecated    2.1.0
	 * @see Redshop\Cart\Helper::generateAttribute
	 */
	public function generateAttributeArray($data, $userId = 0)
	{
		return Redshop\Cart\Helper::generateAttribute($data, $userId);
	}

	public function getSelectedCartAttributeArray($attArr = array())
	{
		$selectedproperty    = array();
		$selectedsubproperty = array();

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$propArr = $attArr[$i]['attribute_childs'];

			for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
			{
				$selectedproperty[] = $propArr[$k]['property_id'];
				$subpropArr         = $propArr[$k]['property_childs'];

				for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
				{
					$selectedsubproperty[] = $subpropArr[$l]['subproperty_id'];
				}
			}
		}

		$ret = array($selectedproperty, $selectedsubproperty);

		return $ret;
	}

	public function getSelectedCartAccessoryArray($attArr = array())
	{
		$selectedAccessory   = array();
		$selectedproperty    = array();
		$selectedsubproperty = array();

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$selectedAccessory[] = $attArr[$i]['accessory_id'];
			$attchildArr         = $attArr[$i]['accessory_childs'];

			for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
			{
				$propArr = $attchildArr[$j]['attribute_childs'];

				for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
				{
					$selectedproperty[] = $propArr[$k]['property_id'];
					$subpropArr         = $propArr[$k]['property_childs'];

					for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
					{
						$selectedsubproperty[] = $subpropArr[$l]['subproperty_id'];
					}
				}
			}
		}

		$ret = array($selectedAccessory, $selectedproperty, $selectedsubproperty);

		return $ret;
	}

	/**
	 * @param   int  $order_item_id
	 * @param   int  $is_accessory
	 * @param   int  $parent_section_id
	 * @param   int  $quantity
	 *
	 * @return  array
	 */
	public function generateAttributeFromOrder($order_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quantity = 1)
	{
		$generateAttributeCart = array();

		$orderItemAttdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "attribute", $parent_section_id);

		for ($i = 0, $in = count($orderItemAttdata); $i < $in; $i++)
		{
			$accPropertyCart                             = array();
			$generateAttributeCart[$i]['attribute_id']   = $orderItemAttdata[$i]->section_id;
			$generateAttributeCart[$i]['attribute_name'] = $orderItemAttdata[$i]->section_name;

			$orderPropdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "property", $orderItemAttdata[$i]->section_id);

			for ($p = 0, $pn = count($orderPropdata); $p < $pn; $p++)
			{
				$accSubpropertyCart = array();
				$property           = RedshopHelperProduct_Attribute::getAttributeProperties($orderPropdata[$p]->section_id);
				$pricelist          = RedshopHelperProduct_Attribute::getPropertyPrice($orderPropdata[$p]->section_id, $quantity, 'property');

				if (count($pricelist) > 0)
				{
					$property_price = $pricelist->product_price;
				}
				else
				{
					$property_price = $property[0]->property_price;
				}

				$accPropertyCart[$p]['property_id']     = $orderPropdata[$p]->section_id;
				$accPropertyCart[$p]['property_name']   = $property[0]->text;
				$accPropertyCart[$p]['property_oprand'] = $property[0]->oprand;
				$accPropertyCart[$p]['property_price']  = $property_price;

				$orderSubpropdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "subproperty", $orderPropdata[$p]->section_id);

				for ($sp = 0, $countSubproperty = count($orderSubpropdata); $sp < $countSubproperty; $sp++)
				{
					$subproperty       = RedshopHelperProduct_Attribute::getAttributeSubProperties($orderSubpropdata[$sp]->section_id);
					$pricelist         = RedshopHelperProduct_Attribute::getPropertyPrice($orderSubpropdata[$sp]->section_id, $quantity, 'subproperty');

					if (count($pricelist) > 0)
					{
						$subproperty_price = $pricelist->product_price;
					}
					else
					{
						$subproperty_price = $subproperty[0]->subattribute_color_price;
					}

					$accSubpropertyCart[$sp]['subproperty_id']     = $orderSubpropdata[$sp]->section_id;
					$accSubpropertyCart[$sp]['subproperty_name']   = $subproperty[0]->text;
					$accSubpropertyCart[$sp]['subproperty_oprand'] = $subproperty[0]->oprand;
					$accSubpropertyCart[$sp]['subproperty_price']  = $subproperty_price;
				}

				$accPropertyCart[$p]['property_childs'] = $accSubpropertyCart;
			}

			$generateAttributeCart[$i]['attribute_childs'] = $accPropertyCart;
		}

		return $generateAttributeCart;
	}

	public function generateAccessoryFromOrder($order_item_id = 0, $product_id = 0, $quantity = 1)
	{
		$generateAccessoryCart = array();

		$orderItemdata = RedshopHelperOrder::getOrderItemAccessoryDetail($order_item_id);

		foreach ($orderItemdata as $index => $orderItem)
		{
			$accessory          = RedshopHelperAccessory::getProductAccessories($orderItem->product_id);
			$accessorypricelist = \Redshop\Product\Accessory::getPrice($product_id, $accessory[0]->newaccessory_price, $accessory[0]->accessory_main_price, 1);
			$accessory_price    = $accessorypricelist[0];

			$generateAccessoryCart[$index]['accessory_id']       = $orderItem->product_id;
			$generateAccessoryCart[$index]['accessory_name']     = $accessory[0]->product_name;
			$generateAccessoryCart[$index]['accessory_oprand']   = $accessory[0]->oprand;
			$generateAccessoryCart[$index]['accessory_price']    = $accessory_price;
			$generateAccessoryCart[$index]['accessory_quantity'] = $orderItem->product_quantity;
			$generateAccessoryCart[$index]['accessory_childs']   = $this->generateAttributeFromOrder($order_item_id, 1, $orderItem->product_id, $quantity);
		}

		return $generateAccessoryCart;
	}

	public function discountCalculatorData($product_data, $data)
	{
		$use_discount_calculator = $product_data->use_discount_calc;
		$discount_calc_method    = $product_data->discount_calc_method;
		$use_range               = $product_data->use_range;
		$calc_output_array       = array();

		if ($use_discount_calculator)
		{
			$discount_cal = $this->discountCalculator($data);

			$calculator_price  = $discount_cal['product_price'];
			$product_price_tax = $discount_cal['product_price_tax'];

			$discountArr = array();
			if ($calculator_price)
			{
				$calc_output               = "Type : " . $discount_calc_method . "<br />";
				$calc_output_array['type'] = $discount_calc_method;

				if ($use_range)
				{
					$calcHeight        = @$data['calcHeight'];
					$calcWidth         = @$data['calcWidth'];
					$calcDepth         = @$data['calcDepth'];
					$calcRadius        = @$data['calcRadius'];
					$calcPricePerPiece = "";
					$totalPiece        = "";
				}
				else
				{
					$calcHeight        = @$product_data->product_height;
					$calcWidth         = @$product_data->product_width;
					$calcDepth         = @$product_data->product_length;
					$calcRadius        = @$data['calcRadius'];
					$calcPricePerPiece = @$discount_cal['price_per_piece'];
					$totalPiece        = @$discount_cal['total_piece'];
				}

				switch ($discount_calc_method)
				{
					case "volume":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_HEIGHT') . " " . $calcHeight . "<br />";
						$calc_output_array['calcHeight'] = $calcHeight;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_WIDTH') . " " . $calcWidth . "<br />";
						$calc_output_array['calcWidth'] = $calcWidth;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_LENGTH') . " " . $calcDepth . "<br />";
						$calc_output_array['calcDepth'] = $calcDepth;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}

						break;

					case "area":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_DEPTH') . " " . $calcDepth . "<br />";
						$calc_output_array['calcDepth'] = $calcDepth;
						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_WIDTH') . " " . $calcWidth . "<br />";
						$calc_output_array['calcWidth'] = $calcWidth;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}

						break;

					case "circumference":

						$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_RADIUS') . " " . $calcRadius . "<br />";
						$calc_output_array['calcRadius'] = $calcRadius;

						if ($calcPricePerPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_PRICE_PER_PIECE') . " " . $calcPricePerPiece . "<br />";
							$calc_output_array['calcPricePerPiece'] = $calcDepth;
						}

						if ($totalPiece != "")
						{
							$calc_output .= JText::_('COM_REDSHOP_TOTAL_PIECE') . " " . $totalPiece . "<br />";
							$calc_output_array['totalPiece'] = $totalPiece;
						}
						break;
				}

				$calc_output .= JText::_('COM_REDSHOP_DISCOUNT_CALC_UNIT') . " " . $data['calcUnit'];
				$calc_output_array['calcUnit'] = $data['calcUnit'];

				// Extra selected value data
				$calc_output .= "<br />" . $discount_cal['pdcextra_data'];

				// Extra selected value ids
				$calc_output_array['calcextra_ids'] = $discount_cal['pdcextra_ids'];

				$discountArr[] = $calc_output;
				$discountArr[] = $calc_output_array;
				$discountArr[] = $calculator_price;
				$discountArr[] = $product_price_tax;

				return $discountArr;
			}
			else
			{
				return array();
			}
		}
	}

	/**
	 * Discount calculator Ajax Function
	 *
	 * @param   array  $get
	 *
	 * @return  array
	 */
	public function discountCalculator($get)
	{
		$productId = (int) $get['product_id'];

		$discount_cal = array();

		$productPrice = RedshopHelperProductPrice::getNetPrice($productId);

		$product_price = $productPrice['product_price_novat'];

		$data = RedshopHelperProduct::getProductById($productId);

		// Default calculation method
		$calcMethod = $data->discount_calc_method;

		// Default calculation unit
		$globalUnit = "m";

		// Use range or not
		$use_range = $data->use_range;

		$calcHeight = $get['calcHeight'];
		$calcWidth  = $get['calcWidth'];
		$calcLength = $get['calcDepth'];
		$calcRadius = $get['calcRadius'];
		$calcUnit   = trim($get['calcUnit']);

		$calcHeight = str_replace(",", ".", $calcHeight);
		$calcWidth  = str_replace(",", ".", $calcWidth);
		$calcLength = str_replace(",", ".", $calcLength);
		$calcRadius = $cart_mdata = str_replace(",", ".", $calcRadius);
		$calcUnit   = $cart_mdata = str_replace(",", ".", $calcUnit);

		// Convert unit using helper function
		$unit = \Redshop\Helper\Utility::getUnitConversation($globalUnit, $calcUnit);

		$calcHeight *= $unit;
		$calcWidth *= $unit;
		$calcLength *= $unit;
		$calcRadius *= $unit;

		$product_unit = 1;

		if (!$use_range)
		{
			$product_unit = \Redshop\Helper\Utility::getUnitConversation($globalUnit, Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'));

			$product_height   = $data->product_height * $product_unit;
			$product_width    = $data->product_width * $product_unit;
			$product_length   = $data->product_length * $product_unit;
			$product_diameter = $data->product_diameter * $product_unit;
		}

		$Area      = 0;

		switch ($calcMethod)
		{
			case "volume":

				$Area = $calcHeight * $calcWidth * $calcLength;

				if (!$use_range)
					$product_area = $product_height * $product_width * $product_length;
				break;

			case "area":
				$Area = $calcLength * $calcWidth;

				if (!$use_range)
					$product_area = $product_length * $product_width;
				break;

			case "circumference":

				$Area = 2 * PI * $calcRadius;

				if (!$use_range)
					$product_area = PI * $product_diameter;
				break;
		}

		$finalArea = $Area;

		if ($use_range)
		{
			$finalArea = number_format($finalArea, 8, '.', '');

			// Calculation prices as per various area
			$discount_calc_data = $this->getDiscountCalcData($finalArea, $productId);

		}
		else
		{
			// Shandard size of product
			$final_product_Area = $product_area;

			// Total sheet calculation
			if ($final_product_Area <= 0)
				$final_product_Area = 1;
			$total_sheet = $finalArea / $final_product_Area;

			// Returns the next highest integer value by rounding up value if necessary.
			if (isset($data->allow_decimal_piece) && $data->allow_decimal_piece)
			{
				$total_sheet = ceil($total_sheet);
			}

			// If sheet is less than 0 or equal to 0 than
			if ($total_sheet <= 0)
				$total_sheet = 1;

			// Product price of all sheets
			$product_price_total = $total_sheet * $product_price;

			$discount_calc_data = array();
			$discount_calc_data[0] = new stdClass;

			// Generating array
			$discount_calc_data[0]->area_price         = $product_price;
			$discount_calc_data[0]->discount_calc_unit = $product_unit;
			$discount_calc_data[0]->price_per_piece    = $product_price_total;
		}

		$area_price       = 0;
		$pricePerPieceTax = 0;

		if (count($discount_calc_data))
		{
			$area_price = $discount_calc_data[0]->area_price;

			// Discount calculator extra price enhancement
			$pdcextraid = $get['pdcextraid'];
			$pdcstring  = $pdcids = array();

			if (trim($pdcextraid) != "")
			{
				$pdcextradatas = $this->getDiscountCalcDataExtra($pdcextraid);

				for ($pdc = 0, $countExtrafield = count($pdcextradatas); $pdc < $countExtrafield; $pdc++)
				{
					$pdcextradata = $pdcextradatas[$pdc];
					$option_name  = $pdcextradata->option_name;
					$pdcprice     = $pdcextradata->price;
					$pdcoprand    = $pdcextradata->oprand;
					$pdcextra_id  = $pdcextradata->pdcextra_id;

					$pdcstring[] = $option_name . ' (' . $pdcoprand . ' ' . $pdcprice . ' )';
					$pdcids[]    = $pdcextra_id;

					switch ($pdcoprand)
					{
						case "+":
							$area_price += $pdcprice;
							break;
						case "-":
							$area_price -= $pdcprice;
							break;
						case "%":
							$area_price *= 1 + ($pdcprice / 100);
							break;
					}
				}
			}

			// Applying TAX
			$chktag              = \Redshop\Template\Helper::isApplyAttributeVat();

			if ($use_range)
			{
				$display_final_area = $finalArea / ($unit * $unit);
				$price_per_piece = $area_price;

				$pricePerPieceTax = RedshopHelperProduct::getProductTax($productId, $price_per_piece, 0, 1);

				echo $display_final_area . "\n";

				echo $area_price . "\n";

				echo $price_per_piece . "\n";

				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

				echo $pricePerPieceTax . "\n";
				echo $chktag . "\n";
			}
			else
			{
				$price_per_piece = $discount_calc_data[0]->price_per_piece;

				$pricePerPieceTax = RedshopHelperProduct::getProductTax($productId, $price_per_piece, 0, 1);

				echo $Area . "<br />" . JText::_('COM_REDSHOP_TOTAL_PIECE') . $total_sheet . "\n";

				echo $area_price . "\n";

				echo $price_per_piece . "\n";

				echo JText::_('COM_REDSHOP_TOTAL_AREA') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_PER_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_OF_ALL_PIECE') . "\n";

				echo JText::_('COM_REDSHOP_PRICE_TOTAL') . "\n";

				echo $pricePerPieceTax . "\n";
				echo $chktag . "\n";
			}
		}
		else
		{
			$price_per_piece = false;
			echo "fail";
		}

		$discount_cal['product_price']     = $price_per_piece;
		$discount_cal['product_price_tax'] = $pricePerPieceTax;
		$discount_cal['pdcextra_data']     = "";

		if (isset($pdcstring) && count($pdcstring) > 0)
		{
			$discount_cal['pdcextra_data'] = implode("<br />", $pdcstring);
		}

		$discount_cal['pdcextra_ids']      = '';

		if (isset($pdcids) && (count($pdcids) > 0))
		{
			$discount_cal['pdcextra_ids'] = implode(",", $pdcids);
		}

		if (isset($total_sheet))
		{
			$discount_cal['total_piece']       = $total_sheet;
		}

		$discount_cal['price_per_piece']   = $area_price;

		return $discount_cal;
	}

	/**
	 * Funtion get Discount calculation data
	 *
	 * @param   number  $area         default value is 0
	 * @param   number  $pid          default value can be null
	 * @param   number  $areabetween  default value is 0
	 *
	 * @return object|mixed
	 */
	public function getDiscountCalcData($area = 0, $pid = 0, $areabetween = 0)
	{
		$query = $this->_db->getQuery(true)
			->select("*")
			->from($this->_db->quoteName("#__redshop_product_discount_calc"))
			->where($this->_db->quoteName("product_id") . "=" . (int) $pid)
			->order("id ASC");

		if ($areabetween)
		{
			$query->where((floatval($area)) . " BETWEEN `area_start` AND `area_end` ");
		}

		if ($area)
		{
			$query->where($this->_db->quoteName("area_start_converted") . "<=" . floatval($area))
				->where($this->_db->quoteName("area_end_converted") . ">=" . floatval($area));
		}

		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/**
	 * @param   string  $pdcextraids
	 * @param   int     $productId
	 *
	 * @return  mixed
	 */
	public function getDiscountCalcDataExtra($pdcextraids = "", $productId = 0)
	{
		return RedshopHelperCartDiscount::getDiscountCalcDataExtra($pdcextraids, $productId);
	}

	/**
	 * Handle required attribute before add in to cart messages
	 *
	 * @param   array   $data                  cart data
	 * @param   string  $attributeTemplate     Attribute added data
	 * @param   array   $selectedAttrId        Selected attribute id for add to cart
	 * @param   array   $selectedPropId        Selected Property Id for Add to cart
	 * @param   array   $notselectedSubpropId  Not selected subproperty ids during add to cart
	 *
	 * @return  string  Error Message if found otherwise return null.
	 * @throws  Exception
	 */
	public function handleRequiredSelectedAttributeCartMessage($data, $attributeTemplate, $selectedAttrId, $selectedPropId, $notselectedSubpropId)
	{
		if (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE'))
		{
			return;
		}

		// Check if required attribute is filled or not ...
		$attributeTemplateArray = \Redshop\Template\Helper::getAttribute($attributeTemplate);

		if (!empty($attributeTemplateArray))
		{
			$selectedAttributId = 0;

			if (count($selectedAttrId) > 0)
			{
				$selectedAttributId = implode(",", $selectedAttrId);
			}

			$requiredAttribute = RedshopHelperProduct_Attribute::getProductAttribute(
								$data['product_id'],
								0,
								0,
								0,
								1,
								$selectedAttributId
							);

			if (!empty($requiredAttribute))
			{
				$requiredAttributeArray = array();

				for ($re = 0, $countAttribute = count($requiredAttribute); $re < $countAttribute; $re++)
				{
					$requiredAttributeArray[$re] = urldecode($requiredAttribute[$re]->attribute_name);
				}

				$requiredAttributeName = implode(", ", $requiredAttributeArray);

				// Error message if first attribute is required
				return $requiredAttributeName . " " . JText::_('COM_REDSHOP_IS_REQUIRED');
			}

			$selectedPropertyId = 0;

			if (!empty($selectedPropId))
			{
				$selectedPropertyId = implode(",", $selectedPropId);
			}

			$notselectedSubpropertyId = 0;

			if (count($notselectedSubpropId) > 0)
			{
				$notselectedSubpropertyId = implode(",", $notselectedSubpropId);
			}

			$requiredProperty = RedshopHelperProduct_Attribute::getAttributeProperties(
								/** @scrutinizer ignore-type */ $selectedPropertyId,
								/** @scrutinizer ignore-type */ $selectedAttributId,
								$data['product_id'],
								0,
								1,
								/** @scrutinizer ignore-type */ $notselectedSubpropertyId
							);

			if (!empty($requiredProperty))
			{
				$requiredSubAttributeArray = array();

				for ($re1 = 0, $countProperty = count($requiredProperty); $re1 < $countProperty; $re1++)
				{
					$requiredSubAttributeArray[$re1] = urldecode($requiredProperty[$re1]->property_name);
				}

				$requiredSubAttributeName = implode(",", $requiredSubAttributeArray);

				// Give error as second attribute is required
				if ($data['reorder'] != 1)
				{
					return $requiredSubAttributeName . " " . JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED');
				}
			}
		}

		return;
	}
}
