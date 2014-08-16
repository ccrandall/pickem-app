<!DOCTYPE html>
<html>
<head>
<title>YQL Test</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet" type="text/css"> 
    <!--<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css" />-->
	<link rel="stylesheet" href="theme/water/jquery.mobile-1.3.1.css">
	<link rel="stylesheet" href="css/style.css">
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.js"></script>
	<script src="http://yui.yahooapis.com/3.8.0/build/yui/yui-min.js"></script>  
    
    <script type="text/javascript">
	
	function onSuccess(data, status) {
        data = $.trim(data);
		$("#schedule_results").empty();
		$("#notification").empty().show().html(data);
    }

    function onError(data, status) {
        // handle an error
    }        
  
    $(document).ready(function() {
		$("#notification").hide();
        $(document).on("click", "#submit-2", function(){

            var formData = $("#pickem-set").serialize();
            $.ajax({
                type: "POST",
                url: "ajax.php",
                cache: false,
                data: formData,
                success: onSuccess,
                error: onError
            });
            return false;
        });
		week_array = new Array(
			null,
			new Date(2014,8,4),
			new Date(2014,8,11),
			new Date(2014,8,18),
			new Date(2014,8,25),
			new Date(2014,9,2),
			new Date(2014,9,9),
			new Date(2014,9,16),
			new Date(2014,9,23),
			new Date(2014,9,30),
			new Date(2014,10,6),
			new Date(2014,10,13),
			new Date(2014,10,20),
			new Date(2014,10,27),
			new Date(2014,11,4),
			new Date(2014,11,11),
			new Date(2014,11,18),
			new Date(2014,11,25)
		);
		current_spread_date = '';
		for (var i=1; i<week_array.length;i++) {
			if (week_array[i] > new Date() === false) {
				current_spread_date = week_array[i];
				break;
			}
		}
		current_spread_date = (current_spread_date == '') ? week_array[1] : current_spread_date;
		// console.log(current_spread_date);
	
		$(".pagination a").click(function(e) {
			$("#schedule_results").html('<img src="ajax-loader.gif">');
			$("#pickset-week").attr('value', $(this).attr('href'));
			current_spread_date = week_array[$(this).attr('href')]; // set spread date to week that's clicked
			console.log(current_spread_date);
			schedule_yui_query($(this).attr('href'));
			e.preventDefault();
		});
    });
	</script>
