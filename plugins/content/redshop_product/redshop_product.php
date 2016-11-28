<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

JLoader::import('redshop.library');

/**
 * PlgContentRedshop_Product
 *
 * @since  1.5
 */
class PlgContentRedshop_Product extends JPlugin
{
	/**
	 * onContentPrepare event
	 * 
	 * @param   string   $context  context of event
	 * @param   array    &$row     result list
	 * @param   object   &$params  plugin params
	 * @param   integer  $page     paging
	 * 
	 * @return  bool
	 */
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		if (preg_match_all("/{redshop:.+?}/", $row->text, $matches, PREG_PATTERN_ORDER) > 0)
		{
			JHTML::_('behavior.tooltip');
			JHTML::_('behavior.modal');

			$session = JFactory::getSession();
			$post    = JRequest::get('POST');

			if (isset($post['product_currency']))
			{
				$session->set('product_currency', $post['product_currency']);
			}

			JHtml::_('redshopjquery.framework');
			JHtml::script('com_redshop/redbox.js', false, true);
			JHtml::script('com_redshop/attribute.js', false, true);
			JHtml::script('com_redshop/common.js', false, true);
			JHtml::stylesheet('com_redshop/redshop.css', array(), true);
			JHtml::stylesheet('com_redshop/style.css', array(), true);
			JHtml::stylesheet('com_redshop/scrollable-navig.css', array(), true);

			$pluginId     = "plg_";
			$productHelper = productHelper::getInstance();
			$extraField    = extraField::getInstance();
			$redHelper     = redhelper::getInstance();
			$lang          = JFactory::getLanguage();

			// Or JPATH_ADMINISTRATOR if the template language file is only
			$lang->load('plg_content_redshop_product', JPATH_ADMINISTRATOR);
			$lang->load('com_redshop', JPATH_SITE);

			$plugin = JPluginHelper::getPlugin('content', 'redshop_product');
			$redRarams = new JRegistry($plugin->params);

			// Get show price yes/no option
			$showPrice = trim($redRarams->get('show_price', 0));
			$showPriceWithVat = trim($redRarams->get('show_price_with_vat', 1));
			$showDiscountPriceLayout = trim($redRarams->get('show_discountpricelayout', 1));
			$redTemplate = Redtemplate::getInstance();
			$prodTemplateId = trim($redRarams->get('product_template', 1));
			$prodTemplates = $redTemplate->getTemplate('product_content_template', $prodTemplateId);
			$prodTemplate = $prodTemplates[0]->template_desc;

			if ($prodTemplate == "")
			{
				$prodTemplate = RedshopLayoutHelper::render(
					'product_default',
					array(
					),
					JPATH_SITE . '/plugins/' . $this->_type . '/' . $this->_name . '/layouts'
				);
			}

			$matches = $matches[0];

			for ($i = 0, $countMatches = count($matches); $i < $countMatches; $i++)
			{
				$prtemplate = $prodTemplate;
				$match = explode(":", $matches[$i]);
				$productId = (int) (trim($match[1], '}'));
				$product = $productHelper->getProductById($productId);
				$url = JURI::root();

				if (!$product->product_id)
				{
					$row->text = str_replace($matches[$i], '', $row->text);
					continue;
				}

				// Changes for sh404sef duplicating url
				$catId = $productHelper->getCategoryProduct($product->product_id);
				$itemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);

				if (count($itemData) > 0)
				{
					$pItemid = $itemData->id;
				}
				else
				{
					$pItemid = $redHelper->getItemid($product->product_id, $catId);
				}

				$defaultLink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&cid=' . $catId . '&Itemid=' . $pItemid);
				$link = ($page == 1) ? $url . $defaultLink : JRoute::_($defaultLink);

				// End changes for sh404sef duplicating url

				if (strstr($prtemplate, "{product_thumb_image_3}"))
				{
					$prodImgTag     = '{product_thumb_image_3}';
					$prodImgHeight  = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT_3');
					$prodImageWidth = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_3');
				}
				elseif (strstr($prtemplate, "{product_thumb_image_2}"))
				{
					$prodImgTag     = '{product_thumb_image_2}';
					$prodImgHeight  = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT_2');
					$prodImageWidth = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_2');
				}
				elseif (strstr($prtemplate, "{product_thumb_image_1}"))
				{
					$prodImgTag     = '{product_thumb_image_1}';
					$prodImgHeight  = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT');
					$prodImageWidth = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE');
				}
				else
				{
					$prodImgTag     = '{product_thumb_image}';
					$prodImgHeight  = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT');
					$prodImageWidth = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE');
				}

				$hiddenThumbImage = RedshopLayoutHelper::render(
					'hidden_thumb_image',
					array(
						'prodImageWidth' => $prodImageWidth,
						'prodImgHeight'  => $prodImgHeight
					),
					JPATH_SITE . '/plugins/' . $this->_type . '/' . $this->_name . '/layouts'
				);

				$thumbImage = $productHelper->getProductImage($productId, $link, $prodImageWidth, $prodImgHeight, 2, 1);
				$prtemplate = str_replace($prodImgTag, $thumbImage . $hiddenThumbImage, $prtemplate);
				$productName = "<a href='" . $link . "' title=''>" . $product->product_name . "</a>";
				$prtemplate = str_replace("{product_name}", $productName, $prtemplate);

				if (strstr($prtemplate, "{product_desc}"))
				{
					$prtemplate = str_replace("{product_desc}", $product->product_s_desc, $prtemplate);
				}

				if (strstr($prtemplate, "{product_price}"))
				{
					$prodPriceHtml = '';

					if ($showPrice && Redshop::getConfig()->get('SHOW_PRICE'))
					{
						$productPrice = $productHelper->getProductPrice($product->product_id, $showPriceWithVat);
						$productArr = $productHelper->getProductNetPrice($product->product_id, 0, 1);
						$productPriceDiscount = $productArr['productPrice'];
						$productPriceDiscountVat = $productArr['productVat'];

						if ($showPriceWithVat)
						{
							$productPriceDiscount += $productPriceDiscountVat;
						}

						if ($product->product_on_sale && $productPriceDiscount > 0)
						{
							if ($product_price > $productPriceDiscount)
							{
								$realPrice = $productPrice - $productPriceDiscount;

								if ($showDiscountPriceLayout)
								{
									$prodPriceHtml = RedshopLayoutHelper::render(
											'product_price',
											array(
												'realPrice' => $realPrice,
											),
											JPATH_SITE . '/plugins/' . $this->_type . '/' . $this->_name . '/layouts'
										);
								}
								else
								{
									$productPrice = $productPriceDiscount;
									$prodPriceHtml = $productHelper->getProductFormattedPrice($productPrice);
								}
							}
							else
							{
								$prodPriceHtml = $productHelper->getProductFormattedPrice($productPrice);
							}
						}
						else
						{
							$prodPriceHtml = $productHelper->getProductFormattedPrice($product_price);
						}
					}

					$prtemplate = str_replace("{product_price}", $prodPriceHtml, $prtemplate);
				}

				if (strstr($prtemplate, "{read_more}"))
				{
					$readMore = RedshopLayoutHelper::render(
								'readmore',
								array(
									'link' => $link,
								),
								JPATH_SITE . '/plugins/' . $this->_type . '/' . $this->_name . '/layouts'
							);

					$prtemplate = str_replace("{read_more}", $readMore, $prtemplate);
				}

				/*
				 * Product attribute  Start
				 */
				$attributesSet = array();

				if ($product->attribute_set_id > 0)
				{
					$attributesSet = $productHelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
				}

				$attributes = $productHelper->getProductAttribute($product->product_id);
				$attributes = array_merge($attributes, $attributesSet);
				$totalAtt = count($attributes);

				/*
				 * Product accessory Start
				 */
				$accessory = $productHelper->getProductAccessory(0, $product->product_id);
				$totalAccessory = count($accessory);

				// Product User Field Start
				$countUserField = 0;
				$returnArr = $productHelper->getProductUserfieldFromTemplate($prtemplate);
				$templateUserField = $returnArr[0];
				$userfieldArr = $returnArr[1];

				if (strstr($prtemplate, "{if product_userfield}") && strstr($prtemplate, "{product_userfield end if}") && $templateUserField != "")
				{
					$ufield = "";
					$cart = $session->get('cart');
					$idx = 0;

					if (isset($cart['idx']))
					{
						$idx = (int) ($cart['idx']);
					}

					$cartId = '';

					for ($j = 0; $j < $idx; $j++)
					{
						if ($cart[$j]['product_id'] == $this->data->product_id)
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

						$productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', $cartId, 0, $this->data->product_id);

						$ufield .= $productUserFields[1];

						if ($productUserFields[1] != "")
						{
							$countUserField++;
						}

						$prtemplate = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $prtemplate);
						$prtemplate = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $prtemplate);
					}

