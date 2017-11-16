<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redshop_producttab
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);
JHTML::script('com_redshop/redbox.js', false, true);
$module_id = "mod_" . $module->id;

$producthelper = productHelper::getInstance();
$redhelper     = redhelper::getInstance();
$extraField    = extraField::getInstance();

// Create Pane
echo JHtml::_('tabs.start', 'pane', array('startOffset' => 0));

// Create 1st Tab
if ($newprd)
{
	$rows = ModRedProductTabHelper::getList($params, 'newest', $module->id);

	if (is_array($rows) && count($rows) > 0)
	{
		echo JHtml::_('tabs.panel', JText::_('MOD_REDPRODUCTTAB_NEWEST_PRODUCTS'), 'tab1');

		include JModuleHelper::getLayoutPath('mod_redproducttab', $layout . '_tab');
	}
}

// Create 2nd Tab
if ($ltsprd)
{
	$rows = ModRedProductTabHelper::getList($params, 'latest', $module->id);

	if (is_array($rows) && count($rows) > 0)
	{
		echo JHtml::_('tabs.panel', JText::_('MOD_REDPRODUCTTAB_LATEST_PRODUCTS'), 'tab2');

		include JModuleHelper::getLayoutPath('mod_redproducttab', $layout . '_tab');
	}
}

// Create 3nd Tab
if ($soldprd)
{
	$rows = ModRedProductTabHelper::getList($params, 'most_sold', $module->id);

	if (is_array($rows) && count($rows) > 0)
	{
		echo JHtml::_('tabs.panel', JText::_('MOD_REDPRODUCTTAB_MOST_SOLD_PRODUCTS'), 'tab3');

		include JModuleHelper::getLayoutPath('mod_redproducttab', $layout . '_tab');
	}
}

// Create 4nd Tab
if ($splprd)
{
	$rows = ModRedProductTabHelper::getList($params, 'special', $module->id);

	if (is_array($rows) && count($rows) > 0)
	{
		echo JHtml::_('tabs.panel', JText::_('MOD_REDPRODUCTTAB_SPECIAL_PRODUCTS'), 'tab4');

		include JModuleHelper::getLayoutPath('mod_redproducttab', $layout . '_tab');
	}
}

echo JHtml::_('tabs.end');
