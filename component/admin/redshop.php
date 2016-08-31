<?php
/**
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package    RedSHOP.Backend
 *
 * @copyright  Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Load redSHOP Library
JLoader::import('redshop.library');

$config = Redshop::getConfig();

if (!$config->isExists())
{
	error_reporting(0);
	$controller = 'redshop';
	JRequest::setVar('view', 'redshop');
	JRequest::setVar('layout', 'noconfig');
}

$redhelper = RedshopSiteHelper::getInstance();
$redhelper->removeShippingRate();
$json_var = JRequest::getVar('json');

$view = JRequest::getVar('view');
$user = JFactory::getUser();
$usertype = array_keys($user->groups);
$user->usertype = $usertype[0];
$user->gid = $user->groups[$user->usertype];

if (ENABLE_BACKENDACCESS && $user->gid != 8 && !$json_var)
{
	$access_rslt = new Redaccesslevel;
	$access_rslt->checkaccessofuser($user->gid);
}

if (ENABLE_BACKENDACCESS)
{
	if ($user->gid != 8 && $view != '' && !$json_var)
	{
		$task = JRequest::getVar('task');
		$redaccesslevel = new Redaccesslevel;
		$redaccesslevel->checkgroup_access($view, $task, $user->gid);
	}
}

$isWizard = JRequest::getInt('wizard', 0);
$step     = JRequest::getVar('step', '');

// Initialize wizard
if ($isWizard || $step != '')
{
	if (ENABLE_BACKENDACCESS)
	{
		if ($user->gid != 8)
		{
			$redaccesslevel = new Redaccesslevel;
			$redaccesslevel->checkgroup_access('wizard', '', $user->gid);
		}
	}

	JRequest::setVar('view', 'wizard');

	require_once JPATH_COMPONENT . '/helpers/wizard/wizard.php';
	$redSHOPWizard = new redSHOPWizard;
	$redSHOPWizard->initialize();

	return true;
}

$view = $app->input->get('view', 'redshop');

$user        = JFactory::getUser();
$task        = $app->input->get('task', '');
$layout      = JRequest::getVar('layout', '');
$showbuttons = JRequest::getVar('showbuttons', '0');
$showall     = JRequest::getVar('showall', '0');

// Check for array format.
$filter = JFilterInput::getInstance();

if (is_array($task))
{
	$command = $filter->clean(array_pop(array_keys($task)), 'cmd');
}
else
{
	$command = $filter->clean($task, 'cmd');
}

// Check for a not controller.task command.
if ($command != '' && strpos($command, '.') === false)
{
	JRequest::setVar('task', $view . '.' . $command);
	$task = $command;
}
elseif ($command != '' && strpos($command, '.') !== false)
{
	$commands = explode('.', $command);
	$view = $commands[0];
	$task = $commands[1];
}

// Set the controller page
if (!file_exists(JPATH_COMPONENT . '/controllers/' . $view . '.php'))
{
	$view = 'redshop';
	JRequest::setVar('view', $view);
}

RedshopHelperConfig::script('SITE_URL', JURI::root());
RedshopHelperConfig::script('REDCURRENCY_SYMBOL', Redshop::getConfig()->get('REDCURRENCY_SYMBOL'));
RedshopHelperConfig::script('PRICE_SEPERATOR', Redshop::getConfig()->get('PRICE_SEPERATOR'));
RedshopHelperConfig::script('CURRENCY_SYMBOL_POSITION', Redshop::getConfig()->get('CURRENCY_SYMBOL_POSITION'));
RedshopHelperConfig::script('PRICE_DECIMAL', Redshop::getConfig()->get('PRICE_DECIMAL'));
RedshopHelperConfig::script('THOUSAND_SEPERATOR', Redshop::getConfig()->get('THOUSAND_SEPERATOR'));
RedshopHelperConfig::script('VAT_RATE_AFTER_DISCOUNT', Redshop::getConfig()->get('VAT_RATE_AFTER_DISCOUNT'));
JText::script('COM_REDSHOP_IS_REQUIRED');

// Execute the task.
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
$controller->redirect();
