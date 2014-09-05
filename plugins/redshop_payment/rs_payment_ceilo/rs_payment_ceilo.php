<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgRedshop_paymentrs_payment_ceilo extends JPlugin
{
	public $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgRedshop_paymentrs_payment_ceilo(&$subject)
	{
		// Load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_ceilo');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	public function onPrePayment_rs_payment_ceilo($element, $data)
	{
		$config = new Redconfiguration;
		$currencyClass = new CurrencyHelper;
		$app = JFactory::getApplication();
		$objOrder = new order_functions;
		$uri = JURI::getInstance();
		$url = $uri->root();
		$user = JFactory::getUser();
		$sessionid = session_id();
		$session = JFactory::getSession();
		$ccdata = $session->get('ccdata');

		if ($element != 'rs_payment_ceilo')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}

		// Get params from plugin
		$ceilo_parameters = $this->getparameters('rs_payment_ceilo');
		$paymentinfo = $ceilo_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// Store number
		$ceilo_loja_id = $paymentparams->get('ceilo_loja_id', '');

		// Key
		$ceilo_loja_chave = $paymentparams->get('ceilo_loja_chave', '');

		// Auto capture
		$capturarAutomaticamente = $paymentparams->get('capturarAutomaticamente', '');
		$indicadorAutorizacao = $paymentparams->get('indicadorAutorizacao', '');
		$tentarAutenticar = $paymentparams->get('tentarAutenticar', '');
		$debug_mode = $paymentparams->get('debug_mode', 0);
		$tipoParcelamento = $paymentparams->get('tipoParcelamento', '');

		if ($capturarAutomaticamente == 1)
		{
			$capturarAutomaticamente = "true";
		}
		else
		{
			$capturarAutomaticamente = "false";
		}

		// Additional Customer Data
		$user_id = $data['billinginfo']->user_id;
		$remote_add = $_SERVER["REMOTE_ADDR"];

		// Email Settings
		$user_email = $data['billinginfo']->user_email;

		// Get Credit card Information
		$order_payment_name = substr($ccdata['order_payment_name'], 0, 50);
		$creditcard_code = strtolower($ccdata['creditcard_code']);
		$order_payment_number = substr($ccdata['order_payment_number'], 0, 20);
		$credit_card_code = substr($ccdata['credit_card_code'], 0, 4);
		$order_payment_expire_month = substr($ccdata['order_payment_expire_month'], 0, 2);
		$order_payment_expire_year = $ccdata['order_payment_expire_year'];
		$formaPagamento = 1;
		$order_number = substr($data['order_number'], 0, 16);
		$tax_exempt = false;

		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/rs_payment_ceilo/includes/include.php';
		include $paymentpath;

		$Pedido = new Pedido;

		$Pedido->formaPagamentoBandeira = $creditcard_code;

		if ($formaPagamento != "A" && $formaPagamento != "1")
		{
			$Pedido->formaPagamentoProduto = $tipoParcelamento;
			$Pedido->formaPagamentoParcelas = $formaPagamento;
		}
		else
		{
			$Pedido->formaPagamentoProduto = $formaPagamento;
			$Pedido->formaPagamentoParcelas = 1;
		}

		$Pedido->dadosEcNumero = $ceilo_loja_id;
		$Pedido->dadosEcChave = $ceilo_loja_chave;

		$Pedido->capturar = $capturarAutomaticamente;

		$Pedido->autorizar = $indicadorAutorizacao;

		$Pedido->dadosPortadorNumero = $order_payment_number;
		$Pedido->dadosPortadorVal = $order_payment_expire_year
			. str_pad($order_payment_expire_month, 2, "0", STR_PAD_LEFT);

		// Verifica se C�digo de Seguran�a foi informado e ajusta o indicador corretamente
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
		$Pedido->dadosPedidoNumero = $data['order_number'];

		// Assign Amount
		$tot_amount = $order_total = $data['order_total'];
		$amount = number_format($tot_amount, 2, '.', '') * 100;
		$Pedido->dadosPedidoValor = 1000;

		if ($tentarAutenticar == "sim")
		{
			$objResposta = $Pedido->RequisicaoTransacao(true);
		}
		else
		{
			$objResposta = $Pedido->RequisicaoTid();

			$Pedido->tid = $objResposta->tid;
			$Pedido->pan = $objResposta->pan;
			$Pedido->status = $objResposta->status;

			$objResposta = $Pedido->RequisicaoAutorizacaoPortador();
		}

		$Pedido->tid = $objResposta->tid;
		$Pedido->pan = $objResposta->pan;
		$Pedido->status = $objResposta->status;

		// Serializa Pedido e guarda na SESSION
		$StrPedido = $Pedido->ToString();
		$_SESSION["pedidos"]->append($StrPedido);

		// Resgata último pedido feito da SESSION
		$ultimoPedido = $_SESSION["pedidos"]->count();

		$ultimoPedido--;

		$Pedido->FromString($_SESSION["pedidos"]->offsetGet($ultimoPedido));

		// Consulta situa��o da transa��o
		$objResposta = $Pedido->RequisicaoConsulta();

		if ($capturarAutomaticamente == "true")
		{
			$message = $objResposta->captura->mensagem;
		}
		else
		{
			$message = $objResposta->autorizacao->mensagem;
		}

		// Atualiza status
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

		$values['message'] = $message;
		$values['transaction_id'] = (string) $objResposta->tid;

		$values = (object) $values;

		unset($_SESSION["pedidos"]);

		return $values;
	}

	public function getparameters($payment)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	public function onCapture_Paymentrs_payment_ceilo($element, $data)
	{
		$db = JFactory::getDbo();
		JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
		JLoader::load('RedshopHelperAdminOrder');
		$objOrder = new order_functions;

		// Get params from plugin
		$ceilo_parameters = $this->getparameters('rs_payment_ceilo');
		$paymentinfo = $ceilo_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);

		// Store number
		$ceilo_loja_id = $paymentparams->get('ceilo_loja_id', '');

		// Key
		$ceilo_loja_chave = $paymentparams->get('ceilo_loja_chave', '');

		// Add request-specific fields to the request string.
		$paymentpath = JPATH_SITE . '/plugins/redshop_payment/rs_payment_ceilo/includes/include.php';
		include $paymentpath;

		$objResposta = null;
		$Pedido = new Pedido;

		$amount = number_format($data['order_amount'], 2, '.', '') * 100;

		$Pedido->dadosEcNumero = $ceilo_loja_id;
		$Pedido->dadosEcChave = $ceilo_loja_chave;
		$Pedido->tid = $data['order_transactionid'];
		$Pedido->dadosPedidoNumero = $data['order_number'];

		$Pedido->dadosPedidoValor = $amount;
		$Pedido->formaPagamentoProduto = 1;
		$Pedido->formaPagamentoParcelas = 1;
		$PercentualCaptura = $Pedido->dadosPedidoValor;
		$objResposta = $Pedido->RequisicaoCaptura($PercentualCaptura, null);

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
