<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Class Redshop Helper Product
 *
 * @since  1.5
 */
class RedshopHelperProduct
{
	/**
	 * Product info
	 *
	 * @var  array
	 */
	protected static $products = array();

	/**
	 * All product data
	 *
	 * @var  array
	 */
	protected static $allProducts = array();

	/**
	 * @var array  List of available product number
	 *
	 * @since  2.0.4
	 */
	protected static $productNumbers = array();

	/**
	 * @var array  List of available product number
	 *
	 * @since  2.0.6
	 */
	protected static $productPrices = array();

	/**
	 * @var array  List of  product special id
	 *
	 * @since  2.1.5
	 */
	protected static $productSpecialIds = array();

	/**
	 * @var array  List of  product date range
	 *
	 * @since  2.1.5
	 */
	protected static $productDateRange = array();

	/**
	 * Get all product information
	 * Warning: This method is loading all the products from DB. Which can resulting
	 * 			into memory issue. Use with caution.
	 * 			It is aimed to use in CLI version or for webservices.
	 *
	 * @return  array  Product Information array
	 */
	public static function getList()
	{
		if (empty(static::$allProducts))
		{
			$db    = JFactory::getDbo();
			$query = self::getMainProductQuery();
			$query->select(
				array(
						'p.product_name as text',
						'p.product_id as value'
					)
			);

			$db->setQuery($query);

			static::$allProducts = $db->loadObjectList('product_id');
		}

		return static::$allProducts;
	}

	/**
	 * Get product information base on list of Ids
	 *
	 * @param   array    $productIds  Product ids
	 * @param   int      $userId      User id
	 * @param   boolean  $setRelated  Is need to set related or not
	 *
	 * @return  array
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public static function getProductsByIds($productIds = array(), $userId = 0, $setRelated = true)
	{
		if (!$userId)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		$productIds = \Joomla\Utilities\ArrayHelper::toInteger($productIds);

		if (empty($productIds))
		{
			return array();
		}

		$results       = array();
		$newProductIds = array();

		foreach ($productIds as $productId)
		{
			$key = $productId . '.' . $userId;

			// Load from static cache if already exist.
			if (array_key_exists($key, static::$products))
			{
				$results[] = static::$products[$key];

				if ($setRelated)
				{
					self::setProductRelates(array($key => static::$products[$key]), $userId);
				}

				continue;
			}

			// Check if data is already loaded while getting list
			if (array_key_exists($productId, static::$allProducts))
			{
				static::$products[$key] = static::$allProducts[$productId];

				if ($setRelated)
				{
					self::setProductRelates(array($key => static::$products[$key]), $userId);
				}

				continue;
			}

			$newProductIds[] = $productId;
		}

		if (empty($newProductIds))
		{
			return $results;
		}

		// Otherwise load product info
		$db    = JFactory::getDbo();
		$query = self::getMainProductQuery(false, $userId);

		// Select product
		$query->where($db->qn('p.product_id') . ' IN (' . implode(',', $productIds) . ')');

		$items = (array) $db->setQuery($query)->loadObjectList();

		if (empty($items))
		{
			return $results;
		}

		foreach ($items as $item)
		{
			$key                    = $item->product_id . '.' . $userId;
			static::$products[$key] = $item;
			$results[]              = $item;

			if ($setRelated === true)
			{
				self::setProductRelates(array($key => static::$products[$key]), $userId);
			}
		}

		return $results;
	}

	/**
	 * Get product information
	 *
	 * @param   integer  $productId   Product id
	 * @param   integer  $userId      User id
	 * @param   boolean  $setRelated  Is need to set related or not
	 *
	 * @return  mixed
	 * @throws  Exception
	 */
	public static function getProductById($productId, $userId = 0, $setRelated = true)
	{
		if (!$userId)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		$key = $productId . '.' . $userId;

		if (!array_key_exists($key, static::$products))
		{
			// Check if data is already loaded while getting list
			if (array_key_exists($productId, static::$allProducts))
			{
				static::$products[$key] = static::$allProducts[$productId];
			}
			// Otherwise load product info
			else
			{
				$db    = JFactory::getDbo();
				$query = self::getMainProductQuery(false, $userId);

				// Select product
				$query->where($db->qn('p.product_id') . ' = ' . (int) $productId);

				$db->setQuery($query);
				static::$products[$key] = $db->loadObject();
			}

			if ($setRelated === true && static::$products[$key])
			{
				self::setProductRelates(array($key => static::$products[$key]), $userId);
			}
		}

		return static::$products[$key];
	}

