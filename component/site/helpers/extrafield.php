<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

/**
 * Extra Field Class
 */
class extraField
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
	const SECTION_PRODUCT =	1;

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
	 * User fields
	 *
	 * @var  array
	 */
	protected static $userFields = array();

	/**
	 * Extra field display data
	 *
	 * @var  array
	 */
	protected static $extraFieldDisplay = array();

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

	public function list_all_field($field_section = "", $section_id = 0, $uclass = '')
	{
		$row_data = $this->getSectionFieldList($field_section, 1);

		$fieldHtml = '';

		for ($i = 0, $in = count($row_data); $i < $in; $i++)
		{
			$type = $row_data[$i]->field_type;

			$data_value = $this->getSectionFieldDataList($row_data[$i]->field_id, $field_section, $section_id);

			if (!empty($data_value) && count($data_value) <= 0)
			{
				$data_value->data_txt = '';
			}

			$cssClassName = array();
			$class        = '';

			if (1 == $row_data[$i]->required)
			{
				if ($uclass == '')
				{
					$cssClassName[] = 'required';
				}
				else
				{
					$cssClassName[] = $uclass;
				}

				// Adding title to display JS validation Error message.
				$class = 'title="' . JText::sprintf('COM_REDSHOP_VALIDATE_EXTRA_FIELD_IS_REQUIRED', $row_data[$i]->field_title) . '" ';
			}

			// Default css class name
			$cssClassName[] = $row_data[$i]->field_class;

			$class .= ' class="' . implode(' ', $cssClassName) . '"';

			switch ($type)
			{
				case self::TYPE_TEXT:

					$text_value = '';

					if ($data_value && $data_value->data_txt)
					{
						$text_value = $data_value->data_txt;
					}

					$inputField = '<input ' . $class . ' type="text" maxlength="' . $row_data[$i]->field_maxlength . '" name="' . $row_data[$i]->field_name . '" id="' . $row_data[$i]->field_name . '" value="' . $text_value . '" size="32" />';
					break;

				case self::TYPE_TEXT_AREA:

					$textarea_value = '';

					if ($data_value && $data_value->data_txt)
					{
						$textarea_value = $data_value->data_txt;
					}

					$inputField = '<textarea ' . $class . '  name="' . $row_data[$i]->field_name . '"  id="' . $row_data[$i]->field_name . '" cols="' . $row_data[$i]->field_cols . '" rows="' . $row_data[$i]->field_rows . '" >' . $textarea_value . '</textarea>';
					break;

				case self::TYPE_CHECK_BOX:

					$field_chk = $this->getFieldValue($row_data[$i]->field_id);
					$chk_data  = @explode(",", $data_value->data_txt);

					for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
					{
						$checked = '';

						if (@in_array($field_chk[$c]->field_value, $chk_data))
						{
							$checked = ' checked="checked" ';
						}

						$inputField = '<input class="' . $row_data[$i]->field_class . ' ' . $class . '"   type="checkbox"  ' . $checked . ' name="' . $row_data[$i]->field_name . '[]" id="' . $row_data[$i]->field_name . "_" . $field_chk[$c]->value_id . '" value="' . $field_chk[$c]->field_value . '" />' . $field_chk[$c]->field_name . '<br />';
					}

					$inputField .= '<label for="' . $row_data[$i]->field_name . '[]" class="error">' . JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR') . '&nbsp;' . $row_data[$i]->field_title . '</label>';
					break;

				case self::TYPE_RADIO_BUTTON:

					$selectedValue = ($data_value) ? $data_value->data_txt : '';

					$inputField = JHTML::_(
						'select.radiolist',
						$this->getFieldValue($row_data[$i]->field_id),
						$row_data[$i]->field_name,
						array(
							'class' => $row_data[$i]->field_class
						),
						'field_value',
						'field_name',
						$selectedValue
					);
					break;
                case self::TYPE_SELECT_BOX_SINGLE:

					$field_chk = $this->getFieldValue($row_data[$i]->field_id);
					$chk_data  = @explode(",", $data_value->data_txt);

					$inputField = '<select class="' . $row_data[$i]->field_class . ' ' . $class . '"    name="' . $row_data[$i]->field_name . '"   id="' . $row_data[$i]->field_name . '">';

					for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
					{
						$selected = '';

						if (@in_array($field_chk[$c]->field_value, $chk_data))
						{
							$selected = ' selected="selected" ';
						}

						$inputField .= '<option value="' . $field_chk[$c]->field_value . '" ' . $selected . ' >' . $field_chk[$c]->field_value . '</option>';
					}

					$inputField .= '</select>';
					break;

                case self::TYPE_SELECT_BOX_MULTIPLE:

					$field_chk = $this->getFieldValue($row_data[$i]->field_id);
					$chk_data  = @explode(",", $data_value->data_txt);

					$inputField = '<select class="' . $row_data[$i]->field_class . ' ' . $class . '"   multiple size=10 name="' . $row_data[$i]->field_name . '[]">';

					for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
					{
						$selected = '';

						if (@in_array(urlencode($field_chk[$c]->field_value), $chk_data))
						{
							$selected = ' selected="selected" ';
						}

						$inputField .= '<option value="' . urlencode($field_chk[$c]->field_value) . '" ' . $selected . ' >' . $field_chk[$c]->field_name . '</option>';
					}

					$inputField .= '</select>';
					break;

                case self::TYPE_DATE_PICKER:

					$date = date("d-m-Y", time());
					$size = '20';

					if ($data_value && $data_value->data_txt)
					{
						$date = date("d-m-Y", strtotime($data_value->data_txt));
					}

					if ($row_data[$i]->field_size > 0)
					{
						$size = $row_data[$i]->field_size;
					}

					$inputField = JHTML::_('redshopjquery.calendar', $date, $row_data[$i]->field_name, $row_data[$i]->field_name, $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => $size, 'maxlength' => '15'));
					break;
			}

			$fieldHtml .= RedshopLayoutHelper::render(
							'fields.html',
							array(
								'fieldHandle' => $row_data[$i],
								'inputField'  => $inputField
							)
						);

		}

		return $fieldHtml;
	}

	/**
	 * Display User Documents
	 *
	 * @param   int     $productId         Product id
	 * @param   object  $extraFieldValues  Extra field name
	 * @param   string  $ajaxFlag          Ajax flag
	 *
	 * @return  string
	 */
	public function displayUserDocuments($productId, $extraFieldValues, $ajaxFlag = '')
	{
		$session = JFactory::getSession();
		$userDocuments = $session->get('userDocument', array());
		$html = array('<ol id="ol_' . $extraFieldValues->field_name . '_' . $productId . '">');
		$fileNames = array();

		if (isset($userDocuments[$productId]))
		{
			foreach ($userDocuments[$productId] as $id => $userDocument)
			{
				$fileNames[] = $userDocument['fileName'];
				$sendData = array(
					'id' => $id,
					'product_id' => $productId,
					'uniqueOl' => $ajaxFlag . $extraFieldValues->field_name . '_' . $productId,
					'fieldName' => $extraFieldValues->field_name,
					'ajaxFlag' => $ajaxFlag,
					'fileName' => $userDocument['fileName'],
					'action' => JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=removeAjaxUpload'
				);

				$html[] = '<li id="uploadNameSpan' . $id . '"><span>' . $userDocument['fileName'] . '</span>&nbsp;<a href="javascript:removeAjaxUpload('
					. htmlspecialchars(json_encode($sendData)) . ');">' . JText::_('COM_REDSHOP_DELETE') . '</a></li>';
			}
		}

		$html[] = '</ol>';
		$html[] = '<input type="hidden" name="extrafields' . $productId . '[]" id="' . $ajaxFlag . $extraFieldValues->field_name . '_' . $productId . '" '
			. ($extraFieldValues->required ? ' required="required"' : '') . ' userfieldlbl="' . $extraFieldValues->field_title
			. '" value="' . implode(',', $fileNames) . '" />';

		return implode('', $html);
	}

	public function list_all_user_fields($field_section = "", $section_id = self::SECTION_PRODUCT_USERFIELD, $field_type = '', $idx = 'NULL', $isatt = 0, $product_id, $mywish = "", $addwish = 0)
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
				->where('field_section = ' . $db->quote($section_id))
				->where('field_name = ' . $db->quote($field_section))
				->where('published = 1')
				->where('field_show_in_front = 1')
				->order('ordering');
			$db->setQuery($query);
			self::$userFields[$section_id . '_' . $field_section] = $db->loadObjectlist();
		}

		$row_data       = self::$userFields[$section_id . '_' . $field_section];
		$ex_field       = '';
		$ex_field_title = '';

		for ($i = 0, $in = count($row_data); $i < $in; $i++)
		{
			$type = $row_data[$i]->field_type;
			$asterisk = $row_data[$i]->required > 0 ? '* ' : '';

			if ($field_type != 'hidden')
			{
				$ex_field_title .= '<div class="userfield_label">' . $asterisk . $row_data[$i]->field_title . '</div>';
			}

			$text_value = '';

			if ($addwish == 1)
			{
				$text_value = $mywish;
			}

			if ($cart && isset($cart[$idx][$row_data[$i]->field_name]))
			{
				if ($type == self::TYPE_DATE_PICKER)
				{
					$text_value = date("d-m-Y", strtotime($cart[$idx][$row_data[$i]->field_name]));
				}
				else
				{
					$text_value = $cart[$idx][$row_data[$i]->field_name];
				}
			}

			if ($field_type == 'hidden')
			{
				$value = '';

				if ($type == self::TYPE_DOCUMENTS)
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

				$ex_field .= '<input type="hidden" name="' . $row_data[$i]->field_name . '"  id="' . $row_data[$i]->field_name . '" value="' . $value . '"/>';
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
                    case self::TYPE_TEXT:

						$onkeyup = '';

						if (Redshop::getConfig()->get('AJAX_CART_BOX') == 0)
						{
							$onkeyup = $addtocartFormName . '.' . $row_data[$i]->field_name . '.value = this.value';
						}

						$ex_field .= '<div class="userfield_input"><input class="' . $row_data[$i]->field_class . '" type="text" maxlength="' . $row_data[$i]->field_maxlength . '" onkeyup="var f_value = this.value;' . $onkeyup . '" name="extrafields' . $product_id . '[]"  id="' . $row_data[$i]->field_name . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->field_title . '" value="' . $text_value . '" size="' . $row_data[$i]->field_size . '" /></div>';
						break;

					case self::TYPE_TEXT_AREA:

						$onkeyup = '';

						if (Redshop::getConfig()->get('AJAX_CART_BOX') == 0)
						{
							$onkeyup = $addtocartFormName . '.' . $row_data[$i]->field_name . '.value = this.value';
						}

						$ex_field .= '<div class="userfield_input"><textarea class="' . $row_data[$i]->field_class . '"  name="extrafields' . $product_id . '[]"  id="' . $row_data[$i]->field_name . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->field_title . '" cols="' . $row_data[$i]->field_cols . '" onkeyup=" var f_value = this.value;' . $onkeyup . '" rows="' . $row_data[$i]->field_rows . '" >' . $text_value . '</textarea></div>';
						break;

					case self::TYPE_CHECK_BOX:

						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							$checked = '';

							if (@in_array($field_chk[$c]->field_value, $chk_data))
							{
								$checked = ' checked="checked" ';
							}

							$ex_field .= '<div class="userfield_input"><input  class="' . $row_data[$i]->field_class . '" type="checkbox"  ' . $checked . ' name="extrafields' . $product_id . '[]" id="' . $row_data[$i]->field_name . "_" . $field_chk[$c]->value_id . '" userfieldlbl="' . $row_data[$i]->field_title . '" value="' . $field_chk[$c]->field_value . '" ' . $req . ' />' . $field_chk[$c]->field_value . '</div>';
						}
						break;

					case self::TYPE_RADIO_BUTTON:

						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							$checked = '';

							if (@in_array($field_chk[$c]->field_value, $chk_data))
							{
								$checked = ' checked="checked" ';
							}

							$ex_field .= '<div class="userfield_input"><input class="' . $row_data[$i]->field_class . '" type="radio" ' . $checked . ' name="extrafields' . $product_id . '[]" userfieldlbl="' . $row_data[$i]->field_title . '"  id="' . $row_data[$i]->field_name . "_" . $field_chk[$c]->value_id . '" value="' . $field_chk[$c]->field_value . '" ' . $req . ' />' . $field_chk[$c]->field_name . '</div>';
						}
						break;

					case self::TYPE_SELECT_BOX_SINGLE:

						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);
						$ex_field .= '<div class="userfield_input"><select name="extrafields' . $product_id . '[]" ' . $req . ' id="' . $row_data[$i]->field_name . '" userfieldlbl="' . $row_data[$i]->field_title . '">';
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

					case self::TYPE_SELECT_BOX_MULTIPLE:

						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);
						$ex_field .= '<div class="userfield_input"><select multiple="multiple" size=10 name="extrafields' . $product_id . '[]" ' . $req . ' id="' . $row_data[$i]->field_name . '" userfieldlbl="' . $row_data[$i]->field_title . '">';

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

                    case self::TYPE_DOCUMENTS :
						// File Upload
						JHtml::_('redshopjquery.framework');
						JHtml::script('com_redshop/ajaxupload.js', false, true);

						$ajax = '';
						$unique = $row_data[$i]->field_name . '_' . $product_id;

						if ($isatt > 0)
						{
							$ajax = 'ajax';
							$unique = $row_data[$i]->field_name;
						}

						$ex_field .= '<div class="userfield_input">'
							. '<input type="button" class="' . $row_data[$i]->field_class . '" value="' . JText::_('COM_REDSHOP_UPLOAD') . '" id="file'
							. $ajax . $unique . '" />';
						$ex_field .= '<script>
							new AjaxUpload(
								"file' . $ajax . $unique . '",
								{
									action:"' . JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",
									data :{
										mname:"file' . $ajax . $row_data[$i]->field_name . '",
										product_id:"' . $product_id . '",
										uniqueOl:"' . $unique . '",
										fieldName: "' . $row_data[$i]->field_name . '",
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
										jQuery("#' . $row_data[$i]->field_name . '").val(uploadfiles);
									}
								}
							);
						</script>';

						$ex_field .= '<p>' . JText::_('COM_REDSHOP_UPLOADED_FILE') . ':</p>'
							. $this->displayUserDocuments($product_id, $row_data[$i], $ajax) . '</div>';

						break;

					case self::TYPE_IMAGE_SELECT:

						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);
						$ex_field .= '<table><tr>';

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							$ex_field .= '<td><div class="userfield_input"><img id="' . $row_data[$i]->field_name . "_" . $field_chk[$c]->value_id . '" class="pointer imgClass_' . $product_id . '" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $field_chk[$c]->field_name . '" title="' . $field_chk[$c]->field_value . '" alt="' . $field_chk[$c]->field_value . '" onclick="javascript:setProductUserFieldImage(\'' . $row_data[$i]->field_name . '\',\'' . $product_id . '\',\'' . $field_chk[$c]->field_value . '\',this);"/></div></td>';
						}

						$ex_field .= '</tr></table>';
						$ajax = '';

						if (Redshop::getConfig()->get('AJAX_CART_BOX') && $isatt > 0)
						{
							$ajax = 'ajax';
						}

						$ex_field .= '<input type="hidden" name="extrafields' . $product_id . '[]" id="' . $ajax . $row_data[$i]->field_name . '_' . $product_id . '" userfieldlbl="' . $row_data[$i]->field_title . '" ' . $req . '  />';
						break;

					case self::TYPE_DATE_PICKER:

						$ajax = '';
						$req = $row_data[$i]->required;

						if (Redshop::getConfig()->get('AJAX_CART_BOX') && $isatt == 0)
						{
							$req = 0;
						}

						if (Redshop::getConfig()->get('AJAX_CART_BOX') && $isatt > 0)
						{
							$ajax = 'ajax';
						}

						$ex_field .= '<div class="userfield_input">' . JHTML::_('calendar', $text_value, 'extrafields' . $product_id . '[]', $ajax . $row_data[$i]->field_name . '_' . $product_id, $format = '%d-%m-%Y', array('class' => $row_data[$i]->field_class, 'size' => $row_data[$i]->field_size, 'maxlength' => $row_data[$i]->field_maxlength, 'required' => $req, 'userfieldlbl' => $row_data[$i]->field_title, 'errormsg' => '')) . '</div>';
						break;

                    case self::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS:
						$field_chk = $this->getSectionFieldDataList($row_data[$i]->field_id, 12, $product_id);

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
								$ex_field .= '' . $asterisk . $row_data[$i]->field_title . ' : <select name="extrafields' . $product_id . '[]" id="' . $row_data[$i]->field_name . '" userfieldlbl="' . $row_data[$i]->field_title . '" ' . $req . ' >';
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

			if (trim($row_data[$i]->field_desc) != '' && $field_type != 'hidden')
			{
				$ex_field .= '<div class="userfield_tooltip">&nbsp; ' . JHTML::tooltip($row_data[$i]->field_desc, $row_data[$i]->field_name, 'tooltip.png', '', '', false) . '</div>';
			}
		}

		$ex = array();
		$ex[0] = $ex_field_title;
		$ex[1] = $ex_field;

		return $ex;
	}

	public function extra_field_display($field_section = "", $section_id = 0, $field_name = "", $template_data = "", $categorypage = 0)
	{
		$db = JFactory::getDbo();

		$redTemplate = Redtemplate::getInstance();
		$url         = JURI::base();

		if (!isset(self::$extraFieldDisplay[$field_section]) || !array_key_exists($field_name, self::$extraFieldDisplay[$field_section]))
		{
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_fields'))
				->where('field_section = ' . $db->quote($field_section));

			if ($field_name != "")
			{
				$query->where('field_name IN (' . $field_name . ')');
			}

			$db->setQuery($query);

			if (!isset(self::$extraFieldDisplay[$field_section]))
			{
				self::$extraFieldDisplay[$field_section] = array();
			}

			self::$extraFieldDisplay[$field_section][$field_name] = $db->loadObjectlist();
		}

		$row_data = self::$extraFieldDisplay[$field_section][$field_name];

		for ($i = 0, $in = count($row_data); $i < $in; $i++)
		{
			$type                = $row_data[$i]->field_type;
			$published           = $row_data[$i]->published;
			$field_show_in_front = $row_data[$i]->field_show_in_front;

			$data_value = $this->getSectionFieldDataList($row_data[$i]->field_id, $field_section, $section_id);

			if ($categorypage == 1)
			{
				$search_lbl = "{producttag:" . $row_data[$i]->field_name . "_lbl}";
				$search     = "{producttag:" . $row_data[$i]->field_name . "}";
			}
			else
			{
				$search_lbl = "{" . $row_data[$i]->field_name . "_lbl}";
				$search     = "{" . $row_data[$i]->field_name . "}";
			}

			if (count($data_value) != 0
				&& $published
				&& ($field_show_in_front || JFactory::getApplication()->isAdmin()))
			{
				$displayvalue = '';

				switch ($type)
				{
                    case self::TYPE_TEXT:
					case self::TYPE_WYSIWYG:
					case self::TYPE_DATE_PICKER:
					case self::TYPE_SELECT_BOX_SINGLE:

						$displayvalue = $data_value->data_txt;
						break;

					case self::TYPE_TEXT_AREA:

						$displayvalue = htmlspecialchars($data_value->data_txt);
						break;

					case self::TYPE_CHECK_BOX:
					case self::TYPE_RADIO_BUTTON:
					case self::TYPE_SELECT_BOX_MULTIPLE:

						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $data_value->data_txt);
						$tmparr    = array();

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							if (@in_array(urlencode($field_chk[$c]->field_value), $chk_data))
							{
								$tmparr[] = urldecode($field_chk[$c]->field_value);
							}
						}

						$displayvalue = urldecode(implode('<br>', $tmparr));
						break;

					case self::TYPE_SELECT_COUNTRY_BOX:

						$displayvalue = "";

						if ($data_value->data_txt != "")
						{
							$q = "SELECT country_name FROM #__redshop_country "
								. "WHERE id = " . (int) $data_value->data_txt;
							$db->setQuery($q);
							$field_chk    = $db->loadObject();
							$displayvalue = $field_chk->country_name;
						}
						break;
					case self::TYPE_DOCUMENTS :

						// Support Legacy string.
						if (preg_match('/\n/', $data_value->data_txt))
						{
							$document_explode = explode("\n", $data_value->data_txt);
							$document_value   = array($document_explode[0] => $document_explode[1]);
						}
						else
						{
							// Support for multiple file upload using JSON for better string handling
							$document_value = json_decode($data_value->data_txt);
						}

						if (count($document_value) > 0)
						{
							$displayvalue = "";

							foreach ($document_value as $document_title => $filename)
							{
								$link     = REDSHOP_FRONT_DOCUMENT_ABSPATH . 'extrafields/' . $filename;
								$link_phy = REDSHOP_FRONT_DOCUMENT_RELPATH . 'extrafields/' . $filename;

								if (is_file($link_phy))
								{
									$displayvalue .= "<a href=\"$link\" target='_blank' >$document_title</a>";
								}
							}
						}
						break;
					case self::TYPE_IMAGE_SELECT :
						// Image
					case self::TYPE_IMAGE_WITH_LINK :
						$document_value = $this->getFieldValue($row_data[$i]->field_id);

						$tmp_image_hover = array();
						$tmp_image_link  = array();
						$chk_data        = @explode(",", $data_value->data_txt);

						if ($data_value->alt_text)
						{
							$tmp_image_hover = explode(',,,,,', $data_value->alt_text);
						}

						if ($data_value->image_link)
						{
							$tmp_image_link = @explode(',,,,,', $data_value->image_link);
						}

						$chk_data    = @explode(",", $data_value->data_txt);
						$image_link  = array();
						$image_hover = array();

						for ($ch = 0, $countChkData = count($chk_data); $ch < $countChkData; $ch++)
						{
							$image_link[$chk_data[$ch]]  = isset($tmp_image_link[$ch]) ? $tmp_image_link[$ch] : '';
							$image_hover[$chk_data[$ch]] = isset($tmp_image_hover[$ch]) ? $tmp_image_hover[$ch] : '';
						}

						$displayvalue = '';

						for ($c = 0, $cn = count($document_value); $c < $cn; $c++)
						{
							if (@in_array($document_value[$c]->value_id, $chk_data))
							{
								$filename = $document_value[$c]->field_name;

								$link = REDSHOP_FRONT_IMAGES_ABSPATH . "extrafield/" . $filename;

								$str_image_link = $image_link[$document_value[$c]->value_id];

								if ($str_image_link)
								{
									$displayvalue .= "<a href='" . $str_image_link
										. "' class='imgtooltip' ><img src='" . $link . "' title='" . $document_value[$c]->field_value . "' alt='" . $document_value[$c]->field_value . "' /><span><div class='spnheader'>"
										. $row_data[$i]->field_title . "</div><div class='spnalttext'>"
										. $image_hover[$document_value[$c]->value_id] . "</div></span></a>";
								}
								else
								{
									$displayvalue .= "<a class='imgtooltip'><img src='" . $link . "' title='" . $document_value[$c]->field_value . "' alt='" . $document_value[$c]->field_value . "' /><span><div class='spnheader'>"
										. $row_data[$i]->field_title . "</div><div class='spnalttext'>"
										. $image_hover[$document_value[$c]->value_id] . "</div></span></a>";
								}
							}
						}

						break;
					default :
						break;
				}

				$displaytitle  = $data_value->data_txt != "" ? $data_value->field_title : "";
				$displayvalue  = $redTemplate->parseredSHOPplugin($displayvalue);
				$template_data = str_replace($search_lbl, JText::_($displaytitle), $template_data);
				$template_data = str_replace($search, $displayvalue, $template_data);
			}
			else
			{
				$template_data = str_replace($search_lbl, "", $template_data);
				$template_data = str_replace($search, "", $template_data);
			}
		}

		return $template_data;
	}

	public function getFieldValue($id)
	{
		$db = JFactory::getDbo();

		$q = "SELECT * FROM #__redshop_fields_value "
			. "WHERE field_id=" . (int) $id . " "
			. "ORDER BY value_id ASC ";
		$db->setQuery($q);
		$list = $db->loadObjectlist();

		return $list;
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
	 * @deprecated  2.0.3  Use RedshopHelperExtrafields::getSectionFieldList() instead
	 */
	public function getSectionFieldList($section = self::SECTION_PRODUCT_USERFIELD, $front = 1, $published = 1, $required = 0)
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
	 */
	public function getSectionFieldNameArray($section = self::SECTION_PRODUCT_USERFIELD, $front = 1, $published = 1, $required = 0)
	{
		$fields = RedshopHelperExtrafields::getSectionFieldList($section, $front, $published, $required);

		if (empty($fields))
		{
			return array();
		}

		$result = array();

		foreach ($fields as $field)
		{
			$result[] = $field->field_name;
		}

		return $result;
	}

	public function getSectionFieldIdArray($section = self::SECTION_PRODUCT_USERFIELD, $front = 1, $published = 1, $required = 0)
	{
		JFactory::getDbo();

		$and = "";

		if ($published == 1)
		{
			$and .= "AND published=" . (int) $published . " ";
		}

		if ($required == 1)
		{
			$and .= "AND required=" . (int) $required . " ";
		}

		$query = "SELECT field_id, field_name FROM #__redshop_fields "
			. "WHERE field_section = " . $db->quote($section) . " "
			. "AND field_show_in_front = " . (int) $front . " "
			. $and;
		$db->setQuery($query);
		$list = $db->loadObjectList();

		return $list;
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
