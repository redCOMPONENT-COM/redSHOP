<?php

/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2022 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Log\Log;

defined('_JEXEC') or die;

class PlgRedshop_PaymentGpay_domestic extends \RedshopPayment
{
    /**
     * This method will be triggered on before placing order to authorize or charge credit card
     *
     * @param   string  $element  Name of the payment plugin
     * @param   array   $data     Cart Information
     *
     * @return  void  Authorize or Charge success or failed message and transaction id
     * @throws  Exception
     * @since   1.0
     */
    public function onPrePayment($element, $data)
    {
        if ($element != 'gpay_domestic') {
            return;
        }

        $amount    = $data['carttotal'];

        if ($data['shipping'] > 0 && $data['carttotal'] <= 0) {
            $amount    = $data['shipping'];
        }

        $this->getToken();

        $jsonResult = $this->createTransaction($amount, $data['order_id']);

        JFactory::getApplication()->redirect($jsonResult['response']['order_url']);
    }

    protected function preparePaymentInput($orderInfo)
    {
    }

    private function getToken()
    {
        $merchantCode = $this->params->get('merchantCode');
        $password     = $this->params->get('password');

        $data = array(
            'merchant_code' => $merchantCode,
            'password'      => $password
        );

        $response = $this->execPostRequest("/authentication/token", json_encode($data));
        $result = json_decode($response);
        $this->accessToken = $result->response->token;
    }

    private function createTransaction($orderTotal, $orderId)
    {
        $user = JFactory::getUser();
        $merchantCode = $this->params->get('merchantCode');
        $amount       = number_format($orderTotal, 0, "", "");
        $orderTime    = time();
        $enbed = "";

        $rawHash     = "merchant_code=" . $merchantCode . "&order_id=" . $orderId . "&order_amt=" . $amount . "&embed_data=Customer Data&order_currency=VND&language=vi";
        $signature   = $this->getSignature($rawHash);
        $callbackUrl = $this->getNotifyUrl($orderId);
        $webhookUrl = $this->getWebhookUrl($orderId);

        $description  = "Order: " . $orderId;
        $customerId   = $this->params->get('customerId');

        $tokenId = RedshopEntityField_Data::getInstance()->loadItemByArray(
            array(
                'fieldid' => RedshopHelperExtrafields::getField('rs_token_gpay')->id,
                'itemid'  => $user->id,
                'section' => RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS
            )
        )->getItem()->data_txt;

        $data      = array(
            'merchant_code'     => $merchantCode,
            'order_id'          => $orderId,
            'order_amt'         => (int) $amount,
            'order_currency'    => 'VND',
            'description'       => $description,
            'order_time'        => $orderTime,
            'customer_id'       => $customerId,
            'callback_url'      => $callbackUrl,
            'webhook_url'       => $webhookUrl,
            'token_id'          => $tokenId,
            'language'          => 'vi',
            'service_code'      => 'PAYMENTGATEWAY',
            'payment_method'    => 'BANK_ATM',
            'payment_type'      => 'IMMEDIATE',
            'signature'         => $signature,
            'embed_data'        => 'Customer Data'
        );

        //Create order in API Gpay
        $result = $this->execPostRequest("/order/init", json_encode($data));

        $response = json_decode($result, true);

        if ($response['meta']['code'] != 200 || $response == NULL)
        {
            $app = JFactory::getApplication();
            $Itemid = RedshopHelperRouter::getCheckoutItemId();
            $language = $app->input->get('lang');

            switch ($language) {
                case 'vi-VN': $lang = 'vi';break;
                case 'en-GB': $lang = 'en';break;
                default: $lang = 'vi';
            }

            if ($lang = 'vi')
            {
                $messageError = 'Lỗi thanh toán';
            }
            else
            {
                $messageError = 'Error with payment';
            }

            $msg = $response['return_message'] ?? $messageError;
            $msgType = 'error';

            // Write order log
            \RedshopHelperOrder::writeOrderLog($orderId, 0, 'P', 'Unpaid', $msg);

            $app->redirect(JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid . '&lang=' .$lang, false), $msg, $msgType);
        }

