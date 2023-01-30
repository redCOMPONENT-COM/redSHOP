<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller.OrderDetail
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Billy\RedshopBilly;

/**
 * Redshop Order Detail Controller
 *
 * @package     Redshop.Backend
 * @subpackage  Controller.OrderDetail
 * @since       1.0
 */
class RedshopControllerOrder_detail extends RedshopController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'order_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);
        parent::display();
    }

    public function save()
    {
        $post = $this->input->post->getArray();

        $text_field         = $this->input->post->get('text_field', '', 'raw');
        $post["text_field"] = $text_field;

        $cid = $this->input->post->get('cid', array(0), 'array');

        $post ['order_id'] = $cid [0];

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        if ($model->store($post)) {
            $msg = JText::_('COM_REDSHOP_ORDER_DETAIL_SAVED');
        } else {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_ORDER_DETAIL');
        }

        $this->setRedirect('index.php?option=com_redshop&view=order', $msg);
    }

    public function remove()
    {
        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1) {
            throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        if (!$model->delete($cid)) {
            echo "<script> alert('" . /** @scrutinizer ignore-deprecated */ $model->getError(
                ) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_ORDER_DETAIL_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=order', $msg);
    }

    public function cancel()
    {
        $msg = JText::_('COM_REDSHOP_ORDER_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=com_redshop&view=order', $msg);
    }

    public function neworderitem()
    {
        $post = $this->input->post->getArray();
        $tmpl = "";

        if (isset($post['tmpl'])) {
            $tmpl = $post['tmpl'];
        }

        $cid = $this->input->post->get('cid', array(0), 'array');

        $order_item_id = $this->input->post->get('order_item_id', 0);

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        $orderItem          = Redshop\Order\Helper::redesignProductItem($post);
        $post['order_item'] = $orderItem;

        $productId     = $orderItem[0]->product_id;
        $finalquantity = $quantity = $orderItem[0]->quantity;

        // Check product Quantity
        if (Redshop::getConfig()->get('USE_STOCKROOM') == 1) {
            $currentStock = RedshopHelperStockroom::getStockroomTotalAmount($productId);

            if ($currentStock >= $quantity) {
                $finalquantity = (int)$quantity;
            } else {
                $finalquantity = (int)$currentStock;
            }
        }

        if ($finalquantity > 0) {
            if ($model->neworderitem($post, $finalquantity, $order_item_id)) {
                if ($order_item_id == 0) {
                    $msg = JText::_('COM_REDSHOP_ORDER_ITEM_ADDED');
                } else {
                    $msg = JText::_('COM_REDSHOP_QUANTITY_UPDATED');
                }
            } else {
                if ($order_item_id == 0) {
                    $msg = JText::_('COM_REDSHOP_ERROR_ADDING_ORDER_ITEM');
                } else {
                    $msg = JText::_('COM_REDSHOP_ERROR_UPDATING_QUANTITY');
                }
            }
        } else {
            $msg = JText::_('COM_REDSHOP_PRODUCT_OUT_OF_STOCK');
        }

        if ($tmpl) {
            $this->setRedirect(
                'index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0] . '&tmpl=' . $tmpl,
                $msg
            );
        } else {
            $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0], $msg);
        }
    }

    public function delete_item()
    {
        $post = $this->input->post->getArray();
        $cid  = $this->input->post->get('cid', array(0), 'array');

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        $orderItem = RedshopHelperOrder::getOrderItemDetail($cid[0]);

        // Delete order if there are only 1 order item
        if (count($orderItem) == 1 && $orderItem[0]->order_item_id == $post['order_item_id']) {
            $model->delete($cid);
            $msg = JText::_('COM_REDSHOP_ORDER_DELETED_SUCCESSFULLY');

            $this->setRedirect('index.php?option=com_redshop&view=order', $msg);

            return;
        }

        // Delete order item.
        if ($model->delete_item($post)) {
            $msg = JText::_('COM_REDSHOP_ORDER_ITEM_DELETED');
        } else {
            $msg = JText::_('COM_REDSHOP_ERROR_DELETING_ORDER_ITEM');
        }

        $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0], $msg);
    }

    public function updateItem()
    {
        $post = $this->input->post->getArray();
        $cid  = $this->input->post->get('cid', array(0), 'array');
        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        if ($model->updateItem($post)) {
            $msg = JText::_('COM_REDSHOP_ORDER_ITEM_PRICE_UPDATED');
        } else {
            $msg = JText::_('COM_REDSHOP_ERROR_UPDATING_PRICE');
        }

        $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0], $msg);
    }

    public function update_discount()
    {
        $type = '';
        $post = $this->input->post->getArray();

        $cid = $this->input->post->get('cid', array(0), 'array');

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        if ($model->update_discount($post)) {
            $msg = JText::_('COM_REDSHOP_DISCOUNT_UPDATED');
        } else {
            $msg = JText::_('COM_REDSHOP_ERROR_UPDATING_DISCOUNT');
            $type = 'error';
        }

        $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0], $msg, $type);
    }

    public function special_discount()
    {
        $type = '';
        $post = $this->input->post->getArray();
        $cid  = $this->input->post->get('cid', array(0), 'array');

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        if ($model->special_discount($post)) {
            $msg = JText::_('COM_REDSHOP_SPECIAL_DISCOUNT_APPLIED');
        } else {
            $msg = JText::_('COM_REDSHOP_ERROR_IN_SPECIAL_DISCOUNT');
            $type = 'error';
        }

        $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0], $msg, $type);
    }

    public function update_shippingrates()
    {
        $post = $this->input->post->getArray();
        $cid  = $this->input->post->get('cid', array(0), 'array');

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        if ($model->update_shippingrates($post)) {
            $msg = JText::_('COM_REDSHOP_SHIPPING_RATE_UPDATED');
        } else {
            $msg = JText::_('COM_REDSHOP_ERROR_UPDATING_SHIPPING_RATE');
        }

        $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0], $msg);
    }

    public function update_paymentmethod() {
        $post = $this->input->post->getArray();
        $cid  = $this->input->post->get('cid', array(0), 'array');

        if (\Redshop\Order\Helper::updateOrderPaymentMethod($post)) {
            $orderEntity = RedshopEntityOrder::getInstance($cid[0]);
            $orderData   = $orderEntity->getItem();

            if (JPluginHelper::isEnabled('billy') && $orderData->is_billy_booked == 0) {
                RedshopBilly::renewInvoiceInBilly($orderData);
            } else if (JPluginHelper::isEnabled('billy') && $orderData->is_billy_booked == 1) {
                $msg     = JText::_('COM_REDSHOP_BILLY_ORDER_IS_ALREADY_BOOKED_ERROR') . $cid[0];
                $msgType = 'error';
            }
            $msg     = JText::_('COM_REDSHOP_PAYMENT_METHOD_UPDATED');
            $msgType = 'message';
        } else {
            $msg     = JText::_('COM_REDSHOP_ERROR_UPDATING_PAYMENT_METHOD');
            $msgType = 'error';
        }

        $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0], $msg, $msgType);
    }

    public function updateShippingAdd()
    {
        $post             = $this->input->post->getArray();
        $suboption        = $this->input->getString('suboption', 'com_redshop');
        $view             = ($suboption == 'com_redshop') ? 'order_detail' : 'order';
        $cid              = $this->input->post->get('cid', array(0), 'array');
        $post['order_id'] = $cid[0];

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        if ($model->updateShippingAdd($post)) {
            $msg = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_UPDATED');
        } else {
            $msg = JText::_('COM_REDSHOP_ERROR_UPDATING_SHIPPING_INFORMATION');
        }

        ?>
        <script type="text/javascript">

            window.parent.document.location = "index.php?option=<?php echo $suboption;?>&view=<?php echo $view;?>&cid[]=<?php echo $cid[0];?>";

            window.close();
        </script>
        <?php
        JFactory::getApplication()->close();
    }

    public function updateBillingAdd()
    {
        $post             = $this->input->post->getArray();
        $cid              = $this->input->post->get('cid', array(0), 'array');
        $post['order_id'] = $cid[0];

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        if ($model->updateBillingAdd($post)) {
            $msg = JText::_('COM_REDSHOP_BILLING_INFORMATION_UPDATED');
        } else {
            $msg = JText::_('COM_REDSHOP_ERROR_UPDATING_BILLING_INFORMATION');
        }

        ?>
        <script type="text/javascript">

            window.parent.document.location = "index.php?option=com_redshop&view=order_detail&cid[]=<?php echo $cid[0];?>";

            window.close();
        </script>
        <?php
        JFactory::getApplication()->close();
    }

    /**
     * Method for create stock note pdf
     *
     * @return  void
     */
    public function createpdf()
    {
        if (!RedshopHelperPdf::isAvailablePdfPlugins()) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_ERROR_MISSING_PDF_PLUGIN'), 'error');
        } else {
            $this->getView('order_detail', 'pdf');
        }

        parent::display();
    }

    /**
     * Method for create stock note pdf
     *
     * @return  void
     */
    public function createpdfstocknote()
    {
        if (!RedshopHelperPdf::isAvailablePdfPlugins()) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_ERROR_MISSING_PDF_PLUGIN'), 'error');
        } else {
            $this->getView('order_detail', 'stocknotepdf');
        }

        parent::display();
    }

    public function send_downloadmail()
    {
        $cid  = $this->input->get->get('cid', array(0), 'array');
        $tmpl = $this->input->getCmd('tmpl', '');
        $msg  = JText::_('COM_REDSHOP_ERROR_DOWNLOAD_MAIL_FAIL');
        $type = 'error';

        if (RedshopHelperOrder::sendDownload($cid[0])) {
            $msg = JText::_('COM_REDSHOP_DOWNLOAD_MAIL_HAS_BEEN_SENT');
            $type = 'message';
        }

        if ($tmpl) {
            $this->setRedirect(
                'index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0] . '&tmpl=' . $tmpl,
                $msg
            );
        } else {
            $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0], $msg, $type);
        }
    }

    public function displayProductItemInfo()
    {
        $get = $this->input->get->getArray();

        $productId = $get['product'];
        $quantity  = $get['quantity'];
        $unique_id = $get['unique_id'];
        $user_id   = $get['user_id'];
        $newprice  = $get['newprice'];

        $response = RedshopHelperProduct::getProductItemInfo($productId, $quantity, $unique_id, $user_id, $newprice);
        echo $response;
        JFactory::getApplication()->close();
    }

    public function checkoutNext()
    {
        $app     = JFactory::getApplication();
        $session = JFactory::getSession();

        /** @var RedshopModelOrder_detail $model */
        $model = $this->getModel('order_detail');

        $request = $this->input->getArray();
        $order   = RedshopEntityOrder::getInstance($request['order_id'])->getItem();

        // Send the order_id and order payment_id to the payment plugin so it knows which DB record to update upon successful payment
        $userBilling       = RedshopHelperOrder::getOrderBillingUserInfo($request['order_id']);
        $shippingAddresses = RedshopHelperOrder::getOrderShippingUserInfo($request['order_id']);

        if (isset($shippingAddresses)) {
            $shippingAddress = $shippingAddresses;

            $shippingAddress->country_2_code = RedshopHelperWorld::getCountryCode2($shippingAddress->country_code);
            $shippingAddress->state_2_code   = RedshopHelperWorld::getStateCode2($shippingAddress->state_code);
        }

        if (isset($shippingAddresses)) {
            $d["shippingaddress"] = $shippingAddresses;

            $d["shippingaddress"]->country_2_code = RedshopHelperWorld::getCountryCode2(
                $d["shippingaddress"]->country_code
            );
            $d["shippingaddress"]->state_2_code   = RedshopHelperWorld::getStateCode2(
                $d ["shippingaddress"]->state_code
            );

            $shippingAddresses->country_2_code = RedshopHelperWorld::getCountryCode2(
                $d ["shippingaddress"]->country_code
            );
            $shippingAddresses->state_2_code   = RedshopHelperWorld::getStateCode2($d ["shippingaddress"]->state_code);
        }

        if (isset($userBilling)) {
            $d ["billingaddress"] = $userBilling;

            if (isset($userBilling->country_code)) {
                $d["billingaddress"]->country_2_code = RedshopHelperWorld::getCountryCode2($userBilling->country_code);

                $userBilling->country_2_code = RedshopHelperWorld::getCountryCode2($userBilling->country_code);
            }

            if (isset($userBilling->state_code)) {
                $d["billingaddress"]->state_2_code = RedshopHelperWorld::getStateCode2($userBilling->state_code);

                $userBilling->state_2_code = RedshopHelperWorld::getStateCode2($userBilling->state_code);
            }
        }

        $creditCardData = array();

        $creditCardData['order_payment_name']         = $request['order_payment_name'];
        $creditCardData['creditcard_code']            = $request['creditcard_code'];
        $creditCardData['order_payment_number']       = $request['order_payment_number'];
        $creditCardData['order_payment_expire_month'] = $request['order_payment_expire_month'];
        $creditCardData['order_payment_expire_year']  = $request['order_payment_expire_year'];
        $creditCardData['credit_card_code']           = $request['credit_card_code'];
        $creditCardData['selectedCardId']             = $this->input->getString('selectedCard', '');

        $session->set('ccdata', $creditCardData);

        $values['order_shipping'] = $order->order_shipping;
        $values['order_number']   = $request['order_id'];
        $values['order_tax']      = $order->order_tax;
        $values['shippinginfo']   = $d ["shippingaddress"];
        $values['billinginfo']    = $d ["billingaddress"];
        $values['order_total']    = $order->order_total;
        $values['order_subtotal'] = $order->order_subtotal;
        $values["order_id"]       = $request['order_id'];
        $values['payment_plugin'] = $request['payment_plugin'];
        $values['order']          = $order;

        JPluginHelper::importPlugin('redshop_payment');
        $dispatcher = RedshopHelperUtility::getDispatcher();

        $results         = $dispatcher->trigger(
            'onPrePayment_' . $values['payment_plugin'],
            array($values['payment_plugin'], $values)
        );
        $paymentResponse = $results[0];

        if ($paymentResponse->responsestatus == "Success" || $values['payment_plugin'] == "") {
            $paymentResponse->log                       = $paymentResponse->message;
            $paymentResponse->msg                       = $paymentResponse->message;
            $paymentResponse->order_status_code         = 'C';
            $paymentResponse->order_payment_status_code = 'Paid';
            $paymentResponse->order_id                  = $request['order_id'];

            RedshopHelperOrder::changeOrderStatus($paymentResponse);
        }

        // Update order payment table with  credit card details
        $model->update_ccdata($request['order_id'], $paymentResponse->transaction_id);

        $app->redirect(
            Redshop\IO\Route::_(
                JURI::base() . "index.php?option=com_redshop&view=order_detail&task=edit&cid[]=" . $request['order_id']
            ),
            $paymentResponse->message
        );
    }

    public function send_invoicemail()
    {
        $cid         = $this->input->get->get('cid', array(0), 'array');
        $tmpl        = $this->input->getCmd('tmpl', '');
        $orderEntity = RedshopEntityOrder::getInstance($cid[0]);
        $orderData   = $orderEntity->getItem();

        if (JPluginHelper::isEnabled('billy') && $orderData->is_billy_booked == 1) {
            $resendInvoice = RedshopBilly::ReSendInvoice($cid[0]);

            if ($resendInvoice) {
                $msg     = JText::_('COM_REDSHOP_BILLY_INVOICE_MAIL_HAS_BEEN_RE_SENT') . $cid[0];
                $msgType = 'message';
            } else {
                $msg     = JText::_('COM_REDSHOP_ERROR_INVOICE_MAIL_FAIL');
                $msgType = 'error';
            }
        } else if (JPluginHelper::isEnabled('billy') && $orderData->is_billy_booked == 0) {
            $msg     = JText::_('COM_REDSHOP_BILLY_ORDER_IS_NOT_BOOKED_ERROR') . $cid[0];
            $msgType = 'error';
        } else if (Redshop\Mail\Invoice::sendMail($cid[0])) {
            $msg     = JText::_('COM_REDSHOP_INVOICE_MAIL_HAS_BEEN_SENT');
            $msgType = 'message';
        } else {
            $msg     = JText::_('COM_REDSHOP_ERROR_INVOICE_MAIL_FAIL');
            $msgType = 'error';
        }

        if ($tmpl) {
            $this->setRedirect(
                'index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0] . '&tmpl=' . $tmpl,
                $msg, $msgType
            );
        } else {
            $this->setRedirect(
                'index.php?option=com_redshop&view=order_detail&cid[]=' . $cid[0], $msg, $msgType);
        }
    }

    /**
     * Resend Order Mail on Demand
     *
     * @return  void
     * @throws  Exception
     */
    public function resendOrderMail()
    {
        $orderId = $this->input->getInt('orderid');
        $tmpl    = $this->input->getCmd('tmpl', '');

        if (Redshop\Mail\Order::sendMail($orderId)) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_SEND_ORDER_MAIL'));
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_ERROR_SENDING_ORDER_MAIL'), 'error');
        }

        if ($tmpl) {
            $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $orderId . '&tmpl=' . $tmpl);
        } else {
            $this->setRedirect('index.php?option=com_redshop&view=order_detail&cid[]=' . $orderId);
        }
    }

    /**
     * Pay order from backend. Responsibility of Changing Order Status will be on payment method it self.
     * Using order_functions::getInstance()->changeorderstatus($return);
     * To give more flexibility to payment method.
     *
     * @return  void
     */
    public function pay()
    {
        $orderId = $this->input->getInt('orderId');

        JPluginHelper::importPlugin('redshop_payment');
        RedshopHelperUtility::getDispatcher()->trigger('onBackendPayment', array($orderId));

        $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $orderId);
    }

    /**
     * Store Extra field
     *
     * @return  void
     * @throws  Exception
     */
    public function storeExtraField()
    {
        $data = $this->input->post->getArray();

        RedshopHelperExtrafields::extraFieldSave($data, RedshopHelperExtrafields::SECTION_ORDER, $data['order_id']);

        $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $data['order_id']);
    }

    // Tweak by Ronni START - Re-order feature function and add Product "Genbestilling" to re-order in admin and make it work same as front
    /**
     * Re-order in backend
     *
     * @return  void
     * @throws  Exception
     */	
    public function reorder() 
    {
        $orderId = $this->input->get('cid');
        
        if ($orderId[0] <= 0 || $orderId[0] == '') {
            return;
        }

        //Get order information direct from database
        $db         = JFactory::getDbo();
        $orderQuery = "SELECT * FROM #__redshop_orders WHERE order_id = " . $orderId[0];
        $db->setQuery($orderQuery);
        $order      = $db->loadObject();
        
        //Generate order number
        $orderNumber = RedshopHelperOrder::generateOrderNumber();
        
        //Set reorder parameters
        $order->order_id 				= '';
        $order->order_number 			= $orderNumber;
        $order->invoice_number_chrono 	= '';
        $order->invoice_number 			= '';
        $order->order_status 			= 'C';
        $order->order_payment_status 	= 'Unpaid';
        $order->cdate 					= time();
        $order->mdate 					= time();
        $order->customer_note		    = 'Genbestilling af ordre ' . $orderId[0];
        $order->ip_address 				= $_SERVER['REMOTE_ADDR'];
        $order->encr_key 				= \Redshop\Crypto\Helper\Encrypt::generateCustomRandomEncryptKey();
        $order->is_booked 				= 0;
        $order->bookinvoice_number 		= 0;
        $order->bookinvoice_date 		= 0;
        $order->refferal_info 			= '';
        $order->billy_invoice_no 		= '';
        $order->is_billy_booked 		= 0;
        $order->is_billy_cashbook 		= 0;
        $order->billy_bookinvoice_date 	= '';
        $order->overdue_limit 			= 0;
        $order->overdue_days 			= 0;
        $order->order_label_create		= 0;
        
        // Insert the order object into the #__redshop_orders table.
        $reorder = $db->insertObject('#__redshop_orders', $order);
        
        if(!$reorder) {
            JFactory::getApplication()->enqueueMessage(JText::_('Order has not been created properly.'), 'error');
            $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $orderId[0]);
        }
        
        $newOrderId = $db->insertid();
        
        //Get order items
        $itemQuery = "SELECT * FROM #__redshop_order_item WHERE order_id = " . $orderId[0];
        $db->setQuery($itemQuery);
        $orderItems = $db->loadObjectList();
        
        //Set reorder item parameters
        foreach ($orderItems as $num => $item) {
            $orderItemId         = $item->order_item_id;
            $item->order_item_id = '';
            $item->order_id      = $newOrderId;
            $item->order_status = 'C';
            $item->cdate        = time();
            $item->mdate        = time();

            if ($item->order_item_sku == 'genbestilling') {
                continue;
            }

            // Insert the item object into the #redshop_order_item table.
            $insertedItem = $db->insertObject('#__redshop_order_item', $item);
            
            if (!$insertedItem) {
                JFactory::getApplication()->enqueueMessage(JText::_('Order Item has not been created properly.'), 'error');
                $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $orderId[0]);
            }
            
            $newItemId = $db->insertid();
            
            //get item attributes
            $itemAttributeQuery = "SELECT * FROM #__redshop_order_attribute_item WHERE order_item_id = " . $orderItemId;
            $db->setQuery($itemAttributeQuery);
            $itemAttributes     = $db->loadObjectList();
            
            if (count($itemAttributes) > 0) {
                //set item attribute parameters
                foreach ($itemAttributes as $k => $itemAttribute) {
                    $itemAttribute->order_att_item_id = '';
                    $itemAttribute->order_item_id     = $newItemId;
                    // Insert item attribute into the #__redshop_order_attribute_item table.
                    $insertedItemAttr = $db->insertObject('#__redshop_order_attribute_item', $itemAttribute);
                    
                    if (!$insertedItemAttr) {
                        JFactory::getApplication()->enqueueMessage(JText::_('Order Item Attribute has not been created properly.'), 'error');
                        $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $orderId[0]);
                    }
                }
            }
            //get order users fields data
            $fieldDataQuery = "SELECT * FROM #__redshop_fields_data WHERE itemid = " . $orderItemId;
            $db->setQuery($fieldDataQuery);
            $fieldsData     = $db->loadObjectList();
            if (count($fieldsData) > 0) {
                //set users field data parameters
                foreach ($fieldsData as $i => $fieldData) {
                    $fieldData->data_id = '';
                    $fieldData->itemid  = $newItemId;
                    // Insert users field data into the #__redshop_fields_data table.
                    $insertedFieldData  = $db->insertObject('#__redshop_fields_data', $fieldData);
                    if (!$insertedFieldData) {
                        JFactory::getApplication()->enqueueMessage(JText::_('Order Item User Field has not been created properly.'), 'error');
                        $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $orderId[0]);
                    }
                }
            }
        }
        
        // Add reorder product to know the order is repeated
        $reorderItem                         = new stdClass;
        $reorderItem->order_item_id          = '';
        $reorderItem->order_item_name        = 'Genbestilling - ' . $orderId[0];
        $reorderItem->order_id               = $newOrderId;
        $reorderItem->user_info_id           = $orderItems[0]->user_info_id;
        $reorderItem->supplier_id            = 0;
        $reorderItem->product_attribute      = '';
        $reorderItem->product_id             = 3627;
        $reorderItem->order_item_sku         = 'genbestilling';
        $reorderItem->product_quantity       = 1;
        $reorderItem->product_item_price     = 0;
        $reorderItem->product_final_price    = 0;
        $reorderItem->order_item_currency    = $orderItems[0]->order_item_currency;
        $reorderItem->order_status           = 'C';
        $reorderItem->product_item_old_price = 0;
        $reorderItem->mdate                  = time();
        $reorderItem->cdate                  = time();
        
        // Insert the payment object into the #__redshop_order_payment table.
        $reorderProduct = $db->insertObject('#__redshop_order_item', $reorderItem);
        if (!$reorderProduct) {
            JFactory::getApplication()->enqueueMessage(JText::_('Reorder Product has not been created properly.'), 'error');
            $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $orderId[0]);
        }

        //Get order payment terms
        $paymentQuery = "SELECT * FROM #__redshop_order_payment WHERE order_id = " . $orderId[0];
        $db->setQuery($paymentQuery);
        $paymentTerms = $db->loadObject();
        
        //Set reorder payment terms
        $paymentTerms->payment_order_id       = '';
        $paymentTerms->order_id               = $newOrderId;
        $paymentTerms->authorize_status       = '';
        $paymentTerms->order_payment_trans_id = '';
        $paymentTerms->order_payment_number   = '';
        
        // Insert the payment object into the #__redshop_order_payment table.
        $payment = $db->insertObject('#__redshop_order_payment', $paymentTerms);
        if (!$payment) {
            JFactory::getApplication()->enqueueMessage(JText::_('Payment Line has not been created properly.'), 'error');
            $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $orderId[0]);
        }

        //Set order status log
        $orderStatusLog                       = new stdClass;
        $orderStatusLog->order_status         = 'C';
        $orderStatusLog->order_id             = $newOrderId;
        $orderStatusLog->order_payment_status = 'Unpaid';
        $orderStatusLog->date_changed         = time();
        
        // Insert the order status object into the #__redshop_order_status_log table.
        $orderStatus = $db->insertObject('#__redshop_order_status_log', $orderStatusLog);
        if (!$orderStatus) {
            JFactory::getApplication()->enqueueMessage(JText::_('Order Status has not been created properly.'), 'error');
            $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $orderId[0]);
        }

        //Get order users info
        $usersInfoQuery = "SELECT * FROM #__redshop_order_users_info WHERE order_id = " . $orderId[0];
        $db->setQuery($usersInfoQuery);
        $orderUsersInfo = $db->loadObjectList();
        
        //Set reorder users info parameters
        foreach ($orderUsersInfo as $num => $user) {
            $user->order_info_id = '';
            $user->order_id      = $newOrderId;
            // Insert the users info object into the #__redshop_order_users_info table.
            $userInfo = $db->insertObject('#__redshop_order_users_info', $user);
            if (!$userInfo) {
                JFactory::getApplication()->enqueueMessage(JText::_('Order Users Info has not been created properly.'), 'error');
                $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $orderId[0]);
            }
        }


        $orderdetail   = RedshopHelperOrder::getOrderDetail($newOrderId);
        $invoiceHandle = RedshopBilly::renewInvoiceInBilly($orderdetail);
    
        JFactory::getApplication()->enqueueMessage("<b>Ordre #" . $orderId[0] . " er genbestilt. Nyt ordre nummer #" . $newOrderId . "</b>", 'message');
        $this->setRedirect('index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $newOrderId);
    }
    // Tweak by Ronni END - reorder feature function and add Product "Genbestilling" to re-order in admin and make it work same as front
}
