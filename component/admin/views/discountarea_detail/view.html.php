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

class discountarea_detailVIEWdiscountarea_detail extends JView
{
	function display($tpl = null)
	{		
		JToolBarHelper::title(   JText::_( 'DISCOUNT_MANAGEMENT_DETAIL' ), 'redshop_discountmanagmenet48' );
		$uri =& JFactory::getURI();
		$lists = array();

		$model = $this->getModel();
		$option = JRequest::getVar('option');
		
		$document = & JFactory::getDocument();
		$document->addScript ('components/'.$option.'/assets/js/select_sort.js');
		$document->addStyleSheet ( 'components/'.$option.'/assets/css/search.css' );
		$document->addScript ('components/'.$option.'/assets/js/search.js');
		
		$detail	=& $this->get('data');
		$isNew = ($detail->discountAreaid < 1);
		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );
		
		JToolBarHelper::title(   JText::_( 'DISCOUNT' ).': <small><small>[ ' . $text.' ]</small></small>','redshop_discountmanagmenet48' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		$lists['discount_on'] = JHTML::_('select.booleanlist',  'discount_on', 'class="inputbox" ', $detail->discount_on, JText::_('PERCENTAGE'), JText::_('TOTAL'));
		$lists['published'] = JHTML::_('select.booleanlist','published', 'class="inputbox"', $detail->published );
		
		$categoryData = $model->GetCategoryList();
		$detail->category_id = explode(',',$detail->category_id);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp,$detail->category_id);
		$lists['category_id'] = JHTML::_('select.genericlist',$categoryData,'category_id[]','class="inputbox" multiple="multiple" ','value','text',$detail->category_id);
		
		$productData = array();
		$result_container = array();
		if($detail->product_id) 
		{
			$result_container = $model->GetProductList($detail->product_id);
		}
		$lists['product_all'] = JHTML::_('select.genericlist',$productData,'product_all[]','class="inputbox" multiple="multiple" ','value','text',$detail->product_id);
		$lists['product_id'] 	= JHTML::_('select.genericlist',$result_container,  'container_product[]', 'class="inputbox" onmousewheel="mousewheel(this); ondblclick="selectnone(this);" multiple="multiple"  size="15" style="width:200px;" ', 'value', 'text', 0 );
				
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>