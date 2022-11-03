<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Product\Image;

defined('_JEXEC') or die;

/**
 * Product image render helper
 *
 * @since  2.1.0
 */
class Render
{
    /**
     * @param   \stdClass  $product            Product data
     * @param   string     $imageName          Image name
     * @param   string     $linkImageName      Link image name
     * @param   string     $link               Link
     * @param   integer    $width              Width
     * @param   integer    $height             Height
     * @param   integer    $isLight            Product detail is light
     * @param   integer    $enableHover        Enable hover or not
     * @param   array      $preSelectedResult  Pre selected results
     * @param   string     $suffixId           Suffix ID
     *
     * @return  string                     Html content with replaced.
     * @throws  \Exception
     *
     * @since   2.1.0
     */
    public static function replace(
        $product,
        $imageName = '',
        $linkImageName = '',
        $link = '',
        $width = 0,
        $height = 0,
        $isLight = 2,
        $enableHover = 0,
        $preSelectedResult = array(),
        $suffixId = ''
    ) {
        $isLight       = (int)$isLight;
        $imageName     = trim($imageName);
        $linkImageName = trim($linkImageName);
        $productId     = $product->product_id;
        $middlePath    = REDSHOP_FRONT_IMAGES_RELPATH . 'product/';
        $productImage  = $product->product_full_image;
        $dispatcher    = \RedshopHelperUtility::getDispatcher();

        if ($isLight !== 2 && $product->product_thumb_image && file_exists(
                $middlePath . $product->product_thumb_image
            )) {
            $productImage = $product->product_thumb_image;
        }

        $altText = \RedshopHelperMedia::getAlternativeText('product', $productId, $productImage);
        $altText = empty($altText) ? $product->product_name : $altText;

        $dispatcher->trigger('onChangeMainProductImageAlternateText', array(&$product, &$altText));

        $title           = $altText;
        $catProductHover = false;

        if ($enableHover && \Redshop::getConfig()->getBool('PRODUCT_HOVER_IMAGE_ENABLE')) {
            $catProductHover = true;
        }

        $noimage             = 'noimage.jpg';
        $productImageDefault = REDSHOP_FRONT_IMAGES_ABSPATH . $noimage;
        $productHoverImg     = REDSHOP_FRONT_IMAGES_ABSPATH . $noimage;
        $linkImage           = REDSHOP_FRONT_IMAGES_ABSPATH . $noimage;
        $isWaterMarkImg      = \Redshop::getConfig()->getInt('WATERMARK_PRODUCT_THUMB_IMAGE');

        if (!empty($imageName)) {
            $productImageDefault = \RedshopHelperMedia::watermark(
                'product',
                $imageName,
                $width,
                $height,
                $isWaterMarkImg
            );

            if ($catProductHover) {
                $productHoverImg = \RedshopHelperMedia::watermark(
                    'product',
                    $imageName,
                    \Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_WIDTH'),
                    \Redshop::getConfig()->get('PRODUCT_HOVER_IMAGE_HEIGHT'),
                    \Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE')
                );
            }

            if (!empty($linkImageName)) {
                $linkImage = \RedshopHelperMedia::watermark('product', $linkImageName, '', '', $isWaterMarkImg);
            } else {
                $linkImage = \RedshopHelperMedia::watermark('product', $imageName, '', '', $isWaterMarkImg);
            }
        }

        if (!empty($preSelectedResult)) {
            $productImageDefault = $preSelectedResult['product_mainimg'];
            $title               = $preSelectedResult['aTitleImageResponse'];
            $linkImage           = $preSelectedResult['aHrefImageResponse'];
        }

        $commonId  = !empty($suffixId) ? $productId . '_' . $suffixId : $productId;
        $imgSource = $productImageDefault;
        $imgClass  = '';
        $divClass  = '';

        if ($catProductHover) {
            $imgSource = $productHoverImg;
            $imgClass  = 'redImagepreview';
            $divClass  = 'redhoverImagebox';
        }

        if ($isLight !== 2 && $isLight !== 1) {
            $thumbImage = \RedshopLayoutHelper::render(
                'tags.common.img',
                array(
                    'src'  => $productImageDefault,
                    'alt'  => $altText,
                    'attr' => 'id="main_image' . $commonId . '" title="' . $title . '" width="' . $width . '" height="' . $height. '"'
                ),
                '',
                \RedshopLayoutHelper::$layoutOption
            );
        } else {
            if ($isLight === 1) {
                $thumbImage = \RedshopLayoutHelper::render(
                    'tags.common.img_link',
                    array(
                        'link'     => $linkImage,
                        'linkAttr' => 'id="a_main_image' . $commonId . '" rel="myallimg" title="' . $title . '"',
                        'src'      => $imgSource,
                        'alt'      => $altText,
                        'imgAttr'  => 'id="main_image' . $commonId . '" class="' . $imgClass . '" title="' . $title . '" width="' . $width . '" height="' . $height. '"'
                    ),
                    '',
                    \RedshopLayoutHelper::$layoutOption
                );
            } elseif (\Redshop::getConfig()->getInt('PRODUCT_IS_LIGHTBOX') === 1) {
                $thumbImage = \RedshopLayoutHelper::render(
                    'tags.common.img_link',
                    array(
                        'class'    => 'modal',
                        'link'     => $linkImage,
                        'linkAttr' => 'id="a_main_image' . $commonId . '" rel="{handler: \'image\', size: {}}" title="' . $title . '"',
                        'src'      => $imgSource,
                        'alt'      => $altText,
                        'imgAttr'  => 'id="main_image' . $commonId . '" class="' . $imgClass . '" title="' . $title . '" width="' . $width . '" height="' . $height. '"'
                    ),
                    '',
                    \RedshopLayoutHelper::$layoutOption
                );
            } else {
                $thumbImage = \RedshopLayoutHelper::render(
                    'tags.common.img_link',
                    array(
                        'link'     => $link,
                        'linkAttr' => 'id="a_main_image' . $commonId . '" title="' . $title . '"',
                        'src'      => $imgSource,
                        'alt'      => $altText,
                        'imgAttr'  => 'id="main_image' . $commonId . '" class="' . $imgClass . '" title="' . $title . '" width="' . $width . '" height="' . $height. '"'
                    ),
                    '',
                    \RedshopLayoutHelper::$layoutOption
                );
            }
        }

        $thumbImage = \RedshopLayoutHelper::render(
            'tags.common.tag',
            array(
                'tag'   => 'div',
                'class' => $divClass,
                'text'  => $thumbImage
            ),
            '',
            \RedshopLayoutHelper::$layoutOption
        );

        $dispatcher->trigger('onChangeMainProductImageAlternateText', array(&$product, &$altText));

        return $thumbImage;
    }
}
