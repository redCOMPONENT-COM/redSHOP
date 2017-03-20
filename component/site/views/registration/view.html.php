<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewRegistration extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$Itemid = JRequest::getInt('Itemid');

		$user    = JFactory::getUser();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');

		if ($user->id || (isset($auth['users_info_id']) && $auth['users_info_id'] > 0))
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $Itemid));
		}

		$params = $app->getParams('com_redshop');
		JHtml::_('redshopjquery.framework');
		JHtml::script('com_redshop/jquery.validate.js', false, true);
		JHtml::script('com_redshop/common.js', false, true);
		JHtml::script('com_redshop/jquery.metadata.js', false, true);
		JHtml::script('com_redshop/registration.js', false, true);
		JHtml::stylesheet('com_redshop/validation.css', array(), true);

		JPluginHelper::importPlugin('redshop_vies_registration');

		$field                        = extraField::getInstance();

		$jInput = JFactory::getApplication()->input;
		$openToStretcher = 0;
		$isCompany = $jInput->getInt('is_company', 0);

		if ($isCompany == 1 || Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE') == 2)
		{
			$openToStretcher = 1;
		}

		// Allow registration type settings
		$lists['allowCustomer'] = "";
		$lists['allowCompany'] = "";
		$lists['showCustomerdesc'] = "";
		$lists['showCompanydesc'] = "style='display:none;'";

		if (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 1)
		{
			$lists['allowCompany']      = "style='display:none;'";
			$openToStretcher = 0;
		}
		elseif (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') == 2)
		{
			$lists['allowCustomer']     = "style='display:none;'";
			$lists['showCustomerdesc']  = "style='display:none;'";
			$openToStretcher = 1;
		}

		if (Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE') == 2)
		{
			$lists['showCompanydesc']  = '';
			$lists['showCustomerdesc'] = "style='display:none;'";
		}

		$lists['is_company'] = ($openToStretcher == 1 || ($isCompany == 1)) ? 1 : 0;

		if ($lists['is_company'])
		{
			// Field_section 8 : Company Address
			$lists['extra_field_company'] = $field->list_all_field(8);
		}
		else
		{
			// Field_section 7 : Customer Registration
			$lists['extra_field_user']    = $field->list_all_field(7);
		}

		$this->lists = $lists;
		$this->params = $params;
		parent::display($tpl);
	}
}
