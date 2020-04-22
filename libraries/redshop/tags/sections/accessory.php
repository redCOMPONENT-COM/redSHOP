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
 * @since  2.1
 */
class RedshopTagsSectionsAccessory extends RedshopTagsAbstract
{
	/**
	 * @var    array
	 *
	 * @since  2.1.5
	 */
	public $selectedAccessory = array();

	/**
	 * @var    array
	 *
	 * @since  2.1.5
	 */
	public $selectedAccessoryQua = array();

	/**
	 * @var    array
	 *
	 * @since  2.1.5
	 */
	public $selectAtt = array();

	/**
	 * @var    integer
	 *
	 * @since  2.1.5
	 */
	public $itemId;


	/**
	 * Init function
	 *
	 * @return mixed|void
	 *
	 * @throws Exception
	 * @since 2.1
	 */
	public function init()
	{
		$input               = JFactory::getApplication()->input;
		$this->itemId        = $input->get('Itemid');
		$selectedAccessories = $this->data['selectedAccessories'];

		if (count($selectedAccessories) > 0) {
			$this->selectedAccessory    = $selectedAccessories[0];
			$this->selectedAccessoryQua = $selectedAccessories[3];
			$this->selectAtt            = array($selectedAccessories[1], $selectedAccessories[2]);
		}
	}

