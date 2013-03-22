<?php
require_once 'braintree/lib/Braintree.php';

// get plugin params
$environment = $this->_params->get("environment");
$merchnat_id = $this->_params->get("merchant_id");
$public_key = $this->_params->get("public_key");
$private_key = $this->_params->get("private_key");

Braintree_Configuration::environment($environment);
Braintree_Configuration::merchantId($merchnat_id);
Braintree_Configuration::publicKey($public_key);
Braintree_Configuration::privateKey($private_key);

?>
