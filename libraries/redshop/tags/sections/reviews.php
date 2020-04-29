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
class RedshopTagsSectionsReviews extends RedshopTagsAbstract
{
    public $tags = array('{show_all_images_rating}', '{rating_statistics}');

    public function init()
    {
    }

    public function replace()
    {
        $products = $this->data['products'];

        if (Redshop::getConfig()->get('FAVOURED_REVIEWS') != "" || Redshop::getConfig()->get('FAVOURED_REVIEWS') != 0) {
            $mainBlock = Redshop::getConfig()->get('FAVOURED_REVIEWS');
        } else {
            $mainBlock = 5;
        }

        $templateProduct = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}');

        if (!empty($templateProduct)) {
            $productTemplate = $templateProduct['template'];

            $productData = "";

            for ($i = 0; $i < count($products); $i++) {
                $productData    .= $productTemplate;
                $productData    = str_replace("{product_title}", $products[$i]->product_name, $productData);
                $templateReview = $this->getTemplateBetweenLoop(
                    '{review_loop_start}',
                    '{review_loop_end}',
                    $productData
                );

                if (!empty($templateReview)) {
                    $reviewTemplate  = $templateReview['template'];
                    $templateReview1 = '';
                    $templateReview2 = '';
                    $reviews         = $this->data['model']->getProductreviews($products[$i]->product_id);

                    if (count($reviews) > 0) {
                        for ($j = 0; $j < $mainBlock && $j < count($reviews); $j++) {
                            $reviewData1     = $this->replaceReview($reviewTemplate, $reviews[$j]);
                            $templateReview1 .= $reviewData1['template'];
                        }

                        for ($k = $mainBlock; $k < count($reviews); $k++) {
                            $reviewData2     = $this->replaceReview($reviewTemplate, $reviews[$k]);
                            $templateReview2 .= $reviewData2['template'];
                        }
                    }

                    $reviewData = RedshopLayoutHelper::render(
                        'tags.review.review_all',
                        array(
                            'templateReview1' => $templateReview1,
                            'templateReview2' => $templateReview2,
                            'mainBlock'       => $mainBlock,
                            'reviews'         => $reviews,
                            'productId'       => $products[$i]->product_id
                        ),
                        '',
                        RedshopLayoutHelper::$layoutOption
                    );

                    $productData = $templateReview['begin'] . $reviewData . $templateReview['end'];
                }
            }

            $this->template = $templateProduct['begin'] . $productData . $templateProduct['end'];
        }

        $this->addReplace('{rating_statistics}', '');
        $this->addReplace('{show_all_images_rating}', '');

        if ($this->isTagExists('{pagination}')) {
            $this->addReplace('{pagination}', '');
        }

        return parent::replace();
    }

    /**
     * Replace review
     *
     * @param   string  $reviewTemplate
     * @param   object  $review
     *
     * @return  string
     *
     * @since   __DEPLOY_VERSION
     */
    public function replaceReview($reviewTemplate, $review)
    {
        $this->replacements = array();

        $fullName = isset($review->firstname) ? $review->firstname : '';
        $fullName .= ' ' . (isset($review->lastname) ? $review->lastname : '');

        $starImage = RedshopLayoutHelper::render(
            'tags.common.img',
            array(
                'src' => REDSHOP_MEDIA_IMAGES_ABSPATH . 'star_rating/' . $review->user_rating . '.gif',
                'alt' => 'star rating'
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        if (empty(trim($fullName))) {
            $fullName = $review->username;
        }

        $imgHtml  = '';
        $allImage = array();

        if (!empty($review->images)) {
            $images = json_decode($review->images);

            foreach ($images as $image) {
                if (!empty($image)) {
                    $linkImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_rating/' . $image;

                    $thumbImg = RedshopHelperMedia::getImagePath(
                        $image,
                        '',
                        'thumb',
                        'product_rating',
                        Redshop::getConfig()->get('RATING_THUMB_IMAGE_WIDTH'),
                        Redshop::getConfig()->get('RATING_THUMB_IMAGE_HEIGHT'),
                        Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
                    );

                    $allImage[] = $linkImage;

                    $imgHtml .= RedshopLayoutHelper::render(
                        'tags.review.images',
                        array(
                            'linkImage' => $linkImage,
                            'review'    => $review,
                            'thumbImg'  => $thumbImg
                        ),
                        '',
                        RedshopLayoutHelper::$layoutOption
                    );
                }
            }
        }


        $this->replacements['{fullname}']     = $fullName;
        $this->replacements['{email}']        = $review->email;
        $this->replacements['{company_name}'] = $review->company_name;
        $this->replacements['{title}']        = $review->title;
        $this->replacements['{comment}']      = nl2br($review->comment);
        $this->replacements['{stars}']        = $starImage;
        $this->replacements['{images}']       = $imgHtml;
        $this->replacements['{reviewdate}']   = RedshopHelperDatetime::convertDateFormat($review->time);

        return array(
            'template' => $this->strReplace($this->replacements, $reviewTemplate),
            'allImage' => $allImage
        );
    }
}