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
<table class="admintable">

	<tr>
		<td width="100" align="right" class="key">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISPLAY_ORDERS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISPLAY_ORDERS'); ?>">
       <?php
					echo JText::_('COM_REDSHOP_DISPLAY_ORDERS_LBL');
					?></span></td>
		<td><?php
			echo $this->lists ['display_new_orders'];
			?>
		</td>
	</tr>
</table>
