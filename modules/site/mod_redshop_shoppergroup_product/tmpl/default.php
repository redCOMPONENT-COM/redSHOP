<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergroup_product
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHtml::stylesheet('mod_redshop_shoppergroup_product/products.css', false, true);
JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);
JHTML::script('com_redshop/redbox.js', false, true);
?>

<div class='mod_redshop_shoppergroup_product_wrapper'>

<?php foreach ($rows as $row): ?>
	<?php $attributesSet = []; ?>

	<?php if ($row->attribute_set_id > 0): ?>
		<?php $attributesSet = $productHelper->getProductAttribute(0, $row->attribute_set_id, 0, 1); ?>
	<?php endif ?>

	<?php $attributes = $productHelper->getProductAttribute($row->product_id); ?>
	<?php $attributes = array_merge($attributes, $attributesSet); ?>
	<?php $totalAtt   = count($attributes); ?>

	
	<!-- Collecting extra fields -->

	<?php $countNumberUserField = 0; ?>
	<?php $hiddenUserfield      = ""; ?>
	<?php $userfieldArr         = array(); ?>

	<?php if (Redshop::getConfig()->get('AJAX_CART_BOX')): ?>
		<?php $ajaxDetailTemplateDesc = ""; ?>
		<?php $ajaxDetailTemplate     = $productHelper->getAjaxDetailboxTemplate($row); ?>

		<?php if (count($ajaxDetailTemplate) > 0): ?>
			<?php $ajaxDetailTemplateDesc = $ajaxDetailTemplate->template_desc; ?>
		<?php endif ?>

		<?php $returnArr          = $productHelper->getProductUserfieldFromTemplate($ajaxDetailTemplateDesc); ?>
		<?php $templateUserField  = $returnArr[0]; ?>
		<?php $userfieldArr       = $returnArr[1]; ?>

		<?php if ($templateUserField != ""): ?>
			<?php $ufield = ""; ?>

			<?php for ($ui = 0; $ui < count($userfieldArr); $ui++): ?>
				<?php $productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 12, '', '', 0, $row->product_id); ?>
				<?php $ufield .= $productUserFields[1]; ?>

				<?php if ($productUserFields[1] != ""): ?>
					<?php $countNumberUserField++; ?>
				<?php endif ?>

				<?php $templateUserField = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $templateUserField); ?>
				<?php $templateUserField = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $templateUserField); ?>
			<?php endfor ?>

			<?php if ($ufield != ""): ?>
				<?php $hiddenUserfield = "<div style='display:none;'><form method='post' action='' id='user_fields_form_" . $row->product_id . "' name='user_fields_form_" . $row->product_id . "'>" . $templateUserField . "</form></div>"; ?>
			<?php endif ?>
		<?php endif ?>
	<?php endif ?>

	<?php $itemData = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $row->product_id); ?>

	<?php if (count($itemData) > 0): ?>
		<?php $itemId = $itemData->id; ?>
	<?php else: ?>
		<?php $itemId = $redHelper->getItemid($row->product_id); ?>
	<?php endif ?>

	<?php $link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id . '&Itemid=' . $itemId); ?>

	<div class='mod_redshop_shoppergroup_product'>

	<?php if ($image): ?>
		<?php $thumbImage = $productHelper->getProductImage($row->product_id, $link, $thumbWidth, $thumbHeight); ?>
		<div class='mod_redshop_shoppergroup_product_image'><?php echo $thumbImage ?></div>
	<?php endif ?>

	<div class='mod_redshop_shoppergroup_product_title'><a href='<?php echo $link ?>' title=''>
		<?php echo ($params->get('crop_title_length') == 0) ? $row->product_name : trim(substr($row->product_name, 0, $params->get('crop_title_length'))) . $params->get('post_text'); ?>
	</a></div>

	<?php if ($showShortDescription): ?>
		<div class='mod_redshop_shoppergroup_product_desc'><?php echo $row->product_s_desc ?></div>
	<?php endif ?>

	<?php $productPrice 		  = $productHelper->getProductPrice($row->product_id, $showVat); ?>
	<?php $productArr           = $productHelper->getProductNetPrice($row->product_id); ?>
	<?php $productPriceDiscount = $productArr['productPrice'] + $productArr['productVat']; ?>

	<?php if (!$row->not_for_sale && $showPrice): ?>
		<?php if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE))): ?>
			<?php if (!$productPrice): ?>
				<?php $productPriceDis = $productHelper->getPriceReplacement($productPrice); ?>
			<?php else: ?>
				<?php $productPriceDis = $productHelper->getProductFormattedPrice($productPrice); ?>
			<?php endif ?>

			<?php if ($row->product_on_sale && $productPriceDiscount > 0): ?>
				<?php if ($productPrice > $productPriceDiscount): ?>
					<?php $userPrice     = $productPrice - $productPriceDiscount; ?>

					<?php if ($showDiscountPriceLayout): ?>
						<div id='mod_redoldprice' class='mod_redoldprice'><span style='text-decoration:line-through;'><?php echo $productHelper->getProductFormattedPrice($productPrice) ?></span></div>
						<?php $productPrice = $productPriceDiscount; ?>
						<div id='mod_redmainprice' class='mod_redmainprice'><?php echo $productHelper->getProductFormattedPrice($productPriceDiscount) ?></div>
						<div id='mod_redsavedprice' class='mod_redsavedprice'><?php echo JText::_('MOD_REDSHOP_SHOPPERGROUP_PRODUCT_PRODCUT_PRICE_YOU_SAVED') . ' ' . $productHelper->getProductFormattedPrice($userPrice) ?></div>
					<?php else: ?>
						<?php $productPrice = $productPriceDiscount; ?>
						<div class='mod_redshop_shoppergroup_product_price'><?php echo $productHelper->getProductFormattedPrice($productPrice) ?></div>
					<?php endif ?>
				<?php endif ?>
			<?php endif ?>

			<div class='mod_redshop_shoppergroup_product_price'><?php echo $productPriceDis ?></div>
		<?php endif ?>
	<?php endif ?>

	<?php if ($showReadmore): ?>
		<div class='mod_redshop_shoppergroup_product_readmore'><a href='<?php echo $link ?>'><?php echo JText::_('MOD_REDSHOP_SHOPPERGROUP_PRODUCT_TXT_READ_MORE') ?></a>&nbsp;</div>
	<?php endif ?>

	<?php if ($showAddToCart): ?>
		<?php $addToCart = $productHelper->replaceCartTemplate($row->product_id, $row->category_id, 0, 0, "", false, $userfieldArr, $totalAtt, $row->total_accessories, $countNumberUserField, $moduleId); ?>
		<div class='mod_redshop_shoppergroup_product_addtocart'><?php echo $addToCart . $hiddenUserfield ?></div>
	<?php endif ?>

	</div>
<?php endforeach ?>

</div>
