$.delegate($('#chart div svg'), "svg_loaded", function(e){
	var s = document.createElement("script");
	s.type = "image/svg+xml";
	s.text = $('#chart div svg').text();
	$('head').append(s);
});
