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
	protected $countries = array();

	/**
	 * States based on given country
	 *
	 * @var  array
	 */
	protected $states = array();

	/**
	 * Returns the RedshopHelperWorld object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  RedshopHelperWorld  The RedshopHelperWorld object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Get all the countries supported by shop
	 *
	 * @return  array  Countries
	 */
	public function countries()
	{
		$db = JFactory::getDbo();

		if (!empty($this->countries))
		{
			return $this->countries;
		}

		// Load allowed contries from config
		$countriesList = Redshop::getConfig()->get('COUNTRY_LIST');

		if ($countriesList)
		{
			// Covert them into an array
			$countriesList = explode(',', $countriesList);

			if (!empty($countriesList))
			{
				// Quote them and prepare for query
				array_walk($countriesList, function(&$country, $key, $db) {
						$country = $db->quote($country);
				}, $db);

				$query = $db->getQuery(true)
							->select(
								array(
									$db->qn('country_3_code', 'value'),
									$db->qn('country_name', 'text'),
									$db->qn('country_jtext'),
								)
							)
							->from($db->qn('#__redshop_country'))
							->where($db->qn('country_3_code') . ' IN (' . implode(',', $countriesList) . ')')
							->order($db->qn('country_name'));

				// Set the query and load the result.
				$db->setQuery($query);
				$this->countries = redhelper::getInstance()->convertLanguageString($db->loadObjectList());

				// Check for a database error.
				if ($db->getErrorNum())
				{
					JError::raiseWarning(500, $db->getErrorMsg());

					return null;
				}
			}
		}

		return $this->countries;
	}

	/**
	 * Get states based on coutnry
	 *
	 * @param   string  $country  Country Code
	 *
	 * @return  array             States information
	 */
	public function getStates($country)
	{
		if (array_key_exists($country, $this->states))
		{
			return $this->states[$country];
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select(
				array(
					$db->qn('s.state_2_code', 'value'),
					$db->qn('s.state_name', 'text'),
					$db->qn('c.id'),
					$db->qn('c.country_3_code')
				)
			)
			->from($db->qn('#__redshop_state', 's'))
			->from($db->qn('#__redshop_country', 'c'))
			->where($db->qn('c.id') . ' = ' . $db->qn('s.country_id'))
			->where($db->qn('c.country_3_code') . ' = ' . $db->q($country))
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
		$this->states[$country] = $states;

		return $states;
	}

	/**
	 * Get country dropdown
	 *
	 * @param   array   $post             Information from post data
	 * @param   string  $countryListName  Name of the select element
	 * @param   string  $addressType      Address type. BT or ST
	 * @param   string  $class            Country select list class name
	 *
	 * @return  array                     Country list information
	 */
	public function getCountryList($post = array(), $countryListName = "country_code", $addressType = "BT", $class = "inputbox", $stateListId = "state_code")
	{
		$addressType     = ($addressType == "ST") ? "_ST" : "";
		$countries       = $this->countries();
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

		for ($i = 0; $i < $totalCountries; $i++)
		{
			if ($countries[$i]->value == $selectedCountry)
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
	 * @param   array   $post             $post get from $_POST request
	 * @param   string  $stateListName    State Code from billing or Shipping
	 * @param   string  $addressType      Distinguish billing or shipping
	 * @param   string  $class            Class of state of selected field
	 *
	 * @return array
	 */
	public function getStateList($post = array(), $stateListName = "state_code", $addressType = "BT", $class = "inputbox")
	{
		$selectedCountryCode = Redshop::getConfig()->get('SHOP_COUNTRY');

		if (isset($post['country_code']))
		{
			$selectedCountryCode = $post['country_code'];
		}
		else if (isset($post['country_code_ST']))
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

		$states = $this->getStates($selectedCountryCode);

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
	 * @return  string  JSON encoded string of states list.
	 */
	public function getStatesAjax($countryCode)
	{
		$states = $this->getStates($countryCode);

		if (!empty($states))
		{
			$states = array_merge(
				array(JHtml::_('select.option', '', JText::_("COM_REDSHOP_SELECT"))),
				$states
			);
		}

		return json_encode($states);
	}
}
