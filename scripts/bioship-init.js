
/* Mobile Button Functions */
// 1.8.0: use jQuery instead of plain javascript
function showmainmenu() {jQuery(function($) {
	$('#mainmenushow').css('display','none'); $('#mainmenuhide').css('display','inline-block'); $('#mainmenu').css('display','block');
}); }
function hidemainmenu() {jQuery(function($) {
	$('#mainmenuhide').css('display','none'); $('#mainmenushow').css('display','inline-block'); $('#mainmenu').css('display','none');
}); }

function showsidebar() {jQuery(function($) {
	if (document.getElementById('subsidebarshow')) {hidesubsidebar();}
	$('#sidebarshow').css('display','none'); $('#sidebarshowsmall').css('display','none');
	$('#sidebarhide').css('display','block'); $('#sidebarhidesmall').css('display','block');
	$('#sidebar').css('display','block').css('width','100%');
}); }
function hidesidebar() {jQuery(function($) {
	$('#sidebarhide').css('display','none'); $('#sidebarhidesmall').css('display','none');
	$('#sidebarshow').css('display','block'); $('#sidebarshowsmall').css('display','block');
	$('#sidebar').css('display','none').css('width','');
}); }

function showsubsidebar() {jQuery(function($) {
	if (document.getElementById('sidebarshow')) {hidesidebar();}
	$('#subsidebarshow').css('display','none'); $('#subsidebarshowsmall').css('display','none');
	$('#subsidebarhide').css('display','block'); $('#subsidebarhidesmall').css('display','block');
	$('#subsidebar').css('display','block').css('width','100%');
}); }
function hidesubsidebar() {jQuery(function($) {
	$('#subsidebarhide').css('display','none'); $('#subsidebarhidesmall').css('display','none');
	$('#subsidebarshow').css('display','block'); $('#subsidebarshowsmall').css('display','block');
	$('#subsidebar').css('display','none').css('width','');
}); }

// 1.8.5: smooth scroll to top method
function scrolltotop() {jQuery('html,body').animate({scrollTop: 0}, 1000);}

