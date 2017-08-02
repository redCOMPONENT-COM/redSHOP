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

function updateGLSLocation(zipcode) {
	var url = redSHOP.RSConfig._('SITE_URL') + 'index.php?tmpl=component&option=com_redshop&view=checkout&task=updateGLSLocation';
	url += "&zipcode=" + zipcode;

	jQuery.ajax({
		url: url,
		type: 'GET'
	})
	.done(function(response) {
		jQuery('#rs_locationdropdown').html(response);
		jQuery('select:not(".disableBootstrapChosen")').select2();
	})
	.fail(function() {
		console.warn("error");
	});
}

function number_format(number, decimals, dec_point, thousands_sep) {
	var n = number,
		prec = decimals;

	var toFixedFix = function(n, prec) {
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
	} else if (prec >= 1 && decPos === -1) {
		s += dec + new Array(prec).join(0) + '0';
	}

	// setting final price with currency Symbol
	var display_price = "";

	if (redSHOP.RSConfig._('CURRENCY_SYMBOL_POSITION') == 'front') {
		display_price = redSHOP.RSConfig._('REDCURRENCY_SYMBOL') + "&nbsp;" + s;
	} else if (redSHOP.RSConfig._('CURRENCY_SYMBOL_POSITION') == 'behind') {
		display_price = s + "&nbsp;" + redSHOP.RSConfig._('REDCURRENCY_SYMBOL');
	} else if (redSHOP.RSConfig._('CURRENCY_SYMBOL_POSITION') == 'none') {
		display_price = s;
	} else {
		display_price = redSHOP.RSConfig._('REDCURRENCY_SYMBOL') + "&nbsp;" + s;
	}

	return display_price;
}

function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

function collectOfflineAttributes(product_id, accessory_id, unique_id) {
	var prefix;
	var attrArr = new Array();
	var allpropArr = new Array();
	var tolallsubpropArr = new Array();
	var mainprice = 0;
	var maintax = 0;
	var isStock = true;
	var setPropEqual = true;
	var setSubpropEqual = true;
	var acc_error = "";
	var acc_error_alert = false;

	if (accessory_id != 0) {
		prefix = "acc_";
		if (document.getElementById('accessory_id_' + product_id + '_' + accessory_id + unique_id)) {
			mainprice = parseFloat(document.getElementById('accessory_id_' + product_id + '_' + accessory_id + unique_id).getAttribute('accessoryprice'));
			maintax = parseFloat(document.getElementById('accessory_id_' + product_id + '_' + accessory_id + unique_id).getAttribute('accessorypricevat'));
		}
	} else {
		prefix = "prd_";
		if (document.getElementById('main_price' + unique_id)) {
			mainprice = parseFloat(document.getElementById('main_price' + unique_id).value);
		}
		if (document.getElementById("product_vatprice" + unique_id)) {
			maintax = parseFloat(document.getElementById("product_vatprice" + unique_id).value);
		}
	}
	var commonid = unique_id + prefix + product_id + '_' + accessory_id;

	if (document.getElementsByName('attribute_id_' + commonid + '[]')) {
		var attrName = document.getElementsByName('attribute_id_' + commonid + '[]');
		for (var i = 0; i < attrName.length; i++) {
			attrArr[i] = attrName[i].value;
		}
	}
	for (var i = 0; i < attrArr.length; i++) {
		var attribute_id = attrArr[i];
		commonid = unique_id + prefix + product_id + '_' + accessory_id + '_' + attribute_id;
		var propId = document.getElementById('property_id_' + commonid);
		if (propId) {
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

			allpropArr[i] = propArr.join(",,");

			// required check
			if (propId.getAttribute('required') == 1 && propArr.length == 0) {
				if (document.getElementById('att_lebl')) {
					acc_error += document.getElementById('att_lebl').innerHTML + " ";
				}
				acc_error += unescape(propId.getAttribute('attribute_name')) + "\n";
				acc_error_alert = true;
			}

			// Collect property Price start
			if (setPropEqual && setSubpropEqual) {
				var oprandElementId = 'property_id_' + commonid + '_oprand';
				var priceElementId = 'property_id_' + commonid + '_proprice';
				var taxElementId = 'property_id_' + commonid + '_protax';
				var retProArr = calculateOfflineProductPrice(mainprice, maintax, oprandElementId, priceElementId, taxElementId, propArr);
				//              setPropEqual = retProArr[0];
				mainprice = retProArr[1];
				maintax = retProArr[2];
			}

			// Collect subproperty start
			var isSubproperty = false;
			var allsubpropArr = new Array();
			for (var p = 0; p < propArr.length; p++) {
				var property_id = propArr[p];

				var subcommonid = unique_id + prefix + product_id + '_' + accessory_id + '_' + attribute_id + '_' + property_id;
				if (document.getElementById('subproperty_id_' + subcommonid)) {
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
					/******Collect subproperty Price start*******/
					if (setPropEqual && setSubpropEqual) {
						var oprandElementId = 'subproperty_id_' + subcommonid + '_oprand';
						var priceElementId = 'subproperty_id_' + subcommonid + '_proprice';
						var taxElementId = 'subproperty_id_' + subcommonid + '_protax';
						var retSubArr = calculateOfflineProductPrice(mainprice, maintax, oprandElementId, priceElementId, taxElementId, subpropArr);
						//                      setSubpropEqual = retSubArr[0];
						mainprice = retSubArr[1];
						maintax = retSubArr[2];
					}

					allsubpropArr[p] = subpropArr.join("::");
				}
			}
			tolallsubpropArr[i] = allsubpropArr.join(",,");
		}
	}

	if (accessory_id != 0) {
		if (document.getElementById("acc_attribute_data" + unique_id)) {
			document.getElementById("acc_attribute_data" + unique_id).value = attrArr.join("##");
		}
		if (document.getElementById("acc_property_data" + unique_id)) {
			document.getElementById("acc_property_data" + unique_id).value = allpropArr.join("##");
		}
		if (document.getElementById("acc_subproperty_data" + unique_id)) {
			document.getElementById("acc_subproperty_data" + unique_id).value = tolallsubpropArr.join("##");
		}
		if (document.getElementById("accessory_price" + unique_id)) {
			document.getElementById("accessory_price" + unique_id).value = mainprice;
		}
		if (document.getElementById("accessory_vatprice" + unique_id)) {
			document.getElementById("accessory_vatprice" + unique_id).value = maintax;
		}
	} else {
		if (document.getElementById("attribute_data" + unique_id)) {
			document.getElementById("attribute_data" + unique_id).value = attrArr.join("##");
		}
		if (document.getElementById("property_data" + unique_id)) {
			document.getElementById("property_data" + unique_id).value = allpropArr.join("##");
		}
		if (document.getElementById("subproperty_data" + unique_id)) {
			document.getElementById("subproperty_data" + unique_id).value = tolallsubpropArr.join("##");
		}
		if (document.getElementById("tmp_product_price" + unique_id)) {
			document.getElementById("tmp_product_price" + unique_id).value = mainprice;
		}
		if (document.getElementById("tmp_product_vatprice" + unique_id)) {
			document.getElementById("tmp_product_vatprice" + unique_id).value = maintax;
		}
	}
	if (document.getElementById("requiedAttribute" + unique_id)) {
		document.getElementById("requiedAttribute" + unique_id).value = acc_error;
	}
}

