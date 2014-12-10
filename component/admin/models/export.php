<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperAdminExtra_field');
JLoader::load('RedshopHelperProduct');

/**
 * Class Model Export
 *
 * @since  2.5
 */
class RedshopModelExport extends RedshopModel
{
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
			$app->Redirect("index.php?option=com_redshop&view=export", JText::_("COM_REDSHOP_PLEASE_SELECT_SECTION"));
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

		$export_product_extra_field = JRequest::getInt('export_product_extra_field', 0);
		$product_category = JRequest::getVar('product_category');
		$product_category_value = "";

		for ($j = 0; $j < count($product_category); $j++)
		{
			$product_category_value .= "'" . $product_category[$j] . "'" . ",";
		}

		$manufacturer_id = JRequest::getVar('manufacturer_id');
		$manufacturer_id_value = "";

		for ($i = 0; $i < count($manufacturer_id); $i++)
		{
			$manufacturer_id_value .= "'" . $manufacturer_id[$i] . "'" . ",";
		}

		if (count($manufacturer_id) > 0 || count($product_category) > 0)
		{
			$q = "SELECT p.*,pc.product_id FROM `#__redshop_product` p left outer join `"
				. "#__redshop_product_category_xref` pc on p.product_id = pc.product_id ";
			$q .= " where ";
		}
		else
		{
			$q = "SELECT * FROM `#__redshop_product` ORDER BY product_id asc ";
		}

		if (count($manufacturer_id) > 0)
		{
			$q .= " p.manufacturer_id IN (" . substr_replace($manufacturer_id_value, "", -1) . ")";
		}

		if (count($manufacturer_id) > 0 && count($product_category) > 0)
		{
			$q .= " AND ";
		}

		if (count($product_category) > 0)
		{
			$q .= " pc.category_id IN (" . substr_replace($product_category_value, "", -1) . ")";
		}

		if (count($manufacturer_id) > 0 || count($product_category) > 0)
		{
			$q .= " group by p.product_id ORDER BY p.product_id asc";
		}

		$db->setQuery($q);

		if (!($cur = $db->LoadObjectList()))
		{
			return null;
		}

		$ret = null;
		$i = 0;

		if (count($cur) > 0)
		{
			for ($p = 0; $p < count($cur); $p++)
			{
				$row = $cur[$p];
				$row = (array) $row;
				$fields = count($row);

				// Get product extra fields data - collect data
				if ($export_product_extra_field)
				{
					$extrafields = $this->getProductExtrafield();
					$extrafheader = $extrafields['header'];
					$efieldsdata = $extrafields['rowdata'];
				}

				if ($i == 0)
				{
					foreach ($row as $id => $value)
					{
						echo '"' . str_replace('"', "'", $id) . '"';

						if ($i < ($fields - 1))
						{
							echo ',';
						}

						$i++;
					}

					echo ',"sitepath","category_id","category_name","accessory_products","product_stock","images","images_order","images_alternattext","video","video_order","video_alternattext","document","document_order","document_alternattext","download","download_order","download_alternattext"';

					// Product Extra field as header
					if ($export_product_extra_field)
						echo ',"' . implode('","', $extrafheader) . '"';

					echo "\r\n";
				}

				$i = 0;

				foreach ($row as $id => $value)
				{
					if ($id == 'product_full_image' && $value != "")
					{
						if (!is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $value))
						{
							$value = "";
						}
					}

					if ($id == 'product_thumb_image' && $value != "")
					{
						if (!is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $value))
						{
							$value = "";
						}
					}

					if ($id == 'product_back_full_image' && $value != "")
					{
						if (!is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $value))
						{
							$value = "";
						}
					}

