
var JSONstring;
var request;
var user_valid=1;
var email_valid=1;
function getHTTPObject()
{
	var xhr = false;
	if (window.XMLHttpRequest)
	{
		xhr = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		try
		{
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{
			try
			{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e)
			{
				xhr = false;
			}
		}
	}
	return xhr;
}

function select_dynamic_field(tpl_id,pr_id,sec1tion)
{
	/*
	 * redCRM Front-End Prefix
	 */
	var setcrmprefix = "";
	
	if(typeof(crmPREFIX) === "undefined"){
		
		setcrmprefix = "";		
	}else{
		if(crmPREFIX == 'rc')
			setcrmprefix = "administrator/";
	}
	
	if(tpl_id==0)
	{
		document.getElementById('dynamic_field').innerHTML="";
		return false;
	}

	var form = document.forms['adminForm'];
	
	request = getHTTPObject();
	request.onreadystatechange = templateData;
		
	request.open("GET", setcrmprefix+"index.php?option=com_redshop&view=product&task=template&tmpl=component&json&template_id="+tpl_id+"&product_id="+pr_id+"&section="+sec1tion+"&sid="+Math.random(), true);
	request.send(null);
}

function ajax_delete_subproperty(sp_id,subattribute_id){
	if (confirm("Are you sure you want to delete")) 
	{
		request = getHTTPObject();
		request.open("GET", "index.php?option=com_redshop&view=product_detail&task=delete_subprop&tmpl=component&sp_id="+sp_id+"&subattribute_id="+subattribute_id, true);
		request.send(null);
		return true;
	}
	return false;
}

function resetTermsCondition()
{
	if (document.getElementById('show_terms_and_conditions1') && document.getElementById('show_terms_and_conditions1').checked==1)
	{
		if(confirm("Are you sure you want to reset")) 
		{
			request = getHTTPObject();
			request.open("GET", "index.php?option=com_redshop&view=configuration&task=resetTermsCondition&tmpl=component", true);
			request.send(null);
			return true;
		}
	}
	return false;
}

function ajax_delete_property(attribute_id,property_id){
	if (confirm("Are you sure you want to delete")) 
	{
		request = getHTTPObject();
		request.open("GET", "index.php?option=com_redshop&view=product_detail&task=delete_prop&tmpl=component&attribute_id="+attribute_id+"&property_id="+property_id, true);
		request.send(null);
		return true;
	}
	return false;
}

function ajax_delete_attribute(product_id,attribute_id,attribute_set_id){
	
	if (confirm("Are you sure you want to delete")) 
	{
		request = getHTTPObject();
		request.open("GET", "index.php?option=com_redshop&view=product_detail&task=delete_attibute&tmpl=component&attribute_id="+attribute_id+"&product_id="+product_id+"&attribute_set_id="+attribute_set_id, true);
		request.send(null);
		return true;
	}
	return false;
}

function changeBookInvoice(val)
{
	if(val==2)
	{
		if(document.getElementById('booking_order_status'))
		{
			document.getElementById('booking_order_status').style.display = '';
		}
	}
	else
	{
		if(document.getElementById('booking_order_status'))
		{
			document.getElementById('booking_order_status').style.display = 'none';
		}
	}
}

function templateData()
{  
	if(request.readyState == 4)
	{
		
		document.getElementById('dynamic_field').innerHTML=request.responseText;
	
		
		var el = getElementsByClass('calendar',null,'img');
		 
		
		for ( i=0;i<el.length;i++ ) {
			// do stuff here with myEls[i]
			 var calImgId = el[i].id;
			 arr =  calImgId.split("_img");
			 n = arr.length;
			 var calName = arr[0];
			 var patt=/[rs_]*/;
			 
			 if(calName.match(patt) == 'rs_'){
			 
			 	window.addEvent('domready', function() {Calendar.setup({
		         	inputField     :    calName,     // id of the input field
		         	ifFormat       :    "%d-%m-%Y",      // format of the input field
		         	button         :    el[i].id,  // trigger for the calendar (button ID)
		        	align          :    "Tl",           // alignment (defaults to "Bl")
		        	singleClick    :    true
		     	});});
		     }
			}
		
		// mce_editable
		/*$$('a.modal-button').each(function(el) {
			el.addEvent('click', function(e) {
				new Event(e).stop();
				SqueezeBox.fromElement(el);
			});
		});*/
		
		var el = getElementsByClass('mce_editable',null,'textarea');
		for ( i=0;i<el.length;i++ ) {
			// do stuff here with myEls[i]
			 var textareaId = el[i].id;
			 
			 var patt=/[rs_]*/;
			 
			 if(textareaId.match(patt) == 'rs_'){
			 
				 tinyMCE.execCommand('mceToggleEditor', false, textareaId);return false;
		     }
			}
		
		
		
		//window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });	  
	}
}
function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}

