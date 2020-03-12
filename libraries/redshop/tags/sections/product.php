<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2020 - 2021 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsProduct extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $tags = [
		'{back_link}',
		'{navigation_link_right}',
		'{navigation_link_left}',
		'{product_size}',
		'{product_length}',
		'{product_length_lbl}',
		'{product_width_lbl}',
		'{product_width}',
		'{product_height_lbl}',
		'{product_height}',
		'{product_diameter_lbl}',
		'{diameter}',
		'{product_volume}',
		'{product_volume_lbl}',
		'{print}',
		'{associate_tag}',
		'{product_name}',
		'{product_id_lbl}',
		'{product_number_lbl}',
		'{product_id}',
		'{product_s_desc}',
		'{product_desc}',
		'{view_full_size_image_lbl}',
		'{zoom_image}',
		'{product_category_list}',
		'{manufacturer_image}',
		'{product_weight}',
		'{product_weight_lbl}',
		'{update_date}',
		'{publish_date}',
		'{manufacturer_link}',
		'{manufacturer_product_link}',
		'{manufacturer_name}',
		'{supplier_name}',
		'{delivery_time_lbl}',
		'{product_delivery_time}',
		'{facebook_like_button}',
		'{googleplus1}',
		'{bookmark}',
		'{more_videos}',
		'{more_documents}',
		'{product_preview_img}',
		'{form_rating_without_lightbox}',
		'{form_rating}',
		'{product_rating_summary}',
		'{product_rating}',
		'{ask_question_about_product_without_lightbox}',
		'{form_rating_without_link}',
		'{component_heading}',
		'{discount_calculator}'
	];

	/**
	 * @var    \JDatabaseDriver
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $db;

	/**
	 * @var    integer
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $itemId;

	/**
	 * @var    \JInput
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $input;

	/**
	 * @var    object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $product;

	/**
	 * @var    array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public $infoTagImg;

	/**
	 * Init
	 *
	 * @return  mixed
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function init()
	{
		$this->db           = JFactory::getDbo();
		$this->input        = JFactory::getApplication()->input;
		$this->itemId       = $this->input->getInt('Itemid');
		$this->product      = $this->data['data'];
		$this->optionLayout = RedshopLayoutHelper::$layoutOption;
		$this->infoTagImg   = $this->getWidthHeight(
			$this->template,
			'product_thumb_image',
			'PRODUCT_MAIN_IMAGE_HEIGHT',
			'PRODUCT_MAIN_IMAGE'
		);
	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replace()
	{
		\JPluginHelper::importPlugin('redshop_product');
		$dispatcher    = \RedshopHelperUtility::getDispatcher();
		$isViewProduct = !empty($this->data['isViewProduct']) ? true : false;
		$print         = $this->input->getBool('print', false);
		$url           = JURI::base();
		$uri           = JURI::getInstance();
		$Scheme        = $uri->getScheme();
		$user          = JFactory::getUser();
		$document      = JFactory::getDocument();

		//Replace Product price when config enable discount is "No"
		if (Redshop::getConfig()->getInt('DISCOUNT_ENABLE') == 0) {
			$this->template = str_replace('{product_old_price}', '', $this->template);
		}

		if ($isViewProduct) {
			$this->template = RedshopLayoutHelper::render(
					'tags.product.heading',
					[
						'pageheading'    => $this->db->escape($this->product->pageheading),
						'params'         => $this->data['params'],
						'pageHeadingTag' => $this->db->escape($this->data['pageHeadingTag'])
					],
					'',
					$this->optionLayout
				) . $this->product->event->afterDisplayTitle . $this->product->event->beforeDisplayProduct . $this->template;
		}

		/*
		 * Replace Discount Calculator Tag
		 */

		if ($this->product->use_discount_calc) {
			// Get discount calculator Template
			$this->addReplace('{discount_calculator}', $this->data['caclulatorTemplate']);
		} else {
			$this->addReplace('{discount_calculator}', '');
		}

		if (Redshop::getConfig()->getInt('COMPARE_PRODUCTS') === 0) {
			$this->addReplace('{compare_products_button}', '');
			$this->addReplace('{compare_product_div}', '');
		}

		$this->addReplace('{component_heading}', $this->db->escape($this->product->product_name));

		if ($this->isTagExists('{back_link}')) {
			$backLink = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => htmlentities($_SERVER['HTTP_REFERER']),
					'content' => JText::_('COM_REDSHOP_BACK')
				],
				'',
				$this->optionLayout
			);

			$this->addReplace('{back_link}', $backLink);
		}

		$returnToCategoryLink = strstr($this->template, '{returntocategory_link}');
		$returnToCategoryName = strstr($this->template, '{returntocategory_name}');
		$returnToCategoryStr  = strstr($this->template, '{returntocategory}');

		if ($returnToCategoryLink || $returnToCategoryName || $returnToCategoryStr) {
			$returnCatLink    = '';
			$returnToCategory = '';

			if ($this->product->category_id) {
				$returnCatLink = JRoute::_(
					'index.php?option=com_redshop&view=category&layout=detail&cid=' . $this->product->category_id .
					'&Itemid=' . $this->itemId
				);

				$returnToCategory = RedshopLayoutHelper::render(
					'tags.common.link',
					[
						'link'    => $returnCatLink,
						'content' => Redshop::getConfig()->get(
								'DAFULT_RETURN_TO_CATEGORY_PREFIX'
							) . " " . $this->product->category_name
					],
					'',
					$this->optionLayout
				);
			}

			$this->replacements['{returntocategory_link}'] = $returnCatLink;
			$this->replacements['{returntocategory_name}'] = $this->product->category_name;
			$this->replacements['{returntocategory}']      = $returnToCategory;
		}

		if ($this->isTagExists('{navigation_link_right}') || $this->isTagExists('{navigation_link_left}')) {
			$this->replaceNavigationNextPrev();
		}

		/*
		 * product size variables
		 */
		$productVolume = RedshopLayoutHelper::render(
			'tags.product.size',
			[
				'product' => $this->product
			],
			'',
			$this->optionLayout
		);

		$this->addReplace('{product_size}', $productVolume);

		if (Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT')) {
			$productUnit = RedshopLayoutHelper::render(
				'tags.common.tag',
				[
					'tag'   => 'span',
					'class' => 'product_unit_variable',
					'text'  => Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT')
				],
				'',
				$this->optionLayout
			);
		} else {
			$productUnit = '';
		}

		// Product length
		if ($this->product->product_length > 0) {
			$this->addReplace('{product_length_lbl}', JText::_('COM_REDSHOP_PRODUCT_LENGTH_LBL'));

			$insertStr = RedshopHelperProduct::redunitDecimal($this->product->product_length) . "&nbsp" . $productUnit;
			$this->addReplace('{product_length}', $insertStr);
		} else {
			$this->addReplace('{product_length_lbl}', '');
			$this->addReplace('{product_length}', '');
		}

		// Product width
		if ($this->product->product_width > 0) {
			$insertStr = RedshopHelperProduct::redunitDecimal($this->product->product_width) . "&nbsp" . $productUnit;
			$this->addReplace('{product_width_lbl}', JText::_('COM_REDSHOP_PRODUCT_WIDTH_LBL'));
			$this->addReplace('{product_width}', $insertStr);
		} else {
			$this->addReplace('{product_width_lbl}', '');
			$this->addReplace('{product_width}', '');
		}

		// Product Height
		if ($this->product->product_height > 0) {
			$insertStr = RedshopHelperProduct::redunitDecimal($this->product->product_height) . "&nbsp" . $productUnit;
			$this->addReplace('{product_height_lbl}', JText::_('COM_REDSHOP_PRODUCT_HEIGHT_LBL'));
			$this->addReplace('{product_height}', $insertStr);
		} else {
			$this->addReplace('{product_height_lbl}', '');
			$this->addReplace('{product_height}', '');
		}

		// Product Diameter
		if ($this->product->product_diameter > 0) {
			$this->addReplace('{product_diameter_lbl}', JText::_('COM_REDSHOP_PRODUCT_DIAMETER_LBL'));
			$this->addReplace(
				'{diameter}',
				RedshopHelperProduct::redunitDecimal(
					$this->product->product_diameter
				) . "&nbsp" . $productUnit
			);
		} else {
			$this->addReplace('{product_diameter_lbl}', '');
			$this->addReplace('{diameter}', '');
		}

		// Product Volume
		$productVolumeUnit = RedshopLayoutHelper::render(
			'tags.common.tag',
			[
				'tag'   => 'span',
				'class' => 'product_unit_variable',
				'text'  => Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . "3"
			],
			'',
			$this->optionLayout
		);

		if ($this->product->product_volume > 0) {
			$insertStr = JText::_('COM_REDSHOP_PRODUCT_VOLUME_LBL') . JText::_('COM_REDSHOP_PRODUCT_VOLUME_UNIT');
			$this->addReplace('{product_volume_lbl}', $insertStr);

			$insertStr = RedshopHelperProduct::redunitDecimal(
					$this->product->product_volume
				) . "&nbsp" . $productVolumeUnit;
			$this->addReplace('{product_volume}', $insertStr);
		} else {
			$this->addReplace('{product_volume}', '');
			$this->addReplace('{product_volume_lbl}', '');
		}

		// Replace Product Template
		if ($print) {
			$onClick = 'onclick="window.print();"';
		} else {
			$printUrl = $url . "index.php?option=com_redshop&view=product&pid=" . $this->product->product_id;
			$printUrl .= "&cid=" . $this->product->category_id . "&print=1&tmpl=component&Itemid=" . $this->itemId;
			$onClick  = 'onclick="window.open(\'' . $printUrl . '\',\'mywindow\',\'scrollbars=1\',\'location=1\')"';
		}

		$printTag = RedshopLayoutHelper::render(
			'tags.common.print',
			[
				'onClick' => $onClick
			],
			'',
			$this->optionLayout
		);

		// Associate_tag display update
		$assTag = '';

		if (RedshopHelperUtility::isRedProductFinder()) {
			$associateTag = RedshopHelperProduct::getassociatetag($this->product->product_id);

			for ($k = 0, $kn = count($associateTag); $k < $kn; $k++) {
				if ($associateTag[$k] != '') {
					$assTag .= $associateTag[$k]->type_name . " : " . $associateTag[$k]->tag_name . "<br/>";
				}
			}
		}

		$this->template = RedshopHelperTax::replaceVatInformation($this->template);

		$this->addReplace('{associate_tag}', $assTag);
		$this->addReplace('{print}', $printTag);
		$this->addReplace('{product_name}', $this->product->product_name);
		$this->addReplace('{product_id_lbl}', JText::_('COM_REDSHOP_PRODUCT_ID_LBL'));
		$this->addReplace('{product_number_lbl}', JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'));
		$this->addReplace('{product_id}', $this->product->product_id);
		$this->addReplace('{product_s_desc}', htmlspecialchars_decode($this->product->product_s_desc));
		$this->addReplace('{product_desc}', htmlspecialchars_decode($this->product->product_desc));
		$this->addReplace('{view_full_size_image_lbl}', JText::_('COM_REDSHOP_VIEW_FULL_SIZE_IMAGE_LBL'));

		if ($this->isTagExists('{zoom_image}')) {
			$sendLink = $url . 'components/com_redshop/assets/images/product/' . $this->product->product_full_image;

			$sendImage = RedshopLayoutHelper::render(
				'tags.product.zoom_image',
				[
					'sendLink' => $sendLink,
					'product'  => $this->product
				],
				'',
				$this->optionLayout
			);

			$this->addReplace('{zoom_image}', $sendImage);
		}

		if ($this->isTagExists('{product_category_list}')) {
			$prodCats = RedshopHelperProduct::getProductCaterories($this->product->product_id, 1);

			$pCats = RedshopLayoutHelper::render(
				'tags.product.category_list',
				[
					'prodCats' => $prodCats
				],
				'',
				$this->optionLayout
			);

			$this->addReplace('{product_category_list}', $pCats);
		}

		if ($this->isTagExists('{manufacturer_image}')) {
			$this->replaceManufactureImage();
		}

		$productWeightUnit = RedshopLayoutHelper::render(
			'tags.common.tag',
			[
				'tag'   => 'span',
				'class' => 'product_unit_variable',
				'text'  => Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT')
			],
			'',
			$this->optionLayout
		);

		if ($this->product->weight > 0) {
			$insertStr = RedshopHelperProduct::redunitDecimal(
					$this->product->weight
				) . "&nbsp;" . $productWeightUnit;
			$this->addReplace('{product_weight}', $insertStr);
			$this->addReplace('{product_weight_lbl}', JText::_('COM_REDSHOP_PRODUCT_WEIGHT_LBL'));
		} else {
			$this->addReplace('{product_weight}', '');
			$this->addReplace('{product_weight_lbl}', '');
		}

		$this->template = RedshopHelperStockroom::replaceStockroomAmountDetail(
			$this->template,
			$this->product->product_id
		);

		$this->addReplace(
			'{update_date}',
			RedshopHelperDatetime::convertDateFormat(strtotime($this->product->update_date))
		);

		if ($this->product->publish_date != '0000-00-00 00:00:00') {
			$this->addReplace(
				'{publish_date}',
				RedshopHelperDatetime::convertDateFormat(strtotime($this->product->publish_date))
			);
		} else {
			$this->addReplace('{publish_date}', '');
		}

		/*
		 * Conditional tag
		 * if product on discount : Yes
		 * {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
		 * NO : // OUTPUT : Display blank
		 */
		$this->template = RedshopHelperProduct::getProductOnSaleComment($this->product, $this->template);

		/*
		 * Conditional tag
		 * if product on discount : Yes
		 * {if product_special} This is a special product {product_special end if} // OUTPUT : This is a special product
		 * NO : // OUTPUT : Display blank
		 */
		$this->template   = RedshopHelperProduct::getSpecialProductComment($this->product, $this->template);
		$manufacturerLink = RedshopLayoutHelper::render(
			'tags.common.link',
			[
				'link'    => JRoute::_(
					'index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $this->product->manufacturer_id .
					'&Itemid=' . $this->itemId
				),
				'class'   => 'btn btn-primary manufacturer_link',
				'content' => JText::_("COM_REDSHOP_VIEW_MANUFACTURER")
			],
			'',
			$this->optionLayout
		);

		$manufacturerPLink = RedshopLayoutHelper::render(
			'tags.common.link',
			[
				'link'    => JRoute::_(
					'index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $this->product->manufacturer_id .
					'&Itemid=' . $this->itemId
				),
				'class'   => 'btn btn-primary manufacturer_product_link',
				'content' => JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS")
			],
			'',
			$this->optionLayout
		);

		$this->addReplace('{manufacturer_link}', $manufacturerLink);
		$this->addReplace('{manufacturer_product_link}', $manufacturerPLink);
		$this->addReplace('{manufacturer_name}', $this->product->manufacturer_name);

		$supplierName = '';

		if ($this->product->supplier_id) {
			$supplierName = RedshopEntitySupplier::getInstance($this->product->supplier_id)->getItem()->name;
		}

		$this->addReplace('{supplier_name}', $supplierName);

		if ($this->isTagExists('{product_delivery_time}')) {
			$productDeliveryTime = RedshopHelperProduct::getProductMinDeliveryTime($this->product->product_id);

			if ($productDeliveryTime != "") {
				$this->addReplace('{delivery_time_lbl}', JText::_('COM_REDSHOP_DELIVERY_TIME'));
				$this->addReplace('{product_delivery_time}', $productDeliveryTime);
			} else {
				$this->addReplace('{delivery_time_lbl}', '');
				$this->addReplace('{product_delivery_time}', '');
			}
		}

		// Facebook I like Button
		if ($this->isTagExists('{facebook_like_button}')) {
			$facebookLink = $uri->toString();
			$facebookLink = urlencode(JFilterOutput::cleanText($facebookLink));
			$facebookLike = RedshopLayoutHelper::render(
				'tags.product.facebook_like_button',
				[
					'Scheme'       => $Scheme,
					'facebookLink' => $facebookLink
				],
				'',
				$this->optionLayout
			);
			$this->addReplace('{facebook_like_button}', $facebookLike);
			$siteName = JFactory::getConfig()->get('sitename');

			$document->setMetaData("og:url", JFilterOutput::cleanText($facebookLink));
			$document->setMetaData("og:type", "product");
			$document->setMetaData("og:site_name", $siteName);
		}

// Google I like Button
		if ($this->isTagExists('{googleplus1}')) {
			JHTML::script('https://apis.google.com/js/plusone.js');
			$this->addReplace('{googleplus1}', '<g:plusone></g:plusone>');
		}

		if (strstr($this->template, "{bookmark}")) {
			$bookMark = RedshopLayoutHelper::render(
				'tags.product.bookmark',
				['Scheme' => $Scheme],
				'',
				$this->optionLayout
			);

			$this->addReplace('{bookmark}', $bookMark);
		}

		//  Extra field display
		$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(
			RedshopHelperExtrafields::SECTION_PRODUCT,
			null
		);
		$this->template = RedshopHelperProductTag::getExtraSectionTag(
			$extraFieldName,
			$this->product->product_id,
			"1",
			$this->template
		);

		// More images
		$this->replaceWrapper();

		if (strstr($this->template, "{child_products}")) {
			$parentProductId = $this->product->product_id;

			if ($this->product->product_parent_id != 0) {
				$parentProductId = RedshopHelperProduct::getMainParentProduct($this->product->product_id);
			}

			$frmChild = "";

			if ($parentProductId != 0) {
				$productInfo = \Redshop\Product\Product::getProductById($parentProductId);

				// Get child products
				$childProducts = Redshop\Product\Product::getAllChildProductArrayList(0, $parentProductId);

				if (!empty($childProducts)) {
					$childProducts = array_merge(array($productInfo), $childProducts);

					$displayText = (Redshop::getConfig()->get(
							'CHILDPRODUCT_DROPDOWN'
						) == "product_number") ? "product_number" : "product_name";

					$selected = array($this->product->product_id);
					$lists    = [];

					$lists['product_child_id'] = JHtml::_(
						'select.genericlist',
						$childProducts,
						'pid',
						'class="inputbox" size="1"  onchange="document.frmChild.submit();"',
						'product_id',
						$displayText,
						$selected
					);

					$frmChild .= "<form name='frmChild' method='post' action=''>";
					$frmChild .= "<div class='product_child_product'>" . JText::_(
							'COM_REDSHOP_CHILD_PRODUCTS'
						) . "</div>";
					$frmChild .= "<div class='product_child_product_list'>" . $lists ['product_child_id'] . "</div>";
					$frmChild .= "<input type='hidden' name='view' value='product'>";
					$frmChild .= "<input type='hidden' name='task' value='gotochild'>";
					$frmChild .= "<input type='hidden' name='option' value='com_redshop'>";
					$frmChild .= "<input type='hidden' name='Itemid' value='" . $this->itemId . "'>";
					$frmChild .= "</form>";
				}
			}

			$this->template = str_replace("{child_products}", $frmChild, $this->template);
		}

// Checking for child products
		$childProduct = RedshopHelperProduct::getChildProduct($this->product->product_id);

		if (!empty($childProduct)) {
			if (Redshop::getConfig()->get('PURCHASE_PARENT_WITH_CHILD') == 1) {
				$isChilds      = false;
				$attributesSet = array();

				if ($this->product->attribute_set_id > 0) {
					$attributesSet = \Redshop\Product\Attribute::getProductAttribute(
						0,
						$this->product->attribute_set_id,
						0,
						1
					);
				}

				$attributes = \Redshop\Product\Attribute::getProductAttribute($this->product->product_id);
				$attributes = array_merge($attributes, $attributesSet);
			} else {
				$isChilds   = true;
				$attributes = array();
			}
		} else {
			$isChilds      = false;
			$attributesSet = array();

			if ($this->product->attribute_set_id > 0) {
				$attributesSet = \Redshop\Product\Attribute::getProductAttribute(
					0,
					$this->product->attribute_set_id,
					0,
					1
				);
			}

			$attributes = \Redshop\Product\Attribute::getProductAttribute($this->product->product_id);
			$attributes = array_merge($attributes, $attributesSet);
		}

		$attributeTemplate = \Redshop\Template\Helper::getAttribute($this->template);

		// Check product for not for sale
		$this->template = RedshopHelperProduct::getProductNotForSaleComment(
			$this->product,
			$this->template,
			$attributes
		);

		// Replace product in stock tags
		$this->template = Redshop\Product\Stock::replaceInStock(
			$this->product->product_id,
			$this->template,
			$attributes,
			$attributeTemplate
		);

		// Product attribute  Start
		$totalAtt = count($attributes);

		$this->template = RedshopTagsReplacer::_(
			'attributes',
			$this->template,
			array(
				'productId'         => $this->product->product_id,
				'accessoryId'       => 0,
				'relatedProductId'  => 0,
				'attributes'        => $attributes,
				'attributeTemplate' => $attributeTemplate,
				'isChild'           => $isChilds
			)
		);

// Product attribute  End

		$prNumber                    = $this->product->product_number;
		$preSelectedResult           = array();
		$moreImageResponse           = '';
		$propertyData                = '';
		$subPropertyData             = '';
		$attributeProductStockStatus = null;
		$selectedPropertyId          = 0;
		$selectedSubPropertyId       = 0;

		if (!empty($attributes) && !empty($attributeTemplate)) {
			for ($a = 0, $an = count($attributes); $a < $an; $a++) {
				$selectedId = array();
				$property   = RedshopHelperProduct_Attribute::getAttributeProperties(0, $attributes[$a]->attribute_id);

				if ($attributes[$a]->text != "" && count($property) > 0) {
					for ($i = 0, $in = count($property); $i < $in; $i++) {
						if ($property[$i]->setdefault_selected) {
							$selectedId[] = $property[$i]->property_id;
							$propertyData .= $property[$i]->property_id;

							if ($i != (count($property) - 1)) {
								$propertyData .= '##';
							}
						}
					}

					if (count($selectedId) > 0) {
						$selectedPropertyId = $selectedId[count($selectedId) - 1];
						$subProperty        = RedshopHelperProduct_Attribute::getAttributeSubProperties(
							0,
							$selectedPropertyId
						);
						$selectedId         = array();
						$countSubProperty   = count($subProperty);
						if ($countSubProperty > 0) {
							for ($sp = 0; $sp < $countSubProperty; $sp++) {
								if ($subProperty[$sp]->setdefault_selected) {
									$selectedId[]    = $subProperty[$sp]->subattribute_color_id;
									$subPropertyData .= $subProperty[$sp]->subattribute_color_id;

									if ($sp != (count($subProperty) - 1)) {
										$subPropertyData .= '##';
									}
								}
							}
						}

						if (count($selectedId) > 0) {
							$subPropertyData       = implode('##', $selectedId);
							$selectedSubPropertyId = $selectedId[count($selectedId) - 1];
						}
					}
				}
			}

			$get                     = [];
			$get['product_id']       = $this->product->product_id;
			$get['main_imgwidth']    = $this->infoTagImg['width'];
			$get['main_imgheight']   = $this->infoTagImg['height'];
			$get['property_data']    = $propertyData;
			$get['subproperty_data'] = $subPropertyData;
			$get['property_id']      = $selectedPropertyId;
			$get['subproperty_id']   = $selectedSubPropertyId;
			$pluginResults           = array();

			// Trigger plugin to get merge images.
			$dispatcher->trigger('onBeforeImageLoad', array($get, &$pluginResults));

			$preSelectedResult = RedshopHelperProductTag::displayAdditionalImage(
				$this->product->product_id,
				0,
				0,
				$selectedPropertyId,
				$selectedSubPropertyId,
				$this->infoTagImg['width'],
				$this->infoTagImg['height'],
				'product'
			);

			if (isset($pluginResults['mainImageResponse'])) {
				$preSelectedResult['product_mainimg'] = $pluginResults['mainImageResponse'];
			}

			$productAvailabilityDate = strstr($this->template, "{product_availability_date}");
			$stockNotifyFlag         = strstr($this->template, "{stock_notify_flag}");
			$stockStatus             = strstr($this->template, "{stock_status");

			if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus) {
				$attributeProductStockStatus = RedshopHelperProduct::getproductStockStatus(
					$this->product->product_id,
					$totalAtt,
					$selectedPropertyId,
					$selectedSubPropertyId
				);
			}

			$moreImageResponse = $preSelectedResult['response'];

			if (!is_null($preSelectedResult['pr_number']) && !empty($preSelectedResult['pr_number'])) {
				$prNumber = $preSelectedResult['pr_number'];
			}
		} else {
			$productAvailabilityDate = strstr($this->template, "{product_availability_date}");
			$stockNotifyFlag         = strstr($this->template, "{stock_notify_flag}");
			$stockStatus             = strstr($this->template, "{stock_status");

			if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus) {
				$attributeProductStockStatus = RedshopHelperProduct::getproductStockStatus(
					$this->product->product_id,
					$totalAtt
				);
			}
		}

		$this->template = \Redshop\Helper\Stockroom::replaceProductStockData(
			$this->product->product_id,
			$selectedPropertyId,
			$selectedSubPropertyId,
			$this->template,
			$attributeProductStockStatus
		);

		$productNumberOutput = '<span id="product_number_variable' . $this->product->product_id . '">' . $prNumber . '</span>';
		$this->template      = str_replace("{product_number}", $productNumberOutput, $this->template);

		// Product accessory Start
		$accessory      = RedshopHelperAccessory::getProductAccessories(0, $this->product->product_id);
		$totalAccessory = count($accessory);

		$this->template = RedshopHelperProductAccessory::replaceAccessoryData(
			$this->product->product_id,
			0,
			$accessory,
			$this->template,
			$isChilds
		);

		$this->replaceMoreImage($moreImageResponse);

		if ($this->isTagExists('{more_videos}')) {
			$mediaYoutube = RedshopHelperProduct::getVideosProduct(
				$this->product->product_id,
				$attributes,
				$attributeTemplate,
				'youtube'
			);
			$mediaVideos  = RedshopHelperProduct::getVideosProduct(
				$this->product->product_id,
				$attributes,
				$attributeTemplate,
				'video'
			);

			$insertStr = RedshopLayoutHelper::render(
				'tags.product.more_videos',
				[
					'mediaYoutubes' => $mediaYoutube,
					'mediaVideos'   => $mediaVideos
				],
				'',
				$this->optionLayout
			);

			$this->addReplace('{more_videos}', $insertStr);
		}

		if (strstr($this->template, "{more_documents}")) {
			$mediaDocuments = RedshopHelperMedia::getAdditionMediaImage(
				$this->product->product_id,
				"product",
				"document"
			);

			$moreDoc = RedshopLayoutHelper::render(
				'tags.product.more_document',
				[
					'mediaDocuments' => $mediaDocuments,
					'productId'      => $this->product->product_id
				],
				'',
				$this->optionLayout
			);

			$this->addReplace('{more_documents}', $moreDoc);
		}

		$this->replaceThumbImg($preSelectedResult);

		$this->template = RedshopHelperProduct::getJcommentEditor($this->product, $this->template);

		// ProductFinderDatepicker Extra Field Start

		$fieldArray     = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);
		$this->template = RedshopHelperProduct::getProductFinderDatepickerValue(
			$this->template,
			$this->product->product_id,
			$fieldArray
		);

		// ProductFinderDatepicker Extra Field End

		// Product User Field Start
		$countNoUserField  = 0;
		$returnArr         = \Redshop\Product\Product::getProductUserfieldFromTemplate($this->template);
		$templateUserField = $returnArr[0];
		$userFieldArr      = $returnArr[1];

		if ($this->isTagExists('{if product_userfield}') && $this->isTagExists(
				'{product_userfield end if}'
			) && $templateUserField != "") {
			$this->replaceUserField($userFieldArr, $countNoUserField);
		}
		// Product User Field End

		$this->replaceCategoryProductImg();
		$this->replaceFrontImgLink();

		// Product preview image.
		if ($this->isTagExists('{product_preview_img}')) {
			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $this->product->product_preview_image)) {
				$previewsrcPath = RedshopHelperMedia::getImagePath(
					$this->product->product_preview_image,
					'',
					'thumb',
					'product',
					Redshop::getConfig()->get('PRODUCT_PREVIEW_IMAGE_WIDTH'),
					Redshop::getConfig()->get('PRODUCT_PREVIEW_IMAGE_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);

				$previewImg = RedshopLayoutHelper::render(
					'tags.common.img',
					[
						'src'  => $previewsrcPath,
						'attr' => 'class="rs_previewImg"'
					],
					'',
					$this->optionLayout
				);
				$this->addReplace('{product_preview_img}', $previewImg);
			} else {
				$this->addReplace('{product_preview_img}', '');
			}
		}

		// Cart
		$this->template = Redshop\Cart\Render::replace(
			$this->product->product_id,
			$this->product->category_id,
			0,
			0,
			$this->template,
			$isChilds,
			$userFieldArr,
			$totalAtt,
			$totalAccessory,
			$countNoUserField
		);

		$this->replacements['{ajaxwishlist_icon}'] = '';

		// Replace wishlistbutton
		$this->template = RedshopHelperWishlist::replaceWishlistTag($this->product->product_id, $this->template);

		// Replace compare product button
		$this->template = Redshop\Product\Compare::replaceCompareProductsButton(
			$this->product->product_id,
			$this->product->category_id,
			$this->template
		);

		// Ajax detail box template
		$ajaxDetailTemplateData = \Redshop\Template\Helper::getAjaxDetailBox($this->product);

		if (null !== $ajaxDetailTemplateData) {
			$this->replacements["{ajaxdetail_template:" . $ajaxDetailTemplateData->name . "}"] = '';
		}

		// Checking if user logged in then only enabling review button
		$reviewForm = "";

		if (($user->id && Redshop::getConfig()->get('RATING_REVIEW_LOGIN_REQUIRED')) || !Redshop::getConfig()->get(
				'RATING_REVIEW_LOGIN_REQUIRED'
			)) {
			// Write Review link with the products
			if ($this->isTagExists('{form_rating_without_lightbox}') && !JFactory::getApplication()->input->getInt(
					'rate',
					0
				)) {
				$form = RedshopModelForm::getInstance(
					'Product_Rating',
					'RedshopModel',
					array(
						'context' => 'com_redshop.edit.product_rating.' . $this->product->product_id
					)
				)->/** @scrutinizer ignore-call */ getForm();

				$ratingForm = RedshopLayoutHelper::render(
					'product.product_rating',
					array(
						'form'      => $form,
						'modal'     => 0,
						'productId' => $this->product->product_id,
						'returnUrl' => base64_encode(Juri::getInstance()->toString())
					)
				);

				$this->addReplace('{form_rating_without_lightbox}', $ratingForm);
			}

			if ($this->isTagExists('{form_rating}')) {
				$reviewForm = RedshopLayoutHelper::render(
					'tags.common.modal',
					[
						'class' => 'redbox btn btn-primary',
						'link'  => JURI::root(
							) . 'index.php?option=com_redshop&view=product_rating&tmpl=component&product_id=' . $this->product->product_id .
							'&category_id=' . $this->product->category_id .
							'&Itemid=' . $this->itemId,
						'x'     => 500,
						'y'     => 500,
						'text'  => JText::_('COM_REDSHOP_WRITE_REVIEW')
					],
					'',
					$this->optionLayout
				);

				$this->addReplace('{form_rating}', $reviewForm);
			}
		} else {
			$reviewForm = JText::_('COM_REDSHOP_YOU_NEED_TO_LOGIN_TO_POST_A_REVIEW');

			if ($this->isTagExists("{form_rating_without_lightbox}")) {
				$this->addReplace('{form_rating_without_lightbox}', $reviewForm);
			}

			if ($this->isTagExists("{form_rating}")) {
				$this->addReplace('{form_rating}', $reviewForm);
			}
		}

		// Product Review/Rating
		if ($this->isTagExists('{product_rating_summary}')) {
			$finalAvgReviewData = Redshop\Product\Rating::getRating($this->product->product_id);

			if ($finalAvgReviewData != "") {
				$this->addReplace('{product_rating_summary}', $finalAvgReviewData);
			} else {
				$this->addReplace('{product_rating_summary}', '');
			}
		}

		if ($this->isTagExists("{product_rating}")) {
			if ((int)Redshop::getConfig()->get('FAVOURED_REVIEWS') !== 0) {
				$mainBlock = Redshop::getConfig()->get('FAVOURED_REVIEWS');
			} else {
				$mainBlock = 5;
			}

			$mainTemplate = RedshopHelperTemplate::getTemplate('review');

			if (count($mainTemplate) > 0 && $mainTemplate[0]->template_desc) {
				$mainTemplate = $mainTemplate[0]->template_desc;
			} else {
				$mainTemplate = RedshopHelperTemplate::getDefaultTemplateContent('review');
			}

			$mainTemplate = RedshopTagsReplacer::_(
				'review',
				$mainTemplate,
				array(
					'productId' => $this->product->product_id,
					'mainBlock' => $mainBlock
				)
			);

			$this->addReplace('{product_rating}', $mainTemplate);
		}

		$this->replacements['{send_to_friend}'] = RedshopLayoutHelper::render(
			'tags.common.link',
			[
				'class'   => 'redcolorproductimg',
				'link'    => JURI::root(
					) . 'index.php?option=com_redshop&view=send_friend&pid=' . $this->product->product_id . '&tmpl=component&Itemid=' . $this->itemId,
				'content' => JText::_('COM_REDSHOP_SEND_FRIEND')
			],
			'',
			$this->optionLayout
		);

		// Ask question about this product
		if ($this->isTagExists("{ask_question_about_product}")) {
			$this->replacements['{ask_question_about_product}'] = RedshopLayoutHelper::render(
				'tags.common.modal',
				[
					'class' => 'redbox btn btn-primary',
					'link'  => JURI::root(
						) . 'index.php?option=com_redshop&view=ask_question&pid=' . $this->product->product_id .
						'&tmpl=component&Itemid=' . $this->itemId,
					'text'  => JText::_('COM_REDSHOP_ASK_QUESTION_ABOUT_PRODUCT'),
					'x'     => 500,
					'y'     => 500
				],
				'',
				$this->optionLayout
			);
		}

		$this->replaceProductSubscription();
		$this->replaceQuestionAnswer();

		$myTags = '';

		if (Redshop::getConfig()->getInt('MY_TAGS') !== 0 && $user->id && $this->isTagExists("{my_tags_button}")) {
			// Product Tags - New Feature Like Magento Store
			$myTags = RedshopLayoutHelper::render(
				'tags.product.my_tags_button',
				[
					'productId' => $this->product->product_id,
					'userId'    => $user->id
				],
				'',
				$this->optionLayout
			);
			// End Product Tags
		}

		$this->replacements['{my_tags_button}']              = $myTags;
		$this->replacements['{with_vat}']                    = '';
		$this->replacements['{without_vat}']                 = '';
		$this->replacements['{attribute_price_with_vat}']    = '';
		$this->replacements['{attribute_price_without_vat}'] = '';

// Replace Minimum quantity per order
		$minOrderProductQuantity = '';

		if ((int)$this->product->min_order_product_quantity > 0) {
			$minOrderProductQuantity = $this->product->min_order_product_quantity;
		}

		$this->template = str_replace(
			'{min_order_product_quantity}',
			$minOrderProductQuantity,
			$this->template
		);

		$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

		$this->template = RedshopHelperText::replaceTexts($this->template);

		$this->template = RedshopHelperProduct::getRelatedTemplateView($this->template, $this->product->product_id);

		// Replacing ask_question_about_product_without_lightbox must be after parseredSHOPplugin for not replace in cloak plugin form emails
		if ($this->isTagExists('{ask_question_about_product_without_lightbox}')) {
			$questionForm = RedshopTagsReplacer::_(
				'askquestion',
				'',
				array(
					'form' => RedshopModelForm::getInstance('Ask_Question', 'RedshopModel')->getForm(),
					'ask'  => 1
				)
			);

			$this->addReplace('{ask_question_about_product_without_lightbox}', $questionForm);
		}

		// Replacing form_rating_without_link must be after parseredSHOPplugin for not replace in cloak plugin form emails
		if ($this->isTagExists('{form_rating_without_link}')) {
			$form        = RedshopModelForm::getInstance(
				'Product_Rating',
				'RedshopModel',
				array(
					'context' => 'com_redshop.edit.product_rating.' . $this->product->product_id
				)
			)
				->getForm();
			$displayData = array(
				'form'       => $form,
				'modal'      => 0,
				'product_id' => $this->product->product_id
			);

			$this->addReplace(
				'{form_rating_without_link}',
				RedshopLayoutHelper::render('product.product_rating', $displayData)
			);
		}

		$this->template = $this->strReplace($this->replacements, $this->template);

		return parent::replace();
	}


	/**
	 * Method replace navigation next prev
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceNavigationNextPrev()
	{
		$nextButton = '';
		$prevButton = '';

		// Next Navigation
		$nextProducts = Redshop\Product\Product::getPrevNextproduct(
			$this->product->product_id,
			$this->product->category_id,
			1
		);

		if (!empty($nextProducts)) {
			$nextLink          = JRoute::_(
				'index.php?option=com_redshop&view=product&pid=' . $nextProducts->product_id .
				'&cid=' . $this->product->category_id .
				'&Itemid=' . $this->itemId
			);
			$contentNextButton = '';

			if (Redshop::getConfig()->getInt('DEFAULT_LINK_FIND') === 0) {
				$contentNextButton = $nextProducts->product_name . " " . Redshop::getConfig()->get(
						'DAFULT_NEXT_LINK_SUFFIX'
					);
			} elseif ((int)Redshop::getConfig()->get('DEFAULT_LINK_FIND') === 1) {
				$contentNextButton = Redshop::getConfig()->get('CUSTOM_NEXT_LINK_FIND');
			} elseif (!empty(Redshop::getConfig()->get('IMAGE_PREVIOUS_LINK_FIND')) && file_exists(
					REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('IMAGE_PREVIOUS_LINK_FIND')
				)) {
				$contentNextButton = RedshopLayoutHelper::render(
					'tags.common.img',
					[
						'src' => REDSHOP_FRONT_IMAGES_ABSPATH . Redshop::getConfig()->get('IMAGE_NEXT_LINK_FIND'),
						'alt' => $nextProducts->product_name
					],
					'',
					$this->optionLayout
				);
			}

			$nextButton = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => $nextLink,
					'content' => $contentNextButton
				],
				'',
				$this->optionLayout
			);
		}

		// Start previous logic
		$previousProducts = Redshop\Product\Product::getPrevNextproduct(
			$this->product->product_id,
			$this->product->category_id,
			-1
		);

		if (!empty($previousProducts)) {
			$prevLink          = JRoute::_(
				'index.php?option=com_redshop&view=product&pid=' . $previousProducts->product_id .
				'&cid=' . $this->product->category_id .
				'&Itemid=' . $this->itemId
			);
			$contentPrevButton = '';


			if (Redshop::getConfig()->getInt('DEFAULT_LINK_FIND') === 0) {
				$contentPrevButton = Redshop::getConfig()->get(
						'DAFULT_PREVIOUS_LINK_PREFIX'
					) . " " . $previousProducts->product_name;
			} elseif (Redshop::getConfig()->get('DEFAULT_LINK_FIND') == 1) {
				$contentPrevButton = Redshop::getConfig()->get('CUSTOM_PREVIOUS_LINK_FIND');
			} elseif (!empty(Redshop::getConfig()->get('IMAGE_PREVIOUS_LINK_FIND')) && file_exists(
					REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('IMAGE_PREVIOUS_LINK_FIND')
				)) {
				$contentPrevButton = RedshopLayoutHelper::render(
					'tags.common.img',
					[
						'src' => REDSHOP_FRONT_IMAGES_ABSPATH . Redshop::getConfig()->get('IMAGE_PREVIOUS_LINK_FIND'),
						'alt' => $previousProducts->product_name
					],
					'',
					$this->optionLayout
				);
			}

			$prevButton = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => $prevLink,
					'content' => $contentPrevButton
				],
				'',
				$this->optionLayout
			);
			// End
		}

		$this->addReplace('{navigation_link_right}', $nextButton);
		$this->addReplace('{navigation_link_left}', $prevButton);
	}

	/**
	 * Method replace manufacturer image
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceManufactureImage()
	{
		$manufacturerImage = '';
		$manufacturerMedia = RedshopEntityManufacturer::getInstance($this->product->manufacturer_id)->getMedia();

		if ($manufacturerMedia->isValid() && !empty($manufacturerMedia->get('media_name'))
			&& JFile::exists(
				REDSHOP_MEDIA_IMAGE_RELPATH . 'manufacturer/' . $this->product->manufacturer_id . '/' . $manufacturerMedia->get(
					'media_name'
				)
			)) {
			$thumbHeight = Redshop::getConfig()->get('MANUFACTURER_THUMB_HEIGHT');
			$thumbWidth  = Redshop::getConfig()->get('MANUFACTURER_THUMB_WIDTH');

			if (Redshop::getConfig()->get('WATERMARK_MANUFACTURER_IMAGE') || Redshop::getConfig()->get(
					'WATERMARK_MANUFACTURER_THUMB_IMAGE'
				)) {
				$imagePath = RedshopHelperMedia::watermark(
					'manufacturer',
					$manufacturerMedia->get('media_name'),
					$thumbWidth,
					$thumbHeight,
					Redshop::getConfig()->get('WATERMARK_MANUFACTURER_IMAGE')
				);
			} else {
				$imagePath = RedshopHelperMedia::getImagePath(
					$manufacturerMedia->get('media_name'),
					'',
					'thumb',
					'manufacturer',
					$thumbWidth,
					$thumbHeight,
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING'),
					'manufacturer',
					$this->product->manufacturer_id
				);
			}

			$manufacturerImage = RedshopLayoutHelper::render(
				'tags.manufacturer.image',
				array(
					'altText'         => $manufacturerMedia->get('media_alternate_text'),
					'manufacturerId'  => $this->product->manufacturer_id,
					'media'           => $manufacturerMedia,
					'manufacturerImg' => $imagePath
				),
				'',
				$this->optionLayout
			);
		}

		$this->addReplace('{manufacturer_image}', $manufacturerImage);
	}

	/**
	 * Method replace wrapper
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceWrapper()
	{
		$wrapper         = RedshopHelperProduct::getWrapper($this->product->product_id, 0, 1);
		$wrapperTemplate = RedshopHelperTemplate::getTemplate("wrapper_template");

		if ($this->isTagExists("{wrapper_template:")) {
			for ($w = 0, $wn = count($wrapperTemplate); $w < $wn; $w++) {
				if ($this->isTagExists("{wrapper_template:" . $wrapperTemplate[$w]->name . "}")) {
					$wrapperHtml = RedshopTagsReplacer::_(
						'wrapper',
						$wrapperTemplate[$w]->template_desc,
						array(
							'data'    => $this->product,
							'wrapper' => $wrapper
						)
					);

					$this->replacements["{wrapper_template:" . $wrapperTemplate[$w]->name . "}"] = $wrapperHtml;
				}
			}
		}
	}

	/**
	 * Method replace thumb image
	 *
	 * @param array $preSelectedResult
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceThumbImg($preSelectedResult)
	{
		if ($this->isTagExists($this->infoTagImg['imageTag'])) {
			$thumbImg = Redshop\Product\Image\Image::getImage(
				$this->product->product_id,
				JRoute::_('index.php?option=com_redshop&view=product&pid=' . $this->product->product_id),
				$this->infoTagImg['width'],
				$this->infoTagImg['height'],
				Redshop::getConfig()->get('PRODUCT_DETAIL_IS_LIGHTBOX'),
				0,
				0,
				$preSelectedResult
			);

			$thumImageHtml = RedshopLayoutHelper::render(
				'tags.product.thumb_image',
				[
					'thumbImg'  => $thumbImg,
					'productId' => $this->product->product_id,
					'width'     => $this->infoTagImg['width'],
					'height'    => $this->infoTagImg['height']

				],
				'',
				$this->optionLayout
			);

			$this->replacements[$this->infoTagImg['imageTag']] = $thumImageHtml;
		}
	}

	/**
	 * Method replace more image
	 *
	 * @param string $moreImageResponse
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceMoreImage($moreImageResponse)
	{
		$infoMoreImg = $this->getWidthHeight(
			$this->template,
			'more_images',
			'PRODUCT_ADDITIONAL_IMAGE_HEIGHT',
			'PRODUCT_ADDITIONAL_IMAGE'
		);

		if ($this->isTagExists($infoMoreImg['imageTag'])) {
			if ($moreImageResponse != "") {
				$moreImages = $moreImageResponse;
			} else {
				$mediaImage = RedshopHelperMedia::getAdditionMediaImage($this->product->product_id, "product");
				$moreImages = '';

				for ($m = 0, $mn = count($mediaImage); $m < $mn; $m++) {
					$moreImages .= $this->replaceMoreImageItem($mediaImage[$m], $infoMoreImg);
				}
			}

			$this->replacements[$infoMoreImg['imageTag']] = RedshopLayoutHelper::render(
				'tags.product.more_image',
				[
					'productId'  => $this->product->product_id,
					'moreImages' => $moreImages
				],
				'',
				$this->optionLayout
			);
		}
	}

	/**
	 * Method replace more image item
	 *
	 * @param object $mediaImage
	 * @param array  $infoMoreImg
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceMoreImageItem($mediaImage, $infoMoreImg)
	{
		$filename1  = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $mediaImage->media_name;
		$moreImages = '';

		if ($mediaImage->media_name != $mediaImage->product_full_image && file_exists(
				$filename1
			) && !empty($mediaImage->media_name)) {
			$altText = RedshopHelperMedia::getAlternativeText(
				'product',
				$mediaImage->section_id,
				'',
				$mediaImage->media_id
			);

			if (!$altText) {
				$altText = $mediaImage->media_name;
			}

			$thumb = $mediaImage->media_name;

			if (Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE')) {
				$pimg      = RedshopHelperMedia::watermark(
					'product',
					$thumb,
					$infoMoreImg['width'],
					$infoMoreImg['height'],
					Redshop::getConfig()->get(
						'WATERMARK_PRODUCT_ADDITIONAL_IMAGE'
					)
				);
				$linkImage = RedshopHelperMedia::watermark(
					'product',
					$thumb,
					'',
					'',
					Redshop::getConfig()->get(
						'WATERMARK_PRODUCT_ADDITIONAL_IMAGE'
					)
				);

				$hoverimgPath = RedshopHelperMedia::watermark(
					'product',
					$thumb,
					Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH'),
					Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_HEIGHT'),
					Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE')
				);
			} else {
				$pimg      = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					'product',
					$infoMoreImg['width'],
					$infoMoreImg['height'],
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
				$linkImage = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $thumb;

				$hoverimgPath = RedshopHelperMedia::getImagePath(
					$thumb,
					'',
					'thumb',
					'product',
					Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH'),
					Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
			}

			if (Redshop::getConfig()->get('PRODUCT_ADDIMG_IS_LIGHTBOX')) {
				$moreImages .= RedshopLayoutHelper::render(
					'tags.product.more_image.lightbox',
					[
						'linkImage'    => $linkImage,
						'altText'      => $altText,
						'pimg'         => $pimg,
						'hoverimgPath' => $hoverimgPath,
						'productId'    => $this->product->product_id
					],
					'',
					$this->optionLayout
				);
			} else {
				if (Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE')) {
					$imgPath = RedshopHelperMedia::watermark(
						'product',
						$thumb,
						$this->infoTagImg['width'],
						$this->infoTagImg['height'],
						Redshop::getConfig()->get(
							'WATERMARK_PRODUCT_ADDITIONAL_IMAGE'
						),
						'0'
					);
				} else {
					$imgPath = RedshopHelperMedia::getImagePath(
						$thumb,
						'',
						'thumb',
						'product',
						$this->infoTagImg['width'],
						$this->infoTagImg['height'],
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
				}

				$hoverMoreImages = RedshopHelperMedia::watermark(
					'product',
					$thumb,
					'',
					'',
					Redshop::getConfig()->get(
						'WATERMARK_PRODUCT_ADDITIONAL_IMAGE'
					),
					'0'
				);

				$fileNameOrg = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $mediaImage->product_full_image;

				if (file_exists($fileNameOrg)) {
					$thumbOriginal = $mediaImage->product_full_image;
				} else {
					$thumbOriginal = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
				}

				if (Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE')) {
					$imgPathOrg = RedshopHelperMedia::watermark(
						'product',
						$thumbOriginal,
						$this->infoTagImg['width'],
						$this->infoTagImg['height'],
						Redshop::getConfig()->get(
							'WATERMARK_PRODUCT_THUMB_IMAGE'
						),
						'0'
					);
				} else {
					$imgPathOrg = RedshopHelperMedia::getImagePath(
						$thumbOriginal,
						'',
						'thumb',
						'product',
						$this->infoTagImg['width'],
						$this->infoTagImg['height'],
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
				}

				$moreImages .= RedshopLayoutHelper::render(
					'tags.product.more_image.without_lightbox',
					[
						'imgPath'         => $imgPath,
						'productId'       => $this->product->product_id,
						'hoverMoreImages' => $hoverMoreImages,
						'imgPathOrg'      => $imgPathOrg,
						'pimg'            => $pimg,
						'altText'         => $altText,
						'hoverimgPath'    => $hoverimgPath
					],
					'',
					$this->optionLayout
				);
			}
		}

		return $moreImages;
	}

	/**
	 * Method replace user field
	 *
	 * @param array   $userFieldArr
	 * @param integer $countNoUserField
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceUserField($userFieldArr, &$countNoUserField)
	{
		$cart = \Redshop\Cart\Helper::getCart();
		$idx  = 0;

		if (isset($cart['idx'])) {
			$idx = (int)($cart['idx']);
		}

		$cartId = '';

		for ($j = 0; $j < $idx; $j++) {
			if ($cart[$j]['product_id'] == $this->product->product_id) {
				$cartId = $j;
			}
		}

		$countUserFieldArr = count($userFieldArr);

		if ($countUserFieldArr > 0) {
			for ($ui = 0; $ui < $countUserFieldArr; $ui++) {
				if (!$idx) {
					$cartId = "";
				}

				$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields(
					$userFieldArr[$ui],
					12,
					'',
					$cartId,
					0,
					$this->product->product_id
				);

				if ($productUserFields[1] != "") {
					$countNoUserField++;
				}

				$this->replacements['{' . $userFieldArr[$ui] . '_lbl}'] = $productUserFields[0];
				$this->replacements['{' . $userFieldArr[$ui] . '}']     = $productUserFields[1];
			}
		}

		$subTemplate           = $this->getTemplateBetweenLoop('{if product_userfield}', '{product_userfield end if}');
		$productUserFieldsForm = RedshopLayoutHelper::render(
			'tags.common.form',
			[
				'method'  => 'post',
				'id'      => 'user_fields_form',
				'name'    => 'user_fields_form',
				'content' => $subTemplate['template']
			],
			'',
			$this->optionLayout
		);

		$this->template = $subTemplate['begin'] . $productUserFieldsForm . $subTemplate['end'];
	}

	/**
	 * Method replace category product image
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceCategoryProductImg()
	{
		if ($this->isTagExists('{category_product_img}')) {
			$mainSrcPath = RedshopHelperMedia::getImagePath(
				$this->product->category_full_image,
				'',
				'thumb',
				'category',
				$this->infoTagImg['width'],
				$this->infoTagImg['height'],
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
			);

			$backSrcPath = RedshopHelperMedia::getImagePath(
				$this->product->category_back_full_image,
				'',
				'thumb',
				'category',
				$this->infoTagImg['width'],
				$this->infoTagImg['height'],
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
			);

			$aHrefPath     = REDSHOP_FRONT_IMAGES_ABSPATH . "category/" . $this->product->category_full_image;
			$aHrefBackPath = REDSHOP_FRONT_IMAGES_ABSPATH . "category/" . $this->product->category_back_full_image;

			$this->replacements['{category_front_img_link}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => '#',
					'attr'    => 'onClick="javascript:changeproductImage(' . $this->product->product_id . ',\'' . $mainSrcPath . '\',\'' . $aHrefPath . '\');"',
					'',
					'content' => JText::_('COM_REDSHOP_FRONT_IMAGE')
				],
				'',
				$this->optionLayout
			);

			$this->replacements['{category_back_img_link}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => '#',
					'attr'    => 'onClick="javascript:changeproductImage(' . $this->product->product_id . ',\'' . $backSrcPath . '\',\'' . $aHrefBackPath . '\');"',
					'content' => JText::_('COM_REDSHOP_BACK_IMAGE')
				],
				'',
				$this->optionLayout
			);

			$this->replacements['{category_product_img}'] = RedshopHelperProduct::getProductCategoryImage(
				$this->product->product_id,
				$this->product->category_full_image,
				'',
				$this->infoTagImg['width'],
				$this->infoTagImg['height']
			);
		} else {
			$this->replacements['{category_front_img_link}'] = '';
			$this->replacements['{category_back_img_link}']  = '';
		}
	}

	/**
	 * Method replace front image link
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceFrontImgLink()
	{
		if ($this->isTagExists('{front_img_link}') || $this->isTagExists('{back_img_link}')) {
			// Front-back image tag...
			if ($this->product->product_thumb_image) {
				$mainSrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->product->product_thumb_image;
			} else {
				$mainSrcPath = RedshopHelperMedia::getImagePath(
					$this->product->product_full_image,
					'',
					'thumb',
					'product',
					$this->infoTagImg['width'],
					$this->infoTagImg['height'],
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
			}

			if ($this->product->product_back_thumb_image) {
				$backSrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->product->product_back_thumb_image;
			} else {
				$backSrcPath = RedshopHelperMedia::getImagePath(
					$this->product->product_back_full_image,
					'',
					'thumb',
					'product',
					$this->infoTagImg['width'],
					$this->infoTagImg['height'],
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);
			}

			$aHrefPath     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->product->product_full_image;
			$aHrefBackPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->product->product_back_full_image;

			$this->replacements['{front_img_link}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => '#',
					'attr'    => 'onClick="javascript:changeproductImage(' . $this->product->product_id . ',\'' . $mainSrcPath . '\',\'' . $aHrefPath . '\');"',
					'',
					'content' => JText::_('COM_REDSHOP_FRONT_IMAGE')
				],
				'',
				$this->optionLayout
			);

			$this->replacements['{back_img_link}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => '#',
					'attr'    => 'onClick="javascript:changeproductImage(' . $this->product->product_id . ',\'' . $backSrcPath . '\',\'' . $aHrefBackPath . '\');"',
					'content' => JText::_('COM_REDSHOP_BACK_IMAGE')
				],
				'',
				$this->optionLayout
			);
		} else {
			$this->replacements['{front_img_link}'] = '';
			$this->replacements['{back_img_link}']  = '';
		}
	}

	/**
	 * Method replace product subscription
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceProductSubscription()
	{
		if ($this->isTagExists('{subscription}') || $this->isTagExists('{product_subscription}')) {
			if ($this->product->product_type == 'subscription') {
				$subscription = RedshopHelperProduct::getSubscription($this->product->product_id);

				$subscriptionData = RedshopLayoutHelper::render(
					'tags.product.subscription',
					[
						'subscriptions' => $subscription,
						'productId'     => $this->product->product_id
					],
					'',
					$this->optionLayout
				);

				$this->replacements['{subscription}']         = $subscriptionData;
				$this->replacements['{product_subscription}'] = $subscriptionData;
			} else {
				$this->replacements['{subscription}']         = '';
				$this->replacements['{product_subscription}'] = '';
			}
		}
	}

	/**
	 * Method replace question answer
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceQuestionAnswer()
	{
		$questionTemplate = $this->getTemplateBetweenLoop('{question_loop_start}', '{question_loop_end}');

		if (!empty($questionTemplate)) {
			$productQuestion = RedshopHelperProduct::getQuestionAnswer(0, $this->product->product_id, 0, 1);
			$questionLoop    = "";

			if ($questionTemplate['template'] != "") {
				for ($q = 0, $qn = count($productQuestion); $q < $qn; $q++) {
					$replaceQuestion = [];

					$replaceQuestion['{question}']       = $productQuestion[$q]->question;
					$replaceQuestion['{question_date}']  = RedshopHelperDatetime::convertDateFormat(
						$productQuestion [$q]->question_date
					);
					$replaceQuestion['{question_owner}'] = $productQuestion[$q]->user_name;

					$qLoop = $this->strReplace($replaceQuestion, $questionTemplate['template']);

					$answerTemplate = $this->getTemplateBetweenLoop('{answer_loop_start}', '{answer_loop_end}', $qLoop);
					$productAnswer  = RedshopHelperProduct::getQuestionAnswer($productQuestion [$q]->id, 0, 1, 1);
					$answerLoop     = "";

					for ($a = 0, $an = count($productAnswer); $a < $an; $a++) {
						$replaceAnswer                   = [];
						$replaceAnswer['{answer}']       = $productAnswer[$a]->question;
						$replaceAnswer['{answer_date}']  = RedshopHelperDatetime::convertDateFormat(
							$productAnswer[$a]->question_date
						);
						$replaceAnswer['{answer_owner}'] = $productAnswer[$a]->user_name;

						$answerLoop .= $this->strReplace($replaceAnswer, $answerTemplate['template']);
					}

					$questionLoop .= $answerTemplate['begin'] . $answerLoop . $answerTemplate['end'];
				}
			}

			$this->template = $questionTemplate['begin'] . $questionLoop . $questionTemplate['end'];
		}
	}
}