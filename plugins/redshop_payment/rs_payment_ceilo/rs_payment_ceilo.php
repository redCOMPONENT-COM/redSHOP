<?php

/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
//$mainframe =& JFactory::getApplication();
//$mainframe->registerEvent( 'onPrePayment', 'plgRedshoprs_payment_bbs' );
class plgRedshop_paymentrs_payment_ceilo extends JPlugin
{
	var $_table_prefix = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	function plgRedshop_paymentrs_payment_ceilo(&$subject)
	{
		// load plugin parameters
		parent::__construct($subject);
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_payment', 'rs_payment_ceilo');
		$this->_params = new JRegistry($this->_plugin->params);


	}

	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onPrePayment_rs_payment_ceilo($element, $data)
	{

		$config = new Redconfiguration();
		$currencyClass = new convertPrice ();
		$mainframe =& JFactory::getApplication();
		$objOrder = new order_functions();
		$uri =& JURI::getInstance();
		$url = $uri->root();
		$user = JFactory::getUser();
		$sessionid = session_id();
		$session =& JFactory::getSession();
		$ccdata = $session->get('ccdata');


		if ($element != 'rs_payment_ceilo')
		{
			return;
		}

		if (empty($plugin))
		{
			$plugin = $element;
		}


		// get params from plugin
		$ceilo_parameters = $this->getparameters('rs_payment_ceilo');
		$paymentinfo = $ceilo_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);


		$ceilo_loja_id = $paymentparams->get('ceilo_loja_id', ''); // store number
		$ceilo_loja_chave = $paymentparams->get('ceilo_loja_chave', ''); // key
		$capturarAutomaticamente = $paymentparams->get('capturarAutomaticamente', ''); // auto capture
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


		// get Credit card Information
		$order_payment_name = substr($ccdata['order_payment_name'], 0, 50);
		$creditcard_code = strtolower($ccdata['creditcard_code']);
		$order_payment_number = substr($ccdata['order_payment_number'], 0, 20);
		$credit_card_code = substr($ccdata['credit_card_code'], 0, 4); ////$_POST["cartaoCodigoSeguranca"]
		$order_payment_expire_month = substr($ccdata['order_payment_expire_month'], 0, 2);
		$order_payment_expire_year = $ccdata['order_payment_expire_year'];
		$formaPagamento = 1; //$ccdata['formaPagamento'];//$_POST["formaPagamento"]
		$order_number = substr($data['order_number'], 0, 16);
		$tax_exempt = false;

//echo $creditcard_code;

		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . 'rs_payment_ceilo' . DS . 'includes' . DS . 'include.php';
		include($paymentpath);


		$Pedido = new Pedido();

		// L� dados do $_POST
		$Pedido->formaPagamentoBandeira = $creditcard_code; //$_POST["codigoBandeira"];

		if ($formaPagamento != "A" && $formaPagamento != "1") ////$_POST["formaPagamento"]
		{
			$Pedido->formaPagamentoProduto = $tipoParcelamento; //$_POST["tipoParcelamento"];
			$Pedido->formaPagamentoParcelas = $formaPagamento;
		}
		else
		{
			$Pedido->formaPagamentoProduto = $formaPagamento;
			$Pedido->formaPagamentoParcelas = 1;
		}

		$Pedido->dadosEcNumero = $ceilo_loja_id; //LOJA;
		$Pedido->dadosEcChave = $ceilo_loja_chave; //LOJA_CHAVE;

		$Pedido->capturar = $capturarAutomaticamente; //$_POST["capturarAutomaticamente"];

		$Pedido->autorizar = $indicadorAutorizacao; //$_POST["indicadorAutorizacao"];


		$Pedido->dadosPortadorNumero = $order_payment_number; //$_POST["cartaoNumero"];
		$Pedido->dadosPortadorVal = $order_payment_expire_year . str_pad($order_payment_expire_month, 2, "0", STR_PAD_LEFT); //$_POST["cartaoValidade"];

		// Verifica se C�digo de Seguran�a foi informado e ajusta o indicador corretamente
		if ($credit_card_code == null || $credit_card_code == "")
		{
			$Pedido->dadosPortadorInd = "0";
		}
		else if ($Pedido->formaPagamentoBandeira == "mastercard")
		{
			$Pedido->dadosPortadorInd = "1";
		}
		else
		{
			$Pedido->dadosPortadorInd = "1";
		}
		$Pedido->dadosPortadorCodSeg = $credit_card_code;
		$Pedido->dadosPedidoNumero = $data['order_number'];

		//Assign Amount
		$tot_amount = $order_total = $data['order_total'];
		// $amount = $currencyClass->convert ( $tot_amount, '', 'USD' );
		$amount = number_format($tot_amount, 2, '.', '') * 100;
		$Pedido->dadosPedidoValor = 1000; //$_POST["produto"];


		if ($tentarAutenticar == "sim") // TRANSA��O
		{

			$objResposta = $Pedido->RequisicaoTransacao(true);

		}
		else // AUTORIZA��O DIRETA
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


		// Resgata �ltimo pedido feito da SESSION
		$ultimoPedido = $_SESSION["pedidos"]->count();

		$ultimoPedido -= 1;

		//$Pedido = new Pedido();
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
			//echo "order Success -----";

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

	function getparameters($payment)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__extensions WHERE `element`='" . $payment . "'";
		$db->setQuery($sql);
		$params = $db->loadObjectList();

		return $params;
	}

	function onCapture_Paymentrs_payment_ceilo($element, $data)
	{

		$db = JFactory::getDBO();
		require_once (JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php');
		$objOrder = new order_functions();

		// get params from plugin
		$ceilo_parameters = $this->getparameters('rs_payment_ceilo');
		$paymentinfo = $ceilo_parameters[0];
		$paymentparams = new JRegistry($paymentinfo->params);


		$ceilo_loja_id = $paymentparams->get('ceilo_loja_id', ''); // store number
		$ceilo_loja_chave = $paymentparams->get('ceilo_loja_chave', ''); // key


		// Add request-specific fields to the request string.
		$paymentpath = JPATH_SITE . DS . 'plugins' . DS . 'redshop_payment' . DS . 'rs_payment_ceilo' . DS . 'includes' . DS . 'include.php';
		include($paymentpath);


		$objResposta = null;
		$Pedido = new Pedido();
		//$Pedido->FromString($_SESSION["pedidos"]->offsetGet($key));
		$amount = number_format($data['order_amount'], 2, '.', '') * 100;


		$Pedido->dadosEcNumero = $ceilo_loja_id; //LOJA;
		$Pedido->dadosEcChave = $ceilo_loja_chave; //LOJA_CHAVE;
		$Pedido->tid = $data['order_transactionid'];
		$Pedido->dadosPedidoNumero = $data['order_number'];


		//$Pedido->dadosPedidoData = $XML->$DadosPedido->$DataHora;
		$Pedido->dadosPedidoValor = $amount;
		$Pedido->formaPagamentoProduto = 1;
		$Pedido->formaPagamentoParcelas = 1;
		$PercentualCaptura = $Pedido->dadosPedidoValor;
		$objResposta = $Pedido->RequisicaoCaptura($PercentualCaptura, null);

		$Pedido->status = $objResposta->status;


		// call function to post an order ------


		if ($Pedido->status == 6)
		{
			//echo "transaction Success -----";
			$message = $message = $objResposta->captura->mensagem;
			;
			$values->responsestatus = 'Success';

		}
		else
		{
			//echo "transaction Fail -----";
			$message = $message = $objResposta->captura->mensagem;
			;
			$values->responsestatus = 'Fail';

		}

		$values->message = $message;

		return $values;

	}


}