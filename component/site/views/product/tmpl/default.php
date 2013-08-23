<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();

$url             = JURI::base();
$u               = JURI::getInstance();
$Scheme          = $u->getScheme();

$Itemid          = JRequest::getVar('Itemid');
$print           = JRequest::getVar('print');
$model           = $this->getModel('product');
$user            = JFactory::getUser();
$session         = JFactory::getSession();
$document        = JFactory::getDocument();
$dispatcher      = JDispatcher::getInstance();

$extraField      = new extraField;
$texts           = new text_library;
$producthelper   = new producthelper;
$redshopconfig   = new Redconfiguration;
$stockroomhelper = new rsstockroomhelper;
$redTemplate     = new Redtemplate;
$config          = new Redconfiguration;
$redhelper       = new redhelper;

$template = $this->template;

if (count($template) > 0 && $template->template_desc != "")
{
	$template_desc = $template->template_desc;
}
else
{
	$template_desc = "<div id=\"produkt\">\r\n<div class=\"produkt_spacer\"></div>\r\n<div class=\"produkt_anmeldelser_opsummering\">{product_rating_summary}</div>\r\n<div id=\"opsummering_wrapper\">\r\n<div id=\"opsummering_skubber\"></div>\r\n<div id=\"opsummering_link\">{product_rating_summary}</div>\r\n</div>\r\n<div id=\"produkt_kasse\">\r\n<div class=\"produkt_kasse_venstre\">\r\n<div class=\"produkt_kasse_billed\">{product_thumb_image}</div>\r\n<div class=\"produkt_kasse_billed_flere\">{more_images}</div>\r\n<div id=\"produkt_kasse_venstre_tekst\">{view_full_size_image_lbl}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre\">\r\n{attribute_template:attributes}<div class=\"produkt_kasse_hoejre_accessory\">{accessory_template:accessory}</div>\r\n<div class=\"produkt_kasse_hoejre_pris\">\r\n<div class=\"produkt_kasse_hoejre_pris_indre\" id=\"produkt_kasse_hoejre_pris_indre\">{product_price}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre_laegikurv\">\r\n<div class=\"produkt_kasse_hoejre_laegikurv_indre\">{form_addtocart:add_to_cart2}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre_leveringstid\">\r\n<div class=\"produkt_kasse_hoejre_leveringstid_indre\">{delivery_time_lbl}: {product_delivery_time}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre_bookmarksendtofriend\">\r\n<div class=\"produkt_kasse_hoejre_bookmark\">{bookmark}</div>\r\n<div class=\"produkt_kasse_hoejre_sendtofriend\">{send_to_friend}</div>\r\n</div>\r\n</div>\r\n<div id=\"produkt_beskrivelse_wrapper\">\r\n<div class=\"produkt_beskrivelse\">\r\n<div id=\"produkt_beskrivelse_maal\">\r\n<div id=\"produkt_maal_wrapper\">\r\n<div id=\"produkt_maal_indhold_hojre\">\r\n<div id=\"produkt_hojde\">{product_height_lbl}: {product_height}</div>\r\n<div id=\"produkt_bredde\">x {product_width_lbl}: {product_width}</div>\r\n<div id=\"produkt_dybde\">x {product_length_lbl}: {product_length}</div>\r\n<div style=\"width: 275px; height: 10px; clear: left;\"></div>\r\n<div id=\"producent_link\">{manufacturer_link}</div>\r\n<div id=\"produkt_writereview\">{form_rating}</div>\r\n</div>\r\n</div>\r\n</div>\r\n<h2>{product_name}</h2>\r\n<div id=\"beskrivelse_lille\">{product_s_desc}</div>\r\n<div id=\"beskrivelse_stor\">{product_desc}</div>\r\n<div class=\"product_related_products\">{related_product:related_products}</div>\r\n</div>\r\n</div>\r\n<div id=\"produkt_anmeldelser\">\r\n{product_rating}</div>\r\n</div>\r\n</div>";
}
?>

<div class="product">
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
		<?php
		if (count($this->data) > 0)
		{
			if ($this->data->pageheading != "")
			{
				echo $this->escape($this->data->pageheading);
			}
			else
			{
				echo $this->escape($this->pageheadingtag);
			}
		}?>
	</div>
</div>
<div style="clear:both"></div>

<?php
// Display after title data
echo $this->data->event->afterDisplayTitle;

// Display before product data
echo $this->data->event->beforeDisplayProduct;

/*
 * Replace Discount Calculator Tag
 * update by Gunjan
 */
$discount_calculator = "";

if ($this->data->use_discount_calc)
{
	// Get discount calculator Template
	$template_desc = str_replace('{discount_calculator}', $this->loadTemplate('calculator'), $template_desc);
}
else
{
	$template_desc = str_replace('{discount_calculator}', '', $template_desc);
}

$template_desc = str_replace('{component_heading}', $this->escape($this->data->product_name), $template_desc);

if (strstr($template_desc, '{back_link}'))
{
	$back_link     = '<a href="' . htmlentities($_SERVER['HTTP_REFERER']) . '">' . JText::_('COM_REDSHOP_BACK') . '</a>';
	$template_desc = str_replace('{back_link}', $back_link, $template_desc);
}

if (strstr($template_desc, '{returntocategory_link}') || strstr($template_desc, '{returntocategory_name}') || strstr($template_desc, '{returntocategory}'))
{
	$returncatlink    = '';
	$returntocategory = '';

	if ($this->data->category_id)
	{
		$returncatlink    = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $this->data->category_id . '&Itemid=' . $Itemid);
		$returntocategory = '<a href="' . $returncatlink . '">' . DAFULT_RETURN_TO_CATEGORY_PREFIX . " " . $this->data->category_name . '</a>';
	}

	$template_desc = str_replace('{returntocategory_link}', $returncatlink, $template_desc);
	$template_desc = str_replace('{returntocategory_name}', $this->data->category_name, $template_desc);
	$template_desc = str_replace('{returntocategory}', $returntocategory, $template_desc);
}

if (strstr($template_desc, '{navigation_link_right}') || strstr($template_desc, '{navigation_link_left}'))
{
	$nextbutton = '';
	$prevbutton = '';

	// Next Navigation
	$nextproducts = $model->getPrevNextproduct($this->data->product_id, $this->data->category_id, 1);

	if (count($nextproducts) > 0)
	{
		$nextlink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $nextproducts->product_id . '&cid=' . $this->data->category_id . '&Itemid=' . $Itemid);

		if (DEFAULT_LINK_FIND == 0)
		{
			$nextbutton = '<a href="' . $nextlink . '">' . $nextproducts->product_name . "" . DAFULT_NEXT_LINK_SUFFIX . '</a>';
		}
		elseif (DEFAULT_LINK_FIND == 1)
		{
			$nextbutton = '<a href="' . $nextlink . '">' . CUSTOM_NEXT_LINK_FIND . '</a>';
		}
		elseif (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . IMAGE_PREVIOUS_LINK_FIND))
		{
			$nextbutton = '<a href="' . $nextlink . '"><img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . IMAGE_NEXT_LINK_FIND . '" /></a>';
		}
	}

	// Start previous logic
	$previousproducts = $model->getPrevNextproduct($this->data->product_id, $this->data->category_id, -1);

	if (count($previousproducts) > 0)
	{
		$prevlink = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $previousproducts->product_id . '&cid=' . $this->data->category_id . '&Itemid=' . $Itemid);

		if (DEFAULT_LINK_FIND == 0)
		{
			$prevbutton = '<a href="' . $prevlink . '">' . DAFULT_PREVIOUS_LINK_PREFIX . "" . $previousproducts->product_name . '</a>';
		}
		elseif (DEFAULT_LINK_FIND == 1)
		{
			$prevbutton = '<a href="' . $prevlink . '">' . CUSTOM_PREVIOUS_LINK_FIND . '</a>';
		}
		elseif (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . IMAGE_PREVIOUS_LINK_FIND))
		{
			$prevbutton = '<a href="' . $prevlink . '"><img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . IMAGE_PREVIOUS_LINK_FIND . '" /></a>';
		}

		// End
	}

	$template_desc = str_replace('{navigation_link_right}', $nextbutton, $template_desc);
	$template_desc = str_replace('{navigation_link_left}', $prevbutton, $template_desc);
}

/*
 * product size variables
 */
$product_volume = "";
$product_volume .= '<span class="length_number">' . $producthelper->redunitDecimal($this->data->product_length) . '</span>';
$product_volume .= '<span class="length_unit">' . DEFAULT_VOLUME_UNIT . '</span>';
$product_volume .= '<span class="separator">X</span>';
$product_volume .= '<span class="width_number">' . $producthelper->redunitDecimal($this->data->product_width) . '</span>';
$product_volume .= '<span class="width_unit">' . DEFAULT_VOLUME_UNIT . '</span>';
$product_volume .= '<span class="separator">X</span>';
$product_volume .= '<span class="height_number">' . $producthelper->redunitDecimal($this->data->product_height) . '</span>';
$product_volume .= '<span class="height_unit">' . DEFAULT_VOLUME_UNIT . '</span>';

$template_desc = str_replace('{product_size}', $product_volume, $template_desc);

if (DEFAULT_VOLUME_UNIT)
	$product_unit = '<span class="product_unit_variable">' . DEFAULT_VOLUME_UNIT . '</span>';
else
	$product_unit = '';

// Product length
if ($this->data->product_length > 0)
{
	$template_desc = str_replace("{product_length_lbl}", JText::_('COM_REDSHOP_PRODUCT_LENGTH_LBL'), $template_desc);
	$template_desc = str_replace('{product_length}', $producthelper->redunitDecimal($this->data->product_length) . "&nbsp" . $product_unit, $template_desc);
}
else
{
	$template_desc = str_replace("{product_length_lbl}", "", $template_desc);
	$template_desc = str_replace('{product_length}', "", $template_desc);
}

