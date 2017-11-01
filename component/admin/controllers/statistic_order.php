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
 * Statistic Order controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Statistic Order
 * @since       2.0.0.3
 */
class RedshopControllerStatistic_Order extends RedshopControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.0.0.3
	 */
	public function getModel($name = 'Statistic_Order', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Export orders CSV.
	 *
	 * @return  mixed.
	 *
	 * @since   2.0.0.3
	 */
	public function exportOrder()
	{
		$productHelper = productHelper::getInstance();
		$orderFunction = order_functions::getInstance();
		$model         = $this->getModel();
		$data          = $model->exportOrder();
		$noProducts    = $model->countProductByOrder();
		$productCount  = array();

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=Order.csv');

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			$productCount[] = $noProducts [$i]->noproduct;
		}

		$noProducts = max($productCount);

		ob_clean();

		echo "Order number, Order status, Order date , Shipping method , Shipping user, Shipping address,";
		echo "Shipping postalcode,Shipping city, Shipping country, Company name, Email ,Billing address,";
		echo "Billing postalcode, Billing city, Billing country,Billing User ,";

		for ($i = 1; $i <= $noProducts; $i++)
		{
			echo JText::_('COM_REDSHOP_PRODUCT_NAME') . $i . ' ,';
			echo JText::_('COM_REDSHOP_PRODUCT') . ' ' . JText::_('COM_REDSHOP_PRODUCT_PRICE') . $i . ' ,';
			echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE') . $i . ' ,';
		}

		echo "Order Total\n";

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			$billingInfo = RedshopHelperOrder::getOrderBillingUserInfo($data[$i]->order_id);
			$details = RedshopShippingRate::decrypt($data[$i]->ship_method_id);

			echo $data [$i]->order_id . ",";
			echo utf8_decode($orderFunction->getOrderStatusTitle($data [$i]->order_status)) . " ,";
			echo date('d-m-Y H:i', $data[$i]->cdate) . " ,";

			if (empty($details))
			{
				echo str_replace(",", " ", $details[1]) . "(" . str_replace(",", " ", $details[2]) . ") ,";
			}
			else
			{
				echo '';
			}

			$shippingInfo = RedshopHelperOrder::getOrderShippingUserInfo($data[$i]->order_id);

			echo str_replace(",", " ", $shippingInfo->firstname) . " " . str_replace(",", " ", $shippingInfo->lastname) . " ,";
			echo str_replace(",", " ", utf8_decode($shippingInfo->address)) . " ,";
			echo $shippingInfo->zipcode . " ,";
			echo str_replace(",", " ", utf8_decode($shippingInfo->city)) . " ,";
			echo $shippingInfo->country_code . " ,";
			echo str_replace(",", " ", $shippingInfo->company_name) . " ,";
			echo $shippingInfo->user_email . " ,";

			echo str_replace(",", " ", utf8_decode($billingInfo->address)) . " ,";
			echo $billingInfo->zipcode . " ,";
			echo str_replace(",", " ", utf8_decode($billingInfo->city)) . " ,";
			echo $billingInfo->country_code . " ,";
			echo str_replace(",", " ", $billingInfo->firstname) . " " . str_replace(",", " ", $billingInfo->lastname) . " ,";

			$noItems = $orderFunction->getOrderItemDetail($data[$i]->order_id);

			for ($it = 0, $countItem = count($noItems); $it < $countItem; $it++)
			{
				echo str_replace(",", " ", utf8_decode($noItems[$it]->order_item_name)) . " ,";
				echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " " . $noItems[$it]->product_final_price . ",";

				$productAttribute = $productHelper->makeAttributeOrder($noItems[$it]->order_item_id, 0, $noItems[$it]->product_id, 0, 1);
				$productAttribute = strip_tags(str_replace(",", " ", $productAttribute->product_attribute));
				echo trim(utf8_decode($productAttribute)) . " ,";
			}

			$temp = $noProducts - count($noItems);

			if ($temp >= 0)
			{
				echo str_repeat(' ,', $temp * 3);
			}

			echo  Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " " . $data [$i]->order_total . "\n";
		}

		exit ();
	}
}
