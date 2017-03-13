<?php

$link = 'nope';
include('._-configurations._-/loader.php');

//see if the IP exists for username. 
if($TibiaMarket->isLogged()){
	$userData = $TibiaMarket->userData();
	$id = $userData['id'];
				
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
}

//CHECK TO SEE IF BANNED
$getbanned = $odb->prepare("SELECT * FROM `banned` WHERE `information` = ? && `type` = ?");
$getbanned->BindValue(1, $TibiaMarket->userIP());
$getbanned->BindValue(2, "IP");
$getbanned->execute();
if($getbanned->rowCount() != 0){
	Header("Location: banned");
}

//############################# CRON JOB #############################
$today = time();

$getAllAuctionLowerThanDate = $odb->prepare("SELECT * FROM `auctions` WHERE `expire` < ? AND `status` = ?");
$getAllAuctionLowerThanDate->BindValue(1, $today);
$getAllAuctionLowerThanDate->BindValue(2, "0");
$getAllAuctionLowerThanDate->execute();

while($record = $getAllAuctionLowerThanDate->fetch(PDO::FETCH_ASSOC)){

	//get the highest bidder
	$getLastBid = $odb->prepare("SELECT * FROM `bids` WHERE `auctionid` = ? ORDER BY `id` DESC LIMIT 1");
	$getLastBid->BindValue(1, $record['id']);
	$getLastBid->execute();
	$getLastBidF = $getLastBid->fetch(PDO::FETCH_ASSOC);

	if(empty($getLastBidF)){

	}


	if($getLastBid->rowCount() == 0){
		continue;
	}

	//add notificaitons
	$message = "You have won an auction!";
	$link = $siteURL . "auction/" . $record['id'];
	$insertNotif = $odb->prepare("INSERT INTO `notifications` (`userid`, `message`, `link`, `date`) VALUES(?, ?, ?, ?)");
	$insertNotif->BindValue(1, $getLastBidF['userid']);
	$insertNotif->BindValue(2, $message);
	$insertNotif->BindValue(3, $link);
	$insertNotif->BindValue(4, $TibiaMarket->dateTime());
	$insertNotif->execute();

	print_r($insertNotif->errorInfo());

	//close the bid and set the winner
	$updateAuction = $odb->prepare("UPDATE `auctions` SET `status` = ?, `winner` = ? WHERE `id` = ?");
	$updateAuction->BindValue(1, "1");
	$updateAuction->BindValue(2, $getLastBidF['userid']);
	$updateAuction->BindValue(3, $record['id']);
	$updateAuction->execute();
}
//############################# CRON JOB #############################

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php _echo($siteName);?></title>
	<base href="<?php _echo($siteURL);?>">
	<meta name="description" content="Here you can buy/sell/auction items from the RPG game, Tibia.">
	<meta charset="UTF-8" />
	<meta name=“robots” content=“noindex”>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/font-awesome.css" />
	<link rel="stylesheet" href="css/fullcalendar.css" />
	<link rel="stylesheet" href="css/jquery.jscrollpane.css" />
	<link rel="stylesheet" href="css/select2.css" />
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/jquery.gritter.css" />
	<link rel="stylesheet" href="css/unicorn.css" />
	<link rel="stylesheet" href="css/TMcustom.css" />
	<script src='https://www.google.com/recaptcha/api.js'></script>
	
	<style>
	 .g-recaptcha {
		 width: 100%;
	 }
	 
	</style>
	
	<link href="/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<!--[if lt IE 9]>
		<script type="text/javascript" src="js/respond.min.js"></script>
		<![endif]-->
</head>

