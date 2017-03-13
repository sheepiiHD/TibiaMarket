
<?php 

if ($link == 'nope'){
	?>
<div class="widget-box">
	<div class="widget-title">
		<span class="icon pull-right"><i class="fa fa-ellipsis-h"></i></span>
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#tab1">Most Recent</a></li>
			<li class=""><a data-toggle="tab" href="#tab2">TibiaMarket</a></li>
			<li class=""><a data-toggle="tab" href="#tab3">Fansites</a></li>
			<li class=""><a data-toggle="tab" href="#tab4">Cipsoft</a></li>
			<?php if($TibiaMarket->isLogged()){
					$user = $TibiaMarket->userData();
					if($user['access_level'] > 1){ ?>
						<li class=""><a data-toggle="tab" href="#tab5">Staff</a></li>
			<?php } }?>
		</ul>
	</div>
	<div class="widget-content tab-content">
		<div id="tab1" class="tab-pane active">
		<?php
			//Query for all news.
			$newsquery = $odb->prepare("SELECT * FROM `news` WHERE `author` <> ? ORDER BY timestamp DESC LIMIT 5");
			$newsquery->BindValue(1, "Staff");
			$newsquery->execute();
			
			$sess = $TibiaMarket->getSession();
			$query2 = $odb->prepare("SELECT * FROM `users` WHERE `lghash` = ?");
			$query2->BindValue(1, $sess);
			$query2->execute();
			
			$userinfo = $query2->fetch(PDO::FETCH_ASSOC);
			
			while($result = $newsquery->fetch(PDO::FETCH_ASSOC)){
				$formatted = $TibiaMarket->addhttp($result["url"]);
				echo ("<p><b>[".$result["author"]."]</b> - <a class='newsticker' href=".$formatted.">\"".$result["description"]."\" </a>");	
				if($userinfo['access_level'] == 4 && $result["owner"] == $userinfo['username']){
					echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
				}
				if($userinfo['access_level'] > 4 && $userinfo['access_level'] < 7){
					echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
				}
				echo ("</p>");
			}
		?>
		</div>
		<div id="tab2" class="tab-pane">
		<?php
			//Query for TibiaMarket News.
			$newsquery = $odb->prepare("SELECT * FROM `news` WHERE `author` = ? ORDER BY timestamp DESC LIMIT 5");
			$newsquery->BindValue(1, "TibiaMarket");
			$newsquery->execute();
			
			$sess = $TibiaMarket->getSession();
			$query2 = $odb->prepare("SELECT * FROM `users` WHERE `lghash` = ?");
			$query2->BindValue(1, $sess);
			$query2->execute();
			
			$userinfo = $query2->fetch(PDO::FETCH_ASSOC);
			
			while($result = $newsquery->fetch(PDO::FETCH_ASSOC)){
				$formatted = $TibiaMarket->addhttp($result["url"]);
				echo ("<p><b>[".$result["author"]."]</b> - <a class='newsticker' href=".$formatted.">\"".$result["description"]."\" </a>");		
				if($userinfo['access_level'] == 4 && $result["owner"] == $userinfo['username']){
					echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
				}
				if($userinfo['access_level'] > 4 && $userinfo['access_level'] < 7){
					echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
				}
				echo ("</p>");
			}
		?>
		</div>
		<div id="tab3" class="tab-pane">
		<?php
				//Query for fansite news.
			$newsquery = $odb->prepare("SELECT * FROM `news` WHERE `author` <> ? AND `author` <> ? AND `author` <> ? ORDER BY timestamp DESC LIMIT 5");
			$newsquery->BindValue(1, "TibiaMarket");
			$newsquery->BindValue(2, "Cipsoft");
			$newsquery->BindValue(3, "Staff");
			$newsquery->execute();
			
			$sess = $TibiaMarket->getSession();
			$query2 = $odb->prepare("SELECT * FROM `users` WHERE `lghash` = ?");
			$query2->BindValue(1, $sess);
			$query2->execute();
			
			$userinfo = $query2->fetch(PDO::FETCH_ASSOC);
			
			while($result = $newsquery->fetch(PDO::FETCH_ASSOC)){
				$formatted = $TibiaMarket->addhttp($result["url"]);
				echo ("<p><b>[".$result["author"]."]</b> - <a class='newsticker' href=".$formatted.">\"".$result["description"]."\" </a>");		
				if($userinfo['access_level'] == 4 && $result["owner"] == $userinfo['username']){
					echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
				}
				if($userinfo['access_level'] > 4 && $userinfo['access_level'] < 7){
					echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
				}
				echo ("</p>");
			}
		?>
		</div>
		<div id="tab4" class="tab-pane">
		<?php
			//Query for Cipsoft news.
			$newsquery = $odb->prepare("SELECT * FROM `news` WHERE `author` = ? ORDER BY timestamp DESC LIMIT 5");
			$newsquery->BindValue(1, "Cipsoft");
			$newsquery->execute();
			
			$sess = $TibiaMarket->getSession();
			$query2 = $odb->prepare("SELECT * FROM `users` WHERE `lghash` = ?");
			$query2->BindValue(1, $sess);
			$query2->execute();
			
			$userinfo = $query2->fetch(PDO::FETCH_ASSOC);
			
			while($result = $newsquery->fetch(PDO::FETCH_ASSOC)){
				$formatted = $TibiaMarket->addhttp($result["url"]);
				echo ("<p><b>[".$result["author"]."]</b> - <a class='newsticker' href=".$formatted.">\"".$result["description"]."\" </a>");		
				if($userinfo['access_level'] == 4 && $result["owner"] == $userinfo['username']){
					echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
				}
				if($userinfo['access_level'] > 4 && $userinfo['access_level'] < 7){
					echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
				}
				echo ("</p>");
			}
		?>
		</div>
		<?php if($TibiaMarket->isLogged()){
					$user = $TibiaMarket->userData();
					if($user['access_level'] > 1){ ?>
						<div id="tab5" class="tab-pane">
						<?php
							//Query for Cipsoft news.
							$newsquery = $odb->prepare("SELECT * FROM `news` WHERE `author` = ? ORDER BY timestamp DESC LIMIT 5");
							$newsquery->BindValue(1, "Staff");
							$newsquery->execute();
							
							$sess = $TibiaMarket->getSession();
							$query2 = $odb->prepare("SELECT * FROM `users` WHERE `lghash` = ?");
							$query2->BindValue(1, $sess);
							$query2->execute();
							
							$userinfo = $query2->fetch(PDO::FETCH_ASSOC);
							
							while($result = $newsquery->fetch(PDO::FETCH_ASSOC)){
								$formatted = $TibiaMarket->addhttp($result["url"]);
								echo ("<p><b>[".$result["author"]."]</b> - <a class='newsticker' href=".$formatted.">\"".$result["description"]."\" </a>");		
								if($userinfo['access_level'] == 4 && $result["owner"] == $userinfo['username']){
									echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
								}
								if($userinfo['access_level'] > 4 && $userinfo['access_level'] < 7){
									echo('<a href="./staff/delete/'.$result['id'].'" class="tip-top" data-original-title="Delete"><i class="fa fa-times"></i></a>');
								}
								echo ("</p>");
							}
						?>
						</div>
		<?php } }?>
	</div>
</div>
<?php	} else { 
			echo "Someone is where they're not suppose to be...";
		}
?>