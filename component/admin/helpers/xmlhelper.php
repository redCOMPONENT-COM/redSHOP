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
 * Admin Components Helper for XML
 *
 * @since       1.6
 *
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml instead
 */
class xmlHelper
{
	/**
	 * $db
	 *
	 * @var  null
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public $_db = null;

	/**
	 * Table prefix
	 *
	 * @var  null
	 *
	 * @deprecated  __DEPLOY_VERSION__
	 */
	public $_table_prefix = null;

	/**
	 * Constructor
	 */
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

	/**
	 * Get section column list
	 *
	 * @param   string  $section       Section
	 * @param   string  $childSection  Child section
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getSectionColumnList() instead
	 */
	public function getSectionColumnList($section = "", $childSection = "")
	{
		return RedshopHelperXml::getSectionColumnList($section, $childSection);
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
	public function getXMLFileTag($fieldname = "", $xmlfiletag = "")
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

	/**
	 * Get Product List
	 *
	 * @param   array  $xmlarray   XML array to get
	 * @param   array  $xmlExport  XML array export
	 *
	 * @return  array
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getProductList() instead
	 */
	public function getProductList($xmlarray = array(), $xmlExport = array())
	{
		return RedshopHelperXml::getProductList($xmlarray, $xmlExport);
	}

	/**
	 * Get Order List
	 *
	 * @param   array  $xmlarray  XML array to get order list
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getOrderList() instead
	 */
	public function getOrderList($xmlarray = array())
	{
		return RedshopHelperXml::getOrderList($xmlarray);
	}

	/**
	 * Get order user info list
	 *
	 * @param   array    $xmlarray     XML to get info
	 * @param   integer  $order_id     Order ID
	 * @param   string   $addresstype  Address Type as 2 characters
	 *
	 * @return  boolean/object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getOrderUserInfoList() instead
	 */
	public function getOrderUserInfoList($xmlarray = array(), $order_id = 0, $addresstype = "BT")
	{
		return RedshopHelperXml::getOrderUserInfoList($xmlarray, $order_id, $addresstype);
	}

	/**
	 * Get order item list
	 *
	 * @param   array    $xmlarray  XML to get list
	 * @param   integer  $order_id  Order ID
	 *
	 * @return  boolean/object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getOrderItemList() instead
	 */
	public function getOrderItemList($xmlarray = array(), $order_id = 0)
	{
		return RedshopHelperXml::getOrderItemList($xmlarray, $order_id);
	}

	/**
	 * Get Stockroom List
	 *
	 * @param   array    $xmlarray    XML to get
	 * @param   integer  $product_id  Product ID
	 *
	 * @return  boolean/object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getStockroomList() instead
	 */
	public function getStockroomList($xmlarray = array(), $product_id = 0)
	{
		return RedshopHelperXml::getStockroomList($xmlarray, $product_id);
	}

	/**
	 * Get extra field list
	 *
	 * @param   array    $xmlarray      XML array to get list
	 * @param   integer  $section_id    Section field ID
	 * @param   integer  $fieldsection  Section Field
	 *
	 * @return  boolean/object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::getExtraFieldList() instead
	 */
	public function getExtraFieldList($xmlarray = array(), $section_id = 0, $fieldsection = 0)
	{
		return RedshopHelperXml::getExtraFieldList($xmlarray, $section_id, $fieldsection);
	}

	/**
	 * Import remote image
	 *
	 * @param   string  $src   URL to begin curl
	 * @param   string  $dest  Path to put file
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperXml::importRemoteImage() instead
	 */
	public function importRemoteImage($src, $dest)
	{
		return RedshopHelperXml::importRemoteImage($src, $dest);
	}
}