</head>
<body>
<div data-role="page">
    <div data-role="header">
        <h2>NFL Pickem</h2>
    </div>
    <div role="main" class="ui-content">
		<h3>Week: </h3>
	<div class="pagination" data-role="controlgroup" data-type="horizontal">
		<?
			$i = 1;
			while ($i <= 17) {
				//$active_class = ($i == 1) ? 'ui-btn-active' : '';
				echo '<a data-role="button" data-mini="true" class="" href="' . $i . '">'.$i.'</a>';
				if ($i == 9) {
					echo '</div><div class="pagination second" data-role="controlgroup" data-type="horizontal">';
				}
				$i++;
			}
		?>
	</div>
	<div id="schedule_results"><img src="img/ajax-loader.gif"></div>
	<div id="notification" class="success"><img src="img/ajax-loader-success.gif"></div>
	<script src="https://yui-s.yahooapis.com/3.8.0/build/yui/yui-min.js"></script>
	<script>

	var get_point_spread = function(team_name) {
		YUI().use('node', 'event', 'yql', function(Y) {
		    var spread_url = "http://sports.yahoo.com/nfl/odds/";
		    var yql_query = "select * from html where url='" + spread_url + "'";
			//yql_query += " and xpath='//div[@id=\"odds\"]' and compat=\"html5\"";
			yql_query += "and xpath='//table[@class=\"pointspread\"]/tbody/tr/td[contains(., \""+ team_name +"\")]/following-sibling::td[2]' and compat=\"html5\"";
		    Y.YQL(yql_query, function(response) {
		    	if(response.query.results){
					var spread_data = response.query.results.td.div[0];
					//console.log(spread_data);
					var home_team_spread = '';
					var away_team_spread = '';
					
					if (team_name.indexOf('Giants') >= 0 || team_name.indexOf('Jets') >= 0) {
						team_name = 'NY ' + team_name;
					}
					
					if (spread_data.span.class == 'bottom-line') {
						home_team_spread = spread_data.span.content;
						console.log('team: ' + team_name + home_team_spread);
						var home_team_spread_class = team_name.toLowerCase().replace(/ /g,'-').replace(/\./g,'');
						$("."+ home_team_spread_class +".home_spread").html(home_team_spread);
					} else {
						away_team_spread = spread_data.span.content;
						var away_team_spread_class = team_name.toLowerCase().replace(/ /g,'-').replace(/\./g,'');
						$("."+ away_team_spread_class +".away_spread").html(away_team_spread);
					}
				}
			});
		});
	}
	
	// Calls YQL Web service, parses results, and outputs results
	var schedule_yui_query = function(week) {
	  YUI().use(['node', 'event', 'yql'], function(Y) {
		  //Y.one("#get_stories").on('click',function() {
		    var schedule = '<div><form action="ajax.php" id="pickem-set" method="POST">';
		    // var story = Y.one('#story').get('value') || 'world';
		    var schedule_url = "http://sports.yahoo.com/nfl/scoreboard/?week="+ week +"&phase=2&season=2014";
		    var yql_query = "select * from html where url='" + schedule_url + "'";
		    // yql_query += " and xpath='//div[@class=\"content\"]//div[@class=\"txt\"]/p'";
			yql_query += " and xpath='//div[@id=\"mediasportsscoreboardgrandslam\"]' and compat=\"html5\" ";
			
		    Y.YQL(yql_query, function(response) {
		      if(response.query.results){
		        var no_schedule = response.query.results.length;
		        //console.log(response.query.results)
				/*
				schedule += "<h3 class="+ find_class +">" + response.query.results.div.h3 + " </h3>";
				*/
				var schedule_list = response.query.results.div.div.table.tbody.tr;
				//console.log(schedule_list);
				k = 1;
				schedule_list.forEach(function(node,index) {
					var i = 1;
					
					//console.log(node.class);
					var j = 0;
					if (node.class.indexOf('date') >= 0) {
						//console.log(node.th.h3);
						schedule += "<h3>" + node.th.h3 + "</h3>";
						i++;
					}

					if (node.class.indexOf('game') >= 0) {
						//console.log(node.td[1].span);
						//console.log(node.td[2].h4);
						//console.log(node.td[3].span);
						if (node.td[0].span[0] === undefined) {
							gametime = node.td[0].span.content;
						} else {
							gametime = node.td[0].span[0].content;
						}
						away_team = node.td[1].span[1].em;
						away_team_lower = node.td[1].span[1].em.toLowerCase().replace(/ /g, '-').replace(/\./g,'');
						away_team_class = node.td[1].class;
						home_team = node.td[3].span.em;
						home_team_lower = node.td[3].span.em.toLowerCase().replace(/ /g, '-').replace(/\./g,'');
						home_team_class = node.td[3].class;
											
						schedule += '<fieldset data-role="controlgroup"><legend>Game Time: '+ gametime +'</legend><input type="radio" name="game-'+ k +'" id="'+ away_team_lower +'" value="'+ away_team_lower +'"><label for="'+ away_team_lower +'"><span class="'+ away_team_class +'">'+ away_team +'</span> <span class="'+ away_team_lower +' away_spread"></span></label><input type="radio" name="game-'+ k +'" id="'+ home_team_lower +'" value="'+ home_team_lower +'"><label for="'+ home_team_lower +'"><span class="'+ home_team_class +'">'+ home_team +'</span> <span class="'+ home_team_lower +' home_spread"></span></label></fieldset>';				
						
						if (new Date(2014,8,4).getTime() == current_spread_date.getTime() || current_spread_date < new Date().getTime() == true) { // don't run get_point_spread unless it's available for that week
							if (node.td[1].class.indexOf('away') >= 0) {
								if (away_team.indexOf('NY') >= 0) {
									get_point_spread(away_team.replace('NY ', ''));
								} else {
									get_point_spread(away_team);
								}
							}
							if (node.td[3].class.indexOf('home') >= 0) {
								if (home_team.indexOf('NY') >= 0) {
									get_point_spread(home_team.replace('NY ', ''));
								} else {
									get_point_spread(home_team);
								}
							}
						}
						k++;

					}				
				});
				
		      } else{
		        schedule += "Sorry, could not find any data. Please try another one.";
				console.log(response);
		      }
		      schedule += '<button class="ui-shadow ui-btn ui-corner-all" type="submit" id="submit-2">Send Picks</button><input type="hidden" value="1" id="pickset-week" name="pickset-week"></form></div>';
		      Y.one('#schedule_results').empty().append(schedule);
			  $('#schedule_results').trigger('create');
			  $("#pickset-week").attr('value', week);
			  //$("#schedule_results input[type='radio']").checkboxradio("refresh");
		      schedule = "";
		    });
	  });
	}
	setTimeout(function() {
		schedule_yui_query('1');
		//sports_yui_query('national')
	}, 2000); 
	setTimeout(function() {
		
	}, 1000); 
	</script>
	</div>
</div>
</body>
</html>
