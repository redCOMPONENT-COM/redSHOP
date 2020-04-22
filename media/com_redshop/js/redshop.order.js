function showUserDetail() {
    xmlhttp = GetXmlHttpObject();
    if (xmlhttp == null) {
        alert("Your browser does not support XMLHTTP!");
        return;
    }
    var val = '';
    if (document.getElementById("user_id")) {
        val = '&user_id=' + document.getElementById("user_id").value;
    }
    if (document.getElementById("guestuser0")) {
        document.getElementById("guestuser0").checked = true;
    }

    var url = 'index.php?tmpl=component&option=com_redshop&view=addorder_detail';
    url = url + val;
    url = url + "&sid=" + Math.random() + "&ajaxtask=getuser";

    xmlhttp.onreadystatechange = stateChanged;
    xmlhttp.open("GET", url, true);
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.send(null);
}

function showquotationUserDetail() {
    xmlhttp = GetXmlHttpObject();
    if (xmlhttp == null) {
        alert("Your browser does not support XMLHTTP!");
        return;
    }
    var val = '';
    if (document.getElementById("user_id")) {
        val = '&user_id=' + document.getElementById("user_id").value;
    }
    if (document.getElementById("guestuser0")) {
        document.getElementById("guestuser0").checked = true;
    }

    var url = 'index.php?tmpl=component&option=com_redshop&view=addquotation_detail';
    url = url + val;
    url = url + "&sid=" + Math.random() + "&ajaxtask=getuser";

    xmlhttp.onreadystatechange = stateChanged;
    xmlhttp.open("GET", url, true);
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.send(null);
}

function showGuestDetail() {
    xmlhttp = GetXmlHttpObject();
    if (xmlhttp == null) {
        alert("Your browser does not support XMLHTTP!");
        return;
    }
    var val = '';
    if (document.getElementById("user_id")) {
        val = '&user_id=0&uid=add';
    }

    var url = 'index.php?tmpl=component&option=com_redshop&view=addorder_detail';
    url = url + val;
    url = url + "&sid=" + Math.random() + "&ajaxtask=getuser";

    xmlhttp.onreadystatechange = stateChanged;
    xmlhttp.open("GET", url, true);
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.send(null);
}

function stateChanged() {
    if (xmlhttp.readyState == 4) {
        document.getElementById("userinforesult").innerHTML = xmlhttp.responseText;
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

function getShippinginfo(ship_id, is_company) {
    if (document.getElementById("billisship").checked) {
        if (document.getElementById('order_shipping_div')) {
            document.getElementById('order_shipping_div').style.display = "none";
        }
        if (document.getElementById('shipp_users_info_id')) {
            document.getElementById('shipp_users_info_id').disabled = true;
        }
    } else {
        if (document.getElementById('shipp_users_info_id')) {
            document.getElementById('shipp_users_info_id').disabled = false;
            document.getElementById('shipp_users_info_id').value = ship_id;
        }
        xmlhttp = GetXmlHttpObject();
        if (xmlhttp == null) {
            alert("Your browser does not support XMLHTTP!");
            return;
        }
        var val = '';
        if (document.getElementById("user_id")) {
            val = '&user_id=' + document.getElementById("user_id").value;
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (document.getElementById('order_shipping_div')) {
                    document.getElementById('order_shipping_div').innerHTML = xmlhttp.responseText;
                    document.getElementById('order_shipping_div').style.display = "block";
                }
            }
        }
        var linktocontroller = "index.php?option=com_redshop&view=addorder_detail&task=changeshippingaddress" + val;
        linktocontroller = linktocontroller + '&shippingadd_id=' + ship_id + '&is_company=' + is_company;

        xmlhttp.open("GET", linktocontroller, true);
        xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xmlhttp.send(null);
    }
}
