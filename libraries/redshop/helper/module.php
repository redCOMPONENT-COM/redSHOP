<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.0.6
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Module
 *
 * @since  2.0.0.6
 */
class RedshopHelperModule
{
	/**
	 * Load payment languages
	 *
	 * @return   void
	 *
	 * @since   2.0.3
	 */
	public static function loadLanguages()
	{
		// Load modules language file
		$paymentsLangList = RedshopHelperUtility::getModules(-1);
		$language         = JFactory::getLanguage();

		foreach ($paymentsLangList as $paymentLang)
		{
			$extension = $paymentLang->element;
			$language->load($extension, JPATH_SITE, $language->getTag(), true);
			$language->load($extension, JPATH_ADMINISTRATOR, $language->getTag(), true);
			$language->load($extension, JPATH_ROOT . '/modules/' . $extension, $language->getTag(), true);
		}
	}
}
