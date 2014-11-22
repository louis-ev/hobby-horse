function active_Translation( $lang_list_a ) {
	var link_lang = $lang_list_a.data("lang");
	var link_lang_full = $lang_list_a.data("langfull");
	var $this_chapter = $lang_list_a.closest(".tableau-cont");

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

function addCategoryPoint(thisPath, endPoint, posX, posY) {
/*
	var posX = $("#viewport").scrollLeft() + e.pageX;
	var posY = $("#viewport").scrollTop() + e.pageY;
*/

	thisPath.add(new paper.Point( posX, posY));
	// Remove old endpoint
/*
	if (endPoint) {
	  endPoint.remove();
	}
	endPoint = new paper.Path.Circle({ center: new paper.Point( posX, posY), radius: 1.3, fillColor: thisPath.strokeColor });
*/
	thisPath.smooth();
	paper.view.draw();

	console.log( "posX : " + posX );
	console.log( "posY : " + posY );

}

var gotoByScroll = function(section, margintop, callback) {
	var offsetTopPx = section.offset().top - margintop;
	$('#viewport').scrollTo(
		section,
		400,
		{ offset: -margintop },
		{ easing: 'easeInOutQuint' }
	);
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

function cssGridCreator() {

	$("<div id='gridSystem'></div>").appendTo("#base");

	for ( i=0; i < 8; i += 1 ) {

		var newDivision = $("<div class='division'></div>");

		for ( j=0; j < 8; j += 1 ) {

			var subdivision = $("<div class='subdivision'></div>").appendTo( newDivision );

			for ( k=0; k < 48; k += 1 ) {
				subdivision.append("<div class='horizontalDivision' data-countV='" + ((i*8) + j) + "' data-countH='" + k + "'></div>");
			}


		}

		newDivision.appendTo( $("#gridSystem") );

	}

	$("#gridSystem .subdivision").each( function() {
	});

}


// paper.js
function drawLinks() {
  // Get a reference to the canvas object
  $("#base").append('<canvas id="links"></canvas>');

  var canvas = document.getElementById('links');
  // Create an empty project and a view for the canvas:
  paper.setup(canvas);

	if ( $(".tableau-cont[data-categories]").length > 0 ) {

		// faire un tableau de tous les data-categories
		var dataList = $(".tableau-cont[data-categories]").map(function() {
		    return $(this).data("categories");
		}).get();

		//console.log( dataList.join("X") );

		var uniqueCategories = [];
		$.each(dataList, function(i, el){

		    var categoriesTemp = el.trim().split(" ");
		    $.each(categoriesTemp, function(i, el){
			    if($.inArray(el, uniqueCategories) === -1 && el !== "non-classe" && el !== "" ) {
				    uniqueCategories.push(el);
			    }
				});
		});

		console.log( "uniqueCategories : " + uniqueCategories.join("|") );

		// pour récupérer tous les tableaux taggés arts-visuels
		// $(".tableau-cont[data-categories*='arts-visuels']");

		// uniqueCategories contient toutes les categories du terrain donc.
		// on peut maintenant
		// 1. récupérer tous les tableau-cont qui ont chaque categorie,
		// 2. prendre leur coordonnées
		// 3. tracer des lignes entre
		$.each(uniqueCategories, function( index, value) {

			var catProjects = $(".tableau-cont[data-categories*=" + value + "]");
			console.log( "Tableau " + value + " : " + catProjects.length );
			// pour tous les projets qui ont la classe
			catProjects.each( function(i) {
				$thisProject = $(this);

				catProjects.slice(i).not($thisProject).each( function(i) {
					$thatProject = $(this);

					var path = new paper.Path();
				  // Stroke details
				  // path.strokeColor = 'rgb(220,220,220)';
				  path.strokeColor = '#888';
				  path.strokeWidth = 1;
				  // path.fullySelected = true;
				  // path.dashArray = [2, 4];

					path.add(new paper.Point( $thisProject.offset().left, $thisProject.offset().top) );
					path.add(new paper.Point( ($thisProject.offset().left + $thatProject.offset().left)/2 - 10, ($thisProject.offset().top + $thatProject.offset().top)/2 + 10 ) );
					path.add(new paper.Point( $thatProject.offset().left, $thatProject.offset().top));
					path.closed = false;

					path.smooth();
					paper.view.draw();


				});

			});
		});

  }



  $(window).on('click', function(e) {
    path.fullySelected = false;

    var posX = $("#viewport").scrollLeft() + e.pageX;
    var posY = $("#viewport").scrollTop() + e.pageY;

    path.add(new paper.Point( posX, posY));
    // Remove old endpoint
    if (endPoint) {
	    endPoint.remove();
    }
    endPoint = new paper.Path.Circle({ center: new paper.Point( posX, posY), radius: 1.3, fillColor: path.strokeColor });
    path.smooth();
    paper.view.draw();

    console.log( "e.pageX : " + e.pageX );
    console.log( "posX : " + posX );
    console.log( "e.pageX : " + e.pageY );
    console.log( "posY : " + posY );

  });
  //
/*
  $('body').on('keydown', function(e) {
    if ((e.keyCode == 97 || e.keyCode == 65) && (e.metaKey || e.ctrlKey)) {
      e.preventDefault();
      if (!path.fullySelected) path.fullySelected = true;
      else path.fullySelected = false;
      paper.view.draw();
    } else if ((e.keyCode == 8 || e.keyCode == 46) && path.fullySelected) {
      e.preventDefault();
      path.segments = [];
      endPoint.remove();
      paper.view.draw();
      endPoint = false;
      localStorage.setItem('points', JSON.stringify([]));
      return false;
    }
  });
*/
  // Resize canvas
/*
  $(window).on('resize', function(e) {
    // Resize the canvas to the body's height
    paper.view.viewSize = new paper.Size(window.innerWidth, $(document).height());
    paper.view.draw();
  }).resize();
*/
}

