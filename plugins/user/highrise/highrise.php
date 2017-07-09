<?php
/**
 * @package     Joomla.User
 * @subpackage  Plugin.RedshopHighrise
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Create Highrise user after creating redSHOP User
 *
 * @package     Joomla.User
 * @subpackage  Plugin.RedshopHighrise
 */
class PlgUserHighrise extends JPlugin
{
	/**
	 * API Access token for highrisehq.com
	 *
	 * @var  string
	 */
	public $apiToken;

	/**
	 * API Access url to connect with highrisehq.com
	 *
	 * @var  string
	 */
	public $apiUrl;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct(&$subject, $config = array())
	{
		$lang          = JFactory::getLanguage();
		$lang->load('plg_user_highrise', JPATH_ADMINISTRATOR);

		parent::__construct($subject, $config);
	}

	/**
	 * Method will trigger after creating redSHOP user
	 *
	 * @param   array    $data   User Information
	 * @param   boolean  $isNew  False if user is in edit mode. True for new user
	 *
	 * @return  void
	 */
	public function onAfterCreateRedshopUser($data, $isNew)
	{
		if (!$isNew)
		{
			return;
		}

		$this->apiUrl   = $this->params->get('apiUrl', '');
		$this->apiToken = $this->params->get('apiToken', '');

		if (!$data['is_company'])
		{
			$request['sLastName'] = $data['lastname'];
		}

		$request['sFirstName'] = $data['firstname'];
		$request['sCompany']   = isset($data['company_name']) ? $data['company_name'] : '';
		$request['sEmail']     = $data['email'];
		$request['sPhone']     = $data['phone'];
		$request['sCity']      = $data['city'];
		$request['sCountry']   = $data['country_code'];
		$request['sState']     = $data['state_code'];
		$request['sStreet']    = $data['address'];
		$request['sZip']       = $data['zipcode'];

		$this->pushContactToHighrise($request);
	}

	/**
	 * Method to push contact information on Highrise
	 *
	 * @param   array  $request  Request user information array
	 *
	 * @return  void
	 */
	public function pushContactToHighrise($request)
	{
		// Check that person doesn't already exist
		if ($this->isHighrisePersonExist($request))
		{
			JError::raiseWarning(21, JText::_('PLG_USER_HIGHRISE_PERSON_EXIST'));

			return;
		}

		$postData = '<person>
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
		</person>';

		$curl = curl_init($this->apiUrl . '/people.xml');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERPWD, $this->apiToken . ':x');

		curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

		$xml   = curl_exec($curl);
		$error = curl_error($CR);
		curl_close($curl);

		if (!empty($error))
		{
			JError::raiseWarning(21, JText::_('PLG_USER_HIGHRISE_CREATE_ERROR'));
		}
		else
		{
			JFactory::getApplication()->enqueueMessage(
				JText::_('PLG_USER_HIGHRISE_CREATE_SUCCESS'),
				'success'
			);
		}
	}

	/**
	 * Check user is exist on highrise
	 *
	 * @param   array   $person  Request data of user information
	 *
	 * @return  boolean  True if person found
	 */
	public function isHighrisePersonExist($person)
	{
		jimport('joomla.http');

		$http = new JHttp(new JRegistry);

		// Set up Curl Headers
		$headers = array(
			'Authorization' => 'Basic ' . base64_encode($this->apiToken . ':x')
		);

		$response = $http->get(
			$this->apiUrl . '/people/search.xml?criteria[email]=' . urlencode($person['sEmail']),
			$headers
		);

		if (200 != $response->code)
		{
			JError::raiseError(403, $response->body);
		}

		$people = simplexml_load_string($response->body);

		$id = false;

		foreach ($people->person as $person)
		{
			if ($person != null)
			{
				$id = $person->id;

				break;
			}
		}

		return $id;
	}
}
