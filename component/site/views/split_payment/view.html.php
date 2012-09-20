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

class split_paymentViewsplit_payment extends JView
{ 
   	function display ($tpl=null)
   	{ 
   		global $mainframe;

   		$params = &$mainframe->getParams('com_redshop');

   		$pathway = &$mainframe->getPathway();
		$document = &JFactory::getDocument();
		
   		$pathway->addItem(JText::_('SPLIT_PAYMENT'),'');
   		
		$userdata = JRequest::getVar ('userdata');
   		$user =& JFactory::getUser();
   		
   		// preform security checks
		if ($user->id==0) 
		{
			echo JText::_('ALERTNOTAUTH_ACCOUNT');
			return;
		}
   		
		$this->assignRef('user',$user);
		$this->assignRef('userdata',$userdata);
		$this->assignRef('params',$params);
		$payment_method_id  = JRequest::getVar('payment_method_id');
		$this->assignRef('payment_method_id',$payment_method_id);
		
   		parent::display($tpl);
  	}
}