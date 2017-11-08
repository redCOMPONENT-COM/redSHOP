<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopViewAddquotation_detail extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$extra_field = extra_field::getInstance();
		$order_functions = order_functions::getInstance();
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_QUOTATION_MANAGEMENT'));

		$document->addScript('components/com_redshop/assets/js/json.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');
		$document->addScript('components/com_redshop/assets/js/order.js');
		$document->addScript(JURI::base() . 'components/com_redshop/assets/js/common.js');
		$session = JFactory::getSession();
		$uri = JFactory::getURI();

		$lists = array();
		$model = $this->getModel();
		$user_id = JFactory::getApplication()->input->getInt('user_id', 0);

		if ($user_id != 0)
		{
			$billing = $order_functions->getBillingAddress($user_id);
		}
		else
		{
			$billing = $model->setBilling();
		}

		$detail = new stdClass;
		$detail->user_id = $user_id;

		$session->set('offlineuser_id', $user_id);

		JToolBarHelper::title(
			JText::_('COM_REDSHOP_QUOTATION_MANAGEMENT') . ': <small><small>[ '
				. JText::_('COM_REDSHOP_NEW') . ' ]</small></small>', 'redshop_order48'
		);

		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::custom('send', 'send.png', 'send.png', JText::_('COM_REDSHOP_SEND'), false);
		JToolBarHelper::cancel();

		$countryarray                  = RedshopHelperWorld::getCountryList((array) $billing);
		$billing->country_code         = $countryarray['country_code'];
		$lists['country_code']         = $countryarray['country_dropdown'];

		$statearray                    = RedshopHelperWorld::getStateList((array) $billing);
		$lists['state_code']           = $statearray['state_dropdown'];
		$lists['quotation_extrafield'] = $extra_field->list_all_field(16, $billing->users_info_id);

		$this->lists       = $lists;
		$this->detail      = $detail;
		$this->billing     = $billing;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
