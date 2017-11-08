<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');
?>

<table class="admintable table">
    <tr>
        <td colspan="2" class="vat_intro_text">
			<?php
			echo JText::_('COM_REDSHOP_VAT_INTRO_TEXT');
			?>
        </td>
    </tr>
    <tr>
        <td width="100" align="right" class="key"><span
                    class="editlinktip hasTip"
                    title="<?php
					echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_COUNTRY');
					?>::<?php
					echo JText::_('COM_REDSHOP_DEFAULT_VAT_COUNTRY_LBL');
					?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_COUNTRY_LBL');
			?></label></span></td>
        <td><?php
			echo $this->lists ['default_vat_country'];
			?></td>
    </tr>
    <tr>
        <td width="100" align="right" class="key"><span
                    class="editlinktip hasTip"
                    title="<?php
					echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_STATE');
					?>::<?php
					echo JText::_('COM_REDSHOP_DEFAULT_VAT_STATE_LBL');
					?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_STATE_LBL');
			?></label></span></td>
        <td><?php
			echo $this->lists ['default_vat_state'];
			?></td>
    </tr>
    <tr>
        <td width="100" align="right" class="key"><span
                    class="editlinktip hasTip"
                    title="<?php
					echo JText::_('COM_REDSHOP_TOOLTIP_CALCULATE_VAT_BASED_ON_LBL');
					?>::<?php
					echo JText::_('COM_REDSHOP_CALCULATE_VAT_BASED_ON_LBL');
					?>"> <label for="name">
					<?php
					echo JText::_('COM_REDSHOP_CALCULATE_VAT_BASED_ON_LBL');
					?></label></span></td>
        <td><?php
			echo $this->lists ['calculate_vat_on'];
			?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="price_intro_text">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="price_intro_text">
			<?php
			echo JText::_('COM_REDSHOP_VAT_RATES_INTRO_TEXT');
			?>
        </td>
    </tr>
    <tr>
        <td width="100" align="right" class="key"><span
                    class="editlinktip hasTip"
                    title="<?php
					echo JText::_('COM_REDSHOP_ADD_VAT_RATES_LBL');
					?>::<?php
					echo JText::_('COM_REDSHOP_TOOLTIP_ADD_VAT_RATES_LBL');
					?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_ADD_VAT_RATES_LBL');
			?></label></span></td>
        <td><a class="btn btn-small btn-info"
               href="index.php?option=com_redshop&task=tax_rate.add&tmpl=component"
               target="blank"><?php
				echo JText::_('COM_REDSHOP_ADD_RATES');
				?></a>
        </td>
    </tr>
    <tr>
        <td width="100" align="right" class="key">&nbsp;</td>
        <td>
            <ol>
				<?php
				for ($i = 0; $i < count($this->taxrates); $i++)
				{
					$tax = $this->taxrates [$i];

					$tax_rate_id  = $tax->id;
					$tax_country  = $tax->tax_country;
					$tax_rate     = $tax->tax_rate;
					$tax_group_id = $tax->tax_group_id;

					$rate_html = $tax_country . " (" . $tax_rate . ")";
					?>
                    <li>
                        <a href="index.php?option=com_redshop&task=tag_rate.edit&id=<?php echo $tax_rate_id; ?>" target="_blank">
							<?php echo $rate_html ?>
                        </a>
                        <a onclick="getvatremove(<?php echo $tax_rate_id ?>);" href="javascript:">Remove</a>
                    </li>
					<?php
				}
				?>
                <input type="hidden" name="vattax_rate_id" id="vattax_rate_id">
                <input type="hidden" name="vatremove" value="0" id="vatremove">
            </ol>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="price_intro_text">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="apply_vat_discount_intro_text">
			<?php echo JText::_('COM_REDSHOP_VAT_DISCOUNT_INTRO_TEXT'); ?>
        </td>
    </tr>
    <tr>
        <td width="100" align="right" class="key"><span
                    class="editlinktip hasTip"
                    title="<?php
					echo JText::_('COM_REDSHOP_TOOLTIP_APPLY_VAT_ON_DISCOUNT');
					?>::<?php
					echo JText::_('COM_REDSHOP_APPLY_VAT_ON_DISCOUNT_LBL');
					?>"> <label for="name">
					<?php
					echo JText::_('COM_REDSHOP_APPLY_VAT_ON_DISCOUNT_LBL');
					?></label></span></td>
        <td><?php
			echo $this->lists ['apply_vat_on_discount'];
			?>
        </td>
    </tr>
</table>
