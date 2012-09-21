/**
 * @version		$Id:  $Revision
 * @package		mootool
 * @subpackage	lofslidernews
 * @copyright	Copyright (C) JAN 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website     http://landofcoder.com
 * @license		This plugin is dual-licensed under the GNU General Public License and the MIT License 
 */
 Element.Events.extend({
	'wheelup': {
		type: Element.Events.mousewheel.type,
		map: function(event){
			event = new Event(event);
			if (event.wheel >= 0) this.fireEvent('wheelup', event)
		}
	},
	'wheeldown': {
		type: Element.Events.mousewheel.type,
		map: function(event){
			event = new Event(event);
			if (event.wheel <= 0) this.fireEvent('wheeldown', event)
		}
	}
});
 ////
if( typeof(LofSlideshow) == 'undefined' ){
	var LofSlideshow = new Class( {
		initialize:function( eMain, eNavigator, eNavOuter, options ){
			this.setting = Object.extend({
				autoStart			: true,
				descStyle	    	: 'sliding',
				mainItemSelector    : 'div.ice-main-item',
				navSelector  		: 'li' ,
				navigatorEvent		: 'click',
				interval	  	 	:  2000,
				auto			    : false,
				navItemsDisplay		: 3,
				startItem			: 1,
				navItemHeight		: 100,
				navItemWidth 		: 310
			}, options || {} );

			this.currentNo  = 0;
			this.nextNo     = null;
			this.previousNo = null;
			this.fxItems	= [];	
			this.minSize 	= 0;
			this.onClick = false;
			if( $defined(eMain) ){
				this.slides	   = eMain.getElements( this.setting.mainItemSelector );
				this.maxWidth  = eMain.getStyle('width').toInt();

				this.maxHeight = eMain.getStyle('height').toInt();
				this.styleMode = this.__getStyleMode();  
				var fx =  Object.extend({waiting:false, onComplete:function(){ this.onClick=false}.bind(this) }, this.setting.fxObject );
				this.slides.each( function(item, index) {
					item.setStyles( eval('({"'+this.styleMode[0]+'": index * this.maxSize,"'+this.styleMode[1]+'":Math.abs(this.maxSize),"display" : "block"})') );		
					this.fxItems[index] = new Fx.Styles( item,  fx );
				}.bind(this) );
				if( this.styleMode[0] == 'opacity' || this.styleMode[0] =='z-index' ){
					this.slides[0].setStyle(this.styleMode[0],'1');
				}

				eMain.addEvents( { 'mouseenter' : this.stop.bind(this),
							   	   'mouseleave' :function(e){ 
								    if(  this.setting.auto ){
										this.play( this.setting.interval,'next', true );
									} }.bind(this) } );
			}
			if( $defined(eNavigator) && $defined(eNavOuter) ){
				var modes = {
					horizontal : ['margin-left', 'width', 'height', 'navItemWidth', 'navItemHeight'],
					vertical   : ['top', 'height', 'width', 'navItemHeight', 'navItemWidth']
				}
				var mode = ( this.setting.navPos == 'bottom' || this.setting.navPos == 'top' )?'horizontal' : 'vertical';		
				this.navigatorItems = eNavigator.getElements( this.setting.navSelector );
				if( this.setting.navItemsDisplay > this.navigatorItems.length ){
					this.setting.navItemsDisplay = this.navigatorItems.length;	
				}
				this.navigatorSlideSize = this.setting[modes[mode][3]];	 
				eNavOuter.setStyle(modes[mode][1], this.setting.navItemsDisplay * this.navigatorSlideSize);
                eNavOuter.setStyle(modes[mode][2], this.setting[modes[mode][4]] );
								
				this.navigatorFx = new Fx.Style( eNavigator, modes[mode][0],
												{transition:Fx.Transitions.Sine.easeInOut,duration:900} );

				 if(  this.setting.auto ){
					this.registerMousewheelHandler( eNavigator ); // allow to use the srcoll
				 }
				this.navigatorItems.each( function(item,index) {
					item.addEvent( this.setting.navigatorEvent, function(){													 
						this.jumping( index, true );
						this.setNavActive( index, item );	
					}.bind(this) ); 
	
						item.setStyles( { 'height' : this.setting.navItemHeight,
									  	  'width'  : this.setting.navItemWidth} );		
				}.bind(this) );
				this.setNavActive( 0 );
			}
		},
		navivationAnimate:function( currentIndex ) { 
			if (currentIndex <= this.setting.startItem 
				|| currentIndex - this.setting.startItem >= this.setting.navItemsDisplay-1) {
					this.setting.startItem = currentIndex - this.setting.navItemsDisplay+2;
					if (this.setting.startItem < 0) this.setting.startItem = 0;
					if (this.setting.startItem >this.slides.length-this.setting.navItemsDisplay) {
						this.setting.startItem = this.slides.length-this.setting.navItemsDisplay;
					}
			}		
			this.navigatorFx.stop().start( -this.setting.startItem*this.navigatorSlideSize );	
		},
		setNavActive:function( index, item ){
			if( $defined(this.navigatorItems) && $defined( this.navigatorItems[index] ) ){ 
				this.navigatorItems.removeClass('active');
				this.navigatorItems[index].addClass('active');	
				this.navivationAnimate( this.currentNo );	
			}
		},
		__getStyleMode:function(){
			switch( this.setting.direction ){
				case 'opacity': this.maxSize=0; this.minSize=1; return ['opacity','opacity'];
				case 'replace': this.maxSize=0; this.minSize=1; return ['display','display'];
				case 'vrup':    this.maxSize=this.maxHeight;    return ['top','height'];
				case 'vrdown':  this.maxSize=-this.maxHeight;   return ['top','height'];
				case 'hrright': this.maxSize=-this.maxWidth;    return ['left','width'];
				case 'hrleft':
				default: this.maxSize=this.maxWidth; return ['left','width'];
			}
		},
		registerMousewheelHandler:function( element ){ 
			element.addEvents({
				'wheelup': function(e) {
					
					e = new Event(e).stop(); 
						this.previous(true);
				}.bind(this),
			 
				'wheeldown': function(e) {
					e = new Event(e).stop();
				
					this.next(true);
				}.bind(this)
			} );
		},
		registerButtonsControl:function( eventHandler, objects, isHover ){
			if( $defined(objects) && this.slides.length > 1 ){
				for( var action in objects ){ 
					if( $defined(this[action.toString()])  && $defined(objects[action]) ){
						objects[action].addEvent( eventHandler, this[action.toString()].bind(this, true) );
					}
				}
			}
			return this;	
		},
		start:function( isStart, obj ){
			this.setting.auto = isStart;
			// if use the preload image.
			if( obj ) {
				this.preloadImages( this.onComplete(obj) );
			} else {
				if( this.setting.auto && this.slides.length > 1 ){
						this.play( this.setting.interval,'next', true );}	
			}
		},
		onComplete:function( obj ){
			(function(){																
					new Fx.Style( obj ,'opacity',{ transition:Fx.Transitions.Quad.easeInOut,
														 duration:800} ).start(1,0)}).delay(600);
			if( this.setting.auto && this.slides.length > 1 ){
				this.play( this.setting.interval,'next', true );}	
			
		},
		preloadImages:function( _options ){
			var options=Object.extend({
									  onComplete:function(){},
									  onProgress:function(){}
									  },_options||{});
			var loaded=[];
			var counter=0;
			var self = this;
			this.slides.getElements('img').each( function(image, index){
				if( !image.complete ){				  
					image.onload =function(){
						count++;
						if( count >= images.length ){
							self.onComplete();
						}
					}
					image.onerror =function(){ 
						count++;
						if( count >= images.length ){
							self.onComplete();
						}	
					}
				}else {
					count++;
					if( count >= images.length ){
						self.onComplete();
					}	
				}
			} );
				
		},
		onProcessing:function( manual, start, end ){	
			this.onClick = true;
			this.previousNo = this.currentNo + (this.currentNo>0 ? -1 : this.slides.length-1);
			this.nextNo 	= this.currentNo + (this.currentNo < this.slides.length-1 ? 1 : 1- this.slides.length);				
			return this;
		},
		finishFx:function( manual ){
			if( manual ) this.stop();
			if( manual && this.setting.auto ){	
				this.play( this.setting.interval,'next', true );
			}		
			this.setNavActive( this.currentNo );	
		},
		getObjectDirection:function( start, end ){
			return eval("({'"+this.styleMode[0]+"':["+start+", "+end+"]})");	
		},
		fxStart:function( index, obj ){
			this.fxItems[index].stop().start( obj );
			return this;
		},
		jumping:function( no, manual ){
			this.stop();
			if( this.currentNo == no ) return;
			var object = this.onProcessing( null, manual, 0, this.maxSize );
			if( this.currentNo < no  ){
				object.fxStart( no, this.getObjectDirection(this.maxSize , this.minSize) );
				object.fxStart( this.currentNo, this.getObjectDirection(this.minSize,  -this.maxSize) );
			} else {
				object.fxStart( no, this.getObjectDirection(-this.maxSize , this.minSize) );
				object.fxStart( this.currentNo, this.getObjectDirection(this.minSize,  this.maxSize) );	
			}
			object.finishFx( manual );	
			this.currentNo  = no;
		},
		next:function( manual , item){
			if( this.onClick ) return ;
			this.currentNo += (this.currentNo < this.slides.length-1) ? 1 : (1 - this.slides.length);	
			this.onProcessing( item, manual, 0, this.maxSize )
				.fxStart( this.currentNo, this.getObjectDirection(this.maxSize ,this.minSize) )
				.fxStart( this.previousNo, this.getObjectDirection(this.minSize, -this.maxSize) )
				.finishFx( manual );
		},
		previous:function( manual, item ){
			if( this.onClick ) return ;
			this.currentNo += this.currentNo > 0 ? -1 : this.slides.length - 1;
			this.onProcessing( item, manual, -this.maxWidth, this.minSize )
				.fxStart( this.nextNo, this.getObjectDirection(this.minSize, this.maxSize) )
				.fxStart( this.currentNo,  this.getObjectDirection(-this.maxSize, this.minSize) )
				.finishFx( manual	);			
		},
		play:function( delay, direction, wait ){
			this.stop(); 
			if(!wait){ this[direction](false); }
			this.isRun = this[direction].periodical(delay,this,true);
		},stop:function(){; $clear(this.isRun ); }
	} );
}
