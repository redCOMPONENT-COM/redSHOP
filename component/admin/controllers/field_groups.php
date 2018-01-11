<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Field groups list controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       __DEPLOY_VERSION__
 */
class RedshopControllerField_Groups extends RedshopControllerAdmin
{
	/**
	 * Method for get prepared HTML of fields group
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function ajaxGetFieldsGroup()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$section  = $this->input->getInt('section', 0);
		$selected = $this->input->getInt('selected', 0);

		/** @var RedshopModelField_Groups $model */
		$model = $this->getModel('Field_Groups');
		$model->setState('list.limit', 99);
		$model->setState('filter.section', $section);

		$fieldGroups = $model->getItems();

		$options = array('<option value="">' . JText::_('COM_REDSHOP_FIELD_GROUP_NOGROUP') . '</option>');

		if (!empty($fieldGroups))
		{
			foreach ($fieldGroups as $fieldGroup)
			{
				$checked   = $fieldGroup->id == $selected ? 'selected="selected"' : '';
				$options[] = '<option value="' . $fieldGroup->id . '" ' . $checked . '>' . $fieldGroup->name . '</option>';
			}
		}

		echo implode("\n", $options);

		JFactory::getApplication()->close();
	}
}
