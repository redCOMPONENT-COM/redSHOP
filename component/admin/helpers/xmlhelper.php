<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class xmlHelper
{
	/**
	 *
	 * @return array
	 */
	public function getSectionTypeList()
	{
		$section   = array();
		$section[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT'));
		$section[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$section[] = JHTML::_('select.option', 'order', JText::_('COM_REDSHOP_ORDER'));

		return $section;
	}

	/**
	 * @param   string $value Value
	 *
	 * @return  string
	 */
	public function getSectionTypeName($value = '')
	{
		if ($value === 'product' || $value === 'order')
		{
			return JText::_('COM_REDSHOP_' . strtoupper($value));
		}

		return '-';
	}

	/**
	 *
	 * @return array
	 */
	public function getSynchIntervalList()
	{
		$section   = array();
		$section[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$section[] = JHTML::_('select.option', 24, JText::_('COM_REDSHOP_24_HOURS'));
		$section[] = JHTML::_('select.option', 12, JText::_('COM_REDSHOP_12_HOURS'));
		$section[] = JHTML::_('select.option', 6, JText::_('COM_REDSHOP_6_HOURS'));

		return $section;
	}

	/**
	 * @param   integer $value Value
	 *
	 * @return  string
	 *
	 */
	public function getSynchIntervalName($value = 0)
	{
		if ($value == 6 || $value == 12 || $value == 24)
		{
			return JText::_('COM_REDSHOP_' . $value . '_HOURS');
		}

		return '-';
	}

	/**
	 * @param   string $section      Section
	 * @param   string $childSection Child section
	 *
	 * @return  array|mixed
	 */
	public function getSectionColumnList($section = "", $childSection = "")
	{
		$catcol = array();
		$table  = "";

		switch ($section)
		{
			case 'product':
				switch ($childSection)
				{
					case "stockdetail":
						$table  = "stockroom";
						$fields = \Redshop\Repositories\Table::getFields('#__redshop_product_stockroom_xref');

						foreach ($fields as $field)
						{
							if ($field->Field != 'quantity')
							{
								continue;
							}

							$catcol[] = $field;
						}
						break;
					case "prdextrafield":
						$fields = \Redshop\Repositories\Table::getFields('#__redshop_fields_data');

						foreach ($fields as $field)
						{
							if ($field->Field == "user_email" || $field->Field == "section")
							{
								continue;
							}

							$catcol[] = $field;
						}
						break;
					default:
						$table  = "product";
						$fields = \Redshop\Repositories\Table::getFields('#__redshop_category');

						foreach ($fields as $field)
						{
							if ($field->Field == 'category_name' || $field->Field == 'category_description')
							{
								$catcol[] = $field;
							}
							elseif ($field->Field == "category_template") // Start Code for display product_url
							{
								$field->Field = "link";
								$catcol[]     = $field;
							}
							elseif ($field->Field == "category_thumb_image") // Start Code for display delivertime
							{
								$field->Field = "delivertime";
								$catcol[]     = $field;
							}
							elseif ($field->Field == "category_full_image") // Start Code for display pickup
							{
								$field->Field = "pickup";
								$catcol[]     = $field;
							}
							elseif ($field->Field == "category_back_full_image") // Start Code for display charges
							{
								$field->Field = "charge";
								$catcol[]     = $field;
							}
							elseif ($field->Field == "category_pdate") // Start Code for display freight
							{
								$field->Field = "freight";
								$catcol[]     = $field;
							}
						}

						// Start Code for display manufacturer name field
						$fields = \Redshop\Repositories\Table::getFields('#__redshop_manufacturer');

						foreach ($fields as $field)
						{
							if ($field->Field !== "name")
							{
								continue;
							}

							$catcol[] = $field;
						}

						break;
				}
				break;
			case 'order':
				$table = "orders";

				switch ($childSection)
				{
					case "orderdetail":
						$table = "orders";
						break;
					case "billingdetail":
						$table = "order_users_info";
						break;
					case "shippingdetail":
						$table = "order_users_info";
						break;
					case "orderitem":
						$table = "order_item";
						break;
				}

				break;
		}

		// Reset fields
		$fields = array();

		if ($section != "" && $table != "")
		{
			$fields = \Redshop\Repositories\Table::getFields('#__redshop_' . $table);
		}

		$fields = array_merge($fields, $catcol);

		foreach ($fields as $index => $field)
		{
			if (strtoupper($fields[$index]->Key) == "PRI")
			{
				unset($fields[$index]);
			}
		}

		sort($fields);

		return $fields;
	}

	/**
	 * @param   string $fieldName  Field name
	 * @param   string $xmlFileTag Xml file tag
	 *
	 * @return  array
	 *
	 * @since   2.1.0
	 */
	public function getXMLFileTag($fieldName = "", $xmlFileTag)
	{
		$result = "";
		$update = 1;

		if (empty($xmlFileTag))
		{
			return array($result, $update);
		}

		$fields = explode(";", $xmlFileTag);

		foreach ($fields as $index => $field)
		{
			$value = explode("=", $field);

			if ($value[0] == $fieldName)
			{
				$result = trim($value[1]);
				$update = (isset($value[2])) ? $value[2] : 0;
				break;
			}
		}

		return array($result, $update);
	}

	/**
	 * @param   string $xmlFileTag Xml file tag
	 *
	 * @return  array
	 */
	public function explodeXMLFileString($xmlFileTag = '')
	{
		if (empty($xmlFileTag))
		{
			return array();
		}

		$value  = array();
		$fields = explode(";", $xmlFileTag);

		foreach ($fields as $index => $field)
		{
			$value[$index] = explode("=", $field);
		}

		return $value;
	}

	/**
	 * @param   integer $xmlExportId Xml export id
	 *
	 * @return  mixed
	 */
	public function getXMLExportInfo($xmlExportId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__redshop_xml_export', 'x'))
			->where($db->quoteName('x.xmlexport_id') . ' = ' . (int) $xmlExportId);

		return $db->setQuery($query)->loadObjectList();
	}

	public function getXMLExportIpAddress($xmlexport_id = 0)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT * FROM " . "#__redshop_xml_export_ipaddress AS x "
			. "WHERE x.xmlexport_id=" . (int) $xmlexport_id;

		return $db->setQuery($query)->loadObjectlist();
	}

	public function insertXMLExportlog($xmlexport_id = 0, $filename = "")
	{
		$db    = JFactory::getDbo();
		$query = "INSERT INTO " . "#__redshop_xml_export_log "
			. "(xmlexport_id, xmlexport_filename, xmlexport_date) "
			. "VALUES "
			. "(" . (int) $xmlexport_id . ", " . $db->quote($filename) . "," . $db->quote(time()) . ") ";

		return $db->setQuery($query)->execute();
	}

	public function updateXMLExportFilename($xmlexport_id = 0, $filename = "")
	{
		$db    = JFactory::getDbo();
		$query = "UPDATE " . "#__redshop_xml_export "
			. "SET filename=" . $db->quote($filename) . " "
			. "WHERE xmlexport_id=" . (int) $xmlexport_id;

		return $db->setQuery($query)->execute();
	}

	public function getXMLImportInfo($xmlimport_id = 0)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT * FROM " . "#__redshop_xml_import "
			. "WHERE xmlimport_id=" . (int) $xmlimport_id;

		return $db->setQuery($query)->loadObjectList();
	}

	public function insertXMLImportlog($xmlimport_id = 0, $filename = "")
	{
		$db    = JFactory::getDbo();
		$query = "INSERT INTO " . "#__redshop_xml_import_log "
			. "(xmlimport_id, xmlimport_filename, xmlimport_date) "
			. "VALUES "
			. "(" . (int) $xmlimport_id . ", " . $db->quote($filename) . "," . (int) time() . ") ";

		return $db->setQuery($query)->execute();
	}

	public function updateXMLImportFilename($xmlimport_id = 0, $filename = "")
	{
		$db    = JFactory::getDbo();
		$query = "UPDATE " . "#__redshop_xml_import "
			. "SET filename=" . $db->quote($filename) . " "
			. "WHERE xmlimport_id=" . (int) $xmlimport_id;

		return $db->setQuery($query)->execute();
	}

	/**
	 * @param   integer $xmlExportId Xml export id
	 *
	 * @return  string|boolean
	 */
	public function writeXMLExportFile($xmlExportId = 0)
	{
		$xmlarray      = array();
		$xmlExportData = $this->getXMLExportInfo($xmlExportId);

		if (count($xmlExportData) <= 0)
		{
			return false;
		}

		$destpath = JPATH_SITE . "/components/com_redshop/assets/xmlfile/export/";
		$section  = $xmlExportData->section_type;
		$columns  = $this->getSectionColumnList($section, "orderdetail");

		for ($i = 0, $in = count($columns); $i < $in; $i++)
		{
			$tag = $this->getXMLFileTag($columns[$i]->Field, $xmlExportData->xmlexport_filetag);

			if ($tag[0] != "")
			{
				$xmlarray[$columns[$i]->Field] = $tag[0];
			}
		}

		$datalist          = array();
		$billinglist       = array();
		$shippinglist      = array();
		$orderItemlist     = array();
		$stocklist         = array();
		$prdextrafieldlist = array();
		$xmlbilling        = array();
		$xmlshipping       = array();
		$xmlOrderitem      = array();
		$xmlstock          = array();
		$xmlprdextrafield  = array();
		$prdfullimage      = "";
		$prdthmbimage      = "";

		switch ($section)
		{
			case "product":
				if (array_key_exists("product_full_image", $xmlarray))
				{
					$prdfullimage = $xmlarray['product_full_image'];
				}

				if (array_key_exists("product_thumb_image", $xmlarray))
				{
					$prdthmbimage = $xmlarray['product_thumb_image'];
				}

				$datalist = $this->getProductList($xmlarray, $xmlExportData);

				$columns = $this->getSectionColumnList($section, "stockdetail");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = $this->getXMLFileTag($columns[$i]->Field, $xmlExportData->xmlexport_stocktag);

					if ($tag[0] != "")
					{
						$xmlstock[$columns[$i]->Field] = $tag[0];
					}
				}

				$columns = $this->getSectionColumnList($section, "prdextrafield");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = $this->getXMLFileTag($columns[$i]->Field, $xmlExportData->xmlexport_prdextrafieldtag);

					if ($tag[0] != "")
					{
						$xmlprdextrafield[$columns[$i]->Field] = $tag[0];
					}
				}
				break;
			case "order":
				$datalist = $this->getOrderList($xmlarray);

				$columns = $this->getSectionColumnList($section, "billingdetail");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = $this->getXMLFileTag($columns[$i]->Field, $xmlExportData->xmlexport_billingtag);

					if ($tag[0] != "")
					{
						$xmlbilling[$columns[$i]->Field] = $tag[0];
					}
				}

				$columns = $this->getSectionColumnList($section, "shippingdetail");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = $this->getXMLFileTag($columns[$i]->Field, $xmlExportData->xmlexport_shippingtag);

					if ($tag[0] != "")
					{
						$xmlshipping[$columns[$i]->Field] = $tag[0];
					}
				}

				$columns = $this->getSectionColumnList($section, "orderitem");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = $this->getXMLFileTag($columns[$i]->Field, $xmlExportData->xmlexport_orderitemtag);

					if ($tag[0] != "")
					{
						$xmlOrderitem[$columns[$i]->Field] = $tag[0];
					}
				}
				break;
			default:
				return false;
		}

		// Make the filename unique
		$filename = RedshopHelperMedia::cleanFileName($xmlExportData->display_filename . '.xml');

		$xml_document = "<?xml version='1.0' encoding='utf-8'?>";

		if (trim($xmlExportData->element_name) == "")
		{
			$xmlExportData->element_name = $xmlExportData->parent_name . "_element";
		}

		$xml_document .= "<" . $xmlExportData->parent_name . ">";

		for ($i = 0, $in = count($datalist); $i < $in; $i++)
		{
			$product_id = 0;

			if ($section == "product")
			{
				$product_id = $datalist[$i]['product_id'];
			}

			$xml_billingdocument  = "";
			$xml_shippingdocument = "";
			$xml_itemdocument     = "";
			$xml_stockdocument    = "";
			$xml_prdextradocument = "";

			if (!empty($xmlbilling))
			{
				$billinglist = $this->getOrderUserInfoList($xmlbilling, $datalist[$i]->order_id);

				if (!empty($billinglist))
				{
					$xml_billingdocument .= "<$xmlExportData->billing_element_name>";

					while (list($prop, $val) = each($billinglist))
					{
						$val                 = html_entity_decode($val);
						$xml_billingdocument .= "<$prop><![CDATA[$val]]></$prop>";
					}

					$xml_billingdocument .= "</$xmlExportData->billing_element_name>";
				}
			}

			if (!empty($xmlshipping))
			{
				$shippinglist = $this->getOrderUserInfoList($xmlshipping, $datalist[$i]->order_id, "ST");

				if (!empty($shippinglist))
				{
					$xml_shippingdocument .= "<$xmlExportData->shipping_element_name>";

					while (list($prop, $val) = each($shippinglist))
					{
						$val                  = html_entity_decode($val);
						$xml_shippingdocument .= "<$prop><![CDATA[$val]]></$prop>";
					}

					$xml_shippingdocument .= "</$xmlExportData->shipping_element_name>";
				}
			}

			if (!empty($xmlOrderitem))
			{
				$orderItemlist = $this->getOrderItemList($xmlOrderitem, $datalist[$i]->order_id);

				if (!empty($orderItemlist))
				{
					$xml_itemdocument .= "<" . $xmlExportData->orderitem_element_name . "s>";

					for ($j = 0, $jn = count($orderItemlist); $j < $jn; $j++)
					{
						$xml_itemdocument .= "<$xmlExportData->orderitem_element_name>";

						while (list($prop, $val) = each($orderItemlist[$j]))
						{
							$val              = html_entity_decode($val);
							$xml_itemdocument .= "<$prop><![CDATA[$val]]></$prop>";
						}

						$xml_itemdocument .= "</$xmlExportData->orderitem_element_name>";
					}

					$xml_itemdocument .= "</" . $xmlExportData->orderitem_element_name . "s>";
				}
			}

			if (!empty($xmlstock))
			{
				$stocklist = $this->getStockroomList($xmlstock, $product_id);

				if (!empty($stocklist))
				{
					$xml_stockdocument .= "<" . $xmlExportData->stock_element_name . "s>";

					for ($j = 0, $jn = count($stocklist); $j < $jn; $j++)
					{
						$xml_stockdocument .= "<$xmlExportData->stock_element_name>";

						while (list($prop, $val) = each($stocklist[$j]))
						{
							$val               = html_entity_decode($val);
							$xml_stockdocument .= "<$prop><![CDATA[$val]]></$prop>";
						}

						$xml_stockdocument .= "</$xmlExportData->stock_element_name>";
					}

					$xml_stockdocument .= "</" . $xmlExportData->stock_element_name . "s>";
				}
			}

			if (!empty($xmlprdextrafield))
			{
				$prdextrafieldlist = $this->getExtraFieldList($xmlprdextrafield, $product_id, 1);

				if (!empty($prdextrafieldlist))
				{
					$xml_prdextradocument .= "<" . $xmlExportData->prdextrafield_element_name . "s>";

					for ($j = 0, $jn = count($prdextrafieldlist); $j < $jn; $j++)
					{
						$xml_prdextradocument .= "<" . $prdextrafieldlist[$j]->name . ">";

						while (list($prop, $val) = each($prdextrafieldlist[$j]))
						{
							if ($prop == 'name')
							{
								continue;
							}

							$val                  = html_entity_decode($val);
							$xml_prdextradocument .= "<$prop><![CDATA[$val]]></$prop>";
						}

						$xml_prdextradocument .= "</" . $prdextrafieldlist[$j]->name . ">";
					}

					$xml_prdextradocument .= "</" . $xmlExportData->prdextrafield_element_name . "s>";
				}
			}

			if ($section != "order" || !empty($xml_itemdocument))
			{
				$xml_document .= "<$xmlExportData->element_name>";

				while (list($prop, $val) = each($datalist[$i]))
				{
					$val = html_entity_decode($val);

					if ($prop == $prdfullimage && $val != "")
					{
						$val = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $val;
					}

					if ($prop == $prdthmbimage && $val != "")
					{
						$val = REDSHOP_FRONT_IMAGES_ABSPATH . "product/thumb/" . $val;
					}

					if ((isset($xmlarray['cdate']) && $prop == $xmlarray['cdate']) || (isset($xmlarray['mdate']) && $prop == $xmlarray['mdate']))
					{
						$val = RedshopHelperDatetime::convertDateFormat((int) $val);
					}

					if ($prop != "order_id" && $prop != "product_id")
					{
						// Start Code for display product url,delivertime,pickup,charges,freight
						if ($prop == "manufacturer")
						{
							$val = "noname";
						}

						if ($prop == "link")
						{
							$val = JURI::root() . 'index.php?option=com_redshop&view=product&pid=' . $product_id;
						}

						elseif ($prop == "pickup")
						{
							$val = "";
						}

						elseif ($prop == "charge")
						{
							$d['product_id'] = $product_id;
							$srate           = RedshopHelperShipping::getDefaultShippingXmlExport($d);
							$val1            = $srate['shipping_rate'];
							$val             = round($val1);
						}

						elseif ($prop == "freight")
						{
							$d['product_id'] = $product_id;
							$srate           = RedshopHelperShipping::getDefaultShippingXmlExport($d);
							$val1            = $srate['shipping_rate'];
							$val             = round($val1);
						}

						elseif ($prop == "delivertime")
						{
							$query = "SELECT * FROM " . "#__redshop_stockroom AS s "
								. "LEFT JOIN " . "#__redshop_product_stockroom_xref AS sx ON s.stockroom_id=sx.stockroom_id "
								. "WHERE product_id=" . (int) $product_id . " "
								. "ORDER BY s.stockroom_id ASC ";

							$db   = JFactory::getDbo();
							$list = $db->setQuery($query)->loadObject();

							for ($k = 0, $kn = count($list); $k < $kn; $k++)
							{
								if ($list->max_del_time == 1 && $list->max_del_time < 2)
								{
									$val = "1";
								}
								elseif ($list->max_del_time == 2 && $list->max_del_time <= 3)
								{
									$val = "2";
								}
								elseif ($list->max_del_time == 4)
								{
									$val = "4";
								}
								elseif ($list->max_del_time == 5)
								{
									$val = "5";
								}
								elseif ($list->max_del_time >= 6 && $list->max_del_time <= 10)
								{
									$val = "6,7,8,9,10";
								}
								elseif ($list->max_del_time == "")
								{
									$val = "";
								}
							}
						}

						$xml_document .= "<$prop><![CDATA[$val]]></$prop>";
					}
				}

				$xml_document .= $xml_billingdocument;
				$xml_document .= $xml_shippingdocument;
				$xml_document .= $xml_itemdocument;
				$xml_document .= $xml_stockdocument;
				$xml_document .= $xml_prdextradocument;
				$xml_document .= "</" . $xmlExportData->element_name . ">";
			}
		}

		$xml_document .= "</" . $xmlExportData->parent_name . ">";

		// Data in Variables ready to be written to an XML file
		JFile::write($destpath . $filename, $xml_document);

		$this->insertXMLExportlog($xmlExportId, $filename);

		// Update new generated exported file in database record
		$this->updateXMLExportFilename($xmlExportId, $filename);

		return $filename;
	}

	public function writeXMLImportFile($xmlimport_id = 0, $tmlxmlimport_url = "")
	{
		$destpath      = JPATH_SITE . "/components/com_redshop/assets/xmlfile/import/";
		$xmlimportdata = $this->getXMLImportInfo($xmlimport_id);

		if (count($xmlimportdata) <= 0)
		{
			return false; // Import record not exists
		}

		if ($tmlxmlimport_url == "" && $xmlimportdata->filename == "")
		{
			return false; // No URL to import file
		}

		if ($tmlxmlimport_url != "")
		{
			$xmlimportdata->xmlimport_url = $tmlxmlimport_url;
		}
		else
		{
			$xmlimportdata->xmlimport_url = $destpath . $xmlimportdata->filename;
		}

		$filedetail = $this->readXMLImportFile($xmlimportdata->xmlimport_url, $xmlimportdata);
		$datalist   = $filedetail['xmlarray'];

		if (count($datalist) <= 0)
		{
			return false; // No data In imported xmlfile.So no need to write import file.
		}

		// Make the filename unique
		$filename = RedshopHelperMedia::cleanFileName($xmlimportdata->display_filename . ".xml");

		$xml_document = "<?xml version='1.0' encoding='utf-8'?>";
		$xml_document .= "<" . $xmlimportdata->element_name . "s>";

		for ($i = 0, $in = count($datalist); $i < $in; $i++)
		{
			$xml_document .= "<" . $xmlimportdata->element_name . ">";

			while (list($prop, $val) = each($datalist[$i]))
			{
				if (is_array($val))
				{
					$subdatalist = $val;

					if (isset($subdatalist[0]))
					{
						$xml_document .= "<" . $prop . ">";

						for ($j = 0, $jn = count($subdatalist); $j < $jn; $j++)
						{
							$childelement = substr($prop, 0, -1);
							$xml_document .= "<" . $childelement . ">";

							while (list($subprop, $subval) = each($subdatalist[$j]))
							{
								$subval       = html_entity_decode($subval);
								$xml_document .= "<$subprop><![CDATA[$subval]]></$subprop>";
							}

							$xml_document .= "</" . $childelement . ">";
						}

						$xml_document .= "</" . $prop . ">";
					}

					elseif (!empty($subdatalist))
					{
						$xml_document .= "<" . $prop . ">";

						while (list($subprop, $subval) = each($subdatalist))
						{
							$subval       = html_entity_decode($subval);
							$xml_document .= "<$subprop><![CDATA[$subval]]></$subprop>";
						}

						$xml_document .= "</" . $prop . ">";
					}
				}
				else
				{
					$val          = html_entity_decode($val);
					$xml_document .= "<$prop><![CDATA[$val]]></$prop>";
				}
			}

			$xml_document .= "</" . $xmlimportdata->element_name . ">";
		}

		$xml_document .= "</" . $xmlimportdata->element_name . "s>";

		// Data in Variables ready to be written to an XML file
		JFile::write($destpath . $filename, $xml_document);

		// Update new generated imported file in database record
		$this->updateXMLImportFilename($xmlimport_id, $filename);

		return $filename;
	}

	public function readXMLImportFile($file = "", $data = array(), $isImport = 0)
	{
		$resultsectionarray   = array();
		$resultbillingarray   = array();
		$resulshippingtarray  = array();
		$resultorderitemarray = array();
		$resultstockarray     = array();
		$resultprdextarray    = array();

		$xmlFileArray      = array();
		$xmlBillingArray   = array();
		$xmlShippingArray  = array();
		$xmlOrderitemArray = array();
		$xmlStockArray     = array();
		$xmlPrdextArray    = array();

		if ($isImport)
		{
			$xmlFileArray      = $this->explodeXMLFileString($data->xmlimport_filetag);
			$xmlBillingArray   = $this->explodeXMLFileString($data->xmlimport_billingtag);
			$xmlShippingArray  = $this->explodeXMLFileString($data->xmlimport_shippingtag);
			$xmlOrderitemArray = $this->explodeXMLFileString($data->xmlimport_orderitemtag);
			$xmlStockArray     = $this->explodeXMLFileString($data->xmlimport_stocktag);
			$xmlPrdextArray    = $this->explodeXMLFileString($data->xmlimport_prdextrafieldtag);
		}

		$content     = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_NOCDATA);
		$mainelement = "";

		foreach ($content as $key => $val)
		{
			$mainelement = $key;
			break;
		}

		$resultarray = array();

		if (strtolower($mainelement) == strtolower($data->element_name))
		{
			foreach ($content->$mainelement AS $mainelementval)
			{
				$row = array();
				$j   = 0;

				foreach ($mainelementval AS $mainkey => $mainvalue) // Main element Array Start
				{
					if (!empty($mainvalue->children()))
					{
						$subrow     = array();
						$subelement = "";

						if (strtolower($mainkey) == strtolower($data->billing_element_name)) // Billing element Array Start
						{
							$subelement = $data->billing_element_name;
							$l          = 0;

							foreach ($mainvalue->children() AS $subkey => $subvalue)
							{
								$resultbillingarray[$l] = $subkey;

								if ($isImport == 0)
								{
									$subrow[$subkey] = (string) $subvalue;
								}

								elseif ($isImport == 1 && trim($xmlBillingArray[$l][1]) != "" && $xmlBillingArray[$l][2] == 1)
								{
									$subrow[$xmlBillingArray[$l][1]] = (string) $subvalue;
								}

								$l++;
							}
						}
						elseif (strtolower($mainkey) == strtolower($data->shipping_element_name)) // Shipping element Array Start
						{
							$subelement = $data->shipping_element_name;
							$l          = 0;

							foreach ($mainvalue->children() AS $subkey => $subvalue)
							{
								$resulshippingtarray[$l] = $subkey;

								if ($isImport == 0)
								{
									$subrow[$subkey] = (string) $subvalue;
								}

								elseif ($isImport == 1 && trim($xmlShippingArray[$l][1]) != "" && $xmlShippingArray[$l][2] == 1)
								{
									$subrow[$xmlShippingArray[$l][1]] = (string) $subvalue;
								}

								$l++;
							}
						}
						elseif (strtolower($mainkey) == strtolower($data->stock_element_name)
							|| strtolower(substr($mainkey, 0, -1)) == strtolower($data->stock_element_name)) // Stock element Array Start
						{
							$subelement = $data->stock_element_name;
							$l          = 0;

							foreach ($mainvalue->children() AS $subelementval)
							{
								$k = 0;

								foreach ($subelementval AS $subkey => $subvalue)
								{
									$resultstockarray[$k] = $subkey;

									if ($isImport == 0)
									{
										$subrow[$l][$subkey] = (string) $subvalue;
									}
									elseif ($isImport == 1 && trim($xmlStockArray[$k][1]) != "" && $xmlStockArray[$k][2] == 1)
									{
										$subrow[$l][$xmlStockArray[$k][1]] = (string) $subvalue;
									}

									$k++;
								}

								$l++;
							}
						}
						elseif (strtolower($mainkey) == strtolower($data->prdextrafield_element_name)
							|| strtolower(substr($mainkey, 0, -1)) == strtolower($data->prdextrafield_element_name)) // Product Extra field element Array Start
						{
							$subelement = $data->prdextrafield_element_name;
							$l          = 0;

							foreach ($mainvalue->children() AS $subelementval)
							{
								$k = 0;

								foreach ($subelementval AS $subkey => $subvalue)
								{
									$resultprdextarray[$k] = $subkey;

									if ($isImport == 0)
									{
										$subrow[$l][$subkey] = (string) $subvalue;
									}
									elseif ($isImport == 1 && trim($xmlPrdextArray[$k][1]) != "" && $xmlPrdextArray[$k][2] == 1)
									{
										$subrow[$l][$xmlPrdextArray[$k][1]] = (string) $subvalue;
									}

									$k++;
								}

								$l++;
							}
						}
						elseif (strtolower($mainkey) == strtolower($data->orderitem_element_name) || strtolower(substr($mainkey, 0, -1)) == strtolower($data->orderitem_element_name)) // Order item element Array Start
						{
							$subelement = $data->orderitem_element_name;
							$l          = 0;

							foreach ($mainvalue->children() AS $subelementval)
							{
								$k = 0;

								foreach ($subelementval AS $subkey => $subvalue)
								{
									$resultorderitemarray[$k] = $subkey;

									if ($isImport == 0)
									{
										$subrow[$l][$subkey] = (string) $subvalue;
									}
									elseif ($isImport == 1 && trim($xmlOrderitemArray[$k][1]) != "" && $xmlOrderitemArray[$k][2] == 1)
									{
										$subrow[$l][$xmlOrderitemArray[$k][1]] = (string) $subvalue;
									}

									$k++;
								}

								$l++;
							}
						}

						if ($subelement != "")
						{
							$row[$subelement] = $subrow;
						}
					}
					else
					{
						$resultsectionarray[$j] = $mainkey;

						if ($isImport == 0)
						{
							$row[$mainkey] = (string) $mainvalue;
						}

						elseif ($isImport == 1 && trim($xmlFileArray[$j][1]) != "" && $xmlFileArray[$j][2] == 1)
						{
							$row[$xmlFileArray[$j][1]] = (string) $mainvalue;
						}
					}

					$j++;
				}

				$resultarray[] = $row;
			}
		}

		$result['xmlarray']          = $resultarray;
		$result['xmlsectionarray']   = $resultsectionarray;
		$result['xmlbillingarray']   = $resultbillingarray;
		$result['xmlshippingarray']  = $resulshippingtarray;
		$result['xmlorderitemarray'] = $resultorderitemarray;
		$result['xmlstockarray']     = $resultstockarray;
		$result['xmlprdextarray']    = $resultprdextarray;

		return $result;
	}

	/**
	 * @param   integer $xmlImportId Xml import id
	 *
	 * @return  boolean
	 */
	public function importXMLFile($xmlImportId = 0)
	{
		$db            = JFactory::getDbo();
		$xmlimportdata = $this->getXMLImportInfo($xmlImportId);

		if (count($xmlimportdata) <= 0)
		{
			return false; // Import record not exists
		}

		$destpath = JPATH_SITE . "/components/com_redshop/assets/xmlfile/import/";

		if (($xmlimportdata->filename == "" || !JFile::exists($destpath . $xmlimportdata->filename)) && $xmlimportdata->published == 0)
		{
			return false;
		}

		$filedetail = $this->readXMLImportFile($destpath . $xmlimportdata->filename, $xmlimportdata, 1);
		$datalist   = $filedetail['xmlarray'];

		if (count($datalist) <= 0)
		{
			return false; // No data In imported xmlfile.So no need to write import file.
		}

		switch ($xmlimportdata->section_type)
		{
			case "product":
				for ($i = 0, $in = count($datalist); $i < $in; $i++)
				{
					$oldproduct_number = $datalist[$i]['product_number'];
					$update            = false;

					if (array_key_exists('product_number', $datalist[$i]) && $datalist[$i]['product_number'] != "")
					{
						if (\Redshop\Repositories\Product::getProductByNumber($datalist[$i]['product_number']))
						{
							$update                         = true;
							$datalist[$i]['product_number'] = $xmlimportdata->add_prefix_for_existing . $datalist[$i]['product_number'];
						}
					}

					if (array_key_exists('product_full_image', $datalist[$i]) && $datalist[$i]['product_full_image'] != "")
					{
						$src      = $datalist[$i]['product_full_image'];
						$filename = basename($src);
						$dest     = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $filename;

						$this->importRemoteImage($src, $dest);
						$datalist[$i]['product_full_image'] = $filename;
					}

					if (array_key_exists('product_thumb_image', $datalist[$i]) && $datalist[$i]['product_thumb_image'] != "")
					{
						$src      = $datalist[$i]['product_thumb_image'];
						$filename = basename($src);
						$dest     = REDSHOP_FRONT_IMAGES_RELPATH . "product/thumb/" . $filename;

						$this->importRemoteImage($src, $dest);
						$datalist[$i]['product_thumb_image'] = $filename;
					}

					// UPDATE EXISTING IF RECORD EXISTS
					if ($xmlimportdata->override_existing && $update)
					{
						$datalist[$i]['product_number'] = $oldproduct_number;

						$query      = "SELECT product_id FROM " . "#__redshop_product "
							. "WHERE product_number=" . $db->quote($oldproduct_number);
						$product_id = $db->setQuery($query)->loadResult();

						$prdarray = array();
						$catarray = array();

						while (list($key, $value) = each($datalist[$i]))
						{
							if (!is_array($value))
							{
								if ($key != "category_id" && $key != "category_name")
								{
									$prdarray[] = $key . "='" . addslashes($value) . "' ";
								}
								else
								{
									$catarray[$key] = addslashes($value);
								}
							}
							elseif (!empty($value))
							{
								for ($j = 0, $jn = count($value); $j < $jn; $j++)
								{
									if ($key == $xmlimportdata->stock_element_name)
									{
										if (isset($value[$j]['stockroom_name']))
										{
											$stockarray = array();

											while (list($subkey, $subvalue) = each($value[$j]))
											{
												$stockarray[] = $subkey . "='" . addslashes($subvalue) . "' ";
											}

											$stockstring = implode(", ", $stockarray);

											if (trim($stockstring) != "")
											{
												$query = "UPDATE " . "#__redshop_stockroom AS s "
													. ", " . "#__redshop_product_stockroom_xref AS sx "
													. ", " . "#__redshop_product AS p "
													. "SET $stockstring "
													. "WHERE sx.stockroom_id=s.stockroom_id "
													. "AND sx.product_id=p.product_id "
													. "AND p.product_number=" . $db->quote($oldproduct_number) . " "
													. "AND s.stockroom_name=" . $db->quote($value[$j]['stockroom_name']) . " ";

												$db->setQuery($query)->execute();
												$affected_rows = $db->getAffectedRows();

												if (!$affected_rows)
												{
													$query = "SELECT stockroom_id FROM " . "#__redshop_stockroom "
														. "WHERE stockroom_name=" . $db->quote($value[$j]['stockroom_name']) . "";
													$db->setQuery($query);
													$stockroom_id = $db->loadResult();

													if (!$stockroom_id)
													{
														$query = "INSERT IGNORE INTO " . "#__redshop_stockroom "
															. "(stockroom_name) VALUES (" . $db->quote($value[$j]['stockroom_name']) . ")";
														$db->setQuery($query);
														$db->execute();
														$stockroom_id = $db->insertid();
													}

													$query = "INSERT IGNORE INTO " . "#__redshop_product_stockroom_xref "
														. "(stockroom_id,product_id,quantity) VALUES (" . (int) $stockroom_id . "," . (int) $product_id . ",0)";

													$db->setQuery($query)->execute();

													$query = "UPDATE " . "#__redshop_stockroom AS s "
														. ", " . "#__redshop_product_stockroom_xref AS sx "
														. ", " . "#__redshop_product AS p "
														. "SET $stockstring "
														. "WHERE sx.stockroom_id=s.stockroom_id "
														. "AND sx.product_id=p.product_id "
														. "AND p.product_number=" . $db->quote($oldproduct_number) . " "
														. "AND s.stockroom_name=" . $db->quote($value[$j]['stockroom_name']) . " ";

													$db->setQuery($query)->execute();
												}
											}
										}
									}
									elseif ($key == $xmlimportdata->prdextrafield_element_name)
									{
										if (isset($value[$j]['fieldid']))
										{
											$prdextarray = array();

											while (list($subkey, $subvalue) = each($value[$j]))
											{
												$prdextarray[] = $subkey . "='" . addslashes($subvalue) . "' ";
											}

											$prdextstring = implode(", ", $prdextarray);

											if (trim($prdextstring) != "")
											{
												$query = "UPDATE " . "#__redshop_fields_data AS fa "
													. ", " . "#__redshop_product AS p "
													. "SET $prdextstring "
													. "WHERE p.product_id=fa.itemid "
													. "AND fa.section='1' "
													. "AND fa.fieldid=" . (int) $value[$j]['fieldid'] . " "
													. "AND p.product_number=" . $db->quote($oldproduct_number);

												$db->setQuery($query)->execute();
												$affected_rows = $db->getAffectedRows();

												if (!$affected_rows)
												{
													$query = "INSERT IGNORE INTO " . "#__redshop_fields_data "
														. "(fieldid,itemid,section) VALUES (" . $db->quote($value[$j]['fieldid']) . "," . (int) $product_id . ",1)";

													$db->setQuery($query)->execute();

													$query = "UPDATE " . "#__redshop_fields_data AS fa "
														. ", " . "#__redshop_product AS p "
														. "SET $prdextstring "
														. "WHERE p.product_id=fa.itemid "
														. "AND fa.section='1' "
														. "AND fa.fieldid=" . $db->quote($value[$j]['fieldid']) . " "
														. "AND p.product_number=" . $db->quote($oldproduct_number) . " ";

													$db->setQuery($query)->execute();
												}
											}
										}
									}
								}
							}
						}

						if (!empty($prdarray))
						{
							$upstring = implode(", ", $prdarray);
							$query    = "UPDATE " . "#__redshop_product "
								. "SET $upstring "
								. "WHERE product_number=" . $db->quote($oldproduct_number) . " ";

							$db->setQuery($query)->execute();
						}

						if (!empty($catarray))
						{
							$category_id = 0;

							if (isset($catarray['category_id']))
							{
								$category_id = $catarray['category_id'];
							}

							elseif (isset($catarray['category_name']))
							{
								$query = "SELECT category_id FROM " . "#__redshop_category "
									. "WHERE category_name=" . $db->quote($catarray['category_name']) . " ";

								$category_id = $db->setQuery($query)->loadResult();
							}

							if ($category_id == 0 && isset($catarray['category_name']) && $catarray['category_name'] != "")
							{
								$query = "INSERT IGNORE INTO " . "#__redshop_category "
									. "(category_name) VALUES (" . $db->quote($catarray['category_name']) . ")";

								$db->setQuery($query)->execute();
								$category_id = $db->insertid();

								$query = "INSERT IGNORE INTO " . "#__redshop_category_xref "
									. "(category_parent_id,category_child_id) "
									. "VALUES ('0', " . (int) $category_id . ")";

								$db->setQuery($query)->execute();
							}

							if ($category_id != 0)
							{
								$query = 'DELETE FROM ' . '#__redshop_product_category_xref '
									. "WHERE product_id=" . (int) $product_id . " "
									. "AND category_id=" . (int) $category_id . " ";

								$db->setQuery($query)->execute();

								$query = "INSERT IGNORE INTO " . "#__redshop_product_category_xref "
									. "(category_id,product_id) "
									. "VALUES (" . (int) $category_id . ", " . (int) $product_id . ")";

								$db->setQuery($query)->execute();
							}
						}
					}
					else
					{
						if (!empty($datalist[$i]['product_number']) && trim($datalist[$i]['product_name']) != "")
						{
							$prdkeysarray = array();
							$prdvalsarray = array();
							$catarray     = array();

							while (list($key, $value) = each($datalist[$i]))
							{
								if (!is_array($value))
								{
									if ($key != "category_id" && $key != "category_name")
									{
										$prdvalsarray[] = addslashes($value);
										$prdkeysarray[] = $key;
									}
									else
									{
										$catarray[$key] = addslashes($value);
									}
								}
							}

							if (!empty($prdkeysarray))
							{
								$fieldstring = implode(", ", $prdkeysarray);
								$valuestring = implode("', '", $prdvalsarray);
								$valuestring = "'" . $valuestring . "'";
								$query       = "INSERT IGNORE INTO " . "#__redshop_product "
									. "($fieldstring) VALUES ($valuestring)";

								$db->setQuery($query)->execute();
								$product_id = $db->insertid();

								foreach ($datalist[$i] AS $key => $value)
								{
									if (is_array($value))
									{
										for ($j = 0, $jn = count($value); $j < $jn; $j++)
										{
											if ($key == $xmlimportdata->stock_element_name)
											{
												if (isset($value[$j]['stockroom_name']))
												{
													$stockvalsarray = array();
													$stockkeysarray = array();

													while (list($subkey, $subvalue) = each($value[$j]))
													{
														if ($subkey == "quantity")
														{
															$stockvalsarray[] = addslashes($subvalue);
															$stockkeysarray[] = $subkey;
														}
													}

													$fieldstring = implode(", ", $stockkeysarray);
													$valuestring = implode("', '", $stockvalsarray);
													$valuestring = "'" . $valuestring . "'";

													if (trim($fieldstring) != "")
													{
														$query = "SELECT stockroom_id FROM " . "#__redshop_stockroom "
															. "WHERE stockroom_name=" . $db->quote($value[$j]['stockroom_name']) . "";

														$stockroom_id = $db->setQuery($query)->loadResult();

														if (!$stockroom_id)
														{
															$query = "INSERT IGNORE INTO " . "#__redshop_stockroom "
																. "(stockroom_name) VALUES (" . $db->quote($value[$j]['stockroom_name']) . ")";

															$db->setQuery($query)->execute();
															$stockroom_id = $db->insertid();
														}

														if ($stockroom_id)
														{
															$fieldstring .= ",stockroom_id,product_id";
															$valuestring .= "," . (int) $stockroom_id . ", " . (int) $product_id . "";

															$query = "INSERT IGNORE INTO " . "#__redshop_product_stockroom_xref "
																. "($fieldstring) VALUES ($valuestring)";

															$db->setQuery($query)->execute();
														}
													}
												}
											}
											elseif ($key == $xmlimportdata->prdextrafield_element_name)
											{
												if (isset($value[$j]['fieldid']))
												{
													$extvalsarray = array();
													$extkeysarray = array();

													while (list($subkey, $subvalue) = each($value[$j]))
													{
														if ($subkey != "itemid")
														{
															$extvalsarray[] = addslashes($subvalue);
															$extkeysarray[] = $subkey;
														}
													}

													$fieldstring = implode(", ", $extkeysarray);
													$valuestring = implode("', '", $extvalsarray);
													$valuestring = "'" . $valuestring . "'";

													if (trim($fieldstring) != "")
													{
														$fieldstring .= ",itemid,section";
														$valuestring .= "," . (int) $product_id . ", '1' ";
														$query       = "INSERT IGNORE INTO " . "#__redshop_fields_data "
															. "($fieldstring) VALUES ($valuestring)";

														$db->setQuery($query)->execute();
													}
												}
											}
										}
									}
								}

								if (!empty($catarray))
								{
									$category_id = 0;

									if (isset($catarray['category_id']))
									{
										$category_id = $catarray['category_id'];
									}

									elseif (isset($catarray['category_name']))
									{
										$query = "SELECT category_id FROM " . "#__redshop_category "
											. "WHERE category_name=" . $db->quote($catarray['category_name']) . " ";

										$category_id = $db->setQuery($query)->loadResult();
									}

									if ($category_id == 0 && isset($catarray['category_name']) && $catarray['category_name'] != "")
									{
										$query = "INSERT IGNORE INTO " . "#__redshop_category "
											. "(category_name) VALUES (" . $db->quote($catarray['category_name']) . ")";

										$db->setQuery($query)->execute();
										$category_id = $db->insertid();

										$query = "INSERT IGNORE INTO " . "#__redshop_category_xref "
											. "(category_parent_id,category_child_id) "
											. "VALUES ('0', " . (int) $category_id . ")";

										$db->setQuery($query)->execute();
									}

									if ($category_id != 0)
									{
										\Redshop\Repositories\Products::delete(array(
											'product_id'  => $product_id,
											'category_id' => $category_id
										));

										$query = "INSERT IGNORE INTO #__redshop_product_category_xref "
											. "(category_id,product_id) "
											. "VALUES (" . (int) $category_id . ", " . (int) $product_id . ")";

										$db->setQuery($query)->execute();
									}
								}
							}
						}
					}
				}
				break;
			case "order":
				for ($i = 0, $in = count($datalist); $i < $in; $i++)
				{
					$oldorder_number = $datalist[$i]['order_number'];
					$update          = false;

					if (array_key_exists('order_number', $datalist[$i]) && $datalist[$i]['order_number'] != "")
					{
						if (\Redshop\Repositories\Order::getOrderByNumber($datalist[$i]['order_number']))
						{
							$update                       = true;
							$datalist[$i]['order_number'] = $xmlimportdata->add_prefix_for_existing . $datalist[$i]['order_number'];
						}
					}

					// UPDATE EXISTING IF RECORD EXISTS
					if ($xmlimportdata->override_existing && $update)
					{
						$datalist[$i]['order_number'] = $oldorder_number;
						$ordarray                     = array();

						while (list($key, $value) = each($datalist[$i]))
						{
							if (!is_array($value))
							{
								$ordarray[] = $key . "='" . $value . "' ";
							}

							elseif (!empty($value))
							{
								if ($key == $xmlimportdata->orderitem_element_name)
								{
									for ($j = 0, $jn = count($value); $j < $jn; $j++)
									{
										if (isset($value[$j]['order_item_sku']))
										{
											$oitemarray = array();

											while (list($subkey, $subvalue) = each($value[$j]))
											{
												$oitemarray[] = $subkey . "='" . $subvalue . "' ";
											}

											$oitemstring = implode(", ", $oitemarray);

											if (trim($oitemstring) != "")
											{
												$query = "UPDATE " . "#__redshop_order_item AS oi "
													. ", " . "#__redshop_orders AS o "
													. "SET $oitemstring "
													. "WHERE oi.order_id=o.order_id "
													. "AND o.order_number=" . $db->quote($oldorder_number) . " "
													. "AND oi.order_item_sku=" . $db->quote($value[$j]['order_item_sku']) . " ";

												$db->setQuery($query)->execute();
											}
										}
									}
								}
								elseif ($key == $xmlimportdata->billing_element_name)
								{
									$billingarray = array();

									while (list($subkey, $subvalue) = each($value))
									{
										$billingarray[] = $subkey . "='" . $subvalue . "' ";
									}

									$billingstring = implode(", ", $billingarray);

									if (trim($billingstring) != "")
									{
										$query = "UPDATE " . "#__redshop_order_users_info AS ou "
											. ", " . "#__redshop_orders AS o "
											. "SET $billingstring "
											. "WHERE ou.order_id=o.order_id "
											. "AND o.order_number=" . $db->quote($oldorder_number) . " "
											. "AND ou.address_type='BT' ";

										$db->setQuery($query)->execute();
									}
								}
								elseif ($key == $xmlimportdata->shipping_element_name)
								{
									$shippingarray = array();

									while (list($subkey, $subvalue) = each($value))
									{
										$shippingarray[] = $subkey . "='" . $subvalue . "' ";
									}

									$shippingstring = implode(", ", $shippingarray);

									if (trim($shippingstring) != "")
									{
										$query = "UPDATE " . "#__redshop_order_users_info AS ou "
											. ", " . "#__redshop_orders AS o "
											. "SET $shippingstring "
											. "WHERE ou.order_id=o.order_id "
											. "AND o.order_number=" . $db->quote($oldorder_number) . " "
											. "AND ou.address_type='ST' ";

										$db->setQuery($query)->execute();
									}
								}
							}
						}

						if (!empty($ordarray))
						{
							$upstring = implode(", ", $ordarray);
							$query    = "UPDATE " . "#__redshop_orders "
								. "SET $upstring "
								. "WHERE order_number=" . $db->quote($oldorder_number) . " ";

							$db->setQuery($query)->execute();
						}
					}
					else
					{
						if (!empty($datalist[$i]['order_number']))
						{
							$ordkeysarray = array();
							$ordvalsarray = array();

							while (list($key, $value) = each($datalist[$i]))
							{
								if (!is_array($value))
								{
									$ordvalsarray[] = $value;
									$ordkeysarray[] = $key;
								}
							}

							if (!empty($ordkeysarray))
							{
								$fieldstring = implode(", ", $ordkeysarray);
								$valuestring = implode("', '", $ordvalsarray);
								$valuestring = "'" . $valuestring . "'";
								$query       = "INSERT IGNORE INTO " . "#__redshop_orders "
									. "($fieldstring) VALUES ($valuestring)";

								$db->setQuery($query)->execute();
								$order_id = $db->insertid();

								foreach ($datalist[$i] AS $key => $value)
								{
									if (is_array($value))
									{
										if ($key == $xmlimportdata->orderitem_element_name)
										{
											for ($j = 0, $jn = count($value); $j < $jn; $j++)
											{
												if (isset($value[$j]['order_item_sku']))
												{
													$oitemvalsarray = array();
													$oitemkeysarray = array();

													while (list($subkey, $subvalue) = each($value[$j]))
													{
														if ($subkey != "order_id")
														{
															$oitemvalsarray[] = $subvalue;
															$oitemkeysarray[] = $subkey;
														}
													}

													$fieldstring = implode(", ", $oitemkeysarray);
													$valuestring = implode("', '", $oitemvalsarray);
													$valuestring = "'" . $valuestring . "'";

													if (trim($fieldstring) != "")
													{
														$fieldstring .= ",order_id";
														$valuestring .= ",'" . $order_id . "'";

														$query = "INSERT IGNORE INTO " . "#__redshop_order_item "
															. "($fieldstring) VALUES ($valuestring)";

														$db->setQuery($query)->execute();
													}
												}
											}
										}
										elseif ($key == $xmlimportdata->billing_element_name)
										{
											$billvalsarray = array();
											$billkeysarray = array();

											while (list($subkey, $subvalue) = each($value))
											{
												if ($subkey != "order_id")
												{
													$billvalsarray[] = $subvalue;
													$billkeysarray[] = $subkey;
												}
											}

											$fieldstring = implode(", ", $billkeysarray);
											$valuestring = implode("', '", $billvalsarray);
											$valuestring = "'" . $valuestring . "'";

											if (trim($fieldstring) != "")
											{
												$fieldstring .= ",order_id";
												$valuestring .= ",'" . $order_id . "'";

												$query = "INSERT IGNORE INTO " . "#__redshop_order_users_info "
													. "($fieldstring) VALUES ($valuestring)";

												$db->setQuery($query)->execute();
											}
										}
										elseif ($key == $xmlimportdata->shipping_element_name)
										{
											$shippvalsarray = array();
											$shippkeysarray = array();

											while (list($subkey, $subvalue) = each($value[$j]))
											{
												if ($subkey != "order_id")
												{
													$shippvalsarray[] = $subvalue;
													$shippkeysarray[] = $subkey;
												}
											}

											$fieldstring = implode(", ", $shippkeysarray);
											$valuestring = implode("', '", $shippvalsarray);
											$valuestring = "'" . $valuestring . "'";

											if (trim($fieldstring) != "")
											{
												$fieldstring .= ",order_id";
												$valuestring .= ",'" . $order_id . "'";

												$query = "INSERT IGNORE INTO " . "#__redshop_order_users_info "
													. "($fieldstring) VALUES ($valuestring)";

												$db->setQuery($query)->execute();
											}
										}
									}
								}
							}
						}
					}
				}
				break;
			default:
				return false;
		}

		$this->insertXMLImportlog($xmlImportId, $xmlimportdata->filename);

		return true;
	}

	public function getProductList($xmlarray = array(), $xmlExport = array())
	{
		if (empty($xmlarray))
		{
			return array();
		}

		$db       = JFactory::getDbo();
		$list     = array();
		$field    = array();
		$strfield = "";

		foreach ($xmlarray AS $key => $value)
		{
			if ($key == "category_name")
			{
				$field[] = "c." . $key . " AS " . $value;
			}
			elseif ($key == "product_price")
			{
				$field[] = "if(p.product_on_sale='1' and ((p.discount_stratdate = 0 and p.discount_enddate=0)
					or (p.discount_stratdate <= UNIX_TIMESTAMP() and p.discount_enddate>=UNIX_TIMESTAMP())), p.discount_price, p."
					. $key . ") AS " . $value;
			}
			elseif ($key == "name") // Start Code for display manufacture name
			{
				$field[] = "m." . $key . " AS " . $value;
			}

			elseif ($key == "link") // Start Code for display product_url
			{
				$field[] = "m.manufacturer_email AS link ";
			}

			elseif ($key == "delivertime") // Start Code for display delivertime
			{
				$field[] = "s.max_del_time AS delivertime ";
			}

			elseif ($key == "pickup") // Start Code for display pickup
			{
				$field[] = "m.manufacturer_email AS pickup ";
			}

			elseif ($key == "charge") // Start Code for display charges
			{
				$field[] = "m.manufacturer_email AS charge ";
			}

			elseif ($key == "freight") // Start Code for display freight
			{
				$field[] = "m.manufacturer_email AS freight ";
			}

			else
			{
				$field[] = "p." . $key . " AS " . $value;
			}
		}

		if (!empty($field))
		{
			$strfield = implode(", ", $field);
		}

		$andcat = ($xmlExport->xmlexport_on_category != "") ? "AND c.category_id IN ($xmlExport->xmlexport_on_category) " : "";

		if ($strfield != "")
		{
			$query = "SELECT " . $strfield . ", p.product_id FROM " . "#__redshop_product AS p "
				. "LEFT JOIN " . "#__redshop_product_category_xref AS x ON x.product_id=p.product_id "
				. "LEFT JOIN " . "#__redshop_category AS c ON c.category_id=x.category_id "
				. "LEFT JOIN " . "#__redshop_manufacturer AS m ON m.manufacturer_id=p.manufacturer_id  "
				. "LEFT JOIN " . "#__redshop_product_stockroom_xref AS sx ON sx.product_id=p.product_id "
				. "LEFT JOIN " . "#__redshop_stockroom AS s ON s.stockroom_id =sx.stockroom_id  "
				. "WHERE p.published=1 "
				. $andcat
				. "GROUP BY p.product_id "
				. "ORDER BY p.product_id ASC ";
			$list  = $db->setQuery($query)->loadAssocList();
		}

		return $list;
	}

	public function getOrderList($xmlArray = array())
	{
		if (empty($xmlArray))
		{
			return array();
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		foreach ($xmlArray AS $key => $value)
		{
			$field[] = $key . " AS " . $value;
			$query->select($db->quoteName($key, $value));
		}

		$query->select($db->quoteName('order_id'))
			->from($db->quoteName('#__redshop_orders'))
			->order($db->quoteName('order_id'));

		return $db->setQuery($query)->loadObjectlist();
	}

	public function getOrderUserInfoList($xmlarray = array(), $orderId = 0, $addresstype = "BT")
	{
		if (empty($xmlarray))
		{
			return array();
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		foreach ($xmlarray AS $key => $value)
		{
			$query->select($db->quoteName($key, $value));
		}

		$query->from($db->quoteName('#__order_users_info'))
			->where($db->quoteName('address_type') . ' = ' . $db->quoteName($addresstype))
			->where($db->quoteName('order_id') . (int) $orderId);

		return $db->setQuery($query)->loadObjectList();
	}

	public function getOrderItemList($xmlArray = array(), $orderId = 0)
	{
		if (empty($xmlArray))
		{
			return array();
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		foreach ($xmlArray AS $key => $value)
		{
			$query->select($db->quoteName($key, $value));
		}

		$query->from($db->quoteName('#__redshop_order_item'))
			->where($db->quoteName('order_id') . (int) $orderId)
			->order($db->quoteName('order_item_id'));

		return $db->setQuery($query)->loadObjectList();
	}

	public function getStockroomList($xmls = array(), $product_id = 0)
	{
		$db = JFactory::getDbo();

		if (empty($xmls))
		{
			return array();
		}

		$field = array();

		foreach ($xmls AS $key => $value)
		{
			$field[] = $key . " AS " . $value;
		}

		if (empty($field))
		{
			return array();
		}

		$query = "SELECT " . implode(", ", $field) . " FROM " . "#__redshop_stockroom AS s "
			. "LEFT JOIN " . "#__redshop_product_stockroom_xref AS sx ON s.stockroom_id=sx.stockroom_id "
			. "WHERE product_id=" . (int) $product_id . " "
			. "ORDER BY s.stockroom_id ASC ";
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/**
	 * @param   array   $xmls Xml
	 * @param   integer $sectionId
	 * @param   integer $fieldSection
	 *
	 * @return  array|mixed
	 *
	 */
	public function getExtraFieldList($xmls = array(), $sectionId = 0, $fieldSection = 0)
	{
		if (empty($xmls))
		{
			return array();
		}

		$db    = JFactory::getDbo();
		$field = array();

		foreach ($xmls AS $key => $value)
		{
			$field[] = $db->qn($key, $value);
		}

		if (empty($field))
		{
			return array();
		}

		$query = $db->getQuery(true)
			->select($field)
			->select($db->qn('f.name', 'name'))
			->from($db->qn('#__redshop_fields_data', 'fd'))
			->innerjoin($db->qn('#__redshop_fields', 'f') . ' ON fd.fieldid = f.id')
			->where($db->qn('fd.itemid') . ' = ' . (int) $sectionId)
			->where($db->qn('fd.section') . ' = ' . (int) $fieldSection);

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * @param   string $src  Source
	 * @param   string $dest Source
	 */
	public function importRemoteImage($src, $dest)
	{
		\Redshop\Environment\Remote\Curl::downloadFile($src, $dest);
	}
}
