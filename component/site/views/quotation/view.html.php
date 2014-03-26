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

//require_once( JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );
//require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'helper.php' );

class quotationViewquotation extends JView
{   
   	function display ($tpl=null)
   	{  
   		global $mainframe;
   		
   		$redconfig = new Redconfiguration();
   		$uri 	= &JFactory::getURI();
   		
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$session =& JFactory::getSession();		
		$cart = $session->get( 'cart');
		$return = JRequest::getVar('return');
//		if(!DEFAULT_QUOTATION_MODE)
//		{
//			$msg = JText::_('COM_REDSHOP_QUOTAION_MODE_IS_OFF');
//			$mainframe->Redirect ( 'index.php?option='.$option.'&view=cart&Itemid='.$Itemid,$msg);
//		}
//   		$user=JFactory::getUser();
//	   	if(!$user->id) 
//	   	{
//	   			$tpl='user';
	   			//$mainframe->Redirect ( 'index.php?option='.$option.'&view=checkout&Itemid='.$Itemid);
//		} 
		if(!$return){
	   		if($cart['idx']<1)
			{
				$mainframe->Redirect ( 'index.php?option='.$option.'&view=cart&Itemid='.$Itemid);
			}
		}
		JHTML::Script('validation.js', 'administrator/components/com_redshop/assets/js/',false);
		
   		$model = $this->getModel('quotation');
   		$detail = $model->getData();//UserAccountInfo();
   		
   		$this->assignRef('detail',	$detail);
   		$this->assignRef('request_url',	$uri->toString());
   		
		parent::display($tpl);
  	}
}