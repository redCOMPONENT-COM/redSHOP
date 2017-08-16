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
			$db   = JFactory::getDbo();

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

		if (Redshop::getConfig()->get('SHOW_PRICE'))
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

	/**
	 * Method for get product net price
	 *
	 * @param   integer  $productId     ID of product
	 * @param   integer  $userId        ID of user
	 * @param   integer  $quantity      Quantity for get
	 * @param   string   $templateHtml  Template data
	 * @param   array    $attributes    Attributes list.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getNetPrice($productId, $userId = 0, $quantity = 1, $templateHtml = '', $attributes = array())
	{
		$row       = RedshopHelperProduct::getProductById($productId);
		$productId = $row->product_id;
		$newPrice  = $row->product_price;

		$userId             = !$userId ? JFactory::getUser()->id : $userId;
		$productPrices      = array();
		$priceText          = JText::_('COM_REDSHOP_REGULAR_PRICE') . "";
		$productVatLabel    = '';
		$priceLabel         = '';
		$oldPriceLabel      = '';
		$priceSavingLabel   = '';
		$oldPriceExcludeVat = '';

		$result = productHelper::getInstance()->getProductPrices($productId, $userId, $quantity);

		if (!empty($result))
		{
			$newPrice = $result->product_price;
		}

		// Set Product Custom Price through product plugin
		$dispatcher = RedshopHelperUtility::getDispatcher();
		JPluginHelper::importPlugin('redshop_product');
		$results = $dispatcher->trigger('setProductCustomPrice', array($productId));

		if (count($results) > 0 && $results[0])
		{
			$newPrice = $results[0];
		}

		$isApplyTax   = productHelper::getInstance()->getApplyVatOrNot($templateHtml, $userId);
		$specialPrice = self::getProductSpecialPrice($newPrice, productHelper::getInstance()->getProductSpecialId($userId), $productId);

		if (!is_null($specialPrice))
		{
			$discountAmount = $specialPrice->discount_type == 0 ?
				$specialPrice->discount_amount : ($newPrice * $specialPrice->discount_amount) / (100);

			$newPrice = $newPrice < 0 ? 0 : $newPrice;
			$regPrice = $row->product_price;

			if ($isApplyTax)
			{
				$priceTax = RedshopHelperProduct::getProductTax($row->product_id, $newPrice, $userId);
				$regPrice = $row->product_price + $priceTax;
			}

			/**
			 * @TODO: Need to check here why system force
			 * $priceTax  = $this->getProductTax($productId, $row->product_price, $userId);
			 * $reg_price = $row->product_price;
			 */

			$formattedPrice = self::formattedPrice($regPrice);
			$productPrice   = $newPrice - $discountAmount;
			$productPrice   = $productPrice < 0 ? 0 : $productPrice;

			$priceText = $priceText . '<span class="redPriceLineThrough">' . $formattedPrice
				. '</span><br />' . JText::_('COM_REDSHOP_SPECIAL_PRICE');
		}
		else
		{
			$productPrice = $newPrice;
		}

		$dispatcher->trigger('onSetProductPrice', array(&$productPrice, $productId));

		$excludeVat     = productHelper::getInstance()->defaultAttributeDataPrice($productId, $productPrice, $templateHtml, $userId, 0, $attributes);
		$formattedPrice = self::formattedPrice($excludeVat);
		$priceText      = $priceText . '<span id="display_product_price_without_vat' . $productId . '">' . $formattedPrice . '</span>'
			. '<input type="hidden" name="product_price_excluding_price" id="product_price_excluding_price' . $productId . '" '
			. 'value="' . $productPrice . '" />';

		$defaultTaxAmount         = RedshopHelperProduct::getProductTax($productId, $productPrice, $userId, 1);
		$taxAmount                = RedshopHelperProduct::getProductTax($productId, $productPrice, $userId);
		$productPriceExcludingVat = $productPrice;
		$productPriceIncludingVat = $defaultTaxAmount + $productPriceExcludingVat;

		if ($isApplyTax)
		{
			$productPrice = $taxAmount + $productPrice;
		}

		$productPrice = $productPrice < 0 ? 0 : $productPrice;

		if (Redshop::getConfig()->get('SHOW_PRICE'))
		{
			$priceExcludingVat        = $priceText;
			$productDiscountPriceTemp = RedshopHelperDiscount::getDiscountPriceBaseDiscountDate($productId);
			$oldPriceExcludeVat       = $productPriceExcludingVat;

			$dispatcher->trigger('onSetProductDiscountPrice', array(&$productDiscountPriceTemp, $productId));

			if ($row->product_on_sale && $productDiscountPriceTemp > 0)
			{
				$discountPriceExcludingVat = $productDiscountPriceTemp;

				$taxAmount = RedshopHelperProduct::getProductTax($productId, $productDiscountPriceTemp, $userId);

				if (intval($isApplyTax) && $productDiscountPriceTemp)
				{
					$productDiscountPriceTemp = $productDiscountPriceTemp + $taxAmount;
				}

				if ($productPrice < $productDiscountPriceTemp)
				{
					$productPrice = productHelper::getInstance()->defaultAttributeDataPrice(
						$productId, $productPrice, $templateHtml, $userId, intval($isApplyTax), $attributes
					);

					$mainPrice             = $productPrice;
					$discountPrice         = '';
					$oldPrice              = '';
					$priceSaving           = '';
					$priceSavingPercentage = '';
					$priceNoVAT            = $productPriceExcludingVat;
					$seoProductSavingPrice = '';
					$seoProductPrice       = $productPrice;
					$taxAmount             = RedshopHelperProduct::getProductTax($productId, $priceNoVAT, $userId);
				}
				else
				{
					$priceSaving = $productPriceExcludingVat - $discountPriceExcludingVat;

					// Calculate total price saving in percentage
					$priceSavingPercentage = ($priceSaving / $productPriceExcludingVat) * 100;

					// Only apply VAT if set to apply in config or tag
					if (intval($isApplyTax) && $priceSaving)
					{
						// Adding VAT in saving price
						$priceSaving += RedshopHelperProduct::getProductTax($productId, $priceSaving, $userId);
					}

					$productPriceIncludingVat = $productDiscountPriceTemp + $taxAmount;

					$oldPrice = productHelper::getInstance()->defaultAttributeDataPrice(
						$productId, $productPrice, $templateHtml, $userId, intval($isApplyTax), $attributes
					);

					$productDiscountPriceTemp = productHelper::getInstance()->defaultAttributeDataPrice(
						$productId, $productDiscountPriceTemp, $templateHtml, $userId, intval($isApplyTax), $attributes
					);

					$discountPrice = $productDiscountPriceTemp;
					$mainPrice     = $productDiscountPriceTemp;
					$productPrice  = $productDiscountPriceTemp;

					$priceNoVAT = productHelper::getInstance()->defaultAttributeDataPrice(
						$productId, $discountPriceExcludingVat, $templateHtml, $userId, 0, $attributes
					);

					$seoProductPrice       = $productDiscountPriceTemp;
					$seoProductSavingPrice = $priceSaving;

					$priceSavingLabel = JText::_('COM_REDSHOP_PRODUCT_PRICE_SAVING_LBL');
					$oldPriceLabel    = JText::_('COM_REDSHOP_PRODUCT_OLD_PRICE_LBL');
				}
			}
			else
			{
				$mainPrice = $productPrice;

				$productPrice = productHelper::getInstance()->defaultAttributeDataPrice(
					$productId, $productPrice, $templateHtml, $userId, intval($isApplyTax), $attributes
				);

				$discountPrice         = '';
				$priceSaving           = '';
				$priceSavingPercentage = '';
				$oldPrice              = '';
				$priceNoVAT            = $productPriceExcludingVat;
				$seoProductPrice       = $productPrice;
				$seoProductSavingPrice = '';
			}

			if ($taxAmount && intval($isApplyTax))
			{
				$productVatLabel = ' ' . JText::_('COM_REDSHOP_PRICE_INCLUDING_TAX');
			}
			else
			{
				$productVatLabel = ' ' . JText::_('COM_REDSHOP_PRICE_EXCLUDING_TAX');
			}

			$priceLabel = JText::_('COM_REDSHOP_PRODUCT_PRICE');
		}
		else
		{
			$seoProductPrice       = '';
			$seoProductSavingPrice = '';
			$discountPrice         = '';
			$oldPrice              = '';
			$priceSaving           = '';
			$priceSavingPercentage = '';
			$priceNoVAT            = '';
			$mainPrice             = '';
			$productPrice          = '';
			$priceExcludingVat     = '';
		}

		$productPrices['productPrice']                    = (float) $priceNoVAT;
		$productPrices['product_price']                   = (float) $productPrice;
		$productPrices['price_excluding_vat']             = (float) $priceExcludingVat;
		$productPrices['product_main_price']              = (float) $mainPrice;
		$productPrices['product_price_novat']             = (float) $priceNoVAT;
		$productPrices['product_price_saving']            = (float) $priceSaving;
		$productPrices['product_price_saving_percentage'] = (float) $priceSavingPercentage;
		$productPrices['product_price_saving_lbl']        = $priceSavingLabel;
		$productPrices['product_old_price']               = (float) $oldPrice;
		$productPrices['product_discount_price']          = (float) $discountPrice;
		$productPrices['seoProductSavingPrice']           = (float) $seoProductSavingPrice;
		$productPrices['seoProductPrice']                 = (float) $seoProductPrice;
		$productPrices['product_old_price_lbl']           = $oldPriceLabel;
		$productPrices['product_price_lbl']               = $priceLabel;
		$productPrices['product_vat_lbl']                 = $productVatLabel;
		$productPrices['productVat']                      = (float) $taxAmount;
		$productPrices['product_old_price_excl_vat']      = (float) $oldPriceExcludeVat;
		$productPrices['product_price_incl_vat']          = (float) $productPriceIncludingVat;

		return $productPrices;
	}

	/**
	 * Method for get product show price
	 *
	 * @param   integer  $productId     Product ID
	 * @param   string   $templateHtml  Template content
	 * @param   string   $seoTemplate   SEO template
	 * @param   int      $userId        User ID
	 * @param   boolean  $isRel         Is Rel
	 * @param   array    $attributes    Attributes
	 *
	 * @return mixed|string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getShowPrice($productId, $templateHtml, $seoTemplate = "", $userId = 0, $isRel = false, $attributes = array())
	{
		$price                        = '';
		$priceNoVat                   = '';
		$displayPriceDiscount         = '';
		$displayOldPrice              = '';
		$displayPriceSaving           = '';
		$displayPriceSavingPercentage = '';
		$displayPriceNoVAT            = '';
		$displayPriceWithVAT          = '';
		$priceSavingLabel             = '';
		$oldPriceLabel                = '';
		$vatLabel                     = '';
		$priceLabel                   = '';
		$seoProductPrice              = '';
		$seoProductSavingPrice        = '';
		$oldPriceNoVat                = '';

		$userId    = !$userId ? JFactory::getUser()->id : $userId;
		$relPrefix = !$isRel ? '' : 'rel';

		$defaultQuantity = productHelper::getInstance()->GetDefaultQuantity($productId, $templateHtml);
		$productPrices   = self::getNetPrice($productId, $userId, $defaultQuantity, $templateHtml, $attributes);

		if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
			|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))))
		{
			$price         = self::priceReplacement($productPrices['product_price'] * $defaultQuantity);
			$mainPrice     = self::priceReplacement($productPrices['product_main_price'] * $defaultQuantity);
			$oldPrice      = self::priceReplacement((float) $productPrices['product_old_price'] * $defaultQuantity);
			$priceSaving   = self::priceReplacement($productPrices['product_price_saving'] * $defaultQuantity);
			$discountPrice = self::priceReplacement($productPrices['product_discount_price'] * $defaultQuantity);
			$priceNoVAT    = self::priceReplacement($productPrices['product_price_novat'] * $defaultQuantity);
			$priceWithVAT  = self::priceReplacement($productPrices['product_price_incl_vat'] * $defaultQuantity);
			$oldPriceNoVat = self::priceReplacement($productPrices['product_old_price_excl_vat'] * $defaultQuantity);

			$isStockExists = RedshopHelperStockroom::isStockExists($productId);

			if ($isStockExists && strpos($templateHtml, "{" . $relPrefix . "product_price_table}") !== false)
			{
				$productPriceTable = RedshopHelperProduct::getProductQuantityPrice($productId, $userId);
				$templateHtml      = str_replace("{" . $relPrefix . "product_price_table}", $productPriceTable, $templateHtml);
			}

			$priceNoVat            = $productPrices['price_excluding_vat'];
			$seoProductPrice       = self::priceReplacement($productPrices['seoProductPrice'] * $defaultQuantity);
			$seoProductSavingPrice = self::priceReplacement((float) $productPrices['seoProductSavingPrice'] * $defaultQuantity);

			$oldPriceLabel    = $productPrices['product_old_price_lbl'];
			$priceSavingLabel = $productPrices['product_price_saving_lbl'];
			$priceLabel       = $productPrices['product_price_lbl'];
			$vatLabel         = $productPrices['product_vat_lbl'];

			$displayOldPrice      = $oldPrice;
			$displayPriceDiscount = $discountPrice;
			$displayPriceSaving   = $priceSaving;
			$displayPriceNoVAT    = $priceNoVAT;

			if ($productPrices['product_discount_price'])
			{
				$displayPriceDiscount = '<span id="display_product_discount_price' . $productId . '">' . $discountPrice . '</span>';
			}

			if ($productPrices['product_old_price'])
			{
				$displayOldPrice = '<span id="display_product_old_price' . $productId . '">' . $oldPrice . '</span>';
			}

			if ($productPrices['product_price_saving'])
			{
				$displayPriceSaving           = '<span id="display_product_saving_price' . $productId . '">' . $priceSaving . '</span>';
				$displayPriceSavingPercentage = '<span id="display_product_saving_price_percentage' . $productId . '">'
					. JText::sprintf('COM_REDSHOP_PRODUCT_PRICE_SAVING_PERCENTAGE_LBL', round($productPrices['product_price_saving_percentage']))
					. '%</span>';
			}

			if ($productPrices['product_price_novat'] != "")
			{
				$displayPriceNoVAT = '<span id="display_product_price_no_vat' . $productId . '">' . $priceNoVAT . '</span>';
			}

			if ($productPrices['product_price_incl_vat'] != "")
			{
				$displayPriceWithVAT = '<span id="product_price_incl_vat' . $productId . '">' . $priceWithVAT . '</span>';
			}
		}

		if (strpos($templateHtml, "{" . $relPrefix . "product_price_table}") !== false)
		{
			$templateHtml = str_replace("{" . $relPrefix . "product_price_table}", '', $templateHtml);
		}

		if ($seoTemplate != "")
		{
			$seoTemplate = str_replace("{" . $relPrefix . "saleprice}", $seoProductPrice, $seoTemplate);
			$seoTemplate = str_replace("{" . $relPrefix . "saving}", $seoProductSavingPrice, $seoTemplate);

			return $seoTemplate;
		}

		if (strpos($templateHtml, "{" . $relPrefix . "lowest_price}") !== false
			|| strpos($templateHtml, "{" . $relPrefix . "highest_price}") !== false)
		{
			$productPriceMinMax = productHelper::getInstance()->getProductMinMaxPrice($productId);

			if (strpos($templateHtml, "{" . $relPrefix . "lowest_price}") !== false)
			{
				if (!empty($productPriceMinMax['min']))
				{
					$productMinPrice = self::priceReplacement($productPriceMinMax['min'] * $defaultQuantity);

					$templateHtml = str_replace(
						"{" . $relPrefix . "lowest_price}",
						'<span id="produkt_kasse_hoejre_pris_indre' . $productId . '">' . $productMinPrice . '</span>',
						$templateHtml
					);
				}
				else
				{
					$templateHtml = str_replace(
						"{" . $relPrefix . "lowest_price}",
						'<span id="produkt_kasse_hoejre_pris_indre' . $productId . '">' . $price . '</span>',
						$templateHtml
					);
				}
			}

			if (strpos($templateHtml, "{" . $relPrefix . "highest_price}") !== false)
			{
				if (!empty($productPriceMinMax['min']))
				{
					$productMaxPrice = self::priceReplacement($productPriceMinMax['max'] * $defaultQuantity);

					$templateHtml = str_replace(
						"{" . $relPrefix . "highest_price}",
						'<span id="produkt_kasse_hoejre_pris_indre' . $productId . '">' . $productMaxPrice . '</span>',
						$templateHtml
					);
				}
				else
				{
					$templateHtml = str_replace(
						"{" . $relPrefix . "highest_price}",
						'<span id="produkt_kasse_hoejre_pris_indre' . $productId . '">' . $price . '</span>',
						$templateHtml
					);
				}
			}
		}

		$templateHtml = str_replace(
			"{" . $relPrefix . "product_price}",
			'<span id="produkt_kasse_hoejre_pris_indre' . $productId . '">' . $price . '</span>',
			$templateHtml
		);

		$templateHtml = str_replace("{" . $relPrefix . "price_excluding_vat}", $priceNoVat, $templateHtml);
		$templateHtml = str_replace("{" . $relPrefix . "product_discount_price}", $displayPriceDiscount, $templateHtml);

		if ($productPrices['product_price_saving'])
		{
			$templateHtml = str_replace("{" . $relPrefix . "product_price_saving}", $displayPriceSaving, $templateHtml);
			$templateHtml = str_replace("{" . $relPrefix . "product_price_saving_excl_vat}", $displayPriceSaving, $templateHtml);
			$templateHtml = str_replace("{" . $relPrefix . "product_price_saving_lbl}", $priceSavingLabel, $templateHtml);

			$templateHtml = str_replace("{" . $relPrefix . "product_price_saving_percentage}", $displayPriceSavingPercentage, $templateHtml);
		}
		else
		{
			$templateHtml = str_replace("{" . $relPrefix . "product_price_saving}", '', $templateHtml);
			$templateHtml = str_replace("{" . $relPrefix . "product_price_saving_lbl}", '', $templateHtml);

			$templateHtml = str_replace("{" . $relPrefix . "product_price_saving_percentage}", '', $templateHtml);
		}

		if ($productPrices['product_old_price'])
		{
			$pricePercentDiscount = 100 - ($productPrices['product_discount_price'] / $productPrices['product_old_price'] * 100);
			$templateHtml         = str_replace("{" . $relPrefix . "product_old_price}", $displayOldPrice, $templateHtml);
			$templateHtml         = str_replace("{" . $relPrefix . "product_old_price_lbl}", $oldPriceLabel, $templateHtml);
		}
		else
		{
			$templateHtml = str_replace("{" . $relPrefix . "product_old_price}", '', $templateHtml);
			$templateHtml = str_replace("{" . $relPrefix . "product_old_price_lbl}", '', $templateHtml);
		}

		$oldPriceNoVat = '<span id="display_product_old_price' . $productId . '">' . $oldPriceNoVat . '</span>';

		$templateHtml = str_replace("{" . $relPrefix . "product_old_price_excl_vat}", $oldPriceNoVat, $templateHtml);
		$templateHtml = str_replace("{" . $relPrefix . "product_price_novat}", $displayPriceNoVAT, $templateHtml);
		$templateHtml = str_replace("{" . $relPrefix . "product_price_incl_vat}", $displayPriceWithVAT, $templateHtml);
		$templateHtml = str_replace("{" . $relPrefix . "product_vat_lbl}", $vatLabel, $templateHtml);
		$templateHtml = str_replace("{" . $relPrefix . "product_price_lbl}", $priceLabel, $templateHtml);

		return $templateHtml;
	}
}
