<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\Export;
use Joomla\Utilities\ArrayHelper;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Product
 *
 * @since  1.0
 */
class PlgRedshop_ExportProduct extends Export\AbstractBase
{
	/**
	 * Is include attributes.
	 *
	 * @var    boolean
	 *
	 * @since  1.0.1
	 */
	protected $isAttributes = false;

	/**
	 * Is include extra fields.
	 *
	 * @var    boolean
	 *
	 * @since  1.0.1
	 */
	protected $isExtraFields = false;

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 *
	 * @TODO   : Need to load XML File instead
	 */
	public function onAjaxProduct_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		// Prepare categories list.
		$categories = RedshopEntityCategory::getInstance(RedshopHelperCategory::getRootId())->getChildCategories();

		// Prepare manufacturers list.
		$manufacturers = RedshopModel::getInstance('Manufacturer', 'RedshopModel')->getManufacturers();

		$html = RedshopLayoutHelper::render(
			'export.config.product',
			array(
				'categories'     => $categories,
				'manufacturers'   => $manufacturers
			)
		);

		$this->config($html);
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->isAttributes  = JFactory::getApplication()->input->getBool('include_attributes', 0);
		$this->isExtraFields = JFactory::getApplication()->input->getBool('product_extrafields', 0);

		$this->start();
	}

	/**
	 * Event run on export process
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAjaxProduct_Export()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$this->export();
	}

	/**
	 * Event run on export process
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Complete()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return $this->convertFile();
	}

	/**
	 * Method for get query
	 *
	 * @return \JDatabaseQuery
	 *
	 * @since  1.0.0
	 */
	protected function getQuery()
	{
		$input = JFactory::getApplication()->input;

		$categories    = $input->get('product_categories', array(), 'ARRAY');
		$manufacturers = $input->get('product_manufacturers', array(), 'ARRAY');

		$db    = $this->db;
		$query = $db->getQuery(true)
			->select('m.manufacturer_name')
			->select('p.*')
			->select($db->quote(JUri::root()) . ' AS ' . $db->qn('sitepath'))
			->select(
				'(SELECT GROUP_CONCAT(' . $db->qn('pcx.category_id') . ' SEPARATOR ' . $db->quote('###')
				. ') FROM ' . $db->qn('#__redshop_product_category_xref', 'pcx')
				. ' WHERE ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id')
				. ' ORDER BY ' . $db->qn('pcx.category_id') . ') AS ' . $db->qn('category_id')
			)
			->select(
				'(SELECT GROUP_CONCAT(' . $db->qn('c.name') . ' SEPARATOR ' . $db->quote('###')
				. ') FROM ' . $db->qn('#__redshop_product_category_xref', 'pcx')
				. ' INNER JOIN ' . $db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id')
				. ' WHERE ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id')
				. ' ORDER BY ' . $db->qn('pcx.category_id') . ') AS ' . $db->qn('name')
			)
			->select(
				'(SELECT GROUP_CONCAT(CONCAT('
				. $db->qn('p2.product_number') . ',' . $db->quote('~') . ',' . $db->qn('pa.accessory_price') . ')'
				. ' SEPARATOR ' . $db->quote('###') . ') FROM ' . $db->qn('#__redshop_product_accessory', 'pa')
				. ' LEFT JOIN ' . $db->qn('#__redshop_product', 'p2') . ' ON ' . $db->qn('p2.product_id') . ' = ' . $db->qn('pa.child_product_id')
				. ' WHERE ' . $db->qn('pa.product_id') . ' = ' . $db->qn('p.product_id') . ') AS ' . $db->qn('accessory_products')
			)
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('pc.product_id'))
			->leftJoin($db->qn('#__redshop_manufacturer', 'm') . ' ON ' . $db->qn('p.manufacturer_id') . ' = ' . $db->qn('m.manufacturer_id'))
			->group($db->qn('p.product_id'))
			->order($db->qn('p.product_id') . ' asc');

		$medias = array(
			'images'   => array('images', 'images_order', 'images_alternattext'),
			'video'    => array('video', 'video_order', 'video_alternattext'),
			'document' => array('document', 'document_order', 'document_alternattext'),
			'download' => array('download', 'download_order', 'download_alternattext'),
		);

		$mediaColumn = array('m.media_name', 'm.ordering', 'm.media_alternate_text');
		$mediaQuery  = $db->getQuery(true);

		foreach ($medias as $mediaType => $columns)
		{
			foreach ($columns as $index => $columnAlias)
			{
				$mediaQuery->clear()
					->select('GROUP_CONCAT(' . $db->qn($mediaColumn[$index]) . ' SEPARATOR ' . $db->quote('###') . ')')
					->from($db->qn('#__redshop_media', 'm') . ' USE INDEX(' . $db->qn('#__rs_idx_media_common') . ')')
					->where($db->qn('m.section_id') . ' = ' . $db->qn('p.product_id'))
					->where($db->qn('m.media_type') . ' = ' . $db->quote($mediaType))
					->where($db->qn('m.media_section') . ' = ' . $db->quote('product'))
					->order($db->qn('m.ordering'));
				$query->select('(' . $mediaQuery . ') AS ' . $db->qn($columnAlias));
			}
		}

		if (!empty($categories))
		{
			ArrayHelper::toInteger($categories);
			$query->where($db->qn('pc.category_id') . ' IN (' . implode(',', $categories) . ')');
		}

		if (!empty($manufacturers))
		{
			ArrayHelper::toInteger($manufacturers);
			$query->where($db->qn('p.manufacturer_id') . ' IN (' . implode(',', $manufacturers) . ')');
		}

		return $query;
	}

	/**
	 * Method for get headers data.
	 *
	 * @return  mixed
	 *
	 * @since  1.0.0
	 */
	protected function getHeader()
	{
		// Get main data.
		$headers = parent::getHeader();
		$input   = JFactory::getApplication()->input;

		// Stockroom
		$stockrooms = RedshopHelperStockroom::getStockroom();

		if (!empty($stockrooms))
		{
			foreach ($stockrooms as $stockroom)
			{
				$headers[] = $stockroom->stockroom_name;
			}
		}

		// Extra fields if needed.
		if ($this->isExtraFields)
		{
			$db     = $this->db;
			$query  = $db->getQuery(true)
				->select($db->qn('name'))
				->from($db->qn('#__redshop_fields'))
				->where($db->qn('section') . ' = 1');
			$result = $db->setQuery($query)->loadColumn();

			if (!empty($result))
			{
				$headers = array_merge($headers, $result);
			}
		}

		// Product attributes if needed.
		if (JFactory::getApplication()->input->getBool('include_attributes', 0) == true)
		{
			$headers = array_merge($headers, $this->getAttributesHeader());
		}

		return $headers;
	}

	/**
	 * Method for get additional headers when include attributes enabled.
	 *
	 * @return  array
	 *
	 * @since   1.0.1
	 */
	protected function getAttributesHeader()
	{
		return array(
			'attribute_name', 'attribute_ordering', 'allow_multiple_selection', 'hide_attribute_price', 'attribute_required',
			'display_type', 'property_name', 'property_stock', 'property_ordering', 'property_virtual_number', 'setdefault_selected',
			'setrequire_selected', 'setdisplay_type', 'oprand', 'property_price', 'property_image', 'property_main_image',
			'subattribute_color_name', 'subattribute_stock', 'subattribute_color_ordering', 'subattribute_setdefault_selected',
			'subattribute_color_title', 'subattribute_virtual_number', 'subattribute_color_oprand', 'required_sub_attribute',
			'subattribute_color_price', 'subattribute_color_image', 'delete', 'media_name', 'media_alternate_text', 'media_section',
			'media_published', 'media_ordering'
		);
	}

	/**
	 * Method for do some stuff for data return. (Like image path,...)
	 *
	 * @param   array $data Array of data.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	protected function processData(&$data)
	{
		if (empty($data))
		{
			return;
		}

		$imagesColumn = array('product_full_image', 'product_thumb_image', 'product_back_full_image', 'product_back_thumb_image',
			'product_preview_image', 'product_preview_back_image');

		// Stockroom
		$stockrooms = RedshopHelperStockroom::getStockroom();

		// Process fields if needed.
		$fieldsData = array();

		if ($this->isExtraFields)
		{
			$productIds = array_map(
				function ($o)
				{
					return $o->product_id;
				},
				$data
			);

			$db         = $this->db;
			$query      = $db->getQuery(true)
				->select($db->qn(array('d.data_txt', 'd.itemid', 'f.name')))
				->from($db->qn('#__redshop_fields', 'f'))
				->leftJoin($db->qn('#__redshop_fields_data', 'd') . ' ON ' . $db->qn('f.id') . ' = ' . $db->qn('d.fieldid'))
				->where($db->qn('f.section') . ' = 1')
				->where($db->qn('d.itemid') . ' IN (' . implode(',', $productIds) . ')')
				->order($db->qn('f.id') . ' ASC');
			$fieldsData = $db->setQuery($query)->loadObjectList('itemid');
		}

		$isAttributes = JFactory::getApplication()->input->getBool('include_attributes', 0);
		$newData      = array();

		foreach ($data as $index => $item)
		{
			$item          = (array) $item;
			$attributeRows = array();

			if ($this->isExtraFields && isset($fieldsData[$item['product_id']]))
			{
				$itemField = $fieldsData[$item['product_id']];

				$item[$itemField->name] = $itemField->data_txt;
			}

			foreach ($item as $column => $value)
			{
				// Image path process
				if (in_array($column, $imagesColumn) && $value != "")
				{
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $value))
					{
						$item[$column] = REDSHOP_FRONT_IMAGES_ABSPATH . 'product/' . $value;
					}
					else
					{
						$item[$column] = "";
					}
				}
				else
				{
					$item[$column] = str_replace(array("\n", "\r"), "", $value);
				}

				if ($column == 'product_s_desc' || $column == 'product_desc')
				{
					$item[$column] = str_replace($this->separator, '', $this->db->escape($value));

					continue;
				}

				// Discount start date
				if ($column == 'discount_stratdate')
				{
					$item[$column] = !empty($item[$column]) ? RedshopHelperDatetime::convertDateFormat($item[$column]) : null;

					continue;
				}

				// Discount end date
				if ($column == 'discount_enddate')
				{
					$item[$column] = !empty($item[$column]) ? RedshopHelperDatetime::convertDateFormat($item[$column]) : null;

					continue;
				}
			}

			// Stockroom process
			if (!empty($stockrooms))
			{
				foreach ($stockrooms as $stockroom)
				{
					$amount = RedshopHelperStockroom::getStockroomAmountDetailList($item['product_id'], "product", $stockroom->stockroom_id);
					$amount = !empty($amount) ? $amount[0]->quantity : 0;

					$item[$stockroom->stockroom_name] = $amount;
				}
			}

			// Media process
			$this->processMedia($item);

			if ($isAttributes)
			{
				$attributeRows = $this->getAttributesData($item);

				// Add empty data for additional header
				foreach ($this->getAttributesHeader() as $header)
				{
					$item[$header] = '';
				}
			}

			$newData[] = $item;

			if (!empty($attributeRows))
			{
				foreach ($attributeRows as $attributeRow)
				{
					$newData[] = $attributeRow;
				}
			}
		}

		$data = $newData;
	}

	/**
	 * Method for process medias of product.
	 *
	 * @param   array $product Product data.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	protected function processMedia(&$product)
	{
		// @TODO: Would implement media check files exist.

		return;
	}

	/**
	 * Method for get query
	 *
	 * @param   array $productData Product data.
	 *
	 * @return  array
	 *
	 * @since  1.0.0
	 */
	protected function getAttributesData($productData)
	{
		$db = $this->db;

		// Attributes query
		$attributeQuery = $db->getQuery(true)
			->select($db->qn('a.attribute_name'))
			->select($db->qn('a.ordering', 'attribute_ordering'))
			->select($db->qn('a.allow_multiple_selection'))
			->select($db->qn('a.hide_attribute_price'))
			->select($db->qn('a.attribute_required'))
			->select($db->qn('a.display_type'))
			->select($db->quote('') . ' AS ' . $db->qn('property_name'))
			->select($db->quote('') . ' AS ' . $db->qn('property_stock'))
			->select($db->quote('') . ' AS ' . $db->qn('property_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('property_virtual_number'))
			->select($db->quote('') . ' AS ' . $db->qn('setdefault_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('setrequire_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('setdisplay_type'))
			->select($db->quote('') . ' AS ' . $db->qn('oprand'))
			->select($db->quote('') . ' AS ' . $db->qn('property_price'))
			->select($db->quote('') . ' AS ' . $db->qn('property_image'))
			->select($db->quote('') . ' AS ' . $db->qn('property_main_image'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_name'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_stock'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_setdefault_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_title'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_virtual_number'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_oprand'))
			->select($db->quote('') . ' AS ' . $db->qn('required_sub_attribute'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_price'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_image'))
			->select($db->quote('0') . ' AS ' . $db->qn('delete'))
			->select($db->quote('') . ' AS ' . $db->qn('media_name'))
			->select($db->quote('') . ' AS ' . $db->qn('media_alternate_text'))
			->select($db->quote('') . ' AS ' . $db->qn('media_section'))
			->select($db->quote('') . ' AS ' . $db->qn('media_published'))
			->select($db->quote('') . ' AS ' . $db->qn('media_ordering'))
			->from($db->qn('#__redshop_product', 'p'))
			->innerJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a.product_id'))
			->where($db->qn('p.product_id') . ' = ' . $productData['product_id']);

		// Properties query
		$propertiesQuery = $db->getQuery(true)
			->select($db->qn('a.attribute_name'))
			->select($db->quote('') . ' AS ' . $db->qn('attribute_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('allow_multiple_selection'))
			->select($db->quote('') . ' AS ' . $db->qn('hide_attribute_price'))
			->select($db->quote('') . ' AS ' . $db->qn('attribute_required'))
			->select($db->quote('') . ' AS ' . $db->qn('display_type'))
			->select($db->qn('ap.property_name'))
			->select(
				'(SELECT GROUP_CONCAT(CONCAT('
				. $db->qn('att_stock.stockroom_id') . ',' . $db->quote(':') . ',' . $db->qn('att_stock.quantity') . ')'
				. ' SEPARATOR ' . $db->quote('#') . ') FROM ' . $db->qn('#__redshop_product_attribute_stockroom_xref', 'att_stock')
				. ' WHERE ' . $db->qn('att_stock.section_id') . ' = ' . $db->qn('ap.property_id')
				. ' AND ' . $db->qn('att_stock.section') . ' = ' . $db->quote('property') . ') AS ' . $db->qn('property_stock')
			)
			->select($db->qn('ap.ordering', 'property_ordering'))
			->select($db->qn('ap.property_number', 'property_virtual_number'))
			->select($db->qn('ap.setdefault_selected'))
			->select($db->qn('ap.setrequire_selected'))
			->select($db->qn('ap.setdisplay_type'))
			->select($db->qn('ap.oprand'))
			->select($db->qn('ap.property_price'))
			->select($db->qn('ap.property_image'))
			->select($db->qn('ap.property_main_image'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_name'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_stock'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_setdefault_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_title'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_virtual_number'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_oprand'))
			->select($db->quote('') . ' AS ' . $db->qn('required_sub_attribute'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_price'))
			->select($db->quote('') . ' AS ' . $db->qn('subattribute_color_image'))
			->select($db->quote('0') . ' AS ' . $db->qn('delete'))
			->select($db->qn('m.media_name') . ' AS ' . $db->qn('media_name'))
			->select($db->qn('m.media_alternate_text') . ' AS ' . $db->qn('media_alternate_text'))
			->select($db->qn('m.media_section') . ' AS ' . $db->qn('media_section'))
			->select($db->qn('m.published') . ' AS ' . $db->qn('media_published'))
			->select($db->qn('m.ordering') . ' AS ' . $db->qn('media_ordering'))
			->from($db->qn('#__redshop_product', 'p'))
			->innerJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a.product_id'))
			->innerJoin(
				$db->qn('#__redshop_product_attribute_property', 'ap') . ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
			)
			->leftJoin(
				$db->qn('#__redshop_media', 'm') . ' ON ' . $db->qn('m.section_id') . ' = ' . $db->qn('ap.property_id')
				. ' AND ' . $db->qn('m.media_section') . ' = ' . $db->q('property')
			)
			->where($db->qn('p.product_id') . ' = ' . $productData['product_id'])
			->order($db->qn('product_number') . ',' . $db->qn('property_ordering'));

		// Sub-properties query
		$subPropertiesQuery = $db->getQuery(true)
			->select($db->qn('a.attribute_name'))
			->select($db->quote('') . ' AS ' . $db->qn('attribute_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('allow_multiple_selection'))
			->select($db->quote('') . ' AS ' . $db->qn('hide_attribute_price'))
			->select($db->quote('') . ' AS ' . $db->qn('attribute_required'))
			->select($db->quote('') . ' AS ' . $db->qn('display_type'))
			->select($db->qn('ap.property_name'))
			->select($db->quote('') . ' AS ' . $db->qn('property_stock'))
			->select($db->quote('') . ' AS ' . $db->qn('property_ordering'))
			->select($db->quote('') . ' AS ' . $db->qn('property_virtual_number'))
			->select($db->quote('') . ' AS ' . $db->qn('setdefault_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('setrequire_selected'))
			->select($db->quote('') . ' AS ' . $db->qn('setdisplay_type'))
			->select($db->quote('') . ' AS ' . $db->qn('oprand'))
			->select($db->quote('') . ' AS ' . $db->qn('property_price'))
			->select($db->quote('') . ' AS ' . $db->qn('property_image'))
			->select($db->quote('') . ' AS ' . $db->qn('property_main_image'))
			->select($db->qn('sp.subattribute_color_name'))
			->select(
				'(SELECT GROUP_CONCAT(CONCAT('
				. $db->qn('stocksp.stockroom_id') . ',' . $db->quote(':') . ',' . $db->qn('stocksp.quantity') . ')'
				. ' SEPARATOR ' . $db->quote('#') . ') FROM ' . $db->qn('#__redshop_product_attribute_stockroom_xref', 'stocksp')
				. ' WHERE ' . $db->qn('stocksp.section_id') . ' = ' . $db->qn('sp.subattribute_color_id')
				. ' AND ' . $db->qn('stocksp.section') . ' = ' . $db->quote('subproperty') . ') AS ' . $db->qn('subattribute_stock')
			)
			->select($db->qn('sp.ordering', 'subattribute_color_ordering'))
			->select($db->qn('sp.setdefault_selected', 'subattribute_setdefault_selected'))
			->select($db->qn('sp.subattribute_color_title'))
			->select($db->qn('sp.subattribute_color_number', 'subattribute_virtual_number'))
			->select($db->qn('sp.oprand', 'subattribute_color_oprand'))
			->select($db->qn('ap.setrequire_selected', 'required_sub_attribute'))
			->select($db->qn('sp.subattribute_color_price'))
			->select($db->qn('sp.subattribute_color_image'))
			->select($db->quote('0') . ' AS ' . $db->qn('delete'))
			->select($db->qn('m1.media_name') . ' AS ' . $db->qn('media_name'))
			->select($db->qn('m1.media_alternate_text') . ' AS ' . $db->qn('media_alternate_text'))
			->select($db->qn('m1.media_section') . ' AS ' . $db->qn('media_section'))
			->select($db->qn('m1.published') . ' AS ' . $db->qn('media_published'))
			->select($db->qn('m1.ordering') . ' AS ' . $db->qn('media_ordering'))
			->from($db->qn('#__redshop_product', 'p'))
			->innerJoin($db->qn('#__redshop_product_attribute', 'a') . ' ON ' . $db->qn('p.product_id') . ' = ' . $db->qn('a.product_id'))
			->innerJoin(
				$db->qn('#__redshop_product_attribute_property', 'ap') . ' ON ' . $db->qn('a.attribute_id') . ' = ' . $db->qn('ap.attribute_id')
			)
			->innerJoin(
				$db->qn('#__redshop_product_subattribute_color', 'sp') . ' ON ' . $db->qn('ap.property_id') . ' = ' . $db->qn('sp.subattribute_id')
			)
			->leftJoin(
				$db->qn('#__redshop_media', 'm1') . ' ON ' . $db->qn('m1.section_id') . ' = ' . $db->qn('sp.subattribute_color_id')
				. ' AND ' . $db->qn('m1.media_section') . ' = ' . $db->q('subproperty')
			)
			->where($db->qn('p.product_id') . ' = ' . $productData['product_id'])
			->order($db->qn('product_number') . ',' . $db->qn('subattribute_color_ordering'));

		$attributeQuery->union($propertiesQuery)->union($subPropertiesQuery);

		$results = $db->setQuery($attributeQuery)->loadObjectList();

		if (empty($results))
		{
			return array();
		}

		// Create new row data.
		$cleanItem  = array();
		$attributes = array();

		foreach ($productData as $key => $value)
		{
			if (in_array($key, array('product_number', 'product_id', 'product_name')))
			{
				$cleanItem[$key] = $value;
			}
			else
			{
				$cleanItem[$key] = '';
			}
		}

		foreach ($results as $result)
		{
			$newItem = $cleanItem;
			$result  = (array) $result;

			$newItem = array_merge($newItem, $result);

			foreach ($newItem as $key => $value)
			{
				// Property image
				if (!empty($newItem['property_image']))
				{
					$newItem['property_image'] = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $newItem['property_image'];
				}

				// Property main image
				if (!empty($newItem['property_main_image']))
				{
					$newItem['property_main_image'] = REDSHOP_FRONT_IMAGES_ABSPATH . 'property/' . $newItem['property_main_image'];
				}

				// Property Media Image
				if (!empty($newItem['media_name']) && ($newItem['media_section'] == 'property'))
				{
					$newItem['media_name'] = REDSHOP_FRONT_IMAGES_ABSPATH . 'property/' . $newItem['media_name'];
				}

				// Sub-attribute image
				if (!empty($newItem['subattribute_color_image']))
				{
					$newItem['subattribute_color_image'] = REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/' . $newItem['subattribute_color_image'];
				}

				// Property Media Image
				if (!empty($newItem['media_name']) && ($newItem['media_section'] == 'subproperty'))
				{
					$newItem['media_name'] = REDSHOP_FRONT_IMAGES_ABSPATH . 'subproperty/' . $newItem['media_name'];
				}
			}

			$attributes[] = $newItem;
		}

		return $attributes;
	}
}
