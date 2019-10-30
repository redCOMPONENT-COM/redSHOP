<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Fields;

use Redshop\Helper\ExtraFields;

defined('_JEXEC') or die;

/**
 * Fields - Site helper
 *
 * @since  2.1.0
 */
class SiteHelper
{
	/**
	 * @var array
	 */
	protected static $userFields = array();

	/**
	 * Method for render fields
	 *
	 * @param   integer $fieldSection Field Section
	 * @param   integer $sectionId    Section ID
	 * @param   string  $uniqueClass  Unique class
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public static function renderFields($fieldSection = 0, $sectionId = 0, $uniqueClass = '')
	{
		$fields = \RedshopHelperExtrafields::getSectionFieldList($fieldSection, 1);

		if (empty($fields))
		{
			return '';
		}

		$html = '';

		foreach ($fields as $field)
		{
			$type      = $field->type;
			$dataValue = \RedshopHelperExtrafields::getData($field->id, $fieldSection, $sectionId);

			if (!empty($dataValue) && count($dataValue) <= 0)
			{
				$dataValue->data_txt = '';
			}

			$cssClassName = array();
			$class        = '';

			if (1 == $field->required)
			{
				if ($uniqueClass == '')
				{
					$cssClassName[] = 'required';
				}
				else
				{
					$cssClassName[] = $uniqueClass;
				}

				// Adding title to display JS validation Error message.
				$class = 'title="' . \JText::sprintf('COM_REDSHOP_VALIDATE_EXTRA_FIELD_IS_REQUIRED', $field->title) . '" ';
			}

			// Default css class name
			$cssClassName[] = $field->class;
			$class         .= ' class="' . implode(' ', $cssClassName) . '"';
			$fieldEntity    = \RedshopEntityField::getInstance($field->id)->bind($field);
			$inputField     = '';

			switch ($type)
			{
				case \RedshopHelperExtrafields::TYPE_TEXT_AREA:
					$textAreaValue = $dataValue && $dataValue->data_txt ? $dataValue->data_txt : '';
					$inputField    = '<textarea ' . $class . '  name="' . $field->name . '"  id="' . $field->name . '" cols="' . $field->cols . '"'
						. ' rows="' . $field->rows . '" >' . $textAreaValue . '</textarea>';
					break;

				case \RedshopHelperExtrafields::TYPE_CHECK_BOX:
					$fieldValues = $fieldEntity->getFieldValues();
					$chkData     = explode(",", $dataValue->data_txt);

					foreach ($fieldValues as $value)
					{
						$checked = '';

						if (in_array($value->field_value, $chkData))
						{
							$checked = ' checked="checked" ';
						}

						$inputField .= '<input class="' . $field->class . ' ' . $class . '"   type="checkbox"  ' . $checked
							. ' name="' . $field->name . '[]" id="' . $field->name . "_" . $value->value_id . '" '
							. ' value="' . $value->field_value . '" />' . $value->field_name . '<br />';
					}

					$inputField .= '<label for="' . $field->name . '[]" class="error">'
						. \JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR') . '&nbsp;' . $field->title . '</label>';
					break;

				case \RedshopHelperExtrafields::TYPE_RADIO_BUTTON:
					$selectedValue = ($dataValue) ? $dataValue->data_txt : '';
					$inputField    = \JHtml::_(
						'select.radiolist',
						$fieldEntity->getFieldValues(),
						$field->name,
						array(
							'class' => $field->class
						),
						'field_value',
						'field_name',
						$selectedValue
					);
					break;

				case \RedshopHelperExtrafields::TYPE_SELECT_BOX_SINGLE:
					$fieldValues = $fieldEntity->getFieldValues();
					$chkData     = explode(",", $dataValue->data_txt);
					$inputField  = '<select class="' . $field->class . ' ' . $class . '"    name="' . $field->name . '"   id="' . $field->name . '">';

					foreach ($fieldValues as $value)
					{
						$selected = '';

						if (in_array($value->field_value, $chkData))
						{
							$selected = ' selected="selected" ';
						}

						$inputField .= '<option value="' . $value->field_value . '" ' . $selected . ' >' . $value->field_value . '</option>';
					}

					$inputField .= '</select>';
					break;

				case \RedshopHelperExtrafields::TYPE_SELECT_BOX_MULTIPLE:
					$fieldValues = $fieldEntity->getFieldValues();
					$chkData     = explode(",", $dataValue->data_txt);

					$inputField = '<select class="' . $field->class . ' ' . $class . '"   multiple size=10 name="' . $field->name . '[]">';

					foreach ($fieldValues as $value)
					{
						$selected = '';

						if (in_array(urlencode($value->field_value), $chkData))
						{
							$selected = ' selected="selected" ';
						}

						$inputField .= '<option value="' . urlencode($value->field_value) . '" ' . $selected . ' >'
							. $value->field_name . '</option>';
					}

					$inputField .= '</select>';
					break;

				case \RedshopHelperExtrafields::TYPE_DATE_PICKER:
					$date = $dataValue && $dataValue->data_txt ? date("d-m-Y", strtotime($dataValue->data_txt)) : date("d-m-Y", time());
					$size = $field->size > 0 ? $field->size : 20;

					$inputField = \JHtml::_(
						'redshopcalendar.calendar',
						$date,
						$field->name,
						$field->name,
						$format = '%d-%m-%Y',
						array('class' => 'inputbox', 'size' => $size, 'maxlength' => '15')
					);
					break;

				case \RedshopHelperExtrafields::TYPE_TEXT:
				default:
					$textValue  = $dataValue && $dataValue->data_txt ? $dataValue->data_txt : '';
					$inputField = '<input ' . $class . ' type="text" maxlength="' . $field->maxlength . '" name="' . $field->name . '" '
						. 'id="' . $field->name . '" value="' . $textValue . '" size="32" />';
					break;
			}

			$html .= \RedshopLayoutHelper::render(
				'fields.html',
				array(
					'fieldHandle' => $field,
					'inputField'  => $inputField
				)
			);
		}

		return $html;
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
	 * @since   2.1.0
	 */
	public static function listAllUserFields($fieldSection = "", $sectionId = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD,
		$fieldType = '', $idx = 'NULL', $isAtt = 0, $productId = 0, $myWish = '', $addWish = 0
	)
	{
		$db   = \JFactory::getDbo();
		$cart = \RedshopHelperCartSession::getCart();

		$prePrefix = "";

		if ($isAtt == 1)
		{
			$prePrefix = "ajax_";
		}

		$addToCartFormName = 'addtocart_' . $prePrefix . 'prd_' . $productId;

		if (!array_key_exists($sectionId . '_' . $fieldSection, self::$userFields))
		{
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_fields'))
				->where('section = ' . $db->quote($sectionId))
				->where('name = ' . $db->quote($fieldSection))
				->where('published = 1')
				->where('show_in_front = 1')
				->order('ordering');

			self::$userFields[$sectionId . '_' . $fieldSection] = $db->setQuery($query)->loadObjectlist();
		}

		$rowData      = self::$userFields[$sectionId . '_' . $fieldSection];
		$exField      = '';
		$exFieldTitle = '';

		foreach ($rowData as $index => $data)
		{
			$type     = $data->type;
			$asterisk = $data->required > 0 ? '* ' : '';

			if ($fieldType != 'hidden')
			{
				$exFieldTitle .= '<div class="userfield_label">' . $asterisk . $data->title . '</div>';
			}

			$textValue = $addWish == 1 ? $myWish : '';

			if (!empty($cart) && isset($cart[$idx][$data->name]))
			{
				$textValue = $cart[$idx][$data->name];

				if ($type == \RedshopHelperExtrafields::TYPE_DATE_PICKER)
				{
					$textValue = date("d-m-Y", strtotime($cart[$idx][$data->name]));
				}
			}

			if ($fieldType == 'hidden')
			{
				$value = '';

				if ($type == \RedshopHelperExtrafields::TYPE_DOCUMENTS)
				{
					$userDocuments = \JFactory::getSession()->get('userDocument', array());
					$fileNames     = array();

					if (isset($userDocuments[$productId]))
					{
						foreach ($userDocuments[$productId] as $id => $userDocument)
						{
							$fileNames[] = $userDocument['fileName'];
						}

						$value = implode(',', $fileNames);
					}
				}

				$exField .= '<input type="hidden" name="' . $data->name . '"  id="' . $data->name . '" value="' . $value . '"/>';
			}
			else
			{
				$req = '';

				if ($data->required == 1)
				{
					$req = ' required = "' . $data->required . '"';
				}

				switch ($type)
				{
					default:
					case \RedshopHelperExtrafields::TYPE_TEXT:

						$onKeyup = '';

						if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') == 0)
						{
							$onKeyup = $addToCartFormName . '.' . $data->name . '.value = this.value';
						}

						$exField .= '<div class="userfield_input">'
							. '<input class="' . $data->class . '" type="text" maxlength="' . $data->maxlength . '"'
							. ' onkeyup="var f_value = this.value;' . $onKeyup . '" name="extrafields' . $productId . '[]"'
							. ' id="' . $data->name . '" ' . $req . ' userfieldlbl="' . $data->title . '" '
							. ' value="' . $textValue . '" size="' . $data->size . '" />'
							. '</div>';
						break;

					case \RedshopHelperExtrafields::TYPE_TEXT_AREA:

						$onKeyup = '';

						if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') == 0)
						{
							$onKeyup = $addToCartFormName . '.' . $data->name . '.value = this.value';
						}

						$exField .= '<div class="userfield_input">';
						$exField .= '<textarea class="' . $data->class . '"  name="extrafields' . $productId . '[]" id="' . $data->name . '" ' . $req . ' userfieldlbl="' . $data->title . '" cols="' . $data->cols . '" onkeyup=" var f_value = this.value;' . $onKeyup . '" rows="' . $data->rows . '" >' . $textValue . '</textarea>';
						$exField .= '</div>';
						break;

					case \RedshopHelperExtrafields::TYPE_CHECK_BOX:

						$fieldCheck = \RedshopEntityField::getInstance($data->id)->getFieldValues();
						$checkData  = explode(",", $cart[$idx][$data->name]);

						foreach ($fieldCheck as $aFieldCheck)
						{
							$checked = '';

							if (in_array($aFieldCheck->field_value, $checkData))
							{
								$checked = ' checked="checked" ';
							}

							$exField .= '<div class="userfield_input">';
							$exField .= '<input  class="' . $data->class . '" type="checkbox"  ' . $checked . ' name="extrafields' . $productId . '[]" id="' . $data->name . "_" . $aFieldCheck->value_id . '" userfieldlbl="' . $data->title . '" value="' . $aFieldCheck->field_value . '" ' . $req . ' />' . $aFieldCheck->field_value;
							$exField .= '</div>';
						}

						break;

					case \RedshopHelperExtrafields::TYPE_RADIO_BUTTON:

						$fieldCheck = \RedshopEntityField::getInstance($data->id)->getFieldValues();

						foreach ($fieldCheck as $aFieldCheck)
						{
							$exField .= '<div class="userfield_input">';
							$exField .= '<input class="' . $data->class . '" type="radio" name="extrafields' . $productId . '[]" userfieldlbl="' . $data->title . '"  id="' . $data->name . "_" . $aFieldCheck->value_id . '" value="' . $aFieldCheck->field_value . '" ' . $req . ' />' . $aFieldCheck->field_name;
							$exField .= '</div>';
						}

						break;

					case \RedshopHelperExtrafields::TYPE_SELECT_BOX_SINGLE:

						$fieldCheck = \RedshopEntityField::getInstance($data->id)->getFieldValues();
						$checkData  = explode(",", $cart[$idx][$data->name]);
						$exField    .= '<div class="userfield_input"><select name="extrafields' . $productId . '[]" ' . $req . ' id="' . $data->name . '" userfieldlbl="' . $data->title . '">';
						$exField    .= '<option value="">' . \JText::_('COM_REDSHOP_SELECT') . '</option>';

						foreach ($fieldCheck as $aFieldCheck)
						{
							if ($aFieldCheck->field_value != "" && $aFieldCheck->field_value != "-" && $aFieldCheck->field_value != "0" && $aFieldCheck->field_value != "select")
							{
								$selected = '';

								if (in_array($aFieldCheck->field_value, $checkData))
								{
									$selected = ' selected="selected" ';
								}

								$exField .= '<option value="' . $aFieldCheck->field_value . '" ' . $selected . '   >' . $aFieldCheck->field_value . '</option>';
							}
						}

						$exField .= '</select></div>';
						break;

					case \RedshopHelperExtrafields::TYPE_SELECT_BOX_MULTIPLE:

						$fieldCheck = \RedshopEntityField::getInstance($data->id)->getFieldValues();
						$checkData  = explode(",", $cart[$idx][$data->name]);
						$exField    .= '<div class="userfield_input"><select multiple="multiple" size=10 name="extrafields' . $productId . '[]" ' . $req . ' id="' . $data->name . '" userfieldlbl="' . $data->title . '">';

						foreach ($fieldCheck as $aFieldCheck)
						{
							$selected = '';

							if (in_array(urlencode($aFieldCheck->field_value), $checkData))
							{
								$selected = ' selected="selected" ';
							}

							$exField .= '<option value="' . urlencode($aFieldCheck->field_value) . '" ' . $selected . ' >' . $aFieldCheck->field_value . '</option>';
						}

						$exField .= '</select></div>';
						break;

					case \RedshopHelperExtrafields::TYPE_DOCUMENTS:

						// File Upload
						\JHtml::_('redshopjquery.framework');
						/** @scrutinizer ignore-deprecated */ \JHtml::script('com_redshop/ajaxupload.min.js', false, true);

						$ajax   = '';
						$unique = $data->name . '_' . $productId;

						if ($isAtt > 0)
						{
							$ajax   = 'ajax';
							$unique = $data->name;
						}

						$exField .= '<div class="userfield_input">'
							. '<input type="button" class="' . $data->class . '" value="' . \JText::_('COM_REDSHOP_UPLOAD') . '" id="file'
							. $ajax . $unique . '" />';
						$exField .= '<script>
							new AjaxUpload(
								"file' . $ajax . $unique . '",
								{
									action:"' . \JUri::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
									data :{
										mname:"file' . $ajax . $data->name . '",
										product_id:"' . $productId . '",
										uniqueOl:"' . $unique . '",
										fieldName: "' . $data->name . '",
										ajaxFlag: "' . $ajax . '"
									},
									name:"file' . $ajax . $unique . '",
									onSubmit : function(file , ext){
										jQuery("file' . $ajax . $unique . '").text("' . \JText::_('COM_REDSHOP_UPLOADING') . '" + file);
										this.disable();
									},
									onComplete :function(file,response){
										jQuery("#ol_' . $unique . ' li.error").remove();
										jQuery("#ol_' . $unique . '").append(response);
										var uploadfiles = jQuery("#ol_' . $unique . ' li").map(function() {
											return jQuery(this).find("span").text();
										}).get().join(",");
										this.enable();
										jQuery("#' . $ajax . $unique . '").val(uploadfiles);
										jQuery("#' . $data->name . '").val(uploadfiles);
									}
								}
							);
						</script>';

						$exField .= '<p>' . \JText::_('COM_REDSHOP_UPLOADED_FILE') . ':</p>' . ExtraFields::displayUserDocuments($productId, $data, $ajax) . '</div>';
						break;

					case \RedshopHelperExtrafields::TYPE_IMAGE_SELECT:

						$fieldCheck = \RedshopEntityField::getInstance($data->id)->getFieldValues();
						$exField    .= '<table><tr>';

						foreach ($fieldCheck as $aFieldCheck)
						{
							$exField .= '<td><div class="userfield_input"><img id="' . $data->name . "_" . $aFieldCheck->value_id . '" class="pointer imgClass_' . $productId . '" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $aFieldCheck->field_name . '" title="' . $aFieldCheck->field_value . '" alt="' . $aFieldCheck->field_value . '" onclick="javascript:setProductUserFieldImage(\'' . $data->name . '\',\'' . $productId . '\',\'' . $aFieldCheck->field_value . '\',this);"/></div></td>';
						}

						$exField .= '</tr></table>';
						$ajax    = '';

						if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isAtt > 0)
						{
							$ajax = 'ajax';
						}

						$exField .= '<input type="hidden" name="extrafields' . $productId . '[]" id="' . $ajax . $data->name . '_' . $productId . '" userfieldlbl="' . $data->title . '" ' . $req . '  />';
						break;

					case \RedshopHelperExtrafields::TYPE_DATE_PICKER:

						$ajax = '';
						$req  = $data->required;

						if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isAtt == 0)
						{
							$req = 0;
						}

						if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isAtt > 0)
						{
							$ajax = 'ajax';
						}

						$exField .= '<div class="userfield_input">'
							. \JHtml::_(
								'redshopcalendar.calendar',
								$textValue,
								'extrafields' . $productId . '[]',
								$ajax . $data->name . '_' . $productId,
								null,
								array(
									'class'        => $data->class,
									'size'         => $data->size,
									'maxlength'    => $data->maxlength,
									'required'     => $req,
									'userfieldlbl' => $data->title,
									'errormsg'     => ''
								)
							)
							. '</div>';
						break;

					case \RedshopHelperExtrafields::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS:
						$fieldCheck = \RedshopHelperExtrafields::getData($data->id, 12, $productId);

						if ($fieldCheck)
						{
							$mainSplitDateTotal = preg_split(" ", $fieldCheck->data_txt);
							$mainSplitDate      = preg_split(":", $mainSplitDateTotal[0]);
							$mainSplitDateExtra = preg_split(":", $mainSplitDateTotal[1]);

							$dateStart  = mktime(
								0, 0, 0,
								(int) date('m', $mainSplitDate[0]),
								(int) date('d', $mainSplitDate[0]),
								(int) date('Y', $mainSplitDate[0])
							);
							$dateEnd    = mktime(
								23, 59, 59,
								(int) date('m', $mainSplitDate[1]),
								(int) date('d', $mainSplitDate[1]),
								(int) date('Y', $mainSplitDate[1])
							);
							$todayStart = mktime(
								0, 0, 0,
								(int) date('m'),
								(int) date('d'),
								(int) date('Y')
							);
							$todayEnd   = mktime(
								23, 59, 59,
								(int) date('m'),
								(int) date('d'),
								(int) date('Y')
							);

							if ($dateStart <= $todayStart && $dateEnd >= $todayEnd)
							{
								$exField .= '<div class="userfield_input">';
								$exField .= '' . $asterisk . $data->title . ' : <select name="extrafields' . $productId . '[]" id="' . $data->name . '" userfieldlbl="' . $data->title . '" ' . $req . ' >';
								$exField .= '<option value="">' . \JText::_('COM_REDSHOP_SELECT') . '</option>';

								foreach ($mainSplitDateExtra as $aMainSplitDateExtra)
								{
									if ($aMainSplitDateExtra != "")
									{
										$exField .= '<option value="' . date("d-m-Y", $aMainSplitDateExtra) . '"  >' . date("d-m-Y", $aMainSplitDateExtra) . '</option>';
									}
								}

								$exField .= '</select></div>';
							}
						}
						break;
				}
			}

			if (trim($data->desc) != '' && $fieldType != 'hidden')
			{
				$exField .= '<div class="userfield_tooltip">&nbsp; ' . \JHtml::tooltip($data->desc, $data->name, 'tooltip.png', '', '', false) . '</div>';
			}
		}

		return array($exFieldTitle, $exField);
	}
}
