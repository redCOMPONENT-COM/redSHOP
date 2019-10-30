<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

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
	 * @var rsCarthelper
	 */
	public $cartHelper;

	/**
	 * Constructor
	 *
	 * @param   array $default config array
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->cartHelper = rsCarthelper::getInstance();
	}

	/**
	 * Method to add product in cart
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function add()
	{
		$app                      = JFactory::getApplication();
		$post                     = $app->input->post->getArray();
		$parentAccessoryProductId = $post['product_id'];

		// Invalid request then redirect to dashboard
		if (empty($app->input->post->getInt('product_id')) || empty($app->input->post->getInt('quantity')))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_CART_INVALID_REQUEST'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_redshop'));
		}

		$productHelper = productHelper::getInstance();
		$itemId        = RedshopHelperRouter::getCartItemId();

		// Call add method of modal to store product in cart session
		$userfield = $app->input->get('userfield');

		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$dispatcher->trigger('onBeforeAddProductToCart', array(&$post));

		$isAjaxCartBox = Redshop::getConfig()->getBool('AJAX_CART_BOX');
		$result = Redshop\Cart\Cart::addProduct($post);

		if (!is_bool($result) || (is_bool($result) && !$result))
		{
			$errorMessage = $result ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

			// Set Error Message
			$app->enqueueMessage($errorMessage, 'error');

			if ($isAjaxCartBox)
			{
				echo '`0`' . $errorMessage;
				$app->close();
			}
			else
			{
				$itemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['product_id']);

				if (count($itemData) > 0)
				{
					$prdItemid = $itemData->id;
				}
				else
				{
					$prdItemid = RedshopHelperRouter::getItemId($post['product_id'], RedshopProduct::getInstance($post['product_id'])->cat_in_sefurl);
				}

				// Directly redirect if error found
				$app->redirect(
					JRoute::_(
						'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&cid='
						. $post['category_id'] . '&Itemid=' . $prdItemid,
						false
					)
				);
			}
		}

		$session              = JFactory::getSession();
		$cart                 = RedshopHelperCartSession::getCart();
		$isQuotationMode      = Redshop::getConfig()->getBool('DEFAULT_QUOTATION_MODE');
		$isShowQuotationPrice = Redshop::getConfig()->getBool('SHOW_QUOTATION_PRICE');

		if (isset($cart['AccessoryAsProduct']) && !empty($post['accessory_data']))
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
					$accessories            = explode("@@", $data['accessory_data']);
					$accessoriesQuantity    = explode("@@", $data['acc_quantity_data']);
					$accessoriesAttribute   = explode("@@", $data['acc_attribute_data']);
					$accessoriesProperty    = explode("@@", $data['acc_property_data']);
					$accessoriesSubProperty = explode("@@", $data['acc_subproperty_data']);

					foreach ($accessories as $i => $accessoryId)
					{
						$accessory                               = RedshopHelperAccessory::getProductAccessories($accessoryId);
						$cartData                                = array();
						$cartData['parent_accessory_product_id'] = $parentAccessoryProductId;
						$cartData['product_id']                  = $accessory[0]->child_product_id;
						$cartData['quantity']                    = $accessoriesQuantity[$i];
						$cartData['category_id']                 = 0;
						$cartData['sel_wrapper_id']              = 0;
						$cartData['attribute_data']              = $accessoriesAttribute[$i];
						$cartData['property_data']               = $accessoriesProperty[$i];
						$cartData['subproperty_data']            = $accessoriesSubProperty[$i];
						$cartData['accessory_id']                = $accessories[$i];

						$result = Redshop\Cart\Cart::addProduct($cartData);
						$cart   = RedshopHelperCartSession::getCart();

						if (!is_bool($result) || !$result)
						{
							$errorMessage = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

							$app->enqueueMessage($errorMessage, 'error');

							if (/** @scrutinizer ignore-deprecated */JError::isError(/** @scrutinizer ignore-deprecated */JError::getError()))
							{
								$error        = /** @scrutinizer ignore-deprecated */JError::getError();
								$errorMessage = $error->getMessage();
								$app->enqueueMessage(/** @scrutinizer ignore-deprecated */$this->getError(), 'error');
							}

							if ($isAjaxCartBox)
							{
								echo '`0`' . $errorMessage;
								$app->close();
							}

							$itemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['product_id']);

							if (count($itemData) > 0)
							{
								$prdItemid = $itemData->id;
							}
							else
							{
								$prdItemid = RedshopHelperRouter::getItemId($post['product_id']);
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

			if (!$isQuotationMode || ($isQuotationMode && $isShowQuotationPrice))
			{
				RedshopHelperCart::addCartToDatabase();
			}

			RedshopHelperCart::cartFinalCalculation();
			unset($cart['AccessoryAsProduct']);
		}
		else
		{
			if (!$isQuotationMode || ($isQuotationMode && $isShowQuotationPrice))
			{
				RedshopHelperCart::addCartToDatabase();
			}

			RedshopHelperCart::cartFinalCalculation();
		}

		$link = JRoute::_(
			'index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&Itemid=' . $itemId,
			false
		);

		if (!$userfield)
		{
			if ($isAjaxCartBox && isset($post['ajax_cart_box']))
			{
				$link = JRoute::_(
					'index.php?option=com_redshop&view=cart&ajax_cart_box=' . $post['ajax_cart_box'] . '&tmpl=component&Itemid=' . $itemId,
					false
				);
			}
			else
			{
				if (Redshop::getConfig()->getInt('ADDTOCART_BEHAVIOUR') === 1)
				{
					$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $itemId, false);
				}
				else
				{
					if (isset($cart['notice_message']) && !empty($cart['notice_message']))
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

	/**
	 * Method to add coupon code in cart for discount
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function coupon()
	{
		$itemId = RedshopHelperRouter::getCartItemId();
		$app    = JFactory::getApplication();
		$ajax   = $app->input->getInt('ajax', 0);

		/** @var RedshopModelCart $model */
		$model = $this->getModel('Cart');

		// Call coupon method of model to apply coupon
		$valid = $model->coupon();

		$cart = RedshopHelperCartSession::getCart();
		$this->modifyCalculation($cart);
		RedshopHelperCart::cartFinalCalculation(false);

		// Store cart entry in db
		RedshopHelperCart::addCartToDatabase();

		$message     = null;
		$messageType = null;

		// If coupon code is valid than apply to cart else raise error
		if ($valid)
		{
			$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $itemId, false);

			if (Redshop::getConfig()->get('DISCOUNT_TYPE') == 1)
			{
				foreach ($cart as $index => $value)
				{
					if (!is_numeric($index))
					{
						continue;
					}

					$checkDiscountPro = RedshopHelperDiscount::getDiscountPriceBaseDiscountDate($value['product_id']);
				}

				if ($checkDiscountPro != 0)
				{
					$message     = JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE');
					$messageType = 'error';
				}
				else
				{
					$message     = JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID');
					$messageType = 'success';
				}
			}

			if (Redshop::getConfig()->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT') != 1)
			{
				$message     = JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE');
				$messageType = 'warning';
			}
			else
			{
				$message     = JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID');

				$this->setRedirect($link, JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID'));
			}
		}
		else
		{
			$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $itemId, false);

			$message     = JText::_('COM_REDSHOP_COUPON_CODE_IS_NOT_VALID');
			$messageType = 'error';
		}

		if ($ajax)
		{
			$carts = RedshopHelperCart::generateCartOutput(RedshopHelperCartSession::getCart());

			echo json_encode(array($valid, $message, $carts[0]));

			$app->close();
		}
		else
		{
			$this->setRedirect($link, $message, $messageType);
		}
	}

	/**
	 * Method for modify calculate cart
	 *
	 * @param   array $cart Cart data.
	 *
	 * @return  mixed
	 * @throws  Exception
	 */
	public function modifyCalculation($cart)
	{
		$calArr                   = \Redshop\Cart\Helper::calculation($cart);
		$cart['product_subtotal'] = $calArr[1];
		$discountAmount           = 0;
		$voucherDiscount          = 0;
		$couponDiscount           = 0;

		if (Redshop::getConfig()->getInt('DISCOUNT_ENABLE') == 1)
		{
			$discountAmount = Redshop\Cart\Helper::getDiscountAmount($cart);

			if ($discountAmount > 0)
			{
				$cart = RedshopHelperCartSession::getCart();
			}
		}

		$cart['cart_discount'] = $discountAmount;

		if (array_key_exists('voucher', $cart))
		{
			$voucherDiscount = RedshopHelperDiscount::calculate('voucher', $cart['voucher']);
			if (Redshop::getConfig()->get('DISCOUNT_TYPE') == 2)
			{
				$voucherDiscount = $voucherDiscount - $cart['voucher'][1]['voucher_value'];
			}
		}

		$cart['voucher_discount'] = $voucherDiscount;

		if (array_key_exists('coupon', $cart))
		{
			$couponDiscount = RedshopHelperDiscount::calculate('coupon', $cart['coupon']);
			if (Redshop::getConfig()->get('DISCOUNT_TYPE') == 2)
			{
				$couponDiscount = $couponDiscount - $cart['coupon'][1]['coupon_value'];
			}
		}

		$cart['coupon_discount'] = $couponDiscount;
		$codeDsicount            = $voucherDiscount + $couponDiscount;
		$totaldiscount           = $cart['cart_discount'] + $codeDsicount;

		$calArr = \Redshop\Cart\Helper::calculation($cart);

		$tax         = $calArr[5];
		$discountVAT = 0;
		$chktag      = RedshopHelperCart::taxExemptAddToCart();

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
		$cart['mod_cart_total']            = Redshop\Cart\Module::calculate($cart);

		RedshopHelperCartSession::setCart($cart);

		return $cart;
	}

	/**
	 * Method to add voucher code in cart for discount
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function voucher()
	{
		$itemId = RedshopHelperRouter::getCartItemId();

		/** @var RedshopModelCart $model */
		$model = $this->getModel('Cart');

		// Call voucher method of model to apply voucher to cart if f voucher code is valid than apply to cart else raise error
		if ($model->voucher())
		{
			$cart = RedshopHelperCartSession::getCart();
			$this->modifyCalculation($cart);
			RedshopHelperCart::cartFinalCalculation(false);

			$link = JRoute::_('index.php?option=com_redshop&view=cart&seldiscount=voucher&Itemid=' . $itemId, false);
			$message     = null;
			$messageType = null;

			if (Redshop::getConfig()->get('DISCOUNT_TYPE') == 1)
			{
				foreach ($cart as $index => $value)
				{
					if (!is_numeric($index))
					{
						continue;
					}

					$checkDiscountPro = RedshopHelperDiscount::getDiscountPriceBaseDiscountDate($value['product_id']);
				}

				if ($checkDiscountPro != 0)
				{
					$message     = JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID_NOT_APPLY_PRODUCTS_ON_SALE');
					$messageType = 'error';
				}
				else
				{
					$message     = JText::_('COM_REDSHOP_DISCOUNT_CODE_IS_VALID');
					$messageType = 'success';
				}
			}

			if (Redshop::getConfig()->getInt('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT') != 1)
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
	 * @throws Exception
	 */
	public function update()
	{
		$post = JFactory::getApplication()->input->post->getArray();

		/** @var RedshopModelCart $model */
		$model = $this->getModel('cart');

		if (isset($post['checkQuantity']))
		{
			unset($post['checkQuantity']);
		}

		// Call update method of model to update product info of cart
		$model->update($post);

		RedshopHelperCart::cartFinalCalculation();
		RedshopHelperCart::addCartToDatabase();

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperRouter::getCartItemId(), false);
		$this->setRedirect($link);
	}

	/**
	 * Method to update all product info in cart
	 *
	 * @return void
	 * @throws Exception
	 */
	public function update_all()
	{
		$post = JFactory::getApplication()->input->post->getArray();

		/** @var RedshopModelCart $model */
		$model = $this->getModel('cart');

		// Call update_all method of model to update all products info of cart
		$model->update_all($post);

		RedshopHelperCart::cartFinalCalculation();
		RedshopHelperCart::addCartToDatabase();

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperRouter::getCartItemId(), false);
		$this->setRedirect($link);
	}

	/**
	 * Method to make cart empty
	 *
	 * @return void
	 */
	public function empty_cart()
	{
		/** @var RedshopModelCart $model */
		$model = $this->getModel('cart');

		// Call empty_cart method of model to remove all products from cart
		$model->emptyCart();
		$user = JFactory::getUser();

		if ($user->id)
		{
			RedshopHelperCart::removeCartFromDatabase(0, $user->id, true);
		}

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperRouter::getCartItemId(), false);
		$this->setRedirect($link);
	}

	/**
	 * Method to delete cart entry from session
	 *
	 * @return void
	 * @throws Exception
	 */
	public function delete()
	{
		$post        = JFactory::getApplication()->input->post->getArray();
		$cartElement = $post['cart_index'];

		/** @var RedshopModelCart $model */
		$model = $this->getModel('cart');

		$model->delete($cartElement);
		RedshopHelperCart::cartFinalCalculation();
		RedshopHelperCart::addCartToDatabase();

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . RedshopHelperRouter::getCartItemId(), false);
		$this->setRedirect($link);
	}

	/**
	 * Method to delete cart entry from session by ajax
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function ajaxDeleteCartItem()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest();

		$app         = JFactory::getApplication();
		$input       = $app->input;
		$cartElement = $input->post->getInt('idx');

		/** @var RedshopModelCart $model */
		$model = $this->getModel('cart');

		$input->set('ajax_cart_box', 1);
		$model->delete($cartElement);

		RedshopHelperCart::addCartToDatabase();
		RedshopHelperCart::cartFinalCalculation();

		$carts = RedshopHelperCart::generateCartOutput(RedshopHelperCartSession::getCart());

		echo $carts[0];

		$app->close();
	}

	/**
	 * discount calculator Ajax Function
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function discountCalculator()
	{
		ob_clean();
		$get = JFactory::getApplication()->input->get->getArray(/** @scrutinizer ignore-type */ 'GET');
		rsCarthelper::getInstance()->discountCalculator($get);

		JFactory::getApplication()->close();
	}

	/**
	 * Method to add multiple products by its product number using mod_redmasscart module.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function redmasscart()
	{
		$app  = JFactory::getApplication();
		$post = $app->input->post->getArray();

		// Check for request forgeries.
		if (!JSession::checkToken())
		{
			$msg  = JText::_('COM_REDSHOP_TOKEN_VARIFICATION');
			$rurl = base64_decode($post["rurl"]);
			$app->redirect($rurl, $msg);;
		}

		if ($post["numbercart"] == "")
		{
			$msg  = JText::_('COM_REDSHOP_PLEASE_ENTER_PRODUCT_NUMBER');
			$rurl = base64_decode($post["rurl"]);
			$app->redirect($rurl, $msg);
		}

		/** @var RedshopModelCart $model */
		$model = $this->getModel('cart');
		$model->redmasscart($post);

		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $app->input->getInt('Itemid'), false);
		$this->setRedirect($link);
	}

	/**
	 * Get Shipping rate function
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function getShippingrate()
	{
		echo Redshop\Shipping\Rate::calculate();

		JFactory::getApplication()->close();
	}

	/**
	 * Change Attribute
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function changeAttribute()
	{
		$post = JFactory::getApplication()->input->post->getArray();

		/** @var RedshopModelCart $model */
		$model = $this->getModel('cart');

		$cart = \Redshop\Cart\Cart::modify($model->changeAttribute($post), JFactory::getUser()->id);

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
	 * @throws Exception
	 */
	public function cancel()
	{
		$link = JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . JFactory::getApplication()->input->getInt('Itemid'), false); ?>
		<script language="javascript">
			window.parent.location.href = "<?php echo $link ?>";
		</script>
		<?php
		JFactory::getApplication()->close();
	}

	/**
	 * Get product tax for ajax request
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function ajaxGetProductTax()
	{
		\Redshop\Helper\Ajax::validateAjaxRequest('get');

		$app = JFactory::getApplication();

		$productId    = $app->input->getInt('id', 0);
		$productPrice = $app->input->getFloat('price', 0);
		$userId       = $app->input->getInt('userId', 0);
		$taxExempt    = $app->input->getBool('taxExempt', false);

		$product = new Registry;
		$product->set(
			'tax',
			RedshopHelperProduct::getProductTax(
				$productId,
				$productPrice,
				$userId,
				/** @scrutinizer ignore-type */ $taxExempt
			)
		);

		ob_clean();
		echo $product;

		$app->close();
	}
}
