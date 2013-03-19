<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');

//require_once  JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' ;
//require_once  JPATH_COMPONENT_SITE.DS.'helpers'.DS.'helper.php' ;

class quotationViewquotation extends JView
{
	public function display($tpl = null)
	{
		global $mainframe;

		$redconfig = new Redconfiguration;
		$uri       = & JFactory::getURI();

		$option  = JRequest::getVar('option');
		$Itemid  = JRequest::getVar('Itemid');
		$session =& JFactory::getSession();
		$cart    = $session->get('cart');
		$return  = JRequest::getVar('return');
//		if(!DEFAULT_QUOTATION_MODE)
//		{
//			$msg = JText::_('COM_REDSHOP_QUOTAION_MODE_IS_OFF');
//			$mainframe->Redirect ( 'index.php?option='.$option.'&view=cart&Itemid='.$Itemid,$msg);
//		}
//   		$user=JFactory::getUser();
//	   	if(!$user->id) 
//	   	{
//	   			$tpl='user';
		// $mainframe->Redirect ( 'index.php?option='.$option.'&view=checkout&Itemid='.$Itemid);
//		} 
		if (!$return)
		{
			if ($cart['idx'] < 1)
			{
				$mainframe->Redirect('index.php?option=' . $option . '&view=cart&Itemid=' . $Itemid);
			}
		}
		JHTML::Script('validation.js', 'administrator/components/com_redshop/assets/js/', false);

		$model  = $this->getModel('quotation');
		$detail = $model->getData(); //UserAccountInfo();

		$this->assignRef('detail', $detail);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
