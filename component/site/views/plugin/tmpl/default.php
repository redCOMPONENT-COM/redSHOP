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

// Event
$task = JRequest::getCmd('task');

// Group
$type   = JRequest::getCmd('type');

$jinput = JFactory::getApplication()->input;
$post   = $jinput->getArray($_REQUEST);

JPluginHelper::importPlugin($type);

$paymentResponses = $dispatcher->trigger($task, array(&$post));
