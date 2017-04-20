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
$jscookType = $params->get('jscook_type', 'tree');

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

	JHtml::script('mod_redshop_categories/JSCookMenu.js', false, true);
	JHtml::script('mod_redshop_categories/JSCookTree.js', false, true);
	JHtml::script('mod_redshop_categories/' . strtolower($jscookStyle) . '.js', false, true);

	$document->addScriptDeclaration(
		RedshopLayoutHelper::render(
			$jscookStyle . '.theme',
			array(
				'ct' . $jscookStyle . 'Base' => JURI::root() . '/media/mod_redshop_categories/' . $jscookStyle . '/'
			),
			'modules/mod_redshop_categories/'
		)
	);

	JHtml::stylesheet('mod_redshop_categories/' . strtolower($jscookStyle) . '.css', false, true);
}
else
{
	JHtml::script('mod_redshop_categories/JSCookMenu.js', false, true);

	$document->addScriptDeclaration(
		RedshopLayoutHelper::render(
			'JSCook.theme',
			array(
				'ct' . $jscookStyle . 'Base' => JURI::root() . '/media/mod_redshop_categories/' . $jscookStyle . '/'
			),
			'modules/mod_redshop_categories/'
		)
	);

	JHtml::stylesheet('mod_redshop_categories/jscook.css', false, true);
}

// Create a unique tree identifier, in case multiple trees are used
// (max one per module)
$varname = "JSCook_" . uniqid($jscookType . "_");

require JModuleHelper::getLayoutPath('mod_redshop_categories', $params->get('layout', 'jscook'));
