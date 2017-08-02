<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products_slideshow
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

$bannerWidth     = intval($params->get('bannerWidth', 912));
$bannerHeight    = intval($params->get('bannerHeight', 700));
$imageWidth      = intval($params->get('imageWidth'));
$imageHeight     = intval($params->get('imageHeight'));
$backgroundColor = trim($params->get('backgroundColor', '#FFFFFF'));
$wmode           = trim($params->get('wmode', 'window'));
$id              = intval($params->get('category_id', 0));

// Include the helper functions only once
require_once __DIR__ . '/helper.php';

RedshopProductSlideshow::create_smart_xml_files($params, $module->id);

require JModuleHelper::getLayoutPath('mod_redshop_products_slideshow', $params->get('layout', 'default'));
