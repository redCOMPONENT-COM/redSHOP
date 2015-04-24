<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('redshop.library');
JLoader::load('RedshopHelperHelper');

/**
 * Renders a Manufacturer Order by Fields
 *
 * @package  RedSHOP
 * @since    1.5
 */
class JFormFieldOrderbymanufacturer extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'orderbymanufacturer';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$helper = new redhelper;
		$name   = $this->name;
		$value  = $this->value;

		if (!$value)
		{
			$value = DEFAULT_MANUFACTURER_ORDERING_METHOD;
		}

		$order_data = $helper->getManufacturerOrderByList();

		$order_select = JHTML::_('select.genericlist', $order_data, $name, 'class="inputbox"', 'value', 'text', $value, $name);

		return $order_select;
	}
}
