<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Import library dependencies
jimport('joomla.plugin.plugin');
JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminOrder');

class plgredshop_productstockroom_status extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for
	 * plugins because func_get_args ( void ) returns a copy of all passed arguments
	 * NOT references.  This causes problems with cross-referencing necessary for the
	 * observer design pattern.
	 */
	public function plgredshop_productstockroom_status(&$subject)
	{
		parent::__construct($subject);

		// Load plugin parameters
		$this->_table_prefix = '#__redshop_';
		$this->_plugin = JPluginHelper::getPlugin('redshop_product', 'stockroom_status');
		$this->_params = new JRegistry($this->_plugin->params);
	}

	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param    object        The Product Template Data
	 * @param    object        The product params
	 * @param    object        The product object
	 */
	public function getStockroomStatus($order_id)
	{
		$db = JFactory::getDbo();
		//$order_id= $order->order_id;
		$order_functions = new order_functions;
		$stockroomhelper = new rsstockroomhelper;
		$producthelper = new producthelper;
		$orderproducts = $order_functions->getOrderItemDetail($order_id);

		$stockroom_id = "";

		$message = "<table ><tr><td colspan='4'>Hello Administrator,</td>
					</tr>
					<tr>
						<td colspan='4'>The following product/s have reached minimum stock level. </td>
					</tr>
					<tr><td colspan='4'>
					<table border ='1'>
					<tr>
						<td>Product Number</td>
						<td>Product Name</td>
						<td>Stockroom Name</td>
						<td>Current Stock</td>
					</tr>";

		for ($p = 0; $p < count($orderproducts); $p++)
		{
			$product_id = $orderproducts[$p]->product_id;
			$product_detail = $producthelper->getProductById($product_id);
			$stockroom_id = $orderproducts[$p]->stockroom_id;

			$stockroom_id = explode(",", $stockroom_id);

			$stock_flag = 0;

			for ($s = 0; $s < count($stockroom_id); $s++)
			{
				$stock_details = $stockroomhelper->getStockroomDetail($stockroom_id[$s]);

				$min_stock_amount = $stock_details[0]->min_stock_amount;
				$stock_status = $stockroomhelper->getStockAmountwithReserve($product_id, $section = "product", $stockroom_id[$s]);

				if ($stock_status <= $min_stock_amount)
				{
					$stock_flag = 1;

					$message .= "
						<tr>
							<td>" . $product_detail->product_number . "</td>
							<td>" . $product_detail->product_name . "</td>
							<td>" . $stock_details[0]->stockroom_name . "</td>
							<td>" . $stock_status . "</td>
						</tr>";
				}
			}

		}

		$message .= "</table></td></tr>";
		$message .= "<tr><td colspan='4'>Regards,</td></tr><tr><td colspan='4'>Stockkeeper</td></tr>";
		$message .= "</table>";

		if (ADMINISTRATOR_EMAIL != "" && $stock_flag == 1)
		{
			JMail::getInstance()->sendMail(SHOP_NAME, SHOP_NAME, ADMINISTRATOR_EMAIL, "Stockroom Status Mail", $message, 1);
		}

	}

}

?>
