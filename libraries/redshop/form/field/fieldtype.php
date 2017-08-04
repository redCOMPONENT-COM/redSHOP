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

		$fieldTypes   = array();
		$fieldTypes[] = (object) array('value' => '1', 'text' => JText::_('COM_REDSHOP_TEXT_FIELD'));
		$fieldTypes[] = (object) array('value' => '2', 'text' => JText::_('COM_REDSHOP_TEXT_AREA'));
		$fieldTypes[] = (object) array('value' => '3', 'text' => JText::_('COM_REDSHOP_CHECKBOX'));
		$fieldTypes[] = (object) array('value' => '4', 'text' => JText::_('COM_REDSHOP_RADIOBOX'));
		$fieldTypes[] = (object) array('value' => '5', 'text' => JText::_('COM_REDSHOP_SINGLE_SELECT_BOX'));
		$fieldTypes[] = (object) array('value' => '6', 'text' => JText::_('COM_REDSHOP_MULTI_SELECT_BOX'));
		$fieldTypes[] = (object) array('value' => '7', 'text' => JText::_('COM_REDSHOP_SELECT_COUNTRY_BOX'));
		$fieldTypes[] = (object) array('value' => '8', 'text' => JText::_('COM_REDSHOP_WYSIWYG'));
		$fieldTypes[] = (object) array('value' => '9', 'text' => JText::_('COM_REDSHOP_MEDIA'));
		$fieldTypes[] = (object) array('value' => '10', 'text' => JText::_('COM_REDSHOP_DOCUMENTS'));
		$fieldTypes[] = (object) array('value' => '11', 'text' => JText::_('COM_REDSHOP_IMAGE'));
		$fieldTypes[] = (object) array('value' => '12', 'text' => JText::_('COM_REDSHOP_DATE_PICKER'));
		$fieldTypes[] = (object) array('value' => '13', 'text' => JText::_('COM_REDSHOP_IMAGE_WITH_LINK'));
		$fieldTypes[] = (object) array('value' => '15', 'text' => JText::_('COM_REDSHOP_SELECTION_BASED_ON_SELECTED_CONDITIONS'));

		$parentOptions = parent::getOptions();
		$options = array_merge($parentOptions, $fieldTypes);

		return $options;
	}
}
