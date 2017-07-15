<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Product Tag
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperProductTag
{
	/**
	 * @var array
	 */
	protected static $productSpecialPrices = array();

	/**
	 * Parse extra fields for template for according to section.
	 *
	 * @param   array    $fieldNames       List of field names
	 * @param   integer  $productId        ID of product
	 * @param   integer  $section          Section
	 * @param   string   $templateContent  Template content
	 * @param   integer  $categoryPage     Argument for product section extra field for category page
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getExtraSectionTag($fieldNames = array(), $productId = 0, $section = 0, $templateContent = '', $categoryPage = 0)
	{
		$fieldName = RedshopHelperTemplate::getExtraFieldsForCurrentTemplate($fieldNames, $templateContent, $categoryPage);

		if (empty($fieldName))
		{
			return $templateContent;
		}

		$templateContent = RedshopHelperExtrafields::extraFieldDisplay($section, $productId, $fieldName, $templateContent, $categoryPage);

		return $templateContent;
	}
}
