<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_login
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$params->def('greeting', 1);

$type    = modRedshopLoginHelper::getType();
$return  = modRedshopLoginHelper::getReturnURL($params, $type);
$rItemid = trim($params->get('registrationitemid', ''));
$user    = JFactory::getUser();

require JModuleHelper::getLayoutPath('mod_redshop_login');
