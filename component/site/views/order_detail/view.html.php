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
                    JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . $app->input->getInt('Itemid'), false)
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
                    JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . $app->input->getInt('Itemid'), false)
                );
            } elseif ((int)$auth['users_info_id'] !== (int)$orderDetail->user_info_id) {
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
        $order   = RedshopEntityOrder::getInstance($orderId)->getItem();

        if ($order->order_status != 'C' && $order->order_status != 'S' && $order->order_status != 'PR' && $order->order_status != 'APP' && $print != 1 && $order->order_payment_status != 'Paid') {
            $reorder = "<form method='post'>
			<input type='hidden' name='order_id' value='" . $orderId . "'>
			<input type='hidden' name='option' value='com_redshop'>
			<input type='hidden' name='view' value='order_detail'>
			<input type='hidden' name='task' value='payment'>
			<input type='submit' name='payment' value='" . JText::_("COM_REDSHOP_PAY") . "'>
			</form>";
        } else {
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
        }

        $template = str_replace("{reorder_button}", $reorder, $template);
    }
}
