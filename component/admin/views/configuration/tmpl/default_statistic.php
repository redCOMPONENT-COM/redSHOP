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

<legend><?php echo JText::_('COM_REDSHOP_SHOW_LAST_MONTH_STATISTIC'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_LAST_MONTH_STATISTIC_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_LAST_MONTH_STATISTIC'); ?>">
		<label><?php
			echo JText::_('COM_REDSHOP_SHOW_LAST_MONTH_STATISTIC');
			?></label>
	<?php
			echo $this->lists ['display_statistic'];
			?>
</div>

