<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$configobj       = Redconfiguration::getInstance();
$order_functions = order_functions::getInstance();

$url      = JURI::base();
$Itemid   = RedshopHelperRouter::getCheckoutItemId();
$order_id = JFactory::getApplication()->input->getInt('oid');
$encr     = !empty(trim($encr)) ? '&encr=' . $encr : '';

$order     = Redshop\Entity\Order::getInstance($order_id)->getItem();
$orderitem = RedshopHelperOrder::getOrderItemDetail($order_id);

if ($order->order_total > 0 && !Redshop::getConfig()->get('USE_AS_CATALOG')) {
    $paymentmethod = $order_functions->getOrderPaymentDetail($order_id);
    $paymentmethod = RedshopHelperOrder::getPaymentMethodInfo($paymentmethod[0]->payment_method_class);
    $paymentmethod = $paymentmethod[0];

    $paymentpath   = JPATH_SITE . '/plugins/redshop_payment/' . $paymentmethod->element . '/' . $paymentmethod->element . '.xml';
    $paymentparams = new JRegistry($paymentmethod->params);

    echo '<div>';

    $is_creditcard = $paymentparams->get('is_creditcard', '');
    $is_redirected = $paymentparams->get('is_redirected', 0);

    if (!$is_creditcard || $is_redirected == 1) {
        $adminpath        = JPATH_ADMINISTRATOR . '/components/com_redshop';
        $invalid_elements = $paymentparams->get('invalid_elements', '');

        $billingAddresses = RedshopHelperOrder::getOrderBillingUserInfo($order->order_id);

        if (isset($billingAddresses)) {
            if (isset($billingAddresses->country_code)) {
                $billingAddresses->country_2_code = RedshopHelperWorld::getCountryCode2(
                    $billingAddresses->country_code
                );
            }

            if (isset($billingAddresses->state_code)) {
                $billingAddresses->state_2_code = $billingAddresses->state_code;
            }
        }

        $shippingaddresses = RedshopHelperOrder::getOrderShippingUserInfo($order->order_id);

        if (isset($shippingaddresses)) {
            if (isset($shippingaddresses->country_code)) {
                $shippingaddresses->country_2_code = RedshopHelperWorld::getCountryCode2(
                    $shippingaddresses->country_code
                );
            }

            if (isset($shippingaddresses->state_code)) {
                $shippingaddresses->state_2_code = $shippingaddresses->state_code;
            }
        }

        $cart_quantity = 0;

        for ($i = 0, $in = count($orderitem); $i < $in; $i++) {
            $cart_quantity += $orderitem[$i]->product_quantity;
        }

        $values['shippinginfo']   = $shippingaddresses;
        $values['billinginfo']    = $billingAddresses;
        $values['carttotal']      = $order->order_total;
        $values['order_subtotal'] = $order->order_subtotal;
        $values["order_id"]       = $order_id;
        $values["order_quantity"] = $cart_quantity;
        $values['payment_plugin'] = $paymentmethod->element;
        $values['odiscount']      = $order->order_discount;
        $values['order']          = $order;

        if ($values['payment_plugin'] == "rs_payment_epayrelay") {
            $epay_url  = "https://relay.ditonlinebetalingssystem.dk/relay/v2/relay.cgi/";
            $actionurl = $url . 'index.php?option=com_redshop&view=epayrelay&oid=' . $order_id . '&Itemid=' . $Itemid;
            $results   = "<form method='post' action='" . $actionurl . "' name='epayrelayfrm' id='epayrelayfrm'>";
            $results   .= "<input type='hidden' name='order_id' value='" . $values["order_id"] . "'>";
            $results   .= "<table width='100%' border='0'><tr><td align='right' style='padding-right:50px'> <input type='submit' name='paynowbtn' value='Pay Now'></td></tr></table>";
            $results   .= "<input type='hidden' name='payment_plugin' value='" . $values['payment_plugin'] . "'>";
            $results   .= "</form>";
            echo $results;

            ?>
            <script language="javascript">
                document.getElementById('epayrelayfrm').submit();
            </script>
            <?php
        } else {
            JPluginHelper::importPlugin('redshop_payment');
            $dispatcher = RedshopHelperUtility::getDispatcher();
            $results    = $dispatcher->trigger('onPrePayment', array($values['payment_plugin'], $values));

            $key = array_search(true, $results);

            if (is_array($results) && $key !== false) {
                $app = JFactory::getApplication();
                $app->redirect(
                    Redshop\IO\Route::_(
	                    'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $order_id . '&Itemid=' . $Itemid . $encr,
                        false
                    )
                );
            }
        }
    }
} else {
    $app = JFactory::getApplication();
    $app->redirect(
        Redshop\IO\Route::_(
	        'index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $order_id . '&Itemid=' . $Itemid . $encr,
            false
        )
    );
}
?>
</div>
