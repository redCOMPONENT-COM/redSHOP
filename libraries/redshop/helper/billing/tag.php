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
 * Class Redshop Helper Billing Tag
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperBillingTag
{
	/**
	 * Method for replace Billing Address
	 *
	 * @param   string   $content         Template content
	 * @param   object   $billingAddress  Billing data
	 * @param   boolean  $sendMail        Is in send mail?
	 *
	 * @return  mixed
	 * @since   __DEPLOY_VERSION__
	 */
	public static function replaceBillingAddress($content, $billingAddress, $sendMail = false)
	{
		self::replaceBlock($content, $billingAddress);
		self::replaceSingle($content, $billingAddress, $sendMail);

		return str_replace('{billing_address_information_lbl}', JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'), $content);
	}

	/**
	 * Method for replace Billing Address tag {billing_address}
	 *
	 * @param   string   $content         Template content
	 * @param   object   $billingAddress  Billing data
	 * @param   boolean  $sendMail        Is in send mail?
	 *
	 * @return  void
	 * @since   __DEPLOY_VERSION__
	 */
	public static function replaceSingle(&$content, $billingAddress, $sendMail = false)
	{
		if (strpos($content, '{billing_address}') === false)
		{
			return;
		}

		if (null === $billingAddress)
		{
			$content = str_replace('{billing_address}', '', $content);

			return;
		}

		$billingLayout = $sendMail ? 'mail.billing' : 'cart.billing';

		JPluginHelper::importPlugin('redshop_shipping');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$dispatcher->trigger('onBeforeRenderBillingAddress', array(&$billingAddress));

		$billingContent = RedshopLayoutHelper::render(
			$billingLayout,
			array('billingaddresses' => $billingAddress),
			null,
			array('client' => 0)
		);

		if (strpos($content, '{quotation_custom_field_list}') !== false)
		{
			$content = str_replace('{quotation_custom_field_list}', '', $content);

			if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'))
			{
				$billingContent .= RedshopHelperExtrafields::listAllField(
					RedshopHelperExtrafields::SECTION_QUOTATION, $billingAddress->users_info_id
				);
			}
		}
		elseif (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'))
		{
			$content = RedshopHelperExtrafields::listAllField(
				RedshopHelperExtrafields::SECTION_QUOTATION, $billingAddress->users_info_id, '', '', $content
			);
		}

		$content = str_replace('{billing_address}', $billingContent, $content);
	}

	/**
	 * Method for replace Billing Address tags between {billing_address_start} and {billing_address_end}
	 *
	 * @param   string  $content         Template content
	 * @param   object  $billingAddress  Billing data
	 *
	 * @return  void
	 * @since   __DEPLOY_VERSION__
	 */
	public static function replaceBlock(&$content, $billingAddress)
	{
		if (strpos($content, '{billing_address_start}') === false || strpos($content, '{billing_address_end}') === false)
		{
			return;
		}

		$templateStart   = explode('{billing_address_start}', $content);
		$templateEnd     = explode('{billing_address_end}', $templateStart[1]);
		$billingTemplate = $templateEnd[0];

		$extraFields           = '';
		$companyName           = '';
		$companyNameLabel      = '';
		$firstName             = '';
		$firstNameLabel        = '';
		$lastName              = '';
		$lastNameLabel         = '';
		$address               = '';
		$addressLabel          = '';
		$zip                   = '';
		$zipLabel              = '';
		$city                  = '';
		$cityLabel             = '';
		$country               = '';
		$countryLabel          = '';
		$state                 = '';
		$stateLabel            = '';
		$phone                 = '';
		$phoneLabel            = '';
		$email                 = '';
		$emailLabel            = '';
		$vatNumber             = '';
		$vatNumberLabel        = '';
		$eanNumber             = '';
		$eanNumberLabel        = '';
		$taxExempt             = '';
		$taxExemptLabel        = '';
		$taxExemptRequest      = '';
		$taxExemptRequestLabel = '';

		if (null !== $billingAddress)
		{
			if (!empty($billingAddress->firstname))
			{
				$firstName      = $billingAddress->firstname;
				$firstNameLabel = JText::_('COM_REDSHOP_FIRSTNAME');
			}

			if (!empty($billingAddress->lastname))
			{
				$lastName      = $billingAddress->lastname;
				$lastNameLabel = JText::_('COM_REDSHOP_LASTNAME');
			}

			if (!empty($billingAddress->address))
			{
				$address      = $billingAddress->address;
				$addressLabel = JText::_('COM_REDSHOP_ADDRESS');
			}

			if (!empty($billingAddress->zipcode))
			{
				$zip      = $billingAddress->zipcode;
				$zipLabel = JText::_('COM_REDSHOP_ZIP');
			}

			if (!empty($billingAddress->city))
			{
				$city      = $billingAddress->city;
				$cityLabel = JText::_('COM_REDSHOP_CITY');
			}

			$countryName = RedshopHelperOrder::getCountryName($billingAddress->country_code);

			if (!empty($countryName))
			{
				$country      = JText::_($countryName);
				$countryLabel = JText::_('COM_REDSCOM_REDSHOP_COUNTRYHOP_CITY');
			}

			$stateName = RedshopHelperOrder::getStateName($billingAddress->state_code, $billingAddress->country_code);

			if (!empty($stateName))
			{
				$state      = JText::_($stateName);
				$stateLabel = JText::_('COM_REDSHOP_STATE');
			}

			if (!empty($billingAddress->phone))
			{
				$phone      = $billingAddress->phone;
				$phoneLabel = JText::_('COM_REDSHOP_PHONE');
			}

			if (!empty($billingAddress->user_email))
			{
				$email      = $billingAddress->user_email;
				$emailLabel = JText::_('COM_REDSHOP_EMAIL');
			}
			elseif (!empty(JFactory::getUser()->email))
			{
				$email      = JFactory::getUser()->email;
				$emailLabel = JText::_('COM_REDSHOP_EMAIL');
			}

			if ($billingAddress->is_company === 1)
			{
				if (!empty($billingAddress->company_name))
				{
					$companyName      = $billingAddress->company_name;
					$companyNameLabel = JText::_('COM_REDSHOP_COMPANY_NAME');
				}

				if (!empty($billingAddress->vat_number))
				{
					$vatNumber      = $billingAddress->vat_number;
					$vatNumberLabel = JText::_('COM_REDSHOP_VAT_NUMBER');
				}

				if (!empty($billingAddress->ean_number))
				{
					$eanNumber      = $billingAddress->ean_number;
					$eanNumberLabel = JText::_('COM_REDSHOP_EAN_NUMBER');
				}

				if (Redshop::getConfig()->getBool('SHOW_TAX_EXEMPT_INFRONT'))
				{
					$taxExempt = $billingAddress->tax_exempt === 1 ? JText::_('COM_REDSHOP_TAX_YES') : JText::_('COM_REDSHOP_TAX_NO');

					$taxExemptLabel = JText::_('COM_REDSHOP_TAX_EXEMPT');

					$taxExemptRequest = $billingAddress->requesting_tax_exempt === 1 ?
						JText::_('COM_REDSHOP_TAX_YES') : JText::_('COM_REDSHOP_TAX_NO');

					$taxExemptRequestLabel = JText::_('COM_REDSHOP_USER_TAX_EXEMPT_REQUEST_LBL');
				}
			}

			$fieldSection = $billingAddress->is_company === 1 ?
				RedshopHelperExtrafields::SECTION_COMPANY_BILLING_ADDRESS : RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS;

			$extraFields = RedshopHelperExtrafields::listAllFieldDisplay($fieldSection, $billingAddress->users_info_id, 1);
		}

		$tags = array('{companyname}', '{companyname_lbl}', '{firstname}', '{firstname_lbl}', '{lastname}', '{lastname_lbl}', '{address}',
			'{address_lbl}', '{zip}', '{zip_lbl}', '{city}', '{city_lbl}', '{country}', '{country_lbl}', '{state}', '{state_lbl}', '{email}',
			'{email_lbl}', '{phone}', '{phone_lbl}', '{vatnumber}', '{vatnumber_lbl}', '{ean_number}', '{ean_number_lbl}', '{taxexempt}',
			'{taxexempt_lbl}', '{user_taxexempt_request}', '{user_taxexempt_request_lbl}', '{billing_extrafield}');

		$replace = array($companyName, $companyNameLabel, $firstName, $firstNameLabel, $lastName, $lastNameLabel, $address, $addressLabel,
			$zip, $zipLabel, $city, $cityLabel, $country, $countryLabel, $state, $stateLabel, $email, $emailLabel, $phone, $phoneLabel,
			$vatNumber, $vatNumberLabel, $eanNumber, $eanNumberLabel, $taxExempt, $taxExemptLabel, $taxExemptRequest, $taxExemptRequestLabel,
			$extraFields);

		// Merge content
		$content = $templateStart[0] . str_replace($tags, $replace, $billingTemplate) . $templateEnd[1];

		// Remove {billing_address} tag from content if already run through this function
		$content = str_replace('{billing_address}', '', $content);
	}
}
