<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  2.0.3  Use RedshopHelperExtrafields instead
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

/**
 * Redshop Helper for Extra Fields
 *
 * @since   1.6.0
 * @deprecated  2.0.3  Use RedshopHelperExtrafields instead
 */
class extra_field
{
	/**
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Returns the extra_field object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  extra_field  The extra_field object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * List all field in product
	 *
	 * @param   integer  $section  Section product
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::listAllFieldInProduct() instead
	 */
	public function list_all_field_in_product($section = RedshopHelperExtrafields::SECTION_PRODUCT)
	{
		return RedshopHelperExtrafields::listAllFieldInProduct($section);
	}

	/**
	 * List all field
	 *
	 * @param   string   $field_section  Field Section
	 * @param   integer  $section_id     Section ID
	 * @param   string   $field_name     Field Name
	 * @param   string   $templateDesc  Template
	 *
	 * @return  string   HTML <td></td>
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::listAllField() instead
	 */
	public function list_all_field($field_section = "", $section_id = 0, $field_name = "", $templateDesc = "")
	{
		return RedshopHelperExtrafields::listAllField($field_section, $section_id, $field_name, $templateDesc);
	}

	/**
	 * Save extra fields
	 *
	 * @param   array    $data           Data to insert
	 * @param   integer  $field_section  Field section to match
	 * @param   string   $section_id     Section ID
	 * @param   string   $user_email     User to match by email
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::extraFieldSave() instead
	 */
	public function extra_field_save($data, $field_section, $section_id = "", $user_email = "")
	{
		RedshopHelperExtrafields::extraFieldSave($data, $field_section, $section_id, $user_email);
	}

	/**
	 * validate Extra Field
	 *
	 * @param   integer   $field_section  Field Section List
	 * @param   integer  $section_id     Section ID
	 *
	 * @return  boolean
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::CheckExtraFieldValidation() instead
	 */
	public function chk_extrafieldValidation($field_section = 0, $section_id = 0)
	{
		return RedshopHelperExtrafields::CheckExtraFieldValidation($field_section, $section_id);
	}

	/**
	 * List all fields and display
	 *
	 * @param   integer   $field_section  Field section
	 * @param   integer  $section_id     Section ID
	 * @param   integer  $flag           Flag
	 * @param   string   $user_email     User email
	 * @param   string   $templateDesc  Template description
	 * @param   boolean  $sendmail       True/ False
	 *
	 * @return string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::listAllFieldDisplay() instead
	 */
	public function list_all_field_display($field_section = 0, $section_id = 0, $flag = 0, $user_email = "", $templateDesc = "", $sendmail = false)
	{
		return RedshopHelperExtrafields::listAllFieldDisplay($field_section, $section_id, $flag, $user_email, $templateDesc, $sendmail);
	}

	/**
	 * List all user fields
	 *
	 * @param   string $fieldSection Field Section
	 * @param   int    $sectionId    Section ID
	 * @param   string $fieldType    Field type
	 * @param   string $uniqueId     Unique ID
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::listAllUserFields() instead
	 */
	public function list_all_user_fields($fieldSection = "", $sectionId = RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD,
		$fieldType = '', $uniqueId = '')
	{
		return RedshopHelperExtrafields::listAllUserFields($fieldSection, $sectionId, $fieldType, $uniqueId);
	}

	/**
	 * Render HTML radio list
	 *
	 * @param   string   $name      Name of radio checkbox
	 * @param   array    $attribs   Attribute values
	 * @param   array    $selected  The name of the object variable for the option text
	 * @param   string   $yes       Option Days
	 * @param   string   $no        Option Weeks
	 * @param   boolean  $id        ID of radio checkbox
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::booleanList() instead
	 */
	public function booleanlist($name, $attribs = null, $selected = null, $yes = 'yes', $no = 'no', $id = false)
	{
		return RedshopHelperExtrafields::booleanList($name, $attribs, $selected, $yes, $no, $id);
	}

	/**
	 * Render HTML radio list with options
	 *
	 * @param   string   $name       Name of radio checkbox
	 * @param   array    $attribs    Attribute values
	 * @param   array    $selected   The name of the object variable for the option text
	 * @param   string   $yes        Option Days
	 * @param   string   $no         Option Weeks
	 * @param   boolean  $id         ID of radio checkbox
	 * @param   string   $yes_value  ID of radio checkbox
	 * @param   string   $no_value   ID of radio checkbox
	 *
	 * @return  string
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::rsBooleanList() instead
	 */
	public function rs_booleanlist($name, $attribs = null, $selected = null, $yes = 'yes', $no = 'no', $id = false,
		$yes_value = 'Days', $no_value = 'Weeks'
	)
	{
		return RedshopHelperExtrafields::rsBooleanList($name, $attribs, $selected, $yes, $no, $id, $yes_value, $no_value);
	}

	/**
	 * Get fields value by ID
	 *
	 * @param   integer  $id  ID of field
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::getFieldValue() instead
	 */
	public function getFieldValue($id)
	{
		return RedshopHelperExtrafields::getFieldValue($id);
	}

	/**
	 * Get Section Field List
	 *
	 * @param   integer  $section  [description]
	 * @param   integer  $front    [description]
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::getSectionFieldList() instead
	 */
	public function getSectionFieldList($section = RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, $front = 1)
	{
		return RedshopHelperExtrafields::getSectionFieldList($section, $front);
	}

	/**
	 * Get section field data list
	 *
	 * @param   integer  $fieldid      Field ID
	 * @param   integer  $section      Section ID
	 * @param   integer  $orderitemid  Order Item ID
	 * @param   string   $user_email   User Email
	 *
	 * @return  object
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::getSectionFieldDataList() instead
	 */
	public function getSectionFieldDataList($fieldid, $section = 0, $orderitemid = 0, $user_email = "")
	{
		return RedshopHelperExtrafields::getSectionFieldDataList($fieldid, $section, $orderitemid, $user_email);
	}

	/**
	 * Copy product extra field
	 *
	 * @param   integer  $oldproduct_id  Old Product ID
	 * @param   integer  $newPid         New Product ID
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::copyProductExtraField() instead
	 */
	public function copy_product_extra_field($oldproduct_id, $newPid)
	{
		RedshopHelperExtrafields::copyProductExtraField($oldproduct_id, $newPid);
	}

	/**
	 * Delete extra field data
	 *
	 * @param   integer  $data_id  Data ID
	 *
	 * @return  void
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::deleteExtraFieldData() instead
	 */
	public function deleteExtraFieldData($data_id)
	{
		RedshopHelperExtrafields::deleteExtraFieldData($data_id);
	}
}
