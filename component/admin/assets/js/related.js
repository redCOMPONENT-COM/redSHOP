 
function moveRight_related(maxNum) {
    var selSource = document.adminForm.product_all_related;
    var selTo = document.adminForm.related_product;
	
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

function moveLeft_related() {
    var selSource = document.adminForm.related_product;
    var selTo = document.adminForm.product_all_related;
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
	listsort('product_all_related',0, false, true);
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
function selectAll_related(theSelect,fr) {
		for ( var i=0,n=theSelect.options.length; i<n; i++)
		{
		//alert(theSelect.options[i].text);	
		theSelect.options[i].selected = true;
		}
	  var theSelect = document.adminForm.product_all_related;
		for ( var i=0,n=theSelect.options.length; i<n; i++)
		{
		//alert(theSelect.options[i].text);	
		theSelect.options[i].selected = true;
		}
		
		// selectAll(fr.elements['container_product[]']); 
		return true;
	
}

function selectnone_related(obj) { /* NEW added from version 1.1 */
	obj = (typeof obj == "string") ? document.getElementById(obj) : obj;
	if (obj.tagName.toLowerCase() != "select")
		return;
	for (i=0; i<obj.length; i++) {
		obj[i].selected = false;
	}
}

function mousewheel_related(obj) {
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

function hasOptions(obj){
	if(obj!=null && obj.options!=null){
				return true;
		}
				return false;
}

function swapOptions(obj,i,j){
	var o = obj.options;
	var i_selected = o[i].selected;
	var j_selected = o[j].selected;
	var temp = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);
	var temp2= new Option(o[j].text, o[j].value, o[j].defaultSelected, o[j].selected);
	o[i] = temp2;o[j] = temp;o[i].selected = j_selected;
	o[j].selected = i_selected;
}

function moveOptionUp(obj){
	
	
	if(!hasOptions(obj))
	{  
		return;
	}
	
	for(i=0;i<obj.options.length;i++)
	{
		if(obj.options[i].selected){
			if(i != 0 && !obj.options[i-1].selected){
				swapOptions(obj,i,i-1);
				obj.options[i-1].selected = true;
				}
			}
	}
}


function moveOptionDown(obj){
	if(!hasOptions(obj))
	{
		return;
	}
	for(i=obj.options.length-1;i>=0;i--)
	{
		if(obj.options[i].selected){
			if(i !=(obj.options.length-1) && ! obj.options[i+1].selected){
				swapOptions(obj,i,i+1);
				obj.options[i+1].selected = true;
				}
			}
	}
}
