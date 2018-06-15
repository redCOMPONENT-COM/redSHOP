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
	public function process($originalProduct)
	{
		$this->originalProduct = $originalProduct;
		$copiedProduct         = clone $originalProduct;

		$copiedProduct->product_id = null;
		$copiedProduct->published  = 0;

		// Update information
		$copiedProduct->product_name   = General::renameToUniqueValue('product_name', $this->originalProduct->product_name);
		$copiedProduct->product_number = General::renameToUniqueValue('product_number', $this->originalProduct->product_number, 'dash');
		$copiedProduct->publish_date   = \JFactory::getDate()->toSql();
		$copiedProduct->update_date    = '0000-00-00 00:00:00';

		// Reset data
		$copiedProduct->visited          = 0;
		$copiedProduct->checked_out      = 0;
		$copiedProduct->checked_out_time = '0000-00-00 00:00:00';

		if (!empty($copiedProduct->sef_url))
		{
			$copiedProduct->sef_url = General::renameToUniqueValue('sef_url', $copiedProduct->sef_url, 'dash');
		}

		if (!empty($copiedProduct->canonical_url))
		{
			$copiedProduct->canonical_url = General::renameToUniqueValue('canonical_url', $copiedProduct->canonical_url, 'dash');
		}

		$copiedProduct->product_thumb_image        = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_thumb_image
		);
		$copiedProduct->product_full_image         = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_full_image
		);
		$copiedProduct->product_back_full_image    = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_back_full_image
		);
		$copiedProduct->product_back_thumb_image   = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_back_thumb_image
		);
		$copiedProduct->product_preview_image      = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_preview_image
		);
		$copiedProduct->product_preview_back_image = $this->copyImageFile(
			REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $this->originalProduct->product_preview_back_image
		);

		$table = $this->getTable();
		$table->bind($copiedProduct);

		if (!$table->check())
		{
			return false;
		}

		if (!$table->store())
		{
			return false;
		}

		$this->copiedProduct = \RedshopEntityProduct::getInstance($table->get('product_id'));
		$this->copiedProduct->loadItem();

		if ($this->copiedProduct->hasId())
		{
			\JFactory::getApplication()->enqueueMessage(\JText::sprintf('COM_REDSHOP_PRODUCT_COPIED_SUCCESS', $this->originalProduct->product_name));
		}

		$this->copyPrices();
		$this->copyProductMedias();
		$this->copyProductAttributes();
		$this->copyCategories();
		$this->copyRelated();
		$this->copyExtraFields();
		$this->copyStockroom();
		$this->copyAccesories();

		return $this->copiedProduct;
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

		$this->copyRecords($db->setQuery($query)->loadObjectList(), array('product_id' => (int) $this->copiedProduct->getId()), '#__redshop_product_price', 'price_id');

		// Discount calculate
		$query->clear()
			->select('*')
			->from($db->quoteName('#__redshop_product_discount_calc'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$this->copyRecords($db->setQuery($query)->loadObjectList(), array('product_id' => (int) $this->copiedProduct->getId()), '#__redshop_product_discount_calc', 'id');

		// Discount calculate extra
		$query->clear()
			->select('*')
			->from($db->quoteName('#__redshop_product_discount_calc_extra'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$this->copyRecords($db->setQuery($query)->loadObjectList(), array('product_id' => (int) $this->copiedProduct->getId()), '#__redshop_product_discount_calc_extra', 'pdcextra_id');

		\JFactory::getApplication()->enqueueMessage(\JText::sprintf('The price of product %s was copied successfully', $this->originalProduct->product_name));
	}

	/**
	 * @return  void
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	protected function copyProductMedias()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_media'))
			->where($db->quoteName('section_id') . ' = ' . (int) $this->originalProduct->product_id)
			->where($db->quoteName('media_section') . '=' . $db->quote('product'));

		$originalProductMedias = $db->setQuery($query)->loadObjectList();

		if (!$originalProductMedias)
		{

			\JFactory::getApplication()->enqueueMessage('There are no media files to copy for this product', 'notice');

			return;
		}

		foreach ($originalProductMedias as $originalProductMedia)
		{
			/**
			 * @var \stdClass $originalProductMedia
			 */
			$newProductMedia             = clone $originalProductMedia;
			$newProductMedia->media_id   = null;
			$newProductMedia->section_id = (int) $this->copiedProduct->getId();

			if ($newProductMedia->media_type == 'images')
			{
				$newProductMedia->media_name = $this->copyImageFile(REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . $originalProductMedia->media_name);

				// mime re-detect
				$mediaFile = REDSHOP_FRONT_IMAGES_RELPATH_PRODUCT . (string) $newProductMedia->media_name;

				if (\JFile::exists($mediaFile))
				{
					$originalProductMedia->media_mimetype = trim(mime_content_type($mediaFile));
				}
			}

			\JFactory::getDbo()->insertObject('#__redshop_media', $newProductMedia);
		}

		\JFactory::getApplication()->enqueueMessage(\JText::sprintf('Product media of product %s was copied successfully', $this->originalProduct->product_name));
	}

	/**
	 * @return  void
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	protected function copyProductAttributes()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_product_attribute'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$originalProductAttributes = $db->setQuery($query)->loadObjectList();

		if (!($originalProductAttributes))
		{
			\JFactory::getApplication()->enqueueMessage(\JText::_('COM_REDSHOP_PRODUCT_NO_PRODUCT_ATTRIBUTES_FOR_COPY'), 'notice');

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

				if (!$originalProductAttributeProperties)
				{
					\JFactory::getApplication()->enqueueMessage('There are no product\'s properties to copy for this product', 'notice');

					continue;
				}

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
					$this->copyAttributePropertyMedias($this->getMedias($originalProductAttributeProperty->property_id, 'property'), $newProductAttributeProperty);

					// Sub attribute level #3
					$query->clear();
					$query->select('*')
						->from($db->quoteName('#__redshop_product_subattribute_color'))
						->where($db->quoteName('subattribute_id') . ' = ' . (int) $originalProductAttributeProperty->property_id);

					$originalProductSubAttributeColors = $db->setQuery($query)->loadObjectList();

					if (!$originalProductSubAttributeColors)
					{
						\JFactory::getApplication()->enqueueMessage('There are no product\'s sub attributes to copy for this product', 'notice');

						continue;
					}

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
						$this->copySubAttributeColorMedia($this->getMedias($originalProductSubAttributeColor->subattribute_color_id, 'subproperty'), $newProductSubAttributeColor);
					}
				}
			}
		}

		\JFactory::getApplication()->enqueueMessage(\JText::sprintf('COM_REDSHOP_PRODUCT_ATTRIBUTES_COPIED_SUCCESS', $this->originalProduct->product_name));
	}

	private function copyAttributePropertyMedias($originalProductAttributePropertyMedias, $newProductAttributeProperty)
	{
		if (!$originalProductAttributePropertyMedias)
		{
			return;
		}

		foreach ($originalProductAttributePropertyMedias as $originalProductAttributePropertyMedia)
		{
			$newProductAttributePropertyMedia             = clone $originalProductAttributePropertyMedia;
			$newProductAttributePropertyMedia->media_id   = null;
			$newProductAttributePropertyMedia->section_id = (int) $newProductAttributeProperty->property_id;
			$newProductAttributePropertyMedia->media_name = $this->copyImageFile(REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . $originalProductAttributePropertyMedia->media_name);

			\JFactory::getDbo()->insertObject('#__redshop_media', $newProductAttributePropertyMedia);
		}
	}

	/**
	 * @param $originalProductSubAttributeColorMedias
	 * @param $newProductSubAttributeColor
	 *
	 *
	 * @since  2.1.0
	 */
	private function copySubAttributeColorMedia($originalProductSubAttributeColorMedias, $newProductSubAttributeColor)
	{
		if (!$originalProductSubAttributeColorMedias)
		{
			return;
		}

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

	/**
	 * @return  void
	 * @throws  \Exception
	 * @since   2.1.0
	 */
	protected function copyCategories()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_product_category_xref'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$this->copyRecords($db->setQuery($query)->loadObjectList(), array('product_id' => (int) $this->copiedProduct->getId()), '#__redshop_product_category_xref');

		\JFactory::getApplication()->enqueueMessage(\JText::sprintf('COM_REDSHOP_PRODUCT_CATEGORIES_COPIED_SUCCESS', $this->originalProduct->product_name));
	}

	/**
	 *
	 * @since   2.1.0
	 */
	protected function copyRelated()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_product_related'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$this->copyRecords($db->setQuery($query)->loadObjectList(), array('product_id' => (int) $this->copiedProduct->getId()), '#__redshop_product_related');

		\JFactory::getApplication()->enqueueMessage(\JText::sprintf('COM_REDSHOP_PRODUCT_RELATED_COPIED_SUCCESS', $this->originalProduct->product_name));
	}

	/**
	 *
	 * @since   2.1.0
	 */
	protected function copyExtraFields()
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_fields_data'))
			->where($db->quoteName('itemid') . ' = ' . (int) $this->originalProduct->product_id)
			->where(
				'(' . $db->quoteName('section') . ' = ' . $db->quote('1')
				. ' OR ' . $db->quoteName('section') . ' = ' . $db->quote('12')
				. ' OR ' . $db->quoteName('section') . ' = ' . $db->quote('17') . ')'
			);

		$this->copyRecords($db->setQuery($query)->loadObjectList(), array('itemid' => (int) $this->copiedProduct->getId()), '#__redshop_fields_data', 'data_id');

		\JFactory::getApplication()->enqueueMessage(\JText::sprintf('COM_REDSHOP_PRODUCT_EXTRA_FIELDS_COPIED_SUCCESS', $this->originalProduct->product_name));
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
		$query->select('*')
			->from($db->quoteName('#__redshop_product_stockroom_xref'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$this->copyRecords($db->setQuery($query)->loadObjectList(), array('product_id' => (int) $this->copiedProduct->getId()), '#__redshop_product_stockroom_xref');

		\JFactory::getApplication()->enqueueMessage(\JText::sprintf('COM_REDSHOP_PRODUCT_STOCKROOM_COPIED_SUCCESS', $this->originalProduct->product_name));
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

		// Delete any accesories with copied product before clone it
		$query->clear();
		$query->delete($db->quoteName('#__redshop_product_accessory'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->copiedProduct->getId());
		$db->setQuery($query)->execute();

		$query->clear()
			->select('*')
			->from($db->quoteName('#__redshop_product_accessory'))
			->where($db->quoteName('product_id') . ' = ' . (int) $this->originalProduct->product_id);

		$this->copyRecords($db->setQuery($query)->loadObjectList(), array('product_id' => (int) $this->copiedProduct->getId()), '#__redshop_product_accessory', 'accessory_id');

		\JFactory::getApplication()->enqueueMessage(\JText::sprintf('COM_REDSHOP_PRODUCT_ACCESSORIES_COPIED_SUCCESS', $this->originalProduct->product_name));
	}

	/**
	 * @param   string $from Copy image from
	 *
	 * @return  boolean|null|string
	 *
	 * @since   2.1.0
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
	 *
	 * @since   2.1.0
	 */
	private function copyRecords($originalRecords, $replaceData, $table, $primaryKey = null)
	{
		if (!$originalRecords)
		{
			return;
		}

		foreach ($originalRecords as $originalRecord)
		{
			foreach ($replaceData as $key => $value)
			{
				if (!property_exists($originalRecord, $key))
				{
					continue;
				}

				$originalRecord->{$key} = $value;

				if ($primaryKey)
				{
					$originalRecord->{$primaryKey} = null;
				}
			}

			\JFactory::getDbo()->insertObject($table, $originalRecord);
		}
	}

	/**
	 *
	 * @since   2.1.0
	 */
	private function getMedias($sectionId, $mediaSection = 'product')
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->quoteName('#__redshop_media'))
			->where($db->quoteName('section_id') . ' = ' . (int) $sectionId)
			->where($db->quoteName('media_section') . '=' . (string) $db->quote($mediaSection));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * @param   string $table  Table class
	 * @param   string $prefix Prefix
	 *
	 * @return  boolean|\JTable|\TableProduct_Detail
	 *
	 * @since   2.1.0
	 */
	private function getTable($table = 'Product_Detail', $prefix = 'Table')
	{
		return \JTable::getInstance($table, $prefix);
	}
}
