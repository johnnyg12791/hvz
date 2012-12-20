<audio id="sound" style="visibility:hidden;" src="sounds/Bite-SoundBible.com-2056759375.mp3" controls preload="auto" autobuffer></audio>


<div data-role="page">
		<div data-role="header">
		
		<?php
		$num_hours_so_far = getHoursAgo($start_timestamp);
		
		$queryPlayers = "SELECT fb_id, fb_name, recent_latitude, recent_longitude, is_human, num_bites FROM Game_Player_Info, Fb_Id_Name WHERE fb_id = player_id AND game_id = '$game_id'";
		$resultPlayers = mysql_query($queryPlayers);
		$counter = 0;
		while($row = mysql_fetch_assoc($resultPlayers)){	
			$player['fb_id'] = $row['fb_id'];
			$player['fb_name'] = $row['fb_name'];
			$player['latitude'] = $row['recent_latitude'];
			$player['longitude'] = $row['recent_longitude'];
			$player['is_human'] = $row['is_human'];
			$player['num_bites'] = $row['num_bites'];
			$playersArray[$counter] = $player;
			$counter++;
		}	
	?>
	   <a onclick="history.back(-1)" data-icon="arrow-l">Back</a>
	    <!--<a data-role="button"  onclick="resignGame()" data-icon="delete">Resign</a>-->
		<h1><?=$game_name?></h1>
		<a href="gameDetails.php?game_id=<?=$game_id?>" data-icon="arrow-r">More</a>
	</div><!-- /header -->

	<div data-role="content">
		<div id="mapholder" style="width: devic-width; height: 400px;"></div>
		<script src="http://maps.google.com/maps/api/js?sensor=true"></script>
		<script>
		<!--Stylings for the zombie map-->
	  var ATTACK_DISTANCE = .01; <!--(miles? kilometers?)-->
	  var MY_MAPTYPE_ID = 'zombie mode';
      var styles = [
           {
            featureType: 'all',
            elementType: 'geometry',
            stylers: [
              { hue: '#ff0008' },
              { saturation: -5 },
              { lightness: -62}
            ]
          },
          {
            featureType: 'landscape.man_made',
            elementType: 'geometry.stroke',
            stylers: [
              { hue: '#ff0900' },
              { color: '#000401'},
              { saturation: 97 }
            ]
          },
          {
            featureType: 'road',
            elementType: 'geometry.fill',
            stylers: [
              {weight: 1.7},
              { hue: '#00ff11' },
              { saturation: 1 }, 
              {lightness: -100}
            ]
          },
          {
            featureType: 'road',
            elementType: 'labels.text.stroke',
            stylers: [
              {lightness: 100}
            ]
          },

          {
            featureType: 'water',
            elementType: 'geometry',
            stylers: [
              { hue: '#00ff5e'},
              {lightness: -80}
            ]
          }
        ];
        var MY_MAPTYPE_ID2 = 'human mode';
      var styles2 = [
           {
            featureType: 'landscape',
            elementType: 'all',
            stylers: [
              { hue: '#005eff' },
              { saturation: 14},
              { lightness: -68}
            ]
          },
           {
            featureType: 'road',
            elementType: 'geometry.fill',
            stylers: [
            {weight: 1.7},
              { hue: '#007fff' },
              { saturation: 100 },
              { lightness: -39}
            ]
          },
          {
            featureType: 'road',
            elementType: 'all',
            stylers: [
              { hue: '#0077ff' },
              { lightness: 77}
            ]
          },
          {
            featureType: 'water',
            elementType: 'geometry',
            stylers: [
              { hue: '#0800ff' },
              {lightness: -84}
            ]
          },
          {
            featureType: 'poi',
            elementType: 'all',
            stylers: [
              { hue: '#005eff'},
              {saturation: -59},
              {lightness: -59}
            ]
          }
        ];

		var x=document.getElementById("demo");
		getLocation();
		function getLocation(){
	  		if (navigator.geolocation){
	   	 		navigator.geolocation.getCurrentPosition(showPosition,showError);
	    	}else{x.innerHTML="Geolocation is not supported by this browser.";}
	  	}
		var myLat=0;
		var myLon=0;
		var isHuman;
		var myMessage;
		var map;
		var marker;
  		var infowindow;
  		var myOptions;
  		var markersArray = [];
  		
  		<!--Sets up map and gets initial location-->
  		function showPosition(position){
		  lat=position.coords.latitude;
		  myLat=lat;
		  lon=position.coords.longitude;
		  myLon=lon;
		  
		$.post("updateLocation.php", {
			latitude: myLat,
			longitude: myLon,
			game_id: <?=$game_id?>,
			user_id: <?=$user_id?>
		}, function(data) {
			
		});
		
		  latlon=new google.maps.LatLng(lat, lon);
		  mapholder=document.getElementById('mapholder');
		  mapholder.style.height='400px';
		  mapholder.style.width='device-width';
		  
		   <?
		   //Gets the users status (either human or zombie)
		  for($i=0;$i<count($playersArray);$i++){
		  ?>	
		  	if(<?=$user_id?>==<?=$playersArray[$i]['fb_id']?>){
		  		if(<?=$playersArray[$i]['is_human']?>){
		  			isHuman=1;
		  		}else{
		  			isHuman=0;
		  			myMessage="You have killed <?=$playersArray[$i]['num_bites']?> humans";
		  		}
		  	}
		  <?}?>
		  
		  
		  //HUMANVERSION
		  if(isHuman){
		  	myOptions={
				  center:latlon,zoom:15,
				  mapTypeControl: false,
        	  	  mapTypeId: MY_MAPTYPE_ID2
			 };
			 map=new google.maps.Map(document.getElementById("mapholder"),myOptions);
		  	 
         	var myMapType = new google.maps.StyledMapType(styles2);
         	map.mapTypes.set(MY_MAPTYPE_ID2, myMapType);
		  }else{
		  //ZOMBIEVERSION!!!
		  	myOptions={
				  center:latlon,zoom:15,
				  mapTypeControl:false,
        	  	  mapTypeId: MY_MAPTYPE_ID
			 };
			 map=new google.maps.Map(document.getElementById("mapholder"),myOptions);
		  	 
          	
         	var myMapType = new google.maps.StyledMapType(styles);
         	map.mapTypes.set(MY_MAPTYPE_ID, myMapType);
		  }
		  plotUser();
		  plotMarkers();
		  navigator.geolocation.watchPosition(updateMarker,showError); 
      }
      
      function plotUser(){
      	marker=new google.maps.Marker({position:latlon,map:map,title:"You are here!"});
		  if(isHuman){
		  	marker.setIcon('http://maps.google.com/mapfiles/ms/micons/blue-dot.png');
		  }
		  if(isHuman){
		  	infowindow = new google.maps.InfoWindow({
            	content: '<h5> You have survived for <?=$num_hours_so_far?> hours</h5>'
          	});
		  }else{
		  	infowindow = new google.maps.InfoWindow({
            	content: '<h5>'+myMessage+'</h5>'
          	});
		  }
		  google.maps.event.addListener(marker, 'click', function() {
          	infowindow.open(map,marker);
          });
		  infowindow.open(map,marker);

      }
      
		function plotMarkers(){
		  		  
		  <?
		  for($i=0;$i<count($playersArray);$i++){
		  ?>	
		  if(<?=$user_id?>!=<?=$playersArray[$i]['fb_id']?>){
		  	lat2=<?=$playersArray[$i]['latitude']?>;
		  	lon2=<?=$playersArray[$i]['longitude']?>;
		  	latlon2=new google.maps.LatLng(lat2, lon2);
		  	marker_<?=$i?>=new google.maps.Marker ({position:latlon2, map:map, title: "Friends here!"});
		    markersArray.push(marker_<?=$i?>);
		  	var playerStatus;
		  	var playerMessage;
		  	var distanceMessage=' ';
		  		if(<?=$playersArray[$i]['is_human']?>){
		  		  marker_<?=$i?>.setIcon('running_man_animation.png');
		  		  playerStatus='Human';
		  		  if(!isHuman){
		  		  	var x=lat2-myLat;
		  		  	var y=lon2-myLon;
		  		  	x=x*x;
		  		  	y=y*y;
		  		  	var distance=Math.sqrt(x+y);
		  		  	if(distance<ATTACK_DISTANCE){
		  		  		playerMessage='<form><input id="attack" type="button" value="Attack!" onclick="sendAttack(<?=$playersArray[$i]['fb_id']?>)"></form>'; /*i know format looks weird, but its ok*/
		  		  	}else{
		  		  		playerMessage='You are too far to attack this human';
		  		  	}
		  		  }else{
		  		  	playerMessage='';	
		  		  }  
		  		}else{
		  			marker_<?=$i?>.setIcon('http://android-emotions.com/wp-content/flagallery/zombie-run/zombie-run-cover.png');
			  		playerStatus='Zombie';
			  		playerMessage="Killed: <?=$playersArray[$i]['num_bites']?> humans";
			  		if(isHuman){
		  		  	var x2=lat2-myLat;
		  		  	var y2=lon2-myLon;
		  		  	x2=x2*x2;
		  		  	y2=y2*y2;
		  		  	var distance=Math.sqrt(x2+y2);
		  		  	if(distance<ATTACK_DISTANCE){
		  		  			distanceMessage='Watch out! This zombie is close enough to attack you!';
		  		  	}else{
		  		  		distanceMessage='This zombie is too far to attack you';
		  		  	}
		  		  }else{
		  		  		
		  		  }  

			  	}
			 	 var playerName="<?=$playersArray[$i]['fb_name']?>";
			 	 var infowindow_<?=$i?> = new google.maps.InfoWindow({	
	         		content: '<h5>'+playerName+'</h5><h5>'+playerMessage+'</h5><h5>'+distanceMessage+'</h5>'
	         	 });
			 	google.maps.event.addListener(marker_<?=$i?>, 'click', function() {
	         	infowindow_<?=$i?>.open(map,marker_<?=$i?>);
	        	});
		  	}
         <?}?>     	  
		}
		
		function sendAttack(fb_id){
			document.getElementById("sound").play();
			//alert('about to send attack to database on ' + fb_id);
			$.post("attack.php", {
					game_id: <?=$game_id?>, 
					biter_id: <?=$user_id?>, 
					bitten_id: fb_id
				}, function(data) {
					alert('Successful Attack');
   				});
   			$("#attack").hide();
		}
		
		
		function updateMarker(newposition){
			newlat=newposition.coords.latitude;
			newlon=newposition.coords.longitude;
			newlatlon=new google.maps.LatLng(newlat, newlon);
			marker.setPosition(newlatlon);
		$.post("updateLocation.php", {
			latitude: newlat,
			longitude: newlon,
			game_id: <?=$game_id?>,
			user_id: <?=$user_id?>
		}, function(data) {
			
		});
	
			deleteMarkers();
			
			plotMarkers();
		}	
		
		function deleteMarkers() {
  			if (markersArray) {
    			for (i in markersArray) {
     				markersArray[i].setMap(null);
    			}
   				markersArray.length = 0;
  			}
		}
		
		
		
	    //error messages
		function showError(error){
	  		switch(error.code){
	    		case error.PERMISSION_DENIED:
	      			x.innerHTML="User denied the request for Geolocation."
	      			break;
	    		case error.POSITION_UNAVAILABLE:
	      			x.innerHTML="Location information is unavailable."
	      			break;
	    		case error.TIMEOUT:
	      			x.innerHTML="The request to get user location timed out."
	      			break;
	    		case error.UNKNOWN_ERROR:
	      			x.innerHTML="An unknown error occurred."
	      			break;
	    	}
		}
		function resignGame(){
			if(confirm("Are you sure you want to resign?")){
				$("#resign").click(function(event) {
					$.post("resign.php", {
						game_id: <?=$game_id?>,
						user_id: <?=$user_id?>
					});
				});
				alert('successfully resigned');
			}
		}
		
		
		setTimeout(function(){
   			window.location.reload(1);
		}, 60000);
	</script>
	</script>
	
	<a id='resign' data-role="button" data-theme="a" data-inline="true" onclick='resignGame()'>Resign</a>
	<script>
	
	</script>
	
	<h5 style="text-align:right; color: white">
	<img src="running_man_animation.png" width="25" height="25"> 
	Humans: <?=$num_humans?>
	<img src="http://android-emotions.com/wp-content/flagallery/zombie-run/zombie-run-cover.png" width="25" height="25"> 
	Zombies: <?=$num_zombies?>
	</h5>
	
	
	</div><!-- /content -->
	
	<div data-role="footer" data-id="samebar" class="nav-icons" data-position="fixed" data-tap-toggle="false">
	
	<?php
		include('inc/navBar.php');
	 ?>
	 </div> <!-- end of footer -->
	
</div><!-- /page -->