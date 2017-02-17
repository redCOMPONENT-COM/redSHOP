<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_currencies
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JLoader::import('helper', __DIR__);

$session = JFactory::getSession();
$textBefore = $params->get('text_before', '');
$currencies = ModRedshopCurrenciesHelper::getList($params);
require JModuleHelper::getLayoutPath('mod_redshop_currencies', $params->get('layout', 'default'));
