<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Fields;

defined('_JEXEC') or die;

/**
 * Fields - Site helper
 *
 * @since  __DEPLOY_VERSION__
 */
class SiteHelper
{
	/**
	 * Method for render fields
	 *
	 * @param   integer $fieldSection Field Section
	 * @param   integer $sectionId    Section ID
	 * @param   string  $uniqueClass  Unique class
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
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
						'redshopjquery.calendar',
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
}
