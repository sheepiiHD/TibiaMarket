<?php include('._-configurations._-/loader.php'); 
	
		if(isset($_POST['submit'])){
			if(strlen($_POST['description']) > 0){
				if(strlen($_POST['description']) <= 210){
					if($_POST['action'] == "update"){
						$user = $TibiaMarket->userData();
						$id = $user['id'];
						//Get the amount of the reputation
						$squery = $odb->prepare("SELECT * FROM `reputation` WHERE `giver_id` = ? && `recipient_id` = ?");
						$squery->BindValue(1, $id);
						$squery->BindValue(2, $_GET['id']);
						$squery->execute();
					
						
						$q = $squery->fetch(PDO::FETCH_ASSOC);
						$amount = $q['amount'];
						
						//Get the total amount of reputation this user has.
						$dquery = $odb->prepare("SELECT * FROM `users` WHERE `id` = ?");
						$dquery->BindValue(1, $_GET['id']);
						$dquery->execute();
						
						$q2 = $dquery->fetch(PDO::FETCH_ASSOC);
						$totalrep = $q2['reputation'];
						
						//Remove the amount temporarily. 
						$altered = $totalrep - $amount;
						$added = $altered + $_POST['repamount'];
						
						//Update the description
						$updatedesc = $odb->prepare("UPDATE `reputation` SET `description` = ? WHERE `giver_id` = ? && `recipient_id` = ?");
						$updatedesc->BindValue(1, $_POST['description']);
						$updatedesc->BindValue(2, $id);
						$updatedesc->BindValue(3, $_GET['id']);
						$updatedesc->execute();
						
						//Update the amount
						$updateamt = $odb->prepare("UPDATE `reputation` SET `amount` = ? WHERE `giver_id` = ? && `recipient_id` = ?");
						$updateamt->BindValue(1, $_POST['repamount']);
						$updateamt->BindValue(2, $id);
						$updateamt->BindValue(3, $_GET['id']);
						$updateamt->execute();
						
						//Update the reputation
						$updaterep = $odb->prepare("UPDATE `users` SET `reputation` = ? WHERE `id` = ?");
						$updaterep->BindValue(1, $added);
						$updaterep->BindValue(2, $_GET['id']);
						$updaterep->execute();
						
					}elseif($_POST['action'] == "add"){
						$user = $TibiaMarket->userData();
						$id = $user['id'];
						
						$addrep = $odb->prepare("INSERT INTO `reputation` (`id`, `recipient_id`, `giver_id`, `amount`, `description`, `timestamp`) VALUES (NULL, ? , ? , ? , ? , CURRENT_TIMESTAMP)");
						$addrep->BindValue(1, $_GET['id']);
						$addrep->BindValue(2, $id);
						$addrep->BindValue(3, $_POST['repamount']);
						$addrep->BindValue(4, $_POST['description']);
						$addrep->execute();
						
						$dquery = $odb->prepare("SELECT * FROM `users` WHERE `id` = ?");
						$dquery->BindValue(1, $_GET['id']);
						$dquery->execute();
						
						$q2 = $dquery->fetch(PDO::FETCH_ASSOC);
						$totalrep = $q2['reputation'];
						
						$rep = $_POST['repamount'];
						
						$added = $totalrep + $rep;
						//Update the reputation
						$updaterep = $odb->prepare("UPDATE `users` SET `reputation` = ? WHERE `id` = ?");
						$updaterep->BindValue(1, $added);
						$updaterep->BindValue(2, $_GET['id']);
						$updaterep->execute();
					}else{
						echo("There's an error! :(");
					}
				}else{
					echo("Your reputation can only be a maximum of 210.");
				}
			}else{
				echo("You need to provide a description!");
			}
		}


?>
<script>
    window.onunload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
    }
