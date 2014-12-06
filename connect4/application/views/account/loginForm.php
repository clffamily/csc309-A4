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
	
	<h1>Login</h1>
	
<?php
	require_once 'securimage.php';

	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p>";
	}
	
	echo form_open('account/login');
?>	
	<div class="form-group">
<?php 	
	echo form_label('Username'); 
	echo form_error('username');
	$name_input = array( 'name' => 'username', 'class' => 'form-control');
	echo form_input($name_input , set_value('username'),"required");	
?>
	</div>
	
	<div class="form-group">
<?php 
	echo form_label('Password'); 
	echo form_error('password');
	$password_input = array( 'name' => 'password', 'class' => 'form-control');
	echo form_password($password_input,'',"required");
?>
	</div>
	
	<div class="form-group">
<?php 	
	echo "<div>";
	echo Securimage::getCaptchaHtml();
	echo "</div>";
?>
	</div>
		
	<div class="form-group">
<?php 

	$attributes = array(
		'name' => 'submit',
		'class' => 'btn btn-default',
		'value' => 'Login'
	);

	echo form_submit($attributes);

?>
	</div>
			
<?php 
	echo "<p>" . anchor('account/newForm','Create Account') . "</p>";

	echo "<p>" . anchor('account/recoverPasswordForm','Recover Password') . "</p>";
	
	echo form_close();
?>	
</div>
</div>

</body>

</html>

