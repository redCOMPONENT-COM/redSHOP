/**
 * IceTabs Module for Joomla 1.6 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2011 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/icetabs.html
 * @Support 	http://www.icetheme.com/Forums/IceTabs/
 *
 */
 
if(typeof(IceSlideShow) == 'undefined'){
	var IceSlideShow = new Class({
		initialize:function(eMain, eNavigator,eNavOuter, options){
			this.setting = $extend({
				autoStart			: true,
				descStyle	    	: 'sliding',
				mainItemSelector    : 'div.lof-main-item',
				navSelector  		: 'li' ,
				navigatorEvent		: 'click',
				interval	  	 	:  2000,
				auto			    :  false,
				navItemsDisplay:3,
				startItem:0,
				navItemHeight:100,
				navItemWidth:310
			}, options || {});
			
			this.currentNo  = 0;
			this.nextNo     = null;
			this.previousNo = null;
			this.fxItems	= [];	
			this.minSize 	= 0;
			this.onClick = false;
			if($defined(eMain)){
				this.slides	   = eMain.getElements(this.setting.mainItemSelector);
				this.maxWidth  = eMain.getStyle('width').toInt();
				this.maxHeight = eMain.getStyle('height').toInt();
				this.styleMode = this.__getStyleMode();  
				var fx =  $extend({waiting:false, onComplete:function(){ this.onClick=false}.bind(this)}, this.setting.fxObject);
				this.slides.each(function(item, index) {
					item.setStyles(eval('({"'+this.styleMode[0]+'": index * this.maxSize,"'+this.styleMode[1]+'":Math.abs(this.maxSize),"display" : "block"})'));
					this.fxItems[index] = new Fx.Morph(item,  fx);
				}.bind(this));
				if(this.styleMode[0] == 'opacity' || this.styleMode[0] =='z-index'){
					this.slides[0].setStyle(this.styleMode[0],'1');
				}
				eMain.addEvents({ 'mouseenter' : this.stop.bind(this),
							   	   'mouseleave' :function(e){ 
								   if(this.setting.auto ) {
									this.play(this.setting.interval,'next', true); } }.bind(this) });
			}
			// if has the navigator
			if($defined(eNavigator)){
				this.navigatorItems = eNavigator.getElements(this.setting.navSelector);
				if(this.setting.navItemsDisplay > this.navigatorItems.length){
					this.setting.navItemsDisplay = this.navigatorItems.length;	
				}
				
				if(this.setting.navPos == 'left' || this.setting.navPos == 'right')
					eNavOuter.setStyles({'height':this.setting.navItemsDisplay*this.setting.navItemHeight, 'width':this.setting.navItemWidth});
				else
					eNavOuter.setStyles({'height':this.setting.navItemHeight, 'width':this.setting.navItemsDisplay*this.setting.navItemWidth});
				this.navigatorFx = new Fx.Morph(eNavigator,
												{transition:Fx.Transitions.Quad.easeInOut,duration:800});
				if(this.setting.auto ) {
					this.registerMousewheelHandler(eNavigator); // allow to use the srcoll
				}
				this.navigatorItems.each(function(item,index) {
					item.addEvent(this.setting.navigatorEvent, function(){													 
						this.jumping(index, true);
						this.setNavActive(index, item);	
					}.bind(this));
						item.setStyles({ 'height':this.setting.navItemHeight,
									  	  'width'  : this.setting.navItemWidth});
				}.bind(this));
				this.setNavActive(0);
			}
		},
		navivationAnimate:function(currentIndex) { 
			if (currentIndex <= this.setting.startItem 
				|| currentIndex - this.setting.startItem >= this.setting.navItemsDisplay-1) {
					this.setting.startItem = currentIndex - this.setting.navItemsDisplay+2;
					if (this.setting.startItem < 0) this.setting.startItem = 0;
					if (this.setting.startItem >this.slides.length-this.setting.navItemsDisplay) {
						this.setting.startItem = this.slides.length-this.setting.navItemsDisplay;
					}
			}
			//alert(this.setting.navPos);
			if(this.setting.navPos == 'left' || this.setting.navPos == 'right')
				this.navigatorFx.cancel().start({ 'top':-this.setting.startItem*this.setting.navItemHeight});	
			else
				this.navigatorFx.cancel().start({ 'left':-this.setting.startItem*this.setting.navItemWidth});	
		},
		setNavActive:function(index, item){
			if($defined(this.navigatorItems)){ 
				this.navigatorItems.removeClass('active');
				this.navigatorItems[index].addClass('active');	
				this.navivationAnimate(this.currentNo);	
			}
		},
		__getStyleMode:function(){
			switch(this.setting.direction){
				case 'opacity': this.maxSize=0; this.minSize=1; return ['opacity','opacity'];
				case 'vrup':    this.maxSize=this.maxHeight;    return ['top','height'];
				case 'vrdown':  this.maxSize=-this.maxHeight;   return ['top','height'];
				case 'hrright': this.maxSize=-this.maxWidth;    return ['left','width'];
				case 'hrleft':
				default: this.maxSize=this.maxWidth; return ['left','width'];
			}
		},
		registerMousewheelHandler:function(element){ 
			element.addEvent('mousewheel', function(e){
				e.stop();
				if(e.wheel > 0 ){
					this.previous(true);	
				} else {
					this.next(true);	
				}
			}.bind(this));
		},
		registerButtonsControl:function(eventHandler, objects, isHover){
			if($defined(objects) && this.slides.length > 1){
				for(var action in objects){ 
					if($defined(this[action.toString()])  && $defined(objects[action])){
						objects[action].addEvent(eventHandler, this[action.toString()].bind(this, [true]));
					}
				}
			}
			return this;	
		},
		start:function(isStart, obj){
			this.setting.auto = isStart;
			// if use the preload image.
			if(obj) {
				var images = [] 
				this.slides.getElements('img').each(function(item, index){
					images[index] = item.getProperty('src');
				});
				var loader = new Asset.images(images, { onComplete:function(){	
					(function(){ obj.fade('out')  ;}).delay(400);		
					
					if(isStart && this.slides.length > 0){this.play(this.setting.interval,'next', true);}	
				}.bind(this) }); 
			} else {
				if(isStart && this.slides.length > 0){this.play(this.setting.interval,'next', true);}	
			}
		},
		onProcessing:function(manual, start, end){
			this.onClick = true;
			this.previousNo = this.currentNo + (this.currentNo>0 ? -1 : this.slides.length-1);
			this.nextNo 	= this.currentNo + (this.currentNo < this.slides.length-1 ? 1 : 1- this.slides.length);				
			return this;
		},
		finishFx:function(manual){
			if(manual) this.stop();
			if(manual && this.setting.auto){ 
				this.play(this.setting.interval,'next', true);
			}
			this.setNavActive( this.currentNo );	
		},
		getObjectDirection:function(start, end){			
			return eval("({'"+this.styleMode[0]+"':["+start+", "+end+"]})");	
		},
		fxStart:function(index, obj){
			
			this.fxItems[index].cancel().start(obj);
			return this;
		},
		jumping:function(no, manual){
			this.stop();
			if(this.currentNo == no) return;
			
			if((no == this.currentNo - 1) && this.currentNo > 0)
			{
				this.onProcessing(null, manual, -this.maxWidth, this.minSize)
					.fxStart(this.currentNo, this.getObjectDirection(this.minSize, this.maxSize))
					.fxStart(no, this.getObjectDirection(-this.maxSize, this.minSize))
					.finishFx(manual);	
			}
			else				
			{
				this.onProcessing(null, manual, 0, this.maxSize)
					.fxStart(no, this.getObjectDirection(this.maxSize , this.minSize))
					.fxStart(this.currentNo, this.getObjectDirection(this.minSize,  -this.maxSize))
					.finishFx(manual);	
			}
			this.currentNo  = no;
		},
		next:function(manual , item){
			if( this.onClick ) return ;
			this.currentNo += (this.currentNo < this.slides.length-1) ? 1 : (1 - this.slides.length);	
			this.onProcessing(item, manual, 0, this.maxSize)
				.fxStart(this.currentNo, this.getObjectDirection(this.maxSize ,this.minSize))
				.fxStart(this.previousNo, this.getObjectDirection(this.minSize, -this.maxSize))
				.finishFx(manual);
		},
		previous:function(manual, item){
			if( this.onClick ) return ;
			this.currentNo += this.currentNo > 0 ? -1 : this.slides.length - 1;
			this.onProcessing(item, manual, -this.maxWidth, this.minSize)
				.fxStart(this.nextNo, this.getObjectDirection(this.minSize, this.maxSize))
				.fxStart(this.currentNo,  this.getObjectDirection(-this.maxSize, this.minSize))
				.finishFx(manual	);			
		},
		play:function(delay, direction, wait){
			this.stop(); 
			if(!wait){ this[direction](false); }
			this.isRun = this[direction].periodical(delay,this,true);
		},stop:function(){; $clear(this.isRun); }
	});
}