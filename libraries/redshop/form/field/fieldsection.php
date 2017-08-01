<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Renders a Productfinder Form
 *
 * @package        RedSHOP.Backend
 * @subpackage     Element
 * @since          1.5
 */
class RedshopFormFieldFieldSection extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	protected $type = 'fieldsection';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		JFactory::getLanguage()->load('com_redshop');

		$fieldSections   = array();
		$fieldSections[] = (object) array('value' => '1', 'text' => JText::_('COM_REDSHOP_PRODUCT'));
		$fieldSections[] = (object) array('value' => '2', 'text' => JText::_('COM_REDSHOP_CATEGORY'));
		$fieldSections[] = (object) array('value' => '7', 'text' => JText::_('COM_REDSHOP_CUSTOMER_ADDRESS'));
		$fieldSections[] = (object) array('value' => '8', 'text' => JText::_('COM_REDSHOP_COMPANY_ADDRESS'));
		$fieldSections[] = (object) array('value' => '9', 'text' => JText::_('COM_REDSHOP_COLOR_SAMPLE'));
		$fieldSections[] = (object) array('value' => '10', 'text' => JText::_('COM_REDSHOP_MANUFACTURER'));
		$fieldSections[] = (object) array('value' => '11', 'text' => JText::_('COM_REDSHOP_SHIPPING'));
		$fieldSections[] = (object) array('value' => '12', 'text' => JText::_('COM_REDSHOP_PRODUCT_USERFIELD'));
		$fieldSections[] = (object) array('value' => '13', 'text' => JText::_('COM_REDSHOP_GIFTCARD_USERFIELD'));
		$fieldSections[] = (object) array('value' => '14', 'text' => JText::_('COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS'));
		$fieldSections[] = (object) array('value' => '15', 'text' => JText::_('COM_REDSHOP_COMPANY_SHIPPING_ADDRESS'));
		$fieldSections[] = (object) array('value' => '17', 'text' => JText::_('COM_REDSHOP_PRODUCTFINDER_DATEPICKER'));
		$fieldSections[] = (object) array('value' => '16', 'text' => JText::_('COM_REDSHOP_QUOTATION'));
		$fieldSections[] = (object) array('value' => '18', 'text' => JText::_('COM_REDSHOP_PAYMENT_GATEWAY'));
		$fieldSections[] = (object) array('value' => '19', 'text' => JText::_('COM_REDSHOP_SHIPPING_GATEWAY'));

		$parentOptions = parent::getOptions();
		$options = array_merge($parentOptions, $fieldSections);

		return $options;
	}
}
