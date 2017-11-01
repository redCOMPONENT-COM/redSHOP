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
				<h3 class="box-title"><?php echo JText::_('PLG_REDSHOP_PRODUCT_TYPE_BUNDLE_TYPE_NAME'); ?></h3>
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
										'product_bundle_search',
										array(
											'select2.options' => array(
												'events' => array(
													'select2-selecting' => 'function(e) {create_table_bundle(e.object.text, e.object.id, e.object.price)}',
													'select2-close' => 'function(e) {$(this).select2("val", "")}'
												)
											),
											'select2.ajaxOptions' => array(
												'typeField' => ', accessoryList: function(){
													var listAcc = [];
													jQuery(\'input.childProductBundle\').each(function(){
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
				<table id="bundle_table" class="adminlist table table-striped" border="0">
						<thead>
							<tr>
								<th>
									<?php echo JText::_('PLG_REDSHOP_PRODUCT_TYPE_BUNDLE_NAME'); ?>
								</th>
								<th>
									<?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?>
								</th>
								<th>
									<?php echo JText::_('COM_REDSHOP_PRODUCT_NORMAIL_PRICE'); ?>
								</th>
								<th>
									<?php echo JText::_('COM_REDSHOP_ORDERING'); ?>
								</th>
								<th>
									<?php echo JText::_('COM_REDSHOP_DELETE'); ?>
								</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$i = 0;
							foreach ($bundleData as $bundleProduct)
							{
						?>
							<tr>
								<td>
									<input type="text"
										   name="product_bundle[<?php echo $i; ?>][bundle_name]"
										   size="2"
										   value="<?php echo $bundleProduct->bundle_name; ?>"
										   class="text_area input-small text-center" style="text-align: center"
										/>
								</td>
								<td>
									<?php echo $bundleProduct->product_name;?>
									<input type="hidden" class="childProductBundle"
										   value="<?php echo $bundleProduct->bundle_id; ?>"
										   name="product_bundle[<?php echo $i; ?>][bundle_id]"
										/>
									<input type="hidden" class="bundleIdValue"
										   value="<?php echo $bundleProduct->product_id; ?>"
										   name="product_bundle[<?php echo $i; ?>][product_id]"
										/>
								</td>
								<td>
									<?php echo $bundleProduct->product_price;?>
								</td>
								<td>
									<input type="text"
										   name="product_bundle[<?php echo $i; ?>][ordering]"
										   size="2"
										   value="<?php echo $bundleProduct->ordering; ?>"
										   class="text_area input-small text-center" style="text-align: center"
										/>
								</td>
								<td>
									<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
										   onclick="deleteRow_bundle(this, <?php echo $bundleProduct->bundle_id; ?>);"
										   class="button btn btn-danger" type="button"
										/>
								</td>
							</tr>
						<?php
							$i++;
						}
						?>
						</tbody>
				</table>
				<input type="hidden" name="total_bundle" id="total_bundle" value="<?php echo $i; ?>"/>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function create_table_bundle(data, id, price) {
		name = data;
		var g = parseInt(document.getElementById("total_bundle").value) + parseInt(f);
		var myTable = document.getElementById('bundle_table');
		var tBody = myTable.getElementsByTagName('tbody')[0];
		var newTR = document.createElement('tr');

		var newTD1 = document.createElement('td');
		var newTD2 = document.createElement('td');
		var newTD3 = document.createElement('td');
		var newTD4 = document.createElement('td');
		var newTD5 = document.createElement('td');

		newTD1.innerHTML = '<input type="text" name="product_bundle[' + g + '][bundle_name]"  size="2" value="' + name + '" class="text_area input-small text-center" style="text-align: center" />';
		newTD2.innerHTML = name + '<input type="hidden" class="childProductBundle" value="' + id + '" name="product_bundle[' + g + '][bundle_id]"><input type="hidden" value="<?php echo $product->product_id ?>" name="product_bundle[' + g + '][product_id]">';
		newTD3.innerHTML = price;
		newTD4.innerHTML = '<input type="text" name="product_bundle[' + g + '][ordering]" size="2" value="0" class="text_area input-small text-center" style="text-align: center" />';
		newTD5.innerHTML = '<input value="' + Joomla.JText._('COM_REDSHOP_DELETE') + '" onclick="javascript:deleteRow_bundle(this);" class="button btn btn-danger" type="button" />';

		newTR.appendChild(newTD1);
		newTR.appendChild(newTD2);
		newTR.appendChild(newTD3);
		newTR.appendChild(newTD4);
		newTR.appendChild(newTD5);
		tBody.appendChild(newTR);
		f++;
	}

	function deleteRow_bundle(r) {
		if (window.confirm("Are you sure you want to delete?"))
		{
			var i = r.parentNode.parentNode.rowIndex;
			document.getElementById('bundle_table').deleteRow(i);
		}
	}
</script>