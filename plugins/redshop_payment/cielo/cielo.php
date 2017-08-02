<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 *  PlgRedshop_PaymentCielo class.
 *
 * @package  Redshopb.Plugin
 * @since    1.7.0
 */
class PlgRedshop_PaymentCielo extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 *
	 * @param   Object  $element  element object
	 * @param   Object  $data     data params
	 *
	 * @return  $values
	 */
	public function onPrePayment_Cielo($element, $data)
	{
		$app 		= JFactory::getApplication();
		$session 	= JFactory::getSession();
		$ccdata 	= $session->get('ccdata');

		if ($element != 'cielo')
		{
			return;
		}

		$cart = $session->get('cart');

		$order                 = new stdClass;
		$order->OrderNumber    = $data['order_number'];
		$order->SoftDescriptor = $app->input->getString('customer_note');

		$order->Cart                  = new stdClass;

		if ($data['odiscount'] > 0)
		{
			$order->Cart->Discount        = new stdClass;
			$order->Cart->Discount->Type  = 'Amount';
			$order->Cart->Discount->Value = $data['odiscount'] * 100;
		}

		$order->Cart->Items                 = array();

		for ($i = 0; $i < $cart['idx']; $i++)
		{
			$itemId = $cart[$i]['product_id'];
			$item = RedshopHelperProduct::getProductById($itemId);

			$order->Cart->Items[$i]              = new stdClass;
			$order->Cart->Items[$i]->Name        = $item->product_name;
			$order->Cart->Items[$i]->Description = $item->product_s_desc;
			$order->Cart->Items[$i]->UnitPrice   = $cart[$i]['product_price'];
			$order->Cart->Items[$i]->Quantity    = $cart[$i]['quantity'];
			$order->Cart->Items[$i]->Type        = 'Payment';
			$order->Cart->Items[$i]->Sku         = $item->product_number;
		}

		$order->Shipping       = new stdClass;
		$order->Shipping->Type = 'Free';

		if ($data['order_shipping'] > 0)
		{
			$order->Shipping->Type = 'Correios';
		}

		$order->Shipping->SourceZipCode = $this->params->get('sourceZipCode');
		$order->Shipping->TargetZipCode = $data['shippinginfo']->zipcode;

		$order->Shipping->Address             = new stdClass;
		$order->Shipping->Address->Street     = $data['shippinginfo']->address;
		$order->Shipping->Address->Number     = '';
		$order->Shipping->Address->Complement = '';
		$order->Shipping->Address->District   = '';
		$order->Shipping->Address->City       = $data['shippinginfo']->city;
		$order->Shipping->Address->State      = $data['shippinginfo']->state_2_code;

		$order->Shipping->Services              = array();
		$order->Shipping->Services[0]           = new stdClass;
		$order->Shipping->Services[0]->Name     = 'Shipping';
		$order->Shipping->Services[0]->Price    = $data['order_shipping'] * 100;
		$order->Shipping->Services[0]->DeadLine = 15;

		$order->Payment                 = new stdClass;
		$order->Payment->BoletoDiscount = 0;
		$order->Payment->DebitDiscount  = 0;

		$order->Customer           = new stdClass;
		$order->Customer->Identity = $data['billinginfo']->users_info_id;
		$order->Customer->FullName = $data['billinginfo']->firstname . ' ' . $data['billinginfo']->lastname;
		$order->Customer->Email    = $data['billinginfo']->user_email;
		$order->Customer->Phone    = $data['billinginfo']->phone;

		$order->Options                   = new stdClass;
		$order->Options->AntifraudEnabled = false;

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'https://cieloecommerce.cielo.com.br/api/public/v1/orders');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($order));
		curl_setopt(
			$curl,
			CURLOPT_HTTPHEADER,
			[
				'MerchantId: ' . $this->params->get('merchantId'),
				'Content-Type: application/json'
			]
		);

		$response = curl_exec($curl);

		curl_close($curl);

		$json = json_decode($response);

		$values = new stdClass;

		if ($json->Settings)
		{
			$values->responsestatus = 'Success';
			$message                = JText::_('PLG_REDSHOP_PAYMENT_CIELO_ORDER_PLACED');
		}
		else
		{
			$values->responsestatus = 'Fail';
			$message                = $json->Message . ' ' . JText::_('PLG_REDSHOP_PAYMENT_CIELO_ORDER_NOT_PLACED');
		}

		$values->transaction_id = '';
		$values->message        = $message;

		return $values;
	}

	/**
	 * onCapture_PaymentCielo description
	 * 
	 * @param   Object  $element  element
	 * @param   Object  $data     data params
	 * 
	 * @return  $values
	 */
	public function onCapture_PaymentCielo($element, $data)
	{
		// Store number
		$cieloLojaId = $this->params->get('cielo_loja_id', '');

		// Key
		$cieloLojaChave = $this->params->get('cielo_loja_chave', '');

		// Add request-specific fields to the request string.
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/cielo/includes/include.php';
		include $paymentpath;

		$objResposta = null;
		$pedido = new Pedido;

		$amount = number_format($data['order_amount'], 2, '.', '') * 100;

		$pedido->dadosEcNumero          = $cieloLojaId;
		$pedido->dadosEcChave           = $cieloLojaChave;
		$pedido->tid                    = $data['order_transactionid'];
		$pedido->dadosPedidoNumero      = $data['order_number'];

		$pedido->dadosPedidoValor       = $amount;
		$pedido->formaPagamentoProduto  = 1;
		$pedido->formaPagamentoParcelas = 1;
		$percentualCaptura              = $pedido->dadosPedidoValor;
		$objResposta                    = $pedido->RequisicaoCaptura($percentualCaptura, null);

		$pedido->status = $objResposta->status;

		// Call function to post an order ------
		$values = new stdClass;

		if ($pedido->status == 6)
		{
			$message = $objResposta->captura->mensagem;
			$values->responsestatus = 'Success';
		}
		else
		{
			$message = $objResposta->captura->mensagem;
			$values->responsestatus = 'Fail';
		}

		$values->message = $message;

		return $values;
	}
}
