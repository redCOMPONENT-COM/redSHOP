<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use WhichBrowser\Parser;

/**
 * Class Redshop Helper for Payment Methods
 *
 * @since  1.5
 */
class RedshopHelperPayment
{
	/**
	 * Payment Method type constant
	 */
	const TYPE = 'redshop_payment';

	/**
	 * Check for specific payment group type plugin - suffixed using given `type`
	 * Specially Checking for suffixed using `rs_payment_banktransfer` plugin
	 *
	 * @param   string $name       Payment Plugin Element Name
	 * @param   string $typeSuffix Suffix to match
	 *
	 * @return  boolean  True when position found else false
	 */
	public static function isPaymentType($name, $typeSuffix = 'rs_payment_banktransfer')
	{
		$position = strpos($name, $typeSuffix);

		// Return false when given suffix is not found in string
		if ($position === false)
		{
			return false;
		}

		// True when position found
		return true;
	}

	/**
	 * Get payment method info
	 *
	 * @param   string $name Payment Method name - Null to get all plugin info
	 *
	 * @return  mixed   Object is return one payment method, array for all.
	 *
	 * @throws  Exception
	 */
	public static function info($name = '')
	{
		if (!JPluginHelper::isEnabled(self::TYPE, $name))
		{
			throw new Exception(JText::sprintf('COM_REDSHOP_PAYMENT_IS_NOT_ENABLED', $name));
		}

		$plugins = JPluginHelper::getPlugin(self::TYPE, $name);

		if ($name == '' && is_array($plugins))
		{
			array_walk($plugins, function (&$plugin)
			{
				$plugin->params = new Registry($plugin->params);
			});
		}
		else
		{
			$plugins->params = new Registry($plugins->params);
		}

		return $plugins;
	}

	/**
	 * Load payment languages
	 *
	 * @param   boolean  $all  True for all (discover, enabled, disabled). False for just enabled only.
	 *
	 * @return   void
	 *
	 * @since   2.0.2
	 */
	public static function loadLanguages($all = false)
	{
		// Load payment plugin language file
		if ($all)
		{
			$paymentsLangList = RedshopHelperUtility::getPlugins("redshop_payment");
		}
		else
		{
			$paymentsLangList = RedshopHelperUtility::getPlugins("redshop_payment", 1);
		}

		$language = JFactory::getLanguage();

		for ($index = 0, $ln = count($paymentsLangList); $index < $ln; $index++)
		{
			$extension = 'plg_redshop_payment_' . $paymentsLangList[$index]->element;
			$language->load($extension, JPATH_ADMINISTRATOR, $language->getTag(), true);
			$language->load(
				$extension,
				JPATH_PLUGINS . '/' . $paymentsLangList[$index]->folder . '/' . $paymentsLangList[$index]->element,
				$language->getTag(),
				true
			);
		}
	}

