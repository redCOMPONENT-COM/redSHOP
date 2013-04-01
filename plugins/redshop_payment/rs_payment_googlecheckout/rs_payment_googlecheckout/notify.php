<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$my_path = dirname(__FILE__);

if (file_exists($my_path . "/../../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../../configuration.php");
	require_once $my_path . "/../../../configuration.php";
}
elseif (file_exists($my_path . "/../../configuration.php"))
{
	$absolute_path = dirname($my_path . "/../../configuration.php");
	require_once $my_path . "/../../configuration.php";
}
elseif (file_exists($my_path . "/configuration.php"))
{
	$absolute_path = dirname($my_path . "/configuration.php");
	require_once $my_path . "/configuration.php";
}
else
{
	die ("Joomla Configuration File not found!");
}

$absolute_path = realpath($absolute_path);

define ('_JEXEC', 1);
define ('JPATH_BASE', $absolute_path);
define ('DS', DIRECTORY_SEPARATOR);
define ('JPATH_COMPONENT_ADMINISTRATOR', JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop');
define ('JPATH_COMPONENT', JPATH_BASE . DS . 'components' . DS . 'com_redshop');

// Load the framework

require_once $absolute_path . DS . 'includes' . DS . 'defines.php';
require_once $absolute_path . DS . 'includes' . DS . 'framework.php';

// create the mainframe object
$app = JFactory::getApplication();

// Initialize the framework
$app->initialise();

// load system plugin group
JPluginHelper::importPlugin('system');

// trigger the onBeforeStart events
$app->triggerEvent('onBeforeStart');
$lang = JFactory::getLanguage();
$mosConfig_lang = $GLOBALS ['mosConfig_lang'] = strtolower($lang->getBackwardLang());
// Adjust the live site path

/*** END of Joomla config ***/

// redshop language file
JPlugin::loadLanguage('com_redshop');

require_once JPATH_BASE . DS . 'administrator' . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'order.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'redshop.cfg.php';
$objOrder = new order_functions;

$tid = $_REQUEST ["transact"];
$order_id = JFilterOutput::cleanText($_REQUEST ['shopping-cart_merchant-private-data']);

$google_order_id = $_REQUEST ['google-order-number'];

$orders_payment_status_id = "";
$log = "";

switch ($_REQUEST ['_type'])
{
	case 'new-order-notification' :

		$orders_status_id = 'P';
		$orders_payment_status_id = 'UNPAID';
		$log = JText::_('COM_REDSHOP_GC_ORDER_PLACED');
		break;
	case 'order-state-change-notification' :

		$google_orders_status = $_REQUEST ['new-financial-order-state'];
		$google_fulfillment_status = $_REQUEST ['new-fulfillment-order-state'];

		switch ($google_orders_status)
		{
			case 'CHARGEABLE' :
				$orders_status_id = 'S';
				$log = JText::_('COM_REDSHOP_GC_ORDER_CHARGED');
				break;
			case 'REVIEWING' :
				$orders_status_id = 'P';
				$orders_payment_status_id = 'UNPAID';
				$log = JText::_('COM_REDSHOP_GC_ORDER_REVIED');
				break;
			case 'CHARGING' :
				$orders_status_id = 'S';
				$log = JText::_('COM_REDSHOP_GC_ORDER_CHARGED');
				break;
			case 'CHARGED' :
				$orders_status_id = 'C';
				$orders_payment_status_id = 'PAID';
				$log = JText::_('COM_REDSHOP_GC_ORDER_CONFIRM');
				break;
			case 'PAYMENT_DECLINED' :
				$orders_status_id = 'RT';
				$log = JText::_('COM_REDSHOP_GC_ORDER_PAYMENT_DECLINE');
				break;
			case 'CANCELLED' :
				$orders_status_id = 'X';
				$log = JText::_('COM_REDSHOP_GC_ORDER_PAYMENT_CANCELLED');
				break;
			case 'CANCELLED_BY_GOOGLE' :
				$orders_status_id = 'X';
				$log = JText::_('COM_REDSHOP_GC_ORDER_PAYMENT_CANCELLED_BY_GOOGLE');
				break;
		}
		break;
}

// google giving redSHOP order id for the first time
// we need it back from transaction id

$db = JFactory::getDBO();

if (!isset ($order_id))
{
	$query = "SELECT order_id FROM #__redshop_order_payment WHERE order_payment_trans_id = '" . $google_order_id . "'";
	$db->SetQuery($query);
	$order_id = $db->loadResult();
}

// make status change array
$values = array();
$values['transaction_id'] = $google_order_id;
$values['order_id'] = $order_id;
$values['order_status_code'] = $orders_status_id;
$values['order_payment_status_code'] = $orders_payment_status_id;
$values['log'] = $log;
$values['msg'] = JText::_('COM_REDSHOP_ORDER_PLACED');

$maildata = "";

$maildata .= "REQUEST";
$maildata .= "\n";

foreach ($_REQUEST as $key => $val)
{
	$maildata .= $key . " => " . $val;
	$maildata .= "\n";
	$maildata .= "\n";
}

$maildata .= "\n";
$maildata .= "MY VALUE";
$maildata .= "\n";

foreach ($values as $key => $val)
{
	$maildata .= $key . " => " . $val;
	$maildata .= "\n";
	$maildata .= "\n";
}

//mail("gunjan@redweb.dk","googlecheckout",$maildata);

// change order status
$objOrder->changeorderstatus($values);

?>