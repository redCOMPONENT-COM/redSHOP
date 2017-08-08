<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Cart Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerCart extends RedshopController
{
	/**
	 * Constructor
	 *
	 * @param   array $default config array
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->_carthelper = rsCarthelper::getInstance();
	}

	/**
	 * Method to add product in cart
	 *
	 * @return void
	 */
	public function add()
	{
		$app                        = JFactory::getApplication();
		$post                       = $app->input->post->getArray();
		$parent_accessory_productid = $post['product_id'];

		$producthelper = productHelper::getInstance();
		$rsCartHelper  = rsCarthelper::getInstance();
		$Itemid        = RedshopHelperUtility::getCartItemId();

		// Call add method of modal to store product in cart session
		$userfield = $app->input->get('userfield');

		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$dispatcher->trigger('onBeforeAddProductToCart', array(&$post));

		$result = $rsCartHelper->addProductToCart($post);

		if (!is_bool($result) || (is_bool($result) && !$result))
		{
			$errorMessage = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

			// Set Error Message
			$app->enqueueMessage($errorMessage, 'error');

			if (Redshop::getConfig()->get('AJAX_CART_BOX') == 1)
			{
				echo "`0`" . $errorMessage;
				die();
			}
			else
			{
				$itemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['product_id']);

				if (count($itemData) > 0)
				{
					$prdItemid = $itemData->id;
				}
				else
				{
					// @TODO Verify $product variable
					$prdItemid = RedshopHelperUtility::getItemId($post['product_id'], $product->cat_in_sefurl);
				}

				// Directly redirect if error found
				$app->redirect(
					JRoute::_(
						'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&cid=' . $post['category_id'] . '&Itemid=' . $prdItemid,
						false
					)
				);
			}
		}

		$session = JFactory::getSession();
		$cart = RedshopHelperCartSession::getCart();

		if (isset($cart['AccessoryAsProduct']) && $post['accessory_data'] != '')
		{
			$attArr = $cart['AccessoryAsProduct'];

			if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE'))
			{
				$data['accessory_data']       = $attArr[0];
				$data['acc_quantity_data']    = $attArr[1];
				$data['acc_attribute_data']   = $attArr[2];
				$data['acc_property_data']    = $attArr[3];
				$data['acc_subproperty_data'] = $attArr[4];

				if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0))
				{
					$accessory_data       = explode("@@", $data['accessory_data']);
					$acc_quantity_data    = explode("@@", $data['acc_quantity_data']);
					$acc_attribute_data   = explode("@@", $data['acc_attribute_data']);
					$acc_property_data    = explode("@@", $data['acc_property_data']);
					$acc_subproperty_data = explode("@@", $data['acc_subproperty_data']);

					for ($i = 0, $in = count($accessory_data); $i < $in; $i++)
					{
						$accessory                               = RedshopHelperAccessory::getProductAccessories($accessory_data[$i]);
						$cartData                                = array();
						$cartData['parent_accessory_product_id'] = $parent_accessory_productid;
						$cartData['product_id']                  = $accessory[0]->child_product_id;
						$cartData['quantity']                    = $acc_quantity_data[$i];
						$cartData['category_id']                 = 0;
						$cartData['sel_wrapper_id']              = 0;
						$cartData['attribute_data']              = $acc_attribute_data[$i];
						$cartData['property_data']               = $acc_property_data[$i];
						$cartData['subproperty_data']            = $acc_subproperty_data[$i];
						$cartData['accessory_id']                = $accessory_data[$i];

						$result = $rsCartHelper->addProductToCart($cartData);

						$cart = $session->get('cart');

						if (is_bool($result) && $result)
						{
						}
						else
						{
							$errorMessage = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

							$app->enqueueMessage($errorMessage, 'error');

							if (JError::isError(JError::getError()))
							{
								$error        = JError::getError();
								$errorMessage = $error->getMessage();
								$app->enqueueMessage($this->getError(), 'error');
							}

							if (Redshop::getConfig()->get('AJAX_CART_BOX') == 1)
							{
								echo "`0`" . $errorMessage;
								die();
							}
							else
							{
								$itemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['product_id']);

								if (count($itemData) > 0)
								{
									$prdItemid = $itemData->id;
								}
								else
								{
									$prdItemid = RedshopHelperUtility::getItemId($post['product_id']);
								}

								$app->redirect(
									JRoute::_(
										'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&Itemid=' . $prdItemid,
										false
									)
								);
							}
						}
					}
				}
			}

			if (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
			{
				RedshopHelperCart::addCartToDatabase();
			}

			RedshopHelperCart::cartFinalCalculation();
			unset($cart['AccessoryAsProduct']);
		}
		else
		{
			if (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
			{
				RedshopHelperCart::addCartToDatabase();
			}

			RedshopHelperCart::cartFinalCalculation();
		}

		$link = JRoute::_(
			'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&Itemid=' . $Itemid,
			false
		);

		if (!$userfield)
		{
			if (Redshop::getConfig()->get('AJAX_CART_BOX') == 1 && isset($post['ajax_cart_box']))
			{
				$link = JRoute::_(
					'index.php?option=com_redshop&view=cart&ajax_cart_box=' . $post['ajax_cart_box'] . '&tmpl=component&Itemid=' . $Itemid,
					false
				);
			}
			else
			{
				if (Redshop::getConfig()->get('ADDTOCART_BEHAVIOUR') == 1)
				{
					$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid, false);
				}
				else
				{
					if (isset($cart['notice_message']) && $cart['notice_message'] != "")
					{
						$this->setMessage($cart['notice_message'], 'warning');
					}

					$this->setMessage(JText::_('COM_REDSHOP_PRODUCT_ADDED_TO_CART'), 'message');
					$link = JRoute::_($_SERVER['HTTP_REFERER'], false);
				}
			}
		}

		$userDocuments = $session->get('userDocument', array());

		if (isset($userDocuments[$post['product_id']]))
		{
			unset($userDocuments[$post['product_id']]);
			$session->set('userDocument', $userDocuments);
		}

		$this->setRedirect($link);
	}

	public function modifyCalculation($cart)
	{
		$producthelper            = productHelper::getInstance();
		$calArr                   = $this->_carthelper->calculation($cart);
		$cart['product_subtotal'] = $calArr[1];
		$session                  = JFactory::getSession();
		$discountAmount           = 0;
		$voucherDiscount          = 0;
		$couponDiscount           = 0;
		$totaldiscount            = 0;

		if (Redshop::getConfig()->get('DISCOUNT_ENABLE') == 1)
		{
			$discountAmount = $producthelper->getDiscountAmount($cart);

			if ($discountAmount > 0)
			{
				$cart = $session->get('cart');
			}
		}

		$cart['cart_discount'] = $discountAmount;

		if (array_key_exists('voucher', $cart))
		{
			$voucherDiscount = $this->_carthelper->calculateDiscount('voucher', $cart['voucher']);
		}

		$cart['voucher_discount'] = $voucherDiscount;

		if (array_key_exists('coupon', $cart))
		{
			$couponDiscount = $this->_carthelper->calculateDiscount('coupon', $cart['coupon']);
		}

		$cart['coupon_discount'] = $couponDiscount;
		$codeDsicount            = $voucherDiscount + $couponDiscount;
		$totaldiscount           = $cart['cart_discount'] + $codeDsicount;

		$calArr = $this->_carthelper->calculation($cart);

		$tax         = $calArr[5];
		$discountVAT = 0;
		$chktag      = $producthelper->taxexempt_addtocart();

		if ((float) Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT') && !Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT') && !empty($chktag))
		{
			if (isset($cart['discount_tax']) && !empty($cart['discount_tax']))
			{
				$discountVAT = $cart['discount_tax'];
				$calArr[1]   = $calArr[1] - $cart['discount_tax'];
				$tax         = $tax - $discountVAT;
			}
			else
			{
				$vatData = RedshopHelperTax::getVatRates();

				if (isset($vatData->tax_rate) && !empty($vatData->tax_rate))
				{
					$productPriceExclVAT = $cart['product_subtotal_excl_vat'];
					$productVAT          = $cart['product_subtotal'] - $cart['product_subtotal_excl_vat'];
					$avgVAT              = (($productPriceExclVAT + $productVAT) / $productPriceExclVAT) - 1;
					$discountVAT         = ($avgVAT * $totaldiscount) / (1 + $avgVAT);
				}
			}
		}

		$cart['total']             = $calArr[0] - $totaldiscount;
		$cart['subtotal']          = $calArr[1] + $calArr[3] - $totaldiscount;
		$cart['subtotal_excl_vat'] = $calArr[2] + ($calArr[3] - $calArr[6]) - ($totaldiscount - $discountVAT);

		if ($cart['total'] <= 0)
		{
			$cart['subtotal_excl_vat'] = 0;
		}

		$cart['product_subtotal']          = $calArr[1];
		$cart['product_subtotal_excl_vat'] = $calArr[2];
		$cart['shipping']                  = $calArr[3];
		$cart['tax']                       = $tax;
		$cart['sub_total_vat']             = $tax + $calArr[6];
		$cart['discount_vat']              = $discountVAT;
		$cart['shipping_tax']              = $calArr[6];
		$cart['discount_ex_vat']           = $totaldiscount - $discountVAT;
		$cart['mod_cart_total']            = $this->_carthelper->GetCartModuleCalc($cart);

		RedshopHelperCartSession::setCart($cart);

		return $cart;
	}

	/**
	 * Method to add coupon code in cart for discount
	 *
	 * @return void
	 */
	public function coupon()
	{
		$Itemid    = RedshopHelperUtility::getCartItemId();

		// Call coupon method of model to apply coupon
		$valid = $this->getModel('cart')->coupon();
		$cart  = RedshopHelperCartSession::getCart();
		$this->modifyCalculation($cart);
		RedshopHelperCart::cartFinalCalculation(false);

		// Store cart entry in db
		RedshopHelperCart::addCartToDatabase();

		// If coupon code is valid than apply to cart else raise error
		if ($valid)
		{
			$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid, false);

			if (Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT') != 1)
			{
				$this->setRedirect($link, JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE'), 'warning');
			}
			else
			{
				$this->setRedirect($link, JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID'));
			}
		}
		else
		{
			$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $Itemid, false);
			$this->setRedirect($link, JText::_('COM_REDSHOP_COUPON_CODE_IS_NOT_VALID'), 'error');
		}
	}

	/**
	 * Method to add voucher code in cart for discount
	 *
	 * @return void
	 */
	public function voucher()
	{
		$session = JFactory::getSession();
		$itemId  = RedshopHelperUtility::getCartItemId();

		// Call voucher method of model to apply voucher to cart
		// if voucher code is valid than apply to cart else raise error
		if ($this->getModel('cart')->voucher())
		{
			$cart = $session->get('cart');
			$this->modifyCalculation($cart);
			RedshopHelperCart::cartFinalCalculation(false);

			$link = JRoute::_('index.php?option=com_redshop&view=cart&seldiscount=voucher&Itemid=' . $itemId, false);

			if (Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT') != 1)
			{
				$this->setRedirect($link, JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE'), 'warning');
			}
			else
			{
				$this->setRedirect($link, JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID'));
			}
		}
		else
		{
			$msg  = JText::_('COM_REDSHOP_VOUCHER_CODE_IS_NOT_VALID');
			$link = JRoute::_('index.php?option=com_redshop&view=cart&msg=' . $msg . '&seldiscount=voucher&Itemid=' . $itemId, false);
			$this->setRedirect($link, $msg, 'error');
		}
	}

	/**
	 * Method to update product info in cart
	 *
	 * @return void
	 */
	public function update()
	{
		$post  = JFactory::getApplication()->input->post->getArray();
		$model = $this->getModel('cart');

		if (isset($post['checkQuantity']))
		{
			unset($post['checkQuantity']);
		}

		// Call update method of model to update product info of cart
		$model->update($post);

		RedshopHelperCart::cartFinalCalculation();
		RedshopHelperCart::addCartToDatabase();

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperUtility::getCartItemId(), false);
		$this->setRedirect($link);
	}

	/**
	 * Method to update all product info in cart
	 *
	 * @return void
	 */
	public function update_all()
	{
		$post  = JFactory::getApplication()->input->post->getArray();
		$model     = $this->getModel('cart');

		// Call update_all method of model to update all products info of cart
		$model->update_all($post);

		RedshopHelperCart::cartFinalCalculation();
		RedshopHelperCart::addCartToDatabase();

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperUtility::getCartItemId(), false);
		$this->setRedirect($link);
	}

	/**
	 * Method to make cart empty
	 *
	 * @return void
	 */
	public function empty_cart()
	{
		// Call empty_cart method of model to remove all products from cart
		$this->getModel('cart')->emptyCart();
		$user = JFactory::getUser();

		if ($user->id)
		{
			RedshopHelperCart::removeCartFromDatabase(0, $user->id, true);
		}

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperUtility::getCartItemId(), false);
		$this->setRedirect($link);
	}

	/**
	 * Method to delete cart entry from session
	 *
	 * @return void
	 */
	public function delete()
	{
		$post        = JFactory::getApplication()->input->post->getArray();
		$cartElement = $post['cart_index'];

		$this->getModel('cart')->delete($cartElement);
		RedshopHelperCart::cartFinalCalculation();
		RedshopHelperCart::addCartToDatabase();

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperUtility::getCartItemId(), false);
		$this->setRedirect($link);
	}

	/**
	 * Method to delete cart entry from session by ajax
	 *
	 * @return void
	 */
	public function ajaxDeleteCartItem()
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app         = JFactory::getApplication();
		$input       = $app->input;
		$cartElement = $input->post->getInt('idx');
		$model       = $this->getModel('cart');
		$input->set('ajax_cart_box', 1);
		$model->delete($cartElement);

		RedshopHelperCart::addCartToDatabase();
		RedshopHelperCart::cartFinalCalculation();

		$app->close();
	}

	/**
	 * discount calculator Ajax Function
	 *
	 * @return discount by Ajax
	 */
	public function discountCalculator()
	{
		ob_clean();
		$get = JFactory::getApplication()->input->get->getArray('GET');
		rsCarthelper::getInstance()->discountCalculator($get);

		JFactory::getApplication()->close();
	}

	/**
	 * Method to add multiple products by its product number using mod_redmasscart module.
	 *
	 * @return void
	 */
	public function redmasscart()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app  = JFactory::getApplication();
		$post = $app->input->post->getArray();

		if ($post["numbercart"] == "")
		{
			$msg  = JText::_('COM_REDSHOP_PLEASE_ENTER_PRODUCT_NUMBER');
			$rurl = base64_decode($post["rurl"]);
			$app->redirect($rurl, $msg);
		}

		$this->getModel('cart')->redmasscart($post);

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $app->input->getInt('Itemid'), false);
		$this->setRedirect($link);
	}

	/**
	 * Get Shipping rate function
	 *
	 * @return shipping rate by Ajax
	 */
	public function getShippingrate()
	{
		echo RedshopHelperShipping::getShippingRateCalc();

		JFactory::getApplication()->close();
	}

	/**
	 * change Attribute
	 *
	 * @return void
	 */
	public function changeAttribute()
	{
		$post  = JFactory::getApplication()->input->post->getArray();
		$model = $this->getModel('cart');

		$cart = rsCarthelper::getInstance()->modifyCart($model->changeAttribute($post), JFactory::getUser()->id);

		RedshopHelperCartSession::setCart($cart);
		RedshopHelperCart::cartFinalCalculation();

		?>
		<script type="text/javascript">
			window.parent.location.reload();
		</script>
		<?php
	}

	/**
	 * Method called when user pressed cancel button
	 *
	 * @return void
	 */
	public function cancel()
	{
		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . JFactory::getApplication()->inpt->getInt('Itemid'), false); ?>
		<script language="javascript">
			window.parent.location.href = "<?php echo $link ?>";
		</script>
		<?php
		JFactory::getApplication()->close();
	}

	/**
	 * Get product tax for ajax request
	 *
	 * @return  string  Return json response for tax
	 */
	public function ajaxGetProductTax()
	{
		RedshopHelperAjax::validateAjaxRequest('get');

		$app = JFactory::getApplication();

		$productId    = $app->input->getInt('id', 0);
		$productPrice = $app->input->getFloat('price', 0);
		$userId       = $app->input->getInt('userId', 0);
		$taxExempt    = $app->input->getBool('taxExempt', false);

		$product = new JRegistry;
		$product->set(
			'tax',
			RedshopHelperProduct::getProductTax(
				$productId,
				$productPrice,
				$userId,
				$taxExempt
			)
		);

		ob_clean();
		echo $product;

		$app->close();
	}
}
