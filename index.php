<!DOCTYPE html>
<html>
	<head>
		<title>facebook tool</title>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.2/css/bulma.min.css" />

		<!-- JQuery -->

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- facebook JS SDK -->

		<script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '2223553694382717',
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
			   				error: (data)=> alert("could not fetch the URL")
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

		<style media="screen">

			html,body {
				height: 100%;
				background: linear-gradient(to bottom, #DDD, #EEE, white);
			}

			.title {
				text-align: center;
			}

			button.is-blue{
				background-color: #3B5998;
				color: white;
				border: 0px;
				outline: 0px;
				padding: 4px 10px;
				margin: 0 auto;
				transition: 0.2s;
			}

			button.is-blue:hover {
				background-color: #8B9DC3;
				color: white;
				transition: 0.2s;
			}

			div.doc{
				width: 80%;
				margin: 0 auto;
				text-align: center

			}

			hr{
				height: 2px;
				background: black;
				color: black;
			}
		</style>

	</head>

	<body>

	<!-- design this page -->
	<h1 class="title" style="padding: 7px 0;">Facebook album backup tool</h1>

	<div class="box doc">
		<h2 class="title is-4">How this works</h2>
		<hr />

		<p>If you are take a lot of pictures and want to keep them safe on your google drive, this is the tool my friend!</p>

		<p>Using this is simple, just login with your facebook account using the button below. You will be shown a list of all your albums, which you will be able to browse and backup to your google drive.</p>


		<div id="fblogin" style="display: none; margin: 10px 0;">
			<button class="button is-blue">Login with facebook account</button>
		</div>

		<p><b>Your privacy is not harmed!</b></p><p>Make a note that We do not intend to collect or use any of your information on our server. Moreover, this is a completely open source application, so you can watch the code for yourself <a target="_blank" href="https://github.com/sameer-manek/facebook_backup">here</a>. You are also welcomed to improve the code!</p>
	</div>

	</body>
</html>
