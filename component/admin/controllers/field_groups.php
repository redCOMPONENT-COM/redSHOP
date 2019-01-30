<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Field groups list controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       2.1.0
 */
class RedshopControllerField_Groups extends RedshopControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.1.0
	 */
	public function getModel($name = 'Field_Group', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Method for get prepared HTML of fields group
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public function ajaxGetFieldsGroup()
	{
		Redshop\Helper\Ajax::validateAjaxRequest();

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
