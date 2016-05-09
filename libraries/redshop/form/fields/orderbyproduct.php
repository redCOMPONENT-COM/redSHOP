<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JLoader::import('redshop.library');

/**
 * Renders a Product Orderby Fields
 *
 * @package  RedSHOP
 * @since    1.2
 */
class JFormFieldorderbyproduct extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'orderbyproduct';

	protected function getInput()
	{
		$helper = redhelper::getInstance();
		$name                 = $this->name;
		$value                = $this->value;

		if (!$value)
		{
			$value = DEFAULT_PRODUCT_ORDERING_METHOD;
		}

		$order_data = $helper->getOrderByList();

		$order_select = JHTML::_('select.genericlist', $order_data, $name, 'class="inputbox"', 'value', 'text', $value, $name);

		return $order_select;
	}
}
