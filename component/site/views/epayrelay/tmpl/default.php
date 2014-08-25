<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperCart');

$db  = JFactory::getDbo();
$url = JURI::base();

$jinput = JFactory::getApplication()->input;
$post   = $jinput->getArray($_POST);

JPluginHelper::importPlugin('redshop_payment');
$dispatcher = JDispatcher::getInstance();
$results    = $dispatcher->trigger('onPrePayment', array($post['payment_plugin'], $post));
