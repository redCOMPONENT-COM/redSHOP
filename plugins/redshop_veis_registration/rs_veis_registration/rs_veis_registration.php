<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

class plgRedshop_veis_registrationrs_veis_registration extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgRedshop_veis_registrationrs_veis_registration(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_veis_registration', 'rs_veis_registration');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function checkVeisValidation($element, $data)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT country_2_code FROM ' . $this->_table_prefix . 'country ' . 'WHERE country_3_code LIKE "' . $element['country_code'] . '"';
		$db->setQuery($query);
		$member_country_code = $db->loadResult();
		$member_vat_number = $element['vat_number'];
		$member_requester_vat_number = $this->_params->get("vat_number");
		$member_requester_country_code = $this->_params->get("member_state");

		$client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");

		if ($client)
		{
			$params = array('countryCode' => $member_country_code, 'vatNumber' => $member_vat_number, 'requesterCountryCode' => $member_requester_country_code, 'requesterVatNumber' => $member_requester_vat_number);
			try
			{
				$r = $client->checkVatApprox($params);
				$requestIdentifier_num = $r->requestIdentifier;

				if ($r->valid == true)
				{
					echo "<tr>";
					echo "<td>";
					echo JText::_('COM_REDSHOP_VEIS_VALIDATION') . ": ";
					echo "<input type='hidden' name='veis_vat_number' value='" . $requestIdentifier_num . "'>";
					echo "<input type='hidden' name='veis_status' value='valid'>";
					echo "</td>";
					echo "<td>";
					echo "Valid VAT number (" . $requestIdentifier_num . ")";
					echo "</td>";
					echo "</tr>";
				}
				else
				{
					echo "<tr>";
					echo "<td >";
					echo JText::_('COM_REDSHOP_VEIS_VALIDATION') . ": ";
					echo "</td>";
					echo "<td >Invalid VAT number<br>";
					echo "<input type='radio' name='veis_status_invalid' id='veis_status_invalid1' value='0' >&nbsp;" . JText::_('COM_REDSHOP_VEIS_VALIDATION_STATUS1');
					echo "&nbsp;<input type='radio' name='veis_status_invalid' id='veis_status_invalid2' value='1' checked='checked'>&nbsp;" . JText::_('COM_REDSHOP_VEIS_VALIDATION_STATUS2');
					echo "<input type='hidden' name='veis_vat_number' value='' id='veis_vat_number'>";
					echo "<input type='hidden' name='veis_status' value='Invalid' id='veis_status'>";
					echo "</td>";
					echo "</tr>";
				}

			}
			catch (SoapFault $e)
			{
				$ret = $e->faultstring;

				if (strtoupper($ret) == "INVALID_INPUT")
				{
					$main_msg = "The provided CountryCode is invalid or the VAT number is empty.";
				}
				elseif (strtoupper($ret) == "SERVICE_UNAVAILABLE")
				{
					$main_msg = "The SOAP service is unavailable, try again later.";
				}
				elseif (strtoupper($ret) == "MS_UNAVAILABLE")
				{
					$main_msg = "The Member State service is unavailable, try again later or with another Member State.";
				}
				elseif (strtoupper($ret) == "TIMEOUT")
				{
					$main_msg = "The Member State service could not be reached in time, try again later or with another Member State";
				}
				elseif (strtoupper($ret) == "SERVER_BUSY")
				{
					$main_msg = "The service cannot process your request. Try again later.";
				}
				else
				{
					$main_msg = "Invalid";
				}
				echo "<tr>";
				echo "<td >";
				echo JText::_('COM_REDSHOP_VEIS_VALIDATION') . ": ";
				echo "</td><td >";

				if ($main_msg != "")
				{
					echo $main_msg . "<br>";
				}
				echo "<input type='radio' name='veis_status_invalid' id='veis_status_invalid1' value='0' >&nbsp;" . JText::_('COM_REDSHOP_VEIS_VALIDATION_STATUS1');
				echo "&nbsp;<input type='radio' name='veis_status_invalid' id='veis_status_invalid2' value='1' checked='checked'>&nbsp;" . JText::_('COM_REDSHOP_VEIS_VALIDATION_STATUS2');
				echo "<input type='hidden' name='veis_vat_number' value='' id='veis_vat_number'>";
				echo "<input type='hidden' name='veis_status' value='Invalid' id='veis_status'>";
				echo "</td>";
				echo "</tr>";

			}
		}

		exit;
	}
}
