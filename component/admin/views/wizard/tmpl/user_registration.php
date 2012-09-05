<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<div>&nbsp;</div>
<fieldset class="adminform">
	<table class="admintable">
		<tr>
			<td colspan="2" class="registration_intro_text">
				<?php echo JText::_('REGISTRATION_METHOD_INTRO_TEXT'); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_REGISTER_METHOD_LBL' ); ?>::<?php echo JText::_( 'REGISTER_METHOD_LBL' ); ?>">
				<label for="name"><?php echo JText::_ ( 'REGISTER_METHOD_LBL' );?></label></span>
			</td>
			<td>
				<?php echo $this->lists ['register_method']; ?>
			</td>
		</tr>
	</table>
</fieldset>
<div>&nbsp;</div>
<fieldset class="adminform">
	<table class="admintable">
		<tr>
			<td colspan="2" class="welcomepage_intro_text">
				<?php echo JText::_('WELCOMEPAGE_INTRO_TEXT'); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_WELCOMEPAGE_INTROTEXT_LBL' ); ?>::<?php echo JText::_( 'WELCOMEPAGE_INTROTEXT_LBL' ); ?>">
			<?php echo JText::_ ( 'WELCOMEPAGE_INTROTEXT_LBL' );?>:</span>
			</td>
			<td>
			<textarea class="text_area" type="text" name="welcomepage_introtext" id="welcomepage_introtext" rows="4" cols="40" /><?php echo $this->temparray['welcomepage_introtext']; ?></textarea>
			</td>
		</tr>
	</table>
</fieldset>
<?php
if(ALLOW_CUSTOMER_REGISTER_TYPE != 2){
?>
<div>&nbsp;</div>
<fieldset class="adminform">
	<table class="admintable">
		<tr>
			<td colspan="2" class="registration_page_intro_text">
				<?php echo JText::_('REGISTRATION_PAGE_INTRO_TEXT'); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_REGISTRATION_PAGE_INTRO_LBL' ); ?>::<?php echo JText::_( 'REGISTRATION_PAGE_INTRO_LBL' ); ?>">
			<?php echo JText::_ ( 'REGISTRATION_PAGE_INTRO_LBL' );?>:</span>
			</td>
			<td>
			<textarea class="text_area" type="text" name="registration_introtext" id="registration_introtext" rows="4" cols="40" /><?php echo $this->temparray['registration_introtext'];?></textarea>
			</td>
		</tr>
	</table>
</fieldset>
<?php
}
if(ALLOW_CUSTOMER_REGISTER_TYPE != 1){
?>
<div>&nbsp;</div>
<fieldset class="adminform">
	<table class="admintable">
		<tr>
			<td colspan="2" class="registration_page_intro_text">
				<?php echo JText::_('REGISTRATION_PAGE_COMP_INTRO_TEXT'); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_REGISTRATION_PAGE_COMP_INTRO_LBL' ); ?>::<?php echo JText::_( 'REGISTRATION_PAGE_COMP_INTRO_LBL' ); ?>">
			<?php echo JText::_ ( 'REGISTRATION_PAGE_COMP_INTRO_LBL' );?>:</span>
			</td>
			<td>
			<textarea class="text_area" type="text" name="registration_comp_introtext" id="registration_comp_introtext" rows="4" cols="40" /><?php echo $this->temparray['registration_comp_introtext'];	?></textarea>
			</td>
		</tr>
	</table>
</fieldset>
<?php
}
?>