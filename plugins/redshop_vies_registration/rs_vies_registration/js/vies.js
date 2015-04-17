jQuery(document).ready(function(){
	function stateChanged() {
		if (xmlhttp.readyState == 4) {
			if (document.getElementById("vies_wait")) {
				document.getElementById("vies_wait").innerHTML = xmlhttp.responseText;
			}
			if (document.getElementById("vies_wait_input")) {
				document.getElementById("vies_wait_input").value = "1";
				return false;
			}
		} else {
			if (document.getElementById("vies_wait")) {
				document.getElementById("vies_wait").innerHTML = Joomla.JText._('PLG_REDSHOP_VIES_REGISTRATION_VERYFIES_VAT_NUMBER');
			}
		}
	}
	function GetXmlHttpObject() {
		if (window.XMLHttpRequest) {
			// Code for IE7+, Firefox, Chrome, Opera, Safari
			return new XMLHttpRequest();
		}
		if (window.ActiveXObject) {
			// Code for IE6, IE5
			return new ActiveXObject("Microsoft.XMLHTTP");
		}
		return null;
	}
	jQuery('#adminForm').on('focusout', '#vat_number', function(){
		jQuery('#vies_wait_input').val('');
	});
	jQuery('.registrationSubmitButton').on('click', function(){
		if (document.getElementById('toggler2') && document.getElementById('vat_number') && document.getElementById('country_code') && document.getElementById('toggler2').checked && document.getElementById('vat_number').value != "" && document.getElementById('country_code').value != "") {
			console.log('inner');
			if (document.getElementById('vies_status_invalid1')) {
				if (document.getElementById('vies_status_invalid1').checked) {
					return false;
				}
			}
			if (document.getElementById("vies_wait_input") && document.getElementById("vies_wait_input").value == "") {
				xmlhttp = GetXmlHttpObject();
				if (xmlhttp == null) {
					alert(Joomla.JText._('PLG_REDSHOP_VIES_REGISTRATION_BROWSER_NOT_SUPPORT_XMLHTTP'));
					return;
				}
				var vat_number = '&vat_number=' + document.getElementById('vat_number').value;
				var country_code = '&country_code=' + document.getElementById('country_code').value;
				var url = 'index.php?tmpl=component&option=com_redshop&view=plugin&task=checkViesValidation&type=redshop_vies_registration';
				url = url + vat_number + country_code;
				xmlhttp.onreadystatechange = stateChanged;
				xmlhttp.open("POST", url, true);
				xmlhttp.send(null);
			}
		}
		return true;
	})
});