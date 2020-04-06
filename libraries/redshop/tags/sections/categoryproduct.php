<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  3.0.1
 */
class RedshopTagsSectionsCategoryProduct extends RedshopTagsAbstract
{
	/**
	 * @var  mixed
	 *
	 * @since   3.0.1
	 */
	public $detail;

	/**
	 * Init
	 *
	 * @return  mixed
	 *
	 * @since   3.0.1
	 */
	public function init()
	{
		$this->detail = $this->data['detail'];
	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION
	 */
	public function replace()
	{
		if ($this->data['params']->get('show_page_heading', 0)) {
			$this->template = RedshopLayoutHelper::render(
					'tags.category_product.page_heading',
					[
						'catId'          => $this->data['catId'],
						'params'         => $this->data['params'],
						'pageHeadingTag' => $this->data['pageHeadingTag']
					],
					'',
					$this->optionLayout
				) . $this->template;
		}

		$print = $this->input->getBool('print', false);

		if (empty($this->detail)) {
			$this->template = RedshopLayoutHelper::render(
				'tags.common.tag',
				[
					'tag'   => 'div',
					'class' => 'product-empty',
					'text'  => JText::_('COM_REDSHOP_ALL_CATEGORY_VIEW_NO_RESULT_TEXT')
				],
				'',
				$this->optionLayout
			);
		}

		if ($print) {
			$this->replacements['{product_price_slider}'] = '';
			$this->replacements['{pagination}']           = '';
		}

		$printHtml = RedshopLayoutHelper::render(
			'tags.common.print',
			['onClick' => 'onclick="window.print();"'],
			'',
			$this->optionLayout
		);

		$this->replacements['{print}'] = ($print) ? '' : $printHtml;

		$this->replacements['{category_frontpage_introtext}'] = Redshop::getConfig()->get(
			'CATEGORY_FRONTPAGE_INTROTEXT'
		);

		$this->replaceCategory();

		if ($this->isTagExists('{filter_by}')) {
			$this->replacements['{filter_by_lbl}'] = '';
			$this->replacements['{filter_by}']     = '';
		}

		if ($this->isTagExists('{template_selector_category}')) {
			$this->replacements['{template_selector_category_lbl}'] = JText::_(
				'COM_REDSHOP_TEMPLATE_SELECTOR_CATEGORY_LBL'
			);
			$this->replacements['{template_selector_category}']     = RedshopLayoutHelper::render(
				'tags.category_product.selector_category',
				[
					'listCategoryTemplate' => $this->data['lists']['category_template'],
					'manufacturerId'       => $this->data['manufacturerId'],
					'orderBySelect'        => $this->data['orderBySelect']
				],
				'',
				$this->optionLayout
			);
		}

		if ($this->isTagExists('{order_by}')) {
			$this->replacements['{order_by_lbl}'] = '';
			$this->replacements['{order_by}']     = '';
		}

		if ($this->isTagExists('{pagination}')) {
			$this->replacements['{pagination}'] = $this->data['model']->getCategoryProductPagination()->getPagesLinks();
		}

		$this->replacements['{with_vat}']                    = '';
		$this->replacements['{without_vat}']                 = '';
		$this->replacements['{attribute_price_with_vat}']    = '';
		$this->replacements['{attribute_price_without_vat}'] = '';

		$this->template = $this->strReplace($this->replacements, $this->template);
		$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

		return parent::replace();
	}

	/**
	 * Replace category
	 *
	 * @return  void
	 *
	 * @since   3.0.1
	 */
	private function replaceCategory()
	{
		$templateCat = $this->getTemplateBetweenLoop('{category_loop_start}', '{category_loop_end}');

		if (!empty($templateCat)) {
			$infoImgCategory = $this->getWidthHeight(
				$templateCat['template'],
				'category_thumb_image',
				'THUMB_HEIGHT',
				'THUMB_WIDTH'
			);

			$extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(2, 1, 1);
			$dataAdd        = "";

			for ($i = 0; $i < count($this->detail); $i++) {
				$dataAdd .= $this->replaceCategoryItem(
					$templateCat['template'],
					$infoImgCategory,
					$this->detail[$i],
					$extraFieldName
				);
			}

			$this->template = $templateCat['begin'] . $dataAdd . $templateCat['end'];
		}
	}

	/**
	 * Replace category item
	 *
	 * @param string $template
	 * @param array  $infoImgCategory
	 * @param object $category
	 * @param array  $extraFieldName
	 *
	 * @return  string
	 *
	 * @since   3.0.1
	 */
	private function replaceCategoryItem($template, $infoImgCategory, $category, $extraFieldName)
	{
		$cItemId                 = RedshopHelperRouter::getCategoryItemid($category->id);
		$replaceCategoryItemData = [];

		if ($cItemId != "") {
			$tmpItemId = $cItemId;
		} else {
			$tmpItemId = $this->itemId;
		}

		$link       = JRoute::_(
			'index.php?option=com_redshop&view=category&cid=' . $category->id . '&layout=detail&Itemid=' . $tmpItemId
		);
		$middlePath = REDSHOP_FRONT_IMAGES_RELPATH . 'category/';
		$productImg = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";
		$linkImage  = $productImg;

		if ($category->category_full_image && file_exists($middlePath . $category->category_full_image)) {
			$productImg = RedshopHelperMedia::watermark(
				'category',
				$category->category_full_image,
				$infoImgCategory['width'],
				$infoImgCategory['height'],
				Redshop::getConfig()->get(
					'WATERMARK_CATEGORY_THUMB_IMAGE'
				)
			);

			$linkImage = RedshopHelperMedia::watermark(
				'category',
				$category->category_full_image,
				'',
				'',
				Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE')
			);
		} elseif (Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE') && file_exists(
				$middlePath . Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE')
			)) {
			$productImg = RedshopHelperMedia::watermark(
				'category',
				Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'),
				$infoImgCategory['width'],
				$infoImgCategory['height'],
				Redshop::getConfig()->get(
					'WATERMARK_CATEGORY_THUMB_IMAGE'
				)
			);
			$linkImage  = RedshopHelperMedia::watermark(
				'category',
				Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'),
				'',
				'',
				Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE')
			);
		}

		$replaceCategoryItemData[$infoImgCategory['imageTag']] = RedshopLayoutHelper::render(
			'tags.category_product.thumb_image',
			[
				'category'   => $category,
				'linkImage'  => $linkImage,
				'productImg' => $productImg
			],
			'',
			$this->optionLayout
		);

		if (strstr($template, '{category_name}')) {
			$replaceCategoryItemData['{category_name}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => $link,
					'content' => $category->name,
					'class'   => 'category_name',
					'attr'    => 'title="' . $category->name . '"'
				],
				'',
				$this->optionLayout
			);
		}

