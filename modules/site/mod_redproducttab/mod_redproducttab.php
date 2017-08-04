<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_producttab
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$newprd  = trim($params->get('show_newprd', 1));
$ltsprd  = trim($params->get('show_ltsprd', 1));
$soldprd = trim($params->get('show_soldprd', 1));
$splprd  = trim($params->get('show_splprd', 1));

$image          = trim($params->get('image', 0));
$show_price     = trim($params->get('show_price', 0));
$show_readmore  = trim($params->get('show_readmore', 1));
$show_addtocart = trim($params->get('show_addtocart', 1));
$show_desc      = trim($params->get('show_desc', 1));
$thumbwidth     = trim($params->get('thumbwidth', 100));
$thumbheight    = trim($params->get('thumbheight', 100));
$layout         = $params->get('layout', 'default');
$productPerRow  = $params->get('number_of_row');

JFactory::getDocument()->addStyleSheet("modules/mod_redproducttab/css/style.css");

JLoader::import('redshop.library');

require_once __DIR__ . '/helper.php';

include JModuleHelper::getLayoutPath('mod_redproducttab', $layout);
