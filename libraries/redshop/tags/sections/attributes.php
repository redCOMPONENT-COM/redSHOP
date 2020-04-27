<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  3.0
 */
class RedshopTagsSectionsAttributes extends RedshopTagsAbstract
{
    /**
     * @var
     * @since 3.0
     */
    public static $selectSubProperty;
    /**
     * @var
     * @since 3.0
     */
    public static $propertyStockRooms;
    /**
     * @var
     * @since 3.0
     */
    public static $preSelected;
    /**
     * @var
     * @since 3.0
     */
    public static $propertyPreOrderStockRooms;
    /**
     * @var
     * @since 3.0
     */
    public static $preOrderPropertyStock;
    /**
     * @var    array
     *
     * @since   3.0
     */
    public $tags = array();
    /**
     * @var    integer
     *
     * @since   3.0
     */
    private $mphThumb;
    /**
     * @var    integer
     *
     * @since   3.0
     */
    private $mpwThumb;

    /**
     * @var    integer
     *
     * @since   3.0
     */
    private $isAjax;

    /**
     * @var    string
     *
     * @since   3.0
     */
    private $prefix;

    /**
     * Init
     *
     * @return  mixed
     *
     * @since   3.0
     */
    public function init()
    {
    }

    /**
     * Execute replace
     *
     * @return  string
     *
     * @since   3.0
     */
    public function replace()
    {
        $userId             = 0;
        $session            = JFactory::getSession();
        $productId          = !empty($this->data['productId']) ? $this->data['productId'] : 0;
        $accessoryId        = !empty($this->data['accessoryId']) ? $this->data['accessoryId'] : 0;
        $relatedProductId   = !empty($this->data['relatedProductId']) ? $this->data['relatedProductId'] : 0;
        $attributes         = !empty($this->data['attributes']) ? $this->data['attributes'] : [];
        $attributeTemplate  = !empty($this->data['attributeTemplate']) ? $this->data['attributeTemplate'] : null;
        $isChild            = !empty($this->data['isChild']) ? $this->data['isChild'] : false;
        $selectedAttributes = !empty($this->data['selectedAttributes']) ? $this->data['selectedAttributes'] : [];
        $displayIndCart     = !empty($this->data['displayIndCart']) ? $this->data['displayIndCart'] : 1;
        $onlySelected       = !empty($this->data['onlySelected']) ? $this->data['onlySelected'] : false;

        $isApplyAttributeVAT     = \Redshop\Template\Helper::isApplyAttributeVat($this->template);
        $isApplyAttributeVATTags = array(
            'chkvat' => $isApplyAttributeVAT
        );

        $session->set('chkvat', $isApplyAttributeVATTags);

        if ($displayIndCart && Redshop::getConfig()->getInt('INDIVIDUAL_ADD_TO_CART_ENABLE') === 1) {
            $attributeTemplate = empty($attributeTemplate) ?
                \Redshop\Template\Helper::getAttribute($this->template, false) : $attributeTemplate;

            if (!empty($attributeTemplate)) {
                $this->template = str_replace("{attribute_template:$attributeTemplate->name}", '', $this->template);
            }

            // @Todo refactor template section attribute with cart
            return \RedshopHelperAttribute::replaceAttributeWithCartData(
                $productId,
                $accessoryId,
                $relatedProductId,
                $attributes,
                $this->template,
                $attributeTemplate,
                $isChild,
                $onlySelected
            );
        }

        $attributeTemplate = empty($attributeTemplate) ? \Redshop\Template\Helper::getAttribute(
            $this->template,
            false
        ) : $attributeTemplate;

        if (empty($attributeTemplate) || $attributeTemplate == new stdClass) {
            return $this->template;
        }

        $this->replacements["{attributewithcart_template:$attributeTemplate->name}"] = '';

        if ($isChild || count($attributes) <= 0) {
            return str_replace("{attribute_template:$attributeTemplate->name}", '', $this->template);
        }

        JHtml::_('script', 'com_redshop/redshop.thumbscroller.min.js', array('version' => 'auto', 'relative' => true));
        $layout = JFactory::getApplication()->input->getCmd('layout', '');

        $prePrefix    = "";
        $this->isAjax = 0;

        if ($layout == "viewajaxdetail") {
            $prePrefix    = "ajax_";
            $this->isAjax = 1;
        }

        if ($accessoryId != 0) {
            $this->prefix = $prePrefix . "acc_";
        } elseif ($relatedProductId != 0) {
            $this->prefix = $prePrefix . "rel_";
        } else {
            $this->prefix = $prePrefix . "prd_";
        }

        if ($relatedProductId != 0) {
            $productId = $relatedProductId;
        }

        $product = \Redshop\Product\Product::getProductById($productId);

        $this->setDimensionImage($product);


        $selectProperty    = array();
        $selectSubProperty = array();

        if (count($selectedAttributes) > 0) {
            $selectProperty    = $selectedAttributes[0];
            $selectSubProperty = $selectedAttributes[1];
        }

        self::$selectSubProperty = $selectSubProperty;

        $attributeTemplateData = $attributeTemplate->template_desc;

        JText::script('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED');

        if (count($attributes) > 0) {
            $attributeTable = "";

            // Import plugin group
            JPluginHelper::importPlugin('redshop_product');

            for ($a = 0, $an = count($attributes); $a < $an; $a++) {
                $attributeTable .= $this->replaceAttribute(
                    $attributes[$a],
                    $attributeTemplateData,
                    $productId,
                    $accessoryId,
                    $selectProperty,
                    $relatedProductId,
                    $product
                );
            }

            $attributeTable = \RedshopLayoutHelper::render(
                'tags.attributes.attribute_template',
                [
                    'content' => $attributeTable
                ],
                '',
                \RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{attribute_template:$attributeTemplate->name}"] = $attributeTable;
        } else {
            $this->replacements["{attribute_template:$attributeTemplate->name}"] = '';
        }

        $this->template = $this->strReplace($this->replacements, $this->template);

        return parent::replace();
    }

    public function setDimensionImage($product)
    {
        $productTemplate = RedshopHelperTemplate::getTemplate("product", $product->product_template);

        if (strpos($productTemplate[0]->template_desc, "{more_images_3}") !== false) {
            $this->mphThumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3');
            $this->mpwThumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_3');
        } elseif (strpos($productTemplate[0]->template_desc, "{more_images_2}") !== false) {
            $this->mphThumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2');
            $this->mpwThumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_2');
        } elseif (strpos($productTemplate[0]->template_desc, "{more_images_1}") !== false) {
            $this->mphThumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
            $this->mpwThumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
        } else {
            $this->mphThumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
            $this->mpwThumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
        }
    }

    public function replaceAttribute(
        $attribute,
        $attributeTemplateData,
        $productId,
        $accessoryId,
        $selectProperty,
        $relatedProductId,
        $product
    ) {

        if ($attribute->attribute_show_fe == 0) {
            $target = '<div class="attribute_wrapper">';
            $replacement = '<div class="attribute_wrapper" style="display: none;">';
            $attributeTemplateData = str_replace($target, $replacement, $attributeTemplateData);
        }

        $subDisplay  = false;
        $replaceAttr = [];

        $propertyAll = empty($attribute->properties) ?
            \RedshopHelperProduct_Attribute::getAttributeProperties(0, $attribute->attribute_id) :
            $attribute->properties;
        $propertyAll = array_values($propertyAll);

        if (!Redshop::getConfig()->get('DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA') && Redshop::getConfig()->get(
                'USE_STOCKROOM'
            )) {
            $property = \Redshop\Helper\Stockroom::getAttributePropertyWithStock($propertyAll);
        } else {
            $property = $propertyAll;
        }

        $propertyIds = array_map(
            function ($object) {
                return $object->value;
            },
            $property
        );

        self::$propertyStockRooms         = \RedshopHelperStockroom::getMultiSectionsStock($propertyIds, 'property');
        self::$propertyPreOrderStockRooms = \RedshopHelperStockroom::getMultiSectionsPreOrderStock(
            $propertyIds,
            'property'
        );

        $attributeTable                   = '';

        if ($attribute->text != "" && count($property) > 0) {
            $attributeTable .= $attributeTemplateData;

            $commonId    = $this->prefix . $productId . '_' . $accessoryId . '_' . $attribute->value;
            $hiddenAttId = 'attribute_id_' . $this->prefix . $productId . '_' . $accessoryId;
            $propertyId  = 'property_id_' . $commonId;

            $imgAdded              = 0;
            $selectedProperty      = 0;
            self::$preSelected     = true;
            $propertyWoscrollerDiv = '';
            $propertyImgWScroller  = strpos($attributeTable, "{property_image_without_scroller}");

            if ($propertyImgWScroller !== false) {
                $replaceAttr['{property_image_scroller}'] = '';
            }

            for ($i = 0, $in = count($property); $i < $in; $i++) {
                $propertyWoscrollerDiv .= $this->replaceProperty(
                    $attributeTable,
                    $imgAdded,
                    $property[$i],
                    $selectProperty,
                    $selectedProperty,
                    $propertyId,
                    $productId,
                    $relatedProductId,
                    $accessoryId,
                    $attribute
                );
            }

            $propertyWoscrollerDiv = \RedshopLayoutHelper::render(
                'tags.attributes.property_image_w_scroller',
                array(
                    'propertyImgWScroller' => $propertyImgWScroller,
                    'content'              => $propertyWoscrollerDiv
                ),
                '',
                \RedshopLayoutHelper::$layoutOption
            );

            if (!$this->mphThumb) {
                $this->mphThumb = 50;
            }

            if (!$this->mpwThumb) {
                $this->mpwThumb = 50;
            }

            // Run event for prepare product properties.
            RedshopHelperUtility::getDispatcher()->trigger('onPrepareProductProperties', array($product, &$property));

            $properties        = array_merge(
                array(JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT') . ' ' . urldecode($attribute->text))),
                $property
            );
            $defaultPropertyId = array();
            $attDisplayType    = $attribute->display_type;

            // Init listing html-attributes
            $chkListAttributes = array(
                'attribute_name' => urldecode($attribute->attribute_name)
            );

            // Only add required html-attibute if needed.
            if ($attribute->attribute_required) {
                $chkListAttributes['required'] = 'true';
            }

            // Prepare Javascript OnChange or OnClick function
            $changePropertyDropdown = "changePropertyDropdown('" . $productId . "','" . $accessoryId . "','"
                . $relatedProductId . "', '" . $attribute->value . "',this.value, '" . $this->mpwThumb . "', '"
                . $this->mphThumb . "');";

            // Radio or Checkbox
            if ($attDisplayType == 'radio') {
                unset($properties[0]);

                $attributeListType = ($attribute->allow_multiple_selection) ? 'redshopselect.checklist' : 'redshopselect.radiolist';

                $chkListAttributes['cssClassSuffix'] = ' no-group';
                $chkListAttributes['onClick']        = "javascript:" . $changePropertyDropdown;
            } // Dropdown list
            else {
                $attributeListType = 'select.genericlist';
                $scrollerFunction  = '';

                if ($imgAdded > 0 && strpos($attributeTable, "{property_image_scroller}") !== false) {
                    $scrollerFunction = "isFlowers" . $commonId . ".scrollImageCenter(this.selectedIndex-1);";
                }

                $chkListAttributes['onchange'] = "javascript:" . $scrollerFunction . $changePropertyDropdown;
            }

            if ($selectedProperty) {
                $subDisplay          = true;
                $defaultPropertyId[] = $selectedProperty;
            }

            $lists['property_id'] = JHTML::_(
                $attributeListType,
                $properties,
                $propertyId . '[]',
                $chkListAttributes,
                'value',
                'text',
                $selectedProperty,
                $propertyId
            );

            $attributeTable .= \RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'type'  => 'hidden',
                    'name'  => $hiddenAttId . '[]',
                    'value' => $attribute->value
                ),
                '',
                \RedshopLayoutHelper::$layoutOption
            );

            if ($attribute->attribute_required > 0) {
                $pos       = Redshop::getConfig()->get('ASTERISK_POSITION') > 0 ? urldecode(
                        $attribute->text
                    ) . "<span id='asterisk_right'> * " : "<span id='asterisk_left'>* </span>" . urldecode(
                        $attribute->text
                    );
                $attrTitle = $pos;
            } else {
                $attrTitle = urldecode($attribute->text);
            }

            if (strpos($attributeTable, '{attribute_tooltip}') !== false) {
                if (!empty($attribute->attribute_description)) {
                    $replaceAttr['{attribute_tooltip}'] = JHTML::tooltip(
                        $attribute->attribute_description,
                        $attribute->attribute_description,
                        'tooltip.png',
                        '',
                        ''
                    );
                } else {
                    $replaceAttr['{attribute_tooltip}'] = '';
                }
            }

            $replaceAttr['{attribute_title}']   = $attrTitle;
            $replaceAttr['{property_dropdown}'] = $lists['property_id'];

            $propertyScroller = \RedshopLayoutHelper::render(
                'tags.attributes.property_scroller',
                array(
                    'attribute'        => $attribute,
                    'properties'       => $property,
                    'commonId'         => $commonId,
                    'productId'        => $productId,
                    'propertyId'       => $propertyId,
                    'accessoryId'      => $accessoryId,
                    'relatedProductId' => $relatedProductId,
                    'selectedProperty' => $selectedProperty,
                    'width'            => $this->mpwThumb,
                    'height'           => $this->mphThumb
                ),
                '',
                \RedshopLayoutHelper::$layoutOption
            );

            // Changes for attribue Image Scroll
            if ($imgAdded == 0 || $this->isAjax == 1) {
                $propertyScroller = "";
            }

            $replaceAttr['{property_image_scroller}']         = $propertyScroller;
            $replaceAttr['{property_image_without_scroller}'] = $propertyWoscrollerDiv;

            if ($subDisplay) {
                $style = ' style="display:block" ';
            } else {
                $style = ' style="display:none" ';
            }
            $attributeTable   = $this->strReplace($replaceAttr, $attributeTable);
            $subPropertyData  = "";
            $subPropertyStart = $attributeTable;
            $subPropertyEnd   = "";
            $subAttData       = explode("{subproperty_start}", $attributeTable);

            if (count($subAttData) > 0) {
                $subPropertyStart = $subAttData[0];
            }

            $replaceMiddle = '';

            if (count($subAttData) > 1) {
                $subAttData = explode("{subproperty_end}", $subAttData[1]);

                if (count($subAttData) > 0) {
                    $subPropertyData = $subAttData[0];
                    $replaceMiddle   = "{replace_subprodata}";
                }

                if (count($subAttData) > 1) {
                    $subPropertyEnd = $subAttData[1];
                }
            }

            $subPropertyStartTag = '<div id="property_responce' . $commonId . '" ' . $style . '>';

            $displaySubProperty = "";

            $layout = JFactory::getApplication()->input->getCmd('layout', '');

            foreach ($defaultPropertyId as $aDefaultPropertyId) {
                $displaySubProperty .= RedshopHelperProduct::replaceSubPropertyData(
                    $productId,
                    $accessoryId,
                    $relatedProductId,
                    $attribute->attribute_id,
                    $aDefaultPropertyId,
                    $subPropertyData,
                    $layout,
                    self::$selectSubProperty
                );
            }

            if ($subDisplay) {
                $attributeTable = $subPropertyStart . "{subproperty_start}" . $replaceMiddle . "{subproperty_end}" . $subPropertyEnd;
                $attributeTable = str_replace($replaceMiddle, $displaySubProperty, $attributeTable);
            }

            $attributeTable .= \RedshopLayoutHelper::render(
                'tags.common.input',
                [
                    'type'  => 'hidden',
                    'id'    => 'subattdata_' . $commonId,
                    'value' => base64_encode(htmlspecialchars($subPropertyData))
                ],
                '',
                \RedshopLayoutHelper::$layoutOption
            );

            $attributeTable = str_replace("{subproperty_start}", $subPropertyStartTag, $attributeTable);
            $attributeTable = str_replace("{subproperty_end}", "</div>", $attributeTable);
        }

        return $attributeTable;
    }

