<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class cartModelcart.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelCart extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	/**
	 *  Product data
	 *
	 * @var  [type]
	 */
	public $_product = null;

	public $_table_prefix = null;

	public $_template = null;

	public $_r_voucher = 0;

	public $_c_remain = 0;

	public $_globalvoucher = 0;

	public $_producthelper = null;

	public $_carthelper = null;

	public $_userhelper = null;

	public $_objshipping = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$this->_producthelper = productHelper::getInstance();
		$this->_carthelper    = rsCarthelper::getInstance();
		$this->_userhelper    = rsUserHelper::getInstance();
		$this->_objshipping   = shipping::getInstance();
		$user                 = JFactory::getUser();
		$session              = JFactory::getSession();

		// Remove expired products from cart
		$this->emptyExpiredCartProducts();

		$cart = $session->get('cart');

		if (!empty($cart))
		{
			if (!$cart)
			{
				$cart        = array();
				$cart['idx'] = 0;
			}

			$user_id        = $user->id;
			$usersess       = $session->get('rs_user');
			$shopperGroupId = $this->_userhelper->getShopperGroup($user_id);

			if (array_key_exists('user_shopper_group_id', $cart))
			{
				$userArr = $this->_producthelper->getVatUserinfo($user_id);

				// Removed due to discount issue $usersess['vatCountry']
				if ($cart['user_shopper_group_id'] != $shopperGroupId
					|| (!isset($usersess['vatCountry']) || !isset($usersess['vatState']) || $usersess['vatCountry'] != $userArr->country_code || $usersess['vatState'] != $userArr->state_code))
				{
					$cart                          = $this->_carthelper->modifyCart($cart, $user_id);
					$cart['user_shopper_group_id'] = $shopperGroupId;

					$task = JFactory::getApplication()->input->getCmd('task');

					if ($task != 'coupon' && $task != 'voucher')
					{
						$cart = $this->_carthelper->modifyDiscount($cart);
					}
				}
			}

			$session->set('cart', $cart);
		}
	}

	public function emptyExpiredCartProducts()
	{
		if (Redshop::getConfig()->get('IS_PRODUCT_RESERVE') && Redshop::getConfig()->get('USE_STOCKROOM'))
		{
			$stockroomhelper = rsstockroomhelper::getInstance();
			$session         = JFactory::getSession();
			$db              = JFactory::getDbo();
			$cart            = $session->get('cart');
			$session_id      = session_id();
			$carttimeout     = (int) Redshop::getConfig()->get('CART_TIMEOUT');
			$time            = time() - ($carttimeout * 60);

			$sql = "SELECT product_id FROM " . $this->_table_prefix . "cart "
				. "WHERE session_id = " . $db->quote($session_id) . " "
				. "AND section='product' "
				. "AND time < $time ";
			$db->setQuery($sql);
			$deletedrs = $db->loadColumn();

			$sql = "SELECT product_id FROM " . $this->_table_prefix . "cart "
				. "WHERE session_id = " . $db->quote($session_id) . " "
				. "AND section='product' ";
			$db->setQuery($sql);
			$includedrs = $db->loadColumn();

			$cart = $session->get('cart');

			if ($cart)
			{
				$idx = (int) ( isset($cart['idx']) ? $cart['idx'] : 0);

				for ($j = 0; $j < $idx; $j++)
				{
					if (count($deletedrs) > 0 && in_array($cart[$j]['product_id'], $deletedrs))
					{
						$this->delete($j);
					}

					if (count($includedrs) > 0 && !in_array($cart[$j]['product_id'], $includedrs))
					{
						$this->delete($j);
					}
				}
			}

			$stockroomhelper->deleteExpiredCartProduct();
		}
	}

	/**
	 * Empty cart
	 *
	 * @return  void
	 */
	public function empty_cart()
	{
		$session         = JFactory::getSession();
		$stockroomhelper = rsstockroomhelper::getInstance();

		$cart = $session->get('cart');
		unset($cart);
		setcookie("redSHOPcart", "", time() - 3600, "/");
		$cart['idx'] = 0;
		$session->set('cart', $cart);
		$stockroomhelper->deleteCartAfterEmpty();
	}

	public function getData()
	{
		if (empty($this->_data))
		{
			$redTemplate = Redtemplate::getInstance();

			if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'))
			{
				$this->_data = $redTemplate->getTemplate("quotation_cart");
			}
			else
			{
				if (!Redshop::getConfig()->get('USE_AS_CATALOG'))
				{
					$this->_data = $redTemplate->getTemplate("cart");
				}
				else
				{
					$this->_data = $redTemplate->getTemplate("catalogue_cart");
				}
			}
		}

		return $this->_data;
	}

	/**
	 * Update cart.
	 *
	 * @param   array  $data  data in cart
	 */
	public function update($data)
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$user    = JFactory::getUser();

		$cartElement = $data['cart_index'];
		$newQuantity = intval(abs($data['quantity']) > 0 ? $data['quantity'] : 1);
		$oldQuantity = intval($cart[$cartElement]['quantity']);

		$calculator_price = 0;
		$wrapper_price = 0;
		$wrapper_vat = 0;

		if ($newQuantity <= 0)
		{
			$newQuantity = 1;
		}

		if ($newQuantity != $oldQuantity)
		{
			if (isset($cart[$cartElement]['giftcard_id']) && $cart[$cartElement]['giftcard_id'])
			{
				$cart[$cartElement]['quantity'] = $newQuantity;
			}
			else
			{
				if (array_key_exists('checkQuantity', $data))
				{
					$cart[$cartElement]['quantity'] = $data['checkQuantity'];
				}
				else
				{
					$cart[$cartElement]['quantity'] = $this->_carthelper->checkQuantityInStock($cart[$cartElement], $newQuantity);
				}

				if ($newQuantity > $cart[$cartElement]['quantity'])
				{
					$cart['notice_message'] = $cart[$cartElement]['quantity'] . " " . JTEXT::_('COM_REDSHOP_AVAILABLE_STOCK_MESSAGE');
				}
				else
				{
					$cart['notice_message'] = "";
				}

				$cart[$cartElement]['cart_accessory'] = $this->updateAccessoryPriceArray($cart[$cartElement], $cart[$cartElement]['quantity']);
				$cart[$cartElement]['cart_attribute'] = $this->updateAttributePriceArray($cart[$cartElement], $cart[$cartElement]['quantity']);

				// Discount calculator
				if (!empty($cart[$cartElement]['discount_calc']))
				{
					$calcdata = $cart[$cartElement]['discount_calc'];
					$calcdata['product_id'] = $cart[$cartElement]['product_id'];

					$discount_cal = $this->_carthelper->discountCalculator($calcdata);

					$calculator_price = $discount_cal['product_price'];
					$product_price_tax = $discount_cal['product_price_tax'];
				}

				// Attribute price
				$retAttArr = $this->_producthelper->makeAttributeCart($cart[$cartElement]['cart_attribute'], $cart[$cartElement]['product_id'], $user->id, $calculator_price, $cart[$cartElement]['quantity']);
				$product_price = $retAttArr[1];
				$product_vat_price = $retAttArr[2];
				$product_old_price = $retAttArr[5] + $retAttArr[6];
				$product_old_price_excl_vat = $retAttArr[5];

				// Accessory price
				$retAccArr = $this->_producthelper->makeAccessoryCart($cart[$cartElement]['cart_accessory'], $cart[$cartElement]['product_id']);
				$accessory_total_price = $retAccArr[1];
				$accessory_vat_price = $retAccArr[2];

				if ($cart[$cartElement]['wrapper_id'])
				{
					$wrapperArr = $this->_carthelper->getWrapperPriceArr(array('product_id' => $cart[$cartElement]['product_id'], 'wrapper_id' => $cart[$cartElement]['wrapper_id']));
					$wrapper_vat = $wrapperArr['wrapper_vat'];
					$wrapper_price = $wrapperArr['wrapper_price'];
				}

				if (isset($cart[$cartElement]['subscription_id']) && $cart[$cartElement]['subscription_id'] != "")
				{
					$subscription_vat = 0;
					$subscription_detail = $this->_producthelper->getProductSubscriptionDetail($cart[$cartElement]['product_id'], $cart[$cartElement]['subscription_id']);
					$subscription_price = $subscription_detail->subscription_price;

					if ($subscription_price)
					{
						$subscription_vat = $this->_producthelper->getProductTax($cart[$cartElement]['product_id'], $subscription_price);
					}

					$product_vat_price += $subscription_vat;
					$product_price = $product_price + $subscription_price;

					$product_old_price_excl_vat += $subscription_price;
				}

				$cart[$cartElement]['product_price'] = $product_price + $product_vat_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
				$cart[$cartElement]['product_old_price'] = $product_old_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
				$cart[$cartElement]['product_old_price_excl_vat'] = $product_old_price_excl_vat + $accessory_total_price + $wrapper_price;
				$cart[$cartElement]['product_price_excl_vat'] = $product_price + $accessory_total_price + $wrapper_price;
				$cart[$cartElement]['product_vat'] = $product_vat_price + $accessory_vat_price + $wrapper_vat;
				JPluginHelper::importPlugin('redshop_product');
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger('onAfterCartUpdate', array(&$cart, $cartElement, $data));
			}
		}

		$session->set('cart', $cart);
	}

	public function update_all($data)
	{
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher    = JDispatcher::getInstance();
		$productHelper = productHelper::getInstance();
		$session       = JFactory::getSession();
		$cart          = $session->get('cart');
		$user          = JFactory::getUser();

		if (!$cart)
		{
			$cart        = array();
			$cart['idx'] = 0;
			$session->set('cart', $cart);
			$cart        = $session->get('cart');
		}

		$idx           = (int) ($cart['idx']);
		$quantity_all  = $data['quantity_all'];
		$quantity      = explode(",", $quantity_all);
		$totalQuantity = array_sum($quantity);

		for ($i = 0; $i < $idx; $i++)
		{
			if ($quantity[$i] < 0)
			{
				$quantity[$i] = $cart[$i]['quantity'];
			}

			$quantity[$i] = intval(abs($quantity[$i]) > 0 ? $quantity[$i] : 1);

			if ($quantity[$i] != $cart[$i]['quantity'])
			{
				if (isset($cart[$i]['giftcard_id']) && $cart[$i]['giftcard_id'])
				{
					$cart[$i]['quantity'] = $quantity[$i];
				}
				else
				{
					// Reinit price
					$productPriceInit = 0;

					// Accessory price fix during update
					$accessoryAsProdut = RedshopHelperAccessory::getAccessoryAsProduct($cart['AccessoryAsProduct']);
					$accessoryAsProdutWithoutVat = false;

					if (isset($accessoryAsProdut->accessory)
						&& isset($accessoryAsProdut->accessory[$cart[$i]['product_id']])
						&& isset($cart[$i]['accessoryAsProductEligible']))
					{
						$accessoryAsProdutWithoutVat        = '{without_vat}';
						$accessoryPrice                     = (float) $accessoryAsProdut->accessory[$cart[$i]['product_id']]->newaccessory_price;

						$productPriceInit                   = $productHelper->productPriceRound($accessoryPrice);
						$cart[$i]['product_vat']            = 0;
						$cart[$i]['product_price_excl_vat'] = $productHelper->productPriceRound($accessoryPrice);
					}

					$cart[$i]['quantity']       = $this->_carthelper->checkQuantityInStock($cart[$i], $quantity[$i]);

					$cart[$i]['cart_accessory'] = $this->updateAccessoryPriceArray($cart[$i], $cart[$i]['quantity']);
					$cart[$i]['cart_attribute'] = $this->updateAttributePriceArray($cart[$i], $cart[$i]['quantity']);

					// Discount calculator
					if (!empty($cart[$i]['discount_calc']))
					{
						$calcdata = $cart[$i]['discount_calc'];
						$calcdata['product_id'] = $cart[$i]['product_id'];

						$discount_cal = $this->_carthelper->discountCalculator($calcdata);

						$calculator_price = $discount_cal['product_price'];
						$product_price_tax = $discount_cal['product_price_tax'];
					}

					$dispatcher->trigger('onBeforeCartItemUpdate', array(&$cart, $i, &$calculator_price));

					// Attribute price
					$retAttArr = $this->_producthelper->makeAttributeCart(
						$cart[$i]['cart_attribute'],
						$cart[$i]['product_id'],
						$user->id,
						$productPriceInit,
						$totalQuantity,	// Total Quantity based discount applied here
						$accessoryAsProdutWithoutVat
					);

					$accessoryAsProductZero     = (count($retAttArr[8]) == 0 && $productPriceInit == 0 && $accessoryAsProdutWithoutVat);
					$product_price              = ($accessoryAsProductZero) ? 0 : $retAttArr[1];
					$product_vat_price          = ($accessoryAsProductZero) ? 0 : $retAttArr[2];
					$product_old_price          = ($accessoryAsProductZero) ? 0 : $retAttArr[5] + $retAttArr[6];
					$product_old_price_excl_vat = ($accessoryAsProductZero) ? 0 : $retAttArr[5];

					// Accessory price
					$retAccArr = $this->_producthelper->makeAccessoryCart(
						$cart[$i]['cart_accessory'],
						$cart[$i]['product_id']
					);
					$accessory_total_price = $retAccArr[1];
					$accessory_vat_price   = $retAccArr[2];

					$wrapper_price = 0;
					$wrapper_vat   = 0;

					if ($cart[$i]['wrapper_id'])
					{
						$wrapperArr    = $this->_carthelper->getWrapperPriceArr(array('product_id' => $cart[$i]['product_id'], 'wrapper_id' => $cart[$i]['wrapper_id']));
						$wrapper_vat   = $wrapperArr['wrapper_vat'];
						$wrapper_price = $wrapperArr['wrapper_price'];
					}

					$subscription_vat = 0;

					if (isset($cart[$i]['subscription_id']) && $cart[$i]['subscription_id'] != "")
					{
						$product_id          = $cart[$i]['product_id'];
						$subscription_detail = $this->_producthelper->getProductSubscriptionDetail($product_id, $cart[$i]['subscription_id']);
						$subscription_price  = $subscription_detail->subscription_price;

						if ($subscription_price)
						{
							$subscription_vat = $this->_producthelper->getProductTax($product_id, $subscription_price);
						}

						$product_vat_price += $subscription_vat;
						$product_price     = $product_price + $subscription_price;

						$product_old_price_excl_vat += $subscription_price;
					}

					$cart[$i]['product_price']              = $product_price + $product_vat_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
					$cart[$i]['product_old_price']          = $product_old_price + $accessory_total_price + $accessory_vat_price + $wrapper_price + $wrapper_vat;
					$cart[$i]['product_old_price_excl_vat'] = $product_old_price_excl_vat + $accessory_total_price + $wrapper_price;
					$cart[$i]['product_price_excl_vat']     = $product_price + $accessory_total_price + $wrapper_price;
					$cart[$i]['product_vat']                = $product_vat_price + $accessory_vat_price + $wrapper_vat;

					$dispatcher->trigger('onAfterCartItemUpdate', array(&$cart, $i, $data));
				}
			}
		}

		unset($cart[$idx]);

		$session->set('cart', $cart);
	}

	public function delete($cartElement)
	{
		$stockroomhelper = rsstockroomhelper::getInstance();
		$session         = JFactory::getSession();

		$cart = $session->get('cart');

		if (array_key_exists($cartElement, $cart))
		{
			if (array_key_exists('cart_attribute', $cart[$cartElement]))
			{
				foreach ($cart[$cartElement]['cart_attribute'] as $cartAttribute)
				{
					if (array_key_exists('attribute_childs', $cartAttribute))
					{
						foreach ($cartAttribute['attribute_childs'] as $attributeChilds)
						{
							if (array_key_exists('property_childs', $attributeChilds))
							{
								foreach ($attributeChilds['property_childs'] as $propertyChilds)
								{
									$stockroomhelper->deleteCartAfterEmpty($propertyChilds['subproperty_id'], 'subproperty', $cart[$cartElement]['quantity']);
								}
							}

							$stockroomhelper->deleteCartAfterEmpty($attributeChilds['property_id'], 'property', $cart[$cartElement]['quantity']);
						}
					}
				}
			}

			$stockroomhelper->deleteCartAfterEmpty($cart[$cartElement]['product_id'], 'product', $cart[$cartElement]['quantity']);
			unset($cart[$cartElement]);
			$cart = array_merge(array(), $cart);

			$Index = $cart['idx'] - 1;

			if ($Index > 0)
			{
				$cart['idx'] = $Index;
			}
			else
			{
				$cart        = array();
				$cart['idx'] = 0;
			}
		}

		$session->set('cart', $cart);
	}

	public function coupon($c_data = array())
	{
		return $this->_carthelper->coupon();
	}

	public function voucher($v_data = array())
	{
		return $this->_carthelper->voucher();
	}

	public function redmasscart($post)
	{
		$data            = array();
		$products_number = explode("\n", $post["numbercart"]);
		$db = JFactory::getDbo();

		for ($i = 0, $countNumber = count($products_number); $i < $countNumber; $i++)
		{
			$productNumber = trim($products_number[$i]);

			if ($productNumber == "")
			{
				continue;
			}

			$query = $db->getQuery(true)
				->select('product_id,published, not_for_sale, expired,product_name')
				->from($db->qn('#__redshop_product'))
				->where('product_number = ' . $db->quote($productNumber));
			$product = $db->setQuery($query)->loadObject();

			if (!$product)
			{
				continue;
			}

			$product_id = $product->product_id;

			if ($product->published == 0)
			{
				$msg = sprintf(JText::_('COM_REDSHOP_PRODUCT_IS_NOT_PUBLISHED'), $product->product_name, $product_id);
				JError::raiseWarning(20, $msg);
				continue;
			}

			if ($product->not_for_sale == 1)
			{
				$msg = sprintf(JText::_('COM_REDSHOP_PRODUCT_IS_NOT_FOR_SALE'), $product->product_name, $product_id);
				JError::raiseWarning(20, $msg);
				continue;
			}

			if ($product->expired == 1)
			{
				$msg = sprintf(JText::_('COM_REDSHOP_PRODUCT_IS_EXPIRED'), $product->product_name, $product_id);
				JError::raiseWarning(20, $msg);
				continue;
			}

			$data["product_id"] = $product_id;

			if (isset($post["mod_quantity"]) && $post["mod_quantity"] != "")
			{
				$data["quantity"] = $post["mod_quantity"];
			}
			else
			{
				$data["quantity"] = 1;
			}

			$this->_carthelper->addProductToCart($data);
			$this->_carthelper->cartFinalCalculation();
		}
	}

	/**
	 * check if attribute tag is present in product template.
	 *
	 * @param   int  $product_id  the product id
	 *
	 * @return bool
	 */
	public function checkifTagAvailable($product_id)
	{
		$db          = JFactory::getDbo();
		$redTemplate = Redtemplate::getInstance();
		$q           = "SELECT product_template FROM " . $this->_table_prefix . "product "
			. "WHERE product_id = " . (int) $product_id;

		$db->setQuery($q);
		$row_data = $db->loadResult();

		$template              = $redTemplate->getTemplate("product", $row_data);
		$product_template_desc = $template[0]->template_desc;

		if (strstr($product_template_desc, "{attribute_template:"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 	shipping rate calculator
	 */
	public function shippingrate_calc()
	{
		JHTML::script('com_redshop/common.js', false, true);

		$countryarray         = RedshopHelperWorld::getCountryList();
		$post['country_code'] = $countryarray['country_code'];
		$conutry              = $countryarray['country_dropdown'];

		$statearray           = RedshopHelperWorld::getStateList($post);
		$state                = $statearray['state_dropdown'];

		$shipping_calc = "<form name='adminForm' id='adminForm'>";
		$shipping_calc .= "<label>" . JText::_('COM_REDSHOP_COUNTRY') . "</label><br />";
		$shipping_calc .= $conutry;
		$shipping_calc .= "<div id='div_state_lbl'><label>" . JText::_('COM_REDSHOP_STATE') . "</label></div>";
		$shipping_calc .= "<div id='div_state_txt'>" . $state . "</div>";
		$shipping_calc .= "<br />";
		$shipping_calc .= "<label>" . JText::_('COM_REDSHOP_ZIPCODE') . "</label><br />";
		$shipping_calc .= "<input type='text' name='zipcode' id='zip_code' />";
		$shipping_calc .= "<br />";
		$shipping_calc .= "<input class='blackbutton btn' type='button' name='shippingcalc' id='shippingcalc' value='" . JText::_('COM_REDSHOP_UPDATE') . "' onClick='javascript:getShippingrate();' />";
		$shipping_calc .= "</form>";

		return $shipping_calc;
	}

	public function updateAccessoryPriceArray($data = array(), $newquantity = 1)
	{
		$attArr = $data['cart_accessory'];

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$attchildArr = $attArr[$i]['accessory_childs'];

			$attArr[$i]['accessory_quantity'] = $newquantity;

			for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
			{
				$propArr = $attchildArr[$j]['attribute_childs'];

				for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
				{
					$pricelist = $this->_producthelper->getPropertyPrice($propArr[$k]['property_id'], $newquantity, 'property');

					if (count($pricelist) > 0)
					{
						$propArr[$k]['property_price'] = $pricelist->product_price;
					}
					else
					{
						$pricelist                     = $this->_producthelper->getProperty($propArr[$k]['property_id'], 'property');
						$propArr[$k]['property_price'] = $pricelist->product_price;
					}

					$subpropArr = $propArr[$k]['property_childs'];

					for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
					{
						$pricelist = $this->_producthelper->getPropertyPrice($subpropArr[$l]['subproperty_id'], $newquantity, 'subproperty');

						if (count($pricelist) > 0)
						{
							$subpropArr[$l]['subproperty_price'] = $pricelist->product_price;
						}
						else
						{
							$pricelist                           = $this->_producthelper->getProperty($subpropArr[$l]['subproperty_id'], 'subproperty');
							$subpropArr[$k]['subproperty_price'] = $pricelist->product_price;
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

	public function updateAttributePriceArray($data = array(), $newquantity = 1)
	{
		$attArr = $data['cart_attribute'];

		for ($i = 0, $in = count($attArr); $i < $in; $i++)
		{
			$propArr = $attArr[$i]['attribute_childs'];

			for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
			{
				$pricelist = $this->_producthelper->getPropertyPrice($propArr[$k]['property_id'], $newquantity, 'property');

				if (count($pricelist) > 0)
				{
					$propArr[$k]['property_price'] = $pricelist->product_price;
				}
				else
				{
					$pricelist                     = $this->_producthelper->getProperty($propArr[$k]['property_id'], 'property');
					$propArr[$k]['property_price'] = $pricelist->product_price;
				}

				$subpropArr = $propArr[$k]['property_childs'];

				for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
				{
					$pricelist = $this->_producthelper->getPropertyPrice($subpropArr[$l]['subproperty_id'], $newquantity, 'subproperty');

					if (count($pricelist) > 0)
					{
						$subpropArr[$l]['subproperty_price'] = $pricelist->product_price;
					}
					else
					{
						$pricelist                           = $this->_producthelper->getProperty($subpropArr[$l]['subproperty_id'], 'subproperty');
						$subpropArr[$k]['subproperty_price'] = $pricelist->product_price;
					}
				}

				$propArr[$k]['property_childs'] = $subpropArr;
			}

			$attArr[$i]['attribute_childs'] = $propArr;
		}

		return $attArr;
	}

	public function getCartProductPrice($product_id, $cart, $voucher_left)
	{
		$productArr             = array();
		$affected_product_idArr = array();
		$idx                    = $cart['idx'];
		$product_price          = 0;
		$product_price_excl_vat = 0;
		$p_quantity             = 0;

		$product_idArr = explode(',', $product_id);

		for ($v = 0; ($v < $idx) && ($voucher_left > 0); $v++)
		{
			if ($voucher_left < $cart[$v]['quantity'] && $voucher_left)
			{
				$cart[$v]['quantity'] = $voucher_left;
			}

			if (in_array($cart[$v]['product_id'], $product_idArr) || $this->_globalvoucher)
			{
				$product_price += $cart[$v]['product_price'] * $cart[$v]['quantity'];
				$p_quantity += $cart[$v]['quantity'];
				$product_price_excl_vat += $cart[$v]['product_price_excl_vat'] * $cart[$v]['quantity'];
				$affected_product_idArr[] = $cart[$v]['product_id'];
				$voucher_left             = $voucher_left - $cart[$v]['quantity'];
			}
		}

		$productArr['product_ids']            = implode(',', $affected_product_idArr);
		$productArr['product_price']          = $product_price;
		$productArr['product_price_excl_vat'] = $product_price_excl_vat;
		$productArr['product_quantity']       = $p_quantity;

		return $productArr;
	}

	public function changeAttribute($data)
	{
		$imagename = '';
		$type      = '';
		$session   = JFactory::getSession();
		$cart      = $session->get('cart');

		$generateAttributeCart = array();
		$product_id            = $data['product_id'];
		$idx                   = $data['cart_index'];

		if (isset($data['attribute_id_prd_' . $product_id . '_0']))
		{
			$attribute_data = $data['attribute_id_prd_' . $product_id . '_0'];

			for ($ia = 0; $ia < count($attribute_data); $ia++)
			{
				$accPropertyCart                              = array();
				$attribute                                    = $this->_producthelper->getProductAttribute(0, 0, $attribute_data[$ia]);
				$generateAttributeCart[$ia]['attribute_id']   = $attribute_data[$ia];
				$generateAttributeCart[$ia]['attribute_name'] = $attribute[0]->text;

				if ($attribute[0]->text != "" && isset($data['property_id_prd_' . $product_id . '_0_' . $attribute_data[$ia]]))
				{
					$acc_property_data = $data['property_id_prd_' . $product_id . '_0_' . $attribute_data[$ia]];

					for ($ip = 0; $ip < count($acc_property_data); $ip++)
					{
						if ($acc_property_data[$ip] != 0)
						{
							$accSubpropertyCart = array();
							$property_price     = 0;
							$property           = $this->_producthelper->getAttibuteProperty($acc_property_data[$ip]);
							$pricelist          = $this->_producthelper->getPropertyPrice($acc_property_data[$ip], $cart[$idx]['quantity'], 'property');

							if (count($pricelist) > 0)
							{
								$property_price = $pricelist->product_price;
							}
							else
							{
								$property_price = $property[0]->property_price;
							}

							if (count($property) > 0 && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property[0]->property_image))
							{
								$type      = 'product_attributes';
								$imagename = $property[0]->property_image;
							}

							$accPropertyCart[$ip]['property_id']     = $acc_property_data[$ip];
							$accPropertyCart[$ip]['property_name']   = $property[0]->text;
							$accPropertyCart[$ip]['property_oprand'] = $property[0]->oprand;
							$accPropertyCart[$ip]['property_price']  = $property_price;

							if (isset($data['subproperty_id_prd_' . $product_id . '_0_' . $attribute_data[$ia] . '_' . $acc_property_data[$ip]]))
							{
								$acc_subproperty_data = $data['subproperty_id_prd_' . $product_id . '_0_' . $attribute_data[$ia] . '_' . $acc_property_data[$ip]];

								for ($isp = 0; $isp < count($acc_subproperty_data); $isp++)
								{
									if ($acc_subproperty_data[$isp] != 0)
									{
										$subproperty_price = 0;
										$subproperty       = $this->_producthelper->getAttibuteSubProperty($acc_subproperty_data[$isp]);
										$pricelist         = $this->_producthelper->getPropertyPrice($acc_subproperty_data[$isp], $cart[$idx]['quantity'], 'subproperty');

										if (count($pricelist) > 0)
										{
											$subproperty_price = $pricelist->product_price;
										}
										else
										{
											$subproperty_price = $subproperty[0]->subattribute_color_price;
										}

										if (count($subproperty) > 0 && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $subproperty[0]->subattribute_color_image))
										{
											$type      = 'subcolor';
											$imagename = $subproperty[0]->subattribute_color_image;
										}

										$accSubpropertyCart[$isp]['subproperty_id']           = $acc_subproperty_data[$isp];
										$accSubpropertyCart[$isp]['subproperty_name']         = $subproperty[0]->text;
										$accSubpropertyCart[$isp]['subproperty_oprand']       = $subproperty[0]->oprand;
										$accSubpropertyCart[$isp]['subattribute_color_title'] = $subproperty[0]->subattribute_color_title;
										$accSubpropertyCart[$isp]['subproperty_price']        = $subproperty_price;
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

		if (!empty($imagename) && !empty($type))
		{
			$cart[$idx]['hidden_attribute_cartimage'] = REDSHOP_FRONT_IMAGES_ABSPATH . $type . "/" . $imagename;
		}

		return $cart;
	}
}
