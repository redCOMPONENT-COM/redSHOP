<?php

/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
/** @scrutinizer ignore-deprecated */
JHtml::script('com_redshop/redshop.creditcard.min.js', false, true);
/** @scrutinizer ignore-deprecated */
JHtml::script('com_redshop/redshop.onestep.min.js', false, true);
/** @var RedshopModelCheckout $model */
$model = $this->getModel('checkout');


$oneStepTemplate = RedshopHelperTemplate::getTemplate("onestep_checkout");

if (count($oneStepTemplate) > 0 && $oneStepTemplate[0]->template_desc) {
    $oneStepTemplateHtml = $oneStepTemplate[0]->template_desc;
} else {
    $oneStepTemplateHtml = JText::_("COM_REDSHOP_TEMPLATE_NOT_EXISTS");
}

echo RedshopTagsReplacer::_(
    'onestepcheckout',
    $oneStepTemplateHtml,
    array(
        'usersInfoId'       => $this->users_info_id,
        'shippingAddresses' => $model->shippingaddresses(),
        'billingAddress'    => $model->billingaddresses()
    )
);

?>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("input[name='togglerchecker']").each(function (idx, el) {
                if ($(el).is(':checked')) {
                    getBillingTemplate($(el));
                }
            });

            if ($('#createaccount') && $('#createaccount').is(':checked')) {
                $('#onestep-createaccount-wrapper').css('display', 'block');
            }

            var settings = $('#adminForm').validate().settings;

            // Modify validation settings
            $.extend(true, settings, {
                rules: {
                    payment_method_id: {
                        required: function () {
                            if ($("#adminForm [name='payment_method_id']") && !$("#adminForm [name='payment_method_id']").is(':checked')) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                },
                messages: {
                    payment_method_id: "<?php echo JText::_('COM_REDSHOP_SELECT_PAYMENT_METHOD') ?>"
                },
                errorPlacement: function (error, element) {
                    if ((element.is(":radio") && element.attr('name') == "payment_method_id")) {
                        error.appendTo(element.parents('#paymentblock'));
                    } else if (element.is(":checkbox") && element.attr('name') == "termscondition") {
                        error.appendTo(element.closest('.checkbox'));
                    } else { // This is the default behavior
                        error.insertAfter(element);
                    }
                }
            });
        });
    })(jQuery);
</script>