	/**
	 * Get Main Product Query
	 *
	 * @param   bool|JDatabaseQuery  $query   Get query or false
	 * @param   int                  $userId  User id
	 *
	 * @return JDatabaseQuery
	 */
	public static function getMainProductQuery($query = false, $userId = 0)
	{
		$shopperGroupId = RedshopHelperUser::getShopperGroup($userId);
		$db = JFactory::getDbo();

		if (!$query)
		{
			$query = $db->getQuery(true);
		}

		$query->select(array('p.*', 'p.product_id'))
			->from($db->qn('#__redshop_product', 'p'));

		// Require condition
		$query->group($db->qn('p.product_id'));

		// Select price
		$query->select(
			array(
				'pp.price_id', $db->qn('pp.product_price', 'price_product_price'),
				$db->qn('pp.product_currency', 'price_product_currency'), $db->qn('pp.discount_price', 'price_discount_price'),
				$db->qn('pp.discount_start_date', 'price_discount_start_date'), $db->qn('pp.discount_end_date', 'price_discount_end_date')
			)
		)
			->leftJoin(
				$db->qn('#__redshop_product_price', 'pp')
				. ' ON p.product_id = pp.product_id AND ((pp.price_quantity_start <= 1 AND pp.price_quantity_end >= 1)'
				. ' OR (pp.price_quantity_start = 0 AND pp.price_quantity_end = 0)) AND pp.shopper_group_id = ' . (int) $shopperGroupId
			)
			->order('pp.price_quantity_start ASC');

		// Select category
		$query->select(array('pc.category_id'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON pc.product_id = p.product_id');

		// Getting cat_in_sefurl as main category id if it available
		$query->leftJoin(
			$db->qn('#__redshop_product_category_xref', 'pc3') . ' ON pc3.product_id = p.product_id AND pc3.category_id = p.cat_in_sefurl'
			)
			->leftJoin($db->qn('#__redshop_category', 'c3') . ' ON pc3.category_id = c3.id AND c3.published = 1');

		$subQuery = $db->getQuery(true)
			->select('GROUP_CONCAT(DISTINCT c2.id ORDER BY c2.id ASC SEPARATOR ' . $db->q(',') . ')')
			->from($db->qn('#__redshop_category', 'c2'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc2') . ' ON c2.id = pc2.category_id')
			->where('p.product_id = pc2.product_id')
			->where('((p.cat_in_sefurl != ' . $db->q('') . ' AND p.cat_in_sefurl != pc2.category_id) OR p.cat_in_sefurl = ' . $db->q('') . ')')
			->where('c2.published = 1');

		// In first position set main category id
		$query->select('CONCAT_WS(' . $db->q(',') . ', c3.id, (' . $subQuery . ')) AS categories');

		// Select media
		$query->select(array('media.media_alternate_text', 'media.media_id'))
			->leftJoin(
				$db->qn('#__redshop_media', 'media')
				. ' ON media.section_id = p.product_id AND media.media_section = ' . $db->q('product')
				. ' AND media.media_type = ' . $db->q('images') . ' AND media.media_name = p.product_full_image'
			);

		// Select ratings
		$subQuery = $db->getQuery(true)
			->select('COUNT(pr1.rating_id)')
			->from($db->qn('#__redshop_product_rating', 'pr1'))
			->where('pr1.product_id = p.product_id')
			->where('pr1.published = 1');

		$query->select('(' . $subQuery . ') AS count_rating');

		$subQuery = $db->getQuery(true)
			->select('SUM(pr2.user_rating)')
			->from($db->qn('#__redshop_product_rating', 'pr2'))
			->where('pr2.product_id = p.product_id')
			->where('pr2.published = 1');

		$query->select('(' . $subQuery . ') AS sum_rating');

		// Count Accessories
		$subQuery = $db->getQuery(true)
			->select('COUNT(pa.accessory_id)')
			->from($db->qn('#__redshop_product_accessory', 'pa'))
			->leftJoin($db->qn('#__redshop_product', 'parent_product') . ' ON parent_product.product_id = pa.child_product_id')
			->where('pa.product_id = p.product_id')
			->where('parent_product.published = 1');

		$query->select('(' . $subQuery . ') AS total_accessories');

		// Count child products
		$subQuery = $db->getQuery(true)
			->select('COUNT(child.product_id) AS count_child_products, child.product_parent_id')
			->from($db->qn('#__redshop_product', 'child'))
			->where('child.product_parent_id > 0')
			->where('child.published = 1')
			->group('child.product_parent_id');

		$query->select('child_product_table.count_child_products')
			->leftJoin('(' . $subQuery . ') AS child_product_table ON child_product_table.product_parent_id = p.product_id');

		// Sum quantity
		if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
		{
			$subQuery = $db->getQuery(true)
				->select('SUM(psx.quantity)')
				->from($db->qn('#__redshop_product_stockroom_xref', 'psx'))
				->where('psx.product_id = p.product_id')
				->where('psx.quantity >= 0')
				->where('psx.stockroom_id > 0');

			$query->select('(' . $subQuery . ') AS sum_quanity');
		}

		return $query;
	}

	/**
	 * Set product relates
	 *
	 * @param   array  $products  Products
	 * @param   int    $userId    User id
	 *
	 * @return  void
	 */
	public static function setProductRelates($products, $userId = 0)
	{
		if (empty($products) || !is_array($products))
		{
			return;
		}

		$userId = !$userId ? JFactory::getUser()->id : $userId;

		$getAttributeKeys  = array();
		$getExtraFieldKeys = array();

		foreach ($products as $product)
		{
			if (!isset($product->product_id))
			{
				continue;
			}

			$key = $product->product_id . '.' . $userId;

			if (!array_key_exists($key, static::$products))
			{
				continue;
			}

			static::$products[$product->product_id . '.' . $userId]->categories  = explode(',', $product->categories);

			// If this product not has attributes yet. Put this in array of product which need to get attributes.
			if (!isset(static::$products[$key]->attributes))
			{
				static::$products[$key]->attributes  = array();
				$getAttributeKeys[] = $product->product_id;
			}

			// If this product not has extra fields yet. Put this in array of product which need to get extra fields.
			if (!isset(static::$products[$key]->extraFields))
			{
				static::$products[$key]->extraFields  = array();
				$getExtraFieldKeys[] = $product->product_id;
			}
		}

		self::setProductAttributes($getAttributeKeys, $userId);
		self::setProductExtraFields($getExtraFieldKeys, $userId);
	}

	/**
	 * Set product array
	 *
	 * @param   array  $products  Array product/s values
	 *
	 * @return void
	 */
	public static function setProduct($products)
	{
		static::$products = $products + static::$products;
		self::setProductRelates($products);
	}

	/**
	 * Replace Accessory Data
	 *
	 * @param   int     $productId  Product id
	 * @param   array   $accessory  Accessory list
	 * @param   int     $userId     User id
	 * @param   string  $uniqueId   Unique id
	 *
	 * @return mixed
	 *
	 * @since   2.0.3
	 */
	public static function replaceAccessoryData($productId = 0, $accessory = array(), $userId = 0, $uniqueId = "")
	{
		$totalAccessory = count($accessory);
		$accessoryList  = "";

		if (!$totalAccessory)
		{
			return '';
		}

		$accessoryList .= "<tr><th>" . JText::_('COM_REDSHOP_ACCESSORY_PRODUCT') . "</th></tr>";

		for ($a = 0, $an = count($accessory); $a < $an; $a++)
		{
			$acId   = $accessory[$a]->child_product_id;
			$cpData = Redshop::product((int) $acId);

			$accessoryName = RedshopHelperUtility::maxChars(
				$accessory[$a]->product_name,
				Redshop::getConfig()->getInt('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
				Redshop::getConfig()->getString('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
			);

			// Get accessory final price with VAT rules
			$accessoryPriceList = \Redshop\Product\Accessory::getPrice(
				$productId, $accessory[$a]->newaccessory_price, $accessory[$a]->accessory_main_price
			);

			$accessoryPrice = $accessoryPriceList[0];

			$accessoryPriceWithoutvat = \Redshop\Product\Accessory::getPrice(
				$productId, $accessory[$a]->newaccessory_price,
				$accessory[$a]->accessory_main_price, 1
			);
			$accessoryPriceWithoutVat = $accessoryPriceWithoutvat[0];
			$accessoryPriceVat        = $accessoryPrice - $accessoryPriceWithoutVat;

			$commonid = $productId . '_' . $accessory[$a]->accessory_id . $uniqueId;

			// Accessory attribute  Start
			$attributesSet = array();

			if ($cpData->attribute_set_id > 0)
			{
				$attributesSet = RedshopHelperProduct_Attribute::getProductAttribute(0, $cpData->attribute_set_id);
			}

			$attributes = RedshopHelperProduct_Attribute::getProductAttribute($acId);
			$attributes = array_merge($attributes, $attributesSet);

			$accessoryCheckbox = "<input onClick='calculateOfflineTotalPrice(\"" . $uniqueId . "\");' type='checkbox' name='accessory_id_"
				. $productId . $uniqueId . "[]' totalattributs='" . count($attributes) . "' accessoryprice='"
				. $accessoryPrice . "' accessorypricevat='" . $accessoryPriceVat . "' id='accessory_id_"
				. $commonid . "' value='" . $accessory[$a]->accessory_id . "' />";

			$accessoryList .= "<tr><td>" . $accessoryCheckbox . "&nbsp;" . $accessoryName . ' : '
				. RedshopHelperProductPrice::formattedPrice($accessoryPrice) . "</td></tr>";

			$accessoryList .= RedshopHelperProductTag::replaceAttributeData(
				$productId, $accessory[$a]->accessory_id, $attributes, $userId, $uniqueId
			);
		}

		return $accessoryList;
	}

	/**
	 * Replace Attribute Data
	 *
	 * @param   int     $productId    Product id
	 * @param   int     $accessoryId  Accessory id
	 * @param   array   $attributes   Attribute list
	 * @param   int     $userId       User id
	 * @param   string  $uniqueId     Unique id
	 *
	 * @return  mixed
	 *
	 * @since   2.0.3
	 *
	 * @deprecated  2.1.0
	 */
	public static function replaceAttributeData($productId = 0, $accessoryId = 0, $attributes = array(), $userId = 0, $uniqueId = "")
	{
		return RedshopHelperProductTag::replaceAttributeData($productId, $accessoryId, $attributes, $userId, $uniqueId);
	}

	/**
	 * Replace Accessory Data
	 *
	 * @param   int     $productId  Product id
	 * @param   int     $userId     User id
	 * @param   string  $uniqueId   Unique id
	 *
	 * @return mixed
	 *
	 * @since   2.0.3
	 */
	public static function replaceWrapperData($productId = 0, $userId = 0, $uniqueId = "")
	{
		$wrapperList   = '';

		$wrapper = self::getWrapper($productId, 0, 1);

		if (empty($wrapper))
		{
			return '';
		}

		$wArray = array();
		$wArray[0] = new stdClass;
		$wArray[0]->wrapper_id = 0;
		$wArray[0]->wrapper_name = JText::_('COM_REDSHOP_SELECT');
		$commonId = $productId . $uniqueId;

		for ($i = 0, $in = count($wrapper); $i < $in; $i++)
		{
			$wrapperVat = 0;

			if ($wrapper[$i]->wrapper_price > 0)
			{
				$wrapperVat = self::getProductTax($productId, $wrapper[$i]->wrapper_price, $userId);
			}

			$wrapper[$i]->wrapper_price += $wrapperVat;
			$wrapper[$i]->wrapper_name   = $wrapper [$i]->wrapper_name . " ("
				. RedshopHelperProductPrice::formattedPrice($wrapper[$i]->wrapper_price) . ")";

			$wrapperList .= "<input type='hidden' id='wprice_" . $commonId . "_"
				. $wrapper [$i]->wrapper_id . "' value='" . $wrapper[$i]->wrapper_price . "' />";
			$wrapperList .= "<input type='hidden' id='wprice_tax_" . $commonId . "_"
				. $wrapper [$i]->wrapper_id . "' value='" . $wrapperVat . "' />";
		}

		$wrapper = array_merge($wArray, $wrapper);

		$lists['wrapper_id'] = JHtml::_(
			'select.genericlist',
			$wrapper, 'wrapper_id_' . $commonId . '[]',
			'id="wrapper_id_' . $commonId . '" class="inputbox" onchange="calculateOfflineTotalPrice(\'' . $uniqueId . '\');" ',
			'wrapper_id', 'wrapper_name',
			0
		);

		$wrapperList .= "<tr><td>" . JText::_('COM_REDSHOP_WRAPPER') . " : " . $lists ['wrapper_id'] . "</td></tr>";

		return $wrapperList;
	}

	/**
	 * Get product item info
	 *
	 * @param   integer  $productId        Product id
	 * @param   integer  $quantity         Product quantity
	 * @param   string   $uniqueId         Unique id
	 * @param   integer  $userId           User id
	 * @param   integer  $newProductPrice  New product price
	 *
	 * @return  mixed
	 *
	 * @since   2.0.3
	 *
	 * @throws  Exception
	 */
	public static function getProductItemInfo($productId = 0, $quantity = 1, $uniqueId = "", $userId = 0, $newProductPrice = 0)
	{
		$wrapperList = "";
		$accessoryList = "";
		$attributeList = "";
		$productUserField = "";
		$productPriceExclVat = 0;
		$productTax = 0;

		if ($productId)
		{
			$productInfo = Redshop::product((int) $productId);

			if ($newProductPrice != 0)
			{
				$productPriceExclVat = $newProductPrice;
				$productTax          = self::getProductTax($productId, $newProductPrice, $userId);
			}

			else
			{
				$productArr          = RedshopHelperProductPrice::getNetPrice($productId, $userId, $quantity);
				$productPriceExclVat = $productArr['productPrice'];
				$productTax          = $productArr['productVat'];

				// Attribute start
				$attributesSet = array();

				if ($productInfo->attribute_set_id > 0)
				{
					$attributesSet = RedshopHelperProduct_Attribute::getProductAttribute(0, $productInfo->attribute_set_id, 0, 1);
				}

				$attributes = RedshopHelperProduct_Attribute::getProductAttribute($productId);
				$attributes = array_merge($attributes, $attributesSet);
				$attributeList = RedshopHelperProductTag::replaceAttributeData($productId, 0, $attributes, $userId, $uniqueId);

				// Accessory start
				$accessory     = RedshopHelperAccessory::getProductAccessories(0, $productId);
				$accessoryList = self::replaceAccessoryData($productId, $accessory, $userId, $uniqueId);

				// Wrapper selection box generate
				$wrapperList      = self::replaceWrapperData($productId, $userId, $uniqueId);
				$productUserField = self::replaceUserField($productId, $productInfo->product_template, $uniqueId);
			}
		}

		$productPrice = $productPriceExclVat + $productTax;
		$total_price  = $productPrice * $quantity;
		$totalTax     = $productTax * $quantity;

		$displayRespoce = "";
		$displayRespoce .= "<div id='product_price_excl_vat'>" . $productPriceExclVat . "</div>";
		$displayRespoce .= "<div id='product_tax'>" . $productTax . "</div>";
		$displayRespoce .= "<div id='product_price'>" . $productPrice . "</div>";
		$displayRespoce .= "<div id='total_price'>" . $total_price . "</div>";
		$displayRespoce .= "<div id='total_tax'>" . $totalTax . "</div>";
		$displayRespoce .= "<div id='attblock'><table>" . $attributeList . "</table></div>";
		$displayRespoce .= "<div id='productuserfield'><table>" . $productUserField . "</table></div>";
		$displayRespoce .= "<div id='accessoryblock'><table>" . $accessoryList . "</table></div>";
		$displayRespoce .= "<div id='noteblock'>" . $wrapperList . "</div>";

		return $displayRespoce;
	}

	/**
	 * Replace Shipping method
	 *
	 * @param   array  $data              Data
	 * @param   int    $shippUsersInfoId  Shipping User info id
	 * @param   int    $shippingRateId    Shipping rate id
	 *
	 * @return mixed
	 *
	 * @since   2.0.3
	 */
	public static function replaceShippingMethod($data = array(), $shippUsersInfoId = 0, $shippingRateId = 0)
	{
		if (!$shippUsersInfoId)
		{
			return '<div class="shipnotice">' . JText::_('COM_REDSHOP_FILL_SHIPPING_ADDRESS') . '</div>';
		}

		$language = JFactory::getLanguage();
		$shippingMethod = RedshopHelperOrder::getShippingMethodInfo();

		JPluginHelper::importPlugin('redshop_shipping');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$shippingRate = $dispatcher->trigger('onListRates', array(&$data));

		$rateArr = array();
		$r = 0;

		for ($s = 0, $sn = count($shippingMethod); $s < $sn; $s++)
		{
			if (isset($shippingRate[$s]) === false)
			{
				continue;
			}

			$rate = $shippingRate[$s];
			$extension = 'plg_redshop_shipping_' . strtolower($shippingMethod[$s]->element);
			$language->load($extension, JPATH_ADMINISTRATOR);
			$rs = $shippingMethod[$s];

			if (count($rate) > 0)
			{
				for ($i = 0, $in = count($rate); $i < $in; $i++)
				{
					$displayrate = ($rate[$i]->rate > 0) ? " (" . RedshopHelperProductPrice::formattedPrice($rate[$i]->rate) . " )" : "";
					$rateArr[$r] = new stdClass;
					$rateArr[$r]->text = strip_tags(JText::_($rs->name) . " - " . $rate[$i]->text . $displayrate);
					$rateArr[$r]->value = $rate[$i]->value;
					$r++;
				}
			}
		}

		if (empty($rateArr))
		{
			return JText::_('COM_REDSHOP_NO_SHIPPING_METHODS_TO_DISPLAY');
		}

		if (!$shippingRateId)
		{
			$shippingRateId = $rateArr[0]->value;
		}

		return JHtml::_(
			'select.genericlist',
			$rateArr,
			'shipping_rate_id',
			'class="inputbox" onchange="calculateOfflineShipping();" ',
			'value',
			'text',
			$shippingRateId
		);
	}

	/**
	 * Redesign product item
	 *
	 * @param   array  $post  Data
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 *
	 * @deprecated  2.1.0
	 */
	public static function redesignProductItem($post = array())
	{
		return Redshop\Order\Helper::redesignProductItem($post);
	}

	/**
	 * Replace User Field
	 *
	 * @param   int     $productId   Product id
	 * @param   int     $templateId  Template id
	 * @param   string  $uniqueId    Unique id
	 *
	 * @return  mixed
	 *
	 * @since   2.0.3
	 *
	 * @throws  Exception
	 */
	public static function replaceUserField($productId = 0, $templateId = 0, $uniqueId = "")
	{
		$templateDesc  = RedshopHelperTemplate::getTemplate("product", $templateId);
		$returnArr     = self::getProductUserfieldFromTemplate($templateDesc[0]->template_desc);
		$commonId      = $productId . $uniqueId;

		if (empty($returnArr[1]))
		{
			return '';
		}

		$productUserFields = "<table>";

		foreach ($returnArr[1] as $index => $return)
		{
			$resultArr = RedshopHelperExtrafields::listAllUserFields(
				$return, RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, "", $commonId
			);
			$hiddenArr = RedshopHelperExtrafields::listAllUserFields(
				$return, RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, "hidden", $commonId
			);

			if (!empty($resultArr[0]))
			{
				$productUserFields .= "<tr><td>" . $resultArr[0] . "</td><td>" . $resultArr[1] . $hiddenArr[1] . "</td></tr>";
			}
		}

		return $productUserFields . "</table>";
	}

	/**
	 * Insert Product user field
	 *
	 * @param   int     $fieldId      Field id
	 * @param   int     $orderItemId  Order item id
	 * @param   int     $sectionId    Section id
	 * @param   string  $value        Unique id
	 *
	 * @return  boolean
	 *
	 * @since   2.0.3
	 */
	public static function insertProductUserField($fieldId = 0, $orderItemId = 0, $sectionId = 12, $value = '')
	{
		$db      = JFactory::getDbo();
		$columns = array('fieldid', 'data_txt', 'itemid', 'section');
		$values  = array($db->q((int) $fieldId), $db->q($value), $db->q((int) $orderItemId), $db->q((int) $sectionId));

		$query = $db->getQuery(true)
			->insert($db->qn('#__redshop_fields_data'))
			->columns($db->qn($columns))
			->values(implode(',', $values));

		return $db->setQuery($query)->execute();
	}

	/**
	 * Get product by sort list
	 *
	 * @return array
	 *
	 * @since   2.0.3
	 */
	public static function getProductsSortByList()
	{
		$productData = array();
		$productData[0] = new stdClass;
		$productData[0]->value = "0";
		$productData[0]->text = JText::_('COM_REDSHOP_SELECT_PUBLISHED');

		$productData[1] = new stdClass;
		$productData[1]->value = "p.published";
		$productData[1]->text = JText::_('COM_REDSHOP_PRODUCT_PUBLISHED');

		$productData[2] = new stdClass;
		$productData[2]->value = "p.unpublished";
		$productData[2]->text = JText::_('COM_REDSHOP_PRODUCT_UNPUBLISHED');

		$productData[3] = new stdClass;
		$productData[3]->value = "p.product_on_sale";
		$productData[3]->text = JText::_('COM_REDSHOP_PRODUCT_ON_SALE');

		$productData[4] = new stdClass;
		$productData[4]->value = "p.product_not_on_sale";
		$productData[4]->text = JText::_('COM_REDSHOP_PRODUCT_NOT_ON_SALE');

		$productData[5] = new stdClass;
		$productData[5]->value = "p.product_special";
		$productData[5]->text = JText::_('COM_REDSHOP_PRODUCT_SPECIAL');

		$productData[6] = new stdClass;
		$productData[6]->value = "p.expired";
		$productData[6]->text = JText::_('COM_REDSHOP_PRODUCT_EXPIRED');

		$productData[7] = new stdClass;
		$productData[7]->value = "p.not_for_sale";
		$productData[7]->text = JText::_('COM_REDSHOP_PRODUCT_NOT_FOR_SALE');

		$productData[8] = new stdClass;
		$productData[8]->value = "p.sold_out";
		$productData[8]->text = JText::_('COM_REDSHOP_PRODUCT_SOLD_OUT');

		return $productData;
	}

	/**
	 * Method for get all payment method of this product set in backend
	 *
	 * @param   integer  $productId  If exist.
	 *
	 * @return  array            List of payment method
	 *
	 * @since   2.1.0
	 */
	public static function getAllAvailableProductPayment($productId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('payment_id'))
			->from($db->qn('#__redshop_product_payment_xref'));

		if ($productId)
		{
			$query->where($db->qn('product_id') . ' = ' . $db->quote($productId));
		}

		// Set the query and load the result.
		return $db->setQuery($query)->loadColumn();
	}

	/**
	 * Method for get all product number exist in system
	 *
	 * @param   int  $productId  If exist. Exclude product number from this product Id from list
	 *
	 * @return  array            List of product number
	 *
	 * @since   2.0.4
	 */
	public static function getAllAvailableProductNumber($productId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('product_number'))
			->from($db->qn('#__redshop_product'));

		if ($productId)
		{
			$query->where($db->qn('product_id') . ' <> ' . $db->quote($productId));
		}

		// Set the query and load the result.
		return $db->setQuery($query)->loadColumn();
	}

	/**
	 * Get Layout product quantity price
	 *
	 * @param   int  $productId  Product Id
	 * @param   int  $userId     User Id
	 *
	 * @return  mixed  Redshop Layout
	 *
	 * @since   2.0.5
	 */
	public static function getProductQuantityPrice($productId, $userId)
	{
		$db      = JFactory::getDbo();
		$userArr = JFactory::getSession()->get('rs_user');

		if (empty($userArr))
		{
			RedshopHelperUser::createUserSession($userId);
		}

		$shopperGroupId = RedshopHelperUser::getShopperGroup($userId);

		if ($userId)
		{
			$query = $db->getQuery(true)
				->select('p.*')
				->from($db->qn('#__redshop_users_info', 'u'))
				->leftJoin($db->qn('#__redshop_product_price', 'p') . ' ON ' . $db->qn('u.shopper_group_id') . ' = ' . $db->qn('p.shopper_group_id'))
				->where($db->qn('p.product_id') . ' = ' . $db->q((int) $productId))
				->where($db->qn('u.user_id') . ' = ' . $db->q((int) $userId))
				->where($db->qn('u.address_type') . ' = ' . $db->q('BT'))
				->order($db->qn('p.price_quantity_start') . ' ASC');
		}
		else
		{
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_product_price'))
				->where($db->qn('product_id') . ' = ' . $db->q((int) $productId))
				->where($db->qn('shopper_group_id') . ' = ' . $db->q((int) $shopperGroupId))
				->order($db->qn('price_quantity_start') . ' ASC');
		}

		$result = $db->setQuery($query)->loadObjectList();

		if (empty($result))
		{
			return '';
		}

		return RedshopLayoutHelper::render(
			'product.product_price_table',
			array(
					'result'    => $result,
					'productId' => $productId,
					'userId'    => $userId
				),
			'',
			array(
					'component' => 'com_redshop'
				)
		);
	}

	/**
	 * Method for get product tax
	 *
	 * @param   integer  $productId     Product Id
	 * @param   float    $productPrice  Product price
	 * @param   integer  $userId        User ID
	 * @param   integer  $taxExempt     Tax exempt
	 *
	 * @return  integer
	 *
	 * @since   2.0.6
	 */
	public static function getProductTax($productId = 0, $productPrice = 0.0, $userId = 0, $taxExempt = 0)
	{
		$redshopUser = JFactory::getSession()->get('rs_user');
		$app         = JFactory::getApplication();

		if ($userId == 0 && !$app->isClient('administrator'))
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}
		else
		{
			$userId = $app->input->getInt('user_id', 0);
		}

		$productInfor = $productId != 0 ? self::getProductById($productId) : array();
		$productTax   = 0;
		$redshopUser  = empty($redshopUser) ? array('rs_is_user_login' => 0) : $redshopUser;

		if ($redshopUser['rs_is_user_login'] == 0 && $userId != 0)
		{
			RedshopHelperUser::createUserSession($userId);
		}

		$vatRateData = RedshopHelperTax::getVatRates($productId, $userId);
		$taxRate     = !empty($vatRateData) ? $vatRateData->tax_rate : 0;

		$productPrice = $productPrice <= 0 && isset($productInfor->product_price) ? $productInfor->product_price : $productPrice;
		$productPrice = RedshopHelperProductPrice::priceRound($productPrice);

		if ($taxExempt)
		{
			return $productPrice * $taxRate;
		}

		if (!$taxRate)
		{
			return RedshopHelperProductPrice::priceRound($productTax);
		}

		if (!$userId)
		{
			$productTax = $productPrice * $taxRate;
		}
		else
		{
			$userInformation = RedshopHelperUser::getUserInformation($userId);

			if (null === $userInformation || $userInformation->requesting_tax_exempt !== 1 || !$userInformation->tax_exempt_approved)
			{
				$productTax = $productPrice * $taxRate;
			}
		}

		return RedshopHelperProductPrice::priceRound($productTax);
	}

	/**
	 * Get Product Prices
	 *
	 * @param   int  $productId  Product id
	 * @param   int  $userId     User id
	 * @param   int  $quantity   Quantity
	 *
	 * @return  object           Product price object
	 *
	 * @since   2.0.6
	 */
	public static function getProductPrices($productId, $userId, $quantity = 1)
	{
		$key = $productId . '_' . $userId . '_' . $quantity;

		if (array_key_exists($key, self::$productPrices))
		{
			return self::$productPrices[$key];
		}

		$userArr = JFactory::getSession()->get('rs_user');
		$result  = null;

		if (empty($userArr))
		{
			$userArr = RedshopHelperUser::createUserSession($userId);
		}

		$shopperGroupId = $userArr['rs_user_shopperGroup'];

		if ($quantity != 1)
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select(
					$db->qn(array(
						'p.price_id', 'p.product_price', 'p.product_currency', 'p.discount_price', 'p.discount_start_date', 'p.discount_end_date'
						)
					)
				)
				->from($db->qn('#__redshop_product_price', 'p'));

			if ($userId)
			{
				$query->leftJoin($db->qn('#__redshop_users_info', 'u') . ' ON u.shopper_group_id = p.shopper_group_id')
					->where('u.user_id = ' . (int) $userId)
					->where('u.address_type = ' . $db->quote('BT'));
			}
			else
			{
				$query->where('p.shopper_group_id = ' . (int) $shopperGroupId);
			}

			$query->where('p.product_id = ' . (int) $productId)
				->where(
					'((p.price_quantity_start <= ' . (int) $quantity . ' AND p.price_quantity_end >= '
					. (int) $quantity . ') OR (p.price_quantity_start = 0 AND p.price_quantity_end = 0))'
				)
				->order('p.price_quantity_start ASC');

			$result = $db->setQuery($query)->loadObject();
		}
		else
		{
			$productData = self::getProductById($productId, $userId);

			if (null !== $productData && isset($productData->price_id))
			{
				$result = new stdClass;
				$result->price_id            = $productData->price_id;
				$result->product_price       = $productData->price_product_price;
				$result->discount_price      = $productData->price_discount_price;
				$result->product_currency    = $productData->price_product_currency;
				$result->discount_start_date = $productData->price_discount_start_date;
				$result->discount_end_date   = $productData->price_discount_end_date;
			}
		}

		if (!empty($result) && $result->discount_price != 0
			&& $result->discount_start_date != 0 && $result->discount_end_date != 0
			&& $result->discount_start_date <= time()
			&& $result->discount_end_date >= time()
			&& $result->discount_price < $result->product_price)
		{
			$result->product_price = $result->discount_price;
		}

		self::$productPrices[$key] = $result;

		return self::$productPrices[$key];
	}

	/**
	 * Method for set product attributes
	 *
	 * @param   array    $getAttributeKeys  Attributes key
	 * @param   integer  $userId            Current user ID
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public static function setProductAttributes($getAttributeKeys = array(), $userId = 0)
	{
		if (empty($getAttributeKeys))
		{
			return;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('a.attribute_id', 'value'))
			->select($db->qn('a.attribute_name', 'text'))
			->select('a.*')
			->select($db->qn('ast.attribute_set_name'))
			->select($db->qn('ast.published', 'attribute_set_published'))
			->from($db->qn('#__redshop_product_attribute', 'a'))
			->leftJoin(
				$db->qn('#__redshop_attribute_set', 'ast') . ' ON ' . $db->qn('ast.attribute_set_id') . ' = ' . $db->qn('a.attribute_set_id')
			)
			->where($db->qn('a.attribute_name') . ' != ' . $db->quote(''))
			->where($db->qn('a.attribute_published') . ' = 1')
			->where($db->qn('a.product_id') . ' IN (' . implode(',', $getAttributeKeys) . ')')
			->order($db->qn('a.ordering') . ' ASC');

		$attributes = $db->setQuery($query)->loadObjectList();

		if (empty($attributes))
		{
			return;
		}

		foreach ($attributes as $attribute)
		{
			$key = $attribute->product_id . '.' . $userId;

			static::$products[$key]->attributes[$attribute->attribute_id]             = $attribute;
			static::$products[$key]->attributes[$attribute->attribute_id]->properties = array();
		}

		$query->clear()
			->select($db->qn('ap.property_id', 'value'))
			->select($db->qn('ap.property_name', 'text'))
			->select('ap.*')
			->select('a.attribute_name')
			->select('a.attribute_id')
			->select('a.product_id')
			->select('a.attribute_set_id')
			->from($db->qn('#__redshop_product_attribute_property', 'ap'))
			->leftJoin(
				$db->qn('#__redshop_product_attribute', 'a') . ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
			)
			->where($db->qn('a.product_id') . ' IN (' . implode(',', $getAttributeKeys) . ')')
			->where($db->qn('ap.property_published') . ' = 1')
			->where($db->qn('a.attribute_published') . ' = 1')
			->where($db->qn('a.attribute_name') . ' != ' . $db->quote(''))
			->order($db->qn('ap.ordering') . ' ASC');

		$properties = $db->setQuery($query)->loadObjectList();

		if (empty($properties))
		{
			return;
		}

		foreach ($properties as $property)
		{
			$key = $property->product_id . '.' . $userId;

			static::$products[$key]->attributes[$property->attribute_id]->properties[$property->property_id] = $property;
		}
	}

	/**
	 * Method for set product extra fields
	 *
	 * @param   array    $getExtraFieldKeys  Attributes key
	 * @param   integer  $userId             Current user ID
	 *
	 * @return  void
	 *
	 * @since   2.1.0
	 */
	public static function setProductExtraFields($getExtraFieldKeys = array(), $userId = 0)
	{
		if (empty($getExtraFieldKeys))
		{
			return;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('fd.*')
			->select($db->qn('f.title'))
			->from($db->qn('#__redshop_fields_data', 'fd') . ' FORCE INDEX(' . $db->qn('#__field_data_common') . ')')
			->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('fd.fieldid') . ' = ' . $db->qn('f.id'))
			->where($db->qn('fd.itemid') . ' IN (' . implode(',', $getExtraFieldKeys) . ')')
			->where($db->qn('fd.section') . ' = 1');

		$extraFields = $db->setQuery($query)->loadObjectList();

		if (empty($extraFields))
		{
			return;
		}

		foreach ($extraFields as $extraField)
		{
			$key = $extraField->itemid . '.' . $userId;

			static::$products[$key]->extraFields[$extraField->fieldid] = $extraField;
		}
	}

	/**
	 * Method for get child products of specific product
	 *
	 * @param   integer  $productId  Product ID
	 *
	 * @return  array
	 * @since   2.1.0
	 */
	public static function getChildProduct($productId = 0)
	{
		$childProducts = RedshopEntityProduct::getInstance($productId)->getChildProducts();

		if ($childProducts->isEmpty())
		{
			return array();
		}

		$results = array();

		foreach ($childProducts->getAll() as $child)
		{
			/** @var  RedshopEntityProduct $child */
			$results[] = $child->getItem();
		}

		return $results;
	}

	/**
	 * Method get videos product
	 *
	 * @param   integer  $product_id
	 * @param   array    $attributes
	 * @param   object   $attribute_template
	 * @param   string   $media_type
	 *
	 * @return  mixed
	 * @since   2.1.3
	 */
	public static function getVideosProduct($product_id, $attributes, $attribute_template, $media_type = 'youtube')
	{
		$media_product_videos = RedshopHelperMedia::getAdditionMediaImage($product_id, "product", "$media_type");

		if (count($attributes) > 0 && count($attribute_template) > 0)
		{
			for ($a = 0, $an = count($attributes); $a < $an; $a++)
			{
				$selectedId = array();
				$property   = RedshopHelperProduct_Attribute::getAttributeProperties(0, $attributes[$a]->attribute_id);

				if ($attributes[$a]->text != "" && count($property) > 0)
				{
					for ($i = 0, $in = count($property); $i < $in; $i++)
					{
						if ($property[$i]->setdefault_selected)
						{
							$media_property_videos = RedshopHelperMedia::getAdditionMediaImage($property[$i]->property_id, "property", $media_type);
							$selectedId[]          = $property[$i]->property_id;
						}
					}

					if (count($selectedId) > 0)
					{
						$selectedpropertyId = $selectedId[count($selectedId) - 1];
						$subproperty        = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $selectedpropertyId);
						$selectedId         = array();

						for ($sp = 0, $c = count($subproperty); $sp < $c; $sp++)
						{
							if ($subproperty[$sp]->setdefault_selected)
							{
								$media_subproperty_videos = RedshopHelperMedia::getAdditionMediaImage($subproperty[$sp]->subattribute_color_id, "subproperty", $media_type);
								$selectedId[]             = $subproperty[$sp]->subattribute_color_id;
							}
						}
					}
				}
			}

		}

		$media_videos = array();

		if (!empty($media_subproperty_videos))
		{
			$media_videos = $media_subproperty_videos;
		}
		elseif (!empty($media_property_videos))
		{
			$media_videos = $media_property_videos;
		}
		elseif (!empty($media_product_videos))
		{
			$media_videos = $media_product_videos;
		}

		return $media_videos;
	}

	/**
	 * Method statistics rating product
	 *
	 * @param   integer  $product_id
	 *
	 * @return  mixed
	 * @since   2.1.4
	 */
	public static function statisticsRatingProduct($productId)
	{
		$db = JFactory::getDbo();

		$totalRating = $db->getQuery(true)
			->select('count(rating_id)')
			->from($db->qn('#__redshop_product_rating'))
			->where("product_id = $productId");

		$query = $db->getQuery(true)
			->select(array('user_rating', 'count(user_rating) as count'))
			->select('FORMAT(count(user_rating) / (' . $totalRating . ') * 100, 0) AS percent')
			->from('#__redshop_product_rating')
			->where($db->qn('product_id') . ' = ' . $db->q($productId))
			->where($db->qn('published') . ' = 1')
			->group($db->qn(array('product_id', 'user_rating')));

		return $db->setQuery($query)->loadObjectList();
	}

	public static function replaceProductSubCategory(&$templateDesc, $category)
	{
		if (strpos($templateDesc, "{product_loop_start}") !== false && strpos($templateDesc, "{product_loop_end}") !== false)
		{
			$templateD1        = explode("{product_loop_start}", $templateDesc);
			$templateD2        = explode("{product_loop_end}", $templateD1 [1]);
			$templateProduct   = $templateD2 [0];
			$categoryId        = $category->id;
			$listSectionFields = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);
			$products          = RedshopHelperCategory::getCategoryProductList($categoryId);
			$input             = JFactory::getApplication()->input;
			$itemId            = $input->getInt('Itemid');
			$slide             = $input->getInt('ajaxslide', null);
			$limitProduct      = count($products);

			JPluginHelper::importPlugin('redshop_product');
			JPluginHelper::importPlugin('redshop_product_type');
			$dispatcher = RedshopHelperUtility::getDispatcher();

			$attributeTemplate = \Redshop\Template\Helper::getAttribute($templateProduct);

			$extraFieldName                = Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);
			$extraFieldsForCurrentTemplate = RedshopHelperTemplate::getExtraFieldsForCurrentTemplate($extraFieldName, $templateProduct, 1);
			$productData                  = '';
			list($templateUserfield, $userfieldArr) = self::getProductUserfieldFromTemplate($templateProduct);
			$templateProduct = RedshopHelperTax::replaceVatInformation($templateProduct);

			if (strpos($templateDesc, "{subproductlimit:") !== false)
			{
				$usePerPageLimit = true;
				$perpage         = explode('{subproductlimit:', $templateDesc);
				$perpage         = explode('}', $perpage[1]);
				$limitProduct    = intval($perpage[0]);
				$templateDesc   = str_replace("{subproductlimit:" . intval($perpage[0]) . "}", "", $templateDesc);
			}

			for ($i = 0; $i < $limitProduct; $i++)
			{
				// ToDo: This is wrong way to generate tmpl file. And model function to load $this->product is wrong way also. Fix it.
				// ToDo: Echo a message when no records is returned by selection of empty category or wrong manufacturer in menu item params.

				$countNoUserField = 0;

				$product  = $products[$i];
				$dataAdd = $templateProduct;

				// ProductFinderDatepicker Extra Field Start
				$dataAdd = self::getProductFinderDatepickerValue($dataAdd, $product->product_id, $listSectionFields);
				// ProductFinderDatepicker Extra Field End

				//Replace Product price when config enable discount is "No"
				if (Redshop::getConfig()->getInt('DISCOUNT_ENABLE') === 0)
				{
					$dataAdd = str_replace('{product_old_price}', '', $dataAdd);
				}

				/*
				 * Process the prepare Product plugins
				 */
				$params  = array();
				$results = $dispatcher->trigger('onPrepareProduct', array(& $dataAdd, &$params, $product));

				if (strpos($dataAdd, "{product_delivery_time}") !== false)
				{
					$productDeliveryTime = self::getProductMinDeliveryTime($product->product_id);

					if ($productDeliveryTime != "")
					{
						$dataAdd = str_replace("{delivery_time_lbl}", JText::_('COM_REDSHOP_DELIVERY_TIME'), $dataAdd);
						$dataAdd = str_replace("{product_delivery_time}", $productDeliveryTime, $dataAdd);
					}
					else
					{
						$dataAdd = str_replace("{delivery_time_lbl}", "", $dataAdd);
						$dataAdd = str_replace("{product_delivery_time}", "", $dataAdd);
					}
				}

				// More documents
				if (strpos($dataAdd, "{more_documents}") !== false)
				{
					$mediaDocuments = RedshopHelperMedia::getAdditionMediaImage($product->product_id, "product", "document");
					$moreDoc        = '';

					for ($m = 0, $nm = count($mediaDocuments); $m < $nm; $m++)
					{
						$alttext = RedshopHelperMedia::getAlternativeText(
							"product", $mediaDocuments[$m]->section_id, "", $mediaDocuments[$m]->media_id, "document"
						);

						if (!$alttext)
						{
							$alttext = $mediaDocuments[$m]->media_name;
						}

						if (JFile::exists(REDSHOP_FRONT_DOCUMENT_RELPATH . 'product/' . $mediaDocuments[$m]->media_name))
						{
							$downlink = JURI::root() .
								'index.php?tmpl=component&option=com_redshop' .
								'&view=product&pid=' . $product->product_id .
								'&task=downloadDocument&fname=' . $mediaDocuments[$m]->media_name .
								'&Itemid=' . $itemId;
							$moreDoc .= "<div><a href='" . $downlink . "' title='" . $alttext . "'>";
							$moreDoc .= $alttext;
							$moreDoc .= "</a></div>";
						}
					}

					$dataAdd = str_replace("{more_documents}", "<span id='additional_docs" . $product->product_id . "'>" . $moreDoc . "</span>", $dataAdd);
				}

				// More documents end

				// Product User Field Start
				$hiddenUserField = "";

				if ($templateUserfield != "")
				{
					$ufield = "";

					for ($ui = 0, $nui = count($userfieldArr); $ui < $nui; $ui++)
					{
						$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($userfieldArr[$ui], 12, '', '', 0, $product->product_id);
						$ufield            .= $productUserFields[1];

						if ($productUserFields[1] != "")
						{
							$countNoUserField++;
						}

						$dataAdd = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $dataAdd);
						$dataAdd = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $dataAdd);
					}

					$productUserFieldsForm = "<form method='post' action='' id='user_fields_form_" . $product->product_id .
						"' name='user_fields_form_" . $product->product_id . "'>";

					if ($ufield != "")
					{
						$dataAdd = str_replace("{if product_userfield}", $productUserFieldsForm, $dataAdd);
						$dataAdd = str_replace("{product_userfield end if}", "</form>", $dataAdd);
					}
					else
					{
						$dataAdd = str_replace("{if product_userfield}", "", $dataAdd);
						$dataAdd = str_replace("{product_userfield end if}", "", $dataAdd);
					}
				}
				elseif (Redshop::getConfig()->get('AJAX_CART_BOX'))
				{
					$ajaxDetailTemplateDesc = "";
					$ajaxDetailTemplate      = \Redshop\Template\Helper::getAjaxDetailBox($product);

					if (null !== $ajaxDetailTemplate)
					{
						$ajaxDetailTemplateDesc = $ajaxDetailTemplate->template_desc;
					}

					$returnArr          = self::getProductUserfieldFromTemplate($ajaxDetailTemplateDesc);
					$templateUserfield = $returnArr[0];
					$userfieldArr       = $returnArr[1];

					if ($templateUserfield != "")
					{
						$ufield = "";

						for ($ui = 0, $nui = count($userfieldArr); $ui < $nui; $ui++)
						{
							$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($userfieldArr[$ui], 12, '', '', 0, $product->product_id);
							$ufield            .= $productUserFields[1];

							if ($productUserFields[1] != "")
							{
								$countNoUserField++;
							}

							$templateUserfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $templateUserfield);
							$templateUserfield = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $templateUserfield);
						}

