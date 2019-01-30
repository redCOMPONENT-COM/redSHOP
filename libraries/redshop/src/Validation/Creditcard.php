<?php
/**
 * @package     Redshop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Validation;

defined('_JEXEC') or die;


/**
 * @package     Redshop\Validation
 *
 * @since       2.1.0
 */
class Creditcard
{
	/**
	 * @param   string $number Number
	 * @param   string $type   Type
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public static function isValid($number, $type)
	{
		$verified = false;

		switch ($type)
		{
			case 'American':
				$denum   = 'American Express';
				$pattern = "/^([34|37]{2})([0-9]{13})$/";
				break;
			case 'Dinners':
				$denum   = 'Diner\'s Club';
				$pattern = "/^([30|36|38]{2})([0-9]{12})$/";
				break;
			case 'Discover':
				$denum   = 'Discover';
				$pattern = "/^([6011]{4})([0-9]{12})$/";
				break;
			case 'Master':
				$denum   = 'Master Card';
				$pattern = "/^([51|52|53|54|55]{2})([0-9]{14})$/";
				break;
			case 'Visa':
				$denum   = 'Visa';
				$pattern = "/^([4]{1})([0-9]{12,15})$/";
				break;
			default:
				$denum   = '';
				$pattern = '';
				break;
		}

		if (empty($pattern))
		{
			return "Credit card invalid. Please make sure that you entered a valid <em>" . $denum . "</em> credit card ";
		}

		if (preg_match($pattern, $number))
		{
			$verified = true;
		}

		if ($verified === false)
		{
			// Do something here in case the validation fails
			return "Credit card invalid. Please make sure that you entered a valid <em>" . $denum . "</em> credit card ";
		}

		return "Your <em>" . $denum . "</em> credit card is valid";
	}
}