function calculateOfflineProductPrice(price, tax, oprandElementId, priceElementId, taxElementId, elementArr) {
	var setEqual = true;
	for (var i = 0; i < elementArr.length; i++) {
		var id = elementArr[i];

		var oprand = document.getElementById(oprandElementId + id).value;
		var subprice = document.getElementById(priceElementId + id).value;
		var subtax = document.getElementById(taxElementId + id).value;

		if (oprand == "-") {
			price -= parseFloat(subprice);
			tax -= parseFloat(subtax);
		} else if (oprand == "+") {
			price += parseFloat(subprice);
			tax += parseFloat(subtax);
		} else if (oprand == "*") {
			price *= parseFloat(subprice);
			tax *= parseFloat(subtax);
		} else if (oprand == "/") {
			price /= parseFloat(subprice);
			tax /= parseFloat(subtax);
		} else if (oprand == "=") {
			price = parseFloat(subprice);
			tax = parseFloat(subtax);
			setEqual = false;
			break;
		}
	}
	var retArr = new Array();
	retArr[0] = setEqual;
	retArr[1] = price;
	retArr[2] = tax;
	return retArr;
}

function calculateOfflineTotalPrice(unique_id) {
	var product_id = 0;
	if (document.getElementById(unique_id)) {
		product_id = document.getElementById(unique_id).value;
		if (product_id == 0 || product_id == "") {
			return false;
		}
	}
	var mainprice = 0;

	// accessory price add
	var acc_excl_price = 0;
	var accfinalprice = collectOfflineAccessory(product_id, unique_id);
	if (document.getElementById("accessory_vatprice" + unique_id)) {
		acc_excl_price = accfinalprice - parseFloat(document.getElementById("accessory_vatprice" + unique_id).value);
	}

	collectOfflineAttributes(product_id, 0, unique_id);
	var prd_excl_price = 0;
	if (document.getElementById('tmp_product_price' + unique_id)) {
		mainprice = parseFloat(document.getElementById('tmp_product_price' + unique_id).value);
	}
	if (document.getElementById('tmp_product_vatprice' + unique_id)) {
		prd_excl_price = mainprice - document.getElementById("tmp_product_vatprice" + unique_id).value;
	}

	// setting wrapper price
	var wexcl_price = 0;
	wprice = setOfflineWrapperComboBox(product_id, unique_id);
	if (document.getElementById("wrapper_vatprice" + unique_id)) {
		wexcl_price = wprice - parseFloat(document.getElementById("wrapper_vatprice" + unique_id).value);
	}

	var excl_price = 0;
	final_price_f = parseFloat(mainprice) + parseFloat(accfinalprice) + parseFloat(wprice);
	excl_price = parseFloat(prd_excl_price) + parseFloat(acc_excl_price) + parseFloat(wexcl_price);

	if (document.getElementById('prdprice' + unique_id)) {
		document.getElementById('prdprice' + unique_id).innerHTML = number_format(final_price_f, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	}
	if (document.getElementById('productprice' + unique_id)) {
		document.getElementById('productprice' + unique_id).value = final_price_f;
	}
	if (document.getElementById('prdexclprice' + unique_id)) {
		document.getElementById('prdexclprice' + unique_id).value = excl_price;
	}
	var quantity = 1;
	if (document.getElementById("quantity" + unique_id) && (trim(document.getElementById("quantity" + unique_id).value) != "" && !isNaN(document.getElementById("quantity" + unique_id).value))) {
		quantity = document.getElementById("quantity" + unique_id).value;
	}
	document.getElementById("quantity" + unique_id).value = quantity;

	if (document.getElementById("prdtax" + unique_id)) {
		document.getElementById("prdtax" + unique_id).innerHTML = number_format(final_price_f - excl_price, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	}
	if (document.getElementById("taxprice" + unique_id)) {
		document.getElementById("taxprice" + unique_id).value = (final_price_f - excl_price) * quantity;
	}
	if (document.getElementById('subprice' + unique_id)) {
		document.getElementById('subprice' + unique_id).value = quantity * final_price_f;
	}
	if (document.getElementById('tdtotalprd' + unique_id)) {
		document.getElementById('tdtotalprd' + unique_id).innerHTML = number_format(quantity * final_price_f, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	}
	calculateOfflineTotal();
}

function setOfflineWrapperComboBox(product_id, unique_id) {
	if (product_id == 0 || product_id == "") {
		return false;
	}
	var wrapper_price = 0;
	var wprice_vat = 0;
	var commonid = product_id + unique_id;

	var wrapperName = document.getElementsByName('wrapper_id_' + commonid + '[]');
	seli = 0;
	var wArr = new Array();
	for (var sp = 0; sp < wrapperName.length; sp++) {
		if (wrapperName[sp].selectedIndex && wrapperName[sp].options[wrapperName[sp].selectedIndex].value) {
			var wid = wrapperName[sp].options[wrapperName[sp].selectedIndex].value;
			if (document.getElementById("wprice_" + commonid + "_" + wid)) {
				wrapper_price = parseFloat(document.getElementById("wprice_" + commonid + "_" + wid).value);
			}
			if (document.getElementById("wprice_tax_" + commonid + "_" + wid)) {
				wprice_vat = parseFloat(document.getElementById("wprice_tax_" + commonid + "_" + wid).value);
			}
			wArr[seli++] = wid;
		}
	}
	if (document.getElementById("wrapper_vatprice" + unique_id)) {
		document.getElementById("wrapper_vatprice" + unique_id).value = wprice_vat;
	}
	if (document.getElementById("wrapper_data" + unique_id)) {
		document.getElementById("wrapper_data" + unique_id).value = wArr.join("##");
	}
	return wrapper_price;
}

function collectOfflineAccessory(product_id, unique_id) {
	if (product_id == 0 || product_id == "") {
		return false;
	}
	var layout = "";
	var prefix = "";
	var acc_subatt_price_final = 0;
	var acc_subatt_vat_final = 0;
	var acc_price_total = 0;
	var acc_vat_total = 0;
	var price = 0;
	var selid = 0;
	var tmpprice = 0;
	var myaccall = new Array();
	var myattall = new Array();
	var mypropall = new Array();
	var mysubpropall = new Array();

	// elements
	if (document.getElementsByName("accessory_id_" + product_id + unique_id + "[]")) {
		dcatt = document.getElementsByName("accessory_id_" + product_id + unique_id + "[]");

		var total_accessory = (dcatt.length);
		for (j = 0; j < total_accessory; j++) {
			var my_acc_fprice = 0;
			var my_acc_vat = 0;
			var accessory_id = dcatt[j].value;
			var commonid = product_id + '_' + accessory_id + unique_id;
			var accchkchecked = 0;
			var attribute_id = 0;
			acc_chk = document.getElementById("accessory_id_" + commonid);
			if (document.getElementById("attribute_id_" + commonid)) {
				attribute_id = document.getElementById("attribute_id_" + commonid);
			}
			accchkchecked = dcatt[j].checked;

			if (accchkchecked) {
				myaccall[selid] = accessory_id;
				collectOfflineAttributes(product_id, accessory_id, unique_id);
				if (document.getElementById("accessory_price" + unique_id)) {
					my_acc_fprice = parseFloat(document.getElementById("accessory_price" + unique_id).value);
				}
				if (document.getElementById("accessory_vatprice" + unique_id)) {
					my_acc_vat = parseFloat(document.getElementById("accessory_vatprice" + unique_id).value);
				}
				if (document.getElementById("acc_attribute_data" + unique_id)) {
					myattall[selid] = document.getElementById("acc_attribute_data" + unique_id).value;
				}
				if (document.getElementById("acc_property_data" + unique_id)) {
					mypropall[selid] = document.getElementById("acc_property_data" + unique_id).value;
				}
				if (document.getElementById("acc_subproperty_data" + unique_id)) {
					mysubpropall[selid] = document.getElementById("acc_subproperty_data" + unique_id).value;
				}
				selid++;
			}
			acc_price_total += parseFloat(my_acc_fprice);
			acc_vat_total += parseFloat(my_acc_vat);
		}
		acc_subatt_price_final += parseFloat(acc_price_total);
		acc_subatt_vat_final += parseFloat(acc_vat_total);
		if (document.getElementById("accessory_data" + unique_id)) {
			document.getElementById("accessory_data" + unique_id).value = myaccall.join("@@");
		}
		if (document.getElementById("acc_attribute_data" + unique_id)) {
			document.getElementById("acc_attribute_data" + unique_id).value = myattall.join("@@");
		}
		if (document.getElementById("acc_property_data" + unique_id)) {
			document.getElementById("acc_property_data" + unique_id).value = mypropall.join("@@");
		}
		if (document.getElementById("acc_subproperty_data" + unique_id)) {
			document.getElementById("acc_subproperty_data" + unique_id).value = mysubpropall.join("@@");
		}
		if (document.getElementById("accessory_price" + unique_id)) {
			document.getElementById("accessory_price" + unique_id).value = acc_subatt_price_final;
		}
		if (document.getElementById("accessory_vatprice" + unique_id)) {
			document.getElementById("accessory_vatprice" + unique_id).value = acc_subatt_vat_final;
		}
	}

	return acc_subatt_price_final;
}

function displayProductDetailInfo(unique_id, newprice) {
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
				var ordertotal = 0;
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
				newxmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
				newxmlhttp.send(null);
			}

			// load calendar setup
			calendarDefaultLoad();
		}
	}
	xmlhttp.open("GET", url, true);
	xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xmlhttp.send(null);
}

