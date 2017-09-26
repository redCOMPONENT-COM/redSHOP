<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Attribute Helper
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       2.0.3
 */
abstract class RedshopHelperAttribute
{
	/**
	 * Method for replace attribute data in template.
	 *
	 * @param   int     $productId           Product ID
	 * @param   int     $accessoryId         Accessory ID
	 * @param   int     $relatedProductId    Related product ID
	 * @param   array   $attributes          List of attribute data.
	 * @param   string  $templateContent     HTML content of template.
	 * @param   object  $attributeTemplate   List of attribute templates.
	 * @param   bool    $isChild             Is child?
	 * @param   array   $selectedAttributes  Preselected attribute list.
	 * @param   int     $displayIndCart      Display in cart?
	 * @param   bool    $onlySelected        True for just render selected / pre-selected attribute. False as normal.
	 *
	 * @return  string                       HTML content with replaced data.
	 *
	 * @since   2.0.3
	 */
	public static function replaceAttributeData($productId = 0, $accessoryId = 0, $relatedProductId = 0, $attributes = array(), $templateContent = '',
		$attributeTemplate = null, $isChild = false, $selectedAttributes = array(), $displayIndCart = 1, $onlySelected = false)
	{
		$user_id         = 0;
		$stockroomHelper = rsstockroomhelper::getInstance();
		$productHelper   = productHelper::getInstance();
		$session         = JFactory::getSession();

		$chktagArr['chkvat'] = $chktag = $productHelper->getApplyattributeVatOrNot($templateContent);
		$session->set('chkvat', $chktagArr);

		if (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE') == 1 && $displayIndCart)
		{
			$attributeTemplate = empty($attributeTemplate) ? $productHelper->getAttributeTemplate($templateContent, false) : $attributeTemplate;

			if (!empty($attributeTemplate))
			{
				$templateContent = str_replace("{attribute_template:$attributeTemplate->template_name}", "", $templateContent);
			}

			return self::replaceAttributewithCartData(
				$productId, $accessoryId, $relatedProductId, $attributes, $templateContent, $attributeTemplate, $isChild, $onlySelected
			);
		}

		$attributeTemplate = empty($attributeTemplate) ? $productHelper->getAttributeTemplate($templateContent, false) : $attributeTemplate;

		if (empty($attributeTemplate))
		{
			return $templateContent;
		}

		$templateContent = str_replace("{attributewithcart_template:$attributeTemplate->template_name}", "", $templateContent);

		if ($isChild || count($attributes) <= 0)
		{
			$templateContent = str_replace("{attribute_template:$attributeTemplate->template_name}", "", $templateContent);

			return $templateContent;
		}

		JHtml::script('com_redshop/thumbscroller.js', false, true);
		$layout = JFactory::getApplication()->input->getCmd('layout', '');

		$preprefix = "";
		$isAjax    = 0;

		if ($layout == "viewajaxdetail")
		{
			$preprefix = "ajax_";
			$isAjax    = 1;
		}

		if ($accessoryId != 0)
		{
			$prefix = $preprefix . "acc_";
		}
		elseif ($relatedProductId != 0)
		{
			$prefix = $preprefix . "rel_";
		}
		else
		{
			$prefix = $preprefix . "prd_";
		}

		if ($relatedProductId != 0)
		{
			$productId = $relatedProductId;
		}

		$selectProperty    = array();
		$selectSubproperty = array();

		if (count($selectedAttributes) > 0)
		{
			$selectProperty    = $selectedAttributes[0];
			$selectSubproperty = $selectedAttributes[1];
		}

		$attribute_template_data = $attributeTemplate->template_desc;

		$product         = RedshopHelperProduct::getProductById($productId);
		$producttemplate = RedshopHelperTemplate::getTemplate("product", $product->product_template);

		if (strpos($producttemplate[0]->template_desc, "{more_images_3}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_3');
		}
		elseif (strpos($producttemplate[0]->template_desc, "{more_images_2}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_2');
		}
		elseif (strpos($producttemplate[0]->template_desc, "{more_images_1}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}
		else
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}

		JText::script('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED');

		if (count($attributes) > 0)
		{
			$attribute_table = "<span id='attribute_ajax_span'>";

			// Import plugin group
			JPluginHelper::importPlugin('redshop_product');

			for ($a = 0, $an = count($attributes); $a < $an; $a++)
			{
				$subdisplay = false;

				$property_all = empty($attributes[$a]->properties) ? $productHelper->getAttibuteProperty(0, $attributes[$a]->attribute_id) :
					$attributes[$a]->properties;
				$property_all = array_values($property_all);

				if (!Redshop::getConfig()->get('DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA') && Redshop::getConfig()->get('USE_STOCKROOM'))
				{
					$property = $productHelper->getAttibutePropertyWithStock($property_all);
				}
				else
				{
					$property = $property_all;
				}

				$propertyIds = array_map(
					function($object)
					{
						return $object->value;
					},
					$property
				);

				$propertyStockrooms         = RedshopHelperStockroom::getMultiSectionsStock($propertyIds, 'property');
				$propertyPreOrderStockrooms = RedshopHelperStockroom::getMultiSectionsPreOrderStock($propertyIds, 'property');

				if ($attributes[$a]->text != "" && count($property) > 0)
				{
					$attribute_table .= $attribute_template_data;

					$commonid    = $prefix . $productId . '_' . $accessoryId . '_' . $attributes[$a]->value;
					$hiddenattid = 'attribute_id_' . $prefix . $productId . '_' . $accessoryId;
					$propertyid  = 'property_id_' . $commonid;

					$imgAdded               = 0;
					$selectedProperty       = 0;
					$property_woscrollerdiv = "";

					if (strpos($attribute_table, "{property_image_without_scroller}") !== false)
					{
						$attribute_table        = str_replace("{property_image_scroller}", "", $attribute_table);
						$property_woscrollerdiv = "<div class='property_main_outer'>";
					}

					for ($i = 0, $in = count($property); $i < $in; $i++)
					{
						if (count($selectProperty) > 0)
						{
							if (in_array($property[$i]->value, $selectProperty))
							{
								$selectedProperty = $property[$i]->value;
							}
						}
						else
						{
							if ($property[$i]->setdefault_selected)
							{
								$selectedProperty = $property[$i]->value;
							}
						}

						if (isset($property[$i]->sub_properties))
						{
							$subproperty_all = $property[$i]->sub_properties;
						}
						else
						{
							$subproperty_all = $productHelper->getAttibuteSubProperty(0, $property[$i]->value);
						}

						// Filter Out of stock data
						if (!Redshop::getConfig()->get('DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA') && Redshop::getConfig()->get('USE_STOCKROOM'))
						{
							$subproperty = $productHelper->getAttibuteSubPropertyWithStock($subproperty_all);
						}
						else
						{
							$subproperty = $subproperty_all;
						}

						$subpropertystock          = 0;
						$preorder_subpropertystock = 0;

						$subPropertyIds = array_map(
							function ($item)
							{
								return $item->value;
							},
							$subproperty
						);
						$subPropertyStockrooms = RedshopHelperStockroom::getMultiSectionsStock($subPropertyIds, 'subproperty');
						$subPropertyPreOrderStockrooms = RedshopHelperStockroom::getMultiSectionsPreOrderStock($subPropertyIds, 'subproperty');

						foreach ($subproperty as $sub)
						{
							$subpropertystock += isset($subPropertyStockrooms[$sub->value]) ? (int) $subPropertyStockrooms[$sub->value] : 0;
							$preorder_subpropertystock += isset($subPropertyPreOrderStockrooms[$sub->value]) ?
								(int) $subPropertyPreOrderStockrooms[$sub->value] : 0;
						}

						$property_stock = isset($propertyStockrooms[$property[$i]->value]) ? (int) $propertyStockrooms[$property[$i]->value] : 0;
						$property_stock += $subpropertystock;

						// Preorder stock data
						$preorder_property_stock = isset($propertyPreOrderStockrooms[$property[$i]->value]) ?
							(int) $propertyPreOrderStockrooms[$property[$i]->value] : 0;
						$preorder_property_stock += $preorder_subpropertystock;

						if ($property[$i]->property_image)
						{
							if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property[$i]->property_image))
							{
								$borderstyle = ($selectedProperty == $property[$i]->value) ? " 1px solid " : "";

								$thumbUrl = RedShopHelperImages::getImagePath(
									$property[$i]->property_image,
									'',
									'thumb',
									'product_attributes',
									$mpw_thumb,
									$mph_thumb,
									Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								);

								$property_woscrollerdiv .= "<div class='property_image_inner' id='" . $propertyid
									. "_propimg_" . $property[$i]->value . "'><a onclick='setPropImage(\"" . $productId
									. "\",\"" . $propertyid . "\",\"" . $property[$i]->value . "\");changePropertyDropdown(\""
									. $productId . "\",\"" . $accessoryId . "\",\"" . $relatedProductId . "\",\""
									. $attributes [$a]->value . "\",\"" . $property[$i]->value . "\",\"" . $mpw_thumb
									. "\",\"" . $mph_thumb
									. "\");'><img class='redAttributeImage' width='50' height='50' src='" . $thumbUrl . "'></a></div>";
								$imgAdded++;
							}
						}

						$attributes_property_vat_show   = 0;
						$attributes_property_withoutvat = 0;
						$attributes_property_oldprice   = 0;

						if ($property [$i]->property_price > 0)
						{
							$attributes_property_oldprice = $property [$i]->property_price;

							$pricelist = $productHelper->getPropertyPrice($property[$i]->value, 1, 'property');

							if (count($pricelist) > 0)
							{
								$property[$i]->property_price = $pricelist->product_price;
							}

							$attributes_property_withoutvat = $property [$i]->property_price;

							/*
							 * changes for {without_vat} tag output parsing
							 * only for display purpose
							 */
							$attributes_property_vat_show = 0;
							$attributes_property_oldprice_vat = 0;

							if (!empty($chktag))
							{
								if ($property [$i]->oprand != '*' && $property [$i]->oprand != '/')
								{
									$attributes_property_vat_show = $productHelper->getProducttax($productId, $property [$i]->property_price, $user_id);
									$attributes_property_oldprice_vat = $productHelper->getProducttax($productId, $attributes_property_oldprice, $user_id);
								}
							}

							$attributes_property_vat_show += $property [$i]->property_price;
							$attributes_property_oldprice += $attributes_property_oldprice_vat;

							/*
							 * get product vat to include
							 */
							$attributes_property_vat = $productHelper->getProducttax($productId, $property [$i]->property_price, $user_id);
							$property [$i]->property_price += $attributes_property_vat;

							if (Redshop::getConfig()->get('SHOW_PRICE')
								&& (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
								|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
								&& (!$attributes[$a]->hide_attribute_price))
							{
								$property[$i]->text = urldecode($property[$i]->property_name) . " (" . $property [$i]->oprand
									. $productHelper->getProductFormattedPrice($attributes_property_vat_show) . ")";
							}
							else
							{
								$property[$i]->text = urldecode($property[$i]->property_name);
							}
						}
						else
						{
							$property[$i]->text = urldecode($property[$i]->property_name);
						}

						// Add stock data into property data.
						$property[$i]->stock = $property_stock;

						// Add pre-order stock data into property data.
						$property[$i]->preorder_stock = $preorder_property_stock;


						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_oprand' . $property [$i]->value
							. '" value="' . $property [$i]->oprand . '" />';
						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_proprice' . $property [$i]->value
							. '" value="' . $attributes_property_vat_show . '" />';
						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_proprice_withoutvat' . $property [$i]->value
							. '" value="' . $attributes_property_withoutvat . '" />';
						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_prooldprice' . $property [$i]->value
							. '" value="' . $attributes_property_oldprice . '" />';
						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_stock' . $property [$i]->value . '" value="'
							. $property_stock . '" />';
						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_preorderstock' . $property [$i]->value
							. '" value="' . $preorder_property_stock . '" />';
					}

					if (!$mph_thumb)
					{
						$mph_thumb = 50;
					}

					if (!$mpw_thumb)
					{
						$mpw_thumb = 50;
					}

					$atth = 50;
					$attw = 50;

					if (Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT'))
					{
						$atth = Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT');
					}

					if (Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH'))
					{
						$attw = Redshop::getConfig()->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH');
					}

					if (strpos($attribute_table, "{property_image_without_scroller}") !== false)
					{
						$property_woscrollerdiv .= "</div>";
					}

					// Run event for prepare product properties.
					RedshopHelperUtility::getDispatcher()->trigger('onPrepareProductProperties', array($product, &$property));

					$properties = array_merge(
						array(JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT') . ' ' . urldecode($attributes[$a]->text))),
						$property
					);
					$defaultPropertyId = array();
					$attDisplayType    = $attributes[$a]->display_type;

					// Init listing html-attributes
					$chkListAttributes = array(
						'attribute_name' => urldecode($attributes[$a]->attribute_name)
					);

					// Only add required html-attibute if needed.
					if ($attributes[$a]->attribute_required)
					{
						$chkListAttributes['required'] = 'true';
					}

					// Prepare Javascript OnChange or OnClick function
					$changePropertyDropdown = "changePropertyDropdown('" . $productId . "','" . $accessoryId . "','"
						. $relatedProductId . "', '" . $attributes[$a]->value . "',this.value, '" . $mpw_thumb . "', '" . $mph_thumb . "');";

					// Radio or Checkbox
					if ($attDisplayType == 'radio')
					{
						unset($properties[0]);

						$attributeListType = ($attributes[$a]->allow_multiple_selection) ? 'redshopselect.checklist' : 'redshopselect.radiolist';

						$chkListAttributes['cssClassSuffix'] = ' no-group';
						$chkListAttributes['onClick']        = "javascript:" . $changePropertyDropdown;
					}
					// Dropdown list
					else
					{
						$attributeListType = 'select.genericlist';
						$scrollerFunction  = '';

						if ($imgAdded > 0 && strpos($attribute_table, "{property_image_scroller}") !== false)
						{
							$scrollerFunction = "isFlowers" . $commonid . ".scrollImageCenter(this.selectedIndex-1);";
						}

						$chkListAttributes['id']       = $propertyid;
						$chkListAttributes['onchange'] = "javascript:" . $scrollerFunction . $changePropertyDropdown;
					}

					if ($selectedProperty)
					{
						$subdisplay          = true;
						$defaultPropertyId[] = $selectedProperty;
					}

					$lists['property_id'] = JHTML::_(
						$attributeListType,
						$properties,
						$propertyid . '[]',
						$chkListAttributes,
						'value',
						'text',
						$selectedProperty,
						$propertyid . '_'
					);

					$attribute_table .= "<input type='hidden' name='" . $hiddenattid . "[]' value='" . $attributes [$a]->value . "' />";

					if ($attributes [$a]->attribute_required > 0)
					{
						$pos        = Redshop::getConfig()->get('ASTERISK_POSITION') > 0 ? urldecode($attributes [$a]->text) . "<span id='asterisk_right'> * " : "<span id='asterisk_left'>* </span>" . urldecode($attributes[$a]->text);
						$attr_title = $pos;
					}
					else
					{
						$attr_title = urldecode($attributes[$a]->text);
					}

					if (strpos($attribute_table, '{attribute_tooltip}') !== false)
					{
						if (!empty($attributes[$a]->attribute_description))
						{
							$tooltip = JHTML::tooltip($attributes[$a]->attribute_description, $attributes[$a]->attribute_description, 'tooltip.png', '', '');
							$attribute_table = str_replace("{attribute_tooltip}", $tooltip, $attribute_table);
						}
						else
						{
							$attribute_table = str_replace("{attribute_tooltip}", "", $attribute_table);
						}
					}

					$attribute_table = str_replace("{attribute_title}", $attr_title, $attribute_table);
					$attribute_table = str_replace("{property_dropdown}", $lists ['property_id'], $attribute_table);

					$propertyScroller = RedshopLayoutHelper::render(
						'product.property_scroller',
						array(
								'attribute'        => $attributes[$a],
								'properties'       => $property,
								'commonId'         => $commonid,
								'productId'        => $productId,
								'propertyId'       => $propertyid,
								'accessoryId'      => $accessoryId,
								'relatedProductId' => $relatedProductId,
								'selectedProperty' => $selectedProperty,
								'width'            => $mpw_thumb,
								'height'           => $mph_thumb
							),
						'',
						array(
								'component' => 'com_redshop'
							)
					);

					// Changes for attribue Image Scroll
					if ($imgAdded == 0 || $isAjax == 1)
					{
						$propertyScroller = "";
					}

					$attribute_table = str_replace("{property_image_scroller}", $propertyScroller, $attribute_table);
					$attribute_table = str_replace("{property_image_without_scroller}", $property_woscrollerdiv, $attribute_table);

					if ($subdisplay)
					{
						$style = ' style="display:block" ';
					}
					else
					{
						$style = ' style="display:none" ';
					}

					$subpropertydata  = "";
					$subpropertystart = $attribute_table;
					$subpropertyend   = "";
					$subattdata       = explode("{subproperty_start}", $attribute_table);

					if (count($subattdata) > 0)
					{
						$subpropertystart = $subattdata[0];
					}

					$replaceMiddle = '';

					if (count($subattdata) > 1)
					{
						$subattdata = explode("{subproperty_end}", $subattdata[1]);

						if (count($subattdata) > 0)
						{
							$subpropertydata = $subattdata[0];
							$replaceMiddle   = "{replace_subprodata}";
						}

						if (count($subattdata) > 1)
						{
							$subpropertyend = $subattdata[1];
						}
					}

					$subproperty_start = '<div id="property_responce' . $commonid . '" ' . $style . '>';

					$displaySubproperty = "";

					for ($selp = 0; $selp < count($defaultPropertyId); $selp++)
					{
						$displaySubproperty .= $productHelper->replaceSubPropertyData(
							$productId, $accessoryId, $relatedProductId, $attributes[$a]->attribute_id, $defaultPropertyId[$selp], $subpropertydata,
							$layout, $selectSubproperty
						);
					}

					if ($subdisplay)
					{
						$attribute_table = $subpropertystart . "{subproperty_start}" . $replaceMiddle . "{subproperty_end}" . $subpropertyend;
						$attribute_table = str_replace($replaceMiddle, $displaySubproperty, $attribute_table);
					}

					$attribute_table .= "<input type='hidden' id='subattdata_" . $commonid . "' value='"
						. base64_encode(htmlspecialchars($subpropertydata)) . "' />";
					$subPropertyHtml = array();

					foreach ($property as $key => $propertyValue)
					{
						$subProperties = RedshopHelperProduct_Attribute::getAttributeSubProperties(0, $propertyValue->value);
						$subPropertyHtml[] = RedshopLayoutHelper::render(
							'product.subproperty_price_list',
							array(
								'productId' => $productId,
								'userId'    => JFactory::getUser()->id,
								'propertyId' => $propertyValue->value,
								'subProperties' => $subProperties,
								'templateContent' => $templateContent,
								'subPropertyData' => $subpropertydata,
								'commonId' => $commonid
							),
							'',
							array(
								'component' => 'com_redshop'
							)
						);
					}

					$attribute_table = str_replace("{subproperty_price_list}", implode('', $subPropertyHtml), $attribute_table);
					$attribute_table = str_replace("{subproperty_start}", $subproperty_start, $attribute_table);
					$attribute_table = str_replace("{subproperty_end}", "</div>", $attribute_table);
				}
			}

			$attribute_table .= "<span id='cart_attribute_box'></span></span>";

			$templateContent = str_replace("{attribute_template:$attributeTemplate->template_name}", $attribute_table, $templateContent);
		}
		else
		{
			$templateContent = str_replace("{attribute_template:$attributeTemplate->template_name}", "", $templateContent);
		}

		return $templateContent;
	}

	/**
	 * Method for replace attribute data with allow add to cart in template.
	 *
	 * @param   int     $productId          Product ID
	 * @param   int     $accessoryId        Accessory ID
	 * @param   int     $relatedProductId   Related product ID
	 * @param   array   $attributes         List of attribute data.
	 * @param   string  $templateContent    HTML content of template.
	 * @param   object  $attributeTemplate  List of attribute templates.
	 * @param   bool    $isChild            Is child?
	 * @param   bool    $onlySelected       True for just render selected / pre-selected attribute. False as normal.
	 *
	 * @return  string                      HTML content with replaced data.
	 *
	 * @since   2.0.3
	 */
	public static function replaceAttributeWithCartData($productId = 0, $accessoryId = 0, $relatedProductId = 0, $attributes = array(),
		$templateContent = '', $attributeTemplate = null, $isChild = false, $onlySelected = false)
	{
		$user_id       = 0;
		$productHelper = productHelper::getInstance();

		if (empty($attributeTemplate))
		{
			return $templateContent;
		}

		if ($isChild || !count($attributes))
		{
			return str_replace("{attributewithcart_template:$attributeTemplate->template_name}", "", $templateContent);
		}

		$layout    = JFactory::getApplication()->input->getCmd('layout', '');
		$prePrefix = "";
		$isAjax    = false;

		if ($layout == 'viewajaxdetail')
		{
			$prePrefix = "ajax_";
			$isAjax    = true;
		}

		if ($accessoryId != 0)
		{
			$prefix = $prePrefix . "acc_";
		}
		elseif ($relatedProductId != 0)
		{
			$prefix = $prePrefix . "rel_";
		}
		else
		{
			$prefix = $prePrefix . "prd_";
		}

		if ($relatedProductId != 0)
		{
			$productId = $relatedProductId;
		}

		$product         = RedshopHelperProduct::getProductById($productId);
		$productTemplate = RedshopHelperTemplate::getTemplate("product", $product->product_template);
		$productTemplate = $productTemplate[0];

		if (strpos($productTemplate->template_desc, "{more_images_3}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_3');
		}
		elseif (strpos($productTemplate->template_desc, "{more_images_2}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_2');
		}
		elseif (strpos($productTemplate->template_desc, "{more_images_1}") !== false)
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}
		else
		{
			$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
			$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
		}

		$cartTemplate   = array();
		$attributeTable = "";

		foreach ($attributes as $attribute)
		{
			$attributeTable .= $attributeTemplate->template_desc;

			$attributeTable = str_replace("{property_image_lbl}", JText::_('COM_REDSHOP_PROPERTY_IMAGE_LBL'), $attributeTable);
			$attributeTable = str_replace("{virtual_number_lbl}", JText::_('COM_REDSHOP_VIRTUAL_NUMBER_LBL'), $attributeTable);
			$attributeTable = str_replace("{property_name_lbl}", JText::_('COM_REDSHOP_PROPERTY_NAME_LBL'), $attributeTable);
			$attributeTable = str_replace("{property_price_lbl}", JText::_('COM_REDSHOP_PROPERTY_PRICE_LBL'), $attributeTable);
			$attributeTable = str_replace("{property_stock_lbl}", JText::_('COM_REDSHOP_PROPERTY_STOCK_LBL'), $attributeTable);
			$attributeTable = str_replace("{add_to_cart_lbl}", JText::_('COM_REDSHOP_ADD_TO_CART_LBL'), $attributeTable);

			if (empty($attribute->properties))
			{
				$properties = RedshopHelperProduct_Attribute::getAttributeProperties(0, $attribute->attribute_id);
			}
			else
			{
				$properties = $attribute->properties;
			}

			if (empty($attribute->text) || empty($properties)
				|| strpos($attributeTable, "{property_start}") === false || strpos($attributeTable, "{property_start}") === false)
			{
				continue;
			}

			$start            = explode("{property_start}", $attributeTable);
			$end              = explode("{property_end}", $start[1]);
			$propertyTemplate = $end[0];

			$commonId   = $prefix . $productId . '_' . $accessoryId . '_' . $attribute->value;
			$propertyId = 'property_id_' . $commonId;

			$propertyData = "";

			foreach ($properties as $property)
			{
				// Skip if "onlySelected" is true and this property not set as selected.
				if ($onlySelected && !$property->setdefault_selected)
				{
					continue;
				}

				$propertyData .= $propertyTemplate;

				$priceWithVat    = 0;
				$priceWithoutVat = 0;
				$propertyStock         = RedshopHelperStockroom::getStockAmountWithReserve($property->value, "property");
				$preOrderPropertyStock = RedshopHelperStockroom::getPreorderStockAmountwithReserve($property->value, "property");

				$propertyData = str_replace("{property_name}", urldecode($property->property_name), $propertyData);
				$propertyData = str_replace("{virtual_number}", $property->property_number, $propertyData);

				// Replace {property_stock}
				if (strpos($propertyData, '{property_stock}') !== false)
				{
					$displayStock = ($propertyStock) ? JText::_('COM_REDSHOP_IN_STOCK') : JText::_('COM_REDSHOP_NOT_IN_STOCK');
					$propertyData = str_replace("{property_stock}", $displayStock, $propertyData);
				}

				// Replace {property_image}
				if (strpos($propertyData, '{property_image}') !== false)
				{
					$propertyImage = "";

					if ($property->property_image
						&& JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property->property_image))
					{
						$thumbUrl = RedshopHelperMedia::getImagePath(
							$property->property_image,
							'',
							'thumb',
							'product_attributes',
							$mpw_thumb,
							$mph_thumb,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
						$propertyImage = "<img title='" . urldecode($property->property_name) . "' src='" . $thumbUrl . "'>";
					}

					$propertyData = str_replace("{property_image}", $propertyImage, $propertyData);
				}

				if (strpos($propertyData, '{property_oprand}') !== false || strpos($propertyData, '{property_price}') !== false)
				{
					$price           = '';
					$opRand          = '';

					if ($property->property_price > 0)
					{
						$prices = $productHelper->getPropertyPrice($property->value, 1, 'property');

						if (count($prices) > 0)
						{
							$property->property_price = $prices->product_price;
						}

						$priceWithoutVat = $property->property_price;

						if ($productHelper->getApplyattributeVatOrNot($propertyData))
						{
							$priceWithVat = $productHelper->getProducttax($productId, $property->property_price, $user_id);
						}

						$priceWithVat += $property->property_price;

						if (Redshop::getConfig()->get('SHOW_PRICE')
							&& (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
							|| (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))
							&& !$attribute->hide_attribute_price)
						{
							$opRand = $property->oprand;
							$price  = $productHelper->getProductFormattedPrice($priceWithVat);
						}
					}

					$propertyData = str_replace("{property_oprand}", $opRand, $propertyData);
					$propertyData = str_replace("{property_price}", $price, $propertyData);
				}

				if (!count($cartTemplate))
				{
					$cartTemplate = $productHelper->getAddtoCartTemplate($propertyData);
				}

				if (count($cartTemplate) > 0)
				{
					$propertyData = $productHelper->replacePropertyAddtoCart(
						$productId, $property->value, 0, $propertyId, $propertyStock,
						$propertyData, $cartTemplate, $templateContent
					);
				}

				$propertyData .= '<input type="hidden" id="' . $propertyId . '_oprand' . $property->value
					. '" value="' . $property->oprand . '" />';
				$propertyData .= '<input type="hidden" id="' . $propertyId . '_proprice' . $property->value
					. '" value="' . $priceWithVat . '" />';
				$propertyData .= '<input type="hidden" id="' . $propertyId . '_proprice_withoutvat'
					. $property->value . '" value="' . $priceWithoutVat . '" />';

				$propertyData .= '<input type="hidden" id="' . $propertyId . '_stock' . $property->value
					. '" value="' . $propertyStock . '" />';
				$propertyData .= '<input type="hidden" id="' . $propertyId . '_preorderstock'
					. $property->value . '" value="' . $preOrderPropertyStock . '" />';

				$formId = 'addtocart_' . $propertyId . '_' . $property->value;

				$propertyData = RedshopHelperWishlist::replaceWishlistTag($productId, $propertyData, $formId);
			}

			$attributeTitle = urldecode($attribute->text);

			if ($attribute->attribute_required > 0)
			{
				$pos = Redshop::getConfig()->get('ASTERISK_POSITION') > 0 ? urldecode($attribute->text)
					. "<span id='asterisk_right'> * " : "<span id='asterisk_left'>* </span>"
					. urldecode($attribute->text);
				$attributeTitle = $pos;
			}

			$attributeTable = str_replace("{attribute_title}", $attributeTitle, $attributeTable);
			$attributeTable = str_replace("{property_start}", "", $attributeTable);
			$attributeTable = str_replace("{property_end}", "", $attributeTable);
			$attributeTable = str_replace($propertyTemplate, $propertyData, $attributeTable);
		}

		if ($attributeTable != "")
		{
			$cart_template = $productHelper->getAddtoCartTemplate($templateContent);

			if (count($cart_template) > 0)
			{
				$templateContent = str_replace("{form_addtocart:$cart_template->template_name}", "", $templateContent);
			}
		}

		return str_replace("{attributewithcart_template:$attributeTemplate->template_name}", $attributeTable, $templateContent);
	}
}
