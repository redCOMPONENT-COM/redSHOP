<?php
/**
 * Created by PhpStorm.
 * User: nhung nguyen
 * Date: 5/25/2017
 * Time: 3:51 PM
 */

namespace AcceptanceTester;


class ConfigurationManageJoomla3Steps extends AdminManagerJoomla3Steps
{
	public function featureUsedStockRoom()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$ratingTab, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$stockRoomYes);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featureOffStockRoom()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$ratingTab, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$stockRoomNo);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}


	public function featureEditInLineYes()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$editInline, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$eidtInLineYes);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featureEditInLineNo()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$editInline, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$editInLineNo);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featureComparisonNo()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$comparisonTab, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$comparisonNo);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featureComparisonYes()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$featureSetting);
		$I->waitForElement(\ConfigurationManageJ3Page::$comparisonTab, 60);
		$I->waitForElement(\ConfigurationManageJ3Page::$stockRoomTab, 60);
		$I->click(\ConfigurationManageJ3Page::$comparisonYes);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}


	//Price

	public function featurePriceNo()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$price);
		$I->waitForElement(\ConfigurationManageJ3Page::$priceTab, 60);
		$I->click(\ConfigurationManageJ3Page::$showPriceNo);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	public function featurePriceYes()
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$price);
		$I->waitForElement(\ConfigurationManageJ3Page::$priceTab, 60);
		$I->click(\ConfigurationManageJ3Page::$showPriceYes);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
	}

	/**
	 * @param $country
	 * @param $state
	 * @param $vatDefault
	 * @param $vatCalculation
	 * @param $vatAfter
	 * @param $calculationBase
	 * @param $vatNumber
	 */
	public function setupVAT($country, $state, $vatDefault, $vatCalculation, $vatAfter, $vatNumber, $calculationBase, $requiVAT)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$price);

		$I->click(\ConfigurationManageJ3Page::$countryPrice);
		$I->waitForElement(\ConfigurationManageJ3Page::$countrySearchPrice, 5);
		$I->fillField(\ConfigurationManageJ3Page::$countrySearchPrice, $country);
		$userConfigurationPage = new \ConfigurationManageJ3Page();
		$I->waitForElement($userConfigurationPage->returnChoice($country));
		$I->click($userConfigurationPage->returnChoice($country));

		//get state
		$I->click(\ConfigurationManageJ3Page::$statePrice);
		$I->waitForElement(\ConfigurationManageJ3Page::$stateSearchPrice, 5);
		$I->fillField(\ConfigurationManageJ3Page::$stateSearchPrice, $state);
		$I->waitForElement($userConfigurationPage->returnChoice($state));
		$I->click($userConfigurationPage->returnChoice($state));

		//get default vat
		$I->click(\ConfigurationManageJ3Page::$vatGroup);
		$I->waitForElement(\ConfigurationManageJ3Page::$vatSearchGroup, 5);
		$I->fillField(\ConfigurationManageJ3Page::$vatSearchGroup, $vatDefault);
		$I->waitForElement($userConfigurationPage->returnChoice($vatDefault));
		$I->pressKey(\ConfigurationManageJ3Page::$vatGroup, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		//get vat base on
		$I->click(\ConfigurationManageJ3Page::$vatDefaultBase);
		$I->waitForElement(\ConfigurationManageJ3Page::$vatSearchDefaultBase, 5);
		$I->fillField(\ConfigurationManageJ3Page::$vatSearchDefaultBase, $vatCalculation);
		$I->waitForElement($userConfigurationPage->returnChoice($vatCalculation));
		$I->pressKey(\ConfigurationManageJ3Page::$vatDefaultBase, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		//apply vat on discount
		switch ($vatAfter) {
			case 'after':
				$I->click(\ConfigurationManageJ3Page::$applyDiscountAfter);
				break;
			case 'before':
				$I->click(\ConfigurationManageJ3Page::$applyDiscountBefore);
				break;
		}

		// value after discount
		$I->fillField(\ConfigurationManageJ3Page::$vatAfterDiscount, $vatNumber);

		//get value calculation based on
		switch ($calculationBase) {
			case 'billing':
				$I->click(\ConfigurationManageJ3Page::$calculationBaseBilling);
				break;
			case 'shipping':
				$I->click(\ConfigurationManageJ3Page::$calculationBaseShipping);
				break;
		}

		//get requi vat yesno

		switch ($requiVAT) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$vatNumberYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$vatNumberNo);
				break;
		}

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

	}

	public function cartSetting($addcart, $allowPreOrder, $enableQuation, $cartTimeOut, $enabldAjax, $defaultCart, $buttonCartLead, $onePage, $showShippingCart, $attributeImage, $quantityChange, $quantityInCart, $minimunOrder)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$cartCheckout);
		$userConfiguration = new \ConfigurationManageJ3Page();
		switch ($addcart) {
			case 'product':
				$I->click(\ConfigurationManageJ3Page::$addCartProduct);
				break;
			case 'attribute':
				$I->click(\ConfigurationManageJ3Page::$addCartAttibute);
				break;
		}
		switch ($allowPreOrder) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$allowPreOrOderYes);
				break;

			case 'no':
				$I->click(\ConfigurationManageJ3Page::$allowPreorderNo);
				break;
		}
		switch ($enableQuation) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$enableQuotationYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$enableQuotationNo);
				break;
		}

		$I->fillField(\ConfigurationManageJ3Page::$cartTimeOut, $cartTimeOut);

		switch ($enabldAjax) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$enableAjaxYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$enableAjaxNo);
				break;
		}
		//choice default cart/checkout item ID
		if ($defaultCart != null) {
			$I->click(\ConfigurationManageJ3Page::$defaultCart);
			$I->waitForElement(\ConfigurationManageJ3Page::$defaultCartSearch, 5);
			$I->fillField(\ConfigurationManageJ3Page::$defaultCartSearch, $defaultCart);
			$I->waitForElement($userConfiguration->returnchoice($defaultCart));
			$I->pressKey(\ConfigurationManageJ3Page::$defaultCart, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}

		//Choice add to cart button lead
		$I->click(\ConfigurationManageJ3Page::$buttonCartLead);
		$I->waitForElement(\ConfigurationManageJ3Page::$buttonCartSearch);
		$I->fillField(\ConfigurationManageJ3Page::$buttonCartSearch, $buttonCartLead);
		$I->waitForElement($userConfiguration->returnchoice($buttonCartLead));
		$I->pressKey(\ConfigurationManageJ3Page::$buttonCartLead, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		switch ($onePage) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$onePageYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$onePageNo);
				break;
		}
		switch ($showShippingCart) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$showShippingCartYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$showShippingCartNo);
				break;
		}

		switch ($attributeImage) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$attributeImageInCartYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$attributeImageInCartNo);
				break;
		}
		switch ($quantityChange) {
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$quantityChangeInCartYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$quantityChangeInCartNo);
				break;
		}
		$I->fillField(\ConfigurationManageJ3Page::$quantityInCart, $quantityInCart);

		$I->fillField(\ConfigurationManageJ3Page::$minimunOrderTotal, $minimunOrder);
		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function productUnit($volumeUnit, $weightUnit,$noDecimals){
		$I= $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$productTab);

		$I->click(\ConfigurationManageJ3Page::$volumeUnit);
		$I->waitForElement(\ConfigurationManageJ3Page::$volumeUnitSearch,30);
		$I->fillField(\ConfigurationManageJ3Page::$volumeUnitSearch,$volumeUnit);
		$I->pressKey(\ConfigurationManageJ3Page::$volumeUnit, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->click(\ConfigurationManageJ3Page::$weightUnit);
		$I->waitForElement(\ConfigurationManageJ3Page::$weightUnitSearch,30);
		$I->fillField(\ConfigurationManageJ3Page::$weightUnitSearch,$weightUnit);
		$I->pressKey(\ConfigurationManageJ3Page::$weightUnit, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->fillField(\ConfigurationManageJ3Page::$unitDecimal,$noDecimals);

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function productLayout($defaultTemplate , $defaultSort,$displayOutOfAttribute){
		$I= $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$productTab);

		$usePage = new \ConfigurationManageJ3Page();

		$I->click(\ConfigurationManageJ3Page::$productTemplate);
		$I->waitForElement(\ConfigurationManageJ3Page::$productTemplateSearch,30);
		$I->fillField(\ConfigurationManageJ3Page::$productTemplateSearch,$defaultTemplate);


		$I->waitForElement($usePage->returnChoice($defaultTemplate),30);
		$I->pressKey(\ConfigurationManageJ3Page::$productTemplate, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->click(\ConfigurationManageJ3Page::$productSortProduct);
		$I->waitForElement(\ConfigurationManageJ3Page::$productSortProductSearch,30);
		$I->fillField(\ConfigurationManageJ3Page::$productSortProductSearch,$defaultSort);
		$I->pressKey(\ConfigurationManageJ3Page::$productSortProduct, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		switch ($displayOutOfAttribute){
			case 'yes':
				$I->click(\ConfigurationManageJ3Page::$outOfStockAttributeDataYes);
				break;
			case 'no':
				$I->click(\ConfigurationManageJ3Page::$outOfStockAttributeDataNo);
				break;
				default;
				break;
		}

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}


	public function productImageSetting($showProductImage , $productDetailImage,$attributeProductDetail, $productImageWidth,$productImageHeight,$productImageTwoWidth,$productImageTwoHeight,$productImageThreeWidth,$productImageThreeHeight,$additionalImageWidth,$additionalImageHeight,$additionalImageTwoWidth,$additionalImageTwoHeight,$additionalImageThreeWidth,$additionalImageThreeHeight)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$productTab);

		if ($showProductImage=='no'){
			$I->click(\ConfigurationManageJ3Page::$productImageLightNo);
		}else{
			$I->click(\ConfigurationManageJ3Page::$productImageLightYes);
		}

		if($productDetailImage=='no'){
			$I->click(\ConfigurationManageJ3Page::$productDetailImageNo);
		}else{
			$I->click(\ConfigurationManageJ3Page::$productDetailImageYes);
		}

		if($attributeProductDetail=='no'){
			$I->click(\ConfigurationManageJ3Page::$attributeProductDetailNo);
		}else{
			$I->click(\ConfigurationManageJ3Page::$attributeProductDetailYes);
		}

		$I->fillField(\ConfigurationManageJ3Page::$productImageWidth,$productImageWidth);
		$I->fillField(\ConfigurationManageJ3Page::$productImageHeight,$productImageHeight);

		$I->fillField(\ConfigurationManageJ3Page::$productImageTwoWidth,$productImageTwoWidth);
		$I->fillField(\ConfigurationManageJ3Page::$productImageTwoHeight,$productImageTwoHeight);

		$I->fillField(\ConfigurationManageJ3Page::$productImageThreeWidth,$productImageThreeWidth);
		$I->fillField(\ConfigurationManageJ3Page::$productImageThreeHeight,$productImageThreeHeight);

		$I->fillField(\ConfigurationManageJ3Page::$additionalImageWidth,$additionalImageWidth);
		$I->fillField(\ConfigurationManageJ3Page::$additionalImageHeight,$additionalImageHeight);

		$I->fillField(\ConfigurationManageJ3Page::$additionalImageTwoWidth,$additionalImageTwoWidth);
		$I->fillField(\ConfigurationManageJ3Page::$additionalImageTwoHeight,$additionalImageTwoHeight);

		$I->fillField(\ConfigurationManageJ3Page::$additionalImageThreeWidth,$additionalImageThreeWidth);
		$I->fillField(\ConfigurationManageJ3Page::$additionalImageThreeHeight,$additionalImageThreeHeight);

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function productWaterProduct($waterMark , $waterMarkProduct,$waterMarkAdditional, $ProductHoverImage,$productHoverImageWeight,$productHoverImageHeight,$enableAdditionHover
		,$additionHoverImageWidth,$additionHoverImageHeight,$productPreviewHoverImageWidth
		, $productPreviewHoverImageHeight,$categoryPreviewHoverImageWidth,$categoryPreviewHoverImageHeight,$attributeScrollPreviewHoverImageWidth,$attributeScrollHoverImageHeight,$noAttributeScroll,$nosubAttributeScrool )
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$productTab);
		if ($waterMark=='yes')
		{
			$I->click(\ConfigurationManageJ3Page::$waterMarkYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$waterMarkNo);
		}

		if ($waterMarkProduct=='yes')
		{
			$I->click(\ConfigurationManageJ3Page::$waterMarkProductYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$waterMarkProductNo);
		}

		if ($waterMarkAdditional=='yes')
		{
			$I->click(\ConfigurationManageJ3Page::$waterMarkAdditionalYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$waterMarkAdditionalNo);
		}

		if ($ProductHoverImage=='yes')
		{
			$I->click(\ConfigurationManageJ3Page::$ProductHoverImageYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$ProductHoverImageNo);
		}

		$I->fillField(\ConfigurationManageJ3Page::$productHoverImageWeight,$productHoverImageWeight);
		$I->fillField(\ConfigurationManageJ3Page::$productHoverImageHeight,$productHoverImageHeight);

		if ($enableAdditionHover=='yes')
		{
			$I->click(\ConfigurationManageJ3Page::$enableAdditionHoverYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$enableAdditionHoverNo);
		}
		$I->fillField(\ConfigurationManageJ3Page::$additionHoverImageWidth,$additionHoverImageWidth);
		$I->fillField(\ConfigurationManageJ3Page::$additionHoverImageHeight,$additionHoverImageHeight);

		$I->fillField(\ConfigurationManageJ3Page::$productPreviewHoverImageWidth,$productPreviewHoverImageWidth);
		$I->fillField(\ConfigurationManageJ3Page::$productPreviewHoverImageHeight,$productPreviewHoverImageHeight);

		$I->fillField(\ConfigurationManageJ3Page::$categoryPreviewHoverImageWidth,$categoryPreviewHoverImageWidth);
		$I->fillField(\ConfigurationManageJ3Page::$categoryPreviewHoverImageHeight,$categoryPreviewHoverImageHeight);

		$I->fillField(\ConfigurationManageJ3Page::$attributeScrollPreviewHoverImageWidth,$attributeScrollPreviewHoverImageWidth);
		$I->fillField(\ConfigurationManageJ3Page::$attributeScrollPreviewHoverImageHeight,$attributeScrollHoverImageHeight);

		$I->fillField(\ConfigurationManageJ3Page::$noAttributeScroll,$noAttributeScroll);
		$I->fillField(\ConfigurationManageJ3Page::$nosubAttributeScrool,$nosubAttributeScrool);

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function productAccessory($enableIndividual, $showAccessory,$defaultAccessory,$maxCharacter,$accessoryEndSuffix,$enterTitle,$TitleSuffix)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$productTab);
		$I->click(\ConfigurationManageJ3Page::$accessoryTab);
		if($enableIndividual=='yes'){
			$I->click(\ConfigurationManageJ3Page::$accessoryYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$accessoryNo);
		}

		if($showAccessory=='yes'){
			$I->click(\ConfigurationManageJ3Page::$accessoryInBoxYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$accessoryInBoxNo);
		}

		$I->click(\ConfigurationManageJ3Page::$accessorySorting);
		$I->waitForElement(\ConfigurationManageJ3Page::$accessorySortingSearch,30);
		$I->fillField(\ConfigurationManageJ3Page::$accessorySortingSearch,$defaultAccessory);
		$I->pressKey(\ConfigurationManageJ3Page::$accessorySorting, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->fillField(\ConfigurationManageJ3Page::$maxCharacterForRelated,$maxCharacter);
		$I->fillField(\ConfigurationManageJ3Page::$accessoryDescriptionEnd,$accessoryEndSuffix);
		$I->fillField(\ConfigurationManageJ3Page::$accessoryCharacterTitle,$enterTitle);
		$I->fillField(\ConfigurationManageJ3Page::$accessorySuffix,$TitleSuffix);

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);

	}

	public function productAccessoryImage($accessoryThumbnailWidth, $accessoryThumbnailHeight,$accessoryThumbnailTwoHeight,$accessoryThumbnailTwoWidth,$accessoryThumbnailThreeHeight,$accessoryThumbnailThreeWidth)
	{
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$productTab);
		$I->click(\ConfigurationManageJ3Page::$accessoryTab);

		$I->fillField(\ConfigurationManageJ3Page::$accessoryThumbnailWidth,$accessoryThumbnailWidth);
		$I->fillField(\ConfigurationManageJ3Page::$accessoryThumbnailHeight,$accessoryThumbnailHeight);
		$I->fillField(\ConfigurationManageJ3Page::$accessoryThumbnailTwoHeight,$accessoryThumbnailTwoHeight);
		$I->fillField(\ConfigurationManageJ3Page::$accessoryThumbnailTwoWidth,$accessoryThumbnailTwoWidth);

		$I->fillField(\ConfigurationManageJ3Page::$accessoryThumbnailThreeHeight,$accessoryThumbnailThreeHeight);
		$I->fillField(\ConfigurationManageJ3Page::$accessoryThumbnailThreeWidth,$accessoryThumbnailThreeWidth);

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function relatedProduct($twoWay, $child,$parent,$defaultRelated,$relatedProductDescriptionMax,$relatedDescriptionSuffix,$relatedMaxCharacter,$relatedDescription,$relatedShortMaxCharacter,$relatedTitleMax){
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$productTab);
		$I->click(\ConfigurationManageJ3Page::$relatedTab);

		if($twoWay=='yes'){
			$I->click(\ConfigurationManageJ3Page::$twoWayRelatedProductYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$twoWayRelatedProductNo);
		}

		$I->click(\ConfigurationManageJ3Page::$childProduct);
		$I->waitForElement(\ConfigurationManageJ3Page::$childProductSearch,30);
		$I->fillField(\ConfigurationManageJ3Page::$childProductSearch,$child);
		$I->pressKey(\ConfigurationManageJ3Page::$childProduct, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		if($parent=='yes'){
			$I->click(\ConfigurationManageJ3Page::$parentProductYes);
		}else{
			$I->click(\ConfigurationManageJ3Page::$parentProductNo);
		}


		$I->fillField(\ConfigurationManageJ3Page::$relatedProductDescriptionMax,$relatedProductDescriptionMax);
		$I->fillField(\ConfigurationManageJ3Page::$relatedDescriptionSuffix,$relatedDescriptionSuffix);
		$I->fillField(\ConfigurationManageJ3Page::$relatedMaxCharacter,$relatedMaxCharacter);
		$I->fillField(\ConfigurationManageJ3Page::$relatedDescription,$relatedDescription);
		$I->fillField(\ConfigurationManageJ3Page::$relatedShortMaxCharacter,$relatedShortMaxCharacter);
		$I->fillField(\ConfigurationManageJ3Page::$relatedTitleMax,$relatedTitleMax);

		$I->click(\ConfigurationManageJ3Page::$defaultSearchRelated);
		$I->waitForElement(\ConfigurationManageJ3Page::$defaultSearchRelatedSearch,30);
		$I->fillField(\ConfigurationManageJ3Page::$defaultSearchRelatedSearch,$defaultRelated);
		$I->pressKey(\ConfigurationManageJ3Page::$defaultSearchRelated, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

	public function imageRelatedProduct($relatedProductThumbnailWight,$relatedProductThumbnailHeight,$relatedProductThumbnailTwoWight,$relatedProductThumbnailTwoHeight,$relatedProductThumbnailThreeWight,$relatedProductThumbnailThreeHeight){
		$I = $this;
		$I->amOnPage(\ConfigurationManageJ3Page::$URL);
		$I->click(\ConfigurationManageJ3Page::$productTab);
		$I->click(\ConfigurationManageJ3Page::$relatedTab);

		$I->fillField(\ConfigurationManageJ3Page::$relatedProductThumbnailWight,$relatedProductThumbnailWight);
		$I->fillField(\ConfigurationManageJ3Page::$relatedProductThumbnailHeight,$relatedProductThumbnailHeight);
		$I->fillField(\ConfigurationManageJ3Page::$relatedProductThumbnailTwoWight,$relatedProductThumbnailTwoWight);
		$I->fillField(\ConfigurationManageJ3Page::$relatedProductThumbnailTwoHeight,$relatedProductThumbnailTwoHeight);
		$I->fillField(\ConfigurationManageJ3Page::$relatedProductThumbnailThreeWight,$relatedProductThumbnailThreeWight);
		$I->fillField(\ConfigurationManageJ3Page::$relatedProductThumbnailThreeHeight,$relatedProductThumbnailThreeHeight);

		$I->click(\ConfigurationManageJ3Page::$buttonSave);
		$I->see(\ConfigurationManageJ3Page::$namePage, \ConfigurationManageJ3Page::$selectorPageTitle);
	}

}