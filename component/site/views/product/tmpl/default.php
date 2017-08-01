<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtmlBehavior::modal();

$url             = JURI::base();
$u               = JURI::getInstance();
$Scheme          = $u->getScheme();

$watched = $this->session->get('watched_product', array());

if (in_array($this->pid, $watched) == 0)
{
	array_push($watched, $this->pid);
	$this->session->set('watched_product', $watched);
}

$print           = $this->input->getBool('print', false);
$user            = JFactory::getUser();

$extraField      = extraField::getInstance();
$producthelper   = productHelper::getInstance();
$redshopconfig   = Redconfiguration::getInstance();
$stockroomhelper = rsstockroomhelper::getInstance();
$config          = Redconfiguration::getInstance();

$template = $this->template;

if (count($template) > 0 && $template->template_desc != "")
{
	$template_desc = $template->template_desc;
}
else
{
	$template_desc  = "<div id=\"produkt\">\r\n<div class=\"produkt_spacer\"></div>\r\n<div class=\"produkt_anmeldelser_opsummering\">";
	$template_desc .= "{product_rating_summary}</div>\r\n<div id=\"opsummering_wrapper\">\r\n<div id=\"opsummering_skubber\"></div>\r\n";
	$template_desc .= "<div id=\"opsummering_link\">{product_rating_summary}</div>\r\n</div>\r\n<div id=\"produkt_kasse\">\r\n";
	$template_desc .= "<div class=\"produkt_kasse_venstre\">\r\n<div class=\"produkt_kasse_billed\">{product_thumb_image}</div>\r\n";
	$template_desc .= "<div class=\"produkt_kasse_billed_flere\">{more_images}</div>\r\n<div id=\"produkt_kasse_venstre_tekst\">";
	$template_desc .= "{view_full_size_image_lbl}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre\">\r\n{attribute_template:attributes}";
	$template_desc .= "<div class=\"produkt_kasse_hoejre_accessory\">{accessory_template:accessory}</div>\r\n";
	$template_desc .= "<div class=\"produkt_kasse_hoejre_pris\">\r\n<div class=\"produkt_kasse_hoejre_pris_indre\" ";
	$template_desc .= "id=\"produkt_kasse_hoejre_pris_indre\">{product_price}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre_laegikurv\">\r\n";
	$template_desc .= "<div class=\"produkt_kasse_hoejre_laegikurv_indre\">{form_addtocart:add_to_cart2}</div>\r\n</div>\r\n";
	$template_desc .= "<div class=\"produkt_kasse_hoejre_leveringstid\">\r\n<div class=\"produkt_kasse_hoejre_leveringstid_indre\">";
	$template_desc .= "{delivery_time_lbl}: {product_delivery_time}</div>\r\n</div>\r\n<div class=\"produkt_kasse_hoejre_bookmarksendtofriend\">\r\n";
	$template_desc .= "<div class=\"produkt_kasse_hoejre_bookmark\">{bookmark}</div>\r\n<div class=\"produkt_kasse_hoejre_sendtofriend\">";
	$template_desc .= "{send_to_friend}</div>\r\n</div>\r\n</div>\r\n<div id=\"produkt_beskrivelse_wrapper\">\r\n<div class=\"produkt_beskrivelse\">";
	$template_desc .= "\r\n<div id=\"produkt_beskrivelse_maal\">\r\n<div id=\"produkt_maal_wrapper\">\r\n<div id=\"produkt_maal_indhold_hojre\">\r\n";
	$template_desc .= "<div id=\"produkt_hojde\">{product_height_lbl}: {product_height}</div>\r\n<div id=\"produkt_bredde\">";
	$template_desc .= "x {product_width_lbl}: {product_width}</div>\r\n<div id=\"produkt_dybde\">x {product_length_lbl}: {product_length}</div>\r\n";
	$template_desc .= "<div style=\"width: 275px; height: 10px; clear: left;\"></div>\r\n<div id=\"producent_link\">{manufacturer_link}</div>\r\n";
	$template_desc .= "<div id=\"produkt_writereview\">{form_rating}</div>\r\n</div>\r\n</div>\r\n</div>\r\n<h2>{product_name}</h2>\r\n";
	$template_desc .= "<div id=\"beskrivelse_lille\">{product_s_desc}</div>\r\n<div id=\"beskrivelse_stor\">{product_desc}</div>\r\n";
	$template_desc .= "<div class=\"product_related_products\">{related_product:related_products}</div>\r\n</div>\r\n</div>\r\n";
	$template_desc .= "<div id=\"produkt_anmeldelser\">\r\n{product_rating}</div>\r\n</div>\r\n</div>";
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

$returnToCategoryLink = strstr($template_desc, '{returntocategory_link}');
$returnToCategoryName = strstr($template_desc, '{returntocategory_name}');
$returnToCategoryStr  = strstr($template_desc, '{returntocategory}');

if ($returnToCategoryLink || $returnToCategoryName || $returnToCategoryStr)
{
	$returncatlink    = '';
	$returntocategory = '';

	if ($this->data->category_id)
	{
		$returncatlink = JRoute::_(
										'index.php?option=com_redshop&view=category&layout=detail&cid=' . $this->data->category_id .
										'&Itemid=' . $this->itemId
									);

		$returntocategory = '<a href="' . $returncatlink . '">' . Redshop::getConfig()->get('DAFULT_RETURN_TO_CATEGORY_PREFIX') . " " . $this->data->category_name . '</a>';
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
	$nextproducts = $this->model->getPrevNextproduct($this->data->product_id, $this->data->category_id, 1);

	if (count($nextproducts) > 0)
	{
		$nextlink = JRoute::_(
								'index.php?option=com_redshop&view=product&pid=' . $nextproducts->product_id .
								'&cid=' . $this->data->category_id .
								'&Itemid=' . $this->itemId
					);

		if (Redshop::getConfig()->get('DEFAULT_LINK_FIND') == 0)
		{
			$nextbutton = '<a href="' . $nextlink . '">' . $nextproducts->product_name . "" . Redshop::getConfig()->get('DAFULT_NEXT_LINK_SUFFIX') . '</a>';
		}
		elseif (Redshop::getConfig()->get('DEFAULT_LINK_FIND') == 1)
		{
			$nextbutton = '<a href="' . $nextlink . '">' . Redshop::getConfig()->get('CUSTOM_NEXT_LINK_FIND') . '</a>';
		}
		elseif (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('IMAGE_PREVIOUS_LINK_FIND')))
		{
			$nextbutton = '<a href="' . $nextlink . '"><img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . Redshop::getConfig()->get('IMAGE_NEXT_LINK_FIND') . '" /></a>';
		}
	}

	// Start previous logic
	$previousproducts = $this->model->getPrevNextproduct($this->data->product_id, $this->data->category_id, -1);

	if (count($previousproducts) > 0)
	{
		$prevlink = JRoute::_(
								'index.php?option=com_redshop&view=product&pid=' . $previousproducts->product_id .
								'&cid=' . $this->data->category_id .
								'&Itemid=' . $this->itemId
					);

		if (Redshop::getConfig()->get('DEFAULT_LINK_FIND') == 0)
		{
			$prevbutton = '<a href="' . $prevlink . '">' . Redshop::getConfig()->get('DAFULT_PREVIOUS_LINK_PREFIX') . "" . $previousproducts->product_name . '</a>';
		}
		elseif (Redshop::getConfig()->get('DEFAULT_LINK_FIND') == 1)
		{
			$prevbutton = '<a href="' . $prevlink . '">' . Redshop::getConfig()->get('CUSTOM_PREVIOUS_LINK_FIND') . '</a>';
		}
		elseif (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . Redshop::getConfig()->get('IMAGE_PREVIOUS_LINK_FIND')))
		{
			$prevbutton = '<a href="' . $prevlink . '"><img src="' . REDSHOP_FRONT_IMAGES_ABSPATH . Redshop::getConfig()->get('IMAGE_PREVIOUS_LINK_FIND') . '" /></a>';
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
$product_volume .= '<span class="length_unit">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . '</span>';
$product_volume .= '<span class="separator">X</span>';
$product_volume .= '<span class="width_number">' . $producthelper->redunitDecimal($this->data->product_width) . '</span>';
$product_volume .= '<span class="width_unit">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . '</span>';
$product_volume .= '<span class="separator">X</span>';
$product_volume .= '<span class="height_number">' . $producthelper->redunitDecimal($this->data->product_height) . '</span>';
$product_volume .= '<span class="height_unit">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . '</span>';

$template_desc = str_replace('{product_size}', $product_volume, $template_desc);

if (Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'))
{
	$product_unit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . '</span>';
}
else
{
	$product_unit = '';
}

// Product length
if ($this->data->product_length > 0)
{
	$template_desc = str_replace("{product_length_lbl}", JText::_('COM_REDSHOP_PRODUCT_LENGTH_LBL'), $template_desc);

	$insertStr     = $producthelper->redunitDecimal($this->data->product_length) . "&nbsp" . $product_unit;
	$template_desc = str_replace('{product_length}', $insertStr, $template_desc);
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

	$insertStr     = $producthelper->redunitDecimal($this->data->product_width) . "&nbsp" . $product_unit;
	$template_desc = str_replace('{product_width}', $insertStr, $template_desc);
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

	$insertStr     = $producthelper->redunitDecimal($this->data->product_height) . "&nbsp" . $product_unit;
	$template_desc = str_replace('{product_height}', $insertStr, $template_desc);
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
$product_volume_unit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT') . "3" . '</span>';

if ($this->data->product_volume > 0)
{
	$insertStr     = JText::_('COM_REDSHOP_PRODUCT_VOLUME_LBL') . JText::_('COM_REDSHOP_PRODUCT_VOLUME_UNIT');
	$template_desc = str_replace("{product_volume_lbl}", $insertStr, $template_desc);

	$insertStr     = $producthelper->redunitDecimal($this->data->product_volume) . "&nbsp" . $product_volume_unit;
	$template_desc = str_replace('{product_volume}', $insertStr, $template_desc);
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
	$print_url  = $url . "index.php?option=com_redshop&view=product&pid=" . $this->data->product_id;
	$print_url .= "&cid=" . $this->data->category_id . "&print=1&tmpl=component&Itemid=" . $this->itemId;

	$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}

$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png'
					alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'
					title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
$print_tag .= "</a>";

// Associate_tag display update
$ass_tag = '';

if ($this->redHelper->isredProductfinder())
{
	$associate_tag = $producthelper->getassociatetag($this->data->product_id);

	for ($k = 0, $kn = count($associate_tag); $k < $kn; $k++)
	{
		if ($associate_tag[$k] != '')
		{
			$ass_tag .= $associate_tag[$k]->type_name . " : " . $associate_tag[$k]->tag_name . "<br/>";
		}
	}
}

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
	$send_image    = "<a  onclick=\"setZoomImagepath(this)\"
							title='" . $this->data->product_name . "'
							id='rsZoom_image" . $this->data->product_id . "'
							href='" . $sendlink . "' rel=\"lightbox[gallery]\">
			<div class='zoom_image' id='rsDiv_zoom_image'>" . JText::_('SEND_MAIL_IMAGE_LBL') . "</div></a>";
	$template_desc = str_replace("{zoom_image}", $send_image, $template_desc);
}

if (strstr($template_desc, "{product_category_list}"))
{
	$pcats    = "";
	$prodCats = $producthelper->getProductCaterories($this->data->product_id, 1);

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
	$mh_thumb    = Redshop::getConfig()->get('MANUFACTURER_THUMB_HEIGHT');
	$mw_thumb    = Redshop::getConfig()->get('MANUFACTURER_THUMB_WIDTH');
	$thum_image  = "";
	$media_image = $producthelper->getAdditionMediaImage($this->data->manufacturer_id, "manufacturer");
	$m           = 0;

	if ($media_image[$m]->media_name && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "manufacturer/" . $media_image[$m]->media_name))
	{
		$wimg      = $this->redHelper->watermark('manufacturer', $media_image[$m]->media_name, $mw_thumb, $mh_thumb, Redshop::getConfig()->get('WATERMARK_MANUFACTURER_THUMB_IMAGE'));
		$linkimage = $this->redHelper->watermark('manufacturer', $media_image[$m]->media_name, '', '', Redshop::getConfig()->get('WATERMARK_MANUFACTURER_IMAGE'));

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

$product_weight_unit = '<span class="product_unit_variable">' . Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT') . '</span>';

if ($this->data->weight > 0)
{
	$insertStr     = $producthelper->redunitDecimal($this->data->weight) . "&nbsp;" . $product_weight_unit;
	$template_desc = str_replace("{product_weight}", $insertStr, $template_desc);
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

$manuUrl          = JRoute::_(
								'index.php?option=com_redshop&view=manufacturers&layout=detail&mid=' . $this->data->manufacturer_id .
								'&Itemid=' . $this->itemId
					);
$manufacturerLink = "<a class='btn btn-primary' href='" . $manuUrl . "'>" . JText::_("COM_REDSHOP_VIEW_MANUFACTURER") . "</a>";

$manuUrl           = JRoute::_(
								'index.php?option=com_redshop&view=manufacturers&layout=products&mid=' . $this->data->manufacturer_id .
								'&Itemid=' . $this->itemId
					);
$manufacturerPLink = "<a class='btn btn-primary' href='" . $manuUrl . "'>" .
						JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . " " . $this->data->manufacturer_name .
					"</a>";

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
	$facebook_link = urlencode(JFilterOutput::cleanText($uri->toString()));
	$facebook_like = '<iframe src="' . $Scheme . '://www.facebook.com/plugins/like.php?href=' . $facebook_link . '&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>';
	$template_desc = str_replace("{facebook_like_button}", $facebook_like, $template_desc);

	$jconfig  = JFactory::getConfig();
	$sitename = $jconfig->get('sitename');

	$this->document->setMetaData("og:url", JFilterOutput::cleanText($uri->toString()));
	$this->document->setMetaData("og:type", "product");
	$this->document->setMetaData("og:site_name", $sitename);
}

// Google I like Button
if (strstr($template_desc, "{googleplus1}"))
{
	JHTML::script('https://apis.google.com/js/plusone.js');
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
	$ph_thumb = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT_3');
	$pw_thumb = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_3');
}
elseif (strstr($template_desc, "{product_thumb_image_2}"))
{
	$pimg_tag = '{product_thumb_image_2}';
	$ph_thumb = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT_2');
	$pw_thumb = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_2');
}
elseif (strstr($template_desc, "{product_thumb_image_1}"))
{
	$pimg_tag = '{product_thumb_image_1}';
	$ph_thumb = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT');
	$pw_thumb = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE');
}
else
{
	$pimg_tag = '{product_thumb_image}';
	$ph_thumb = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE_HEIGHT');
	$pw_thumb = Redshop::getConfig()->get('PRODUCT_MAIN_IMAGE');
}

// More images
if (strstr($template_desc, "{more_images_3}"))
{
	$mpimg_tag = '{more_images_3}';
	$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3');
	$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_3');
}
elseif (strstr($template_desc, "{more_images_2}"))
{
	$mpimg_tag = '{more_images_2}';
	$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2');
	$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_2');
}
elseif (strstr($template_desc, "{more_images_1}"))
{
	$mpimg_tag = '{more_images_1}';
	$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
	$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
}
else
{
	$mpimg_tag = '{more_images}';
	$mph_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE_HEIGHT');
	$mpw_thumb = Redshop::getConfig()->get('PRODUCT_ADDITIONAL_IMAGE');
}

// PRODUCT WRAPPER START
$wrapper = $producthelper->getWrapper($this->data->product_id, 0, 1);
$wrappertemplate = $this->redTemplate->getTemplate("wrapper_template");

if (strstr($template_desc, "{wrapper_template:"))
{
	for ($w = 0, $wn = count($wrappertemplate); $w < $wn; $w++)
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

			if (Redshop::getConfig()->get('AUTO_SCROLL_WRAPPER'))
			{
				$wrapperimage_div .= "<marquee behavior='scroll'
				 								direction='left'
				 								onmouseover='this.stop()'
				 								onmouseout='this.start()'
				 								scrolldelay='200' width='200'
				 								>";
			}

			$wrapperimage_div .= "<table><tr>";

			for ($i = 0, $in = count($wrapper); $i < $in; $i++)
			{
				$wrapper_vat = 0;

				if ($wrapper[$i]->wrapper_price > 0 && !strstr($template_desc, "{without_vat}"))
				{
					$wrapper_vat = $producthelper->getProducttax($this->data->product_id, $wrapper[$i]->wrapper_price);
				}

				$wp            = $wrapper[$i]->wrapper_price + $wrapper_vat;
				$wp_withoutvat = $wrapper[$i]->wrapper_price;

				$wid   = $wrapper[$i]->wrapper_id;
				$title = " title='" . $wrapper[$i]->wrapper_name . "' ";
				$alt   = " alt='" . $wrapper[$i]->wrapper_name . "' ";

				if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE)))
				{
					$wrapper[$i]->wrapper_name = $wrapper[$i]->wrapper_name . " (" . $producthelper->getProductFormattedPrice($wp) . ")";
				}

				$wrapperimage_div .= "<td id='wrappertd" . $wid . "'>";

				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "wrapper/" . $wrapper[$i]->wrapper_image))
				{
					$thumbUrl = RedShopHelperImages::getImagePath(
									$wrapper[$i]->wrapper_image,
									'',
									'thumb',
									'wrapper',
									Redshop::getConfig()->get('DEFAULT_WRAPPER_THUMB_WIDTH'),
									Redshop::getConfig()->get('DEFAULT_WRAPPER_THUMB_HEIGHT'),
									Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
								);
					$wrapperimage_div .= "
					<a onclick='setWrapper($wid,$wp,$wp_withoutvat,\"" . $this->data->product_id . "\");'>
					<img src='" . $thumbUrl . "' " . $title . $alt . " /></a>";
				}

				if (strstr($wrappertemplate_data, "{wrapper_price}"))
				{
					$wrapperimage_div .= "<br/><div onclick='setWrapper($wid,$wp,$wp_withoutvat,\"" . $this->data->product_id . "\");' align='center'>";

					if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE)))
					{
						$wrapperimage_div .= $producthelper->getProductFormattedPrice($wp);
					}

					$wrapperimage_div .= "</div>";
				}

				$wrapperimage_div .= "</td>";
				$wrappertemplate_data .= "<input type='hidden' name='w_price' id='w_price" . $wid . "' value='" . $wp . "' />";
				$wrappertemplate_data .= "<input type='hidden' name='w_price_withoutvat' id='w_price_withoutvat" . $wid . "' value='" . $wp_withoutvat . "' />";

				if (!Redshop::getConfig()->get('AUTO_SCROLL_WRAPPER'))
				{
					if (($i + 1) % 3 == 0)
					{
						$wrapperimage_div .= "</tr><tr>";
					}
				}
			}

			$wrapperimage_div .= "</tr></table>";

			if (Redshop::getConfig()->get('AUTO_SCROLL_WRAPPER'))
			{
				$wrapperimage_div .= "</marquee>";
			}

			if (count($wrapper) > 0)
			{
				$wrapper = array_merge($warray, $wrapper);

				$lists['wrapper_id'] = JHtml::_(
													'select.genericlist',
													$wrapper,
													'wrapper_id',
													'class="inputbox" onchange="calculateTotalPrice(\'' . $this->data->product_id . '\',0);" ',
													'wrapper_id',
													'wrapper_name',
													0
										);

				$wrappertemplate_data = str_replace("{wrapper_dropdown}", $lists ['wrapper_id'], $wrappertemplate_data);
				$wrappertemplate_data = str_replace("{wrapper_image}", $wrapperimage_div, $wrappertemplate_data);
				$wrappertemplate_data = str_replace("{wrapper_price}", "", $wrappertemplate_data);
				$wrapper_checkbox     = JText::_('COM_REDSHOP_Add_WRAPPER') .
										": <input type='checkbox' name='wrapper_check' onclick='calculateTotalPrice(\"" .
										$this->data->product_id .
										"\",0);' id='wrapper_check' />";
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
		$childproducts = $this->model->getAllChildProductArrayList(0, $parentproductid);

		if (count($childproducts) > 0)
		{
			$childproducts = array_merge(array($productInfo), $childproducts);

			$display_text = (Redshop::getConfig()->get('CHILDPRODUCT_DROPDOWN') == "product_number") ? "product_number" : "product_name";

			$selected = array($this->data->product_id);

			$lists['product_child_id'] = JHtml::_(
													'select.genericlist',
													$childproducts,
													'pid',
													'class="inputbox" size="1"  onchange="document.frmChild.submit();"',
													'product_id',
													$display_text,
													$selected
										);

			$frmChild .= "<form name='frmChild' method='post' action=''>";
			$frmChild .= "<div class='product_child_product'>" . JText::_('COM_REDSHOP_CHILD_PRODUCTS') . "</div>";
			$frmChild .= "<div class='product_child_product_list'>" . $lists ['product_child_id'] . "</div>";
			$frmChild .= "<input type='hidden' name='view' value='product'>";
			$frmChild .= "<input type='hidden' name='task' value='gotochild'>";
			$frmChild .= "<input type='hidden' name='option' value='com_redshop'>";
			$frmChild .= "<input type='hidden' name='Itemid' value='" . $this->itemId . "'>";
			$frmChild .= "</form>";
		}
	}

	$template_desc = str_replace("{child_products}", $frmChild, $template_desc);
}

