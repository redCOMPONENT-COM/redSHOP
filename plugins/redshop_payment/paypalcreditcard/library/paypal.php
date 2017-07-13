<?php
/**
 * @package     Redshop.Payment
 * @subpackage  PaypalPayment
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

/**
 * Common architecture for paypal payment class.
 *
 * @package     Redshop.Payment
 * @subpackage  PaypalPayment
 * @since       1.5
 */
class RedshopPaypalPayment extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin base path
	 *
	 * @var  null
	 */
	protected $path = null;

	/**
	 * Load Framework
	 *
	 * @return  object  Paypal Application Context
	 */
	public function loadFramework()
	{
		JLoader::import('redshop.library');

		// If the project is used as its own project, it would use rest-api-sdk-php composer autoloader.
		$composerAutoload = __DIR__ . '/vendor/autoload.php';

		if (!JFile::exists($composerAutoload))
		{
			echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies."
				. "\nPlease see the README for more information.\n";

			exit(1);
		}

		require_once $composerAutoload;

		return $this->getApiContext();
	}

	/**
	 * Get Paypal Application Context
	 *
	 * @return  object  Paypal Application Context
	 */
	public function getApiContext()
	{
		$clientId     = $this->params->get('clientId');
		$clientSecret = $this->params->get('clientSecret');

		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				$clientId,
				$clientSecret
			)
		);

		$mode    = $this->params->get('isTest') ? 'sandbox' : 'live';
		$debug   = $this->params->get('isDebug') ? 'DEBUG' : 'FINE';
		$isDebug = (boolean) $this->params->get('isDebug');

		$apiContext->setConfig(
			array(
				'mode'           => $mode,
				'log.LogEnabled' => $isDebug,
				'log.FileName'   => '../PayPal.log',
				'log.LogLevel'   => $debug, // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
				'cache.enabled'  => true,
				// 'http.CURLOPT_CONNECTTIMEOUT' => 30
				// 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
			)
		);

		return $apiContext;
	}
}