function validate(ind)
{

	var form = document.forms['adminForm'];
	
	var JSONObject = new Object;
	
	JSONObject.ind = ind;
	
	JSONObject.userid = (form['id'].value) ? form['id'].value : form['user_id'].value;
	JSONObject.username = form['username'].value;	
	JSONObject.email = form['email'].value;
	
	if(JSONObject.username == ''){		
		user_valid=0;		
		document.getElementById('user_valid').style.color = "red";
		document.getElementById('user_valid').innerHTML = 'User Field can\'t be blank';
		return false;
	}
	 
	if(ind==2 && user_valid==1)
	{
		if(JSONObject.email == ''){
			email_valid=0;
			document.getElementById('email_valid').style.color = "red";
			document.getElementById('email_valid').innerHTML = 'E-mail Field can\'t be blank';
			return false;
		}
	}
	 
	var temp=validateemail(form['email']);	if(temp==false) return false; 
	
	JSONstring = JSON.stringify(JSONObject);
	
	request = getHTTPObject();
	request.onreadystatechange = function(){
		// if request object received response
		if(request.readyState == 4)
		{			
			// controller response
			var JSONtext = request.responseText;			 
			// convert received string to JavaScript object
			var JSONobject = JSON.parse(JSONtext);
			 
			// notice how variables are used
			if(JSONobject.ind == 1){
				
				if(JSONobject.username == 1){
					user_valid=0;					
				}else{ 
					user_valid=1;					
				}
			}
			else{
				
				if(JSONobject.email == 1){ 
					email_valid=0;					
				}else{ 
					email_valid=1;					
				}
			}
			
			if(user_valid == 0){
				document.getElementById('user_valid').style.color = "red";
				document.getElementById('user_valid').innerHTML = 'User already exist';
			}else{
				document.getElementById('user_valid').style.color = "green";
				document.getElementById('user_valid').innerHTML = 'Username is available';
			}
			
			if(email_valid == 0){
				document.getElementById('email_valid').style.color = "red";
				document.getElementById('email_valid').innerHTML = 'Email already exist';
			}else{
				document.getElementById('email_valid').style.color = "green";
				document.getElementById('email_valid').innerHTML = 'Email is available';
			}
		}
	}
	request.open("GET", "index.php?option=com_redshop&view=user_detail&task=validation&json="+JSONstring, true);
	request.send(null);
}

function checkmail(email)
{
 
    if(email.length <= 0)
	{
	  return true;
	}
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null) 
      {
	    var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
	    if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
return false; 
}
function validateemail(o)
{
	var strError;
	email=o.value;
			   if(!checkmail(email)) 
               { 
                 if(!strError || strError.length ==0) 
                 { 
                    strError ="Enter valid Email address "; 
                 }//if                                               
                 document.getElementById('email_valid').innerHTML = strError;
                 return false; 
               }
}

//******************************** Mail Section Select ******************


