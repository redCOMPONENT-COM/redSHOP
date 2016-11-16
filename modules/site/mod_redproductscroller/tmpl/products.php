<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<?php if (count($ItemData) > 0): ?>
	<?php $Itemid = $ItemData->id; ?>
<?php else: ?>
	<?php $Itemid = $redhelper->getItemid($row->product_id); ?>
<?php endif; ?>

<?php $html   = ''; ?>
<?php $thumbImage = ""; ?>
<?php $pname = $row->product_name; ?>
<?php $link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid); ?>
<?php $pname = $row->product_name; ?>

<?php if ($boxwidth > 0): ?>
	<?php $pwidth = $boxwidth / 10; ?>
	<?php $pname  = wordwrap($pname, $pwidth, "<br>\n", true); ?>
<?php endif; ?>

<?php if ($row->product_full_image): ?>
	<?php $thumbUrl = RedShopHelperImages::getImagePath(
					$row->product_full_image,
					'',
					'thumb',
					'product',
					$thumbwidth,
					$thumbheight,
					Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
				); ?>
	<?php $thumbImage = "<div style='width:" . $thumbwidth . "px;height:" . $thumbheight . "px;'>
						<a href='" . $link . "' title=''>
							<img src='" . $thumbUrl . "'>
						</a>
					</div>"; ?>

	<?php $html .= $thumbImage; ?>
<?php endif; ?>

<?php if ($showProductName == 'yes'): ?>
	<?php $pname = "<tr><td style='text-align:" . $ScrollTextAlign . ";font-weight:" . $ScrollTextWeight . ";font-size:" . $ScrollTextSize . "px;'><a href='" . $link . "' >" . $pname . "</a></td></tr>"; ?>
	<?php $html .= $pname; ?>
<?php endif; ?>

<?php if (Redshop::getConfig()->get('SHOW_PRICE') == 1 && !$row->not_for_sale && !Redshop::getConfig()->get('USE_AS_CATALOG') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')))): ?>
	<?php if ($showPrice == 'yes'): ?>
		<?php $productPrice    	  = $producthelper->getProductPrice($row->product_id); ?>
		<?php $productArr       	  = $producthelper->getProductNetPrice($row->product_id); ?>
		<?php $productPriceDiscount = $productArr['productPrice'] + $productArr['productVat']; ?>

		<?php if (!$productPrice): ?>
			<?php $productPriceDis = $producthelper->getPriceReplacement($productPrice); ?>
		<?php else: ?>
			<?php $productPriceDis = $producthelper->getProductFormattedPrice($productPrice); ?>
		<?php endif; ?>

		<?php $display_text = "<tr><td class='mod_redproducts_price' style='text-align:" . $ScrollTextAlign . ";font-weight:" . $ScrollTextWeight . ";font-size:" . $ScrollTextSize . "px;'>" . $productPriceDis . "</td></tr>"; ?>

		<?php if ($row->product_on_sale && $productPriceDiscount > 0): ?>
			<?php if ($productPrice > $productPriceDiscount): ?>
				<?php $display_text = ""; ?>
				<?php $s_price      = $productPrice - $productPriceDiscount; ?>

				<?php if ($show_discountpricelayout): ?>
					<?php $html .= "<tr><td id='mod_redoldprice' class='mod_redoldprice' style='text-align:" . $ScrollTextAlign . ";font-weight:" . $ScrollTextWeight . ";font-size:" . $ScrollTextSize . "px;'><span style='text-decoration:line-through;'>" . $producthelper->getProductFormattedPrice($productPrice) . "</span></td></tr>"; ?>
					<?php $productPrice = $productPriceDiscount; ?>
					<?php $html .= "<tr><td id='mod_redmainprice' class='mod_redmainprice' style='text-align:" . $ScrollTextAlign . ";font-weight:" . $ScrollTextWeight . ";font-size:" . $ScrollTextSize . "px;'>" . $producthelper->getProductFormattedPrice($productPriceDiscount) . "</td></tr>"; ?>
					<?php $html .= "<tr><td id='mod_redsavedprice' class='mod_redsavedprice' style='text-align:" . $ScrollTextAlign . ";font-weight:" . $ScrollTextWeight . ";font-size:" . $ScrollTextSize . "px;'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</td></tr>"; ?>
				<?php else: ?>
					<?php $productPrice = $productPriceDiscount; ?>
					<?php $html .= "<tr><td class='mod_redproducts_price' style='text-align:" . $ScrollTextAlign . ";font-weight:" . $ScrollTextWeight . ";font-size:" . $ScrollTextSize . "px;'>" . $producthelper->getProductFormattedPrice($productPrice) . "</td></tr>"; ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php $html .= $display_text; ?>
	<?php endif; ?>
<?php endif ?>

<?php if ($showAddToCart == 'yes'): ?>
	<?php $addtocartData = $producthelper->replaceCartTemplate($row->product_id, $category_id, 0, 0, "", false, array(), 0, 0, 0, $module_id); ?>
	<?php $html .= "<tr><td style='text-align:" . $ScrollTextAlign . ";font-weight:" . $ScrollTextWeight . ";font-size:" . $ScrollTextSize . "px;'>" . $addtocartData . "</td></tr>"; ?>
<?php endif; ?>

<?php echo $html; ?>
