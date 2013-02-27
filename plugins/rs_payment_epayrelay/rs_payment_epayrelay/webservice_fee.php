<?php
function getCardInfo($merchantnumber, $cardno_prefix, $amount, $currency, $acquirer)
	{
		  require_once('lib/nusoap.php');
		  global $epayresponse;
		  global $fee;
		  global $cardtype;
		  global $cardtext;
		  $returnVal = false;
		  //
		  // Initialize the nusoap object with the ePay WSDL
		  //
		  $client = new nusoap_client('https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx?WSDL', 'wsdl', '', '', '', '');
		  $err = $client->getError();
		  if ($err) {
		  	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
		  	exit();
		  }
		  //
		  // Create an arry with the parameters to the webservice
		  //
		  $param = array(
		    'merchantnumber' => $merchantnumber, 
		    'cardno_prefix' => $cardno_prefix, 
		    'amount' => $amount,
		    'currency' => $currency,
		    'acquirer' => $acquirer);
		  $result = $client->call('getcardinfo', array('parameters' => $param), '', '', false, true);
		  // Check for a fault
		  if ($client->fault) {
		  	echo '<h2>An error occured during webservice</h2><pre>';
		  	print_r($result);
		  	echo '</pre>';
		  } else {
		  	// Check for errors
		  	$err = $client->getError();
		  	if ($err) {
		      //
		  		// Display the error
		  		//
		  		echo '<h2>An error occured during webservice</h2><pre>' . $err . '</pre>';
		  	} else {
		  	  //
		  		// Display the result
		  		//
		  		/*echo '<h2>Returning values from capturePayment</h2><pre>';
		  		print_r($result);
		  		echo '</pre>';*/
		  		
		  		if ($result['getcardinfoResult'] == 'true') {
		  		  $returnVal = true;
		  		  $fee = $result['fee'];
		  		  $cardtype = $result['cardtype'];
		  		  $cardtext = $result['cardtypetext'];
		      } else {
		        //
		        // Only use the epayresponse and pbsresponse on errors!
		        //
		        $epayresponse = $result['epayresponse'];
		      }
		  	}
		  }
		return $returnVal;
	}
		
	$epayrespons = "";
	$merchantnumber = $_REQUEST['merchantnumber'];
	$cardno_prefix = $_REQUEST['cardno_prefix'];
	$amount = $_REQUEST['amount'];
	$currency = $_REQUEST['currency'];
	$acquirer= $_REQUEST['acquirer'];
	
	$fee = "";
	$cardtype = "";
	$cardtext = "";
	
	if (getCardInfo($merchantnumber,$cardno_prefix,$amount,$currency, $acquirer)) {
		echo $fee . "," . $cardtype . "," . $cardtext;
	} else {
		echo $epayresponse;
	}
?>