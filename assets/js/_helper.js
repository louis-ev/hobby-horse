function active_Translation( $lang_list_a ) {
	var link_lang = $lang_list_a.data("lang");
	var link_lang_full = $lang_list_a.data("langfull");
	var $this_chapter = $lang_list_a.closest(".chapter");

	$this_chapter.find(".lang-list a").each( function() {
		$(this).removeClass("active");
		var link_lang = $(this).data("lang");
		$(this).find("li abbr").text( link_lang );
	});

	$lang_list_a.addClass("active");
	$lang_list_a.find("li abbr").text( link_lang_full );

	$this_chapter.find("article").removeClass("active");
	$this_chapter.find("article[data-lang=" + link_lang + "]").addClass("active");

	console.log("mouseover lang : " + link_lang + " article-header : " + $this_chapter.find("article[data-lang=" + link_lang + "] .entry-title").text() );



}

var gotoByScroll = function(section, margintop, callback) {
	if ($(window).width() >= 992) {
		var offsetTopPx = section.offset().top - margintop;
		$('#viewport').scrollTo(
			section,
			400,
			{ offset: -margintop },
			{ easing: 'easeInOutQuint' }
		);
	} else {
		$('#viewport').animate({
			scrollTop: section.offset().top
		}, 0, callback );
	}
};


$.extend($.easing, {
	easeInOutQuint: function(x, t, b, c, d) {
		if ((t /= d / 2) < 1) { return c / 2 * t * t * t * t * t + b; }
		return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
	}
});

/*
 * navbar
 */

function navbar_events() {

	$(".banner").on({
		mouseover: function(e) {

			var $target = $(e.target);

			//console.log( $target.parents(".sommaire").length );


		},
		mouseleave: function(e) {

/*
			var $target = $(e.target);

			$(".navbar-container").removeClass("is-active");
			$("body").removeClass("navbar-active");
			if ( $target.parents(".sommaire").length > 0 ) {
				$(".sommaire").removeClass("is-active");
			}
*/

		},

		click: function (e) {


		},

	});

	$(".sommaire").on({
		click:function(e) {

			console.log(  $(e.target) );

			if ( $(e.target).hasClass("sommaire--boutton") ) {

				if ( $(this).hasClass("is-active") ) {

					$(this).removeClass("is-active");
					$(".navbar-container").removeClass("is-active");
					$("body").removeClass("navbar-active");

					$("#viewport").transition({
						x: '0px'
					});
					//$("body").removeClass("is-zoomedout");

				} else {

					$(this).addClass("is-active");
					$(".navbar-container").addClass("is-active");
					$("body").addClass("navbar-active");

					$("#viewport").transition({
						x: '400px'
					});
					//$("body").addClass("is-zoomedout");
				}

			} else {

				chapter = $(e.target).data("chapter");
				part = $(e.target).data("part");
				lang = $(e.target).data("lang");

				console.log( "SOMMAIRE : chapter = " + chapter + " part = " + part + " lang = " + lang );

				if ( chapter !== undefined && part !== undefined ) {
					console.log("got chapter and part");

					var $thisTitle = $(e.target);

					var $thisTableau = $(".tableau-cont[data-chapter=" + chapter + "][data-part=" + part + "]");
					var $thisLang = $thisTableau.find(".lang-list>a[data-lang=" + lang + "]");

					active_Translation( $thisLang );

					// aller à cet endroit sur la map
					gotoByScroll( $thisTableau, 40, function() {

					});

				}

			}
		},
	});

	$(".wwhww-items").on({
		click: function(e) {

			if ( $(this).hasClass("is-active") ) {

				$(this).removeClass("is-active");
				$("body").removeClass("navbar-active");

			} else {

				$(".wwhww-items").removeClass("is-active");
				$(this).addClass("is-active");
				$("body").addClass("navbar-active");

			}

		},
	});
}

