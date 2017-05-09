<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Fields
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewFields extends RedshopViewList
{
	/**
	 * @var  boolean
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $hasOrdering = true;

	/**
	 * Method for run before display to initial variables.
	 *
	 * @param   string  &$tpl  Template name
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
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
	 *
	 * @since   __DEPLOY_VERSION__
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

		return parent::onRenderColumn($config, $index, $row);
	}
}