// Checking for child products
$childproduct = $producthelper->getChildProduct($this->data->product_id);

if (count($childproduct) > 0)
{
	if (Redshop::getConfig()->get('PURCHASE_PARENT_WITH_CHILD') == 1)
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
$template_desc = RedshopHelperAttribute::replaceAttributeData($this->data->product_id, 0, 0, $attributes, $template_desc, $attribute_template, $isChilds);

// Product attribute  End

$pr_number                   = $this->data->product_number;
$preselectedresult           = array();
$moreimage_response          = '';
$property_data               = '';
$subproperty_data            = '';
$attributeproductStockStatus = null;
$selectedpropertyId          = 0;
$selectedsubpropertyId       = 0;

if (count($attributes) > 0 && count($attribute_template) > 0)
{
	for ($a = 0, $an = count($attributes); $a < $an; $a++)
	{
		$selectedId = array();
		$property   = $producthelper->getAttibuteProperty(0, $attributes[$a]->attribute_id);

		if ($attributes[$a]->text != "" && count($property) > 0)
		{
			for ($i = 0, $in = count($property); $i < $in; $i++)
			{
				if ($property[$i]->setdefault_selected)
				{
					$selectedId[] = $property[$i]->property_id;
					$property_data .= $property[$i]->property_id;

					if ($i != (count($property)-1))
					{
						$property_data .= '##';
					}
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
						$selectedId[]     = $subproperty[$sp]->subattribute_color_id;
						$subproperty_data .= $subproperty[$sp]->subattribute_color_id;

						if ($sp != (count($subproperty)-1))
						{
							$subproperty_data .= '##';
						}
					}
				}

				if (count($selectedId) > 0)
				{
					$subproperty_data      = implode('##',$selectedId);
					$selectedsubpropertyId = $selectedId[count($selectedId) - 1];
				}
			}
		}
	}

	$get['product_id']       = $this->data->product_id;
	$get['main_imgwidth']    = $pw_thumb;
	$get['main_imgheight']   = $ph_thumb;
	$get['property_data']    = $property_data;
	$get['subproperty_data'] = $subproperty_data;
	$get['property_id']      = $selectedpropertyId;
	$get['subproperty_id']   = $selectedsubpropertyId;
	$pluginResults           = array();

	// Trigger plugin to get merge images.
	$this->dispatcher->trigger('onBeforeImageLoad', array ($get, &$pluginResults));

	$preselectedresult = RedshopHelperProductTag::displayAdditionalImage(
		$this->data->product_id,
		0,
		0,
		$selectedpropertyId,
		$selectedsubpropertyId,
		$pw_thumb,
		$ph_thumb,
		'product'
	);

	$productAvailabilityDate = strstr($template_desc, "{product_availability_date}");
	$stockNotifyFlag         = strstr($template_desc, "{stock_notify_flag}");
	$stockStatus             = strstr($template_desc, "{stock_status");

	if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus)
	{
		$attributeproductStockStatus = $producthelper->getproductStockStatus(
			$this->data->product_id,
			$totalatt,
			$selectedpropertyId,
			$selectedsubpropertyId
		);
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
	$productAvailabilityDate = strstr($template_desc, "{product_availability_date}");
	$stockNotifyFlag         = strstr($template_desc, "{stock_notify_flag}");
	$stockStatus             = strstr($template_desc, "{stock_status");

	if ($productAvailabilityDate || $stockNotifyFlag || $stockStatus)
	{
		$attributeproductStockStatus = $producthelper->getproductStockStatus($this->data->product_id, $totalatt);
	}
}

