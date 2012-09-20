function checkveisvalid()
{
	if(document.getElementById('toggler2') && document.getElementById('vat_number') && document.getElementById('country_code'))
	{
		if(document.getElementById('toggler2').checked && document.getElementById('vat_number').value!="" && document.getElementById('country_code').value!="")
		{
			if(document.getElementById('veis_status_invalid1'))
			{
				if(document.getElementById('veis_status_invalid1').checked)
				{
					return false;
				}
			}
			if(document.getElementById("veis_wait_input"))
			{
				if(document.getElementById("veis_wait_input").value=="")
				{
					xmlhttp=GetXmlHttpObject();
					if (xmlhttp==null)
					{
						alert ("Your browser does not support XMLHTTP!");
						return;
					}
					var vat_number = '&vat_number='+document.getElementById('vat_number').value;
					var country_code = '&country_code='+document.getElementById('country_code').value;
					var url='index.php?tmpl=component&option=com_redshop&view=plugin&task=checkVeisValidation&type=redshop_veis_registration';
					url=url+vat_number+country_code;
					xmlhttp.onreadystatechange=stateChanged;
					xmlhttp.open("GET",url,true);
					xmlhttp.send(null);
				}
			}
		} else {
			return true;
		}
	} else {
		return true;
	}
	
}

function stateChanged()
{
	if (xmlhttp.readyState==4)
	{
		if(document.getElementById("veis_wait"))
		{
			document.getElementById("veis_wait").innerHTML=xmlhttp.responseText;
		}
		if(document.getElementById("veis_wait_input"))
		{
			document.getElementById("veis_wait_input").value="1";
			return false;
		}
	} else {
		if(document.getElementById("veis_wait"))
		{
			document.getElementById("veis_wait").innerHTML="<tr><td colspan='2' align='center'>Veryfies VAT number in EU VEIS DB in progress please wait...</td></tr>";
		}
	}
}	
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

function replaceveisval()
{
	if(document.getElementById("veis_wait_input"))
	{
		document.getElementById("veis_wait_input").value="";
	}
}