<?php
defined ('_JEXEC') or die ('restricted access');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');
$uri =& JURI::getInstance();
$url= $uri->root();
$Itemid = JRequest::getVar('Itemid');
$option = JRequest::getVar('option');
// get product helper


$document = JFactory::getDocument();
//$document->addstylesheet('components/com_redshop/assets/css/redshop_pricefilter.css');

// 	include redshop js file.
require_once(JPATH_SITE. DS .'components'.DS.'com_redshop'.DS.'helpers'.DS.'redshop.js.php');

JHTML::Script('attribute.js', 'components/com_redshop/assets/js/',false);
JHTML::Script('jquery-1.js', 'components/com_redshop/assets/js/',false);
JHTML::Script('jquery-ui-1.js', 'components/com_redshop/assets/js/',false);
JHTML::Stylesheet('priceslider.css', 'components/com_redshop/assets/css/');
JHTML::Stylesheet('jquery-ui-1.css', 'components/com_redshop/assets/css/');
?>
<script type="text/javascript">
var dom = {};
dom.query = jQuery.noConflict(true);
dom.query(function() {
        $slider = dom.query("#redslider");//Caching slider object
        $amount = dom.query("#redamount");//Caching amount object
        $products = dom.query('#redproducts');//Caching product object
        $ajaxMessage =  dom.query('#ajaxMessage');//Caching ajaxMessage object

        $slider.slider({
            range: true, // necessary for creating a range slider
            min: <?php echo $texpricemin;?>, // minimum range of slider
            max: <?php echo $texpricemax;?>, //maximimum range of slider
            values: [<?php echo $texpricemin;?>, <?php echo $texpricemax;?>], //initial range of slider
            slide: function(event, ui) { // This event is triggered on every mouse move during slide.

            	startrange = number_format(ui.values[0],PRICE_DECIMAL,PRICE_SEPERATOR,THOUSAND_SEPERATOR);
                endrange = number_format(ui.values[1],PRICE_DECIMAL,PRICE_SEPERATOR,THOUSAND_SEPERATOR);

                $amount.html(startrange + ' - ' + endrange);//set value of  amount span to current slider values
            },
            stop: function(event, ui){//This event is triggered when the user stops sliding.
                $ajaxMessage.css({display:'block'});
                $products.find('ul').css({opacity:.2});

            	url = "<?php echo $url;?>index.php?tmpl=component&option=com_redshop&view=price_filter";
            	url = url + "&category=<?php echo $category;?>&count=<?php echo $count;?>&image=<?php echo $image;?>";
            	url = url + "&thumbwidth=<?php echo $thumbwidth;?>&thumbheight=<?php echo $thumbheight;?>";
            	url = url + "&show_price=<?php echo $show_price;?>&show_readmore=<?php echo $show_readmore;?>";
            	url = url + "&show_addtocart=<?php echo $show_addtocart;?>&show_desc=<?php echo $show_desc;?>";
            	url = url + "&show_discountpricelayout=<?php echo $show_discountpricelayout;?>&Itemid=<?php echo $Itemid;?>";
            	url = url + "&texpricemin=" + ui.values[0] + "&texpricemax=" + ui.values[1];

            	$products.load(url,'',function(){
                    $ajaxMessage.css({display:'none'});
                });
            }
        });

        startrange = number_format($slider.slider("values", 0),PRICE_DECIMAL,PRICE_SEPERATOR,THOUSAND_SEPERATOR);
        endrange = number_format($slider.slider("values", 1),PRICE_DECIMAL,PRICE_SEPERATOR,THOUSAND_SEPERATOR);

        $amount.html(startrange + ' - ' + endrange);
    });

    window.onload = function(){
    	$ajaxMessage.css({display:'block'});
       // $products.find('ul').css({opacity:.2});

    	url = "<?php echo $url;?>index.php?tmpl=component&option=com_redshop&view=price_filter";
    	url = url + "&category=<?php echo $category;?>&count=<?php echo $count;?>&image=<?php echo $image;?>";
    	url = url + "&thumbwidth=<?php echo $thumbwidth;?>&thumbheight=<?php echo $thumbheight;?>";
    	url = url + "&show_price=<?php echo $show_price;?>&show_readmore=<?php echo $show_readmore;?>";
    	url = url + "&show_addtocart=<?php echo $show_addtocart;?>&show_desc=<?php echo $show_desc;?>";
    	url = url + "&show_discountpricelayout=<?php echo $show_discountpricelayout;?>&Itemid=<?php echo $Itemid;?>";
    	url = url + "&texpricemin=<?php echo $texpricemin;?>&texpricemax=<?php echo $texpricemax;?>";

    	$products.load(url,'',function(){
            $ajaxMessage.css({display:'none'});
        });
    }
</script>
<!--<form action="<?php echo 'index.php?option=com_redshop';?>" method="post" name="adminForm" enctype="multipart/form-data">-->
<div id="pricefilter">
    <div class="left" id="leftSlider">
        <div id="range"><?php echo JText::_('COM_REDSHOP_PRICE').": ";?><span id="redamount"></span></div>
        <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" id="redslider">
        	<div style="left: 52.381%; width: 0%;" class="ui-slider-range ui-widget-header"></div>
        	<a style="left: 52.381%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
        	<a style="left: 52.381%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
        </div>
	</div>
	<div class="left" id="blankfilter"></div>
	<div class="left" id="productsWrap">
        <div style="display: none;" id="ajaxMessage"><?php echo JText::_('COM_REDSHOP_LOADING');?></div>
        <div id="redproducts"><?php echo JText::_('COM_REDSHOP_NO_PRODUCT_FOUND');?></div>
    </div>
</div>
