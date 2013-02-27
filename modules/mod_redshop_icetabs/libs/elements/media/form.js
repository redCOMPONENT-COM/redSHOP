/**
 * $ModDesc
 * 
 * @version	$Id: helper.php $Revision
 * @package	modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 */ 
// JavaScript Document
window.addEvent('load', function(){

 var controls=['group'];
 
 $$("#module-sliders .panel").each( function(item, i){
		var class_name= i%2==0?"lof-odd":"lof-even";
		item.addClass( class_name );
	} );
	
 if(  $defined($$('#module-sliders ul.adminformlist li')) && $$('#module-sliders ul.adminformlist li').length > 0 ) {  
	var lis = document.getElements('#module-sliders ul.adminformlist li');
	 lis.each( function(li, index){
		var tmp = li.getElement('.lof-group');
		if(tmp)
		{
			if( tmp.getProperty('title') ){
				li.addClass('group-'+tmp.getProperty('title')).addClass('icon-'+tmp.getProperty('title'));
				for( j=index+1; j < lis.length; j++ ){
					
					if( $defined(lis[j].getElement('.lof-end-group')) ) {
						//lis[j].remove();
						break;
					}
					lis[j].addClass('group-'+tmp.getProperty('title')).addClass('lof-group-tr');
				}
				var title = tmp.getProperty('title');
				tmp.enable= true;
				tmp.addEvent("click",function(){
					hide = false;
					if(this.getProperty("class").test (/expand/))
					{
						this.removeClass("expand");
						hide = true;
					}
					else
					{
						this.addClass("expand");
					}
					update({
							value:this.getProperty('title'),
							enable:hide},hide);
					var parent = getParentByTagName(this,"li");
					parent.show();
				})
			}
		}
	 });
	function getParentByTagName (el, tag) {
		if(el){
			var parent = $(el).getParent();
			if(parent){
				while (!parent || parent.tagName.toLowerCase() != tag.toLowerCase()) {
					parent = parent.getParent();
				}
				return parent;
			}
		}
		return null;
	}
	 function update( tmp, hide ){
		 	if( hide ){
				tmp.enable = true;
			}
		 	var title = tmp.value;
			if(  tmp.enable==false  && $defined(tmp.enable) ) {
			//	alert( $E('.admintable' ).getElements("*[class=^"+title+"]") );
				var tmpp = document.getElements('ul.adminformlist li.group-'+title +' .lof-group');
				if(tmpp)
				{
					tmpp.addClass("expand");
				}
				document.getElements('ul.adminformlist li.group-'+title ).setStyle('display','');
				tmp.enable=true;

			} else if(title && title !=-1) {
				document.getElements('ul.adminformlist li.group-'+title ).setStyle('display','none');
				tmp.enable=false;
			}
			setTimeout( function(){
				document.getElement('.pane-sliders').setStyle( 'height', document.getElement('.panelform').offsetHeight );
			}, 100 );
	 }
	 

	controls.each( function(_group){
		document.getElements('#jform_params_'+_group).addEvent('change',function(){
			var tmdp = this;
			tmdp.enable = false;
			update( this  );
			var selected = this;
			document.getElements('#jform_params_'+_group +' option').each( function(tmp, index){
					if(tmp.value !=selected.value ) {
						update( tmp, true );
					}
			} );
		});
		 document.getElements('#jform_params_'+_group+' option').each( function(tmp, index){
				if(!tmp.selected) {
					update( tmp );
				}
				else
				{
					var tmpp = document.getElements('ul.adminformlist li.group-'+tmp.value +' .lof-group');
					if(tmpp)
					{
						tmpp.addClass("expand");
					}
				}

		} );
		
	} );
} else {
	var controls=['group','enable_caption'];
	controls.each( function(_group){ 
		$$('#jform_params_'+_group).addEvent('change',function(){
			 $$('.lof-group').hide();	
			 $$('.lof-'+this.value).show();
			 (function(){
				 var height = ($$('#module-sliders .jpane-slider')[0].getElement('.panelform-legacy').getHeight() );
				 $$('#module-sliders .jpane-slider')[0].setStyle('height', height ) ;
			 }).delay(300);
		});
		 $$('#jform_params_'+_group+' option').each(function(item){
			if( item.selected ){
			 $$('.lof-group').hide();	
				(function(){  $$('.lof-'+item.value).show(); }).delay(100);
				 (function(){
				 var height = ($$('#module-sliders .jpane-slider')[0].getElement('.panelform-legacy').getHeight() );
				 $$('#module-sliders .jpane-slider')[0].setStyle('height', height ) ;
				 }).delay(300);
				return ;
			}
		});
	} );
}
} );