<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperAdminExtra_field');
JLoader::load('RedshopHelperAdminOrder');

class RedshopViewAddquotation_detail extends RedshopView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$option = JRequest::getVar('option');
		$extra_field = new extra_field;
		$order_functions = new order_functions;
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_QUOTATION_MANAGEMENT'));

		JHtml::script('com_redshop/json.js', false, true);
		$document->addScript('components/com_redshop/assets/js/validation.js');
		$document->addScript('components/com_redshop/assets/js/order.js');
		$document->addScript(JURI::base() . 'components/com_redshop/assets/js/common.js');
		$document->addScript('components/com_redshop/assets/js/select_sort.js');
		$document->addStyleSheet('components/com_redshop/assets/css/search.css');
		$document->addScript('components/com_redshop/assets/js/search.js');
		$session = JFactory::getSession();
		$uri = JFactory::getURI();

		$lists = array();
		$model = $this->getModel();
		$detail = $this->get('Data');
		$Redconfiguration = new Redconfiguration;

		$user_id = JRequest::getVar('user_id', 0);

		if ($user_id != 0)
		{
			$billing = $order_functions->getBillingAddress($user_id);
		}
		else
		{
			$billing = $model->setBilling();
		}

		if (!is_object($detail))
		{
			$detail = new stdClass;
		}

		$detail->user_id = $user_id;

		$session->set('offlineuser_id', $user_id);

		JToolBarHelper::title(
			JText::_('COM_REDSHOP_QUOTATION_MANAGEMENT') . ': <small><small>[ '
				. JText::_('COM_REDSHOP_NEW') . ' ]</small></small>', 'redshop_order48'
		);

		JToolBarHelper::save();
		JToolBarHelper::custom('send', 'send.png', 'send.png', JText::_('COM_REDSHOP_SEND'), false);
		JToolBarHelper::cancel();

		// PRODUCT/ATTRIBUTE STOCK ROOM QUANTITY CHECKING IS IMPLEMENTED

		$countryarray = $Redconfiguration->getCountryList((array) $billing);
		$billing->country_code = $countryarray['country_code'];
		$lists['country_code'] = $countryarray['country_dropdown'];
		$statearray = $Redconfiguration->getStateList((array) $billing);
		$lists['state_code'] = $statearray['state_dropdown'];
		$lists['quotation_extrafield'] = $extra_field->list_all_field(16, $billing->users_info_id);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->billing = $billing;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
