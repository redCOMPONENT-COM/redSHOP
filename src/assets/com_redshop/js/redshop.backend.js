(function($) {
    $(document).ready(function() {
        if ($('.message-sys').length) {
            var messageContainer = $('#system-message-container:not(.not-system)');

            // No messages found. Create an empty div
            if (!messageContainer.length) {
                messageContainer = $("<div>", {
                    id: "system-message-container"
                });
            }

            $('.message-sys').append(messageContainer);
        }

        // We cannot access the body tag so add admin-lte styling classes dynamically
        $('body').addClass('skin-red-light sidebar-mini');

        // Tooltips
        $('[data-toggle="tooltip"]').tooltip();

        $('img[src*="system/images/tooltip.png"]').each(function() {
            var s = $(this).attr('src');
            s = s.replace('system', 'com_redshop');
            $(this).attr('src', s);
        });
    });
})(jQuery);
