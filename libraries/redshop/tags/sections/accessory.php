<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @since  __DEPLOY_VERSION__
	 */
	public $selectedAccessory = array();

	/**
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $selectedAccessoryQua = array();

	/**
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $selectAtt = array();

	/**
	 * @var    integer
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $itemId;


	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since   2.1
	 */
	public function init()
	{
		$input = JFactory::getApplication()->input;
		$this->itemId = $input->get('Itemid');
		$selectedAccessories  = $this->data['selectedAccessories'];

		if (count($selectedAccessories) > 0)
		{
			$this->selectedAccessory    = $selectedAccessories[0];
			$this->selectedAccessoryQua = $selectedAccessories[3];
			$this->selectAtt            = array($selectedAccessories[1], $selectedAccessories[2]);
		}
	}

	/**
	 * Execute replace
	 *
	 * @return  string
	 *
	 * @since   2.0.0.5
	 */
	public function replace()
	{
		$accessories       = $this->data['accessory'];
		$accessoryTemplate = '';
		$attributeTemplate = (object) \Redshop\Template\Helper::getAttribute($this->template);
		$productId         = $this->data['relProductId'] != 0 ? $this->data['relProductId'] : $this->data['productId'];
		$userId            = 0;
		$product           = RedshopHelperProduct::getProductById($productId);

		$this->replaceMainAccessory($this->data['templateContent'], $product, $userId);


		$subtemplate = $this->getTemplateBetweenLoop('{accessory_product_start}', '{accessory_product_end}');

		if (!empty($accessories))
		{
			foreach ($accessories as $accessory)
			{
				$accessoryTemplate .= $this->replaceAccessory($accessory, $subtemplate['template'], $attributeTemplate);
			}

			$this->template = $subtemplate['begin'] . $accessoryTemplate . $subtemplate['end'];

			$selectedAccessoriesHtml = '';

			if ($this->isTagExists('{selected_accessory_price}') && $this->data['isAjax'] == 0)
			{
				$selectedAccessoryPrice  = RedshopHelperProductPrice::priceReplacement(0);
				$selectedAccessoriesHtml = RedshopLayoutHelper::render(
					'tags.accessory.selected_accessory_price',
					array(
						'selectedAccessoryPrice' => $selectedAccessoryPrice
					)
				);
			}

			$this->template = str_replace("{selected_accessory_price}", $selectedAccessoriesHtml, $this->template);
		}

		return parent::replace();
	}

	/**
	 * Replace accessory
	 *
	 * @param   object   $accessory
	 * @param   string   $template
	 * @param   object   $attributeTemplate
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replaceAccessory($accessory, $template, $attributeTemplate)
	{
		$accessoryProduct   = RedshopHelperProduct::getProductById($accessory->child_product_id);
		$commonId           = $this->data['prefix'] . $this->data['productId'] . '_' . $accessory->accessory_id;

		$template = RedshopLayoutHelper::render(
			'tags.accessory.template',
			array(
				'template' => $template,
				'commonId' => $commonId
			)
		);

		$attributeSet = array();

		if ($accessoryProduct->attribute_set_id > 0)
		{
			$attributeSet = RedshopHelperProduct_Attribute::getProductAttribute(0, $accessoryProduct->attribute_set_id);
		}

		$attributes = RedshopHelperProduct_Attribute::getProductAttribute($accessoryProduct->product_id);
		$attributes = array_merge($attributes, $attributeSet);

		// Get accessory final price with VAT rules
		$accessoryPriceWithoutVAT = \Redshop\Product\Accessory::getPrice(
			$this->data['productId'],
			$accessory->newaccessory_price,
			$accessory->accessory_main_price,
			1
		);

		if ($this->isTagExists("{without_vat}"))
		{
			$accessoryPrices = \Redshop\Product\Accessory::getPrice(
				$this->data['productId'],
				$accessory->newaccessory_price,
				$accessory->accessory_main_price
			);
		}
		else
		{
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

		if ($this->isTagExists('{accessory_preview_image}'))
		{
			$imageUrl    = RedshopHelperMedia::getImagePath(
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
				array(
					'component' => 'com_redshop'
				)
			);

			$template = str_replace('{accessory_preview_image}', $previewImage, $template);
		}

		$accessoryChecked = "";

		if (($this->data['isAjax'] == 1 && in_array($accessory->accessory_id, $this->selectedAccessory))
			|| ($this->data['isAjax'] == 0 && $accessory->setdefault_selected))
		{
			$accessoryChecked = "checked";
		}

		if ($this->isTagExists('{accessory_add_chkbox}'))
		{
			$this->replaceAddCheckbox($accessory, $template, $commonId, $attributes, $accessoryPrice, $accessoryPriceWithoutVAT, $accessoryChecked);
		}

		$template   = str_replace(
			"{accessory_add_chkbox_lbl}",
			JText::_('COM_REDSHOP_ACCESSORY_ADD_CHKBOX_LBL') . '&nbsp;' . $accessory->product_name,
			$template
		);

		if ($this->isTagExists('{accessory_title}'))
		{
			$accessoryProductName = RedshopHelperUtility::maxChars(
				$accessory->product_name,
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
			);

			$htmlTitle = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'text' => $accessoryProductName,
					'id' => 'accessory_' . $accessory->accessory_id,
					'class' => 'accessory-title accessory_' . $accessory->accessory_id
				)
			);

			$template = str_replace('{accessory_title}', $htmlTitle, $template);
		}

		if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
				|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))))
		{
			if ($this->isTagExists('{accessory_price}'))
			{
				$class    = 'accessory-price accessory_' . $accessory->accessory_id;
				$template = $this->replaceTagPrice('{accessory_price}', $accessoryPrice, $template, $class);
			}

			if ($this->isTagExists('{accessory_main_price}'))
			{
				$class    = 'accessory-main-price accessory_' . $accessory->accessory_id;
				$template = $this->replaceTagPrice('{accessory_main_price}', $accessoryMainPrice, $template, $class);
			}

			if ($this->isTagExists('{accessory_price_saving}'))
			{
				$class    = 'accessory-price-saving accessory_' . $accessory->accessory_id;
				$template = $this->replaceTagPrice('{accessory_price_saving}', $accessorySavedPrice, $template, $class);
			}
		}
		else
		{
			$template = str_replace("{accessory_price}", '', $template);
			$template = str_replace("{accessory_main_price}", '', $template);
			$template = str_replace("{accessory_price_saving}", '', $template);
		}

		if ($this->isTagExists('{accessory_short_desc}'))
		{
			$accessoryShortDesc = RedshopHelperUtility::maxChars(
				$accessory->product_s_desc,
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS'),
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX')
			);

			$htmlShorDesc = RedshopLayoutHelper::render(
				'tags.common.short_desc',
				array(
					'text' => $accessoryShortDesc,
					'class' => 'accessory-short-desc accessory_' . $accessory->accessory_id
				)
			);

			$template = str_replace('{accessory_short_desc}', $htmlShorDesc, $template);
		}

		if ($this->isTagExists('{accessory_quantity}'))
		{
			if (Redshop::getConfig()->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE'))
			{

				$key    = array_search($accessory->accessory_id, $this->selectedAccessory);
				$accqua = ($accessoryChecked != "" && isset($this->selectedAccessoryQua[$key]) && $this->selectedAccessoryQua[$key])
					? $this->selectedAccessoryQua[$key] : 1;

				$accessoryQuantity = RedshopLayoutHelper::render(
					'tags.accessory.quantity',
					array(
						'accqua' => $accqua,
						'commonId' => $commonId,
						'name' => "accquantity_". $this->data['prefix'] . $this->data['productId']
					)
				);

				$template = str_replace("{accessory_quantity}", $accessoryQuantity, $template);
				$template = str_replace("{accessory_quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY'), $template);
			}
			else
			{
				$template = str_replace("{accessory_quantity}", "", $template);
				$template = str_replace("{accessory_quantity_lbl}", "", $template);
			}
		}

		$template = str_replace("{product_number}", $accessory->product_number, $template);

		$readMoreLink = JRoute::_(
			'index.php?option=com_redshop&view=product&pid=' . $accessory->child_product_id . '&Itemid=' . $this->itemId,
			false
		);

		if ($this->isTagExists('{accessory_readmore}'))
		{
			$accessoryReadMore = RedshopLayoutHelper::render(
				'tags.common.readmore',
				array(
					'title' => $accessory->product_name,
					'readMoreLink' => $readMoreLink,
					'class' => "accessory-readmore accessory_readmore_" . $accessory->accessory_id
				)
			);

			$template = str_replace("{accessory_readmore}", $accessoryReadMore, $template);
		}

		$template = str_replace("{accessory_readmore_link}", $readMoreLink, $template);

		if ($this->isTagExists('{manufacturer_name}') || $this->isTagExists("{manufacturer_link}"))
		{
			$manufacturer = RedshopEntityManufacturer::getInstance($accessoryProduct->manufacturer_id)->getItem();

			if (!empty($manufacturer))
			{
				$manufacturerUrl = JRoute::_(
					'index.php?option=com_redshop&view=manufacturers&layout=products&mid='
					. $manufacturer->id . '&Itemid=' . $this->itemId
				);

				$manufacturerLink = RedshopLayoutHelper::render(
					'tags.common.link',
					array(
						'class' => 'accessory-manufacture-link',
						'link' => $manufacturerUrl,
						'content' => JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS")
					)
				);

				$template = str_replace("{manufacturer_name}", $manufacturer->name, $template);
				$template = str_replace("{manufacturer_link}", $manufacturerLink, $template);
			}
			else
			{
				$template = str_replace("{manufacturer_name}", '', $template);
				$template = str_replace("{manufacturer_link}", '', $template);
			}
		}

		$this->replaceCustomField($accessory, $template);

		return $template;
	}

	/**
	 * Replace customfield
	 *
	 * @param   object   $accessory
	 * @param   string   $template
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replaceCustomField($accessory, &$template)
	{
		$fields = RedshopHelperExtrafields::getSectionFieldList(
			RedshopHelperExtrafields::SECTION_PRODUCT, 1, 1
		);

		if (count($fields) > 0)
		{
			foreach ($fields as $field)
			{
				$fieldValues = RedshopHelperExtrafields::getSectionFieldDataList(
					$field->id, 1, $accessory->child_product_id
				);

				if ($fieldValues && $fieldValues->data_txt != ""
					&& $field->show_in_front == 1 && $field->published == 1)
				{
					$template = str_replace('{' . $field->name . '}', $fieldValues->data_txt, $template);
					$template = str_replace('{' . $field->name . '_lbl}', $field->title, $template);
				}
				else
				{
					$template = str_replace('{' . $field->name . '}', "", $template);
					$template = str_replace('{' . $field->name . '_lbl}', "", $template);
				}
			}
		}
	}

	/**
	 * Replace tag price
	 *
	 * @param   string   $tag
	 * @param   integer  $price
	 * @param   object   $accessory
	 * @param   string   $template
	 * @param   string   $class
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replaceTagPrice($tag, $price, $template, $class = '')
	{
		$htmlPrice = RedshopHelperProductPrice::formattedPrice($price);
		$tagPrice = RedshopLayoutHelper::render(
			'tags.common.price',
			array(
				'price' => $price,
				'htmlPrice' => $htmlPrice,
				'class' => $class
			)
		);

		return str_replace($tag, $tagPrice, $template);
	}

	/**
	 * Replace image
	 *
	 * @param   object   $accessory
	 * @param   string   $template
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replaceImage($accessory, &$template)
	{
		$input        = JFactory::getApplication()->input;
		$itemId       = $input->get('Itemid');
		$accessoryImg = '';

		$this->getWidthHeight($template, 'accessory_image',  $accessoryImgTag, $accessoryWidthThumb, $accessoryHeightThumb);

		$accessoryProductLink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $accessory->child_product_id . '&Itemid=' . $itemId, false);

		JPluginHelper::importPlugin('redshop_product');

		// Trigger to change product image.
		RedshopHelperUtility::getDispatcher()->trigger(
			'changeProductImage',
			array(
				&$accessoryImg,
				$accessory,
				$accessoryProductLink,
				$accessoryWidthThumb,
				$accessoryHeightThumb,
				Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX'),
				''
			)
		);

		$accessoryImage = $accessory->product_full_image;

		if (empty($accessoryImg))
		{
			if (Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX') == 1)
			{
				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryImage))
				{
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
				}
				else
				{
					$thumbUrl = RedshopHelperMedia::getImagePath(
						'noimage.jpg',
						'',
						'thumb',
						'',
						$accessoryWidthThumb,
						$accessoryHeightThumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);

					$imageUrl = REDSHOP_FRONT_IMAGES_ABSPATH. "noimage.jpg";
					$accessoryImage = 'noimage.jpg';
				}

				$accessoryImg = RedshopLayoutHelper::render(
					'tags.accessory.image.lightbox',
					array(
						'accessoryImage' => $accessoryImage,
						'accessoryId' => $accessory->accessory_id,
						'thumbUrl' => $thumbUrl,
						'imageUrl' => $imageUrl,
						'accessoryWidthThumb'    => $accessoryWidthThumb,
						'accessoryHeightThumb' => $accessoryHeightThumb
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);
			}
			else
			{
				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryImage))
				{
					$thumbUrl = RedshopHelperMedia::getImagePath(
						$accessoryImage,
						'',
						'thumb',
						'product',
						$accessoryWidthThumb,
						$accessoryHeightThumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
				}
				else
				{
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
						'accessoryId' => $accessory->accessory_id,
						'thumbUrl' => $thumbUrl,
						'accessoryWidthThumb'    => $accessoryWidthThumb,
						'accessoryHeightThumb' => $accessoryHeightThumb
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);
			}
		}

		$template = str_replace($accessoryImgTag, $accessoryImg, $template);
	}

	/**
	 * Replace add checkbox
	 *
	 * @param   object   $accessory
	 * @param   string   $template
	 * @param   string   $commonId
	 * @param   array    $attributes
	 * @param   string   $accessoryPrice
	 * @param   string   $accessoryPriceWithoutVAT
	 * @param   string   $accessoryChecked
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replaceAddCheckbox($accessory, &$template, $commonId, $attributes, $accessoryPrice, $accessoryPriceWithoutVAT, $accessoryChecked)
	{
		$checkbox = RedshopLayoutHelper::render(
			'tags.accessory.add_chkbox',
			array(
				'productId'   => $this->data['productId'],
				'accessoryId' => $accessory->accessory_id,
				'commonId'    => $commonId,
				'attributes'  => $attributes,
				'relProductId' => $this->data['relProductId'],
				'prefix' => $this->data['prefix'],
				'accessoryPriceWithoutVAT' => $accessoryPriceWithoutVAT,
				'accessoryPrice' => $accessoryPrice,
				'accessoryChecked' => $accessoryChecked
			),
			'',
			array(
				'component' => 'com_redshop'
			)
		);

		$template = str_replace('{accessory_add_chkbox}', $checkbox, $template);
	}

	/**
	 * Replace main accessory
	 *
	 * @param   string    $templateContent
	 * @param   object    $product
	 * @param   integer   $userId
	 *
	 * @return  string|boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function replaceMainAccessory($templateContent, $product, $userId)
	{
		$subTemplate       = $this->getTemplateBetweenLoop('{if accessory_main}', '{accessory_main end if}');

		if (!$subTemplate)
		{
			return false;
		}

		$template = $subTemplate['template'];

		if ($this->isTagExists('{accessory_main_short_desc}'))
		{
			$mainShortDesc = RedshopHelperUtility::limitText(
					$product->product_s_desc,
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_MAX_CHARS'),
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_DESC_END_SUFFIX')
			);

			$htmlShorDesc = RedshopLayoutHelper::render(
				'tags.common.short_desc',
				array(
					'text' => $mainShortDesc,
					'class' => 'accessory-main-short-desc'
				)
			);

			$template = str_replace('{accessory_main_short_desc}', $htmlShorDesc, $template);
		}

		if ($this->isTagExists('{accessory_main_title}'))
		{
			$mainTitle = RedshopHelperUtility::limitText(
					$product->product_name,
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_MAX_CHARS'),
					Redshop::getConfig()->get('ACCESSORY_PRODUCT_TITLE_END_SUFFIX')
			);

			$htmlTitle = RedshopLayoutHelper::render(
				'tags.common.label',
				array(
					'text' => $mainTitle,
					'id' => '',
					'class' => 'accessory-main-title'
				)
			);

			$template = str_replace('{accessory_main_title}', $htmlTitle, $template);
		}

		if ($this->isTagExists('{accessory_main_readmore}'))
		{
			$accessoryMainReadMore = RedshopLayoutHelper::render(
				'tags.common.readmore',
				array(
					'title' => $product->product_name,
					'readMoreLink' => '#',
					'class' => "accessory-main-readmore accessory_readmore_" . $product->product_id
				)
			);

			$template = str_replace("{accessory_main_readmore}", $accessoryMainReadMore, $template);
		}

		$accessoryMainImage     = $product->product_full_image;
		$accessoryMainImage2    = '';

		$this->getWidthHeight($template, 'accessory_main_image' ,$accessoryImgTag, $accessoryWidthThumb, $accessoryHeightThumb);

		if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessoryMainImage))
		{
			$thumbUrl = RedshopHelperMedia::getImagePath(
				$accessoryMainImage,
				'',
				'thumb',
				'product',
				$accessoryWidthThumb,
				$accessoryHeightThumb,
				Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
			);

			if (Redshop::getConfig()->get('ACCESSORY_PRODUCT_IN_LIGHTBOX') == 1)
			{
				$accessoryMainImage2 = RedshopLayoutHelper::render(
					'tags.accessory.image.lightbox',
					array(
						'accessoryImage' => $accessoryMainImage,
						'accessoryId' => '',
						'thumbUrl' => $thumbUrl,
						'imageUrl' => REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $accessoryMainImage,
						'accessoryWidthThumb'    => $accessoryWidthThumb,
						'accessoryHeightThumb' => $accessoryHeightThumb
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);
			}
			else
			{
				$accessoryMainImage2 = RedshopLayoutHelper::render(
					'tags.accessory.image.no_lightbox',
					array(
						'accessoryProductLink' => '',
						'accessoryId' => '',
						'thumbUrl' => $thumbUrl,
						'accessoryWidthThumb'    => $accessoryWidthThumb,
						'accessoryHeightThumb' => $accessoryHeightThumb
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);
			}
		}

		$template = str_replace($accessoryImgTag, $accessoryMainImage2, $template);
		$productPrices   = array();

		// @Todo Check selected accessory price
		if ($this->isTagExists('{accessory_mainproduct_price}') || strpos($templateContent, "{selected_accessory_price}") !== false)
		{
			$productPrices = RedshopHelperProductPrice::getNetPrice($product->product_id, $userId, 1, $templateContent);
		}

		if ($this->isTagExists('{accessory_mainproduct_price}'))
		{
			if (Redshop::getConfig()->get('SHOW_PRICE')
				&& (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
					|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
						&& Redshop::getConfig()->get('SHOW_QUOTATION_PRICE'))))
			{
				$accessoryMainProductPrice = RedshopHelperProductPrice::priceReplacement($productPrices['product_price']);

				$template = str_replace("{accessory_mainproduct_price}", $accessoryMainProductPrice, $template);
			}
		}

		// @Todo refactor stock
		$template   = Redshop\Product\Stock::replaceInStock($product->product_id, $template);

		$this->template = $subTemplate['begin'] . $template . $subTemplate['end'];
	}

	/**
	 * Get width height
	 *
	 * @param   string    $template
	 * @param   string    $type
	 * @param   string    $imageTag
	 * @param   integer   $width
	 * @param   integer   $height
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getWidthHeight($template, $type, &$imageTag, &$width, &$height)
	{
		if (strpos($template, '{' . $type . '_3}') !== false)
		{
			$imageTag = '{' . $type . '_3}';
			$height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_3');
			$width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_3');
		}
		elseif (strpos($template, '{' . $type . '_2}') !== false)
		{
			$imageTag = '{' . $type . '_2}';
			$height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT_2');
			$width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH_2');
		}
		elseif (strpos($template, '{' . $type . '_1}') !== false)
		{
			$imageTag = '{' . $type . '_1}';
			$height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
			$width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
		}
		else
		{
			$imageTag = '{' . $type . '}';
			$height   = Redshop::getConfig()->get('ACCESSORY_THUMB_HEIGHT');
			$width    = Redshop::getConfig()->get('ACCESSORY_THUMB_WIDTH');
		}
	}
}
