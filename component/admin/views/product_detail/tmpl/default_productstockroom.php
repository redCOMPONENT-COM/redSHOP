<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$model = $this->getModel('product_detail');
$stockrooms = $model->StockRoomList();
?>

<table class="admintable table">

	<tr>
		<td colspan="2">

			<table id="accessory_table" class="adminlist table table-striped" border="0">

				<thead>

					<tr>
						<th><?php echo JText::_('COM_REDSHOP_STOCKROOM_NAME'); ?></th>
						<th><?php echo JText::_('COM_REDSHOP_STOCKROOM_QTY'); ?></th>
						<th><?php echo JText::_('COM_REDSHOP_MIN_DELIVERY_TIME'); ?></th>
						<th><?php echo JText::_('COM_REDSHOP_PREORDER_STOCKROOM_QTY'); ?></th>
						<th><?php echo JText::_('COM_REDSHOP_ALREDAY_ORDERED_PREORDER_STOCKROOM_QTY'); ?></th>
					</tr>

				</thead>

				<tbody>
				<?php
					if (count($stockrooms) > 0)
					{
						foreach ($stockrooms as $s)
						{
							$ordered_preorder = "";
							$preorder_stock = "";
							$quantity = $model->StockRoomProductQuantity($this->detail->product_id, $s->stockroom_id);
							$preorder_stock_data = $model->StockRoomPreorderProductQuantity($this->detail->product_id, $s->stockroom_id);

							if ($preorder_stock_data)
							{
								$ordered_preorder = $preorder_stock_data[0]->ordered_preorder;
								$preorder_stock = $preorder_stock_data[0]->preorder_stock;
							}
				?>
							<tr>
								<td>
									<?php echo $s->stockroom_name; ?>
								</td>
								<td>
									<input type="text" name="quantity[]" size="5" class="text_area" value="<?php echo $quantity; ?>"/>
									<input type="hidden" name="stockroom_id[]" value="<?php echo $s->stockroom_id; ?>"/>
								</td>
								<td>
									<?php
										$del_time = '';

										if ($s->min_del_time != '')
										{
											$del_time = $s->min_del_time;

											if ($s->delivery_time == 'Days')
											{
												echo $del_time . "  " . JText::_('COM_REDSHOP_DAYS');
											}
											elseif ($s->delivery_time == 'Weeks')
											{
												$del_time = $s->min_del_time / 7;
												echo (int) $del_time . "  " . JText::_('COM_REDSHOP_WEEKS');
											}
										}
									?>
								</td>
								<td>
									<input type="text" name="preorder_stock[]" size="5" class="text_area" value="<?php echo $preorder_stock; ?>"/>
									<input type="button"
										   name="preorder_reset"
										   value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"
										   onclick="location.href = 'index.php?option=com_redshop&view=product_detail&task=ResetPreorderStock&stockroom_type=product&product_id=<?php echo $this->detail->product_id ?>&stockroom_id=<?php echo $s->stockroom_id ?>' ; ">

								</td>
								<td>
									<?php echo $ordered_preorder;?>
									<input type="hidden" name="ordered_preorder[]" value="<?php echo $ordered_preorder; ?>"/>
								</td>
							</tr>
					<?php
						}
					}
					?>
				<input type="hidden" name="product_id" value="<?php echo $this->detail->product_id; ?>"/>
				<input type="hidden" name="stockroom_type" value="<?php echo "product"; ?>"/>
				</tbody>

			</table>

		</td>
	</tr>

</table>
