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
 * =============================
 * @var  array  $displayData      Display data
 * @var  object $billingAddresses Billing addresses
 */
extract($displayData);

$extraSections = ($billingAddresses->is_company == 1) ?
	RedshopHelperExtrafields::SECTION_COMPANY_BILLING_ADDRESS : RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS;
?>

<div class="redshop-billingaddresses">
	<?php if ($billingAddresses->is_company == 1) : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_COMPANY_NAME') ?>:</label>
            <div class="col-xs-7"><?php echo $billingAddresses->company_name; ?></div>
        </div>
	<?php endif; ?>

    <div class="row">
        <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:</label>
        <div class="col-xs-7"><?php echo $billingAddresses->firstname; ?></div>
    </div>

    <div class="row">
        <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</label>
        <div class="col-xs-7"><?php echo $billingAddresses->lastname; ?></div>
    </div>

	<?php if (!empty($billingAddresses->address)) : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingAddresses->address; ?></div>
        </div>
	<?php endif; ?>

	<?php if (!empty($billingAddresses->zipcode)) : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingAddresses->zipcode; ?></div>
        </div>
	<?php endif; ?>

	<?php if (!empty($billingAddresses->city)) : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingAddresses->city; ?></div>
        </div>
	<?php endif; ?>

	<?php if (!empty($billingAddresses->country_code)): ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_COUNTRY') ?>:</label>
            <div class="col-xs-7"><?php echo JText::_(RedshopHelperOrder::getCountryName($billingAddresses->country_code)) ?></div>
        </div>
	<?php endif; ?>

	<?php $state = RedshopHelperOrder::getStateName($billingAddresses->state_code, $billingAddresses->country_code); ?>
	<?php if (!empty($state)) : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_STATE') ?>:</label>
            <div class="col-xs-7"><?php echo $state ?></div>
        </div>
	<?php endif; ?>

	<?php if (!empty($billingAddresses->phone)) : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingAddresses->phone; ?></div>
        </div>
	<?php endif; ?>

	<?php if (!empty($billingAddresses->user_email)) : ?>
        <div class="row">
            <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</label>
            <div class="col-xs-7"><?php echo $billingAddresses->user_email ? $billingAddresses->user_email : $user->email; ?></div>
        </div>
	<?php endif; ?>

	<?php if ($billingAddresses->is_company == 1) : ?>

		<?php if (!empty($billingAddresses->ean_number)) : ?>
            <div class="row">
                <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_EAN_NUMBER'); ?>:</label>
                <div class="col-xs-7"><?php echo $billingAddresses->ean_number; ?></div>
            </div>
		<?php endif; ?>
		<?php if (!empty($billingAddresses->requisition_number)) : ?>
            <div class="row">
                <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER'); ?>:</label>
                <div class="col-xs-7"><?php echo $billingAddresses->requisition_number ?></div>
            </div>
		<?php endif; ?>

		<?php if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1) : ?>
            <div class="row">
                <label class="col-xs-5"><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:</label>
                <div class="col-xs-7"><?php echo $billingAddresses->vat_number; ?></div>
            </div>
		<?php endif; ?>

		<?php if (Redshop::getConfig()->get('SHOW_TAX_EXEMPT_INFRONT')) : ?>
			<?php
			if ($billingAddresses->tax_exempt == 1)
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
	<?php echo RedshopHelperExtrafields::listAllFieldDisplay($extraSections, $billingAddresses->users_info_id) ?>
</div>
