<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$input = JFactory::getApplication()->input;
$priceMin = $input->get('texpricemin', null);
$priceMax = $input->get('texpricemax', null);
?>

<div id="tmpdiv" style="display:none"></div>
<script type="text/javascript">
	jQuery(function($) {
		$(document).ready(function(){
			var slider = $("#redcatslider");//Caching slider object
			var amount = $("#redcatamount");//Caching amount object
			var products = $('#redcatproducts');//Caching product object
			var ajaxMessage =  $('#ajaxcatMessage');//Caching ajaxMessage object
			var redcatpagination =  $('#redcatpagination');
			var oldredcatpagination =  $('#oldredcatpagination');
			var oldRedPageLimit = $('#oldRedPageLimit');

			//Resolve Mootools conflict
			$.ui.slider.prototype.widgetEventPrefix = 'slider';

			slider.slider({
				range: true, // necessary for creating a range slider
				min: <?php echo $minmax[0];?>, // minimum range of slider
				max: <?php echo $minmax[1];?>, //maximimum range of slider
				values: [<?php echo null !== $priceMin ? $priceMin : $minmax[0];?>, <?php echo null !== $priceMax ? $priceMax : $minmax[1];?>], //initial range of slider
				slide: function(event, ui) { // This event is triggered on every mouse move during slide.

					var startrange = number_format(ui.values[0],redSHOP.RSConfig._('PRICE_DECIMAL'),redSHOP.RSConfig._('PRICE_SEPERATOR'),redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
					var endrange = number_format(ui.values[1],redSHOP.RSConfig._('PRICE_DECIMAL'),redSHOP.RSConfig._('PRICE_SEPERATOR'),redSHOP.RSConfig._('THOUSAND_SEPERATOR'));

					amount.html(startrange + ' - ' + endrange);//set value of  amount span to current slider values
				},
				stop: function(event, ui){//This event is triggered when the user stops sliding.
					ajaxMessage.css({display:'block'});
					products.find('ul').css({opacity:.2});
					slider.css({overflow:'visible'});

					var url = redSHOP.RSConfig._('SITE_URL')+"index.php?option=com_redshop&view=category";
					url = url + "&cid=<?php echo $this->catid;?>&layout=detail&Itemid=<?php echo $this->itemid;?>";
					url = url + "&texpricemin=" + ui.values[0] + "&texpricemax=" + ui.values[1];
					url = url + "&category_template=<?php echo $categoryTemplateId;?>&manufacturer_id=<?php echo $manufacturerId;?>&order_by=<?php echo urlencode($orderBySelect);?>";

					$("#tmpdiv").load(url,{ajaxslide:1},function(){
						ajaxMessage.css({display:'none'});
						products.html($("#productlist").html());
						oldredcatpagination.html($("#redcatpagination").html());
						oldRedPageLimit.html($('#redPageLimit').html())
					});
					// Start Code for fixes IE9 issue
					$(this).parent("div").attr('style','');
					// End Code for fixes IE9 issue
				}
			});
			var startrange = number_format(slider.slider("values", 0),redSHOP.RSConfig._('PRICE_DECIMAL'),redSHOP.RSConfig._('PRICE_SEPERATOR'),redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
			var endrange = number_format(slider.slider("values", 1),redSHOP.RSConfig._('PRICE_DECIMAL'),redSHOP.RSConfig._('PRICE_SEPERATOR'),redSHOP.RSConfig._('THOUSAND_SEPERATOR'));

			amount.html(startrange + ' - ' + endrange);
		});
	});
</script>
<div id="tmpdiv" style="display:none"></div>
