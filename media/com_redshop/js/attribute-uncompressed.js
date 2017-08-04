/**
 * @copyright  Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Only define the redSHOP namespace if not defined.
redSHOP = window.redSHOP || {};

redSHOP.setProductTax = function(postData){

    // Setting default
    postData.id        = postData.id || 0;
    postData.price     = postData.price || 0;
    postData.userId    = postData.userId || 0;
    postData.taxExempt = postData.taxExempt || false;

    postData.option    = 'com_redshop';
    postData.view      = 'cart';
    postData.task      = 'cart.ajaxGetProductTax';
    postData.tmpl      = 'component';

    jQuery.ajax({
        url: redSHOP.RSConfig._('AJAX_BASE_URL'),
        type: 'POST',
        dataType: 'json',
        data: postData,
    }).done(function( product ) {
        // Setting in global variable
        redSHOP.baseTax = product.tax;
    });
};

redSHOP.filterExtraFieldName = function(name){
    name = reverseString(name);
    return reverseString(name.substr(name.indexOf("_") + 1));
};

redSHOP.collectExtraFields = function(extraField, productId){

    var field = {
        name: extraField.id.replace('_' + productId, ''),
        value: jQuery(extraField).val()
    };

    switch(extraField.type)
    {
        case 'checkbox':
        case 'radio':

            field.name = redSHOP.filterExtraFieldName(extraField.id);
            field.value = jQuery('[id^='+field.name+']:checked').val();

        break;
    }

    return field;
};

redSHOP.updateCartExtraFields = function(extraFields, productId, formName){

    jQuery.each(extraFields, function(index, extraField) {

        var field = redSHOP.collectExtraFields(extraField, productId);

        jQuery(formName + ' input[id=' + field.name +']').val(field.value);
    });
};

redSHOP.updateAjaxCartExtraFields = function(extraFields, productId){

    var extraFieldPost = [];

    jQuery.each(extraFields, function(index, extraField) {

        var field = redSHOP.collectExtraFields(extraField, productId);

        extraFieldPost.push(field.name + '=' + field.value);
    });

    return extraFieldPost;
};

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

    // Calculating base tax using price 1
    // Later it can be used to multiplied with any price to get exact tax in JS.
    redSHOP.setProductTax({id: 0, price: 1});
});

var r_browser = false;
var subproperty_main_image = "";

function getHTTPObject()
{
    var xhr = false;

    if (window.XMLHttpRequest)
    {
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        try
        {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e)
        {
            try
            {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e)
            {
                xhr = false;
            }
        }
    }

    return xhr;
}

var request;

// Js Clean up code start function
function productaddprice(product_id, relatedprd_id)
{
    var qty = 1;

    if (relatedprd_id != 0)
    {
        prefix = relatedprd_id;
    }
    else
    {
        prefix = product_id;
    }

    if (document.getElementById("accessory_data"))
    {
        var accessory_data = document.getElementById("accessory_data").value;
    }

    if (document.getElementById("acc_quantity_data"))
    {
        var acc_quantity_data = document.getElementById("acc_quantity_data").value;
    }

    if (document.getElementById("acc_attribute_data"))
    {
        var acc_attribute_data = document.getElementById("acc_attribute_data").value.replace("##", "::");
    }

    if (document.getElementById("acc_property_data"))
    {
        var acc_property_data = document.getElementById("acc_property_data").value.replace("##", "::");
    }

    if (document.getElementById("acc_subproperty_data"))
    {
        var acc_subproperty_data = document.getElementById("acc_subproperty_data").value.replace("##", "::");
    }

    if (document.getElementById('quantity' + prefix) && document.getElementById('quantity' + prefix))
    {
        qty = document.getElementById('quantity' + prefix).value;
    }

    if (document.getElementById('attribute_data'))
    {
        var attribute_data = document.getElementById('attribute_data').value.replace("##", "::");
    }

    if (document.getElementById('property_data'))
    {
        var property_data = document.getElementById('property_data').value.replace("##", "::");
    }

    if (document.getElementById('subproperty_data'))
    {
        var subproperty_data = document.getElementById('subproperty_data').value.replace("##", "::");
    }

    var url = redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=product&task=displayProductaddprice&tmpl=component&qunatity=" + qty;
    url = url + "&product_id=" + product_id + "&attribute_data=" + attribute_data + "&property_data=" + property_data + "&subproperty_data=" + subproperty_data;
    url = url + "&accessory_data=" + accessory_data + "&acc_quantity_data=" + acc_quantity_data + "&acc_attribute_data=" + acc_attribute_data + "&acc_property_data=" + acc_property_data + "&acc_subproperty_data=" + acc_subproperty_data;

    request = getHTTPObject();
    request.onreadystatechange = function () {

        // if request object received response
        if (request.readyState == 4)
        {
            var str = request.responseText.split(":");
            var accessory_price = 0;
            var accessory_price_withoutvat = 0;
            var wprice = 0;
            var wrapper_price_withoutvat = 0;

            if (document.getElementById("wrapper_price"))
            {
                wprice = parseFloat(document.getElementById("wrapper_price").value);
            }

            if (document.getElementById("wrapper_price_withoutvat"))
            {
                wrapper_price_withoutvat = parseFloat(document.getElementById("wrapper_price_withoutvat").value);
            }

            if (document.getElementById('produkt_kasse_hoejre_pris_indre' + prefix))
            {
                document.getElementById('produkt_kasse_hoejre_pris_indre' + prefix).innerHTML = number_format(parseFloat(str[0]) + (wprice * qty), redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
            }

            if (document.getElementById('display_product_discount_price' + prefix))
            {
                document.getElementById('display_product_discount_price' + prefix).innerHTML = number_format(str[4], redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
            }

            if (document.getElementById('display_product_price_without_vat' + prefix))
            {
                document.getElementById('display_product_price_without_vat' + prefix).innerHTML = number_format(parseFloat(str[5]) + (wrapper_price_withoutvat * qty), redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
            }

            if (document.getElementById('display_product_price_no_vat' + prefix))
            {
                document.getElementById('display_product_price_no_vat' + prefix).innerHTML = number_format(parseFloat(str[5]) + (wrapper_price_withoutvat * qty), redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
            }

            if (document.getElementById('display_product_old_price' + prefix))
            {
                document.getElementById('display_product_old_price' + prefix).innerHTML = number_format(str[2], redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
            }

            if (document.getElementById('display_product_saving_price' + prefix))
            {
                document.getElementById('display_product_saving_price' + prefix).innerHTML = number_format(str[3], redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
            }

            if (document.getElementById('main_price' + prefix))
            {
                document.getElementById('main_price' + prefix).value = str[0];
            }

            if (document.getElementById('product_price_no_vat' + prefix))
            {
                document.getElementById('product_price_no_vat' + prefix).value = str[5];

                if (document.getElementById('main_price' + product_id))
                {
                    document.getElementById('main_price' + product_id).value = str[0];
                }

                if (document.getElementById('product_price_no_vat' + product_id))
                {
                    document.getElementById('product_price_no_vat' + product_id).value = str[5];
                }

                if (document.getElementById('product_old_price' + product_id))
                {
                    document.getElementById('product_old_price' + product_id).value = str[2];
                }
            }

            if (document.getElementById('product_old_price' + prefix))
            {
                document.getElementById('product_old_price' + prefix).value = str[2];
            }
        }
    };

    request.open("GET", url, true);
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    request.send(null);
}

/**
 * Initialize function store for trigger
 *
 * @type  {Array}
 */
redSHOP.onChangePropertyDropdown = [];

