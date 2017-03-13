<?php
include('._._inc_header._._.php');

if(isset($_POST['chname'])){
	$errors = array();

	$charName = inputSanitize($_POST['chname']);

	$getAllContent = $TibiaMarket->characterPage($charName);

	preg_match("/Level:<\/td><td>(.*)<\/td><\/tr><tr bgcolor=#F1E0C6><td><nobr>Achievement Points/", $getAllContent, $matches);
			
	array_shift($matches);
	$level = $matches[0];

	if($level < 80){
		$errors[] = "Your character must be higher than level 80.";
	}else{
		$token = $TibiaMarket->token() . $TibiaMarket->token();

		$insertChar = $odb->prepare("INSERT INTO `characters` (`userid`, `name`, `hash`, `status`) VALUES(?, ?, ?, ?)");
		$insertChar->BindValue(1, $userData['id']);
		$insertChar->BindValue(2, $charName);
		$insertChar->BindValue(3, $token);
		$insertChar->BindValue(4, "0");
		$insertChar->execute();
	}
}

if(isset($_GET['deleteCharacter'])){
	if(is_numeric($_GET['deleteCharacter'])){
		$deleteCharacter = $odb->prepare("DELETE FROM `characters` WHERE `id` = ? AND `userid` = ?");
		$deleteCharacter->BindValue(1, $_GET['deleteCharacter']);
		$deleteCharacter->BindValue(2, $userData['id']);
		$deleteCharacter->execute();

		$deleteAllBids = $odb->prepare("DELETE FROM `bids` WHERE `charid` = ? AND `userid` = ?");
		$deleteAllBids->BindValue(1, $_GET['deleteCharacter']);
		$deleteAllBids->BindValue(2, $userData['id']);
		$deleteAllBids->execute();

		$TibiaMarket->redirect("characters");
	}
}

if(isset($_GET['verify'])){
	if(is_numeric($_GET['verify'])){
		$errors = array();

		$getCharName = $odb->prepare("SELECT * FROM `characters` WHERE `id` = ? AND `userid` = ?");
		$getCharName->BindValue(1, $_GET['verify']);
		$getCharName->BindValue(2, $userData['id']);
		$getCharName->execute();
		$getCharNameF = $getCharName->fetch(PDO::FETCH_ASSOC);
		$charName = $getCharNameF['name'];
		$verifyHash = $getCharNameF['hash'];

		$getAllContent = $TibiaMarket->characterPage($charName);

		preg_match("/Comment:<\/td><td>(.*)<\/td><\/tr><tr bgcolor/s", $getAllContent, $matches);

		array_shift($matches);

		$comment = $matches[0];

		if (strpos($comment, $verifyHash) !== false) {
			$updateChar = $odb->prepare("UPDATE `characters` SET `status` = ? WHERE `id` = ? AND `hash` = ?");
			$updateChar->BindValue(1, "1");
			$updateChar->BindValue(2, $_GET['verify']);
			$updateChar->BindValue(3, $verifyHash);
			$updateChar->execute();

			$TibiaMarket->redirect("characters");
		}else{
			$errors[] = "The Verify Hash was not found in the Comment section of your profile on Tibia.";
		}


		// Comment:</td><td>Special Canadian Archer! </td></tr><tr bgcolor=#D4C0A1><td>
	}
}

?>

<div id="content">
	<div id="content-header">
		<h1>My Characters</h1>
	</div>
	<div id="breadcrumb">
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">My Characters</a>
	</div>
	<div class="row">

		<div class="col-xs-12">
			<div class="alert alert-info alert-block">
				<a class="close" data-dismiss="alert" href="#">×</a>
				<center>To verify a character, you must put the verify hash in your comments on Tibia. Then click on Verify to finish the verifying process.</center>
			</div>
		</div>

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
						<i class="fa fa-user"></i>
					</span>
					<h5>My Characters</h5>
				</div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped table-hover data-table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Verify Hash</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$getMyCharacters = $odb->prepare("SELECT * FROM `characters` WHERE `userid` = ?");
								$getMyCharacters->BindValue(1, $userData['id']);
								$getMyCharacters->execute();

								while($record = $getMyCharacters->fetch(PDO::FETCH_ASSOC)){
									?>
										<tr>
											<td><?php _echo($record['name']);?></td>
											<td><?php _echo($record['hash']);?></td>
											<td>
												<center>
													<?php
														if($record['status'] == 0){
															?>
																<span class="label label-danger">Unverified</span>
															<?php
														}else{
															?>
																<span class="label label-success">Verified</span>
															<?php
														}
													?>
												</center>
											</td>
											<td style="width:130px;">
												<center>
													<?php
														if($record['status'] == 0){
															?>
																<a href="characters?verify=<?php _echo($record['id']);?>" class="btn btn-primary btn-xs">Verify</a>
															<?php
														}
													?>
													<a href="characters?deleteCharacter=<?php _echo($record['id']);?>" class="btn btn-danger btn-xs">Remove</a>
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
						<i class="fa fa-user"></i>
					</span>
					<h5>Add Character</h5>
				</div>
				<div class="widget-content nopadding">
					<form action="" method="post" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-4 control-label">Character Name</label>
							<div class="col-sm-8">
								<input type="text" class="form-control input-sm" name="chname">
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