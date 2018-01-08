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
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getOptions()
	{
		JModelList::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models', 'RedshopModel');

		/**
		 * @var  $model  RedshopModelField_Groups
		 */
		$model = JModelList::getInstance('Field_Groups', 'RedshopModel');
		$model->setState('list.limit', 99);
		$groups = $model->getItems();

		$options = array(
			JHtml::_('select.option', 0, JText::_('COM_REDSHOP_FIELD_GROUP_NOGROUP'))
		);

		if ($groups)
		{
			foreach ($groups as $group)
			{
				$options[] = array('value' => $group->id, 'text' => $group->name);
			}
		}

		$parentOptions = parent::getOptions();

		return array_merge($parentOptions, $options);
	}
}
