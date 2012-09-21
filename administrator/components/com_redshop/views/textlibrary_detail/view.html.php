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

class textlibrary_detailVIEWtextlibrary_detail extends JView
{
	function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->setTitle( JText::_('COM_REDSHOP_TEXTLIBRARY') );

		$uri 		= JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail	= $this->get('data');

		$isNew		= ($detail->textlibrary_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );
		JToolBarHelper::title(   JText::_('COM_REDSHOP_TEXTLIBRARY' ).': <small><small>[ ' . $text.' ]</small></small>', 'redshop_textlibrary48' );
		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {

			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		// Section can be added from here
		$optionsection = array();
		$optionsection[]   	= JHTML::_('select.option', '0',JText::_('COM_REDSHOP_SELECT'));
		$optionsection[]   	= JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_Product'));
		$optionsection[]   	= JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_Category'));
		$optionsection[]   	= JHTML::_('select.option', 'newsletter', JText::_('COM_REDSHOP_Newsletter'));

		$lists['section'] = JHTML::_('select.genericlist',$optionsection,  'section', 'class="inputbox" size="1" ' , 'value', 'text',  $detail->section );

		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );

		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}

}
