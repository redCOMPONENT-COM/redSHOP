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

class attribute_set_detailVIEWattribute_set_detail extends JView
{
	function display($tpl = null)
	{ 
		$redTemplate = new Redtemplate();
			
		$option = JRequest::getVar('option');
		$db = JFactory::getDBO();
		$cfg = JFactory::getConfig(); 
	    $dbPrefix = $cfg->getValue('config.dbprefix');
		$lists = array();
		
		$model = $this->getModel ( 'attribute_set_detail' );
		 
		
		$attributes = $model->getattributes();

		JToolBarHelper::title(JText::_('COM_REDSHOP_ATTRIBUTE_SET_DETAIL' ),'redshop_attribute_bank48');
		
		$document = &JFactory::getDocument();		
		
		$document->addScriptDeclaration ("
		
		var WANT_TO_DELETE = '".JText::_('COM_REDSHOP_DO_WANT_TO_DELETE')."';
		
		");
		
		$document->addScript ('components/'.$option.'/assets/js/fields.js');
		
		$document->addScript ('components/'.$option.'/assets/js/select_sort.js');
		
		//$document->addScript ('components/'.$option.'/assets/js/json.js');
		
		$document->addScript ('components/'.$option.'/assets/js/validation.js');
		
		//$document->addStyleSheet ( 'components/com_redshop/assets/css/search.css' );
		
		//$document->addScript ('components/com_redshop/assets/js/search.js');
		
		//$document->addScript ('components/com_redshop/assets/js/related.js');

		$uri =& JFactory::getURI();
		
		
		//$this->setLayout('default');
				

		

		$detail	=& $this->get('data');

		$isNew = ($detail->attribute_set_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );
		
		JToolBarHelper::title(   JText::_('COM_REDSHOP_ATTRIBUTE_SET' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_attribute_bank48' );
		
			
		JToolBarHelper::apply();
		
		JToolBarHelper::save();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
		
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		
		$lists['published'] = JHTML::_('select.booleanlist','published', 'class="inputbox"', $detail->published );
				
		
		$lists['attributes'] = $attributes;
		
		 
		$this->assignRef('model',		$model);
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
?>