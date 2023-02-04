/* =========================== */
/* === BioShip Init Script === */
/* =========================== */

/* Mobile Button Functions */
/* ----------------------- */
// 1.8.0: use jQuery instead of plain javascript
// 2.1.3: prefix show / hide functions
function bioship_showmainmenu() {jQuery(function($) {
	$('#mainmenushow').hide(); $('#mainmenuhide').css('display','inline-block'); 
	$('body').addClass('has-mobile-menu');
	$('#navigation').addClass('mobile-menu'); 
	$('#navigation #primarymenu').show();
}); }
function bioship_hidemainmenu() {jQuery(function($) {
	$('#mainmenuhide').hide(); $('#mainmenushow').css('display','inline-block');
	$('body').removeClass('has-mobile-menu');
	$('#navigation').removeClass('mobile-menu'); 
	$('#navigation #primarymenu').hide();
}); }

function bioship_showsidebar() {jQuery(function($) {
	if (document.getElementById('subsidebarshow')) {bioship_hidesubsidebar();}
	$('#sidebarshow').css('display','none'); $('#sidebarshowsmall').css('display','none');
	$('#sidebarhide').css('display','block'); $('#sidebarhidesmall').css('display','block');
	$('#sidebar').css('display','block').css('width','100%');
}); }
function bioship_hidesidebar() {jQuery(function($) {
	$('#sidebarhide').css('display','none'); $('#sidebarhidesmall').css('display','none');
	$('#sidebarshow').css('display','block'); $('#sidebarshowsmall').css('display','block');
	$('#sidebar').css('display','none').css('width','');
}); }

function bioship_showsubsidebar() {jQuery(function($) {
	if (document.getElementById('sidebarshow')) {bioship_hidesidebar();}
	$('#subsidebarshow').css('display','none'); $('#subsidebarshowsmall').css('display','none');
	$('#subsidebarhide').css('display','block'); $('#subsidebarhidesmall').css('display','block');
	$('#subsidebar').css('display','block').css('width','100%');
}); }
function bioship_hidesubsidebar() {jQuery(function($) {
	$('#subsidebarhide').css('display','none'); $('#subsidebarhidesmall').css('display','none');
	$('#subsidebarshow').css('display','block'); $('#subsidebarshowsmall').css('display','block');
	$('#subsidebar').css('display','none').css('width','');
}); }


/* Scroll to Top */
/* ------------- */
// 1.8.5: smooth scroll to top method
function bioship_scrolltotop() {jQuery('html,body').animate({scrollTop: 0}, 1000);}

/* Smooth Scrolling */
/* ---------------- */
/* http://css-tricks.com/snippets/jquery/smooth-scrolling/ */
/* 1.8.0: add selector quotes for jquery 1.12 fix (WP 4.5)*/
function bioship_smoothscrolling() {
	jQuery('a[href*="#"]:not([href="#"])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = jQuery(this.hash);
			target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				var scrollto = target.offset().top + 150;
				jQuery('html,body').animate({scrollTop: scrollto}, 1000);
				return false;
			}
		}
	});
}


/* Sticky Kit Elements */
/* ------------------- */
// 1.9.9: revamped sticky kit function
// 2.0.9: use element array instead of input field
function bioship_stickyelements(elements) {
	for (i in elements) {
		$element = jQuery(elements[i]);
		if ($element.is(':visible')) {
			$element.stick_in_parent().on('sticky_kit:stick', function(e) {
				/* display glitch bypass: trim 1px on stick */
				parentwidth = jQuery(e.target).parent().width();
				jQuery(e.target).parent().width(parentwidth-1+'px');
			});
		} else {$element.trigger('sticky_kit:detach');}
	}
}

/* FitVids Elements */
/* ---------------- */
function bioship_fitvids(elements) {
	for (i in elements) {jQuery(elements[i]).fitVids();}
}


/* ---------------- */
/* Loader Functions */
/* ---------------- */
// 2.1.3: separated from document ready triggers
function bioship_matchheights() {jQuery('.matchheight').matchHeight();}
function bioship_modernizr() {jQuery(document).Modernizr();}
function bioship_foundation() {jQuery(document).foundation();}


// var startheaderwidth; var startheaderheight;
// var logowidth; var logoheight; var calculatedratios;
// var titleratio; var descratio; var titlelratio; var desclratio;

/* ---------------- */
/* Resize Functions */
/* ---------------- */

