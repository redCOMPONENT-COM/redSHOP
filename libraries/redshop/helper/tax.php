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
	 * @var array
	 */
	protected static $vatRate = array();

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
			$strVat = productHelper::getInstance()->getApplyVatOrNot($templateData) ?
				Redshop::getConfig()->get('WITH_VAT_TEXT_INFO') : Redshop::getConfig()->get('WITHOUT_VAT_TEXT_INFO');

			$templateData = str_replace("{vat_info}", $strVat, $templateData);
		}

		return $templateData;
	}

	/**
	 * get VAT rates from product or global
	 *
	 * @param   int  $productId  Id current product
	 * @param   int  $userId     Id current user
	 *
	 * @return  object|null      VAT rates information
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getVatRates($productId = 0, $userId = 0)
	{
		$userId      = !$userId ? JFactory::getUser()->id : $userId;
		$productInfo = (object) RedshopHelperProduct::getProductById($productId);
		$taxGroupId  = 0;
		$session     = JFactory::getSession();
		$userData    = RedshopHelperUser::getVatUserInformation($userId);
		$userArr     = $session->get('rs_user');
		$taxGroup    = Redshop::getConfig()->get('DEFAULT_VAT_GROUP');

		if (!empty($userArr))
		{
			if (array_key_exists('vatCountry', $userArr) && !empty($userArr['taxData']))
			{
				if (empty($productInfo->product_tax_group_id))
				{
					$productInfo->product_tax_group_id = Redshop::getConfig()->get('DEFAULT_VAT_GROUP');
				}

				if ($userArr['vatCountry'] == $userData->country_code
					&& $userArr['vatState'] == $userData->state_code
					&& $userArr['vatGroup'] == $productInfo->product_tax_group_id)
				{
					return $userArr['taxData'];
				}
			}
		}

		if (isset($productInfo->product_tax_group_id) && $productInfo->product_tax_group_id > 0)
		{
			$taxGroup = $productInfo->product_tax_group_id;
		}

		$key = $taxGroup . '.' . $userId;

		if (!array_key_exists($key, self::$vatRate))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('tr.*')
				->from($db->qn('#__redshop_tax_rate', 'tr'))
				->leftJoin($db->qn('#__redshop_tax_group', 'tg') . ' ON ' . $db->qn('tg.id') . ' = ' . $db->qn('tr.tax_group_id'))
				->leftJoin($db->qn('#__redshop_country', 'c') . ' ON ' . $db->qn('tr.tax_country') . ' = ' . $db->qn('c.country_3_code')
					. ' AND ' . $db->qn('c.country_3_code') . ' = ' . $db->quote($userData->country_code))
				->leftJoin($db->qn('#__redshop_state', 's') . ' ON ' . $db->qn('tr.tax_state') . ' = ' . $db->qn('s.state_3_code'))
				->where('tg.published = 1')
				->where('tr.tax_country = ' . $db->quote($userData->country_code))
				->where('(s.state_2_code = ' . $db->quote($userData->state_code) . ' OR tr.tax_state = ' . $db->quote('') . ')')
				->where('tr.tax_group_id = ' . (int) $taxGroup)
				->order('tax_rate');

			if (Redshop::getConfig()->get('VAT_BASED_ON') == 2)
			{
				$query->where('tr.is_eu_country = 1');
			}

			self::$vatRate[$key] = $db->setQuery($query)->loadObject();
		}

		$userArr['taxData']    = self::$vatRate[$key];
		$userArr['vatCountry'] = $userData->country_code;
		$userArr['vatState']   = $userData->state_code;

		if (!empty($userArr['taxData']))
		{
			$taxGroupId = $userArr['taxData']->tax_group_id;
		}

		$userArr['vatGroup'] = $taxGroupId;
		$session->set('rs_user', $userArr);

		return self::$vatRate[$key];
	}
}
