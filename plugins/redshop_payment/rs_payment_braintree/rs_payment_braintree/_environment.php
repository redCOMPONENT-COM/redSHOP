<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once 'braintree/lib/Braintree.php';

// get plugin params
$environment = $this->params->get("environment");
$merchnat_id = $this->params->get("merchant_id");
$public_key = $this->params->get("public_key");
$private_key = $this->params->get("private_key");

Braintree_Configuration::environment($environment);
Braintree_Configuration::merchantId($merchnat_id);
Braintree_Configuration::publicKey($public_key);
Braintree_Configuration::privateKey($private_key);
