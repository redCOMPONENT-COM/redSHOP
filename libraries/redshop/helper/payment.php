<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

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
			->where($db->qn('order_payment_trans_id') . ' = ' . $db->quote($transactionId));

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
	 * @param   string   $name                Name of list
	 * @param   integer  $productId           Only product to show
	 * @param   array    $selectedPayments    Only show selected payments
	 * @param   integer  $size                Size of dropdown
	 * @param   boolean  $multiple            Dropdown is multiple or not
	 * @param   array    $disabledFields      Fields need to be disabled
	 * @param   integer  $width               Width in pixel
	 *
	 * @return  string   HTML of dropdown
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function listAll($name, $productId, $selectedPayments = array(), $size = 1, $multiple = false, $disabledFields = array(),
		$width = 250)
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
		$selectedPayments = array_column($selectedPayments, 'payment_id');


		$multiple = $multiple ? "multiple=\"multiple\"" : "";
		$id       = str_replace('[]', '', $name);
		$html     .= "<select class=\"inputbox\" style=\"width: " . $width . "px;\" size=\"$size\" $multiple name=\"$name\" id=\"$id\">\n";


		$html .= self::listTree($productId, '0', $selectedPayments, $disabledFields);

		$html .= "</select>\n";

		return $html;
	}

	/**
	 * List payment into dropdown,
	 *
	 *
	 * @param   string   $productId          Product ID
	 * @param   integer  $cid                Category ID
	 * @param   array    $selectedPayments   Only show selected payments
	 * @param   array    $disabledFields     Disable fields
	 * @param   string   $html               Before HTML
	 *
	 * @return String   HTML of <option></option>
	 *
	 * @since
	 */
	public static function listTree($productId = '', $cid = 0, $selectedPayments = array(), $disabledFields = array(), $html = '')
	{
		$paymentMethods = self::info();
		$totalPaymentMethod = count($paymentMethods);

		if ($totalPaymentMethod > 0)
		{
			foreach ($paymentMethods as $p => $oneMethod)
			{
				include_once JPATH_SITE . '/plugins/redshop_payment/' . $oneMethod->name . '/' . $oneMethod->name . '.php';

				$value    = $oneMethod->name;
				$disabled = '';
				$selected = '';

				if (in_array($oneMethod->name, $selectedPayments))
				{
					$selected = "selected=\"selected\"";
				}

				$html .= "<option $selected $disabled value=\"$value\">" . JText::_('PLG_' . strtoupper($oneMethod->name)) . "</option>";

			}
		}

		return $html;
	}

	/**
	 * List payment into dropdown,
	 *
	 *
	 * @param   array  $paymentMethods   Payment methods
	 *
	 * @return  string                   HTML of <option></option>
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getPaymentMethodInCheckOut($paymentMethods = array())
	{
		$totalPaymentMethod = count($paymentMethods);

		$currentPaymentMethods = array();

		if ($totalPaymentMethod > 0)
		{
			foreach ($paymentMethods as $p => $oneMethod)
			{
				$currentPaymentMethods[] = $oneMethod->name;
			}
		}

		$cart = JFactory::getSession()->get('cart');

		$idx = 0;

		if (isset($cart['idx']))
		{
			$idx  = $cart['idx'];
		}

		$db = JFactory::getDbo();

		$newPaymentMethods = array();
		$flag = true;
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

			$payments = $db->setQuery($query)->loadObjectList();

			if ($payments)
			{
				$payments = array_column($payments , 'payment_id');
			}
			else
			{
				$payments = $currentPaymentMethods;
			}

			if ($idx == 1)
			{
				return $payments;
			}

			$newPaymentMethods[] = array('product_id'=>$productId, 'payments'=> $payments);

			if ($i > 0 && $flag)
			{
				$commonPaymentMethod = array_intersect($newPaymentMethods[$i-1]['payments'], $newPaymentMethods[$i]['payments']);

				if (!$commonPaymentMethod)
				{
					$flag = false;
				}
			}
		}

		// Product in cart use these payment method
		if ($commonPaymentMethod)
		{
			return $commonPaymentMethod;
		}
	}

	public static function displayPaymentMethodInCheckOut($paymentMethods=array())
	{
		$totalPaymentMethod = count($paymentMethods);

		$currentPaymentMethods = array();
		if ($totalPaymentMethod > 0)
			foreach ($paymentMethods as $p => $oneMethod)
				$currentPaymentMethods[] = $oneMethod->name;

		$cart = JFactory::getSession()->get('cart');

		$idx = 0;
		if (isset($cart['idx'])) $idx  = $cart['idx'];

		$db = JFactory::getDbo();

		$common_payment_method = $currentPaymentMethods;
		$productHelper = productHelper::getInstance();
		$html = '';

		for ($i = 0; $i < $idx; $i++)
		{
			$productId = $cart[$i]['product_id'];

			$query = $db->getQuery(true);
			$query
			    ->select($db->qn('a.payment_id'))
			    ->from($db->qn('#__redshop_product_payment_xref','a'))
			    ->join('INNER', $db->qn('#__redshop_product', 'b') . ' ON (' . $db->qn('a.product_id') . ' = ' . $db->qn('b.product_id') . ')')
			    ->where($db->qn('b.use_individual_payment_method') . ' = 1');

			if ( $productId )
			{
				$query->where($db->qn('a.product_id') . ' = ' . $db->q((int) $productId));
			}

			$db->setQuery($query);
			$payments = $db->loadObjectList();

			if ( $payments )
				$payments = array_column($payments , 'payment_id');
			else
				$payments = $currentPaymentMethods;

			$product = $productHelper->getProductById($productId);
			$html .=  '<div class="row"><label class="col-xs-5">'.$product->product_name.'</label><div class="col-xs-7">';
			$tmp = '';
			foreach ($payments as $p)
			{
				$tmp .=  JText::_('PLG_' . strtoupper($p)).',';
			}
			$tmp = rtrim($tmp,",");
			$html .= $tmp.'</div></div>';
		}

		return $html;
	}
}
