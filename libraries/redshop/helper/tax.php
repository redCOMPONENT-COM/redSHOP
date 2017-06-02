<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Text
 *
 * @since  2.0.6
 */
class RedshopHelperTax
{
	/**
	 * Method for replace tags about VAT information
	 *
	 * @param   string  $templateData  Template data.
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public static function replaceVatInformation($templateData)
	{
		if (strpos($templateData, "{vat_info}") !== false)
		{
			$isApplyVAT = productHelper::getInstance()->getApplyVatOrNot($templateData);

			if ($isApplyVAT)
			{
				$strVat = Redshop::getConfig()->get('WITH_VAT_TEXT_INFO');
			}
			else
			{
				$strVat = Redshop::getConfig()->get('WITHOUT_VAT_TEXT_INFO');
			}

			$templateData = str_replace("{vat_info}", $strVat, $templateData);
		}

		return $templateData;
	}
}
