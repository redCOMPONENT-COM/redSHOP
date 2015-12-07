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
		$db = JFactory::getDbo();
		$row = $this->getTable('barcode');

		if (!$row->bind($data))
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}
	}

	public function checkorder($barcode)
	{
		$db = JFactory::getDbo();
		$query = "SELECT order_id  FROM #__redshop_orders where barcode='" . $barcode . "'";
		$db->setQuery($query);
		$order = $db->loadObject();

		if (!$order)
		{
			return false;
		}

		return $order;
	}

	public function getLog($order_id)
	{
		$db = JFactory::getDbo();
		$query = "SELECT count(*) as log FROM #__redshop_orderbarcode_log where order_id=" . $order_id;
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function getLogdetail($order_id)
	{
		$db = JFactory::getDbo();
		$logquery = "SELECT *  FROM #__redshop_orderbarcode_log where order_id=" . $order_id;
		$db->setQuery($logquery);

		return $db->loadObjectlist();
	}

	public function getUser($user_id)
	{
		$db = JFactory::getDbo();
		$userquery = "SELECT name  FROM #__redshop_users where id=" . $user_id;
		$db->setQuery($userquery);

		return $db->loadObject();
	}

	public function updateorderstatus($barcode, $order_id)
	{
		$db = JFactory::getDbo();
		$update_query = "UPDATE #__redshop_orders SET order_status = 'S' where barcode='"
			. $barcode . "' and order_id ='" . $order_id . "'";
		$db->setQuery($update_query);
		$db->execute();
	}
}
