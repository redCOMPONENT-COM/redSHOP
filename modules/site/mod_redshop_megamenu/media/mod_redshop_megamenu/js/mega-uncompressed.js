/* hoverIntent is similar to jQuery's built-in "hover" method except that
 * instead of firing the handlerIn function immediately, hoverIntent checks
 * to see if the user's mouse has slowed down (beneath the sensitivity
 * threshold) before firing the event. The handlerOut function is only
 * called after a matching handlerIn.
 *
 * // basic usage ... just like .hover()
 * .hoverIntent( handlerIn, handlerOut )
 * .hoverIntent( handlerInOut )
 *
 * // basic usage ... with event delegation!
 * .hoverIntent( handlerIn, handlerOut, selector )
 * .hoverIntent( handlerInOut, selector )
 *
 * // using a basic configuration object
 * .hoverIntent( config )
 *
 * @param  handlerIn   function OR configuration object
 * @param  handlerOut  function OR selector for delegation OR undefined
 * @param  selector    selector OR undefined
 * @author Brian Cherne <brian(at)cherne(dot)net>
 */

(function(factory) {
	'use strict';
	if (typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	} else if (jQuery && !jQuery.fn.hoverIntent) {
		factory(jQuery);
	}
})(function($) {
	'use strict';

	// default configuration values
	var _cfg = {
		interval: 100,
		sensitivity: 6,
		timeout: 0
	};

	// counter used to generate an ID for each instance
	var INSTANCE_COUNT = 0;

	// current X and Y position of mouse, updated during mousemove tracking (shared across instances)
	var cX, cY;

	// saves the current pointer position coordinated based on the given mouse event
	var track = function(ev) {
		cX = ev.pageX;
		cY = ev.pageY;
	};

	// compares current and previous mouse positions
	var compare = function(ev,$el,s,cfg) {
		// compare mouse positions to see if pointer has slowed enough to trigger `over` function
		if ( Math.sqrt( (s.pX-cX)*(s.pX-cX) + (s.pY-cY)*(s.pY-cY) ) < cfg.sensitivity ) {
			$el.off('mousemove.hoverIntent'+s.namespace,track);
			delete s.timeoutId;
			// set hoverIntent state as active for this element (so `out` handler can eventually be called)
			s.isActive = true;
			// clear coordinate data
			delete s.pX; delete s.pY;
			return cfg.over.apply($el[0],[ev]);
		} else {
			// set previous coordinates for next comparison
			s.pX = cX; s.pY = cY;
			// use self-calling timeout, guarantees intervals are spaced out properly (avoids JavaScript timer bugs)
			s.timeoutId = setTimeout( function(){compare(ev, $el, s, cfg);} , cfg.interval );
		}
	};

	// triggers given `out` function at configured `timeout` after a mouseleave and clears state
	var delay = function(ev,$el,s,out) {
		delete $el.data('hoverIntent')[s.id];
		return out.apply($el[0],[ev]);
	};

	$.fn.hoverIntent = function(handlerIn,handlerOut,selector) {
		// instance ID, used as a key to store and retrieve state information on an element
		var instanceId = INSTANCE_COUNT++;

		// extend the default configuration and parse parameters
		var cfg = $.extend({}, _cfg);
		if ( $.isPlainObject(handlerIn) ) {
			cfg = $.extend(cfg, handlerIn );
		} else if ($.isFunction(handlerOut)) {
			cfg = $.extend(cfg, { over: handlerIn, out: handlerOut, selector: selector } );
		} else {
			cfg = $.extend(cfg, { over: handlerIn, out: handlerIn, selector: handlerOut } );
		}

		// A private function for handling mouse 'hovering'
		var handleHover = function(e) {
			// cloned event to pass to handlers (copy required for event object to be passed in IE)
			var ev = $.extend({},e);

			// the current target of the mouse event, wrapped in a jQuery object
			var $el = $(this);

			// read hoverIntent data from element (or initialize if not present)
			var hoverIntentData = $el.data('hoverIntent');
			if (!hoverIntentData) { $el.data('hoverIntent', (hoverIntentData = {})); }

			// read per-instance state from element (or initialize if not present)
			var state = hoverIntentData[instanceId];
			if (!state) { hoverIntentData[instanceId] = state = { id: instanceId }; }

			// state properties:
			// id = instance ID, used to clean up data
			// timeoutId = timeout ID, reused for tracking mouse position and delaying "out" handler
			// isActive = plugin state, true after `over` is called just until `out` is called
			// pX, pY = previously-measured pointer coordinates, updated at each polling interval
			// namespace = string used as namespace for per-instance event management

			// clear any existing timeout
			if (state.timeoutId) { state.timeoutId = clearTimeout(state.timeoutId); }

			// event namespace, used to register and unregister mousemove tracking
			var namespace = state.namespace = '.hoverIntent'+instanceId;

			// handle the event, based on its type
			if (e.type === 'mouseenter') {
				// do nothing if already active
				if (state.isActive) { return; }
				// set "previous" X and Y position based on initial entry point
				state.pX = ev.pageX; state.pY = ev.pageY;
				// update "current" X and Y position based on mousemove
				$el.on('mousemove.hoverIntent'+namespace,track);
				// start polling interval (self-calling timeout) to compare mouse coordinates over time
				state.timeoutId = setTimeout( function(){compare(ev,$el,state,cfg);} , cfg.interval );
			} else { // "mouseleave"
				// do nothing if not already active
				if (!state.isActive) { return; }
				// unbind expensive mousemove event
				$el.off('mousemove.hoverIntent'+namespace,track);
				// if hoverIntent state is true, then call the mouseOut function after the specified delay
				state.timeoutId = setTimeout( function(){delay(ev,$el,state,cfg.out);} , cfg.timeout );
			}
		};

		// listen for mouseenter and mouseleave
		return this.on({'mouseenter.hoverIntent':handleHover,'mouseleave.hoverIntent':handleHover}, cfg.selector);
	};
});


