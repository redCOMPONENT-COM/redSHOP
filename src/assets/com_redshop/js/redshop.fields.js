if (!String.prototype.trim) {
    // Code for trim.
    String.prototype.trim = function() {
        return this.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    };
}

function strpos(haystack, needle, offset) {
    var i = (haystack + '').indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
}

var f = 1;

function addNewRow(tableRef) {
    var g = parseInt(document.getElementById("total_extra").value) + parseInt(f);
    var myTable = document.getElementById(tableRef);
    var tBody = myTable.getElementsByTagName('tbody')[0];
    var newTR = document.createElement('tr');
    var newTD = document.createElement('td');
    var newTD1 = document.createElement('td');
    var newTD2 = document.createElement('td');
    var newTD3 = document.createElement('td');

    var fieldtp = "text";
    fieldname = "extra_name[]";
    var fieldtype = document.getElementById("jform_type").value;

    if (fieldtype == 11 || fieldtype == 13) {
        fieldtp = "file";
        fieldname = "extra_name_file[]";
    }

    newTD.innerHTML = '<input id="extra_name' + g + '" type="' + fieldtp + '" name="' + fieldname + '" value="field_temp_opt_' + g + '" id="' + fieldname + '" class="form-control" />';
    newTD1.innerHTML = '<input type="text" name="extra_value[]" value="" id="extra_value' + g + '"  class="form-control" /><input type="hidden" name="value_id[]" id="value_id' + g + '" />';
    newTD2.innerHTML = '&nbsp;';
    newTD3.innerHTML = '<input class="btn btn-danger" value="Delete" onclick="deleteRow(this)" type="button" />';
    newTR.appendChild(newTD);
    newTR.appendChild(newTD1);
    newTR.appendChild(newTD2);
    newTR.appendChild(newTD3);
    tBody.appendChild(newTR);
    f++;
}

var f = 1;

function addNewRowcustom(field_name) {

    var tableRef = 'extra_table';
    var g = parseInt(document.getElementById("total_extra").value) + parseInt(f);
    var myTable = document.getElementById(tableRef);
    var tBody = myTable.getElementsByTagName('tbody')[0];
    var newTR = document.createElement('tr');
    var newTD = document.createElement('td');
    var newTD1 = document.createElement('td');
    var newTD2 = document.createElement('td');

    var fieldtp = "text";
    fieldname = field_name.name + "_extra_name[]";

    newTD.innerHTML = '<input type="' + fieldtp + '" name="' + fieldname + '" value="" id="' + fieldname + '">';
    newTD1.innerHTML = '<input type="hidden" name="value_id[]" id="value_id[]">  <input value="Delete" onclick="deleteRow(this)" class="button" type="button" />';
    newTD2.innerHTML = '&nbsp;';
    newTR.appendChild(newTD);
    newTR.appendChild(newTD1);
    newTR.appendChild(newTD2);
    tBody.appendChild(newTR);
    f++;

}

function create_table_data(data, volume, id) {
    name = data;

    var g = parseInt(document.getElementById("total_extra").value) + parseInt(f);
    var myTable = document.getElementById('container_table');
    var tBody = myTable.getElementsByTagName('tbody')[0];
    var newTR = document.createElement('tr');

    var newTD1 = document.createElement('td');
    var newTD2 = document.createElement('td');
    var newTD3 = document.createElement('td');
    var newTD4 = document.createElement('td');
    var cdata = 0;

    if (document.getElementById("porder1").checked)
        cdata = 1

    newTD1.innerHTML = name;
    newTD2.innerHTML = '<input size="5" type="text" name="quantity[]" value="1" onchange="changeM3(' + id + ',this.value)" id="quantity[]"><input type="hidden" name="container_product[]" value="' + id + '" id="container_product[]"><input type="hidden" value="' + cdata + '" name="container_porder[]" >';
    newTD3.innerHTML = '<div align="center"><input size="5" type="text" name="volume[]" id="volume' + id + '" value="' + volume + '" readonly="readonly" /></div>';
    newTD4.innerHTML = "<input value=\"X\" onclick=\"javascript:deleteRow_container(this);\" class=\"button\" type=\"button\" />";

    newTR.appendChild(newTD1);
    newTR.appendChild(newTD2);
    newTR.appendChild(newTD3);
    newTR.appendChild(newTD4);
    tBody.appendChild(newTR);
    f++;
}