// Product width
if ($this->data->product_width > 0)
{
	$template_desc = str_replace("{product_width_lbl}", JText::_('COM_REDSHOP_PRODUCT_WIDTH_LBL'), $template_desc);
	$template_desc = str_replace('{product_width}', $producthelper->redunitDecimal($this->data->product_width) . "&nbsp" . $product_unit, $template_desc);
}
else
{
	$template_desc = str_replace("{product_width_lbl}", "", $template_desc);
	$template_desc = str_replace('{product_width}', "", $template_desc);
}

// Product Height
if ($this->data->product_height > 0)
{
	$template_desc = str_replace("{product_height_lbl}", JText::_('COM_REDSHOP_PRODUCT_HEIGHT_LBL'), $template_desc);
	$template_desc = str_replace('{product_height}', $producthelper->redunitDecimal($this->data->product_height) . "&nbsp" . $product_unit, $template_desc);
}
else
{
	$template_desc = str_replace("{product_height_lbl}", "", $template_desc);
	$template_desc = str_replace('{product_height}', "", $template_desc);
}

// Product Diameter
if ($this->data->product_diameter > 0)
{
	$template_desc = str_replace("{product_diameter_lbl}", JText::_('COM_REDSHOP_PRODUCT_DIAMETER_LBL'), $template_desc);
	$template_desc = str_replace("{diameter}", $producthelper->redunitDecimal($this->data->product_diameter) . "&nbsp" . $product_unit, $template_desc);
}
else
{
	$template_desc = str_replace("{product_diameter_lbl}", "", $template_desc);
	$template_desc = str_replace('{diameter}', "", $template_desc);
}

// Product Volume
$product_volume_unit = '<span class="product_unit_variable">' . DEFAULT_VOLUME_UNIT . "3" . '</span>';

if ($this->data->product_volume > 0)
{
	$template_desc = str_replace("{product_volume_lbl}", JText::_('COM_REDSHOP_PRODUCT_VOLUME_LBL') . JText::_('COM_REDSHOP_PRODUCT_VOLUME_UNIT'), $template_desc);
	$template_desc = str_replace('{product_volume}', $producthelper->redunitDecimal($this->data->product_volume) . "&nbsp" . $product_volume_unit, $template_desc);
}
else
{
	$template_desc = str_replace('{product_volume}', "", $template_desc);
	$template_desc = str_replace("{product_volume_lbl}", "", $template_desc);
}

// Replace Product Template
if ($print)
{
	$onclick = "onclick='window.print();'";
}
else
{
	$print_url = $url . "index.php?option=com_redshop&view=product&pid=" . $this->data->product_id . "&cid=" . $this->data->category_id . "&print=1&tmpl=component&Itemid=" . $Itemid;
	$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}

$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
$print_tag .= "</a>";

// Associate_tag display update nayan panchal start
$ass_tag = '';

if ($redhelper->isredProductfinder())
{
	$associate_tag = $producthelper->getassociatetag($this->data->product_id);

	for ($k = 0; $k < count($associate_tag); $k++)
	{
		if ($associate_tag[$k] != '')
		{
			$ass_tag .= $associate_tag[$k]->type_name . " : " . $associate_tag[$k]->tag_name . "<br/>";
		}
	}
}

// Associate_tag display update nayan panchal end
$template_desc = $producthelper->replaceVatinfo($template_desc);
$template_desc = str_replace("{associate_tag}", $ass_tag, $template_desc);
$template_desc = str_replace("{print}", $print_tag, $template_desc);
$template_desc = str_replace("{product_name}", $this->data->product_name, $template_desc);
$template_desc = str_replace("{product_id_lbl}", JText::_('COM_REDSHOP_PRODUCT_ID_LBL'), $template_desc);
$template_desc = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $template_desc);
$template_desc = str_replace("{product_id}", $this->data->product_id, $template_desc);

$template_desc = str_replace("{product_s_desc}", htmlspecialchars_decode($this->data->product_s_desc), $template_desc);
$template_desc = str_replace("{product_desc}", htmlspecialchars_decode($this->data->product_desc), $template_desc);
$template_desc = str_replace("{view_full_size_image_lbl}", JText::_('COM_REDSHOP_VIEW_FULL_SIZE_IMAGE_LBL'), $template_desc);

if (strstr($template_desc, "{zoom_image}"))
{
	$sendlink      = $url . 'components/com_redshop/assets/images/product/' . $this->data->product_full_image;
	$send_image    = "<a  onclick=\"setZoomImagepath(this)\" title='" . $this->data->product_name . "' id='rsZoom_image" . $this->data->product_id . "' href='" . $sendlink . "' rel=\"lightbox[gallery]\">
			<div class='zoom_image' id='rsDiv_zoom_image'>" . JText::_('SEND_MAIL_IMAGE_LBL') . "</div></a>";
	$template_desc = str_replace("{zoom_image}", $send_image, $template_desc);
}

if (strstr($template_desc, "{product_category_list}"))
{
	$pcats    = "";
	$prodCats = $producthelper->getProductCaterories($this->data->product_id);

	foreach ($prodCats as $prodCat)
	{
		$pcats .= '<a title="' . $prodCat->name . '" href="' . $prodCat->link . '">';
		$pcats .= $prodCat->name;
		$pcats .= "</a><br />";
	}

	$template_desc = str_replace("{product_category_list}", $pcats, $template_desc);
}

if (strstr($template_desc, "{manufacturer_image}"))
{
	$mh_thumb    = MANUFACTURER_THUMB_HEIGHT;
	$mw_thumb    = MANUFACTURER_THUMB_WIDTH;
	$thum_image  = "";
	$media_image = $producthelper->getAdditionMediaImage($this->data->manufacturer_id, "manufacturer");
	$m           = 0;

	if ($media_image[$m]->media_name && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "manufacturer/" . $media_image[$m]->media_name))
	{
		$wimg      = $redhelper->watermark('manufacturer', $media_image[$m]->media_name, $mw_thumb, $mh_thumb, WATERMARK_MANUFACTURER_THUMB_IMAGE);
		$linkimage = $redhelper->watermark('manufacturer', $media_image[$m]->media_name, '', '', WATERMARK_MANUFACTURER_IMAGE);

		$altText = $producthelper->getAltText('manufacturer', $this->data->manufacturer_id);

		if (!$altText)
		{
			$altText = $this->data->manufacturer_name;
		}

		$thum_image = "<a title='" . $altText . "' class=\"modal\" href='" . $linkimage . "'   rel=\"{handler: 'image', size: {}}\">
				<img alt='" . $altText . "' title='" . $altText . "' src='" . $wimg . "'></a>";
	}

	$template_desc = str_replace("{manufacturer_image}", $thum_image, $template_desc);
}

$product_weight_unit = '<span class="product_unit_variable">' . DEFAULT_WEIGHT_UNIT . '</span>';

if ($this->data->weight > 0)
{
	$template_desc = str_replace("{product_weight}", $producthelper->redunitDecimal($this->data->weight) . "&nbsp;" . $product_weight_unit, $template_desc);
	$template_desc = str_replace("{product_weight_lbl}", JText::_('COM_REDSHOP_PRODUCT_WEIGHT_LBL'), $template_desc);
}
else
{
	$template_desc = str_replace("{product_weight}", "", $template_desc);
	$template_desc = str_replace("{product_weight_lbl}", "", $template_desc);
}

$template_desc = $stockroomhelper->replaceStockroomAmountDetail($template_desc, $this->data->product_id);

$template_desc = str_replace("{update_date}", $redshopconfig->convertDateFormat(strtotime($this->data->update_date)), $template_desc);

if ($this->data->publish_date != '0000-00-00 00:00:00')
{
	$template_desc = str_replace("{publish_date}", $redshopconfig->convertDateFormat(strtotime($this->data->publish_date)), $template_desc);
}
else
{
	$template_desc = str_replace("{publish_date}", "", $template_desc);
}

/*
 * Conditional tag
 * if product on discount : Yes
 * {if product_on_sale} This product is on sale {product_on_sale end if} // OUTPUT : This product is on sale
 * NO : // OUTPUT : Display blank
 */
$template_desc = $producthelper->getProductOnSaleComment($this->data, $template_desc);

/*
 * Conditional tag
 * if product on discount : Yes
 * {if product_special} This is a special product {product_special end if} // OUTPUT : This is a special product
 * NO : // OUTPUT : Display blank
 */
$template_desc = $producthelper->getSpecialProductComment($this->data, $template_desc);

$manufacturerLink = "<a href='" . JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $this->data->manufacturer_id . '&Itemid=' . $Itemid) . "'>" . JText::_("COM_REDSHOP_VIEW_MANUFACTURER") . "</a>";
$manufacturerPLink = "<a href='" . JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $this->data->manufacturer_id . '&Itemid=' . $Itemid) . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . " " . $this->data->manufacturer_name . "</a>";
$template_desc = str_replace("{manufacturer_link}", $manufacturerLink, $template_desc);
$template_desc = str_replace("{manufacturer_product_link}", $manufacturerPLink, $template_desc);
$template_desc = str_replace("{manufacturer_name}", $this->data->manufacturer_name, $template_desc);

$template_desc = str_replace("{supplier_name}", "", $template_desc);

