<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields instead
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
jimport('joomla.filesystem.file');

/**
 * Redshop Helper for Extra Fields
 *
 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields instead
 */
class extra_field
{
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
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::listAllFieldInProduct() instead
	 */
	public function list_all_field_in_product($section = extraField::SECTION_PRODUCT)
	{
		return RedshopHelperExtraFields::listAllFieldInProduct($section);
	}

	/**
	 * List all field
	 *
	 * @param   string   $field_section  Field Section
	 * @param   integer  $section_id     Section ID
	 * @param   string   $field_name     Field Name
	 * @param   string   $table          Table
	 * @param   string   $template_desc  Template
	 *
	 * @return  string   HTML <td></td>
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::listAllField() instead
	 */
	public function list_all_field($field_section = "", $section_id = 0, $field_name = "", $table = "", $template_desc = "")
	{
		return RedshopHelperExtraFields::listAllField($field_section, $section_id, $field_name, $table, $template_desc);
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
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::extraFieldSave() instead
	 */
	public function extra_field_save($data, $field_section, $section_id = "", $user_email = "")
	{
		return RedshopHelperExtraFields::extraFieldSave($data, $field_section, $section_id, $user_email);
	}

	/**
	 * validate Extra Field
	 *
	 * @param   string   $field_section  Field Section List
	 * @param   integer  $section_id     Section ID
	 *
	 * @return  boolean
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::CheckExtraFieldValidation() instead
	 */
	public function chk_extrafieldValidation($field_section = "", $section_id = 0)
	{
		return RedshopHelperExtraFields::CheckExtraFieldValidation($field_section, $section_id);
	}

	/**
	 * List all fields and display
	 *
	 * @param   string   $field_section  Field section
	 * @param   integer  $section_id     Section ID
	 * @param   integer  $flag           Flag
	 * @param   string   $user_email     User email
	 * @param   string   $template_desc  Template description
	 * @param   boolean  $sendmail       True/ False
	 *
	 * @return string
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::listAllFieldDisplay() instead
	 */
	public function list_all_field_display($field_section = "", $section_id = 0, $flag = 0, $user_email = "", $template_desc = "", $sendmail = false)
	{
		return RedshopHelperExtraFields::listAllFieldDisplay($field_section, $section_id, $flag, $user_email, $template_desc, $sendmail);
	}

	/**
	 * List all user fields
	 *
	 * @param   string  $field_section  Field Section
	 * @param   string  $section_id     Section ID
	 * @param   string  $field_type     Field type
	 * @param   string  $unique_id      Unique ID
	 *
	 * @return  string
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::listAllUserFields() instead
	 */
	public function list_all_user_fields($field_section = "", $section_id = extraField::SECTION_PRODUCT_USERFIELD, $field_type = '', $unique_id)
	{
		return RedshopHelperExtraFields::listAllUserFields($field_section, $section_id, $field_type, $unique_id);
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::booleanList() instead
	 */
	public function booleanlist($name, $attribs = null, $selected = null, $yes = 'yes', $no = 'no', $id = false)
	{
		RedshopHelperExtraFields::booleanList($name, $attribs, $selected, $yes, $no, $id);
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
	 * @param   boolean  $yes_value  ID of radio checkbox
	 * @param   boolean  $no_value   ID of radio checkbox
	 *
	 * @return  string
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::rsBooleanList() instead
	 */
	public function rs_booleanlist($name, $attribs = null, $selected = null, $yes = 'yes', $no = 'no', $id = false, $yes_value, $no_value)
	{
		return RedshopHelperExtraFields::rsBooleanList($name, $attribs, $selected, $yes, $no, $id, $yes_value, $no_value);
	}

	/**
	 * Get fields value by ID
	 *
	 * @param   integer  $id  ID of field
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::getFieldValue() instead
	 */
	public function getFieldValue($id)
	{
		return RedshopHelperExtraFields::getFieldValue($id);
	}

	/**
	 * Get Section Field List
	 *
	 * @param   string   $section  [description]
	 * @param   integer  $front    [description]
	 *
	 * @return  object
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::getSectionFieldList() instead
	 */
	public function getSectionFieldList($section = extraField::SECTION_PRODUCT_USERFIELD, $front = 1)
	{
		return RedshopHelperExtraFields::getSectionFieldList($section, $front);
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
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::getSectionFieldDataList() instead
	 */
	public function getSectionFieldDataList($fieldid, $section = 0, $orderitemid = 0, $user_email = "")
	{
		$model =  JModelLegacy::getInstance('Fields', 'RedshopModel');

		return $model->getFieldDataList($fieldid, $section, $orderitemid, $user_email);
	}

	/**
	 * Copy product extra field
	 *
	 * @param   integer  $oldproduct_id  Old Product ID
	 * @param   integer  $newPid         New Product ID
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::copyProductExtraField() instead
	 */
	public function copy_product_extra_field($oldproduct_id, $newPid)
	{
		return RedshopHelperExtraFields::copyProductExtraField($oldproduct_id, $newPid);
	}

	/**
	 * Delete extra field data
	 *
	 * @param   integer  $data_id  Data ID
	 *
	 * @return  void
	 *
	 * @deprecated  __DEPLOY_VERSION__  Use RedshopHelperExtraFields::deleteExtraFieldData() instead
	 */
	public function deleteExtraFieldData($data_id)
	{
		return RedshopHelperExtraFields::deleteExtraFieldData($data_id);
	}
}
