<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

/**
 * $displayData extract
 *
 * @var   array   $displayData   Display data
 * @var   integer $productId     Product Id
 * @var   string  $main_template Template rating
 * @var   integer $mainblock     Main block
 */
extract($displayData);

// Fetching reviews
$reviews          = RedshopHelperProduct::getProductReviewList($productId);
$reviews_template = "";
$product_template = "";

if (strstr($main_template, "{product_loop_start}") && strstr($main_template, "{product_loop_end}")) {
    $product_start    = explode("{product_loop_start}", $main_template);
    $product_end      = explode("{product_loop_end}", $product_start [1]);
    $product_template = $product_end [0];

    if (strstr($main_template, "{product_loop_start}") && strstr($main_template, "{product_loop_end}")) {
        $review_start     = explode("{review_loop_start}", $product_template);
        $review_end       = explode("{review_loop_end}", $review_start [1]);
        $reviews_template = $review_end [0];
    }
}

$allImage     = array();
$product_data = '';
$reviews_all  = '';

if ($product_template != "" && $reviews_template != "" && count($reviews) > 0) {
    $product_data .= str_replace("{product_title}", '', $product_template);

    $reviews_data1 = "";
    $reviews_data2 = "";
    $reviews_data  = "";

    for ($j = 0; $j < $mainblock && $j < count($reviews); $j++) {
        $fullname  = $reviews[$j]->firstname . " " . $reviews[$j]->lastname;
        $starimage = '<img src="' . REDSHOP_MEDIA_IMAGES_ABSPATH . 'star_rating/' . $reviews[$j]->user_rating . '.gif">';

        if ($fullname != " ") {
            $displayname = $fullname;
        } else {
            $displayname = $reviews[$j]->username;
        }

        $images1  = json_decode($reviews[$j]->images);
        $imgHtml1 = '<ul>';

        foreach ($images1 as $image1) {
            if (!empty($image1)) {
                $linkImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_rating/' . $image1;

                $thumbImg1 = RedshopHelperMedia::getImagePath(
                    $image1,
                    '',
                    'thumb',
                    'product_rating',
                    Redshop::getConfig()->get('RATING_THUMB_IMAGE_WIDTH'),
                    Redshop::getConfig()->get('RATING_THUMB_IMAGE_HEIGHT'),
                    Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
                );

                $allImage[] = $linkImage;
                $imgHtml1   .= '<li><a href="' . $linkImage . '" class="group-images-' . $reviews[$j]->rating_id . '"><img src="' . $thumbImg1 . '" /></a></li>';
            }
        }

        $imgHtml1 .= '</ul>';

        $reviews_data1 = str_replace("{fullname}", $displayname, $reviews_template);
        $reviews_data1 = str_replace("{email}", $reviews[$j]->email, $reviews_data1);
        $reviews_data1 = str_replace("{company_name}", $reviews[$j]->company_name, $reviews_data1);
        $reviews_data1 = str_replace("{title}", $reviews [$j]->title, $reviews_data1);
        $reviews_data1 = str_replace("{comment}", nl2br($reviews [$j]->comment), $reviews_data1);
        $reviews_data1 = str_replace("{stars}", $starimage, $reviews_data1);
        $reviews_data1 = str_replace("{images}", $imgHtml1, $reviews_data1);
        $reviews_data1 = str_replace(
            "{reviewdate}",
            RedshopHelperDatetime::convertDateFormat($reviews [$j]->time),
            $reviews_data1
        );
        $reviews_data  .= $reviews_data1;
    }

    if ($mainblock < count($reviews)) {
        $reviews_data .= '<div style="clear:both;" class="show_reviews">';
        $reviews_data .= '<a href="javascript:showallreviews();">';
        $reviews_data .= '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'reviewarrow.gif"> ';
        $reviews_data .= JText::_('COM_REDSHOP_SHOW_ALL_REVIEWS') . '</a></div>';
    }

    $reviews_data .= '<div style="display:none;" id="showreviews" name="showreviews">';

    for ($k = $mainblock; $k < count($reviews); $k++) {
        $fullname2  = $reviews [$k]->firstname . " " . $reviews [$k]->lastname;
        $starimage2 = '<img src="' . REDSHOP_MEDIA_IMAGES_ABSPATH . 'star_rating/' . $reviews[$k]->user_rating . '.gif">';
        $images2    = json_decode($reviews[$k]->images);
        $imgHtml2   = '';
        $imgHtml2   = '<ul>';

        foreach ($images2 as $image2) {
            $linkImage  = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_rating/' . $image2;
            $allImage[] = $linkImage;
            $imgHtml2   .= '<li><img src="' . $linkImage . '" width="50%" height="50%" /></li>';
        }

        $imgHtml2 .= '<ul>';

        $fullname2     = $reviews[$k]->username;
        $reviews_data2 = str_replace("{fullname}", '', $reviews_template);
        $reviews_data2 = str_replace("{email}", $reviews[$k]->email, $reviews_data2);
        $reviews_data2 = str_replace("{company_name}", $reviews[$k]->company_name, $reviews_data2);
        $reviews_data2 = str_replace("{title}", $reviews [$k]->title, $reviews_data2);
        $reviews_data2 = str_replace("{comment}", nl2br($reviews [$k]->comment), $reviews_data2);
        $reviews_data2 = str_replace("{stars}", $starimage2, $reviews_data2);
        $reviews_data2 = str_replace("{images}", $imgHtml2, $reviews_data2);
        $reviews_data2 = str_replace(
            "{reviewdate}",
            RedshopHelperDatetime::convertDateFormat($reviews [$k]->time),
            $reviews_data2
        );
        $reviews_data  .= $reviews_data2;
    }

    $reviews_data .= '</div>';
    $reviews_all  .= $reviews_data;
}

if (strstr($main_template, "{show_all_images_rating}")) {
    $allImageHtml = '<ul>';

    foreach ($allImage as $itemImage) {
        $allImageHtml .= '<li><img src="' . $itemImage . '" width="50%" height="50%"/></li>';
    }

    $allImageHtml .= '</ul>';

    $main_template = str_replace('{show_all_images_rating}', $allImageHtml, $main_template);
}

if (strstr($main_template, "{rating_statistics}") && !empty($reviews)) {
    $main_template = str_replace(
        '{rating_statistics}',
        RedshopLayoutHelper::render('product.rating_summary', array('productId' => $productId)),
        $main_template
    );
}

$main_template = str_replace('{rating_statistics}', '', $main_template);

$product_data  = str_replace(
    "{review_loop_start}" . $reviews_template . "{review_loop_end}",
    $reviews_all,
    $product_data
);
$main_template = str_replace(
    "{product_loop_start}" . $product_template . "{product_loop_end}",
    $product_data,
    $main_template
);
echo $main_template;