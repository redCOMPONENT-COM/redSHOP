<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Renders a searchtype Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */
class JFormFieldproductsearchtype extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'productsearchtype';

	protected function getInput()
	{
		$searchType = array();

		$searchType[] = JHTML::_('select.option', 'p.product_name ASC', JText::_('COM_REDSHOP_PRODUCT_NAME'));
		$searchType[] = JHTML::_('select.option', 'p.product_price ASC', JText::_('COM_REDSHOP_PRODUCT_PRICE_ASC'));
		$searchType[] = JHTML::_('select.option', 'p.product_price DESC', JText::_('COM_REDSHOP_PRODUCT_PRICE_DESC'));
		$searchType[] = JHTML::_('select.option', 'p.product_number ASC', JText::_('COM_REDSHOP_PRODUCT_NUMBER_ASC'));
		$searchType[] = JHTML::_('select.option', 'p.product_id DESC', JText::_('COM_REDSHOP_NEWEST'));
		$searchType[] = JHTML::_('select.option', 'pc.ordering ASC', JText::_('COM_REDSHOP_ORDER'));
		$searchType[] = JHTML::_('select.option', 'm.manufacturer_name ASC', JText::_('COM_REDSHOP_MANUFACTURER_NAME'));

//		array_unshift($searchType, JHTML::_('select.option', '0', '- '.JText::_('COM_REDSHOP_SELECT_SEARCH_TYPE').' -', 'value', 'text'));

		return JHTML::_('select.genericlist', $searchType, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
	}
}
