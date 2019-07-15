<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

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
		$productHelper = productHelper::getInstance();
		$wrapperList   = '';

		$wrapper = $productHelper->getWrapper($productId, 0, 1);

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
					$rateArr[$r]->text = JText::_($rs->name) . " - " . $rate[$i]->text . $displayrate;
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
			$rateArr, 'shipping_rate_id',
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
		$productHelper = productHelper::getInstance();
		$templateDesc  = RedshopHelperTemplate::getTemplate("product", $templateId);
		$returnArr     = $productHelper->getProductUserfieldFromTemplate($templateDesc[0]->template_desc);
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
}
