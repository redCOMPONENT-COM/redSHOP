<?php

class EpaySoap {
	public function capture($marchantnumber, $transactionid, $amount)
	{
		$epay_params = array();
		$epay_params['merchantnumber'] = $marchantnumber;
		$epay_params['transactionid'] = $transactionid;
		$epay_params['amount'] = intval($amount);
		
		$result = $this->_soapcall('capture', $epay_params);
		
		return $result;
	}
	
	public function credit($marchantnumber, $transactionid, $amount)
	{
		$epay_params = array();
		$epay_params['merchantnumber'] = $marchantnumber;
		$epay_params['transactionid'] = $transactionid;
		$epay_params['amount'] = intval($amount);
		
		$result = $this->_soapcall('credit', $epay_params);
		
		return $result;
	}
	
	public function delete($marchantnumber, $transactionid)
	{
		$epay_params = array();
		$epay_params['merchantnumber'] = $marchantnumber;
		$epay_params['transactionid'] = $transactionid;
		
		$result = $this->_soapcall('delete', $epay_params);
		
		return $result;
	}
	
	public function getEpayError($marchantnumber, $epay_response_code)
	{
		$epay_params = array();
		$epay_params['merchantnumber'] = $marchantnumber;
		$epay_params['language'] = 2;
		$epay_params['epayresponsecode'] = $epay_response_code;
		
		$result = $this->_soapcall('getEpayError', $epay_params);	
				
		$result = $result['epayresponsestring'];

		
		return $result;
	}
	
	public function getPbsError($marchantnumber, $pbs_response_code)
	{
		$epay_params = array();
		$epay_params['merchantnumber'] = $marchantnumber;
		$epay_params['language'] = 2;
		$epay_params['pbsresponsecode'] = $pbs_response_code;
		
		$result = $this->_soapcall('getPbsError', $epay_params);
		
		$result = $result['pbsresponsestring'];
		
		return $result;
	}
	
	public function gettransaction($marchantnumber, $transactionid)
	{
		$epay_params = array();
		$epay_params['merchantnumber'] = $marchantnumber;
		$epay_params['transactionid'] = $transactionid;
		
		$result = $this->_soapcall('gettransaction', $epay_params);
		
		return $result;
	}
	
	public function gettransactionInformation($marchantnumber, $transactionid)
	{
		$epay_params = array();
		$epay_params['merchantnumber'] = $marchantnumber;
		$epay_params['transactionid'] = $transactionid;
		
		$result = $this->_soapcall('gettransaction', $epay_params);
		
		if ($result['gettransactionResult'] == true)
			return $result['transactionInformation'];
		else
			return false;
	}
	
	private function _soapcall($call, $params)
	{
		require_once(dirname(__FILE__).'/lib/nusoap.php');
		
		$soapclient = new nusoap_client('https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx?WSDL','wsdl');
				
		$result = $soapclient->call($call, array('parameters' => $params));
		
		/* Debug */
		//echo '<h2>Request</h2><pre>' . htmlspecialchars($soapclient->request, ENT_QUOTES) . '</pre>';
		//echo '<h2>Response</h2><pre>' . htmlspecialchars($soapclient->response, ENT_QUOTES) . '</pre>';
		//echo '<h2>Debug</h2><pre>' . htmlspecialchars($soapclient->debug_str, ENT_QUOTES) . '</pre>';
		//echo '<pre>'; print_r($result); echo '</pre>';
		
		// Check for a fault
		if ($soapclient->fault) {
			echo '<div class="alert error"><h2>Fault</h2><pre>';
			print_r($result);
			echo '</pre></div>';
		} else {
			// Check for errors
			$err = $soapclient->getError();
			if ($err) {
				// Display the error
				echo '<div class="alert error"><h2>Error</h2><pre>' . $err . '</pre></div>';
			} else {
				return $result;
			}
		}
		
		return false;
	}
};
?>