	/**
	 * Method for check if order has this payment is update yet?
	 *
	 * @param   integer $orderId       Order ID
	 * @param   mixed   $transactionId Order payment transaction id
	 *
	 * @return  boolean
	 *
	 * @since   2.0.6
	 */
	public static function orderPaymentNotYetUpdated($orderId, $transactionId)
	{
		if (empty($orderId) || empty($transactionId))
		{
			return false;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__redshop_order_payment'))
			->where($db->qn('order_id') . ' = ' . (int) $orderId)
			->where($db->qn('order_payment_trans_id') . ' = ' . $db->quote((int) $transactionId));

		$result = $db->setQuery($query)->loadResult();

		if (!$result)
		{
			return true;
		}

		return false;
	}

	/**
	 * Replace conditional tag from Redshop payment Discount/charges
	 *
	 * @param   string   $template       Template html
	 * @param   integer  $amount         Amount of cart
	 * @param   integer  $cart           Is in cart?
	 * @param   string   $paymentOprand  Payment oprand
	 *
	 * @return  string
	 *
	 * @since   2.0.7
	 */
	public static function replaceConditionTag($template = '', $amount = 0, $cart = 0, $paymentOprand = '-')
	{
		if (strpos($template, '{if payment_discount}') === false || strpos($template, '{payment_discount end if}') === false)
		{
			return $template;
		}

		if ($cart == 1 || $amount == 0)
		{
			$templateDiscountStart = explode('{if payment_discount}', $template);
			$templateDiscountEnd   = explode('{payment_discount end if}', $templateDiscountStart[1]);

			return $templateDiscountStart[0] . $templateDiscountEnd[1];
		}

		if ($amount <= 0)
		{
			$templateStart = explode('{if payment_discount}', $template);
			$templateEnd   = explode('{payment_discount end if}', $templateStart[1]);

			return $templateStart[0] . $templateEnd[1];
		}

		$template = str_replace("{payment_order_discount}", RedshopHelperProductPrice::formattedPrice($amount), $template);
		$payText  = ($paymentOprand == '+') ? JText::_('COM_REDSHOP_PAYMENT_CHARGES_LBL') : JText::_('COM_REDSHOP_PAYMENT_DISCOUNT_LBL');
		$template = str_replace("{payment_discount_lbl}", $payText, $template);
		$template = str_replace("{payment_discount end if}", '', $template);
		$template = str_replace("{if payment_discount}", '', $template);

		return $template;
	}

	/**
	 * List all categories and return HTML format
	 *
	 * @param   string   $name       Name of list
	 * @param   integer  $productId  Only product to show
	 * @param   integer  $size       Size of dropdown
	 * @param   boolean  $multiple   Dropdown is multiple or not
	 * @param   integer  $width      Width in pixel
	 *
	 * @return  string   HTML of dropdown
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public static function listAll($name, $productId, $size = 1, $multiple = false, $width = 250)
	{
		$db    = JFactory::getDbo();
		$html  = '';
		$query = $db->getQuery(true)
			->select($db->qn('payment_id'))
			->from($db->qn('#__redshop_product_payment_xref'));

		if ($productId)
		{
			$query->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));
		}

		$selectedPayments = $db->setQuery($query)->loadObjectList();

		if ($selectedPayments)
		{
			$selectedPayments = array_column($selectedPayments, 'payment_id');
		}

		$multiple = $multiple ? "multiple=\"multiple\"" : "";
		$id       = str_replace('[]', '', $name);
		$html    .= "<select class=\"inputbox\" style=\"width: " . $width . "px;\" size=\"$size\" $multiple name=\"$name\" id=\"$id\">\n";
		$html    .= self::listTree($selectedPayments);
		$html    .= "</select>\n";

		return $html;
	}

	/**
	 * Get payment method by id product
	 *
	 * @param   integer  $productId  Only product to show
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public static function getPaymentByIdProduct($productId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('payment_id'))
			->from($db->qn('#__redshop_product_payment_xref'))
			->where($db->qn('product_id') . ' = ' . $db->q((int) $productId));

		return $db->setQuery($query)->loadColumn();
	}

	/**
	 * List payment into dropdown
	 *
	 * @param   array   $selectedPayments  Only show selected payments
	 * @param   string  $html              Before HTML
	 *
	 * @return  string   HTML of <option></option>
	 *
	 * @since   2.1.0
	 *
	 * @throws  Exception
	 */
	public static function listTree($selectedPayments = array(), $html = '')
	{
		self::loadLanguages();

		$paymentMethods = self::info();

		if (empty($paymentMethods))
		{
			return $html;
		}

		$userBrowser = new Parser($_SERVER['HTTP_USER_AGENT']);
		$isMsIE      = $userBrowser->browser->isFamily('Internet Explorer');

		foreach ($paymentMethods as $p => $oneMethod)
		{
			$paymentPath = JPATH_SITE . '/plugins/redshop_payment/' . $oneMethod->name . '/' . $oneMethod->name . '.php';

			include_once $paymentPath;

			$value 	  = $oneMethod->name;
			$disabled = '';
			$selected = '';

			if (in_array($oneMethod->name, $selectedPayments))
			{
				$selected = "selected=\"selected\"";
			}

			if (in_array($oneMethod->id, $selectedPayments))
			{
				$disabled = 'disabled="disabled"';
			}

			if ($disabled != '' && $isMsIE)
			{
				// IE7 suffers from a bug, which makes disabled option fields selectable
				$html .= "<option $selected value=\"$value\">" . JText::_('PLG_' . strtoupper($oneMethod->name)) . "</option>";
			}
			else
			{
				$html .= "<option $selected $disabled value=\"$value\">" . JText::_('PLG_' . strtoupper($oneMethod->name)) . "</option>";
			}
		}

		return $html;
	}
	/**
	 * Get payment method in Checkout,
	 *
	 * @param   array  $paymentMethods           Array PaymentMethods
	 *
	 * @return  array   Common PaymentMethods
	 *
	 * @since   2.1.0
	 */
	public static function getPaymentMethodInCheckOut($paymentMethods=array())
	{
		$currentPaymentMethods = array();

		if (!empty($paymentMethods))
		{
			foreach ($paymentMethods as $p => $oneMethod)
			{
				$currentPaymentMethods[] = $oneMethod->name;
			}
		}

		$cart = RedshopHelperCartSession::getCart();

		$idx = 0;

		if (isset($cart['idx']))
		{
			$idx = $cart['idx'];
		}

		$db = JFactory::getDbo();

		$paymentMethods      = array();
		$flag                = true;
		$commonPaymentMethod = $currentPaymentMethods;

		for ($i = 0; $i < $idx; $i++)
		{
			$productId = $cart[$i]['product_id'];

			$query = $db->getQuery(true)
				->select($db->qn('a.payment_id'))
				->from($db->qn('#__redshop_product_payment_xref', 'a'))
				->join('INNER', $db->qn('#__redshop_product', 'b') . ' ON (' . $db->qn('a.product_id') . ' = ' . $db->qn('b.product_id') . ')')
				->where($db->qn('b.use_individual_payment_method') . ' = 1');

			if ($productId)
			{
				$query->where($db->qn('a.product_id') . ' = ' . $db->q((int) $productId));
			}

			$db->setQuery($query);
			$payments = $db->loadObjectList();

			if ($payments)
			{
				$payments = array_column($payments, 'payment_id');
			}
			else
			{
				$payments = $currentPaymentMethods;
			}

			if ($idx == 1)
			{
				return $payments;
			}

			$paymentMethods[] = array('product_id' => $productId, 'payments' => $payments);

			if ($i > 0 && $flag)
			{
				$commonPaymentMethod = array_intersect($paymentMethods[$i - 1]['payments'], $paymentMethods[$i]['payments']);

				if (!empty($commonPaymentMethod))
				{
					$flag = false;
				}
			}
		}

		// Product in cart use these payment method
		return $commonPaymentMethod;
	}
	/**
	 * List common payment methods of products cart in checkout,
	 *
	 * @param   array   $paymentMethods     All active payment methods
	 *
	 * @return  string  HTML of <div></div>
	 *
	 * @since   2.1.0
	 */
	public static function displayPaymentMethodInCheckOut($paymentMethods=array())
	{
		$currentPaymentMethods = array();

		if (count($paymentMethods) > 0)
		{
			foreach ($paymentMethods as $p => $oneMethod)
			{
				$currentPaymentMethods[] = $oneMethod->name;
			}
		}

		$cart = RedshopHelperCartSession::getCart();
		$db   = JFactory::getDbo();
		$html = '';

		foreach ($cart as $index => $product)
		{
			if (!is_array($product) || empty($product))
			{
				continue;
			}

			$productId = $product['product_id'];

			$query = $db->getQuery(true);
			$query
				->select($db->qn('a.payment_id'))
				->from($db->qn('#__redshop_product_payment_xref', 'a'))
				->join('INNER', $db->qn('#__redshop_product', 'b') . ' ON (' . $db->qn('a.product_id') . ' = ' . $db->qn('b.product_id') . ')')
				->where($db->qn('b.use_individual_payment_method') . ' = 1');

			if ($productId)
			{
				$query->where($db->qn('a.product_id') . ' = ' . $db->q((int) $productId));
			}

			$db->setQuery($query);
			$payments = $db->loadObjectList();

			if ($payments)
			{
				$payments = array_column($payments, 'payment_id');
			}
			else
			{
				$payments = $currentPaymentMethods;
			}

			$product = RedshopHelperProduct::getProductById($productId);
			$html   .= '<div class="row"><label class="col-xs-5">' . $product->product_name . '</label><div class="col-xs-7">';
			$tmp     = '';

			foreach ($payments as $p)
			{
				$tmp .= JText::_('PLG_' . strtoupper($p)) . ',';
			}

			$tmp   = rtrim($tmp, ",");
			$html .= $tmp . '</div></div>';
		}

		return $html;
	}

	/**
	 * Calculate payment Discount/charges
	 *
	 * @param   float  $total       Total
	 * @param   object $payment     Payment information
	 * @param   float  $finalAmount Final amount
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public static function calculate($total, $payment, $finalAmount)
	{
		$discount = 0;

		if ($payment->payment_discount_is_percent == 0)
		{
			$discount = $payment->payment_price;
		}
		elseif ($payment->payment_price > 0)
		{
			$discount = $total * $payment->payment_price / 100;
		}

		$discount = $discount ? round($discount, 2) : 0;

		if (!$discount)
		{
			return array($finalAmount, 0);
		}

		$discount    = $total < $discount ? $total : $discount;
		$finalAmount = $payment->payment_oprand == '+' ? $finalAmount + $discount : $finalAmount - $discount;

		return array($finalAmount, $discount);
	}
}
