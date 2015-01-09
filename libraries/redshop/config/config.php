<?php
/**
 * @package     Redshop.Library
 * @subpackage  Config
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Redshop Configuration
 *
 * @package     Redshop.Library
 * @subpackage  Config
 * @since       1.5
 */
class RedshopConfig
{
	/**
	 * javascript strings for configuration variables
	 *
	 * @var    array
	 * @since  1.5
	 */
	protected static $jsStrings = array();

	/**
	 * Stores redshop configuration strings in the JavaScript language store.
	 *
	 * @param   string  $key    The Javascript config string key.
	 * @param   string  $value  The Javascript config string value.
	 *
	 * @return  string
	 *
	 * @since   1.5
	 */
	public static function script($key = null, $value = null)
	{
		// Add the key to the array if not null.
		if ($key !== null)
		{
			// Assign key to the value
			self::$jsStrings[strtoupper($key)] = $value;
		}

		return self::$jsStrings;
	}

	/**
	 * Set javascript strings
	 *
	 * @return  void
	 */
	public static function scriptDeclaration()
	{
		JFactory::getDocument()->addScriptDeclaration('
			(function() {
				var RedshopStrings = ' . json_encode(self::script()) . ';
				if (typeof redSHOP == "undefined") {
					redSHOP = {};
					redSHOP.RSConfig.strings = RedshopStrings;
				}
				else {
					redSHOP.RSConfig.load(RedshopStrings);
				}
			})();
		');
	}
}
