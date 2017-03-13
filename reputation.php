<?php
	include('._._inc_header._._.php');

	if(isset($_GET['id'])){
		$validated = false;
		if(is_numeric($_GET['id'])){
			$validateUserID = $odb->prepare("SELECT * FROM `users` WHERE `id` = ?");
			$validateUserID->BindValue(1, $_GET['id']);
			$validateUserID->execute();
			if($validateUserID->rowCount() == 1){
				$validateReputationID = $odb->prepare("SELECT * FROM `users` WHERE `id` = ?");
				$validateReputationID->BindValue(1, $_GET['id']);
				$validateReputationID->execute();
				
				$array = $validateReputationID->fetch(PDO::FETCH_ASSOC);
				$modifiedUsername = $array['username'];
			}else{
				$TibiaMarket->redirect("index");
			}
		}else{
			$TibiaMarket->redirect("index");
		}
	}else{
		$TibiaMarket->redirect("index");
	}
	
	//Taken
	$getRecipientReputations = $odb->prepare("SELECT * FROM `reputation` WHERE `recipient_id` = ?");
    $getRecipientReputations->BindValue(1, $_GET['id']);
    $getRecipientReputations->execute();
		
	//Given 
	$getGiverReputations = $odb->prepare("SELECT * FROM `reputation` WHERE `giver_id` = ?");
    $getGiverReputations->BindValue(1, $_GET['id']);
    $getGiverReputations->execute();
	
	$user = $TibiaMarket->userData();
	if($user['id'] == $_GET['id']){
		$self_rep = true;
	}else{
		$self_rep = false;
	}
?>

<div id="content">
    <div id="content-header">
        
    </div>
    <div id="breadcrumb">
        <a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
        <a href="#" class="current">Reputation <?php echo(" - ".$modifiedUsername); ?> </a>
    </div>
    <div class="row">
        <div class="col-sm-12">
			<?php if($TibiaMarket->isLogged()){ ?>
			<a class="btn btn-primary btn-xs" onclick="window.open('rep/<?php echo ($_GET['id']); ?>','MsgWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=250');">Add/Modify Reputation</a>
			<?php } ?>
			<table class="table table-bordered table-striped table-hover data-table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Amount</th>
						<th>Description</th>
						<th>Time</th>
						<?php if($self_rep){ ?>
						<th>Report</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
						<?php 
						while($record = $getRecipientReputations->fetch(PDO::FETCH_ASSOC)){ 
								$query = $odb->prepare("SELECT * FROM `users` WHERE `id` = ?");
								$query->BindValue(1, $record['giver_id']);
								$query->execute();
								
								$userinfo = $query->fetch(PDO::FETCH_ASSOC);
								
								$reputation = $userinfo['reputation'];
								$VIP = $userinfo['VIP'];
								$access = $userinfo['access_level'];
								$id = $userinfo['id'];
								$username = $userinfo['username'];
								
								$description = inputSanitize($record['description']);
								
								//Positive reputation
								if($record['amount'] > 0){
									$rep_color = "color: #32CD32;";
									$formatted = "+".$record['amount'];
								}
								
								//Neutral reputation
								if($record['amount'] == 0){
									$rep_color = "color: #000000;";
									$formatted = $record['amount'];
								}
								
								//Negative reputation
								if($record['amount'] < 0){
									$rep_color = "color: #FF0000;";
									$formatted = $record['amount'];
								}
								
									//Normal user GREEN
									$rank = "color: #228B22;";
									
									//VIP OR NOT
									$vipbold = "";
									
									if($reputation == 0){
										$color = "color: #000000;";
										
									}else if($reputation > 0){
										$color = "color: #32CD32;";
										
										//Elite users PURPLE
										if($reputation >= 50 && $reputation < 300){
											$rank = "color: #9400D3;";
											
										//Master users ROYAL BLUE
										}else if($reputation >= 300){
											$rank = "color: #436EEE;";
										}
										
									}else{
										$color = "color: #FF0000;";
									}
									//Change if staff
										
									//Moderators
									if($access > 1 && $access < 4){
										$rank = "color: #e59400;";
											
									//Administrators
									}else if($access > 3 && $access < 6){
										$rank = "color: #ff0000;";
											
									//Founders
									}else if($access > 5){
										$rank = "color: #dbba08;"; 
									}
									if($VIP > 0 || $access == 6 || $access == 5){
										$vipbold = "font-weight: bold;";
									}
								
						?>
							<tr class="gradeX">
							<td><center><span style="<?php _echo($rank); ?> <?php _echo($vipbold); ?>"><?php _echo($username); ?></span> (<a href="<?php _echo("reputation.php?id=".$id);?>"><span style="<?php _echo($color); ?>font-weight: bold;"><?php _echo($reputation); ?></span></a>) </center></td>
							<td><center><span style="<?php _echo($rep_color); ?> font-weight: bold;" ><?php _echo($formatted);?></span></center></td>
							<td><center><?php _echo($description);?></center></td>
							<td><center><?php _echo($record['timestamp']);?></center></td>
							<?php if($self_rep){ ?>
							<td><center>REPORT BUTTON HERE</center></td>
							<?php } ?>
							</tr>
						<?php } ?>
				</tbody>
			</table>
        </div>
    </div>
</div>

<?php
include('._._inc_footer._._.php');
?>