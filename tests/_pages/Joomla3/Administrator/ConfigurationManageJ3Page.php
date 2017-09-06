<?php

/**
 *
 */
class ConfigurationManageJ3Page extends AdminJ3Page
{
	//nam page
	public static $namePage = "Configuration";

	public static $URL = '/administrator/index.php?option=com_redshop&view=configuration';

	public static $stockRoomYes = "#use_stockroom1-lbl";

	public static $stockRoomNo = "#use_stockroom0-lbl";

	public static $eidtInLineYes = "#inline_editing1-lbl";

	public static $editInLineNo = "#inline_editing0-lbl";

	public static $comparisonNo = "#compare_products0-lbl";

	public static $comparisonYes = "#compare_products1-lbl";

	//Price
	public static $showPriceYes = "#show_price1-lbl";

	public static $showPriceNo = "#show_price0-lbl";

	public static $countryPrice = ['id' => 's2id_default_vat_country'];

	public static $countrySearchPrice = ['id' => 's2id_autogen35_search'];

	public static $statePrice = ['id' => 's2id_default_vat_state'];

	public static $stateSearchPrice = ['id' => 's2id_autogen36_search'];

	public static $vatGroup = ['id' => 's2id_default_vat_group'];

	public static $vatSearchGroup = ['id' => 's2id_autogen37_search'];

	public static $vatDefaultBase = ['id' => 's2id_vat_based_on'];

	public static $vatSearchDefaultBase = ['id' => 's2id_autogen38_search'];

	public static $applyDiscountAfter = ['id' => 'apply_vat_on_discount0-lbl'];

	public static $applyDiscountBefore = ['id' => 'apply_vat_on_discount1-lbl'];

	public static $vatAfterDiscount = ['id' => 'vat_rate_after_discount'];

	public static $calculationBaseBilling = ['id' => 'calculate_vat_onBT-lbl'];

	public static $calculationBaseShipping = ['id' => 'calculate_vat_onST-lbl'];

	public static $vatNumberNo = ['id' => 'required_vat_number0-lbl'];

	public static $vatNumberYes = ['id' => 'required_vat_number1-lbl'];

	//xPath feature
	public static $priceTab = ['xpath' => "//h3[text()='Main Price Settings']"];

	public static $comparisonTab = ['xpath' => "//h3[text()='Comparison']"];

	public static $stockRoomTab = ['xpath' => "//h3[text()='Stockroom']"];

	public static $editInline = ['xpath' => "//h3[text()='Inline Edit']"];

	public static $ratingTab = ['xpath' => "//h3[text()='Rating']"];

	//button
	public static $featureSetting = "Feature Settings";

	public static $price = "Price";

	public static $cartCheckout = "Cart / Checkout";

	public static $productTab=['xpath'=>'//form[@id=\'adminForm\']/div/div[1]/div/div/ul/li[5]/a'];

	// cart checkout cart setting

	public static $addCartProduct = ['id' => 'individual_add_to_cart_enable0-lbl'];

	public static $addCartAttibute = ['id' => 'individual_add_to_cart_enable1-lbl'];

	public static $allowPreorderNo = ['id' => 'allow_pre_order0-lbl'];

	public static $allowPreOrOderYes = ['id' => 'allow_pre_order1-lbl'];

	public static $enableQuotationNo = ['id' => 'default_quotation_mode0-lbl'];

	public static $enableQuotationYes = ['id' => 'default_quotation_mode1-lbl'];

	public static $cartTimeOut = ['id' => 'cart_timeout'];

	public static $enableAjaxNo = ['id' => 'ajax_cart_box0-lbl'];

	public static $enableAjaxYes = ['id' => 'ajax_cart_box1-lbl'];

	public static $defaultCart = ['id' => 's2id_default_cart_checkout_itemid'];

	public static $defaultCartSearch = ['id' => 's2id_autogen42_search'];

	public static $buttonCartLead = ['id' => 's2id_addtocart_behaviour'];

	public static $buttonCartSearch = ['id' => 's2id_autogen43_search'];

	public static $onePageNo = ['id' => 'onestep_checkout_enable0-lbl'];

	public static $onePageYes = ['id' => 'onestep_checkout_enable1-lbl'];

	public static $showShippingCartNo = ['id' => 'show_shipping_in_cart0-lbl'];

	public static $showShippingCartYes = ['id' => 'show_shipping_in_cart1-lbl'];

	public static $attributeImageInCartYes = ['id' => 'wanttoshowattributeimage1-lbl'];

