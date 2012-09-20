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
class giftcard_detailVIEWgiftcard_detail extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'GIFTCARD_MANAGEMENT' ), 'redshop_giftcard_48' );

		$uri = & JFactory::getURI();

		jimport('joomla.html.pane');
		$pane = & JPane::getInstance('sliders');
		$this->assignRef('pane',$pane);

		$this->setLayout('default');

		$lists = array();

		$detail	=& $this->get('data');


		$isNew		= ($detail->giftcard_id < 1);

		$text = $isNew ? JText::_( 'NEW' ) : JText::_( 'EDIT' );

		JToolBarHelper::title( JText::_( 'GIFTCARDS' ).': <small><small>[ '.$text.' ]</small></small>' , 'redshop_giftcard_48');

		JToolBarHelper::apply();

		JToolBarHelper::save();

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {

			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$lists['customer_amount'] = JHTML::_('select.booleanlist',  'customer_amount', 'class="inputbox" ', $detail->customer_amount);
		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );

		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}
}?>