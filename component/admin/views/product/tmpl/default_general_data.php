<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

$editor         = JFactory::getEditor();
$calendarFormat = '%d-%m-%Y';
?>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            var productNumber = [];

            $.post(
                "index.php?option=com_redshop&view=product&task=product.ajaxGetAllProductNumber",
                {
                    "<?php echo JSession::getFormToken() ?>": 1,
                    "product_id": <?php echo (int) $this->item->product_id ?>
                },
                function (response) {
                    productNumber = response.split(',');
                });

            document.formvalidator.setHandler("productNumber", function (value) {
                return !productNumber.contains(value);
            });
        });
    })(jQuery);
</script>
<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_PRODUCT_INFORMATION'); ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
							<?php echo $this->form->renderField('product_name') ?>
                        </div>

                        <div class="form-group">
							<?php echo $this->form->renderField('product_number') ?>
                        </div>

                        <div class="form-group">
                            <label for="categories">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_CATEGORY'); ?>
                                <span class="star text-danger"> *</span>
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
                            </label>
							<?php echo $this->lists['categories']; ?>
                        </div>

                        <div class="form-group">
                            <label for="product_template">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_TEMPLATE'); ?>
                                <span class="star text-danger"> *</span>
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
                            </label>
							<?php echo $this->lists['product_template']; ?>
                        </div>

                        <div class="form-group">
                            <label for="manufacturer_id">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_MANUFACTURER'); ?>
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
                            </label>
							<?php echo $this->lists['manufacturers']; ?>
                        </div>

                        <div class="form-group">
                            <label for="published0"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?></label>
							<?php echo $this->lists['published']; ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
							<?php echo $this->form->renderField('product_price') ?>
                        </div>

                        <div class="form-group">
                            <label for="product_tax_group_id">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_TAX_GROUP'); ?>
								<?php
								echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_TAX'), JText::_('COM_REDSHOP_PRODUCT_TAX_GROUP'), 'tooltip.png', '', '', false);
								?>
                            </label>
							<?php echo $this->lists['product_tax_group_id']; ?>
                        </div>

                        <div class="form-group">
                            <label for="discount_price">
								<?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE'); ?>
								<?php
								echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PRICE'), JText::_('COM_REDSHOP_DISCOUNT_PRICE'), 'tooltip.png', '', '', false);
								?>
                            </label>

                            <div class="input-group">
                                <span class="input-group-addon"><?php echo Redshop::getConfig()->get('REDCURRENCY_SYMBOL') ?></span>
                                <input class="form-control"
                                       type="text"
                                       name="discount_price"
                                       id="discount_price"
                                       size="10"
                                       maxlength="10"
                                       value="<?php echo $this->item->discount_price; ?>"
                                />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="discount_stratdate"><?php echo JText::_('COM_REDSHOP_DISCOUNT_START_DATE'); ?></label>
							<?php
							$startDate = null;

							if ($this->item->discount_stratdate)
							{
								$startDate = JFactory::getDate($this->item->discount_stratdate)->format("d-m-Y");
							}

							echo JHtml::_(
								'calendar',
								$startDate,
								'discount_stratdate',
								'discount_stratdate',
								$calendarFormat,
								array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19')
							);
							?>
                        </div>

                        <div class="form-group">
                            <label for="discount_enddate"><?php echo JText::_('COM_REDSHOP_DISCOUNT_END_DATE'); ?></label>
							<?php
							$endDate = null;

							if ($this->item->discount_enddate)
							{
								$endDate = JFactory::getDate($this->item->discount_enddate)->format("d-m-Y");
							}

							echo JHtml::_(
								'calendar',
								$endDate,
								'discount_enddate',
								'discount_enddate',
								$calendarFormat,
								array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19')
							);
							?>
                        </div>

						<?php $display = "";
						if (!$this->item->discount_stratdate || !$this->item->discount_enddate) : ?>
							<?php $display = 'style="display: none"' ?>
						<?php endif; ?>

                        <div class="alert alert-info" <?php echo $display ?>>
							<?php
							$isProductOnSale = ($this->item->product_on_sale) ? JText::_('JYES') : JText::_('JNO');
							echo JText::sprintf('COM_REDSHOP_PRODUCT_ON_SALE_HINT', $isProductOnSale);
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label><?php echo JText::_('COM_REDSHOP_FULL_DESCRIPTION'); ?></label>
					<?php echo $editor->display("product_desc", $this->item->product_desc, '$widthPx', '$heightPx', '100', '20'); ?>
                </div>
                <div class="clearfix"></div>

                <div class="form-group">
                    <label><?php echo JText::_('COM_REDSHOP_SHORT_DESCRIPTION'); ?></label>
					<?php echo $editor->display("product_s_desc", $this->item->product_s_desc, '$widthPx', '$heightPx', '100', '20'); ?>
                </div>
            </div>
        </div>


    </div>

    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_PRODUCT_IMAGE'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
					<?php echo RedshopHelperMediaImage::render(
						'product_full_image',
						'product',
						$this->item->product_id,
						'product',
						$this->item->product_full_image,
						false
					) ?>
                </div>
				<?php if ($this->item->product_id > 0) : ?>
					<?php $ilink = 'index.php?tmpl=component&option=com_redshop&view=media&section_id='
						. $this->item->product_id . '&showbuttons=1&media_section=product'; ?>
                    <div class="form-group">
                        <a class="joom-box btn btn-primary" title="Image" href="<?php echo JRoute::_($ilink, false) ?>"
                           rel="{handler: 'iframe', size: {x: 950, y: 500}}">
							<?php echo JText::_('COM_REDSHOP_ADD_ADDITIONAL_IMAGES'); ?>
                        </a>
                    </div>
				<?php endif; ?>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_PRODUCT_MEASURES'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
					<?php echo $this->form->renderField('product_volume') ?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('product_length') ?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('product_width') ?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('product_height') ?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('product_diameter') ?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('weight') ?>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_ADDITIONAL_INFORMATION'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="supplier_id">
						<?php echo JText::_('COM_REDSHOP_SUPPLIER'); ?>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SUPPLIER'), JText::_('COM_REDSHOP_SUPPLIER'), 'tooltip.png', '', '', false);
						?>
                    </label>
					<?php echo $this->lists['supplier']; ?>
                </div>

                <div class="form-group">
                    <label for="product_type">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_TYPE'); ?>
						<?php echo JHtml::tooltip(JText::_('COM_REDSHOP_PRODUCT_TYPE_TIP'), JText::_('COM_REDSHOP_PRODUCT_TYPE'), 'tooltip.png', '', '', false); ?>
                    </label>
					<?php echo $this->lists['product_type']; ?>
                </div>

                <div class="form-group">
                    <label for="product_parent_id">
						<?php echo JText::_('COM_REDSHOP_PARENT_PRODUCT'); ?>
						<?php
						echo JHtml::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PARENT_PRODUCT'), JText::_('COM_REDSHOP_PARENT_PRODUCT'), 'tooltip.png', '', '', false);
						?>
                    </label>
					<?php
					echo JHtml::_('redshopselect.search', $this->producthelper->getProductByID($this->item->product_parent_id),
						'product_parent_id',
						array(
							'select2.options'     => array('multiple' => 'false', 'placeholder' => JText::_('COM_REDSHOP_PARENT_PRODUCT')),
							'option.key'          => 'product_id',
							'option.text'         => 'product_name',
							'select2.ajaxOptions' => array('typeField' => ', parent:1, product_id:' . (int) $this->item->product_id)
						)
					);
					?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('product_special') ?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('expired') ?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('not_for_sale') ?>
                </div>

                <div class="form-group">
                    <label for="preorder">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_PREORDER'); ?>
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
                    </label>
					<?php echo $this->lists['preorder']; ?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('minimum_per_product_total') ?>
                </div>

				<?php if (Redshop::getConfig()->get('ALLOW_PRE_ORDER')) : ?>
                    <div class="form-group">
                        <label>
							<?php echo JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL'); ?>
							<?php
							echo JHtml::tooltip(
								JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_AVAILABILITY_DATE'),
								JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL'),
								'tooltip.png',
								'',
								'',
								false
							);
							?>
                        </label>
						<?php
						$availability_date = "";

						if ($this->item->product_availability_date)
						{
							$availability_date = date("d-m-Y", $this->item->product_availability_date);
						}

						echo JHtml::_(
							'calendar',
							$availability_date,
							'product_availability_date',
							'product_availability_date',
							$calendarFormat,
							array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19')
						);
						?>
                    </div>
				<?php endif; ?>

                <div class="form-group">
					<?php echo $this->form->renderField('min_order_product_quantity') ?>
                </div>

                <div class="form-group">
					<?php echo $this->form->renderField('max_order_product_quantity') ?>
                </div>
            </div>
        </div>
    </div>
</div>
