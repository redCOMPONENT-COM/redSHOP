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

class supplier_detailVIEWsupplier_detail extends JView
{
	function display($tpl = null)
	{

		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );
		
		JToolBarHelper::title(   JText::_('COM_REDSHOP_SUPPLIER_MANAGEMENT_DETAIL' ), 'redshop_manufact48' );
				
		$uri 		=& JFactory::getURI();
		
		$this->setLayout('default');

		$lists = array();

		$detail	=& $this->get('data');
		
		$model=  $this->getModel('supplier_detail');
		 
		$isNew		= ($detail->supplier_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );
		
		JToolBarHelper::title(   JText::_('COM_REDSHOP_SUPPLIER' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_manufact48' );
		
		 
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );
		
		$this->assignRef('lists',			$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
	
}

?>
