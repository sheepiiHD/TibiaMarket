<?php
include('../._._inc_header._._.php');
?>
<?php 
	$access = $TibiaMarket->userTable($userData['id'], "access_level");	
	$username = $TibiaMarket->userTable($userData['id'], "username");
	
	if($access == 4 && $owner == $username || $access >= 5 && $access <=6){
		
		$query = $odb->prepare("SELECT * FROM `news` WHERE `id` = ?");
		$query->BindValue(1, $_GET['id']);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
						
		$author = 		$result['author'];
		$description = 	$result['description'];
		$time = 		$result['timestamp'];
		$header = 		$result['header'];
		$owner = 		$result['owner'];
		$url = 			$result['url'];
		
		if(isset($_GET['confirmation'])){
			$TibiaMarket->addLog($username." deleted the news: \"".$description."\"");
			
			$del = $odb->prepare("DELETE FROM `news` WHERE `id` = ?");
			$del->BindValue(1, $_GET['id']);
			$del->execute();
			
			Header("Location:http://www.tibiamarket.net/");
		}
		
	}
					
?>
<div id="content">
	<?php if($access == 4 && $owner == $username || $access >= 5 && $access <=6){ ?>
	<div id="content-header">
		<h1>Delete News</h1>
	</div>
	<div id="breadcrumb">
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">Delete</a>
	</div>
	<div class="widget-box">
		<div class="widget-title">
			<span class="icon">
				<i class="fa fa-comment"></i>
			</span>
			<h5>Are you sure you want to delete this news?</h5>
		</div>
		<div class="widget-content" id="gritter-notify">
			
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
						<i class="fa fa-th"></i>
						</span>
						<h5>News information</h5>
					</div>
					<div class="widget-content nopadding">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>Author</th>
									<th>Header</th>
									<th>Description</th>
									<th>URL</th>
									<th>Timestamp</th>
									<th>Owner</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo($author); ?></td>
									<td><?php echo($header); ?></td>
									<td><?php echo($description); ?></td>
									<td><?php echo($url); ?> </td>
									<td><?php echo($time); ?></td>
									<td><?php echo($owner); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			<form method="post">
				<a href="<?php echo('./staff/delete/'.$_GET["id"].'&&confirmation=1')?>" class="sticky btn btn-block btn-dark-green">Yes, delete this news.</a>
			</form>
			<a href="http://www.tibiamarket.net/index" class="normal btn btn-block btn-dark-red">No, do not delete this news.</a>
		</div>
	</div>
	<?php }else{ echo("You do not have permission to be here."); include('../php/rank.php'); } ?>
</div>


<?php
include('../._._inc_footer._._.php');
?>