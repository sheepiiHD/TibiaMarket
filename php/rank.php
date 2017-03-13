<?php
$access = $TibiaMarket->userTable($userData['id'], "access_level");
		$rank;
if($link = 'nope'){
		
		if($access == 6){
			$rank = "Founder";
		}
		if($access == 5){
			$rank = "Super Administrator";
		}
		if($access == 4){
			$rank = "Administrator";
		}
		if($access == 3){
			$rank = "Super Moderator";
		}
		if($access == 2){
			$rank = "Moderator";
		}
		if($access < 2){
			$rank = "User";
		}
		?> 
		
		<?php echo $rank ?>
		
	<?php		
	} else { 
			echo "Someone is where they're not suppose to be...";
		}
?>