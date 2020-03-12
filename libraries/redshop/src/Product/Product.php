<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product;


defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Class Product Helper
 *
 * @since 3.0
 */
class Product
{
	/**
	 * Product info
	 *
	 * @var  array
	 * @since 3.0
	 */
	protected static $products = array();

	/**
	 * All product data
	 *
	 * @var  array
	 * @since 3.0
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
	 * @param string $templateData
	 * @param int    $giftCard
	 *
	 * @return array
	 * @since 3.0
	 */
	public static function getProductUserFieldFromTemplate($templateData = "", $giftCard = 0)
	{
		$userFields     = array();
		$userFieldsLbl  = array();
		$result         = array();
		$templateMiddle = "";

		if ($giftCard) {
			$templateStart = explode("{if giftcard_userfield}", $templateData);

			if (isset($templateStart[1])) {
				if (!empty($templateStart)) {
					$templateEnd = explode("{giftcard_userfield end if}", $templateStart[1]);

					if (!empty($templateEnd)) {
						$templateMiddle = $templateEnd[0];
					}
				}
			}
		} else {
			$templateStart = explode("{if product_userfield}", $templateData);

			if (count($templateStart) > 1) {
				$templateEnd = explode("{product_userfield end if}", $templateStart[1]);

				if (!empty($templateEnd)) {
					$templateMiddle = $templateEnd[0];
				}
			}
		}

		if ($templateMiddle != "") {
			$tmp = explode('}', $templateMiddle);

			for ($i = 0, $in = count($tmp); $i < $in; $i++) {
				$val   = strpbrk($tmp[$i], "{");
				$value = str_replace("{", "", $val);

				if ($value != "") {
					if (strpos($templateMiddle, '{' . $value . '_lbl}') !== false) {
						$userFieldsLbl[] = $value . '_lbl';
						$userFields[]    = $value;
					} else {
						$userFieldsLbl[] = '';
						$userFields[]    = $value;
					}
				}
			}
		}

		$tmp = array();

		for ($i = 0, $in = count($userFields); $i < $in; $i++) {
			if (!in_array($userFields[$i], $userFieldsLbl)) {
				$tmp[] = $userFields[$i];
			}
		}

		$userFields = $tmp;
		$result[0]  = $templateMiddle;
		$result[1]  = $userFields;

		return $result;
	}

	/**
	 * @param         $productId
	 * @param int     $userId
	 * @param bool    $setRelated
	 *
	 * @return mixed
	 * @since 3.0
	 */
	public static function getProductById($productId, $userId = 0, $setRelated = true)
	{
		if (!$userId) {
			$user   = \JFactory::getUser();
			$userId = $user->id;
		}

		$key = $productId . '.' . $userId;

		if (!array_key_exists($key, static::$products)) {
			// Check if data is already loaded while getting list
			if (array_key_exists($productId, static::$allProducts)) {
				static::$products[$key] = static::$allProducts[$productId];
			} // Otherwise load product info
			else {
				$db    = \JFactory::getDbo();
				$query = self::getMainProductQuery(false, $userId);

				// Select product
				$query->where($db->qn('p.product_id') . ' = ' . (int)$productId);

				$db->setQuery($query);
				static::$products[$key] = $db->loadObject();
			}

			if ($setRelated === true && static::$products[$key]) {
				self::setProductRelates(array($key => static::$products[$key]), $userId);
			}
		}

		return static::$products[$key];
	}

