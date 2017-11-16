<?php
/**
 * @package     Redshopb.Site
 * @subpackage  mod_redshopb_megamenu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

// Load module language
$lang = JFactory::getLanguage();
$lang->load('mod_redshop_megamenu', __DIR__);

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$categories = ModRedshopMegaMenuHelper::getCategories($params);

ModRedshopMegaMenuHelper::sortCategories($categories, $params->get('ordering', 'name'), $params->get('destination', 'asc'));

if (count($categories))
{
	$class_sfx	= htmlspecialchars($params->get('class_sfx'));
	JHtml::stylesheet('mod_redshop_megamenu/mega.css', false, true);
	JHtml::script('mod_redshop_megamenu/mega.js', false, true);
	require JModuleHelper::getLayoutPath('mod_redshop_megamenu', $params->get('layout', 'default'));
}
