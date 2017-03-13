<?php
include('._._inc_header._._.php');
if($TibiaMarket->isLogged() == false){
	$TibiaMarket->redirect("login");
}
if(isset($_POST['item'])){
	$errors = array();

	if( empty($_POST['item']) || empty($_POST['world']) || empty($_POST['character']) || empty($_POST['minbid']) || empty($_POST['length'])){
		$errors[] = "Please fill in all the fields";
	}


	if(!is_numeric($_POST['item']) || !is_numeric($_POST['world']) || !is_numeric($_POST['character']) || !is_numeric($_POST['minbid']) || !is_numeric($_POST['length'])){
		$errors[] = "Verify your inputs.";
	}

	if($TibiaMarket->characterTable($_POST['character'], "userid") != $userData['id']){
		$errors[] = "Character not found";
	}

	if(empty($TibiaMarket->itemTable($_POST['item'], "name"))){
		$errors[] = "Item not found";
	}

	if(empty($TibiaMarket->worldTable($_POST['world'], "name"))){
		$errors[] = "World not found";
	}

	if($_POST['minbid'] < 0){
		$errors[] = "Minimum bid cannot be lower than zero.";
	}

	if(isset($_POST['buyout'])){
		if(empty($_POST['buyout'])){
			$buyout = "0";
		}else{
			if($_POST['buyout'] < 0){
				$errors[] = "Buyout cannot be lower than zero";
			}
		}
	}else{
		$buyout = "0";
	}

	if($_POST['length'] == "1"){
		$length = "3 Days";
	}elseif($_POST['length'] == "2"){
		$length = "5 Days";
	}elseif($_POST['length'] == "3"){
		$length = "7 Days";
	}elseif($_POST['length'] == "4"){
		$length = "14 Days";
	}else{
		$errors[] = "Length is not valid";
	}

	$getVerifiedCharCount = $odb->prepare("SELECT * FROM `characters` WHERE `userid` = ? AND `status` = ?");
	$getVerifiedCharCount->BindValue(1, $userData['id']);
	$getVerifiedCharCount->BindValue(2, "1");
	$getVerifiedCharCount->execute();

	if($getVerifiedCharCount->rowCount() == 0){
		$errors[] = "You do not have a verified character.";
	}

	$item 	= inputSanitize($_POST['item']);
	$world 	= inputSanitize($_POST['world']);
	$char 	= inputSanitize($_POST['character']);
	$minbid = inputSanitize($_POST['minbid']);
	$buyout = inputSanitize($_POST['buyout']);
	$ctnt   = inputSanitize($_POST['message']);


	if(empty($errors)){
		//count the expire

		$nowUnix = time();
		$lengthToAdd = strtotime('+' . $length);

		$insertAuction = $odb->prepare("INSERT INTO `auctions` (`userid`, `item`, `world`, `charid`, `minbid`, `buyout`, `length`, `addedunix`, `expire`, `comment`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$insertAuction->BindValue(1, $userData['id']);
		$insertAuction->BindValue(2, $item);
		$insertAuction->BindValue(3, $world);
		$insertAuction->BindValue(4, $char);
		$insertAuction->BindValue(5, $minbid);
		$insertAuction->BindValue(6, $buyout);
		$insertAuction->BindValue(7, $length);
		$insertAuction->BindValue(8, $nowUnix);
		$insertAuction->BindValue(9, $lengthToAdd);
		$insertAuction->BindValue(10, $ctnt);
		$insertAuction->execute();

	}




}
?>

<div id="content">
	<div id="content-header">
		<h1>My Auctions</h1>
	</div>
	<div id="breadcrumb">
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">My Auctions</a>
	</div>
	<div class="row">

		<div class="col-xs-12">
			<?php
				if(isset($errors)){
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
		</div>

		<div class="col-xs-8">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-trophy"></i>
					</span>
					<h5>My Auctions</h5>
				</div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped table-hover data-table">
						<thead>
							<tr>
								<th>Item</th>
								<th>World</th>
								<th>Character</th>
								<th>Buyout</th>
								<th>Length</th>
								<th>Auction Ends</th>
								<th>Current Bid</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$getMyAuctions = $odb->prepare("SELECT * FROM `auctions` WHERE `userid` = ?");
								$getMyAuctions->BindValue(1, $userData['id']);
								$getMyAuctions->execute();

								while($record = $getMyAuctions->fetch(PDO::FETCH_ASSOC)){
									?>
										<tr>
											<td><center><?php _echo($TibiaMarket->itemTable($record['item'], "name"));?></center></td>
											<td><center><?php _echo($TibiaMarket->worldTable($record['world'], "name"));?></center></td>
											<td><center><?php _echo($TibiaMarket->characterTable($record['charid'], "name"));?></center></td>
											<td>
												<center>
													<?php
														if($record['buyout'] == 0){
															echo '<span class="label label-info">Disabled</span>';
														}else{
															_echo($record['buyout']);
														}
													?>
												</center>
											</td>
											<td><center><?php _echo($record['length']);?></center></td>
											<td><center><?php _echo(date('Y-m-d H:i', $record['expire']));?></center></td>
											<td>
												<center>
													<?php
														$getLastBid = $odb->prepare("SELECT * FROM `bids` WHERE `auctionid` = ? ORDER BY `id` DESC LIMIT 1");
														$getLastBid->BindValue(1, $record['id']);
														$getLastBid->execute();
														$getLastBidF = $getLastBid->fetch(PDO::FETCH_ASSOC);

														if(empty($getLastBidF['bid'])){
															_echo("0");
														}else{
															_echo(($getLastBidF['bid']));
														}
													?>
												</center>
											</td>
											<td style="width:65;">
												<center>
													<a href="auction/<?php _echo($record['id']);?>" class="btn btn-primary btn-xs">View Auction</a>
												</center>
											</td>
										</tr>
									<?php
								}
							?>						
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-xs-4">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-trophy"></i>
					</span>
					<h5>Create Auction</h5>
				</div>
				<div class="widget-content nopadding">
					<?php
						$getVerifiedCharCount = $odb->prepare("SELECT * FROM `characters` WHERE `userid` = ? AND `status` = ?");
						$getVerifiedCharCount->BindValue(1, $userData['id']);
						$getVerifiedCharCount->BindValue(2, "1");
						$getVerifiedCharCount->execute();

						if($getVerifiedCharCount->rowCount() == 0){
							?>
								<div class="alert alert-danger">
									<button type="button" class="close" data-dismiss="alert">×</button>
									You must have a verified character to create an auction.
								</div>

							<?php
						}else{
							?>
								<form action="" method="post" class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-4 control-label">Item</label>
										<div class="col-sm-8">
											<select name="item">
												<?php
													$getAllItems = $odb->prepare("SELECT * FROM `items`");
													$getAllItems->execute();

													while($record = $getAllItems->fetch(PDO::FETCH_ASSOC)){
														?>
															<option value="<?php _echo($record['id']);?>"> <?php _echo($record['name']);?> </option>
														<?php
													}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label">World</label>
										<div class="col-sm-8">
											<select name="world">
												<?php
													$getAllWorlds = $odb->prepare("SELECT * FROM `worlds`");
													$getAllWorlds->execute();

													while($record = $getAllWorlds->fetch(PDO::FETCH_ASSOC)){
														?>
															<option value="<?php _echo($record['id']);?>"> <?php _echo($record['name']);?> </option>
														<?php
													}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label">Character</label>
										<div class="col-sm-8">
											<select name="character">
												<?php
													$getAllChars = $odb->prepare("SELECT * FROM `characters` WHERE `userid` = ?");
													$getAllChars->BindValue(1, $userData['id']);
													$getAllChars->execute();

													while($record = $getAllChars->fetch(PDO::FETCH_ASSOC)){
														?>
															<option value="<?php _echo($record['id']);?>"> <?php _echo($record['name']);?> </option>
														<?php
													}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label">Minimum Bid</label>
										<div class="col-sm-8">
											<input type="text" class="form-control input-sm" name="minbid">
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label">Buyout</label>
										<div class="col-sm-8">
											<input type="text" class="form-control input-sm" name="buyout">
											<span class="help-block text-left">Leave empty to disable</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label">Length</label>
										<div class="col-sm-8">
											<select name="length">
												<option value="1">3 Days</option>
												<option value="2">5 Days</option>
												<option value="3">7 Days</option>
												<option value="4">14 Days</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-4 control-label">Comment / Message</label>
										<div class="col-sm-8">
											<textarea class="form-control input-sm" name="message"></textarea>
											<span class="help-block text-left">You can leave a message or comment that all bidders can see.</span>
										</div>
									</div>

					
									<div class="form-actions">
										<button type="submit" class="btn btn-primary btn-sm">Submit</button>
									</div>
								</form>
							<?php
						}
					?>
				</div>
			</div>
		</div>

	</div>
</div>

<?php
include('._._inc_footer._._.php');
?>