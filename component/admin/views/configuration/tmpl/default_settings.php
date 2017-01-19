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
        <h3><?php echo JText::_('COM_REDSHOP_STORE_SETTINGS') ?></h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label for="shop_country" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_SHOP_NAME_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOP_NAME_LBL') ?>">
				<?php echo JText::_('COM_REDSHOP_SHOP_NAME_LBL'); ?>
            </label>
            <input type="text" name="shop_name" id="shop_name" value="<?php echo $this->config->get('SHOP_NAME') ?>" class="form-control"/>
        </div>

        <div class="form-group">
            <label for="shop_country" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_SHOP_COUNTRY') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOP_COUNTRY') ?>">
				<?php echo JText::_('COM_REDSHOP_SHOP_COUNTRY_LBL'); ?>
            </label>
			<?php echo $this->lists['shop_country']; ?>
        </div>

        <div class="form-group">
            <label for="default_shipping_country" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_COUNTRY_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_SHIPPING_COUNTRY_LBL') ?>">
				<?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_COUNTRY_LBL'); ?>
            </label>
			<?php echo $this->lists ['default_shipping_country']; ?>
        </div>

        <div class="form-group">
            <label for="default_dateformat" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_DEFAULT_DATEFORMAT_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_DATEFORMAT_LBL') ?>">
				<?php echo JText::_('COM_REDSHOP_DEFAULT_DATEFORMAT_LBL'); ?>
            </label>
			<?php echo $this->lists ['default_dateformat']; ?>
        </div>

        <div class="form-group">
            <label for="welcome_msg" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_WELCOME_MESSAGE') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WELCOME_MESSAGE') ?>">
				<?php echo JText::_('COM_REDSHOP_WELCOME_MESSAGE'); ?>
            </label>
            <input type="text" name="welcome_msg" id="welcome_msg" value="<?php echo $this->config->get('WELCOME_MSG') ?>" class="form-control"/>
        </div>
        <div class="form-group">
            <label for="administrator_email" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_ADMINISTRATOR_EMAIL_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADMINISTRATOR_EMAIL_LBL') ?>">
				<?php echo JText::_('COM_REDSHOP_ADMINISTRATOR_EMAIL_LBL'); ?>
            </label>
            <input type="text" name="administrator_email" id="administrator_email" value="<?php echo $this->config->get('ADMINISTRATOR_EMAIL'); ?>"
                   class="form-control"/>
        </div>
        <div class="form-group">
            <label for="invoice_mail_send_option" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_USE_ENCODING_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_ENCODING') ?>">
				<?php echo JText::_('COM_REDSHOP_USE_ENCODING_LBL'); ?>
            </label>
			<?php echo $this->lists ['use_encoding']; ?>
        </div>
        <div class="form-group">
            <label for="country_list" class="hasTip"
                   title="<?php echo JText::_('COM_REDSHOP_COUNTRY_LIST_LBL') ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COUNTRY_LIST_LBL') ?>">
				<?php echo JText::_('COM_REDSHOP_COUNTRY_LIST_LBL'); ?>
            </label>
			<?php echo $this->lists ['country_list']; ?>
        </div>
    </div>
</div>
