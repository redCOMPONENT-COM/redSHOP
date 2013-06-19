// open modal box
window.addEvent('domready', function () {
    // Load redbox on page load to make modal working.
    var imagehandle = {isenable: true, mainImage: true};
    preloadSlimbox(imagehandle);
    // end
    var otheroptions = {handler: 'iframe'};
    redBOX.assign($$(".redcolorproductimg"), otheroptions);

    redBOX.assign($$('a.redbox'), {
        parse: 'rel'
    });

});

var r_browser = false;
var subproperty_main_image = "";
function getHTTPObject() {
    var xhr = false;
    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {
                xhr = false;
            }
        }
    }
    return xhr;
}
var request;

/************************Js Clean up code start function*******************************/
function productaddprice(product_id, relatedprd_id) {
    var qty = 1;

    if (relatedprd_id != 0) {
        prefix = relatedprd_id;
    } else {
        prefix = product_id;
    }

    if (document.getElementById("accessory_data")) {
        var accessory_data = document.getElementById("accessory_data").value;
    }
    if (document.getElementById("acc_quantity_data")) {
        var acc_quantity_data = document.getElementById("acc_quantity_data").value;
    }
    if (document.getElementById("acc_attribute_data")) {
        var acc_attribute_data = document.getElementById("acc_attribute_data").value.replace("##", "::");
    }
    if (document.getElementById("acc_property_data")) {
        var acc_property_data = document.getElementById("acc_property_data").value.replace("##", "::");
    }
    if (document.getElementById("acc_subproperty_data")) {
        var acc_subproperty_data = document.getElementById("acc_subproperty_data").value.replace("##", "::");
    }
//	alert(accessory_data + " " + acc_quantity_data + " " + acc_attribute_data + " " + acc_property_data + " " + acc_subproperty_data);

    if (document.getElementById('quantity' + prefix) && document.getElementById('quantity' + prefix)) {
        qty = document.getElementById('quantity' + prefix).value;
    }
    if (document.getElementById('attribute_data')) {
        var attribute_data = document.getElementById('attribute_data').value.replace("##", "::");
    }
    if (document.getElementById('property_data')) {
        var property_data = document.getElementById('property_data').value.replace("##", "::");
    }
    if (document.getElementById('subproperty_data')) {
        var subproperty_data = document.getElementById('subproperty_data').value.replace("##", "::");
    }
    //	alert("123");
    var url = site_url + "index.php?option=com_redshop&view=product&task=displayProductaddprice&tmpl=component&qunatity=" + qty;
    url = url + "&product_id=" + product_id + "&attribute_data=" + attribute_data + "&property_data=" + property_data + "&subproperty_data=" + subproperty_data;
    url = url + "&accessory_data=" + accessory_data + "&acc_quantity_data=" + acc_quantity_data + "&acc_attribute_data=" + acc_attribute_data + "&acc_property_data=" + acc_property_data + "&acc_subproperty_data=" + acc_subproperty_data;

    request = getHTTPObject();
    request.onreadystatechange = function () {
        // if request object received response

        if (request.readyState == 4) {
            //alert(request.responseText);
            var str = request.responseText.split(":");
            var accessory_price = 0;
            var accessory_price_withoutvat = 0;
            var wprice = 0;
            var wrapper_price_withoutvat = 0;


//			if(document.getElementById('accessory_price'))
//			{
//				accessory_price = parseFloat(document.getElementById('accessory_price').value)*myqty;
//			}
//			if(document.getElementById('accessory_price_withoutvat'))
//			{
//				accessory_price_withoutvat = parseFloat(document.getElementById('accessory_price_withoutvat').value);
//			}
            if (document.getElementById("wrapper_price")) {
                wprice = parseFloat(document.getElementById("wrapper_price").value);
            }
            if (document.getElementById("wrapper_price_withoutvat")) {
                wrapper_price_withoutvat = parseFloat(document.getElementById("wrapper_price_withoutvat").value);
            }

            if (document.getElementById('produkt_kasse_hoejre_pris_indre' + prefix)) {
                document.getElementById('produkt_kasse_hoejre_pris_indre' + prefix).innerHTML = number_format(parseFloat(str[0]) + (wprice * qty), PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
            }
            if (document.getElementById('display_product_discount_price' + prefix)) {
                document.getElementById('display_product_discount_price' + prefix).innerHTML = number_format(str[4], PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
            }
            if (document.getElementById('display_product_price_without_vat' + prefix)) {
                document.getElementById('display_product_price_without_vat' + prefix).innerHTML = number_format(parseFloat(str[5]) + (wrapper_price_withoutvat * qty), PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
            }
            if (document.getElementById('display_product_price_no_vat' + prefix)) {
                document.getElementById('display_product_price_no_vat' + prefix).innerHTML = number_format(parseFloat(str[5]) + (wrapper_price_withoutvat * qty), PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
            }
            if (document.getElementById('display_product_old_price' + prefix)) {
                document.getElementById('display_product_old_price' + prefix).innerHTML = number_format(str[2], PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
            }
            if (document.getElementById('display_product_saving_price' + prefix)) {
                document.getElementById('display_product_saving_price' + prefix).innerHTML = number_format(str[3], PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
            }

            if (document.getElementById('main_price' + prefix)) {
                document.getElementById('main_price' + prefix).value = str[0];
            }
            if (document.getElementById('product_price_no_vat' + prefix)) {
                document.getElementById('product_price_no_vat' + prefix).value = str[5];
                if (document.getElementById('main_price' + product_id)) {
                    document.getElementById('main_price' + product_id).value = str[0];
                }
                if (document.getElementById('product_price_no_vat' + product_id)) {
                    document.getElementById('product_price_no_vat' + product_id).value = str[5];
                }
                if (document.getElementById('product_old_price' + product_id)) {
                    document.getElementById('product_old_price' + product_id).value = str[2];
                }


            }
            if (document.getElementById('product_old_price' + prefix)) {
                document.getElementById('product_old_price' + prefix).value = str[2];
            }
        }
    };
    request.open("GET", url, true);
    request.send(null);

}
function changePropertyDropdown(product_id, accessory_id, relatedprd_id, attribute_id, selectedproperty_id, mpw_thumb, mph_thumb) {
    var allarg = arguments;
    var propArr = new Array();
    var subpropArr = new Array();
    var property_data = "";
    var subproperty_data = "";
    var suburl = "";
    var subatthtml = "";
    var layout = "";
    var prefix;
    if (document.getElementById('isAjaxBoxOpen')) {
        layout = document.getElementById('isAjaxBoxOpen').value;
    }

    var preprefix = "";
    if (layout == "viewajaxdetail") {
        preprefix = "ajax_";
    }
    if (accessory_id != 0) {
        prefix = preprefix + "acc_";
    } else if (relatedprd_id != 0) {
        prefix = preprefix + "rel_";
    } else {
        prefix = preprefix + "prd_";
    }

    var commonid = prefix + product_id + '_' + accessory_id + '_' + attribute_id;
    if (document.getElementById('subattdata_' + commonid)) {
        subatthtml = document.getElementById('subattdata_' + commonid).value;
    }
    suburl = suburl + "&subatthtml=" + subatthtml;
    suburl = suburl + "&product_id=" + product_id;
    suburl = suburl + "&attribute_id=" + attribute_id;
    suburl = suburl + "&accessory_id=" + accessory_id;
    suburl = suburl + "&relatedprd_id=" + relatedprd_id;

    if (document.getElementsByName('property_id_' + commonid + '[]')) {
        var propName = document.getElementsByName('property_id_' + commonid + '[]');
        var sel_i = 0;
        for (var p = 0; p < propName.length; p++) {
            if (propName[p].type == 'checkbox' || propName[p].type == 'radio') {
                if (propName[p].checked) {
                    propArr[sel_i++] = propName[p].value;
                }
            } else {
                if (propName[p].selectedIndex) {
                    propArr[sel_i++] = propName[p].options[propName[p].selectedIndex].value;
                }
            }
        }
        var subsel_i = 0;
        for (var sp = 0; sp < propArr.length; sp++) {
            var spcommonid = commonid + '_' + propArr[sp];
            if (document.getElementsByName('subproperty_id_' + spcommonid + '[]')) {
                var subpropName = document.getElementsByName('subproperty_id_' + spcommonid + '[]');
                for (var p = 0; p < subpropName.length; p++) {
                    if (subpropName[p].type == 'checkbox' || subpropName[p].type == 'radio') {
                        if (subpropName[p].checked) {
                            subpropArr[subsel_i++] = subpropName[p].value;
                        }
                    } else {
                        if (subpropName[p].selectedIndex) {
                            subpropArr[subsel_i++] = subpropName[p].options[subpropName[p].selectedIndex].value;
                        }
                    }
                }
            }
        }

        property_data = propArr.join(",");
        subproperty_data = subpropArr.join(",");
        suburl = suburl + "&property_id=" + property_data;
        suburl = suburl + "&subproperty_id=" + subproperty_data;
    }
    var url = site_url + "index.php?option=com_redshop&view=product&task=displaySubProperty&tmpl=component&isAjaxBox=" + layout;
    url = url + suburl;
//	alert(url);
    request = getHTTPObject();
    request.onreadystatechange = function () {
        // if request object received response
        if (document.getElementById('property_responce' + commonid)) {
            document.getElementById('property_responce' + commonid).style.display = 'none';
        }
        if (request.readyState == 4) {
            var property_id = 0;
            if (document.getElementById('property_responce' + commonid)) {
                document.getElementById('property_responce' + commonid).innerHTML = request.responseText;
                document.getElementById('property_responce' + commonid).style.display = '';

                for (var p = 0; p < propArr.length; p++) {
                    property_id = propArr[p];
                    var scrollercommonid = commonid + '_' + property_id;
//					alert('divsubimgscroll'+commonid+'_'+property_id);
                    if (document.getElementById('divsubimgscroll' + scrollercommonid)) {
                        var scrollhtml = document.getElementById('divsubimgscroll' + scrollercommonid).innerHTML;
                        if (scrollhtml != "") {
                            var imgs = scrollhtml.split('#_#');
                            var unique = "isFlowers" + scrollercommonid;
                            unique = new ImageScroller('isFlowersFrame' + scrollercommonid, 'isFlowersImageRow' + scrollercommonid);
                            var subpropertycommonid = 'subproperty_id_' + scrollercommonid;
                            var subinfo = '';
                            for (i = 0; i < imgs.length; i++) {
                                subinfo = imgs[i].split('`_`');
                                var subproperty_id = subinfo[1];
                                unique.addThumbnail(subinfo[0], "javascript:isFlowers" + scrollercommonid + ".scrollImageCenter('" + i + "');setSubpropImage('" + product_id + "','" + subpropertycommonid + "','" + subproperty_id + "');calculateTotalPrice('" + product_id + "','" + relatedprd_id + "');displayAdditionalImage('" + product_id + "','" + accessory_id + "','" + relatedprd_id + "','" + property_id + "','" + subproperty_id + "');", "", "", subpropertycommonid + "_subpropimg_" + subproperty_id, "");
                            }
                            var rs_size = 50;
                            if (mph_thumb > mpw_thumb) {
                                rs_size = mph_thumb;
                            }
                            else {
                                rs_size = mpw_thumb;
                            }

                            unique.setThumbnailHeight(parseInt(ATTRIBUTE_SCROLLER_THUMB_HEIGHT));
                            unique.setThumbnailWidth(parseInt(ATTRIBUTE_SCROLLER_THUMB_WIDTH));
                            unique.setThumbnailPadding(5);
                            unique.setScrollType(0);
                            unique.enableThumbBorder(false);
                            unique.setClickOpenType(1);
                            unique.setThumbsShown(NOOF_SUBATTRIB_THUMB_FOR_SCROLLER);
                            unique.setNumOfImageToScroll(1);
                            unique.renderScroller();
                            window["isFlowers" + scrollercommonid] = unique;
                        }
                    }
                }
            }
            displayAdditionalImage(product_id, accessory_id, relatedprd_id, property_id, 0);
            calculateTotalPrice(product_id, relatedprd_id);

            // trigger js function via redSHOP Product plugin
            onchangePropertyDropdown(allarg);
        }
    };
    request.open("GET", url, true);
    request.send(null);
}

/**
 * This function can be override via redSHOP Plugin
 *
 * @params: orgarg  All the arguments array from the original function
 */
function onchangePropertyDropdown(orgarg) {
    return true;
}

function display_image(imgs, product_id, gethover) {
    if (!PRODUCT_DETAIL_IS_LIGHTBOX)
        document.getElementById('a_main_image' + product_id).href = gethover;
    document.getElementById('main_image' + product_id).src = imgs;
}
function display_image_out(imgs, product_id, gethover) {
    document.getElementById('main_image' + product_id).src = gethover;
}
function display_image_add(img, product_id) {
    document.getElementById('main_image' + product_id).src = img;
}
function display_image_add_out(img, product_id) {
    if (subproperty_main_image != "")
        document.getElementById('main_image' + product_id).src = subproperty_main_image;
    else
        document.getElementById('main_image' + product_id).src = img;
}
function collectAttributes(product_id, accessory_id, relatedprd_id) {


    var prefix;
    var attrArr = new Array();
    var allpropArr = new Array();
    var tolallsubpropArr = new Array();
    var mainprice = 0;
    var price_without_vat = 0;
    var old_price = 0;
    var isStock = true;
    var setPropEqual = true;
    var setSubpropEqual = true;
    var acc_error = "";
    var subacc_error = "";
    var layout = "";

    var myaccQuan = 1;
    if (document.getElementById("accquantity_" + product_id + "_" + accessory_id)) {
        myaccQuan = document.getElementById("accquantity_" + product_id + "_" + accessory_id).value;
    }


    if (document.getElementById('product_preorder' + product_id)) {
        var preorder = document.getElementById('product_preorder' + product_id).value;
    }
    if (document.getElementById('product_stock' + product_id)) {
        var product_stock = document.getElementById('product_stock' + product_id).value;
    }
    if (document.getElementById('preorder_product_stock' + product_id)) {
        var preorder_stock = document.getElementById('preorder_product_stock' + product_id).value;
    }


    if (document.getElementById('isAjaxBoxOpen')) {
        layout = document.getElementById('isAjaxBoxOpen').value;
    }

    var preprefix = "";
    if (layout == "viewajaxdetail") {
        preprefix = "ajax_";
    }

    if (accessory_id != 0) {
        prefix = preprefix + "acc_";
        if (document.getElementById('accessory_id_' + product_id + '_' + accessory_id)) {
            mainprice = parseFloat(document.getElementById('accessory_id_' + product_id + '_' + accessory_id).getAttribute('accessoryprice'));
            price_without_vat = parseFloat(document.getElementById('accessory_id_' + product_id + '_' + accessory_id).getAttribute('accessorywithoutvatprice'));
        }
        old_price = mainprice;
    } else if (relatedprd_id != 0) {
        prefix = preprefix + "rel_";
        if (document.getElementById('main_price' + product_id)) {
            mainprice = parseFloat(document.getElementById('main_price' + product_id).value);
        }
        if (document.getElementById('product_price_excluding_price' + product_id)) {
            price_without_vat = parseFloat(document.getElementById('product_price_excluding_price' + product_id).value);
        } else if (document.getElementById('product_price_no_vat' + product_id)) {
            price_without_vat = parseFloat(document.getElementById('product_price_no_vat' + product_id).value);
        }
        if (document.getElementById('product_old_price' + product_id)) {
            old_price = parseFloat(document.getElementById('product_old_price' + product_id).value);
        }

    } else {

        prefix = preprefix + "prd_";
        if (document.getElementById('main_price' + product_id)) {
            mainprice = parseFloat(document.getElementById('main_price' + product_id).value);

        }
        if (document.getElementById('product_price_excluding_price' + product_id)) {
            price_without_vat = parseFloat(document.getElementById('product_price_excluding_price' + product_id).value);
        } else if (document.getElementById('product_price_no_vat' + product_id)) {
            price_without_vat = parseFloat(document.getElementById('product_price_no_vat' + product_id).value);
        }
        if (document.getElementById('product_old_price' + product_id)) {
            old_price = parseFloat(document.getElementById('product_old_price' + product_id).value);
        }
    }


    var commonid = prefix + product_id + '_' + accessory_id;
    var commonstockid = prefix + product_id;
//	alert("mainprice = " + mainprice);
    if (document.getElementsByName('attribute_id_' + commonid + '[]')) {
        var attrName = document.getElementsByName('attribute_id_' + commonid + '[]');
        for (var i = 0; i < attrName.length; i++) {
            attrArr[i] = attrName[i].value;
        }
    }

    // removing " USE_STOCKROOM==1 && " from below condition - Gunjan
    //if(isStock && ALLOW_PRE_ORDER!=1)

    //alert(preorder_stock);
    if (isStock) {
        isStock = checkProductStockRoom(product_stock, commonstockid, preorder, preorder_stock);
    }
    if (attrArr.length <= 0 && AJAX_CART_BOX == 1) {
        if (document.getElementById("requiedAttribute")) {
            acc_error = document.getElementById("requiedAttribute").value;
        }

        if (document.getElementById("requiedProperty")) {
            subacc_error = document.getElementById("requiedProperty").value;
        }
    }


    for (var i = 0; i < attrArr.length; i++) {

        //alert("hi");
        var attribute_id = attrArr[i];
        commonid = prefix + product_id + '_' + accessory_id + '_' + attribute_id;
        var propId = document.getElementById('property_id_' + commonid);
        if (propId) {
            setPropertyImage(product_id, 'property_id_' + commonid);
            var propName = document.getElementsByName('property_id_' + commonid + '[]');

            var seli = 0;
            var propArr = new Array();
            /******Collect property start*******/
            for (var p = 0; p < propName.length; p++) {
                if (propName[p].type == 'checkbox' || propName[p].type == 'radio') {
                    if (propName[p].checked && propName[p].value != 0) {
                        propArr[seli++] = propName[p].value;
                    }
                } else {
                    if (propName[p].selectedIndex && propName[p].options[propName[p].selectedIndex].value != 0) {

                        propArr[seli++] = propName[p].options[propName[p].selectedIndex].value;

                    }
                }
            }
            //alert("propArr = " + propArr.length);
            if (propArr.length > 0) {
                allpropArr[i] = propArr.join(",,");
            }
            //alert(" propArr.length = " + propArr.length);
            // required check
            if (propId.getAttribute('required') == 1 && propArr.length == 0) {
                acc_error += document.getElementById('att_lebl').innerHTML + " " + unescape(propId.getAttribute('attribute_name')) + "\n";
            }


            /******Collect property Price start*******/
            if (setPropEqual && setSubpropEqual) {
                var oprandElementId = 'property_id_' + commonid + '_oprand';
                var priceElementId = 'property_id_' + commonid + '_proprice';
                var retProArr = calculateSingleProductPrice(mainprice, oprandElementId, priceElementId, propArr);
                //setPropEqual = retProArr[0];
                mainprice = retProArr[1];

                var retProArr = calculateSingleProductPrice(old_price, oprandElementId, priceElementId, propArr);
                old_price = retProArr[1];

                priceElementId = 'property_id_' + commonid + '_proprice_withoutvat';
                retProArr = calculateSingleProductPrice(price_without_vat, oprandElementId, priceElementId, propArr);
                price_without_vat = retProArr[1];
            }
            /******Collect property Price end*******/

            /******Collect property end*******/

            /******Collect subproperty start*******/
            var isSubproperty = false;
            var allsubpropArr = new Array();
            for (var p = 0; p < propArr.length; p++) {
                var property_id = propArr[p];
                var stockElementId = 'property_id_' + commonid + '_stock' + property_id;
                var preOrderstockElementId = 'property_id_' + commonid + '_preorderstock' + property_id;


                // removing " USE_STOCKROOM==1 && " from below condition - Gunjan
                if (document.getElementById(stockElementId) && document.getElementById(preOrderstockElementId) && isStock && accessory_id == 0) {
                    //alert(document.getElementById(stockElementId).value);
                    //if( (preorder == 'global' && ALLOW_PRE_ORDER!=1) || (preorder == '' && ALLOW_PRE_ORDER!=1) || (preorder == 'no'))
                    //{
                    isStock = checkProductStockRoom(document.getElementById(stockElementId).value, commonstockid, preorder, document.getElementById(preOrderstockElementId).value);
                    //}
                }

                var subcommonid = prefix + product_id + '_' + accessory_id + '_' + attribute_id + '_' + property_id;
                var subPropId = document.getElementById('subproperty_id_' + subcommonid);
                if (subPropId) {
                    setSubpropertyImage(product_id, 'subproperty_id_' + subcommonid);
                    isSubproperty = true;
                    var subpropName = document.getElementsByName('subproperty_id_' + subcommonid + '[]');
                    seli = 0;
                    var subpropArr = new Array();
                    for (var sp = 0; sp < subpropName.length; sp++) {
                        if (subpropName[sp].type == 'checkbox' || subpropName[sp].type == 'radio') {
                            if (subpropName[sp].checked && subpropName[sp].value) {
                                subpropArr[seli++] = subpropName[sp].value;
                            }
                        } else {
                            if (subpropName[sp].selectedIndex && subpropName[sp].options[subpropName[sp].selectedIndex].value) {
                                subpropArr[seli++] = subpropName[sp].options[subpropName[sp].selectedIndex].value;
                            }
                        }
                    }
                    for (var sp = 0; sp < subpropArr.length; sp++) {
                        var stockElementId = 'subproperty_id_' + subcommonid + '_stock' + subpropArr[sp];
                        if (USE_STOCKROOM == 1 && document.getElementById(stockElementId) && accessory_id == 0) {
                            //if( (preorder == 'global' && ALLOW_PRE_ORDER!=1) || (preorder == '' && ALLOW_PRE_ORDER!=1) || (preorder == 'no'))
                            //{
                            isStock = checkProductStockRoom(document.getElementById(stockElementId).value, commonstockid, preorder, preorder_stock);
                            //}
                        }
                    }

                    if (subPropId.getAttribute('required') == 1 && subpropArr.length == 0) {
                        subacc_error += document.getElementById('subprop_lbl').innerHTML + " " + unescape(subPropId.getAttribute('subpropName')) + "\n";
                    }
                    /******Collect subproperty Price start*******/
                    if (setPropEqual && setSubpropEqual) {
                        var oprandElementId = 'subproperty_id_' + subcommonid + '_oprand';
                        var priceElementId = 'subproperty_id_' + subcommonid + '_proprice';
                        var retSubArr = calculateSingleProductPrice(mainprice, oprandElementId, priceElementId, subpropArr);
                        //setSubpropEqual = retSubArr[0];
                        mainprice = retSubArr[1];

                        var retSubArr = calculateSingleProductPrice(old_price, oprandElementId, priceElementId, subpropArr);
                        old_price = retSubArr[1];

                        priceElementId = 'subproperty_id_' + subcommonid + '_proprice_withoutvat';


                        retSubArr = calculateSingleProductPrice(price_without_vat, oprandElementId, priceElementId, subpropArr);
                        price_without_vat = retSubArr[1];
                    }
                    /******Collect subproperty Price end*******/
                    allsubpropArr[p] = subpropArr.join("::");
                }
            }
            tolallsubpropArr[i] = allsubpropArr.join(",,");
            /******Collect subproperty end*******/
        }
    }
    if (allpropArr.length == 0) {
        attrArr = new Array();
    }
    //alert("mainprice = " + mainprice);
//	alert("attrArr = " + attrArr + "allpropArr = " + allpropArr + "tolallsubpropArr = " + tolallsubpropArr);

    if (accessory_id != 0) {
        if (document.getElementById("acc_attribute_data")) {
            document.getElementById("acc_attribute_data").value = attrArr.join("##");
        }
        if (document.getElementById("acc_property_data")) {
            document.getElementById("acc_property_data").value = allpropArr.join("##");
        }
        if (document.getElementById("acc_subproperty_data")) {
            document.getElementById("acc_subproperty_data").value = tolallsubpropArr.join("##");
        }
        if (document.getElementById("accessory_price")) {
            document.getElementById("accessory_price").value = mainprice;
        }
        if (document.getElementById("accessory_price_withoutvat")) {
            document.getElementById("accessory_price_withoutvat").value = price_without_vat;
        }
    } else {
        if (document.getElementById("attribute_data")) {
            document.getElementById("attribute_data").value = attrArr.join("##");
        }
        if (document.getElementById("property_data")) {
            document.getElementById("property_data").value = allpropArr.join("##");
        }
        if (document.getElementById("subproperty_data")) {
            document.getElementById("subproperty_data").value = tolallsubpropArr.join("##");
        }
        if (document.getElementById("tmp_product_price")) {
            document.getElementById("tmp_product_price").value = mainprice;
        }
        if (document.getElementById("productprice_notvat")) {

            document.getElementById("productprice_notvat").value = price_without_vat;
        }
        if (document.getElementById("tmp_product_old_price")) {
            document.getElementById("tmp_product_old_price").value = old_price;
        }
    }
    if (document.getElementById("requiedAttribute")) {
        document.getElementById("requiedAttribute").value = acc_error;
    }
    if (document.getElementById("requiedProperty")) {
        document.getElementById("requiedProperty").value = subacc_error;
    }
}

/*//Arguments
 stockAmount= normal stock amount
 commonstockid = Id
 preorder  = Preorder is Enable or not
 preorder_stock = prorder stock amount


 */

function checkProductStockRoom(stockAmount, commonstockid, preorder, preorder_stock) {

    var isStock = true;

    if (stockAmount > 0) {
        if (document.getElementById('pdaddtocart' + commonstockid)) {
            document.getElementById('pdaddtocart' + commonstockid).style.display = '';
        }

        if (USE_AS_CATALOG == 1) {
            if (document.getElementById('pdaddtocart' + commonstockid)) {
                document.getElementById('pdaddtocart' + commonstockid).style.display = 'none';
            }
        }
        if (document.getElementById('preordercart' + commonstockid)) {
            document.getElementById('preordercart' + commonstockid).style.display = 'none';
        }
        if (document.getElementById('stockaddtocart' + commonstockid)) {
            document.getElementById('stockaddtocart' + commonstockid).style.display = 'none';
        }

        isStock = true;
    } else {

        if (stockAmount == 0) {
            if ((preorder == 'global' && ALLOW_PRE_ORDER != 1) || (preorder == '' && ALLOW_PRE_ORDER != 1) || (preorder == 'no')) {



                //isPreorderProductStock
                if (document.getElementById('stockaddtocart' + commonstockid)) {
                    document.getElementById('stockaddtocart' + commonstockid).style.display = '';
                }
                if (document.getElementById('stockaddtocart' + commonstockid)) {
                    document.getElementById('stockaddtocart' + commonstockid).innerHTML = COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE;
                }

                if (USE_AS_CATALOG == 1) {
                    if (document.getElementById('stockaddtocart' + commonstockid)) {
                        document.getElementById('stockaddtocart' + commonstockid).style.display = 'none';
                    }
                }
                if (document.getElementById('preordercart' + commonstockid)) {
                    document.getElementById('preordercart' + commonstockid).style.display = 'none';
                }
                if (document.getElementById('pdaddtocart' + commonstockid)) {
                    document.getElementById('pdaddtocart' + commonstockid).style.display = 'none';
                }


            } else {

                if (preorder_stock == 0) {

                    if (document.getElementById('stockaddtocart' + commonstockid)) {
                        document.getElementById('stockaddtocart' + commonstockid).style.display = '';
                    }
                    if (document.getElementById('stockaddtocart' + commonstockid)) {
                        document.getElementById('stockaddtocart' + commonstockid).innerHTML = COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE;
                    }

                    if (USE_AS_CATALOG == 1) {
                        if (document.getElementById('stockaddtocart' + commonstockid)) {
                            document.getElementById('stockaddtocart' + commonstockid).style.display = 'none';
                        }
                    }
                    if (document.getElementById('preordercart' + commonstockid)) {
                        document.getElementById('preordercart' + commonstockid).style.display = 'none';
                    }
                    if (document.getElementById('pdaddtocart' + commonstockid)) {
                        document.getElementById('pdaddtocart' + commonstockid).style.display = 'none';
                    }


                } else {


                    if (document.getElementById('stockaddtocart' + commonstockid)) {
                        document.getElementById('stockaddtocart' + commonstockid).style.display = 'none';
                    }
                    if (document.getElementById('stockaddtocart' + commonstockid)) {
                        document.getElementById('stockaddtocart' + commonstockid).innerHTML = "";
                    }
                    if (document.getElementById('pdaddtocart' + commonstockid)) {
                        document.getElementById('pdaddtocart' + commonstockid).style.display = 'none';
                    }
                    if (document.getElementById('preordercart' + commonstockid)) {
                        document.getElementById('preordercart' + commonstockid).style.display = '';
                    }

                    if (USE_AS_CATALOG == 1) {
                        if (document.getElementById('preordercart' + commonstockid)) {
                            document.getElementById('preordercart' + commonstockid).style.display = 'none';
                        }
                    }


                }


            }
        }


        //isStock = false;
    }
    if (document.getElementById('stockQuantity' + commonstockid)) {
        if (stockAmount > 0 || preorder_stock > 0) {
            document.getElementById('stockQuantity' + commonstockid).style.display = '';
        } else {
            document.getElementById('stockQuantity' + commonstockid).style.display = 'none';
        }
    }
    return isStock;
}

function calculateSingleProductPrice(price, oprandElementId, priceElementId, elementArr) {
    var setEqual = true;
    for (var i = 0; i < elementArr.length; i++) {
        var id = elementArr[i];

        var oprand = document.getElementById(oprandElementId + id).value;
        var subprice = document.getElementById(priceElementId + id).value;

        if (oprand == "-") {
            price -= parseFloat(subprice);
        } else if (oprand == "+") {
            price += parseFloat(subprice);
        } else if (oprand == "*") {
            price *= parseFloat(subprice);
        } else if (oprand == "/") {
            price /= parseFloat(subprice);
        } else if (oprand == "=") {
            price = parseFloat(subprice);
            setEqual = false;
            break;
        }
    }
    var retArr = new Array();
    retArr[0] = setEqual;
    retArr[1] = price;
    return retArr;
}

// calculate attribute price
function calculateTotalPrice(product_id, relatedprd_id) {

    if (product_id == 0 || product_id == "") {
//		alert("Product ID is missing");
        return false;
    }
    var mainprice = 0;
    var price_without_vat = 0;
    var old_price = 0;
    var accfinalprice_withoutvat = 0;
    var product_old_price = 0;
    // accessory price add
    var accfinalprice = collectAccessory(product_id, relatedprd_id);


    var qty = 1;
    if (relatedprd_id != 0) {
        prefix = relatedprd_id;
    } else {
        prefix = product_id;
    }
    if (document.getElementById('quantity' + prefix) && document.getElementById('quantity' + prefix)) {

        qty = document.getElementById('quantity' + prefix).value;
    }

    if (document.getElementById('accessory_price_withoutvat')) {
        accfinalprice_withoutvat = parseFloat(document.getElementById('accessory_price_withoutvat').value);
    }

    collectAttributes(product_id, 0, relatedprd_id);

    if (document.getElementById('quantity' + prefix) && document.getElementById('quantity' + prefix).type == "select-one") {
        productaddprice(product_id, relatedprd_id);
    }

    if (document.getElementById('tmp_product_price')) {
        mainprice = parseFloat(document.getElementById('tmp_product_price').value);
    }
    if (document.getElementById('hidden_subscription_prize')) {
        mainprice = parseFloat(mainprice) + parseFloat(document.getElementById('hidden_subscription_prize').value);
    }
    if (document.getElementById('productprice_notvat')) {
        price_without_vat = parseFloat(document.getElementById('productprice_notvat').value);
    }
    if (document.getElementById('tmp_product_old_price')) {
        old_price = parseFloat(document.getElementById('tmp_product_old_price').value);
    }
    // end
    // setting wrapper price
    setWrapperComboBox();
    var wprice = 0;
    if (document.getElementById("wrapper_price")) {
        wprice = document.getElementById("wrapper_price").value;
    }
    var wrapper_price_withoutvat = 0;
    if (document.getElementById("wrapper_price_withoutvat")) {
        wrapper_price_withoutvat = document.getElementById("wrapper_price_withoutvat").value;
    }
    // end wrapper

//    alert("main price = " + mainprice + " : aacc = " + accfinalprice + " : wrapp = " + wprice);

    //var final_price_f = parseFloat(mainprice) + parseFloat(attribute_price) + parseFloat(accfinalprice) + parseFloat(wprice);
    final_price_f = parseFloat(mainprice) + parseFloat(accfinalprice) + parseFloat(wprice);
    // product dropdown qunty change multiple by original product price start

    if (document.getElementById('quantity' + prefix) && document.getElementById('quantity' + prefix).type == "select-one") {
        //alert('Please wait while calculating price');
        window.setTimeout(this.checkTimeout.bind(this), 40000);

    }


    product_price_without_vat = parseFloat(price_without_vat) + parseFloat(accfinalprice_withoutvat) + parseFloat(wrapper_price_withoutvat);

    product_old_price = parseFloat(old_price) + parseFloat(accfinalprice) + parseFloat(wprice);

    savingprice = parseFloat(product_old_price) - parseFloat(final_price_f);


    if (SHOW_PRICE == '1') {
        if (!final_price_f || (DEFAULT_QUOTATION_MODE == '1' && SHOW_QUOTATION_PRICE != '1')) {

            final_price = getPriceReplacement(final_price_f);
        } else {

            final_price = number_format(final_price_f, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
        }
    } else {
        final_price = getPriceReplacement(final_price_f);
    }
    if (SHOW_PRICE == '1' && ( DEFAULT_QUOTATION_MODE != '1' || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))) {
        //if(document.getElementById('quantity'+prefix) && document.getElementById('quantity'+prefix).type=="select-one")
        //{
        if (document.getElementById('produkt_kasse_hoejre_pris_indre' + product_id)) {
            document.getElementById('produkt_kasse_hoejre_pris_indre' + product_id).innerHTML = final_price;
        }
        //}
        if (document.getElementById('display_product_discount_price' + product_id)) {
            document.getElementById('display_product_discount_price' + product_id).innerHTML = final_price;
        }
        if (!product_price_without_vat) {
            product_price_without_vat = getPriceReplacement(product_price_without_vat);
        } else {
            product_price_without_vat = number_format(product_price_without_vat, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
        }
        if (document.getElementById('display_product_price_without_vat' + product_id)) {
            document.getElementById('display_product_price_without_vat' + product_id).innerHTML = product_price_without_vat;
        }
        //	if(document.getElementById('quantity'+prefix) && document.getElementById('quantity'+prefix).type=="select-one")
        //{
        if (document.getElementById('display_product_price_no_vat' + product_id)) {
            document.getElementById('display_product_price_no_vat' + product_id).innerHTML = product_price_without_vat;
        }
        //}

        if (document.getElementById('display_product_old_price' + product_id)) {
            if (!product_old_price) {
                product_old_price = getPriceReplacement(product_old_price);
            } else {
                product_old_price = number_format(product_old_price, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
            }
            document.getElementById('display_product_old_price' + product_id).innerHTML = product_old_price;
        }
        if (document.getElementById('display_product_saving_price' + product_id)) {
            savingprice = number_format(savingprice, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
            document.getElementById('display_product_saving_price' + product_id).innerHTML = savingprice;
        }
        if (document.getElementById("rs_selected_accessory_price")) {
            document.getElementById("rs_selected_accessory_price").innerHTML = final_price;
        }
    }
}

//Accessory data collect start
function collectAccessory(product_id, relatedprd_id) {
    if (product_id == 0 || product_id == "") {
//		alert("Product ID is missing");
        return false;
    }
    var layout = "";
    var prefix = "";
    var acc_subatt_price_final = 0;
    var acc_price_total = 0;
    var acc_price_withoutvat = 0;
    var acc_sub_price_withoutvat = 0;
    var price = 0;
    var selid = 0;
    var tmpprice = 0;
    var myaccall = new Array();
    var myaccqua = new Array();
    var myattall = new Array();
    var mypropall = new Array();
    var mysubpropall = new Array();


    if (document.getElementById('isAjaxBoxOpen')) {
        layout = document.getElementById('isAjaxBoxOpen').value;
    }
    if (layout == "viewajaxdetail") {
        prefix = "ajax_";
    }
    // elements
    if (document.getElementsByName("accessory_id_" + prefix + product_id + "[]")) {
        dcatt = document.getElementsByName("accessory_id_" + prefix + product_id + "[]");
        if (document.getElementById("giftcard_id")) {
            if (document.getElementById("giftcard_id").value > 0) {
                if (dcatt.length == 0) {
                    return true;
                }
            }
        }
        var total_accessory = (dcatt.length);
        for (j = 0; j < total_accessory; j++) {
            var my_acc_fprice = 0;
            var my_acc_withoutvat_price = 0;
            var accessory_id = dcatt[j].value;
            var commonid = prefix + product_id + '_' + accessory_id;
            var accchkchecked = 0;
            var attribute_id = 0;
            acc_chk = document.getElementById("accessory_id_" + commonid);
            if (document.getElementById("attribute_id_" + commonid)) {
                attribute_id = document.getElementById("attribute_id_" + commonid);
            }
            accchkchecked = dcatt[j].checked;
            var accQuan = 1;
            if (relatedprd_id != 0) {
                qid = relatedprd_id;
            } else {
                qid = product_id;
            }
            if (document.getElementById("accquantity_" + commonid)) {
                accQuan = document.getElementById("accquantity_" + commonid).value;
            }
            else if (document.getElementById('quantity' + qid) && document.getElementById('quantity' + qid).type == "select-one") {
                accQuan = document.getElementById('quantity' + qid).value;
            } else {
                accQuan = 1;
            }
            if (accchkchecked) {
                myaccall[selid] = accessory_id;
                myaccqua[selid] = accQuan;
                collectAttributes(product_id, accessory_id, relatedprd_id);
                if (document.getElementById("accessory_price")) {
                    my_acc_fprice = parseFloat(document.getElementById("accessory_price").value);
                }
                if (document.getElementById("accessory_price_withoutvat")) {
                    my_acc_withoutvat_price = parseFloat(document.getElementById("accessory_price_withoutvat").value);
                }
                if (document.getElementById("acc_attribute_data")) {
                    myattall[selid] = document.getElementById("acc_attribute_data").value;
                }
                if (document.getElementById("acc_property_data")) {
                    mypropall[selid] = document.getElementById("acc_property_data").value;
                }
                if (document.getElementById("acc_subproperty_data")) {
                    mysubpropall[selid] = document.getElementById("acc_subproperty_data").value;
                }
                if (document.getElementById("divaccstatus" + commonid)) {
                    document.getElementById("divaccstatus" + commonid).className = 'accessorystatus added';
                }
                selid++;
            }
            else {
                if (document.getElementById("divaccstatus" + commonid)) {
                    document.getElementById("divaccstatus" + commonid).className = 'accessorystatus';
                }
            }

            acc_price_total += (parseFloat(my_acc_fprice) * accQuan);
            acc_price_withoutvat += (parseFloat(my_acc_withoutvat_price) * accQuan);
        }
        acc_subatt_price_final += parseFloat(acc_price_total);
        acc_sub_price_withoutvat += parseFloat(acc_price_withoutvat);
        if (document.getElementById("accessory_data")) {
            document.getElementById("accessory_data").value = myaccall.join("@@");
        }
        if (document.getElementById("acc_quantity_data")) {
            document.getElementById("acc_quantity_data").value = myaccqua.join("@@");
        }
        if (document.getElementById("acc_attribute_data")) {
            document.getElementById("acc_attribute_data").value = myattall.join("@@");
        }
        if (document.getElementById("acc_property_data")) {
            document.getElementById("acc_property_data").value = mypropall.join("@@");
        }
        if (document.getElementById("acc_subproperty_data")) {
            document.getElementById("acc_subproperty_data").value = mysubpropall.join("@@");
        }
        if (document.getElementById("accessory_price")) {
            document.getElementById("accessory_price").value = acc_subatt_price_final;
        }
        if (document.getElementById("accessory_price_withoutvat")) {
            document.getElementById("accessory_price_withoutvat").value = acc_sub_price_withoutvat;
        }

    }
    return acc_subatt_price_final;
}

// formatting number
function number_format(number, decimals, dec_point, thousands_sep) {

    var n = number, prec = decimals;

    // converting price
    n *= CURRENCY_CONVERT;


    var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return (Math.round(n * k) / k).toString();
    };

    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
    var dec = (typeof dec_point === 'undefined') ? '.' : dec_point;

    var s = (prec > 0) ? toFixedFix(n, prec) : toFixedFix(Math.round(n), prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;
    var abs = toFixedFix(Math.abs(n), prec);
    var _, i;

    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;

        _[0] = s.slice(0, i + (n < 0)) +
            _[0].slice(i).replace(/(\d{3})/g, sep + '$1');
        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }

    var decPos = s.indexOf(dec);
    if (prec >= 1 && decPos !== -1 && (s.length - decPos - 1) < prec) {
        s += new Array(prec - (s.length - decPos - 1)).join(0) + '0';
    }
    else if (prec >= 1 && decPos === -1) {
        s += dec + new Array(prec).join(0) + '0';
    }


    // setting final price with currency Symbol
    var display_price = "";


    if (CURRENCY_SYMBOL_POSITION == 'front') {
        display_price = CURRENCY_SYMBOL_CONVERT + s;
    } else if (CURRENCY_SYMBOL_POSITION == 'behind') {
        display_price = s + CURRENCY_SYMBOL_CONVERT;
    } else if (CURRENCY_SYMBOL_POSITION == 'none') {
        display_price = s;
    } else {
        display_price = CURRENCY_SYMBOL_CONVERT + s;
    }

    return display_price;
}

function getPriceReplacement(product_price) {
    var ret = "";
    if (SHOW_PRICE == "0") {
        url = PRICE_REPLACE_URL;
        if (url == "") {
            url = "#";
        }
        ret = "<a href='" + url + "'>" + PRICE_REPLACE + "</a>";
    }
    if (SHOW_PRICE == "1" && product_price == 0) {
        url = ZERO_PRICE_REPLACE_URL;
        if (url == "") {
            url = "#";
        }
        ret = "<a href='" + url + "'>" + ZERO_PRICE_REPLACE + "</a>";
    }
    return ret;
}

function setWrapper(id, price, price_withoutvat, product_id) {
    if (document.getElementById("wrapper_id")) {
        document.getElementById("wrapper_id").value = id;
    }
    if (document.getElementById("sel_wrapper_id")) {
        document.getElementById("sel_wrapper_id").value = id;
    }
    document.getElementById("wrapper_price").value = price;
    document.getElementById("wrapper_price_withoutvat").value = price_withoutvat;
    calculateTotalPrice(product_id, 0);
}

function setPropImage(product_id, propertyObj, selValue) {
    var propName = document.getElementById(propertyObj);

    if (propName) {
        if (propName.type == 'checkbox' || propName.type == 'radio') {
            var propNameObj = document.getElementsByName(propertyObj + "[]");
            for (var p = 0; p < propNameObj.length; p++) {
                var newval = propNameObj[p].value;
                if (newval == selValue) {
                    propNameObj[p].checked = true;
                }
            }
        } else {
            for (var p = 0; p < propName.options.length; p++) {
                var newval = propName.options[p].value;
                if (newval == selValue) {
                    propName.options[p].selected = true;
                }
            }
        }
    }
}

function setSubpropImage(product_id, subpropertyObj, selValue) {
    var subpropName = document.getElementById(subpropertyObj);
    if (subpropName) {
        if (subpropName.type == 'checkbox' || subpropName.type == 'radio') {
            var subpropNameObj = document.getElementsByName(subpropertyObj + "[]");
            for (var p = 0; p < subpropNameObj.length; p++) {
                var newval = subpropNameObj[p].value;
                if (subpropNameObj[p].value == selValue) {
                    subpropNameObj[p].checked = true;
                }
            }
        } else {
            for (var p = 0; p < subpropName.options.length; p++) {
                var newval = subpropName.options[p].value;
                if (subpropName.options[p].value == selValue) {
                    subpropName.options[p].selected = true;
                }
            }
        }
    }
}

function setPropertyImage(product_id, propertyObj) {
    //alert('Please wait while calculating price');
    var selValue = 0;
    var propName = document.getElementById(propertyObj);

    if (propName) {
        if (propName.type == 'checkbox' || propName.type == 'radio') {
            var propNameObj = document.getElementsByName(propertyObj + "[]");
            for (var p = 0; p < propNameObj.length; p++) {
                var borderstyle = "";
//				var borderpadding = "";
                selValue = propNameObj[p].value;
                if (propNameObj[p].checked) {
                    borderstyle = "1px solid";
//					borderpadding = "7px";
                }
                if (document.getElementById(propertyObj + "_propimg_" + selValue)) {

                    document.getElementById(propertyObj + "_propimg_" + selValue).style.border = borderstyle;
//					document.getElementById(propertyObj+"_propimg_"+selValue).style.padding = borderpadding;
                }
            }
        } else {

            for (var p = 0; p < propName.length; p++) {

                var borderstyle = "";
//				var borderpadding = "";
                selValue = propName[p].value;

                if (propName[propName.selectedIndex].value == selValue) {
                    borderstyle = "1px solid";
//					borderpadding = "7px";
                }
                if (document.getElementById(propertyObj + "_propimg_" + selValue)) {

                    document.getElementById(propertyObj + "_propimg_" + selValue).style.border = borderstyle;
//					document.getElementById(propertyObj+"_propimg_"+selValue).style.padding = borderpadding;
                }
            }

        }
    }
}

function setSubpropertyImage(product_id, subpropertyObj, selValue) {
    var selValue = 0;
    var subpropName = document.getElementById(subpropertyObj);
    if (subpropName) {
        if (subpropName.type == 'checkbox' || subpropName.type == 'radio') {
            var subpropNameObj = document.getElementsByName(subpropertyObj + "[]");
            for (var p = 0; p < subpropNameObj.length; p++) {
                var borderstyle = "";
//				var borderpadding = "";
                selValue = subpropNameObj[p].value;
                if (subpropNameObj[p].checked) {
                    borderstyle = "1px solid";
//					borderpadding = "7px";
                }
                if (document.getElementById(subpropertyObj + "_subpropimg_" + selValue)) {
                    document.getElementById(subpropertyObj + "_subpropimg_" + selValue).style.border = borderstyle;
//					document.getElementById(subpropertyObj+"_subpropimg_"+selValue).style.padding = borderpadding;
                }
            }
        } else {
            for (var p = 0; p < subpropName.length; p++) {

                var borderstyle = "";
//				var borderpadding = "";
                selValue = subpropName[p].value;
                if (subpropName[subpropName.selectedIndex].value == selValue) {
                    borderstyle = "1px solid";
//					borderpadding = "7px";
                }
                if (document.getElementById(subpropertyObj + "_subpropimg_" + selValue)) {
                    document.getElementById(subpropertyObj + "_subpropimg_" + selValue).style.border = borderstyle;
                    //document.getElementById('subproperty_main_outer').style.width = "104px";
                }
            }

        }
    }
}

function displayAdditionalImage(product_id, accessory_id, relatedprd_id, selectedproperty_id, selectedsubproperty_id) {
    var suburl = "&product_id=" + product_id;
    suburl = suburl + "&accessory_id=" + accessory_id;
    suburl = suburl + "&relatedprd_id=" + relatedprd_id;
    suburl = suburl + "&property_id=" + selectedproperty_id;
    suburl = suburl + "&subproperty_id=" + selectedsubproperty_id;
    var txtresponse = "";
    if (accessory_id != 0) {
        prefix = "acc_";
        product_id = accessory_id;
    } else if (relatedprd_id != 0) {
        prefix = "rel_";
    } else {
        prefix = "prd_";
    }
    collectAttributes(product_id, 0, relatedprd_id);

    if (document.getElementById('property_data')) {
        var property_data = document.getElementById('property_data').value;
        suburl = suburl + "&property_data=" + encodeURIComponent(property_data);
    }
    if (document.getElementById('subproperty_data')) {
        var subproperty_data = document.getElementById('subproperty_data').value;
        suburl = suburl + "&subproperty_data=" + encodeURIComponent(subproperty_data);
    }
    if (document.getElementById(prefix + "main_imgwidth")) {
        suburl = suburl + "&main_imgwidth=" + parseInt(document.getElementById(prefix + "main_imgwidth").value);
    }
    if (document.getElementById(prefix + "main_imgheight")) {
        suburl = suburl + "&main_imgheight=" + parseInt(document.getElementById(prefix + "main_imgheight").value);
    }
    var changehref = 0;
    if (document.getElementById('a_main_image' + product_id) || document.getElementById('main_image' + product_id)) {
        if (document.getElementById('a_main_image' + product_id)) {
            var tmphref = document.getElementById('a_main_image' + product_id).href;
            tmphref = tmphref.split("");
            var newhref = tmphref.reverse();
            newhref = newhref.join("");
            tmphref = newhref.split(".");
            tmphref = tmphref[0].split("");
            newhref = tmphref.reverse();
            newhref = newhref.join("");
        }
        else {
            var tmphref = document.getElementById('main_image' + product_id).src;
            tmphref = tmphref.split("");
            var newhref = tmphref.reverse();
            newhref = newhref.join("");
            tmphref = newhref.split(".");
            tmphref = tmphref[0].split("");
            newhref = tmphref.reverse();
            newhref = newhref.join("");
            newhref = newhref.split("&");
            newhref = newhref[0];
        }


        // change extension to lowercase
        newhref = newhref.toLowerCase();

        if (newhref == "jpg" || newhref == "jpeg" || newhref == "png" || newhref == "gif" || newhref == "bmp") {
            changehref = 1;
        }
    }

    var url = site_url + "index.php?option=com_redshop&view=product&task=displayAdditionImage&redview=" + REDSHOP_VIEW + "&redlayout=" + REDSHOP_LAYOUT + "&tmpl=component";
    url = url + suburl;


    request = getHTTPObject();
    request.onreadystatechange = function () {

        // if request object received response

        if (request.readyState == 4) {
            txtresponse = request.responseText;
            var arrResponse = txtresponse.split("`_`");

            //alert(arrResponse);
            if (arrResponse[9] != "" && document.getElementById('product_number_variable' + product_id)) {
                document.getElementById('product_number_variable' + product_id).innerHTML = arrResponse[9];
            }
            subproperty_main_image = arrResponse[4];

            if (document.getElementById('a_main_image' + product_id)) {
                if (arrResponse[2] != "" && changehref == 1) {
                    document.getElementById('a_main_image' + product_id).href = arrResponse[2];
                }
                if (arrResponse[3] != "") {
                    document.getElementById('a_main_image' + product_id).title = arrResponse[3];
                }

                //if(arrResponse[4]!="")
                //{
                //if(PRODUCT_ADDIMG_IS_LIGHTBOX==1)
                //	document.getElementById('a_main_image'+product_id).innerHTML=arrResponse[4];
                //	else
                //	document.getElementById('a_main_image'+product_id).src=arrResponse[4];
                //}
                if (arrResponse[4] != "") {
                    if (PRODUCT_ADDIMG_IS_LIGHTBOX == 1 && REDSHOP_VIEW == "product") {
                        document.getElementById('a_main_image' + product_id).innerHTML = arrResponse[4];
                    }
                    else if (REDSHOP_VIEW == "category") {
                        document.getElementById('a_main_image' + product_id).innerHTML = arrResponse[4];
                    }
                    else {
                        if (document.getElementById('main_image' + product_id) && arrResponse[4] != "") {
                            document.getElementById('main_image' + product_id).src = arrResponse[4];
                        }
                    }
                }
            }
            else {

                if (arrResponse[4] != "") {
                    if (document.getElementById('main_image' + product_id) && arrResponse[4] != "") {
                        document.getElementById('main_image' + product_id).src = arrResponse[4];
                    }
                }
            }

            if (document.getElementById('additional_images' + product_id) && arrResponse[1] != "") {
                document.getElementById('additional_images' + product_id).innerHTML = arrResponse[1];
            }
            if (document.getElementById('hidden_attribute_cartimage' + product_id)) {
                document.getElementById('hidden_attribute_cartimage' + product_id).value = arrResponse[12];
            }
//			alert(arrResponse[6]);
            if (document.getElementById('stockImage' + product_id) && arrResponse[5] != "") {
                document.getElementById('stockImage' + product_id).src = arrResponse[5];
            }
            if (document.getElementById('stockImageTooltip' + product_id) && arrResponse[6] != "") {
                document.getElementById('stockImageTooltip' + product_id).innerHTML = arrResponse[6];
            }
            if (document.getElementById('displayProductInStock' + product_id) && arrResponse[10] != "") {
                document.getElementById('displayProductInStock' + product_id).innerHTML = arrResponse[10];
            }
            if (document.getElementById('ProductAttributeMinDelivery' + product_id) && arrResponse[7] != "") {
                document.getElementById('ProductAttributeMinDelivery' + product_id).innerHTML = arrResponse[7];
            }

            if (document.getElementById('stock_status_div' + product_id) && arrResponse[11] != "") {

                document.getElementById('stock_status_div' + product_id).innerHTML = arrResponse[11];
            }

            if (document.getElementById('notify_stock' + product_id)) {

                document.getElementById('notify_stock' + product_id).innerHTML = arrResponse[13];
            }
            if (document.getElementById('stock_availability_date_lbl' + product_id)) {
                document.getElementById('stock_availability_date_lbl' + product_id).innerHTML = arrResponse[14];
            }
            if (document.getElementById('stock_availability_date' + product_id)) {
                document.getElementById('stock_availability_date' + product_id).innerHTML = arrResponse[15];
            }

            // preload slimbox
            var imagehandle = {isenable: true, mainImage: false};
            preloadSlimbox(imagehandle);

        }
    };
    request.open("GET", url, true);
    request.send(null);
}

/**
 * This is an override function to load lightbox and modal
 * This function can be override by any none redSHOP javascript.
 * Initially this function will load core redbox(joomla sqeezebox)
 * @return
 */
function preloadSlimbox(parameters) {

    if (parameters.isenable) {
        var imgoptions = {handler: 'image'};
        redBOX.initialize({});
        if (parameters.mainImage)
            redBOX.assign($$("a[rel='myallimg']"), imgoptions);
        else
            redBOX.assign($$(".additional_image > a[rel='myallimg']"), imgoptions);

    }
}

function setWrapperComboBox() {
    if (document.getElementById("wrapper_id") && document.getElementById("sel_wrapper_id")) {
        document.getElementById("sel_wrapper_id").value = document.getElementById("wrapper_id").value;
    }
    var obj = document.getElementsByName("w_price");
    var id = 0;
    if (document.getElementById("sel_wrapper_id")) {
        id = document.getElementById("sel_wrapper_id").value;
    }
    var wprice = 0;
    var wprice_withoutvat = 0;
    if (document.getElementById("wrapper_check") && document.getElementById("wrapper_check").checked) {
        if (id != 0) {
            wprice = document.getElementById("w_price" + id).value;
            wprice_withoutvat = document.getElementById("w_price_withoutvat" + id).value;
        }
    }
    if (document.getElementById("wrapper_price")) {
        document.getElementById("wrapper_price").value = wprice;
    }
    if (document.getElementById("wrapper_price_withoutvat")) {
        document.getElementById("wrapper_price_withoutvat").value = wprice_withoutvat;
    }
    for (i = 0; i < obj.length; i++) {
        var tmpval = obj[i].id.substr(7);//obj[i].value;
        if (document.getElementById("wrappertd" + tmpval)) {
            if (tmpval == id) {
                document.getElementById("wrappertd" + tmpval).style.border = "1px solid";
                document.getElementById("wrappertd" + tmpval).style.padding = "7px";
            } else {
                document.getElementById("wrappertd" + tmpval).style.border = "";
                document.getElementById("wrappertd" + tmpval).style.padding = "";
            }
        }
    }
}

/*
 * ajax function for calculatin of discount
 */
function discountCalculation(proid) {
    var calHeight = 0, calWidth = 0, calDepth = 0, calRadius = 0, calUnit = 'cm', globalcalUnit = 'cm', total_area = '', price_per_area = 0, price_per_piece = 0, output = "", price_total = 0;

    /*if(document.getElementById('product_id')){
     proid = document.getElementById('product_id').value;
     }else{
     alert("No Add to cart Available");
     return false;
     }*/

    if (document.getElementById('calc_height')) {
        calHeight = document.getElementById('calc_height').value;
        if (calHeight == "") {
            alert(COM_REDSHOP_PLEASE_INSERT_HEIGHT);
            return false;
        }

    }

    if (document.getElementById('calc_width')) {
        calWidth = document.getElementById('calc_width').value;
        if (calWidth == "") {
            alert(COM_REDSHOP_PLEASE_INSERT_WIDTH);
            return false;
        }
    }

    if (document.getElementById('calc_depth')) {
        calDepth = document.getElementById('calc_depth').value;
        if (calDepth == "") {
            alert(COM_REDSHOP_PLEASE_INSERT_DEPTH);
            return false;
        }
    }

    if (document.getElementById('calc_radius')) {
        calRadius = document.getElementById('calc_radius').value;
        if (calRadius == "") {
            alert(COM_REDSHOP_PLEASE_INSERT_RADIUS);
            return false;
        }
    }

    if (document.getElementById('discount_calc_unit')) {
        calUnit = document.getElementById('discount_calc_unit').value;
        if (calUnit == 0) {
            alert(COM_REDSHOP_PLEASE_INSERT_UNIT);
            return false;
        }
    }

    if (document.getElementById('calc_unit')) {
        globalcalUnit = document.getElementById('calc_unit').value;
    }

    // new extra enhancement of discount calculator added
    var pdcoptionid = new Array();
    if (document.getElementsByName('pdc_option_name[]')) {

        var pdcoptions = document.getElementsByName('pdc_option_name[]');
        var opk = 0;
        for (var op = 0; op < pdcoptions.length; op++) {
            var pdcoption = pdcoptions[op];
            if (pdcoption.checked) {
                pdcoptionid[opk] = pdcoption.value;
                opk++;
            }
        }
    }
    pdcoptionid = pdcoptionid.join(",");
    // End

    http = getHTTPObject();

    if (http == null) {
        alert("Your browser does not support XMLHTTP!");
        return;
    }

    //alert("calHeight"+calHeight+"calWidth"+calWidth+"calDepth"+calDepth+"calRadius"+calRadius);

    http.onreadystatechange = function () {
        if (http.readyState == 4) {

            var areaPrice = http.responseText;

            //var areaPrice = responce.split("~");
            areaPrice = areaPrice.replace(/^\s+|\s+$/g, "");

            if (areaPrice == "fail") {

                alert(COM_REDSHOP_NOT_AVAILABLE);
                return false;
            } else {

                areaPrice = areaPrice.split("\n");

                // get quantity

                var eld = document.getElementsByName('quantity'), qty = 1;
                for (var g = 0; g < eld.length; g++) {

                    if (eld[g].id == 'ajax_quantity' + proid) {

                        qty = eld[g].value;
                    } else {
                        if (eld[g].id == 'quantity' + proid) {

                            qty = eld[g].value;
                        }
                    }
                }
                // end


                total_area = areaPrice[0];

                price_per_area = areaPrice[1];

                price_per_piece = areaPrice[2];
                price_excl_vat = areaPrice[7];


                // format numbers
                var formatted_price_per_area = number_format(price_per_area, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);

                var formatted_price_per_piece = number_format(price_per_piece, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);

                if (qty <= 0)
                    qty = 1;

                price_total = parseFloat(price_per_piece) * qty;

                var formatted_price_total = number_format(price_total, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);


                output = areaPrice[3] + total_area + "<br />";
                output += areaPrice[4] + formatted_price_per_area + "<br />";
                output += areaPrice[5] + formatted_price_per_piece + "<br />";
                output += areaPrice[6] + formatted_price_total;


                if (document.getElementById('discount_cal_final_price')) {
                    document.getElementById('discount_cal_final_price').innerHTML = output;
                }

                if (document.getElementById('main_price' + proid)) {
                    var product_main_price = document.getElementById('main_price' + proid).value;

                    calculateTotalPrice(proid, 0);

                    if (SHOW_PRICE == '1' && ( DEFAULT_QUOTATION_MODE != '1' || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))) {


                        var product_total = final_price_f - parseFloat(product_main_price) + parseFloat(price_total);

                        if (areaPrice[8] == 1) {
                            var product_price_excl_vat = price_total + price_excl_vat * qty;
                        } else {
                            var product_price_excl_vat = price_total * qty;
                        }

                        formatted_price_total = number_format(product_total, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
                        formatted_product_price_excl_vat = number_format(product_price_excl_vat, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
                        if (document.getElementById('produkt_kasse_hoejre_pris_indre' + proid)) {
                            document.getElementById('produkt_kasse_hoejre_pris_indre' + proid).innerHTML = formatted_product_price_excl_vat;
                            if (document.getElementById('display_product_price_no_vat' + proid))
                                document.getElementById('display_product_price_no_vat' + proid).innerHTML = formatted_price_total;
                            if (document.getElementById('product_price_no_vat' + proid))
                                document.getElementById('product_price_no_vat' + proid).value = product_total;
                        }

                        if (document.getElementById('product_price_incl_vat' + proid)) {
                            document.getElementById('product_price_incl_vat' + proid).innerHTML = formatted_product_price_excl_vat;
                        }

                        // set product main price as price total for dynamic price change
                        document.getElementById('main_price' + proid).value = product_price_excl_vat;
                    }
                }
            }
        }
    };

    http.open("GET", site_url + "index.php?option=com_redshop&view=cart&task=discountCalculator&product_id=" + proid + "&calcHeight=" + calHeight + "&calcWidth=" + calWidth + "&calcDepth=" + calDepth + "&calcRadius=" + calRadius + "&calcUnit=" + calUnit + "&pdcextraid=" + pdcoptionid + "&tmpl=component", true);
    http.send(null);
}

function setProductUserFieldImage(id, prodid, value1, ele) {
    var imgLength = document.getElementsByClassName('imgClass_' + prodid);

    for (var i = 0; i < imgLength.length; i++) {
        removeClass(imgLength[i], 'selectedimg');
    }
    if (document.getElementById(id + '_' + prodid)) {
        document.getElementById(id + '_' + prodid).value = value1;
        ele.className += ' selectedimg';
    }
    if (document.getElementById('ajax' + id + '_' + prodid)) {
        document.getElementById('ajax' + id + '_' + prodid).value = value1;
    }
    if (document.getElementById(id)) {
        document.getElementById(id).value = value1;
    }
}

function removeClass(ele, cls) {
    if (hasClass(ele, cls)) {
        var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
        ele.className = ele.className.replace(reg, ' ');
    }
}

function hasClass(ele, cls) {
    return ele.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}

/*
 * function to support ie too
 */
function RedgetElementsByClassName(xx) {
    var rl = new Array();
    var ael = document.all ? document.all : document.getElementsByTagName('*');
    for (i = 0, j = 0; i < ael.length; i++) {
        if ((ael[i].className == xx)) {
            rl[j] = ael[i];
            j++;
        }
    }
    return rl;
}
function getElementsByClassName(xx) {
    var rl = new Array();
    var ael = document.all ? document.all : document.getElementsByTagName('*');
    for (i = 0, j = 0; i < ael.length; i++) {
        if ((ael[i].className == xx)) {
            rl[j] = ael[i];
            j++;
        }
    }
    return rl;
}

function displayAddtocartForm(frmCartName, product_id, relatedprd_id, giftcard_id, frmUserfieldName) {

    var str = '';
    var elem = "";

    if (document.getElementById(frmUserfieldName)) {
        elem = document.getElementById(frmUserfieldName).elements;
    }

    if (product_id == 0 || product_id == "") {
//		alert("Product ID is missing");
        return false;
    }

    var arrcheckbox = new Array();
    var fieldname = new Array();
    var arSelected = new Array();
    var fieldNamefrmId = "";

    for (var i = 0; i < elem.length; i++) {
        if (elem[i].type == "checkbox" && elem != "" || elem[i].type == "radio" && elem != "") {
            if (elem[i].checked == true) {
                arrcheckbox[i] = elem[i].value;
                fieldname[i] = elem[i].name;
                var elements = document.getElementById(frmCartName).elements;
                fieldNamefrmId = reverseString(elem[i].id);
                fieldNamefrmId = reverseString(fieldNamefrmId.substr(fieldNamefrmId.indexOf("_") + 1));
                for (var j = 0; j < elements.length; j++) {
//					if(elem[i].name == elements[j].name)
                    if (fieldNamefrmId == elements[j].name) {
                        var strval = elements[j].value;

                        if (strval.search(arrcheckbox[i]) == -1) {
                            if (elements[j].value != "")
                                elements[j].value += ",";
                            elements[j].value += arrcheckbox[i];
                        }
                    }
                }
            }
        } else if (elem[i].type == "select-one") {
            arrcheckbox[i] = elem[i].value;
            fieldname[i] = elem[i].name;
            var elements = document.getElementById(frmCartName).elements;
            for (var j = 0; j < elements.length; j++) {
                fieldNamefrmId = elem[i].id;
                //if(elem[i].name == elements[j].name)
                if (fieldNamefrmId == elements[j].name) {
                    var strval = elements[j].value;
                    if (strval.search(arrcheckbox[i]))
                        elements[j].value += arrcheckbox[i];
                }
            }
        } else if (elem[i].type == "select-multiple") {
            var ob = elem[i];
            elements = document.getElementById(frmCartName).elements;

            for (var t = 0; t < ob.options.length; t++) {
                if (ob.options[ t ].selected) {
                    for (var j = 0; j < elements.length; j++) {
                        fieldNamefrmId = elem[i].id;
                        //if(elem[i].name == elements[j].name)
                        if (fieldNamefrmId == elements[j].name) {
                            var strval = elements[j].value;
                            if (strval.search(String(ob.options[ t ].value)) == -1) {
                                if (elements[j].value != "")
                                    elements[j].value += ",";
                                elements[j].value += (String(ob.options[ t ].value));
                            }
                        }
                    }
                }
            }
        }
    }

    var cal_el = RedgetElementsByClassName('calendar');

    var cal_fieldNamefrmId = "";
    for (cal_i = 0; cal_i < cal_el.length; cal_i++) {
        // do stuff here with myEls[i]
        var calImgId = cal_el[cal_i].id;
        arr = calImgId.split("_img");
        n = arr.length;
        var calName = arr[0];
        if (calName != "" && calName.search(frmCartName)) {
            if (document.getElementById(calName).value != "") {
                cal_fieldNamefrmId = reverseString(calName);
                cal_fieldNamefrmId = reverseString(cal_fieldNamefrmId.substr(cal_fieldNamefrmId.indexOf("_") + 1));
                //			if(document.getElementById(cal_fieldNamefrmId))
                //			document.getElementById(cal_fieldNamefrmId).value = document.getElementById(calName).value;
                var frm_name = String(frmCartName);
                var elements = document.getElementById(frm_name).elements;
                var cfieldName = String(cal_fieldNamefrmId + '_' + product_id);

                for (var j = 0; j < elements.length; j++) {
                    if (cal_fieldNamefrmId == elements[j].name) {
                        elements[j].value = document.getElementById(cfieldName).value;
                    }
                }
            }
        }
    }
    if (document.getElementById(frmCartName) && document.getElementById('requiedAttribute')) {
        document.getElementById('requiedAttribute').value = document.getElementById(frmCartName).requiedAttribute.getAttribute('reattribute');
    }
    if (document.getElementById(frmCartName) && document.getElementById('requiedProperty')) {
        document.getElementById('requiedProperty').value = document.getElementById(frmCartName).requiedProperty.getAttribute('reproperty');
    }
    if (giftcard_id == 0) {
        //get selected attribute,property,subproperty data and total price
        calculateTotalPrice(product_id, relatedprd_id);
    }

    //set selected attribute,property,subproperty data and total price to Add to cart form
    if (!setAddtocartForm(frmCartName, product_id))
        return false;

    return true;
}

function setAddtocartForm(frmCartName, product_id) {
    var frm = document.getElementById(frmCartName);

    if (document.getElementById('Itemid')) {
        frm.Itemid.value = document.getElementById('Itemid').value;
//		alert("Itemid= " + frm.Itemid.value);
    }
    if (document.getElementById('attribute_data')) {
        frm.attribute_data.value = document.getElementById('attribute_data').value;
//		alert("attribute_data= " + frm.attribute_data.value);
    }
    if (document.getElementById('property_data')) {
        frm.property_data.value = document.getElementById('property_data').value;
//		alert("property_data= " + frm.property_data.value);
    }
    if (document.getElementById('subproperty_data')) {
        frm.subproperty_data.value = document.getElementById('subproperty_data').value;
//		alert("subproperty_data= " + frm.subproperty_data.value);
    }
    if (document.getElementById('accessory_data')) {
        frm.accessory_data.value = document.getElementById('accessory_data').value;
//		alert("accessory_data= " + frm.accessory_data.value);
    }
    if (document.getElementById('acc_quantity_data')) {
        frm.acc_quantity_data.value = document.getElementById('acc_quantity_data').value;
//		alert("acc_quantity_data= " + frm.acc_quantity_data.value);
    }
    if (document.getElementById('acc_attribute_data')) {
        frm.acc_attribute_data.value = document.getElementById('acc_attribute_data').value;
//		alert("acc_attribute_data= " + frm.acc_attribute_data.value);
    }
    if (document.getElementById('acc_property_data')) {
        frm.acc_property_data.value = document.getElementById('acc_property_data').value;
//		alert("acc_property_data= " + frm.acc_property_data.value);
    }
    if (document.getElementById('acc_subproperty_data')) {
        frm.acc_subproperty_data.value = document.getElementById('acc_subproperty_data').value;
//		alert("acc_subproperty_data= " + frm.acc_subproperty_data.value);
    }
    if (document.getElementById('accessory_price')) {
        frm.accessory_price.value = document.getElementById('accessory_price').value;
//		alert("accessory_price= " + frm.accessory_price.value);
    }
    if (document.getElementById('requiedAttribute')) {
        frm.requiedAttribute.value = document.getElementById('requiedAttribute').value;
//		alert("requiedAttribute= " + frm.requiedAttribute.value);
    }
    if (document.getElementById('requiedProperty')) {
        frm.requiedProperty.value = document.getElementById('requiedProperty').value;
//		alert("requiedProperty= " + frm.requiedProperty.value);
    }

    var product_quantity = 1;
    if (document.getElementById('quantity' + product_id).value) {
        product_quantity = document.getElementById('quantity' + product_id).value;
    }
    if (document.getElementById('hidden_attribute_cartimage' + product_id)) {
        frm.hidden_attribute_cartimage.value = document.getElementById('hidden_attribute_cartimage' + product_id).value;
    }

    if (parseInt(frm.min_quantity.value) != 0 && parseInt(frm.min_quantity.value) > product_quantity) {
        alert(frm.min_quantity.getAttribute('requiredtext') + " " + parseInt(frm.min_quantity.value));
        return false;
    }

    if (parseInt(frm.max_quantity.value) != 0 && parseInt(frm.max_quantity.value) < product_quantity) {
        alert(frm.max_quantity.getAttribute('requiredtext') + " " + parseInt(frm.max_quantity.value));
        return false;
    }

    if (document.getElementById('calc_height')) {
        var calHeight = document.getElementById('calc_height').value;

        if (calHeight == "") {
            alert(COM_REDSHOP_PLEASE_INSERT_HEIGHT);
            return false;
        } else {
            frm.calcHeight.value = calHeight;
        }

    }

    if (document.getElementById('calc_width')) {
        var calWidth = document.getElementById('calc_width').value;
        if (calWidth == "") {
            alert(COM_REDSHOP_PLEASE_INSERT_WIDTH);
            return false;
        } else {
            frm.calcWidth.value = calWidth;
        }
    }

    if (document.getElementById('calc_depth')) {
        var calDepth = document.getElementById('calc_depth').value;
        if (calDepth == "") {
            alert(COM_REDSHOP_PLEASE_INSERT_DEPTH);
            return false;
        } else {
            frm.calcDepth.value = calDepth;
        }
    }

    if (document.getElementById('calc_radius')) {
        var calRadius = document.getElementById('calc_radius').value;
        if (calRadius == "") {
            alert(COM_REDSHOP_PLEASE_INSERT_RADIUS);
            return false;
        } else {
            frm.calcRadius.value = calRadius;
        }
    }

    if (document.getElementById('discount_calc_unit')) {
        calUnit = document.getElementById('discount_calc_unit').value;
        if (calUnit == 0) {
            alert(COM_REDSHOP_PLEASE_INSERT_UNIT);
            return false;
        } else {
            frm.calcUnit.value = calUnit;
        }
    }

    // new extra enhancement of discount calculator added
    var pdcoptionid = new Array();
    if (document.getElementsByName('pdc_option_name[]')) {

        var pdcoptions = document.getElementsByName('pdc_option_name[]');
        var opk = 0;
        for (var op = 0; op < pdcoptions.length; op++) {
            var pdcoption = pdcoptions[op];
            if (pdcoption.checked) {
                pdcoptionid[opk] = pdcoption.value;
                opk++;
            }
        }
    }
    pdcoptionid = pdcoptionid.join(",");
    frm.pdcextraid.value = pdcoptionid;
    // End

    if (document.getElementById('hidden_subscription_id')) {
        subId = document.getElementById('hidden_subscription_id').value;
        if (subId == 0) {
            alert(COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN);
            return false;
        } else {
            frm.subscription_id.value = subId;
        }
    }

    return true;
}

function checkAddtocartValidation(frmCartName, product_id, relatedprd_id, giftcard_id, frmUserfieldName, totAttribute, totAccessory, totUserfield) {


    if (product_id == 0 || product_id == "") {
//		alert("Product ID is missing");
        return false;
    }
    var prop_id_cart = "";

    var prop_id_cart_value = "";
    var subprop_id_cart = "";
    var subprop_id_cart_value = "";
    var attr_id = "";
    var subattr_id = "";
    var att_required = "";
    var att_name = "";
    var att_name_lebl = "";
    var att_error = "";
    var att_error_alert = false;
    var attreq = false;

    var arr_attr_id = new Array();
    var arr_subattr_id = new Array();
    var sel_i = 0;
    var sub_sel_i = 0;
    // User field validation

    if (AJAX_CART_BOX == 0) {
        var ret = userfieldValidation("extrafields" + product_id);
        if (!ret) {
            return false;
        }
        var requiedAttribute = document.getElementById(frmCartName).requiedAttribute.value;
        var requiedProperty = document.getElementById(frmCartName).requiedProperty.value;

        if (requiedAttribute != "") {
            alert(requiedAttribute);
            return false;
        }
        if (requiedProperty != "") {
            alert(requiedProperty);
            return false;
        }
        document.getElementById(frmCartName).submit();

    } else {
        /*
         * count total attribute + extra fields
         * Where natt = number of total attribute
         * And nextra = number of extra fields
         */
        var ntotal = parseInt(totAttribute) + parseInt(totAccessory) + parseInt(totUserfield);
//		alert(parseInt(totAttribute) + " = "+ parseInt(totAccessory) + " = " + parseInt(totUserfield));
        // submit form from product detail page
        /*
         * ntotal = count total attribute + extra fields
         * if attribute is not available then cart will submit directly
         *
         */
        if (giftcard_id != 0) {
            submitAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield);
        }
        else {
            if (ntotal > 0) {
                displayAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield);
            } else {

                submitAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield);
            }
        }
    }
}

function displayAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield) {
    if (product_id == 0 || product_id == "") {
//		alert("Product ID is missing");
        return false;
    }
    var layout = "";
    if (document.getElementById('isAjaxBoxOpen')) {
        layout = document.getElementById('isAjaxBoxOpen').value;
    }
    var attdata = 0, setatt = 1, qty = 1, setacc = 0;
    // get form
    var formname = document.getElementById(frmCartName);

    // get multiple extra fields attributes
    var extrafields = document.getElementsByName('extrafields' + product_id + '[]');

    // intialized Userfield Name( comma seprated )
    var extrafieldNames = "";
    var previousfieldName = "";
    var fieldNamefrmId = "";
    var chk_flag = false;
    var rdo_previousfieldName = "";
    var rdo_fieldNamefrmId = "";
    var rdo_flag = false;
    var imgfieldNamefrmId = "";
    var selmulti_fieldNamefrmId = "";

    for (var ex = 0; ex < extrafields.length; ex++) {

        if (!extrafields[ex].value && extrafields[ex].type == 'text') {
            extrafieldNames += extrafields[ex].id; 	// make Id as Name
            if ((extrafields.length - 1) != ex) {
                extrafieldNames += ',';
            }
        }
        else if (!extrafields[ex].value && extrafields[ex].type == 'select-one') {
            extrafieldNames += extrafields[ex].id; 	// make Id as Name
            if ((extrafields.length - 1) != ex) {
                extrafieldNames += ',';
            }
        }
        else if (!extrafields[ex].value && extrafields[ex].type == 'hidden') {
            imgfieldNamefrmId = reverseString(extrafields[ex].id);
            imgfieldNamefrmId = reverseString(imgfieldNamefrmId.substr(imgfieldNamefrmId.indexOf("_") + 1));
            extrafieldNames += imgfieldNamefrmId; 	// make Id as Name
            if ((extrafields.length - 1) != ex) {
                extrafieldNames += ',';
            }
        }
        else if (extrafields[ex].type == 'checkbox') {
            fieldNamefrmId = reverseString(extrafields[ex].id);
            fieldNamefrmId = reverseString(fieldNamefrmId.substr(fieldNamefrmId.indexOf("_") + 1));

            if (previousfieldName != "" && previousfieldName != fieldNamefrmId && chk_flag == false) {
                extrafieldNames += previousfieldName + ",";
            }

            if (previousfieldName != fieldNamefrmId) {
                previousfieldName = fieldNamefrmId;
                chk_flag = false;

            }
            if (extrafields[ex].checked || chk_flag == true) {
                chk_flag = true;
                continue;
            }
            if ((ex == (extrafields.length - 1) && chk_flag == false) || (extrafields[ex + 1].type != 'checkbox' && chk_flag == false)) {
                extrafieldNames += previousfieldName + ",";
            }

        }
        else if (extrafields[ex].type == 'radio') {

            rdo_fieldNamefrmId = reverseString(extrafields[ex].id);
            rdo_fieldNamefrmId = reverseString(rdo_fieldNamefrmId.substr(rdo_fieldNamefrmId.indexOf("_") + 1));

            if (rdo_previousfieldName != "" && rdo_previousfieldName != rdo_fieldNamefrmId && rdo_flag == false) {
                extrafieldNames += rdo_previousfieldName + ",";
            }

            if (rdo_previousfieldName != rdo_fieldNamefrmId) {
                rdo_previousfieldName = rdo_fieldNamefrmId;
                rdo_flag = false;
            }
            if (extrafields[ex].checked || rdo_flag == true) {
                rdo_flag = true;
                continue;
            }
            else if ((ex == (extrafields.length - 1) && rdo_flag == false) || (extrafields[ex + 1].type != 'radio' && rdo_flag == false)) {
                extrafieldNames += rdo_previousfieldName + ",";
            }
        }
        else if (extrafields[ex].type == 'select-multiple') {

            selmulti_fieldNamefrmId = reverseString(extrafields[ex].id);
            selmulti_fieldNamefrmId = reverseString(selmulti_fieldNamefrmId.substr(selmulti_fieldNamefrmId.indexOf("_") + 1));

            if (extrafields[ex].value) {
                continue;
            }
            else {
                extrafieldNames += selmulti_fieldNamefrmId + ",";
            }
        }


    }
    /* for calender type
     * user field
     */
    var cal_el = RedgetElementsByClassName('calendar');

    var cal_fieldNamefrmId = "";
    for (cal_i = 0; cal_i < cal_el.length; cal_i++) {
        // do stuff here with myEls[i]
        var calImgId = cal_el[cal_i].id;

        arr = calImgId.split("_img");
        n = arr.length;
        var calName = arr[0];

        if (calName != "") {
            if (document.getElementById(calName).value == "") {
                cal_fieldNamefrmId = reverseString(calName);
                cal_fieldNamefrmId = reverseString(cal_fieldNamefrmId.substr(cal_fieldNamefrmId.indexOf("_") + 1));
                extrafieldNames += "," + cal_fieldNamefrmId + ",";
            }
        }
    }
    // End
    var subscription_data = "";
    if (document.getElementById('hidden_subscription_id')) {

        subId = document.getElementById('hidden_subscription_id').value;
        if (subId == 0 || subId == "") {
            alert(COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN);
            return false;
        }
        subscription_data = "&subscription_id=" + subId;
    }

    var product_quantity = 1;
    var params = "";
    request = getHTTPObject();
    if (document.getElementById('quantity' + product_id).value) {
        product_quantity = document.getElementById('quantity' + product_id).value;
    }
    var requiedAttribute = document.getElementById(frmCartName).requiedAttribute.value;
    var requiedProperty = document.getElementById(frmCartName).requiedProperty.value;
    var requiedAccessory = document.getElementById(frmCartName).accessory_data.value;

    if (extrafieldNames == 0) {
        totUserfield = 0;
    }
    if (requiedAttribute == "" && requiedProperty == "") {
        totAttribute = 0;
    }

    var accarr = new Array();
    if (totAccessory > 0 && requiedAccessory != "" && requiedAccessory != 0) {
        accarr = requiedAccessory.split("@@");
        if (totAccessory == accarr.length) {
            totAccessory = 0;
        }
    }
//	if(requiedAccessory!="")
//	{
//		totAccessory = 0;
//	}
//	alert("totAttribute=" + totAttribute + "totAccessory=" + totAccessory + "totUserfield=" + totUserfield);

    var ntotal = parseInt(totAttribute) + parseInt(totAccessory) + parseInt(totUserfield);
    var othertotal = parseInt(totAttribute) + parseInt(totUserfield);
    if ((totAccessory > 0 && othertotal == 0 && accarr.length > 0) || ntotal == 0 || layout == "viewajaxdetail") {
        submitAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield);
    }
    else {
        var sel_data = "&property_data=" + encodeURIComponent(document.getElementById(frmCartName).property_data.value);
        sel_data = sel_data + "&subproperty_data=" + encodeURIComponent(document.getElementById(frmCartName).subproperty_data.value);
        sel_data = sel_data + "&accessory_data=" + encodeURIComponent(document.getElementById(frmCartName).accessory_data.value);
        sel_data = sel_data + "&acc_quantity_data=" + encodeURIComponent(document.getElementById(frmCartName).acc_quantity_data.value);
        sel_data = sel_data + "&acc_property_data=" + encodeURIComponent(document.getElementById(frmCartName).acc_property_data.value);
        sel_data = sel_data + "&acc_subproperty_data=" + encodeURIComponent(document.getElementById(frmCartName).acc_subproperty_data.value);

        var params = "option=com_redshop&view=product&pid=" + product_id + "&relatedprd_id=" + relatedprd_id + "&layout=viewajaxdetail&product_quantity=" + product_quantity + "&tmpl=component&nextrafield=" + totUserfield + "&extrafieldNames=" + extrafieldNames + subscription_data + sel_data;

        var detailurl = site_url + "index.php?" + params;

        /*var options = {url:detailurl,handler:'ajax',size: {x: 500, y: 600}};

         redBOX.initialize({});
         document.attbox = redBOX.open(null,options);*/

        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                var responce = request.responseText;
                var options = {url: detailurl, handler: 'html', size: {x: parseInt(AJAX_DETAIL_BOX_WIDTH), y: parseInt(AJAX_DETAIL_BOX_HEIGHT)}, htmldata: responce};
                redBOX.initialize({});
                document.attbox = redBOX.open(null, options);

                // preload slimbox
                var imagehandle = {isenable: false, mainImage: false};
                preloadSlimbox(imagehandle);

                var el = RedgetElementsByClassName('calendar');

                for (i = 0; i < el.length; i++) {
                    // do stuff here with myEls[i]
                    var calImgId = el[i].id;
                    arr = calImgId.split("_img");
                    n = arr.length;
                    var calName = arr[0];
                    var realname = calName.split("ajax");

                    if ((calImgId.search('ajax') != -1) && (extrafieldNames.search(realname[0]) != -1)) {
                        window.addEvent('domready', function () {
                            Calendar.setup({
                                inputField: calName,     // id of the input field
                                ifFormat: "%d-%m-%Y",      // format of the input field

                                button: el[i].id,  // trigger for the calendar (button ID)
                                align: "Tl",           // alignment (defaults to "Bl")
                                singleClick: true
                            });
                        });
                    }
                }
            }
        };
        request.open("POST", detailurl, true);
        request.send(params);
    }

}

function submitAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield) {
    var frm = document.getElementById(frmCartName);

    var proid = 0;
    var priceval = 0;
    var mainpri = 0;
    var proppri = 0;
    var attpric = 0;
    var accpric = 0;
    var propcartid = 0;
    var subpropcartid = 0;
    var wrapperdata = "";
    var accdata = "";
    var attdata = "";
    var subattdata = "";
    var qty = 1;
    var id = '';
    var set = false;
    // calculator variables
    var calHeight = 0, calWidth = 0;

    // get multiple extra fields attributes
    var extrafields = document.getElementsByName('extrafields' + product_id + '[]');

    // intialize defaults
    var extrafieldName = "";
    var extrafieldVal = "";
    var extrafieldpost = "";

    var previousfieldName = "";
    var fieldNamefrmId = "";
    var chk_flag = false;
    var rdo_previousfieldName = "";
    var rdo_fieldNamefrmId = "";
    var rdo_flag = false;

    var selmulti_fieldNamefrmId = "";

    var ret = userfieldValidation("extrafields" + product_id);
    if (!ret) {
        return false;
    }
    var requiedAttribute = document.getElementById(frmCartName).requiedAttribute.value;
    var requiedProperty = document.getElementById(frmCartName).requiedProperty.value;

    if (requiedAttribute != 0 && requiedAttribute != "") {
        alert(requiedAttribute);
        return false;
    }
    if (requiedProperty != 0 && requiedProperty != "") {
        alert(requiedProperty);
        return false;
    }


    for (var ex = 0; ex < extrafields.length; ex++) {

        if (extrafields[ex].type == 'checkbox') {
            fieldNamefrmId = reverseString(extrafields[ex].id);
            fieldNamefrmId = reverseString(fieldNamefrmId.substr(fieldNamefrmId.indexOf("_") + 1));
            if (previousfieldName != "" && previousfieldName != fieldNamefrmId && extrafieldVal != "") {
                extrafieldpost += "&" + previousfieldName + "=" + extrafieldVal;
            }
            if (previousfieldName != fieldNamefrmId) {
                extrafieldVal = "";
                previousfieldName = fieldNamefrmId;
            }
            if (extrafields[ex].checked) {
                if (extrafieldVal != "")
                    extrafieldVal += ",";
                extrafieldVal += extrafields[ex].value;
            }
            if (ex == (extrafields.length - 1) && extrafieldVal != "") {
                extrafieldpost += "&" + fieldNamefrmId + "=" + extrafieldVal;
            }
            if (ex < (extrafields.length - 1)) {
                if ((extrafields[ex + 1].type != 'checkbox') && extrafieldVal != "")
                    extrafieldpost += "&" + fieldNamefrmId + "=" + extrafieldVal;
            }
        }
        else if (extrafields[ex].type == 'radio') {

            rdo_fieldNamefrmId = reverseString(extrafields[ex].id);
            rdo_fieldNamefrmId = reverseString(rdo_fieldNamefrmId.substr(rdo_fieldNamefrmId.indexOf("_") + 1));

            if (rdo_previousfieldName != "" && rdo_previousfieldName != rdo_fieldNamefrmId && rdo_flag == false) {
                extrafieldpost += "&" + rdo_previousfieldName + "=" + extrafieldVal;
            }

            if (rdo_previousfieldName != rdo_fieldNamefrmId) {
                extrafieldVal = "";
                rdo_previousfieldName = rdo_fieldNamefrmId;
                rdo_flag = false;
                if (extrafields[ex].checked || rdo_flag == true) {
                    rdo_flag = true;
                    extrafieldpost += "&" + rdo_previousfieldName + "=" + extrafields[ex].value;
                    continue;
                }
            }
            else {
                if (extrafields[ex].checked || rdo_flag == true) {
                    rdo_flag = true;
                    extrafieldpost += "&" + rdo_fieldNamefrmId + "=" + extrafields[ex].value;
                    continue;
                }
            }
        }
        else if (extrafields[ex].type == 'select-multiple') {
            var ob = extrafields[ex];
            extrafieldVal = "";
            selmulti_fieldNamefrmId = reverseString(extrafields[ex].id);
            selmulti_fieldNamefrmId = reverseString(selmulti_fieldNamefrmId.substr(selmulti_fieldNamefrmId.indexOf("_") + 1));
            for (var t = 0; t < ob.options.length; t++) {
                if (ob.options[ t ].selected) {
                    var strval = extrafieldVal;
                    if (strval.search(String(ob.options[ t ].value)) == -1) {
                        if (extrafieldVal != "")
                            extrafieldVal += ",";
                        extrafieldVal += (String(ob.options[ t ].value));
                    }
                }
            }

            if (extrafieldVal) {
                extrafieldpost += "&" + selmulti_fieldNamefrmId + "=" + extrafieldVal;
            }
        } else if (extrafields[ex].type == 'hidden') {
            imgfieldNamefrmId = reverseString(extrafields[ex].id);
            imgfieldNamefrmId = reverseString(imgfieldNamefrmId.substr(imgfieldNamefrmId.indexOf("_") + 1));

            extrafieldName = imgfieldNamefrmId; 	// make Id as Name
            extrafieldVal = extrafields[ex].value;	// get extra field value
            extrafieldpost += "&" + extrafieldName + "=" + extrafieldVal;
            extrafieldVal = "";
        }
        else {
            if (extrafields[ex].id.search('ajax') != -1) {
                var tmpName = extrafields[ex].id.split('ajax');
                var cal_fieldNamefrmId = "";
                cal_fieldNamefrmId = reverseString(tmpName[1]);
                cal_fieldNamefrmId = reverseString(cal_fieldNamefrmId.substr(cal_fieldNamefrmId.indexOf("_") + 1));

                extrafields[ex].id = cal_fieldNamefrmId;
            }


            extrafieldName = extrafields[ex].id; 	// make Id as Name
            extrafieldVal = encodeURIComponent(extrafields[ex].value);	// get extra field value
            extrafieldpost += "&" + extrafieldName + "=" + extrafieldVal;
        }
    }
    var cal_el = RedgetElementsByClassName('calendar');

    var cal_fieldNamefrmId = "";
    for (cal_i = 0; cal_i < cal_el.length; cal_i++) {
        // do stuff here with myEls[i]
        var calImgId = cal_el[cal_i].id;

        arr = calImgId.split("_img");
        n = arr.length;
        var calName = arr[0];

        if (calName != "" && calName.search(product_id)) {
            if (document.getElementById(calName).value != "") {
                cal_fieldNamefrmId = reverseString(calName);
                cal_fieldNamefrmId = reverseString(cal_fieldNamefrmId.substr(cal_fieldNamefrmId.indexOf("_") + 1));
                extrafieldpost += "&" + cal_fieldNamefrmId + "=" + document.getElementById(calName).value;
            }
        }
    }
    // End
    var subscription_data = "";
    if (document.getElementById('hidden_subscription_id')) {

        subId = document.getElementById('hidden_subscription_id').value;
        if (subId == 0 || subId == "") {
            alert(COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN);
            return false;
        }
        subscription_data = "&subscription_id=" + subId;
    }
    if (document.getElementById('giftcard_id')) {
        id = "&giftcard_id=" + product_id;

        if (document.getElementById('reciver_email'))
            id += "&reciver_email=" + document.getElementById('reciver_email').value;
        if (document.getElementById('reciver_name'))
            id += "&reciver_name=" + document.getElementById('reciver_name').value;
        if (document.getElementById('customer_amount'))
            id += "&customer_amount=" + document.getElementById('customer_amount').value;
    } else {
        id = "&product_id=" + product_id;
    }

    request = getHTTPObject();
    var params = "option=com_redshop&view=cart&task=add&tmpl=component&ajax_cart_box=1";

    params = params + "&Itemid=" + frm.Itemid.value + id;
    params = params + "&category_id=" + frm.category_id.value;
    params = params + "&attribute_data=" + frm.attribute_data.value;
    params = params + "&property_data=" + frm.property_data.value;
    params = params + "&subproperty_data=" + frm.subproperty_data.value;
//	params = params + "&attribute_price="+frm.attribute_price.value;
    params = params + "&requiedAttribute=" + frm.requiedAttribute.value;
    params = params + "&requiedProperty=" + frm.requiedProperty.value;
    params = params + "&accessory_data=" + frm.accessory_data.value;
    params = params + "&acc_quantity_data=" + frm.acc_quantity_data.value;
    params = params + "&acc_attribute_data=" + frm.acc_attribute_data.value;
    params = params + "&acc_property_data=" + frm.acc_property_data.value;
    params = params + "&acc_subproperty_data=" + frm.acc_subproperty_data.value;
    params = params + "&accessory_price=" + frm.accessory_price.value;
    if (document.getElementById("wrapper_check") && document.getElementById("wrapper_check").checked) {
        params = params + "&sel_wrapper_id=" + frm.sel_wrapper_id.value;
    }
    params = params + "&quantity=" + frm.quantity.value;
    params = params + "&hidden_attribute_cartimage=" + frm.hidden_attribute_cartimage.value;


    if (document.getElementById('calc_height')) {
        params = params + "&calcHeight=" + frm.calcHeight.value;
    }
    if (document.getElementById('calc_width')) {
        params = params + "&calcWidth=" + frm.calcWidth.value;
    }
    if (document.getElementById('calc_depth')) {
        params = params + "&calcDepth=" + frm.calcDepth.value;
    }
    if (document.getElementById('calc_radius')) {
        params = params + "&calcRadius=" + frm.calcRadius.value;
    }
    if (document.getElementById('calc_unit')) {
        params = params + "&calcUnit=" + frm.calcUnit.value;
    }
    // pdc extra data
    params = params + "&pdcextraid=" + frm.pdcextraid.value;

    params = params + subscription_data + extrafieldpost;

    /*
     * Function will override from any non redSHOP core javascript to append more cart params
     *
     * Also we can use the same function as validator
     */
    if (getExtraParams(frm)) {
        params = params + getExtraParams(frm);
    } else {
        return false;
    }

    var url = site_url + "index.php?" + params;

    request.open("POST", url, false);

    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Content-length", params.length);
    request.setRequestHeader("Connection", "close");

    var aj_flag = true;
    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            var responce = request.responseText;
            //alert(responce);
            responce = responce.split("`");


            if (responce[1] == "0") {
                alert(responce[2]); //alert last message
                return false;
            }
            else {
                if (document.attbox) {
                    document.attbox.close();
                }
            }

            // cart module

            if (document.getElementById('mod_cart_total') && responce[1]) {
                document.getElementById('mod_cart_total').innerHTML = responce[1];
            }
            if (document.getElementById('rs_promote_free_shipping_div') && responce[2]) {
                document.getElementById('rs_promote_free_shipping_div').innerHTML = responce[2];
            }
            //alert(responce[2])
            if (document.getElementById('mod_cart_checkout_ajax')) {
                document.getElementById('mod_cart_checkout_ajax').style.display = "";
            }

            // End
            var newurl = site_url + "index.php?option=com_redshop&view=product&pid=" + product_id + "&r_template=cartbox&tmpl=component";

            /*var options = {url:newurl,handler:'ajax',size: {x: 500, y: 150}};

             redBOX.initialize({});
             document.ajaxbox = redBOX.open(null,options);*/

            request_inner = getHTTPObject();

            request_inner.onreadystatechange = function () {
                if (request_inner.readyState == 4 && request_inner.status == 200 && aj_flag) {
                    var responcebox = request_inner.responseText;

                    aj_flag = false;

                    var options = {url: newurl, handler: 'html', size: {x: parseInt(AJAX_BOX_WIDTH), y: parseInt(AJAX_BOX_HEIGHT)}, htmldata: responcebox, onOpen: function () {
                        if (AJAX_CART_DISPLAY_TIME > 0) {
                            var fn = function () {
                                this.close()
                            }.bind(this).delay(AJAX_CART_DISPLAY_TIME);
                        }
                    }};
                    redBOX.initialize({});
                    document.ajaxbox = redBOX.open(null, options);

                }
            };
            request_inner.open("GET", newurl, true);
            request_inner.send(null);

        }
    };
    request.send(url);
    //request.send(params);
}