/**
 * load calendar when order item response load
 * Fetch extra field elemnts and set calander js dynamically
 */
function calendarDefaultLoad() {

	var cal_img_elm = $$('.userfield_input').getElements('img[id^=rs_]');
	var cal_input_elm = $$('.userfield_input').getElements('input[id^=rs_]');

	var idcollection = [];
	cal_input_elm.each(function(el) {

		if (el.length > 0) idcollection.push(el[0].id);

	});

	var imgcollection = [];
	cal_img_elm.each(function(el) {

		if (el.length > 0) imgcollection.push(el[0].id);

	});

	imgcollection.each(function(el, ind) {

		//document.write(idcollection[ind] + "  " + imgcollection[ind] + " <br />");
		Calendar.setup({
			// Id of the input field
			inputField: idcollection[ind],
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: imgcollection[ind],
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 0
		});

	});

}

function changeOfflinePropertyDropdown(product_id, accessory_id, attribute_id, unique_id) {
	var propArr = new Array();
	var property_data = "";
	var suburl = "";

	if (accessory_id != 0) {
		prefix = unique_id + "acc_";
	} else {
		prefix = unique_id + "prd_";
	}
	var user_id = 0;
	if (document.getElementById("user_id")) {
		user_id = document.getElementById("user_id").value;
	}

	var commonid = prefix + product_id + '_' + accessory_id + '_' + attribute_id;

	suburl = suburl + "&unique_id=" + unique_id;
	suburl = suburl + "&product_id=" + product_id;
	suburl = suburl + "&accessory_id=" + accessory_id;
	suburl = suburl + "&attribute_id=" + attribute_id;
	suburl = suburl + "&user_id=" + user_id;

	if (document.getElementsByName('property_id_' + commonid + '[]')) {
		var propName = document.getElementsByName('property_id_' + commonid + '[]');
		var sel_i = 0;
		for (var p = 0; p < propName.length; p++) {
			if (propName[p].type == 'checkbox') {
				if (propName[p].checked) {
					propArr[sel_i++] = propName[p].value;
				}
			} else {
				if (propName[p].selectedIndex) {
					propArr[sel_i++] = propName[p].options[propName[p].selectedIndex].value;
				}
			}
		}
		property_data = propArr.join(",");
		suburl = suburl + "&property_id=" + property_data;
	}
	var url = "index.php?tmpl=component&option=com_redshop&view=addquotation_detail&task=displayOfflineSubProperty";
	url = url + suburl;
	xmlhttp = GetXmlHttpObject();
	xmlhttp.onreadystatechange = function() {
		// if request object received response
		if (document.getElementById('property_responce' + commonid)) {
			document.getElementById('property_responce' + commonid).style.display = 'none';
		}
		if (xmlhttp.readyState == 4) {
			// controller response
			if (document.getElementById('property_responce' + commonid)) {
				document.getElementById('property_responce' + commonid).innerHTML = xmlhttp.responseText;
				document.getElementById('property_responce' + commonid).style.display = '';
			}
			calculateOfflineTotalPrice(unique_id);
		}
	}
	xmlhttp.open("GET", url, true);
	xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xmlhttp.send(null);
}

