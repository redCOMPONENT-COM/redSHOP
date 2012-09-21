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
jimport( 'joomla.application.component.view' );

class prices_detailVIEWprices_detail extends JView
{
	function display($tpl = null)
	{
		$db = jFactory::getDBO();
		JToolBarHelper::title(   JText::_('COM_REDSHOP_PRICE_MANAGEMENT_DETAIL' ), 'redshop_vatrates48' );
		$option = JRequest::getVar('option','','request','string');
		$document = JFactory::getDocument();
	 	$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();
		$detail	= $this->get('data');

		$isNew = ($detail->price_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW' ) : JText::_('COM_REDSHOP_EDIT' );

		JToolBarHelper::title(   JText::_('COM_REDSHOP_PRICE' ).': <small><small>[ ' . $text.' ]</small></small>' , 'redshop_vatrates48' );
		JToolBarHelper::save();

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$model = $this->getModel('prices_detail');
		$lists['product_id'] = $detail->product_id;
		$lists['product_name'] = $detail->product_name;

		 $q =  'SELECT shopper_group_id AS value,shopper_group_name AS text '
			. 'FROM #__'.TABLE_PREFIX.'_shopper_group';
		$db->setQuery($q);
		$shoppergroup  = $db->loadObjectList();

	/*	$arrtmp = array();
		$arrtmp[0]->value="0";
		$arrtmp[0]->text=JText::_('COM_REDSHOP_DEFAULT');
		$shoppergroup=@array_merge($arrtmp,$shoppergroup);*/
		$lists['shopper_group_name'] = JHTML::_('select.genericlist',$shoppergroup,'shopper_group_id','class="inputbox" size="1"','value','text',$detail->shopper_group_id);

		$this->assignRef('lists',		$lists);
		$this->assignRef('detail',		$detail);
		$this->assignRef('request_url',	$uri->toString());
		parent::display($tpl);
	}
}?>
