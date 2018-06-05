<?php
/**
 * @package     Redshop\Product\Utilities
 * @subpackage  Copy
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product\Utilities;

/**
 * Class Copy
 * @package Redshop\Product\Utilities
 *
 * @since   2.1.0
 */
class Copy
{
	/**
	 * @var   \stdClass
	 * @since 2.1.0
	 */
	private $originalProduct;

	/**
	 * @var   \RedshopEntityProduct
	 * @since 2.1.0
	 */
	private $copiedProduct;

	/**
	 * @param   \stdClass $originalProduct Original product class
	 *
	 * @return  $this|bool|\RedshopEntityProduct
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	public function copy($originalProduct)
	{
		$this->originalProduct = $originalProduct;
		$this->copiedProduct   = clone $originalProduct;

		$this->copiedProduct->product_id = null;
		$this->copiedProduct->published  = 0;

		// Update information
		$this->copiedProduct->product_name   = General::renameToUniqueValue('product_name', $this->originalProduct->product_name);
		$this->copiedProduct->product_number = General::renameToUniqueValue('product_number', $this->originalProduct->product_number, 'dash');
		$this->copiedProduct->publish_date   = \JFactory::getDate()->toSql();
		$this->copiedProduct->update_date    = '0000-00-00 00:00:00';

		// Reset data
		$this->copiedProduct->visited          = 0;
		$this->copiedProduct->checked_out      = 0;
		$this->copiedProduct->checked_out_time = '0000-00-00 00:00:00';

		if (!empty($this->copiedProduct->sef_url))
		{
			$this->copiedProduct->sef_url = General::renameToUniqueValue('sef_url', $this->copiedProduct->sef_url, 'dash');
		}

		if (!empty($this->copiedProduct->canonical_url))
		{
			$this->copiedProduct->canonical_url = General::renameToUniqueValue('canonical_url', $this->copiedProduct->canonical_url, 'dash');
		}

		$this->copiedProduct->product_thumb_image        = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_thumb_image
		);
		$this->copiedProduct->product_full_image         = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_full_image
		);
		$this->copiedProduct->product_back_full_image    = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_back_full_image
		);
		$this->copiedProduct->product_back_thumb_image   = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_back_thumb_image
		);
		$this->copiedProduct->product_preview_image      = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_preview_image
		);
		$this->copiedProduct->product_preview_back_image = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_preview_back_image
		);

		$table = $this->getTable();
		$table->bind($this->copiedProduct);

		if ($table->check() === false)
		{
			return false;
		}

		if (!$table->store())
		{
			return false;
		}

		$this->copiedProduct = \RedshopEntityProduct::getInstance($table->get('product_id'));
		$this->copiedProduct->loadItem();

		$this->copyProductMedias();
		$this->copyPrices();
		$this->copyCategories();
		$this->copyStockroom();
		$this->copyAccesories();
		$this->copyProductAttributes();

		return $this->copiedProduct;
	}

	/**
	 * @return  void
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	protected function copyProductMedias()
	{
		$originalProductMedias = $this->getMedias($this->originalProduct->product_id);

		if (!$originalProductMedias)
		{
			return;
		}

		foreach ($originalProductMedias as $originalProductMedia)
		{
			$newProductMedia             = clone $originalProductMedia;
			$newProductMedia->media_id   = null;
			$newProductMedia->section_id = (int) $this->copiedProduct->getId();

			if ($newProductMedia->media_type == 'images')
			{
				$newProductMedia->media_name = $this->copyImageFile(REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $originalProductMedia->media_name);

				// mime re-detect
				$mediaFile = REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $newProductMedia->media_name;

				if (\JFile::exists((string) $mediaFile))
				{
					$originalProductMedia->media_mimetype = trim(mime_content_type($mediaFile));
				}
			}

			\JFactory::getDbo()->insertObject('#__redshop_media', $newProductMedia);
		}
	}

	/**
	 * @return  void
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	protected function copyPrices()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_product_price'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);
		$db->setQuery($query);

		$originalProductPrices = $db->setQuery($query)->loadObjectList();

		if (!$originalProductPrices)
		{
			return;
		}

		foreach ($originalProductPrices as $originalProductPrice)
		{
			$originalProductPrice->price_id   = null;
			$originalProductPrice->product_id = (int) $this->copiedProduct->getId();

			\JFactory::getDbo()->insertObject('#__redshop_product_price', $newProductMedia);
		}
	}

	/**
	 * @return  void
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	protected function copyCategories()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array(
				$db->quoteName('category_id'),
				$db->quoteName('ordering'),
			)
		)
			->from($db->quoteName('#__redshop_product_category_xref'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$originalProductCategories = $db->setQuery($query)->loadObjectList();

		if (!$originalProductCategories)
		{
			return;
		}

		foreach ($originalProductCategories as $originalProductCategory)
		{
			$originalProductCategory->product_id = (int) $this->copiedProduct->getId();

			\JFactory::getDbo()->insertObject('#__redshop_product_category_xref', $originalProductCategory);
		}
	}

	/**
	 * @return  void
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	protected function copyStockroom()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array(
				$db->quoteName('stockroom_id'),
				$db->quoteName('quantity'),
				$db->quoteName('preorder_stock'),
				$db->quoteName('ordered_preorder')
			)
		)
			->from($db->quoteName('#__redshop_product_stockroom_xref'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$originalProductStookrooms = $db->setQuery($query)->loadObjectList();

		if (!$originalProductStookrooms)
		{
			return;
		}

		foreach ($originalProductStookrooms as $originalProductStookroom)
		{
			$originalProductStookroom->product_id = (int) $this->copiedProduct->getId();

			\JFactory::getDbo()->insertObject('#__redshop_product_stockroom_xref', $originalProductStookroom);
		}
	}

	/**
	 * @return  void
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	protected function copyAccesories()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array(
				$db->quoteName('child_product_id'),
				$db->quoteName('accessory_price'),
				$db->quoteName('oprand'),
				$db->quoteName('setdefault_selected'),
				$db->quoteName('ordering'),
				$db->quoteName('category_id')
			)
		)
			->from($db->quoteName('#__redshop_product_accessory'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$originalProductAccesories = $db->setQuery($query)->loadObjectList();

		if (!$originalProductAccesories)
		{
			return;
		}

		foreach ($originalProductAccesories as $originalProductAccesory)
		{
			$originalProductAccesory->product_id = (int) $this->copiedProduct->getId();

			\JFactory::getDbo()->insertObject('#__redshop_product_accessory', $originalProductAccesory);
		}
	}

	/**
	 * @return  void
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	protected function copyProductAttributes()
	{
		$originalProductAttributes = $this->getProductAttributes($this->originalProduct->product_id);

		if (empty($originalProductAttributes))
		{
			return;
		}

		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);

		// Level #1
		foreach ($originalProductAttributes as $originalProductAttribute)
		{
			$newProductAttribute               = clone $originalProductAttribute;
			$newProductAttribute->attribute_id = null;
			$newProductAttribute->product_id   = (int) $this->copiedProduct->getId();

			// Copy level #1
			if (\JFactory::getDbo()->insertObject('#__redshop_product_attribute', $newProductAttribute, 'attribute_id'))
			{
				# Level 2 process
				$query->clear();
				$query->select('*')
					->from($db->quoteName('#__redshop_product_attribute_property'))
					->where($db->quoteName('attribute_id') . ' = ' . (int) $originalProductAttribute->attribute_id);

				$originalProductAttributeProperties = $db->setQuery($query)->loadObjectList();

				foreach ($originalProductAttributeProperties as $originalProductAttributeProperty)
				{
					$newProductAttributeProperty               = clone $originalProductAttributeProperty;
					$newProductAttributeProperty->property_id  = null;
					$newProductAttributeProperty->attribute_id = $newProductAttribute->attribute_id;

					// Manual upload or select from Joomla! Media
					$newProductAttributeProperty->property_image = $this->copyImageFile(
						REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . $originalProductAttributeProperty->property_image
					);

					$newProductAttributeProperty->property_main_image = $this->copyImageFile(
						REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $originalProductAttributeProperty->property_main_image
					);

					\JFactory::getDbo()->insertObject('#__redshop_product_attribute_property', $newProductAttributeProperty, 'property_id');

					// Copy media
					$originalProductAttributePropertyMedias = $this->getMedias($originalProductAttributeProperty->property_id, 'property');

					foreach ($originalProductAttributePropertyMedias as $originalProductAttributePropertyMedia)
					{
						$newProductAttributePropertyMedia             = clone $originalProductAttributePropertyMedia;
						$newProductAttributePropertyMedia->media_id   = null;
						$newProductAttributePropertyMedia->section_id = (int) $newProductAttributeProperty->property_id;
						$newProductAttributePropertyMedia->media_name = $this->copyImageFile(REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $originalProductAttributePropertyMedia->media_name);

						\JFactory::getDbo()->insertObject('#__redshop_media', $newProductAttributePropertyMedia);
					}

					// Sub attribute level #3
					$query->clear();
					$query->select('*')
						->from($db->quoteName('#__redshop_product_subattribute_color'))
						->where($db->quoteName('subattribute_id') . ' = ' . (int) $originalProductAttributeProperty->property_id);

					$originalProductSubAttributeColors = $db->setQuery($query)->loadObjectList();

					foreach ($originalProductSubAttributeColors as $originalProductSubAttributeColor)
					{
						$newProductSubAttributeColor                                = clone $originalProductSubAttributeColor;
						$newProductSubAttributeColor->subattribute_color_id         = null;
						$newProductSubAttributeColor->subattribute_id               = $newProductAttributeProperty->property_id;
						$newProductSubAttributeColor->subattribute_color_image      = $this->copyImageFile(REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $originalProductSubAttributeColor->subattribute_color_image);
						$newProductSubAttributeColor->subattribute_color_main_image = $this->copyImageFile(REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . $originalProductSubAttributeColor->subattribute_color_main_image);

						// Copy level 3
						\JFactory::getDbo()->insertObject('#__redshop_product_subattribute_color', $newProductSubAttributeColor, 'subattribute_color_id');

						// Copy media
						$originalProductSubAttributeColorMedias = $this->getMedias($originalProductSubAttributeColor->subattribute_color_id, 'subproperty');

						foreach ($originalProductSubAttributeColorMedias as $originalProductSubAttributeColorMedia)
						{
							$newProductSubAttributeColorMedia             = clone $originalProductSubAttributeColorMedia;
							$newProductSubAttributeColorMedia->media_id   = null;
							$newProductSubAttributeColorMedia->section_id = (int) $newProductSubAttributeColor->subattribute_color_id;
							$newProductSubAttributeColorMedia->media_name = $this->copyImageFile(REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . $originalProductSubAttributeColorMedia->media_name);

							// @TODO Another media types

							\JFactory::getDbo()->insertObject('#__redshop_media', $newProductSubAttributeColorMedia);
						}
					}
				}
			}
		}
	}

	/**
	 * @param   integer $sectionId    Section ID
	 * @param   string  $mediaSection Section name
	 *
	 * @return mixed
	 */
	private function getMedias($sectionId, $mediaSection = 'product')
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_media'))
			->where($db->quoteName('section_id') . ' = ' . (int) $sectionId)
			->where($db->quoteName('media_section') . '=' . $db->quote($mediaSection));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * @param   integer $productId Product ID
	 *
	 * @return mixed
	 * @since   2.1.0
	 */
	private function getProductAttributes($productId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_product_attribute'))
			->where($db->quoteName('product_id') . ' = ' . (int) $productId);

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * @param   string $from Copy image from
	 *
	 * @return  boolean|null|string
	 */
	private function copyImageFile($from)
	{
		static $uid;

		if (!isset($uid))
		{
			$uid = time();
		}

		if (\JFile::exists($from))
		{
			$pathInfo    = pathinfo($from);
			$newFilename = $uid . '_' . $pathInfo['basename'];

			if (copy($from, $pathInfo['dirname'] . '/' . $newFilename) === false)
			{
				return false;
			}

			return $newFilename;
		}

		return null;
	}

	/**
	 * @param   string $table  Table class
	 * @param   string $prefix Prefix
	 *
	 * @return  boolean|\JTable
	 *
	 * @since   2.1.0
	 */
	private function getTable($table = 'Product_Detail', $prefix = 'Table')
	{
		return \JTable::getInstance($table, $prefix);
	}
}
