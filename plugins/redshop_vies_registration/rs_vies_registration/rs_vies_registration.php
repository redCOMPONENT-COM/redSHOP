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
 * Class PlgRedshop_Vies_Registrationrs_Vies_Registration
 *
 * @since  1.5
 */
class PlgRedshop_Vies_Registrationrs_Vies_Registration extends JPlugin
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
		$lang = JFactory::getLanguage();
		$lang->load('plg_redshop_vies_registration_rs_vies_registration', JPATH_ADMINISTRATOR);
		JText::script('PLG_REDSHOP_VIES_REGISTRATION_BROWSER_NOT_SUPPORT_XMLHTTP');
		JText::script('PLG_REDSHOP_VIES_REGISTRATION_VERYFIES_VAT_NUMBER');
		JHtml::script('plugins/redshop_vies_registration/rs_vies_registration/js/vies.js');

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
			->select('country_2_code')
			->from($db->qn('#__redshop_country'))
			->where('country_3_code LIKE ' . $db->q($element['country_code']));
		$member_country_code = $db->setQuery($query)->loadResult();
		$member_vat_number = $element['vat_number'];
		$member_requester_vat_number = $this->params->get("vat_number");
		$member_requester_country_code = $this->params->get("member_state");

		$client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");

		if ($client)
		{
			$params = array(
				'countryCode' => $member_country_code,
				'vatNumber' => $member_vat_number,
				'requesterCountryCode' => $member_requester_country_code,
				'requesterVatNumber' => $member_requester_vat_number
			);

			try
			{
				$r = $client->checkVatApprox($params);
				$requestIdentifier_num = $r->requestIdentifier;

				if ($r->valid == true)
				{
					?>
					<td>
						<?php echo JText::_('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION')
							. ": " . JText::sprintf('PLG_REDSHOP_VIES_REGISTRATION_VALID_VAT_NUMBER', $requestIdentifier_num); ?>
						<input type="hidden" name="vies_vat_number" value="<?php echo $requestIdentifier_num; ?>">
						<input type="hidden" name="vies_status" value="valid">
					</td>
					<?php
				}
				else
				{
					?>
					<td>
						<?php echo JText::_('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION')
							. ": " . JText::_('PLG_REDSHOP_VIES_REGISTRATION_INVALID_VAT_NUMBER'); ?><br>
						<label class="radio"><input type="radio" name="vies_status_invalid" id="vies_status_invalid1" value="0" >&nbsp;
							<?php echo JText::_('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION_STATUS1'); ?>
						</label>
						<label class="radio"><input type="radio" name="vies_status_invalid" id="vies_status_invalid2" value="1" checked="checked">&nbsp;
							<?php echo JText::_('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION_STATUS2'); ?>
						</label>
						<input type="hidden" name="vies_vat_number" value="" id="vies_vat_number">
						<input type="hidden" name="vies_status" value="Invalid" id="vies_status">
					</td>
					<?php
				}
			}
			catch (SoapFault $e)
			{
				$ret = $e->faultstring;

				if (strtoupper($ret) == "INVALID_INPUT")
				{
					$main_msg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_COUNTRYCODE_IS_INVALID');
				}
				elseif (strtoupper($ret) == "SERVICE_UNAVAILABLE")
				{
					$main_msg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_SOAP_UNAVAILABLE');
				}
				elseif (strtoupper($ret) == "MS_UNAVAILABLE")
				{
					$main_msg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_MEMBER_STATE_UNAVAILABLE');
				}
				elseif (strtoupper($ret) == "TIMEOUT")
				{
					$main_msg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_MEMBER_STATE_NOT_BE_REACHED');
				}
				elseif (strtoupper($ret) == "SERVER_BUSY")
				{
					$main_msg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_SERVICE_CANNOT_PROCESS');
				}
				else
				{
					$main_msg = JText::_('PLG_REDSHOP_VIES_REGISTRATION_INVALID');
				}

				?>
				<td>
					<?php echo JText::_('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION') . ": "; ?>
					<?php
					if ($main_msg != "")
					{
						echo $main_msg . "<br>";
					}
					?>
					<label class="radio"><input type="radio" name="vies_status_invalid" id="vies_status_invalid1" value="0" >&nbsp;
						<?php echo JText::_('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION_STATUS1'); ?>
					</label>
					<label class="radio"><input type="radio" name="vies_status_invalid" id="vies_status_invalid2" value="1" checked="checked">&nbsp;
						<?php echo JText::_('PLG_REDSHOP_VIES_REGISTRATION_VALIDATION_STATUS2'); ?>
					</label>
					<input type="hidden" name="vies_vat_number" value="" id="vies_vat_number">
					<input type="hidden" name="vies_status" value="Invalid" id="vies_status">
				</td>
				<?php
			}
		}

		exit;
	}
}
