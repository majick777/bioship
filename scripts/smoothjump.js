/* http://css-tricks.com/snippets/jquery/smooth-scrolling/ */

jQuery(function($) {
  $('a[href*=#]:not([href=#])').click(function() {
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
});