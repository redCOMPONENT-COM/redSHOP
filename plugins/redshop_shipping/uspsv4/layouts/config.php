<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>
<div class="form form-horizontal">
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_USERNAME_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_USERNAME') ?>
        </label>
        <div class="col-md-9">
            <input type="text" name="USPS_USERNAME" class="form-control" value="<?php echo USPS_USERNAME ?>"/>
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PASSWORD_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PASSWORD') ?>
        </label>
        <div class="col-md-9">
            <input type="text" name="USPS_PASSWORD" class="form-control" value="<?php echo USPS_PASSWORD ?>"/>
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_SERVER_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_SERVER') ?>
        </label>
        <div class="col-md-9">
            <input
                    type="text"
                    name="USPS_SERVER"
                    class="form-control"
                    value="<?php echo USPS_SERVER ?>"
            />
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PATH_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PATH') ?>
        </label>
        <div class="col-md-9">
            <input
                    type="text"
                    name="USPS_PATH"
                    class="form-control"
                    value="<?php echo USPS_PATH ?>"
            />
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE_TOOLTIP') ?>">
			<?php echo JText::_('COM_REDSHOP_SHIP_FROM_ZIPCODE') ?>
        </label>
        <div class="col-md-9">
            <input
                    class="form-control"
                    type="text"
                    name="OVERRIDE_SOURCE_ZIP"
                    value="<?php echo OVERRIDE_SOURCE_ZIP ?>"
            />
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PROXYSERVER_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PROXYSERVER') ?>
        </label>
        <div class="col-md-9">
            <input
                    type="text"
                    name="USPS_PROXYSERVER"
                    class="form-control"
                    value="<?php echo USPS_PROXYSERVER ?>"
            />
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PADDING_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PADDING') ?>
        </label>
        <div class="col-md-9">
            <input
                    class="form-control"
                    type="text"
                    name="USPS_PADDING"
                    value="<?php echo USPS_PADDING ?>"
            />
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_HANDLING_FEE_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_HANDLING_FEE') ?>
        </label>
        <div class="col-md-9">
            <input
                    class="form-control"
                    type="text"
                    name="USPS_HANDLINGFEE"
                    value="<?php echo USPS_HANDLINGFEE ?>"
            />
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_INTLHANDLINGFEE_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_INTLHANDLINGFEE') ?>
        </label>
        <div class="col-md-9">
            <input
                    type="text"
                    name="USPS_INTLHANDLINGFEE"
                    class="form-control"
                    value="<?php echo USPS_INTLHANDLINGFEE ?>"
            />
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_MACHINABLE_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_MACHINABLE') ?>
        </label>
        <div class="col-md-9">
			<?php echo JHTML::_('redshopselect.booleanlist', 'USPS_MACHINABLE', array(), USPS_MACHINABLE) ?>
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_QUOTE_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_QUOTE') ?>
        </label>
        <div class="col-md-9">
			<?php echo JHTML::_('redshopselect.booleanlist', 'USPS_SHOW_DELIVERY_QUOTE', array(), USPS_SHOW_DELIVERY_QUOTE) ?>
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_REPORTERRORS_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_REPORTERRORS') ?>
        </label>
        <div class="col-md-9">
			<?php echo JHTML::_('redshopselect.booleanlist', 'USPS_REPORTERRORS', array(), USPS_REPORTERRORS) ?>
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_STANDARDSHIPPING_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_STANDARDSHIPPING') ?>
        </label>
        <div class="col-md-9">
			<?php echo JHTML::_('redshopselect.booleanlist', 'USPS_STANDARDSHIPPING', array(), USPS_STANDARDSHIPPING) ?>
        </div>
    </div>
    <div class="form-group row-fluid">
        <label class="col-md-3 control-label hasPopover"
               data-content="<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PREFIX_TOOLTIP') ?>">
			<?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_PREFIX') ?>
        </label>
        <div class="col-md-9">
            <input type="text" name="USPS_PREFIX" class="form-control" value="<?php echo USPS_PREFIX ?>"/>
        </div>
    </div>
</div>
<hr/>
<div class="row-fluid">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_SHIP'); ?></h3>
            </div>
            <div class="panel-body">
				<?php $i = 0; ?>
				<?php while (defined("USPS_SHIP" . $i)) : ?>
					<?php $shipName = 'USPS_SHIP' . $i; ?>
                    <div class="form-group row-fluid">
                        <label class="col-md-6 control-label">
							<?php echo constant($shipName . '_TEXT'); ?>
                        </label>
                        <div class="col-md-6">
							<?php echo JHtml::_('redshopselect.booleanlist', $shipName, array(), constant($shipName)) ?>
                        </div>
                    </div>
					<?php $i++; ?>
				<?php endwhile; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3><?php echo JText::_('PLG_REDSHOP_SHIPPING_USPSV4_USPS_INTL'); ?></h3>
            </div>
            <div class="panel-body">
				<?php $i = 0; ?>
				<?php while (defined("USPS_INTL" . $i)) : ?>
					<?php $shipName = 'USPS_INTL' . $i; ?>
                    <div class="form-group row-fluid">
                        <label class="col-md-7 control-label">
							<?php echo constant($shipName . '_TEXT'); ?>
                        </label>
                        <div class="col-md-5">
							<?php echo JHtml::_('redshopselect.booleanlist', $shipName, array(), constant($shipName)) ?>
                        </div>
                    </div>
					<?php $i++; ?>
				<?php endwhile; ?>
            </div>
        </div>
    </div>
</div>
