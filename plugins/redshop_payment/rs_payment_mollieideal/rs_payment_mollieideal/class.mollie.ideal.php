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
class ideal
{
	var $partnerid = null;
	var $transaction_id = null;
	var $bankid = null;
	var $amount = null;
	var $description = null;
	var $reporturl = null;
	var $returnurl = null;
	var $country = 31;
	var $currency = 'EUR';
	var $payed = false;
	var $bankurl = null;
	var $statusmessage = null;
	var $testmode = false;
	var $banks = array();

	function setPartnerID($partnerid)
	{
		if (is_numeric($partnerid))
		{
			$this->partnerid = $partnerid;

			return true;
		}

		return false;
	}

	function setAmount($amount)
	{
		$currency = new convertPrice;
		$amount = $currency->convert($amount, '', 'EUR');
		$amount *= 100;

		if (is_numeric($amount))
		{
			$this->amount = $amount;

			return true;
		}

		return false;
	}

	function setCountry($country)
	{
		if (is_numeric($country))
		{
			$this->country = $country;

			return true;
		}

		return false;
	}

	function setReportURL($reporturl)
	{
		if (preg_match('|(\w+)://([^/:]+)(:\d+)?/(.*)|', $reporturl))
		{
			$this->reporturl = $reporturl;

			return true;
		}

		return false;
	}

	function setReturnURL($returnurl)
	{
		if (preg_match('|(\w+)://([^/:]+)(:\d+)?/(.*)|', $returnurl))
		{
			$this->returnurl = $returnurl;

			return true;
		}

		return false;
	}

	function setDescription($description)
	{
		if ($description != '')
		{
			$this->description = substr($description, 0, 29);

			return true;
		}

		return false;
	}

	function setBankid($bankid)
	{
		if ($bankid != '')
		{
			$this->bankid = $bankid;

			return true;
		}

		return false;
	}

	function setTransactionId($transaction_id)
	{
		if ($transaction_id != '')
		{
			$this->transaction_id = $transaction_id;

			return true;
		}

		return false;
	}

	function setTestMode($testmode)
	{
		return ($this->testmode = $testmode);
	}

	function fetchBanks()
	{
		// Gets/refreshes banks from mollie
		$result = $this->sendToHost('www.mollie.nl', '/xml/ideal/', 'a=banklist' . ($this->testmode ? '&partnerid=' . $this->partnerid : ''));

		if (!$result)
			return false;

		list($headers, $xml) = preg_split("/(\r?\n){2}/", $result, 2);

		$data = new SimpleXMLElement($xml);
		$data = $data->bank;

		// Build banks-array
		$this->banks = array();

		foreach ($data as $bank)
		{
			$this->banks[(string) $bank->bank_id] = (string) $bank->bank_name;
		}

		return $this->banks;
	}

	function createPayment()
	{
		// Prepares a payment with mollie
		if ($this->partnerid == '' or
			$this->amount == '' or
			$this->reporturl == '' or
			$this->returnurl == '' or
			$this->description == '' or
			$this->bankid == ''
		)
			return false;


		$result = $this->sendToHost('www.mollie.nl', '/xml/ideal/',
			'a=fetch' .
				'&partnerid=' . urlencode($this->partnerid) .
				'&bank_id=' . urlencode($this->bankid) .
				'&amount=' . urlencode($this->amount) .
				'&reporturl=' . urlencode($this->reporturl) .
				'&description=' . urlencode($this->description) .
				'&returnurl=' . urlencode($this->returnurl));

		if (!$result)
			return false;

		list($headers, $xml) = preg_split("/(\r?\n){2}/", $result, 2);

		$data = new SimpleXMLElement($xml);
		$data = $data->order;

		$this->transaction_id = (string) $data->transaction_id;
		$this->amount = (int) $data->amount;
		$this->currency = (string) $data->currency;
		$this->bankurl = html_entity_decode((string) $data->URL);
		$this->statusmessage = (string) $data->message;


		return true;
	}

	function checkPayment($pid, $tid, $testmode = 0)
	{
		// Check a payment with mollie
		$result = $this->sendToHost('www.mollie.nl', '/xml/ideal/',
			'a=check' .
				'&partnerid=' . urlencode($pid) .
				'&transaction_id=' . urlencode($tid) .
				($testmode ? '&testmode=true' : ''));


		if (!$result)
			return false;

		list($headers, $xml) = preg_split("/(\r?\n){2}/", $result, 2);

		$data = new SimpleXMLElement($xml);
		$data = $data->order;

		$this->payed = ((string) $data->payed == 'true');
		$this->amount = (int) $data->amount;
		$this->statusmessage = (string) $data->message;

		return $data;
	}

	function sendToHost($host, $path, $data)
	{
		if ($this->testmode)
		{
			$data .= '&testmode=true';
		}

		// Posts data to server
		$fp = @fsockopen($host, 80);
		$buf = '';

		if ($fp)
		{
			@fputs($fp, "POST $path HTTP/1.0\n");
			@fputs($fp, "Host: $host\n");
			@fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
			@fputs($fp, "Content-length: " . strlen($data) . "\n");
			@fputs($fp, "Connection: close\n\n");
			@fputs($fp, $data);
			while (!feof($fp))
				$buf .= fgets($fp, 128);
			fclose($fp);
		}

		return $buf;
	}
}

?>