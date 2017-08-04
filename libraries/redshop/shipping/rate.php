<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Shipping.Rate
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
	 * @return  string         Encrypted string.
	 */
	public static function encrypt($data)
	{
		$string = implode('|', $data);

		return str_replace(
			"+",
			" ",
			base64_encode(self::cryptMethod($string))
		);
	}

	/**
	 * Decrypt the passed string
	 *
	 * @param   string  $string  String which needs to decrypt
	 *
	 * @return  array           Decrypted info in array
	 */
	public static function decrypt($string)
	{
		$decrypt = self::cryptMethod(base64_decode(str_replace(' ', '+', $string)));

		return explode('|', $decrypt);
	}

	/**
	 * Logic to encrypt and decrypt
	 *
	 * @param   string  $string  String which needs to be crypt
	 *
	 * @return  string           Crypted string
	 */
	protected static function cryptMethod($string)
	{
		$length    = strlen($string);
		$encrypted = "";

		for ($position = 0; $position < $length; $position++)
		{
			$keyToUse              = (($length + $position) + 1);
			$keyToUse              = (255 + $keyToUse) % 255;
			$byteToBeEncrypted     = substr($string, $position, 1);
			$asciiNumByteToEncrypt = ord($byteToBeEncrypted);
			$xoredByte             = $asciiNumByteToEncrypt ^ $keyToUse;
			$encryptedByte         = chr($xoredByte);
			$encrypted             .= $encryptedByte;
		}

		return $encrypted;
	}

	/**
	 * Delete shipping rate when shipping method is not available
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function removeShippingRate()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT(' . $db->qn('shipping_class') . ')')
			->from($db->qn('#__redshop_shipping_rate'));

		$shippingClasses = $db->setQuery($query)->loadColumn();

		if (empty($shippingClasses))
		{
			return;
		}

		$query->clear()
			->select($db->qn('element'))
			->from($db->qn('#__extensions'))
			->where($db->qn('folder') . ' = ' . $db->quote('redshop_shipping'));

		$shipping = $db->setQuery($query)->loadColumn();

		$differentShipping = array_diff($shippingClasses, $shipping);
		sort($differentShipping);

		if (!empty($differentShipping))
		{
			$query->clear()
				->delete($db->qn('#__redshop_shipping_rate'))
				->where($db->qn('shipping_class') . ' IN (' . implode(',', RedshopHelperUtility::quote($differentShipping)) . ')');
			$db->setQuery($query)->execute();
		}
	}
}
