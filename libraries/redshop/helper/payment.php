<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Payment Methods
 *
 * @since  1.5
 */
class RedshopHelperPayment
{
	/**
	 * Check for specific payment group type plugin - suffixed using given `type`
	 * Specially Checking for suffixed using `rs_payment_banktransfer` plugin
	 *
	 * @param   string  $name        Payment Plugin Element Name
	 * @param   string  $typeSuffix  Suffix to match
	 *
	 * @return  boolean  True when position found else false
	 */
	public static function isPaymentType($name, $typeSuffix = 'rs_payment_banktransfer')
	{
		$position = strpos($name, $typeSuffix);

		// Return false when given suffix is not found in string
		if ($position === false)
		{
			return false;
		}

		// True when position found
		return true;
	}
}
