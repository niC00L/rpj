$(document).ready(function(){
	$.ajaxPrefilter( function (options) {
			if (options.crossDomain && jQuery.support.cors) {
			var http = (window.location.protocol === 'http:' ? 'http:' : 'https:');
			options.url = http + '//cors-anywhere.herokuapp.com/' + options.url;
			//options.url = "http://cors.corsproxy.io/url=" + options.url;
		}
	});

	var update = function() {
		$.get("http://www.ticketportal.cz/jsws/PredstavenieMiesto.ashx?idp=-2147483145", function(response) {
			if((response.match(/\[1,0\]/g) || []).length !== 0) {
				$("#tickets").html('\
					<p id="num"><a href="http://www.ticketportal.cz/performancesvg.aspx?idp=-2147483145">' 
					+ (response.match(/\[1,0\]/g) || []).length + 
					'</a></p><p id="wor"><a href="http://www.ticketportal.cz/performancesvg.aspx?idp=-2147483145"> Dragon tickets left</a><br />\n\
					<a class="tiny" href="http://nicool.rocks/stuff/tickets/?lang=stuff&page=tickets">Do you want this on your page?</a></p>');			
			}	
			else {
				$("#tickets").html('\
					<p id="num"><a href="http://www.ticketportal.cz/performancesvg.aspx?idp=-2147483145"> Sold out</a><br />\n\
					<a class="tiny" href="http://nicool.rocks/stuff/tickets/?lang=stuff&page=tickets">Do you want this on your page?</a></p>');			
			}
		});
	};
	setInterval(function(){ update() }, 10 * 1000);
	update();
});