	public static $attributeImageInCartNo = ['id' => 'wanttoshowattributeimage0-lbl'];

	public static $quantityChangeInCartNo = ['id' => 'quantity_text_display0-lbl'];

	public static $quantityChangeInCartYes = ['id' => 'quantity_text_display1-lbl'];

	public static $quantityInCart = ['id' => 'default_quantity'];

	public static $defaultproductQuantity = ['id' => 'default_quantity_selectbox_value'];

	public static $minimunOrderTotal = ['id' => 'minimum_order_total'];

	//product tab

	//unit state
	public static $tabProduct="Product";

	public static $volumeUnit=['id'=>'s2id_default_volume_unit'];

	public static $volumeUnitSearch=['id'=>'s2id_autogen23_search'];

	public static $weightUnit=['id'=>'s2id_default_weight_unit'];

	public static $weightUnitSearch=['id'=>'s2id_autogen24_search'];

	public static $unitDecimal=['id'=>'unit_decimal'];

	//product layout
	public static $productTemplate=['id'=>'s2id_default_product_template'];

	public static $productTemplateSearch=['id'=>'s2id_autogen25_search'];

	public static $productSortProduct=['id'=>'s2id_default_product_ordering_method'];

	public static $productSortProductSearch=['id'=>'s2id_autogen26_search'];

	public static $outOfStockAttributeDataYes=['id'=>'display_out_of_stock_attribute_data1-lbl'];

	public static $outOfStockAttributeDataNo=['id'=>'display_out_of_stock_attribute_data0-lbl'];

	//image setting

	public static $productImageLightNo=['id'=>'product_is_lightbox0-lbl'];

	public static $productImageLightYes=['id'=>'product_is_lightbox1-lbl'];

	public static $productDetailImageNo=['id'=>'product_detail_is_lightbox0-lbl'];

	public static $productDetailImageYes=['id'=>'product_detail_is_lightbox1-lbl'];

	public static $attributeProductDetailNo=['id'=>'product_addimg_is_lightbox0-lbl'];

	public static $attributeProductDetailYes=['id'=>'product_addimg_is_lightbox1-lbl'];

	public static $productImageWidth=['id'=>'product_main_image'];

	public static $productImageHeight=['id'=>'product_main_image_height'];

	public static $productImageTwoWidth=['id'=>'product_main_image_2'];

	public static $productImageTwoHeight=['id'=>'product_main_image_height_2'];

	public static $productImageThreeWidth=['id'=>'product_main_image_3'];

	public static $productImageThreeHeight=['id'=>'product_main_image_height_3'];

	public static $additionalImageWidth=['id'=>'product_additional_image'];

	public static $additionalImageHeight=['id'=>'product_additional_image_height'];

	public static $additionalImageTwoWidth=['id'=>'product_additional_image_2'];

	public static $additionalImageTwoHeight=['id'=>'product_additional_image_height_2'];

	public static $additionalImageThreeWidth=['id'=>'product_additional_image_3'];

	public static $additionalImageThreeHeight=['id'=>'product_additional_image_height_3'];

	public static $waterMarkYes=['id'=>'watermark_product_image1-lbl'];

	public static $waterMarkNo=['id'=>'watermark_product_image0-lbl'];

	public static $waterMarkProductYes=['id'=>'watermark_product_thumb_image1-lbl'];

	public static $waterMarkProductNo=['id'=>'watermark_product_thumb_image0-lbl'];

	public static $waterMarkAdditionalYes=['id'=>'watermark_product_additional_image1-lbl'];

	public static $waterMarkAdditionalNo=['id'=>'watermark_product_additional_image0-lbl'];

	//when start is yes  , we will get image of product at frontend belong value we put
	public static $ProductHoverImageYes=['id'=>'product_hover_image_enable1-lbl'];

	public static $ProductHoverImageNo=['id'=>'product_hover_image_enable0-lbl'];

	public static $productHoverImageWeight=['id'=>'product_hover_image_width'];

	public static $productHoverImageHeight=['id'=>'product_hover_image_height'];

	public static $enableAdditionHoverYes=['id'=>'additional_hover_image_enable1-lbl'];

	public static $enableAdditionHoverNo=['id'=>'additional_hover_image_enable0-lbl'];

	public static $additionHoverImageWidth=['id'=>'additional_hover_image_width'];

	public static $additionHoverImageHeight=['id'=>'additional_hover_image_height'];

