<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == "shopper_group.cancel" || document.formvalidator.isValid(document.getElementById("adminForm"))) {
            Joomla.submitform(task);
        }
    };
</script>
<form action="index.php?option=com_redshop&view=shopper_group&task=shopper_group.edit&shopper_group_id=<?php echo $this->item->shopper_group_id ?>"
      method="post" id="adminForm" name="adminForm" class="adminForm form-validate form-horizontal" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_WRAPPER_INFORMATION') ?></h3>
                </div>
                <div class="box-body">
					<?php echo $this->form->renderField('shopper_group_name') ?>
					<?php echo $this->form->renderField('parent_id') ?>
					<?php echo $this->form->renderField('shopper_group_customer_type') ?>
					<?php echo $this->form->renderField('shopper_group_portal') ?>
					<?php echo $this->form->renderField('shopper_group_categories') ?>
					<?php echo $this->form->renderField('shopper_group_manufactures') ?>
					<?php echo $this->form->renderField('published') ?>
                    <div class="form-group">
                        <label class="control-label col-md-2"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_LOGO') ?></label>
                        <div class="col-md-10">
							<?php echo RedshopHelperMediaImage::render(
								'shopper_group_logo',
								'shopperlogo',
								$this->item->shopper_group_id,
								'shopperlogo',
								$this->item->shopper_group_logo
							) ?>
                        </div>
                    </div>
					<?php echo $this->form->renderField('shopper_group_introtext') ?>
					<?php echo $this->form->renderField('shopper_group_desc') ?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_WRAPPER_CONFIGURATION') ?></h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="jform_url"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_URL') ?></label>
                        <div class="col-md-10">
							<?php if (!empty($this->item->shopper_group_id)): ?>
								<?php
								$preview = JURI::root() . "index.php?option=com_redshop&view=login&layout=portal&protalid=" . $this->item->shopper_group_id;
								$preview .= "&Itemid=" . Redshop::getConfig()->get('PORTAL_LOGIN_ITEMID');
								$preview = JRoute::_($preview, false);
								?>
                                <a id="jform_url" href="<?php echo $preview ?>" target="_blank"><?php echo $preview ?></a>
							<?php else: ?>
                                <p id="jform_url" class="text-warning"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_URL_GENERATE_AFTER_SAVE') ?></p>
							<?php endif; ?>
                        </div>
                    </div>
					<?php echo $this->form->renderField('shopper_group_cart_itemid') ?>
					<?php echo $this->form->renderField('default_shipping') ?>
					<?php echo $this->form->renderField('default_shipping_rate') ?>
					<?php echo $this->form->renderField('shopper_group_cart_checkout_itemid') ?>
					<?php
					/*
					 * @TODO: Since old layout, this field has been hidden
					echo $this->form->renderField('tax_group_id');
					echo $this->form->renderField('apply_product_vat_price')
					echo $this->form->renderField('is_logged_in')
					 */
					?>
					<?php echo $this->form->renderField('show_price_without_vat') ?>
					<?php echo $this->form->renderField('show_price') ?>
					<?php echo $this->form->renderField('use_as_catalog') ?>
					<?php echo $this->form->renderField('shopper_group_quotation_mode') ?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value=""/>
	<?php echo $this->form->getInput('shopper_group_id') ?>
	<?php echo JHtml::_('form.token'); ?>
</form>
