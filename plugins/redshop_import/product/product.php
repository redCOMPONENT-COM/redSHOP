<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractImportPlugin;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Product
 *
 * @since  1.0
 */
class PlgRedshop_ImportProduct extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $primaryKey = 'product_id';

	/**
	 * @var string
	 */
	protected $nameKey = 'product_name';

	/**
	 * List of columns for encoding UTF8
	 *
	 * @var array
	 */
	protected $encodingColumns = array('product_name', 'product_desc', 'product_s_desc');

	/**
	 * List of columns for number format
	 *
	 * @var array
	 */
	protected $numberColumns = array('product_price');

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return '';
	}

	/**
	 * Event run when run importing.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Import()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input           = JFactory::getApplication()->input;
		$this->encoding  = $input->getString('encoding', 'UTF-8');
		$this->separator = $input->getString('separator', ',');
		$this->folder    = $input->getCmd('folder', '');

		return json_encode($this->importing());
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
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return JTable::getInstance('Product_Detail', 'Table');
	}

	/**
	 * Process mapping data.
	 *
	 * @param   array $header Header array
	 * @param   array $data   Data array
	 *
	 * @return  array           Mapping data.
	 *
	 * @since   1.0.0
	 */
	public function processMapping($header, $data)
	{
		$data = parent::processMapping($header, $data);
		$db   = $this->db;

		// Get manufacturer id
		if (!empty($data['manufacturer_name']))
		{
			$query = $db->getQuery(true)
				->select($db->qn("manufacturer_id"))
				->from($db->qn('#__redshop_manufacturer'))
				->where($db->qn('manufacturer_name') . ' = ' . $db->quote($data['manufacturer_name']));

			$manufacturerId = (int) $db->setQuery($query)->loadResult();

			if (!empty($manufacturerId))
			{
				$data['manufacturer_id'] = $manufacturerId;
			}
			else
			{
				JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

				$manufacturer                    = RedshopTable::getInstance('Manufacturer', 'RedshopTable');
				$manufacturer->manufacturer_name = $data['manufacturer_name'];
				$manufacturer->published         = 1;
				$manufacturer->store();
				$data['manufacturer_id'] = $manufacturer->manufacturer_id;
			}
		}

		if (empty($data['product_thumb_image']))
		{
			unset($data['product_thumb_image']);
		}
		else
		{
			if (!JUri::isInternal($data['product_thumb_image']))
			{
				$url       = $data['product_thumb_image'];
				$imageName = basename($url);
				$fileName  = RedshopHelperMedia::cleanFileName($imageName, $data['product_id']);
				$dest      = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $fileName;
				JFile::write($dest, file_get_contents($url));
				$data['product_thumb_image'] = $fileName;
			}
			else
			{
				$imageName                   = basename($data['product_thumb_image']);
				$data['product_thumb_image'] = $imageName;
			}
		}

		if (empty($data['product_full_image']))
		{
			unset($data['product_full_image']);
		}
		else
		{
			if (!JUri::isInternal($data['product_full_image']))
			{
				$url        = $data['product_full_image'];
				$binaryData = file_get_contents($url);

				if ($binaryData === false)
				{
					unset($data['product_full_image']);
				}
				else
				{
					$imageName = basename($url);
					$fileName  = RedshopHelperMedia::cleanFileName($imageName, $data['product_id']);
					$dest      = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $fileName;
					JFile::write($dest, $binaryData);
					$data['product_full_image'] = $fileName;
				}
			}
			else
			{
				$imageName                  = basename($data['product_full_image']);
				$data['product_full_image'] = $imageName;
			}
		}

		if (empty($data['product_back_full_image']))
		{
			unset($data['product_back_full_image']);
		}
		else
		{
			if (!JUri::isInternal($data['product_back_full_image']))
			{
				$url       = $data['product_back_full_image'];
				$imageName = basename($url);
				$fileName  = RedshopHelperMedia::cleanFileName($imageName, $data['product_id']);
				$dest      = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $fileName;
				JFile::write($dest, file_get_contents($url));
				$data['product_back_full_image'] = $fileName;
			}
			else
			{
				$imageName                       = basename($data['product_back_full_image']);
				$data['product_back_full_image'] = $imageName;
			}
		}

		if (empty($data['product_preview_back_image']))
		{
			unset($data['product_preview_back_image']);
		}
		else
		{
			if (!JUri::isInternal($data['product_preview_back_image']))
			{
				$url       = $data['product_preview_back_image'];
				$imageName = basename($url);
				$fileName  = RedshopHelperMedia::cleanFileName($imageName, $data['product_id']);
				$dest      = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $fileName;
				JFile::write($dest, file_get_contents($url));
				$data['product_preview_back_image'] = $fileName;
			}
			else
			{
				$imageName                          = basename($data['product_preview_back_image']);
				$data['product_preview_back_image'] = $imageName;
			}
		}

		if (empty($data['product_back_thumb_image']))
		{
			unset($data['product_back_thumb_image']);
		}
		else
		{
			if (!JUri::isInternal($data['product_back_thumb_image']))
			{
				$url       = $data['product_back_thumb_image'];
				$imageName = basename($url);
				$fileName  = RedshopHelperMedia::cleanFileName($imageName, $data['product_id']);
				$dest      = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $fileName;
				JFile::write($dest, file_get_contents($url));
				$data['product_back_thumb_image'] = $fileName;
			}
			else
			{
				$imageName                        = basename($data['product_back_thumb_image']);
				$data['product_back_thumb_image'] = $imageName;
			}
		}

		if (empty($data['product_preview_image']))
		{
			unset($data['product_preview_image']);
		}
		else
		{
			if (!JUri::isInternal($data['product_preview_image']))
			{
				$url       = $data['product_preview_image'];
				$imageName = basename($url);
				$fileName  = RedshopHelperMedia::cleanFileName($imageName, $data['product_id']);
				$dest      = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $fileName;
				JFile::write($dest, file_get_contents($url));
				$data['product_preview_image'] = $fileName;
			}
			else
			{
				$imageName                     = basename($data['product_preview_image']);
				$data['product_preview_image'] = $imageName;
			}
		}

		if (!empty($data['discount_stratdate']))
		{
			$data['discount_stratdate'] = JFactory::getDate(date('d-m-Y H:i:s', strtotime($data['discount_stratdate'])))->toUnix();
		}

		if (!empty($data['discount_enddate']))
		{
			$data['discount_enddate'] = JFactory::getDate(date('d-m-Y H:i:s', strtotime($data['discount_enddate'])))->toUnix();
		}

		// Setting product on sale when discount dates are set
		if (!empty($data['discount_stratdate']) || !empty($data['discount_enddate']))
		{
			$data['product_on_sale'] = 1;
		}
		else
		{
			$data['product_on_sale'] = !isset($data['product_on_sale']) ? 0 : (int) $data['product_on_sale'];
		}

		if (isset($data['product_price']) && false !== strpos($data['product_price'], ','))
		{
			$data['product_price'] = str_replace(',', '.', $data['product_price']);
		}

		if (isset($data['discount_price']) && false !== strpos($data['discount_price'], ','))
		{
			$data['discount_price'] = str_replace(',', '.', $data['discount_price']);
		}

		// Get product_id base on product_number
		if (!empty($data['product_number']))
		{
			$query = $db->getQuery(true)
				->select($db->qn('product_id'))
				->from($db->qn('#__redshop_product'))
				->where($db->qn('product_number') . ' = ' . $db->quote($data['product_number']));

			$data['product_id'] = (int) $db->setQuery($query)->loadResult();
		}

		return $data;
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable $table Header array
	 * @param   array   $data  Data array
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function processImport($table, $data)
	{
		$isNew = false;
		$db    = $this->db;

		if (empty($data['product_number']))
		{
			return false;
		}

		// This is attribute data line.
		if (!empty($data['attribute_name']))
		{
			$this->importAttributesData($data);

			return true;
		}

		// Try to load old data.
		if (array_key_exists($this->primaryKey, $data) && $data[$this->primaryKey])
		{
			$isNew = $table->load($data[$this->primaryKey]);
		}

		// Bind data to table
		if (!$table->bind($data))
		{
			return false;
		}

		// Check table before storing
		if (!$table->check())
		{
			return false;
		}

		// Insert for new data or update exist data.
		if ((!$isNew && !$db->insertObject('#__redshop_product', $table, $this->primaryKey)) || !$table->store(false))
		{
			return false;
		}

		$productId = $table->{$this->primaryKey};

		// Product Full Image
		if (!empty($data['product_full_image']))
		{
			$src = $data['sitepath'] . "components/com_redshop/assets/images/product/" . $data['product_full_image'];

			if (JFile::exists($src))
			{
				$file = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $data['product_full_image'];

				if (!JFile::exists($file))
				{
					JFile::copy($src, $file);
				}
			}

			$query = $db->getQuery(true)
				->select("COUNT(*)")
				->from($db->qn('#__redshop_media'))
				->where($db->qn('media_name') . ' LIKE ' . $db->quote($data['product_full_image']))
				->where($db->qn('media_section') . ' LIKE ' . $db->quote('product'))
				->where($db->qn('section_id') . ' = ' . $db->quote($productId))
				->where($db->qn('media_type') . ' = ' . $db->quote('images'))
				->where($db->qn('published') . ' = ' . $db->quote('1'));

			$count = $db->setQuery($query)->loadResult();

			if (!$count)
			{
				$mediaTable                 = JTable::getInstance('Media_Detail', 'Table');
				$mediaTable->media_id       = 0;
				$mediaTable->media_name     = $data['product_full_image'];
				$mediaTable->media_section  = 'product';
				$mediaTable->section_id     = $productId;
				$mediaTable->media_type     = 'images';
				$mediaTable->media_mimetype = '';
				$mediaTable->published      = 1;

				$mediaTable->store();
				unset($mediaTable);
			}
		}

		// Additional images
		$this->importAdditionalImages($productId, $data);

		// Additional videos
		$this->importAdditionalVideos($productId, $data);

		// Additional documents
		$this->importAdditionalDocuments($productId, $data);

		// Additional downloads
		$this->importAdditionalDownloads($productId, $data);

		// Product Extra Field Import
		$extraFieldColumns = $this->getExtraFieldNames($data);

		if (!empty($extraFieldColumns))
		{
			foreach ($extraFieldColumns as $fieldKey)
			{
				$this->importProductFieldData($fieldKey, $data, $productId);
			}
		}

		// Category relation
		$this->categoryRelation($productId, $data);

		// Accessories product
		$this->importAccessoriesProduct($productId, $data);

		// Product stock data
		$this->importProductStock($productId, $data);

		return true;
	}

	/**
	 * Get Extra Field Names
	 *
	 * @param   array $keyProducts Array key products
	 *
	 * @return  array
	 */
	public function getExtraFieldNames($keyProducts)
	{
		$extraFieldNames = array();

		if (is_array($keyProducts))
		{
			$pattern = '/rs_/';

			foreach ($keyProducts as $key => $value)
			{
				if (preg_match($pattern, $key))
				{
					$extraFieldNames[] = $key;
				}
			}
		}

		return $extraFieldNames;
	}

	/**
	 * Update/insert product extra field data
	 *
	 * @param   string  $fieldName Extra Field Names
	 * @param   array   $data      CSV rawdata
	 * @param   integer $productId Product Id
	 *
	 * @return  void
	 */
	public function importProductFieldData($fieldName, $data, $productId)
	{
		if (empty($fieldName) || empty($data))
		{
			return;
		}

		$db    = $this->db;
		$value = $data[$fieldName];

		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('name') . ' = ' . $db->quote($fieldName));

		if ($fieldId = $db->setQuery($query)->loadResult())
		{
			$query->clear()
				->select('data_id')
				->from($db->qn('#__redshop_fields_data'))
				->where($db->qn('fieldid') . ' = ' . $db->quote($fieldId))
				->where($db->qn('itemid') . ' = ' . (int) $productId)
				->where($db->qn('section') . ' = 1');

			if ($dataId = $db->setQuery($query)->loadResult())
			{
				$query->clear()
					->update($db->qn('#__redshop_fields_data'))
					->set('data_txt = ' . $db->quote($value))
					->where('data_id = ' . $db->quote($dataId));
				$db->setQuery($query)->execute();
			}
			elseif (!empty($value))
			{
				$fieldData           = new stdClass;
				$fieldData->fieldid  = $fieldId;
				$fieldData->data_txt = $value;
				$fieldData->itemid   = $productId;
				$fieldData->section  = 1;
				$db->insertObject('#__redshop_fields_data', $fieldData);
			}
		}
	}

	/**
	 * Method for insert/update categories relation
	 *
	 * @param   int   $productId Product ID
	 * @param   array $data      Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function categoryRelation($productId = 0, $data = array())
	{
		if (empty($data) || !$productId)
		{
			return;
		}

		$categoryId   = !empty($data['category_id']) ? $data['category_id'] : '';
		$categoryName = !empty($data['category_name']) ? $data['category_name'] : '';

		if (empty($categoryId) && empty($categoryName))
		{
			return;
		}

		$db    = $this->db;
		$query = $db->getQuery(true);

		// Prepare categories ids array
		if (!empty($categoryId))
		{
			$categories = explode('###', $categoryId);
		}
		else
		{
			$categoryName = explode('###', $categoryName);

			foreach ($categoryName as $i => $cat)
			{
				$categoryName[$i] = $db->quote($cat);
			}

			$query->clear()
				->select($db->qn('id'))
				->from($db->qn('#__redshop_category'))
				->where($db->qn('name') . ' IN (' . implode(',', $categoryName) . ')');
			$categories = $db->setQuery($query)->loadColumn();
		}

		// Remove all current product category
		$query->clear()
			->delete($db->qn('#__redshop_product_category_xref'))
			->where($db->qn('product_id') . ' = ' . $db->quote($productId));
		$db->setQuery($query)->execute();

		// Skip if there are no categories.
		if (empty($categories))
		{
			return;
		}

		// Insert new categories
		$query->clear()
			->insert($db->qn('#__redshop_product_category_xref'))
			->columns(array('category_id', 'product_id'));

		foreach ($categories as $category)
		{
			$query->values($db->quote($category) . ',' . $db->quote($productId));
		}

		$db->setQuery($query)->execute();
	}

	/**
	 * Method for insert/update accessories products
	 *
	 * @param   int   $productId Product ID
	 * @param   array $data      Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function importAccessoriesProduct($productId = 0, $data = array())
	{
		if (empty($data) || !$productId || empty($data['accessory_products']))
		{
			return;
		}

		$accessories = explode("###", $data['accessory_products']);

		if (empty($accessories))
		{
			return;
		}

		$db    = $this->db;
		$query = $db->getQuery(true);

		foreach ($accessories as $accessory)
		{
			$accessoryIds        = explode('~', $accessory);
			$accessoryProductSku = $accessoryIds[0];
			$accessoryPrice      = $accessoryIds[1];

			$query->clear()
				->select('COUNT(*)')
				->from($db->qn('#__redshop_product_accessory', 'pa'))
				->leftJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = pa.child_product_id')
				->where($db->qn('pa.product_id') . ' = ' . $db->quote($productId))
				->where($db->qn('p.product_number') . ' = ' . $db->quote($accessoryProductSku));

			$total = $db->setQuery($query)->loadresult();

			$query->clear()
				->select($db->qn('product_id'))
				->from($db->qn('#__redshop_product'))
				->where($db->qn('product_number') . ' = ' . $db->quote($accessoryProductSku));

			$childProductId = $db->setQuery($query)->loadresult();

			if (!$total)
			{
				$insert                   = new stdClass;
				$insert->accessory_id     = '';
				$insert->product_id       = $productId;
				$insert->child_product_id = $childProductId;
				$insert->accessory_price  = $accessoryPrice;

				$db->insertObject('#__redshop_product_accessory', $insert);
			}
			else
			{
				$query = $db->getQuery(true)
					->update($db->qn('#__redshop_product_accessory'))
					->set($db->qn('accessory_price') . ' = ' . $db->quote($accessoryPrice))
					->where($db->qn('product_id') . ' = ' . $db->quote($productId))
					->where($db->qn('child_product_id') . ' = ' . $db->quote($childProductId));

				$db->setQuery($query)->execute();
			}
		}
	}

	/**
	 * Method for insert/update product stocks
	 *
	 * @param   int   $productId Product ID
	 * @param   array $data      Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function importProductStock($productId = 0, $data = array())
	{
		if (empty($data) || !$productId || empty($data['accessory_products']))
		{
			return;
		}

		// Product stock
		$stockrooms = RedshopHelperStockroom::getStockroom();

		if (empty($stockrooms))
		{
			return;
		}

		$db    = $this->db;
		$query = $db->getQuery(true);

		foreach ($stockrooms as $stockroom)
		{
			if (!empty($data[$stockroom->stockroom_name]))
			{
				$stock = $data[$stockroom->stockroom_name];

				$query->clear()
					->select('COUNT(*) AS total')
					->from($db->qn('#__redshop_product_stockroom_xref'))
					->where($db->qn('product_id') . ' = ' . $db->quote($productId))
					->where($db->qn('stockroom_id') . ' = ' . $db->quote($stockroom->stockroom_id));

				$db->setQuery($query);
				$total = $db->loadresult();

				if ($total <= 0)
				{
					$insert               = new stdClass;
					$insert->product_id   = $productId;
					$insert->stockroom_id = $stockroom->stockroom_id;
					$insert->quantity     = $stock;
					$db->insertObject("#__redshop_product_stockroom_xref", $insert);
				}
				else
				{
					$query = $db->getQuery(true)
						->update($db->qn('#__redshop_product_stockroom_xref'))
						->set($db->qn('quantity') . ' = ' . $db->quote($stock))
						->where($db->qn('product_id') . ' = ' . $db->quote($productId))
						->where($db->qn('stockroom_id') . ' = ' . $db->quote($stockroom->stockroom_id));

					$db->setQuery($query)->execute();
				}
			}
		}
	}

	/**
	 * Method for insert/update additional images
	 *
	 * @param   int   $productId Product ID
	 * @param   array $data      Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function importAdditionalImages($productId = 0, $data = array())
	{
		if (empty($data) || !$productId || empty($data['images']))
		{
			return;
		}

		$images = explode('#', $data['images']);
		$images = array_filter(array_values($images));

		if (empty($images))
		{
			return;
		}

		$db    = $this->db;
		$query = $db->getQuery(true);

		$sectionImagesOrder = !empty($data['images_order']) ? explode('#', $data['images_order']) : array();
		$sectionImagesText  = !empty($data['images_alternattext']) ? explode('#', $data['images_order']) : array();

		foreach ($images as $index => $image)
		{
			// Copy file
			$source = $data['sitepath'] . "components/com_redshop/assets/images/product/" . $image;

			if (JFile::exists($source))
			{
				$file = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $image;

				if (!JFile::exists($file))
				{
					JFile::copy($source, $file);
				}
			}
			else
			{
				if (!JUri::isInternal($image))
				{
					$binaryData = @file_get_contents($image);

					if ($binaryData === false)
					{
						unset($data['product_full_image']);
					}
					else
					{
						$imageName = basename($image);
						$fileName  = RedShopHelperImages::cleanFileName($imageName, $data['product_id']);
						$dest      = REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $fileName;
						JFile::write($dest, $binaryData);
						$data['product_preview_image'] = $fileName;
					}
				}
			}

			$ordering      = isset($sectionImagesOrder[$index]) ? $sectionImagesOrder[$index] : 0;
			$alternateText = isset($sectionImagesText[$index]) ? $sectionImagesText[$index] : '';

			$query->clear()
				->select('media_id')
				->from($db->qn('#__redshop_media'))
				->where($db->qn('media_name') . ' LIKE ' . $db->quote($image))
				->where($db->qn('media_section') . ' = ' . $db->quote('product'))
				->where($db->qn('section_id') . ' = ' . $db->quote($productId))
				->where($db->qn('media_type') . ' = ' . $db->quote('images'));

			$mediaId = $db->setQuery($query)->loadResult();

			if (!$mediaId)
			{
				$rows                       = JTable::getInstance('Media_Detail', 'Table');
				$rows->media_id             = 0;
				$rows->media_name           = $image;
				$rows->media_section        = 'product';
				$rows->section_id           = $productId;
				$rows->media_type           = 'images';
				$rows->media_mimetype       = '';
				$rows->published            = 1;
				$rows->media_alternate_text = $alternateText;
				$rows->ordering             = $ordering;

				$rows->store();
			}
			else
			{
				$query = $db->getQuery(true)
					->update($db->qn('#__redshop_media'))
					->set($db->qn('media_alternate_text') . ' = ' . $db->quote($alternateText))
					->set($db->qn('ordering') . ' = ' . $db->quote($ordering))
					->where($db->qn('media_id') . ' = ' . $db->quote($mediaId));
				$db->setQuery($query)->execute();
			}
		}
	}

	/**
	 * Method for insert/update additional videos
	 *
	 * @param   int   $productId Product ID
	 * @param   array $data      Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function importAdditionalVideos($productId = 0, $data = array())
	{
		if (empty($data) || !$productId || empty($data['video']))
		{
			return;
		}

		$videos = explode('#', $data['video']);
		$videos = array_filter(array_values($videos));

		if (empty($videos))
		{
			return;
		}

		$db    = $this->db;
		$query = $db->getQuery(true);

		$orderings      = !empty($data['video_order']) ? explode('#', $data['video_order']) : array();
		$alternateTexts = !empty($data['video_alternattext']) ? explode('#', $data['video_alternattext']) : array();

		foreach ($videos as $index => $video)
		{
			// Copy file
			$source = $data['sitepath'] . "components/com_redshop/assets/video/product/" . $video;

			if (JFile::exists($source))
			{
				$file = JPATH_COMPONENT_SITE . '/assets/video/product/' . $video;

				if (!JFile::exists($file))
				{
					JFile::copy($source, $file);
				}
			}

			$ordering      = isset($orderings[$index]) ? $orderings[$index] : 0;
			$alternateText = isset($alternateTexts[$index]) ? $alternateTexts[$index] : '';

			$query->clear()
				->select('media_id')
				->from($db->qn('#__redshop_media'))
				->where($db->qn('media_name') . ' LIKE ' . $db->quote($video))
				->where($db->qn('media_section') . ' = ' . $db->quote('product'))
				->where($db->qn('section_id') . ' = ' . $db->quote($productId))
				->where($db->qn('media_type') . ' = ' . $db->quote('video'));

			$mediaId = $db->setQuery($query)->loadResult();

			if (!$mediaId)
			{
				$rows                       = JTable::getInstance('Media_Detail', 'Table');
				$rows->media_id             = 0;
				$rows->media_name           = $video;
				$rows->media_section        = 'product';
				$rows->section_id           = $productId;
				$rows->media_type           = 'video';
				$rows->media_mimetype       = '';
				$rows->published            = 1;
				$rows->media_alternate_text = $alternateText;
				$rows->ordering             = $ordering;

				$rows->store();
			}
			else
			{
				$query = $db->getQuery(true)
					->update($db->qn('#__redshop_media'))
					->set($db->qn('media_alternate_text') . ' = ' . $db->quote($alternateText))
					->set($db->qn('ordering') . ' = ' . $db->quote($ordering))
					->where($db->qn('media_id') . ' = ' . $db->quote($mediaId));
				$db->setQuery($query)->execute();
			}
		}
	}

	/**
	 * Method for insert/update additional documents
	 *
	 * @param   int   $productId Product ID
	 * @param   array $data      Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function importAdditionalDocuments($productId = 0, $data = array())
	{
		if (empty($data) || !$productId || empty($data['document']))
		{
			return;
		}

		$documents = explode('#', $data['document']);
		$documents = array_filter(array_values($documents));

		if (empty($documents))
		{
			return;
		}

		$db    = $this->db;
		$query = $db->getQuery(true);

		$orderings      = !empty($data['document_order']) ? explode('#', $data['document_order']) : array();
		$alternateTexts = !empty($data['document_alternattext']) ? explode('#', $data['document_alternattext']) : array();

		foreach ($documents as $index => $document)
		{
			// Copy file
			$source = $data['sitepath'] . "components/com_redshop/assets/document/product/" . $document;

			if (JFile::exists($source))
			{
				$file = REDSHOP_FRONT_DOCUMENT_RELPATH . 'product/' . $document;

				if (!JFile::exists($file))
				{
					JFile::copy($source, $file);
				}
			}

			$ordering      = isset($orderings[$index]) ? $orderings[$index] : 0;
			$alternateText = isset($alternateTexts[$index]) ? $alternateTexts[$index] : '';

			$query->clear()
				->select('media_id')
				->from($db->qn('#__redshop_media'))
				->where($db->qn('media_name') . ' LIKE ' . $db->quote($document))
				->where($db->qn('media_section') . ' = ' . $db->quote('product'))
				->where($db->qn('section_id') . ' = ' . $db->quote($productId))
				->where($db->qn('media_type') . ' = ' . $db->quote('document'));

			$mediaId = $db->setQuery($query)->loadResult();

			if (!$mediaId)
			{
				$rows                       = JTable::getInstance('Media_Detail', 'Table');
				$rows->media_id             = 0;
				$rows->media_name           = $document;
				$rows->media_section        = 'product';
				$rows->section_id           = $productId;
				$rows->media_type           = 'document';
				$rows->media_mimetype       = '';
				$rows->published            = 1;
				$rows->media_alternate_text = $alternateText;
				$rows->ordering             = $ordering;

				$rows->store();
			}
			else
			{
				$query = $db->getQuery(true)
					->update($db->qn('#__redshop_media'))
					->set($db->qn('media_alternate_text') . ' = ' . $db->quote($alternateText))
					->set($db->qn('ordering') . ' = ' . $db->quote($ordering))
					->where($db->qn('media_id') . ' = ' . $db->quote($mediaId));
				$db->setQuery($query)->execute();
			}
		}
	}

	/**
	 * Method for insert/update additional downloads
	 *
	 * @param   int   $productId Product ID
	 * @param   array $data      Data
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function importAdditionalDownloads($productId = 0, $data = array())
	{
		if (empty($data) || !$productId || empty($data['download']))
		{
			return;
		}

		$downloads = explode('#', $data['download']);
		$downloads = array_filter(array_values($downloads));

		if (empty($downloads))
		{
			return;
		}

		$db    = $this->db;
		$query = $db->getQuery(true);

		$orderings      = !empty($data['download_order']) ? explode('#', $data['download_order']) : array();
		$alternateTexts = !empty($data['download_alternattext']) ? explode('#', $data['download_alternattext']) : array();

		foreach ($downloads as $index => $download)
		{
			// Copy file
			$source = $data['sitepath'] . "components/com_redshop/assets/download/product/" . $download;

			if (JFile::exists($source))
			{
				$file = JPATH_COMPONENT_SITE . '/assets/download/product/' . $download;

				if (!JFile::exists($file))
				{
					JFile::copy($source, $file);
				}
			}

			$ordering      = isset($orderings[$index]) ? $orderings[$index] : 0;
			$alternateText = isset($alternateTexts[$index]) ? $alternateTexts[$index] : '';

			$query->clear()
				->select('media_id')
				->from($db->qn('#__redshop_media'))
				->where($db->qn('media_name') . ' LIKE ' . $db->quote($download))
				->where($db->qn('media_section') . ' = ' . $db->quote('product'))
				->where($db->qn('section_id') . ' = ' . $db->quote($productId))
				->where($db->qn('media_type') . ' = ' . $db->quote('download'));

			$mediaId = $db->setQuery($query)->loadResult();

			if (!$mediaId)
			{
				$rows                       = JTable::getInstance('Media_Detail', 'Table');
				$rows->media_id             = 0;
				$rows->media_name           = $download;
				$rows->media_section        = 'product';
				$rows->section_id           = $productId;
				$rows->media_type           = 'download';
				$rows->media_mimetype       = '';
				$rows->published            = 1;
				$rows->media_alternate_text = $alternateText;
				$rows->ordering             = $ordering;

				$rows->store();
			}
			else
			{
				$query = $db->getQuery(true)
					->update($db->qn('#__redshop_media'))
					->set($db->qn('media_alternate_text') . ' = ' . $db->quote($alternateText))
					->set($db->qn('ordering') . ' = ' . $db->quote($ordering))
					->where($db->qn('media_id') . ' = ' . $db->quote($mediaId));
				$db->setQuery($query)->execute();
			}
		}
	}

	/**
	 * Process import data.
	 *
	 * @param   array $data Data array
	 *
	 * @return  boolean
	 *
	 * @since   1.0.0
	 */
	public function importAttributesData($data)
	{
		$table = RedshopTable::getInstance('Attribute', 'RedshopTable');
		$db    = $this->db;
		$isNew = false;

		$hasPropertyName    = !empty($data['property_name']) ? true : false;
		$hasSubPropertyName = !empty($data['subattribute_color_name']) ? true : false;

		// Get product id
		$query     = $db->getQuery(true)
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_number') . ' = ' . $db->quote($data['product_number']));
		$productId = $db->setQuery($query)->loadResult();

		// Get attribute id
		$query->clear()
			->select($db->qn('attribute_id'))
			->from($db->qn('#__redshop_product_attribute'))
			->where($db->qn('product_id') . ' = ' . $db->quote($productId))
			->where($db->qn('attribute_name') . ' = ' . $db->quote($data['attribute_name']));
		$attributeId = $db->setQuery($query)->loadResult();

		// Insert attribute if not exist.
		if (!$attributeId)
		{
			if (!$hasPropertyName && !$hasSubPropertyName)
			{
				$attributeData = array(
					'attribute_name'           => $data['attribute_name'],
					'ordering'                 => $data['attribute_ordering'],
					'allow_multiple_selection' => $data['allow_multiple_selection'],
					'hide_attribute_price'     => $data['hide_attribute_price'],
					'attribute_required'       => $data['attribute_required'],
					'display_type'             => $data['display_type'],
					'product_id'               => $productId
				);

				if (!$table->bind($attributeData) || !$table->store())
				{
					return false;
				}

				return true;
			}

			return false;
		}

		$propertyId = 0;

		// Property data.
		if ($hasPropertyName)
		{
			// Get Property ID
			$query = $db->getQuery(true)
				->select($db->qn('property_id'))
				->from($db->qn('#__redshop_product_attribute_property'))
				->where($db->qn('attribute_id') . ' = ' . $db->quote($attributeId))
				->where($db->qn('property_name') . ' = ' . $db->quote($data['property_name']));
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

				// Property stock
				if (!empty($data['property_stock']))
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
							->from($db->qn('#__redshop_stockroom'))
							->where($db->qn('stockroom_id') . ' = ' . $db->quote($propertyStock[0]));
						$db->setQuery($query);
						$stockDatas = $db->loadObjectList();

						if (empty($stockDatas))
						{
							continue;
						}

						$query->clear()
							->select("*")
							->from($db->qn('#__redshop_product_attribute_stockroom_xref'))
							->where($db->qn('stockroom_id') . ' = ' . $db->quote($propertyStock[0]))
							->where($db->qn('section') . ' = ' . $db->quote('property'))
							->where($db->qn('section_id') . ' = ' . $db->quote($propertyId));

						$propertyProducts = $db->setQuery($query)->loadObjectList();

						if (!empty($propertyProducts))
						{
							$query->clear()
								->update($db->qn('#__redshop_product_attribute_stockroom_xref'))
								->set($db->qn('quantity') . ' = ' . $db->quote($propertyStock[1]))
								->where($db->qn('stockroom_id') . ' = ' . $db->quote($propertyStock[0]))
								->where($db->qn('section') . ' = ' . $db->quote('property'))
								->where($db->qn('section_id') . ' = ' . $db->quote($propertyId));
							$db->setQuery($query)->clear();
						}
						else
						{
							$newData               = new stdClass;
							$newData->quantity     = $propertyStock[1];
							$newData->stockroom_id = $propertyStock[0];
							$newData->section      = 'property';
							$newData->section_id   = $propertyId;
							$db->insertObject('#__redshop_product_attribute_stockroom_xref', $newData);
						}
					}
				}

				if (!empty($data['media_name']) && ($data['media_section'] == 'property'))
				{
					$newMediaName  = basename($data['media_name']);
					$ext           = pathinfo($newMediaName, PATHINFO_EXTENSION);
					$mediaMimeType = 'images/' . $ext;

					$media = array(
						'media_name'           => $newMediaName,
						'media_alternate_text' => $data['media_alternate_text'],
						'media_section'        => $data['media_section'],
						'section_id'           => $propertyId,
						'media_type'           => 'images',
						'media_mimetype'       => $mediaMimeType,
						'published'            => $data['media_published'],
						'ordering'             => $data['media_ordering']
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
						$fields  = array();

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
						$values  = $db->q(array_values($media));

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
			$query         = $db->getQuery(true)
				->select($db->qn('subattribute_color_id'))
				->from($db->qn('#__redshop_product_subattribute_color'))
				->where($db->qn('subattribute_id') . ' = ' . $db->quote($propertyId))
				->where($db->qn('subattribute_color_name') . ' = ' . $db->quote($data['subattribute_color_name']));

			$subPropertyId = (int) $db->setQuery($query)->loadResult();

			/** @var Tablesubattribute_property $subPropertyTable */
			$subPropertyTable = JTable::getInstance('Subattribute_Property', 'Table');

			if ($subPropertyId)
			{
				$subPropertyTable->load($subPropertyId);
			}
			else
			{
				$subPropertyTable->subattribute_color_id = null;
			}

			$subPropertyTable->subattribute_color_name   = $data['subattribute_color_name'];
			$subPropertyTable->subattribute_color_price  = $data['subattribute_color_price'];
			$subPropertyTable->ordering                  = $data['subattribute_color_ordering'];
			$subPropertyTable->setdefault_selected       = $data['subattribute_setdefault_selected'];
			$subPropertyTable->subattribute_color_title  = $data['subattribute_color_title'];
			$subPropertyTable->subattribute_color_number = $data['subattribute_virtual_number'];

			$oprand                                     = in_array($data['subattribute_color_oprand'], array('+', '-', '*', '/', '=')) ? $data['subattribute_color_oprand'] : '';
			$subPropertyTable->oprand                   = $oprand;
			$subPropertyTable->subattribute_color_image = isset($data['subattribute_color_image']) ? basename($data['subattribute_color_image']) : '';
			$subPropertyTable->subattribute_id          = $propertyId;

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
						->from($db->qn('#__redshop_stockroom'))
						->where($db->qn('stockroom_id') . ' = ' . $db->quote($stock[0]));
					$stockDatas = $db->setQuery($query)->loadObjectList();

					if (empty($stockDatas))
					{
						continue;
					}

					$query->clear()
						->select('COUNT(*)')
						->from('#__redshop_product_attribute_stockroom_xref')
						->where($db->qn('stockroom_id') . ' = ' . $db->quote($stock[0]))
						->where($db->qn('section') . ' = ' . $db->quote('subproperty'))
						->where($db->qn('section_id') . ' = ' . $db->quote($subPropertyId));

					$count = $db->setQuery($query)->loadResult();

					if ($count)
					{
						$query->clear()
							->update($db->qn('#__redshop_product_attribute_stockroom_xref'))
							->set($db->qn('quantity') . ' = ' . $db->quote($stock[1]))
							->where($db->qn('stockroom_id') . ' = ' . $db->quote($stock[0]))
							->where($db->qn('section') . ' = ' . $db->quote('subproperty'))
							->where($db->qn('section_id') . ' = ' . $db->quote($subPropertyId));
						$db->setQuery($query)->execute();
					}
					else
					{
						$insert               = new stdClass;
						$insert->quantity     = $stock[1];
						$insert->stockroom_id = $stock[0];
						$insert->section_id   = $subPropertyId;
						$insert->section      = 'subproperty';
						$db->insertObject('#__redshop_product_attribute_stockroom_xref', $insert);
					}
				}
			}

			if (!empty($data['media_name']) && ($data['media_section'] == 'subproperty'))
			{
				$newMediaName  = basename($data['media_name']);
				$ext           = pathinfo($newMediaName, PATHINFO_EXTENSION);
				$mediaMimeType = 'images/' . $ext;

				$media = array(
					'media_name'           => $newMediaName,
					'media_alternate_text' => $data['media_alternate_text'],
					'media_section'        => $data['media_section'],
					'section_id'           => $subPropertyId,
					'media_type'           => 'images',
					'media_mimetype'       => $mediaMimeType,
					'published'            => $data['media_published'],
					'ordering'             => $data['media_ordering']
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
					$fields  = array();

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
					$values  = $db->q(array_values($media));

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
