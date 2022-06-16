var $xb=$xb||jQuery.noConflict();
// Event handling
function on(el, evt, fn, bubble) {	
	var evts = evt.split(" "),
		i = 0,
		l = evts.length;
	// Loop through the events and check for standards or IE based even handling	
	for(i; i < l; i++) {
		evt = evts[i];
		if("addEventListener" in el) {
			try {
				el.addEventListener(evt, fn, bubble);
			} catch(e) {}
		} else if("attachEvent" in el) {
			el.attachEvent("on" + evt, fn);
		}
	}
}

function removeEvt(el, evt, fn, bubble) {	
	var evts = evt.split(" "),
		i = 0,
		l = evts.length;
	for(i; i < l; i++) {
		evt = evts[i];
		if("removeEventListener" in el) {
			try {
				el.removeEventListener(evt, fn, bubble);
			} catch(e) {}
		} 
		else if("detachEvent" in el)
			el.detachEvent("on" + evt, fn);
	}
}
(function($) {
	$.fn.xbThumblie = function(options) {
			
	    if(this.length > 1) {
	        this.each(function() { 
	        	$(this).xbThumblie(options);
	        });
	        return this;
	    }
	    
	    // private variables
	    var el = {},
	    	$this = $(this),
	    	_this = this,
			id = $this.attr("id"),
			expr = ":not(.xb-thumblie_cloned, .xb-thumblie_empty)",
			settings = $.extend({
				retina: 1,
				duration: 5,
				fade: 250,
				startW: 100,
				startH: 100,
				showCaptions: true,
				captionHeight: 0,
				fontFamily: "Helvetica,Arial,sans-serif",
				showControls: true,
				showInfo: true,
				autohideControls: false,
				autohideInfo: false,
				galleryCss: {},
				imageCss: {},
				playing: false,
				prevTxt: "Previous",
				nextTxt: "Next",
				playTxt: "Play",
				closeTxt: "Close"}, options);
			
		el[id] = {
			"curImage": null,
			"total": $this.find("li").length,
			"s": settings
		};
	
	    // private methods
	    var initialize = function() {
	
			$("body:not(:has(.xb-thumblie_image-container))") // Match a body tag that *doesn't* contain a .xb-thumblie_image-container class
			.append("<div class=\"xb-thumblie_overlay\"><\/div>" +
					"<div class=\"xb-thumblie_close xb-thumblie_ui\"><p class=\"xb-thumblie_ui_small\" title=\""+el[id].s.closeTxt+"\">X<\/p><\/div>" +
					"<div class=\"xb-thumblie_info xb-thumblie_ui xb-thumblie_ui_small\"><\/div>" +
					"<div class=\"xb-thumblie_image-container\">" +
						"<div class=\"xb-thumblie_image\"><\/div>" +
					"<\/div>" +
					"<div class=\"xb-thumblie_caption xb-thumblie_ui\"><\/div>" +
					"<div class=\"xb-thumblie_controls xb-thumblie_ui\">" +
						"<div class=\"xb-thumblie_prev\"><p class=\"xb-thumblie_ui_small\" title=\""+el[id].s.prevTxt+"\">&laquo;<\/p><\/div>" +
						"<div class=\"xb-thumblie_play\"><p class=\"xb-thumblie_ui_small\" title=\""+el[id].s.playTxt+"\">&gt;<\/p><\/div>" +
						"<div class=\"xb-thumblie_next\"><p class=\"xb-thumblie_ui_small\" title=\""+el[id].s.nextTxt+"\">&raquo;<\/p><\/div>" +
					"<\/div>");
			
			el[id].$ui = $(".xb-thumblie_ui");
			el[id].$info = $(".xb-thumblie_info");
			el[id].$controls = $(".xb-thumblie_controls");
			el[id].$prev = el[id].$controls.find(".xb-thumblie_prev > p").add($(".xb-thumblie_prev"));
			el[id].$next = el[id].$controls.find(".xb-thumblie_next > p").add($(".xb-thumblie_next"));
			el[id].$play = el[id].$controls.find(".xb-thumblie_play > p");
			el[id].$close = $(".xb-thumblie_close > p");
			el[id].$title = $(".xb-thumblie_image-container > .xb-thumblie_title");
			el[id].$caption = $(".xb-thumblie_caption");
			el[id].$list = $this.find("ul");
			el[id].$usableItems = el[id].$list.find("li");
			el[id].$images = el[id].$usableItems.find("> a");
			el[id].$viewer = $(".xb-thumblie_image-container");
			el[id].$galleryViewer = $(".xb-thumblie_image-container");
			el[id].$overlay = $(".xb-thumblie_overlay");
	
			window.tlIndex = 0;
			
			var temp = (document.body || document.documentElement).appendChild(document.createElement("div")),
				tempImg = temp.appendChild(document.createElement("img"));
			temp.style.visibility = "hidden";
			temp.setAttribute("class", "exibid-thumblie_image-container");
			
			el[id].s.borderWidth = $(tempImg).outerHeight();
			
			(document.body || document.documentElement).removeChild(temp);
			temp = null;
			
			$(window).resize(_this.adjustGallery);
			
			$(el[id].$title).add(el[id].$caption).css({"text-overflow":"ellipsis"});
			
			el[id].$info.hide();
			
			// When an image is clicked on:
			el[id].$images.click(function() {
				
				// Show the overlay
				showOverlay(this);
				
				// Prevent the default behaviour
				return false;
			});
			
	        return _this;
	    },
	
		autohideIn = function() {
			if(el[id].s.autohideInfo)
				el[id].$info.show();
			if(el[id].s.autohideControls)
				el[id].$controls.show();
		},
		
		autohideOut = function() {
			if(el[id].s.autohideInfo)
				el[id].$info.add(el[id].s.autohideControls ? el[id].$controls : null).hide();
			else if(el[id].s.autohideControls)
				el[id].$controls.hide();
		},
		
		/*****************************************************************
        * The following three methods are based on Brad Birdsall's excellent Swipe
        *
        * Swipe 1.0
        *
        * Brad Birdsall, Prime
        * Copyright 2011, Licensed GPL & MIT
        * http://swipejs.com
        *****************************************************************/
 
		touchStart = function(e) {
			// Get the touch start points
			this.touch = {
				startX: e.touches[0].pageX,
				startY: e.touches[0].pageY,
				// set initial timestamp of touch sequence
				time: Number( new Date() )	
			};
			
			// used for testing first onTouchMove event
			this.touch.isScrolling = undefined;
			
			// reset deltaX
			this.touch.deltaX = 0;
			
		},
		
		touchMove = function(e) {
			// If we detect more than one finger or a pinch, don't do anything
			if(e.touches.length > 1 || e.scale && e.scale !== 1) {
				return;
			}
			this.touch.deltaX = e.touches[0].pageX - this.touch.startX;
			
			// determine if scrolling test has run - one time test
			if ( typeof this.touch.isScrolling == "undefined") {
				this.touch.isScrolling = !!( this.touch.isScrolling || Math.abs(this.touch.deltaX) < Math.abs(e.touches[0].pageY - this.touch.startY) );
			}
			
			// if user is not trying to scroll vertically
			if (!this.touch.isScrolling) {
								
				// prevent native scrolling 
				e.preventDefault ? e.preventDefault() : e.returnValue = false;
			}
		},
		
		touchEnd = function(e) {
			
            // If we detect more than one finger or a pinch, don't do anything
            if(e.touches.length > 1 || e.scale && e.scale !== 1) {
                return;
            }
 
			// determine if slide attempt triggers next/prev slide
			var isValidSlide = 
				  Number(new Date()) - this.touch.time < 250      	// if slide duration is less than 250ms
				  && Math.abs(this.touch.deltaX) > 20               // and if slide amt is greater than 20px
				  || Math.abs(this.touch.deltaX) > windowWidth()/2, // or if slide amt is greater than half the width
		
			// determine if slide attempt is past start and end
				isPastBounds = 
				  !this.index && this.touch.deltaX > 0                          // if first slide and slide amt is greater than 0
				  || this.index == this.length - 1 && this.touch.deltaX < 0;    // or if last slide and slide amt is less than 0
			
			// if not scrolling vertically
			if (!this.touch.isScrolling) {
				// call slide function with slide end value based on isValidSlide and isPastBounds tests
				if(isValidSlide) {
					(this.touch.deltaX > 0 ? _this.prevImage() : _this.nextImage());
				 }
		
			}
			this.touch = undefined;
		},
		
		addListeners = function() {
			// Hide everything when clicking the overlay or the close button
			$(el[id].$overlay).add(el[id].$close).click(hideAll);
			
			// Listeners
			on(window, "touchstart", touchStart, false);
			on(window, "touchmove", touchMove, false);
			on(window, "touchend", touchEnd, false);
			
			// Display the next image when clicking the previous image link or the left side of the image
			el[id].$prev.click(_this.prevImage);
			
			// Display the next image when clicking the next image link or the right side of the image
			el[id].$next.click(_this.nextImage);
				
			// Play/pause the slideshow when the play/pause buttons are clicked
			el[id].$play.click(_this.playPause);
			
			// Autohide the controls and info when hovering over the viewer
			if(el[id].s.autohideControls || el[id].s.autohideInfo)
				el[id].$viewer.hover(autohideIn, autohideOut);
			
			// track key presses
			$(document).keydown(function(e){
					// Hide everything when the escape key is pressed
					if(e.which == 27)
						hideAll();
					// Play/pause the slideshow when the spacebar is pressed
					else if(e.which == 32) // Spacebar
					{
						if(!$( document.activeElement ).is("input, textarea, select"))
							return _this.playPause();
					}
					// Go to the prev/next image when the left/right arrows are pressed
					else if(e.which == 37) // Left arrow
						return _this.prevImage();
					else if(e.which == 39) // Right arrow
						return _this.nextImage();
				});
		},
		
		removeEventListeners = function() {
			// Unbind the overlay, close button, prev/next and play buttons
			el[id].$overlay
				.add(el[id].$close)
				.add(el[id].$prev)
				.add(el[id].$next)
				.add(el[id].$play)
				.unbind("click");
			// Unbind the autohide
			el[id].$viewer.unbind();
			// Unbind the key presses
			$(document).unbind("keydown");
			
			removeEvt(window, "touchstart", touchStart, false);
			removeEvt(window, "touchmove", touchMove, false);
			removeEvt(window, "touchend", touchEnd, false);
		},
		
		resetDuration = function() {
			if(el[id].s.playing) {
				el[id].$play.text("\\");
				clearInterval(el[id].duration);
				el[id].duration = setInterval(_this.nextImage, el[id].s.duration * 1000);
			}
		},
		
		loadImages = function() {
			if(!el[id].$usableItems.index(el[id].curImage)) {
				$('<img />').attr('src', $(el[id].curImage).next(expr).find("> a").attr("href"));
				$('<img />').attr('src', el[id].$usableItems.last().find("> a").attr("href"));
			} else if(el[id].$usableItems.index(el[id].curImage) == (el[id].total-1)) {
				$('<img />').attr('src', el[id].$usableItems.first().find("> a").attr("href"));
				$('<img />').attr('src', $(el[id].curImage).prev(expr).find("> a").attr("href"));
			} else {
				$('<img />').attr('src', $(el[id].curImage).next(expr).find("> a").attr("href"));
				$('<img />').attr('src', $(el[id].curImage).prev(expr).find("> a").attr("href"));
			}
		},
		
		hideAll = function() {
			el[id].$overlay.removeClass(id + "-thumblie_image-container");
			el[id].$galleryViewer.removeClass(id + "-thumblie_image-container");
			el[id].$ui.removeClass(id + "-thumblie_ui");
			el[id].$info.removeClass(id + "-thumblie_info");
			el[id].$caption.removeClass(id + "-thumblie_caption");
			
			// Find the overlay element and hide it
			el[id].$overlay.stop().hide();
			
			// Find the gallery element and hide it
			el[id].$viewer.stop().hide();
			
			el[id].$ui.hide();
			
			el[id].$viewer.find("img").empty();
			
			el[id].$title.empty();
			
			// Stop to the slideshow
			el[id].s.playing = false;
			clearInterval(el[id].duration);
			el[id].$play.text(">");
			
			removeEventListeners();
			el[id].curImage = null;
			return false;
		},
				
		showOverlay = function(anchor) {
			// Change the color of the overlay and hide it using opacity:0,
			el[id].$overlay.css({
				opacity: 0
			})
			
			.addClass(id + "-thumblie_overlay")
			
			// then show it (this changes its display state)
			.show(function(){
				el[id].$close.parent().show();
			})
			
			// and finally, animate its opacity up to the specified opacity
			.animate({
					opacity: 1
				}, 100, function(){
					// Show the gallery placeholder
					showImage($(anchor).parent());
				}
			);
			addListeners();
		},
				
		showImage = function(listItem) {
			// Remember the image
			if($(listItem).parent().hasClass("xb-thumblie_thumbs"))
			{
				var prevImage = el[id].curImage;
				el[id].curImage = listItem;
			}
			else
				throw "showImage error: Incorrect element passed";
	
			window.tlIndex = el[id].$usableItems.index(listItem);
			
			// Load the next/prev images, stop events, and reset the slideshow timer
			loadImages();
			el[id].$viewer.stop();
			clearInterval(el[id].duration);
			
			// Add the customization classes
			el[id].$ui.addClass(id + "-thumblie_ui");
			el[id].$galleryViewer.addClass(id + "-thumblie_image-container");
			el[id].$info.addClass(id + "-thumblie_info");
			el[id].$caption.addClass(id + "-thumblie_caption");
			
			// Get the gallery_image element
			el[id].$image = $(".xb-thumblie_image");
						
			var anchor = $(el[id].curImage).find("> a"),
				path = anchor.attr("href"),
				image = new Image(),
				$thumb = $(anchor).find("> img"),
				$link = el[id].$image.find("> a");
			
			
			// Remove the old one if there
			el[id].$image.find("img").remove();
			el[id].$title.empty();
			
			$(".xb-thumblie_image > .xb-thumblie_prev, .xb-thumblie_image > .xb-thumblie_next").hide();
			  
			// Set the gallery viewer text color and then hide it
			el[id].$title.css({color: el[id].s.galleryTxtColor}).hide();
			
			// Show and resize the placeholder if necessary
			$(".xb-thumblie_image-container:hidden").css(el[id].s.galleryCss)
				.css({
					background: el[id].s.galleryBg, 
					width: el[id].s.startW, 
					height: el[id].s.startH,
					top: ((window.innerHeight || $(window).height()) / 2) - el[id].s.startH / 2,
					left: ((window.innerWidth || $(window).width()) / 2) - el[id].s.startW / 2,
					fontFamily: el[id].s.fontFamily
				})
				.show();
			
			// Once the new image has loaded place it in the gallery_image div
			$(image).load(function() {
				var width = (image.width / (el[id].s.retina + 1)),
					height = (image.height / (el[id].s.retina + 1)),
					scale = Math.max(width  / $(window).width(), height / $(window).height()),
					fittedWidth = width / scale,
					fittedHeight = height / scale;
				
				el[id].visibleImage = this;
				
				el[id].image = {"width": width, "height": height};
				
				if(fittedWidth > width || fittedHeight > height) {
					fittedWidth = width;
					fittedHeight = height;
				}
				
				if(el[id].$overlay.is(":visible")) {
					el[id].$image.prepend(image);
					// Add a link if one has been set
					if($thumb.attr("data-url"))
						if($link.get(0))
							$link.attr("href", $thumb.attr("data-url"))
						else
							el[id].$image.append("<a class=\"xb-thumblie_image-link\" href=\""+$thumb.attr("data-url")+"\">");
					else
						if($link.get(0))
							$link.remove();
					// Show and resize the placeholder if necessary
					el[id].$viewer.animate({
						height: fittedHeight,
						width: fittedWidth,
						top: (((window.innerHeight || $(window).height()) / 2) - fittedHeight / 2),
						left: ((window.innerWidth || $(window).width()) / 2) - fittedWidth / 2
					}, 200, function(){
						// Show the image once the animation has completed
						$(image).css(el[id].s.imageCss)
							.css({
								height: fittedHeight,
								width: fittedWidth,
								opacity: 0
							})
							.show()
							.animate({
								opacity: 1
							}, 200, function() {
								if(el[id].s.showControls) {
									if(el[id].s.autohideControls)
										el[id].$controls.hide();
									else if((!el[id].s.autohideControls || el[id].s.autohideControls && prevImage) || (el[id].s.autohideControls && !prevImage))
										el[id].$controls.show();
								}
								else {
									el[id].$controls.hide();
								}
								if(el[id].s.showInfo) {
									if(el[id].s.autohideInfo)
										el[id].$info.hide();
									else if((!el[id].s.autohideInfo || el[id].s.autohideInfo && prevImage) || (el[id].s.autohideInfo && !prevImage)) {
										var listItems = el[id].$list.find("li:not(.xb-thumblie_empty, .xb-thumblie_cloned)");
										el[id].$info.text(($(listItems).index(el[id].curImage)+1)+" / "+el[id].total).show();
									}
								}
								else {
									el[id].$info.hide();
								}
								resetDuration();
							});
						if(el[id].s.showCaptions) {
							el[id].$caption.empty();
							var title = $thumb.attr("title"),
								desc = $thumb.attr("alt");
							if(title)
								el[id].$caption.append("<h1>" + title + "</h1>").show();
							if(desc)
								el[id].$caption.append("<p>" + desc + "</p>").show();
							if(!title && ! desc)
								el[id].$caption.hide();
						}
						else {
							el[id].$caption.hide();
						}
					});
				}
			}).attr("src", path).hide();
		}
	
	    // public methods  
	    
	    this.playPause = function() {
			if(!el[id].s.playing) {
				el[id].duration = setInterval(_this.nextImage, el[id].s.duration * 1000);
				el[id].$play.text("\\");
			}
			else {
				clearInterval(el[id].duration);
				el[id].$play.text(">");
			}
			el[id].s.playing = !el[id].s.playing;
			return false;
		}
							
		this.prevImage = function() {
			try {
				showImage($(el[id].curImage).prev());
			} catch (e) {
				showImage(el[id].$usableItems.last());
			}
			return false;
		}
		
		this.nextImage = function() {
			try {
				showImage($(el[id].curImage).next());
			} catch (e) {
				showImage(el[id].$usableItems.first());
			}
			return false;
		}
			
		this.adjustGallery = function() {
			// If the gallery isn't there then return
			if(el[id].curImage === null)
				return;
				
			var width = el[id].image.width,
				height = el[id].image.height,
				scale = Math.max(width  / ($(window).width()), height / ($(window).height())),
				fittedWidth = width / scale,
				fittedHeight = height / scale;
			
			if(fittedWidth > width || fittedHeight > height) {
				fittedWidth = width;
				fittedHeight = height;
			}
			
			el[id].$viewer.css({
				height: fittedHeight,
				width: fittedWidth,
				top: ((window.innerHeight || $(window).height()) / 2) - fittedHeight / 2,
				left: ((window.innerWidth || $(window).width()) / 2) - fittedWidth / 2
			});
			
			el[id].$viewer.find("img").css({
				height: fittedHeight,
				width: fittedWidth
			});
		}
		
		this.showImageAtIndex = function(index) {
			if(!el[id].$images[index])
				return;
			// If the overlay is visible then just show the image
			if(el[id].$overlay.is(":visible"))
				showImage(el[id].$usableItems[index]);
			else
				showOverlay(el[id].$images[index]);
		}
		
		this.editSettings = function(settings) {
			el[id].s = $.extend(el[id].s, settings);
			this.refresh();
		}
		
		this.refresh = function() {
			if(el[id].curImage === null)
				return;
			showImage($(el[id].$usableItems[window.tlIndex]));
		}
					
		return initialize();
	}
})(jQuery);