</script>
<html lang="en">
	<head>
		<title><?php _echo($siteName);?></title>
		<base href="<?php _echo($siteURL);?>">
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/font-awesome.css" />
		<link rel="stylesheet" href="css/fullcalendar.css" />
		<link rel="stylesheet" href="css/jquery.jscrollpane.css" />
		<link rel="stylesheet" href="css/select2.css" />
		<link rel="stylesheet" href="css/jquery-ui.css">
		<link rel="stylesheet" href="css/jquery.gritter.css">
		<link href="/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<!--[if lt IE 9]>
			<script type="text/javascript" src="js/respond.min.js"></script>
			<![endif]-->
	</head>
	
	<div style="text-align: center; padding-top: 10px;">
		<div class="col-sm-12">
		<?php 	
			if($TibiaMarket->isLogged()){ 
				if(isset($_GET['id'])){
					$user = $TibiaMarket->userData();
					$access = $user['access_level'];
					$id = $user['id'];
					$reputation = $user['reputation'];
					$VIP = $user['VIP'];
					//
					$query = $odb->prepare("SELECT * FROM `reputation` WHERE `giver_id` = ? && `recipient_id` = ?");
					$query->BindValue(1, $id);
					$query->BindValue(2, $_GET['id']);
					$query->execute();
					
					$recipientquery = $odb->prepare("SELECT * FROM `users` WHERE `id` = ?");
					$recipientquery->BindValue(1, $_GET['id']);
					$recipientquery->execute();
					
					$recipientinfo = $recipientquery->fetch(PDO::FETCH_ASSOC);
					$recipientaccess = $recipientinfo['access_level'];
					$recipientid = $recipientinfo['id'];
					$recipientusername = $recipientinfo['username'];
					$recipientreputation = $recipientinfo['reputation'];
					$recipientVIP = $recipientinfo['VIP'];
					
					$totalrep = 1;
					
					if($access < 1){
						if($reputation >= 50 && $reputation < 300){
							$totalrep = 2;
						}
						if($reputation >= 300){
							$totalrep = 3;
						}
					}
					if($VIP > 0){
						$totalrep += 2;
					}
					if($access > 1 && $access < 4){
						$totalrep = 5;
					}
					if($access > 3 && $access < 6){
						$totalrep = 8;
					}
					if($access > 5){
						$totalrep = 15;
					}
					
					$totalrep *=2;
					$startrep = $totalrep/2;
					$timesadded = 0;
					if($_GET['id'] != $user['id']){
						//new rep
						?>
						<form action="" method="post" class="form-horizontal">
							<div class="form-group">
								<div class="col-sm-8">
									<select name="repamount">
										<?php
										//Add new Reputation
										if($query->rowCount() == 0){
											while($timesadded < $totalrep + 1){
												if($startrep > 0){
													$color = "color:green;";
													$message = "Positive: (+";
												}elseif($startrep == 0){
													$color = "color:grey;";
													$message = "Neutral: ";
												}elseif($startrep < 0){
													$color = "color:red;";
													$message = "Negative: (";
												}
												echo("<option style=".$color." font-weight: bold; value=".$startrep.">".$message.$startrep.")</option>");
												$startrep -= 1;
												$timesadded += 1;
											} 
											$action = "add";
										//Update the rep
										}else{
											while($timesadded < $totalrep + 1){
												if($startrep > 0){
													$color = "color:green;";
													$message = "Positive: (+";
												}elseif($startrep == 0){
													$color = "color:grey;";
													$message = "Neutral: (";
												}elseif($startrep < 0){
													$color = "color:red;";
													$message = "Negative: (";
												}
												echo("<option style=".$color." font-weight: bold; value=".$startrep.">".$message.$startrep.")</option>");
												$startrep -= 1;
												$timesadded += 1;
											} 
											$action = "update";
										} ?>
									</select>
								</div>
							</div>
							<!-- Action to take depending on whether it exists already. -->
							<input type="hidden" name="action" id="hiddenField" value="<?php echo($action); ?>" />
							<div class="form-group">
								<label class="col-sm-4 control-label">Description</label>
								<div class="col-sm-8">
									<input id="desc1" type="text" class="form-control input-sm" name="description">
									<span class="help-block text-left">IMPORTANT: Abusing the reputation system may lead to <u><b>losing</b></u> ALL of your reputation, and being temporarily banned. </span>
								</div>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-primary btn-sm" name="submit">Submit</button>
							</div>
						</form>
						
						
						
					<?php 
					}else{
						echo("<span style='color:red;'>Error: You can't rep yourself silly! </span>");
					}
				}else{
					echo("<span style='color:red;'>Error: You don't have an ID! </span>");
				}
			}else{
				echo("<span style='color:red;'> You're not logged in! </span>");
			} 
			
		?>
		</div>
	</div>
</html>