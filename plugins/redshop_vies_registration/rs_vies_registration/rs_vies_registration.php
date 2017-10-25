<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class PlgRedshop_Vies_RegistrationRs_Vies_Registration
 *
 * @since  1.5
 */
class PlgRedshop_Vies_RegistrationRs_Vies_Registration extends JPlugin
{
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
		JHtml::_('redshopjquery.framework');
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_vies_registration_rs_vies_registration', JPATH_ADMINISTRATOR);
		JText::script('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION_STATUS2');
		JText::script('PLG_REDSHOP_VIES_REGISTRATION_VERYFIES_VAT_NUMBER');
		JText::script('PLG_REDSHOP_VIES_REGISTRATION_VALID_VAT_NUMBER');
		JHtml::script('rs_vies_registration/vies.js', false, true);

		parent::__construct($subject, $config);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   array  $element  Array values
	 *
	 * @return  void
	 */
	public function checkviesValidation($element)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('country_2_code'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_3_code') . ' LIKE ' . $db->q($element['country_code']));
		$memberCountryCode = $db->setQuery($query)->loadResult();
		$memberVatNumber = $element['vat_number'];
		$memberRequesterVatNumber = $this->params->get("vat_number");
		$memberRequesterCountryCode = $this->params->get("member_state");

		$client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
		$result = '';
		$mainMsg = '';

		if ($client)
		{
			$params = array(
				'countryCode' => $memberCountryCode,
				'vatNumber' => $memberVatNumber,
				'requesterCountryCode' => $memberRequesterCountryCode,
				'requesterVatNumber' => $memberRequesterVatNumber
			);

			try
			{
				$r = $client->checkVatApprox($params);

				if ($r->valid == true)
				{
					$result = json_encode(true);
				}
				else
				{
					$result = json_encode(
						JText::_('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION')
						. ": " . JText::_('PLG_REDSHOP_VIES_REGISTRATION_INVALID_VAT_NUMBER')
					);
				}
			}
			catch (SoapFault $e)
			{
				$ret = $e->faultstring;

				switch (strtoupper($ret))
				{
					case 'INVALID_INPUT':
						$mainMsg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_COUNTRYCODE_IS_INVALID');
						break;
					case 'SERVICE_UNAVAILABLE':
						$mainMsg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_SOAP_UNAVAILABLE');
						break;
					case 'MS_UNAVAILABLE':
						$mainMsg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_MEMBER_STATE_UNAVAILABLE');
						break;
					case 'TIMEOUT':
						$mainMsg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_MEMBER_STATE_NOT_BE_REACHED');
						break;
					case 'SERVER_BUSY':
						$mainMsg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_SERVICE_CANNOT_PROCESS');
						break;
					default:
						$mainMsg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_INVALID');
						break;
				}

				$result = JText::_('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION') . ": " . $mainMsg;
			}
		}

		echo json_encode($result);

		JFactory::getApplication()->close();
	}
}
