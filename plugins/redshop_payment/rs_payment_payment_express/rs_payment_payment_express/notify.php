<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
define('_JEXEC', 1);

define('JPATH_BASE', dirname(__FILE__));

define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

JDEBUG ? $_PROFILER->mark('afterLoad') : null;

/**
 * CREATE THE APPLICATION
 *
 * NOTE :
 */
$app = JFactory::getApplication();

/**
 * INITIALISE THE APPLICATION
 *
 * NOTE :
 */
// Set the language
$app->initialise();
$request = JRequest::get('request');

$txid      = $request['tx'];
$tx_status = $request['st'];
$order_id  = $request['oid'];
$Itemid    = $request["Itemid"];

if (isset($txid) && $tx_status == 'Completed')
{
	$db = JFactory::getDbo();

	if (orderPaymentNotYetUpdated($db, $order_id, $txid))
	{
		$query = "UPDATE `#__redshop_orders` set order_status = 'C' where order_id = " . $order_id;
		$db->setQuery($query);
		$db->Query();
	}
}

function orderPaymentNotYetUpdated($dbConn, $order_id, $tid)
{
	$db    = JFactory::getDbo();
	$res   = false;
	$query = "SELECT COUNT(*) `qty` FROM `#__redshop_order_payment` WHERE `order_id` = '" . $db->getEscaped($order_id) . "' and order_payment_trans_id = '" . $db->getEscaped($txid) . "'";
	$db->setQuery($query);
	$order_payment = $db->loadResult();

	if ($order_payment == 0)
	{
		$res = true;
	}

	return $res;
}
