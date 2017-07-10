<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_promote_free_shipping
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$shippingHelper = shipping::getInstance();
$shippingRateId = $params->get("shipping_rate_id");
$text 			= $shippingHelper->getfreeshippingRate($shippingRateId);
$conditionText  = $params->get("condition_text");

require JModuleHelper::getLayoutPath('mod_redshop_promote_free_shipping');
