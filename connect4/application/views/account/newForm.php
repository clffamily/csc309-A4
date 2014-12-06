<!DOCTYPE html>

<html>
	<head>
		<style>
			input {
				display: block;
			}
		</style>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script>
			function checkPassword() {
				var p1 = $("#pass1"); 
				var p2 = $("#pass2");
				
				if (p1.val() == p2.val()) {
					p1.get(0).setCustomValidity("");  // All is well, clear error message
					return true;
				}	
				else	 {
					p1.get(0).setCustomValidity("Passwords do not match");
					return false;
				}
			}
		</script>
	</head> 
<body>  
<div class="container"> 
<div class="jumbotron">
	<h1>New Account</h1>
	<?php 
	echo form_open('account/createNew');
	?>

<div class="form-group">
	<?php 
	echo form_label('Username'); 
	echo form_error('username');
	$name_input = array( 'name' => 'username', 'class' => 'form-control');
	echo form_input($name_input ,set_value('username'),"required");
	?>
</div>
		
<div class="form-group">
	<?php 
	echo form_label('Password'); 
	echo form_error('password');
	$password_input = array( 'name' => 'password', 'class' => 'form-control');
	echo form_password($password_input,'',"id='pass1' required");
	?>
</div>
			
<div class="form-group">
	<?php 
	echo form_label('Password Confirmation'); 
	echo form_error('passconf');
	$passconf_input = array( 'name' => 'passconf', 'class' => 'form-control');
	echo form_password($passconf_input,'',"id='pass2' required oninput='checkPassword();'");
	?>
</div>
				
<div class="form-group">
	<?php 
	echo form_label('First');
	echo form_error('first');
	$first_input = array( 'name' => 'first', 'class' => 'form-control');
	echo form_input($first_input, set_value('first'),"required");
	?>
</div>
					
<div class="form-group">
	<?php
	echo form_label('Last');
	echo form_error('last');
	$last_input = array( 'name' => 'last', 'class' => 'form-control');
	echo form_input($last_input, set_value('last'),"required");
	?>
</div>
						
<div class="form-group">
	<?php
	echo form_label('Email');
	echo form_error('email');
	$email_input = array( 'name' => 'email', 'class' => 'form-control');
	echo form_input($email_input, set_value('email'),"required");
	?>
</div>
							
<div class="form-group">
	<?php
	
	$attributes = array(
			'name' => 'submit',
			'class' => 'btn btn-default',
			'value' => 'Register'
	);
	
	echo form_submit($attributes);
	echo form_close();
?>	

</div>
</div>
</div>
</body>

</html>

