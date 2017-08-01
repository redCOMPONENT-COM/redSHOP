<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

$productHelper = productHelper::getInstance();

?>
<?php foreach ($itemAttdata as $item) : ?>
	<div class="checkout_attribute_title">
		<?php echo $item->section_name ?>

		<?php
			$propData = RedshopHelperQuotation::getQuotationItemAttributeDetail(
				$quotationItemId,
				$isAccessory,
				"property",
				$item->section_id
			);
		?>

		<?php foreach ($propData as $property) : ?>
			<div class="checkout_attribute_price">
			<?php echo $property->section_name ?>

			<?php if ($quotationStatus != 1 || ($quotationStatus == 1 && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE') == 1)) : ?>
			<?php
				$propertyOprand       = $property->section_oprand;
				$propertyPrice        = $productHelper->getProductFormattedPrice($property->section_price);
				$propertyPriceWithVat = $productHelper->getProductFormattedPrice($property->section_price + $property->section_vat);

				echo "(" . $propertyOprand . " " . $propertyPrice . " excl. vat / " . $propertyPriceWithVat . ")";
			?>
			<?php endif; ?>
			</div>

			<?php
				$subpropdata = RedshopHelperQuotation::getQuotationItemAttributeDetail(
					$quotationItemId,
					$isAccessory,
					"subproperty",
					$property->section_id
				);
			?>

			<?php foreach ($subpropdata as $subProperty) : ?>
				<div class="checkout_subattribute_price">
					<?php echo $subProperty->section_name ?>

					<?php if ($quotation_status != 1 || ($quotation_status == 1 && Redshop::getConfig()->get('SHOW_QUOTATION_PRICE') == 1)) : ?>
					<?php
						$subpropertyOprand       = $subProperty->section_oprand;
						$subpropertyPrice        = $productHelper->getProductFormattedPrice($subProperty->section_price);
						$subpropertyPriceWithVat = $productHelper->getProductFormattedPrice($subProperty->section_price + $subProperty->section_vat);

						echo "(" . $subpropertyOprand . " " . $subpropertyPrice . " excl. vat / " . $subpropertyPriceWithVat . ")";
					?>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
<?php endforeach; ?>