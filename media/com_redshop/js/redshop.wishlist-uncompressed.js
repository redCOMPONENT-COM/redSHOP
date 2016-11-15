(function($){
    $(document).ready(function(){
       $('.redshop-wishlist-button, .redshop-wishlist-link').click(function(event){
            event.preventDefault();

            var productId = $(this).attr('data-productid');
            var link      = $(this).attr('data-href');

            if (link == '' || typeof link == 'undefined') {
                link = $(this).attr('href');
            }

            if (productId == '' || isNaN(productId)) {
                return false;
            }

            link += '&product_id=' + productId;

            var $form = $('form#addtocart_prd_' + productId);

            if (!$form.length) {
                SqueezeBox.open(link, {handler: 'iframe'});

                return true;
            }

            $form = $($form[0]);

            var attribute = $form.children('input#attribute_data');
            var property = $form.children('input#property_data');
            var subAttribute = $form.children('input#subproperty_data');

            if (attribute.length) {
                link += '&attribute_id=' + $(attribute[0]).val();
            }

           if (property.length) {
               link += '&property_id=' + $(property[0]).val();
           }

           if (subAttribute.length) {
               link += '&subattribute_id=' + $(subAttribute[0]).val();
           }

           SqueezeBox.open(link, {handler: 'iframe'});

           return true;
       });
    });
})(jQuery);