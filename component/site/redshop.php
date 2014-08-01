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
JLoader::import('joomla.html.parameter');

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('configuration', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
JLoader::import('template', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
JLoader::import('stockroom', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
JLoader::import('economic', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
JLoader::import('images', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');

$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

JLoader::import('joomla.html.pagination');

require_once JPATH_COMPONENT . '/helpers/cron.php';
require_once JPATH_COMPONENT . '/helpers/statistic.php';
require_once JPATH_COMPONENT . '/helpers/pagination.php';
require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_COMPONENT . '/helpers/product.php';
require_once JPATH_COMPONENT . '/helpers/currency.php';
require_once JPATH_COMPONENT . '/helpers/redshop.js.php';

// Check for array format.
$filter = JFilterInput::getInstance();
$task   = $app->input->getCmd('task', 'display');
$vName  = $app->input->getCmd('view', false);

if (is_array($task))
{
	$command = $filter->clean(array_pop(array_keys($task)), 'cmd');
}
else
{
	$command = $filter->clean($task, 'cmd');
}

// Check for a not controller.task command.
if (strpos($command, '.') === false && $vName !== false)
{
	JRequest::setVar('task', $vName . '.' . $command);
}

// Perform the Request task
$controller = JControllerLegacy::getInstance('Redshop');
$controller->execute($app->input->getCmd('task'));

$controller->redirect();
