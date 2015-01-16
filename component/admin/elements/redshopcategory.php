<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * element for default product layout
 *
 * @package        Joomla
 * @subpackage     redshop
 * @since          1.5
 */
class JFormFieldRedshopcategory extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'redshopcategory';

	protected function getInput()
	{
		$name = $this->name;
		$categories = RedshopHelperCategory::getCategoryListArray();
		array_unshift($categories, JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_CATEGORY') . ' -', 'category_id', 'category_name'));

		return JHTML::_('select.genericlist', $categories, $name, 'class="inputbox"', 'category_id', 'category_name', $this->value, $name);
	}
}
