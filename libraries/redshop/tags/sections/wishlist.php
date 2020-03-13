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
 * @since 3.0
 */
class RedshopTagsSectionsWishlist extends RedshopTagsAbstract
{
	/**
	 * @var    integer
	 *
	 * @since 3.0
	 */
	public $wishlistId;

	/**
	 * @var    integer
	 *
	 * @since 3.0
	 */
	public $itemId;

	/**
	 * @var    array
	 *
	 * @since 3.0
	 */
	public $productModel = array();

	/**
	 * @var    integer
	 *
	 * @since 3.0
	 */
	public $mainId = null;

	/**
	 * @var    integer
	 *
	 * @since 3.0
	 */
	public $totalId = null;

	/**
	 * @var    integer
	 *
	 * @since 3.0
	 */
	public $totalCountNoUserField = null;

	/**
	 * @var    array
	 *
	 * @since  2.1.5
	 */
	public $extraFieldName = array();

	/**
	 * @var    integer
	 *
	 * @since 3.0
	 */
	public $view;

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since 3.0
	 */
	public function init()
	{
		$input = JFactory::getApplication()->input;
		$this->wishlistId = $input->getInt('wishlist_id');
		$this->itemId = $input->getInt('Itemid');
		$this->view = $input->get('view');
	}

	/**
	 * Executing replace
	 * @return string
	 *
	 * @throws Exception
	 * @since 3.0
	 */
	public function replace()
	{
		if ($this->view == 'product' || $this->view == 'category')
		{
			if (empty($this->data['productId']))
			{
				return;
			}

			JHtml::script('com_redshop/redshop.wishlist.min.js', false, true);

			$productId = $this->data['productId'];
			$formId    = $this->data['formId'];
			$user      = JFactory::getUser();
			$link      = '';

			if (!$user->guest)
			{
				$link = JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=wishlist&task=addtowishlist&tmpl=component';
			}
			else
			{
				if (Redshop::getConfig()->get('WISHLIST_LOGIN_REQUIRED') != 0)
				{
					$link = JRoute::_('index.php?option=com_redshop&view=login&wishlist=1&product_id=' . $productId);
				}
			}

			if ($this->isTagExists('{wishlist_button}'))
			{
				$wishlistButton = RedshopLayoutHelper::render(
					'tags.product.wishlist_button',
					array(
						'link'      => $link,
						'productId' => $productId,
						'formId'    => $formId
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);

				$this->replacements["{wishlist_button}"] = $wishlistButton;
				$this->template = $this->strReplace($this->replacements, $this->template);
			}

			if ($this->isTagExists('{wishlist_link}') || $this->isTagExists('{property_wishlist_link}'))
			{
				$wishlistLink = RedshopLayoutHelper::render(
					'tags.product.wishlist_link',
					array(
						'link'      => $link,
						'productId' => $productId,
						'formId'    => $formId
					),
					'',
					array(
						'component' => 'com_redshop'
					)
				);

				$this->replacements["{wishlist_link}"] = $wishlistLink;
				$this->replacements["{property_wishlist_link}"] = $wishlistLink;
				$this->template = $this->strReplace($this->replacements, $this->template);
			}

			return $this->template;
		}
		elseif ($this->view == 'account')
		{
			$this->productModel = $this->data['productModel'];
			$this->extraFieldName = Redshop\Helper\ExtraFields::getSectionFieldNames(1, 1, 1);
			$wishlistTemplate = '';
			$wishlists = $this->data['wishlist'];
			$templateProduct = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}');

			if (count($wishlists) > 0)
			{
				$link = JURI::root() . "index.php?option=com_redshop&view=account&layout=mywishlist&mail=1&tmpl=component&wishlist_id=" . $this->wishlistId;

				foreach ($wishlists as $wishlist)
				{
					$wishlistTemplate .= $this->replaceProduct($wishlist, $templateProduct['template']);
				}

				$this->template = $templateProduct['begin'] . $wishlistTemplate . $templateProduct['end'];

				if ($this->isTagExists('{mail_link}'))
				{
					$srcImage = RedshopLayoutHelper::render(
						'tags.common.img',
						array(
							'src'     => REDSHOP_MEDIA_IMAGES_ABSPATH . 'mailcenter16.png'
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$mailLink = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'class'     => 'redcolorproductimg',
							'link'      => $link,
							'content'   => $srcImage
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$this->replacements["{mail_link}"] = $mailLink;
					$this->template = $this->strReplace($this->replacements, $this->template);
				}

				if ($this->isTagExists('{back_link}'))
				{
					$backLink = RedshopLayoutHelper::render(
						'tags.common.link',
						array(
							'link'      => JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $this->itemId),
							'content'   => JText::_('COM_REDSHOP_BACK_TO_MYACCOUNT'),
							'attr'      => 'title="' . JText::_('COM_REDSHOP_BACK_TO_MYACCOUNT'). '"'
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$this->replacements["{back_link}"] = $backLink;
					$this->template = $this->strReplace($this->replacements, $this->template);
				}

				if ($this->isTagExists('{all_cart}'))
				{
					$backLink = RedshopLayoutHelper::render(
						'tags.wishlist.all_cart',
						array(
							'wishlist'      => count($wishlists),
							'mainId'        => $this->mainId,
							'totalId'      => $this->totalId,
							'totalCountNoUserField' => $this->totalCountNoUserField
						),
						'',
						RedshopLayoutHelper::$layoutOption
					);

					$this->replacements["{all_cart}"] = $backLink;
					$this->template = $this->strReplace($this->replacements, $this->template);
				}
			}
			else
			{
				$this->replacements["{mail_link}"] = '';
				$this->template = $this->strReplace($this->replacements, $this->template);
			}

			$this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

			if (count($wishlists) > 0)
			{
				return $this->template;
			}
			else
			{
				$noWishList = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag'       => 'dev',
						'class'     => 'nowishlist',
						'id'        => 'nowishlist',
						'text'      => JText::_('COM_REDSHOP_NO_PRODUCTS_IN_WISHLIST')
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->template = $noWishList;
				return $this->template;
			}
		}
	}

	/**
	 * Get template content between loop tags
	 *
	 * @param   string  $beginTag  Begin tag
	 * @param   string  $endTag    End tag
	 * @param   string  $template  Template
	 *
	 * @return  mixed
	 *
	 * @since 3.0
	 */
	public function getTemplateBetweenLoop($beginTag, $endTag, $template = '')
	{
		if ($this->isTagExists($beginTag) && $this->isTagExists($endTag))
		{
			$templateStartData = explode($beginTag, $this->template);

			if (!empty($template))
			{
				$templateStartData = explode($beginTag, $template);
			}

			$templateStart     = $templateStartData [0];

			$templateEndData = explode($endTag, $templateStartData [1]);
			$templateEnd     = $templateEndData[1];

			$templateMain = $templateEndData[0];

			return array(
				'begin'    => $templateStart,
				'template' => $templateMain,
				'end'      => $templateEnd
			);
		}

		return false;
	}

	/**
	 * @param $templateProduct
	 * @param $wishlist
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 3.0
	 */
	public function replaceProduct($wishlist, $templateProduct)
	{
		// @Todo: Refactor template section product
		$wishlist->wishlistData = RedshopHelperWishlist::getWishlist($wishlist->wishlist_id);
		$session       = JFactory::getSession();
		$isIndividualAddToCart = (boolean) Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE');
		$wishlistuserfielddata  = RedshopHelperWishlist::getUserFieldData($wishlist->wishlist_id, $wishlist->product_id);
		$link                   = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $wishlist->product_id . '&Itemid=' . $this->itemId);
		$linkRemove            = 'index.php?option=com_redshop&view=account&layout=mywishlist&wishlist_id=' . $this->wishlistId
			. '&pid=' . $wishlist->product_id . '&remove=1';

		if ($isIndividualAddToCart)
		{
			$linkRemove .= '&wishlist_product_id=' . $wishlist->wishlistData->wishlist_product_id;
		}

		$linkRemove = JRoute::_($linkRemove . '&Itemid=' . $this->itemId, false);
		$imageThumbProduct = $this->getWidthHeight($templateProduct, 'product_thumb_image', 'THUMB_HEIGHT', 'THUMB_WIDTH');
		$thumbImage             = Redshop\Product\Image\Image::getImage($wishlist->product_id, $link, $imageThumbProduct['width'], $imageThumbProduct['height']);

		if ($this->isTagExists($imageThumbProduct['imageTag']))
		{
			$this->replacements[$imageThumbProduct["imageTag"]] = $thumbImage;
			$templateProduct = $this->strReplace($this->replacements, $templateProduct);
		}

		if ($this->isTagExists('{product_number}'))
		{
			$productNumber = RedshopLayoutHelper::render(
				'tags.common.tag',
				array(
					'tag'      => 'dev',
					'class'    => "product_$wishlist->product_number",
					'id'       => "product_$wishlist->product_number",
					'text'      => $wishlist->product_number
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{product_number}"] = $productNumber;
			$templateProduct = $this->strReplace($this->replacements, $templateProduct);
		}

		if ($this->isTagExists('{product_name}'))
		{
			$nameAddress = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link'      => $link,
					'content'   => $wishlist->product_name
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{product_name}"] = $nameAddress;
			$templateProduct = $this->strReplace($this->replacements, $templateProduct);
		}

		// Checking for child products start
		if ($this->isTagExists("{child_products}"))
		{
			$parentproductid = $wishlist->product_id;

			if ($this->data->product_parent_id != 0)
			{
				$parentproductid = RedshopHelperProduct::getMainParentProduct($wishlist->product_id);
			}

			$frmChild = "";

			if ($parentproductid != 0)
			{
				$productInfo = \Redshop\Product\Product::getProductById($parentproductid);

				// Get child products
				$childProducts = $this->productModel->getAllChildProductArrayList(0, $parentproductid);

				if (count($childProducts) > 0)
				{
					$childProducts = array_merge(array($productInfo), $childProducts);

					$cldName = array();

					if (count($childProducts) > 0)
					{
						$parentId = 0;

						for ($c = 0, $cn = count($childProducts); $c < $cn; $c++)
						{
							if ($childProducts[$c]->product_parent_id == 0)
							{
								$level = "";
							}

							$parentId = $childProducts[$c]->product_parent_id;

							$childProducts[$c]->product_name = $level . $childProducts[$c]->product_name;
						}

						$cldName = @array_merge($cldName, $childProducts);
					}

					$selected                  = array($wishlist->product_id);
					$lists['product_child_id'] = JHTML::_('select.genericlist', $cldName, 'pid', 'class="inputbox" size="1"  onchange="document.frmChild.submit();"', 'product_id', 'product_name', $selected);

					$frmChild .= "<form name='frmChild' method='get'>";
					$frmChild .= JText::_('COM_REDSHOP_CHILD_PRODUCTS') . $lists ['product_child_id'];
					$frmChild .= "<input type='hidden' name='Itemid' value='" . $this->itemId . "'>";
					$frmChild .= "<input type='hidden' name='cid' value='" . $wishlist->category_id . "'>";
					$frmChild .= "<input type='hidden' name='view' value='product'>";
					$frmChild .= "<input type='hidden' name='option' value='com_redshop'>";
					$frmChild .= "</form>";
				}
			}

			$templateProduct = str_replace("{child_products}", $frmChild, $templateProduct);
		}

		$childProduct = RedshopHelperProduct::getChildProduct($wishlist->product_id);

		if (count($childProduct) > 0)
		{
			if (Redshop::getConfig()->get('PURCHASE_PARENT_WITH_CHILD') == 1)
			{
				$isChilds       = false;
				$attributesSet = array();

				if ($wishlist->attribute_set_id > 0)
				{
					$attributesSet = \Redshop\Product\Attribute::getProductAttribute(0, $wishlist->attribute_set_id, 0, 1);
				}

				$attributes = \Redshop\Product\Attribute::getProductAttribute($wishlist->product_id);

				$attributes = array_merge($attributes, /** @scrutinizer ignore-type */ $templateProduct);
			}
			else
			{
				$isChilds   = true;
				$attributes = array();
			}
		}
		else
		{
			$isChilds       = false;
			$attributesSet = array();

			if ($wishlist->attribute_set_id > 0)
			{
				$attributesSet = \Redshop\Product\Attribute::getProductAttribute(0, $wishlist->attribute_set_id, 0, 1);
			}

			$attributes = \Redshop\Product\Attribute::getProductAttribute($wishlist->product_id);
			$attributes = array_merge($attributes, $attributesSet);
		}

		$attributeTemplate = \Redshop\Template\Helper::getAttribute($templateProduct);

		$wishlistData = $wishlist->wishlistData;

		if ($wishlistData)
		{
			// Get necessary data for attributes, properties and sub-attributes.
			foreach ($attributes as $key => $attribute)
			{
				if (empty($attribute->properties))
				{
					continue;
				}

				if (!isset($wishlistData->product_items[$attribute->attribute_id]))
				{
					if ($isIndividualAddToCart)
					{
						unset($attributes[$key]);
					}

					continue;
				}

				$wishlistProductItem = $wishlistData->product_items[$attribute->attribute_id];

				foreach ($attribute->properties as $property)
				{
					$property->setdefault_selected = 0;

					if ($property->property_id != $wishlistProductItem->property_id)
					{
						continue;
					}

					$property->setdefault_selected = 1;

					if (empty($wishlistProductItem->subattribute_id))
					{
						continue;
					}

					if (empty($property->sub_properties))
					{
						$property->sub_properties = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $property->value);
					}

					foreach ($property->sub_properties as $subProperty)
					{
						$subProperty->setdefault_selected = 0;

						if ($subProperty->subattribute_color_id == $wishlistProductItem->subattribute_id
							&& $subProperty->subattribute_id == $wishlistProductItem->attribute_id)
						{
							$subProperty->setdefault_selected = 1;
						}
					}
				}
			}

			$attributes = array_values($attributes);
		}

		$templateProduct = RedshopHelperProduct::getProductNotForSaleComment($wishlist, $templateProduct, $attributes);
		$templateProduct = Redshop\Product\Stock::replaceInStock($wishlist->product_id, $templateProduct, $attributes, $attributeTemplate);

		// Product attribute  Start
		$totalatt      = count($attributes);
		$templateProduct = RedshopHelperAttribute::replaceAttributeData(
			$wishlist->product_id, 0, 0, $attributes, $templateProduct, $attributeTemplate, $isChilds, array(), 1, true
		);

		// Product accessory Start
		$accessory      = RedshopHelperAccessory::getProductAccessories(0, $wishlist->product_id);
		$totalAccessory = count($accessory);

		$templateProduct = RedshopHelperProductAccessory::replaceAccessoryData($wishlist->product_id, 0, $accessory, $templateProduct, $isChilds);

		// Product User Field Start
		$countNoUserField = 0;
		$returnArr           = \Redshop\Product\Product::getProductUserfieldFromTemplate($templateProduct);
		$templateUserfield  = $returnArr[0];

		$userfieldArr = $returnArr[1];


		if (strstr($templateProduct, "{if product_userfield}") && strstr($templateProduct, "{product_userfield end if}") && $templateUserfield != "")
		{
			$ufield = "";
			$cart   = $session->get('cart');

			if (isset($cart['idx']))
			{
				$idx = (int) ($cart['idx']);
			}

			$idx     = 0;
			$cartId = '';

			for ($j = 0; $j < $idx; $j++)
			{
				if ($cart[$j]['product_id'] == $wishlist->product_id)
				{
					$cartId = $j;
				}
			}

			for ($ui = 0; $ui < count($userfieldArr); $ui++)
			{
				if (!$idx)
				{
					$cartId = "";
				}

				$productUserFieldsFinal = $wishlistuserfielddata[$ui]->userfielddata;

				if ($productUserFieldsFinal != '')
				{
					$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($userfieldArr[$ui], 12, '', '', 0, $wishlist->product_id, $productUserFieldsFinal, 1);
				}
				else
				{
					$productUserFields = Redshop\Fields\SiteHelper::listAllUserFields($userfieldArr[$ui], 12, '', $cartId, 0, $wishlist->product_id);
				}

				$ufield .= $productUserFields[1];

				if ($productUserFields[1] != "")
				{
					$countNoUserField++;
				}

				$templateProduct = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $templateProduct);
				$templateProduct = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $templateProduct);
			}

			$productUserFieldsForm = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

			if ($ufield != "")
			{
				$templateProduct = str_replace("{if product_userfield}", $productUserFieldsForm, $templateProduct);
				$templateProduct = str_replace("{product_userfield end if}", "</form>", $templateProduct);
			}
			else
			{
				$templateProduct = str_replace("{if product_userfield}", "", $templateProduct);
				$templateProduct = str_replace("{product_userfield end if}", "", $templateProduct);
			}
		}

		if ($this->isTagExists('{read_more}'))
		{
			$rMore = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link'      => $link,
					'attr'      => 'title="' . $wishlist->product_name . '"',
					'content'   => JText::_('COM_REDSHOP_READ_MORE')
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{read_more}"] = $rMore;
			$templateProduct = $this->strReplace($this->replacements, $templateProduct);
		}

		if ($this->isTagExists('{read_more_link}'))
		{
			$rMoreLink = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link'      => $link,
					'content'   => JText::_('COM_REDSHOP_READ_MORE')
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{read_more_link}"] = $rMoreLink;
			$templateProduct = $this->strReplace($this->replacements, $templateProduct);
		}

		if ($this->isTagExists('{remove_product_link}'))
		{
			$removeProductLink = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'link'      => $linkRemove,
					'content'   => JText::_('COM_REDSHOP_REMOVE_PRODUCT_FROM_WISHLIST'),
					'attr'      => 'style="text-decoration:none"'
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->replacements["{remove_product_link}"] = $removeProductLink;
			$templateProduct = $this->strReplace($this->replacements, $templateProduct);
		}

		// Extra field display
		$templateProduct = RedshopHelperProductTag::getExtraSectionTag($this->extraFieldName, $wishlist->product_id, "1", $templateProduct, 1);

		$templateProduct = str_replace("{if product_on_sale}", "", $templateProduct);
		$templateProduct = str_replace("{product_on_sale end if}", "", $templateProduct);

		if (isset($wishlist->category_id) === false)
		{
			$wishlist->category_id = 0;
		}

		if ($isIndividualAddToCart)
		{
			$templateProduct = Redshop\Cart\Render::replace(
				$wishlist->product_id, $wishlist->category_id, 0, 0, $templateProduct, $isChilds,
				$userfieldArr, $totalatt, $totalAccessory, $countNoUserField, $wishlist->wishlistData->wishlist_product_id
			);
		}
		else
		{
			$templateProduct = Redshop\Cart\Render::replace(
				$wishlist->product_id, $wishlist->category_id, 0, 0, $templateProduct, $isChilds,
				$userfieldArr, $totalatt, $totalAccessory, $countNoUserField
			);
		}

		$this->mainId .= $wishlist->product_id . ",";
		$this->totalId .= $totalatt . ",";
		$this->totalCountNoUserField .= $countNoUserField . ",";

		return $templateProduct;
	}
}
