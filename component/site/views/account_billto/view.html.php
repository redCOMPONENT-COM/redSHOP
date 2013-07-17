<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

require_once JPATH_COMPONENT . '/helpers/extra_field.php';

class Account_billtoViewaccount_billto extends JView
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
		$app = JFactory::getApplication();
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

		JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('jquery.validate.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('registration.js', 'components/com_redshop/assets/js/', false);
		JHTML::Stylesheet('validation.css', 'components/com_redshop/assets/css/');

		// Preform security checks
		if ($user->id == 0 && $auth['users_info_id'] == 0)
		{
			$app->Redirect('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getVar('Itemid'));
			exit;
		}

		$lists['requesting_tax_exempt'] = JHTML::_('select.booleanlist', 'requesting_tax_exempt', 'class="inputbox"', @$billingaddresses->requesting_tax_exempt);

		// Field_section 7 :Customer Address
		$lists['extra_field_user']      = $extra_field->list_all_field(7, @$billingaddresses->users_info_id);
		$lists['extra_field_company']   = $extra_field->list_all_field(8, @$billingaddresses->users_info_id);

		$this->lists = $lists;
		$this->billingaddresses = $billingaddresses;
		$this->request_url = $uri->toString();
		$this->params = $params;
		parent::display($tpl);
	}
}