					if ($id == 'product_back_thumb_image' && $value != "")
					{
						if (!is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $value))
						{
							$value = "";
						}
					}

					if ($id == 'product_preview_image' && $value != "")
					{
						if (!is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $value))
						{
							$value = "";
						}
					}

					if ($id == 'product_preview_back_image' && $value != "")
					{
						if (!is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $value))
						{
							$value = "";
						}
					}

					$value = str_replace("\n", "", $value);

					$value = str_replace("\r", "", $value);

					echo '"' . str_replace('"', "'", $value) . '"';

					if ($i < ($fields))
					{
						echo ',';
					}

					$i++;
				}

				// Added site path
				if ($fields)
				{
					$sitepath = JURI::root();
					echo $sitepath . ",";
				}

				// Added category ids and name
				if ($fields + 1)
				{
					$query = "SELECT pcx.category_id,c.category_name FROM #__redshop_product_category_xref as pcx"
						. " LEFT JOIN #__redshop_category c ON c.category_id = pcx.category_id"
						. " WHERE product_id ='" . $row['product_id'] . "' ";
					$db->setQuery($query);
					$category = $db->loadObjectList();
					$cats = array();
					$cat_name = array();

					for ($cat = 0; $cat < count($category); $cat++)
					{
						$cats[] = $category[$cat]->category_id;
						$cat_name[] = $category[$cat]->category_name;
					}

					$categoryids = implode("###", $cats);
					$categorynames = implode("###", $cat_name);
					echo $categoryids . "," . $categorynames . ",";
				}

				// Added accessory product ids
				if ($fields + 2)
				{
					$query = "SELECT CONCAT(`product_number`,'~',`accessory_price`) as accsdata  FROM `#__redshop_product_accessory` as pa "
						. " LEFT JOIN #__redshop_product p ON p.product_id = pa.child_product_id"
						. " WHERE pa.`product_id` = '" . $row['product_id'] . "' ";
					$db->setQuery($query);
					$accessory = $db->loadObjectList();
					$accessories = "";
					$accs = array();

					for ($acc = 0; $acc < count($accessory); $acc++)
					{
						$accs[] = $accessory[$acc]->accsdata;
					}

					$accessories = implode("###", $accs);
					echo $accessories . ",";
				}

				if ($fields + 3)
				{
					$query = 'SELECT quantity FROM `#__redshop_product_stockroom_xref` WHERE `product_id` = '
						. $row['product_id'] . ' AND 	stockroom_id = ' . DEFAULT_STOCKROOM;
					$db->setQuery($query);

					$stock = $db->loadObject();

					if ($stock)
					{
						echo $stock->quantity;
					}

					echo ',';
				}

				// Media type image name export
				if ($fields + 4)
				{
					$query = "SELECT media_name
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'images'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$images = $this->_getList($query);

					if ($images)
					{
						$image = array();

						for ($i = 0; $i < count($images); $i++)
						{
							$image[] = $images[$i]->media_name;
						}

						$image = implode("#", $image);
						echo $image;
					}

					echo ',';
				}

				// Media type image order export
				if ($fields + 5)
				{
					$query = "SELECT ordering
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'images'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$images = $this->_getList($query);

					if ($images)
					{
						$image = array();

						for ($i = 0; $i < count($images); $i++)
						{
							$image[] = $images[$i]->ordering;
						}

						$image = implode("#", $image);
						echo $image;
					}

					echo ',';
				}

				// Media type image media_alternate_text export
				if ($fields + 6)
				{
					$query = "SELECT media_alternate_text
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'images'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$images = $this->_getList($query);

					if ($images)
					{
						$image = array();

						for ($i = 0; $i < count($images); $i++)
						{
							$image[] = $images[$i]->media_alternate_text;
						}

						$image = implode("#", $image);
						echo $image;
					}

					echo ',';
				}

				// Media type video name export
				if ($fields + 7)
				{
					$query = "SELECT media_name
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'video'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$videos = $this->_getList($query);

					if ($videos)
					{
						$video = array();

						for ($i = 0; $i < count($videos); $i++)
						{
							$video[] = $videos[$i]->media_name;
						}

						$video = implode("#", $video);
						echo $video;
					}

					echo ',';
				}

				// Media type video ordering export
				if ($fields + 8)
				{
					$query = "SELECT ordering
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'video'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$videos = $this->_getList($query);

					if ($videos)
					{
						$video = array();

						for ($i = 0; $i < count($videos); $i++)
						{
							$video[] = $videos[$i]->ordering;
						}

						$video = implode("#", $video);
						echo $video;
					}

					echo ',';
				}

				// Media type video media_alternate_text export
				if ($fields + 8)
				{
					$query = "SELECT media_alternate_text
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'video'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$videos = $this->_getList($query);

					if ($videos)
					{
						$video = array();

						for ($i = 0; $i < count($videos); $i++)
						{
							$video[] = $videos[$i]->media_alternate_text;
						}

						$video = implode("#", $video);
						echo $video;
					}

					echo ',';
				}

				// Media type document name export
				if ($fields + 9)
				{
					$query = "SELECT media_name
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'document'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$documents = $this->_getList($query);

					if ($documents)
					{
						$document = array();

						for ($i = 0; $i < count($documents); $i++)
						{
							$document[] = $documents[$i]->media_name;
						}

						$document = implode("#", $document);
						echo $document;
					}

					echo ',';
				}

				// Media type document ordering export
				if ($fields + 10)
				{
					$query = "SELECT ordering
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'document'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$documents = $this->_getList($query);

					if ($documents)
					{
						$document = array();

						for ($i = 0; $i < count($documents); $i++)
						{
							$document[] = $documents[$i]->ordering;
						}

						$document = implode("#", $document);
						echo $document;
					}

					echo ',';
				}

				// Media type document media_alternate_text export
				if ($fields + 10)
				{
					$query = "SELECT media_alternate_text
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'document'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$documents = $this->_getList($query);

					if ($documents)
					{
						$document = array();

						for ($i = 0; $i < count($documents); $i++)
						{
							$document[] = $documents[$i]->media_alternate_text;
						}

						$document = implode("#", $document);
						echo $document;
					}

					echo ',';
				}

				// Media type download name export
				if ($fields + 11)
				{
					$query = "SELECT media_name
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'download'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$downloads = $this->_getList($query);

					if ($downloads)
					{
						$download = array();

						for ($i = 0; $i < count($downloads); $i++)
						{
							$download[] = $downloads[$i]->media_name;
						}

						$download = implode("#", $download);
						echo $download;
					}

					echo ',';
				}

				// Media type download ordering export
				if ($fields + 12)
				{
					$query = "SELECT ordering
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'download'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$downloads = $this->_getList($query);

					if ($downloads)
					{
						$download = array();

						for ($i = 0; $i < count($downloads); $i++)
						{
							$download[] = $downloads[$i]->ordering;
						}

						$download = implode("#", $download);
						echo $download;
					}

					echo ',';
				}

				// Media type download media_alternate_text export
				if ($fields + 13)
				{
					$query = "SELECT media_alternate_text
								FROM `#__redshop_media`
								WHERE `media_section` LIKE 'product'
								AND `media_type` LIKE 'download'
								AND `section_id` =" . $row['product_id'] . "
								ORDER BY ordering ASC";

					$downloads = $this->_getList($query);

					if ($downloads)
					{
						$download = array();

						for ($i = 0; $i < count($downloads); $i++)
						{
							$download[] = $downloads[$i]->media_alternate_text;
						}

						$download = implode("#", $download);
						echo $download;
					}

					echo ',';
				}

				// Product extra fields data
				if ($export_product_extra_field)
				{
					$fd = 0;

					if (count($extrafheader) > 0)
					{
						foreach ($extrafheader as $fieldid => $fieldname)
						{
							$fieldoutdata = (isset($efieldsdata[$row['product_id']][$fieldid])) ? $efieldsdata[$row['product_id']][$fieldid] : '';
							$fieldoutdata = str_replace("\n", "", $fieldoutdata);
							$fieldoutdata = str_replace("\r", "", $fieldoutdata);
							$fieldoutdata = '"' . str_replace('"', "'", $fieldoutdata) . '"';
							echo $fieldoutdata;

							$fd++;

							if ($fd < count($extrafheader))
							{
								echo ",";
							}
						}
					}
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
	 * Load the categories for export
	 *
	 * @return  void
	 */
	private function loadCategories()
	{
		$db = JFactory::getDbo();
		$q = "SELECT c.*,cx.category_parent_id
			FROM #__redshop_category c LEFT JOIN #__redshop_category_xref cx ON c.category_id = cx.category_child_id WHERE cx.category_parent_id IS NOT NULL ORDER BY c.category_id";
		$db->setQuery($q);

		if (!($cur = $db->LoadObjectList()))
		{
			return null;
		}

		$ret = null;
		$i = 0;

		if (count($cur) > 0)
		{
			for ($c = 0; $c < count($cur); $c++)
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
	}

	/**
	 * Load the attributes for export
	 *
	 * @return  void
	 */
	private function loadAttributes()
	{
		$producthelper = new producthelper;

		$db = JFactory::getDbo();
		$query = "SELECT * FROM `#__redshop_product` ORDER BY product_id asc ";
		$db->setQuery($query);
		$cur = $db->loadObjectList();

		$ret = null;

		if (count($cur) > 0)
		{
			$redhelper = new redhelper;
			$isrecrm = false;

			if ($redhelper->isredCRM())
			{
				$isrecrm = true;
			}

			for ($i = 0; $i < count($cur); $i++)
			{
				if ($i == 0)
				{
					echo '"product_number","attribute_name","attribute_ordering","allow_multiple_selection","hide_attribute_price","attribute_required","display_type","property_name","property_stock"';

					if ($isrecrm)
					{
						echo ',"property_stock_placement"';
					}

					echo ',"property_ordering","property_virtual_number","setdefault_selected","setdisplay_type","oprand","property_price","property_image","property_main_image","subattribute_color_name","subattribute_stock"';

					if ($isrecrm)
					{
						echo ',"subattribute_stock_placement"';
					}

					echo ',"subattribute_color_ordering","subattribute_setdefault_selected","subattribute_color_title","subattribute_virtual_number","subattribute_color_oprand","required_sub_attribute","subattribute_color_price","subattribute_color_image","delete"';

					echo "\r\n";
				}

				// Added attribute of products
				$attribute = $producthelper->getProductAttribute($cur[$i]->product_id);
				$attr = array();

				for ($att = 0; $att < count($attribute); $att++)
				{
					if ($attribute[$att]->attribute_name != "")
					{
						echo '"' . $cur[$i]->product_number . '","' . $attribute[$att]->attribute_name . '","' . $attribute[$att]->ordering . '","' . $attribute[$att]->allow_multiple_selection . '","' . $attribute[$att]->hide_attribute_price . '","' . $attribute[$att]->attribute_required . '","' . $attribute[$att]->display_type . '"';

						if ($isrecrm)
						{
							echo ',,';
						}

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

							for ($h = 0; $h < count($fetch_arrtibute_stock); $h++)
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

							if ($isrecrm)
							{
								$main_attribute_stock_placement = "";

								// Initialiase variables.
								$query = $db->getQuery(true);

								// Prepare query.
								$query->select('stock_placement');
								$query->from('#__redcrm_attribute_stock_placement');
								$query->where('section = "property"');
								$query->where('section_id = "' . $att_property[$prop]->property_id . '"');

								// Inject the query and load the result.
								$db->setQuery($query);
								$main_attribute_stock_placement = $db->loadResult();

								echo ',"' . $main_attribute_stock_placement . '"';
							}

							echo ',"' . $att_property[$prop]->ordering . '","' . $att_property[$prop]->property_number . '","'
								. $att_property[$prop]->setdefault_selected . '","' . $att_property[$prop]->setdisplay_type . '","'
								. $att_property[$prop]->oprand . '","' . $att_property[$prop]->property_price . '","' . $property_image
								. '","' . $property_main_image . '"';

							if ($isrecrm)
							{
								echo ',';
							}

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

								for ($b = 0; $b < count($fetch_arrtibute_stock_sub); $b++)
								{
									$main_attribute_stock_sub .= $fetch_arrtibute_stock_sub[$b]->stockroom_id . ":" . $fetch_arrtibute_stock_sub[$b]->quantity . "#";
								}

								if ($subatt_property[$subprop]->subattribute_color_image != "")
								{
									$subattribute_color_image = REDSHOP_FRONT_IMAGES_ABSPATH . 'subcolor/'
										. $subatt_property[$subprop]->subattribute_color_image;
								}

								echo '"' . $cur[$i]->product_number . '","' . $attribute[$att]->attribute_name . '",,,,,,"' . $att_property[$prop]->property_name . '"';

								if ($isrecrm)
								{
									echo ',';
								}

								echo ',,,,,,,,,,"' . $subatt_property[$subprop]->subattribute_color_name . '","' . $main_attribute_stock_sub . '"';

								if ($isrecrm)
								{
									$main_attribute_stock_sub_placement = "";

									// Initialiase variables.
									$query = $db->getQuery(true);

									// Prepare query.
									$query->select('stock_placement');
									$query->from('#__redcrm_attribute_stock_placement');
									$query->where('section = "subproperty"');
									$query->where('section_id = "' . $subatt_property[$subprop]->subattribute_color_id . '"');

									// Inject the query and load the result.
									$db->setQuery($query);
									$main_attribute_stock_sub_placement = $db->loadResult();

									echo ',"' . $main_attribute_stock_sub_placement . '"';
								}

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
	private function loadManufacturer()
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
			for ($e = 0; $e < count($manufacturers); $e++)
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
				$pids = $db->LoadResultArray();
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
	}

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

		$ret = null;
		$i = 0;

		if (count($cur) > 0)
		{
			for ($r = 0; $r < count($cur); $r++)
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
		$extra_field   = new extra_field;
		$producthelper = new producthelper;
		$db            = JFactory::getDbo();
		$query         = "SELECT * FROM `#__redshop_fields` ORDER BY field_id asc ";
		$db->setQuery($query);
		$cur           = $db->loadObjectList();
		$ret           = null;

		for ($i = 0; $i < count($cur); $i++)
		{
			if ($i == 0)
			{
				echo "field_id,field_title,field_name_field,field_type,field_desc,field_class,field_section,field_maxlength,field_cols,field_rows,field_size,field_show_in_front,required,published,data_id,data_txt,itemid,section,value_id,field_value,field_name,data_number";
				echo "\r\n";
			}

			$query = 'SELECT data_id,`data_txt`,`itemid`,`section` FROM `#__redshop_fields_data` WHERE `fieldid` = ' . $cur[$i]->field_id . ' and section!=""';
			$db->setQuery($query);
			$data = $db->loadObjectList();
			$attr = array();

			$datavalue = $extra_field->getFieldValue($cur[$i]->field_id);
			$attrvalue = array();

			echo $cur[$i]->field_id . "," . $cur[$i]->field_title . "," . $cur[$i]->field_name . "," . $cur[$i]->field_type . ","
				. $cur[$i]->field_desc . "," . $cur[$i]->field_class . "," . $cur[$i]->field_section . "," . $cur[$i]->field_maxlength . ","
				. $cur[$i]->field_cols . "," . $cur[$i]->field_rows . "," . $cur[$i]->field_size . "," . $cur[$i]->field_show_in_front . ","
				. $cur[$i]->required . "," . $cur[$i]->published . "\n";

			for ($att = 0; $att < count($data); $att++)
			{
				$product_details = $producthelper->getProductById($data[$att]->itemid);
				echo $cur[$i]->field_id . ",,,,,,,,,,,,,," . $data[$att]->data_id . ",\"" . $data[$att]->data_txt . "\","
					. $data[$att]->itemid . "," . $data[$att]->section . ",,,," . $product_details->product_number . ",\n";
			}

			for ($attrvalue = 0; $attrvalue < count($datavalue); $attrvalue++)
			{
				echo $cur[$i]->field_id . ",,,,,,,,,,,,,,,,,," . $datavalue[$attrvalue]->value_id . "," . $datavalue[$attrvalue]->field_value . "," . $datavalue[$attrvalue]->field_name . ",\n";
			}
		}
	}

	/**
	 * Load the users for export
	 *
	 * @return  void
	 */
	private function loadUsers()
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
			foreach($usersInfo as $userId => $user)
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
	}

	/**
	 * Get Joomla User and Group relation
	 *
	 * @param   array  $userIds  Joomla Userids
	 *
	 * @return  array  UserId index array for group ids
	 */
	private function getJGroupIds($userIds)
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
	}

	/**
	 * Load the Shipping Address for export
	 *
	 * @return  void
	 */
	private function loadshippingaddress()
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

		$ret = null;
		$i = 0;

		if (count($cur) > 0)
		{
			for ($s = 0; $s < count($cur); $s++)
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
	}

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
			for ($e = 0; $e < count($product); $e++)
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
			for ($f = 0; $f < count($attributes); $f++)
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
	 * get Product extra field data
	 *
	 * @return  array  Extra Field information
	 */
	public function getProductExtrafield()
	{
		$db = JFactory::getDbo();

		$query = "SELECT field_id, field_name FROM #__redshop_fields "
			. "WHERE field_section=1 "
			. "ORDER BY ordering ";
		$db->setQuery($query);
		$fields = $db->loadObjectlist();

		$listfields = array();

		for ($i = 0; $i < count($fields); $i++)
		{
			$listfields[$fields[$i]->field_id] = $fields[$i]->field_name;
		}

		ksort($listfields);

		$query = "SELECT fieldid, data_txt, itemid FROM #__redshop_fields_data "
			. "WHERE section=1 ";
		$db->setQuery($query);
		$fielddata = $db->loadObjectlist();

		$fieldrowdata = array();

		for ($i = 0; $i < count($fielddata); $i++)
		{
			$product_id = $fielddata[$i]->itemid;
			$data = $fielddata[$i]->data_txt;
			$field_id = $fielddata[$i]->fieldid;
			$fieldrowdata[$product_id][$field_id] = $data;
		}

		$fieldExport = array();
		$fieldExport['header'] = $listfields;
		$fieldExport['rowdata'] = $fieldrowdata;

		return $fieldExport;
	}
}
