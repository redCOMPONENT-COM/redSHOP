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
 * @param   object  $subProperties    Subproperties
 * @param   int     $productId        Product id
 * @param   int     $userId           User id
 * @param   int     $propertyId       Product id
 * @param   html    $templateContent  Template Content
 * @param   html    $subPropertyData  SubProperty Data
 */

extract($displayData);
$productHelper = productHelper::getInstance();
$cartTemplate = $productHelper->getAddtoCartTemplate($subPropertyData);
$priceList = array();

foreach ($subProperties as $key => $subProperty)
{
	$subPropertyPrices = RedshopHelperProduct_Attribute::getPropertyPrices($subProperty->value, 'subproperty', $userId);

	foreach ($subPropertyPrices as $key => $subPropertyPrice)
	{
		$priceList[$subPropertyPrice->price_quantity_start][$subPropertyPrice->section_id] = $subPropertyPrice->product_price;
	}
}

?>
<table class="table price_table_override hidden property_table" border="1" property_id="property_table_<?php echo $propertyId; ?>" id="property_table_<?php echo $propertyId; ?>">
    <tr class="sub_properties_name">
        <td></td>
		<?php foreach ($subProperties as $key => $subProperty) : ?>
            <td><?php echo $subProperty->subattribute_color_name; ?></td>
		<?php endforeach; ?>
    </tr>
	<?php $count = 0; ?>
	<?php foreach ($priceList as $quantity => $list) : ?>
        <tr class="<?php echo ($count >= 1) ? 'hidden price-row' : ''; ?> ">
			<?php $count ++;?>
            <td class="quantity"><?php echo $quantity.' stk.'; ?></td>
			<?php foreach ($subProperties as $key => $subProperty) : ?>
				<?php foreach ($list as $subPropertyId => $price) : ?>
					<?php if ($subPropertyId == $subProperty->value) : ?>
                        <td><?php echo number_format($price,2,',',''); ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
        </tr>
	<?php endforeach; ?>
    <tr class="quantity_inputs">
        <td class="antal"></td>
		<?php foreach ($subProperties as $key => $subProperty) : ?>
            <td>
				<?php
				$subPropertyStock = RedshopHelperStockroom::getStockAmountWithReserve($subProperty->value, "subproperty");
				echo $productHelper->replacePropertyAddtoCart(
					$productId, $propertyId, 0, $commonId, $subPropertyStock,
					$subPropertyData, $cartTemplate, $subPropertyData, $subProperty->value
				);
				?>
            </td>
		<?php endforeach; ?>
    </tr>
</table>
