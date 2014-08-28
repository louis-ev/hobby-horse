/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

(function($) {

// Use this variable to set up the common and page specific functions. If you
// rename this variable, you will also need to rename the namespace below.
var Roots = {
  // All pages
  common: {
    init: function() {

		$(".chapter").each(function() {
			$this = $(this);

			var largest_article_height = 0;

			$this.find("article").each( function() {
				$that = $(this);
				lang = $that.data("lang");
				$this.find(".lang-list a[data-lang=" + lang + "]").addClass("exists");

				if ( $that.height() > largest_article_height ) {
					largest_article_height = $that.height();
				}
			});

			console.log( '$this.find("> header").height() : ' + $this.find("> header").height() + ' $this.find("> lang-list").height() : ' + $this.find("> lang-list").height() + ' largest_article_height : ' + largest_article_height);
			$this.height( $this.find("> header").height() + $this.find("> lang-list").height() + largest_article_height );

		});

		$(".chapter .lang-list a.exists").on("mouseover", function() {
			active_Translation ( $(this) );
		});

		$(".chapter .lang-list").each(function() {
			active_Translation( $(this).find("a.exists").eq(0) );
		});

    }
  },
  // Home page
  home: {
    init: function() {
      // JavaScript to be fired on the home page
    }
  },

  page_template_template_article_langue_head_php: {
    init: function() {


    }
  }
};

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Roots;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

$(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
