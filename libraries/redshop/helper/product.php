<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
	 * Get product information
	 *
	 * @param   int  $productId  Product id
	 * @param   int  $userId     User id
	 *
	 * @return mixed
	 */
	public static function getProductById($productId, $userId = 0)
	{
		if (!$userId)
		{
			$user = JFactory::getUser();
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

			// Otheriwise load product info
			else
			{
				$db = JFactory::getDbo();
				$query = self::getMainProductQuery(false, $userId);

				// Select product
				$query->where($db->qn('p.product_id') . ' = ' . (int) $productId);

				$db->setQuery($query);
				static::$products[$key] = $db->loadObject();
			}

			if (static::$products[$key])
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
		$query->group('p.product_id');

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
		if (!$userId)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		if (empty($products))
		{
			return;
		}

		$getAttributeKeys  = array();
		$getExtraFieldKeys = array();

		foreach ((array) $products  as $product)
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

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (!empty($getAttributeKeys))
		{
			$query->clear()
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

			if ($attributes = $db->setQuery($query)->loadObjectList())
			{
				foreach ($attributes as $attribute)
				{
					$key = $attribute->product_id . '.' . $userId;
					static::$products[$key]->attributes[$attribute->attribute_id] = $attribute;
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
				$db->setQuery($query);

				if ($properties = $db->loadObjectList())
				{
					foreach ($properties as $property)
					{
						$key = $property->product_id . '.' . $userId;
						static::$products[$key]->attributes[$property->attribute_id]->properties[$property->property_id] = $property;
					}
				}
			}
		}

		if (!empty($getExtraFieldKeys))
		{
			$query->clear()
				->select('fd.*')
				->select($db->qn('f.title'))
				->from($db->qn('#__redshop_fields_data', 'fd') . ' FORCE INDEX (idx_itemid)')
				->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('fd.fieldid') . ' = ' . $db->qn('f.id'))
				->where($db->qn('fd.itemid') . ' IN (' . implode(',', $getExtraFieldKeys) . ')')
				->where($db->qn('fd.section') . ' = 1');

			if ($extraFields = $db->setQuery($query)->loadObjectList())
			{
				foreach ($extraFields as $extraField)
				{
					$key = $extraField->itemid . '.' . $userId;
					static::$products[$key]->extraFields[$extraField->fieldid] = $extraField;
				}
			}
		}
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
		$redConfig = Redconfiguration::getInstance();
		$productHelper = productHelper::getInstance();
		$totalAccessory = count($accessory);
		$accessoryList = "";

		if (!$totalAccessory)
		{
			return '';
		}

		$accessoryList .= "<tr><th>" . JText::_('COM_REDSHOP_ACCESSORY_PRODUCT') . "</th></tr>";

		for ($a = 0, $an = count($accessory); $a < $an; $a++)
		{
			$acId = $accessory[$a]->child_product_id;
			$cpData = Redshop::product((int) $acId);

			$accessoryName = $redConfig->maxchar(
				$accessory[$a]->product_name,
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
			);

			// Get accessory final price with VAT rules
			$accessoryPriceList = $productHelper->getAccessoryPrice(
				$productId, $accessory[$a]->newaccessory_price, $accessory[$a]->accessory_main_price
			);

			$accessoryPrice = $accessoryPriceList[0];

			$accessoryPriceWithoutvat = $productHelper->getAccessoryPrice(
				$productId, $accessory[$a]->newaccessory_price,
				$accessory[$a]->accessory_main_price, 1
			);
			$accessoryPriceWithoutVat = $accessoryPriceWithoutvat[0];
			$accessoryPriceVat = $accessoryPrice - $accessoryPriceWithoutVat;

			$commonid = $productId . '_' . $accessory[$a]->accessory_id . $uniqueId;

			// Accessory attribute  Start
			$attributesSet = array();

			if ($cpData->attribute_set_id > 0)
			{
				$attributesSet = $productHelper->getProductAttribute(0, $cpData->attribute_set_id);
			}

			$attributes = $productHelper->getProductAttribute($acId);
			$attributes = array_merge($attributes, $attributesSet);

			$accessoryCheckbox = "<input onClick='calculateOfflineTotalPrice(\"" . $uniqueId . "\");' type='checkbox' name='accessory_id_"
				. $productId . $uniqueId . "[]' totalattributs='" . count($attributes) . "' accessoryprice='"
				. $accessoryPrice . "' accessorypricevat='" . $accessoryPriceVat . "' id='accessory_id_"
				. $commonid . "' value='" . $accessory[$a]->accessory_id . "' />";

			$accessoryList .= "<tr><td>" . $accessoryCheckbox . "&nbsp;" . $accessoryName . ' : '
				. $productHelper->getProductFormattedPrice($accessoryPrice) . "</td></tr>";

			$accessoryList .= self::replaceAttributeData($productId, $accessory[$a]->accessory_id, $attributes, $userId, $uniqueId);
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
	 * @return mixed
	 *
	 * @since   2.0.3
	 */
	public static function replaceAttributeData($productId = 0, $accessoryId = 0, $attributes = array(), $userId = 0, $uniqueId = "")
	{
		$productHelper = productHelper::getInstance();
		$attributeList = "";

		if ($accessoryId != 0)
		{
			$prefix = $uniqueId . "acc_";
		}
		else
		{
			$prefix = $uniqueId . "prd_";
		}

		JText::script('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED');

		for ($a = 0, $an = count($attributes); $a < $an; $a++)
		{
			$property = $productHelper->getAttibuteProperty(0, $attributes[$a]->attribute_id);

			if (empty($attributes[$a]->text) || empty($property))
			{
				continue;
			}

			$commonId = $prefix . $productId . '_' . $accessoryId . '_' . $attributes[$a]->attribute_id;
			$hiddenAttId = 'attribute_id_' . $prefix . $productId . '_' . $accessoryId;
			$propertyId = 'property_id_' . $commonId;

			for ($i = 0, $in = count($property); $i < $in; $i++)
			{
				$attributesPropertyVat = 0;

				if ($property[$i]->property_price > 0)
				{
					$propertyOprand = $property[$i]->oprand;

					$propertyPrice = $productHelper->getProductFormattedPrice($property[$i]->property_price);

					// Get product vat to include.
					$attributesPropertyVat = self::getProductTax($productId, $property[$i]->property_price, $userId);
					$property[$i]->property_price += $attributesPropertyVat;

					$propertyPriceWithVat = $productHelper->getProductFormattedPrice($property[$i]->property_price);

					$property[$i]->text = urldecode($property[$i]->property_name)
						. " (" . $propertyOprand
						. " " . $propertyPrice
						. "excl. vat / "
						. $propertyPriceWithVat . ")";
				}
				else
				{
					$property[$i]->text = urldecode($property[$i]->property_name);
				}

				$attributeList .= '<input type="hidden" id="'
					. $propertyId . '_oprand' . $property[$i]->value . '" value="'
					. $property [$i]->oprand . '" />';
				$attributeList .= '<input type="hidden" id="'
					. $propertyId . '_protax' . $property[$i]->value . '" value="'
					. $attributesPropertyVat . '" />';
				$attributeList .= '<input type="hidden" id="'
					. $propertyId . '_proprice' . $property[$i]->value . '" value="'
					. $property [$i]->property_price . '" />';
			}

			$tmpArray = array();
			$tmpArray[0] = new stdClass;
			$tmpArray[0]->value = 0;
			$tmpArray[0]->text = JText::_('COM_REDSHOP_SELECT') . " " . urldecode($attributes[$a]->text);

			$newProperty = array_merge($tmpArray, $property);
			$chklist = "";

			if ($attributes[$a]->allow_multiple_selection)
			{
				for ($chk = 0; $chk < count($property); $chk++)
				{
					if ($attributes[$a]->attribute_required == 1)
					{
						$required = "required='" . $attributes[$a]->attribute_required . "'";
					}
					else
					{
						$required = "";
					}

					$chklist .= "<br /><input type='checkbox' value='" . $property[$chk]->value . "' name='"
						. $propertyId . "[]' id='" . $propertyId . "' class='inputbox' attribute_name='"
						. $attributes[$a]->attribute_name . "' required='" . $attributes[$a]->attribute_required
						. "' onchange='javascript:changeOfflinePropertyDropdown(\"" . $productId . "\",\"" . $accessoryId
						. "\",\"" . $attributes[$a]->attribute_id . "\",\"" . $uniqueId . "\");'  />&nbsp;" . $property[$chk]->text;
				}
			}
			else
			{
				$chklist = JHTML::_(
					'select.genericlist', $newProperty, $propertyId . '[]', 'id="' . $propertyId
					. '"  class="inputbox" size="1" attribute_name="' . $attributes[$a]->attribute_name . '" required="'
					. $attributes[$a]->attribute_required . '" onchange="javascript:changeOfflinePropertyDropdown(\''
					. $productId . '\',\'' . $accessoryId . '\',\'' . $attributes[$a]->attribute_id . '\',\'' . $uniqueId
					. '\');" ', 'value', 'text', ''
				);
			}

			$lists ['property_id'] = $chklist;

			$attributeList .= "<input type='hidden' name='" . $hiddenAttId . "[]' value='" . $attributes[$a]->value . "' />";

			if ($attributes[$a]->attribute_required > 0)
			{
				$pos = Redshop::getConfig()->get('ASTERISK_POSITION') > 0 ? urldecode($attributes[$a]->text)
					. "<span id='asterisk_right'> * " : "<span id='asterisk_left'>* </span>"
					. urldecode($attributes[$a]->text);
				$attrTitle = $pos;
			}
			else
			{
				$attrTitle = urldecode($attributes[$a]->text);
			}

			$attributeList .= "<tr><td>" . $attrTitle . " : " . $lists['property_id'] . "</td></tr>";
			$attributeList .= "<tr><td><div id='property_responce" . $commonId . "' style='display:none;'></td></tr>";
		}

		return $attributeList;
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
			$wrapper [$i]->wrapper_name = $wrapper [$i]->wrapper_name . " ("
				. $productHelper->getProductFormattedPrice($wrapper[$i]->wrapper_price) . ")";
			$wrapperList .= "<input type='hidden' id='wprice_" . $commonId . "_"
				. $wrapper [$i]->wrapper_id . "' value='" . $wrapper[$i]->wrapper_price . "' />";
			$wrapperList .= "<input type='hidden' id='wprice_tax_" . $commonId . "_"
				. $wrapper [$i]->wrapper_id . "' value='" . $wrapperVat . "' />";
		}

		$wrapper = array_merge($wArray, $wrapper);
		$lists ['wrapper_id'] = JHtml::_(
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
	 * @param   int     $productId        Product id
	 * @param   int     $quantity         Product quantity
	 * @param   string  $uniqueId         Unique id
	 * @param   int     $userId           User id
	 * @param   int     $newProductPrice  New product price
	 *
	 * @return  mixed
	 *
	 * @since   2.0.3
	 */
	public static function getProductItemInfo($productId = 0, $quantity = 1, $uniqueId = "", $userId = 0, $newProductPrice = 0)
	{
		$productHelper = productHelper::getInstance();

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
				$productTax = $productHelper->getProductTax($productId, $newProductPrice, $userId);
			}

			else
			{
				$productArr = $productHelper->getProductNetPrice($productId, $userId, $quantity);
				$productPriceExclVat = $productArr['productPrice'];
				$productTax = $productArr['productVat'];

				// Attribute start
				$attributesSet = array();

				if ($productInfo->attribute_set_id > 0)
				{
					$attributesSet = $productHelper->getProductAttribute(0, $productInfo->attribute_set_id, 0, 1);
				}

				$attributes = $productHelper->getProductAttribute($productId);
				$attributes = array_merge($attributes, $attributesSet);
				$attributeList = self::replaceAttributeData($productId, 0, $attributes, $userId, $uniqueId);

				// Accessory start
				$accessory = $productHelper->getProductAccessory(0, $productId);
				$accessoryList = self::replaceAccessoryData($productId, $accessory, $userId, $uniqueId);

				// Wrapper selection box generate
				$wrapperList = self::replaceWrapperData($productId, $userId, $uniqueId);
				$productUserField = self::replaceUserField($productId, $productInfo->product_template, $uniqueId);
			}
		}

		$productPrice = $productPriceExclVat + $productTax;
		$total_price = $productPrice * $quantity;
		$totalTax = $productTax * $quantity;

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
		$productHelper = productHelper::getInstance();

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
					$displayrate = ($rate[$i]->rate > 0) ? " (" . $productHelper->getProductFormattedPrice($rate[$i]->rate) . " )" : "";
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
	 * @return array
	 *
	 * @since   2.0.3
	 */
	public static function redesignProductItem($post = array())
	{
		$orderItem = array();
		$i = -1;

		foreach ($post as $key => $value)
		{
			if (!strcmp("product", substr($key, 0, 7)) && strlen($key) < 10)
			{
				$i++;

				if (!isset($orderItem[$i]))
				{
					$orderItem[$i] = new stdClass;
				}

				$orderItem[$i]->product_id = $value;
			}

			if (!strcmp("attribute_dataproduct", substr($key, 0, 21)))
			{
				$orderItem[$i]->attribute_data = $value;
			}

			if (!strcmp("property_dataproduct", substr($key, 0, 20)))
			{
				$orderItem[$i]->property_data = $value;
			}

			if (!strcmp("subproperty_dataproduct", substr($key, 0, 23)))
			{
				$orderItem[$i]->subproperty_data = $value;
			}

			if (!strcmp("accessory_dataproduct", substr($key, 0, 21)))
			{
				$orderItem[$i]->accessory_data = $value;
			}

			if (!strcmp("acc_attribute_dataproduct", substr($key, 0, 25)))
			{
				$orderItem[$i]->acc_attribute_data = $value;
			}

			if (!strcmp("acc_property_dataproduct", substr($key, 0, 24)))
			{
				$orderItem[$i]->acc_property_data = $value;
			}

			if (!strcmp("acc_subproperty_dataproduct", substr($key, 0, 27)))
			{
				$orderItem[$i]->acc_subproperty_data = $value;
			}

			if (!strcmp("extrafieldId", substr($key, 0, 12)))
			{
				$orderItem[$i]->extrafieldId = $value;
			}

			if (!strcmp("extrafieldname", substr($key, 0, 14)))
			{
				$orderItem[$i]->extrafieldname = $value;
			}

			if (!strcmp("wrapper_dataproduct", substr($key, 0, 19)))
			{
				$orderItem[$i]->wrapper_data = $value;
			}

			if (!strcmp("quantityproduct", substr($key, 0, 15)))
			{
				$orderItem[$i]->quantity = $value;
			}

			if (!strcmp("prdexclpriceproduct", substr($key, 0, 19)))
			{
				$orderItem[$i]->prdexclprice = $value;
			}

			if (!strcmp("taxpriceproduct", substr($key, 0, 15)))
			{
				$orderItem[$i]->taxprice = $value;
			}

			if (!strcmp("productpriceproduct", substr($key, 0, 19)))
			{
				$orderItem[$i]->productprice = $value;
			}

			if (!strcmp("requiedAttributeproduct", substr($key, 0, 23)))
			{
				$orderItem[$i]->requiedAttributeproduct = $value;
			}
		}

		return $orderItem;
	}

	/**
	 * Replace User Field
	 *
	 * @param   int     $productId   Product id
	 * @param   int     $templateId  Template id
	 * @param   string  $uniqueId    Unique id
	 *
	 * @return mixed
	 *
	 * @since   2.0.3
	 */
	public static function replaceUserField($productId = 0, $templateId = 0, $uniqueId = "")
	{
		$productHelper = productHelper::getInstance();
		$templateDesc = RedshopHelperTemplate::getTemplate("product", $templateId);
		$returnArr = $productHelper->getProductUserfieldFromTemplate($templateDesc[0]->template_desc);

		$commonId = $productId . $uniqueId;

		if (empty($returnArr[1]))
		{
			return '';
		}

		$productUserFields = "<table>";

		for ($ui = 0; $ui < count($returnArr[1]); $ui++)
		{
			$resultArr = RedshopHelperExtrafields::listAllUserFields($returnArr[1][$ui], 12, "", $commonId);
			$hiddenArr = RedshopHelperExtrafields::listAllUserFields($returnArr[1][$ui], 12, "hidden", $commonId);

			if ($resultArr[0] != "")
			{
				$productUserFields .= "<tr><td>" . $resultArr[0] . "</td><td>" . $resultArr[1] . $hiddenArr[1] . "</td></tr>";
			}
		}

		$productUserFields .= "</table>";

		return $productUserFields;
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
		$db = JFactory::getDbo();
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
		$userHelper = rsUserHelper::getInstance();

		if (empty($userArr))
		{
			$userArr = $userHelper->createUserSession($userId);
		}

		$shopperGroupId = RedshopHelperUser::getShopperGroup($userId);

		if ($userId)
		{
			$query = $db->getQuery(true)
				->select('p.*')
				->from($db->qn('#__redshop_users_info', 'u'))
				->leftjoin($db->qn('#__redshop_product_price', 'p') . ' ON ' . $db->qn('u.shopper_group_id') . ' = ' . $db->qn('p.shopper_group_id'))
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

		return	RedshopLayoutHelper::render(
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
	 * @param   integer  $productPrice  Product price
	 * @param   integer  $userId        User ID
	 * @param   integer  $taxExempt     Tax exempt
	 *
	 * @return  integer
	 *
	 * @since   2.0.6
	 */
	public static function getProductTax($productId = 0, $productPrice = 0, $userId = 0, $taxExempt = 0)
	{
		$redshopUser = JFactory::getSession()->get('rs_user');

		if ($userId == 0)
		{
			$user   = JFactory::getUser();
			$userId = $user->id;
		}

		$productInfor = array();

		if ($productId != 0)
		{
			$productInfor = self::getProductById($productId);
		}

		$productTax   = 0;
		$taxRate = 0;

		if (empty($redshopUser))
		{
			$redshopUser                     = array();
			$redshopUser['rs_is_user_login'] = 0;
		}

		if ($redshopUser['rs_is_user_login'] == 0 && $userId != 0)
		{
			RedshopHelperUser::createUserSession($userId);
		}

		$vatRateData = productHelper::getInstance()->getVatRates($productId, $userId);

		if ($vatRateData)
		{
			$taxRate = $vatRateData->tax_rate;
		}

		if ($productPrice <= 0 && isset($productInfor->product_price))
		{
			$productPrice = $productInfor->product_price;
		}

		$productPrice = productHelper::getInstance()->productPriceRound($productPrice);

		if ($taxExempt)
		{
			$productTax = $productPrice * $taxRate;

			return $productTax;
		}

		if ($taxRate)
		{
			if ($userId)
			{
				$userInformation = RedshopHelperUser::getUserInformation($userId);

				if (count($userInformation) > 0)
				{
					if ($userInformation->requesting_tax_exempt == 1 && $userInformation->tax_exempt_approved)
					{
						$productTax = $productTax;
					}
					else
					{
						$productTax = $productPrice * $taxRate;
					}
				}
				else
				{
					$productTax = $productPrice * $taxRate;
				}
			}
			else
			{
				$productTax = $productPrice * $taxRate;
			}
		}

		return productHelper::getInstance()->productPriceRound($productTax);
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
				->where('((p.price_quantity_start <= ' . (int) $quantity . ' AND p.price_quantity_end >= '
					. (int) $quantity . ') OR (p.price_quantity_start = 0 AND p.price_quantity_end = 0))')
				->order('p.price_quantity_start ASC');

			$result = $db->setQuery($query)->loadObject();
		}
		else
		{
			if ($productData = RedshopHelperProduct::getProductById($productId, $userId))
			{
				if (isset($productData->price_id))
				{
					$result = new stdClass;
					$result->price_id = $productData->price_id;
					$result->product_price = $productData->price_product_price;
					$result->discount_price = $productData->price_discount_price;
					$result->product_currency = $productData->price_product_currency;
					$result->discount_start_date = $productData->price_discount_start_date;
					$result->discount_end_date = $productData->price_discount_end_date;
				}
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
}
