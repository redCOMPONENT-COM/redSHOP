<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Product Accessory
 *
 * @since  2.1.0
 */
class RedshopHelperProductAccessory
{
    /**
     * Method for replace accessory data.
     *
     * @param   integer  $productId            Product ID
     * @param   integer  $relProductId         Related product ID
     * @param   array    $accessory            Accessories data.
     * @param   string   $templateContent      Template content
     * @param   boolean  $isChild              True for accessory products is child.
     * @param   array    $selectedAccessories  Selected accessory.
     *
     * @return  mixed|string
     *
     * @throws Exception
     * @since   2.1.0
     *
     */
    public static function replaceAccessoryData(
        $productId = 0,
        $relProductId = 0,
        $accessory = array(),
        $templateContent = '',
        $isChild = false,
        $selectedAccessories = array()
    ) {
        $input   = JFactory::getApplication()->input;
        $viewAcc = $input->get('viewacc', 1);
        $layout  = $input->get('layout');
        $isAjax  = 0;
        $prefix  = "";

        if ($layout == "viewajaxdetail") {
            $isAjax = 1;
            $prefix = "ajax_";
        }

        $productId = $relProductId != 0 ? $relProductId : $productId;

        $accessoryTemplate = \Redshop\Template\Helper::getAccessory($templateContent);

        if (null === $accessoryTemplate) {
            return $templateContent;
        }

        if (empty($accessory)) {
            $templateContent = str_replace(
                "{accessory_template:" . $accessoryTemplate->name . "}",
                "",
                $templateContent
            );

            return $templateContent;
        }

        $accessoryTemplateData2 = $accessoryTemplate->template_desc;

        $accessoryWrapper = RedshopTagsReplacer::_(
            'accessory',
            $accessoryTemplateData2,
            array(
                'accessory'           => $accessory,
                'productId'           => $productId,
                'prefix'              => $prefix,
                'relProductId'        => $relProductId,
                'isChild'             => $isChild,
                'selectedAccessories' => $selectedAccessories,
                'isAjax'              => $isAjax,
                'templateContent'     => $templateContent
            )
        );

        // Attribute ajax change
        if ($viewAcc != 1 && Redshop::getConfig()->getInt('AJAX_CART_BOX') != 0) {
            $accessoryWrapper = '';
        }

        $templateContent = str_replace(
            "{accessory_template:" . $accessoryTemplate->name . "}",
            $accessoryWrapper,
            $templateContent
        );

        return $templateContent;
    }

    /**
     * Method for replace main accessory tags.
     *
     * @param   string   $accessoryTemplate  Accessory template data
     * @param   string   $templateContent    Template content
     * @param   object   $product            Product Data
     * @param   integer  $userId             User ID
     *
     * @return  void
     *
     * @since   2.1.0
     */
    public static function replaceMainAccessory(&$accessoryTemplate, $templateContent, $product, $userId)
    {
        if (strpos($accessoryTemplate, "{if accessory_main}") === false
            || strpos($accessoryTemplate, "{accessory_main end if}") === false) {
            return;
        }

        $accessoryTemplate = explode('{if accessory_main}', $accessoryTemplate);
        $accessoryStart    = $accessoryTemplate[0];
        $accessoryTemplate = explode('{accessory_main end if}', $accessoryTemplate[1]);
        $accessoryEnd      = $accessoryTemplate[1];
        $accessoryMiddle   = $accessoryTemplate[0];

        if (strpos($accessoryMiddle, "{accessory_main_short_desc}") !== false) {
            $accessoryMiddle = str_replace(
                "{accessory_main_short_desc}",
                RedshopHelperUtility::limitText(
                    $product->product_s_desc,
                    Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS'),
                    Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX')
                ),
                $accessoryMiddle
            );
        }

        if (strpos($accessoryMiddle, "{accessory_main_title}") !== false) {
            $accessoryMiddle = str_replace(
                "{accessory_main_title}",
                RedshopHelperUtility::limitText(
                    $product->product_name,
                    Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
                    Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
                ),
                $accessoryMiddle
            );
        }

        $accessoryProductDetail = "<a href='#' title='" . $product->product_name . "'>" . JText::_(
                'COM_REDSHOP_READ_MORE'
            ) . "</a>";
        $accessoryMiddle        = str_replace("{accessory_main_readmore}", $accessoryProductDetail, $accessoryMiddle);
        $accessoryMainImage     = $product->product_full_image;
        $accessoryMainImage2    = '';

        self::getWidthHeight($accessoryMiddle, $accessoryImgTag, $accessoryWidthThumb, $accessoryHeightThumb);

        if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryMainImage)) {
            $thumbUrl = RedshopHelperMedia::getImagePath(
                $accessoryMainImage,
                '',
                'thumb',
                'product',
                $accessoryWidthThumb,
                $accessoryHeightThumb,
                Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
            );

            if (Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX') == 1) {
                $accessoryMainImage2 = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH
                    . "product/" . $accessoryMainImage
                    . "' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\">"
                    . "<img id='main_image' class='redAttributeImage' src='" . $thumbUrl . "' /></a>";
            } else {
                $accessoryMainImage2 = "<img id='main_image' class='redAttributeImage' src='" . $thumbUrl . "' />";
            }
        }

        $accessoryMiddle = str_replace($accessoryImgTag, $accessoryMainImage2, $accessoryMiddle);
        $productPrices   = array();

        if (strpos($accessoryMiddle, "{accessory_mainproduct_price}") !== false
            || strpos($templateContent, "{selected_accessory_price}") !== false) {
            $productPrices = RedshopHelperProductPrice::getNetPrice($product->product_id, $userId, 1, $templateContent);
        }

        if (strpos($accessoryMiddle, "{accessory_mainproduct_price}") !== false) {
            if (Redshop::getConfig()->get('SHOW_PRICE')
                && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
                    || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
                        && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))) {
                $accessoryMainProductPrice = RedshopHelperProductPrice::priceReplacement(
                    $productPrices['product_price']
                );

                $accessoryMiddle = str_replace(
                    "{accessory_mainproduct_price}",
                    $accessoryMainProductPrice,
                    $accessoryMiddle
                );
            }
        }

        $accessoryMiddle   = Redshop\Product\Stock::replaceInStock($product->product_id, $accessoryMiddle);
        $accessoryTemplate = $accessoryStart . $accessoryMiddle . $accessoryEnd;
    }

    /**
     * Method for get image width height from tags in template
     *
     * @param   string   $template  Template content
     * @param   string   $imageTag  Accessory image tag
     * @param   integer  $width     Return variable width
     * @param   integer  $height    Return variable height
     *
     * @return  void
     *
     * @since   2.1.0
     */
    public static function getWidthHeight($template, &$imageTag, &$width, &$height)
    {
        if (strpos($template, "{accessory_main_image_3}") !== false) {
            $imageTag = '{accessory_main_image_3}';
            $height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_3');
            $width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_3');
        } elseif (strpos($template, "{accessory_main_image_2}") !== false) {
            $imageTag = '{accessory_main_image_2}';
            $height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_2');
            $width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_2');
        } elseif (strpos($template, "{accessory_main_image_1}") !== false) {
            $imageTag = '{accessory_main_image_1}';
            $height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
            $width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
        } else {
            $imageTag = '{accessory_main_image}';
            $height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
            $width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
        }
    }
}
