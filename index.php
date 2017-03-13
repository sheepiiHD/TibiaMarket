<?php
include('._._inc_header._._.php');

?>
<div id="content">
	<?php include ('./php/newsticker.php'); ?>
			<div class="widget-box">
				<div class="widget-title">
					<span class="icon">
						<i class="fa fa-th"></i>
					</span>
					<h5>Open Auctions</h5>
				</div>
				<div id="specialdiv" style ="position:fixed; z-index:100; bottom:0%; left: 0%; padding:20px; background-color:black; color:white; text-align: center; border: none; display:none;"><b><u>Item Information</u></b></div>
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped table-hover data-table">
						<thead>
							<tr>
								<th>Action</th>
								<th>Item</th>
								<th>Character</th>
								<th>Current Bid</th>
								<th>World</th>
								<th>Auction End Date</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
								
								//STATUS 0 = Normal accepted
								$getOpenMarkets = $odb->prepare("SELECT * FROM `auctions` WHERE `status` = ?");
								$getOpenMarkets->BindValue(1, "0");
								$getOpenMarkets->execute();
								
								while($record = $getOpenMarkets->fetch(PDO::FETCH_ASSOC)){
									//Item information
									$itemid = $TibiaMarket->itemTable($record['item'], "id");
									$getItemInfo = $odb->prepare("SELECT * FROM `items` WHERE `id` = ?");
									$getItemInfo->BindValue(1, $itemid);
									$getItemInfo->execute();
									
									$iteminfo = $getItemInfo->fetch(PDO::FETCH_ASSOC);
									
									
									
									
									$char_name = $TibiaMarket->characterTable($record['charid'], "name");
									
									$getUserReputation = $odb->prepare("SELECT * FROM `users` WHERE `id` = ?");
									$getUserReputation->BindValue(1, $record['userid']);
									$getUserReputation->execute();
									
									$array = $getUserReputation->fetch(PDO::FETCH_ASSOC);
									$reputation = $array['reputation'];
									$VIP = $array['VIP'];
									$access = $array['access_level'];
									//Normal user GREEN
									$rank = "color: #228B22;";
									
									//VIP OR NOT
									$vipbold = "";
									
									if($reputation == 0){
										$color = "color: #000000;";
										
									}else if($reputation > 0){
										$color = "color: #32CD32;";
										
										//Elite users PURPLE
										if($reputation >= 50 && $reputation < 300){
											$rank = "color: #9400D3;";
											
										//Master users ROYAL BLUE
										}else if($reputation >= 300){
											$rank = "color: #436EEE;";
										}
										
									}else{
										$color = "color: #FF0000;";
									}
									//Change if staff
										
									//Moderators
									if($access > 1 && $access < 4){
										$rank = "color: #e59400;";
											
									//Administrators
									}else if($access > 3 && $access < 6){
										$rank = "color: #ff0000;";
											
									//Founders
									}else if($access > 5){
										$rank = "color: #dbba08;"; 
									}
									if($VIP > 0 || $access == 6 || $access == 5){
										$vipbold = "font-weight: bold;";
									}
									
									?>
									<tr class="gradeX">
										<td>
											<center>
												<?php
													if($TibiaMarket->isLogged()){
														echo '<a href="auction/' . $record['id'] . '" class="btn btn-primary btn-xs">Make a bid</a>';
													}else{
														echo '<a href="login" class="btn btn-primary btn-xs">Make a bid</a>';
													}
												?>
											</center>
										</td>
										<td><center><?php echo("<img alt=\"\" src=\"data:image/gif;base64," . $TibiaMarket->itemTable($record['item'], "image") . "\" />  ");echo($TibiaMarket->itemTable($record['item'], "name"));?></center></td>
										<td><center><span style="<?php _echo($rank); ?> <?php _echo($vipbold); ?>"><?php _echo($char_name); ?></span> (<a href="<?php _echo("reputation.php?id=".$record['userid']);?>"><span style="<?php _echo($color); ?> font-weight: bold;"><?php _echo($reputation); ?></span></a>) </center></td>
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
										<td><center><?php _echo($TibiaMarket->worldTable($record['world'], "name"));?></center></td>
										<td><center><?php _echo(date('Y-m-d H:i', $TibiaMarket->auctionTable($record['id'], "expire")));?></center></td>
										<td><center><span class="label label-success">Open</span></center></td>
									</tr>
									<?php
								}
							?>						
						</tbody>
					</table>
				</div>
			</div>
</div>

<?php
include('._._inc_footer._._.php');
?>