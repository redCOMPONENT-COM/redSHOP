<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Model Export
 *
 * @since  2.5
 */
class RedshopModelExport extends RedshopModel
{
	/**
	 * Contains product extrafield data
	 *
	 * @var  array
	 */
	public static $productExtraFieldsData = array();

	/**
	 * Product Extrafields Name
	 *
	 * @var  array
	 */
	public static $productExtraFields = array();

	/**
	 * Get export data
	 *
	 * @return  void
	 */
	public function getData()
	{
		$app = JFactory::getApplication();

		$exportname = JRequest::getVar('export');

		if (!$exportname)
		{
			$app->redirect("index.php?option=com_redshop&view=export", JText::_("COM_REDSHOP_PLEASE_SELECT_SECTION"));
		}

		/* Set the export filename */
		$exportfilename = 'redshop_' . $exportname . '.csv';

		/* Start output to the browser */
		if (preg_match('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
		{
			$UserBrowser = "Opera";
		}
		elseif (preg_match('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
		{
			$UserBrowser = "IE";
		}
		else
		{
			$UserBrowser = '';
		}

		$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

		/* Clean the buffer */
		ob_clean();

		header('Content-Type: ' . $mime_type);
		header('Content-Encoding: UTF-8');
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		if ($UserBrowser == 'IE')
		{
			header('Content-Disposition: inline; filename="' . $exportfilename . '"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		}
		else
		{
			header('Content-Disposition: attachment; filename="' . $exportfilename . '"');
			header('Pragma: no-cache');
		}

		/* Load the data */
		$export = JRequest::getVar('export');

		switch ($export)
		{
			case 'products':
				$this->loadProducts();
				break;
			case 'categories':
				$this->loadCategories();
				break;
			case 'attributes':
				$this->loadAttributes();
				break;
			case 'related_product':
				$this->loadRelatedProducts();
				break;
			case 'fields':
				$this->loadFields();
				break;
			case 'users':
				$this->loadUsers();
				break;
			case 'shipping_address':
				$this->loadshippingaddress();
				break;
			case 'shopperGroupProductPrice':
				$this->loadShopperGroupProductPrice();
				break;
			case 'shopperGroupAttributePrice':
				$this->loadShopperGroupAttributePrice();
				break;
			case 'manufacturer':
				$this->loadManufacturer();
				break;
		}

		/* Finalize */
		exit;
	}

	/**
	 * Load the products for export
	 *
	 * @return  void
	 */
	private function loadProducts()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('p.*')
			->from($db->qn('#__redshop_product', 'p'))
			->leftJoin($db->qn('#__redshop_product_category_xref', 'pc') . ' ON p.product_id = pc.product_id')
			->group('p.product_id')
			->order('p.product_id asc');
		$jInput = JFactory::getApplication()->input;

		if (count($product_category = $jInput->get('product_category', array(), 'array')) > 0)
		{
			JArrayHelper::toInteger($product_category);
			$query->where('pc.category_id IN (' . implode(',', $product_category) . ')');
		}

		if (count($manufacturer_id = $jInput->get('manufacturer_id', array(), 'array')) > 0)
		{
			JArrayHelper::toInteger($manufacturer_id);
			$query->where('p.manufacturer_id IN (' . implode(',', $manufacturer_id) . ')');
		}

		if (!($cur = $db->setQuery($query)->loadObjectList()))
		{
			return null;
		}

		$headers = array_keys((array) $cur[0]);
		$headers = array_merge(
			$headers,
			array('sitepath','category_id','category_name','accessory_products',
				'images','images_order','images_alternattext','video','video_order','video_alternattext',
				'document','document_order','document_alternattext','download','download_order',
				'download_alternattext')
		);

		// Product stockroom list
		$stockrooms = RedshopHelperStockroom::getStockroom();

		if (count($stockrooms) > 0)
		{
			foreach ($stockrooms as $stockroom)
			{
				$headers[] = $stockroom->stockroom_name;
			}
		}

		// Product extra fields data
		if ($exportProductExtraField = $jInput->getInt('export_product_extra_field', 0))
		{
			// Load all product extrafield information
			self::getProductExtraFields();

			$fieldHeader = array_keys(self::$productExtraFields);

			// Append Extra Fields name in header
			$headers = array_merge(
				$headers,
				$fieldHeader
			);
		}

		$export = array();

		foreach ($cur as $oneProduct)
		{
			$oneProduct->product_full_image = $this->checkFileExists($oneProduct->product_full_image);
			$oneProduct->product_thumb_image = $this->checkFileExists($oneProduct->product_thumb_image);
			$oneProduct->product_back_full_image = $this->checkFileExists($oneProduct->product_back_full_image);
			$oneProduct->product_back_thumb_image = $this->checkFileExists($oneProduct->product_back_thumb_image);
			$oneProduct->product_preview_image = $this->checkFileExists($oneProduct->product_preview_image);
			$oneProduct->product_preview_back_image = $this->checkFileExists($oneProduct->product_preview_back_image);
			$oneProduct->sitepath = JURI::root();

			$query->clear()
				->select('pcx.category_id, c.category_name')
				->from($db->qn('#__redshop_product_category_xref', 'pcx'))
				->leftJoin($db->qn('#__redshop_category', 'c') . ' ON c.category_id = pcx.category_id')
				->where('product_id = ' . (int) $oneProduct->product_id);

			if ($categories = $db->setQuery($query)->loadObjectList())
			{
				$cats = array();
				$cat_name = array();

				foreach ($categories as $category)
				{
					$cats[] = $category->category_id;
					$cat_name[] = $category->category_name;
				}

				$oneProduct->category_id = implode("###", $cats);
				$oneProduct->category_name = implode("###", $cat_name);
			}

			$query->clear()
				->select('CONCAT(p.product_number,' . $db->q('~') . ',pa.accessory_price) as accsdata')
				->from($db->qn('#__redshop_product_accessory', 'pa'))
				->leftJoin($db->qn('#__redshop_product', 'p') . ' ON p.product_id = pa.child_product_id')
				->where('pa.product_id = ' . (int) $oneProduct->product_id);

			if ($accessories = $db->setQuery($query)->loadObjectList())
			{
				$accs = array();

				foreach ($accessories as $accessory)
				{
					$accs[] = $accessory->accsdata;
				}

				$oneProduct->accessory_products = implode("###", $accs);
			}

			if (count($stockrooms) > 0)
			{
				foreach ($stockrooms as $stockroom)
				{
					$stockroomAmount = RedshopHelperStockroom::getStockroomAmountDetailList($oneProduct->product_id, "product", $stockroom->stockroom_id);
					$oneProduct->{$stockroom->stockroom_name} = $stockroomAmount[0]->quantity;
				}
			}

			$query->clear()
				->select('*')
				->from($db->qn('#__redshop_media'))
				->where('media_section = ' . $db->q('product'))
				->where('section_id = ' . (int) $oneProduct->product_id)
				->order('ordering ASC');

			if ($medias = $db->setQuery($query)->loadObjectList())
			{
				$imageArr = array(
					'name' => array(),
					'ordering' => array(),
					'alternate_text' => array()
				);
				$videoArr = array(
					'name' => array(),
					'ordering' => array(),
					'alternate_text' => array()
				);
				$documentArr = array(
					'name' => array(),
					'ordering' => array(),
					'alternate_text' => array()
				);
				$downloadArr = array(
					'name' => array(),
					'ordering' => array(),
					'alternate_text' => array()
				);

				foreach ($medias as $media)
				{
					switch ($media->media_type)
					{
						case 'images':
							if ($this->checkFileExists($media->media_name))
							{
								$imageArr['name'][] = $media->media_name;
								$imageArr['ordering'][] = $media->ordering;
								$imageArr['alternate_text'][] = $media->media_alternate_text;
							}
							break;
						case 'video':
							if ($this->checkFileExists($media->media_name, JPATH_ROOT . '/components/com_redshop/assets/video/product/'))
							{
								$videoArr['name'][] = $media->media_name;
								$videoArr['ordering'][] = $media->ordering;
								$videoArr['alternate_text'][] = $media->media_alternate_text;
							}
							break;
						case 'document':
							if ($this->checkFileExists($media->media_name, REDSHOP_FRONT_DOCUMENT_RELPATH))
							{
								$documentArr['name'][] = $media->media_name;
								$documentArr['ordering'][] = $media->ordering;
								$documentArr['alternate_text'][] = $media->media_alternate_text;
							}
							break;
						case 'download':
							if ($this->checkFileExists($media->media_name, Redshop::getConfig()->get('PRODUCT_DOWNLOAD_ROOT')))
							{
								$downloadArr['name'][] = $media->media_name;
								$downloadArr['ordering'][] = $media->ordering;
								$downloadArr['alternate_text'][] = $media->media_alternate_text;
							}
							break;
					}
				}

				$oneProduct->images = implode("#", $imageArr['name']);
				$oneProduct->images_order = implode("#", $imageArr['ordering']);
				$oneProduct->images_alternattext = implode("#", $imageArr['alternate_text']);
				$oneProduct->video = implode("#", $videoArr['name']);
				$oneProduct->video_order = implode("#", $videoArr['ordering']);
				$oneProduct->video_alternattext = implode("#", $videoArr['alternate_text']);
				$oneProduct->document = implode("#", $documentArr['name']);
				$oneProduct->document_order = implode("#", $documentArr['ordering']);
				$oneProduct->document_alternattext = implode("#", $documentArr['alternate_text']);
				$oneProduct->download = implode("#", $downloadArr['name']);
				$oneProduct->download_order = implode("#", $downloadArr['ordering']);
				$oneProduct->download_alternattext = implode("#", $downloadArr['alternate_text']);
			}

			if ($exportProductExtraField)
			{
				foreach ($fieldHeader as $extraFieldName)
				{
					$oneProduct->$extraFieldName = '';

					if (isset(self::$productExtraFieldsData[(int) $oneProduct->product_id][$extraFieldName]))
					{
						$oneProduct->$extraFieldName = self::$productExtraFieldsData[(int) $oneProduct->product_id][$extraFieldName]->data_txt;
					}
				}
			}

			$export[] = (array) $oneProduct;
		}

		$this->displayCsvData($export, $headers);
	}

	/**
	 * Load the categories for export
	 *
	 * @return  void
	 */
	/*private function loadCategories()
	{
		$db = JFactory::getDbo();
		$q = "SELECT c.*,cx.category_parent_id
			FROM #__redshop_category c LEFT JOIN #__redshop_category_xref cx ON c.category_id = cx.category_child_id WHERE cx.category_parent_id IS NOT NULL ORDER BY c.category_id";
		$db->setQuery($q);

		if (!($cur = $db->LoadObjectList()))
		{
			return null;
		}

		$i = 0;

		if (count($cur) > 0)
		{
			for ($c = 0, $cn = count($cur); $c < $cn; $c++)
			{
				$row = $cur[$c];
				$row = (array) $row;
				$fields = count($row);

				if ($i == 0)
				{
					foreach ($row as $id => $value)
					{
						echo '"' . str_replace('"', '""', $id) . '"';

						if ($i < ($fields - 1))
						{
							echo ',';
						}

						$i++;
					}

					echo "\r\n";
				}

				$i = 0;

				foreach ($row as $id => $value)
				{
					if ($id == 'category_full_image' && $value != "")
					{
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $value))
						{
							$value = REDSHOP_FRONT_IMAGES_ABSPATH . 'category/' . $value;
						}
						else
						{
							$value = "";
						}
					}

					$value = str_replace("\n", "", $value);

					$value = str_replace("\r", "", $value);

					echo '"' . str_replace('"', '""', $value) . '"';

					if ($i < ($fields - 1))
					{
						echo ',';
					}

					$i++;
				}

				echo "\r\n";
			}

			if (is_resource($cur))
			{
				mysql_free_result($cur);
			}
		}
	}*/

	/**
	 * Load the attributes for export
	 *
	 * @return  void
	 */
	private function loadAttributes()
	{
		$producthelper = productHelper::getInstance();

		$db = JFactory::getDbo();
		$query = "SELECT * FROM `#__redshop_product` ORDER BY product_id asc ";
		$db->setQuery($query);
		$cur = $db->loadObjectList();

		if (count($cur) > 0)
		{
			$redhelper = redhelper::getInstance();

			for ($i = 0, $in = count($cur); $i < $in; $i++)
			{
				if ($i == 0)
				{
					echo '"product_number","attribute_name","attribute_ordering","allow_multiple_selection","hide_attribute_price","attribute_required","display_type","property_name","property_stock"';
					echo ',"property_ordering","property_virtual_number","setdefault_selected","setdisplay_type","oprand","property_price","property_image","property_main_image","subattribute_color_name","subattribute_stock"';
					echo ',"subattribute_color_ordering","subattribute_setdefault_selected","subattribute_color_title","subattribute_virtual_number","subattribute_color_oprand","required_sub_attribute","subattribute_color_price","subattribute_color_image","delete"';

					echo "\r\n";
				}

				// Added attribute of products
				$attribute = $producthelper->getProductAttribute($cur[$i]->product_id);

				for ($att = 0; $att < count($attribute); $att++)
				{
					if ($attribute[$att]->attribute_name != "")
					{
						echo '"' . $cur[$i]->product_number . '","' . $attribute[$att]->attribute_name . '","' . $attribute[$att]->ordering . '","' . $attribute[$att]->allow_multiple_selection . '","' . $attribute[$att]->hide_attribute_price . '","' . $attribute[$att]->attribute_required . '","' . $attribute[$att]->display_type . '"';
						echo ',,,,,,,,,,,,,,,,"0"';
						echo "\r\n";
						$att_property = $producthelper->getAttibuteProperty(0, $attribute[$att]->attribute_id);

						for ($prop = 0; $prop < count($att_property); $prop++)
						{
							$property_image = "";
							$property_main_image = "";
							$main_attribute_stock = "";

							$sel_arrtibute_stock = "select * from `#__redshop_product_attribute_stockroom_xref` where section_id='" . $att_property[$prop]->property_id . "'";
							$db->setQuery($sel_arrtibute_stock);
							$fetch_arrtibute_stock = $db->loadObjectList();

							for ($h = 0, $hn = count($fetch_arrtibute_stock); $h < $hn; $h++)
							{
								$main_attribute_stock .= $fetch_arrtibute_stock[$h]->stockroom_id . ":" . $fetch_arrtibute_stock[$h]->quantity . "#";
							}

							if ($att_property[$prop]->property_image != "")
							{
								$property_image = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_attributes/' . $att_property[$prop]->property_image;
							}

							if ($att_property[$prop]->property_main_image != "")
							{
								$property_main_image = REDSHOP_FRONT_IMAGES_ABSPATH . 'property/' . $att_property[$prop]->property_main_image;
							}

							echo '"' . $cur[$i]->product_number . '","' . $attribute[$att]->attribute_name . '",,,,,,"' . $att_property[$prop]->property_name . '","' . $main_attribute_stock . '"';

							echo ',"' . $att_property[$prop]->ordering . '","' . $att_property[$prop]->property_number . '","'
								. $att_property[$prop]->setdefault_selected . '","' . $att_property[$prop]->setdisplay_type . '","'
								. $att_property[$prop]->oprand . '","' . $att_property[$prop]->property_price . '","' . $property_image
								. '","' . $property_main_image . '"';
							echo ',,,,,,,,,"0"';
							echo "\n";

							$subatt_property = $producthelper->getAttibuteSubProperty(0, $att_property[$prop]->property_id);

							for ($subprop = 0; $subprop < count($subatt_property); $subprop++)
							{
								$subattribute_color_image = "";
								$main_attribute_stock_sub = "";

								$sel_arrtibute_stock_sub = "select * from `#__redshop_product_attribute_stockroom_xref` where section_id='" . $subatt_property[$subprop]->subattribute_color_id
									. "'";
								$db->setQuery($sel_arrtibute_stock_sub);
								$fetch_arrtibute_stock_sub = $db->loadObjectList();

								for ($b = 0, $bn = count($fetch_arrtibute_stock_sub); $b < $bn; $b++)
								{
									$main_attribute_stock_sub .= $fetch_arrtibute_stock_sub[$b]->stockroom_id . ":" . $fetch_arrtibute_stock_sub[$b]->quantity . "#";
								}

								if ($subatt_property[$subprop]->subattribute_color_image != "")
								{
									$subattribute_color_image = REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/'
										. $subatt_property[$subprop]->subattribute_color_image;
								}

								echo '"' . $cur[$i]->product_number . '","' . $attribute[$att]->attribute_name . '",,,,,,"' . $att_property[$prop]->property_name . '"';
								echo ',,,,,,,,,,"' . $subatt_property[$subprop]->subattribute_color_name . '","' . $main_attribute_stock_sub . '"';

								echo ',"' . $subatt_property[$subprop]->ordering . '","' . $subatt_property[$subprop]->setdefault_selected
									. '","' . $subatt_property[$subprop]->subattribute_color_title . '","'
									. $subatt_property[$subprop]->subattribute_color_number . '","' . $subatt_property[$subprop]->oprand
									. '","' . $att_property[$prop]->setrequire_selected . '","' . $subatt_property[$subprop]->subattribute_color_price
									. '","' . $subattribute_color_image . '","0"';
								echo "\n";
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Load the manufacturer for export
	 *
	 * @return  void
	 */
	/*private function loadManufacturer()
	{
		$db = JFactory::getDbo();
		$query = "SELECT m.* "
			. "FROM `#__redshop_manufacturer` AS m ";
		$db->setQuery($query);

		if (!($manufacturers = $db->LoadObjectList()))
		{
			return null;
		}

		$i = 0;

		if (count($manufacturers) > 0)
		{
			for ($e = 0, $en = count($manufacturers); $e < $en; $e++)
			{
				$row = $manufacturers[$e];
				$row = (array) $row;
				$fields = count($row);

				if ($i == 0)
				{
					foreach ($row as $id => $value)
					{
						echo $this->_text_qul . str_replace('"', '""', $id) . $this->_text_qul;

						if ($i < ($fields - 1))
						{
							echo ',';
						}

						$i++;
					}

					echo ',' . $this->_text_qul . 'product_id' . $this->_text_qul;
					echo "\r\n";
				}

				$i = 0;
				$query = "SELECT p.product_id "
					. "FROM `#__redshop_product` AS p "
					. "WHERE p.manufacturer_id=" . $manufacturers[$e]->manufacturer_id;
				$db->setQuery($query);
				$pids = $db->loadColumn();
				$pids = implode("|", $pids);

				foreach ($row as $id => $value)
				{
					echo $this->_text_qul . str_replace('"', '""', $value) . $this->_text_qul;

					if ($i < ($fields - 1))
					{
						echo ',';
					}

					$i++;
				}

				echo ',' . $this->_text_qul . $pids . $this->_text_qul;
				echo "\r\n";
			}
		}
	}*/

	/**
	 * Load the Related Products for export
	 *
	 * @return  void
	 */
	private function loadRelatedProducts()
	{
		$db = JFactory::getDbo();
		$relsku = "SELECT `product_number` FROM `#__redshop_product` WHERE `product_id` = pr.`related_id`";
		$mainsku = "SELECT `product_number` FROM `#__redshop_product` WHERE `product_id` = pr.`product_id`";

		$q = "SELECT (" . $relsku . ") as related_sku,(" . $mainsku . ") as product_sku FROM `#__redshop_product_related` as pr WHERE (" . $relsku . ") IS NOT NULL AND (" . $mainsku . ") IS NOT NULL ";
		$db->setQuery($q);

		if (!($cur = $db->LoadObjectList()))
		{
			return null;
		}

		$i = 0;

		if (count($cur) > 0)
		{
			for ($r = 0, $rn = count($cur); $r < $rn; $r++)
			{
				$row = $cur[$r];
				$row = (array) $row;
				$fields = count($row);

				if ($i == 0)
				{
					foreach ($row as $id => $value)
					{
						echo '"' . str_replace('"', '""', $id) . '"';

						if ($i < ($fields - 1))
						{
							echo ',';
						}

						$i++;
					}

					echo "\r\n";
				}

				$i = 0;

				foreach ($row as $id => $value)
				{
					echo '"' . str_replace('"', '""', $value) . '"';

					if ($i < ($fields - 1))
					{
						echo ',';
					}

					$i++;
				}

				echo "\r\n";
			}

			if (is_resource($cur))
			{
				mysql_free_result($cur);
			}
		}
	}

	/**
	 * Load the fields for export
	 *
	 * @return  void
	 */
	private function loadFields()
	{
		$extra_field   = extra_field::getInstance();
		$producthelper = productHelper::getInstance();
		$db            = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_fields'))
			->order('field_id asc');
		$db->setQuery($query);
		$export = array();
		$headers = array('field_id','field_title','field_name_field','field_type','field_desc','field_class','field_section','field_maxlength',
			'field_cols','field_rows','field_size','field_show_in_front','required','published','data_id','data_txt','itemid',
			'section','value_id','field_value','field_name','data_number');

		if ($cur = $db->loadObjectList())
		{
			foreach ($cur as $field)
			{
				$row = array();
				$row['field_id'] = $field->field_id;
				$row['field_title'] = $field->field_title;
				$row['field_name_field'] = $field->field_name;
				$row['field_type'] = $field->field_type;
				$row['field_desc'] = $field->field_desc;
				$row['field_class'] = $field->field_class;
				$row['field_section'] = $field->field_section;
				$row['field_maxlength'] = $field->field_maxlength;
				$row['field_cols'] = $field->field_cols;
				$row['field_rows'] = $field->field_rows;
				$row['field_size'] = $field->field_size;
				$row['field_show_in_front'] = $field->field_show_in_front;
				$row['required'] = $field->required;
				$row['published'] = $field->published;
				$export[] = $row;

				$query->clear()
					->select('data_id, data_txt, itemid, section')
					->from($db->qn('#__redshop_fields_data'))
					->where('fieldid = ' . $db->q($field->field_id))
					->where('section != ' . $db->q(''));
				$db->setQuery($query);

				if ($data = $db->loadObjectList())
				{
					foreach ($data as $oneData)
					{
						$productNumber = '';

						if ($product_details = $producthelper->getProductById($oneData->itemid))
						{
							$productNumber = $product_details->product_number;
						}

						$row = array();
						$row['field_id'] = $field->field_id;
						$row['field_section'] = $field->field_section;
						$row['field_name_field'] = $field->field_name;
						$row['data_id'] = $oneData->data_id;
						$row['data_txt'] = $oneData->data_txt;
						$row['itemid'] = $oneData->itemid;
						$row['section'] = $oneData->section;
						$row['data_number'] = $productNumber;
						$export[] = $row;
					}
				}

				if ($dataValue = $extra_field->getFieldValue($field->field_id))
				{
					foreach ($dataValue as $oneDataValue)
					{
						$row = array();
						$row['field_id'] = $field->field_id;
						$row['field_section'] = $field->field_section;
						$row['field_name_field'] = $field->field_name;
						$row['value_id'] = $oneDataValue->value_id;
						$row['field_value'] = $oneDataValue->field_value;
						$row['field_name'] = $oneDataValue->field_name;
						$export[] = $row;
					}
				}
			}
		}

		$this->displayCsvData($export, $headers);
	}

	/**
	 * Check File Exists
	 *
	 * @param   string  $fileName     File Name
	 * @param   string  $fileRelPath  Relate file pah
	 *
	 * @return  string
	 */
	public function checkFileExists($fileName, $fileRelPath = '')
	{
		if ($fileRelPath == '')
		{
			$fileRelPath = REDSHOP_FRONT_IMAGES_RELPATH . 'product/';
		}

		if (!is_file($fileRelPath . $fileName))
		{
			$fileName = '';
		}

		return $fileName;
	}

	/**
	 * Display Csv Data
	 *
	 * @param   array  $export   Export rows
	 * @param   array  $headers  Export headers
	 *
	 * @return  void
	 */
	public function displayCsvData($export, $headers)
	{
		if (count($export))
		{
			echo '"' . implode('","', $headers) . "\"\r\n";

			foreach ($export as $oneRow)
			{
				foreach ($headers as $oneHeader)
				{
					if (isset($oneRow[$oneHeader]))
					{
						$value = str_replace("\n", '', $oneRow[$oneHeader]);
						$value = str_replace("\r", '', $value);
						echo '"' . str_replace('"', "'", $value) . '"';
					}

					echo ',';
				}

				echo "\n";
			}
		}
	}

	/**
	 * Load the users for export
	 *
	 * @return  void
	 */
	/*private function loadUsers()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select(
				array(
					$db->qn('ui.users_info_id'),
					$db->qn('sg.shopper_group_name'),
					'IFNULL( u.id,ui.user_id) as id',
					'IFNULL( u.email,ui.user_email) as email',
					$db->qn('u.username'),
					$db->qn('u.name'),
					'"" as password',
					'"" as usertype',
					$db->qn('u.block'),
					$db->qn('u.sendEmail'),
					$db->qn('ui.company_name'),
					$db->qn('ui.firstname'),
					$db->qn('ui.lastname'),
					$db->qn('ui.vat_number'),
					$db->qn('ui.tax_exempt'),
					$db->qn('ui.shopper_group_id'),
					$db->qn('ui.country_code'),
					$db->qn('ui.address'),
					$db->qn('ui.city'),
					$db->qn('ui.state_code'),
					$db->qn('ui.zipcode'),
					$db->qn('ui.tax_exempt_approved'),
					$db->qn('ui.approved'),
					$db->qn('ui.is_company'),
					$db->qn('ui.phone')
				)
			)
			->from($db->qn('#__redshop_users_info', 'ui'))
			->where($db->qn('ui.address_type') . ' = ' . $db->q('BT'))
			->leftjoin(
				$db->qn('#__users', 'u') . ' ON ' . $db->qn('u.id') . ' = ' . $db->qn('ui.user_id')
			)
			->leftjoin(
				$db->qn('#__redshop_shopper_group', 'sg') . ' ON ' . $db->qn('sg.shopper_group_id') . ' = ' . $db->qn('ui.shopper_group_id')
			);

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$usersInfo = $db->loadObjectList('id');
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		$groupIds = $this->getJGroupIds(array_keys($usersInfo));

		if (count($usersInfo) > 0)
		{
			// Start the ouput
			$output = fopen('php://output', 'w');

			$i = 0;

			$headings = array();

			// Then loop through the rows
			foreach ($usersInfo as $userId => $user)
			{
				$user = (array) $user;
				$user['usertype'] = implode(',', $groupIds[$userId]);

				if ($i == 0)
				{
					$headings = array_keys($user);

					// Create the headers
					fputcsv($output, $headings, ',', '"');
				}

				// Add the rows to the body
				fputcsv($output, $user);

				$i++;
			}

			JFactory::getApplication()->close();
		}
	}*/

	/**
	 * Get Joomla User and Group relation
	 *
	 * @param   array  $userIds  Joomla Userids
	 *
	 * @return  array  UserId index array for group ids
	 */
	/*private function getJGroupIds($userIds)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__user_usergroup_map'))
			->where($db->qn('user_id') . ' IN (' . implode(',', $userIds) . ')');

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$result   = $db->loadObjectList();
			$groupIds = array();

			// Arrange groupids in user id collection
			foreach ($result as $value)
			{
				$groupIds[$value->user_id][] = $value->group_id;
			}
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $groupIds;
	}*/

	/**
	 * Load the Shipping Address for export
	 *
	 * @return  void
	 */
	/*private function loadshippingaddress()
	{
		$db = JFactory::getDbo();
		$query = "SELECT  IFNULL( u.email, ui.user_email ) as email , u.username, ui.company_name, ui.firstname,
		ui.lastname, ui.address, ui.city, ui.state_code, ui.zipcode, ui.country_code, ui.phone
			FROM (
			`#__redshop_users_info` AS ui
			LEFT JOIN #__users AS u ON u.id = ui.user_id)WHERE ui.`address_type` = 'ST'";
		$db->setQuery($query);

		if (!($cur = $db->LoadObjectList()))
		{
			return null;
		}

		$i = 0;

		if (count($cur) > 0)
		{
			for ($s = 0, $sn = count($cur); $s < $sn; $s++)
			{
				$row = $cur[$s];
				$row = (array) $row;
				$fields = count($row);

				if ($i == 0)
				{
					foreach ($row as $id => $value)
					{
						echo '"' . str_replace('"', '""', $id) . '"';

						if ($i < ($fields - 1))
						{
							echo ',';
						}

						$i++;
					}

					echo "\r\n";
				}

				$i = 0;

				foreach ($row as $id => $value)
				{
					echo '"' . str_replace('"', '""', $value) . '"';

					if ($i < ($fields - 1))
					{
						echo ',';
					}

					$i++;
				}

				echo "\r\n";
			}

			if (is_resource($cur))
			{
				mysql_free_result($cur);
			}
		}
	}*/

	/**
	 * Export Shopper Group based price for Product
	 *
	 * @return  void
	 */
	public function loadShopperGroupProductPrice()
	{
		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select(
			array(
					$db->qn('p.product_number'),
					$db->qn('p.product_name'),
					$db->qn('pp.product_price'),
					$db->qn('price_quantity_start'),
					$db->qn('price_quantity_end'),
					$db->qn('pp.discount_price'),
					$db->qn('pp.discount_start_date'),
					$db->qn('pp.discount_end_date'),
					$db->qn('s.shopper_group_id'),
					$db->qn('s.shopper_group_name')
				)
			)
			->from($db->qn('#__redshop_product_price', 'pp'))
			->leftjoin(
				$db->qn('#__redshop_product', 'p')
				. ' ON ' . $db->qn('p.product_id') . '=' . $db->qn('pp.product_id')
			)
			->leftjoin(
				$db->qn('#__redshop_shopper_group', 's')
				. ' ON ' . $db->qn('s.shopper_group_id') . '=' . $db->qn('pp.shopper_group_id')
			)
			->where($db->qn('p.product_number') . '!= ""');

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$product = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		$i = 0;

		if (count($product) > 0)
		{
			for ($e = 0, $en = count($product); $e < $en; $e++)
			{
				$row = $product[$e];
				$row = (array) $row;
				$fields = count($row);

				if ($i == 0)
				{
					foreach ($row as $id => $value)
					{
						echo '"' . str_replace('"', '""', $id) . '"';

						if ($i < ($fields - 1))
						{
							echo ',';
						}

						$i++;
					}

					echo "\r\n";
				}

				$i = 0;

				foreach ($row as $id => $value)
				{
					echo '"' . str_replace('"', '""', $value) . '"';

					if ($i < ($fields - 1))
					{
						echo ',';
					}

					$i++;
				}

				echo "\r\n";
			}
		}
	}

	/**
	 * Load the shopper group for product
	 *
	 * @return  void
	 */
	public function loadShopperGroupAttributePrice()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select(
				array(
					$db->qn('ap.section'),
					$db->qn('product.product_number'),
					$db->qn('product.product_name'),
					$db->qn('product.product_price'),
					$db->qn('p.property_number', 'attribute_number'),
					$db->qn('p.property_name', 'product_attribute'),
					$db->qn('ap.product_price', 'attribute_price'),
					$db->qn('ap.price_quantity_start'),
					$db->qn('ap.price_quantity_end'),
					$db->qn('ap.discount_price'),
					$db->qn('ap.discount_start_date'),
					$db->qn('ap.discount_end_date'),
					$db->qn('s.shopper_group_id'),
					$db->qn('s.shopper_group_name')
				)
			)
			->from($db->qn('#__redshop_product_attribute_price', 'ap'))
			->leftjoin(
				$db->qn('#__redshop_product_attribute_property', 'p')
				. ' ON ' . $db->qn('p.property_id') . '=' . $db->qn('ap.section_id')
			)
			->leftjoin(
				$db->qn('#__redshop_shopper_group', 's')
				. ' ON ' . $db->qn('s.shopper_group_id') . '=' . $db->qn('ap.shopper_group_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product_attribute', 'pa')
				. ' ON ' . $db->qn('pa.attribute_id') . '=' . $db->qn('p.attribute_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product', 'product')
				. ' ON ' . $db->qn('product.product_id') . '=' . $db->qn('pa.product_id')
			)
			->where($db->qn('ap.section') . '="property"');

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$properties = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		// Sub attribute query
		$query = $db->getQuery(true)
			->select(
				array(
					$db->qn('ap.section'),
					$db->qn('product.product_number'),
					$db->qn('product.product_name'),
					$db->qn('product.product_price'),
					$db->qn('sp.subattribute_color_number', 'attribute_number'),
					$db->qn('sp.subattribute_color_name', 'product_attribute'),
					$db->qn('ap.product_price', 'attribute_price'),
					$db->qn('ap.price_quantity_start'),
					$db->qn('ap.price_quantity_end'),
					$db->qn('ap.discount_price'),
					$db->qn('ap.discount_start_date'),
					$db->qn('ap.discount_end_date'),
					$db->qn('s.shopper_group_id'),
					$db->qn('s.shopper_group_name')
				)
			)
			->from($db->qn('#__redshop_product_attribute_price', 'ap'))
			->leftjoin(
				$db->qn('#__redshop_product_subattribute_color', 'sp')
				. ' ON ' . $db->qn('sp.subattribute_color_id') . '=' . $db->qn('ap.section_id')
			)
			->leftjoin(
				$db->qn('#__redshop_shopper_group', 's')
				. ' ON ' . $db->qn('s.shopper_group_id') . '=' . $db->qn('ap.shopper_group_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product_attribute_property', 'p')
				. ' ON ' . $db->qn('sp.subattribute_id') . '=' . $db->qn('p.property_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product_attribute', 'pa')
				. ' ON ' . $db->qn('pa.attribute_id') . '=' . $db->qn('p.attribute_id')
			)
			->leftjoin(
				$db->qn('#__redshop_product', 'product')
				. ' ON ' . $db->qn('product.product_id') . '=' . $db->qn('pa.product_id')
			)
			->where($db->qn('ap.section') . '="subproperty"');

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$subProperties = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		// Merge Property and SubProperty data
		$attributes = array_merge($properties, $subProperties);

		$i = 0;

		if (count($attributes) > 0)
		{
			for ($f = 0, $fn = count($attributes); $f < $fn; $f++)
			{
				$row    = $attributes[$f];
				$row    = (array) $row;
				$fields = count($row);

				if ($i == 0)
				{
					foreach ($row as $id => $value)
					{
						echo '"' . str_replace('"', '""', $id) . '"';

						if ($i < ($fields - 1))
						{
							echo ',';
						}

						$i++;
					}

					echo "\r\n";
				}

				$i = 0;

				// Get product number from array
				$isProductNumber   = (int) $row['product_number'];
				$isAttributeNumber = (int) $row['attribute_number'];

				foreach ($row as $id => $value)
				{
					// Only allow attribute which has product number and attribute number
					if ($isProductNumber && $isAttributeNumber)
					{
						echo '"' . str_replace('"', '""', $value) . '"';

						if ($i < ($fields - 1))
						{
							echo ',';
						}
					}

					$i++;
				}

				// Only allow add new line when it has product number
				if ($isProductNumber)
				{
					echo "\r\n";
				}
			}
		}
	}

	/**
	 * Get Manufacturers to export
	 *
	 * @return  array  List of all manufacturers
	 */
	public function getmanufacturers()
	{
		$db = JFactory::getDbo();

		$query = 'SELECT manufacturer_id as value,manufacturer_name as text FROM #__redshop_manufacturer  WHERE published=1 ORDER BY `manufacturer_name`';
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	/**
	 * Get product extrafields data
	 *
	 * @return  object  Extrafields info and product data
	 */
	public static function getProductExtraFields()
	{
		if (empty(self::$productExtraFields))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
					->select('f.*, fd.*')
					->from($db->qn('#__redshop_fields', 'f'))
					->leftJoin($db->qn('#__redshop_fields_data', 'fd') . ' ON fd.fieldid = f.field_id')
					->where('fd.section = 1')
					->order('f.field_id asc');

			$fields = $db->setQuery($query)->loadObjectList();

			for ($i = 0, $n = count($fields); $i < $n; $i++)
			{
				$field = $fields[$i];

				self::$productExtraFields[$field->field_name] = $field->field_name;
				self::$productExtraFieldsData[$field->itemid][$field->field_name] = $field;
			}
		}

		$return         = new stdClass;
		$return->fields = array_keys(self::$productExtraFields);
		$return->data   = self::$productExtraFieldsData;

		return $return;
	}

	/**
	 * Method for get all available exports features.
	 *
	 * @return  array  List of available exports.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getExports()
	{
		$plugins = JPluginHelper::getPlugin('redshop_export');

		if (empty($plugins))
		{
			return array();
		}

		$language = JFactory::getLanguage();

		foreach ($plugins as $plugin)
		{
			$language->load('plg_redshop_export_' . $plugin->name, JPATH_SITE . '/plugins/redshop_export/' . $plugin->name);
		}

		return $plugins;
	}
}
