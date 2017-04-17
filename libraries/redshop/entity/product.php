<?php
/**
 * @package     Redshop.Library
 * @subpackage  Entity
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Product Entity
 *
 * @package     Redshop.Library
 * @subpackage  Entity
 * @since       2.0.4
 */
final class RedshopEntityProduct extends RedshopEntity
{

	public function processBeforeSaving(&$item = array())
	{
		// Temporary code while moving to jform fields
		$item = array_merge($item, $item['jform']);
		unset($item['jform'] );

		return true;
	}

	public function processAfterSaving(&$table)
	{

	}
}