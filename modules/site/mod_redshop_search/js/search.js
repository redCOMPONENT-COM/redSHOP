/**
 *
 * @returns {boolean|object}
 */
function getHTTPObject() {
	var xhr = false;
	if (window.XMLHttpRequest) {
		/** global: XMLHttpRequest */
		xhr = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		try {
			/** global: ActiveXObject */
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

/**
 * @TODO Use jQuery.ajax instead
 * @param tid
 * @param mid
 */
function loadProducts(tid, mid) {
	/** global: base_url */
	if (typeof base_url === 'undefined')
	{
		base_url = '';
	}

	request = getHTTPObject();
	request.onreadystatechange = sendProductData;
	request.open("GET", base_url + "index.php?tmpl=component&option=com_redshop&view=search&task=loadProducts&taskid=" + tid + "&manufacture_id=" + mid, true);
	request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	request.send(null);
}

/**
 * function is executed when var request state changes
 */
function sendProductData() {
	// if request object received response
	if (request.readyState == 4) {
		var reponce = request.responseText;
		var resdiv = document.getElementById('product_search_catdata_product');
		if (reponce != "" && document.getElementById('product_search_catdata_product')) {
			resdiv.style.display = 'block';
			resdiv.innerHTML = reponce;
		} else {
			resdiv.style.display = 'none';
		}
	}
}
