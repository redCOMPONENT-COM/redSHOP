<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

defined('_JEXEC') or die;

/**
 * Mail User helper
 *
 * @since  2.1.0
 */
class User
{
	/**
	 * Send registration mail
	 *
	 * @param   array  $data  Registration data
	 *
	 * @return  boolean
	 *
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function sendRegistrationMail(&$data)
	{
		$mailSection  = "register";
		$mailTemplate = Helper::getTemplate(0, $mailSection);

		if (empty($mailTemplate))
		{
			return false;
		}

		$app = \JFactory::getApplication();

		$mailTemplate = $mailTemplate[0];
		$mainPassword = $app->input->post->getString('password1');
		$mailFrom     = $app->get('mailfrom');
		$fromName     = $app->get('fromname');

		// Time for the email magic so get ready to sprinkle the magic dust...
		$mailBcc = array();

		$mailData    = $mailTemplate->mail_body;
		$mailSubject = $mailTemplate->mail_subject;

		if (trim($mailTemplate->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailTemplate->mail_bcc);
		}

		$search   = array();
		$replace  = array();
		$search[] = "{shopname}";
		$search[] = "{firstname}";
		$search[] = "{lastname}";
		$search[] = "{fullname}";
		$search[] = "{name}";
		$search[] = "{username}";
		$search[] = "{password}";
		$search[] = "{email}";
		$search[] = '{account_link}';

		$replace[] = \Redshop::getConfig()->get('SHOP_NAME');
		$replace[] = $data['firstname'];
		$replace[] = $data['lastname'];
		$replace[] = $data['firstname'] . " " . $data['lastname'];
		$replace[] = $data['name'];
		$replace[] = $data['username'];
		$replace[] = $mainPassword;
		$replace[] = $data['email'];
		$replace[] = '<a href="' . \JUri::root() . 'index.php?option=com_redshop&view=account'
			. '" target="_blank">' . \JText::_('COM_REDSHOP_ACCOUNT_LINK') . '</a>';

		$mailBody = str_replace($search, $replace, $mailData);

		Helper::imgInMail($mailBody);

		$mailSubject = str_replace($search, $replace, $mailSubject);

		$bcc = array();

		if ($mailBody && $data['email'] != "")
		{
			if (trim(\Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')) != '')
			{
				$bcc = explode(",", trim(\Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));
			}

			$bcc = array_merge($bcc, $mailBcc);
			Helper::sendEmail($mailFrom, $fromName, $data['email'], $mailSubject, $mailBody, true, null, $bcc, null, $mailSection, func_get_args());
		}

		// Tax exempt waiting approval mail
		if (\Redshop::getConfig()->get('USE_TAX_EXEMPT') && $data['tax_exempt'] == 1)
		{
			self::sendTaxExempt("tax_exempt_waiting_approval_mail", $data, $bcc);
		}

		return true;
	}

	/**
	 * Send request tax exempt mail
	 *
	 * @param   object $data     Mail data
	 * @param   string $username Username
	 *
	 * @return  boolean
	 *
	 * @since   2.1.0
	 */
	public static function sendRequestTaxExempt($data, $username = "")
	{
		if (empty(\Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')))
		{
			return false;
		}

		$mailSection = "request_tax_exempt_mail";
		$mailInfo    = Helper::getTemplate(0, $mailSection);
		$dataAdd     = "";
		$subject     = "";
		$mailBcc     = null;

		if (count($mailInfo) > 0)
		{
			$dataAdd = $mailInfo[0]->mail_body;
			$subject = $mailInfo[0]->mail_subject;

			if (trim($mailInfo[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
			}
		}

		$config      = \JFactory::getConfig();
		$from        = $config->get('mailfrom');
		$fromName    = $config->get('fromname');
		$stateName   = \RedshopHelperOrder::getStateName($data->state_code);
		$countryName = \RedshopHelperOrder::getCountryName($data->country_code);

		$dataAdd = str_replace("{vat_number}", $data->vat_number, $dataAdd);
		$dataAdd = str_replace("{username}", $username, $dataAdd);
		$dataAdd = str_replace("{company_name}", $data->company_name, $dataAdd);
		$dataAdd = str_replace("{country}", $countryName, $dataAdd);
		$dataAdd = str_replace("{state}", $stateName, $dataAdd);
		$dataAdd = str_replace("{phone}", $data->phone, $dataAdd);
		$dataAdd = str_replace("{zipcode}", $data->zipcode, $dataAdd);
		$dataAdd = str_replace("{address}", $data->address, $dataAdd);
		$dataAdd = str_replace("{city}", $data->city, $dataAdd);

		Helper::imgInMail($dataAdd);

		$sendTo = explode(",", trim(\Redshop::getConfig()->get('ADMINISTRATOR_EMAIL')));

		return Helper::sendEmail($from, $fromName, $sendTo, $subject, $dataAdd, 1, null, $mailBcc, null, $mailSection, func_get_args());
	}

	/**
	 * Send subscriptions re-new mail
	 *
	 * @param   array $data Mail data
	 *
	 * @return  boolean
	 *
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function sendSubscriptionRenewal($data = array())
	{
		$mailSection  = "subscription_renewal_mail";
		$mailTemplate = Helper::getTemplate(0, $mailSection);

		if (empty($mailTemplate))
		{
			return false;
		}

		$app           = \JFactory::getApplication();
		$productHelper = \productHelper::getInstance();

		$mailTemplate = $mailTemplate[0];
		$data         = (object) $data;
		$mailFrom     = $app->get('mailfrom');
		$fromName     = $app->get('fromname');
		$mailBcc      = null;

		$mailData    = $mailTemplate->mail_body;
		$mailSubject = $mailTemplate->mail_subject;

		if (trim($mailTemplate->mail_bcc) != "")
		{
			$mailBcc = explode(",", $mailTemplate->mail_bcc);
		}

		$userData = \RedshopHelperOrder::getBillingAddress($data->user_id);

		if (!$userData)
		{
			return false;
		}

		$userEmail = $userData->user_email;
		$firstName = $userData->firstname;
		$lastName  = $userData->lastname;

		$product             = \Redshop::product((int) $data->product_id);
		$productSubscription = $productHelper->getProductSubscriptionDetail($data->product_id, $data->subscription_id);

		$search   = array();
		$replace  = array();
		$search[] = "{shopname}";
		$search[] = "{firstname}";
		$search[] = "{lastname}";
		$search[] = "{product_name}";
		$search[] = "{subsciption_enddate}";
		$search[] = "{subscription_period}";
		$search[] = "{subscription_price}";
		$search[] = "{product_link}";

		$replace[] = \Redshop::getConfig()->get('SHOP_NAME');
		$replace[] = $firstName;
		$replace[] = $lastName;
		$replace[] = $product->product_name;
		$replace[] = \RedshopHelperDatetime::convertDateFormat($data->end_date);
		$replace[] = $productSubscription->subscription_period . " " . $productSubscription->period_type;
		$replace[] = \RedshopHelperProductPrice::formattedPrice($productSubscription->subscription_price);

		$productUrl = \JUri::root() . 'index.php?option=com_redshop&view=product&pid=' . $data->product_id;

		$replace[] = "<a href='" . $productUrl . "'>" . $product->product_name . "</a>";

		$mailData = str_replace($search, $replace, $mailData);

		Helper::imgInMail($mailData);

		$mailSubject = str_replace($search, $replace, $mailSubject);

		return Helper::sendEmail($mailFrom, $fromName, $userEmail, $mailSubject, $mailData, true,
			null, $mailBcc, null, $mailSection, func_get_args()
		);
	}

	/**
	 * Send tax exempt mail
	 *
	 * @param   string $mailSection Mail section
	 * @param   array  $userInfo    User info data
	 * @param   string $email       User email
	 *
	 * @return  boolean
	 *
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function sendTaxExempt($mailSection, $userInfo = array(), $email = "")
	{
		if (\Redshop::getConfig()->getBool('USE_TAX_EXEMPT') == false)
		{
			return false;
		}

		$app          = \JFactory::getApplication();
		$mailFrom     = $app->get('mailfrom');
		$fromName     = $app->get('fromname');
		$mailBcc      = null;
		$mailData     = $mailSection;
		$mailSubject  = $mailSection;
		$mailTemplate = Helper::getTemplate(0, $mailSection);

		if (count($mailTemplate) > 0)
		{
			$mailData    = html_entity_decode($mailTemplate[0]->mail_body, ENT_QUOTES);
			$mailSubject = html_entity_decode($mailTemplate[0]->mail_subject, ENT_QUOTES);

			if (trim($mailTemplate[0]->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailTemplate[0]->mail_bcc);
			}
		}

		$search  = array();
		$replace = array();

		$search[]  = "{username}";
		$search[]  = "{shopname}";
		$search[]  = "{name}";
		$search[]  = "{company_name}";
		$search[]  = "{address}";
		$search[]  = "{city}";
		$search[]  = "{zipcode}";
		$search[]  = "{country}";
		$search[]  = "{phone}";
		$replace[] = $userInfo['username'];
		$replace[] = \Redshop::getConfig()->get('SHOP_NAME');
		$replace[] = $userInfo['firstname'] . ' ' . $userInfo['lastname'];

		if ($userInfo['is_company'] == 1)
		{
			$replace[] = $userInfo['company_name'];
		}
		else
		{
			$replace[] = "";
		}

		$replace[] = $userInfo['address'];
		$replace[] = $userInfo['city'];
		$replace[] = $userInfo['zipcode'];
		$replace[] = \RedshopHelperOrder::getCountryName($userInfo['country_code']);
		$replace[] = $userInfo['phone'];

		$mailData = str_replace($search, $replace, $mailData);
		Helper::imgInMail($mailData);

		if ($email != "")
		{
			Helper::sendEmail($mailFrom, $fromName, $email, $mailSubject, $mailData, true, null, $mailBcc, null, $mailSection, func_get_args());
		}

		return true;
	}
}
