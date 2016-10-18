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
 * Class Redshop Helper for XML
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
	 * Get XML File Tag
	 *
	 * @param   string  $fieldName   Field name
	 * @param   string  $xmlFileTag  XML File Tag
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getXMLFileTag($fieldName = '', $xmlFileTag = '')
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
					$update = (isset($value[2])) ? $value[2] : 0;
					break;
				}
			}
		}

		return array($result, $update);
	}

	/**
	 * Explode XML file string
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
	 * Get XML Export Info
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
	 * Get XML export IP address
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
	 * @param   integer  $xmlExportId  XML export ID to update
	 * @param   string   $fileName     New file name to set
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function updateXmlExportFilename($xmlExportId = 0, $fileName = "")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->update($db->qn('#__redshop_xml_export'))
			->set($db->qn('filename') . ' = ' . $db->quote($fileName))
			->where($db->qn('xmlexport_id') . ' = ' . (int) $xmlExportId);

		$db->setQuery($query);
		$db->execute();
	}
}
