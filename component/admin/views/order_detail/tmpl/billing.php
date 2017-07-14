<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$billing      = $this->billing;
$is_company   = $billing->is_company;
$allowCompany = '';

if ($is_company != 1)
{
	$allowCompany = 'style="display:none;"';
}

$extra_field = extra_field::getInstance();

if (!isset($billing->order_info_id))
	$billing->order_info_id = 0;

$Itemid = JRequest::getVar('Itemid');
?>
<script type="text/javascript">

    function validateInfo() {

        var frm = document.updateBillingAdd;

        if (frm.firstname.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME')?>");
            return false;
        }
        if (frm.lastname.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME')?>");
            return false;
        }
        if (frm.address.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS')?>");
            return false;
        }
        if (frm.zipcode.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ZIPCODE')?>");
            return false;
        }
        if (frm.city.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_CITY')?>");
            return false;
        }

        if (frm.phone.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_PHONE')?>");
            return false;
        }

        if (frm.user_email.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS')?>");
            return false;
        }
        else {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var testEmail = re.test(frm.user_email.value);

            if (!testEmail) {
                alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS')?>");

                return false;
            }

            return testEmail;
        }


        return true;
    }

</script>
<form action="index.php?option=com_redshop" method="post" name="updateBillingAdd" id="updateBillingAdd">
    <div class="col50">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_REDSHOP_BILLING_INFORMATION'); ?></legend>
            <table class="admintable table table-striped">
                <tr>
                    <td width="100" align="right" class="key"><label><?php echo JText::_('COM_REDSHOP_REGISTER_AS'); ?>:</label></td>
                    <td><?php echo $this->lists['is_company']; ?></td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
							<?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="firstname" size="32" maxlength="250"
                               value="<?php echo $billing->firstname; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
							<?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="lastname" size="32" maxlength="250"
                               value="<?php echo $billing->lastname; ?>"/>
                    </td>
                </tr>
                <tr id="trCompanyName" <?php echo $allowCompany; ?>>
                    <td width="100" align="right" class="key"><label><?php echo JText::_('COM_REDSHOP_COMPANY_NAME'); ?>:</label></td>
                    <td><input class="form-control" type="text" name="company_name"
                               value="<?php echo $billing->company_name; ?>" size="32" maxlength="250"/></td>
                </tr>

                <tr>
                    <td width="100" align="right" class="key">
                        <label>
							<?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="address" size="32" maxlength="250"
                               value="<?php echo @$billing->address; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="address">
							<?php echo JText::_('COM_REDSHOP_ZIP'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="zipcode" size="32" maxlength="250"
                               value="<?php echo @$billing->zipcode; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
							<?php echo JText::_('COM_REDSHOP_CITY'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="city" size="32" maxlength="250"
                               value="<?php echo @$billing->city; ?>"/>
                    </td>
                </tr>
                <tr <?php if ($this->showcountry == 0) echo " style='display:none;'"; ?>>
                    <td width="100" align="right" class="key">
                        <label for="contact_info">
							<?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:
                        </label>
                    </td>
                    <td>
						<?php echo $this->lists['country_code']; ?>
                    </td>
                </tr>
                <tr id="div_state_txt" <?php if ($this->showstate == 0) echo " style='display:none;'"; ?> >
                    <td width="100" align="right" class="key">
                        <label for="address">
							<?php echo JText::_('COM_REDSHOP_STATE'); ?>:
                        </label>
                    </td>
                    <td>
						<?php echo $this->lists['state_code']; ?>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
							<?php echo JText::_('COM_REDSHOP_PHONE'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="phone" size="32" maxlength="250"
                               value="<?php echo @$billing->phone; ?>"/>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key"><label><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</label></td>
                    <td><input class="form-control" type="text" name="user_email" size="32" maxlength="250"
                               value="<?php echo @$billing->user_email; ?>"/></td>
                </tr>
                <tr id="trEANnumber" <?php echo $allowCompany; ?>>
                    <td width="100" align="right" class="key"><label><?php echo JText::_('COM_REDSHOP_EAN_NUMBER'); ?>:</label></td>
                    <td><input class="form-control" type="text" name="ean_number" value="<?php echo $billing->ean_number; ?>"
                               size="32" maxlength="250"/></td>
                </tr>
				<?php
				if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1)
				{
					?>
                    <tr id="trVatNumber" <?php echo $allowCompany; ?>>
                        <td valign="top" align="right" class="key"><label><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:</label></td>
                        <td><input class="text_area" type="text" name="vat_number" id="vat_number"
                                   value="<?php echo $billing->vat_number; ?>" size="20" maxlength="250"/>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_VAT_NUMBER'), JText::_('COM_REDSHOP_VAT_NUMBER'), 'tooltip.png', '', '', false); ?>
                        </td>
                    </tr>
                    <tr id="trTaxExempt" <?php echo $allowCompany; ?>>
                        <td valign="top" align="right" class="key"><label><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT'); ?>:</label></td>
                        <td><?php echo $this->lists['tax_exempt'];
							echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TAX_EXEMPT'), JText::_('COM_REDSHOP_TAX_EXEMPT'), 'tooltip.png', '', '', false); ?></td>
                    </tr>
                    <tr id="trTaxExemptRequest" <?php echo $allowCompany; ?>>
                        <td valign="top" class="key"><label><?php echo JText::_('COM_REDSHOP_USER_REQUEST_TAX_EXEMPT_LBL'); ?>:</label></td>
                        <td><?php echo $this->lists['requesting_tax_exempt']; ?>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_USER_REQUEST_TAX_EXEMPT'), JText::_('COM_REDSHOP_USER_REQUEST_TAX_EXEMPT_LBL'), 'tooltip.png', '', '', false); ?></td>
                    </tr>
                    <tr id="trTaxExemptApproved" <?php echo $allowCompany; ?>>
                        <td valign="top" class="key"><label><?php echo JText::_('COM_REDSHOP_TEX_EXEMPT_APPROVED'); ?>:</label></td>
                        <td><?php echo $this->lists['tax_exempt_approved']; ?>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TEX_EXEMPT_APPROVED'), JText::_('COM_REDSHOP_TEX_EXEMPT_APPROVED'), 'tooltip.png', '', '', false); ?></td>
                    </tr>
					<?php
				}

				if ($is_company)
				{
					echo $extra_field->list_all_field(8, $billing->users_info_id);
				}
				else
				{
					echo $extra_field->list_all_field(7, $billing->users_info_id);
				}

				?>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="submit" value="<?php echo JText::_('COM_REDSHOP_SAVE'); ?>" class="btn btn-primary"
                               onclick="return validateInfo();">
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div class="clr"></div>
    <input type="hidden" name="task" value="updateBillingAdd"/>
    <input type="hidden" name="view" value="order_detail"/>
    <input type="hidden" name="cid[]" value="<?php echo $this->detail->order_id; ?>"/>
    <input type="hidden" name="order_info_id" value="<?php echo $billing->order_info_id; ?>"/>
    <input type="hidden" name="user_id" value="<?php echo $billing->user_id; ?>"/>
    <input type="hidden" name="users_info_id" value="<?php echo $billing->users_info_id; ?>"/>
    <input type="hidden" name="shopper_group_id" value="<?php echo $billing->shopper_group_id; ?>"/>
    <input type="hidden" name="approved" value="<?php echo $billing->approved; ?>"/>
    <input type="hidden" name="address_type" value="BT"/>
</form>
