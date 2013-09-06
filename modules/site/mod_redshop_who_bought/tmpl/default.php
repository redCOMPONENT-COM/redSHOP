<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_who_bought
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

$Itemid = JRequest::getInt('Itemid');
$user = JFactory::getUser();

$document = JFactory::getDocument();
include_once("modules/mod_redshop_who_bought/assets/css/skin.css.php");
JHTML::Script('jquery-1.4.2.min.js', 'components/com_redshop/assets/js/', false);
JHTML::Script('query.jcarousel.min.js', 'modules/mod_redshop_who_bought/assets/js/', false);

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
$Redconfiguration = new Redconfiguration();
$Redconfiguration->defineDynamicVars();

$producthelper = new producthelper();
$redhelper = new redhelper();
$redTemplate = new Redtemplate();
$extraField = new extraField();
$module_id = "mod_" . $module->id;

// 	include redshop js file.
require_once JPATH_SITE . '/components/com_redshop/helpers/redshop.js.php';


JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
// lightbox Javascript
JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);
JHTML::Stylesheet('fetchscript.css', 'components/com_redshop/assets/css/');

echo '<ul id="mycarousel" class="jcarousel-skin-tango">';
if (count($productlists))
{
	foreach ($productlists as $product)
	{
		$category_id = $producthelper->getCategoryProduct($product->product_id);

		$attributes_set = array();
		if ($product->attribute_set_id > 0)
		{
			$attributes_set = $producthelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
		}
		$attributes = $producthelper->getProductAttribute($product->product_id);
		$attributes = array_merge($attributes, $attributes_set);
		$totalatt   = count($attributes);
		/////////////////////////////////// Product attribute  End /////////////////////////////////


		/////////////////////////////////// Product accessory Start /////////////////////////////////
		$accessory      = $producthelper->getProductAccessory(0, $product->product_id);
		$totalAccessory = count($accessory);
		/////////////////////////////////// Product accessory End /////////////////////////////////


		/*
		 * collecting extra fields
		 */
		$count_no_user_field = 0;
		$hidden_userfield    = "";
		$userfieldArr        = array();
		if (AJAX_CART_BOX)
		{
			$ajax_detail_template_desc = "";
			$ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($product);
			if (count($ajax_detail_template) > 0)
			{
				$ajax_detail_template_desc = $ajax_detail_template->template_desc;
			}
			$returnArr          = $producthelper->getProductUserfieldFromTemplate($ajax_detail_template_desc);
			$template_userfield = $returnArr[0];
			$userfieldArr       = $returnArr[1];

			if ($template_userfield != "")
			{
				$ufield = "";
				for ($ui = 0; $ui < count($userfieldArr); $ui++)
				{
					$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $product->product_id);
					$ufield .= $product_userfileds[1];
					if ($product_userfileds[1] != "")
					{
						$count_no_user_field++;
					}
					$template_userfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $product_userfileds[0], $template_userfield);
					$template_userfield = str_replace('{' . $userfieldArr[$ui] . '}', $product_userfileds[1], $template_userfield);
				}
				if ($ufield != "")
				{
					$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $product->product_id . "' name='user_fields_form_" . $product->product_id . "'>" . $template_userfield . "</form></div>";
				}
			}
		}


		echo " <li>";
		if ($show_product_image)
		{
			echo "<div style='height:" . $thumbheight . ";  text-align:center;'><img  height='" . $thumbheight . " width='" . $thumbwidth . " src='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image . "' /></div>";
		}
		if ($show_addtocart_button)
		{
			echo "<div>&nbsp;</div>";
			$addtocart = $producthelper->replaceCartTemplate($product->product_id, $category_id, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id);
			echo "<div class='mod_redshop_products_addtocart'>" . $addtocart . $hidden_userfield . "</div>";

		}


		if ($show_product_name)
		{
			echo "<div>&nbsp;</div>";
			echo "<div style='text-align:center;'>" . $product->product_name . "</div>";
		}

		if ($show_product_price)
		{
			echo "<div style='text-align:center;'>" . $product->product_price . "&nbsp;" . CURRENCY_CODE . "</div>";
		}
	}
	echo "</li>";
}
echo "</ul>"


?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('#mycarousel').jcarousel();
	});

</script>