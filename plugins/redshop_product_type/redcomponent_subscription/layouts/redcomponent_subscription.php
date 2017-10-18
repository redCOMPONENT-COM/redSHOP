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
		<fieldset class="adminform">
			<table class="admintable table">
				<tr>
					<td class="key">
						<label for="productSubscriptionPeriod">
							<?php echo JText::_('PLG_REDSHOP_PRODUCT_TYPE_REDCOMPONENT_SUBSCRIPTION_PERIOD'); ?>
						</label>
					</td>
					<td>
						<input id="productSubscriptionPeriod" type="text" value="<?php echo $period ?>" name="productSubscriptionPeriod" />
					</td>
				</tr>

				<tr>
					<td class="key">
						<label>
							<?php echo JText::_('PLG_REDSHOP_PRODUCT_TYPE_REDCOMPONENT_SUBSCRIPTION_SELECT_PRODUCTS'); ?>
						</label>
					</td>
					<td>
						<?php
						echo JHtml::_('redshopselect.search', '',
							'product_redcomponent_subscription_search',
							array(
								'select2.options' => array(
									'events' => array(
										'select2-selecting' => 'function(e) {create_table_redcomponent_subscription(e.object.text, e.object.id, e.object.price)}',
										'select2-close' => 'function(e) {$(this).select2("val", "")}'
									)
								),
								'select2.ajaxOptions' => array(
									'typeField' => ', accessoryList: function(){
										var listAcc = [];
										jQuery(\'input.productSubscription\').each(function(){
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
		<table id="redcomponent_subscription_table" class="adminlist table table-striped" border="0">
				<thead>
					<tr>
						<th>
							<?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?>
						</th>
						<th>
							<?php echo JText::_('COM_REDSHOP_PRODUCT_NORMAIL_PRICE'); ?>
						</th>
						<th>
							<?php echo JText::_('COM_REDSHOP_DELETE'); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$i = 0;
					foreach ($subscriptions as $subscription)
					{
				?>
					<tr>
						<td>
							<?php echo $subscription->product_name;?>
							<input type="hidden" class="productSubscription"
								   value="<?php echo $subscription->product_id; ?>"
								   name="productSubscription[]"
								/>
						</td>
						<td>
							<?php echo $subscription->product_price;?>
						</td>
						<td>
							<input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
								   onclick="deleteRow_redcomponent_subscription(this);"
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
		<input type="hidden" name="total_redcomponent_subscription" id="total_redcomponent_subscription" value="<?php echo $i; ?>"/>
	</div>
</div>

<script type="text/javascript">
	function create_table_redcomponent_subscription(data, id, price) {
		name = data;
		var g = parseInt(document.getElementById("total_redcomponent_subscription").value) + parseInt(f);
		var myTable = document.getElementById('redcomponent_subscription_table');
		var tBody = myTable.getElementsByTagName('tbody')[0];
		var newTR = document.createElement('tr');

		var newTD1 = document.createElement('td');
		var newTD2 = document.createElement('td');
		var newTD3 = document.createElement('td');
		var newTD4 = document.createElement('td');
		var newTD5 = document.createElement('td');

		newTD1.innerHTML = name + '<input type="hidden" class="productSubscription" value="' + id + '" name="productSubscription[]">';
		newTD2.innerHTML = price;
		newTD3.innerHTML = '<input value="' + Joomla.JText._('COM_REDSHOP_DELETE') + '" onclick="javascript:deleteRow_redcomponent_subscription(this);" class="button btn btn-danger" type="button" />';

		newTR.appendChild(newTD1);
		newTR.appendChild(newTD2);
		newTR.appendChild(newTD3);
		tBody.appendChild(newTR);
		f++;
	}

	function deleteRow_redcomponent_subscription(r) {
		if (window.confirm("Are you sure you want to delete?"))
		{
			var i = r.parentNode.parentNode.rowIndex;
			document.getElementById('redcomponent_subscription_table').deleteRow(i);
		}
	}
</script>