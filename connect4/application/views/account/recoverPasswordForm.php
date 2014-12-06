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
	<h1>Recover Password</h1>
<?php 
	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p>";
	}

	echo form_open('account/recoverPassword');
	?>
	
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
			'value' => 'Recover Password'
	);
	
	echo form_submit($attributes);
	echo form_close();
	?>	
</div>

</div>
</div>
</body>

</html>

