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
 * View Categories
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.5
 */
class RedshopViewFields extends RedshopViewList
{
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
		$redtemplate = Redtemplate::getInstance();

		$isCheckedOut = $row->checked_out && JFactory::getUser()->id != $row->checked_out;

		if ($config['dataCol'] == 'type')
		{
			return $redtemplate->getFieldTypeSections($row->type);
		}

		if ($config['dataCol'] == 'section')
		{
			return $redtemplate->getFieldSections($row->section);
		}

		return parent::onRenderColumn($config, $index, $row);
	}
}
