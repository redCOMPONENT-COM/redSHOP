<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop Order Status Section Search field.
 *
 * @since  1.0
 */
class RedshopFormFieldOrder_Status extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Order_Status';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  array  The field input markup.
	 */
	protected function getOptions()
	{
		// Load redSHOP Library
		JLoader::import('redshop.library');

		$this->value = $this->multiple ? (array) $this->value : (string) $this->value;
		$orderStatus = RedshopHelperOrder::getOrderStatusList();

		// Merge any additional options in the XML definition.
		return array_merge(parent::getOptions(), $orderStatus);
	}
}
