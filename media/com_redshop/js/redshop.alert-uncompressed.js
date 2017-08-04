/**
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

(function ($) {
    $.redshopAlert = function (title, message, type) {
        var $alert = null;
        var msgTitle = title;
        var msgBody = message;
        var msgType = "success";

        if (typeof type != "undefined") {
            msgType = type;
        }

        /**
         * Init function
         */
        this.init = function () {
            if ($alert == null) {
                if ($("#redshop-alert-wrapper").length <= 0) {
                    $alert = $("<div>")
                        .attr("id", "redshop-alert-wrapper")
                        .css({
                            "display": "none",
                            "position": "fixed",
                            "top": "70px",
                            "right": "2%"
                        });
                    $("<div>").append($("<h4>")).append($("<p>")).appendTo($alert)

                    $alert.appendTo($("body"));
                } else {
                    $alert = $("#redshop-alert-wrapper");
                }
            }
        };

        /**
         * Display alert function
         */
        this.display = function () {
            $alert.fadeIn('slow', function () {
                window.setTimeout(function () {
                    $alert.fadeOut('slow');
                }, 5000);
            });
        };

        /**
         * Prepare alert HTML code
         */
        this.prepare = function () {
            var $div = $($alert.children("div")[0]);
            $div.attr("class", "").addClass("callout callout-" + msgType);
            $div.find("h4").html(msgTitle);
            $div.find("p").html(msgBody);
        };

        this.init();
        this.prepare();
        this.display();
    }
})(jQuery);
