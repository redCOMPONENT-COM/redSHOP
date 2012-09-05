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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'order.php');
class account_shiptoViewaccount_shipto extends JView
{
   	function display ($tpl=null)
   	{
   		global $mainframe;

   		$order_functions = new order_functions();
   		$extra_field = new extraField();//extra_field();

   		$task = JRequest::getVar('task');
   		$user =& JFactory::getUser();
   		$uri =& JFactory::getURI();
	   	// preform security checks
   		$session =& JFactory::getSession();
		$auth = $session->get( 'auth') ;
		$params = &$mainframe->getParams('com_redshop');
   		if($user->id)
		{
			$billingaddresses = $order_functions->getBillingAddress($user->id);
		}
		elseif(isset($auth['users_info_id']) && $auth['users_info_id'])
		{
			$model 	= $this->getModel('account_shipto');
			$billingaddresses = $model->_loadData($auth['users_info_id']);
		}else
		{
			$mainframe->Redirect('index.php?option=com_redshop&view=login&Itemid='.JRequest::getVar('Itemid'));
			exit;
		}

		$prodhelperobj = new producthelper();
   		$prodhelperobj->generateBreadcrumb();

  	 	if($task=='addshipping')
        {
			JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/',false);
			JHTML::Script('jquery.validate.js', 'components/com_redshop/assets/js/',false);
			JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
			JHTML::Script('registration.js', 'components/com_redshop/assets/js/',false);
			JHTML::Stylesheet('validation.css', 'components/com_redshop/assets/css/');
			JHTML::Script('joomla.javascript.js', 'includes/js/',false);

        	$shippingaddresses = & $this->get('Data');
        	if ($shippingaddresses->users_info_id>0 && $shippingaddresses->user_id != $billingaddresses->user_id)
        	{
        		echo JText::_('ALERTNOTAUTH');
			 	return;
        	}
        	$lists['shipping_customer_field'] = $extra_field->list_all_field(14, $shippingaddresses->users_info_id);
			$lists['shipping_company_field'] = $extra_field->list_all_field(15, $shippingaddresses->users_info_id);

			$this->setLayout('form');
        }
        else
        {
        	$shippingaddresses = $order_functions->getShippingAddress($user->id);
   		}

		$this->assignRef('lists',$lists);
		$this->assignRef('shippingaddresses',$shippingaddresses);
		$this->assignRef('billingaddresses',$billingaddresses);
		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('params',$params);

		parent::display($tpl);
  	}
}