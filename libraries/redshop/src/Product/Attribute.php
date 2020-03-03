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
use Joomla\Utilities\ArrayHelper;
/**
 * Class Product Helper
 *
 * @since 3.0
 */
class Attribute
{
	/**
	 * @var   array
	 *
	 * @since  3.0
	 */
	protected static $attributeProperties = array();

	/**
	 * @var   array
	 *
	 * @since  3.0
	 */
	protected static $productAttributes = array();

	/**
	 * @var   array
	 *
	 * @since  3.0
	 */
	protected static $subProperties = array();

	/**
	 * @var   array
	 *
	 * @since  2.0.4
	 */
	protected static $propertyPrice = array();

	/**
	 * Get Product Attribute
	 *
	 * @param   integer  $productId          Product id
	 * @param   integer  $attributeSetId     Attribute set id
	 * @param   integer  $attributeId        Attribute id
	 * @param   integer  $published          Published attribute set
	 * @param   integer  $attributeRequired  Attribute required
	 * @param   string   $notAttributeId     Not attribute id
	 *
	 * @return  array
	 * @throws  Exception
	 * @since 3.0
	 */
	public static function getProductAttribute($productId = 0, $attributeSetId = 0, $attributeId = 0, $published = 0, $attributeRequired = 0, $notAttributeId = '')
	{
		$key = md5(
			$productId . '_' . $attributeSetId . '_' . $attributeId . '_' . $published . '_' . $attributeRequired
			. '_' . $notAttributeId
		);

		if (!array_key_exists($key, static::$productAttributes))
		{
			if ($productId)
			{
				$selectedAttributes = array();
				$productData        = \Redshop\Product\Product::getProductById($productId);

				if ($productData && isset($productData->attributes))
				{
					foreach ($productData->attributes as $attribute)
					{
						if (($attributeSetId && $attributeSetId !== $attribute->attribute_set_id)
							|| ($attributeId && $attributeId !== $attribute->attribute_id)
							|| ($published && $published !== $attribute->attribute_published)
							|| ($published && $attributeSetId && $published !== $attribute->attribute_set_published)
							|| ($attributeRequired && $attributeRequired !== (int)$attribute->attribute_required))
						{
							continue;
						}

						if ($notAttributeId)
						{
							$notAttributeIds = explode(',', $notAttributeId);
							$notAttributeIds = ArrayHelper::toInteger($notAttributeIds);

							if (in_array($attribute->attribute_id, $notAttributeIds, false))
							{
								continue;
							}
						}

						$selectedAttributes[] = $attribute;
					}
				}

				static::$productAttributes[$key] = $selectedAttributes;
			}
			else{
				$db    = \JFactory::getDbo();
				$query = $db->getQuery(true)
					->select(
						array('a.attribute_id AS value', 'a.attribute_name AS text', 'a.*', 'ast.attribute_set_name')
					)
					->from($db->qn('#__redshop_product_attribute', 'a'))
					->leftJoin(
						$db->qn('#__redshop_attribute_set', 'ast') . ' ON ast.attribute_set_id = a.attribute_set_id'
					)
					->where('a.attribute_name != ' . $db->q(''))
					->where('a.attribute_published = 1')
					->order('a.ordering ASC')
					->order('a.attribute_name ASC');

				if ($attributeSetId !== 0) {
					$query->where('a.attribute_set_id = ' . (int)$attributeSetId);
				}

				if ($attributeId !== 0) {
					$query->where('a.attribute_id = ' . (int)$attributeId);
				}

				if ($published !== 0) {
					$query->where('ast.published = ' . (int)$published);
				}

				if ($attributeRequired !== 0) {
					$query->where('a.attribute_required = ' . (int)$attributeRequired);
				}

				if (!empty($notAttributeId)) {
					// Sanitize ids
					$notAttributeIds = explode(',', $notAttributeId);
					$notAttributeIds = ArrayHelper::toInteger($notAttributeIds);

					$query->where('a.attribute_id NOT IN (' . implode(',', $notAttributeIds) . ')');
				}

				static::$productAttributes[$key] = $db->setQuery($query)->loadObjectList();
			}

			\JPluginHelper::importPlugin('redshop_product');
			\RedshopHelperUtility::getDispatcher()->trigger(
				'onGetProductAttribute',
				array(&static::$productAttributes[$key])
			);
		}

		return static::$productAttributes[$key];
	}
}