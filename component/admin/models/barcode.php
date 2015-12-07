<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die();


/**
 * Barcode reder/generator Model
 *
 * @package     Redshop
 * @subpackage  Barcode
 * @since       1.2
 */
class RedshopModelBarcode extends RedshopModel
{
	public $_id = null;

	public $_data = null;


	public $_loglist = null;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();

	}

	public function save($data)
	{
		$row = $this->getTable('barcode');

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
	}

	public function checkorder($barcode)
	{
		$query = "SELECT order_id  FROM #__redshop_orders where barcode='" . $barcode . "'";
		$this->_db->setQuery($query);
		$order = $this->_db->loadObject();

		if (!$order)
		{
			return false;
		}

		return $order;
	}

	public function getLog($order_id)
	{
		$query = "SELECT count(*) as log FROM #__redshop_orderbarcode_log where order_id=" . $order_id;
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	public function getLogdetail($order_id)
	{
		$logquery = "SELECT *  FROM #__redshop_orderbarcode_log where order_id=" . $order_id;
		$this->_db->setQuery($logquery);

		return $this->_db->loadObjectlist();
	}

	public function getUser($user_id)
	{
		$userquery = "SELECT name  FROM #__redshop_users where id=" . $user_id;
		$this->_db->setQuery($userquery);

		return $this->_db->loadObject();
	}

	public function updateorderstatus($barcode, $order_id)
	{
		$update_query = "UPDATE #__redshop_orders SET order_status = 'S' where barcode='"
			. $barcode . "' and order_id ='" . $order_id . "'";
		$this->_db->setQuery($update_query);
		$this->_db->execute();
	}
}
