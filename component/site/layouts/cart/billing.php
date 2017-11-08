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
 * Layout variables
 * =============================
 * @var  array   $displayData       Display data
 * @var  object  $billingaddresses  Billing addresses
 */
extract($displayData);

$extraSections = ($billingaddresses->is_company == 1) ? extraField::SECTION_COMPANY_BILLING_ADDRESS : extraField::SECTION_PRIVATE_BILLING_ADDRESS;

?>

<div class="redshop-billingaddresses">
	<?php if ($billingaddresses->is_company == 1) : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_COMPANY_NAME') ?>:</label>
            <div class="col-xs-7"><?php echo $billingaddresses->company_name; ?></div>
        </div>
	<?php endif; ?>

    <div class="row">
        <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:</label>
        <div class="col-xs-7"><?php echo $billingaddresses->firstname; ?></div>
    </div>

    <div class="row">
        <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</label>
        <div class="col-xs-7"><?php echo $billingaddresses->lastname; ?></div>
    </div>

	<?php if ($billingaddresses->address != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingaddresses->address; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($billingaddresses->zipcode != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingaddresses->zipcode; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($billingaddresses->city != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingaddresses->city; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($billingaddresses->country_code != ""): ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_COUNTRY') ?>:</label>
            <div class="col-xs-7"><?php echo JText::_(RedshopHelperOrder::getCountryName($billingaddresses->country_code)) ?></div>
        </div>
	<?php endif; ?>

	<?php $state = RedshopHelperOrder::getStateName($billingaddresses->state_code, $billingaddresses->country_code); ?>
	<?php if ($state != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_STATE') ?>:</label>
            <div class="col-xs-7"><?php echo $state ?></div>
        </div>
	<?php endif; ?>

	<?php if ($billingaddresses->phone != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingaddresses->phone; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($billingaddresses->user_email != "") : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingaddresses->user_email ? $billingaddresses->user_email : $user->email; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($billingaddresses->is_company == 1) : ?>

		<?php if ($billingaddresses->ean_number != "") : ?>
            <div class="row">
                <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_EAN_NUMBER'); ?>:</label>
                <div class="col-xs-7"><?php echo $billingaddresses->ean_number; ?></div>
            </div>

            <div class="row">
                <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER'); ?>:</label>
                <div class="col-xs-7"><?php echo $billingaddresses->requisition_number; ?></div>
            </div>
		<?php endif; ?>

		<?php if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1) : ?>
            <div class="row">
                <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:</label>
                <div class="col-xs-7"><?php echo $billingaddresses->vat_number; ?></div>
            </div>
		<?php endif; ?>

		<?php if (Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT')) : ?>
			<?php
			if ($billingaddresses->tax_exempt == 1)
			{
				$tax_exempt = JText::_('COM_REDSHOP_TAX_YES');
			}
			else
			{
				$tax_exempt = JText::_('COM_REDSHOP_TAX_NO');
			}
			?>

            <div class="row">
                <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT'); ?>:</label>
                <div class="col-xs-7"><?php echo $tax_exempt; ?></div>
            </div>
		<?php endif; ?>
	<?php endif; ?>
	<?php echo RedshopHelperExtrafields::listAllFieldDisplay($extraSections, $billingaddresses->users_info_id) ?>
</div>
