<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   array    $accessories  Accessory Data
 * @param   int      $productId    Product id
 * @param   int      $userId       User id
 * @param   boolean  $checkTag     Check Tag
 */
extract($displayData);
$productHelper = productHelper::getInstance();
?>
<?php if (!empty($accessories)) : ?>
<div class="checkout_accessory_static">
	<?php echo JText::_("COM_REDSHOP_ACCESSORY"); ?>
</div>
<?php endif; ?>
<?php foreach ($accessories as $key => $accessory): ?>
	<?php $accessoryVat = 0; ?>
	<?php $accessoryPrice = $accessory['accessory_price']; ?>
	<?php if ($accessoryPrice > 0): ?>
		<?php $accessoryVat = $productHelper->getProducttax($productId, $accessoryPrice, $userId); ?>
	<?php endif; ?>
	<?php if ($checkTag): ?>
		<?php $accessoryPrice = $accessoryPrice + $accessoryVat; ?>
	<?php endif; ?>
	<?php $displayPrice = " (" . $productHelper->getProductFormattedPrice($accessoryPrice) . ")"; ?>
	<?php if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')): ?>
		<?php $displayPrice = ""; ?>
	<?php endif; ?>
	<div class="checkout_accessory_title">
		<?php echo urldecode($accessory['accessory_name']); ?> <?php echo $displayPrice; ?>
	</div>
	<?php if (!empty($accessory['accessory_childs'])) : ?>
		<?php foreach ($accessory['accessory_childs'] as $attributes): ?>
			<?php if (empty($attributes['attribute_childs'])) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<?php $attribute = $productHelper->getProductAttribute(0, 0, $attributes['attribute_id']); ?>
			<?php $hideAttribute = 0; ?>
			<?php if (count($attribute) > 0): ?>
				<?php $hideAttribute = $attribute[0]->hide_attribute_price; ?>
			<?php endif; ?>
			<div class="checkout_attribute_title">
				<?php echo urldecode($attributes['attribute_name']); ?>: 
			</div>
			<?php foreach ($attributes['attribute_childs'] as $properties): ?>
				<?php $propertyVat = 0; ?>
				<?php $propertyPrice = $properties['property_price']; ?>
				<?php if ($propertyPrice > 0): ?>
					<?php $propertyVat = $productHelper->getProducttax($productId, $propertyPrice, $userId); ?>
				<?php endif; ?>
				<?php if ($checkTag): ?>
					<?php $propertyPrice = $propertyPrice + $propertyVat; ?>
				<?php endif; ?>
				<?php $displayPrice = " (" . $properties['property_oprand'] . ' ' . $productHelper->getProductFormattedPrice($propertyPrice) . ")"; ?>
				<?php if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE') || $hideAttribute): ?>
					<?php $displayPrice = ""; ?>
				<?php endif; ?>
				<?php $property = $productHelper->getAttibuteProperty($properties['property_id']); ?>
				<?php $virtualNumber = ""; ?>
				<?php if (count($property) > 0 && $property[0]->property_number) : ?>
					<?php $virtualNumber = "<div class='checkout_attribute_number'>" . $property[0]->property_number
								. "</div>"; ?>
				<?php endif; ?>
				<div class="checkout_attribute_wrapper">
					<div class="checkout_attribute_price">
						<?php echo urldecode($properties['property_name']); ?> <?php echo $displayPrice; ?>
					</div>
					<?php echo $virtualNumber; ?>
				</div>
				<?php if (!empty($properties['property_childs'])) : ?>
					<?php foreach ($properties['property_childs'] as $subProperties): ?>
						<?php $subPropertyVat = 0; ?>
						<?php $subPropertyPrice = $subProperties['subproperty_price']; ?>
						<?php if ($subPropertyPrice > 0): ?>
							<?php $subPropertyVat = $productHelper->getProducttax($productId, $subPropertyPrice, $userId); ?>
						<?php endif; ?>
						<?php if ($checkTag): ?>
							<?php $subPropertyPrice = $subPropertyPrice + $subPropertyVat; ?>
						<?php endif; ?>
						<?php $displayPrice = " (" . $subProperties['subproperty_oprand'] . ' ' . $productHelper->getProductFormattedPrice($subPropertyPrice) . ")"; ?>
						<?php if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE') || $hideAttribute): ?>
							<?php $displayPrice = ""; ?>
						<?php endif; ?>
						<?php $subProperty = $productHelper->getAttibuteProperty($subProperties['subproperty_id']); ?>
						<?php $virtualNumber = ""; ?>
						<?php if (count($subProperty) > 0 && $subProperty[0]->subattribute_color_number) : ?>
							<?php $virtualNumber = "<div class='checkout_subattribute_number'>[" . $subProperty[0]->subattribute_color_number
										. "]</div>"; ?>
						<?php endif; ?>
						<div class="checkout_subattribute_wrapper">
							<div class="checkout_subattribute_price">
								<?php echo urldecode($subProperties['subproperty_name']); ?> <?php echo $displayPrice; ?>
							</div>
							<?php echo $virtualNumber; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	<?php endif; ?>
<?php endforeach; ?>
