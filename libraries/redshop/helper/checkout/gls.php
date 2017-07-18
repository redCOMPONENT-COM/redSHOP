<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.7
 */
defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Cart - Discount
 *
 * @since  2.0.7
 */
class RedshopHelperCheckoutGls
{
	/**
	 * @param   string  $shopId  Shop Id
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function getShopId($shopId)
	{
		$glsMobile = JFactory::getApplication()->input->post->getString('gls_mobile', '');
		$glsZipCode = JFactory::getApplication()->input->post->getString('gls_zipcode', '');

		if (!empty($glsMobile))
		{
			$shopId .= '###' . $glsMobile;
		}

		if (!empty($glsZipCode))
		{
			$shopId .= '###' . $glsZipCode;
		}

		return $shopId;
	}
}