<div id="tmpdiv" style="display:none"></div>
<script type="text/javascript">
	var redJ = jQuery.noConflict();
    redJ(function() {
        var slider = redJ("#redcatslider");//Caching slider object
        var amount = redJ("#redcatamount");//Caching amount object
        var products = redJ('#redcatproducts');//Caching product object
        var ajaxMessage =  redJ('#ajaxcatMessage');//Caching ajaxMessage object
        var redcatpagination =  redJ('#redcatpagination');
        var oldredcatpagination =  redJ('#oldredcatpagination');

        slider.slider({
            range: true, // necessary for creating a range slider
            min: <?php echo $minmax[0];?>, // minimum range of slider
            max: <?php echo $minmax[1];?>, //maximimum range of slider
            values: [<?php echo (isset($_REQUEST["texpricemin"]) ? $_REQUEST["texpricemin"] : $minmax[0]);?>, <?php echo (isset($_REQUEST["texpricemax"]) ? $_REQUEST["texpricemax"] : $minmax[1]);?>], //initial range of slider
            slide: function(event, ui) { // This event is triggered on every mouse move during slide.

            	startrange = number_format(ui.values[0],PRICE_DECIMAL,PRICE_SEPERATOR,THOUSAND_SEPERATOR);
                endrange = number_format(ui.values[1],PRICE_DECIMAL,PRICE_SEPERATOR,THOUSAND_SEPERATOR);

                amount.html(startrange + ' - ' + endrange);//set value of  amount span to current slider values
            },
            stop: function(event, ui){//This event is triggered when the user stops sliding.
                ajaxMessage.css({display:'block'});
                products.find('ul').css({opacity:.2});
                slider.css({overflow:'visible'});

                url = site_url+"index.php?option=com_redshop&view=category";
            	url = url + "&cid=<?php echo $catid;?>&layout=detail&Itemid=<?php echo $Itemid;?>";
            	url = url + "&texpricemin=" + ui.values[0] + "&texpricemax=" + ui.values[1];
            	url = url + "&category_template=<?php echo $category_template_id;?>&manufacturer_id=<?php echo $manufacturer_id;?>&order_by=<?php echo urlencode($order_by_select);?>";

            	redJ("#tmpdiv").load(url,{ajaxslide:1},function(){
                    ajaxMessage.css({display:'none'});
                    products.html(redJ("#productlist").html());
                    oldredcatpagination.html(redJ("#redcatpagination").html());
                });
            	// Start Code for fixes IE9 issue
        		redJ(this).parent("div").attr('style','');	
        		// End Code for fixes IE9 issue	
            }
        });
        startrange = number_format(slider.slider("values", 0),PRICE_DECIMAL,PRICE_SEPERATOR,THOUSAND_SEPERATOR);
        endrange = number_format(slider.slider("values", 1),PRICE_DECIMAL,PRICE_SEPERATOR,THOUSAND_SEPERATOR);

        amount.html(startrange + ' - ' + endrange);
	});


</script>
<div id="tmpdiv" style="display:none"></div>