<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
require_once JPATH_COMPONENT . '/helpers/product.php';
include_once JPATH_COMPONENT . '/helpers/helper.php';
include_once JPATH_COMPONENT . '/helpers/cart.php';

$db  = JFactory::getDBO();
$url = JURI::base();

$option = JRequest::getVar('option');

$post = JRequest::get('post');

JPluginHelper::importPlugin('redshop_payment');
$dispatcher = JDispatcher::getInstance();
$results    = $dispatcher->trigger('onPrePayment', array($post['payment_plugin'], $post));