function mail_select(str)
{		
	var form = document.forms['adminForm'];
	
	var val = form.mail_section.value;
	
	var please = form.please.value;

	if(val != 'order_status'){
		
		document.getElementById("order_state_edit").style.display="none";
		document.getElementById("order_state").style.display="none";
		return false;
	}
	if(val == '0' ){
		alert(please);
		return true;	
	}
	
	var JSONObject = new Object;	
	 
	JSONObject.mail_order_status = form.mail_section.value;
	
	JSONstring = JSON.stringify(JSONObject);
	
	request = getHTTPObject();
	request.onreadystatechange = mail_order_status;
	request.open("GET", "index.php?tmpl=component&option=com_redshop&view=mail_detail&task=mail_section&json="+JSONstring, true);
	request.send(null);
}
function mail_order_status(){
	
	
	if(request.readyState == 4)
	{
		var JSONtext = request.responseText;
		 
		var JSONobject = JSON.parse(JSONtext);
		
		document.getElementById('responce').innerHTML=JSONobject.order_statusHtml;
		try
		 {
			document.getElementById("order_state").style.display="table-row";
			document.getElementById("order_state_edit").style.display="none";
		 }
		catch(ex)
		{
			document.getElementById("order_state").style.display="block";
			document.getElementById("order_state_edit").style.display="none";
		}
	}
}
/* Catalog colour added  */
var f=0;
function addNewcolor(tableRef){
	
	var ccode=document.getElementById("color_code_1").value;
	if(ccode=="")
	{
		var cat_img=document.getElementById("catalog_image").value;
	}
	var g=parseInt(document.getElementById("total_extra").value) + parseInt(f);	
	var myTable = document.getElementById(tableRef);
	var tBody = myTable.getElementsByTagName('tbody')[0];
	var newTR = document.createElement('tr');
	var newTD = document.createElement('td');
	var newTD1 = document.createElement('td');
	if(ccode!="")
	{
	is_img=0;
	code_img=document.getElementById("color_code_1").value;
	newTD.innerHTML = '<div style=" width:100px:height:100px;background-color:'+code_img+'">&nbsp;</div>';
	}
	else
	{
	is_img=1;
	code_img=document.getElementById("catalog_image").value;
	newTD.innerHTML = document.getElementById("image_dis").innerHTML;
	}
	
	newTD1.innerHTML = '<input type="hidden" name="colour_id[]" id="colour_id[]">  <input value="Delete" onclick="deletecolor(this)" class="button" type="button" />';
	newTD1.innerHTML += '<input type="hidden" value="'+code_img+'" name="code_image[]"  id="code_image[]"><input type="hidden" name="is_image[]" value="'+is_img+'" id="is_image[]">';
	newTR.appendChild (newTD);
	newTR.appendChild (newTD1);	
	tBody.appendChild(newTR);
	document.getElementById("color_code_1").value="";
	document.getElementById("catalog_image").value="";
	document.getElementById("image_display").src="";
	f++;
	}
function deletecolor(r) {
	 
	var i=r.parentNode.parentNode.rowIndex;
	document.getElementById('extra_table').deleteRow(i);
	 
	}
