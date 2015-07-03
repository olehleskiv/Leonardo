<meta charset="utf-8">
<link href="assets/css/login.css" rel='stylesheet' type='text/css' />
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:600italic,400,300,600,700' rel='stylesheet' type='text/css'>

<div class="main">
	<div class="login-form">
		<h1>Villa "Leonardo"</h1>
		<div class="head">
			<img src="images/user.png" alt="Villa Leonardo admin"/>
		</div>
		<form action="<?php echo $self; ?>" method='post'>
			<input type="text" class="text" name="username" id="username" value="Логін" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Логін';}" >
			<input type="password" name="password" value="Пароль" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Пароль';}">
			<div class="submit">
				<input type="submit" name="submit" value="Увійти" >
			</div>
			<?php 
				if ($isError == true) {
					echo '<p class="error-text">Пароль або логін НЕПРАВИЛЬНІ! Спробуйте ще раз!</p>';
				}
			?>
		</form>
	</div>
</div>

