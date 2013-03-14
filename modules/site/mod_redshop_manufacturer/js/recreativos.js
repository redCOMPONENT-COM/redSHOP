var HIJ = jQuery.noConflict();

jQuery(document).ready(function () {

    jQuery('#brand_carousel').jcarousel({
        wrap: 'last',
        scroll: 6,
        auto: 6,
        animation: 'slow',
        easing: 'swing',
        itemLoadCallback: HIJ
    });


});
