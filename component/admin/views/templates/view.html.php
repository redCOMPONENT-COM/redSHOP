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
 * View Templates
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.7
 */
class RedshopViewTemplates extends RedshopViewList
{
	/**
	 * Display duplicate button or not.
	 *
	 * @var   boolean
	 * @since  2.0.7
	 */
	protected $enableDuplicate = true;

	/**
	 * Method for render 'Published' column
	 *
	 * @param   array   $config  Row config.
	 * @param   int     $index   Row index.
	 * @param   object  $row     Row data.
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public function onRenderColumn($config, $index, $row)
	{
		if ($config['dataCol'] === 'template_section')
		{
			return RedshopHelperTemplate::getTemplateSections($row->template_section);
		}

		return parent::onRenderColumn($config, $index, $row);
	}
}
