window.onerror = function () {
    return true;
}
var rscs = jQuery.noConflict();
var dom = {};
dom.query = jQuery.noConflict(true);
dom.query(document).ready(function () {

    dom.query('#rs_category_scroller').red_product({
        wrap: 'last',
        scroll: 1,
        auto: 6,
        animation: 'slow',
        easing: 'swing',
        itemLoadCallback: rscs
    });
});