		if (strstr($template, '{category_readmore}')) {
			$replaceCategoryItemData['{category_readmore}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => $link,
					'content' => JText::_('COM_REDSHOP_READ_MORE'),
					'class'   => 'category_name',
					'attr'    => 'title="' . $category->name . '"'
				],
				'',
				$this->optionLayout
			);
		}

		if (strstr($template, '{category_description}')) {
			$catDesc = RedshopHelperUtility::maxChars(
				$category->description,
				Redshop::getConfig()->get(
					'CATEGORY_SHORT_DESC_MAX_CHARS'
				),
				Redshop::getConfig()->get(
					'CATEGORY_SHORT_DESC_END_SUFFIX'
				)
			);

			$replaceCategoryItemData['{category_description}'] = $catDesc;
		}

		if (strstr($template, '{category_short_desc}')) {
			$catShortDesc = RedshopHelperUtility::maxChars(
				$category->short_description,
				Redshop::getConfig()->get(
					'CATEGORY_SHORT_DESC_MAX_CHARS'
				),
				Redshop::getConfig()->get(
					'CATEGORY_SHORT_DESC_END_SUFFIX'
				)
			);

			$replaceCategoryItemData['{category_short_desc}'] = $catShortDesc;
		}

		if (strstr($template, '{category_total_product}')) {
			$totalProduct = RedshopHelperProduct::getProductCategory(
				$category->id
			);

			$replaceCategoryItemData['{category_total_product}']     = count($totalProduct);
			$replaceCategoryItemData['{category_total_product_lbl}'] = JText::_('COM_REDSHOP_TOTAL_PRODUCT');
		}

		/*
		 * category template extra field
		 * "2" argument is set for category
		 */
		$template = RedshopHelperProductTag::getExtraSectionTag($extraFieldName, $category->id, "2", $template);
		$template = $this->strReplace($replaceCategoryItemData, $template);
		$template = $this->replaceProduct($template, $category->id);

		return $template;
	}

	/**
	 * Replace product
	 *
	 * @param string  $template
	 * @param integer $catId
	 *
	 * @return  string
	 *
	 * @since   3.0.1
	 */
	private function replaceProduct($template, $catId)
	{
		$templateProduct = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}', $template);

		if (!empty($templateProduct)) {
			$attributeTemplate = \Redshop\Template\Helper::getAttribute($templateProduct['template']);
			$extraFieldName    = \Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);
			$productDataAdd    = "";

			$products = $this->data['model']->getCategorylistProduct($catId);

			for ($j = 0; $j < count($products); $j++) {
				$productDataAdd .= $this->replaceProductItem(
					$products[$j],
					$templateProduct['template'],
					$extraFieldName,
					$attributeTemplate,
					$catId
				);
			}

			$template = $templateProduct['begin'] . $productDataAdd . $templateProduct['end'];
		}

		return $template;
	}

	/**
	 * Replace product item
	 *
	 * @param object  $product
	 * @param string  $template
	 * @param array   $extraFieldName
	 * @param object  $attributeTemplate
	 * @param integer $catId
	 *
	 * @return  string
	 *
	 * @since   3.0.1
	 */
	private function replaceProductItem($product, $template, $extraFieldName, $attributeTemplate, $catId)
	{
		$productId              = (int)$product->product_id;
		$replaceProductItemData = [];

		if (!is_object($product)) {
			return false;
		}

		$countNoUserField = 0;

		// Counting accessory
		$accessoryList  = RedshopHelperAccessory::getProductAccessories(0, $productId);
		$totalAccessory = count($accessoryList);

		// Product User Field Start
		$hiddenUserField          = "";
		$productUserFieldTemplate = \Redshop\Product\Product::getProductUserfieldFromTemplate(
			$template
		);
		$templateUserField        = $productUserFieldTemplate[0];
		$userFieldArr             = $productUserFieldTemplate[1];

		if ($templateUserField != "") {
			$userFieldReplaceData = $this->replaceProductUserField($template, $userFieldArr, $productId);
			$countNoUserField     = $userFieldReplaceData['countNoUserField'];

			if ($userFieldReplaceData['uField'] != "") {
				$productUserFieldsForm = RedshopLayoutHelper::render(
					'tags.common.form',
					[
						'tag'     => 'form',
						'method'  => 'post',
						'id'      => 'user_fields_form_' . $product->product_id,
						'class'   => 'user_fields_form_' . $product->product_id,
						'content' => $userFieldReplaceData['uField']
					],
					'',
					$this->optionLayout
				);

				$template = $productUserFieldTemplate['begin'] . $productUserFieldsForm . $productUserFieldTemplate['end'];
			} else {
				$template = $productUserFieldTemplate['begin'] . $productUserFieldTemplate['end'];
			}
		} elseif (Redshop::getConfig()->get('AJAX_CART_BOX')) {
			$ajaxDetailTemplateDesc = "";
			$ajaxDetailTemplate     = \Redshop\Template\Helper::getAjaxDetailBox($product);

			if (null !== $ajaxDetailTemplate) {
				$ajaxDetailTemplateDesc = $ajaxDetailTemplate->template_desc;
			}

			$productUserFieldTemplate = \Redshop\Product\Product::getProductUserfieldFromTemplate(
				$ajaxDetailTemplateDesc
			);

			$templateUserField    = $productUserFieldTemplate[0];
			$userFieldArr         = $productUserFieldTemplate[1];
			$userFieldReplaceData = $this->replaceProductUserField($templateUserField, $userFieldArr, $productId);
			$countNoUserField     = $userFieldReplaceData['countNoUserField'];

			if ($userFieldReplaceData['template'] != "") {
				$hiddenUserField = RedshopLayoutHelper::render(
					'tags.category_product.hidden_user_field',
					[
						'productId' => $productId,
						'content'   => $userFieldReplaceData['template']
					],
					'',
					$this->optionLayout
				);
			}
		}

		$template = $template . $hiddenUserField;
		/************** end user fields ***************************/

		$itemData = RedshopHelperProduct::getMenuInformation(0, 0, '', 'product&pid=' . $productId);

		if (!empty($itemData)) {
			$pItemid = $itemData->id;
		} else {
			$pItemid = RedshopHelperRouter::getItemId($productId);
		}

		$replaceProductItemData['{product_id_lbl}']     = JText::_('COM_REDSHOP_PRODUCT_ID_LBL');
		$replaceProductItemData['{product_id}']         = $productId;
		$replaceProductItemData['{product_number_lbl}'] = JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL');
		$replaceProductItemData['{product_number_lbl}'] = JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL');
		$replaceProductItemData['{product_number}']     = RedshopLayoutHelper::render(
			'tags.common.tag',
			[
				'tag'  => 'span',
				'id'   => 'product_number_variable' . $productId,
				'text' => $product->product_number
			],
			'',
			$this->optionLayout
		);

		$replaceProductItemData['{product_size}'] = RedshopHelperProduct::redunitDecimal(
				$product->product_volume
			) . "&nbsp;" . RedshopLayoutHelper::render(
				'tags.common.tag',
				[
					'tag'   => 'span',
					'class' => 'product_unit_variable',
					'text'  => Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . "3"
				],
				'',
				$this->optionLayout
			);

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

		$replaceProductItemData['{product_length}'] = RedshopHelperProduct::redunitDecimal(
				$product->product_length
			) . "&nbsp;" . $productUnit;

		$replaceProductItemData['{product_width}'] = RedshopHelperProduct::redunitDecimal(
				$product->product_width
			) . "&nbsp;" . $productUnit;

		$replaceProductItemData['{product_height}'] = RedshopHelperProduct::redunitDecimal(
				$product->product_height
			) . "&nbsp;" . $productUnit;

		$template            = RedshopHelperTax::replaceVatInformation($template);
		$this->data['catId'] = isset($catId) ? $catId : '';
		$link                = JRoute::_(
			'index.php?option=com_redshop&view=product&pid=' .
			$productId . '&cid=' . $this->data['catId'] . '&Itemid=' . $pItemid
		);

		if (strstr($template, '{product_name}')) {
			$productName = RedshopHelperUtility::maxChars(
				$product->product_name,
				Redshop::getConfig()->get(
					'CATEGORY_PRODUCT_TITLE_MAX_CHARS'
				),
				Redshop::getConfig()->get(
					'CATEGORY_PRODUCT_TITLE_END_SUFFIX'
				)
			);

			$replaceProductItemData['{product_name}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => $link,
					'attr'    => 'title="' . $product->product_name . '"',
					'content' => $productName
				],
				'',
				$this->optionLayout
			);
		}

		if (strstr($template, '{read_more}')) {
			$replaceProductItemData['{read_more}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => $link,
					'attr'    => 'title="' . $product->product_name . '"',
					'content' => JText::_('COM_REDSHOP_READ_MORE')
				],
				'',
				$this->optionLayout
			);
		}

		if (strstr($template, '{read_more_link}')) {
			$replaceProductItemData['{read_more_link}'] = $link;
		}

		if (strstr($template, '{product_s_desc}')) {
			$productShortDesc = RedshopHelperUtility::maxChars(
				$product->product_s_desc,
				Redshop::getConfig()->get(
					'CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS'
				),
				Redshop::getConfig()->get(
					'CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX'
				)
			);

			$replaceProductItemData['{product_s_desc}'] = $productShortDesc;
		}

		if (strstr($template, '{product_desc}')) {
			$productDesc = RedshopHelperUtility::maxChars(
				$product->product_desc,
				Redshop::getConfig()->get(
					'CATEGORY_PRODUCT_DESC_MAX_CHARS'
				),
				Redshop::getConfig()->get(
					'CATEGORY_PRODUCT_DESC_END_SUFFIX'
				)
			);

			$replaceProductItemData['{product_desc}'] = $productDesc;
		}

		if (strstr($template, '{product_rating_summary}')) {
			// Product Review/Rating Fetching reviews
			$replaceProductItemData['{product_rating_summary}'] = Redshop\Product\Rating::getRating($productId);
		}

		$manufacturerName = RedshopEntityManufacturer::getInstance($product->manufacturer_id)->getItem()->name;

		if (strstr($template, '{manufacturer_link}')) {
			$replaceProductItemData['{manufacturer_link}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => JRoute::_(
						'index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' .
						$product->manufacturer_id . '&Itemid=' . $this->itemId
					),
					'class'   => 'btn btn-primary',
					'attr'    => 'title="' . $manufacturerName . '"',
					'content' => $manufacturerName
				],
				'',
				$this->optionLayout
			);

			if (strstr($template, "{manufacturer_link}")) {
				$replaceProductItemData['{manufacturer_name}'] = '';
			}
		}

		if (strstr($template, '{manufacturer_product_link}')) {
			$replaceProductItemData['{manufacturer_product_link}'] = RedshopLayoutHelper::render(
				'tags.common.link',
				[
					'link'    => JRoute::_(
						'index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $product->manufacturer_id .
						'&Itemid=' . $this->itemId
					),
					'class'   => 'btn btn-primary',
					'attr'    => 'title="' . $manufacturerName . '"',
					'content' => JText::_(
							"COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS"
						) . ' ' . $manufacturerName
				],
				'',
				$this->optionLayout
			);
		}

		if (strstr($template, '{manufacturer_name}')) {
			$replaceProductItemData['{manufacturer_name}'] = $manufacturerName;
		}

		$productThumbImgData = $this->getWidthHeight(
			$template,
			'product_thumb_image',
			'CATEGORY_PRODUCT_THUMB_HEIGHT',
			'CATEGORY_PRODUCT_THUMB_WIDTH'
		);

		// Product image flying addwishlist time start.
		$thumImage = Redshop\Product\Image\Image::getImage(
			$productId,
			$link,
			$productThumbImgData['width'],
			$productThumbImgData['height'],
			2,
			1
		);

		// Product image flying addwishlist time end.

		$replaceProductItemData[$productThumbImgData['imageTag']] = RedshopLayoutHelper::render(
			'tags.product.thumb_image',
			[
				'thumbImg'  => $thumImage,
				'productId' => $productId,
				'width'     => $productThumbImgData['width'],
				'height'    => $productThumbImgData['height']
			],
			'',
			$this->optionLayout
		);

		$template = RedshopHelperProduct::getJcommentEditor($product, $template);

		/*
		 * Product loop template extra field
		 * lat arg set to "1" for indetify parsing data for product tag loop in category
		 * last arg will parse {producttag:NAMEOFPRODUCTTAG} nameing tags.
		 * "1" is for section as product.
		 */

		$template = RedshopHelperProductTag::getExtraSectionTag(
			$extraFieldName,
			$productId,
			"1",
			$template,
			1
		);

		/************************************
		 *  Conditional tag
		 *  if product on discount : Yes
		 *  {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
		 *  NO : // OUTPUT : Display blank
		 ************************************/
		$template = RedshopHelperProduct::getProductOnSaleComment($product, $template);

		// Replace wishlistbutton.
		$template = RedshopHelperWishlist::replaceWishlistTag($productId, $template);

		// Replace compare product button.
		$template = Redshop\Product\Compare::replaceCompareProductsButton(
			$productId,
			(int)$this->data['catId'],
			$template
		);

		$template = RedshopHelperStockroom::replaceStockroomAmountDetail(
			$template,
			$productId
		);

		// Checking for child products.
		$childProduct = RedshopHelperProduct::getChildProduct($productId);

		if (count($childProduct) > 0) {
			$isChild    = true;
			$attributes = array();
		} else {
			$isChild = false;

			// Get attributes.
			$attributesSet = array();

			if ($product->attribute_set_id > 0) {
				$attributesSet = \Redshop\Product\Attribute::getProductAttribute(
					0,
					$product->attribute_set_id,
					0,
					1
				);
			}

			$attributes = \Redshop\Product\Attribute::getProductAttribute($productId);
			$attributes = array_merge($attributes, $attributesSet);
		}

		// Product attribute - Start.
		$totalAttr = count($attributes);

		// Check product for not for sale.
		$template = RedshopHelperProduct::getProductNotForSaleComment(
			$product,
			$template,
			$attributes
		);

		$template = Redshop\Product\Stock::replaceInStock(
			$productId,
			$template,
			$attributes,
			$attributeTemplate
		);

		$template = RedshopTagsReplacer::_(
			'attributes',
			$template,
			array(
				'productId'         => $productId,
				'attributes'        => $attributes,
				'attributeTemplate' => $attributeTemplate,
				'isChild'           => $isChild,
			)
		);

		// Get cart tempalte.
		$template = Redshop\Cart\Render::replace(
			$productId,
			(int)$this->data['catId'],
			0,
			0,
			$template,
			$isChild,
			$userFieldArr,
			$totalAttr,
			$totalAccessory,
			$countNoUserField
		);

		$template = $this->strReplace($replaceProductItemData, $template);

		return $template;
	}

	/**
	 * Replace product user field
	 *
	 * @param string  $template
	 * @param array   $userFieldArr
	 * @param integer $productId
	 *
	 * @return  string
	 * @since   3.0.1
	 */
	private function replaceProductUserField($template, $userFieldArr, $productId)
	{
		$uField           = "";
		$countNoUserField = 0;

		for ($ui = 0; $ui < count($userFieldArr); $ui++) {
			$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields(
				$userFieldArr[$ui],
				12,
				'',
				'',
				0,
				$productId
			);

			$uField .= $productUserFields[1];

			if ($productUserFields[1] != "") {
				$countNoUserField++;
			}

			$template = str_replace(
				'{' . $userFieldArr[$ui] . '_lbl}',
				$productUserFields[0],
				$template
			);
			$template = str_replace(
				'{' . $userFieldArr[$ui] . '}',
				$productUserFields[1],
				$template
			);
		}

		return [
			'template'         => $template,
			'countNoUserField' => $countNoUserField,
			'uField'           => $uField

		];
	}
}