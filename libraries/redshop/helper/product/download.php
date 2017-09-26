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
 * Class Redshop Helper Product Download
 *
 * @since  2.0.7
 */
class RedshopHelperProductDownload
{
	/**
	 * Method to check product is downloadable or else
	 *
	 * @param   integer $productId Product Id
	 * @param   boolean $return    If yes, return object. False return number of download
	 *
	 * @return  object|integer
	 *
	 * @since   2.0.7
	 */
	public static function checkDownload($productId, $return = false)
	{
		if (!$productId)
		{
			return !$return ? 0 : null;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn(
				array(
					'product_download', 'product_download_days', 'product_download_limit', 'product_download_clock',
					'product_download_clock_min', 'product_download_infinite')
				)
			)
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_id') . ' = ' . (int) $productId);

		$result = $db->setQuery($query)->loadObject();

		if ($return)
		{
			return $result;
		}

		return !$result ? 0 : (int) $result->product_download;
	}
}
