<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
use Joomla\Registry\Registry;
use \Redshop\Traits\Replace;

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */

class RedshopTagsSectionsCommonDisplayCart extends RedshopTagsAbstract
{
	use Replace\Template, Replace\Discount, Replace\ConditionTag, Replace\Tax, Replace\TermsConditions,
		Replace\NewsletterSubscription;

	/**
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $tags = array(
		'{customer_note}', '{customer_note_lbl}', '{requisition_number}', '{shop_more}', '{checkout_button}',
		'{thirdparty_email}', '{thirdparty_email_lbl}', '{customer_note}', '{customer_note_lbl}', '{customer_message_chk}',
		'{customer_message_chk_lbl}', '{customer_message}', '{customer_note}', '{referral_code}', '{referral_code_lbl}',
		'{requisition_number}', '{requisition_number_lbl}', '{shop_more}', '{checkout_back_button}', '{shipping_lbl}',
		'{tax_with_shipping_lbl}', '{checkout}', '{checkout_button}', '{quotation_request}', '{coupon_code_lbl}', '{print}'
	);

	/**
	 * Init
	 *
	 * @return  mixed
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function init()
	{

	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replace()
	{
		$session         = JFactory::getSession();
		$cart            = \Redshop\Cart\Helper::getCart();
		$usersess        = $session->get('rs_user');
		$usersInfoId     = $this->data['usersInfoId'];
		$shippingRateId  = $this->data['shippingRateId'];
		$paymentMethodId = $this->data['paymentMethodId'];
		$itemId          = $this->data['itemId'];
		$customerNote    = $this->data['customerNote'];
		$regNumber       = $this->data['regNumber'];
		$thirdpartyEmail = $this->data['thirpartyEmail'];
		$customerMessage = $this->data['customerMessage'];
		$referralCode    = $this->data['referralCode'];
		$shopId          = $this->data['shopId'];
		$layoutOptions   = RedshopLayoutHelper::$layoutOption;

		$usersess['rs_user_info_id'] = $usersInfoId;
		unset($cart['shipping']);
		$session->set('rs_user', $usersess);
		$cart = \Redshop\Cart\Cart::modify($cart, JFactory::getUser()->id);

		if ($shippingRateId && $cart['free_shipping'] != 1)
		{
			$shipArr              = Redshop\Helper\Shipping::calculateShipping($shippingRateId);
			$cart['shipping']     = $shipArr['order_shipping_rate'];
			$cart['shipping_vat'] = (!isset($shipArr['shipping_vat'])) ? 0 : $shipArr['shipping_vat'];
		}

		$cart = \RedshopHelperDiscount::modifyDiscount($cart);

		// Plugin support:  Process the shipping cart
		JPluginHelper::importPlugin('redshop_product');
		JPluginHelper::importPlugin('redshop_checkout');
		RedshopHelperUtility::getDispatcher()->trigger(
			'onDisplayShoppingCart', array(&$cart, &$this->template, $usersInfoId, $shippingRateId, $paymentMethodId, $this->data['data'])
		);

		$paymentMethod = \RedshopHelperOrder::getPaymentMethodInfo($paymentMethodId);
		$paymentMethod = $paymentMethod[0];

		$paymentMethod->params       = new Registry($paymentMethod->params);
		$isCreditcard               = $paymentMethod->params->get('is_creditcard', '');
		$paymentOprand              = $paymentMethod->params->get('payment_oprand', '');
		$paymentDiscountIsPercent = $paymentMethod->params->get('payment_discount_is_percent', '');
		$paymentPrice               = $paymentMethod->params->get('payment_price', '');
		$acceptedCredictCard       = $paymentMethod->params->get("accepted_credict_card");

		$paymentInfo                              = new stdClass;
		$paymentInfo->payment_price               = $paymentPrice;
		$paymentInfo->is_creditcard               = $isCreditcard;
		$paymentInfo->payment_oprand              = $paymentOprand;
		$paymentInfo->payment_discount_is_percent = $paymentDiscountIsPercent;
		$paymentInfo->accepted_credict_card       = $acceptedCredictCard;

		if (Redshop::getConfig()->get('PAYMENT_CALCULATION_ON') == 'subtotal')
		{
			$paymentAmount = $cart['product_subtotal'];
		}
		else
		{
			$paymentAmount = $cart['total'];
		}

		$paymentArray   = RedshopHelperPayment::calculate($paymentAmount, $paymentInfo, $cart['total']);
		$cart['total']  = $paymentArray[0];
		$paymentAmount = $paymentArray[1];

		if (isset($cart['discount']) === false)
		{
			$cart['discount'] = 0;
		}

		$cart['payment_oprand'] = $paymentOprand;
		$cart['payment_amount'] = $paymentAmount;

		$this->template = $this->replaceTemplate($cart, $this->template, 1);

		$thirdPartyEmailValue = "";

		if ($thirdpartyEmail != "")
		{
			$thirdPartyEmailValue = $thirdpartyEmail;
		}
		elseif (isset($cart['thirdparty_email']))
		{
			$thirdPartyEmailValue = $cart['thirdparty_email'];
		}

		if (strstr($this->template, "{thirdparty_email}"))
		{
			$thirdpartyEmail = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'type' => 'text',
					'name' => 'thirdparty_email',
					'id' => 'thirdparty_email',
					'value' => $thirdPartyEmailValue
				),
				'',
				$layoutOptions
			);

			$thirdpartyEmailLbl = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'id' => 'thirdparty_email',
					'text' => JText::_('COM_REDSHOP_THIRDPARTY_EMAIL_LBL')
				),
				'',
				$layoutOptions
			);

			$this->addReplace('{thirdparty_email}', $thirdpartyEmail);
			$this->addReplace('{thirdparty_email_lbl}', $thirdpartyEmailLbl);
		}

		$customerNoteValue = $customerNote;

		if (empty($customerNote) && isset($cart['customer_note']))
		{
			$customerNoteValue = $cart['customer_note'];
		}

		$requisitionNumber = $regNumber;

		if (empty($regNumber) && isset($cart['requisition_number']))
		{
			$requisitionNumber = $cart['requisition_number'];
		}

		if ($this->isTagExists('{customer_note}'))
		{
			$customerNoteHtml = RedshopLayoutHelper::render(
				'tags.common.textarea',
				array(
					'name' => 'customer_note',
					'id' => 'customer_note',
					'content' => $customerNoteValue
				),
				'',
				$layoutOptions
			);

			$customerNoteLbl = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'id' => 'customer_note',
					'text' => JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL')
				),
				'',
				$layoutOptions
			);

			$this->addReplace('{customer_note}', $customerNoteHtml);
			$this->addReplace('{customer_note_lbl}', $customerNoteLbl);
		}

		$customerMessageChk = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'rs_customer_message_chk',
				'id' => 'rs_customer_message_chk',
				'type' => 'checkbox',
				'attr' => 'onclick="javascript:displaytextarea(this);"'
			),
			'',
			$layoutOptions
		);

		$customerMessageChkLbl = RedshopLayoutHelper::render(
			'tags.common.label',
			array(
				'id' => 'rs_customer_message_chk',
				'text' => JText::_('COM_REDSHOP_CUSTOMER_MESSAGE_LBL')
			),
			'',
			$layoutOptions
		);

		$this->addReplace('{customer_message_chk}', $customerMessageChk);
		$this->addReplace('{customer_message_chk_lbl}', $customerMessageChkLbl);



		$customerMessage  = RedshopLayoutHelper::render(
			'tags.checkout.customer_message',
			array('customerMessage' => $customerMessage),
			'',
			$layoutOptions
		);

		$this->addReplace('{customer_message}', $customerMessage);

		$referralCode = RedshopLayoutHelper::render(
			'tags.common.input',
			array(
				'name' => 'txt_referral_code',
				'id' => 'txt_referral_code',
				'type' => 'text',
				'value' => $referralCode
			),
			'',
			$layoutOptions
		);

		$referralCodeLbl = RedshopLayoutHelper::render(
			'tags.common.label',
			array(
				'id' => 'txt_referral_code',
				'text' => JText::_('COM_REDSHOP_REFERRAL_CODE_LBL')
			),
			'',
			$layoutOptions
		);

		$this->addReplace('{referral_code}', $referralCode);
		$this->addReplace('{referral_code_lbl}', $referralCodeLbl);

		if ($this->isTagExists('{requisition_number}'))
		{
			$regNumber = RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'name' => 'requisition_number',
					'id' => 'requisition_number',
					'type' => 'text',
					'value' => $requisitionNumber,
					'class' => 'inputbox'
				),
				'',
				$layoutOptions
			);

			$reqNumberLbl = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'id' => 'requisition_number',
					'text' => JText::_('COM_REDSHOP_REQUISITION_NUMBER')
				),
				'',
				$layoutOptions
			);

			$this->addReplace('{requisition_number}', $regNumber);
			$this->addReplace('{requisition_number_lbl}', $reqNumberLbl);
		}

		if ($this->isTagExists('{shop_more}'))
		{
			if (Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK') != '')
			{
				$shopMoreLink = JRoute::_(Redshop::getConfig()->get('CONTINUE_REDIRECT_LINK'));
			}
			elseif ($catItemId = RedshopHelperRouter::getCategoryItemid())
			{
				$shopMoreLink = JRoute::_('index.php?option=com_redshop&view=category&Itemid=' . $catItemId);
			}
			else
			{
				$shopMoreLink = JRoute::_('index.php');
			}

			$shopMore = RedshopLayoutHelper::render(
				'tags.common.button',
				array(
					'class' => 'blackbutton btn',
					'text' => JText::_('COM_REDSHOP_SHOP_MORE'),
					'attr' => 'onclick="javascript:document.location=\'' . $shopMoreLink . '\'" type="button"'
				),
				'',
				$layoutOptions
			);

			$this->addReplace('{shop_more}', $shopMore);
		}

		if ($this->isTagExists('{checkout_back_button}'))
		{
			$checkoutBack = RedshopLayoutHelper::render(
				'tags.common.button',
				array(
					'class' => 'blackbutton btn',
					'text' => JText::_('COM_REDSHOP_BACK_BUTTON'),
					'attr' => 'onclick="javascript: history.go(-1);"'
				),
				'',
				$layoutOptions
			);

			$this->addReplace('{checkout_back_button}', $checkoutBack);
		}

		$this->template = $this->replaceConditionTag($this->template, $paymentAmount, 0, $paymentOprand);

		if (!empty($shippingRateId) && Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
		{
			$shippinPriceWithVat = RedshopHelperProductPrice::formattedPrice($cart ['shipping']);
			$shippinPrice        = RedshopHelperProductPrice::formattedPrice($cart ['shipping'] - $cart['shipping_vat']);
		}
		else
		{
			$this->addReplace('{shipping_lbl}', '');
			$this->addReplace('{tax_with_shipping_lbl}', '');
		}

		$this->template = $this->replaceTermsConditions($this->template, $itemId);
		$this->template = $this->replaceNewsletterSubscription($this->template);

		$checkout = RedshopLayoutHelper::render(
			'tags.checkout.button',
			array(
				'itemId' => $itemId,
				'usersInfoId' => $usersInfoId,
				'orderId' => \JFactory::getApplication()->input->get('order_id'),
				'shopId' => $shopId,
				'shippingRateId' => $shippingRateId,
				'paymentMethodId' => $paymentMethodId,
				'cartTotal' => $cart['total']
			),
			'',
			$layoutOptions
		);

		$this->addReplace('{checkout}', $checkout);
		$this->addReplace('{checkout_button}', $checkout);

		$qlink             = JRoute::_('index.php?option=com_redshop&view=quotation&tmpl=component&return=1&Itemid=' . $itemId);
		$quotation_request = RedshopLayoutHelper::render(
			'tags.checkout.quotation_request',
			array(
				'link' => $qlink
			),
			'',
			$layoutOptions
		);

		$this->addReplace('{quotation_request}', $quotation_request);

		if ($this->isTagExists('{coupon_code_lbl}'))
		{
			$coupon = '';

			if (isset($cart["coupon_code"]))
			{
				$coupon_price = \Redshop\Promotion\Coupon::getCouponPrice();

				$coupon = RedshopLayoutHelper::render(
					'tags.checkout.coupon_code_lbl',
					array(
						'couponCode' => $cart['coupon_code'],
						'couponPrice' => $coupon_price
					),
					'',
					$layoutOptions
				);
			}

			$this->addReplace('{coupon_code_lbl}', $coupon);
		}

		$this->addReplace('{print}', '');

		$this->template = Redshop\Cart\Render\Label::replace($this->template);

		\Redshop\Cart\Helper::setCart((array) $cart);

		return parent::replace();
	}
}