(function($) {
	jQuery.fn.shopbMegaMenu = function(s) {
		var p;
		var $overlay;
		var $this = $(this);
		$.extend(p = {
			showSpeed: 300,
			hideSpeed: 300,
			trigger: "hover",
			showDelay: 0,
			hideDelay: 0,
			effect: "fade",
			align: "left",
			responsive: true,
			animation: "none",
			indentChildren: true,
			indicatorFirstLevel: "+",
			indicatorSecondLevel: "+",
			showOverlay: false
		}, s);
		var o = $(this);
		var m = $(o).children(".shopbMegaMenu-menu");
		var k = $(m).find("li");
		var j;
		var i = 768;
		var h = 2000;
		var r = 200;
		$(m).children("li").children("a").each(function() {
			if ($(this).siblings(".dropdown, .megamenu")["length"] > 0) {
				$(this).append("<span class='indicator'>" + p.indicatorFirstLevel + "</span>")
			}
		});
		$(m).find(".dropdown").children("li").children("a").each(function() {
			if ($(this).siblings(".dropdown")["length"] > 0) {
				$(this).append("<span class='indicator'>" + p.indicatorSecondLevel + "</span>")
			}
		});
		$(m).find(".megamenu").find('li ul li a').each(function() {
			if ($(this).siblings(".dropdown")["length"] > 0) {
				$(this).append("<span class='indicator'>" + p.indicatorSecondLevel + "</span>")
			}
		});
		if (p.align == "right") {
			$(m).addClass("shopbMegaMenu-right")
		}
		if (p.indentChildren) {
			$(m).addClass("shopbMegaMenu-indented")
		}
		if (p.responsive) {
			$(o).addClass("shopbMegaMenu-responsive").prepend("<a href='javascript:void(0)' class='showhide'><em></em><em></em><em></em></a>");
			j = $(o).children(".showhide")
		}

		function openMenu(event, $object) {
			event = event || 'hover';
			if (event != 'click'){
				$object = $(this);
			}

			var $subMenu = $object.children(".dropdown, .megamenu");
			if ($subMenu.length){
				$subMenu.delay(p.showDelay).addClass(p.animation);
				if (p.showOverlay == true) {
					$overlay.show();
				}

				if (p.effect == "fade") {
					$subMenu.fadeIn(p.showSpeed)
				} else {
					$subMenu.slideDown(p.showSpeed)
				}
			}
		}

		function closeMenu(event, $this) {
			event = event || 'hover';

			if (event != 'click'){
				$this = $(this);
			}

			var $subMenu = $this.children(".dropdown, .megamenu");
			if ($subMenu.length) {
				$subMenu.delay(p.hideDelay).removeClass(p.animation);
				if (p.effect == "fade") {
					$subMenu.fadeOut(p.hideSpeed)
				} else {
					$subMenu.slideUp(p.hideSpeed)
				}

				if (p.showOverlay == true && $overlay != undefined) {
					$overlay.hide();
				}
			}
		}

		function executeMenu() {
			$(m).find(".dropdown, .megamenu").hide(0);
			if (navigator.userAgent.match(/Mobi/i) || window.navigator["msMaxTouchPoints"] > 0 || p.trigger == "click") {
				$(".shopbMegaMenu-menu > li > a, .shopbMegaMenu-menu ul.dropdown li a").bind("click touchstart", function(v) {
					v.stopPropagation();
					v.preventDefault();
					$(this).parent("li").siblings("li").find(".dropdown, .megamenu").stop(true, true).fadeOut(300);
					if ($(this).siblings(".dropdown, .megamenu").css("display") == "none") {
						openMenu('click', $(this).parent("li"));
						return false
					} else {
						closeMenu('click', $(this).parent("li"))
					}
					window.location["href"] = $(this)["attr"]("href")
				});
				$(document).bind("click.menu touchstart.menu", function(v) {
					if ($(v.target)["closest"](".shopbMegaMenu")["length"] == 0) {
						$(".shopbMegaMenu-menu").find(".dropdown, .megamenu").fadeOut(300)
					}
				})
			} else {
				$(k).hoverIntent(openMenu,closeMenu);
			}
		}

		function initRespSub() {
			$(m).find(".dropdown, .megamenu").hide(0);
			$(m).find(".indicator").each(function() {
				if ($(this).parent("a").siblings(".dropdown, .megamenu")["length"] > 0) {
					$(this).bind("click", function(v) {
						if ($(this).parent().prop("tagName") == "A") {
							v.preventDefault()
						}
						if ($(this).parent("a").siblings(".dropdown, .megamenu").css("display") == "none") {
							$(this).parent("a").siblings(".dropdown, .megamenu").delay(p.showDelay).slideDown(p.showSpeed);
							$(this).parent("a").parent("li").siblings("li").find(".dropdown, .megamenu").slideUp(p.hideSpeed)
						} else {
							$(this).parent("a").siblings(".dropdown, .megamenu").slideUp(p.hideSpeed)
						}
					})
				}
			})
		}

		function alignPosForSubMenu() {
			var w = $(m).children("li").children(".dropdown");
			if ($(window).innerWidth() > i) {
				var v = $(o).outerWidth(true);
				for (var x = 0; x < w.length; x++) {
					if ($(w[x]).parent("li").position.left + $(w[x]).outerWidth() > v) {
						$(w[x]).css("right", 0)
					} else {
						if (v == $(w[x]).outerWidth() || (v - $(w[x]).outerWidth()) < 20) {
							$(w[x]).css("left", 0)
						}
						if ($(w[x]).parent("li").position.left + $(w[x]).outerWidth() < v) {
							$(w[x]).css("right", "auto")
						}
					}
				}
			}
		}

		function initResponsiveMenu() {
			$(m).hide(0);
			$(j).show(0).click(function() {
				if ($(m).css("display") == "none") {
					$(m).slideDown(p.showSpeed)
				} else {
					$(m).slideUp(p.hideSpeed).find(".dropdown, .megamenu").hide(p.hideSpeed)
				}
			})
		}

		function backInNormalMenu() {
			$(m).show(0);
			$(j).hide(0);
		}

		function unbindEvents() {
			$(o).find("li, a").unbind();
			$(document).unbind("click.menu touchstart.menu")
		}

		function t() {
			function x(A) {
				var z = $(A).find(".shopbMegaMenu-tabs-nav > li");
				var y = $(A).find(".shopbMegaMenu-tabs-content");
				$(z).bind("click touchstart", function(B) {
					B.stopPropagation();
					B.preventDefault();
					$(z).removeClass("active");
					$(this).addClass("active");
					$(y).hide(0);
					$(y[$(this).index()]).show(0)
				})
			}
			if ($(m).find(".shopbMegaMenu-tabs")["length"] > 0) {
				var v = $(m).find(".shopbMegaMenu-tabs");
				for (var w = 0; w < v.length; w++) {
					x(v[w])
				}
			}
		}

		function browserWidth() {
			return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth
		}

		function initMenu() {
			alignPosForSubMenu();
			var megaMenu = $(".megamenu");
			megaMenu.css({'left': '0px', 'width': '100%'});
			var leftOffest =  - (document.body.clientWidth - megaMenu.width()) / 2;
			megaMenu.css({'left': leftOffest + 'px', 'width': (document.body.clientWidth) + 'px'});
			if (browserWidth() <= i && h > i) {
				unbindEvents();
				if (p.responsive) {
					initResponsiveMenu();
					initRespSub()
				} else {
					executeMenu()
				}
			}
			if (browserWidth() > i && r <= i) {
				unbindEvents();
				backInNormalMenu();
				executeMenu()
			}
			h = browserWidth();
			r = browserWidth();
			t();
			if (/MSIE (d+.d+);/ ["test"](navigator.userAgent) && browserWidth() < i) {
				var v = new Number(RegExp.$1);
				if (v == 8) {
					$(j).hide(0);
					$(m).show(0);
					unbindEvents();
					executeMenu()
				}
			}

			$('.shopbMegaMenu').on('click', '.accordion-toggle', function(){
				var $collapse = $(this).parent().parent().find('.collapse');
				if ($collapse.hasClass('in')){
					$collapse.css({'overflow':'hidden'});
				}
			}).on('hidden.bs.collapse', '.accordion', function(item){
				$(item.target).css({'overflow':'hidden'});
			}).on('shown.bs.collapse', '.accordion', function(item){
				$(item.target).css({'overflow':'visible'});
			});

			if (p.showOverlay == true) {
				var topPos = $this.offset().bottom;
				$overlay = $('<div>').css({
					"position": "fixed",
					"top": topPos + "px",
					"left": 0,
					"width": "100%",
					"height": "100%",
					"background-color": "#000",
					"z-index": 1,
					"opacity": 0.75,
					"display": "none"
				});
				$overlay.appendTo($this).hide();
			}
		}

		initMenu();
		$(window).resize(function() {
			initMenu();
			alignPosForSubMenu()
		})
	}
}(jQuery));