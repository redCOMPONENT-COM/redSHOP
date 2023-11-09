<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_login
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

// Include the login functions only once
require_once 'helper.php';

$params->def('greeting', 1);

$type             = \ModRedshopLoginHelper::getType();
$return           = \ModRedshopLoginHelper::getReturnUrl($params, $type);
$twofactormethods = JAuthenticationHelper::getTwoFactorMethods();
$user             = Factory::getApplication()->getIdentity();
$thirdPartyLogin  = Redshop\Helper\Login::getThirdPartyLogin();
$layout           = $params->get('layout', 'default');

// Logged users must load the logout sub layout
if (!$user->guest) {
    $layout .= '_logout';
}

require JModuleHelper::getLayoutPath('mod_redshop_login', $layout);
