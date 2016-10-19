<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml instead
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

/**
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml instead
 */
class xmlHelper
{
	public $_db = null;
	public $_data = null;
	public $_table_prefix = null;

	public function __construct()
	{
		$this->_table_prefix = '#__redshop_';
		$this->_db = JFactory::getDbo();
	}

	/**
	 * Get Section Type List
	 *
	 * @return  array  Array of HTML section list
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getSectionTypeList() instead
	 */
	public function getSectionTypeList()
	{
		return RedshopHelperXml::getSectionTypeList();
	}

	/**
	 * Get Section Type Name
	 *
	 * @param   string  $value  Value to get section type name
	 *
	 * @return  string
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getSectionTypeName() instead
	 */
	public function getSectionTypeName($value = '')
	{
		return RedshopHelperXml::getSectionTypeName($value);
	}

	/**
	 * Get Synchornization Interval List
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getSynchIntervalList() instead
	 */
	public function getSynchIntervalList()
	{
		return RedshopHelperXml::getSynchIntervalList();
	}

	/**
	 * Get Synchronization Interval Name
	 *
	 * @param   integer  $value  Decimal value for hours
	 *
	 * @return  string
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getSynchIntervalName() instead
	 */
	public function getSynchIntervalName($value = 0)
	{
		return RedshopHelperXml::getSynchIntervalName($value);
	}

	// @TODO
	public function getSectionColumnList($section = "", $childSection = "")
	{
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
						$table = "stockroom"; //"product_stockroom_xref";
						$q = "SHOW COLUMNS FROM " . $this->_table_prefix . "product_stockroom_xref";
						$this->_db->setQuery($q);
						$cat = $this->_db->loadObjectList();
						for ($i = 0, $in = count($cat); $i < $in; $i++)
						{
							if ($cat[$i]->Field == "quantity")
							{
								$catcol[] = $cat[$i];
							}
						}
						break;
					case "prdextrafield":
						$table = ""; //"fields_data";
						$q = "SHOW COLUMNS FROM " . $this->_table_prefix . "fields_data";
						$this->_db->setQuery($q);
						$cat = $this->_db->loadObjectList();
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
						$q = "SHOW COLUMNS FROM " . $this->_table_prefix . "category";
						$this->_db->setQuery($q);
						$cat = $this->_db->loadObjectList();

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
							elseif ($cat[$i]->Field == "category_template") //Start Code for display product_url
							{
								$cat[$i]->Field = "link";
								$catcol[] = $cat[$i];
							}

							elseif ($cat[$i]->Field == "category_thumb_image") //Start Code for display delivertime
							{
								$cat[$i]->Field = "delivertime";
								$catcol[] = $cat[$i];
							}

							elseif ($cat[$i]->Field == "category_full_image") //Start Code for display pickup
							{
								$cat[$i]->Field = "pickup";
								$catcol[] = $cat[$i];
							}

							elseif ($cat[$i]->Field == "category_back_full_image") //Start Code for display charges
							{
								$cat[$i]->Field = "charge";
								$catcol[] = $cat[$i];
							}
							elseif ($cat[$i]->Field == "category_pdate") //Start Code for display freight
							{
								$cat[$i]->Field = "freight";
								$catcol[] = $cat[$i];
							}

						}

						// Start Code for display manufacturer name field
						$q = "SHOW COLUMNS FROM " . $this->_table_prefix . "manufacturer";
						$this->_db->setQuery($q);
						$cat = $this->_db->loadObjectList();

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
			$q = "SHOW COLUMNS FROM " . $this->_table_prefix . $table;
			$this->_db->setQuery($q);
			$cols = $this->_db->loadObjectList();
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
	 * Get XML File Tag
	 *
	 * @param   string  $fieldname   Field name
	 * @param   string  $xmlfiletag  XML File Tag
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getXMLFileTag() instead
	 */
	public function getXMLFileTag($fieldname = "", $xmlfiletag)
	{
		return RedshopHelperXml::getXMLFileTag($fieldname, $xmlfiletag);
	}

	/**
	 * Explode XML file string
	 *
	 * @param   string  $xmlfiletag  String to explode
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::explodeXmlFileString() instead
	 */
	public function explodeXMLFileString($xmlfiletag = "")
	{
		return RedshopHelperXml::explodeXmlFileString($xmlfiletag);
	}

	/**
	 * Get XML Export Info
	 *
	 * @param   integer  $xmlexport_id  ID of xml to export
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getXmlExportInfo() instead
	 */
	public function getXMLExportInfo($xmlexport_id = 0)
	{
		return RedshopHelperXml::getXmlExportInfo($xmlexport_id);
	}

	/**
	 * Get XML export IP address
	 *
	 * @param   integer  $xmlexport_id  ID of xml to export IP address
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getXmlExportIpAddress() instead
	 */
	public function getXMLExportIpAddress($xmlexport_id = 0)
	{
		return RedshopHelperXml::getXmlExportIpAddress($xmlexport_id);
	}

