<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.controller');

require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';
require_once JPATH_COMPONENT_SITE . '/helpers/extra_field.php';


/**
 * registration Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RegistrationController extends JController
{
	/**
	 * newregistration function
	 *
	 * @access public
	 * @return void
	 */
	public function newregistration()
	{
		$app    = JFactory::getApplication();
		$post   = JRequest::get('post');
		$option = JRequest::getCmd('option');
		$Itemid = JRequest::getInt('Itemid', 0);

		$prodhelperobj = new producthelper;
		$redshopMail   = new redshopMail;

		$model   = $this->getModel('registration');
		$success = $model->store($post);

		if ($success)
		{
			if ($post['mywishlist'] == 1)
			{
				$wishreturn = JRoute::_('index.php?loginwishlist=1&option=com_redshop&view=wishlist&Itemid=' . $Itemid, false);
				$this->setRedirect($wishreturn);
			}
			else
			{
				$msg = WELCOME_MSG;

				if (SHOP_NAME != "")
				{
					$msg = str_replace("{shopname}", SHOP_NAME, $msg);
				}

				// Redirection settings
				$link = JRoute::_('index.php?option=com_redshop&view=redshop&Itemid=' . $Itemid);

				$menu = JFactory::getApplication()->getMenu();
				$retMenuItem = array();
				$retMenuItem = $menu->getItem($menu->getParams($Itemid)->get('registrationredirect'));

				if (count($retMenuItem) > 0)
				{
					$link = JRoute::_($retMenuItem->link . '&Itemid=' . $retMenuItem->id);
				}

				// Redirection settings End
				$this->setRedirect($link, $msg);
			}
		}
		else
		{
			parent::display();
		}
	}

	/**
	 * captcha
	 *
	 * @return  void
	 */
	public function captcha()
	{
		require_once JPATH_COMPONENT_SITE . '/helpers/captcha.php';

		$width       = JRequest::getInt('width', 120);
		$height      = JRequest::getInt('height', 40);
		$characters  = JRequest::getInt('characters', 6);
		$captchaname = JRequest::getCmd('captcha', 'security_code');

		$captcha = new CaptchaSecurityImages($width, $height, $characters, $captchaname);
	}

	/**
	 * searchUserdetailByPhone
	 *
	 * @return  string
	 */
	public function searchUserdetailByPhone()
	{
		ob_clean();
		$get = JRequest::get('get');
		$return = "";

		JPluginHelper::importPlugin('telesearch', 'rs_telesearch');
		$this->_dispatcher = JDispatcher::getInstance();
		$tele['phone']     = $get['phone'];
		$accountHandle     = $this->_dispatcher->trigger('findByTelephoneNumber', array($tele));

		if (count($accountHandle) > 0)
		{
			$response = $accountHandle[0];

			if (count($response) > 0)
			{
				$return = implode("`_`", $response);
			}
		}

		echo $return;
		die();
	}

	/**
	 * getCompanyOrCustomer
	 *
	 * @return  string
	 */
	public function getCompanyOrCustomer()
	{
		$redTemplate  = new Redtemplate;
		$rsUserhelper = new rsUserhelper;
		$extraField   = new extraField;

		$get = JRequest::get('get');
		$template_id = $get['template_id'];
		$is_company  = $get['is_company'];

		$lists['extra_field_user']        = $extraField->list_all_field(7);
		$lists['extra_field_company']     = $extraField->list_all_field(8);
		$lists['shipping_customer_field'] = $extraField->list_all_field(14, 0, 'billingRequired valid');
		$lists['shipping_company_field']  = $extraField->list_all_field(15, 0, 'billingRequired valid');
		$lists['isAjax']                  = 1;

		if ($is_company == 1)
		{
			$template = $redTemplate->getTemplate("company_billing_template", $template_id);

			if (count($template) > 0 && $template[0]->template_desc != "")
			{
				$template_desc = $template[0]->template_desc;
			}
			else
			{
				$template_desc = '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr><td width="100" align="right">{email_lbl}:</td><td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr><td width="100" align="right">{company_name_lbl}</td><td>{company_name}</td><td><span class="required">*</span></td></tr><!-- {vat_number_start} --><tr><td width="100" align="right">{vat_number_lbl}</td><td>{vat_number}</td><td><span class="required">*</span></td></tr><!-- {vat_number_end} --><tr><td width="100" align="right">{firstname_lbl}</td><td>{firstname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_lbl}</td><td>{lastname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_lbl}</td><td>{address}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_lbl}</td><td>{city}</td><td><span class="required">*</span></td></tr><tr id="{country_txtid}" style="{country_style}"><td width="100" align="right">{country_lbl}</td><td>{country}</td><td><span class="required">*</span></td></tr><tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td><td>{state}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{ean_number_lbl}</td><td>{ean_number}</td><td></td></tr><tr><td width="100" align="right">{tax_exempt_lbl}</td><td>{tax_exempt}</td></tr><tr><td colspan="3">{company_extrafield}</td></tr></tbody></table>';
			}

			$template_desc = $rsUserhelper->replaceCompanyCustomer($template_desc, $get, $lists);
		}
		else
		{
			$template = $redTemplate->getTemplate("private_billing_template", $template_id);

			if (count($template) > 0 && $template[0]->template_desc != "")
			{
				$template_desc = $template[0]->template_desc;
			}
			else
			{
				$template_desc = '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr><td width="100" align="right">{email_lbl}:</td><td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr><td width="100" align="right">{firstname_lbl}</td><td>{firstname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_lbl}</td><td>{lastname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_lbl}</td><td>{address}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_lbl}</td><td>{city}</td><td><span class="required">*</span></td></tr><tr id="{country_txtid}" style="{country_style}"><td width="100" align="right">{country_lbl}</td><td>{country}</td><td><span class="required">*</span></td></tr><tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td><td>{state}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td><span class="required">*</span></td></tr><tr><td colspan="3">{private_extrafield}</td></tr></tbody></table>';
			}

			$template_desc = $rsUserhelper->replacePrivateCustomer($template_desc, $get, $lists);
		}

		echo $return = "<div id='ajaxRegistrationDiv'>" . $template_desc . "</div>";
		die();
	}
}
