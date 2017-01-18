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

<form action="index.php?option=com_redshop&task=mass_discount.edit&id=<?php echo $this->item->id ?>" method="post"
	  id="adminForm" name="adminForm" class="form-validate form-horizontal" enctype="multipart/form-data">
	<fieldset class="adminform">
		<div class="row">
			<div class="col-sm-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAIL') ?></h3>
					</div>
					<div class="box-body">
						<?php echo $this->form->renderField('name') ?>
						<?php echo $this->form->renderField('type') ?>
						<?php echo $this->form->renderField('amount') ?>
						<?php echo $this->form->renderField('start_date') ?>
						<?php echo $this->form->renderField('end_date') ?>
                        <div class="description">
                            <div class="description-header">
                                <small><?php echo JText::_('COM_REDSHOP_MASS_DISCOUNT_DETAIL_CHOOSE_ONE_OF_THESE_FIELDS_DESCRIPTION');?></small>
                            </div>
                            <div class="description-content">
                                <small>
									<?php echo JText::_('COM_REDSHOP_MASS_DISCOUNT_DETAIL_PRODUCTS_DESCRIPTION');?><br />
									<?php echo JText::_('COM_REDSHOP_MASS_DISCOUNT_DETAIL_CATEGORIES_DESCRIPTION');?><br />
									<?php echo JText::_('COM_REDSHOP_MASS_DISCOUNT_DETAIL_MANUFACTURERS_DESCRIPTION');?><br />
                                </small>
                            </div>
							<?php echo $this->form->renderField('discount_product') ?>
							<?php echo $this->form->renderField('category_id') ?>
							<?php echo $this->form->renderField('manufacturer_id') ?>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="task" value=""/>
	<?php echo $this->form->getInput('id') ?>
</form>