	/**
	 * Insert file name into xml export Log with id
	 *
	 * @param   integer  $xmlexport_id  Xml Export ID to insert
	 * @param   string   $filename      File name to insert
	 *
	 * @return void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::insertXmlExportLog() instead
	 */
	public function insertXMLExportlog($xmlexport_id = 0, $filename = "")
	{
		return RedshopHelperXml::insertXmlExportLog($xmlexport_id, $filename);
	}

	/**
	 * Update file name in xml_export
	 *
	 * @param   integer  $xmlexport_id  XML export ID to update
	 * @param   string   $filename      New file name to set
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::updateXmlExportFilename() instead
	 */
	public function updateXMLExportFilename($xmlexport_id = 0, $filename = "")
	{
		return RedshopHelperXml::updateXmlExportFilename($xmlexport_id, $filename);
	}

	/**
	 * Get XML import info
	 *
	 * @param   integer  $xmlimport_id  Description of XML import info
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getXmlImportInfo() instead
	 */
	public function getXMLImportInfo($xmlimport_id = 0)
	{
		return RedshopHelperXml::getXmlImportInfo($xmlimport_id);
	}

	/**
	 * Insert XML import log
	 *
	 * @param   integer  $xmlimport_id  XML import log ID to insert
	 * @param   string   $filename      File name to insert
	 *
	 * @return void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::insertXmlImportlog() instead
	 */
	public function insertXMLImportlog($xmlimport_id = 0, $filename = "")
	{
		return RedshopHelperXml::insertXmlImportlog($xmlimport_id, $filename);
	}

	/**
	 * Update XML Import file name
	 *
	 * @param   integer  $xmlimport_id  XML Import ID
	 * @param   string   $filename      File name to update
	 *
	 * @return void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::updateXmlImportFileName() instead
	 */
	public function updateXMLImportFilename($xmlimport_id = 0, $filename = "")
	{
		return RedshopHelperXml::updateXmlImportFileName($xmlimport_id, $filename);
	}

	/**
	 * Write XML Export to file
	 *
	 * @param   integer  $xmlexport_id  XML Export ID
	 *
	 * @return  string   File name
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::writeXmlExportFile() instead
	 */
	public function writeXMLExportFile($xmlexport_id = 0)
	{
		return RedshopHelperXml::writeXmlExportFile($xmlexport_id);
	}

	/**
	 * Write XML Import to file
	 *
	 * @param   integer  $xmlimport_id      XML Import ID to write
	 * @param   string   $tmlxmlimport_url  URL to write file
	 *
	 * @return  string   File name after writing
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::writeXmlExportFile() instead
	 */
	public function writeXMLImportFile($xmlimport_id = 0, $tmlxmlimport_url = "")
	{
		return RedshopHelperXml::writeXmlExportFile($xmlimport_id, $tmlxmlimport_url);
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::readXmlImportFile() instead
	 */
	public function readXMLImportFile($file = "", $data = array(), $isImport = 0)
	{
		return RedshopHelperXml::readXmlImportFile($file, $data, $isImport);
	}

	/**
	 * Import file XML to ID
	 *
	 * @param   integer  $xmlimport_id  ID of XML Import
	 *
	 * @return  boolean
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::importXmlFile() instead
	 */
	function importXMLFile($xmlimport_id = 0)
	{
		return RedshopHelperXml::importXmlFile($xmlimport_id);
	}

	/**
	 * Get product by product number
	 *
	 * @param   string  $product_number  Product Number
	 *
	 * @return  boolean
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getProductExist() instead
	 */
	public function getProductExist($product_number = "")
	{
		return RedshopHelperXml::getProductExist($product_number);
	}

	/**
	 * Get order by order number
	 *
	 * @param   string  $order_number  Order Number
	 *
	 * @return  boolean
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getOrderExist() instead
	 */
	public function getOrderExist($order_number = "")
	{
		return RedshopHelperXml::getOrderExist($order_number);
	}

	public function getProductList($xmlarray = array(), $xmlExport = array())
	{
		$list = array();
		$field = array();
		$strfield = "";

		if (count($xmlarray) > 0)
		{
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
				elseif ($key == "manufacturer_name") //Start Code for display manufacture name
				{
					$field[] = "m." . $key . " AS " . $value;
				}

				elseif ($key == "link") //Start Code for display product_url
				{
					$field[] = "m.manufacturer_email AS link ";
				}

				elseif ($key == "delivertime") //Start Code for display delivertime
				{
					$field[] = "s.max_del_time AS delivertime ";
				}

				elseif ($key == "pickup") //Start Code for display pickup
				{
					$field[] = "m.manufacturer_email AS pickup ";
				}

				elseif ($key == "charge") //Start Code for display charges
				{
					$field[] = "m.manufacturer_email AS charge ";
				}

				elseif ($key == "freight") //Start Code for display freight
				{
					$field[] = "m.manufacturer_email AS freight ";
				}

				else
				{
					$field[] = "p." . $key . " AS " . $value;
				}
			}

			if (count($field) > 0)
			{
				$strfield = implode(", ", $field);
			}

			$andcat = ($xmlExport->xmlexport_on_category != "") ? "AND c.category_id IN ($xmlExport->xmlexport_on_category) " : "";

			if ($strfield != "")
			{
				$query = "SELECT " . $strfield . ", p.product_id FROM " . $this->_table_prefix . "product AS p "
					. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS x ON x.product_id=p.product_id "
					. "LEFT JOIN " . $this->_table_prefix . "category AS c ON c.category_id=x.category_id "
					. "LEFT JOIN " . $this->_table_prefix . "manufacturer AS m ON m.manufacturer_id=p.manufacturer_id  "
					. "LEFT JOIN " . $this->_table_prefix . "product_stockroom_xref AS sx ON sx.product_id=p.product_id "
					. "LEFT JOIN " . $this->_table_prefix . "stockroom AS s ON s.stockroom_id =sx.stockroom_id  "
					. "WHERE p.published=1 "
					. $andcat
					. "GROUP BY p.product_id "
					. "ORDER BY p.product_id ASC ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadAssocList();
			}
		}

		return $list;
	}

