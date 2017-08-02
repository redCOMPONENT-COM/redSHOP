<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$list            = ModRedManufacturerHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$ImageWidth      = $params->get('ImageWidth', '200');
$ImageHeight     = $params->get('ImageHeight', '200');
$scrollWidth     = $params->get('ScrollWidth', '500');
$PageLink        = $params->get('PageLink', 'detail');
$showImage       = $params->get('show_image', '1');
$showProductName = $params->get('show_product_name', '0');
$scrollDelay     = $params->get('ScrollDelay', 'slow');
$showLinkOnProductName = $params->get('show_link_on_product_name', '0');
$preText         = $params->get('pretext', "");
$scrollBehavior  = $params->get('ScrollBehavior', '1');
$scrollAuto      = $params->get('ScrollAuto', '1');
$controlNav      = $params->get('controlNav', 0);
$directionNav      = $params->get('directionNav', 0);

if ($scrollDelay == 'slow')
{
	$scrollDelay = '600';
}
else
{
	$scrollDelay = '200';
}

array_map(
	function($list)
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$menuItem = $menu->getItems('link', 'index.php?option=com_redshop&view=manufacturers&layout=products', false);
		$list->item_id = $app->input->getInt('Itemid');

		foreach ($menuItem as $k => $value)
		{
			$menuParams = $value->params;

			if ($menuParams->get('manufacturerid') == $list->manufacturer_id)
			{
				$list->item_id = $value->id;
				break;
			}
		}

		return $list;
	}, $list
);

require JModuleHelper::getLayoutPath('mod_redmanufacturer', $params->get('layout', 'default'));
