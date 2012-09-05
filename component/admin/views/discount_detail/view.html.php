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

class discount_detailVIEWdiscount_detail extends JView
{
	function display($tpl = null)
	{
				
		JToolBarHelper::title(   JText::_( 'DISCOUNT_MANAGEMENT_DETAIL' ), 'redshop_discountmanagmenet48' );
						
		$uri =& JFactory::getURI();
		
		$this->setLayout('default');

		$lists = array();

		$detail	=& $this->get('data');

		$layout = JRequest::getVar('layout');
   				
		if($layout == 'product')
		{
			$this->setLayout('product');	
		
			$isNew = ($detail->discount_product_id < 1);
			
		}else{
		
			$isNew = ($detail->discount_id < 1);
		}

		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		
		JToolBarHelper::title(   JText::_( 'DISCOUNT' ).': <small><small>[ ' . $text.' ]</small></small>','redshop_discountmanagmenet48' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		
		$model = $this->getModel('discount_detail');
		
		$selectedShoppers = $model->selectedShoppers();
		
		$shoppers =& $this->get('shoppers');		
		
		$lists['shopper_group_id'] = JHTML::_('select.genericlist', $shoppers , 'shopper_group_id[]', 'class="inputbox" multiple="multiple" size="10"', 'value', 'text', $selectedShoppers);		
		
		$discount_type          = array (JHTML::_('select.option','no', JText::_('SELECT')),JHTML::_('select.option',0 , JText::_('TOTAL')), JHTML::_('select.option',1, JText::_('PERCENTAGE')));
		$lists['discount_type'] = JHTML::_('select.genericlist',  $discount_type, 'discount_type', 'class="inputbox" size="1"', 'value', 'text',  $detail->discount_type );
		
		$discount_condition          = array (JHTML::_('select.option','0', JText::_('SELECT')),JHTML::_('select.option',1 , JText::_('LOWER')), JHTML::_('select.option',2, JText::_('EQUAL')), JHTML::_('select.option',3, JText::_('HIGHER')));
		$lists['discount_condition'] = JHTML::_('select.genericlist',  $discount_condition, 'condition', 'class="inputbox" size="1"', 'value', 'text',  $detail->condition );
		
		$lists['published'] = JHTML::_('select.booleanlist','published', 'class="inputbox"', $detail->published );
		
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>