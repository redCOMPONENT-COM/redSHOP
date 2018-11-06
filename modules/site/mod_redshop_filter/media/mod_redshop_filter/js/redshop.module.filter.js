(function ($) {
    var redSHOP = window.redSHOP || {};
    redSHOP.Module = redSHOP.Module || {};
    redSHOP.Module.Filter = redSHOP.Module.Filter || {};
    redSHOP.Module.Filter.form = null;
    redSHOP.Module.Filter.priceSlider = null;
    redSHOP.Module.Filter.options = {};
    redSHOP.Module.Filter.resetMode = false;
    redSHOP.Module.Filter.setup = function (options) {
        redSHOP.Module.Filter.options = options;
        redSHOP.Module.Filter.form = $("#redproductfinder-form-" + redSHOP.Module.Filter.options.domId);
        redSHOP.Module.Filter.checkList();

        // Setup for Manufacturer filter
        if (options.moduleParams.manufacturer === "1") {
            redSHOP.Module.Filter.form.find('input[name="keyword-manufacturer"]').on('keyup', function (event) {
                if (event.keyCode === 13) {
                    return false;
                }

                redSHOP.Module.Filter.populateManufacturerOptions();

                return true;
            });
        }

        redSHOP.Module.Filter.form.find('[type="checkbox"]').each(function () {
            redSHOP.Module.Filter.checkClick($(this));
        });

        redSHOP.Module.Filter.form.html(function () {
            $('span.label_alias').click(function () {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active').next('ul.collapse').removeClass('in');
                } else {
                    var ultab = redSHOP.Module.Filter.form.find('ul.collapse.in');
                    ultab.removeClass('in').prev('span').removeClass('active');

                    $(this).addClass('active').next('ul.collapse').addClass('in');
                }
            });

            if (options.moduleParams.price === "1") {
                redSHOP.Module.Filter.rangeSlide(options.rangeMin, options.rangeMax, options.currentMin, options.currentMax, redSHOP.Module.Filter.submitFormAjax);
                redSHOP.Module.Filter.form.find("input[name='redform[filterprice][min]']").on("keypress", function(event){
                    if (event.keyCode === 13) {
                        var val = [$(this).val(), redSHOP.Module.Filter.form.find("input[name='redform[filterprice][max]']").val()];
                        redSHOP.Module.Filter.form.find("#slider-range").slider("values", val);

                        return false;
                    }

                    return true;
                });
                redSHOP.Module.Filter.form.find("input[name='redform[filterprice][max]']").on("keypress", function(event){
                    if (event.keyCode === 13) {
                        var val = [redSHOP.Module.Filter.form.find("input[name='redform[filterprice][min]']").val(), $(this).val()];
                        redSHOP.Module.Filter.form.find("#slider-range").slider("values", val);

                        return false;
                    }

                    return true;
                });
            }
        });

        // Setup for keyword field
        if (options.moduleParams.keyword === '1') {
            $("#" + options.domId + "-keyword").on("keypress", function (event) {
                if (event.keyCode === 13) {
                    redSHOP.Module.Filter.submitFormAjax();

                    return false;
                }

                return true;
            });
        }

        // Setup for "Clear" button
        if (options.moduleParams.show_clear === "1") {
            redSHOP.Module.Filter.form.find("#clear-btn").on("click", function (event) {
                event.preventDefault();
                redSHOP.Module.Filter.resetMode = true;
                redSHOP.Module.Filter.form.find('input[type="checkbox"]').prop('checked', false);
                redSHOP.Module.Filter.form.find('input[type="checkbox"]').each(function () {
                    redSHOP.Module.Filter.checkClick($(this));
                });

                // Reset keyword field
                if (options.moduleParams.keyword === "1") {
                    redSHOP.Module.Filter.form.find("#" + options.domId + "-keyword").val("");
                }

                // Reset manufacturer options
                if (options.moduleParams.manufacturer === "1") {
                    redSHOP.Module.Filter.form.find('input[name="keyword-manufacturer"]').val("");
                    redSHOP.Module.Filter.populateManufacturerOptions();
                }

                // Reset filter price
                if (options.moduleParams.price === "1") {
                    redSHOP.Module.Filter.form.find('input[name="redform[filterprice][min]"]').val(options.rangeMin);
                    redSHOP.Module.Filter.form.find('input[name="redform[filterprice][max]"]').val(options.rangeMax);
                    redSHOP.Module.Filter.rangeSlide(options.rangeMin, options.rangeMax, options.currentMin, options.currentMax, redSHOP.Module.Filter.submitFormAjax);
                }

                // Submit form
                redSHOP.Module.Filter.resetMode = false;
                redSHOP.Module.Filter.submitFormAjax(null);
            });
        }
    };
    redSHOP.Module.Filter.checkList = function () {
        var check = [];

        redSHOP.Module.Filter.form.find('#manu #manufacture-list input').on('change', function () {
            $(this).on("keypress", function (event) {
                if (event.keyCode === 13) {
                    return false;
                }
            });

            check.push($(this).val());
            redSHOP.Module.Filter.submitForm(this);
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
        if (redSHOP.Module.Filter.priceSlider === null) {
            $.ui.slider.prototype.widgetEventPrefix = 'slider';
            redSHOP.Module.Filter.priceSlider = redSHOP.Module.Filter.form.find("#slider-range").slider({
                range: true,
                min: rangeMin,
                max: rangeMax,
                step: 1,
                values: [currentMin, currentMax],
                slide: function (event, ui) {
                    redSHOP.Module.Filter.form.find('[name="redform[filterprice][min]"]').attr('value', ui.values[0]);
                    redSHOP.Module.Filter.form.find('[name="redform[filterprice][max]"]').attr('value', ui.values[1]);
                }, change: function () {
                    if (callback && typeof(callback) === "function") {
                        $('input[name="limitstart"]').val(0);
                        callback();
                    }
                }
            });
        } else {
            redSHOP.Module.Filter.priceSlider.slider("option", "min", rangeMin);
            redSHOP.Module.Filter.priceSlider.slider("option", "max", rangeMax);
            redSHOP.Module.Filter.priceSlider.slider("values", [currentMin, currentMax]);
        }
    };
    redSHOP.Module.Filter.submitFormAjax = function () {
        if (redSHOP.Module.Filter.resetMode === false) {
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
                    window.history.pushState("", "", $($.parseHTML(data)).find("#new-url").text());
                },
                complete: function () {
                    $('#wait').css('display', 'none');

                    if (redSHOP.Module.Filter.options.moduleParams.restricted === "1") {
                        restricted(
                            redSHOP.Module.Filter.form.serialize(),
                            $('input[name="pids"]').val(),
                            redSHOP.Module.Filter.options.moduleParams
                        );
                    }
                }
            });
        }

        return false;
    };
    redSHOP.Module.Filter.submitForm = function () {
        redSHOP.Module.Filter.form.find('input[name="limitstart"]').val(0);
        redSHOP.Module.Filter.submitFormAjax();
    };
    redSHOP.Module.Filter.populateManufacturerOptions = function(){
        var arr = redSHOP.Module.Filter.options.manufacturers;
        var keyword = redSHOP.Module.Filter.form.find('input[name="keyword-manufacturer"]').val();
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
                var is_check = '';

                if ($.inArray(data.manufacturer_id, check_list) != -1) {
                    is_check = 'checked="checked"';
                }

                html += '<li style="list-style: none"><label>';
                html += '<span class="taginput" data-aliases="' + data.id + '">';
                html += '<input type="checkbox" ' + is_check + ' value="' + data.id + '" name="redform[manufacturer][]" />';
                html += '</span>';
                html += '<span class="tagname">' + data.name + '</span>';
                html += '</label></li>';
            }
        });

        redSHOP.Module.Filter.form.find('#manu #manufacture-list').html('');
        redSHOP.Module.Filter.form.find('#manu #manufacture-list').append(html);
        redSHOP.Module.Filter.checkList();
    };
})(jQuery);