if (strstr($template_desc, "{product_delivery_time}"))
{
	$product_delivery_time = $producthelper->getProductMinDeliveryTime($this->data->product_id);

	if ($product_delivery_time != "")
	{
		$template_desc = str_replace("{delivery_time_lbl}", JText::_('COM_REDSHOP_DELIVERY_TIME'), $template_desc);
		$template_desc = str_replace("{product_delivery_time}", $product_delivery_time, $template_desc);
	}
	else
	{
		$template_desc = str_replace("{delivery_time_lbl}", "", $template_desc);
		$template_desc = str_replace("{product_delivery_time}", "", $template_desc);
	}
}

// Facebook I like Button
if (strstr($template_desc, "{facebook_like_button}"))
{
	$uri           = JFactory::getURI();
	$facebook_link = urlencode($uri->toString());
	$facebook_like = '<iframe src="' . $Scheme . '://www.facebook.com/plugins/like.php?href=' . $facebook_link . '&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>';
	$template_desc = str_replace("{facebook_like_button}", $facebook_like, $template_desc);

	$jconfig  = JFactory::getConfig();
	$sitename = $jconfig->getValue('config.sitename');

	$document->setMetaData("og:url", $uri->toString());
	$document->setMetaData("og:type", "product");
	$document->setMetaData("og:site_name", $sitename);
}

// Google I like Button
if (strstr($template_desc, "{googleplus1}"))
{
	JHTML::Script('plusone.js', 'https://apis.google.com/js/', false);
	$uri           = JFactory::getURI();
	$google_like   = '<g:plusone></g:plusone>';
	$template_desc = str_replace("{googleplus1}", $google_like, $template_desc);
}

if (strstr($template_desc, "{bookmark}"))
{
	$bookmark = '<script type="text/javascript">addthis_pub = "AddThis";</script>';
	$bookmark .= '<a href="' . $Scheme . '://www.addthis.com/bookmark.php" onmouseover="return addthis_open(this, \'\', \'[URL]\', \'[TITLE]\')" onmouseout="addthis_close()" onclick="return addthis_sendto()">';
	$bookmark .= '<img src="' . $Scheme . '://s7.addthis.com/static/btn/lg-share-en.gif" alt="Share" border="0" height="16" width="125"></a>';
	$bookmark .= '<script type="text/javascript" src="' . $Scheme . '://s7.addthis.com/js/200/addthis_widget.js"></script>';
	$template_desc = str_replace("{bookmark}", $bookmark, $template_desc);
}

//  Extra field display
$extraFieldName = $extraField->getSectionFieldNameArray(1, 1, 1);
$template_desc = $producthelper->getExtraSectionTag($extraFieldName, $this->data->product_id, "1", $template_desc);

// Product thumb image
if (strstr($template_desc, "{product_thumb_image_3}"))
{
	$pimg_tag = '{product_thumb_image_3}';
	$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT_3;
	$pw_thumb = PRODUCT_MAIN_IMAGE_3;
}
elseif (strstr($template_desc, "{product_thumb_image_2}"))
{
	$pimg_tag = '{product_thumb_image_2}';
	$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT_2;
	$pw_thumb = PRODUCT_MAIN_IMAGE_2;
}
elseif (strstr($template_desc, "{product_thumb_image_1}"))
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

// More images
if (strstr($template_desc, "{more_images_3}"))
{
	$mpimg_tag = '{more_images_3}';
	$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3;
	$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_3;
}
elseif (strstr($template_desc, "{more_images_2}"))
{
	$mpimg_tag = '{more_images_2}';
	$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2;
	$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_2;
}
elseif (strstr($template_desc, "{more_images_1}"))
{
	$mpimg_tag = '{more_images_1}';
	$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
	$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
}
else
{
	$mpimg_tag = '{more_images}';
	$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
	$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
}

// PRODUCT WRAPPER START
$wrapper = $producthelper->getWrapper($this->data->product_id, 0, 1);
$wrappertemplate = $redTemplate->getTemplate("wrapper_template");

if (strstr($template_desc, "{wrapper_template:"))
{
	for ($w = 0; $w < count($wrappertemplate); $w++)
	{
		if (strstr($template_desc, "{wrapper_template:" . $wrappertemplate [$w]->template_name . "}"))
		{
			$wrappertemplate_data = $wrappertemplate [$w]->template_desc;
			$wrapper_start        = explode("{product_wrapper_start}", $wrappertemplate_data);

			if (isset ($wrapper_start [1]))
			{
				$wrapper_start        = explode("{product_wrapper_end}", $wrapper_start [1]);
				$wrappertemplate_data = $wrapper_start [0];
			}

			$wrappertemplate_data .= "<input type='hidden' name='wrapper_price' id='wrapper_price' value='0' />";
			$wrappertemplate_data .= "<input type='hidden' name='wrapper_price_withoutvat' id='wrapper_price_withoutvat' value='0' />";
			$warray                   = array();
			$warray [0]->wrapper_id   = 0;
			$warray [0]->wrapper_name = JText::_('COM_REDSHOP_SELECT_WRAPPER');
			$wrapperimage_div         = "";

			if (AUTO_SCROLL_WRAPPER)
			{
				$wrapperimage_div .= "<marquee behavior='scroll' direction='left' onmouseover='this.stop()' onmouseout='this.start()' scrolldelay='200' width='200'>";
			}

			$wrapperimage_div .= "<table cellpadding='5' cellspacing='5'><tr>";

			for ($i = 0; $i < count($wrapper); $i++)
			{
				$wrapper_vat = 0;

				if ($wrapper[$i]->wrapper_price > 0 && !strstr($template_desc, "{without_vat}"))
				{
					$wrapper_vat = $producthelper->getProducttax($this->data->product_id, $wrapper[$i]->wrapper_price);
				}

				$wp            = $wrapper [$i]->wrapper_price + $wrapper_vat;
				$wp_withoutvat = $wrapper [$i]->wrapper_price;

				$wid   = $wrapper [$i]->wrapper_id;
				$title = " title='" . $wrapper [$i]->wrapper_name . "' ";
				$alt   = " alt='" . $wrapper [$i]->wrapper_name . "' ";

				if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
				{
					$wrapper [$i]->wrapper_name = $wrapper [$i]->wrapper_name . " (" . $producthelper->getProductFormattedPrice($wp) . ")";
				}
				else
				{
					$wrapper [$i]->wrapper_name = $wrapper [$i]->wrapper_name;
				}

				$wrapperimage_div .= "<td id='wrappertd" . $wid . "'>";

				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "wrapper/" . $wrapper [$i]->wrapper_image))
				{
					$wrapperimage_div .= "
					<a onclick='setWrapper($wid,$wp,$wp_withoutvat,\"" . $this->data->product_id . "\");'>
					<img src='" . $url . "/components/com_redshop/helpers/thumb.php?filename=wrapper/" . $wrapper [$i]->wrapper_image . "&newxsize=" . DEFAULT_WRAPPER_THUMB_WIDTH . "&newysize=" . DEFAULT_WRAPPER_THUMB_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "' " . $title . $alt . " /></a>";
				}

				if (strstr($wrappertemplate_data, "{wrapper_price}"))
				{
					$wrapperimage_div .= "<br/><div onclick='setWrapper($wid,$wp,$wp_withoutvat,\"" . $this->data->product_id . "\");' align='center'>";

					if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
					{
						$wrapperimage_div .= $producthelper->getProductFormattedPrice($wp);
					}

					$wrapperimage_div .= "</div>";
				}

				$wrapperimage_div .= "</td>";
				$wrappertemplate_data .= "<input type='hidden' name='w_price' id='w_price" . $wid . "' value='" . $wp . "' />";
				$wrappertemplate_data .= "<input type='hidden' name='w_price_withoutvat' id='w_price_withoutvat" . $wid . "' value='" . $wp_withoutvat . "' />";

				if (!AUTO_SCROLL_WRAPPER)
				{
					if (($i + 1) % 3 == 0)
					{
						$wrapperimage_div .= "</tr><tr>";
					}
				}
			}

			$wrapperimage_div .= "</tr></table>";

			if (AUTO_SCROLL_WRAPPER)
			{
				$wrapperimage_div .= "</marquee>";
			}

			if (count($wrapper) > 0)
			{
				$wrapper              = array_merge($warray, $wrapper);
				$lists ['wrapper_id'] = JHTML::_('select.genericlist', $wrapper, 'wrapper_id', 'class="inputbox" onchange="calculateTotalPrice(\'' . $this->data->product_id . '\',0);" ', 'wrapper_id', 'wrapper_name', 0);

				$wrappertemplate_data = str_replace("{wrapper_dropdown}", $lists ['wrapper_id'], $wrappertemplate_data);
				$wrappertemplate_data = str_replace("{wrapper_image}", $wrapperimage_div, $wrappertemplate_data);
				$wrappertemplate_data = str_replace("{wrapper_price}", "", $wrappertemplate_data);
				$wrapper_checkbox     = JText::_('COM_REDSHOP_Add_WRAPPER') . ": <input type='checkbox' name='wrapper_check' onclick='calculateTotalPrice(\"" . $this->data->product_id . "\",0);' id='wrapper_check' />";
				$wrappertemplate_data = str_replace("{wrapper_add_checkbox}", $wrapper_checkbox, $wrappertemplate_data);
				$template_desc        = str_replace("{wrapper_template:" . $wrappertemplate [$w]->template_name . "}", $wrappertemplate_data, $template_desc);
			}
			else
			{
				$template_desc = str_replace("{wrapper_template:" . $wrappertemplate [$w]->template_name . "}", "", $template_desc);
			}
		}
	}
}

