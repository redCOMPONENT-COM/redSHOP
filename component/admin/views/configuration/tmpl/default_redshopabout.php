<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<div id="config-document">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<?php
					/*<tr>
						<td width="50%">
							<fieldset class="adminform">
							<legend><?php echo JText::_('COM_REDSHOP_REDSHOP_VERSION' ); ?></legend>
								<?php echo $this->loadTemplate('redshop_version');?>
							</fieldset>
						</td>
					</tr>*/
					?>
					<tr>
						<td width="50%">
							<fieldset class="adminform">
								<legend><?php echo JText::_('COM_REDSHOP_SYSTEM_INFORMATION'); ?></legend>
								<?php echo $this->loadTemplate('system_information');?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td width="50%">
							<fieldset class="adminform">
								<legend><?php echo JText::_('COM_REDSHOP_REDSHOP_MODULES'); ?></legend>
								<?php echo $this->loadTemplate('redshop_modules');?>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td width="50%">
							<fieldset class="adminform">
								<legend><?php echo JText::_('COM_REDSHOP_REDSHOP_SHIPPING_PLUGINS'); ?></legend>
								<?php echo $this->loadTemplate('redshop_shipping');?>
							</fieldset>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="50%">
							<fieldset class="adminform">
								<legend><?php echo JText::_('COM_REDSHOP_REDSHOP_PAYMENT_PLUGINS'); ?></legend>
								<?php echo $this->loadTemplate('redshop_plugins');?>
							</fieldset>
						</td>
					</tr>
				</table>
		</tr>
	</table>
</div>
