<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmasscart
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
$module_base = JURI::base() . 'modules/mod_redmasscart/';

require JModuleHelper::getLayoutPath('mod_redmasscart', $params->get('layout', 'default'));
