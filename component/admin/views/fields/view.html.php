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
	 * Method for run before display to initial variables.
	 *
	 * @param   string  &$tpl  Template name
	 *
	 * @return  void
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

		return parent::onRenderColumn($config, $index, $row);
	}
}
