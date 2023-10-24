<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Payment;

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Payment Helper
 *
 * @since 3.0
 */
class Helper
{
    /**
     * @param   int  $paymentMethodId
     *
     * @return string
     * @since 3.0
     */
    public static function replaceCreditCardInformation($paymentMethodId = 0)
    {
        if (empty($paymentMethodId)) {
            \JFactory::getApplication()->enqueueMessage(
                Text::_('COM_REDSHOP_PAYMENT_NO_CREDIT_CARDS_PLUGIN_LIST_FOUND'),
                'error'
            );

            return '';
        }

        $paymentMethod = \RedshopHelperOrder::getPaymentMethodInfo($paymentMethodId);
        $paymentMethod = $paymentMethod[0];

        $cardInfo = "";

        if (
            file_exists(
                JPATH_SITE . '/plugins/redshop_payment/' . $paymentMethod->element . '/' . $paymentMethod->element . '.php'
            )
        ) {
            $paymentParams      = new Registry($paymentMethod->params);
            $acceptedCreditCard = $paymentParams->get("accepted_credict_card", array());

            if (
                $paymentParams->get('is_creditcard', 0)
                && !empty($acceptedCreditCard)
            ) {
                $cardInfo = \RedshopLayoutHelper::render(
                    'order.payment.creditcard',
                    array(
                        'pluginParams' => $paymentParams,
                    )
                );
            } else {
                \JFactory::getApplication()->enqueueMessage(
                    Text::_('COM_REDSHOP_PAYMENT_CREDIT_CARDS_NOT_FOUND'),
                    'error'
                );
            }
        }

        return $cardInfo;
    }
}