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

<div class="panel panel-primary form-vertical">
    <div class="panel-heading">
        <h3><?php echo JText::_('COM_REDSHOP_MANUFACTURER_SETTINGS'); ?></h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label for="manufacturer_template" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_TEMPLATE_FOR_VM_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_TEMPLATE_LBL'); ?>
            </label>
			<?php echo $this->lists ['manufacturer_template'] ?>
        </div>
        <div class="form-group">
            <label for="default_manufacturer_ordering_method" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_ORDERING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_ORDERING_METHOD_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_ORDERING_METHOD_LBL'); ?>
            </label>
			<?php echo $this->lists['default_manufacturer_ordering_method'] ?>
        </div>
        <div class="form-group">
            <label for="default_manufacturer_product_ordering_method" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD_LBL'); ?>
            </label>
			<?php echo $this->lists['default_manufacturer_product_ordering_method'] ?>
        </div>
        <div class="form-group">
            <label for="manufacturer_title_max_chars" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_MAX_CHARS'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_MAX_CHARS_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_MANUFACTURER_MAX_CHARS_LBL'); ?>
            </label>
            <input type="number" name="manufacturer_title_max_chars" id="manufacturer_title_max_chars" class="form-control"
                   value="<?php echo $this->config->get('MANUFACTURER_TITLE_MAX_CHARS'); ?>"/>
        </div>
        <div class="form-group">
            <label for="manufacturer_title_end_suffix" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_END_SUFFIX'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MANUFACTURER_TITLE_END_SUFFIX_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_MANUFACTURER_TITLE_END_SUFFIX_LBL'); ?>
            </label>
            <input type="number" name="manufacturer_title_end_suffix" id="manufacturer_title_end_suffix" class="form-control"
                   value="<?php echo $this->config->get('MANUFACTURER_TITLE_END_SUFFIX'); ?>">
        </div>
        <div class="form-group">
            <label for="manufacturer_mail_enable" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_ENABLE_MANUFACTURER_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_MANUFACTURER_EMAIL_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_ENABLE_MANUFACTURER_EMAIL_LBL'); ?>
            </label>
			<?php echo $this->lists ['manufacturer_mail_enable'] ?>
        </div>
        <div class="form-group">
            <label for="supplier_mail_enable" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_ENABLE_SUPPLIER_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_SUPPLIER_EMAIL_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_ENABLE_SUPPLIER_EMAIL_LBL'); ?>
            </label>
			<?php echo $this->lists ['supplier_mail_enable'] ?>
        </div>
    </div>
</div>
