<?php
include('../._._inc_header._._.php');

		//Founder
		$getFounders = $odb->prepare("SELECT * FROM `users` WHERE `access_level` = ?");
		$getFounders->BindValue(1, "6");
		$getFounders->execute();


		//Super Administrators
		$getSuperAdmins = $odb->prepare("SELECT * FROM `users` WHERE `access_level` = ?");
		$getSuperAdmins->BindValue(1, "5");
		$getSuperAdmins->execute();

		//Administrators
		$getAdmins = $odb->prepare("SELECT * FROM `users` WHERE `access_level` = ?");
		$getAdmins->BindValue(1, "4");
		$getAdmins->execute();

		//Super Moderators
		$getSuperMods = $odb->prepare("SELECT * FROM `users` WHERE `access_level` = ?");
		$getSuperMods->BindValue(1, "3");
		$getSuperMods->execute();

		//Moderators
		$getMods = $odb->prepare("SELECT * FROM `users` WHERE `access_level` = ?");
		$getMods->BindValue(1, "2");
		$getMods->execute();
?>


<div id="content">
	<div id="content-header">
		<h1>Staff</h1>
	</div>
	<div id="breadcrumb">
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">Staff</a>
	</div>
	<div class="row">
		<div class="col-xs-8">
		<?php
			if($getFounders->rowCount() > 0){
				echo ("<p><h4>Founders:</h4></p>");
				while($result = $getFounders->fetch(PDO::FETCH_ASSOC)){
					$formatted = preg_replace("/ /","+",$result['username']);
					echo("<p><a href='https://secure.tibia.com/community/?subtopic=characters&name=".$formatted."'>".$result['username']."</a></p>");
				}
			}
		
			if($getSuperAdmins->rowCount() > 0){
				echo ("<p><h4>Super Administrators:</h4></p>");
				while($result = $getSuperAdmins->fetch(PDO::FETCH_ASSOC)){
					$formatted = preg_replace("/ /","+",$result['username']);
					echo("<p><a href='https://secure.tibia.com/community/?subtopic=characters&name=".$formatted."'>".$result['username']."</a></p>");
				}
			}
		
			if($getAdmins->rowCount() > 0){
				echo ("<p><h4>Admins:</h4></p>");
				while($result = $getAdmins->fetch(PDO::FETCH_ASSOC)){
					$formatted = preg_replace("/ /","+",$result['username']);
					echo("<p><a href='https://secure.tibia.com/community/?subtopic=characters&name=".$formatted."'>".$result['username']."</a></p>");
				}
			}
		
			if($getSuperMods->rowCount() > 0){
				echo ("<p><h4>Super Moderators:</h4></p>");
				while($result = $getSuperMods->fetch(PDO::FETCH_ASSOC)){
					$formatted = preg_replace("/ /","+",$result['username']);
					echo("<p><a href='https://secure.tibia.com/community/?subtopic=characters&name=".$formatted."'>".$result['username']."</a></p>");
				}
			}
		
			if($getMods->rowCount() > 0){
				echo ("<p><h4>Moderators:</h4></p>");
				while($result = $getMods->fetch(PDO::FETCH_ASSOC)){
					$formatted = preg_replace("/ /","+",$result['username']);
					echo("<p><a href='https://secure.tibia.com/community/?subtopic=characters&name=".$formatted."'>".$result['username']."</a></p>");
				}
			}
		?>
		</div>
	</div>
</div>

<?php
include('../._._inc_footer._._.php');
?>