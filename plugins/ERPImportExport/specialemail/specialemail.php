<?php
/**
 * @package     RedShop
 * @subpackage  ERPImportExport
 * @copyright   Copyright (C) 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminOrder');
JLoader::load('RedshopHelperAdminShipping');

/**
 * Create Color image plugin
 *
 * @package     RedShop
 * @subpackage  ERPImportExport
 * @since       1.3.3
 */
class PlgERPImportExportSpecialemail extends JPlugin
{
	/**
	 * Get Order Details
	 *
	 * @param   integer  $orderId  Order Information Id
	 * @param   integer  $userId   User Id
	 *
	 * @return  array    Order Infomation
	 */
	private function _getOrderDetails($orderId, $userId = 0)
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
				->select('o.*')
				->from($db->qn('#__redshop_orders', 'o'));

		if ($orderId)
		{
			$query->where($db->qn('o.order_id') . ' = ' . (int) $orderId);
		}

		if ($userId)
		{
			$query->where($db->qn('o.user_id') . ' = ' . (int) $userId);
		}

		// Join Payment Information
		$query->select('op.*')
			->leftjoin(
				$db->qn('#__redshop_order_payment', 'op')
				. ' ON ' . $db->qn('o.order_id') . '=' . $db->qn('op.order_id')
			);

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$order = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $order;
	}

	/**
	 * Trigger Plugin on after Order place
	 *
	 * @param   object  $orderDetail  Order Info
	 *
	 * @return  void
	 */
	function exportOrder($orderDetail)
	{
		$app             = JFactory::getApplication();
		$config          = JFactory::getConfig();
		$order_functions = new order_functions;
		$producthelper   = new producthelper;
		$shippinghelper  = new shipping;
		$orderDetail     = $this->_getOrderDetails((int) $orderDetail->order_id);

		// Load language files
		$language        = JFactory::getLanguage();
		$shippingPlugins = JPluginHelper::getPlugin("redshop_shipping");

		for ($l = 0; $l < count($shippingPlugins); $l++)
		{
			$extension = 'plg_redshop_shipping_' . strtolower($shippingPlugins[$l]->name);
			$language->load($extension, JPATH_ADMINISTRATOR);
		}

		$shippingDetail  = explode("|", $shippinghelper->decryptShipping(str_replace(" ", "+", $orderDetail->ship_method_id)));
		$billingAddress  = $order_functions->getOrderBillingUserInfo($orderDetail->order_id);
		$shippingAddress = $order_functions->getOrderShippingUserInfo($orderDetail->order_id);

		if (count($shippingAddress) <= 0)
		{
			$shippingAddress = $billingAddress;
		}

		$orderItems      = $order_functions->getOrderItemDetail($orderDetail->order_id);

		$mailContent = '' . "\n";
		$mailContent .= '<Ordrenr>' . $orderDetail->order_id . "\n";
		$mailContent .= '<OrdreDato>' . date("d-m-Y", $orderDetail->cdate) . "\n";
		$mailContent .= date("H:i:s", $orderDetail->cdate) . "\n";

		// Payment transation id
		$mailContent .= '<Rekvnr>' . $orderDetail->order_payment_trans_id . "\n";
		$mailContent .= ' ' . "\n";
		$mailContent .= '<KundeData>' . "\n";
		$mailContent .= '------------------------------------------' . "\n";
		$mailContent .= '<Notat>' . $orderDetail->customer_note . "\n";
		$mailContent .= '<FNavn>' . $billingAddress->firstname . ' ' . "\n";
		$mailContent .= $billingAddress->lastname . "\n";

		// CVR number
		$mailContent .= '<FCvr>' . "\n";
		$mailContent .= '<FKontakt>' . "\n";
		$mailContent .= '<FAdr1>' . $billingAddress->address . "\n";
		$mailContent .= '<FAdr2>' . "\n";
		$mailContent .= '<FPost>' . $billingAddress->zipcode . "\n";
		$mailContent .= '<FLand>' . $billingAddress->city . "\n";
		$mailContent .= '<FTlf>' . $billingAddress->phone . "\n";
		$mailContent .= '<FMail>' . $billingAddress->user_email . "\n";
		$mailContent .= '<FB2Bpassword>' . "\n";

		// Leverings adresse her under
		$mailContent .= '<LNavn>' . $shippingAddress->firstname . ' ' . $shippingAddress->lastname . "\n";
		$mailContent .= '<LKontakt>' . "\n";
		$mailContent .= '<LAdr1>' . $shippingAddress->address . "\n";
		$mailContent .= '<LAdr2>' . "\n";
		$mailContent .= '<LPost>' . $shippingAddress->zipcode . ' ' . $shippingAddress->city . "\n";

		$mailContent .= '<LLand>' . $shippingAddress->country_code . "\n";
		$mailContent .= 'Danmark' . "\n";
		$mailContent .= ' ' . "\n";

		$mailContent .= '<Reserved1>' . $shippingAddress->phone . "\n";
		$mailContent .= '<Reserved2>' . $shippingAddress->user_email . "\n";
		$mailContent .= '<Reserved3>' . JText::_($shippingDetail[1]) . " " . $shippingDetail[2] . " " . $this->_currencyFormat($shippingDetail[3]) . "\n";
		$mailContent .= '<Reserved4>' . $orderDetail->requisition_number . "\n";

		$i = 0;

		foreach ($orderItems as $key => $orderItem)
		{
			// Giftcard number
			if ((int) $orderItem->is_giftcard == 1)
			{
				$mailContent .= '<Reserved5>' . $orderItem->product_id . "\n";
			}

			if ($i == 0)
			{
				$mailContent .= '<EAN>' . "\n";
				$mailContent .= '---------------------------------------------------------------' . "\n";
				$mailContent .= '<Antal>' . "\n";
				$mailContent .= '<Varenr./Vare>' . "\n";
				$mailContent .= '<Stk. pris>' . "\n";
				$mailContent .= '<Total Pris>' . "\n";
				$mailContent .= '======================================================================' . "\n";
			}

			$i++;

			$mailContent .= $orderItem->product_quantity . "\n";

			// Get Order Attribute information
			$orderedAttributes = $producthelper->makeAttributeOrder(
									$orderItem->order_item_id,
									0,
									$orderItem->product_id
								);

			$mailContent .= $orderedAttributes->attributeNumber . "\n";
			/*$mailContent .= $this->_currencyFormat($orderItem->product_item_price_excl_vat) . '   " - "    ' . $this->_currencyFormat($orderItem->product_item_price) . "\n";*/
			$mailContent .= '        ' . $orderItem->order_item_name . '    ' . strip_tags($orderedAttributes->product_attribute) . "\n";
			$mailContent .= $this->_currencyFormat($orderItem->product_final_price) . "\n";
		}

		$mailContent .= ' ' . "\n";
		$mailContent .= '-------------------------------------------------------------------------- ' . "\n";
		$mailContent .= '<Varetotal> ' . "\n";
		$mailContent .= $this->_currencyFormat($orderDetail->order_total) . "\n";
		$mailContent .= 'DKK ' . "\n";
		$mailContent .= '------------------------------------------------------------------------' . "\n";
		$mailContent .= '<TotalRabat>' . $this->_currencyFormat($orderDetail->order_discount) . "\n";
		$mailContent .= 'DKK ' . "\n";

		$mailContent .= '<Betalingsmetode>' . JText::_($orderDetail->order_payment_name) . "\n";
		$mailContent .= '<Betalingsgebyr>' . $this->_currencyFormat($orderDetail->order_transfee) . "\n";
		$mailContent .= '<Forsendelsesmetode>' . JText::_($shippingDetail[1]) . "\n";
		$mailContent .= '<Forsendelsesgebyr>' . $this->_currencyFormat($shippingDetail[3]) . "\n";

		// Weight
		$mailContent .= '<Vægt>.' . $this->_getOrderWeight($orderDetail->order_id) . "\n";
		$mailContent .= '<Total excl. moms>' . ($orderDetail->order_total - $orderDetail->order_tax) . ' DKK' . "\n";
		$mailContent .= '<Total incl. moms>' . $orderDetail->order_total . ' DKK' . "\n";

		$content = nl2br(htmlspecialchars($mailContent));

		JFactory::getMailer()->sendMail(
			$config->get('mailfrom'),
			$config->get('fromname'),
			$this->params->get('email', 'ERP Special Email'),
			$this->params->get('subject', 'ERP Special Email Subject'),
			$content,
			true
		);
	}

	/**
	 * Currency Format for this specific email
	 *
	 * @param   float  $price  Price
	 *
	 * @return  string  Formatted String output
	 */
	private function _currencyFormat($price)
	{
		return number_format($price, 2, ',', '.');
	}

	/**
	 * Total weight of all ordered Product
	 *
	 * @param   integer  $orderId  Order Id
	 *
	 * @return  float            Order total weight of products
	 */
	private function _getOrderWeight($orderId)
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('sum(' . $db->qn('weight') . ')')
			->from($db->qn('#__redshop_product', 'p'))
			->leftjoin(
				$db->qn('#__redshop_order_item', 'oi')
				. ' ON ' . $db->qn('oi.product_id') . '=' . $db->qn('p.product_id')
			)
			->where($db->qn('oi.order_id') . ' = ' . (int) $orderId)
			->where($db->qn('oi.is_giftcard') . ' = 0');

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$weight = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $weight;
	}
}
