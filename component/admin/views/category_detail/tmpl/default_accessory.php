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

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_ACCESSORY_PRODUCT'); ?></h3>
			</div>
			<div class="box-body">
				<?php
					echo RedshopLayoutHelper::render(
							'system.message',
							array(
								'msgList' => array(
									'info' => array(
										JText::_('COM_REDSHOP_CATEGORY_ACCESSORY_PRODUCT_INFO')
									)
								),
								'showHeading' => false,
								'allowClose' => false
							)
						);
				?>
				<table class="admintable table">
					<tr>
						<td VALIGN="TOP" class="key" align="center"><?php echo JText::_('COM_REDSHOP_PRODUCT_SOURCE'); ?> <br/>
							<br/>
							<?php
							echo JHtml::_('redshopselect.search', '',
								'category_accessory_search',
								array(
									'select2.options' => array(
										'events' => array(
											'select2-selecting' => 'function(e) {create_table_accessory(e.object.text, e.object.id, e.object.price)}',
											'select2-close' => 'function(e) {$(this).select2("val", "")}'
										)
									),
									'select2.ajaxOptions' => array('typeField' => ', accessoryList: function(){
										var listAcc = [];
										jQuery(\'input.childProductAccessory\').each(function(){
											listAcc[listAcc.length] = jQuery(this).val();
										});
										return listAcc.join(",");
									}'),
								)
							);
							?>
						</td>
						<td></td>
					</tr>
					<tr>
						<td colspan="2">
							<table id="accessory_table" class="adminlist table table-striped" border="0">
								<thead>
								<tr>
									<th width="400"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
									<th width="75"><?php echo JText::_('COM_REDSHOP_PRODUCT_NORMAIL_PRICE'); ?></th>
									<th width="50"><?php echo JText::_('COM_REDSHOP_OPRAND'); ?></th>
									<th width="75"><?php echo JText::_('COM_REDSHOP_ADDED_VALUE'); ?></th>
									<th width="15%"><?php echo JText::_('COM_REDSHOP_ORDERING'); ?></th>
									<th width="50"><?php echo JText::_('COM_REDSHOP_DELETE'); ?></th>
								</tr>
								</thead>
								<tbody>
								<?php

								$accessory_product = $this->lists['categroy_accessory_product'];

								for ($f = 0, $fn = count($accessory_product); $f < $fn; $f++)
								{
									$accessory_main_price = 0;
									if ($accessory_product[$f]->product_id && $accessory_product[$f]->accessory_id)
									{
										$accessory_main_price = $producthelper->getAccessoryPrice($accessory_product[$f]->product_id, $accessory_product[$f]->newaccessory_price, $accessory_product[$f]->accessory_main_price, 1);
									}
									$checked = ($accessory_product[$f]->setdefault_selected) ? "checked" : "";    ?>
									<tr>
										<td><?php echo $accessory_product[$f]->product_name;?>
											<input type="hidden" value="<?php echo $accessory_product[$f]->child_product_id; ?>" class="childProductAccessory"
											       name="product_accessory[<?php echo $f; ?>][child_product_id]">
											<input type="hidden" value="<?php echo $accessory_product[$f]->accessory_id; ?>"
											       name="product_accessory[<?php echo $f; ?>][accessory_id]"></td>
										<td><?php echo $accessory_main_price[1];?></td>
										<td><input size="1" maxlength="1" class="text_area input-small text-center" type="text"
										           value="<?php echo $accessory_product[$f]->oprand; ?>"
										           onchange="javascript:oprand_check(this);"
										           name="product_accessory[<?php echo $f; ?>][oprand]"></td>
										<td><input size="5" class="text_area input-small text-center" type="text"
										           value="<?php echo $accessory_product[$f]->accessory_price; ?>"
										           name="product_accessory[<?php echo $f; ?>][accessory_price]"></td>
										<td><input type="text" name="product_accessory[<?php echo $f; ?>][ordering]" size="5"
										           value="<?php echo $accessory_product[$f]->ordering; ?>" class="text_area input-small text-center"
										           style="text-align: center"/></td>
										<!-- <td><input value="1" class="button" type="checkbox" name="product_accessory[<?php echo $f;?>][setdefault_selected]" <?php echo $checked;?>></td>-->
										<td><input value="<?php echo JText::_('COM_REDSHOP_DELETE'); ?>"
										           onclick="deleteRow_accessory(this,<?php echo $accessory_product[$f]->accessory_id; ?>,<?php echo $accessory_product[$f]->category_id; ?>,<?php echo $accessory_product[$f]->child_product_id ?>);"
										           class="button btn btn-danger" type="button"></td>
									</tr>
								<?php }    ?>
								</tbody>
							</table>
							<input type="hidden" name="total_accessory" id="total_accessory" value="<?php echo $f; ?>"/></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

