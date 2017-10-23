<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$allowCustomer = '';
$allowCompany  = '';

if (!$this->shipping && $this->detail->is_company == 1)
{
	$allowCustomer = 'style="display:none;"';
}
else
{
	$allowCompany = 'style="display:none;"';
}

$countrystyle = (isset($this->showcountry) && $this->showcountry == 0) ? ' style="display:none;" ' : '';
$statestyle   = (isset($this->showstates) && $this->showstates == 0) ? ' style="display:none;" ' : '';
?>
<div class="col50" >
    <table class="admintable table" >
        <tr >
            <td valign="top" align="right" class="key" >
                <span id="divFirstname" ><?php echo JText::_('COM_REDSHOP_FIRST_NAME'); ?></span >:
            </td >
            <td ><input class="text_area" type="text" name="firstname" id="firstname"
                        value="<?php echo $this->detail->firstname; ?>" size="20" maxlength="250" /></td >
        </tr >
        <tr id="trLastname" >
            <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_LAST_NAME'); ?>:</td >
            <td ><input class="text_area" type="text" name="lastname" id="lastname"
                        value="<?php echo $this->detail->lastname; ?>" size="20" maxlength="250" />
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_LAST_NAME'), JText::_('COM_REDSHOP_LAST_NAME'), 'tooltip.png', '', '', false); ?>
            </td >
        </tr >
        <tr id="trCompanyName" <?php echo $allowCompany; ?>>
            <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_COMPANY_NAME'); ?>:</td >
            <td ><input class="text_area" type="text" name="company_name"
                        value="<?php echo $this->detail->company_name; ?>" size="20" maxlength="250" /></td >
        </tr >
        <tr >
            <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</td >
            <td ><input class="text_area" type="text" name="address" id="address"
                        value="<?php echo $this->detail->address; ?>" size="20" maxlength="250" />
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_ADDRESS'), JText::_('COM_REDSHOP_ADDRESS'), 'tooltip.png', '', '', false); ?>
            </td >
        </tr >
        <tr >
            <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td >
            <td ><input class="text_area" type="text" name="city" id="city" value="<?php echo $this->detail->city; ?>"
                        size="20" maxlength="250" />
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CITY'), JText::_('COM_REDSHOP_CITY'), 'tooltip.png', '', '', false); ?>
            </td >
        </tr >
        <tr <?php echo $countrystyle; ?>>
            <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</td >
            <td ><?php echo $this->lists['country_code']; ?>
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_COUNTRY'), JText::_('COM_REDSHOP_Country'), 'tooltip.png', '', '', false); ?></td >
        </tr >
        <tr <?php echo $countrystyle; ?>>
            <td valign="top" align="right" class="key" >
                <div id="div_state_lbl" <?php echo $statestyle; ?>><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</div >
            </td >
            <td >
                <div id="div_state_txt" <?php echo $statestyle; ?>><?php echo $this->lists['state_code']; ?>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_STATE'), JText::_('COM_REDSHOP_State'), 'tooltip.png', '', '', false); ?></div >
            </td >
        </tr >
        <tr >
            <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td >
            <td ><input class="inputbox" type="text" name="phone" id="phone" size="20"
                        value="<?php echo $this->detail->phone; ?>" />
				<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PHONE'), JText::_('COM_REDSHOP_PHONE'), 'tooltip.png', '', '', false); ?>
            </td >
        </tr >
        <tr >
            <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_ZIPCODE'); ?>:</td >
            <td ><input class="inputbox" type="text" name="zipcode" id="zipcode" size="20"
                        value="<?php echo $this->detail->zipcode; ?>" /></td >
        </tr >
        <tr id="trEANnumber" <?php echo $allowCompany; ?>>
            <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_EAN_NUMBER'); ?>:</td >
            <td ><input class="text_area" type="text" name="ean_number" value="<?php echo $this->detail->ean_number; ?>"
                        size="20" maxlength="250" /></td >
        </tr >
		<?php
		if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1)
		{
			?>
            <tr id="trVatNumber" <?php echo $allowCompany; ?>>
                <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:</td >
                <td ><input class="text_area" type="text" name="vat_number" id="vat_number"
                            value="<?php echo $this->detail->vat_number; ?>" size="20" maxlength="250" />
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_VAT_NUMBER'), JText::_('COM_REDSHOP_VAT_NUMBER'), 'tooltip.png', '', '', false); ?>
                </td >
            </tr >
            <tr style="display: none;" id="trTaxExempt" <?php echo $allowCompany; ?>>
                <td valign="top" align="right" class="key" ><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT'); ?>:</td >
                <td ><?php echo $this->lists['tax_exempt'];
					echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TAX_EXEMPT'), JText::_('COM_REDSHOP_TAX_EXEMPT'), 'tooltip.png', '', '', false); ?></td >
            </tr >
            <tr id="trTaxExemptRequest" <?php echo $allowCompany; ?>>
                <td valign="top" class="key" ><?php echo JText::_('COM_REDSHOP_USER_REQUEST_TAX_EXEMPT_LBL'); ?>:</td >
                <td ><?php echo $this->lists['requesting_tax_exempt']; ?>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_USER_REQUEST_TAX_EXEMPT'), JText::_('COM_REDSHOP_USER_REQUEST_TAX_EXEMPT_LBL'), 'tooltip.png', '', '', false); ?></td >
            </tr >
            <tr id="trTaxExemptApproved" <?php echo $allowCompany; ?>>
                <td valign="top" class="key" ><?php echo JText::_('COM_REDSHOP_TEX_EXEMPT_APPROVED'); ?>:</td >
                <td ><?php echo $this->lists['tax_exempt_approved']; ?>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TEX_EXEMPT_APPROVED'), JText::_('COM_REDSHOP_TEX_EXEMPT_APPROVED'), 'tooltip.png', '', '', false); ?>
                    <input type="hidden" name="tax_exempt_approved_id"
                           value="<?php echo $this->detail->tax_exempt_approved; ?>" /></td >
            </tr >
			<?php
		}
		?>
        <tr >
            <td colspan='2' >
				<?php
				if ($this->shipping)
				{
					if ($this->detail->is_company == 1)
					{
						if ($this->lists['shipping_company_field'] != "")
						{
							echo '<div id="exCompanyField">' . $this->lists['shipping_company_field'] . '</div>';
						}
					}
					else
					{
						if ($this->lists['shipping_customer_field'] != "")
						{
							echo '<div id="exCustomerField">' . $this->lists['shipping_customer_field'] . '</div>';
						}
					}
				}
				else
				{
					if ($this->detail->is_company == 1)
                    {
                        if ($this->lists['company_field'] != "")
                        {
                            echo '<div id="exCompanyField" ' . $allowCompany . '>' . $this->lists['company_field'] . '</div>';
                        }
                    }
                    else
                    {
                        if ($this->lists['customer_field'] != "")
                        {
                            echo '<div id="exCustomerField" ' . $allowCustomer . '>' . $this->lists['customer_field'] . '</div>';
                        }
                    }
				} ?></td >
        </tr >
		<?php
		if ($this->shipping)
		{
			?>
            <input type="hidden" name="email" value="<?php echo $this->detail->email; ?>" />
            <input type="hidden" name="is_company" value="<?php echo $this->detail->is_company; ?>" />
            <input type="hidden" name="shopper_group_id" value="<?php echo $this->detail->shopper_group_id; ?>" />
            <input type="hidden" name="company_name" value="<?php echo $this->detail->company_name; ?>" />
            <input type="hidden" name="ean_number" value="<?php echo $this->detail->ean_number; ?>" />
            <input type="hidden" name="vat_number" value="<?php echo $this->detail->vat_number; ?>" />
            <input type="hidden" name="tax_exempt" value="<?php echo $this->detail->tax_exempt; ?>" />
            <input type="hidden" name="requesting_tax_exempt"
                   value="<?php echo $this->detail->requesting_tax_exempt; ?>" />
            <input type="hidden" name="tax_exempt_approved" value="<?php echo $this->detail->tax_exempt_approved; ?>" />
			<?php
		} ?>
    </table >
</div >
