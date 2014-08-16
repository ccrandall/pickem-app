<!DOCTYPE html>
<html>
<head>
<title>YQL Test</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet" type="text/css"> 
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
	<script src="http://yui.yahooapis.com/3.8.0/build/yui/yui-min.js"></script>  

	<script>
	
	var get_point_spread = function() {
		YUI().use('node', 'event', 'yql', function(Y) {
		    var spread_url = "http://sports.yahoo.com/nfl/odds/";
		    var yql_query = "select * from html where url='" + spread_url + "'";
			yql_query += " and xpath='//div[@id=\"odds\"]' and compat=\"html5\" ";
			
			odds_one = [];
			odds_two = [];
			odds_object = [];
			
		    Y.YQL(yql_query, function(response) {
		    	if(response.query.results){
					var spread_list = response.query.results.div.div[1].table;
					//console.log(response);
					spread_list.forEach(function(node, index) {
						//console.log(node);
				 	   	//first table
						if (index == 0) {
							/*
							console.log(node.tbody.tr.td[0].p.span.a.content);
							console.log(node.tbody.tr.td[0].span.a.content);
							console.log(node.tbody.tr.td[1].div[0].span.content);
							console.log(node.tbody.tr.td[1].div[0].span.class);
							*/
							if (node.tbody.tr.td[2].div[0].span.class == 'bottom-line') { // favorite has class bottom-line
								//console.log('favorite: ' + node.tbody.tr.td[0].p.span.a.content + ' spread: ' + node.tbody.tr.td[1].div[0].span.content);
								odds_one[node.tbody.tr.td[0].span[1].a.content] = node.tbody.tr.td[2].div[0].span.content;
							} else { // favoriate has class top-line
								//console.log('favorite: ' + node.tbody.tr.td[0].span.a.content + ' spread: ' + node.tbody.tr.td[1].div[0].span.content);
								//odds_one[node.tbody.tr.td[0].span.a.content] = node.tbody.tr.td[2].div[0].span.content;
							}
						}
						for (var i = 0; i < node.tbody.tr.length; i++) {
							if (node.tbody.tr.length == 0) {						 	   	
							} else {
								/*
								console.log(node.tbody.tr[i].td[0].p.span.a.content);
			  					console.log(node.tbody.tr[i].td[0].span.a.content);
			  					console.log(node.tbody.tr[i].td[1].div[0].span.content);
			  					console.log(node.tbody.tr[i].td[1].div[0].span.class); */
								if (node.tbody.tr[i].td[2].div[0].span.class == 'bottom-line') { // favorite has class bottom-line
									//console.log('favorite: ' + node.tbody.tr[i].td[0].p.span.a.content + ' spread: ' + node.tbody.tr[i].td[1].div[0].span.content);
									odds_two[node.tbody.tr[i].td[0].span[1].a.content] = node.tbody.tr[i].td[2].div[0].span.content;
								} else { // favoriate has class top-line
									//console.log('favorite: ' + node.tbody.tr[i].td[0].span.a.content + ' spread: ' + node.tbody.tr[i].td[1].div[0].span.content);
									//odds_two[node.tbody.tr[i].td[0].span.a.content] = node.tbody.tr[i].td[2].div[0].span.content;
								}								
							}
						}
					});	
					odds_object = $.extend(odds_one, odds_two);
					console.log(odds_object["Seattle Seahawks"]);
				}
				//console.log(response);
			});
		});
		// return odds_object;
	}
	setTimeout(function() {
		get_point_spread();
	}, 2000); 
	</script>
</head>
<body>
	<div id="results"></div>
</body>
</html>
		    Y.YQL(yql_query, function(response) {
		    	if(response.query.results){
					var spread_list = response.query.results.div.div[1].table;
					
					odds_one = [];
					odds_two = [];
					odds_object = [];
					
					spread_list.forEach(function(node, index) {
						//console.log(node);
				 	   	//first table
						if (index == 0) {
							/*
							console.log(node.tbody.tr.td[0].p.span.a.content);
							console.log(node.tbody.tr.td[0].span.a.content);
							console.log(node.tbody.tr.td[1].div[0].span.content);
							console.log(node.tbody.tr.td[1].div[0].span.class);
							*/
							
							if (node.tbody.tr.td[2].div[0].span.class == 'bottom-line') { // favorite has class bottom-line
								//console.log('favorite: ' + node.tbody.tr.td[0].p.span.a.content + ' spread: ' + node.tbody.tr.td[1].div[0].span.content);
								var team_name = node.tbody.tr.td[0].span[1].a.content.toLowerCase().replace(/ /g, '-');
								var spread_value = node.tbody.tr.td[2].div[0].span.content;
								
								odds_one['"'+ team_name + '"'] = spread_value;
							} else { // favoriate has class top-line
								//console.log('favorite: ' + node.tbody.tr.td[0].span.a.content + ' spread: ' + node.tbody.tr.td[1].div[0].span.content);
								//var team_name = node.tbody.tr.td[0].span.a.content.toLowerCase().replace(/ /g, '-');
								//var spread_value = node.tbody.tr.td[1].div[0].span.content;
								
								//odds_one['"'+ team_name + '"'] = spread_value;
							}
						}
						for (var i = 0; i < node.tbody.tr.length; i++) {
							if (node.tbody.tr.length == 0) {						 	   	
							} else {
								/*
								console.log(node.tbody.tr[i].td[0].p.span.a.content);
			  					console.log(node.tbody.tr[i].td[0].span.a.content);
			  					console.log(node.tbody.tr[i].td[1].div[0].span.content);
			  					console.log(node.tbody.tr[i].td[1].div[0].span.class); */
								if (node.tbody.tr[i].td[2].div[0].span.class == 'bottom-line') { // favorite has class bottom-line
									//console.log('favorite: ' + node.tbody.tr[i].td[0].p.span.a.content + ' spread: ' + node.tbody.tr[i].td[1].div[0].span.content);
									var team_name = node.tbody.tr[i].td[0].span[1].a.content.toLowerCase().replace(/ /g, '-');
									var spread_value = node.tbody.tr[i].td[2].div[0].span.content;
								
									odds_two['"'+ team_name + '"'] = spread_value;
								} else { // favoriate has class top-line
									//console.log('favorite: ' + node.tbody.tr[i].td[0].span.a.content + ' spread: ' + node.tbody.tr[i].td[1].div[0].span.content);
									//var team_name = node.tbody.tr[i].td[0].span.a.content.toLowerCase().replace(/ /g, '-');
									//var spread_value = node.tbody.tr[i].td[1].div[0].span.content;
								
									//odds_two['"'+ team_name + '"'] = spread_value;
								}								
							}
						}
					});	
					odds_object = $.extend(odds_one, odds_two);
					$.each(odds_object, function(index, element) {
						var $this = $element;
						if ($this) {
							
						}
						//$('<span class="spread">'+ spread +'</span>').insertAfter(''+ team_check +'');
					});
					console.log(odds_object);
				}
			});