	public function getOrderList($xmlarray = array())
	{
		$list = array();
		$field = array();
		$strfield = "";

		if (count($xmlarray) > 0)
		{
			foreach ($xmlarray AS $key => $value)
			{
				$field[] = $key . " AS " . $value;
			}

			if (count($field) > 0)
			{
				$strfield = implode(", ", $field);
			}

			if ($strfield != "")
			{
				$query = "SELECT " . $strfield . ", order_id FROM " . $this->_table_prefix . "orders "
					. "ORDER BY order_id ASC ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectlist();
			}
		}

		return $list;
	}

	public function getOrderUserInfoList($xmlarray = array(), $order_id = 0, $addresstype = "BT")
	{
		$list = array();
		$field = array();
		$strfield = "";

		if (count($xmlarray) > 0)
		{
			foreach ($xmlarray AS $key => $value)
			{
				$field[] = $key . " AS '" . $value . "'";
			}

			if (count($field) > 0)
			{
				$strfield = implode(", ", $field);
			}

			if ($strfield != "")
			{
				$query = "SELECT " . $strfield . " FROM " . $this->_table_prefix . "order_users_info "
					. "WHERE address_type=" . $this->_db->quote($addresstype) . " "
					. "AND order_id=" . (int) $order_id . " "
					. "ORDER BY order_id ASC ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObject();
			}
		}

		return $list;
	}

	public function getOrderItemList($xmlarray = array(), $order_id = 0)
	{
		$list = array();
		$field = array();
		$strfield = "";

		if (count($xmlarray) > 0)
		{
			foreach ($xmlarray AS $key => $value)
			{
				$field[] = $key . " AS " . $value;
			}

			if (count($field) > 0)
			{
				$strfield = implode(", ", $field);
			}

			if ($strfield != "")
			{
				$query = "SELECT " . $strfield . " FROM " . $this->_table_prefix . "order_item "
					. "WHERE order_id=" . (int) $order_id . " "
					. "ORDER BY order_item_id ASC ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectList();
			}
		}

		return $list;
	}

	public function getStockroomList($xmlarray = array(), $product_id = 0)
	{
		$list = array();
		$field = array();
		$strfield = "";

		if (count($xmlarray) > 0)
		{
			foreach ($xmlarray AS $key => $value)
			{
				$field[] = $key . " AS " . $value;
			}

			if (count($field) > 0)
			{
				$strfield = implode(", ", $field);
			}

			if ($strfield != "")
			{
				$query = "SELECT " . $strfield . " FROM " . $this->_table_prefix . "stockroom AS s "
					. "LEFT JOIN " . $this->_table_prefix . "product_stockroom_xref AS sx ON s.stockroom_id=sx.stockroom_id "
					. "WHERE product_id=" . (int) $product_id . " "
					. "ORDER BY s.stockroom_id ASC ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectList();
			}
		}

		return $list;
	}

	public function getExtraFieldList($xmlarray = array(), $section_id = 0, $fieldsection = 0)
	{
		$list = array();
		$field = array();
		$strfield = "";

		if (count($xmlarray) > 0)
		{
			foreach ($xmlarray AS $key => $value)
			{
				$field[] = $key . " AS " . $value;
			}

			if (count($field) > 0)
			{
				$strfield = implode(", ", $field);
			}

			if ($strfield != "")
			{
				$query = "SELECT " . $strfield . " FROM " . $this->_table_prefix . "fields_data "
					. "WHERE itemid=" . (int) $section_id . " "
					. "AND section=" . (int) $fieldsection . " ";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectList();
			}
		}

		return $list;
	}

	public function importRemoteImage($src, $dest)
	{
		chmod($dest, 0777);
		$Channel = curl_init($src);
		$File = fopen($dest, "w");
		curl_setopt($Channel, CURLOPT_FILE, $File);
		curl_setopt($Channel, CURLOPT_HEADER, 0);
		curl_setopt($Channel, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($Channel, CURLOPT_SSL_VERIFYHOST, true);
		curl_exec($Channel);
		curl_close($Channel);
		fclose($File);
	}
}