if (strstr($template_desc, "{navigator_products}"))
{
	$parentproductid = $this->data->product_id;

	$frmChild               = "";
	$navigator_products_lbl = "";

	if ($parentproductid != 0 && JPluginHelper::isEnabled('redshop_product_navigation', 'rs_product_navigation'))
	{
		$productInfo = $producthelper->getProductById($parentproductid);

		// Get child products
		$childproducts = $producthelper->getProductNavigator(0, $parentproductid);

		if (count($childproducts) > 0)
		{
			$navigator_products_lbl = JText::_('COM_REDSHOP_NAVIGATOR_PRODUCTS') . ": ";
			$cld_name               = array();

			if (count($childproducts) > 0)
			{
				for ($c = 0; $c < count($childproducts); $c++)
				{
					$childproducts[$c]->product_name = $childproducts[$c]->navigator_name;
				}

				$cld_name = @array_merge($cld_name, $childproducts);
			}

			$selected = array($this->data->product_id);

			$lists['product_child_id'] = JHTML::_('select.genericlist', $cld_name, 'pid', 'class="inputbox" size="1"  onchange="document.frmNav.submit();"', 'child_product_id', 'product_name', $selected);

			$frmChild .= "<form name='frmNav' id='frmNav' method='post' action=''>";
			$frmChild .= "<div class='product_child_product_list'>" . $lists ['product_child_id'] . "</div>";
			$frmChild .= "<input type='hidden' name='view' value='product'>";
			$frmChild .= "<input type='hidden' name='task' value='gotonavproduct'>";
			$frmChild .= "<input type='hidden' name='option' value='com_redshop'>";
			$frmChild .= "<input type='hidden' name='Itemid' value='" . $Itemid . "'>";
			$frmChild .= "</form>";
		}
	}

	$template_desc = str_replace("{navigator_products}", $frmChild, $template_desc);
	$template_desc = str_replace("{navigator_products_lbl}", $navigator_products_lbl, $template_desc);
}

// PRODUCT WRAPPER END

if (strstr($template_desc, "{child_products}"))
{
	$parentproductid = $this->data->product_id;

	if ($this->data->product_parent_id != 0)
	{
		$parentproductid = $producthelper->getMainParentProduct($this->data->product_id);
	}

	$frmChild = "";

	if ($parentproductid != 0)
	{
		$productInfo = $producthelper->getProductById($parentproductid);

		// Get child products
		$childproducts = $model->getAllChildProductArrayList(0, $parentproductid);

		if (count($childproducts) > 0)
		{
			$childproducts = array_merge(array($productInfo), $childproducts);

			$cld_name = array();

			if (count($childproducts) > 0)
			{
				$parentid = 0;

				for ($c = 0; $c < count($childproducts); $c++)
				{
					if ($childproducts[$c]->product_parent_id == 0)
					{
						$level = "";
					}
					else
					{
						if ($parentid != $childproducts[$c]->product_parent_id)
						{
							$level = $level; //."_";
						}
					}

					$parentid = $childproducts[$c]->product_parent_id;

					$childproducts[$c]->product_name = $level . $childproducts[$c]->product_name;
				}

				$cld_name = @array_merge($cld_name, $childproducts);
			}

			$display_text = (CHILDPRODUCT_DROPDOWN == "product_number") ? "product_number" : "product_name";

			$selected                  = array($this->data->product_id);
			$lists['product_child_id'] = JHTML::_('select.genericlist', $cld_name, 'pid', 'class="inputbox" size="1"  onchange="document.frmChild.submit();"', 'product_id', $display_text, $selected);

			$frmChild .= "<form name='frmChild' method='post' action=''>";
			$frmChild .= "<div class='product_child_product'>" . JText::_('COM_REDSHOP_CHILD_PRODUCTS') . "</div><div class='product_child_product_list'>" . $lists ['product_child_id'] . "</div>";
			$frmChild .= "<input type='hidden' name='view' value='product'>";
			$frmChild .= "<input type='hidden' name='task' value='gotochild'>";
			$frmChild .= "<input type='hidden' name='option' value='com_redshop'>";
			$frmChild .= "<input type='hidden' name='Itemid' value='" . $Itemid . "'>";
			$frmChild .= "</form>";
		}
	}

	$template_desc = str_replace("{child_products}", $frmChild, $template_desc);
}

// Checking for child products
$childproduct = $producthelper->getChildProduct($this->data->product_id);

if (count($childproduct) > 0)
{
	if (PURCHASE_PARENT_WITH_CHILD == 1)
	{
		$isChilds       = false;
		$attributes_set = array();

		if ($this->data->attribute_set_id > 0)
		{
			$attributes_set = $producthelper->getProductAttribute(0, $this->data->attribute_set_id, 0, 1);
		}

		$attributes = $producthelper->getProductAttribute($this->data->product_id);
		$attributes = array_merge($attributes, $attributes_set);
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
	$attributes_set = array();

	if ($this->data->attribute_set_id > 0)
	{
		$attributes_set = $producthelper->getProductAttribute(0, $this->data->attribute_set_id, 0, 1);
	}

	$attributes = $producthelper->getProductAttribute($this->data->product_id);
	$attributes = array_merge($attributes, $attributes_set);
}

$attribute_template = $producthelper->getAttributeTemplate($template_desc);

// Check product for not for sale
$template_desc = $producthelper->getProductNotForSaleComment($this->data, $template_desc, $attributes);

// Replace product in stock tags
$template_desc = $producthelper->replaceProductInStock($this->data->product_id, $template_desc, $attributes, $attribute_template);

// Product attribute  Start
$totalatt = count($attributes);
$template_desc = $producthelper->replaceAttributeData($this->data->product_id, 0, 0, $attributes, $template_desc, $attribute_template, $isChilds);

// Product attribute  End

$pr_number                   = $this->data->product_number;
$preselectedresult           = array();
$moreimage_response          = '';
$attributeproductStockStatus = null;
$selectedpropertyId          = 0;
$selectedsubpropertyId       = 0;

if (count($attributes) > 0 && count($attribute_template) > 0)
{
	for ($a = 0; $a < count($attributes); $a++)
	{
		$selectedId = array();
		$property   = $producthelper->getAttibuteProperty(0, $attributes[$a]->attribute_id);

		if ($attributes[$a]->text != "" && count($property) > 0)
		{
			for ($i = 0; $i < count($property); $i++)
			{
				if ($property[$i]->setdefault_selected)
				{
					$selectedId[] = $property[$i]->property_id;
				}
			}

			if (count($selectedId) > 0)
			{
				$selectedpropertyId = $selectedId[count($selectedId) - 1];
				$subproperty        = $producthelper->getAttibuteSubProperty(0, $selectedpropertyId);
				$selectedId         = array();

				for ($sp = 0; $sp < count($subproperty); $sp++)
				{
					if ($subproperty[$sp]->setdefault_selected)
					{
						$selectedId[] = $subproperty[$sp]->subattribute_color_id;
					}
				}

				if (count($selectedId) > 0)
				{
					$selectedsubpropertyId = $selectedId[count($selectedId) - 1];
				}
			}
		}
	}

	$preselectedresult = $producthelper->displayAdditionalImage($this->data->product_id, 0, 0, $selectedpropertyId, $selectedsubpropertyId, $pw_thumb, $ph_thumb, $redview = 'product');

	if (strstr($template_desc, "{product_availability_date}") || strstr($template_desc, "{stock_notify_flag}") || strstr($template_desc, "{stock_status"))
	{
		$attributeproductStockStatus = $producthelper->getproductStockStatus($this->data->product_id, $totalatt, $selectedpropertyId, $selectedsubpropertyId);
	}

	$moreimage_response  = $preselectedresult['response'];
	$aHrefImageResponse  = $preselectedresult['aHrefImageResponse'];
	$aTitleImageResponse = $preselectedresult['aTitleImageResponse'];

	$mainImageResponse = $preselectedresult['product_mainimg'];

	$attrbimg = $preselectedresult['attrbimg'];

	if (!is_null($preselectedresult['pr_number']) && !empty($preselectedresult['pr_number']))
	{
		$pr_number = $preselectedresult['pr_number'];
	}
}
else
{
	if (strstr($template_desc, "{product_availability_date}") || strstr($template_desc, "{stock_notify_flag}") || strstr($template_desc, "{stock_status"))
	{
		$attributeproductStockStatus = $producthelper->getproductStockStatus($this->data->product_id, $totalatt);
	}
}

$template_desc = $producthelper->replaceProductStockdata($this->data->product_id, $selectedpropertyId, $selectedsubpropertyId, $template_desc, $attributeproductStockStatus);

$product_number_output = '<span id="product_number_variable' . $this->data->product_id . '">' . $pr_number . '</span>';
$template_desc = str_replace("{product_number}", $product_number_output, $template_desc);

// Product accessory Start
$accessory = $producthelper->getProductAccessory(0, $this->data->product_id);
$totalAccessory = count($accessory);

$template_desc = $producthelper->replaceAccessoryData($this->data->product_id, 0, $accessory, $template_desc, $isChilds);

// Product accessory End

if (strstr($template_desc, $mpimg_tag))
{
	if ($moreimage_response != "")
	{
		$more_images = $moreimage_response;
	}
	else
	{
		$media_image = $producthelper->getAdditionMediaImage($this->data->product_id, "product");
		$more_images = '';

		for ($m = 0; $m < count($media_image); $m++)
		{
			$filename1 = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $media_image[$m]->media_name;

			if ($media_image[$m]->media_name != $media_image[$m]->product_full_image && file_exists($filename1))
			{
				$alttext = $producthelper->getAltText('product', $media_image[$m]->section_id, '', $media_image[$m]->media_id);

				if (!$alttext)
				{
					$alttext = $media_image [$m]->media_name;
				}

				if ($media_image [$m]->media_name)
				{
					$thumb = $media_image [$m]->media_name;

					if (WATERMARK_PRODUCT_ADDITIONAL_IMAGE)
					{
						$pimg          = $redhelper->watermark('product', $thumb, $mpw_thumb, $mph_thumb, WATERMARK_PRODUCT_ADDITIONAL_IMAGE, "1");
						$linkimage     = $redhelper->watermark('product', $thumb, '', '', WATERMARK_PRODUCT_ADDITIONAL_IMAGE, "0");
						$hoverimg_path = $redhelper->watermark('product', $thumb, ADDITIONAL_HOVER_IMAGE_WIDTH, ADDITIONAL_HOVER_IMAGE_HEIGHT, WATERMARK_PRODUCT_ADDITIONAL_IMAGE, '2');
					}
					else
					{
						$pimg          = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $thumb . "&newxsize=" . $mpw_thumb . "&newysize=" . $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
						$linkimage     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $thumb;
						$hoverimg_path = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $thumb . "&newxsize=" . ADDITIONAL_HOVER_IMAGE_WIDTH . "&newysize=" . ADDITIONAL_HOVER_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
					}

					if (PRODUCT_ADDIMG_IS_LIGHTBOX)
					{
						$more_images_div_start = "<div class='additional_image'><a href='" . $linkimage . "' title='" . $alttext . "' rel=\"myallimg\">";
						$more_images_div_end   = "</a></div>";
						$more_images .= $more_images_div_start;
						$more_images .= "<img src='" . $pimg . "' alt='" . $alttext . "' title='" . $alttext . "'>";
						$more_images_hrefend = "";
					}
					else
					{
						if (WATERMARK_PRODUCT_ADDITIONAL_IMAGE)
						{
							$img_path = $redhelper->watermark('product', $thumb, $pw_thumb, $ph_thumb, WATERMARK_PRODUCT_ADDITIONAL_IMAGE, '0');
						}
						else
						{
							$img_path = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $thumb . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
						}

						$hovermore_images = $redhelper->watermark('product', $thumb, '', '', WATERMARK_PRODUCT_ADDITIONAL_IMAGE, '0');

						$filename_org = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $media_image[$m]->product_full_image;

						if (file_exists($filename_org))
						{
							$thumb_original = $media_image[$m]->product_full_image;
						}
						else
						{
							$thumb_original = PRODUCT_DEFAULT_IMAGE;
						}

						if (WATERMARK_PRODUCT_THUMB_IMAGE)
							$img_path_org = $redhelper->watermark('product', $thumb_original, $pw_thumb, $ph_thumb, WATERMARK_PRODUCT_THUMB_IMAGE, '0');
						else
							$img_path_org = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $thumb_original . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
						$hovermore_org         = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $thumb_original . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
						$oimg_path             = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $thumb . "&newxsize=" . $mpw_thumb . "&newysize=" . $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
						$more_images_div_start = "<div class='additional_image' onmouseover='display_image(\"" . $img_path . "\"," . $this->data->product_id . ",\"" . $hovermore_images . "\");' onmouseout='display_image_out(\"" . $img_path_org . "\"," . $this->data->product_id . ",\"" . $img_path_org . "\");'>";
						$more_images_div_end   = "</div>";
						$more_images .= $more_images_div_start;
						$more_images .= '<a href="javascript:void(0)" >' . "<img src='" . $pimg . "' title='" . $alttext . "' style='cursor: auto;'>";
						$more_images_hrefend = "</a>";
					}

					if (ADDITIONAL_HOVER_IMAGE_ENABLE)
					{
						$more_images .= "<img src='" . $hoverimg_path . "' alt='" . $alttext . "' title='" . $alttext . "' class='redImagepreview'>";
					}

					$more_images .= $more_images_hrefend;
					$more_images .= $more_images_div_end;
				}
			}
		}
	}

	$template_desc = str_replace($mpimg_tag, "<span class='redhoverImagebox' id='additional_images" . $this->data->product_id . "'>" . $more_images . "</span>", $template_desc);
}

// More images end

// More documents
if (strstr($template_desc, "{more_documents}"))
{
	$media_documents = $producthelper->getAdditionMediaImage($this->data->product_id, "product", "document");
	$more_doc        = '';

	for ($m = 0; $m < count($media_documents); $m++)
	{
		$alttext = $producthelper->getAltText("product", $media_documents[$m]->section_id, "", $media_documents[$m]->media_id, "document");

		if (!$alttext)
		{
			$alttext = $media_documents[$m]->media_name;
		}

		if (is_file(REDSHOP_FRONT_DOCUMENT_RELPATH . "product/" . $media_documents[$m]->media_name))
		{
			$downlink = JUri::root() . 'index.php?tmpl=component&option=com_redshop&view=product&pid=' . $this->data->product_id . '&task=downloadDocument&fname=' . $media_documents[$m]->media_name . '&Itemid=' . $Itemid;
			$more_doc .= "<div><a href='" . $downlink . "' title='" . $alttext . "'>";
			$more_doc .= $alttext;
			$more_doc .= "</a></div>";
		}
	}

	$template_desc = str_replace("{more_documents}", "<span id='additional_docs" . $this->data->product_id . "'>" . $more_doc . "</span>", $template_desc);
}

// More documents end

$hidden_thumb_image = "<input type='hidden' name='prd_main_imgwidth' id='prd_main_imgwidth' value='" . $pw_thumb . "'><input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='" . $ph_thumb . "'>";
$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $this->data->product_id);

