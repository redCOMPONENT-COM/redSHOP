<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  2.1.5
 */
class RedshopTagsSectionsCompareProduct extends RedshopTagsAbstract
{
	public $tags = array(
		'{print}',
		'{compare_product_heading}',
		'{returntocategory_name}',
		'{returntocategory_link}',
		'{remove_all}',
		'{expand_collapse}',
		'{product_name}',
		'{product_image}',
		'{manufacturer_name}',
		'{discount_start_date}',
		'{discount_end_date}',
		'{product_price}',
		'{product_s_desc}',
		'{product_desc}',
		'{product_rating_summary}',
		'{product_delivery_time}',
		'{product_number}',
		'{products_in_stock}',
		'{product_stock_amount_image}',
		'{product_weight}',
		'{product_length}',
		'{product_height}',
		'{product_width}',
		'{product_availability_date}',
		'{product_volume}',
		'{product_category}',
		'{remove}',
		'{add_to_cart}',
		'{product_field}'
	);

	/**
	 * Init function
	 * @return mixed|void
	 *
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function init()
	{

	}

	/**
	 * Executing replace
	 * @return string
	 *
	 * @throws Exception
	 * @since 2.1.5
	 */
	public function replace()
	{
		$compare = $this->data['compare'];
		$compareCategoryId = $this->data['compareCategoryId'];
		$itemId = $this->data['itemId'];
		$print = $this->data['print'];
		$template = $this->template;
		$redTemplate = $this->data['redTemplate'];
		$pagetitle = JText::_('COM_REDSHOP_COMPARE_PRODUCTS');
		$this->addReplace('{compare_product_heading}', $pagetitle);
		$list  = $compare->getItems();
		$total = $compare->getItemsTotal();

		if ($total > 0)
		{
			if ($total == 1)
			{
				JLog::add(JText::_('COM_REDSHOP_ADD_ONE_MORE_PRODUCT_TO_COMPARE'), JLog::NOTICE);
			}

			$returnLink = JRoute::_("index.php?option=com_redshop&view=category&cid=" . $compareCategoryId . "&Itemid=" . $itemId);

			if ($print)
			{
				$printTag = RedshopLayoutHelper::render(
					'tags.common.print',
					array('onClick' => 'onclick=window.print();'),
					'',
					RedshopLayoutHelper::$layoutOption
				);
			}
			else
			{
				$printUrl = JURI::base() . "index.php?option=com_redshop&view=product&layout=compare&print=1&tmpl=component";

				$printTag = RedshopLayoutHelper::render(
					'tags.common.print',
					array('onClick' => 'onclick=window.open("' . $printUrl . '","mywindow","scrollbars=1","location=1")'),
					'',
					RedshopLayoutHelper::$layoutOption
				);

			}

			$this->addReplace('{print}', $printTag);
			$this->addReplace('{returntocategory_name}', JText::_("COM_REDSHOP_GO_BACK"));
			$this->addReplace('{returntocategory_link}', $returnLink);

			$removeAll = RedshopLayoutHelper::render(
				'tags.common.link',
				array(
					'class' => 'remove',
					'link' => JUri::root() . 'index.php?option=com_redshop&view=product&task=removecompare&tmpl=component&Itemid=' . $itemId,
					'content' => JText::_('COM_REDSHOP_REMOVE_ALL_PRODUCT_FROM_COMPARE_LIST')
				),
				'',
				RedshopLayoutHelper::$layoutOption
			);

			$this->addReplace('{remove_all}', $removeAll);
			$productTag = array();

			if (!empty($compareTemplate))
			{
				$productTag = Redshop\Helper\Utility::getProductTags(1, $template);
			}

			$index = 0;

			$this->replacements = [
				'{expand_collapse}' => '',
				'{product_name}' => '',
				'{product_image}' => '',
				'{manufacturer_name}' => '',
				'{discount_start_date}' => '',
				'{discount_end_date}' => '',
				'{product_s_desc}' => '',
				'{product_desc}' => '',
				'{product_number}' => '',
				'{product_weight}' => '',
				'{product_length}' => '',
				'{product_height}' => '',
				'{product_width}' => '',
				'{product_volume}' => '',
				'{product_price}' => '',
				'{product_rating_summary}' => '',
				'{products_in_stock}' => '',
				'{product_stock_amount_image}' => '',
				'{product_delivery_time}' => '',
				'{product_availability_date}' => '',
				'{product_category}' => '',
				'{remove}' => '',
				'{add_to_cart}' => ''
			];

			foreach ($list as $data)
			{
				$product = \Redshop\Product\Product::getProductById($data['item']->productId);
				$tdStart = '';
				$tdEnd   = '';

				if ($index != ($total - 1))
				{
					$tdStart = "<td>";
					$tdEnd   = "</td>";
				}

				$expDiv = "<div name='exp_" . $product->product_id . "'>";
				$divEnd = "</div>";
				$ItemData = RedshopHelperProduct::getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);

				if (!empty($ItemData))
				{
					$pItemid = $ItemData->id;
				}
				else
				{
					$catIdMain = $product->cat_in_sefurl;
					$pItemid   = RedshopHelperRouter::getItemId($product->product_id, $catIdMain);
				}

				$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&Itemid=' . $pItemid);

				$thumbUrl = RedshopHelperMedia::getImagePath(
					$product->product_full_image,
					'',
					'thumb',
					'product',
					Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_WIDTH'),
					Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_HEIGHT'),
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				);

				$linkImg = RedshopLayoutHelper::render(
					'tags.common.link_img',
					array(
						'link' => $link,
						'linkAttr' => 'title="' . $product->product_name . '"',
						'src' => $thumbUrl
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$img = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'div',
						'attr' => 'style="width:' . Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_WIDTH') . 'px; height:' . Redshop::getConfig()->get('COMPARE_PRODUCT_THUMB_HEIGHT') . 'px; float: left;"',
						'text' => $linkImg
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$expand = RedshopLayoutHelper::render(
					'tags.common.link',
					array(
						'link' => 'javascript:void(0)',
						'attr' => 'onClick="expand_collapse(this,' . $product->product_id . ')" style="font-size:18px;text-decoration:none;"',
						'content' => '-'
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				if ($index != ($total - 1))
				{
					$this->replacements['{expand_collapse}'] .= $expand . $tdEnd . '<td align="center">';
				}
				else
				{
					$this->replacements['{expand_collapse}'] .= $expand . $tdEnd . $tdStart;
				}

				$this->replacements['{product_name}'] .= $expDiv . $product->product_name . $divEnd . $tdEnd . $tdStart;
				$this->replacements['{product_image}'] .= $expDiv . $img . $divEnd . $tdEnd . $tdStart;

				if (strstr($template, "{manufacturer_name}"))
				{
					$manufacturer     = RedshopEntityManufacturer::getInstance($product->manufacturer_id);
					$manufacturerName = $manufacturer->get('name');
					$this->replacements['{manufacturer_name}'] .= $expDiv . $manufacturerName . $divEnd . $tdEnd . $tdStart;
				}

				if (strstr($template, "{discount_start_date}"))
				{
					$discStartDate = '';

					if ($product->discount_stratdate)
					{
						$discStartDate = RedshopHelperDatetime::convertDateFormat($product->discount_stratdate);
					}

					$this->replacements['{discount_start_date}'] .= $expDiv . $discStartDate . $divEnd . $tdEnd . $tdStart;
				}

				if (strstr($template, "{discount_end_date}"))
				{
					$discEndDate = "";

					if ($product->discount_enddate)
					{
						$discEndDate = RedshopHelperDatetime::convertDateFormat($product->discount_enddate);
					}

					$this->replacements['{discount_end_date}'] .= $expDiv . $discEndDate . $divEnd . $tdEnd . $tdStart;
				}

				$this->replacements['{product_s_desc}'] .= $expDiv . $product->product_s_desc . $divEnd . $tdEnd . $tdStart;
				$this->replacements['{product_desc}'] .= $expDiv . $product->product_desc . $divEnd . $tdEnd . $tdStart;
				$this->replacements['{product_number}'] .= $expDiv . $product->product_number . $divEnd . $tdEnd . $tdStart;

				$productWeightUnit = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'span',
						'class' => 'product_unit_variable',
						'text' => Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT')
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements['{product_weight}'] .= $expDiv . RedshopHelperProduct::redunitDecimal($product->weight) . "&nbsp;" . $productWeightUnit . $divEnd . $tdEnd . $tdStart;
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

				$this->replacements['{product_length}'] .= $expDiv . RedshopHelperProduct::redunitDecimal($product->product_length) . "&nbsp;" . $productUnit . $divEnd . $tdEnd . $tdStart;
				$this->replacements['{product_height}'] .= $expDiv . RedshopHelperProduct::redunitDecimal($product->product_height) . "&nbsp;" . $productUnit . $divEnd . $tdEnd . $tdStart;
				$this->replacements['{product_width}'] .= $expDiv . RedshopHelperProduct::redunitDecimal($product->product_width) . "&nbsp;" . $productUnit . $divEnd . $tdEnd . $tdStart;

				$productVolumeUnit = RedshopLayoutHelper::render(
					'tags.common.tag',
					array(
						'tag' => 'span',
						'class' => 'product_unit_variable',
						'text' => Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . "3"
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements['{product_volume}'] .= $expDiv . RedshopHelperProduct::redunitDecimal($product->product_volume) . "&nbsp;" . $productVolumeUnit . $divEnd . $tdEnd . $tdStart;

				if ($this->isTagExists('{product_price}'))
				{
					$price = 0;

					if (Redshop::getConfig()->get('SHOW_PRICE')
						&& !Redshop::getConfig()->get('USE_AS_CATALOG')
						&& (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
							|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
								&& Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
					)
					{
						$productPrices = RedshopHelperProductPrice::getNetPrice($product->product_id);
						$price         = RedshopHelperProductPrice::formattedPrice($productPrices['product_price']);
					}

					$this->replacements['{product_price}'] .= $expDiv . $price . $divEnd . $tdEnd . $tdStart;
				}

				if ($this->isTagExists('{product_rating_summary}'))
				{
					$finalAvgReviewData = Redshop\Product\Rating::getRating($data['item']->productId);
					$this->replacements['{product_rating_summary}'] .= $expDiv . $finalAvgReviewData . $divEnd . $tdEnd . $tdStart;
				}

				if ($this->isTagExists('{products_in_stock}') || $this->isTagExists('{product_stock_amount_image}'))
				{
					$productStock = RedshopHelperStockroom::getStockAmountWithReserve($data['item']->productId);
					$this->replacements['{products_in_stock}'] .= $expDiv . $productStock . $divEnd . $tdEnd . $tdStart;
					$stockamountList  = RedshopHelperStockroom::getStockAmountImage($data['item']->productId, "product", $productStock);
					$stockamountImageLink = "";

					if (!empty($stockamountList))
					{
						$stockamountImageDiv1 = RedshopLayoutHelper::render(
							'tags.common.tag',
							array(
								'tag' => 'div',
								'class' => 'spnheader',
								'text' => JText::_('COM_REDSHOP_STOCK_AMOUNT')
							),
							'',
							RedshopLayoutHelper::$layoutOption
						);

						$stockamountImageDiv2 = RedshopLayoutHelper::render(
							'tags.common.tag',
							array(
								'tag' => 'div',
								'class' => 'spnalttext',
								'id' => 'stockImageTooltip' . $data['item']->productId,
								'text' => $stockamountList[0]->stock_amount_image_tooltip
							),
							'',
							RedshopLayoutHelper::$layoutOption
						);

						$stockamountImageSpan = RedshopLayoutHelper::render(
							'tags.common.tag',
							array(
								'tag' => 'span',
								'text' => $stockamountImageDiv1.$stockamountImageDiv2
							),
							'',
							RedshopLayoutHelper::$layoutOption
						);

						$stockamountImage = RedshopLayoutHelper::render(
							'tags.common.img',
							array(
								'src' => REDSHOP_FRONT_IMAGES_ABSPATH . 'stockroom/' . $stockamountList[0]->stock_amount_image,
								'alt' => $stockamountList[0]->stock_amount_image_tooltip,
								'attr' => 'width="150px" height="90px" id="stockImage' . $data['item']->productId . '"'
							),
							'',
							RedshopLayoutHelper::$layoutOption
						);

						$stockamountImageLink = RedshopLayoutHelper::render(
							'tags.common.link',
							array(
								'class' => 'imgtooltip',
								'link' => 'javascript:void(0)',
								'content' => $stockamountImageSpan . $stockamountImage
							),
							'',
							RedshopLayoutHelper::$layoutOption
						);
					}

					$this->replacements['{product_stock_amount_image}'] .= $expDiv . $stockamountImageLink . $divEnd . $tdEnd . $tdStart;
				}

				if ($this->isTagExists('{product_delivery_time}'))
				{
					$productDeliveryTime = RedshopHelperProduct::getProductMinDeliveryTime($data['item']->productId);
					$this->replacements['{product_delivery_time}'] .= $expDiv . $productDeliveryTime . $divEnd . $tdEnd . $tdStart;
				}

				if ($this->isTagExists('{product_availability_date}'))
				{
					$availableDate = "";

					if ($product->product_availability_date != "")
					{
						$availableDate = RedshopHelperDatetime::convertDateFormat($product->product_availability_date);
					}

					$this->replacements['{product_availability_date}'] .= $expDiv . $availableDate . $divEnd . $tdEnd . $tdStart;
				}

				if ($this->isTagExists('{product_category}'))
				{
					$categoriesId = explode(',', $data['item']->categoriesId);

					if (count($categoriesId) <= 1)
					{
						$category     = RedshopEntityCategory::getInstance($data['item']->categoryId);
						$categoryName = $category->get('name');
					}
					else
					{
						$categoriesName = array();

						foreach ($categoriesId as $categoryId)
						{
							$category = RedshopEntityCategory::getInstance((int) $categoryId);

							if (!in_array($category->get('name'), $categoriesName))
							{
								$categoriesName[] = $category->get('name');
							}
						}

						$categoryName = implode(' ,', $categoriesName);
					}

					$this->replacements['{product_category}'] .= $expDiv . $categoryName . $divEnd . $tdEnd . $tdStart;
				}

				$linkRemove = JUri::root() . 'index.php?option=com_redshop&view=product&task=removecompare&layout=compare&pid=' . $product->product_id . '&cid=' . $categoriesId[0] . '&Itemid=' . $itemId . '&tmpl=component';

				$remove = RedshopLayoutHelper::render(
					'tags.common.link',
					array(
						'class' => '',
						'link' => $linkRemove,
						'content' =>  JText::_('COM_REDSHOP_REMOVE_PRODUCT_FROM_COMPARE_LIST')
					),
					'',
					RedshopLayoutHelper::$layoutOption
				);

				$this->replacements['{remove}'] .= $expDiv . $remove . $divEnd . $tdEnd . $tdStart;

				if ($this->isTagExists('{add_to_cart}'))
				{
					$addToCart = Redshop\Cart\Render::replace($data['item']->productId, 0, 0, 0, '{form_addtocart:add_to_cart1}');
					$this->replacements['{add_to_cart}'] .= $expDiv . $addToCart . $divEnd . $tdEnd . $tdStart;
				}

				// Extra field display
				foreach ($productTag as $aProductTag)
				{

					$str = "'" . $aProductTag . "'";

					if ($index != ($total - 1))
					{
						$template = str_replace(
							'{' . $aProductTag . '}',
							$expDiv . '{' . $aProductTag . '}' . $divEnd . $tdEnd . $tdStart . '{addedext_tag}',
							$template
						);
					}

					$template = Redshop\Helper\ExtraFields::displayExtraFields("1", $product->product_id, $str, $template);
					$template = str_replace('{addedext_tag}', '{' . $aProductTag . '}', $template);
				}

				$index++;
			}

			$template = $this->strReplace($this->replacements, $template);

			$template = Redshop\Template\General::replaceBlank(array(
				'{expand_collapse}',
				'{product_name}',
				'{product_image}',
				'{manufacturer_name}',
				'{discount_start_date}',
				'{discount_end_date}',
				'{product_price}',
				'{product_s_desc}',
				'{product_desc}',
				'{product_rating_summary}',
				'{product_delivery_time}',
				'{product_number}',
				'{products_in_stock}',
				'{product_stock_amount_image}',
				'{product_weight}',
				'{product_length}',
				'{product_height}',
				'{product_width}',
				'{product_availability_date}',
				'{product_volume}',
				'{product_category}',
				'{remove}',
				'{add_to_cart}'
			), $template);

			$template = $redTemplate->parseredSHOPplugin($template);
		}
		else
		{
			$template = JText::_('COM_REDSHOP_NO_PRODUCTS_TO_COMPARE');
		}

		$this->template = $template;
		return parent::replace();
	}
}