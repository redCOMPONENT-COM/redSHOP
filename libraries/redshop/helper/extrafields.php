<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

/**
 * Class Redshop Helper ExtraFields
 *
 * @since  1.6.1
 */
class RedshopHelperExtrafields
{
	/**
	 * Extra Field Type for Input Text Element
	 *
	 * @var  int
	 */
	const TYPE_TEXT = 1;

	/**
	 * Extra Field Type for Input Text Area Element
	 *
	 * @var  int
	 */
	const TYPE_TEXT_AREA = 2;

	/**
	 * Extra Field Type for Checkboxes Element
	 *
	 * @var  int
	 */
	const TYPE_CHECK_BOX = 3;

	/**
	 * Extra Field Type for Input Radio Button Element
	 *
	 * @var  int
	 */
	const TYPE_RADIO_BUTTON = 4;

	/**
	 * Extra Field Type for Input Single Select Element
	 *
	 * @var  int
	 */
	const TYPE_SELECT_BOX_SINGLE = 5;

	/**
	 * Extra Field Type for Input Multi Select Element
	 *
	 * @var  int
	 */
	const TYPE_SELECT_BOX_MULTIPLE = 6;

	/**
	 * Extra Field Type for Country Select List Element
	 *
	 * @var  int
	 */
	const TYPE_SELECT_COUNTRY_BOX = 7;

	/**
	 * Extra Field Type for WYSIWYG Editor
	 *
	 * @var  int
	 */
	const TYPE_WYSIWYG = 8;

	/**
	 * Extra Field Type for Input Media element
	 *
	 * @var  int
	 */
	const TYPE_MEDIA = 9;

	/**
	 * Extra Field Type for Document
	 *
	 * @var  int
	 */
	const TYPE_DOCUMENTS = 10;

	/**
	 * Extra Field Type for Image select
	 *
	 * @var  int
	 */
	const TYPE_IMAGE_SELECT = 11;

	/**
	 * Extra Field Type for Date Picket element.
	 *
	 * @var  int
	 */
	const TYPE_DATE_PICKER = 12;

	/**
	 * Extra Field Type for with link
	 *
	 * @var  int
	 */
	const TYPE_IMAGE_WITH_LINK = 13;

	/**
	 * Extra Field Type for selection based on selected condition.
	 *
	 * @var  int
	 */
	const TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS = 15;

	/**
	 * Extra Field Type for product finder date picker.
	 *
	 * @var  int
	 */
	const TYPE_PRODUCT_FINDER_DATE_PICKER = 17;

	/**
	 * Extra Field Section Id for Product
	 *
	 * @var  integer
	 */
	const SECTION_PRODUCT = 1;

	/**
	 * Extra Field Section Id for Category
	 *
	 * @var  integer
	 */
	const SECTION_CATEGORY = 2;

	/**
	 * Extra Field Section Id for Form
	 *
	 * @var  integer
	 */
	const SECTION_FORM = 3;

	/**
	 * Extra Field Section Id for Email
	 *
	 * @var  integer
	 */
	const SECTION_EMAIL = 4;

	/**
	 * Extra Field Section Id for Confirmation
	 *
	 * @var  integer
	 */
	const SECTION_CONFIRMATION = 5;

	/**
	 * Extra Field Section Id for User information
	 *
	 * @var  integer
	 */
	const SECTION_USER_INFORMATIONS = 6;

	/**
	 * Extra Field Section Id for Private Billing Address
	 *
	 * @var  integer
	 */
	const SECTION_PRIVATE_BILLING_ADDRESS = 7;

	/**
	 * Extra Field Section Id for Private Billing Address
	 *
	 * @var  integer
	 */
	const SECTION_COMPANY_BILLING_ADDRESS = 8;

	/**
	 * Extra Field Section Id for Color Sample
	 *
	 * @var  integer
	 */
	const SECTION_COLOR_SAMPLE = 9;

	/**
	 * Extra Field Section Id for Manufacturer
	 *
	 * @var  integer
	 */
	const SECTION_MANUFACTURER = 10;

	/**
	 * Extra Field Section Id for Shipping
	 *
	 * @var  integer
	 */
	const SECTION_SHIPPING = 11;

	/**
	 * Extra Field Section Id for Product User Field
	 *
	 * @var  integer
	 */
	const SECTION_PRODUCT_USERFIELD = 12;

	/**
	 * Extra Field Section Id for Gift Card User Field
	 *
	 * @var  integer
	 */
	const SECTION_GIFT_CARD_USER_FIELD = 13;

	/**
	 * Extra Field Section Id for Private Shipping Address
	 *
	 * @var  integer
	 */
	const SECTION_PRIVATE_SHIPPING_ADDRESS = 14;

	/**
	 * Extra Field Section Id for Company Shipping Address
	 *
	 * @var  integer
	 */
	const SECTION_COMPANY_SHIPPING_ADDRESS = 15;

	/**
	 * Extra Field Section Id for Quotation
	 *
	 * @var  integer
	 */
	const SECTION_QUOTATION = 16;

	/**
	 * Extra Field Section Id for Date Picker
	 *
	 * @var  integer
	 */
	const SECTION_PRODUCT_FINDER_DATE_PICKER = 17;

	/**
	 * Extra Field Section Id for Payment Gateways
	 *
	 * @var  integer
	 */
	const SECTION_PAYMENT_GATEWAY = 18;

	/**
	 * Extra Field Section Id for Shipping Gateways
	 *
	 * @var  integer
	 */
	const SECTION_SHIPPING_GATEWAY = 19;

	/**
	 * Extra Field Section Id for Order
	 *
	 * @var  integer
	 */
	const SECTION_ORDER = 20;

	/**
	 * List of fields data
	 *
	 * @var  array
	 */
	protected static $fieldsData = array();

	/**
	 * All fields information
	 *
	 * @var  array
	 */
	protected static $fieldsName = array();

	/**
	 * List of fields
	 *
	 * @var   array
	 *
	 * @since  2.0.3
	 */
	protected static $sectionFields = array();

	/**
	 * Extra field display data
	 *
	 * @var   array
	 *
	 * @since  2.0.6
	 */
	protected static $extraFieldDisplay = array();

