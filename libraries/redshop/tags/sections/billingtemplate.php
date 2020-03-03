<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  3.0
 */
class RedshopTagsSectionsBillingTemplate extends RedshopTagsAbstract
{
	public $tags = array(
		'{required_lbl}', '{shipping_same_as_billing}', '{shipping_same_as_billing_lbl}',
	);

	public function init(){}

	public function replace()
	{
		$this->replacePrivateCompanyBilling('private');
		$this->replacePrivateCompanyBilling('company');
		$this->template = $this->strReplace($this->replacements, $this->template);
		$this->template = $this->replaceCreateAccount();
		$this->addReplace('{required_lbl}', JText::_('COM_REDSHOP_REQUIRED'));
		$this->replaceSameShipping();

		$this->template .= RedshopLayoutHelper::render(
			'tags.common.tag',
			array(
				'text' => '',
				'tag' => 'div',
				'id' => 'tmpRegistrationDiv',
				'class' => '',
				'attr' => 'style="display: none;"'
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		return parent::replace();
	}

	/**
	 * Replace tag private_billing_template and company_billing_template
	 *
	 * @param   string  $prefix
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public function replacePrivateCompanyBilling($prefix)
	{
		$templates = RedshopHelperTemplate::getTemplate($prefix . '_billing_template');

		if (empty($templates))
		{
			$tmpTemplate       = new stdClass;
			$tmpTemplate->name = $prefix . '_billing_template';
			$tmpTemplate->id   = 0;

			$templates = array($tmpTemplate);
		}

		foreach ($templates as $template)
		{
			if (!$this->isTagExists("{" . $prefix . "_billing_template:" . $template->name . "}"))
			{
				continue;
			}

			$html = '';

			if (($prefix == 'private' && $this->data['isCompany'] != 1) ||
				$prefix == 'company' && $this->data['isCompany'] == 1)
			{
				$html = !empty($template->template_desc) ?
					$template->template_desc :
					RedshopHelperTemplate::getDefaultTemplateContent($prefix . '_billing_template');

				$html = RedshopTagsReplacer::_(
					$prefix . 'billingtemplate',
					$html,
					array(
						'data' => $this->data['data'],
						'lists' => $this->data['lists']
					)
				);
			}

			$html = RedshopLayoutHelper::render(
				'tags.billing_template.private_company',
				array(
					'content' => $html,
					'divContent' => 'tbl' . $prefix . '_customer',
					'divTemplateId' => 'div' . ucfirst($prefix) . 'TemplateId',
					'templateId' => $template->id
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements['{' . $prefix . '_billing_template:' . $template->name . '}'] = $html;

			break;
		}
	}

	/**
	 * Replace same shipping
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function replaceSameShipping()
	{
		if ($this->data['showShipping'] && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			$billingIsShipping = "";

			if ((isset($this->data['data']['billisship']) && $this->data['data']['billisship'] == 1)
				|| Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS'))
			{
				$billingIsShipping = "checked='checked'";
			}

			$sameShippingLbl = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'text'  => JText::_('COM_REDSHOP_SHIPPING_SAME_AS_BILLING'),
					'id'    => 'billisship',
					'class' => ''
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->addReplace('{shipping_same_as_billing_lbl}', $sameShippingLbl);

			$sameShipping = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'name' => 'billisship',
					'id' => 'billisship',
					'type' => 'checkbox',
					'value' => '1',
					'attr' => 'onclick="billingIsShipping(this);" ' . $billingIsShipping,
					'class' => ''
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->addReplace('{shipping_same_as_billing}', $sameShipping);
		}
		else
		{
			$this->addReplace('{shipping_same_as_billing_lbl}', '');
			$this->addReplace('{shipping_same_as_billing}', '');
		}
	}

	/**
	 * Replace create account
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public function replaceCreateAccount()
	{
		$subTemplate = $this->getTemplateBetweenLoop('{account_creation_start}', '{account_creation_end}');
		$this->replacements = array();
		if (!empty($subTemplate))
		{
			$createAccountHtml      = '';
			$checkboxStyle          = '';

			if (Redshop::getConfig()->get('REGISTER_METHOD') != 1 && Redshop::getConfig()->get('REGISTER_METHOD') != 3)
			{
				$createAccountHtml = $subTemplate['template'];

				if (Redshop::getConfig()->get('REGISTER_METHOD') == 2)
				{
					$checkboxStyle = $this->data['createAccount'] == 1 ? 'style="display:block"' : 'style="display:none"';
				}
				else
				{
					$checkboxStyle = 'style="display:block"';
				}

				$usernameLbl = RedshopLayoutHelper::render(
					'tags.common.label',
					array(
						'text'  => JText::_('COM_REDSHOP_USERNAME_REGISTER'),
						'id'    => 'username',
						'class' => ''
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements['{username_lbl}'] = $usernameLbl;

				$username = RedshopLayoutHelper::render(
					'tags.common.input',
					array(
						'id' => 'username',
						'name' => 'username',
						'type' => 'text',
						'value' => (!empty($this->data['data']["username"]) ? $this->data['data']['username'] : ''),
						'class' => 'inputbox required',
						'attr' => 'size="32" maxlength="250" data-msg="' . JText::_('COM_REDSHOP_PLEASE_ENTER_USERNAME') . '"'
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements['{username}'] = $username;

				$passwordLbl = RedshopLayoutHelper::render(
					'tags.common.label',
					array(
						'text'  => JText::_('COM_REDSHOP_PASSWORD_REGISTER'),
						'id'    => 'password',
						'class' => ''
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements['{password_lbl}'] = $passwordLbl;

				$password = RedshopLayoutHelper::render(
					'tags.common.input',
					array(
						'id' => 'password1',
						'name' => 'password1',
						'type' => 'text',
						'value' => (!empty($this->data['data']["username"]) ? $this->data['data']['username'] : ''),
						'class' => 'inputbox required',
						'attr' => 'size="32" maxlength="250" data-msg="' . JText::_('COM_REDSHOP_PLEASE_ENTER_PASSWORD') . '"'
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements['{password}'] = $password;

				$confirmPassLbl = RedshopLayoutHelper::render(
					'tags.common.label',
					array(
						'text'  => JText::_('COM_REDSHOP_CONFIRM_PASSWORD'),
						'id'    => 'password2',
						'class' => ''
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements['{confirm_password_lbl}'] = $confirmPassLbl;

				$confirmPass = RedshopLayoutHelper::render(
					'tags.common.input',
					array(
						'id' => 'password2',
						'name' => 'password2',
						'type' => 'text',
						'value' => '',
						'class' => 'inputbox required',
						'attr' => 'size="32" maxlength="250" data-msg="' . JText::_('COM_REDSHOP_PLEASE_ENTER_PASSWORD') . '"'
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements['{confirm_password}'] = $confirmPass;

				$newsletterSignupLabel     = "";
				$newsletterSignupCheckHtml = "";

				if ($this->data['showNewsletter'] && Redshop::getConfig()->get('NEWSLETTER_ENABLE'))
				{
					$newsletterSignupLabel = RedshopLayoutHelper::render(
						'tags.common.label',
						array(
							'text'  => JText::_('COM_REDSHOP_SIGN_UP_FOR_NEWSLETTER'),
							'id'    => 'newsletter_signup',
							'class' => ''
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$newsletterSignupCheckHtml = RedshopLayoutHelper::render(
						'tags.common.input',
						array(
							'id' => 'newsletter_signup',
							'name' => 'newsletter_signup',
							'type' => 'checkbox',
							'value' => '1',
							'class' => '',
							'attr' => ''
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);
				}

				$this->replacements['{newsletter_signup_lbl}'] = $newsletterSignupLabel;
				$this->replacements['{newsletter_signup_chk}'] = $newsletterSignupCheckHtml;
			}

			if (!empty(\JFactory::getUser()->id))
			{
				return $subTemplate['begin'] . $subTemplate['end'];
			}
			else
			{
				$createAccountHtml = $this->strReplace($this->replacements, $createAccountHtml);

				return $subTemplate['begin'] .
						RedshopLayoutHelper::render(
							'tags.billing_template.create_account',
							array(
								'checkboxStyle' => $checkboxStyle,
								'htmlContent' => $createAccountHtml
							),
							'',
							RedshopLayoutHelper::$layoutOption
						) .
						$subTemplate['end'];
			}
		}

		return $this->template;
	}
}