        return $response;  // decode jsonson
    }

    private function execPostRequest($url, $data, $method = "POST")
    {
        $endpoint = $this->params->get('production');

        if ($this->params->get('isTest')) {
            $endpoint = $this->params->get('sandbox');
        }

        $ch = curl_init($endpoint . $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method === "POST") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Signature ' . $this->getSignature($data),
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->accessToken
            )
        );

        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);

        //Write log
        $this->writeLog(
            "Request: ".json_encode($data,JSON_UNESCAPED_UNICODE),
            'payment_api'
        );
        $this->writeLog(
            "Response: ".json_encode($result,JSON_UNESCAPED_UNICODE),
            'payment_api'
        );

        return $result;
    }


    /**
     * Notify payment
     *
     * @param   string  $element  Name of plugin
     * @param   array   $request  HTTP request data
     *
     * @return  object  Contains the information of order success of falier in object
     * @since   1.0
     */
    public function onNotifyPaymentGpay_domestic($element, $request)
    {
        if ($element != 'gpay_domestic') {
            return false;
        }

        $app = JFactory::getApplication();
        $user = JFactory::getUser();

        $merchantCode = $this->params->get('merchantCode');
        $gpayTransId  = $request['gpay_trans_id'];
        $orderId      = $request['order_id'];
        $token        = $request['token_info'];

        $this->getToken();

        $rawHash     = "merchant_code=" . $merchantCode . "&gpay_trans_id=" . $gpayTransId;
        $signature   = $this->getSignature($rawHash);

        $data = array(
            'merchant_code' => $merchantCode,
            'gpay_trans_id' => $gpayTransId,
            'signature'     => $signature
        );

        $response = $this->execPostRequest("/order/detail", json_encode($data));

        $tokerUserExits = RedshopEntityField_Data::getInstance()->loadItemByArray(
            array(
                'fieldid' => RedshopHelperExtrafields::getField('rs_token_gpay')->id,
                'itemid'  => $user->id,
                'section' => RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS
            )
        )->getItem()->data_txt;

        if (!$tokerUserExits)
        {
            $this->saveTokenUser($user, $token);
        }

        $result = json_decode($response, true);
        $responseOrderIds = $result['response']['order_id'];

        //Write Log in custom field
        $this->saveDataJson($responseOrderIds , json_encode(json_encode($result), JSON_UNESCAPED_UNICODE));

        if ($result['response']['order_status'] == "ORDER_SUCCESS")
        {
            $values = $this->setStatus(
                $responseOrderIds,
                $result['response']['gpay_trans_id'],
                $this->params->get('verify_status', ''),
                'Paid',
                JText::_('PLG_REDSHOP_PAYMENT_GPAY_DOMESTIC_PAYMENT_SUCCESS'),
                JText::_('PLG_REDSHOP_PAYMENT_GPAY_DOMESTIC_PAYMENT_SUCCESS')
            );

            if (isset($request['ipn']) && $request['ipn'])
            {
                $values->log = $values->log . ' IPN';
                //Change order status
                RedshopHelperOrder::changeOrderStatus($values);
            }

            return $values;
        }


        if (isset($request['ipn']) && $request['ipn'])
        {
            $values = $this->setStatus(
                $responseOrderIds,
                $result['response']['gpay_trans_id'],
                $this->params->get('verify_status', ''),
                'Paid',
                JText::_('PLG_REDSHOP_PAYMENT_GPAY_DOMESTIC_PAYMENT_SUCCESS'),
                JText::_('PLG_REDSHOP_PAYMENT_GPAY_DOMESTIC_PAYMENT_SUCCESS')
            );

            $values->log = $values->log . ' IPN';

            //Change order status
            RedshopHelperOrder::changeOrderStatus($values);
        }
        // Redirect "checkout" page when order unpaid.
        $app = JFactory::getApplication();
        $itemId = $app->input->get('Itemid');

        $app->redirect(JRoute::_(
            JUri::base() . "index.php?option=com_redshop&Itemid=". $itemId."&view=checkout",
            false
        ));
    }

    public function saveDataJson($orderId = '', $dataJson = '')
    {
        try
        {
            $db = JFactory::getDbo();
            $customField                         = RedshopEntityField::getInstanceByField('name',
                'rs_gpay_order_ref');

            if (!$customField || !$dataJson || !$orderId)
            {
                return;
            }

            $columns                            = array('fieldid', 'data_txt', 'itemid', 'section');

            $values                             = array(
                $db->q((int) $customField->get('id')),
                $db->q($dataJson),
                $db->q((int) $orderId),
                $db->q((int) RedshopHelperExtrafields::SECTION_ORDER)
            );

            $query = $db->getQuery(true)
                ->insert($db->qn('#__redshop_fields_data'))
                ->columns($db->qn($columns))
                ->values(implode(',', $values));

            $db->setQuery($query)->execute();
        }
        catch (Exception $e)
        {
            return;
        }
    }

    public function saveTokenUser($user, $token)
    {
        $db = JFactory::getDbo(true);

        $values = array(
            RedshopHelperExtrafields::getField('rs_token_gpay')->id,
            $token,
            $user->id,
            (int) RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS
        );

        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__redshop_fields_data'))
            ->columns($db->quoteName(array('fieldid', 'data_txt', 'itemid', 'section')))
            ->values(implode(',', $db->q($values)));

        $db->setQuery($query)->execute();
    }

    /**
     *
     * Get webhook information
     *
     * @since YI-1066
     * @return void
     */
    public function getInformationByWebhook($data, $payload) {

        if($data['payment_method'] != 'gpay_domestic') return;

        $status = $payload['status'];
        $gpayTransId = $payload['gpay_trans_id'];
        $gOrderId = $payload['order_id'];

        if ($status === "ORDER_SUCCESS")
        {
            //Write Log in custom field
            $this->saveDataJson($gOrderId , json_encode($payload, JSON_UNESCAPED_UNICODE));

            if ($status === "ORDER_SUCCESS")
            {
                $values = $this->setStatus(
                    $gOrderId,
                    $gpayTransId,
                    $this->params->get('verify_status', ''),
                    'Paid',
                    JText::_('PLG_REDSHOP_PAYMENT_GPAY_DOMESTIC_PAYMENT_SUCCESS'),
                    'Success'
                );

                //Change order status
                RedshopHelperOrder::changeOrderStatus($values);

                echo json_encode( array(
                    'code' => 200,
                    'message' => "Order $gOrderId payment successfully!"
                ) );
            }
        }

        exit;
    }

    /**
     *
     * Reprocess the encoded base-64 data from webhook and return an array.
     *
     * @param $webhookDataStr string this string should be encoded base-64, get from the webhook
     * @since YI-1066
     * @return array|false
     */
    private function webhookDataReprocess(string $webhookDataStr) {
        $dataDecoded = base64_decode($webhookDataStr);

        if(!$dataDecoded) return false;

        return json_decode($dataDecoded, true);
    }

    private function writeLog($comment, $name, $logType = Log::NOTICE)
    {
        Log::addLogger(
            array('text_file' => $name . '.log'),
            Log::ALL,
            'com_redshop'
        );

        Log::add($comment, $logType, 'com_redshop');
    }

    private function getSignature($data)
    {
        $private_key_pem = openssl_pkey_get_private($this->params->get('privateKey')
        );

        openssl_sign($data, $binary_signature, $private_key_pem, OPENSSL_ALGO_SHA256);

        return base64_encode($binary_signature);
    }
}
