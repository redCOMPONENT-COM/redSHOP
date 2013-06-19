
if (!String.prototype.trim) {
   //code for trim
	String.prototype.trim=function(){return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');};

}

function strpos (haystack, needle, offset)
{
	var i = (haystack+'').indexOf(needle, (offset || 0));
	return i === -1 ? false : i;
}


function field_select(value)
{
	if(value==3 || value==4 || value==5 || value==6 || value==11 || value==13)
	{
		document.getElementById("field_data").style.display="block";
		if(value==11 || value==13)
		{
			document.getElementById("divfieldFile").style.display="block";
			document.getElementById("divfieldText").style.display="none";
		} else {
			document.getElementById("divfieldFile").style.display="none";
			document.getElementById("divfieldText").style.display="block";
		}
	}
	else
	{
		document.getElementById("field_data").style.display="none";
	}

	if(value==15)
	{
		document.adminForm.field_section.disabled=true;
		document.adminForm.field_section.options[8].selected=true;
	} else {
		document.adminForm.field_section.disabled=false;
		document.adminForm.field_section.options[0].selected=true;
	}
}


var f=1;
function addNewRow(tableRef){
	var g=parseInt(document.getElementById("total_extra").value) + parseInt(f);
	var myTable = document.getElementById(tableRef);
	var tBody = myTable.getElementsByTagName('tbody')[0];
	var newTR = document.createElement('tr');
	var newTD = document.createElement('td');
	var newTD1 = document.createElement('td');
	var newTD2 = document.createElement('td');

	var fieldtp = "text";
	fieldname = "extra_name[]";
	var fieldtype = document.getElementById("field_type").value;
	if(fieldtype==11 || fieldtype==13)
	{
		fieldtp = "file";
		fieldname = "extra_name_file[]";
	}
	newTD.innerHTML = '<input type="'+fieldtp+'" name="'+fieldname+'" value="field_temp_opt_'+g+'" id="'+fieldname+'">';
	newTD1.innerHTML = '<input type="text" name="extra_value[]" value="" id="extra_value[]">&nbsp;<input type="hidden" name="value_id[]" id="value_id[]">  <input value="Delete" onclick="deleteRow(this)" class="button" type="button" />';
	newTD2.innerHTML = '&nbsp;';
	newTR.appendChild (newTD);
	newTR.appendChild (newTD1);
	newTR.appendChild (newTD2);
	tBody.appendChild(newTR);
	f++;

	//modalpopup();
	}

var f=1;
function addNewRowcustom(field_name){

	var tableRef='extra_table';
	var g=parseInt(document.getElementById("total_extra").value) + parseInt(f);
	var myTable = document.getElementById(tableRef);
	var tBody = myTable.getElementsByTagName('tbody')[0];
	var newTR = document.createElement('tr');
	var newTD = document.createElement('td');
	var newTD1 = document.createElement('td');
	var newTD2 = document.createElement('td');

	var fieldtp = "text";
	fieldname = field_name.name+"_extra_name[]";

	newTD.innerHTML = '<input type="'+fieldtp+'" name="'+fieldname+'" value="" id="'+fieldname+'">';
	newTD1.innerHTML = '<input type="hidden" name="value_id[]" id="value_id[]">  <input value="Delete" onclick="deleteRow(this)" class="button" type="button" />';
	newTD2.innerHTML = '&nbsp;';
	newTR.appendChild (newTD);
	newTR.appendChild (newTD1);
	newTR.appendChild (newTD2);
	tBody.appendChild(newTR);
	f++;
	//modalpopup();
	}

function create_table_data(data,volume,id){
	name=data;

	var g=parseInt(document.getElementById("total_extra").value) + parseInt(f);
	var myTable = document.getElementById('container_table');
	var tBody = myTable.getElementsByTagName('tbody')[0];
	var newTR = document.createElement('tr');

	var newTD1 = document.createElement('td');
	var newTD2 = document.createElement('td');
	var newTD3 = document.createElement('td');
	var newTD4 = document.createElement('td');
	var cdata=0;

	if(document.getElementById("porder1").checked)
	cdata=1

	newTD1.innerHTML = name;
	newTD2.innerHTML = '<input size="5" type="text" name="quantity[]" value="1" onchange="changeM3('+id+',this.value)" id="quantity[]"><input type="hidden" name="container_product[]" value="'+id+'" id="container_product[]"><input type="hidden" value="'+cdata+'" name="container_porder[]" >';
	newTD3.innerHTML = '<div align="center"><input size="5" type="text" name="volume[]" id="volume'+id+'" value="'+volume+'" readonly="readonly" /></div>';
	newTD4.innerHTML = "<input value=\"X\" onclick=\"javascript:deleteRow_container(this);\" class=\"button\" type=\"button\" />";

	newTR.appendChild (newTD1);
	newTR.appendChild (newTD2);
	newTR.appendChild (newTD3);
	newTR.appendChild (newTD4);
	tBody.appendChild(newTR);
	f++;

	//modalpopup();
}
function changeM3(id,qty,volume){
 //var volume = document.getElementById('volume'+id).value;
  document.getElementById('volume'+id).value = qty*volume;
}
function create_table_accessory(data,id,price){
	name=data;
	var g=parseInt(document.getElementById("total_accessory").value) + parseInt(f);
	var myTable = document.getElementById('accessory_table');
	var tBody = myTable.getElementsByTagName('tbody')[0];
	var newTR = document.createElement('tr');

	var newTD1 = document.createElement('td');
	var newTD2 = document.createElement('td');
	var newTD3 = document.createElement('td');
	var newTD4 = document.createElement('td');
	var newTD5 = document.createElement('td');
//	var newTD6 = document.createElement('td');
	var newTD7 = document.createElement('td');

	newTD1.innerHTML = name+'<input type="hidden" value="'+id+'" name="product_accessory['+g+'][child_product_id]"><input type="hidden" value="0" name="product_accessory['+g+'][accessory_id]">';
	newTD2.innerHTML = price;
	newTD3.innerHTML = '<input size="1" maxlength="1" onchange="javascript:oprand_check(this);" type="text" name="product_accessory['+g+'][oprand]" value="+" >';
	newTD4.innerHTML = '<input size="5" type="text" name="product_accessory['+g+'][accessory_price]" value="1">';
	newTD5.innerHTML = '<input type="text" name="product_accessory['+g+'][ordering]" size="5" value="" class="text_area" style="text-align: center" />';
//	newTD6.innerHTML = '<input value="1" class="button" type="checkbox" name="product_accessory['+g+'][setdefault_selected]">';
	newTD7.innerHTML = '<input value="Remove" onclick="javascript:deleteRow_accessory(this,0,0,0);" class="button" type="button" />';

	newTR.appendChild (newTD1);
	newTR.appendChild (newTD2);
	newTR.appendChild (newTD3);
	newTR.appendChild (newTD4);
	newTR.appendChild (newTD5);
//	newTR.appendChild (newTD6);
	newTR.appendChild (newTD7);
	tBody.appendChild(newTR);
	f++;

	//modalpopup();
}
function deleteRow(r) {
	if(window.confirm("Are you sure you want to delete field value?"))
	{
	var i=r.parentNode.parentNode.rowIndex;
	document.getElementById('extra_table').deleteRow(i);
	}
	}

var gh=0;
var h=1;
var or = 1;

function addNewRow_attribute(tableRef){
	//var g=parseInt(document.getElementById("attribute_table").value) + parseInt(gh);
	if(gh == 0)
		gh=document.getElementById("total_table").value;
	total_g = h-1;
//	total_g=document.getElementById("total_g").value;
	var p=0;
	var tit=document.getElementById("atitle").innerHTML;
	var ord = document.getElementById("aordering").innerHTML;
	var adselected = document.getElementById("adselected").innerHTML;
	var areq=document.getElementById("atitlerequired").innerHTML;
	var apublished=document.getElementById("atitlepublished").innerHTML;
	var sub_areq=document.getElementById("sub_atitlerequired").innerHTML;
	var sub_multi=document.getElementById("sub_multiselected").innerHTML;
	var showpropertytitlespan = document.getElementById("showpropertytitlespan").innerHTML;
	var spn_allow_multiple=document.getElementById("spn_allow_multiple_selection").innerHTML;
	var spn_hide_price=document.getElementById("spn_hide_attribute_price").innerHTML;
	var spn_display_type=document.getElementById("spn_display_type").innerHTML;
	var prop=document.getElementById("aproperty").innerHTML;
	var pri=document.getElementById("aprice").innerHTML;
	var new_attrib=document.getElementById("new_attribute").innerHTML;
	var delete_attri =document.getElementById("delete_attribute").innerHTML;
	delete_attri = delete_attri.trim();
	var new_prop=document.getElementById("new_property").innerHTML;
	var new_sub_prop=document.getElementById("new_sub_property").innerHTML;
	var img=document.getElementById("aimage").innerHTML;

	var myTable = document.getElementById(tableRef);

	var tBody = myTable.getElementsByTagName('tbody')[0];

	var newTable 	= document.createElement('table');
	newTable.setAttribute("width","100%");
	newTable.setAttribute("border","0");
	newTable.setAttribute("cellspacing","0");
	newTable.setAttribute("cellpadding","0");
	newTable.setAttribute("id","mainattributetable"+gh);
	var newTR0 	= document.createElement('tr');
	var newTD0 	= document.createElement('td');

	var subnewTable0	= document.createElement('table');
	subnewTable0.setAttribute("class","blue_area");
	subnewTable0.setAttribute("width","100%");
	subnewTable0.setAttribute("border","0");
	subnewTable0.setAttribute("cellspacing","0");
	subnewTable0.setAttribute("cellpadding","0");

	var subnewTR0 	= document.createElement('tr');
	var subnewTD0 	= document.createElement('td');


	var newTR = document.createElement('tr');
	var newTD = document.createElement('td');
	//newTD.setAttribute("width","11%");
	newTD.setAttribute("align","left");
	var newTD1 = document.createElement('td');
	newTD1.setAttribute("class","td2");
	newTD1.setAttribute("align","right");

	var newTD2 = document.createElement('td');
	newTD2.setAttribute("class","td3");
	newTD2.setAttribute("align","right");


	var newTD4 = document.createElement('td');
	newTD4.setAttribute("align","right");
	newTD4.setAttribute("class","td12");



	var newTD6 = document.createElement('td');
	newTD6.setAttribute("align","right");
	newTD6.setAttribute("class","td5");

	var newTD7 = document.createElement('td');
	newTD7.setAttribute("align","right");
	newTD7.setAttribute("class","td6");


	var newTD8 = document.createElement('td');
	newTD8.setAttribute("class","td7");
	newTD8.setAttribute("align","right");


	newTD.setAttribute("class","red_blue_blue td1");

	newTD.innerHTML = "<img onclick='showhidearrow(\"attribute_table_pro\", \""+gh+"\")' id='arrowimg"+gh+"' class='arrowimg' src='../administrator/components/com_redshop/assets/images/arrow.png' alt='img'>" + tit;
	table_pr="property_table"+gh;
	newTD1.innerHTML = "<input type='hidden' class='text_area'  size='22' value='0' name='attribute["+gh+"][id]' ><input type='text' class='text_area input_t1' size='22' name='attribute["+gh+"][name]'  >";

	newTD2.innerHTML = ord+":<input type='text_area input_t4' name='attribute["+gh+"][ordering]' size='2' value='"+gh+"' ><input type='hidden' name='attribute["+gh+"][tmpordering]' size='3' value='"+gh+"' >";
	newTD4.innerHTML = areq+":<input type='checkbox'  name='attribute["+gh+"][required]' value='1' >";

	newTD6.innerHTML = apublished+":<input type='checkbox' checked='checked' class='text_area' size='55' name='attribute["+gh+"][published]' value='1' >";

	newTD7.innerHTML = "";
//	newTD7.innerHTML = "<a href=\"javascript:addNewRow_attribute('"+tableRef+"')\">"+new_attrib+"</a>&nbsp;|&nbsp;<a href=\"javascript:addproperty('"+table_pr+"','"+gh+"')\">"+new_prop+"</a>";
	newTD8.innerHTML = '<input value="'+delete_attri+'" onclick="javascript:deleteRow_attribute(\'mainattributetable'+gh+'\',\''+tableRef+'\',\''+table_pr+'\')" class="btn_attribute" type="button" style="float:right;"/>';


	//newTR.appendChild (newTDImage);
	newTR.appendChild (newTD);
	newTR.appendChild (newTD1);
	newTR.appendChild (newTD2);
	//newTR.appendChild (newTD3);
	newTR.appendChild (newTD4);
	//newTR.appendChild (newTD5);
	newTR.appendChild (newTD6);
	newTR.appendChild (newTD7);
	newTR.appendChild (newTD8);


	subnewTable0.appendChild (newTR);
	subnewTD0.appendChild (subnewTable0);
	subnewTR0.appendChild (subnewTD0);

	newTable.appendChild (subnewTR0);
	newTD0.appendChild (newTable);
	newTR0.appendChild (newTD0);
	tBody.appendChild(newTR0);


	var subnewTable0_0	= document.createElement('table');


	subnewTable0_0.setAttribute("class","grey_area");
	subnewTable0_0.setAttribute("width","100%");
	subnewTable0_0.setAttribute("border","0");
	subnewTable0_0.setAttribute("cellspacing","0");
	subnewTable0_0.setAttribute("cellpadding","0");
	subnewTable0_0.setAttribute("id","attribute_table_pro" + gh);

	var subnewTR0_0 	= document.createElement('tr');
	var subnewTD0_0 	= document.createElement('td');


	var newTR_0 = document.createElement('tr');

	var newTD_multi_0 = document.createElement('td');
	var newTD_multi_1 = document.createElement('td');
	var newTD_multi_2 = document.createElement('td');
	var newTD_multi_3 = document.createElement('td');
	var newTD_multi_4 = document.createElement('td');
	var newTD_multi_5 = document.createElement('td');
	var newTD_multi_7 = document.createElement('td');
	var newTD_multi_8 = document.createElement('td');
	var newTD_multi_9 = document.createElement('td');
	var newTD_multi_10 = document.createElement('td');

	newTD_multi_0.setAttribute("class","td1");

	newTD_multi_1.setAttribute("class","td2");
	newTD_multi_1.setAttribute("align","right");

	newTD_multi_2.setAttribute("class","td3");

	newTD_multi_3.setAttribute("class","td4");
	//newTD_multi_4.setAttribute("width","4%");
	//newTD_multi_5.setAttribute("width","8%");


	newTD_multi_7.setAttribute("class","td5");
	newTD_multi_7.setAttribute("align","right");
//	newTD_multi_8.setAttribute("width","4%");


	newTD_multi_9.setAttribute("class","td6");
	newTD_multi_9.setAttribute("align","right");

	newTD_multi_10.setAttribute("class","td7");




// 	var newTD_hide_price = document.createElement('td');

	//newTD_hide_price.setAttribute("colSpan","4");
//	newTD_multi_0.innerHTML = "<a href=\"javascript:addNewRow_attribute('"+tableRef+"')\">"+new_attrib+"</a>&nbsp;|&nbsp;<a href=\"javascript:addproperty('"+table_pr+"','"+gh+"')\">"+new_prop+"</a>";
	newTD_multi_0.innerHTML = "<a class='btn_attribute' href=\"javascript:addproperty('"+table_pr+"','"+gh+"')\">+ Add "+new_prop+"</a>";
	newTD_multi_1.innerHTML = spn_allow_multiple+": <input type='checkbox' size='5' name='attribute['"+gh+"'][allow_multiple_selection]' >";
	newTD_multi_7.innerHTML = spn_hide_price+': <input type="checkbox" size="5" name="attribute['+gh+'][hide_attribute_price]" >';
	newTD_multi_9.innerHTML = spn_display_type+': <select name="attribute['+gh+'][display_type]"><option value="dropdown">Dropdown List</option><option value="radio">Radio Button</option></select>';
	//newTD_multi_1.innerHTML = '<input type="checkbox" size="5" name="attribute['+gh+'][allow_multiple_selection]" >';
	//newTD_multi_1.innerHTML = '<input type="checkbox" size="5" name="attribute['+gh+'][allow_multiple_selection]" >';
	newTD_multi_3.innerHTML = "&nbsp;";
	newTD_multi_10.innerHTML = "&nbsp;";

	newTR_0.appendChild (newTD_multi_0);
	newTR_0.appendChild (newTD_multi_1);
	newTR_0.appendChild (newTD_multi_2);
	newTR_0.appendChild (newTD_multi_3);
	//newTR_0.appendChild (newTD_multi_4);
	//newTR_0.appendChild (newTD_multi_5);
	newTR_0.appendChild (newTD_multi_7);
	//newTR_0.appendChild (newTD_multi_8);
	newTR_0.appendChild (newTD_multi_9);
	newTR_0.appendChild (newTD_multi_10);

	//newTR_0.appendChild (newTD_hide_price);

	//tBody.appendChild(newTR_0);

	subnewTable0_0.appendChild (newTR_0);
	subnewTD0_0.appendChild (subnewTable0_0);
	subnewTR0_0.appendChild (subnewTD0_0);

	newTable.appendChild (subnewTR0_0);
	newTD0.appendChild (newTable);
	newTR0.appendChild (newTD0);
	tBody.appendChild(newTR0);


	var subnewTable1	= document.createElement('table');
	subnewTable1.setAttribute("class","");
	subnewTable1.setAttribute("cellpadding","0");
	subnewTable1.setAttribute("border","0");
	subnewTable1.setAttribute("cellspacing","0");
	var subnewTbody1 = document.createElement('tbody');

	subnewTable1.setAttribute("width","100%");
	var subnewTR1 	= document.createElement('tr');
	var subnewTD1 	= document.createElement('td');
	subnewTD1.setAttribute("colspan","12");

	var newTR1 = document.createElement('tr');
	var newTD1 = document.createElement('td');

	//newTD1.setAttribute("colSpan","5");


	newTD1.innerHTML = '<table  class="grey_solid_area"  width="100%" cellpadding="0" border="0" cellspacing="0" id="property_table'+gh+'">'

	  +'<tr class="attr_tbody" id="attr_tbody'+gh+p+'">'
	  +'<td>'
	  +'<table class="attribute_value" width="100%" cellpadding="0" border="0" cellspacing="0" id="attribute_table'+gh+p+'"><tr><td class="red_blue_blue td1"><img class="arrowimg" onclick="showhidearrow(\'attribute_parameter_tr\', \''+gh+p+'\'); showhidearrow(\'sub_property\', \''+gh+p+'\')" id="arrowimg'+gh+p+'" src="../administrator/components/com_redshop/assets/images/arrow.png" alt="img"/>'
	  +prop+'</td><td class="td2"  align="right"><input type="text" class="text_area input_t1" name="attribute['+gh+'][property]['+total_g+'][name]" ></td><td class="td3" align="right">'+ord+':<input type="text" class="text_area input_t4" name="attribute['+gh+'][property]['+total_g+'][order]" size="3" value="0" ></td><td align="right" class="td4">'+adselected+':<input type="checkbox" name="attribute['+gh+'][property]['+total_g+'][default_sel]" \'><input type="hidden" name="attribute['+gh+'][property]['+total_g+'][default_sel]" id="hdnpropdselected'+gh+p+'"><input type="hidden" name="propsub_attdselected['+gh+'][value][]" id="hdnpropsub_attdselected'+gh+p+'" ></td>'
	  +'<td class="td5" align="right">'+pri+'<input type="text" class="text_area input_t3" size="2" name="attribute['+gh+'][property]['+total_g+'][oprand]" id="oprand'+gh+'0" style="text-align: center;" maxlength="1" value="+" onchange="javascript:oprand_check(this);" >'
	  +'&nbsp;<input type="text" class="text_area input_t2" name="attribute['+gh+'][property]['+total_g+'][price]" id="price" ></td><td class="td6">&nbsp;</td>'

	  +'<td class="td7"><div class="repon"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="up_image"><tr>'

	  +'<td class="td1" style="padding-right: 10px;">&nbsp;</td>'
	  +'<td class="td2" align="left"><div class="button2-left"><div class="image"><a class="modal" id="modal'+gh+'0" title="Image" href="index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid='+gh+'0&layout=thumbs" rel="{handler: \'iframe\', size: {x: 900, y: 500}}"></a></div></div> <input type="file" size="20" name="attribute_'+gh+'_property_'+total_g+'_image"  /><input type="hidden" class="text_area" size="12" name="attribute['+gh+'][property]['+total_g+'][imagetmp]"  /><input type="hidden"  name="attribute['+gh+'][property]['+total_g+'][mainImage]" id="propmainImage'+gh+'0" value=""></td>'
	  +'<td class="td3">&nbsp;</td>'
	  +'<td class="td4"><img id="propertyImage'+gh+'0" src="" style="display: none;" /></td>'

	  +'<td class="td5" align="right" style="padding-right: 25px;"><span>Published:&nbsp;</span><input type="checkbox" class="text_area" size="55" name="attribute['+gh+'][property]['+total_g+'][published]" checked="checked"  value="1"><span>&nbsp;Extra Field:&nbsp;</span><input type="input" class="text_area" size="8" name="attribute['+gh+'][property]['+total_g+'][extra_field]" value=""><div class="remove_attr"><input value="Delete" onclick="javascript:deleteRow_property(\'property_table'+gh+p+'\',\'property_table'+gh+'\',\'sub_attribute_table'+gh+p+'\',\''+gh+p+'\')" class="btn_attribute_remove" type="button" /><input type="hidden" name="attribute['+gh+'][property]['+total_g+'][property_id] value="0" ></div></td>'
	  +'<tr></table></div></td>'

	  +'</tr></table></td></tr>'

	  +'<tr class="attribute_parameter_tr" id="attribute_parameter_tr'+gh+p+'" style="display:none"><td><table width="100%" border="0" cellpadding="0" cellspacing="0" >'
	  +'<tr>'
	  +'<td class="red_blue_blue td1"><span style="padding-left: 10px;">Attribute parameter</span></td>'
	  +'<td class="td2" align="right"><input type="text" value="" class="text_area input_t1" name="attribute['+gh+'][property]['+total_g+'][subproperty][title]"></td>'
	  +'<td class="td3"></td>'
	  +'<td class="td4" align="right">Required: <input type="checkbox" onchange="javascript:if(this.checked){document.getElementById(&quot;hdnpropsub_attdselected12&quot;).value=1;}else{document.getElementById(&quot;hdnpropsub_attdselected12&quot;).value=0;}" name="attribute['+gh+'][property]['+total_g+'][req_sub_att]"><input type="hidden" id="hdnpropsub_attdselected12" name="propsub_attdselected[1][value][]"></td>'
	  +'<td class="td5" align="right">Multiselect: <input type="checkbox" onchange="javascript:if(this.checked){document.getElementById(&quot;hdnpropsub_multiselected12&quot;).value=1;}else{document.getElementById(&quot;hdnpropsub_multiselected12&quot;).value=0;}" name="attribute['+gh+'][property]['+total_g+'][multi_sub_att]"><input type="hidden" id="hdnpropsub_multiselected12" name="propsub_multiselected[1][value][]"></td>'
	  +'<td class="td6" align="right">Display Type <select name="attribute['+gh+'][property]['+total_g+'][setdisplay_type]"><option value="dropdown">Dropdown List</option><option value="radio">Radio Button</option></select></td>'
	  +'<td class="td7">&nbsp;</td>'
	  +'</tr>'
	  +'</table></tr>'

	  +'<tr class="sub_property" id="sub_property'+gh+p+'"><td style="padding: 0px;"><table width="100%" border="0" cellpadding="0" cellspacing="0"  id="sub_property_table'+gh+p+'">'
	  +'<tr><td colspan="12" class="td1" style="border-right: 1px solid #CCCCCC;" valign="top"><a href="javascript: addsubproperty(\'sub_attribute_table'+gh+'0\',\''+gh+'\',\'0\')" class="btn_attribute">+ '+new_sub_prop+'</a>'
	  +'</td><td><table width="100%" border="0" cellpadding="0"  cellspacing="0" id="sub_attribute_table'+gh+'0" class="sub_attribute_table"><tr style="display:none;"><td></td></tr></table></td></tr></table></td></tr>'
	 /* +'<tr>'
	  +'<td><a href="javascript: addsubproperty(\'sub_attribute_table'+gh+'0\','+gh+',\'0\')"><span id="new_sub_property">'+new_sub_prop+'</span></a></td>'
	  +'<td colspan="5"><table class="adminform"  border="0" cellpadding="2" cellspacing="2" id="sub_attribute_table'+gh+'0"><tr><td><div style="display:none;" id="showhidetitle'+gh+'0"><span id="showpropertytitlespan">'+showpropertytitlespan+'</span><input type="text" name="attribute['+gh+'][property]['+total_g+'][subproperty][title]" size="15" value="" ><span>'+sub_areq+'</span><input type="checkbox" name="attribute['+gh+'][property]['+total_g+'][req_sub_att]" /><span>'+sub_multi+'</span><input type="checkbox" name="attribute['+gh+'][property]['+total_g+'][multi_sub_att]" /><span>'+spn_display_type+'</span>'
	  +'<select name="attribute['+gh+'][property]['+total_g+'][setdisplay_type]"><option value="dropdown">Dropdown List</option><option value="radio">Radio Button</option></select></div></td></tr></table></td></tr>'*/
	  +'</td></tr></table>';



	/*newTD1.innerHTML = +'<table width="100%" border="0" cellpadding="0" cellspacing="0" >'
		+'<tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="attribute_parameter">'
		+'<td class="red_blue_blue" width="11%">Attribute parameter</td>'
		+'<td  width="9%"><input type="text" name="attribute['+rh+'][property]['+h+'][subproperty][title]" size="22" value="" >'
		+'<td align="right" width="1%">Required:</td>'
		+'<td width="4%" align="left"><input type="checkbox" name="attribute['+rh+'][property]['+h+'][req_sub_att]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=0;}\'><input type="hidden" name="propsub_attdselected['+rh+'][value][]" id="hdnpropsub_attdselected'+rh+or+'" >'
		+'<td width="4%" align="right">Multiselect:</td>'
		+'<td width="6%"><input type="checkbox" name="attribute['+rh+'][property]['+h+'][multi_sub_att]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropsub_multiselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropsub_multiselected'+rh+or+'").value=0;}\'><input type="hidden" name="propsub_multiselected['+rh+'][value][]" id="hdnpropsub_multiselected'+rh+or+'" ></td>'
		+'<td width="7%" align="left">'+spn_display_type+':</td>'
		+'<td width="43%"></span><select name="attribute['+rh+'][property]['+h+'][setdisplay_type]"><option value="dropdown">Dropdown List</option><option value="radio">Radio Button</option></select>'
		+'</td></tr></table></td></tr>'
		+'</table>';*/

	///<input value="Remove" onclick="javascript:deleteRow_property(\'property_table'+gh+'\',\'\',\'\')" class="button" type="button" /><input type="hidden" name="attribute['+gh+'][property]['+total_g+'][property_id] value="0" ><a href="javascript: addsubproperty(\'sub_attribute_table'+gh+'0\','+gh+',\'0\')" class="btn_attribute">'+new_sub_prop+'</a>

	newTR1.appendChild (newTD1);
	tBody.appendChild(newTR1);



	subnewTbody1.appendChild (newTR1);
	subnewTable1.appendChild (subnewTbody1);
	subnewTD1.appendChild (subnewTable1);
	subnewTR1.appendChild (subnewTD1);

	subnewTable0_0.appendChild (subnewTR1);
	newTD0.appendChild (newTable);
	newTR0.appendChild (newTD0);
	tBody.appendChild  (newTR0);
	//SqueezeBox.initialize({});
	/*$$('a.modal-button').each(function(el) {
		el.addEvent('click', function(e) {
			new Event(e).stop();
			SqueezeBox.fromElement(el);
		});
	});*/
	/*var el = document.getElementById('modal'+gh+'0');
	el.addEvent('click', function(e) {
		new Event(e).stop();
		SqueezeBox.fromElement(el);
	});*/
	//addproperty(table_pr,gh);
	modalpopup('modal'+gh+'0');
	gh++;
}


function addproperty(tableRef,rh){
	if(h == 1)
		h=document.getElementById("total_g").value;
	or = h;

	var prop=document.getElementById("aproperty").innerHTML;
	var pri=document.getElementById("aprice").innerHTML;
	var adselected = document.getElementById("adselected").innerHTML;
	var sub_areq=document.getElementById("sub_atitlerequired").innerHTML;
	var sub_multi=document.getElementById("sub_multiselected").innerHTML;
	var showpropertytitlespan = document.getElementById("showpropertytitlespan").innerHTML;
	var ord = document.getElementById("aordering").innerHTML;
	var new_attrib=document.getElementById("new_attribute").innerHTML;

	var delete_attri =document.getElementById("delete_attribute").innerHTML;
	var new_prop=document.getElementById("new_property").innerHTML;
	var new_sub_prop=document.getElementById("new_sub_property").innerHTML;
	var img=document.getElementById("aimage").innerHTML;
	var spn_display_type=document.getElementById("spn_display_type").innerHTML;

	var myTable = document.getElementById(tableRef);
	var tBody = myTable.getElementsByTagName('tbody')[0];
	var newTR = document.createElement('tr');
	newTR.setAttribute("class","attr_tbody");
	newTR.setAttribute("id","attr_tbody" + rh + h);

	var newTR1 = document.createElement('tr');
	newTR1.setAttribute("id","attribute_parameter_tr" + rh + h);
	newTR1.setAttribute("class","attribute_parameter_tr");
	newTR1.setAttribute("style","display:none");


	var newTR2 = document.createElement('tr');
	newTR2.setAttribute("id","sub_property" + rh + h);
	newTR2.setAttribute("class","sub_property");

	var newTD = document.createElement('td');
	var newTD1 = document.createElement('td');
	var newTD2 = document.createElement('td');
	newTD2.setAttribute("style","padding:0px");


	var newTD3 = document.createElement('td');
	var newTD4 = document.createElement('td');
	var newTD5 = document.createElement('td');

	var newTD6 = document.createElement('td');
	var newTD7 = document.createElement('td');
	var newTD8 = document.createElement('td');
	var newTD9 = document.createElement('td');
	//var newTD10 = document.createElement('td');
	//var newTD11 = document.createElement('td');

	newTD.innerHTML = '<table id="attribute_table'+h+'" class="attribute_value">'
			 +'<tr>'
			 +'<td class="red_blue_blue td1"><img  class="arrowimg" onclick="showhidearrow(\'attribute_parameter_tr\', \''+rh+h+'\'); showhidearrow(\'sub_property\', \''+rh+h+'\')" id="arrowimg'+rh+h+'" src="../administrator/components/com_redshop/assets/images/arrow.png" alt="img"/>'
			 + prop + '</td>'
			 +'<td class="td2" align="right"><input type="text" size="22" class="text_area input_t1"  name="attribute['+rh+'][property]['+h+'][name]" ><input type="hidden" name="attribute['+rh+'][property]['+h+'][property_id]" value="0" ></td>'
			+'<td class="td3" align="right">'+ord+': '
			+'<input type="text" class="text_area input_t4" name="attribute['+rh+'][property]['+h+'][order]" size="2" value="'+or+'" ></td>'
			+'<td class="td4" align="right">'+adselected+': '
			+'<input type="checkbox" name="attribute['+rh+'][property]['+h+'][default_sel]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropdselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropdselected'+rh+or+'").value=0;}\'><input type="hidden" name="attribute['+rh+'][property]['+h+'][propselected]" id="hdnpropdselected'+rh+or+'" ></td>'
			+'<td class="td5" align="right">'+pri+': '
			+'<input type="text" class="text_area input_t3" size="2" name="attribute['+rh+'][property]['+h+'][oprand]" style="text-align: center;" id="oprand'+rh+h+'" value="+" maxlength="1" onchange="javascript:oprand_check(this);" >&nbsp;'
			+'<input type="text" size="8" class="text_area input_t2" name="attribute['+rh+'][property]['+h+'][price]"  ></td>'
			+'<td class="td6"></td>'
			+'<td class="td7"><div class="repon"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="up_image"><tr>'
			+'<td class="td1" style="padding-right: 10px;">&nbsp;</td>'
			+'<td class="td2" align="left"><div class="button2-left"><div class="image"><a class="modal" id="modal'+rh+or+'" title="Image" href="index.php?tmpl=component&option=com_redshop&view=media&fsec=property&fid='+rh+or+'&layout=thumbs" rel="{handler: \'iframe\', size: {x: 900, y: 500}}"></a></div></div><input type="hidden"  name="attribute['+rh+'][property]['+h+'][mainImage]" id="propmainImage'+rh+or+'" value=""><input type="hidden" class="text_area" size="12" name="imagetmp['+rh+'][value][]"  /><input type="file" size="20" name="attribute_'+rh+'_property_'+h+'_image"  ></td>'
			+'<td class="td3">&nbsp;</td>'
			+'<td class="td4"><img id="propertyImage'+rh+or+'" src="" style="display: none;" /></td>'
			+'<td class="td5" align="right" style="padding-right: 25px;"><span>Published:&nbsp;</span><input type="checkbox" class="text_area" size="55" name="attribute['+rh+'][property]['+h+'][published]" checked="checked" value="1"><span>&nbsp;Extra Field:&nbsp;</span><input type="input" class="text_area" size="8" name="attribute['+rh+'][property]['+h+'][extra_field]" value=""><div class="remove_attr"><input value="Delete" onclick="javascript:deleteRow_property(\'attribute_table'+h+'\',\''+tableRef+'\',\'sub_attribute_table'+rh+h+'\', \''+rh+h+'\')" class="btn_attribute_remove" type="button" /></div></td>'
			+'<tr></table></div></td>'
			+ '</tr><table>';


	newTD1.innerHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" id="attribute_parameter'+h+'" >'
			+'<tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="attribute_parameter">'
			+'<td class="red_blue_blue td1"><span style="padding-left: 10px;">Attribute parameter</span></td>'
			+'<td class="td2" align="right"><input class="text_area input_t1" type="text" name="attribute['+rh+'][property]['+h+'][subproperty][title]" size="22" value="" >'
			+'<td class="td3"></td><td align="right" class="td4">Required: <input type="checkbox" name="attribute['+rh+'][property]['+h+'][req_sub_att]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=0;}\'><input type="hidden" name="propsub_attdselected['+rh+'][value][]" id="hdnpropsub_attdselected'+rh+or+'" >'
			+'<td class="td5" align="right">Multiselect Sub Attribute: <input type="checkbox" name="attribute['+rh+'][property]['+h+'][multi_sub_att]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropsub_multiselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropsub_multiselected'+rh+or+'").value=0;}\'><input type="hidden" name="propsub_multiselected['+rh+'][value][]" id="hdnpropsub_multiselected'+rh+or+'" ></td>'
			+'<td class="td6" align="right">'+spn_display_type+': <select name="attribute['+rh+'][property]['+h+'][setdisplay_type]"><option value="dropdown">Dropdown List</option><option value="radio">Radio Button</option></select>'
			+'<td class="td7">&nbsp;</td>'
			+'</td></tr></table></td></tr>'
			+'</table>';

	newTD2.innerHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0"  id="sub_property_table'+h+'">'
		+'<tr><td colspan="12" class="td1" style="border-right: 1px solid #CCCCCC;" valign="top"><a href="javascript:addsubproperty(\'sub_attribute_table'+rh+h+'\',\''+rh+'\',\''+h+'\')" class="btn_attribute" align="right">+ Add sub property</a>'
		+'</td><td style="padding:0px;"><table width="100%" border="0" cellpadding="0"  cellspacing="0" id="sub_attribute_table'+rh+h+'" class="sub_attribute_table"><tr style="display:none;"><td></td></tr></table></td></tr></table>';




	//newTD.innerHTML = newTD.innerHTML + '<td>'+sub_areq+'<td>';
	//newTD.innerHTML = newTD.innerHTML + '<td><input type="checkbox" name="attribute['+rh+'][property]['+h+'][req_sub_att]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=0;}\'><input type="hidden" name="propsub_attdselected['+rh+'][value][]" id="hdnpropsub_attdselected'+rh+or+'" >';



	//newTD.innerHTML = '<td width="2%"><img src="../administrator/components/com_redshop/assets/images/arrow.png" alt="img"></td>'+prop;
	//newTD1.innerHTML ='<input type="text" class="text_area" size="40" name="attribute['+rh+'][property]['+h+'][name]" ><input type="hidden" name="attribute['+rh+'][property]['+h+'][property_id]" value="0" ><span>'+ord+'</span><input type="text" name="attribute['+rh+'][property]['+h+'][order]" size="3" value="'+or+'" ><span>'+adselected+'</span><input type="checkbox" name="attribute['+rh+'][property]['+h+'][default_sel]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropdselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropdselected'+rh+or+'").value=0;}\'><input type="hidden" name="attribute['+rh+'][property]['+h+'][propselected]" id="hdnpropdselected'+rh+or+'" ><span>'+sub_areq+'</span><input type="checkbox" name="attribute['+rh+'][property]['+h+'][req_sub_att]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=0;}\'><input type="hidden" name="propsub_attdselected['+rh+'][value][]" id="hdnpropsub_attdselected'+rh+or+'" >';
	//newTD2.innerHTML = pri;
	//newTD3.innerHTML = '<input type="text" class="text_area" size="12" name="attribute['+rh+'][property]['+h+'][price]"  >';
	//newTD5.innerHTML = '<input type="text" class="text_area" size="2" name="attribute['+rh+'][property]['+h+'][oprand]" style="text-align: center;" id="oprand'+rh+h+'" value="+" maxlength="1" onchange="javascript:oprand_check(this);" > ';
	//newTD4.innerHTML = '<img id="propertyImage'+rh+or+'" src="" style="display: none;" /><span><div class="button2-left"><div class="image"><a class="modal" id="modal'+rh+or+'" title="Image" href="index3.php?option=com_redshop&view=media&fsec=property&fid='+rh+or+'&layout=thumbs" rel="{handler: \'iframe\', size: {x: 900, y: 500}}"></a></div></div></span><input type="hidden"  name="attribute['+rh+'][property]['+h+'][mainImage]" id="propmainImage'+rh+or+'" value=""><input type="hidden" class="text_area" size="12" name="imagetmp['+rh+'][value][]"  /><input type="file" class="text_area" size="12" name="attribute_'+rh+'_property_'+h+'_image"  > '+"&nbsp;<input value='Remove' onclick=\"javascript:deleteRow_property(this,'"+tableRef+"','sub_attribute_table"+rh+h+"')\" class='button' type='button' /> ";

	//newTD11.innerHTML = '<a href="javascript:addsubproperty(\'sub_attribute_table'+rh+h+'\','+rh+','+h+')"><span id="new_sub_property">'+new_sub_prop+'</span></a>';
	//newTD6.setAttribute("colSpan","5");
	//newTD6.innerHTML = '<table class="adminform" width="100%" border="0" cellpadding="2" cellspacing="2" id="sub_attribute_table'+rh+h+'"><tr><td><div style="display:none;" id="showhidetitle'+rh+h+'"><span id="showpropertytitlespan">'+showpropertytitlespan+'</span><input type="text" name="attribute['+rh+'][property]['+h+'][subproperty][title]" size="15" value="" ><span>'+sub_areq+'</span><input type="checkbox" name="attribute['+rh+'][property]['+h+'][req_sub_att]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropsub_attdselected'+rh+or+'").value=0;}\'><input type="hidden" name="propsub_attdselected['+rh+'][value][]" id="hdnpropsub_attdselected'+rh+or+'" ><span>'+sub_multi+'</span><input type="checkbox" name="attribute['+rh+'][property]['+h+'][multi_sub_att]" onChange=\'javascript:if(this.checked){document.getElementById("hdnpropsub_multiselected'+rh+or+'").value=1;}else{document.getElementById("hdnpropsub_multiselected'+rh+or+'").value=0;}\'><input type="hidden" name="propsub_multiselected['+rh+'][value][]" id="hdnpropsub_multiselected'+rh+or+'" ><span>'+spn_display_type+'</span><select name="attribute['+rh+'][property]['+h+'][setdisplay_type]"><option value="dropdown">Dropdown List</option><option value="radio">Radio Button</option></select></div></td></tr></table>';




	newTR.appendChild (newTD);
	newTR1.appendChild (newTD1);
	newTR2.appendChild (newTD2);

	tBody.appendChild(newTR);
	tBody.appendChild(newTR1);
	tBody.appendChild(newTR2);

	/*var el = document.getElementById('modal'+rh+or);
	el.addEvent('click', function(e) {
		new Event(e).stop();
		SqueezeBox.fromElement(el);
	});*/

	modalpopup('modal'+rh+or);

	h++;
	or++;

	document.getElementById("total_g").value = h;

}

var orde=0;
var sp=0;
function showpropertytitle(divid){
	document.getElementById("showsubproperty"+divid).style.display="";
}
function addsubproperty(tableRef,rh,sh){

	document.getElementById("attribute_parameter_tr"+rh+sh).style.display="";

	if(sp == 0)
		sp=document.getElementById("total_z").value;
	if(document.getElementById("showhidetitle"+rh+sh))
		document.getElementById("showhidetitle"+rh+sh).style.display="block";
	var prop=document.getElementById("aproperty").innerHTML;
	var ord = document.getElementById("aordering").innerHTML;
	var adselected = document.getElementById("adselected").innerHTML;
	var pri=document.getElementById("aprice").innerHTML;
	var new_attrib=document.getElementById("new_attribute").innerHTML;
	var delete_attri =document.getElementById("delete_attribute").innerHTML;
	var new_prop=document.getElementById("new_property").innerHTML;
	var img=document.getElementById("aimage").innerHTML;

	var myTable = document.getElementById(tableRef);

	var tBody = myTable.getElementsByTagName('tbody')[0];
	var newTR = document.createElement('tr');

	var newTD = document.createElement('td');
	newTD.setAttribute("style","padding:0px;");

	var newTD1 = document.createElement('td');
	var newTD2 = document.createElement('td');
	var newTD3 = document.createElement('td');
	var newTD5 = document.createElement('td');
//	attribute['+rh+'][property]['+h+'][oprand]

	//<table border="0" align="left" width="100%" cellspacing="0" cellpadding="0" class="subattribute" id="sub_attribute_table3" style="border-bottom: 1px solid #CCCCCC;">
	newTD.innerHTML = '<table id="sub'+rh+orde+'" border="0" align="left" width="100%" cellspacing="0" cellpadding="0" class="subattribute" style="border-bottom: 1px solid #CCCCCC;">'
			 +'<tr valign="top">'
			 +'<td class="td2" align="right">Parameter: '
			 +'<input type="text" class="text_area input_t2" size="8" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][name]" ></td>'
			 +'<td class="td3" align="right">'+ord+': '
			 +'<input type="text" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][order]" size="2" value="'+orde+'" class="text_area input_t4"></td>'
			 +'<td align="right" class="td4">'+adselected+': '
			 +'<input type="checkbox" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][chk_propdselected]" ><input type="hidden" name="propdselected['+rh+'][subvalue]['+sh+'][]" id="hdnsubpropdselected'+rh+sh+orde+'" ><input type="hidden" name="property_id['+rh+'][subvalue]['+sh+'][]" value="0" ></td>'
			 +'<td class="td5" align="right">'+pri+': '
			 +'<input type="text" class="text_area input_t3" size="1" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][oprand]" style="text-align: center;" id="oprand'+rh+sh+'" value="+" maxlength="1" onchange="javascript:oprand_check(this);" >&nbsp;<input class="input_t2" type="text" size="8" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][price]" value="0"></td>'
			 +'<td class="td6" align="right"></td>'
			 +'<td align="left" class="td7">'
			 +'<div class="repon"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="up_image"><tr>'

			 +'<td class="td1" style="padding-right: 10px;">&nbsp;</td>'
			 +'<td class="td2" align="left"><span><div class="button2-left"><div class="image"><a class="modal" id="submodal'+rh+sp+'" title="Image" href="index.php?tmpl=component&option=com_redshop&view=media&fsec=subproperty&fid='+rh+sp+'&layout=thumbs" rel="{handler: \'iframe\', size: {x: 900, y: 500}}"></a></div></div></span><input type="hidden"  name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][mainImage]" id="subpropmainImage'+rh+sp+'" value=""><input type="hidden" class="text_area" size="12" name="imagetmp['+rh+'][subvalue]['+sh+'][]"  /><input type="file" size="20" name="attribute_'+rh+'_property_'+sh+'_subproperty_'+sp+'_image"  ></td>'
			 +'<td class="td3">&nbsp;</td>'
			 +'<td class="td4"><img id="subpropertyImage'+rh+sp+'" src="" style="display: none;" /></td>'

			 +'<td class="td5" align="right"  style="padding-right: 25px;"><span>Published:&nbsp;</span><input type="checkbox" class="text_area" size="55" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][published]" checked="checked" value="1"><span>Extra Field:&nbsp;</span><input type="text" class="text_area" size="8" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][extra_field]" value=""><div class="remove_attr"><input value="Delete" onclick="javascript:deleteRow_subproperty(\'sub'+rh+orde+'\',\'sub'+rh+sp+'\')" class="btn_attribute_remove" type="button" /></div></td>'
			 +'</tr></table></div>'
			 +'</td>'
			+ '</tr></table>';

	//newTD.innerHTML = '<input type="text" class="text_area" size="40" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][name]" ><span>'+ord+'</span><input type="text" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][order]" size="3" value="'+orde+'" ><span>'+adselected+'</span><input type="checkbox" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][chk_propdselected]" ><input type="hidden" name="propdselected['+rh+'][subvalue]['+sh+'][]" id="hdnsubpropdselected'+rh+sh+orde+'" ><input type="hidden" name="property_id['+rh+'][subvalue]['+sh+'][]" value="0" >';
	//newTD1.innerHTML =pri;
	//newTD2.innerHTML = '<input type="text" class="text_area" size="12" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][price]"  > ';
	//newTD3.innerHTML = '<input type="text" class="text_area" size="2" name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][oprand]" style="text-align: center;" id="oprand'+rh+sh+'" value="+" maxlength="1" onchange="javascript:oprand_check(this);" >';
	//newTD5.innerHTML = '<img id="subpropertyImage'+rh+orde+'" src="" style="display: none;" /><span><div class="button2-left"><div class="image"><a class="modal" id="submodal'+rh+orde+'" title="Image" href="index3.php?option=com_redshop&view=media&fsec=subproperty&fid='+rh+orde+'&layout=thumbs" rel="{handler: \'iframe\', size: {x: 900, y: 500}}"></a></div></div></span><input type="hidden"  name="attribute['+rh+'][property]['+sh+'][subproperty]['+sp+'][mainImage]" id="subpropmainImage'+rh+orde+'" value=""><input type="hidden" class="text_area" size="12" name="imagetmp['+rh+'][subvalue]['+sh+'][]"  /><input type="file" class="text_area" size="12" name="attribute_'+rh+'_property_'+sh+'_subproperty_'+sp+'_image"  > '+"&nbsp;<input value='Remove' onclick=\"javascript:deleteRow_subproperty(this,'"+tableRef+"')\" class='button' type='button' /> ";

	newTR.appendChild (newTD);
	//newTR.appendChild (newTD1);
	//newTR.appendChild (newTD3);
	//newTR.appendChild (newTD2);
	//newTR.appendChild (newTD5);

	tBody.appendChild(newTR);

	/*var el = document.getElementById('submodal'+rh+orde);
	el.addEvent('click', function(e) {
		new Event(e).stop();
		SqueezeBox.fromElement(el);
	});*/

	modalpopup('submodal'+rh+sp);

	sp++;
	orde++;
	//h++;


}

function deleteRow_attribute(r,tableref,table_pr,attr_id) {

	if(table_pr)
	{
		for(var i = document.getElementById(table_pr).rows.length; i > 0;i--)
		{
		document.getElementById(table_pr).deleteRow(i -1);
		}
	}
	if(r)
	{
		for(var i = document.getElementById(r).rows.length; i > 0;i--)
		{
		document.getElementById(r).deleteRow(i -1);
		}
	}
    return;
	var i=r.parentNode.parentNode.rowIndex;
	document.getElementById(tableref).deleteRow(i+2);
	document.getElementById(tableref).deleteRow(i+1);
	document.getElementById(tableref).deleteRow(i);
	if(document.getElementById('hdn_del_attribute'))
	{
		if(document.getElementById('hdn_del_attribute').value != "")
			document.getElementById('hdn_del_attribute').value += ","+ attr_id;
		else
			document.getElementById('hdn_del_attribute').value = attr_id;
	}
}


function deleteRow_property(r,tableref,tableref_sub,porp_id) {

	//var delRow = r.parentNode.parentNode.rowIndex;
	//document.getElementById(r.id).deleteRow(delRow);
	//document.getElementById("attribute_parameter" + porp_id).deleteRow(delRow);
	//sub_property


	/*if(tableref_sub)
	{
		for(var i = document.getElementById(tableref_sub).rows.length; i > 0;i--)
		{
			document.getElementById(tableref_sub).deleteRow(i -1);
		}
	}

	if(r)
	{
		for(var i = document.getElementById(r).rows.length; i > 0;i--)
		{
			document.getElementById(r).deleteRow(i -1);
		}
	}*/

	if(document.getElementById("attr_tbody" + porp_id) != undefined)
	{
		var node = document.getElementById("attr_tbody" + porp_id);
		node.parentNode.removeChild(node);

	}

	if(document.getElementById("attribute_parameter_tr" + porp_id) != undefined)
	{
		var node = document.getElementById("attribute_parameter_tr" + porp_id);
		node.parentNode.removeChild(node);

	}


	if(document.getElementById("sub_property" + porp_id) != undefined)
	{
		var node = document.getElementById("sub_property" + porp_id);
		node.parentNode.removeChild(node);
	}

	document.getElementById("total_table").value = document.getElementById("total_table").value - 1;


    return;
	var delRow = r.parentNode.parentNode;
	var tbl = delRow.parentNode.parentNode;

	var rIndex = delRow.sectionRowIndex;
	tbl.deleteRow(rIndex+1);
	tbl.deleteRow(rIndex);
		if(document.getElementById('hdn_del_property'))
		{
			if(document.getElementById('hdn_del_property').value != "")
				document.getElementById('hdn_del_property').value += ","+ porp_id;
			else
				document.getElementById('hdn_del_property').value = porp_id;
		}



	}

function deleteRow_subproperty(r,tableref,subprop_id) {

	if(document.getElementById(r) != undefined)
	{
		var node = document.getElementById(r);
		node.parentNode.removeChild(node);
	}

	/*if(document.getElementById(r))
	{
		for(var i = document.getElementById(r).rows.length; i > 0;i--)
		{
			document.getElementById(r).deleteRow(i -1);
		}
	}*/





	return;

	var i=r.parentNode.parentNode.parentNode.rowIndex;

	document.getElementById(tableref).deleteRow(i);
	if(document.getElementById('hdn_del_subproperty'))
	{
		if(document.getElementById('hdn_del_subproperty').value != "")
			document.getElementById('hdn_del_subproperty').value += ","+ subprop_id;
		else
			document.getElementById('hdn_del_subproperty').value = subprop_id;
	}
}


function deleteRow_container(r)
{
	var i=r.parentNode.parentNode.rowIndex;
	document.getElementById('container_table').deleteRow(i);
}
function deleteRow_accessory(r, accessory_id,category_id, child_product_id)
{
	if(window.confirm("Are you sure you want to delete?"))
	{
		var i=r.parentNode.parentNode.rowIndex;
		document.getElementById('accessory_table').deleteRow(i);
		if(accessory_id!=0)
		{
			delete_accessory(accessory_id,category_id, child_product_id);
		}
	}
}
//******************************** Add New Poperty Element ******************


function addNewRowOfProp(tableRef){


	var myTable = document.getElementById(tableRef);
	var tBody = myTable.getElementsByTagName('tbody')[0];
	var newTR = document.createElement('tr');
	var newTD = document.createElement('td');
	var newTD1 = document.createElement('td');

	newTD.innerHTML = '';
	newTD1.innerHTML = '<input type="file" name="property_sub_img[]" value="" id="property_sub_img[]" ><input value="Delete" onclick="deleteRowOfProp(this)" class="button" type="button" />';
	newTR.appendChild (newTD);
	newTR.appendChild (newTD1);
	tBody.appendChild(newTR);

	//modalpopup();

}


//******************************** Delete Poperty Element ******************

function deleteRowOfProp(r) {
	var i=r.parentNode.parentNode.rowIndex;
	document.getElementById('admintable').deleteRow(i);
}

function delete_accessory(accessory_id,category_id,child_product_id)
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
		{}
	}
	var linktocontroller = "index.php?option=com_redshop&view=product_detail&task=removeaccesory";
	linktocontroller = linktocontroller + "&accessory_id="+accessory_id;
	linktocontroller = linktocontroller + "&category_id="+category_id;
	linktocontroller = linktocontroller + "&child_product_id="+child_product_id;
	xmlhttp.open("GET",linktocontroller,true);
	xmlhttp.send(null);
}

function showhidearrow(n, k)
{
	var pathimages = document.getElementById("pathimages").innerHTML;

	if(n == "attribute_parameter_tr")
	{
		var a = document.getElementById("sub_attribute_table" + k).getElementsByTagName('tbody')[0].rows;

		var x = 0;
		for(var i=0; i < a.length; i++)
		{
			if(strpos(a[i].getAttribute("style"), 'none') == false)
			{

				x++;
			}
		}


		if(x == 0)
		{
			return;
		}
	}

	if(document.getElementById(n + k).style.display == "none")
	{
		document.getElementById(n + k).style.display = "";
		document.getElementById("arrowimg" + k).src = pathimages + "arrow.png";
	}
	else
	{
		document.getElementById(n + k).style.display = "none";
		document.getElementById("arrowimg" + k).src = pathimages + "arrow_d.png";


	}
}


function modalpopup(n)
{

	SqueezeBox.initialize();

	SqueezeBox.assign($$('#' + n), {
		parse: 'rel'
	});
}
