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
 * The Catalog view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View.Catalog
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewCatalog extends RedshopViewForm
{
	/**
	 * Method for prepare field HTML
	 *
	 * @param   object  $field  Group object
	 *
	 * @return  boolean|string  False if keep. String for HTML content if success.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function prepareField($field)
	{
		if ($field->getAttribute('name') == 'media')
		{
			return false;
		}

		return parent::/** @scrutinizer ignore-call */ prepareField($field);
	}
}