	public static $productPreviewHoverImageWidth=['id'=>'product_preview_image_width'];

	public static $productPreviewHoverImageHeight=['id'=>'product_preview_image_height'];

	public static $categoryPreviewHoverImageWidth=['id'=>'category_product_preview_image_width'];

	public static $categoryPreviewHoverImageHeight=['id'=>'category_product_preview_image_height'];

	public static $attributeScrollPreviewHoverImageWidth=['id'=>'attribute_scroller_thumb_width'];

	public static $attributeScrollPreviewHoverImageHeight=['id'=>'category_product_preview_image_height'];

	public static $noAttributeScroll=['id'=>'noof_thumb_for_scroller'];

	public static $nosubAttributeScrool=['id'=>'noof_subattrib_thumb_for_scroller'];

	//product accessory Products
	public static $accessoryTab=['xpath'=>'//ul[@id=\'product-paneTabs\']/li[2]/a'];

	public static $accessoryYes=['id'=>'accessory_as_product_in_cart_enable1-lbl'];

	public static $accessoryNo=['id'=>'accessory_as_product_in_cart_enable0-lbl'];

	public static $accessoryInBoxYes=['id'=>'accessory_product_in_lightbox1-lbl'];

	public static $accessoryInBoxNo=['id'=>'accessory_product_in_lightbox0-lbl'];

	public static $accessorySorting=['id'=>'s2id_default_accessory_ordering_method'];

	public static $accessorySortingSearch=['id'=>'s2id_autogen27_search'];

	public static $maxCharacterForRelated=['id'=>'accessory_product_desc_max_chars'];

	public static $accessoryDescriptionEnd=['id'=>'accessory_product_desc_end_suffix'];

	public static $accessoryCharacterTitle=['id'=>'accessory_product_title_max_chars'];

	public static $accessorySuffix=['id'=>'accessory_product_title_end_suffix'];

	//product accessory Products image setting
	public static $accessoryThumbnailWidth=['id'=>'accessory_thumb_width'];

	public static $accessoryThumbnailHeight=['id'=>'accessory_thumb_height'];

	public static $accessoryThumbnailTwoWidth=['id'=>'accessory_thumb_width_2'];

	public static $accessoryThumbnailTwoHeight=['id'=>'accessory_thumb_height_2'];

	public static $accessoryThumbnailThreeHeight=['id'=>'accessory_thumb_width_3'];

	public static $accessoryThumbnailThreeWidth=['id'=>'accessory_thumb_height_3'];

	//related product setting
	public static $relatedTab=['xpath'=>'//ul[@id=\'product-paneTabs\']/li[3]/a'];

	public static $twoWayRelatedProductNo=['id'=>'twoway_related_product0-lbl'];

	public static $twoWayRelatedProductYes=['id'=>'twoway_related_product1-lbl'];

	public static $childProduct=['id'=>'s2id_childproduct_dropdown'];

	public static $childProductSearch=['id'=>'s2id_autogen28_search'];

	public static $parentProductYes=['id'=>'purchase_parent_with_child1-lbl'];

	public static $parentProductNo=['id'=>'purchase_parent_with_child0-lbl'];

	public static $defaultSearchRelated=['id'=>'s2id_default_related_ordering_method'];

	public static $defaultSearchRelatedSearch=['id'=>'s2id_autogen29_search'];


	public static $relatedProductDescriptionMax=['id'=>'related_product_desc_max_chars'];

	public static $relatedDescriptionSuffix=['id'=>'related_product_desc_end_suffix'];

	public static $relatedMaxCharacter=['id'=>'related_product_short_desc_max_chars'];

	public static $relatedDescription=['id'=>'related_product_short_desc_end_suffix'];

	public static $relatedShortMaxCharacter=['id'=>'related_product_title_max_chars'];

	public static $relatedTitleMax=['id'=>'related_product_title_end_suffix'];


	//related product image setting

	public static $relatedProductThumbnailWight=['id'=>'related_product_thumb_width'];

	public static $relatedProductThumbnailHeight=['id'=>'related_product_thumb_height'];

	public static $relatedProductThumbnailTwoWight=['id'=>'related_product_thumb_width_2'];

	public static $relatedProductThumbnailTwoHeight=['id'=>'related_product_thumb_height_2'];

	public static $relatedProductThumbnailThreeWight=['id'=>'related_product_thumb_width_3'];

	public static $relatedProductThumbnailThreeHeight=['id'=>'related_product_thumb_height_3'];


}