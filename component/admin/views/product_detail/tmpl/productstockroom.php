<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$model = $this->getModel();

$cid = JRequest::getVar('cid', 0);

$section_id = JRequest::getVar('section_id', 0);
$section = JRequest::getVar('property');


$stockrooms = $model->StockRoomList();

?>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<table class="admintable">
		<tr>
			<td colspan="2">
				<table id="accessory_table" class="adminlist" border="0">
					<thead>
					<tr>
						<th width="400"><?php echo JText::_('COM_REDSHOP_STOCKROOM_NAME'); ?></th>
						<th width="75"><?php echo JText::_('COM_REDSHOP_STOCKROOM_QTY'); ?></th>
						<th width="75"><?php echo JText::_('COM_REDSHOP_PREORDER_STOCKROOM_QTY'); ?></th>
						<th width="75"><?php echo JText::_('COM_REDSHOP_ALREDAY_ORDERED_PREORDER_STOCKROOM_QTY'); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php
					$iscrm = false;
					$helper = new redhelper();
					/**
					 * redCRM includes
					 */
					if ($helper->isredCRM())
					{

						if (ENABLE_ITEM_TRACKING_SYSTEM)
						{
							$iscrm = true;
							$crmHelper = new crmHelper();
							$crmSupplierOrderHelper = new crmSupplierOrderHelper();

							$stockWhere = (USE_STOCKROOM) ? " 1=1" : " stockroom_id = " . DEFAULT_STOCKROOM;

							$stockroom_list = $crmHelper->getStockroom('stockroom_id AS value,stockroom_name AS text', "1", $stockWhere);


							$stockroomname = $stockroom_list[0]->text;

							$data = new stdClass();
							$data->product_id = $this->detail->product_id;
							$data->property_id = 0;
							$data->subproperty_id = 0;

							if ($section == 'property')
							{
								$data->property_id = $section_id;
							}
							else if ($section == 'subproperty')
							{
								# get data for property id
								$subattribute_data = $this->getAttibuteSubProperty($section_id);
								$data->property_id = $subattribute_data[0]->subattribute_id;
								$data->subproperty_id = $section_id;
							}

							$stockAmount = $crmSupplierOrderHelper->getSupplierStock($data);
							$deliveryTime = DEFAULT_DELIVERY_DAYS_STATUS_PENDING;
							if ($stockAmount > 0) $deliveryTime = DEFAULT_DELIVERY_DAYS_STATUS_ACTIVE;

							?>
							<tr>
								<td width="200"><?php echo $stockroomname; ?></td>
								<td><?php echo $stockAmount;?></td>
							</tr>
						<?php
						}
					}
					if (count($stockrooms) > 0 && !$iscrm)
					{
						foreach ($stockrooms as $s)
						{

							$ordered_preorder = "";
							$preorder_stock = "";
							$quantity = $model->StockRoomAttProductQuantity($section_id, $s->stockroom_id, $section);
							$preorder_stock_data = $model->StockRoomAttProductPreorderstock($section_id, $s->stockroom_id, $section);

							if ($preorder_stock_data)
							{
								$ordered_preorder = $preorder_stock_data[0]->ordered_preorder;
								$preorder_stock = $preorder_stock_data[0]->preorder_stock;
							}

							?>
							<tr>
								<td width="200"><?php echo $s->stockroom_name; ?></td>
								<td><input type="text" name="quantity[]" size="5" class="text_area"
								           value="<?php echo $quantity; ?>"/>
									<input type="hidden" name="stockroom_id[]" value="<?php echo $s->stockroom_id; ?>"/>
								</td>

								<td>
									<input type="text" name="preorder_stock[]" size="5" class="text_area"
									       value="<?php echo $preorder_stock; ?>"/>
									<input type="button" name="preorder_reset" value="Reset"
									       onclick="location.href = 'index.php?option=com_redshop&view=product_detail&task=ResetPreorderStockBank&stockroom_type=<?php echo $section ?>&section_id=<?php echo $section_id ?>&cid=<?php echo $cid ?>&product_id=<?php echo $this->detail->product_id ?>&stockroom_id=<?php echo $s->stockroom_id ?>' ; ">

								</td>
								<td>
									<?php echo $ordered_preorder;?>
									<input type="hidden" name="ordered_preorder[]"
									       value="<?php echo $ordered_preorder; ?>"/>
								</td>
							</tr>
						<?php
						}
					}
					?>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php if (!$iscrm)
				{ ?>
					<input type="submit" name="submit" value="Save">
				<?php }?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="hidden" name="view" value="product_detail">
				<input type="hidden" name="task" value="saveAttributeStock">
				<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
				<input type="hidden" name="section" value="<?php echo $section; ?>">
				<input type="hidden" name="cid" value="<?php echo $cid; ?>">

			</td>
		</tr>
	</table>

</form>
