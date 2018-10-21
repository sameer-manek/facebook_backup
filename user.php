<?php

		if(!session_id()) {
		    session_start();
		}

		if(!isset($_SESSION['fb_access_token'])){
			header("location: /");
		}
?>

<!DOCTYPE html>
<html>
<head>
	<title>FB back up tool</title>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
		   window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '2223553694382717',
		      cookie     : true,
		      xfbml      : true,
		      version    : 'v3.1'
		    });

		    FB.AppEvents.logPageView();

				let logout = document.getElementById("logoutButton")
				logout.onclick = function() {
					FB.logout().then(
						window.location = "/fb_caller?i=logout"
					);
				}
		  };
		  (function(d, s, id){
		     var js, fjs = d.getElementsByTagName(s)[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement(s); js.id = id;
		     js.src = "https://connect.facebook.net/en_US/sdk.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));

		</script>
</head>
<body>

	<h1 id="user_name"></h1>

	<button id="logoutButton">logout</button>

	<div id="albums">

	</div>

<script type="text/javascript">
	$(document).ready(function(){
		$.ajax({
			url: "/fb_caller.php",
			type: 'GET',
			dataType: 'JSON',
			data: {
				i: "info",
			},
			success: (data)=> $("#user_name").html(data.name+"'s albums"),
			error: ()=> {
				console.log("some problem occured")
			}
		});

		$.ajax({
			url: "/fb_caller.php",
			type: 'GET',
			dataType: 'JSON',
			data: {
				i : 'albums'
			},
			success: (data)=> {
				data.forEach(function(e){
					let x = '<div class="album"><img src="'+e.picture.url+'"><a href="/album.php?id='+e.id+'" class="forward">'+e.name+'</p></div>';
					$("#albums").append(x);
				})
			},
			error: ()=> console.log('error')
		})
	})
</script>

</body>
</html>