	/**
	 * Executing replace
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function replace()
	{
		$accessories       = $this->data['accessory'];
		$accessoryTemplate = '';
		$attributeTemplate = (object)\Redshop\Template\Helper::getAttribute($this->template);
		$productId         = $this->data['relProductId'] != 0 ? $this->data['relProductId'] : $this->data['productId'];
		$userId            = 0;
		$product           = \Redshop\Product\Product::getProductById($productId);

		$this->replaceMainAccessory($this->data['templateContent'], $product, $userId);


		$subtemplate = $this->getTemplateBetweenLoop('{accessory_product_start}', '{accessory_product_end}');

		if (!empty($accessories)) {
			foreach ($accessories as $accessory) {
				$accessoryTemplate .= $this->replaceAccessory($accessory, $subtemplate['template'], $attributeTemplate);
			}

			$this->template = $subtemplate['begin'] . $accessoryTemplate . $subtemplate['end'];

			$selectedAccessoriesHtml = '';

			if ($this->isTagExists('{selected_accessory_price}') && $this->data['isAjax'] == 0) {
				$selectedAccessoryPrice  = RedshopHelperProductPrice::priceReplacement(0);
				$selectedAccessoriesHtml = RedshopLayoutHelper::render(
					'tags.accessory.selected_accessory_price',
					array(
						'selectedAccessoryPrice' => $selectedAccessoryPrice
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);
			}

			$this->replacements["{selected_accessory_price}"] = $selectedAccessoriesHtml;
			$this->template                                   = $this->strReplace($this->replacements, $this->template);
		}

		return parent::replace();
	}

	/**
	 * Replace Accessory Tags
	 *
	 * @param $accessory
	 * @param $template
	 * @param $attributeTemplate
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function replaceAccessory($accessory, $template, $attributeTemplate)
	{
		$this->replacements = array();
		$accessoryProduct   = \Redshop\Product\Product::getProductById($accessory->child_product_id);
		$commonId           = $this->data['prefix'] . $this->data['productId'] . '_' . $accessory->accessory_id;

		$template = RedshopLayoutHelper::render(
			'tags.accessory.template',
			array(
				'template' => $template,
				'commonId' => $commonId
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$attributeSet = array();

		if ($accessoryProduct->attribute_set_id > 0) {
			$attributeSet = \Redshop\Product\Attribute::getProductAttribute(0, $accessoryProduct->attribute_set_id);
		}

		$attributes = \Redshop\Product\Attribute::getProductAttribute($accessoryProduct->product_id);
		$attributes = array_merge($attributes, $attributeSet);

		// Get accessory final price with VAT rules
		$accessoryPriceWithoutVAT = \Redshop\Product\Accessory::getPrice(
			$this->data['productId'],
			$accessory->newaccessory_price,
			$accessory->accessory_main_price,
			1
		);

		if ($this->isTagExists("{without_vat}")) {
			$accessoryPrices = \Redshop\Product\Accessory::getPrice(
				$this->data['productId'],
				$accessory->newaccessory_price,
				$accessory->accessory_main_price
			);
		} else {
			$accessoryPrices = $accessoryPriceWithoutVAT;
		}

		$accessoryPriceWithoutVAT = $accessoryPriceWithoutVAT[0];

		$accessoryPrice      = $accessoryPrices[0];
		$accessoryMainPrice  = $accessoryPrices[1];
		$accessorySavedPrice = $accessoryPrices[2];

		// @Todo: Refactor template section attribute
		$template = RedshopHelperAttribute::replaceAttributeData(
			$this->data['productId'],
			$accessory->accessory_id,
			$this->data['relProductId'],
			$attributes,
			$template,
			$attributeTemplate,
			$this->data['isChild'],
			$this->selectAtt
		);

		// @Todo: Reacfor section template stock
		$template = Redshop\Product\Stock::replaceInStock($accessory->child_product_id, $template);

		// Accessory attribute  End

		$this->replaceImage($accessory, $template);

		if ($this->isTagExists('{accessory_preview_image}')) {
			$imageUrl = RedshopHelperMedia::getImagePath(
				$accessoryProduct->product_preview_image,
				'',
				'thumb',
				'product',
				Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH'),
				Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT'),
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
			);

			$previewImage = RedshopLayoutHelper::render(
				'tags.accessory.preview_image',
				array(
					'accessoryId' => $accessory->child_product_id,
					'imageUrl'    => $imageUrl,
					'productInfo' => $accessoryProduct
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements['{accessory_preview_image}'] = $previewImage;
		}

		$accessoryChecked = "";

		if (($this->data['isAjax'] == 1 && in_array($accessory->accessory_id, $this->selectedAccessory))
			|| ($this->data['isAjax'] == 0 && $accessory->setdefault_selected)) {
			$accessoryChecked = "checked";
		}

		if ($this->isTagExists('{accessory_add_chkbox}')) {
			$this->replaceAddCheckbox(
				$accessory,
				$template,
				$commonId,
				$attributes,
				$accessoryPrice,
				$accessoryPriceWithoutVAT,
				$accessoryChecked
			);
		}

		$checkboxLbl = RedshopLayoutHelper::render(
			'tags.common.label',
			array(
				'text'  => JText::_('COM_REDSHOP_ACCESSORY_ADD_CHKBOX_LBL') . '&nbsp;' . $accessory->product_name,
				'id'    => 'accessory_checkbox_lbl' . $accessory->accessory_id,
				'class' => 'accessory_checkbox_lbl'
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$this->replacements["{accessory_add_chkbox_lbl}"] = $checkboxLbl;

		if ($this->isTagExists('{accessory_title}')) {
			$accessoryProductName = RedshopHelperUtility::maxChars(
				$accessoryProduct->product_name,
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
			);

			$htmlTitle = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'text'  => $accessoryProductName,
					'id'    => 'accessory_' . $accessory->accessory_id,
					'class' => 'accessory-title accessory_' . $accessory->accessory_id
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{accessory_title}"] = $htmlTitle;
		}

		if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
				|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get(
						'SHOW_QUOTATION_PRICE'
					)))) {
			if ($this->isTagExists('{accessory_price}')) {
				$class    = 'accessory-price accessory_' . $accessory->accessory_id;
				$template = $this->replaceTagPrice('{accessory_price}', $accessoryPrice, $template, $class);
			}

			if ($this->isTagExists('{accessory_main_price}')) {
				$class    = 'accessory-main-price accessory_' . $accessory->accessory_id;
				$template = $this->replaceTagPrice('{accessory_main_price}', $accessoryMainPrice, $template, $class);
			}

			if ($this->isTagExists('{accessory_price_saving}')) {
				$class    = 'accessory-price-saving accessory_' . $accessory->accessory_id;
				$template = $this->replaceTagPrice('{accessory_price_saving}', $accessorySavedPrice, $template, $class);
			}
		} else {
			$this->replacements["{accessory_price}"]        = '';
			$this->replacements["{accessory_main_price}"]   = '';
			$this->replacements["{accessory_price_saving}"] = '';
		}

		if ($this->isTagExists('{accessory_short_desc}')) {
			$accessoryShortDesc = RedshopHelperUtility::maxChars(
				$accessory->product_s_desc,
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS'),
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX')
			);

			$htmlShortDesc = RedshopLayoutHelper::render(
				'tags.common.short_desc',
				array(
					'text'  => $accessoryShortDesc,
					'class' => 'accessory-short-desc accessory_' . $accessory->accessory_id
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{accessory_short_desc}"] = $htmlShortDesc;
		}

		if ($this->isTagExists('{accessory_quantity}')) {
			if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')) {
				$key    = array_search($accessory->accessory_id, $this->selectedAccessory);
				$accqua = ($accessoryChecked != "" && isset($this->selectedAccessoryQua[$key]) && $this->selectedAccessoryQua[$key])
					? $this->selectedAccessoryQua[$key] : 1;

				$accessoryQuantity = RedshopLayoutHelper::render(
					'tags.accessory.quantity',
					array(
						'accqua'   => $accqua,
						'commonId' => $commonId,
						'name'     => "accquantity_" . $this->data['prefix'] . $this->data['productId']
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements["{accessory_quantity}"]     = $accessoryQuantity;
				$this->replacements["{accessory_quantity_lbl}"] = JText::_('COM_REDSHOP_QUANTITY');
			} else {
				$this->replacements["{accessory_quantity}"]     = '';
				$this->replacements["{accessory_quantity_lbl}"] = '';
			}
		}

		$this->replacements["{product_number}"] = $accessory->product_number;

		$readMoreLink = JRoute::_(
			'index.php?option=com_redshop&view=product&pid=' . $accessory->child_product_id . '&Itemid=' . $this->itemId,
			false
		);

		if ($this->isTagExists('{accessory_readmore}')) {
			$accessoryReadMore = RedshopLayoutHelper::render(
				'tags.common.readmore',
				array(
					'title'        => $accessory->product_name,
					'readMoreLink' => $readMoreLink,
					'class'        => "accessory-readmore accessory_readmore_" . $accessory->accessory_id
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{accessory_readmore}"] = $accessoryReadMore;
		}

		$this->replacements["{accessory_readmore_link}"] = $readMoreLink;

		if ($this->isTagExists('{manufacturer_name}') || $this->isTagExists("{manufacturer_link}")) {
			$manufacturer = RedshopEntityManufacturer::getInstance($accessoryProduct->manufacturer_id)->getItem();

			if (!empty($manufacturer)) {
				$manufacturerUrl = JRoute::_(
					'index.php?option=com_redshop&view=manufacturers&layout=products&mid='
					. $manufacturer->id . '&Itemid=' . $this->itemId
				);

				$manufacturerLink = RedshopLayoutHelper::render(
					'tags.common.link',
					array(
						'class'   => 'btn btn-primary accessory-manufacture-link',
						'link'    => $manufacturerUrl,
						'content' => JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS")
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements["{manufacturer_name}"] = $manufacturer->name;
				$this->replacements["{manufacturer_link}"] = $manufacturerLink;
			} else {
				$this->replacements["{manufacturer_name}"] = '';
				$this->replacements["{manufacturer_link}"] = '';
			}
		}

		$this->replaceCustomField($accessory, $template);

		return $this->strReplace($this->replacements, $template);
	}

	/**
	 * Replace customfield
	 *
	 * @param object $accessory
	 * @param string $template
	 *
	 * @return  void
	 *
	 * @since   2.1.5
	 */
	public function replaceCustomField($accessory, &$template)
	{
		$fields = RedshopHelperExtrafields::getSectionFieldList(
			RedshopHelperExtrafields::SECTION_PRODUCT,
			1,
			1
		);

		if (count($fields) > 0) {
			foreach ($fields as $field) {
				$fieldValues = RedshopHelperExtrafields::getSectionFieldDataList(
					$field->id,
					1,
					$accessory->child_product_id
				);

				if ($fieldValues && $fieldValues->data_txt != ""
					&& $field->show_in_front == 1 && $field->published == 1) {
					$this->replacements['{' . $field->name . '}']     = $fieldValues->data_txt;
					$this->replacements['{' . $field->name . '_lbl}'] = $field->title;
				} else {
					$this->replacements['{' . $field->name . '}']     = '';
					$this->replacements['{' . $field->name . '_lbl}'] = '';
				}
			}

			$template = $this->strReplace($this->replacements, $template);
		}
	}

