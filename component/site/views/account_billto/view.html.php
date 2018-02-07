<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



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
		$app    = JFactory::getApplication();
		$params = $app->getParams('com_redshop');

		$billingaddresses = $GLOBALS['billingaddresses'];

		if (empty($billingaddresses))
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

		JHtml::_('redshopjquery.framework');
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/jquery.validate.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/redshop.common.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::script('com_redshop/redshop.registration.min.js', false, true);
		/** @scrutinizer ignore-deprecated */JHtml::stylesheet('com_redshop/redshop.validation.min.css', array(), true);

		// Preform security checks
		if ($user->id == 0 && $auth['users_info_id'] == 0)
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));
			$app->close();
		}

		$lists['requesting_tax_exempt'] = JHTML::_('select.booleanlist', 'requesting_tax_exempt', 'class="inputbox"', @$billingaddresses->requesting_tax_exempt);

		if ($billingaddresses->is_company)
		{
			$lists['extra_field_company'] = Redshop\Fields\SiteHelper::renderFields(8, $billingaddresses->users_info_id);
		}
		else
		{
			$lists['extra_field_user'] = Redshop\Fields\SiteHelper::renderFields(7, $billingaddresses->users_info_id);
		}

		$this->request_url = $uri->toString();

		$this->lists            = $lists;
		$this->billingaddresses = $billingaddresses;
		JFilterOutput::cleanText($this->request_url);
		$this->params           = $params;

		parent::display($tpl);
	}
}
