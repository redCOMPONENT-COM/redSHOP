<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

class extraField
{
	/*
	 * field_type 	=   1 :- Text Field
	 * 					2 :- Text Area
	 * 					3 :- Check Box
	 * 					4 :- Radio Button
	 * 					5 :- Select Box (Single select)
	 * 					6 :- Select Box (Multiple select)
	 * 					7 :- Select country box
	 * 					8 :- Wysiwyg
	 * 					9 :- Media
	 * 					10:- Documents
	 * 					11:- Image Select
	 * 					12:- Date Picker
	 *
	 * field_section = 	1 :- Product
	 * 					2 :- Category
	 * 					3 :- Form
	 * 					4 :- E-mail
	 * 					5 :- Confirmation
	 * 					6 :- Userinformations
	 * 					7 :- Customer Address
	 * 					8 :- Company Address
	 * 					9 :- Color sample
	 * 				   10 :- Manufacturer
	 * 				   11 :- Shipping
	 * 				   12 :- Product_UserField
	 *
	 */
	public $_data         = null;

	public $_table_prefix = null;

	public $_db           = null;

	public function __construct()
	{
		$this->_db = JFactory::getDbo();
		$this->_table_prefix = '#__redshop_';
	}

	public function list_all_field($field_section = "", $section_id = 0, $uclass = '')
	{
		$row_data = $this->getSectionFieldList($field_section, 1);

		$ex_field = '';

		for ($i = 0; $i < count($row_data); $i++)
		{
			$type = $row_data[$i]->field_type;

			$ex_field .= '<tr><td width="100" valign="top" align="right"><label>' . JText::_($row_data[$i]->field_title) . ': </label></td><td>';

			$data_value = $this->getSectionFieldDataList($row_data[$i]->field_id, $field_section, $section_id);

			if (count($data_value) <= 0)
			{
				$data_value->data_txt = '';
			}

			$astrict = $row_data[$i]->required ? "<span class='required'>*</span>" : "";

			if ($row_data[$i]->required == 1)
			{
				if ($uclass == '')
				{
					$class = 'class="required"';
				}
				else
				{
					$class = 'class="' . $uclass . '"';
				}

				$span_class = "<span class='required'>*</span>";
			}
			else
			{
				$class      = '';
				$span_class = '';
			}

			switch ($type)
			{
				case 1:
					// 1 :- Text Field
					$text_value = $data_value->data_txt;
					$ex_field .= '<input ' . $class . ' type="text" maxlength="' . $row_data[$i]->field_maxlength . '" name="' . $row_data[$i]->field_name . '" id="' . $row_data[$i]->field_name . '" value="' . $text_value . '" size="32" />';
					break;
				case 2:
					// 2 :- Text Area
					$textarea_value = '';

					if ($data_value && $data_value->data_txt)
					{
						$textarea_value = $data_value->data_txt;
					}

					$ex_field .= '<textarea ' . $class . '  name="' . $row_data[$i]->field_name . '"  id="' . $row_data[$i]->field_name . '" cols="' . $row_data[$i]->field_cols . '" rows="' . $row_data[$i]->field_rows . '" >' . $textarea_value . '</textarea>';
					break;
				case 3:
					// 3 :- Check Box
					$field_chk = $this->getFieldValue($row_data[$i]->field_id);
					$chk_data  = @explode(",", $data_value->data_txt);

					if ($row_data[$i]->required == 1)
					{
						$class = 'required';
					}
					else
					{
						$class = '';
					}

					for ($c = 0; $c < count($field_chk); $c++)
					{
						$checked = '';

						if (@in_array($field_chk[$c]->field_value, $chk_data))
						{
							$checked = ' checked="checked" ';
						}

						$ex_field .= '<input class="' . $row_data[$i]->field_class . ' ' . $class . '"   type="checkbox"  ' . $checked . ' name="' . $row_data[$i]->field_name . '[]" id="' . $row_data[$i]->field_name . "_" . $field_chk[$c]->value_id . '" value="' . $field_chk[$c]->field_value . '" />' . $field_chk[$c]->field_name . '<br />';
					}
					$ex_field .= '<label for="' . $row_data[$i]->field_name . '[]" class="error">' . JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR') . '&nbsp;' . $row_data[$i]->field_title . '</label>';
					break;
				case 4:
					// 4 :- Radio Button
					$field_chk = $this->getFieldValue($row_data[$i]->field_id);
					$chk_data  = @explode(",", $data_value->data_txt);

					if ($row_data[$i]->required == 1)
					{
						$class = 'required';
					}
					else
					{
						$class = '';
					}

					for ($c = 0; $c < count($field_chk); $c++)
					{
						$checked = '';

						if (@in_array($field_chk[$c]->field_value, $chk_data))
						{
							$checked = ' checked="checked" ';
						}

						$ex_field .= '<input class="' . $row_data[$i]->field_class . ' ' . $class . '"   type="radio" ' . $checked . '  name="' . $row_data[$i]->field_name . '"  id="' . $row_data[$i]->field_name . "_" . $field_chk[$c]->value_id . '" value="' . $field_chk[$c]->field_value . '" />' . $field_chk[$c]->field_name . '<br />';
					}

					$ex_field .= '<label for="' . $row_data[$i]->field_name . '" class="error">' . JText::_('COM_REDSHOP_PLEASE_SELECT_YOUR') . '&nbsp;' . $row_data[$i]->field_title . '</label>';
					break;
				case 5:
					// 5 :-Select Box (Single select)
					$field_chk = $this->getFieldValue($row_data[$i]->field_id);
					$chk_data  = @explode(",", $data_value->data_txt);

					if ($row_data[$i]->required == 1)
					{
						$class = 'required';
					}
					else
					{
						$class = '';
					}

					$ex_field .= '<select class="' . $row_data[$i]->field_class . ' ' . $class . '"    name="' . $row_data[$i]->field_name . '"   id="' . $row_data[$i]->field_name . '">';

					for ($c = 0; $c < count($field_chk); $c++)
					{
						$selected = '';

						if (@in_array($field_chk[$c]->field_value, $chk_data))
						{
							$selected = ' selected="selected" ';
						}

						$ex_field .= '<option value="' . $field_chk[$c]->field_value . '" ' . $selected . ' >' . $field_chk[$c]->field_value . '</option>';
					}

					$ex_field .= '</select>';
					break;

				case 6:
					// 6 :- Select Box (Multiple select)
					$field_chk = $this->getFieldValue($row_data[$i]->field_id);
					$chk_data  = @explode(",", $data_value->data_txt);

					if ($row_data[$i]->required == 1)
					{
						$class = 'required';
					}
					else
					{
						$class = '';
					}

					$ex_field .= '<select class="' . $row_data[$i]->field_class . ' ' . $class . '"   multiple size=10 name="' . $row_data[$i]->field_name . '[]">';

					for ($c = 0; $c < count($field_chk); $c++)
					{
						$selected = '';

						if (@in_array(urlencode($field_chk[$c]->field_value), $chk_data))
						{
							$selected = ' selected="selected" ';
						}

						$ex_field .= '<option value="' . urlencode($field_chk[$c]->field_value) . '" ' . $selected . ' >' . $field_chk[$c]->field_name . '</option>';
					}

					$ex_field .= '</select>';
					break;

				case 12:
					// 12 :- Date Picker
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

					$ex_field .= JHTML::_('calendar', $date, $row_data[$i]->field_name, $row_data[$i]->field_name, $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => $size, 'maxlength' => '15'));
					break;
			}

			if (trim($row_data[$i]->field_desc) == '')
			{
				$ex_field .= '</td><td valign="top">' . $span_class;
			}
			else
			{
				$ex_field .= '</td><td valign="top">' . $span_class . '&nbsp; ' . JHTML::tooltip($row_data[$i]->field_desc, '', 'tooltip.png', '', '', false);
			}

			$ex_field .= '</td></tr>';
		}

