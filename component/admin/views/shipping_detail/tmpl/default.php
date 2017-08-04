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
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;

        if (pressbutton == "shipping_rate") {
            form.view.value = "shipping_rate";
        }

        if (pressbutton == "cancel") {
            submitform(pressbutton);
            return;
        }

        if (form.name.value == "") {
            alert("<?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_MUST_HAVE_A_NAME', true); ?>");
        } else {
            submitform(pressbutton);
        }
    };
</script>
<form action="<?php echo JRoute::_(JUri::getInstance()->toString()) ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-primary"><?php echo JText::_('COM_REDSHOP_DETAILS') ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="name" class="col-md-4">
								<?php echo JText::_('COM_REDSHOP_SHIPPING_NAME'); ?>:
                            </label>
                            <div class="col-md-8">
                                <strong><?php echo JText::_($this->detail->name) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="name" class="col-md-4">
								<?php echo JText::_('COM_REDSHOP_SHIPPING_CLASS'); ?>:
                            </label>
                            <div class="col-md-8">
                                <strong><?php echo JText::_($this->detail->element) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="name" class="col-md-4">
								<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
                            </label>
                            <div class="col-md-8">
								<?php echo $this->lists['published'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-primary"><?php echo JText::_('COM_REDSHOP_CONFIG') ?></h3>
                </div>
                <div class="box-body">
					<?php
					JPluginHelper::importPlugin('redshop_shipping');
					$dispatcher = RedshopHelperUtility::getDispatcher();
					$payment    = $dispatcher->trigger('onShowConfig', array($this->detail));
					?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="element" value="<?php echo $this->detail->element; ?>"/>
    <input type="hidden" name="extension_id" value="<?php echo $this->detail->extension_id; ?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="view" value="shipping_detail"/>
    <input type="hidden" name="plugin" value="<?php echo $this->detail->folder; ?>"/>
</form>
