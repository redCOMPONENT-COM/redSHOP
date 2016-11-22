<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_productcompare
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');


$isCompare = (int) Redshop::getConfig()->get('COMPARE_PRODUCTS', 0);
$uri = JURI::getInstance();
$url = $uri->root();
$productHelper = productHelper::getInstance();
$redHelper = redhelper::getInstance();
$itemId = JRequest::getInt('Itemid');
$cid = JRequest::getInt('cid');


if ($isCompare === 1)
{
	require JModuleHelper::getLayoutPath('mod_redshop_productcompare', $params->get('layout', 'default'));
}
else
{
	require JModuleHelper::getLayoutPath('mod_redshop_productcompare', $params->get('layout', 'notfound'));
}
