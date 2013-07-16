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
require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_COMPONENT . '/helpers/extra_field.php';

class checkoutViewcheckout extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$model     = $this->getModel('checkout');
		$option    = JRequest::getVar('option');
		$Itemid    = JRequest::getVar('Itemid');
		$task      = JRequest::getVar('task');
		$user      = JFactory::getUser();
		$redhelper = new redhelper;
		$field     = new extraField;
		$session   = JFactory::getSession();

		// Load language file
		$payment_lang_list = $redhelper->getPlugins("redshop_payment");
		$language          = JFactory::getLanguage();
		$base_dir          = JPATH_ADMINISTRATOR;
		$language_tag      = $language->getTag();

		for ($l = 0; $l < count($payment_lang_list); $l++)
		{
			$extension = 'plg_redshop_payment_' . $payment_lang_list[$l]->element;
			$language->load($extension, $base_dir, $language_tag, true);
		}

		JHTML::Script('joomla.javascript.js', 'includes/js/', false);
		JHTML::Script('validate.js', 'media/system/js/', false);
		JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('jquery.validate.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('jquery.metadata.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('registration.js', 'components/com_redshop/assets/js/', false);
		JHTML::Stylesheet('validation.css', 'components/com_redshop/assets/css/');
		JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);

		if (JPluginHelper::isEnabled('redshop_veis_registration', 'rs_veis_registration'))
		{
			JHTML::Script('veis.js', 'plugins/redshop_veis_registration/rs_veis_registration/js/', false);
		}

		$cart = $session->get('cart');
		$auth = $session->get('auth');

		if (!is_array($auth))
		{
			$auth['users_info_id'] = 0;
			$session->set('auth', $auth);
			$auth = $session->get('auth');
		}

		if ($cart['idx'] < 1)
		{
			$msg  = JText::_('COM_REDSHOP_EMPTY_CART');
			$link = 'index.php?option=' . $option . '&Itemid=' . $Itemid;
			$app->Redirect($link, $msg);
		}

		$lists = array();

		if ($task != '')
		{
			$tpl = $task;
		}
		else
		{
			if ($user->id || $auth['users_info_id'] > 0)
			{
				$cart = $session->get('cart');

				if (DEFAULT_QUOTATION_MODE == 1 && !array_key_exists("quotation_id", $cart))
				{
					$app->Redirect('index.php?option=' . $option . '&view=quotation&Itemid=' . $Itemid);
				}

				$users_info_id     = JRequest::getInt('users_info_id');
				$billingaddresses  = $model->billingaddresses();
				$shippingaddresses = $model->shippingaddresses();

				if (!$users_info_id)
				{
					if ((!isset($users_info_id) || $users_info_id == 0) && count($shippingaddresses) > 0)
					{
						$users_info_id = $shippingaddresses[0]->users_info_id;
					}
					elseif ((!isset($users_info_id) || $users_info_id == 0) && count($billingaddresses) > 0)
					{
						$users_info_id = $billingaddresses->users_info_id;
					}
					else
					{
						$app->Redirect("index.php?option=" . $option . "&view=account_billto&Itemid=" . $Itemid);
					}
				}

				$shipping_rate_id = JRequest::getVar('shipping_rate_id');
				$element          = JRequest::getVar('payment_method_id');
				$ccinfo           = JRequest::getVar('ccinfo');

				$total_discount = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
				$subtotal       = (SHIPPING_AFTER == 'total') ? $cart['product_subtotal'] - $total_discount : $cart['product_subtotal'];

				$this->users_info_id = $users_info_id;
				$this->shipping_rate_id = $shipping_rate_id;
				$this->element = $element;
				$this->ccinfo = $ccinfo;
				$this->order_subtotal = $subtotal;
				$this->ordertotal = $cart['total'];
			}
			else
			{
				// Field_section 6 : Customer Registration
				$lists['extra_field_user']        = $field->list_all_field(7);

				// Field_section 6 : Company Address
				$lists['extra_field_company']     = $field->list_all_field(8);
				$lists['shipping_customer_field'] = $field->list_all_field(14, 0, 'billingRequired valid');
				$lists['shipping_company_field']  = $field->list_all_field(15, 0, 'billingRequired valid');
			}
		}

		if (($user->id || $auth['users_info_id'] > 0) && ONESTEP_CHECKOUT_ENABLE)
		{
			$this->setLayout('onestepcheckout');
		}

		$this->lists = $lists;
		parent::display($tpl);
	}
}
