<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

global $jscookType, $jscookMenuStyle, $jscookTreeStyle;

require_once $absoluteModulePath . '/helpers/jscook.php';

$jscookMenuStyle = $params->get('jscookMenu_style', 'ThemeOffice');
$jscookTreeStyle = $params->get('jscookTree_style', 'ThemeXP');
$jscookType = $params->get('jscook_type', 'menu');

$Itemid = JRequest::getInt('Itemid');
$TreeId = JRequest::getInt('TreeId');

$document = JFactory::getDocument();
$document->addScript($liveModulePath . '/tmpl/JSCook/JSCookMenu.js');

$document->addScriptDeclaration('var ctThemeXPBase = "' . $liveModulePath . '/tmpl/ThemeXP/";');

if ($jscookType == "tree")
{
	switch ($jscookTreeStyle)
	{
		case "ThemeXP":
				$jscookTree = "ctThemeXP1";
			break;
		case "ThemeNavy":
				$jscookTree = "ctThemeNavy";
			break;
	}

	$document->addScript($liveModulePath . '/tmpl/JSCook/JSCookTree.js');

	$document->addScriptDeclaration(
		RedshopLayoutHelper::render(
			$jscookTreeStyle . '.theme',
			array(
				'ctThemeXPBase' => $liveModulePath . '/tmpl/' . $jscookTreeStyle . '/'
			),
			'modules/mod_redshop_categories/'
		)
	);

	$document->addStyleSheet($liveModulePath . '/tmpl/' . $jscookTreeStyle . '/theme.css');
}
else
{
	$document->addScript($liveModulePath . '/tmpl/JSCook/JSCookMenu.js');

	$document->addScriptDeclaration(
		RedshopLayoutHelper::render(
			'JSCook.theme',
			array(
				'cmThemeOfficeBase' => $liveModulePath . '/tmpl/ThemeOffice/'
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
