<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_META_DATA_TAB'); ?></h3>
			</div>
			<div class="box-body">
				<table class="admintable table">

					<tr>
						<td valign="top" align="right" class="key">
							<?php echo JText::_('COM_REDSHOP_META_ROBOT_INFO'); ?>:
						</td>
						<td>
							<textarea class="text_area" type="text" name="metarobot_info" id="metarobot_info" rows="4"
							          cols="40"/><?php echo $this->detail->metarobot_info; ?></textarea>
							<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_META_ROBOT_INFO'), JText::_('COM_REDSHOP_META_ROBOT_INFO'), 'tooltip.png', '', '', false); ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

