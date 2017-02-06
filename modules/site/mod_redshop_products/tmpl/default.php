<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

JHtml::stylesheet('mod_redshop_products/products.css', false, true);

// Lightbox Javascript
JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);
JHtml::script('com_redshop/redbox.js', false, true);
?>

<div class="mod_redshop_products_wrapper">

<?php for ($i = 0, $in = count($rows); $i < $in; $i++): ?>
	<?php $row = $rows[$i]; ?>

	<!-- Stock room status -->

	<?php if ($showStockroomStatus == 1): ?>
		<?php $isStockExists = $stockRoomHelper->isStockExists($row->product_id); ?>

		<?php if (!$isStockExists): ?>
			<?php $isPreorderStockExists = $stockRoomHelper->isPreorderStockExists($row->product_id); ?>
		<?php endif ?>

		<?php if (!$isStockExists):?>
			<?php $productPreorder = $row->preorder; ?>

			<?php if (($productPreorder == "global" && Redshop::getConfig()->get('ALLOW_PRE_ORDER')) || ($productPreorder == "yes") || ($productPreorder == "" && Redshop::getConfig()->get('ALLOW_PRE_ORDER'))): ?>
				<?php if (!$isPreorderStockExists): ?>
					<?php $stockStatus = "<div class=\"modProductStockStatus mod_product_outstock\"><span></span>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div>"; ?>
				<?php else: ?>
					<?php $stockStatus = "<div class=\"modProductStockStatus mod_product_preorder\"><span></span>" . JText::_('COM_REDSHOP_PRE_ORDER') . "</div>"; ?>
				<?php endif; ?>

			<?php else: ?>
				<?php $stockStatus = "<div class=\"modProductStockStatus mod_product_outstock\"><span></span>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div>"; ?>
			<?php endif; ?>

		<?php else: ?>
			<?php $stockStatus = "<div class=\"modProductStockStatus mod_product_instock\"><span></span>" . JText::_('COM_REDSHOP_AVAILABLE_STOCK') . "</div>"; ?>
		<?php endif; ?>

	<?php endif; ?>

	<!-- End stock room status -->

	<?php $categoryId = $productHelper->getCategoryProduct($row->product_id); ?>

	<?php $itemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id); ?>

	<?php if (count($itemData) > 0): ?>
		<?php $itemId = $itemData->id; ?>
	<?php else: ?>
		<?php $itemId = $redHelper->getItemid($row->product_id, $categoryId); ?>
	<?php endif ?>

	<?php $link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $categoryId . '&Itemid=' . $itemId); ?>

	<?php if (isset($verticalProduct) && $verticalProduct): ?>
		<div class="mod_redshop_products">
	<?php else: ?>
		<div class="mod_redshop_products_horizontal">
	<?php endif ?>

	<?php $productInfo = $productHelper->getProductById($row->product_id); ?>

	<?php if ($image): ?>
		<?php $thumb = $productInfo->product_full_image; ?>

		<?php if (Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE')): ?>
			<?php $thumImage = $redHelper->watermark('product', $thumb, $thumbWidth, $thumbHeight, Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE'), '0');
			echo "<div class=\"mod_redshop_products_image\"><img src=\"" . $thumImage . "\"></div>"; ?>
		<?php else: ?>
			<?php $thumImage = RedShopHelperImages::getImagePath(
							$thumb,
							'',
							'thumb',
							'product',
							$thumbWidth,
							$thumbHeight,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						); ?>
			<div class="mod_redshop_products_image"><a href="<?php echo $link ?>" title="<?php echo $row->product_name ?>"><img src="<?php echo $thumImage ?>"></a></div>
		<?php endif ?>

	<?php endif ?>

	<?php if (!empty($stockStatus)): ?>
		<?php echo $stockStatus; ?>
	<?php endif ?>

	<div class="mod_redshop_products_title"><a href="<?php echo $link ?>" title=""><?php echo $row->product_name ?></a></div>

	<?php if ($showShortDescription): ?>
		<div class="mod_redshop_products_desc"><?php echo $row->product_s_desc ?></div>
	<?php endif ?>

	<?php if (!$row->not_for_sale && $showPrice): ?>
		<?php $productArr = $productHelper->getProductNetPrice($row->product_id); ?>

		<?php if ($showVat != '0'): ?>
			<?php $productPrice           = $productArr['product_main_price']; ?>
			<?php $productPriceDiscount   = $productArr['productPrice'] + $productArr['productVat']; ?>
			<?php $productOldPrice 		= $productArr['product_old_price']; ?>
		<?php else: ?>
			<?php $productPrice          = $productArr['product_price_novat']; ?>
			<?php $productPriceDiscount = $productArr['productPrice']; ?>
			<?php $productOldPrice 		= $productArr['product_old_price_excl_vat']; ?>
		<?php endif ?>

		<?php if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE))): ?>
			<?php if (!$productPrice): ?>
				<?php $productDiscountPrice = $productHelper->getPriceReplacement($productPrice); ?>
			<?php else: ?>
				<?php $productDiscountPrice = $productHelper->getProductFormattedPrice($productPrice); ?>
			<?php endif ?>

			<?php $displyText = "<div class=\"mod_redshop_products_price\">" . $productDiscountPrice . "</div>"; ?>

			<?php if ($row->product_on_sale && $productPriceDiscount > 0): ?>
				<?php if ($productOldPrice > $productPriceDiscount): ?>
					<?php $displyText = ""; ?>
					<?php $savingPrice     = $productOldPrice - $productPriceDiscount; ?>

					<?php if ($showDiscountPriceLayout): ?>
						<div id="mod_redoldprice" class="mod_redoldprice"><?php echo $productHelper->getProductFormattedPrice($productOldPrice) ?></div>
						<?php $productPrice = $productPriceDiscount; ?>
						<div id="mod_redmainprice" class="mod_redmainprice"><?php echo $productHelper->getProductFormattedPrice($productPriceDiscount) ?></div>
						<div id="mod_redsavedprice" class="mod_redsavedprice"><?php echo JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $productHelper->getProductFormattedPrice($savingPrice) ?></div>
					<?php else: ?>
						<?php $productPrice = $productPriceDiscount; ?>
						<div class="mod_redshop_products_price"><?php echo $productHelper->getProductFormattedPrice($productPrice) ?></div>
					<?php endif ?>
				<?php endif ?>
			<?php endif ?>
			<?php echo $displyText; ?>
		<?php endif ?>

	<?php endif ?>

	<?php if ($showReadmore): ?>
		<div class="mod_redshop_products_readmore"><a href="<?php echo $link ?>"><?php echo JText::_('COM_REDSHOP_TXT_READ_MORE') ?></a>&nbsp;</div>
	<?php endif ?>

	<?php if (isset($showAddToCart) && $showAddToCart): ?>
		<!-- Product attribute  Start -->
		<?php $attributesSet = array(); ?>

		<?php if ($row->attribute_set_id > 0): ?>
			<?php $attributesSet = $productHelper->getProductAttribute(0, $row->attribute_set_id, 0, 1); ?>
		<?php endif ?>

		<?php $attributes = $productHelper->getProductAttribute($row->product_id); ?>
		<?php $attributes = array_merge($attributes, $attributesSet); ?>
		<?php $totalAtt   = count($attributes); ?>

		<!-- Product attribute  End -->


		<!-- Product accessory Start -->
		<?php $accessory      = $productHelper->getProductAccessory(0, $row->product_id); ?>
		<?php $totalAccessory = count($accessory); ?>

		<!-- Product accessory End -->

		<!-- Collecting extra fields -->
		<?php $countNoUserField = 0; ?>
		<?php $hiddenUserField = ''; ?>
		<?php $userfieldArr = []; ?>

		<?php if (Redshop::getConfig()->get('AJAX_CART_BOX')): ?>
			<?php $ajaxDetailTemplateDesc = ""; ?>
			<?php $ajaxDetailTemplate      = $productHelper->getAjaxDetailboxTemplate($row); ?>

			<?php if (count($ajaxDetailTemplate) > 0): ?>
				<?php $ajaxDetailTemplateDesc = $ajaxDetailTemplate->template_desc; ?>
			<?php endif ?>

			<?php $returnArr          = $productHelper->getProductUserfieldFromTemplate($ajaxDetailTemplateDesc); ?>
			<?php $templateUserfield  = $returnArr[0]; ?>
			<?php $userfieldArr       = $returnArr[1]; ?>

			<?php if ($templateUserfield != ""): ?>
				<?php $ufield = ""; ?>

				<?php for ($ui = 0; $ui < count($userfieldArr); $ui++): ?>
					<?php $productUserfields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id); ?>
					<?php $ufield .= $productUserfields[1]; ?>

					<?php if ($productUserfields[1] != ""): ?>
						<?php $countNoUserField++; ?>
					<?php endif ?>

					<?php $templateUserfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserfields[0], $templateUserfield); ?>
					<?php $templateUserfield = str_replace('{' . $userfieldArr[$ui] . '}', $productUserfields[1], $templateUserfield); ?>
				<?php endfor ?>

				<?php if ($ufield != ""): ?>
					<?php $hiddenUserField = "<div class=\"hiddenFields\"><form method=\"post\" action=\"\" id=\"user_fields_form_" . $row->product_id . "\" name=\"user_fields_form_" . $row->product_id . "\">" . $templateUserfield . "</form></div>"; ?>
				<?php endif ?>
			<?php endif ?>
		<?php endif; ?>

		<!-- End -->

		<?php $addToCart = $productHelper->replaceCartTemplate($row->product_id, $categoryId, 0, 0, "", false, $userfieldArr, $totalAtt, $totalAccessory, $countNoUserField, $moduleId); ?>
		<div class="mod_redshop_products_addtocart"><?php echo $addToCart . $hiddenUserField ?></div>
	<?php endif ?>

	</div>
<?php endfor ?>

</div>
