<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractExportPlugin;
use Joomla\Utilities\ArrayHelper;

JLoader::import('redshop.library');

/**
 * Plugins redSHOP Export Product
 *
 * @since  1.0
 */
class PlgRedshop_ExportProduct extends AbstractExportPlugin
{
	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 *
	 * @TODO   : Need to load XML File instead
	 */
	public function onAjaxProduct_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		// Radio for load extra fields
		$configs[] = '<div class="form-group">
			<label class="col-md-2 control-label">' . JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_EXTRA_FIELDS') . '</label>
			<div class="col-md-10">
				<label class="radio-inline"><input name="product_extrafields" value="1" type="radio" />' . JText::_('JYES') . '</label>
				<label class="radio-inline"><input name="product_extrafields" value="0" type="radio" checked />' . JText::_('JNO') . '</label>
			</div>
		</div>';

		// Prepare categories list.
		$categories = RedshopHelperCategory::getCategoryListArray();
		$options    = array();

		foreach ($categories as $category)
		{
			$options[] = JHtml::_('select.option', $category->category_id, $category->category_name, 'value', 'text');
		}

		$configs[] = '<div class="form-group">
			<label class="col-md-2 control-label">' . JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_CATEGORIES') . '</label>
			<div class="col-md-10">'
			. JHtml::_(
				'select.genericlist', $options, 'product_categories[]',
				'class="form-control" multiple placeholder="' . JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_CATEGORIES_PLACEHOLDER') . '"',
				'value',
				'text'
			) . '</div>
		</div>';

		// Prepare manufacturers list.
		$db            = JFactory::getDbo();
		$query         = $db->getQuery(true)
			->select($db->qn('manufacturer_id', 'value'))
			->select($db->qn('manufacturer_name', 'text'))
			->from($db->qn('#__redshop_manufacturer'));
		$manufacturers = $db->setQuery($query)->loadObjectList();
		$options       = array();

		foreach ($manufacturers as $manufacturer)
		{
			$options[] = JHtml::_('select.option', $manufacturer->value, $manufacturer->text, 'value', 'text');
		}

		$configs[] = '<div class="form-group">
			<label class="col-md-2 control-label">' . JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_MANUFACTURERS') . '</label>
			<div class="col-md-10">'
			. JHtml::_(
				'select.genericlist', $options, 'product_manufacturers[]',
				'class="form-control" multiple placeholder="' . JText::_('PLG_REDSHOP_EXPORT_PRODUCT_CONFIG_MANUFACTURERS_PLACEHOLDER') . '"',
				'value',
				'text'
			) . '
			</div>
		</div>';

		return implode('', $configs);
	}

	/**
	 * Event run when user click on Start Export
	 *
	 * @return  number
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Start()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$headers = $this->getHeader();

		if (!empty($headers))
		{
			$this->writeData($headers, 'w+');
		}

		return (int) $this->getTotal();
	}

	/**
	 * Event run on export process
	 *
	 * @return  int
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Export()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input = JFactory::getApplication()->input;
		$limit = $input->getInt('limit', 0);
		$start = $input->getInt('start', 0);

		return $this->exporting($start, $limit);
	}

	/**
	 * Event run on export process
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function onAjaxProduct_Complete()
	{
		$this->downloadFile();

		JFactory::getApplication()->close();
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
			->select('p.*')
			->select($db->quote(JUri::root()) . ' AS ' . $db->qn('sitepath'))
			->select(
				'(SELECT GROUP_CONCAT(' . $db->qn('pcx.category_id') . ' SEPARATOR ' . $db->quote('###')
				. ') FROM ' . $db->qn('#__redshop_product_category_xref', 'pcx')
				. ' WHERE ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id')
				. ' ORDER BY ' . $db->qn('pcx.category_id') . ') AS ' . $db->qn('category_id')
			)
			->select(
				'(SELECT GROUP_CONCAT(' . $db->qn('c.category_name') . ' SEPARATOR ' . $db->quote('###')
				. ') FROM ' . $db->qn('#__redshop_product_category_xref', 'pcx')
				. ' INNER JOIN ' . $db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('pcx.category_id')
				. ' WHERE ' . $db->qn('p.product_id') . ' = ' . $db->qn('pcx.product_id')
				. ' ORDER BY ' . $db->qn('pcx.category_id') . ') AS ' . $db->qn('category_name')
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
			->group($db->qn('p.product_id'))
			->order($db->qn('p.product_id') . ' asc');

		$medias = array(
			'images'   => array('images', 'images_order', 'images_alternattext'),
			'video'    => array('video', 'video_order', 'video_alternattext'),
			'document' => array('document', 'document_order', 'document_alternattext'),
			'download' => array('download', 'download_order', 'download_alternattext'),
		);

		$mediaColumn = array('m.media_name', 'm.ordering', 'm.media_alternate_text');
		$mediaQuery = $db->getQuery(true);

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
	 * @return array|bool
	 *
	 * @since  1.0.0
	 */
	protected function getHeader()
	{
		// Get main data.
		$headers = parent::getHeader();

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
		$extraFields = (bool) JFactory::getApplication()->input->get('product_extrafields', 0);

		if ($extraFields)
		{
			$db     = $this->db;
			$query  = $db->getQuery(true)
				->select($db->qn('field_name'))
				->from($db->qn('#__redshop_fields'))
				->where($db->qn('field_section') . ' = 1');
			$result = $db->setQuery($query)->loadColumn();

			if (!empty($result))
			{
				$headers = array_merge($headers, $result);
			}
		}

		return $headers;
	}

	/**
	 * Method for do some stuff for data return. (Like image path,...)
	 *
	 * @param   array  &$data  Array of data.
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
		$extraFields = (bool) JFactory::getApplication()->input->get('product_extrafields', 0);
		$fieldsData  = array();

		if ($extraFields)
		{
			$productIds = array_map(
				function($o) {
					return $o->product_id;
				},
				$data
			);

			$db     = $this->db;
			$query  = $db->getQuery(true)
				->select($db->qn(array('d.data_txt', 'd.itemid', 'f.field_name')))
				->from($db->qn('#__redshop_fields', 'f'))
				->leftJoin($db->qn('#__redshop_fields_data', 'd') . ' ON ' . $db->qn('f.field_id') . ' = ' . $db->qn('d.fieldid'))
				->where($db->qn('f.field_section') . ' = 1')
				->where($db->qn('d.itemid') . ' IN (' . implode(',', $productIds) . ')')
				->order($db->qn('f.field_id') . ' ASC');
			$fieldsData = $db->setQuery($query)->loadObjectList('itemid');
		}

		foreach ($data as $index => $item)
		{
			$item = (array) $item;

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
				}

				// Discount start date
				if (!empty($item['discount_stratdate']))
				{
					$item['discount_stratdate'] = RedshopHelperDatetime::convertDateFormat($item['discount_stratdate']);
				}

				// Discount end date
				if (!empty($item['discount_enddate']))
				{
					$item['discount_enddate'] = RedshopHelperDatetime::convertDateFormat($item['discount_enddate']);
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

				// Extra fields process
				if (!$extraFields)
				{
					continue;
				}

				if (isset($fieldsData[$item['product_id']]))
				{
					$itemField = $fieldsData[$item['product_id']];

					$item[$itemField->field_name] = $itemField->data_txt;
				}
			}

			$data[$index] = $item;
		}
	}

	/**
	 * Method for process medias of product.
	 *
	 * @param   array  &$product  Product data.
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
}