// --- Logo Resizing ---
// 1.8.5: maybe resize header logo
function bioship_resizeheaderlogo() {
	headerwidth = jQuery('#header').width();
	ratio = headerwidth / bioship.startheaderwidth;
	newlogowidth = bioship.logowidth * ratio;
	newlogoheight = bioship.logoheight * ratio;

	// 1.9.6: smaller screen onload fix
	if (newlogowidth > headerwidth) {
		newlogowidth = headerwidth;
		newlogoheight = newlogowidth / bioship.logowidth * bioship.logoheight;
		/* console.log('x'+newlogowidth+' y'+newlogoheight); */
	}
	if (newlogowidth > bioship.logowidth) {
		newlogowidth = bioship.logowidth; newlogoheight = bioship.logoheight;
	}

	jQuery('#site-logo img.logo-image').width(newlogowidth);
	jQuery('#site-logo img.logo-image').height(newlogoheight);
}

/* Resize Title Texts */
/* ------------------ */
function bioship_resizetitletexts() {

	if (bioship.calculatedratios == false) {
		/* console.log('Max Width: '+bioship.maxwidth); */
		jQuery('#header').css('width', bioship.maxwidth+'px !important');
		jQuery('#header').css('padding','0px !important').css('margin','0px !important');
		titlesize = jQuery('#site-title-text a').css('font-size').replace('px', '');
		descsize = jQuery('#site-description div').css('font-size').replace('px', '');
		titlelh = jQuery('#site-title-text a').css('line-height').replace('px','');
		desclh = jQuery('#site-description div').css('line-height').replace('px','');
		jQuery('#header').css('width', '').css('padding', '').css('margin', '');

		bioship.titleratio = titlesize / bioship.maxwidth;
		bioship.titlelratio = titlelh / bioship.maxwidth;
		bioship.descratio = descsize / bioship.maxwidth;
		bioship.desclratio = desclh / bioship.maxwidth;
		bioship.calculatedratios = true;
	}

	headerwidth = jQuery('#header').width();
	resizeratio = headerwidth / bioship.maxwidth;
	/* console.log('Resize Ratio: '+resizeratio); */
	newtitlesize = headerwidth * bioship.titleratio;
	newtitlelh = headerwidth * bioship.titlelratio;
	newdescsize = headerwidth * bioship.descratio;
	newdesclh = headerwidth * bioship.desclratio;
	if (newtitlesize > titlesize) {newtitlesize = titlesize;}
	if (newtitlelh > titlelh) {newtitlelh = titlelh;}
	if (newdescsize > descsize) {newdescsize = descsize;}
	if (newdesclh > desclh) {newdesclh = desclh;}
	newtitlesize += 'px'; newtitlelh += 'px'; newdescsize += 'px'; newdesclh += 'px';
	jQuery('#site-title-text a').css('font-size', newtitlesize).css('line-height', newtitlelh);
	jQuery('#site-description div').css('font-size', newdescsize).css('line-height', newdesclh);
	/* console.log('New Title Size: '+newtitlesize+' New Title Line Height: '+newtitlelh); */
	/* console.log('New Desc Size: '+newdescsize+' New Desc Line Height: '+newdesclh); */
}

// --- Header Resizing ---
// 2.0.9: maybe resize header height with background
var headerratio = null;
function bioship_resizeheader() {
	if (headerratio == null) {
		startwidth = $('#header').width();
		jQuery('#header').css('width', bioship.maxwidth+'px !important');
		headerheight = $('#header').height();
		headerratio = headerheight / bioship.maxwidth;
		/* console.log('Header Ratio: '+headerratio); */
		jQuery('#header').css('width', startwidth+'px');
	}
	headerwidth = jQuery('#header').width();
	newheaderheight = headerwidth * headerratio;
	/* console.log('New Header Height: '+newheaderheight); */
	jQuery('#header').css('height', newheaderheight);
}

