<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

JFactory::getApplication()->setUserState('com_redshop.user_detail.data', "");
HTMLHelper::_('bootstrap.tooltip', '.hasTooltip');
$allowCustomer = '';
$allowCompany  = '';

if (!$this->shipping && $this->detail->is_company == 1) {
    $allowCustomer = 'style="display:none;"';
} else {
    $allowCompany = 'style="display:none;"';
}

$countryStyle = (isset($this->showcountry) && $this->showcountry == 0) ? ' style="display:none;" ' : '';
$stateStyle   = (isset($this->showstates) && $this->showstates == 0) ? ' style="display:none;" ' : '';
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo Text::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL'); ?></h3>
    </div>
    <table class="admintable table">
        <tr id="trCompanyName" <?php echo $allowCompany; ?>>
            <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_COMPANY_NAME'); ?>:
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_COMPANY_NAME'),
                    Text::_('COM_REDSHOP_COMPANY_NAME')
                ); ?>
            </td>
            <td>
                <input class="text_area" type="text" name="company_name" value="<?php echo $this->detail->company_name; ?>" size="20" 
                    maxlength="250" />
            </td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key">
                <span id="divFirstname"><?php echo Text::_('COM_REDSHOP_FIRST_NAME'); ?></span>:
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_FIRST_NAME'),
                    Text::_('COM_REDSHOP_FIRST_NAME')
                ); ?>
            </td>
            <td>
                <input class="text_area" type="text" name="firstname" id="firstname" value="<?php echo $this->detail->firstname; ?>" 
                        size="20" maxlength="250" />
                <span class="star text-danger" id="user_valid"> *</span>
            </td>
        </tr>
        <tr id="trLastname">
            <td valign="top" align="right" class="key">
                <?php echo Text::_('COM_REDSHOP_LAST_NAME'); ?>:
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_LAST_NAME'),
                    Text::_('COM_REDSHOP_LAST_NAME')
                ); ?>
            </td>
            <td><input class="text_area" type="text" name="lastname" id="lastname" value="<?php echo $this->detail->lastname; ?>" 
                    size="20" maxlength="250" />
                <span class="star text-danger" id="user_valid"> *</span>
            </td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key">
                <?php echo Text::_('COM_REDSHOP_ADDRESS'); ?>:
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_ADDRESS'),
                    Text::_('COM_REDSHOP_ADDRESS')
                ); ?>
            </td>
            <td>
                <input class="text_area" type="text" name="address" id="address" value="<?php echo $this->detail->address; ?>" 
                    size="20" maxlength="250" />
            </td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key">
                <?php echo Text::_('COM_REDSHOP_CITY'); ?>:
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_CITY'),
                    Text::_('COM_REDSHOP_CITY')
                ); ?>
            </td>
            <td>
                <input class="text_area" type="text" name="city" id="city" value="<?php echo $this->detail->city; ?>" size="20" maxlength="250" />
            </td>
        </tr>
        <tr <?php echo $countryStyle; ?>>
            <td valign="top" align="right" class="key">
                <?php echo Text::_('COM_REDSHOP_COUNTRY'); ?>:
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_COUNTRY'),
                    Text::_('COM_REDSHOP_Country')
                ); ?>
            </td>
            <td>
                <?php echo $this->lists['country_code']; ?>
            </td>
        </tr>
        <tr <?php echo $countryStyle; ?>>
            <td valign="top" align="right" class="key">
                <div id="div_state_lbl" <?php echo $stateStyle; ?>>
                    <?php echo Text::_('COM_REDSHOP_STATE'); ?>:
                    <?php echo HTMLHelper::_(
                        'redshop.tooltip',
                        Text::_('COM_REDSHOP_TOOLTIP_STATE'),
                        Text::_('COM_REDSHOP_State')
                    ); ?>
                </div>
            </td>
            <td>
                <div id="div_state_txt" <?php echo $stateStyle; ?>>
                    <?php echo $this->lists['state_code']; ?>
                </div>
            </td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key">
                <?php echo Text::_('COM_REDSHOP_PHONE'); ?>:
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_PHONE'),
                    Text::_('COM_REDSHOP_PHONE')
                ); ?>
            </td>
            <td>
                <input class="inputbox" type="text" name="phone" id="phone" size="20" value="<?php echo $this->detail->phone; ?>" />
            </td>
        </tr>
        <tr>
            <td valign="top" align="right" class="key">
                <?php echo Text::_('COM_REDSHOP_ZIPCODE'); ?>:
                <?php echo HTMLHelper::_(
                    'redshop.tooltip',
                    Text::_('COM_REDSHOP_TOOLTIP_ZIPCODES'),
                    Text::_('COM_REDSHOP_ZIPCODE')
                ); ?>
            </td>
            <td>
                <input class="inputbox" type="text" name="zipcode" id="zipcode" size="20" value="<?php echo $this->detail->zipcode; ?>" />
            </td>
        </tr>
        <tr id="trEANnumber" <?php echo $allowCompany; ?>>
            <td valign="top" align="right" class="key"><?php echo Text::_('COM_REDSHOP_EAN_NUMBER'); ?>:</td>
            <td><input class="text_area" type="text" name="ean_number" value="<?php echo $this->detail->ean_number; ?>" size="20" maxlength="250" /></td>
        </tr>
        <?php
        if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1) {
        ?>
            <tr id="trVatNumber" <?php echo $allowCompany; ?>>
                <td valign="top" align="right" class="key">
                    <?php echo Text::_('COM_REDSHOP_VAT_NUMBER'); ?>:
                    <?php echo HTMLHelper::_(
                        'redshop.tooltip',
                        Text::_('COM_REDSHOP_TOOLTIP_VAT_NUMBER'),
                        Text::_('COM_REDSHOP_VAT_NUMBER')
                    ); ?>
                </td>
                <td><input class="text_area" type="text" name="vat_number" id="vat_number" value="<?php echo $this->detail->vat_number; ?>" size="20" maxlength="250" />
                </td>
            </tr>
            <tr style="display: none;" id="trTaxExempt" <?php echo $allowCompany; ?>>
                <td valign="top" align="right" class="key">
                    <?php echo Text::_('COM_REDSHOP_TAX_EXEMPT'); ?>:
                    <?php echo HTMLHelper::_(
                        'redshop.tooltip',
                        Text::_('COM_REDSHOP_TOOLTIP_TAX_EXEMPT'),
                        Text::_('COM_REDSHOP_TAX_EXEMPT')
                    ); ?>
                </td>
                <td><?php echo $this->lists['tax_exempt']; ?></td>
            </tr>
            <tr id="trTaxExemptRequest" <?php echo $allowCompany; ?>>
                <td valign="top" class="key">
                    <?php echo Text::_('COM_REDSHOP_USER_REQUEST_TAX_EXEMPT_LBL'); ?>:
                    <?php echo HTMLHelper::_(
                        'redshop.tooltip',
                        Text::_('COM_REDSHOP_TOOLTIP_USER_REQUEST_TAX_EXEMPT'),
                        Text::_('COM_REDSHOP_USER_REQUEST_TAX_EXEMPT_LBL')
                    ); ?>
                </td>
                <td><?php echo $this->lists['requesting_tax_exempt']; ?></td>
            </tr>
            <tr id="trTaxExemptApproved" <?php echo $allowCompany; ?>>
                <td valign="top" class="key">
                    <?php echo Text::_('COM_REDSHOP_TEX_EXEMPT_APPROVED'); ?>:
                    <?php echo HTMLHelper::_(
                        'redshop.tooltip',
                        Text::_('COM_REDSHOP_TOOLTIP_TEX_EXEMPT_APPROVED'),
                        Text::_('COM_REDSHOP_TEX_EXEMPT_APPROVED')
                    ); ?>
                </td>
                <td><?php echo $this->lists['tax_exempt_approved']; ?>
                    <input type="hidden" name="tax_exempt_approved_id" value="<?php echo $this->detail->tax_exempt_approved; ?>" />
                </td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan='2'>
                <?php if (!empty($this->shipping)) : ?>
                    <?php if ($this->detail->is_company == 1) : ?>
                        <?php if ($this->lists['shipping_company_field'] != "") : ?>
                            <div id="exCompanyField"><?php echo $this->lists['shipping_company_field'] ?></div>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php if ($this->lists['shipping_customer_field'] != "") : ?>
                            <div id="exCustomerField"><?php echo $this->lists['shipping_customer_field'] ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else : ?>
                    <?php if ($this->lists['company_field'] != "") : ?>
                        <div id="exCompanyField" <?php echo $allowCompany ?>>
                            <?php echo $this->lists['company_field'] ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->lists['customer_field'] != "") : ?>
                        <div id="exCustomerField" <?php echo $allowCustomer ?>>
                            <?php echo $this->lists['customer_field'] ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php
        if ($this->shipping) {
            $userInfoId = \JFactory::getApplication()->input->getInt('info_id', 0);
            $user       = \Redshop\User\Helper::getUsers([], ['ui.users_info_id' => ['=' => $userInfoId]])[0];
        ?>
            <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
            <input type="hidden" name="user_info_id" value="<?php echo $userInfoId ?>" />
            <input type="hidden" name="email" value="<?php echo $user->email; ?>" />
            <input type="hidden" name="is_company" value="<?php echo $user->is_company; ?>" />
            <input type="hidden" name="shopper_group_id" value="<?php echo $user->shopper_group_id; ?>" />
            <input type="hidden" name="company_name" value="<?php echo $user->company_name; ?>" />
            <input type="hidden" name="ean_number" value="<?php echo $user->ean_number; ?>" />
            <input type="hidden" name="vat_number" value="<?php echo $user->vat_number; ?>" />
            <input type="hidden" name="tax_exempt" value="<?php echo $user->tax_exempt; ?>" />
            <input type="hidden" name="requesting_tax_exempt" value="<?php echo $user->requesting_tax_exempt; ?>" />
            <input type="hidden" name="tax_exempt_approved" value="<?php echo $user->tax_exempt_approved; ?>" />
        <?php
        } ?>
    </table>
</div>