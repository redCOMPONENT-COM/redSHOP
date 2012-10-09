<?php
defined('_JEXEC') or die ('restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'order.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');
include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'cart.php');

$db  = JFactory::getDBO();
$url = JURI::base();

//$Itemid = $redhelper->getCheckoutItemid();
$option = JRequest::getVar('option');

$post = JRequest::get('post');

JPluginHelper::importPlugin('redshop_payment');
$dispatcher = JDispatcher::getInstance();
$results    = $dispatcher->trigger('onPrePayment', array($post['payment_plugin'], $post));

?>
