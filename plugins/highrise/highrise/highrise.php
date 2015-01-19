<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
jimport('joomla.event.plugin');

class plghighrisehighrise extends JPlugin
{
	public $api_token = '';

	public $highrise_url = '';

	public $errorMsg = '';

	/**
	 * specific redform plugin parameters
	 *
	 * @param $data
	 */
	public function oncreateHighriseUser($data)
	{
		$plugin = JPluginHelper::getPlugin('highrise', 'highrise');
		$pluginParams = new JRegistry($plugin->params);
		$this->highrise_url = $pluginParams->get('highrise_url', '');
		$this->api_token = $pluginParams->get('api_token', '');

		if (!$data['is_company'])
		{
			$request['sLastName'] = $data['lastname'];
		}

		$request['sFirstName'] = $data['firstname'];
		$request['sCompany'] = $data['company_name'];
		$request['sEmail'] = $data['email'];
		$request['sPhone'] = $data['phone'];
		$request['sCity'] = $data['city'];
		$request['sCountry'] = $data['country_code'];
		$request['sState'] = $data['state_code'];
		$request['sStreet'] = $data['address'];
		$request['sZip'] = $data['zipcode'];
		$this->pushContact($request);
	}

	/**
	 * Method to get debtor contact handle
	 *
	 * @return array
	 */
	public function pushContact($request)
	{
		// Check that person doesn't already exist
		$id = $this->_person_in_highrise($request);

		if ($id < 0)
		{
			$curl = curl_init($this->highrise_url . '/people.xml');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_USERPWD, $this->api_token . ':x');

			curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, '<person>
				<first-name>' . htmlspecialchars($request['sFirstName']) . '</first-name>
				<last-name>' . htmlspecialchars($request['sLastName']) . '</last-name>
				<background>' . htmlspecialchars($request['staff_comment']) . '</background>
				<company-name>' . htmlspecialchars($request['sCompany']) . '</company-name>
				<contact-data>
					<email-addresses>
						<email-address>
							<address>' . htmlspecialchars($request['sEmail']) . '</address>
							<location>Work</location>
						</email-address>
					</email-addresses>
				<phone-numbers>
					<phone-number>
						<number>' . htmlspecialchars($request['sPhone']) . '</number>
						<location>Work</location>
					</phone-number>
				</phone-numbers>
				<addresses>
				    <address>
				      <city>' . htmlspecialchars($request['sCity']) . '</city>
				      <country>' . htmlspecialchars($request['sCountry']) . '</country>
				      <state>' . htmlspecialchars($request['sState']) . '</state>
				      <street>' . htmlspecialchars($request['sStreet']) . '</street>
				      <zip>' . htmlspecialchars($request['sZip']) . '</zip>
				      <location>Work</location>
				    </address>
				  </addresses>
				</contact-data>
			</person>');

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

			$xml = curl_exec($curl);
			$error = curl_error($CR);
			curl_close($curl);

			if (!empty($error))
			{
				$this->errorMsg = "Highrise can not create user";
				JError::raiseWarning(21, $this->errorMsg);
			}
		}
		else
		{
			$this->errorMsg = "Person already in Highrise";
			JError::raiseWarning(21, $this->errorMsg);
		}

		return '';
	}

	/**
	 * Search for a person in Highrise
	 *
	 * @param $person
	 *
	 * @return SimpleXMLElement[]|string
	 */
	public function _person_in_highrise($person)
	{
		$curl = curl_init($this->highrise_url . '/people/search.xml?term=' . urlencode($person['sFirstName'] . ' ' . $person['sLastName']));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		// Username (api token, fake password as per Highrise api)
		curl_setopt($curl, CURLOPT_USERPWD, $this->api_token . ':x');

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

		$xml = curl_exec($curl);
		curl_close($curl);

		// Parse XML
		$people = simplexml_load_string($xml);
		echo($people);
		$id = '-1';

		foreach ($people->person as $person)
		{
			if ($person != null)
			{
				$id = $person->id;
			}
		}

		return $id;
	}
}