$template_desc = $producthelper->replaceProductStockdata(
															$this->data->product_id,
															$selectedpropertyId,
															$selectedsubpropertyId,
															$template_desc,
															$attributeproductStockStatus
				);

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

		for ($m = 0, $mn = count($media_image); $m < $mn; $m++)
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

					if (Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE'))
					{
						$pimg          = $this->redHelper->watermark('product', $thumb, $mpw_thumb, $mph_thumb, Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE'), "1");
						$linkimage     = $this->redHelper->watermark('product', $thumb, '', '', Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE'), "0");

						$hoverimg_path = $this->redHelper->watermark(
																		'product',
																		$thumb,
																		Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH'),
																		Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_HEIGHT'),
																		Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE'),
																		'2'
										);
					}
					else
					{
						$pimg = RedShopHelperImages::getImagePath(
										$thumb,
										'',
										'thumb',
										'product',
										$mpw_thumb,
										$mph_thumb,
										Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
									);
						$linkimage     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $thumb;

						$hoverimg_path = RedShopHelperImages::getImagePath(
											$thumb,
											'',
											'thumb',
											'product',
											Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_WIDTH'),
											Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_HEIGHT'),
											Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
										);
					}

					if (Redshop::getConfig()->get('PRODUCT_ADDIMG_IS_LIGHTBOX'))
					{
						$more_images_div_start = "<div class='additional_image'><a href='" . $linkimage . "' title='" . $alttext . "' rel=\"myallimg\">";
						$more_images_div_end   = "</a></div>";
						$more_images .= $more_images_div_start;
						$more_images .= "<img src='" . $pimg . "' alt='" . $alttext . "' title='" . $alttext . "'>";
						$more_images_hrefend = "";
					}
					else
					{
						if (Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE'))
						{
							$img_path = $this->redHelper->watermark('product', $thumb, $pw_thumb, $ph_thumb, Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE'), '0');
						}
						else
						{
							$img_path = RedShopHelperImages::getImagePath(
											$thumb,
											'',
											'thumb',
											'product',
											$pw_thumb,
											$ph_thumb,
											Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
										);
						}

						$hovermore_images = $this->redHelper->watermark('product', $thumb, '', '', Redshop::getConfig()->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE'), '0');

						$filename_org = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $media_image[$m]->product_full_image;

						if (file_exists($filename_org))
						{
							$thumb_original = $media_image[$m]->product_full_image;
						}
						else
						{
							$thumb_original = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
						}

						if (Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE'))
						{
							$img_path_org = $this->redHelper->watermark('product', $thumb_original, $pw_thumb, $ph_thumb, Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE'), '0');
						}
						else
						{
							$img_path_org = RedShopHelperImages::getImagePath(
											$thumb_original,
											'',
											'thumb',
											'product',
											$pw_thumb,
											$ph_thumb,
											Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
										);
						}

						$hovermore_org = RedShopHelperImages::getImagePath(
											$thumb_original,
											'',
											'thumb',
											'product',
											$pw_thumb,
											$ph_thumb,
											Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
										);
						$oimg_path = RedShopHelperImages::getImagePath(
											$thumb,
											'',
											'thumb',
											'product',
											$mpw_thumb,
											$mph_thumb,
											Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
										);

						$more_images_div_start = "<div class='additional_image'
						 								onmouseover='display_image(\"" . $img_path . "\"," . $this->data->product_id . ",\"" . $hovermore_images . "\");'
						 								onmouseout='display_image_out(\"" . $img_path_org . "\"," . $this->data->product_id . ",\"" . $img_path_org . "\");'>";
						$more_images_div_end   = "</div>";
						$more_images .= $more_images_div_start;
						$more_images .= '<a href="javascript:void(0)" >' . "<img src='" . $pimg . "' title='" . $alttext . "' style='cursor: auto;'>";
						$more_images_hrefend = "</a>";
					}

					if (Redshop::getConfig()->get('ADDITIONAL_HOVER_IMAGE_ENABLE'))
					{
						$more_images .= "<img src='" . $hoverimg_path . "' alt='" . $alttext . "' title='" . $alttext . "' class='redImagepreview'>";
					}

					$more_images .= $more_images_hrefend;
					$more_images .= $more_images_div_end;
				}
			}
		}
	}

	$insertStr     = "<div class='redhoverImagebox' id='additional_images" . $this->data->product_id . "'>" . $more_images . "</div><div class=\"clr\"></div>";
	$template_desc = str_replace($mpimg_tag, $insertStr, $template_desc);
}

// More images end

// More videos (youtube)
if (strstr($template_desc, "{more_videos}"))
{
	$media_product_videos = $producthelper->getAdditionMediaImage($this->data->product_id, "product", "youtube");

	if (count($attributes) > 0 && count($attribute_template) > 0)
	{
		for ($a = 0, $an = count($attributes); $a < $an; $a++)
		{
			$selectedId = array();
			$property   = $producthelper->getAttibuteProperty(0, $attributes[$a]->attribute_id);

			if ($attributes[$a]->text != "" && count($property) > 0)
			{
				for ($i = 0, $in = count($property); $i < $in; $i++)
				{
					if ($property[$i]->setdefault_selected)
					{
						$media_property_videos = $producthelper->getAdditionMediaImage($property[$i]->property_id, "property", "youtube");
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
							$media_subproperty_videos = $producthelper->getAdditionMediaImage($subproperty[$sp]->subattribute_color_id, "subproperty", "youtube");
							$selectedId[]     = $subproperty[$sp]->subattribute_color_id;
						}
					}
				}
			}
		}

	}

	if (!empty($media_subproperty_videos))
	{
		$media_videos = $media_subproperty_videos;
	}
	elseif (!empty($media_property_videos))
	{
		$media_videos = $media_property_videos;
	}
	elseif (!empty($media_product_videos))
	{
		$media_videos = $media_product_videos;
	}

	$insertStr = '';

	if (count($media_videos) > 0)
	{
		for ($m = 0, $mn = count($media_videos); $m < $mn; $m++)
		{
			$insertStr .= "<div id='additional_vids_" . $media_videos[$m]->media_id . "'><a class='modal' title='" . $media_videos[$m]->media_alternate_text . "' href='http://www.youtube.com/embed/" . $media_videos[$m]->media_name . "' rel='{handler: \"iframe\", size: {x: 800, y: 500}}'><img src='https://img.youtube.com/vi/" . $media_videos[$m]->media_name . "/default.jpg' height='80px' width='80px'/></a></div>";
		}
	}

	$template_desc = str_replace("{more_videos}", $insertStr, $template_desc);
}
// More videos (youtube) end

// More documents
if (strstr($template_desc, "{more_documents}"))
{
	$media_documents = $producthelper->getAdditionMediaImage($this->data->product_id, "product", "document");
	$more_doc        = '';

	for ($m = 0, $mn = count($media_documents); $m < $mn; $m++)
	{
		$alttext = $producthelper->getAltText("product", $media_documents[$m]->section_id, "", $media_documents[$m]->media_id, "document");

		if (!$alttext)
		{
			$alttext = $media_documents[$m]->media_name;
		}

		if (JFile::exists(REDSHOP_FRONT_DOCUMENT_RELPATH . "product/" . $media_documents[$m]->media_name))
		{
			$downlink = JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&pid=' . $this->data->product_id .
										'&task=downloadDocument&fname=' . $media_documents[$m]->media_name .
										'&Itemid=' . $this->itemId;
			$more_doc .= "<div><a href='" . $downlink . "' title='" . $alttext . "'>";
			$more_doc .= $alttext;
			$more_doc .= "</a></div>";
		}
	}

	$insertStr     = "<span id='additional_docs" . $this->data->product_id . "'>" . $more_doc . "</span>";
	$template_desc = str_replace("{more_documents}", $insertStr, $template_desc);
}

// More documents end

$hidden_thumb_image = "<input type='hidden' name='prd_main_imgwidth' id='prd_main_imgwidth' value='" . $pw_thumb . "'>
						<input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='" . $ph_thumb . "'>";
$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $this->data->product_id);

// Product image
$thum_image = "<div class='productImageWrap' id='productImageWrapID_" . $this->data->product_id . "'>" .
				$producthelper->getProductImage($this->data->product_id, $link, $pw_thumb, $ph_thumb, Redshop::getConfig()->get('PRODUCT_DETAIL_IS_LIGHTBOX'), 0, 0, $preselectedresult) .
				"</div>";

$template_desc = str_replace($pimg_tag, $thum_image . $hidden_thumb_image, $template_desc);
// Product image end

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
	$cart   = $this->session->get('cart');

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

		$productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', $cart_id, 0, $this->data->product_id);

		$ufield .= $productUserFields[1];

		if ($productUserFields[1] != "")
		{
			$count_no_user_field++;
		}

		$template_desc = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $template_desc);
		$template_desc = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $template_desc);
	}

	$productUserFieldsForm = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

	if ($ufield != "")
	{
		$template_desc = str_replace("{if product_userfield}", $productUserFieldsForm, $template_desc);
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
	$mainsrcPath = RedShopHelperImages::getImagePath(
						$this->data->category_full_image,
						'',
						'thumb',
						'category',
						$pw_thumb,
						$ph_thumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
	$backsrcPath = RedShopHelperImages::getImagePath(
						$this->data->category_back_full_image,
						'',
						'thumb',
						'category',
						$pw_thumb,
						$ph_thumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);

	$ahrefpath     = REDSHOP_FRONT_IMAGES_ABSPATH . "category/" . $this->data->category_full_image;
	$ahrefbackpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->category_back_full_image;

	$product_front_image_link = "<a href='#' onClick='javascript:changeproductImage(" . $this->data->product_id . ",\"" . $mainsrcPath . "\",\"" .
																						$ahrefpath . "\");'>" .
									JText::_('COM_REDSHOP_FRONT_IMAGE') .
								"</a>";
	$product_back_image_link  = "<a href='#' onClick='javascript:changeproductImage(" . $this->data->product_id . ",\"" . $backsrcPath . "\",\"" .
																						$ahrefbackpath . "\");'>" .
									JText::_('COM_REDSHOP_BACK_IMAGE') .
								"</a>";

	$template_desc = str_replace("{category_front_img_link}", $product_front_image_link, $template_desc);
	$template_desc = str_replace("{category_back_img_link}", $product_back_image_link, $template_desc);

	// Display category front image
	$thum_catimage = $producthelper->getProductCategoryImage(
																$this->data->product_id,
																$this->data->category_full_image,
																'',
																$pw_thumb, $ph_thumb,
																Redshop::getConfig()->get('PRODUCT_DETAIL_IS_LIGHTBOX')
															);
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
		$mainsrcPath = RedShopHelperImages::getImagePath(
						$this->data->product_full_image,
						'',
						'thumb',
						'product',
						$pw_thumb,
						$ph_thumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
	}

	if ($this->data->product_back_thumb_image)
	{
		$backsrcPath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_back_thumb_image;
	}
	else
	{
		$backsrcPath = RedShopHelperImages::getImagePath(
						$this->data->product_back_full_image,
						'',
						'thumb',
						'product',
						$pw_thumb,
						$ph_thumb,
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);
	}

	$ahrefpath     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_full_image;
	$ahrefbackpath = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $this->data->product_back_full_image;

	$product_front_image_link = "<a href='#' onClick='javascript:changeproductImage(" . $this->data->product_id . ",\"" . $mainsrcPath . "\",\"" .
																						$ahrefpath . "\");'>" .
									JText::_('COM_REDSHOP_FRONT_IMAGE') .
								"</a>";
	$product_back_image_link  = "<a href='#' onClick='javascript:changeproductImage(" . $this->data->product_id . ",\"" . $backsrcPath . "\",\"" .
																						$ahrefbackpath . "\");'>" .
									JText::_('COM_REDSHOP_BACK_IMAGE') .
								"</a>";

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
	if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $this->data->product_preview_image))
	{
		$previewsrcPath = RedShopHelperImages::getImagePath(
						$this->data->product_preview_image,
						'',
						'thumb',
						'product',
						Redshop::getConfig()->get('PRODUCT_PREVIEW_IMAGE_WIDTH'),
						Redshop::getConfig()->get('PRODUCT_PREVIEW_IMAGE_HEIGHT'),
						Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
					);

		$previewImg    = "<img src='" . $previewsrcPath . "' class='rs_previewImg' />";
		$template_desc = str_replace("{product_preview_img}", $previewImg, $template_desc);
	}
	else
	{
		$template_desc = str_replace("{product_preview_img}", "", $template_desc);
	}
}