	/**
	 * Replace tags price
	 *
	 * @param           $tag
	 * @param           $price
	 * @param           $template
	 * @param string    $class
	 *
	 * @return string
	 *
	 * @since 2.1.5
	 */
	public function replaceTagPrice($tag, $price, $template, $class = '')
	{
		$htmlPrice = RedshopHelperProductPrice::formattedPrice($price);
		$tagPrice  = RedshopLayoutHelper::render(
			'tags.common.price',
			array(
				'price'     => $price,
				'htmlPrice' => $htmlPrice,
				'class'     => $class
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$this->replacements[$tag] = $tagPrice;

		return $this->strReplace($this->replacements, $template);
	}

	/**
	 * @param $accessory
	 * @param $template
	 *
	 *
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function replaceImage($accessory, &$template)
	{
		$input            = JFactory::getApplication()->input;
		$itemId           = $input->get('Itemid');
		$accessoryImg     = '';
		$infoAccessoryImg = $this->getWidthHeight(
			$template,
			'accessory_image',
			'ACCESSORY_THUMB_HEIGHT',
			'ACCESSORY_THUMB_WIDTH'
		);

		$accessoryImgTag      = $infoAccessoryImg['imageTag'];
		$accessoryWidthThumb  = $infoAccessoryImg['width'];
		$accessoryHeightThumb = $infoAccessoryImg['height'];


		$accessoryProductLink = JRoute::_(
			'index.php?option=com_redshop&view=product&pid=' . $accessory->child_product_id . '&Itemid=' . $itemId,
			false
		);

		JPluginHelper::importPlugin('redshop_product');

		// Trigger to change product image.
		$accessoryImg = RedshopHelperUtility::getDispatcher()->trigger(
			'changeProductImage',
			array(
				$accessoryImg,
				$accessory,
				$accessoryProductLink,
				$accessoryWidthThumb,
				$accessoryHeightThumb,
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX'),
				''
			)
		);

		$accessoryImage = $accessory->product_full_image;

		if (empty($accessoryImg)) {
			if (Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX') == 1) {
				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryImage)) {
					$thumbUrl = RedshopHelperMedia::getImagePath(
						$accessoryImage,
						'',
						'thumb',
						'product',
						$accessoryWidthThumb,
						$accessoryHeightThumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);

					$imageUrl = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $accessoryImage;
				} else {
					$thumbUrl = RedshopHelperMedia::getImagePath(
						'noimage.jpg',
						'',
						'thumb',
						'',
						$accessoryWidthThumb,
						$accessoryHeightThumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);

					$imageUrl       = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";
					$accessoryImage = 'noimage.jpg';
				}

				$accessoryImg = RedshopLayoutHelper::render(
					'tags.accessory.image.lightbox',
					array(
						'accessoryImage'       => $accessoryImage,
						'accessoryId'          => $accessory->accessory_id,
						'thumbUrl'             => $thumbUrl,
						'imageUrl'             => $imageUrl,
						'accessoryWidthThumb'  => $accessoryWidthThumb,
						'accessoryHeightThumb' => $accessoryHeightThumb
					),
					'',
					array(
						'component'  => 'com_redshop',
						'layoutType' => 'Twig',
						'layoutOf'   => 'library'
					)
				);
			} else {
				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryImage)) {
					$thumbUrl = RedshopHelperMedia::getImagePath(
						$accessoryImage,
						'',
						'thumb',
						'product',
						$accessoryWidthThumb,
						$accessoryHeightThumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
				} else {
					$thumbUrl = RedshopHelperMedia::getImagePath(
						'noimage.jpg',
						'',
						'thumb',
						'',
						$accessoryWidthThumb,
						$accessoryHeightThumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
				}

				$accessoryImg = RedshopLayoutHelper::render(
					'tags.accessory.image.no_lightbox',
					array(
						'accessoryProductLink' => $accessoryProductLink,
						'accessoryId'          => $accessory->accessory_id,
						'thumbUrl'             => $thumbUrl,
						'accessoryWidthThumb'  => $accessoryWidthThumb,
						'accessoryHeightThumb' => $accessoryHeightThumb
					),
					'',
					array(
						'component'  => 'com_redshop',
						'layoutType' => 'Twig',
						'layoutOf'   => 'library'
					)
				);
			}
		}

		$this->replacements[$accessoryImgTag] = $accessoryImg;
		$template                             = $this->strReplace($this->replacements, $template);
	}

	/**
	 * Replace add checkbox
	 *
	 * @param object $accessory
	 * @param string $template
	 * @param string $commonId
	 * @param array  $attributes
	 * @param string $accessoryPrice
	 * @param string $accessoryPriceWithoutVAT
	 * @param string $accessoryChecked
	 *
	 * @return  void
	 *
	 * @since   2.1.5
	 */
	public function replaceAddCheckbox(
		$accessory,
		&$template,
		$commonId,
		$attributes,
		$accessoryPrice,
		$accessoryPriceWithoutVAT,
		$accessoryChecked
	) {
		$checkbox = RedshopLayoutHelper::render(
			'tags.accessory.add_chkbox',
			array(
				'productId'                => $this->data['productId'],
				'accessoryId'              => $accessory->accessory_id,
				'commonId'                 => $commonId,
				'attributes'               => $attributes,
				'relProductId'             => $this->data['relProductId'],
				'prefix'                   => $this->data['prefix'],
				'accessoryPriceWithoutVAT' => $accessoryPriceWithoutVAT,
				'accessoryPrice'           => $accessoryPrice,
				'accessoryChecked'         => $accessoryChecked
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		$this->replacements['{accessory_add_chkbox}'] = $checkbox;
		$template                                     = $this->strReplace($this->replacements, $template);
	}

	/**
	 * @param $product
	 *
	 * @return string
	 *
	 * @since 2.1.5
	 */
	public function replaceMainAccessoryReadmore($product)
	{
		$accessoryMainReadMore = RedshopLayoutHelper::render(
			'tags.common.readmore',
			array(
				'title'        => $product->product_name,
				'readMoreLink' => '#',
				'class'        => "accessory-main-readmore accessory_readmore_" . $product->product_id
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		return $accessoryMainReadMore;
	}

	/**
	 * @param $product
	 *
	 * @return string
	 *
	 * @since 2.1.5
	 */
	public function replaceMainAccessoryTitle($product)
	{
		$mainTitle = RedshopHelperUtility::limitText(
			$product->product_name,
			Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
			Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
		);

		$htmlTitle = RedshopLayoutHelper::render(
			'tags.common.label',
			array(
				'text'  => $mainTitle,
				'id'    => '',
				'class' => 'accessory-main-title'
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		return $htmlTitle;
	}

	/**
	 * @param $product
	 *
	 * @return string
	 *
	 * @since 2.1.5
	 */
	public function replaceMainAccessoryShortDesc($product)
	{
		$mainShortDesc = RedshopHelperUtility::limitText(
			$product->product_s_desc,
			Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS'),
			Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX')
		);

		$htmlShortDesc = RedshopLayoutHelper::render(
			'tags.common.short_desc',
			array(
				'text'  => $mainShortDesc,
				'class' => 'accessory-main-short-desc'
			),
			'',
			RedshopLayoutHelper::$layoutOption
		);

		return $htmlShortDesc;
	}

	/**
	 * @param $product
	 * @param $accessoryWidthThumb
	 * @param $accessoryHeightThumb
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function replaceMainAccessoryImage($product, $accessoryWidthThumb, $accessoryHeightThumb)
	{
		$accessoryMainImage  = $product->product_full_image;
		$accessoryMainImage2 = '';

		if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryMainImage)) {
			$thumbUrl = RedshopHelperMedia::getImagePath(
				$accessoryMainImage,
				'',
				'thumb',
				'product',
				$accessoryWidthThumb,
				$accessoryHeightThumb,
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
			);

			if (Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX') == 1) {
				$accessoryMainImage2 = RedshopLayoutHelper::render(
					'tags.accessory.image.lightbox',
					array(
						'accessoryImage'       => $accessoryMainImage,
						'accessoryId'          => '',
						'thumbUrl'             => $thumbUrl,
						'imageUrl'             => REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $accessoryMainImage,
						'accessoryWidthThumb'  => $accessoryWidthThumb,
						'accessoryHeightThumb' => $accessoryHeightThumb
					),
					'',
					array(
						'component'  => 'com_redshop',
						'layoutType' => 'Twig',
						'layoutOf'   => 'library'
					)
				);
			} else {
				$accessoryMainImage2 = RedshopLayoutHelper::render(
					'tags.accessory.image.no_lightbox',
					array(
						'accessoryProductLink' => '',
						'accessoryId'          => '',
						'thumbUrl'             => $thumbUrl,
						'accessoryWidthThumb'  => $accessoryWidthThumb,
						'accessoryHeightThumb' => $accessoryHeightThumb
					),
					'',
					array(
						'component'  => 'com_redshop',
						'layoutType' => 'Twig',
						'layoutOf'   => 'library'
					)
				);
			}
		}

		return $accessoryMainImage2;
	}

	/**
	 * @param $templateContent
	 * @param $product
	 * @param $userId
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function replaceMainAccessory($templateContent, $product, $userId)
	{
		$this->replacements = array();

		$subTemplate = $this->getTemplateBetweenLoop('{if accessory_main}', '{accessory_main end if}');

		if (!$subTemplate) {
			return false;
		}

		$template = $subTemplate['template'];

		if ($this->isTagExists('{accessory_main_short_desc}')) {
			$this->replacements['{accessory_main_short_desc}'] = $this->replaceMainAccessoryShortDesc($product);
		}

		if ($this->isTagExists('{accessory_main_title}')) {
			$this->replacements['{accessory_main_title}'] = $this->replaceMainAccessoryTitle($product);
		}

		if ($this->isTagExists('{accessory_main_readmore}')) {
			$this->replacements['{accessory_main_readmore}'] = $this->replaceMainAccessoryReadmore($product);
		}

		$infoAccessoryMainImg = $this->getWidthHeight(
			$template,
			'accessory_main_image',
			'ACCESSORY_THUMB_HEIGHT',
			'ACCESSORY_THUMB_WIDTH'
		);

		$this->replacements[$infoAccessoryMainImg['imageTag']] = $this->replaceMainAccessoryImage(
			$product,
			$infoAccessoryMainImg['width'],
			$infoAccessoryMainImg['height']
		);
		$productPrices                                         = array();

		// @Todo Check selected accessory price
		if ($this->isTagExists('{accessory_mainproduct_price}') || strpos(
				$templateContent,
				"{selected_accessory_price}"
			) !== false) {
			$productPrices = RedshopHelperProductPrice::getNetPrice($product->product_id, $userId, 1, $templateContent);
		}

		if ($this->isTagExists('{accessory_mainproduct_price}')) {
			if (Redshop::getConfig()->get('SHOW_PRICE')
				&& (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
					|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
						&& Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))) {
				$accessoryMainProductPrice = RedshopHelperProductPrice::priceReplacement(
					$productPrices['product_price']
				);

				$this->replacements["{accessory_mainproduct_price}"] = $accessoryMainProductPrice;
			}
		}

		$template = $this->strReplace($this->replacements, $template);

		// @Todo refactor stock
		$template = Redshop\Product\Stock::replaceInStock($product->product_id, $template);

		$this->template = $subTemplate['begin'] . $template . $subTemplate['end'];
	}
}
