<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
class RedshopFormFieldFieldType extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	protected $type = 'fieldtype';

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

		$fieldTypes = RedshopHelperTemplate::getFieldTypeSections();

		$parentOptions = parent::getOptions();
		$options = array_merge($parentOptions, $fieldTypes);

		return $options;
	}
}
