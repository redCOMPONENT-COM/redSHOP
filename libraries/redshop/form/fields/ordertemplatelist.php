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
 * Renders a template Form
 *
 * @package        Joomla
 * @subpackage     Banners
 * @since          1.5
 */
class JFormFieldordertemplatelist extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'ordertemplatelist';

	protected function getInput()
	{
		$db = JFactory::getDbo();

		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$query = 'SELECT id,name FROM #__redshop_template '
			. 'WHERE published=1 '
			. 'AND section="order_list" ';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		array_unshift($options, JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_TEMPLATE') . ' -', 'id', 'name'));

		return JHTML::_('select.genericlist', $options, $this->name, 'class="inputbox"', 'id', 'name', $this->value, $this->id);


	}
}

?>
