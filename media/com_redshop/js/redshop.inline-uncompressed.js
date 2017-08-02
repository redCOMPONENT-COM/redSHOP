(function ($) {
    $(document).ready(function () {
        $("#redSHOPAdminContainer .label-edit-inline").each(function (index, item) {
            var $label = $(item);
            var $input = $("#" + $(this).data("target"));
            var $id = $(this).data("id");
            var $value = $(this).data("original-value");

            $(item).click(function (e) {
                if (e.target.nodeName == "A") {
                    return true;
                }

                e.preventDefault();

                $(this).hide('fast', function () {
                    $input.show('fast').trigger("show");
                });
            });

            $input.on("show", function (event) {
                $(this).prop("disabled", false).removeClass("disabled").focus().select();
            })
                .on("blur", function (event) {
                    $(this).hide("fast", function () {
                        $label.show("fast");
                    });
                })
                .on("keypress", function (event) {
                    var keyCode = event.keyCode || event.which;

                    if (keyCode == 13) {
                        event.preventDefault();
                        // Enter key
                        document.adminForm.task.value = "ajaxInlineEdit";
                        formData = $("#adminForm").serialize();
                        formData += "&id=" + $id;

                        $.ajax({
                            url: document.adminForm.action,
                            type: "POST",
                            data: formData,
                            dataType: "JSON",
                            complete: function () {
                                $input.prop("disabled", true).addClass("disabled");
                            }
                        })
                            .done(function (response) {
                                if (response == 1) {
                                    if ($label.find("a").length) {
                                        $label.find("a").text($input.val());
                                    } else {
                                        $label.text($input.val());
                                    }
                                    $.redshopAlert(
                                        Joomla.JText._('COM_REDSHOP_SUCCESS'),
                                        Joomla.JText._('COM_REDSHOP_DATA_UPDATE_SUCCESS')
                                    );
                                } else {
                                    $.redshopAlert(
                                        Joomla.JText._('COM_REDSHOP_FAIL'),
                                        Joomla.JText._('COM_REDSHOP_DATA_UPDATE_FAIL'),
                                        "danger"
                                    );
                                }

                                $input.hide("fast", function () {
                                    $label.show("fast");
                                });

                                document.adminForm.task.value = "";
                            });
                    } else if (keyCode == 27) {
                        // Escape key
                        $input.val($value).hide("fast", function () {
                            $label.show("fast");
                        });

                        document.adminForm.task.value = "";
                    }
                });
        });
    });
})(jQuery);