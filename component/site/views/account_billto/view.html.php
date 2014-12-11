<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperExtra_field');

class RedshopViewAccount_billto extends RedshopView
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 */
	public function display($tpl = null)
	{
		$app         = JFactory::getApplication();
		$extra_field = new extraField;

		$params = $app->getParams('com_redshop');

		$billingaddresses = $GLOBALS['billingaddresses'];

		if (count($billingaddresses) <= 0)
		{
			$model            = $this->getModel('account_billto');
			$billingaddresses = $model->_initData();
		}

		$user    = JFactory::getUser();
		$uri     = JFactory::getURI();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');

		if (!is_array($auth))
		{
			$auth['users_info_id'] = 0;
			$session->set('auth', $auth);
			$auth = $session->get('auth');
		}

		JHtml::script('com_redshop/jquery-1.4.2.min.js', false, true);
		JHtml::script('com_redshop/jquery.validate.js', false, true);
		JHtml::script('com_redshop/common.js', false, true);
		JHtml::script('com_redshop/registration.js', false, true);
		JHtml::stylesheet('com_redshop/validation.css', array(), true);

		// Preform security checks
		if ($user->id == 0 && $auth['users_info_id'] == 0)
		{
			$app->redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid'));
			exit;
		}

		$lists['requesting_tax_exempt'] = JHTML::_('select.booleanlist', 'requesting_tax_exempt', 'class="inputbox"', @$billingaddresses->requesting_tax_exempt);

		// Field_section 7 :Customer Address
		$lists['extra_field_user']      = $extra_field->list_all_field(7, @$billingaddresses->users_info_id);
		$lists['extra_field_company']   = $extra_field->list_all_field(8, @$billingaddresses->users_info_id);

		$requestUrl = $uri->toString();

		$this->lists            = $lists;
		$this->billingaddresses = $billingaddresses;
		$this->request_url      = JFilterOutput::cleanText($requestUrl);
		$this->params           = $params;

		parent::display($tpl);
	}
}