/*
 * This function originally will override for AJAX CART Submit Data
 */
function getExtraParams(frm) {
    return '&';
}

function displayAddtocartProperty(frmCartName, product_id, attribute_id, property_id) {
    if (document.getElementById('attribute_data')) {
        document.getElementById('attribute_data').value = attribute_id;
    }
    if (document.getElementById('property_data')) {
        document.getElementById('property_data').value = property_id;
    }
    if (document.getElementById('subproperty_data')) {
        document.getElementById('subproperty_data').value = "";
    }
    //set selected attribute,property,subproperty data and total price to Add to cart form
    if (!setAddtocartForm(frmCartName, product_id)) {
        return false;
    }
    return true;
}

function showallreviews() {
    if (document.getElementById("showreviews")) {
        if (document.getElementById("showreviews").style.display == "none") {
            document.getElementById("showreviews").style.display = "";
        }
        else {
            document.getElementById("showreviews").style.display = "none";
        }
    }
}

/************************Js Clean up code function end *********************************/


function checkAddtocartwishlistValidation(frmCartName, product_id, relatedprd_id, giftcard_id, frmUserfieldName, totAttribute, totAccessory, totUserfield, wishList) {


    if (product_id == 0 || product_id == "") {
//		alert("Product ID is missing");
        return false;
    }
    var prop_id_cart = "";
    var prop_id_cart_value = "";
    var subprop_id_cart = "";
    var subprop_id_cart_value = "";
    var attr_id = "";
    var subattr_id = "";
    var att_required = "";
    var att_name = "";
    var att_name_lebl = "";
    var att_error = "";
    var att_error_alert = false;
    var attreq = false;

    var arr_attr_id = new Array();
    var arr_subattr_id = new Array();
    var sel_i = 0;
    var sub_sel_i = 0;
    // User field validation

    //if((AJAX_CART_BOX==0 && wishList==0) || wishList==1)
    if (wishList == 1) {
        var ret = userfieldValidation("extrafields" + product_id);
        if (!ret) {
            return false;
        }
        var requiedAttribute = document.getElementById(frmCartName).requiedAttribute.value;

        var requiedProperty = document.getElementById(frmCartName).requiedProperty.value;

        if (requiedAttribute != "") {
            alert(requiedAttribute);
            return false;
        }
        if (requiedProperty != "") {
            alert(requiedProperty);
            return false;
        }
        return true;
        //		document.getElementById(frmCartName).submit();

    } else {
        /*
         * count total attribute + extra fields
         * Where natt = number of total attribute
         * And nextra = number of extra fields
         */
//		var ntotal = parseInt(totAttribute) + parseInt(totAccessory) + parseInt(totUserfield);
//		alert(parseInt(totAttribute) + " = "+ parseInt(totAccessory) + " = " + parseInt(totUserfield));
        // submit form from product detail page
        /*
         * ntotal = count total attribute + extra fields
         * if attribute is not available then cart will submit directly
         *
         */

        submitAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield);
        return true;

    }
    //alert('bye***');
}

