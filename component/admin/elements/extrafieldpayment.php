<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Renders a Productfinder Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */

class JFormFieldextrafieldpayment extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'extrafieldpayment';


	protected function getInput()
	{

		$db = JFactory::getDBO();

		// This might get a conflict with the dynamic translation - TODO: search for better solution

		$query = "SELECT field_name,field_title FROM #__redshop_fields "
			. "WHERE published=1 "
			. "AND field_show_in_front=1 "
			. "AND field_section='18'  ORDER BY ordering";
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_EXTRA_FIELD') . ' -', 'field_name', 'field_title'));
		return JHTML::_('select.genericlist', $options, '' . $this->name . '[]', 'multiple="multiple" size="5"', 'field_name', 'field_title', $this->value, $this->id);

	}
}
