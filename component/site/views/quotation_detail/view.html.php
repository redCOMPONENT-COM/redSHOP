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
require_once (JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'quotation.php');

class quotation_detailViewquotation_detail extends JView
{ 
   	function display ($tpl=null)
   	{ 
   		global $mainframe;

   		$quotationHelper = new quotationHelper();
   		
   		$print = JRequest::getVar('print');
   		if($print)
   		{ ?>
  			<script type="text/javascript" language="javascript">
				window.print();
  			</script>			
  <?php }
   		
//   	$params = &$mainframe->getParams('com_redshop');
//   	$pathway = &$mainframe->getPathway();
//		$document = &JFactory::getDocument();
		
//   	$pathway->addItem(JText::_('COM_REDSHOP_ORDER_DETAILS'),'');
   		$user =& JFactory::getUser();
   		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
   		
		$quoid = JRequest::getInt('quoid');
   		$encr = JRequest::getVar('encr');

   		if(!$quoid)
   		{
   			$mainframe->Redirect ( 'index.php?option='.$option.'&view=account&Itemid='.$Itemid);
   		}
   		$quotationDetail = $quotationHelper->getQuotationDetail($quoid);

   		if(count($quotationDetail)<1)
   		{
   			JError::raiseWarning(404,JText::_('COM_REDSHOP_NOACCESS_QUOTATION'));
			echo JText::_('COM_REDSHOP_NOACCESS_QUOTATION');
			return;
   		}
   		if(!$user->id)
   		{
	   		if(isset($encr))
	   		{
	   			$model = $this->getModel('quotation_detail');
	   			$authorization = $model->checkAuthorization($quoid,$encr);
	   			if(!$authorization)
	   			{
	   				JError::raiseWarning(404,JText::_('COM_REDSHOP_QUOTATION_ENCKEY_FAILURE'));
	   				echo JText::_('COM_REDSHOP_QUOTATION_ENCKEY_FAILURE');
	   				return false;
	   			}
	   		} else {
				$mainframe->Redirect('index.php?option=com_redshop&view=login&Itemid='.JRequest::getVar('Itemid'));
				return;
			}
   		} else {
   			if(count($quotationDetail)>0 && $quotationDetail->user_id != $user->id)
   			{
   				JError::raiseWarning(404,JText::_('COM_REDSHOP_NOACCESS_QUOTATION'));
   				echo JText::_('COM_REDSHOP_NOACCESS_QUOTATION');
				return;
   			}
   		}
//		$this->assignRef('params',$params);

   		parent::display($tpl);
  	}
}	?>