/* Page Load Functions */
jQuery(document).ready(function($) {

	// Load Superfish Menu
	// 1.8.5: added check if superfish function exists
	$(function(){
		if (typeof superfish === 'function') {
			$('#navigation ul.menu')
			.find('li.current_page_item,li.current_page_parent,li.current_page_ancestor,li.current-cat,li.current-cat-parent,li.current-menu-item')
			.addClass('active').end().superfish({autoArrows: true});
		}
	});

	// valid XHTML method of target_blank
	$(function(){$('a[rel*="external"]').click( function() {window.open(this.href); return false;}); });

	// Style Tags
	$(function(){$('p.tags a').wrap('<span class="st_tag" />');});

	// Focus on search form on 404 pages
	$(function(){$("body.error404 #content #s").focus();});

	// Smooth Hash Link Scrolling
	/* http://css-tricks.com/snippets/jquery/smooth-scrolling/ */
	// 2.0.9: use variable check instead of input check
	if (typeof smoothscrolling !== 'undefined') {
		if (smoothscrolling == 'yes') {
			/* 1.8.0: add selector quotes for jquery 1.12 fix (WP 4.5)*/
			$('a[href*="#"]:not([href="#"])').click(function() {
				if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
					var target = $(this.hash);
					target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
					if (target.length) {
						var scrollto = target.offset().top + 150;
						$('html,body').animate({scrollTop: scrollto}, 1000);
						return false;
					}
				}
			});
		}
	}

	/* Sticky Kit */
	// 1.5.0: maybe Trigger Sticky Page Elements
	// 1.9.9: revamped sticky kit function
	// 2.0.9: use element array instead of input field
	function stickyelements() {
		if (typeof stickyelements !== 'undefined') {
			for (i in stickyelements) {
				$element = $(stickyelements[i]);
				if ($element.is(':visible')) {
					$element.stick_in_parent().on('sticky_kit:stick', function(e) {
						/* display glitch bypass: trim 1px on stick */
						parentwidth = $(e.target).parent().width();
						$(e.target).parent().width(parentwidth-1+'px');
					});
				} else {$(stickyelements[i]).trigger('sticky_kit:detach');}
			}
		}
	}
	stickyelements();

	/* FitVids */
	// 1.5.0: maybe Trigger FitVids Elements
	// 1.9.9: optimized fitvids array code
	// 2.0.9: use element array instead of input field
	if (typeof fitvidselements !== 'undefined') {
		for (i in fitvidselements) {$(fitvidselements[i]).fitVids();}
	}

	/* Modernizr */
	// 2.0.9: maybe initialize Modernizr
	if (typeof loadmodernizr !== 'undefined') {
		if (loadmodernizr == 'yes') {$(document).Modernizr();}
	}

	/* Foundation */
	// maybe initialize Foundation
	if (typeof loadfoundation !== 'undefined') {
		if (loadfoundation == 'yes') {$(document).foundation();}
	}

	/* MatchHeight */
	// 1.9.9: maybe run jquery matchHeight
	function matchheights() {
		if (typeof loadmatchheights !== 'undefined') {
			if (loadmatchheights == 'yes') {$('.matchheight').matchHeight();}
		}
	}
	matchheights();

	/* Check Mobile Buttons */
	function checkmobilebuttons() {
		screenwidth = $(window).width();

		// show menu at 480
		if (document.getElementById('mainmenushow')) {
			if (document.getElementById('mainmenushow').style.display == 'none') {
				// hide actually means restore - show in this case
				if (screenwidth > 479) {hidemainmenu();}
			}
		}

		// show sidebar at 640
		if (document.getElementById('sidebarshow')) {
			if (document.getElementById('sidebarshow').style.display == 'none') {
				// hide actually means (restore) show in this case
				if (screenwidth > 639) {hidesidebar();}
			}
		}

		// show in subsidebar at 768
		if (document.getElementById('subsidebarshow')) {
			if (document.getElementById('subsidebarshow').style.display == 'none') {
				// hide actually means restore - show in this case
				if (screenwidth > 767) {hidesubsidebar();}
			}
		}
	}
	checkmobilebuttons();


	/* Dynamic Header Resizing */

	var startheaderwidth = $('#header').width();
	var startheaderheight = $('#header').height();

	// 1.8.5: maybe resize header logo
	var logowidth = $('#site-logo img.logo-image').width();
	var logoheight = $('#site-logo img.logo-image').height();
	function resizeheaderlogo() {
		// 1.9.8: fix to check for page element
		if (typeof logoresize !== 'undefined') {
			if (logoresize == 'yes') {
				headerwidth = $('#header').width();
				ratio = headerwidth / startheaderwidth;
				newlogowidth = logowidth * ratio;
				newlogoheight = logoheight * ratio;

				// 1.9.6: smaller screen onload fix
				if (newlogowidth > headerwidth) {
					newlogowidth = headerwidth;
					newlogoheight = newlogowidth / logowidth * logoheight;
					/* console.log('x'+newlogowidth+' y'+newlogoheight); */
				}
				if (newlogowidth > logowidth) {
					newlogowidth = logowidth; newlogoheight = logoheight;
				}

				$('#site-logo img.logo-image').width(newlogowidth);
				$('#site-logo img.logo-image').height(newlogoheight);
			}
		}
 	}
 	// 1.9.6: onload resize fix
	resizeheaderlogo();

	// 2.0.9: maybe resize site title text
	// 2.1.2: replace site-desc span with div
	var calculatedratios = false; var titleratio; var descratio; var titlelratio; var desclratio;
	function resizetitletexts() {
		if (typeof sitetextresize !== 'undefined') {
			if (sitetextresize == 'yes') {

				if (calculatedratios == false) {
					console.log('Max Width: '+maxwidth);
					$('#header').css('width', maxwidth+'px !important');
					$('#header').css('padding','0px !important').css('margin','0px !important');
					titlesize = $('#site-title-text a').css('font-size').replace('px', '');
					descsize = $('#site-description div').css('font-size').replace('px', '');
					titlelh = $('#site-title-text a').css('line-height').replace('px','');
					desclh = $('#site-description div').css('line-height').replace('px','');
					$('#header').css('width', '').css('padding', '').css('margin', '');

					titleratio = titlesize / maxwidth;
					titlelratio = titlelh / maxwidth;
					descratio = descsize / maxwidth;
					desclratio = desclh / maxwidth;
					calculatedratios = true;
				}

				headerwidth = $('#header').width();
				resizeratio = headerwidth / maxwidth;
				// console.log('Resize Ratio: '+resizeratio);
				newtitlesize = headerwidth * titleratio;
				newtitlelh = headerwidth * titlelratio;
				newdescsize = headerwidth * descratio;
				newdesclh = headerwidth * desclratio;
				if (newtitlesize > titlesize) {newtitlesize = titlesize;}
				if (newtitlelh > titlelh) {newtitlelh = titlelh;}
				if (newdescsize > descsize) {newdescsize = descsize;}
				if (newdesclh > desclh) {newdesclh = desclh;}
				newtitlesize += 'px'; newtitlelh += 'px'; newdescsize += 'px'; newdesclh += 'px';
				$('#site-title-text a').css('font-size', newtitlesize).css('line-height', newtitlelh);
				$('#site-description div').css('font-size', newdescsize).css('line-height', newdesclh);
				// console.log('New Title Size: '+newtitlesize+' New Title Line Height: '+newtitlelh);
				// console.log('New Desc Size: '+newdescsize+' New Desc Line Height: '+newdesclh);
			}
		}
	}
	resizetitletexts();

	// 2.0.9: maybe resize header height with background
	var headerratio = null;
	function resizeheader() {
		if (typeof headerresize !== 'undefined') {
			if (headerresize == 'yes') {
				if (headerratio == null) {
					startwidth = $('#header').width();
					$('#header').css('width', maxwidth+'px !important');
					headerheight = $('#header').height();
					headerratio = headerheight / maxwidth;
					console.log('Header Ratio: '+headerratio);
					$('#header').css('width', startwidth+'px');
				}
				headerwidth = $('#header').width();
				newheaderheight = headerwidth * headerratio;
				console.log('New Header Height: '+newheaderheight);
				$('#header').css('height', newheaderheight);
			}
		}
	}
	resizeheader();


	/* Debounce Delay Callback Function */
	// ref: http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed
	var resizeDebounce = (function () {
		var timers = {};
		return function (callback, ms, uniqueId) {
			if (!uniqueId) {uniqueId = "nonuniqueid";}
			if (timers[uniqueId]) {clearTimeout (timers[uniqueId]);}
			timers[uniqueId] = setTimeout(callback, ms);
		};
	})();

	/* Trigger Functions on Window Resize (with debounce) */
	$(window).resize(function () {
		resizeDebounce(function(){
			// check mobile buttons on resize
			checkmobilebuttons();

			// maybe resize header logo
			resizeheaderlogo();

			// maybe resize site text
			resizetitletexts();

			// 1.9.9: match heights as may have changed
			matchheights();

			// 1.9.9: recheck sticky kit elements
			stickyelements();

		}, 750, "themejavascript");
	});

});