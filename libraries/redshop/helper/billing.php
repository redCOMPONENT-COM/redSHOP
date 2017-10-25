<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Billing Helper
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       2.0.7
 */
class RedshopHelperBilling
{
	/**
	 * Method for render billing layout
	 *
	 * @param   array   $post           Available data.
	 * @param   integer $isCompany      Is company?
	 * @param   array   $lists          Lists
	 * @param   integer $showShipping   Show shipping?
	 * @param   integer $showNewsletter Show newsletter?
	 * @param   integer $createAccount  Is create account?
	 *
	 * @return  string                    HTML content layout.
	 *
	 * @since version
	 */
	public static function render($post = array(), $isCompany = 0, $lists, $showShipping = 0, $showNewsletter = 0,
	                              $createAccount = 1)
	{
		$billingIsShipping = "";

		if ((isset($post['billisship']) && $post['billisship'] == 1)
			|| Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'))
		{
			$billingIsShipping = "checked='checked'";
		}

		$billingTemplate = RedshopHelperTemplate::getTemplate("billing_template");

		if (!empty($billingTemplate) && !empty($billingTemplate[0]->template_desc)
			&& strpos($billingTemplate[0]->template_desc, "private_billing_template:") !== false
			&& strpos($billingTemplate[0]->template_desc, "company_billing_template:") !== false)
		{
			$templateHtml = $billingTemplate[0]->template_desc;
		}
		else
		{
			$templateHtml = self::getDefaultTemplate();
		}

		/*
		 * Billing template for private customer
		 */
		$privateTemplates = RedshopHelperTemplate::getTemplate("private_billing_template");

		if (empty($privateTemplates))
		{
			$tmpTemplate                = new stdClass;
			$tmpTemplate->template_name = 'private_billing_template';
			$tmpTemplate->template_id   = 0;

			$privateTemplates = array($tmpTemplate);
		}

		foreach ($privateTemplates as $privateTemplate)
		{
			if (strpos($templateHtml, "{private_billing_template:" . $privateTemplate->template_name . "}") === false)
			{
				continue;
			}

			$html = '';

			if ($isCompany != 1)
			{
				$html = !empty($privateTemplate->template_desc) ?
					$privateTemplate->template_desc : self::getDefaultPrivateTemplate();

				$html = RsUserHelper::getInstance()->replacePrivateCustomer($html, $post, $lists);
			}

			$html = '<div id="tblprivate_customer">' . $html . '</div>'
				. '<div id="divPrivateTemplateId" style="display:none;">' . $privateTemplate->template_id . '</div>';

			$templateHtml = str_replace(
				'{private_billing_template:' . $privateTemplate->template_name . '}',
				$html,
				$templateHtml
			);

			break;
		}

		/*
		 * Billing template for company customer
		 */
		$companyTemplates = RedshopHelperTemplate::getTemplate("company_billing_template");

		if (empty($companyTemplates))
		{
			$tmpTemplate                = new stdClass;
			$tmpTemplate->template_name = 'company_billing_template';
			$tmpTemplate->template_id   = 0;

			$companyTemplates = array($tmpTemplate);
		}

		foreach ($companyTemplates as $companyTemplate)
		{
			if (strpos($templateHtml, "{company_billing_template:" . $companyTemplate->template_name . "}") === false)
			{
				continue;
			}

			$html = '';

			if ($isCompany == 1)
			{
				$html = !empty($companyTemplate->template_desc) ?
					$companyTemplate->template_desc : self::getDefaultCompanyTemplate();

				$html = RsUserHelper::getInstance()->replaceCompanyCustomer($html, $post, $lists);
			}

			$html = '<div id="tblcompany_customer">' . $html . '</div>'
				. '<div id="divCompanyTemplateId" style="display:none;">' . $companyTemplate->template_id . '</div>';

			$templateHtml = str_replace(
				'{company_billing_template:' . $companyTemplate->template_name . '}',
				$html,
				$templateHtml
			);

			break;
		}

		$templateHtml = str_replace("{required_lbl}", JText::_('COM_REDSHOP_REQUIRED'), $templateHtml);

		if ($showShipping && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			$templateHtml = str_replace(
				'{shipping_same_as_billing_lbl}',
				JText::_('COM_REDSHOP_SHIPPING_SAME_AS_BILLING'),
				$templateHtml
			);

			$html = '<input type="checkbox" id="billisship" name="billisship" value="1" '
				. 'onclick="billingIsShipping(this);" ' . $billingIsShipping . ' />';

			$templateHtml = str_replace('{shipping_same_as_billing}', $html, $templateHtml);
		}
		else
		{
			$templateHtml = str_replace("{shipping_same_as_billing_lbl}", '', $templateHtml);
			$templateHtml = str_replace("{shipping_same_as_billing}", '', $templateHtml);
		}

		if (strpos($templateHtml, "{account_creation_start}") !== false && strpos($templateHtml, "{account_creation_end}") !== false)
		{
			$createAccountHtmlStart = explode('{account_creation_start}', $templateHtml);
			$createAccountHtmlEnd   = explode('{account_creation_end}', $createAccountHtmlStart [1]);
			$createAccountHtml      = '';
			$checkboxStyle          = '';

			if (Redshop::getConfig()->get('REGISTER_METHOD') != 1 && Redshop::getConfig()->get('REGISTER_METHOD') != 3)
			{
				$createAccountHtml = $createAccountHtmlEnd[0];

				if (Redshop::getConfig()->get('REGISTER_METHOD') == 2)
				{
					$checkboxStyle = $createAccount == 1 ? 'style="display:block"' : 'style="display:none"';
				}
				else
				{
					$checkboxStyle = 'style="display:block"';
				}

				$createAccountHtml = str_replace("{username_lbl}", JText::_('COM_REDSHOP_USERNAME_REGISTER'), $createAccountHtml);

				$html              = '<input class="inputbox required" type="text" name="username" id="username" size="32" maxlength="250" value="'
					. (!empty($post["username"]) ? $post['username'] : '') . '" />';
				$createAccountHtml = str_replace("{username}", $html, $createAccountHtml);

				$createAccountHtml = str_replace("{password_lbl}", JText::_('COM_REDSHOP_PASSWORD_REGISTER'), $createAccountHtml);
				$createAccountHtml = str_replace(
					"{password}",
					'<input class="inputbox required" type="password" name="password1" id="password1" size="32" maxlength="250" value="" />',
					$createAccountHtml
				);

				$createAccountHtml = str_replace("{confirm_password_lbl}", JText::_('COM_REDSHOP_CONFIRM_PASSWORD'), $createAccountHtml);

				$createAccountHtml = str_replace("{confirm_password}",
					'<input class="inputbox required" type="password" name="password2" id="password2" size="32" maxlength="250" value="" />',
					$createAccountHtml
				);

				$newsletterSignupLabel     = "";
				$newsletterSignupCheckHtml = "";

				if ($showNewsletter && Redshop::getConfig()->get('NEWSLETTER_ENABLE'))
				{
					$newsletterSignupLabel     = JText::_('COM_REDSHOP_SIGN_UP_FOR_NEWSLETTER');
					$newsletterSignupCheckHtml = '<input type="checkbox" name="newsletter_signup" id="newsletter_signup" value="1">';
				}

				$createAccountHtml = str_replace("{newsletter_signup_lbl}", $newsletterSignupLabel, $createAccountHtml);
				$createAccountHtml = str_replace("{newsletter_signup_chk}", $newsletterSignupCheckHtml, $createAccountHtml);
			}

			$templateHtml = $createAccountHtmlStart[0] . '<div id="tdUsernamePassword" ' . $checkboxStyle . '>' . $createAccountHtml . '</div>' .
				$createAccountHtmlEnd[1];
		}

		$templateHtml .= '<div id="tmpRegistrationDiv" style="display: none;"></div>';

		return $templateHtml;
	}

	/**
	 * Method for replace billing common fields
	 *
	 * @param   string $templateHtml Html content
	 * @param   array  $data         Data
	 * @param   array  $lists        Array select
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceCommonFields($templateHtml, $data, $lists)
	{
		$data = is_null($data) || !is_array($data) ? array() : $data;

		$countries             = RedshopHelperWorld::getCountryList($data);
		$data['country_code']  = $countries['country_code'];
		$lists['country_code'] = $countries['country_dropdown'];
		$states                = RedshopHelperWorld::getStateList($data);
		$lists['state_code']   = $states['state_dropdown'];
		$countryStyle          = (count($countries['countrylist']) == 1 && count($states['statelist']) == 0) ? 'display:none;' : '';
		$stateStyle            = ($states['is_states'] <= 0) ? 'display:none;' : '';

		$readOnly = "";

		$templateHtml = str_replace("{email_lbl}", JText::_('COM_REDSHOP_EMAIL'), $templateHtml);
		$templateHtml = str_replace(
			"{email}",
			'<input class="inputbox required" type="text" title="' . JText::_('COM_REDSHOP_PROVIDE_CORRECT_EMAIL_ADDRESS') . '" name="email1" '
			. 'id="email1" size="32" maxlength="250" value="' . (isset($data["email1"]) ? $data["email1"] : '') . '" />',
			$templateHtml
		);

		if (strstr($templateHtml, "{retype_email_start}") && strstr($templateHtml, "{retype_email_end}"))
		{
			$htmlStart   = explode('{retype_email_start}', $templateHtml);
			$htmlEnd     = explode('{retype_email_end}', $htmlStart[1]);
			$htmlContent = '';

			if (Redshop::getConfig()->get('SHOW_EMAIL_VERIFICATION'))
			{
				$htmlContent = $htmlEnd[0];
				$htmlContent = str_replace("{retype_email_lbl}", JText::_('COM_REDSHOP_RETYPE_CUSTOMER_EMAIL'), $htmlContent);
				$htmlContent = str_replace(
					'{retype_email}',
					'<input type="text" id="email2" name="email2" size="32" maxlength="250" value="" class="inputbox required" required />',
					$htmlContent
				);
			}

			$templateHtml = $htmlStart[0] . $htmlContent . $htmlEnd[1];
		}

		$templateHtml = str_replace("{company_name_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $templateHtml);

		$templateHtml = str_replace(
			"{company_name}",
			'<input class="inputbox required" type="text" name="company_name" id="company_name" size="32" maxlength="250" '
			. 'value="' . (isset($data["company_name"]) ? $data["company_name"] : '') . '" />',
			$templateHtml
		);

		$templateHtml = str_replace("{firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $templateHtml);

		$templateHtml = str_replace(
			"{firstname}",
			'<input class="inputbox required" type="text" name="firstname" id="firstname" size="32" maxlength="250" '
			. 'value="' . (isset($data["firstname"]) ? $data["firstname"] : '') . '" />',
			$templateHtml
		);

		$templateHtml = str_replace("{lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $templateHtml);

		$templateHtml = str_replace(
			"{lastname}",
			'<input class="inputbox required" type="text" name="lastname" id="lastname" size="32" maxlength="250" '
			. 'value="' . (isset($data["lastname"]) ? $data["lastname"] : '') . '" />',
			$templateHtml
		);

		$templateHtml = str_replace("{address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $templateHtml);

		$templateHtml = str_replace(
			"{address}",
			'<input class="inputbox required" type="text" name="address" id="address" size="32" maxlength="250" '
			. 'value="' . (isset($data["address"]) ? $data["address"] : '') . '" />',
			$templateHtml
		);

		$templateHtml = str_replace("{zipcode_lbl}", JText::_('COM_REDSHOP_ZIP'), $templateHtml);

		$templateHtml = str_replace(
			"{zipcode}",
			'<input class="inputbox required"  type="text" name="zipcode" id="zipcode" size="32" maxlength="10" '
			. 'value="' . (isset($data["zipcode"]) ? $data["zipcode"] : '') . '" onblur="return autoFillCity(this.value,\'BT\');" />',
			$templateHtml
		);

		$templateHtml = str_replace("{city_lbl}", JText::_('COM_REDSHOP_CITY'), $templateHtml);

		$templateHtml = str_replace(
			"{city}",
			'<input class="inputbox required" type="text" name="city" ' . $readOnly . ' id="city" '
			. 'value="' . (isset($data["city"]) ? $data["city"] : '') . '" size="32" maxlength="250" />',
			$templateHtml
		);

		// Allow phone number to be optional using template tags.
		$phoneIsRequired = strpos($templateHtml, '{phone_optional}') !== false ? '' : 'required';
		$templateHtml    = str_replace("{phone_optional}", '', $templateHtml);
		$templateHtml    = str_replace(
			"{phone}",
			'<input class="inputbox ' . $phoneIsRequired . '" type="text" name="phone" id="phone" size="32" maxlength="250" '
			. 'value="' . (isset($data["phone"]) ? $data["phone"] : '') . '" onblur="return searchByPhone(this.value,\'BT\');" />',
			$templateHtml
		);

		$templateHtml = str_replace("{phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $templateHtml);
		$templateHtml = str_replace("{country_txtid}", "div_country_txt", $templateHtml);
		$templateHtml = str_replace("{country_style}", $countryStyle, $templateHtml);
		$templateHtml = str_replace("{state_txtid}", "div_state_txt", $templateHtml);
		$templateHtml = str_replace("{state_style}", $stateStyle, $templateHtml);

		$templateHtml = str_replace("{country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $templateHtml);
		$templateHtml = str_replace("{country}", $lists['country_code'], $templateHtml);
		$templateHtml = str_replace("{state_lbl}", JText::_('COM_REDSHOP_STATE'), $templateHtml);
		$templateHtml = str_replace("{state}", $lists['state_code'], $templateHtml);

		return $templateHtml;
	}

	/**
	 * Method for replace private customer billing fields.
	 *
	 * @param   string  $templateHtml  Template content
	 * @param   array   $post          Available data.
	 * @param   array   $lists         Available list data.
	 *
	 * @return  string                 Html content after replace
	 *
	 * @since  2.0.7
	 */
	public static function replacePrivateCustomer($templateHtml = '', $post = array(), $lists = array())
	{
		$templateHtml = self::replaceCommonFields($templateHtml, $post, $lists);

		if (strpos($templateHtml, "{private_extrafield}") === false)
		{
			return $templateHtml;
		}

		$userExtraFields = Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 2 && $lists['extra_field_user'] != "" ?
			$lists['extra_field_user'] : '';

		return str_replace("{private_extrafield}", $userExtraFields, $templateHtml);
	}

	/**
	 * Method for replace company billing fields.
	 *
	 * @param   string  $templateHtml  Template content
	 * @param   array   $post          Available data.
	 * @param   array   $lists         Available list data.
	 *
	 * @return  string                 Html content after replace
	 *
	 * @since  2.0.7
	 */
	public static function replaceCompanyCustomer($templateHtml = '', $post = array(), $lists = array())
	{
		$templateHtml = self::replaceCommonFields($templateHtml, $post, $lists);

		$templateHtml = str_replace("{company_name_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $templateHtml);
		$templateHtml = str_replace(
			"{company_name}",
			'<input class="inputbox required" type="text" name="company_name" id="company_name" size="32" maxlength="250" '
			. 'value="' . (!empty($post["company_name"]) ? $post['company_name'] : '') . '" />',
			$templateHtml
		);
		$templateHtml = str_replace("{ean_number_lbl}", JText::_('COM_REDSHOP_EAN_NUMBER'), $templateHtml);
		$templateHtml = str_replace(
			"{ean_number}",
			'<input class="inputbox" type="text" name="ean_number" id="ean_number" size="32" maxlength="250" '
			. 'value="' . (!empty($post["ean_number"]) ? $post['ean_number'] : '') . '" />',
			$templateHtml
		);

		if (strpos($templateHtml, "{vat_number_start}") !== false && strpos($templateHtml, "{vat_number_end}") !== false)
		{
			$htmlStart  = explode('{vat_number_start}', $templateHtml);
			$htmlEnd    = explode('{vat_number_end}', $htmlStart[1]);
			$htmlMiddle = '';

			if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1)
			{
				$htmlMiddle    = $htmlEnd[0];
				$classRequired = Redshop::getConfig()->get('REQUIRED_VAT_NUMBER') == 1 ? "required" : "";
				$htmlMiddle    = str_replace("{vat_number_lbl}", JText::_('COM_REDSHOP_BUSINESS_NUMBER'), $htmlMiddle);
				$htmlMiddle    = str_replace(
					"{vat_number}",
					'<input type="text" class="inputbox ' . $classRequired . '" name="vat_number" id="vat_number" size="32" maxlength="250" '
					. 'value="' . (!empty($post["vat_number"]) ? $post['vat_number'] : '') . '" />',
					$htmlMiddle
				);
			}

			$templateHtml = $htmlStart[0] . $htmlMiddle . $htmlEnd[1];
		}

		if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1 && Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT'))
		{
			$allowCompany = isset($post['is_company']) && 1 != (int) $post['is_company'] ? 'style="display:none;"' : '';
			$taxExempt    = isset($post["tax_exempt"]) ? $post["tax_exempt"] : '';

			$taxExemptHtml   = JHtml::_(
				'select.booleanlist',
				'tax_exempt',
				'class="inputbox" ',
				$taxExempt,
				JText::_('COM_REDSHOP_COMPANY_IS_VAT_EXEMPTED'),
				JText::_('COM_REDSHOP_COMPANY_IS_NOT_VAT_EXEMPTED')
			);

			$templateHtml = str_replace(
				"{tax_exempt_lbl}",
				'<div id="lblTaxExempt" ' . $allowCompany . '>' . JText::_('COM_REDSHOP_TAX_EXEMPT') . '</div>',
				$templateHtml
			);

			$templateHtml = str_replace("{tax_exempt}", '<div id="trTaxExempt" ' . $allowCompany . '>' . $taxExemptHtml . '</div>', $templateHtml);
		}
		else
		{
			$templateHtml = str_replace("{tax_exempt_lbl}", '', $templateHtml);
			$templateHtml = str_replace("{tax_exempt}", '', $templateHtml);
		}

		if (strpos($templateHtml, "{company_extrafield}") !== false)
		{
			$companyExtraFields = (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 1 && $lists['extra_field_company'] != "") ?
				$lists['extra_field_company'] : "";

			$templateHtml = str_replace("{company_extrafield}", $companyExtraFields, $templateHtml);
		}

		return $templateHtml;
	}

	/**
	 * Method for return default html content
	 *
	 * @return  string   HTML content of default template.
	 *
	 * @since  2.0.7
	 */
	public static function getDefaultTemplate()
	{
		return '<table class="admintable" border="0" cellspacing="0" cellpadding="0"><tbody><tr valign="top"><td>'
			. '{private_billing_template:private_billing_template}{company_billing_template:company_billing_template}'
			. '</td><td>{account_creation_start}<table class="admintable" border="0"><tbody><tr>'
			. '<td width="100" align="right">{username_lbl}</td><td>{username}</td><td><span class="required">*</span>'
			. '</td></tr><tr><td width="100" align="right">{password_lbl}</td><td>{password}</td><td>'
			. '<span class="required">*</span></td></tr><tr><td width="100" align="right">{confirm_password_lbl}</td>'
			. '<td>{confirm_password}</td><td><span class="required">*</span></td></tr><tr>'
			. '<td width="100" align="right">{newsletter_signup_chk}</td><td colspan="2">{newsletter_signup_lbl}</td>'
			. '</tr></tbody></table>{account_creation_end}</td></tr><tr><td colspan="2" align="right">'
			. '<span class="required">*</span>{required_lbl}</td></tr><tr class="trshipping_add">'
			. '<td class="tdshipping_add" colspan="2">{shipping_same_as_billing_lbl} {shipping_same_as_billing}</td>'
			. '</tr></tbody></table>';
	}

	/**
	 * Method for return default html content for private customer
	 *
	 * @return  string   HTML content of default template.
	 *
	 * @since  2.0.7
	 */
	public static function getDefaultPrivateTemplate()
	{
		return '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr>'
			. '<td width="100" align="right">{email_lbl}:</td>' .
			'<td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">' .
			'{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr>' .
			'<td width="100" align="right">{firstname_lbl}</td><td>{firstname}</td><td><span class="required">*</span></td></tr><tr>' .
			'<td width="100" align="right">{lastname_lbl}</td><td>{lastname}</td><td><span class="required">*</span></td></tr><tr>' .
			'<td width="100" align="right">{address_lbl}</td><td>{address}</td><td><span class="required">*</span></td></tr><tr>' .
			'<td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td><td><span class="required">*</span></td></tr><tr>' .
			'<td width="100" align="right">{city_lbl}</td><td>{city}</td><td><span class="required">*</span></td></tr>' .
			'<tr id="{country_txtid}" style="{country_style}"><td width="100" align="right">{country_lbl}</td><td>{country}</td><td>' .
			'<span class="required">*</span></td></tr><tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td>' .
			'<td>{state}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td>' .
			'<span class="required">*</span></td></tr><tr><td colspan="3">{private_extrafield}</td></tr></tbody></table>';
	}

	/**
	 * Method for return default html content for company customer
	 *
	 * @return  string   HTML content of default template.
	 *
	 * @since  2.0.7
	 */
	public static function getDefaultCompanyTemplate()
	{
		return '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr><td width="100" align="right">{email_lbl}:</td>'
			. '<td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">'
			. '{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr>'
			. '<td width="100" align="right">{company_name_lbl}</td><td>{company_name}</td><td><span class="required">*</span></td></tr>'
			. '<!--{vat_number_start} --><tr><td width="100" align="right">{vat_number_lbl}</td><td>{vat_number}</td><td>'
			. '<span class="required">*</span></td></tr><!-- {vat_number_end} --><tr><td width="100" align="right">{firstname_lbl}</td>'
			. '<td>{firstname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_lbl}</td>'
			. '<td>{lastname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_lbl}</td>'
			. '<td>{address}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td>'
			. '<td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_lbl}</td><td>{city}</td><td>'
			. '<span class="required">*</span></td></tr><tr id="{country_txtid}" style="{country_style}">'
			. '<td width="100" align="right">{country_lbl}</td><td>{country}</td><td><span class="required">*</span></td></tr>'
			. '<tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td><td>{state}</td><td>'
			. '<span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td>'
			. '<span class="required">*</span></td></tr><tr><td width="100" align="right">{ean_number_lbl}</td><td>{ean_number}</td><td></td>'
			. '</tr><tr><td width="100" align="right">{tax_exempt_lbl}</td><td>{tax_exempt}</td></tr><tr><td colspan="3">{company_extrafield}</td>'
			. '</tr></tbody></table>';
	}
}
