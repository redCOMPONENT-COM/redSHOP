<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Utilities\ArrayHelper;

/**
 * Class Redshop Helper Product Attribute
 *
 * @since  2.0.3
 */
abstract class RedshopHelperProduct_Attribute
{
	/**
	 * @var   array
	 *
	 * @since  2.0.3
	 */
	protected static $attributeProperties = array();

	/**
	 * @var   array
	 *
	 * @since  2.0.3
	 */
	protected static $productAttributes = array();

	/**
	 * @var   array
	 *
	 * @since  2.0.3
	 */
	protected static $subProperties = array();

	/**
	 * Get Attribute Properties of specific product.
	 *
	 * @param   int  $propertyId      Property id
	 * @param   int  $attributeId     Attribute id
	 * @param   int  $productId       Product id
	 * @param   int  $attributeSetId  Attribute set id
	 * @param   int  $required        Required
	 * @param   int  $notPropertyId   Not property id
	 *
	 * @return  mixed
	 */
	public static function getAttributeProperties($propertyId = 0, $attributeId = 0, $productId = 0, $attributeSetId = 0, $required = 0,
		$notPropertyId = 0)
	{
		$key = md5($propertyId . '_' . $attributeId . '_' . $productId . '_' . $attributeSetId . '_' . $required . '_' . $notPropertyId);

		if (!array_key_exists($key, static::$attributeProperties))
		{
			if ($productId)
			{
				$selectedProperties = array();
				$productId = explode(',', $productId);
				$productId = ArrayHelper::toInteger($productId);

				if ($attributeId)
				{
					$attributeId = explode(',', $attributeId);
					$attributeId = ArrayHelper::toInteger($attributeId);
				}

				foreach ($productId as $item)
				{
					if ($productData = RedshopHelperProduct::getProductById($item))
					{
						foreach ($productData->attributes as $attribute)
						{
							if ($attributeId && !in_array($attribute->attribute_id, $attributeId))
							{
								continue;
							}

							foreach ($attribute->properties as $property)
							{
								if ($propertyId)
								{
									$propertyIds = explode(',', $propertyId);
									$propertyIds = ArrayHelper::toInteger($propertyIds);

									if (!in_array($property->property_id, $propertyIds))
									{
										continue;
									}
								}

								if ($attributeSetId)
								{
									$attributeSetIds = explode(',', $attributeSetId);
									$attributeSetIds = ArrayHelper::toInteger($attributeSetIds);

									if (!in_array($property->attribute_set_id, $attributeSetIds))
									{
										continue;
									}
								}

								if ($required && $required != $property->setrequire_selected)
								{
									continue;
								}

								if ($notPropertyId)
								{
									$notPropertyIds = explode(',', $notPropertyId);
									$notPropertyIds = ArrayHelper::toInteger($notPropertyIds);

									if (in_array($property->property_id, $notPropertyIds))
									{
										continue;
									}
								}

								$selectedProperties[] = $property;
							}
						}
					}
				}

				static::$attributeProperties[$key] = $selectedProperties;
			}
			else
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select(array('ap.property_id AS value', 'ap.property_name AS text', 'ap.*', 'a.attribute_name'))
					->from($db->qn('#__redshop_product_attribute_property', 'ap'))
					->leftJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON a.attribute_id = ap.attribute_id')
					->where('ap.property_published = 1')
					->order('ap.ordering ASC')
					->order('ap.property_name ASC');

				if ($attributeId != 0)
				{
					// Sanitize ids
					$attributeIds = explode(',', $attributeId);
					$attributeIds = ArrayHelper::toInteger($attributeIds);

					$query->where('ap.attribute_id IN (' . implode(',', $attributeIds) . ')');
				}

				if ($attributeSetId != 0)
				{
					// Sanitize ids
					$attributeSetIds = explode(',', $attributeSetId);
					$attributeSetIds = ArrayHelper::toInteger($attributeSetIds);

					$query->where('a.attribute_set_id IN (' . implode(',', $attributeSetIds) . ')');
				}

				if ($propertyId != 0)
				{
					// Sanitize ids
					$propertyIds = explode(',', $propertyId);
					$propertyIds = ArrayHelper::toInteger($propertyIds);

					$query->where('ap.property_id IN (' . implode(',', $propertyIds) . ')');
				}

				if ($required != 0)
				{
					$query->where('ap.setrequire_selected = ' . (int) $required);
				}

				if ($notPropertyId != 0)
				{
					// Sanitize ids
					$notPropertyIds = explode(',', $notPropertyId);
					$notPropertyIds = ArrayHelper::toInteger($notPropertyIds);

					$query->where('ap.property_id NOT IN (' . implode(',', $notPropertyIds) . ')');
				}

				static::$attributeProperties[$key] = $db->setQuery($query)->loadObjectlist();
			}
		}

