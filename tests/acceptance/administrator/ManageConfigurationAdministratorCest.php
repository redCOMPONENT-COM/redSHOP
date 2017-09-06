<?php

/**
 *
 * Configuration function
 *
 */
class ManageConfigurationAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		//setup VAT for system

		$this->country         = 'United States';
		$this->state           = 'Alabam';
		$this->vatDefault      = 'Default';
		$this->vatCalculation  = 'Webshop';
		$this->vatAfter        = 'after';
		$this->vatNumber       = 0;
		$this->calculationBase = 'billing';
		$this->requiVAT        = 'no';

		//setup Cart setting
		$this->addcart          = 'product';
		$this->allowPreOrder    = 'yes';
		$this->cartTimeOut      = $this->faker->numberBetween(100, 10000);
		$this->nableQuation     = 'no';
		$this->enabldAjax       = 'no';
		$this->defaultCart      = null;
		$this->buttonCartLead   = 'Back to current view';
		$this->onePage          = 'no';
		$this->showShippingCart = 'no';
		$this->attributeImage   = 'no';
		$this->quantityChange   = 'no';
		$this->quantityInCart   = 0;
		$this->minimunOrder     = 0;
		$this->enableQuation    = 'no';

		//product unit
		$this->volumeUnit = "Centimetres";
		$this->weightUnit = "Grams";
		$this->noDecimals = 0;

		//product templage
		$this->defaultTemplate       = "product";
		$this->defaultSort           = "Sort by product name asc";
		$this->displayOutOfAttribute = "yes";

		//image setting
		$this->showProductImage           = "no";
		$this->productDetailImage         = "yes";
		$this->attributeProductDetail     = 'no';
		$this->productImageWidth          = 200;
		$this->productImageHeight         = 200;
		$this->productImageTwoWidth       = 0;
		$this->productImageTwoHeight      = 0;
		$this->productImageThreeWidth     = 0;
		$this->productImageThreeHeight    = 0;
		$this->additionalImageWidth       = 80;
		$this->additionalImageHeight      = 80;
		$this->additionalImageTwoWidth    = 0;
		$this->additionalImageTwoHeight   = 0;
		$this->additionalImageThreeWidth  = 0;
		$this->additionalImageThreeHeight = 0;

		//setting water mark of product
		$this->waterMark                             = 'no';
		$this->waterMarkProduct                      = 'no';
		$this->waterMarkAdditional                   = 'no';
		$this->ProductHoverImage                     = 'yes';
		$this->productHoverImageWeight               = 200;
		$this->productHoverImageHeight               = 300;
		$this->enableAdditionHover                   = 'no';
		$this->additionHoverImageWidth               = 0;
		$this->additionHoverImageHeight              = 100;
		$this->productPreviewHoverImageWidth         = 100;
		$this->productPreviewHoverImageHeight        = 100;
		$this->categoryPreviewHoverImageWidth        = 100;
		$this->categoryPreviewHoverImageHeight       = 50;
		$this->attributeScrollPreviewHoverImageWidth = 50;
		$this->attributeScrollHoverImageHeight       = 50;
		$this->noAttributeScroll                     = 3;
		$this->nosubAttributeScrool                  = 3;

		// accessory products
		$this->enableIndividual   = 'no';
		$this->showAccessory      = 'no';
		$this->defaultAccessory   = "Sort by product ID asc";
		$this->maxCharacter       = 0;
		$this->accessoryEndSuffix = 0;
		$this->enterTitle         = 0;
		$this->TitleSuffix        = 0;

		//access product
		$this->accessoryThumbnailWidth       = 10;
		$this->accessoryThumbnailHeight      = 10;
		$this->accessoryThumbnailTwoHeight   = 0;
		$this->accessoryThumbnailTwoWidth    = 0;
		$this->accessoryThumbnailThreeHeight = 0;
		$this->accessoryThumbnailThreeWidth  = 0;

		//related product
		$this->twoWay                       = 'no';
		$this->child                        = "Child Product Name";
		$this->parent                       = 'no';
		$this->defaultRelated               = "Sort order by desc";
		$this->relatedProductDescriptionMax = 0;
		$this->relatedDescriptionSuffix     = 0;
		$this->relatedMaxCharacter          = 0;
		$this->relatedDescription           = 0;
		$this->relatedShortMaxCharacter     = 10;
		$this->relatedTitleMax              = 0;

		//image related product
		$this->relatedProductThumbnailWight       = 150;
		$this->relatedProductThumbnailHeight      = 100;
		$this->relatedProductThumbnailTwoWight    = 0;
		$this->relatedProductThumbnailTwoHeight   = 0;
		$this->relatedProductThumbnailThreeWight  = 0;
		$this->relatedProductThumbnailThreeHeight = 0;


	}


	public function _before(AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
	}

	public function settingWithDefault(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test used Stockroom  in Administrator');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->wantTo('Start stook room ');
		$I->featureUsedStockRoom();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

		$I->wantTo('Off stook room ');
		$I->featureOffStockRoom();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

		$I->wantTo(' Edit inline is yes ');
		$I->featureEditInLineYes();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

		$I->wantTo(' Edit inline is no ');
		$I->featureEditInLineNo();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

		$I->wantTo(' Comparison  is yes ');
		$I->featureComparisonYes();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

		$I->wantTo(' Comparison  is No ');
		$I->featureComparisonNo();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

		$I->wantTo(' Feature is No ');
		$I->featurePriceNo();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

		$I->wantTo(' Feature is yes ');
		$I->featurePriceYes();
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

		$I->wantTo(' Setup VAT ');
		$I->setupVAT($this->country, $this->state, $this->vatDefault, $this->vatCalculation, $this->vatAfter, $this->vatNumber, $this->calculationBase, $this->requiVAT);

		$I->wantTo(' Setup cart setting ');
		$I->cartSetting($this->addcart, $this->allowPreOrder, $this->enableQuation, $this->cartTimeOut, $this->enabldAjax, $this->defaultCart, $this->buttonCartLead, $this->onePage,$this->showShippingCart,$this->attributeImage,$this->quantityChange,$this->quantityInCart,$this->minimunOrder);

		$I->wantTo(' Setup product unit ');
		$I->productUnit($this->volumeUnit, $this->weightUnit, $this->noDecimals);

		$I->wantTo(' Setup product layout ');
		$I->productLayout($this->defaultTemplate, $this->defaultSort, $this->displayOutOfAttribute);

		$I->wantTo('Setup product Image setting');
		$I->productImageSetting($this->showProductImage, $this->productDetailImage, $this->attributeProductDetail,$this->productImageWidth,$this->productImageHeight
			,$this->productImageTwoWidth,$this->productImageTwoHeight,$this->productImageThreeWidth,$this->productImageThreeHeight,$this->additionalImageWidth,$this->additionalImageHeight,$this->additionalImageTwoWidth,$this->additionalImageTwoHeight,$this->additionalImageThreeWidth,$this->additionalImageThreeHeight);

		$I->wantTo('Edit Water product');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->productWaterProduct($this->waterMark , $this->waterMarkProduct,$this->waterMarkAdditional, $this->ProductHoverImage,$this->productHoverImageWeight,$this->productHoverImageHeight,$this->enableAdditionHover
			,$this->additionHoverImageWidth,$this->additionHoverImageHeight,$this->productPreviewHoverImageWidth
			, $this->productPreviewHoverImageHeight,$this->categoryPreviewHoverImageWidth,$this->categoryPreviewHoverImageHeight,$this->attributeScrollPreviewHoverImageWidth,$this->attributeScrollHoverImageHeight,$this->noAttributeScroll,$this->nosubAttributeScrool );

		$I->wantTo('product Accessory ');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->productAccessory($this->enableIndividual, $this->showAccessory,$this->defaultAccessory,$this->maxCharacter,$this->accessoryEndSuffix,$this->enterTitle,$this->TitleSuffix);

		$I->wantTo('product Image Accessory ');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->productAccessoryImage($this->accessoryThumbnailWidth, $this->accessoryThumbnailHeight,$this->accessoryThumbnailTwoHeight,$this->accessoryThumbnailTwoWidth,$this->accessoryThumbnailThreeHeight,$this->accessoryThumbnailThreeWidth);

		$I->wantTo('related product   ');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->relatedProduct($this->twoWay, $this->child,$this->parent,$this->defaultRelated,$this->relatedProductDescriptionMax,$this->relatedDescriptionSuffix,$this->relatedMaxCharacter,$this->relatedDescription,$this->relatedShortMaxCharacter,$this->relatedTitleMax);

		$I->wantTo('image related product   ');
		$I = new AcceptanceTester\ConfigurationManageJoomla3Steps($scenario);
		$I->imageRelatedProduct($this->relatedProductThumbnailWight, $this->relatedProductThumbnailHeight, $this->relatedProductThumbnailTwoWight, $this->relatedProductThumbnailTwoHeight, $this->relatedProductThumbnailThreeWight, $this->relatedProductThumbnailThreeHeight);

	}
	
}