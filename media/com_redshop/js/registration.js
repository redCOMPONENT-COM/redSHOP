if(typeof window["jQuery"]!="undefined"){var rss=jQuery.noConflict(),rs=jQuery.noConflict(),rscompany=jQuery.noConflict();rs().ready(function(){function t(){var e=[],t,n=window.location.href.slice(window.location.href.indexOf("?")+1).split("&");for(var r=0;r<n.length;r++)t=n[r].split("="),e.push(t[0]),e[t[0]]=t[1];return e}var e=t();rs.validator.addMethod("zipcode",function(e,t){return this.optional(t)||/^\d{4} ?[a-z]{2}$/i.test(e)||e.match(/(^\d{6}?$)|(^\d{5}?$)|(^\d{7}?$)|(^\d{4}?$)|(^\d{3}?$)|(^\d{8}?$)|(^\d{9}?$)|[A-Z]{1,2}\d[\dA-Z]?\s?\d[A-Z]{2}$/i)||e.match(/^[A-Z][0-9][A-Z].[0-9][A-Z][0-9]$/)||e.match(/^[A-Z][0-9][A-Z][0-9][A-Z][0-9]$/i)||e.match(/^[0-9]{5}$/)||e.match(/^[0-9]{2,2}\s[0-9]{3,3}$/)||e.match(/^[0-9]{3,3}\s[0-9]{2,2}$/)||e.match(/^[0-9]{4,4}-[0-9]{3,3}$/)||e.match(/^[0-9]{3,3}-[0-9]{2,2}$/)||e.match(/^[0-9]{2,2}-[0-9]{3,3}$/)||e.match(/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/)},Joomla.JText._("COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP")),rs.validator.addMethod("phone",function(e,t){return e=e.replace(/\s+/g,""),this.optional(t)||e.length>9||e.length>8||e.length>14&&e.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/)||e.match(/^(\(?(0|\+44)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/)||e.match(/^((0|\+44)7(5|6|7|8|9){1}\d{2}\s?\d{6})$/)||e.match(/^[0-9]{10}$|^\(0[1-9]{1}\)[0-9]{8}$|^[0-9]{8}$|^[0-9]{4}[ ][0-9]{3}[ ][0-9]{3}$|^\(0[1-9]{1}\)[ ][0-9]{4}[ ][0-9]{4}$|^[0-9]{4}[ ][0-9]{4}$/)},Joomla.JText._("COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE")),rs.validator.addMethod("emailCheck",function(e){var t="user/json_email_check";return $.ajax({cache:!1,async:!1,type:"POST",data:"email="+e,url:t,success:function(e){result=e=="TRUE"?!0:!1}}),result},""),rs("#adminForm").validate({rules:{firstname:"required",lastname:"required",username:{required:function(){return document.getElementById("createaccount")&&rs("#createaccount").is(":checked")||!document.getElementById("createaccount")&&rs("#username")?!0:!1},minlength:2},company_name:{required:function(){return rs("#toggler2").is(":checked")?!0:!1}},vat_number:{required:function(){return rs("#toggler2").is(":checked")&&redSHOP.RSConfig._("REQUIRED_VAT_NUMBER")==1?!0:!1}},country_code:{required:function(){return document.getElementById("div_country_txt")&&document.getElementById("div_country_txt").style.display!="none"?!0:!1}},state_code:{required:function(){return document.getElementById("div_state_txt")&&document.getElementById("div_state_txt").style.display!="none"?!0:!1}},ean_number:{required:function(){return rs("#toggler2").is(":checked")&&document.getElementById("ean_number")&&document.getElementById("ean_number").value!=""?!0:!1},minlength:13,maxlength:13,decimal:!1,negative:!1,number:!0},email1:{email:!0},email2:{required:!0,equalTo:"#email1"},password1:{required:function(){return document.getElementById("createaccount")&&rs("#createaccount").is(":checked")||document.getElementById("user_id")&&document.getElementById("user_id").value==0&&rs("#password1")?!0:!1},minlength:5},password2:{required:function(){return document.getElementById("createaccount")&&rs("#createaccount").is(":checked")||document.getElementById("user_id")&&document.getElementById("user_id").value==0&&rs("#password2")?!0:!1},minlength:5,equalTo:"#password1"},topic:{required:"#newsletter:checked",minlength:2},zipcode:{zipcode:!0},phone:{phone:!0},termscondition:{required:function(){return!document.getElementById("termscondition")|(document.getElementById("termscondition")&&rs("#termscondition").is(":checked"))?!1:!0}},agree:"required"},ignore:"#adminForm input:not(:visible)",messages:{required:Joomla.JText._("COM_REDSHOP_THIS_FIELD_IS_REQUIRED"),company_name:Joomla.JText._("COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME"),firstname:Joomla.JText._("COM_REDSHOP_YOUR_MUST_PROVIDE_A_FIRSTNAME"),lastname:Joomla.JText._("COM_REDSHOP_YOUR_MUST_PROVIDE_A_LASTNAME"),address:Joomla.JText._("COM_REDSHOP_YOUR_MUST_PROVIDE_A_ADDRESS"),zipcode:Joomla.JText._("COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP"),city:Joomla.JText._("COM_REDSHOP_YOUR_MUST_PROVIDE_A_CITY"),phone:Joomla.JText._("COM_REDSHOP_YOUR_MUST_PROVIDE_A_PHONE"),vies_wait_input:"",username:{required:Joomla.JText._("COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME"),minlength:Joomla.JText._("COM_REDSHOP_USERNAME_MIN_CHARACTER_LIMIT")},email1:{required:Joomla.JText._("COM_REDSHOP_PROVIDE_EMAIL_ADDRESS")},email2:{required:Joomla.JText._("COM_REDSHOP_PROVIDE_EMAIL_ADDRESS"),equalTo:Joomla.JText._("COM_REDSHOP_EMAIL_NOT_MATCH")},password1:{required:Joomla.JText._("COM_REDSHOP_THIS_FIELD_IS_REQUIRED"),minlength:Joomla.JText._("COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT")},password2:{required:Joomla.JText._("COM_REDSHOP_THIS_FIELD_IS_REQUIRED"),minlength:Joomla.JText._("COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT"),equalTo:Joomla.JText._("COM_REDSHOP_PASSWORD_NOT_MATCH")},termscondition:"Please select terms and conditions",agree:"Please accept our policy",ean_number:{minlength:Joomla.JText._("COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT"),maxlength:Joomla.JText._("COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT"),decimal:Joomla.JText._("COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT"),negative:Joomla.JText._("COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT"),number:Joomla.JText._("COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT")}}}),rs("#username").focus(function(){var e=rs("#firstname").val(),t=rs("#lastname").val();e&&t&&!this.value&&(this.value=e+"."+t)}),rs.validator.addMethod("billingRequired",function(e,t){return rs("#billisship").is(":checked")?rs(t).parents(".subTable").length:!this.optional(t)},"")})};