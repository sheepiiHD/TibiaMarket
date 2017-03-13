<?php
include('._._inc_header._._.php');

if(isset($_POST['username'])){
    $errors = array();

    if( empty($_POST['username']) || empty($_POST['password'])){
        $errors[] = "Please fill in all the fields.";
    }
	
	
    $username   = inputSanitize($_POST['username']);
    $password   = inputSanitize($_POST['password']);

    if(!$TibiaMarket->username_exists($username)){
        $errors[] = 'Wrong username/password combination.';
    }else{

    	$dbPassword = $TibiaMarket->userTable($TibiaMarket->userIDByUsername($username), "password");
        if($TibiaMarket->hashVerify($password, $dbPassword)){
            $userStatus = $TibiaMarket->userTable($TibiaMarket->userIDByUsername($username), "status");
            if($userStatus == "ACTIVE"){

				//update the token
                $token = $TibiaMarket->token();
                $updateUserInfo = $odb->prepare("UPDATE `users` SET `lastlogin` = ?, `lghash` = ? WHERE `username` = ?");
                $updateUserInfo->BindValue(1, $TibiaMarket->dateTime());
                $updateUserInfo->BindValue(2, $token);
                $updateUserInfo->BindValue(3, $username);
                $updateUserInfo->execute();
				
                $set = $TibiaMarket->setSession($token, "0");
				
				//see if the IP exists for username. 
				$id = $TibiaMarket->userTable($TibiaMarket->userIDByUsername($username), "id");
				
				$getIPs = $odb->prepare("SELECT * FROM `internetprotocols` WHERE `userid` = ? && `ip` = ?");
				$getIPs->BindValue(1, $id);
				$getIPs->BindValue(2, $TibiaMarket->userIP());
				$getIPs->execute();
				
				if($getIPs->rowCount() == 0){
					$addip = $odb->prepare("INSERT INTO `internetprotocols` (`id`, `userid`, `ip`, `first_time`) VALUES (NULL, ? , ? , CURRENT_TIMESTAMP)");
					$addip->BindValue(1, $id);
					$addip->BindValue(2, $TibiaMarket->userIP());
					$addip->execute();
				}
    
                Header("Location: index");
                exit();
    
                $success = true;
            }else{
                $errors[] = "You must activate your account first.";
            }
        }else{
            $errors[] = 'Wrong username/password combination.';
        }

    }
}
?>

<div id="content">
	<div id="content-header">
		<h1>Login</h1>
	</div>
	<div id="breadcrumb">
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">Login</a>
	</div>
	<div class="row">
		<div class="col-xs-4"></div>
		<div class="col-xs-4">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-lock"></i>
					</span>
					<h5>Login</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="" method="post" class="form-horizontal">
						<?php
                    	    if(isset($_POST['username'])){
                    	        if(!empty($errors)){
                    	            echo '<div class="alert alert-danger">';
                    	            echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                    	            foreach($errors as $error){
                    	                echo ''.$error.'<br />';
                    	            }
                    	            echo '</div>';
                    	        }
                    	    }
                    	?>

						<div class="form-group">
							<label class="col-sm-3 control-label">Username</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm" name="username">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Password</label>
							<div class="col-sm-9">
								<input type="password" class="form-control input-sm" name="password">
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