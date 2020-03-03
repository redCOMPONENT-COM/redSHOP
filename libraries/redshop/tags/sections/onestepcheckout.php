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
class RedshopTagsSectionsOneStepCheckout extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since   3.0
	 */
	public $tags = array();

	/**
	 * Init
	 *
	 * @return  mixed
	 *
	 * @since   3.0
	 */
	public function init()
	{

	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   3.0
	 */
	public function replace()
	{
		JPluginHelper::importPlugin('redshop_shipping');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$dispatcher->trigger('onRenderCustomField');
		$user = JFactory::getUser();
		$app  = JFactory::getApplication();
		$session = JFactory::getSession();
		$auth    = $session->get('auth');
		$itemId = RedshopHelperRouter::getCheckoutItemId();
		$subReplacement = [];

		$cart  = \Redshop\Cart\Helper::getCart();
		$billingAddresses = $this->data['billingAddress'];

		if ($billingAddresses == new stdClass)
		{
			$billingAddresses = null;
		}

		$paymentMethods          = RedshopHelperUtility::getPlugins('redshop_payment', 1);
		$selectedPaymentMethodId = 0;

		if (count($paymentMethods) > 0)
		{
			$selectedPaymentMethodId = $paymentMethods[0]->element;
		}

		$shippingBoxes         = RedshopHelperShipping::getShippingBox();
		$selectedShippingBoxId = 0;

		if (count($shippingBoxes) > 0)
		{
			$selectedShippingBoxId = $shippingBoxes[0]->shipping_box_id;
		}

		$usersInfoId       = $app->input->getInt('users_info_id', $this->data['usersInfoId']);
		$paymentMethodId   = $app->input->getCmd('payment_method_id', $selectedPaymentMethodId);
		$shippingBoxPostId = $app->input->getInt('shipping_box_id', $selectedShippingBoxId);
		$shippingRateId    = $app->input->getInt('shipping_rate_id', 0);

		if ($usersInfoId == 0 && !empty($billingAddresses) && !empty($billingAddresses->users_info_id))
		{
			$usersInfoId = $billingAddresses->users_info_id;
		}

		$loginTemplate = "";

		if (!$usersInfoId && Redshop::getConfig()->getInt('REGISTER_METHOD') != 1
			&& Redshop::getConfig()->getInt('REGISTER_METHOD') != 3)
		{
			$loginTemplate = RedshopLayoutHelper::render(
				'tags.checkout.onestep.login',
				array(
					'itemId' => $itemId,
					'returnUrl' => base64_encode(JRoute::_('index.php?option=com_redshop&view=checkout', false))
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);
		}

		if ($this->isTagExists('{billing_address_information_lbl}'))
		{
			$this->replacements['{billing_address_information_lbl}'] = JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL');
		}

		$paymentTemplate     = "";
		$paymentTemplateHtml = "";
		$templates           = RedshopHelperTemplate::getTemplate("redshop_payment");

		foreach ($templates as $template)
		{
			if (!$this->isTagExists("{payment_template:" . $template->name . "}"))
			{
				continue;
			}

			$paymentTemplate     = "{payment_template:" . $template->name . "}";
			$paymentTemplateHtml = $template->template_desc;

			$subReplacement[$paymentTemplate] = RedshopLayoutHelper::render(
				'tags.common.tag',
				array(
					'tag' => 'div',
					'id' => 'divPaymentMethod',
					'text' => $paymentTemplate
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);
		}

		$templates = RedshopHelperTemplate::getTemplate("checkout");

		foreach ($templates as $template)
		{
			if (strpos($this->template, "{checkout_template:" . $template->name . "}") === false)
			{
				continue;
			}

			$cartTemplate                  = "{checkout_template:" . $template->name . "}";
			$subReplacement[$cartTemplate] = RedshopLayoutHelper::render(
				'tags.checkout.onestep.checkout_template',
				array(
					'cartTemplate' => $cartTemplate,
					'templateId' => $template->id
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements[$cartTemplate] = $template->template_desc;
		}

		// For shipping template
		$shippingBoxTemplate     = "";
		$shippingBoxTemplateHtml = "";
		$shippingTemplate        = "";
		$shippingTemplateHtml    = "";
		$templates = RedshopHelperTemplate::getTemplate("shippingbox");

		foreach ($templates as $template)
		{
			if (strpos($this->template, "{shippingbox_template:" . $template->name . "}") === false)
			{
				continue;
			}

			$shippingBoxTemplate     = "{shippingbox_template:" . $template->name . "}";
			$shippingBoxTemplateHtml = $template->template_desc;
		}

		$templates = RedshopHelperTemplate::getTemplate("redshop_shipping");

		foreach ($templates as $template)
		{
			if (strpos($this->template, "{shipping_template:" . $template->name . "}") === false)
			{
				continue;
			}

			$shippingTemplate     = "{shipping_template:" . $template->name . "}";
			$shippingTemplateHtml = $template->template_desc;

			$subReplacement[$shippingTemplate] = RedshopLayoutHelper::render(
				'tags.checkout.onestep.shipping_template',
				array(
					'shippingTemplate' => $shippingTemplate,
					'templateId' => $template->id
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);
		}

		if (Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE'))
		{
			$orderTotal    = $cart['total'];
			$totalDiscount = $cart['cart_discount'] + $cart['voucher_discount'] + $cart['coupon_discount'];
			$orderSubTotal = Redshop::getConfig()->getString('SHIPPING_AFTER') == 'total' ?
				$cart['product_subtotal_excl_vat'] - $totalDiscount : $cart['product_subtotal_excl_vat'];

			$shippingBoxTemplateHtml = RedshopTagsReplacer::_(
				'shippingbox',
				$shippingBoxTemplateHtml,
				array(
					'shippingBoxPostId' => $shippingBoxPostId
				)
			);

			$this->replacements[$shippingBoxTemplate] = $shippingBoxTemplateHtml;

			$return = \Redshop\Shipping\Tag::replaceShippingTemplate(
				$shippingTemplateHtml,
				$shippingRateId,
				$shippingBoxPostId,
				$user->id,
				$usersInfoId,
				$orderTotal,
				$orderSubTotal
			);

			$shippingTemplateHtml = $return['template_desc'];
			$shippingRateId       = $return['shipping_rate_id'];

			if ($shippingRateId)
			{
				$shippingList         = Redshop\Helper\Shipping::calculateShipping($shippingRateId);
				$cart['shipping']     = $shippingList['order_shipping_rate'];
				$cart['shipping_vat'] = $shippingList['shipping_vat'];
				$cart                 = RedshopHelperDiscount::modifyDiscount($cart);
			}
			$this->replacements[$shippingTemplate] = $shippingTemplateHtml;
		}
		else
		{
			$this->replacements[$shippingBoxTemplate] = '';
			$this->replacements[$shippingTemplate] = '';
		}

		$eanNumber = 0;

		if (!empty($billingAddresses) && !empty($billingAddresses->ean_number))
		{
			$eanNumber = 1;
		}

		if (!$this->isTagExists('{billing_template}'))
		{
			$subReplacement['{billing_address}'] = '{billing_address}{billing_template}';
		}

		if ($usersInfoId)
		{
			$this->template = RedshopHelperBillingTag::replaceBillingAddress($this->template, $billingAddresses);
			$billingTemplate     = '';
		}
		else
		{
			$lists                            = [];
			$lists['shipping_customer_field'] = Redshop\Fields\SiteHelper::renderFields(RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS);
			$lists['shipping_company_field']  = Redshop\Fields\SiteHelper::renderFields(RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS);

			$this->replacements['{billing_address}'] = '';

			JPluginHelper::importPlugin('redshop_checkout');
			$dispatcher = RedshopHelperUtility::getDispatcher();

			$billingTemplate = RedshopLayoutHelper::render(
				'tags.checkout.onestep.billing',
				array(
					'lists' => $lists,
					'username' =>$app->input->getString('username', ''),
					'dispatcher' => $dispatcher
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);
		}

		$this->replacements['{billing_template}'] = $billingTemplate;

		if ($this->isTagExists('{edit_billing_address}') && $usersInfoId)
		{
			$this->replacements['{edit_billing_address}'] = RedshopLayoutHelper::render(
				'tags.common.modal',
				array(
					'class' => 'modal btn btn-primary',
					'link' => JRoute::_('index.php?option=com_redshop&view=account_billto&tmpl=component&return=checkout&setexit=1&Itemid=' . $itemId),
					'text' => JText::_('COM_REDSHOP_EDIT'),
					'x' => 800,
					'y' => 500
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);
		}
		else
		{
			$this->replacements['{edit_billing_address}'] = '';
		}

		$isCompany = isset($billingAddresses->is_company) ? $billingAddresses->is_company : 0;

		$this->template = RedshopTagsReplacer::_(
			'shippingaddress',
			$this->template,
			array(
				'usersInfoId' => $usersInfoId,
				'shippingAddresses' => $this->data['shippingAddresses'],
				'billingAddresses' => $billingAddresses,
				'isCompany' => $isCompany
			)
		);

		JPluginHelper::importPlugin('redshop_checkout');
		$dispatcher->trigger('onRenderInvoiceOneStepCheckout', array(&$this->template));

		if ($usersInfoId && !empty($billingAddresses))
		{
			$paymentTemplateHtml = RedshopTagsReplacer::_(
				'paymentmethod',
				$paymentTemplateHtml,
				array(
					'paymentMethodId' => $paymentMethodId,
					'isCompany' => $isCompany,
					'eanNumber' => $eanNumber
				)
			);

			$this->replacements[$paymentTemplate] = $paymentTemplateHtml;
		}
		else
		{
			$this->replacements[$paymentTemplate] = '';
		}

		$this->template = $this->strReplace($subReplacement, $this->template);
		$this->template = $this->strReplace($this->replacements, $this->template);

		$this->template = \RedshopTagsReplacer::_(
			'commondisplaycart',
			$this->template,
			array(
				'usersInfoId' => $usersInfoId,
				'shippingRateId' => $shippingRateId,
				'paymentMethodId' => $paymentMethodId,
				'itemId' => $itemId,
				'customerNote' => '',
				'regNumber' => '',
				'thirpartyEmail' => '',
				'customerMessage' => '',
				'referralCode' => '',
				'shopId' => '',
				'data' => array()
			)
		);

		$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);
		$showLoginTemplate = (!$user->id && empty($auth['users_info_id']) && Redshop::getConfig()->getInt('REGISTER_METHOD') != 1 && Redshop::getConfig()->getInt('REGISTER_METHOD') != 3);

		$this->template =  RedshopLayoutHelper::render(
			'tags.checkout.onestep.template',
			array(
				'loginTemplate' => $loginTemplate,
				'showLoginTemplate' => $showLoginTemplate,
				'oneStepTemplateHtml' => $this->template
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		return parent::replace();
	}
}