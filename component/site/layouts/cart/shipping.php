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

$extra_section = ($shippingaddresses->is_company == 1) ? extraField::SECTION_COMPANY_SHIPPING_ADDRESS : extraField::SECTION_PRIVATE_SHIPPING_ADDRESS;
?>

<div class="redshop-shippingaddresses">
	<?php if ($shippingaddresses->is_company == 1) : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_COMPANY_NAME'); ?>:</label>
            <div class="col-xs-7"><?php echo $shippingaddresses->company_name; ?></div>
        </div>
	<?php endif; ?>

    <div class="row">
        <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:</label>
        <div class="col-xs-7"><?php echo $shippingaddresses->firstname; ?></div>
    </div>

    <div class="row">
        <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</label>
        <div class="col-xs-7"><?php echo $shippingaddresses->lastname; ?></div>
    </div>

	<?php if ($shippingaddresses->address != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</label>
            <div class="col-xs-7"><?php echo $shippingaddresses->address; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($shippingaddresses->zipcode != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</label>
            <div class="col-xs-7"><?php echo $shippingaddresses->zipcode; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($shippingaddresses->city != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</label>
            <div class="col-xs-7"><?php echo $shippingaddresses->city; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($shippingaddresses->country_code != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</label>
            <div class="col-xs-7"><?php echo JText::_(RedshopHelperOrder::getCountryName($shippingaddresses->country_code)) ?></div>
        </div>
	<?php endif; ?>

	<?php $state = RedshopHelperOrder::getStateName($shippingaddresses->state_code, $shippingaddresses->country_code); ?>
	<?php if ($state != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</label>
            <div class="col-xs-7"><?php echo $state; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($shippingaddresses->phone != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</label>
            <div class="col-xs-7"><?php echo $shippingaddresses->phone; ?></div>
        </div>
	<?php endif; ?>

	<?php echo RedshopHelperExtrafields::listAllFieldDisplay($extra_section, $shippingaddresses->users_info_id) ?>
</div>
