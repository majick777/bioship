
// mobile button functions
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
	$('#sidebar').css('display','block'); $('#sidebar').css('width','100%');
}); }
function hidesidebar() {jQuery(function($) {
	$('#sidebarhide').css('display','none'); $('#sidebarhidesmall').css('display','none');
	$('#sidebarshow').css('display','block'); $('#sidebarshowsmall').css('display','block');
	$('#sidebar').css('display','none'); $('#sidebar').css('width','');
}); }

function showsubsidebar() {jQuery(function($) {
	if (document.getElementById('sidebarshow')) {hidesidebar();}
	$('#subsidebarshow').css('display','none'); $('#subsidebarshowsmall').css('display','none');
	$('#subsidebarhide').css('display','block'); $('#subsidebarhidesmall').css('display','block');
	$('#subsidebar').css('display','block'); $('#subsidebar').css('width','100%');
}); }
function hidesubsidebar() {jQuery(function($) {
	$('#subsidebarhide').css('display','none'); $('#subsidebarhidesmall').css('display','none');
	$('#subsidebarshow').css('display','block'); $('#subsidebarshowsmall').css('display','block');
	$('#subsidebar').css('display','none'); $('#subsidebar').css('width','');
}); }

// 1.8.5: smooth scroll to top method
function scrolltotop() {jQuery('html,body').animate({scrollTop: 0}, 1000);}

// run after page loads
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

	// Smooth Hash LInk Scrolling
	/* http://css-tricks.com/snippets/jquery/smooth-scrolling/ */
	if (document.getElementById('smoothscrolling')) {
		if (document.getElementById('smoothscrolling').value == 'yes') {
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

	// 1.5.0: maybe Trigger Sticky Page Elements
	// 1.9.9: revamped sticky kit function
	function stickyelements() {
		if (document.getElementById('stickyelements')) {
			var stickyelementslist = document.getElementById('stickyelements').value;
			if (stickyelementslist != '') {
				var stickyelements = new Array();
				if (stickyelementslist.indexOf(',') == -1) {stickyelements[0] = stickyelementslist;}
				else {stickyelements = stickyelementslist.split(',');}

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
	}
	stickyelements();

	// 1.5.0: maybe Trigger FitVids Elements
	// 1.9.9: optimized fitvids array code
	if (document.getElementById('fitvidselements')) {
		var fitvidselementslist = document.getElementById('fitvidselements').value;
		if (fitvidselementslist != '') {
			var fitvidselements = new Array();
			if (fitvidselementslist.indexOf(',') == -1) {fitvidselements[0] = fitvidselementslist;}
			else {fitvidselements = fitvidselementslist.split(',');}
			for (i in fitvidselements) {$(fitvidselements[i]).fitVids();}
		}
	}

	// maybe initialize Foundation
	if (document.getElementById('foundation')) {
		if (document.getElementById('foundation').value == 'load') {$(document).foundation();}
	}

	// 1.9.9: maybe run jquery matchHeight
	function matchheights() {
		if (document.getElementById('matchheight')) {
			if (document.getElementById('matchheight').value == 'yes') {$('.matchheight').matchHeight();}
		}
	}
	matchheights();

	// check mobile buttons
	function checkmobilebuttons() {
		screenwidth = $(window).width();
		// console.log(screenwidth);

		// show menu at 480
		if (document.getElementById('mainmenushow')) {
			if (document.getElementById('mainmenushow').style.display == 'none') {
				// hide means (restore) show in this case
				if (screenwidth > 479) {hidemainmenu();}
			}
		}

		// show sidebar at 640
		if (document.getElementById('sidebarshow')) {
			if (document.getElementById('sidebarshow').style.display == 'none') {
				// hide means (restore) show in this case
				if (screenwidth > 639) {hidesidebar();}
			}
		}

		// show in subsidebar at 768
		if (document.getElementById('subsidebarshow')) {
			if (document.getElementById('subsidebarshow').style.display == 'none') {
				// hide means (restore) show in this case
				if (screenwidth > 767) {hidesubsidebar();}
			}
		}
	}
	checkmobilebuttons();

	// Window Resize Functions (with debounce)
	/* http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed */
	var resizeDebounce = (function () {
		var timers = {};
		return function (callback, ms, uniqueId) {
			if (!uniqueId) {uniqueId = "nonuniqueid";}
			if (timers[uniqueId]) {clearTimeout (timers[uniqueId]);}
			timers[uniqueId] = setTimeout(callback, ms);
		};
	})();

	// 1.8.5: maybe resize header logo
	var logowidth = $('#site-logo img.logo-image').width();
	var logoheight = $('#site-logo img.logo-image').height();
	var startheaderwidth = $('#header').width();
	function resizeheaderlogo() {
		// 1.9.8: fix to check for page element
		if (document.getElementById('logoresize')) {
			if (document.getElementById('logoresize').value == 'yes') {
			headerwidth = $('#header').width();
			ratio = headerwidth / startheaderwidth;
			newlogowidth = logowidth * ratio;
			newlogoheight = logoheight * ratio;

			// 1.9.6: smaller screen onload fix
			if (newlogowidth > headerwidth) {
				newlogowidth = headerwidth;
				newlogoheight = newlogowidth / logowidth * logoheight;
				console.log('x'+newlogowidth); console.log('x'+newlogoheight);
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

	$(window).resize(function () {
		resizeDebounce(function(){
			// check mobile buttons on resize
			checkmobilebuttons();

			// maybe resize header logo
			resizeheaderlogo();

			// 1.9.9: match heights as may have changed
			matchheights();

			// 1.9.9: recheck sticky kit elements
			stickyelements();

		}, 750, "themejavascript");
	});

});