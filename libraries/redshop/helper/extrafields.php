<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

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
	 * Get list of fields.
	 *
	 * @param   integer  $published   Published Status which needs to be get. Default -1 will ignore any status.
	 * @param   integer  $limitStart  Set limit start
	 * @param   integer  $limit       Set limit
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

		self::$fieldsName = $db->setQuery($query, $limitStart, $limit)->loadObjectList('field_name');

		return self::$fieldsName;
	}

	/**
	 * Get field information from field name.
	 *
	 * @param   string  $name  Field name prefixed with `rs_`
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
	 * @param   int  $name         Name of the field - Typically contains `rs_` prefix.
	 * @param   int  $section      Section id of the field.
	 * @param   int  $sectionItem  Section item id
	 *
	 * @return mixed|null
	 */
	public static function getDataByName($name, $section, $sectionItem)
	{
		// Get Field id
		$fieldId = self::getField($name)->field_id;

		return self::getData($fieldId, $section, $sectionItem);
	}

	/**
	 * Get Section Field Data List
	 *
	 * @param   int  $fieldId      Field id
	 * @param   int  $section      Section id of the field.
	 * @param   int  $sectionItem  Section item id
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
				->select($db->qn('f.field_title'))
				->from($db->qn('#__redshop_fields_data', 'fd'))
				->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('fd.fieldid') . ' = ' . $db->qn('f.field_id'))
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
	 * @return  object
	 *
	 * @since   2.0.3
	 */
	public static function listAllFieldInProduct($section = extraField::SECTION_PRODUCT)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->qn('#__redshop_fields'))
			->where($db->qn('field_section') . ' = ' . (int) $section)
			->where($db->qn('display_in_product') . ' = 1')
			->where($db->qn('published') . ' = 1')
			->order($db->qn('ordering'));

		$db->setQuery($query);
		$rowData = $db->loadObjectlist();

		return $rowData;
	}

	/**
	 * List all fields
	 *
	 * @param   string   $fieldSection  Field section
	 * @param   integer  $sectionId     Section ID
	 * @param   string   $fieldName     Field name
	 * @param   string   $table         Table
	 * @param   string   $templateDesc  Template
	 *
	 * @return  string   HTML <td></td>
	 *
	 * @since   2.0.3
	 */
	public static function listAllField($fieldSection = "", $sectionId = 0, $fieldName = "", $table = "", $templateDesc = "")
	{
		$db = JFactory::getDbo();
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');
		$model = JModelLegacy::getInstance('Fields', 'RedshopModel');

		$rowData = $model->getFieldsBySection($fieldSection, $fieldName);
		$exField = '';

		if (count($rowData) > 0 && $table == "")
		{
			$exField = '<table class="admintable" border="0" >';
		}

		for ($i = 0, $in = count($rowData); $i < $in; $i++)
		{
			$type      = $rowData[$i]->field_type;
			$dataValue = self::getSectionFieldDataList($rowData[$i]->field_id, $fieldSection, $sectionId);
			$exField .= '<tr>';
			$extraFieldValue = "";
			$extraFieldLabel = JText::_($rowData[$i]->field_title);

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
					$textValue       = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$size            = ($rowData[$i]->field_size > 0) ? $rowData[$i]->field_size : 20;
					$extraFieldValue = '<input
											class="' . $rowData[$i]->field_class . '"
											type="text"
											maxlength="' . $rowData[$i]->field_maxlength . '" '
						. $required
						. $reqlbl
						. $errormsg
						. ' name="' . $rowData[$i]->field_name . '"
											id="' . $rowData[$i]->field_name . '"
											value="' . htmlspecialchars($textValue) . '"
											size="' . $size . '"
										/>';
					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_TEXT_AREA:
					$textareaValue   = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$extraFieldValue = '<textarea class="' . $rowData[$i]->field_class . '"  name="' . $rowData[$i]->field_name . '" ' . $required . $reqlbl . $errormsg . ' id="' . $rowData[$i]->field_name . '" cols="' . $rowData[$i]->field_cols . '" rows="' . $rowData[$i]->field_rows . '" >' . htmlspecialchars($textareaValue) . '</textarea>';
					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_CHECK_BOX:
					$fieldChk = self::getFieldValue($rowData[$i]->field_id);
					$chkData  = @explode(",", $dataValue->data_txt);

					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$extraFieldValue = '';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						$checked = (@in_array(urlencode($fieldChk[$c]->field_value), $chkData)) ? ' checked="checked" ' : '';
						$extraFieldValue .= '<input  class="' . $rowData[$i]->field_class . '" type="checkbox" ' . $required . $reqlbl . $errormsg . ' ' . $checked . ' name="' . $rowData[$i]->field_name . '[]"  id="' . $rowData[$i]->field_name . "_" . $fieldChk[$c]->value_id . '" value="' . urlencode($fieldChk[$c]->field_value) . '" />' . $fieldChk[$c]->field_value . '<br />';
					}

					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_RADIO_BUTTON:
					$fieldChk = self::getFieldValue($rowData[$i]->field_id);
					$chkData  = @explode(",", $dataValue->data_txt);

					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$extraFieldValue = '';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						$checked = (@in_array(urlencode($fieldChk[$c]->field_value), $chkData)) ? ' checked="checked" ' : '';
						$extraFieldValue .= '<input class="' . $rowData[$i]->field_class . '" type="radio" ' . $checked . ' ' . $required . $reqlbl . $errormsg . ' name="' . $rowData[$i]->field_name . '"  id="' . $rowData[$i]->field_name . "_" . $fieldChk[$c]->value_id . '" value="' . urlencode($fieldChk[$c]->field_value) . '" />' . $fieldChk[$c]->field_value . '<br />';
					}

					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_SELECT_BOX_SINGLE:
					$fieldChk = self::getFieldValue($rowData[$i]->field_id);
					$chkData  = @explode(",", $dataValue->data_txt);

					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$extraFieldValue = '<select name="' . $rowData[$i]->field_name . '">';
					$extraFieldValue .= '<option value="">' . JText::_('COM_REDSHOP_SELECT') . '</option>';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						$selected = (isset($dataValue->data_txt) && ($fieldChk[$c]->field_value == $dataValue->data_txt)) ? ' selected="selected" ' : '';
						$extraFieldValue .= '<option value="' . $fieldChk[$c]->field_value . '" ' . $selected . ' ' . $required . $reqlbl . $errormsg . '>' . $fieldChk[$c]->field_name . '</option>';
					}

					$extraFieldValue .= '</select>';
					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_SELECT_BOX_MULTIPLE:
					$fieldChk = self::getFieldValue($rowData[$i]->field_id);
					$chkData  = @explode(",", $dataValue->data_txt);

					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$extraFieldValue = '<select multiple size=10 name="' . $rowData[$i]->field_name . '[]">';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						$selected = (@in_array(urlencode($fieldChk[$c]->field_value), $chkData)) ? ' selected="selected" ' : '';
						$extraFieldValue .= '<option value="' . urlencode($fieldChk[$c]->field_value) . '" ' . $selected . ' ' . $required . $reqlbl . $errormsg . '>' . $fieldChk[$c]->field_name . '</option>';
					}

					$extraFieldValue .= '</select>';
					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_SELECT_COUNTRY_BOX:
					$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__redshop_country'));
					$db->setQuery($query);
					$fieldChk = $db->loadObjectlist();
					$chkData  = @explode(",", $dataValue->data_txt);

					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$extraFieldValue = '<select name="' . $rowData[$i]->field_name . '">';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						$selected = (@in_array($fieldChk[$c]->id, $chkData)) ? ' selected="selected" ' : '';
						$extraFieldValue .= '<option value="' . $fieldChk[$c]->id . '" ' . $selected . ' '
							. $required . $reqlbl . $errormsg . '>' . $fieldChk[$c]->country_name . '</option>';
					}

					$extraFieldValue .= '</select>';
					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_WYSIWYG:
					$editor = JFactory::getEditor();
					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$textareaValue   = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$extraFieldValue = $editor->display($rowData[$i]->field_name, $textareaValue, '200', '50', '100', '20', false);
					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_DOCUMENTS:
					$document = JFactory::getDocument();
					JHtml::_('redshopjquery.ui');
					$document->addScriptDeclaration('
						jQuery(function($){
							var remove_a = null;
							$(\'a#add_' . $rowData[$i]->field_name . '\').on(\'click\', function(e){
								e.preventDefault();
								var extra_field_name = $(this).attr(\'title\'), extra_field_doc_html = "";
								var html_acceptor = $(\'#html_\'+extra_field_name);
								var total_elm = html_acceptor.children(\'div\').length;

								extra_field_doc_html = \'<div id="div_\'+extra_field_name+total_elm+\'" class="ui-helper-clearfix">\';
									extra_field_doc_html += \'<input type="text" value="" id="text_\'+extra_field_name+total_elm+\'" errormsg="" reqlbl="" name="text_\'+extra_field_name+\'[]">\';
									extra_field_doc_html += \'<input type="file" id="file_\'+extra_field_name+total_elm+\'" name="\'+extra_field_name+\'[]" class="">\';
									extra_field_doc_html += \'<a href="#" class="rsDocumentDelete" style="float:left;" title="\'+extra_field_name+\'" id="remove_\'+extra_field_name+total_elm+\'">' . JText::_('COM_REDSHOP_DELETE') . '</a>\';
								extra_field_doc_html += \'</div>\';

								html_acceptor.append(extra_field_doc_html);
								$(\'#div_\'+extra_field_name+total_elm).effect( \'highlight\');
							});
							$(\'#html_' . $rowData[$i]->field_name . '\').on(\'click\', \'a.rsDocumentDelete\', function(e){
								e.preventDefault();
								$(this).parent(\'div\').effect(\'highlight\',{},500,function(){
									$(this).remove();
								});
							});
						});
					');

					if (is_object($dataValue) && property_exists($dataValue, 'data_txt'))
					{
						// Support Legacy string.
						if (preg_match('/\n/', $dataValue->data_txt))
						{
							$document_explode = explode("\n", $dataValue->data_txt);
							$dataTxt          = array($document_explode[0] => $document_explode[1]);
						}
						else
						{
							// Support for multiple file upload using JSON for better string handling
							$dataTxt = json_decode($dataValue->data_txt);
						}
					}

					if (isset($dataTxt) && count($dataTxt) > 0)
					{
						$extraFieldValue = "";
						$index           = 0;

						foreach ($dataTxt as $text_area_value_text => $text_area_value)
						{
							$extraFieldValue .= '<div id="div_' . $rowData[$i]->field_name . $index . '">
											<input type="text" name="text_' . $rowData[$i]->field_name . '[]" ' . $required . $reqlbl . $errormsg . ' id="text_' . $rowData[$i]->field_name . $index . '" value="' . $text_area_value_text . '" />&nbsp;';
							$extraFieldValue .= '<input class="' . $rowData[$i]->field_class . '"  name="' . $rowData[$i]->field_name . '[]"  id="file_' . $rowData[$i]->field_name . $index . '" type="file"  />';

							$destination_prefix     = REDSHOP_FRONT_DOCUMENT_ABSPATH . 'extrafields/';
							$destination_prefix_phy = REDSHOP_FRONT_DOCUMENT_RELPATH . 'extrafields/';
							$destination_prefix_del = '/components/com_redshop/assets/document/extrafields/';
							$media_image            = $destination_prefix_phy . $text_area_value;

							if (is_file($media_image))
							{
								$media_image = $destination_prefix . $text_area_value;
								$media_type  = strtolower(JFile::getExt($text_area_value));

								if ($media_type == 'jpg' || $media_type == 'jpeg' || $media_type == 'png' || $media_type == 'gif')
								{
									$extraFieldValue .= '<div id="docdiv' . $index . '"><img width="100"  src="' . $media_image . '" border="0" />&nbsp;<a href="#123"   onclick="delimg(\'' . $text_area_value . '\', \'div_' . $rowData[$i]->field_name . $index . '\',\'' . $destination_prefix_del . '\', \'' . $dataValue->data_id . ':document\');"> Remove Media</a>&nbsp;<input class="' . $rowData[$i]->field_class . '"  name="' . $rowData[$i]->field_name . '[]"  id="' . $rowData[$i]->field_name . '" value="' . $text_area_value . '" type="hidden"  /></div>';
								}
								else
								{
									$extraFieldValue .= '<div id="docdiv' . $index . '"><a href="' . $media_image . '" target="_blank">' . $text_area_value . '</a>&nbsp;<a href="#123"   onclick="delimg(\'' . $text_area_value . '\', \'div_' . $rowData[$i]->field_name . $index . '\',\'' . $destination_prefix_del . '\', \'' . $dataValue->data_id . ':document\');"> Remove Media</a>&nbsp;<input class="' . $rowData[$i]->field_class . '"  name="' . $rowData[$i]->field_name . '[]"  id="' . $rowData[$i]->field_name . '" value="' . $text_area_value . '" type="hidden"  /></div>';
								}
							}
							else
							{
								$extraFieldValue .= JText::_('COM_REDSHOP_FILE_NOT_EXIST');
							}

							$extraFieldValue .= '</div>';

							$index++;
						}
					}

					$exField .= '<td width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$exField .= '<td><a href="#" title="' . $rowData[$i]->field_name . '" id="add_' . $rowData[$i]->field_name . '">' . JText::_('COM_REDSHOP_ADD') . '</a><div id="html_' . $rowData[$i]->field_name . '">' . $extraFieldValue . '</div>';
					break;

				case extraField::TYPE_IMAGE_SELECT:
					$fieldChk  = self::getFieldValue($rowData[$i]->field_id);
					$dataValue = self::getSectionFieldDataList($rowData[$i]->field_id, $fieldSection, $sectionId);
					$value     = '';

					if ($dataValue)
					{
						$value = $dataValue->data_txt;
					}

					$chkData = @explode(",", $dataValue->data_txt);
					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$extraFieldValue = '<table><tr>';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						if (in_array($fieldChk[$c]->value_id, $chkData))
						{
							$class = ' class="pointer imgClass_' . $sectionId . ' selectedimg" ';
						}
						else
						{
							$class = ' class="pointer imgClass_' . $sectionId . '"';
						}

						$extraFieldValue .= '<td><div class="userfield_input"><img id="' . $fieldChk[$c]->value_id . '" name="imgField[]" ' . $class . ' src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $fieldChk[$c]->field_name . '" title="' . $fieldChk[$c]->field_value . '" alt="' . $fieldChk[$c]->field_value . '" onclick="javascript:setProductUserFieldImage(\'' . $fieldChk[$c]->value_id . '\',\'' . $sectionId . '\',\'' . $fieldChk[$c]->field_id . '\',this);" /></div></td>';
					}

					$extraFieldValue .= '<input type="hidden" name="imgFieldId' . $rowData[$i]->field_id . '" id="imgFieldId' . $rowData[$i]->field_id . '" value="' . $value . '"/>';
					$extraFieldValue .= '</tr></table>';
					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_DATE_PICKER:

					if ($rowData[$i]->fieldSection != 17)
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

					$size = ($rowData[$i]->field_size > 0) ? $rowData[$i]->field_size : 20;
					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$extraFieldValue = JHTML::_('calendar', $date, $rowData[$i]->field_name, $rowData[$i]->field_name, '%d-%m-%Y', array('class' => 'inputbox', 'size' => $size, 'maxlength' => '15'));
					$exField .= '<td>' . $extraFieldValue;
					break;

				case extraField::TYPE_IMAGE_WITH_LINK:

					$fieldChk      = self::getFieldValue($rowData[$i]->field_id);
					$dataValue     = self::getSectionFieldDataList($rowData[$i]->field_id, $fieldSection, $sectionId);
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

					$chkData    = @explode(",", $dataValue->data_txt);
					$imageLink  = array();
					$imageHover = array();

					for ($ch = 0; $ch < count($chkData); $ch++)
					{
						$imageLink[$chkData[$ch]]  = $tmpImageLink[$ch];
						$imageHover[$chkData[$ch]] = $tmpImageHover[$ch];
					}

					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$extraFieldValue = '<table>';

					for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
					{
						$altText      = '';
						$strImageLink = '';
						$extraFieldValue .= '<tr>';

						if (in_array($fieldChk[$c]->value_id, $chkData))
						{
							$class        = ' class="pointer imgClass_' . $sectionId . ' selectedimg" ';
							$style1       = "display:block;";
							$strImageLink = $imageLink[$fieldChk[$c]->value_id];
							$altText      = $imageHover[$fieldChk[$c]->value_id];
						}
						else
						{
							$style1 = "display:none;";
							$class  = ' class="pointer imgClass_' . $sectionId . '"';
						}

						$extraFieldValue .= '<td><div class="userfield_input"><img id="' . $fieldChk[$c]->value_id . '" name="imgField[]" ' . $class . ' src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $fieldChk[$c]->field_name . '" title="' . $fieldChk[$c]->field_value . '" alt="' . $fieldChk[$c]->field_value . '" onclick="javascript:setProductImageLink(\'' . $fieldChk[$c]->value_id . '\',\'' . $sectionId . '\',\'' . $fieldChk[$c]->field_id . '\',this);" /></div></td>';
						$extraFieldValue .= '<td><div id="hover_link' . $fieldChk[$c]->value_id . '" style="' . $style1 . '">';
						$extraFieldValue .= '<table><tr><td valign="top" width="100" align="right" class="key">' . JText::_('COM_REDSHOP_IMAGE_HOVER') . '</td><td><input type="text" name="image_hover' . $fieldChk[$c]->value_id . '"  value="' . $altText . '"/></td></tr>';
						$extraFieldValue .= '<tr><td valign="top" width="100" align="right" class="key">' . JText::_('COM_REDSHOP_IMAGE_LINK') . '</td><td><input type="text" name="image_link' . $fieldChk[$c]->value_id . '" value="' . $strImageLink . '"/></td></tr>';
						$extraFieldValue .= '</table></div></td>';
						$extraFieldValue .= '</tr>';
					}

					$extraFieldValue .= '<input type="hidden" name="imgFieldId' . $rowData[$i]->field_id . '" id="imgFieldId' . $rowData[$i]->field_id . '" value="' . $value . '"/>';
					$extraFieldValue .= '</table>';
					$exField .= '<td>' . $extraFieldValue;
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

					if ($rowData[$i]->field_size > 0)
					{
						$size = $rowData[$i]->field_size;
					}
					else
					{
						$size = '20';
					}

					$exField .= '<td valign="top" width="100" align="right" class="key">' . $extraFieldLabel . '</td>';
					$extraFieldValue = 'Publish Date: ';

					$extraFieldValue .= "<input type='text' name='" . $rowData[$i]->field_name . "' value='" . $datePublish . "'>";
					$extraFieldValue .= '&nbsp;&nbsp;&nbsp;&nbsp; Expiry Date: ';
					$extraFieldValue .= "<input type='text' name='" . $rowData[$i]->field_name . "_expiry' value='" . $dateExpiry . "'>";
					$extraFieldValue .= '</td></tr><tr><td>&nbsp;</td><td>';
					$extraFieldValue .= "<div class='col50' id='field_data'>";
					$extraFieldValue .= "Enter Available Dates: <input type='button' name='addvalue' id='addvalue' class='button'  Value='" . JText::_('COM_REDSHOP_ADD_VALUE') . "' onclick='addNewRowcustom(" . $rowData[$i]->field_name . ");'/>";
					$extraFieldValue .= "<fieldset class='adminform'>";
					$extraFieldValue .= "<legend>'" . JText::_('COM_REDSHOP_VALUE') . "'</legend>";
					$extraFieldValue .= "<table cellpadding='0' cellspacing='5' border='0' id='extra_table' width='95%'>";
					$extraFieldValue .= "<tr><th width='20%'>'" . JText::_('COM_REDSHOP_OPTION_VALUE') . "'</th>
						<th>&nbsp;</th></tr>";

					if (count($mainSplitDateExtra) > 0)
					{
						for ($k = 0, $kn = count($mainSplitDateExtra); $k < $kn; $k++)
						{
							if ($mainSplitDateExtra[$k] != "")
							{
								$extraFieldValue .= "<tr><td><div id='divfieldText'><input type='text' name='" . $rowData[$i]->field_name . "_extra_name[]'  value='" . date("d-m-Y", $mainSplitDateExtra[$k]) . "' name='" . $rowData[$i]->field_name . "_extra_name[]'></div></td><td><input value='Delete' onclick='deleteRow(this)' class='button' type='button' /></td>";
								$extraFieldValue .= "</tr>";
							}
						}
					}
					else
					{
						$k = 1;
						$extraFieldValue .= "<tr><td><div id='divfieldText'><input type='text' name='" . $rowData[$i]->field_name . "_extra_name[]' value='" . date('d-m-Y') . "' name='" . $rowData[$i]->field_name . "_extra_name[]'></div>
						</td>
						</tr>";
					}

					$extraFieldValue .= "</table></fieldset></div><input type='hidden' value='" . $k . "' name='total_extra' id='total_extra'>";
					$exField .= '<td>' . $extraFieldValue;
					break;
			}

			if (trim($templateDesc) != '')
			{
				if (strstr($templateDesc, "{" . $rowData[$i]->field_name . "}"))
				{
					$templateDesc = str_replace("{" . $rowData[$i]->field_name . "}", $extraFieldValue, $templateDesc);
					$templateDesc = str_replace("{" . $rowData[$i]->field_name . "_lbl}", $extraFieldLabel, $templateDesc);
				}

				$templateDesc = str_replace("{" . $rowData[$i]->field_name . "}", "", $templateDesc);
				$templateDesc = str_replace("{" . $rowData[$i]->field_name . "_lbl}", "", $templateDesc);
			}
			else
			{
				if (trim($rowData[$i]->field_desc) == '')
				{
					$exField .= '</td><td valign="top">';
				}
				else
				{
					$exField .= '</td><td valign="top">&nbsp; ' . JHTML::tooltip($rowData[$i]->field_desc, $rowData[$i]->field_name, 'tooltip.png', '', '', false);
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
	 * @param   array    $data          Data to insert
	 * @param   integer  $fieldSection  Field section to match
	 * @param   string   $sectionId     Section ID
	 * @param   string   $userEmail     User to match by email
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
			->where($db->qn('field_section') . ' = ' . (int) $fieldSection)
			->where($db->qn('published') . ' = 1');

		$db->setQuery($query);
		$rowData = $db->loadObjectlist();

		for ($i = 0, $in = count($rowData); $i < $in; $i++)
		{
			$dataTxt = '';

			if (isset($data[$rowData[$i]->field_name]))
			{
				if ($rowData[$i]->field_type == 8 || $rowData[$i]->field_type == 1 || $rowData[$i]->field_type == 2)
				{
					$dataTxt = JFactory::getApplication()->input->get($rowData[$i]->field_name, '', 'RAW');
				}
				else
				{
					$dataTxt = $data[$rowData[$i]->field_name];
				}
			}

			// Save Document Extra Field
			if ($rowData[$i]->field_type == extraField::TYPE_DOCUMENTS)
			{
				$files = $_FILES[$rowData[$i]->field_name]['name'];
				$texts = $data['text_' . $rowData[$i]->field_name];

				$documentsValue = array();

				if (isset($data[$rowData[$i]->field_name]))
				{
					$documentsValue = $data[$rowData[$i]->field_name];
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

							$src         = $_FILES[$rowData[$i]->field_name]['tmp_name'][$ij];
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

			if ($rowData[$i]->field_type == extraField::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS)
			{
				if ($data[$rowData[$i]->field_name] != "" && $data[$rowData[$i]->field_name . "_expiry"] != "")
				{
					$dataTxt = strtotime($data[$rowData[$i]->field_name]) . ":" . strtotime($data[$rowData[$i]->field_name . "_expiry"]) . " ";

					if (count($data[$rowData[$i]->field_name . "_extra_name"]) > 0)
					{
						for ($r = 0; $r < count($data[$rowData[$i]->field_name . "_extra_name"]); $r++)
						{
							$dataTxt .= strtotime($data[$rowData[$i]->field_name . "_extra_name"][$r]) . ":";
						}
					}
				}
			}

			if (is_array($dataTxt))
			{
				$dataTxt = implode(",", $dataTxt);
			}

			$sect = explode(",", $fieldSection);

			if ($rowData[$i]->field_type == extraField::TYPE_IMAGE_SELECT || $rowData[$i]->field_type == extraField::TYPE_IMAGE_WITH_LINK)
			{
				$list = self::getSectionFieldDataList($rowData[$i]->field_id, $fieldSection, $sectionId, $userEmail);

				if ($rowData[$i]->field_type == extraField::TYPE_IMAGE_WITH_LINK)
				{
					$fieldValueArray = explode(',', $data['imgFieldId' . $rowData[$i]->field_id]);
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
						->where($db->qn('fieldid') . ' = ' . (int) $rowData[$i]->field_id);

					$db->setQuery($sql);
					$db->execute();
				}

				// Reset $sql query
				$sql = $db->getQuery(true);

				if (count($list) > 0)
				{
					$sql->update($db->qn('#__redshop_fields_data'))
						->set($db->qn('data_txt') . ' = ' . $db->quote($data['imgFieldId' . $rowData[$i]->field_id]))
						->where($db->qn('itemid') . ' = ' . (int) $sectionId)
						->where($db->qn('section') . ' = ' . $db->quote($fieldSection))
						->where($db->qn('user_email') . ' = ' . $db->quote($userEmail))
						->where($db->qn('fieldid') . ' = ' . (int) $rowData[$i]->field_id);
				}
				else
				{
					$sql->insert($db->qn('#__redshop_fields_data'))
						->columns($db->qn(array('fieldid', 'data_txt', 'itemid', 'section', 'alt_text', 'image_link', 'user_email')))
						->values(implode(',', array((int) $rowData[$i]->field_id, $db->quote($data['imgFieldId' . $rowData[$i]->field_id]), (int) $sectionId, $db->quote($fieldSection), $db->quote($strImageHover), $db->quote($strImageLink), $db->quote($userEmail))));
				}

				$db->setQuery($sql);
				$db->execute();
			}
			else
			{
				for ($h = 0, $hn = count($sect); $h < $hn; $h++)
				{
					$list = self::getSectionFieldDataList($rowData[$i]->field_id, $sect[$h], $sectionId, $userEmail);

					if (count($list) > 0)
					{
						$sql = $db->getQuery(true);
						$sql->update($db->qn('#__redshop_fields_data'))
							->set($db->qn('data_txt') . ' = ' . $db->quote($dataTxt))
							->where($db->qn('itemid') . ' = ' . (int) $sectionId)
							->where($db->qn('section') . ' = ' . (int) $sect[$h])
							->where($db->qn('user_email') . ' = ' . $db->quote($userEmail))
							->where($db->qn('fieldid') . ' = ' . (int) $rowData[$i]->field_id);

						$db->setQuery($sql);
						$db->execute();
					}
					elseif (!empty($dataTxt))
					{
						$sql = $db->getQuery(true);
						$sql->insert($db->qn('#__redshop_fields_data'))
							->columns($db->qn(array('fieldid', 'data_txt', 'itemid', 'section', 'user_email')))
							->values(implode(',', array((int) $rowData[$i]->field_id, $db->quote($dataTxt), (int) $sectionId, (int) $sect[$h], $db->quote($userEmail))));

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
	 * @param   string   $fieldSection  Field Section List
	 * @param   integer  $sectionId     Section ID
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
			$dataValue = self::getSectionFieldDataList($rowData[$i]->field_id, $fieldSection, $sectionId);

			if (empty($dataValue) && $required)
			{
				return $rowData[$i]->field_title;
			}
		}

		return false;
	}

	/**
	 * List all fields and display
	 *
	 * @param   string   $fieldSection  Field section
	 * @param   integer  $sectionId     Section ID
	 * @param   integer  $flag          Flag
	 * @param   string   $userEmail     User email
	 * @param   string   $templateDesc  Template description
	 * @param   boolean  $sendmail      True/ False
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
			$type            = $rowData[$i]->field_type;
			$extraFieldValue = "";
			$extraFieldLabel = $rowData[$i]->field_title;

			if ($flag == 1)
			{
				if ($i > 0)
				{
					$exField .= "<br />";
				}

				$exField .= JText::_($extraFieldLabel) . ' : ';
			}

			$dataValue = self::getSectionFieldDataList($rowData[$i]->field_id, $fieldSection, $sectionId, $userEmail);

			switch ($type)
			{
				case extraField::TYPE_TEXT:
					$extraFieldValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$exField .= $extraFieldValue;
					break;

				case extraField::TYPE_TEXT_AREA:
					$extraFieldValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$exField .= $extraFieldValue;
					break;

				case extraField::TYPE_CHECK_BOX:
					$fieldChk = self::getFieldValue($rowData[$i]->field_id);
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
					$fieldChk = self::getFieldValue($rowData[$i]->field_id);
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
					$fieldChk = self::getFieldValue($rowData[$i]->field_id);
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
					$fieldChk = self::getFieldValue($rowData[$i]->field_id);
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
						$query = $db->getQuery(true);

						$query->select($db->qn('country_name'))
							->from($db->qn('#__redshop_country'))
							->where($db->qn('id') . ' = ' . $db->quote($dataValue->data_txt));

						$db->setQuery($query);

						$fieldChk        = $db->loadObject();
						$extraFieldValue = $fieldChk->country_name;
					}

					$exField .= $extraFieldValue;
					break;

				// 12 :- Date Picker
				case extraField::TYPE_DATE_PICKER:
					$extraFieldValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
					$exField .= $extraFieldValue;
					break;
			}

			if (trim($templateDesc) != '')
			{
				if (strstr($templateDesc, "{" . $rowData[$i]->field_name . "}"))
				{
					$templateDesc = str_replace("{" . $rowData[$i]->field_name . "}", $extraFieldValue, $templateDesc);
					$templateDesc = str_replace("{" . $rowData[$i]->field_name . "_lbl}", $extraFieldLabel, $templateDesc);
				}

				$templateDesc = str_replace("{" . $rowData[$i]->field_name . "}", "", $templateDesc);
				$templateDesc = str_replace("{" . $rowData[$i]->field_name . "_lbl}", "", $templateDesc);
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
	 * @param   string   $fieldSection  Field Section
	 * @param   integer  $sectionId     Section ID
	 * @param   string   $fieldType     Field type
	 * @param   string   $uniqueId      Unique ID
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
			->where($db->qn('field_section') . ' = ' . (int) $sectionId)
			->where($db->qn('field_name') . ' = ' . $db->quote($fieldSection))
			->where($db->qn('published') . ' = 1');

		$db->setQuery($query);

		$rowData      = $db->loadObjectlist();
		$exField      = '';
		$exFieldTitle = '';

		for ($i = 0, $in = count($rowData); $i < $in; $i++)
		{
			$type     = $rowData[$i]->field_type;
			$asterisk = $rowData[$i]->required > 0 ? '* ' : '';

			if ($fieldType != 'hidden')
			{
				$exFieldTitle .= '<div class="userfield_label">' . $asterisk . $rowData[$i]->field_title . '</div>';
			}

			$textValue = '';

			if ($fieldType == 'hidden')
			{
				$exField .= '<input type="hidden" name="extrafieldId' . $uniqueId . '[]"  value="' . $rowData[$i]->field_id . '" />';
			}
			else
			{
				$req = ' required = "' . $rowData[$i]->required . '"';

				switch ($type)
				{
					case extraField::TYPE_TEXT:
						$onkeyup = '';
						$exField .= '<div class="userfield_input"><input class="' . $rowData[$i]->field_class . '" type="text" maxlength="' . $rowData[$i]->field_maxlength . '" onkeyup="var f_value = this.value;' . $onkeyup . '" name="extrafieldname' . $uniqueId . '[]"  id="' . $rowData[$i]->field_name . '" ' . $req . ' userfieldlbl="' . $rowData[$i]->field_title . '" value="' . $textValue . '" size="' . $rowData[$i]->field_size . '" /></div>';
						break;

					case extraField::TYPE_TEXT_AREA:
						$onkeyup = '';
						$exField .= '<div class="userfield_input"><textarea class="' . $rowData[$i]->field_class . '"  name="extrafieldname' . $uniqueId . '[]"  id="' . $rowData[$i]->field_name . '" ' . $req . ' userfieldlbl="' . $rowData[$i]->field_title . '" cols="' . $rowData[$i]->field_cols . '" onkeyup=" var f_value = this.value;' . $onkeyup . '" rows="' . $rowData[$i]->field_rows . '" >' . $textValue . '</textarea></div>';
						break;

					case extraField::TYPE_CHECK_BOX:
						$fieldChk = self::getFieldValue($rowData[$i]->field_id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->field_name]);

						for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
						{
							$checked = (@in_array($fieldChk[$c]->field_value, $chkData)) ? ' checked="checked" ' : '';
							$exField .= '<div class="userfield_input"><input  class="' . $rowData[$i]->field_class . '" type="checkbox"  ' . $checked . ' name="extrafieldname' . $uniqueId . '[]" id="' . $rowData[$i]->field_name . "_" . $fieldChk[$c]->value_id . '" userfieldlbl="' . $rowData[$i]->field_title . '" value="' . $fieldChk[$c]->field_value . '" ' . $req . ' />' . $fieldChk[$c]->field_value . '</div>';
						}
						break;

					case extraField::TYPE_RADIO_BUTTON:
						$fieldChk = self::getFieldValue($rowData[$i]->field_id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->field_name]);

						for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
						{
							$checked = (@in_array($fieldChk[$c]->field_value, $chkData)) ? ' checked="checked" ' : '';
							$exField .= '<div class="userfield_input"><input class="' . $rowData[$i]->field_class . '" type="radio" ' . $checked . ' name="extrafieldname' . $uniqueId . '[]" userfieldlbl="' . $rowData[$i]->field_title . '"  id="' . $rowData[$i]->field_name . "_" . $fieldChk[$c]->value_id . '" value="' . $fieldChk[$c]->field_value . '" ' . $req . ' />' . $fieldChk[$c]->field_value . '</div>';
						}
						break;

					case extraField::TYPE_SELECT_BOX_SINGLE:
						$fieldChk = self::getFieldValue($rowData[$i]->field_id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->field_name]);
						$exField .= '<div class="userfield_input"><select name="extrafieldname' . $uniqueId . '[]" ' . $req . ' id="' . $rowData[$i]->field_name . '" userfieldlbl="' . $rowData[$i]->field_title . '">';
						$exField .= '<option value="">' . JText::_('COM_REDSHOP_SELECT') . '</option>';

						for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
						{
							if ($fieldChk[$c]->field_value != "" && $fieldChk[$c]->field_value != "-" && $fieldChk[$c]->field_value != "0" && $fieldChk[$c]->field_value != "select")
							{
								$selected = (@in_array($fieldChk[$c]->field_value, $chkData)) ? ' selected="selected" ' : '';
								$exField .= '<option value="' . $fieldChk[$c]->field_value . '" ' . $selected . '   >' . $fieldChk[$c]->field_value . '</option>';
							}
						}

						$exField .= '</select></div>';
						break;

					case extraField::TYPE_SELECT_BOX_MULTIPLE:
						$fieldChk = self::getFieldValue($rowData[$i]->field_id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->field_name]);
						$exField .= '<div class="userfield_input"><select multiple="multiple" size=10 name="extrafieldname' . $uniqueId . '[]" ' . $req . ' id="' . $rowData[$i]->field_name . '" userfieldlbl="' . $rowData[$i]->field_title . '">';

						for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
						{
							$selected = (@in_array($fieldChk[$c]->field_value, $chkData)) ? ' selected="selected" ' : '';
							$exField .= '<option value="' . $fieldChk[$c]->field_value . '" ' . $selected . ' >' . $fieldChk[$c]->field_value . '</option>';
						}

						$exField .= '</select></div>';
						break;

					case extraField::TYPE_DOCUMENTS:
						JHtml::_('redshopjquery.framework');
						JHtml::script('com_redshop/ajaxupload.js', false, true);
						$ajax = "";
						$exField .= '<div class="userfield_input"><input class="' . $rowData[$i]->field_class . '" type="button" value="' . JText::_('COM_REDSHOP_UPLOAD') . '" name="file' . $rowData[$i]->field_name . '_' . $uniqueId . '"  id="file' . $rowData[$i]->field_name . '_' . $uniqueId . '" ' . $req . ' userfieldlbl="' . $rowData[$i]->field_title . '" size="' . $rowData[$i]->field_size . '" /><p>' . JText::_('COM_REDSHOP_UPLOADED_FILE') . ':<ol id="ol_' . $rowData[$i]->field_name . '"></ol></p></div>';
						$exField .= '<input type="hidden" name="extrafieldname' . $uniqueId . '[]" id="' . $rowData[$i]->field_name . '_' . $uniqueId . '" ' . $req . ' userfieldlbl="' . $rowData[$i]->field_title . '"  />';

						$exField .= '<script>jQuery.noConflict();new AjaxUpload("file' . $rowData[$i]->field_name . '_' . $uniqueId . '",{action:"index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",data :{mname:"file' . $rowData[$i]->field_name . '_' . $uniqueId . '"}, name:"file' . $rowData[$i]->field_name . '_' . $uniqueId . '",onSubmit : function(file , ext){jQuery("' . $rowData[$i]->field_name . '").text("' . JText::_('COM_REDSHOP_UPLOADING') . '" + file);this.disable();}, onComplete :function(file,response){jQuery("<li></li>").appendTo(jQuery("#ol_' . $rowData[$i]->field_name . '")).text(response);var uploadfiles = jQuery("#ol_' . $ajax . $rowData[$i]->field_name . ' li").map(function() {return jQuery(this).text();}).get().join(",");jQuery("#' . $rowData[$i]->field_name . '_' . $uniqueId . '").val(uploadfiles);jQuery("#' . $rowData[$i]->field_name . '").val(uploadfiles);this.enable();}});</script>';

						break;

					case extraField::TYPE_IMAGE_SELECT:
						$fieldChk = self::getFieldValue($rowData[$i]->field_id);
						$chkData  = @explode(",", $cart[$idx][$rowData[$i]->field_name]);
						$exField .= '<table><tr>';

						for ($c = 0, $cn = count($fieldChk); $c < $cn; $c++)
						{
							$exField .= '<td><div class="userfield_input"><img id="' . $rowData[$i]->field_name . "_" . $fieldChk[$c]->value_id . '" class="pointer imgClass_' . $uniqueId . '" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $fieldChk[$c]->field_name . '" title="' . $fieldChk[$c]->field_value . '" alt="' . $fieldChk[$c]->field_value . '" onclick="javascript:setProductUserFieldImage(\'' . $rowData[$i]->field_name . '\',\'' . $uniqueId . '\',\'' . $fieldChk[$c]->field_value . '\',this);"/></div></td>';
						}

						$exField .= '</tr></table>';
						$ajax = '';

						$exField .= '<input type="hidden" name="extrafieldname' . $uniqueId . '[]" id="' . $ajax . $rowData[$i]->field_name . '_' . $uniqueId . '" userfieldlbl="' . $rowData[$i]->field_title . '" ' . $req . '  />';
						break;

					case extraField::TYPE_DATE_PICKER:
						$ajax = '';
						$req  = $rowData[$i]->required;

						$exField .= '<div class="userfield_input">' . JHTML::_('calendar', $textValue, 'extrafieldname' . $uniqueId . '[]', $ajax . $rowData[$i]->field_name . '_' . $uniqueId, '%d-%m-%Y', array('class' => $rowData[$i]->field_class, 'size' => $rowData[$i]->field_size, 'maxlength' => $rowData[$i]->field_maxlength, 'required' => $req, 'userfieldlbl' => $rowData[$i]->field_title, 'errormsg' => '')) . '</div>';
						break;
				}
			}

			if (trim($rowData[$i]->field_desc) != '' && $fieldType != 'hidden')
			{
				$exField .= '<div class="userfield_tooltip">&nbsp; ' . JHTML::tooltip($rowData[$i]->field_desc, $rowData[$i]->field_name, 'tooltip.png', '', '', false) . '</div>';
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
			JHTML::_('select.option', "Days", JText::_($yes)),
			JHTML::_('select.option', "Weeks", JText::_($no))
		);

		return JHTML::_('select.radiolist', $arr, $name, $attribs, 'value', 'text', $selected, $id);
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
			JHTML::_('select.option', $yesValue, JText::_($yes)),
			JHTML::_('select.option', $noValue, JText::_($no))
		);

		return JHTML::_('redshopselect.radiolist', $arr, $name, $attribs, 'value', 'text', $selected, $id);
	}

	/**
	 * Get fields value by ID
	 *
	 * @param   integer  $id  ID of field
	 *
	 * @return  object
	 *
	 * @since 2.0.3
	 */
	public static function getFieldValue($id)
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models');
		$model = JModelLegacy::getInstance('Fields', 'RedshopModel');

		return $model->getFieldValue($id);
	}

	/**
	 * Get Section Field List
	 *
	 * @param   integer  $section    Section ID
	 * @param   integer  $front      Field show in front
	 * @param   integer  $published  Field show in front
	 * @param   integer  $required   Field show in front
	 *
	 * @return  object
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
				->where($db->qn('field_section') . ' = ' . (int) $section)
				->order($db->qn('ordering'));

			if ($front)
			{
				$query->where($db->qn('field_show_in_front') . ' = ' . (int) $front);
			}

			if ($published)
			{
				$query->where($db->qn('published') . ' = ' . (int) $published);
			}

			if ($required)
			{
				$query->where($db->qn('required') . ' = ' . (int) $required);
			}

			static::$sectionFields[$key] = $db->setQuery($query)->loadObjectlist();
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
	 * @since 2.0.3
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
	 * @since 2.0.3
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
	 * @param   integer  $dataId  Data ID
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
}
