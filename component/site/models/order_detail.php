<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class order_detailModelorder_detail extends JModel
{
	var $_id = null;
	var $_data = null;
	var $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}

	public function checkauthorization($oid, $encr)
	{
		$query = "SELECT count(order_id) FROM  " . $this->_table_prefix . "orders WHERE order_id = '" . $oid . "' AND encr_key like '" . $encr . "' ";
		$this->_db->setQuery($query);
		$order_detail = $this->_db->loadResult();

		return $order_detail;
	}

	/*
	 * update analytics status
	 */
	public function UpdateAnalytics_status($oid)
	{

		$query = "UPDATE  " . $this->_table_prefix . "orders SET `analytics_status` = 1 WHERE order_id = '" . $oid . "'";
		$this->_db->setQuery($query);
		if (!$this->_db->Query())
		{
			return false;
		}

		return true;
	}

	/*
	 * getBilling Addresses
	 */
	public function billingaddresses()
	{
		global $mainframe;
		$order_functions = new order_functions();
		$user            = & JFactory::getUser();
		$session         =& JFactory::getSession();


		$auth = $session->get('auth');
		$list = array();
		if ($user->id)
		{
			$list = $order_functions->getBillingAddress($user->id);
		}
		else if ($auth['users_info_id'])
		{
			$uid  = -$auth['users_info_id'];
			$list = $order_functions->getBillingAddress($uid);
		}

		return $list;
	}

	/*
	 * get category name from Product Id
	 */
	public function getCategoryNameByProductId($pid)
	{
		$db    = & JFactory::getDBO();
		$query = "SELECT c.category_name FROM #__redshop_product_category_xref AS pcx "
			. "LEFT JOIN #__redshop_category AS c ON c.category_id=pcx.category_id "
			. "WHERE pcx.product_id=" . $pid . " AND c.category_name IS NOT NULL ORDER BY c.category_id ASC LIMIT 0,1";
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function resetcart()
	{
		$session = & JFactory::getSession();
		$session->set('cart', null);
		$session->set('ccdata', null);
		$session->set('issplit', null);
		$session->set('userfiled', null);
		unset ($_SESSION ['ccdata']);
	}

	public function update_ccdata($order_id, $payment_transaction_id)
	{
		$db = JFactory::getDBO();

		$session =& JFactory::getSession();
		$ccdata  = $session->get('ccdata');

		$order_payment_code     = $ccdata['creditcard_code'];
		$order_payment_cardname = base64_encode($ccdata['order_payment_name']);
		$order_payment_number   = base64_encode($ccdata['order_payment_number']);
		$order_payment_ccv      = base64_encode($ccdata['credit_card_code']); // this is ccv code
		$order_payment_expire   = $ccdata['order_payment_expire_month'] . $ccdata['order_payment_expire_year'];
		$order_payment_trans_id = $payment_transaction_id;

		$payment_update = "UPDATE " . $this->_table_prefix . "order_payment "
			. " SET order_payment_code  = '" . $order_payment_code . "' ,"
			. " order_payment_cardname  = '" . $order_payment_cardname . "' ,"
			. " order_payment_number  = '" . $order_payment_number . "' ,"
			. " order_payment_ccv  = '" . $order_payment_ccv . "' ,"
			. " order_payment_expire  = '" . $order_payment_expire . "' ,"
			. " order_payment_trans_id  = '" . $payment_transaction_id . "' "
			. " WHERE order_id  = '" . $order_id . "'";

		$db->setQuery($payment_update);
		if (!$db->Query())
		{
			return false;
		}
	}
}
