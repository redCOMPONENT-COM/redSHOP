<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Utility helper
 *
 * @since  2.0.7
 */
class Utility
{
	/**
	 * This function is for check captcha code
	 *
	 * @param   string   $data            The answer
	 * @param   boolean  $displayWarning  Display warning or not.
	 *
	 * @return  boolean
	 * @throws  \Exception
	 *
	 * @since   2.0.7
	 */
	public static function checkCaptcha($data, $displayWarning = true)
	{
		$default = \JFactory::getConfig()->get('captcha');

		if (\JFactory::getApplication()->isSite())
		{
			$default = \JFactory::getApplication()->getParams()->get('captcha', \JFactory::getConfig()->get('captcha'));
		}

		if (empty($default))
		{
			return true;
		}

		$captcha = \JCaptcha::getInstance($default, array('namespace' => 'redshop'));

		if ($captcha != null && !$captcha->checkAnswer($data))
		{
			if ($displayWarning)
			{
				\JFactory::getApplication()->enqueueMessage(\JText::_('COM_REDSHOP_INVALID_SECURITY'), 'error');
			}

			return false;
		}

		return true;
	}

	/**
	 * Function which will return product tag array form  given template
	 *
	 * @param   integer $section      Display warning or not.
	 * @param   string  $templateHtml Display warning or not.
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function getProductTags($section = \RedshopHelperExtrafields::SECTION_PRODUCT, $templateHtml = '')
	{
		if (empty($templateHtml))
		{
			return array();
		}

		$db     = \JFactory::getDbo();
		$query  = $db->getQuery(true)
			->select($db->qn('name'))
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('section') . ' = ' . (int) $section);
		$fields = $db->setQuery($query)->loadColumn();

		if (empty($fields))
		{
			return array();
		}

		$templateHtml = explode("{", $templateHtml);

		if (empty($templateHtml))
		{
			return array();
		}

		$results = array();

		foreach ($templateHtml as $tmp)
		{
			$word = explode('}', $tmp);

			if (in_array($word[0], $fields))
			{
				$results[] = $word[0];
			}
		}

		return $results;
	}

	/**
	 * Method for convert Unit
	 *
	 * @param   string  $globalUnit  Base conversation unit
	 * @param   string  $calcUnit    Unit ratio which to convert
	 *
	 * @return  float                Unit ratio
	 *
	 * @since   2.1.0
	 */
	public static function getUnitConversation($globalUnit, $calcUnit)
	{
		if (empty($globalUnit) || empty($calcUnit) || $globalUnit == $calcUnit)
		{
			return 1.0;
		}

		switch ($calcUnit)
		{
			case "mm": // Millimeters
				switch ($globalUnit)
				{
					case "cm":
						return 0.1;

					case "m":
						return 0.001;

					case "inch":
						return 0.0393700787;

					case "feet":
						return 0.0032808399;

					default:
						return 1.0;
				}

				break;

			case "cm": // Centimeters
				switch ($globalUnit)
				{
					case "mm":
						return 10;

					case "m":
						return 0.01;

					case "inch":
						return 0.393700787;

					case "feet":
						return 0.032808399;

					default:
						return 1;
				}

				break;

			case "m": // Meters
				switch ($globalUnit)
				{
					case "mm":
						return 1000;

					case "cm":
						return 100;

					case "inch":
						return 39.3700787;

					case "feet":
						return 3.2808399;

					default:
						return 1;
				}

				break;

			case "inch": // Inches
				switch ($globalUnit)
				{
					case "mm":
						return 25.4;

					case "cm":
						return 2.54;

					case "m":
						return 0.0254;

					case "feet":
						return 0.0833333333;

					default:
						return 1;
				}

				break;

			case "feet": // Feets
				switch ($globalUnit)
				{
					case "mm":
						return 304.8;

					case "cm":
						return 30.48;

					case "m":
						return 0.3048;

					case "inch":
						return 12;

					default:
						return 1;
				}

				break;

			case "kg": // Kilograms
				switch ($globalUnit)
				{
					case "pounds":
					case "lbs":
						return 2.20462262;

					case "gram":
						return 1000;

					default:
						return 1;
				}

				break;

			case "pounds": // UK Pounds
			case "lbs":
				switch ($globalUnit)
				{
					case "gram":
						return 453.59237;

					case "kg":
						return 0.45359237;

					default:
						return 1;
				}

				break;

			case "gram":
				switch ($globalUnit)
				{
					case "pounds":
					case "lbs":
						return 0.00220462262;

					case "kg":
						return 0.001;

					default:
						return 1;
				}

				break;

			default:
				return 1;
		}
	}

	/**
	 * Method to get string between inputs
	 *
	 * @param   string  $start   Starting string where you need to start search
	 * @param   string  $end     Ending string where you need to end search
	 * @param   string  $string  Target string from where need to search
	 *
	 * @return  array            Matched string array
	 *
	 * @since   2.1.0
	 */
	public static function findStringBetween($start, $end, $string)
	{
		preg_match_all('/' . preg_quote($start, '/') . '([^\.)]+)' . preg_quote($end, '/') . '/i', $string, $m);

		return $m[1];
	}
}
