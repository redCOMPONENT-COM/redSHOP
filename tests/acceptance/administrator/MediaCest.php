<?php

use AcceptanceTester\MediaSteps as MediaSteps;
use  AcceptanceTester\CategoryManagerJoomla3Steps as CategoryManagerJoomla3Steps;
use  AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerJoomla3Steps;
class MediaCest
{

	public function __construct()
	{
		$this->faker               = Faker\Factory::create();
		$this->couponCode          = $this->faker->bothify('CouponCheckoutProductCest ?##?');
		$this->couponValueIn       = 'Total';
		$this->couponValue         = '10';
		$this->couponType          = 'Globally';
		$this->couponLeft          = '10';
		$this->categoryName        = 'Testing Category ' . $this->faker->randomNumber();
		$this->noPage              = $this->faker->randomNumber();
		$this->productName         = 'Testing Products' . rand(99, 999);
		$this->randomProductNumber = $this->faker->numberBetween(999, 9999);
		$this->randomProductPrice  = '24';
		$this->minimumPerProduct   = '1';
		$this->minimumQuantity     = 1;
		$this->maximumQuantity     = $this->faker->numberBetween(11, 100);
		$this->discountStart       = "12-12-2016";
		$this->discountEnd         = "23-05-2017";

		$this->fieldMediaAlterImage = $this->faker->bothify('fieldMediaAlterImage ?##?');
		$this->fieldMediaAlterImageEdit = $this->fieldMediaAlterImage.'Edit';

		// youtube
		$this->fieldMediaAlterYou = $this->faker->bothify('fieldMediaAlterYou ?##?');
		$this->fieldMediaAlterYouEdit= $this->fieldMediaAlterYou.'Edit';
		$this->youTube = 'wIft-t-MQuE';
	}


	public function _before(MediaSteps $client)
	{
		$client->doAdministratorLogin();
	}


	public function deleteMedia(MediaSteps $client){
		$client->comment('I want to delete all media ');
		$client->deleteAllMedia();
	}

	public function createCategoryProduct(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Category Save creation in Administrator');
		$I = new AcceptanceTester\CategoryManagerJoomla3Steps($scenario);
		$I->wantTo('Create a Category Save button');
		$I->addCategorySave($this->categoryName);

		$I = new ProductManagerJoomla3Steps($scenario);
		$I->createProductSave($this->productName, $this->categoryName, $this->randomProductNumber, $this->randomProductPrice, $this->minimumPerProduct, $this->minimumQuantity, $this->maximumQuantity, $this->discountStart, $this->discountEnd);

	}

	public function createUpdateDeleteImage(MediaSteps $client)
	{
		$client->comment('Client create update and delete Image ');
		$client->addImageMedia($this->productName,$this->fieldMediaAlterImage);
		$client->editImageMedia($this->fieldMediaAlterImage, $this->fieldMediaAlterImageEdit);
		$client->deleteAllMedia();
	}

	public function createUpdateDeleteYoutube (MediaSteps $client)
	{
		$client->comment('Client create and delete media is youtube');
		$client->addYouTubeMedia($this->productName,$this->fieldMediaAlterYou,$this->youTube);
		$client->deleteAllMedia();
	}

}