function changePropertyDropdown(product_id, accessory_id, relatedprd_id, attribute_id, selectedproperty_id, mpw_thumb, mph_thumb)
{
    var allarg           = arguments;
    var propArr          = [];
    var subpropArr       = [];
    var property_data    = "";
    var subproperty_data = "";
    var suburl           = "";
    var subatthtml       = "";
    var layout           = "";
    var prefix;

    if (document.getElementById('isAjaxBoxOpen'))
    {
        layout = document.getElementById('isAjaxBoxOpen').value;
    }

    var preprefix = "";

    if (layout == "viewajaxdetail")
    {
        preprefix = "ajax_";
    }

    if (accessory_id != 0)
    {
        prefix = preprefix + "acc_";
    }
    else if (relatedprd_id != 0)
    {
        prefix = preprefix + "rel_";
    }
    else
    {
        prefix = preprefix + "prd_";
    }

    var commonid = prefix + product_id + '_' + accessory_id + '_' + attribute_id;

    if (document.getElementById('subattdata_' + commonid))
    {
        subatthtml = document.getElementById('subattdata_' + commonid).value;
    }

    suburl = suburl + "&subatthtml=" + subatthtml;
    suburl = suburl + "&product_id=" + product_id;
    suburl = suburl + "&attribute_id=" + attribute_id;
    suburl = suburl + "&accessory_id=" + accessory_id;
    suburl = suburl + "&relatedprd_id=" + relatedprd_id;

    if (document.getElementsByName('property_id_' + commonid + '[]'))
    {
        var propName = document.getElementsByName('property_id_' + commonid + '[]');
        var sel_i = 0;

        for (var p = 0; p < propName.length; p++)
        {
            if (propName[p].type == 'checkbox' || propName[p].type == 'radio')
            {
                if (propName[p].checked)
                {
                    propArr[sel_i++] = propName[p].value;
                }
            }
            else
            {
                if (propName[p].selectedIndex)
                {
                    propArr[sel_i++] = propName[p].options[propName[p].selectedIndex].value;
                }
            }
        }

        var subsel_i = 0;

        for (var sp = 0; sp < propArr.length; sp++)
        {
            var spCommonName = '[name="subproperty_id_' + commonid + '_' + propArr[sp] + '[]"]';

            if (jQuery(spCommonName).length)
            {
                if ('radio' == jQuery(spCommonName).attr('type') || 'checkbox' == jQuery(spCommonName).attr('type'))
                {
                    subpropArr.push(jQuery(spCommonName + ':checked').val());
                }
                else
                {
                    subpropArr.push(jQuery(spCommonName).val());
                }
            }
        }

        property_data = propArr.join(",");
        subproperty_data = subpropArr.join(",");
        suburl = suburl + "&property_id=" + property_data;
        suburl = suburl + "&subproperty_id=" + subproperty_data;
    }

    var url = redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=product&task=displaySubProperty&tmpl=component&isAjaxBox=" + layout;
    url = url + suburl;

    request = getHTTPObject();
    request.onreadystatechange = function () {
        // if request object received response
        if (document.getElementById('property_responce' + commonid))
        {
            document.getElementById('property_responce' + commonid).style.display = 'none';
        }

        if(request.readyState != 4 )
        {
            if(document.getElementById('rs_image_loader'))
            document.getElementById('rs_image_loader').style.display = 'block';
        }

        if (request.readyState == 4)
        {
            var property_id = (propArr.length > 0) ? propArr[0] : 0;

            if (document.getElementById('property_responce' + commonid))
            {
                document.getElementById('property_responce' + commonid).innerHTML = request.responseText;
                document.getElementById('property_responce' + commonid).style.display = '';

                for (var p = 0; p < propArr.length; p++)
                {
                    property_id = propArr[p];
                    var scrollercommonid = commonid + '_' + property_id;

                    if (document.getElementById('divsubimgscroll' + scrollercommonid))
                    {
                        var scrollhtml = document.getElementById('divsubimgscroll' + scrollercommonid).innerHTML;

                        if (scrollhtml != "")
                        {
                            var imgs = scrollhtml.split('#_#');
                            var unique = "isFlowers" + scrollercommonid;
                            unique = new ImageScroller('isFlowersFrame' + scrollercommonid, 'isFlowersImageRow' + scrollercommonid);
                            var subpropertycommonid = 'subproperty_id_' + scrollercommonid;
                            var subinfo = '';

                            for (i = 0; i < imgs.length; i++)
                            {
                                subinfo = imgs[i].match(/\d+/g);
                                var subproperty_id = subinfo[0];
                                var subname = document.getElementById(subpropertycommonid + "_name" + subproperty_id).value;
                                unique.addThumbnail(
                                    imgs[i],
                                    "javascript:isFlowers" + scrollercommonid + ".scrollImageCenter('" + i + "');setSubpropImage('" + product_id + "','" + subpropertycommonid + "','" + subproperty_id + "');calculateTotalPrice('" + product_id + "','" + relatedprd_id + "');displayAdditionalImage('" + product_id + "','" + accessory_id + "','" + relatedprd_id + "','" + property_id + "','" + subproperty_id + "');",
                                    subname,
                                    "",
                                    subpropertycommonid + "_subpropimg_" + subproperty_id,
                                    ""
                                );
                            }

                            var rs_size = 50;

                            if (mph_thumb > mpw_thumb)
                            {
                                rs_size = mph_thumb;
                            }
                            else
                            {
                                rs_size = mpw_thumb;
                            }

                            unique.setThumbnailHeight(parseInt(redSHOP.RSConfig._('ATTRIBUTE_SCROLLER_THUMB_HEIGHT')));
                            unique.setThumbnailWidth(parseInt(redSHOP.RSConfig._('ATTRIBUTE_SCROLLER_THUMB_WIDTH')));
                            unique.setThumbnailPadding(5);
                            unique.setScrollType(0);
                            unique.enableThumbBorder(false);
                            unique.setClickOpenType(1);
                            unique.setThumbsShown(redSHOP.RSConfig._('NOOF_SUBATTRIB_THUMB_FOR_SCROLLER'));
                            unique.setNumOfImageToScroll(1);
                            unique.renderScroller();
                            window["isFlowers" + scrollercommonid] = unique;
                        }
                    }
                }
            }

            displayAdditionalImage(product_id, accessory_id, relatedprd_id, property_id, 0);
            calculateTotalPrice(product_id, relatedprd_id);

            jQuery('select:not(".disableBootstrapChosen")').select2();

            // Setting up redSHOP JavaScript onChangePropertyDropdown trigger
            if (redSHOP.onChangePropertyDropdown.length > 0)
            {
                for(var g = 0, n = redSHOP.onChangePropertyDropdown.length; g < n; g++)
                {
                    new redSHOP.onChangePropertyDropdown[g](allarg, propArr);
                }
            }
        }
    };

    request.open("GET", url, true);
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    request.send(null);
}

function display_image(imgs, product_id, gethover)
{
    if (!redSHOP.RSConfig._('PRODUCT_DETAIL_IS_LIGHTBOX'))
        document.getElementById('a_main_image' + product_id).href = gethover;
    document.getElementById('main_image' + product_id).src = imgs;
}

function display_image_out(imgs, product_id, gethover)
{
    document.getElementById('main_image' + product_id).src = gethover;
}

function display_image_add(img, product_id)
{
    if (document.getElementById('main_image' + product_id)) {
        document.getElementById('main_image' + product_id).src = img;
    }
}
function display_image_add_out(img, product_id)
{
    if (document.getElementById('main_image' + product_id)) {
        if (subproperty_main_image != "")
            document.getElementById('main_image' + product_id).src = subproperty_main_image;
        else
            document.getElementById('main_image' + product_id).src = img;
    }
}

