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
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'category.php' );
class manufacturer_detailVIEWmanufacturer_detail extends JView
{
	function display($tpl = null)
	{

		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'extra_field.php' );

		JToolBarHelper::title(   JText::_('COM_REDSHOP_MANUFACTURER_MANAGEMENT_DETAIL' ), 'redshop_manufact48' );

		$uri 		=& JFactory::getURI();
		$document = & JFactory::getDocument();
		$option = JRequest::getVar('option');
		$document->addScript ('components/'.$option.'/assets/js/validation.js');
		$this->setLayout('default');

		$lists = array();

		$detail	=& $this->get('data');

		$model=  $this->getModel('manufacturer_detail');

		$template_data = $model->TemplateData();

		$isNew		= ($detail->manufacturer_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );

		JToolBarHelper::title(   JText::_('COM_REDSHOP_MANUFACTURER' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_manufact48' );

		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {

			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$optiontemplet = array();
		$optiontemplet[]   	= JHTML::_('select.option', '0',JText::_('COM_REDSHOP_Select'));

		$result = array_merge($optiontemplet, $template_data);

		$lists['template'] 	= JHTML::_('select.genericlist',$result,  'template_id', 'class="inputbox" size="1" ', 'value', 'text',  $detail->template_id );

		$detail->excluding_category_list = explode(',',$detail->excluding_category_list);
		$product_category = new product_category();
		$lists['excluding_category_list'] = $product_category->list_all("excluding_category_list[]",0,$detail->excluding_category_list,10,false,true);
		
		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );
		$field = new extra_field();
		//////// Extra field //////////
		$list_field=$field->list_all_field(10, $detail->manufacturer_id); /// field_section 6 :Userinformations
		$lists['extra_field']=$list_field;
		//////////////////////////////

		$this->assignRef('lists',			$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}

}

?>
