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
class JFormFieldsearchtype extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'searchtype';

	protected function getInput()
	{
		$searchType = array();
		$searchType[] = JHTML::_('select.option', 'product_name', JText::_('COM_REDSHOP_PRODUCT_NAME'));
		$searchType[] = JHTML::_('select.option', 'product_desc', JText::_('COM_REDSHOP_PRODUCT_DESCRIPTION'));
		$searchType[] = JHTML::_('select.option', 'product_number', JText::_('COM_REDSHOP_PRODUCT_NUMBER'));
		$searchType[] = JHTML::_('select.option', 'name_number', JText::_("COM_REDSHOP_PRODUCT_NAME") . ' & ' . JText::_("COM_REDSHOP_PRODUCT_NUMBER"));
		$searchType[] = JHTML::_('select.option', 'virtual_product_num', JTEXT::_("COM_REDSHOP_VIRTUAL_PRODUCT_NUM"));
		$searchType[] = JHTML::_('select.option', 'name_desc', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_DESCRIPTION"));
		$searchType[] = JHTML::_('select.option', 'name_number_desc', JText::_("COM_REDSHOP_PRODUCT_NAME_AND_PRODUCT_NUMBER_AND_VIRTUAL_PRODUCT_NUM_AND_PRODUCT_DESCRIPTION"));

//		array_unshift($searchType, JHTML::_('select.option', '0', '- '.JText::_('COM_REDSHOP_SELECT_SEARCH_TYPE').' -', 'value', 'text'));

		return JHTML::_('select.genericlist', $searchType, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
	}
}