if (count($preselectedresult) > 0)
{
	$thum_image = "<div class='productImageWrap' id='productImageWrapID_" . $this->data->product_id . "'>" . $producthelper->replaceProductImage($this->data, "", "", "", $pw_thumb, $ph_thumb, PRODUCT_DETAIL_IS_LIGHTBOX, 0, $preselectedresult) . "</div>";
}
else
{
	// Product image flying addwishlist time start
	$thum_image = "<div class='productImageWrap' id='productImageWrapID_" . $this->data->product_id . "'>" . $producthelper->getProductImage($this->data->product_id, $link, $pw_thumb, $ph_thumb, PRODUCT_DETAIL_IS_LIGHTBOX) . "</div>";
}

// Product image flying addwishlist time end
$template_desc = str_replace($pimg_tag, $thum_image . $hidden_thumb_image, $template_desc);

$template_desc = $producthelper->getJcommentEditor($this->data, $template_desc);

// ProductFinderDatepicker Extra Field Start

$fieldArray = $extraField->getSectionFieldList(17, 0, 0);
$template_desc = $producthelper->getProductFinderDatepickerValue($template_desc, $this->data->product_id, $fieldArray);

// ProductFinderDatepicker Extra Field End

// Product User Field Start
$count_no_user_field = 0;
$returnArr = $producthelper->getProductUserfieldFromTemplate($template_desc);
$template_userfield = $returnArr[0];
$userfieldArr = $returnArr[1];

if (strstr($template_desc, "{if product_userfield}") && strstr($template_desc, "{product_userfield end if}") && $template_userfield != "")
{
	$ufield = "";
	$cart   = $session->get('cart');

	if (isset($cart['idx']))
	{
		$idx = (int) ($cart['idx']);
	}

	$idx     = 0;
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

		$template_desc = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $product_userfileds[0], $template_desc);
		$template_desc = str_replace('{' . $userfieldArr[$ui] . '}', $product_userfileds[1], $template_desc);
	}

	$product_userfileds_form = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

	if ($ufield != "")
	{
		$template_desc = str_replace("{if product_userfield}", $product_userfileds_form, $template_desc);
		$template_desc = str_replace("{product_userfield end if}", "</form>", $template_desc);
	}
	else
	{
		$template_desc = str_replace("{if product_userfield}", "", $template_desc);
		$template_desc = str_replace("{product_userfield end if}", "", $template_desc);
	}
}

// Product User Field End

// Category front-back image tag...
if (strstr($template_desc, "{category_product_img}"))
{
	$mainsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=category/" . $this->data->category_full_image . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
	$backsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=category/" . $this->data->category_back_full_image . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;

	$ahrefpath     = REDSHOP_FRONT_IMAGES_ABSPATH . "category/" . $this->data->category_full_image;
	$ahrefbackpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->category_back_full_image;

	$product_front_image_link = "<a href='#' onClick='javascript:changeproductImage(" . $this->data->product_id . ",\"" . $mainsrcPath . "\",\"" . $ahrefpath . "\");'>" . JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
	$product_back_image_link  = "<a href='#' onClick='javascript:changeproductImage(" . $this->data->product_id . ",\"" . $backsrcPath . "\",\"" . $ahrefbackpath . "\");'>" . JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

	$template_desc = str_replace("{category_front_img_link}", $product_front_image_link, $template_desc);
	$template_desc = str_replace("{category_back_img_link}", $product_back_image_link, $template_desc);

	// Display category front image
	$thum_catimage = $producthelper->getProductCategoryImage($this->data->product_id, $this->data->category_full_image, '', $pw_thumb, $ph_thumb, PRODUCT_DETAIL_IS_LIGHTBOX);
	$template_desc = str_replace("{category_product_img}", $thum_catimage, $template_desc);

	// Category front-back image tag end
}
else
{
	$template_desc = str_replace("{category_front_img_link}", "", $template_desc);
	$template_desc = str_replace("{category_back_img_link}", "", $template_desc);
	$template_desc = str_replace("{category_product_img}", "", $template_desc);
}

