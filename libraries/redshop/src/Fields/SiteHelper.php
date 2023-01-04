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
     * @param   integer  $fieldSection  Field Section
     * @param   integer  $sectionId     Section ID
     * @param   string   $uniqueClass   Unique class
     *
     * @return  string
     *
     * @since   2.1.0
     */
    public static function renderFields($fieldSection = 0, $sectionId = 0, $uniqueClass = '')
    {
        $fields = \RedshopHelperExtrafields::getSectionFieldList($fieldSection, 1);

        if (empty($fields)) {
            return '';
        }

        $html = '';

        foreach ($fields as $field) {
            $type      = $field->type;
            $dataValue = \RedshopHelperExtrafields::getData($field->id, $fieldSection, $sectionId);

            if (!empty($dataValue) && count((array)$dataValue) <= 0) {
                $dataValue->data_txt = '';
            }

            $cssClassName = array();
            $class        = '';

            if (1 == $field->required) {
                if ($uniqueClass == '') {
                    $cssClassName[] = 'required';
                } else {
                    $cssClassName[] = $uniqueClass;
                }

                // Adding title to display JS validation Error message.
                $class = 'title="' . \JText::sprintf(
                        'COM_REDSHOP_VALIDATE_EXTRA_FIELD_IS_REQUIRED',
                        $field->title
                    ) . '" ';
            }

            // Default css class name
            $cssClassName[] = $field->class;
            $class          .= ' class="' . implode(' ', $cssClassName) . '"';
            $fieldEntity    = \RedshopEntityField::getInstance($field->id)->bind($field);
            $inputField     = '';

            switch ($type) {
                case \RedshopHelperExtrafields::TYPE_TEXT_AREA:
                    $textAreaValue = $dataValue && $dataValue->data_txt ? $dataValue->data_txt : '';
                    $inputField    = '<textarea ' . $class . '  name="' . $field->name . '"  id="' . $field->name . '" cols="' . $field->cols . '"'
                        . ' rows="' . $field->rows . '" >' . $textAreaValue . '</textarea>';
                    break;

                case \RedshopHelperExtrafields::TYPE_CHECK_BOX:
                    $fieldValues = $fieldEntity->getFieldValues();
                    $chkData     = !empty($dataValue->data_txt) ? explode(",", $dataValue->data_txt) : array();

                    foreach ($fieldValues as $value) {
                        $checked = '';

                        if (in_array($value->field_value, $chkData)) {
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
                    $chkData     = !empty($dataValue->data_txt) ? explode(",", $dataValue->data_txt) : array();
                    $inputField  = '<select class="' . $field->class . ' ' . $class . '"    name="' . $field->name . '"   id="' . $field->name . '">';

                    foreach ($fieldValues as $value) {
                        $selected = '';

                        if (in_array($value->field_value, $chkData)) {
                            $selected = ' selected="selected" ';
                        }

                        $inputField .= '<option value="' . $value->field_value . '" ' . $selected . ' >' . $value->field_name . '</option>';
                    }

                    $inputField .= '</select>';
                    break;

                case \RedshopHelperExtrafields::TYPE_SELECT_BOX_MULTIPLE:
                    $fieldValues = $fieldEntity->getFieldValues();
                    $chkData     = !empty($dataValue->data_txt) ? explode(",", $dataValue->data_txt) : array();

                    $inputField = '<select class="' . $field->class . ' ' . $class . '"   multiple size=10 name="' . $field->name . '[]">';

                    foreach ($fieldValues as $value) {
                        $selected = '';

                        if (in_array(urlencode($value->field_value), $chkData)) {
                            $selected = ' selected="selected" ';
                        }

                        $inputField .= '<option value="' . urlencode($value->field_value) . '" ' . $selected . ' >'
                            . $value->field_name . '</option>';
                    }

                    $inputField .= '</select>';
                    break;

                case \RedshopHelperExtrafields::TYPE_DATE_PICKER:
                    $date = $dataValue && $dataValue->data_txt ? date("d-m-Y", strtotime($dataValue->data_txt)) : date(
                        "d-m-Y",
                        time()
                    );
                    $size = $field->size > 0 ? $field->size : 20;

                    $inputField = \JHtml::_(
                        'redshopcalendar.calendar',
                        $date,
                        $field->name,
                        $field->name,
                        $format = 'd-m-Y',
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
     * @param   string  $uniqueId       Unique ID
     *
     * @return  array
     * @since   2.1.0
     */
    public static function listAllUserFields(
        $fieldSection = "",
        $sectionId = \RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD,
        $fieldType = '',
        $idx = 'NULL',
        $isAtt = 0,
        $productId = 0,
        $myWish = '',
        $addWish = 0,
        $uniqueId = ''
    ) {
        $db   = \JFactory::getDbo();
        $cart = \Redshop\Cart\Helper::getCart();

        $prePrefix = "";

        if ($isAtt == 1) {
            $prePrefix = "ajax_";
        }

        $addToCartFormName = 'addtocart_' . $prePrefix . 'prd_' . $productId;

        if (!array_key_exists($sectionId . '_' . $fieldSection, self::$userFields)) {
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

        foreach ($rowData as $index => $data) {
            $type     = $data->type;
            $asterisk = $data->required > 0 ? '* ' : '';

            if ($fieldType != 'hidden') {
                $exFieldTitle .= '<div class="userfield_label">' . $asterisk . $data->title . '</div>';
            }

            $textValue = $addWish == 1 ? $myWish : '';

            if (!empty($cart) && isset($cart[$idx][$data->name])) {
                $textValue = $cart[$idx][$data->name];

                if ($type == \RedshopHelperExtrafields::TYPE_DATE_PICKER) {
                    $textValue = date("d-m-Y", strtotime($cart[$idx][$data->name]));
                }
            }

            if ($fieldType == 'hidden') {
                $value = '';

                if ($type == \RedshopHelperExtrafields::TYPE_DOCUMENTS) {
                    $userDocuments = \JFactory::getSession()->get('userDocument', array());
                    $fileNames     = array();

                    if (isset($userDocuments[$productId])) {
                        foreach ($userDocuments[$productId] as $id => $userDocument) {
                            $fileNames[] = $userDocument['fileName'];
                        }

                        $value = implode(',', $fileNames);
                    }
                }

                $exField .= '<input type="hidden" name="' . $data->name . '"  id="' . $data->name . '" value="' . $value . '"/>';
            } else {
                $req = '';

                if ($data->required == 1) {
                    $req = ' required = "' . $data->required . '"';
                }

                $uniqueId = !empty($uniqueId) ? $uniqueId : $productId;

                switch ($type) {
                    default:
                    case \RedshopHelperExtrafields::TYPE_TEXT:

                        $onKeyup = '';

                        if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') == 0) {
                            $onKeyup = $addToCartFormName . '.' . $data->name . '.value = this.value';
                        }

                        $exField .= \RedshopLayoutHelper::render(
                            'extrafields.userfield.text',
                            array(
                                'rowData'  => $data,
                                'required' => $req,
                                'uniqueId' => $uniqueId,
                                'onKeyup' => $onKeyup
                            )
                        );
                        break;

                    case \RedshopHelperExtrafields::TYPE_TEXT_AREA:

                        $onKeyup = '';

                        if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') == 0) {
                            $onKeyup = $addToCartFormName . '.' . $data->name . '.value = this.value';
                        }

                        $exField .= \RedshopLayoutHelper::render(
                            'extrafields.userfield.textarea',
                            array(
                                'rowData'  => $data,
                                'required' => $req,
                                'uniqueId' => $uniqueId,
                                'onKeyup' => $onKeyup
                            )
                        );
                        break;

                    case \RedshopHelperExtrafields::TYPE_CHECK_BOX:

                        $fieldCheck = \RedshopEntityField::getInstance($data->id)->getFieldValues();
                        $checkData  = explode(",", $cart[$idx][$data->name]);

                        $exField  .= \RedshopLayoutHelper::render(
                            'extrafields.userfield.checkbox',
                            array(
                                'rowData'    => $data,
                                'required'   => $req,
                                'fieldCheck' => $fieldCheck,
                                'checkData'  => $checkData,
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;

                    case \RedshopHelperExtrafields::TYPE_RADIO_BUTTON:

                        $fieldCheck = \RedshopEntityField::getInstance($data->id)->getFieldValues();
                        $chkData    = explode(",", $cart[$idx][$data->name]);

                        $exField  .= \RedshopLayoutHelper::render(
                            'extrafields.userfield.radio',
                            array(
                                'rowData'    => $data,
                                'required'   => $req,
                                'fieldCheck' => $fieldCheck,
                                'checkData'  => $chkData,
                                'uniqueId'   => $uniqueId
                            )
                        );

                        break;

                    case \RedshopHelperExtrafields::TYPE_SELECT_BOX_SINGLE:
                        $fieldCheck = \RedshopEntityField::getInstance($data->id)->getFieldValues();
                        $exField  .= \RedshopLayoutHelper::render(
                            'extrafields.userfield.select',
                            array(
                                'rowData'    => $data,
                                'required'   => $req,
                                'fieldCheck' => $fieldCheck,
                                'checkData'  => isset($cart[$idx][$data->name]) ? explode(",", $cart[$idx][$data->name]) : [],
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;

                    case \RedshopHelperExtrafields::TYPE_SELECT_BOX_MULTIPLE:

                        $fieldChk = \RedshopEntityField::getInstance($data->id)->getFieldValues();
                        $chkData  = explode(",", $cart[$idx][$data->name]);
                        $exField  .= \RedshopLayoutHelper::render(
                            'extrafields.userfield.multiple',
                            array(
                                'rowData'    => $data,
                                'required'   => $req,
                                'fieldCheck' => $fieldChk,
                                'checkData'  => $chkData,
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;

                    case \RedshopHelperExtrafields::TYPE_DOCUMENTS:
                        $exField .= \RedshopLayoutHelper::render(
                            'extrafields.userfield.document',
                            array(
                                'rowData'    => $data,
                                'required'   => $req,
                                'uniqueId'   => $uniqueId,
                                'isAtt' => $isAtt,
                                'productId' => $productId
                            )
                        );

                        $ajax   = '';
                        if ($isAtt > 0) {
                            $ajax   = 'ajax';
                        }

                        $exField .= '<p>' . \JText::_(
                                'COM_REDSHOP_UPLOADED_FILE'
                            ) . ':</p>' . ExtraFields::displayUserDocuments($productId, $data, $ajax) . '</div>';
                        break;

                    case \RedshopHelperExtrafields::TYPE_IMAGE_SELECT:
                        $fieldChk = \RedshopEntityField::getInstance($data->id)->getFieldValues();
                        $exField  .= \RedshopLayoutHelper::render(
                            'extrafields.userfield.image',
                            array(
                                'rowData'    => $data,
                                'required'   => $req,
                                'fieldCheck' => $fieldChk,
                                'uniqueId'   => $uniqueId,
                                'isAtt'      => $isAtt
                            )
                        );
                        break;

                    case \RedshopHelperExtrafields::TYPE_DATE_PICKER:

                        $ajax = '';
                        $req  = $data->required;

                        if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isAtt == 0) {
                            $req = 0;
                        }

                        if (\Redshop::getConfig()->getInt('AJAX_CART_BOX') && $isAtt > 0) {
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

                        if ($fieldCheck) {
                            $mainSplitDateTotal = preg_split(" ", $fieldCheck->data_txt);
                            $mainSplitDate      = preg_split(":", $mainSplitDateTotal[0]);
                            $mainSplitDateExtra = preg_split(":", $mainSplitDateTotal[1]);

                            $dateStart  = mktime(
                                0,
                                0,
                                0,
                                (int)date('m', $mainSplitDate[0]),
                                (int)date('d', $mainSplitDate[0]),
                                (int)date('Y', $mainSplitDate[0])
                            );
                            $dateEnd    = mktime(
                                23,
                                59,
                                59,
                                (int)date('m', $mainSplitDate[1]),
                                (int)date('d', $mainSplitDate[1]),
                                (int)date('Y', $mainSplitDate[1])
                            );
                            $todayStart = mktime(
                                0,
                                0,
                                0,
                                (int)date('m'),
                                (int)date('d'),
                                (int)date('Y')
                            );
                            $todayEnd   = mktime(
                                23,
                                59,
                                59,
                                (int)date('m'),
                                (int)date('d'),
                                (int)date('Y')
                            );

                            if ($dateStart <= $todayStart && $dateEnd >= $todayEnd) {
                                $exField .= '<div class="userfield_input">';
                                $exField .= '' . $asterisk . $data->title . ' : <select name="extrafields' . $productId . '[]" id="' . $data->name . '" userfieldlbl="' . $data->title . '" ' . $req . ' >';
                                $exField .= '<option value="">' . \JText::_('COM_REDSHOP_SELECT') . '</option>';

                                foreach ($mainSplitDateExtra as $aMainSplitDateExtra) {
                                    if ($aMainSplitDateExtra != "") {
                                        $exField .= '<option value="' . date(
                                                "d-m-Y",
                                                (int) $aMainSplitDateExtra
                                            ) . '"  >' . date("d-m-Y", (int) $aMainSplitDateExtra) . '</option>';
                                    }
                                }

                                $exField .= '</select></div>';
                            }
                        }
                        break;
                }
            }

            if (trim($data->desc) != '' && $fieldType != 'hidden') {
                $exField .= '<div class="userfield_tooltip">&nbsp; ' . \JHtml::tooltip(
                        $data->desc,
                        $data->name,
                        'tooltip.png',
                        '',
                        ''
                    ) . '</div>';
            }
        }

        return array($exFieldTitle, $exField);
    }
}