var mainpro_id = new Array();
var totatt = new Array();
var totcount_no_user_field = new Array();

function productalladdprice(my) {


    var wishList = 1;
    mainpro_id = document.frm.product_id.value.split(",");
    totatt = document.frm.totacc_id.value.split(",");
    totcount_no_user_field = document.frm.totcount_no_user_field.value.split(",");

    mainpro_id.length = mainpro_id.length - 1;


    for (var i = 0; i < mainpro_id.length; i++) {	//alert(i);

        if (mainpro_id[i] != "") {

            if (displayAddtocartForm('addtocart_prd_' + mainpro_id[i], mainpro_id[i], '0', '0', 'user_fields_form')) {

                //submitAjaxCartdetail('addtocart_prd_'+mainpro_id[i],mainpro_id[i], 0, 0,totatt[i],0,totcount_no_user_field[i]);
                if (!checkAddtocartwishlistValidation('addtocart_prd_' + mainpro_id[i], mainpro_id[i], '0', '0', 'user_fields_form', totatt[i], '', totcount_no_user_field[i], wishList)) {
                    //alert("inside");
                    //continue;
                    return false;
                }
                else {
                    //return submitAjaxwishlistCartdetail('addtocart_prd_'+mainpro_id[i],mainpro_id[i], 0, 0,totatt[i],0,totcount_no_user_field[i]);

                }

                if (i == (mainpro_id.length - 1)) {
                    //wishList=2;
                    //for(var j=0;j<=i;j++)
                    //{
//
//							return	submitAjaxwishlistCartdetail('addtocart_prd_'+mainpro_id[j],mainpro_id[j], 0, 0,totatt[j],0,totcount_no_user_field[j]);
//						//alert('addtocart_prd_'+mainpro_id[j]);
//						}


                }


            }


        }
    }

    submitAjaxwishlistCartdetail('addtocart_prd_' + mainpro_id[0], mainpro_id[0], 0, 0, totatt[0], 0, totcount_no_user_field[0], my);


}


