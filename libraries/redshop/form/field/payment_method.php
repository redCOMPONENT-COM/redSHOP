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
 * Redshop Payment Methods field.
 *
 * @since  1.0
 */
class RedshopFormFieldPayment_Method extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Payment_Method';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  array  The field input markup.
	 */
	protected function getOptions()
	{
		$payments = RedshopHelperOrder::getPaymentMethodInfo();

		if (empty($payments))
		{
			return parent::getOptions();
		}

		RedshopHelperPayment::loadLanguages();

		$options     = array();
		$this->value = $this->multiple ? (array) $this->value : (string) $this->value;

		foreach ($payments as $payment)
		{
			$option = new stdClass;

			$option->text  = JText::_($payment->name);
			$option->value = $payment->element;

			$options[] = $option;
		}

		return array_merge(parent::getOptions(), $options);
	}
}
