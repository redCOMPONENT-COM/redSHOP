<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<table class="admintable" id="measurement">
	<tr>
		<td class="key">
			<span
				class="editlinktip hasTip"
				title="<?php echo JText::_('COM_REDSHOP_GLS_CUSTOMER_ID_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_GLS_CUSTOMER_ID_LBL'); ?>"
			>
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_GLS_CUSTOMER_ID_LBL'); ?>
				</label>
			</span>
		</td>
		<td>
			<input
				type="text"
				name="gls_customer_id"
		        id="gls_customer_id"
		        value="<?php echo $this->config->get('GLS_CUSTOMER_ID'); ?>">
		</td>
	</tr>
</table>
