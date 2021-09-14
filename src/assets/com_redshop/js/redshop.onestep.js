(function($){
    $(document).ready(function(){
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
                payment_method_id: "<?php echo JText::sprintf('COM_REDSHOP_SELECT_PAYMENT_METHOD') ?>"
            },
            errorPlacement: function (error, element) {
                if ((element.is(":radio") && element.attr('name') == "payment_method_id")) {
                    error.appendTo(element.parents('#divPaymentMethod'));
                } else if (element.is(":checkbox") && element.attr('name') == "termscondition") {
                    error.appendTo(element.closest('.checkbox'));
                } else { // This is the default behavior
                    error.insertAfter(element);
                }
            }
        });

        $(".onestep-createaccount-toggle").change(function(evt){
            evt.preventDefault();
            $("#onestep-createaccount-wrapper").slideToggle('medium');
        });
    });
})(jQuery);