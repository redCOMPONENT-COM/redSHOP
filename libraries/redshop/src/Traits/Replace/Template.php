<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Traits\Replace;

defined('_JEXEC') || die;

/**
 * For classes extends class RedshopTagsAbstract
 *
 * @since  3.0
 */
trait Template
{
	/**
	 * @param        $cart
	 * @param        $cartData
	 * @param   int  $checkout
	 *
	 * @return string|string[]
	 * @throws Exception
	 * @since 3.0
	 */
	public function replaceTemplate($cart, $cartData, $checkout = 1)
	{
		\JPluginHelper::importPlugin('redshop_checkout');
		\JPluginHelper::importPlugin('redshop_shipping');
		$dispatcher  = \RedshopHelperUtility::getDispatcher();
		$replacement = array();
		$dispatcher->trigger('onBeforeReplaceTemplateCart', array(&$cart, &$cartData, $checkout));

		if ($this->isTagExists('{product_loop_start}') && $this->isTagExists('{product_loop_end}'))
		{
			$templateData = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}', $cartData);
			$templateMiddle = $this->replaceCartItem($templateData['template'], $cart, 1, \Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'));
			$cartData       = $templateData['begin'] . $templateMiddle . $templateData['end'];
		}

		$cartData = \Redshop\Cart\Render\Label::replace($cartData);

		$total                  = $cart['total'];
		$subtotalExclVat        = $cart['subtotal_excl_vat'];
		$productSubtotal        = $cart['product_subtotal'];
		$productSubtotalExclVat = $cart['product_subtotal_excl_vat'];
		$subtotal               = $cart['subtotal'];
		$discountExVat          = $cart['discount_ex_vat'];
		$discountTotal          = $cart['voucher_discount'] + $cart['coupon_discount'];
		$discountAmount         = $cart["cart_discount"];
		$tax                    = $cart['tax'];
		$subTotalVat            = $cart['sub_total_vat'];
		$shipping               = $cart['shipping'];
		$shippingVat            = $cart['shipping_tax'];

		if ($total <= 0)
		{
			$total = 0;
		}

		if (isset($cart ['discount_type']) === false)
		{
			$cart ['discount_type'] = 0;
		}

		$tmpDiscount   = $discountTotal;
		$discountTotal = \RedshopHelperProductPrice::formattedPrice($discountTotal + $discountAmount, true);

		if (!\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (\Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && \Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
		{
			if (strpos($cartData, '{product_subtotal_lbl}') !== false)
			{
				$replacement['{product_subtotal_lbl}'] = \JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_LBL');
			}

			if (strpos($cartData, '{product_subtotal_excl_vat_lbl}') !== false)
			{
				$replacement['{product_subtotal_excl_vat_lbl}'] = \JText::_('COM_REDSHOP_PRODUCT_SUBTOTAL_EXCL_LBL');
			}

			if (strpos($cartData, '{shipping_with_vat_lbl}') !== false)
			{
				$replacement['{shipping_with_vat_lbl}'] = \JText::_('COM_REDSHOP_SHIPPING_WITH_VAT_LBL');
			}

			if (strpos($cartData, '{shipping_excl_vat_lbl}') !== false)
			{
				$replacement['{shipping_excl_vat_lbl}'] = \JText::_('COM_REDSHOP_SHIPPING_EXCL_VAT_LBL');
			}

			if (strpos($cartData, '{product_price_excl_lbl}') !== false)
			{
				$replacement['{product_price_excl_lbl}'] = \JText::_('COM_REDSHOP_PRODUCT_PRICE_EXCL_LBL');
			}

			$replacement['{total}'] = \RedshopLayoutHelper::render(
				'tags.common.price',
				array(
					'class' => 'spnTotal',
					'id' => 'spnTotal',
					'htmlPrice' => \RedshopHelperProductPrice::formattedPrice($total, true)
				),
				'',
				\RedshopLayoutHelper::$layoutOption
			);

			$replacement['{total_excl_vat}'] =
				\RedshopLayoutHelper::render(
					'tags.common.price',
					array(
						'class' => 'spnTotal',
						'id' => 'spnTotal',
						'htmlPrice' => \RedshopHelperProductPrice::formattedPrice($subtotalExclVat)
					),
					'',
					\RedshopLayoutHelper::$layoutOption
				);

			$checkVat = \Redshop\Template\Helper::isApplyVat($cartData);

			if (!empty($checkVat))
			{
				$replacement['{subtotal}'] = \RedshopHelperProductPrice::formattedPrice($subtotal);
				$replacement['{product_subtotal}'] = \RedshopHelperProductPrice::formattedPrice($productSubtotal);
			}
			else
			{
				$replacement['{subtotal}'] = \RedshopHelperProductPrice::formattedPrice($subtotalExclVat);
				$replacement['{product_subtotal}'] = \RedshopHelperProductPrice::formattedPrice($productSubtotalExclVat);
			}

			if (($this->isTagExists('{discount_denotation}') || $this->isTagExists('{shipping_denotation}')) && ($discountTotal != 0 || $shipping != 0))
			{
				$replacement['{denotation_label}'] = \JText::_('COM_REDSHOP_DENOTATION_TXT');
			}
			else
			{
				$replacement['{denotation_label}'] = '';
			}

			if ($this->isTagExists('{discount_excl_vat}'))
			{
				$replacement['{discount_denotation}'] = '*';
			}
			else
			{
				$replacement['{discount_denotation}'] = '';
			}

			$replacement['{subtotal_excl_vat}'] = \RedshopHelperProductPrice::formattedPrice($subtotalExclVat);
			$replacement['{product_subtotal_excl_vat}'] = \RedshopHelperProductPrice::formattedPrice($productSubtotalExclVat);
			$replacement['{sub_total_vat}'] = \RedshopHelperProductPrice::formattedPrice($subTotalVat);
			$replacement['{discount_excl_vat}'] = \RedshopHelperProductPrice::formattedPrice($discountExVat);

			$rep = true;

			if (!$checkout)
			{
				if (!\Redshop::getConfig()->get('SHOW_SHIPPING_IN_CART') || !\Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
				{
					$rep = false;
				}
			}
			else
			{
				if (!\Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE'))
				{
					$rep = false;
				}
			}

			if (!empty($rep))
			{
				if ($this->isTagExists('{shipping_excl_vat}'))
				{
					$replacement['{shipping_denotation}'] = '*';
				}
				else
				{
					$replacement['{shipping_denotation}'] = '';
				}

				$replacement['{shipping_excl_vat}'] =
					\RedshopLayoutHelper::render(
						'tags.common.price',
						array(
							'class' => 'spnShippingrate',
							'id' => 'spnShippingrate',
							'htmlPrice' => \RedshopHelperProductPrice::formattedPrice($shipping - $cart['shipping_tax'], true)
						),
						'',
						\RedshopLayoutHelper::$layoutOption
					);

				$replacement['{shipping}'] = \RedshopLayoutHelper::render(
					'tags.common.price',
					array(
						'class' => 'spnShippingrate',
						'id' => 'spnShippingrate',
						'htmlPrice' => \RedshopHelperProductPrice::formattedPrice($shipping, true)
					),
					'',
					\RedshopLayoutHelper::$layoutOption
				);

				$replacement['{order_shipping}'] = \RedshopHelperProductPrice::formattedPrice($shipping, true);
				$replacement['{shipping_lbl}'] = \JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
				$replacement['{tax_with_shipping_lbl}'] = \JText::_('COM_REDSHOP_CHECKOUT_SHIPPING_LBL');
				$replacement['{vat_shipping}'] = \RedshopHelperProductPrice::formattedPrice($shippingVat);
			}
			else
			{
				$replacement['{order_shipping}'] = '';
				$replacement['{shipping_excl_vat}'] = '';
				$replacement['{shipping_lbl}'] = '';
				$replacement['{shipping}'] = '';
				$replacement['{tax_with_shipping_lbl}'] = '';
				$replacement['{vat_shipping}'] = '';
				$replacement['{shipping_denotation}'] = '';
			}
		}
		else
		{
			$replacement['{total}'] = "<span id='spnTotal'></span>";
			$replacement['{shipping_excl_vat}'] = "<span id='spnShippingrate'></span>";
			$replacement['{order_shipping}'] = '';
			$replacement['{shipping_lbl}'] = '';
			$replacement['{shipping}'] = "<span id='spnShippingrate'></span>";
			$replacement['{subtotal}'] = '';
			$replacement['{tax_with_shipping_lbl}'] = '';
			$replacement['{vat_shipping}'] = '';
			$replacement['{subtotal_excl_vat}'] = '';
			$replacement['{shipping_excl_vat}'] = '';
			$replacement['{subtotal_excl_vat}'] = '';
			$replacement['{product_subtotal_excl_vat}'] = '';
			$replacement['{product_subtotal}'] = '';
			$replacement['{sub_total_vat}'] = '';
			$replacement['{discount_excl_vat}'] = '';
			$replacement['{discount_denotation}'] = '';
			$replacement['{shipping_denotation}'] = '';
			$replacement['{denotation_label}'] = '';
			$replacement['{total_excl_vat}'] = '';
		}

		if (!\Redshop::getConfig()->get('APPLY_VAT_ON_DISCOUNT'))
		{
			$totalForDiscount = $subtotalExclVat;
		}
		else
		{
			$totalForDiscount = $subtotal;
		}

		$cartData = $this->replaceDiscount($cartData, $discountAmount + $tmpDiscount, $totalForDiscount, \Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE'));

		if ($checkout)
		{
			$cartData = $this->replaceConditionTag($cartData, $cart['payment_amount'], 0, $cart['payment_oprand']);
		}
		else
		{
			$paymentOprand = (isset($cart['payment_oprand'])) ? $cart['payment_oprand'] : '-';
			$cartData     = $this->replaceConditionTag($cartData, 0, 1, $paymentOprand);
		}

		$cartData = $this->replaceTax(
			$cartData,
			$tax + $shippingVat,
			$discountAmount + $tmpDiscount,
			0,
			\Redshop::getConfig()->getBool('DEFAULT_QUOTATION_MODE')
		);

		$dispatcher->trigger('onAfterReplaceTemplateCart', array(&$cartData, $checkout));

		return $this->strReplace($replacement, $cartData);
	}
}