if (strstr($template_desc, "{front_img_link}") || strstr($template_desc, "{back_img_link}"))
{
	// Front-back image tag...
	if ($this->data->product_thumb_image)
	{
		$mainsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_thumb_image;
	}
	else
	{
		$mainsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $this->data->product_full_image . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
	}

	if ($this->data->product_back_thumb_image)
	{
		$backsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_back_thumb_image;
	}
	else
	{
		$backsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $this->data->product_back_full_image . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
	}

	$ahrefpath     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_full_image;
	$ahrefbackpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_back_full_image;

	$product_front_image_link = "<a href='#' onClick='javascript:changeproductImage(" . $this->data->product_id . ",\"" . $mainsrcPath . "\",\"" . $ahrefpath . "\");'>" . JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
	$product_back_image_link  = "<a href='#' onClick='javascript:changeproductImage(" . $this->data->product_id . ",\"" . $backsrcPath . "\",\"" . $ahrefbackpath . "\");'>" . JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

	$template_desc = str_replace("{front_img_link}", $product_front_image_link, $template_desc);
	$template_desc = str_replace("{back_img_link}", $product_back_image_link, $template_desc);
}
else
{
	$template_desc = str_replace("{front_img_link}", "", $template_desc);
	$template_desc = str_replace("{back_img_link}", "", $template_desc);
}

// Front-back image tag end

// Product preview image.
if (strstr($template_desc, "{product_preview_img}"))
{
	if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $this->data->product_preview_image))
	{
		$previewsrcPath = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $this->data->product_preview_image . "&newxsize=" . PRODUCT_PREVIEW_IMAGE_WIDTH . "&newysize=" . PRODUCT_PREVIEW_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;

		$previewImg    = "<img src='" . $previewsrcPath . "' class='rs_previewImg' />";
		$template_desc = str_replace("{product_preview_img}", $previewImg, $template_desc);
	}
	else
	{
		$template_desc = str_replace("{product_preview_img}", "", $template_desc);
	}
}

// Product preview image end.

// Front-back preview image tag...
if (strstr($template_desc, "{front_preview_img_link}") || strstr($template_desc, "{back_preview_img_link}"))
{
	if ($this->data->product_preview_image)
	{
		$mainpreviewsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_preview_image . "&newxsize=" . PRODUCT_PREVIEW_IMAGE_WIDTH . "&newysize=" . PRODUCT_PREVIEW_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
	}

	if ($this->data->product_preview_back_image)
	{
		$backpreviewsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_preview_back_image . "&newxsize=" . PRODUCT_PREVIEW_IMAGE_WIDTH . "&newysize=" . PRODUCT_PREVIEW_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
	}

	$product_front_image_link = "<a href='#' onClick='javascript:changeproductPreviewImage(" . $this->data->product_id . ",\"" . $mainpreviewsrcPath . "\");'>" . JText::_('COM_REDSHOP_FRONT_IMAGE') . "</a>";
	$product_back_image_link  = "<a href='#' onClick='javascript:changeproductPreviewImage(" . $this->data->product_id . ",\"" . $backpreviewsrcPath . "\");'>" . JText::_('COM_REDSHOP_BACK_IMAGE') . "</a>";

	$template_desc = str_replace("{front_preview_img_link}", $product_front_image_link, $template_desc);
	$template_desc = str_replace("{back_preview_img_link}", $product_back_image_link, $template_desc);
}
else
{
	$template_desc = str_replace("{front_preview_img_link}", "", $template_desc);
	$template_desc = str_replace("{back_preview_img_link}", "", $template_desc);
}

// Front-back preview image tag end

// Cart
$template_desc = $producthelper->replaceCartTemplate($this->data->product_id, $this->data->category_id, 0, 0, $template_desc, $isChilds, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field);

$template_desc = str_replace("{ajaxwishlist_icon}", '', $template_desc);

// Replace wishlistbutton
$template_desc = $producthelper->replaceWishlistButton($this->data->product_id, $template_desc);

// Replace compare product button
$template_desc = $producthelper->replaceCompareProductsButton($this->data->product_id, $this->data->category_id, $template_desc);

// Ajax detail box template
$ajaxdetail_templatedata = $producthelper->getAjaxDetailboxTemplate($this->data);

if (count($ajaxdetail_templatedata) > 0)
{
	$template_desc = str_replace("{ajaxdetail_template:" . $ajaxdetail_templatedata->template_name . "}", "", $template_desc);
}

// Checking if user logged in then only enabling review button
$reviewform = "";

if ($user->id)
{
	// Write Review link with the products
	if (strstr($template_desc, "{form_rating_without_lightbox}"))
	{
		$reviewlink    = JURI::root() . 'index.php?option=com_redshop&view=product_rating&rate=1&product_id=' . $this->data->product_id . '&category_id=' . $this->data->category_id . '&Itemid=' . $Itemid;
		$reviewform    = '<a href="' . $reviewlink . '">' . JText::_('WRITE_REVIEW') . '</a>';
		$template_desc = str_replace("{form_rating_without_lightbox}", $reviewform, $template_desc);
	}

	if (strstr($template_desc, "{form_rating}"))
	{
		$reviewlink    = "";
		$reviewform    = "";
		$reviewlink    = JURI::root() . 'index.php?option=com_redshop&view=product_rating&tmpl=component&product_id=' . $this->data->product_id . '&category_id=' . $this->data->category_id . '&Itemid=' . $Itemid;
		$reviewform    = '<a class="redbox" rel="{handler:\'iframe\',size:{x:620,y:420}}" href="' . $reviewlink . '">' . JText::_('COM_REDSHOP_WRITE_REVIEW') . '</a>';
		$template_desc = str_replace("{form_rating}", $reviewform, $template_desc);
	}
}
else
{
	$reviewform = JText::_('COM_REDSHOP_YOU_NEED_TO_LOGIN_TO_POST_A_REVIEW');

	if (strstr($template_desc, "{form_rating_without_lightbox}"))
	{
		$template_desc = str_replace("{form_rating_without_lightbox}", $reviewform, $template_desc);
	}

	if (strstr($template_desc, "{form_rating}"))
	{
		$template_desc = str_replace("{form_rating}", $reviewform, $template_desc);
	}
}

if (strstr($template_desc, "{form_rating_without_link}"))
{
	$session = JFactory::getSession();

	$a = intval(mt_rand(1, 100) / 10);
	$b = intval(mt_rand(1, 100) / 10);

	$hash = uniqid();

	$session->set('session.simplemath' . $hash, $a + $b);

	$reviewform = "<form name='writereview' action='' method='post' onSubmit=\"return validateReview();\">";
	$reviewform .= "<div style='clear:both;'>";
	$reviewform .= "<table cellpadding='3' cellspacing=\"3\" border=\"0\" width=\"100%\">";
	$reviewform .= "<tr>";
	$reviewform .= "<td colspan=\"2\">" . JText::_('COM_REDSHOP_WRITE_REVIEWFORM_HEADER_TEXT') . "</td>";
	$reviewform .= "</tr>";
	$reviewform .= "<tr>";
	$reviewform .= "<td valign=\"top\" align=\"left\" width=\"100\">";
	$reviewform .= "<label for=\"rating\">";
	$reviewform .= JText::_('COM_REDSHOP_RATING');
	$reviewform .= "</label>";
	$reviewform .= "</td>";
	$reviewform .= "<td>";
	$reviewform .= "<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">";
	$reviewform .= "<tr>";
	$reviewform .= "<td>" . JText::_('COM_REDSHOP_GOOD') . "</td>";
	$reviewform .= "<td align=\"center\">";
	$reviewform .= "<input type=\"radio\" name=\"user_rating\" id=\"user_rating0\" value=\"0\">";
	$reviewform .= "</td>";
	$reviewform .= "<td align=\"center\">";
	$reviewform .= "<input type=\"radio\" name=\"user_rating\" id=\"user_rating1\" value=\"1\">";
	$reviewform .= "</td>";
	$reviewform .= "<td align=\"center\">";
	$reviewform .= "<input type=\"radio\" name=\"user_rating\" id=\"user_rating2\" value=\"2\">";
	$reviewform .= "</td>";
	$reviewform .= "<td align=\"center\">";
	$reviewform .= "<input type=\"radio\" name=\"user_rating\" id=\"user_rating3\" value=\"3\">";
	$reviewform .= "</td>";
	$reviewform .= "<td align=\"center\">";
	$reviewform .= "<input type=\"radio\" name=\"user_rating\" id=\"user_rating4\" value=\"4\">";
	$reviewform .= "</td>";
	$reviewform .= "<td align=\"center\">";
	$reviewform .= "<input type=\"radio\" name=\"user_rating\" id=\"user_rating5\" value=\"5\">";
	$reviewform .= "</td>";
	$reviewform .= "<td>" . JText::_('COM_REDSHOP_EXCELLENT') . "</td>";
	$reviewform .= "</tr>";
	$reviewform .= "</table>";
	$reviewform .= "</td>";
	$reviewform .= "</tr>";
	$reviewform .= "<tr>";
	$reviewform .= "<td valign=\"top\" align=\"left\">";
	$reviewform .= "<label for=\"username\">";
	$reviewform .= JText::_('COM_REDSHOP_USER_FULLNAME');
	$reviewform .= "</label>";
	$reviewform .= "</td>";
	$reviewform .= "<td colspan=\"8\">";
	$reviewform .= "<input type=\"text\" class=\"inputbox\" name=\"username\" id=\"username\" value=\"\" >";
	$reviewform .= "</td>";
	$reviewform .= "</tr>";

	$reviewform .= "<tr>";
	$reviewform .= "<td valign=\"top\" align=\"left\">";
	$reviewform .= "<label for=\"username\">";
	$reviewform .= JText::_('COM_REDSHOP_COMPANY_NAME');
	$reviewform .= "</label>";
	$reviewform .= "</td>";
	$reviewform .= "<td colspan=\"8\">";
	$reviewform .= "<input type=\"text\" class=\"inputbox\" name=\"company_name\" id=\"company_name\" value=\"\" >";
	$reviewform .= "</td>";
	$reviewform .= "</tr>";

	$reviewform .= "<tr>";
	$reviewform .= "<td valign=\"top\" align=\"left\">";
	$reviewform .= "<label for=\"email\">";
	$reviewform .= JText::_('COM_REDSHOP_USER_EMAIL');
	$reviewform .= "</label>";
	$reviewform .= "</td>";
	$reviewform .= "<td colspan=\"8\">";
	$reviewform .= "<input type=\"text\" class=\"inputbox\" name=\"email\" id=\"email\" value=\"\" >";
	$reviewform .= "</td>";
	$reviewform .= "</tr>";

	$reviewform .= "<tr>";
	$reviewform .= "<td valign=\"top\" align=\"left\">";
	$reviewform .= "<label for=\"rating_title\">";
	$reviewform .= JText::_('COM_REDSHOP_RATING_TITLE');
	$reviewform .= "</label>";
	$reviewform .= "</td>";
	$reviewform .= "<td colspan=\"8\">";
	$reviewform .= "<input type=\"text\" class=\"inputbox\" name=\"title\" id=\"title\" value=\"\" size=\"48\">";
	$reviewform .= "</td>";
	$reviewform .= "</tr>";
	$reviewform .= "<tr>";
	$reviewform .= "<td valign=\"top\" align=\"left\">";
	$reviewform .= "<label for=\"comment\">";
	$reviewform .= JText::_('COM_REDSHOP_COMMENT');
	$reviewform .= "</label>";
	$reviewform .= "</td>";
	$reviewform .= "<td colspan=\"8\">";
	$reviewform .= "<textarea class=\"text_area\" name=\"comment\" id=\"comment\" cols=\"60\" rows=\"7\" /></textarea>";
	$reviewform .= "</td>";
	$reviewform .= "</tr>";

	$reviewform .= "<tr>";
	$reviewform .= "<td valign=\"top\" align=\"left\">";
	$reviewform .= "<label for=\"review_captcha\">";
	$reviewform .= JText::_('COM_REDSHOP_REVIEW_CAPTCHA');
	$reviewform .= "</label>";
	$reviewform .= "</td>";
	$reviewform .= "<td colspan=\"8\">";
	$reviewform .= "<input type=\"text\" class=\"inputbox\" name=\"review_captcha\" id=\"review_captcha\" value=\"\" size=\"48\"><input type=\"hidden\" name=\"review_captcha_hash\" value='" . $hash . "' /><br>";
	$reviewform .= JText::sprintf('COM_REDSHOP_REVIEW_CAPTCHA_INFO', $a, $b);
	$reviewform .= "</td>";
	$reviewform .= "</tr>";

	$reviewform .= "<tr>";
	$reviewform .= "<td></td>";
	$reviewform .= "<td colspan=\"8\"><input type=\"submit\" name=\"btnsubmit\" value='" . JText::_('COM_REDSHOP_SEND_REVIEW') . "' ></td>";
	$reviewform .= "</tr>";
	$reviewform .= "<tr>";
	$reviewform .= "<td colspan=\"2\">" . JText::_('COM_REDSHOP_WRITE_REVIEWFORM_FOOTER_TEXT') . "</td>";
	$reviewform .= "</tr>";
	$reviewform .= "</table>";
	$reviewform .= "<input type='hidden' value='" . $this->data->product_id . "' name='product_id'>";
	$reviewform .= "<input type='hidden' value='" . $this->data->category_id . "' name='category_id'>";
	$reviewform .= "<input type='hidden' name='view' value='product'>";
	$reviewform .= "<input type=\"hidden\" name=\"published\" value=\"0\" />";
	$reviewform .= "<input type=\"hidden\" name=\"rate\" value='0' />";
	$reviewform .= "<input type=\"hidden\" name=\"time\" value='" . time() . "' />";

	$reviewform .= "<input type='hidden' name='task' value='writeReview'>";
	$reviewform .= "<input type='hidden' name='Itemid' value='" . $Itemid . "'>";
	$reviewform .= "</div>";
	$reviewform .= "</form>";

	$template_desc = str_replace("{form_rating_without_link}", $reviewform, $template_desc);
}

