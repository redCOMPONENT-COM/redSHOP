<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES'); ?></h3>
			</div>
			<div class="box-body">
				<fieldset class="adminform">
					<div class="alert alert-success" role="alert"><?php echo JText::_('COM_REDSHOP_HINT_ATTRIBUTE'); ?></div>

					<div class="col-sm-4">
						<?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE_SET_LBL'); ?>
					</div>
					<div class="col-sm-8">
						 <?php echo $this->lists['attributesSet']; ?>
					</div>

					<div class="col-sm-4">
						<?php echo JText::_('COM_REDSHOP_COPY_ATTRIBUTES_FROM_ATTRIBUTE_SET'); ?>
					</div>
					<div class="col-sm-8">
						 <?php echo $this->lists['copy_attribute']; ?>
					</div>
					
					<a class="btn btn-success add_attribute btn-small pull-right" href="#"> <?php echo '+ ' . JText::_('COM_REDSHOP_NEW_ATTRIBUTE'); ?></a>
				</fieldset>
				<hr/>
				<?php echo RedshopLayoutHelper::render('product_detail.product_attribute', array('this' => $this)); ?>
			</div>
		</div>
	</div>
</div>

