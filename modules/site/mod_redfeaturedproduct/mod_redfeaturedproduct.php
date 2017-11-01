<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTMLBehavior::modal();
JLoader::import('redshop.library');

$thumbWidth = $params->get('thumbwidth', "100");
$thumbHeight = $params->get('thumbheight', "100");
$scrollerWidth = $params->get('scrollerwidth', "700");

JLoader::import('helper', __DIR__);

$list = ModRedFeaturedProductHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_redfeaturedproduct', $params->get('layout', 'default'));
