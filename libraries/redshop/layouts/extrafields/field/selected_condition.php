<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   object  $rowData             Extra field data
 * @param   string  $extraFieldLabel     Extra field label
 * @param   string  $required            Extra field required
 * @param   string  $requiredLabel       Extra field required label
 * @param   string  $errorMsg            Extra field error message
 * @param   string  $datePublish         Extra field publish date
 * @param   string  $dateExpiry          Extra field expiry date
 * @param   string  $mainSplitDateExtra  Extra field slit date
 */
extract($displayData);
?>
<td>
	<td valign="top" width="100" align="right" class="key">
		<?php echo $extraFieldLabel; ?>
	</td>
	<tr>
		<td>
			<?php echo JText::_('COM_REDSHOP_PUBLISHED_DATE'); ?>
			<input type="text" name="<?php echo $rowData->name ?>" value="<?php echo $datePublish; ?>">
			&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo JText::_('COM_REDSHOP_EXPIRY_DATE'); ?>
			<input type="text" name="<?php echo $rowData->name ?>" value="<?php echo $dateExpiry; ?>">
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<div class="col50" id="field_data">
				<?php echo JText::_('COM_REDSHOP_ENTER_AVAILABLE_DATE'); ?>
				<input
					type="button" 
					name="addvalue"
					id="addvalue"
					class="button"
					value="<?php echo JText::_('COM_REDSHOP_ADD_VALUE'); ?>"
					onclick="addNewRowcustom(<?php echo $rowData->name; ?>);"
				>
				<fieldset class="adminform">
					<legend>
						<?php echo JText::_('COM_REDSHOP_VALUE'); ?>
					</legend>
					<table cellpadding="0" cellspacing="5" border="0" id="extra_table" width="95%">
						<tr>
							<th width="20%">
								<?php echo JText::_('COM_REDSHOP_OPTION_VALUE'); ?>
							</th>
							<th>&nbsp;</th>
						</tr>
						<?php if (count($mainSplitDateExtra) > 0) : ?>
							<?php foreach ($mainSplitDateExtra as $key => $slitDate) : ?>
								<?php if (empty($slitDate)) : ?>
									<?php continue; ?>
								<?php endif; ?>
								<?php $total++; ?>
								<tr>
									<td>
										<div id="divfieldText">
											<input
												type="text"
												name="<?php echo $rowData->name; ?>_extra_name[]"
												value="<?php echo date("d-m-Y", $slitDate); ?>"
											>
										</div>
									</td>
									<td>
										<input 
											type="button"
											class="button"
											onclick="deleteRow(this);"
											value="<?php echo JText::_('COM_REDSHOP_DELETE_LBL'); ?>"
										>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<?php $total = 1; ?>
							<tr>
								<td>
									<div id="divfieldText">
										<input
											type="text"
											name="<?php echo $rowData->name; ?>_extra_name[]"
											value="<?php echo date("d-m-Y"); ?>"
										>
									</div>
								</td>
							</tr>
						<?php endif; ?>
					</table>
				</fieldset>
			</div>
			<input type="hidden" name="total_extra" value="<?php echo $total; ?>" id="total_extra">
		</td>
	</tr>
	