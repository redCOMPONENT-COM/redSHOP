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

<legend><?php echo JText::_('COM_REDSHOP_EXPAND_ALL_LBL'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_EXPAND_ALL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_EXPAND_ALL'); ?>">
			 <label><?php
			echo JText::_('COM_REDSHOP_EXPAND_ALL_LBL');
			?> </label>
	<?php
			echo $this->lists ['expand_all'];
			?>
</div>
