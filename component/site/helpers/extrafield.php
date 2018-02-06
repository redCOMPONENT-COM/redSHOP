<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Redshop\Helper\ExtraFields;

defined('_JEXEC') or die;

/**
 * Extra Field Class
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
	 */
	public function list_all_user_fields($fieldSection = "", $sectionId = RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, $fieldType = '', $idx = 'NULL', $isAtt = 0, $productId, $myWish = '', $addWish = 0)
	{
		$db      = JFactory::getDbo();
		$cart    = RedshopHelperCartSession::getCart();

		$prePrefix = "";

		if ($isAtt == 1)
		{
			$prePrefix = "ajax_";
		}

		$addToCartFormName = 'addtocart_' . $prePrefix . 'prd_' . $productId;

		if (!array_key_exists($sectionId . '_' . $fieldSection, self::$userFields))
		{
			$query                                              = $db->getQuery(true)
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

			$textValue = '';

			if ($addWish == 1)
			{
				$textValue = $myWish;
			}

			if (!empty($cart) && isset($cart[$idx][$data->name]))
			{
				$textValue = $cart[$idx][$data->name];

				if ($type == RedshopHelperExtrafields::TYPE_DATE_PICKER)
				{
					$textValue = date("d-m-Y", strtotime($cart[$idx][$data->name]));
				}
			}

			if ($fieldType == 'hidden')
			{
				$value = '';

				if ($type == RedshopHelperExtrafields::TYPE_DOCUMENTS)
				{
					$userDocuments = JFactory::getSession()->get('userDocument', array());
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
					case RedshopHelperExtrafields::TYPE_TEXT:

						$onKeyup = '';

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') == 0)
						{
							$onKeyup = $addToCartFormName . '.' . $data->name . '.value = this.value';
						}

						$exField .= '<div class="userfield_input">';
						$exField .= '<input class="' . $data->class . '" type="text" maxlength="' . $data->maxlength . '" onkeyup="var f_value = this.value;' . $onKeyup . '" name="extrafields' . $productId . '[]"  id="' . $data->name . '" ' . $req . ' userfieldlbl="' . $data->title . '" value="' . $textValue . '" size="' . $data->size . '" />';
						$exField .= '</div>';
						break;

					case RedshopHelperExtrafields::TYPE_TEXT_AREA:

						$onKeyup = '';

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') == 0)
						{
							$onKeyup = $addToCartFormName . '.' . $data->name . '.value = this.value';
						}

						$exField .= '<div class="userfield_input">';
						$exField .= '<textarea class="' . $data->class . '"  name="extrafields' . $productId . '[]"  id="' . $data->name . '" ' . $req . ' userfieldlbl="' . $data->title . '" cols="' . $data->cols . '" onkeyup=" var f_value = this.value;' . $onKeyup . '" rows="' . $data->rows . '" >' . $textValue . '</textarea>';
						$exField .= '</div>';
						break;

					case RedshopHelperExtrafields::TYPE_CHECK_BOX:

						$fieldCheck = RedshopEntityField::getInstance($data->id)->getFieldValues();
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

					case RedshopHelperExtrafields::TYPE_RADIO_BUTTON:

						$fieldCheck = RedshopEntityField::getInstance($data->id)->getFieldValues();
						$checkData  = explode(",", $cart[$idx][$data->name]);

						foreach ($fieldCheck as $aFieldCheck)
						{
							$checked = '';

							if (in_array($aFieldCheck->field_value, $checkData))
							{
								$checked = ' checked="checked" ';
							}

							$exField .= '<div class="userfield_input">';
							$exField .= '<input class="' . $data->class . '" type="radio" ' . $checked . ' name="extrafields' . $productId . '[]" userfieldlbl="' . $data->title . '"  id="' . $data->name . "_" . $aFieldCheck->value_id . '" value="' . $aFieldCheck->field_value . '" ' . $req . ' />' . $aFieldCheck->field_name;
							$exField .= '</div>';
						}

						break;

					case RedshopHelperExtrafields::TYPE_SELECT_BOX_SINGLE:

						$fieldCheck = RedshopEntityField::getInstance($data->id)->getFieldValues();
						$checkData  = explode(",", $cart[$idx][$data->name]);
						$exField    .= '<div class="userfield_input"><select name="extrafields' . $productId . '[]" ' . $req . ' id="' . $data->name . '" userfieldlbl="' . $data->title . '">';
						$exField    .= '<option value="">' . JText::_('COM_REDSHOP_SELECT') . '</option>';

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

					case RedshopHelperExtrafields::TYPE_SELECT_BOX_MULTIPLE:

						$fieldCheck = RedshopEntityField::getInstance($data->id)->getFieldValues();
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

					case RedshopHelperExtrafields::TYPE_DOCUMENTS:

						// File Upload
						JHtml::_('redshopjquery.framework');
						/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/ajaxupload.min.js', false, true);

						$ajax   = '';
						$unique = $data->name . '_' . $productId;

						if ($isAtt > 0)
						{
							$ajax   = 'ajax';
							$unique = $data->name;
						}

						$exField .= '<div class="userfield_input">'
							. '<input type="button" class="' . $data->class . '" value="' . JText::_('COM_REDSHOP_UPLOAD') . '" id="file'
							. $ajax . $unique . '" />';
						$exField .= '<script>
							new AjaxUpload(
								"file' . $ajax . $unique . '",
								{
									action:"' . JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
									data :{
										mname:"file' . $ajax . $data->name . '",
										product_id:"' . $productId . '",
										uniqueOl:"' . $unique . '",
										fieldName: "' . $data->name . '",
										ajaxFlag: "' . $ajax . '"
									},
									name:"file' . $ajax . $unique . '",
									onSubmit : function(file , ext){
										jQuery("file' . $ajax . $unique . '").text("' . JText::_('COM_REDSHOP_UPLOADING') . '" + file);
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

						$exField .= '<p>' . JText::_('COM_REDSHOP_UPLOADED_FILE') . ':</p>' . Redshop\Helper\ExtraFields::displayUserDocuments($productId, $data, $ajax) . '</div>';
						break;

					case RedshopHelperExtrafields::TYPE_IMAGE_SELECT:

						$fieldCheck = RedshopEntityField::getInstance($data->id)->getFieldValues();
						$exField    .= '<table><tr>';

						foreach ($fieldCheck as $aFieldCheck)
						{
							$exField .= '<td><div class="userfield_input"><img id="' . $data->name . "_" . $aFieldCheck->value_id . '" class="pointer imgClass_' . $productId . '" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $aFieldCheck->field_name . '" title="' . $aFieldCheck->field_value . '" alt="' . $aFieldCheck->field_value . '" onclick="javascript:setProductUserFieldImage(\'' . $data->name . '\',\'' . $productId . '\',\'' . $aFieldCheck->field_value . '\',this);"/></div></td>';
						}

						$exField .= '</tr></table>';
						$ajax    = '';

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isAtt > 0)
						{
							$ajax = 'ajax';
						}

						$exField .= '<input type="hidden" name="extrafields' . $productId . '[]" id="' . $ajax . $data->name . '_' . $productId . '" userfieldlbl="' . $data->title . '" ' . $req . '  />';
						break;

					case RedshopHelperExtrafields::TYPE_DATE_PICKER:

						$ajax = '';
						$req  = $data->required;

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isAtt == 0)
						{
							$req = 0;
						}

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isAtt > 0)
						{
							$ajax = 'ajax';
						}

						$exField .= '<div class="userfield_input">'
							. JHtml::_(
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

					case RedshopHelperExtrafields::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS:
						$fieldCheck = RedshopHelperExtrafields::getData($data->id, 12, $productId);

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
								$exField .= '<option value="">' . JText::_('COM_REDSHOP_SELECT') . '</option>';

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
				$exField .= '<div class="userfield_tooltip">&nbsp; ' . JHTML::tooltip($data->desc, $data->name, 'tooltip.png', '', '', false) . '</div>';
			}
		}

		$ex    = array();
		$ex[0] = $exFieldTitle;
		$ex[1] = $exField;

		return $ex;
	}

	/**
	 * Method for display extra field.
	 *
	 * @param   integer  $fieldSection  Field section
	 * @param   integer  $sectionId     Section ID
	 * @param   string   $fieldName     Field name
	 * @param   string   $templateData  Template content
	 * @param   int      $categoryPage   Category page
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
