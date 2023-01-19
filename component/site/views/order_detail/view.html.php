<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Order Detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.6
 */
class RedshopViewOrder_Detail extends RedshopView
{
    /**
     * @var   object
     */
    public $OrdersDetail;

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void          A string if successful, otherwise a Error object.
     *
     * @throws  \Exception
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();

        $orderFunctions = order_functions::getInstance();

        $print = $app->input->getInt('print', 0);

        if ($print) {
            ?>
            <script type="text/javascript" language="javascript">
                window.print();
            </script>
            <?php
        }

        RedshopHelperBreadcrumb::generate();

        $user         = JFactory::getUser();
        $session      = JFactory::getSession();
        $auth         = $session->get('auth');
        $orderId      = $app->input->getInt('oid', $session->get('order_id'));
        $encr         = $app->input->getString('encr', null);
        $orderPayment = $orderFunctions->getOrderPaymentDetail($orderId);

        if ($orderPayment && count($orderPayment)) {
            // Load payment language file
            $language     = JFactory::getLanguage();
            $base_dir     = JPATH_ADMINISTRATOR;
            $language_tag = $language->getTag();
            $extension    = 'plg_redshop_payment_' . ($orderPayment[0]->payment_method_class);

            $language->load($extension, $base_dir, $language_tag, true);
        }

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        $orderDetail = RedshopHelperOrder::getOrderDetails($orderId);

        if ($orderDetail === null) {
            throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
        }

        if ($user->id) {
            $rUser = RedshopHelperUser::getUserInformation(0, '', $orderDetail->user_info_id, false, true);

            if ($rUser->user_email != $user->email) {
                $app->redirect(
                    Redshop\IO\Route::_('index.php?option=com_redshop&view=login&Itemid=' . $app->input->getInt('Itemid'), false)
                );
            }
        } else {
            if ($encr) {
                // Preform security checks
                $authorization = $model->checkauthorization($orderId, $encr, false);

                if (empty($authorization)) {
                    throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
                }
            } elseif ((int)$orderDetail->user_id > 0) {
                $app->redirect(
                    Redshop\IO\Route::_('index.php?option=com_redshop&view=login&Itemid=' . $app->input->getInt('Itemid'), false)
                );
            } elseif ((int)$auth['users_info_id'] !== (int)$orderDetail->user_info_id && $orderPayment[0]->payment_method_class !== 'rs_payment_paypal') {
                throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
            }
        }

        JPluginHelper::importPlugin('system');
        RedshopHelperUtility::getDispatcher()->trigger('onDisplayOrderReceipt', array(&$orderDetail));

        $this->OrdersDetail = $orderDetail;
        $this->user         = $user;
        $this->params       = /** @scrutinizer ignore-call */
            $app->getParams('com_redshop');

