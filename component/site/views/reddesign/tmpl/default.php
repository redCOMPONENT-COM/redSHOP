<?php
defined ('_JEXEC') or die ('restricted access');

$url= JURI::base();
$pagetitle = JText::_('COM_REDSHOP_WELCOME_TO_REDDESIGN');
include_once (JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');
require_once( JPATH_COMPONENT_SITE.DS.'helpers'.DS.'product.php' );
$producthelper = new producthelper();
$Itemid = JRequest::getVar ( 'Itemid' );
$redhelper = new redhelper();
$Itemid = $redhelper->getCartItemid($Itemid);
$image_path = $url."components".DS."com_reddesign".DS."assets".DS."images".DS."designtype".DS;
$default_imageId = $this->image_id; 
?>
<script language='javascript'>
var rDesign = jQuery.noConflict();
var imageId = 0;
rDesign(function() {
	if(!rDesign("#selimage").val())	
		imageId = <?php echo $default_imageId ?>;
	else
		imageId = rDesign("#selimage").val();
	
	var alink = site_url+"index.php?tmpl=component&option=com_reddesign&view=getdesign&id="+imageId+"&task=loadimage";
	
	rDesign("#divDesignarea").load(site_url+'index.php?tmpl=component&option=com_reddesign&view=getdesign',
			{id:imageId,task:'loadoptions'},
			function(){
				rDesign("#divDesignAreaInput").html(rDesign("#tblDesignAreaInput").html());
				rDesign("#tblDesignAreaInput").html("");

				rDesign("#divDesignFontAlign").html(rDesign("#tblDesignFontAlign").html());
				rDesign("#tblDesignFontAlign").html("");

				rDesign("#divDesignFontType").html(rDesign("#tblDesignFontType").html());
				rDesign("#tblDesignFontType").html("");

				rDesign("#divDesignFontSize").html(rDesign("#tblDesignFontSize").html());
				rDesign("#tblDesignFontSize").html("");

				rDesign("#divDesignFontColor").html(rDesign("#tblDesignFontColor").html());
				rDesign("#tblDesignFontColor").html("");

				rDesign("#divDesignSaveButton").html(rDesign("#tblDesignSaveButton").html());
				rDesign("#tblDesignSaveButton").html("");
				});
	rDesign("#idimg").attr("src",alink);
	rDesign("#selimage").change(function(){
				imageId = rDesign("#selimage").val();
				rDesign("#divloadimage div.divarea").remove();
				var alink = site_url+"index.php?tmpl=component&option=com_reddesign&tmpl=component&view=getdesign&id="+rDesign("#selimage").val()+"&task=loadimage";
				rDesign("#idimg").attr("src",alink);
	
		rDesign("#divDesignarea").load(site_url+'index.php?tmpl=component&option=com_reddesign&view=getdesign&tmpl=component',{id:rDesign(this).val(),task:'loadoptions'},
			function(){
				rDesign("#divDesignAreaInput").html(rDesign("#tblDesignAreaInput").html());
				rDesign("#tblDesignAreaInput").html("");

				rDesign("#divDesignFontAlign").html(rDesign("#tblDesignFontAlign").html());
				rDesign("#tblDesignFontAlign").html("");

				rDesign("#divDesignFontType").html(rDesign("#tblDesignFontType").html());
				rDesign("#tblDesignFontType").html("");

				rDesign("#divDesignFontSize").html(rDesign("#tblDesignFontSize").html());
				rDesign("#tblDesignFontSize").html("");

				rDesign("#divDesignFontColor").html(rDesign("#tblDesignFontColor").html());
				rDesign("#tblDesignFontColor").html("");

				rDesign("#divDesignSaveButton").html(rDesign("#tblDesignSaveButton").html());
				rDesign("#tblDesignSaveButton").html("");
				}
		);
		});
});
function selectchange(){
	if(rDesign("#hdnActive").val())
		sendRequest(rDesign("#hdnActive").val());
}
function funtextAlign(align,btnid){
	rDesign("#tdAlign button").removeClass("selected");
	rDesign("#"+btnid).addClass("selected");
	rDesign("#hdnAlign").val(align);
	if(rDesign("#hdnActive").val())
		sendRequest(rDesign("#hdnActive").val());
}

var alink="";
function sendRequest(id)
{
	if(rDesign("#activecolor").css("background-color") == "undefined") 
		var Color = rDesign("#hdnColor").val();
	else
	{	
		var Color = rDesign("#activecolor").css("background-color");
		 rDesign("#hdnColor").val(Color);
	}
	var optVar = ""+rDesign("#fonttype").val();
	optVar += "|"+rDesign("#selfontsize").val();
	optVar += "|"+encodeURIComponent(Color);
	optVar += "|"+encodeURIComponent(rDesign("#txtArea_"+id).val());
	optVar += "|"+rDesign.trim(rDesign("#hdnAreaName_"+id).val());
	optVar += "|"+rDesign("#hdnAlign").val();
	
	rDesign("#hdnArea_"+id).val(optVar);
	var hdn = 0;
	var hdnurl=""; 
	rDesign(".tdDesign input:hidden").each(function(){
		hdn++;
		hdnurl += "&arg"+hdn+"="+rDesign(this).val();
		});
	hdnurl += "&targ="+hdn;
	
	var opt = "option=com_reddesign&tmpl=component";
	opt += "&view=getdesign";
	opt += "&id="+imageId;
	opt += "&task=loadimage";
	opt += hdnurl;
	//opt += "&posleft="+rDesign("#hdnposleft").val()+"&postop="+rDesign("#hdnpostop").val();

	alink = site_url+"index.php?tmpl=component&"+opt;

	/*if(rDesign("#txtArea_"+id).val()!="")
	{*/
		rDesign("#idimg").attr("src",alink);
	//}
}
function saveDesign()
{
	var hdnflag = "";
	rDesign(".tdDesign input:hidden").each(function(){
		hdnflag += rDesign(this).val();
	});
	if(hdnflag=="")
	{
		rDesign.ajax({
			url : site_url+"index.php?tmpl=component&option=com_reddesign&view=getdesign&tmpl=component&task=blankpdf&id="+imageId,
			type:'post',
			data:{saveimage:1},
			success:function(html){
				rDesign('#hdnargs').val("");
				rDesign('#image_id').val(imageId);
				rDesign('#reddesignfile').val(html);
				rDesign('#addtocartdesign').submit();
			}
		});
	}
	
	rDesign.ajax({
		url : alink,
		type:'post',
		data:{saveimage:1},
		success:function(html){
				if(isNaN(html))
				{
					var hdn = 0;
					var hdnargs=""; 
						rDesign(".tdDesign input:hidden").each(function(){
						hdnargs += rDesign(this).val()+"&";
						});
					rDesign('#hdnargs').val(hdnargs);
					rDesign('#image_id').val(imageId);
					rDesign('#reddesignfile').val(html);
				//
						rDesign.ajax({
						url : alink+'&saveimage=1&no_html=1&format=pdf',
						type:'post',
						data:{filename:html},
						success:function(){
							rDesign('#addtocartdesign').submit();
							}
						});
				}
				else
				{
					var area_name = rDesign.trim(rDesign("#hdnAreaName_"+html).val());
					alert("<?php echo JText::_('COM_REDSHOP_DESIGN_AREA')." "; ?>"+area_name+"<?php echo " ".JText::_('COM_REDSHOP_EXCEEDS_THE_DESIGN_AREA');?>"+".\n"+"<?php echo JText::_('COM_REDSHOP_PLEASE_CORRECT_THIS');?>");
				}			
			}
		});
}
function setOption(id)
{
	var rDesign = jQuery.noConflict();
		
	rDesign("#fonttype").load(site_url+"index.php?tmpl=component&option=com_reddesign&view=getdesign&tmpl=component&task=getfonttype&no_html=1&id="+id,{},function(){
		if(rDesign('#hdnArea_'+id).val()!="") {
	var getOpt = rDesign('#hdnArea_'+id).val();
		
	getOpt = getOpt.split("|");
	rDesign("#fonttype").val(getOpt[0]); }
	});
	rDesign("#selfontsize").load(site_url+"index.php?tmpl=component&option=com_reddesign&view=getdesign&tmpl=component&task=getfontsize&no_html=1&id="+id,{},function(){
		if(rDesign('#hdnArea_'+id).val()!="") {
	var getOpt = rDesign('#hdnArea_'+id).val();
		
	getOpt = getOpt.split("|");
	rDesign("#selfontsize").val(getOpt[1]); }
	});
	rDesign("#tdColor").load(site_url+"index.php?tmpl=component&option=com_reddesign&view=getdesign&tmpl=component&task=getcolors&no_html=1&id="+id,{},function(){
		if(rDesign('#hdnArea_'+id).val()!="") {
	var getOpt = rDesign('#hdnArea_'+id).val();
		
		getOpt = getOpt.split("|");
		rDesign("div.colorcode").each(function(){
		    rDesign(this).css("border-color","#CCCCCC");
			rDesign(this).removeAttr("id");
			 if(rDesign(this).css("background-color")==decodeURIComponent(getOpt[2])){
				rDesign(this).css("border-color","#000000");
				rDesign(this).attr("id","activecolor");
			}	
			});
			rDesign("div.allcolorcode").css("background-color",decodeURIComponent(getOpt[2])); }
		});
	
	rDesign("#hdnActive").val(id);
	rDesign(".rinput").css("border","");
	rDesign(".rinput").css("outline","");
	rDesign("#txtArea_"+id).css("border","1px solid #5B7AA1");
	rDesign("#txtArea_"+id).css("outline","2px solid #BACCE2");
	
	if(rDesign('#hdnArea_'+id).val()!="")
	{
		var getOpt = rDesign('#hdnArea_'+id).val();
		
		getOpt = getOpt.split("|");
		rDesign("#fonttype").val(getOpt[0]);
		rDesign("#selfontsize").val(getOpt[1]);
		rDesign("div.colorcode").each(function(){
	    rDesign(this).css("border-color","#CCCCCC");
		rDesign(this).removeAttr("id");
		 if(rDesign(this).css("background-color")==decodeURIComponent(getOpt[2])){
			rDesign(this).css("border-color","#000000");
			rDesign(this).attr("id","activecolor");
		}	
		});
		rDesign("div.allcolorcode").css("background-color",decodeURIComponent(getOpt[2]));
		rDesign("#tdAlign button").removeClass("selected");
		if(getOpt[5]==1)
			rDesign("#btnLeft").addClass("selected");
		else if(getOpt[5]==2)
			rDesign("#btnCenter").addClass("selected");
		else
			rDesign("#btnRight").addClass("selected");
		rDesign("#hdnAlign").val(getOpt[5]);
	}
	else
	{
		rDesign("#fonttype").val("");
		rDesign("#selfontsize").val("");
		rDesign("#activecolor").css("border-color","#CCCCCC");
		rDesign("div.colorcode").removeAttr("id");
		rDesign("div.allcolorcode").css("border-color","#000000");
		rDesign("div.colorcode:first").css("border-color","#000000");
		rDesign("div.colorcode:first").attr("id","activecolor");

		var url = site_url+'index.php?tmpl=component&option=com_reddesign&view=getdesign&tmpl=component&task=getfontalign&no_html=1&id='+id;
		rDesign.getJSON(url,{num: Math.random()},
		        function(data){
					rDesign("#tdAlign button").removeClass("selected");
						if(data==3)
							rDesign("#btnRight").addClass("selected");
						else if(data==2)
							rDesign("#btnCenter").addClass("selected");
						else
						{
							rDesign("#btnLeft").addClass("selected");
							data = 1;
						}
						rDesign("#hdnAlign").val(data);
		        });				
	}
}
</script>
<!--<h1 class="componentheading">
    <?php echo $pagetitle; ?>
</h1>
--><div id="divDesignarea" style="display:none" ></div>
<?php if($this->designtype_detail->reddesign_autotemplate) 
{
?>
<form name="addtocart0" action=""  id="addtocartdesign" method="post">
<!--<form name="addtocart0" method="post" id="addtocartdesign" action="index.php" >-->
<table>
	<tr>
		<td><div id="divImageSelection"><?php echo $this->lists['selimage']; ?></div></td>
		<td><div id="divProductName"><?php echo $this->product_detail->product_name ; ?></div></td>
	</tr>
	<tr>
		<td>
			<div id="divloadimage"  align="center" >
				<img id ="idimg" style="border:1px solid #000000;"  />
			</div>
		</td>
		<td valign="top">
			
			<div id="divProductPrice">
			<b><?php echo JText::_('COM_REDSHOP_PRICE')?>:</b><br/>
			<?php echo "<span class=\"price_currency_format\">".$producthelper->getProductFormattedPrice($this->product_detail->product_price)."</span> ";  ?>
			</div>
			
			<div id="divDesignAreaInput"></div>
			<div id="divDesignFontType"></div>
			<div id="divDesignFontSize"></div>
			<div id="divDesignFontColor"></div>
			<div id="divDesignFontAlign"></div>
			<div id="divDesignQuantity">
			<b><?php echo JText::_('COM_REDSHOP_DESIGN_QUANTITY');?> :</b><br/>
			<input name="quantity" id="quantity1" value="1" type="text" size="2">
			</div>
			<div id="divDesignSaveButton"></div>
			
			<input value="<?php echo $Itemid;?>" name="Itemid" type="hidden">			
			<input value="<?php echo $this->pid; ?>" name="product_id" type="hidden">
			<input value="<?php echo $this->cid; ?>" name="category_id" type="hidden">
			<input value="" name="hdnargs" type="hidden" id="hdnargs">
			<input value="" name="image_id" type="hidden" id="image_id">															
			<input value="cart" name="view" type="hidden">
			<input value="1" name="reddesign" type="hidden">
			<input value="com_redshop" name="option" type="hidden">
			<input value="order<?php echo time(); ?>" name="reddesignfile" id="reddesignfile" type="hidden">
			<input value="add" name="task" type="hidden">
			<input name="product_price" value="<?php echo $this->product_detail->product_price; ?>" type="hidden">
		</td>
	</tr>
	<tr>
	<td colspan="2">
		<div id="divProductDescription">
				<?php echo $this->product_detail->product_s_desc; ?>
		</div>
	</td>
	</tr>
</table>
</form>
<?php 
} 
else 
{
?>
<form name="addtocart0" action=""  id="addtocartdesign" method="post">
<?php 
	$template_desc = $this->templatedetail;
	
	$data_add =str_replace("{background_image_selection}",$this->lists['selimage'],$template_desc);
	$data_add =str_replace("{product_name}",$this->product_detail->product_name,$data_add);
	$data_add =str_replace("{image_area}",'<div id="divloadimage" align="center"><img id ="idimg" style="border:1px solid #000000;" /></div>',$data_add);
	
	$data_add =str_replace("{product_price_label}",JText::_('COM_REDSHOP_PRICE'),$data_add);
	$data_add =str_replace("{product_price}",'<span class="price_currency_format">'.REDCURRENCY_SYMBOL."</span> ".number_format($this->product_detail->product_price,2,PRICE_SEPERATOR,THOUSAND_SEPERATOR),$data_add);
	$data_add =str_replace("{design_area_input}",'<div id="divDesignAreaInput"></div>',$data_add);
	$data_add =str_replace("{product_short_desciption}",$this->product_detail->product_s_desc,$data_add);
	$data_add =str_replace("{save_design_button}",'<div id="divDesignSaveButton"></div>',$data_add);
	$data_add =str_replace("{font_select}",'<div id="divDesignFontType"></div>',$data_add);
	$data_add =str_replace("{font_size}",'<div id="divDesignFontSize"></div>',$data_add);
	$data_add =str_replace("{font_color}",'<div id="divDesignFontColor"></div>',$data_add);
	$data_add =str_replace("{font_align}",'<div id="divDesignFontAlign"></div>',$data_add);
	$data_add =str_replace("{design_quantity_label}", JText::_('COM_REDSHOP_DESIGN_QUANTITY'),$data_add);
	$data_add =str_replace("{design_quantity}",'<input name="quantity" id="quantity1" value="1" type="text" size="2" >',$data_add);
//	echo $data_add;
	echo eval("?>".$data_add."<?php ");
?>
	<input value="<?php echo $Itemid;?>" name="Itemid" type="hidden">
	<input value="<?php echo $this->pid; ?>" name="product_id" type="hidden">
	<input value="<?php echo $this->cid; ?>" name="category_id" type="hidden">
	<input value="" name="hdnargs" type="hidden" id="hdnargs">
	<input value="" name="image_id" type="hidden" id="image_id">															
	<input value="cart" name="view" type="hidden">
	<input value="1" name="reddesign" type="hidden">
	<input value="com_redshop" name="option" type="hidden">
	<input value="order<?php echo time(); ?>" name="reddesignfile" id="reddesignfile" type="hidden">
	<input value="add" name="task" type="hidden">
	<input name="product_price" value="<?php echo $this->product_detail->product_price; ?>" type="hidden">
	
</form>
<?php } ?>