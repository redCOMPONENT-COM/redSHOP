<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_producttab
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
jimport('joomla.html.pane');
$pane = JPane::getInstance('sliders');

$uri = JURI::getInstance();
$url = $uri->root();

$Itemid = JRequest::getInt('Itemid');
$user   = JFactory::getUser();
$option = 'com_redshop';

$document = JFactory::getDocument();
JHTML::Stylesheet('products.css', 'modules/mod_redshop_products/css/');
// 	include redshop js file.
require_once JPATH_SITE . '/components/com_redshop/helpers/redshop.js.php';

JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);
// lightbox Javascript
JHTML::Script('fetchscript.js', 'components/com_redshop/assets/js/', false);
JHtml::stylesheet('com_redshop/fetchscript.css', array(), true);
$module_id = "mod_" . $module->id;

// get product helper
JLoader::load('RedshopHelperProduct');
$producthelper = new producthelper;
$redhelper     = new redhelper;
$extraField    = new extraField;
?>
	<script type="text/javascript">jQuery.noConflict();</script>
	<style>
		<!--

			/* tabs */

		dl.tabs {
			float: left;
			margin: 10px 0 -1px 0;
			z-index: 50;
		}

		dl.tabs dt {
			float: left;
			padding: 4px 10px;
			border-left: 1px solid #ccc;
			border-right: 1px solid #ccc;
			border-top: 1px solid #ccc;
			margin-left: 3px;
			background: #f0f0f0;
			color: #666;
		}

		dl.tabs dt.open {
			background: #F9F9F9;
			border-bottom: 1px solid #F9F9F9;
			z-index: 100;
			color: #000;
		}

		div.current {
			clear: both;
			border: 1px solid #ccc;
			padding: 10px 10px;
		}

		div.current dd {
			padding: 0;
			margin: 0;
		}

		-->
	</style>
