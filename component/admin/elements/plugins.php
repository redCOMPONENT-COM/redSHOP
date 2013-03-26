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

class JFormFieldplugins extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'plugins';


	protected function getInput()
	{

		$db = JFactory::getDBO();

		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$query = 'SELECT shopper_group_id,shopper_group_name ' .
			' FROM #__redshop_shopper_group WHERE published=1';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_SHOPPER_GROUP') . ' -', 'shopper_group_id', 'shopper_group_name'));
		return JHTML::_('select.genericlist', $options, '' . $this->name . '[]', 'multiple="multiple" size="5"', 'shopper_group_id', 'shopper_group_name', $this->value, $this->id);

	}
}
