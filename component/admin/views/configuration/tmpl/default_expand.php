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
<table class="admintable">

	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_EXPAND_ALL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_EXPAND_ALL'); ?>">
			<?php
			echo JText::_('COM_REDSHOP_EXPAND_ALL_LBL');
			?></td>
		<td><?php
			echo $this->lists ['expand_all'];
			?>
		</td>
	</tr>
</table>
