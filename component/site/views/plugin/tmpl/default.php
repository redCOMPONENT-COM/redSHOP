<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Event
$task = $app->input->getCmd('task');

// Group
$type = $app->input->getCmd('type');

$post = $app->input->getArray();

JPluginHelper::importPlugin($type);

$paymentResponses = JFactory::getApplication()->triggerEvent($task, array(&$post));
