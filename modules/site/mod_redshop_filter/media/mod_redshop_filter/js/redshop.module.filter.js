(function ($) {
    var redSHOP = window.redSHOP || {};
    redSHOP.Module = redSHOP.Module || {};
    redSHOP.Module.Filter = redSHOP.Module.Filter || {};
    redSHOP.Module.Filter.form = null;
    redSHOP.Module.Filter.options = {};
    redSHOP.Module.Filter.setup = function (options) {
        redSHOP.Module.Filter.options = options;
        redSHOP.Module.Filter.form = $("#redproductfinder-form-" + redSHOP.Module.Filter.options.domId);
        redSHOP.Module.Filter.checkList();

        redSHOP.Module.Filter.form.find('input[name="keyword-manufacturer"]').on('keyup', function () {
            var json = redSHOP.Module.Filter.options.manufacturers;
            var arr = $.parseJSON(json);
            var keyword = $(this).val();
            var new_arr = [];
            var check = $('input[name="check_list"]').val();
            var check_list = $.parseJSON(check);
            $.each(arr, function (i, value) {
                if (value.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1) {
                    new_arr.push(value);
                }
            });

            var html = '';

            $.each(new_arr, function (key, data) {
                var check = Object.keys(data).length;
                if (check > 0) {
                    if ($.inArray(data.manufacturer_id, check_list) != -1) {
                        var is_check = 'checked="checked"';
                    } else {
                        var is_check = '';
                    }
                    html += '<li style="list-style: none"><label>';
                    html += '<span class="taginput" data-aliases="' + data.id + '">';
                    html += '<input type="checkbox" ' + is_check + ' value="' + data.id + '" name="redform[manufacturer][]" />';
                    html += '</span>'
                    html += '<span class="tagname">' + data.name + '</span>';
                    html += '</label></li>';
                }
            });

            redSHOP.Module.Filter.form.find('#manu #manufacture-list').html('');
            redSHOP.Module.Filter.form.find('#manu #manufacture-list').append(html);
            redSHOP.Module.Filter.checkList();
        });

        redSHOP.Module.Filter.form.find('[type="checkbox"]').each(function () {
            redSHOP.Module.Filter.checkClick($(this));
        });

        redSHOP.Module.Filter.form.html(function () {
            $('span.label_alias').click(function (event) {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active').next('ul.collapse').removeClass('in');
                } else {
                    var ultab = redSHOP.Module.Filter.form.find('ul.collapse.in');
                    ultab.removeClass('in').prev('span').removeClass('active');

                    $(this).addClass('active').next('ul.collapse').addClass('in');
                }
            });
            redSHOP.Module.Filter.rangeSlide(options.rangeMin, options.rangeMax, options.currentMin, options.currentMax, redSHOP.Module.Filter.submitFormAjax);
        });

        // Setup for keyword field
        if (options.showKeyword) {
            $("#" + options.domId + "-keyword").on("keypress", function (event) {
                if (event.keyCode === 13) {
                    redSHOP.Module.Filter.submitFormAjax();

                    return false;
                }

                return true;
            });
        }

        // Setup for keyword field
        if (options.showClearBtn) {
            redSHOP.Module.Filter.form.find("#clear-btn").on("click", function (event) {
                event.preventDefault();
                redSHOP.Module.Filter.form.find('input[type="checkbox"]').prop('checked', false);
                redSHOP.Module.Filter.form.find('input[type="checkbox"]').each(function () {
                    redSHOP.Module.Filter.checkClick($(this));
                });
                redSHOP.Module.Filter.form.find('input[name="redform[filterprice][min]"]').val(options.rangeMin);
                redSHOP.Module.Filter.form.find('input[name="redform[filterprice][max]"]').val(options.rangeMax);
                $("#" + options.domId + "-keyword").val("");
                redSHOP.Module.Filter.rangeSlide(options.rangeMin, options.rangeMax, options.currentMin, options.currentMax);
                redSHOP.Module.Filter.submitFormAjax(null);
            });
        }
    };
    redSHOP.Module.Filter.checkList = function () {
        var check = [];

        redSHOP.Module.Filter.form.find('#manu #manufacture-list input').on('change', function () {
            check.push($(this).val());
        });

        $('input[name="check_list"]').val(JSON.stringify(check));
    };
    redSHOP.Module.Filter.checkClick = function (ele) {
        if ($(ele).prop("checked") === true)
            $(ele).prev('.icon').addClass('active');
        else
            $(ele).prev('.icon').removeClass('active');
    };
    redSHOP.Module.Filter.rangeSlide = function (rangeMin, rangeMax, currentMin, currentMax, callback) {
        $.ui.slider.prototype.widgetEventPrefix = 'slider';
        redSHOP.Module.Filter.form.find("#slider-range").slider({
            range: true,
            min: rangeMin,
            max: rangeMax,
            step: 1,
            values: [currentMin, currentMax],
            slide: function (event, ui) {
                redSHOP.Module.Filter.form.find('[name="redform[filterprice][min]"]').attr('value', ui.values[0]);
                redSHOP.Module.Filter.form.find('[name="redform[filterprice][max]"]').attr('value', ui.values[1]);
            }, change: function (event, ui) {
                if (callback && typeof(callback) === "function") {
                    $('input[name="limitstart"]').val(0);
                    callback();
                }
            }
        });
    };
    redSHOP.Module.Filter.submitFormAjax = function () {
        $.ajax({
            type: "POST",
            url: redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&task=search.findProducts",
            data: redSHOP.Module.Filter.form.serialize(),
            beforeSend: function () {
                $('#wait').css('display', 'block');
            },
            success: function (data) {
                var $mainContent = $("#main #redshopcomponent");

                if (!$mainContent.length) {
                    $mainContent = jQuery("#redshopcomponent");
                }

                $mainContent.html(data);
                $('select').select2();

                url = $($.parseHTML(data)).find("#new-url").text();
                window.history.pushState("", "", url);
            },
            complete: function () {
                $('#wait').css('display', 'none');

                if (redSHOP.Module.Filter.options.isRestricted) {
                    var pids = jQuery('input[name="pids"]').val();
                    restricted(redSHOP.Module.Filter.form.serialize(), pids, redSHOP.Module.Filter.options.moduleParams);
                }
            }
        });
    };
    redSHOP.Module.Filter.submitForm = function () {
        redSHOP.Module.Filter.form.find('input[name="limitstart"]').val(0);
        redSHOP.Module.Filter.submitFormAjax();
    };
})(jQuery);