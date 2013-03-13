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
		<legend><?php echo JText::_('COM_REDSHOP_CART_CHECKOUT'); ?></legend>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td width="50%">
					<?php echo $this->loadTemplate('cart_settings');?>
					<?php echo $this->loadTemplate('payment_ship_secure');?>
				</td>
				<td>
					<?php echo $this->loadTemplate('cart_template_image_setting');?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
