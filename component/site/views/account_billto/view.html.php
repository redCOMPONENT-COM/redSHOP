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

require_once( JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );
class account_billtoViewaccount_billto extends JView
{
   	function display ($tpl=null)
   	{
   		global $mainframe;
   		$extra_field = new extraField();

   		$params 	= &$mainframe->getParams('com_redshop');

   		$billingaddresses = $GLOBALS['billingaddresses'];
   		if(count($billingaddresses)<=0)
   		{
   			$model 	= $this->getModel('account_billto');
   			$billingaddresses = $model->_initData();
   		}
   		$user =& JFactory::getUser();
   		$uri =& JFactory::getURI();
   		$session =& JFactory::getSession();
   		$auth = $session->get( 'auth') ;
		if(!is_array($auth))
		{
			 $auth['users_info_id'] = 0;
	 		 $session->set('auth',$auth);
			 $auth = $session->get( 'auth') ;
		}

		JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('jquery.validate.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/',false);
		JHTML::Script('registration.js', 'components/com_redshop/assets/js/',false);
		JHTML::Stylesheet('validation.css', 'components/com_redshop/assets/css/');
   		// preform security checks
		if ($user->id==0 && $auth['users_info_id']==0)
		{
			$mainframe->Redirect('index.php?option=com_redshop&view=login&Itemid='.JRequest::getVar('Itemid'));
			exit;
		}

		$lists['requesting_tax_exempt'] 		= JHTML::_('select.booleanlist',  'requesting_tax_exempt', 'class="inputbox"', @$billingaddresses->requesting_tax_exempt );
		$lists['extra_field_user'] = $extra_field->list_all_field(7, @$billingaddresses->users_info_id); /// field_section 7 :Customer Address
		$lists['extra_field_company'] = $extra_field->list_all_field(8, @$billingaddresses->users_info_id);

		$this->assignRef('lists',$lists);
	 	$this->assignRef('billingaddresses',$billingaddresses);
		$this->assignRef('request_url',	$uri->toString());
		$this->assignRef('params',$params);
   		parent::display($tpl);
  	}
}