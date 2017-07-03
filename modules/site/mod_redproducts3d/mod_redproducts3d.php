<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redproducts3d
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('helper', __DIR__);

$count                  = trim($params->get('count', 2));
$stageWidth             = trim($params->get('stageWidth', 600));
$stageHeight            = trim($params->get('stageHeight', 400));
$thumbwidth             = trim($params->get('thumbwidth', 100));
$thumbheight            = trim($params->get('thumbheight', 100));
$radius                 = trim($params->get('radius', 230));
$focalBlur              = trim($params->get('focalBlur', 5));
$elevation              = trim($params->get('elevation', -50));
$enableImageReflection  = trim($params->get('enableImageReflection', 'yes'));
$enableimageStroke      = trim($params->get('enableimageStroke', 'yes'));
$enableMouseOverToolTip = trim($params->get('enableMouseOverToolTip', 'yes'));
$enableMouseOverEffects = trim($params->get('enableMouseOverEffects', 'yes'));

$rows = ModRedProducts3d::getList($params);

require JModuleHelper::getLayoutPath('mod_redproducts3d');
