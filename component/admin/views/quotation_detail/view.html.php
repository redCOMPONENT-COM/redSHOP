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

jimport( 'joomla.application.component.view' );

//require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'extra_field.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'quotation.php' );

class quotation_detailVIEWquotation_detail extends JView
{
	function display($tpl = null)
	{
		$quotationHelper = new quotationHelper();
		$option = JRequest::getVar('option');
		$layout = JRequest::getVar('layout','default');
		
		$document = & JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_QUOTATION') );
		
		$document->addScript (JURI::base().'components/'.$option.'/assets/js/order.js');
		$document->addScript (JURI::base().'components/'.$option.'/assets/js/common.js');
		$document->addStyleSheet (JURI::base(). 'components/'.$option.'/assets/css/search.css' );
		$document->addScript (JURI::base().'components/'.$option.'/assets/js/search.js');
		$document->addScript (JURI::base().'components/'.$option.'/assets/js/json.js');
		
		$uri	=& JFactory::getURI();
		$lists = array();
		$model = $this->getModel();
		
		if($layout!='default')
		{
			$this->setLayout($layout);
		}
		$detail	=& $this->get('data');
		$redconfig = new Redconfiguration();

		$isNew = ($detail->quotation_id < 1);
		$userarr = & $this->get('userdata');

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );
		
		JToolBarHelper::title(JText::_('COM_REDSHOP_QUOTATION_DETAIL' ).': <small><small>[ '.$text.' ]</small></small>', 'redshop_quotation48' );
		JToolBarHelper::save();
		JToolBarHelper::custom( 'send','send.png','send.png',JText::_('COM_REDSHOP_SEND'),false);
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		$status = $quotationHelper->getQuotationStatusList();
		$lists['quotation_status'] 	= JHTML::_('select.genericlist',$status,  'quotation_status', 'class="inputbox" size="1" ', 'value', 'text',  $detail->quotation_status );
		
		$this->assignRef('lists',		$lists);
		$this->assignRef('quotation',	$detail);
		$this->assignRef('quotationuser',	$userarr);
		$this->assignRef('request_url',	$uri->toString());
		
		parent::display($tpl);
	}
}	?>