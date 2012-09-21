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
jimport('joomla.html.pane');

class mail_detailVIEWmail_detail extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_('COM_REDSHOP_MAIL_MANAGEMENT_DETAIL' ), 'redshop_mailcenter48' );

		$option = JRequest::getVar('option','','request','string');

		$document = JFactory::getDocument();

		//$document->addScript ('components/'.$option.'/assets/js/media.js');
		$document->addScript ('components/'.$option.'/assets/js/json.js');
		$document->addScript ('components/'.$option.'/assets/js/validation.js');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail	= $this->get('data');

		$isNew = ($detail->mail_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );

		JToolBarHelper::title(   JText::_('COM_REDSHOP_MAIL' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_mailcenter48' );

		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {

			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$model = $this->getModel('mail_detail');

		if($detail->mail_section == 'order_status' && $detail->mail_section!='0')
		{
			$order_status  = $model->mail_section();
			$select = array();
			$select[]   = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_Select'));
			$merge = array_merge($select,$order_status);

			$lists['order_status'] = JHTML::_('select.genericlist',$merge,  'mail_order_status', 'class="inputbox" size="1" title="" ', 'value', 'text',$detail->mail_order_status );
		}


		$redtemplate = new Redtemplate();
		$optiontype = $redtemplate->getMailSections();
		$lists['type'] 		= JHTML::_('select.genericlist',$optiontype,  'mail_section', 'class="inputbox" size="1" onchange="mail_select(this)" ', 'value', 'text',  $detail->mail_section );

		$lists['published'] = JHTML::_('select.booleanlist','published', 'class="inputbox"', $detail->published );

		$pane = JPane::getInstance('sliders');

		$this->assignRef('pane',$pane);
		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}