		return static::$attributeProperties[$key];
	}

	/**
	 * Get Product Attribute
	 *
	 * @param   int  $productId          Product id
	 * @param   int  $attributeSetId     Attribute set id
	 * @param   int  $attributeId        Attribute id
	 * @param   int  $published          Published attribute set
	 * @param   int  $attributeRequired  Attribute required
	 * @param   int  $notAttributeId     Not attribute id
	 *
	 * @return mixed
	 */
	public static function getProductAttribute($productId = 0, $attributeSetId = 0, $attributeId = 0, $published = 0, $attributeRequired = 0,
		$notAttributeId = 0)
	{
		$key = md5($productId . '_' . $attributeSetId . '_' . $attributeId . '_' . $published . '_' . $attributeRequired . '_' . $notAttributeId);

		if (!array_key_exists($key, static::$productAttributes))
		{
			if ($productId)
			{
				$selectedAttributes = array();
				$productData = RedshopHelperProduct::getProductById($productId);

				if ($productData && isset($productData->attributes))
				{
					foreach ($productData->attributes as $attribute)
					{
						if (($attributeSetId && ($attributeSetId != $attribute->attribute_set_id))
							|| ($attributeId && ($attributeId != $attribute->attribute_id))
							|| ($published && ($published != $attribute->attribute_published))
							|| ($published && $attributeSetId && ($published != $attribute->attribute_set_published))
							|| ($attributeRequired && ($attributeRequired != $attribute->attribute_required)))
						{
							continue;
						}

						if ($notAttributeId)
						{
							$notAttributeIds = explode(',', $notAttributeId);
							$notAttributeIds = ArrayHelper::toInteger($notAttributeIds);

							if (in_array($attribute->attribute_id, $notAttributeIds))
							{
								continue;
							}
						}

						$selectedAttributes[] = $attribute;
					}
				}

				static::$productAttributes[$key] = $selectedAttributes;
			}
			else
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select(array('a.attribute_id AS value', 'a.attribute_name AS text', 'a.*', 'ast.attribute_set_name'))
					->from($db->qn('#__redshop_product_attribute', 'a'))
					->leftJoin($db->qn('#__redshop_attribute_set', 'ast') . ' ON ast.attribute_set_id = a.attribute_set_id')
					->where('a.attribute_name != ' . $db->q(''))
					->where('a.attribute_published = 1')
					->order('a.ordering ASC')
					->order('a.attribute_name ASC');

				if ($attributeSetId != 0)
				{
					$query->where('a.attribute_set_id = ' . (int) $attributeSetId);
				}

				if ($attributeId != 0)
				{
					$query->where('a.attribute_id = ' . (int) $attributeId);
				}

				if ($published != 0)
				{
					$query->where('ast.published = ' . (int) $published);
				}

				if ($attributeRequired != 0)
				{
					$query->where('a.attribute_required = ' . (int) $attributeRequired);
				}

				if ($notAttributeId != 0)
				{
					// Sanitize ids
					$notAttributeIds = explode(',', $notAttributeId);
					$notAttributeIds = ArrayHelper::toInteger($notAttributeIds);

					$query->where('a.attribute_id NOT IN (' . implode(',', $notAttributeIds) . ')');
				}

				static::$productAttributes[$key] = $db->setQuery($query)->loadObjectlist();
			}
		}

		return static::$productAttributes[$key];
	}

	/**
	 * Method for get sub properties
	 *
	 * @param   int  $subPropertyId  Sub-Property ID
	 * @param   int  $propertyId     Property ID
	 *
	 * @return  mixed                List of sub-properties data.
	 *
	 * @since  2.0.3
	 */
	public static function getAttributeSubProperties($subPropertyId = 0, $propertyId = 0)
	{
		$subPropertyId = (int) $subPropertyId;
		$propertyId    = (int) $propertyId;

		$key = md5($subPropertyId . '_' . $propertyId);

		if (!array_key_exists($key, static::$subProperties))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->select($db->qn('sp.subattribute_color_id', 'value'))
				->select($db->qn('sp.subattribute_color_name', 'text'))
				->select('sp.*')
				->select($db->qn('p.property_name'))
				->select($db->qn('p.setrequire_selected'))
				->select($db->qn('p.setmulti_selected'))
				->select($db->qn('p.setdisplay_type'))
				->from($db->qn('#__redshop_product_subattribute_color', 'sp'))
				->leftJoin(
					$db->qn('#__redshop_product_attribute_property', 'p') . ' ON ' . $db->qn('p.property_id') . ' = ' . $db->qn('sp.subattribute_id')
				)
				->where($db->qn('sp.subattribute_published') . ' = 1')
				->order($db->qn('sp.ordering') . ' ASC')
				->order($db->qn('sp.subattribute_color_name') . ' ASC');

			if ($subPropertyId)
			{
				$query->where($db->qn('sp.subattribute_color_id') . ' = ' . $subPropertyId);
			}

			if ($propertyId)
			{
				$query->where($db->qn('sp.subattribute_id') . ' = ' . $propertyId);
			}

			static::$subProperties[$key] = $db->setQuery($query)->loadObjectList();
		}

		return static::$subProperties[$key];
	}
}
