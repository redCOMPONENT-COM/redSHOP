<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$countries = RedshopHelperWorld::getCountryList();
$country = $countries['country_dropdown'];
?>
<div id="redshopcomponent" class="redshop redSHOPSiteViewRegistration isJ30">
	<table>
		<tbody>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_EMAIL'); ?></label>
					<input class="inputbox required" type="text" title="<?php echo JText::_('COM_REDSHOP_PROVIDE_CORRECT_EMAIL_ADDRESS') ?>" name="email1" id="email1" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_RETYPE_CUSTOMER_EMAIL'); ?></label>
					<input class="inputbox required" type="text" id="email2" name="email2" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_COMPANY_NAME'); ?></label>
					<input class="inputbox required" type="text" name="company_name" id="company_name" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?></label>
					<input class="inputbox required" type="text" name="firstname" id="firstname" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?></label>
					<input class="inputbox required" type="text" name="lastname" id="lastname" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?></label>
					<input class="inputbox required" type="text" name="address" id="address" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_ZIP'); ?></label>
					<input class="inputbox required" type="text" name="zipcode" id="zipcode" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_CITY'); ?></label>
					<input class="inputbox required" type="text" name="city" id="city" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr id="div_country_txt">
				<td>
					<label><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?></label>
					<?php echo $country; ?>
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_PHONE'); ?></label>
					<input class="inputbox required" type="text" name="phone" id="phone" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('COM_REDSHOP_EAN_NUMBER'); ?></label>
					<input class="inputbox required" type="text" name="ean_number" id="ean_number" size="32" maxlength="250" value="" />
				</td>
				<td>
					<span class="required">*</span>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="customfield">
					<?php echo RedshopHelperExtrafields::listAllField(8); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