$template_desc = str_replace("{form_rating}", $reviewform, $template_desc);

// Product Review/Rating
if (strstr($template_desc, "{product_rating_summary}"))
{
	$final_avgreview_data = $producthelper->getProductRating($this->data->product_id);

	if ($final_avgreview_data != "")
	{
		$template_desc = str_replace("{product_rating_summary}", $final_avgreview_data, $template_desc);
	}
	else
	{
		$template_desc = str_replace("{product_rating_summary}", '', $template_desc);
	}
}

if (strstr($template_desc, "{product_rating}"))
{
	if (FAVOURED_REVIEWS != "" || FAVOURED_REVIEWS != 0)
		$mainblock = FAVOURED_REVIEWS;
	else
		$mainblock = 5;

	$main_template = $redTemplate->getTemplate("review");

	if (count($main_template) > 0 && $main_template[0]->template_desc)
	{
		$main_template = $main_template[0]->template_desc;
	}
	else
	{
		$main_template = "<div>{product_loop_start}<p><strong>{product_title}</strong></p><div>{review_loop_start}<div id=\"reviews_wrapper\"><div id=\"reviews_rightside\"><div id=\"reviews_fullname\">{fullname}</div><div id=\"reviews_title\">{title}</div><div id=\"reviews_comment\">{comment}</div></div><div id=\"reviews_leftside\"><div id=\"reviews_stars\">{stars}</div></div></div>{review_loop_end}<div>{product_loop_end}</div></div></div>	";
	}

	// Fetching reviews
	$reviews          = $producthelper->getProductReviewList($this->data->product_id);
	$reviews_template = "";
	$product_template = "";

	if (strstr($main_template, "{product_loop_start}") && strstr($main_template, "{product_loop_end}"))
	{
		$product_start    = explode("{product_loop_start}", $main_template);
		$product_end      = explode("{product_loop_end}", $product_start [1]);
		$product_template = $product_end [0];

		if (strstr($main_template, "{product_loop_start}") && strstr($main_template, "{product_loop_end}"))
		{
			$review_start     = explode("{review_loop_start}", $product_template);
			$review_end       = explode("{review_loop_end}", $review_start [1]);
			$reviews_template = $review_end [0];
		}
	}

	$product_data = '';
	$reviews_all  = '';

	if ($product_template != "" && $reviews_template != "" && count($reviews) > 0)
	{
		$product_data .= str_replace("{product_title}", '', $product_template);

		$reviews_data1 = "";
		$reviews_data2 = "";
		$reviews_data  = "";

		for ($j = 0; $j < $mainblock && $j < count($reviews); $j++)
		{
			$fullname  = $reviews[$j]->username;
			$starimage = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'star_rating/' . $reviews[$j]->user_rating . '.gif">';

			$reviews_data1 = str_replace("{fullname}", $fullname, $reviews_template);
			$reviews_data1 = str_replace("{email}", $reviews[$j]->email, $reviews_data1);
			$reviews_data1 = str_replace("{company_name}", $reviews[$j]->company_name, $reviews_data1);
			$reviews_data1 = str_replace("{title}", $reviews [$j]->title, $reviews_data1);
			$reviews_data1 = str_replace("{comment}", nl2br($reviews [$j]->comment), $reviews_data1);
			$reviews_data1 = str_replace("{stars}", $starimage, $reviews_data1);
			$reviews_data1 = str_replace("{review_date}", $redshopconfig->convertDateFormat($reviews [$j]->time), $reviews_data1);
			$reviews_data .= $reviews_data1;
		}

		if ($mainblock < count($reviews))
		{
			$reviews_data .= '<div style="clear:both;" class="show_reviews">';
			$reviews_data .= '<a href="javascript:showallreviews();">';
			$reviews_data .= '<img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . 'reviewarrow.gif"> ';
			$reviews_data .= JText::_('COM_REDSHOP_SHOW_ALL_REVIEWS') . '</a></div>';
		}

		$reviews_data .= '<div style="display:none;" id="showreviews" name="showreviews">';

		for ($k = $mainblock; $k < count($reviews); $k++)
		{
			$fullname2  = $reviews [$k]->firstname . " " . $reviews [$k]->lastname;
			$starimage2 = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'star_rating/' . $reviews[$k]->user_rating . '.gif">';

			$fullname2     = $reviews[$k]->username;
			$reviews_data2 = str_replace("{fullname}", '', $reviews_template);
			$reviews_data2 = str_replace("{email}", $reviews[$k]->email, $reviews_data2);
			$reviews_data2 = str_replace("{company_name}", $reviews[$k]->company_name, $reviews_data2);
			$reviews_data2 = str_replace("{title}", $reviews [$k]->title, $reviews_data2);
			$reviews_data2 = str_replace("{comment}", nl2br($reviews [$k]->comment), $reviews_data2);
			$reviews_data2 = str_replace("{stars}", $starimage2, $reviews_data2);
			$reviews_data2 = str_replace("{review_date}", $redshopconfig->convertDateFormat($reviews [$k]->time), $reviews_data2);
			$reviews_data .= $reviews_data2;
		}

		$reviews_data .= '</div>';
		$reviews_all .= $reviews_data;
	}

	$product_data  = str_replace("{review_loop_start}" . $reviews_template . "{review_loop_end}", $reviews_all, $product_data);
	$main_template = str_replace("{product_loop_start}" . $product_template . "{product_loop_end}", $product_data, $main_template);

	$template_desc = str_replace("{product_rating}", $main_template, $template_desc);
}

// Send to friend
$rlink = JURI::root() . 'index.php?option=com_redshop&view=send_friend&pid=' . $this->data->product_id . '&tmpl=component&Itemid=' . $Itemid;
$send_friend_link = '<a class="redcolorproductimg" href="' . $rlink . '" >' . JText::_('COM_REDSHOP_SEND_FRIEND') . '</a>';
$template_desc = str_replace("{send_to_friend}", $send_friend_link, $template_desc);

