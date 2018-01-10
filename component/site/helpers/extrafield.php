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
	 * @deprecated  __DEPLOY_VERSION__
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
	 * @deprecated  __DEPLOY_VERSION__
	 * @see Redshop\Helper\ExtraFields::displayUserDocuments
	 */
	public function displayUserDocuments($productId, $extraFieldValues, $ajaxFlag = '')
	{
		return Redshop\Helper\ExtraFields::displayUserDocuments($productId, $extraFieldValues, $ajaxFlag);
	}

	public function list_all_user_fields($field_section = "", $section_id = RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, $field_type = '', $idx = 'NULL', $isatt = 0, $product_id, $mywish = "", $addwish = 0)
	{
		$db      = JFactory::getDbo();
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$url     = JURI::base();

		$preprefix = "";

		if ($isatt == 1)
		{
			$preprefix = "ajax_";
		}

		$addtocartFormName = 'addtocart_' . $preprefix . 'prd_' . $product_id;

		if (!array_key_exists($section_id . '_' . $field_section, self::$userFields))
		{
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_fields'))
				->where('section = ' . $db->quote($section_id))
				->where('name = ' . $db->quote($field_section))
				->where('published = 1')
				->where('show_in_front = 1')
				->order('ordering');
			$db->setQuery($query);
			self::$userFields[$section_id . '_' . $field_section] = $db->loadObjectlist();
		}

		$row_data       = self::$userFields[$section_id . '_' . $field_section];
		$ex_field       = '';
		$ex_field_title = '';

		for ($i = 0, $in = count($row_data); $i < $in; $i++)
		{
			$type = $row_data[$i]->type;
			$asterisk = $row_data[$i]->required > 0 ? '* ' : '';

			if ($field_type != 'hidden')
			{
				$ex_field_title .= '<div class="userfield_label">' . $asterisk . $row_data[$i]->title . '</div>';
			}

			$text_value = '';

			if ($addwish == 1)
			{
				$text_value = $mywish;
			}

			if ($cart && isset($cart[$idx][$row_data[$i]->name]))
			{
				if ($type == RedshopHelperExtrafields::TYPE_DATE_PICKER)
				{
					$text_value = date("d-m-Y", strtotime($cart[$idx][$row_data[$i]->name]));
				}
				else
				{
					$text_value = $cart[$idx][$row_data[$i]->name];
				}
			}

			if ($field_type == 'hidden')
			{
				$value = '';

				if ($type == RedshopHelperExtrafields::TYPE_DOCUMENTS)
				{
					$userDocuments = $session->get('userDocument', array());
					$fileNames = array();

					if (isset($userDocuments[$product_id]))
					{
						foreach ($userDocuments[$product_id] as $id => $userDocument)
						{
							$fileNames[] = $userDocument['fileName'];
						}

						$value = implode(',', $fileNames);
					}
				}

				$ex_field .= '<input type="hidden" name="' . $row_data[$i]->name . '"  id="' . $row_data[$i]->name . '" value="' . $value . '"/>';
			}
			else
			{
				if ($row_data[$i]->required == 1)
				{
					$req = ' required = "' . $row_data[$i]->required . '"';
				}
				else
				{
					$req = '';
				}

				switch ($type)
				{
					default:
					case RedshopHelperExtrafields::TYPE_TEXT:

						$onkeyup = '';

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') == 0)
						{
							$onkeyup = $addtocartFormName . '.' . $row_data[$i]->name . '.value = this.value';
						}

						$ex_field .= '<div class="userfield_input"><input class="' . $row_data[$i]->class . '" type="text" maxlength="' . $row_data[$i]->maxlength . '" onkeyup="var f_value = this.value;' . $onkeyup . '" name="extrafields' . $product_id . '[]"  id="' . $row_data[$i]->name . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->title . '" value="' . $text_value . '" size="' . $row_data[$i]->size . '" /></div>';
						break;

					case RedshopHelperExtrafields::TYPE_TEXT_AREA:

						$onkeyup = '';

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') == 0)
						{
							$onkeyup = $addtocartFormName . '.' . $row_data[$i]->name . '.value = this.value';
						}

						$ex_field .= '<div class="userfield_input"><textarea class="' . $row_data[$i]->class . '"  name="extrafields' . $product_id . '[]"  id="' . $row_data[$i]->name . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->title . '" cols="' . $row_data[$i]->cols . '" onkeyup=" var f_value = this.value;' . $onkeyup . '" rows="' . $row_data[$i]->rows . '" >' . $text_value . '</textarea></div>';
						break;

					case RedshopHelperExtrafields::TYPE_CHECK_BOX:

						$field_chk = $this->getFieldValue($row_data[$i]->id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->name]);

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							$checked = '';

							if (@in_array($field_chk[$c]->field_value, $chk_data))
							{
								$checked = ' checked="checked" ';
							}

							$ex_field .= '<div class="userfield_input"><input  class="' . $row_data[$i]->class . '" type="checkbox"  ' . $checked . ' name="extrafields' . $product_id . '[]" id="' . $row_data[$i]->name . "_" . $field_chk[$c]->value_id . '" userfieldlbl="' . $row_data[$i]->title . '" value="' . $field_chk[$c]->field_value . '" ' . $req . ' />' . $field_chk[$c]->field_value . '</div>';
						}
						break;

					case RedshopHelperExtrafields::TYPE_RADIO_BUTTON:

						$field_chk = $this->getFieldValue($row_data[$i]->id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->name]);

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							$checked = '';

							if (@in_array($field_chk[$c]->field_value, $chk_data))
							{
								$checked = ' checked="checked" ';
							}

							$ex_field .= '<div class="userfield_input"><input class="' . $row_data[$i]->class . '" type="radio" ' . $checked . ' name="extrafields' . $product_id . '[]" userfieldlbl="' . $row_data[$i]->title . '"  id="' . $row_data[$i]->name . "_" . $field_chk[$c]->value_id . '" value="' . $field_chk[$c]->field_value . '" ' . $req . ' />' . $field_chk[$c]->field_name . '</div>';
						}
						break;

					case RedshopHelperExtrafields::TYPE_SELECT_BOX_SINGLE:

						$field_chk = $this->getFieldValue($row_data[$i]->id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->name]);
						$ex_field .= '<div class="userfield_input"><select name="extrafields' . $product_id . '[]" ' . $req . ' id="' . $row_data[$i]->name . '" userfieldlbl="' . $row_data[$i]->title . '">';
						$ex_field .= '<option value="">' . JText::_('COM_REDSHOP_SELECT') . '</option>';

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							if ($field_chk[$c]->field_value != "" && $field_chk[$c]->field_value != "-" && $field_chk[$c]->field_value != "0" && $field_chk[$c]->field_value != "select")
							{
								$selected = '';

								if (@in_array($field_chk[$c]->field_value, $chk_data))
								{
									$selected = ' selected="selected" ';
								}

								$ex_field .= '<option value="' . $field_chk[$c]->field_value . '" ' . $selected . '   >' . $field_chk[$c]->field_value . '</option>';
							}
						}

						$ex_field .= '</select></div>';
						break;

					case RedshopHelperExtrafields::TYPE_SELECT_BOX_MULTIPLE:

						$field_chk = $this->getFieldValue($row_data[$i]->id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->name]);
						$ex_field .= '<div class="userfield_input"><select multiple="multiple" size=10 name="extrafields' . $product_id . '[]" ' . $req . ' id="' . $row_data[$i]->name . '" userfieldlbl="' . $row_data[$i]->title . '">';

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							$selected = '';

							if (@in_array(urlencode($field_chk[$c]->field_value), $chk_data))
							{
								$selected = ' selected="selected" ';
							}

							$ex_field .= '<option value="' . urlencode($field_chk[$c]->field_value) . '" ' . $selected . ' >' . $field_chk[$c]->field_value . '</option>';
						}

						$ex_field .= '</select></div>';
						break;

					case RedshopHelperExtrafields::TYPE_DOCUMENTS:
						// File Upload
						JHtml::_('redshopjquery.framework');
						JHtml::script('com_redshop/ajaxupload.js', false, true);

						$ajax = '';
						$unique = $row_data[$i]->name . '_' . $product_id;

						if ($isatt > 0)
						{
							$ajax = 'ajax';
							$unique = $row_data[$i]->name;
						}

						$ex_field .= '<div class="userfield_input">'
							. '<input type="button" class="' . $row_data[$i]->class . '" value="' . JText::_('COM_REDSHOP_UPLOAD') . '" id="file'
							. $ajax . $unique . '" />';
						$ex_field .= '<script>
							new AjaxUpload(
								"file' . $ajax . $unique . '",
								{
									action:"' . JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
									data :{
										mname:"file' . $ajax . $row_data[$i]->name . '",
										product_id:"' . $product_id . '",
										uniqueOl:"' . $unique . '",
										fieldName: "' . $row_data[$i]->name . '",
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
										jQuery("#' . $row_data[$i]->name . '").val(uploadfiles);
									}
								}
							);
						</script>';

						$ex_field .= '<p>' . JText::_('COM_REDSHOP_UPLOADED_FILE') . ':</p>'
							. Redshop\Helper\ExtraFields::displayUserDocuments($product_id, $row_data[$i], $ajax) . '</div>';

						break;

					case RedshopHelperExtrafields::TYPE_IMAGE_SELECT:

						$field_chk = $this->getFieldValue($row_data[$i]->id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->name]);
						$ex_field .= '<table><tr>';

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							$ex_field .= '<td><div class="userfield_input"><img id="' . $row_data[$i]->name . "_" . $field_chk[$c]->value_id . '" class="pointer imgClass_' . $product_id . '" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $field_chk[$c]->field_name . '" title="' . $field_chk[$c]->field_value . '" alt="' . $field_chk[$c]->field_value . '" onclick="javascript:setProductUserFieldImage(\'' . $row_data[$i]->name . '\',\'' . $product_id . '\',\'' . $field_chk[$c]->field_value . '\',this);"/></div></td>';
						}

						$ex_field .= '</tr></table>';
						$ajax = '';

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isatt > 0)
						{
							$ajax = 'ajax';
						}

						$ex_field .= '<input type="hidden" name="extrafields' . $product_id . '[]" id="' . $ajax . $row_data[$i]->name . '_' . $product_id . '" userfieldlbl="' . $row_data[$i]->title . '" ' . $req . '  />';
						break;

					case RedshopHelperExtrafields::TYPE_DATE_PICKER:

						$ajax = '';
						$req = $row_data[$i]->required;

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isatt == 0)
						{
							$req = 0;
						}

						if (Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isatt > 0)
						{
							$ajax = 'ajax';
						}

						$ex_field .= '<div class="userfield_input">'
							. JHtml::_(
								'redshopcalendar.calendar',
								$text_value,
								'extrafields' . $product_id . '[]',
								$ajax . $row_data[$i]->name . '_' . $product_id,
								null,
								array(
									'class' => $row_data[$i]->class,
									'size' => $row_data[$i]->size,
									'maxlength' => $row_data[$i]->maxlength,
									'required' => $req,
									'userfieldlbl' => $row_data[$i]->title,
									'errormsg' => ''
								)
							)
							. '</div>';
						break;

					case RedshopHelperExtrafields::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS:
						$field_chk = $this->getSectionFieldDataList($row_data[$i]->id, 12, $product_id);

						if (count($field_chk) > 0)
						{
							$mainsplit_date_total = preg_split(" ", $field_chk->data_txt);
							$mainsplit_date       = preg_split(":", $mainsplit_date_total[0]);
							$mainsplit_date_extra = preg_split(":", $mainsplit_date_total[1]);

							$dateStart  = mktime(0, 0, 0, date('m', $mainsplit_date[0]), date('d', $mainsplit_date[0]), date('Y', $mainsplit_date[0]));
							$dateEnd    = mktime(23, 59, 59, date('m', $mainsplit_date[1]), date('d', $mainsplit_date[1]), date('Y', $mainsplit_date[1]));
							$todayStart = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
							$todayEnd   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

							if ($dateStart <= $todayStart && $dateEnd >= $todayEnd)
							{
								$ex_field .= '<div class="userfield_input">';
								$ex_field .= '' . $asterisk . $row_data[$i]->title . ' : <select name="extrafields' . $product_id . '[]" id="' . $row_data[$i]->name . '" userfieldlbl="' . $row_data[$i]->title . '" ' . $req . ' >';
								$ex_field .= '<option value="">' . JText::_('COM_REDSHOP_SELECT') . '</option>';

								for ($c = 0, $cn = count($mainsplit_date_extra); $c < $cn; $c++)
								{
									if ($mainsplit_date_extra[$c] != "")
									{
										$ex_field .= '<option value="' . date("d-m-Y", $mainsplit_date_extra[$c]) . '"  >' . date("d-m-Y", $mainsplit_date_extra[$c]) . '</option>';
									}
								}

								$ex_field .= '</select></div>';
							}
						}
						break;
				}
			}

			if (trim($row_data[$i]->desc) != '' && $field_type != 'hidden')
			{
				$ex_field .= '<div class="userfield_tooltip">&nbsp; ' . JHTML::tooltip($row_data[$i]->desc, $row_data[$i]->name, 'tooltip.png', '', '', false) . '</div>';
			}
		}

		$ex = array();
		$ex[0] = $ex_field_title;
		$ex[1] = $ex_field;

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
	 * @deprecated  __DEPLOY_VERSION__
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
