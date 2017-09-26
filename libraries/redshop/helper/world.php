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
 * Class Redshop Helper to get world countries and states
 *
 * @since  1.6.1
 */
class RedshopHelperWorld
{
	/**
	 * Static instance of class
	 *
	 * @var  null
	 */
	protected static $instance = null;

	/**
	 * Countries supported in shop
	 *
	 * @var  array
	 */
	protected static $countries = array();

	/**
	 * States based on given country
	 *
	 * @var  array
	 */
	protected static $states = array();

	/**
	 * Returns the RedshopHelperWorld object, only creating it
	 * if it does not already exist.
	 *
	 * @return  RedshopHelperWorld  The RedshopHelperWorld object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Get all the countries supported by shop
	 *
	 * @return  array  Countries
	 */
	public static function countries()
	{
		if (!empty(self::$countries))
		{
			return self::$countries;
		}

		$db = JFactory::getDbo();

		// Load allowed countries from config
		$countries = Redshop::getConfig()->get('COUNTRY_LIST');

		if (!empty($countries))
		{
			// Covert them into an array
			$countries = explode(',', $countries);

			if (!empty($countries))
			{
				// Quote them and prepare for query
				$countries = self::quoteArray($countries);

				$query = $db->getQuery(true)
						->select(
							array(
								$db->qn('country_3_code', 'value'),
								$db->qn('country_name', 'text'),
								$db->qn('country_jtext'),
							)
						)
						->from($db->qn('#__redshop_country'))
						->where($db->qn('country_3_code') . ' IN (' . implode(',', $countries) . ')')
						->order($db->qn('country_name'));

				// Set the query and load the result.
				$db->setQuery($query);

				self::$countries = RedshopHelperUtility::convertLanguageString($db->loadObjectList());

				// Check for a database error.
				if ($db->getErrorNum())
				{
					JError::raiseWarning(500, $db->getErrorMsg());

					return null;
				}
			}
		}

		return self::$countries;
	}

