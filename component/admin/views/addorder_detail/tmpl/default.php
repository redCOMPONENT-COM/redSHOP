<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.calendar');

$app       = JFactory::getApplication();
$redconfig = Redconfiguration::getInstance();

$model = $this->getModel('addorder_detail');

$billing   = $this->billing;
$shipping  = $this->shipping;
$isCompany = $billing->is_company;

if (!empty ($_SERVER['REMOTE_ADDR'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
} else {
    $ip = 'unknown';
}
$session = JFactory::getSession();
$session->set('ordertotal', 0);
$billisshipcheck = ($this->shipping->billisship) ? "checked" : "";
$shippingblock   = ($this->shipping->billisship) ? "none" : "";

if ($this->detail->user_id < 0) {
    $style          = "none";
    $create_account = 0;
} else {
    $style          = "block";
    $create_account = 1;
}
$allowCustomer = '';
$allowCompany  = '';
if ($isCompany == 1) {
    $allowCustomer = 'style="display:none;"';
} else {
    $allowCompany = 'style="display:none;"';
}
$err = JFactory::getApplication()->input->get('err', '');

$DEFAULT_QUANTITY = Redshop::getConfig()->get('DEFAULT_QUANTITY');

$username = $app->getUserState('com_redshop.addorder_detail.guestuser.username', null);

// Clear state
$app->setUserState('com_redshop.addorder_detail.guestuser.username', null);
?>
<script type="text/javascript">
    var xmlhttp;
    // Tweak by Ronni - Change var rowCount = 1; to = 0;
    var rowCount = 0;

    // Tweak by Ronni START - add product custom button
    var tmpRowCount = 9;
    function addNewproductRowCustom(tblid, productid) {
        var table = document.getElementById(tblid);
        if (rowCount > 0) {
            rowCount = table.rows.length;
        }

        rowCount++;
        var newTR = document.createElement('tr');
        var newTD = document.createElement('td');
        var newTD1 = document.createElement('td');
        var newTD2 = document.createElement('td');
        var newTD3 = document.createElement('td');
        var newTD4 = document.createElement('td');
        var newTD5 = document.createElement('td');
        var newTD6 = document.createElement('td');
        var newTD7 = document.createElement('td');
        var item = new Array();

      //newTD.innerHTML = '<img onclick="deleteOfflineProductRow(' + rowCount + ');" class="btn btn-danger" title="<?php echo JText::_('COM_REDSHOP_REMOVE_PRODUCT'); ?>" alt="<?php echo JText::_('X');?>">';
        newTD.innerHTML = '<input type="button" value="<?php echo JText::_('COM_REDSHOP_REMOVE_PRODUCT')?>" class="btn btn-danger" onclick="deleteOfflineProductRow(' + rowCount + '); return false;" />';
    
        newTD1.innerHTML = '<input type="text" name="product' + rowCount + '" id="product' + rowCount + '" value="0" /><div id="divAttproduct' + rowCount + '"></div><div id="divAccproduct' + rowCount + '"></div><div id="divUserFieldproduct' + rowCount + '"></div>';
        newTD2.innerHTML = '';
        newTD2.id = 'tdnoteproduct' + rowCount;
        newTD3.innerHTML = '<input type="text" name="prdexclpriceproduct' + rowCount + '" id="prdexclpriceproduct' + rowCount + '" onchange="changeCustomOfflinePriceBox(\'product' + rowCount + '\','+productid+');" value="0" size="10" >';
        newTD4.innerHTML = '<div id="prdtaxproduct' + rowCount + '"></div><input name="taxpriceproduct' + rowCount + '" id="taxpriceproduct' + rowCount + '" type="hidden" value="0" />';
        newTD4.align = 'right';
        newTD5.innerHTML = '<div id="prdpriceproduct' + rowCount + '"></div><input name="productpriceproduct' + rowCount + '" id="productpriceproduct' + rowCount + '" type="hidden" value="0" />';
        newTD5.align = 'right';
        newTD6.innerHTML = '<input type="text" name="quantityproduct' + rowCount + '" id="quantityproduct' + rowCount + '" onchange="changeCustomOfflineQuantityBox(\'product' + rowCount + '\','+productid+');" value="1" size="<?php echo $DEFAULT_QUANTITY;?>" maxlength="<?php echo $DEFAULT_QUANTITY;?>" >';
        newTD7.innerHTML = '<div id="tdtotalprdproduct' + rowCount + '"></div><input name="subpriceproduct' + rowCount + '" id="subpriceproduct' + rowCount + '" type="hidden" value="0" /><input type="hidden" name="main_priceproduct' + rowCount + '" id="main_priceproduct' + rowCount + '" value="0" /><input type="hidden" name="tmp_product_priceproduct' + rowCount + '" id="tmp_product_priceproduct' + rowCount + '" value="0"><input type="hidden" name="product_vatpriceproduct' + rowCount + '" id="product_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="tmp_product_vatpriceproduct' + rowCount + '" id="tmp_product_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="wrapper_dataproduct' + rowCount + '" id="wrapper_dataproduct' + rowCount + '" value="0"><input type="hidden" name="wrapper_vatpriceproduct' + rowCount + '" id="wrapper_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="accessory_dataproduct' + rowCount + '" id="accessory_dataproduct' + rowCount + '" value="0"><input type="hidden" name="acc_attribute_dataproduct' + rowCount + '" id="acc_attribute_dataproduct' + rowCount + '" value="0"><input type="hidden" name="acc_property_dataproduct' + rowCount + '" id="acc_property_dataproduct' + rowCount + '" value="0"><input type="hidden" name="acc_subproperty_dataproduct' + rowCount + '" id="acc_subproperty_dataproduct' + rowCount + '" value="0"><input type="hidden" name="accessory_priceproduct' + rowCount + '" id="accessory_priceproduct' + rowCount + '" value="0"><input type="hidden" name="accessory_vatpriceproduct' + rowCount + '" id="accessory_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="attribute_dataproduct' + rowCount + '" id="attribute_dataproduct' + rowCount + '" value="0"><input type="hidden" name="property_dataproduct' + rowCount + '" id="property_dataproduct' + rowCount + '" value="0"><input type="hidden" name="subproperty_dataproduct' + rowCount + '" id="subproperty_dataproduct' + rowCount + '" value="0"><input type="hidden" name="requiedAttributeproduct' + rowCount + '" id="requiedAttributeproduct' + rowCount + '" value="0">';
        newTD7.align = 'right';

        var item = document.getElementsByName('order_item');

        newTR.appendChild(newTD);
        newTR.appendChild(newTD1);
        newTR.appendChild(newTD2);
        newTR.appendChild(newTD3);
        newTR.appendChild(newTD4);
        newTR.appendChild(newTD5);
        newTR.appendChild(newTD6);
        newTR.appendChild(newTD7);

        newTR.id = 'trPrd' + rowCount;

        table.appendChild(newTR);
    //  var tmpRowCount = rowCount + 7;
        createJsonObject(rowCount,productid);
    //  setTimeout(function(){},5000);
        var newurl = "index.php?tmpl=component&option=com_redshop&view=addorder_detail&task=getProductName&productid=" + productid;
     
        jQuery.ajax({url: newurl, success: function(result){
            jQuery("#select2-chosen-"+tmpRowCount).text(result);
            tmpRowCount++;
        }});

        document.getElementById('product'+rowCount).value = productid;
    
        displayCustomProductDetailInfo('product'+rowCount, 0, productid);
    }
    
    function changeCustomOfflinePriceBox(unique_id, productid) {
        var prdexclprice = 0;
        if (document.getElementById("prdexclprice" + unique_id) && (trim(document.getElementById("prdexclprice" + unique_id).value) != "" && !isNaN(document.getElementById("prdexclprice" + unique_id).value))) {
            prdexclprice = document.getElementById("prdexclprice" + unique_id).value;
        }
        
        document.getElementById("prdexclprice" + unique_id).value = prdexclprice;
        if (document.getElementById("change_product_tmp_price" + unique_id)) {
            document.getElementById("change_product_tmp_price" + unique_id).value = prdexclprice;
        }
        
        displayCustomProductDetailInfo(unique_id, prdexclprice, productid);
    }

    function changeCustomOfflineQuantityBox(unique_id, productid) {
        var prdexclprice = 0;
        if (document.getElementById("main_price" + unique_id) && document.getElementById("product_vatprice" + unique_id)) {
            prdexclprice = parseFloat(document.getElementById("main_price" + unique_id).value) - parseFloat(document.getElementById("product_vatprice" + unique_id).value);
        }
        
        document.getElementById("prdexclprice" + unique_id).value = prdexclprice;
        if (document.getElementById("change_product_tmp_price" + unique_id)) {
            prdexclprice = document.getElementById("change_product_tmp_price" + unique_id).value;
        }
        displayCustomProductDetailInfo(unique_id, prdexclprice, productid);
    }

    function displayCustomProductDetailInfo(unique_id, newprice, productid) {
        xmlhttp = GetXmlHttpObject();

        if (xmlhttp == null) {
            alert("Your browser does not support XMLHTTP!");
            return;
        }

        var val = '';
        var quantity = 1;
        var product_id = 0;
        var user_id = 0;

        if (document.getElementById("user_id")) {
            user_id = document.getElementById("user_id").value;
        }

        if (document.getElementById(unique_id)) {
            product_id = document.getElementById(unique_id).value;
        }

        if (product_id == 0 || product_id == "") {
            return false;
        }

        product_id = productid;

        if (document.getElementById("quantity" + unique_id) && (trim(document.getElementById("quantity" + unique_id).value) != "" && !isNaN(document.getElementById("quantity" + unique_id).value))) {
            quantity = document.getElementById("quantity" + unique_id).value;
        }

        document.getElementById("quantity" + unique_id).value = quantity;

        var pval = '&product=' + product_id;
        pval = pval + '&quantity=' + quantity;
        pval = pval + '&user_id=' + user_id;
        pval = pval + '&unique_id=' + unique_id;
        pval = pval + '&newprice=' + newprice;

        if (document.getElementById('order_subtotal')) {
            pval = pval + '&ordertotal=' + document.getElementById("order_subtotal").value;
        }

        var url = "index.php?tmpl=component&option=com_redshop&view=order_detail&task=displayProductItemInfo";
        url = url + pval;
        url = url + "&pid=" + Math.random() + "&ajaxtask=getproduct&objid=" + unique_id;

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4) {
                document.getElementById("divCalc").innerHTML = xmlhttp.responseText;
                // hidden variable for quantity price change issue
                if (document.getElementById("change_product_tmp_price" + unique_id) && document.getElementById("change_product_tmp_price" + unique_id).value == '0') {
                    document.getElementById("change_product_tmp_price" + unique_id).value = document.getElementById("product_price_excl_vat").innerHTML;
                }

                if (document.getElementById("prdexclprice" + unique_id)) {
                    document.getElementById("prdexclprice" + unique_id).value = document.getElementById("product_price_excl_vat").innerHTML;
                }

                if (document.getElementById("taxprice" + unique_id)) {
                    document.getElementById("taxprice" + unique_id).value = document.getElementById("total_tax").innerHTML;
                }

                if (document.getElementById("prdtax" + unique_id)) {
                    document.getElementById("prdtax" + unique_id).innerHTML = number_format(document.getElementById("product_tax").innerHTML, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
                }

                if (document.getElementById("product_vatprice" + unique_id)) {
                    document.getElementById("product_vatprice" + unique_id).value = document.getElementById("product_tax").innerHTML;
                }

                if (document.getElementById("prdprice" + unique_id)) {
                    document.getElementById("prdprice" + unique_id).innerHTML = number_format(document.getElementById("product_price").innerHTML, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
                }

                if (document.getElementById("productprice" + unique_id)) {
                    document.getElementById("productprice" + unique_id).value = document.getElementById("product_price").innerHTML;
                }

                document.getElementById("subprice" + unique_id).value = document.getElementById("total_price").innerHTML;
                document.getElementById("tdtotalprd" + unique_id).innerHTML = number_format(document.getElementById("total_price").innerHTML, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));

                if (newprice == 0) {
                    document.getElementById("divAtt" + unique_id).innerHTML = document.getElementById("attblock").innerHTML;
                    document.getElementById("divAcc" + unique_id).innerHTML = document.getElementById("accessoryblock").innerHTML;
                    document.getElementById("divUserField" + unique_id).innerHTML = document.getElementById("productuserfield").innerHTML;
                    document.getElementById("tdnote" + unique_id).innerHTML = document.getElementById("noteblock").innerHTML;
                }

                if (document.getElementById("tmp_product_price" + unique_id)) {
                    document.getElementById("tmp_product_price" + unique_id).value = document.getElementById("product_price").innerHTML;
                }

                if (document.getElementById("main_price" + unique_id)) {
                    document.getElementById("main_price" + unique_id).value = document.getElementById("product_price").innerHTML;
                }

                calculateOfflineTotalPrice(unique_id);

                document.getElementById("divCalc").innerHTML = "";

                if (document.getElementById("tdShipping")) {
                    var ordertotal    = 0;
                    var ordersubtotal = 0;
                    
                    if (document.getElementById("order_total")) {
                        ordertotal = parseFloat(document.getElementById("order_total").value);
                    }
    
                    if (document.getElementById("order_subtotal")) {
                        ordersubtotal = parseFloat(document.getElementById("order_subtotal").value);
                    }
            
                    var prdArr = new Array();
                    var qntArr = new Array();
                    var j = 0;

                    for (i = 1; i <= rowCount; i++) {
                        if (document.getElementById("product" + i) && document.getElementById("product" + i).value != 0) {
                            prdArr[j] = document.getElementById("product" + i).value;
                        }

                        if (document.getElementById("quantityproduct" + i) && document.getElementById("quantityproduct" + i).value != 0) {
                            qntArr[j] = document.getElementById("quantityproduct" + i).value;
                        }

                        j++;
                    }

                    var shipp_users_info_id = 0;
                    var order_user_id = 0;

                    if (document.getElementById("shipp_users_info_id")) {
                        shipp_users_info_id = document.getElementById("shipp_users_info_id").value;
                    }

                    if (document.getElementById("user_id")) {
                        order_user_id = document.getElementById("user_id").value;
                    }

                    var newurl = "index.php?tmpl=component&option=com_redshop&view=addorder_detail&layout=productorderinfo&ordertotal=" + ordertotal + "&ordersubtotal=" + ordersubtotal + "&productarr=" + prdArr + "&qntarr=" + qntArr + "&shipp_users_info_id=" + shipp_users_info_id + "&order_user_id=" + order_user_id;

                    newxmlhttp = GetXmlHttpObject();
                    newxmlhttp.onreadystatechange = function() {
                        if (newxmlhttp.readyState == 4) {
                            document.getElementById("divCalc").innerHTML = newxmlhttp.responseText;
                            document.getElementById("tdShipping").innerHTML = document.getElementById("shippingblock").innerHTML;
                            if (document.getElementById("tdPayment")) {
                                document.getElementById("tdPayment").innerHTML = document.getElementById("paymentblock").innerHTML;
                            }
    
                            document.getElementById("divCalc").innerHTML = "";
                            calculateOfflineShipping();
                        }
                    }
                
                    newxmlhttp.open("GET", newurl, true);
                    newxmlhttp.send(null);
                }

                // load calendar setup
                calendarDefaultLoad();
            }
        }
        
        xmlhttp.open("GET", url, true);
        xmlhttp.send(null);
    }
    // Tweak by Ronni END : add product custom button

    function addNewproductRow(tblid) {
        var table = document.getElementById(tblid);

        //	var rowCount = table.rows.length;
        rowCount++;
        var newTR = document.createElement('tr');//table.insertRow(rowCount);

        var newTD = document.createElement('td');
        var newTD1 = document.createElement('td');
        var newTD2 = document.createElement('td');
        var newTD3 = document.createElement('td');
        var newTD4 = document.createElement('td');
        var newTD5 = document.createElement('td');
        var newTD6 = document.createElement('td');
        var newTD7 = document.createElement('td');
        var item = new Array();

        newTD.innerHTML = '<input type="button" value="<?php echo JText::_(
            'COM_REDSHOP_REMOVE_PRODUCT'
        )?>" class="btn btn-danger" onclick="deleteOfflineProductRow(' + rowCount + '); return false;" />';

        newTD1.innerHTML = '<input type="text" name="product' + rowCount + '" id="product' + rowCount + '" value="0" /><div id="divAttproduct' + rowCount + '"></div><div id="divAccproduct' + rowCount + '"></div><div id="divUserFieldproduct' + rowCount + '"></div>';
        newTD2.innerHTML = '';
        newTD2.id = 'tdnoteproduct' + rowCount;
        newTD3.innerHTML = '<input type="number" min="0" name="prdexclpriceproduct' + rowCount + '" id="prdexclpriceproduct' + rowCount + '" onchange="changeOfflinePriceBox(\'product' + rowCount + '\');" value="0" size="10" >';
        newTD4.innerHTML = '<div id="prdtaxproduct' + rowCount + '"></div><input name="taxpriceproduct' + rowCount + '" id="taxpriceproduct' + rowCount + '" type="hidden" value="0" />';
        newTD4.align = 'right';
        newTD5.innerHTML = '<div id="prdpriceproduct' + rowCount + '"></div><input name="productpriceproduct' + rowCount + '" id="productpriceproduct' + rowCount + '" type="hidden" value="0" />';
        newTD5.align = 'right';
        newTD6.innerHTML = '<input type="number" min="1" class="quantity" name="quantityproduct' + rowCount + '" id="quantityproduct' + rowCount + '" onchange="changeOfflineQuantityBox(\'product' + rowCount + '\');" value="1" size="<?php echo $DEFAULT_QUANTITY;?>" maxlength="<?php echo $DEFAULT_QUANTITY;?>" >';
        newTD7.innerHTML = '<div id="tdtotalprdproduct' + rowCount + '"></div><input name="subpriceproduct' + rowCount + '" id="subpriceproduct' + rowCount + '" type="hidden" value="0" /><input type="hidden" name="main_priceproduct' + rowCount + '" id="main_priceproduct' + rowCount + '" value="0" /><input type="hidden" name="tmp_product_priceproduct' + rowCount + '" id="tmp_product_priceproduct' + rowCount + '" value="0"><input type="hidden" name="product_vatpriceproduct' + rowCount + '" id="product_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="tmp_product_vatpriceproduct' + rowCount + '" id="tmp_product_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="wrapper_dataproduct' + rowCount + '" id="wrapper_dataproduct' + rowCount + '" value="0"><input type="hidden" name="wrapper_vatpriceproduct' + rowCount + '" id="wrapper_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="accessory_dataproduct' + rowCount + '" id="accessory_dataproduct' + rowCount + '" value="0"><input type="hidden" name="acc_attribute_dataproduct' + rowCount + '" id="acc_attribute_dataproduct' + rowCount + '" value="0"><input type="hidden" name="acc_property_dataproduct' + rowCount + '" id="acc_property_dataproduct' + rowCount + '" value="0"><input type="hidden" name="acc_subproperty_dataproduct' + rowCount + '" id="acc_subproperty_dataproduct' + rowCount + '" value="0"><input type="hidden" name="accessory_priceproduct' + rowCount + '" id="accessory_priceproduct' + rowCount + '" value="0"><input type="hidden" name="accessory_vatpriceproduct' + rowCount + '" id="accessory_vatpriceproduct' + rowCount + '" value="0"><input type="hidden" name="attribute_dataproduct' + rowCount + '" id="attribute_dataproduct' + rowCount + '" value="0"><input type="hidden" name="property_dataproduct' + rowCount + '" id="property_dataproduct' + rowCount + '" value="0"><input type="hidden" name="subproperty_dataproduct' + rowCount + '" id="subproperty_dataproduct' + rowCount + '" value="0"><input type="hidden" name="requiedAttributeproduct' + rowCount + '" id="requiedAttributeproduct' + rowCount + '" value="0">';
        newTD7.align = 'right';

        var item = document.getElementsByName('order_item');

        newTR.appendChild(newTD);
        newTR.appendChild(newTD1);
        newTR.appendChild(newTD2);
        newTR.appendChild(newTD3);
        newTR.appendChild(newTD4);
        newTR.appendChild(newTD5);
        newTR.appendChild(newTD6);
        newTR.appendChild(newTD7);

        newTR.id = 'trPrd' + rowCount;

        table.appendChild(newTR);

        createJsonObject(rowCount);
    }

    Joomla.submitbutton = function (pressbutton) {
        submitbutton(pressbutton);
    };

    submitbutton = function (pressbutton) {

        var form = document.adminForm;

        if (pressbutton == 'cancel') {
            <?php      $link = 'index.php?option=com_redshop&view=order';
            $link = RedshopHelperUtility::getSSLLink($link, 0);
            ?>
            window.location = '<?php echo $link;?>';
            return;

        }
        if (pressbutton == 'save' || pressbutton == 'save_without_sendmail') {
            if (form.user_id.value == 0) {
                alert("<?php echo JText::_('COM_REDSHOP_SELECT_USER');?>");
                return;
            }
            if (form.product1.value == 0) {
                alert("<?php echo JText::_('COM_REDSHOP_SELECT_PRODUCT');?>");
                return;
            }
            if (form.order_status.value == 0 || form.order_status.value == '') {
                alert("<?php echo JText::_('COM_REDSHOP_TABLE_ORDER_REDSHOP_INVALID_ORDER_STATUS');?>");
                return false;
            }
            if (form.shipping_rate_id) {
                if (form.shipping_rate_id.value == '' || form.shipping_rate_id.value == 0) {
                    alert("<?php echo JText::_('COM_REDSHOP_SELECT_SHIPPING');?>");
                    return;
                }
            } else {
                if (<?php echo Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE');?>) {
                    alert("<?php echo JText::_('COM_REDSHOP_SELECT_SHIPPING');?>");
                    return;
                }
            }
            if (validateExtrafield(form) == false) {
                return false;
            }
        }
        if (pressbutton == 'validateUserDetail') {
            validateUserDetail();
            return false;
        }
        submitform(pressbutton);
    }

    function validateUserDetail() {
        var form = document.adminForm;
        var rad_val = 0;

        var requiredPostCode = "<?php echo Redshop::getConfig()->getBool('REQUIRED_POSTAL_CODE') ?>";
        var requiredEanNumber = "<?php echo Redshop::getConfig()->getBool('REQUIRED_EAN_NUMBER')?>";
        var requiredAddress = "<?php echo Redshop::getConfig()->getBool('REQUIRED_ADDRESS') ?>";
        var requiredPhone = "<?php echo Redshop::getConfig()->getBool('REQUIRED_PHONE') ?>";

        for (var i = 0; i < form.guestuser.length; i++) {
            if (form.guestuser[i].checked && document.getElementById('users_info_id').value != 0) {
                rad_val = form.guestuser[i].value;
                break;
            }
        }

        if (form.firstname.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME')?>");
            form.firstname.focus();
            return false;
        }
        if (form.lastname.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME')?>");
            form.lastname.focus();
            return false;
        }
        if (form.address.value == '' && requiredAddress) {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS')?>");
            form.address.focus();
            return false;
        }
        if (form.zipcode.value == '' && requiredPostCode) {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ZIPCODE')?>");
            form.zipcode.focus();
            return false;
        }
        if (form.city.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_CITY')?>");
            form.city.focus();
            return false;
        }
        if (form.phone.value == '' && requiredPhone) {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_PHONE')?>");
            form.phone.focus();
            return false;
        }

        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if (form.email.value == '') {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS')?>");
            form.email.focus();
            return false;
        }
        var email = form.email.value;
        if (reg.test(email) == false) {
            alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_EMAIL_ADDRESS')?>");
            form.email.focus();
            return false;
        }
        form.user_email.value = form.email.value;

        if (rad_val == 1) {
            if (document.getElementById('username') && form.username.value == "") {
                alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME', true); ?>");
                form.username.focus();
                return false;
            }
        }
        if (rad_val == 1) {
            if (document.getElementById('password')) {
                if (form.password.value == '') {
                    alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_PASSWORD')?>");
                    form.password.focus();
                    return false;
                }

                if (((trim(form.password.value) != "") || (trim(form.password2.value) != "")) && (form.password.value != form.password2.value)) {
                    alert("<?php echo JText::_('COM_REDSHOP_PASSWORD_NOT_MATCH', true); ?>");
                    form.password2.focus();
                    return false;
                }
            }
        }
        <?php if(!Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS')) {?>
        if (!document.getElementById('billisship').checked) {
            if (form.firstname_ST.value == '') {
                alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME')?>");
                form.firstname_ST.focus();
                return false;
            }
            if (form.lastname_ST.value == '') {
                alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME')?>");
                form.lastname_ST.focus();
                return false;
            }
            if (form.address_ST.value == '') {
                alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS')?>");
                form.address_ST.focus();
                return false;
            }
            if (form.zipcode_ST.value == '') {
                alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ZIPCODE')?>");
                form.zipcode_ST.focus();
                return false;
            }
            if (form.city_ST.value == '') {
                alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_CITY')?>");
                form.city_ST.focus();
                return false;
            }
            if (form.phone_ST.value == '') {
                alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_PHONE')?>");
                form.phone_ST.focus();
                return false;
            }
        }
        <?php }?>
        if (validateExtrafield(form) == false) {
            return false;
        }
        submitform('guestuser');
    }
