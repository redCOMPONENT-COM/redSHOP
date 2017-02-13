<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Helper for mod_redmanufacturer
 *
 * @since  1.6.1
 */
abstract class ModRedshopCurrenciesHelper
{
	/**
	 * Retrieve a list of article
	 *
	 * @param   \Joomla\Registry\Registry  &$params  Module parameters
	 *
	 * @return  mixed
	 */
	public static function getList(&$params)
	{
		$db = JFactory::getDbo();
		$currencies = $params->get('product_currency', '');

		$currenciess = array();

		if ($currencies)
		{
			$query = $db->getQuery(true)
				->select($db->qn(array('currency_id', 'currency_code', 'currency_name')))
				->from($db->qn('#__redshop_currency'))
				->where($db->qn('currency_code') . ' IN (' . implode(',', redhelper::quote($currencies)) . ')')
				->order($db->qn('currency_name'));
			$db->setQuery($query);
			$currenciess = $db->loadObjectList();
		}

		for ($i = 0, $in = count($currenciess); $i < $in; $i++)
		{
			$currencies[$currenciess[$i]->currency_code] = $currenciess[$i]->currency_name;
		}

		$session = JFactory::getSession();
		$jinput = JFactory::getApplication()->input;

		$productCurrency = $jinput->post->get('product_currency', '');

		if (!empty($productCurrency))
		{
			$session->set('product_currency', $productCurrency);
		}

		return $currencies;
	}
}
