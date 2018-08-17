<!DOCTYPE html>
<html>
	<head>
		<title>facebook tool</title>

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
		   		// if not logged in display the login button
		   		if(response.status == "connected"){
		   			document.write("connected");
		   		} else{
		   			document.write('not connected');
		   		}
		   		// else show the user name on the screen
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

	<?php

		session_start();

		require_once('./vendor/autoload.php');

		$fb = new \Facebook\Facebook([
		  'app_id' => '222413885297299',
		  'app_secret' => '85e84df32c06c5801bba06f3710b3e0f',
		  'default_graph_version' => 'v3.1',
		]);

		function fetchUserInfo($access_token, $fb){
				try {
				  $response = $fb->get('/me', $access_token);
				} catch(\Facebook\Exceptions\FacebookResponseException $e) {
				  // When Graph returns an error
				  echo 'Graph returned an error: ' . $e->getMessage();
				  exit;
				} catch(\Facebook\Exceptions\FacebookSDKException $e) {
				  // When validation fails or other local issues
				  echo 'Facebook SDK returned an error: ' . $e->getMessage();
				  exit;
				}

				$user = $response->getGraphUser();
				echo $user->getName();
		}

		// trial with using given accesstoken

		/*fetchUserInfo(
			'EAADKSMSgUpMBAM1qnePSjUIUKZAKpeNDwI4bXx5NTmZAonRUt4AnYKZCVXlaFBupZCT2946msycXSXBO7hFMgYxrIFR49woUNJIU6lqK8frdXierjFI0PQRAASmWUZA58eg39AFUeKcKHPBqgzJQ4jq0n4dexIGtd9pTFZCQpzR83koeKbDCCp6lZANJ7b4oOhppZCzWURIILPXd7VZB1nB3Irr3rzv9zSg8NRgiIUADkAQZDZD', $fb
		);*/

		// next: how to fetch the access token
	?>

	<!-- first check if the user is already connected his/her account -->

	</body>
</html>