<?php
//Get JPaneTabs instance
$myTabs = JPane::getInstance('tabs', array('startOffset' => 0));
//Create Pane
echo $myTabs->startPane('pane');
//Create 1st Tab
if ($newprd)
{
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_NEWEST_PRODUCTS'), 'tab1');?>
	<table border="0" cellpadding="2" cellspacing="2">
		<tr>
			<?php
			for ($i = 0; $i < count($newprdlist); $i++)
			{
				?>
				<td width="20%">
				<?php    $row = $newprdlist[$i];

				$category_id = $row->category_id; //$producthelper->getCategoryProduct($row->product_id);

				$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);
				if (count($ItemData) > 0)
				{
					$Itemid = $ItemData->id;
				}
				else
				{
					$Itemid = $redhelper->getItemid($row->product_id);
				}

				$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid);
				if ($image)
				{
					$thum_image = $producthelper->getProductImage($row->product_id, $link, $thumbwidth, $thumbheight);
					echo "<div>" . $thum_image . "</div>";
				}
				echo "<a href='" . $link . "'>" . $row->product_name . "</a><br>";
				if (!$row->not_for_sale && $show_price && !USE_AS_CATALOG)
				{
					$product_price          = $producthelper->getProductPrice($row->product_id);
					$productArr             = $producthelper->getProductNetPrice($row->product_id);
					$product_price_discount = $productArr['productPrice'] + $productArr['productVat'];
					if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
					{
						if (!$product_price)
						{
							$product_price_dis = $producthelper->getPriceReplacement($product_price);
						}
						else
						{
							$product_price_dis = $producthelper->getProductFormattedPrice($product_price);
						}
						$disply_text = "<div class='mod_redshop_products_price'>" . $product_price_dis . "</div>";

						if ($row->product_on_sale && $product_price_discount > 0)
						{
							if ($product_price > $product_price_discount)
							{
								$disply_text = "";
								$s_price     = $product_price - $product_price_discount;
								if ($show_discountpricelayout)
								{
									echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>" . $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
									$product_price = $product_price_discount;
									echo "<div id='mod_redmainprice' class='mod_redmainprice'>" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
									echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</div>";
								}
								else
								{
									$product_price = $product_price_discount;
									echo "<div class='mod_redshop_products_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
								}
							}
						}
						echo $disply_text;
					} /*else {
				$product_price_dis = $producthelper->getPriceReplacement($product_price);
				echo "<div class='mod_redproducts_price'>".$product_price_dis."</div>";
			}*/
				}
				if ($show_readmore)
				{
					echo "<br><a href='" . $link . "'>" . JText::_('COM_REDSHOP_TXT_READ_MORE') . "</a>&nbsp;";
				}

				if ($show_addtocart)
				{
					/////////////////////////////////// Product attribute  Start /////////////////////////////////
					$attributes_set = array();
					if ($row->attribute_set_id > 0)
					{
						$attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1);
					}
					$attributes = $producthelper->getProductAttribute($row->product_id);
					$attributes = array_merge($attributes, $attributes_set);
					$totalatt   = count($attributes);
					//$data_add = $producthelper->replaceAttributeData($row->product_id,0,0,$attributes,$data_add);
					/////////////////////////////////// Product attribute  End /////////////////////////////////


					/////////////////////////////////// Product accessory Start /////////////////////////////////
					$accessory      = $producthelper->getProductAccessory(0, $row->product_id);
					$totalAccessory = count($accessory);

					//$data_add = $producthelper->replaceAccessoryData($row->product_id,0,$accessory,$data_add,$isChilds);
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
						$ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($row->product_id);
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
								$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id);
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
								$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $row->product_id . "' name='user_fields_form_" . $row->product_id . "'>" . $template_userfield . "</form></div>";
							}
						}
					}
					$addtocart = $producthelper->replaceCartTemplate($row->product_id, $category_id, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id);
					echo "<div class='mod_redshop_products_addtocart'>" . $addtocart . $hidden_userfield . "</div>";
				}
			}    ?></tr>
	</table>
	<?php echo $myTabs->endPanel();
}
//Create 2nd Tab
if ($ltsprd)
{
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_LATEST_PRODUCTS'), 'tab2');?>
	<table border="0" cellpadding="2" cellspacing="2">
		<tr>
			<?php
			//echo '<pre/>';

			for ($i = 0; $i < count($ltsprdlist); $i++)
			{
				?>
				<td width="20%">
				<?php    $row = $ltsprdlist[$i];

				$category_id = $row->category_id; //$category_id = $producthelper->getCategoryProduct($row->product_id);

				$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);
				if (count($ItemData) > 0)
				{
					$Itemid = $ItemData->id;
				}
				else
				{
					$Itemid = $redhelper->getItemid($row->product_id);
				}

				$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid);
				if ($image)
				{
					$thum_image = $producthelper->getProductImage($row->product_id, $link, $thumbwidth, $thumbheight);
					echo "<div>" . $thum_image . "</div>";
				}

				echo "<a href='" . $link . "'>" . $row->product_name . "</a><br>";
				if (!$row->not_for_sale && $show_price)
				{
					$product_price          = $producthelper->getProductPrice($row->product_id);
					$productArr             = $producthelper->getProductNetPrice($row->product_id);
					$product_price_discount = $productArr['productPrice'] + $productArr['productVat'];

					if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
					{
						if (!$product_price)
						{
							$product_price_dis = $producthelper->getPriceReplacement($product_price);
						}
						else
						{
							$product_price_dis = $producthelper->getProductFormattedPrice($product_price);
						}
						$disply_text = "<div class='mod_redproducts_price'>" . $product_price_dis . "</div>";

						if ($row->product_on_sale && $product_price_discount > 0)
						{
							if ($product_price > $product_price_discount)
							{
								$disply_text = "";
								$s_price     = $product_price - $product_price_discount;
								if ($show_discountpricelayout)
								{
									echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>" . $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
									$product_price = $product_price_discount;
									echo "<div id='mod_redmainprice' class='mod_redmainprice'>" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
									echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</div>";
								}
								else
								{
									$product_price = $product_price_discount;
									echo "<div class='mod_redshop_products_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
								}
							}
						}
						echo $disply_text;
					} /*else {
				$product_price_dis = $producthelper->getPriceReplacement($product_price);
				echo "<div class='mod_redshop_products_price'>".$product_price_dis."</div>";
			}*/
				}

				if ($show_readmore)
				{
					echo "<br><a href='" . $link . "'>" . JText::_('COM_REDSHOP_TXT_READ_MORE') . "</a>&nbsp;";
				}
				if ($show_addtocart)
				{
					/////////////////////////////////// Product attribute  Start /////////////////////////////////
					$attributes_set = array();
					if ($row->attribute_set_id > 0)
					{
						$attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1);
					}
					$attributes = $producthelper->getProductAttribute($row->product_id);
					$attributes = array_merge($attributes, $attributes_set);
					$totalatt   = count($attributes);
					//$data_add = $producthelper->replaceAttributeData($row->product_id,0,0,$attributes,$data_add);
					/////////////////////////////////// Product attribute  End /////////////////////////////////


					/////////////////////////////////// Product accessory Start /////////////////////////////////
					$accessory      = $producthelper->getProductAccessory(0, $row->product_id);
					$totalAccessory = count($accessory);

					//$data_add = $producthelper->replaceAccessoryData($row->product_id,0,$accessory,$data_add,$isChilds);
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
						$ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($row->product_id);
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
								$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id);
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
								$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $row->product_id . "' name='user_fields_form_" . $row->product_id . "'>" . $template_userfield . "</form></div>";
							}
						}
					}
					$addtocart = $producthelper->replaceCartTemplate($row->product_id, $category_id, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id);
					echo "<div class='mod_redshop_products_addtocart'>" . $addtocart . $hidden_userfield . "</div>";
				}
			}    ?></tr>
	</table>
	<?php echo $myTabs->endPanel();
}
//Create 2nd Tab
if ($soldprd)
{
	echo $myTabs->startPanel(JText::_('COM_REDSHOP_MOST_SOLD_PRODUCTS'), 'tab3');?>
	<table border="0" cellpadding="2" cellspacing="2">
		<tr>
			<?php
			for ($i = 0; $i < count($soldprdlist); $i++)
			{
				?>
				<td width="20%">
				<?php    $row = $soldprdlist[$i];
				$category_id  = $row->category_id; //$category_id = $producthelper->getCategoryProduct($row->product_id);

				$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);
				if (count($ItemData) > 0)
				{
					$Itemid = $ItemData->id;
				}
				else
				{
					$Itemid = $redhelper->getItemid($row->product_id);
				}

				$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid);
				if ($image)
				{
					$thum_image = $producthelper->getProductImage($row->product_id, $link, $thumbwidth, $thumbheight);
					echo "<div>" . $thum_image . "</div>";
				}

				echo "<a href='" . $link . "'>" . $row->product_name . "</a><br>";
				if (!$row->not_for_sale && $show_price)
				{
					$product_price          = $producthelper->getProductPrice($row->product_id);
					$productArr             = $producthelper->getProductNetPrice($row->product_id);
					$product_price_discount = $productArr['productPrice'] + $productArr['productVat'];
					if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
					{
						if (!$product_price)
						{
							$product_price_dis = $producthelper->getPriceReplacement($product_price);
						}
						else
						{
							$product_price_dis = $producthelper->getProductFormattedPrice($product_price);
						}
						$display_text = "<div class='mod_redproducts_price'>" . $product_price_dis . "</div>";

						if ($row->product_on_sale && $product_price_discount > 0)
						{
							if ($product_price > $product_price_discount)
							{
								$display_text = "";
								$s_price      = $product_price - $product_price_discount;
								if ($show_discountpricelayout)
								{
									echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>" . $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
									$product_price = $product_price_discount;
									echo "<div id='mod_redmainprice' class='mod_redmainprice'>" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
									echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</div>";
								}
								else
								{
									$product_price = $product_price_discount;
									echo "<div class='mod_redshop_products_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
								}
							}
						}
						echo $display_text;
					} /*else {
				$product_price_dis = $producthelper->getPriceReplacement($product_price);
				echo "<div class='mod_redproducts_price'>".$product_price_dis."</div>";
			}*/
				}
				if ($show_readmore)
				{
					echo "<br><a href='" . $link . "'>" . JText::_('COM_REDSHOP_TXT_READ_MORE') . "</a>&nbsp;";
				}
				if ($show_addtocart)
				{
					/////////////////////////////////// Product attribute  Start /////////////////////////////////
					$attributes_set = array();
					if ($row->attribute_set_id > 0)
					{
						$attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1);
					}
					$attributes = $producthelper->getProductAttribute($row->product_id);
					$attributes = array_merge($attributes, $attributes_set);
					$totalatt   = count($attributes);
					//$data_add = $producthelper->replaceAttributeData($row->product_id,0,0,$attributes,$data_add);
					/////////////////////////////////// Product attribute  End /////////////////////////////////


					/////////////////////////////////// Product accessory Start /////////////////////////////////
					$accessory      = $producthelper->getProductAccessory(0, $row->product_id);
					$totalAccessory = count($accessory);

					//$data_add = $producthelper->replaceAccessoryData($row->product_id,0,$accessory,$data_add,$isChilds);
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
						$ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($row->product_id);
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
								$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id);
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
								$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $row->product_id . "' name='user_fields_form_" . $row->product_id . "'>" . $template_userfield . "</form></div>";
							}
						}
					}
					$addtocart = $producthelper->replaceCartTemplate($row->product_id, $category_id, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id);
					echo "<div class='mod_redshop_products_addtocart'>" . $addtocart . $hidden_userfield . "</div>";
				}
			}    ?></tr>
	</table>
	<?php echo $myTabs->endPanel();
}
//Create 2nd Tab
if ($splprd)
{

	echo $myTabs->startPanel(JText::_('COM_REDSHOP_SPECIAL_PRODUCTS'), 'tab4');    ?>
	<table border="0" cellpadding="2" cellspacing="2">
		<tr>
			<?php
			for ($i = 0; $i < count($splprdlist); $i++)
			{
				?>
				<td width="20%">
				<?php    $row = $splprdlist[$i];
				$category_id  = $row->category_id; //$category_id = $producthelper->getCategoryProduct($row->product_id);

				$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id);
				if (count($ItemData) > 0)
				{
					$Itemid = $ItemData->id;
				}
				else
				{
					$Itemid = $redhelper->getItemid($row->product_id);
				}

				$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid);
				if ($image)
				{
					$thum_image = $producthelper->getProductImage($row->product_id, $link, $thumbwidth, $thumbheight);
					echo "<div>" . $thum_image . "</div>";
				}

				echo "<a href='" . $link . "'>" . $row->product_name . "</a><br>";
				if (!$row->not_for_sale && $show_price)
				{
					$product_price          = $producthelper->getProductPrice($row->product_id);
					$productArr             = $producthelper->getProductNetPrice($row->product_id);
					$product_price_discount = $productArr['productPrice'] + $productArr['productVat'];
					if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
					{
						if (!$product_price)
						{
							$product_price_dis = $producthelper->getPriceReplacement($product_price);
						}
						else
						{
							$product_price_dis = $producthelper->getProductFormattedPrice($product_price);
						}
						$disply_text = "<div class='mod_redshop_products_price'>" . $product_price_dis . "</div>";

						if ($row->product_on_sale && $product_price_discount > 0)
						{
							if ($product_price > $product_price_discount)
							{
								$disply_text = "";
								$s_price     = $product_price - $product_price_discount;
								if ($show_discountpricelayout)
								{
									echo "<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'>" . $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
									$product_price = $product_price_discount;
									echo "<div id='mod_redmainprice' class='mod_redmainprice'>" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
									echo "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</div>";
								}
								else
								{
									$product_price = $product_price_discount;
									echo "<div class='mod_redshop_products_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
								}
							}
						}
						echo $disply_text;
					} /*else {
				$product_price_dis = $producthelper->getPriceReplacement($product_price);
				echo "<div class='mod_redproducts_price'>".$product_price_dis."</div>";
			}*/
				}

				if ($show_readmore)
				{
					echo "<br><a href='" . $link . "'>" . JText::_('COM_REDSHOP_TXT_READ_MORE') . "</a>&nbsp;";
				}
				if ($show_addtocart)
				{
					/////////////////////////////////// Product attribute  Start /////////////////////////////////
					$attributes_set = array();
					if ($row->attribute_set_id > 0)
					{
						$attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1);
					}
					$attributes = $producthelper->getProductAttribute($row->product_id);
					$attributes = array_merge($attributes, $attributes_set);
					$totalatt   = count($attributes);
					//$data_add = $producthelper->replaceAttributeData($row->product_id,0,0,$attributes,$data_add);
					/////////////////////////////////// Product attribute  End /////////////////////////////////


					/////////////////////////////////// Product accessory Start /////////////////////////////////
					$accessory      = $producthelper->getProductAccessory(0, $row->product_id);
					$totalAccessory = count($accessory);

					//$data_add = $producthelper->replaceAccessoryData($row->product_id,0,$accessory,$data_add,$isChilds);
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
						$ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($row->product_id);
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
								$product_userfileds = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id);
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
								$hidden_userfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $row->product_id . "' name='user_fields_form_" . $row->product_id . "'>" . $template_userfield . "</form></div>";
							}
						}
					}
					$addtocart = $producthelper->replaceCartTemplate($row->product_id, $category_id, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id);
					echo "<div class='mod_redshop_products_addtocart'>" . $addtocart . $hidden_userfield . "</div>";
				}
			}    ?></tr>
	</table>
	<?php

	echo $myTabs->endPanel();
}
//End Pane
echo $myTabs->endPane();
