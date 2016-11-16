<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redshop_producttab
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$itemClass      = '';
$containerClass = '';

if (version_compare(JVERSION, '3.0', '<'))
{
	$itemClass      = 'left1';
	$containerClass = 'clearfix';
}

$nbRow = $productPerRow;
$j     = 0;
?>
<?php foreach ($rows as $row): ?>
<?php $j++; ?>
<?php if ($j%$nbRow == 1) : ?>
<div class="row <?php echo $containerClass; ?>">
<?php  endif; ?>
	<div class="span<?php echo (12/$productPerRow); ?> <?php echo $itemClass; ?>">
	<?php $category_id = $row->category_id; ?>
	<?php $ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id); ?>

	<?php if (count($ItemData) > 0): ?>
		<?php $Itemid = $ItemData->id; ?>
	<?php else: ?>
		<?php $Itemid = $redhelper->getItemid($row->product_id); ?>
	<?php endif; ?>

	<?php $link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid); ?>
		<?php if ($image) : ?>
			<div>
				<?php echo $producthelper->getProductImage($row->product_id, $link, $thumbwidth, $thumbheight); ?>
			</div>
		<?php endif; ?>

		<p>
			<a href="<?php echo $link; ?>"><?php echo $row->product_name; ?></a>
		</p>

		<?php if (!$row->not_for_sale && $show_price && !Redshop::getConfig()->get('USE_AS_CATALOG')): ?>
			<?php $productPrice          = $producthelper->getProductPrice($row->product_id); ?>
			<?php $productArr             = $producthelper->getProductNetPrice($row->product_id); ?>
			<?php $productPriceDiscount = $productArr['productPrice'] + $productArr['productVat']; ?>

			<?php if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE))): ?>
				
				<?php if (!$productPrice): ?>
					<?php $productPriceDis = $producthelper->getPriceReplacement($productPrice); ?>
				<?php else: ?>
					<?php $productPriceDis = $producthelper->getProductFormattedPrice($productPrice); ?>
				<?php endif; ?>

				<?php if ($row->product_on_sale && ($productPriceDiscount > 0 && $productPrice > $productPriceDiscount)): ?>
					
					<?php if ($show_discountpricelayout): ?>
						<div id="mod_redoldprice" class="mod_redoldprice">
							<span style="text-decoration:line-through">
								<?php echo $producthelper->getProductFormattedPrice($product_price) ?>
							</span>
						</div>

						<div id="mod_redmainprice" class="mod_redmainprice">
							<?php echo $producthelper->getProductFormattedPrice($productPriceDiscount) ?>
						</div>

						<div id="mod_redsavedprice" class="mod_redsavedprice">
							<?php echo JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($productPrice - $productPriceDiscount) ?>
						</div>
					<?php else: ?>
						<div class="mod_redshop_products_price">
							<?php echo $producthelper->getProductFormattedPrice($productPriceDiscount) ?>
						</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="mod_redshop_products_price"><?php echo $productPriceDis ?></div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif ?>

		<?php if ($show_readmore): ?>
			<br><a href="<?php echo  $link ?>"><?php echo JText::_('MOD_REDPRODUCTTAB_SHOW_READ_MORE') ?></a>&nbsp;
		<?php endif ?>

		<?php if ($show_addtocart): ?>
			<?php $attributes_set = array(); ?>

			<?php if ($row->attribute_set_id > 0): ?>
				<?php $attributes_set = $producthelper->getProductAttribute(0, $row->attribute_set_id, 0, 1); ?>
			<?php endif; ?>

			<?php $attributes 	  = $producthelper->getProductAttribute($row->product_id); ?>
			<?php $attributes 	  = array_merge($attributes, $attributes_set); ?>
			<?php $totalatt   	  = count($attributes); ?>
			<?php $accessory      = $producthelper->getProductAccessory(0, $row->product_id); ?>
			<?php $totalAccessory = count($accessory); ?>

			<?php $count_no_user_field = 0; ?>
			<?php $hidden_userfield    = ""; ?>
			<?php $userfieldArr        = array(); ?>

			<?php if (Redshop::getConfig()->get('AJAX_CART_BOX')): ?>
				<?php $ajax_detail_template_desc = ""; ?>
				<?php $ajax_detail_template      = $producthelper->getAjaxDetailboxTemplate($row); ?>

				<?php if (count($ajax_detail_template) > 0): ?>
					<?php $ajax_detail_template_desc = $ajax_detail_template->template_desc; ?>
				<?php endif; ?>

				<?php $returnArr          = $producthelper->getProductUserfieldFromTemplate($ajax_detail_template_desc); ?>
				<?php $template_userfield = $returnArr[0]; ?>
				<?php $userfieldArr       = $returnArr[1]; ?>

				<?php if ($template_userfield != ""): ?>
					<?php $ufield = ""; ?>

					<?php for ($ui = 0, $countUserFieldArr = count($userfieldArr); $ui < $countUserFieldArr; $ui++): ?>
						<?php $productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id); ?>
						<?php $ufield .= $productUserFields[1]; ?>

						<?php if ($productUserFields[1] != ""): ?>
							<?php $count_no_user_field++; ?>
						<?php endif; ?>

						<?php $template_userfield = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $template_userfield); ?>
						<?php $template_userfield = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $template_userfield); ?>
					<?php endfor; ?>

					<?php if ($ufield != ""): ?>
						<?php $hidden_userfield = "<div style=\"display:none;\"><form method=\"post\" action=\"\" id=\"user_fields_form_" . $row->product_id . "\" name=\"user_fields_form_" . $row->product_id . "\">" . $template_userfield . "</form></div>"; ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php $addtocart = $producthelper->replaceCartTemplate($row->product_id, $category_id, 0, 0, "", false, $userfieldArr, $totalatt, $totalAccessory, $count_no_user_field, $module_id); ?>
			<div class="mod_redshop_products_addtocart"><?php echo $addtocart . $hidden_userfield ?></div>
		<?php endif; ?>
	</div>
<?php if(($j%$nbRow == 0) || ($j == count($rows))) : ?>
</div>
<?php endif; ?>
<?php endforeach; ?>
