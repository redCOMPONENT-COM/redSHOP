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

class newsletter_detailVIEWnewsletter_detail extends JView
{
	function display($tpl = null)
	{
		$option = JRequest::getVar('option','','request','string');
		$layout = JRequest::getVar('layout');

		//$templates = JRequest::getVar ('templates',array());
		$model = $this->getModel ('newsletter_detail');
		$templates	= $model->gettemplates();


		//merging select option in the select box
		$temps = array();
		$temps[0]->value=0;
		$temps[0]->text=JText::_('COM_REDSHOP_SELECT');
		$templates=@array_merge($temps,$templates);

		JToolBarHelper::title(   JText::_('COM_REDSHOP_NEWSLETTER_MANAGEMENT_DETAIL' ), 'redshop_newsletter48' );

		$document = JFactory::getDocument();

		$document->addScript ('components/'.$option.'/assets/js/select_sort.js');

		$uri = JFactory::getURI();
		$lists = array();
		$detail	=& $this->get('data');
		$isNew = ($detail->newsletter_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );

		if($layout=="statistics")
		{
			$document->addScript('http://www.google.com/jsapi');
			$text = "statistics";
			JRequest::setVar ( 'hidemainmenu', 1 );
			$this->setLayout($layout);
		}
		else
		{
			$this->setLayout('default');
		}

		JToolBarHelper::title(   JText::_('COM_REDSHOP_NEWSLETTER' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_newsletter48' );
		if($layout!="statistics")
		{
			JToolBarHelper::apply();
			JToolBarHelper::save();
		}
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$lists['newsletter_template'] = JHTML::_('select.genericlist',$templates,'template_id','class="inputbox" size="1" ','value','text',$detail->template_id);

		$lists['published'] 		= JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );

		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