function collectAttributes(productId, accessoryId, relatedProductId)
{
    var prefix,
        attributeIds         = [],
        allProperties        = [],
        totalSubProperties   = [],
        mainprice            = 0,
        price_without_vat    = 0,
        old_price            = 0,
        isStock              = true,
        setPropEqual         = true,
        setSubpropEqual      = true,
        requiredError        = "",
        subPropRequiredError = "",
        layout               = jQuery('#isAjaxBoxOpen').val(),
        preorder             = jQuery('#product_preorder' + productId).val(),
        product_stock        = jQuery('#product_stock' + productId).val(),
        preorder_stock       = jQuery('#preorder_product_stock' + productId).val(),
        preprefix            = "",
        myaccQuan            = 1;

    if (jQuery("#accquantity_" + productId + "_" + accessoryId).length)
    {
        myaccQuan = jQuery("#accquantity_" + productId + "_" + accessoryId).val();
    }

    if (layout == "viewajaxdetail")
    {
        preprefix = "ajax_";
    }

    if (accessoryId != 0)
    {
        prefix            = preprefix + "acc_";
        mainprice         = parseFloat(jQuery('#accessory_id_' + productId + '_' + accessoryId).attr('accessoryprice'));
        price_without_vat = parseFloat(jQuery('#accessory_id_' + productId + '_' + accessoryId).attr('accessorywithoutvatprice'));
        old_price         = mainprice;
    }
    else
    {
        prefix    = (relatedProductId != 0) ? preprefix + "rel_" : preprefix + "prd_";
        mainprice = parseFloat(jQuery('#main_price' + productId).val());
        old_price = parseFloat(jQuery('#product_old_price' + productId).val());

        if (jQuery('#product_price_excluding_price' + productId).length)
        {
            price_without_vat = parseFloat(jQuery('#product_price_excluding_price' + productId).val());
        }
        else if (jQuery('#product_price_no_vat' + productId).length)
        {
            price_without_vat = parseFloat(jQuery('#product_price_no_vat' + productId).val());
        }
    }

    var commonid      = prefix + productId + '_' + accessoryId,
        commonstockid = prefix + productId;

    if (isStock)
    {
        isStock = checkProductStockRoom(product_stock, commonstockid, preorder, preorder_stock);
    }

    // Init attribute dom element
    var attributeDoms = jQuery('[name="attribute_id_' + commonid + '[]"]');

    if (attributeDoms.length <= 0 && redSHOP.RSConfig._('AJAX_CART_BOX') == 1)
    {
        requiredError        = jQuery('#requiedAttribute').val();
        subPropRequiredError = jQuery('#requiedProperty').val();
    }

    // Loop through attributes
    attributeDoms.each(function(index, attribute) {

        var attributeId = attribute.value;
            commonid    = prefix + productId + '_' + accessoryId + '_' + attributeId;

        attributeIds.push(attributeId);

        var propertyDoms = jQuery('[name="property_id_' + commonid + '[]"]');

        if (propertyDoms.length){

            setPropertyImage(productId, 'property_id_' + commonid);

            var seli = 0, requiredProp = [], properties = [];

            // Loop through properties
            propertyDoms.each(function(propIndex, property) {

                if (property.type == 'checkbox' || property.type == 'radio')
                {
                    if (property.checked && property.value != 0)
                    {
                        properties.push(property.value);
                    }
                }
                else
                {
                    if (property.selectedIndex && property.options[property.selectedIndex].value != 0)
                    {
                        properties.push(property.options[property.selectedIndex].value);
                    }
                }

                if (property.required)
                {
                    requiredProp.push(property.getAttribute('attribute_name'));
                }
            });

            // Push to all properties array
            if (properties.length)
            {
                allProperties.push(properties.join(",,"));
            }

            // Check required
            if (requiredProp.length && !properties.length)
            {
                requiredError += Joomla.JText._('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED') + " " + unescape(requiredProp.join("<br>")) + "\n";
            }

            // Collect property Price
            if (setPropEqual && setSubpropEqual)
            {
                var oprandElementId          = 'property_id_' + commonid + '_oprand',
                    priceElementId           = 'property_id_' + commonid + '_proprice',
                    priceWithoutVatElementId = 'property_id_' + commonid + '_proprice_withoutvat'
                    priceOldElementId        = 'property_id_' + commonid + '_prooldprice';

                old_price         = calculateSingleProductPrice(old_price, oprandElementId, priceOldElementId, properties);
                price_without_vat = calculateSingleProductPrice(price_without_vat, oprandElementId, priceWithoutVatElementId, properties);
            }

            // Collect sub-properties
            var isSubproperty = false, allSubProperties = [];

            properties.each(function(propertyId) {

                // Handle stocks
                var stockElementId         = 'property_id_' + commonid + '_stock' + propertyId;
                var preOrderstockElementId = 'property_id_' + commonid + '_preorderstock' + propertyId;

                if (jQuery('#' + stockElementId).length > 0
                    && jQuery('#' + preOrderstockElementId).length > 0
                    && isStock && accessoryId == 0)
                {
                    isStock = checkProductStockRoom(
                                jQuery('#' + stockElementId).val(),
                                commonstockid,
                                preorder,
                                jQuery('#' + preOrderstockElementId).val()
                            );
                }

                var subCommonId   = prefix + productId + '_' + accessoryId + '_' + attributeId + '_' + propertyId;
                var subCommonName = '[name="subproperty_id_' + subCommonId + '[]"]';

                if (jQuery(subCommonName).length)
                {
                    setSubpropertyImage(productId, 'subproperty_id_' + subCommonId);
                    isSubproperty = true;

                    var subProperties = [];

                    if ('radio' == jQuery(subCommonName).attr('type')
                        || 'checkbox' == jQuery(subCommonName).attr('type'))
                    {
                        subProperties.push(jQuery(subCommonName + ':checked').val());
                    }
                    else
                    {
                        subProperties.push(jQuery(subCommonName).val());
                    }

                    subProperties.each(function(subProperty) {

                        var stockElementId         = '#subproperty_id_' + subCommonId + '_stock' + subProperty;
                        var preorderStockElementId = '#subproperty_id_' + subCommonId + '_preOrderStock' + subProperty;

                        if (redSHOP.RSConfig._('USE_STOCKROOM') == 1 && jQuery(stockElementId).length && accessoryId == 0)
                        {
                            isStock = checkProductStockRoom(
                                        jQuery(stockElementId).val(),
                                        commonstockid,
                                        preorder,
                                        jQuery(preorderStockElementId).val()
                                    );
                        }
                    });

                    if (jQuery(subCommonName).attr('required') == 1 && subProperties.length) {
                        subPropRequiredError += jQuery('#subprop_lbl').html() + " " + unescape(jQuery(subCommonName).attr('subpropName')) + "\n";
                    }

                    // Collect sub-property Price
                    if (setPropEqual && setSubpropEqual)
                    {
                        var oprandElementId          = 'subproperty_id_' + subCommonId + '_oprand',
                            priceElementId           = 'subproperty_id_' + subCommonId + '_proprice',
                            priceWithoutVatElementId = 'subproperty_id_' + subCommonId + '_proprice_withoutvat';
                            priceOldElementId        = 'subproperty_id_' + subCommonId + '_prooldprice';

                        old_price         = calculateSingleProductPrice(old_price, oprandElementId, priceOldElementId, subProperties);
                        price_without_vat = calculateSingleProductPrice(price_without_vat, oprandElementId, priceWithoutVatElementId, subProperties);
                    }

                    allSubProperties.push(subProperties.join("::"));
                }

                totalSubProperties.push(allSubProperties.join(",,"));
            });
        }
    });

    mainprice = price_without_vat;

    // Apply vat here in last. Just apply in case price is not below 0.
    if (mainprice > 0)
    {
        mainprice = mainprice * (1 + redSHOP.RSConfig._('BASE_TAX'));
    }

    if (allProperties.length == 0)
    {
        attributeIds = [];
    }

    if (accessoryId != 0)
    {
        jQuery('#acc_attribute_data').val(attributeIds.join("##"));
        jQuery('#acc_property_data').val(allProperties.join("##"));
        jQuery('#acc_subproperty_data').val(totalSubProperties.join("##"));
        jQuery('#accessory_price').val(mainprice);
        jQuery('#accessory_price_withoutvat').val(price_without_vat);
    }
    else
    {
        jQuery('#attribute_data').val(attributeIds.join("##"));
        jQuery('#property_data').val(allProperties.join("##"));
        jQuery('#subproperty_data').val(totalSubProperties.join("##"));
        jQuery('#tmp_product_price').val(mainprice);
        jQuery('#productprice_notvat').val(price_without_vat);
        jQuery('#tmp_product_old_price').val(old_price);
    }

    jQuery('#requiedAttribute').val(requiredError);
    jQuery('#requiedProperty').val(subPropRequiredError);
}

