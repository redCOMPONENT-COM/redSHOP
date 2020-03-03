<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
use Joomla\Utilities\ArrayHelper;
defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  3.0
 */
class RedshopTagsSectionsPaymentMethod extends RedshopTagsAbstract
{
	public $tags = array('{payment_heading}', '{split_payment}');

	public function init(){}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION
	 */
	public function replace()
	{
		$userId = JFactory::getUser()->id;

		$paymentMethods = RedshopHelperPayment::info();

		// Get common payment methods of product in this cart
		$commonPaymentMethods = RedshopHelperPayment::getPaymentMethodInCheckOut($paymentMethods);

		if (!empty($commonPaymentMethods))
		{
			$this->addReplace('{payment_heading}', JText::_('COM_REDSHOP_PAYMENT_METHOD'));
			$this->addReplace('{split_payment}', '');

			$subTemplate = $this->getTemplateBetweenLoop('{payment_loop_start}', '{payment_loop_end}');

			if (!empty($subTemplate))
			{
				$templateMiddle = $subTemplate['template'];
				$shopperGroupId = RedshopHelperUser::getShopperGroup($userId);
				$paymentDisplay = "";
				$hasCreditCard  = false;

				// Filter payment gateways array for shopperGroups
				$paymentMethods = array_filter(
					$paymentMethods,
					function ($paymentMethod) use ($shopperGroupId)
					{
						$paymentFilePath = JPATH_SITE
							. '/plugins/redshop_payment/'
							. $paymentMethod->name . '/' . $paymentMethod->name . '.php';

						if (!JFile::exists($paymentFilePath))
						{
							return false;
						}

						$shopperGroups  = $paymentMethod->params->get('shopper_group_id', array());

						if (!is_array($shopperGroups))
						{
							$shopperGroups = array($shopperGroups);
						}

						$shopperGroups = ArrayHelper::toInteger($shopperGroups);

						if (in_array((int) $shopperGroupId, $shopperGroups) || (!isset($shopperGroups[0]) || 0 == $shopperGroups[0]))
						{
							return true;
						}

						return false;
					}
				);

				$totalPaymentMethod = count($paymentMethods);

				if ($totalPaymentMethod > 0)
				{
					foreach ($paymentMethods as $index => $oneMethod)
					{
						$paymentDisplay .= $this->replacePaymentMethod($hasCreditCard, $templateMiddle, $oneMethod, $commonPaymentMethods, $totalPaymentMethod, $index);
					}
				}

				$templateDesc = $subTemplate['begin'] . $paymentDisplay . $subTemplate['end'];

				if (count($paymentMethods) == 1 && !$hasCreditCard)
				{
					$templateDesc = RedshopLayoutHelper::render(
						'tags.common.tag',
						array(
							'tag' => 'div',
							'text' => $templateDesc,
							'attr' => 'style="display:none;"'
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);
				}

				$this->template = $templateDesc;
			}
		}
		else
		{
			//clear
			$this->replacements['{creditcard_information}'] = '';
			$this->replacements['{payment_loop_start}'] = '';
			$this->replacements['{payment_loop_end}'] = '';
			$this->replacements['{payment_heading}'] = JText::_('COM_REDSHOP_PAYMENT_METHOD_CONFLICT');
			$this->replacements['{payment_method_name}'] = RedshopHelperPayment::displayPaymentMethodInCheckOut($paymentMethods);

			$this->template = $this->strReplace($this->replacements, $this->template);
		}

		return parent::replace();
	}

	/**
	 * Replace payment method
	 *
	 * @param   boolean    $hasCreditCard
	 * @param   string    $templateMiddle
	 * @param   object    $oneMethod
	 * @param   array     $commonPaymentMethods
	 * @param   integer   $totalPaymentMethod
	 * @param   integer   $index
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION
	 */
	public function replacePaymentMethod(&$hasCreditCard, $templateMiddle, $oneMethod, $commonPaymentMethods, $totalPaymentMethod, $index)
	{
		$paymentDisplay     = '';
		$this->replacements = array();

		if (in_array($oneMethod->name, $commonPaymentMethods))
		{
			$cardInformation = "";
			$displayPayment  = "";
			include_once JPATH_SITE . '/plugins/redshop_payment/' . $oneMethod->name . '/' . $oneMethod->name . '.php';

			$lang = JFactory::getLanguage();
			$lang->load('plg_redshop_payment_' . $oneMethod->name, JPATH_ADMINISTRATOR, $lang->getTag(), true);

			$privatePerson = $oneMethod->params->get('private_person', '');
			$business      = $oneMethod->params->get('business', '');
			$isCreditCard  = (boolean) $oneMethod->params->get('is_creditcard', 0);
			$checked       = $this->data['paymentMethodId'] === $oneMethod->name || $totalPaymentMethod <= 1;
			$checked       = 'checked="' . $checked . '"';
			$logo          = $oneMethod->params->get('logo', '');
			$showImage     = (!empty($logo) && JFile::exists(JPATH_ROOT . '/' . $logo)) ? 1 : 0;

			$paymentRadioOutput = RedshopLayoutHelper::render(
				'tags.payment_method.payment_radio',
				array(
					'oneMethod'          => $oneMethod,
					'paymentMethodId'    => $this->data['paymentMethodId'],
					'index'              => $index,
					'totalPaymentMethod' => $totalPaymentMethod,
					'checked'            => $checked,
					'isCompany'          => $this->data['isCompany'],
					'eanNumber'          => $this->data['eanNumber'],
					'logo'               => $logo,
					'showImage'          => $showImage,
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$isSubscription = \Redshop\Cart\Helper::checkProductSubscription();

			// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
			$isBankTransferPaymentType = \RedshopHelperPayment::isPaymentType($oneMethod->name);

			if ($isBankTransferPaymentType && !$checked) {
			    $checked = $isBankTransferPaymentType;
            }

			if ($oneMethod->name == 'rs_payment_eantransfer' || $isBankTransferPaymentType)
			{
				if ($this->data['isCompany'] == 0 && $privatePerson == 1)
				{
					$displayPayment = $paymentRadioOutput;
				}
				else
				{
					if ($this->data['isCompany'] == 1 && $business == 1 &&
						($oneMethod->name != 'rs_payment_eantransfer'
							|| ($oneMethod->name == 'rs_payment_eantransfer' && $this->data['eanNumber'] != 0)))
					{
						$displayPayment = $paymentRadioOutput;
					}
				}
			}
			elseif ($isSubscription)
			{
				$subscriptionPlan = $this->getSubscriptionPlans();
				$displayPayment   = RedshopLayoutHelper::render(
					'tags.payment_method.payment_subscription',
					array(
						'oneMethod' => $oneMethod,
						'index' => $index,
						'checked' => $checked,
						'subscriptionPlan' => $subscriptionPlan
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);
			}
			else
			{
				$displayPayment = $paymentRadioOutput;
			}

			if ($isCreditCard)
			{
				$cardInformation = '<div id="divcardinfo_' . $oneMethod->name . '">';

				$cart = JFactory::getSession()->get('cart');

				if ($checked && Redshop::getConfig()->get('ONESTEP_CHECKOUT_ENABLE')  && $cart['total'] > 0)
				{
					$cardInformation .= \Redshop\Payment\Helper::replaceCreditCardInformation($oneMethod->name);
				}

				$cardInformation .= '</div>';

				$hasCreditCard = true;
			}

			$paymentDisplay .= str_replace(
				'<div class="extrafield_payment">',
				'<div class="extrafield_payment" id="' . $oneMethod->name . '">',
				$templateMiddle
			);

			$this->replacements['{payment_method_name}'] = $displayPayment;
			$this->replacements['{creditcard_information}'] = $cardInformation;
			$this->replaceExtraField($checked, $oneMethod);

			$paymentDisplay = $this->strReplace($this->replacements, $paymentDisplay);
		}

		return $paymentDisplay;
	}

	/**
	 * Replace extra field
	 *
	 * @param   boolean   $checked
	 * @param   object    $oneMethod
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION
	 */
	public function replaceExtraField($checked, $oneMethod)
	{
		if ($this->isTagExists('{payment_extrafields}'))
		{
			$paymentExtraFieldsHtml = '';

			if ($checked)
			{
				$layoutFile = new JLayoutFile('order.payment.extrafields');

				// Append plugin JLayout path to improve view based on plugin if needed.
				$layoutFile->addIncludePath(JPATH_SITE . '/plugins/' . $oneMethod->type . '/' . $oneMethod->name . '/layouts');
				$paymentExtraFieldsHtml =  $layoutFile->render(array('plugin' => $oneMethod));
			}

			$paymentExtraFieldsHtml = RedshopLayoutHelper::render(
				'tags.common.tag',
				array(
					'text' => $paymentExtraFieldsHtml,
					'tag' => 'div',
					'class' => 'extrafield_payment',
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements['{payment_extrafields}'] = $paymentExtraFieldsHtml;
		}
	}

	/**
	 * Display credit card form based on payment method
	 *
	 * @param   integer  $payment_method_id  Payment Method ID for which form needs to be prepare
	 *
	 * @return  string     Credit Card form display data in HTML
	 *
	 * @since   3.0
	 */
	public function getSubscriptionPlans()
	{
		// @Todo get subscription plan
		return '';
	}
}