var tbhttp;
var xmlhttp;

function setExportSectionType() {
    var section = "";
    if (document.getElementById('section_type')) {
        section = document.getElementById('section_type').value;
    }
    if (document.getElementById('tblProductType')) {
        document.getElementById('tblProductType').style.display = "none";
    }
    if (document.getElementById('tblOrderType')) {
        document.getElementById('tblOrderType').style.display = "none";
    }
    if (document.getElementById('prdelement_name')) {
        document.getElementById('prdelement_name').style.display = "none";
    }
    if (document.getElementById('ordelement_name')) {
        document.getElementById('ordelement_name').style.display = "none";
    }
    if (document.getElementById('tdelement_name')) {
        document.getElementById('tdelement_name').style.display = "none";
    }
    if (document.getElementById('trStockdetail')) {
        document.getElementById('trStockdetail').style.display = "none";
    }
    if (document.getElementById('trExtrafield')) {
        document.getElementById('trExtrafield').style.display = "none";
    }
    if (document.getElementById('trBillingdetail')) {
        document.getElementById('trBillingdetail').style.display = "none";
    }
    if (document.getElementById('trShippingdetail')) {
        document.getElementById('trShippingdetail').style.display = "none";
    }
    if (document.getElementById('trOrderitem')) {
        document.getElementById('trOrderitem').style.display = "none";
    }

    switch (section) {
        case "product":
            if (document.getElementById('tblProductType')) {
                document.getElementById('tblProductType').style.display = "";
            }
            if (document.getElementById('tblOrderType')) {
                document.getElementById('tblOrderType').style.display = "none";
            }
            if (document.getElementById('prdelement_name')) {
                document.getElementById('prdelement_name').style.display = "";
            }
            if (document.getElementById('ordelement_name')) {
                document.getElementById('ordelement_name').style.display = "none";
            }
            if (document.getElementById('tdelement_name')) {
                document.getElementById('tdelement_name').style.display = "";
            }
            if (document.getElementById('trStockdetail')) {
                document.getElementById('trStockdetail').style.display = "";
            }
            if (document.getElementById('trExtrafield')) {
                document.getElementById('trExtrafield').style.display = "";
            }
            if (document.getElementById('trBillingdetail')) {
                document.getElementById('trBillingdetail').style.display = "none";
            }
            if (document.getElementById('trShippingdetail')) {
                document.getElementById('trShippingdetail').style.display = "none";
            }
            if (document.getElementById('trOrderitem')) {
                document.getElementById('trOrderitem').style.display = "none";
            }
            break;
        case "order":
            if (document.getElementById('tblProductType')) {
                document.getElementById('tblProductType').style.display = "none";
            }
            if (document.getElementById('tblOrderType')) {
                document.getElementById('tblOrderType').style.display = "";
            }
            if (document.getElementById('prdelement_name')) {
                document.getElementById('prdelement_name').style.display = "none";
            }
            if (document.getElementById('ordelement_name')) {
                document.getElementById('ordelement_name').style.display = "";
            }
            if (document.getElementById('tdelement_name')) {
                document.getElementById('tdelement_name').style.display = "";
            }
            if (document.getElementById('trStockdetail')) {
                document.getElementById('trStockdetail').style.display = "none";
            }
            if (document.getElementById('trExtrafield')) {
                document.getElementById('trExtrafield').style.display = "none";
            }
            if (document.getElementById('trBillingdetail')) {
                document.getElementById('trBillingdetail').style.display = "";
            }
            if (document.getElementById('trShippingdetail')) {
                document.getElementById('trShippingdetail').style.display = "";
            }
            if (document.getElementById('trOrderitem')) {
                document.getElementById('trOrderitem').style.display = "";
            }
            break;
    }
}

function setImportSectionType() {
    xmlhttp = GetXmlHttpObject();
    if (xmlhttp == null) {
        alert("Your browser does not support XMLHTTP!");
        return;
    }
    var preurl = '';
    if (document.getElementById('xmlimport_id')) {
        preurl = preurl + "&cid[]=" + document.getElementById('xmlimport_id').value;
    }
    if (document.getElementById('section_type')) {
        preurl = preurl + "&section_type=" + document.getElementById('section_type').value;
    }
    var url = 'index.php?tmpl=component&option=com_redshop&view=xmlimport_detail&task=edit';
    url = url + preurl + "&sid=" + Math.random();

    xmlhttp.onreadystatechange = stateChanged;
    xmlhttp.open("GET", url, true);
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.send(null);
}

function stateChanged() {
    if (xmlhttp.readyState == 4) {
        document.getElementById("adminresult").innerHTML = xmlhttp.responseText;
    }
}

function GetXmlHttpObject() {
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        return new XMLHttpRequest();
    }
    if (window.ActiveXObject) {
        // code for IE6, IE5
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
    return null;
}

var f = 1;

function addNewRow(tableRef) {
    var totalrow = parseInt(document.getElementById("iparray").value) + parseInt(f);

    var myTable = document.getElementById(tableRef);
    var tBody = myTable.getElementsByTagName('tbody')[0];
    var newTR = document.createElement('tr');
    var newTD = document.createElement('td');
    var newTD1 = document.createElement('td');

    newTD.innerHTML = document.getElementById("tdIPText").value;
    newTD1.innerHTML = '<input type="text" name="access_ipaddress[]" value="" id="access_ipaddress">&nbsp;<input type="hidden" name="xmlexport_ip_id[]" value="0" /><input value="Delete" onclick="deleteRow(this,0)" class="button" type="button" />';
    newTR.appendChild(newTD);
    newTR.appendChild(newTD1);
    tBody.appendChild(newTR);
    f++;
}

var IpId = 0;

function deleteRow(r, IpId) {
    if (window.confirm("Are you sure you want to delete?")) {
        var i = r.parentNode.parentNode.rowIndex;
        document.getElementById('tblaccessIp').deleteRow(i);
        var purl = "";
        if (IpId != 0) {
            purl = purl + "&xmlexport_ip_id=" + IpId;
        }

        var url = 'index.php?option=com_redshop&view=xmlexport_detail&task=removeIpAddress';
        url = url + purl;

        tbhttp = GetXmlHttpObject();
        tbhttp.open("GET", url, true);
        tbhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        tbhttp.send(null);
    }
}
