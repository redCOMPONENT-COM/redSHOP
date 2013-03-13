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
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_PRICING'); ?></legend>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td width="50%">
					<fieldset class="adminform">
						<?php echo $this->loadTemplate('price');?>
					</fieldset>
					<fieldset class="adminform">
						<?php echo $this->loadTemplate('vat');?>
					</fieldset>
				</td>
				<td width="50%">
					<fieldset class="adminform">
						<?php echo $this->loadTemplate('images_giftcard');?>
					</fieldset>
					<fieldset class="adminform">

						<?php echo $this->loadTemplate('discount');?>
					</fieldset>
					<fieldset class="adminform">
						<?php echo $this->loadTemplate('discount_mail');?>
					</fieldset>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