function changeM3(id, qty, volume) {
    document.getElementById('volume' + id).value = qty * volume;
}

function create_table_accessory(data, id, price) {
    name = data;
    var g = parseInt(document.getElementById("total_accessory").value) + parseInt(f);
    var myTable = document.getElementById('accessory_table');
    var tBody = myTable.getElementsByTagName('tbody')[0];
    var newTR = document.createElement('tr');

    var newTD1 = document.createElement('td');
    var newTD2 = document.createElement('td');
    var newTD3 = document.createElement('td');
    var newTD4 = document.createElement('td');
    var newTD5 = document.createElement('td');
    var newTD7 = document.createElement('td');

    newTD1.innerHTML = name + '<input type="hidden" class="childProductAccessory" value="' + id + '" name="product_accessory[' + g + '][child_product_id]"><input type="hidden" value="0" name="product_accessory[' + g + '][accessory_id]">';
    newTD2.innerHTML = price;
    newTD3.innerHTML = '<input size="1" maxlength="1" onchange="javascript:oprand_check(this);" class="text_area input-small text-center" type="text" name="product_accessory[' + g + '][oprand]" value="+" >';
    newTD4.innerHTML = '<input size="5" type="text" name="product_accessory[' + g + '][accessory_price]" class="text_area input-small text-center" value="0">';
    newTD5.innerHTML = '<input type="text" name="product_accessory[' + g + '][ordering]" size="5" value="" class="text_area input-small text-center" style="text-align: center" />';
    newTD7.innerHTML = '<input value="' + Joomla.JText._('COM_REDSHOP_DELETE') + '" onclick="javascript:deleteRow_accessory(this,0,0,0);" class="button btn btn-danger" type="button" />';

    newTR.appendChild(newTD1);
    newTR.appendChild(newTD2);
    newTR.appendChild(newTD3);
    newTR.appendChild(newTD4);
    newTR.appendChild(newTD5);
    newTR.appendChild(newTD7);
    tBody.appendChild(newTR);
    f++;
}

function deleteRow(r) {
    if (window.confirm("Are you sure you want to delete field value?")) {
        var i = r.parentNode.parentNode.rowIndex;

        if (document.querySelectorAll("#extra_table input[name*='extra_value']").length == 2)
        {
            document.getElementById('extra_table').deleteRow(i);
            document.querySelectorAll("#extra_table input.button")[0].style.display = "none";
        }
        else
        {
            document.getElementById('extra_table').deleteRow(i);
        }
    }
}

function deleteRow_container(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById('container_table').deleteRow(i);
}

function deleteRow_accessory(r, accessory_id, category_id, child_product_id) {
    if (window.confirm("Are you sure you want to delete?")) {
        var i = r.parentNode.parentNode.rowIndex;
        document.getElementById('accessory_table').deleteRow(i);
        if (accessory_id != 0) {
            delete_accessory(accessory_id, category_id, child_product_id);
        }
    }
}

/**
 * Add New Poperty Element
 */
function addNewRowOfProp(tableRef) {
    var myTable = document.getElementById(tableRef);
    var tBody = myTable.getElementsByTagName('tbody')[0];
    var newTR = document.createElement('tr');
    var newTD = document.createElement('td');
    var newTD1 = document.createElement('td');

    newTD.innerHTML = '';
    newTD1.innerHTML = '<input type="file" name="property_sub_img[]" value="" id="property_sub_img[]" ><input value="Delete" onclick="deleteRowOfProp(this)" class="button" type="button" />';
    newTR.appendChild(newTD);
    newTR.appendChild(newTD1);
    tBody.appendChild(newTR);
}

/**
 * Delete Poperty Element
 */
function deleteRowOfProp(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById('admintable').deleteRow(i);
}

function delete_accessory(accessory_id, category_id, child_product_id) {
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {}
    }
    var linktocontroller = "index.php?option=com_redshop&view=product_detail&task=removeaccesory";
    linktocontroller = linktocontroller + "&accessory_id=" + accessory_id;
    linktocontroller = linktocontroller + "&category_id=" + category_id;
    linktocontroller = linktocontroller + "&child_product_id=" + child_product_id;
    xmlhttp.open("GET", linktocontroller, true);
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.send(null);
}
