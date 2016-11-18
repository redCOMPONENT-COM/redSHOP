<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once $absoluteModulePath . '/helpers/jscook.php';

$jscookStyle = $params->get('jscook_style', 'ThemeOffice');
$jscookType = $params->get('jscook_type', 'menu');

$Itemid = JRequest::getInt('Itemid');
$TreeId = JRequest::getInt('TreeId');
$jscookTree = 'cmThemeOffice';
$iconName = 'categories.png';

$document = JFactory::getDocument();

if ($jscookType == "tree")
{
	switch ($jscookStyle)
	{
		case "ThemeXP":
				$jscookTree = "ctThemeXP1";
			break;
		case "ThemeNavy":
				$jscookTree = "ctThemeNavy";
				$iconName = "open.gif";
			break;
	}

	$document->addScript($liveModulePath . '/tmpl/JSCook/JSCookMenu.js');
	$document->addScript($liveModulePath . '/tmpl/JSCook/JSCookTree.js');
	$document->addScript($liveModulePath . '/tmpl/' . $jscookStyle . '/theme.js');

	$document->addScriptDeclaration(
		RedshopLayoutHelper::render(
			$jscookStyle . '.theme',
			array(
				'ct' . $jscookStyle . 'Base' => $liveModulePath . '/tmpl/' . $jscookStyle . '/'
			),
			'modules/mod_redshop_categories/'
		)
	);

	$document->addStyleSheet($liveModulePath . '/tmpl/' . $jscookStyle . '/theme.css');
}
else
{
	$document->addScript($liveModulePath . '/tmpl/JSCook/JSCookMenu.js');

	$document->addScriptDeclaration(
		RedshopLayoutHelper::render(
			'JSCook.theme',
			array(
				'ct' . $jscookStyle . 'Base' => $liveModulePath . '/tmpl/ThemeOffice/'
			),
			'modules/mod_redshop_categories/'
		)
	);

	$document->addStyleSheet($liveModulePath . '/tmpl/JSCook/theme.css');
}

// Create a unique tree identifier, in case multiple trees are used
// (max one per module)
$varname = "JSCook_" . uniqid($jscookType . "_");

require JModuleHelper::getLayoutPath('mod_redshop_categories', $params->get('layout', 'jscook'));
