<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerDelivery extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function export_data()
	{
		$db = JFactory::getDbo();
		$query = "SELECT  * FROM   #__redshop_users_info as uf , #__redshop_orders as o LEFT JOIN #__redshop_order_status os ON o.order_status=os.order_status_code WHERE o.user_id = uf.user_id AND uf.address_type = 'BT'  AND o.order_status  IN ('RD','RD1','RD2')   ";

		$db->setQuery($query);
		$orders = $db->loadObjectList();
		$k = 0;

		$del = ",";

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=deliverylist.csv');

		$txt = '';
		$txt .= JText::_('COM_REDSHOP_ORDER_ID') . $del;
		$txt .= JText::_('COM_REDSHOP_NAME') . $del;
		$txt .= JText::_('COM_REDSHOP_ADDRESS') . $del;
		$txt .= JText::_('COM_REDSHOP_TELEPHONE') . $del;
		$txt .= JText::_('COM_REDSHOP_Amount') . $del;
		$txt .= JText::_('COM_REDSHOP_GOOD_NO') . $del;
		$txt .= JText::_('COM_REDSHOP_EDITION_NO') . $del;
		$txt .= JText::_('COM_REDSHOP_PRODUCT') . $del;
		$txt .= JText::_('COM_REDSHOP_CBM') . $del;
		$txt .= JText::_('COM_REDSHOP_EDITION') . $del;
		$txt .= JText::_('COM_REDSHOP_SOLD_FROM_STOCKROOM') . $del;
		$txt .= JText::_('COM_REDSHOP_STATUS');
		$txt .= "\n";

		for ($i = 0, $n = count($orders); $i < $n; $i++)
		{
			$row = $orders[$i];

			$row->id = $row->order_id;

			$query = "SELECT oi.*,p.product_volume "
				. " FROM #__redshop_order_item oi "
				. " LEFT JOIN #__redshop_product p ON p.product_id = oi.product_id "
				. " WHERE order_id = " . (int) $row->order_id
				. " ORDER BY delivery_time";

			$db->setQuery($query);

			$products = $db->loadObjectList();

			$total = count($products);

			for ($j = 0; $j < $total; $j++)
			{
				$product = $products[$j];

				$query = "SELECT * FROM #__redshop_container WHERE container_id = '" . $product->container_id . "'";

				$db->setQuery($query);

				if (!$container = $db->loadObject())
				{
					$container->container_name = '';
				}

				if ($j == 0)
				{
					$txt .= $row->order_id . $del;
					$txt .= $row->firstname . $del;

					$txt .= $del;
					$txt .= $del;

					$txt .= $product->product_quantity . $del;
					$txt .= $del;

					$txt .= $product->order_item_sku . $del;

					$txt .= $product->order_item_name . $del;
					$txt .= $product->product_volume . $del;

					$txt .= $del;
					$txt .= $container->container_name . $del;
					$txt .= $row->order_status_name;
					$txt .= "\n";
				}
				else
				{
					$txt .= $del;
					$txt .= $del;

					$txt .= $del;
					$txt .= $del;

					$txt .= $product->product_quantity . $del;
					$txt .= $del;

					$txt .= $product->order_item_sku . $del;

					$txt .= $product->order_item_name . $del;
					$txt .= $product->product_volume . $del;

					$txt .= $del;
					$txt .= $container->container_name . $del;
					$txt .= $del;
					$txt .= "\n";
				}
			}

			$k = 1 - $k;
		}

		echo $txt;
		exit;
	}
}
