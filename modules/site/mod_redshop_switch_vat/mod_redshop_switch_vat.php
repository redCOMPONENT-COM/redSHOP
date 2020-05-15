<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_switch_vat
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$layout = $params->get('layout', 'default');

require_once 'helper.php';

$doc = \JFactory::getDocument();
$doc->addScript("modules/mod_redshop_switch_vat/js/default.js");

require JModuleHelper::getLayoutPath('mod_redshop_switch_vat', $layout);