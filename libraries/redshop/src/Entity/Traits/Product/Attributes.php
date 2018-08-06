<?php
/**
 * @package     Redshop\Entity\Traits\Product
 * @subpackage  Product
 *
 * @copyright   Copyright (C) 2012 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

namespace Redshop\Entity\Traits\Product;

use Redshop\Repositories\Product;

/**
 * Trait Attributes
 * @package Redshop\Entity\Traits\Product
 *
 * @since   2.1.0
 */
trait Attributes
{
	/**
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public function getAttributes()
	{
		if (!$this->hasId())
		{
			return array();
		}

		$attributes    = Product::getAttributes($this->getId());
		$attributeData = array();

		if (empty($attributes))
		{
			return $attributeData;
		}

		foreach ($attributes as $attribute)
		{
			$properties = Product::getProperties($attribute->attribute_id);

			$attribute_id             = $attribute->attribute_id;
			$attribute_name           = $attribute->attribute_name;
			$attribute_description    = $attribute->attribute_description;
			$attribute_required       = $attribute->attribute_required;
			$allow_multiple_selection = $attribute->allow_multiple_selection;
			$hide_attribute_price     = $attribute->hide_attribute_price;
			$ordering                 = $attribute->ordering;
			$attribute_published      = $attribute->attribute_published;
			$display_type             = $attribute->display_type;

			foreach ($properties as $index => $property)
			{
				$property[$index]->subvalue = Product::getSubAttributes($property->property_id);
			}

			$attributeData[] = array('attribute_id'             => $attribute_id, 'attribute_name' => $attribute_name,
			                         'attribute_description'    => $attribute_description,
			                         'attribute_required'       => $attribute_required, 'ordering' => $ordering, 'property' => $properties,
			                         'allow_multiple_selection' => $allow_multiple_selection, 'hide_attribute_price' => $hide_attribute_price,
			                         'attribute_published'      => $attribute_published, 'display_type' => $display_type,
			                         'attribute_set_id'         => $attribute->attribute_set_id);
		}

		return $attributeData;
	}
}