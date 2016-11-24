<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_who_bought
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHtml::_('redshopjquery.framework');
JHtml::stylesheet('mod_redshop_who_bought/skin.css', false, true);
JHtml::script('mod_redshop_who_bought/jquery.jcarousel.min.js', false, true);
JHtml::script('com_redshop/common.js', false, true);
JHtml::script('com_redshop/redbox.js', false, true);
?>

<?php $document->addStyleDeclaration('
	.jcarousel-skin-tango .jcarousel-container-horizontal {
		width:' . $sliderWidth . 'px;
	}
	.jcarousel-skin-tango .jcarousel-item {
		width:' . ($sliderWidth / 2 - 8) . 'px;
	}
'); ?>

<?php JFactory::getDocument()->addScriptDeclaration('
	jQuery(document).ready(function () {
		jQuery(\'#mycarousel_' . $module->id . '\').jcarousel();
	});'); ?>

<ul id="mycarousel_<?php echo $module->id ?>" class="jcarousel-skin-tango">

<?php if (count($rows)): ?>
	<?php foreach ($rows as $product): ?>
		<?php $categoryId = $productHelper->getCategoryProduct($product->product_id); ?>

		<?php $attributesSet = array(); ?>

		<?php if ($product->attribute_set_id > 0): ?>
			<?php $attributesSet = $productHelper->getProductAttribute(0, $product->attribute_set_id, 0, 1); ?>
		<?php endif ?>

		<?php $attributes = $productHelper->getProductAttribute($product->product_id); ?>
		<?php $attributes = array_merge($attributes, $attributesSet); ?>
		<?php $totalAtt   = count($attributes); ?>

		<?php $accessory      = $productHelper->getProductAccessory(0, $product->product_id); ?>
		<?php $totalAccessory = count($accessory); ?>

		<!-- Collecting extra fields -->

		<?php $countNumberUserField = 0; ?>
		<?php $hiddenUserfield     = ""; ?>
		<?php $userfieldArr         = []; ?>

		<?php if (Redshop::getConfig()->get('AJAX_CART_BOX')): ?>
			<?php $ajaxDetailTemplateDesc = ""; ?>
			<?php $ajaxDetailTemplate     = $productHelper->getAjaxDetailboxTemplate($product); ?>

			<?php if (count($ajaxDetailTemplate) > 0): ?>
				<?php $ajaxDetailTemplateDesc = $ajaxDetailTemplate->template_desc; ?>
			<?php endif ?>

			<?php $returnArr         = $productHelper->getProductUserfieldFromTemplate($ajaxDetailTemplateDesc); ?>
			<?php $templateUserfield = $returnArr[0]; ?>
			<?php $userfieldArr      = $returnArr[1]; ?>

			<?php if ($templateUserfield != ""): ?>
				<?php $ufield = ""; ?>

				<?php for ($ui = 0; $ui < count($userfieldArr); $ui++): ?>
					<?php $productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $product->product_id); ?>
					<?php $ufield .= $productUserFields[1]; ?>

					<?php if ($productUserFields[1] != ""): ?>
						<?php $countNumberUserField++; ?>
					<?php endif ?>

					<?php $templateUserfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $templateUserfield); ?>
					<?php $templateUserfield = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $templateUserfield); ?>
				<?php endfor ?>

				<?php if ($ufield != ""): ?>
					<?php $hiddenUserfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $product->product_id . "' name='user_fields_form_" . $product->product_id . "'>" . $templateUserfield . "</form></div>"; ?>
				<?php endif ?>
			<?php endif ?>
		<?php endif ?>
		<li>

		<?php if ($showProductImage): ?>
			<?php if (!JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'product/' . $product->product_full_image)): ?>
				<?php $filePath = JPATH_SITE . '/components/com_redshop/assets/images/noimage.jpg'; ?>
				<?php $fileName = RedShopHelperImages::generateImages(
					$filePath, '', $thumbWidth, $thumbHeight, 'thumb', Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				); ?>
				<?php $fileNamePathInfo = pathinfo($fileName); ?>
				<?php $thumbImage = REDSHOP_FRONT_IMAGES_ABSPATH . 'thumb/' . $fileNamePathInfo['basename']; ?>
			<?php elseif (Redshop::getConfig()->get('WATERMARK_PRODUCT_IMAGE')): ?>
				<?php $thumbImage = $redHelper->watermark('product', $product->product_full_image, $thumbWidth, $thumbHeight, Redshop::getConfig()->get('WATERMARK_PRODUCT_THUMB_IMAGE'), '0'); ?>
			<?php else: ?>
				<?php $thumbImage = RedShopHelperImages::getImagePath(
					$product->product_full_image,
					'',
					'thumb',
					'product',
					$thumbWidth,
					$thumbHeight,
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				); ?>
			<?php endif ?>

			<div class="imageWhoBought" style="min-height:<?php echo $thumbHeight ?>px"><img src='<?php echo $thumbImage ?>' /></div>
		<?php endif ?>

		<?php if ($showAddToCart): ?>
			<div>&nbsp;</div>
			<?php $addtocart = $productHelper->replaceCartTemplate($product->product_id, $categoryId, 0, 0, "", false, $userfieldArr, $totalAtt, $totalAccessory, $countNumberUserField, $moduleId); ?>
			<div class='mod_redshop_products_addtocart addToCartWhoBought'><?php echo $addtocart . $hiddenUserfield ?></div>
		<?php endif ?>

		<?php if ($showProductName): ?>
			<?php $pItemid = $redHelper->getItemid($product->product_id); ?>
			<?php $link = JRoute::_(
					'index.php?option=com_redshop&view=product&pid=' . $product->product_id . '&Itemid=' . $pItemid
			); ?>

			<div>&nbsp;</div>

			<?php if ($productTitleLinkable): ?>
				<div style='text-align:center;'>
					<a href='<?php echo $link ?>'>
						<?php echo $product->product_name; ?>
					</a>
				</div>
			<?php else: ?>
				<div style='text-align:center;'><?php echo $product->product_name ?></div>
			<?php endif ?>
		<?php endif ?>

		<?php if ($show_product_price && $product->product_price): ?>
			<?php if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE))): ?>
				<div class="priceWhoBought"><?php echo $productHelper->getProductFormattedPrice($product->product_price) ?></div>
			<?php endif ?>
		<?php endif ?>
	<?php endforeach ?>

	</li>
<?php endif ?>

</ul>