        parent::display($tpl);
    }

    /**
     * Replace Reorder Button
     *
     * @param   string &$template  Template Data
     *
     * @return  void
     */
    public function replaceReorderButton(&$template)
    {
        $app     = JFactory::getApplication();
        $orderId = $app->input->getInt('oid', 0);
        $print   = $app->input->getInt('print', 0);

        $orderEntity        = RedshopEntityOrder::getInstance($orderId);
        $order              = $orderEntity->getItem();
        $paymentMethodClass = $orderEntity->getPayment()->getItem()->payment_method_class;

        // Tweak by Ronni  - Change IF function
        if ($order->order_status !== 'RD1' && $order->order_status !== 'S' && $order->order_status !== 'X' 
                && $order->order_status !== 'PR' && $print !== 1 && $order->order_payment_status !== 'Paid' 
                && $paymentMethodClass == 'bambora') {
    //  if ($order->order_status != 'C' && $order->order_status != 'S' && $order->order_status != 'PR' && $order->order_status != 'APP' && $print != 1 && $order->order_payment_status != 'Paid' && $paymentMethodClass != 'rs_payment_banktransfer') {
            // Tweak by Ronni - Pay button Epay
            $reorder = "<div id='system-message'>
                            <div class='alert alert-error'>
                                <a class='close' data-dismiss='alert'>
                                    ×
                                </a>
                                <h4 class='alert-heading'><?php echo JText::_('PLG_REDSHOP_PAYMENT_BAMBORA_PAYMENT_FAILED');?></h4>
                                <div>
                                    <div class='alert-error'>
                                        <?php echo JText::_('PLG_REDSHOP_PAYMENT_BAMBORA_ORDER_NOT_PLACED');?>
                                    </div>
                                    <br>
                                    <a class='btn btn-primary login-button' href='/index.php?option=com_redshop&view=order_detail&layout=checkout_final&oid=" . $orderId . "&Itemid=176&encr=" . $order->encr_key . "'>
                                        <?php echo JText::_('COM_REDSHOP_PAY');?>
                                    </a>
                                </div>
                            </div>
                        </div>";
            /*
            $reorder = "<form method='post'>
            <input type='hidden' name='order_id' value='" . $orderId . "'>
            <input type='hidden' name='option' value='com_redshop'>
            <input type='hidden' name='view' value='order_detail'>
            <input type='hidden' name='task' value='payment'>
            <input type='submit' name='payment' value='" . JText::_("COM_REDSHOP_PAY") . "'>
            </form>";
            */
        } elseif ($order->order_status == 'RD' || $order->order_status == 'RD1' || $order->order_status == 'RD2' || $order->order_status == 'S') {
            /*
            JFactory::getDocument()->addScriptDeclaration(
                '
                function submitReorder() {
                    if (!confirm("' . JText::_('COM_REDSHOP_CONFIRM_CART_EMPTY') . '")) {
                        return false;
                    }
                    return true;
                }
            '
            );
            $reorder = "<form method='post' name='frmreorder' id='frmreorder'>";
            $reorder .= "<input type='submit' name='reorder' id='reorder' value='" . JText::_(
                    'COM_REDSHOP_REORDER'
                ) . "' onclick='return submitReorder();' />";
            $reorder .= "<input type='hidden' name='order_id' value='" . $orderId . "'>";
            $reorder .= "<input type='hidden' name='option' value='com_redshop'>";
            $reorder .= "<input type='hidden' name='view' value='order_detail'>";
            $reorder .= "<input type='hidden' name='task' value='reorder'></form>";
            */
            $reorder = "<button class='btn-primary btn' style='width:100%'>
                            <?php echo JText::_('COM_REDSHOP_REORDER_MSG');?>
                        </button>
                        <br>";
        } elseif ($order->order_status == 'C' && $order->order_payment_status == 'Paid') {
            $reorder = "<button class='btn-primary btn' style='width:100%;margin-top:15px;margin-bottom:15px'>
                            <i class='fa fa-check-circle' aria-hidden='true'></i> 
                            <?php echo JText::_('COM_REDSHOP_PAYMENT_STA_PAID');?>
                        </button>
                        <br>";
        } elseif ($order->order_status == 'X') {
            $reorder = "<button class='btn btn-danger' style='width:100%'>
                            <i class='fa fa-times-circle' aria-hidden='true'></i> 
                            <?php echo JText::_('COM_REDSHOP_EPAY_ORDER_CANCELLED');?>
                        </button>
                        <br>";
        } else {
            $reorder = "<br>";
        }

        $template = str_replace("{reorder_button}", $reorder, $template);
        
        // Tweak by Ronni - Add Invoice button
        $invoice = "";
        if ($order->order_status == 'S' || $order->order_status == 'RD1') {
            $invoice = "<a href='/components/com_redshop/assets/orders/rsBillyInvoice_{order_number}.pdf' 
                                class='btn-primary btn' style='width:47%;margin-top:15px' target='_blank'>
                            <?php echo JText::_('COM_REDSHOP_DOWNLOAD_INVOICE');?>
                        </a>
                        <br><br>";
        }
        
        $template = str_replace("{invoice_button}", $invoice, $template);

        // Tweak by Ronni - Add Mobilepay modal
        $mobilepayModal = "";
        if ($order->order_payment_status == 'Unpaid' && $paymentMethodClass == 'rs_payment_banktransfer2') {
            $mobilepayModal = '<div class="price_box price_box_orange" style="margin-top:10px;margin-bottom:10px;margin-left:-20px!important;margin-right:-20px!important;font-weight: 400">
                                    <div style="text-align: center">
                                        <?php echo JText::_("COM_REDSHOP_MOBILEPAY_MODAL");?>
                                        <b>
                                            ' . RedshopHelperProductPrice::formattedPrice($order->order_total) . '
                                        </b>
                                    </div>
                                </div>
                                <span class="" id="Modal-mobilepay-alert_1" data-toggle="modal" 
                                        data-target="#Modal-mobilepay-alert">
                                </span>
                                <div id="Modal-mobilepay-alert" class="modal fade" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" 
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                Mobilepay for Ordre #' . $orderId . '
                                            </div>
                                            <div class="modal-body">
                                                <?php echo JText::_("COM_REDSHOP_MOBILEPAY_MODAL");?>
                                                <b>
                                                    ' . RedshopHelperProductPrice::formattedPrice($order->order_total) . '
                                                </b>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn" data-dismiss="modal" aria-hidden="true">
                                                    <?php echo JText::_("COM_REDSHOP_CLOSE");?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <style type="text/css">
                                    .modal-header{background-color:#e77500!important;color:#ffffff!important}
                                </style>';
        }
        
        $template = str_replace("{mobilepayModal}", $mobilepayModal, $template);
    }
}
// Tweak by Ronni - Mobilepay modal JS trigger */ ?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $("#Modal-mobilepay-alert_1").trigger("click");
    });
</script>