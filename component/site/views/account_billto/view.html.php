<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Account Billing To view
 *
 * @package     RedSHOP.Frontend
 * @subpackage  View
 * @since       1.6.0
 */
class RedshopViewAccount_Billto extends RedshopView
{
	/**
	 * @var string
	 */
	public $request_url;

	/**
	 * @var array
	 */
	public $lists;

	/**
	 * @var object|boolean
	 */
	public $billingaddresses;

	/**
	 * @var  \Joomla\Registry\Registry
	 */
	public $params;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed         A string if successful, otherwise a JError object.
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		/** @var JApplicationSite $app */
		$app    = JFactory::getApplication();
		$params = $app->getParams('com_redshop');

		$billingAddresses = Redshop\User\Billing\Billing::getGlobal();

		if (empty($billingAddresses) || $billingAddresses == new stdClass)
		{
			/** @var RedshopModelAccount_Billto $model */
			$model = $this->getModel('account_billto');

			$billingAddresses = $model->_initData();
		}

		$user    = JFactory::getUser();
		$uri     = JUri::getInstance();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');

		if (!is_array($auth))
		{
			$auth['users_info_id'] = 0;
			$session->set('auth', $auth);
			$auth = $session->get('auth');
		}

		JHtml::_('redshopjquery.framework');
		/** @scrutinizer ignore-deprecated */
		JHtml::script('com_redshop/jquery.validate.min.js', false, true);
		/** @scrutinizer ignore-deprecated */
		JHtml::script('com_redshop/redshop.common.min.js', false, true);
		/** @scrutinizer ignore-deprecated */
		JHtml::script('com_redshop/redshop.registration.min.js', false, true);
		/** @scrutinizer ignore-deprecated */
		JHtml::stylesheet('com_redshop/redshop.validation.min.css', array(), true);

		// Preform security checks
		if ($user->id == 0 && $auth['users_info_id'] == 0)
		{
			$app->redirect(JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));
			$app->close();
		}

		$lists = array(
			'requesting_tax_exempt' => JHtml::_(
				'select.booleanlist',
				'requesting_tax_exempt',
				'class="inputbox"',
				$billingAddresses->requesting_tax_exempt
			)
		);

		if ($billingAddresses->is_company)
		{
			$lists['extra_field_company'] = Redshop\Fields\SiteHelper::renderFields(
				RedshopHelperExtrafields::SECTION_COMPANY_BILLING_ADDRESS, $billingAddresses->users_info_id
			);
		}
		else
		{
			$lists['extra_field_user'] = Redshop\Fields\SiteHelper::renderFields(
				RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS, $billingAddresses->users_info_id
			);
		}

		$this->request_url = $uri->toString();

		$this->lists            = $lists;
		$this->billingaddresses = $billingAddresses;
		JFilterOutput::cleanText($this->request_url);
		$this->params = $params;

		parent::display($tpl);
	}
}
