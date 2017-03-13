<?php
include('._._inc_header._._.php');

if($TibiaMarket->isLogged()){
  Header("Location: index");
  exit();
}
if(isset($_POST['username'])){
	$errors = array();
	
	$secret 	= "nope";
    $response 	= $_POST['g-recaptcha-response'];
    $uIP 		= $TibiaMarket->userIP();
	$data 		= file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$uIP");
	$decoded 	= json_decode($data);
	if(isset($_POST['g-recaptcha-response'])){
          $captcha = $_POST['g-recaptcha-response'];
	}

	if( empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password1']) || empty($_POST['password2'])){
		$errors[] = "Please fill in all the fields";
	}

	$uIP 		= $TibiaMarket->userIP();

	$username 	= $_POST['username'];
	$email 		= $_POST['email'];
	$password 	= $_POST['password1'];
	$password2 	= $_POST['password2'];
	
	if(strlen($username) < 5){
		$errors[] = 'Your username must be atleast 5 characters';
	}
	
	if(!$decoded->success){
		$errors[] = 'Captcha was not entered.';
	}
	
	if (strlen($password) < 6){
		$errors[] = 'Your password must be atleast 6 characters';
	} else if (strlen($password) > 18){
		$errors[] = 'Your password cannot be more than 18 characters long';
	} else if (strcmp($password, $password2) != 0) {
		$errors[] = 'Password does not match.';
	}

	if($TibiaMarket->email_exists($email)){
		$errors[] = 'Your email already exists in our records';
	}

	if($TibiaMarket->username_exists($username)){
		$errors[] = 'Username is already in use.';
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = "Email is not valid"; 
	}

	if(empty($errors)){

		$token = $TibiaMarket->token();

		$insertUser = $odb->prepare("INSERT INTO `users` (`username`, `email`, `password`, `registrationip`, `registrationdate`, `lastlogin`, `status`, `lghash`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
		$insertUser->BindValue(1, $username);
		$insertUser->BindValue(2, $email);
		$insertUser->BindValue(3, $TibiaMarket->hashPW($password));
		$insertUser->BindValue(4, $uIP);
		$insertUser->BindValue(5, $TibiaMarket->dateTime());
		$insertUser->BindValue(6, "0000-00-00 00:00:00");
		$insertUser->BindValue(7, $token);
		$insertUser->BindValue(8, "");
		$insertUser->execute();


		$message = "Greetings {$username},

		Please activate your account by clicking or by copying and pasting in your browser.

		{$siteURL}activate/{$token}

		Kind regards,
		TibiaMarket";

		$sendMail = SendEmail($email, "Activate your " . $siteName . " account!", $message);


		$success = true;
	}



}

?>

<div id="content">
	<div id="content-header">
		<h1>Register</h1>
	</div>
	<div id="breadcrumb">
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">Register</a>
	</div>
	<div class="row">
		<div class="col-xs-3"></div>
		<div class="col-xs-6">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-unlock-alt"></i>
					</span>
					<h5>Register</h5>
				</div>
				<div class="widget-content nopadding">

					<form action="" method="post" class="form-horizontal">

						<?php
							if(isset($errors)){
								if(empty($errors)){
									if($success){
										echo '<div class="alert alert-success">';
										echo '<button type="button" class="close" data-dismiss="alert">×</button>';
										echo '<strong>You have successfully been registered. Verify your email to activate your account.</strong><br>';
										echo '</div>';	
									}				
								}else{
									echo '<div class="alert alert-danger">';
									echo '<button type="button" class="close" data-dismiss="alert">×</button>';
									echo '<strong>Oh snap!</strong><br>';
					
										foreach($errors as $error){
											echo '-'.$error.'<br />';
										}
					
									echo '</div>';
								}
							}
						?>
						
						<div class="form-group">
							<label class="col-sm-4 control-label">Username</label>
							<div class="col-sm-8">
								<input type="text" class="form-control input-sm" name="username">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label">Email</label>
							<div class="col-sm-8">
								<input type="text" class="form-control input-sm" name="email">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label">Password</label>
							<div class="col-sm-8">
								<input type="password" class="form-control input-sm" name="password1">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label">Re-Type Password</label>
							<div class="col-sm-8">
								<input type="password" class="form-control input-sm" name="password2">
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-primary btn-sm">Submit</button>
						</div>
						<div class="g-recaptcha form-actions" data-theme="dark" data-sitekey="NOPE"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include('._._inc_footer._._.php');
?>