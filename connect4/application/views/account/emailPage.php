
<!DOCTYPE html>

<html>
	<head>
		<style>
			input {
				display: block;
			}
		</style>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	</head> 
<body>  
<div class="container"> 
<div class="jumbotron"> 
	<h1>Password Recovery</h1>
	
	<p>Please check your email for your new password.
	</p>	
	
<?php 
	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p>";
	}

	echo "<p>" . anchor('account/index','Login') . "</p>";
?>	
</div>
</div>
</body>

</html>

