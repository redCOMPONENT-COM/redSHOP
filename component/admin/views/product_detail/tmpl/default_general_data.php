<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$editor = JFactory::getEditor();
$calendarFormat = '%d-%m-%Y';
?>

<div class="row">
	<div class="col-sm-4">
		<div class="form-group">
			<label for="product_name"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></label>
			<input class="form-control"
				type="text"
				name="product_name"
				id="product_name"
				size="32"
				maxlength="250"
				value="<?php echo htmlspecialchars($this->detail->product_name); ?>" />
			<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NAME'), JText::_('COM_REDSHOP_PRODUCT_NAME'), 'tooltip.png', '', '', false); ?>
		</div>

		<div class="form-group">
			<label for="product_number"><?php echo JText::_('COM_REDSHOP_PRODUCT_NUMBER'); ?></label>
			<input class="form-control"
				type="text"
				name="product_number"
				id="product_number"
				size="32"
				maxlength="250"
				value="<?php echo $this->detail->product_number; ?>"
				/>
			<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_NUMBER'), JText::_('COM_REDSHOP_PRODUCT_NUMBER'), 'tooltip.png', '', '', false); ?>
		</div>

		<div class="form-group">
			<label for="product_template"><?php echo JText::_('COM_REDSHOP_PRODUCT_TEMPLATE'); ?></label>
			<?php echo $this->lists['product_template']; ?>
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
		</div>

		<div class="form-group">
			<label for="product_type"><?php echo JText::_('COM_REDSHOP_PRODUCT_TYPE'); ?></label>
			<?php echo $this->lists['product_type']; ?>
			<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_PRODUCT_TYPE_TIP'), JText::_('COM_REDSHOP_PRODUCT_TYPE'), 'tooltip.png', '', '', false); ?>
		</div>

		<div class="form-group">
			<label for="product_parent_id"><?php echo JText::_('COM_REDSHOP_PARENT_PRODUCT'); ?></label>
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
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PARENT_PRODUCT'), JText::_('COM_REDSHOP_PARENT_PRODUCT'), 'tooltip.png', '', '', false);
			?>
		</div>

		<div class="form-group">
			<label for="categories"><?php echo JText::_('COM_REDSHOP_PRODUCT_CATEGORY'); ?></label>
			<?php echo $this->lists['categories']; ?>
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
		</div>

		<div class="form-group">
			<label for="manufacturer_id"><?php echo JText::_('COM_REDSHOP_PRODUCT_MANUFACTURER'); ?></label>
			<?php echo $this->lists['manufacturers']; ?>
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
		</div>

		<div class="form-group">
			<label for="supplier_id"><?php echo JText::_('COM_REDSHOP_SUPPLIER'); ?></label>
			<?php echo $this->lists['supplier']; ?>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SUPPLIER'), JText::_('COM_REDSHOP_SUPPLIER'), 'tooltip.png', '', '', false);
			?>
		</div>

		<div class="form-group">
			<label for="published0"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?></label>
			<?php echo $this->lists['published'];?>
		</div>

	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label for="product_price"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></label>
			<input class="form-control"
				type="text"
				name="product_price"
				id="product_price"
				size="10"
				maxlength="10"
				value="<?php echo $this->detail->product_price; ?>"
			/>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PRICE'), JText::_('COM_REDSHOP_PRODUCT_PRICE'), 'tooltip.png', '', '', false);
			?>
		</div>

		<div class="form-group">
			<label for="product_tax_group_id"><?php echo JText::_('COM_REDSHOP_PRODUCT_TAX_GROUP'); ?></label>
			<?php echo $this->lists['product_tax_group_id']; ?>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_TAX'), JText::_('COM_REDSHOP_PRODUCT_TAX_GROUP'), 'tooltip.png', '', '', false);
			?>
		</div>

		<div class="form-group">
			<label for="minimum_per_product_total"><?php echo JText::_('COM_REDSHOP_MINIMUM_PER_PRODUCT_TOTAL_LBL'); ?></label>
			<input class="form-control"
				type="text"
				name="minimum_per_product_total"
				id="minimum_per_product_total"
				size="10"
				maxlength="10"
				value="<?php echo $this->detail->minimum_per_product_total;?>" />
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_MINIMUM_PER_PRODUCT_TOTAL'), JText::_('COM_REDSHOP_MINIMUM_PER_PRODUCT_TOTAL_LBL'), 'tooltip.png', '', '', false);
			?>
		</div>

		<div class="form-group">
			<div class="alert alert-info">
				<?php
					$isProductOnSale = ($this->detail->product_on_sale) ? JText::_('JYES') : JText::_('JNO');
					echo JText::sprintf('COM_REDSHOP_PRODUCT_ON_SALE_HINT', $isProductOnSale);
				?>
			</div>
		</div>

		<div class="form-group">
			<label for="discount_price"><?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE'); ?></label>
			<input class="form-control"
				   type="text"
				   name="discount_price"
				   id="discount_price"
				   size="10"
				   maxlength="10"
				   value="<?php echo $this->detail->discount_price; ?>"
				/>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PRICE'), JText::_('COM_REDSHOP_DISCOUNT_PRICE'), 'tooltip.png', '', '', false);
			?>
		</div>

		<div class="form-group">
			<label for="discount_stratdate"><?php echo JText::_('COM_REDSHOP_DISCOUNT_START_DATE'); ?></label>
			<?php
				$sdate = "";

				if ($this->detail->discount_stratdate)
				{
					if ($startDateTimeStamp = strtotime($this->detail->discount_stratdate))
					{
						$this->detail->discount_stratdate = $startDateTimeStamp;
					}

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
		</div>

		<div class="form-group">
			<label for="discount_enddate"><?php echo JText::_('COM_REDSHOP_DISCOUNT_END_DATE'); ?></label>
			<?php
				$edate = "";

				if ($this->detail->discount_enddate)
				{
					if ($endDateTimeStamp = strtotime($this->detail->discount_enddate))
					{
						$this->detail->discount_enddate = $endDateTimeStamp;
					}

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
		</div>

		<div class="form-group">
			<label for="preorder"><?php echo JText::_('COM_REDSHOP_PRODUCT_PREORDER'); ?></label>
			<?php echo $this->lists['preorder']; ?>
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
		</div>

		<?php if (ALLOW_PRE_ORDER) : ?>
		<div class="form-group">
			<label><?php echo JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL'); ?></label>
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
		</div>
		<?php endif; ?>

		<div class="form-group">
			<label for="min_order_product_quantity"><?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_PRODUCT_QUANTITY_LBL'); ?></label>
			<input class="form-control"
				   type="text"
				   name="min_order_product_quantity"
				   id="min_order_product_quantity"
				   size="10"
				   maxlength="10"
				   value="<?php echo $this->detail->min_order_product_quantity; ?>"
				/>
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
		</div>

		<div class="form-group">
			<label for="max_order_product_quantity"><?php echo JText::_('COM_REDSHOP_MAXIMUM_ORDER_PRODUCT_QUANTITY_LBL'); ?></label>
			<input class="form-control"
				   type="text"
				   name="max_order_product_quantity"
				   id="max_order_product_quantity"
				   size="10"
				   maxlength="10"
				   value="<?php echo @$this->detail->max_order_product_quantity; ?>"
				/>
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
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label for="product_volume"><?php echo JText::_('COM_REDSHOP_PRODUCT_VOLUME'); ?></label>
			<input class="form-control"
				   type="text"
				   name="product_volume"
				   id="product_volume"
				   size="10"
				   maxlength="10"
				   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_volume); ?>"
				/>
			<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?>3
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
		</div>

		<div class="form-group">
			<label for="product_length"><?php echo JText::_('COM_REDSHOP_PRODUCT_LENGTH'); ?></label>
			<input class="form-control"
				   type="text"
				   name="product_length"
				   id="product_length"
				   size="10"
				   maxlength="10"
				   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_length); ?>"
				/>
			<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?>
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
		</div>

		<div class="form-group">
			<label for="product_width"><?php echo JText::_('COM_REDSHOP_PRODUCT_WIDTH'); ?></label>
			<input class="form-control"
				   type="text"
				   name="product_width"
				   id="product_width"
				   size="10"
				   maxlength="10"
				   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_width); ?>"
				/>
			<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?>
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
		</div>

		<div class="form-group">
			<label for="product_height"><?php echo JText::_('COM_REDSHOP_PRODUCT_HEIGHT'); ?></label>
			<input class="form-control"
				   type="text"
				   name="product_height"
				   id="product_height"
				   size="10"
				   maxlength="10"
				   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_height); ?>"
				/>
			<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_HEIGHT'), JText::_('COM_REDSHOP_PRODUCT_HEIGHT'), 'tooltip.png', '', '', false);
			?>
		</div>

		<div class="form-group">
			<label for="product_diameter"><?php echo JText::_('COM_REDSHOP_PRODUCT_DIAMETER'); ?></label>
			<input class="text_area"
				   type="text"
				   name="product_diameter"
				   id="product_diameter"
				   size="10"
				   maxlength="10"
				   value="<?php echo $this->producthelper->redunitDecimal($this->detail->product_diameter); ?>"
				/>
			<?php echo Redshop::getConfig()->get('DEFAULT_VOLUME_UNIT'); ?>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DIAMETER'), JText::_('COM_REDSHOP_PRODUCT_DIAMETER'), 'tooltip.png', '', '', false);
			?>
		</div>

		<div class="form-group">
			<label for="weight"><?php echo JText::_('COM_REDSHOP_WEIGHT_LBL'); ?></label>
			<input class="text_area"
				   type="text"
				   name="weight"
				   id="weight"
				   size="10"
				   maxlength="10"
				   value="<?php echo $this->producthelper->redunitDecimal($this->detail->weight); ?>"
				/>
			<?php echo Redshop::getConfig()->get('DEFAULT_WEIGHT_UNIT'); ?>
			<?php
			echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_WEIGHT'), JText::_('COM_REDSHOP_WEIGHT_LBL'), 'tooltip.png', '', '', false);
			?>
		</div>

		<div class="form-group">
			<label for="product_special0"><?php echo JText::_('COM_REDSHOP_PRODUCT_SPECIAL'); ?></label>
			<?php echo $this->lists['product_special']; ?>
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
		</div>

		<div class="form-group">
			<label for="expired0"><?php echo JText::_('COM_REDSHOP_PRODUCT_EXPIRED'); ?></label>
			<?php echo $this->lists['expired']; ?>
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
		</div>

		<div class="form-group">
			<label for="not_for_sale0"><?php echo JText::_('COM_REDSHOP_PRODUCT_NOT_FOR_SALE'); ?></label>
			<?php echo $this->lists['not_for_sale'];?>
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
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<label><?php echo JText::_('COM_REDSHOP_SHORT_DESCRIPTION'); ?></label>
			<?php echo $editor->display("product_s_desc", $this->detail->product_s_desc, '$widthPx', '$heightPx', '100', '20'); ?>
		</div>

		<div class="form-group">
			<label><?php echo JText::_('COM_REDSHOP_FULL_DESCRIPTION'); ?></label>
			<?php echo $editor->display("product_desc", $this->detail->product_desc, '$widthPx', '$heightPx', '100', '20'); ?>
		</div>
	</div>
</div>
