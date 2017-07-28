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
 * Redshop Shippings method field.
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopFormFieldShippings extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'shippings';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  array  The field input markup.
	 */
	protected function getOptions()
	{
		$shippingList = RedshopHelperOrder::getShippingMethodInfo();

		if (empty($shippingList))
		{
			return parent::getOptions();
		}

		$options = parent::getOptions();
		RedshopHelperShipping::loadLanguages();

		foreach ($shippingList as $shipping)
		{
			$option        = new stdClass;
			$option->value = $shipping->element;
			$option->text  = JText::_($shipping->name);
			$options[]     = $option;
		}

		return $options;
	}
}
