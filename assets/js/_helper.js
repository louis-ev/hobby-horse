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