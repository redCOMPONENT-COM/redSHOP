<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Shipping.Rate
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Shipping Rate functions
 *
 * @since  1.6
 */
class RedshopShippingRate
{
	/**
	 * Get encrypted string from an array
	 *
	 * @param   array  $data  Information of shipping which needs to encrypt
	 *
	 * @return  string        Encrypted string.
	 *
	 * @deprecated 2.1.0
	 * @see Redshop\Shipping\Rate::encrypt
	 */
	public static function encrypt($data)
	{
		return Redshop\Shipping\Rate::encrypt($data);
	}

	/**
	 * Decrypt the passed string
	 *
	 * @param   string  $string  String which needs to decrypt
	 *
	 * @return  array            Decrypted info in array
	 *
	 * @deprecated 2.1.0
	 * @see Redshop\Shipping\Rate::decrypt
	 */
	public static function decrypt($string)
	{
		return Redshop\Shipping\Rate::decrypt($string);
	}

	/**
	 * Delete shipping rate when shipping method is not available
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 * @deprecated __DEPLOY_VERSION
	 * @see Redshop\Shipping\Rate::removeShippingRate
	 */
	public static function removeShippingRate()
	{
		Redshop\Shipping\Rate::removeShippingRate();
	}
}
