<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
		$model          = $this->getModel('stockroom_listing');
		$stockroom_type = $this->input->post->getString('stockroom_type', 'product');

		$pid              = $this->input->post->get('pid', array(0), 'array');
		$sid              = $this->input->post->get('sid', array(0), 'array');
		$quantity         = $this->input->post->get('quantity', array(0), 'array');
		$preorder_stock   = $this->input->post->get('preorder_stock', array(0), 'array');
		$ordered_preorder = $this->input->post->get('ordered_preorder', array(0), 'post', 'array');

		for ($i = 0, $in = count($sid); $i < $in; $i++)
		{
			$model->storeStockroomQuantity($stockroom_type, $sid[$i], $pid[$i], $quantity[$i], $preorder_stock[$i], $ordered_preorder[$i]);
		}

		$this->setRedirect('index.php?option=com_redshop&view=stockroom_listing&id=0&stockroom_type=' . $stockroom_type);
	}

	public function ResetPreorderStock()
	{
		$model          = $this->getModel('stockroom_listing');
		$stockroom_type = $this->input->get('stockroom_type', 'product');
		$pid            = $this->input->get('product_id');
		$sid            = $this->input->get('stockroom_id');

		$model->ResetPreOrderStockroomQuantity($stockroom_type, $sid, $pid);

		$this->setRedirect('index.php?option=com_redshop&view=stockroom_listing&id=0&stockroom_type=' . $stockroom_type);
	}

	public function print_data()
	{
		echo '<script type="text/javascript" language="javascript">	window.print(); </script>';
	}
}
