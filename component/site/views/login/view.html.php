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

class loginViewlogin extends JView
{ 
   	function display ($tpl=null)
   	{   
   		global $mainframe;
   		$user =& JFactory::getUser();
   		
   		$params = &$mainframe->getParams('com_redshop');
		
   		$model = $this->getModel();
   		
   		$shoppergroupid = JRequest::getInt('protalid',0);
			
    	$ShopperGroupDetail = $model->ShopperGroupDetail($shoppergroupid);
    	
    	$layout = JRequest::getVar('layout','');    	
    	
    	$user =& JFactory::getUser();
    	
    	$check = $model->CheckShopperGroup($user->username,$shoppergroupid);
    	
    	if ($layout == 'portal' || PORTAL_SHOP == 1){

    		isset($ShopperGroupDetail[0]->shopper_group_portal)? $portal = $ShopperGroupDetail[0]->shopper_group_portal : $portal = 0; 
    		
    		if ($portal == 1 || PORTAL_SHOP == 1){
    			    		
	    		if ($user->id!=""){    		
	    			$this->setLayout('portals');
	    		}else{ 
	    			$this->setLayout('portal');    			
	    		}
    		}else {
    			$mainframe->enqueuemessage(JText::_('SHOPPER_GROUP_PORTAL_IS_DISABLE'));
    			$mainframe->Redirect('index.php?option=com_redshop');	
    		}
    		
    	}else{
   		
	   		if($user->id!="")
	   		{
	   			$this->setLayout('logout');
	   		}
    	}
    	  	
    	$this->assignRef('ShopperGroupDetail',$ShopperGroupDetail);
    	$this->assignRef('check',$check);		
   		parent::display($tpl);
  	}
}