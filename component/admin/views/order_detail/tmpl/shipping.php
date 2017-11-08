<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$billing  = $this->billing;
$shipping = $this->shipping;

if (!$shipping)
{
	$shipping = $billing;
}
if (!isset($shipping->order_info_id))
	$shipping->order_info_id = 0;

$Itemid = JFactory::getApplication()->input->get('Itemid');
?>
<script type="text/javascript">

    function validateInfo() {

        var frm = document.updateShippingAdd;

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

        return true;
    }

</script>
<form action="index.php?option=com_redshop" method="post" name="updateShippingAdd" id="updateShippingAdd">
    <div class="col50">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_REDSHOP_SHIPPING_INFORMATION'); ?></legend>
            <table class="admintable table table-striped">
                <tr>
                    <td width="100" align="right" class="key">
                        <label>
							<?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="firstname" size="32" maxlength="250"
                               value="<?php echo $shipping->firstname; ?>"/>
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
                               value="<?php echo $shipping->lastname; ?>"/>
                    </td>
                </tr>


                <tr>
                    <td width="100" align="right" class="key">
                        <label>
							<?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="address" size="32" maxlength="250"
                               value="<?php echo @$shipping->address; ?>"/>
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
                               value="<?php echo @$shipping->zipcode; ?>"/>
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
                               value="<?php echo @$shipping->city; ?>"/>
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
                               value="<?php echo @$shipping->phone; ?>"/>
                    </td>
                </tr>
                <tr>
					<?php
					$field = extra_field::getInstance();
					if ($shipping->is_company == 1)
					{
						echo $extrafields = $field->list_all_field(15, $shipping->users_info_id);
					}
					else
					{
						echo $extrafields = $field->list_all_field(14, $shipping->users_info_id);
					}
					?>
                </tr>
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
    <input type="hidden" name="task" value="updateShippingAdd"/>
    <input type="hidden" name="view" value="order_detail"/>
    <input type="hidden" name="cid[]" value="<?php echo $this->detail->order_id; ?>"/>
    <input type="hidden" name="order_info_id" value="<?php echo $shipping->order_info_id; ?>"/>
    <input type="hidden" name="user_id" value="<?php echo $shipping->user_id; ?>"/>
    <input type="hidden" name="users_info_id" value="<?php echo $shipping->users_info_id; ?>"/>
    <input type="hidden" name="shopper_group_id" value="<?php echo $shipping->shopper_group_id; ?>"/>
    <input type="hidden" name="tax_exempt_approved" value="<?php echo $shipping->tax_exempt_approved; ?>"/>
    <input type="hidden" name="approved" value="<?php echo $shipping->approved; ?>"/>
    <input type="hidden" name="is_company" value="<?php echo $shipping->is_company; ?>"/>
    <input type="hidden" name="address_type" value="ST"/>


</form>
