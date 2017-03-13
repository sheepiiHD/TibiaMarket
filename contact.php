<?php
include('._._inc_header._._.php');

if(isset($_POST['subject'])){
    $errors = array();

    if( empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])){
        $errors[] = "Please fill in all the fields.";
    }

    $email      = inputSanitize($_POST['email']);
    $subject    = inputSanitize($_POST['subject']);
    $message    = inputSanitize($_POST['message']);


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid"; 
    }

    if(empty($errors)){

        $userIP = $TibiaMarket->userIP();
        $message = 
        "<center><b>You have received a new message from a user on {$siteName}</b></center>

        Email: {$email}

        Subject: {$subject}

        Message: {$message}

        User Ip Address: {$userIP}";

        $sendMail = SendEmail($contactEmail, "New Message on {$siteName}", $message);

        $success = true;
    }

}

?>

<div id="content">
	<div id="content-header">
		<h1>Contact</h1>
	</div>
	<div id="breadcrumb">
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">Contact</a>
	</div>
	<div class="row">
		<div class="col-xs-3"></div>
		<div class="col-xs-6">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-envelope"></i>
					</span>
					
					<h5>Contact Us</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="" method="post" class="form-horizontal">
						<?php
                            if(isset($errors)){
                                if(empty($errors)){
                                    if($success){
                                        echo '<div class="alert alert-success">';
                                        echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                                        echo '<strong>We have received your email. We should reply within 48 hours.</strong><br>';
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
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm" name="email">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Subject</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm" name="subject">
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Message</label>
                            <div class="col-sm-9">
                                <textarea class="form-control input-sm" rows="4" name="message"></textarea>
                            </div>
                        </div>

						<div class="form-actions">
							<button type="submit" class="btn btn-primary btn-sm">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include('._._inc_footer._._.php');
?>