function xml_object()
{
	var xmlHttp;

			try {   xmlHttp=new XMLHttpRequest();  }
			catch (e)
			{   try    {     xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");    }
		
				catch (e)   {
				
				 try   {     xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");     }
		
				catch (e)     {   alert("Your browser does not support AJAX!");     return false;
		
				  } 	}
		  }
		 return xmlHttp;
}
function chk_preorder()
{
	if(document.getElementById("porder1").checked)
	{
		
				xmlHttp=xml_object();
				 xmlHttp.onreadystatechange=function()
			  	 {
					 if(xmlHttp.readyState==4)
					 {
				    	//alert(xmlHttp.responseText);
				    	document.getElementById("preorder_div").innerHTML=xmlHttp.responseText;
				    	
				  	 }
			
				}				
				 xmlHttp.open("GET","index.php?tmpl=component&option=com_redshop&view=product_container&showbuttons=1&print_display=1&preorder=1",true);
				 xmlHttp.send(null);
			 
	}
	else
	{
		document.getElementById("preorder_div").innerHTML="";
	}
}
 
function chk_manufacturer()
{
   mid=document.getElementById("supplier_id").value;	 
}

function change_input(ev,val1)
{	 
	if(document.getElementById("porder1").checked)	
	val2 = val1+'`'+1;
	else
	val2 = val1+'`'+0;
 
 //document.getElementById("porder").value
	document.getElementById("input").value=val2;
	document.getElementById("input").onkeypress(ev);
	document.getElementById("input").onkeyup(ev);
}

//remove Property image
var rtask = "";
var id = 0;
function removePropertyImage(image,task){
	
	id = image;
	rtask = task;	
	var con = confirm(WANT_TO_DELETE);
	if(con == true){	
	
		request = getHTTPObject();
		request.onreadystatechange = removeImageRes;
		request.open("GET", "index.php?tmpl=component&option=com_redshop&view=product_detail&task=remove"+task+"Image&pid="+id, true);
		request.send(null);
	}
}

function removeImageRes(){
	
	if(request.readyState == 4)
	{		
		var ele = rtask+"_image_"+id;
		var responce = request.responseText;
		
		if(document.getElementById(ele) && responce != "")
			document.getElementById(ele).style.display = "none";
	}
}

function checkDiscountEnable()
{
	if(document.getElementById('discount_enable1').checked)
	{
		document.getElementById('vouchers_enable0').checked = true;
		document.getElementById('coupons_enable0').checked = true;
	} else {
		document.getElementById('vouchers_enable1').checked = true;
		document.getElementById('coupons_enable1').checked = true;
	}
	checkDiscountType();
}
function checkCouponEnable()
{
	if(document.getElementById('coupons_enable1').checked)
	{
		document.getElementById('discount_enable0').checked = true;
		document.getElementById('vouchers_enable0').checked = true;
	} else {
		document.getElementById('discount_enable1').checked = true;
		document.getElementById('vouchers_enable1').checked = true;
	}
	checkDiscountType();
}
function checkVoucherEnable()
{
	if(document.getElementById('vouchers_enable1').checked)
	{
		document.getElementById('discount_enable0').checked = true;
		document.getElementById('coupons_enable0').checked = true;
	} else {
		document.getElementById('discount_enable1').checked = true;
		document.getElementById('coupons_enable1').checked = true;
	}
	checkDiscountType();
}
function checkDiscountType()
{
	if(document.getElementById('discount_type'))
	{
		discount_type = document.getElementById('discount_type').value;
		if(discount_type==0)
		{
			document.getElementById('discount_enable1').checked = true;
			document.getElementById('coupons_enable0').checked = true;
			document.getElementById('vouchers_enable0').checked = true;
		}
		if(discount_type==1)
		{
			if(document.getElementById('discount_enable1').checked)
			{
				document.getElementById('vouchers_enable0').checked = true;
				document.getElementById('coupons_enable0').checked = true;
			} 
			else if(document.getElementById('coupons_enable1').checked)
			{
				document.getElementById('discount_enable0').checked = true;
				document.getElementById('vouchers_enable0').checked = true;
			} 
			else
			{
				document.getElementById('vouchers_enable1').checked = true;
				document.getElementById('discount_enable0').checked = true;
				document.getElementById('coupons_enable0').checked = true;
			}
		} 
		if(discount_type==2)
		{
			document.getElementById('discount_enable1').checked = true;
			if(document.getElementById('coupons_enable1').checked)
			{
				document.getElementById('vouchers_enable0').checked = true;
			} 
			else 
			{
				document.getElementById('vouchers_enable1').checked = true;
				document.getElementById('coupons_enable0').checked = true;
			}
		}
		if(discount_type==3 || discount_type==4)
		{
			document.getElementById('discount_enable1').checked = true;
			document.getElementById('coupons_enable1').checked = true;
			document.getElementById('vouchers_enable1').checked = true;
		}
	}	
}
function getElementsByClassName(xx)
{
	var rl=new Array();
	var ael=document.all?document.all:document.getElementsByTagName('*');
	for(i=0,j=0;i<ael.length;i++)
	{
		if((ael[i].className==xx))
		{
			rl[j]=ael[i];
			j++;
		}
	}
	return rl;
}
function setProductUserFieldImage(id,prodid,field_id,ele)
{
	//var imgLength = document.getElementsByClassName('imgClass_'+prodid);
	var imgLength = getElementsByClassName('imgClass_'+prodid);
	if(document.getElementById(id))
	{
		//document.getElementById(id).value = value1;
		if(hasClass(ele,'selectedimg')){ 
			removeClass(ele,'selectedimg');
		}else
			ele.className += ' selectedimg';
		
		var imgFieldLength = document.getElementsByName('imgField[]');
		var imgIds = new Array();
		var p = 0;
		for(var g=0;g<imgFieldLength.length;g++)
		{
			
			if(hasClass(imgFieldLength[ g ],'selectedimg')){
				imgIds[p++] = imgFieldLength[ g ].id;
			}
		}
		
		if(document.getElementById('imgFieldId'+field_id)){
		 	document.getElementById('imgFieldId'+field_id).value = imgIds.join(',');
		}
		
	}
	
}

function setProductImageLink(id,prodid,field_id,ele)
{
	var imgLength = document.getElementsByClassName('imgClass_'+prodid);
	
	if(document.getElementById(id))
	{
		//document.getElementById(id).value = value1;
		if(hasClass(ele,'selectedimg')){ 
			removeClass(ele,'selectedimg');
			document.getElementById('hover_link'+id).style.display = "none";
		}else{
			ele.className += ' selectedimg';
			document.getElementById('hover_link'+id).style.display = "block";
		}
		var imgFieldLength = document.getElementsByName('imgField[]');
		var imgIds = new Array();
		var p = 0;
		for(var g=0;g<imgFieldLength.length;g++)
		{
			
			if(hasClass(imgFieldLength[ g ],'selectedimg')){
				imgIds[p++] = imgFieldLength[ g ].id;
			}
		}
		
		if(document.getElementById('imgFieldId'+field_id)){
		 	document.getElementById('imgFieldId'+field_id).value = imgIds.join(',');
		}
		
	}
	
}

function removeClass(ele,cls) 
{
	if (hasClass(ele,cls)) {
	var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
	ele.className=ele.className.replace(reg,' ');
	}
}

function hasClass(ele,cls) 
{
	return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}


function delimg(str, divname , spath, data_id)
{
//alert(str);
	var tmp=divname;
	if (window.XMLHttpRequest)
  	{// code for IE7+, Firefox, Chrome, Opera, Safari
  		xmlhttp=new XMLHttpRequest();
  	}
	else
	{// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{

		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		  {
			document.getElementById(divname).innerHTML=xmlhttp.responseText;
			document.getElementById(divname).style.display="none";
		  }
		  else
		  {
			 document.getElementById(tmp).innerHTML = "<img src='"+site_url+"/components/com_redshop/assets/images/loading.gif' />";	 
		  }
	}

	var linktocontroller = "index.php?option=com_redshop&view=configuration&task=removeimg&imname="+str+'&divname='+divname+'&data_id='+data_id+'&spath='+spath;
	//alert(linktocontroller);
	xmlhttp.open("GET",linktocontroller,true);
	xmlhttp.send(null);
}

// User registration related function

function showOfflineCompanyOrCustomer(isCompany)
{
	if(isCompany==1)	// For Company
	{
		if(document.getElementById('trCompanyName'))
		{
			document.getElementById('trCompanyName').style.display='';
		}
		if(document.getElementById('trEANnumber'))
		{
			document.getElementById('trEANnumber').style.display='';
		}
		if(document.getElementById('trReqnumber'))
		{
			document.getElementById('trReqnumber').style.display='';
		}
		if(document.getElementById('trVatNumber'))
		{
			document.getElementById('trVatNumber').style.display='';
		}
		if(document.getElementById('divContact'))
		{
			document.getElementById('divContact').style.display='';
		}
		if(document.getElementById('divFirstname'))
		{
			document.getElementById('divFirstname').style.display='';
		}
		if(document.getElementById('trLastname'))
		{
			document.getElementById('trLastname').style.display='';
		}
		if(document.getElementById('trTaxExempt'))
		{
			document.getElementById('trTaxExempt').style.display='';
		}
		if(document.getElementById('trTaxExemptRequest'))
		{
			document.getElementById('trTaxExemptRequest').style.display='';
		}
		if(document.getElementById('trTaxExemptApproved'))
		{
			document.getElementById('trTaxExemptApproved').style.display='';
		}
		if(document.getElementById('exCompanyField'))
		{
			document.getElementById('exCompanyField').style.display='';
		}
		if(document.getElementById('exCustomerField'))
		{
			document.getElementById('exCustomerField').style.display='none';
		}
		if(document.getElementById('exCompanyFieldST'))
		{
			document.getElementById('exCompanyFieldST').style.display='';
		}
		if(document.getElementById('exCustomerFieldST'))
		{
			document.getElementById('exCustomerFieldST').style.display='none';
		}
	}
	else	// For Customer
	{
		if(document.getElementById('trCompanyName'))
		{
			document.getElementById('trCompanyName').style.display='none';
		}
		if(document.getElementById('trEANnumber'))
		{
			document.getElementById('trEANnumber').style.display='none';
		}
		if(document.getElementById('trReqnumber'))
		{
			document.getElementById('trReqnumber').style.display='none';
		}
		if(document.getElementById('trVatNumber'))
		{
			document.getElementById('trVatNumber').style.display='none';
		}
		if(document.getElementById('divContact'))
		{
			document.getElementById('divContact').style.display='none';
		}
		if(document.getElementById('divFirstname'))
		{
			document.getElementById('divFirstname').style.display='';
		}
		if(document.getElementById('trLastname'))
		{
			document.getElementById('trLastname').style.display='';
		}
		if(document.getElementById('trTaxExempt'))
		{
			document.getElementById('trTaxExempt').style.display='none';
		}
		if(document.getElementById('trTaxExemptRequest'))
		{
			document.getElementById('trTaxExemptRequest').style.display='none';
		}
		if(document.getElementById('trTaxExemptApproved'))
		{
			document.getElementById('trTaxExemptApproved').style.display='none';
		}
		if(document.getElementById('exCompanyField'))
		{
			document.getElementById('exCompanyField').style.display='none';
		}
		if(document.getElementById('exCustomerField'))
		{
			document.getElementById('exCustomerField').style.display='';
		}
		if(document.getElementById('exCompanyFieldST'))
		{
			document.getElementById('exCompanyFieldST').style.display='none';
		}
		if(document.getElementById('exCustomerFieldST'))
		{
			document.getElementById('exCustomerFieldST').style.display='';
		}
	}
}

// wizrad

function getvatremove(vattax_rate_id)
{
	document.installform.vattax_rate_id.value=vattax_rate_id;
	document.installform.vatremove.value='1';
	document.installform.submit();

}



function validateExtrafield(form)
{	
	var me = form.elements;
	var r = new RegExp("^[a-zA-Z](([\.\-a-zA-Z0-9@])?[a-zA-Z0-9]*)*$", "i");
	var errorMSG = '';
	var iserror=0;
	// loop through all input elements in form
	var fieldErrorMessages = new Array;
	
	for (var i=0; i < me.length; i++) 
	{
		// check if element is mandatory; here required="1"
		var myenabled = (typeof(me[i].getAttribute('required')) == 'undefined' ) || (me[i].getAttribute('required') != 1);
		var mytyp = me[i].type;
		var myact = myenabled && mytyp != 'reset' && mytyp != 'button' && mytyp != 'submit' && mytyp != 'image';
		var myname = me[i].getAttribute('name');
		var req = me[i].getAttribute('required');
		var lbl = me[i].getAttribute('reqlbl');
		if(lbl==null)
		{
			lbl = me[i].getAttribute('userfieldlbl');
		}
		if(lbl==null)
		{
			lbl = me[i].getAttribute('attribute_name');
		}
		var error = me[i].getAttribute('errormsg');
		if(error==null)
		{
			error = IS_REQUIRED;
		}
		var val = me[i].value;		
		if(req != null && req == 1 && lbl!= null)
		{
			// validation for input type text and textarea			
			if (mytyp == 'text' || mytyp == 'textarea' ) {
				if(trim(val) == ''){
					// add up all error messages
					errorMSG += lbl + ' : ' + error + '\n';
					// notify user by changing background color, in this case to red
					me[i].style.borderColor = "red";
					iserror=1;
				} else if(trim(val) != '') me[i].style.borderColor = "";
			}
			
			// validation for select box
			if(mytyp == 'select-multiple' || mytyp == 'select-one')
			{
				var opSelect = 0;
				if(me[i].options.selectedIndex==-1)
				{
					opSelect = 1;
				}					
				for(var op=0; op< me[i].options.length; op++)
				{
					if( me[i].options[op].selected == true && me[i].options[op].value==0)
					{
						opSelect=1;
					}
				}
				if (opSelect==1) 
				{
					var alreadyFlagged = false;
					for (var j = 0, n = fieldErrorMessages.length; j < n; j++) {
						if (fieldErrorMessages[j] == me[i].getAttribute('name')) {
							alreadyFlagged = true;
							break
						}
					}
					if ( ! alreadyFlagged ) {
						fieldErrorMessages.push(me[i].getAttribute('name'));
						// add up all error messages 
						errorMSG += lbl + ' : '+ error +' \n';			
						
						// notify user by changing background color, in this case to red						
						me[i].parentNode.style.border = "solid 1px red";						
						iserror=1;
					}						
				} else if (opSelect == 0) me[i].parentNode.style.border = "none";				
			}				
		
			// validation for radio button and check box
			if (mytyp == 'radio' || mytyp == 'checkbox') {
				var rOptions = me[myname];
				var rChecked = 0;
				
				if(rOptions.length > 1) {
					for (var r=0; r < rOptions.length; r++) {
						if ( (typeof(rOptions[r].getAttribute('required')) != "null") && ( rOptions[r].getAttribute('required') == 1) ) {							
							if (rOptions[r].checked) {
								rChecked=1;
							}
						}
					}
				} else {					
					if (me[i].checked) {
						rChecked=1;
					}
				}
				if (rChecked==0) {
					for (var k=0; k < me.length; k++) {
						if (me[i].getAttribute('name') == me[k].getAttribute('name')) {
							if (me[k].checked) {
								rChecked=1;
								break;
							}
						}
					}
				}
				if (rChecked==0) {
					var alreadyFlagged = false;
					for (var j = 0, n = fieldErrorMessages.length; j < n; j++) {
						if (fieldErrorMessages[j] == me[i].getAttribute('name')) {
							alreadyFlagged = true;
							break
						}
					}
					if ( ! alreadyFlagged ) {
						fieldErrorMessages.push(me[i].getAttribute('name'));
						// add up all error messages 
						errorMSG += lbl + ' : '+ error +' \n';				
						
						// notify user by changing background color, in this case to red						
						me[i].parentNode.style.border = "solid 1px red";						
						iserror=1;
					}						
				} else if (rChecked == 1) me[i].parentNode.style.border = "none";
			}
					
			// validation for media
			if(mytyp == 'file' ){
				
				var fileval = document.getElementById(me[i].id).value;
				var hiddenfile = '';
				if(document.getElementById('hidden'+me[i].id))
				{
					hiddenfile = document.getElementById('hidden'+me[i].id).value;
				}
				
				if(fileval == '' && hiddenfile==''){					
					// add up all error messages 
					errorMSG += lbl + ' : '+ error +' \n';				
					
					// notify user by changing background color, in this case to red						
					me[i].style.borderColor = "red";						
					iserror=1;					
				}else if(iserror == 0 ) me[i].style.borderColor = '';
				
			}
		}
	}				
	
	if(iserror==1) {
		alert(errorMSG);
		return false;
	} else {
		return true;
	}	
}