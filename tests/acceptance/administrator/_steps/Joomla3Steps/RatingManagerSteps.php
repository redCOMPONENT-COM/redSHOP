<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\ProductCheckoutManagerJoomla3Steps;

/**
 * Class RatingManagerSteps
 * @since 3.0.2
 */
class RatingManagerSteps extends ProductCheckoutManagerJoomla3Steps
{
	/**
	 * @param $rating
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function createRating($rating)
	{
		$I = $this;
		$I->amOnPage(RatingManagerPage::$URL);
		$I->checkForPhpNoticesOrWarnings();
		$I->waitForText(RatingManagerPage::$titlePage, 10, RatingManagerPage::$h1);
		$I->waitForText(RatingManagerPage::$buttonNew, 30);
		$I->click(RatingManagerPage::$buttonNew);
		$I->waitForText(RatingManagerPage::$titlePageNew, 10, RatingManagerPage::$h1);
		$I->waitForElementVisible(RatingManagerPage::$inputTitle, 30);
		$I->fillField(RatingManagerPage::$inputTitle, $rating['title']);
		$ratingPage = new RatingManagerPage();

		if (isset($rating['numberRating']))
		{
			$I->waitForElementVisible($ratingPage->returnIdRating($rating['numberRating']), 30);
			$I->click($ratingPage->returnIdRating($rating['numberRating']));
		}

		if (isset($rating['comment']))
		{
			$I->waitForElementVisible(RatingManagerPage::$textAreaComment, 30);
			$I->fillField(RatingManagerPage::$textAreaComment, $rating['comment']);
		}

		$I->scrollTo(RatingManagerPage::$textAreaComment);

		if (isset( $rating['user']))
		{
			$I->waitForElementVisible(RatingManagerPage::$selectUser, 30);
			$I->click(RatingManagerPage::$selectUser);
			$I->waitForElementVisible(RatingManagerPage::$inputSearchUser, 30);
			$I->fillField(RatingManagerPage::$inputSearchUser, $rating['user']);
			$I->waitForElementVisible(RatingManagerPage::$searchUserFirst, 30);
			$I->click(RatingManagerPage::$searchUserFirst);
		}

		if (isset( $rating['product']))
		{
			$I->waitForElementVisible(RatingManagerPage::$selectProduct, 30);
			$I->click(RatingManagerPage::$selectProduct);
			$I->fillField(RatingManagerPage::$inputSearchProduct, $rating['product']);
			$I->waitForElementVisible(RatingManagerPage::$searchProductFirst, 30);
			$I->click(RatingManagerPage::$searchProductFirst);
		}

		if (isset( $rating['favoured']))
		{
			switch ($rating['favoured'])
			{
				case 'yes':
					$I->waitForElementVisible($ratingPage->returnIdFavoured(1), 30);
					$I->click($ratingPage->returnIdFavoured(1));
					break;

				case 'no':
					$I->waitForElementVisible($ratingPage->returnIdFavoured(0), 30);
					$I->click($ratingPage->returnIdFavoured(0));
					break;
			}
		}

		if (isset( $rating['published']))
		{
			switch ($rating['published'])
			{
				case 'yes':
					$I->waitForElementVisible($ratingPage->returnIdPublished(1), 30);
					$I->click($ratingPage->returnIdFavoured(1));
					break;

				case 'no':
					$I->waitForElementVisible($ratingPage->returnIdPublished(0), 30);
					$I->click($ratingPage->returnIdFavoured(0));
					break;
			}
		}

		$I->waitForText(RatingManagerPage::$buttonSaveClose, 10);
		$I->click(RatingManagerPage::$buttonSaveClose);
		$I->waitForText(RatingManagerPage::$messageSaveRatingSuccess, 30);
	}

	/**
	 * @param $ratingName
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function searchRating($ratingName)
	{
		$I = $this;
		$I->wantTo('Search the Product');
		$I->amOnPage(RatingManagerPage::$URL);
		$I->filterListBySearching($ratingName, RatingManagerPage::$filterSearch);
	}

	/**
	 * @param $rating
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function deleteRating($rating)
	{
		$I = $this;
		$I->searchRating($rating['product']);
		$I->waitForText(RatingManagerPage::$titlePage, 10, RatingManagerPage::$h1);
		$I->waitForElementVisible(RatingManagerPage::$firstItem, 20);
		$I->click(RatingManagerPage::$firstItem);
		$I->waitForText(RatingManagerPage::$buttonDelete, 10);
		$I->click(RatingManagerPage::$buttonDelete);
		$I->canSeeInPopup(RatingManagerPage::$messageDeleteRating);
		$I->acceptPopup();
		$I->waitForText(RatingManagerPage::$messageDeleteRatingSuccess, 10);
		$I->dontSee($rating['title']);
	}

	/**
	 * @param $rating
	 * @param $state
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function changeStateRating($rating, $state)
	{
		$I = $this;
		$I->searchRating($rating['product']);
		$I->waitForText(RatingManagerPage::$titlePage, 10, RatingManagerPage::$h1);
		$I->waitForElementVisible(RatingManagerPage::$firstItem, 20);
		$I->click(RatingManagerPage::$firstItem);

		if ($state == 'unpublish')
		{
			$I->waitForText(RatingManagerPage::$buttonUnpublish, 10);
			$I->click(RatingManagerPage::$buttonUnpublish);
			$I->waitForText(RatingManagerPage::$messageUnpublishSuccess, 10);
		}
		else
		{
			$I->waitForText(RatingManagerPage::$buttonPublish, 10);
			$I->click(RatingManagerPage::$buttonPublish);
			$I->waitForText(RatingManagerPage::$messagePublishSuccess, 30);
		}
	}

	/**
	 * @param $rating
	 * @param $categoryName
	 * @param $function
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function createRatingOnFrontEnd($rating, $categoryName, $function)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$categoryDiv, 30);
		$productFrontEndManagerPage = new FrontEndProductManagerJoomla3Page;
		$I->click($productFrontEndManagerPage->productCategory($categoryName));
		$I->waitForElement(FrontEndProductManagerJoomla3Page::$productList, 30);
		$I->click($productFrontEndManagerPage->product($rating['product']));

		$I->waitForElementVisible(FrontEndProductManagerJoomla3Page::$buttonWriteReview);
		$I->click(FrontEndProductManagerJoomla3Page::$buttonWriteReview);

		$I->executeJS(RatingManagerPage::jQueryIframe());
		$I->wait(0.5);
		$I->switchToIFrame(RatingManagerPage::$nameIframe);
		$I->waitForElementVisible(RatingManagerPage::$inputTitleFrontEnd, 30);
		$I->fillField(RatingManagerPage::$inputTitleFrontEnd, $rating['title']);
		$ratingPage = new RatingManagerPage();

		if (isset($rating['numberRating']))
		{
			$I->waitForElementVisible($ratingPage->returnIdRatingFrontEnd($rating['numberRating']), 30);
			$I->click($ratingPage->returnIdRatingFrontEnd($rating['numberRating']));
		}

		if (isset($rating['comment']))
		{
			$I->waitForElementVisible(RatingManagerPage::$textAreaCommentFrontEnd, 30);
			$I->fillField(RatingManagerPage::$textAreaCommentFrontEnd, $rating['comment']);
		}

		if($function == 'no')
		{
			if (isset($rating['userName']))
			{
				$I->waitForElementVisible(RatingManagerPage::$inputUserName, 30);
				$I->fillField(RatingManagerPage::$inputUserName, $rating['userName']);
			}

			if (isset($rating['email']))
			{
				$I->waitForElementVisible(RatingManagerPage::$inputEmail, 30);
				$I->fillField(RatingManagerPage::$inputEmail, $rating['email']);
			}
		}

		$I->waitForElementVisible(RatingManagerPage::$buttonSendReview, 10);
		$I->click(RatingManagerPage::$buttonSendReview);
		$I->waitForText(RatingManagerPage::$messageSaveRatingSuccessFrontEnd, 30);
	}

	/**
	 * @param $rating
	 * @throws Exception
	 * @since 3.0.2
	 */
	public function checkDisplayRatingOnFrontEnd($rating)
	{
		$I = $this;
		$I->amOnPage(RatingManagerPage::$urlFrontEnd);
		$I->waitForElementVisible(RatingManagerPage::$classRating, 10);
		$I->waitForText($rating['product'], 10);
		$I->waitForText($rating['title'], 10, RatingManagerPage::$titleReview2);
		$I->waitForText($rating['comment'], 10, RatingManagerPage::$commentReview2);
		$I->waitForText($rating['userName'], 10, RatingManagerPage::$fullNameReview2);
	}
}
