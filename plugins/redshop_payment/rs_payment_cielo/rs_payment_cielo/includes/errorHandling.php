<?php

$logFile = "../logs/log.log";

// Verifica em Resposta XML a ocorr�ncia de erros
// Par�metros: XML de envio, XML de Resposta
function VerificaErro($vmPost, $vmResposta)
{
	$error_msg = null;

	try
	{
		if (stripos($vmResposta, "SSL certificate problem") !== false)
		{
			throw new Exception("CERTIFICADO INV�LIDO - O certificado da transa��o n�o foi aprovado", "099");
		}

		$objResposta = simplexml_load_string($vmResposta, null, LIBXML_NOERROR);

		if ($objResposta == null)
		{
			throw new Exception("HTTP READ TIMEOUT - o Limite de Tempo da transa��o foi estourado", "099");
		}
	}
	catch (Exception $ex)
	{
		$error_msg = "     C�digo do erro: " . $ex->getCode() . "\n";
		$error_msg .= "     Mensagem: " . $ex->getMessage() . "\n";

		// Gera p�gina HTML
		echo '<html><head><title>Erro na transa��o</title></head><body>';
		echo '<span style="color:red;, font-weight:bold;">Ocorreu um erro em sua transa��o!</span>' . '<br />';
		echo '<span style="font-weight:bold;">Detalhes do erro:</span>' . '<br />';
		echo '<pre>' . $error_msg . '<br /><br />';
		//echo "     XML de envio: " . "<br />" . htmlentities($vmPost);
		echo '</pre><p><center>';
		echo '<input type="button" value="Retornar" onclick="javascript:if(window.opener!=null){window.opener.location.reload();' .
			'window.close();}else{window.location.href=' . "'index.php';" . '}" />';
		echo '</center></p></body></html>';
		$error_msg .= "     XML de envio: " . "\n" . $vmPost;

		// Dispara o erro
		trigger_error($error_msg, E_USER_ERROR);

		return true;
	}

	if ($objResposta->getName() == "erro")
	{
		$error_msg = "     C�digo do erro: " . $objResposta->codigo . "\n";
		$error_msg .= "     Mensagem: " . utf8_decode($objResposta->mensagem) . "\n";
		// Gera p�gina HTML
		echo '<html><head><title>Erro na transa��o</title></head><body>';
		echo '<span style="color:red;, font-weight:bold;">Ocorreu um erro em sua transa��o!</span>' . '<br />';
		echo '<span style="font-weight:bold;">Detalhes do erro:</span>' . '<br />';
		echo '<pre>' . $error_msg . '<br /><br />';
		//echo "     XML de envio: " . "<br />" . htmlentities($vmPost);
		echo '</pre><p><center>';
		echo '<input type="button" value="Retornar" onclick="javascript:if(window.opener!=null){window.opener.location.reload();' .
			'window.close();}else{window.location.href=' . "'index.php';" . '}" />';
		echo '</center></p></body></html>';
		$error_msg .= "     XML de envio: " . "\n" . $vmPost;

		// Dispara o erro
		trigger_error($error_msg, E_USER_ERROR);
	}
}

// Grava erros no arquivo de log
function Handler($eNum, $eMsg, $file, $line, $eVars)
{
	$logFile = "../logs/log.log";
	$e = "";
	$Data = date("Y-m-d H:i:s (T)");

	$errortype = array(
		E_ERROR             => 'ERROR',
		E_WARNING           => 'WARNING',
		E_PARSE             => 'PARSING ERROR',
		E_NOTICE            => 'RUNTIME NOTICE',
		E_CORE_ERROR        => 'CORE ERROR',
		E_CORE_WARNING      => 'CORE WARNING',
		E_COMPILE_ERROR     => 'COMPILE ERROR',
		E_COMPILE_WARNING   => 'COMPILE WARNING',
		E_USER_ERROR        => 'ERRO NA TRANSACAO',
		E_USER_WARNING      => 'USER WARNING',
		E_USER_NOTICE       => 'USER NOTICE',
		E_STRICT            => 'RUNTIME NOTICE',
		E_RECOVERABLE_ERROR => 'CATCHABLE FATAL ERROR'
	);

	$e .= "**********************************************************\n";
	$e .= $eNum . " " . $errortype[$eNum] . " - ";
	$e .= $Data . "\n";
	$e .= "     ARQUIVO: " . $file . "(Linha " . $line . ")\n";
	$e .= "     MENSAGEM: " . "\n" . $eMsg . "\n\n";

	error_log($e, 3, $logFile);

	//exit();
}

$olderror = set_error_handler("Handler");
ini_set('error_log', $logFile);
ini_set('log_errors', 'On');
ini_set('display_errors', 'On');
ini_set("date.timezone", "America/Sao_Paulo");

?>