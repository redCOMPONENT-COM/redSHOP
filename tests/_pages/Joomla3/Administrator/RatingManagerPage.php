<?php
/**
 * @package     redSHOP
 * @subpackage  page
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class RatingManagerPage
 * @since 3.0.2
 */
class RatingManagerPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $URL = "administrator/index.php?option=com_redshop&view=rating";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $titlePage = "Rating Management";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $titlePageNew = "Rating: [ New ]";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $urlFrontEnd = "index.php?option=com_redshop&view=ratings";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $classRating = ".redSHOPSiteViewRatings";

	/**
	 * @var string
	 * @since
	 */
	public static $filterSearch = "#comment_filter";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $inputTitle = "#title";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $firstItem = "#cb0";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $inputTitleFrontEnd = "#jform_title";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $textAreaComment = "#comment";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $textAreaCommentFrontEnd  = "#jform_comment";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $selectUser = "#s2id_userid";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $inputSearchUser = "#s2id_autogen1_search";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $inputUserName = "#jform_username";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $inputEmail= "#jform_email";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $searchUserFirst = '//ul[@id ="select2-results-1"]/li/div';

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $selectProduct = "#s2id_product_id";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $inputSearchProduct = "#s2id_autogen2_search";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $searchProductFirst = '//ul[@id ="select2-results-2"]/li/div';

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $buttonSendReview = "//input[@value='Send Review']";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $messageSaveRatingSuccess = "Rating details saved";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $messageSaveRatingSuccessFrontEnd = "Email has been sent successfully";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $messageDeleteRating = "Are you sure you want to delete these rating?";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $messageDeleteRatingSuccess = "Rating detail deleted successfully";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $messageUnpublishSuccess = 'unpublished successfully';

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $messagePublishSuccess = 'published successfully';

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $titleReview2 = "(//div[@id='reviews_title'])[2]";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $commentReview2 = "(//div[@id='reviews_comment'])[2]";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $fullNameReview2 = "(//div[@id='reviews_fullname'])[2]";

	/**
	 * @param $number
	 * @return string
	 * @since 3.0.2
	 */
	public function returnIdRating($number)
	{
		return "#user_rating" . $number;
	}

	/**
	 * @param $number
	 * @return string
	 * @since 3.0.2
	 */
	public function returnIdFavoured($number)
	{
		return "#favoured" . $number;
	}

	/**
	 * @param $number
	 * @return string
	 * @since 3.0.2
	 */
	public function returnIdPublished($number)
	{
		return "#published" . $number;
	}

	/**
	 * @param $number
	 * @return string
	 * @since 3.0.2
	 */
	public function returnIdRatingFrontEnd($number)
	{
		return '//label[@for ="jform_user_rating'.$number.'"]';
	}

}