/**
 * Function to update Stock Status on view
 *
 * @param   {string}  stockStatus    Stock Status: 'instock' or 'outofstock' or 'preorder'
 * @param   {string}  commonstockid  Common id for element
 *
 * @return  {void}
 */
redSHOP.updateStockStatusMessage = function(stockStatus, commonstockid){

    var showAddToCart     = 'inline-block',
        showOutOfStock    = 'inline-block',
        showPreOrder      = 'inline-block',
        showStockQuantity = 'inline-block',
        statusMessage     = '';

    if ('instock' == stockStatus)
    {
        showAddToCart     = 'inline-block';
        showStockQuantity = 'inline-block';
        showOutOfStock    = 'none';
        showPreOrder      = 'none';
        statusMessage     = Joomla.JText._('COM_REDSHOP_AVAILABLE_STOCK');
    }
    // When status is outofstock and preorder
    else
    {
        showAddToCart     = 'none';
        showStockQuantity = 'none';

        if ('preorder' == stockStatus)
        {
            showOutOfStock = 'none';
            showPreOrder   = 'inline-block';
            statusMessage  = Joomla.JText._('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE');
        }
        // When outofstock
        else
        {
            showOutOfStock = 'inline-block';
            showPreOrder   = 'none';
            statusMessage  = Joomla.JText._('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');
        }
    }

    // Add to cart Button for instock
    if (document.getElementById('pdaddtocart' + commonstockid)) {
        document.getElementById('pdaddtocart' + commonstockid).style.display = showAddToCart;
    }

    // Out Of Stock enable / disable
    if (document.getElementById('stockaddtocart' + commonstockid)) {
        document.getElementById('stockaddtocart' + commonstockid).style.display = showOutOfStock;
    }

    // Out Of Stock text handle
    if (document.getElementById('stockaddtocart' + commonstockid)) {
        document.getElementById('stockaddtocart' + commonstockid).innerHTML = statusMessage;
    }

    // Pre Order handle
    if (document.getElementById('preordercart' + commonstockid)) {
        document.getElementById('preordercart' + commonstockid).style.display = showPreOrder;
    }

    if (document.getElementById('stockQuantity' + commonstockid))
    {
        document.getElementById('stockQuantity' + commonstockid).style.display = showStockQuantity;
    }
};

/**
 * Check Product Stockroom status
 *
 * @param   {integer}  stockAmount     Stock Amount
 * @param   {string}   commonstockid   Common id for element
 * @param   {string}   preorder        PreOrder type
 * @param   {integer}  preorder_stock  PreOrder stock amount
 *
 * @return  {boolean}   True if stock found otherwise false
 */
function checkProductStockRoom(stockAmount, commonstockid, preorder, preorder_stock) {

    var stockStatus = 'instock';

    if (stockAmount > 0)
    {
        stockStatus = (1 == redSHOP.RSConfig._('USE_AS_CATALOG')) ? 'outofstock' : 'instock';
    }
    else
    {
        if (stockAmount == 0)
        {
            if (
                (preorder == 'global' && redSHOP.RSConfig._('ALLOW_PRE_ORDER') != 1)
                || (preorder == '' && redSHOP.RSConfig._('ALLOW_PRE_ORDER') != 1)
                || (preorder == 'no')
            ){
                stockStatus = (1 == redSHOP.RSConfig._('USE_AS_CATALOG')) ? 'instock' : 'outofstock';
            }
            else
            {
                if (preorder_stock == 0)
                {
                    stockStatus = (1 == redSHOP.RSConfig._('USE_AS_CATALOG')) ? 'preorder' : 'outofstock';
                }
                else
                {
                    stockStatus = (1 == redSHOP.RSConfig._('USE_AS_CATALOG')) ? 'outofstock' : 'preorder';
                }
            }
        }
    }

    redSHOP.updateStockStatusMessage(stockStatus, commonstockid);

    // Setting return type to boolean
    return ('instock' == stockStatus);
}

/**
 * Parse Price using Operand
 *
 * @param   {number}  price            Base Price
 * @param   {string}  oprandElementId  Operand Element Id
 * @param   {string}  priceElementId   Price Element Id
 * @param   {array}  elementArr        Base Element Array
 *
 * @return  {array}                    Return success index[0] and price[1] in array
 */
function calculateSingleProductPrice(price, oprandElementId, priceElementId, elementArr)
{
    var setEqual = true;
        price = parseFloat(price);

    for (var i = 0; i < elementArr.length && elementArr[i] != 0; i++)
    {
        var id       = elementArr[i];
        var oprand   = jQuery('#' + oprandElementId + id).val();
        var subprice = parseFloat(jQuery('#' + priceElementId + id).val());

        if (oprand == "-")
        {
            price -= subprice;
        }
        else if (oprand == "+")
        {
            price += subprice;
        }
        else if (oprand == "*")
        {
            price *= subprice;
        }
        else if (oprand == "/")
        {
            price /= subprice;
        }
        else if (oprand == "=")
        {
            price = subprice;
            setEqual = false;

            break;
        }
    }

    return price;
}

