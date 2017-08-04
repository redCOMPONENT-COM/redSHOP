<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
	<div>&nbsp;</div>
	<fieldset class="adminform">
		<table class="admintable table">
			<tr>
				<td colspan="2" class="registration_intro_text">
					<?php echo JText::_('COM_REDSHOP_REGISTRATION_METHOD_INTRO_TEXT'); ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_REGISTER_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_REGISTER_METHOD_LBL'); ?>">
				<label for="name"><?php echo JText::_('COM_REDSHOP_REGISTER_METHOD_LBL');?></label></span>
				</td>
				<td>
					<?php echo $this->lists ['register_method']; ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<div>&nbsp;</div>
	<fieldset class="adminform">
		<table class="admintable table">
			<tr>
				<td colspan="2" class="welcomepage_intro_text">
					<?php echo JText::_('COM_REDSHOP_WELCOMEPAGE_INTRO_TEXT'); ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WELCOMEPAGE_INTROTEXT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_WELCOMEPAGE_INTROTEXT_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_WELCOMEPAGE_INTROTEXT_LBL');?>:</span>
				</td>
				<td>
					<textarea class="text_area" type="text" name="welcomepage_introtext" id="welcomepage_introtext"
					          rows="4" cols="40"/><?php echo $this->temparray['WELCOMEPAGE_INTROTEXT']; ?></textarea>
				</td>
			</tr>
		</table>
	</fieldset>
<?php
if (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 2)
{
	?>
	<div>&nbsp;</div>
	<fieldset class="adminform">
		<table class="admintable table">
			<tr>
				<td colspan="2" class="registration_page_intro_text">
					<?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_INTRO_TEXT'); ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_REGISTRATION_PAGE_INTRO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_INTRO_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_INTRO_LBL');?>:</span>
				</td>
				<td>
					<textarea class="text_area" type="text" name="registration_introtext" id="registration_introtext"
					          rows="4" cols="40"/><?php echo $this->temparray['REGISTRATION_INTROTEXT']; ?></textarea>
				</td>
			</tr>
		</table>
	</fieldset>
<?php
}
if (Redshop::getConfig()->get('ALLOW_CUSTOMER_REGISTER_TYPE') != 1)
{
	?>
	<div>&nbsp;</div>
	<fieldset class="adminform">
		<table class="admintable table">
			<tr>
				<td colspan="2" class="registration_page_intro_text">
					<?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_COMP_INTRO_TEXT'); ?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_REGISTRATION_PAGE_COMP_INTRO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_COMP_INTRO_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_REGISTRATION_PAGE_COMP_INTRO_LBL');?>:</span>
				</td>
				<td>
					<textarea class="text_area" type="text" name="registration_comp_introtext"
					          id="registration_comp_introtext" rows="4"
					          cols="40"/><?php echo $this->temparray['REGISTRATION_COMPANY_INTROTEXT']; ?></textarea>
				</td>
			</tr>
		</table>
	</fieldset>
<?php
}
?>