						if ($ufield != "")
						{
							$hiddenUserField = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $product->product_id .
								"' name='user_fields_form_" . $product->product_id . "'>" . $templateUserfield . "</form></div>";
						}
					}
				}

				$dataAdd = $dataAdd . $hiddenUserField;
				/************** end user fields ***************************/

				$ItemData  = self::getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);
				$catidmain = $input->get("cid");
				$catItemid = RedshopHelperRouter::getCategoryItemid($product->cat_in_sefurl);

				if (!empty($catItemid))
				{
					$pItemid = $catItemid;
				}
				else
				{
					$pItemid = $ItemData->id;

					if (!empty($pItemid))
				{
					$pItemid = RedshopHelperRouter::getItemId($product->product_id, $catidmain);
				}
				}
				$dataAdd              = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $dataAdd);
				$dataAdd              = str_replace("{product_id}", $product->product_id, $dataAdd);
				$dataAdd              = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $dataAdd);
				$productNumberOutput  = '<span id="product_number_variable' . $product->product_id . '">' . $product->product_number . '</span>';
				$dataAdd              = str_replace("{product_number}", $productNumberOutput, $dataAdd);

				$productVolumeUnit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . "3" . '</span>';

				$dataAddStr = self::redunitDecimal($product->product_volume) . "&nbsp;" . $productVolumeUnit;
				$dataAdd   = str_replace("{product_size}", $dataAddStr, $dataAdd);

				$productUnit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . '</span>';
				$dataAdd     = str_replace("{product_length}", self::redunitDecimal($product->product_length) . "&nbsp;" . $productUnit, $dataAdd);
				$dataAdd     = str_replace("{product_width}", self::redunitDecimal($product->product_width) . "&nbsp;" . $productUnit, $dataAdd);
				$dataAdd     = str_replace("{product_height}", self::redunitDecimal($product->product_height) . "&nbsp;" . $productUnit, $dataAdd);

				$specificLink = $dispatcher->trigger('createProductLink', array($product));

				if (empty($specificLink))
				{
					$productCatId = !empty($product->categories) && is_array($product->categories) ? $product->categories[0] : $categoryId;

					$link = JRoute::_(
						'index.php?option=com_redshop' .
						'&view=product&pid=' . $product->product_id .
						'&cid=' . $productCatId .
						'&Itemid=' . $pItemid
					);
				}
				else
				{
					$link = $specificLink[0];
				}

				$pname      = RedshopHelperUtility::maxChars($product->product_name, Redshop::getConfig()->get('CATEGORY_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_PRODUCT_TITLE_END_SUFFIX'));
				$productNm = $pname;

				if (strpos($dataAdd, '{product_name_nolink}') !== false)
				{
					$dataAdd = str_replace("{product_name_nolink}", $productNm, $dataAdd);
				}

				if (strpos($dataAdd, '{product_name}') !== false)
				{
					$pname    = "<a href='" . $link . "' title='" . $product->product_name . "'>" . $pname . "</a>";
					$dataAdd = str_replace("{product_name}", $pname, $dataAdd);
				}

				if (strpos($dataAdd, '{category_product_link}') !== false)
				{
					$dataAdd = str_replace("{category_product_link}", $link, $dataAdd);
				}

				if (strpos($dataAdd, '{read_more}') !== false)
				{
					$rmore    = "<a href='" . $link . "' title='" . $product->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
					$dataAdd = str_replace("{read_more}", $rmore, $dataAdd);
				}

				if (strpos($dataAdd, '{read_more_link}') !== false)
				{
					$dataAdd = str_replace("{read_more_link}", $link, $dataAdd);
				}

				/**
				 * Related Product List in Lightbox
				 * Tag Format = {related_product_lightbox:<related_product_name>[:width][:height]}
				 */
				if (strpos($dataAdd, '{related_product_lightbox:') !== false)
				{
					$relatedProduct  = self::getRelatedProduct($product->product_id);
					$rtlnone         = explode("{related_product_lightbox:", $dataAdd);
					$rtlntwo         = explode("}", $rtlnone[1]);
					$rtlnthree       = explode(":", $rtlntwo[0]);
					$rtln            = $rtlnthree[0];
					$rtlnfwidth      = (isset($rtlnthree[1])) ? $rtlnthree[1] : "900";
					$rtlnwidthtag    = (isset($rtlnthree[1])) ? ":" . $rtlnthree[1] : "";

					$rtlnfheight   = (isset($rtlnthree[2])) ? $rtlnthree[2] : "600";
					$rtlnheighttag = (isset($rtlnthree[2])) ? ":" . $rtlnthree[2] : "";

					$rtlntag = "{related_product_lightbox:$rtln$rtlnwidthtag$rtlnheighttag}";

					if (!empty($relatedProduct))
					{
						$linktortln = JURI::root() .
							"index.php?option=com_redshop&view=product&pid=" . $product->product_id .
							"&tmpl=component&template=" . $rtln . "&for=rtln";
						$rtlna      = '<a class="redcolorproductimg" href="' . $linktortln . '"  >' . JText::_('COM_REDSHOP_RELATED_PRODUCT_LIST_IN_LIGHTBOX') . '</a>';
					}
					else
					{
						$rtlna = "";
					}

					$dataAdd = str_replace($rtlntag, $rtlna, $dataAdd);
				}

				if (strpos($dataAdd, '{product_s_desc}') !== false)
				{
					$PSDesc = RedshopHelperUtility::maxChars($product->product_s_desc, Redshop::getConfig()->get('CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX'));
					$dataAdd = str_replace("{product_s_desc}", $PSDesc, $dataAdd);
				}

				if (strpos($dataAdd, '{product_desc}') !== false)
				{
					$PDesc   = RedshopHelperUtility::maxChars($product->product_desc, Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_MAX_CHARS'), Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_END_SUFFIX'));
					$dataAdd = str_replace("{product_desc}", $PDesc, $dataAdd);
				}

				if (strpos($dataAdd, '{product_rating_summary}') !== false)
				{
					// Product Review/Rating Fetching reviews
					$finalAvgReviewData = Redshop\Product\Rating::getRating($product->product_id);
					$dataAdd             = str_replace("{product_rating_summary}", $finalAvgReviewData, $dataAdd);
				}

				if (strpos($dataAdd, '{manufacturer_link}') !== false)
				{
					$manufacturerLinkHref = JRoute::_(
						'index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $product->manufacturer_id .
						'&Itemid=' . $itemId
					);
					$manufacturerLink      = '<a class="btn btn-primary" href="' . $manufacturerLinkHref . '" title="' . $product->manufacturer_name . '">' .
						$product->manufacturer_name .
						'</a>';
					$dataAdd               = str_replace("{manufacturer_link}", $manufacturerLink, $dataAdd);

					if (strpos($dataAdd, "{manufacturer_link}") !== false)
					{
						$dataAdd = str_replace("{manufacturer_name}", "", $dataAdd);
					}
				}

				if (strpos($dataAdd, '{manufacturer_product_link}') !== false)
				{
					$manuUrl           = JRoute::_(
						'index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $product->manufacturer_id .
						'&Itemid=' . $itemId
					);
					$manufacturerPLink = "<a class='btn btn-primary' href='" . $manuUrl . "'>" .
						JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . " " . $product->manufacturer_name .
						"</a>";
					$dataAdd          = str_replace("{manufacturer_product_link}", $manufacturerPLink, $dataAdd);
				}

				if (strpos($dataAdd, '{manufacturer_name}') !== false)
				{
					$dataAdd = str_replace("{manufacturer_name}", $product->manufacturer_name, $dataAdd);
				}

				if (strpos($dataAdd, "{product_thumb_image_3}") !== false)
				{
					$pimgTag = '{product_thumb_image_3}';
					$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_3');
					$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_3');
				}
				elseif (strpos($dataAdd, "{product_thumb_image_2}") !== false)
				{
					$pimgTag = '{product_thumb_image_2}';
					$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_2');
					$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_2');
				}
				elseif (strpos($dataAdd, "{product_thumb_image_1}") !== false)
				{
					$pimgTag = '{product_thumb_image_1}';
					$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
					$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
				}
				else
				{
					$pimgTag = '{product_thumb_image}';
					$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
					$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
				}

				$hiddenThumbImage = "<input type='hidden' name='prd_main_imgwidth'  id='prd_main_imgwidth' value='" . $pwThumb . "'>
								<input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='" . $phThumb . "'>";

				// Product image flying addwishlist time start
				$thumbImage = "<span class='productImageWrap' id='productImageWrapID_" . $product->product_id . "'>" .
					Redshop\Product\Image\Image::getImage($product->product_id, $link, $pwThumb, $phThumb, 2, 1) .
					"</span>";

				// Product image flying addwishlist time end
				$dataAdd = str_replace($pimgTag, $thumbImage . $hiddenThumbImage, $dataAdd);

				// Front-back image tag...
				if (strpos($dataAdd, "{front_img_link}") !== false || strpos($dataAdd, "{back_img_link}") !== false)
				{
					if ($product->product_thumb_image)
					{
						$mainsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_thumb_image;
					}
					else
					{
						$mainsrcPath = RedshopHelperMedia::getImagePath(
							$product->product_full_image,
							'',
							'thumb',
							'product',
							$pwThumb,
							$phThumb,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
					}

					if ($product->product_back_thumb_image)
					{
						$backsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_thumb_image;
					}
					else
					{
						$backsrcPath = RedshopHelperMedia::getImagePath(
							$product->product_back_full_image,
							'',
							'thumb',
							'product',
							$pwThumb,
							$phThumb,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
					}

					$ahrefpath     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image;
					$ahrefbackpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_full_image;

					$productFrontImageLink = "<a href='#' onClick='javascript:changeproductImage(" .
						$product->product_id . ",\"" . $mainsrcPath . "\",\"" . $ahrefpath . "\");'>" .
						JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
					$productBackImageLink  = "<a href='#' onClick='javascript:changeproductImage(" .
						$product->product_id . ",\"" . $backsrcPath . "\",\"" . $ahrefbackpath . "\");'>" .
						JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

					$dataAdd = str_replace("{front_img_link}", $productFrontImageLink, $dataAdd);
					$dataAdd = str_replace("{back_img_link}", $productBackImageLink, $dataAdd);
				}
				else
				{
					$dataAdd = str_replace("{front_img_link}", "", $dataAdd);
					$dataAdd = str_replace("{back_img_link}", "", $dataAdd);
				}

				// Front-back image tag end

				// Product preview image.
				if (strpos($dataAdd, '{product_preview_img}') !== false)
				{
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product->product_preview_image))
					{
						$previewsrcPath = RedshopHelperMedia::getImagePath(
							$product->product_preview_image,
							'',
							'thumb',
							'product',
							Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH'),
							Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT'),
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
						$previewImg     = "<img src='" . $previewsrcPath . "' class='rs_previewImg' />";
						$dataAdd       = str_replace("{product_preview_img}", $previewImg, $dataAdd);
					}
					else
					{
						$dataAdd = str_replace("{product_preview_img}", "", $dataAdd);
					}
				}

				$dataAdd = self::getJcommentEditor($product, $dataAdd);

				/*
				 * product loop template extra field
				 * lat arg set to "1" for indetify parsing data for product tag loop in category
				 * last arg will parse {producttag:NAMEOFPRODUCTTAG} nameing tags.
				 * "1" is for section as product
				 */
				if ($extraFieldsForCurrentTemplate && !empty($loadCategorytemplate))
				{
					$dataAdd = Redshop\Helper\ExtraFields::displayExtraFields(1, $product->product_id, $extraFieldsForCurrentTemplate, $dataAdd, true);
				}

				/************************************
				 *  Conditional tag
				 *  if product on discount : Yes
				 *  {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
				 *  NO : // OUTPUT : Display blank
				 ************************************/
				$dataAdd = self::getProductOnSaleComment($product, $dataAdd);

				// Replace wishlistbutton
				$dataAdd = RedshopHelperWishlist::replaceWishlistTag($product->product_id, $dataAdd);

				// Replace compare product button
				$dataAdd = Redshop\Product\Compare::replaceCompareProductsButton($product->product_id, $categoryId, $dataAdd);

				$dataAdd = RedshopHelperStockroom::replaceStockroomAmountDetail($dataAdd, $product->product_id);

				// Checking for child products
				if (isset($product->count_child_products) && $product->count_child_products > 0)
				{
					if (Redshop::getConfig()->get('PURCHASE_PARENT_WITH_CHILD') == 1)
					{
						$isChilds = false;

						// Get attributes
						$attributeSet = array();

						if ($product->attribute_set_id > 0)
						{
							$attributeSet = RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
						}

						$attributes = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
						$attributes = array_merge($attributes, $attributeSet);
					}
					else
					{
						$isChilds   = true;
						$attributes = array();
					}
				}
				else
				{
					$isChilds = false;

					// Get attributes
					$attributeSet = array();

					if ($product->attribute_set_id > 0)
					{
						$attributeSet = RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
					}

					$attributes = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
					$attributes = array_merge($attributes, $attributeSet);
				}

				// Product attribute  Start
				$totalatt = count($attributes);

				// Check product for not for sale

				$dataAdd = self::getProductNotForSaleComment($product, $dataAdd, $attributes);

				$dataAdd = Redshop\Product\Stock::replaceInStock($product->product_id, $dataAdd, $attributes, $attributeTemplate);

				$dataAdd = RedshopHelperAttribute::replaceAttributeData($product->product_id, 0, 0, $attributes, $dataAdd, $attributeTemplate, $isChilds);

				// Get cart tempalte
				$dataAdd = Redshop\Cart\Render::replace(
					$product->product_id,
					$category->id,
					0,
					0,
					$dataAdd,
					$isChilds,
					$userfieldArr,
					$totalatt,
					isset($product->total_accessories) ? $product->total_accessories : 0,
					$countNoUserField
				);

				//  Extra field display
				$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(RedshopHelperExtrafields::SECTION_PRODUCT);
				$dataAdd       = RedshopHelperProductTag::getExtraSectionTag($extraFieldName, $product->product_id, "1", $dataAdd);

				$productAvailabilityDate = strstr($dataAdd, "{product_availability_date}");
				$stockNotifyFlag         = strstr($dataAdd, "{stock_notify_flag}");
				$stockStatus             = strstr($dataAdd, "{stock_status");

				$attributeproductStockStatus = array();

				if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus)
				{
					$attributeproductStockStatus = self::getproductStockStatus($product->product_id, $totalatt);
				}

				$dataAdd = \Redshop\Helper\Stockroom::replaceProductStockData(
					$product->product_id,
					0,
					0,
					$dataAdd,
					$attributeproductStockStatus
				);

				$dispatcher->trigger('onAfterDisplayProduct', array(&$dataAdd, array(), $product));

				$productData .= $dataAdd;
			}

			if (!$slide)
			{
				$productTmpl = "<div class='redcatproducts'>" . $productData . "</div>";
			}
			else
			{
				$productTmpl = $productData;
			}

			$templateDesc = str_replace("{product_loop_start}", "", $templateDesc);
			$templateDesc = str_replace("{product_loop_end}", "", $templateDesc);
			$templateDesc = str_replace($templateProduct, "<div class='productlist'>" . $productTmpl . "</div>", $templateDesc);
		}
	}

	public static function getWrapper($product_id, $wrapper_id = 0, $default = 1)
	{
		$db = Factory::getDbo();

		$usetoall = "";
		$and      = "";

		if ($wrapper_id != 0)
		{
			$and .= " AND wrapper_id='" . $wrapper_id . "' ";
		}

		$query = $db->getQuery(true);
		$query->select('*')
            ->from($db->qn('#__redshop_product_category_xref'))
            ->where($db->qn('product_id') . ' = ' . $db->q((int) $product_id));

		$db->setQuery($query);
		$cat = $db->loadObjects();

		for ($i = 0, $in = count($cat); $i < $in; $i++)
		{
			$usetoall .= " OR FIND_IN_SET(" . (int) $cat[$i]->category_id . ",category_id) ";
		}

		if ($default != 0)
		{
			$usetoall .= " OR wrapper_use_to_all = 1 ";
		}

		$query = "SELECT * FROM " .  $db->qn('#__redshop_wrapper')
			. "WHERE published = 1 "
			. "AND (FIND_IN_SET(" . (int) $product_id . ",product_id) "
			. $usetoall . " )"
			. $and;
		$db->setQuery($query);
		$list = $db->loadObjectList();

		return $list;
	}

	public static function getProductFinderDatepickerValue($templatedata = "", $productid = 0, $fieldsArray = array(), $giftcard = 0)
	{
		if (empty($fieldsArray))
		{
			return $templatedata;
		}

		foreach ($fieldsArray as $fieldArray)
		{
			$fieldValueArray = RedshopHelperExtrafields::getData($fieldArray->id, 17, $productid);

			if ($fieldValueArray->data_txt != ""
				&& $fieldArray->show_in_front == 1
				&& $fieldArray->published == 1
				&& $giftcard == 0)
			{
				$templatedata = str_replace('{' . $fieldArray->name . '}', $fieldValueArray->data_txt, $templatedata);
				$templatedata = str_replace('{' . $fieldArray->name . '_lbl}', $fieldArray->title, $templatedata);
			}
			else
			{
				$templatedata = str_replace('{' . $fieldArray->name . '}', "", $templatedata);
				$templatedata = str_replace('{' . $fieldArray->name . '_lbl}', "", $templatedata);
			}
		}

		return $templatedata;
	}

	public static function redunitDecimal($price)
	{
		if (Redshop::getConfig()->get('UNIT_DECIMAL') != "")
		{
			return number_format($price, Redshop::getConfig()->get('UNIT_DECIMAL'), '.', '');
		}

		return $price;
	}

	public static function getassociatetag($product_id = 0)
	{
		$db = JFactory::getDbo();
		$query = " SELECT a.product_id,at.tag_id,rg.tag_name,ty.type_name FROM  #__redproductfinder_associations as a left outer join #__redproductfinder_association_tag as at on a.id=at.association_id left outer join #__redproductfinder_tags as rg on at.tag_id=rg.id left outer join #__redproductfinder_types as ty on at.type_id=ty.id where a.product_id='" . $product_id . "' ";
		$db->setQuery($query);
		$res = $db->loadObjectlist();

		return $res;
	}

	public static function getProductCaterories($productId, $displayLink = 0)
	{
		$prodCatsObjectArray = array();
		$db                  = JFactory::getDbo();
		$query               = $db->getQuery(true)
			->select($db->qn('c.name'))
			->select($db->qn('c.id'))
			->from($db->qn('#__redshop_category'))
			->leftjoin($db->qn('#__redshop_product_category_xref', 'pcx') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $productId))
			->where($db->qn('c.published') . ' = 1');

		$rows = $db->setQuery($query)->loadObjectList();

		for ($i = 0, $in = count($rows); $i < $in; $i++)
		{
			$ppCat = $pCat = '';
			$row   = $rows[$i];

			$query = $db->getQuery(true)
				->select($db->qn('parent_id'))
				->select($db->qn('name'))
				->from($db->qn('#__redshop_category'))
				->where($db->qn('id') . ' = ' . $db->q((int) $row->id));

			$parentCat = $db->setQuery($query)->loadObject();

			if (!empty($parentCat) && $parentCat->parent_id)
			{
				$pCat = $parentCat->name;

				$query = $db->getQuery(true)
					->select($db->qn('parent_id'))
					->select($db->qn('name'))
					->from($db->qn('#__redshop_category'))
					->where($db->qn('id') . ' = ' . $db->q((int) $parentCat->parent_id));

				$pparentCat = $db->setQuery($query)->loadObject();

				if (!empty($pparentCat) && $pparentCat->parent_id)
				{
					$ppCat = $pparentCat->name;
				}
			}

			$spacediv  = (isset($pCat) && $pCat) ? " > " : "";
			$pspacediv = (isset($ppCat) && $ppCat) ? " > " : "";
			$catlink   = '';

			if ($displayLink)
			{
				$catItem   = RedshopHelperRouter::getCategoryItemid($row->id);

				if (!(boolean) $catItem)
				{
					$catItem = JFactory::getApplication()->input->getInt('Itemid');
				}

				$catlink = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid='
					. $row->id . '&Itemid=' . $catItem);
			}

			$prodCatsObject        = new stdClass;
			$prodCatsObject->name  = $ppCat . $pspacediv . $pCat . $spacediv . $row->name;
			$prodCatsObject->link  = $catlink;
			$prodCatsObjectArray[] = $prodCatsObject;
		}

		return $prodCatsObjectArray;
	}

	public static function getProductOnSaleComment($product = array(), $data_add = "")
	{
		if (strpos($data_add, "{if product_on_sale}") && strpos($data_add, "{product_on_sale end if}") !== false)
		{
			if ($product->product_on_sale == 1 && (($product->discount_stratdate == 0 && $product->discount_enddate == 0) || ($product->discount_stratdate <= time() && $product->discount_enddate >= time())))
			{
				$data_add = str_replace("{discount_start_date}", RedshopHelperDatetime::convertDateFormat($product->discount_stratdate), $data_add);
				$data_add = str_replace("{discount_end_date}", RedshopHelperDatetime::convertDateFormat($product->discount_enddate), $data_add);
				$data_add = str_replace("{if product_on_sale}", '', $data_add);
				$data_add = str_replace("{product_on_sale end if}", '', $data_add);
			}
			else
			{
				$template_pd_sdata = strstr($data_add, '{if product_on_sale}', true);
				$template_pd_edata = substr(strstr($data_add, '{product_on_sale end if}'), 24);
				$data_add          = $template_pd_sdata . $template_pd_edata;
			}

			$data_add = str_replace("{discount_start_date}", '', $data_add);
			$data_add = str_replace("{discount_end_date}", '', $data_add);
		}

		return $data_add;
	}

	public static function getSpecialProductComment($product = array(), $data_add = "")
	{
		if (strpos($data_add, "{if product_special}") !== false && strpos($data_add, "{product_special end if}") !== false)
		{
			if ($product->product_special == 0)
			{
				$template_pd_sdata = explode('{if product_special}', $data_add);
				$template_pd_edata = explode('{product_special end if}', $template_pd_sdata [1]);
				$data_add          = $template_pd_sdata[0] . $template_pd_edata[1];
			}

			$data_add = str_replace("{if product_special}", '', $data_add);
			$data_add = str_replace("{product_special end if}", '', $data_add);
		}

		return $data_add;
	}

	public static function getProductMinDeliveryTime($product_id = 0, $section_id = 0, $section = '', $loadDiv = 1)
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (!$section_id && !$section)
		{
			$query
				->from($db->qn('#__redshop_product_stockroom_xref') . ' AS ps')
				->where($db->qn('ps.product_id') . ' = ' . (int) $product_id);
		}
		else
		{
			$query
				->from($db->qn('#__redshop_product_attribute_stockroom_xref') . ' AS ps')
				->where($db->qn('ps.section_id') . ' = ' . (int) $section_id)
				->where($db->qn('ps.section') . ' = ' . $db->q($section));
		}

		// Create the base select statement.
		$query->select(
			array(
				'min_del_time as deltime',
				's.max_del_time',
				's.delivery_time'
			)
		)
			->join('', $db->qn('#__redshop_stockroom') . ' AS s')
			->where($db->qn('ps.stockroom_id') . ' = ' . $db->qn('s.stockroom_id'))
			->where($db->qn('ps.quantity') . ' > 0 ')
			->order($db->qn('min_del_time') . ' ASC');

		// Set the query and load the result.
		$db->setQuery($query, 0, 1);

		try
		{
			$result = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		$product_delivery_time = '';

		if ($result)
		{
			// Append where clause to get Maximum Delivery time of Minimum Delivery stockroom
			$query->where($db->qn('s.min_del_time') . ' = ' . (int) $result->deltime);

			// Set the query and load the row.
			$db->setQuery($query, 0, 1);

			try
			{
				$row = $db->loadObject();
			}
			catch (RuntimeException $e)
			{
				throw new RuntimeException($e->getMessage(), $e->getCode());
			}

			if ($row->deltime == 0 || $row->deltime == ' ')
			{
				$product_delivery_time = '';
			}
			else
			{
				if ($row->delivery_time == "Days")
				{
					$duration = JText::_('COM_REDSHOP_DAYS');
				}
				else
				{
					$row->deltime      = $row->deltime / 7;
					$row->max_del_time = $row->max_del_time / 7;
					$duration          = JText::_('COM_REDSHOP_WEEKS');
				}

				$product_delivery_time = (int) $row->deltime . "-" . (int) $row->max_del_time . " " . $duration;
			}
		}

		if ($product_delivery_time && $loadDiv)
		{
			$product_delivery_time = '<div id="ProductAttributeMinDelivery' . $product_id . '">' . $product_delivery_time . '</div>';
		}

		return $product_delivery_time;
	}

	/*
	 * function to get products parent id
	 *
	 * @return: int
	 */
	public static function getMainParentProduct($parent_id)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT product_parent_id FROM " . $db->qn('#__redshop_product ')
			. "WHERE published=1 "
			. "AND product_id = " . (int) $parent_id;
		$db->setQuery($query);
		$product_parent_id = $db->loadResult();

		if ((int) $product_parent_id !== 0)
		{
			$parent_id = self::getMainParentProduct($product_parent_id);
		}

		return $parent_id;
	}

	public static function getProductNotForSaleComment($product = array(), $data_add = "", $attributes = array(), $is_relatedproduct = 0, $seoTemplate = "")
	{
		$showPrice = true;

		if ($product->expired || $product->not_for_sale == 1)
		{
			$showPrice = false;
		}

		if ($showPrice)
		{
			// Product show price without formatted
			$applytax = \Redshop\Template\Helper::isApplyVat($data_add);

			if ($applytax)
			{
				$GLOBAL ['without_vat'] = false;
			}
			else
			{
				$GLOBAL ['without_vat'] = true;
			}

			$data_add = RedshopHelperProductPrice::getShowPrice($product->product_id, $data_add, $seoTemplate, 0, $is_relatedproduct, $attributes);
		}
		else
		{
			$relPrefix = ($is_relatedproduct) ? 'rel' : '';
			$data_add  = str_replace("{" . $relPrefix . "product_price_lbl}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price_novat}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "price_excluding_vat}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price_table}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_old_price}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price_saving}", "", $data_add);
		}

		return $data_add;
	}

	/**
	 * @param   integer $productId             Product id
	 * @param   integer $totalAttribute        Total attribute
	 * @param   integer $selectedPropertyId    Selected property id
	 * @param   integer $selectedsubpropertyId Selected sub property id
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 * @throws  \Exception
	 */
	public static function getproductStockStatus($productId = 0, $totalAttribute = 0, $selectedPropertyId = 0, $selectedsubpropertyId = 0)
	{
		return RedshopEntityProduct::getInstance($productId)->getStockstatus($totalAttribute, $selectedPropertyId, $selectedsubpropertyId);
	}

	public static function getJcommentEditor($product = array(), $data_add = "")
	{
		$app             = JFactory::getApplication();
		$product_reviews = "";
		$product_id      = $product->product_id;

		if ($product_id && strpos($data_add, "{jcomments off}") === false && strpos($data_add, "{jcomments on}") !== false)
		{
			$comments = $app->getCfg('absolute_path') . '/components/com_jcomments/jcomments.php';

			if (file_exists($comments))
			{
				require_once $comments;
				$product_reviews = JComments::showComments($product_id, 'com_redshop', $product->product_name);
			}

			$data_add = str_replace("{jcomments on}", $product_reviews, $data_add);
		}

		$data_add = str_replace("{jcomments on}", $product_reviews, $data_add);
		$data_add = str_replace("{jcomments off}", "", $data_add);

		return $data_add;
	}

	public static function getProductUserfieldFromTemplate($templatedata = "", $giftcard = 0)
	{
		$userfields      = array();
		$userfields_lbl  = array();
		$retArr          = array();
		$template_middle = "";

		if ($giftcard)
		{
			$template_start = explode("{if giftcard_userfield}", $templatedata);

			if (!empty($template_start))
			{
				$template_end = explode("{giftcard_userfield end if}", $template_start[1]);

				if (!empty($template_end))
				{
					$template_middle = $template_end[0];
				}
			}
		}
		else
		{
			$template_start = explode("{if product_userfield}", $templatedata);

			if (count($template_start) > 1)
			{
				$template_end = explode("{product_userfield end if}", $template_start[1]);

				if (!empty($template_end))
				{
					$template_middle = $template_end[0];
				}
			}
		}

		if ($template_middle != "")
		{
			$tmpArr = explode('}', $template_middle);

			for ($i = 0, $in = count($tmpArr); $i < $in; $i++)
			{
				$val   = strpbrk($tmpArr[$i], "{");
				$value = str_replace("{", "", $val);

				if ($value != "")
				{
					if (strpos($template_middle, '{' . $value . '_lbl}') !== false)
					{
						$userfields_lbl[] = $value . '_lbl';
						$userfields[]     = $value;
					}
					else
					{
						$userfields_lbl[] = '';
						$userfields[]     = $value;
					}
				}
			}
		}

		$tmp = array();

		for ($i = 0, $in = count($userfields); $i < $in; $i++)
		{
			if (!in_array($userfields[$i], $userfields_lbl))
			{
				$tmp[] = $userfields[$i];
			}
		}

		$userfields = $tmp;
		$retArr[0]  = $template_middle;
		$retArr[1]  = $userfields;

		return $retArr;
	}

	public static function getProductCategoryImage($product_id = 0, $category_img = '', $link = '', $width, $height)
	{
		$result     = self::getProductById($product_id);
		$thum_image = "";
		$title      = " title='" . $result->product_name . "' ";
		$alt        = " alt='" . $result->product_name . "' ";

		if ($category_img && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "category/" . $category_img))
		{
			if (Redshop::getConfig()->get('PRODUCT_IS_LIGHTBOX') == 1)
			{
				$product_img       = RedshopHelperMedia::watermark('category', $category_img, $width, $height, Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				RedshopHelperMedia::watermark('product', $category_img, Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_WIDTH'), Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_HEIGHT'), Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$linkimage         = RedshopHelperMedia::watermark('category', $category_img, '', '', Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$thum_image        = "<a id='a_main_image" . $product_id . "' href='" . $linkimage . "' " . $title . "  rel=\"myallimg\">";
				$thum_image        .= "<img id='main_image" . $product_id . "' src='" . $product_img . "' " . $title . $alt . " />";

				$thum_image .= "</a>";
			}
			else
			{
				$product_img       = RedshopHelperMedia::watermark('category', $category_img, $width, $height, Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				RedshopHelperMedia::watermark('category', $category_img, Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_WIDTH'), Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_HEIGHT'), Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE'), '0');
				$thum_image        = "<a id='a_main_image" . $product_id . "' href='" . $link . "' " . $title . ">";
				$thum_image        .= "<img id='main_image" . $product_id . "' src='" . $product_img . "' " . $title . $alt . " />";
				$thum_image        .= "</a>";
			}
		}

		return $thum_image;
	}

	public static function getSubscription($product_id = 0)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT * FROM " . $db->qn('#__redshop_product_subscription')
			. "WHERE product_id = " . (int) $product_id . " "
			. "ORDER BY subscription_id ";
		$db->setQuery($query);
		$list = $db->loadObjectlist();

		return $list;
	}

	/**
	 * Function Get Question Answers
	 *
	 * @param   int $questionId default 0
	 * @param   int $productId  default 0
	 * @param   int $faq        is FAQ
	 * @param   int $front      show in Front or Not
	 *
	 * @return  array
	 */
	public static function getQuestionAnswer($questionId = 0, $productId = 0, $faq = 0, $front = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$and = "";

		if ($questionId != 0)
		{
			if ($faq != 0)
			{
				$and .= " AND " . $db->qn('q.parent_id') . " = " . $db->q($questionId);
			}
			else
			{
				$and .= " AND " . $db->qn('q.id') . " = " . $db->q($questionId);
			}
		}
		else
		{
			$and .= " AND " . $db->qn('q.product_id') . " = " . $db->q($productId) . " AND " . $db->qn('q.parent_id') . " = 0 ";
		}

		if ($front != 0)
		{
			$and .= " AND q.published='1' ";
		}

		// Avoid db killing
		if (!empty($and))
		{
			$query->select(
				$db->qn(
					[
						'q.id', 'q.parent_id', 'q.product_id',
						'q.question', 'q.user_id', 'q.user_name',
						'q.user_email', 'q.published', 'q.question_date',
						'q.ordering', 'q.telephone', 'q.address'
					]
				)
			)
				->from($db->qn('#__redshop_customer_question', 'q'))
				->where($db->qn('q.id') . ' > 0 ' . $and)
				->order($db->qn('q.ordering'));

			$db->setQuery($query);

			return $db->loadObjectList();
		}

		return null;
	}

	/**
	 * Parse related product template
	 *
	 * @param   string  $templateDesc Template Contents
	 * @param   integer $product_id   Product Id
	 *
	 * @todo    Move this functionality to library helper and convert this code into JLayout
	 *
	 * @return  string   Parsed Template HTML
	 */
	public static function getRelatedtemplateView($templateDesc, $product_id)
	{
		$relatedProduct  = self::getRelatedProduct($product_id);
		$relatedTemplate = \Redshop\Template\Helper::getRelatedProduct($templateDesc);
		$fieldArray      = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);

		JPluginHelper::importPlugin('redshop_product');
		$dispatcher = RedshopHelperUtility::getDispatcher();

		if (null === $relatedTemplate)
		{
			$templateDesc = RedshopHelperText::replaceTexts($templateDesc);

			return $templateDesc;
		}

		if (!empty($relatedProduct)
			&& strpos($relatedTemplate->template_desc, "{related_product_start}") !== false
			&& strpos($relatedTemplate->template_desc, "{related_product_end}") !== false)
		{
			$related_template_data = '';
			$product_start         = explode("{related_product_start}", $relatedTemplate->template_desc);
			$product_end           = explode("{related_product_end}", $product_start [1]);

			$tempdata_div_start  = $product_start [0];
			$tempdata_div_middle = $product_end [0];
			$tempdata_div_end    = $product_end [1];

			$attribute_template = \Redshop\Template\Helper::getAttribute($tempdata_div_middle);

			// Extra field display
			$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);

			for ($r = 0, $rn = count($relatedProduct); $r < $rn; $r++)
			{
				$related_template_data .= $tempdata_div_middle;

				$dispatcher->trigger('onPrepareRelatedProduct', array(&$related_template_data, $relatedProduct[$r]));

				$ItemData = self::getMenuInformation(0, 0, '', 'product&pid=' . $relatedProduct[$r]->product_id);

				if (!empty($ItemData))
				{
					$pItemid = $ItemData->id;
				}
				else
				{
					$catidmain = $relatedProduct[$r]->cat_in_sefurl;
					$pItemid   = RedshopHelperRouter::getItemId($relatedProduct[$r]->product_id, $catidmain);
				}

				$rlink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $relatedProduct[$r]->product_id . '&cid=' . $relatedProduct[$r]->cat_in_sefurl . '&Itemid=' . $pItemid);

				if (strpos($related_template_data, "{relproduct_image_3}") !== false)
				{
					$rpimg_tag = '{relproduct_image_3}';
					$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT_3');
					$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH_3');
				}
				elseif (strpos($related_template_data, "{relproduct_image_2}") !== false)
				{
					$rpimg_tag = '{relproduct_image_2}';
					$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT_2');
					$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH_2');
				}
				elseif (strpos($related_template_data, "{relproduct_image_1}") !== false)
				{
					$rpimg_tag = '{relproduct_image_1}';
					$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT');
					$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH');
				}
				else
				{
					$rpimg_tag = '{relproduct_image}';
					$rph_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_HEIGHT');
					$rpw_thumb = Redshop::getConfig()->get('RELATED_PRODUCT_THUMB_WIDTH');
				}

				$hidden_thumb_image    = "<input type='hidden' name='rel_main_imgwidth' id='rel_main_imgwidth' value='"
					. $rpw_thumb . "'><input type='hidden' name='rel_main_imgheight' id='rel_main_imgheight' value='"
					. $rph_thumb . "'>";
				$relimage              = Redshop\Product\Image\Image::getImage($relatedProduct [$r]->product_id, $rlink, $rpw_thumb, $rph_thumb);
				$related_template_data = str_replace($rpimg_tag, $relimage . $hidden_thumb_image, $related_template_data);

				if (strpos($related_template_data, "{relproduct_link}") !== false)
				{
					$rpname = "<a href='" . $rlink . "' title='" . $relatedProduct [$r]->product_name . "'>"
						. RedshopHelperUtility::maxChars($relatedProduct [$r]->product_name, Redshop::getConfig()->getInt('RELATED_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->getString('RELATED_PRODUCT_TITLE_END_SUFFIX'))
						. "</a>";
				}
				else
				{
					$rpname = RedshopHelperUtility::maxChars($relatedProduct [$r]->product_name, Redshop::getConfig()->getInt('RELATED_PRODUCT_TITLE_MAX_CHARS'), Redshop::getConfig()->getString('RELATED_PRODUCT_TITLE_END_SUFFIX'));
				}

				$rpdesc       = RedshopHelperUtility::maxChars($relatedProduct [$r]->product_desc, Redshop::getConfig()->getInt('RELATED_PRODUCT_DESC_MAX_CHARS'), Redshop::getConfig()->getString('RELATED_PRODUCT_DESC_END_SUFFIX'));
				$rp_shortdesc = RedshopHelperUtility::maxChars($relatedProduct [$r]->product_s_desc, Redshop::getConfig()->getInt('RELATED_PRODUCT_SHORT_DESC_MAX_CHARS'), Redshop::getConfig()->getString('RELATED_PRODUCT_SHORT_DESC_END_SUFFIX'));

				$related_template_data = str_replace("{relproduct_link}", '', $related_template_data);

				if (strpos($related_template_data, "{relproduct_link}") !== false)
				{
					$related_template_data = str_replace("{relproduct_name}", "", $related_template_data);
				}
				else
				{
					$related_template_data = str_replace("{relproduct_name}", $rpname, $related_template_data);
				}

				if (strstr($related_template_data, "{relproduct_rating_summary}"))
				{
					$final_avgreview_data = Redshop\Product\Rating::getRating($relatedProduct [$r]->product_id);

					if ($final_avgreview_data != "")
					{
						$related_template_data = str_replace("{relproduct_rating_summary}", $final_avgreview_data, $related_template_data);
					}
					else
					{
						$related_template_data = str_replace("{relproduct_rating_summary}", '', $related_template_data);
					}
				}

				$related_template_data = str_replace("{relproduct_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $related_template_data);
				$related_template_data = str_replace("{relproduct_number}", $relatedProduct [$r]->product_number, $related_template_data);
				$related_template_data = str_replace("{relproduct_s_desc}", $rp_shortdesc, $related_template_data);
				$related_template_data = str_replace("{relproduct_desc}", $rpdesc, $related_template_data);

				// ProductFinderDatepicker Extra Field Start
				$related_template_data = self::getProductFinderDatepickerValue($related_template_data, $relatedProduct[$r]->product_id, $fieldArray);

				if (strpos($related_template_data, "{manufacturer_name}") !== false || strpos($related_template_data, "{manufacturer_link}") !== false)
				{
					$manufacturer = RedshopEntityManufacturer::getInstance($relatedProduct[$r]->manufacturer_id)->getItem();

					if (!empty($manufacturer))
					{
						$man_url               = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $relatedProduct[$r]->manufacturer_id . '&Itemid=' . $pItemid);
						$manufacturerLink      = "<a class='btn btn-primary' href='" . $man_url . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . "</a>";
						$related_template_data = str_replace("{manufacturer_name}", $manufacturer->name, $related_template_data);
						$related_template_data = str_replace("{manufacturer_link}", $manufacturerLink, $related_template_data);
					}
					else
					{
						$related_template_data = str_replace("{manufacturer_name}", '', $related_template_data);
						$related_template_data = str_replace("{manufacturer_link}", '', $related_template_data);
					}
				}

				$rmore                 = '<a href="' . $rlink . '" title="' . $relatedProduct [$r]->product_name . '">'
					. JText::_('COM_REDSHOP_READ_MORE')
					. '</a>';
				$related_template_data = str_replace("{read_more}", $rmore, $related_template_data);
				$related_template_data = str_replace("{read_more_link}", $rlink, $related_template_data);

				/*
				 *  related product Required Attribute start
				 * 	this will parse only Required Attributes
				 */
				$relid          = $relatedProduct [$r]->product_id;
				$attributes_set = array();

				if ($relatedProduct [$r]->attribute_set_id > 0)
				{
					$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $relatedProduct [$r]->attribute_set_id);
				}

				$attributes = RedshopHelperProduct_Attribute::getProductAttribute($relid);
				$attributes = array_merge($attributes, $attributes_set);

				$related_template_data = RedshopHelperProductTag::replaceAttributeData($relatedProduct[$r]->mainproduct_id, 0, $relatedProduct[$r]->product_id, $attributes, $related_template_data, $attribute_template);

				// Check product for not for sale
				$related_template_data = self::getProductNotForSaleComment($relatedProduct[$r], $related_template_data, $attributes, 1);

				$related_template_data = Redshop\Cart\Render::replace($relatedProduct[$r]->mainproduct_id, 0, 0, $relatedProduct[$r]->product_id, $related_template_data, false, array(), count($attributes), 0, 0);
				$related_template_data = Redshop\Product\Compare::replaceCompareProductsButton($relatedProduct[$r]->product_id, 0, $related_template_data, 1);
				$related_template_data = Redshop\Product\Stock::replaceInStock($relatedProduct[$r]->product_id, $related_template_data);

				$related_template_data = self::getProductOnSaleComment($relatedProduct[$r], $related_template_data);
				$related_template_data = self::getSpecialProductComment($relatedProduct[$r], $related_template_data);

				$isCategorypage = (JFactory::getApplication()->input->getCmd('view') == "category") ? 1 : 0;

				//  Extra field display
				$related_template_data = RedshopHelperProductTag::getExtraSectionTag($extraFieldName, $relatedProduct[$r]->product_id, "1", $related_template_data, $isCategorypage);

				// Related product attribute price list
				$related_template_data = self::replaceAttributePriceList($relatedProduct[$r]->product_id, $related_template_data);

				if (strpos($related_template_data, "{wishlist_link}") !== false)
				{
					$wishlistLink          = "<div class=\"wishlist\">" . RedshopHelperWishlist::replaceWishlistTag($relatedProduct[$r]->product_id, '{wishlist_link}') . "</div>";
					$related_template_data = str_replace("{wishlist_link}", $wishlistLink, $related_template_data);
				}

				$childproduct = self::getChildProduct($relatedProduct[$r]->product_id);

				if (count($childproduct) > 0)
				{
					$attributes = array();
				}
				else
				{
					// Get attributes
					$attributes_set = array();

					if ($relatedProduct[$r]->attribute_set_id > 0)
					{
						$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $relatedProduct[$r]->attribute_set_id, 0, 1);
					}

					$attributes = RedshopHelperProduct_Attribute::getProductAttribute($relatedProduct[$r]->product_id);
					$attributes = array_merge($attributes, $attributes_set);
				}

				$totalatt = count($attributes);

				$attributeproductStockStatus = array();

				$productAvailabilityDate = strstr($related_template_data, "{product_availability_date}");
				$stockNotifyFlag         = strstr($related_template_data, "{stock_notify_flag}");
				$stockStatus             = strstr($related_template_data, "{stock_status");

				if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus)
				{
					$attributeproductStockStatus = self::getproductStockStatus($relatedProduct[$r]->product_id, $totalatt);
				}

				$related_template_data = \Redshop\Helper\Stockroom::replaceProductStockData(
					$relatedProduct[$r]->product_id,
					0,
					0,
					$related_template_data,
					$attributeproductStockStatus
				);

				$dispatcher->trigger('onAfterDisplayRelatedProduct', array(&$related_template_data, $relatedProduct[$r]));
			}

			$related_template_data = $tempdata_div_start . $related_template_data . $tempdata_div_end;

			$templateDesc = str_replace("{related_product:$relatedTemplate->name}", $related_template_data, $templateDesc);
			$templateDesc = RedshopHelperTemplate::parseRedshopPlugin($templateDesc);
		}
		else
		{
			$templateDesc = str_replace("{related_product:$relatedTemplate->name}", "", $templateDesc);
		}

		$templateDesc = RedshopHelperText::replaceTexts($templateDesc);

		return $templateDesc;
	}

	/**
	 * Get menu detail
	 *
	 * @param   string $link Link
	 *
	 * @return  mixed|null
	 * @throws  Exception
	 */
	public static function getMenuDetail($link = '')
	{
		// Do not allow queries that load all the items
		if ($link != '')
		{
			return JFactory::getApplication()->getMenu()->getItems('link', $link, true);
		}

		return null;
	}

	public static function getCategoryNavigationlist($category_id)
	{
		static $i = 0;
		static $category_list = array();

		$categorylist       = RedshopEntityCategory::getInstance($category_id)->getItem();
		$category_parent_id = self::getParentCategory($category_id);

		if (!empty($categorylist) && $categorylist->parent_id > 0)
		{
			$cItemid = RedshopHelperRouter::getCategoryItemid($categorylist->id);

			if ($cItemid != "")
			{
				$tmpItemid = $cItemid;
			}
			else
			{
				$tmpItemid = JFactory::getApplication()->input->get('Itemid');
			}

			$category_list[$i]['category_id']   = $categorylist->id;
			$category_list[$i]['category_name'] = $categorylist->name;
			$category_list[$i]['catItemid']     = $tmpItemid;
		}

		if ($category_parent_id)
		{
			$i++;
			array_merge($category_list, self::getCategoryNavigationlist($category_parent_id));
		}

		return $category_list;
	}

	/**
	 * Get Menu Information
	 *
	 * @param   int    $Itemid      Item id
	 * @param   int    $sectionId   Section id
	 * @param   string $sectionName Section name
	 * @param   string $menuView    Menu view
	 * @param   bool   $isRedshop   Is redshop
	 *
	 * @return mixed|null
	 */
	public static function getMenuInformation($Itemid = 0, $sectionId = 0, $sectionName = '', $menuView = '', $isRedshop = true)
	{
		$menu   = JFactory::getApplication()->getMenu();
		$values = array();

		if ($menuView != "")
		{
			if ($items = explode('&', $menuView))
			{
				$values['view'] = $items[0];
				unset($items[0]);

				if (!empty($items))
				{
					foreach ($items as $item)
					{
						$value             = explode('=', $item);
						$values[$value[0]] = $value[1];
					}
				}
			}
		}

		if ($Itemid != 0)
		{
			return $menu->getItem($Itemid);
		}

		if ($isRedshop)
		{
			$menuItems = RedshopHelperRouter::getRedshopMenuItems();
		}
		else
		{
			$menuItems = $menu->getMenu();
		}

		foreach ($menuItems as $oneMenuItem)
		{
			if (!RedshopHelperRouter::checkMenuQuery($oneMenuItem, $values))
			{
				break;
			}

			if ($sectionName != '')
			{
				if ($sectionId != 0)
				{
					if ($oneMenuItem->params->get($sectionName) != $sectionId)
					{
						break;
					}
				}
				else
				{
					if ($oneMenuItem->params->get($sectionName, false) !== false)
					{
						break;
					}
				}
			}

			return $oneMenuItem;
		}

		return null;
	}


	/**
	 * Get Category Product
	 *
	 * @param   int $productId Product id
	 *
	 * @return string
	 */
	public static function getCategoryProduct($productId = 0)
	{
		if ($result = self::getProductById($productId))
		{
			if (!empty($result->categories))
			{
				return is_array($result->categories) ? implode(',', $result->categories) : $result->categories;
			}

			return $result->category_id;
		}

		return '';
	}

	public static function getProductCategory($id = 0)
	{
		$db    = JFactory::getDbo();
		$rsUserhelper               = rsUserHelper::getInstance();
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();
		$and                        = '';

		if ($shopper_group_manufactures != "")
		{
			// Sanitize groups
			$shopGroupsIds = explode(',', $shopper_group_manufactures);
			$shopGroupsIds = Joomla\Utilities\ArrayHelper::toInteger($shopGroupsIds);

			$and .= " AND p.manufacturer_id IN (" . implode(',', $shopGroupsIds) . ") ";
		}

		$query = "SELECT p.product_id FROM " . $db->qn('#__redshop_product_category_xref') . " AS pc"
			. " LEFT JOIN " . $db->qn('#__redshop_product') . " AS p ON pc.product_id=p.product_id "
			. " WHERE category_id = " . (int) $id . " "
			. $and;
		$db->setQuery($query);
		$res = $db->loadObjectlist();

		return $res;
	}

	public static function makeAttributeCart($attributes = array(), $productId = 0, $userId = 0, $newProductPrice = 0, $quantity = 1, $data = '')
	{
		$user = JFactory::getUser();

		if ($userId == 0)
		{
			$userId = $user->id;
		}

		$sel               = 0;
		$selP              = 0;
		$applyVat          = \Redshop\Template\Helper::isApplyAttributeVat($data, $userId);
		$setPropEqual      = true;
		$setSubpropEqual   = true;
		$selectedAttributs = array();
		$selectedProperty  = array();
		$productOldprice   = 0;
		$productVatPrice   = 0;

		if ($newProductPrice != 0)
		{
			$productPrice = $newProductPrice;

			if ($productPrice > 0)
			{
				$productVatPrice = self::getProductTax($productId, $productPrice, $userId);
			}
		}
		else
		{
			$productPrices = RedshopHelperProductPrice::getNetPrice($productId, $userId, $quantity, $data);

			// Using price without vat to proceed with calcualtion - we will apply vat in the end.
			$productPrice    = $productPrices['product_price_novat'];
			$productVatPrice = $productPrices['productVat'];
			$productOldprice = $productPrices['product_old_price_excl_vat'];
		}

		$isStock          = RedshopHelperStockroom::isStockExists($productId);
		$isPreorderStock  = RedshopHelperStockroom::isPreorderStockExists($productId);
		$displayAttribute = 0;

		for ($i = 0, $in = count($attributes); $i < $in; $i++)
		{
			$propertiesOperator        = array();
			$propertiesPrice           = array();
			$propertiesPriceWithVat    = array();
			$propertiesVat             = array();
			$subPropertiesOperator     = array();
			$subPropertiesPrice        = array();
			$subPropertiesPriceWithVat = array();
			$subPropertiesVat          = array();

			$properties = !empty($attributes[$i]['attribute_childs']) ? $attributes[$i]['attribute_childs'] : array();

			if (count($properties) > 0)
			{
				$displayAttribute++;
			}

			for ($k = 0, $kn = count($properties); $k < $kn; $k++)
			{
				$propertyVat             = 0;
				$propertyOperator        = $properties[$k]['property_oprand'];
				$propertyPriceWithoutVat = (isset($properties[$k]['property_price'])) ? $properties[$k]['property_price'] : 0;
				$property                = RedshopHelperProduct_Attribute::getAttributeProperties($properties[$k]['property_id']);
				$propertyPrice           = $propertyPriceWithoutVat;

				if ($propertyPriceWithoutVat > 0)
				{
					// Set property vat to 1 when price is 1. For * and / math rules.
					if ($propertyPriceWithoutVat == 1
						&& ($propertyOperator == '*' || $propertyOperator == '/'))
					{
						$propertyVat = 1;
					}

					if ($propertyOperator != '*' && $propertyOperator != '/')
					{
						$propertyVat = self::getProductTax($productId, $propertyPriceWithoutVat, $userId);
					}
				}

				$isStock         = RedshopHelperStockroom::isStockExists($properties[$k]['property_id'], "property");
				$isPreorderStock = RedshopHelperStockroom::isPreorderStockExists($properties[$k]['property_id'], "property");

				$propertiesOperator[$k]     = $propertyOperator;
				$propertiesPrice[$k]        = $propertyPriceWithoutVat;
				$propertiesPriceWithVat[$k] = $propertyPrice;
				$propertiesVat[$k]          = $propertyVat;
				$subProperties              = $properties[$k]['property_childs'];

				for ($l = 0, $ln = count($subProperties); $l < $ln; $l++)
				{
					if ($l == 0)
					{
						$selectedProperty[$selP++] = $properties[$k]['property_id'];
					}

					// Continue if there is no subproperty id
					if (!(int) $subProperties[$l]['subproperty_id'])
					{
						continue;
					}

					$subPropertyVat             = 0;
					$subPropertyOperator        = $subProperties[$l]['subproperty_oprand'];
					$subPropertyPriceWithoutVat = $subProperties[$l]['subproperty_price'];
					$subPropertyPrice           = $subPropertyPriceWithoutVat;

					if ($subPropertyPriceWithoutVat > 0)
					{
						// Set property vat to 1 when price is 1. For * and / math rules.
						if ($subPropertyPriceWithoutVat == 1
							&& ($subPropertyOperator == '*' || $subPropertyOperator == '/'))
						{
							$subPropertyVat = 1;
						}

						if ($subPropertyOperator != '*' && $subPropertyOperator != '/')
						{
							$subPropertyVat = self::getProductTax($productId, $subPropertyPriceWithoutVat, $userId);
						}
					}

					$isStock         = RedshopHelperStockroom::isStockExists(
						$subProperties[$l]['subproperty_id'],
						"subproperty"
					);
					$isPreorderStock = RedshopHelperStockroom::isPreorderStockExists(
						$subProperties[$l]['subproperty_id'],
						"subproperty"
					);

					$subPropertiesOperator[$k][$l]     = $subPropertyOperator;
					$subPropertiesPrice[$k][$l]        = $subPropertyPriceWithoutVat;
					$subPropertiesPriceWithVat[$k][$l] = $subPropertyPrice;
					$subPropertiesVat[$k][$l]          = $subPropertyVat;
				}
			}

			// FOR PROPERTY AND SUBPROPERTY PRICE CALCULATION
			$propertyPrices = self::makeTotalPriceByOprand($productPrice, $propertiesOperator, $propertiesPriceWithVat);
			$productPrice   = $propertyPrices[1];

			$propertyOldPriceVats = self::makeTotalPriceByOprand($productOldprice, $propertiesOperator, $propertiesPrice);
			$productOldprice      = $propertyOldPriceVats[1];

			for ($t = 0, $tn = count($properties); $t < $tn; $t++)
			{
				$selectedAttributs[$sel++] = $attributes[$i]['attribute_id'];

				if ($setPropEqual && $setSubpropEqual && isset($subPropertiesPriceWithVat[$t]))
				{
					$subPropertyPrices = self::makeTotalPriceByOprand($productPrice, $subPropertiesOperator[$t], $subPropertiesPriceWithVat[$t]);

					$productPrice = $subPropertyPrices[1];

					$subPropertyOldPriceVats = self::makeTotalPriceByOprand($productOldprice, $subPropertiesOperator[$t], $subPropertiesPrice[$t]);
					$productOldprice         = $subPropertyOldPriceVats[1];
				}
			}
		}

		$displayattribute = RedshopLayoutHelper::render(
			'product.product_attribute',
			array(
				'attributes'       => $attributes,
				'data'             => $data,
				'displayAttribute' => $displayAttribute
			),
			'',
			array(
				'component' => 'com_redshop',
				'client'    => 0
			)
		);

		$productVatOldPrice = 0;

		if ($productOldprice > 0)
		{
			$productVatOldPrice = self::getProductTax($productId, $productOldprice, $userId);
		}

		// Recalculate VAT if set to apply vat for attribute
		if ($applyVat)
		{
			$productVatPrice = self::getProductTax($productId, $productPrice, $userId);
		}

		// Todo: For QA to check all cases.
		/*if ($this->getApplyVatOrNot($data, $userId))
		{
			$productPrice += $productVatPrice;
		}*/

		$data = array(
			$displayattribute,
			$productPrice,
			$productVatPrice,
			$selectedAttributs,
			$isStock,
			$productOldprice,
			$productVatOldPrice,
			$isPreorderStock,
			$selectedProperty
		);

		JPluginHelper::importPlugin('redshop_product');
		RedshopHelperUtility::getDispatcher()->trigger('onMakeAttributeCart', array(&$data, $attributes, $productId));

		return $data;
	}

	public static function makeAccessoryCart($attArr = array(), $product_id = 0, $user_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$data                  = \Redshop\Template\Cart::getCartTemplate();
		$chktag                = \Redshop\Template\Helper::isApplyAttributeVat($data[0]->template_desc, $user_id);
		$setPropEqual          = true;
		$setSubpropEqual       = true;
		$displayaccessory      = "";
		$accessory_total_price = 0;
		$accessory_vat_price   = 0;

		if (count($attArr) > 0)
		{
			for ($i = 0, $in = count($attArr); $i < $in; $i++)
			{
				$acc_vat = 0;

				if ($attArr[$i]['accessory_price'] > 0)
				{
					$acc_vat = self::getProductTax($product_id, $attArr[$i]['accessory_price'], $user_id);
				}

				$accessory_price = $attArr[$i]['accessory_price'];

				if (!empty($chktag))
				{
					$accessory_price     = $attArr[$i]['accessory_price'] + $acc_vat;
					$accessory_vat_price = $acc_vat;
				}

				$attchildArr = $attArr[$i]['accessory_childs'];

				for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
				{
					$prooprand      = array();
					$proprice       = array();
					$provatprice    = array();
					$provat         = array();
					$subprooprand   = array();
					$subproprice    = array();
					$subprovatprice = array();
					$subprovat      = array();

					$propArr = $attchildArr[$j]['attribute_childs'];

					for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
					{
						$property_price = $propArr[$k]['property_price'];
						$acc_vat        = 0;
						$acc_propvat    = 0;

						if ($propArr[$k]['property_price'] > 0)
						{
							$acc_propvat = self::getProductTax($product_id, $propArr[$k]['property_price'], $user_id);
						}

						if (!empty($chktag))
						{
							$property_price = $property_price + $acc_propvat;
							$acc_vat        = $acc_propvat;
						}

						$prooprand[$k]   = $propArr[$k]['property_oprand'];
						$proprice[$k]    = $propArr[$k]['property_price'];
						$provatprice[$k] = $property_price;
						$provat[$k]      = $acc_vat;

						$subpropArr = $propArr[$k]['property_childs'];

						for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
						{
							$acc_vat           = 0;
							$acc_subpropvat    = 0;
							$subproperty_price = $subpropArr[$l]['subproperty_price'];

							if ($subpropArr[$l]['subproperty_price'] > 0)
							{
								$acc_subpropvat = self::getProductTax($product_id, $subpropArr[$l]['subproperty_price'], $user_id);
							}

							if (!empty($chktag))
							{
								$subproperty_price = $subproperty_price + $acc_subpropvat;
								$acc_vat           = $acc_subpropvat;
							}


							$subprooprand[$k][$l]   = $subpropArr[$l]['subproperty_oprand'];
							$subproprice[$k][$l]    = $subpropArr[$l]['subproperty_price'];
							$subprovatprice[$k][$l] = $subproperty_price;
							$subprovat[$k][$l]      = $acc_vat;
						}
					}

					/// FOR ACCESSORY PROPERTY AND SUBPROPERTY PRICE CALCULATION
					if ($setPropEqual && $setSubpropEqual)
					{
						$accessory_priceArr = self::makeTotalPriceByOprand($accessory_price, $prooprand, $provatprice);
						$accessory_vatArr   = self::makeTotalPriceByOprand($accessory_vat_price, $prooprand, $provat);
						//$setPropEqual = $accessory_priceArr[0];
						$accessory_price     = $accessory_priceArr[1];
						$accessory_vat_price = $accessory_vatArr[1];
					}

					for ($t = 0, $tn = count($propArr); $t < $tn; $t++)
					{
						if ($setPropEqual && $setSubpropEqual && isset($subprovatprice[$t]))
						{
							$accessory_priceArr  = self::makeTotalPriceByOprand(
								$accessory_price,
								$subprooprand[$t],
								$subprovatprice[$t]
							);
							$accessory_vatArr    = self::makeTotalPriceByOprand(
								$accessory_vat_price,
								$subprooprand[$t],
								$subprovat[$t]
							);
							$accessory_price     = $accessory_priceArr[1];
							$accessory_vat_price = $accessory_vatArr[1];
						}
					}

					// FOR ACCESSORY PROPERTY AND SUBPROPERTY PRICE CALCULATION
				}

				$accessory_total_price += ($accessory_price);
			}

			$displayaccessory .= RedshopLayoutHelper::render(
				'product.product_accessory',
				array(
					'accessories' => $attArr,
					'productId'   => $product_id,
					'userId'      => $user_id,
					'checkTag'    => $chktag
				),
				'',
				array(
					'component' => 'com_redshop'
				)
			);
		}

		$accessory_total_price = $accessory_total_price - $accessory_vat_price;

		return array($displayaccessory, $accessory_total_price, $accessory_vat_price);
	}

	// Get Product subscription price
	public static function getProductSubscriptionDetail($product_id, $subscription_id)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT * "
			. " FROM " . $db->qn('#__redshop_product_subscription')
			. " WHERE "
			. " product_id = " . (int) $product_id . " AND subscription_id = " . (int) $subscription_id;
		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Method for get property or sub object
	 *
	 * @param   string $sectionId Section ID
	 * @param   string $section   Section
	 *
	 * @return  object
	 *
	 */
	public static function getProperty($sectionId, $section)
	{
		if ($section == 'property')
		{
			$properties = RedshopHelperProduct_Attribute::getAttributeProperties($sectionId);

			if (!empty($properties))
			{
				$properties[0]->product_price = $properties[0]->property_price;

				return $properties[0];
			}
		}
		elseif ($section == 'subproperty')
		{
			$properties = RedshopHelperProduct_Attribute::getAttributeSubProperties($sectionId);

			if (!empty($properties))
			{
				$properties[0]->product_price = $properties[0]->subattribute_color_price;

				return $properties[0];
			}
		}

		return null;
	}

	/**
	 * Get Parent Category
	 *
	 * @param int $id
	 *
	 * @return int parentId \ null
	 *
	 */
	public static function getParentCategory($id = 0)
	{
		if ($result = RedshopEntityCategory::getInstance($id)->getItem())
		{
			return $result->parent_id;
		}

		return null;
	}

	/**
	 * @param   integer $productId Product id
	 * @param   integer $relatedId Related id
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getRelatedProduct($productId = 0, $relatedId = 0)
	{
		$db    = JFactory::getDbo();
		$and             = "";
		$orderby         = "ORDER BY p.product_id ASC ";
		$orderby_related = "";

		if (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD'))
		{
			$orderby         = "ORDER BY " . Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD');
			$orderby_related = "";
		}

		if ($productId != 0)
		{
			// Sanitize ids
			$productIds = explode(',', $productId);
			$productIds = Joomla\Utilities\ArrayHelper::toInteger($productIds);

			if (RedshopHelperUtility::isRedProductFinder())
			{
				$q = "SELECT extrafield  FROM #__redproductfinder_types where type_select='Productfinder_datepicker'";
				$db->setQuery($q);
				$finaltypetype_result = $db->loadObject();
			}
			else
			{
				$finaltypetype_result = array();
			}

			$and .= "AND r.product_id IN (" . implode(',', $productIds) . ") ";

			if (Redshop::getConfig()->get('TWOWAY_RELATED_PRODUCT'))
			{
				if (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == "r.ordering ASC" || Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == "r.ordering DESC")
				{
					$orderby         = "";
					$orderby_related = "ORDER BY " . Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD');
				}

				$query = "SELECT * FROM " . $db->qn('#__redshop_product_related') . " AS r "
					. "WHERE r.product_id IN (" . implode(',', $productIds) . ") OR r.related_id IN (" . implode(',', $productIds) . ")" . $orderby_related . "";
				$db->setQuery($query);
				$list = $db->loadObjectlist();

				$relatedArr = array();

				for ($i = 0, $in = count($list); $i < $in; $i++)
				{
					if ($list[$i]->product_id == $productId)
					{
						$relatedArr[] = $list[$i]->related_id;
					}
					else
					{
						$relatedArr[] = $list[$i]->product_id;
					}
				}

				if (empty($relatedArr))
				{
					return array();
				}

				// Sanitize ids
				$relatedArr = Joomla\Utilities\ArrayHelper::toInteger($relatedArr);
				$relatedArr = array_unique($relatedArr);

				$query = "SELECT " . $productId . " AS mainproduct_id,p.* "
					. "FROM " . $db->qn('#__redshop_product') . " AS p "
					. "WHERE p.published = 1 ";
				$query .= ' AND p.product_id IN (' . implode(", ", $relatedArr) . ') ';
				$query .= $orderby;

				$db->setQuery($query);
				$list = $db->loadObjectlist();

				return $list;
			}
		}

		if ($relatedId != 0)
		{
			$and .= "AND r.related_id = " . (int) $relatedId . " ";
		}

		if (count($finaltypetype_result) > 0 && $finaltypetype_result->extrafield != ''
			&& (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC' || Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt DESC'))
		{
			$add_e = ",e.*";
		}
		else
		{
			$add_e = " ";
		}

		$query = "SELECT r.product_id AS mainproduct_id,p.* " . $add_e . " "
			. "FROM " . $db->qn('#__redshop_product_related') . " AS r "
			. "LEFT JOIN " . $db->qn('#__redshop_product') . " AS p ON p.product_id = r.related_id ";

		if (!empty($finaltypetype_result) && !empty($finaltypetype_result->extrafield)
			&& (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC'
				|| Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt DESC'))
		{
			$query .= " LEFT JOIN " . $db->qn('#__fields_data') . " AS e ON p.product_id = e.itemid ";
		}

		$query .= " WHERE p.published = 1 ";

		if (count($finaltypetype_result) > 0 && $finaltypetype_result->extrafield != ''
			&& (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC' || Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt DESC'))
		{
			$query .= " AND e.fieldid = " . (int) $finaltypetype_result->extrafield . " AND e.section=17 ";
		}

		$query .= " $and GROUP BY r.related_id ";

		if ((Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC'
			|| Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt DESC'))
		{
			if (Redshop::getConfig()->get('DEFAULT_RELATED_ORDERING_METHOD') == 'e.data_txt ASC')
			{
				$s = "STR_TO_DATE( e.data_txt, '%d-%m-%Y' ) ASC";
			}
			else
			{
				$s = "STR_TO_DATE( e.data_txt, '%d-%m-%Y' ) DESC";
			}

			$query .= " ORDER BY " . $s;
		}
		else
		{
			$query .= " $orderby ";
		}

		$db->setQuery($query);

		$list = $db->loadObjectlist();

		return $list;
	}

	public static function getSelectedAccessoryArray($data = array())
	{
		$selectedAccessory    = array();
		$selectedAccessoryQua = array();
		$selectedProperty     = array();
		$selectedSubproperty  = array();

		if (!empty($data['accessory_data']))
		{
			$accessoryData   = explode("@@", $data['accessory_data']);
			$accQuantityData = explode("@@", $data['acc_quantity_data']);

			for ($i = 0, $in = count($accessoryData); $i < $in; $i++)
			{
				if (empty($accessoryData[$i]))
				{
					continue;
				}

				$selectedAccessory[]    = $accessoryData[$i];
				$selectedAccessoryQua[] = $accQuantityData[$i];
			}
		}

		if (!empty($data['acc_property_data']))
		{
			$accessoryPropertyData = explode('@@', $data['acc_property_data']);

			for ($i = 0, $in = count($accessoryPropertyData); $i < $in; $i++)
			{
				$accessoryPropertyData1 = explode('##', $accessoryPropertyData[$i]);
				$countAccessoryProperty = count($accessoryPropertyData1);

				if ($countAccessoryProperty == 0)
				{
					continue;
				}

				for ($ia = 0; $ia < $countAccessoryProperty; $ia++)
				{
					$accessoryPropertyData2  = explode(',,', $accessoryPropertyData1[$ia]);
					$countAccessoryProperty2 = count($accessoryPropertyData2);

					if ($countAccessoryProperty2 == 0)
					{
						continue;
					}

					for ($ip = 0; $ip < $countAccessoryProperty2; $ip++)
					{
						if ($accessoryPropertyData2[$ip] == "")
						{
							continue;
						}

						$selectedProperty[] = $accessoryPropertyData2[$ip];
					}
				}
			}
		}

		if (!empty($data['acc_subproperty_data']))
		{
			$accessorySubpropertyData = explode('@@', $data['acc_subproperty_data']);

			for ($i = 0, $in = count($accessorySubpropertyData); $i < $in; $i++)
			{
				$accessorySubpropertyData1 = explode('##', $accessorySubpropertyData[$i]);
				$countAccessorySubroperty  = count($accessorySubpropertyData1);

				if ($countAccessorySubroperty == 0)
				{
					continue;
				}

				for ($ia = 0; $ia < $countAccessorySubroperty; $ia++)
				{
					$accessorySubpropertyData2 = explode(',,', $accessorySubpropertyData1[$ia]);
					$countAccessorySubroperty2 = count($accessorySubpropertyData2);

					if ($countAccessorySubroperty2 == 0)
					{
						continue;
					}

					for ($ip = 0; $ip < $countAccessorySubroperty2; $ip++)
					{
						$accessorySubpropertyData3 = explode('::', $accessorySubpropertyData2[$ip]);
						$countAccessorySubroperty3 = count($accessorySubpropertyData3);

						if ($countAccessorySubroperty3 == 0)
						{
							continue;
						}

						for ($isp = 0; $isp < $countAccessorySubroperty3; $isp++)
						{
							if ($accessorySubpropertyData3[$isp] == "")
							{
								continue;
							}

							$selectedSubproperty[] = $accessorySubpropertyData3[$isp];
						}
					}
				}
			}
		}

		return array($selectedAccessory, $selectedProperty, $selectedSubproperty, $selectedAccessoryQua);
	}

	public static function getSelectedAttributeArray($data = array())
	{
		$selectedProperty    = array();
		$selectedsubproperty = array();

		if (!empty($data['property_data']))
		{
			$acc_property_data = explode('##', $data['property_data']);

			for ($ia = 0, $countProperty = count($acc_property_data); $ia < $countProperty; $ia++)
			{
				$acc_property_data1 = explode(',,', $acc_property_data[$ia]);
				$countProperty1     = count($acc_property_data1);

				for ($ip = 0; $ip < $countProperty1; $ip++)
				{
					if ($acc_property_data1[$ip] != "")
					{
						$selectedProperty[] = $acc_property_data1[$ip];
					}
				}
			}
		}

		if (!empty($data['subproperty_data']))
		{
			$acc_subproperty_data = explode('##', $data['subproperty_data']);
			$countSubproperty     = count($acc_subproperty_data);

			for ($ia = 0; $ia < $countSubproperty; $ia++)
			{
				$acc_subproperty_data1 = @explode('::', $acc_subproperty_data[$ia]);
				$countSubproperty1     = count($acc_subproperty_data1);

				for ($ip = 0; $ip < $countSubproperty1; $ip++)
				{
					$acc_subproperty_data2 = explode(',,', $acc_subproperty_data1[$ip]);
					$countSubproperty2     = count($acc_subproperty_data2);

					for ($isp = 0; $isp < $countSubproperty2; $isp++)
					{
						if ($acc_subproperty_data2[$isp] != "")
						{
							$selectedsubproperty[] = $acc_subproperty_data2[$isp];
						}
					}
				}
			}
		}

		$ret = array($selectedProperty, $selectedsubproperty);

		return $ret;
	}

	/**
	 * replace related product attribute price list
	 *
	 * child product as related product concept is included
	 *    New Tag : {relproduct_attribute_pricelist} = related product attribute price list
	 *
	 * @params: $id :  product id
	 * @params: $templatedata : template data
	 */
	public static function replaceAttributePriceList($id, $templatedata)
	{
		$output     = "";
		$attributes = RedshopHelperProduct_Attribute::getProductAttribute($id, 0, 0, 1);

		$k = 0;

		for ($i = 0, $in = count($attributes); $i < $in; $i++)
		{
			$attribute      = $attributes[$i];
			$attribute_name = $attribute->text;
			$attribute_id   = $attribute->value;
			$propertys      = RedshopHelperProduct_Attribute::getAttributeProperties(0, $attribute_id);

			for ($p = 0, $pn = count($propertys); $p < $pn; $p++)
			{
				$property = $propertys[$p];

				$property_id             = $property->value;
				$property_name           = $property->text;
				$proprty_price           = $property->property_price;
				$property_formated_price = RedshopHelperProductPrice::formattedPrice($proprty_price);
				$proprty_oprand          = $property->oprand;

				$output .= '<div class="related_plist_property_name' . $k . '">' . $property_formated_price . '</div>';

				$subpropertys = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property_id);

				for ($s = 0, $sn = count($subpropertys); $s < $sn; $s++)
				{
					$subproperty = $subpropertys[$s];

					$subproperty_id    = $subproperty->value;
					$subproperty_name  = $subproperty->text;
					$subproprty_price  = $subproperty->subattribute_color_price;
					$subproprty_oprand = $subproperty->oprand;
				}

				$k++;
			}
		}
		#$output = ($output!="") ? "<div>".$output."</div>" : "";
		$templatedata = str_replace("{relproduct_attribute_pricelist}", $output, $templatedata);

		return $templatedata;
	}

	/**
	 * Get formatted number
	 *
	 * @param   float   $price         Price amount
	 * @param   boolean $convertSigned True for convert negative price to absolution price.
	 *
	 * @return  string                   Formatted price.
	 */
	public static function redpriceDecimal($price, $convertSigned = true)
	{
		$price = ($convertSigned == true) ? abs($price) : $price;

		return number_format($price, Redshop::getConfig()->get('PRICE_DECIMAL'), '.', '');
	}

	/**
	 * @param   object  $order     Order object
	 * @param   integer $sectionId Section Id
	 *
	 * @return  string
	 */
	public static function getPaymentandShippingExtrafields($order, $sectionId)
	{
		$fieldsList = RedshopHelperExtrafields::getSectionFieldList($sectionId, 1);
		$resultArr  = array();

		foreach ($fieldsList as $field)
		{
			$result = RedshopHelperExtrafields::getData($field->id, $sectionId, $order->order_id);

			if (!is_null($result) && $result->data_txt != "" && $field->show_in_front == 1)
			{
				$resultArr[] = $result->title . " : " . $result->data_txt;
			}
		}

		$return = "";

		if (!empty($resultArr))
		{
			$return = implode("<br/>", $resultArr);
		}

		return $return;
	}

	public static function getuserfield($orderitemid = 0, $section_id = 12)
	{
		$resultArr = array();

		$userfield = RedshopHelperOrder::getOrderUserFieldData($orderitemid, $section_id);

		if (!empty($userfield))
		{
			$orderItem  = RedshopHelperOrder::getOrderItemDetail(0, 0, $orderitemid);
			$product_id = $orderItem[0]->product_id;

			$productdetail   = self::getProductById($product_id);
			$productTemplate = RedshopHelperTemplate::getTemplate("product", $productdetail->product_template);

			$returnArr    = self::getProductUserfieldFromTemplate($productTemplate[0]->template_desc);
			$userFieldTag = $returnArr[1];

			for ($i = 0, $in = count($userFieldTag); $i < $in; $i++)
			{
				for ($j = 0, $jn = count($userfield); $j < $jn; $j++)
				{
					if ($userfield[$j]->name == $userFieldTag[$i])
					{
						if ($userfield[$j]->type == 10)
						{
							$files    = explode(",", $userfield[$j]->data_txt);
							$data_txt = "";

							for ($f = 0, $fn = count($files); $f < $fn; $f++)
							{
								$u_link   = REDSHOP_FRONT_DOCUMENT_ABSPATH . "product/" . $files[$f];
								$data_txt .= "<a href='" . $u_link . "' target='_blank'>" . $files[$f] . "</a> ";
							}

							if (trim($data_txt) != "")
							{
								$resultArr[] = '<span class="userfield-label"">' . $userfield[$j]->title
									. ':</span><span class="userfield-value">' . stripslashes($data_txt) . '</span>';
							}
						}
						else
						{
							if (trim($userfield[$j]->data_txt) != "")
							{
								$resultArr[] = '<span class="userfield-label"">' . $userfield[$j]->title
									. '</span> : <span class="userfield-value">' . stripslashes($userfield[$j]->data_txt);
							}
						}
					}
				}
			}
		}

		$resultstr = "";

		if (empty($resultArr))
		{
			return $resultstr;
		}

		return "<div>" . JText::_("COM_REDSHOP_PRODUCT_USERFIELD") . "</div><div>" . implode("<br/>", $resultArr) . "</div>";
	}

	public static function makeAttributeOrder($order_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $stock = 0, $export = 0, $data = '')
	{
		$chktag            = \Redshop\Template\Helper::isApplyAttributeVat($data);
		$product_attribute = "";
		$quantity          = 0;
		$stockroom_id      = "0";
		$orderItemdata     = RedshopHelperOrder::getOrderItemDetail(0, 0, $order_item_id);
		$cartAttributes    = array();

		$products = self::getProductById($orderItemdata[0]->product_id);

		if (count($orderItemdata) > 0 && $is_accessory != 1)
		{
			$product_attribute = $orderItemdata[0]->product_attribute;
			$quantity          = $orderItemdata[0]->product_quantity;
		}

		$orderItemAttdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "attribute", $parent_section_id);

		// Get Attribute middle template
		$attribute_middle_template = \Redshop\Template\Helper::getAttributeTemplateLoop($data);
		$attribute_final_template  = '';

		if (count($orderItemAttdata) > 0)
		{
			for ($i = 0, $in = count($orderItemAttdata); $i < $in; $i++)
			{
				$attribute = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $orderItemAttdata[$i]->section_id);

				// Assign Attribute middle template in tmp variable
				$tmp_attribute_middle_template = $attribute_middle_template;
				$tmp_attribute_middle_template = str_replace(
					"{product_attribute_name}", urldecode($orderItemAttdata[$i]->section_name), $tmp_attribute_middle_template
				);

				$orderPropdata = RedshopHelperOrder::getOrderItemAttributeDetail(
					$order_item_id, $is_accessory, "property", $orderItemAttdata[$i]->section_id
				);

				// Initialize attribute calculated price
				$propertyCalculatedPriceSum = $orderItemdata[0]->product_item_old_price;

				for ($p = 0, $pn = count($orderPropdata); $p < $pn; $p++)
				{
					$property_price                  = $orderPropdata[$p]->section_price;
					$productAttributeCalculatedPrice = 0;

					if ($stock == 1)
					{
						RedshopHelperStockroom::manageStockAmount($orderPropdata[$p]->section_id, $quantity, $orderPropdata[$p]->stockroom_id, "property");
					}

					$property = RedshopHelperProduct_Attribute::getAttributeProperties($orderPropdata[$p]->section_id);

					if (!empty($chktag))
					{
						$property_price = $orderPropdata[$p]->section_price + $orderPropdata[$p]->section_vat;
					}

					// Show actual productive price
					if ($export == 0 && $property_price > 0)
					{
						$propertyOperand                     = $orderPropdata[$p]->section_oprand;
						$productAttributeCalculatedPriceBase = RedshopHelperUtility::setOperandForValues(
							$propertyCalculatedPriceSum, $propertyOperand, $property_price
						);
						$productAttributeCalculatedPrice     = $productAttributeCalculatedPriceBase - $propertyCalculatedPriceSum;
						$propertyCalculatedPriceSum          = $productAttributeCalculatedPriceBase;
					}

					$disPrice           = '';
					$hideAttributePrice = count($attribute) > 0 ? $attribute[0]->hide_attribute_price : 0;

					if (strpos($data, '{product_attribute_price}') !== false)
					{
						if ($export == 1)
						{
							$disPrice = ' (' . $orderPropdata[$p]->section_oprand . Redshop::getConfig()->get('CURRENCY_SYMBOL') . $property_price . ')';
						}
						elseif (!$hideAttributePrice)
						{
							$disPrice = " (" . $orderPropdata[$p]->section_oprand . RedshopHelperProductPrice::formattedPrice($property_price) . ")";
						}
					}

					// Replace attribute property price and value
					$tmp_attribute_middle_template = str_replace("{product_attribute_value}", urldecode($orderPropdata[$p]->section_name), $tmp_attribute_middle_template);
					$tmp_attribute_middle_template = str_replace("{product_attribute_value_price}", $disPrice, $tmp_attribute_middle_template);

					// Assign tmp variable to looping variable to get copy of all texts
					$attribute_final_template .= $tmp_attribute_middle_template;

					// Initialize attribute child array
					$attributeChilds = array(
						'property_id'     => $orderPropdata[$p]->section_id,
						'property_name'   => $orderPropdata[$p]->section_name,
						'property_oprand' => $orderPropdata[$p]->section_oprand,
						'property_price'  => $property_price,
						'property_childs' => array()
					);

					$orderSubpropdata = RedshopHelperOrder::getOrderItemAttributeDetail($order_item_id, $is_accessory, "subproperty", $orderPropdata[$p]->section_id);

					for ($sp = 0, $countSubproperty = count($orderSubpropdata); $sp < $countSubproperty; $sp++)
					{
						$subproperty_price = $orderSubpropdata[$sp]->section_price;

						if ($stock == 1)
						{
							RedshopHelperStockroom::manageStockAmount($orderSubpropdata[$sp]->section_id, $quantity, $orderSubpropdata[$sp]->stockroom_id, "subproperty");
						}

						$subproperty = RedshopHelperProduct_Attribute::getAttributeSubProperties($orderSubpropdata[$sp]->section_id);

						if (!empty($chktag))
						{
							$subproperty_price = $orderSubpropdata[$sp]->section_price + $orderSubpropdata[$sp]->section_vat;
						}

						// Show actual productive price
						if ($export == 0 && $subproperty_price > 0)
						{
							$subPropertyOperand                  = $orderSubpropdata[$sp]->section_oprand;
							$productAttributeCalculatedPriceBase = RedshopHelperUtility::setOperandForValues(
								$propertyCalculatedPriceSum, $subPropertyOperand, $subproperty_price
							);
							$productAttributeCalculatedPrice     = $productAttributeCalculatedPriceBase - $propertyCalculatedPriceSum;
							$propertyCalculatedPriceSum          = $productAttributeCalculatedPriceBase;
						}

						$attributeChilds['property_childs'][] = array(
							'subproperty_id'           => $orderSubpropdata[$sp]->section_id,
							'subproperty_name'         => $orderSubpropdata[$sp]->section_name,
							'subproperty_oprand'       => $orderSubpropdata[$sp]->section_oprand,
							'subattribute_color_title' => urldecode($subproperty[0]->subattribute_color_title),
							'subproperty_price'        => $subproperty_price
						);
					}

					// Format Calculated price using Language variable
					$productAttributeCalculatedPrice = RedshopHelperProductPrice::formattedPrice($productAttributeCalculatedPrice);
					$productAttributeCalculatedPrice = JText::sprintf('COM_REDSHOP_CART_PRODUCT_ATTRIBUTE_CALCULATED_PRICE', $productAttributeCalculatedPrice);
					$tmp_attribute_middle_template   = str_replace(
						"{product_attribute_calculated_price}",
						$productAttributeCalculatedPrice,
						$tmp_attribute_middle_template
					);

					// Assign tmp variable to looping variable to get copy of all texts
					$attribute_final_template = $tmp_attribute_middle_template;

					// Initialize attribute child array
					$attribute[0]->attribute_childs[] = $attributeChilds;
				}

				// Prepare cart type attribute array
				$cartAttributes[] = get_object_vars($attribute[0]);
			}

			$displayattribute = RedshopLayoutHelper::render(
				'product.order_attribute',
				array(
					'orderItemAttdata' => $orderItemAttdata,
					'data'             => $data,
					'orderItemId'      => $order_item_id,
					'isAccessory'      => $is_accessory,
					'chktag'           => $chktag,
					'export'           => $export
				),
				'',
				array(
					'component' => 'com_redshop',
					'client'    => 0
				)
			);
		}
		else
		{
			$displayattribute = $product_attribute;
		}

		if (isset($products->use_discount_calc) && $products->use_discount_calc == 1)
		{
			$displayattribute = $displayattribute . $orderItemdata[0]->discount_calc_data;
		}

		$data                                 = new stdClass;
		$data->product_attribute              = $displayattribute;
		$data->attribute_middle_template      = $attribute_final_template;
		$data->attribute_middle_template_core = $attribute_middle_template;
		$data->cart_attribute                 = $cartAttributes;

		return $data;
	}

	/*
	 * load Products Under categoriesd ACL Sopper Group
	 *
	 *  return : "," separated product string
	 */
	public static function loadAclProducts()
	{
		$db    = JFactory::getDbo();
		$user    = JFactory::getUser();
		$userArr = JFactory::getSession()->get('rs_user');

		if (empty($userArr))
		{
			$userArr = RedshopHelperUser::createUserSession($user->id);
		}

		$shopperGroupId = $userArr['rs_user_shopperGroup'];

		if ($user->id > 0)
			$catquery = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg LEFT JOIN #__redshop_users_info as uf ON sg.`shopper_group_id` = uf.shopper_group_id WHERE uf.user_id = '" . $user->id . "' AND sg.shopper_group_portal=1 ";
		else
			$catquery = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg WHERE  sg.`shopper_group_id` = " . (int) $shopperGroupId . " AND sg.shopper_group_portal=1";

		$db->setQuery($catquery);
		$category_ids_obj = $db->loadObjectList();
		if (empty($category_ids_obj))
		{
			return "";
		}
		else
		{
			$category_ids = $category_ids_obj[0]->shopper_group_categories;
		}

		// Sanitize ids
		$catIds = explode(',', $category_ids);
		$catIds = Joomla\Utilities\ArrayHelper::toInteger($catIds);

		$query = "SELECT product_id
						FROM `#__redshop_product_category_xref` WHERE category_id IN (" . implode(',', $catIds) . ")";

		$db->setQuery($query);
		$shopperprodata = $db->loadObjectList();
		$aclProduct     = array();

		for ($i = 0, $in = count($shopperprodata); $i < $in; $i++)
		{
			$aclProduct[] = $shopperprodata[$i]->product_id;
		}

		if (count($aclProduct) > 0)
			$aclProduct = implode(",", $aclProduct);
		else
			$aclProduct = "";

		return $aclProduct;
	}

	public static function makeAttributeQuotation($quotation_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quotation_status = 2, $stock = 0)
	{
		$displayattribute  = "";
		$product_attribute = "";
		$quantity          = 0;
		$stockroom_id      = "0";
		$Itemdata          = RedshopHelperQuotation::getQuotationProduct(0, $quotation_item_id);

		if (count($Itemdata) > 0 && $is_accessory != 1)
		{
			$product_attribute = $Itemdata[0]->product_attribute;
			$quantity          = $Itemdata[0]->product_quantity;
		}

		$ItemAttdata = RedshopHelperQuotation::getQuotationItemAttributeDetail(
			$quotation_item_id,
			$is_accessory,
			"attribute",
			$parent_section_id
		);

		$displayattribute = RedshopLayoutHelper::render(
			'product.quotation_attribute',
			array(
				'itemAttdata'     => $ItemAttdata,
				'quotationItemId' => $quotation_item_id,
				'isAccessory'     => $is_accessory,
				'quotationStatus' => $quotation_status,
				'parentSectionId' => $parent_section_id,
				'stock'           => $stock
			),
			'',
			array(
				'client'    => 0,
				'component' => 'com_redshop'
			)
		);

		return $displayattribute;
	}

	public static function makeAccessoryQuotation($quotation_item_id = 0, $quotation_status = 2)
	{
		$displayaccessory = "";
		$Itemdata         = RedshopHelperQuotation::getQuotationItemAccessoryDetail($quotation_item_id);

		if (count($Itemdata) > 0)
		{
			$displayaccessory .= "<div class='checkout_accessory_static'>" . JText::_("COM_REDSHOP_ACCESSORY") . ":</div>";

			for ($i = 0, $in = count($Itemdata); $i < $in; $i++)
			{
				$displayaccessory .= "<div class='checkout_accessory_title'>" . urldecode($Itemdata[$i]->accessory_item_name) . " ";

				if ($quotation_status != 1 || ($quotation_status == 1 && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE') == 1))
				{
					$displayaccessory .= "(" . RedshopHelperProductPrice::formattedPrice($Itemdata[$i]->accessory_price + $Itemdata[$i]->accessory_vat) . ")";
				}

				$displayaccessory .= "</div>";
				$displayaccessory .= self::makeAttributeQuotation(
					$quotation_item_id,
					1,
					$Itemdata[$i]->accessory_id,
					$quotation_status
				);

			}
		}
		else
		{
			$Itemdata         = RedshopHelperQuotation::getQuotationProduct(0, $quotation_item_id);
			$displayaccessory = $Itemdata[0]->product_accessory;
		}

		return $displayaccessory;
	}

	public static function getValidityDate($period, $data)
	{
		$todate = mktime(0, 0, 0, (int) date('m'), (int) date('d') + $period, (int) date('Y'));

		$todate   = RedshopHelperDatetime::convertDateFormat($todate);
		$fromdate = RedshopHelperDatetime::convertDateFormat(strtotime(date('d M Y')));

		$data = str_replace("{giftcard_validity_from}", JText::_('COM_REDSHOP_FROM') . " " . $fromdate, $data);
		$data = str_replace("{giftcard_validity_to}", JText::_('COM_REDSHOP_TO') . " " . $todate, $data);

		return $data;
	}

	/**
	 * @param   array   $cart
	 * @param   integer $orderId
	 * @param   integer $sectionId
	 *
	 * @return  false|mixed|void
	 */
	public static function insertPaymentShippingField($cart = array(), $orderId = 0, $sectionId = 18)
	{
		$fieldsList = RedshopHelperExtrafields::getSectionFieldList($sectionId, 1);

		if (empty($fieldsList))
		{
			return;
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->insert($db->quoteName('#__redshop_fields_data'))
			->columns($db->quoteName(array('fieldid', 'data_txt', 'itemid', 'section')));

		foreach ($fieldsList as $fieldList)
		{
			$userFields = '';

			if (isset($cart['extrafields_values']))
			{
				$userFields = $cart['extrafields_values'][$fieldList->name];
			}

			if (!empty(trim($userFields)))
			{
				$values = array(
					(int) $fieldList->id,
					$db->quote(addslashes($userFields)),
					(int) $orderId,
					$db->quote($sectionId)
				);
				$query->values(implode(',', $values));
			}
		}

		return $db->setQuery($query)->execute();
	}

	public static function getProductMediaName($product_id)
	{
		$db    = JFactory::getDbo();
		$query = 'SELECT media_name FROM ' . $db->qn('#__media')
			. 'WHERE media_section = "product" '
			. 'AND media_type="download" '
			. 'AND published=1 AND section_id = ' . (int) $product_id;
		$db->setQuery($query);
		$res = $db->loadObjectList();

		return $res;
	}

	public static function getProdcutSerialNumber($product_id, $is_used = 0)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT * FROM " . $db->qn('#__redshop_product_serial_number')
			. "WHERE product_id = " . (int) $product_id . " "
			. " AND is_used = " . (int) $is_used . " "
			. " LIMIT 0,1";
		$db->setQuery($query);
		$rs = $db->loadObject();

		if (count($rs) > 0)
		{
			// Update serial number...
			self::updateProdcutSerialNumber($rs->serial_id);
		}
		else
		{
			$rs->serial_number = "";
		}

		return $rs;
	}

	/*
	 *  Update used seraial number status
	 */
	public static function updateProdcutSerialNumber($serial_id)
	{
		$db    = JFactory::getDbo();
		$update_query = "UPDATE " . $db->qn('#__redshop_product_serial_number')
			. " SET is_used='1' WHERE serial_id = " . (int) $serial_id;
		$db->setQuery($update_query);
		$db->execute();
	}

	public static function insertProductDownload($product_id, $user_id, $order_id, $media_name, $serial_number)
	{
		$db = JFactory::getDbo();

		// download data
		$downloadable_product = RedshopHelperProductDownload::checkDownload($product_id, true); //die();

		$product_download_limit = ($downloadable_product->product_download_limit > 0) ? $downloadable_product->product_download_limit : Redshop::getConfig()->get('PRODUCT_DOWNLOAD_LIMIT');

		$product_download_days      = ($downloadable_product->product_download_days > 0) ? $downloadable_product->product_download_days : Redshop::getConfig()->get('PRODUCT_DOWNLOAD_DAYS');
		$product_download_clock     = ($downloadable_product->product_download_clock > 0) ? $downloadable_product->product_download_clock : 0;
		$product_download_clock_min = ($downloadable_product->product_download_clock_min > 0) ? $downloadable_product->product_download_clock_min : 0;

		$product_download_days = (date("H") > $product_download_clock && $product_download_days == 0) ? 1 : $product_download_days;

		$product_download_days_time = (time() + ($product_download_days * 24 * 60 * 60));

		$endtime = mktime(
			$product_download_clock,
			$product_download_clock_min,
			0,
			(int) date("m", $product_download_days_time),
			(int) date("d", $product_download_days_time),
			(int) date("Y", $product_download_days_time)
		);

		// if download product is set to infinit
		$endtime = ($downloadable_product->product_download_infinite == 1) ? 0 : $endtime;

		// Generate Download Token
		$token = md5(uniqid(mt_rand(), true));

		$sql = "INSERT INTO " . $db->qn('#__redshop_product_download')
			. "(product_id,user_id,order_id, end_date, download_max, download_id, file_name,product_serial_number) "
			. "VALUES(" . (int) $product_id . ", " . (int) $user_id . ", " . (int) $order_id . ", "
			. (int) $endtime . ", " . (int) $product_download_limit . ", "
			. $db->quote($token) . ", " . $db->quote($media_name) . "," . $db->quote($serial_number) . ")";
		$db->setQuery($sql);
		$db->execute();

		return true;
	}

	public static function insertProdcutUserfield($id = 'NULL', $cart = array(), $order_item_id = 0, $section_id = 12)
	{
		$db = JFactory::getDbo();

		$row_data = RedshopHelperExtrafields::getSectionFieldList($section_id, 1);

		for ($i = 0, $in = count($row_data); $i < $in; $i++)
		{
			if (array_key_exists($row_data[$i]->name, $cart[$id]) && $cart[$id][$row_data[$i]->name])
			{
				$user_fields = $cart[$id][$row_data[$i]->name];

				if (trim($user_fields) != '')
				{
					$sql = "INSERT INTO " . $db->qn('#__fields_data')
						. "(fieldid,data_txt,itemid,section) "
						. "value (" . (int) $row_data[$i]->id . "," . $db->quote(addslashes($user_fields)) . ","
						. (int) $order_item_id . "," . $db->quote($section_id) . ")";
					$db->setQuery($sql);
					$db->execute();
				}
			}
		}

		return;
	}

	public static function makeTotalPriceByOprand($price = 0, $oprandArr = array(), $priceArr = array())
	{
		$setEqual = true;

		for ($i = 0, $in = count($oprandArr); $i < $in; $i++)
		{
			$oprand   = $oprandArr[$i];
			$subprice = $priceArr[$i];

			if ($oprand == "-")
			{
				$price -= $subprice;
			}
			elseif ($oprand == "+")
			{
				$price += $subprice;
			}
			elseif ($oprand == "*")
			{
				$price *= $subprice;
			}
			elseif ($oprand == "/")
			{
				$price /= $subprice;
			}
			elseif ($oprand == "=")
			{
				$price    = $subprice;
				$setEqual = false;
				break;
			}
		}

		$retArr    = array();
		$retArr[0] = $setEqual;
		$retArr[1] = $price;

		return $retArr;
	}

	public static function replaceSubPropertyData($product_id = 0, $accessory_id = 0, $relatedprd_id = 0, $attribute_id = 0, $property_id = 0, $subatthtml = "", $layout = "", $selectSubproperty = array())
	{
		$attribute_table = "";
		$subproperty     = array();

		/** @scrutinizer ignore-deprecated */
		JHtml::script('com_redshop/redshop.thumbscroller.min.js', false, true);
		$chkvatArr = JFactory::getSession()->get('chkvat');
		$chktag    = $chkvatArr['chkvat'];

		$preprefix = "";
		$isAjax    = 0;

		if ($layout == "viewajaxdetail")
		{
			$preprefix = "ajax_";
			$isAjax    = 1;
		}

		if ($property_id != 0 && $attribute_id != 0)
		{
			$attributes      = RedshopHelperProduct_Attribute::getProductAttribute(0, 0, $attribute_id);
			$attributes      = $attributes[0];
			$subproperty_all = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property_id);
			// filter Out of stock data
			if (!Redshop::getConfig()->get('DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA') && Redshop::getConfig()->get('USE_STOCKROOM'))
			{
				$subproperty = \Redshop\Helper\Stockroom::getAttributeSubPropertyWithStock($subproperty_all);
			}
			else
			{
				$subproperty = $subproperty_all;
			}

			// Get stockroom and pre-order stockroom data.
			$subPropertyIds     = array_map(
				function ($item) {
					return $item->value;
				},
				$subproperty
			);
			$stockrooms         = RedshopHelperStockroom::getMultiSectionsStock($subPropertyIds, 'subproperty');
			$preOrderStockrooms = RedshopHelperStockroom::getMultiSectionsPreOrderStock($subPropertyIds, 'subproperty');

			foreach ($subproperty as $i => $item)
			{
				$subproperty[$i]->stock          = isset($stockrooms[$item->value]) ? (int) $stockrooms[$item->value] : 0;
				$subproperty[$i]->preorder_stock = isset($preOrderStockrooms[$item->value]) ? (int) $preOrderStockrooms[$item->value] : 0;
			}
		}

		if ($accessory_id != 0)
		{
			$prefix = $preprefix . "acc_";
		}
		elseif ($relatedprd_id != 0)
		{
			$prefix = $preprefix . "rel_";
		}
		else
		{
			$prefix = $preprefix . "prd_";
		}

		if ($relatedprd_id != 0)
		{
			$product_id = $relatedprd_id;
		}

		$product         = self::getProductById($product_id);
		$producttemplate = RedshopHelperTemplate::getTemplate("product", $product->product_template);

		if (strpos($producttemplate[0]->template_desc, "{more_images_3}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_3');
		}
		elseif (strpos($producttemplate[0]->template_desc, "{more_images_2}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_2');
		}
		elseif (strpos($producttemplate[0]->template_desc, "{more_images_1}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}
		else
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}

		if ($subatthtml != "")
		{
			// Load plugin group
			JPluginHelper::importPlugin('redshop_product');

			if (count($subproperty) > 0)
			{
				$attribute_table     = $subatthtml;
				$attribute_table     .= '<span id="subprop_lbl" style="display:none;">'
					. JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED') . '</span>';
				$commonid            = $prefix . $product_id . '_' . $accessory_id . '_' . $attribute_id . '_'
					. $property_id;
				$subpropertyid       = 'subproperty_id_' . $commonid;
				$selectedsubproperty = 0;
				$imgAdded            = 0;

				$subproperty_woscrollerdiv = "";

				if (strpos($subatthtml, "{subproperty_image_without_scroller}") !== false)
				{
					$attribute_table           = str_replace("{subproperty_image_scroller}", "", $attribute_table);
					$subproperty_woscrollerdiv .= "<div class='subproperty_main_outer' id='subproperty_main_outer'>";
				}

				$subprop_Arry    = array();
				$preselectSubPro = true;

				for ($i = 0, $in = count($subproperty); $i < $in; $i++)
				{
					if (count($selectSubproperty) > 0)
					{
						if (in_array($subproperty[$i]->value, $selectSubproperty))
						{
							$selectedsubproperty = $subproperty[$i]->value;
						}
					}
					else
					{
						if ($subproperty[$i]->setdefault_selected)
						{
							$selectedsubproperty = $subproperty[$i]->value;
						}
					}

					if (!empty($subproperty[$i]->subattribute_color_image))
					{
						if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $subproperty[$i]->subattribute_color_image))
						{
							$borderstyle    = ($selectedsubproperty == $subproperty[$i]->value) ? " 1px solid " : "";
							$thumbUrl       = RedshopHelperMedia::getImagePath(
								$subproperty[$i]->subattribute_color_image,
								'',
								'thumb',
								'subcolor',
								Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH'),
								Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT'),
								Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
							);
							$subprop_Arry[] = $thumbUrl;
							$style          = null;

							if ($subproperty[$i]->setdefault_selected && $preselectSubPro)
							{
								$style       = ' style="border: 1px solid;"';
								$preselectSubPro = false;
							}

							$subproperty_woscrollerdiv .= "<div id='" . $subpropertyid . "_subpropimg_"
								. $subproperty[$i]->value . "' class='subproperty_image_inner' ". $style ."><a onclick='setSubpropImage(\""
								. $product_id . "\",\"" . $subpropertyid . "\",\"" . $subproperty[$i]->value
								. "\");calculateTotalPrice(\"" . $product_id . "\",\"" . $relatedprd_id
								. "\");displayAdditionalImage(\"" . $product_id . "\",\"" . $accessory_id . "\",\""
								. $relatedprd_id . "\",\"" . $property_id . "\",\"" . $subproperty[$i]->value
								. "\");'><img class='redAttributeImage'  src='" . $thumbUrl . "' title='" . $subproperty[$i]->text . "'></a></div>";

							$imgAdded++;
						}
					}

					$attributes_subproperty_vat_show   = 0;
					$attributes_subproperty_withoutvat = 0;
					$attributes_subproperty_oldprice   = 0;

					if ($subproperty [$i]->subattribute_color_price > 0)
					{
						$attributes_subproperty_oldprice = $subproperty [$i]->subattribute_color_price;

						$pricelist = RedshopHelperProduct_Attribute::getPropertyPrice($subproperty[$i]->value, 1, 'subproperty');

						if (count($pricelist) > 0)
						{
							$subproperty[$i]->subattribute_color_price = $pricelist->product_price;
						}

						$attributes_subproperty_withoutvat = $subproperty [$i]->subattribute_color_price;

						if ($chktag)
						{
							$attributes_subproperty_vat_show = self::getProductTax($product_id, $subproperty [$i]->subattribute_color_price);

							$attributes_subproperty_oldprice_vat = self::getProductTax($product_id, $attributes_subproperty_oldprice);
						}

						$attributes_subproperty_vat_show += $subproperty [$i]->subattribute_color_price;
						$attributes_subproperty_oldprice += $attributes_subproperty_oldprice_vat;

						if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))) && (!$attributes->hide_attribute_price))
						{
							$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name) . " (" . $subproperty [$i]->oprand . strip_tags(RedshopHelperProductPrice::formattedPrice($attributes_subproperty_vat_show)) . ")";
						}
						else
						{
							$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name);
						}
					}
					else
					{
						$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name);
					}

					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_name' . $subproperty [$i]->value . '" value="' . $subproperty [$i]->subattribute_color_name . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_oprand' . $subproperty [$i]->value . '" value="' . $subproperty [$i]->oprand . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_proprice' . $subproperty [$i]->value . '" value="' . $attributes_subproperty_vat_show . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_proprice_withoutvat' . $subproperty [$i]->value . '" value="' . $attributes_subproperty_withoutvat . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_prooldprice' . $subproperty [$i]->value . '" value="' . $attributes_subproperty_oldprice . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_stock' . $subproperty [$i]->value . '" value="' . $subproperty[$i]->stock . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_preOrderStock' . $subproperty [$i]->value . '" value="' . $subproperty[$i]->preorder_stock . '" />';
				}

				if (strpos($subatthtml, "{subproperty_image_without_scroller}") !== false)
				{
					$subproperty_woscrollerdiv .= "</div>";
				}

				// Run event when prepare sub-properties data.
				RedshopHelperUtility::getDispatcher()->trigger('onPrepareProductSubProperties', array($product, &$subproperty));

				if (Redshop::getConfig()->get('USE_ENCODING'))
				{
					$displayPropertyName = mb_convert_encoding(urldecode($subproperty[0]->property_name), "ISO-8859-1", "UTF-8");

				}
				else
				{
					$displayPropertyName = urldecode($subproperty[0]->property_name);
				}

				if ($subproperty[0]->subattribute_color_title != "")
				{
					if (Redshop::getConfig()->get('USE_ENCODING'))
					{
						$displayPropertyName = mb_convert_encoding(
							urldecode($subproperty[0]->subattribute_color_title),
							"ISO-8859-1",
							"UTF-8"
						);
					}
					else
					{
						$displayPropertyName = urldecode($subproperty[0]->subattribute_color_title);
					}
				}

				$subproperties  = array_merge(
					array(JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT') . ' ' . $displayPropertyName)),
					$subproperty
				);
				$attDisplayType = (isset($subproperty[0]->setdisplay_type)) ? $subproperty[0]->setdisplay_type : 'radio';

				// Init listing html-attributes
				$chkListAttributes = array(
					'id'          => $subpropertyid,
					'subpropName' => $displayPropertyName
				);

				// Only add required html-attibute if needed.
				if ($subproperty[0]->setrequire_selected)
				{
					$chkListAttributes['required'] = 'true';
				}

				$scrollerFunction = '';

				if ($imgAdded > 0 && strstr($attribute_table, "{subproperty_image_scroller}"))
				{
					$scrollerFunction = "isFlowers" . $commonid . ".scrollImageCenter(this.selectedIndex-1);";

					if ('radio' == $attDisplayType)
					{
						$scrollerFunction = "isFlowers" . $commonid . ".scrollImageCenter(\"" . $chk . "\");";
					}
				}

				// Prepare Javascript OnChange or OnClick function
				$onChangeJSFunction = $scrollerFunction
					. "calculateTotalPrice('" . $product_id . "','" . $relatedprd_id . "');"
					. "displayAdditionalImage('" . $product_id . "','" . $accessory_id . "','" . $relatedprd_id . "','" . $property_id . "',this.value);";

				// Radio or Checkbox
				if ('radio' == $attDisplayType)
				{
					unset($subproperties[0]);

					$attributeListType = ($subproperty[0]->setmulti_selected) ? 'redshopselect.checklist' : 'redshopselect.radiolist';

					$chkListAttributes['cssClassSuffix'] = ' no-group';
					$chkListAttributes['onClick']        = "javascript:" . $onChangeJSFunction;
				}
				// Dropdown list
				else
				{
					$attributeListType             = 'select.genericlist';
					$chkListAttributes['onchange'] = "javascript:" . $onChangeJSFunction;
				}

				$lists['subproperty_id'] = JHTML::_(
					$attributeListType,
					$subproperties,
					$subpropertyid . '[]',
					$chkListAttributes,
					'value',
					'text',
					$selectedsubproperty,
					$subpropertyid
				);

				$subPropertyScroller = RedshopLayoutHelper::render(
					'product.subproperty_scroller',
					array(
						'subProperties'     => $subproperty,
						'commonId'          => $commonid,
						'productId'         => $product_id,
						'propertyId'        => $property_id,
						'subPropertyId'     => $subpropertyid,
						'accessoryId'       => $accessory_id,
						'relatedProductId'  => $relatedprd_id,
						'selectSubproperty' => $selectedsubproperty,
						'subPropertyArray'  => $subprop_Arry,
						'width'             => $mpw_thumb,
						'height'            => $mph_thumb
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);

				if ($imgAdded === 0 || $isAjax == 1)
				{
					$subPropertyScroller = "";
				}

				if ($subproperty[0]->setrequire_selected == 1)
				{
					$displayPropertyName = Redshop::getConfig()->get('ASTERISK_POSITION') > 0 ? $displayPropertyName . "<span id='asterisk_right'> * </span>" : "<span id='asterisk_left'>* </span>" . $displayPropertyName;
				}
				$attribute_table = str_replace("{property_title}", $displayPropertyName, $attribute_table);
				$attribute_table = str_replace("{subproperty_dropdown}", $lists ['subproperty_id'], $attribute_table);

				if (strpos($subatthtml, "{subproperty_image_without_scroller}") !== false)
				{
					$attribute_table = str_replace("{subproperty_image_scroller}", "", $attribute_table);
					$attribute_table = str_replace("{subproperty_image_without_scroller}", $subproperty_woscrollerdiv, $attribute_table);
				}
				elseif (strpos($subatthtml, "{subproperty_image_scroller}") !== false)
				{
					$attribute_table = str_replace("{subproperty_image_scroller}", $subPropertyScroller, $attribute_table);
					$attribute_table = str_replace("{subproperty_image_without_scroller}", "", $attribute_table);
				}
			}
		}

		return $attribute_table;
	}

	// Get User Product subscription detail
	public static function getUserProductSubscriptionDetail($order_item_id)
	{
		$db = JFactory::getDbo();

		$query = "SELECT * FROM " . $db->qn('#__redshop_product_subscribe_detail') . " AS p "
			. "LEFT JOIN " . $db->qn('#__redshop_product_subscription') . " AS ps ON ps.subscription_id=p.subscription_id "
			. "WHERE order_item_id = " . (int) $order_item_id;
		$db->setQuery($query);
		$list = $db->loadObject();

		return $list;
	}

	/**
	 * Get Product Special Id
	 *
	 * @param   int $userId User Id
	 *
	 * @return  string
	 */
	public static function getProductSpecialId($userId)
	{
		if (array_key_exists($userId, self::$productSpecialIds))
		{
			return self::$productSpecialIds[$userId];
		}

		$db = JFactory::getDbo();

		if ($userId)
		{
			RedshopHelperUser::createUserSession($userId);

			$query = $db->getQuery(true)
				->select('ps.discount_product_id')
				->from($db->qn('#__redshop_discount_product_shoppers', 'ps'))
				->leftJoin($db->qn('#__redshop_users_info', 'ui') . ' ON ui.shopper_group_id = ps.shopper_group_id')
				->where('ui.user_id = ' . (int) $userId)
				->where('ui.address_type = ' . $db->q('BT'));
		}
		else
		{
			$userArr = JFactory::getSession()->get('rs_user');

			if (empty($userArr))
			{
				$userArr = RedshopHelperUser::createUserSession($userId);
			}

			$shopperGroupId = isset($userArr['rs_user_shopperGroup']) ?
				$userArr['rs_user_shopperGroup'] : RedshopHelperUser::getShopperGroup($userId);

			$query = $db->getQuery(true)
				->select('dps.discount_product_id')
				->from($db->qn('#__redshop_discount_product_shoppers', 'dps'))
				->where('dps.shopper_group_id =' . (int) $shopperGroupId);
		}

		$result = $db->setQuery($query)->loadColumn();

		self::$productSpecialIds[$userId] = '0';

		if (!empty($result))
		{
			self::$productSpecialIds[$userId] .= ',' . implode(',', $result);
		}

		return self::$productSpecialIds[$userId];
	}

	public static function isProductDateRange($userfieldArr, $product_id)
	{
		$db = JFactory::getDbo();
		$isEnable = true;

		if (count($userfieldArr) <= 0)
		{
			$isEnable = false;

			return $isEnable;
		}

		if (!array_key_exists('15', self::$productDateRange))
		{
			$query = $db->getQuery(true)
				->select('name, id')
				->from($db->qn('#__redshop_fields'))
				->where('type = 15');
			$db->setQuery($query);
			self::$productDateRange['15'] = $db->loadObject();
		}

		$fieldData = self::$productDateRange['15'];

		if (!$fieldData)
		{
			$isEnable = false;

			return $isEnable;
		}

		$field_name = $fieldData->name;

		if (is_array($userfieldArr))
		{
			if (in_array($field_name, $userfieldArr))
			{
				$field_id  = $fieldData->id;
				$dateQuery = "select data_txt from " . $db->qn('#__fields_data') . " where fieldid = " . (int) $field_id . " AND itemid = " . (int) $product_id;
				$db->setQuery($dateQuery);
				$datedata = $db->loadObject();

				if (count($datedata) > 0)
				{
					$data_txt             = $datedata->data_txt;
					$mainsplit_date_total = preg_split(" ", $data_txt);
					$mainsplit_date       = preg_split(":", $mainsplit_date_total[0]);

					$dateStart = mktime(
						0,
						0,
						0,
						(int) date('m', $mainsplit_date[0]),
						(int) date('d', $mainsplit_date[0]),
						(int) date('Y', $mainsplit_date[0])
					);

					$dateEnd = mktime(
						23,
						59,
						59,
						(int) date('m', $mainsplit_date[1]),
						(int) date('d', $mainsplit_date[1]),
						(int) date('Y', $mainsplit_date[1])
					);

					$todayStart = mktime(
						0,
						0,
						0,
						(int) date('m'),
						(int) date('d'),
						(int) date('Y')
					);

					$todayEnd = mktime(23, 59, 59, (int) date('m'), (int) date('d'), (int) date('Y'));

					if ($dateStart <= $todayStart && $dateEnd >= $todayEnd)
					{
						// Show add to cart button
						$isEnable = false;
					}
				}
				else
				{
					// Show add to cart button
					$isEnable = false;
				}
			}
			else
			{
				// Show add to cart button
				$isEnable = false;
			}
		}
		else
		{
			// Show add to cart button
			$isEnable = false;
		}

		return $isEnable;
	}

	public static function getProductparentImage($product_parent_id)
	{
		$result = self::getProductById($product_parent_id);

		if ($result->product_full_image == '' && $result->product_parent_id > 0)
		{
			$result = self::getProductparentImage($result->product_parent_id);
		}

		return $result;
	}

	public static function GetProdcutUserfield($id = 'NULL', $section_id = 12)
	{
		$cart     = RedshopHelperCartSession::getCart();
		$row_data = RedshopHelperExtrafields::getSectionFieldList($section_id, 1, 0);

		if ($section_id == 12)
		{
			$product_id    = $cart[$id]['product_id'];
			$productdetail = self::getProductById($product_id);
			$temp_name     = "product";
			$temp_id       = $productdetail->product_template;
			$giftcard      = 0;
		}
		else
		{
			$temp_name = "giftcard";
			$temp_id   = 0;
			$giftcard  = 1;
		}

		$productTemplate = RedshopHelperTemplate::getTemplate($temp_name, $temp_id);

		$returnArr    = self::getProductUserfieldFromTemplate($productTemplate[0]->template_desc, $giftcard);
		$userFieldTag = $returnArr[1];

		$resultArr = array();

		for ($i = 0, $in = count($userFieldTag); $i < $in; $i++)
		{
			for ($j = 0, $jn = count($row_data); $j < $jn; $j++)
			{
				if (array_key_exists($userFieldTag[$i], $cart[$id]) && $cart[$id][$userFieldTag[$i]])
				{
					if ($row_data[$j]->name == $userFieldTag[$i])
					{
						$strtitle = '';

						if ($row_data[$j]->title)
						{
							$strtitle = '<span class="product-userfield-title">' . $row_data[$j]->title . ':</span>';
						}

						$resultArr[] = $strtitle . ' <span class="product-userfield-value">' . $cart[$id][$userFieldTag[$i]] . '</span>';
					}
				}
			}
		}

		$resultstr = "";

		if (empty($resultArr))
		{
			return $resultstr;
		}

		return "<div>" . JText::_("COM_REDSHOP_PRODUCT_USERFIELD") . "</div><div>" . implode("<br/>", $resultArr) . "</div>";
	}

	public static function GetProdcutfield_order($orderitemid = 'NULL', $section_id = 1)
	{
		$orderItem = RedshopHelperOrder::getOrderItemDetail(0, 0, $orderitemid);

		$product_id = $orderItem[0]->product_id;

		$row_data = RedshopHelperExtrafields::getSectionFieldList($section_id, 1, 0);

		$resultArr = array();

		for ($j = 0, $jn = count($row_data); $j < $jn; $j++)
		{
			$main_result = RedshopHelperExtrafields::getData($row_data[$j]->id, $section_id, $product_id);

			if (isset($main_result->data_txt) && isset($row_data[$j]->display_in_checkout))
			{
				if ($main_result->data_txt != "" && 1 == $row_data[$j]->display_in_checkout)
				{
					$resultArr[] = '<span class="product-order-title">' . $main_result->title . ':</span><span class="product-order-value">' . $main_result->data_txt . '</span>';
				}
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = implode("<br/>", $resultArr);
		}

		return $resultstr;
	}

	public static function removeOutofstockProduct($products)
	{
		$filter_products = array();

		for ($s = 0, $sn = count($products); $s < $sn; $s++)
		{
			$product = $products[$s];
			$pid     = $product->product_id;

			$attributes_set = array();

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = RedshopHelperProduct_Attribute::getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$attributes   = RedshopHelperProduct_Attribute::getProductAttribute($product->product_id);
			$attributes   = array_merge($attributes, $attributes_set);
			$totalatt     = count($attributes);
			$stock_amount = RedshopHelperStockroom::getFinalStockofProduct($pid, $totalatt);

			if ($stock_amount)
			{
				$filter_products[] = $products[$s];
			}
		}

		return $filter_products;
	}

	public static function GetProdcutfield($id = 'NULL', $section_id = 1)
	{
		$cart = JFactory::getSession()->get('cart');
		$product_id = $cart[$id]['product_id'];
		$row_data   = RedshopHelperExtrafields::getSectionFieldList($section_id, 1, 0);

		$resultArr = array();

		for ($j = 0, $jn = count($row_data); $j < $jn; $j++)
		{
			$main_result = RedshopHelperExtrafields::getData($row_data[$j]->id, $section_id, $product_id);

			if (isset($main_result->data_txt) && isset($row_data[$j]->display_in_checkout))
			{
				if ($main_result->data_txt != "" && 1 == $row_data[$j]->display_in_checkout)
				{
					$resultArr[] = '<span class="product-field-title">' . $main_result->title . ': </span><span class="product-field-value">' . $main_result->data_txt . '</span>';
				}
			}
		}

		$resultstr = "";

		if (empty($resultArr))
		{
			return $resultstr;
		}

		return implode("<br/>", $resultArr);
	}

	/**
	 * Get Max and Min of Product Price
	 *
	 * @param   int $productId Product Id
	 *
	 * @return  array
	 */
	public static function getProductMinMaxPrice($productId)
	{
		$attributes           = RedshopHelperProduct_Attribute::getProductAttribute($productId);
		$propertyIds          = array();
		$subPropertyIds       = array();
		$propertyPriceList    = array();
		$subPropertyPriceList = array();

		foreach ($attributes as $key => $attribute)
		{
			foreach ($attribute->properties as $property)
			{
				$propertyIds[] = $property->property_id;
				$subProperties = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property->property_id);

				foreach ($subProperties as $subProperty)
				{
					$subPropertyIds[] = $subProperty->value;
				}
			}
		}

		$db = JFactory::getDbo();

		if (!empty($productId))
		{
			$productPriceList = \Redshop\Repositories\Product::getPrices((int) $productId);
		}

		if (!empty($propertyIds))
		{
			$query             = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_attribute_price'))
				->where($db->qn('section') . ' = ' . $db->q('property'))
				->where($db->qn('section_id') . ' IN (' . implode(',', $propertyIds) . ')');
			$propertyPriceList = $db->setQuery($query)->loadColumn();
		}

		if (!empty($subPropertyIds))
		{
			$query                = $db->getQuery(true)
				->select($db->qn('product_price'))
				->from($db->qn('#__redshop_product_attribute_price'))
				->where($db->qn('section') . ' = ' . $db->q('subproperty'))
				->where($db->qn('section_id') . ' IN (' . implode(',', $subPropertyIds) . ')');
			$subPropertyPriceList = $db->setQuery($query)->loadColumn();
		}

		$productPriceList    = array_unique(array_merge($productPriceList, $propertyPriceList, $subPropertyPriceList));
		$productPrice['min'] = min($productPriceList);
		$productPrice['max'] = max($productPriceList);

		return $productPrice;
	}

	/**
	 * Get Product Review List
	 *
	 * @param   int $productId Product id
	 *
	 * @return mixed
	 */
	public static function getProductReviewList($productId)
	{
		// Initialize variables.
		$db = JFactory::getDbo();

		// Create the base select statement.
		$query = $db->getQuery(true)
			->select('pr.*')
			->select($db->qn('ui.firstname'))
			->select($db->qn('ui.lastname'))
			->from($db->qn('#__redshop_product_rating', 'pr'))
			->leftjoin(
				$db->qn('#__redshop_users_info', 'ui')
				. ' ON '
				. $db->qn('ui.user_id') . '=' . $db->qn('pr.userid')
				. ' AND ' . $db->qn('ui.address_type') . '=' . $db->q('BT')
			)
			->where($db->qn('pr.product_id') . ' = ' . (int) $productId)
			->where($db->qn('pr.published') . ' = 1')
			->where($db->qn('pr.email') . ' != ' . $db->q(''))
			->order($db->qn('pr.favoured') . ' DESC')
			->group($db->qn('pr.rating_id'));

		try
		{
			$reviews = $db->setQuery($query)->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $reviews;
	}

	/**
	 * Get section
	 *
	 * @param   string  $section Section name
	 * @param   integer $id      Section id
	 *
	 * @return  mixed|null
	 * @deprecated 2.1.0
	 */
	public static function getSection($section = '', $id = 0)
	{
		// To avoid killing queries do not allow queries that get all the items
		if ($id != 0 && $section != '')
		{
			switch ($section)
			{
				case 'product':
					return self::getProductById($id);
				case 'category':
					return RedshopEntityCategory::getInstance($id)->getItem();
				default:
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__redshop_' . $section))
						->where($db->qn($section . '_id') . ' = ' . (int) $id);

					return $db->setQuery($query)->loadObject();
			}
		}

		return null;
	}

	public static function getCategoryNameByProductId($pid)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('c.name'))
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $pid))
			->where($db->qn('c.name') . ' IS NOT NULL')
			->order($db->qn('c.id') . ' ASC')
			->setLimit(0, 1);

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Return checked if product is in session of compare product cart else blank
	 *
	 * @param   integer $productId Id of product
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.7
	 */
	public static function checkCompareProduct($productId)
	{
		$productId = (int) $productId;

		if (!$productId)
		{
			return '';
		}

		$compareProducts = JFactory::getSession()->get('compare_product');

		if (!$compareProducts)
		{
			return '';
		}

		$idx = (int) ($compareProducts['idx']);

		foreach ($compareProducts[$idx] as $compareProduct)
		{
			if ($compareProduct["product_id"] == $productId)
			{
				return 'checked';
			}
		}

		return '';
	}


}
