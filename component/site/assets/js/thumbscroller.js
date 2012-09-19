var ImageScroller = function(_imageScrollerParentDivID, _imageScrollerChildDivID) {
   _imageScrollerParentDivID = getElem(_imageScrollerParentDivID); 
   _imageScrollerChildDivID = getElem(_imageScrollerChildDivID); 
   var prImagePaths = new Array(); 
   var prImageAltText = new Array(); 
   var prImageClicks = new Array(); 
   var prImageDescription = new Array();
   var prImageId = new Array();
   var prImageBorderStyle = new Array();
   var iNumOfThumbsShown = 1; 
   var iNumOfImages; 
   
   var bAutoScroll = 0; 
   //0=false, 1=true
   var iAutoScrollDelay = 2000; 
   var bAutoScrollDirection = 1; 
   //0=back, 1=forward
   var bAutoReverse = 1; 
   //0=false, 1=true
   var iScrollType = 1; 
   //0=horizontal, 1=vertical
   var bEnableThumbBorder = 0; 
   //0=no, 1=yes
   var bEnableCurrentCount = 0; 
   //0=no, 1=yes
   var bEnableThumbDescription = 0; 
   //0=no, 1=yes
   var bClickOpenType = 0; 
   //0=same window, 1=new window
   var iImageScrollAmount = 1;
   //number of images to scroll
   
   var objCounterDiv = ""; 
   var objDescriptionDiv = ""; 
   var iSmoothSlideInterval = 1;//9;//3; 
   var iSmoothSlideAmount = 1;//70;//7; 
   var moveTimer; 
   
   this.THUMB_HEIGHT = 80; 
   this.THUMB_WIDTH = 80; 
   this.THUMB_PADDING = 4; 
   
   var CURRENT_THUMB_INDEX = 1; 
   var NEW_REVERSE_OFFSET = 0; 
   var MAX_REVERSE_OFFSET = 0; 
   var NEW_FORWARD_OFFSET = 0; 
   var IS_SCROLLING = false; 
   //* BEGIN FUNCTIONS *//
   this.setNumOfImageToScroll = function(_NumOfImagesToScroll) {
        iImageScrollAmount = parseInt(_NumOfImagesToScroll);
   };
   this.enableThumbnailDescription = function(_descriptionDivID) {
      bEnableThumbDescription = 1; 
      objDescriptionDiv = _descriptionDivID; 
      }; 
   this.setScrollType = function(_iType) {
      if (_iType == 0) {
         iScrollType = 0; 
         }
      else {
         iScrollType = 1; 
         }
      }; 
   this.setScrollSpeed = function(_iSpeed) {
      if (_iSpeed > 0 || _iSpeed < 1000) {
         iSmoothSlideInterval = _iSpeed + 500; 
         }
      else {
         iSmoothSlideInterval = 2; 
         }
      }; 
   this.setScrollAmount = function(_iAmount) {
      if (_iAmount > 0 || _iAmount < 1000) {
         iSmoothSlideAmount = _iAmount; 
         }
      else {
         iSmoothSlideAmount = 7; 
         }
      }; 
   this.setClickOpenType = function(_openType) {
      if (_openType == 0 || _openType == 1) {
         bClickOpenType = _openType; 
         }
      }; 
   this.enableCurrentCount = function(_counterDivID) {
      bEnableCurrentCount = 1; 
      objCounterDiv = _counterDivID; 
      }; 
   this.enableThumbBorder = function(_boolean) {
      bEnableThumbBorder = _boolean; 
      }; 
   this.setThumbsShown = function(_newNumOfThumbsShown) {
      iNumOfThumbsShown = parseInt(_newNumOfThumbsShown); 
      }; 
   this.addThumbnail = function(_thumbnailURL, _fullClickURL, _thumbnailAlt, _thumbnailDescription, _aImageId, _aImageBorderStyle) {
      prImagePaths[prImagePaths.length] = _thumbnailURL; 
      prImageClicks[prImageClicks.length] = _fullClickURL; 
      prImageAltText[prImageAltText.length] = _thumbnailAlt; 
      prImageDescription[prImageDescription.length] = _thumbnailDescription; 
      prImageId[prImageId.length] = _aImageId;
      prImageBorderStyle[prImageBorderStyle.length] = _aImageBorderStyle;
      }; 
   this.setThumbnailHeight = function(_newThumbHeight) {
      this.THUMB_HEIGHT = _newThumbHeight; 
      }; 
   this.getThumbnailHeight = function() {
      return this.THUMB_HEIGHT; 
      }; 
   this.setThumbnailWidth = function(_newThumbWidth) {
      this.THUMB_WIDTH = _newThumbWidth; 
      }; 
   this.getThumbnailWidth = function() {
      return this.THUMB_WIDTH; 
      }; 
   this.setThumbnailPadding = function(_newThumbPadding) {
      this.THUMB_PADDING = _newThumbPadding; 
      }; 
   this.getThumbnailPadding = function() {
      return THUMB_PADDING; 
      }; 
   this.getCurrentThumbIndex = function() {
      return CURRENT_THUMB_INDEX; 
      }; 
   this.getThumbnailCount = function() {
      return iNumOfImages; 
      }; 
   this.renderScroller = function() {
      iNumOfImages = prImagePaths.length; 
      if (iNumOfThumbsShown > iNumOfImages) {
         iNumOfThumbsShown = iNumOfImages; 
         }
      MAX_REVERSE_OFFSET = 0 - (iNumOfImages - iNumOfThumbsShown) * this.THUMB_WIDTH; 
      if (this.THUMB_PADDING > 0) {
//    	  MAX_REVERSE_OFFSET = MAX_REVERSE_OFFSET - (iNumOfImages * this.THUMB_PADDING);
     	 // Changed from original calculation (bcoz Not display last image)  
          MAX_REVERSE_OFFSET = MAX_REVERSE_OFFSET - ((iNumOfImages + iNumOfThumbsShown) * this.THUMB_PADDING);
         }
      if (bEnableThumbBorder == 1) {
         MAX_REVERSE_OFFSET = MAX_REVERSE_OFFSET - (iNumOfImages * 4); 
         }
      if (iScrollType == 0) {
         _imageScrollerParentDivID.style.width = (this.THUMB_WIDTH * iNumOfThumbsShown) + (iNumOfThumbsShown * ((this.THUMB_PADDING ) * 2)) + "px"; 
         if (bEnableThumbBorder == 1) {
            _imageScrollerParentDivID.style.width = (parseInt(_imageScrollerParentDivID.style.width) + (iNumOfThumbsShown * 4)) + "px"; 
            }
         _imageScrollerParentDivID.style.height = this.THUMB_HEIGHT + (this.THUMB_PADDING * 2)+2 + "px"; 
         if (bEnableThumbBorder == 1) {
            _imageScrollerParentDivID.style.height = (parseInt(_imageScrollerParentDivID.style.height) + 4) + "px"; 
            }
         
         _imageScrollerChildDivID.style.width = (this.THUMB_WIDTH * iNumOfImages) + (iNumOfImages * (this.THUMB_PADDING * 2)) + "px";
         if(/MSIE[\/\s](\d+\.\d+)/.test(navigator.userAgent))
        	 _imageScrollerChildDivID.style.width = (this.THUMB_WIDTH * iNumOfImages)+this.THUMB_WIDTH + (iNumOfImages * (this.THUMB_PADDING * 2)) + "px";
         if (bEnableThumbBorder == 1) {
            _imageScrollerChildDivID.style.width = (parseInt(_imageScrollerChildDivID.style.width) + (iNumOfImages * 4)) + "px"; 
            }
         }
      else if (iScrollType == 1) {
         _imageScrollerParentDivID.style.width = (this.THUMB_WIDTH) + ((this.THUMB_PADDING * 2)) + "px"; 
         if (bEnableThumbBorder == 1) {
            _imageScrollerParentDivID.style.width = (parseInt(_imageScrollerParentDivID.style.width) + (4)) + "px"; 
            }
         _imageScrollerParentDivID.style.height = (this.THUMB_HEIGHT * iNumOfThumbsShown) + (iNumOfThumbsShown * (this.THUMB_PADDING * 2)) + "px"; 
         if (bEnableThumbBorder == 1) {
            _imageScrollerParentDivID.style.height = (parseInt(_imageScrollerParentDivID.style.height) + (iNumOfThumbsShown * 4)) + "px"; 
            }
         _imageScrollerChildDivID.style.width = (this.THUMB_WIDTH) + (this.THUMB_PADDING * 2) + "px"; 
         if (bEnableThumbBorder == 1) {
            _imageScrollerChildDivID.style.width = (parseInt(_imageScrollerChildDivID.style.width) + 4) + "px"; 
            }
         }
      //*** [Begin] Image Cacheing code ***//
      var oHref;
      var oImage;
        oHref = document.createElement("a");
        oImage = document.createElement("img");
        
      for (i = 0; i < iNumOfImages; i++) {
        oHref = document.createElement("a");
        oHref.href = prImageClicks[i];
        oHref.title = prImageAltText[i];
        if(/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent))
        {
        	oHref.style.margin = '4.5px';
        }
   		if(/MSIE[\/\s](\d+\.\d+)/.test(navigator.userAgent))
   		{
   			oHref.style.margin = '0px';
   		}
//        if (bClickOpenType == 1) {
//            oHref.target = "_blank";
//        }
        var browserAdjust = 2;
		if (document.all) { browserAdjust = 4; };
        oImage = document.createElement("img");
            oImage.src = prImagePaths[i];
            oImage.alt = prImageAltText[i];
            oImage.border = 1;
            oImage.id = prImageId[i];
            oImage.width = this.THUMB_WIDTH;
            oImage.height = this.THUMB_HEIGHT;
            oImage.style.border = prImageBorderStyle[i];
            oImage.style.padding = this.THUMB_PADDING;
            
          oHref.appendChild(oImage);
          _imageScrollerChildDivID.appendChild(oHref);
            
      }
      //*** [End]   Image Cacheing code ***//
      
      if (bEnableCurrentCount == 1) {
         addAnEvent(window, "load", this.updateCurrentCount); 
         }
      if (bEnableThumbDescription == 1) {
         addAnEvent(window, "load", this.updateCurrentDescription); 
         }
      };
      this.stopSmoothScroll = function() {
          IS_SCROLLING = false; 
    	window.clearTimeout(moveTimer); 
      };
      this.smoothScrollForward = function() {
          _origOffset = parseInt(_imageScrollerChildDivID.style.left); 
          _currentOffset = parseInt(_imageScrollerChildDivID.style.left); 
          _newOffset = _currentOffset - iSmoothSlideAmount; 
          if (this.THUMB_PADDING > 0) {
             _newOffset = _newOffset - ((2 * iImageScrollAmount) * this.THUMB_PADDING); 
             }
          if (bEnableThumbBorder == 1) {
             _newOffset = _newOffset - 4; 
             }
          if (IS_SCROLLING == false && _newOffset >= MAX_REVERSE_OFFSET) {
             NEW_FORWARD_OFFSET = _newOffset; 
             smoothMoveScrollerLeft(); 
             }
      }; 
      this.stopSmoothScroll = function() {
          IS_SCROLLING = false; 
    		window.clearTimeout(moveTimer); 
      };
      
      function smoothMoveScrollerRight() {
          _ElementObj = _imageScrollerChildDivID; 
          _currentOffset = parseInt(_ElementObj.style.left); 
          if (_currentOffset < 0 && (_currentOffset + iSmoothSlideAmount) <= 0) {
             _ElementObj.style.left = _currentOffset + iSmoothSlideAmount + "px"; 
             IS_SCROLLING = true; 
             moveTimer = window.setTimeout(smoothMoveScrollerRight, iSmoothSlideInterval); 
             }
          else if (_currentOffset < 0) {
             _ElementObj.style.left = _currentOffset + 1 + "px"; 
             IS_SCROLLING = true; 
             moveTimer = window.setTimeout(smoothMoveScrollerRight, iSmoothSlideInterval); 
             }
          else {
             IS_SCROLLING = false; 
             window.clearTimeout(moveTimer); 
             }
          };
   this.scrollUp = function() {
      _origOffset = parseInt(_imageScrollerChildDivID.style.top); 
      _currentOffset = parseInt(_imageScrollerChildDivID.style.top); 
      _newOffset = _currentOffset - (this.THUMB_HEIGHT * iImageScrollAmount); 
      if (this.THUMB_PADDING > 0) {
         _newOffset = _newOffset - (2 * this.THUMB_PADDING); 
         }
      if (bEnableThumbBorder == 1) {
         _newOffset = _newOffset - 4; 
         }
      if (IS_SCROLLING == false && _newOffset >= MAX_REVERSE_OFFSET) {
         NEW_FORWARD_OFFSET = _newOffset; 
         moveScrollerUp(); 
         }
      }; 
   this.scrollDown = function() {
      _origOffset = parseInt(_imageScrollerChildDivID.style.top); 
      _currentOffset = parseInt(_imageScrollerChildDivID.style.top); 
      _newOffset = _currentOffset + (this.THUMB_HEIGHT * iImageScrollAmount); 
      if (this.THUMB_PADDING > 0) {
         _newOffset = _newOffset + (2 * this.THUMB_PADDING); 
         }
      if (bEnableThumbBorder == 1) {
         _newOffset = _newOffset + 4; 
         }
      if (_newOffset <= 0) {
         if(_currentOffset > (_origOffset - this.THUMB_HEIGHT)) {
            if (IS_SCROLLING == false && _newOffset >= MAX_REVERSE_OFFSET) {
               NEW_REVERSE_OFFSET = _newOffset; 
               moveScrollerDown(); 
               }
            }
         }
      }; 
   this.scrollTop = function() {
      if (IS_SCROLLING == false) {
         NEW_FORWARD_OFFSET = ( - 1 * (iNumOfImages - iNumOfThumbsShown) * this.THUMB_HEIGHT); 
         CURRENT_THUMB_INDEX = iNumOfImages - iNumOfThumbsShown; 
         moveScrollerUp(); 
         }
      }; 
   this.scrollBottom = function() {
      if (IS_SCROLLING == false) {
         NEW_REVERSE_OFFSET = 0; 
         CURRENT_THUMB_INDEX = iNumOfImages - iNumOfThumbsShown; 
         moveScrollerDown(); 
         }
      }; 
      this.scrollImageCenter = function(_currentImageIndex) 
      {
    	  _origOffset = parseInt(_imageScrollerChildDivID.style.left);
//    	  alert("_origOffset = " + _origOffset);
    	  if(_currentImageIndex==0)
    	  {
    		  NEW_REVERSE_OFFSET = 0; 
    		  CURRENT_THUMB_INDEX = _currentImageIndex+1;
    		  moveScrollerRight();
    	  }
    	  var _new = - ( _origOffset );
    	  if(_currentImageIndex > 0)
    	  {
    		  var _eqn = ( _currentImageIndex - (parseInt(iNumOfThumbsShown/2)) ) * (this.THUMB_WIDTH + (2 * iImageScrollAmount * this.THUMB_PADDING));
//    		  alert("_new = " + _new + "_eqn = " + _eqn);
    		  if(_new > _eqn)
    		  {
    			  if(_new>0)
    			  {
	    			  NEW_REVERSE_OFFSET = -(_eqn); 
	        		  CURRENT_THUMB_INDEX = _currentImageIndex+1;
	    			  moveScrollerRight();
    			  }
    		  } else {
    			  if(_eqn<(-MAX_REVERSE_OFFSET))
    			  {
	    			  NEW_FORWARD_OFFSET = -(_eqn); 
	        		  CURRENT_THUMB_INDEX = _currentImageIndex+1;
	    			  moveScrollerLeft();
    			  }
    		  }
    	  }
     };
      function smoothMoveScrollerLeft() {
          _ElementObj = _imageScrollerChildDivID; 
          _currentOffset = parseInt(_ElementObj.style.left); 
          if (_currentOffset > MAX_REVERSE_OFFSET && (_currentOffset - iSmoothSlideAmount) >= MAX_REVERSE_OFFSET) {
             _ElementObj.style.left = _currentOffset - iSmoothSlideAmount + "px"; 
             IS_SCROLLING = true; 
             moveTimer = window.setTimeout(smoothMoveScrollerLeft, iSmoothSlideInterval); 
             }
          else if (_currentOffset > MAX_REVERSE_OFFSET) {
             _ElementObj.style.left = _currentOffset - 1 + "px"; 
             IS_SCROLLING = true; 
             moveTimer = window.setTimeout(smoothMoveScrollerLeft, iSmoothSlideInterval); 
             }
          else {
             IS_SCROLLING = false; 
             window.clearTimeout(moveTimer); 
             }
          }; 
  this.smoothScrollReverse = function() {
      _origOffset = parseInt(_imageScrollerChildDivID.style.left); 
      _currentOffset = parseInt(_imageScrollerChildDivID.style.left); 
      _newOffset = _currentOffset + iSmoothSlideAmount; 
      if (this.THUMB_PADDING > 0) {
         _newOffset = _newOffset + (this.THUMB_PADDING * (2 * iImageScrollAmount)); 
         }
      if (bEnableThumbBorder == 1) {
         _newOffset = _newOffset + 4; 
         }
      if (_newOffset <= 0) {
         if(_currentOffset > (_origOffset - (this.THUMB_WIDTH * iImageScrollAmount))) {
            if (IS_SCROLLING == false) {
               NEW_REVERSE_OFFSET = _newOffset; 
               smoothMoveScrollerRight(); 
               }
            }
         }
      }; 
  this.scrollReverse = function() {
	   
      _origOffset = parseInt(_imageScrollerChildDivID.style.left); 
      _currentOffset = parseInt(_imageScrollerChildDivID.style.left);
      
      _newOffset = _currentOffset + (this.THUMB_WIDTH * iImageScrollAmount); 
      if (this.THUMB_PADDING > 0) {
         _newOffset = _newOffset + (this.THUMB_PADDING * (2 * iImageScrollAmount)); 
         }
      if (bEnableThumbBorder == 1) {
         _newOffset = _newOffset + 4; 
         }
      
      if (_newOffset <= 0) {
         if(_currentOffset > (_origOffset - (this.THUMB_WIDTH * iImageScrollAmount))) {
//        	 alert("iNumOfImages=" + iNumOfImages + "_origOffset" + _origOffset  + "_newOffset = " + _newOffset);
            if (IS_SCROLLING == false) {
               NEW_REVERSE_OFFSET = _newOffset; 
               moveScrollerRight(); 
               }
            }
         }
      }; 
   this.scrollForward = function() {
      _origOffset = parseInt(_imageScrollerChildDivID.style.left); 
      _currentOffset = parseInt(_imageScrollerChildDivID.style.left); 
      _newOffset = _currentOffset - (this.THUMB_WIDTH * iImageScrollAmount); 
      if (this.THUMB_PADDING > 0) {
         _newOffset = _newOffset - ((2 * iImageScrollAmount) * this.THUMB_PADDING); 
         }
      if (bEnableThumbBorder == 1) {
         _newOffset = _newOffset - 4; 
         }
      if (IS_SCROLLING == false && _newOffset >= MAX_REVERSE_OFFSET) {
         NEW_FORWARD_OFFSET = _newOffset; 
         moveScrollerLeft(); 
         }
      }; 
   this.scrollEnd = function() {
      if (IS_SCROLLING == false) {
         NEW_FORWARD_OFFSET = MAX_REVERSE_OFFSET; 
         CURRENT_THUMB_INDEX = iNumOfImages - iNumOfThumbsShown; 
         moveScrollerLeft(); 
         }
      }; 
   this.scrollBegin = function() {
      if (IS_SCROLLING == false) {
         NEW_REVERSE_OFFSET = 0; 
         CURRENT_THUMB_INDEX = 2; 
         moveScrollerRight(); 
         }
      }; 
   this.updateCurrentDescription = function() {
      getElem(objDescriptionDiv).innerHTML = prImageDescription[CURRENT_THUMB_INDEX - 1]; 
      }; 
   this.updateCurrentCount = function() {
      getElem(objCounterDiv).innerHTML = CURRENT_THUMB_INDEX + "/" + iNumOfImages; 
      }; 
   function moveScrollerUp() {
      _ElementObj = _imageScrollerChildDivID; 
      _currentOffset = parseInt(_ElementObj.style.top); 
      if (_currentOffset > NEW_FORWARD_OFFSET && (_currentOffset - iSmoothSlideAmount) >= NEW_FORWARD_OFFSET) {
         _ElementObj.style.top = _currentOffset - iSmoothSlideAmount + "px"; 
         IS_SCROLLING = true; 
         moveTimer = window.setTimeout(moveScrollerUp, iSmoothSlideInterval); 
         }
      else if (_currentOffset > NEW_FORWARD_OFFSET) {
         _ElementObj.style.top = _currentOffset - 1 + "px"; 
         IS_SCROLLING = true; 
         moveTimer = window.setTimeout(moveScrollerUp, iSmoothSlideInterval); 
         }
      else {
         IS_SCROLLING = false; 
         CURRENT_THUMB_INDEX++; 
         window.clearTimeout(moveTimer); 
         if (bEnableThumbDescription == 1) {
            getElem(objDescriptionDiv).innerHTML = prImageDescription[CURRENT_THUMB_INDEX - 1]; 
            }
         if (bEnableCurrentCount == 1) {
            getElem(objCounterDiv).innerHTML = CURRENT_THUMB_INDEX + "/" + iNumOfImages; 
            }
         }
      }; 
   function moveScrollerDown() {
      _ElementObj = _imageScrollerChildDivID; 
      _currentOffset = parseInt(_ElementObj.style.top); 
      if (_currentOffset < NEW_REVERSE_OFFSET && (_currentOffset + iSmoothSlideAmount) <= NEW_REVERSE_OFFSET) {
         _ElementObj.style.top = _currentOffset + iSmoothSlideAmount + "px"; 
         IS_SCROLLING = true; 
         moveTimer = window.setTimeout(moveScrollerDown, iSmoothSlideInterval); 
         }
      else if (_currentOffset < NEW_REVERSE_OFFSET) {
         _ElementObj.style.top = _currentOffset + 1 + "px"; 
         IS_SCROLLING = true; 
         moveTimer = window.setTimeout(moveScrollerDown, iSmoothSlideInterval); 
         }
      else {
         IS_SCROLLING = false; 
         CURRENT_THUMB_INDEX--; 
         window.clearTimeout(moveTimer); 
         if (bEnableThumbDescription == 1) {
            getElem(objDescriptionDiv).innerHTML = prImageDescription[CURRENT_THUMB_INDEX - 1]; 
            }
         if (bEnableCurrentCount == 1) {
            getElem(objCounterDiv).innerHTML = CURRENT_THUMB_INDEX + "/" + iNumOfImages; 
            }
         }
      }; 
   function moveScrollerRight() {
      _ElementObj = _imageScrollerChildDivID; 
      _currentOffset = parseInt(_ElementObj.style.left); 
      if (_currentOffset < NEW_REVERSE_OFFSET && (_currentOffset + iSmoothSlideAmount) <= NEW_REVERSE_OFFSET) {
         _ElementObj.style.left = _currentOffset + iSmoothSlideAmount + "px"; 
         IS_SCROLLING = true; 
         moveTimer = window.setTimeout(moveScrollerRight, iSmoothSlideInterval); 
         }
      else if (_currentOffset < NEW_REVERSE_OFFSET) {
         _ElementObj.style.left = _currentOffset + 1 + "px"; 
         IS_SCROLLING = true; 
         moveTimer = window.setTimeout(moveScrollerRight, iSmoothSlideInterval); 
         }
      else {
         IS_SCROLLING = false; 
         CURRENT_THUMB_INDEX--; 
         window.clearTimeout(moveTimer); 
         if (bEnableThumbDescription == 1) {
            getElem(objDescriptionDiv).innerHTML = prImageDescription[CURRENT_THUMB_INDEX - 1]; 
            }
         if (bEnableCurrentCount == 1) {
            getElem(objCounterDiv).innerHTML = CURRENT_THUMB_INDEX + "/" + iNumOfImages; 
            }
         }
      }; 
   function moveScrollerLeft() {
      _ElementObj = _imageScrollerChildDivID; 
      _currentOffset = parseInt(_ElementObj.style.left); 
      if (_currentOffset > NEW_FORWARD_OFFSET && (_currentOffset - iSmoothSlideAmount) >= NEW_FORWARD_OFFSET) {
         _ElementObj.style.left = _currentOffset - iSmoothSlideAmount + "px"; 
         IS_SCROLLING = true; 
         moveTimer = window.setTimeout(moveScrollerLeft, iSmoothSlideInterval); 
         }
      else if (_currentOffset > NEW_FORWARD_OFFSET) {
         _ElementObj.style.left = _currentOffset - 1 + "px"; 
         IS_SCROLLING = true; 
         moveTimer = window.setTimeout(moveScrollerLeft, iSmoothSlideInterval); 
         }
      else {
         IS_SCROLLING = false; 
         CURRENT_THUMB_INDEX++; 
         window.clearTimeout(moveTimer); 
         if (bEnableThumbDescription == 1) {
            getElem(objDescriptionDiv).innerHTML = prImageDescription[CURRENT_THUMB_INDEX - 1]; 
            }
         if (bEnableCurrentCount == 1) {
            getElem(objCounterDiv).innerHTML = CURRENT_THUMB_INDEX + "/" + iNumOfImages; 
            }
         }
      }; 
   function addAnEvent(_obj, _eventName, _functionName) {
      if (window.addEventListener) {
         _obj.addEventListener(_eventName, _functionName, false); 
         }
      else {
         _obj.attachEvent("on" + _eventName, _functionName); 
         }
      }; 
   function getElem(_elemID) {
      return document.getElementById(_elemID); 
      }; 
   };