// calculate attribute price
function calculateTotalPrice(productId, relatedProductId) {

    if (productId == 0 || productId == "")
    {
        return false;
    }

    var mainprice                = 0,
        price_without_vat        = 0,
        old_price                = 0,
        accfinalprice_withoutvat = 0,
        product_old_price        = 0,
        qty                      = 1,
        accfinalprice            = parseFloat(collectAccessory(productId, relatedProductId)),
        prefix                   = (relatedProductId != 0) ? relatedProductId : productId,
        wprice                   = 0,
        wrapper_price_withoutvat = 0;

    collectAttributes(productId, 0, relatedProductId);

    if (jQuery('#tmp_product_old_price').length)
    {
        product_old_price = parseFloat(jQuery('#tmp_product_old_price').val());
    }

    if (jQuery('#accessory_price_withoutvat').length)
    {
        accfinalprice_withoutvat = parseFloat(jQuery('#accessory_price_withoutvat').val());
    }

    if (jQuery('#quantity' + prefix).length)
    {
        qty = jQuery('#quantity' + prefix).val();
    }

    if (jQuery('#tmp_product_price').length)
    {
        mainprice = parseFloat(jQuery('#tmp_product_price').val());
    }

    if (jQuery('#hidden_subscription_prize').length)
    {
        mainprice += parseFloat(jQuery('#hidden_subscription_prize').val());
    }

    if (jQuery('#productprice_notvat').length)
    {
        price_without_vat = parseFloat(jQuery('#productprice_notvat').val());
    }

    if (jQuery('#tmp_product_old_price').length)
    {
        old_price = parseFloat(jQuery('#tmp_product_old_price').val());
    }

    // setting wrapper price
    setWrapperComboBox();

    if (jQuery('#wrapper_price').length)
    {
        wprice = parseFloat(jQuery('#wrapper_price').val());
    }

    if (jQuery('#wrapper_price_withoutvat').length)
    {
        wrapper_price_withoutvat = parseFloat(jQuery('#wrapper_price_withoutvat').val());
    }

    final_price_f             = mainprice + accfinalprice + wprice;
    product_price_without_vat = price_without_vat + accfinalprice_withoutvat + wrapper_price_withoutvat;
    product_old_price         = old_price + accfinalprice + wprice;
    savingprice               = parseFloat(product_old_price) - parseFloat(final_price_f);

    if (redSHOP.RSConfig._('SHOW_PRICE') == '1')
    {
        if (!final_price_f
            || (redSHOP.RSConfig._('DEFAULT_QUOTATION_MODE') == '1' && redSHOP.RSConfig._('SHOW_QUOTATION_PRICE') != '1'))
        {
            final_price = getPriceReplacement(final_price_f);
            final_price_novat = getPriceReplacement(product_price_without_vat);
        }
        else
        {
            final_price = number_format(final_price_f, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
            final_price_novat = number_format(product_price_without_vat, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
        }
    }
    else
    {
        final_price = getPriceReplacement(final_price_f);
        final_price_novat = getPriceReplacement(product_price_without_vat);
    }

    if (redSHOP.RSConfig._('SHOW_PRICE') == '1'
        && (redSHOP.RSConfig._('DEFAULT_QUOTATION_MODE') != '1'
            || (redSHOP.RSConfig._('DEFAULT_QUOTATION_MODE') && redSHOP.RSConfig._('SHOW_QUOTATION_PRICE'))
            )
        )
    {
        if (redSHOP.RSConfig._('SHOW_PRICE_WITHOUT_VAT') == '1') {
            jQuery('#produkt_kasse_hoejre_pris_indre' + productId).html(final_price_novat);
            jQuery('#display_product_discount_price' + productId).html(final_price_novat);
        } else {
            jQuery('#produkt_kasse_hoejre_pris_indre' + productId).html(final_price);
            jQuery('#display_product_discount_price' + productId).html(final_price);
        }

        if (!product_price_without_vat)
        {
            product_price_without_vat = getPriceReplacement(product_price_without_vat);
        }
        else
        {
            product_price_without_vat = number_format(product_price_without_vat, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
        }

        jQuery('#display_product_price_without_vat' + productId).html(product_price_without_vat);
        jQuery('#display_product_price_no_vat' + productId).html(product_price_without_vat);


        if (jQuery('#display_product_old_price' + productId).length)
        {
            if (!product_old_price)
            {
                product_old_price = getPriceReplacement(product_old_price);
            }
            else
            {
                product_old_price = number_format(product_old_price, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
            }

            jQuery('#display_product_old_price' + productId).html(product_old_price);
        }

        if (jQuery('#display_product_saving_price' + productId).length)
        {
            savingprice = number_format(savingprice, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
            jQuery('#display_product_saving_price' + productId).html(savingprice);
        }

        jQuery('#rs_selected_accessory_price').html(final_price);
    }
}

// Accessory data collect start
function collectAccessory(product_id, relatedprd_id)
{
    if (product_id == 0 || product_id == "")
    {
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
    var myaccall = [];
    var myaccqua = [];
    var myattall = [];
    var mypropall = [];
    var mysubpropall = [];


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
    n *= redSHOP.RSConfig._('CURRENCY_CONVERT');


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


    if (redSHOP.RSConfig._('CURRENCY_SYMBOL_POSITION') == 'front') {
        display_price = redSHOP.RSConfig._('CURRENCY_SYMBOL_CONVERT') + s;
    } else if (redSHOP.RSConfig._('CURRENCY_SYMBOL_POSITION') == 'behind') {
        display_price = s + redSHOP.RSConfig._('CURRENCY_SYMBOL_CONVERT');
    } else if (redSHOP.RSConfig._('CURRENCY_SYMBOL_POSITION') == 'none') {
        display_price = s;
    } else {
        display_price = redSHOP.RSConfig._('CURRENCY_SYMBOL_CONVERT') + s;
    }

    return display_price;
}

function getPriceReplacement(product_price) {
    var ret = "";
    if (redSHOP.RSConfig._('SHOW_PRICE') == "0") {
        url = redSHOP.RSConfig._('PRICE_REPLACE_URL');
        if (url == "") {
            url = "#";
        }
        ret = "<a href='" + url + "'>" + redSHOP.RSConfig._('PRICE_REPLACE') + "</a>";
    }
    if (redSHOP.RSConfig._('SHOW_PRICE') == "1" && product_price == 0) {
        url = redSHOP.RSConfig._('ZERO_PRICE_REPLACE_URL');
        if (url == "") {
            url = "#";
        }
        ret = "<a href='" + url + "'>" + redSHOP.RSConfig._('ZERO_PRICE_REPLACE') + "</a>";

        // In any cases both values are null than we should not show this link
        if (url === null && redSHOP.RSConfig._('ZERO_PRICE_REPLACE') === null)
        {
            return '';
        }
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
    var selValue = 0;
    var propName = document.getElementById(propertyObj);

    if (propName) {
        if (propName.type == 'checkbox' || propName.type == 'radio') {
            var propNameObj = document.getElementsByName(propertyObj + "[]");
            for (var p = 0; p < propNameObj.length; p++) {
                var borderstyle = "";
                selValue = propNameObj[p].value;
                if (propNameObj[p].checked) {
                    borderstyle = "1px solid";
                }
                if (document.getElementById(propertyObj + "_propimg_" + selValue)) {

                    document.getElementById(propertyObj + "_propimg_" + selValue).style.border = borderstyle;
                }
            }
        } else {

            for (var p = 0; p < propName.length; p++) {

                var borderstyle = "";
                selValue = propName[p].value;

                if (propName[propName.selectedIndex].value == selValue) {
                    borderstyle = "1px solid";
                }
                if (document.getElementById(propertyObj + "_propimg_" + selValue)) {

                    document.getElementById(propertyObj + "_propimg_" + selValue).style.border = borderstyle;
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
                selValue = subpropNameObj[p].value;
                if (subpropNameObj[p].checked) {
                    borderstyle = "1px solid";
                }
                if (document.getElementById(subpropertyObj + "_subpropimg_" + selValue)) {
                    document.getElementById(subpropertyObj + "_subpropimg_" + selValue).style.border = borderstyle;
                }
            }
        } else {
            for (var p = 0; p < subpropName.length; p++) {

                var borderstyle = "";
                selValue = subpropName[p].value;
                if (subpropName[subpropName.selectedIndex].value == selValue) {
                    borderstyle = "1px solid";
                }
                if (document.getElementById(subpropertyObj + "_subpropimg_" + selValue)) {
                    document.getElementById(subpropertyObj + "_subpropimg_" + selValue).style.border = borderstyle;
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

        var newhref = '';

        if (document.getElementById('a_main_image' + product_id)) {
            var tmphref = document.getElementById('a_main_image' + product_id).href;
            if ('undefined' !== typeof tmphref)
            {
                tmphref = tmphref.split("");
                newhref = tmphref.reverse();
                newhref = newhref.join("");
                tmphref = newhref.split(".");
                tmphref = tmphref[0].split("");
                newhref = tmphref.reverse();
                newhref = newhref.join("");
            }
        }
        else {
            var tmphref = document.getElementById('main_image' + product_id).src;
            if ('undefined' !== typeof tmphref)
            {
                tmphref = tmphref.split("");
                newhref = tmphref.reverse();
                newhref = newhref.join("");
                tmphref = newhref.split(".");
                tmphref = tmphref[0].split("");
                newhref = tmphref.reverse();
                newhref = newhref.join("");
                newhref = newhref.split("&");
                newhref = newhref[0];
            }
        }

        // change extension to lowercase
        newhref = newhref.toLowerCase();

        if (newhref == "jpg" || newhref == "jpeg" || newhref == "png" || newhref == "gif" || newhref == "bmp") {
            changehref = 1;
        }
    }

    var url = redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=product&task=displayAdditionImage&redview=" + redSHOP.RSConfig._('REDSHOP_VIEW') + "&redlayout=" + redSHOP.RSConfig._('REDSHOP_LAYOUT') + "&tmpl=component";
    url = url + suburl;

    request = getHTTPObject();
    request.onreadystatechange = function () {

        if (request.readyState == 4) {
            txtresponse = request.responseText;
            var arrResponse = txtresponse.split("`_`");

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
            }

            document.getElementById('main_image' + product_id).src = arrResponse[4];
            document.getElementsByClassName('product_more_videos')[0].innerHTML = arrResponse[16];

            if (document.getElementById('additional_images' + product_id) && arrResponse[1] != "") {
                document.getElementById('additional_images' + product_id).innerHTML = arrResponse[1];
            }
            if (document.getElementById('hidden_attribute_cartimage' + product_id)) {
                document.getElementById('hidden_attribute_cartimage' + product_id).value = arrResponse[12];
            }
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
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
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
        var vidoptions = {handler: 'iframe', size: {x: 800, y: 500}};
        redBOX.initialize({});

        if (parameters.mainImage) {
            redBOX.assign($$("a[rel='myallimg']"), imgoptions);
        }
        else {
            redBOX.assign($$(".additional_image > a[rel='myallimg']"), imgoptions);
            redBOX.assign($$("[id^='additional_vids_'] > a.modal"), vidoptions);
        }

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
        var tmpval = obj[i].id.substr(7);
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

    if (document.getElementById('calc_height')) {
        calHeight = document.getElementById('calc_height').value;
        if (calHeight == "") {
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_HEIGHT'));
            return false;
        }

    }

    if (document.getElementById('calc_width')) {
        calWidth = document.getElementById('calc_width').value;
        if (calWidth == "") {
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_WIDTH'));
            return false;
        }
    }

    if (document.getElementById('calc_depth')) {
        calDepth = document.getElementById('calc_depth').value;
        if (calDepth == "") {
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_DEPTH'));
            return false;
        }
    }

    if (document.getElementById('calc_radius')) {
        calRadius = document.getElementById('calc_radius').value;
        if (calRadius == "") {
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_RADIUS'));
            return false;
        }
    }

    if (document.getElementById('discount_calc_unit')) {
        calUnit = document.getElementById('discount_calc_unit').value;
        if (calUnit == 0) {
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_UNIT'));
            return false;
        }
    }

    if (document.getElementById('calc_unit')) {
        globalcalUnit = document.getElementById('calc_unit').value;
    }

    // new extra enhancement of discount calculator added
    var pdcoptionid = [];
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

    http = getHTTPObject();

    if (http == null) {
        alert("Your browser does not support XMLHTTP!");
        return;
    }

    http.onreadystatechange = function () {
        if (http.readyState == 4) {

            var areaPrice = http.responseText;

            areaPrice = areaPrice.replace(/^\s+|\s+$/g, "");

            if (areaPrice == "fail") {

                alert(Joomla.JText._('COM_REDSHOP_NOT_AVAILABLE'));
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
                var formatted_price_per_area = number_format(price_per_area, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));

                var formatted_price_per_piece = number_format(price_per_piece, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));

                if (qty <= 0)
                    qty = 1;

                price_total = parseFloat(price_per_piece) * qty;

                var formatted_price_total = number_format(price_total, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));

                output = areaPrice[3] + total_area + "<br />";
                output += areaPrice[4] + formatted_price_per_area + "<br />";
                output += areaPrice[5] + formatted_price_per_piece + "<br />";
                output += areaPrice[6] + formatted_price_total;

                if (document.getElementById('discount_cal_final_price')) {
                    document.getElementById('discount_cal_final_price').innerHTML = output;
                }

                if (document.getElementById('main_price' + proid))
                {
                    var product_main_price = document.getElementById('main_price' + proid).value;

                    if (redSHOP.RSConfig._('SHOW_PRICE') == '1' && ( redSHOP.RSConfig._('DEFAULT_QUOTATION_MODE') != '1' || (redSHOP.RSConfig._('DEFAULT_QUOTATION_MODE') && redSHOP.RSConfig._('SHOW_QUOTATION_PRICE'))))
                    {
                        var product_total = parseFloat(product_main_price) + parseFloat(price_total);

                        if (areaPrice[8] == 1) {
                            var product_price_excl_vat = price_total + price_excl_vat * qty;
                        } else {
                            var product_price_excl_vat = price_total * qty;
                        }

                        formatted_price_total = number_format(product_total, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
                        formatted_product_price_excl_vat = number_format(product_price_excl_vat, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
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

                    calculateTotalPrice(proid, 0);
                }
            }
        }
    };

    http.open("GET", redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=cart&task=discountCalculator&product_id=" + proid + "&calcHeight=" + calHeight + "&calcWidth=" + calWidth + "&calcDepth=" + calDepth + "&calcRadius=" + calRadius + "&calcUnit=" + calUnit + "&pdcextraid=" + pdcoptionid + "&tmpl=component", true);
    http.setRequestHeader("X-Requested-With", "XMLHttpRequest");
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
    var rl = [];
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
    var rl = [];
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

    if (product_id == 0 || product_id == "")
    {
        return false;
    }

    redSHOP.updateCartExtraFields(
        jQuery('#' + frmUserfieldName + ' :input:not(:button, :hidden)'),
        product_id,
        '#' + frmCartName
    );

    jQuery('#requiedAttribute').val(jQuery('#' + frmCartName + ' [name=requiedAttribute]').attr('reattribute'));

    jQuery('#requiedProperty').val(jQuery('#' + frmCartName + ' [name=requiedProperty]').attr('reproperty'));

    if (giftcard_id == 0)
    {
        //get selected attribute,property,subproperty data and total price
        calculateTotalPrice(product_id, relatedprd_id);
    }

    //set selected attribute,property,subproperty data and total price to Add to cart form
    if (!setAddtocartForm(frmCartName, product_id))
    {
        return false;
    }

    return true;
}

function setAddtocartForm(frmCartName, product_id) {
    var frm = document.getElementById(frmCartName);

    if (document.getElementById('Itemid')) {
        frm.Itemid.value = document.getElementById('Itemid').value;
    }
    if (document.getElementById('attribute_data')) {
        frm.attribute_data.value = document.getElementById('attribute_data').value;
    }
    if (document.getElementById('property_data')) {
        frm.property_data.value = document.getElementById('property_data').value;
    }
    if (document.getElementById('subproperty_data')) {
        frm.subproperty_data.value = document.getElementById('subproperty_data').value;
    }
    if (document.getElementById('accessory_data')) {
        frm.accessory_data.value = document.getElementById('accessory_data').value;
    }
    if (document.getElementById('acc_quantity_data')) {
        frm.acc_quantity_data.value = document.getElementById('acc_quantity_data').value;
    }
    if (document.getElementById('acc_attribute_data')) {
        frm.acc_attribute_data.value = document.getElementById('acc_attribute_data').value;
    }
    if (document.getElementById('acc_property_data')) {
        frm.acc_property_data.value = document.getElementById('acc_property_data').value;
    }
    if (document.getElementById('acc_subproperty_data')) {
        frm.acc_subproperty_data.value = document.getElementById('acc_subproperty_data').value;
    }
    if (document.getElementById('accessory_price')) {
        frm.accessory_price.value = document.getElementById('accessory_price').value;
    }
    if (document.getElementById('requiedAttribute')) {
        frm.requiedAttribute.value = document.getElementById('requiedAttribute').value;
    }
    if (document.getElementById('requiedProperty')) {
        frm.requiedProperty.value = document.getElementById('requiedProperty').value;
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
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_HEIGHT'));
            return false;
        } else {
            frm.calcHeight.value = calHeight;
        }

    }

    if (document.getElementById('calc_width')) {
        var calWidth = document.getElementById('calc_width').value;
        if (calWidth == "") {
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_WIDTH'));
            return false;
        } else {
            frm.calcWidth.value = calWidth;
        }
    }

    if (document.getElementById('calc_depth')) {
        var calDepth = document.getElementById('calc_depth').value;
        if (calDepth == "") {
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_DEPTH'));
            return false;
        } else {
            frm.calcDepth.value = calDepth;
        }
    }

    if (document.getElementById('calc_radius')) {
        var calRadius = document.getElementById('calc_radius').value;
        if (calRadius == "") {
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_RADIUS'));
            return false;
        } else {
            frm.calcRadius.value = calRadius;
        }
    }

    if (document.getElementById('discount_calc_unit')) {
        calUnit = document.getElementById('discount_calc_unit').value;
        if (calUnit == 0) {
            alert(Joomla.JText._('COM_REDSHOP_PLEASE_INSERT_UNIT'));
            return false;
        } else {
            frm.calcUnit.value = calUnit;
        }
    }

    // new extra enhancement of discount calculator added
    var pdcoptionid = [];
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
            alert(Joomla.JText._('COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN'));
            return false;
        } else {
            frm.subscription_id.value = subId;
        }
    }

    return true;
}

/**
 * To set redSHOP Validate Add to cart trigger functions
 *
 * @type  {Array}
 */
var redShopAddtocartValidationJsTrigger = [];

function checkAddtocartValidation(frmCartName, product_id, relatedprd_id, giftcard_id, frmUserfieldName, totAttribute, totAccessory, totUserfield) {


    if (product_id == 0 || product_id == "") {
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

    var arr_attr_id = [];
    var arr_subattr_id = [];
    var sel_i = 0;
    var sub_sel_i = 0;
    // User field validation

    if (redSHOP.RSConfig._('AJAX_CART_BOX') == 0) {
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

        // Setting up redSHOP JavaScript Add to cart trigger
        if (redShopAddtocartValidationJsTrigger.length > 0)
        {
            for(var g = 0, n = redShopAddtocartValidationJsTrigger.length; g < n; g++)
            {
                if (redShopAddtocartValidationJsTrigger[g](arguments) == false)
                {
                    return false;
                }
            }
        }

        document.getElementById(frmCartName).submit();

    } else {
        /*
         * count total attribute + extra fields
         * Where natt = number of total attribute
         * And nextra = number of extra fields
         */
        var ntotal = parseInt(totAttribute) + parseInt(totAccessory) + parseInt(totUserfield);
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
            var cansubmit = true;

            var ret = userfieldValidation("extrafields" + product_id);
            if (!ret) {
                cansubmit = false;
            }

            var requiedAttribute = document.getElementById(frmCartName).requiedAttribute.value;
            var requiedProperty = document.getElementById(frmCartName).requiedProperty.value;

            if (requiedAttribute != "") {
                cansubmit = false;
            }

            if (requiedProperty != "") {
                cansubmit = false;
            }

            // Setting up redSHOP JavaScript Add to cart trigger
            if (redShopAddtocartValidationJsTrigger.length > 0)
            {
                for(var g = 0, n = redShopAddtocartValidationJsTrigger.length; g < n; g++)
                {
                    if (redShopAddtocartValidationJsTrigger[g](arguments) == false)
                    {
                        return;
                    }
                }
            }

            if (ntotal > 0 && cansubmit == false) {
                displayAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield);
            } else {

                submitAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield);
            }
        }
    }
}

function displayAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield) {
    if (product_id == 0 || product_id == "") {
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
            imgfieldNamefrmId = redSHOP.filterExtraFieldName(extrafields[ex].id);
            extrafieldNames += imgfieldNamefrmId; 	// make Id as Name
            if ((extrafields.length - 1) != ex) {
                extrafieldNames += ',';
            }
        }
        else if (extrafields[ex].type == 'checkbox') {
            fieldNamefrmId = redSHOP.filterExtraFieldName(extrafields[ex].id);

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

            rdo_fieldNamefrmId = redSHOP.filterExtraFieldName(extrafields[ex].id);

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

            selmulti_fieldNamefrmId = redSHOP.filterExtraFieldName(extrafields[ex].id);

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
                cal_fieldNamefrmId = redSHOP.filterExtraFieldName(calName);
                extrafieldNames += "," + cal_fieldNamefrmId + ",";
            }
        }
    }
    // End
    var subscription_data = "";
    if (document.getElementById('hidden_subscription_id')) {

        subId = document.getElementById('hidden_subscription_id').value;
        if (subId == 0 || subId == "") {
            alert(Joomla.JText._('COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN'));
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

    var accarr = [];
    if (totAccessory > 0 && requiedAccessory != "" && requiedAccessory != 0) {
        accarr = requiedAccessory.split("@@");
        if (totAccessory == accarr.length) {
            totAccessory = 0;
        }
    }

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

        var detailurl = redSHOP.RSConfig._('SITE_URL') + "index.php?" + params;

        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                var responce = request.responseText;
                var options = {url: detailurl, handler: 'html', size: {x: parseInt(redSHOP.RSConfig._('AJAX_DETAIL_BOX_WIDTH')), y: parseInt(redSHOP.RSConfig._('AJAX_DETAIL_BOX_HEIGHT'))}, htmldata: responce};
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
        request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        request.send(params);
    }

}

/**
 * To get redSHOP Add to cart Extra add to cart parameters
 * Global JS variable to set Plugin additional add to cart parameters
 *
 * @type  {Object}
 */
var getExtraParamsArray = {};

/**
 * To set redSHOP Add to cart trigger functions
 *
 * @type  {Array}
 */
var redShopJsTrigger = [];

function submitAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield)
{
    var frm = document.getElementById(frmCartName);

    var id = '';
    var set = false;

    if (!userfieldValidation("extrafields" + product_id))
    {
        return false;
    }

    var requiedAttribute = jQuery('#' + frmCartName + ' [name=requiedAttribute]').val();
    var requiedProperty = jQuery('#' + frmCartName + ' [name=requiedProperty]').val();

    if (requiedAttribute != 0 && requiedAttribute != "") {
        alert(requiedAttribute);
        return false;
    }
    if (requiedProperty != 0 && requiedProperty != "") {
        alert(requiedProperty);
        return false;
    }

    extraFieldPost = redSHOP.updateAjaxCartExtraFields(
                        jQuery('[name^="extrafields' + product_id + '"]'),
                        product_id
                    ).join('&');

    var subscription_data = "";

    if (document.getElementById('hidden_subscription_id')) {
        subId = document.getElementById('hidden_subscription_id').value;
        if (subId == 0 || subId == "") {
            alert(Joomla.JText._('COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN'));
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
    var params = "ajax_cart_box=1";

    params = params + "&Itemid=" + frm.Itemid.value + id;
    params = params + "&category_id=" + frm.category_id.value;
    params = params + "&attribute_data=" + frm.attribute_data.value;
    params = params + "&property_data=" + frm.property_data.value;
    params = params + "&subproperty_data=" + frm.subproperty_data.value;
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

    params = params + subscription_data + '&' + extraFieldPost;

    // Setting up redSHOP JavaScript Add to cart trigger
    if (redShopJsTrigger.length > 0)
    {
        for(var g = 0, n = redShopJsTrigger.length; g < n; g++)
        {
            new redShopJsTrigger[g](arguments);
        }
    }

    /*
     * getExtraParamsArray is a global JS variable to set additional add to cart parameters
     * using redshop_product plugin.
     * Example: getExtraParamsArray.foo = 'bar';
     */
    if ('object' === typeof getExtraParamsArray)
    {
        for(key in getExtraParamsArray)
        {
            params += '&' + key + '=' + getExtraParamsArray[key];
        }
    }

    request.open("POST", redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=cart&task=add&tmpl=component", false);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");

    var aj_flag = true;
    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            var responce = request.responseText;
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
            if (document.getElementById('mod_cart_checkout_ajax')) {
                document.getElementById('mod_cart_checkout_ajax').style.display = "";
            }

            // End
            var newurl = redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=product&pid=" + product_id + "&r_template=cartbox&tmpl=component";

            request_inner = getHTTPObject();

            request_inner.onreadystatechange = function () {
                if (request_inner.readyState == 4 && request_inner.status == 200 && aj_flag) {
                    var responcebox = request_inner.responseText;

                    aj_flag = false;

                    var options = {url: newurl, handler: 'html', size: {x: parseInt(redSHOP.RSConfig._('AJAX_BOX_WIDTH')), y: parseInt(redSHOP.RSConfig._('AJAX_BOX_HEIGHT'))}, htmldata: responcebox, onOpen: function () {
                        if (redSHOP.RSConfig._('AJAX_CART_DISPLAY_TIME') > 0) {
                            var fn = function () {
                                this.close();
                            }.bind(this).delay(redSHOP.RSConfig._('AJAX_CART_DISPLAY_TIME'));
                        }
                    }};
                    redBOX.initialize({});
                    document.ajaxbox = redBOX.open(null, options);

                }
            };
            request_inner.open("GET", newurl, true);
            request_inner.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            request_inner.send(null);

        }
    };
    request.send(params);
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

    var arr_attr_id = [];
    var arr_subattr_id = [];
    var sel_i = 0;
    var sub_sel_i = 0;
    // User field validation

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
    } else {
        // submit form from product detail page
        /*
         * ntotal = count total attribute + extra fields
         * if attribute is not available then cart will submit directly
         *
         */

        submitAjaxCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield);
        return true;

    }
}

var mainpro_id = [];
var totatt = [];
var totcount_no_user_field = [];

function productalladdprice(my) {


    var wishList = 1;
    mainpro_id = document.frm.product_id.value.split(",");
    totatt = document.frm.totacc_id.value.split(",");
    totcount_no_user_field = document.frm.totcount_no_user_field.value.split(",");

    mainpro_id.length = mainpro_id.length - 1;


    for (var i = 0; i < mainpro_id.length; i++) {

        if (mainpro_id[i] != "") {

            if (displayAddtocartForm('addtocart_prd_' + mainpro_id[i], mainpro_id[i], '0', '0', 'user_fields_form')) {

                if (!checkAddtocartwishlistValidation('addtocart_prd_' + mainpro_id[i], mainpro_id[i], '0', '0', 'user_fields_form', totatt[i], '', totcount_no_user_field[i], wishList)) {
                    return false;
                }
            }
        }
    }

    submitAjaxwishlistCartdetail('addtocart_prd_' + mainpro_id[0], mainpro_id[0], 0, 0, totatt[0], 0, totcount_no_user_field[0], my);
}


var d = 0;
function submitAjaxwishlistCartdetail(frmCartName, product_id, relatedprd_id, giftcard_id, totAttribute, totAccessory, totUserfield, my)
{
    displayAddtocartForm('addtocart_prd_' + mainpro_id[d], mainpro_id[d], '0', '0', 'user_fields_form');

    var frm = document.getElementById(frmCartName),
        params = [],
        requiedAttribute = jQuery('#' + frmCartName + ' [name="requiedAttribute"]').val(),
        requiedProperty = jQuery('#' + frmCartName + ' [name="requiedProperty"]').val();

    if (!userfieldValidation("extrafields" + product_id))
    {
        return false;
    }

    if (requiedAttribute != 0 && requiedAttribute != "")
    {
        alert(requiedAttribute);
        return false;
    }

    if (requiedProperty != 0 && requiedProperty != "")
    {
        alert(requiedProperty);
        return false;
    }

    var extraFieldPost = redSHOP.updateAjaxCartExtraFields(
                        jQuery('[name^="extrafields' + product_id + '"]'),
                        product_id
                    ).join('&');

    if (jQuery('#hidden_subscription_id').length > 0)
    {
        subId = jQuery('#hidden_subscription_id').val();

        if (subId == 0 || subId == "")
        {
            alert(Joomla.JText._('COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN'));
            return false;
        }

        params.push("subscription_id=" + subId);
    }

    if (jQuery('#giftcard_id').length > 0)
    {
        params.push("giftcard_id=" + product_id);
        params.push("reciver_email=" + jQuery('#reciver_email').val());
        params.push("reciver_name=" + jQuery('#reciver_name').val());
        params.push("customer_amount=" + jQuery('#customer_amount').val());
    }
    else
    {
        params.push("product_id=" + product_id);
    }

    request = getHTTPObject();

    params.push("Itemid=" + frm.Itemid.value);
    params.push("category_id=" + frm.category_id.value);
    params.push("attribute_data=" + frm.attribute_data.value);
    params.push("property_data=" + frm.property_data.value);
    params.push("subproperty_data=" + frm.subproperty_data.value);
    params.push("requiedAttribute=" + frm.requiedAttribute.value);
    params.push("requiedProperty=" + frm.requiedProperty.value);
    params.push("accessory_data=" + frm.accessory_data.value);
    params.push("acc_quantity_data=" + frm.acc_quantity_data.value);
    params.push("acc_attribute_data=" + frm.acc_attribute_data.value);
    params.push("acc_property_data=" + frm.acc_property_data.value);
    params.push("acc_subproperty_data=" + frm.acc_subproperty_data.value);
    params.push("accessory_price=" + frm.accessory_price.value);
    params.push("sel_wrapper_id=" + frm.sel_wrapper_id.value);
    params.push("quantity=1");
    params.push("pdcextraid=" + frm.pdcextraid.value);

    if (document.getElementById('calc_height')) {
        params.push("calcHeight=" + frm.calcHeight.value);
    }
    if (document.getElementById('calc_width')) {
        params.push("calcWidth=" + frm.calcWidth.value);
    }
    if (document.getElementById('calc_depth')) {
        params.push("calcDepth=" + frm.calcDepth.value);
    }
    if (document.getElementById('calc_radius')) {
        params.push("calcRadius=" + frm.calcRadius.value);
    }
    if (document.getElementById('calc_unit')) {
        params.push("calcUnit=" + frm.calcUnit.value);
    }

    var postVars = params.join('&') + '&' + extraFieldPost;

    // Setting up redSHOP JavaScript Add to cart trigger
    if (redShopJsTrigger.length > 0)
    {
        for(var g = 0, n = redShopJsTrigger.length; g < n; g++)
        {
            new redShopJsTrigger[g](arguments);
        };
    };

    /*
     * getExtraParamsArray is a global JS variable to set additional add to cart parameters
     * using redshop_product plugin.
     * Example: getExtraParamsArray.foo = 'bar';
     */
    if ('object' === typeof getExtraParamsArray)
    {
        for(key in getExtraParamsArray)
        {
            postVars += '&' + key + '=' + getExtraParamsArray[key];
        }
    };

    var url = redSHOP.RSConfig._('SITE_URL') + "index.php";

    if (my == 1 || my == 2)
    {
        url += "?option=com_redshop&view=product&task=addtowishlist&wid=1&ajaxon=1&tmpl=component";
    }
    else
    {
        url += "?option=com_redshop&view=cart&task=add&tmpl=component&ajax_cart_box=1";
    }

    if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent))
        request.open("POST", url, true);
    else
        request.open("POST", url, false);

    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("Content-length", postVars.length);
    request.setRequestHeader("Connection", "close");

    request.onreadystatechange = function () {
        if (request.readyState < 4) {

            jQuery('#saveid, #allcart').html('<font size="1" color="red">Processing...</font>');
        }

        if (request.readyState == 4) {

            var responce = request.responseText;

            responce = responce.split("`");

            if (responce[1] == "0") {
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
                window.location = redSHOP.RSConfig._('SITE_URL') + "index.php?wishlist=1&option=com_redshop&view=login";
            } else if (my == 2) {
                return false;
            } else {
                window.location = redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=cart";
            }

            jQuery("#saveid, #allcart").html('');

            // cart module
            if (responce[1])
            {
                jQuery('#mod_cart_total').html(responce[1]);
            }

            if (responce[2])
            {
                jQuery('#rs_promote_free_shipping_div').html(responce[2]);
            }

            jQuery('#mod_cart_checkout_ajax').hide();
        }
    };

    request.send(postVars);
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
            fieldNamefrmId = redSHOP.filterExtraFieldName(extrafields[ex].id);
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

            rdo_fieldNamefrmId = redSHOP.filterExtraFieldName(extrafields[ex].id);

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
                if (extrafields[ex].checked)
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
            selmulti_fieldNamefrmId = redSHOP.filterExtraFieldName(extrafields[ex].id);
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

            imgfieldNamefrmId = redSHOP.filterExtraFieldName(extrafields[ex].id);

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
                cal_fieldNamefrmId = redSHOP.filterExtraFieldName(tmpName[1]);

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
                cal_fieldNamefrmId = redSHOP.filterExtraFieldName(calName);
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


    var url = redSHOP.RSConfig._('SITE_URL') + "index.php?" + params;


    if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent))
        request.open("POST", url, true);
    else
        request.open("POST", url, false);

    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
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
    request.send(params);
}

function getStocknotify(product_id, property_id, subproperty_id) {

    var url = redSHOP.RSConfig._('SITE_URL') + "index.php?option=com_redshop&view=product&task=addNotifystock&tmpl=component&product_id=" + product_id;
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
    request.open("GET", url, true);
    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
}
