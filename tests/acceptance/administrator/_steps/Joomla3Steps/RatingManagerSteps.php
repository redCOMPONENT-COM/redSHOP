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
		$I->amOnPage(RatingManagerPage::$url);
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
		$I->amOnPage(RatingManagerPage::$url);
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
		$I->checkAllResults();
		$I->waitForText(RatingManagerPage::$buttonDelete, 10);
		$I->click(RatingManagerPage::$buttonDelete);
		$I->canSeeInPopup(RatingManagerPage::$messageDeleteRating);
		$I->acceptPopup();
		$I->waitForText(RatingManagerPage::$messageDeleteRatingSuccess, 10);
		$I->dontSee($rating['title']);
	}
}