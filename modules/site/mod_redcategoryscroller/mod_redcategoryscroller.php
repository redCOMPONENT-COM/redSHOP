<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_main_categoryscroller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

global $my, $mosConfig_absolute_path;
JLoader::import('redshop.library');

JLoader::import('helper', __DIR__);

$module_id = "mod_" . $module->id;

// Start of category Scroller Script
$scroller = new RedCategoryScroller($params, $module->id);

/**
 * Load category
 **/
$rows = $scroller->getRedCategorySKU($scroller->NumberOfCategory, $scroller->ScrollSortMethod, $scroller->category_id, $scroller->featuredCategory);

/**
 * Display category Scroller
 **/
$scroller->displayRedScroller($rows);