/* Check Mobile Buttons */
/* -------------------- */
// 2.1.3: separate function from trigger
function bioship_checkmobilebuttons() {

	screenwidth = jQuery(window).width();
	/* console.log('Screen Width: '+screenwidth); */

	// --- show main menu at 480 ---
	// 2.2.0: fix to primary menu targeting
	if (document.getElementById('mainmenubutton')) {
		if (screenwidth > 479) {
			jQuery('#mainmenubutton').hide();
			bioship_hidemainmenu(); // removes mobile menu
			setTimeout(function() {jQuery('#navigation #primarymenu').show();}, 100);
		} else {
			if (!jQuery('#navigation').hasClass('mobile-menu')) {
				jQuery('#navigation #primarymenu').hide();
			}
			jQuery('#mainmenubutton').show();
		}
	}

	// --- show sidebar at 640 ---
	if (document.getElementById('sidebarshow')) {
		if (document.getElementById('sidebarshow').style.display == 'none') {
			// hide == restore == show in this case
			if (screenwidth > 639) {bioship_hidesidebar();}
		}
	}

	// --- show in subsidebar at 768 ---
	if (document.getElementById('subsidebarshow')) {
		if (document.getElementById('subsidebarshow').style.display == 'none') {
			// hide == restore == show in this case
			if (screenwidth > 767) {bioship_hidesubsidebar();}
		}
	}
}

/* --- Sticky Navigation Bar --- */
/* 2.1.5: added sticky navbar option */
if ( (typeof bioship.stickynavbar !== 'undefined') 
  || (typeof bioship.stickylogo !== 'undefined') ) {
	window.onscroll = function() {bioship_stickynavbar()};
}
function bioship_stickynavbar() {
	if (typeof bioship.stickynavbar !== 'undefined') {
		if (window.pageYOffset == 0) {
			jQuery('#navigation').removeClass('sticky-navbar');
			if (typeof bioship.stickylogo !== 'undefined') {
				jQuery('#site-logo').removeClass('sticky-logo');
			}
			return;
		}
		navbar = document.getElementById('navigation');
		stickytop = navbar.offsetTop;
		if (window.pageYOffset >= stickytop) {
			jQuery('#navigation').addClass('sticky-navbar');
			if (typeof bioship.stickylogo !== 'undefined') {
				jQuery('#site-logo').addClass('sticky-logo');
			}
		} else {
			jQuery('#navigation').removeClass('sticky-navbar');
			if (typeof bioship.stickylogo !== 'undefined') {
				jQuery('#site-logo').removeClass('sticky-logo');
			}
		}
	} else {
		if (typeof bioship.stickylogo !== 'undefined') {
			if (window.pageYOffset == 0) {
				jQuery('#site-logo').removeClass('sticky-logo'); return;
			}
			sitelogo = document.getElementById('site-logo');
			stickytop = sitelogo.offsetTop;
			if (window.pageYOffset >= stickytop) {
				jQuery('#site-logo').addClass('sticky-logo');
			} else {jQuery('#site-logo').removeClass('sticky-logo');}
		}
	}
}

