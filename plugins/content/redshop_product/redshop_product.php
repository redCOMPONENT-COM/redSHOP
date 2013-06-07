<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Replaces textstring with link
 */
require_once JPATH_ROOT . '/components/com_redshop/helpers/redshop.js.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/product.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/extra_field.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/template.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';

class plgContentredshop_product extends JPlugin
{
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		if (preg_match_all("/{redshop:.+?}/", $row->text, $matches, PREG_PATTERN_ORDER) > 0)
		{
			JHTML::_('behavior.tooltip');
			JHTML::_('behavior.modal');

			$session = JFactory::getSession('product_currency');
			$post = JRequest::get('POST');

			if (isset($post['product_currency']))
			{
				$session->set('product_currency', $post['product_currency']);
			}

					$currency_symbol = REDCURRENCY_SYMBOL;
			$currency_convert = 1;

			if ($session->get('product_currency'))
			{
				$currency_symbol = $session->get('product_currency');
				$CurrencyHelper = new CurrencyHelper;
				$currency_convert = $CurrencyHelper->convert(1);
			}

					$document = JFactory::getDocument();
			JHTML::Script('jquery.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
			JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
			JHTML::Stylesheet('redshop.css', 'components/com_redshop/assets/css/');
			JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');
			JHTML::Stylesheet('style.css', 'components/com_redshop/assets/css/');
			JHTML::Stylesheet('scrollable-navig.css', 'components/com_redshop/assets/css/');
			$document->addScriptDeclaration("
		                var site_url = '" . JURI::root() . "';
		                var AJAX_CART_BOX = " . AJAX_CART_BOX . ";
		                var REDCURRENCY_SYMBOL = '" . REDCURRENCY_SYMBOL . "';
		                var CURRENCY_SYMBOL_CONVERT = '" . $currency_symbol . "';
		                var CURRENCY_CONVERT = '" . $currency_convert . "';
		                var PRICE_SEPERATOR = '" . PRICE_SEPERATOR . "';
						var PRODUCT_OUTOFSTOCK_MESSAGE = '" . JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE') . "';
						var CURRENCY_SYMBOL_POSITION = '" . CURRENCY_SYMBOL_POSITION . "';
						var PRICE_DECIMAL = '" . PRICE_DECIMAL . "';
						var AJAX_CART_DISPLAY_TIME = '" . AJAX_CART_DISPLAY_TIME . "';
						var THOUSAND_SEPERATOR = '" . THOUSAND_SEPERATOR . "';
		                var VIEW_CART = '" . JText::_('COM_REDSHOP_VIEW_CART') . "';
		                var CONTINUE_SHOPPING = '" . JText::_('COM_REDSHOP_CONTINUE_SHOPPING') . "';
		                var CART_SAVE = '" . JText::_('COM_REDSHOP_CART_SAVE') . "';
		                var IS_REQUIRED = '" . JText::_('COM_REDSHOP_IS_REQUIRED') . "';
		                var ENTER_NUMBER = '" . JText::_('COM_REDSHOP_ENTER_NUMBER') . "';
		                var USE_STOCKROOM = '" . USE_STOCKROOM . "';
		                var USE_AS_CATALOG = '" . USE_AS_CATALOG . "';
		                var SHOW_PRICE = '" . SHOW_PRICE . "';
						var DEFAULT_QUOTATION_MODE = '" . DEFAULT_QUOTATION_MODE . "';
		                var PRICE_REPLACE = '" . PRICE_REPLACE . "';
						var PRICE_REPLACE_URL = '" . PRICE_REPLACE_URL . "';
						var ZERO_PRICE_REPLACE = '" . ZERO_PRICE_REPLACE . "';
						var ZERO_PRICE_REPLACE_URL = '" . ZERO_PRICE_REPLACE_URL . "';
				");
			$module_id = "plg_";
			$producthelper = new producthelper;
			$extraField = new extraField;
			$objhelper = new redhelper;
			$lang = JFactory::getLanguage();

			// Or JPATH_ADMINISTRATOR if the template language file is only
			$lang->load('plg_content_redshop_product', JPATH_ADMINISTRATOR);

			$plugin =& JPluginHelper::getPlugin('content', 'redshop_product');
			$red_params = new JRegistry($plugin->params);

			// Get show price yes/no option
			$show_price = trim($red_params->get('show_price', 0));
			$show_price_with_vat = trim($red_params->get('show_price_with_vat', 1));
			$show_discountpricelayout = trim($red_params->get('show_discountpricelayout', 1));
			$redTemplate = new Redtemplate;
			$prtemplate_id = trim($red_params->get('product_template', 1));
			$prtemplate1 = $redTemplate->getTemplate('product_content_template', $prtemplate_id);
			$prtemplate_default = $prtemplate1[0]->template_desc;

			if ($prtemplate_default == "")
			{
				$prtemplate_default = '<div class="mod_redshop_products"><table border="0"><tbody><tr><td><div class="mod_redshop_products_image">{product_thumb_image}</div></td></tr><tr><td><div class="mod_redshop_products_title">{product_name}</div></td></tr><tr><td><div class="mod_redshop_products_price">{product_price}</div></td></tr><tr><td><div class="mod_redshop_products_readmore">{read_more}</div></td></tr><tr><td><div>{attribute_template:attributes}</div></td></tr><tr><td><div class="mod_redshop_product_addtocart">{form_addtocart:add_to_cart1}</div></td></tr></tbody></table></div>';
			}

					$matches = $matches[0];

			for ($i = 0; $i < count($matches); $i++)
			{
				$prtemplate = '';
				$prtemplate = $prtemplate_default;
				$match = explode(":", $matches[$i]);
				$product_id = (int) (trim($match[1], '}'));
				$product = $producthelper->getProductById($product_id);
				$url = JURI::root();
				$product_image = $product->product_full_image;
				$product_img = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product_image;
				$title = " title='" . $product->product_name . "' ";
				$alt = " alt='" . $product->product_name . "' ";

				if (!$product->product_id)
				{
					$row->text = str_replace($matches[$i], '', $row->text);
					continue;
				}

				// Changes for sh404sef duplicating url
				$catid = $producthelper->getCategoryProduct($product->product_id);
				$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);

				if (count($ItemData) > 0)
				{
					$pItemid = $ItemData->id;
				}
				else
				{
					$pItemid = $objhelper->getItemid($product->product_id, $catid);
				}

					$defaultLink = 'index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&cid=' . $catid . '&Itemid=' . $pItemid;
				$link = ($page == 1) ? $url . $defaultLink : JRoute::_($defaultLink);

				// End changes for sh404sef duplicating url

				if (strstr($prtemplate, "{product_thumb_image_3}"))
				{
					$pimg_tag = '{product_thumb_image_3}';
					$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT_3;
					$pw_thumb = PRODUCT_MAIN_IMAGE_3;
				}
				elseif (strstr($data_add, "{product_thumb_image_2}"))
				{
					$pimg_tag = '{product_thumb_image_2}';
					$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT_2;
					$pw_thumb = PRODUCT_MAIN_IMAGE_2;
				}
				elseif (strstr($data_add, "{product_thumb_image_1}"))
				{
					$pimg_tag = '{product_thumb_image_1}';
					$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT;
					$pw_thumb = PRODUCT_MAIN_IMAGE;
				}
				else
				{
					$pimg_tag = '{product_thumb_image}';
					$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT;
					$pw_thumb = PRODUCT_MAIN_IMAGE;
				}

					$hidden_thumb_image = "<input type='hidden' name='prd_main_imgwidth' id='prd_main_imgwidth' value='"
						. $pw_thumb . "'><input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='"
						. $ph_thumb . "'>";
				$thum_image = $producthelper->getProductImage($product_id, $link, $pw_thumb, $ph_thumb, 2, 1);
				$prtemplate = str_replace($pimg_tag, $thum_image . $hidden_thumb_image, $prtemplate);
				$product_name = "<a href='" . $link . "' title=''>" . $product->product_name . "</a>";
				$prtemplate = str_replace("{product_name}", $product_name, $prtemplate);

				if (strstr($prtemplate, "{product_desc}"))
				{
					$prtemplate = str_replace("{product_desc}", $product->product_s_desc, $prtemplate);
				}

				if (strstr($prtemplate, "{product_price}"))
				{
					if ($show_price && SHOW_PRICE)
					{
						$product_price = $producthelper->getProductPrice($product->product_id, $show_price_with_vat);

						$productArr = $producthelper->getProductNetPrice($rows[$k]->product_id, 0, 1);
						$product_price_discount = $productArr['productPrice'];
						$product_price_discountVat = $productArr['productVat'];

						if ($show_price_with_vat)
						{
							$product_price_discount += $product_price_discountVat;
						}

						if ($product->product_on_sale && $product_price_discount > 0)
						{
							if ($product_price > $product_price_discount)
							{
								$s_price = $product_price - $product_price_discount;

								if ($show_discountpricelayout)
								{
									$pr_price = "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>"
										. $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
									$product_price = $product_price_discount;
									$pr_price = "<div id='mod_redmainprice' class='mod_redmainprice'>"
										. $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
									$pr_price = "<div id='mod_redsavedprice' class='mod_redsavedprice'>"
										. JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' '
										. $producthelper->getProductFormattedPrice($s_price) . "</div>";
								}
								else
								{
									$product_price = $product_price_discount;
									$pr_price = $producthelper->getProductFormattedPrice($product_price);
								}
							}
							else
							{
								$pr_price = $producthelper->getProductFormattedPrice($product_price);
							}
						}
						else
						{
							$pr_price = $producthelper->getProductFormattedPrice($product_price);
						}
					}

					$prtemplate = str_replace("{product_price}", $pr_price, $prtemplate);
				}

				if (strstr($prtemplate, "{read_more}"))
				{
					$read_more = "<a href='" . $link . "'>" . JText::_('COM_REDSHOP_TXT_READ_MORE') . "</a>";
					$prtemplate = str_replace("{read_more}", $read_more, $prtemplate);
				}

				/*
				 * Product attribute  Start
				 */
				$attributes_set = array();

				if ($product->attribute_set_id > 0)
				{
					$attributes_set = $producthelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
				}

				$attributes = $producthelper->getProductAttribute($product->product_id);
				$attributes = array_merge($attributes, $attributes_set);
				$totalatt = count($attributes);

				/*
				 * Product accessory Start
				 */
				$accessory = $producthelper->getProductAccessory(0, $product->product_id);
				$totalAccessory = count($accessory);

				// Product User Field Start
				$count_no_user_field = 0;
				$returnArr = $producthelper->getProductUserfieldFromTemplate($prtemplate);
				$template_userfield = $returnArr[0];
				$userfieldArr = $returnArr[1];

				if (strstr($prtemplate, "{if product_userfield}") && strstr($prtemplate, "{product_userfield end if}") && $template_userfield != "")
				{
					$ufield = "";
					$cart = $session->get('cart');

					if (isset($cart['idx']))
					{
						$idx = (int) ($cart['idx']);
					}

					$idx = 0;
					$cart_id = '';

					for ($j = 0; $j < $idx; $j++)
					{
						if ($cart[$j]['product_id'] == $this->data->product_id)
						{
							$cart_id = $j;
						}
					}

					for ($ui = 0; $ui < count($userfieldArr); $ui++)
					{
						if (!$idx)
						{
							$cart_id = "";
						}

						$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', $cart_id, 0, $this->data->product_id);

						$ufield .= $product_userfileds[1];

						if ($product_userfileds[1] != "")
						{
							$count_no_user_field++;
						}

						$prtemplate = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $product_userfileds[0], $prtemplate);
						$prtemplate = str_replace('{' . $userfieldArr[$ui] . '}', $product_userfileds[1], $prtemplate);
					}

					$product_userfileds_form = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

					if ($ufield != "")
					{
						$prtemplate = str_replace("{if product_userfield}", $product_userfileds_form, $prtemplate);
						$prtemplate = str_replace("{product_userfield end if}", "</form>", $prtemplate);
					}
					else
					{
						$prtemplate = str_replace("{if product_userfield}", "", $prtemplate);
						$prtemplate = str_replace("{product_userfield end if}", "", $prtemplate);
					}
				}

				// Product User Field End

				$childproduct = $producthelper->getChildProduct($product->product_id);

				if (count($childproduct) > 0)
				{
					$isChilds = true;
					$attributes = array();
				}
				else
				{
					$isChilds = false;

					// Get attributes
					$attributes_set = array();

					if ($product->attribute_set_id > 0)
					{
						$attributes_set = $producthelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
					}

					$attributes = $producthelper->getProductAttribute($product->product_id);
					$attributes = array_merge($attributes, $attributes_set);
				}

				$prtemplate = $producthelper->replaceCartTemplate(
					$product->product_id,
					0,
					0,
					0,
					$prtemplate,
					false,
					$userfieldArr,
					$totalatt,
					$totalAccessory,
					$count_no_user_field,
					$module_id
				);

				$attribute_template = $producthelper->getAttributeTemplate($prtemplate);
				$prtemplate = $producthelper->replaceAttributeData($product->product_id, 0, 0, $attributes, $prtemplate, $attribute_template, $isChilds);

				$row->text = str_replace($matches[$i], $prtemplate, $row->text);
			}
		}

		return true;
	}
}
