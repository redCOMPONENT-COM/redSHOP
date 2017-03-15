<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       __DEPLOY_VERSION__
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

/**
 * Class Redshop Helper for Xml
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperXml
{
	/**
	 * Get Section Type List
	 *
	 * @return  array  Array of HTML section list
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getSectionTypeList()
	{
		$section = array();

		$section[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT'));
		$section[] = JHTML::_('select.option', 'product', JText::_('COM_REDSHOP_PRODUCT'));
		$section[] = JHTML::_('select.option', 'order', JText::_('COM_REDSHOP_ORDER'));

		return $section;
	}

	/**
	 * Get Section Type Name
	 *
	 * @param   string  $value  Value to get section type name
	 *
	 * @return  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getSectionTypeName($value = '')
	{
		switch ($value)
		{
			case 'product':
				return JText::_('COM_REDSHOP_PRODUCT');
			case 'order':
				return JText::_('COM_REDSHOP_ORDER');
		}
	}

	/**
	 * Get Synchornization Interval List
	 *
	 * @return  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getSynchIntervalList()
	{
		$section = array();

		$section[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$section[] = JHTML::_('select.option', 24, JText::_('COM_REDSHOP_24_HOURS'));
		$section[] = JHTML::_('select.option', 12, JText::_('COM_REDSHOP_12_HOURS'));
		$section[] = JHTML::_('select.option', 6, JText::_('COM_REDSHOP_6_HOURS'));

		return $section;
	}

	/**
	 * Get section column list
	 *
	 * @param   string  $section       Section
	 * @param   string  $childSection  Child section
	 *
	 * @return  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getSectionColumnList($section = "", $childSection = "")
	{
		$db = JFactory::getDbo();

		$cols = array();
		$catcol = array();
		$table = "";

		switch ($section)
		{
			case 'product':
				$table = "product";

				switch ($childSection)
				{
					case "stockdetail":
						$query->getQuery(true);
						$table = "stockroom";
						$q = "SHOW COLUMNS FROM #__redshop_product_stockroom_xref";
						$db->setQuery($q);
						$cat = $db->loadObjectList();

						for ($i = 0, $in = count($cat); $i < $in; $i++)
						{
							if ($cat[$i]->Field == "quantity")
							{
								$catcol[] = $cat[$i];
							}
						}

						break;
					case "prdextrafield":

						$table = "";
						$q = "SHOW COLUMNS FROM #__redshop_fields_data";
						$db->setQuery($q);
						$cat = $db->loadObjectList();

						for ($i = 0, $in = count($cat); $i < $in; $i++)
						{
							if ($cat[$i]->Field != "user_email" && $cat[$i]->Field != "section")
							{
								$catcol[] = $cat[$i];
							}
						}

						break;
					default:
						$table = "product";
						$q = "SHOW COLUMNS FROM #__redshop_category";
						$db->setQuery($q);
						$cat = $db->loadObjectList();

						for ($i = 0, $in = count($cat); $i < $in; $i++)
						{
							if ($cat[$i]->Field == "category_name")
							{
								$catcol[] = $cat[$i];
							}
							elseif ($cat[$i]->Field == "category_description")
							{
								$catcol[] = $cat[$i];
							}
							// Start Code for display product_url
							elseif ($cat[$i]->Field == "category_template")
							{
								$cat[$i]->Field = "link";
								$catcol[] = $cat[$i];
							}
							// Start Code for display delivertime
							elseif ($cat[$i]->Field == "category_thumb_image")
							{
								$cat[$i]->Field = "delivertime";
								$catcol[] = $cat[$i];
							}
							// Start Code for display pickup
							elseif ($cat[$i]->Field == "category_full_image")
							{
								$cat[$i]->Field = "pickup";
								$catcol[] = $cat[$i];
							}
							// Start Code for display charges
							elseif ($cat[$i]->Field == "category_back_full_image")
							{
								$cat[$i]->Field = "charge";
								$catcol[] = $cat[$i];
							}
							// Start Code for display freight
							elseif ($cat[$i]->Field == "category_pdate")
							{
								$cat[$i]->Field = "freight";
								$catcol[] = $cat[$i];
							}
						}

						// Start Code for display manufacturer name field
						$q = "SHOW COLUMNS FROM #__redshop_manufacturer";
						$db->setQuery($q);
						$cat = $db->loadObjectList();

						for ($i = 0, $in = count($cat); $i < $in; $i++)
						{
							if ($cat[$i]->Field == "manufacturer_name")
							{
								$catcol[] = $cat[$i];
							}
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

		if ($section != "" && $table != "")
		{
			$q = "SHOW COLUMNS FROM #__redshop_" . $table;
			$db->setQuery($q);
			$cols = $db->loadObjectList();
		}

		$cols = array_merge($cols, $catcol);

		for ($i = 0, $in = count($cols); $i < $in; $i++)
		{
			if (strtoupper($cols[$i]->Key) == "PRI")
			{
				unset($cols[$i]);
			}
		}

		sort($cols);

		return $cols;
	}

	/**
	 * Get Synchronization Interval Name
	 *
	 * @param   integer  $value  Decimal value for hours
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getSynchIntervalName($value = 0)
	{
		switch ($value)
		{
			case 24:
				return JText::_('COM_REDSHOP_24_HOURS');
			case 12:
				return JText::_('COM_REDSHOP_12_HOURS');
			case 6:
				return JText::_('COM_REDSHOP_6_HOURS');
		}
	}

	/**
	 * Get Xml File Tag
	 *
	 * @param   string  $fieldName   Field name
	 * @param   string  $xmlFileTag  Xml File Tag
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getXmlFileTag($fieldName = '', $xmlFileTag = '')
	{
		$result = "";
		$update = 1;

		if ($xmlFileTag != "")
		{
			$field = explode(";", $xmlFileTag);

			for ($i = 0, $in = count($field); $i < $in; $i++)
			{
				$value = explode("=", $field[$i]);

				if ($value[0] == $fieldName)
				{
					$result = trim($value[1]);

					if (isset($value[2]))
					{
						$update = $value[2];
					}
					else
					{
						$update = 0;
					}

					break;
				}
			}
		}

		return array($result, $update);
	}

	/**
	 * Explode Xml file string
	 *
	 * @param   string  $xmlFileTag  String to explode
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function explodeXmlFileString($xmlFileTag = "")
	{
		$value = array();

		if ($xmlFileTag != "")
		{
			$field = explode(";", $xmlFileTag);

			for ($i = 0, $in = count($field); $i < $in; $i++)
			{
				$value[$i] = explode("=", $field[$i]);
			}
		}

		return $value;
	}

	/**
	 * Get Xml Export Info
	 *
	 * @param   integer  $xmlExportId  ID of xml to export
	 *
	 * @return  object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getXmlExportInfo($xmlExportId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->qn('#__redshop_xml_export', 'x'))
			->where($db->qn('x.xmlexport_id') . ' = ' . (int) $xmlExportId);

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Get Xml export IP address
	 *
	 * @param   integer  $xmlExportId  ID of xml to export IP address
	 *
	 * @return  object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getXmlExportIpAddress($xmlExportId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->qn('#__redshop_xml_export_ipaddress', 'x'))
			->where($db->qn('x.xmlexport_id') . ' = ' . (int) $xmlExportId);

		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	/**
	 * Insert file name into xml export Log with id
	 *
	 * @param   integer  $xmlExportId  Xml Export ID to insert
	 * @param   string   $fileName     File name to insert
	 *
	 * @return void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function insertXmlExportLog($xmlExportId = 0, $fileName = "")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->insert($db->qn('#__redshop_xml_export_log'))
			->columns($db->qn(array('xmlexport_id', 'xmlexport_filename', 'xmlexport_date')))
			->values(implode(',', array((int) $xmlExportId, $db->quote($fileName), $db->quote(time()))));

		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Update file name in xml_export
	 *
	 * @param   integer  $xmlExportId  Xml export ID to update
	 * @param   string   $fileName     New file name to set
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	puBlic static function updateXmlExportFilename($xmlExportId = 0, $fileName = "")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->update($db->qn('#__redshop_xml_export'))
			->set($db->qn('filename') . ' = ' . $db->quote($fileName))
			->where($db->qn('xmlexport_id') . ' = ' . (int) $xmlExportId);

		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Get Xml import info
	 *
	 * @param   integer  $xmlImportId  Description of Xml import info
	 *
	 * @return  object
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function getXmlImportInfo($xmlImportId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->qn('#__redshop_xml_import'))
			->where($db->qn('xmlimport_id') . ' = ' . (int) $xmlImportId);

		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Insert Xml import log
	 *
	 * @param   integer  $xmlImportId  Xml import log ID to insert
	 * @param   string   $fileName     File name to insert
	 *
	 * @return void
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function insertXmlImportlog($xmlImportId = 0, $fileName = "")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->insert($db->qn('#__redshop_xml_import_log'))
			->columns($db->qn(array('xmlimport_id', 'xmlimport_filename', 'xmlimport_date')))
			->values(implode(',', array((int) $xmlImportId, $db->quote($fileName), (int) time())));

		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Update Xml Import file name
	 *
	 * @param   integer  $xmlImportId  Xml Import ID
	 * @param   string   $fileName     File name to update
	 *
	 * @return void
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function updateXmlImportFileName($xmlImportId = 0, $fileName = "")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->update($db->qn('#__redshop_xml_import'))
			->set($db->qn('filename') . ' = ' . $db->quote($fileName))
			->where($db->qn('xmlimport_id') . ' = ' . (int) $xmlImportId);

		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Write Xml Export to file
	 *
	 * @param   integer  $xmlExportId  Xml Export ID
	 *
	 * @return  string   File name
	 *
	 * @since	__DEPLOY_VERSION__
	 */
	public static function writeXmlExportFile($xmlExportId = 0)
	{
		$config   = Redconfiguration::getInstance();
		$shipping = shipping::getInstance();

		$xmlArray      = array();
		$xmlExportData = self::getXmlExportInfo($xmlExportId);

		if (count($xmlExportData) <= 0)
		{
			return false;
		}

		$destPath = JPATH_SITE . "/components/com_redshop/assets/xmlfile/export/";
		$section = $xmlExportData->section_type;
		$columns = self::getSectionColumnList($section, "orderdetail");

		for ($i = 0, $in = count($columns); $i < $in; $i++)
		{
			$tag = self::getXmlFileTag($columns[$i]->Field, $xmlExportData->xmlexport_filetag);

			if ($tag[0] != "")
			{
				$xmlArray[$columns[$i]->Field] = $tag[0];
			}
		}

		$dataList          = array();
		$billingList       = array();
		$shippingList      = array();
		$orderItemList     = array();
		$stockList         = array();
		$prdExtrafieldList = array();
		$xmlBilling        = array();
		$xmlShipping       = array();
		$xmlOrderItem      = array();
		$xmlStock          = array();
		$xmlPrdExtrafield  = array();
		$prdFullImage      = "";
		$prdThmbImage      = "";

		switch ($section)
		{
			case "product":
				if (array_key_exists("product_full_image", $xmlArray))
				{
					$prdFullImage = $xmlArray['product_full_image'];
				}

				if (array_key_exists("product_thumb_image", $xmlArray))
				{
					$prdThmbImage = $xmlArray['product_thumb_image'];
				}

				$dataList = self::getProductList($xmlArray, $xmlExportData);

				$columns  = self::getSectionColumnList($section, "stockdetail");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = self::getXmlFileTag($columns[$i]->Field, $xmlExportData->xmlexport_stocktag);

					if ($tag[0] != "")
					{
						$xmlStock[$columns[$i]->Field] = $tag[0];
					}
				}

				$columns = self::getSectionColumnList($section, "prdextrafield");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = self::getXmlFileTag($columns[$i]->Field, $xmlExportData->xmlexport_prdextrafieldtag);

					if ($tag[0] != "")
					{
						$xmlPrdExtrafield[$columns[$i]->Field] = $tag[0];
					}
				}
				break;
			case "order":
				$dataList = self::getOrderList($xmlArray);

				$columns = self::getSectionColumnList($section, "billingdetail");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = self::getXmlFileTag($columns[$i]->Field, $xmlExportData->xmlexport_billingtag);

					if ($tag[0] != "")
					{
						$xmlBilling[$columns[$i]->Field] = $tag[0];
					}
				}

				$columns = self::getSectionColumnList($section, "shippingdetail");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = self::getXmlFileTag($columns[$i]->Field, $xmlExportData->xmlexport_shippingtag);

					if ($tag[0] != "")
					{
						$xmlShipping[$columns[$i]->Field] = $tag[0];
					}
				}

				$columns = self::getSectionColumnList($section, "orderitem");

				for ($i = 0, $in = count($columns); $i < $in; $i++)
				{
					$tag = self::getXmlFileTag($columns[$i]->Field, $xmlExportData->xmlexport_orderitemtag);

					if ($tag[0] != "")
					{
						$xmlOrderItem[$columns[$i]->Field] = $tag[0];
					}
				}
				break;
			default:
				return false;
		}

		// Make the filename unique
		$fileName = RedshopHelperMedia::cleanFileName($xmlExportData->display_filename . '.xml');

		$xmlDocument = "<?xml version='1.0' encoding='utf-8'?>";

		if (trim($xmlExportData->element_name) == "")
		{
			$xmlExportData->element_name = $xmlExportData->parent_name . "_element";
		}

		$xmlDocument .= "<" . $xmlExportData->parent_name . ">";

		for ($i = 0, $in = count($dataList); $i < $in; $i++)
		{
			$productId = 0;

			if ($section == "product")
			{
				$productId = $dataList[$i]['product_id'];
			}

			$xmlBillingDocument  = "";
			$xmlShippingDocument = "";
			$xmlItemDocument     = "";
			$xmlStockDocument    = "";
			$xmlPrdExtraDocument = "";

			if (count($xmlBilling) > 0)
			{
				$billingList = self::getOrderUserInfoList($xmlBilling, $dataList[$i]->order_id);

				if (count($billingList) > 0)
				{
					$xmlBillingDocument .= "<$xmlExportData->billing_element_name>";

					while (list($prop, $val) = each($billingList))
					{
						$val = html_entity_decode($val);
						$xmlBillingDocument .= "<$prop><![CDATA[$val]]></$prop>";
					}

					$xmlBillingDocument .= "</$xmlExportData->billing_element_name>";
				}
			}

			if (count($xmlShipping) > 0)
			{
				$shippingList = self::getOrderUserInfoList($xmlShipping, $dataList[$i]->order_id, "ST");

				if (count($shippingList) > 0)
				{
					$xmlShippingDocument .= "<$xmlExportData->shipping_element_name>";

					while (list($prop, $val) = each($shippingList))
					{
						$val = html_entity_decode($val);
						$xmlShippingDocument .= "<$prop><![CDATA[$val]]></$prop>";
					}

					$xmlShippingDocument .= "</$xmlExportData->shipping_element_name>";
				}
			}

			if (count($xmlOrderItem) > 0)
			{
				$orderItemList = self::getOrderItemList($xmlOrderItem, $dataList[$i]->order_id);

				if (count($orderItemList) > 0)
				{
					$xmlItemDocument .= "<" . $xmlExportData->orderitem_element_name . "s>";

					for ($j = 0, $jn = count($orderItemList); $j < $jn; $j++)
					{
						$xmlItemDocument .= "<$xmlExportData->orderitem_element_name>";

						while (list($prop, $val) = each($orderItemList[$j]))
						{
							$val = html_entity_decode($val);
							$xmlItemDocument .= "<$prop><![CDATA[$val]]></$prop>";
						}

						$xmlItemDocument .= "</$xmlExportData->orderitem_element_name>";
					}

					$xmlItemDocument .= "</" . $xmlExportData->orderitem_element_name . "s>";
				}
			}

			if (count($xmlStock) > 0)
			{
				$stockList = self::getStockroomList($xmlStock, $productId);

				if (count($stockList) > 0)
				{
					$xmlStockDocument .= "<" . $xmlExportData->stock_element_name . "s>";

					for ($j = 0, $jn = count($stockList); $j < $jn; $j++)
					{
						$xmlStockDocument .= "<$xmlExportData->stock_element_name>";

						while (list($prop, $val) = each($stockList[$j]))
						{
							$val = html_entity_decode($val);
							$xmlStockDocument .= "<$prop><![CDATA[$val]]></$prop>";
						}

						$xmlStockDocument .= "</$xmlExportData->stock_element_name>";
					}

					$xmlStockDocument .= "</" . $xmlExportData->stock_element_name . "s>";
				}
			}

			if (count($xmlPrdExtrafield) > 0)
			{
				$prdExtrafieldList = self::getExtraFieldList($xmlPrdExtrafield, $productId, 1);

				if (count($prdExtrafieldList) > 0)
				{
					$xmlPrdExtraDocument .= "<" . $xmlExportData->prdextrafield_element_name . "s>";

					for ($j = 0, $jn = count($prdExtrafieldList); $j < $jn; $j++)
					{
						$xmlPrdExtraDocument .= "<$xmlExportData->prdextrafield_element_name>";

						while (list($prop, $val) = each($prdExtrafieldList[$j]))
						{
							$val = html_entity_decode($val);
							$xmlPrdExtraDocument .= "<$prop><![CDATA[$val]]></$prop>";
						}

						$xmlPrdExtraDocument .= "</$xmlExportData->prdextrafield_element_name>";
					}

					$xmlPrdExtraDocument .= "</" . $xmlExportData->prdextrafield_element_name . "s>";
				}
			}

			if ($section == "order" && $xmlItemDocument == "")
			{
			}
			else
			{
				$xmlDocument .= "<$xmlExportData->element_name>";

				while (list($prop, $val) = each($dataList[$i]))
				{
					$val = html_entity_decode($val);

					if ($prop == $prdFullImage && $val != "")
					{
						$val = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $val;
					}

					if ($prop == $prdThmbImage && $val != "")
					{
						$val = REDSHOP_FRONT_IMAGES_ABSPATH . "product/thumb/" . $val;
					}

					if ((isset($xmlArray['cdate']) && $prop == $xmlArray['cdate']) || (isset($xmlArray['mdate']) && $prop == $xmlArray['mdate']))
					{
						$val = $config->convertDateFormat($val);
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
							$val = JURI::root() . 'index.php?option=com_redshop&view=product&pid=' . $productId;
						}

						elseif ($prop == "pickup")
						{
							$val = "";
						}

						elseif ($prop == "charge")
						{
							$d['product_id'] = $productId;
							$srate = $shipping->getDefaultShipping_xmlexport($d);
							$val1 = $srate['shipping_rate'];
							$val = round($val1);
						}

						elseif ($prop == "freight")
						{
							$d['product_id'] = $productId;
							$srate = $shipping->getDefaultShipping_xmlexport($d);
							$val1 = $srate['shipping_rate'];
							$val = round($val1);
						}

						elseif ($prop == "delivertime")
						{
							$query = $db->getQuery(true);
							$query->select('*')
								->from($db->qn('#__redshop_stockroom', 's'))
								->leftJoin(
									$db->qn('#__redshop_product_stockroom_xref', 'sx')
									. ' ON ' .
									$db->qn('s.stockroom_id') . ' = ' . $db->qn('sx.stockroom_id')
								)
								->where($db->qn('product_id') . ' = ' . (int) $productId)
								->order($db->qn('s.stockroom_id') . ' ASC');

							$db->setQuery($query);
							$list = $db->loadObject();

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

						$xmlDocument .= "<$prop><![CDATA[$val]]></$prop>";
					}
				}

				$xmlDocument .= $xmlBillingDocument;
				$xmlDocument .= $xmlShippingDocument;
				$xmlDocument .= $xmlItemDocument;
				$xmlDocument .= $xmlStockDocument;
				$xmlDocument .= $xmlPrdExtraDocument;
				$xmlDocument .= "</" . $xmlExportData->element_name . ">";
			}
		}

		$xmlDocument .= "</" . $xmlExportData->parent_name . ">";

		/* Data in Variables ready to be written to an Xml file */
		$fp = fopen($destPath . $filEname, 'w');
		fwrite($fp, $xmlDocument);

		self::insertXmlExportlog($xmlExportId, $filEname);

		// Update new generated exported file in database record
		self::updateXmlExportFilename($xmlExportId, $filEname);

		return $filEname;
	}

	/**
	 * Write Xml Import to file
	 *
	 * @param   integer  $xmlImportId      Xml Import ID to write
	 * @param   string   $tmlXmlImportUrl  URL to write file
	 *
	 * @return  string   File name after writing
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function writeXmlImportFile($xmlImportId = 0, $tmlXmlImportUrl = "")
	{
		$destPath      = JPATH_SITE . "/components/com_redshop/assets/xmlfile/import/";
		$xmlImportData = self::getXmlImportInfo($xmlImportId);

		if (count($xmlImportData) <= 0)
		{
			// Import record not exists
			return false;
		}

		if ($tmlXmlImportUrl == "" && $xmlImportData->filename == "")
		{
			// No URL to import file
			return false;
		}

		if ($tmlXmlImportUrl != "")
		{
			$xmlImportData->xmlimport_url = $tmlXmlImportUrl;
		}
		else
		{
			$xmlImportData->xmlimport_url = $destPath . $xmlImportData->filename;
		}

		$fileDetail = self::readXmlImportFile($xmlImportData->xmlimport_url, $xmlImportData);
		$dataList   = $fileDetail['xmlarray'];

		if (count($dataList) <= 0)
		{
			// No data In imported xmlfile.So no need to write import file.
			return false;
		}

		// Make the filename unique
		$fileName    = RedshopHelperMedia::cleanFileName($xmlImportData->display_filename . ".xml");

		$xmlDocument = "<?xml version='1.0' encoding='utf-8'?>";
		$xmlDocument .= "<" . $xmlImportData->element_name . "s>";

		for ($i = 0, $in = count($dataList); $i < $in; $i++)
		{
			$xmlDocument .= "<" . $xmlImportData->element_name . ">";

			while (list($prop, $val) = each($dataList[$i]))
			{
				if (is_array($val))
				{
					$subDataList = $val;

					if (isset($subDataList[0]))
					{
						$xmlDocument .= "<" . $prop . ">";

						for ($j = 0, $jn = count($subDataList); $j < $jn; $j++)
						{
							$childElement = substr($prop, 0, -1);
							$xmlDocument .= "<" . $childElement . ">";

							while (list($subProp, $subVal) = each($subDataList[$j]))
							{
								$subVal = html_entity_decode($subVal);
								$xmlDocument .= "<$subProp><![CDATA[$subVal]]></$subProp>";
							}

							$xmlDocument .= "</" . $childElement . ">";
						}

						$xmlDocument .= "</" . $prop . ">";
					}

					elseif (count($subDataList) > 0)
					{
						$xmlDocument .= "<" . $prop . ">";

						while (list($subProp, $subVal) = each($subDataList))
						{
							$subVal = html_entity_decode($subVal);
							$xmlDocument .= "<$subProp><![CDATA[$subVal]]></$subProp>";
						}

						$xmlDocument .= "</" . $prop . ">";
					}
				}
				else
				{
					$val = html_entity_decode($val);
					$xmlDocument .= "<$prop><![CDATA[$val]]></$prop>";
				}
			}

			$xmlDocument .= "</" . $xmlImportData->element_name . ">";
		}

		$xmlDocument .= "</" . $xmlImportData->element_name . "s>";

		/* Data in Variables ready to be written to an Xml file */
		$fp = fopen($destPath . $fileName, 'w');
		fwrite($fp, $xmlDocument);

		// Update new generated imported file in database record
		self::updateXmlImportFilename($xmlImportId, $fileName);

		return $fileName;
	}

	/**
	 * Read Xml import files
	 *
	 * @param   string   $file      File to read
	 * @param   array    $data      Data for get explode
	 * @param   integer  $isImport  Is import or not
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function readXmlImportFile($file = "", $data = array(), $isImport = 0)
	{
		$resultXml       = array();
		$resultSection   = array();
		$resultBilling   = array();
		$resulShipping   = array();
		$resultOrderItem = array();
		$resultStock     = array();
		$resultPrdExt    = array();

		$xmlFile      = array();
		$xmlBilling   = array();
		$xmlShipping  = array();
		$xmlOrderitem = array();
		$xmlStock     = array();
		$xmlPrdext    = array();

		if ($isImport)
		{
			$xmlFile      = self::explodeXmlFileString($data->xmlimport_filetag);
			$xmlBilling   = self::explodeXmlFileString($data->xmlimport_billingtag);
			$xmlShipping  = self::explodeXmlFileString($data->xmlimport_shippingtag);
			$xmlOrderitem = self::explodeXmlFileString($data->xmlimport_orderitemtag);
			$xmlStock     = self::explodeXmlFileString($data->xmlimport_stocktag);
			$xmlPrdext    = self::explodeXmlFileString($data->xmlimport_prdextrafieldtag);
		}

		$content     = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_NOCDATA);
		$mainElement = "";

		foreach ($content as $key => $val)
		{
			$mainElement = $key;
			break;
		}

		if (strtolower($mainElement) == strtolower($data->element_name))
		{
			foreach ($content->$mainElement AS $mainElementVal)
			{
				$row = array();
				$j = 0;

				foreach ($mainElementVal AS $mainKey => $mainValue) // Main element Array Start
				{
					if (count($mainValue->children()) > 0)
					{
						$subRow     = array();
						$subElement = "";

						if (strtolower($mainKey) == strtolower($data->billing_element_name)) // Billing element Array Start
						{
							$subElement = $data->billing_element_name;
							$l = 0;

							foreach ($mainValue->children() AS $subKey => $subValue)
							{
								$resultBilling[$l] = $subKey;

								if ($isImport == 0)
								{
									$subRow[$subKey] = (string) $subValue;
								}

								elseif ($isImport == 1 && trim($xmlBilling[$l][1]) != "" && $xmlBilling[$l][2] == 1)
								{
									$subRow[$xmlBilling[$l][1]] = (string) $subValue;
								}

								$l++;
							}
						}
						elseif (strtolower($mainKey) == strtolower($data->shipping_element_name)) // Shipping element Array Start
						{
							$subElement = $data->shipping_element_name;
							$l = 0;

							foreach ($mainValue->children() AS $subKey => $subValue)
							{
								$resulShipping[$l] = $subKey;

								if ($isImport == 0)
								{
									$subRow[$subKey] = (string) $subValue;
								}

								elseif ($isImport == 1 && trim($xmlShipping[$l][1]) != "" && $xmlShipping[$l][2] == 1)
								{
									$subRow[$xmlShipping[$l][1]] = (string) $subValue;
								}

								$l++;
							}
						}
						elseif (strtolower($mainKey) == strtolower($data->stock_element_name)
							|| strtolower(substr($mainKey, 0, -1)) == strtolower($data->stock_element_name)) // Stock element Array Start
						{
							$subElement = $data->stock_element_name;
							$l = 0;

							foreach ($mainValue->children() AS $subElementval)
							{
								$k = 0;

								foreach ($subElementval AS $subKey => $subValue)
								{
									$resultStock[$k] = $subKey;

									if ($isImport == 0)
									{
										$subRow[$l][$subKey] = (string) $subValue;
									}
									elseif ($isImport == 1 && trim($xmlStock[$k][1]) != "" && $xmlStock[$k][2] == 1)
									{
										$subRow[$l][$xmlStock[$k][1]] = (string) $subValue;
									}

									$k++;
								}

								$l++;
							}
						}
						elseif (strtolower($mainKey) == strtolower($data->prdextrafield_element_name)
							|| strtolower(substr($mainKey, 0, -1)) == strtolower($data->prdextrafield_element_name)) // Product Extra field element Array Start
						{
							$subElement = $data->prdextrafield_element_name;
							$l = 0;

							foreach ($mainValue->children() AS $subElementval)
							{
								$k = 0;

								foreach ($subElementval AS $subKey => $subValue)
								{
									$resultPrdExt[$k] = $subKey;

									if ($isImport == 0)
									{
										$subRow[$l][$subKey] = (string) $subValue;
									}
									elseif ($isImport == 1 && trim($xmlPrdext[$k][1]) != "" && $xmlPrdext[$k][2] == 1)
									{
										$subRow[$l][$xmlPrdext[$k][1]] = (string) $subValue;
									}

									$k++;
								}

								$l++;
							}
						}
						elseif (strtolower($mainKey) == strtolower($data->orderitem_element_name) || strtolower(substr($mainKey, 0, -1)) == strtolower($data->orderitem_element_name)) // Order item element Array Start
						{
							$subElement = $data->orderitem_element_name;
							$l = 0;

							foreach ($mainValue->children() AS $subElementval)
							{
								$k = 0;

								foreach ($subElementval AS $subKey => $subValue)
								{
									$resultOrderItem[$k] = $subKey;

									if ($isImport == 0)
									{
										$subRow[$l][$subKey] = (string) $subValue;
									}
									elseif ($isImport == 1 && trim($xmlOrderitem[$k][1]) != "" && $xmlOrderitem[$k][2] == 1)
									{
										$subRow[$l][$xmlOrderitem[$k][1]] = (string) $subValue;
									}

									$k++;
								}

								$l++;
							}
						}

						if ($subElement != "")
						{
							$row[$subElement] = $subRow;
						}
					}
					else
					{
						$resultSection[$j] = $mainKey;

						if ($isImport == 0)
						{
							$row[$mainKey] = (string) $mainValue;
						}

						elseif ($isImport == 1 && trim($xmlFile[$j][1]) != "" && $xmlFile[$j][2] == 1)
						{
							$row[$xmlFile[$j][1]] = (string) $mainValue;
						}
					}

					$j++;
				}

				$resultXml[] = $row;
			}
		}

		$result['xmlarray']          = $resultXml;
		$result['xmlsectionarray']   = $resultSection;
		$result['xmlbillingarray']   = $resultBilling;
		$result['xmlshippingarray']  = $resulShipping;
		$result['xmlorderitemarray'] = $resultOrderItem;
		$result['xmlstockarray']     = $resultStock;
		$result['xmlprdextarray']    = $resultPrdExt;

		return $result;
	}

	/**
	 * Import file XML to ID
	 *
	 * @param   integer  $xmlImportId  ID of XML Import
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function importXmlFile($xmlImportId = 0)
	{
		$xmlImportData = self::getXmlImportInfo($xmlImportId);
		$db = JFactory::getDbo();

		if (count($xmlImportData) <= 0)
		{
			// Import record not exists
			return false;
		}

		$destPath = JPATH_SITE . "/components/com_redshop/assets/xmlfile/import/";

		if (($xmlImportData->filename == "" || !is_file($destPath . $xmlImportData->filename)) && $xmlImportData->published == 0)
		{
			return false;
		}

		$fileDetail = self::readXmlImportFile($destPath . $xmlImportData->filename, $xmlImportData, 1);
		$dataList   = $fileDetail['xmlarray'];

		if (count($dataList) <= 0)
		{
			// No data In imported xmlfile.So no need to write import file.
			return false;
		}

		switch ($xmlImportData->section_type)
		{
			case "product":
				for ($i = 0, $in = count($dataList); $i < $in; $i++)
				{
					$oldProductNumber = $dataList[$i]['product_number'];
					$update = false;

					if (array_key_exists('product_number', $dataList[$i]) && $dataList[$i]['product_number'] != "")
					{
						if (self::getProductExist($dataList[$i]['product_number']))
						{
							$update = true;
							$dataList[$i]['product_number'] = $xmlImportData->add_prefix_for_existing . $dataList[$i]['product_number'];
						}
					}

					if (array_key_exists('product_full_image', $dataList[$i]) && $dataList[$i]['product_full_image'] != "")
					{
						$src      = $dataList[$i]['product_full_image'];
						$fileName = basename($src);
						$dest     = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $fileName;

						self::importRemoteImage($src, $dest);
						$dataList[$i]['product_full_image'] = $fileName;
					}

					if (array_key_exists('product_thumb_image', $dataList[$i]) && $dataList[$i]['product_thumb_image'] != "")
					{
						$src      = $dataList[$i]['product_thumb_image'];
						$fileName = basename($src);
						$dest     = REDSHOP_FRONT_IMAGES_RELPATH . "product/thumb/" . $fileName;

						self::importRemoteImage($src, $dest);
						$dataList[$i]['product_thumb_image'] = $fileName;
					}

					// UPDATE EXISTING IF RECORD EXISTS
					if ($xmlImportData->override_existing && $update)
					{
						$dataList[$i]['product_number'] = $oldProductNumber;

						$query = $db->getQuery(true);
						$query->select($db->qn('product_id'))
							->from($db->qn('#__redshop_product'))
							->where($db->qn('product_number') . ' = ' . $db->quote($oldProductNumber));
						$db->setQuery($query);
						$productId = $db->loadResult();

						$prdData = array();
						$catData = array();

						while (list($key, $value) = each($dataList[$i]))
						{
							if (!is_array($value))
							{
								if ($key != "category_id" && $key != "category_name")
								{
									$prdData[] = $key . "='" . addslashes($value) . "' ";
								}
								else
								{
									$catData[$key] = addslashes($value);
								}
							}
							elseif (count($value) > 0)
							{
								for ($j = 0, $jn = count($value); $j < $jn; $j++)
								{
									if ($key == $xmlImportData->stock_element_name)
									{
										if (isset($value[$j]['stockroom_name']))
										{
											$stockArray = array();

											while (list($subKey, $subValue) = each($value[$j]))
											{
												$stockArray[] = $subKey . "='" . addslashes($subValue) . "' ";
											}

											$stockString = implode(", ", $stockArray);

											if (trim($stockString) != "")
											{
												$query = $db->getQuery(true);
												$query = self::sqlUpdateTables(
													array(
														's'  => 'stockroom',
														'sx' => 'product_stockroom_xref',
														'p'  => 'product'
													),
													$stockString,
													array(
														'sx.stockroom_id'  => $db->qn('s.stockroom_id'),
														'sx.product_id'    => $db->qn('p.product_id'),
														'p.product_number' => $db->quote($oldProductNumber),
														's.stockroom_name' => $db->quote($value[$j]['stockroom_name'])
													)
												);
												$db->setQuery($query);
												$db->execute();
												$affectedRows = $db->getAffectedRows();

												if (!$affectedRows)
												{
													$query = $db->getQuery(true);
													$query->select($db->qn('stockroom_id'))
														->from($db->qn('#__redshop_stockroom'))
														->where($db->qn('stockroom_name') . ' = ' . $db->quote($value[$j]['stockroom_name']));
													$db->setQuery($query);
													$stockroomId = $db->loadResult();

													if (!$stockroomId)
													{
														$query = $db->getQuery(true);
														$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_stockroom')
															. " (" . $db->qn('stockroom_name') . ") VALUES (" . $db->quote($value[$j]['stockroom_name']) . ")";
														$db->setQuery($query);
														$db->execute();
														$stockroomId = $db->insertid();
													}

													$query = $db->getQuery(true);
													$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_product_stockroom_xref')
														. " (" . $db->qn('stockroom_id') . ',' . $db->qn('product_id') . ',' . $db->qn('quantity') . ") VALUES (" . (int) $stockroomId . "," . (int) $productId . ",0)";
													$db->setQuery($query);
													$db->execute();

													$query = $db->getQuery(true);
													$query = self::sqlUpdateTables(
														array(
															's'  => 'stockroom',
															'sx' => 'product_stockroom_xref',
															'p'  => 'product'
														),
														$stockString,
														array(
															'sx.stockroom_id'  => $db->qn('s.stockroom_id'),
															'sx.product_id'    => $db->qn('p.product_id'),
															'p.product_number' => $db->quote($oldProductNumber),
															's.stockroom_name' => $db->quote($value[$j]['stockroom_name'])
														)
													);
													$db->setQuery($query);
													$db->execute();
												}
											}
										}
									}
									elseif ($key == $xmlImportData->prdextrafield_element_name)
									{
										if (isset($value[$j]['fieldid']))
										{
											$prdextarray = array();

											while (list($subKey, $subValue) = each($value[$j]))
											{
												$prdextarray[] = $subKey . "='" . addslashes($subValue) . "' ";
											}

											$prdextstring = implode(", ", $prdextarray);

											if (trim($prdextstring) != "")
											{
												$query = $db->getQuery(true);
												$query = self::sqlUpdateTables(
													array(
														'fa'  => 'fields_data',
														'p'   => 'product'
													),
													$prdextstring,
													array(
														'p.product_id'     => $db->qn('fa.itemid'),
														'fa.section'       => '1',
														'fa.fieldid'       => (int) $value[$j]['fieldid'],
														'p.product_number' => $db->quote($oldProductNumber)
													)
												);
												$db->setQuery($query);
												$db->execute();
												$affectedRows = $db->getAffectedRows();

												if (!$affectedRows)
												{
													$query = $db->getQuery(true);
													$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_fields_data')
														. " (" . $db->qn('fieldid') . ',' . $db->qn('itemid') . ',' . $db->qn('section') . ") VALUES (" . $db->quote($value[$j]['fieldid']) . "," . (int) $productId . ",1)";
													$db->setQuery($query);
													$db->execute();

													$query = $db->getQuery(true);
													$query = self::sqlUpdateTables(
														array(
															'fa'  => 'fields_data',
															'p'   => 'product'
														),
														$prdextstring,
														array(
															'p.product_id'     => $db->qn('fa.itemid'),
															'fa.section'       => '1',
															'fa.fieldid'       => (int) $value[$j]['fieldid'],
															'p.product_number' => $db->quote($oldProductNumber)
														)
													);
													$db->setQuery($query);
													$db->execute();
												}
											}
										}
									}
								}
							}
						}

						if (count($prdData) > 0)
						{
							$upString = implode(", ", $prdData);
							$query = $db->getQuery(true);
							$query->update($db->qn('#__redshop_product'))
								->set($upString)
								->where($db->qn('product_number') . ' = ' . $db->quote($oldProductNumber));
							$db->setQuery($query);
							$db->execute();
						}

						if (count($catData) > 0)
						{
							$categoryId = 0;

							if (isset($catData['category_id']))
							{
								$categoryId = $catData['category_id'];
							}

							elseif (isset($catData['category_name']))
							{
								$query = $db->getQuery(true);
								$query->select($db->qn('category_id'))
									->from($db->qn('#__redshop_category'))
									->where($db->qn('category_name') . ' = ' . $db->quote($catData['category_name']));
								$db->setQuery($query);
								$categoryId = $db->loadResult();
							}

							if ($categoryId == 0 && isset($catData['category_name']) && $catData['category_name'] != "")
							{
								$query = $db->getQuery(true);
								$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_category')
									. " (" . $db->qn('category_name') . ") VALUES (" . $db->quote($catData['category_name']) . ")";
								$db->setQuery($query);
								$db->execute();
								$categoryId = $db->insertid();

								$query = $db->getQuery(true);
								$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_category_xref')
									. " (" . $db->qn('category_parent_id') . ',' . $db->qn('category_child_id') . ")"
									. " VALUES ('0', " . (int) $categoryId . ")";
								$db->setQuery($query);
								$db->execute();
							}

							if ($categoryId != 0)
							{
								$query = $db->getQuery(true);
								$query->delete($db->qn('#__redshop_product_category_xref'))
									->where($db->qn('product_id') . ' = ' . (int) $productId)
									->where($db->qn('category_id') . ' = ' . (int) $categoryId);
								$db->setQuery($query);
								$db->execute();

								$query = $db->getQuery(true);
								$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_product_category_xref')
									. " (" . $db->qn('category_id') . ',' . $db->qn('product_id') . ")"
									. " VALUES (" . (int) $categoryId . ", " . (int) $productId . ")";
								$db->setQuery($query);
								$db->execute();
							}
						}
					}
					else
					{
						if (!empty($dataList[$i]['product_number']) && trim($dataList[$i]['product_name']) != "")
						{
							$prdkeysarray = array();
							$prdvalsarray = array();
							$catData = array();

							while (list($key, $value) = each($dataList[$i]))
							{
								if (!is_array($value))
								{
									if ($key != "category_id" && $key != "category_name")
									{
										$prdvalsarray[] = addslashes($value);
										$prdkeysarray[] = $db->qn($key);
									}
									else
									{
										$catData[$key] = addslashes($value);
									}
								}
							}

							if (count($prdkeysarray) > 0)
							{
								$fieldString = implode(", ", $prdkeysarray);
								$valueString = implode("', '", $prdvalsarray);
								$valueString = "'" . $valueString . "'";

								$query = $db->getQuery(true);
								$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_product')
									. " ($fieldString) VALUES ($valueString)";
								$db->setQuery($query);
								$db->execute();
								$productId = $db->insertid();

								foreach ($dataList[$i] AS $key => $value)
								{
									if (is_array($value))
									{
										for ($j = 0, $jn = count($value); $j < $jn; $j++)
										{
											if ($key == $xmlImportData->stock_element_name)
											{
												if (isset($value[$j]['stockroom_name']))
												{
													$stockvalsarray = array();
													$stockkeysarray = array();

													while (list($subKey, $subValue) = each($value[$j]))
													{
														if ($subKey == "quantity")
														{
															$stockvalsarray[] = addslashes($subValue);
															$stockkeysarray[] = $subKey;
														}
													}

													$fieldString = implode(", ", $stockkeysarray);
													$valueString = implode("', '", $stockvalsarray);
													$valueString = "'" . $valueString . "'";

													if (trim($fieldString) != "")
													{
														$query = $db->getQuery(true);
														$query->select($db->qn('stockroom_id'))
															->from($db->qn('#__redshop_stockroom'))
															->where($db->qn('stockroom_name') . ' = ' . $db->quote($value[$j]['stockroom_name']));
														$db->setQuery($query);
														$stockroomId = $db->loadResult();

														if (!$stockroomId)
														{
															$query = $db->getQuery(true);
															$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_stockroom')
																. " (" . $db->qn('stockroom_name') . ") VALUES (" . $db->quote($value[$j]['stockroom_name']) . ")";
															$db->setQuery($query);
															$db->execute();
															$stockroomId = $db->insertid();
														}

														if ($stockroomId)
														{
															$fieldString .= ",stockroom_id,product_id";
															$valueString .= "," . (int) $stockroomId . ", " . (int) $productId . "";

															$query = $db->getQuery(true);
															$query = "INSERT IGNORE INTO " . $db->qn('product_stockroom_xref')
																. " ($fieldString) VALUES ($valueString)";
															$db->setQuery($query);
															$db->execute();
														}
													}
												}
											}
											elseif ($key == $xmlImportData->prdextrafield_element_name)
											{
												if (isset($value[$j]['fieldid']))
												{
													$extValsArray = array();
													$extKeysArray = array();

													while (list($subKey, $subValue) = each($value[$j]))
													{
														if ($subKey != "itemid")
														{
															$extValsArray[] = addslashes($subValue);
															$extKeysArray[] = $subKey;
														}
													}

													$fieldString = implode(", ", $extKeysArray);
													$valueString = implode("', '", $extValsArray);
													$valueString = "'" . $valueString . "'";

													if (trim($fieldString) != "")
													{
														$fieldString .= ",itemid,section";
														$valueString .= "," . (int) $productId . ", '1' ";

														$query = $db->getQuery(true);
														$query = "INSERT IGNORE INTO " . $db->qn('fields_data')
															. " ($fieldString) VALUES ($valueString)";
														$db->setQuery($query);
														$db->execute();
													}
												}
											}
										}
									}
								}

								if (count($catData) > 0)
								{
									$categoryId = 0;

									if (isset($catData['category_id']))
									{
										$categoryId = $catData['category_id'];
									}

									elseif (isset($catData['category_name']))
									{
										$query = $db->getQuery(true);
										$query->select($db->qn('category_id'))
											->from($db->qn('#__redshop_category'))
											->where($db->qn('category_ name') . ' = ' . $db->quote($catData['category_name']));
										$db->setQuery($query);
										$categoryId = $db->loadResult();
									}

									if ($categoryId == 0 && isset($catData['category_name']) && $catData['category_name'] != "")
									{
										$query = $db->getQuery(true);
										$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_category')
											. " (" . $db->qn('category_name') . ") VALUES (" . $db->quote($catData['category_name']) . ")";
										$db->setQuery($query);
										$db->execute();
										$categoryId = $db->insertid();

										$query = $db->getQuery(true);
										$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_category_xref')
											. " (" . $db->qn('category_parent_id') . ',' . $db->qn('category_child_id') . ")"
											. " VALUES ('0', " . (int) $categoryId . ")";
										$db->setQuery($query);
										$db->execute();
									}

									if ($categoryId != 0)
									{
										$query = $db->getQuery(true);
										$query->delete($db->qn('#__redshop_product_category_xref'))
											->where($db->qn('product_id') . ' = ' . (int) $productId)
											->where($db->qn('category_id') . ' = ' . (int) $categoryId);
										$db->setQuery($query);
										$db->execute();

										$query = $db->getQuery(true);
										$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_product_category_xref')
											. " (" . $db->qn('category_id') . ',' . $db->qn('product_id') . ")"
											. " VALUES (" . (int) $categoryId . ", " . (int) $productId . ")";
										$db->setQuery($query);
										$db->execute();
									}
								}
							}
						}
					}
				}
				break;
			case "order":
				for ($i = 0, $in = count($dataList); $i < $in; $i++)
				{
					$oldOrderNumber = $dataList[$i]['order_number'];
					$update = false;

					if (array_key_exists('order_number', $dataList[$i]) && $dataList[$i]['order_number'] != "")
					{
						if (self::getOrderExist($dataList[$i]['order_number']))
						{
							$update = true;
							$dataList[$i]['order_number'] = $xmlImportData->add_prefix_for_existing . $dataList[$i]['order_number'];
						}
					}

					// UPDATE EXISTING IF RECORD EXISTS
					if ($xmlImportData->override_existing && $update)
					{
						$dataList[$i]['order_number'] = $oldOrderNumber;
						$ordArray = array();

						while (list($key, $value) = each($dataList[$i]))
						{
							if (!is_array($value))
							{
								$ordArray[] = $key . "='" . $value . "' ";
							}

							elseif (count($value) > 0)
							{
								if ($key == $xmlImportData->orderitem_element_name)
								{
									for ($j = 0, $jn = count($value); $j < $jn; $j++)
									{
										if (isset($value[$j]['order_item_sku']))
										{
											$oItemArray = array();

											while (list($subKey, $subValue) = each($value[$j]))
											{
												$oItemArray[] = $subKey . "='" . $subValue . "' ";
											}

											$oitemstring = implode(", ", $oItemArray);

											if (trim($oitemstring) != "")
											{
												$query = $db->getQuery(true);
												$query = self::sqlUpdateTables(
													array(
														'oi' => 'order_item',
														'o'  => 'orders'
													),
													$oitemstring,
													array(
														'oi.order_id'       => $db->qn('o.order_id'),
														'o.order_number'    => $db->quote($oldOrderNumber),
														'oi.order_item_sku' => $db->quote($value[$j]['order_item_sku'])
													)
												);
												$db->setQuery($query);
												$db->execute();
											}
										}
									}
								}
								elseif ($key == $xmlImportData->billing_element_name)
								{
									$billingArray = array();

									while (list($subKey, $subValue) = each($value))
									{
										$billingArray[] = $subKey . "='" . $subValue . "' ";
									}

									$billingString = implode(", ", $billingArray);

									if (trim($billingString) != "")
									{
										$query = $db->getQuery(true);
										$query = self::sqlUpdateTables(
											array(
												'ou' => 'order_users_info',
												'o'  => 'orders'
											),
											$billingString,
											array(
												'ou.order_id'       => $db->qn('o.order_id'),
												'o.order_number'    => $db->quote($oldOrderNumber),
												'ou.address_type'   => 'BT'
											)
										);
										$db->setQuery($query);
										$db->execute();
									}
								}
								elseif ($key == $xmlImportData->shipping_element_name)
								{
									$shippingArray = array();

									while (list($subKey, $subValue) = each($value))
									{
										$shippingArray[] = $subKey . "='" . $subValue . "' ";
									}

									$shippingString = implode(", ", $shippingArray);

									if (trim($shippingString) != "")
									{
										$query = $db->getQuery(true);
										$query = self::sqlUpdateTables(
											array(
												'ou' => 'order_users_info',
												'o'  => 'orders'
											),
											$billingString,
											array(
												'ou.order_id'       => $db->qn('o.order_id'),
												'o.order_number'    => $db->quote($oldOrderNumber),
												'ou.address_type'   => 'ST'
											)
										);
										$db->setQuery($query);
										$db->execute();
									}
								}
							}
						}

						if (count($ordArray) > 0)
						{
							$upString = implode(", ", $ordArray);

							$query = $db->getQuery(true);
							$query->update($db->qn('#__redshop_orders'))
								->set($upString)
								->where($db->qn('order_number') . ' = ' . $db->quote($oldOrderNumber));
							$db->setQuery($query);
							$db->execute();
						}
					}
					else
					{
						if (!empty($dataList[$i]['order_number']))
						{
							$ordKeysArray = array();
							$ordValsArray = array();

							while (list($key, $value) = each($dataList[$i]))
							{
								if (!is_array($value))
								{
									$ordValsArray[] = $value;
									$ordKeysArray[] = $key;
								}
							}

							if (count($ordKeysArray) > 0)
							{
								$fieldString = implode(", ", $ordKeysArray);
								$valueString = implode("', '", $ordValsArray);
								$valueString = "'" . $valueString . "'";

								$query = $db->getQuery(true);
								$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_orders')
									. " ($fieldString) VALUES ($valueString)";
								$db->setQuery($query);
								$db->execute();
								$orderId = $db->insertid();

								foreach ($dataList[$i] AS $key => $value)
								{
									if (is_array($value))
									{
										if ($key == $xmlImportData->orderitem_element_name)
										{
											for ($j = 0, $jn = count($value); $j < $jn; $j++)
											{
												if (isset($value[$j]['order_item_sku']))
												{
													$oitemvalsarray = array();
													$oitemkeysarray = array();

													while (list($subKey, $subValue) = each($value[$j]))
													{
														if ($subKey != "order_id")
														{
															$oitemvalsarray[] = $subValue;
															$oitemkeysarray[] = $subKey;
														}
													}

													$fieldString = implode(", ", $oitemkeysarray);
													$valueString = implode("', '", $oitemvalsarray);
													$valueString = "'" . $valueString . "'";

													if (trim($fieldString) != "")
													{
														$fieldString .= ",order_id";
														$valueString .= ",'" . $orderId . "'";

														$query = $db->getQuery(true);
														$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_order_item')
															. " ($fieldString) VALUES ($valueString)";
														$db->setQuery($query);
														$db->execute();
													}
												}
											}
										}
										elseif ($key == $xmlImportData->billing_element_name)
										{
											$billValsArray = array();
											$billKeysArray = array();

											while (list($subKey, $subValue) = each($value))
											{
												if ($subKey != "order_id")
												{
													$billValsArray[] = $subValue;
													$billKeysArray[] = $subKey;
												}
											}

											$fieldString = implode(", ", $billKeysArray);
											$valueString = implode("', '", $billValsArray);
											$valueString = "'" . $valueString . "'";

											if (trim($fieldString) != "")
											{
												$fieldString .= ",order_id";
												$valueString .= ",'" . $orderId . "'";

												$query = $db->getQuery(true);
												$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_order_users_info')
													. " ($fieldString) VALUES ($valueString)";
												$db->setQuery($query);
												$db->execute();
											}
										}
										elseif ($key == $xmlImportData->shipping_element_name)
										{
											$shippValsArray = array();
											$shippKeysArray = array();

											while (list($subKey, $subValue) = each($value[$j]))
											{
												if ($subKey != "order_id")
												{
													$shippValsArray[] = $subValue;
													$shippKeysArray[] = $subKey;
												}
											}

											$fieldString = implode(", ", $shippKeysArray);
											$valueString = implode("', '", $shippValsArray);
											$valueString = "'" . $valueString . "'";

											if (trim($fieldString) != "")
											{
												$fieldString .= ",order_id";
												$valueString .= ",'" . $orderId . "'";

												$query = $db->getQuery(true);
												$query = "INSERT IGNORE INTO " . $db->qn('#__redshop_order_users_info')
													. " ($fieldString) VALUES ($valueString)";
												$db->setQuery($query);
												$db->execute();
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

		self::insertXmlImportLog($xmlImportId, $xmlImportData->filename);

		return true;
	}

	/**
	 * Get product by product number
	 *
	 * @param   string  $productNumber  Product Number
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getProductExist($productNumber = "")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->qn('#__redshop_product'))
			->where($db->qn('product_number') . ' = ' . $db->quote($productNumber));
		$db->setQuery($query);

		if ($db->loadobject())
		{
			return true;
		}

		return false;
	}

	/**
	 * Get order by order number
	 *
	 * @param   string  $orderNumber  Order Number
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getOrderExist($orderNumber = "")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_number') . ' = ' . $db->quote($orderNumber));
		$db->setQuery($query);
		$list = $db->loadobject();

		if (count($list) > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Get Product List
	 *
	 * @param   array  $xmlArray   XML array to get
	 * @param   array  $xmlExport  XML array export
	 *
	 * @return  boolean/array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getProductList($xmlArray = array(), $xmlExport = array())
	{
		$db       = JFactory::getDbo();
		$query    = $db->getQuery(true);
		$list     = array();

		if (count($xmlArray) > 0)
		{
			foreach ($xmlArray as $key => $value)
			{
				if ($key == "category_name")
				{
					$query->select($db->qn("c.{$key}", $value));
				}
				elseif ($key == "product_price")
				{
					$query->select(
						"if(" . $db->qn('p.product_on_sale') . "='1' and (("
						. $db->qn('p.discount_stratdate') . " = 0 and "
						. $db->qn('p.discount_enddate') . "=0) or ("
						. $db->qn('p.discount_stratdate') . " <= UNIX_TIMESTAMP() and "
						. $db->qn('p.discount_enddate') . " >= UNIX_TIMESTAMP())), "
						. $db->qn('p.discount_price') . ", "
						. $db->qn("p.{$key}") . ") "
						. "AS $db->qn($value)"
					);
				}
				// Start Code for display manufacture name
				elseif ($key == "manufacturer_name")
				{
					$query->select($db->qn("m.{$key}", $value));
				}
				// Start Code for display product_url
				elseif ($key == "link")
				{
					$query->select($db->qn('m.manufacturer_email', 'link'));
				}
				// Start Code for display delivertime
				elseif ($key == "delivertime")
				{
					$query->select($db->qn('s.max_del_time', 'delivertime'));
				}
				// Start Code for display pickup
				elseif ($key == "pickup")
				{
					$query->select($db->qn('m.manufacturer_email', 'pickup'));
				}
				// Start Code for display charges
				elseif ($key == "charge")
				{
					$query->select($db->qn('m.manufacturer_email', 'charge'));
				}
				// Start Code for display freight
				elseif ($key == "freight")
				{
					$query->select($db->qn('m.manufacturer_email', 'freight'));
				}
				else
				{
					$query->select($db->qn("p.{$key}", $value));
				}
			}

			if ($strfield != "")
			{
				$query->select($db->qn('p.product_id'))
					->from($db->qn('#__redshop_product', 'p'))
					->lefJoin($db->qn('#__redshop_product_category_xref', 'x') . ' ON ' . $db->qn('x.product_id') . ' = ' . $db->qn('p.product_id'))
					->lefJoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.category_id') . ' = ' . $db->qn('x.category_id'))
					->lefJoin($db->qn('#__redshop_manufacturer', 'm') . ' ON ' . $db->qn('m.manufacturer_id') . ' = ' . $db->qn('p.manufacturer_id'))
					->lefJoin($db->qn('#__redshop_product_stockroom_xref', 'sx') . ' ON ' . $db->qn('sx.product_id') . ' = ' . $db->qn('p.product_id'))
					->lefJoin($db->qn('#__redshop_stockroom', 's') . ' ON ' . $db->qn('s.stockroom_id') . ' = ' . $db->qn('sx.stockroom_id'))
					->where($db->qn('p.published') . ' = 1')
					->group($db->qn('p.product_id'))
					->order($db->qn('p.product_id') . ' ASC');

				if (($xmlExport->xmlexport_on_category != ""))
				{
					$query->where($db->qn('c.category_id') . ' IN (' . $xmlExport->xmlexport_on_category . ')');
				}

				$db->setQuery($query);

				return $db->loadAssocList();
			}
		}

		return false;
	}

	/**
	 * Get Order List
	 *
	 * @param   array  $xml  XML array to get order list
	 *
	 * @return  boolean/object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getOrderList($xml = array())
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (count($xml) > 0)
		{
			foreach ($xml as $key => $value)
			{
				$query->select($db->qn($key, $value));
			}

			$query->select($db->qn('order_id'))
				->from($db->qn('#__redshop_orders'))
				->order($db->qn('order_id') . ' ASC');

			$db->setQuery($query);

			return $db->loadObjectlist();
		}

		return false;
	}

	/**
	 * Get order user info list
	 *
	 * @param   array    $xml          XML to get info
	 * @param   integer  $orderId      Order ID
	 * @param   string   $addressType  Address Type as 2 characters
	 *
	 * @return  boolean/object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getOrderUserInfoList($xml = array(), $orderId = 0, $addressType = "BT")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (count($xml) > 0)
		{
			foreach ($xml as $key => $value)
			{
				$query->select($db->qn($key, $value));
			}

			$query->from($db->qn('#__redshop_order_users_info'))
				->where($db->qn('address_type') . ' = ' . $db->quote($addressType))
				->where($db->qn('order_id') . ' = ' . (int) $orderId)
				->order($db->qn('order_id') . ' ASC');

			$db->setQuery($query);

			return $db->loadObjectlist();
		}

		return false;
	}

	/**
	 * Get order item list
	 *
	 * @param   array    $xml      XML to get list
	 * @param   integer  $orderId  Order ID
	 *
	 * @return  boolean/object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getOrderItemList($xml = array(), $orderId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (count($xml) > 0)
		{
			foreach ($xml as $key => $value)
			{
				$query->select($db->qn($key, $value));
			}

			$query->from($db->qn('#__redshop_order_item'))
				->where($db->qn('order_id') . ' = ' . (int) $orderId)
				->order($db->qn('order_item_id') . ' ASC');

			$db->setQuery($query);

			return $db->loadObjectlist();
		}

		return false;
	}

	/**
	 * Get Stockroom List
	 *
	 * @param   array    $xml        XML to get
	 * @param   integer  $productId  Product ID
	 *
	 * @return  boolean/object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getStockroomList($xml = array(), $productId = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (count($xml) > 0)
		{
			foreach ($xml as $key => $value)
			{
				$query->select($db->qn($key, $value));
			}

			$query->from($db->qn('#__redshop_stockroom', 's'))
				->leftJoin(
					$db->qn('#__redshop_product_stockroom_xref', 'sx')
					. ' ON ' . $db->qn('s.stockroom_id') . ' = ' . $db->qn('sx.stockroom_id')
				)
				->where($db->qn('product_id') . ' = ' . (int) $productId)
				->order($db->qn('s.stockroom_id') . ' ASC');

			$db->setQuery($query);

			return $db->loadObjectlist();
		}

		return false;
	}

	/**
	 * Get extra field list
	 *
	 * @param   array    $xml           XML array to get list
	 * @param   integer  $sectionId     Section field ID
	 * @param   integer  $fieldSection  Section Field
	 *
	 * @return  boolean/object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getExtraFieldList($xml = array(), $sectionId = 0, $fieldSection = 0)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (count($xml) > 0)
		{
			foreach ($xml as $key => $value)
			{
				$query->select($db->qn($key, $value));
			}

			$query->from($db->qn('#__redshop_fields_data', 's'))
				->where($db->qn('itemid') . ' = ' . (int) $sectionId)
				->where($db->qn('section') . ' = ' . (int) $fieldSection);

			$db->setQuery($query);

			return $db->loadObjectlist();
		}

		return false;
	}

	/**
	 * Import remote image
	 *
	 * @param   string  $src   URL to begin curl
	 * @param   string  $dest  Path to put file
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function importRemoteImage($src, $dest)
	{
		chmod($dest, 0777);

		$channel = curl_init($src);
		$file    = fopen($dest, "w");

		curl_setopt($channel, CURLOPT_FILE, $file);
		curl_setopt($channel, CURLOPT_HEADER, 0);
		curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($channel, CURLOPT_SSL_VERIFYHOST, true);
		curl_exec($channel);
		curl_close($channel);
		fclose($file);
	}

	/**
	 * Query SQL Update for multiple tables
	 *
	 * @param   array  $tables     Array of tables to update
	 * @param   array  $sets       Array of values to set
	 * @param   array  $condition  Array of where to update
	 *
	 * @return  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function sqlUpdateTables($tables = array(), $sets = '', $condition = array())
	{
		$query       = "UPDATE ";
		$tmpTables   = array();
		$tmpCodition = array();

		if (empty($tables) || $sets == '')
		{
			return false;
		}

		foreach ($tables as $k => $tb)
		{
			if (is_string($k))
			{
				$tmpTables[] = $db->qn('#__redshop_' . $tb) . ' AS ' . $db->qn($k);
			}
			else
			{
				$tmpTables[] = $db->qn('#__redshop_' . $tb);
			}
		}

		$query .= implode(',', $tmpTables) . " SET $prdextstring";

		if (!empty($condition))
		{
			foreach ($condition as $c => $v)
			{
				if (is_string($v))
				{
					$v = "'" . $v . "'";
				}

				$tmpCondition[] = $db->qn($c) . ' = ' . $v;
			}

			$tmpCondition = implode(' AND ', $tmpCondition);
		}

		$query .= " WHERE " . $tmpCondition;

		return $query;
	}
}