					$productUserFieldsForm = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

					if ($ufield != "")
					{
						$prtemplate = str_replace("{if product_userfield}", $productUserFieldsForm, $prtemplate);
						$prtemplate = str_replace("{product_userfield end if}", "</form>", $prtemplate);
					}
					else
					{
						$prtemplate = str_replace("{if product_userfield}", "", $prtemplate);
						$prtemplate = str_replace("{product_userfield end if}", "", $prtemplate);
					}
				}

				// Product User Field End

				$childProduct = $productHelper->getChildProduct($product->product_id);

				if (count($childProduct) > 0)
				{
					$isChilds = true;
					$attributes = array();
				}
				else
				{
					$isChilds = false;

					// Get attributes
					$attributesSet = array();

					if ($product->attribute_set_id > 0)
					{
						$attributesSet = $productHelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
					}

					$attributes = $productHelper->getProductAttribute($product->product_id);
					$attributes = array_merge($attributes, $attributesSet);
				}

				$prtemplate = $productHelper->replaceCartTemplate(
					$product->product_id,
					0,
					0,
					0,
					$prtemplate,
					false,
					$userfieldArr,
					$totalAtt,
					$totalAccessory,
					$countUserField,
					$pluginId
				);

				$attributeTemplate = $productHelper->getAttributeTemplate($prtemplate);
				$prtemplate = $productHelper->replaceAttributeData($product->product_id, 0, 0, $attributes, $prtemplate, $attributeTemplate, $isChilds);

				$row->text = str_replace($matches[$i], $prtemplate, $row->text);
			}
		}

		return true;
	}
}
