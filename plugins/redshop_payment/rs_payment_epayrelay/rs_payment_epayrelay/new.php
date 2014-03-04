<?php
$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../configuration.php");
	require_once $my_path . "/../../../configuration.php";
}
else
{
	die ("Joomla Configuration File not found!");
}

$absolute_path = realpath($absolute_path);

define ('_JEXEC', 1);
define ('JPATH_BASE', $absolute_path);
define ('DS', DIRECTORY_SEPARATOR);
define ('JPATH_COMPONENT_ADMINISTRATOR', JPATH_BASE . '/administrator/components/com_redshop');
define ('JPATH_COMPONENT', JPATH_BASE . '/components/com_redshop');

// Load the framework

require_once $absolute_path . '/includes/defines.php';
require_once $absolute_path . '/includes/framework.php';

// create the mainframe object
$app = JFactory::getApplication();

// Initialize the framework
$app->initialise();

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
require_once JPATH_COMPONENT . '/helpers/product.php';
include_once JPATH_COMPONENT . '/helpers/helper.php';
include_once JPATH_COMPONENT . '/helpers/cart.php';


$db = JFactory::getDbo();
$url = JURI::base();

//$Itemid = $redhelper->getCheckoutItemid();
$option = JRequest::getVar('option');

$post = JRequest::get('post');

JPluginHelper::importPlugin('redshop_payment');
$dispatcher = JDispatcher::getInstance();
$results = $dispatcher->trigger('onPrePayment', array($post['payment_plugin'], $post));

?>

