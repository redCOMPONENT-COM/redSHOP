window.onerror=function(){return true;}
var LMO = jQuery.noConflict();
jQuery(document).ready(function() {
    
    jQuery('#produkt_carousel').red_product({
        wrap: 'last',
		scroll: 1,
		auto: 6,
		animation: 'slow',
		easing: 'swing',
		itemLoadCallback: LMO
    });	
});
