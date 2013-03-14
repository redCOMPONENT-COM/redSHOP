<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmasscart
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

// Include the syndicate functions only once
$module_base = JURI::base() . 'modules/mod_redmasscart/';
$document    =& JFactory::getDocument();
$document->addStyleSheet($module_base . 'css/style.css');

require(JModuleHelper::getLayoutPath('mod_redmasscart'));
