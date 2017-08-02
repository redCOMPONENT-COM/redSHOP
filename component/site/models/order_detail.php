<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Order_detailModelOrder_detail
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelOrder_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}

	/**
	 * Check Order Information Access Token
	 *
	 * @param   integer  $oid   Order Id
	 * @param   string   $encr  Encryped String - Token
	 *
	 * @return  integer  User Info id - redSHOP User Id if validate.
	 */
	public function checkauthorization($oid, $encr)
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('user_info_id')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_id') . ' = ' . (int) $oid)
			->where($db->qn('encr_key') . ' = ' . $db->q($encr));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$userInfoIdEncr = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		if ($userInfoIdEncr)
		{
			$session               = JFactory::getSession();
			$auth['users_info_id'] = $userInfoIdEncr;

			$session->set('auth', $auth);
		}

		return $userInfoIdEncr;
	}

	/**
	 * Update analytic status
	 *
	 * @return  boolean
	 */
	public function UpdateAnalytics_status($oid)
	{
		$query = "UPDATE  " . $this->_table_prefix . "orders SET `analytics_status` = 1 WHERE order_id = " . (int) $oid;
		$this->_db->setQuery($query);

		if (!$this->_db->execute())
		{
			return false;
		}

		return true;
	}

	/**
	 * Get Billing Addresses
	 *
	 * @return  object
	 */
	public function billingaddresses()
	{
		$app = JFactory::getApplication();
		$order_functions = order_functions::getInstance();
		$user            = JFactory::getUser();
		$session         = JFactory::getSession();

		$auth = $session->get('auth');
		$list = array();

		if ($user->id)
		{
			$list = $order_functions->getBillingAddress($user->id);
		}
		elseif ($auth['users_info_id'])
		{
			$uid  = - $auth['users_info_id'];
			$list = $order_functions->getBillingAddress($uid);
		}

		return $list;
	}

	/**
	 * Get category name from Product Id
	 *
	 * @return  string
	 */
	public function getCategoryNameByProductId($pid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('c.name'))
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $pid))
			->where($db->qn('c.name') . ' IS NOT NULL')
			->order($db->qn('c.id') . ' ASC')
			->setLimit(0, 1);

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * @return  void
	 *
	 * @since   2.0.7
	 */
	public function resetcart()
	{
		RedshopHelperCartSession::reset();
		$session = JFactory::getSession();
		$session->set('ccdata', null);
		$session->set('issplit', null);
		$session->set('userfield', null);

		unset($_SESSION ['ccdata']);
	}

	public function update_ccdata($order_id, $payment_transaction_id)
	{
		$db = JFactory::getDbo();

		$session = JFactory::getSession();
		$ccdata  = $session->get('ccdata');

		$order_payment_code     = $ccdata['creditcard_code'];
		$order_payment_cardname = base64_encode($ccdata['order_payment_name']);
		$order_payment_number   = base64_encode($ccdata['order_payment_number']);

		// This is ccv code
		$order_payment_ccv      = base64_encode($ccdata['credit_card_code']);
		$order_payment_expire   = $ccdata['order_payment_expire_month'] . $ccdata['order_payment_expire_year'];
		$order_payment_trans_id = $payment_transaction_id;

		$payment_update = "UPDATE " . $this->_table_prefix . "order_payment "
			. " SET order_payment_code  = " . $db->quote($order_payment_code) . ", "
			. " order_payment_cardname  = " . $db->quote($order_payment_cardname) . ", "
			. " order_payment_number  = " . $db->quote($order_payment_number) . ", "
			. " order_payment_ccv  = " . $db->quote($order_payment_ccv) . ", "
			. " order_payment_expire  = " . $db->quote($order_payment_expire) . ", "
			. " order_payment_trans_id  = " . $db->quote($payment_transaction_id) . " "
			. " WHERE order_id  = " . (int) $order_id;

		$db->setQuery($payment_update);

		if (!$db->execute())
		{
			return false;
		}
	}
}
