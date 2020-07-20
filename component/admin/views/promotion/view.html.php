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
 * View promotion
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewPromotion extends RedshopViewForm
{
	/**
	 * Method for prepare field HTML
	 *
	 * @param   object  $field  Group object
	 *
	 * @return  boolean|string  False if keep. String for HTML content if success.
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws \Exception
	 */
	protected function prepareField($field)
	{
		switch ($field->getAttribute('name'))
        {
            default:
                return parent::prepareField($field);

        }
	}
}