    public function replaceProperty(
        &$attributeTable,
        &$imgAdded,
        $property,
        $selectProperty,
        &$selectedProperty,
        $propertyId,
        $productId,
        $relatedProductId,
        $accessoryId,
        $attribute
    ) {
        $propertyWoscrollerDiv = '';
        if (count($selectProperty) > 0) {
            if (in_array($property->value, $selectProperty)) {
                $selectedProperty = $property->value;
            }
        } else {
            if ($property->setdefault_selected) {
                $selectedProperty = $property->value;
            }
        }

        if (isset($property->sub_properties)) {
            $subPropertyAll = $property->sub_properties;
        } else {
            $subPropertyAll = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property->value);
        }

        // Filter Out of stock data
        if (!Redshop::getConfig()->get('DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA') && Redshop::getConfig()->get(
                'USE_STOCKROOM'
            )) {
            $subProperty = \Redshop\Helper\Stockroom::getAttributeSubPropertyWithStock($subPropertyAll);
        } else {
            $subProperty = $subPropertyAll;
        }

        $subPropertyStock         = 0;
        $preOrderSubPropertyStock = 0;

        $subPropertyIds                = array_map(
            function ($item) {
                return $item->value;
            },
            $subProperty
        );
        $subPropertyStockrooms         = \RedshopHelperStockroom::getMultiSectionsStock($subPropertyIds, 'subproperty');
        $subPropertyPreOrderStockrooms = \RedshopHelperStockroom::getMultiSectionsPreOrderStock(
            $subPropertyIds,
            'subproperty'
        );

