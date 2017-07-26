// Only define the redSHOP namespace if not defined.
redSHOP = window.redSHOP || {};

redSHOP.compareAction = function(ele, command){

	if (!ele.length) {return;}

	var data = ele.val().split('.');

	if (data.length <= 1)
	{
		data = ele.attr('value').split('.');
	}

	var productId = data[0],
		categoryId = data[1];

	if (command == "")
	{
		command = (ele.is(":checked")) ? 'add' : 'remove';
	}

	jQuery.ajax({
		url: redSHOP.RSConfig._('SITE_URL') + 'index.php?tmpl=component&option=com_redshop&view=product&task=addtocompare',
		type: 'POST',
		dataType: 'json', data: {cmd: command, pid: productId, cid: categoryId},
		complete: function(xhr, textStatus) { },
		success: function(data, textStatus, xhr)
		{
			if (data.success === true)
			{
				jQuery('#divCompareProduct').html(data.html);
			}
			else
			{
				jQuery('#divCompareProduct').html(data.message + '<br />' + data.html);
			}

			// The checkbox "Add To Compare" should be unchecked after user deleting current view product from comparision list
			jQuery('a[id^="removeCompare"]').click(function(event) {
				// Check if deleted product is current view product
				if (jQuery(this).attr('value') ==  jQuery('[id^="rsProductCompareChk"]').val())
				{
					jQuery('[id^="rsProductCompareChk"]').prop('checked', false);
				}

				// Remove it from comparision list and return updated list
				redSHOP.compareAction(jQuery(this), "remove");
			});
		},
		error: function(xhr, textStatus, errorThrown) {
			//called when there is an error
		}
	});
};


// New registration functions
jQuery(document).ready(function() {
	billingIsShipping(document.getElementById('billisship'));

	// Show comparision list on first load of product view
	redSHOP.compareAction(jQuery('[id^="rsProductCompareChk"]'), "getItems");

	// Handle add (remove) product to (from) comparision list
	jQuery('[id^="rsProductCompareChk"]').click(function(event) {
		redSHOP.compareAction(jQuery(this), "");
	});

	// Hide some checkout view stuff
	jQuery('#divPrivateTemplateId').hide();
	jQuery('#divCompanyTemplateId').hide();

	// Click public or private registration form function
	showCompanyOrCustomer(jQuery('[id^=toggler]:checked').get(0));
});

function validateInputNumber(objid)
{
	if(document.getElementById(objid) && (trim(document.getElementById(objid).value)=="" || isNaN(document.getElementById(objid).value) || document.getElementById(objid).value<=0))
	{
		alert(Joomla.JText._('COM_REDSHOP_ENTER_NUMBER'));
		document.getElementById(objid).value = 1;
		return false;
	}
	return true;
}

function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

