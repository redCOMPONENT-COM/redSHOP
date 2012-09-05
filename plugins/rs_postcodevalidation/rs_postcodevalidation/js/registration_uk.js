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
			if(document.getElementById("country_code"))
			{
				if(document.getElementById("country_code").value=='GBR')
				{
					return this.optional(element) || zipcode.match(/^([0-9A-Za-z]{3}) ([0-9A-Za-z]{3})?$/) || zipcode.match(/^([0-9A-Za-z]{4}) ([0-9A-Za-z]{3})?$/) || zipcode.match(/^([0-9A-Za-z]{2}) ([0-9A-Za-z]{3})?$/);
				}
				if(document.getElementById("country_code").value!='GBR')
				{
					if(document.getElementById("zipcode").value == "" && document.getElementById("zipcode").value != "none")
					{
						return false;
					} else {
						return true;
					}
				}	
			}
			if(document.getElementById("country_code_ST"))
			{
				if(document.getElementById("country_code_ST").value=='GBR')
				{
					return this.optional(element) || zipcode.match(/^([0-9A-Za-z]{3}) ([0-9A-Za-z]{3})?$/) || zipcode.match(/^([0-9A-Za-z]{4}) ([0-9A-Za-z]{3})?$/) || zipcode.match(/^([0-9A-Za-z]{2}) ([0-9A-Za-z]{3})?$/);
				}
				if(document.getElementById("country_code_ST").value!='GBR')
				{
					if(document.getElementById("zipcode_ST").value == "" && document.getElementById("zipcode_ST").value != "none")
					{
						return false;
					} else {
						return true;
					}
				}	
			}
		}, "Please specify a valid postal/zip code");
		
		rs.validator.addMethod("phone", function(phone, element) {
			phone = phone.replace(/\s+/g, "");
		    return this.optional(element) || phone.length > 9 || phone.length>14&&
		    phone.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/) || phone.match(/^(\(?(0|\+44)[1-9]{1}\d{1,4}?\)?\s?\d{3,4}\s?\d{3,4})$/) || phone.match(/^((0|\+44)7(5|6|7|8|9){1}\d{2}\s?\d{6})$/) || phone.match(/^[0-9]{10}$|^\(0[1-9]{1}\)[0-9]{8}$|^[0-9]{8}$|^[0-9]{4}[ ][0-9]{3}[ ][0-9]{3}$|^\(0[1-9]{1}\)[ ][0-9]{4}[ ][0-9]{4}$|^[0-9]{4}[ ][0-9]{4}$/);
		}, "Please specify a valid phone number");
		
		
		
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
			rs("#billinfo_adminForm").validate({
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
						required: function (){if(document.getElementById("is_company") && document.getElementById("is_company").value==1) {return true;}else {  return false;}}
					},
					country_code: {
						required: function (){if(document.getElementById("country_code") && document.getElementById("country_code").style.display!='none') {return true;}else {  return false;}}
					},
					state_code: {
						required: function (){if(document.getElementById("state_code") && document.getElementById("state_code").style.display!='none') {return true;}else {  return false;}}
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
					firstname: YOUR_MUST_PROVIDE_A_FIRSTNAME,
					lastname: YOUR_MUST_PROVIDE_A_LASTNAME,
					address:YOUR_MUST_PROVIDE_A_ADDRESS,
					zipcode:YOUR_MUST_PROVIDE_A_ZIP,
					city:YOUR_MUST_PROVIDE_A_CITY,
					phone:YOUR_MUST_PROVIDE_A_PHONE,
					username: {
						required: YOU_MUST_PROVIDE_LOGIN_NAME,
						minlength: "Your username must consist of at least 2 characters"
					},
					 
					password1: {
						minlength: PASSWORD_MIN_CHARACTER_LIMIT
					},
					password2: {
						minlength: PASSWORD_MIN_CHARACTER_LIMIT,
						equalTo: PASSWORD_NOT_MATCH
					},
					email1: {
						required: PROVIDE_EMAIL_ADDRESS
					},
					email2: {
						required: PROVIDE_EMAIL_ADDRESS,
		 				equalTo: EMAIL_NOT_MATCH
					},
					
					//email: "Please enter a valid email address",
					agree: "Please accept our policy"
				}
			});
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
			//alert(document.getElementById("div_state_txt").style.display);
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
					required: function (){if(rs("#toggler2").is(":checked")) {return true;}else {  return false;}}
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
					required: function (){if(document.getElementById("createaccount") && rs("#createaccount").is(":checked") || (!document.getElementById("createaccount") && rs("#password1") )) {return true;}else {  return false;}},
					minlength: 5
				},
				password2: {
					required: function (){if(document.getElementById("createaccount") && rs("#createaccount").is(":checked") || (!document.getElementById("createaccount") && rs("#password2") )) {return true;}else {  return false;}},
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
				agree: "required"
			},
			ignore:"#adminForm input:not(:visible)",
			messages: {
				required: THIS_FIELD_IS_REQUIRED,
				company_name:PLEASE_ENTER_COMPANY_NAME,
				firstname: YOUR_MUST_PROVIDE_A_FIRSTNAME,
				lastname: YOUR_MUST_PROVIDE_A_LASTNAME,
				address:YOUR_MUST_PROVIDE_A_ADDRESS,
				zipcode:YOUR_MUST_PROVIDE_A_ZIP,
				city:YOUR_MUST_PROVIDE_A_CITY,
				phone:YOUR_MUST_PROVIDE_A_PHONE,
				username: {
					required: YOU_MUST_PROVIDE_LOGIN_NAME,
					minlength: "Your username must consist of at least 2 characters"
				},			 
				email1: {
					required: PROVIDE_EMAIL_ADDRESS
				},
				email2: {
					required: PROVIDE_EMAIL_ADDRESS,
	 				equalTo: EMAIL_NOT_MATCH
				},
				password1: {
					required: THIS_FIELD_IS_REQUIRED,
					minlength: PASSWORD_MIN_CHARACTER_LIMIT
				},
				password2: {
					required: THIS_FIELD_IS_REQUIRED,
					minlength: PASSWORD_MIN_CHARACTER_LIMIT,
					equalTo: PASSWORD_NOT_MATCH
				},
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
