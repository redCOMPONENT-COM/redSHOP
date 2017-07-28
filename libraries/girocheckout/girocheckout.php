<?php
/**
 * RedSLIDER Library file.
 * Including this file into your application will make redSLIDER available to use.
 *
 * @package    RedSLIDER.Library
 * @copyright  Copyright (C) 2013 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later, see LICENSE.
 */

defined('JPATH_PLATFORM') or die;

define('__GIROCHECKOUT_SDK_VERSION__', '2.1.14');

if( version_compare( phpversion(), '5.3.0', '<' ) ) {
  // Don't use third parameter for PHP < 5.3
  spl_autoload_register(array('GiroCheckout_SDK_Autoloader', 'load'), TRUE);
}
else {
  spl_autoload_register(array('GiroCheckout_SDK_Autoloader', 'load'), TRUE, TRUE);
}

if( defined('__GIROCHECKOUT_SDK_DEBUG__') && __GIROCHECKOUT_SDK_DEBUG__ === TRUE ) {
  GiroCheckout_SDK_Config::getInstance()->setConfig('DEBUG_MODE',TRUE);
}

class GiroCheckout_SDK_Autoloader {
	public static function load($classname) {
		$filename = $classname . '.php';
		$pathsArray = array ('api',
				'helper',
				'./',
				'api/bluecode',
				'api/giropay',
				'api/directdebit',
				'api/creditcard',
		'api/maestro',
				'api/eps',
				'api/ideal',
				'api/paypal',
				'api/tools',
				'api/girocode',
				'api/paydirekt',
				'api/sofortuw',
				'api/paypage'
	);

		foreach($pathsArray as $path) {
			if($path == './') {
				$pathToFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sdk' . DIRECTORY_SEPARATOR . $filename;
			} else {
				$pathToFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sdk' . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $filename;
			}

			if (file_exists($pathToFile)) {
				require_once $pathToFile;
				return true;
			} else {
				continue;
			}
		}
		return false;
	}
}
