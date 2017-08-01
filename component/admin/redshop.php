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
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Load redSHOP Library
JLoader::import('redshop.library');

$config = Redshop::getConfig();

// Don't redirect view if current view is "install"
if (!$config->isExists() && $app->input->getCmd('view') != 'install')
{
	$controller = 'redshop';
	JFactory::getApplication()->input->set('view', 'redshop');
	JFactory::getApplication()->input->set('layout', 'noconfig');
}

$redHelper = redhelper::getInstance();
RedshopShippingRate::removeShippingRate();
$json = JFactory::getApplication()->input->get('json');

$view = JFactory::getApplication()->input->getCmd('view', '');
$user = JFactory::getUser();
$userType = array_keys($user->groups);
$user->usertype = $userType[0];
$user->gid = $user->groups[$user->usertype];

if (!$user->authorise('core.manage', 'com_redshop') && !$json)
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');

	return false;
}

$isWizard = JFactory::getApplication()->input->getInt('wizard', 0);
$step     = JFactory::getApplication()->input->get('step', '');

// Initialize wizard
if ($isWizard || $step != '')
{
	if ($user->gid != 8 && !$user->authorise('core.manage', 'com_redshop'))
	{
		throw new Exception('COM_REDSHOP_DONT_HAVE_PERMISSION');
	}

	JFactory::getApplication()->input->set('view', 'wizard');

	require_once JPATH_COMPONENT . '/helpers/wizard/wizard.php';
	$redSHOPWizard = new redSHOPWizard;
	$redSHOPWizard->initialize();

	return true;
}

$view = $app->input->get('view', 'redshop');

$user        = JFactory::getUser();
$task        = JFactory::getApplication()->input->getCmd('task', '');
$layout      = JFactory::getApplication()->input->getCmd('layout', '');
$showButtons = JFactory::getApplication()->input->getInt('showbuttons', 0);
$showAll     = JFactory::getApplication()->input->getInt('showall', 0);

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
	JFactory::getApplication()->input->set('task', $view . '.' . $command);
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
	JFactory::getApplication()->input->set('view', $view);
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

$task = JFactory::getApplication()->input->getCmd('task', '');

$controller->execute($task);
$controller->redirect();
