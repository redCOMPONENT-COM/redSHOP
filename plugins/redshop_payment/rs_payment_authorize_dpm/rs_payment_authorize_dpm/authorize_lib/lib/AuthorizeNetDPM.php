<?php
/**
 * Demonstrates the Direct Post Method.
 *
 * To implement the Direct Post Method you need to implement 3 steps:
 *
 * Step 1: Add necessary hidden fields to your checkout form and make your form is set to post to AuthorizeNet.
 *
 * Step 2: Receive a response from AuthorizeNet, do your business logic, and return
 *         a relay response snippet with a url to redirect the customer to.
 *
 * Step 3: Show a receipt page to your customer.
 *
 * This class is more for demonstration purposes than actual production use.
 *
 *
 * @package    AuthorizeNet
 * @subpackage AuthorizeNetDPM
 */

/**
 * A class that demonstrates the DPM method.
 *
 * @package    AuthorizeNet
 * @subpackage AuthorizeNetDPM
 */
class AuthorizeNetDPM extends AuthorizeNetSIM_Form
{

	const LIVE_URL = 'https://secure2.authorize.net/gateway/transact.dll';
	const SANDBOX_URL = 'https://test.authorize.net/gateway/transact.dll';

	/**
	 * Implements all 3 steps of the Direct Post Method for demonstration
	 * purposes.
	 */
	public static function directPostDemo($url, $api_login_id, $transaction_key, $amount = "0.00", $md5_setting = "")
	{

		// Step 1: Show checkout form to customer.
		if (!count($_POST) && !count($_GET))
		{
			$fp_sequence = time(); // Any sequential number like an invoice number.
			echo AuthorizeNetDPM::getCreditCardForm($amount, $fp_sequence, $url, $api_login_id, $transaction_key);
		}
		// Step 2: Handle AuthorizeNet Transaction Result & return snippet.
		elseif (count($_POST))
		{
			$response = new AuthorizeNetSIM($api_login_id, $md5_setting);
			if ($response->isAuthorizeNet())
			{
				if ($response->approved)
				{
					// Do your processing here.
					$redirect_url = $url . '?response_code=1&transaction_id=' . $response->transaction_id;
				}
				else
				{
					// Redirect to error page.
					$redirect_url = $url . '?response_code=' . $response->response_code . '&response_reason_text=' . $response->response_reason_text;
				}
				// Send the Javascript back to AuthorizeNet, which will redirect user back to your site.
				echo AuthorizeNetDPM::getRelayResponseSnippet($redirect_url);
			}
			else
			{
				echo "Error -- not AuthorizeNet. Check your MD5 Setting.";
			}
		}
		// Step 3: Show receipt page to customer.
		elseif (!count($_POST) && count($_GET))
		{
			if ($_GET['response_code'] == 1)
			{
				echo "Thank you for your purchase! Transaction id: " . htmlentities($_GET['transaction_id']);
			}
			else
			{
				echo "Sorry, an error occurred: " . htmlentities($_GET['response_reason_text']);
			}
		}
	}

	/**
	 * A snippet to send to AuthorizeNet to redirect the user back to the
	 * merchant's server. Use this on your relay response page.
	 *
	 * @param string $redirect_url Where to redirect the user.
	 *
	 * @return string
	 */
	public static function getRelayResponseSnippet($redirect_url)
	{
		return "<html><head><script language=\"javascript\">
                <!--
                window.location=\"{$redirect_url}\";
                //-->
                </script>
                </head><body><noscript><meta http-equiv=\"refresh\" content=\"1;url={$redirect_url}\"></noscript></body></html>";
	}

	/**
	 * Generate a sample form for use in a demo Direct Post implementation.
	 *
	 * @param string $amount             Amount of the transaction.
	 * @param string $fp_sequence        Sequential number(ie. Invoice #)
	 * @param string $relay_response_url The Relay Response URL
	 * @param string $api_login_id       Your API Login ID
	 * @param string $transaction_key    Your API Tran Key.
	 * @param bool   $test_mode          Use the sandbox?
	 * @param bool   $prefill            Prefill sample values(for test purposes).
	 *
	 * @return string
	 */
	public static function getCreditCardForm($amount, $fpSequence, $relayResponseUrl, $apiLoginId, $transactionKey, $test_mode = true, $preFill = true)
	{
		$time          = time();
		$fp            = self::getFingerprint($apiLoginId, $transactionKey, $amount, $fpSequence, $time);
		$sim           = new AuthorizeNetSIM_Form(
			array(
				'x_amount'         => $amount,
				'x_fp_sequence'    => $fpSequence,
				'x_fp_hash'        => $fp,
				'x_fp_timestamp'   => $time,
				'x_relay_response' => "TRUE",
				'x_relay_url'      => $relayResponseUrl,
				'x_login'          => $apiLoginId,
			)
		);
		$hidden_fields = $sim->getHiddenFieldString();
		$post_url      = ($test_mode ? self::SANDBOX_URL : self::LIVE_URL);

		return JLayoutHelper::render('forms.default',
			array(
				'postUrl'      => $post_url,
				'hiddenFields' => $hidden_fields,
				'preFill'      => $preFill
			),
			JPATH_ROOT . '/plugins/redshop_payment/rs_payment_authorize_dpm/layouts/'
		);
	}
}
