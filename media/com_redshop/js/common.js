function validateInputNumber(e){return!document.getElementById(e)||!(""==trim(document.getElementById(e).value)||isNaN(document.getElementById(e).value)||document.getElementById(e).value<=0)||(alert(Joomla.JText._("COM_REDSHOP_ENTER_NUMBER")),document.getElementById(e).value=1,!1)}function trim(e,t){return ltrim(rtrim(e,t),t)}function ltrim(e,t){return t=t||"\\s",e.replace(new RegExp("^["+t+"]+","g"),"")}function rtrim(e,t){return t=t||"\\s",e.replace(new RegExp("["+t+"]+$","g"),"")}function userfieldValidation(e){if(document.getElementsByName(e+"[]"))for(var t=document.getElementsByName(e+"[]"),n=t.value,d="",m="",o="",i=!1,r="",l="",a=!1,c=0;c<t.length;c++)if(extrafields_req=t[c].getAttribute("required"),d=t[c].getAttribute("userfieldlbl"),1==extrafields_req&&null!=d)if("checkbox"==t[c].type){if(o=reverseString(t[c].id),o=reverseString(o.substr(o.indexOf("_")+1)),""!=m&&m!=o&&0==i)return alert(t[c-1].getAttribute("userfieldlbl")+" "+Joomla.JText._("COM_REDSHOP_IS_REQUIRED")),!1;if(m!=o&&(extrafieldVal="",m=o),t[c].checked){i=!0;continue}if(c==t.length-1&&0==i||"checkbox"!=t[c+1].type&&0==i)return alert(t[c].getAttribute("userfieldlbl")+" "+Joomla.JText._("COM_REDSHOP_IS_REQUIRED")),!1}else if("radio"==t[c].type){if(l=reverseString(t[c].id),l=reverseString(l.substr(l.indexOf("_")+1)),""!=r&&r!=l&&0==a)return alert(t[c-1].getAttribute("userfieldlbl")+" "+Joomla.JText._("COM_REDSHOP_IS_REQUIRED")),!1;if(r!=l){if(extrafieldVal="",r=l,a=!1,t[c].checked){a=!0;continue}}else{if(t[c].checked||1==a){a=!0;continue}if(c==t.length-1&&0==a||"radio"!=t[c+1].type&&0==a)return alert(t[c].getAttribute("userfieldlbl")+" "+Joomla.JText._("COM_REDSHOP_IS_REQUIRED")),!1}}else if(n=t[c].value,!n)return alert(t[c].getAttribute("userfieldlbl")+" "+Joomla.JText._("COM_REDSHOP_IS_REQUIRED")),!1;return!0}function reverseString(e){var t=e.split(""),n=t.reverse(),d=n.join("");return d}function GetXmlHttpObject(){return window.XMLHttpRequest?new XMLHttpRequest:window.ActiveXObject?new ActiveXObject("Microsoft.XMLHTTP"):null}function changeSubscriptionPrice(e,t,n){document.getElementById("hidden_subscription_id").value=t,document.getElementById("hidden_subscription_prize").value=document.getElementById("hdn_subscribe_"+e).value,calculateTotalPrice(n,0)}function getShippingrate(){if(xmlhttp=GetXmlHttpObject(),null==xmlhttp)return void alert("Your browser does not support XMLHTTP!");var e=document.getElementById("country_code").value,t=document.getElementById("state_code").value,n=document.getElementById("zip_code").value,d="country_code="+e+"&state_code="+t+"&zip_code="+n,m=redSHOP.RSConfig._("SITE_URL")+"index.php?tmpl=component&option=com_redshop&view=cart&task=getShippingrate&"+d;xmlhttp.onreadystatechange=function(){4==xmlhttp.readyState&&(response=xmlhttp.responseText.split("`"),document.getElementById("spnShippingrate")&&(document.getElementById("spnShippingrate").innerHTML=response[0],document.getElementById("spnTotal")&&(document.getElementById("spnTotal").innerHTML=response[1])))},xmlhttp.open("GET",m,!0),xmlhttp.setRequestHeader("X-Requested-With","XMLHttpRequest"),xmlhttp.send(null)}function expand_collapse(e,t){if(e){var n="+";if("+"==e.innerHTML&&(n="-"),e.innerHTML=n,document.getElementsByName("exp_"+t)){var d=document.getElementsByName("exp_"+t),m="none";if("none"==d[0].style.display)var m="block";for(var o=0;o<d.length;o++)d[o].style.display=m}}}function setSliderMinMax(){document.getElementById("slider_texpricemin")&&document.getElementById("texpricemin")&&(document.getElementById("texpricemin").value=document.getElementById("slider_texpricemin").value),document.getElementById("slider_texpricemax")&&document.getElementById("texpricemax")&&(document.getElementById("texpricemax").value=document.getElementById("slider_texpricemax").value),document.orderby_form.submit()}function setSliderMinMaxForManufactur(){document.getElementById("slider_texpricemin")&&document.getElementById("manuf_texpricemin")&&(document.getElementById("manuf_texpricemin").value=document.getElementById("slider_texpricemin").value),document.getElementById("slider_texpricemax")&&document.getElementById("manuf_texpricemax")&&(document.getElementById("manuf_texpricemax").value=document.getElementById("slider_texpricemax").value),document.filterby_form.submit()}function setSliderMinMaxForTemplate(){document.getElementById("slider_texpricemin")&&document.getElementById("temp_texpricemin")&&(document.getElementById("temp_texpricemin").value=document.getElementById("slider_texpricemin").value),document.getElementById("slider_texpricemax")&&document.getElementById("temp_texpricemax")&&(document.getElementById("temp_texpricemax").value=document.getElementById("slider_texpricemax").value),document.template_selecter_form.submit()}function finder_checkbox(e){for(var t=e.elements,n=0;n<t.length;n++)if(t[n].checked)return;n==t.length&&(t[0].checked=!0,t[0].value="0")}function submitme(){}function showhidebox(e){e&&e.checked?(document.getElementById("td_password")&&(document.getElementById("td_password").style.display=""),document.getElementById("td_b_password")&&(document.getElementById("td_b_password").style.display=""),document.getElementById("td_username")&&(document.getElementById("td_username").style.display=""),document.getElementById("td_username_lbl")&&(document.getElementById("td_username_lbl").style.display=""),document.getElementById("tr_cmp_username")&&(document.getElementById("tr_cmp_username").style.display=""),document.getElementById("td_required")&&(document.getElementById("td_required").style.display="")):(document.getElementById("td_password")&&(document.getElementById("td_password").style.display="none"),document.getElementById("td_b_password")&&(document.getElementById("td_b_password").style.display="none"),document.getElementById("td_username")&&(document.getElementById("td_username").style.display="none"),document.getElementById("td_username_lbl")&&(document.getElementById("td_username_lbl").style.display="none"),document.getElementById("tr_cmp_username")&&(document.getElementById("tr_cmp_username").style.display="none"),document.getElementById("td_required")&&(document.getElementById("td_required").style.display="none"))}function showhideboxPrivate(e){if(e&&e.checked){document.getElementById("register_private")&&(document.getElementById("register_private").style.display=""),document.getElementById("register_company")&&(document.getElementById("register_company").style.display="none");var t=document.adminForm;t.createaccount&&showhidebox(t.createaccount)}}function showhideboxCompany(e){if(e&&e.checked){document.getElementById("register_company")&&(document.getElementById("register_company").style.display=""),document.getElementById("register_private")&&(document.getElementById("register_private").style.display="none");var t=document.adminForm2;t.createaccount&&showhidebox(t.createaccount)}}function showcustomfields(){var e=(document.adminForm,!1);if(document.getElementById("toggler2")&&(e=document.getElementById("toggler2").checked),e){document.getElementById("register_company")&&(document.getElementById("register_company").style.display=""),document.getElementById("register_private")&&(document.getElementById("register_private").style.display="none");var t=document.adminForm2;t.createaccount&&showhidebox(t.createaccount)}else{document.getElementById("register_private")&&(document.getElementById("register_private").style.display=""),document.getElementById("register_company")&&(document.getElementById("register_company").style.display="none");var t=document.adminForm;t.createaccount&&showhidebox(t.createaccount)}}function changeproductImage(e,t,n){document.getElementById("a_main_image"+e)&&(document.getElementById("a_main_image"+e).href=n),document.getElementById("main_image"+e)&&(document.getElementById("main_image"+e).src=t)}function billingIsShipping(e){e&&e.checked?document.getElementById("divShipping")&&(document.getElementById("divShipping").style.display="none"):document.getElementById("divShipping")&&(document.getElementById("divShipping").style.display="")}function createUserAccount(e){document.getElementById("tdUsernamePassword")&&(e&&e.checked?document.getElementById("tdUsernamePassword").style.display="":document.getElementById("tdUsernamePassword").style.display="none")}function searchByPhone(){if(value="",document.getElementById("searchphone")&&(value=document.getElementById("searchphone").value),value){window.XMLHttpRequest?xmlhttp=new XMLHttpRequest:xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"),xmlhttp.onreadystatechange=function(){if(4==xmlhttp.readyState&&200==xmlhttp.status){document.getElementById("divSearchPhonemsg")&&(document.getElementById("divSearchPhonemsg").style.display=""!=xmlhttp.responseText?"none":"");var e=xmlhttp.responseText.split("`_`");document.getElementById("address_ST")&&(document.getElementById("address_ST").value=e[1]?e[1]:""),document.getElementById("zipcode_ST")&&(document.getElementById("zipcode_ST").value=e[2]?e[2]:""),document.getElementById("city_ST")&&(document.getElementById("city_ST").value=e[3]?e[3]:""),document.getElementById("phone_ST")&&(document.getElementById("phone_ST").value=e[5]?e[5]:""),document.getElementById("firstname_ST")&&(document.getElementById("firstname_ST").value=e[6]?e[6]:""),document.getElementById("lastname_ST")&&(document.getElementById("lastname_ST").value=e[7]?e[7]:""),document.getElementById("company_name")&&(document.getElementById("company_name").value=e[0]?e[0]:""),document.getElementById("address")&&(document.getElementById("address").value=e[1]?e[1]:""),document.getElementById("zipcode")&&(document.getElementById("zipcode").value=e[2]?e[2]:""),document.getElementById("city")&&(document.getElementById("city").value=e[3]?e[3]:""),document.getElementById("phone")&&(document.getElementById("phone").value=e[5]?e[5]:""),document.getElementById("firstname")&&(document.getElementById("firstname").value=e[6]?e[6]:""),document.getElementById("lastname")&&(document.getElementById("lastname").value=e[7]?e[7]:"")}};var e=redSHOP.RSConfig._("SITE_URL")+"index.php?option=com_redshop&view=registration&task=searchUserdetailByPhone&tmpl=component&phone="+value;xmlhttp.open("GET",e,!0),xmlhttp.setRequestHeader("X-Requested-With","XMLHttpRequest"),xmlhttp.send(null)}}function showCompanyOrCustomer(e){if(!e)return!1;1==e.value?(template_id=parseInt(jQuery("#divCompanyTemplateId").html()),jQuery("#is_company").val("1"),jQuery("#company_registrationintro").show(),jQuery("#customer_registrationintro").hide(),jQuery("#exCompanyFieldST").show(),jQuery("#exCustomerFieldST").hide()):(template_id=parseInt(jQuery("#divPrivateTemplateId").html()),jQuery("#is_company").val("0"),jQuery("#company_registrationintro").hide(),jQuery("#customer_registrationintro").show(),jQuery("#exCompanyFieldST").hide(),jQuery("#exCustomerFieldST").show());var t=redSHOP.RSConfig._("SITE_URL")+"index.php?option=com_redshop&view=registration&task=getCompanyOrCustomer&tmpl=component";t+="&is_company="+e.value+"&template_id="+template_id,jQuery.ajax({url:t,type:"GET"}).done(function(t){jQuery("#tmpRegistrationDiv").html(t),1==e.value?(jQuery("#tblcompany_customer").html(jQuery("#ajaxRegistrationDiv").html()),jQuery("#tblprivate_customer").html("")):(jQuery("#tblprivate_customer").html(jQuery("#ajaxRegistrationDiv").html()),jQuery("#tblcompany_customer").html("")),jQuery("#tmpRegistrationDiv").html(""),jQuery('select:not(".disableBootstrapChosen")').select2()}).fail(function(){console.warn("error")})}function updateGLSLocation(e){xmlhttp1=GetXmlHttpObject();for(var t=redSHOP.RSConfig._("SITE_URL")+"index.php?tmpl=component&option=com_redshop&view=checkout&task=updateGLSLocation",n="",d=document.getElementsByName("users_info_id"),m=0;m<d.length;m++)d[m].checked&&(n=d[m].value);t=t+"&zipcode="+e+"&users_info_id="+n,xmlhttp1.onreadystatechange=function(){4==xmlhttp1.readyState&&document.getElementById("rs_locationdropdown")&&(document.getElementById("rs_locationdropdown").innerHTML=xmlhttp1.responseText)},xmlhttp1.open("GET",t,!0),xmlhttp1.setRequestHeader("X-Requested-With","XMLHttpRequest"),xmlhttp1.send(null)}function displaytextarea(e){e&&e.checked?document.getElementById("rs_Divcustomer_messageTA")&&(document.getElementById("rs_Divcustomer_messageTA").style.display="block"):document.getElementById("rs_Divcustomer_messageTA")&&(document.getElementById("rs_Divcustomer_messageTA").style.display="none")}function onestepCheckoutProcess(e,t){var n="",d="";if("shipping_rate_id"==e&&("default_shipping_gls"==t?document.getElementById("rs_glslocationId")&&(document.getElementById("rs_glslocationId").style.display="block"):document.getElementById("rs_glslocationId")&&(document.getElementById("rs_glslocationId").style.display="none")),document.getElementById("responceonestep")){for(var m=document.getElementsByName("payment_method_id"),o=0;o<m.length;o++)m[o].checked?(d=m[o].value,n=n+"&payment_method_id="+d):document.getElementById("divcardinfo_"+m[o].value)&&(document.getElementById("divcardinfo_"+m[o].value).innerHTML="");if("payment_method_id"==e){xmlhttp1=GetXmlHttpObject();var i=redSHOP.RSConfig._("SITE_URL")+"index.php?tmpl=component&option=com_redshop&view=checkout&task=displaycreditcard";i+=n,xmlhttp1.onreadystatechange=function(){4==xmlhttp1.readyState?document.getElementById("divcardinfo_"+d)&&(document.getElementById("divcardinfo_"+d).innerHTML=xmlhttp1.responseText,document.getElementById("creditcardinfo")&&(document.getElementById("divcardinfo_"+d).innerHTML=document.getElementById("creditcardinfo").innerHTML)):document.getElementById("divcardinfo_"+d)&&(document.getElementById("divcardinfo_"+d).innerHTML="<br>Please wait while loading credit card information form<br><img src='"+redSHOP.RSConfig._("SITE_URL")+"/components/com_redshop/assets/images/preloader.jpeg' border='0'>")},xmlhttp1.open("GET",i,!0),xmlhttp1.setRequestHeader("X-Requested-With","XMLHttpRequest"),xmlhttp1.send(null)}for(var r=0,l=0,a="",c=0,u=0,s="",p="",y="",g="",_=0,m=document.getElementsByName("users_info_id"),o=0;o<m.length;o++)m[o].checked&&(r=m[o].value);for(var m=document.getElementsByName("shipping_box_id"),o=0;o<m.length;o++)m[o].checked&&(l=m[o].value);for(var m=document.getElementsByName("shipping_rate_id"),o=0;o<m.length;o++)m[o].checked&&(a=m[o].value);document.getElementById("divShippingRateTemplateId")&&(c=parseInt(document.getElementById("divShippingRateTemplateId").innerHTML)),document.getElementById("divRedshopCartTemplateId")&&(u=parseInt(document.getElementById("divRedshopCartTemplateId").innerHTML)),document.getElementById("customer_note")&&(s=document.getElementById("customer_note").value),document.getElementById("requisition_number")&&(p=document.getElementById("requisition_number").value),document.getElementById("rs_customer_message_ta")&&(g=document.getElementById("rs_customer_message_ta").value),document.getElementById("txt_referral_code")&&(y=document.getElementById("txt_referral_code").value),document.getElementById("onestepItemid")&&(_=document.getElementById("onestepItemid").value);var h={option:"com_redshop",view:"checkout",task:"oneStepCheckoutProcess",users_info_id:r,shipping_box_id:l,shipping_rate_id:a,payment_method_id:d,rate_template_id:c,cart_template_id:u,customer_note:unescape(s),requisition_number:p,rs_customer_message_ta:g,txt_referral_code:y,objectname:e,Itemid:_,sid:Math.random()},E=redSHOP.RSConfig._("SITE_URL")+"index.php?tmpl=component";!document.getElementById("divShippingRate")||"users_info_id"!=e&&"shipping_box_id"!=e||(document.getElementById("divShippingRate").innerHTML="Loading...<img src='"+redSHOP.RSConfig._("SITE_URL")+"/components/com_redshop/assets/images/loading.gif' />"),document.getElementById("divRedshopCart")&&(document.getElementById("divRedshopCart").innerHTML="Loading...<img src='"+redSHOP.RSConfig._("SITE_URL")+"/components/com_redshop/assets/images/loading.gif' />"),jQuery.ajax({url:E,type:"POST",dataType:"html",data:h}).done(function(e){var t=e.split("`_`");document.getElementById("responceonestep").innerHTML=t[1],t[2]&&document.getElementById("mod_cart_total_value_ajax")&&(document.getElementById("mod_cart_total_value_ajax").innerHTML=t[2]),document.getElementById("divShippingRate")&&document.getElementById("onestepshiprate")&&""!=document.getElementById("onestepshiprate").innerHTML&&(document.getElementById("divShippingRate").innerHTML=document.getElementById("onestepshiprate").innerHTML),document.getElementById("divRedshopCart")&&document.getElementById("onestepdisplaycart")&&""!=document.getElementById("onestepdisplaycart").innerHTML&&(document.getElementById("divRedshopCart").innerHTML=document.getElementById("onestepdisplaycart").innerHTML),document.getElementById("responceonestep").innerHTML="",SqueezeBox.initialize({}),$$("a.modal").each(function(e){e.addEvent("click",function(t){new Event(t).stop(),SqueezeBox.fromElement(e)})})}).fail(function(){console.warn("onestepCheckoutProcess Error")})}if(jQuery(".extrafield_payment").length&&(jQuery(".extrafield_payment").children('[id^="extraFields"]').remove(),"payment_method_id"==e)){var I=jQuery('[name="payment_method_id"]:checked');jQuery.ajax({url:redSHOP.RSConfig._("AJAX_BASE_URL"),type:"POST",dataType:"html",data:{view:"checkout",task:"ajaxDisplayPaymentExtraField",paymentMethod:I.val()}}).done(function(e){jQuery("#paymentblock #"+I.val()).siblings(".extrafield_payment").append(e),jQuery('input[id^="rs_birthdate_"]').length&&window.addEvent("domready",function(){Calendar.setup({inputField:jQuery('input[id^="rs_birthdate_"]').attr("id"),ifFormat:"%d-%m-%Y",button:jQuery('button[id^="rs_birthdate_"]').attr("id"),align:"Tl",singleClick:!0})})}).fail(function(){console.log("extrafield payment get error")})}if(document.getElementById("extrafield_shipping")&&"shipping_rate_id"==e){n=n+"&shipping_rate_id="+t,xmlhttp1=GetXmlHttpObject();var i=redSHOP.RSConfig._("SITE_URL")+"index.php?tmpl=component&option=com_redshop&view=checkout&task=displayshippingextrafield";i+=n,xmlhttp1.onreadystatechange=function(){4==xmlhttp1.readyState&&document.getElementById("extrafield_shipping")&&(document.getElementById("extrafield_shipping").innerHTML=xmlhttp1.responseText)},xmlhttp1.open("GET",i,!0),xmlhttp1.setRequestHeader("X-Requested-With","XMLHttpRequest"),xmlhttp1.send(null)}}function autoFillCity(e,t){if(e){window.XMLHttpRequest?xmlhttp=new XMLHttpRequest:xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"),xmlhttp.onreadystatechange=function(){4==xmlhttp.readyState&&200==xmlhttp.status&&""!=xmlhttp.responseText&&("ST"==t?document.getElementById("city_ST")&&(document.getElementById("city_ST").value=xmlhttp.responseText):document.getElementById("city")&&(document.getElementById("city").value=xmlhttp.responseText))};var n=redSHOP.RSConfig._("SITE_URL")+"index.php?option=com_redshop&view=category&task=autofillcityname&tmpl=component&q="+e;xmlhttp.open("GET",n,!0),xmlhttp.setRequestHeader("X-Requested-With","XMLHttpRequest"),xmlhttp.send(null)}}redSHOP=window.redSHOP||{},redSHOP.compareAction=function(e,t){if(e.length){var n=e.val().split(".");n.length<=1&&(n=e.attr("value").split("."));var d=n[0],m=n[1];""==t&&(t=e.is(":checked")?"add":"remove"),jQuery.ajax({url:redSHOP.RSConfig._("SITE_URL")+"index.php?tmpl=component&option=com_redshop&view=product&task=addtocompare",type:"POST",dataType:"json",data:{cmd:t,pid:d,cid:m},complete:function(e,t){},success:function(e,t,n){e.success===!0?jQuery("#divCompareProduct").html(e.html):jQuery("#divCompareProduct").html(e.message+"<br />"+e.html),jQuery('a[id^="removeCompare"]').click(function(e){jQuery(this).attr("value")==jQuery('[id^="rsProductCompareChk"]').val()&&jQuery('[id^="rsProductCompareChk"]').prop("checked",!1),redSHOP.compareAction(jQuery(this),"remove")})},error:function(e,t,n){}})}},jQuery(document).ready(function(){billingIsShipping(document.getElementById("billisship")),redSHOP.compareAction(jQuery('[id^="rsProductCompareChk"]'),"getItems"),jQuery('[id^="rsProductCompareChk"]').click(function(e){redSHOP.compareAction(jQuery(this),"")}),jQuery("#divPrivateTemplateId").hide(),jQuery("#divCompanyTemplateId").hide(),showCompanyOrCustomer(jQuery("[id^=toggler]:checked").get(0))});
