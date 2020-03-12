<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsCategoryDetail extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $tags = array(
		'{print}',
		'{total_product}',
		'{total_product_lbl}',
		'{returntocategory}',
		'{returntocategory_name}',
		'{returntocategory_link}',
		'{category_main_description}',
		'{category_main_short_desc}',
		'{shopname}',
		'{category_main_thumb_image}',
		'{category_main_thumb_image_1}',
		'{category_main_thumb_image_2}',
		'{category_main_thumb_image_3}',
		'{compare_product_div}',
		'{category_loop_start}',
		'{category_loop_end}',
		'{if subcats}',
		'{subcats end if}',
		'{product_price_slider}',
		'{product_old_price}',
		'{delivery_time_lbl}',
		'{product_delivery_time}',
		'{more_documents}',
		'{if product_userfield}',
		'{product_userfield end if}',
		'{product_id_lbl}',
		'{product_id}',
		'{product_number_lbl}',
		'{product_number}',
		'{product_size}',
		'{product_length}',
		'{product_width}',
		'{product_height}',
		'{product_name_nolink}',
		'{product_name}',
		'{category_product_link}',
		'{read_more}',
		'{read_more_link}',
		'{product_s_desc}',
		'{product_desc}',
		'{product_rating_summary}',
		'{manufacturer_name}',
		'{manufacturer_link}',
		'{manufacturer_product_link}',
		'{product_thumb_image}',
		'{product_thumb_image_1}',
		'{product_thumb_image_2}',
		'{product_thumb_image_3}',
		'{front_img_link}',
		'{back_img_link}',
		'{product_preview_img}',
		'{show_all_products_in_category}',
		'{pagination}',
		'{product_display_limit}',
		'{product_loop_start}',
		'{product_loop_end}',
		'{filter_by_lbl}',
		'{filter_by}',
		'{template_selector_category_lbl}',
		'{template_selector_category}',
		'{order_by_lbl}',
		'{order_by}',
		'{with_vat}',
		'{without_vat}',
		'{attribute_price_with_vat}',
		'{attribute_price_without_vat}',
		'{redproductfinderfilter_formstart}',
		'{product_price_slider1}',
		'{redproductfinderfilter_formend}',
		'{redproductfinderfilter:rp_myfilter}'
	);

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function init()
	{
		$this->input = JFactory::getApplication()->input;
		$this->maincat = $this->data['maincat'];
		$this->detail = $this->data['detail'];
		$this->manufacturer_id = $this->data['manufacturer_id'];
		$this->params = $this->data['params'];
		$this->pageheadingtag = $this->data['pageheadingtag'];
		$this->print = $this->data['print'];
		$this->catid = $this->data['catid'];
		$this->state = $this->data['state'];
		$this->model = $this->data['model'];
		$this->itemid = $this->data['itemid'];
		$this->order_by_select = $this->data['order_by_select'];
		$this->category_template_id = $this->data['category_template_id'];
		$this->option = $this->data['option'];
		$this->product = $this->data['product'];
		$this->lists = $this->data['lists'];
		$this->category_id = $this->data['category_id'];
		$this->productPriceSliderEnable = $this->data['productPriceSliderEnable'];
	}

	/**
	 * Override parent replace with  category detail replacing
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replace()
	{
		$this->template = $this->replaceCategoryDetail($this->template);

		return parent::replace();
	}

	/**
	 * Replace category detail fields tags
	 *
	 * @param string $template Template to replace
	 *
	 * @return  string
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function replaceCategoryDetail($template)
	{
		$replacements = [];
		$redConfiguration = Redconfiguration::getInstance();

		$minmax = $this->model->getMaxMinProductPrice();
		$texPriceMin = $minmax[0];
		$texPriceMax = $minmax[1];

		$slide = $this->input->getInt('ajaxslide', null);
		$start = $this->input->getInt('limitstart', 0);

		if (!$slide) {
			$divHeading = '';

			if ($this->params->get('show_page_heading', 0)) {
				if ($this->maincat->pageheading != "") {
					$heading = JFactory::getDbo()->escape($this->maincat->pageheading);
				} else {
					$heading = JFactory::getDbo()->escape($this->pageheadingtag);
				}

				$divHeading = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'div',
						'class' => 'componentheading' . $this->params->get('pageclass_sfx'),
						'id' => '',
						'attr' => '',
						'text' => $heading
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);
			}

			$divWrapHeading = RedshopLayoutHelper::render(
				'tags.common.tag',
				array(
					'tag' => 'div',
					'class' => 'category',
					'text' => $divHeading
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$template = $divWrapHeading . $template;

			if ($this->print) {
				$onclick = 'onclick="window.print();"';
				$replacements['{product_price_slider}'] = '';
				$replacements['{pagination}'] = '';
			} else {
				$url = JURI::base();

				$endLimit = $this->state->get('list.limit');
				$printUrl = $url . 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $this->catid;
				$printUrl .= '&print=1&tmpl=component&Itemid=' . $this->itemid;
				$printUrl .= '&limit=' . $endLimit . '&texpricemin=' . $texPriceMin . '&texpricemax=' . $texPriceMax;
				$printUrl .= '&order_by=' . $this->order_by_select . '&manufacturer_id=' . $this->manufacturer_id;
				$printUrl .= '&category_template=' . $this->category_template_id;
				$onclick = 'onclick="window.open(\'' . $printUrl . '\', \'mywindow\', \'scrollbars=1\', \'location=1\')"';
			}

			$printTag = RedshopLayoutHelper::render(
				'tags.common.img_link',
				array(
					'class' => '',
					'link' => 'javascript:void(0)',
					'linkAttr' => $onclick . ' title="' . JText::_('COM_REDSHOP_PRINT_LBL') . '"',
					'src' => JSYSTEM_IMAGES_PATH . 'printButton.png',
					'alt' => JText::_('COM_REDSHOP_PRINT_LBL'),
					'imgAttr' => ' title="' . JText::_('COM_REDSHOP_PRINT_LBL') . '"'
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$replacements['{print}'] = $printTag;
			$replacements['{total_product}'] = $this->model->_total;
			$replacements['{total_product_lbl}'] = JText::_('COM_REDSHOP_TOTAL_PRODUCT');

			if (strpos($template, '{returntocategory_link}') !== false
				|| strpos($template, '{returntocategory_name}') !== false
				|| strpos($template, '{returntocategory}') !== false
			) {
				$parentId = RedshopHelperProduct::getParentCategory($this->catid);
				$returnCatLink = '';
				$returnToCategory = '';
				$returnToCategoryName = '';

				if ($parentId != 0) {
					$categoryList = RedshopEntityCategory::getInstance($parentId)->getItem();
					$returnToCategoryName = $categoryList->name;
					$returnCatLink = JRoute::_(
						'index.php?option=' . $this->option .
						'&view=category&cid=' . $parentId .
						'&manufacturer_id=' . $this->manufacturer_id .
						'&Itemid=' . $this->itemid
					);

					$returnToCategory = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'link' => $returnCatLink,
							'content' => Redshop::getConfig()->get(
									'DAFULT_RETURN_TO_CATEGORY_PREFIX'
								) . '&nbsp;' . $categoryList->name
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);
				} elseif (Redshop::getConfig()->get('DAFULT_RETURN_TO_CATEGORY_PREFIX')) {
					$returnToCategoryName = Redshop::getConfig()->get('DAFULT_RETURN_TO_CATEGORY_PREFIX');
					$returnCatLink = JRoute::_(
						'index.php?option=' . $this->option .
						'&view=category&manufacturer_id=' . $this->manufacturer_id .
						'&Itemid=' . $this->itemid
					);

					$returnToCategory = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'link' => $returnCatLink,
							'content' => Redshop::getConfig()->get('DAFULT_RETURN_TO_CATEGORY_PREFIX')
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);
				}

				$replacements['{returntocategory_link}'] = $returnCatLink;
				$replacements['{returntocategory_name}'] = $returnToCategoryName;
				$replacements['{returntocategory}'] = $returnToCategory;
			}

			if (strpos($template, '{category_main_description}') !== false) {
				$mainCatDesc = $redConfiguration->maxchar(
					$this->maincat->description,
					Redshop::getConfig()->get('CATEGORY_SHORT_DESC_MAX_CHARS'),
					Redshop::getConfig()->get('CATEGORY_SHORT_DESC_END_SUFFIX')
				);

				$replacements['{category_main_description}'] = $mainCatDesc;
			}

			if (strpos($template, '{category_main_short_desc}') !== false) {
				$mainCatSDesc = $redConfiguration->maxchar(
					$this->maincat->short_description,
					Redshop::getConfig()->get('CATEGORY_SHORT_DESC_MAX_CHARS'),
					Redshop::getConfig()->get('CATEGORY_SHORT_DESC_END_SUFFIX')
				);

				$replacements['{category_main_short_desc}'] = $mainCatSDesc;
			}

			if (strpos($template, '{shopname}') !== false) {
				$replacements['{shopname}'] = Redshop::getConfig()->get('SHOP_NAME');
			}

			$mainCatName = $redConfiguration->maxchar(
				$this->maincat->name,
				Redshop::getConfig()->get('CATEGORY_TITLE_MAX_CHARS'),
				Redshop::getConfig()->get('CATEGORY_TITLE_END_SUFFIX')
			);

			$replacements['{category_main_name}'] = $mainCatName;

			if (strpos($template, '{category_main_thumb_image_2}') !== false) {
				$cTag = '{category_main_thumb_image_2}';
				$chThumb = Redshop::getConfig()->get('THUMB_HEIGHT_2');
				$cwThumb = Redshop::getConfig()->get('THUMB_WIDTH_2');
			} elseif (strpos($template, '{category_main_thumb_image_3}') !== false) {
				$cTag = '{category_main_thumb_image_3}';
				$chThumb = Redshop::getConfig()->get('THUMB_HEIGHT_3');
				$cwThumb = Redshop::getConfig()->get('THUMB_WIDTH_3');
			} elseif (strpos($template, '{category_main_thumb_image_1}') !== false) {
				$cTag = '{category_main_thumb_image_1}';
				$chThumb = Redshop::getConfig()->get('THUMB_HEIGHT');
				$cwThumb = Redshop::getConfig()->get('THUMB_WIDTH');
			} else {
				$cTag = '{category_main_thumb_image}';
				$chThumb = Redshop::getConfig()->get('THUMB_HEIGHT');
				$cwThumb = Redshop::getConfig()->get('THUMB_WIDTH');
			}

			$link = JRoute::_(
				'index.php?option=' . $this->option .
				'&view=category&cid=' . $this->catid .
				'&manufacturer_id=' . $this->manufacturer_id .
				'&layout=detail&Itemid=' . $this->data['mainItemId']
			);

			$catMainThumb = "";

			$medias = RedshopEntityCategory::getInstance($this->maincat->id)->getMedia();

			// @var RedshopEntityMediaImage $fullImage
			$fullImage = null;

			foreach ($medias->getAll() as $media) {
				// @var RedshopEntityMedia $media
				if ($media->get('scope') == 'full') {
					$fullImage = RedshopEntityMediaImage::getInstance($media->getId());

					break;
				}
			}

			if (null !== $fullImage) {
				if (is_null($chThumb) || !$chThumb || is_null($cwThumb) || !$cwThumb) {
					$chThumb = Redshop::getConfig()->get('THUMB_HEIGHT');
					$cwThumb = Redshop::getConfig()->get('THUMB_WIDTH');
				}

				$waterCatImg = $fullImage->generateThumb($cwThumb, $chThumb);
				$catMainThumb = RedshopLayoutHelper::render(
					'tags.common.img_link',
					array(
						'class' => '',
						'link' => $link,
						'linkAttr' => 'title="' . $mainCatName . '"',
						'src' => $waterCatImg['abs'],
						'alt' => $mainCatName,
						'imgAttr' => 'title="' . $mainCatName . '"'
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);
			}

			$replacements[$cTag] = $catMainThumb;

			$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(2, 1, 1);

			$template = RedshopHelperProductTag::getExtraSectionTag(
				$extraFieldName,
				$this->catid,
				RedshopHelperExtrafields::SECTION_CATEGORY,
				$template
			);

			if (strpos($template, "{compare_product_div}") !== false) {
				$compareProductLink = '';
				$compareProductDiv = '';

				if (!empty(Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE'))) {
					$compareDiv = Redshop\Product\Compare::generateCompareProduct();
					$compareUrl = JRoute::_(
						'index.php?option=com_redshop&view=product&layout=compare&Itemid=' . $this->itemid
					);

					$compareProductLink = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'link' => $compareUrl,
							'content' => JText::_('COM_REDSHOP_COMPARE')
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$compareProductDiv = RedshopLayoutHelper::render(
						'tags.common.tag',
						array(
							'tag' => 'div',
							'id' => 'divCompareProduct',
							'text' => $compareDiv
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);
				}

				$replacements['{compare_product_div}'] = $compareProductLink . $compareProductDiv;
			}

			if (strpos($template, "{category_loop_start}") !== false
				&& strpos($template, "{category_loop_end}") !== false) {
				$templateSubCat = $this->getTemplateBetweenLoop(
					'{category_loop_start}',
					'{category_loop_end}',
					$template
				);

				$subCatTemplate = $templateSubCat['template'];
				$catDetail = "";

				$extraFieldsForCurrentTemplate = RedshopHelperTemplate::getExtraFieldsForCurrentTemplate(
					$extraFieldName,
					$subCatTemplate
				);

				$nc = count($this->detail);

				for ($i = 0; $i < $nc; $i++) {
					if (empty($excludedTags)) {
						break;
					}

					$row = $this->detail[$i];

					// Filter categories based on Shopper group category ACL
					$checkCid = RedshopHelperAccess::checkPortalCategoryPermission($row->id);
					$sgPortal = RedshopHelperShopper_Group::getShopperGroupPortal();
					$portal = 0;

					if (!empty($sgPortal)) {
						$portal = $sgPortal->shopper_group_portal;
					}

					if (!$checkCid && (Redshop::getConfig()->get('PORTAL_SHOP') == 1 || $portal == 1)) {
						continue;
					}

					$dataAdd = explode('{explode_product}', $subCatTemplate);

					// Category template extra field
					// "2" argument is set for category

					if ($extraFieldsForCurrentTemplate) {
						$dataAdd[$i] = Redshop\Helper\ExtraFields::displayExtraFields(
							2,
							$row->id,
							$extraFieldsForCurrentTemplate,
							$dataAdd[$i]
						);
					}

					$catDetail .= $dataAdd[$i];
				}

				$replacements['{category_loop_start}'] = '';
				$replacements['{category_loop_end}'] = '';
				$replacements[$subCatTemplate] = $catDetail;
			}

			if (strpos($template, "{if subcats}") !== false
				&& strpos($template, "{subcats end if}") !== false) {
				$templateSubCats = $this->getTemplateBetweenLoop(
					'{if subcats}',
					'{subcats end if}',
					$template
				);

				if (!empty($this->detail)) {
					$replacements['{if subcats}'] = '';
					$replacements['{subcats end if}'] = '';
				} else {
					$template = $templateSubCats['begin'] . $templateSubCats['end'];
				}
			}

			if (strpos($template, "{product_price_slider}") !== false) {
				$priceSlider = RedshopLayoutHelper::render(
					'tags.category.price_slider',
					array(),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$replacements['{product_price_slider}'] = $priceSlider;
				$productTmpl = JText::_('COM_REDSHOP_NO_PRODUCT_FOUND');
			}
		}

		if (strpos($template, "{product_loop_start}") !== false
			&& strpos($template, "{product_loop_end}") !== false) {
			$templateProduct = $this->getTemplateBetweenLoop(
				'{product_loop_start}',
				'{product_loop_end}',
				$template
			);
			$templateProduct = $templateProduct['template'];

			$attributeTemplate = \Redshop\Template\Helper::getAttribute($templateProduct);

			$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(
				1,
				1,
				1
			);
			$extraFieldsForCurrentTemplate = RedshopHelperTemplate::getExtraFieldsForCurrentTemplate(
				$extraFieldName,
				$templateProduct,
				1
			);
			$productData = '';

			list($templateUserField, $userFieldArr) = \Redshop\Product\Product::getProductUserfieldFromTemplate(
				$templateProduct
			);

			$templateProduct = RedshopHelperTax::replaceVatInformation($templateProduct);
			$fieldArray = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);

			foreach ($this->product as $product) {
				$countNoUserField = 0;
				$dataAdd = $templateProduct;
				$productReplacements = [];

				// ProductFinderDatepicker Extra Field Start
				$dataAdd = RedshopHelperProduct::getProductFinderDatepickerValue(
					$dataAdd,
					$product->product_id,
					$fieldArray
				);

				// ProductFinderDatepicker Extra Field End
				// Replace Product price when config enable discount is "No"
				if (Redshop::getConfig()->getInt('DISCOUNT_ENABLE') === 0) {
					$productReplacements['{product_old_price}'] = '';
				}

				// Process the prepare Product plugins
				$params = array();
				$results = RedshopHelperUtility::getDispatcher()->trigger(
					'onPrepareProduct',
					array(& $dataAdd, & $params, $product)
				);

				if (strpos($dataAdd, "{product_delivery_time}") !== false) {
					$productDeliveryTime = RedshopHelperProduct::getProductMinDeliveryTime($product->product_id);

					if ($productDeliveryTime != "") {
						$productReplacements['{delivery_time_lbl}'] = JText::_('COM_REDSHOP_DELIVERY_TIME');
						$productReplacements['{product_delivery_time}'] = $productDeliveryTime;
					} else {
						$productReplacements['{delivery_time_lbl}'] = '';
						$productReplacements['{product_delivery_time}'] = '';
					}
				}

				// More documents
				if (strpos($dataAdd, "{more_documents}") !== false) {
					$mediaDocuments = RedshopHelperMedia::getAdditionMediaImage(
						$product->product_id,
						"product",
						"document"
					);

					$moreDoc = '';
					$nm = count($mediaDocuments);

					for ($m = 0; $m < $nm; $m++) {
						$altText = RedshopHelperMedia::getAlternativeText(
							"product",
							$mediaDocuments[$m]->section_id,
							"",
							$mediaDocuments[$m]->media_id,
							"document"
						);

						if (!$altText) {
							$altText = $mediaDocuments[$m]->media_name;
						}

						if (JFile::exists(
							REDSHOP_FRONT_DOCUMENT_RELPATH . 'product/' . $mediaDocuments[$m]->media_name
						)) {
							$downlink = JURI::root() .
								'index.php?tmpl=component&option=' . $this->option .
								'&view=product&pid=' . $product->product_id .
								'&task=downloadDocument&fname=' . $mediaDocuments[$m]->media_name .
								'&Itemid=' . $this->itemid;

							$downlink = RedshopLayoutHelper::render(
								'tags.common.link',
								array(
									'link' => $downlink,
									'attr' => 'title="' . $altText . '"',
									'content' => $altText
								),
								'',
								RedshopLayoutHelper::$layoutOption
							);

							$moreDoc = RedshopLayoutHelper::render(
								'tags.common.tag',
								array(
									'tag' => 'div',
									'text' => $downlink
								),
								'',
								RedshopLayoutHelper::$layoutOption
							);
						}
					}

					$moreDoc = RedshopLayoutHelper::render(
						'tags.common.tag',
						array(
							'tag' => 'span',
							'id' => 'additional_docs' . $product->product_id,
							'text' => $moreDoc
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$productReplacements['{more_documents}'] = $moreDoc;
				}

				// More documents end

				// Product User Field Start
				$hiddenUserField = "";

				if ($templateUserField != "") {
					$uField = "";
					$nui = count($userFieldArr);

					for ($ui = 0; $ui < $nui; $ui++) {
						$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields(
							$userFieldArr[$ui],
							12,
							'',
							'',
							0,
							$product->product_id
						);
						$uField .= $productUserFields[1];

						if ($productUserFields[1] != "") {
							$countNoUserField++;
						}

						$productReplacements['{' . $userFieldArr[$ui] . '_lbl}'] = $productUserFields[0];
						$productReplacements['{' . $userFieldArr[$ui] . '}'] = $productUserFields[1];
					}

					$productUserFieldsForm = "<form method='post' action='' id='user_fields_form_" .
						$product->product_id . "' name='user_fields_form_" . $product->product_id . "'>";

					if ($uField != "") {
						$dataAdd = str_replace("{if product_userfield}", $productUserFieldsForm, $dataAdd);
						$dataAdd = str_replace("{product_userfield end if}", "</form>", $dataAdd);
					} else {
						$dataAdd = str_replace("{if product_userfield}", "", $dataAdd);
						$dataAdd = str_replace("{product_userfield end if}", "", $dataAdd);
					}
				} elseif (Redshop::getConfig()->get('AJAX_CART_BOX')) {
					$ajaxDetailTemplateDesc = "";
					$ajaxDetailTemplate = \Redshop\Template\Helper::getAjaxDetailBox($product);

					if (null !== $ajaxDetailTemplate) {
						$ajaxDetailTemplateDesc = $ajaxDetailTemplate->template_desc;
					}

					$returnArr = \Redshop\Product\Product::getProductUserfieldFromTemplate($ajaxDetailTemplateDesc);
					$templateUserField = $returnArr[0];
					$userFieldArr = $returnArr[1];

					if ($templateUserField != "") {
						$uField = "";
						$nui = count($userFieldArr);

						for ($ui = 0; $ui < $nui; $ui++) {
							$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields(
								$userFieldArr[$ui],
								12,
								'',
								'',
								0,
								$product->product_id
							);
							$uField .= $productUserFields[1];

							if ($productUserFields[1] != "") {
								$countNoUserField++;
							}

							$templateUserField = str_replace(
								'{' . $userFieldArr[$ui] . '_lbl}',
								$productUserFields[0],
								$templateUserField
							);
							$templateUserField = str_replace(
								'{' . $userFieldArr[$ui] . '}',
								$productUserFields[1],
								$templateUserField
							);
						}

						if ($uField != "") {
							$userFieldsForm = RedshopLayoutHelper::render(
								'tags.common.form',
								array(
									'action' => '',
									'name' => 'user_fields_form_' . $product->product_id,
									'id' => 'user_fields_form_' . $product->product_id,
									'method' => 'post',
									'content' => $templateUserField
								),
								'',
								RedshopLayoutHelper::$layoutOption
							);

							$hiddenUserField = RedshopLayoutHelper::render(
								'tags.common.tag',
								array(
									'tag' => 'div',
									'attr' => 'style="display:none;"',
									'text' => $userFieldsForm
								),
								'',
								RedshopLayoutHelper::$layoutOption
							);
						}
					}
				}

				$dataAdd = $dataAdd . $hiddenUserField;

				// ************** end user fields ***************************
				$itemData = RedshopHelperProduct::getMenuInformation(
					0,
					0,
					'',
					'product&pid=' . $product->product_id
				);
				$catIdMain = JFactory::getApplication()->input->get("cid");

				if (!empty($itemData)) {
					$pItemId = $itemData->id;
				} else {
					$pItemId = RedshopHelperRouter::getCategoryItemid($product->category_id);

					if (empty($pItemId)) {
						$pItemId = RedshopHelperRouter::getItemId($product->product_id, $catIdMain);
					}
				}

				$productReplacements['{product_id_lbl}'] = JText::_('COM_REDSHOP_PRODUCT_ID_LBL');
				$productReplacements['{product_id}'] = $product->product_id;
				$productReplacements['{product_number_lbl}'] = JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL');

				$productNumberOutput = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'span',
						'id' => 'product_number_variable' . $product->product_id,
						'text' => $product->product_number
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$productReplacements['{product_number}'] = $productNumberOutput;

				$productVolumeUnit = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'span',
						'class' => 'product_unit_variable',
						'text' => Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . 3
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$dataAddStr = RedshopHelperProduct::redunitDecimal(
						$product->product_volume
					) . "&nbsp;" . $productVolumeUnit;

				$productReplacements['{product_size}'] = $dataAddStr;

				$productUnit = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'span',
						'class' => 'product_unit_variable',
						'text' => Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT')
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$productReplacements['{product_length}'] = RedshopHelperProduct::redunitDecimal(
						$product->product_length
					) . "&nbsp;" . $productUnit;
				$productReplacements['{product_width}'] = RedshopHelperProduct::redunitDecimal(
						$product->product_width
					) . "&nbsp;" . $productUnit;
				$productReplacements['{product_height}'] = RedshopHelperProduct::redunitDecimal(
						$product->product_height
					) . "&nbsp;" . $productUnit;

				$specificLink = RedshopHelperUtility::getDispatcher()->trigger('createProductLink', array($product));

				if (empty($specificLink)) {
					$productCatId = !empty($product->categories) && is_array(
						$product->categories
					) ? $product->categories[0] : $this->catid;

					$link = JRoute::_(
						'index.php?option=' . $this->option .
						'&view=product&pid=' . $product->product_id .
						'&cid=' . $productCatId .
						'&Itemid=' . $pItemId
					);
				} else {
					$link = $specificLink[0];
				}

				$pName = $redConfiguration->maxchar(
					$product->product_name,
					Redshop::getConfig()->get('CATEGORY_PRODUCT_TITLE_MAX_CHARS'),
					Redshop::getConfig()->get('CATEGORY_PRODUCT_TITLE_END_SUFFIX')
				);

				$productNm = $pName;

				if (strpos($dataAdd, '{product_name_nolink}') !== false) {
					$productReplacements['{product_name_nolink}'] = $productNm;
				}

				if (strpos($dataAdd, '{product_name}') !== false) {
					$pName = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'class' => '',
							'link' => $link,
							'attr' => 'title="' . $product->product_name . '"',
							'content' => $pName
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$productReplacements['{product_name}'] = $pName;
				}

				if (strpos($dataAdd, '{category_product_link}') !== false) {
					$productReplacements['{category_product_link}'] = $link;
				}

				if (strpos($dataAdd, '{read_more}') !== false) {
					$rmore = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'link' => $link,
							'attr' => 'title="' . $product->product_name . '"',
							'content' => JText::_('COM_REDSHOP_READ_MORE')
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$productReplacements['{read_more}'] = $rmore;
				}

				if (strpos($dataAdd, '{read_more_link}') !== false) {
					$productReplacements['{read_more_link}'] = $link;
				}

				// Related Product List in Lightbox
				// Tag Format = {related_product_lightbox:<related_product_name>[:width][:height]}

				if (strpos($dataAdd, '{related_product_lightbox:') !== false) {
					$relatedProduct = RedshopHelperProduct::getRelatedProduct($product->product_id);
					$rtlnOne = explode("{related_product_lightbox:", $dataAdd);
					$rtlnTwo = explode("}", $rtlnOne[1]);
					$rtlnThree = explode(":", $rtlnTwo[0]);
					$rtln = $rtlnThree[0];
					$rtlnfWidth = (isset($rtlnThree[1])) ? $rtlnThree[1] : "900";
					$rtlnWidthTag = (isset($rtlnThree[1])) ? ":" . $rtlnThree[1] : "";
					$rtlnfHeight = (isset($rtlnThree[2])) ? $rtlnThree[2] : "600";
					$rtlnHeightTag = (isset($rtlnThree[2])) ? ":" . $rtlnThree[2] : "";

					$rtlnTag = "{related_product_lightbox:$rtln$rtlnWidthTag$rtlnHeightTag}";

					if (!empty($relatedProduct)) {
						$linkToRtln = JURI::root() .
							"index.php?option=com_redshop&view=product&pid=" . $product->product_id .
							"&tmpl=component&template=" . $rtln . "&for=rtln";

						$rtlnA = RedshopLayoutHelper::render(
							'tags.common.link',
							array(
								'class' => 'redcolorproductimg',
								'link' => $linkToRtln,
								'content' => JText::_('COM_REDSHOP_RELATED_PRODUCT_LIST_IN_LIGHTBOX')
							),
							'',
							RedshopLayoutHelper::$layoutOption
						);
					} else {
						$rtlnA = "";
					}

					$productReplacements[$rtlnTag] = $rtlnA;
				}

				if (strpos($dataAdd, '{product_s_desc}') !== false) {
					$pSDesc = $redConfiguration->maxchar(
						$product->product_s_desc,
						Redshop::getConfig()->get('CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS'),
						Redshop::getConfig()->get('CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX')
					);

					$productReplacements['{product_s_desc}'] = $pSDesc;
				}

				if (strpos($dataAdd, '{product_desc}') !== false) {
					$pDesc = $redConfiguration->maxchar(
						$product->product_desc,
						Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_MAX_CHARS'),
						Redshop::getConfig()->get('CATEGORY_PRODUCT_DESC_END_SUFFIX')
					);

					$productReplacements['{product_desc}'] = $pDesc;
				}

				if (strpos($dataAdd, '{product_rating_summary}') !== false) {
					// Product Review/Rating Fetching reviews
					$finalAvgReviewData = Redshop\Product\Rating::getRating($product->product_id);

					$productReplacements['{product_rating_summary}'] = $finalAvgReviewData;
				}

				$manufacturerName = isset($product->manufacturer_name) ? $product->manufacturer_name : $product->name;

				if (strpos($dataAdd, '{manufacturer_link}') !== false) {
					$manufacturerLinkHref = JRoute::_(
						'index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $product->manufacturer_id .
						'&Itemid=' . $this->itemid
					);

					$manufacturerLink = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'class' => 'btn btn-primary',
							'link' => $manufacturerLinkHref,
							'attr' => 'title="' . $manufacturerName . '"',
							'content' => $manufacturerName
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$productReplacements['{manufacturer_link}'] = $manufacturerLink;

					if (strpos($dataAdd, "{manufacturer_link}") !== false) {
						$productReplacements['{manufacturer_name}'] = '';
					}
				}

				if (strpos($dataAdd, '{manufacturer_product_link}') !== false) {
					$manuUrl = JRoute::_(
						'index.php?option=com_redshop&view=manufacturers&layout=products&mid=' .
						$product->manufacturer_id . '&Itemid=' . $this->itemid
					);

					$manufacturerPLink = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'class' => 'btn btn-primary',
							'link' => $manuUrl,
							'content' => JText::_(
									"COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS"
								) . ' ' . $manufacturerName
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$productReplacements['{manufacturer_product_link}'] = $manufacturerPLink;
				}

				if (strpos($dataAdd, '{manufacturer_name}') !== false) {
					$productReplacements['{manufacturer_name}'] = $manufacturerName;
				}

				if (strpos($dataAdd, "{product_thumb_image_3}") !== false) {
					$pImgTag = '{product_thumb_image_3}';
					$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_3');
					$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_3');
				} elseif (strpos($dataAdd, "{product_thumb_image_2}") !== false) {
					$pImgTag = '{product_thumb_image_2}';
					$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT_2');
					$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH_2');
				} elseif (strpos($dataAdd, "{product_thumb_image_1}") !== false) {
					$pImgTag = '{product_thumb_image_1}';
					$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
					$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
				} else {
					$pImgTag = '{product_thumb_image}';
					$phThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_HEIGHT');
					$pwThumb = Redshop::getConfig()->get('CATEGORY_PRODUCT_THUMB_WIDTH');
				}

				$hiddenThumbImageWidth = RedshopLayoutHelper::render(
					'tags.common.input',
					array(
						'name' => 'prd_main_imgwidth',
						'id' => 'prd_main_imgwidth',
						'type' => 'hidden',
						'value' => $pwThumb
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$hiddenThumbImageHeight = RedshopLayoutHelper::render(
					'tags.common.input',
					array(
						'name' => 'prd_main_imgheight',
						'id' => 'prd_main_imgheight',
						'type' => 'hidden',
						'value' => $phThumb
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$hiddenThumbImage = $hiddenThumbImageWidth . $hiddenThumbImageHeight;

				$thumbImage = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'span',
						'class' => 'productImageWrap',
						'id' => 'productImageWrapID_' . $product->product_id,
						'attr' => '',
						'text' => Redshop\Product\Image\Image::getImage(
							$product->product_id,
							$link,
							$pwThumb,
							$phThumb,
							2,
							1
						)
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				// Product image flying addwishlist time end
				$productReplacements[$pImgTag] = $thumbImage . $hiddenThumbImage;

				// Front-back image tag...
				if (strpos($dataAdd, "{front_img_link}") !== false
					|| strpos($dataAdd, "{back_img_link}") !== false) {
					if ($product->product_thumb_image) {
						$mainSrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_thumb_image;
					} else {
						$mainSrcPath = RedshopHelperMedia::getImagePath(
							$product->product_full_image,
							'',
							'thumb',
							'product',
							$pwThumb,
							$phThumb,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
					}

					if ($product->product_back_thumb_image) {
						$backSrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_thumb_image;
					} else {
						$backSrcPath = RedshopHelperMedia::getImagePath(
							$product->product_back_full_image,
							'',
							'thumb',
							'product',
							$pwThumb,
							$phThumb,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
					}

					$ahrefpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image;
					$ahrefbackpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_back_full_image;

					$productFrontImageLink = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'link' => '#',
							'attr' => 'onclick="javascript:changeproductImage(' . $product->product_id . ',\'' . $mainSrcPath . '\',\'' . $ahrefpath . '\')";',
							'content' => JText::_('COM_REDSHOP_FRONT_IMAGE')
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$productBackImageLink = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'link' => '#',
							'attr' => 'onclick="javascript:changeproductImage(' . $product->product_id . ',\'' . $backSrcPath . '\',\'' . $ahrefbackpath . '\');"',
							'content' => JText::_('COM_REDSHOP_BACK_IMAGE')
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$productReplacements['{front_img_link}'] = $productFrontImageLink;
					$productReplacements['{back_img_link}'] = $productBackImageLink;
				} else {
					$productReplacements['{front_img_link}'] = '';
					$productReplacements['{back_img_link}'] = '';
				}

				// Front-back image tag end

				// Product preview image.
				if (strpos($dataAdd, '{product_preview_img}') !== false) {
					if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product->product_preview_image)) {
						$preViewSrcPath = RedshopHelperMedia::getImagePath(
							$product->product_preview_image,
							'',
							'thumb',
							'product',
							Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH'),
							Redshop::getConfig()->get('CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT'),
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);

						$previewImg = RedshopLayoutHelper::render(
							'tags.common.img',
							array(
								'src' => $preViewSrcPath,
								'attr' => 'class="rs_previewImg"'
							),
							'',
							RedshopLayoutHelper::$layoutOption
						);

						$productReplacements['{product_preview_img}'] = $previewImg;
					} else {
						$productReplacements['{product_preview_img}'] = '';
					}
				}

				$dataAdd = $this->strReplace($productReplacements, $dataAdd);
				$dataAdd = RedshopHelperProduct::getJcommentEditor($product, $dataAdd);

				/*
				Product loop template extra field
				lat arg set to "1" for indetify parsing data for product tag loop in category
				last arg will parse {producttag:NAMEOFPRODUCTTAG} nameing tags.
				"1" is for section as product
				*/

				if ($extraFieldsForCurrentTemplate && !empty($loadCategoryTemplate)) {
					$dataAdd = Redshop\Helper\ExtraFields::displayExtraFields(
						1,
						$product->product_id,
						$extraFieldsForCurrentTemplate,
						$dataAdd,
						true
					);
				}

				/*
				 Conditional tag
				if product on discount : Yes
				{if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
				NO : // OUTPUT : Display blank
				*/

				$dataAdd = RedshopHelperProduct::getProductOnSaleComment($product, $dataAdd);

				// Replace wishlistbutton
				$dataAdd = RedshopHelperWishlist::replaceWishlistTag($product->product_id, $dataAdd);

				// Replace compare product button
				$dataAdd = Redshop\Product\Compare::replaceCompareProductsButton(
					$product->product_id,
					$this->catid,
					$dataAdd
				);

				$dataAdd = RedshopHelperStockroom::replaceStockroomAmountDetail($dataAdd, $product->product_id);

				// Checking for child products
				if ($product->count_child_products > 0) {
					if (Redshop::getConfig()->get('PURCHASE_PARENT_WITH_CHILD') == 1) {
						$isChilds = false;

						// Get attributes
						$attributesSet = array();

						if ($product->attribute_set_id > 0) {
							$attributesSet = \Redshop\Product\Attribute::getProductAttribute(
								0,
								$product->attribute_set_id,
								0,
								1
							);
						}

						$attributes = \Redshop\Product\Attribute::getProductAttribute($product->product_id);
						$attributes = array_merge($attributes, $attributesSet);
					} else {
						$isChilds = true;
						$attributes = array();
					}
				} else {
					$isChilds = false;

					// Get attributes
					$attributesSet = array();

					if ($product->attribute_set_id > 0) {
						$attributesSet = \Redshop\Product\Attribute::getProductAttribute(
							0,
							$product->attribute_set_id,
							0,
							1
						);
					}

					$attributes = \Redshop\Product\Attribute::getProductAttribute($product->product_id);
					$attributes = array_merge($attributes, $attributesSet);
				}

				// Product attribute  Start
				$totalatt = count($attributes);

				// Check product for not for sale

				$dataAdd = RedshopHelperProduct::getProductNotForSaleComment($product, $dataAdd, $attributes);
				$dataAdd = Redshop\Product\Stock::replaceInStock(
					$product->product_id,
					$dataAdd,
					$attributes,
					$attributeTemplate
				);
				$dataAdd = RedshopHelperAttribute::replaceAttributeData(
					$product->product_id,
					0,
					0,
					$attributes,
					$dataAdd,
					$attributeTemplate,
					$isChilds
				);

				// Get cart tempalte
				$dataAdd = Redshop\Cart\Render::replace(
					$product->product_id,
					$this->catid,
					0,
					0,
					$dataAdd,
					$isChilds,
					$userFieldArr,
					$totalatt,
					$product->total_accessories,
					$countNoUserField
				);

				//  Extra field display
				$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(
					RedshopHelperExtrafields::SECTION_PRODUCT
				);

				$dataAdd = RedshopHelperProductTag::getExtraSectionTag(
					$extraFieldName,
					$product->product_id,
					"1",
					$dataAdd
				);

				$productAvailabilityDate = strstr($dataAdd, "{product_availability_date}");
				$stockNotifyFlag = strstr($dataAdd, "{stock_notify_flag}");
				$stockStatus = strstr($dataAdd, "{stock_status");

				$attributeProductStockStatus = array();

				if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus) {
					$attributeProductStockStatus = RedshopHelperProduct::getproductStockStatus(
						$product->product_id,
						$totalatt
					);
				}

				$dataAdd = \Redshop\Helper\Stockroom::replaceProductStockData(
					$product->product_id,
					0,
					0,
					$dataAdd,
					$attributeProductStockStatus
				);

				RedshopHelperUtility::getDispatcher()->trigger(
					'onAfterDisplayProduct',
					array(&$dataAdd, array(), $product)
				);
				$productData .= $dataAdd;
			}

			if (!$slide) {
				$productTmpl = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'div',
						'id' => 'redcatproducts',
						'text' => $productData
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);
			} else {
				$productTmpl = $productData;
			}

			$productTmpl .= RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'name' => 'slider_texpricemin',
					'id' => 'slider_texpricemin',
					'type' => 'hidden',
					'value' => $texPriceMin
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$productTmpl .= RedshopLayoutHelper::render(
				'tags.common.input',
				array(
					'name' => 'slider_texpricemax',
					'id' => 'slider_texpricemax',
					'type' => 'hidden',
					'value' => $texPriceMax
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			if (strstr($template, "{show_all_products_in_category}")) {
				$replacements['{show_all_products_in_category}'] = '';
				$replacements['{pagination}'] = '';
			}

			$limitBox = '';
			$paginationList = '';
			$usePerPageLimit = false;

			$pagination = new JPagination($this->model->_total, $start, $endLimit);

			if ($this->productPriceSliderEnable) {
				$pagination->setAdditionalUrlParam('texpricemin', $texPriceMin);
				$pagination->setAdditionalUrlParam('texpricemax', $texPriceMax);
			}

			if (strpos($template, "{pagination}") !== false) {
				$paginationList = $pagination->getPagesLinks();
				$replacements['{pagination}'] = $paginationList;
			}

			if (strpos($template, "perpagelimit:") !== false) {
				$usePerPageLimit = true;
				$perPage = explode('{perpagelimit:', $template);
				$perPage = explode('}', $perPage[1]);

				$replacements['{perpagelimit:' . intval($perPage[0]) . '}'] = '';
			}

			if (strpos($template, "{product_display_limit}") !== false) {
				if (!$usePerPageLimit) {
					$limitBox .= RedshopLayoutHelper::render(
						'tags.common.input',
						array(
							'name' => 'texpricemin',
							'type' => 'hidden',
							'value' => $texPriceMin
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$limitBox .= RedshopLayoutHelper::render(
						'tags.common.input',
						array(
							'name' => 'texpricemax',
							'type' => 'hidden',
							'value' => $texPriceMax
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$limitBox = RedshopLayoutHelper::render(
						'tags.common.form',
						array(
							'action' => '',
							'method' => 'post',
							'content' => $limitBox . $pagination->getLimitBox()
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);
				}

				$replacements['{product_display_limit}'] = $limitBox;
			}

			if ($this->productPriceSliderEnable) {
				$productTmpl .= RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'div',
						'id' => 'redcatpagination',
						'attr' => 'style="display:none"',
						'text' => $paginationList
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$productTmpl .= RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'div',
						'id' => 'redPageLimit',
						'attr' => 'style="display:none"',
						'text' => $limitBox
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);
			}

			$replacements['{product_loop_start}'] = '';
			$replacements['{product_loop_end}'] = '';

			$productTmpl = RedshopLayoutHelper::render(
				'tags.common.tag',
				array(
					'tag' => 'div',
					'id' => 'productlist',
					'text' => $productTmpl
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$template = str_replace($templateProduct, $productTmpl, $template);
		}

		if (!$slide) {
			if (strpos($template, "{filter_by}") !== false) {
				$filterByForm = RedshopLayoutHelper::render(
					'tags.category.filter_form',
					array(
						'lists' => $this->lists,
						'texPriceMin' => $texPriceMin,
						'texPriceMax' => $texPriceMax,
						'orderBySelect' => $this->order_by_select,
						'categoryTemplateId' => $this->category_template_id
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				if ($this->lists['manufacturer'] != "") {
					$replacements['{filter_by_lbl}'] = JText::_('COM_REDSHOP_SELECT_FILTER_BY');
				} else {
					$replacements['{filter_by_lbl}'] = '';
				}

				$replacements['{filter_by}'] = $filterByForm;
			}

			if (strpos($template, "{template_selector_category}") !== false) {
				if ($this->lists['category_template'] != "") {
					$templateSelecterForm = RedshopLayoutHelper::render(
						'tags.category.template_selecter_form',
						array(
							'lists' => $this->lists,
							'orderBySelect' => $this->order_by_select,
							'manufacturerId' => $this->manufacturer_id
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$replacements['{template_selector_category_lbl}'] = JText::_(
						'COM_REDSHOP_TEMPLATE_SELECTOR_CATEGORY_LBL'
					);
					$replacements['{template_selector_category}'] = $templateSelecterForm;
				} else {
					$replacements['{template_selector_category_lbl}'] = '';
					$replacements['{template_selector_category}'] = '';
				}
			}

			if (strpos($template, "{order_by}") !== false) {
				$orderByForm = RedshopLayoutHelper::render(
					'tags.category.orderby_form',
					array(
						'lists' => $this->lists,
						'texPriceMin' => $texPriceMin,
						'texPriceMax' => $texPriceMax,
						'manufacturerId' => $this->manufacturer_id,
						'categoryId' => $this->category_id,
						'categoryTemplateId' => $this->category_template_id
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$replacements['{order_by_lbl}'] = JText::_('COM_REDSHOP_SELECT_ORDER_BY');
				$replacements['{order_by}'] = $orderByForm;
			}
		}

		$replacements['{with_vat}'] = '';
		$replacements['{without_vat}'] = '';
		$replacements['{attribute_price_with_vat}'] = '';
		$replacements['{attribute_price_without_vat}'] = '';
		$replacements['{redproductfinderfilter_formstart}'] = '';
		$replacements['{product_price_slider1}'] = '';
		$replacements['{redproductfinderfilter_formend}'] = '';
		$replacements['{redproductfinderfilter:rp_myfilter}'] = '';

		$template = $this->strReplace($replacements, $template);

		return $template;
	}
}
