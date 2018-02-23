/**
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

(function ($) {
    $.redshopAlert = function (title, message, type) {
        var msgTitle = title;
        var msgBody = message;
        var msgType = "success";
        var $wrapper = null;

        if (typeof type !== "undefined") {
            msgType = type;
        }

        /**
         * Init function
         */
        this.init = function () {
            if ($wrapper === null) {
                if ($("#redshop-alert-wrapper").length <= 0) {
                    $wrapper = $("<div>")
                        .attr("id", "redshop-alert-wrapper")
                        .css({
                            "display": "none",
                            "position": "fixed",
                            "top": "60px",
                            "right": "10px",
                            "z-index": "9999"
                        });
                    $wrapper.appendTo($("body"));
                } else {
                    $wrapper = $("#redshop-alert-wrapper");
                }
            }
        };

        /**
         * Display alert function
         */
        this.display = function () {
            $wrapper.fadeIn('slow');
        };

        /**
         * Prepare alert HTML code
         */
        this.prepare = function () {
            var $div = $("<div>")
                .attr("class", "").addClass("callout callout-" + msgType)
                .append(
                    $("<h4>")
                        .html(msgTitle)
                        .append(
                            $("<i>").addClass("fa fa-close pull-right")
                                .css({"cursor" : "pointer"})
                                .click(function(evt){
                                    $(this).parent().parent().remove();
                                })
                        )
                )
                .append($("<p>").html(msgBody));

            $div.appendTo($wrapper);
        };

        this.init();
        this.prepare();
        this.display();
    }
})(jQuery);