	/**
	 * Get list of fields.
	 *
	 * @param   integer $published  Published Status which needs to be get. Default -1 will ignore any status.
	 * @param   integer $limitStart Set limit start
	 * @param   integer $limit      Set limit
	 *
	 * @return  array    Array of all the available fields based on arguments.
	 */
	public static function getList($published = -1, $limitStart = 0, $limit = 0)
	{
		$db = JFactory::getDbo();

		if (!empty(self::$fieldsName))
		{
			return self::$fieldsName;
		}

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_fields'));

		if ($published >= 0)
		{
			$query->where($db->qn('published') . ' = ' . (int) $published);
		}

		self::$fieldsName = $db->setQuery($query, $limitStart, $limit)->loadObjectList('name');

		return self::$fieldsName;
	}

	/**
	 * Get field information from field name.
	 *
	 * @param   string $name Field name prefixed with `rs_`
	 *
	 * @return  object|null    Field information object otherwise null.
	 */
	public static function getField($name)
	{
		$fields = self::getList();

		if (array_key_exists($name, $fields))
		{
			return $fields[$name];
		}

		return null;
	}

	/**
	 * Get Section Field Data List
	 *
	 * @param   int $name        Name of the field - Typically contains `rs_` prefix.
	 * @param   int $section     Section id of the field.
	 * @param   int $sectionItem Section item id
	 *
	 * @return mixed|null
	 */
	public static function getDataByName($name, $section, $sectionItem)
	{
		// Get Field id
		$fieldId = self::getField($name)->id;

		return self::getData($fieldId, $section, $sectionItem);
	}

	/**
	 * Get Section Field Data List
	 *
	 * @param   int $fieldId     Field id
	 * @param   int $section     Section id of the field.
	 * @param   int $sectionItem Section item id
	 *
	 * @return  mixed|null
	 */
	public static function getData($fieldId, $section, $sectionItem)
	{
		$key = $fieldId . '.' . $section . '.' . $sectionItem;

		if (array_key_exists($key, self::$fieldsData))
		{
			return self::$fieldsData[$key];
		}

		// Init null.
		self::$fieldsData[$key] = null;

		if ($section == 1)
		{
			$product = Redshop::product((int) $sectionItem);

			if ($product && isset($product->extraFields[$fieldId]))
			{
				self::$fieldsData[$key] = $product->extraFields[$fieldId];
			}
		}

		if (($section == 1 && !self::$fieldsData[$key]) || $section != 1)
		{
			$db                     = JFactory::getDbo();
			$query                  = $db->getQuery(true)
				->select('fd.*')
				->select($db->qn('f.title'))
				->from($db->qn('#__redshop_fields_data', 'fd'))
				->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('fd.fieldid') . ' = ' . $db->qn('f.id'))
				->where($db->qn('fd.itemid') . ' = ' . (int) $sectionItem)
				->where($db->qn('fd.fieldid') . ' = ' . (int) $fieldId)
				->where($db->qn('fd.section') . ' = ' . $db->quote($section));
			self::$fieldsData[$key] = $db->setQuery($query)->loadObject();
		}

