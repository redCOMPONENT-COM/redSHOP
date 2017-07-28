<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\Import;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Attribute
 *
 * @since  1.0
 */
class PlgRedshop_ImportAttribute extends Import\AbstractBase
{
	/**
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $primaryKey = 'attribute_id';

	/**
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $nameKey = 'attribute_name';

	/**
	 * List of columns for encoding UTF8
	 *
	 * @var array
	 *
	 * @since  1.0
	 */
	protected $encodingColumns = array('attribute_name', 'property_name', 'subattribute_color_name');

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onAjaxAttribute_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		// Ajax response
		$this->config();
	}

	/**
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	public function onAjaxAttribute_Import()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return $this->import();
	}

	/**
	 * Method for get table object.
	 *
	 * @return  \JTable
	 *
	 * @since   1.0.0
	 */
	public function getTable()
	{
		return RedshopTable::getInstance('Attribute', 'RedshopTable');
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable  $table  Header array
	 * @param   array    $data   Data array
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function processImport($table, $data)
	{
		$db = $this->db;
		$isNew = false;

		$hasPropertyName    = !empty($data['property_name']) ? true : false;
		$hasSubPropertyName = !empty($data['subattribute_color_name']) ? true : false;

		// @TODO Move all queries into model or libraries instead inside plugin

		// Get product id
		$query = $db->getQuery(true)
			->select($db->quoteName('product_id'))
			->from($db->quoteName('#__redshop_product'))
			->where($db->quoteName('product_number') . ' = ' . $db->quote($data['product_number']));
		$productId = $db->setQuery($query)->loadResult();

		// Get attribute id
		$query->clear()
			->select($db->quoteName('attribute_id'))
			->from($db->quoteName('#__redshop_product_attribute'))
			->where($db->quoteName('product_id') . ' = ' . $db->quote($productId))
			->where($db->quoteName('attribute_name') . ' = ' . $db->quote($data['attribute_name']));
		$attributeId = $db->setQuery($query)->loadResult();

		// Insert attribute if not exist.
		if (!$attributeId)
		{
			$isNew = true;

			$attributeData = array(
				'attribute_name' => $data['attribute_name'],
				'ordering' => $data['attribute_ordering'],
				'allow_multiple_selection' => $data['allow_multiple_selection'],
				'hide_attribute_price' => $data['hide_attribute_price'],
				'attribute_required' => $data['attribute_required'],
				'display_type' => $data['display_type'],
				'product_id' => $productId
			);

			if (!$table->bind($attributeData) || !$table->store())
			{
				return false;
			}

			$attributeId = $table->attribute_id;
		}

		// In case: No property data and no sub-properties data and this attribute already exist => Update attribute.
		if (!$hasPropertyName && !$hasSubPropertyName && !$isNew)
		{
			$table->load($attributeId);

			$attributeData = array(
				'attribute_name' => $data['attribute_name'],
				'ordering' => $data['attribute_ordering'],
				'allow_multiple_selection' => $data['allow_multiple_selection'],
				'hide_attribute_price' => $data['hide_attribute_price'],
				'attribute_required' => $data['attribute_required'],
				'display_type' => $data['display_type'],
			);

			if (!$table->bind($attributeData) || !$table->store())
			{
				return false;
			}

			return true;
		}

		$propertyId  = 0;

		// Property data.
		if ($hasPropertyName)
		{
			// Get Property ID
			$query = $db->getQuery(true)
				->select($db->quoteName('property_id'))
				->from($db->quoteName('#__redshop_product_attribute_property'))
				->where($db->quoteName('attribute_id') . ' = ' . $db->quote($attributeId))
				->where($db->quoteName('property_name') . ' = ' . $db->quote($data['property_name']));
			$propertyId = (int) $db->setQuery($query)->loadResult();

			if (!$hasSubPropertyName)
			{
				$propertyTable = JTable::getInstance('Attribute_Property', 'Table');

				if ($propertyId)
				{
					$propertyTable->load($propertyId);
				}
				else
				{
					$propertyTable->property_id = null;
				}

				$propertyTable->set('attribute_id', $attributeId);
				$propertyTable->set('property_name', $data['property_name']);
				$propertyTable->set('property_price', $data['property_price']);
				$propertyTable->set('ordering', $data['property_ordering']);
				$propertyTable->set('property_number', $data['property_virtual_number']);
				$propertyTable->set('setdefault_selected', $data['setdefault_selected']);
				$propertyTable->set('setrequire_selected', $data['setrequire_selected']);
				$propertyTable->set('setdisplay_type', $data['setdisplay_type']);
				$oprand = in_array($data['oprand'], array('+', '-', '*', '/', '=')) ? $data['oprand'] : '';

				$propertyTable->set('oprand', $oprand);
				$propertyTable->set('property_image', isset($data['property_image']) ? basename($data['property_image']) : '');
				$propertyTable->set('property_main_image', isset($data['property_main_image']) ? basename($data['property_main_image']) : '');

				if (!$propertyTable->store())
				{
					return false;
				}

				$propertyId = $propertyTable->property_id;

				// Property stock
				if (!empty($data['property_stock']) )
				{
					$propertyStocks = explode("#", $data['property_stock']);

					foreach ($propertyStocks as $propertyStock)
					{
						if (empty($propertyStock))
						{
							continue;
						}

						$propertyStock = explode(':', $propertyStock);

						if (count($propertyStock) != 2)
						{
							continue;
						}

						$query->clear()
							->select("*")
							->from($db->quoteName('#__redshop_stockroom'))
							->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($propertyStock[0]));
						$db->setQuery($query);
						$stockDatas = $db->loadObjectList();

						if (empty($stockDatas))
						{
							continue;
						}

						$query->clear()
							->select("*")
							->from($db->quoteName('#__redshop_product_attribute_stockroom_xref'))
							->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($propertyStock[0]))
							->where($db->quoteName('section') . ' = ' . $db->quote('property'))
							->where($db->quoteName('section_id') . ' = ' . $db->quote($propertyId));

						$propertyProducts = $db->setQuery($query)->loadObjectList();

						if (!empty($propertyProducts))
						{
							$query->clear()
								->update($db->quoteName('#__redshop_product_attribute_stockroom_xref'))
								->set($db->quoteName('quantity') . ' = ' . $db->quote($propertyStock[1]))
								->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($propertyStock[0]))
								->where($db->quoteName('section') . ' = ' . $db->quote('property'))
								->where($db->quoteName('section_id') . ' = ' . $db->quote($propertyId));
							$db->setQuery($query)->clear();
						}
						else
						{
							$newData = new stdClass;
							$newData->quantity = $propertyStock[1];
							$newData->stockroom_id = $propertyStock[0];
							$newData->section = 'property';
							$newData->section_id = $propertyId;
							$db->insertObject('#__redshop_product_attribute_stockroom_xref', $newData);
						}
					}
				}

				if (!empty($data['media_name']) && ($data['media_section'] == 'property'))
				{
					$newMediaName = basename($data['media_name']);
					$ext = pathinfo($newMediaName, PATHINFO_EXTENSION);
					$mediaMimeType = 'images/' . $ext;

					$media = array(
						'media_name' => $newMediaName,
						'media_alternate_text' => $data['media_alternate_text'],
						'media_section' => $data['media_section'],
						'section_id' => $propertyId,
						'media_type' => 'images',
						'media_mimetype' => $mediaMimeType,
						'published' => $data['media_published'],
						'ordering' => $data['media_ordering']
					);

					$query->clear()
						->select('*')
						->from($db->qn('#__redshop_media'))
						->where($db->qn('section_id') . ' = ' . (int) $propertyId)
						->where($db->qn('media_name') . ' = ' . $db->q($newMediaName))
						->where($db->qn('media_section') . ' = ' . $db->q('property'));

					$mediaProperty = $db->setQuery($query)->loadObject();

					if ($mediaProperty)
					{
						$mediaId = $mediaProperty->media_id;
						$fields = array();

						foreach ($media as $k => $v)
						{
							$fields[] = $db->qn($k) . ' = ' . $db->q($v);
						}

						$query->clear()
							->update($db->qn('#__redshop_media'))
							->set($fields)
							->where($db->qn('media_id') . ' = ' . (int) $mediaId);

						$db->setQuery($query)->execute();
					}
					else
					{
						$columns = $db->qn(array_keys($media));
						$values = $db->q(array_values($media));

						$query->clear()
							->insert($db->qn('#__redshop_media'))
							->columns($columns)
							->values(implode(',', $values));

						$db->setQuery($query)->execute();
					}
				}

				// Property image
				if (!empty($data['property_image']) && JFile::exists($data['property_image']))
				{
					$file = REDSHOP_FRONT_IMAGES_RELPATH . 'product_attributes/' . basename($data['property_image']);

					// Copy If file is not already exist
					if (!JFile::exists($file))
					{
						copy($data['property_image'], $file);
					}
				}

				// Property main image
				if (!empty($data['property_main_image']) && JFile::exists($data['property_main_image']))
				{
					$file = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . basename($data['property_main_image']);

					// Copy If file is not already exist
					if (!JFile::exists($file))
					{
						copy($data['property_main_image'], $file);
					}
				}

				// Media
				if (!empty($data['media_name']) && ($data['media_section'] == 'property') && JFile::exists($data['media_name']))
				{
					$file = REDSHOP_FRONT_IMAGES_RELPATH . 'property/' . basename($data['media_name']);

					// Copy If file is not already exist
					if (!JFile::exists($file))
					{
						copy($data['media_name'], $file);
					}
				}

				return true;
			}
			elseif (!$propertyId)
			{
				return false;
			}
		}

		if ($hasSubPropertyName)
		{
			// Get Sub-property ID
			$query = $db->getQuery(true)
				->select($db->quoteName('subattribute_color_id'))
				->from($db->quoteName('#__redshop_product_subattribute_color'))
				->where($db->quoteName('subattribute_id') . ' = ' . $db->quote($propertyId))
				->where($db->quoteName('subattribute_color_name') . ' = ' . $db->quote($data['subattribute_color_name']));
			$subPropertyId = (int) $db->setQuery($query)->loadResult();

			$subPropertyTable = JTable::getInstance('Subattribute_Property', 'Table');

			if ($subPropertyId)
			{
				$subPropertyTable->load($subPropertyId);
			}
			else
			{
				$subPropertyTable->subattribute_color_id = null;
			}

			$subPropertyTable->subattribute_color_name = $data['subattribute_color_name'];
			$subPropertyTable->subattribute_color_price = $data['subattribute_color_price'];
			$subPropertyTable->ordering = $data['subattribute_color_ordering'];
			$subPropertyTable->setdefault_selected = $data['subattribute_setdefault_selected'];
			$subPropertyTable->subattribute_color_title = $data['subattribute_color_title'];
			$subPropertyTable->subattribute_color_number = $data['subattribute_virtual_number'];

			$oprand = in_array($data['subattribute_color_oprand'], array('+', '-', '*', '/', '=')) ? $data['subattribute_color_oprand'] : '';
			$subPropertyTable->oprand = $oprand;
			$subPropertyTable->subattribute_color_image = isset($data['subattribute_color_image']) ? basename($data['subattribute_color_image']) : '';
			$subPropertyTable->subattribute_id = $propertyId;

			if (!$subPropertyTable->store())
			{
				return false;
			}

			$subPropertyId = $subPropertyTable->subattribute_color_id;

			// Sub-properties stock
			if (!empty($data['subattribute_stock']))
			{
				$stocks = explode("#", $data['subattribute_stock']);

				foreach ($stocks as $stock)
				{
					if (empty($stock))
					{
						continue;
					}

					$stock = explode(":", $stock);

					if (count($stock) != 2)
					{
						continue;
					}

					$query->clear()
						->select("*")
						->from($db->quoteName('#__redshop_stockroom'))
						->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($stock[0]));
					$stockDatas = $db->setQuery($query)->loadObjectList();

					if (empty($stockDatas))
					{
						continue;
					}

					$query->clear()
						->select('COUNT(*)')
						->from('#__redshop_product_attribute_stockroom_xref')
						->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($stock[0]))
						->where($db->quoteName('section') . ' = ' . $db->quote('subproperty'))
						->where($db->quoteName('section_id') . ' = ' . $db->quote($subPropertyId));

					$count = $db->setQuery($query)->loadResult();

					if ($count)
					{
						$query->clear()
							->update($db->quoteName('#__redshop_product_attribute_stockroom_xref'))
							->set($db->quoteName('quantity') . ' = ' . $db->quote($stock[1]))
							->where($db->quoteName('stockroom_id') . ' = ' . $db->quote($stock[0]))
							->where($db->quoteName('section') . ' = ' . $db->quote('subproperty'))
							->where($db->quoteName('section_id') . ' = ' . $db->quote($subPropertyId));
						$db->setQuery($query)->execute();
					}
					else
					{
						$insert = new stdClass;
						$insert->quantity = $stock[1];
						$insert->stockroom_id = $stock[0];
						$insert->section_id = $subPropertyId;
						$insert->section = 'subproperty';
						$db->insertObject('#__redshop_product_attribute_stockroom_xref', $insert);
					}
				}
			}

			if (!empty($data['media_name']) && ($data['media_section'] == 'subproperty'))
			{
				$newMediaName = basename($data['media_name']);
				$ext = pathinfo($newMediaName, PATHINFO_EXTENSION);
				$mediaMimeType = 'images/' . $ext;

				$media = array(
					'media_name' => $newMediaName,
					'media_alternate_text' => $data['media_alternate_text'],
					'media_section' => $data['media_section'],
					'section_id' => $subPropertyId,
					'media_type' => 'images',
					'media_mimetype' => $mediaMimeType,
					'published' => $data['media_published'],
					'ordering' => $data['media_ordering']
				);

				$query->clear()
					->select('*')
					->from($db->qn('#__redshop_media'))
					->where($db->qn('section_id') . ' = ' . (int) $subPropertyId)
					->where($db->qn('media_name') . ' = ' . $db->q($newMediaName))
					->where($db->qn('media_section') . ' = ' . $db->q('subproperty'));

				$mediaProperty = $db->setQuery($query)->loadObject();

				if ($mediaProperty)
				{
					$mediaId = $mediaProperty->media_id;
					$fields = array();

					foreach ($media as $k => $v)
					{
						$fields[] = $db->qn($k) . ' = ' . $db->q($v);
					}

					$query->clear()
						->update($db->qn('#__redshop_media'))
						->set($fields)
						->where($db->qn('media_id') . ' = ' . (int) $mediaId);

					$db->setQuery($query)->execute();
				}
				else
				{
					$columns = $db->qn(array_keys($media));
					$values = $db->q(array_values($media));

					$query->clear()
						->insert($db->qn('#__redshop_media'))
						->columns($columns)
						->values(implode(',', $values));

					$db->setQuery($query)->execute();
				}
			}

			// Sub-property image
			if (!empty($data['subattribute_color_image']) && JFile::exists($data['subattribute_color_image']))
			{
				$file = REDSHOP_FRONT_IMAGES_RELPATH . 'subcolor/' . basename($data['subattribute_color_image']);

				// Copy If file is not already exist
				if (!JFile::exists($file))
				{
					copy($data['subattribute_color_image'], $file);
				}
			}

			if (!empty($data['media_name']) && ($data['media_section'] == 'subproperty') && JFile::exists($data['media_name']))
			{
				$file = REDSHOP_FRONT_IMAGES_RELPATH . 'subproperty/' . basename($data['media_name']);

				// Copy If file is not already exist
				if (!JFile::exists($file))
				{
					copy($data['media_name'], $file);
				}
			}
		}

		return true;
	}
}
