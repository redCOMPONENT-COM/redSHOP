<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

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
	 * Extra Field Section Id for Order
	 *
	 * @var  integer
	 */
	const SECTION_ORDER = 20;

	/**
	 * User fields
	 *
	 * @var  array
	 */
	protected static $userFields = array();

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
			$type = $row_data[$i]->type;

			$data_value = $this->getSectionFieldDataList($row_data[$i]->id, $field_section, $section_id);

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
				$class = 'title="' . JText::sprintf('COM_REDSHOP_VALIDATE_EXTRA_FIELD_IS_REQUIRED', $row_data[$i]->title) . '" ';
			}

			// Default css class name
			$cssClassName[] = $row_data[$i]->class;

			$class .= ' class="' . implode(' ', $cssClassName) . '"';

			switch ($type)
			{
				case self::TYPE_TEXT:

					$text_value = '';

					if ($data_value && $data_value->data_txt)
					{
						$text_value = $data_value->data_txt;
					}

					$inputField = '<input ' . $class . ' type="text" maxlength="' . $row_data[$i]->maxlength . '" name="' . $row_data[$i]->name . '" id="' . $row_data[$i]->name . '" value="' . $text_value . '" size="32" />';
					break;

				case self::TYPE_TEXT_AREA:

					$textarea_value = '';

					if ($data_value && $data_value->data_txt)
					{
						$textarea_value = $data_value->data_txt;
					}

					$inputField = '<textarea ' . $class . '  name="' . $row_data[$i]->name . '"  id="' . $row_data[$i]->name . '" cols="' . $row_data[$i]->cols . '" rows="' . $row_data[$i]->rows . '" >' . $textarea_value . '</textarea>';
					break;

				case self::TYPE_CHECK_BOX:

					$field_chk = $this->getFieldValue($row_data[$i]->id);
					$chk_data  = @explode(",", $data_value->data_txt);

					for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
					{
						$checked = '';

						if (@in_array($field_chk[$c]->field_value, $chk_data))
						{
							$checked = ' checked="checked" ';
						}

						$inputField = '<input class="' . $row_data[$i]->class . ' ' . $class . '"   type="checkbox"  ' . $checked . ' name="' . $row_data[$i]->name . '[]" id="' . $row_data[$i]->name . "_" . $field_chk[$c]->value_id . '" value="' . $field_chk[$c]->field_value . '" />' . $field_chk[$c]->field_name . '<br />';
					}

					$inputField .= '<label for="' . $row_data[$i]->name . '[]" class="error">' . JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR') . '&nbsp;' . $row_data[$i]->title . '</label>';
					break;

				case self::TYPE_RADIO_BUTTON:

					$selectedValue = ($data_value) ? $data_value->data_txt : '';

					$inputField = JHTML::_(
						'select.radiolist',
						$this->getFieldValue($row_data[$i]->id),
						$row_data[$i]->name,
						array(
							'class' => $row_data[$i]->class
						),
						'field_value',
						'field_name',
						$selectedValue
					);
					break;
                case self::TYPE_SELECT_BOX_SINGLE:

					$field_chk = $this->getFieldValue($row_data[$i]->id);
					$chk_data  = @explode(",", $data_value->data_txt);

					$inputField = '<select class="' . $row_data[$i]->class . ' ' . $class . '"    name="' . $row_data[$i]->name . '"   id="' . $row_data[$i]->name . '">';

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

					$field_chk = $this->getFieldValue($row_data[$i]->id);
					$chk_data  = @explode(",", $data_value->data_txt);

					$inputField = '<select class="' . $row_data[$i]->class . ' ' . $class . '"   multiple size=10 name="' . $row_data[$i]->name . '[]">';

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

					if ($row_data[$i]->size > 0)
					{
						$size = $row_data[$i]->size;
					}

					$inputField = JHTML::_('redshopjquery.calendar', $date, $row_data[$i]->name, $row_data[$i]->name, $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => $size, 'maxlength' => '15'));
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
		$html = array('<ol id="ol_' . $extraFieldValues->name . '_' . $productId . '">');
		$fileNames = array();

		if (isset($userDocuments[$productId]))
		{
			foreach ($userDocuments[$productId] as $id => $userDocument)
			{
				$fileNames[] = $userDocument['fileName'];
				$sendData = array(
					'id' => $id,
					'product_id' => $productId,
					'uniqueOl' => $ajaxFlag . $extraFieldValues->name . '_' . $productId,
					'fieldName' => $extraFieldValues->name,
					'ajaxFlag' => $ajaxFlag,
					'fileName' => $userDocument['fileName'],
					'action' => JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=removeAjaxUpload'
				);

				$html[] = '<li id="uploadNameSpan' . $id . '"><span>' . $userDocument['fileName'] . '</span>&nbsp;<a href="javascript:removeAjaxUpload('
					. htmlspecialchars(json_encode($sendData)) . ');">' . JText::_('COM_REDSHOP_DELETE') . '</a></li>';
			}
		}

		$html[] = '</ol>';
		$html[] = '<input type="hidden" name="extrafields' . $productId . '[]" id="' . $ajaxFlag . $extraFieldValues->name . '_' . $productId . '" '
			. ($extraFieldValues->required ? ' required="required"' : '') . ' userfieldlbl="' . $extraFieldValues->title
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
				if ($type == self::TYPE_DATE_PICKER)
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
                    case self::TYPE_TEXT:

						$onkeyup = '';

						if (Redshop::getConfig()->get('AJAX_CART_BOX') == 0)
						{
							$onkeyup = $addtocartFormName . '.' . $row_data[$i]->name . '.value = this.value';
						}

						$ex_field .= '<div class="userfield_input"><input class="' . $row_data[$i]->class . '" type="text" maxlength="' . $row_data[$i]->maxlength . '" onkeyup="var f_value = this.value;' . $onkeyup . '" name="extrafields' . $product_id . '[]"  id="' . $row_data[$i]->name . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->title . '" value="' . $text_value . '" size="' . $row_data[$i]->size . '" /></div>';
						break;

					case self::TYPE_TEXT_AREA:

						$onkeyup = '';

						if (Redshop::getConfig()->get('AJAX_CART_BOX') == 0)
						{
							$onkeyup = $addtocartFormName . '.' . $row_data[$i]->name . '.value = this.value';
						}

						$ex_field .= '<div class="userfield_input"><textarea class="' . $row_data[$i]->class . '"  name="extrafields' . $product_id . '[]"  id="' . $row_data[$i]->name . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->title . '" cols="' . $row_data[$i]->cols . '" onkeyup=" var f_value = this.value;' . $onkeyup . '" rows="' . $row_data[$i]->rows . '" >' . $text_value . '</textarea></div>';
						break;

					case self::TYPE_CHECK_BOX:

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

					case self::TYPE_RADIO_BUTTON:

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

					case self::TYPE_SELECT_BOX_SINGLE:

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

					case self::TYPE_SELECT_BOX_MULTIPLE:

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

                    case self::TYPE_DOCUMENTS :
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
							. $this->displayUserDocuments($product_id, $row_data[$i], $ajax) . '</div>';

						break;

					case self::TYPE_IMAGE_SELECT:

						$field_chk = $this->getFieldValue($row_data[$i]->id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->name]);
						$ex_field .= '<table><tr>';

						for ($c = 0, $cn = count($field_chk); $c < $cn; $c++)
						{
							$ex_field .= '<td><div class="userfield_input"><img id="' . $row_data[$i]->name . "_" . $field_chk[$c]->value_id . '" class="pointer imgClass_' . $product_id . '" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $field_chk[$c]->field_name . '" title="' . $field_chk[$c]->field_value . '" alt="' . $field_chk[$c]->field_value . '" onclick="javascript:setProductUserFieldImage(\'' . $row_data[$i]->name . '\',\'' . $product_id . '\',\'' . $field_chk[$c]->field_value . '\',this);"/></div></td>';
						}

						$ex_field .= '</tr></table>';
						$ajax = '';

						if (Redshop::getConfig()->get('AJAX_CART_BOX') && $isatt > 0)
						{
							$ajax = 'ajax';
						}

						$ex_field .= '<input type="hidden" name="extrafields' . $product_id . '[]" id="' . $ajax . $row_data[$i]->name . '_' . $product_id . '" userfieldlbl="' . $row_data[$i]->title . '" ' . $req . '  />';
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

						$ex_field .= '<div class="userfield_input">' . JHTML::_('calendar', $text_value, 'extrafields' . $product_id . '[]', $ajax . $row_data[$i]->name . '_' . $product_id, $format = '%d-%m-%Y', array('class' => $row_data[$i]->class, 'size' => $row_data[$i]->size, 'maxlength' => $row_data[$i]->maxlength, 'required' => $req, 'userfieldlbl' => $row_data[$i]->title, 'errormsg' => '')) . '</div>';
						break;

                    case self::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS:
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
	 * @param   string  $field_section  Field section
	 * @param   int     $section_id     Section ID
	 * @param   string  $field_name     Field name
	 * @param   string  $template_data  Template content
	 * @param   int     $categorypage   Category page
	 *
	 * @return  mixed
	 *
	 * @since   1.6.0
	 *
	 * @deprecated  2.0.6  Use RedshopHelperExtrafields::extraFieldDisplay instead
	 */
	public function extra_field_display($field_section = "", $section_id = 0, $field_name = "", $template_data = "", $categorypage = 0)
	{
		return RedshopHelperExtrafields::extraFieldDisplay($field_section, $section_id, $field_name, $template_data, $categorypage);
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
			$result[] = $field->name;
		}

		return $result;
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
	public function getSectionFieldIdArray($section = self::SECTION_PRODUCT_USERFIELD, $front = 1, $published = 1, $required = 0)
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
