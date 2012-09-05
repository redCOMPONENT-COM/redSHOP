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


class tax_group_detailVIEWtax_group_detail extends JView
{
	function display($tpl = null)
	{		

		$db = jFactory::getDBO();
		
		JToolBarHelper::title(   JText::_('COM_REDSHOP_TAX_GROUP_MANAGEMENT_DETAIL' ), 'redshop_vatgroup48' );
		
		$option = JRequest::getVar('option','','request','string');
		
		$document = & JFactory::getDocument();
		
	 	$uri =& JFactory::getURI();
		
		$this->setLayout('default');

		$lists = array();

		$detail	=& $this->get('data'); 

		$isNew = ($detail->tax_group_id < 1);
		
		if($detail->tax_group_id > 0){
			 	    
	 	    JToolBarHelper :: custom( 'tax', 'redshop_tax_tax32' , JText::_('COM_REDSHOP_TAX') , JText::_('COM_REDSHOP_TAX'), false, false );
	 
		}

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );
		
		JToolBarHelper::title(   JText::_('COM_REDSHOP_TAX_GROUP' ).': <small><small>[ ' . $text.' ]</small></small>', 'redshop_vatgroup48' );
		
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		$lists['published'] 	= JHTML::_('select.booleanlist',   'published', 'class="inputbox" size="1"',  $detail->published );
		
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>