
<!DOCTYPE html>

<html>
	<head>
		<style>
			input {
				display: block;
			}
		</style>
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
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	</head> 
<body>  
<div class="container"> 
<div class="jumbotron">
<h1>Change Password</h1>
<?php 
	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p>";
	}

	echo form_open('account/updatePassword');
	?>
		
<div class="form-group">
	<?php 
	echo form_label('Current Password'); 
	echo form_error('oldPassword');
	$oldPassword_input = array( 'name' => 'oldPassword', 'class' => 'form-control');
	echo form_password($oldPassword_input,set_value('oldPassword'),"required");
	// echo form_password('oldPassword',set_value('oldPassword'),"required");
	?>
</div>
				
<div class="form-group">
	<?php 
	echo form_label('New Password'); 
	echo form_error('newPassword');
	$newPassword_input = array( 'name' => 'newPassword', 'class' => 'form-control');
	echo form_password($newPassword_input,'',"id='pass1' required");
	// echo form_password('newPassword','',"id='pass1' required");
	?>
</div>
					
<div class="form-group">
	<?php 
	echo form_label('Password Confirmation'); 
	echo form_error('passconf');
	$passconf_input = array( 'name' => 'passconf', 'class' => 'form-control');
	echo form_password($passconf_input,'',"id='pass2' required oninput='checkPassword();'");
	// echo form_password('passconf','',"id='pass2' required oninput='checkPassword();'");
	?>
</div>
					
<div class="form-group">
	<?php
	
	$attributes = array(
			'name' => 'submit',
			'class' => 'btn btn-default',
			'value' => 'Change Password'
	);
	
	echo form_submit($attributes);
	
	// echo form_submit('submit', 'Change Password');
	echo form_close();
?>	
</div>
</div>
</div>
</body>

</html>