		return self::$fieldsData[$key];
	}

	/**
	 * List all field in product
	 *
	 * @param   integer  $section  Section product
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 */
	public static function listAllFieldInProduct($section = extraField::SECTION_PRODUCT)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('section') . ' = ' . (int) $section)
			->where($db->qn('display_in_product') . ' = 1')
			->where($db->qn('published') . ' = 1')
			->order($db->qn('ordering'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * List all fields
	 *
	 * @param   string   $fieldSection  Field section
	 * @param   integer  $sectionId     Section ID
	 * @param   string   $fieldName     Field name
	 * @param   string   $table         Table
	 * @param   string   $templateDesc  Template
	 * @param   string   $userEmail     User email
	 *
	 * @return  string                  HTML <td></td>
	 *
	 * @since   2.0.3
	 */
	public static function listAllField($fieldSection = '', $sectionId = 0, $fieldName = '', $table = '', $templateDesc = '', $userEmail = '')
	{
		$db = JFactory::getDbo();

		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');
		/** @var RedshopModelFields $model */
		$model = JModelLegacy::getInstance('Fields', 'RedshopModel');
		$rowData = $model->getFieldsBySection($fieldSection, $fieldName);

		$exField = '';

		if (count($rowData) > 0 && $table == "")
		{
			$exField = '<table class="admintable" border="0" >';
		}

		for ($i = 0, $in = count($rowData); $i < $in; $i++)
		{
			$type            = $rowData[$i]->type;
			$dataValue       = self::getSectionFieldDataList($rowData[$i]->id, $fieldSection, $sectionId);
			$exField         .= '<tr>';
			$extraFieldValue = "";
			$extraFieldLabel = JText::_($rowData[$i]->title);

			$required = '';
			$reqlbl   = ' reqlbl="" ';
			$errormsg = ' errormsg="" ';

			if ($fieldSection == extraField::SECTION_QUOTATION && $rowData[$i]->required == 1)
			{
				$required = ' required="1" ';
				$reqlbl   = ' reqlbl="' . $extraFieldLabel . '" ';
				$errormsg = ' errormsg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '" ';
			}

			switch ($type)
			{
				case extraField::TYPE_TEXT:
					$textValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$exField .= RedshopLayoutHelper::render(
						'extrafields.field.text',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'textValue'       => $textValue
							)
					);
					break;

				case extraField::TYPE_TEXT_AREA:
					$textareaValue   = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..textarea',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'textValue'       => $textareaValue
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);
					break;

				case extraField::TYPE_CHECK_BOX:
					$fieldChk = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
					$chkData  = explode(",", $dataValue->data_txt);
					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..checkbox',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'fieldCheck'      => $fieldChk,
								'checkData'       => $chkData
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);
					break;

				case extraField::TYPE_RADIO_BUTTON:
					$fieldChk = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
					$chkData  = explode(",", $dataValue->data_txt);
					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..radio',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'fieldCheck'      => $fieldChk,
								'checkData'       => $chkData
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);
					break;

				case extraField::TYPE_SELECT_BOX_SINGLE:
					$fieldChk = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
					$chkData  = explode(",", $dataValue->data_txt);
					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..select',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'fieldCheck'      => $fieldChk,
								'checkData'       => $chkData
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);
					break;

				case extraField::TYPE_SELECT_BOX_MULTIPLE:
					$fieldChk = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
					$chkData  = explode(",", $dataValue->data_txt);
					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..multiple',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'fieldCheck'      => $fieldChk,
								'checkData'       => $chkData
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);
					break;

				case extraField::TYPE_SELECT_COUNTRY_BOX:
					$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__redshop_country'));
					$db->setQuery($query);
					$fieldChk = $db->loadObjectList();
					$chkData  = @explode(",", $dataValue->data_txt);
					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..multiple',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'fieldCheck'      => $fieldChk,
								'checkData'       => $chkData
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);
					break;

				case extraField::TYPE_WYSIWYG:
					$editor          = JFactory::getEditor();
					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..editor',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'textValue'       => $textareaValue,
								'editor'          => $editor
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);
					break;

				case extraField::TYPE_DOCUMENTS:
					$dataTxt = array();

					if (is_object($dataValue) && property_exists($dataValue, 'data_txt'))
					{
						// Support Legacy string.
						if (preg_match('/\n/', $dataValue->data_txt))
						{
							$documentExplode = explode("\n", $dataValue->data_txt);
							$dataTxt         = array($documentExplode[0] => $documentExplode[1]);
						}
						else
						{
							// Support for multiple file upload using JSON for better string handling
							$dataTxt = json_decode($dataValue->data_txt);
						}
					}

					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..document',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'dataTxt'         => $dataTxt,
								'dataValue'       => $dataValue
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);
					break;

				case extraField::TYPE_IMAGE_SELECT:

					$fieldChk  = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
					$dataValue = self::getSectionFieldDataList($rowData[$i]->id, $fieldSection, $sectionId);
					$value     = '';

					if ($dataValue)
					{
						$value = $dataValue->data_txt;
					}

					$chkData = explode(",", $dataValue->data_txt);
					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..image',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'fieldCheck'      => $fieldChk,
								'checkData'       => $chkData,
								'value'           => $value,
								'sectionId'       => $sectionId
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);
					break;

				case extraField::TYPE_DATE_PICKER:

					if ($rowData[$i]->section != 17)
					{
						$date = date("d-m-Y", time());
					}
					else
					{
						$date = '';
					}

					if ($dataValue)
					{
						if ($dataValue->data_txt)
						{
							$date = date("d-m-Y", strtotime($dataValue->data_txt));
						}
					}

					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..date_picker',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'date'            => $date
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);

					break;

				case extraField::TYPE_IMAGE_WITH_LINK:

					$fieldChk      = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
					$dataValue     = self::getSectionFieldDataList($rowData[$i]->id, $fieldSection, $sectionId);
					$value         = ($dataValue) ? $dataValue->data_txt : '';
					$tmpImageHover = array();
					$tmpImageLink  = array();

					if ($dataValue->altText)
					{
						$tmpImageHover = explode(',,,,,', $dataValue->altText);
					}

					if ($dataValue->image_link)
					{
						$tmpImageLink = @explode(',,,,,', $dataValue->image_link);
					}

					$chkData    = explode(",", $dataValue->data_txt);
					$imageLink  = array();
					$imageHover = array();

					for ($ch = 0; $ch < count($chkData); $ch++)
					{
						$imageLink[$chkData[$ch]]  = $tmpImageLink[$ch];
						$imageHover[$chkData[$ch]] = $tmpImageHover[$ch];
					}

					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..image_link',
						array(
								'rowData'         => $rowData[$i],
								'extraFieldLabel' => $extraFieldLabel,
								'required'        => $required,
								'requiredLabel'   => $reqlbl,
								'errorMsg'        => $errormsg,
								'fieldCheck'      => $fieldChk,
								'checkData'       => $chkData,
								'value'           => $value,
								'sectionId'       => $sectionId,
								'imageLink'       => $imageLink,
								'imageHover'      => $imageHover
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);

					break;

				case extraField::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS:

					if ($dataValue)
					{
						if ($dataValue->data_txt)
						{
							$mainSplitDateTotal = explode(" ", $dataValue->data_txt);
							$mainSplitDate      = explode(":", $mainSplitDateTotal[0]);
							$mainSplitDateExtra = explode(":", $mainSplitDateTotal[1]);
							$datePublish        = date("d-m-Y", $mainSplitDate[0]);
							$dateExpiry         = date("d-m-Y", $mainSplitDate[1]);
						}
						else
						{
							$datePublish        = date("d-m-Y");
							$dateExpiry         = date("d-m-Y");
							$mainSplitDateExtra = array();
						}
					}
					else
					{
						$datePublish        = date("d-m-Y");
						$dateExpiry         = date("d-m-Y");
						$mainSplitDateExtra = array();
					}

					$exField .= RedshopLayoutHelper::render(
						'extrafields.field..selected_condition',
						array(
								'rowData'            => $rowData[$i],
								'extraFieldLabel'    => $extraFieldLabel,
								'required'           => $required,
								'requiredLabel'      => $reqlbl,
								'errorMsg'           => $errormsg,
								'datePublish'        => $datePublish,
								'dateExpiry'         => $dateExpiry,
								'mainSplitDateExtra' => $mainSplitDateExtra
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);

					break;
			}

			if (trim($templateDesc) != '')
			{
				if (strstr($templateDesc, "{" . $rowData[$i]->name . "}"))
				{
					$templateDesc = str_replace("{" . $rowData[$i]->name . "}", $extraFieldValue, $templateDesc);
					$templateDesc = str_replace("{" . $rowData[$i]->name . "_lbl}", $extraFieldLabel, $templateDesc);
				}

				$templateDesc = str_replace("{" . $rowData[$i]->name . "}", "", $templateDesc);
				$templateDesc = str_replace("{" . $rowData[$i]->name . "_lbl}", "", $templateDesc);
			}
			else
			{
				if (trim($rowData[$i]->desc) == '')
				{
					$exField .= '<td valign="top">';
				}
				else
				{
					$exField .= '<td valign="top">&nbsp; ' . JHtml::tooltip($rowData[$i]->desc, $rowData[$i]->name, 'tooltip.png', '', '', false);
				}
			}

			$exField .= '</td></tr>';
		}

		if (count($rowData) > 0 && $table == "")
		{
			$exField .= '</table>';
		}

		if (trim($templateDesc) != '')
		{
			return $templateDesc;
		}

		return $exField;
	}

	/**
	 * Save extra fields
	 *
	 * @param   array   $data         Data to insert
	 * @param   integer $fieldSection Field section to match
	 * @param   string  $sectionId    Section ID
	 * @param   string  $userEmail    User to match by email
	 *
	 * @return  void
	 *
	 * @since 2.0.3
	 */
	public static function extraFieldSave($data, $fieldSection, $sectionId = "", $userEmail = "")
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('section') . ' = ' . (int) $fieldSection)
			->where($db->qn('published') . ' = 1');

		$db->setQuery($query);
		$rowData = $db->loadObjectlist();

		for ($i = 0, $in = count($rowData); $i < $in; $i++)
		{
			$dataTxt = '';

			if (isset($data[$rowData[$i]->name]))
			{
				if ($rowData[$i]->type == 8 || $rowData[$i]->type == 1 || $rowData[$i]->type == 2)
				{
					$dataTxt = JFactory::getApplication()->input->get($rowData[$i]->name, '', 'RAW');
				}
				else
				{
					$dataTxt = $data[$rowData[$i]->name];
				}
			}

			// Save Document Extra Field
			if ($rowData[$i]->type == extraField::TYPE_DOCUMENTS)
			{
				$files = $_FILES[$rowData[$i]->name]['name'];
				$texts = $data['text_' . $rowData[$i]->name];

				$documentsValue = array();

				if (isset($data[$rowData[$i]->name]))
				{
					$documentsValue = $data[$rowData[$i]->name];
				}

				$total = count($files);

				if (is_array($files) && $total > 0)
				{
					$documents = array();

					for ($ij = 0; $ij < $total; $ij++)
					{
						$file = $files[$ij];

						// Editing uploaded file
						if (isset($documentsValue[$ij]) && $documentsValue[$ij] != "")
						{
							if (trim($texts[$ij]) != '')
							{
								$documents[trim($texts[$ij])] = $documentsValue[$ij];
							}
							else
							{
								$documents[$ij] = $documentsValue[$ij];
							}
						}

						if ($file != "")
						{
							$name = RedshopHelperMedia::cleanFileName($file);

							$src         = $_FILES[$rowData[$i]->name]['tmp_name'][$ij];
							$destination = REDSHOP_FRONT_DOCUMENT_RELPATH . 'extrafields/' . $name;

							JFile::upload($src, $destination);

							if (trim($texts[$ij]) != '')
							{
								$documents[trim($texts[$ij])] = $name;
							}
							else
							{
								$documents[$ij] = $name;
							}
						}
					}

					// Convert array into JSON string for better handler.
					$dataTxt = json_encode($documents);
				}
			}

			if ($rowData[$i]->type == extraField::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS)
			{
				if ($data[$rowData[$i]->name] != "" && $data[$rowData[$i]->name . "_expiry"] != "")
				{
					$dataTxt = strtotime($data[$rowData[$i]->name]) . ":" . strtotime($data[$rowData[$i]->name . "_expiry"]) . " ";

					if (count($data[$rowData[$i]->name . "_extra_name"]) > 0)
					{
						for ($r = 0; $r < count($data[$rowData[$i]->name . "_extra_name"]); $r++)
						{
							$dataTxt .= strtotime($data[$rowData[$i]->name . "_extra_name"][$r]) . ":";
						}
					}
				}
			}

			if (is_array($dataTxt))
			{
				$dataTxt = implode(",", $dataTxt);
			}

			$sect = explode(",", $fieldSection);

			if ($rowData[$i]->type == extraField::TYPE_IMAGE_SELECT || $rowData[$i]->type == extraField::TYPE_IMAGE_WITH_LINK)
			{
				$list = self::getSectionFieldDataList($rowData[$i]->id, $fieldSection, $sectionId, $userEmail);

				if ($rowData[$i]->type == extraField::TYPE_IMAGE_WITH_LINK)
				{
					$fieldValueArray = explode(',', $data['imgFieldId' . $rowData[$i]->id]);
					$imageHover      = array();
					$imageLink       = array();

					for ($fi = 0; $fi < count($fieldValueArray); $fi++)
					{
						$imageHover[$fi] = $data['image_hover' . $fieldValueArray[$fi]];
						$imageLink[$fi]  = $data['image_link' . $fieldValueArray[$fi]];
					}

					$strImageHover = implode(',,,,,', $imageHover);
					$strImageLink  = implode(',,,,,', $imageLink);

					$sql = $db->getQuery(true);
					$sql->update($db->qn('#__redshop_fields_data'))
						->set($db->qn('alt_text') . ' = ' . $db->quote($strImageHover))
						->set($db->qn('image_link') . ' = ' . $db->quote($strImageLink))
						->where($db->qn('itemid') . ' = ' . (int) $sectionId)
						->where($db->qn('section') . ' = ' . $db->quote($fieldSection))
						->where($db->qn('user_email') . ' = ' . $db->quote($userEmail))
						->where($db->qn('fieldid') . ' = ' . (int) $rowData[$i]->id);

					$db->setQuery($sql);
					$db->execute();
				}

				// Reset $sql query
				$sql = $db->getQuery(true);

				if (count($list) > 0)
				{
					$sql->update($db->qn('#__redshop_fields_data'))
						->set($db->qn('data_txt') . ' = ' . $db->quote($data['imgFieldId' . $rowData[$i]->id]))
						->where($db->qn('itemid') . ' = ' . (int) $sectionId)
						->where($db->qn('section') . ' = ' . $db->quote($fieldSection))
						->where($db->qn('user_email') . ' = ' . $db->quote($userEmail))
						->where($db->qn('fieldid') . ' = ' . (int) $rowData[$i]->id);
				}
				else
				{
					$sql->insert($db->qn('#__redshop_fields_data'))
						->columns($db->qn(array('fieldid', 'data_txt', 'itemid', 'section', 'alt_text', 'image_link', 'user_email')))
						->values(implode(',', array((int) $rowData[$i]->id, $db->quote($data['imgFieldId' . $rowData[$i]->id]), (int) $sectionId, $db->quote($fieldSection), $db->quote($strImageHover), $db->quote($strImageLink), $db->quote($userEmail))));
				}

				$db->setQuery($sql);
				$db->execute();
			}
			else
			{
				for ($h = 0, $hn = count($sect); $h < $hn; $h++)
				{
					$list = self::getSectionFieldDataList($rowData[$i]->id, $sect[$h], $sectionId, $userEmail);

					if (count($list) > 0)
					{
						$sql = $db->getQuery(true);
						$sql->update($db->qn('#__redshop_fields_data'))
							->set($db->qn('data_txt') . ' = ' . $db->quote($dataTxt))
							->where($db->qn('itemid') . ' = ' . (int) $sectionId)
							->where($db->qn('section') . ' = ' . (int) $sect[$h])
							->where($db->qn('user_email') . ' = ' . $db->quote($userEmail))
							->where($db->qn('fieldid') . ' = ' . (int) $rowData[$i]->id);

						$db->setQuery($sql);
						$db->execute();
					}
					elseif (!empty($dataTxt))
					{
						$sql = $db->getQuery(true);
						$sql->insert($db->qn('#__redshop_fields_data'))
							->columns($db->qn(array('fieldid', 'data_txt', 'itemid', 'section', 'user_email')))
							->values(implode(',', array((int) $rowData[$i]->id, $db->quote($dataTxt), (int) $sectionId, (int) $sect[$h], $db->quote($userEmail))));

						$db->setQuery($sql);
						$db->execute();
					}
				}
			}
		}
	}

	/**
	 * Validate Extra Field
	 *
	 * @param   string  $fieldSection Field Section List
	 * @param   integer $sectionId    Section ID
	 *
	 * @return  boolean
	 *
	 * @since 2.0.3
	 */
	public static function CheckExtraFieldValidation($fieldSection = "", $sectionId = 0)
	{
		$rowData = self::getSectionFieldList($fieldSection);

		for ($i = 0, $in = count($rowData); $i < $in; $i++)
		{
			$required  = $rowData[$i]->required;
			$dataValue = self::getSectionFieldDataList($rowData[$i]->id, $fieldSection, $sectionId);

			if (empty($dataValue) && $required)
			{
				return $rowData[$i]->title;
			}
		}

		return false;
	}

	/**
	 * List all fields and display
	 *
	 * @param   string  $fieldSection Field section
	 * @param   integer $sectionId    Section ID
	 * @param   integer $flag         Flag
	 * @param   string  $userEmail    User email
	 * @param   string  $templateDesc Template description
	 * @param   boolean $sendmail     True/ False
	 *
	 * @return string
	 *
	 * @since 2.0.3
	 */
	public static function listAllFieldDisplay($fieldSection = "", $sectionId = 0, $flag = 0, $userEmail = "", $templateDesc = "", $sendmail = false)
	{
		$db = JFactory::getDbo();

		$rowData = self::getSectionFieldList($fieldSection);

		$exField = '';

		for ($i = 0, $in = count($rowData); $i < $in; $i++)
		{
			$type            = $rowData[$i]->type;
			$extraFieldValue = "";
			$extraFieldLabel = $rowData[$i]->title;

			if ($flag == 1)
			{
				if ($i > 0)
				{
					$exField .= "<br />";
				}

				$exField .= JText::_($extraFieldLabel) . ' : ';
			}

			$dataValue = self::getSectionFieldDataList($rowData[$i]->id, $fieldSection, $sectionId, $userEmail);

			switch ($type)
			{
				case extraField::TYPE_TEXT:
					$extraFieldValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$exField         .= $extraFieldValue;
					break;

				case extraField::TYPE_TEXT_AREA:
					$extraFieldValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$exField         .= $extraFieldValue;
					break;

				case extraField::TYPE_CHECK_BOX:
					$fieldChk = self::getFieldValue($rowData[$i]->id);
					$chkData  = @explode(",", $dataValue->data_txt);

					$extraFieldValue = '';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						if (@in_array($fieldChk[$c]->field_value, $chkData))
						{
							$extraFieldValue .= $fieldChk[$c]->field_value;
						}
					}

					$exField .= $extraFieldValue;
					break;

				case extraField::TYPE_RADIO_BUTTON:
					$fieldChk = self::getFieldValue($rowData[$i]->id);
					$chkData  = @explode(",", $dataValue->data_txt);

					$extraFieldValue = '';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						if (@in_array($fieldChk[$c]->field_value, $chkData))
						{
							$extraFieldValue .= $fieldChk[$c]->field_value;
						}
					}

					$exField .= $extraFieldValue;
					break;

				case extraField::TYPE_SELECT_BOX_SINGLE:
					$fieldChk = self::getFieldValue($rowData[$i]->id);
					$chkData  = @explode(",", $dataValue->data_txt);

					$extraFieldValue = '';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						if (@in_array($fieldChk[$c]->field_value, $chkData))
						{
							$extraFieldValue .= $fieldChk[$c]->field_value;
						}
					}

					$exField .= $extraFieldValue;
					break;

				case extraField::TYPE_SELECT_BOX_MULTIPLE:
					$fieldChk = self::getFieldValue($rowData[$i]->id);
					$chkData  = @explode(",", $dataValue->data_txt);

					$extraFieldValue = '';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						if (@in_array($fieldChk[$c]->field_value, $chkData))
						{
							if ($c > 0)
							{
								$extraFieldValue .= "," . $fieldChk[$c]->field_value;
							}
							else
							{
								$extraFieldValue .= $fieldChk[$c]->field_value;
							}
						}
					}

					$exField .= $extraFieldValue;
					break;

				case extraField::TYPE_SELECT_COUNTRY_BOX:
					$extraFieldValue = "";

					if ($dataValue && $dataValue->data_txt)
					{
						$fieldChk        = RedshopEntityCountry::getInstance($dataValue->data_txt);
						$extraFieldValue = $fieldChk->get('country_name');
					}

					$exField .= $extraFieldValue;
					break;

				// 12 :- Date Picker
				case extraField::TYPE_DATE_PICKER:
					$extraFieldValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$exField         .= $extraFieldValue;
					break;
			}

			if (trim($templateDesc) != '')
			{
				if (strstr($templateDesc, "{" . $rowData[$i]->name . "}"))
				{
					$templateDesc = str_replace("{" . $rowData[$i]->name . "}", $extraFieldValue, $templateDesc);
					$templateDesc = str_replace("{" . $rowData[$i]->name . "_lbl}", $extraFieldLabel, $templateDesc);
				}

				$templateDesc = str_replace("{" . $rowData[$i]->name . "}", "", $templateDesc);
				$templateDesc = str_replace("{" . $rowData[$i]->name . "_lbl}", "", $templateDesc);
			}
		}

		if (trim($templateDesc) != '')
		{
			return $templateDesc;
		}

		if ($flag == 0 && !empty($extraFieldLabel))
		{
			$client      = null;
			$fieldLayout = 'fields.display';

			if ($sendmail)
			{
				$fieldLayout = 'fields.mail';
				$client      = array('client' => 0);
			}

			return RedshopLayoutHelper::render(
				$fieldLayout,
				array('extra_field_label' => JText::_($extraFieldLabel), 'extra_field_value' => $exField),
				null,
				$client
			);
		}

		return $exField;
	}

	/**
	 * List all user fields
	 *
	 * @param   string  $fieldSection Field Section
	 * @param   integer $sectionId    Section ID
	 * @param   string  $fieldType    Field type
	 * @param   string  $uniqueId     Unique ID
	 *
	 * @return  string
	 *
	 * @since 2.0.3
	 */
	public static function listAllUserFields($fieldSection = "", $sectionId = extraField::SECTION_PRODUCT_USERFIELD, $fieldType = '', $uniqueId = '')
	{
		JHtml::script('com_redshop/attribute.js', false, true);
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('section') . ' = ' . (int) $sectionId)
			->where($db->qn('name') . ' = ' . $db->quote($fieldSection))
			->where($db->qn('published') . ' = 1');

		$db->setQuery($query);

		$rowData      = $db->loadObjectlist();
		$exField      = '';
		$exFieldTitle = '';
		$cart = JFactory::getSession()->get('cart');
		$idx  = 0;

		if (isset($cart['idx']))
		{
			$idx = (int) ($cart['idx']);
		}

		for ($i = 0, $in = count($rowData); $i < $in; $i++)
		{
			$type     = $rowData[$i]->type;
			$asterisk = $rowData[$i]->required > 0 ? '* ' : '';

			if ($fieldType != 'hidden')
			{
				$exFieldTitle .= '<div class="userfield_label">' . $asterisk . $rowData[$i]->title . '</div>';
			}

			$textValue = '';

			if ($fieldType == 'hidden')
			{
				$exField .= '<input type="hidden" name="extrafieldId' . $uniqueId . '[]"  value="' . $rowData[$i]->id . '" />';
			}
			else
			{
				$req = ' required = "' . $rowData[$i]->required . '"';

				switch ($type)
				{
					case extraField::TYPE_TEXT:
						$exField .= RedshopLayoutHelper::render(
							'extrafields.userfield.text',
							array(
									'rowData'  => $rowData[$i],
									'required' => $req,
									'uniqueId' => $uniqueId
								)
						);
						break;

					case extraField::TYPE_TEXT_AREA:
						$exField .= RedshopLayoutHelper::render(
							'extrafields.userfield.textarea',
							array(
									'rowData'  => $rowData[$i],
									'required' => $req,
									'uniqueId' => $uniqueId
								)
						);
						break;

					case extraField::TYPE_CHECK_BOX:
						$fieldChk = self::getFieldValue($rowData[$i]->id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->name]);
						$exField .= RedshopLayoutHelper::render(
							'extrafields.userfield.checkbox',
							array(
									'rowData'    => $rowData[$i],
									'required'   => $required,
									'fieldCheck' => $req,
									'checkData'  => $chkData,
									'uniqueId'   => $uniqueId
								)
						);
						break;

					case extraField::TYPE_RADIO_BUTTON:
						$fieldChk = self::getFieldValue($rowData[$i]->id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->name]);
						$exField .= RedshopLayoutHelper::render(
							'extrafields.userfield.checkbox',
							array(
									'rowData'    => $rowData[$i],
									'required'   => $required,
									'fieldCheck' => $req,
									'checkData'  => $chkData,
									'uniqueId'   => $uniqueId
								)
						);
						break;

					case extraField::TYPE_SELECT_BOX_SINGLE:
						$fieldChk = self::getFieldValue($rowData[$i]->id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->name]);
						$exField .= RedshopLayoutHelper::render(
							'extrafields.userfield.select',
							array(
									'rowData'    => $rowData[$i],
									'required'   => $required,
									'fieldCheck' => $req,
									'checkData'  => $chkData,
									'uniqueId'   => $uniqueId
								)
						);
						break;

					case extraField::TYPE_SELECT_BOX_MULTIPLE:
						$fieldChk = self::getFieldValue($rowData[$i]->id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->name]);
						$exField .= RedshopLayoutHelper::render(
							'extrafields.userfield.multiple',
							array(
									'rowData'    => $rowData[$i],
									'required'   => $required,
									'fieldCheck' => $req,
									'checkData'  => $chkData,
									'uniqueId'   => $uniqueId
								)
						);
						break;

					case extraField::TYPE_DOCUMENTS:
						$exField .= RedshopLayoutHelper::render(
							'extrafields.userfield.date_picker',
							array(
									'rowData'    => $rowData[$i],
									'required'   => $required,
									'fieldCheck' => $req,
									'uniqueId'   => $uniqueId
								)
						);
						break;

					case extraField::TYPE_IMAGE_SELECT:
						$fieldChk = self::getFieldValue($rowData[$i]->id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->name]);
						$exField .= RedshopLayoutHelper::render(
							'extrafields.userfield.image',
							array(
									'rowData'    => $rowData[$i],
									'required'   => $required,
									'fieldCheck' => $req,
									'checkData'  => $chkData,
									'uniqueId'   => $uniqueId
								)
						);
						break;

					case extraField::TYPE_DATE_PICKER:
						$ajax = '';
						$req  = $rowData[$i]->required;
						$exField .= RedshopLayoutHelper::render(
							'extrafields.userfield.date_picker',
							array(
									'rowData'    => $rowData[$i],
									'required'   => $required,
									'fieldCheck' => $req,
									'uniqueId'   => $uniqueId
								)
						);
						break;
				}
			}

			if (trim($rowData[$i]->desc) != '' && $fieldType != 'hidden')
			{
				$exField .= '<div class="userfield_tooltip">&nbsp; ' . JHtml::tooltip($rowData[$i]->desc, $rowData[$i]->name, 'tooltip.png', '', '', false) . '</div>';
			}
			else
			{
			}
		}

		$ex    = array();
		$ex[0] = $exFieldTitle;
		$ex[1] = $exField;

		return $ex;
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
	 * @since 2.0.3
	 */
	public static function booleanList($name, $attribs = null, $selected = null, $yes = 'yes', $no = 'no', $id = false)
	{
		$arr = array(
			JHtml::_('select.option', "Days", JText::_($yes)),
			JHtml::_('select.option', "Weeks", JText::_($no))
		);

		return JHtml::_('select.radiolist', $arr, $name, $attribs, 'value', 'text', $selected, $id);
	}

	/**
	 * Render HTML radio list with options
	 *
	 * @param   string   $name      Name of radio checkbox
	 * @param   array    $attribs   Attribute values
	 * @param   array    $selected  The name of the object variable for the option text
	 * @param   string   $yes       Option Days
	 * @param   string   $no        Option Weeks
	 * @param   boolean  $id        ID of radio checkbox
	 * @param   string   $yesValue  ID of radio checkbox
	 * @param   string   $noValue   ID of radio checkbox
	 *
	 * @return  string
	 *
	 * @since 2.0.3
	 */
	public static function rsBooleanList($name, $attribs = null, $selected = null, $yes = 'yes', $no = 'no', $id = false,
	                                     $yesValue = 'Days', $noValue = 'Weeks')
	{
		$arr = array(
			JHtml::_('select.option', $yesValue, JText::_($yes)),
			JHtml::_('select.option', $noValue, JText::_($no))
		);

		return JHtml::_('redshopselect.radiolist', $arr, $name, $attribs, 'value', 'text', $selected, $id);
	}

	/**
	 * Get fields value by ID
	 *
	 * @param   integer  $id  ID of field
	 *
	 * @return  array
	 *
	 * @since   2.0.3
	 *
	 * @deprecated  2.0.6  Use RedshopEntityField::getFieldValues instead.
	 */
	public static function getFieldValue($id)
	{
		return RedshopEntityField::getInstance($id)->getFieldValues();
	}

	/**
	 * Get Section Field List
	 *
	 * @param   integer $section   Section ID
	 * @param   integer $front     Field show in front
	 * @param   integer $published Field show in front
	 * @param   integer $required  Field show in front
	 *
	 * @return  array
	 *
	 * @since 2.0.3
	 */
	public static function getSectionFieldList($section = self::SECTION_PRODUCT_USERFIELD, $front = 1, $published = 1, $required = 0)
	{
		$key = $section . '_' . $front . '_' . $published . '_' . $required;

		if (!array_key_exists($key, static::$sectionFields))
		{
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
				->from($db->qn('#__redshop_fields'))
				->where($db->qn('section') . ' = ' . (int) $section)
				->order($db->qn('ordering'));

			if ($front)
			{
				$query->where($db->qn('show_in_front') . ' = ' . (int) $front);
			}

			if ($published)
			{
				$query->where($db->qn('published') . ' = ' . (int) $published);
			}

			if ($required)
			{
				$query->where($db->qn('required') . ' = ' . (int) $required);
			}

			static::$sectionFields[$key] = $db->setQuery($query)->loadObjectList();
		}

		return static::$sectionFields[$key];
	}

	/**
	 * Get section field data list
	 *
	 * @param   integer  $fieldId      Field ID
	 * @param   integer  $section      Section ID
	 * @param   integer  $orderItemId  Order Item ID
	 * @param   string   $userEmail    User Email
	 *
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function getSectionFieldDataList($fieldId, $section = 0, $orderItemId = 0, $userEmail = "")
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');

		/** @var RedshopModelFields $model */
		$model = JModelLegacy::getInstance('Fields', 'RedshopModel');

		return $model->getFieldDataList($fieldId, $section, $orderItemId, $userEmail);
	}

	/**
	 * Copy product extra field
	 *
	 * @param   integer  $oldProductId  Old Product ID
	 * @param   integer  $newPid        New Product ID
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public static function copyProductExtraField($oldProductId, $newPid)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($db->qn('#__redshop_fields_data'))
			->where($db->qn('itemid') . ' = ' . (int) $oldProductId)
			->where(
				'(' . $db->qn('section') . ' = ' . $db->quote('1')
				. ' or ' .
				$db->qn('section') . ' = ' . $db->quote('12')
				. ' or ' .
				$db->qn('section') . ' = ' . $db->quote('17') . ')'
			);

		$db->setQuery($query);
		$list = $db->loadObjectList();

		// Skip process if there are no custom fields.
		if (empty($list))
		{
			return;
		}

		$query->clear()
			->insert($db->qn('#__redshop_fields_data'))
			->columns($db->qn(array('fieldid', 'data_txt', 'itemid', 'section', 'alt_text', 'image_link', 'user_email')));

		foreach ($list as $row)
		{
			$query->values(
				implode(',', array(
						(int) $row->fieldid,
						$db->quote($row->data_txt),
						(int) $newPid,
						(int) $row->section,
						$db->quote($row->alt_text),
						$db->quote($row->image_link),
						$db->quote($row->user_email))
				)
			);
		}

		$db->setQuery($query)->execute();
	}

	/**
	 * Delete extra field data
	 *
	 * @param   integer $dataId Data ID
	 *
	 * @return  void
	 *
	 * @since 2.0.3
	 */
	public static function deleteExtraFieldData($dataId)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->delete($db->qn('#__redshop_fields_data'))
			->where($db->qn('data_id') . ' = ' . (int) $dataId);

		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Method for render HTML of extra fields
	 *
	 * @param   string  $fieldSection    Field section
	 * @param   integer $sectionId       ID of section
	 * @param   string  $fieldName       Field name
	 * @param   string  $templateContent HTML template content
	 * @param   integer $categoryPage    Category page
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 */
	public static function extraFieldDisplay($fieldSection = "", $sectionId = 0, $fieldName = "", $templateContent = "", $categoryPage = 0)
	{
		$db = JFactory::getDbo();

		if (!isset(self::$extraFieldDisplay[$fieldSection]) || !array_key_exists($fieldName, self::$extraFieldDisplay[$fieldSection]))
		{
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_fields'))
				->where($db->qn('section') . ' = ' . $db->quote($fieldSection));

			if ($fieldName != "")
			{
				$query->where($db->qn('name') . ' IN (' . $fieldName . ')');
			}

			$db->setQuery($query);

			if (!isset(self::$extraFieldDisplay[$fieldSection]))
			{
				self::$extraFieldDisplay[$fieldSection] = array();
			}

			self::$extraFieldDisplay[$fieldSection][$fieldName] = $db->loadObjectList();
		}

		$rowsData = self::$extraFieldDisplay[$fieldSection][$fieldName];

		for ($i = 0, $in = count($rowsData); $i < $in; $i++)
		{
			$type        = $rowsData[$i]->type;
			$published   = $rowsData[$i]->published;
			$showInFront = $rowsData[$i]->show_in_front;
			$dataValue   = self::getData($rowsData[$i]->id, $fieldSection, $sectionId);

			if ($categoryPage == 1)
			{
				$searchLabel = "{producttag:" . $rowsData[$i]->name . "_lbl}";
				$search      = "{producttag:" . $rowsData[$i]->name . "}";
			}
			else
			{
				$searchLabel = "{" . $rowsData[$i]->name . "_lbl}";
				$search      = "{" . $rowsData[$i]->name . "}";
			}

			if (empty($dataValue) || !$published || (!$showInFront && JFactory::getApplication()->isSite()))
			{
				$templateContent = str_replace($searchLabel, "", $templateContent);
				$templateContent = str_replace($search, "", $templateContent);

				continue;
			}

			$displayValue = '';

			switch ($type)
			{
				case self::TYPE_TEXT:
				case self::TYPE_WYSIWYG:
				case self::TYPE_DATE_PICKER:
				case self::TYPE_SELECT_BOX_SINGLE:

					$displayValue = $dataValue->data_txt;
					break;

				case self::TYPE_TEXT_AREA:

					$displayValue = htmlspecialchars($dataValue->data_txt);
					break;

				case self::TYPE_CHECK_BOX:
				case self::TYPE_RADIO_BUTTON:
				case self::TYPE_SELECT_BOX_MULTIPLE:

					$fieldValues = RedshopEntityField::getInstance($rowsData[$i]->id)->getFieldValues();
					$checkData   = explode(",", $dataValue->data_txt);
					$htmlData    = array();

					foreach ($fieldValues as $fieldValue)
					{
						if (in_array(urlencode($fieldValue->field_value), $checkData))
						{
							$htmlData[] = urldecode($fieldValue->field_value);
						}
					}

					$displayValue = urldecode(implode('<br>', $htmlData));

					break;

				case self::TYPE_SELECT_COUNTRY_BOX:

					$displayValue = "";

					if ($dataValue->data_txt != "")
					{
						$displayValue = RedshopEntityCountry::getInstance((int) $dataValue->data_txt)->get('country_name');
					}

					break;

				case self::TYPE_DOCUMENTS :

					// Support Legacy string.
					if (preg_match('/\n/', $dataValue->data_txt))
					{
						$documentExplode = explode("\n", $dataValue->data_txt);
						$documentValue   = array($documentExplode[0] => $documentExplode[1]);
					}
					else
					{
						// Support for multiple file upload using JSON for better string handling
						$documentValue = json_decode($dataValue->data_txt);
					}

					if (count($documentValue) > 0)
					{
						$displayValue = "";

						foreach ($documentValue as $documentTitle => $fileName)
						{
							$documentLink    = REDSHOP_FRONT_DOCUMENT_ABSPATH . 'extrafields/' . $fileName;
							$absDocumentLink = REDSHOP_FRONT_DOCUMENT_RELPATH . 'extrafields/' . $fileName;

							if (JFile::exists($absDocumentLink))
							{
								$displayValue .= '<a href="' . $documentLink . '" target="_blank">' . $documentTitle . '</a>';
							}
						}
					}

					break;

				case self::TYPE_IMAGE_SELECT :
				case self::TYPE_IMAGE_WITH_LINK :

					$documentValues = RedshopEntityField::getInstance($rowsData[$i]->id)->getFieldValues();
					$tmpImagesHover = array();
					$tmpImagesLink  = array();

					if ($dataValue->alt_text)
					{
						$tmpImagesHover = explode(',,,,,', $dataValue->alt_text);
					}

					if ($dataValue->image_link)
					{
						$tmpImagesLink = @explode(',,,,,', $dataValue->image_link);
					}

					$dataList    = explode(",", $dataValue->data_txt);
					$imagesLink  = array();
					$imagesHover = array();

					foreach ($dataList as $index => $dataItem)
					{
						$imagesLink[$dataItem]  = isset($tmpImagesLink[$index]) ? $tmpImagesLink[$index] : '';
						$imagesHover[$dataItem] = isset($tmpImagesHover[$index]) ? $tmpImagesHover[$index] : '';
					}

					$displayValue = '';

					foreach ($documentValues as $documentValue)
					{
						if (!in_array($documentValue->value_id, $dataList))
						{
							continue;
						}

						$fileName     = $documentValue->field_name;
						$documentLink = REDSHOP_FRONT_IMAGES_ABSPATH . "extrafield/" . $fileName;

						if (!empty($imagesLink[$documentValue->value_id]))
						{
							$displayValue .= "<a href='" . $imagesLink[$documentValue->value_id]
								. "' class='imgtooltip' ><img src='" . $documentLink . "' title='" . $documentValue->field_value . "'"
								. " alt='" . $documentValue->field_value . "' /><span><div class='spnheader'>"
								. $rowsData[$i]->title . "</div><div class='spnalttext'>"
								. $imagesHover[$documentValue->value_id] . "</div></span></a>";
						}
						else
						{
							$displayValue .= "<a class='imgtooltip'><img src='" . $documentLink . "' title='" . $documentValue->field_value . "'"
								. " alt='" . $documentValue->field_value . "' /><span><div class='spnheader'>"
								. $rowsData[$i]->title . "</div><div class='spnalttext'>"
								. $imagesHover[$documentValue->value_id] . "</div></span></a>";
						}
					}

					break;

				default :
					break;
			}

			$displayTitle    = $dataValue->data_txt != "" ? $dataValue->title : "";
			$displayValue    = RedshopHelperTemplate::parseRedshopPlugin($displayValue);
			$templateContent = str_replace($searchLabel, JText::_($displayTitle), $templateContent);
			$templateContent = str_replace($search, $displayValue, $templateContent);
		}

		return $templateContent;
	}
}