// --- Debounce Delay Callback ---
// ref: http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed
// 2.1.3: prefix debounce function
var bioship_resizedebounce = (function () {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) {uniqueId = "nonuniqueid";}
		if (timers[uniqueId]) {clearTimeout (timers[uniqueId]);}
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();


/* ------------------- */
/* Page Load Functions */
/* ------------------- */
jQuery(document).ready(function($) {

	// 2.2.0: added load delay for navigation glitch bypass
	setTimeout(function() {

		// --- Load Superfish Menu ---
		// 1.8.5: added check if superfish function exists
		$(function(){
			if (typeof superfish === 'function') {
				activclasses = 'li.current_page_item,li.current_page_parent,li.current_page_ancestor,li.current-cat,li.current-cat-parent,li.current-menu-item';
				$('#navigation ul.menu').find(activeclasses).addClass('active').end().superfish({autoArrows: true});
			}
		});

		// --- valid XHTML method of target_blank ---
		$(function(){$('a[rel*="external"]').click( function() {window.open(this.href); return false;}); });

		// --- Style Tags ---
		$(function(){$('p.tags a').wrap('<span class="st_tag" />');});

		// --- Focus on search form on 404 pages ---
		$(function(){$("body.error404 #content #s").focus();});

		// --- Smooth Hash Link Scrolling ---
		// 2.0.9: use variable check instead of input check
		if (typeof bioship.smoothscrolling !== 'undefined') {
			if (bioship.smoothscrolling == 'yes') {bioship_smoothscrolling();}
		}

		/* Sticky Kit */
		/* ---------- */
		// 1.5.0: maybe Trigger Sticky Page Elements
		// 2.1.3: trigger separately and prefix function
		if (typeof bioship.stickyelements !== 'undefined') {
			bioship_stickyelements(bioship.stickyelements);
		}

		/* FitVids */
		/* ------- */
		// 1.5.0: maybe Trigger FitVids Elements
		// 1.9.9: optimized fitvids array code
		// 2.0.9: use element array instead of input field
		// 2.2.0: fix to missing object prefix
		if (typeof bioship.fitvidselements !== 'undefined') {
			bioship_fitvids(bioship.fitvidselements);
		}

		/* Modernizr */
		/* --------- */
		// 2.0.9: maybe initialize Modernizr
		if (typeof bioship.loadmodernizr !== 'undefined') {
			if (bioship.loadmodernizr == 'yes') {bioship_modernizr();}
		}

		/* Foundation */
		/* ---------- */
		if (typeof loadfoundation !== 'undefined') {
			if (bioship.loadfoundation == 'yes') {bioship_foundation();}
		}

		/* MatchHeight */
		/* ----------- */
		// 1.9.9: maybe run jquery matchHeight
		// 2.1.3: separate and prefix function
		if (typeof loadmatchheights !== 'undefined') {
			if (bioship.loadmatchheights == 'yes') {bioship_matchheights();}
		}

		/* Check Mobile Buttons */
		/* -------------------- */
		bioship_checkmobilebuttons();

		/* Dynamic Resizing */
		/* ---------------- */

		// --- set start values ---
		bioship.startheaderwidth = jQuery('#header').width();
		bioship.startheaderheight = jQuery('#header').height();
		bioship.logowidth = jQuery('#site-logo img.logo-image').width();
		bioship.logoheight = jQuery('#site-logo img.logo-image').height();
		bioship.calculatedratios = false;

		// --- Logo Resizing ---
		// 1.9.6: onload resize fix
		// 2.1.3: separate and prefix function
		// 1.9.8: fix to check for page element
		if (typeof bioship.logoresize !== 'undefined') {
			if (bioship.logoresize == 'yes') {bioship_resizeheaderlogo();}
		}

		// --- Title Resizing ---
		// 2.0.9: maybe resize site title text
		// 2.1.2: replace site-desc span with div
		// 2.1.3: separate and prefix function
		if (typeof bioship.sitetextresize !== 'undefined') {
			if (bioship.sitetextresize == 'yes') {bioship_resizetitletexts();}
		}

		// --- Header Resizing ---
		// 2.1.3: separate and prefix function
		if (typeof bioship.headerresize !== 'undefined') {
			if (bioship.headerresize == 'yes') {bioship_resizeheader();}
		}

		/* console.log('a: '+jQuery('#navigation').css('display'));
		setTimeout(function() {console.log('a: '+jQuery('#navigation').css('display'));}, 100); */
		
	}, 100);

	/* On Window Resize */
	/* ---------------- */
	$(window).resize(function () {

		// --- with resize debounce ---
		bioship_resizedebounce(function(){

			// --- recheck sticky navbar scroll position ---
			if ( (typeof bioship.stickynavbar !== 'undefined') 
			  || (typeof bioship.stickylogo !== 'undefined') ) {
				bioship_stickynavbar();
			}

			// --- check mobile buttons on resize ---
			bioship_checkmobilebuttons();

			// --- maybe resize header logo ---
			if (typeof bioship.logoresize !== 'undefined') {
				if (bioship.logoresize == 'yes') {bioship_resizeheaderlogo();}
			}

			// --- maybe resize site text ---
			if (typeof bioship.sitetextresize !== 'undefined') {
				if (bioship.sitetextresize == 'yes') {bioship_resizetitletexts();}
			}

			// --- maybe resize header ---
			if (typeof bioship.headerresize !== 'undefined') {
				if (bioship.headerresize == 'yes') {bioship_resizeheader();}
			}

			// --- retrigger match heights ---
			// 1.9.9: match heights as may have changed
			// 2.1.3: check if loaded before retrigger
			if (typeof bioship.loadmatchheights !== 'undefined') {
				if (bioship.loadmatchheights == 'yes') {bioship_matchheights();}
			}

			// --- retrigger sticky elements ---
			// 1.9.9: recheck sticky kit elements
			// 2.1.3: check and pass elements to function
			if (typeof bioship.stickyelements !== 'undefined') {
				bioship_stickyelements(bioship.stickyelements);
			}

		}, 750, "themejavascript");
	});

});