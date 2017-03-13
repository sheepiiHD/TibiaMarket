<?php
include('._._inc_header._._.php');
?>

<div id="content">
	<div id="content-header">
		<h1>My Bids</h1>
	</div>
	<div id="breadcrumb">
		<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
		<a href="#" class="current">My Bids</a>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-star"></i>
					</span>
					<h5>My Winning Bids</h5>
				</div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped table-hover data-table">
						<thead>
							<tr>
								<th>Item Name</th>
								<th>World</th>
								<th>My Bid</th>
								<th>Current Bid</th>
								<th>Auction End Date</th>
								<th>Auction Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$getMyBids = $odb->prepare("SELECT m1.* FROM `bids` m1 LEFT JOIN `bids` m2 ON (m1.`auctionid` = m2.`auctionid` AND m1.`id` < m2.`id`) WHERE m1.`userid` = ? AND m2.`id` IS NULL");
								$getMyBids->BindValue(1, $userData['id']);
								$getMyBids->execute();

								while($record = $getMyBids->fetch(PDO::FETCH_ASSOC)){
									?>
									<tr class="gradeX">
										<td><center><?php _echo($TibiaMarket->itemTable($TibiaMarket->auctionTable($record['auctionid'],"item"), "name"));?></center></td>
										<td><center><?php _echo($TibiaMarket->worldTable($TibiaMarket->auctionTable($record['auctionid'],"item"), "name"));?></center></td>
										<td><center><?php _echo($record['bid']);?></center></td>
										<td>
											<center>
												<?php
													$getLastBid = $odb->prepare("SELECT * FROM `bids` WHERE `auctionid` = ? ORDER BY `id` DESC LIMIT 1");
													$getLastBid->BindValue(1, $record['auctionid']);
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
										<td><center><?php _echo(date('Y-m-d H:i', $TibiaMarket->auctionTable($record['auctionid'], "expire")));?></center></td>
										<td>
											<center>
												<?php
													if($TibiaMarket->auctionTable($record['auctionid'], "status") == 0){
														echo '<span class="label label-success">Open</span>';
													}else{
														echo '<span class="label label-warning">Closed</span>';
													}
												?>
											</center>
										</td>
										<td>
											<center>
												<a href="auction/<?php _echo($record['auctionid']);?>" class="btn btn-primary btn-xs">View</a>
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
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-star"></i>
					</span>
					<h5>All of My Bids</h5>
				</div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped table-hover data-table">
						<thead>
							<tr>
								<th>Item Name</th>
								<th>World</th>
								<th>My Bid</th>
								<th>Current Bid</th>
								<th>Auction End Date</th>
								<th>Auction Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$getMyBids = $odb->prepare("SELECT * FROM `bids` WHERE `userid` = ?");
								$getMyBids->BindValue(1, $userData['id']);
								$getMyBids->execute();

								while($record = $getMyBids->fetch(PDO::FETCH_ASSOC)){
									?>
									<tr class="gradeX">
										<td><center><?php _echo($TibiaMarket->itemTable($TibiaMarket->auctionTable($record['auctionid'],"item"), "name"));?></center></td>
										<td><center><?php _echo($TibiaMarket->worldTable($TibiaMarket->auctionTable($record['auctionid'],"item"), "name"));?></center></td>
										<td><center><?php _echo($record['bid']);?></center></td>
										<td>
											<center>
												<?php
													$getLastBid = $odb->prepare("SELECT * FROM `bids` WHERE `auctionid` = ? ORDER BY `id` DESC LIMIT 1");
													$getLastBid->BindValue(1, $record['auctionid']);
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
										<td><center><?php _echo(date('Y-m-d H:i', $TibiaMarket->auctionTable($record['auctionid'], "expire")));?></center></td>
										<td>
											<center>
												<?php
													if($TibiaMarket->auctionTable($record['auctionid'], "status") == 0){
														echo '<span class="label label-success">Open</span>';
													}else{
														echo '<span class="label label-warning">Closed</span>';
													}
												?>
											</center>
										</td>
										<td>
											<center>
												<a href="auction/<?php _echo($record['auctionid']);?>" class="btn btn-primary btn-xs">View</a>
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
	</div>
</div>

<?php
include('._._inc_footer._._.php');
?>