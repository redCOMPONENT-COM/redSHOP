<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.controller');

include_once JPATH_COMPONENT . '/helpers/helper.php';
include_once JPATH_COMPONENT . '/helpers/cart.php';

/**
 * Cart Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class CartController extends JController
{
	/**
	 * Constructor
	 *
	 * @param   array  $default  config array
	 */
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->_carthelper = new rsCarthelper;
	}

	/**
	 * Method to add product in cart
	 *
	 * @return void
	 */
	public function add()
	{
		$app                        = JFactory::getApplication();
		$option                     = JRequest::getVar('option');
		$post                       = JRequest::get('post');
		$parent_accessory_productid = $post['product_id'];
		$Itemid                     = JRequest::getVar('Itemid');
		$producthelper              = new producthelper;
		$redhelper                  = new redhelper;
		$Itemid                     = $redhelper->getCartItemid($Itemid);
		$model                      = $this->getModel('cart');

		// Call add method of modal to store product in cart session
		$userfiled = JRequest::getVar('userfiled');

		$result = $this->_carthelper->addProductToCart($post);

		if (is_bool($result) && $result)
		{
		}
		else
		{
			$errmsg = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

			if (AJAX_CART_BOX == 1)
			{
				echo "`0`" . $errmsg;
				die();
			}
			else
			{
				$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['product_id']);

				if (count($ItemData) > 0)
				{
					$prdItemid = $ItemData->id;
				}
				else
				{
					$prdItemid = $redhelper->getItemid($post['product_id']);
				}

				$link = JRoute::_("index.php?option=" . $option . "&view=product&pid=" . $post["product_id"] . "&Itemid=" . $prdItemid, false);
				$app->Redirect($link, $errmsg);
			}
		}

		$session = JFactory::getSession();
		$cart    = $session->get('cart');

		if (isset($cart['AccessoryAsProduct']))
		{
			$attArr = $cart['AccessoryAsProduct'];

			if (ACCESSORY_AS_PRODUCT_IN_CART_ENABLE)
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

					for ($i = 0; $i < count($accessory_data); $i++)
					{
						$accessory = $producthelper->getProductAccessory($accessory_data[$i]);
						$post = array();
						$post['parent_accessory_product_id'] = $parent_accessory_productid;
						$post['product_id']                  = $accessory[0]->child_product_id;
						$post['quantity']                    = $acc_quantity_data[$i];
						$post['category_id']                 = 0;
						$post['sel_wrapper_id']              = 0;
						$post['attribute_data']              = $acc_attribute_data[$i];
						$post['property_data']               = $acc_property_data[$i];
						$post['subproperty_data']            = $acc_subproperty_data[$i];

						$result = $this->_carthelper->addProductToCart($post);

						$cart = $session->get('cart');

						if (is_bool($result) && $result)
						{
						}
						else
						{
							$errmsg = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");

							if (JError::isError(JError::getError()))
							{
								$error  = JError::getError();
								$errmsg = $error->message;
							}

							if (AJAX_CART_BOX == 1)
							{
								echo "`0`" . $errmsg;
								die();
							}
							else
							{
								$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['product_id']);

								if (count($ItemData) > 0)
								{
									$prdItemid = $ItemData->id;
								}
								else
								{
									$prdItemid = $redhelper->getItemid($post['product_id']);
								}

								$link = JRoute::_("index.php?option=" . $option . "&view=product&pid=" . $post["product_id"] . "&Itemid=" . $prdItemid, false);
								$app->Redirect($link, $errmsg);
							}
						}
					}
				}
			}

			if (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
			{
				$this->_carthelper->carttodb();
			}

			$this->_carthelper->cartFinalCalculation();
			unset($cart['AccessoryAsProduct']);
		}
		else
		{
			if (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
			{
				$this->_carthelper->carttodb();
			}

			$this->_carthelper->cartFinalCalculation();
		}

		if (!$userfiled)
		{
			if (AJAX_CART_BOX == 1 && isset($post['ajax_cart_box']))
			{
				$link = JRoute::_('index.php?option=' . $option . '&view=cart&ajax_cart_box=' . $post['ajax_cart_box'] . '&tmpl=component&Itemid=' . $Itemid, false);
				$app->Redirect($link);
			}
			else
			{
				if (ADDTOCART_BEHAVIOUR == 1)
				{
					$link = JRoute::_('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, false);
					$app->Redirect($link);
				}
				else
				{
					$link = JRoute::_($_SERVER['HTTP_REFERER'], false);

					if ($cart['notice_message'] != "")
					{
						$msg = $cart['notice_message'] . "<br>";
					}

					$msg .= JTEXT::_('COM_REDSHOP_PRODUCT_ADDED_TO_CART');
					$app->Redirect($link, $msg);
				}
			}
		}
		else
		{
			$link = JRoute::_('index.php?option=' . $option . '&view=product&pid=' . $post['p_id'] . '&Itemid=' . $Itemid, false);
			$app->Redirect($link);
		}
	}

	public function modifyCalculation($cart)
	{
		$producthelper            = new producthelper;
		$calArr                   = $this->_carthelper->calculation($cart);
		$cart['product_subtotal'] = $calArr[1];
		$session                  = JFactory::getSession();
		$discount_amount          = 0;
		$voucherDiscount          = 0;
		$couponDiscount           = 0;
		$discount_excl_vat        = 0;
		$totaldiscount            = 0;

		if (DISCOUNT_ENABLE == 1)
		{
			$discount_amount = $producthelper->getDiscountAmount($cart);
		}

		$cart['cart_discount'] = $discount_amount;

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
		$codeDsicount = $voucherDiscount + $couponDiscount;
		$totaldiscount = $cart['cart_discount'] + $codeDsicount;

		$calArr = $this->_carthelper->calculation($cart);

		$tax = $calArr[5];
		$Discountvat = 0;
		$chktag = $producthelper->taxexempt_addtocart();

		if (APPLY_VAT_ON_DISCOUNT && VAT_RATE_AFTER_DISCOUNT && !empty($chktag))
		{
			$Discountvat = (VAT_RATE_AFTER_DISCOUNT * $totaldiscount) / (1 + VAT_RATE_AFTER_DISCOUNT);
		}

		$cart['total'] = $calArr[0] - $totaldiscount;
		$cart['subtotal'] = $calArr[1] + $calArr[3] - $totaldiscount;
		$cart['subtotal_excl_vat'] = $calArr[2] + ($calArr[3] - $calArr[6]) - ($totaldiscount - $Discountvat);

		if ($cart['total'] <= 0)
		{
			$cart['subtotal_excl_vat'] = 0;
		}

		$cart['product_subtotal']          = $calArr[1];
		$cart['product_subtotal_excl_vat'] = $calArr[2];
		$cart['shipping']                  = $calArr[3];
		$cart['tax']                       = $tax;
		$cart['sub_total_vat']             = $tax + $calArr[6];
		$cart['discount_vat']              = $Discountvat;
		$cart['shipping_tax']              = $calArr[6];
		$cart['discount_ex_vat']           = $totaldiscount - $Discountvat;
		$cart['mod_cart_total']            = $this->_carthelper->GetCartModuleCalc($cart);
		$session->set('cart', $cart);

		return $cart;
	}

	/**
	 * Method to add coupon code in cart for discount
	 *
	 * @return void
	 */
	public function coupon()
	{
		$session   = JFactory::getSession();
		$option    = JRequest::getVar('option');
		$post      = JRequest::get('post');
		$Itemid    = JRequest::getVar('Itemid');
		$redhelper = new redhelper;
		$Itemid    = $redhelper->getCartItemid($Itemid);
		$model     = $this->getModel('cart');

		// Call coupon method of model to apply coupon
		$valid = $model->coupon();
		$cart  = $session->get('cart');
		$this->modifyCalculation($cart);
		$this->_carthelper->cartFinalCalculation(false);

		// Store cart entry in db
		$this->_carthelper->carttodb();

		// If coupon code is valid than apply to cart else raise error
		if ($valid)
		{
			$link = JRoute::_('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, false);
			$this->setRedirect($link);
		}
		else
		{
			$msg  = JText::_('COM_REDSHOP_COUPON_CODE_IS_NOT_VALID');
			$link = JRoute::_('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, false);
			$this->setRedirect($link, $msg);
		}
	}

	/**
	 * Method to add voucher code in cart for discount
	 *
	 * @return void
	 */
	public function voucher()
	{
		$session   = JFactory::getSession();
		$option    = JRequest::getVar('option');
		$post      = JRequest::get('post');
		$Itemid    = JRequest::getVar('Itemid');
		$redhelper = new redhelper;
		$Itemid    = $redhelper->getCartItemid($Itemid);
		$model     = $this->getModel('cart');

		// Call voucher method of model to apply voucher to cart
		$valid = $model->voucher();
		/*
		 *  if voucher code is valid than apply to cart else raise error
		 */
		if ($valid)
		{
			$cart = $session->get('cart');
			$this->modifyCalculation($cart);
			$this->_carthelper->cartFinalCalculation(false);
			$link = JRoute::_('index.php?option=' . $option . '&view=cart&seldiscount=voucher&Itemid=' . $Itemid, false);
			$this->setRedirect($link);
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_VOUCHER_CODE_IS_NOT_VALID');

			$link = JRoute::_('index.php?option=' . $option . '&view=cart&msg=' . $msg . '&seldiscount=voucher&Itemid=' . $Itemid, false);
			$this->setRedirect($link, $msg);
		}
	}

	/**
	 * Method to update product info in cart
	 *
	 * @return void
	 */
	public function update()
	{
		$option    = JRequest::getVar('option');
		$post      = JRequest::get('post');
		$Itemid    = JRequest::getVar('Itemid');
		$redhelper = new redhelper;
		$Itemid    = $redhelper->getCartItemid($Itemid);
		$model     = $this->getModel('cart');

		// Call update method of model to update product info of cart
		$model->update($post);
		$this->_carthelper->cartFinalCalculation();
		$this->_carthelper->carttodb();
		$link = JRoute::_('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, false);
		$this->setRedirect($link);
	}

	/**
	 * Method to update all product info in cart
	 *
	 * @return void
	 */
	public function update_all()
	{
		$option    = JRequest::getVar('option');
		$post      = JRequest::get('post');
		$Itemid    = JRequest::getVar('Itemid');
		$redhelper = new redhelper;
		$Itemid    = $redhelper->getCartItemid($Itemid);
		$model     = $this->getModel('cart');

		// Call update_all method of model to update all products info of cart
		$model->update_all($post);
		$this->_carthelper->cartFinalCalculation();
		$this->_carthelper->carttodb();
		$link = JRoute::_('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, false);
		$this->setRedirect($link);
	}

	/**
	 * Method to make cart empty
	 *
	 * @return void
	 */
	public function empty_cart()
	{
		$option    = JRequest::getVar('option');
		$Itemid    = JRequest::getVar('Itemid');
		$redhelper = new redhelper;
		$Itemid    = $redhelper->getCartItemid($Itemid);
		$model     = $this->getModel('cart');

		// Call empty_cart method of model to remove all products from cart
		$model->empty_cart();
		$user = JFactory::getUser();

		if ($user->id)
		{
			$this->_carthelper->removecartfromdb(0, $user->id, true);
		}

		$link = JRoute::_('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, false);
		$this->setRedirect($link);
	}

	/**
	 * Method to delete cart entry from session
	 *
	 * @return void
	 */
	public function delete()
	{
		$option      = JRequest::getVar('option');
		$post        = JRequest::get('post');
		$cartElement = $post['cart_index'];
		$Itemid      = JRequest::getVar('Itemid');
		$redhelper   = new redhelper;
		$Itemid      = $redhelper->getCartItemid($Itemid);
		$model       = $this->getModel('cart');

		$model->delete($cartElement);
		$this->_carthelper->cartFinalCalculation();
		$this->_carthelper->carttodb();
		$link = JRoute::_('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, false);
		$this->setRedirect($link);
	}

	/**
	 * discount calculator Ajax Function
	 *
	 * @return discount by Ajax
	 */
	public function discountCalculator()
	{
		ob_clean();
		$get = JRequest::get('GET');
		$this->_carthelper->discountCalculator($get);
		exit;
	}

	/**
	 * Method to add multiple products by its product number using mod_redmasscart module.
	 *
	 * @return void
	 */
	public function redmasscart()
	{
		$app    = JFactory::getApplication();
		$option = JRequest::getVar('option');
		$post   = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');

		if ($post["numbercart"] == "")
		{
			$msg  = JText::_('COM_REDSHOP_PLEASE_ENTER_PRODUCT_NUMBER');
			$rurl = base64_decode($post["rurl"]);
			$app->redirect($rurl, $msg);
		}

		$model = $this->getModel('cart');
		$model->redmasscart($post);

		$link = JRoute::_('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, false);
		$this->setRedirect($link);
	}

	/**
	 *  Get Shipping rate function
	 *
	 * @return shipping rate by Ajax
	 */
	public function getShippingrate()
	{
		include_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/shipping.php';
		$shipping = new shipping;
		echo $shipping->getShippingrate_calc();
		exit;
	}

	/**
	 * change Attribute
	 *
	 * @return void
	 */
	public function changeAttribute()
	{
		$post    = JRequest::get('post');
		$model   = $this->getModel('cart');
		$user    = JFactory::getUser();
		$user_id = $user->id;

		$cart = $model->changeAttribute($post);
		$cart = $this->_carthelper->modifyCart($cart, $user_id);

		$session = JFactory::getSession();
		$session->set('cart', $cart);
		$this->_carthelper->cartFinalCalculation();

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
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');

		$link = JRoute::_('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid, false);    ?>
		<script language="javascript">
			window.parent.location.href = "<?php echo $link ?>";
		</script>
		<?php    exit;
	}
}
