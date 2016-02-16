<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopControllerStockroom_listing
 *
 * @since  1.5
 */
class RedshopControllerStockroom_listing extends RedshopControllerAdmin
{
	public function saveStock()
	{
		$model = $this->getModel('stockroom_listing');
		$stockroom_type = JRequest::getVar('stockroom_type', 'product', 'post', 'string');

		$pid = JRequest::getVar('pid', array(0), 'post', 'array');
		$sid = JRequest::getVar('sid', array(0), 'post', 'array');
		$quantity = JRequest::getVar('quantity', array(0), 'post', 'array');
		$preorder_stock = JRequest::getVar('preorder_stock', array(0), 'post', 'array');
		$ordered_preorder = JRequest::getVar('ordered_preorder', array(0), 'post', 'array');

		for ($i = 0, $in = count($sid); $i < $in; $i++)
		{
			$model->storeStockroomQuantity($stockroom_type, $sid[$i], $pid[$i], $quantity[$i], $preorder_stock[$i], $ordered_preorder[$i]);
		}

		$this->setRedirect('index.php?option=com_redshop&view=stockroom_listing&id=0&stockroom_type=' . $stockroom_type);
	}

	public function ResetPreorderStock()
	{
		$model = $this->getModel('stockroom_listing');
		$stockroom_type = JRequest::getVar('stockroom_type', 'product');
		$pid = JRequest::getVar('product_id');
		$sid = JRequest::getVar('stockroom_id');

		$model->ResetPreOrderStockroomQuantity($stockroom_type, $sid, $pid);

		$this->setRedirect('index.php?option=com_redshop&view=stockroom_listing&id=0&stockroom_type=' . $stockroom_type);
	}

	public function print_data()
	{
		echo '<script type="text/javascript" language="javascript">	window.print(); </script>';
	}
}
