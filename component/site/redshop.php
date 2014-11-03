<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  redSHOP
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Load redSHOP Library
JLoader::import('redshop.library');

JLoader::import('joomla.html.parameter');

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::load('RedshopHelperAdminConfiguration');
JLoader::load('RedshopHelperAdminTemplate');
JLoader::load('RedshopHelperAdminStockroom');
JLoader::load('RedshopHelperAdminEconomic');
JLoader::load('RedshopHelperAdminImages');

$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

JLoader::import('joomla.html.pagination');

JLoader::load('RedshopHelperCron');
JLoader::load('RedshopHelperStatistic');
JLoader::load('RedshopHelperPagination');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperCurrency');
JLoader::load('RedshopHelperRedshop.js');

// Helper object
$helper = new redhelper;

// Include redCRM if required
$helper->isredCRM();

$print = $app->input->getCmd('print', '');

// Adding Redshop CSS
$doc = JFactory::getDocument();

// Use diffrent CSS for print layout
if (!$print)
{
	JHtml::stylesheet('com_redshop/redshop.css', array(), true);
}
else
{
	JHtml::stylesheet('com_redshop/print.css', array(), true);
}

JHtml::stylesheet('com_redshop/style.css', array(), true);

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

// Don't create div for AJAX call and GA code.
if ('component' !== $app->input->getCmd('tmpl') && 'html' == $format)
{
	echo "<div id='redshopcomponent' class='redshop'>";

	if ($format != 'final' && $layout != 'receipt')
	{
		/*
		 * get redSHOP Google Analytics Plugin is Enable?
		 * If it is Disable than load Google Analytics From redSHOP
		 */
		$isredGoogleAnalytics = JPluginHelper::isEnabled('system', 'redgoogleanalytics');

		if (!$isredGoogleAnalytics && GOOGLE_ANA_TRACKER_KEY != "")
		{
			JLoader::load('RedshopHelperGoogle_analytics');

			$ga = new GoogleAnalytics;
			$ga->placeTrans();
		}
	}
}

if (PORTAL_SHOP == 1)
{
	if ($vName == 'product' && $productid > 0 && $user->id > 0)
	{
		$checkcid = $helper->getShopperGroupProductCategory($productid);

		if ($checkcid == true)
		{
			$vName = 'login';
			JRequest::setVar('view', 'login');
			JRequest::setVar('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}
	elseif ($vName == 'category' && $categoryid > 0 && $user->id > 0)
	{
		$checkcid = $helper->getShopperGroupCategory($categoryid);

		if ($checkcid == "")
		{
			$vName = 'login';
			JRequest::setVar('view', 'login');
			JRequest::setVar('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}
}
else
{
	if ($vName == 'product' && $productid > 0 && $portal == 1)
	{
		$checkcid = $helper->getShopperGroupProductCategory($productid);

		if ($checkcid == true)
		{
			$vName = 'login';
			JRequest::setVar('view', 'login');
			JRequest::setVar('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}

	if ($vName == 'category' && $categoryid > 0 && $portal == 1)
	{
		$checkcid = $helper->getShopperGroupCategory($categoryid);

		if ($checkcid == "")
		{
			$vName = 'login';
			JRequest::setVar('view', 'login');
			JRequest::setVar('layout', 'portal');
			$app->enqueuemessage(JText::_('COM_REDSHOP_AUTHENTICATIONFAIL'));
		}
	}

	if ($vName == 'redshop')
	{
		$vName = 'category';
		JRequest::setVar('view', 'category');
	}
	else
	{
		JRequest::setVar('view', $vName);
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
	JRequest::setVar('task', $vName . '.' . $command);
}

// Perform the Request task
$controller = JControllerLegacy::getInstance('Redshop');

if (version_compare(JVERSION, '3.0', '<'))
{
	$task = JRequest::getCmd('task');
}
else
{
	$task = $app->input->get('task', '');
}

$controller->execute($task);

// End component DIV here
echo "</div>";

$controller->redirect();
