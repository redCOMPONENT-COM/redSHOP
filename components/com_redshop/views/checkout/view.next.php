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
defined('_JEXEC') or die ('restricted access');


jimport('joomla.application.component.view');

require_once(JPATH_COMPONENT.DS.'helpers'.DS.'product.php' );
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'order.php' );
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'shipping.php' );

class checkoutViewcheckout extends JView
{

   	function display ($tpl=null)
   	{
   		global $mainframe;
   		$shippinghelper = new shipping();
   		$order_functions = new order_functions();

		$params	= &$mainframe->getParams('com_redshop');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$issplit = JRequest::getVar('issplit');
		$ccinfo  = JRequest::getVar('ccinfo');
		$task = JRequest::getVar('task');

   		$model = $this->getModel('checkout');
		$session =& JFactory::getSession();
   		if($issplit!='')
   		{
			$session->set('issplit',$issplit);
		}

		$payment_method_id  = JRequest::getVar('payment_method_id');
		$users_info_id  = JRequest::getInt('users_info_id');
		$auth = $session->get( 'auth') ;
		if(empty($users_info_id ))
		$users_info_id = $auth['users_info_id'];
		$shipping_rate_id  = JRequest::getVar('shipping_rate_id');
   		$shippingdetail = explode ( "|", $shippinghelper->decryptShipping( str_replace(" ","+",$shipping_rate_id) ));
   		if(count($shippingdetail) < 4)
		{
			$shipping_rate_id = "";
		}
	 	$cart = $session->get( 'cart');

		if( $cart['idx'] < 1 )
		{
		 	$msg =  JText::_('COM_REDSHOP_EMPTY_CART' );
			$mainframe->Redirect( 'index.php?option='.$option.'&Itemid='.$Itemid, $msg );
		}
   		if(SHIPPING_METHOD_ENABLE)
		{
			if( $users_info_id < 1 )
			{
				$msg =  JText::_('COM_REDSHOP_SELECT_SHIP_ADDRESS' );
			 	$link = 'index.php?option='.$option.'&view=checkout&Itemid='.$Itemid.'&users_info_id='.$users_info_id.'&shipping_rate_id='.$shipping_rate_id.'&payment_method_id='.$payment_method_id;
				$mainframe->Redirect( $link , $msg );
			}
			if($shipping_rate_id=='' && $cart['free_shipping'] !=1)
			{
				$msg =  JText::_('COM_REDSHOP_SELECT_SHIP_METHOD' );
			 	$link = 'index.php?option='.$option.'&view=checkout&Itemid='.$Itemid.'&users_info_id='.$users_info_id.'&shipping_rate_id='.$shipping_rate_id.'&payment_method_id='.$payment_method_id;
				$mainframe->Redirect( $link , $msg );
			}
		}
		if( $payment_method_id == '' )
		{
			$msg =  JText::_('COM_REDSHOP_SELECT_PAYMENT_METHOD' );
		 	$link = 'index.php?option='.$option.'&view=checkout&Itemid='.$Itemid.'&users_info_id='.$users_info_id.'&shipping_rate_id='.$shipping_rate_id.'&payment_method_id='.$payment_method_id;
			$mainframe->Redirect( $link , $msg );
		}

		$paymentinfo = $order_functions->getPaymentMethodInfo($payment_method_id);
		$paymentinfo = $paymentinfo[0];
		$paymentpath=JPATH_SITE.DS.'plugins'.DS.'redshop_payment'.DS.$paymentinfo->element.DS.$paymentinfo->element.'.xml';
		$paymentparams = new JRegistry( $paymentinfo->params );
		$is_creditcard = $paymentparams->get('is_creditcard','');
		$is_subscription = $paymentparams->get('is_subscription',0);
		if(@$is_creditcard == 1)
		{
			$document =& JFactory::getDocument();
			JHTML::Script('credit_card.js', 'components/com_redshop/assets/js/',false);
		}
		
   		if($is_subscription)
		{
			
			$subscription_id = $session->set( 'subscription_id', $subscription_id) ;
		}
//		if(@$is_creditcard == 1 && $ccinfo == '1')
//		{
//			$_SESSION['ccdata']['order_payment_name'] = JRequest::getVar('order_payment_name');
//			$_SESSION['ccdata']['creditcard_code'] = JRequest::getVar('creditcard_code');
//			$_SESSION['ccdata']['order_payment_number'] = JRequest::getVar('order_payment_number');
//			$_SESSION['ccdata']['order_payment_expire_month'] = JRequest::getVar('order_payment_expire_month');
//			$_SESSION['ccdata']['order_payment_expire_year'] = JRequest::getVar('order_payment_expire_year');
//			$_SESSION['ccdata']['credit_card_code'] = JRequest::getVar('credit_card_code');
//			$validpayment = $model->validatepaymentccinfo();
//			if(!$validpayment[0])
//			{
//				$msg =  $validpayment[1];
//				//$link = 'index.php?option='.$option.'&view=checkout&task=checkoutnext&Itemid='.$Itemid.'&users_info_id='.$users_info_id.'&shipping_rate_id='.$shipping_rate_id.'&payment_method_id='.$payment_method_id;
//				//$mainframe->Redirect( $link , $msg );
//			 }else
//			 {
//			   // $link = 'index.php?option='.$option.'&view=checkout&task=checkoutfinal&Itemid='.$Itemid.'&users_info_id='.$users_info_id.'&shipping_rate_id='.$shipping_rate_id.'&payment_method_id='.$payment_method_id.'&ccinfo='.$ccinfo;
//				//$mainframe->Redirect( $link , $msg );
//			 }
//		}


		$this->assignRef('cart',$cart);
		$this->assignRef('users_info_id',$users_info_id);
		$this->assignRef('shipping_rate_id',$shipping_rate_id);
		$this->assignRef('payment_method_id',$payment_method_id);
		$this->assignRef('is_creditcard',$is_creditcard);

		if($task !='')
		{
			 $tpl = $task;
		}

   		parent::display($tpl);
  	}
}	?>