		return $ex_field;
	}

	public function list_all_user_fields($field_section = "", $section_id = 12, $field_type = '', $idx = 'NULL', $isatt = 0, $product_id, $mywish = "", $addwish = 0)
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$url     = JURI::base();

		$preprefix = "";

		if ($isatt == 1)
		{
			$preprefix = "ajax_";
		}

		$addtocartFormName = 'addtocart_' . $preprefix . 'prd_' . $product_id;

		$document = JFactory::getDocument();
		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);

		$q = "SELECT * FROM " . $this->_table_prefix . "fields "
			. "WHERE field_section='" . $section_id . "' "
			. "AND field_name='" . $field_section . "' "
			. "AND published=1 "
			. "AND field_show_in_front=1 "
			. "ORDER BY ordering ";
		$this->_db->setQuery($q);

		$row_data       = $this->_db->loadObjectlist();
		$ex_field       = '';
		$ex_field_title = '';

		for ($i = 0; $i < count($row_data); $i++)
		{
			$type = $row_data[$i]->field_type;
			$asterisk = $row_data[$i]->required > 0 ? '* ' : '';

			if ($field_type != 'hidden')
			{
				$ex_field_title .= '<div class="userfield_label">' . $asterisk . $row_data[$i]->field_title . '</div>';
			}
			$data_value = $this->getSectionFieldDataList($row_data[$i]->field_id, $field_section, $section_id);

			$text_value = '';

			if ($addwish == 1)
			{
				$text_value = $mywish;
			}

			if ($cart && isset($cart[$idx][$row_data[$i]->field_name]))
			{
				if ($type == 12)
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
				$ex_field .= '<input type="hidden" name="' . $row_data[$i]->field_name . '"  id="' . $row_data[$i]->field_name . '" value=""/>';
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
					case 1:
						// 1 :- Text Field
						$onkeyup = '';

						if (AJAX_CART_BOX == 0)
						{
							$onkeyup = $addtocartFormName . '.' . $row_data[$i]->field_name . '.value = this.value';
						}

						$ex_field .= '<div class="userfield_input"><input class="' . $row_data[$i]->field_class . '" type="text" maxlength="' . $row_data[$i]->field_maxlength . '" onkeyup="var f_value = this.value;' . $onkeyup . '" name="extrafields' . $product_id . '[]"  id="' . $row_data[$i]->field_name . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->field_title . '" value="' . $text_value . '" size="' . $row_data[$i]->field_size . '" /></div>';
						break;

					case 2:
						// 2 :- Text Area
						$onkeyup = '';

						if (AJAX_CART_BOX == 0)
						{
							$onkeyup = $addtocartFormName . '.' . $row_data[$i]->field_name . '.value = this.value';
						}

						$ex_field .= '<div class="userfield_input"><textarea class="' . $row_data[$i]->field_class . '"  name="extrafields' . $product_id . '[]"  id="' . $row_data[$i]->field_name . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->field_title . '" cols="' . $row_data[$i]->field_cols . '" onkeyup=" var f_value = this.value;' . $onkeyup . '" rows="' . $row_data[$i]->field_rows . '" >' . $text_value . '</textarea></div>';
						break;

					case 3:
						// 3 :- Check Box
						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);

						for ($c = 0; $c < count($field_chk); $c++)
						{
							$checked = '';

							if (@in_array($field_chk[$c]->field_value, $chk_data))
							{
								$checked = ' checked="checked" ';
							}

							$ex_field .= '<div class="userfield_input"><input  class="' . $row_data[$i]->field_class . '" type="checkbox"  ' . $checked . ' name="extrafields' . $product_id . '[]" id="' . $row_data[$i]->field_name . "_" . $field_chk[$c]->value_id . '" userfieldlbl="' . $row_data[$i]->field_title . '" value="' . $field_chk[$c]->field_value . '" ' . $req . ' />' . $field_chk[$c]->field_value . '</div>';
						}
						break;

					case 4:
						// 4 :- Radio Button
						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);

						for ($c = 0; $c < count($field_chk); $c++)
						{
							$checked = '';

							if (@in_array($field_chk[$c]->field_value, $chk_data))
							{
								$checked = ' checked="checked" ';
							}

							$ex_field .= '<div class="userfield_input"><input class="' . $row_data[$i]->field_class . '" type="radio" ' . $checked . ' name="extrafields' . $product_id . '[]" userfieldlbl="' . $row_data[$i]->field_title . '"  id="' . $row_data[$i]->field_name . "_" . $field_chk[$c]->value_id . '" value="' . $field_chk[$c]->field_value . '" ' . $req . ' />' . $field_chk[$c]->field_value . '</div>';
						}
						break;

					case 5:
						// 5 :-Select Box (Single select)
						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);
						$ex_field .= '<div class="userfield_input"><select name="extrafields' . $product_id . '[]" ' . $req . ' id="' . $row_data[$i]->field_name . '" userfieldlbl="' . $row_data[$i]->field_title . '">';
						$ex_field .= '<option value="">' . JText::_('COM_REDSHOP_SELECT') . '</option>';

						for ($c = 0; $c < count($field_chk); $c++)
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

					case 6:
						// 6 :- Select Box (Multiple select)
						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);
						$ex_field .= '<div class="userfield_input"><select multiple="multiple" size=10 name="extrafields' . $product_id . '[]" ' . $req . ' id="' . $row_data[$i]->field_name . '" userfieldlbl="' . $row_data[$i]->field_title . '">';

						for ($c = 0; $c < count($field_chk); $c++)
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

					case 10 :
						// File Upload
						JHTML::Script('jquery-1.js', 'components/com_redshop/assets/js/', false);
						JHTML::Script('ajaxupload.js', 'components/com_redshop/assets/js/', false);
						$ajax = "";

						if (AJAX_CART_BOX)
						{
							$ex_field .= '<script>jQuery.noConflict();</script>';

							if ($isatt > 0)
							{
								$ajax = "ajax";
								$ex_field .= '<div class="userfield_input"><input type="button" class="' . $row_data[$i]->field_class . '" value="' . JText::_('COM_REDSHOP_UPLOAD') . '" id="file' . $ajax . $row_data[$i]->field_name . '" onClick=\'javascript:new AjaxUpload("file' . $ajax . $row_data[$i]->field_name . '",{action:"' . JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",data :{mname:"file' . $ajax . $row_data[$i]->field_name . '"}, name:"file' . $ajax . $row_data[$i]->field_name . '",onSubmit : function(file , ext){jQuery("file' . $ajax . $row_data[$i]->field_name . '").text("' . JText::_('COM_REDSHOP_UPLOADING') . '" + file);this.disable();}, onComplete :function(file,response){jQuery("<li></li>").appendTo(jQuery("#ol_' . $ajax . $row_data[$i]->field_name . '")).text(response);var uploadfiles = jQuery("#ol_' . $ajax . $row_data[$i]->field_name . ' li").map(function() {return jQuery(this).text();}).get().join(",");jQuery("#' . $ajax . $row_data[$i]->field_name . '_' . $product_id . '").val(uploadfiles);this.enable();jQuery("#' . $row_data[$i]->field_name . '").val(uploadfiles);}});\' />';
							}
							else
							{
								$ex_field .= '<div class="userfield_input"><input type="button" class="' . $row_data[$i]->field_class . '" value="' . JText::_('COM_REDSHOP_UPLOAD') . '" id="file' . $ajax . $row_data[$i]->field_name . '" />';
								$ex_field .= '<script>
								new AjaxUpload("file' . $ajax . $row_data[$i]->field_name . '",{action:"' . JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",data :{mname:"file' . $ajax . $row_data[$i]->field_name . '"}, name:"file' . $ajax . $row_data[$i]->field_name . '",onSubmit : function(file , ext){jQuery("file' . $ajax . $row_data[$i]->field_name . '").text("' . JText::_('COM_REDSHOP_UPLOADING') . '" + file);this.disable();}, onComplete :function(file,response){jQuery("<li></li>").appendTo(jQuery("#ol_' . $ajax . $row_data[$i]->field_name . '")).text(response);var uploadfiles = jQuery("#ol_' . $ajax . $row_data[$i]->field_name . ' li").map(function() {return jQuery(this).text();}).get().join(",");jQuery("#' . $ajax . $row_data[$i]->field_name . '_' . $product_id . '").val(uploadfiles);this.enable();jQuery("#' . $row_data[$i]->field_name . '").val(uploadfiles);}});
								</script>';
							}

							$ex_field .= '<p>' . JText::_('COM_REDSHOP_UPLOADED_FILE') . ':<ol id="ol_' . $ajax . $row_data[$i]->field_name . '"></ol></p></div>';
							$ex_field .= '<input type="hidden" name="extrafields' . $product_id . '[]" id="' . $ajax . $row_data[$i]->field_name . '_' . $product_id . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->field_title . '" />';
						}
						else
						{
							$ex_field .= '<div class="userfield_input"><input class="' . $row_data[$i]->field_class . '" type="button" value="' . JText::_('COM_REDSHOP_UPLOAD') . '" name="file' . $row_data[$i]->field_name . '_' . $product_id . '"  id="file' . $row_data[$i]->field_name . '_' . $product_id . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->field_title . '" size="' . $row_data[$i]->field_size . '" /><p>' . JText::_('COM_REDSHOP_UPLOADED_FILE') . ':<ol id="ol_' . $row_data[$i]->field_name . '"></ol></p></div>';
							$ex_field .= '<input type="hidden" name="extrafields' . $product_id . '[]" id="' . $row_data[$i]->field_name . '_' . $product_id . '" ' . $req . ' userfieldlbl="' . $row_data[$i]->field_title . '"  />';

							$ex_field .= '<script>jQuery.noConflict();new AjaxUpload("file' . $row_data[$i]->field_name . '_' . $product_id . '",{action:"' . JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=ajaxupload",data :{mname:"file' . $row_data[$i]->field_name . '_' . $product_id . '"}, name:"file' . $row_data[$i]->field_name . '_' . $product_id . '",onSubmit : function(file , ext){jQuery("' . $row_data[$i]->field_name . '").text("' . JText::_('COM_REDSHOP_UPLOADING') . '" + file);this.disable();}, onComplete :function(file,response){jQuery("<li></li>").appendTo(jQuery("#ol_' . $row_data[$i]->field_name . '")).text(response);var uploadfiles = jQuery("#ol_' . $ajax . $row_data[$i]->field_name . ' li").map(function() {return jQuery(this).text();}).get().join(",");jQuery("#' . $row_data[$i]->field_name . '_' . $product_id . '").val(uploadfiles);jQuery("#' . $row_data[$i]->field_name . '").val(uploadfiles);this.enable();}});</script>';
						}
						break;

					case 11:
						// 11 :- Image select
						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $cart[$idx][$row_data[$i]->field_name]);
						$ex_field .= '<table><tr>';

						for ($c = 0; $c < count($field_chk); $c++)
						{
							$ex_field .= '<td><div class="userfield_input"><img id="' . $row_data[$i]->field_name . "_" . $field_chk[$c]->value_id . '" class="pointer imgClass_' . $product_id . '" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $field_chk[$c]->field_name . '" title="' . $field_chk[$c]->field_value . '" alt="' . $field_chk[$c]->field_value . '" onclick="javascript:setProductUserFieldImage(\'' . $row_data[$i]->field_name . '\',\'' . $product_id . '\',\'' . $field_chk[$c]->field_value . '\',this);"/></div></td>';
						}

						$ex_field .= '</tr></table>';
						$ajax = '';

						if (AJAX_CART_BOX && $isatt > 0)
						{
							$ajax = 'ajax';
						}

						$ex_field .= '<input type="hidden" name="extrafields' . $product_id . '[]" id="' . $ajax . $row_data[$i]->field_name . '_' . $product_id . '" userfieldlbl="' . $row_data[$i]->field_title . '" ' . $req . '  />';
						break;

					case 12:
						// 12 :- Date Picker
						$ajax = '';
						$req = $row_data[$i]->required;

						if (AJAX_CART_BOX && $isatt == 0)
						{
							$req = 0;
						}

						if (AJAX_CART_BOX && $isatt > 0)
						{
							$ajax = 'ajax';
						}

						$ex_field .= '<div class="userfield_input">' . JHTML::_('calendar', $text_value, 'extrafields' . $product_id . '[]', $ajax . $row_data[$i]->field_name . '_' . $product_id, $format = '%d-%m-%Y', array('class' => $row_data[$i]->field_class, 'size' => $row_data[$i]->field_size, 'maxlength' => $row_data[$i]->field_maxlength, 'required' => $req, 'userfieldlbl' => $row_data[$i]->field_title, 'errormsg' => '')) . '</div>';
						break;

					case 15:
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

								for ($c = 0; $c < count($mainsplit_date_extra); $c++)
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
		$redTemplate = new Redtemplate;
		$url         = JURI::base();
		$q = "SELECT * from " . $this->_table_prefix . "fields where field_section='" . $field_section . "' ";

		if ($field_name != "")
		{
			$q .= "and field_name in ($field_name)";
		}

		$this->_db->setQuery($q);
		$row_data = $this->_db->loadObjectlist();

		for ($i = 0; $i < count($row_data); $i++)
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

			if (count($data_value) != 0 && $published && $field_show_in_front)
			{
				switch ($type)
				{
					case 1:
						// 1 :- Text Field //
					case 8:
						// 8 :- Wysiwyg
					case 12:
						// Calender
					case 5:
						// 5 :-Select Box (Single select)

						$displayvalue = stripslashes($data_value->data_txt);
						break;
					case 2:
						// 2 :- Text Area
						$displayvalue = htmlspecialchars($data_value->data_txt);
						break;

					case 3:
						// 3 :- Check Box
					case 4:
						// 4 :- Radio Button
					case 6:
						// 6 :- Select Box (Multiple select)
						$field_chk = $this->getFieldValue($row_data[$i]->field_id);
						$chk_data  = @explode(",", $data_value->data_txt);
						$tmparr    = array();

						for ($c = 0; $c < count($field_chk); $c++)
						{
							if (@in_array(urlencode($field_chk[$c]->field_value), $chk_data))
							{
								$tmparr[] = urldecode($field_chk[$c]->field_value);
							}
						}

						$displayvalue = urldecode(implode('<br>', $tmparr));
						break;

					case 7:
						// 7 :-Select Country box
						$displayvalue = "";

						if ($data_value->data_txt != "")
						{
							$q = "SELECT country_name FROM " . $this->_table_prefix . "country "
								. "WHERE country_id=" . $data_value->data_txt;
							$this->_db->setQuery($q);
							$field_chk    = $this->_db->loadObject();
							$displayvalue = $field_chk->country_name;
						}
						break;
					case 9 :
						// Media
						$ftype = explode(".", $data_value->data_txt);

						$link         = REDSHOP_FRONT_IMAGES_ABSPATH . "media/" . $data_value->data_txt;
						$link_phy     = REDSHOP_FRONT_IMAGES_RELPATH . "media/" . $data_value->data_txt;
						$displayvalue = "";

						if (is_file($link_phy))
						{
							$displayvalue = "{" . $ftype[count($ftype) - 1] . "remote}" . $link . "{/" . $ftype[count($ftype) - 1] . "remote}";
						}
						break;
					case 10 :
						// Document

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
					case 11 :
						// Image
					case 13 :
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

						for ($ch = 0; $ch < count($chk_data); $ch++)
						{
							$image_link[$chk_data[$ch]]  = isset($tmp_image_link[$ch]) ? $tmp_image_link[$ch] : '';
							$image_hover[$chk_data[$ch]] = isset($tmp_image_hover[$ch]) ? $tmp_image_hover[$ch] : '';
						}

						$displayvalue = '';

						for ($c = 0; $c < count($document_value); $c++)
						{
							if (@in_array($document_value[$c]->value_id, $chk_data))
							{
								$filename = $document_value[$c]->field_name;

								$link = REDSHOP_FRONT_IMAGES_ABSPATH . "extrafield/" . $filename;

								$str_image_link = $image_link[$document_value[$c]->value_id];

								if ($str_image_link)
								{
									$displayvalue .= "<a href='" . $str_image_link
										. "' class='imgtooltip' ><img src='" . $link . "' /><span><div class='spnheader'>"
										. $row_data[$i]->field_title . "</div><div class='spnalttext'>"
										. $image_hover[$document_value[$c]->value_id] . "</div></span></a>";
								}
								else
								{
									$displayvalue .= "<a class='imgtooltip'><img src='" . $link . "' /><span><div class='spnheader'>"
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
		$q = "SELECT * FROM " . $this->_table_prefix . "fields_value "
			. "WHERE field_id='" . $id . "' "
			. "ORDER BY value_id ASC ";
		$this->_db->setQuery($q);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getSectionFieldList($section = 12, $front = 1, $published = 1, $required = 0)
	{
		$and = "";

		if ($published == 1)
		{
			$and .= "AND published='" . $published . "' ";
		}

		if ($required == 1)
		{
			$and .= "AND required='" . $required . "' ";
		}

		if ($front == 1)
		{
			$and .= "AND field_show_in_front='" . $front . "' ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "fields "
			. "WHERE field_section='" . $section . "' "
			. $and
			. "ORDER BY ordering ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getSectionFieldNameArray($section = 12, $front = 1, $published = 1, $required = 0)
	{
		$and = "";

		if ($published == 1)
		{
			$and .= "AND published='" . $published . "' ";
		}

		if ($required == 1)
		{
			$and .= "AND required='" . $required . "' ";
		}

		$query = "SELECT field_name FROM " . $this->_table_prefix . "fields "
			. "WHERE field_section='" . $section . "' "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadResultArray();

		return $list;
	}

	public function getSectionFieldIdArray($section = 12, $front = 1, $published = 1, $required = 0)
	{
		$and = "";

		if ($published == 1)
		{
			$and .= "AND published='" . $published . "' ";
		}

		if ($required == 1)
		{
			$and .= "AND required='" . $required . "' ";
		}

		$query = "SELECT field_id, field_name FROM " . $this->_table_prefix . "fields "
			. "WHERE field_section='" . $section . "' "
			. "AND field_show_in_front='" . $front . "' "
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getSectionFieldDataList($fieldid, $section = 0, $orderitemid = 0)
	{
		$query = "SELECT fd.*,f.field_title FROM " . $this->_table_prefix . "fields_data AS fd, " . $this->_table_prefix . "fields AS f "
			. "WHERE fd.itemid='" . $orderitemid . "' "
			. "AND fd.fieldid=f.field_id "
			. "AND fd.fieldid='" . $fieldid . "' "
			. "AND fd.section='" . $section . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}
}
