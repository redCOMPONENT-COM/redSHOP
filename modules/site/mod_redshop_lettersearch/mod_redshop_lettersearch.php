<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_lettersearch
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Get module path
$mod_dir = dirname(__FILE__);

define('LETTERSEARCH_MODULE_PATH', $mod_dir);

require_once LETTERSEARCH_MODULE_PATH . '/helper.php';
$lettersearchHelper = new modlettersearchHelper;

$selected_field = trim($params->get('list_of_fields', ''));
$db             = JFactory::getDbo();
$getcharacters  = $lettersearchHelper->getDefaultModulecharacters($selected_field);

// Get show number of products
$number_of_items   = trim($params->get('count_products', 5));

// Get show number of Columns
$number_of_columns = trim($params->get('number_of_columns', 6));

require JModuleHelper::getLayoutPath('mod_redshop_lettersearch');
