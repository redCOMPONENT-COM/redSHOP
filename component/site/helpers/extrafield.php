<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Extra Field Class
 *
 * @since  1.6.0
 */
class extraField
{
	/**
	 * User fields
	 *
	 * @var  array
	 */
	protected static $userFields = array();

	/**
	 * @var  static
	 */
	protected static $instance = null;

	/**
	 * Returns the extraField object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  extraField  The extraField object
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
	 * Method for render fields
	 *
	 * @param   integer  $fieldSection  Field Section
	 * @param   integer  $sectionId     Section ID
	 * @param   string   $uniqueClass   Unique class
	 *
	 * @return  string
	 *
	 * @deprecated  2.1.0
	 *
	 * @see  Redshop\Fields\SiteHelper::renderFields
	 */
	public function list_all_field($fieldSection = 0, $sectionId = 0, $uniqueClass = '')
	{
		return Redshop\Fields\SiteHelper::renderFields($fieldSection, $sectionId, $uniqueClass);
	}

	/**
	 * Display User Documents
	 *
	 * @param   int     $productId         Product id
	 * @param   object  $extraFieldValues  Extra field name
	 * @param   string  $ajaxFlag          Ajax flag
	 *
	 * @return  string
	 *
	 * @deprecated  2.1.0
	 * @see Redshop\Helper\ExtraFields::displayUserDocuments
	 */
	public function displayUserDocuments($productId, $extraFieldValues, $ajaxFlag = '')
	{
		return Redshop\Helper\ExtraFields::displayUserDocuments($productId, $extraFieldValues, $ajaxFlag);
	}

	/**
	 * @param   string   $fieldSection  Field section
	 * @param   integer  $sectionId     Section ID
	 * @param   string   $fieldType     Field type
	 * @param   string   $idx           Index
	 * @param   integer  $isAtt         Is att
	 * @param   integer  $productId     Product ID
	 * @param   string   $myWish        My wish
	 * @param   integer  $addWish       Add wish
	 *
	 * @return  array
	 * @deprecated  2.1.0
	 * @see Redshop\Fields\SiteHelper::listAllUserFields
	 */
	public function list_all_user_fields($fieldSection = "", $sectionId = RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, $fieldType = '', $idx = 'NULL', $isAtt = 0, $productId, $myWish = '', $addWish = 0)
	{
		return Redshop\Fields\SiteHelper::listAllUserFields($fieldSection, $sectionId, $fieldType, $idx, $isAtt, $productId, $myWish, $addWish);
	}

	/**
	 * Method for display extra field.
	 *
	 * @param   integer  $fieldSection  Field section
	 * @param   integer  $sectionId     Section ID
	 * @param   string   $fieldName     Field name
	 * @param   string   $templateData  Template content
	 * @param   integer  $categoryPage  Category page
	 *
	 * @return  mixed
	 * @throws  Exception
	 *
	 * @since   1.6.0
	 *
	 * @deprecated  2.0.6
	 */
	public function extra_field_display($fieldSection = 0, $sectionId = 0, $fieldName = "", $templateData = "", $categoryPage = 0)
	{
		return Redshop\Helper\ExtraFields::displayExtraFields($fieldSection, $sectionId, $fieldName, $templateData, (boolean) $categoryPage);
	}

	/**
	 * Method for get field values
	 *
	 * @param   integer  $id  ID of field
	 *
	 * @return  array
	 *
	 * @since   1.6.0
	 *
	 * @deprecated  2.0.6  Use RedshopEntityField::getFieldValues instead
	 */
	public function getFieldValue($id)
	{
		return RedshopEntityField::getInstance($id)->getFieldValues();
	}

	/**
	 * Get Section Field List
	 *
	 * @param   integer  $section    Section ID
	 * @param   integer  $front      Field show in front
	 * @param   integer  $published  Field show in front
	 * @param   integer  $required   Field show in front
	 *
	 * @return  array
	 *
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::getSectionFieldList() instead
	 */
	public function getSectionFieldList($section = RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, $front = 1, $published = 1, $required = 0)
	{
		return RedshopHelperExtrafields::getSectionFieldList($section, $front, $published, $required);
	}

	/**
	 * Method for get section field names.
	 *
	 * @param   int  $section    Section ID
	 * @param   int  $front      Is show on front?
	 * @param   int  $published  Is published?
	 * @param   int  $required   Is required?
	 *
	 * @return  array            List of field
	 *
	 * @deprecated  2.1.0
	 *
	 * @see     Redshop\Helper\ExtraFields::getSectionFieldNames
	 */
	public function getSectionFieldNameArray($section = RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, $front = 1, $published = 1, $required = 0)
	{
		return Redshop\Helper\ExtraFields::getSectionFieldNames($section, $front, $published, $required);
	}

	/**
	 * Method for get section field names.
	 *
	 * @param   int  $section    Section ID
	 * @param   int  $front      Is show on front?
	 * @param   int  $published  Is published?
	 * @param   int  $required   Is required?
	 *
	 * @return  array            List of field
	 *
	 * @deprecated  2.0.6  Use RedshopHelperExtrafields::getSectionFieldList instead
	 */
	public function getSectionFieldIdArray($section = RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, $front = 1, $published = 1, $required = 0)
	{
		return RedshopHelperExtrafields::getSectionFieldList($section, $front, $published, $required);
	}

	/**
	 * Get Section Field Data List
	 *
	 * @param   int  $fieldId      Field id
	 * @param   int  $section      Section
	 * @param   int  $sectionItem  Section item
	 *
	 * @deprecated 1.6.1  Use RedshopHelperExtrafields::getData instead
	 *
	 * @return mixed|null
	 */
	public function getSectionFieldDataList($fieldId, $section = 0, $sectionItem = 0)
	{
		return RedshopHelperExtrafields::getData($fieldId, $section, $sectionItem);
	}
}
