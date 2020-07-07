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
 * @since  3.0.1
 */
class RedshopTagsSectionsRelatedProduct extends RedshopTagsAbstract
{
    /**
     * @var    object
     *
     * @since  3.0.1
     */
    public $product = array();

    /**
     * Init
     *
     * @return  mixed
     *
     * @since   3.0.1
     */
    public function init()
    {
        $this->product = $this->data['product'];
    }

    /**
     * Execute replace
     *
     * @return  string
     *
     * @since   __DEPLOY__VERSION__
     */
    public function replace()
    {
        $relatedProduct = RedshopHelperProduct::getRelatedProduct($this->product->product_id);
        $subTemplate    = $this->getTemplateBetweenLoop('{related_product_start}', '{related_product_end}');
        $templateData   = '';
        $fieldList      = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);
        /************************************************************ **********************************************/
        for ($r = 0, $rn = count($relatedProduct); $r < $rn; $r++) {
            $templateData .= $this->replaceProductItem($subTemplate['template'], $relatedProduct[$r], $fieldList);
        }

        $this->template = $subTemplate['begin'] . $templateData . $subTemplate['end'];

        return parent::replace();
    }

    /**
     * Replace product item
     *
     * @param   string  $template
     * @param   object  $product
     * @param   array   $fieldList
     *
     * @return  string
     *
     * @since   3.0.1
     */
    private function replaceProductItem($template, $product, $fieldList)
    {
        $replacement = [];

        $rLink = JRoute::_(
            'index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&Itemid=' . $this->itemId
        );

        $imgData = $this->getWidthHeight(
            $template,
            'relproduct_image',
            'RELATED_PRODUCT_THUMB_HEIGHT',
            'RELATED_PRODUCT_THUMB_WIDTH'
        );

        $relImage = Redshop\Product\Image\Image::getImage(
            $product->product_id,
            $rLink,
            $imgData['width'],
            $imgData['height']
        );

        $replacement[$imgData['imageTag']] = RedshopLayoutHelper::render(
            'tags.related_product.relproduct_image',
            [
                'image'  => $relImage,
                'width'  => $imgData['width'],
                'height' => $imgData['height'],
            ],
            '',
            $this->optionLayout
        );

        if (strstr($template, "{relproduct_link}")) {
            $rpName = RedshopLayoutHelper::render(
                'tags.common.link',
                [
                    'link'    => $rLink,
                    'content' => RedshopHelperUtility::maxChars(
                        $product->product_name,
                        Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_MAX_CHARS'),
                        Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_END_SUFFIX')
                    )
                ],
                '',
                $this->optionLayout
            );
        } else {
            $rpName = RedshopHelperUtility::maxChars(
                $product->product_name,
                Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_MAX_CHARS'),
                Redshop::getConfig()->get('RELATED_PRODUCT_TITLE_END_SUFFIX')
            );
        }

        $rpDesc      = RedshopHelperUtility::maxChars(
            $product->product_desc,
            Redshop::getConfig()->get('RELATED_PRODUCT_DESC_MAX_CHARS'),
            Redshop::getConfig()->get('RELATED_PRODUCT_DESC_END_SUFFIX')
        );
        $rpShortDesc = RedshopHelperUtility::maxChars(
            $product->product_s_desc,
            Redshop::getConfig()->get(
                'RELATED_PRODUCT_SHORT_DESC_MAX_CHARS'
            ),
            Redshop::getConfig()->get(
                'RELATED_PRODUCT_SHORT_DESC_END_SUFFIX'
            )
        );

        $replacement['{relproduct_link}'] = '';

        if (strstr($template, "{relproduct_link}")) {
            $replacement['{relproduct_name}'] = '';
        } else {
            $replacement['{relproduct_name}'] = $rpName;
        }

        $template = RedshopHelperProduct::getProductOnSaleComment(
            $product,
            $template
        );

        $replacement['{relproduct_number_lbl}'] = JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL');
        $replacement['{relproduct_number}']     = $product->product_number;
        $replacement['{relproduct_s_desc}']     = $rpShortDesc;
        $replacement['{relproduct_desc}']       = $rpDesc;

        $manufacturer = RedshopEntityManufacturer::getInstance($product->manufacturer_id)->getItem();

        if (is_object($manufacturer)) {
            $replacement['{manufacturer_link}'] = RedshopLayoutHelper::render(
                'tags.common.link',
                [
                    'link'    => JRoute::_(
                        'index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $product->manufacturer_id . '&Itemid=' . $this->itemId
                    ),
                    'content' => JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS")
                ],
                '',
                $this->optionLayout
            );
            $replacement['{manufacturer_name}'] = $manufacturer->name;
        } else {
            $replacement['{manufacturer_name}'] = '';
            $replacement['{manufacturer_link}'] = '';
        }

        // Show Price
        if (!$product->not_for_sale) {
            $template = RedshopHelperProductPrice::getShowPrice(
                $product->product_id,
                $template,
                '',
                0,
                1
            );
        } else {
            $replacement['{price_excluding_vat}']         = '';
            $replacement['{relproduct_price_table}']      = '';
            $replacement['{relproduct_price_novat}']      = '';
            $replacement['{relproduct_old_price}']        = '';
            $replacement['{relproduct_old_price_lbl}']    = '';
            $replacement['{relproduct_price_saving_lbl}'] = '';
            $replacement['{relproduct_price_saving}']     = '';
            $replacement['{relproduct_price}']            = '';
        }

        // End Show Price

        $relMoreLinkHref = JRoute::_(
            'index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&cid=' . $product->cat_in_sefurl . '&Itemid=' . $this->itemId
        );
        $relMoreLink     = "javascript:window.parent.location.href='" . $relMoreLinkHref . "'";

        $replacement['{read_more_link}'] = RedshopLayoutHelper::render(
            'tags.common.link',
            [
                'link'    => $relMoreLink,
                'attr'    => 'title="' . $product->product_name . '"',
                'content' => JText::_('COM_REDSHOP_READ_MORE')
            ],
            '',
            $this->optionLayout
        );
        $replacement['{read_more_link}'] = $relMoreLink;
        /*
         *  related product Required Attribute start
         * 	this will parse only Required Attributes
         */
        $attributesSet = array();

        if ($product->attribute_set_id > 0) {
            $attributesSet = \Redshop\Product\Attribute::getProductAttribute(
                0,
                $product->attribute_set_id
            );
        }

        $attributes = \Redshop\Product\Attribute::getProductAttribute($product->product_id);
        $attributes = array_merge($attributes, $attributesSet);

        $template = $this->strReplace($replacement, $template);
        $template = Redshop\Cart\Render::replace(
            $product->mainproduct_id,
            $this->product->category_id,
            0,
            $product->product_id,
            $template,
            false,
            array(),
            count($attributes)
        );
        $template = Redshop\Product\Compare::replaceCompareProductsButton(
            $product->product_id,
            $this->product->category_id,
            $template,
            1
        );
        $template = Redshop\Product\Stock::replaceInStock(
            $product->product_id,
            $template
        );

        $template = RedshopHelperProduct::replaceAttributePriceList(
            $product->product_id,
            $template
        );

        $template = RedshopHelperProduct::getProductFinderDatepickerValue(
            $template,
            $product->product_id,
            $fieldList
        );

        return $template;
    }
}