// Ask question about this product
if (strstr($template_desc, "{ask_question_about_product}"))
{
	$asklink           = JURI::root() . 'index.php?option=com_redshop&view=ask_question&pid=' . $this->data->product_id . '&tmpl=component&Itemid=' . $Itemid;
	$ask_question_link = '<a class="redbox" rel="{handler:\'iframe\',size:{x:500,y:280}}" href="' . $asklink . '" >' . JText::_('COM_REDSHOP_ASK_QUESTION_ABOUT_PRODUCT') . '</a>';
	$template_desc     = str_replace("{ask_question_about_product}", $ask_question_link, $template_desc);
}

if (strstr($template_desc, "{ask_question_about_product_without_lightbox}"))
{
	$template_desc = str_replace("{ask_question_about_product_without_lightbox}", $this->loadTemplate('askquestion'), $template_desc);
}

// Product subscription type
if (strstr($template_desc, "subscription"))
{
	if ($this->data->product_type == 'subscription')
	{
		$subscription      = $producthelper->getSubscription($this->data->product_id);
		$subscription_data = "<table>";
		$subscription_data .= "<tr><th>" . JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD') . "</th><th>" . JText::_('COM_REDSHOP_SUBSCRIPTION_PRICE') . "</th>";
		$subscription_data .= "<th>" . JText::_('COM_REDSHOP_SUBSCRIBE') . "</th></tr>";

		for ($sub = 0; $sub < count($subscription); $sub++)
		{
			$subscription_data .= "<tr>";
			$subscription_data .= "<td>" . $subscription [$sub]->subscription_period . " " . $subscription [$sub]->period_type . "</td>";
			$subscription_data .= "<td>" . $producthelper->getProductFormattedPrice($subscription [$sub]->subscription_price) . "</td>";
			$subscription_data .= "<td align='center'><input type='hidden' id='hdn_subscribe_" . $subscription [$sub]->subscription_id . "' value='" . $subscription [$sub]->subscription_price . "' /><input type='radio' name='rdoSubscription' value='" . $subscription [$sub]->subscription_id . "' onClick=\"changeSubscriptionPrice(" . $subscription [$sub]->subscription_id . ",this.value, " . $this->data->product_id . ")\" /></td>";
			$subscription_data .= "</tr>";
		}

		$subscription_data .= "</table>";
		$template_desc = str_replace("{subscription}", $subscription_data, $template_desc);
	}
	else
	{
		$template_desc = str_replace("{subscription}", "", $template_desc);
	}
}

// Product subscription type ene here

// PRODUCT QUESTION START
if (strstr($template_desc, "{question_loop_start}") && strstr($template_desc, "{question_loop_end}"))
{
	$qstart         = $template_desc;
	$qmiddle        = "";
	$qend           = "";
	$question_start = explode("{question_loop_start}", $template_desc);

	if (count($question_start) > 0)
	{
		$qstart       = $question_start [0];
		$question_end = explode("{question_loop_end}", $question_start [1]);

		if (count($question_end) > 1)
		{
			$qmiddle = $question_end [0];
			$qend    = $question_end [1];
		}
	}

	$product_question = $producthelper->getQuestionAnswer(0, $this->data->product_id, 0, 1);
	$questionloop     = "";

	if ($qmiddle != "")
	{
		for ($q = 0; $q < count($product_question); $q++)
		{
			$qloop = str_replace("{question}", $product_question [$q]->question, $qmiddle);
			$qloop = str_replace("{question_date}", $config->convertDateFormat($product_question [$q]->question_date), $qloop);
			$qloop = str_replace("{question_owner}", $product_question [$q]->user_name, $qloop);

			$astart       = $qloop;
			$amiddle      = "";
			$aend         = "";
			$answer_start = @explode("{answer_loop_start}", $qloop);

			if (count($answer_start) > 0)
			{
				$astart     = $answer_start [0];
				$answer_end = @explode("{answer_loop_end}", $answer_start [1]);

				if (count($answer_end) > 0)
				{
					$amiddle = $answer_end [0];
					$aend    = $answer_end [1];
				}
			}

			$product_answer = $producthelper->getQuestionAnswer($product_question [$q]->question_id, 0, 1, 1);
			$answerloop     = "";

			for ($a = 0; $a < count($product_answer); $a++)
			{
				$aloop = str_replace("{answer}", $product_answer [$a]->question, $amiddle);
				$aloop = str_replace("{answer_date}", $config->convertDateFormat($product_answer [$a]->question_date), $aloop);
				$aloop = str_replace("{answer_owner}", $product_answer [$a]->user_name, $aloop);

				$answerloop .= $aloop;
			}

			$questionloop .= $astart . $answerloop . $aend;
		}
	}

	$template_desc = $qstart . $questionloop . $qend;
}

// PRODUCT QUESTION END

$my_tags = '';

if (MY_TAGS != 0 && $user->id && strstr($template_desc, "{my_tags_button}"))
{
	// Product Tags - New Feature Like Magento Store
	$my_tags .= "<div id='tags_main'><div id='tags_title'>" . JText::_('COM_REDSHOP_PRODUCT_TAGS') . "</div>";
	$my_tags .= "<div id='tags_lable'>" . JText::_('COM_REDSHOP_ADD_YOUR_TAGS') . "</div>";
	$my_tags .= "<div id='tags_form'><form method='post' action='' id='form_tags' name='form_tags'>
				<table id='tags_table'><tr>
						<td><span>" . JText::_('COM_REDSHOP_TAG_NAME') . "</span></td>
						<td><input type='text'	name='tags_name' id='tags_name' value='' size='52' /></td>
						<td><input type='submit' name='tags_submit' id='tags_submit' value='" . JText::_('COM_REDSHOP_ADD_TAGS') . "' /></td>
					</tr>
					<tr><td colspan='3'>" . JText::_('COM_REDSHOP_TIP_TAGS') . "</td></tr>
					<tr><td colspan='3'>
							<input type='hidden' name='tags_id' id='tags_id' value='0' />
							<input type='hidden' name='product_id' id='product_id' value='" . $this->data->product_id . "' />
							<input type='hidden' name='users_id' id='users_id' value='" . $user->id . "' />
							<input type='hidden' name='view' id='view' value='product' />
							<input type='hidden' name='task' id='task' value='addProductTags' />
							<input type='hidden' name='published' id='published' value='1' /></td></tr>
				</table></form>";
	$my_tags .= "</div>";
	$my_tags .= "</div>";

	// End Product Tags
}

$template_desc = str_replace("{my_tags_button}", $my_tags, $template_desc);

$template_desc = str_replace("{with_vat}", "", $template_desc);
$template_desc = str_replace("{without_vat}", "", $template_desc);

$template_desc = str_replace("{attribute_price_with_vat}", "", $template_desc);
$template_desc = str_replace("{attribute_price_without_vat}", "", $template_desc);

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);

$template_desc = $texts->replace_texts($template_desc);

$template_desc = $producthelper->getRelatedtemplateView($template_desc, $this->data->product_id);

/**
 * @var $data
 *
 * Trigger event onAfterDisplayProduct will display content after product display
 */
$dispatcher->trigger('onAfterDisplayProduct', array(& $template_desc, & $this->params, $this->data));

echo eval("?>" . $template_desc . "<?php ");

?>

<script type="text/javascript">

function setsendImagepath(elm) {
	var path = document.getElementById('<?php echo "main_image" . JRequest::getVar('pid');?>').src;
	var filenamepath = path.replace(/\\/g, '/').replace(/.*\//, '');
	var imageName = filenamepath.split('&');
	elm.href = elm + '&imageName=' + imageName[0];
}

function setZoomImagepath(elm) {
	var elmpath = elm.href;
	var elmfilenamepath = elmpath.replace(/\\/g, '/').replace(/.*\//, '');
	var path = document.getElementById('<?php echo "main_image" . JRequest::getVar('pid');?>').src;
	var filenamepath = path.replace(/\\/g, '/').replace(/.*\//, '');
	var imageName = filenamepath.split('&');

	if (/MSIE[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
		elmfilenamepath = decodeURI(elmfilenamepath);
		if (elmfilenamepath != imageName[0]) {
			var imageurl = '<?php echo $url . 'components/com_redshop/assets/images/mergeImages/'; ?>' + imageName[0];
			elm.href = imageurl;
		}
	}
	else {
		if (elmfilenamepath != imageName[0]) {
			var imageurl = '<?php echo $url . 'components/com_redshop/assets/images/mergeImages/'; ?>' + imageName[0];
			elm.href = imageurl;
		}
	}
}

function validateReview() {
	var email = document.getElementById('email').value;
	var form = document.writereview;
	var flag = 0;
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var selection = document.writereview.user_rating;

	for (i = 0; i < selection.length; i++) {
		if (selection[i].checked == true) {
			flag = 1;
		}
	}
	if (flag == 0) {
		alert('<?php echo JText::_('COM_REDSHOP_PLEASE_RATE_THE_PRODUCT'); ?>');
		return false;
	}
	else if (email == '') {
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS')?>");
		return false;
	}
	else if (reg.test(email) == false) {
		alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_EMAIL_ADDRESS')?>");
		return false;
	}
	else if (form.comment.value == "") {
		alert('<?php echo JText::_('COM_REDSHOP_PLEASE_COMMENT_ON_PRODUCT'); ?>');
		return false;
	}
	else if (form.review_captcha.value == "") {
		alert('<?php echo JText::_('COM_REDSHOP_PLEASE_FILL_CAPTCHA'); ?>');
		return false;
	}
	else {
		return true;
	}
}
</script>
