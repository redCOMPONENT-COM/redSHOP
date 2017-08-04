<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$td_style = ($this->detail->product_download_infinite == 0) ? 'style="display:table-row;"' : 'style="display:none;"';

$optionPeriod = array();
?>

<?php // Subscription
$subscription = $this->producthelper->getSubscription($this->detail->product_id);
$renewal_detail = $this->model->getSubscriptionrenewal();
$productSerialDetail = $this->productSerialDetail;
$total_serial = count($productSerialDetail);
?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_CHANGE_PRODUCT_TYPE_TAB'); ?></h3>
			</div>
			<div class="box-body">
				<?php if (($this->detail->product_type == 'file') || ($this->detail->product_download == 1)) : ?>
				<div id="div_file">

					<table class="admintable table">

						<tr>
							<td class="key">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD'); ?>
							</td>
							<td>
								<?php echo $this->lists['product_download'];?>
							</td>
						</tr>

						<tr>
							<td class="key">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_INFINITE_LIMIT'); ?>
							</td>
							<td>
								<?php
									echo JHtml::_('select.booleanlist',
													'product_download_infinite',
													'class="inputbox" onclick="hideDownloadLimit(this);"',
													$this->detail->product_download_infinite
									);
								?>
							</td>
						</tr>

						<tr id="download_limit" <?php echo $td_style;?> >
							<td class="key">
								<label for="product_download_limit">
									<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_LIMIT_LBL'); ?>
								</label>
							</td>
							<td>
								<input type="text"
									   id="product_download_limit"
									   name="product_download_limit"
									   class="input"
									   size="10"
									   value="<?php echo $this->detail->product_download_limit; ?>"
									/>
							</td>
						</tr>

						<tr id="download_days" <?php echo $td_style;?> >
							<td class="key">
								<label for="product_download_days">
									<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DAYS_LBL'); ?>
								</label>
							</td>
							<td>
								<input type="text"
									   id="product_download_days"
									   name="product_download_days"
									   class="input"
									   size="10"
									   value="<?php echo $this->detail->product_download_days; ?>"
									/>
							</td>
						</tr>

						<tr id="download_clock" <?php echo $td_style;?> >
							<td class="key">
								<label for="product_download_clock">
									<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_CLOCK_LBL'); ?>
								</label>
							</td>
							<td>
								<input type="text"
									   id="product_download_clock"
									   name="product_download_clock"
									   class="input"
									   size="2"
									   maxlength="2"
									   value="<?php echo $this->detail->product_download_clock; ?>"
									/>
								<input type="text"
									   name="product_download_clock_min"
									   class="input" size="2"
									   maxlength="2"
									   value="<?php echo $this->detail->product_download_clock_min; ?>"
									/>
							</td>
						</tr>

						<tr>
							<td class="key">
								<?php echo JText::_('COM_REDSHOP_UPLOAD_CSV_FILE')?>
							</td>
							<td>
								<input type="file" name='serialcsvFile' id='serialcsvFile'/>
							</td>
						</tr>

					</table>

					<?php if ($total_serial > 0) :?>
						<table class="adminlist table table-striped">
							<tr>
								<th></th>
								<th><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
								<th><?php echo JText::_('COM_REDSHOP_SERIAL_NUMBERS'); ?></th>
								<th><?php echo JText::_('COM_REDSHOP_IS_USED'); ?></th>
							</tr>
							<?php
								$serial = 1;

								for ($si = 0; $si < $total_serial; $si++)
								{
									$serial_row = $productSerialDetail[$si];  ?>
									<tr>
										<td>
											<a href='index.php?option=com_redshop&view=product_detail&task=deleteProdcutSerialNumbers&serial_id=<?php echo $serial_row->serial_id ?>&product_id=<?php echo $this->detail->product_id; ?>'><img
												class="delete_item"
												src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH; ?>cross.png"
												title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
												alt="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"></a>
										</td>
										<td>
											<?php echo $serial; ?>
										</td>
										<td>
											<?php echo $serial_row->serial_number; ?>
										</td>
										<td>
											<?php echo $serial_row->is_used ? JText::_('COM_REDSHOP_YES') : JText::_('COM_REDSHOP_NO'); ?>
										</td>
									</tr>
									<?php
									$serial++;
								}
							?>
						</table>
					<?php endif; ?>

				</div>
			<?php elseif ($this->detail->product_type == 'subscription') : ?>
				<div id="div_subscription">

					<table id="tbl_scbscription" class="adminlist">

						<tr>
							<td colspan="4">
								<input type="button" value="<?php echo JText::_('COM_REDSHOP_ADD'); ?>" onClick="add_subscription_row();"/>
							</td>
						</tr>

						<tr>
							<th>
								<?php echo JText::_('COM_REDSHOP_NUM');?>
							</th>
							<th>
								<?php
									echo JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD') . " ";
									echo JHtml::tooltip(JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD_TIP'), JText::_('COM_REDSHOP_SUBSCRIPTION_PERIOD'), 'tooltip.png', '', '', false);
								?>
							</th>
							<th>
								<?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_PRICE');?>
								<?php
									echo JHtml::tooltip(JText::_('COM_REDSHOP_SUBSCRIPTION_PRICE_TIP'), JText::_('COM_REDSHOP_SUBSCRIPTION_PRICE'), 'tooltip.png', '', '', false);
								?>
							</th>
							<th>
								<?php echo JText::_('COM_REDSHOP_DELETE');?>
							</th>
						</tr>

						<?php
							$optionPeriod[] = JHtml::_('select.option', 'days', JText::_('COM_REDSHOP_DAYS'));
							$optionPeriod[] = JHtml::_('select.option', 'month', JText::_('COM_REDSHOP_MONTH'));
							$optionPeriod[] = JHtml::_('select.option', 'year', JText::_('COM_REDSHOP_YEAR'));

							for ($sub = 0; $sub < count($subscription); $sub++)
							{
								$subrow = $subscription[$sub];
						?>
								<tr id="tr_subsc<?php echo $sub; ?>">
									<td>
										<?php echo ($sub + 1);?>
										<input type="hidden" name="subscription_id[]" value="<?php echo $subrow->subscription_id; ?>"/>
									</td>
									<td>
										<input type="text" name="subscription_period[]" class="input" size="10" value="<?php echo $subrow->subscription_period; ?>"/>
										<?php
											echo JHtml::_('select.genericlist', $optionPeriod, 'period_type[]', 'class="inputbox" size="1" ', 'value', 'text', $subrow->period_type);
										?>
									</td>
									<td>
										<input type="text" name="subscription_price[]" class="input" size="10" value="<?php echo $subrow->subscription_price; ?>"/>
									</td>
									<td>
										<input type="button"
											   value="X"
											   title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
											   name="btndelete"
											   onClick="delete_subscription_row(<?php echo $sub; ?>);"
											/>
									</td>
								</tr>
						<?php
							}
						?>

					</table>

					<br/>

					<table>
						<tr>
							<td class="key">
								<label for="before_no_days">
									<?php echo JText::_('COM_REDSHOP_SUBSCRIPTION_RENEWAL_MAIL'); ?>
								</label>
							</td>
							<td>
								<input type="text"
									   id="before_no_days"
									   name="before_no_days"
									   value="<?php echo isset($renewal_detail[0]->before_no_days) ? $renewal_detail[0]->before_no_days : 1; ?>"
									   maxlength="2"
									   size="3"
									/>
								<input type="hidden"
									   name="renewal_id"
									   value="<?php echo isset($renewal_detail[0]->renewal_id) ? $renewal_detail[0]->renewal_id : ''; ?>"
									/>
								<?php echo JText::_('COM_REDSHOP_DAYS_BEFORE_SUBSCRIPTION_END'); ?>
							</td>
						</tr>
					</table>

				</div>
			<?php else : ?>
				<?php
					$this->dispatcher->trigger('onDisplayProductTypeData', array($this->detail));
				?>
			<?php endif; ?>
			</div>
		</div>
	</div>
</div>



<?php
$remove_format = JHtml::$formatOptions;

$add_subscription_row = " " . JHtml::_('select.genericlist', $optionPeriod, 'period_type[]', 'class="inputbox" size="1" ', 'value', 'text');
$add_subscription_row = str_replace($remove_format['format.indent'], "", $add_subscription_row);
$add_subscription_row = str_replace($remove_format['format.eol'], "", $add_subscription_row);

?>

<script type="text/javascript">
	var rowCount = <?php echo (count($subscription) + 1); ?>;
	var rowIndex = <?php echo (count($subscription) + 1); ?>;

	function add_subscription_row() {
		var getTABLE = document.getElementById('tbl_scbscription');
		var tBody = getTABLE.getElementsByTagName("TBODY")[0];
		var newTR = document.createElement('tr');
		var newTD0 = document.createElement('td');
		var newTD1 = document.createElement('td');
		var newTD2 = document.createElement('td');
		var newTD3 = document.createElement('td');

		newTD0.innerHTML = rowIndex;
		newTD1.innerHTML = '<input type="text" name="subscription_period[]" class="input" size="10" /><?php echo $add_subscription_row; ?>';
		newTD2.innerHTML = '<input type="text" name="subscription_price[]" class="input" size="10" />';
		newTD3.innerHTML = '<input type="button" value="X" name="btndelete" title="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>" ' +
						   'onClick="delete_subscription_row(' +
						   rowCount +
						   ');" />';

		newTR.appendChild(newTD0);
		newTR.appendChild(newTD1);
		newTR.appendChild(newTD2);
		newTR.appendChild(newTD3);

		newTR.id = 'tr_subsc' + rowCount;
		tBody.appendChild(newTR);
		rowCount++;
		rowIndex++;
	}

	function delete_subscription_row(index) {
		var row = document.getElementById('tr_subsc' + index);
		var getTABLE = document.getElementById('tbl_scbscription');
		var tBody = getTABLE.getElementsByTagName("TBODY")[0];
		tBody.removeChild(row);
		rowIndex--;
	}
</script>
