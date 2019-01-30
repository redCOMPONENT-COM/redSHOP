<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
class JFormFieldmanufacturer extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'manufacturer';


	protected function getInput()
	{
		$db = JFactory::getDbo();
		$name = $this->name;

		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$query = 'SELECT id,name ' .
			' FROM #__redshop_manufacturer WHERE published=1';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_MANUFACTURER') . ' -', 'id', 'name'));

		return JHTML::_('select.genericlist', $options, $name, 'class="inputbox"', 'id', 'name', $this->value, $name);
	}
}