        foreach ($subProperty as $sub) {
            $subPropertyStock         += isset($subPropertyStockrooms[$sub->value]) ? (int)$subPropertyStockrooms[$sub->value] : 0;
            $preOrderSubPropertyStock += isset($subPropertyPreOrderStockrooms[$sub->value]) ?
                (int)$subPropertyPreOrderStockrooms[$sub->value] : 0;
        }

        $propertyStock = isset(self::$propertyStockRooms[$property->value]) ? (int)self::$propertyStockRooms[$property->value] : 0;
        $propertyStock += $subPropertyStock;

        // Preorder stock data
        self::$preOrderPropertyStock = isset(self::$propertyPreOrderStockRooms[$property->value]) ?
            (int)self::$propertyPreOrderStockRooms[$property->value] : 0;
        self::$preOrderPropertyStock += $preOrderSubPropertyStock;

        if ($property->property_image) {
            if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property->property_image)) {
                $thumbUrl = \RedshopHelperMedia::getImagePath(
                    $property->property_image,
                    '',
                    'thumb',
                    'product_attributes',
                    $this->mpwThumb,
                    $this->mphThumb,
                    Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
                );

                $style = null;

                if ($property->setdefault_selected && self::$preSelected) {
                    $style             = ' style="border: 1px solid;"';
                    self::$preSelected = false;
                }

                $propertyWoscrollerDiv .= \RedshopLayoutHelper::render(
                    'tags.attributes.support_property_scroller',
                    [
                        'style'            => $style,
                        'propertyId'       => $propertyId,
                        'property'         => $property,
                        'productId'        => $productId,
                        'accessoryId'      => $accessoryId,
                        'relatedProductId' => $relatedProductId,
                        'attribute'        => $attribute,
                        'mpwThumb'         => $this->mpwThumb,
                        'mphThumb'         => $this->mphThumb,
                        'thumbUrl'         => $thumbUrl
                    ],
                    '',
                    \RedshopLayoutHelper::$layoutOption
                );

                $imgAdded++;
            }
        }

        $attributesPropertyVatShow    = 0;
        $attributesPropertyWithoutVat = 0;
        $attributesPropertyOldPrice   = 0;

        if ($property->property_price > 0) {
            if ($property->setdefault_selected && !empty($property->property_price_without_vat)) {
                $property->property_price = $property->property_price_without_vat;
            }

            $attributesPropertyOldPrice = $property->property_price;

            $pricelist = RedshopHelperProduct_Attribute::getPropertyPrice($property->value, 1, 'property');

            if (!empty($pricelist)) {
                $property->property_price = $pricelist->product_price;
            }

            $attributesPropertyWithoutVat = $property->property_price;

            /*
             * changes for {without_vat} tag output parsing
             * only for display purpose
             */
            $attributesPropertyVatShow     = 0;
            $attributesPropertyOldPriceVat = 0;

            if (!empty($isApplyAttributeVAT)) {
                if ($property->oprand != '*' && $property->oprand != '/') {
                    $attributesPropertyVatShow     = RedshopHelperProduct::getProductTax(
                        $productId,
                        $property->property_price,
                        \JFactory::getUser()->id
                    );
                    $attributesPropertyOldPriceVat = RedshopHelperProduct::getProductTax(
                        $productId,
                        $attributesPropertyOldPrice,
                        \JFactory::getUser()->id
                    );
                }
            }

            /*
             * get product vat to include
             */
            $attributesPropertyVat = RedshopHelperProduct::getProductTax(
                $productId,
                $property->property_price,
                \JFactory::getUser()->id
            );

            $property->property_price += $attributesPropertyVat;

            $attributesPropertyVatShow  += $property->property_price;
            $attributesPropertyOldPrice += $attributesPropertyOldPriceVat;

            if (Redshop::getConfig()->get('SHOW_PRICE')
                && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
                    || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get(
                            'SHOW_QUOTATION_PRICE'
                        )))
                && (!$attribute->hide_attribute_price)) {
                $property->text = urldecode($property->property_name) . " (" . $property->oprand
                    . strip_tags(RedshopHelperProductPrice::formattedPrice($attributesPropertyVatShow)) . ")";
            } else {
                $property->text = urldecode($property->property_name);
            }
        } else {
            $property->text = urldecode($property->property_name);
        }

        // Add stock data into property data.
        $property->stock = $propertyStock;

        // Add pre-order stock data into property data.
        $property->preorder_stock = self::$preOrderPropertyStock;

        $attributeTable .= \RedshopLayoutHelper::render(
            'tags.attributes.subtemplate_attribute',
            [
                'propertyId'                   => $propertyId,
                'property'                     => $property,
                'attributesPropertyVatShow'    => $attributesPropertyVatShow,
                'attributesPropertyWithoutVat' => $attributesPropertyWithoutVat,
                'attributesPropertyOldPrice'   => $attributesPropertyOldPrice,
                'propertyStock'                => $propertyStock,
                'preorderPropertyStock'        => !empty(self::$preOrderPropertyStock) ? self::$preOrderPropertyStock : 0
            ],
            '',
            \RedshopLayoutHelper::$layoutOption
        );

        return $propertyWoscrollerDiv;
    }
}