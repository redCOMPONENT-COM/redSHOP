<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\String;

class Helper
{
	public static function getUserRandomString()
	{
		return md5(\JFactory::getUser()->id . time());
	}
}