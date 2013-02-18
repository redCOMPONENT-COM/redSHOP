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

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Renders a searchtype Form
 *
 * @package		Joomla
 * @subpackage	Banners
 * @since		1.5
 */
class JFormFieldsearchtype extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	public $type = 'searchtype';

	protected function getInput()
	{
		$db = &JFactory::getDBO();

		$searchType = array();
		$searchType[]   = JHTML::_('select.option', 'product_name', JText::_('COM_REDSHOP_PRODUCT_NAME'));
		$searchType[]   = JHTML::_('select.option', 'product_desc', JText::_('COM_REDSHOP_PRODUCT_DESCRIPTION'));
		$searchType[]   = JHTML::_('select.option', 'product_number', JText::_('COM_REDSHOP_PRODUCT_NUMBER'));
		$searchType[]   = JHTML::_('select.option', 'name_number', JText::_("COM_REDSHOP_PRODUCT_NAME") . ' & ' . JText::_("COM_REDSHOP_PRODUCT_NUMBER"));
		$searchType[]   = JHTML::_('select.option', 'virtual_product_num', JTEXT::_("COM_REDSHOP_VIRTUAL_PRODUCT_NUM"));
		$searchType[]   = JHTML::_('select.option', 'name_desc', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_DESCRIPTION"));
		$searchType[]   = JHTML::_('select.option', 'name_number_desc', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER_AND_VIRTUAL_PRODUCT_NUM_AND_PRODUCT_DESCRIPTION"));

//		array_unshift($searchType, JHTML::_('select.option', '0', '- '.JText::_('COM_REDSHOP_SELECT_SEARCH_TYPE').' -', 'value', 'text'));

		return JHTML::_('select.genericlist',  $searchType, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id );
	}
}