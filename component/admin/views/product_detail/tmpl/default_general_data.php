<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$editor = JFactory::getEditor();
$calendarFormat = '%d-%m-%Y';
?>

<table class="admintable" border="0">

	<tr>
		<td width="50%">

			<table>

				<tr>
					<td class="key">
						<label for="product_name">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="product_name"
							   id="product_name"
							   size="32"
							   maxlength="250"
							   value="<?php echo htmlspecialchars($this->detail->product_name); ?>"
							/>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NAME'), JText::_('COM_REDSHOP_PRODUCT_NAME'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="product_number">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_NUMBER'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="product_number"
							   id="product_number"
							   size="32"
							   maxlength="250"
							   value="<?php echo $this->detail->product_number; ?>"
							/>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NUMBER'), JText::_('COM_REDSHOP_PRODUCT_NUMBER'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="product_template">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_TEMPLATE'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['product_template']; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_TEMPLATE'),
							JText::_('COM_REDSHOP_PRODUCT_TEMPLATE'),
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
						<label for="product_type">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_TYPE'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['product_type']; ?>
						<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_PRODUCT_TYPE_TIP'), JText::_('COM_REDSHOP_PRODUCT_TYPE'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="published0">
							<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['published'];?>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="parent">
							<?php echo JText::_('COM_REDSHOP_PARENT_PRODUCT'); ?>
						</label>
					</td>
					<td>
						<?php
						echo JHtml::_('redshopselect.search', $this->producthelper->getProductByID($this->detail->product_parent_id),
							'product_parent_id',
							array(
								'select2.options' => array('multiple' => 'false', 'placeholder' => JText::_('COM_REDSHOP_PARENT_PRODUCT')),
								'option.key' => 'product_id',
								'option.text' => 'product_name',
								'select2.ajaxOptions' => array('typeField' => ', parent:1, product_id:' . $this->detail->product_id)
							)
						);
						?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PARENT_PRODUCT'), JText::_('COM_REDSHOP_PARENT_PRODUCT'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="product_category">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_CATEGORY'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['categories']; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_CATEGORY'),
							JText::_('COM_REDSHOP_PRODUCT_CATEGORY'),
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

				<tr>
					<td class="key">
						<label for="manufacturer_id">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_MANUFACTURER'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['manufacturers']; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_MANUFACTURER'),
							JText::_('COM_REDSHOP_PRODUCT_MANUFACTURER'),
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
						<label for="supplier_id">
							<?php echo JText::_('COM_REDSHOP_SUPPLIER'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['supplier']; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SUPPLIER'), JText::_('COM_REDSHOP_SUPPLIER'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label>
							<?php echo JText::_('COM_REDSHOP_SHORT_DESCRIPTION'); ?>
						</label>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<?php echo $editor->display("product_s_desc", $this->detail->product_s_desc, '$widthPx', '$heightPx', '100', '20'); ?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label>
							<?php echo JText::_('COM_REDSHOP_FULL_DESCRIPTION'); ?>
						</label>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<?php echo $editor->display("product_desc", $this->detail->product_desc, '$widthPx', '$heightPx', '100', '20'); ?>
					</td>
				</tr>
			</table>
		</td>

		<td width="50%" valign="top">

			<table>

				<tr>
					<td class="key">
						<label for="product_price">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="product_price"
							   id="product_price"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->detail->product_price; ?>"
							/>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PRICE'), JText::_('COM_REDSHOP_PRODUCT_PRICE'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="product_tax_group_id">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_TAX_GROUP'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['product_tax_group_id']; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_TAX'), JText::_('COM_REDSHOP_PRODUCT_TAX_GROUP'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="minimum_per_product_total">
							<?php echo JText::_('COM_REDSHOP_MINIMUM_PER_PRODUCT_TOTAL_LBL'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="minimum_per_product_total"
							   id="minimum_per_product_total"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->detail->minimum_per_product_total;?>" />
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_MINIMUM_PER_PRODUCT_TOTAL'),
							JText::_('COM_REDSHOP_MINIMUM_PER_PRODUCT_TOTAL_LBL'),
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

				<tr>
					<td class="key">
						<label for="discount_price">
							<?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="discount_price"
							   id="discount_price"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->detail->discount_price; ?>"
							/>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PRICE'), JText::_('COM_REDSHOP_DISCOUNT_PRICE'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="discount_stratdate">
							<?php echo JText::_('COM_REDSHOP_DISCOUNT_START_DATE'); ?>
						</label>
					</td>
					<td>
						<?php
						$sdate = "";

						if ($this->detail->discount_stratdate)
						{
							$sdate = date("d-m-Y", $this->detail->discount_stratdate);
						}

						echo JHtml::_(
							'calendar',
							$sdate,
							'discount_stratdate',
							'discount_stratdate',
							$calendarFormat,
							array('class' => 'inputbox', 'size' => '15',  'maxlength' => '19')
						);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="discount_enddate">
							<?php echo JText::_('COM_REDSHOP_DISCOUNT_END_DATE'); ?>
						</label>
					</td>
					<td>
						<?php
						$edate = "";

						if ($this->detail->discount_enddate)
						{
							$edate = date("d-m-Y", $this->detail->discount_enddate);
						}

						echo JHtml::_(
							'calendar',
							$edate,
							'discount_enddate',
							'discount_enddate',
							$calendarFormat,
							array('class' => 'inputbox', 'size' => '15',  'maxlength' => '19')
						);
						?>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="product_on_sale0">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_ON_SALE'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['product_on_sale']; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ON_SALE'),
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_ON_SALE_LBL'),
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
						<label for="product_special0">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_SPECIAL'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['product_special']; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_SPECIAL'),
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_SPECIAL_LBL'),
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
						<label for="expired0">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_EXPIRED'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['expired']; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRED'),
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_EXPIRED_LBL'),
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
						<label for="not_for_sale0">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_NOT_FOR_SALE'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['not_for_sale'];?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NOT_FOR_SALE'),
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NOT_FOR_SALE_LBL'),
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
						<label for="preorder">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_PREORDER'); ?>
						</label>
					</td>
					<td>
						<?php echo $this->lists['preorder']; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREORDER'),
							JText::_('COM_REDSHOP_PRODUCT_PREORDER'),
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

				<tr>
					<td class="key">
						<label for="min_order_product_quantity">
							<?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_PRODUCT_QUANTITY_LBL'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="min_order_product_quantity"
							   id="min_order_product_quantity"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->detail->min_order_product_quantity; ?>"
							/>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_MINIMUM_ORDER_PRODUCT_QUANTITY'),
							JText::_('COM_REDSHOP_MINIMUM_ORDER_PRODUCT_QUANTITY_LBL'),
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
						<label for="max_order_product_quantity">
							<?php echo JText::_('COM_REDSHOP_MAXIMUM_ORDER_PRODUCT_QUANTITY_LBL'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="max_order_product_quantity"
							   id="max_order_product_quantity"
							   size="10"
							   maxlength="10"
							   value="<?php echo @$this->detail->max_order_product_quantity; ?>"
							/>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_MAXIMUM_ORDER_PRODUCT_QUANTITY'),
							JText::_('COM_REDSHOP_TOOLTIP_MAXIMUM_ORDER_PRODUCT_QUANTITY'),
							'tooltip.png',
							'',
							'',
							false
						);
						?>
					</td>
				</tr>

				<?php if (ALLOW_PRE_ORDER) : ?>
					<tr>
						<td style="color: red;"  class="key">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL'); ?>
						</td>
						<td>
							<?php
							$availability_date = "";

							if ($this->detail->product_availability_date)
							{
								$availability_date = date("d-m-Y", $this->detail->product_availability_date);
							}

							echo JHtml::_(
								'calendar',
								$availability_date,
								'product_availability_date',
								'product_availability_date',
								$calendarFormat,
								array('class' => 'inputbox', 'size' => '15',  'maxlength' => '19')
							);

							echo JHtml::tooltip(
								JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_AVAILABILITY_DATE'),
								JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL'),
								'tooltip.png',
								'',
								'',
								false
							);
							?>
						</td>
					</tr>
				<?php endif; ?>

				<tr>
					<td colspan="2">
						<hr/>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="product_volume">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_VOLUME'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="product_volume"
							   id="product_volume"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_volume); ?>"
							/>
						<?php echo DEFAULT_VOLUME_UNIT; ?>3
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_VOLUME'),
							JText::_('COM_REDSHOP_PRODUCT_VOLUME'),
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
						<label for="product_length">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_LENGTH'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="product_length"
							   id="product_length"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_length); ?>"
							/>
						<?php echo DEFAULT_VOLUME_UNIT; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_LENGTH'),
							JText::_('COM_REDSHOP_PRODUCT_LENGTH'),
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
						<label for="product_width">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_WIDTH'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="product_width"
							   id="product_width"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_width); ?>"
							/>
						<?php echo DEFAULT_VOLUME_UNIT; ?></td>
					<td>
						<?php
						echo JHtml::tooltip(
							JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_WIDTH'),
							JText::_('COM_REDSHOP_PRODUCT_WIDTH'),
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
						<label for="product_height">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_HEIGHT'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="product_height"
							   id="product_height"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_height); ?>"
							/>
						<?php echo DEFAULT_VOLUME_UNIT; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HEIGHT'), JText::_('COM_REDSHOP_PRODUCT_HEIGHT'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="product_diameter">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_DIAMETER'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="product_diameter"
							   id="product_diameter"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_diameter); ?>"
							/>
						<?php echo DEFAULT_VOLUME_UNIT; ?>
					</td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DIAMETER'), JText::_('COM_REDSHOP_PRODUCT_DIAMETER'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

				<tr>
					<td class="key">
						<label for="weight">
							<?php echo JText::_('COM_REDSHOP_WEIGHT_LBL'); ?>
						</label>
					</td>
					<td>
						<input class="text_area"
							   type="text"
							   name="weight"
							   id="weight"
							   size="10"
							   maxlength="10"
							   value="<?php echo $this->producthelper->redunitDecimal($this->detail->weight); ?>"
							/>
						<?php echo DEFAULT_WEIGHT_UNIT; ?></td>
					<td>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_WEIGHT'), JText::_('COM_REDSHOP_WEIGHT_LBL'), 'tooltip.png', '', '', false);
						?>
					</td>
				</tr>

			</table>

		</td>
	</tr>

</table>
