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
			//if(document.getElementById("country_code").value=='IRL'){
			//	return true;
			//}else{
			return this.optional(element) ||  /^\d{4} ?[a-z]{2}$/i.test(zipcode)  || zipcode.match(/(^\d{6}?$)|(^\d{5}?$)|(^\d{7}?$)|(^\d{4}?$)|(^\d{3}?$)|(^\d{8}?$)|(^\d{9}?$)|[A-Z]{1,2}\d[\dA-Z]?\s?\d[A-Z]{2}$/i) || zipcode.match(/^[A-Z][0-9][A-Z].[0-9][A-Z][0-9]$/) || zipcode.match(/^[A-Z][0-9][A-Z][0-9][A-Z][0-9]$/i) || zipcode.match(/^[0-9]{5}$/) || zipcode.match(/^[0-9]{2,2}\s[0-9]{3,3}$/) || zipcode.match(/^[0-9]{3,3}\s[0-9]{2,2}$/) || zipcode.match(/^[0-9]{4,4}-[0-9]{3,3}$/) || zipcode.match(/^[0-9]{3,3}-[0-9]{2,2}$/) || zipcode.match(/^[0-9]{2,2}-[0-9]{3,3}$/)|| zipcode.match(/^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/);
			//return this.optional(element) || zipcode.match(/(^\d{6}?$)|(^\d{5}?$)|(^\d{7}?$)|(^\d{4}?$)|(^\d{3}?$)|(^\d{8}?$)|(^\d{9}?$)|([A-PR-UWYZ]([0-9]{1,2}|([A-HK-Y][0-9]|[A-HK-Y][0-9]([0-9]|[ABEHMNPRV-Y]))|[0-9][A-HJKS-UW])\ [0-9][ABD-HJLNP-UW-Z]{2}|(GIR\ 0AA)|(SAN\ TA1)|(BFPO\ (C\/O\ )?[0-9]{1,4})|((ASCN|BBND|[BFS]IQQ|PCRN|STHL|TDCU|TKCA)\ 1ZZ))$/);
			//return this.optional(element) || zipcode.match(/^[A-Z]{1,2}\d[\dA-Z]?\s?\d[A-Z]{2}$/i );
			//}
		}, COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP);

		rs.validator.addMethod("phone", function(phone, element) {
			phone = phone.replace(/\s+/g, "");
		    return this.optional(element) || phone.length > 9 || phone.length > 8 || phone.length>14&&
		    phone.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/) || phone.match(/^(\(?(0|\+44)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/) || phone.match(/^((0|\+44)7(5|6|7|8|9){1}\d{2}\s?\d{6})$/) || phone.match(/^[0-9]{10}$|^\(0[1-9]{1}\)[0-9]{8}$|^[0-9]{8}$|^[0-9]{4}[ ][0-9]{3}[ ][0-9]{3}$|^\(0[1-9]{1}\)[ ][0-9]{4}[ ][0-9]{4}$|^[0-9]{4}[ ][0-9]{4}$/);
		}, COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE);



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


//		if(hash=='com_redshop')
//		{
			// validate signup form on keyup and submit
			/*rs("#billinfo_adminForm").validate({
				rules: {
					firstname: "required",
					lastname: "required",
					//lastname: {
						//required: function (){if(document.getElementById("is_company") && document.getElementById("is_company").value==0) {return true;}else {return false;}}
					//},
					username: {
						required: function (){if(document.getElementById("user_id") && document.getElementById("user_id").value>0) {return true;}else {  return false;}},
						minlength: 2
					},
					company_name: {
						required: function (){if(document.getElementById("is_company") && document.getElementById("is_company").value==1) {return true;}else {  return false;}}
					},
					vat_number: {
						required: function (){if(document.getElementById("is_company") && document.getElementById("is_company").value==1 && REQUIRED_VAT_NUMBER==1) {return true;}else {  return false;}}
					},
					country_code: {
						required: function (){if(document.getElementById("div_country_txt") && document.getElementById("div_country_txt").style.display!='none') {return true;}else {  return false;}}
					},
					state_code: {
						required: function (){if(document.getElementById("div_state_txt") && document.getElementById("div_state_txt").style.display!='none') {return true;}else {  return false;}}
					},
//					ean_number: {
//						required: function (){if(document.getElementById("is_company") && document.getElementById("is_company").value==1) {return true;}else {  return false;}}
//					},
//					requisition_number: {
//						required: function (){if(document.getElementById("is_company") && document.getElementById("is_company").value==1 && document.getElementById("ean_number") && document.getElementById("ean_number").value!='') {return true;}else {return false;}}
//					},
				 	password1: {
						required: function (){if(document.getElementById("user_id") && document.getElementById("user_id").value==0) {return true;}else {return false;}},
						minlength: 5
					},
					password2: {
						required: function (){if(document.getElementById("user_id") && document.getElementById("user_id").value==0) {return true;}else {return false;}},
						minlength: 5,
						equalTo: "#password1"
					},
					email1: {
						email: true
					},
					email2: {
						required: true,
						equalTo: "#email1"
					},
//					topic: {
//						required: "#newsletter:checked",
//						minlength: 2
//					},
					zipcode: {
						zipcode: true
					},
					phone: {
				    //required: true,

						phone:true
				    },
					agree: "required"
				},
				messages: {
					firstname: COM_REDSHOP_YOUR_MUST_PROVIDE_A_FIRSTNAME,
					lastname: COM_REDSHOP_YOUR_MUST_PROVIDE_A_LASTNAME,
					address:COM_REDSHOP_YOUR_MUST_PROVIDE_A_ADDRESS,
					zipcode:COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP,
					city:COM_REDSHOP_YOUR_MUST_PROVIDE_A_CITY,
					phone:COM_REDSHOP_YOUR_MUST_PROVIDE_A_PHONE,
					username: {
						required: COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME,
						minlength: "Your username must consist of at least 2 characters"
					},

					password1: {
						minlength: COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT
					},
					password2: {
						minlength: COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT,
						equalTo: COM_REDSHOP_PASSWORD_NOT_MATCH
					},
					email1: {
						required: COM_REDSHOP_PROVIDE_EMAIL_ADDRESS
					},
					email2: {
						required: COM_REDSHOP_PROVIDE_EMAIL_ADDRESS,
		 				equalTo: COM_REDSHOP_EMAIL_NOT_MATCH
					},

					//email: "Please enter a valid email address",
					agree: "Please accept our policy"
				}
			});*/
//		}
//		rs("#shippinfo_frontForm").validate({
//			rules: {
//				firstname: "required",
//				lastname: "required",
//				agree: "required"
//			},
//			messages: {
//				firstname_ST: YOUR_MUST_PROVIDE_A_FIRSTTNAME,
//				lastname_ST: YOUR_MUST_PROVIDE_A_LASTTNAME,
//				address_ST: YOUR_MUST_PROVIDE_A_ADDRESS,
//				zipcode_ST: YOUR_MUST_PROVIDE_A_ZIP,
//				city_ST: YOUR_MUST_PROVIDE_A_CITY,
//				phone_ST: YOUR_MUST_PROVIDE_A_PHONE,
//				agree: "Please accept our policy"
//			}
//		});
//		rs.metadata.setType("attr", "validate");
			/*rs('#adminForm').submit(function(){
						rs("#adminForm input:not(:visible)").each(function(index){
							alert(rs(this).attr('id'));
						});
						return false;

				});*/
		rs("#adminForm").validate({

			rules: {
				firstname: "required",
				lastname: "required",
			//	lastname: {
				//	required: function (){if(rs("#toggler1").is(":checked")) {return true;}else {  return false;}}
				//},
				username: {
					required: function (){if(document.getElementById("createaccount") && rs("#createaccount").is(":checked") || (!document.getElementById("createaccount") && rs("#username") )) {return true;}else {  return false;}},
					minlength: 2
				},
				company_name: {
					required: function (){if(rs("#toggler2").is(":checked")) {return true;}else {  return false;}}
				},
				vat_number: {
					required: function (){if(rs("#toggler2").is(":checked") && REQUIRED_VAT_NUMBER==1) {return true;}else {  return false;}}
				},
				country_code: {
					required: function (){if(document.getElementById("div_country_txt") && document.getElementById("div_country_txt").style.display!='none') {return true;}else {  return false;}}
				},
				state_code: {
					required: function (){if(document.getElementById("div_state_txt") && document.getElementById("div_state_txt").style.display!='none') {return true;}else {  return false;}}
				},
//				ean_number: {
//					required: function (){if(rs("#toggler2").is(":checked")) {return true;}else {  return false;}}
//				},
//				requisition_number: {
//					required: function (){if(rs("#toggler2").is(":checked") && document.getElementById("ean_number") && document.getElementById("ean_number").value!=''){return true;}else {return false;}}
//				},
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
				    //required: true,
				    phone:true
				},
				termscondition: {
					required: function (){if(!document.getElementById("termscondition") | (document.getElementById("termscondition") && rs("#termscondition").is(":checked"))) {return false;}else {  return true;}}
				},
				agree: "required"
			},
			ignore:"#adminForm input:not(:visible)",
			messages: {
				required: COM_REDSHOP_THIS_FIELD_IS_REQUIRED,
				company_name:COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME,
				firstname: COM_REDSHOP_YOUR_MUST_PROVIDE_A_FIRSTNAME,
				lastname: COM_REDSHOP_YOUR_MUST_PROVIDE_A_LASTNAME,
				address:COM_REDSHOP_YOUR_MUST_PROVIDE_A_ADDRESS,
				zipcode:COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP,
				city:COM_REDSHOP_YOUR_MUST_PROVIDE_A_CITY,
				phone:COM_REDSHOP_YOUR_MUST_PROVIDE_A_PHONE,
				veis_wait_input:'',
				username: {
					required: COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME,
					minlength: COM_REDSHOP_USERNAME_MIN_CHARACTER_LIMIT
				},
				email1: {
					required: COM_REDSHOP_PROVIDE_EMAIL_ADDRESS
				},
				email2: {
					required: COM_REDSHOP_PROVIDE_EMAIL_ADDRESS,
	 				equalTo: COM_REDSHOP_EMAIL_NOT_MATCH
				},
				password1: {
					required: COM_REDSHOP_THIS_FIELD_IS_REQUIRED,
					minlength: COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT
				},
				password2: {
					required: COM_REDSHOP_THIS_FIELD_IS_REQUIRED,
					minlength: COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT,
					equalTo: COM_REDSHOP_PASSWORD_NOT_MATCH
				},
				termscondition: "Please select terms and conditions",
	//			email: "Please enter a valid email address",
				agree: "Please accept our policy"
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
