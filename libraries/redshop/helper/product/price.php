<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Class Redshop Helper Product Price
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperProductPrice
{
	/**
	 * @var array
	 */
	protected static $productSpecialPrices = array();

	/**
	 * Get Product Special Price
	 *
	 * @param   float   $productPrice       Product price
	 * @param   string  $discountStringIds  Discount ids
	 * @param   int     $productId          Product id
	 *
	 * @return  null|object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getProductSpecialPrice($productPrice, $discountStringIds, $productId = 0)
	{
		$categoryProduct = $productId ? productHelper::getInstance()->getCategoryProduct($productId) : '';

		// Get shopper group Id
		$userArr = JFactory::getSession()->get('rs_user');

		if (empty($userArr))
		{
			$user    = JFactory::getUser();
			$userArr = RedshopHelperUser::createUserSession($user->id);
		}

		// Shopper Group Id from user session
		$shopperGroupId = $userArr['rs_user_shopperGroup'];

		$key = $discountStringIds . '.' . $categoryProduct;

		if (!array_key_exists($key, self::$productSpecialPrices))
		{
			$time = time();
			$db = JFactory::getDbo();

			// Secure discount ids
			$discountIds = !empty($discountStringIds) ? ArrayHelper::toInteger(explode(',', $discountStringIds)) : array();
			$discountIds = array_filter($discountIds);

			// Secure category ids
			$catIds = !empty($categoryProduct) ? ArrayHelper::toInteger(explode(',', $categoryProduct)) : array();
			$catIds = array_filter($catIds);

			$query = $db->getQuery(true)
				->select('dp.*')
				->from($db->qn('#__redshop_discount_product', 'dp'))
				->where('dp.published = 1');

			if (!empty($catIds))
			{
				$categoriesSub = array();

				foreach ($catIds as $categoryId)
				{
					// Search by categories if configured
					$categoriesSub[] = ('FIND_IN_SET(' . $categoryId . ', dp.category_ids)');
				}

				// Or just take all categories if it's not provided
				$categoriesSub[] = $db->qn('dp.category_ids') . '=' . $db->quote('');

				if (!empty($discountIds))
				{
					$query->where('(dp.discount_product_id IN (' . implode(',', $discountIds) . ')');
					$query->where('((' . implode(') OR (', $categoriesSub) . ')))');
				}
				else
				{
					$query->where('((' . implode(') OR (', $categoriesSub) . '))');
				}
			}
			elseif (!empty($discountIds))
			{
				$query->where('dp.discount_product_id IN (' . implode(',', $discountIds) . ')');
			}

			$query->where('dp.start_date <= ' . (int) $time)
				->where('dp.end_date >= ' . (int) $time)
				->order('dp.amount DESC');

			// Get all discount based on current shopper group
			$subQuery = $db->getQuery(true)
				->select('dps.discount_product_id')
				->from($db->qn('#__redshop_discount_product_shoppers', 'dps'))
				->where('dps.shopper_group_id = ' . (int) $shopperGroupId);

			// Filter by requested discounts only
			$query->where('dp.discount_product_id IN (' . $subQuery . ')');

			self::$productSpecialPrices[$key] = $db->setQuery($query)->loadObjectList();
		}

		if (empty(self::$productSpecialPrices[$key]))
		{
			return null;
		}

		foreach (self::$productSpecialPrices[$key] as $item)
		{
			if (($item->condition == 1 && $item->amount >= $productPrice)
				|| ($item->condition == 2 && $item->amount == $productPrice)
				|| ($item->condition == 3 && $item->amount <= $productPrice))
			{
				return $item;
			}
		}

		return null;
	}

	/**
	 * Method for replace price.
	 *
	 * @param   float  $productPrice  Product price
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function priceReplacement($productPrice)
	{
		if ($productPrice)
		{
			return self::formattedPrice($productPrice);
		}

		if (!Redshop::getConfig()->get('SHOW_PRICE')
			|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') == '1' && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE') != '1')) // && DEFAULT_QUOTATION_MODE==1)
		{
			return Redshop::getConfig()->get('PRICE_REPLACE_URL') ?
				"<a href='http://" . Redshop::getConfig()->get('PRICE_REPLACE_URL') . "' target='_blank'>"
				. Redshop::getConfig()->get('PRICE_REPLACE') . "</a>" : Redshop::getConfig()->get('PRICE_REPLACE');
		}

		if (Redshop::getConfig()->get('SHOW_PRICE') && trim($productPrice) != "")
		{
			if ((Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') == '0')
				|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') == '1' && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE') == '1'))
			{
				return Redshop::getConfig()->get('ZERO_PRICE_REPLACE_URL') ?
					"<a href='http://" . Redshop::getConfig()->get('ZERO_PRICE_REPLACE_URL') . "' target='_blank'>"
					. Redshop::getConfig()->get('ZERO_PRICE_REPLACE') . "</a>" : Redshop::getConfig()->get('ZERO_PRICE_REPLACE');
			}
		}

		return '';
	}

	/**
	 * Format Product Price
	 *
	 * @param   float    $productPrice    Product price
	 * @param   boolean  $convert         Decide to convert price in Multi Currency
	 * @param   string   $currencySymbol  Product Formatted Price
	 *
	 * @return  string                    Formatted Product Price
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function formattedPrice($productPrice, $convert = true, $currencySymbol = '_NON_')
	{
		$currencySymbol = $currencySymbol == '_NON_' ? Redshop::getConfig()->get('REDCURRENCY_SYMBOL') : $currencySymbol;

		// Get Current Currency of SHOP
		$session = JFactory::getSession();

		// If convert set true than use conversation
		if ($convert && $session->get('product_currency'))
		{
			$productPrice = RedshopHelperCurrency::convert($productPrice);

			if (Redshop::getConfig()->get('CURRENCY_SYMBOL_POSITION') == 'behind')
			{
				$currencySymbol = " " . $session->get('product_currency');
			}
			else
			{
				$currencySymbol = $session->get('product_currency') . " ";
			}
		}

		if (!is_numeric($productPrice))
		{
			return '';
		}

		$priceDecimal      = (int) Redshop::getConfig()->get('PRICE_DECIMAL');
		$priceSeperator    = Redshop::getConfig()->get('PRICE_SEPERATOR');
		$thousandSeperator = Redshop::getConfig()->get('THOUSAND_SEPERATOR', '');
		$productPrice      = (double) $productPrice;
		$productPrice      = number_format($productPrice, $priceDecimal, $priceSeperator, $thousandSeperator);

		switch (Redshop::getConfig()->get('CURRENCY_SYMBOL_POSITION'))
		{
			case 'behind':
				return $productPrice . $currencySymbol;
				break;

			case 'none':
				return $productPrice;
				break;

			case 'front':
			default:
				return $currencySymbol . $productPrice;
				break;
		}
	}

	/**
	 * Method for round product price
	 *
	 * @param   float  $productPrice  Product price
	 *
	 * @return  float
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function priceRound($productPrice)
	{
		return round($productPrice, Redshop::getConfig()->get('CALCULATION_PRICE_DECIMAL', 4));
	}
}
