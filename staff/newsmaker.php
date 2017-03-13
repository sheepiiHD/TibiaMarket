<?php 
if(isset($_POST['author'])){
	$errors = array();

	if( empty($_POST['author']) || empty($_POST['message']) || empty($_POST['url'])){
		$errors[] = "Please fill in all the fields";
	}
	if(empty($errors)){
		
		$author 	= $_POST['author'];
		$message 	= $_POST['message'];
		
		
		$url		= $_POST['url'];
		$username = $TibiaMarket->userTable($userData['id'], "username");
		$access = $TibiaMarket->userTable($userData['id'], "access_level");
		
		$insertNews = $odb->prepare("INSERT INTO `news` (`id`, `author`, `header`, `description`, `url`, `timestamp`, `owner`) VALUES (NULL, ? , ? , ? , ?, CURRENT_TIMESTAMP, ?);");
		// Author
		$insertNews->BindValue(1, $author);
		//Header
		$insertNews->BindValue(2, 'TibiaMarket Staff');
		//Message
		$insertNews->BindValue(3, $message);
		//URL
		$insertNews->BindValue(4, $url);
		//Owner
		$insertNews->BindValue(5, $username);
		
		$insertNews->execute();
		$success = true;
		$TibiaMarket->addLog($username." added the news: \"".$message."\"");
	}
}
if ($link == 'nope'){
	if(isset($errors)){
		if(empty($errors)){
			if($success){
				echo '<div class="alert alert-success">';
				echo '<button type="button" class="close" data-dismiss="alert">×</button>';
				echo '<strong>You have successfully posted a news article. Visit <a href="http://www.tibiamarket.net">the index page </a> to view it!</strong><br>';
				echo '</div>';	
			}				
		}else{
			echo '<div class="alert alert-danger">';
			echo '<button type="button" class="close" data-dismiss="alert">×</button>';
			echo '<strong>Oh snap!</strong><br>';
			foreach($errors as $error){
				echo '-'.$error.'<br />';
			}
			echo '</div>';
		}
	}
?>

<div class="widget-box">
	<div class="widget-title">
		<h5>News Adder</h5>
	</div>
	<div class="widget-content">
		<form action="" method="post" class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-4 control-label">Author</label>
				<div class="col-sm-8">
					<select name="author">
						<option>Staff</option>
						<option>TibiaMarket</option>
						<option>Cipsoft</option>
						<option>TibiaRoyal</option>
						<option>GuildStats.eu</option>
						<option>Portal Tibia</option>
						<option>Tibia-Stats</option>
						<option>TibiaBr</option>
						<option>TibiaEvents</option>
						<option>TibiaLatina.Wikia</option>
						<option>TibiaWiki</option>
						<option>TibiaWiki.com.br</option>
						<option>FunTibia</option>
						<option>Lootpic</option>
						<option>MrThomsen</option>
						<option>Rookie.com.pl</option>
						<option>TibiaBrasileiros</option>
						<option>TibiaCubix</option>
						<option>TibiaGuias</option>
						<option>TibiaHof</option>
						<option>TibiaLibrary</option>
						<option>TibiaLottery</option>
						<option>TibiaML</option>
						<option>TibiaMagazine</option>
						<option>TibiaMisterios</option>
						<option>TibiaWars</option>
						<option>World of Rookgaard</option>										
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Information</label>
				<div class="col-sm-8">
					<textarea class="form-control input-sm" name="message"></textarea>
					<span class="help-block text-left">Here is the body of the news.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">URL</label>
				<div class="col-sm-8">
					<input type="text" class="form-control input-sm" name="url">
					<span class="help-block text-left">Provide a URL so people can navigate to for more information. EX. https://www.tibiamarket.net</span>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-sm">Add News</button>
			</div>
		</form>
	</div>
</div>
<?php	} else { 
			echo "Someone is where they're not suppose to be...";
		}
?>


