<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Redshop\Helper\ExtraFields;

defined('_JEXEC') or die;

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
     * Extra Field Type joomla articles related
     *
     * @var  int
     */
    const TYPE_JOOMLA_RELATED_ARTICLES = 16;

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
     * Extra Field Section Id for Order
     *
     * @var  integer
     */
    const SECTION_ORDER = 20;

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
        if (!self::getField($name)) {
            return null;
        }

        $fieldId = self::getField($name)->id;

        return self::getData($fieldId, $section, $sectionItem);
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

        if (array_key_exists($name, $fields)) {
            return $fields[$name];
        }

        return null;
    }

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

        if (!empty(self::$fieldsName)) {
            return self::$fieldsName;
        }

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_fields'));

        if ($published >= 0) {
            $query->where($db->qn('published') . ' = ' . (int)$published);
        }

        self::$fieldsName = $db->setQuery($query, $limitStart, $limit)->loadObjectList('name');

        return self::$fieldsName;
    }

    /**
     * Get Section Field Data List
     *
     * @param   integer  $fieldId      Field id
     * @param   integer  $section      Section id of the field.
     * @param   integer  $sectionItem  Section item id
     *
     * @return  mixed|null
     */
    public static function getData($fieldId, $section, $sectionItem)
    {
        $key = $fieldId . '.' . $section . '.' . $sectionItem;

        if (array_key_exists($key, self::$fieldsData)) {
            return self::$fieldsData[$key];
        }

        // Init null.
        self::$fieldsData[$key] = null;

        if ($section == 1) {
            $product = Redshop::product((int)$sectionItem);

            if ($product && isset($product->extraFields[$fieldId])) {
                self::$fieldsData[$key] = $product->extraFields[$fieldId];
            }
        }

        if (($section == 1 && !self::$fieldsData[$key]) || $section != 1) {
            $db                     = JFactory::getDbo();
            $query                  = $db->getQuery(true)
                ->select('fd.*')
                ->select($db->qn('f.title'))
                ->from($db->qn('#__redshop_fields_data', 'fd'))
                ->leftJoin($db->qn('#__redshop_fields', 'f') . ' ON ' . $db->qn('fd.fieldid') . ' = ' . $db->qn('f.id'))
                ->where($db->qn('fd.itemid') . ' = ' . (int)$sectionItem)
                ->where($db->qn('fd.fieldid') . ' = ' . (int)$fieldId)
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
     * @return  array
     *
     * @since   2.0.3
     */
    public static function listAllFieldInProduct($section = self::SECTION_PRODUCT)
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->qn('#__redshop_fields'))
            ->where($db->qn('section') . ' = ' . (int)$section)
            ->where($db->qn('display_in_product') . ' = 1')
            ->where($db->qn('published') . ' = 1')
            ->order($db->qn('ordering'));

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * List all fields
     *
     * @param   string   $fieldSection  Field section
     * @param   integer  $sectionId     Section ID
     * @param   string   $fieldName     Field name
     * @param   string   $templateDesc  Template
     * @param   int      $front         Show field in front
     * @param   int      $checkout      Show field in checkout
     *
     * @return  string                  HTML <td></td>
     *
     * @since   2.0.3
     */
    public static function listAllField(
        $fieldSection = '',
        $sectionId = 0,
        $fieldName = '',
        $templateDesc = '',
        $front = 0,
        $checkout = 0
    ) {
        $db = JFactory::getDbo();

        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models', 'RedshopModel');

        /** @var RedshopModelFields $model */
        $model        = JModelLegacy::getInstance('Fields', 'RedshopModel');
        $customFields = $model->getFieldsBySection($fieldSection, $fieldName, $front, $checkout);

        if (!count($customFields)) {
            return '';
        }

        // Grouping
        $customFieldsGrouped = array(0 => array());

        foreach ($customFields as $customField) {
            if (empty($customField->groupName)) {
                $customFieldsGrouped[0][] = $customField;
            } else {
                $customFieldsGrouped[$customField->groupName][] = $customField;
            }
        }

        if (empty($customFieldsGrouped[0])) {
            unset($customFieldsGrouped[0]);
        }

        $active  = key($customFieldsGrouped);
        $active  = !$active ? JText::_('COM_REDSHOP_FIELD_GROUP_NOGROUP') : $active;
        $active  = 'customfield-group-' . JFilterOutput::stringURLSafe($active);
        $setName = 'customfields-section-' . $fieldSection . '-pane';
        $exField = '<div class="row"><div class="col-sm-12">';
        $exField .= JHtml::_('bootstrap.startTabSet', $setName, array('active' => $active));

        foreach ($customFieldsGrouped as $groupName => $customFieldGroup) {
            if (empty($customFieldGroup)) {
                continue;
            }

            $tabName = !$groupName ? JText::_('COM_REDSHOP_FIELD_GROUP_NOGROUP') : $groupName;
            $exField .= JHtml::_(
                'bootstrap.addTab',
                $setName,
                'customfield-group-' . JFilterOutput::stringURLSafe($tabName),
                $tabName
            );
            $exField .= '<table class="table table-striped">';

            foreach ($customFieldGroup as $customField) {
                $type            = $customField->type;
                $dataValue       = self::getSectionFieldDataList($customField->id, $fieldSection, $sectionId);
                $exField         .= '<tr>';
                $extraFieldValue = "";
                $extraFieldLabel = JText::_($customField->title);
                $required        = '';
                $reqlbl          = ' reqlbl="" ';
                $errormsg        = ' errormsg="" ';

                if ($fieldSection == self::SECTION_QUOTATION && $customField->required == 1) {
                    $required = ' required="1" ';
                    $reqlbl   = ' reqlbl="' . $extraFieldLabel . '" ';
                    $errormsg = ' errormsg="' . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED') . '" ';
                }

                switch ($type) {
                    case self::TYPE_TEXT:
                        $textValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
                        $exField   .= RedshopLayoutHelper::render(
                            'extrafields.field.text',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'textValue'       => $textValue
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_TEXT_AREA:
                        $textareaValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
                        $exField       .= RedshopLayoutHelper::render(
                            'extrafields.field.textarea',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'textValue'       => $textareaValue
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_CHECK_BOX:
                        $fieldChk = RedshopEntityField::getInstance($customField->id)->getFieldValues();
                        $chkData  = isset($dataValue->data_txt) ? explode(",", $dataValue->data_txt)
                            : [];
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.field.checkbox',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'fieldCheck'      => $fieldChk,
                                'checkData'       => $chkData
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_RADIO_BUTTON:
                        $fieldChk = RedshopEntityField::getInstance($customField->id)->getFieldValues();
                        $chkData  = explode(",", $dataValue->data_txt);
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.field.radio',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'fieldCheck'      => $fieldChk,
                                'checkData'       => $chkData
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_SELECT_BOX_SINGLE:
                        $fieldChk = RedshopEntityField::getInstance($customField->id)->getFieldValues();
                        $chkData  = explode(",", ($dataValue->data_txt ?? ''));
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.field.select',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'fieldCheck'      => $fieldChk,
                                'checkData'       => $chkData
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_SELECT_BOX_MULTIPLE:
                        $fieldChk = RedshopEntityField::getInstance($customField->id)->getFieldValues();
                        $chkData  = explode(",", $dataValue->data_txt);
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.field.multiple',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'fieldCheck'      => $fieldChk,
                                'checkData'       => $chkData
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_SELECT_COUNTRY_BOX:
                        $query = $db->getQuery(true)
                            ->select('*')
                            ->from($db->qn('#__redshop_country'));
                        $db->setQuery($query);
                        $fieldChk = $db->loadObjectList();
                        $chkData  = array();

                        if (!empty($dataValue->data_txt)) {
                            $chkData = explode(",", $dataValue->data_txt);
                        }

                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.field.multiple',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'fieldCheck'      => $fieldChk,
                                'checkData'       => $chkData
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_JOOMLA_RELATED_ARTICLES:
                        $query = $db->getQuery(true)
                            ->select('*')
                            ->from($db->qn('#__content'))
                            ->where($db->qn('state') . ' = 1');
                        $db->setQuery($query);

                        $fieldChk = $db->loadObjectList();
                        $chkData  = explode(",", $dataValue->data_txt);
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.field.multiple',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'fieldCheck'      => $fieldChk,
                                'checkData'       => $chkData
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_WYSIWYG:
                        $editor        = JEditor::getInstance(JFactory::getConfig()->get('editor'));
                        $textareaValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';

                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.field.editor',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'textValue'       => $textareaValue,
                                'editor'          => $editor
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_DOCUMENTS:
                        $dataTxt = array();

                        if (is_object($dataValue) && property_exists($dataValue, 'data_txt')) {
                            // Support Legacy string.
                            if (preg_match('/\n/', $dataValue->data_txt)) {
                                $documentExplode = explode("\n", $dataValue->data_txt);
                                $dataTxt         = array($documentExplode[0] => $documentExplode[1]);
                            } else {
                                // Support for multiple file upload using JSON for better string handling
                                $dataTxt = json_decode($dataValue->data_txt);
                            }
                        }

                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.field.document',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'dataTxt'         => $dataTxt,
                                'dataValue'       => $dataValue
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_IMAGE_SELECT:

                        $fieldChk  = RedshopEntityField::getInstance($customField->id)->getFieldValues();
                        $dataValue = self::getSectionFieldDataList($customField->id, $fieldSection, $sectionId);
                        $value     = '';

                        if ($dataValue) {
                            $value = $dataValue->data_txt;
                        }

                        $chkData = explode(',', $value);
                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.field.image',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'fieldCheck'      => $fieldChk,
                                'checkData'       => $chkData,
                                'value'           => $value,
                                'sectionId'       => $sectionId
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );
                        break;

                    case self::TYPE_DATE_PICKER:

                        $format = Redshop::getConfig()->get('DEFAULT_DATEFORMAT', 'Y-m-d');
                        $date   = '';

                        if ($customField->section != 17) {
                            $date = date($format, time());
                        }

                        if ($dataValue) {
                            if ($dataValue->data_txt) {
                                $date = date($format, strtotime($dataValue->data_txt));
                            }
                        }

                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.field.date_picker',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'date'            => $date
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );

                        break;

                    case self::TYPE_IMAGE_WITH_LINK:

                        $fieldChk      = RedshopEntityField::getInstance($customField->id)->getFieldValues();
                        $dataValue     = self::getSectionFieldDataList($customField->id, $fieldSection, $sectionId);
                        $value         = ($dataValue) ? $dataValue->data_txt : '';
                        $tmpImageHover = array();
                        $tmpImageLink  = array();

                        if ($dataValue->altText) {
                            $tmpImageHover = explode(',,,,,', $dataValue->altText);
                        }

                        if ($dataValue->image_link) {
                            $tmpImageLink = explode(',,,,,', $dataValue->image_link);
                        }

                        $chkData    = explode(",", $dataValue->data_txt);
                        $imageLink  = array();
                        $imageHover = array();

                        if ($chkData !== false) {
                            foreach ($chkData as $index => $aChkData) {
                                $imageLink[$aChkData]  = $tmpImageLink[$index];
                                $imageHover[$aChkData] = $tmpImageHover[$index];
                            }
                        }

                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.field.image_link',
                            array(
                                'rowData'         => $customField,
                                'extraFieldLabel' => $extraFieldLabel,
                                'required'        => $required,
                                'requiredLabel'   => $reqlbl,
                                'errorMsg'        => $errormsg,
                                'fieldCheck'      => $fieldChk,
                                'checkData'       => $chkData,
                                'value'           => $value,
                                'sectionId'       => $sectionId,
                                'imageLink'       => $imageLink,
                                'imageHover'      => $imageHover
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );

                        break;

                    case self::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS:

                        if ($dataValue) {
                            if ($dataValue->data_txt) {
                                $mainSplitDateTotal = explode(" ", $dataValue->data_txt);
                                $mainSplitDate      = explode(":", $mainSplitDateTotal[0]);
                                $mainSplitDateExtra = explode(":", $mainSplitDateTotal[1]);
                                $datePublish        = date("d-m-Y", $mainSplitDate[0]);
                                $dateExpiry         = date("d-m-Y", $mainSplitDate[1]);
                            } else {
                                $datePublish        = date("d-m-Y");
                                $dateExpiry         = date("d-m-Y");
                                $mainSplitDateExtra = array();
                            }
                        } else {
                            $datePublish        = date("d-m-Y");
                            $dateExpiry         = date("d-m-Y");
                            $mainSplitDateExtra = array();
                        }

                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.field.selected_condition',
                            array(
                                'rowData'            => $customField,
                                'extraFieldLabel'    => $extraFieldLabel,
                                'required'           => $required,
                                'requiredLabel'      => $reqlbl,
                                'errorMsg'           => $errormsg,
                                'datePublish'        => $datePublish,
                                'dateExpiry'         => $dateExpiry,
                                'mainSplitDateExtra' => $mainSplitDateExtra
                            ),
                            '',
                            array(
                                'component' => 'com_redshop',
                                'client'    => 0
                            )
                        );

                        break;

                    default:
                        JPluginHelper::importPlugin('redshop');
                        $dispatcher = RedshopHelperUtility::getDispatcher();

                        $dispatcher->trigger('onDisplayListField', array(&$exField, $customField, $dataValue));
                }

                if (trim($templateDesc) != '') {
                    if (strstr($templateDesc, "{" . $customField->name . "}")) {
                        $templateDesc = str_replace("{" . $customField->name . "}", $extraFieldValue, $templateDesc);
                        $templateDesc = str_replace(
                            "{" . $customField->name . "_lbl}",
                            $extraFieldLabel,
                            $templateDesc
                        );
                    }

                    $templateDesc = str_replace("{" . $customField->name . "}", "", $templateDesc);
                    $templateDesc = str_replace("{" . $customField->name . "_lbl}", "", $templateDesc);
                } else {
                    if (trim($customField->desc) == '') {
                        $exField .= '<td valign="top"></td>';
                    } else {
                        $exField .= '<td valign="top">&nbsp; '
                            . JHtml::tooltip($customField->desc, $customField->name, 'tooltip.png', '', '') . '</td>';
                    }
                }

                $exField .= '</tr>';
            }

            $exField .= '</table>';
            $exField .= JHtml::_('bootstrap.endTab');
        }

        $exField .= JHtml::_('bootstrap.endTabSet');
        $exField .= '</div></div>';

        if (trim($templateDesc) != '') {
            return $templateDesc;
        }

        return $exField;
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
     * @since   2.0.3
     */
    public static function getSectionFieldDataList($fieldId, $section = 0, $orderItemId = 0, $userEmail = "")
    {
        JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/models', 'RedshopModel');

        /** @var RedshopModelFields $model */
        $model = JModelLegacy::getInstance('Fields', 'RedshopModel');

        return $model->getFieldDataList($fieldId, $section, $orderItemId, $userEmail);
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
     * @throws  Exception
     *
     * @since 2.0.3
     */
    public static function extraFieldSave($data, $fieldSection, $sectionId = "", $userEmail = "")
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->qn('#__redshop_fields'))
            ->where($db->qn('section') . ' = ' . (int)$fieldSection)
            ->where($db->qn('published') . ' = 1');

        $rows = (array)$db->setQuery($query)->loadObjectList();

        if (empty($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $dataTxt = '';

            if (isset($data[$row->name])) {
                if ($row->type == self::TYPE_TEXT || $row->type == self::TYPE_TEXT_AREA) {
                    $dataTxt = \JFilterInput::getInstance()->clean($data[$row->name]);
                } elseif ($row->type == self::TYPE_WYSIWYG) {
                    $inputField = JFactory::getApplication()->input->post->get($row->name, '', 'raw');
                    $dataTxt    = \JFilterInput::getInstance(null, null, 1, 1)->clean($inputField, 'html');
                } else {
                    $dataTxt = $data[$row->name];
                }
            }

            // Save Document Extra Field
            if ($row->type == self::TYPE_DOCUMENTS) {
                $files = $_FILES[$row->name]['name'];
                $texts = $data['text_' . $row->name];

                $documentsValue = array();

                if (isset($data[$row->name])) {
                    $documentsValue = $data[$row->name];
                }

                if (is_array($files) && !empty($files)) {
                    $documents = array();

                    foreach ($files as $index => $file) {
                        // Editing uploaded file
                        if (!empty($documentsValue[$index])) {
                            if (!empty(trim($texts[$index]))) {
                                $documents[trim($texts[$index])] = $documentsValue[$index];
                            } else {
                                $documents[$index] = $documentsValue[$index];
                            }
                        }

                        if (!empty($file)) {
                            $name        = RedshopHelperMedia::cleanFileName($file);
                            $src         = $_FILES[$row->name]['tmp_name'][$index];
                            $destination = REDSHOP_FRONT_DOCUMENT_RELPATH . 'extrafields/' . $name;

                            JFile::upload($src, $destination);

                            if (!empty(trim($texts[$index]))) {
                                $documents[trim($texts[$index])] = $name;
                            } else {
                                $documents[$index] = $name;
                            }
                        }
                    }

                    // Convert array into JSON string for better handler.
                    $dataTxt = json_encode($documents);
                }
            }

            if ($row->type == self::TYPE_SELECTION_BASED_ON_SELECTED_CONDITIONS && $data[$row->name] !== "" && $data[$row->name . "_expiry"] !== "") {
                $dataTxt = strtotime($data[$row->name]) . ":" . strtotime($data[$row->name . "_expiry"]) . " ";

                if (!empty($data[$row->name . "_extra_name"])) {
                    foreach ($data[$row->name . "_extra_name"] as $aData) {
                        $dataTxt .= strtotime($aData) . ':';
                    }
                }
            }

            if (is_array($dataTxt)) {
                $dataTxt = implode(',', $dataTxt);
            }

            $sections = explode(',', $fieldSection);

            if ($row->type == self::TYPE_IMAGE_SELECT || $row->type == self::TYPE_IMAGE_WITH_LINK) {
                $list          = self::getSectionFieldDataList($row->id, $fieldSection, $sectionId, $userEmail);
                $strImageHover = '';
                $strImageLink  = '';

                if ($row->type === self::TYPE_IMAGE_WITH_LINK) {
                    $fieldValueArray = explode(',', $data['imgFieldId' . $row->id]);
                    $imageHover      = array();
                    $imageLink       = array();

                    foreach ($fieldValueArray as $index => $fieldValue) {
                        $imageHover[$index] = $data['image_hover' . $fieldValue];
                        $imageLink[$index]  = $data['image_link' . $fieldValue];
                    }

                    $strImageHover = implode(',,,,,', $imageHover);
                    $strImageLink  = implode(',,,,,', $imageLink);

                    $sql = $db->getQuery(true);
                    $sql->update($db->qn('#__redshop_fields_data'))
                        ->set($db->qn('alt_text') . ' = ' . $db->quote($strImageHover))
                        ->set($db->qn('image_link') . ' = ' . $db->quote($strImageLink))
                        ->where($db->qn('itemid') . ' = ' . (int)$sectionId)
                        ->where($db->qn('section') . ' = ' . $db->quote($fieldSection))
                        ->where($db->qn('user_email') . ' = ' . $db->quote($userEmail))
                        ->where($db->qn('fieldid') . ' = ' . (int)$row->id);

                    $db->setQuery($sql)->execute();
                }

                // Reset $sql query
                $sql = $db->getQuery(true);

                if (!empty($list)) {
                    $sql->update($db->qn('#__redshop_fields_data'))
                        ->set($db->qn('data_txt') . ' = ' . $db->quote((string)$data['imgFieldId' . $row->id]))
                        ->where($db->qn('itemid') . ' = ' . (int)$sectionId)
                        ->where($db->qn('section') . ' = ' . $db->quote($fieldSection))
                        ->where($db->qn('user_email') . ' = ' . $db->quote($userEmail))
                        ->where($db->qn('fieldid') . ' = ' . (int)$row->id);
                } else {
                    $sql->insert($db->qn('#__redshop_fields_data'))
                        ->columns(
                            $db->qn(
                                array(
                                    'fieldid',
                                    'data_txt',
                                    'itemid',
                                    'section',
                                    'alt_text',
                                    'image_link',
                                    'user_email'
                                )
                            )
                        )
                        ->values(
                            implode(
                                ',',
                                array(
                                    (int)$row->id,
                                    $db->quote($data['imgFieldId' . $row->id]),
                                    (int)$sectionId,
                                    $db->quote($fieldSection),
                                    $db->quote($strImageHover),
                                    $db->quote($strImageLink),
                                    $db->quote($userEmail)
                                )
                            )
                        );
                }

                $db->setQuery($sql);
                $db->execute();
            } else {
                if ($row->type == self::TYPE_CHECK_BOX || $row->type == self::TYPE_RADIO_BUTTON) {
                    $dataTxt = urldecode($dataTxt);
                }

                foreach ($sections as $section) {
                    $list = self::getSectionFieldDataList($row->id, (int)$section, (int)$sectionId, $userEmail);
                    $sql  = $db->getQuery(true);

                    if (!empty($list)) {
                        $sql->update($db->qn('#__redshop_fields_data'))
                            ->set($db->qn('data_txt') . ' = ' . $db->quote($dataTxt))
                            ->where($db->qn('itemid') . ' = ' . (int)$sectionId)
                            ->where($db->qn('section') . ' = ' . (int)$section)
                            ->where($db->qn('user_email') . ' = ' . $db->quote($userEmail))
                            ->where($db->qn('fieldid') . ' = ' . (int)$row->id);

                        $db->setQuery($sql)->execute();

                        continue;
                    }

                    if (!empty($dataTxt)) {
                        $sql->insert($db->qn('#__redshop_fields_data'))
                            ->columns($db->qn(array('fieldid', 'data_txt', 'itemid', 'section', 'user_email')))
                            ->values(
                                implode(
                                    ',',
                                    array(
                                        (int)$row->id,
                                        $db->quote($dataTxt),
                                        (int)$sectionId,
                                        (int)$section,
                                        $db->quote($userEmail)
                                    )
                                )
                            );

                        $db->setQuery($sql)->execute();
                    }
                }
            }
        }
    }

    /**
     * Validate Extra Field
     *
     * @param   integer  $fieldSection  Field Section List
     * @param   integer  $sectionId     Section ID
     *
     * @return  boolean
     *
     * @since 2.0.3
     */
    public static function CheckExtraFieldValidation($fieldSection = 0, $sectionId = 0)
    {
        $rowData = self::getSectionFieldList($fieldSection);

        for ($i = 0, $in = count($rowData); $i < $in; $i++) {
            $required  = $rowData[$i]->required;
            $dataValue = self::getSectionFieldDataList($rowData[$i]->id, $fieldSection, $sectionId);

            if (empty($dataValue) && $required) {
                return $rowData[$i]->title;
            }
        }

        return false;
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
     * @since 2.0.3
     */
    public static function getSectionFieldList(
        $section = self::SECTION_PRODUCT_USERFIELD,
        $front = null,
        $published = 1,
        $required = 0
    ) {
        $key = $section . '_' . $front . '_' . $published . '_' . $required;

        if (!array_key_exists($key, static::$sectionFields)) {
            $db    = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*')
                ->from($db->qn('#__redshop_fields'))
                ->where($db->qn('section') . ' = ' . (int)$section)
                ->order($db->qn('ordering'));

            if (null !== $front) {
                $query->where($db->qn('show_in_front') . ' = ' . (int)$front);
            }

            if ($published) {
                $query->where($db->qn('published') . ' = ' . (int)$published);
            }

            if ($required) {
                $query->where($db->qn('required') . ' = ' . (int)$required);
            }

            static::$sectionFields[$key] = $db->setQuery($query)->loadObjectList();
        }

        return static::$sectionFields[$key];
    }

    /**
     * List all fields and display
     *
     * @param   integer  $fieldSection  Field section
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
    public static function listAllFieldDisplay(
        $fieldSection = 0,
        $sectionId = 0,
        $flag = 0,
        $userEmail = "",
        $templateDesc = "",
        $sendmail = false
    ) {
        $rowData = self::getSectionFieldList($fieldSection);

        $exField = '';

        for ($i = 0, $in = count($rowData); $i < $in; $i++) {
            $type            = $rowData[$i]->type;
            $extraFieldValue = "";
            $extraFieldLabel = $rowData[$i]->title;

            if ($flag == 1) {
                if ($i > 0) {
                    $exField .= "<br />";
                }

                $exField .= JText::_($extraFieldLabel) . ' : ';
            }

            $dataValue = self::getSectionFieldDataList($rowData[$i]->id, $fieldSection, $sectionId, $userEmail);

            switch ($type) {
                case self::TYPE_TEXT:
                    $extraFieldValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
                    $exField         .= RedshopLayoutHelper::render(
                        'field_display.text',
                        array(
                            'extraFieldLabel' => $extraFieldLabel,
                            'extraFieldValue' => $extraFieldValue,
                            'sendMail'        => $sendmail
                        ),
                        '',
                        array(
                            'component' => 'com_redshop'
                        )
                    );
                    break;

                case self::TYPE_TEXT_AREA:
                    $extraFieldValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';
                    $exField         .= RedshopLayoutHelper::render(
                        'field_display.textarea',
                        array(
                            'extraFieldLabel' => $extraFieldLabel,
                            'extraFieldValue' => $extraFieldValue,
                            'sendMail'        => $sendmail
                        ),
                        '',
                        array(
                            'component' => 'com_redshop'
                        )
                    );
                    break;

                case self::TYPE_CHECK_BOX:
                    $fieldChk        = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
                    $chkData         = !empty($dataValue->data_txt) ? explode(",", $dataValue->data_txt) : array();
                    $extraFieldValue = [];

                    foreach ($fieldChk as $key => $data) {
                        if (!in_array($data->field_value, $chkData)) {
                            continue;
                        }

                        $extraFieldValue[] = $data->field_name;
                    }

                    $exField .= RedshopLayoutHelper::render(
                        'field_display.checkbox',
                        array(
                            'extraFieldLabel' => $extraFieldLabel,
                            'extraFieldValue' => implode(',', $extraFieldValue),
                            'sendMail'        => $sendmail
                        ),
                        '',
                        array(
                            'component' => 'com_redshop'
                        )
                    );
                    break;

                case self::TYPE_RADIO_BUTTON:
                    $fieldChk        = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
                    $chkData         = !empty($dataValue->data_txt) ? explode(",", $dataValue->data_txt) : array();
                    $extraFieldValue = '';

                    foreach ($fieldChk as $key => $data) {
                        if (!in_array($data->field_value, $chkData)) {
                            continue;
                        }

                        $extraFieldValue .= $data->field_name;
                    }

                    $exField .= RedshopLayoutHelper::render(
                        'field_display.radio',
                        array(
                            'extraFieldLabel' => $extraFieldLabel,
                            'extraFieldValue' => $extraFieldValue,
                            'sendMail'        => $sendmail
                        ),
                        '',
                        array(
                            'component' => 'com_redshop'
                        )
                    );
                    break;

                case self::TYPE_SELECT_BOX_SINGLE:
                    $fieldChk        = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
                    $chkData         = !empty($dataValue->data_txt) ? explode(",", $dataValue->data_txt) : array();
                    $extraFieldValue = '';

                    foreach ($fieldChk as $key => $data) {
                        if (!in_array($data->field_value, $chkData)) {
                            continue;
                        }

                        $extraFieldValue .= $data->field_name;
                    }

                    $exField .= RedshopLayoutHelper::render(
                        'field_display.select',
                        array(
                            'extraFieldLabel' => $extraFieldLabel,
                            'extraFieldValue' => $extraFieldValue,
                            'sendMail'        => $sendmail
                        ),
                        '',
                        array(
                            'component' => 'com_redshop'
                        )
                    );
                    break;

                case self::TYPE_SELECT_BOX_MULTIPLE:
                    $fieldChk        = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
                    $chkData         = !empty($dataValue->data_txt) ? explode(",", $dataValue->data_txt) : array();
                    $extraFieldValue = array();

                    foreach ($fieldChk as $key => $data) {
                        if (!in_array($data->field_value, $chkData)) {
                            continue;
                        }

                        $extraFieldValue[] = $data->field_name;
                    }

                    $exField .= RedshopLayoutHelper::render(
                        'field_display.multiple',
                        array(
                            'extraFieldLabel' => $extraFieldLabel,
                            'extraFieldValue' => $extraFieldValue,
                            'sendMail'        => $sendmail
                        ),
                        '',
                        array(
                            'component' => 'com_redshop'
                        )
                    );
                    break;

                case self::TYPE_SELECT_COUNTRY_BOX:
                    $extraFieldValue = "";

                    if ($dataValue && $dataValue->data_txt) {
                        $fieldChk        = RedshopEntityCountry::getInstance($dataValue->data_txt);
                        $extraFieldValue = $fieldChk->get('country_name');
                    }

                    $exField .= RedshopLayoutHelper::render(
                        'field_display.country',
                        array(
                            'extraFieldLabel' => $extraFieldLabel,
                            'extraFieldValue' => $extraFieldValue,
                            'sendMail'        => $sendmail
                        ),
                        '',
                        array(
                            'component' => 'com_redshop'
                        )
                    );
                    break;

                // 12 :- Date Picker
                case self::TYPE_DATE_PICKER:
                    $extraFieldValue = ($dataValue && $dataValue->data_txt) ? $dataValue->data_txt : '';

                    $format = \Redshop::getConfig()->get('DEFAULT_DATEFORMAT');
                    $extraFieldValue = date($format, strtotime($extraFieldValue));
                    //$extraFieldValue = date(\Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), strtotime($extraFieldValue));

                    $exField         .= RedshopLayoutHelper::render(
                        'field_display.datepicker',
                        array(
                            'extraFieldLabel' => $extraFieldLabel,
                            'extraFieldValue' => $extraFieldValue,
                            'sendMail'        => $sendmail
                        ),
                        '',
                        array(
                            'component' => 'com_redshop'
                        )
                    );
                    break;
            }

            if (trim($templateDesc) != '') {
                if (strstr($templateDesc, "{" . $rowData[$i]->name . "}")) {
                    $templateDesc = str_replace("{" . $rowData[$i]->name . "}", $extraFieldValue, $templateDesc);
                    $templateDesc = str_replace("{" . $rowData[$i]->name . "_lbl}", $extraFieldLabel, $templateDesc);
                }

                $templateDesc = str_replace("{" . $rowData[$i]->name . "}", "", $templateDesc);
                $templateDesc = str_replace("{" . $rowData[$i]->name . "_lbl}", "", $templateDesc);
            }
        }

        if (trim($templateDesc) != '') {
            return $templateDesc;
        }

        if ($flag == 0 && !empty($extraFieldLabel)) {
            $client      = null;
            $fieldLayout = 'fields.display';

            if ($sendmail) {
                $fieldLayout = 'fields.mail';
                $client      = array('client' => 0);
            }

            return RedshopLayoutHelper::render(
                $fieldLayout,
                array('extraFieldValue' => $exField),
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
     * @return  array
     *
     * @since   2.0.3
     */
    public static function listAllUserFields(
        $fieldSection = "",
        $sectionId = self::SECTION_PRODUCT_USERFIELD,
        $fieldType = '',
        $uniqueId = ''
    ) {
        /** @scrutinizer ignore-deprecated */
        JHtml::script('com_redshop/redshop.attribute.min.js', false, true);
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->qn('#__redshop_fields'))
            ->where($db->qn('section') . ' = ' . (int)$sectionId)
            ->where($db->qn('name') . ' = ' . $db->quote($fieldSection))
            ->where($db->qn('published') . ' = 1');

        $rowData      = $db->setQuery($query)->loadObjectList();
        $exField      = '';
        $exFieldTitle = '';
        $cart         = \Redshop\Cart\Helper::getCart();
        $idx          = 0;

        if (isset($cart['idx'])) {
            $idx = (int)($cart['idx']);
        }

        for ($i = 0, $in = count($rowData); $i < $in; $i++) {
            $type     = $rowData[$i]->type;
            $asterisk = $rowData[$i]->required > 0 ? '* ' : '';

            if ($fieldType != 'hidden') {
                $exFieldTitle .= '<div class="userfield_label">' . $asterisk . $rowData[$i]->title . '</div>';
            }

            if ($fieldType == 'hidden') {
                $exField .= '<input type="hidden" name="extrafieldId' . $uniqueId . '[]"  value="' . $rowData[$i]->id . '" />';
            } else {
                $req = ' required = "' . $rowData[$i]->required . '"';

                switch ($type) {
                    case self::TYPE_TEXT:
                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.userfield.text',
                            array(
                                'rowData'  => $rowData[$i],
                                'required' => $req,
                                'uniqueId' => $uniqueId
                            )
                        );
                        break;

                    case self::TYPE_TEXT_AREA:
                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.userfield.textarea',
                            array(
                                'rowData'  => $rowData[$i],
                                'required' => $req,
                                'uniqueId' => $uniqueId
                            )
                        );
                        break;

                    case self::TYPE_CHECK_BOX:
                        $fieldChk = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
                        $chkData  = explode(",", $cart[$idx][$rowData[$i]->name]);
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.userfield.checkbox',
                            array(
                                'rowData'    => $rowData[$i],
                                'required'   => $req,
                                'fieldCheck' => $fieldChk,
                                'checkData'  => $chkData,
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;

                    case self::TYPE_RADIO_BUTTON:
                        $fieldChk = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
                        $chkData  = explode(",", $cart[$idx][$rowData[$i]->name]);
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.userfield.radio',
                            array(
                                'rowData'    => $rowData[$i],
                                'required'   => $req,
                                'fieldCheck' => $fieldChk,
                                'checkData'  => $chkData,
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;

                    case self::TYPE_SELECT_BOX_SINGLE:
                        $fieldChk = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
                        $chkData  = explode(",", $cart[$idx][$rowData[$i]->name]);
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.userfield.select',
                            array(
                                'rowData'    => $rowData[$i],
                                'required'   => $req,
                                'fieldCheck' => $fieldChk,
                                'checkData'  => $chkData,
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;

                    case self::TYPE_SELECT_BOX_MULTIPLE:
                        $fieldChk = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
                        $chkData  = explode(",", $cart[$idx][$rowData[$i]->name]);
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.userfield.multiple',
                            array(
                                'rowData'    => $rowData[$i],
                                'required'   => $req,
                                'fieldCheck' => $fieldChk,
                                'checkData'  => $chkData,
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;

                    case self::TYPE_DOCUMENTS:
                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.userfield.document',
                            array(
                                'rowData'    => $rowData[$i],
                                'required'   => $req,
                                'fieldCheck' => $req,
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;

                    case self::TYPE_IMAGE_SELECT:
                        $fieldChk = RedshopEntityField::getInstance($rowData[$i]->id)->getFieldValues();
                        $chkData  = explode(",", $cart[$idx][$rowData[$i]->name]);
                        $exField  .= RedshopLayoutHelper::render(
                            'extrafields.userfield.image',
                            array(
                                'rowData'    => $rowData[$i],
                                'required'   => $req,
                                'fieldCheck' => $fieldChk,
                                'checkData'  => $chkData,
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;

                    case self::TYPE_DATE_PICKER:
                        $req     = $rowData[$i]->required;
                        $exField .= RedshopLayoutHelper::render(
                            'extrafields.userfield.date_picker',
                            array(
                                'rowData'    => $rowData[$i],
                                'required'   => $req,
                                'fieldCheck' => $req,
                                'uniqueId'   => $uniqueId
                            )
                        );
                        break;
                }
            }

            if (trim($rowData[$i]->desc) != '' && $fieldType != 'hidden') {
                $exField .= '<div class="userfield_tooltip">&nbsp; '
                    . JHtml::tooltip($rowData[$i]->desc, $rowData[$i]->name, 'tooltip.png', '', '', false)
                    . '</div>';
            }
        }

        return array($exFieldTitle, $exField);
    }

    /**
     * Render HTML radio list
     *
     * @param   string   $name      Name of radio checkbox
     * @param   mixed    $attribs   Attribute values
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
            JHtml::_('select.option', "Days", JText::_($yes)),
            JHtml::_('select.option', "Weeks", JText::_($no))
        );

        return JHtml::_('select.radiolist', $arr, $name, $attribs, 'value', 'text', $selected, $id);
    }

    /**
     * Render HTML radio list with options
     *
     * @param   string   $name      Name of radio checkbox
     * @param   mixed    $attribs   Attribute values
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
    public static function rsBooleanList(
        $name,
        $attribs = null,
        $selected = null,
        $yes = 'yes',
        $no = 'no',
        $id = false,
        $yesValue = 'Days',
        $noValue = 'Weeks'
    ) {
        $arr = array(
            JHtml::_('select.option', $yesValue, JText::_($yes)),
            JHtml::_('select.option', $noValue, JText::_($no))
        );

        return JHtml::_('redshopselect.radiolist', $arr, $name, $attribs, 'value', 'text', $selected, $id);
    }

    /**
     * Get fields value by ID
     *
     * @param   integer  $id  ID of field
     *
     * @return  array
     *
     * @since       2.0.3
     *
     * @deprecated  2.0.6  Use RedshopEntityField::getFieldValues instead.
     */
    public static function getFieldValue($id)
    {
        return RedshopEntityField::getInstance($id)->getFieldValues();
    }

    /**
     * Copy product extra field
     *
     * @param   integer  $oldProductId  Old Product ID
     * @param   integer  $newPid        New Product ID
     *
     * @return  void
     *
     * @since   2.0.3
     */
    public static function copyProductExtraField($oldProductId, $newPid)
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->qn('#__redshop_fields_data'))
            ->where($db->qn('itemid') . ' = ' . (int)$oldProductId)
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
        if (empty($list)) {
            return;
        }

        $query->clear()
            ->insert($db->qn('#__redshop_fields_data'))
            ->columns(
                $db->qn(array('fieldid', 'data_txt', 'itemid', 'section', 'alt_text', 'image_link', 'user_email'))
            );

        foreach ($list as $row) {
            $query->values(
                implode(
                    ',',
                    array(
                        (int)$row->fieldid,
                        $db->quote($row->data_txt),
                        (int)$newPid,
                        (int)$row->section,
                        $db->quote($row->alt_text),
                        $db->quote($row->image_link),
                        $db->quote($row->user_email)
                    )
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
            ->where($db->qn('data_id') . ' = ' . (int)$dataId);

        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Method for render HTML of extra fields
     *
     * @param   integer  $fieldSection     Field section
     * @param   integer  $sectionId        ID of section
     * @param   string   $fieldName        Field name
     * @param   string   $templateContent  HTML template content
     * @param   integer  $categoryPage     Category page
     *
     * @return  mixed
     * @throws  Exception
     *
     * @since        2.0.6
     *
     * @deprecated   2.1.0
     */
    public static function extraFieldDisplay(
        $fieldSection = 0,
        $sectionId = 0,
        $fieldName = "",
        $templateContent = "",
        $categoryPage = 0
    ) {
        return ExtraFields::displayExtraFields(
            $fieldSection,
            $sectionId,
            $fieldName,
            $templateContent,
            (boolean)$categoryPage
        );
    }

    /**
     * Method for get article joomla by id.
     *
     * @param   string  $ids  Is required?
     *
     * @return  mixed
     */
    public static function getArticleJoomlaById($ids)
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__content'))
            ->where($db->qn('id') . ' IN (' . $ids . ')');

        return $db->setQuery($query)->loadObjectList();
    }

    /**
     * Method get display field data
     *
     * @param   mixed    $data    Is required?
     * @param   string   $layout  Is required?
     * @param   integer  $fieldId
     * @param   string   $dataTxt
     *
     * @return  string
     */
    public static function getDisplayFieldData($data, $layout, $fieldId = 0, $dataTxt = '')
    {
        if (empty($data)) {
            $fieldValues = \RedshopEntityField::getInstance($fieldId)->getFieldValues();
            $checkData   = explode(',', $dataTxt);
            $data        = $layout == 'select' ? array() : '';

            foreach ($fieldValues as $value) {
                if (!in_array(urlencode($value->field_value), $checkData) && !in_array(
                        $value->field_value,
                        $checkData
                    )) {
                    continue;
                }

                if ($layout == 'select') {
                    $data[] = urldecode($value->field_name);
                } else {
                    $data = urldecode($value->field_name);
                }
            }
        }

        return \RedshopLayoutHelper::render(
            'extrafields.display.' . $layout,
            array(
                'data' => $data
            )
        );
    }
}
