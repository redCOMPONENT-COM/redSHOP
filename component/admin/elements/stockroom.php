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
class JElementstockroom extends JElement
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	var $_name = 'stockroom';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = JFactory::getDBO();

		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$query = 'SELECT stockroom_id,stockroom_name ' .
			' FROM #__redshop_stockroom WHERE published=1';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_STOCKROOM') . ' -', 'stockroom_id', 'stockroom_name'));

		return JHTML::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'stockroom_id', 'stockroom_name', $value, $control_name . $name);
	}
}
