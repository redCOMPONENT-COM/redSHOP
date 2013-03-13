<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');


$dispatcher =& JDispatcher::getInstance();
$task = JRequest::getVar('task'); //event
$type = JRequest::getVar('type'); //group
$post = JRequest::get('request');

//echo $task .' '. $type;

JPluginHelper::importPlugin($type);
$paymentResponses = $dispatcher->trigger($task, array(&$post)); //,array( $type));

