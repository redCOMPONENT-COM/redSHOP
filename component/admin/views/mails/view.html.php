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
 * View Mails
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.7
 */
class RedshopViewMails extends RedshopViewList
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
	 * @since   2.0.6
	 */
	public function onRenderColumn($config, $index, $row)
	{
		if ($config['dataCol'] === 'mail_section')
		{
			return RedshopHelperTemplate::getMailSections($row->mail_section);
		}

		return parent::onRenderColumn($config, $index, $row);
	}
}
