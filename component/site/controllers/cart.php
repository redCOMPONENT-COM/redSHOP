<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );

include_once (JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');
include_once (JPATH_COMPONENT.DS.'helpers'.DS.'cart.php');
/**
 * Cart Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class cartController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
		$this->_carthelper = new rsCarthelper();
	}

	/**
	 * Method to add product in cart
	 *
	 */
	function add()
	{
		global $mainframe;
		$option = JRequest::getVar('option');
		$post = JRequest::get('post');
		$parent_accessory_productid = $post['product_id'];
		$Itemid = JRequest::getVar('Itemid');
		$producthelper 	= new producthelper();
		$redhelper = new redhelper();
		$Itemid = $redhelper->getCartItemid($Itemid);
		$model = $this->getModel('cart');

		// call add method of modal to store product in cart session
		$userfiled = JRequest::getVar('userfiled');

		$result = $this->_carthelper->addProductToCart($post);
		if(is_bool($result) && $result)
		{}
		else
		{
			$errmsg = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");
			if(AJAX_CART_BOX == 1)
			{
				echo "`0`".$errmsg;
				die();
			}
			else
			{
				$ItemData = $producthelper->getMenuInformation(0,0,'','product&pid='.$post['product_id']);
				if(count($ItemData)>0)
				{
					$prdItemid = $ItemData->id;
				} else {
					$prdItemid = $redhelper->getItemid($post['product_id']);
				}
				$link 	= JRoute::_("index.php?option=".$option."&view=product&pid=".$post["product_id"]."&Itemid=".$prdItemid, false);
				$mainframe->Redirect($link,$errmsg);
			}
		}
//		$model->add($post);
//		if(JError::isError(JError::getError()) && AJAX_CART_BOX == 1)
//		{
//			$error = JError::getError();
//			echo "`0`".$error->message;
//			die();
//		}
		/* store cart entry in db */


		$session =& JFactory::getSession();
		$cart = $session->get('cart');
		if(isset($cart['AccessoryAsProduct']))
		{
			$attArr = $cart['AccessoryAsProduct'];
			if(ACCESSORY_AS_PRODUCT_IN_CART_ENABLE)
			{
				$data['accessory_data'] = $attArr[0];
				$data['acc_quantity_data'] = $attArr[1];
				$data['acc_attribute_data'] = $attArr[2];
				$data['acc_property_data'] = $attArr[3];
				$data['acc_subproperty_data'] = $attArr[4];

				if(isset($data['accessory_data']) && ($data['accessory_data']!="" && $data['accessory_data']!=0))
				{
					$accessory_data = explode("@@",$data['accessory_data']);
					$acc_quantity_data = explode("@@",$data['acc_quantity_data']);
					$acc_attribute_data = explode("@@",$data['acc_attribute_data']);
					$acc_property_data = explode("@@",$data['acc_property_data']);
					$acc_subproperty_data = explode("@@",$data['acc_subproperty_data']);
					for($i=0;$i < count($accessory_data);$i++)
					{
						$accessory = $producthelper->getProductAccessory($accessory_data[$i]);
						$post = array();
						$post['parent_accessory_product_id'] = $parent_accessory_productid;
						$post['product_id'] = $accessory[0]->child_product_id;
						$post['quantity'] = $acc_quantity_data[$i];
						$post['category_id'] = 0;
						$post['sel_wrapper_id'] = 0;
						$post['attribute_data'] = $acc_attribute_data[$i];
						$post['property_data'] = $acc_property_data[$i];
						$post['subproperty_data'] = $acc_subproperty_data[$i];

						$result = $this->_carthelper->addProductToCart($post);
//						$model->add($post);
						$cart = $session->get('cart');
						if(is_bool($result) && $result)
						{}
						else
						{
							$errmsg = ($result) ? $result : JText::_("COM_REDSHOP_PRODUCT_NOT_ADDED_TO_CART");
							if(JError::isError(JError::getError()))
							{
								$error = JError::getError();
								$errmsg = $error->message;
							}
							if(AJAX_CART_BOX == 1)
							{
								echo "`0`".$errmsg;
								die();
							}
							else
							{
								$ItemData = $producthelper->getMenuInformation(0,0,'','product&pid='.$post['product_id']);
								if(count($ItemData)>0)
								{
									$prdItemid = $ItemData->id;
								} else {
									$prdItemid = $redhelper->getItemid($post['product_id']);
								}
								$link 	= JRoute::_("index.php?option=".$option."&view=product&pid=".$post["product_id"]."&Itemid=".$prdItemid, false);
								$mainframe->Redirect($link,$errmsg);
							}
						}
					}
				}
			}
			if(!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
			{
				$this->_carthelper->carttodb();
			}
			$this->_carthelper->cartFinalCalculation();
			unset($cart['AccessoryAsProduct']);
		}
		else
		{
			if(!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
			{
				$this->_carthelper->carttodb();
			}
			$this->_carthelper->cartFinalCalculation();
		}

		if(!$userfiled)
		{
			if(AJAX_CART_BOX == 1 && isset($post['ajax_cart_box']))
			{
				$link 	= JRoute::_('index.php?option='.$option.'&view=cart&ajax_cart_box='.$post['ajax_cart_box'].'&tmpl=component&Itemid='.$Itemid, false);
				$mainframe->Redirect($link);
			}
			else
			{
				if(ADDTOCART_BEHAVIOUR == 1)
				{
					$link 	= JRoute::_('index.php?option='.$option.'&view=cart&Itemid='.$Itemid, false);
					$mainframe->Redirect($link);
				}
				else
				{

					$link 	= JRoute::_($_SERVER['HTTP_REFERER'], false);
					if($cart['notice_message']!="")
					{
						$msg = $cart['notice_message']."<br>";
					}
					$msg .= JTEXT::_('COM_REDSHOP_PRODUCT_ADDED_TO_CART');
					$mainframe->Redirect($link,$msg);
 				}
			}
		}
		else
		{

			$link 	= JRoute::_( 'index.php?option='.$option.'&view=product&pid='.$post['p_id'].'&Itemid='.$Itemid, false);
			$mainframe->Redirect($link);
		}
	}
	function modifyCalculation($cart)
	{
		$producthelper = new producthelper();
		$calArr = $this->_carthelper->calculation($cart);
		$cart['product_subtotal'] 			= $calArr[1];
		$session =& JFactory::getSession();
		$discount_amount 	= 0;
		$voucherDiscount	= 0;
		$couponDiscount  	= 0;
		$discount_excl_vat 	= 0;

		$totaldiscount=0;
		if ( DISCOUNT_ENABLE == 1 ) {
			$discount_amount = $producthelper->getDiscountAmount($cart);
		}
		$cart['cart_discount'] 		= $discount_amount;
		if(array_key_exists('voucher',$cart))
		{
			$voucherDiscount = $this->_carthelper->calculateDiscount('voucher',$cart['voucher']);
		}
		$cart['voucher_discount'] = $voucherDiscount;

		if(array_key_exists('coupon',$cart))
		{
			$couponDiscount = $this->_carthelper->calculateDiscount('coupon',$cart['coupon']);
		}
		$cart['coupon_discount'] = $couponDiscount;
		$codeDsicount 	= $voucherDiscount + $couponDiscount;
		$totaldiscount 	= $cart['cart_discount'] + $codeDsicount;

		$calArr 		= $this->_carthelper->calculation($cart);

		$tax = $calArr[5];
		$Discountvat = 0;
		$chktag = $producthelper->taxexempt_addtocart();

		if(APPLY_VAT_ON_DISCOUNT && VAT_RATE_AFTER_DISCOUNT && !empty($chktag))
		{
			$Discountvat = (VAT_RATE_AFTER_DISCOUNT * $totaldiscount)/(1+VAT_RATE_AFTER_DISCOUNT);
			$tax = $tax - $Discountvat;
		}

		$cart['total'] 						= $calArr[0] - $totaldiscount;
		$cart['subtotal'] 					= $calArr[1] + $calArr[3] - $totaldiscount;
		$cart['subtotal_excl_vat']  		= $calArr[2] + ($calArr[3] - $calArr[6]) - ($totaldiscount - $Discountvat);
		if($cart['total']<=0)
		{
			$cart['subtotal_excl_vat']  		= 0;
		}

		$cart['product_subtotal']			= $calArr[1];
		$cart['product_subtotal_excl_vat']	= $calArr[2];
		$cart['shipping']  					= $calArr[3];
		$cart['tax'] 						= $tax;
		$cart['sub_total_vat'] 				= $tax + $calArr[6];
		$cart['discount_vat'] 				= $Discountvat;
		$cart['shipping_tax']				= $calArr[6];
		$cart['discount_ex_vat']			= $totaldiscount - $Discountvat;
		$mod_cart_total = $this->_carthelper->GetCartModuleCalc($cart);
		$cart['mod_cart_total'] 	= $mod_cart_total;
		$session->set( 'cart',$cart );

		return $cart;

	}

	/**
	 * Method to add coupon code in cart for discount
	 *
	 */
	function coupon()
	{
		$session =& JFactory::getSession();
		$option = JRequest::getVar('option');
		$post = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');
		$redhelper = new redhelper();
		$Itemid = $redhelper->getCartItemid($Itemid);
		$model = $this->getModel('cart');

		// call coupon method of model to apply coupon
		$valid=$model->coupon();
		$cart =$session->get('cart');
		$this->modifyCalculation($cart);
		$this->_carthelper->cartFinalCalculation(false);

		/* store cart entry in db */
		$this->_carthelper->carttodb();
		/*
		 *  if coupon code is valid than apply to cart else raise error
		 */

		if($valid){

			$link 	= JRoute::_('index.php?option='.$option.'&view=cart&Itemid='.$Itemid, false);
			$this->setRedirect($link);
		}else{

			$msg=JText::_('COM_REDSHOP_COUPON_CODE_IS_NOT_VALID' );

			$link 	= JRoute::_('index.php?option='.$option.'&view=cart&Itemid='.$Itemid, false);
			$this->setRedirect($link, $msg);
		}
	}
	/**
	 * Method to add voucher code in cart for discount
	 *
	 */
	function voucher()
	{
		$session =& JFactory::getSession();
		$option = JRequest::getVar('option');
		$post = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');
		$redhelper = new redhelper();
		$Itemid = $redhelper->getCartItemid($Itemid);

		$model = $this->getModel('cart');

		// call voucher method of model to apply voucher to cart
		$valid=$model->voucher();
		/*
		 *  if voucher code is valid than apply to cart else raise error
		 */
		if($valid){
			$cart =$session->get('cart');
			$this->modifyCalculation($cart);
			$this->_carthelper->cartFinalCalculation(false);
			$link 	= JRoute::_('index.php?option='.$option.'&view=cart&seldiscount=voucher&Itemid='.$Itemid, false);
			$this->setRedirect($link);
		}else{
			$msg=JText::_('COM_REDSHOP_VOUCHER_CODE_IS_NOT_VALID' );

			$link 	= JRoute::_('index.php?option='.$option.'&view=cart&msg='.$msg.'&seldiscount=voucher&Itemid='.$Itemid, false);
			$this->setRedirect($link, $msg);
		}
	}
	/**
	 * Method to update product info in cart
	 *
	 */
	function update(){

		$option = JRequest::getVar('option');
		$post = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');
		$redhelper = new redhelper();
		$Itemid = $redhelper->getCartItemid($Itemid);
		$model = $this->getModel('cart');

		// call update method of model to update product info of cart
		$model->update($post);
		$this->_carthelper->cartFinalCalculation();
		$this->_carthelper->carttodb();
		$link 	= JRoute::_('index.php?option='.$option.'&view=cart&Itemid='.$Itemid, false);
		$this->setRedirect($link);
	}
	/**
	 * Method to update all product info in cart
	 *
	 */
	function update_all(){

		$option = JRequest::getVar('option');
		$post = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');
		$redhelper = new redhelper();
		$Itemid = $redhelper->getCartItemid($Itemid);
		$model = $this->getModel('cart');

		// call update_all method of model to update all products info of cart
		$model->update_all($post);
		$this->_carthelper->cartFinalCalculation();
		$this->_carthelper->carttodb();
		$link 	= JRoute::_('index.php?option='.$option.'&view=cart&Itemid='.$Itemid, false);
		$this->setRedirect($link);
	}
	/**
	 * Method to make cart empty
	 *
	 */
	function empty_cart(){

		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$redhelper = new redhelper();
		$Itemid = $redhelper->getCartItemid($Itemid);
		$model = $this->getModel('cart');

		// call empty_cart method of model to remove all products from cart
		$model->empty_cart();
		$user = JFactory :: getUser();
		if($user->id)
			$this->_carthelper->removecartfromdb(0,$user->id,true);

		$link 	= JRoute::_('index.php?option='.$option.'&view=cart&Itemid='.$Itemid, false);
		$this->setRedirect($link);
	}
	/**
	 * Method to delete cart entry from session
	 *
	 */
	function delete(){

		$option = JRequest::getVar('option');
		$post = JRequest::get('post');
		$cartElement = $post['cart_index'];
		$Itemid = JRequest::getVar('Itemid');
		$redhelper = new redhelper();
		$Itemid = $redhelper->getCartItemid($Itemid);
		$model = $this->getModel('cart');

		$model->delete($cartElement);
		$this->_carthelper->cartFinalCalculation();
		$this->_carthelper->carttodb();
		$link 	= JRoute::_('index.php?option='.$option.'&view=cart&Itemid='.$Itemid, false);
		$this->setRedirect($link);
	}

	/*
	 * discount calculaor Ajax Function
	 *
	 * @return: ajax responce
	 */
	function discountCalculator()
	{
		ob_clean();
		$get = JRequest::get('GET');
		$this->_carthelper->discountCalculator($get);
		exit;
	}
	/**
	 * Method to add multiple products by its product number
	 * using mod_redmasscart module.
	 *
	 */
	function redmasscart(){

		global $mainframe;
		$option = JRequest::getVar('option');
		$post = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');
		if($post["numbercart"] == "")
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_PRODUCT_NUMBER');
			$rurl = base64_decode($post["rurl"]);
			$mainframe->redirect($rurl,$msg);
		}
		$model = $this->getModel('cart');
		$model->redmasscart($post);

		$link 	= JRoute::_('index.php?option='.$option.'&view=cart&Itemid='.$Itemid, false);
		$this->setRedirect($link);
	}
	/*
	 *  cart Calculation
	 * @set: total,subtotal,discount
	 */

	/*
	 *  Get Shipping rate function
	 * @return: shipping rate by Ajax
	 */
	function getShippingrate(){
		include_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'shipping.php');
		$shipping = new shipping();
		echo $shipping->getShippingrate_calc();
		exit;
	}

	/*
	 * change Attribute
	 */
	function changeAttribute()
	{
		$post = JRequest::get('post');
		$model = $this->getModel('cart');
		$user = &JFactory::getUser();
		$user_id = $user->id;

		$cart =  $model->changeAttribute($post);
		$cart = $this->_carthelper->modifyCart($cart,$user_id);

		$session =& JFactory::getSession();
		$session->set( 'cart',$cart );
		$this->_carthelper->cartFinalCalculation();		?>

		<script type="text/javascript">
		window.parent.location.reload();
		</script>
<?php
	}

	/**
	 * Method called when user pressed cancel button
	 *
	 */
	function cancel()
	{
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');

		$link = JRoute::_('index.php?option='.$option.'&view=cart&Itemid='.$Itemid,false);	?>
		<script language="javascript">
			window.parent.location.href="<?php echo $link ?>";
		</script>
<?php	exit;
	}
}?>
