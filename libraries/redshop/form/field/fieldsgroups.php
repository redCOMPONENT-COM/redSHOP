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
 * Form Field class for the Joomla Framework.
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopFormFieldFieldsgroups extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $type = 'Fieldsgroups';

	/**
	 * Method to get the field input markup for a generic list.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getOptions()
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models', 'RedshopModel');
		$groups = JModelLegacy::getInstance('Fields_groups', 'RedshopModel')->getItems();

		$options[] = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_FIELDS_GROUP_NOGROUP'));

		if (count($groups) > 0)
		{
			foreach ($groups as $group)
			{
				$options[] = array('value' => $group->id,'text' => $group->name);
			}
		}

		$parentOptions = parent::getOptions();

		return array_merge($parentOptions, $options);
	}
}
