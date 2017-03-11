<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

/**
 * Redshop Text Library section field.
 *
 * @since  1.0
 */
class RedshopFormFieldText_Library_Section extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Text_Library_Section';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getOptions()
	{
		$options = array(
			JHtml::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT')),
			JHtml::_('select.option', 'category', JText::_('COM_REDSHOP_CATEGORY')),
			JHtml::_('select.option', 'newsletter', JText::_('COM_REDSHOP_NEWSLETTER'))
		);

		$parentOptions = parent::getOptions();
		$options = array_merge($parentOptions, $options);

		return $options;
	}
}
