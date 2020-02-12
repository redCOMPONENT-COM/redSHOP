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
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsReview extends RedshopTagsAbstract
{
	public $tags = array('{show_all_images_rating}', '{rating_statistics}');

	public function init()
	{
	}

	public function replace()
	{
		$reviews          = RedshopHelperProduct::getProductReviewList($this->data['productId']);
		$reviewsTemplate = "";
		$productTemplate = "";

		$templateLoopProduct = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}');

		if (!empty($templateLoopProduct))
		{
			$productTemplate = $templateLoopProduct['template'];

			$templateLoopReview = $this->getTemplateBetweenLoop('{review_loop_start}', '{review_loop_end}');

			if (!empty($templateLoopReview))
			{
				$reviewsTemplate = $templateLoopReview['template'];
			}
		}

		$allImage     = array();
		$productData = '';
		$reviewsAll  = '';

		if ($productTemplate != "" && $reviewsTemplate != "" && count($reviews) > 0)
		{
			$productData .= str_replace("{product_title}", '', $productTemplate);
			$templateReview1 = "";
			$templateReview2 = "";
			$reviewsData  = "";

			for ($j = 0; $j < $this->data['mainBlock'] && $j < count($reviews); $j++)
			{
				$reviewsData1 = $this->replaceReview($reviewsTemplate, $reviews[$j]);

				$templateReview1 .= $reviewsData1['template'];
				$allImage = array_merge($allImage, $reviewsData1['allImage']);
			}

			for ($k = $this->data['mainBlock']; $k < count($reviews); $k++)
			{
				$reviewsData2 = $this->replaceReview($reviewsTemplate, $reviews[$k]);

				$templateReview2 .= $reviewsData2['template'];
				$allImage = array_merge($allImage, $reviewsData2['allImage']);
			}

			$reviewsAll = RedshopLayoutHelper::render(
				'tags.review.review_all',
				array(
					'templateReview1' => $templateReview1,
					'templateReview2' => $templateReview2,
					'mainBlock' => $this->data['mainBlock'],
					'reviews' => $reviews
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);
		}

		if ($this->isTagExists('{show_all_images_rating}'))
		{
			$allImageHtml = RedshopLayoutHelper::render(
				'tags.review.show_all_image',
				array(
					'images' => $allImage
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->addReplace('{show_all_images_rating}', $allImageHtml);
		}

		$ratingStatiticsHtml = '';

		if ($this->isTagExists('{rating_statistics}') && !empty($reviews))
		{
			$ratingStatiticsHtml = $this->replaceRatingStatistics();
		}

		$this->addReplace('{rating_statistics}', $ratingStatiticsHtml);

		$productData    = str_replace("{review_loop_start}" . $reviewsTemplate . "{review_loop_end}", $reviewsAll, $productData);
		$this->template = str_replace("{product_loop_start}" . $productTemplate . "{product_loop_end}", $productData, $this->template);

		return parent::replace();
	}

	public function replaceReview($reviewTemplate, $review)
	{
		$this->replacements = array();
		$fullName           = $review->firstname . " " . $review->lastname;

		$starImage = RedshopLayoutHelper::render(
			'tags.common.img',
			array(
				'src' => REDSHOP_MEDIA_IMAGES_ABSPATH . 'star_rating/' . $review->user_rating . '.gif',
				'alt' => 'star rating'
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		if ($fullName != " ")
		{
			$displayName = $fullName;
		}
		else
		{
			$displayName = $review->username;
		}

		$images1  = json_decode($review->images);
		$imgHtml1 = '';
		$allImage = array();

		foreach ($images1 as $image1)
		{
			if (!empty($image1))
			{
				$linkImage  = REDSHOP_FRONT_IMAGES_ABSPATH . 'product_rating/' . $image1;

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

				$imgHtml1 .= RedshopLayoutHelper::render(
					'tags.review.images',
					array(
						'linkImage' => $linkImage,
						'review' => $review,
						'thumbImg' => $thumbImg1
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);
			}
		}

		$this->replacements['{fullname}'] = $displayName;
		$this->replacements['{email}'] = $review->email;
		$this->replacements['{company_name}'] = $review->company_name;
		$this->replacements['{title}'] = $review->title;
		$this->replacements['{comment}'] = nl2br($review->comment);
		$this->replacements['{stars}'] = $starImage;
		$this->replacements['{images}'] = $imgHtml1;
		$this->replacements['{reviewdate}'] = RedshopHelperDatetime::convertDateFormat($review->time);

		return array(
			'template' => $this->strReplace($this->replacements, $reviewTemplate),
			'allImage' => $allImage
		);
	}

	public function replaceRatingStatistics()
	{
		$statistics = RedshopHelperProduct::statisticsRatingProduct($this->data['productId']);
		$star = array();
		$totalRating  = 0;
		$avg          = 0;

		foreach ($statistics as $statistic)
		{
			$star[$statistic->user_rating] = $statistic->percent;
			$totalRating                  += $statistic->count;
			$avg                          += $statistic->user_rating * $statistic->count;
		}

		$avg          = (is_numeric(number_format($avg  / $totalRating, 1))) ? number_format($avg  / $totalRating, 1) : 0;

		return RedshopLayoutHelper::render(
				'tags.review.rating_statistics',
				array(
					'avg' => $avg,
					'totalRating' => $totalRating,
					'star' => $star
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);
	}
}