<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);
$productHelper = productHelper::getInstance();
$orderFunctions   = order_functions::getInstance();
?>
<?php for ($i = 0, $in = count($orderItemAttdata); $i < $in; $i++) : ?>

    <?php $attribute = $productHelper->getProductAttribute(0, 0, $orderItemAttdata[$i]->section_id); ?>
    <?php $hideAttributePrice = 0; ?>
    <?php if (count($attribute) > 0) : ?>
        <?php $hideAttributePrice = $attribute[0]->hide_attribute_price; ?>
    <?php endif; ?>
    <?php if (strpos($data, '{remove_product_attribute_title}') === false) : ?>
        <?php if (!empty($orderItemAttdata[$i]->section_name)) : ?>
            <div class="checkout_attribute_title">
                <?php echo urldecode($orderItemAttdata[$i]->section_name); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($orderItemAttdata[$i]->section_number)) : ?>
            <div class="checkout_attribute_number">
                <?php echo urldecode($orderItemAttdata[$i]->section_number); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php $orderPropdata = RedshopHelperOrder::getOrderItemAttributeDetail($orderItemId, $isAccessory, "property", $orderItemAttdata[$i]->section_id); ?>
    <?php for ($p = 0, $pn = count($orderPropdata); $p < $pn; $p++) : ?>
        <?php $property = RedshopHelperProduct_Attribute::getAttributeProperties($orderPropdata[$p]->section_id, $orderPropdata[$p]->parent_section_id, 0, 0, 0, 0, false); ?>
        <?php $virtualNumber = ""; ?>
        <?php if (!empty($chktag)) : ?>
            <?php $propertyPrice = $orderPropdata[$p]->section_price + $orderPropdata[$p]->section_vat; ?>
        <?php endif; ?>
        <?php if ($export == 1) : ?>
            <?php $disPrice = " (" . $orderPropdata[$p]->section_oprand . Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . $propertyPrice . ")"; ?>
        <?php else: ?>
            <?php $disPrice = ""; ?>
            <?php if (!$hideAttributePrice) : ?>
                <?php $disPrice = " (" . $orderPropdata[$p]->section_oprand . $productHelper->getProductFormattedPrice($propertyPrice) . ")"; ?>
            <?php endif; ?>
            <?php $propertyOperand = $orderPropdata[$p]->section_oprand; ?>
            <?php if (strpos($data, '{product_attribute_price}') === false) : ?>
                <?php $disPrice = ""; ?>
            <?php endif; ?>
            <?php if (strpos($data, '{product_attribute_number}') === false) : ?>
                <?php $virtualNumber = ""; ?>
            <?php endif; ?>
        <?php endif; ?>
        <div class="checkout_attribute_wrapper">
            <?php if (!empty($orderPropdata[$p]->section_name)) : ?>
                <div class="checkout_attribute_price">
                    <?php echo urldecode($orderPropdata[$p]->section_name) . $disPrice; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($orderPropdata[$p]->property_number)) : ?>
                <div class="checkout_attribute_number">
                    <?php echo urldecode($orderPropdata[$p]->property_number) . $disPrice; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($property[$p]->property_number)) :?>
                <div class="checkout_attribute_number">
                    <?php echo $property[$p]->property_number; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php $orderSubpropdata = RedshopHelperOrder::getOrderItemAttributeDetail($orderItemId, $isAccessory, "subproperty", $orderPropdata[$p]->section_id); ?>
        <?php if (!empty($orderSubpropdata[0]->section_id)) : ?>
            <?php for ($sp = 0; $sp < count($orderSubpropdata); $sp++) : ?>
                <?php $subPropertyPrice = $orderSubpropdata[$sp]->section_price; ?>
                <?php $subProperty = RedshopHelperProduct_Attribute::getAttributeSubProperties($orderSubpropdata[$sp]->section_id, $orderSubpropdata[$sp]->parent_section_id, false); ?>
                <?php if (!empty($chktag)) : ?>
                    <?php $subPropertyPrice = $orderSubpropdata[$sp]->section_price + $orderSubpropdata[$sp]->section_vat; ?>
                <?php endif; ?>
                <?php if ($export == 1) : ?>
                    <?php $disPrice = " (" . $orderSubpropdata[$p]->section_oprand . Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . $subPropertyPrice . ")"; ?>
                <?php else: ?>
                    <?php $disPrice = ""; ?>
                    <?php if (!$hideAttributePrice) : ?>
                        <?php $disPrice = " (" . $orderSubpropdata[$p]->section_oprand . $productHelper->getProductFormattedPrice($subPropertyPrice) . ")"; ?>
                    <?php endif; ?>
                    <?php $subPropertyOperand = $orderSubpropdata[$p]->section_oprand; ?>
                    <?php if (strpos($data, '{product_attribute_price}') === false) : ?>
                        <?php $disPrice = ""; ?>
                    <?php endif; ?>
                    <?php if (strpos($data, '{product_attribute_number}') === false) : ?>
                        <?php $virtualNumber = ""; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (strpos($data, '{remove_product_subattribute_title}') === false) : ?>
                    <?php if (!empty($subProperty[$sp]->subattribute_color_title)) : ?>
                        <div class="checkout_subattribute_title">
                            <?php echo urldecode($subProperty[$sp]->subattribute_color_title); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (!empty($subProperty[$sp]->subattribute_color_name)) : ?>
                    <div class="checkout_subattribute_color_name">
                        <?php echo urldecode($subProperty[$sp]->subattribute_color_name); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($subProperty[$sp]->subattribute_color_number)) : ?>
                    <div class="checkout_subattribute_color_number">
                        <?php echo urldecode($subProperty[$sp]->subattribute_color_number); ?>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        <?php endif; ?>
    <?php endfor; ?>
<?php endfor; ?>