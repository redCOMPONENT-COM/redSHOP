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

<?php
echo JHtml::_('bootstrap.startTabSet', 'product-pane', array('active' => 'product'));
echo JHtml::_('bootstrap.addTab', 'product-pane', 'product', JText::_('COM_REDSHOP_PRODUCT', true));
?>
<div class="row">
	<div class="col-sm-6">
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('product_unit');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('download');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('wrapping');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('catalog');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('color_sample');?>
		</fieldset>
	</div>

	<div class="col-sm-6">
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('product_template_image_settings');?>
		</fieldset>
	</div>
</div>

<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php
echo JHtml::_('bootstrap.addTab', 'product-pane', 'accessory', JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_TAB', true));
echo $this->loadTemplate('accessory_product');
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.addTab', 'product-pane', 'related', JText::_('COM_REDSHOP_RELATED_PRODUCTS', true));
echo $this->loadTemplate('related_product');
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.endTabSet');
?>