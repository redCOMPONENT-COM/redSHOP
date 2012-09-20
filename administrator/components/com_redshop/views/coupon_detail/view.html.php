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

class coupon_detailVIEWcoupon_detail extends JView
{
	function display($tpl = null)
	{
		$option = JRequest::getVar('option');
		$userslist = JRequest::getVar ( 'userslist' ,array());

		JToolBarHelper::title(   JText::_('COM_REDSHOP_COUPON_MANAGEMENT_DETAIL' ), 'redshop_coupon48' );

		$document = & JFactory::getDocument();

		$document->addStyleSheet ( 'components/'.$option.'/assets/css/search.css' );

		$document->addScript ('components/'.$option.'/assets/js/search.js');

		$uri 		=& JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail	=& $this->get('data');

		$isNew		= ($detail->coupon_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );

		JToolBarHelper::title(   JText::_('COM_REDSHOP_COUPON' ).': <small><small>[ '.$text.' ]</small></small>','redshop_coupon48' );

		JToolBarHelper::save();

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {

			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$model=  $this->getModel('coupon_detail');
		$lists['free_shipping'] 	= JHTML::_('select.booleanlist',  'free_shipping', 'class="inputbox" ', $detail->free_shipping );
		$percent_or_total = array (JHTML::_('select.option','no', JText::_('COM_REDSHOP_SELECT')),JHTML::_('select.option',0 , JText::_('COM_REDSHOP_TOTAL')), JHTML::_('select.option',1, JText::_('COM_REDSHOP_PERCENTAGE')));
        $lists['percent_or_total'] = JHTML::_('select.genericlist',  $percent_or_total, 'percent_or_total', 'class="inputbox" size="1"', 'value', 'text',  $detail->percent_or_total );

		$coupon_type = array (JHTML::_('select.option','no', JText::_('COM_REDSHOP_SELECT')),JHTML::_('select.option',0,JText::_('COM_REDSHOP_GLOBAL')), JHTML::_('select.option',1,JText::_('COM_REDSHOP_USER_SPECIFIC')));
        $lists['coupon_type'] = JHTML::_('select.genericlist',  $coupon_type, 'coupon_type', 'class="inputbox" size="1"', 'value', 'text',  $detail->coupon_type );

		$lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $detail->published );

		$lists['userslist'] = JHTML::_('select.genericlist',$userslist,'userid','class="inputbox" size="1" ','value','text',$detail->userid);

		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());

		parent::display($tpl);
	}

}

?>
