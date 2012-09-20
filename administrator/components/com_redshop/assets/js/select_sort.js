function moveRight(maxNum) {
    var selSource = document.adminForm.product_all;
    var selTo = document.adminForm.container_product;
	
	if (selTo.options.length < maxNum) {
	    for (var i = 0; i < selSource.options.length; i++) {
	        if (selSource.options[i].selected && selTo.options.length < maxNum) {
	      	    var newOption = new Option(selSource.options[i].text, selSource.options[i].value);
				var lastVal = selSource.options[i].value;
	            selTo.options[selTo.options.length] = newOption; 
				if (lastVal != 0) {
					selSource.options[i] = null;
				}
				
			} 
		}
	} else {
		alert ("You have a maximum of "+maxNum+" sources selected.");
	}
}

function moveLeft() {
    var selSource = document.adminForm.container_product;
    var selTo = document.adminForm.product_all;
    for (var i = 0; i < selSource.options.length; i++) {
        if (selSource.options[i].selected) {
//			alert(selSource.options[i].text);
      	    var newOption = new Option(selSource.options[i].text, selSource.options[i].value);
			var lastVal = selSource.options[i].value;
			if (lastVal != 0) {
            	selTo.options[selTo.options.length] = newOption;
			}
			 
		} 
	}
	listsort('product_all',0, false, true);
    deleteOptions(selSource)
}

function deleteOptions(selDelete) {
    for (var i = 0; i < selDelete.options.length; i++) {  	
        if (selDelete.options[i].selected) {        		
	    	selDelete.options[i] = null;
	    	i=-1 
		} 
	} 
}


/*Portions of script by Babvailiica www.babailiica.com*/
function selectAll(theSelect) {
		for ( var i=0,n=theSelect.options.length; i<n; i++)
		{
		//alert(theSelect.options[i].text);	
		theSelect.options[i].selected = true;
		}
	  var theSelect = document.adminForm.product_all;
		for ( var i=0,n=theSelect.options.length; i<n; i++)
		{
		//alert(theSelect.options[i].text);	
		theSelect.options[i].selected = true;
		}
		return true;
	
}

function selectnone(obj) { /* NEW added from version 1.1 */
	obj = (typeof obj == "string") ? document.getElementById(obj) : obj;
	if (obj.tagName.toLowerCase() != "select")
		return;
	for (i=0; i<obj.length; i++) {
		obj[i].selected = false;
	}
}

function mousewheel(obj) {
	obj = (typeof obj == "string") ? document.getElementById(obj) : obj;
	if (obj.tagName.toLowerCase() != "select")
		return;
	if (obj.selectedIndex != -1) {
		if (event.wheelDelta > 0) {
			up(obj);
		} else {
			down(obj);
		}
		return false;
	}
}

function sort2d(arrayName, element, num, cs) {
	if (num) {
		for (var i=0; i<(arrayName.length-1); i++) {
			for (var j=i+1; j<arrayName.length; j++) {
				if (parseInt(arrayName[j][element],10) < parseInt(arrayName[i][element],10)) {
					var dummy = arrayName[i];
					arrayName[i] = arrayName[j];
					arrayName[j] = dummy;
				}
			}
		}
	} else {
		for (var i=0; i<(arrayName.length-1); i++) {
			for (var j=i+1; j<arrayName.length; j++) {
				if (cs) {
					if (arrayName[j][element].toLowerCase() < arrayName[i][element].toLowerCase()) {
						var dummy = arrayName[i];
						arrayName[i] = arrayName[j];
						arrayName[j] = dummy;
					}
				} else {
					if (arrayName[j][element] < arrayName[i][element]) {
						var dummy = arrayName[i];
						arrayName[i] = arrayName[j];
						arrayName[j] = dummy;
					}
				}
			}
		}
	}
}

function listsort(obj, by, num, cs) {
	obj = (typeof obj == "string") ? document.getElementById(obj) : obj;
	by = (parseInt("0" + by) > 5) ? 0 : parseInt("0" + by);
	if (obj.tagName.toLowerCase() != "select" && obj.length < 2)
		return false;
	var elements = new Array();
	for (i=0; i<obj.length; i++) {
		elements[elements.length] = new Array(obj[i].text, obj[i].value, (obj[i].currentStyle ? obj[i].currentStyle.color : obj[i].style.color), (obj[i].currentStyle ? obj[i].currentStyle.backgroundColor : obj[i].style.backgroundColor), obj[i].className, obj[i].id, obj[i].selected);
	}
	sort2d(elements, by, num, cs);
	for (i=0; i<obj.length; i++) {
		obj[i].text = elements[i][0];
		obj[i].value = elements[i][1];
		obj[i].style.color = elements[i][2];
		obj[i].style.backgroundColor = elements[i][3];
		obj[i].className = elements[i][4];
		obj[i].id = elements[i][5];
		obj[i].selected = elements[i][6];
	}
}