</script>

<?php if (!JFactory::getApplication()->input->getCmd('ajaxtask', '')): ?>
<form action="<?php echo Redshop\IO\Route::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-4">
            <?php echo JText::_('COM_REDSHOP_SELECT_USER_OR_ADD_NEW_USER_IN_BOTTOM_FIELDS'); ?>:
        </div>
        <div class="col-md-8">
            <?php
            $userDetail        = new stdClass;
            $userDetail->value = $this->detail->user_id;
            $userDetail->text  = $billing->firstname . ' ' . $billing->lastname;
            echo JHTML::_(
                'redshopselect.search',
                $userDetail,
                'user_id',
                array(
                    'select2.ajaxOptions' => array('typeField' => ', addreduser:1'),
                    'select2.options'     => array(
                        'events' => array(
                            'select2-selecting' => 'function(e) {
                                            document.getElementById(\'user_id\').value = e.object.id;
                                            showUserDetail();
                                            if (e.object.id){
                                                document.getElementById(\'trCreateAccount\').style.display = \'none\';
                                            }}'
                        )
                    )
                )
            );
            ?>
        </div>
    </div>
    <div id="trCreateAccount">
        <hr/>
        <div class="row">
            <div class="col-md-4">
                <?php echo JText::_('COM_REDSHOP_CREATE_ACCOUNT'); ?>:
            </div>
            <div class="col-md-8">
                <?php echo JHTML::_(
                    'select.booleanlist',
                    'guestuser',
                    'class="inputbox" onclick="createAccount(this.value);" ',
                    $create_account
                ); ?>
            </div>
        </div>
    </div>
    <table border="0" cellspacing="0" cellpadding="0" class="adminlist table">
        <tbody>
        <tr>
            <td id="userinforesult">
                <?php endif; ?>
                <table class="adminlist table">
                    <tbody>
                    <tr style="background-color: #cccccc">
                        <th><?php echo JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION'); ?></th>
                        <th><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFORMATION'); ?></th>
                    </tr>
                    <tr valign="top">
                        <td width="50%">
                            <table class="adminlist table" border="0">
                                <tr>
                                    <td width="30%" align="right"><?php echo JText::_('COM_REDSHOP_REGISTER_AS'); ?>:
                                    </td>
                                    <td><?php echo $this->lists['is_company']; ?></td>
                                </tr>
                                <tr id="trCompanyName" <?php echo $allowCompany; ?>>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_COMPANY_NAME'); ?>:</td>
                                    <td><input class="inputbox" type="text" name="company_name" id="company_name"
                                               size="32"
                                               maxlength="250" value="<?php echo $billing->company_name; ?>"/></td>
                                </tr>
                                <tr>
                                    <td align="right" class="key"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>
                                        :
                                    </td>
                                    <td><input class="inputbox" type="text" name="firstname" id="firstname" size="32"
                                               maxlength="250" value="<?php echo $billing->firstname; ?>"/></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:</td>
                                    <td><input class="inputbox" type="text" name="lastname" id="lastname" size="32"
                                               maxlength="250" value="<?php echo $billing->lastname; ?>"/></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:</td>
                                    <td><input class="inputbox" type="text" name="address" id="address" size="32"
                                               maxlength="250" value="<?php echo $billing->address; ?>"/></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</td>
                                    <td><input class="inputbox" type="text" name="zipcode" id="zipcode" size="32"
                                               maxlength="250" value="<?php echo $billing->zipcode; ?>"/></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td>
                                    <td><input class="inputbox" type="text" name="city" id="city" size="32"
                                               maxlength="250"
                                               value="<?php echo $billing->city; ?>"/></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</td>
                                    <td><?php echo $this->lists['country_code']; ?></td>
                                </tr>
                                <tr id="div_state_txt">
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</td>
                                    <td><?php echo $this->lists['state_code']; ?></td>
                                </tr>
                                <script type="text/javascript" language="javascript">
                                    if (document.getElementById('rs_state_state_code')) {
                                        if (document.getElementById('rs_state_state_code').options[1] == undefined) {
                                            document.getElementById('div_state_txt').style.display = 'none';
                                        }
                                    }
                                </script>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td>
                                    <td><input class="inputbox" type="text" name="phone" id="phone" size="32"
                                               maxlength="250"
                                               value="<?php echo $billing->phone; ?>"/></td>
                                </tr>
                                <tr>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
                                    <td><input class="inputbox" type="text" name="email" id="email" size="32"
                                               maxlength="250"
                                               value="<?php echo $billing->user_email; ?>"
                                               <?php if ($this->detail->user_id <= 0 && $style == "block")
                                               { ?>onblur="validate(2);"<?php } ?> />
                                        <input type="hidden" name="user_email" id="user_email" value=""/><span
                                                id="email_valid"></span></td>
                                </tr>
                                <tr id="trVatNumber" <?php echo $allowCompany; ?>>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:</td>
                                    <td><input class="inputbox" type="text" name="vat_number" id="vat_number" size="32"
                                               maxlength="250" value="<?php echo $billing->vat_number; ?>"/></td>
                                </tr>
                                <tr id="trEANnumber" <?php echo $allowCompany; ?>>
                                    <td align="right"><?php echo JText::_('COM_REDSHOP_EAN_NUMBER'); ?>:</td>
                                    <td><input class="inputbox" type="text" name="ean_number" id="ean_number" size="32"
                                               maxlength="250" value="<?php echo $billing->ean_number; ?>"/></td>
                                </tr>
                                <?php if (Redshop::getConfig()->get('USE_TAX_EXEMPT') == 1) {
                                    ?>
                                    <tr id="trTaxExempt" <?php echo $allowCompany; ?>>
                                        <td align="right"><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT'); ?>:</td>
                                        <td><?php echo JHTML::_(
                                                'select.booleanlist',
                                                'tax_exempt',
                                                'class="inputbox"',
                                                $billing->tax_exempt
                                            ); ?></td>
                                    </tr>
                                    <tr id="trTaxExemptRequest" <?php echo $allowCompany; ?>>
                                        <td align="right"><?php echo JText::_(
                                                'COM_REDSHOP_USER_REQUEST_TAX_EXEMPT_LBL'
                                            ); ?>:
                                        </td>
                                        <td><?php echo JHTML::_(
                                                'select.booleanlist',
                                                'requesting_tax_exempt',
                                                'class="inputbox"',
                                                $billing->requesting_tax_exempt
                                            ); ?></td>
                                    </tr>
                                    <tr id="trTaxExemptApproved" <?php echo $allowCompany; ?>>
                                        <td align="right"><?php echo JText::_('COM_REDSHOP_TEX_EXEMPT_APPROVED'); ?>
                                            :
                                        </td>
                                        <td><?php echo JHTML::_(
                                                'select.booleanlist',
                                                'tax_exempt_approved',
                                                'class="inputbox"',
                                                $billing->tax_exempt_approved
                                            ); ?>
                                            <input type="hidden" name="tax_exempt_approved_id"
                                                   value="<?php echo $billing->tax_exempt_approved; ?>"/></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2">
                                        <div
                                                id="exCustomerField" <?php echo $allowCustomer; ?>><?php echo $this->lists['customer_field']; ?></div>
                                        <div
                                                id="exCompanyField" <?php echo $allowCompany; ?>><?php echo $this->lists['company_field']; ?></div>
                                    </td>
                                </tr>
                                <input type="hidden" name="users_info_id" id="users_info_id"
                                       value="<?php echo $billing->users_info_id; ?>"/>
                            </table>
                        </td>
                        <td width="50%" style="vertical-align: top;">
                            <table class="adminlist table" border="0" width="100%">
                                <tr>
                                    <td width="30%" align="right">
                                        <?php echo JText::_('COM_REDSHOP_SHIPPING_SAME_AS_BILLING') ?>:
                                    </td>
                                    <td>
                                        <input type="checkbox" id="billisship" name="billisship"
                                               value="1" <?php echo $billisshipcheck ?>
                                               onclick="javascript:getShippinginfo('<?php echo $billing->users_info_id ?>')"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100" align="right"><?php echo JText::_('COM_REDSHOP_SELECT_SHIPPING'); ?>
                                        :
                                    </td>
                                    <td><?php echo $this->lists['shippinginfo_list'] ?></td>
                                </tr>
                            </table>
                            <div id="order_shipping_div" style="display:<?php echo $shippingblock; ?>;">
                                <table class="adminlist" border="0" width="100%" align="center">
                                    <tr>
                                        <td width="100" align="right"><?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>
                                            :
                                        </td>
                                        <td><input class="inputbox" type="text" name="firstname_ST" maxlength="250"
                                                   value="<?php echo $shipping->firstname; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td width="100" align="right"><?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:
                                        </td>
                                        <td><input class="inputbox" type="text" name="lastname_ST" maxlength="250"
                                                   value="<?php echo $shipping->lastname; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td width="100" align="right"><?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:
                                        </td>
                                        <td><input class="inputbox" type="text" name="address_ST" maxlength="250"
                                                   value="<?php echo $shipping->address; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td width="100" align="right"><?php echo JText::_('COM_REDSHOP_ZIP'); ?>:</td>
                                        <td><input class="inputbox" type="text" name="zipcode_ST" maxlength="250"
                                                   value="<?php echo $shipping->zipcode; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td width="100" align="right"><?php echo JText::_('COM_REDSHOP_CITY'); ?>:</td>
                                        <td><input class="inputbox" type="text" name="city_ST" maxlength="250"
                                                   value="<?php echo $shipping->city; ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td width="100" align="right"><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:
                                        </td>
                                        <td><?php echo $this->lists['country_code_ST']; ?></td>
                                    </tr>
                                    <tr id="div_state_st_txt">
                                        <td width="100" align="right"><?php echo JText::_('COM_REDSHOP_STATE'); ?>:</td>
                                        <td><?php echo $this->lists['state_code_ST']; ?></td>
                                    </tr>
                                    <script type="text/javascript" language="javascript">
                                        if (document.getElementById('rs_state_state_code_ST')) {
                                            if (document.getElementById('rs_state_state_code_ST').options[1] == undefined) {
                                                document.getElementById('div_state_st_txt').style.display = 'none';
                                            }
                                        }

                                    </script>
                                    <tr>
                                        <td width="100" align="right"><?php echo JText::_('COM_REDSHOP_PHONE'); ?>:</td>
                                        <td><input class="inputbox" type="text" name="phone_ST" maxlength="20"
                                                   value="<?php echo $shipping->phone; ?>"/></td>
                                    </tr>
                                    <?php /* // Tweak by Ronni START - Add Billing as sender field */ ?>
                                    <tr>
                                        <td width="100" align="right"><?php echo JText::_('Afsender = Faktura adresse'); ?>:</td>
                                        <td><input class="inputbox" type="text" name="billing_as_sender_ST" maxlength="20"
                                                   value="<?php echo $shipping->billing_as_sender; ?>"/></td>
                                    </tr>
                                    <?php /* // Tweak by Ronni END - Add Billing as sender field */ ?>
                                    <tr>
                                        <td colspan="2">
                                            <div
                                                    id="exCustomerFieldST" <?php echo $allowCustomer; ?>><?php echo $this->lists['shipping_customer_field']; ?></div>
                                            <div
                                                    id="exCompanyFieldST" <?php echo $allowCompany; ?>><?php echo $this->lists['shipping_company_field']; ?></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <?php if ($this->detail->user_id <= 0): ?>
                    <table class="adminlist table">
                        <tr>
                            <td width="50%">
                                <table id="tblcreat" style="display:<?php echo $style; ?>;" class="adminlist table">
                                    <tr>
                                        <td width="30%" align="right"><?php echo JText::_('COM_REDSHOP_USERNAME'); ?>:
                                        </td>
                                        <td width="70%"><input class="inputbox" type="text" name="username"
                                                               id="username" size="32" maxlength="250"
                                                               value="<?php echo $username ?>"/><span
                                                    id="user_valid"></span></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo JText::_('COM_REDSHOP_NEW_PASSWORD_LBL'); ?>:</td>
                                        <td><input class="inputbox" type="password" name="password" id="password"
                                                   size="32" maxlength="250"
                                                   value=""/></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><?php echo JText::_('COM_REDSHOP_VERIFIED_PASSWORD_LBL'); ?>
                                            :
                                        </td>
                                        <td><input class="inputbox" type="password" name="password2" id="password2"
                                                   size="32"
                                                   maxlength="250" value=""/></td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%"></td>
                        </tr>
                    </table>
                <?php endif; ?>

                <?php if (\JFactory::getApplication()->input->getCmd('ajaxtask') == "getuser"): ?>
                    <?php die(); ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php if ($err == "" && property_exists($billing, "users_info_id") && $billing->users_info_id): ?>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="adminlist">
                        <tbody>
                        <tr style="background-color: #cccccc">
                            <!-- // Tweak by Ronni : Add colspan="2" -->
                            <th align="left" colspan="2"><?php echo JText::_('COM_REDSHOP_ORDER_DETAILS'); ?></th>
                        </tr>
						<!-- // Tweak by Ronni START : Add product custom button -->
						<tr >
							<td align="right" colspan="7">
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRow('tblproductRow');">
                                    <?php echo JText::_('COM_REDSHOP_NEW'); ?>
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',39);">
                                    Klistermærker
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3517);">
                                    Folieprint
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3614);">
                                    Plakater std
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3565);">
                                    Plakater fri str
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3533);">
                                    Skumplader
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3561);">
                                    Lyskasse film
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3554);">
                                    One way folie
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',4);">
                                    Roll up
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3536);">
                                    PVC banner
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3516);">
                                    Kanvas
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3523);">
                                    Pressevæg
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3559);">
                                    Hastetillæg
                                </a>
								<a class="btn btn-info" style="margin-bottom: 5px" 
                                        href="javascript:addNewproductRowCustom('tblproductRow',3608);">
                                    Forsendelse
                                </a>
							</td>
						</tr>
						<!-- // Tweak by Ronni END : Add product custom button -->
                        <tr>
                            <!-- // Tweak by Ronni : Add colspan="2" -->
                            <td colspan="2">
                                <table class="adminlist" id="tblproductRow" width="100%">
                                    <tr>
                                        <th width="5%"><?php echo JText::_('COM_REDSHOP_ACTION'); ?></th>
                                        <th width="30%">
                                        <?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></td>
                                        <th width="20%">
                                        <?php echo JText::_('COM_REDSHOP_ORDER_PRODUCT_NOTE'); ?></td>
                                        <th width="10%">
                                        <?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_WITHOUT_VAT'); ?></td>
                                        <th width="10%" align="right">
                                        <?php echo JText::_('COM_REDSHOP_TAX'); ?></td>
                                        <th width="10%" align="right">
                                        <?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE'); ?></td>
                                        <th width="5%">
                                        <?php echo JText::_('COM_REDSHOP_PRODUCT_QTY'); ?></td>
                                        <th width="10%" align="right">
                                        <?php echo JText::_('COM_REDSHOP_TOTAL_PRICE'); ?></td>
                                    </tr>
                                    <?php /* Tweak by Ronni START - Remove RS product fields ?>
                                    <tr id="trPrd1">
                                        <td align="center"></td>
                                        <td><?php
                                            echo JHTML::_(
                                                'redshopselect.search',
                                                '',
                                                'product1',
                                                array(
                                                    'select2.ajaxOptions' => array('typeField' => ', isproduct:1'),
                                                    'select2.options'     => array(
                                                        'events' => array(
                                                            'select2-selecting' => 'function(e) {
                                                                document.getElementById(\'product1\').value = e.object.id;
                                                                displayProductDetailInfo(\'product1\', 0);}'
                                                        )
                                                    )
                                                )
                                            );
                                            ?>
                                            <div id="divAttproduct1"></div>
                                            <div id="divAccproduct1"></div>
                                            <div id="divUserFieldproduct1"></div>
                                        </td>
                                        <td id="tdnoteproduct1"></td>
                                        <td><input type="number" min="0" name="prdexclpriceproduct1"
                                                   id="prdexclpriceproduct1"
                                                   onchange="changeOfflinePriceBox('product1');" value="0" size="10">
                                        </td>
                                        <td align="right">
                                            <div id="prdtaxproduct1"></div>
                                            <input name="taxpriceproduct1" id="taxpriceproduct1" type="hidden"
                                                   value="0"/></td>
                                        <td align="right">
                                            <div id="prdpriceproduct1"></div>
                                            <input name="productpriceproduct1" id="productpriceproduct1" type="hidden"
                                                   value="0"/></td>
                                        <td><input type="number" min="0" class="quantity" name="quantityproduct1"
                                                   id="quantityproduct1"
                                                   onchange="changeOfflineQuantityBox('product1');" value="1"
                                                   size="<?php echo Redshop::getConfig()->get('DEFAULT_QUANTITY'); ?>"
                                                   maxlength="<?php echo Redshop::getConfig()->get(
                                                       'DEFAULT_QUANTITY'
                                                   ); ?>"></td>
                                        <td align="right">
                                            <div id="tdtotalprdproduct1"></div>
                                            <input name="subpriceproduct1" id="subpriceproduct1" type="hidden"
                                                   value="0"/>

                                            <input type="hidden" name="main_priceproduct1" id="main_priceproduct1"
                                                   value="0"/>
                                            <input type="hidden" name="tmp_product_priceproduct1"
                                                   id="tmp_product_priceproduct1"
                                                   value="0">
                                            <input type="hidden" name="product_vatpriceproduct1"
                                                   id="product_vatpriceproduct1"
                                                   value="0">
                                            <input type="hidden" name="tmp_product_vatpriceproduct1"
                                                   id="tmp_product_vatpriceproduct1" value="0">
                                            <input type="hidden" name="wrapper_dataproduct1" id="wrapper_dataproduct1"
                                                   value="0">
                                            <input type="hidden" name="wrapper_vatpriceproduct1"
                                                   id="wrapper_vatpriceproduct1"
                                                   value="0">

                                            <input type="hidden" name="accessory_dataproduct1"
                                                   id="accessory_dataproduct1"
                                                   value="0">
                                            <input type="hidden" name="acc_attribute_dataproduct1"
                                                   id="acc_attribute_dataproduct1" value="0">
                                            <input type="hidden" name="acc_property_dataproduct1"
                                                   id="acc_property_dataproduct1"
                                                   value="0">
                                            <input type="hidden" name="acc_subproperty_dataproduct1"
                                                   id="acc_subproperty_dataproduct1" value="0">
                                            <input type="hidden" name="accessory_priceproduct1"
                                                   id="accessory_priceproduct1"
                                                   value="0">
                                            <input type="hidden" name="accessory_vatpriceproduct1"
                                                   id="accessory_vatpriceproduct1" value="0">

                                            <input type="hidden" name="attribute_dataproduct1"
                                                   id="attribute_dataproduct1"
                                                   value="0">
                                            <input type="hidden" name="property_dataproduct1" id="property_dataproduct1"
                                                   value="0">
                                            <input type="hidden" name="subproperty_dataproduct1"
                                                   id="subproperty_dataproduct1"
                                                   value="0">
                                            <input type="hidden" name="requiedAttributeproduct1"
                                                   id="requiedAttributeproduct1"
                                                   value="0">

                                        </td>
                                    </tr>
                                    <?php Tweak by Ronni END - Remove RS product fields */ ?>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><a class="btn btn-success"
                                                 href="javascript:addNewproductRow('tblproductRow');"><?php echo JText::_(
                                        'COM_REDSHOP_ADD_PRODUCT'
                                    ); ?></a>
                                    <?php /* // Tweak by Ronni START : Add <br><br><br><br> */ ?>
                                    <br><br><br><br>
                            </td>
                        </tr>
                        <tr>
                            <!-- // Tweak by Ronni START : Move Order info up to here -->
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0" class="adminlist">
                                    <tr style="background-color: #cccccc">
                                        <th colspan="2" align="left"><?php echo JText::_('COM_REDSHOP_ORDER_INFORMATION'); ?></th>
                                    </tr>
                                    <tr>
                                        <td><?php echo JText::_('COM_REDSHOP_ORDER_DATE'); ?></td>
                                        <td><?php echo $redconfig->convertDateFormat(time()); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo JText::_('COM_REDSHOP_CUSTOMER_IP_ADDRESS'); ?></td>
                                        <td><?php echo $ip; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo JText::_('COM_REDSHOP_COMMENT'); ?></td>
                                        <td><textarea cols="50" rows="5" name="customer_note"></textarea></td>
                                    </tr>
                                    <?php //if($isCompany){?>
                                    <tr>
                                        <td><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER'); ?></td>
                                        <td><input name="requisition_number" id="requisition_number" value=""/></td>
                                    </tr>
                                    <?php //}?>
                                    <tr>
                                        <td><?php echo JText::_('COM_REDSHOP_ORDER_STATUS'); ?></td>
                                        <td><?php
                                            echo RedshopHelperOrder::getStatusList(
                                                'order_status',
                                                "",
                                                "class=\"inputbox\" size=\"1\" "
                                            );
                                            echo "&nbsp";
                                            echo RedshopHelperOrder::getPaymentStatusList(
                                                'order_payment_status',
                                                "",
                                                "class=\"inputbox\" size=\"1\" "
                                            ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo JText::_('COM_REDSHOP_ORDER_PAYMENT_METHOD'); ?></td>
                                        <td id="tdPayment">
                                    </tr>
                                    <tr>
                                        <td><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD'); ?></td>
                                        <td id="tdShipping">
                                    </tr>
                                </table>
                            </td>
                            <!-- // Tweak by Ronni END : Move Order info up to here -->
                            <td>
                            <?php /* // Tweak by Ronni START - Add align="right" to Table */ ?>
                                <table border="0" cellspacing="0" cellpadding="0" class="adminlist align="right"">
                                    <tbody>
                                    <tr align="left">
                                        <td align="right" width="70%">
                                            <strong><?php echo JText::_('COM_REDSHOP_ORDER_SUBTOTAL'); ?>:</strong></td>
                                        <td align="right" width="30%">
                                            <div id="divSubTotal"></div>
                                            <input name="order_subtotal" id="order_subtotal" type="hidden" value="0"/>
                                        </td>
                                    </tr>
                                    <tr align="left">
                                        <td align="right" width="70%"><strong><?php echo JText::_(
                                                    'COM_REDSHOP_ORDER_TAX'
                                                ); ?>
                                                :</strong></td>
                                        <td align="right" width="30%">
                                            <div id="divTax"></div>
                                            <input name="order_tax" id="order_tax" type="hidden" value="0"/></td>
                                    </tr>
                                    <tr align="left">
                                        <td align="right" width="70%">
                                            <strong><?php echo JText::_('COM_REDSHOP_ORDER_DISCOUNT_LBL'); ?>:</strong>
                                        </td>
                                        <td align="right" width="30%">
                                            <div id="divUpdateDiscount"></div>
                                            <input name="update_discount" id="update_discount" size="5" type="number"
                                                   min="0" value="0"/>
                                        </td>
                                    </tr>
                                    <tr align="left">
                                        <td align="right" width="70%">
                                            <strong><?php echo JText::_('COM_REDSHOP_SPECIAL_DISCOUNT'); ?>:</strong>
                                        </td>
                                        <td align="right" width="30%">
                                            <div id="divSpecialDiscount"></div>
                                            <input name="special_discount" id="special_discount" type="number" min="0"
                                                   size="5"
                                                   value="0"/>%
                                        </td>
                                    </tr>
                                    <tr align="left">
                                        <td align="right" width="70%">
                                            <strong><?php echo JText::_('COM_REDSHOP_ORDER_SHIPPING'); ?>:</strong></td>
                                        <td align="right" width="30%">
                                            <div id="divShipping"></div>
                                            <input name="order_shipping" id="order_shipping" type="hidden" value="0"/>
                                        </td>
                                    </tr>
                                    <tr align="left">
                                        <td colspan="2" align="left">
                                            <hr/>
                                        </td>
                                    </tr>
                                    <tr align="left">
                                        <td align="right" width="70%"><strong><?php echo JText::_(
                                                    'COM_REDSHOP_ORDER_TOTAL'
                                                ); ?>
                                                :</strong></td>
                                        <td align="right" width="30%">
                                            <div id="divFinalTotal"></div>
                                            <input name="order_total" id="order_total" type="hidden" value="0"/></td>
                                    </tr>
                                    <tr align="left">
                                        <td colspan="2" align="left">
                                            <hr/>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?php /* // Tweak by Ronni START : Move Order info up
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" class="adminlist">
                        <tr style="background-color: #cccccc">
                            <th colspan="2" align="left"><?php echo JText::_('COM_REDSHOP_ORDER_INFORMATION'); ?></th>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_ORDER_DATE'); ?></td>
                            <td><?php echo $redconfig->convertDateFormat(time()); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_CUSTOMER_IP_ADDRESS'); ?></td>
                            <td><?php echo $ip; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_COMMENT'); ?></td>
                            <td><textarea cols="50" rows="5" name="customer_note"></textarea></td>
                        </tr>
                        <?php //if($isCompany){?>
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER'); ?></td>
                            <td><input name="requisition_number" id="requisition_number" value=""/></td>
                        </tr>
                        <?php //}?>
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_ORDER_STATUS'); ?></td>
                            <td><?php
                                echo RedshopHelperOrder::getStatusList(
                                    'order_status',
                                    "",
                                    "class=\"inputbox\" size=\"1\" "
                                );
                                echo "&nbsp";
                                echo RedshopHelperOrder::getPaymentStatusList(
                                    'order_payment_status',
                                    "",
                                    "class=\"inputbox\" size=\"1\" "
                                ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_ORDER_PAYMENT_METHOD'); ?></td>
                            <td id="tdPayment">
                        </tr>
                        <tr>
                            <td><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD'); ?></td>
                            <td id="tdShipping">
                        </tr>
                    </table>
                </td>
            </tr>
			// Tweak by Ronni START : Move Order info up  */ ?>
        <?php endif; ?>
        </tbody>
    </table>
    <?php if (!JFactory::getApplication()->input->getCmd('ajaxtask')): ?>
    <input type="hidden" name="ip_address" value="<?php echo $ip; ?>"/>
    <input type="hidden" name="cdate" value="<?php echo time(); ?>"/>
    <input type="hidden" name="mdate" value="<?php echo time(); ?>"/>
    <input type="hidden" name="encr_key"
           value="<?php echo \Redshop\Crypto\Helper\Encrypt::generateCustomRandomEncryptKey(); ?>"/>
    <input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>"/>
    <input type="hidden" name="cid[]" value="<?php echo $this->detail->order_id; ?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="option" value="com_redshop"/>
    <input type="hidden" name="view" value="addorder_detail"/>
</form>
    <div id="divCalc"></div>
<?php endif; ?>

<script type="text/javascript">
    // Tweak by Ronni - Change displayProductDetailInfo > displayCustomProductDetailInfo
    function createJsonObject(uniqueId) {
        <?php
        echo JHTML::_(
            'redshopselect.search',
            '',
            "product' + uniqueId + '",
            array(
                'select2.ajaxOptions' => array('typeField' => ', isproduct:1'),
                'select2.options'     => array(
                    'events' => array(
                        'select2-selecting' => 'function(e) {
                        document.getElementById(\'product\' + uniqueId).value = e.object.id;
                        displayCustomProductDetailInfo(\'product\' + uniqueId, 0);}'
                    )
                )
            ),
            true
        );
        ?>
    }

    function validateInputFloat(el, e) {
        var type = jQuery(el).attr("class");

        var value = jQuery(el).val();

        if ((type == "quantity") && (value < 1)) {
            alert('<?php echo JText::_("COM_REDSHOP_ORDER_ITEM_QUANTITY_ATLEAST_ONE") ?>');
            jQuery(el).val(1);
        }

        if (value == '') {
            jQuery(el).val(1);
        }

        if ((e.keyCode == 189) || (e.keyCode == 109)) {
            e.preventDefault();
        }
    }

    jQuery(document).ready(function () {
        jQuery('body').on("change", "input[type=number]", function (e) {
            validateInputFloat(this, e);
        });
    });

</script>
