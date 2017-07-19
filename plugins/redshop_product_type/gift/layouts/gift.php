<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);

?>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('PLG_REDSHOP_PRODUCT_TYPE_GIFT_TYPE_NAME'); ?></h3>
			</div>
			<div class="box-body">
				<fieldset class="adminform">
					<table class="admintable table">
							<tr>
								<td class="key">
									<label for="input">
										<?php echo JText::_('COM_REDSHOP_PRODUCT_SOURCE'); ?>
									</label>
								</td>
								<td>
									<?php
									echo JHtml::_('redshopselect.search', '',
										'product_gift_search',
										array(
											'select2.options' => array(
												'events' => array(
													'select2-selecting' => 'function(e) {create_table_gift(e.object.text, e.object.id, e.object.price)}',
													'select2-close' => 'function(e) {$(this).select2("val", "")}'
												)
											),
											'select2.ajaxOptions' => array(
												'typeField' => ', accessoryList: function(){
													var listAcc = [];
													jQuery(\'input.giftIdValue\').each(function(){
														listAcc[listAcc.length] = jQuery(this).val();
													});
													return listAcc.join(",");
												}, product_id:' . $product->product_id
											),
										)
									);
									?>
								</td>
							</tr>
					</table>
				</fieldset>
				<table id="gift_table" class="adminlist table table-striped" border="0">
						<thead>
							<tr>
								<th>
									<?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?>
								</th>
								<th>
									<?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?>
								</th>
								<th>
									<?php echo JText::_('COM_REDSHOP_QUANTITY_START_LBL'); ?>
								</th>
								<th>
									<?php echo JText::_('COM_REDSHOP_QUANTITY_END_LBL'); ?>
								</th>
								<th>
									<?php echo JText::_('COM_REDSHOP_DELETE'); ?>
								</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$i = 0;
							foreach ($giftData as $giftProduct) :
						?>
							<tr>
								<td>
									<?php echo $giftProduct->product_name;?>
									<input type="hidden" class="childProductGift"
										   value="<?php echo $giftProduct->gift_id; ?>"
										   name="product_gift[<?php echo $i; ?>][gift_id]"
										/>
									<input type="hidden" class="giftIdValue"
										   value="<?php echo $giftProduct->product_id; ?>"
										   name="product_gift[<?php echo $i; ?>][product_id]"
										/>
									<input type="hidden" class="idValue"
										   value="<?php echo $giftProduct->id; ?>"
										   name="product_gift[<?php echo $i; ?>][id]"
										/>
								</td>
								<td>
									<input type="text"
										   name="product_gift[<?php echo $i; ?>][quantity]"
										   size="2"
										   value="<?php echo $giftProduct->quantity; ?>"
										   class="text_area input-small text-center" style="text-align: center"
										/>
								</td>
								<td>
									<input type="text"
										   name="product_gift[<?php echo $i; ?>][quantity_from]"
										   size="2"
										   value="<?php echo $giftProduct->quantity_from; ?>"
										   class="text_area input-small text-center" style="text-align: center"
										/>
								</td>
								<td>
									<input type="text"
										   name="product_gift[<?php echo $i; ?>][quantity_to]"
										   size="2"
										   value="<?php echo $giftProduct->quantity_to; ?>"
										   class="text_area input-small text-center" style="text-align: center"
										/>
								</td>
								<td>
									<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
										   onclick="deleteRow_gift(this, <?php echo $giftProduct->id; ?>);"
										   class="button btn btn-danger" type="button"
										/>
								</td>
							</tr>
						<?php
							$i++;
						endforeach;
						?>
						</tbody>
				</table>
				<input type="hidden" name="total_gift" id="total_gift" value="<?php echo $i; ?>"/>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function create_table_gift(data, id, price) {
		name = data;
		var g = parseInt(document.getElementById("total_gift").value) + parseInt(f);
		var myTable = document.getElementById('gift_table');
		var tBody = myTable.getElementsByTagName('tbody')[0];
		var newTR = document.createElement('tr');

		var newTD1 = document.createElement('td');
		var newTD2 = document.createElement('td');
		var newTD3 = document.createElement('td');
		var newTD4 = document.createElement('td');
		var newTD5 = document.createElement('td');

		newTD1.innerHTML = name + '<input type="hidden" class="childProductGift" value="' + id + '" name="product_gift[' + g + '][gift_id]"><input type="hidden" value="<?php echo $product->product_id ?>" name="product_gift[' + g + '][product_id]">';
		newTD2.innerHTML = '<input type="text" name="product_gift[' + g + '][quantity]"  size="2" class="text_area input-small text-center" style="text-align: center" />';
		newTD3.innerHTML = '<input type="text" name="product_gift[' + g + '][quantity_from]"  size="2" class="text_area input-small text-center" style="text-align: center" />';
		newTD4.innerHTML = '<input type="text" name="product_gift[' + g + '][quantity_to]"  size="2" class="text_area input-small text-center" style="text-align: center" />';
		newTD5.innerHTML = '<input value="' + Joomla.JText._('COM_REDSHOP_DELETE') + '" onclick="javascript:deleteRow_gift(this);" class="button btn btn-danger" type="button" />';

		newTR.appendChild(newTD1);
		newTR.appendChild(newTD2);
		newTR.appendChild(newTD3);
		newTR.appendChild(newTD4);
		newTR.appendChild(newTD5);
		tBody.appendChild(newTR);
		f++;
	}

	function deleteRow_gift(r) {
		if (window.confirm("Are you sure you want to delete?"))
		{
			var i = r.parentNode.parentNode.rowIndex;
			document.getElementById('gift_table').deleteRow(i);
		}
	}
</script>