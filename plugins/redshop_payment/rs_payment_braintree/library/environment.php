<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/vendor/autoload.php';

// Get plugin params
$environment = $this->params->get("environment");
$merchantId  = $this->params->get("merchant_id");
$publicKey   = $this->params->get("public_key");
$privateKey  = $this->params->get("private_key");

Braintree_Configuration::environment($environment);
Braintree_Configuration::merchantId($merchantId);
Braintree_Configuration::publicKey($publicKey);
Braintree_Configuration::privateKey($privateKey);
