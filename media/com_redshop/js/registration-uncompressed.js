if(typeof(window['jQuery']) != "undefined")
{
	var rss = jQuery.noConflict();
	var rs = jQuery.noConflict();
	var rscompany = jQuery.noConflict();

	rs().ready(function() {
		var hash = getUrlVars();

		function getUrlVars()
		{
			var vars = [], hash;
			var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

			for(var i = 0; i < hashes.length; i++)
			{
				hash = hashes[i].split('=');
				vars.push(hash[0]);
				vars[hash[0]] = hash[1];
			}
			return vars;
		}
		rs.validator.addMethod("zipcode", function(zipcode, element) {
			return this.optional(element) ||  /^\d{4} ?[a-z]{2}$/i.test(zipcode)  || zipcode.match(/(^\d{6}?$)|(^\d{5}?$)|(^\d{7}?$)|(^\d{4}?$)|(^\d{3}?$)|(^\d{8}?$)|(^\d{9}?$)|[A-Z]{1,2}\d[\dA-Z]?\s?\d[A-Z]{2}$/i) || zipcode.match(/^[A-Z][0-9][A-Z].[0-9][A-Z][0-9]$/) || zipcode.match(/^[A-Z][0-9][A-Z][0-9][A-Z][0-9]$/i) || zipcode.match(/^[0-9]{5}$/) || zipcode.match(/^[0-9]{2,2}\s[0-9]{3,3}$/) || zipcode.match(/^[0-9]{3,3}\s[0-9]{2,2}$/) || zipcode.match(/^[0-9]{4,4}-[0-9]{3,3}$/) || zipcode.match(/^[0-9]{3,3}-[0-9]{2,2}$/) || zipcode.match(/^[0-9]{2,2}-[0-9]{3,3}$/)|| zipcode.match(/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/);
		}, Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP'));

		rs.validator.addMethod("phone", function(phone, element) {
			phone = phone.replace(/\s+/g, "");
			return this.optional(element) || phone.length > 9 || phone.length > 8 || phone.length>14&&
			phone.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/) || phone.match(/^(\(?(0|\+44)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/) || phone.match(/^((0|\+44)7(5|6|7|8|9){1}\d{2}\s?\d{6})$/) || phone.match(/^[0-9]{10}$|^\(0[1-9]{1}\)[0-9]{8}$|^[0-9]{8}$|^[0-9]{4}[ ][0-9]{3}[ ][0-9]{3}$|^\(0[1-9]{1}\)[ ][0-9]{4}[ ][0-9]{4}$|^[0-9]{4}[ ][0-9]{4}$/);
		}, Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE'));

		rs.validator.addMethod('emailCheck', function(email) {
		var postURL = "user/json_email_check";
		$.ajax({
				 cache:false,
		async:false,
			type: "POST",
		   data: "email=" + email,
		  url: postURL,

		   success: function(msg) {

			 result = (msg=='TRUE') ? true : false;

			}

		});

		  return result;

		}, '');

		jQuery.validator.messages.required = Joomla.JText._('COM_REDSHOP_THIS_FIELD_IS_REQUIRED');
		rs("#adminForm").validate({

			rules: {
				firstname: "required",
				lastname: "required",
				username: {
					required: function (){if(document.getElementById("createaccount") && rs("#createaccount").is(":checked") || (!document.getElementById("createaccount") && rs("#username") )) {return true;}else {  return false;}},
					minlength: 2
				},
				company_name: {
					required: function (){if(rs("#toggler2").is(":checked")) {return true;}else {  return false;}}
				},
				vat_number: {
					required: function (){if(rs("#toggler2").is(":checked") && redSHOP.RSConfig._('REQUIRED_VAT_NUMBER')==1) {return true;}else {  return false;}}
				},
				country_code: {
					required: function (){if(document.getElementById("div_country_txt") && document.getElementById("div_country_txt").style.display!='none') {return true;}else {  return false;}}
				},
				state_code: {
					required: function (){if(document.getElementById("div_state_txt") && document.getElementById("div_state_txt").style.display!='none') {return true;}else {  return false;}}
				},
				ean_number: {
					required: function (){if(rs("#toggler2").is(":checked") && document.getElementById("ean_number") && document.getElementById("ean_number").value!='') {return true;}else {  return false;}},
					minlength: 13,
					maxlength: 13,
					decimal: false,
					negative: false,
					number: true
				},
				email1: {
					email: true
				},
				email2: {
					required: true,
					equalTo: "#email1"
				},
				password1: {
					required: function (){if(document.getElementById("createaccount") && rs("#createaccount").is(":checked") || (document.getElementById("user_id") && document.getElementById("user_id").value==0 && rs("#password1") )) {return true;}else {  return false;}},
					minlength: 5
				},
				password2: {
					required: function (){if(document.getElementById("createaccount") && rs("#createaccount").is(":checked") || (document.getElementById("user_id") && document.getElementById("user_id").value==0 && rs("#password2") )) {return true;}else {  return false;}},
					minlength: 5,
					equalTo: "#password1"
				},
				topic: {
					required: "#newsletter:checked",
					minlength: 2
				},
				zipcode: {
					zipcode: true
				},
				phone: {
					phone: rs('#phone').hasClass('required')
				},
				termscondition: {
					required: function (){if(!document.getElementById("termscondition") | (document.getElementById("termscondition") && rs("#termscondition").is(":checked"))) {return false;}else {  return true;}}
				},
				agree: "required"
			},
			ignore:"#adminForm input:not(:visible)",
			messages: {
				company_name:Joomla.JText._('COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME'),
				firstname: Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_FIRSTNAME'),
				lastname: Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_LASTNAME'),
				address:Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ADDRESS'),
				zipcode:Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP'),
				city:Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_CITY'),
				phone:Joomla.JText._('COM_REDSHOP_YOUR_MUST_PROVIDE_A_PHONE'),
				username: {
					required: Joomla.JText._('COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME'),
					minlength: Joomla.JText._('COM_REDSHOP_USERNAME_MIN_CHARACTER_LIMIT')
				},
				email1: {
					required: Joomla.JText._('COM_REDSHOP_PROVIDE_EMAIL_ADDRESS')
				},
				email2: {
					required: Joomla.JText._('COM_REDSHOP_PROVIDE_EMAIL_ADDRESS'),
					equalTo: Joomla.JText._('COM_REDSHOP_EMAIL_NOT_MATCH')
				},
				password1: {
					required: Joomla.JText._('COM_REDSHOP_THIS_FIELD_IS_REQUIRED'),
					minlength: Joomla.JText._('COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT')
				},
				password2: {
					required: Joomla.JText._('COM_REDSHOP_THIS_FIELD_IS_REQUIRED'),
					minlength: Joomla.JText._('COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT'),
					equalTo: Joomla.JText._('COM_REDSHOP_PASSWORD_NOT_MATCH')
				},
				termscondition: "Please select terms and conditions",
				agree: "Please accept our policy",
				ean_number: {
					minlength: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT'),
					maxlength: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT'),
					decimal: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT'),
					negative: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT'),
					number: Joomla.JText._('COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT')
				}
			}
		});

		// propose username by combining first- and lastname
		rs("#username").focus(function() {
			var firstname = rs("#firstname").val();
			var lastname = rs("#lastname").val();
			if(firstname && lastname && !this.value) {
				this.value = firstname + "." + lastname;
			}
		});

		rs.validator.addMethod("billingRequired", function(value, element) {
			if (rs("#billisship").is(":checked"))
			{
				return rs(element).parents(".subTable").length;
			}
			return !this.optional(element);
		}, "");

	});
}
