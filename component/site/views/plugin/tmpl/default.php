<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$dispatcher = RedshopHelperUtility::getDispatcher();
$app        = JFactory::getApplication();

// Event
$task = $app->input->getCmd('task');

// Group
$type = $app->input->getCmd('type');

$post   = $app->input->getArray();

JPluginHelper::importPlugin($type);

$paymentResponses = $dispatcher->trigger($task, array(&$post));
