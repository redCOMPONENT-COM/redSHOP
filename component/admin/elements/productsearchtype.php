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
class JElementproductsearchtype extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'productsearchtype';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();

		$searchType = array();
		$searchType[]   = JHTML::_('select.option', 'p.product_price ASC', JText::_('PRODUCT_NAME'));
		$searchType[]   = JHTML::_('select.option', 'p.product_price ASC', JText::_('PRODUCT_PRICE_ASC'));
		$searchType[]   = JHTML::_('select.option', 'p.product_price DESC', JText::_('PRODUCT_PRICE_DESC'));
		$searchType[]   = JHTML::_('select.option', 'p.product_number ASC', JText::_('PRODUCT_NUMBER_ASC'));
		$searchType[]   = JHTML::_('select.option', 'p.product_id DESC', JText::_('NEWEST'));
		$searchType[]   = JHTML::_('select.option', 'pc.ordering ASC', JText::_('ORDER'));
		$searchType[]   = JHTML::_('select.option', 'm.manufacturer_name ASC', JText::_('COM_REDSHOP_MANUFACTURER_NAME'));

		
//		array_unshift($searchType, JHTML::_('select.option', '0', '- '.JText::_('Select Search Type').' -', 'value', 'text'));

		return JHTML::_('select.genericlist',  $searchType, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value, $control_name.$name );
	}
}
