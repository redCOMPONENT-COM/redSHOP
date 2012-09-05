window.onerror=function(){return true;}
var LMO = jQuery.noConflict();
var dom = {};
dom.query = jQuery.noConflict(true);
dom.query(document).ready(function() {
    
	dom.query('#produkt_carousel').red_product({
        wrap: 'last',
		scroll: 1,
		auto: 6,
		animation: 'slow',
		easing: 'swing',
		itemLoadCallback: LMO
    });	
});