<body data-color="grey" class="flat">
	<div id="wrapper">
		<div id="header">
			<h1 style="background-size:contain;left:5px;tio:26px;"><a href="index"><?php _echo($siteName);?></a></h1>
			<a id="menu-trigger" href="#"><i class="fa fa-bars"></i></a>
		</div>
		<div id="user-nav">
			<ul class="btn-group">

				<li class="btn"><a title="" href="#"><i class="fa fa-clock-o"></i> <span class="text">Server Time: <?php _echo($TibiaMarket->dateTime());?></span></a></li>

				<?php if($TibiaMarket->isLogged()){ ?>

					<?php
						$getMyNotifications = $odb->prepare("SELECT * FROM `notifications` WHERE `userid` = ? AND `seen` = ?");
						$getMyNotifications->BindValue(1, $userData['id']);
						$getMyNotifications->BindValue(2, "0");
						$getMyNotifications->execute();

						$updateNotifications = $odb->prepare("UPDATE `notifications` SET `seen` = ? WHERE `userid` = ?");
						$updateNotifications->BindValue(1, "1");
						$updateNotifications->BindValue(2, $userData['id']);
						$updateNotifications->execute();
					?>

					<li class="btn dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="fa fa-envelope"></i> <span class="text">Notifications</span> <span class="label label-danger"><?php _echo($getMyNotifications->rowCount());?></span> <b class="caret"></b></a>
						<ul class="dropdown-menu messages-menu">
							<li class="title"><i class="fa fa-envelope-alt"></i>Notifications</li>

							<?php
								while($record = $getMyNotifications->fetch(PDO::FETCH_ASSOC)){
									?>
									<li class="message-item">
										<a href="<?php _echo($record['link']);?>">
											<img alt="User Icon" src="img/demo/av1.jpg" />
											<div class="message-content">
												<span class="message-time">
													<?php _echo(date('Y-m-d', strtotime($record['date'])));?>
												</span>
			
												<span class="message-sender">
													Auction Information
												</span>
			
												<span class="message">
													<?php _echo($record['message']);?>
												</span>
			
											</div>
										</a>
									</li>
									<?php
								}
							?>
						</ul>
					</li>
					<li class="btn"><a title="" href="settings"><i class="fa fa-cog"></i> <span class="text">Settings</span></a></li>
					<li class="btn"><a title="" href="logout"><i class="fa fa-share"></i> <span class="text">Logout</span></a></li>

				<?php } ?>

			</ul>
		</div>
		<div id="sidebar">
			<ul>
				<li class="">
					<a href="index">
						<i class="fa fa-home"></i><span>Market</span>
					</a>
				</li>

				

				<?php
					if($TibiaMarket->isLogged()){
					?>

						<li class="">
							<a href="myauctions">
								<i class="fa fa-trophy"></i><span>My Auctions</span>
							</a>
						</li>

						<li class="">
							<a href="mybids">
								<i class="fa fa-star"></i><span>My Bids</span>
							</a>
						</li>

						<li class="">
							<a href="characters">
								<i class="fa fa-user"></i><span>My Characters</span>
							</a>
						</li>

						<li class="">
							<a href="contact">
								<i class="fa fa-envelope"></i><span>Contact</span>
							</a>
						</li>

						<li class="">
							<a href="logout">
								<i class="fa fa-share"></i><span>Logout</span>
							</a>
						</li>
						
						
						<?php
						if($TibiaMarket->userTable($userData['id'], "access_level") > 1 && $TibiaMarket->userTable($userData['id'], "access_level") < 7){
						?>
							<li class="">
								<a href="staffpanel">
									<i class="fa fa-flask"></i><span>Staff Panel</span>
								</a>
							</li>
						<?php
						}
						
						
					}else{
					?>
						<li class="">
							<a href="login">
								<i class="fa fa-lock"></i><span>Login</span>
							</a>
						</li>

						<li class="">
							<a href="register">
								<i class="fa fa-unlock-alt"></i><span>Register</span>
							</a>
						</li>

						<li class="">
							<a href="contact">
								<i class="fa fa-envelope"></i><span>Contact</span>
							</a>
						</li>
					<?php
					}
				?>				
			</ul>
		</div>