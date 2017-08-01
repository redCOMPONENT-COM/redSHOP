<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;    ?>
<div class="col50">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_USER_DETAIL'); ?></legend>

		<table class="admintable table">
			<?php
			if (!$this->silerntuser)
			{
				?>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USERNAME'); ?>:</td>
					<td><input class="text_area" type="text" name="username" id="username"
					           value="<?php echo $this->detail->username; ?>" size="20" maxlength="250" />
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_USERNAME'), JText::_('COM_REDSHOP_USERNAME'), 'tooltip.png', '', '', false); ?>
						<span id="user_valid">*</span></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_NEW_PASSWORD_LBL');?></td>
					<td><input class="inputbox" type="password" name="password" id="password" size="20" value=""/></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_VERIFIED_PASSWORD_LBL'); ?></td>
					<td><input class="inputbox" type="password" name="password2" id="password2" size="20" value=""/>
					</td>
				</tr>
			<?php
			}?>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
				<td><input class="text_area" type="text" name="email" id="email"
				           value="<?php echo $this->detail->email; ?>" size="20" maxlength="250" onblur="validate(2)"/>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_EMAIL'), JText::_('COM_REDSHOP_EMAIL'), 'tooltip.png', '', '', false); ?>
					<span id="email_valid">*</span></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_LBL'); ?></td>
				<td><?php echo $this->lists['shopper_group']; ?>
					<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_GROUP'), JText::_('COM_REDSHOP_GROUP'), 'tooltip.png', '', '', false); ?></td>
			</tr>
			<?php if (!$this->silerntuser)
			{
				?>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_GROUP'); ?></td>
					<td><?php echo  JHtml::_('access.usergroups', 'groups', $this->detail->user_groups, true);  ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_GROUP'), JText::_('COM_REDSHOP_GROUP'), 'tooltip.png', '', '', false); ?></td>
				</tr>
			<?php
			}?>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_BLOCK_USER'); ?></td>
				<td><?php echo $this->lists['block']; ?></td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_REGISTER_AS');?></td>
				<td><?php echo $this->lists['is_company']; ?></td>
			</tr>
			<tr>
				<td valign="top" align="right"
				    class="key"><?php echo JText::_('COM_REDSHOP_RECEIVE_SYSTEM_EMAIL'); ?></td>
				<td><?php echo $this->lists['sendEmail']; ?></td>
			</tr>
		</table>
	</fieldset>
</div>
