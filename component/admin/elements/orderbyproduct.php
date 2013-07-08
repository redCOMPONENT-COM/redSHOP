<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';

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
		$order_data           = array();

		$order_data[0]        = new stdClass;
		$order_data[0]->value = "p.product_name ASC";
		$order_data[0]->text  = JText::_('COM_REDSHOP_PRODUCT_NAME');

		$order_data[1]        = new stdClass;
		$order_data[1]->value = "p.product_price ASC";
		$order_data[1]->text  = JText::_('COM_REDSHOP_PRODUCT_PRICE_ASC');

		$order_data[2]        = new stdClass;
		$order_data[2]->value = "p.product_price DESC";
		$order_data[2]->text  = JText::_('COM_REDSHOP_PRODUCT_PRICE_DESC');

		$order_data[3]        = new stdClass;
		$order_data[3]->value = "p.product_number ASC";
		$order_data[3]->text  = JText::_('COM_REDSHOP_PRODUCT_NUMBER');

		$order_data[4]        = new stdClass;
		$order_data[4]->value = "p.product_id DESC";
		$order_data[4]->text  = JText::_('COM_REDSHOP_NEWEST');

		$order_data[5]        = new stdClass;
		$order_data[5]->value = "pc.ordering ASC";
		$order_data[5]->text  = JText::_('COM_REDSHOP_ORDERING');

		$order_data[6]        = new stdClass;
		$order_data[6]->value = "m.manufacturer_name ASC";
		$order_data[6]->text  = JText::_('COM_REDSHOP_MANUFACTURER_NAME');

		$name                 = $this->name;
		$value                = $this->value;

		if (!$value)
		{
			$value = DEFAULT_PRODUCT_ORDERING_METHOD;
		}

		$order_select = JHTML::_('select.genericlist', $order_data, $name, 'class="inputbox"', 'value', 'text', $value, $name);

		return $order_select;
	}
}
