<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Fields
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.6
 */
class RedshopViewFields extends RedshopViewList
{
	/**
	 * @var  boolean
	 *
	 * @since  2.0.6
	 */
	public $hasOrdering = true;

	/**
	 * @var  array
	 *
	 * @since  2.1.0
	 */
	public $fieldGroups = array();

	/**
	 * @var  array
	 */
	public $filterFormOptions = array('filtersHidden' => false);

	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.0.6
	 */
	public function beforeDisplay(&$tpl)
	{
		parent::beforeDisplay($tpl);

		// Only display ordering column if user choose filter fields by section
		$filterSection = (int) $this->state->get('filter.field_section', 0);

		if (!$filterSection)
		{
			$this->hasOrdering = false;
		}
	}

	/**
	 * Method for render 'Published' column
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   2.0.6
	 */
	public function onRenderColumn($config, $index, $row)
	{
		if ($config['dataCol'] == 'type')
		{
			return RedshopHelperTemplate::getFieldTypeSections($row->type);
		}
		elseif ($config['dataCol'] == 'section')
		{
			return RedshopHelperTemplate::getFieldSections($row->section);
		}
		elseif ($config['dataCol'] == 'groupId')
		{
			return $row->groupName;
		}

		return parent::onRenderColumn($config, $index, $row);
	}

	/**
	 * Method for add toolbar.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	protected function addToolbar()
	{
		parent::addToolbar();

		$filterSection = (int) $this->state->get('filter.field_section');

		if ($filterSection)
		{
			/** @var RedshopModelField_Groups $model */
			$model = RedshopModel::getInstance('Field_Groups', 'RedshopModel', array('ignore_request' => true));
			$model->setState('filter.section', $filterSection);
			$model->setState('list.limit', 99);

			$fieldGroups       = $model->getItems();
			$this->fieldGroups = $fieldGroups === false ? array() : $fieldGroups;

			JToolbarHelper::modal('fieldsAssignGroup', 'fa fa-list', 'COM_REDSHOP_FIELDS_MASS_ASSIGN_GROUP');
		}
	}
}
