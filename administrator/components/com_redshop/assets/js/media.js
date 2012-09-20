

//******************************** Add New Media Element ******************


function addNewRow(tableRef){
	
		
	var myTable = document.getElementById(tableRef);
	var tBody = myTable.getElementsByTagName('tbody')[0];
	var newTR = document.createElement('tr');
	var newTD = document.createElement('td');
	var newTD1 = document.createElement('td');
	newTD.innerHTML = 'Media Name';
	newTD1.innerHTML = '<input type="file" name="file[]" value="" id="file[]" size="75"><input value="Delete" onclick="deleteRow(this)" class="button" type="button" />';
	newTR.appendChild (newTD);
	newTR.appendChild (newTD1);
	tBody.appendChild(newTR);
	
}


//******************************** Delete Media Element ******************

function deleteRow(r) {
	var i=r.parentNode.parentNode.rowIndex;
	document.getElementById('extra_table').deleteRow(i);
}

//******************************** Bulk Media Element ******************

function media_bulk(r){
	if(r.value == 'yes'){
		
		document.getElementById('bulk_field').style.display = 'block';
		document.getElementById('extra_field').style.display = 'none';
		document.getElementById('addvalue').style.visibility = 'hidden' ;		
		
	}else if(r.value == 'no'){
		document.getElementById('bulk_field').style.display = 'none';
		document.getElementById('extra_field').style.display = 'block';
		document.getElementById('addvalue').style.visibility = 'visible' ;
	}else{
		alert('Please Select');
	}
}





//++++++++++++++++++++++++++ Mail Section +++++++++++++++++++++++++++

var xmlHttp
//******************************** Check Browser Compability******************
function GetXmlHttpObject()
{
	
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
//******************************** Mail Section Select ******************

function mail_select(str)
{	
	
	xmlHttp = GetXmlHttpObject()
	
	var please = document.getElementById('please').value;
	
	if(str.value == 'register' || str.value == 'product')
		return false;
	if(str.value == '0' )
		alert(please);	
	
	
	xmlHttp.onreadystatechange =function() {
		
		if (xmlHttp.readyState == 4) {
			
			//alert(xmlHttp.responseText);
			 
			document.getElementById("responce").innerHTML=xmlHttp.responseText;
			try
			 {
				document.getElementById("order_state").style.display="table-row";
			 }
			catch(ex)
			{
				document.getElementById("order_state").style.display="block";
			}
		}
	}
	
	var url = "index.php?tmpl=component&option="+ str.title +"&view=media_detail&task=mail_section&mail_order_status=" + str.value;

	xmlHttp.open("GET", url, true)
	xmlHttp.send(null)
			
}