var d = 0;
function submitAjaxwishlistCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield, my) {
    displayAddtocartForm('addtocart_prd_' + mainpro_id[d], mainpro_id[d], '0', '0', 'user_fields_form');
    var frm = document.getElementById(frmCartName);

    var proid = 0;
    var priceval = 0;
    var mainpri = 0;
    var proppri = 0;
    var attpric = 0;
    var accpric = 0;
    var propcartid = 0;
    var subpropcartid = 0;
    var wrapperdata = "";
    var accdata = "";
    var attdata = "";
    var subattdata = "";
    var qty = 1;
    var id = '';
    var set = false;
    // calculator variables
    var calHeight = 0, calWidth = 0;


    // get multiple extra fields attributes
    var extrafields = document.getElementsByName('extrafields' + product_id + '[]');

    // intialize defaults
    var extrafieldName = "";
    var extrafieldVal = "";
    var extrafieldpost = "";

    var previousfieldName = "";
    var fieldNamefrmId = "";
    var chk_flag = false;
    var rdo_previousfieldName = "";
    var rdo_fieldNamefrmId = "";
    var rdo_flag = false;

    var selmulti_fieldNamefrmId = "";

    var ret = userfieldValidation("extrafields" + product_id);
    if (!ret) {
        return false;
    }
    var requiedAttribute = document.getElementById(frmCartName).requiedAttribute.value;
    var requiedProperty = document.getElementById(frmCartName).requiedProperty.value;

    if (requiedAttribute != 0 && requiedAttribute != "") {
        alert(requiedAttribute);
        return false;
    }
    if (requiedProperty != 0 && requiedProperty != "") {
        alert(requiedProperty);
        return false;
    }


    for (var ex = 0; ex < extrafields.length; ex++) {

        if (extrafields[ex].type == 'checkbox') {
            fieldNamefrmId = reverseString(extrafields[ex].id);
            fieldNamefrmId = reverseString(fieldNamefrmId.substr(fieldNamefrmId.indexOf("_") + 1));
            if (previousfieldName != "" && previousfieldName != fieldNamefrmId && extrafieldVal != "") {
                extrafieldpost += "&" + previousfieldName + "=" + extrafieldVal;
            }
            if (previousfieldName != fieldNamefrmId) {
                extrafieldVal = "";
                previousfieldName = fieldNamefrmId;
            }
            if (extrafields[ex].checked) {
                if (extrafieldVal != "")
                    extrafieldVal += ",";
                extrafieldVal += extrafields[ex].value;
            }
            if (ex == (extrafields.length - 1) && extrafieldVal != "") {
                extrafieldpost += "&" + fieldNamefrmId + "=" + extrafieldVal;
            }
            if (ex < (extrafields.length - 1)) {
                if ((extrafields[ex + 1].type != 'checkbox') && extrafieldVal != "")
                    extrafieldpost += "&" + fieldNamefrmId + "=" + extrafieldVal;
            }
        }
        else if (extrafields[ex].type == 'radio') {

            rdo_fieldNamefrmId = reverseString(extrafields[ex].id);
            rdo_fieldNamefrmId = reverseString(rdo_fieldNamefrmId.substr(rdo_fieldNamefrmId.indexOf("_") + 1));

            if (rdo_previousfieldName != "" && rdo_previousfieldName != rdo_fieldNamefrmId && rdo_flag == false) {
                extrafieldpost += "&" + rdo_previousfieldName + "=" + extrafieldVal;
            }

            if (rdo_previousfieldName != rdo_fieldNamefrmId) {
                extrafieldVal = "";
                rdo_previousfieldName = rdo_fieldNamefrmId;
                rdo_flag = false;
                if (extrafields[ex].checked || rdo_flag == true) {
                    rdo_flag = true;
                    extrafieldpost += "&" + rdo_previousfieldName + "=" + extrafields[ex].value;
                    continue;
                }
            }
            else {
                if (extrafields[ex].checked || rdo_flag == true) {
                    rdo_flag = true;
                    extrafieldpost += "&" + rdo_fieldNamefrmId + "=" + extrafields[ex].value;
                    continue;
                }
            }
        }
        else if (extrafields[ex].type == 'select-multiple') {
            var ob = extrafields[ex];
            extrafieldVal = "";
            selmulti_fieldNamefrmId = reverseString(extrafields[ex].id);
            selmulti_fieldNamefrmId = reverseString(selmulti_fieldNamefrmId.substr(selmulti_fieldNamefrmId.indexOf("_") + 1));
            for (var t = 0; t < ob.options.length; t++) {
                if (ob.options[ t ].selected) {
                    var strval = extrafieldVal;
                    if (strval.search(String(ob.options[ t ].value)) == -1) {
                        if (extrafieldVal != "")
                            extrafieldVal += ",";
                        extrafieldVal += (String(ob.options[ t ].value));
                    }
                }
            }

            if (extrafieldVal) {
                extrafieldpost += "&" + selmulti_fieldNamefrmId + "=" + extrafieldVal;
            }
        } else if (extrafields[ex].type == 'hidden') {
            imgfieldNamefrmId = reverseString(extrafields[ex].id);
            imgfieldNamefrmId = reverseString(imgfieldNamefrmId.substr(imgfieldNamefrmId.indexOf("_") + 1));

            extrafieldName = imgfieldNamefrmId; 	// make Id as Name
            extrafieldVal = extrafields[ex].value;	// get extra field value
            extrafieldpost += "&" + extrafieldName + "=" + extrafieldVal;
            extrafieldVal = "";
        } else if (extrafields[ex].type == 'text') {

            extrafieldName = extrafields[ex].id; 	// make Id as Name
            extrafieldVal = extrafields[ex].value;	// get extra field value
            extrafieldpost += "&" + extrafieldName + "=" + extrafieldVal;

        }
        else {
            if (extrafields[ex].id.search('ajax') != -1) {
                var tmpName = extrafields[ex].id.split('ajax');
                var cal_fieldNamefrmId = "";
                cal_fieldNamefrmId = reverseString(tmpName[1]);
                cal_fieldNamefrmId = reverseString(cal_fieldNamefrmId.substr(cal_fieldNamefrmId.indexOf("_") + 1));

                extrafields[ex].id = cal_fieldNamefrmId;
            }


            extrafieldName = extrafields[ex].id; 	// make Id as Name
            extrafieldVal = extrafields[ex].value;	// get extra field value
            extrafieldpost += "&" + extrafieldName + "=" + extrafieldVal;
        }
    }
    var cal_el = RedgetElementsByClassName('calendar');

    var cal_fieldNamefrmId = "";
    for (cal_i = 0; cal_i < cal_el.length; cal_i++) {
        // do stuff here with myEls[i]
        var calImgId = cal_el[cal_i].id;

        arr = calImgId.split("_img");
        n = arr.length;
        var calName = arr[0];

        if (calName != "" && calName.search(product_id)) {
            if (document.getElementById(calName).value != "") {
                cal_fieldNamefrmId = reverseString(calName);
                cal_fieldNamefrmId = reverseString(cal_fieldNamefrmId.substr(cal_fieldNamefrmId.indexOf("_") + 1));
                extrafieldpost += "&" + cal_fieldNamefrmId + "=" + document.getElementById(calName).value;
            }
        }
    }
    // End
    var subscription_data = "";

    if (document.getElementById('hidden_subscription_id')) {


        subId = document.getElementById('hidden_subscription_id').value;
        if (subId == 0 || subId == "") {
            alert(COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN);
            return false;
        }
        subscription_data = "&subscription_id=" + subId;
    }
    if (document.getElementById('giftcard_id')) {
        id = "&giftcard_id=" + product_id;

        if (document.getElementById('reciver_email'))
            id += "&reciver_email=" + document.getElementById('reciver_email').value;
        if (document.getElementById('reciver_name'))
            id += "&reciver_name=" + document.getElementById('reciver_name').value;
        if (document.getElementById('customer_amount'))
            id += "&customer_amount=" + document.getElementById('customer_amount').value;
    } else {
        id = "&product_id=" + product_id;
    }

    request = getHTTPObject();

    if (my == 1 || my == 2) {
        var params = "option=com_redshop&view=product&task=addtowishlist&wid=1&ajaxon=1&tmpl=component";
    } else {
        var params = "option=com_redshop&view=cart&task=add&tmpl=component&ajax_cart_box=1";
    }
    params = params + "&Itemid=" + frm.Itemid.value + id;
    params = params + "&category_id=" + frm.category_id.value;
    params = params + "&attribute_data=" + frm.attribute_data.value;
    params = params + "&property_data=" + frm.property_data.value;
    params = params + "&subproperty_data=" + frm.subproperty_data.value;
//	params = params + "&attribute_price="+frm.attribute_price.value;
    params = params + "&requiedAttribute=" + frm.requiedAttribute.value;
    params = params + "&requiedProperty=" + frm.requiedProperty.value;
    params = params + "&accessory_data=" + frm.accessory_data.value;
    params = params + "&acc_quantity_data=" + frm.acc_quantity_data.value;
    params = params + "&acc_attribute_data=" + frm.acc_attribute_data.value;
    params = params + "&acc_property_data=" + frm.acc_property_data.value;
    params = params + "&acc_subproperty_data=" + frm.acc_subproperty_data.value;
    params = params + "&accessory_price=" + frm.accessory_price.value;
    params = params + "&sel_wrapper_id=" + frm.sel_wrapper_id.value;
    params = params + "&quantity=1";// + frm.quantity.value;

//alert(params);return false;
    if (document.getElementById('calc_height')) {
        params = params + "&calcHeight=" + frm.calcHeight.value;
    }
    if (document.getElementById('calc_width')) {
        params = params + "&calcWidth=" + frm.calcWidth.value;
    }
    if (document.getElementById('calc_depth')) {
        params = params + "&calcDepth=" + frm.calcDepth.value;
    }
    if (document.getElementById('calc_radius')) {
        params = params + "&calcRadius=" + frm.calcRadius.value;
    }
    if (document.getElementById('calc_unit')) {
        params = params + "&calcUnit=" + frm.calcUnit.value;
    }
    // pdc extra data
    params = params + "&pdcextraid=" + frm.pdcextraid.value;

    params = params + subscription_data + extrafieldpost;

    /*
     * Function will override from any non redSHOP core javascript to append more cart params
     *
     * Also we can use the same function as validator
     */
    if (getExtraParams(frm)) {
        params = params + getExtraParams(frm);
    } else {
        return false;
    }

    var url = site_url + "index.php?" + params;

    if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent))
        request.open("POST", url, true);
    else
        request.open("POST", url, false);

    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Content-length", params.length);
    request.setRequestHeader("Connection", "close");

    //var aj_flag = true;
    request.onreadystatechange = function () {
        if (request.readyState < 4) {
            if (document.getElementById("saveid") != '' && my == 1) {
                document.getElementById("saveid").innerHTML = '<font size="1" color="red">Processing...</font>';
            } else {
                document.getElementById("allcart").innerHTML = '<font size="1" color="red">Processing...</font>';
            }

        }
        if (request.readyState == 4) {


            var responce = request.responseText;

            responce = responce.split("`");

            if (responce[1] == "0") {
                //alert(responce[2]); //alert last message
                return false;
            }
            else {
                if (document.attbox) {
                    document.attbox.close();
                }
            }

            d++;
            var ltotoal = mainpro_id.length;

            if (d < ltotoal) {
                if (mainpro_id[d] != "") {
                    submitAjaxwishlistCartdetail('addtocart_prd_' + mainpro_id[d], mainpro_id[d], 0, 0, totatt[d], 0, totcount_no_user_field[d]);
                }

            } else if (my == 1) {
                window.location = site_url + "index.php?wishlist=1&option=com_redshop&view=login";
            } else if (my == 2) {
                return false;
            } else {
                window.location = site_url + "index.php?option=com_redshop&view=cart";

            }
            document.getElementById("saveid").innerHTML = '';
            document.getElementById("allcart").innerHTML = '';


            //d++;
            //for(var d=0;d<frmCartName.length;i++)
            //{	//alert(i);
            //alert(mainpro_id[i]);
            //	if(frmCartName[i]!="")
            //	{

            //		submitAjaxwishlistCartdetail('addtocart_prd_'+frmCartName[d],frmCartName[d], 0, 0,product_id[d],0,relatedprd_id[d]);
            //	}
            //}

            // cart module

            if (document.getElementById('mod_cart_total') && responce[1]) {
                document.getElementById('mod_cart_total').innerHTML = responce[1];
            }
            if (document.getElementById('rs_promote_free_shipping_div') && responce[2]) {
                document.getElementById('rs_promote_free_shipping_div').innerHTML = responce[2];
            }
            //alert(responce[2])
            if (document.getElementById('mod_cart_checkout_ajax')) {
                document.getElementById('mod_cart_checkout_ajax').style.display = "";
            }

            // End
            var newurl = site_url + "index.php?option=com_redshop&view=product&pid=" + product_id + "&r_template=cartbox&tmpl=component";

            //alert(myurl);
            var options = {url: newurl, handler: 'ajax', size: {x: 500, y: 600}};
            redBOX.initialize({});
            redBOX.open(null, options);
        }
    };
    request.send(url);
    request.send(null);

}