function calculateOfflineTotal() {
	var table = document.getElementById('tblproductRow');
	var subtotal = 0;
	var totalTax = 0;

	for (i = 1; i <= rowCount; i++) {
		if (document.getElementById("subpriceproduct" + i)) {
			subtotal = parseFloat(subtotal) + parseFloat(document.getElementById("subpriceproduct" + i).value);
		}
		if (document.getElementById("taxpriceproduct" + i)) {
			totalTax = parseFloat(totalTax) + parseFloat(document.getElementById("taxpriceproduct" + i).value);
		}
	}

	if (document.getElementById("divSubTotal")) {
		document.getElementById("divSubTotal").innerHTML = number_format(subtotal, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	}
	if (document.getElementById("order_subtotal")) {
		document.getElementById("order_subtotal").value = subtotal;
	}
	if (document.getElementById("divTax")) {
		document.getElementById("divTax").innerHTML = number_format(totalTax, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	}
	if (document.getElementById("order_tax")) {
		document.getElementById("order_tax").value = totalTax;
	}
	var order_shipping = 0;
	if (document.getElementById("order_shipping")) {
		order_shipping = parseFloat(document.getElementById("order_shipping").value);
	}
	if (document.getElementById("divShipping")) {
		document.getElementById("divShipping").innerHTML = number_format(order_shipping, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	}
	var displaytotal = parseFloat(subtotal) + parseFloat(order_shipping);
	if (document.getElementById("divFinalTotal")) {
		document.getElementById("divFinalTotal").innerHTML = number_format(displaytotal, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	}
	if (document.getElementById("order_total")) {
		document.getElementById("order_total").value = subtotal;
	}
}

function calculateOfflineShipping() {
	if (document.getElementById("shipping_rate_id")) {
		order_shipping = document.getElementById("shipping_rate_id").value;
		var url = "index.php?tmpl=component&option=com_redshop&view=addorder_detail&task=getShippingRate&shipping_rate_id=" + order_shipping;
		xmlhttp = GetXmlHttpObject();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4) {
				document.getElementById("divCalc").innerHTML = xmlhttp.responseText;
				var order_shipping = 0;
				if (document.getElementById("resultShipping")) {
					order_shipping = document.getElementById("resultShipping").innerHTML;
				}
				if (document.getElementById("resultShippingClass")) {
					resultShippingClass = document.getElementById("resultShippingClass").innerHTML;

					if (resultShippingClass == "plgredshop_shippingdefault_shipping_gls") {
						if (document.getElementById('rs_glslocationId')) {
							document.getElementById('rs_glslocationId').style.display = 'block';
						}
					} else {
						if (document.getElementById('rs_glslocationId')) {
							document.getElementById('rs_glslocationId').style.display = 'none';
						}
					}
				}
				if (document.getElementById("resultShippingVat")) {
					order_shipping_vat = document.getElementById("resultShippingVat").innerHTML;

				}
				if (document.getElementById("order_tax").value) {
					var tax = parseFloat(document.getElementById("order_tax").value) + parseFloat(order_shipping_vat);
					document.getElementById("divTax").innerHTML = number_format(tax, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
				}
				if (document.getElementById("divShipping")) {
					document.getElementById("divShipping").innerHTML = number_format(order_shipping, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
				}
				if (document.getElementById("order_shipping")) {
					document.getElementById("order_shipping").value = order_shipping;
				}
				document.getElementById("divCalc").innerHTML = "";
				var subtotal = 0;
				if (document.getElementById("order_total")) {
					subtotal = document.getElementById("order_total").value;
				}
				var displaytotal = parseFloat(subtotal) + parseFloat(order_shipping);
				if (document.getElementById("divFinalTotal")) {
					document.getElementById("divFinalTotal").innerHTML = number_format(displaytotal, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
				}
			}
		}
		xmlhttp.open("GET", url, true);
		xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		xmlhttp.send(null);
	}
}

function changeOfflinePriceBox(unique_id) {
	var prdexclprice = 0;
	if (document.getElementById("prdexclprice" + unique_id) && (trim(document.getElementById("prdexclprice" + unique_id).value) != "" && !isNaN(document.getElementById("prdexclprice" + unique_id).value))) {
		prdexclprice = document.getElementById("prdexclprice" + unique_id).value;
	}
	document.getElementById("prdexclprice" + unique_id).value = prdexclprice;
	if (document.getElementById("change_product_tmp_price" + unique_id)) {
		document.getElementById("change_product_tmp_price" + unique_id).value = prdexclprice;
	}
	displayProductDetailInfo(unique_id, prdexclprice);
}

function changeOfflineQuantityBox(unique_id) {
	var prdexclprice = 0;
	if (document.getElementById("main_price" + unique_id) && document.getElementById("product_vatprice" + unique_id)) {
		prdexclprice = parseFloat(document.getElementById("main_price" + unique_id).value) - parseFloat(document.getElementById("product_vatprice" + unique_id).value);
	}
	document.getElementById("prdexclprice" + unique_id).value = prdexclprice;
	if (document.getElementById("change_product_tmp_price" + unique_id)) {
		prdexclprice = document.getElementById("change_product_tmp_price" + unique_id).value;
	}
	displayProductDetailInfo(unique_id, prdexclprice);
}

function addItembutton(unique_id) {
	var product_id = 0;
	if (document.getElementById(unique_id)) {
		product_id = document.getElementById(unique_id).value;
	}
	if (product_id != 0) {
		if (document.getElementById('prdexclprice' + unique_id)) {
			document.getElementById('prdexclprice' + unique_id).style.display = "";
		}
		if (document.getElementById('quantity' + unique_id)) {
			document.getElementById('quantity' + unique_id).style.display = "";
		}
		if (document.getElementById('add')) {
			document.getElementById('add').style.display = "";
		}
	} else {
		if (document.getElementById('prdexclprice' + unique_id)) {
			document.getElementById('prdexclprice' + unique_id).style.display = "none";
		}
		if (document.getElementById('quantity' + unique_id)) {
			document.getElementById('quantity' + unique_id).style.display = "none";
		}
		if (document.getElementById('add')) {
			document.getElementById('add').style.display = "none";
		}
	}
}

function getQuotationDetail(unqid) {
	var product_price = 0;
	var product_excl_price = 0;
	var totalprice = 0;
	var tax = 0;
	var quantity = 1;
	var user_id = 0;

	if (document.getElementById("hiddenqnt" + unqid) && document.getElementById("hiddenqnt" + unqid).value != 0) {
		quantity = document.getElementById("hiddenqnt" + unqid).value;
	}
	if (document.getElementById('user_id')) {
		user_id = document.getElementById('user_id').value;
	}

	if (document.getElementById("quantity" + unqid) && (trim(document.getElementById("quantity" + unqid).value) != "" && !isNaN(document.getElementById("quantity" + unqid).value))) {
		quantity = document.getElementById("quantity" + unqid).value;
	}
	document.getElementById("quantity" + unqid).value = quantity;

	if (document.getElementById("product_excl_price" + unqid) && (trim(document.getElementById("product_excl_price" + unqid).value) != "" && !isNaN(document.getElementById("product_excl_price" + unqid).value))) {
		product_excl_price = document.getElementById("product_excl_price" + unqid).value;
	}
	document.getElementById("product_excl_price" + unqid).value = product_excl_price;

	var suburl = "&product_id=1";
	suburl = suburl + "&user_id=" + user_id;
	suburl = suburl + "&newprice=" + product_excl_price;
	var url = "index.php?tmpl=component&option=com_redshop&view=quotation_detail&task=getQuotationPriceTax";
	url = url + suburl;
	xmlhttp = GetXmlHttpObject();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			document.getElementById("divCalc").innerHTML = xmlhttp.responseText;
			tax = parseFloat(document.getElementById("newtax").innerHTML);
			document.getElementById("divCalc").innerHTML = "";

			product_price = parseFloat(product_excl_price) + parseFloat(tax);

			if (document.getElementById("tdprdprice" + unqid)) {
				document.getElementById("tdprdprice" + unqid).innerHTML = number_format(product_price, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
			}
			if (document.getElementById("product_price" + unqid)) {
				document.getElementById("product_price" + unqid).value = product_price;
			}
			if (document.getElementById("taxprice" + unqid)) {
				document.getElementById("taxprice" + unqid).value = parseFloat(quantity) * parseFloat(tax);
			}
			if (document.getElementById("totalprice" + unqid)) {
				totalprice = parseFloat(quantity) * parseFloat(product_price);
			}
			document.getElementById("totalprice" + unqid).value = totalprice;
			document.getElementById("tdtotalprice" + unqid).innerHTML = number_format(totalprice, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));

			calculateQuotationTotal();
		}
	}
	xmlhttp.open("GET", url, true);
	xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xmlhttp.send(null);
}

function calculateQuotationTotal() {

	var table = document.getElementById('tblproductRow');
	var subtotal = 0;
	var tax = 0;
	var total = 0;
	var q_discount = 0;
	var q_p_discount = 0,
		q_p_discount_total = 0;
	var qrowCount = document.querySelectorAll("[name*='totalpricep']").length;

	for (i = 1; i <= qrowCount; i++) {
		if (document.getElementById("totalpricep" + i)) {
			subtotal = parseFloat(subtotal) + parseFloat(document.getElementById("totalpricep" + i).value);
			total = parseFloat(total) + parseFloat(document.getElementById("totalpricep" + i).value);
		}
		if (document.getElementById("taxpricep" + i)) {
			tax = parseFloat(tax) + parseFloat(document.getElementById("taxpricep" + i).value);
		}
	}

	total = total;
	subtot_with_discount = subtotal;

	if (document.getElementById("quotation_discount") && (trim(document.getElementById("quotation_discount").value) != "" && !isNaN(document.getElementById("quotation_discount").value))) {
		q_discount = parseFloat(document.getElementById("quotation_discount").value);

		if (redSHOP.RSConfig._('VAT_RATE_AFTER_DISCOUNT')) {
			vatondiscount = (parseFloat(q_discount) * redSHOP.RSConfig._('VAT_RATE_AFTER_DISCOUNT')) / (1 + parseFloat(redSHOP.RSConfig._('VAT_RATE_AFTER_DISCOUNT')));
		} else {
			vatondiscount = 0;
		}

		displaytax = parseFloat(tax) - parseFloat(vatondiscount);
		displaydiscount = parseFloat(q_discount) - parseFloat(vatondiscount);
		displaytotal = total - parseFloat(q_discount);
		subtot_with_discount = subtot_with_discount - parseFloat(displaydiscount);
	}

	if (document.getElementById("quotation_special_discount") && (trim(document.getElementById("quotation_special_discount").value) != "" && !isNaN(document.getElementById("quotation_special_discount").value))) {
		q_p_discount = parseFloat(document.getElementById("quotation_special_discount").value);
		q_p_discount_total = (total * q_p_discount) / 100;
		if (redSHOP.RSConfig._('VAT_RATE_AFTER_DISCOUNT')) {
			vatonspdiscount = (parseFloat(q_p_discount_total) * redSHOP.RSConfig._('VAT_RATE_AFTER_DISCOUNT')) / (1 + parseFloat(redSHOP.RSConfig._('VAT_RATE_AFTER_DISCOUNT')));
		} else {
			vatonspdiscount = 0;
		}
		displaytax = parseFloat(displaytax) - parseFloat(vatonspdiscount);
		displayspdiscount = parseFloat(q_p_discount_total) - parseFloat(vatonspdiscount);
		total = displaytotal - q_p_discount_total;
		subtot_with_discount = subtot_with_discount - parseFloat(q_p_discount_total) + parseFloat(vatonspdiscount);
	}

	document.getElementById("divMainSubTotalwithDiscount").innerHTML = number_format(subtot_with_discount, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	document.getElementById("divMainSubTotal").innerHTML = number_format(subtotal, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	document.getElementById("quotation_subtotal").value = subtotal;

	document.getElementById("divMainFinalTotal").innerHTML = number_format(total, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	document.getElementById("quotation_total").value = total;

	document.getElementById("divMainTax").innerHTML = number_format(displaytax, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	document.getElementById("quotation_tax").value = tax;
	document.getElementById("divMainSpecialDiscount").innerHTML = number_format(displayspdiscount, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
	document.getElementById("divMainDiscount").innerHTML = number_format(displaydiscount, redSHOP.RSConfig._('PRICE_DECIMAL'), redSHOP.RSConfig._('PRICE_SEPERATOR'), redSHOP.RSConfig._('THOUSAND_SEPERATOR'));
}

function deleteOfflineProductRow(index) {
	if (window.confirm("Are you sure you want to delete?")) {
		var row = document.getElementById('trPrd' + index);
		document.getElementById('tblproductRow').removeChild(row);

		calculateOfflineTotal();
	}
}

function displayAddbutton(product_id, unique_id) {
	var form = document.adminFormAdd;
	if (product_id != 0) {
		if (document.getElementById("prdexclprice" + unique_id)) {
			document.getElementById("prdexclprice" + unique_id).style.display = "";
		}
		if (document.getElementById("quantity" + unique_id)) {
			document.getElementById("quantity" + unique_id).style.display = "";
		}
		form.add.style.display = "";
	} else {
		if (document.getElementById("prdexclprice" + unique_id)) {
			document.getElementById("prdexclprice" + unique_id).style.display = "none";
		}
		if (document.getElementById("quantity" + unique_id)) {
			document.getElementById("quantity" + unique_id).style.display = "none";
		}
		form.add.style.display = "none";
	}
}

function extrafieldValidation(frm) {
	var extrafields = frm.elements;
	var extrafields_val = '';
	var extrafields_lbl = '';
	var previousfieldName = "";
	var fieldNamefrmId = "";
	var chk_flag = false;
	var rdo_previousfieldName = "";
	var rdo_fieldNamefrmId = "";
	var rdo_flag = false;

	var selmulti_fieldNamefrmId = "";

	for (var ex = 0; ex < extrafields.length; ex++) {
		extrafields_req = extrafields[ex].getAttribute('required');
		extrafields_lbl = extrafields[ex].getAttribute('userfieldlbl');
		if (extrafields_req == 1 && extrafields_lbl != null) {
			if (extrafields[ex].type == 'checkbox') {
				fieldNamefrmId = reverseString(extrafields[ex].id);
				fieldNamefrmId = reverseString(fieldNamefrmId.substr(fieldNamefrmId.indexOf("_") + 1));
				if (previousfieldName != "" && previousfieldName != fieldNamefrmId && chk_flag == false) {
					alert(extrafields[ex - 1].getAttribute('userfieldlbl') + ' ' + Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
					return false;
				}
				if (previousfieldName != fieldNamefrmId) {
					extrafieldVal = "";
					previousfieldName = fieldNamefrmId;
				}
				if (extrafields[ex].checked) {
					chk_flag = true;
					continue;
				}
				if ((ex == (extrafields.length - 1) && chk_flag == false) || (extrafields[ex + 1].type != 'checkbox') && chk_flag == false) {
					alert(extrafields[ex].getAttribute('userfieldlbl') + ' ' + Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
					return false;
				}
			} else if (extrafields[ex].type == 'radio') {

				rdo_fieldNamefrmId = reverseString(extrafields[ex].id);
				rdo_fieldNamefrmId = reverseString(rdo_fieldNamefrmId.substr(rdo_fieldNamefrmId.indexOf("_") + 1));

				if (rdo_previousfieldName != "" && rdo_previousfieldName != rdo_fieldNamefrmId && rdo_flag == false) {
					alert(extrafields[ex - 1].getAttribute('userfieldlbl') + ' ' + Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
					return false;
				}

				if (rdo_previousfieldName != rdo_fieldNamefrmId) {
					extrafieldVal = "";
					rdo_previousfieldName = rdo_fieldNamefrmId;
					rdo_flag = false;
					if (extrafields[ex].checked) {
						rdo_flag = true;
						continue;
					}
				} else {
					if (extrafields[ex].checked || rdo_flag == true) {
						rdo_flag = true;
						continue;
					}
					if ((ex == (extrafields.length - 1) && rdo_flag == false) || (extrafields[ex + 1].type != 'radio') && rdo_flag == false) {
						alert(extrafields[ex].getAttribute('userfieldlbl') + ' ' + Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
						return false;
					}
				}
			} else {
				extrafields_val = extrafields[ex].value;
				if (!extrafields_val) {
					alert(extrafields[ex].getAttribute('userfieldlbl') + ' ' + Joomla.JText._('COM_REDSHOP_IS_REQUIRED'));
					return false;
				}
			}
		}
	}
	return true;
}

function reverseString(string) {
	var splitext = string.split("");
	var revertext = splitext.reverse();
	var reversed = revertext.join("");
	return reversed;
}

function createAccount(val) {
	jQuery('#user_valid').html('');
	if (!document.getElementById("tblcreat")) {
		return;
	}
	if (val == 1) {
		document.getElementById("tblcreat").style.display = "";
		document.getElementById("tblcreat").width = "100%";
	} else {
		document.getElementById("tblcreat").style.display = "none";
	}
}

function getStateList() {

	xmlhttp = GetXmlHttpObject();
	if (xmlhttp == null) {
		alert("Your browser does not support XMLHTTP!");
		return;
	}
	var selected = new Array();
	var mySelect = document.adminForm.shipping_rate_country; //document.getElementsByName("shipping_rate_country");
	var shipping_rate_id = document.adminForm.shipping_rate_id.value;
	var p = 0;
	for (var i = 0; i < mySelect.options.length; i++) {
		if (mySelect.options[i].selected == true) {
			selected[p++] = mySelect.options[i].value;
		}
	}

	var url = "index.php?tmpl=component&option=com_redshop&view=shipping_rate_detail&task=GetStateDropdown&shipping_rate_id=" + shipping_rate_id;
	url = url + "&country_codes=" + selected.join(',');

	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			document.getElementById("changestate").innerHTML = xmlhttp.responseText;
		}
	}

	xmlhttp.open("GET", url, true);
	xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xmlhttp.send(null);
}
