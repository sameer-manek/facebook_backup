<!DOCTYPE html>
<html>
	<head>
		<title>facebook tool</title>

		<!-- JQuery -->

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- facebook JS SDK -->

		<script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '222413885297299',
		      cookie     : true,
		      xfbml      : true,
		      version    : 'v3.1'
		    });
		      
		    FB.AppEvents.logPageView();
		   	
		   	FB.getLoginStatus(function(response){
		   		if(response.status == 'connected'){
		   			token = response.authResponse.accessToken
		   			window.location = "/fb_caller.php?i=logged&token="+token
		   		}
		   		// if not logged in display the login button
		   		if(response.status != "connected"){
		   			var button = document.getElementById("fblogin")
		   			button.style.display = "block";
		   			button.onclick = () => $.ajax({
			   				url : "/fb_caller.php",
			   				type : 'GET',
			   				dataType : 'JSON',
			   				data : {
			   					i: "login"
			   				},
			   				success: (data)=> window.location = data['uri'],
			   				error: ()=> alert("there was something wrong!")
			   		})
		   		}
		   	})
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

	<!-- design this page -->

	<div id="fblogin" style="display: none;">
		<button>Login with facebook account</button>
	</div>

	</body>
</html>