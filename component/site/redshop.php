<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Load redSHOP Library
JLoader::import('redshop.library');
JLoader::import('joomla.html.parameter');
JLoader::import('joomla.html.pagination');

RedshopHelperCron::init();
statistic::getInstance()->track();

// Helper object
$helper = redhelper::getInstance();

// Set the default view name and format from the Request.
$vName      = $app->input->getCmd('view', 'category');
$task       = $app->input->getCmd('task', '');
$format     = $app->input->getWord('format', 'html');
$layout     = $app->input->getWord('layout', '');
$params     = $app->getParams('com_redshop');
$categoryid = $app->input->getInt('cid', $params->get('categoryid'));
$productid  = $app->input->getInt('pid', 0);
$sgportal   = $helper->getShopperGroupPortal();
$user       = JFactory::getUser();
$portal     = 0;

// Add product in cart from db
$helper->dbtocart();

if (count($sgportal) > 0)
{
	$portal = $sgportal->shopper_group_portal;
}

if (Redshop::getConfig()->get('PORTAL_SHOP') == 1)
{
	if ($vName == 'product' && $productid > 0)
	{
		$checkProductPermission = $helper->checkPortalProductPermission($productid);

		if (!$checkProductPermission)
		{
			$vName = 'login';
			$app->input->set('view', 'login');
			$app->input->set('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}
	elseif ($vName == 'category' && $categoryid > 0)
	{
		$checkCategoryPermission = $helper->checkPortalCategoryPermission($categoryid);

		if (!$checkCategoryPermission)
		{
			$vName = 'login';
			$app->input->set('view', 'login');
			$app->input->set('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}
}
else
{
	if ($vName == 'product' && $productid > 0 && $portal == 1)
	{
		$checkProductPermission = $helper->checkPortalProductPermission($productid);

		if (!$checkProductPermission)
		{
			$vName = 'login';
			$app->input->set('view', 'login');
			$app->input->set('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}

	if ($vName == 'category' && $categoryid > 0 && $portal == 1)
	{
		$checkCategoryPermission = $helper->checkPortalCategoryPermission($categoryid);

		if (!$checkCategoryPermission)
		{
			$vName = 'login';
			$app->input->set('view', 'login');
			$app->input->set('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}

	if ($vName == 'redshop')
	{
		$vName = 'category';
		$app->input->set('view', 'category');
	}
	else
	{
		$app->input->set('view', $vName);
	}
}

// Don't create div for AJAX call and GA code.
if ('component' !== $app->input->getCmd('tmpl') && 'html' == $format)
{
	// Container CSS class definition
	if (version_compare(JVERSION, '3.0', '<'))
	{
		$redSHOPCSSContainerClass = ' isJ25';
	}
	else
	{
		$redSHOPCSSContainerClass = ' isJ30';
	}

	echo '<div id="redshopcomponent" class="redshop redSHOPSiteView' . ucfirst($vName) . $redSHOPCSSContainerClass . '">';

	if ($layout != 'receipt')
	{
		/*
		 * get redSHOP Google Analytics Plugin is Enable?
		 * If it is Disable than load Google Analytics From redSHOP
		 */
		$isRedGoogleAnalytics = JPluginHelper::isEnabled('system', 'redgoogleanalytics');

		if (!$isRedGoogleAnalytics && Redshop::getConfig()->get('GOOGLE_ANA_TRACKER_KEY') != "")
		{
			$ga = new RedshopHelperGoogleanalytics;
			$ga->placeTrans();
		}
	}
}

// Check for array format.
$filter = JFilterInput::getInstance();
$task   = $app->input->getCmd('task', 'display');

if (is_array($task))
{
	$command = $filter->clean(array_pop(array_keys($task)), 'cmd');
}
else
{
	$command = $filter->clean($task, 'cmd');
}

// Check for a not controller.task command.
if (strpos($command, '.') === false)
{
	$app->input->set('task', $vName . '.' . $command);
}

// Perform the Request task
$controller = JControllerLegacy::getInstance('Redshop');

if (version_compare(JVERSION, '3.0', '<'))
{
	$task = JRequest::getCmd('task');
}
else
{
	$task = $app->input->getCmd('task', '');
}

$controller->execute($task);

// End component DIV here
echo "</div>";

echo JLayoutHelper::render('assets');

$controller->redirect();