/***********************all wishlist product add in add to cart end*************************/
function addmywishlist(frmCartName, product_id, myitemid) {

    var extrafields = document.getElementsByName('extrafields' + product_id + '[]');
    var extrafieldName = "";
    var extrafieldVal = "";
    var extrafieldpost = "";
    var previousfieldName = "";
    var fieldNamefrmId = "";
    var chk_flag = false;
    var rdo_previousfieldName = "";
    var rdo_fieldNamefrmId = "";
    var rdo_flag = false;

    var selmulti_fieldNamefrmId = "";


    for (var ex = 0; ex < extrafields.length; ex++) {

        if (extrafields[ex].type == 'checkbox') {
            fieldNamefrmId = reverseString(extrafields[ex].id);
            fieldNamefrmId = reverseString(fieldNamefrmId.substr(fieldNamefrmId.indexOf("_") + 1));
            if (previousfieldName != "" && previousfieldName != fieldNamefrmId && extrafieldVal != "") {
                extrafieldpost += "&" + previousfieldName + "=" + extrafieldVal;
            }
            if (previousfieldName != fieldNamefrmId) {
                extrafieldVal = "";
                previousfieldName = fieldNamefrmId;
            }
            if (extrafields[ex].checked) {
                if (extrafieldVal != "")
                    extrafieldVal += ",";
                extrafieldVal += extrafields[ex].value;
            }
            if (ex == (extrafields.length - 1) && extrafieldVal != "") {
                extrafieldpost += "&" + fieldNamefrmId + "=" + extrafieldVal;
            }
            if (ex < (extrafields.length - 1)) {
                if ((extrafields[ex + 1].type != 'checkbox') && extrafieldVal != "")
                    extrafieldpost += "&" + fieldNamefrmId + "=" + extrafieldVal;
            }
        }
        else if (extrafields[ex].type == 'radio') {

            rdo_fieldNamefrmId = reverseString(extrafields[ex].id);
            rdo_fieldNamefrmId = reverseString(rdo_fieldNamefrmId.substr(rdo_fieldNamefrmId.indexOf("_") + 1));

            if (rdo_previousfieldName != "" && rdo_previousfieldName != rdo_fieldNamefrmId && rdo_flag == false) {
                extrafieldpost += "&" + rdo_previousfieldName + "=" + extrafieldVal;
            }

            if (rdo_previousfieldName != rdo_fieldNamefrmId) {
                extrafieldVal = "";
                rdo_previousfieldName = rdo_fieldNamefrmId;
                rdo_flag = false;
                if (extrafields[ex].checked || rdo_flag == true) {
                    rdo_flag = true;
                    extrafieldpost += "&" + rdo_previousfieldName + "=" + extrafields[ex].value;
                    continue;
                }
            }
            else {
                if (extrafields[ex].checked)// || rdo_flag== true)
                {
                    rdo_flag = true;
                    extrafieldpost += "&" + rdo_fieldNamefrmId + "=" + extrafields[ex].value;
                    continue;
                }
            }
        }
        else if (extrafields[ex].type == 'select-multiple') {
            var ob = extrafields[ex];
            extrafieldVal = "";
            selmulti_fieldNamefrmId = reverseString(extrafields[ex].id);
            selmulti_fieldNamefrmId = reverseString(selmulti_fieldNamefrmId.substr(selmulti_fieldNamefrmId.indexOf("_") + 1));
            for (var t = 0; t < ob.options.length; t++) {
                if (ob.options[ t ].selected) {
                    var strval = extrafieldVal;
                    if (strval.search(String(ob.options[ t ].value)) == -1) {
                        if (extrafieldVal != "")
                            extrafieldVal += ",";
                        extrafieldVal += (String(ob.options[ t ].value));
                    }
                }
            }

            if (extrafieldVal) {
                extrafieldpost += "&" + selmulti_fieldNamefrmId + "=" + extrafieldVal;
            }
        } else if (extrafields[ex].type == 'hidden') {

            imgfieldNamefrmId = reverseString(extrafields[ex].id);
            imgfieldNamefrmId = reverseString(imgfieldNamefrmId.substr(imgfieldNamefrmId.indexOf("_") + 1));

            extrafieldName = imgfieldNamefrmId; 	// make Id as Name
            extrafieldVal = extrafields[ex].value;	// get extra field value
            extrafieldpost += "&" + extrafieldName + "=" + extrafieldVal;
            extrafieldVal = "";
        } else if (extrafields[ex].type == 'text') {

            extrafieldName = extrafields[ex].id; 	// make Id as Name
            extrafieldVal = extrafields[ex].value;	// get extra field value
            extrafieldpost += "&" + extrafieldName + "=" + extrafieldVal;

        }
        else {
            if (extrafields[ex].id.search('ajax') != -1) {
                var tmpName = extrafields[ex].id.split('ajax');
                var cal_fieldNamefrmId = "";
                cal_fieldNamefrmId = reverseString(tmpName[1]);
                cal_fieldNamefrmId = reverseString(cal_fieldNamefrmId.substr(cal_fieldNamefrmId.indexOf("_") + 1));

                extrafields[ex].id = cal_fieldNamefrmId;
            }


            extrafieldName = extrafields[ex].id; 	// make Id as Name
            extrafieldVal = extrafields[ex].value;	// get extra field value
            extrafieldpost += "&" + extrafieldName + "=" + extrafieldVal;
        }

    }

    var cal_el = RedgetElementsByClassName('calendar');

    var cal_fieldNamefrmId = "";
    for (cal_i = 0; cal_i < cal_el.length; cal_i++) {
        // do stuff here with myEls[i]
        var calImgId = cal_el[cal_i].id;

        arr = calImgId.split("_img");
        n = arr.length;
        var calName = arr[0];

        if (calName != "" && calName.search(product_id)) {
            if (document.getElementById(calName).value != "") {
                cal_fieldNamefrmId = reverseString(calName);
                cal_fieldNamefrmId = reverseString(cal_fieldNamefrmId.substr(cal_fieldNamefrmId.indexOf("_") + 1));
                extrafieldpost += "&" + cal_fieldNamefrmId + "=" + document.getElementById(calName).value;
            }
        }
    }


    request = getHTTPObject();

    var params = "option=com_redshop&view=product&task=addtowishlist&json=1&ajaxon=1&tmpl=component";
    params = params + "&Itemid=" + myitemid;
    params = params + "&product_id=" + product_id;
    params = params + "&userfield_id=" + extrafields.length;
    params = params + extrafieldpost;


    var url = site_url + "index.php?" + params;


    if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent))
        request.open("POST", url, true);
    else
        request.open("POST", url, false);

    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Content-length", params.length);
    request.setRequestHeader("Connection", "close");
    var aj_flag = true;
    request.onreadystatechange = function () {

        if (request.readyState < 4) {

            document.getElementById("myprohide_" + product_id).innerHTML = '<font size="1" color="red">Loading...</font>';

        }
        if (request.readyState == 4 && request.status == 200) {
            var responce = request.responseText;
            //alert(responce);

            var str = document.getElementById("mypid").innerHTML;

            var str1 = request.responseText;
            sp = str1.split(":-:");

            if (str.search(sp[1]) == -1) {
                document.getElementById('mypid').innerHTML += sp[0];
                document.getElementById("wid").style.display = 'block';
                document.getElementById("myprohide_" + product_id).innerHTML = '';
            }
        }
    };
    request.send(url);
    request.send(params);
}

function getStocknotify(product_id, property_id, subproperty_id) {

    var url = site_url + "index.php?option=com_redshop&view=product&task=addNotifystock&tmpl=component&product_id=" + product_id;
    url = url + "&property_id=" + property_id + "&subproperty_id=" + subproperty_id;

    request = getHTTPObject();
    request.onreadystatechange = function () {
        // if request object received response
        if (request.readyState == 4) {

            var str = request.responseText;
            if (document.getElementById("notify_stock" + product_id)) {

                document.getElementById("notify_stock" + product_id).innerHTML = str;
            }
        }

    }
    request.open("POST", url, true);
    request.send(null);
}