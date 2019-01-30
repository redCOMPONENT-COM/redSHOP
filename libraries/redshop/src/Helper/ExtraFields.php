<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

/**
 * Extra Fields helper
 *
 * @since  2.1.0
 */
class ExtraFields
{
	/**
	 * Extra field display data
	 *
	 * @var    array
	 *
	 * @since  2.1.0
	 */
	protected static $extraFieldDisplay = array();

	/**
	 * Method for render HTML of extra fields
	 *
	 * @param   integer  $fieldSection     Field section
	 * @param   integer  $sectionId        ID of section
	 * @param   string   $fieldName        Field name
	 * @param   string   $templateContent  HTML template content
	 * @param   boolean  $categoryPage     Category page
	 *
	 * @return  string                     HTML content with rendered tag
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	public static function displayExtraFields($fieldSection = 0, $sectionId = 0, $fieldName = '', $templateContent = '', $categoryPage = false)
	{
		$db = \JFactory::getDbo();

		if (!isset(self::$extraFieldDisplay[$fieldSection]))
		{
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_fields'))
				->where($db->qn('section') . ' = ' . $db->quote($fieldSection));

			self::$extraFieldDisplay[$fieldSection] = $db->setQuery($query)->loadObjectList('name');
		}

		$fieldName = explode(',', str_replace('\'', '', $fieldName));
		$rows      = array();

		foreach ($fieldName as $field)
		{
			if (isset(self::$extraFieldDisplay[$fieldSection][$field]))
			{
				$rows[] = self::$extraFieldDisplay[$fieldSection][$field];
			}
		}

		if (empty($rows))
		{
			return $templateContent;
		}

		foreach ($rows as $row)
		{
			$dataValue = \RedshopHelperExtrafields::getData($row->id, $fieldSection, $sectionId);
			self::replaceFieldTag($templateContent, $row, $dataValue, $categoryPage);
		}

		return $templateContent;
	}

	/**
	 * Method for replace extra field with {if custom_field}...{custom_field end if} support
	 *
	 * @param   string   $templateContent  Template content
	 * @param   object   $field            Field data.
	 * @param   mixed    $fieldValue       Field value.
	 * @param   boolean  $isInCategory     Is template in category page?
	 *
	 * @return  void
	 * @throws  \Exception
	 *
	 * @since   2.1.0
	 */
	protected static function replaceFieldTag(&$templateContent, $field, $fieldValue, $isInCategory = false)
	{
		if (empty($templateContent) || empty($field))
		{
			return;
		}

		$tagLabel = $field->name . '_lbl';
		$tag      = $field->name;

		if ($isInCategory)
		{
			$tagLabel = 'producttag:' . $tagLabel;
			$tag      = 'producttag:' . $tag;
		}

		$ifTagStart = '{if ' . $tag . '}';
		$ifTagEnd   = '{' . $tag . ' end if}';

		$hasIfTag        = false;
		$templateIfStart = '';
		$templateIfEnd   = '';
		$templateIfMain  = '';

		// Has If tag
		if (strpos($templateContent, $ifTagStart) !== false && strpos($templateContent, $ifTagEnd) !== false)
		{
			// Get template content.
			$templateStartData = explode($ifTagStart, $templateContent);
			$templateIfStart   = $templateStartData[0];
			$templateEndData   = explode($ifTagEnd, $templateStartData[1]);
			$templateIfEnd     = $templateEndData[1];
			$templateIfMain    = $templateEndData[0];
			$hasIfTag          = true;

			unset($templateEndData, $templateStartData);
		}

		if (empty($fieldValue) || !$field->published || (!$field->show_in_front && \JFactory::getApplication()->isClient('site')))
		{
			if ($hasIfTag)
			{
				$templateContent = $templateIfStart . $templateIfEnd;
			}
			else
			{
				$templateContent = str_replace(array('{' . $tagLabel . '}', '{' . $tag . '}'), '', $templateContent);
			}

			return;
		}

		if ($hasIfTag)
		{
			$templateContent = $templateIfStart . $templateIfMain . $templateIfEnd;
		}

		$displayValue = '';

		switch ($field->type)
		{
			case \RedshopHelperExtrafields::TYPE_TEXT_AREA:
				$displayValue = \RedshopLayoutHelper::render('extrafields.display.textarea', array('data' => $fieldValue->data_txt));

				break;

			case \RedshopHelperExtrafields::TYPE_CHECK_BOX:
			case \RedshopHelperExtrafields::TYPE_RADIO_BUTTON:
			case \RedshopHelperExtrafields::TYPE_SELECT_BOX_MULTIPLE:
				$fieldValues = \RedshopEntityField::getInstance($field->id)->getFieldValues();
				$checkData   = explode(',', $fieldValue->data_txt);
				$htmlData    = array();

				foreach ($fieldValues as $value)
				{
					if (!in_array(urlencode($value->field_value), $checkData))
					{
						continue;
					}

					$htmlData[] = urldecode($value->field_value);
				}

				$displayValue = \RedshopLayoutHelper::render(
					'extrafields.display.select',
					array(
						'data' => $htmlData
					)
				);

				break;

			case \RedshopHelperExtrafields::TYPE_SELECT_COUNTRY_BOX:
				if (!empty($fieldValue->data_txt))
				{
					$displayValue = \RedshopLayoutHelper::render(
						'extrafields.display.country',
						array('data' => (int) $fieldValue->data_txt)
					);
				}

				break;

			case \RedshopHelperExtrafields::TYPE_DOCUMENTS:
				// Support Legacy string.
				if (preg_match('/\n/', $fieldValue->data_txt))
				{
					$documentExplode = explode("\n", $fieldValue->data_txt);
					$documentValue   = array($documentExplode[0] => $documentExplode[1]);
				}
				else
				{
					// Support for multiple file upload using JSON for better string handling
					$documentValue = json_decode($fieldValue->data_txt);
				}

				if (!empty($documentValue))
				{
					foreach ($documentValue as $documentTitle => $fileName)
					{
						$documentLink    = REDSHOP_FRONT_DOCUMENT_ABSPATH . 'extrafields/' . $fileName;
						$absDocumentLink = REDSHOP_FRONT_DOCUMENT_RELPATH . 'extrafields/' . $fileName;

						if (!\JFile::exists($absDocumentLink))
						{
							continue;
						}

						$displayValue .= \RedshopLayoutHelper::render(
							'extrafields.display.document',
							array(
								'data'  => $fieldValue,
								'field' => $field,
								'link'  => $documentLink,
								'title' => $documentTitle
							)
						);
					}
				}

				break;

			case \RedshopHelperExtrafields::TYPE_IMAGE_SELECT:
			case \RedshopHelperExtrafields::TYPE_IMAGE_WITH_LINK:
				$documentValues = \RedshopEntityField::getInstance($field->id)->getFieldValues();
				$tmpImagesHover = !empty($fieldValue->alt_text) ? explode(',,,,,', $fieldValue->alt_text) : array();
				$tmpImagesLink  = !empty($fieldValue->image_link) ? explode(',,,,,', $fieldValue->image_link) : array();

				$dataList    = explode(",", $fieldValue->data_txt);
				$imagesLink  = array();
				$imagesHover = array();

				foreach ($dataList as $index => $dataItem)
				{
					$imagesLink[$dataItem]  = isset($tmpImagesLink[$index]) ? $tmpImagesLink[$index] : '';
					$imagesHover[$dataItem] = isset($tmpImagesHover[$index]) ? $tmpImagesHover[$index] : '';
				}

				foreach ($documentValues as $documentValue)
				{
					if (!in_array($documentValue->value_id, $dataList))
					{
						continue;
					}

					$fileName     = $documentValue->field_name;
					$documentLink = REDSHOP_FRONT_IMAGES_ABSPATH . "extrafield/" . $fileName;

					$displayValue .= \RedshopLayoutHelper::render(
						'extrafields.display.image',
						array(
							'link'      => $imagesLink,
							'hover'     => $imagesHover,
							'value'     => $documentValue,
							'imageLink' => $documentLink,
							'data'      => $field
						)
					);
				}

				break;

			case \RedshopHelperExtrafields::TYPE_TEXT:
			case \RedshopHelperExtrafields::TYPE_WYSIWYG:
			case \RedshopHelperExtrafields::TYPE_DATE_PICKER:
			case \RedshopHelperExtrafields::TYPE_SELECT_BOX_SINGLE:
			default:
				$displayValue = \RedshopLayoutHelper::render(
					'extrafields.display.text',
					array('data' => $fieldValue->data_txt)
				);

				break;
		}

		$displayTitle    = !empty($fieldValue->data_txt) ? $fieldValue->title : '';
		$displayValue    = \RedshopHelperTemplate::parseRedshopPlugin($displayValue);
		$templateContent = str_replace(
			array('{' . $tagLabel . '}', '{' . $tag . '}'),
			array(\JText::_($displayTitle), $displayValue),
			$templateContent
		);
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
	 * @since   2.1.0
	 */
	public static function displayUserDocuments($productId, $extraFieldValues, $ajaxFlag = '')
	{
		$session       = \JFactory::getSession();
		$userDocuments = $session->get('userDocument', array());
		$html          = array('<ol id="ol_' . $extraFieldValues->name . '_' . $productId . '">');
		$fileNames     = array();

		if (isset($userDocuments[$productId]))
		{
			foreach ($userDocuments[$productId] as $id => $userDocument)
			{
				$fileNames[] = $userDocument['fileName'];
				$sendData    = array(
					'id'         => $id,
					'product_id' => $productId,
					'uniqueOl'   => $ajaxFlag . $extraFieldValues->name . '_' . $productId,
					'fieldName'  => $extraFieldValues->name,
					'ajaxFlag'   => $ajaxFlag,
					'fileName'   => $userDocument['fileName'],
					'action'     => \JUri::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=removeAjaxUpload'
				);

				$html[] = '<li id="uploadNameSpan' . $id . '"><span>' . $userDocument['fileName'] . '</span>&nbsp;'
					. '<a href="javascript:removeAjaxUpload(' . htmlspecialchars(json_encode($sendData)) . ');">'
					. \JText::_('COM_REDSHOP_DELETE') . '</a></li>';
			}
		}

		$html[] = '</ol>';
		$html[] = '<input type="hidden" name="extrafields' . $productId . '[]" id="' . $ajaxFlag . $extraFieldValues->name . '_' . $productId . '" '
			. ($extraFieldValues->required ? ' required="required"' : '') . ' userfieldlbl="' . $extraFieldValues->title
			. '" value="' . implode(',', $fileNames) . '" />';

		return implode('', $html);
	}

	/**
	 * Method for get section field names.
	 *
	 * @param   integer  $section    Section ID
	 * @param   integer  $front      Is show on front?
	 * @param   integer  $published  Is published?
	 * @param   integer  $required   Is required?
	 *
	 * @return  array                List of field
	 *
	 * @since   2.1.0
	 */
	public static function getSectionFieldNames($section = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, $front = 1,
		$published = 1, $required = 0)
	{
		$fields = \RedshopHelperExtrafields::getSectionFieldList($section, $front, $published, $required);

		if (empty($fields))
		{
			return array();
		}

		$result = array();

		foreach ($fields as $field)
		{
			$result[] = $field->name;
		}

		return $result;
	}
}
