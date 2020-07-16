<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Wrapper Detail View
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0.6
 */
class RedshopViewWrapper extends RedshopViewForm
{
	/**
	 * Method for prepare field HTML
	 *
	 * @param object $field Group object
	 *
	 * @return  boolean|string  False if keep. String for HTML content if success.
	 *
	 * @throws \Exception
	 * @since   __DEPLOY_VERSION__
	 */
	protected function prepareField($field)
	{
		return parent::prepareField($field);
	}
}
