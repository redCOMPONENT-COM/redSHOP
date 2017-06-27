<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$model            = $this->getModel('checkout');
$billingaddresses = $model->billingaddresses();
$billadd          = JLayoutHelper::render('cart.billing', array('billingaddresses' => $billingaddresses));

echo $billadd;