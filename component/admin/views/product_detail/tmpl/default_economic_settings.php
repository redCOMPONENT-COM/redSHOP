<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<table class="admintable" border="0">

	<tr>
		<td>

			<table>

				<tr>
					<td class="key">
						<label for="accountgroup_id">
							<?php echo JText::_('COM_REDSHOP_ECONOMIC_ACCOUNTGROUP_LBL'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['accountgroup_id'];?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_ACCOUNTGROUP_LBL'),
							JText::_('COM_REDSHOP_ECONOMIC_ACCOUNTGROUP_LBL'),
							'tooltip.png',
							'',
							'',
							false
						);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="quantity_selectbox_value">
							<?php echo JText::_('COM_REDSHOP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="quantity_selectbox_value"
							   id="quantity_selectbox_value"
							   size="10"
							   value="<?php echo $this->lists['QUANTITY_SELECTBOX_VALUE']; ?>"
							/>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'),
							JText::_('COM_REDSHOP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'),
							'tooltip.png',
							'',
							'',
							false
						);
						?>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>

			</table>

		</td>
	</tr>

</table>
