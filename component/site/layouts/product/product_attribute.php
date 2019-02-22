<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * ======================================
 * @var   integer  $displayData       Layout data
 * @var   integer  $displayAttribute  Display attributes
 * @var   string   $data              HTML content
 * @var   array    $attributes        Attributes data
 */
extract($displayData);
$productHelper = productHelper::getInstance();
?>
<?php if ($displayAttribute > 0) : ?>
    <div class='checkout_attribute_static'><?php echo JText::_("COM_REDSHOP_ATTRIBUTE"); ?></div>
	<?php for ($i = 0, $in = count($attributes); $i < $in; $i++) : ?>
		<?php $properties = !empty($attributes[$i]['attribute_childs']) ? $attributes[$i]['attribute_childs'] : array(); ?>
		<?php $hideAttributePrice = 0; ?>
		<?php $attribute = $productHelper->getProductAttribute(0, 0, $attributes[$i]['attribute_id']); ?>
		<?php if (!empty($attribute)) : ?>
			<?php $hideAttributePrice = $attribute[0]->hide_attribute_price; ?>
		<?php endif; ?>
		<?php if (count($properties) > 0) : ?>
            <div class="checkout_attribute_title">
				<?php echo urldecode($attributes[$i]['attribute_name']); ?>
            </div>
		<?php endif; ?>
		<?php for ($k = 0, $kn = count($properties); $k < $kn; $k++) : ?>
			<?php
            $property         = RedshopHelperProduct_Attribute::getAttributeProperties($properties[$k]['property_id']);
			$propertyOperator = $properties[$k]['property_oprand'];
			$propertyPrice    = (isset($properties[$k]['property_price'])) ? $properties[$k]['property_price'] : 0;
			$displayPrice     = " (" . $propertyOperator . " " . $productHelper->getProductFormattedPrice($propertyPrice) . ")";
			?>
			<?php if ((Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')) || $hideAttributePrice): ?>
				<?php $displayPrice = ""; ?>
			<?php endif; ?>
			<?php if (strpos($data, '{product_attribute_price}') === false): ?>
				<?php $displayPrice = ""; ?>
			<?php endif; ?>
			<?php $virtualNumber = ""; ?>
			<?php if (count($property) > 0 && $property[0]->property_number): ?>
				<?php $virtualNumber = "<div class='checkout_attribute_number'>" . $property[0]->property_number . "</div>"; ?>
			<?php endif; ?>
			<?php if (strpos($data, '{product_attribute_number}') === false): ?>
				<?php $virtualNumber = ''; ?>
			<?php endif; ?>
            <div class="checkout_attribute_wrapper">
                <div class="checkout_attribute_price">
					<?php echo urldecode($properties[$k]['property_name']) . $displayPrice; ?>
                </div>
				<?php echo $virtualNumber; ?>
            </div>
			<?php $subProperties = $properties[$k]['property_childs']; ?>
			<?php if (count($subProperties) > 0): ?>
                <div class="checkout_subattribute_title">
					<?php echo urldecode($subProperties[0]['subattribute_color_title']); ?>
                </div>
			<?php endif; ?>
			<?php for ($l = 0, $ln = count($subProperties); $l < $ln; $l++): ?>
				<?php $subPropertyOperator = $subProperties[$l]['subproperty_oprand']; ?>
				<?php $subPropertyPrice = $subProperties[$l]['subproperty_price']; ?>
				<?php $displayPrice = " (" . $subPropertyOperator . " " . $productHelper->getProductFormattedPrice($subPropertyPrice) . ")"; ?>
				<?php if ((Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && !Redshop::getConfig()->get('SHOW_QUOTATION_PRICE')) || $hideAttributePrice): ?>
					<?php $displayPrice = ""; ?>
				<?php endif; ?>
				<?php if (strpos($data, '{product_attribute_price}') === false): ?>
					<?php $displayPrice = ""; ?>
				<?php endif; ?>
				<?php $virtualNumber = ""; ?>
				<?php if (count($subProperties) > 0 && $subProperties[0]->subattribute_color_number): ?>
					<?php $virtualNumber = "<div class='checkout_subattribute_number'>" . $subProperties[0]['subattribute_color_number'] . "</div>"; ?>
				<?php endif; ?>
				<?php if (strpos($data, '{product_attribute_number}') === false): ?>
					<?php $virtualNumber = ''; ?>
				<?php endif; ?>
                <div class="checkout_subattribute_wrapper">
                    <div class="checkout_subattribute_price">
						<?php echo urldecode($subProperties[$l]['subproperty_name']) . $displayPrice; ?>
                    </div>
					<?php echo $subProperties[0]['subattribute_color_number']; ?>
                </div>
			<?php endfor; ?>
		<?php endfor; ?>
	<?php endfor; ?>
<?php endif;
