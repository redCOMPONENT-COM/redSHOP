<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_category_scroller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<?php JHTML::_('behavior.tooltip'); ?>
<?php JHTML::_('behavior.modal'); ?>

<?php JHtml::_('redshopjquery.framework'); ?>
<?php $document->addStyleSheet(JURI::base() . 'modules/mod_redshop_products/css/products.css');?>

<!-- Light-box Java-script -->
<?php JHtml::script('com_redshop/redbox.js', false, true); ?>
<?php JHtml::script('com_redshop/attribute.js', false, true); ?>
<?php JHtml::script('com_redshop/common.js', false, true); ?>

<?php JHtml::stylesheet('mod_redshop_category_scroller/jquery.css', false, true); ?>
<?php JHtml::stylesheet('mod_redshop_category_scroller/skin_002.css', false, true); ?>

<?php if ($view == 'category'): ?>

	<?php if (!$GLOBALS['product_price_slider']): ?>
		<?php JHtml::script('com_redshop/jquery.tools.min.js', false, true); ?>
	<?php endif ?>
<?php else: ?>
	<?php JHtml::script('com_redshop/jquery.tools.min.js', false, true); ?>
<?php endif; ?>

<?php JHTML::script('com_redshop/carousel.js', false, true); ?>

<?php $document->addScriptDeclaration("jQuery(document).ready(function () {
    jQuery('#rs_category_scroller_" . $module->id . "').red_product({
        wrap: 'last',
        scroll: 1,
        auto: 6,
        animation: 'slow',
        easing: 'swing',
        itemLoadCallback: jQuery.noConflict()
    });
});"); ?>

<?php echo $pretext; ?>
<div style='height:<?php echo $scrollerheight ?>px;'>
<div>
	<div class='red_product-skin-produkter'>
	<div style='display: block;' class='red_product-container red_product-container-horizontal'>
	<div style='display: block;' class='red_product-prev red_product-prev-horizontal'></div>
	<div style='display: block;left: <?php echo ($scrollerwidth + 20) ?>px;' class='red_product-next red_product-next-horizontal'></div>
	<div class='red_product-clip red_product-clip-horizontal' style='width: <?php echo $scrollerwidth ?>px;'>
	<ul id='rs_category_scroller_<?php echo $module->id ?>' class='red_product-list red_product-list-horizontal'>

<?php for ($i = 0, $countRows = count($rows); $i < $countRows; $i++): ?>
	<?php $row = $rows[$i]; ?>

	<?php $itemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id); ?>

	<?php if (count($itemData) > 0): ?>
		<?php $itemId = $itemData->id; ?>
	<?php else: ?>
		<?php $itemId = $redhelper->getItemid($row->product_id); ?>
	<?php endif; ?>

	<?php $catattach = ''; ?>

	<?php if ($row->category_id): ?>
		<?php $catattach = '&cid=' . $row->category_id; ?>
	<?php endif ?>

	<?php $link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . $catattach . '&Itemid=' . $itemId); ?>
	<?php $url  = JURI::base(); ?>

	<li red_productindex='<?php echo $i ?>' class='red_product-item red_product-item-horizontal'><div class='listing-item'><div class='product-shop'>

	<?php if ($showProductName): ?>
		<?php $pname = $config->maxchar($row->product_name, $productTitleMaxChars, $productTitleEndSuffix); ?>
		<a href='<?php echo $link ?>' title='<?php echo $row->product_name ?>'><?php echo $pname ?></a>
	<?php endif ?>

	<?php if (Redshop::getConfig()->get('SHOW_PRICE') && !Redshop::getConfig()->get('USE_AS_CATALOG') && !Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && $showPrice && !$row->not_for_sale): ?>
		<?php $products          	= $producthelper->getProductNetPrice($row->product_id); ?>
		<?php $productPrice        = $producthelper->getPriceReplacement($products['product_price']); ?>
		<?php $productPriceSaving = $producthelper->getPriceReplacement($products['product_price_saving']); ?>
		<?php $productOldPrice    = $producthelper->getPriceReplacement($products['product_old_price']); ?>

		<?php if ($showDiscountPriceLayout): ?>
			<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'><?php echo $productOldPrice ?></span></div>
			<div id='mod_redmainprice' class='mod_redmainprice'><?php echo $productPrice ?></div>
			<div id='mod_redsavedprice' class='mod_redsavedprice'><?php  JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $productPriceSaving ?></div>
		<?php else: ?>
			<div class='mod_redproducts_price'><?php echo $productPrice ?></div>
		<?php endif; ?>
	<?php endif ?>

	<?php if ($showReadMore): ?>
		<div class='mod_redshop_category_scroller_readmore'><a href='<?php echo $link ?>'><?php echo JText::_('COM_REDSHOP_TXT_READ_MORE') ?></a></div>
	<?php endif ?>

	</div>

	<?php if ($showImage): ?>
		<?php $productImg = ""; ?>

		<?php if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "/product/" . $row->product_full_image)): ?>
			<?php $productImg = RedShopHelperImages::getImagePath(
							$row->product_full_image,
							'',
							'thumb',
							'product',
							$thumbwidth,
							$thumbheight,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						); ?>
		<?php elseif (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "/product/" . $row->product_thumb_image)): ?>
			<?php $productImg = RedShopHelperImages::getImagePath(
							$row->product_thumb_image,
							'',
							'thumb',
							'product',
							$thumbwidth,
							$thumbheight,
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						); ?>
		<?php else: ?>
			<?php $productImg = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg"; ?>
		<?php endif ?>

		<div class='product-image' style='width:<?php echo $thumbwidth ?>px;height:<?php echo $thumbheight ?>px;'>
			<a href='<?php echo $link ?>'><img style='width:<?php echo $thumbwidth ?>px;height:<?php echo $thumbheight ?>px;' src='<?php echo $productImg ?>'></a>
		</div>
	<?php endif ?>

	<?php if ($showAddToCart): ?>
		<!-- Product attribute  Start -->
		<?php $attributes_set = []; ?>

		<?php if ($row->attribute_set_id > 0): ?>
			<?php $attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1); ?>
		<?php endif ?>

		<?php $attributes = $producthelper->getProductAttribute($row->product_id); ?>
		<?php $attributes = array_merge($attributes, $attributes_set); ?>
		<?php $totalatt   = count($attributes); ?>

		<!--Product accessory Start -->
		<?php $accessory      = $producthelper->getProductAccessory(0, $row->product_id); ?>
		<?php $totalAccessory = count($accessory); ?>

		<?php $addtocart_data = $producthelper->replaceCartTemplate($row->product_id, 0, 0, 0, "", false, array(), $totalatt, $totalAccessory, 0, $module_id); ?>
		<div class='form-button'><?php echo $addtocart_data ?><div>
	<?php endif ?>

	</div></li>
<?php endfor ?>

</ul></div></div></div></div></div>
