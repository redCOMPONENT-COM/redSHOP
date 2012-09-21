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
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );
//$language =& JFactory::getLanguage();	 
class checkoutViewcheckout extends JView
{
   	function display ($tpl=null)
   	{
   		global $mainframe;
		$model = $this->getModel('checkout');
   		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$task = JRequest::getVar('task');
		$user=JFactory::getUser();
		$redhelper = new redhelper();
		$field = new extraField();
		$session =& JFactory::getSession();

   		// Load language file
   		$payment_lang_list= $redhelper->getPlugins("redshop_payment");
   		$language =& JFactory::getLanguage();
   		$base_dir =  JPATH_ADMINISTRATOR;
   		$language_tag = $language->getTag();
   		
   		
   		for($l=0;$l<count($payment_lang_list);$l++)
   		{
			$extension = 'plg_redshop_payment_'.$payment_lang_list[$l]->element;
			$language->load($extension, $base_dir, $language_tag, true);
   		}
   		//End

        JHTML::Script('joomla.javascript.js', 'includes/js/',false);
        JHTML::Script('validate.js', 'media/system/js/',false);
        JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/',false);
        JHTML::Script('jquery.validate.js', 'components/com_redshop/assets/js/',false);
        JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
        JHTML::Script('jquery.metadata.js', 'components/com_redshop/assets/js/',false);
        JHTML::Script('registration.js', 'components/com_redshop/assets/js/',false);
        JHTML::Stylesheet('validation.css', 'components/com_redshop/assets/css/');
        
   	 	if(JPluginHelper::isEnabled('redshop_veis_registration','rs_veis_registration'))
        {
        	JHTML::Script('veis.js', 'plugins/redshop_veis_registration/rs_veis_registration/js/',false);
        }

   		$cart = $session->get( 'cart') ;
   		$auth = $session->get( 'auth') ;

		if(!is_array($auth))
		{
			 $auth['users_info_id'] = 0;
	 		 $session->set('auth',$auth);
			 $auth = $session->get( 'auth') ;
		}
		if( $cart['idx'] < 1 ) 
		{
		 	$msg =  JText::_('COM_REDSHOP_EMPTY_CART' );
		 	$link = 'index.php?option='.$option.'&Itemid='.$Itemid;
			$mainframe->Redirect( $link , $msg );
		}

		if($task !='')
		{
			$tpl = $task;
		}
		else
		{
			if($user->id || @$auth['users_info_id']> 0)
			{
				$cart = $session->get( 'cart') ;

				if(DEFAULT_QUOTATION_MODE==1 && !array_key_exists("quotation_id",$cart))
				{
					$mainframe->Redirect ( 'index.php?option='.$option.'&view=quotation&Itemid='.$Itemid);
				}

				$users_info_id  = JRequest::getInt('users_info_id');
				$billingaddresses = $model->billingaddresses();
				$shippingaddresses = $model->shippingaddresses();

				if(!$users_info_id){
					if((! isset($users_info_id) || $users_info_id== 0) && count($shippingaddresses)>0 )
					{
					 	$users_info_id  =	$shippingaddresses[0]->users_info_id;
					}else if((!isset($users_info_id) || $users_info_id == 0) && count($billingaddresses)>0 )
					{
					 	$users_info_id  =	$billingaddresses->users_info_id;
					}
					else
					{
//						$msg =  JText::_('COM_REDSHOP_LOGIN_USER_IS_NOT_REDSHOP_USER' );
						$mainframe->Redirect( "index.php?option=".$option."&view=account_billto&Itemid=".$Itemid );
					}
				}

				$shipping_rate_id  = JRequest::getVar('shipping_rate_id');
				$element  = JRequest::getVar('payment_method_id');
				$ccinfo  = JRequest::getVar('ccinfo');

				$total_discount = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
				$subtotal 		= (SHIPPING_AFTER == 'total') ? $cart['product_subtotal']-$total_discount : $cart['product_subtotal'];

				$this->assignRef('users_info_id',$users_info_id);
				$this->assignRef('shipping_rate_id',$shipping_rate_id);
				$this->assignRef('element',$element);
				$this->assignRef('ccinfo',$ccinfo);
				$this->assignRef('order_subtotal',$subtotal);
				$this->assignRef('ordertotal',$cart['total']);
			}
			else
			{
				$lists['extra_field_user']=$field->list_all_field(7);  // field_section 6 : Customer Registration
				$lists['extra_field_company']=$field->list_all_field(8);  // field_section 6 : Company Address
				$lists['shipping_customer_field'] = $field->list_all_field(14,0,'billingRequired valid');
				$lists['shipping_company_field'] = $field->list_all_field(15,0,'billingRequired valid');
			}
		}

   		if(($user->id || @$auth['users_info_id']> 0) && ONESTEP_CHECKOUT_ENABLE)
		{
			$this->setLayout('onestepcheckout');
		}

   		$this->assignRef('lists',$lists);
   		parent::display($tpl);
  	}
}
