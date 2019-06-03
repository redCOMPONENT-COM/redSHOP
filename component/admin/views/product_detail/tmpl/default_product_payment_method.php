<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$paymentBlock = $this->detail->use_individual_payment_method == 1 ? 'block' : 'none';
?>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("input[type='radio'][name='use_individual_payment_method']").change(function (event) {
                $("#payment_method_wrapper").slideToggle();
            });
        });
    })(jQuery);
</script>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_TEMPLATE_PAYMENT_METHOD'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="col-md-3" for="use_individual_payment_method">
						<?php echo JText::_('COM_REDSHOP_USE_INDIVIDUAL_PAYMENT_METHOD'); ?>
                    </label>
                    <div class="col-md-9">
						<?php echo $this->lists['use_individual_payment_method']; ?>
                    </div>
                </div>
                <hr/>
                <div class="form-group" id="payment_method_wrapper" style="display: <?php echo $paymentBlock ?>;">
                    <label class="col-md-3" for="payment_method[]">
						<?php echo JText::_('COM_REDSHOP_SELECT_PAYMENT_METHOD'); ?>
                    </label>
                    <div class="col-md-9">
						<?php echo $this->lists['payment_methods']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