function userfieldValidation(extrafieldname)
{
	/***************************************
	 * validation
	 * for
	 * required
	 * custom userfield
	 ***************************************/
	if(document.getElementsByName(extrafieldname+'[]'))
	{
		var extrafields = document.getElementsByName(extrafieldname+'[]');
		var extrafields_val = extrafields.value;
		var extrafields_lbl = '';
		var previousfieldName = "";
		var fieldNamefrmId = "";
		var chk_flag = false;
		var rdo_previousfieldName = "";
		var rdo_fieldNamefrmId = "";
		var rdo_flag = false;

		for(var ex=0;ex<extrafields.length;ex++)
		{
			extrafields_req = extrafields[ex].getAttribute('required');
			extrafields_lbl = extrafields[ex].getAttribute('userfieldlbl');
			if(extrafields_req==1 && extrafields_lbl!=null)
			{
				if(extrafields[ex].type=='checkbox')
				{
					fieldNamefrmId = reverseString(extrafields[ex].id);
					fieldNamefrmId = reverseString(fieldNamefrmId.substr(fieldNamefrmId.indexOf("_")+1));
					if(previousfieldName != "" && previousfieldName!=fieldNamefrmId && chk_flag==false)
					{
						alert(extrafields[ex-1].getAttribute('userfieldlbl')+' '+Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
						return false;
					}

					if(previousfieldName != fieldNamefrmId)
					{	extrafieldVal = "";
						previousfieldName = fieldNamefrmId;
					}
					if(extrafields[ex].checked)
					{
						chk_flag = true;
						continue;
					}
					if((ex == (extrafields.length-1) && chk_flag==false) || (extrafields[ex+1].type!='checkbox') && chk_flag==false )
					{
						alert(extrafields[ex].getAttribute('userfieldlbl')+' '+Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
						return false;
					}
				}
				else if(extrafields[ex].type=='radio')
				{
					rdo_fieldNamefrmId = reverseString(extrafields[ex].id);
					rdo_fieldNamefrmId = reverseString(rdo_fieldNamefrmId.substr(rdo_fieldNamefrmId.indexOf("_")+1));

					if(rdo_previousfieldName != "" && rdo_previousfieldName!=rdo_fieldNamefrmId && rdo_flag==false)
					{
						alert(extrafields[ex-1].getAttribute('userfieldlbl')+' '+Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
						return false;
					}
					if(rdo_previousfieldName != rdo_fieldNamefrmId)
					{
						extrafieldVal= "";
						rdo_previousfieldName = rdo_fieldNamefrmId;
						rdo_flag = false;
						if(extrafields[ex].checked)
						{
							rdo_flag = true;
							continue;
						}
					}
					else
					{
						if(extrafields[ex].checked || rdo_flag== true)
						{
							rdo_flag = true;
							continue;
						}
						if((ex == (extrafields.length-1) && rdo_flag==false) || (extrafields[ex+1].type!='radio') && rdo_flag==false )
						{
							alert(extrafields[ex].getAttribute('userfieldlbl')+' '+Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
							return false;
						}
					}
				}
				else
				{
					extrafields_val = extrafields[ex].value;
					if(!extrafields_val)
					{
						alert(extrafields[ex].getAttribute('userfieldlbl')+' '+Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
						return false;
					}
				}
			}
		}
	}
	return true;
}

function reverseString(string)
{
	var splitext = string.split("");
	var revertext = splitext.reverse();
	var reversed = revertext.join("");
	return reversed;
}
// End

function GetXmlHttpObject()
{
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		return new XMLHttpRequest();
	}
	if (window.ActiveXObject)
	{
		// code for IE6, IE5
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}

function changeSubscriptionPrice(subid,subval,product_id)
{
	document.getElementById('hidden_subscription_id').value=subval;
	document.getElementById('hidden_subscription_prize').value = document.getElementById('hdn_subscribe_'+subid).value;
	calculateTotalPrice(product_id,0);
}

function getShippingrate()
{
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	{
		alert ("Your browser does not support XMLHTTP!");
		return;
	}
	var country_code = document.getElementById('country_code').value;
	var state_code = document.getElementById('state_code').value;
	var zip_code = document.getElementById('zip_code').value;
	var args = "country_code="+country_code+"&state_code="+state_code+"&zip_code="+zip_code;
	var url= redSHOP.RSConfig._('SITE_URL')+'index.php?tmpl=component&option=com_redshop&view=cart&task=getShippingrate&'+args;
	var total;

	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4)
		{
			response = xmlhttp.responseText.split('`');
			if(document.getElementById('spnShippingrate'))
			{
				document.getElementById('spnShippingrate').innerHTML = response[0];

				if(document.getElementById('spnTotal'))
				{
					document.getElementById('spnTotal').innerHTML = response[1];
				}
			}
		}
	};
	xmlhttp.open("GET",url,true);
	xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xmlhttp.send(null);
}

function expand_collapse(atag,pid)
{
	if(atag)
	{
		var sign = "+";
		if(atag.innerHTML == "+")
			sign = "-";
		atag.innerHTML = sign;

		if(document.getElementsByName("exp_"+pid))
		{
			var expdivs = document.getElementsByName("exp_"+pid);
			var style = 'none';
			if(expdivs[0].style.display == 'none')
				var style = 'block';


			for(var i=0;i<expdivs.length;i++)
				expdivs[i].style.display = style;
		}
	}
}

function setSliderMinMax()
{
	if(document.getElementById('slider_texpricemin') && document.getElementById('texpricemin'))
	{
		document.getElementById('texpricemin').value = document.getElementById('slider_texpricemin').value;
	}
	if(document.getElementById('slider_texpricemax') && document.getElementById('texpricemax'))
	{
		document.getElementById('texpricemax').value = document.getElementById('slider_texpricemax').value;
	}
	document.orderby_form.submit();
}

function setSliderMinMaxForManufactur()
{
	if(document.getElementById('slider_texpricemin') && document.getElementById('manuf_texpricemin'))
	{
		document.getElementById('manuf_texpricemin').value = document.getElementById('slider_texpricemin').value;
	}
	if(document.getElementById('slider_texpricemax') && document.getElementById('manuf_texpricemax'))
	{
		document.getElementById('manuf_texpricemax').value = document.getElementById('slider_texpricemax').value;
	}
	document.filterby_form.submit();
}

function setSliderMinMaxForTemplate()
{
	if(document.getElementById('slider_texpricemin') && document.getElementById('temp_texpricemin'))
	{
		document.getElementById('temp_texpricemin').value = document.getElementById('slider_texpricemin').value;
	}
	if(document.getElementById('slider_texpricemax') && document.getElementById('temp_texpricemax'))
	{
		document.getElementById('temp_texpricemax').value = document.getElementById('slider_texpricemax').value;
	}
	document.template_selecter_form.submit();
}

function finder_checkbox(frm)
{
		var chkboxs = frm.elements;

		for(var i=0;i<chkboxs.length;i++)
		{
			if(chkboxs[i].checked)
				return ;
		}

		if(i==chkboxs.length)
		{
			chkboxs[0].checked = true;
			chkboxs[0].value = '0';
		}
}

function submitme()
{}

function showhidebox(obj)
{
	if(obj && obj.checked)
	{
		if(document.getElementById('td_password'))
		{
		   document.getElementById('td_password').style.display='';
		}
		if(document.getElementById('td_b_password'))
		{
			document.getElementById('td_b_password').style.display='';
		}
		if(document.getElementById('td_username'))
		{
			document.getElementById('td_username').style.display='';
		}
		if(document.getElementById('td_username_lbl'))
		{
			document.getElementById('td_username_lbl').style.display='';
		}
		if(document.getElementById('tr_cmp_username'))
		{
			document.getElementById('tr_cmp_username').style.display='';
		}
		if(document.getElementById('td_required'))
		{
			document.getElementById('td_required').style.display='';
		}
	} else {
		if(document.getElementById('td_password'))
		{
			document.getElementById('td_password').style.display='none';
		}
		if(document.getElementById('td_b_password'))
		{
			document.getElementById('td_b_password').style.display='none';
		}
		if(document.getElementById('td_username'))
		{
			document.getElementById('td_username').style.display='none';
		}
		if(document.getElementById('td_username_lbl'))
		{
			document.getElementById('td_username_lbl').style.display='none';
		}
		if(document.getElementById('tr_cmp_username'))
		{
			document.getElementById('tr_cmp_username').style.display='none';
		}
		if(document.getElementById('td_required'))
		{
			document.getElementById('td_required').style.display='none';
		}
	}
	return ;
}

function showhideboxPrivate(obj)
{
	if(obj && obj.checked)
	{
		if(document.getElementById('register_private'))
		{
			document.getElementById('register_private').style.display='';
		}
		if(document.getElementById('register_company'))
		{
			document.getElementById('register_company').style.display='none';
		}
		var frm = document.adminForm;
		if(frm.createaccount)
		{
			showhidebox(frm.createaccount);
		}
	}
}

function showhideboxCompany(obj)
{
	if(obj && obj.checked)
	{
		if(document.getElementById('register_company'))
		{
			document.getElementById('register_company').style.display='';
		}
		if(document.getElementById('register_private'))
		{
			document.getElementById('register_private').style.display='none';
		}
		var frm = document.adminForm2;
		if(frm.createaccount)
		{
			showhidebox(frm.createaccount);
		}
	}
}

function showcustomfields()
{
	var form=document.adminForm;
	var checkbox= false;
	if(document.getElementById('toggler2'))
	{
		checkbox= document.getElementById('toggler2').checked;
	}
	if(checkbox)
	{
		if(document.getElementById('register_company'))
		{
			document.getElementById('register_company').style.display='';
		}
		if(document.getElementById('register_private'))
		{
			document.getElementById('register_private').style.display='none';
		}
		var frm = document.adminForm2;
		if(frm.createaccount)
		{
			showhidebox(frm.createaccount);
		}
	}
	else
	{
		if(document.getElementById('register_private'))
		{
			document.getElementById('register_private').style.display='';
		}
		if(document.getElementById('register_company'))
		{
			document.getElementById('register_company').style.display='none';
		}
		var frm = document.adminForm;
		if(frm.createaccount)
		{
			showhidebox(frm.createaccount);
		}
	}
}

function changeproductImage(product_id,imgPath,ahrefpath)
{
	if(document.getElementById('a_main_image'+product_id))
	{
		document.getElementById('a_main_image'+product_id).href=ahrefpath;
	}
	if(document.getElementById('main_image'+product_id))
	{
		document.getElementById('main_image'+product_id).src=imgPath;
	}
}

function billingIsShipping(obj)
{
	if(obj && obj.checked)
	{
		if(document.getElementById('divShipping'))
		{
		   document.getElementById('divShipping').style.display='none';
		}
	} else {
		if(document.getElementById('divShipping'))
		{
			document.getElementById('divShipping').style.display='';
		}
	}
}

function createUserAccount(obj)
{
	if(document.getElementById('tdUsernamePassword'))
	{
		if(obj && obj.checked)
		{
		   document.getElementById('tdUsernamePassword').style.display='';
		}
		else
		{
			document.getElementById('tdUsernamePassword').style.display='none';
		}
	}
}

function searchByPhone()
{
	value = '';
	if(document.getElementById('searchphone'))
	{
		value = document.getElementById('searchphone').value;
	}
	if(value)
	{
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
			if(document.getElementById('divSearchPhonemsg'))
			{
				document.getElementById('divSearchPhonemsg').style.display=(xmlhttp.responseText!="") ? "none" : "";
			}
			var str=xmlhttp.responseText.split("`_`");

				if(document.getElementById('address_ST'))
				{
					document.getElementById('address_ST').value=(str[1]) ? str[1] : "";
				}
				if(document.getElementById('zipcode_ST'))
				{
					document.getElementById('zipcode_ST').value=(str[2]) ? str[2] : "";
				}
				if(document.getElementById('city_ST'))
				{
					document.getElementById('city_ST').value=(str[3]) ? str[3] : "";
				}
				if(document.getElementById('phone_ST'))
				{
					document.getElementById('phone_ST').value=(str[5]) ? str[5] : "";
				}
				if(document.getElementById('firstname_ST'))
				{
					document.getElementById('firstname_ST').value=(str[6]) ? str[6] : "";
				}
				if(document.getElementById('lastname_ST'))
				{
					document.getElementById('lastname_ST').value=(str[7]) ? str[7] : "";
				}

				if(document.getElementById('company_name'))
				{
					document.getElementById('company_name').value=(str[0]) ? str[0] : "";
				}
				if(document.getElementById('address'))
				{
					document.getElementById('address').value=(str[1]) ? str[1] : "";
				}
				if(document.getElementById('zipcode'))
				{
					document.getElementById('zipcode').value=(str[2]) ? str[2] : "";
				}
				if(document.getElementById('city'))
				{
					document.getElementById('city').value=(str[3]) ? str[3] : "";
				}
				if(document.getElementById('phone'))
				{
					document.getElementById('phone').value=(str[5]) ? str[5] : "";
				}
				if(document.getElementById('firstname'))
				{
					document.getElementById('firstname').value=(str[6]) ? str[6] : "";
				}
				if(document.getElementById('lastname'))
				{
					document.getElementById('lastname').value=(str[7]) ? str[7] : "";
				}
			}
		}
		var linktocontroller = redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=registration&task=searchUserdetailByPhone&tmpl=component&phone="+value;
		xmlhttp.open("GET",linktocontroller,true);
		xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xmlhttp.send(null);
	}
}

function showCompanyOrCustomer(obj)
{
	if(!obj)
	{
		return false;
	}

	// For Company
	if(obj.value == 1)
	{
		template_id = parseInt(jQuery('#divCompanyTemplateId').html());

		jQuery('#is_company').val('1');
		jQuery('#company_registrationintro').show();
		jQuery('#customer_registrationintro').hide();
		jQuery('#exCompanyFieldST').show();
		jQuery('#exCustomerFieldST').hide();
	}
	// For Customer
	else
	{
		template_id = parseInt(jQuery('#divPrivateTemplateId').html());

		jQuery('#is_company').val('0');
		jQuery('#company_registrationintro').hide();
		jQuery('#customer_registrationintro').show();
		jQuery('#exCompanyFieldST').hide();
		jQuery('#exCustomerFieldST').show();
	}

	var linktocontroller = redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=registration&task=getCompanyOrCustomer&tmpl=component";
		linktocontroller += "&is_company="+obj.value+"&template_id="+template_id;

	var postData = jQuery("#redshopRegistrationForm #adminForm").serializeArray();

	postData = jQuery.grep(postData, function(value) {
		if (
			value.name != 'task' &&
			value.name != 'view' &&
			value.name != 'option' &&
			value.name != 'is_company'
		)
		{
			return true;
		}
	});


	jQuery.ajax({
		url: linktocontroller,
		type: 'GET',
		data: postData
	})
	.done(function(response) {

		jQuery('#tmpRegistrationDiv').html(response);

		if(obj.value==1)
		{
			jQuery('#tblcompany_customer').html(jQuery('#ajaxRegistrationDiv').html());
			jQuery('#tblprivate_customer').html('');
		}
		else
		{
			jQuery('#tblprivate_customer').html(jQuery('#ajaxRegistrationDiv').html());
			jQuery('#tblcompany_customer').html('');
		}

		jQuery('#tmpRegistrationDiv').html('');

		jQuery('select:not(".disableBootstrapChosen")').select2();
	})
	.fail(function() {
		console.warn("error");
	});
}

function getBillingTemplate(el)
{
	var isCompany = jQuery(el).val();
	var type = jQuery(el).attr('billing_type');
	var url = redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=registration&task=getBillingTemplate";

	jQuery.ajax({
		url: url,
		type: 'POST',
		data: {type: type, isCompany: isCompany},
		success: function(html) {
			jQuery('#wrapper-billing').html('');
			jQuery('#wrapper-billing').append(html);
		}
	})
}

function updateGLSLocation(zipcode)
{
	var url= redSHOP.RSConfig._('SITE_URL')+'index.php?tmpl=component&option=com_redshop&view=checkout&task=updateGLSLocation';
	var users_info_id = '';
	var propName = document.getElementsByName('users_info_id');

	for (var p = 0; p < propName.length; p++)
	{
		if (propName[p].checked)
		{
			users_info_id = propName[p].value;
		}
	}

	url += "&zipcode=" + zipcode + '&users_info_id=' + users_info_id;

	jQuery.ajax({
		url: url,
		type: 'GET'
	})
	.done(function(response) {
		jQuery('#rs_locationdropdown').html(response);
		jQuery('select:not(".disableBootstrapChosen")').select2();
	})
	.fail(function() {
		console.warn("error");
	});
}
function displaytextarea(obj)
{
	if(obj && obj.checked)
	{
		if(document.getElementById('rs_Divcustomer_messageTA'))
		{
		   document.getElementById('rs_Divcustomer_messageTA').style.display='block';
		}
	} else {
		if(document.getElementById('rs_Divcustomer_messageTA'))
		{
			document.getElementById('rs_Divcustomer_messageTA').style.display='none';
		}
	}
}
function onestepCheckoutProcess(objectname,classname)
{
	var newparam = "";
	var payment_method_id = "";

	if(objectname=="shipping_rate_id")
	{
		if(classname =="default_shipping_gls")
		{
			if(document.getElementById('rs_glslocationId'))
			{
				document.getElementById('rs_glslocationId').style.display='block';
			}
		}else{
			if(document.getElementById('rs_glslocationId'))
			{
				document.getElementById('rs_glslocationId').style.display='none';
			}
		}
	}

	if(document.getElementById('responceonestep'))
	{
		var propName = document.getElementsByName('payment_method_id');
		for(var p=0;p<propName.length;p++)
		{
			if (propName[p].checked)
			{
				payment_method_id = propName[p].value;
				newparam = newparam + "&payment_method_id=" + payment_method_id;
			}
			else
			{
				if (document.getElementById('divcardinfo_'+propName[p].value))
				{
					document.getElementById('divcardinfo_'+propName[p].value).innerHTML = "";
				}
			}
		}

		if(objectname=="payment_method_id")
		{
			xmlhttp1=GetXmlHttpObject();
			var url1= redSHOP.RSConfig._('SITE_URL')+'index.php?tmpl=component&option=com_redshop&view=checkout&task=displaycreditcard';
			url1 = url1 + newparam;

			xmlhttp1.onreadystatechange=function()
			{
				if (xmlhttp1.readyState==4)
				{
					if(document.getElementById('divcardinfo_'+payment_method_id))
					{
						document.getElementById('divcardinfo_'+payment_method_id).innerHTML = xmlhttp1.responseText;
						if(document.getElementById('creditcardinfo'))
						{
							document.getElementById('divcardinfo_'+payment_method_id).innerHTML = document.getElementById('creditcardinfo').innerHTML;
						}
					}
				} else {
					if(document.getElementById('divcardinfo_'+payment_method_id))
					{
						document.getElementById('divcardinfo_'+payment_method_id).innerHTML = "<br>Please wait while loading credit card information form<br><img src='"+redSHOP.RSConfig._('SITE_URL')+"/components/com_redshop/assets/images/preloader.jpeg' border='0'>";
					}
				}
			};
			xmlhttp1.open("GET",url1,true);
			xmlhttp1.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			xmlhttp1.send(null);
		}

		var users_info_id=0;
		var shipping_box_id = 0;
		var shipping_rate_id="";
		var rate_template_id=0;
		var cart_template_id=0;
		var customer_note="";
		var requisition_number="";
		var txt_referral_code ="";
		var rs_customer_message_ta ="";
		var Itemid = 0;

		var propName = document.getElementsByName('users_info_id');
		for(var p=0;p<propName.length;p++)
		{
			if(propName[p].checked)
			{
				users_info_id = propName[p].value;
			}
		}

		var propName = document.getElementsByName('shipping_box_id');
		for(var p=0;p<propName.length;p++)
		{
			if(propName[p].checked)
			{
				shipping_box_id = propName[p].value;
			}
		}

		var propName = document.getElementsByName('shipping_rate_id');
		for(var p=0;p<propName.length;p++)
		{
			if(propName[p].checked)
			{
				shipping_rate_id = propName[p].value;
			}
		}

		if(document.getElementById('divShippingRateTemplateId'))
		{
			rate_template_id = parseInt(document.getElementById('divShippingRateTemplateId').innerHTML);
		}

		if(document.getElementById('divRedshopCartTemplateId'))
		{
			cart_template_id = parseInt(document.getElementById('divRedshopCartTemplateId').innerHTML);
		}

		if(document.getElementById('customer_note'))
		{
			customer_note = document.getElementById('customer_note').value;
		}
		if(document.getElementById('requisition_number'))
		{
			requisition_number = document.getElementById('requisition_number').value;
		}
		if(document.getElementById('rs_customer_message_ta'))
		{
			rs_customer_message_ta = document.getElementById('rs_customer_message_ta').value;
		}
		if(document.getElementById('txt_referral_code'))
		{
			txt_referral_code = document.getElementById('txt_referral_code').value;
		}
		if(document.getElementById('onestepItemid'))
		{
			Itemid = document.getElementById('onestepItemid').value;
		}

		var postParams = {
			option : 'com_redshop',
			view : 'checkout',
			task : 'oneStepCheckoutProcess',
			users_info_id	: users_info_id,
			shipping_box_id	: shipping_box_id,
			shipping_rate_id	: shipping_rate_id,
			payment_method_id	: payment_method_id,
			rate_template_id	: rate_template_id,
			cart_template_id	: cart_template_id,
			customer_note	: unescape(customer_note),
			requisition_number	: requisition_number,
			rs_customer_message_ta	: rs_customer_message_ta,
			txt_referral_code	: txt_referral_code,
			objectname	: objectname,
			Itemid	: Itemid,
			sid	: Math.random()
		};

		jQuery(redSHOP).trigger("onBeforeOneStepCheckoutProcess", [postParams]);

		var url= redSHOP.RSConfig._('SITE_URL')+'index.php?tmpl=component';

		if(document.getElementById('divShippingRate') && (objectname=="users_info_id" || objectname=="shipping_box_id"))
		{
			document.getElementById('divShippingRate').innerHTML = "Loading...<img src='"+redSHOP.RSConfig._('SITE_URL')+"/components/com_redshop/assets/images/loading.gif' />";
		}
		if(document.getElementById('divRedshopCart'))
		{
			document.getElementById('divRedshopCart').innerHTML = "Loading...<img src='"+redSHOP.RSConfig._('SITE_URL')+"/components/com_redshop/assets/images/loading.gif' />";
		}

		jQuery.ajax({
			url: url,
			type: 'POST',
			dataType: 'html',
			data: postParams,
		})
		.done(function(txtresponse) {
			var arrResponse = txtresponse.split("`_`");

			document.getElementById('responceonestep').innerHTML = arrResponse[1];

			if(arrResponse[2] && document.getElementById('mod_cart_total_value_ajax'))
			{
				document.getElementById('mod_cart_total_value_ajax').innerHTML=arrResponse[2];
			}
			if(document.getElementById('divShippingRate') && document.getElementById('onestepshiprate') && document.getElementById('onestepshiprate').innerHTML!="")
			{
				document.getElementById('divShippingRate').innerHTML = document.getElementById('onestepshiprate').innerHTML;
			}

			if(document.getElementById('divRedshopCart') && document.getElementById('onestepdisplaycart') && document.getElementById('onestepdisplaycart').innerHTML!="")
			{
				document.getElementById('divRedshopCart').innerHTML = document.getElementById('onestepdisplaycart').innerHTML;
			}
			document.getElementById('responceonestep').innerHTML = "";

			SqueezeBox.initialize({});

			$$('a.modal').each(function(el) {
				el.addEvent('click', function(e) {
					new Event(e).stop();
					SqueezeBox.fromElement(el);
				});
			});
		})
		.fail(function() {
			console.warn("onestepCheckoutProcess Error");
		});
	}

	if (jQuery('.extrafield_payment').length)
	{
		jQuery('.extrafield_payment').children('[id^="extraFields"]').remove();

		if (objectname == "payment_method_id")
		{
			var paymentMethod = jQuery('[name="payment_method_id"]:checked');

			jQuery.ajax({
				url: redSHOP.RSConfig._('AJAX_BASE_URL'),
				type: 'POST',
				dataType: 'html',
				data: {
					view: 'checkout',
					task: 'ajaxDisplayPaymentExtraField',
					paymentMethod: paymentMethod.val()
				},
			})
			.done(function(html) {
				jQuery('#paymentblock #' + paymentMethod.val()).siblings('.extrafield_payment').append(html);

				if (jQuery('input[id^="rs_birthdate_"]').length)
				{
					window.addEvent('domready', function () {
						Calendar.setup({
							inputField: jQuery('input[id^="rs_birthdate_"]').attr('id'),
							ifFormat: "%d-%m-%Y",
							button: jQuery('button[id^="rs_birthdate_"]').attr('id'),
							align: "Tl",
							singleClick: true
						});
					});
				}
			})
			.fail(function() {
				console.log("extrafield payment get error");
			});
		}
	}

	if(document.getElementById('extrafield_shipping'))
	{
		if(objectname=="shipping_rate_id")
		{
			newparam = newparam + "&shipping_rate_id=" + classname;

			xmlhttp1=GetXmlHttpObject();
			var url1= redSHOP.RSConfig._('SITE_URL')+'index.php?tmpl=component&option=com_redshop&view=checkout&task=displayshippingextrafield';
			url1 = url1 + newparam;

			xmlhttp1.onreadystatechange=function()
			{
				if (xmlhttp1.readyState==4)
				{
					if(document.getElementById('extrafield_shipping'))
					{
						document.getElementById('extrafield_shipping').innerHTML = xmlhttp1.responseText;
					}
				}
			};
			xmlhttp1.open("GET",url1,true);
			xmlhttp1.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			xmlhttp1.send(null);
		}
	}
}

function autoFillCity(str,isShipping)
{
	if(str)
	{
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
				if(xmlhttp.responseText!="")
				{
					if(isShipping=="ST")
					{
						if(document.getElementById('city_ST'))
						{
							document.getElementById('city_ST').value=xmlhttp.responseText;
						}
					}
					else
					{
						if(document.getElementById('city'))
						{
							document.getElementById('city').value=xmlhttp.responseText;
						}
					}
				}
			}
		}
		var linktocontroller = redSHOP.RSConfig._('SITE_URL')+"index.php?option=com_redshop&view=category&task=autofillcityname&tmpl=component&q="+str;
		xmlhttp.open("GET",linktocontroller,true);
		xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xmlhttp.send(null);
	}
}
