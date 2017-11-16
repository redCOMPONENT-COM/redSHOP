<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_pricefilter
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$uri = JURI::getInstance();
$url = $uri->root();
$Itemid = JRequest::getInt('Itemid');
$document = JFactory::getDocument();

JHtml::_('redshopjquery.ui');
JHtml::script('com_redshop/attribute.js', false, true);
JHtml::stylesheet('com_redshop/priceslider.css', array(), true);
$numberFormatParams = '\'' . Redshop::getConfig()->get('PRICE_DECIMAL') . '\',\'' . Redshop::getConfig()->get('PRICE_SEPERATOR') . '\',\'' . Redshop::getConfig()->get('THOUSAND_SEPERATOR') . '\'';
?>
<script type="text/javascript">
	jQuery(function($) {
		$(document).ready(function(){
			var $slider = $("#redslider");//Caching slider object
			var $amount = $("#redamount");//Caching amount object
			var $products = $('#redproducts');//Caching product object
			var $ajaxMessage = $('#ajaxMessage');//Caching ajaxMessage object

			//Resolve Mootools conflict
			$.ui.slider.prototype.widgetEventPrefix = 'slider';

			$("div#redslider").slider({
				range: true, // necessary for creating a range slider
				min: <?php echo $texpricemin;?>, // minimum range of slider
				max: <?php echo $texpricemax;?>, //maximimum range of slider
				values: [<?php echo $texpricemin;?>, <?php echo $texpricemax;?>], //initial range of slider
				slide: function (event, ui) { // This event is triggered on every mouse move during slide.

					var startrange = number_format(ui.values[0], <?php echo $numberFormatParams; ?>);
					var endrange = number_format(ui.values[1], <?php echo $numberFormatParams; ?>);

					$amount.html(startrange + ' - ' + endrange);//set value of  amount span to current slider values
				},
				stop: function (event, ui) {//This event is triggered when the user stops sliding.
					$ajaxMessage.css({display: 'block'});
					$products.css({opacity: .2});

					var url = "<?php echo $url;?>index.php?format=raw&option=com_redshop&view=price_filter";
					url = url + "&category=<?php echo $category;?>&count=<?php echo $count;?>&image=<?php echo $image;?>";
					url = url + "&thumbwidth=<?php echo $thumbwidth;?>&thumbheight=<?php echo $thumbheight;?>";
					url = url + "&show_price=<?php echo $show_price;?>&show_readmore=<?php echo $show_readmore;?>";
					url = url + "&show_addtocart=<?php echo $show_addtocart;?>&show_desc=<?php echo $show_desc;?>";
					url = url + "&show_discountpricelayout=<?php echo $show_discountpricelayout;?>&Itemid=<?php echo $Itemid;?>";
					url = url + "&texpricemin=" + ui.values[0] + "&texpricemax=" + ui.values[1];

					$products.load(url, '', function () {
						$ajaxMessage.css({display: 'none'});
						$(this).css({opacity: 1});
					});
				}
			});

			var startrange = number_format($slider.slider("values", 0), <?php echo $numberFormatParams; ?>);
			var endrange = number_format($slider.slider("values", 1), <?php echo $numberFormatParams; ?>);

			$amount.html(startrange + ' - ' + endrange);

			$ajaxMessage.css({display: 'block'});
			$products.find('ul').css({opacity:.2});

			var url = "<?php echo $url;?>index.php?format=raw&option=com_redshop&view=price_filter";
			url = url + "&category=<?php echo $category;?>&count=<?php echo $count;?>&image=<?php echo $image;?>";
			url = url + "&thumbwidth=<?php echo $thumbwidth;?>&thumbheight=<?php echo $thumbheight;?>";
			url = url + "&show_price=<?php echo $show_price;?>&show_readmore=<?php echo $show_readmore;?>";
			url = url + "&show_addtocart=<?php echo $show_addtocart;?>&show_desc=<?php echo $show_desc;?>";
			url = url + "&show_discountpricelayout=<?php echo $show_discountpricelayout;?>&Itemid=<?php echo $Itemid;?>";
			url = url + "&texpricemin=<?php echo $texpricemin;?>&texpricemax=<?php echo $texpricemax;?>";

			$products.load(url, '', function () {
				$ajaxMessage.css({display: 'none'});
			});
		});
	});
</script>
<div id="pricefilter">
	<div class="left" id="leftSlider">
		<div id="range"><?php echo JText::_('COM_REDSHOP_PRICE') . ": ";?><span id="redamount"></span></div>
		<div id="redslider"></div>
	</div>
	<div class="clr"></div>
	<div class="left" id="productsWrap">
		<div id="ajaxMessage"><?php echo JText::_('COM_REDSHOP_LOADING');?></div>
		<div id="redproducts"><?php echo JText::_('COM_REDSHOP_NO_PRODUCT_FOUND');?></div>
	</div>
	<div class="clr"></div>
</div>
