<?php
include('._._inc_header._._.php');
if($TibiaMarket->isLogged() == false){
	$TibiaMarket->redirect("login");
}
if(isset($_POST['newpw'])){
    $errors = array();

    if( (empty($_POST['newpw'])) || (empty($_POST['newpw2'])) || (empty($_POST['oldpw']))){
        $errors[] = "Fill in all the fields!";
    }else{

        $changed = false;

        $pw = $_POST['newpw'];
        $pw2 = $_POST['newpw2'];
        if (strlen($pw) < 6){
            $errors[] = 'Your password must be atleast 6 characters';
        } else if (strlen($pw) > 18){
            $errors[] = 'Your password cannot be more than 18 characters long';
        } else if (strcmp($pw, $pw2) != 0) {
            $errors[] = 'Both new passwords does not match.';
        }

        if(!$TibiaMarket->hashVerify($_POST['oldpw'], $userData['password'])){
            $errors[] = 'Your current password does not match.';
        }



        if(empty($errors)){
            $hashPW = $TibiaMarket->hashPW($pw);
            $updatePW = $odb->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");
            $updatePW->BindValue(1, $hashPW);
            $updatePW->BindValue(2, $userData['id']);
            $updatePW->execute();

            $changed = true;
        }
    }
}

?>

<div id="content">
	<div id="content-header">
		<h1>Settings</h1>
	</div>
	<div id="breadcrumb">
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">Settings - <?php
		
		$access = $TibiaMarket->userTable($userData['id'], "access_level");
		 if($access == 6){
			 echo "Founder";
		 }
		 if($access == 5){
			 echo "Super Administrator";
		 }
		 if($access == 4){
			 echo "Administrator";
		 }
		 if($access == 3){
			 echo "Super Moderator";
		 }
		 if($access == 2){
			 echo "Moderator";
		 }
		 if($access == 1){
			 echo "VIP Member";
		 }
		 if($access == 0){
			 echo "Member";
		 }



		?>
		
		
		
		</a>
	</div>
	<div class="row">
		<div class="col-xs-4"></div>
		<div class="col-xs-5">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-cog"></i>
					</span>
					<h5>Change Password </h5>
				</div>
				<div class="widget-content nopadding">
					<form action="" method="post" class="form-horizontal">
						<?php
                    	    if(isset($_POST['newpw2'])){
                    	        if(!empty($errors)){
                    	            echo '<div class="alert alert-danger">';
                    	            echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                    	            foreach($errors as $error){
                    	                echo ''.$error.'<br />';
                    	            }
                    	            echo '</div>';
                    	        }else{
                                    if($changed){
                                        echo '<div class="alert alert-success">';
                                        echo '<button type="button" class="close" data-dismiss="alert">×</button>';
                                        echo 'Your password has been changed.';
                                        echo '</div>';
                                    }
                                }
                    	    }
                    	?>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Current Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control input-sm" name="oldpw">
                            </div>
                        </div>


						<div class="form-group">
							<label class="col-sm-4 control-label">New Password</label>
							<div class="col-sm-8">
								<input type="password" class="form-control input-sm" name="newpw">
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Retype New Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control input-sm" name="newpw2">
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