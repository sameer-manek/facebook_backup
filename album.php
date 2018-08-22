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
		      appId      : '222413885297299',
		      cookie     : true,
		      xfbml      : true,
		      version    : 'v3.1'
		    });
		      
		    FB.AppEvents.logPageView();
		   	
			let logout = document.getElementById("logoutButton")
			logout.onclick = () => {
				FB.logout()
				(window.location = "/fb_caller.php?i=logout")
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

	<h1 id="album_name"></h1>

	<button id="logoutButton">logout</button>

	<div id="photos">
		
	</div>

	<button id="gdrive_backup">backup on google drive</button>

	<!-- todo : implement google drive api -->

<script type="text/javascript">
	// fetch album information

	$(document).ready(function(){
		$.ajax({
			url: '/fb_caller.php',
			type: 'GET',
			dataType: 'JSON',
			data:{
				i : 'album_info',
				id : '<?php echo $_REQUEST['id']; ?>'
			},
			success: (data)=> {
				$("#album_name").append(data.name)
			},
			error: ()=> {
				console.log("this is useless!")
			}
		})

		// fetching the photos in the album

		$.ajax({
			url: 'fb_caller.php',
			type: 'GET',
			dataType: 'JSON',
			data: {
				i: 'photos',
				id : '<?php echo $_REQUEST['id']; ?>'
			}, 
			success: (data) => {
				data.forEach(function(e){
					$("#photos").append('<img src="'+e.picture+'" /><br>')
				})
			},
			error: (data)=> console.log("there was some internal problem!")
		})
	})
</script>

</body>
</html>