<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

class plgRedshop_paymentrs_payment_ceilo extends JPlugin
{
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_ceilo($element, $data)
	{
		$config        = new Redconfiguration;
		$currencyClass = new CurrencyHelper;
		$app           = JFactory::getApplication();
		$objOrder      = new order_functions;
		$uri           = JURI::getInstance();
		$url           = $uri->root();
		$user          = JFactory::getUser();
		$sessionid     = session_id();
		$session       = JFactory::getSession();
		$ccdata        = $session->get('ccdata');

		if ($element != 'rs_payment_ceilo')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		// Store number
		$ceilo_loja_id = $this->params->get('ceilo_loja_id', '');

		// Key
		$ceilo_loja_chave = $this->params->get('ceilo_loja_chave', '');

		// Auto capture
		$capturarAutomaticamente = $this->params->get('capturarAutomaticamente', '');
		$indicadorAutorizacao    = $this->params->get('indicadorAutorizacao', '');
		$tentarAutenticar        = $this->params->get('tentarAutenticar', '');
		$debug_mode              = $this->params->get('debug_mode', 0);
		$tipoParcelamento        = $this->params->get('tipoParcelamento', '');

		if ($capturarAutomaticamente == 1)
		{
			$capturarAutomaticamente = "true";
		}
		else
		{
			$capturarAutomaticamente = "false";
		}

		// Additional Customer Data
		$user_id    = $data['billinginfo']->user_id;
		$remote_add = $_SERVER["REMOTE_ADDR"];

		// Email Settings
		$user_email = $data['billinginfo']->user_email;

		// Get Credit card Information
		$order_payment_name         = substr($ccdata['order_payment_name'], 0, 50);
		$creditcard_code            = strtolower($ccdata['creditcard_code']);
		$order_payment_number       = substr($ccdata['order_payment_number'], 0, 20);
		$credit_card_code           = substr($ccdata['credit_card_code'], 0, 4);
		$order_payment_expire_month = substr($ccdata['order_payment_expire_month'], 0, 2);
		$order_payment_expire_year  = $ccdata['order_payment_expire_year'];
		$formaPagamento             = 1;
		$order_number               = substr($data['order_number'], 0, 16);
		$tax_exempt                 = false;

		include JPATH_SITE . '/plugins/redshop_payment/rs_payment_ceilo/includes/include.php';

		$Pedido = new Pedido;

		$Pedido->formaPagamentoBandeira = $creditcard_code;

		if ($formaPagamento != "A" && $formaPagamento != "1")
		{
			$Pedido->formaPagamentoProduto  = $tipoParcelamento;
			$Pedido->formaPagamentoParcelas = $formaPagamento;
		}
		else
		{
			$Pedido->formaPagamentoProduto  = $formaPagamento;
			$Pedido->formaPagamentoParcelas = 1;
		}

		$Pedido->dadosEcNumero = $ceilo_loja_id;
		$Pedido->dadosEcChave  = $ceilo_loja_chave;
		$Pedido->capturar      = $capturarAutomaticamente;
		$Pedido->autorizar     = $indicadorAutorizacao;

		$Pedido->dadosPortadorNumero = $order_payment_number;
		$Pedido->dadosPortadorVal    = $order_payment_expire_year
			. str_pad($order_payment_expire_month, 2, "0", STR_PAD_LEFT);

		// Checks if Code of safety was entered correctly and adjusts the indicator
		if ($credit_card_code == null || $credit_card_code == "")
		{
			$Pedido->dadosPortadorInd = "0";
		}
		elseif ($Pedido->formaPagamentoBandeira == "mastercard")
		{
			$Pedido->dadosPortadorInd = "1";
		}
		else
		{
			$Pedido->dadosPortadorInd = "1";
		}

		$Pedido->dadosPortadorCodSeg = $credit_card_code;
		$Pedido->dadosPedidoNumero   = $data['order_number'];

		// Assign Amount
		$tot_amount = $order_total = $data['order_total'];
		$amount     = number_format($tot_amount, 2, '.', '') * 100;
		$Pedido->dadosPedidoValor = 1000;

		if ($tentarAutenticar == "sim")
		{
			$objResposta = $Pedido->RequisicaoTransacao(true);
		}
		else
		{
			$objResposta    = $Pedido->RequisicaoTid();
			$Pedido->tid    = $objResposta->tid;
			$Pedido->pan    = $objResposta->pan;
			$Pedido->status = $objResposta->status;
			$objResposta    = $Pedido->RequisicaoAutorizacaoPortador();
		}

		$Pedido->tid    = $objResposta->tid;
		$Pedido->pan    = $objResposta->pan;
		$Pedido->status = $objResposta->status;

		// Serialized Request for Guard on SESSION
		$StrPedido = $Pedido->ToString();
		$_SESSION["pedidos"]->append($StrPedido);

		// Rescues the last request made ​​SESSION
		$ultimoPedido = $_SESSION["pedidos"]->count();

		$ultimoPedido--;

		$Pedido->FromString($_SESSION["pedidos"]->offsetGet($ultimoPedido));

		// Consultation locates the transao
		$objResposta = $Pedido->RequisicaoConsulta();

		if ($capturarAutomaticamente == "true")
		{
			$message = $objResposta->captura->mensagem;
		}
		else
		{
			$message = $objResposta->autorizacao->mensagem;
		}

		// Actualization status
		$Pedido->status = $objResposta->status;

		$Pedido->tid = $objResposta->tid;

		if ($Pedido->status == '4' || $Pedido->status == '6')
		{
			if ($debug_mode == "0")
			{
				$message = 'Success';
			}

			$values['responsestatus'] = 'Success';
		}
		else
		{
			if ($debug_mode == "0")
			{
				$message = 'Fail';
			}

			$values['responsestatus'] = 'Fail';
		}

		$values['message']        = $message;
		$values['transaction_id'] = (string) $objResposta->tid;

		$values = (object) $values;

		unset($_SESSION["pedidos"]);

		return $values;
	}

	public function onCapture_Paymentrs_payment_ceilo($element, $data)
	{
		$db = JFactory::getDbo();
		JLoader::load('RedshopHelperAdminOrder');
		$objOrder = new order_functions;

		// Store number
		$ceilo_loja_id = $this->params->get('ceilo_loja_id', '');

		// Key
		$ceilo_loja_chave = $this->params->get('ceilo_loja_chave', '');

		// Add request-specific fields to the request string.
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/rs_payment_ceilo/includes/include.php';
		include $paymentpath;

		$objResposta = null;
		$Pedido = new Pedido;

		$amount = number_format($data['order_amount'], 2, '.', '') * 100;

		$Pedido->dadosEcNumero          = $ceilo_loja_id;
		$Pedido->dadosEcChave           = $ceilo_loja_chave;
		$Pedido->tid                    = $data['order_transactionid'];
		$Pedido->dadosPedidoNumero      = $data['order_number'];

		$Pedido->dadosPedidoValor       = $amount;
		$Pedido->formaPagamentoProduto  = 1;
		$Pedido->formaPagamentoParcelas = 1;
		$PercentualCaptura              = $Pedido->dadosPedidoValor;
		$objResposta                    = $Pedido->RequisicaoCaptura($PercentualCaptura, null);

		$Pedido->status = $objResposta->status;

		// Call function to post an order ------
		$values = new stdClass;

		if ($Pedido->status == 6)
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
