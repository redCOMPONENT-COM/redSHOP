<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


$dispatcher =& JDispatcher::getInstance();

// Event
$task = JRequest::getVar('task');

// Group
$type = JRequest::getVar('type');
$post = JRequest::get('request');


JPluginHelper::importPlugin($type);

$paymentResponses = $dispatcher->trigger($task, array(&$post));
