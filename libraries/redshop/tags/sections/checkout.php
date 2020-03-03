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
class RedshopTagsSectionsCheckout extends RedshopTagsAbstract
{
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
		$app  = JFactory::getApplication();
		$itemId = RedshopHelperRouter::getCheckoutItemId();
		$usersInfoId     = $this->data['usersInfoId'] ?? \JFactory::getApplication()->input->getInt('users_info_id');
		$shippingRateId  = $this->data['shippingRateId'];
		$paymentMethodId = $this->data['paymentMethodId'];
		$cart            = $this->data['cart'];

		if ($itemId == 0)
		{
			$itemId = $app->input->getInt('Itemid');
		}

		$ccInfo     = $app->input->getInt('ccinfo');
		$glsMobile = $app->input->getString('gls_mobile');

		$shopId = $app->input->getString('shop_id') . '###' . $glsMobile;

		if ($this->data['isCreditcard'] == 1 && $ccInfo != '1' && $cart['total'] > 0)
		{
			$this->template = \Redshop\Payment\Helper::replaceCreditCardInformation($paymentMethodId);
		}
		else {
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
                    'shopId' => $shopId,
                    'data' => array()
                )
            );

            $this->addReplace('{without_vat}', '');
            $this->addReplace('{with_vat}', '');
        }

		$this->template = RedshopLayoutHelper::render(
			'tags.checkout.template',
			array(
				'isCreditcard' => $this->data['isCreditcard'] == 1 && $ccInfo != '1' && $cart['total'] > 0,
				'content' => $this->template,
                'usersInfoId' => $usersInfoId,
                'shippingRateId' => $shippingRateId,
                'paymentMethodId' => $paymentMethodId,
                'itemId' => $itemId,
                'customerNote' => '',
                'regNumber' => '',
                'thirpartyEmail' => '',
                'customerMessage' => '',
                'referralCode' => '',
                'shopId' => $shopId,
                'data' => array()
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		return parent::replace();
	}
}