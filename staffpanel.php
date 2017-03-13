<?php
include('._._inc_header._._.php');
if($TibiaMarket->isLogged() == false){
	$TibiaMarket->redirect("login");
}
?>

<div id="content">
	<?php
	if($TibiaMarket->isLogged()){
	?>
	<div id="content-header">
		<h1>Staff Hub</h1>
		<h5>&nbsp&nbsp&nbsp&nbsp&nbspA place for all your staff needs.</h5>
	</div>
	<hr>
	<div id="breadcrumb">
	
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">[Staff Panel] - <?php include("./php/rank.php"); ?></a>
	</div>
	<?php
	}
		if($access >= 2 && $access <= 6){
		?>
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon"><i class="fa fa-ban"></i></span>
					<h5><?php include("./php/rank.php"); ?> Tools</h5>
					<div class="buttons">
						<a href="#" class="btn"><i class="fa fa-refresh"></i> <span class="text">Update options</span></a>
					</div>
				</div>
				<div class="widget-content">
					<?php
					//<!-- Founder widgets --> 
					if($access == 6){ 
						include("./staff/newsmaker.php");
						include("./staff/imageupdater.php");
						include("./staff/staffactivitylog.php");
					//<!-- Super Administrator widgets -->
					} if ($access == 5){ 
						include("./staff/newsmaker.php");
						include("./staff/imageupdater.php");
						include("./staff/staffactivitylog.php");
					//<!-- Administrator widgets -->
					} if ($access == 4){
						include("./staff/newsmaker.php");
						include("./staff/imageupdater.php");
					//<!-- Super Moderator widgets -->
					} if ($access == 3){
					
					//<!-- Moderator widgets -->
					} if ($access == 2){
					
					} ?>
				</div>
			</div>
		<?php 
		}
		if($access == 0 || $access == 1){
			echo "&nbsp&nbsp&nbsp You do not have permission to access this area.";
		}
	?>
</div>

<?php
include('._._inc_footer._._.php');
?>