	/**
	 * Get states based on country
	 *
	 * @param   string  $country     Country Code
	 * @param   string  $fieldValue  State field column for value
	 *
	 * @return  array             States information
	 */
	public static function getStates($country, $fieldValue = 'state_2_code')
	{
		$key = $country . '_' . $fieldValue;

		if (array_key_exists($key, self::$states))
		{
			return self::$states[$key];
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select(
				array(
					$db->qn('s.' . $fieldValue, 'value'),
					$db->qn('s.state_name', 'text')
				)
			)
			->from($db->qn('#__redshop_state', 's'))
			->leftJoin($db->qn('#__redshop_country', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('s.country_id'))
			->where($db->qn('c.country_3_code') . ' = ' . $db->quote($country))
			->order($db->qn('s.state_name'));

		// Set the query and load the result.
		$db->setQuery($query);
		$states = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return null;
		}

		// Store in states array
		self::$states[$key] = $states;

		return self::$states[$key];
	}

	/**
	 * Get country dropdown
	 *
	 * @param   array   $post             Information from post data
	 * @param   string  $countryListName  Name of the select element
	 * @param   string  $addressType      Address type. BT or ST
	 * @param   string  $class            Country select list class name
	 * @param   string  $stateListId      State list.
	 *
	 * @return  array                     Country list information
	 */
	public static function getCountryList($post = array(), $countryListName = "country_code", $addressType = "BT", $class = "inputbox",
		$stateListId = "state_code")
	{
		$addressType     = ($addressType == "ST") ? "_ST" : "";
		$countries       = self::countries();
		$totalCountries  = count($countries);
		$selectedCountry = Redshop::getConfig()->get('SHOP_COUNTRY');

		if ($totalCountries == 1)
		{
			$selectedCountry = $countries[0]->value;
		}

		if (isset($post['country_code' . $addressType]))
		{
			$selectedCountry = $post['country_code' . $addressType];
		}

		// Only offer please select hint if more than one countries.
		if ($totalCountries > 1)
		{
			$countries = array_merge(
				array(JHtml::_('select.option', '', JText::_('COM_REDSHOP_SELECT'))),
				$countries
			);
		}

		$countryCode = '';

		foreach ($countries as $country)
		{
			if ($country->value == $selectedCountry)
			{
				$countryCode = $selectedCountry;

				break;
			}
		}

		return array(
			'countrylist'                 => $countries,
			'country_code' . $addressType => $countryCode,
			'country_dropdown'            => JHTML::_(
				'select.genericlist',
				$countries,
				$countryListName,
				array('class' => $class, 'stateId' => 'rs_state_' . $stateListId),
				'value',
				'text',
				$selectedCountry,
				'rs_country_' . $countryListName
			)
		);
	}

	/**
	 * This function will get state list from country code and return HTML of state (both billing and shipping)
	 *
	 * @param   array   $post           $post get from $_POST request
	 * @param   string  $stateListName  State Code from billing or Shipping
	 * @param   string  $addressType    Distinguish billing or shipping
	 * @param   string  $class          Class of state of selected field
	 * @param   string  $fieldValue     Field column for value
	 *
	 * @return array
	 */
	public static function getStateList($post = array(), $stateListName = "state_code", $addressType = "BT", $class = "inputbox",
		$fieldValue = 'state_2_code')
	{
		$selectedCountryCode = Redshop::getConfig()->get('SHOP_COUNTRY');

		if (isset($post['country_code']))
		{
			$selectedCountryCode = $post['country_code'];
		}
		elseif (isset($post['country_code_ST']))
		{
			$selectedCountryCode = $post['country_code_ST'];
		}

		$selectedStateCode = "";

		if (isset($post['state_code']))
		{
			$selectedStateCode = $post['state_code'];
		}
		elseif (isset($post['state_code_ST']))
		{
			$selectedStateCode = $post['state_code_ST'];
		}

		$states = self::getStates($selectedCountryCode, $fieldValue);

		$totalStates = count($states);

		if ($totalStates > 1)
		{
			$states = array_merge(
				array(JHtml::_('select.option', '', JText::_("COM_REDSHOP_SELECT"))),
				$states
			);
		}

		return array(
			'statelist'      => $states,
			'is_states'      => $totalStates,
			'state_dropdown' => JHTML::_(
				'select.genericlist',
				$states,
				$stateListName,
				array('class' => $class),
				'value',
				'text',
				$selectedStateCode,
				'rs_state_' . $stateListName
			)
		);
	}

	/**
	 * AJAX Task to get states list
	 *
	 * @param   string  $countryCode  Country code.
	 *
	 * @return  string                JSON encoded string of states list.
	 */
	public static function getStatesAjax($countryCode)
	{
		$states = self::getStates($countryCode);

		if (!empty($states))
		{
			$states = array_merge(
				array(JHtml::_('select.option', '', JText::_("COM_REDSHOP_SELECT"))),
				$states
			);
		}

		return json_encode($states);
	}

	/**
	 * Method for quote array
	 *
	 * @param   array  $list  List of item for quote.
	 *
	 * @return  array
	 *
	 * @since  2.0.3
	 */
	protected static function quoteArray($list = array())
	{
		if (empty($list) || !is_array($list))
		{
			return array();
		}

		$db = JFactory::getDbo();

		foreach ($list as $key => $item)
		{
			$list[$key] = $db->quote($item);
		}

		return $list;
	}

	/**
	 * Method to get Country ID by country 3 code.
	 *
	 * @param   int  $country3code  Country 3 code
	 *
	 * @return  int
	 *
	 * @since   2.0.6
	 */
	public static function getCountryId($country3code)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn('id'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_3_code') . ' LIKE ' . $db->quote($country3code));

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Method to get Country 2 code by Country 3 code.
	 *
	 * @param   int  $country3code  Country 3 code
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public static function getCountryCode2($country3code)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn('country_2_code'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_3_code') . ' LIKE ' . $db->quote($country3code));

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Method to get State code 2 by State code 3.
	 *
	 * @param   int  $stateCode  State 3 code
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public static function getStateCode2($stateCode)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn('state_2_code'))
			->from($db->qn('#__redshop_state'))
			->where($db->qn('state_3_code') . ' LIKE ' . $db->quote($stateCode));

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Method for get State Code
	 *
	 * @param   int     $id         ID of state.
	 * @param   string  $stateCode  State code 2
	 *
	 * @return  string
	 *
	 * @since   2.0.6
	 */
	public static function getStateCode($id, $stateCode)
	{
		if (empty($stateCode))
		{
			return null;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn(array('state_3_code', 'show_state')))
			->from($db->qn('#__redshop_state'))
			->where($db->qn('state_2_code') . ' LIKE ' . $db->quote($stateCode))
			->where($db->qn('id') . ' = ' . (int) $id);

		$result = $db->setQuery($query)->loadObject();

		if ($result && $result->show_state == 3)
		{
			return $result->state_3_code;
		}

		return $stateCode;
	}
}