	/**
	 * @param bool $query
	 * @param int  $userId
	 *
	 * @return bool
	 * @since 3.0
	 */
	public static function getMainProductQuery($query = false, $userId = 0)
	{
		$shopperGroupId = \RedshopHelperUser::getShopperGroup($userId);
		$db             = Factory::getDbo();

		if (!$query) {
			$query = $db->getQuery(true);
		}

		$query->select(array('p.*', 'p.product_id'))
			->from($db->qn('#__redshop_product', 'p'));

		// Require condition
		$query->group($db->qn('p.product_id'));

		// Select price
		$query->select(
			array(
				'pp.price_id',
				$db->qn('pp.product_price', 'price_product_price'),
				$db->qn('pp.product_currency', 'price_product_currency'),
				$db->qn('pp.discount_price', 'price_discount_price'),
				$db->qn('pp.discount_start_date', 'price_discount_start_date'),
				$db->qn('pp.discount_end_date', 'price_discount_end_date')
			)
		)
			->leftJoin(
				$db->qn('#__redshop_product_price', 'pp')
				. ' ON p.product_id = pp.product_id AND ((pp.price_quantity_start <= 1 AND pp.price_quantity_end >= 1)'
				. ' OR (pp.price_quantity_start = 0 AND pp.price_quantity_end = 0)) AND pp.shopper_group_id = ' . (int)$shopperGroupId
			)
			->order('pp.price_quantity_start ASC');

		// Select category
		$query->select(array('pc.category_id'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON pc.product_id = p.product_id');

		// Getting cat_in_sefurl as main category id if it available
		$query->leftJoin(
			$db->qn(
				'#__redshop_product_category_xref',
				'pc3'
			) . ' ON pc3.product_id = p.product_id AND pc3.category_id = p.cat_in_sefurl'
		)
			->leftJoin($db->qn('#__redshop_category', 'c3') . ' ON pc3.category_id = c3.id AND c3.published = 1');

		$subQuery = $db->getQuery(true)
			->select('GROUP_CONCAT(DISTINCT c2.id ORDER BY c2.id ASC SEPARATOR ' . $db->q(',') . ')')
			->from($db->qn('#__redshop_category', 'c2'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc2') . ' ON c2.id = pc2.category_id')
			->where('p.product_id = pc2.product_id')
			->where(
				'((p.cat_in_sefurl != ' . $db->q(
					''
				) . ' AND p.cat_in_sefurl != pc2.category_id) OR p.cat_in_sefurl = ' . $db->q('') . ')'
			)
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
			->leftJoin(
				$db->qn('#__redshop_product', 'parent_product') . ' ON parent_product.product_id = pa.child_product_id'
			)
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
			->leftJoin(
				'(' . $subQuery . ') AS child_product_table ON child_product_table.product_parent_id = p.product_id'
			);

		// Sum quantity
		if (\Redshop::getConfig()->get('USE_STOCKROOM') == 1) {
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
	 * @param array $products Products
	 * @param int   $userId   User id
	 *
	 * @return  void
	 * @since   3.0
	 */
	public static function setProductRelates($products, $userId = 0)
	{
		if (empty($products) || !is_array($products)) {
			return;
		}

		$userId = !$userId ? Factory::getUser()->id : $userId;

		$getAttributeKeys  = array();
		$getExtraFieldKeys = array();

		foreach ($products as $product) {
			if (!isset($product->product_id)) {
				continue;
			}

			$key = $product->product_id . '.' . $userId;

			if (!array_key_exists($key, static::$products)) {
				continue;
			}

			static::$products[$product->product_id . '.' . $userId]->categories = explode(',', $product->categories);

			// If this product not has attributes yet. Put this in array of product which need to get attributes.
			if (!isset(static::$products[$key]->attributes)) {
				static::$products[$key]->attributes = array();
				$getAttributeKeys[]                 = $product->product_id;
			}

			// If this product not has extra fields yet. Put this in array of product which need to get extra fields.
			if (!isset(static::$products[$key]->extraFields)) {
				static::$products[$key]->extraFields = array();
				$getExtraFieldKeys[]                 = $product->product_id;
			}
		}

		self::setProductAttributes($getAttributeKeys, $userId);
		self::setProductExtraFields($getExtraFieldKeys, $userId);
	}

	/**
	 * Method for set product attributes
	 *
	 * @param array   $getAttributeKeys Attributes key
	 * @param integer $userId           Current user ID
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function setProductAttributes($getAttributeKeys = array(), $userId = 0)
	{
		if (empty($getAttributeKeys)) {
			return;
		}

		$db = Factory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('a.attribute_id', 'value'))
			->select($db->qn('a.attribute_name', 'text'))
			->select('a.*')
			->select($db->qn('ast.attribute_set_name'))
			->select($db->qn('ast.published', 'attribute_set_published'))
			->from($db->qn('#__redshop_product_attribute', 'a'))
			->leftJoin(
				$db->qn('#__redshop_attribute_set', 'ast') . ' ON ' . $db->qn('ast.attribute_set_id') . ' = ' . $db->qn(
					'a.attribute_set_id'
				)
			)
			->where($db->qn('a.attribute_name') . ' != ' . $db->quote(''))
			->where($db->qn('a.attribute_published') . ' = 1')
			->where($db->qn('a.product_id') . ' IN (' . implode(',', $getAttributeKeys) . ')')
			->order($db->qn('a.ordering') . ' ASC');

		$attributes = $db->setQuery($query)->loadObjectList();

		if (empty($attributes)) {
			return;
		}

		foreach ($attributes as $attribute) {
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
				$db->qn('#__redshop_product_attribute', 'a') . ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn(
					'ap.attribute_id'
				)
			)
			->where($db->qn('a.product_id') . ' IN (' . implode(',', $getAttributeKeys) . ')')
			->where($db->qn('ap.property_published') . ' = 1')
			->where($db->qn('a.attribute_published') . ' = 1')
			->where($db->qn('a.attribute_name') . ' != ' . $db->quote(''))
			->order($db->qn('ap.ordering') . ' ASC');

		$properties = $db->setQuery($query)->loadObjectList();

		if (empty($properties)) {
			return;
		}

		foreach ($properties as $property) {
			$key = $property->product_id . '.' . $userId;

			static::$products[$key]->attributes[$property->attribute_id]->properties[$property->property_id] = $property;
		}
	}

	/**
	 * Method for set product extra fields
	 *
	 * @param array   $getExtraFieldKeys Attributes key
	 * @param integer $userId            Current user ID
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function setProductExtraFields($getExtraFieldKeys = array(), $userId = 0)
	{
		if (empty($getExtraFieldKeys)) {
			return;
		}

		$db = Factory::getDbo();

		$query = $db->getQuery(true)
			->select('fd.*')
			->select($db->qn('f.title'))
			->from($db->qn('#__redshop_fields_data', 'fd') . ' FORCE INDEX(' . $db->qn('#__field_data_common') . ')')
			->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('fd.fieldid') . ' = ' . $db->qn('f.id'))
			->where($db->qn('fd.itemid') . ' IN (' . implode(',', $getExtraFieldKeys) . ')')
			->where($db->qn('fd.section') . ' = 1');

		$extraFields = $db->setQuery($query)->loadObjectList();

		if (empty($extraFields)) {
			return;
		}

		foreach ($extraFields as $extraField) {
			$key = $extraField->itemid . '.' . $userId;

			static::$products[$key]->extraFields[$extraField->fieldid] = $extraField;
		}
	}

	/**
	 * Get product information base on list of Ids
	 *
	 * @param array   $productIds Product ids
	 * @param int     $userId     User id
	 * @param boolean $setRelated Is need to set related or not
	 *
	 * @return  array
	 * @throws  Exception
	 *
	 * @since   3.0
	 */
	public static function getProductsByIds($productIds = array(), $userId = 0, $setRelated = true)
	{
		if (!$userId) {
			$user   = Factory::getUser();
			$userId = $user->id;
		}

		$productIds = \Joomla\Utilities\ArrayHelper::toInteger($productIds);

		if (empty($productIds)) {
			return array();
		}

		$results       = array();
		$newProductIds = array();

		foreach ($productIds as $productId) {
			$key = $productId . '.' . $userId;

			// Load from static cache if already exist.
			if (array_key_exists($key, static::$products)) {
				$results[] = static::$products[$key];

				if ($setRelated) {
					self::setProductRelates(array($key => static::$products[$key]), $userId);
				}

				continue;
			}

			// Check if data is already loaded while getting list
			if (array_key_exists($productId, static::$allProducts)) {
				static::$products[$key] = static::$allProducts[$productId];

				if ($setRelated) {
					self::setProductRelates(array($key => static::$products[$key]), $userId);
				}

				continue;
			}

			$newProductIds[] = $productId;
		}

		if (empty($newProductIds)) {
			return $results;
		}

		// Otherwise load product info
		$db    = JFactory::getDbo();
		$query = self::getMainProductQuery(false, $userId);

		// Select product
		$query->where($db->qn('p.product_id') . ' IN (' . implode(',', $productIds) . ')');

		$items = (array)$db->setQuery($query)->loadObjectList();

		if (empty($items)) {
			return $results;
		}

		foreach ($items as $item) {
			$key                    = $item->product_id . '.' . $userId;
			static::$products[$key] = $item;
			$results[]              = $item;

			if ($setRelated === true) {
				self::setProductRelates(array($key => static::$products[$key]), $userId);
			}
		}

		return $results;
	}

	/**
	 * Set product array
	 *
	 * @param array $products Array product/s values
	 *
	 * @return void
	 * @since  3.0
	 */
	public static function setProduct($products)
	{
		static::$products = $products + static::$products;
		self::setProductRelates($products);
	}

	/**
	 * Get all product information
	 * Warning: This method is loading all the products from DB. Which can resulting
	 *            into memory issue. Use with caution.
	 *            It is aimed to use in CLI version or for webservices.
	 *
	 * @return  array  Product Information array
	 */
	public static function getList()
	{
		if (empty(static::$allProducts)) {
			$db    = Factory::getDbo();
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
	 * get next or previous product using ordering.
	 *
	 * @param int $productId   current product id
	 * @param int $category_id current product category id
	 * @param int $dirn        to indicate next or previous product
	 *
	 * @return mixed
	 */
	public static function getPrevNextproduct($productId, $category_id, $dirn)
	{
		$db       = \JFactory::getDbo();
		$subQuery = $db->getQuery(true)
			->select('ordering')
			->from($db->qn('#__redshop_product_category_xref'))
			->where($db->qn('product_id') . ' = ' . $db->q($productId))
			->where($db->qn('category_id') . ' = ' . $db->q($category_id))
			->setLimit(0, 1);

		$query = $db->getQuery(true)
			->select('pcx.product_id, p.product_name , ordering')
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftJoin($db->qn('#__redshop_product', 'p') . 'ON p.product_id = pcx.product_id');

		if ($dirn < 0) {
			$query->where($db->qn('ordering') . ' < (' . $subQuery . ')')
				->where($db->qn('p.published') . ' = 1')
				->where($db->qn('category_id') . ' = ' . (int)$category_id)
				->order($db->qn('ordering') . 'DESC');
		} elseif ($dirn > 0) {
			$query->where($db->qn('ordering') . ' > (' . $subQuery . ')')
				->where($db->qn('p.published') . ' = 1')
				->where($db->qn('category_id') . ' = ' . (int)$category_id)
				->order($db->qn('ordering'));
		} else {
			$query->where($db->qn('ordering') . ' = (' . $subQuery . ')')
				->where($db->qn('p.published') . ' = 1')
				->where($db->qn('category_id') . ' = ' . (int)$category_id)
				->order($db->qn('ordering'));
		}

		return $db->setQuery($query, 0, 1)->loadObject();
	}

	/**
	 * Method get all child product
	 *
	 * @param   integer   $childid
	 * @param   integer   $parentid
	 *
	 * @return mixed
	 */
	public static function getAllChildProductArrayList($childid = 0, $parentid = 0)
	{
		$info = \RedshopHelperProduct::getChildProduct($parentid);

		for ($i = 0, $in = count($info); $i < $in; $i++)
		{
			if ($childid != $info[$i]->product_id)
			{
				$GLOBALS['childproductlist'][] = $info[$i];
				self::getAllChildProductArrayList($childid, $info[$i]->product_id);
			}
		}

		return $GLOBALS['childproductlist'];
	}
}