// Cart
$template_desc = $producthelper->replaceCartTemplate(
														$this->data->product_id,
														$this->data->category_id,
														0,
														0,
														$template_desc,
														$isChilds,
														$userfieldArr,
														$totalatt,
														$totalAccessory,
														$count_no_user_field
													);

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

if (($user->id && Redshop::getConfig()->get('RATING_REVIEW_LOGIN_REQUIRED')) || !Redshop::getConfig()->get('RATING_REVIEW_LOGIN_REQUIRED'))
{
	// Write Review link with the products
	if (strstr($template_desc, "{form_rating_without_lightbox}"))
	{
		$reviewlink    = JURI::root() . 'index.php?option=com_redshop&view=product_rating&rate=1&product_id=' . $this->data->product_id .
										'&category_id=' . $this->data->category_id .
										'&Itemid=' . $this->itemId;
		$reviewform    = '<a href="' . $reviewlink . '">' . JText::_('WRITE_REVIEW') . '</a>';
		$template_desc = str_replace("{form_rating_without_lightbox}", $reviewform, $template_desc);
	}

	if (strstr($template_desc, "{form_rating}"))
	{
		$reviewlink    = "";
		$reviewform    = "";
		$reviewlink    = JURI::root() . 'index.php?option=com_redshop&view=product_rating&tmpl=component&product_id=' . $this->data->product_id .
										'&category_id=' . $this->data->category_id .
										'&Itemid=' . $this->itemId;
		$reviewform    = '<a class="redbox btn btn-primary" rel="{handler:\'iframe\',size:{x:500,y:500}}" href="' . $reviewlink . '">' .
							JText::_('COM_REDSHOP_WRITE_REVIEW') .
						'</a>';
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
	if (Redshop::getConfig()->get('FAVOURED_REVIEWS') != "" || Redshop::getConfig()->get('FAVOURED_REVIEWS') != 0)
	{
		$mainblock = Redshop::getConfig()->get('FAVOURED_REVIEWS');
	}
	else
	{
		$mainblock = 5;
	}

	$main_template = $this->redTemplate->getTemplate("review");

	if (count($main_template) > 0 && $main_template[0]->template_desc)
	{
		$main_template = $main_template[0]->template_desc;
	}
	else
	{
		$main_template  = "<div>{product_loop_start}<p><strong>{product_title}</strong></p><div>{review_loop_start}<div id=\"reviews_wrapper\">";
		$main_template .= "<div id=\"reviews_rightside\"><div id=\"reviews_fullname\">{fullname}</div><div id=\"reviews_title\">{title}</div>";
		$main_template .= "<div id=\"reviews_comment\">{comment}</div></div><div id=\"reviews_leftside\"><div id=\"reviews_stars\">{stars}</div>";
		$main_template .= "</div></div>{review_loop_end}<div>{product_loop_end}</div></div></div>	";
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
			$fullname  = $reviews[$j]->firstname . " " . $reviews[$j]->lastname;
			$starimage = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'star_rating/' . $reviews[$j]->user_rating . '.gif">';

			if ($fullname != " ")
			{
				$displayname = $fullname;
			}
			else
			{
				$displayname = $reviews[$j]->username;
			}

			$reviews_data1 = str_replace("{fullname}", $displayname, $reviews_template);
			$reviews_data1 = str_replace("{email}", $reviews[$j]->email, $reviews_data1);
			$reviews_data1 = str_replace("{company_name}", $reviews[$j]->company_name, $reviews_data1);
			$reviews_data1 = str_replace("{title}", $reviews [$j]->title, $reviews_data1);
			$reviews_data1 = str_replace("{comment}", nl2br($reviews [$j]->comment), $reviews_data1);
			$reviews_data1 = str_replace("{stars}", $starimage, $reviews_data1);
			$reviews_data1 = str_replace("{reviewdate}", $redshopconfig->convertDateFormat($reviews [$j]->time), $reviews_data1);
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
			$reviews_data2 = str_replace("{reviewdate}", $redshopconfig->convertDateFormat($reviews [$k]->time), $reviews_data2);
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
$rlink = JURI::root() . 'index.php?option=com_redshop&view=send_friend&pid=' . $this->data->product_id . '&tmpl=component&Itemid=' . $this->itemId;
$send_friend_link = '<a class="redcolorproductimg" href="' . $rlink . '" >' . JText::_('COM_REDSHOP_SEND_FRIEND') . '</a>';
$template_desc = str_replace("{send_to_friend}", $send_friend_link, $template_desc);

// Ask question about this product
if (strstr($template_desc, "{ask_question_about_product}"))
{
	$asklink           = JURI::root() . 'index.php?option=com_redshop&view=ask_question&pid=' . $this->data->product_id .
										'&tmpl=component&Itemid=' . $this->itemId;
	$ask_question_link = '<a class="redbox btn btn-primary" rel="{handler:\'iframe\',size:{x:500,y:500}}" href="' . $asklink . '" >' .
							JText::_('COM_REDSHOP_ASK_QUESTION_ABOUT_PRODUCT') .
						'</a>';
	$template_desc     = str_replace("{ask_question_about_product}", $ask_question_link, $template_desc);
}

// Product subscription type
if (strstr($template_desc, "{subscription}") || strstr($template_desc, "{product_subscription}"))
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
			$subscription_data .= "<td>";
			$subscription_data .= "<input type='hidden'
			 								id='hdn_subscribe_" . $subscription [$sub]->subscription_id . "'
			 								value='" . $subscription [$sub]->subscription_price . "' />";
			$subscription_data .= "<input type='radio'
			 								name='rdoSubscription'
			 								value='" . $subscription [$sub]->subscription_id . "'
			 								onClick=\"changeSubscriptionPrice(" . $subscription [$sub]->subscription_id . ",this.value, " . $this->data->product_id .
									")\" /></td>";
			$subscription_data .= "</tr>";
		}

		$subscription_data .= "</table>";
		$template_desc = str_replace("{subscription}", $subscription_data, $template_desc);
		$template_desc = str_replace("{product_subscription}", $subscription_data, $template_desc);
	}
	else
	{
		$template_desc = str_replace("{subscription}", "", $template_desc);
		$template_desc = str_replace("{product_subscription}", "", $template_desc);
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
		for ($q = 0, $qn = count($product_question); $q < $qn; $q++)
		{
			$qloop = str_replace("{question}", $product_question [$q]->question, $qmiddle);
			$qloop = str_replace("{question_date}", $config->convertDateFormat($product_question [$q]->question_date), $qloop);
			$qloop = str_replace("{question_owner}", $product_question [$q]->user_name, $qloop);

			$astart       = $qloop;
			$amiddle      = "";
			$aend         = "";
			$answer_start = explode("{answer_loop_start}", $qloop);

			if (count($answer_start) > 0)
			{
				$astart     = $answer_start [0];
				$answer_end = explode("{answer_loop_end}", $answer_start [1]);

				if (count($answer_end) > 0)
				{
					$amiddle = $answer_end [0];
					$aend    = $answer_end [1];
				}
			}

			$product_answer = $producthelper->getQuestionAnswer($product_question [$q]->id, 0, 1, 1);
			$answerloop     = "";

			for ($a = 0, $an = count($product_answer); $a < $an; $a++)
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

if (Redshop::getConfig()->get('MY_TAGS') != 0 && $user->id && strstr($template_desc, "{my_tags_button}"))
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

// Replace Minimum quantity per order
$minOrderProductQuantity = '';

if ((int) $this->data->min_order_product_quantity > 0)
{
	$minOrderProductQuantity = $this->data->min_order_product_quantity;
}

$template_desc = str_replace(
	'{min_order_product_quantity}',
	$minOrderProductQuantity,
	$template_desc
);

$template_desc = $this->redTemplate->parseredSHOPplugin($template_desc);

$template_desc = $this->textHelper->replace_texts($template_desc);

$template_desc = $producthelper->getRelatedtemplateView($template_desc, $this->data->product_id);

// Replacing ask_question_about_product_without_lightbox must be after parseredSHOPplugin for not replace in cloak plugin form emails
if (strstr($template_desc, '{ask_question_about_product_without_lightbox}'))
{
	$displayData = array(
		'form' => RedshopModelForm::getInstance('Ask_Question', 'RedshopModel')->getForm(),
		'ask' => 1
	);
	$template_desc = str_replace('{ask_question_about_product_without_lightbox}', RedshopLayoutHelper::render('product.ask_question', $displayData), $template_desc);
}

// Replacing form_rating_without_link must be after parseredSHOPplugin for not replace in cloak plugin form emails
if (strstr($template_desc, '{form_rating_without_link}'))
{
	$form = RedshopModelForm::getInstance(
			'Product_Rating',
			'RedshopModel',
			array(
				'context' => 'com_redshop.edit.product_rating.' . $this->data->product_id
			)
		)
		->getForm();
	$displayData = array(
		'form' => $form,
		'modal' => 0,
		'product_id' => $this->data->product_id
	);
	$template_desc = str_replace('{form_rating_without_link}', RedshopLayoutHelper::render('product.product_rating', $displayData), $template_desc);
}

/**
 * Trigger event onAfterDisplayProduct will display content after product display.
 * Will we change only $template_desc inside a plugin, that's why only $template_desc should be
 * passed by reference.
 */
$this->dispatcher->trigger('onAfterDisplayProduct', array(&$template_desc, $this->params, $this->data));

echo eval("?>" . $template_desc . "<?php ");

?>

<script type="text/javascript">

function setsendImagepath(elm) {
	var path = document.getElementById('<?php echo "main_image" . $this->pid;?>').src;
	var filenamepath = path.replace(/\\/g, '/').replace(/.*\//, '');
	var imageName = filenamepath.split('&');
	elm.href = elm + '&imageName=' + imageName[0];
}

</script>
